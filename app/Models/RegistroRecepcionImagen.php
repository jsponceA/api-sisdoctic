<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class RegistroRecepcionImagen extends Model
{
    protected $table = "registro_recepcion_imagenes";
    protected $primaryKey = "id";
    public $timestamps = false;

    protected $fillable = [
        "registro_recepcion_id",
        "foto",
        "descripcion_foto",
        "tipo_foto",
    ];

    protected $appends = [
        "foto_url",
    ];

    public function registroRecepcion(): BelongsTo
    {
        return $this->belongsTo(RegistroRecepcion::class, "registro_recepcion_id", "id");
    }

    protected function fotoUrl(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => !empty($attributes['foto'])
                ? Storage::url("dashboard/registro-recepciones/{$attributes['foto']}")
                : null
        );
    }
}
