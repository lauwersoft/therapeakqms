<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $form_id
 * @property string $form_path
 * @property int $user_id
 * @property string $title
 * @property array $data
 * @property string $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property User $user
 */
class FormSubmission extends Model
{
    protected $fillable = [
        'form_id',
        'form_path',
        'user_id',
        'title',
        'data',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
