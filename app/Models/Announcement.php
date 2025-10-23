<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'body',
        'school_year_id',
        'user_id',
        'effective_date',
        'end_date',
        'date_published',
        'status',
    ];

    protected $casts = [
        'date_published' => 'datetime',
        'effective_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    // New method to calculate status dynamically
    public function getStatus(): string
    {
        $now = now();

        if ($this->effective_date && $this->end_date) {
            $start = $this->effective_date->isToday() ? $now : $this->effective_date;
            $end = $this->end_date->endOfDay();

            if ($now->between($start, $end)) {
                return 'active';
            } elseif ($now->gt($end)) {
                return 'archive';
            } else {
                return 'inactive';
            }
        }

        if ($this->effective_date) {
            return $now->gte($this->effective_date) ? 'active' : 'inactive';
        }

        return 'inactive';
    }

    public function recipients()
    {
        return $this->belongsToMany(User::class, 'announcement_user', 'announcement_id', 'user_id')
            ->withPivot('read_at')
            ->withTimestamps();
    }
}
