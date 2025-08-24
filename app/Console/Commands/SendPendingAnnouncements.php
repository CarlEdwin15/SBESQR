<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Announcement;
use App\Services\WebPushService;

class SendPendingAnnouncements extends Command
{
    protected $signature = 'announcements:send-pending';
    protected $description = 'Send push notifications for announcements that became active';

    public function handle()
    {
        $now = now();

        $announcements = Announcement::where('status', 'active')
            ->where('notification_sent', false)
            ->whereNotNull('effective_date')
            ->where('effective_date', '<=', $now)
            ->get();

        foreach ($announcements as $announcement) {
            app(WebPushService::class)->broadcast([
                'title' => '📢 New Announcement',
                'body'  => $announcement->title,
                'url'   => route('announcements.index'),
                'tag'   => 'announcement-' . $announcement->id,
                'id'    => $announcement->id,
            ]);

            $announcement->notification_sent = true;
            $announcement->save();
        }

        $this->info($announcements->count() . ' announcements sent.');
    }
}
