<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class Visit extends Model
{
    protected $fillable = [
        'visitor_id',
        'ip_address',
        'city',
        'device_type',
        'user_agent',
        'page_url',
        'referrer',
        'language',
        'timezone',
        'screen',
        'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];
}