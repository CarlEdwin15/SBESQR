<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Events\AnnouncementBroadcasted;
use App\Models\User;
use App\Notifications\AnnouncementNotification;
use App\Services\WebPushService;
use App\Services\SemaphoreService;
use Carbon\Carbon;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use Illuminate\Support\Facades\Notification;
use NotificationChannels\WebPush\PushSubscription;

class AnnouncementController extends Controller
{
    protected $semaphoreService;

    public function __construct(SemaphoreService $semaphoreService)
    {
        $this->semaphoreService = $semaphoreService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $schoolYearId = $request->input('school_year');

        $query = Announcement::with('schoolYear', 'user')->orderByDesc('created_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%");
            });
        }

        if ($schoolYearId) {
            $query->where('school_year_id', $schoolYearId);
        }

        $announcements = $query->get()->map(function ($announcement) {
            $now = now();
            $effective = $announcement->effective_date ? \Carbon\Carbon::parse($announcement->effective_date) : null;
            $end = $announcement->end_date ? \Carbon\Carbon::parse($announcement->end_date)->endOfDay() : null;

            if ($effective && $end) {
                if ($now->between($effective, $end)) {
                    $status = 'active';
                } elseif ($now->gt($end)) {
                    $status = 'archive';
                } else {
                    $status = 'inactive';
                }
            } elseif ($effective) {
                $status = $now->gte($effective) ? 'active' : 'inactive';
            } elseif ($end && $now->gt($end)) {
                $status = 'archive';
            } else {
                $status = 'inactive';
            }

            // Add computed properties
            $announcement->computed_status = $status;
            $announcement->formatted_published = $announcement->date_published ? \Carbon\Carbon::parse($announcement->date_published)->format('M d, Y | l | h:i A') : 'Draft';
            $announcement->formatted_effective = $effective?->format('M d, Y | l') ?? 'N/A';
            $announcement->formatted_end = $end?->format('M d, Y | l') ?? 'N/A';
            $announcement->author_name = $announcement->user?->firstName ?? 'Unknown';

            return $announcement;
        });

        $schoolYearIdsWithAnnouncements = Announcement::select('school_year_id')
            ->distinct()
            ->pluck('school_year_id');

        $schoolYears = SchoolYear::whereIn('id', $schoolYearIdsWithAnnouncements)
            ->orderByDesc('school_year')
            ->get();

        $defaultYear = $this->getDefaultSchoolYear();
        $defaultSchoolYear = SchoolYear::where('school_year', $defaultYear)->first();

        $user = Auth::user();

        $user = Auth::user();
        $notifications = collect(); // default empty collection
        if ($user && $user->id) {  // Added check for $user->id to prevent null access
            $notifications = Announcement::whereHas('recipients', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
                ->orderByDesc('date_published')
                ->take(99)
                ->get();
        }

        return view('admin.announcements.index', compact(
            'announcements',
            'schoolYears',
            'defaultSchoolYear',
            'search',
            'schoolYearId',
            'notifications'
        ));
    }

    // In AnnouncementController.php - Update the store method
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'effective_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:effective_date',
            'school_year_id' => 'nullable|exists:school_years,id',
            'recipients' => 'required|array|min:1',
            'recipients.*' => 'exists:users,id',
        ]);

        if (empty($validated['school_year_id'])) {
            $defaultYear = $this->getDefaultSchoolYear();
            $defaultSchoolYear = SchoolYear::where('school_year', $defaultYear)->first();
            $validated['school_year_id'] = $defaultSchoolYear?->id;
        }

        $validated['user_id'] = Auth::id();
        $validated['date_published'] = now();

        $now = now();
        $effective = !empty($validated['effective_date']) ? Carbon::parse($validated['effective_date'])->setTimeFrom($now) : null;
        $end = !empty($validated['end_date']) ? Carbon::parse($validated['end_date'])->endOfDay() : null;

        if ($effective && $now->lt($effective)) {
            $validated['status'] = 'inactive';
        } elseif ($end && $now->gt($end)) {
            $validated['status'] = 'archive';
        } else {
            $validated['status'] = 'active';
        }

        $validated['effective_date'] = $effective;
        $validated['end_date'] = $end;

        // Create announcement
        $announcement = Announcement::create($validated);

        // Sync recipients - CRITICAL FIX: Save selected users to pivot table
        if ($request->has('recipients')) {
            $recipientIds = array_unique($request->input('recipients'));
            $announcement->recipients()->sync($recipientIds);

            // Log for debugging
            Log::info('Announcement recipients saved', [
                'announcement_id' => $announcement->id,
                'recipients_count' => count($recipientIds),
                'recipient_ids' => $recipientIds
            ]);
        } else {
            // This should not happen due to validation, but handle it
            Log::warning('No recipients provided for announcement', ['announcement_id' => $announcement->id]);
        }

        // Archive expired announcements
        Announcement::where('status', '!=', 'archive')
            ->whereNotNull('end_date')
            ->where('end_date', '<', now())
            ->update(['status' => 'archive']);

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement posted successfully.');
    }

    // Also update the update() method similarly:
    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'body'           => 'required|string',
            'effective_date' => 'nullable|date',
            'end_date'       => 'nullable|date|after_or_equal:effective_date',
            'school_year_id' => 'nullable|exists:school_years,id',
            'recipients'     => 'required|array|min:1',
            'recipients.*'   => 'exists:users,id',
        ]);

        // Ensure a school year (fallback to default if missing)
        if (empty($validated['school_year_id'])) {
            $defaultYear = $this->getDefaultSchoolYear();
            $defaultSchoolYear = SchoolYear::where('school_year', $defaultYear)->first();
            $validated['school_year_id'] = $defaultSchoolYear?->id;
        }

        // Parse effective & end dates
        $now = now();
        $effective = !empty($validated['effective_date'])
            ? Carbon::parse($validated['effective_date'])->setTimeFrom($now)
            : null;
        $end = !empty($validated['end_date'])
            ? Carbon::parse($validated['end_date'])->endOfDay()
            : null;

        // Status logic
        if ($effective && $now->lt($effective)) {
            $validated['status'] = 'inactive';
        } elseif ($end && $now->gt($end)) {
            $validated['status'] = 'archive';
        } else {
            $validated['status'] = 'active';
        }

        $validated['effective_date'] = $effective;
        $validated['end_date'] = $end;

        $announcement->update($validated);

        // Handle recipients - CRITICAL FIX
        if ($request->has('recipients')) {
            $recipientIds = array_unique($request->input('recipients'));
            $announcement->recipients()->sync($recipientIds);

            Log::info('Announcement recipients updated', [
                'announcement_id' => $announcement->id,
                'recipients_count' => count($recipientIds)
            ]);
        }

        // Cleanup expired announcements
        Announcement::where('status', '!=', 'archive')
            ->whereNotNull('end_date')
            ->where('end_date', '<', now())
            ->update(['status' => 'archive']);

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'title' => 'required|string|max:255',
    //         'body' => 'required|string',
    //         'effective_date' => 'nullable|date',
    //         'end_date' => 'nullable|date|after_or_equal:effective_date',
    //         'school_year_id' => 'nullable|exists:school_years,id',
    //     ]);

    //     if (empty($validated['school_year_id'])) {
    //         $defaultYear = $this->getDefaultSchoolYear();
    //         $defaultSchoolYear = SchoolYear::where('school_year', $defaultYear)->first();
    //         $validated['school_year_id'] = $defaultSchoolYear?->id;
    //     }

    //     $validated['user_id'] = Auth::id();
    //     $validated['date_published'] = now();

    //     $now = now();
    //     $effective = !empty($validated['effective_date']) ? Carbon::parse($validated['effective_date'])->setTimeFrom($now) : null;
    //     $end = !empty($validated['end_date']) ? Carbon::parse($validated['end_date'])->endOfDay() : null;

    //     if ($effective && $now->lt($effective)) {
    //         $validated['status'] = 'inactive';
    //     } elseif ($end && $now->gt($end)) {
    //         $validated['status'] = 'archive';
    //     } else {
    //         $validated['status'] = 'active';
    //     }

    //     $validated['effective_date'] = $effective;
    //     $validated['end_date'] = $end;

    //     $announcement = Announcement::create($validated);

    //     // $smsResult = null;
    //     // $recipientsForSms = collect();

    //     // // Handle recipients and send SMS notifications
    //     // if ($request->filled('recipients')) {
    //     //     $recipientIds = array_unique($request->input('recipients'));
    //     //     $announcement->recipients()->sync($recipientIds);

    //     //     // Get recipients with phone numbers
    //     //     $recipientsForSms = User::whereIn('id', $recipientIds)
    //     //         ->whereNotNull('phone')
    //     //         ->get();

    //     //     // Send SMS notifications
    //     //     $smsResult = $this->sendSmsNotifications($recipientsForSms, $announcement);
    //     // } else {
    //     //     // Send to all users with phone numbers (if no specific recipients selected)
    //     //     $recipientsForSms = User::whereNotNull('phone')->get();
    //     //     $smsResult = $this->sendSmsNotifications($recipientsForSms, $announcement);
    //     // }

    //     // Archive expired announcements
    //     Announcement::where('status', '!=', 'archive')
    //         ->whereNotNull('end_date')
    //         ->where('end_date', '<', now())
    //         ->update(['status' => 'archive']);

    //     // Prepare success message
    //     // $successMessage = 'Announcement posted successfully.';
    //     // if ($smsResult === true) {
    //     //     $successMessage .= ' SMS notifications sent to ' . $recipientsForSms->count() . ' recipient(s).';
    //     // } elseif ($smsResult === false) {
    //     //     $successMessage .= ' SMS sending failed.';
    //     // } else {
    //     //     $successMessage .= ' No SMS notifications sent (no valid phone numbers).';
    //     // }

    //     return redirect()->route('announcements.index')
    //         ->with('success', 'Announcement posted successfully.'); // . $successMessage);
    // }

    public function edit($id)
    {
        $announcement = Announcement::with(['recipients'])->findOrFail($id);
        $schoolYears = SchoolYear::all();

        // Get default school year for consistent form behavior
        $defaultYear = $this->getDefaultSchoolYear();
        $defaultSchoolYear = SchoolYear::where('school_year', $defaultYear)->first();

        // Build recipients data for JavaScript
        $recipientsData = $announcement->recipients->map(function ($user) {
            return [
                'id' => $user->id,
                'text' => "{$user->firstName} {$user->lastName} ({$user->email})"
            ];
        });

        return view('admin.announcements.edit', [
            'announcement' => $announcement,
            'schoolYears' => $schoolYears,
            'defaultSchoolYear' => $defaultSchoolYear,
            'recipientsJson' => $recipientsData->toJson()
        ]);
    }

    // public function update(Request $request, Announcement $announcement)
    // {
    //     $validated = $request->validate([
    //         'title'          => 'required|string|max:255',
    //         'body'           => 'required|string',
    //         'effective_date' => 'nullable|date',
    //         'end_date'       => 'nullable|date|after_or_equal:effective_date',
    //         'school_year_id' => 'nullable|exists:school_years,id',
    //     ]);

    //     // Ensure a school year (fallback to default if missing)
    //     if (empty($validated['school_year_id'])) {
    //         $defaultYear = $this->getDefaultSchoolYear();
    //         $defaultSchoolYear = SchoolYear::where('school_year', $defaultYear)->first();
    //         $validated['school_year_id'] = $defaultSchoolYear?->id;
    //     }

    //     // Parse effective & end dates like in store()
    //     $now = now();
    //     $effective = !empty($validated['effective_date'])
    //         ? Carbon::parse($validated['effective_date'])->setTimeFrom($now)
    //         : null;
    //     $end = !empty($validated['end_date'])
    //         ? Carbon::parse($validated['end_date'])->endOfDay()
    //         : null;

    //     // Status logic consistent with store()
    //     if ($effective && $now->lt($effective)) {
    //         $validated['status'] = 'inactive';
    //     } elseif ($end && $now->gt($end)) {
    //         $validated['status'] = 'archive';
    //     } else {
    //         $validated['status'] = 'active';
    //     }

    //     $validated['effective_date'] = $effective;
    //     $validated['end_date'] = $end;

    //     $announcement->update($validated);

    //     // Handle recipients
    //     if ($request->filled('recipients')) {
    //         $recipientIds = array_unique($request->input('recipients'));
    //         $announcement->recipients()->sync($recipientIds);
    //     }

    //     // Cleanup expired announcements → archive
    //     Announcement::where('status', '!=', 'archive')
    //         ->whereNotNull('end_date')
    //         ->where('end_date', '<', now())
    //         ->update(['status' => 'archive']);

    //     return redirect()->route('announcements.index')
    //         ->with('success', 'Announcement updated successfully.');
    // }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }

    public function showAjax($id)
    {
        $announcement = Announcement::with('user')->findOrFail($id);

        return response()->json([
            'title' => $announcement->title,
            'body'  => $announcement->body,
            'author' => $announcement->user?->firstName ?? 'Unknown',
            'published' => $announcement->date_published
                ? \Carbon\Carbon::parse($announcement->date_published)->format('M d, Y h:i A')
                : 'Draft',
        ]);
    }

    public function redirect($id)
    {
        Log::info('=== ANNOUNCEMENT REDIRECT START ===');
        Log::info('Announcement ID: ' . $id);
        Log::info('User authenticated: ' . (Auth::check() ? 'YES' : 'NO'));
        Log::info('User ID: ' . (Auth::id() ?? 'NONE'));

        if (Auth::check()) {
            Log::info('✅ User IS logged in, redirecting to home with announcement_id');

            // Store the announcement ID in session for auto-opening
            session(['notification_announcement_id' => $id]);

            // Redirect to home page - this will trigger the modal auto-open
            return redirect()->route('home');
        } else {
            Log::info('❌ User NOT logged in, storing in session and redirecting to login');

            // Store announcement ID in session for after login
            session(['login_redirect_announcement' => $id]);

            // Redirect to login page
            return redirect()->route('login');
        }
    }

    // Helper function to get the default school year (e.g., "2024-2025")
    private function getDefaultSchoolYear()
    {
        $now = now();
        $year = $now->year;
        $cutoff = now()->copy()->setMonth(6)->setDay(1);
        $start = $now->lt($cutoff) ? $year - 1 : $year;

        return $start . '-' . ($start + 1);
    }

    public function searchUser(Request $request)
    {
        $query = $request->input('q', '');
        $role = $request->input('role', '');

        $users = User::query()
            ->when($role, fn($q) => $q->where('role', $role))
            ->where(function ($q) use ($query) {
                $q->where('firstName', 'like', "%{$query}%")
                    ->orWhere('lastName', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            })
            ->limit(50)
            ->get(['id', 'firstName', 'lastName', 'email', 'phone'])
            ->map(function ($user) {
                return [
                    'id'        => $user->id,
                    'firstName' => $user->firstName ?? '',
                    'lastName'  => $user->lastName ?? '',
                    'email'     => $user->email ?? '',
                    'phone'     => $user->phone ?? '',
                ];
            });

        return response()->json($users);
    }

    /**
     * Send SMS notifications to selected users
     */
    private function sendSmsNotifications($users, $announcement)
    {
        if ($users->isEmpty()) {
            Log::info('No users with phone numbers found for SMS notification');
            return null; // Return null instead of void
        }

        $phoneNumbers = [];
        $usersWithNumbers = [];

        foreach ($users as $user) {
            if ($user->phone) {
                // Format phone number properly
                $phone = preg_replace('/[^0-9]/', '', $user->phone);

                // Convert 09XXXXXXXXX to +639XXXXXXXXX
                if (str_starts_with($phone, '09') && strlen($phone) === 11) {
                    $phone = '+63' . substr($phone, 1);
                } elseif (str_starts_with($phone, '63') && strlen($phone) === 12) {
                    $phone = '+' . $phone;
                } elseif (!str_starts_with($phone, '+63') && strlen($phone) === 10) {
                    $phone = '+63' . $phone;
                }

                // Only add if it's a valid Philippine number
                if (str_starts_with($phone, '+63') && strlen($phone) === 13) {
                    $phoneNumbers[] = $phone;
                    $usersWithNumbers[] = [
                        'user' => $user,
                        'phone' => $phone
                    ];
                } else {
                    Log::warning('Invalid phone number format', [
                        'user_id' => $user->id,
                        'phone' => $user->phone,
                        'formatted' => $phone
                    ]);
                }
            }
        }

        if (empty($phoneNumbers)) {
            Log::info('No valid phone numbers found for SMS notification');
            return null;
        }

        // Send individual SMS to each user with personalized message
        $successCount = 0;
        foreach ($usersWithNumbers as $userData) {
            $user = $userData['user'];
            $phone = $userData['phone'];

            // Create personalized SMS message
            $message = "Hi {$user->firstName}! You have a new announcement! Log in at https://sbesqr.com to view it";

            // Ensure message doesn't exceed 160 characters (standard SMS limit)
            if (strlen($message) > 160) {
                $message = "Hi {$user->firstName}! New announcement! Log in at https://sbesqr.com";
            }

            Log::info('Sending SMS', [
                'to' => $phone,
                'user' => $user->firstName . ' ' . $user->lastName,
                'message_length' => strlen($message),
                'message' => $message
            ]);

            // Send SMS via Semaphore
            try {
                $success = $this->semaphoreService->sendSMS($phone, $message);

                if ($success) {
                    Log::info('SMS sent successfully', [
                        'to' => $phone,
                        'user_id' => $user->id
                    ]);
                    $successCount++;
                } else {
                    Log::error('Failed to send SMS', [
                        'to' => $phone,
                        'user_id' => $user->id
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('SMS sending failed: ' . $e->getMessage(), [
                    'to' => $phone,
                    'user_id' => $user->id
                ]);
            }
        }

        // Return true if at least one SMS was sent successfully
        return $successCount > 0 ? true : false;
    }
}
