<?php 
  require '../vendor/autoload.php'; 
  use PhpOffice\PhpSpreadsheet\Spreadsheet;  
  use PhpOffice\PhpSpreadsheet\IOFactory;
  use PhpOffice\PhpSpreadsheet\Style\Border;
  use PhpOffice\PhpSpreadsheet\Style\Color;


  $spreadsheet = new Spreadsheet();
  $spreadsheet->getProperties()->setCreator("Sevens Ingenieros")->setTitle("EPP-TRABAJADOR");
  
  $spreadsheet->setActiveSheetIndex(0);
  $spreadsheet->getActiveSheet()->getStyle('A:J')->getAlignment()->setVertical('center');
  $spreadsheet->getActiveSheet()->getStyle('C1:J4')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('C8:J8')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('C9:J9')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('A10:J10')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('A11:J11')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('A11:J11')->getAlignment()->setWrapText(true);
  $spreadsheet->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('J1')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('A5:A9')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('I1:I4')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('J8')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('A11:J11')->getFont()->setBold(true);
  
  $spreadsheet->getActiveSheet()->getRowDimension('11')->setRowHeight(35);
  $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
  $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(15);
  $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(15);
  $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(15);
  $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(13);
  $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(12);

  $spreadsheet->getActiveSheet()->getStyle('A1:J11')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
  $hojaActiva = $spreadsheet->getActiveSheet();

  // Add png image to comment background
  $drawing = $drawing = new PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
  $drawing->setName('Paid');
  $drawing->setDescription('Paid');
  $drawing->setPath('../dist/img/logo-principal.png'); // put your path and image here
  $drawing->setCoordinates('A1');
  $drawing->setWidthAndHeight(70, 70);
  $drawing->setOffsetY(15);
  $drawing->setOffsetX(30);
  $drawing->setRotation(0);
  $drawing->getShadow()->setVisible(true);
  $drawing->getShadow()->setDirection(45);
  $drawing->setWorksheet($spreadsheet->getActiveSheet());

  // $spreadsheet->getDefaultStyle()->getFont()->setName("Tahoma");
  // $spreadsheet->getDefaultStyle()->getFont()->setSize(15);

  $hojaActiva->mergeCells('A1:B4'); #imagen
  $hojaActiva->mergeCells('C1:H2'); #nombre plantilla 
  $hojaActiva->mergeCells('C3:H4'); #tarea a realizar
  $hojaActiva->mergeCells('I1:J2'); #Revision
  // --------------------------------
  $hojaActiva->mergeCells('A5:B5'); #Proyecto
  $hojaActiva->mergeCells('C5:J5'); #Proyecto nombre
  $hojaActiva->mergeCells('A6:B6'); #Ubicacion
  $hojaActiva->mergeCells('C6:J6'); #Ubicacion nombre
  $hojaActiva->mergeCells('A7:B7'); #Empresa
  $hojaActiva->mergeCells('C7:J7'); #Empresa nombre
  $hojaActiva->mergeCells('A8:B8'); #Nombre
  $hojaActiva->mergeCells('C8:J8'); #
  $hojaActiva->mergeCells('A9:B9'); #Talla
  $hojaActiva->mergeCells('C9:J9'); #
  $hojaActiva->mergeCells('B11:E11'); #aprellidos y nombres
  $hojaActiva->mergeCells('A10:J10'); #Espaio vacio
  $hojaActiva->mergeCells('I11:J11'); #FECHA
  

  $hojaActiva->setCellValue('C1', 'CONTROL DE E.P.P POR TRABAJADOR');
  $hojaActiva->getStyle('C1')->getFont()->setSize(13);
  $hojaActiva->setCellValue('C3', 'SEVEN´S INGENIEROS S.A.C.');
  $hojaActiva->getStyle('C3')->getFont()->setSize(16);
  // $hojaActiva->setCellValue('C3', '---');

  $hojaActiva->setCellValue('I1', 'REVISIÓN');
  $hojaActiva->setCellValue('I3', 'FECHA');
  $hojaActiva->setCellValue('I4', 'N° REGISTRO');

  $hojaActiva->setCellValue('A5', 'PROYECTO:');
  $hojaActiva->setCellValue('A6', 'UBICACIÓN:');
  $hojaActiva->setCellValue('A7', 'EMPRESA:');
  $hojaActiva->setCellValue('A8', 'NOMBRE:');
  $hojaActiva->setCellValue('A9', 'TALLA:');

  $hojaActiva->setCellValue('A11', 'N°');
  $hojaActiva->setCellValue('B11', 'DESCRIPCIÓN');
  $hojaActiva->setCellValue('F11', 'TALLA');
  $hojaActiva->setCellValue('G11', 'CANTIDAD');
  $hojaActiva->setCellValue('H11', "FECHA");
  $hojaActiva->setCellValue('I11', "FECHA");



  require_once "../modelos/Formatos_varios.php"; 
  $formatos_varios=new FormatosVarios();  

  $rspta=$formatos_varios->formato_ats($_GET["id_proyecto"]);

  $empresa = $rspta['proyecto']['empresa'].'             - '.$rspta['proyecto']['numero_documento'];
  $hojaActiva->setCellValue('C5', $rspta['proyecto']['nombre_proyecto']);
  $hojaActiva->setCellValue('C6', $rspta['proyecto']['ubicacion']);
  $hojaActiva->setCellValue('C7',$empresa );

  $fila_1 = 12;

  for ($key=0; $key < 28 ; $key++) { 
    $spreadsheet->getActiveSheet()->getStyle('A'.$fila_1)->getAlignment()->setHorizontal('center');
    $spreadsheet->getActiveSheet()->getStyle('B'.$fila_1.':J'.$fila_1)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
    $spreadsheet->getActiveSheet()->getStyle('A'.$fila_1, ($key+1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
    $spreadsheet->getActiveSheet()->getStyle('B'.$fila_1, '')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));

    
  
    $hojaActiva->mergeCells('B'.$fila_1.':E'.$fila_1); #aprellidos y nombres
    $hojaActiva->mergeCells('I'.$fila_1.':J'.$fila_1); #aprellidos y nombres
    $hojaActiva->setCellValue('A'.$fila_1, ($key+1));
    $hojaActiva->setCellValue('B'.$fila_1, '');
    $spreadsheet->getActiveSheet()->getStyle('J'.$fila_1)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));

    $spreadsheet->getActiveSheet()->getRowDimension($fila_1)->setRowHeight(30);
    $fila_1++;
    
  }

  // redirect output to client browser
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="Formato_EPP_Trabajador.xlsx"');
  header('Cache-Control: max-age=0');

  $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
  $writer->save('php://output');

?>
