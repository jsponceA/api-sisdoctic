<?php

namespace App\Exports\Api\V1\Dashboard;

use App\Services\Api\V1\Dashboard\EstadoConservacionService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class EstadoConservacionExport implements FromView,ShouldAutoSize
{
    protected $estadoConservacionService;
    protected $params;
    public function __construct(EstadoConservacionService $estadoConservacionService,array $params = [])
    {
        $this->estadoConservacionService = $estadoConservacionService;
        $this->params = $params;
    }

    public function view(): View
    {
        $estadoConservaciones = $this->estadoConservacionService->queryListado($this->params);
        return view('api.v1.dashboard.reportes.estado_conservacion.listado_excel')->with(compact("estadoConservaciones"));
    }
}
