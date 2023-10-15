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
  $spreadsheet->getActiveSheet()->getStyle('A12:J121')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('A12:J12')->getAlignment()->setWrapText(true);
  $spreadsheet->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('J1')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('A5')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('D6')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('E6')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('G6')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('I6')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('A5:J7')->getAlignment()->setHorizontal('center');

  $spreadsheet->getActiveSheet()->getStyle('I1:I4')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('J8')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('A12:J12')->getFont()->setBold(true);
  
  $spreadsheet->getActiveSheet()->getRowDimension('11')->setRowHeight(18);
  $spreadsheet->getActiveSheet()->getRowDimension('12')->setRowHeight(35);
  $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
  $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(15);
  $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(15);
  $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(15);
  $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(13);
  $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(12);

  $spreadsheet->getActiveSheet()->getStyle('A1:J12')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
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
  $hojaActiva->mergeCells('A5:J5'); #Espaio vacio  

  //HEAD 
  $hojaActiva->mergeCells('A6:C7'); #NOMBRE EMPRESA
  $hojaActiva->mergeCells('D6:D7'); #NOMBRE RUC
  $hojaActiva->mergeCells('E6:F7'); #NOMBRE DOMICILIO
  $hojaActiva->mergeCells('G6:H7'); #NOMBRE TIPO
  $hojaActiva->mergeCells('I6:J7'); #NOMBRE TRABAJADORES
  //SELDAS PARA LLENAR LA INFO
  $hojaActiva->mergeCells('A8:C10'); # EMPRESA
  $hojaActiva->mergeCells('D8:D10'); #RUC
  $hojaActiva->mergeCells('E8:F10'); #DOMICILIO
  $hojaActiva->mergeCells('G8:H10'); #TIPO
  $hojaActiva->mergeCells('I8:J10'); #TRABAJADORES

  $hojaActiva->mergeCells('A11:J11'); #Espaio vacio  
  $hojaActiva->mergeCells('D12:G12'); #DESCRIPCION
  $hojaActiva->mergeCells('I12:J12'); #FECHA

  $hojaActiva->setCellValue('C1', 'CONTROL DE E.P.P POR TRABAJADOR');
  $hojaActiva->getStyle('C1')->getFont()->setSize(13);
  $hojaActiva->setCellValue('C3', 'SEVEN´S INGENIEROS S.A.C.');
  $hojaActiva->getStyle('C3')->getFont()->setSize(16);
  // $hojaActiva->setCellValue('C3', '---');

  $hojaActiva->setCellValue('I1', 'REVISIÓN');
  $hojaActiva->setCellValue('I3', 'FECHA');
  $hojaActiva->setCellValue('I4', 'N° REGISTRO');

  $hojaActiva->setCellValue('A5', 'DATOS DEL EMPLEADOR PRINCIPAL');
  $hojaActiva->setCellValue('A6', 'RAZÓN SOCIAL');
  $hojaActiva->setCellValue('D6', 'RUC');
  $hojaActiva->setCellValue('E6', 'DOMICILIO');
  $hojaActiva->setCellValue('G6', 'ACTIVIDAD ECONÓMICA');
  $hojaActiva->setCellValue('I6', 'N° TRABAJADORES');

  $hojaActiva->setCellValue('A11', 'APELLIDOS Y NOMBRES:');
  $hojaActiva->setCellValue('A12', 'N°');
  $hojaActiva->setCellValue('B12', 'CANT.');
  $hojaActiva->setCellValue('C12', 'UND.');
  $hojaActiva->setCellValue('D12', 'EQUIPOS DE SEGURIDAD ENTREGADOS');
  $hojaActiva->setCellValue('F12', '');
  $hojaActiva->setCellValue('G12', '');
  $hojaActiva->setCellValue('H12', "FECHA ENTREGA");
  $hojaActiva->setCellValue('I12', "FIRMA");



  require_once "../modelos/Formatos_varios.php"; 
  $formatos_varios=new FormatosVarios();  

  $rspta=$formatos_varios->formato_ats($_GET["id_proyecto"]);
  //  var_dump($rspta['proyecto']); die();
  $hojaActiva->setCellValue('A8', $rspta['proyecto']['empresa']);
  $hojaActiva->setCellValue('D8', $rspta['proyecto']['numero_documento']);
  $hojaActiva->setCellValue('E8', $rspta['proyecto']['ubicacion']);
  $hojaActiva->setCellValue('G8', $rspta['proyecto']['nombre_proyecto']);
  //ESTILOS 
  // Establecer el valor de la celda
  $hojaActiva->setCellValue('A8', $rspta['proyecto']['empresa']);

  // Aplicar ajuste de texto automático a la celda
  $hojaActiva->getStyle('A8')->getAlignment()->setWrapText(true);
  $hojaActiva->getStyle('E8')->getAlignment()->setWrapText(true);
  $hojaActiva->getStyle('G8')->getAlignment()->setWrapText(true);

  // Opcionalmente, puedes ajustar el tamaño de la fuente para que quepa en una línea
  $hojaActiva->getStyle('A8')->getFont()->setSize(10); 
  $hojaActiva->getStyle('E8')->getFont()->setSize(9); 
  $hojaActiva->getStyle('G8')->getFont()->setSize(8); 

  $fila_1 = 13;

  for ($key=0; $key < 17 ; $key++) { 
    $spreadsheet->getActiveSheet()->getStyle('A'.$fila_1)->getAlignment()->setHorizontal('center');
    $spreadsheet->getActiveSheet()->getStyle('B'.$fila_1.':J'.$fila_1)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
    $spreadsheet->getActiveSheet()->getStyle('A'.$fila_1, ($key+1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
    $spreadsheet->getActiveSheet()->getStyle('B'.$fila_1, '')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));   
  
    $hojaActiva->mergeCells('D'.$fila_1.':G'.$fila_1); #aprellidos y nombres
    $hojaActiva->mergeCells('I'.$fila_1.':J'.$fila_1); #aprellidos y nombres
    $hojaActiva->setCellValue('A'.$fila_1, ($key+1));
    $hojaActiva->setCellValue('D'.$fila_1, '');
    $spreadsheet->getActiveSheet()->getStyle('J'.$fila_1)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));  

    $spreadsheet->getActiveSheet()->getRowDimension($fila_1)->setRowHeight(45);
    $fila_1++;
    
  }

  // redirect output to client browser
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="Formato_EPP_Trabajador.xlsx"');
  header('Cache-Control: max-age=0');

  $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
  $writer->save('php://output');

?>
