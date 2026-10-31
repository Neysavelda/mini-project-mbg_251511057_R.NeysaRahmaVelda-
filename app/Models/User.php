<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_PELAPOR = 'pelapor';
    public const ROLE_PEMANTAU = 'pemantau';
    public const ROLE_KOORDINATOR = 'koordinator';

    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function hasRole(string ...$roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    public function roleLabel(): string
    {
        return match ($this->role) {
            self::ROLE_PELAPOR => 'Pelapor',
            self::ROLE_PEMANTAU => 'Pemantau Komunitas',
            self::ROLE_KOORDINATOR => 'Koordinator Komunitas',
            default => ucfirst((string) $this->role),
        };
    }
}