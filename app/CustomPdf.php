<?php
namespace Apkom;

use setasign\Fpdi\Tcpdf\Fpdi;

class CustomPdf extends FPDI
{
    function Footer()
    {
        // Go to 1.5 cm from bottom
        $this->SetY(-15);
        // Select Helvetica italic 8
        $this->SetFont('Helvetica','',8);
        // Print centered page number
        $this->Cell(0,10,'Page: '.$this->PageNo().' of '.$this->getAliasNbPages(),0,0,'R');
    }
}
?>