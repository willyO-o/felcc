<?php

namespace App\Lib;

use  App\Lib\FpdfFelcc;

class ReportesPdf extends FpdfFelcc
{




    public function imprimir()
    {
        $this->AddPage('P','Letter');
        $this->SetFont('Arial', 'B', 16);

        $this->Image(public_path('felcc/img/felcc.png'), 10, 10, 30);

        $this->Cell(40, 10, 'Hello World!');
        return $this->Output();
    }
}
