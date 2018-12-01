<?php
require("fpdf/fpdf.php");

Class PDF extends FPDF {
    function Header()
    {
        $this->AddFont('Roboto', '', 'roboto.php');
        $this->SetFont('Roboto','',12);
        $this->Cell(70);
        $this->Cell(50,10,iconv('UTF-8', 'WINDOWS-1250', "Účtenka rezervace" ),0,0,'C');
        $this->Ln(20);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->AddFont('Roboto', '', 'roboto.php');
        $this->SetFont('Roboto','',12);
        $this->Cell(70);
        $this->Cell(50,10,iconv('UTF-8', 'WINDOWS-1250', "Kino Vlašim" ),0,0,'C');
        $this->Ln(20);
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->AddFont('Roboto', '', 'roboto.php');
$pdf->SetFont('Roboto','',12);

$pdf->Cell(70);
$pdf->Cell(50,10,iconv('UTF-8', 'WINDOWS-1250', "Počet vstupenek: " ),1,0,'C');
$pdf->Cell(50,10, $_GET["pocet"] ,1,0,'C');
$pdf->Ln();

$sedadla = $_POST["sedadla"];
$pdf->Cell(50,10,iconv('UTF-8', 'WINDOWS-1250', "Sedadla" ),1,0,'C');
$pdf->Cell(50,10,iconv('UTF-8', 'WINDOWS-1250', $sedadla ),1,0,'C');
$pdf->Ln(20);

$pdf->Output();
?>