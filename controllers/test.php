<?php
require('fpdf/fpdf.php');
require_once '../classess/DatabaseHandler.php';
require_once '../classess/SystemSettings.php';
require_once '../classess/SessionHandler.php';
require_once '../classess/RoleHandler.php';
$session = new CustomSessionHandler();
$settings=new SystemSettings();
$db = new DatabaseHandler();

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);

$pdf->Image('../assets/img/DMMMSU-Logo-Final-min.png', 35, 10, 20,20);
$pageWidth = $pdf->GetPageWidth();
$textWidth = $pdf->GetStringWidth('Don Mariano Marcos Memorial State University');
$xCoordinate = ($pageWidth - $textWidth) / 2;
$pdf->SetX($xCoordinate);
$pdf->Cell($textWidth, 10, 'Don Mariano Marcos Memorial State University', 0, 1, 'C');
$textWidth = $pdf->GetStringWidth('Auxiliary Unit');
$xCoordinate = ($pageWidth - $textWidth) / 2;
$pdf->SetX($xCoordinate);
$pdf->SetFont('Arial','B',12);
$pdf->Cell($textWidth, 10, 'Auxiliary Unit', 0, 1, 'C');
$pdf->Ln();
$pdf->SetFont('Arial','B',16);
$textWidth = $pdf->GetStringWidth('Report for '.date("Y"));
$xCoordinate = ($pageWidth - $textWidth) / 2;
$pdf->SetX($xCoordinate);
$pdf->Cell($textWidth,10,'Report for '.date("Y"));
// $result = $db->executeQuery($query);
// echo $query;
// if ($result) {
//     $data = array();
//     while ($row = $result->fetch_assoc()) {
//         $data[] = $row;
//     }
//     echo json_encode($data);
//   }
$pdf->Output();
?>
