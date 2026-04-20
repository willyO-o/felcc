<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Lib\ReportesPdf;

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
}
