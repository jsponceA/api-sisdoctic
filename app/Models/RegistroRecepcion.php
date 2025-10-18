<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegistroRecepcion extends Model
{
    use SoftDeletes;

    protected $table = "registro_recepciones";
    protected $primaryKey = "id";

    protected $fillable = [
        "creado_por_usuario_id",
        "modificado_por_usuario_id",
        "eliminado_por_usuario_id",
        // PROYECTO ASOCIADO
        "proyecto_id",
        // DATOS DE RECEPCIÓN
        "fecha_emision",
        "fecha_recepcion",
        "fecha_entrega_area",
        "tipo_documento_id",
        "num_doc_recep",
        "asunto",
        "tipo_documento_clasificacion_id",
        "especialidad_id",
        "destino",
        // DATOS DE RESPUESTA
        "fecha_respuesta",
        "num_docs_resp",
        "atencion",
        "acciones_observaciones",
        // ESTADO DE RESPUESTA
        "prioridad",
        "situacion",
        "num_dias_sin_responder",
        "fecha_para_responder",
        "estado_documento",
    ];

    protected $appends = [
        "fecha_creacion_format",
        "fecha_modificacion_format",
        "fecha_emision_format",
        "fecha_recepcion_format",
        "fecha_entrega_area_format",
        "fecha_respuesta_format",
        "fecha_para_responder_format",
    ];

    // Relaciones principales
    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class, "proyecto_id", "id");
    }

    public function tipoDocumento(): BelongsTo
    {
        return $this->belongsTo(TipoDocumento::class, "tipo_documento_id", "id");
    }

    public function tipoDocumentoClasificacion(): BelongsTo
    {
        return $this->belongsTo(TipoDocumentoClasificacion::class, "tipo_documento_clasificacion_id", "id");
    }

    public function especialidad(): BelongsTo
    {
        return $this->belongsTo(Especialidad::class, "especialidad_id", "id");
    }

    // Relaciones de tablas relacionadas
    public function documentosAdjuntos(): HasMany
    {
        return $this->hasMany(RegistroRecepcionDocumento::class, "registro_recepcion_id", "id");
    }

    public function documentosRespuesta(): HasMany
    {
        return $this->hasMany(RegistroRecepcionDocumentoRespuesta::class, "registro_recepcion_id", "id");
    }

    // Relaciones de auditoría
    public function creadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, "creado_por_usuario_id", "id");
    }

    public function modificadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, "modificado_por_usuario_id", "id");
    }

    public function eliminadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, "eliminado_por_usuario_id", "id");
    }

    // Atributos computados
    protected function fechaCreacionFormat(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => !empty($attributes['created_at'])
                ? now()->parse($attributes['created_at'])->format("d/m/Y h:i A")
                : null
        );
    }

    protected function fechaModificacionFormat(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => !empty($attributes['updated_at'])
                ? now()->parse($attributes['updated_at'])->format("d/m/Y h:i A")
                : null
        );
    }

    protected function fechaEmisionFormat(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => !empty($attributes['fecha_emision'])
                ? now()->parse($attributes['fecha_emision'])->format("d/m/Y")
                : null
        );
    }

    protected function fechaRecepcionFormat(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => !empty($attributes['fecha_recepcion'])
                ? now()->parse($attributes['fecha_recepcion'])->format("d/m/Y")
                : null
        );
    }

    protected function fechaEntregaAreaFormat(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => !empty($attributes['fecha_entrega_area'])
                ? now()->parse($attributes['fecha_entrega_area'])->format("d/m/Y")
                : null
        );
    }

    protected function fechaRespuestaFormat(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => !empty($attributes['fecha_respuesta'])
                ? now()->parse($attributes['fecha_respuesta'])->format("d/m/Y")
                : null
        );
    }

    protected function fechaParaResponderFormat(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => !empty($attributes['fecha_para_responder'])
                ? now()->parse($attributes['fecha_para_responder'])->format("d/m/Y")
                : null
        );
    }
}
