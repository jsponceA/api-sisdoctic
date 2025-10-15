<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proyecto extends Model
{
    use SoftDeletes;

    protected $table = "proyectos";
    protected $primaryKey = "id";

    protected $fillable = [
        "creado_por_usuario_id",
        "modificado_por_usuario_id",
        "eliminado_por_usuario_id",
        "categoria_id",
        "responsable_id",
        // I. DATOS DE IDENTIFICACIÓN
        "codigo_proyecto",
        "nombre_proyecto",
        "fecha_inicio",
        "fecha_fin",
        "ubicacion",
        "estado",
        "descripcion",
        // II. DATOS TÉCNICOS
        "presupuesto",
        "fuente_financiamiento",
        "objetivos",
        "alcance",
        "numero_beneficiarios",
        // III. EQUIPO Y RECURSOS
        "equipo_trabajo",
        "recursos_materiales",
        "recursos_tecnologicos",
        // IV. SEGUIMIENTO
        "porcentaje_avance",
        "resultados_obtenidos",
        "dificultades_encontradas",
        "lecciones_aprendidas",
        "observaciones",
        "recomendaciones",
    ];

    protected $appends = [
        "fecha_creacion_format",
        "fecha_modificacion_format",
        "fecha_inicio_format",
        "fecha_fin_format",
    ];

    // Relaciones principales
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, "categoria_id", "id");
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(Responsable::class, "responsable_id", "id");
    }

    // Relaciones de tablas relacionadas
    public function actividades(): HasMany
    {
        return $this->hasMany(ProyectoActividad::class, "proyecto_id", "id");
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(ProyectoDocumento::class, "proyecto_id", "id");
    }

    public function imagenes(): HasMany
    {
        return $this->hasMany(ProyectoImagen::class, "proyecto_id", "id");
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

    protected function fechaInicioFormat(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => !empty($attributes['fecha_inicio'])
                ? now()->parse($attributes['fecha_inicio'])->format("d/m/Y")
                : null
        );
    }

    protected function fechaFinFormat(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => !empty($attributes['fecha_fin'])
                ? now()->parse($attributes['fecha_fin'])->format("d/m/Y")
                : null
        );
    }
}

