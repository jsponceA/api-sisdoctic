<?php

namespace App\Exports\Api\V1\Dashboard;

use App\Services\Api\V1\Dashboard\ProyectoService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProyectoExport implements FromView, ShouldAutoSize
{
    protected $proyectoService;
    protected $params;

    public function __construct(ProyectoService $proyectoService, array $params = [])
    {
        $this->proyectoService = $proyectoService;
        $this->params = $params;
    }

    public function view(): View
    {
        $proyectos = $this->proyectoService->queryListado($this->params);
        return view('api.v1.dashboard.reportes.proyecto.listado_excel')->with(compact("proyectos"));
    }
}

