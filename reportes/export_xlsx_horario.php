<?php 
  require '../vendor/autoload.php'; 
  use PhpOffice\PhpSpreadsheet\Spreadsheet;  
  use PhpOffice\PhpSpreadsheet\IOFactory;
  use PhpOffice\PhpSpreadsheet\Style\Border;
  use PhpOffice\PhpSpreadsheet\Style\Color;


  $spreadsheet = new Spreadsheet();
  $spreadsheet->getProperties()->setCreator("Sevens Ingenieros")->setTitle("Horario del personal");
  
  $spreadsheet->setActiveSheetIndex(0);
  $spreadsheet->getActiveSheet()->getStyle('A1:P8')->getAlignment()->setVertical('center');
  $spreadsheet->getActiveSheet()->getStyle('A1:P8')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('B7')->getAlignment()->setWrapText(true);
  $spreadsheet->getActiveSheet()->getStyle('J7')->getAlignment()->setWrapText(true);
  $spreadsheet->getActiveSheet()->getStyle('B')->getFont()->setBold(true);#TURNO
  $spreadsheet->getActiveSheet()->getStyle('J')->getFont()->setBold(true);#TURNO
  $spreadsheet->getActiveSheet()->getStyle('B2:H3')->getFont()->setBold(true); #TITULOS
  $spreadsheet->getActiveSheet()->getStyle('J2:P3')->getFont()->setBold(true); #TITULOS
  
  $spreadsheet->getActiveSheet()->getRowDimension('9')->setRowHeight(35);
  $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5); #VACIO
  $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(5); #VACIO
  $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(15); #TURNO
  $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(15); #TURNO

  $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(12); #DOMINGO
  $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(12); #LUNES
  $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(12); #MARTES
  $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(12); #MIERCOLES
  $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(12); #JUEVES
  $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(12); #VIERNES

  $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(12); #DOMINGO
  $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(12); #LUNES
  $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(12); #MARTES
  $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(12); #MIERCOLES
  $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(12); #JUEVES
  $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(12); #VIERNES

  // $spreadsheet->getActiveSheet()->getStyle('A1:M9')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
  $hojaActiva = $spreadsheet->getActiveSheet();

  $hojaActiva->mergeCells('B2:H2'); #HORARIO 
  $hojaActiva->mergeCells('C8:H8'); #TOTAL HORARIO
  $hojaActiva->mergeCells('B7:B8'); #HORA ACUMULADA
  $hojaActiva->setCellValue('B2', 'HORARIO');
  $hojaActiva->setCellValue('C3', 'DOMINGO');
  $hojaActiva->setCellValue('D3', 'LUNES');
  $hojaActiva->setCellValue('E3', 'MARTES');
  $hojaActiva->setCellValue('F3', 'MIERCOLES');
  $hojaActiva->setCellValue('G3', 'JUEVES');
  $hojaActiva->setCellValue('H3', 'VIERNES');

  $hojaActiva->setCellValue('B4', 'MAÑANA');
  $hojaActiva->setCellValue('B5', 'ALMUERZO');
  $hojaActiva->setCellValue('B6', 'TARDE');
  $hojaActiva->setCellValue('B7', "HORAS \n ACUMULADAS");

  $hojaActiva->mergeCells('J2:P2'); #HORARIO EXTRA  
  $hojaActiva->mergeCells('K8:P8'); #TOTAL HORARIO EXTRA
  $hojaActiva->mergeCells('J7:J8'); #HORA ACUMULADA
  $hojaActiva->setCellValue('J2', 'HORARIO EXTRA');
  $hojaActiva->setCellValue('K3', 'DOMINGO');
  $hojaActiva->setCellValue('L3', 'LUNES');
  $hojaActiva->setCellValue('M3', 'MARTES');
  $hojaActiva->setCellValue('N3', 'MIERCOLES');
  $hojaActiva->setCellValue('O3', 'JUEVES');
  $hojaActiva->setCellValue('P3', 'VIERNES'); 

  $hojaActiva->setCellValue('J4', 'TARDE');
  $hojaActiva->setCellValue('J5', 'CENA');
  $hojaActiva->setCellValue('J6', 'NOCHE');
  $hojaActiva->setCellValue('J7', "HORAS \n ACUMULADAS");

  // require_once "../modelos/Formatos_varios.php"; 
  // $formatos_varios=new FormatosVarios();  
  // $rspta=$formatos_varios->formato_ats($_GET["id_proyecto"]);
  

  // $fila_1 = 10;

  // foreach ($rspta['data'] as $key => $reg) { 
    $spreadsheet->getActiveSheet()->getStyle('A1:P8')->getAlignment()->setHorizontal('center');
    $spreadsheet->getActiveSheet()->getStyle('B2:H8')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
    $spreadsheet->getActiveSheet()->getStyle('J2:P8')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
    // $spreadsheet->getActiveSheet()->getStyle('A'.$fila_1, ($key+1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
    // $spreadsheet->getActiveSheet()->getStyle('B'.$fila_1, $reg['trabajador'])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
     
    $hojaActiva->setCellValue('C4', '8:00 - 12:00');
    $hojaActiva->setCellValue('D4', '7:30 - 12:00');
    $hojaActiva->setCellValue('E4', '7:30 - 12:00');
    $hojaActiva->setCellValue('F4', '7:30 - 12:00');
    $hojaActiva->setCellValue('G4', '7:30 - 12:00');
    $hojaActiva->setCellValue('H4', '7:30 - 12:00');

    $hojaActiva->setCellValue('C5', '');
    $hojaActiva->setCellValue('D5', '12:00 - 1:00');
    $hojaActiva->setCellValue('E5', '12:00 - 1:00');
    $hojaActiva->setCellValue('F5', '12:00 - 1:00');
    $hojaActiva->setCellValue('G5', '12:00 - 1:00');
    $hojaActiva->setCellValue('H5', '12:00 - 1:00');

    $hojaActiva->setCellValue('C6', '');
    $hojaActiva->setCellValue('D6', '1:00 - 5:30');
    $hojaActiva->setCellValue('E6', '1:00 - 5:30');
    $hojaActiva->setCellValue('F6', '1:00 - 5:30');
    $hojaActiva->setCellValue('G6', '1:00 - 5:30');
    $hojaActiva->setCellValue('H6', '1:00 - 4:30');

    $hojaActiva->setCellValue('C7', '4');
    $hojaActiva->setCellValue('D7', '9');
    $hojaActiva->setCellValue('E7', '9');
    $hojaActiva->setCellValue('F7', '9');
    $hojaActiva->setCellValue('G7', '9');
    $hojaActiva->setCellValue('H7', '8');
    $hojaActiva->setCellValue('C8', '48');

    // horario extra

    $hojaActiva->setCellValue('K4', '1:00 - 5:00');
    $hojaActiva->setCellValue('L4', '');
    $hojaActiva->setCellValue('M4', '');
    $hojaActiva->setCellValue('N4', '');
    $hojaActiva->setCellValue('O4', '');
    $hojaActiva->setCellValue('P4', '');

    $hojaActiva->setCellValue('K5', '5:00 - 6:00');
    $hojaActiva->setCellValue('L5', '');
    $hojaActiva->setCellValue('N5', '');
    $hojaActiva->setCellValue('M5', '');
    $hojaActiva->setCellValue('O5', '');
    $hojaActiva->setCellValue('P5', '');

    $hojaActiva->setCellValue('K6', '6:00 - 10:00');
    $hojaActiva->setCellValue('L6', '6:00 - 10:00');
    $hojaActiva->setCellValue('N6', '6:00 - 10:00');
    $hojaActiva->setCellValue('M6', '6:00 - 10:00');
    $hojaActiva->setCellValue('O6', '6:00 - 10:00');
    $hojaActiva->setCellValue('P6', '6:30 - 10:30');

    $hojaActiva->setCellValue('K7', '4');
    $hojaActiva->setCellValue('L7', '4');
    $hojaActiva->setCellValue('N7', '4');
    $hojaActiva->setCellValue('M7', '4');
    $hojaActiva->setCellValue('O7', '4');
    $hojaActiva->setCellValue('P7', '8');
    $hojaActiva->setCellValue('K8', '28');
    
    // $spreadsheet->getActiveSheet()->getStyle('M'.$fila_1)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));

    // $spreadsheet->getActiveSheet()->getRowDimension($fila_1)->setRowHeight(30);
    // $fila_1++;
    
  // }

  // redirect output to client browser
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="Horario del trabajador.xlsx"');
  header('Cache-Control: max-age=0');

  $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
  $writer->save('php://output');

?>
