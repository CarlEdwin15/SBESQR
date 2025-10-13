<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PhilSmsService
{
    protected $apiToken;
    protected $baseUrl = 'https://app.philsms.com/api/v3';

    public function __construct()
    {
        $this->apiToken = config('services.philsms.token');
    }

    protected function headers(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->apiToken,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * Send SMS message via PhilSMS API
     */
    public function sendSMS(string $to, string $message, string $senderId = 'SBESQR'): bool
    {
        // Format phone number (Philippines standard)
        $to = preg_replace('/[^0-9]/', '', $to);
        if (str_starts_with($to, '0')) {
            $to = '63' . substr($to, 1);
        } elseif (!str_starts_with($to, '63')) {
            $to = '63' . $to;
        }

        $payload = [
            'recipient' => $to,
            'sender_id' => $senderId,
            'message'   => $message,
        ];

        try {
            $response = Http::withHeaders($this->headers())
                ->post($this->baseUrl . '/sms/send', $payload);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['status']) && in_array(strtolower($data['status']), ['queued', 'pending', 'sent', 'success'])) {
                    return true;
                }
            }

            Log::error('PhilSMS sending failed', [
                'to' => $to,
                'response' => $response->body(),
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('PhilSMS Exception: ' . $e->getMessage(), [
                'to' => $to,
                'message' => $message,
            ]);
            return false;
        }
    }
}
