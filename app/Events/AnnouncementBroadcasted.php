<?php

namespace App\Events;

use App\Models\Announcement;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class AnnouncementBroadcasted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $announcement;
    public $recipients;

    public function __construct(Announcement $announcement, $recipients)
    {
        $this->announcement = $announcement;
        $this->recipients = $recipients;
    }

    // Broadcast on recipient-specific channels
    public function broadcastOn()
    {
        if ($this->recipients === 'all') {
            return [
                new Channel('announcements.teacher'),
                new Channel('announcements.parent'),
                new Channel('announcements.admin'),
            ];
        }

        return new Channel('announcements.' . $this->recipients);
    }


    public function broadcastAs()
    {
        return 'new-announcement';
    }
}
