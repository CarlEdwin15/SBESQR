<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;
use App\Models\ParentInfo;

Broadcast::channel('announcements.teacher', function ($user) {
    // Only allow teachers + admins
    return $user instanceof User && in_array($user->role, ['teacher', 'admin']);
});

Broadcast::channel('announcements.parent', function ($user) {
    if ($user instanceof User && $user->role === 'parent') {
        return true; // logged-in parent
    }

    // OR support guest parent session
    if (session()->has('parent_email')) {
        return ParentInfo::where('parent_email', session('parent_email'))->exists();
    }

    return false;
});
