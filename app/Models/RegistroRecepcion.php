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
        "proyecto_id",
        "responsable_id",
        "tipo_material_id",
        // I. DATOS DE IDENTIFICACIÓN
        "numero_recepcion",
        "fecha_recepcion",
        "tipo_recepcion",
        "procedencia",
        "remitente",
        "documento_referencia",
        // II. DESCRIPCIÓN DEL MATERIAL
        "descripcion_material",
        "cantidad",
        "unidad_medida",
        "peso_kg",
        "dimensiones",
        "estado_conservacion_ingreso",
        // III. CONDICIONES DE RECEPCIÓN
        "embalaje",
        "documentacion_completa",
        "documentos_adjuntos",
        "registro_fotografico",
        "observaciones_ingreso",
        // IV. UBICACIÓN Y ALMACENAMIENTO
        "ubicacion_temporal",
        "area_almacenamiento",
        "condiciones_ambientales",
        "requiere_tratamiento_urgente",
        "tratamiento_requerido",
        // V. VALORACIÓN Y CLASIFICACIÓN
        "valor_estimado",
        "clasificacion",
        "periodo_cronologico",
        "cultura",
        "patrimonio_cultural",
        // VI. SEGUIMIENTO
        "estado_proceso",
        "acciones_realizadas",
        "fecha_revision",
        "revisado_por",
        "observaciones_finales",
        "recomendaciones",
    ];

    protected $casts = [
        "documentacion_completa" => "boolean",
        "registro_fotografico" => "boolean",
        "requiere_tratamiento_urgente" => "boolean",
        "patrimonio_cultural" => "boolean",
    ];

    protected $appends = [
        "fecha_creacion_format",
        "fecha_modificacion_format",
        "fecha_recepcion_format",
        "fecha_revision_format",
    ];

    // Relaciones principales
    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class, "proyecto_id", "id");
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(Responsable::class, "responsable_id", "id");
    }

    public function tipoMaterial(): BelongsTo
    {
        return $this->belongsTo(TipoMaterial::class, "tipo_material_id", "id");
    }

    // Relaciones de tablas relacionadas
    public function documentos(): HasMany
    {
        return $this->hasMany(RegistroRecepcionDocumento::class, "registro_recepcion_id", "id");
    }

    public function imagenes(): HasMany
    {
        return $this->hasMany(RegistroRecepcionImagen::class, "registro_recepcion_id", "id");
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

    protected function fechaRecepcionFormat(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => !empty($attributes['fecha_recepcion'])
                ? now()->parse($attributes['fecha_recepcion'])->format("d/m/Y")
                : null
        );
    }

    protected function fechaRevisionFormat(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => !empty($attributes['fecha_revision'])
                ? now()->parse($attributes['fecha_revision'])->format("d/m/Y")
                : null
        );
    }
}

