<?php

namespace App\Exports\Api\V1\Dashboard;

use App\Services\Api\V1\Dashboard\UsuarioService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UsuarioExport implements FromView,ShouldAutoSize
{
    private $usuarioService;
    private $params;
    public function __construct(UsuarioService $usuarioService,array $params)
    {
        $this->usuarioService = $usuarioService;
        $this->params = $params;
    }

    public function view():View
    {
        $usuarios = $this->usuarioService->queryListado($this->params);
        return view("api.v1.dashboard.reportes.usuario.listado_excel")->with(compact("usuarios"));
    }
}
