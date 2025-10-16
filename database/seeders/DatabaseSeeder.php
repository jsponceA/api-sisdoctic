<?php

namespace Database\Seeders;

use App\Models\ConfiguracionEmpresa;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //User::factory(30)->create();
        if (ConfiguracionEmpresa::query()->count() == 0) {
            ConfiguracionEmpresa::query()->create([
                "nombre_corto" => "SICMO",
                "nombre_comercial" => "SISTEMA INTEGRAL DE CONSERVCACION Y MONITOREO",
            ]);
        }

        /*User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);*/

        $permisos = [
            [
                "group_name" => "Tipo materiales",
                "permisos" => [
                    "tipo-materiales-listado",
                    "tipo-materiales-visualizar",
                    "tipo-materiales-crear",
                    "tipo-materiales-modificar",
                    "tipo-materiales-eliminar",
                    "tipo-materiales-reportes",
                ]
            ],

            [
                "group_name" => "Denominaciones",
                "permisos" => [
                    "denominaciones-listado",
                    "denominaciones-visualizar",
                    "denominaciones-crear",
                    "denominaciones-modificar",
                    "denominaciones-eliminar",
                    "denominaciones-reportes",
                ]
            ],

            [
                "group_name" => "Categorias",
                "permisos" => [
                    "categorias-listado",
                    "categorias-visualizar",
                    "categorias-crear",
                    "categorias-modificar",
                    "categorias-eliminar",
                    "categorias-reportes",
                ]
            ],

            [
                "group_name" => "Estados Conservacion",
                "permisos" => [
                    "estados-conservacion-listado",
                    "estados-conservacion-visualizar",
                    "estados-conservacion-crear",
                    "estados-conservacion-modificar",
                    "estados-conservacion-eliminar",
                    "estados-conservacion-reportes",
                ]
            ],

            [
                "group_name" => "Responsables",
                "permisos" => [
                    "responsables-listado",
                    "responsables-visualizar",
                    "responsables-crear",
                    "responsables-modificar",
                    "responsables-eliminar",
                    "responsables-reportes",
                ]
            ],

            [
                "group_name" => "Salas",
                "permisos" => [
                    "salas-listado",
                    "salas-visualizar",
                    "salas-crear",
                    "salas-modificar",
                    "salas-eliminar",
                    "salas-reportes",
                ]
            ],

            [
                "group_name" => "Articulos",
                "permisos" => [
                    "articulos-listado",
                    "articulos-visualizar",
                    "articulos-crear",
                    "articulos-modificar",
                    "articulos-eliminar",
                    "articulos-reportes",
                ]
            ],

            [
                "group_name" => "Intervenciones",
                "permisos" => [
                    "intervenciones-listado",
                    "intervenciones-visualizar",
                    "intervenciones-crear",
                    "intervenciones-modificar",
                    "intervenciones-eliminar",
                    "intervenciones-reportes",
                ]
            ],

            [
                "group_name" => "Roles",
                "permisos" => [
                    "roles-listado",
                    "roles-visualizar",
                    "roles-crear",
                    "roles-modificar",
                    "roles-eliminar",
                    "roles-reportes",
                ]
            ],
            [
                "group_name" => "Usuarios",
                "permisos" => [
                    "usuarios-listado",
                    "usuarios-visualizar",
                    "usuarios-crear",
                    "usuarios-modificar",
                    "usuarios-eliminar",
                    "usuarios-habilitar",
                    "usuarios-deshabilitar",
                    "usuarios-reportes",
                ]
            ],
            [
                "group_name" => "Proyectos",
                "permisos" => [
                    "proyectos-listado",
                    "proyectos-visualizar",
                    "proyectos-crear",
                    "proyectos-modificar",
                    "proyectos-eliminar",
                    "proyectos-habilitar",
                    "proyectos-deshabilitar",
                    "proyectos-reportes",
                ]
            ],

            [
                "group_name" => "Registro de Recepciones",
                "permisos" => [
                    "registro-recepciones-listado",
                    "registro-recepciones-visualizar",
                    "registro-recepciones-crear",
                    "registro-recepciones-modificar",
                    "registro-recepciones-eliminar",
                    "registro-recepciones-habilitar",
                    "registro-recepciones-deshabilitar",
                    "registro-recepciones-reportes",
                ]
            ],
            [
                "group_name" => "Empresa",
                "permisos" => ["empresa-modificar"]
            ]

        ];

        foreach ($permisos as $permiso) {
            if (!Permission::query()->where("group_name", $permiso["group_name"])->exists()) {
                foreach ($permiso["permisos"] as $p) {
                    Permission::query()->create(["name" => $p, "group_name" => $permiso["group_name"]]);
                }
            }
        }

        $rol = Role::query()->create(["name" => "administrador"]);
        $rol->syncPermissions(Permission::query()->pluck("name")->toArray());
        $usuario = User::query()->create([
            'username' => "admin",
            'password' => bcrypt(123456),
            'nombres' => "Appnom",
            'apellidos' => "Appape",
            'correo' => "sistemas@gmail.com",
            'estado' => 1,
        ]);
        $usuario->assignRole($rol->name);

    }
}
