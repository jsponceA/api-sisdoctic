<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ConfiguracionEmpresa extends Model
{
    protected $table = 'configuracion_empresas';
    protected $primaryKey = "id";

    protected $fillable = [
        'modificado_por_usuario_id',
        'nombre_comercial',
        'nombre_corto',
        'ruc',
        'telefonos',
        'correo',
        'direccion',
        'logo',
        'favicon'
    ];

    protected $appends = [
        "logo_url",
        "favicon_url"
    ];

    protected function logoUrl(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => !empty($attributes['logo']) ? Storage::url("dashboard/configuracion-empresa/{$attributes['logo']}") : null,
        );
    }

    protected function faviconUrl(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => !empty($attributes['favicon']) ? Storage::url("dashboard/configuracion-empresa/{$attributes['favicon']}") : null,
        );
    }
}
