<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentExport extends Model
{
    protected $fillable = [
        'user_id', 'category', 'status', 'total_docs', 'processed_docs',
        'filename', 'path', 'error',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
