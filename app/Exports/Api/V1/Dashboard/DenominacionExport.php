<?php

namespace App\Exports\Api\V1\Dashboard;

use App\Services\Api\V1\Dashboard\DenominacionService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DenominacionExport implements FromView,ShouldAutoSize
{
    protected $denominacionService;
    protected $params;
    public function __construct(DenominacionService $denominacionService,array $params = [])
    {
        $this->denominacionService = $denominacionService;
        $this->params = $params;
    }

    public function view(): View
    {
        $denominaciones = $this->denominacionService->queryListado($this->params);
        return view('api.v1.dashboard.reportes.denominacion.listado_excel')->with(compact("denominaciones"));
    }
}
