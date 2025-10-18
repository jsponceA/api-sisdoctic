<?php

namespace App\Exports\Api\V1\Dashboard;

use App\Services\Api\V1\Dashboard\TipoDocumentoService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TipoDocumentoExport implements FromView,ShouldAutoSize
{
    protected $tipoDocumentoService;
    protected $params;
    public function __construct(TipoDocumentoService $tipoDocumentoService,array $params = [])
    {
        $this->tipoDocumentoService = $tipoDocumentoService;
        $this->params = $params;
    }

    public function view(): View
    {
        $tipos_documento = $this->tipoDocumentoService->queryListado($this->params);
        return view('api.v1.dashboard.reportes.tipo_documento.listado_excel')->with(compact("tipos_documento"));
    }
}

