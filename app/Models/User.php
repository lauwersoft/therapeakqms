<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $role
 * @property bool $approved
 * @property ?Carbon $email_verified_at
 * @property string $password
 * @property ?string $remember_token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    const ROLE_ADMIN = 'admin';
    const ROLE_EDITOR = 'editor';
    const ROLE_AUDITOR = 'auditor';

    const ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_EDITOR,
        self::ROLE_AUDITOR,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    const NOTIFICATION_DEFAULTS = [
        'comments' => true,
        'publications' => true,
    ];

    protected $fillable = [
        'name',
        'email',
        'organisation',
        'timezone',
        'track_activity',
        'notifications',
        'password',
        'role',
        'approved',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'approved' => 'boolean',
            'track_activity' => 'boolean',
            'notifications' => 'array',
            'last_active_at' => 'datetime',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isEditor(): bool
    {
        return $this->role === self::ROLE_EDITOR;
    }

    public function isAuditor(): bool
    {
        return $this->role === self::ROLE_AUDITOR;
    }

    public function wantsNotification(string $type): bool
    {
        $prefs = $this->notifications ?? [];
        return $prefs[$type] ?? (self::NOTIFICATION_DEFAULTS[$type] ?? false);
    }

    public function localTime($date): \Carbon\Carbon
    {
        return \Carbon\Carbon::parse($date)->setTimezone($this->timezone ?? 'UTC');
    }
}
