<?php

namespace App\Events;

use App\Models\Announcement;
use Carbon\Carbon;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class AnnouncementBroadcasted implements ShouldBroadcast
{
    use SerializesModels;

    public $announcement;
    public $recipients;

    public function __construct(Announcement $announcement, $recipients)
    {
        $this->announcement = $announcement;
        $this->recipients = $recipients;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('announcements.' . $this->recipients);
    }

    public function broadcastWith()
    {
        return [
            'title' => $this->announcement->title,
            'body' => strip_tags($this->announcement->body),
            'date' => Carbon::parse($this->announcement->date_published)->format('M d, Y | h:i A'),
        ];
    }
}
