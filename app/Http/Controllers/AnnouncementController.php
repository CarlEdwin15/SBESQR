<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // ✅ Import Log
use App\Events\AnnouncementBroadcasted;
use App\Models\User;
use App\Notifications\AnnouncementNotification;
use App\Services\WebPushService;
use Carbon\Carbon;
use Minishlink\WebPush\WebPush; // ✅ Import WebPush
use Minishlink\WebPush\Subscription; // ✅ Import Subscription
use Illuminate\Support\Facades\Notification;
use NotificationChannels\WebPush\PushSubscription;

class AnnouncementController extends Controller
{

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

        $notifications = Announcement::orderByDesc('date_published')->take(99)->get();

        return view('admin.announcements.index', compact(
            'announcements',
            'schoolYears',
            'defaultSchoolYear',
            'search',
            'schoolYearId',
            'notifications'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'effective_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:effective_date',
            'school_year_id' => 'nullable|exists:school_years,id',
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

        $announcement = Announcement::create($validated);

        if ($announcement->status === 'active') {
            app(WebPushService::class)->broadcast([
                'title' => '📢 New Announcement',
                'body'  => $announcement->title,
                'url'   => route('announcement.redirect', ['id' => $announcement->id]),
                'tag'   => 'announcement-' . $announcement->id,
                'id'    => $announcement->id,
            ]);
        }

        Announcement::where('status', '!=', 'archive')
            ->whereNotNull('end_date')
            ->where('end_date', '<', now())
            ->update(['status' => 'archive']);

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement posted successfully.');
    }

    public function edit(Announcement $announcement)
    {
        $schoolYears = SchoolYear::orderByDesc('school_year')->get();

        // If editing via AJAX modal (from index)
        if (request()->ajax()) {
            return view('admin.announcements.edit', compact('announcement', 'schoolYears'));
        }

        // Full page edit fallback
        return view('admin.announcements.edit', compact('announcement', 'schoolYears'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'body'           => 'required|string',
            'effective_date' => 'nullable|date',
            'end_date'       => 'nullable|date|after_or_equal:effective_date',
            'school_year_id' => 'nullable|exists:school_years,id',
        ]);

        // Ensure a school year (fallback to default if missing)
        if (empty($validated['school_year_id'])) {
            $defaultYear = $this->getDefaultSchoolYear();
            $defaultSchoolYear = SchoolYear::where('school_year', $defaultYear)->first();
            $validated['school_year_id'] = $defaultSchoolYear?->id;
        }

        // Parse effective & end dates like in store()
        $now = now();
        $effective = !empty($validated['effective_date'])
            ? Carbon::parse($validated['effective_date'])->setTimeFrom($now)
            : null;
        $end = !empty($validated['end_date'])
            ? Carbon::parse($validated['end_date'])->endOfDay()
            : null;

        // Status logic consistent with store()
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

        // Re-broadcast if the updated announcement is active
        if ($announcement->status === 'active') {
            app(WebPushService::class)->broadcast([
                'title' => '📢 Updated Announcement',
                'body'  => $announcement->title,
                'url'   => route('announcement.redirect', ['id' => $announcement->id]),
                'tag'   => 'announcement-' . $announcement->id,
                'id'    => $announcement->id,
            ]);
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

        // Optionally broadcast deletion (so clients can remove it from UI)
        // app(WebPushService::class)->broadcast([
        //     'title' => '🗑️ Announcement Deleted',
        //     'body'  => $announcement->title,
        //     'url'   => route('announcements.index'),
        //     'tag'   => 'announcement-' . $announcement->id,
        //     'id'    => $announcement->id,
        // ]);

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

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('announcements', 'public');
            $url = asset('storage/' . $path);

            return response()->json(['url' => $url]);
        }

        return response()->json(['error' => 'No image uploaded'], 400);
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
}
