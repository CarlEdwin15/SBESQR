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

        // Query builder
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

        $announcements = $query->get();

        // 🟡 Only get school years that have announcements
        $schoolYearIdsWithAnnouncements = Announcement::select('school_year_id')
            ->distinct()
            ->pluck('school_year_id');

        $schoolYears = SchoolYear::whereIn('id', $schoolYearIdsWithAnnouncements)
            ->orderByDesc('school_year')
            ->get();

        // Get default school year
        $defaultYear = $this->getDefaultSchoolYear();
        $defaultSchoolYear = SchoolYear::where('school_year', $defaultYear)->first();

        // 🔔 Get notifications for the dropdown
        $role = Auth::user()->role ?? 'parent';
        $notifications = Announcement::orderBy('date_published', 'desc')
            ->take(99)
            ->get();

        return view('admin.announcements.index', compact(
            'announcements',
            'schoolYears',
            'defaultSchoolYear',
            'search',
            'schoolYearId',
            'notifications' // ✅ Pass this
        ));
    }

    public function create()
    {
        $schoolYears = SchoolYear::orderByDesc('school_year')->get();

        $defaultYear = $this->getDefaultSchoolYear();
        $defaultSchoolYear = SchoolYear::where('school_year', $defaultYear)->first();

        return view('admin.announcements.create', compact('schoolYears', 'defaultSchoolYear'));
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

        $validated['status'] = (!empty($validated['effective_date']) &&
            $now->gte($validated['effective_date']) &&
            (empty($validated['end_date']) || $now->lte($validated['end_date'])))
            ? 'active' : 'inactive';

        $announcement = Announcement::create($validated);

        // 🔔 Send push to all subscribers
        app(WebPushService::class)->broadcast([
            'title' => '📢 New Announcement',
            'body'  => $announcement->title,
            'url'   => route('announcements.index'), // or a detail page if you have one
            'tag'   => 'announcement-' . $announcement->id,
            'id'    => $announcement->id,
        ]);

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement posted successfully.');
    }

    public function edit(Announcement $announcement)
    {
        $schoolYears = SchoolYear::orderByDesc('school_year')->get();

        if (request()->ajax()) {
            return view('admin.announcements._form', compact('announcement', 'schoolYears'));
        }

        return view('admin.announcements.edit', compact('announcement', 'schoolYears'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'effective_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:effective_date',
            'school_year_id' => 'nullable|exists:school_years,id',
        ]);

        // Status logic
        $now = now();
        if (
            !empty($validated['effective_date']) && !empty($validated['end_date']) &&
            $now->between($validated['effective_date'], $validated['end_date'])
        ) {
            $validated['status'] = 'active';
        } else {
            $validated['status'] = 'inactive';
        }

        $announcement->update($validated);

        return redirect()->route('announcements.index')->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()->route('announcements.index')->with('success', 'Announcement deleted.');
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

    public function pusher()
    {
        return view('teacher.pusher');
    }
}
