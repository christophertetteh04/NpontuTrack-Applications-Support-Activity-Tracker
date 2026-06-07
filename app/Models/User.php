<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

        protected $fillable = [
        'name',
        'email',
        'password',
        'employee_id',
        'department',
        'phone',
        'designation',
        'role',
        'is_active',
    ];

        protected $hidden = [
        'password',
        'remember_token',
    ];

        protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

        public function getBioAttribute(): string
    {
        $designation = $this->designation ?: ucfirst(str_replace('_', ' ', $this->role));

        return "{$this->name} ({$this->employee_id}) - {$designation}, {$this->department}";
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTeamLead(): bool
    {
        return in_array($this->role, ['admin', 'team_lead'], true);
    }
}
