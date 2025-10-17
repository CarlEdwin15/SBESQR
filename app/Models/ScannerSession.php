<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScannerSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'schedule_id',
        'school_year_id',
        'date',
    ];

    // (Optional) If your table name is non-standard, include this:
    // protected $table = 'scanner_sessions';
}
