<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property string $action
 * @property string $path
 * @property ?array $details
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property User $user
 */
class DocumentChange extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'path',
        'details',
    ];

    protected function casts(): array
    {
        return [
            'details' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
