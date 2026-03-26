<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserActivity extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'path', 'doc_id', 'doc_title', 'time_spent',
        'device', 'viewport_w', 'viewport_h', 'browser', 'os',
        'ip', 'country_code', 'asn', 'session_uid', 'browser_uid', 'referrer',
        'user_agent', 'scroll_depth', 'page_title',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
