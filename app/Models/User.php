<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use App\Models\Team;

use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'balance',
        'email_verified_at'
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
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // Админ имеет полный доступ
        if ($this->role === 'admin') {
            return true;
        }


        // Менеджер имеет доступ только к определённой панели
        if ($this->role === 'manager' && $panel->getId() === 'admin') {
            return true; // Или замените на `false`, если вообще нельзя в админку
        }

        // Если не админ, перенаправляем на страницу "доступ запрещён"
        if ($panel->getId() === 'admin') {
            // Запоминаем предыдущий URL и редиректим на страницу ошибки
            session()->flash('previous_url', url()->previous());
            redirect()->route('no-access')->send();
        }

        return false;
    }

    public function canAccessNavigationGroup(string $group): bool
    {
        if ($this->role === 'manager') {
            return $group === 'Дані матчів'; // Менеджер видит только "Дані матчів"
        }

        return true; // Другие роли видят всё
    }

    public function teams()
    {
        return $this->hasMany(Team::class, 'owner_id');
    }

 
    public function managedTeams()
    {
        return $this->hasMany(Team::class, 'owner_id');
    }

    public function player()
    {
        return $this->hasMany(Player::class, 'user_id');
    }

    public function hasRole($roles)
    {
        if (is_array($roles)) {
            return in_array($this->role, $roles);
        }

        return $this->role === $roles;
    }

}
