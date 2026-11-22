<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name', 'username', 'email', 'password', 'role', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',   // otomatis di-hash saat disimpan
            'is_active' => 'boolean',
        ];
    }

    public function mahasiswa(): HasOne
    {
        return $this->hasOne(Mahasiswa::class);
    }

    public function mentor(): HasOne
    {
        return $this->hasOne(Mentor::class);
    }

    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isMahasiswa(): bool
    {
        return $this->role === 'mahasiswa';
    }

    public function isMentor(): bool
    {
        return $this->role === 'mentor';
    }

    /** Nama route dashboard sesuai role. */
    public function dashboardRoute(): string
    {
        return match ($this->role) {
            'admin' => 'admin.dashboard',
            'mentor' => 'mentor.dashboard',
            default => 'mahasiswa.dashboard',
        };
    }

    /** Huruf awal nama untuk avatar. */
    public function getInisialAttribute(): string
    {
        $kata = preg_split('/\s+/', trim($this->name));
        $huruf = mb_substr($kata[0] ?? '', 0, 1);
        if (count($kata) > 1) {
            $huruf .= mb_substr($kata[1], 0, 1);
        }
        return mb_strtoupper($huruf);
    }

    public function jumlahNotifikasiBaru(): int
    {
        return $this->notifikasi()->whereNull('dibaca_pada')->count();
    }
}
