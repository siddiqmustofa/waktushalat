<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FridayOfficer extends Model
{
    protected $fillable = [
        'mosque_id',
        'date',
        'khatib',
        'imam',
        'muadzin',
        'bilal',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function mosque()
    {
        return $this->belongsTo(Mosque::class);
    }
}

