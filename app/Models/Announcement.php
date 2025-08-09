<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'body',
        'recipients',
        'school_year_id',
        'user_id',
        'created_by',
        'effective_date',
        'end_date',
        'date_published',
        'status',
    ];

    protected $casts = [
        'date_published' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    // Scope for recipient role (teacher, parent, all)
    public function scopeForRole($query, $role)
    {
        return $query->where(function ($q) use ($role) {
            $q->where('recipients', $role)
                ->orWhere('recipients', 'all');
        });
    }

    // Scope for active announcements (based on dates/status)
    public function scopeIsActive($query)
    {
        $now = now();
        return $query->where('status', 'active')
            ->where(function ($q) use ($now) {
                $q->whereNull('effective_date')
                    ->orWhere('effective_date', '<=', $now);
            })->where(function ($q) use ($now) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', $now);
            });
    }
}
