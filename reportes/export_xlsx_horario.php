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

  #$spreadsheet->getActiveSheet()->getStyle('A1:M9')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
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

  require_once "../modelos/Asistencia_obrero.php"; 
  $formatos_varios=new Asistencia_obrero();  
  $rspta=$formatos_varios->mostrar_horario($_GET["id_proyecto"]);   

  $spreadsheet->getActiveSheet()->getStyle('A1:P8')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('B2:H8')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
  $spreadsheet->getActiveSheet()->getStyle('J2:P8')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));

  $fila_1 = 4; $total_h = 0;
  foreach ($rspta['data']['h_normal'] as $key => $reg) { 
    
    $hojaActiva->setCellValue("C$fila_1", $reg['domingo']);
    $hojaActiva->setCellValue("D$fila_1", $reg['lunes']);
    $hojaActiva->setCellValue("E$fila_1", $reg['martes']);
    $hojaActiva->setCellValue("F$fila_1", $reg['miercoles']);
    $hojaActiva->setCellValue("G$fila_1", $reg['jueves']);
    $hojaActiva->setCellValue("H$fila_1", $reg['viernes']);     
    
    if ( $reg['turno'] == 'HORAS ACUMULADAS') {
      $total_h = floatval($reg['domingo']) + floatval($reg['lunes']) + floatval($reg['martes']) + floatval($reg['miercoles']) + floatval($reg['jueves']) + floatval($reg['viernes']) ;
    }   
    
    $fila_1++;    
  }
  $hojaActiva->setCellValue('C'.($fila_1), $total_h );

  # HORARIO EXTRA
  $fila_1 = 4; $total_e = 0;
  foreach ($rspta['data']['h_extra'] as $key => $reg) {     
    
    $hojaActiva->setCellValue("K$fila_1", $reg['domingo']);
    $hojaActiva->setCellValue("L$fila_1", $reg['lunes']);
    $hojaActiva->setCellValue("M$fila_1", $reg['martes']);
    $hojaActiva->setCellValue("N$fila_1", $reg['miercoles']);
    $hojaActiva->setCellValue("O$fila_1", $reg['jueves']);
    $hojaActiva->setCellValue("P$fila_1", $reg['viernes']);     
    
    if ( $reg['turno'] == 'HORAS ACUMULADAS') {
      $total_e = floatval($reg['domingo']) + floatval($reg['lunes']) + floatval($reg['martes']) + floatval($reg['miercoles']) + floatval($reg['jueves']) + floatval($reg['viernes']) ;
    }    
    
    $fila_1++;    
  }

  $hojaActiva->setCellValue('K'.($fila_1), $total_e );


  // redirect output to client browser
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="Horario del trabajador.xlsx"');
  header('Cache-Control: max-age=0');

  $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
  $writer->save('php://output');

?>
