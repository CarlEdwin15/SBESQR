<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    protected $fillable = [
        'payment_id',
        'amount_paid',
        'payment_date',
        'added_by',
        'payment_method',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function getPaymentMethodNameAttribute()
    {
        return match ($this->payment_method) {
            'cash_on_hand' => 'Cash on Hand',
            'gcash' => 'GCash',
            'paymaya' => 'PayMaya',
            default => '—',
        };
    }
}
