<?php

namespace App\Models;

use NotificationChannels\WebPush\PushSubscription as BasePushSubscription;

class PushSubscription extends BasePushSubscription
{
    protected $fillable = [
        'user_id',
        'endpoint',
        'public_key',
        'auth_token',
        'content_encoding',
        'expiration_time',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
