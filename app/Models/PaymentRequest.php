<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    protected $fillable = [
        'payment_id',
        'parent_id',
        'amount_paid',
        'payment_method',
        'reference_number',
        'receipt_image',
        'status',
        'admin_remarks',
        'requested_at',
        'reviewed_at',
        'attempt_number',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    // Add these helper methods
    public static function canMakeRequest($paymentId, $parentId)
    {
        $attemptCount = self::where('payment_id', $paymentId)
            ->where('parent_id', $parentId)
            ->count();

        return $attemptCount < 2;
    }

    public function isNew()
    {
        return $this->status === 'pending' && $this->requested_at->gt(now()->subDay());
    }

    public static function getNextAttemptNumber($paymentId, $parentId)
    {
        $lastAttempt = self::where('payment_id', $paymentId)
            ->where('parent_id', $parentId)
            ->orderBy('attempt_number', 'desc')
            ->first();

        return $lastAttempt ? $lastAttempt->attempt_number + 1 : 1;
    }

    // Accessor for payment method name
    public function getPaymentMethodNameAttribute()
    {
        return match ($this->payment_method) {
            'gcash' => 'GCash',
            'paymaya' => 'PayMaya',
            default => ucfirst($this->payment_method)
        };
    }
}
