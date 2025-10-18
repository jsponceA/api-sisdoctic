<?php

namespace App\Exports\Api\V1\Dashboard;

use App\Services\Api\V1\Dashboard\EspecialidadService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class EspecialidadExport implements FromView,ShouldAutoSize
{
    protected $especialidadService;
    protected $params;
    public function __construct(EspecialidadService $especialidadService,array $params = [])
    {
        $this->especialidadService = $especialidadService;
        $this->params = $params;
    }

    public function view(): View
    {
        $especialidades = $this->especialidadService->queryListado($this->params);
        return view('api.v1.dashboard.reportes.especialidad.listado_excel')->with(compact("especialidades"));
    }
}

