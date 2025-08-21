<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class AnnouncementNotification extends Notification
{
    use Queueable;

    public $announcement;

    public function __construct($announcement)
    {
        $this->announcement = $announcement;
    }

    /**
     * Channels for delivery.
     */
    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    /**
     * Store in database (optional).
     */
    public function toArray($notifiable)
    {
        return [
            'announcement_id' => $this->announcement->id,
            'title'           => $this->announcement->title,
            'body'            => $this->announcement->body,
            'date_published'  => $this->announcement->date_published,
        ];
    }

    /**
     * Payload for Web Push.
     */
    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title("📢 " . $this->announcement->title)
            ->body($this->announcement->body)
            ->icon('/assetsDashboard/img/icons/announcement.png')
            ->badge('/assetsDashboard/img/icons/badge.png')
            ->tag('announcement-' . $this->announcement->id) // ensures grouping
            ->data([
                'url' => url('/announcements/' . $this->announcement->id),
                'id'  => $this->announcement->id,
            ])
            ->action('open', 'View Announcement');
    }
}
