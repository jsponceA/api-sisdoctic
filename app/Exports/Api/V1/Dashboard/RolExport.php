<?php

namespace App\Exports\Api\V1\Dashboard;

use App\Services\Api\V1\Dashboard\RolService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RolExport implements FromView,ShouldAutoSize
{
    protected $rolService;
    protected $params;
    public function __construct(RolService $rolService,array $params = [])
    {
        $this->rolService = $rolService;
        $this->params = $params;
    }

    public function view(): View
    {
        $roles = $this->rolService->queryListado($this->params);
        return view('api.v1.dashboard.reportes.rol.listado_excel')->with(compact("roles"));
    }
}
