<?php 
  require '../vendor/autoload.php'; 
  use PhpOffice\PhpSpreadsheet\Spreadsheet;  
  use \PhpOffice\PhpSpreadsheet\IOFactory;

  $spreadsheet = new Spreadsheet();
  $spreadsheet->getProperties()->setCreator("Sevens Ingenieros")->setTitle("Formato Ats");
  
  $spreadsheet->setActiveSheetIndex(0);
  $spreadsheet->getActiveSheet()->getStyle('A:J')->getAlignment()->setVertical('center');
  $spreadsheet->getActiveSheet()->getStyle('A7')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('C2')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('A8:J8')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('A4:A5')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('D5')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('F5')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('I1:I3')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('I5')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
  $hojaActiva = $spreadsheet->getActiveSheet();

  // Add png image to comment background
  $drawing = $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
  $drawing->setName('Paid');
  $drawing->setDescription('Paid');
  $drawing->setPath('../dist/img/logo-principal.png'); // put your path and image here
  $drawing->setCoordinates('A1');
  $drawing->setWidthAndHeight(70, 70);
  $drawing->setOffsetX(11);
  $drawing->setRotation(0);
  $drawing->getShadow()->setVisible(true);
  $drawing->getShadow()->setDirection(45);
  $drawing->setWorksheet($spreadsheet->getActiveSheet());

  // $spreadsheet->getDefaultStyle()->getFont()->setName("Tahoma");
  // $spreadsheet->getDefaultStyle()->getFont()->setSize(15);

  $hojaActiva->mergeCells('A1:B3');
  $hojaActiva->mergeCells('C1:H1');
  $hojaActiva->mergeCells('C2:H3');
  $hojaActiva->mergeCells('C4:J4');
  $hojaActiva->mergeCells('A6:J6');
  $hojaActiva->mergeCells('A7:J7');
  $hojaActiva->mergeCells('B8:C8');
  $hojaActiva->mergeCells('G8:I8');
  $hojaActiva->mergeCells('D8:E8');

  $hojaActiva->setCellValue('C1', 'Formato');
  $hojaActiva->setCellValue('C2', 'ANÁLISIS SEGURO DE TRABAJO (ATS)');

  $hojaActiva->setCellValue('I1', 'CÓDIGO');
  $hojaActiva->setCellValue('I2', 'VERSION');
  $hojaActiva->setCellValue('I3', 'FECHA');

  $hojaActiva->setCellValue('A4', 'Tarea a realizar:');
  $hojaActiva->setCellValue('A5', 'RAZÓN SOCIAL:');

  $hojaActiva->setCellValue('D5', 'RUC:');
  $hojaActiva->setCellValue('F5', 'LUGAR:');
  $hojaActiva->setCellValue('I5', 'N° DE REGISTRO:');

  $hojaActiva->setCellValue('A7', 'EQUIPO DE ATS');

  $hojaActiva->setCellValue('A8', 'N°');
  $hojaActiva->setCellValue('B8', 'Apellidos y Nombres');
  $hojaActiva->setCellValue('D8', 'Firma');

  $hojaActiva->setCellValue('F8', 'N°');
  $hojaActiva->setCellValue('G8', 'Apellidos y Nombres');
  $hojaActiva->setCellValue('J8', 'Firma');

  require_once "../modelos/Formatos_varios.php"; 
  $formatos_varios=new FormatosVarios();  

  $rspta=$formatos_varios->formato_ats($_GET["id_proyecto"]);
  // echo json_encode($rspta, true);
  $cant_array = count($rspta['data']);
  $cant_array_mitad = count($rspta['data'])/2;

  $fila_1 = 9; $fila_2 = 9;

  foreach ($rspta['data'] as $key => $reg) {

    // if ($cant_array_mitad < $cant_array) {
    //   $cant_array_mitad++;
    //   $data_mitad = $rspta['data'][$cant_array_mitad];
    //   (isset($data_mitad) ? $hojaActiva->setCellValue('B'.$fila, '') : $hojaActiva->setCellValue('B'.$fila, $data_mitad[$cant_array_mitad]['trabajador']) );

    //   $hojaActiva->setCellValue('A'.$fila, $reg['trabajador']);

    //   $fila++;
    // }
    
    if ( $cant_array_mitad < $cant_array) {
      $hojaActiva->mergeCells('B'.$fila_1.':C'.$fila_1);
      $hojaActiva->mergeCells('D'.$fila_1.':E'.$fila_1.'');
      $hojaActiva->setCellValue('A'.$fila_1, ($key+1));
      $hojaActiva->setCellValue('B'.$fila_1, $reg['trabajador']);
      $fila_1++;
    } else {
      $hojaActiva->mergeCells('G'.$fila_1.':I'.$fila_1);
      $hojaActiva->setCellValue('F'.$fila_2, ($key+1));
      $hojaActiva->setCellValue('G'.$fila_2, $reg['trabajador']);
      $fila_2++;
    }
    $cant_array_mitad++;
  }

  // redirect output to client browser
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="myfile.xlsx"');
  header('Cache-Control: max-age=0');

  $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
  $writer->save('php://output');

?>
