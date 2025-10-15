<?php

namespace App\Exports\Api\V1\Dashboard;

use App\Services\Api\V1\Dashboard\ArticuloService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ArticuloExport implements FromView,ShouldAutoSize
{
    protected $articuloService;
    protected $params;
    public function __construct(ArticuloService $articuloService,array $params = [])
    {
        $this->articuloService = $articuloService;
        $this->params = $params;
    }

    public function view(): View
    {
        $articulos = $this->articuloService->queryListado($this->params);
        return view('api.v1.dashboard.reportes.articulo.listado_excel')->with(compact("articulos"));
    }
}
