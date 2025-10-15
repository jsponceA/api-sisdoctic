<?php

namespace App\Exports\Api\V1\Dashboard;

use App\Services\Api\V1\Dashboard\RegistroRecepcionService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RegistroRecepcionExport implements FromView, ShouldAutoSize
{
    protected $registroRecepcionService;
    protected $params;

    public function __construct(RegistroRecepcionService $registroRecepcionService, array $params = [])
    {
        $this->registroRecepcionService = $registroRecepcionService;
        $this->params = $params;
    }

    public function view(): View
    {
        $registros = $this->registroRecepcionService->queryListado($this->params);
        return view('api.v1.dashboard.reportes.registro_recepcion.listado_excel')->with(compact("registros"));
    }
}

