<?php

namespace Voerro\Laravel\VisitorTracker\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $table = 'visitortracker_visits';

    protected $guarded = [];

    protected $casts = [
        'is_ajax' => 'boolean',
        'is_login_attempt' => 'boolean',
        'is_bot' => 'boolean',
        'is_mobile' => 'boolean',
    ];
}
