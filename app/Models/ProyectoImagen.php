<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProyectoImagen extends Model
{
    protected $table = "proyecto_imagenes";
    protected $primaryKey = "id";
    public $timestamps = false;
    protected $fillable = [
        "proyecto_id",
        "foto",
    ];

    protected $appends = [
        "foto_url",
    ];

    protected function fotoUrl(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => !empty($attributes['foto']) ? Storage::url("dashboard/proyectos/{$attributes['foto']}") : null
        );
    }
}
