<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class TwilioService
{
    protected $client;
    protected $from;

    public function __construct()
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $this->from = config('services.twilio.from');
        $this->client = new Client($sid, $token);
    }

    public function sendSMS($to, $message)
    {
        try {
            // Format to international if starts with 09
            if (preg_match('/^09\d{9}$/', $to)) {
                $to = '+63' . substr($to, 1);
            }

            $this->client->messages->create($to, [
                'from' => $this->from,
                'body' => $message,
            ]);

            Log::info("📩 SMS sent to {$to}: {$message}");
            return true;
        } catch (\Exception $e) {
            Log::error("❌ SMS failed: " . $e->getMessage());
            return false;
        }
    }
}
