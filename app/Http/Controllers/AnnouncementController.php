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
use Carbon\Carbon;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use Illuminate\Support\Facades\Notification;
use NotificationChannels\WebPush\PushSubscription;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $month = $request->input('month');
        $year = $request->input('year');

        // Default month to current month if not specified
        if (!$month && !$request->has('month')) {
            $month = date('m'); // Current month
        }

        // Default year to current year if not specified
        if (!$year && !$request->has('year')) {
            $year = date('Y'); // Current year
        }

        $query = Announcement::with(['user', 'recipients'])->orderByDesc('date_published');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%");
            });
        }

        // Apply month filter
        if ($month) {
            $query->whereMonth('date_published', $month);
        }

        // Apply year filter
        if ($year) {
            $query->whereYear('date_published', $year);
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

        // Get available years from 2024 and above that have announcements
        $availableYears = $this->getAvailableYears();

        $user = Auth::user();
        $notifications = collect();
        if ($user && $user->id) {
            $notifications = Announcement::whereHas('recipients', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
                ->orderByDesc('date_published')
                ->take(99)
                ->get();
        }

        return view('admin.announcements.index', compact(
            'announcements',
            'search',
            'month',
            'year',
            'availableYears',
            'notifications'
        ));
    }

    /**
     * Get available years from 2024 and above that have announcements
     */
    private function getAvailableYears()
    {
        // Get distinct years from announcements where year >= 2024
        $years = Announcement::selectRaw('YEAR(date_published) as year')
            ->whereNotNull('date_published')
            ->whereYear('date_published', '>=', 2024)
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        // If no years found, add current year
        if (empty($years)) {
            $years = [date('Y')];
        }

        // Ensure current year is included
        $currentYear = date('Y');
        if (!in_array($currentYear, $years) && $currentYear >= 2024) {
            $years[] = $currentYear;
            rsort($years); // Sort descending
        }

        return $years;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'effective_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:effective_date',
        ]);

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

        $announcement = Announcement::create($validated);

        // Handle recipients
        if ($request->filled('recipients')) {
            $recipientIds = array_unique($request->input('recipients'));
            $announcement->recipients()->sync($recipientIds);
        }

        Announcement::where('status', '!=', 'archive')
            ->whereNotNull('end_date')
            ->where('end_date', '<', now())
            ->update(['status' => 'archive']);

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement posted successfully.');
    }

    public function edit($id)
    {
        $announcement = Announcement::with(['recipients'])->findOrFail($id);

        // Build recipients data for JavaScript
        $recipientsData = $announcement->recipients->map(function ($user) {
            return [
                'id' => $user->id,
                'text' => "{$user->firstName} {$user->lastName} ({$user->email})"
            ];
        });

        return view('admin.announcements.edit', [
            'announcement' => $announcement,
            'recipientsJson' => $recipientsData->toJson()
        ]);
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'body'           => 'required|string',
            'effective_date' => 'nullable|date',
            'end_date'       => 'nullable|date|after_or_equal:effective_date',
        ]);

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

        if ($request->filled('recipients')) {
            $recipientIds = array_unique($request->input('recipients'));
            $announcement->recipients()->sync($recipientIds);
        }

        // Cleanup expired announcements → archive
        Announcement::where('status', '!=', 'archive')
            ->whereNotNull('end_date')
            ->where('end_date', '<', now())
            ->update(['status' => 'archive']);

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

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
            ->get(['id', 'firstName', 'lastName', 'email'])
            ->map(function ($user) {
                return [
                    'id'        => $user->id,
                    'firstName' => $user->firstName ?? '',
                    'lastName'  => $user->lastName ?? '',
                    'email'     => $user->email ?? '',
                ];
            });

        return response()->json($users);
    }
}
