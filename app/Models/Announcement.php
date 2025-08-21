<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }
}
