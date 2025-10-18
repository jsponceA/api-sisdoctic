<?php

namespace App\Exports\Api\V1\Dashboard;

use App\Services\Api\V1\Dashboard\AreaService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AreaExport implements FromView,ShouldAutoSize
{
    protected $areaService;
    protected $params;
    public function __construct(AreaService $areaService,array $params = [])
    {
        $this->areaService = $areaService;
        $this->params = $params;
    }

    public function view(): View
    {
        $areas = $this->areaService->queryListado($this->params);
        return view('api.v1.dashboard.reportes.area.listado_excel')->with(compact("areas"));
    }
}

