<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PushSubscription extends Model
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
        return $this->belongsTo(\App\Models\User::class);
    }
}
