<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class SemaphoreService
{
    protected $apiKey;
    protected $sender;

    public function __construct()
    {
        $this->apiKey = config('services.semaphore.api_key'); // from config/services.php
        $this->sender = config('services.semaphore.sender');  // e.g., 'SBESQR'
    }

    /**
     * Send SMS via Semaphore API using cURL
     * @param string|array $to Single phone or array of phones (bulk)
     * @param string $message
     * @return bool
     */
    public function sendSMS($to, string $message): bool
    {
        if (is_array($to)) {
            $to = implode(',', $to); // convert array to comma-separated string
        }

        $ch = curl_init();

        $parameters = [
            'apikey'     => $this->apiKey,
            'number'     => $to,
            'message'    => $message,
            'sendername' => $this->sender,
        ];

        curl_setopt($ch, CURLOPT_URL, 'https://semaphore.co/api/v4/messages');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);
        $curlError = curl_error($ch);

        if ($curlError) {
            Log::error('Semaphore cURL Error: ' . $curlError, [
                'to' => $to,
                'message' => $message,
            ]);
            return false;
        }

        $response = json_decode($output, true);

        // Check if all messages returned with success or queued
        if ($response && isset($response[0]['status']) && in_array(strtolower($response[0]['status']), ['queued', 'pending', 'sent'])) {
            return true;
        }

        Log::error('Semaphore SMS failed', [
            'to' => $to,
            'message' => $message,
            'response' => $output,
        ]);

        return false;
    }
}
