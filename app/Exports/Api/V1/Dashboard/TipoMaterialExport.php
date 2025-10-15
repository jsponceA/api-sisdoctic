<?php

namespace App\Exports\Api\V1\Dashboard;

use App\Services\Api\V1\Dashboard\TipoMaterialService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TipoMaterialExport implements FromView,ShouldAutoSize
{
    protected $tipoMaterialService;
    protected $params;
    public function __construct(TipoMaterialService $tipoMaterialService,array $params = [])
    {
        $this->tipoMaterialService = $tipoMaterialService;
        $this->params = $params;
    }

    public function view(): View
    {
        $tipoMateriales = $this->tipoMaterialService->queryListado($this->params);
        return view('api.v1.dashboard.reportes.tipo_material.listado_excel')->with(compact("tipoMateriales"));
    }
}
