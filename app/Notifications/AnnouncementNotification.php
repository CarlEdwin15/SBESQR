<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
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
            'url'             => route('announcement.redirect', ['id' => $this->announcement->id]),
        ];
    }

    /**
     * Payload for Web Push.
     */
    public function toWebPush($notifiable, $notification)
    {
        try {
            // Generate absolute URL for the redirect route
            $redirectUrl = route('announcement.redirect', ['id' => $this->announcement->id]);

            return (new WebPushMessage)
                ->title("📢 " . $this->announcement->title)
                ->body(strip_tags($this->announcement->body))
                ->icon(asset('assetsDashboard/img/icons/announcement.png'))
                ->badge(asset('assetsDashboard/img/icons/badge.png'))
                ->vibrate([100, 50, 100])
                ->tag('announcement-' . $this->announcement->id)
                ->data([
                    'url' => $redirectUrl, // This should now be a full URL like "https://yoursite.com/announcement/redirect/123"
                    'id' => $this->announcement->id,
                ])
                ->action('open', 'View Announcement');
        } catch (\Exception $e) {
            Log::error("WebPush failed for user {$notifiable->id}: " . $e->getMessage());
            return null;
        }
    }
}
