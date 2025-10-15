<?php

namespace App\Exports\Api\V1\Dashboard;

use App\Services\Api\V1\Dashboard\IntervencionService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class IntervencionExport implements FromView, ShouldAutoSize
{
    protected $intervencionService;
    protected $params;

    public function __construct(IntervencionService $intervencionService, array $params = [])
    {
        $this->intervencionService = $intervencionService;
        $this->params = $params;
    }

    public function view(): View
    {
        $intervenciones = $this->intervencionService->queryListado($this->params);
        return view('api.v1.dashboard.reportes.intervencion.listado_excel')->with(compact("intervenciones"));
    }
}

