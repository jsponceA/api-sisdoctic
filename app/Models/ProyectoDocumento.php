<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProyectoDocumento extends Model
{
    protected $table = "proyecto_documentos";
    protected $primaryKey = "id";
    public $timestamps = false;
    protected $fillable = [
        "proyecto_id",
        "archivo",
    ];

    protected $appends = [
        "archivo_url",
    ];

    protected function archivoUrl(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => !empty($attributes['archivo']) ? Storage::url("dashboard/proyectos/{$attributes['archivo']}") : null
        );
    }
}

