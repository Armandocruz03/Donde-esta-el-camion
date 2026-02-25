<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public function rol()
    {
        return $this->belongsTo(Rol::class);
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
    'name', 'email', 'password', 'rol_id',
    'telephone', 'adress', 'numero', 'colonia', 'municipio',
    'lat', 'lng', 'ruta_id', 'notifications_enabled',
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
    public function canAccessPanel(\Filament\Panel $panel): bool
        {
            return $this->rol_id !== 3;
        }

    //Relación Ruta
    public function ruta()
        {
            return $this->belongsTo(\App\Models\Ruta::class);
        }
}
