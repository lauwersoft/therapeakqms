<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserActivity extends Model
{
    public $timestamps = false;

    const TYPE_PAGE_VIEW = 'page_view';
    const TYPE_COMMENT = 'comment';
    const TYPE_REPLY = 'reply';
    const TYPE_RESOLVE_COMMENT = 'resolve_comment';
    const TYPE_UNRESOLVE_COMMENT = 'unresolve_comment';
    const TYPE_DELETE_COMMENT = 'delete_comment';
    const TYPE_EDIT_DOCUMENT = 'edit_document';
    const TYPE_PUBLISH = 'publish';
    const TYPE_DOWNLOAD = 'download';
    const TYPE_FORM_SUBMIT = 'form_submit';
    const TYPE_LOGIN = 'login';

    protected $fillable = [
        'user_id', 'type', 'path', 'doc_id', 'doc_title', 'time_spent',
        'device', 'viewport_w', 'viewport_h', 'browser', 'os',
        'ip', 'country_code', 'asn_number', 'asn_org', 'session_uid', 'browser_uid', 'timezone', 'referrer',
        'user_agent', 'scroll_depth', 'page_title', 'detail',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
