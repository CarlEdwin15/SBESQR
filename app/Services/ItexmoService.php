<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ItexmoService
{
    protected $apiCode;
    protected $password;
    protected $sender;
    protected $endpoint = 'https://www.itexmo.com/php_api/api.php'; // common endpoint used in examples

    public function __construct()
    {
        $this->apiCode  = config('services.itexmo.code');
        $this->password = config('services.itexmo.password'); // optional
        $this->sender   = config('services.itexmo.sender_id'); // optional
    }

    /**
     * iTexMo examples and community packages usually use local "09..." format
     * so convert common stored formats (e.g. +63...) -> 09...
     */
    protected function toItexmoLocalFormat(string $phone): string
    {
        $p = preg_replace('/[^0-9+]/', '', trim($phone));


        // Replace str_starts_with($p, '+63') with:
        if (Str::startsWith($p, '+63')) {
            return '0' . substr($p, 3);
        }

        if (Str::startsWith($p, '63') && strlen($p) === 11) {
            return '0' . substr($p, 2);
        }

        // 9XXXXXXXXX -> 09XXXXXXXXX
        if (preg_match('/^9\d{9}$/', $p)) {
            return '0' . $p;
        }

        // already 09XXXXXXXXX or other fallback
        return $p;
    }

    /**
     * Send SMS. Returns array with ['success' => bool, 'code' => int|null, 'raw' => string]
     * According to community examples, iTexMo returns numeric codes (0 => success).
     * See error code mapping in the package README. :contentReference[oaicite:5]{index=5}
     */
    public function sendSMS(string $to, string $message, ?string $sender = null): array
    {
        $toLocal = $this->toItexmoLocalFormat($to);

        // iTexMo examples often expect the 1/2/3 POST keys (recipient, message, apicode)
        $payload = [
            '1' => $toLocal,
            '2' => $message,
            '3' => $this->apiCode,
        ];

        // include password if given by your provider/account
        if ($this->password) {
            $payload['passwd'] = $this->password;
        }

        // include a sender if configured (some accounts support custom sender IDs)
        $senderToUse = $sender ?: $this->sender;
        if ($senderToUse) {
            $payload['sender'] = $senderToUse;
        }

        try {
            $response = Http::asForm()->post($this->endpoint, $payload);
            $body = trim((string) $response->body());

            // Common community examples and package readme: 0 => success, otherwise numeric error code.
            if ($response->successful() && ($body === '0' || $body === '')) {
                return ['success' => true, 'code' => 0, 'raw' => $body];
            }

            // Numeric error
            if (is_numeric($body)) {
                return ['success' => false, 'code' => (int)$body, 'raw' => $body];
            }

            // Unexpected response
            return ['success' => false, 'code' => null, 'raw' => $body];
        } catch (\Exception $e) {
            Log::error('ItexmoService::sendSMS exception: ' . $e->getMessage(), [
                'to' => $toLocal,
                'payload' => $payload,
            ]);
            return ['success' => false, 'code' => null, 'raw' => $e->getMessage()];
        }
    }
}
