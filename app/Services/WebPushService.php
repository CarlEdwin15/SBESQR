<?php

namespace App\Services;

use App\Models\PushSubscription;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription as WebPushSubscription;

class WebPushService
{
    protected WebPush $webPush;

    public function __construct()
    {
        $this->webPush = new WebPush([
            'VAPID' => [
                'subject' => config('webpush.vapid.subject'),
                'publicKey' => config('webpush.vapid.public_key'),
                'privateKey' => config('webpush.vapid.private_key'),
            ],
        ]);
    }


    public function broadcast(array $payload): void
    {
        PushSubscription::query()->orderBy('id')->chunk(500, function ($subs) use ($payload) {
            foreach ($subs as $sub) {
                $this->webPush->queueNotification(
                    WebPushSubscription::create([
                        'endpoint' => $sub->endpoint,
                        'publicKey' => $sub->public_key,
                        'authToken' => $sub->auth_token,
                        'contentEncoding' => $sub->content_encoding,
                    ]),
                    json_encode($payload)
                );
            }

            foreach ($this->webPush->flush() as $report) {
                if (!$report->isSuccess()) {
                    $status = optional($report->getResponse())->getStatusCode();
                    if (in_array($status, [404, 410])) {
                        $endpoint = $report->getRequest()->getUri()->__toString();
                        PushSubscription::where('endpoint', $endpoint)->delete();
                    }
                }
            }
        });
    }
}
