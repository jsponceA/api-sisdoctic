<?php

namespace App\Exports\Api\V1\Dashboard;

use App\Services\Api\V1\Dashboard\ResponsableService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ResponsableExport implements FromView,ShouldAutoSize
{
    protected $responsableService;
    protected $params;
    public function __construct(ResponsableService $responsableService,array $params = [])
    {
        $this->responsableService = $responsableService;
        $this->params = $params;
    }

    public function view(): View
    {
        $responsables = $this->responsableService->queryListado($this->params);
        return view('api.v1.dashboard.reportes.responsable.listado_excel')->with(compact("responsables"));
    }
}
