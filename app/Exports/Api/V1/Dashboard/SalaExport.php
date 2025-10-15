<?php

namespace App\Exports\Api\V1\Dashboard;

use App\Services\Api\V1\Dashboard\SalaService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SalaExport implements FromView,ShouldAutoSize
{
    protected $salaService;
    protected $params;
    public function __construct(SalaService $salaService,array $params = [])
    {
        $this->salaService = $salaService;
        $this->params = $params;
    }

    public function view(): View
    {
        $salas = $this->salaService->queryListado($this->params);
        return view('api.v1.dashboard.reportes.sala.listado_excel')->with(compact("salas"));
    }
}
