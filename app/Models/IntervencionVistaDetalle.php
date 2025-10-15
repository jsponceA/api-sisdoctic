<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class IntervencionVistaDetalle extends Model
{
    protected $table = "intervencion_vista_detalles";
    protected $primaryKey = "id";
    public $timestamps = false;
    protected $fillable = [
        "intervencion_id",
        "foto",
    ];

    protected $appends = [
        "foto_url",
    ];

    protected function fotoUrl(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => !empty($attributes['foto']) ? Storage::url("dashboard/intervenciones/{$attributes['foto']}") : null
        );
    }

}
