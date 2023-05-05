<?php 
  require '../vendor/autoload.php'; 
  use PhpOffice\PhpSpreadsheet\Spreadsheet;  
  use PhpOffice\PhpSpreadsheet\IOFactory;
  use PhpOffice\PhpSpreadsheet\Style\Border;
  use PhpOffice\PhpSpreadsheet\Style\Color;


  $spreadsheet = new Spreadsheet();
  $spreadsheet->getProperties()->setCreator("Sevens Ingenieros")->setTitle("Formato Ats");
  
  $spreadsheet->setActiveSheetIndex(0);
  $spreadsheet->getActiveSheet()->getStyle('A:J')->getAlignment()->setVertical('center');
  $spreadsheet->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('A7')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('D8')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('I8')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('C2')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('A8:J8')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('A4:A5')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('D5')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('F5')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('I1:I3')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('I5')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(10);
  $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(35);
  $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
  $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
  $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
  $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(15);
  $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(20);
  $spreadsheet->getActiveSheet()->getStyle('A1:J8')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
  $hojaActiva = $spreadsheet->getActiveSheet();

  // Add png image to comment background
  $drawing = $drawing = new PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
  $drawing->setName('Paid');
  $drawing->setDescription('Paid');
  $drawing->setPath('../dist/img/logo-principal.png'); // put your path and image here
  $drawing->setCoordinates('A1');
  $drawing->setWidthAndHeight(70, 70);
  $drawing->setOffsetY(5);
  $drawing->setOffsetX(30);
  $drawing->setRotation(0);
  $drawing->getShadow()->setVisible(true);
  $drawing->getShadow()->setDirection(45);
  $drawing->setWorksheet($spreadsheet->getActiveSheet());

  // $spreadsheet->getDefaultStyle()->getFont()->setName("Tahoma");
  // $spreadsheet->getDefaultStyle()->getFont()->setSize(15);

  $hojaActiva->mergeCells('A1:B3'); #imagen
  $hojaActiva->mergeCells('A4:B4'); #tarea a realizar
  $hojaActiva->mergeCells('A5:B5'); #razon social
  $hojaActiva->mergeCells('C1:H1'); #formato
  $hojaActiva->mergeCells('C2:H3'); #nombre plantilla 
  $hojaActiva->mergeCells('C4:J4'); #tarea a realizar
  $hojaActiva->mergeCells('G5:H5'); #lugar
  $hojaActiva->mergeCells('A6:J6'); #espacio vacio
  $hojaActiva->mergeCells('A7:J7'); #equipo ats
  $hojaActiva->mergeCells('B8:C8'); #aprellidos y nombres
  $hojaActiva->mergeCells('D8:E8'); #Firma
  $hojaActiva->mergeCells('G8:H8'); #aprellidos y nombres
  $hojaActiva->mergeCells('I8:J8'); #Firma
  
  // ,'Trabajos de construcción'
  $hojaActiva->setCellValue('C1', 'Formato');
  $hojaActiva->setCellValue('C2', 'ANÁLISIS SEGURO DE TRABAJO (ATS)');

  $hojaActiva->setCellValue('I1', 'CÓDIGO:');
  $hojaActiva->setCellValue('I2', 'VERSION:');
  $hojaActiva->setCellValue('I3', 'FECHA:');

  $hojaActiva->setCellValue('A4', 'TAREA A REALIZAR:');
  $hojaActiva->setCellValue('C4', 'TRABAJOS DE CONTRUCCÍON');
  $hojaActiva->setCellValue('A5', 'RAZÓN SOCIAL:');
  $hojaActiva->setCellValue('C5', 'SEVEN´S INGENIEROS SELVA S.A.C.');

  $hojaActiva->setCellValue('D5', 'RUC:');
  $hojaActiva->setCellValue('E5', '20609935651');
  $hojaActiva->setCellValue('F5', 'LUGAR:');
  $hojaActiva->setCellValue('I5', 'N° DE REGISTRO:');

  $hojaActiva->setCellValue('A7', 'EQUIPO DE ATS');

  $hojaActiva->setCellValue('A8', 'N°');
  $hojaActiva->setCellValue('B8', 'Apellidos y Nombres');
  $hojaActiva->setCellValue('D8', 'Firma');

  $hojaActiva->setCellValue('F8', 'N°');
  $hojaActiva->setCellValue('G8', 'Apellidos y Nombres');
  $hojaActiva->setCellValue('I8', 'Firma');

  require_once "../modelos/Formatos_varios.php"; 
  $formatos_varios=new FormatosVarios();  
  // 20609935651 ruc de la empresa
  $rspta=$formatos_varios->formato_ats($_GET["id_proyecto"]);
  // echo json_encode($rspta, true);
  //datos de lugar
  $hojaActiva->setCellValue('G5', $rspta['proyecto']['ubicacion']);

  $cant_array = count($rspta['data']);
  $cant_array_mitad = count($rspta['data'])/2;

  $fila_1 = 9; $fila_2 = 9; $row = 9;

  foreach ($rspta['data'] as $key => $reg) {  
    
    if ( $cant_array_mitad < $cant_array) {

      $spreadsheet->getActiveSheet()->getStyle('B'.$fila_1.':C'.$fila_1)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
      $spreadsheet->getActiveSheet()->getStyle('D'.$fila_1.':E'.$fila_1.'')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
      $spreadsheet->getActiveSheet()->getStyle('A'.$fila_1)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));

      $hojaActiva->mergeCells('B'.$fila_1.':C'.$fila_1); #aprellidos y nombres
      $hojaActiva->mergeCells('D'.$fila_1.':E'.$fila_1.''); #Firma
      $hojaActiva->setCellValue('A'.$fila_1, ($key+1));
      $hojaActiva->setCellValue('B'.$fila_1, $reg['trabajador']);
      $spreadsheet->getActiveSheet()->getStyle('E'.$fila_1)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));

      $fila_1++;
    } else {

      $spreadsheet->getActiveSheet()->getStyle('G'.$fila_2.':H'.$fila_2)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
      $spreadsheet->getActiveSheet()->getStyle('I'.$fila_2.':J'.$fila_2)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
      $spreadsheet->getActiveSheet()->getStyle('F'.$fila_2)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));


      $hojaActiva->mergeCells('G'.$fila_2.':H'.$fila_2); #aprellidos y nombres
      $hojaActiva->mergeCells('I'.$fila_2.':J'.$fila_2); #Firma
      $hojaActiva->setCellValue('F'.$fila_2, ($key+1));
      $hojaActiva->setCellValue('G'.$fila_2, $reg['trabajador']);
      $spreadsheet->getActiveSheet()->getStyle('J'.$fila_2)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));

      $fila_2++;
    }
    $cant_array_mitad++;
    $spreadsheet->getActiveSheet()->getRowDimension($row++)->setRowHeight(30);
  }

  // redirect output to client browser
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="fortmato_ats.xlsx"');
  header('Cache-Control: max-age=0');

  $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
  $writer->save('php://output');

?>
