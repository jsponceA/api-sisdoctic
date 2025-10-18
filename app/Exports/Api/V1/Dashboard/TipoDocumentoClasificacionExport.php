<?php

namespace App\Exports\Api\V1\Dashboard;

use App\Services\Api\V1\Dashboard\TipoDocumentoClasificacionService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TipoDocumentoClasificacionExport implements FromView,ShouldAutoSize
{
    protected $tipoDocumentoClasificacionService;
    protected $params;
    public function __construct(TipoDocumentoClasificacionService $tipoDocumentoClasificacionService,array $params = [])
    {
        $this->tipoDocumentoClasificacionService = $tipoDocumentoClasificacionService;
        $this->params = $params;
    }

    public function view(): View
    {
        $tipos_documento_clasificacion = $this->tipoDocumentoClasificacionService->queryListado($this->params);
        return view('api.v1.dashboard.reportes.tipo_documento_clasificacion.listado_excel')->with(compact("tipos_documento_clasificacion"));
    }
}

