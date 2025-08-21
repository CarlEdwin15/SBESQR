<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PushSubscription;

class PushSubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'endpoint' => 'required|string',
            'keys.p256dh' => 'required|string',
            'keys.auth' => 'required|string',
        ]);

        $sub = PushSubscription::updateOrCreate(
            ['endpoint' => $request->endpoint],
            [
                'user_id'          => Auth::id(),
                'public_key'       => $request->input('keys.p256dh'),
                'auth_token'       => $request->input('keys.auth'),
                'content_encoding' => 'aes128gcm',
                'expiration_time'  => $request->expirationTime,
            ]
        );

        return response()->json(['ok' => true, 'id' => $sub->id]);
    }

    public function destroy(Request $request)
    {
        $request->validate(['endpoint' => 'required|url']);
        PushSubscription::where('endpoint', $request->endpoint)->delete();
        return response()->json(['ok' => true]);
    }
}
