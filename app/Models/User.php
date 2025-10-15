<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable,HasApiTokens, HasRoles, HasFactory, SoftDeletes;

    protected $fillable = [
        'username',
        'password',
        'nombres',
        'apellidos',
        'correo',
        'foto',
        'estado'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        "nombres_apellidos",
        "foto_url",
        "fecha_creacion_format",
        "fecha_modificacion_format"
    ];

    protected function nombresApellidos(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => "{$attributes['nombres']} {$attributes['apellidos']}"
        );
    }

    protected function fotoUrl(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => !empty($attributes['foto']) ? Storage::url("dashboard/usuarios/{$attributes['foto']}") : null,
        );
    }

    protected function fechaCreacionFormat(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => !empty($attributes['created_at']) ? now()->parse($attributes['created_at'])->format("d/m/Y h:i: A") : null
        );
    }

    protected function fechaModificacionFormat(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => !empty($attributes['updated_at']) ? now()->parse($attributes['updated_at'])->format("d/m/Y h:i: A") : null
        );
    }

}
