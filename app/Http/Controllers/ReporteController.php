<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Lib\ReportesPdf;
use App\Models\FichaRegistro;
class ReporteController extends Controller
{
    //

    protected $reportesPdf;
    public function __construct()
    {
        $this->reportesPdf = new ReportesPdf();
    }


    public function index()
    {

        $this->reportesPdf->imprimir();
        exit;
    }
    public function pdfFicha($codigo)
    {
        $fichaRegistro = FichaRegistro::whereRaw('MD5(id) = ?', [$codigo])->firstOrFail();

        $this->reportesPdf->fichaRegistro($fichaRegistro);
        exit;

    }
}
