<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class RegistroRecepcionDocumentoRespuesta extends Model
{
    protected $table = "registro_recepcion_documentos_respuesta";
    protected $primaryKey = "id";
    public $timestamps = false;

    protected $fillable = [
        "registro_recepcion_id",
        "archivo",
        "nombre_original",
    ];

    protected $appends = [
        "archivo_url",
    ];

    public function registroRecepcion(): BelongsTo
    {
        return $this->belongsTo(RegistroRecepcion::class, "registro_recepcion_id", "id");
    }

    protected function archivoUrl(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => !empty($attributes['archivo'])
                ? Storage::url("dashboard/registro-recepciones/{$attributes['archivo']}")
                : null
        );
    }
}

