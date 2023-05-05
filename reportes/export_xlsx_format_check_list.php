<?php 
  require '../vendor/autoload.php'; 
  use PhpOffice\PhpSpreadsheet\Spreadsheet;  
  use PhpOffice\PhpSpreadsheet\IOFactory;
  use PhpOffice\PhpSpreadsheet\Style\Border;
  use PhpOffice\PhpSpreadsheet\Style\Color;


  $spreadsheet = new Spreadsheet();
  $spreadsheet->getProperties()->setCreator("Sevens Ingenieros")->setTitle("Formato Check List");
  
  $spreadsheet->setActiveSheetIndex(0);
  $spreadsheet->getActiveSheet()->getSheetView()->setZoomScale(85);
  $spreadsheet->getActiveSheet()->getStyle('A:AD')->getAlignment()->setVertical('center');
  // $spreadsheet->getActiveSheet()->mergeCells('A1:D1');

  $spreadsheet->getActiveSheet()->getStyle('D1:AD3')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('A4:AD4')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('A5:AD8')->getAlignment()->setHorizontal('center');

  $spreadsheet->getActiveSheet()->getStyle('A5:W5')->getAlignment()->setWrapText(true);
  $spreadsheet->getActiveSheet()->getStyle('A7:AD7')->getAlignment()->setWrapText(true);

  $spreadsheet->getActiveSheet()->getStyle('D1:AD1')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('A4:AD4')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('A5:W5')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('A7:AD7')->getFont()->setBold(true);
    
  $spreadsheet->getActiveSheet()->getRowDimension('5')->setRowHeight(35);
  $spreadsheet->getActiveSheet()->getRowDimension('7')->setRowHeight(35);

  $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
  $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(7);
  $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(7);
  $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(7);
  $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(7);
  $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(7);
  $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(7);
  $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(7);
  $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(7);
  $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(7);
  $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(7);
  $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setWidth(7);
  $spreadsheet->getActiveSheet()->getColumnDimension('R')->setWidth(7);
  $spreadsheet->getActiveSheet()->getColumnDimension('S')->setWidth(7);
  $spreadsheet->getActiveSheet()->getColumnDimension('T')->setWidth(7);
  $spreadsheet->getActiveSheet()->getColumnDimension('U')->setWidth(7);
  $spreadsheet->getActiveSheet()->getColumnDimension('V')->setWidth(7);
  $spreadsheet->getActiveSheet()->getColumnDimension('W')->setWidth(7);
  $spreadsheet->getActiveSheet()->getColumnDimension('X')->setWidth(7);
  $spreadsheet->getActiveSheet()->getColumnDimension('X')->setWidth(7);
  $spreadsheet->getActiveSheet()->getColumnDimension('Y')->setWidth(7);
  $spreadsheet->getActiveSheet()->getColumnDimension('AA')->setWidth(7);
  $spreadsheet->getActiveSheet()->getColumnDimension('AB')->setWidth(7);
  $spreadsheet->getActiveSheet()->getColumnDimension('AC')->setWidth(7);
  $spreadsheet->getActiveSheet()->getColumnDimension('AD')->setWidth(7);

  $spreadsheet->getActiveSheet()->getStyle('A1:AD8')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
  $hojaActiva = $spreadsheet->getActiveSheet();

  // Add png image to comment background
  $drawing = $drawing = new PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
  $drawing->setName('Paid');
  $drawing->setDescription('Paid');
  $drawing->setPath('../dist/img/logo-principal.png'); // put your path and image here
  $drawing->setCoordinates('A1');
  $drawing->setWidthAndHeight(70, 70);
  $drawing->setOffsetY(5);
  $drawing->setOffsetX(50);
  $drawing->setRotation(0);
  $drawing->getShadow()->setVisible(true);
  $drawing->getShadow()->setDirection(45);
  $drawing->setWorksheet($spreadsheet->getActiveSheet());

  // $spreadsheet->getDefaultStyle()->getFont()->setName("Tahoma");
  // $spreadsheet->getDefaultStyle()->getFont()->setSize(15);

  $hojaActiva->mergeCells('A1:C3'); #imagen
  $hojaActiva->mergeCells('D1:AD1'); #nombre plantilla 
  $hojaActiva->mergeCells('D2:W3'); #tarea a realizar
  $hojaActiva->mergeCells('X2:Z3',); #Fecha
  $hojaActiva->mergeCells('AA2:AD3',); # Rellenar Fecha
  $hojaActiva->mergeCells('A4:AD4'); #Datos del empleador
  
  $hojaActiva->mergeCells('A5:F5'); # RAZÓN SOCIAL O DENOMINACIÓN SOCIAL
  $hojaActiva->mergeCells('A6:F6'); #
  $hojaActiva->mergeCells('G5:J5'); # RUC
  $hojaActiva->mergeCells('G6:J6'); #
  $hojaActiva->mergeCells('K5:P5'); # DOMICILIO
  $hojaActiva->mergeCells('K6:P6'); #
  $hojaActiva->mergeCells('Q5:V5'); # RAZÓN SOCIAL O DENOMINACIÓN SOCIAL
  $hojaActiva->mergeCells('Q6:V6'); #
  $hojaActiva->mergeCells('W5:Z5'); # N° TRABAJADORES EN EL TRABAJO
  $hojaActiva->mergeCells('W6:Z6'); #
  $hojaActiva->mergeCells('AA5:AD6'); # vACIO

  $hojaActiva->mergeCells('B7:F7'); # VERIFICACIÓN DE EQUIPOS DE SEGURIDAD
  $hojaActiva->mergeCells('G7:H7'); # CASCO
  $hojaActiva->mergeCells('I7:J7'); # CORTA VIENTO
  $hojaActiva->mergeCells('K7:L7'); # LENTES DE SEGURIDAD
  $hojaActiva->mergeCells('M7:N7'); # GUANTES DE NITRILO
  $hojaActiva->mergeCells('O7:P7'); # GUANTES DE JEBE
  $hojaActiva->mergeCells('Q7:R7'); # GUANTES DE CUERO
  $hojaActiva->mergeCells('S7:T7'); # ZAPATOS DE SEGURIDAD
  $hojaActiva->mergeCells('U7:V7'); # PROTECTORES AUDITIVOS
  $hojaActiva->mergeCells('W7:X7'); # ARNES
  $hojaActiva->mergeCells('Y7:Z7'); # CARETA
  $hojaActiva->mergeCells('AA7:AB7'); # BOTAS DE JEBE
  $hojaActiva->mergeCells('AC7:AD7'); # CAPOTIN

  $hojaActiva->mergeCells('B8:F8'); #aprellidos y nombres
  

  $hojaActiva->setCellValue('D1', 'CONTROL DIARIO DE EQUIPOS DE PROTECCIÓN PERSONAL');
  $hojaActiva->setCellValue('D2', '---');
  $hojaActiva->setCellValue('X2', 'FECHA : ');
  $hojaActiva->setCellValue('D3', '---');

  $hojaActiva->setCellValue('A4', 'DATOS DEL EMPLEADOR PRINCIPAL:');

  $hojaActiva->setCellValue('A5', 'RAZÓN SOCIAL O DENOMINACIÓN SOCIAL');
  $hojaActiva->setCellValue('G5', 'RUC');
  $hojaActiva->setCellValue('K5', 'DOMICILIO (Dirección, distrito, departamento, provincia)');
  $hojaActiva->setCellValue('Q5', 'TIPO DE ACTIVIDAD ECONÓMICA');
  $hojaActiva->setCellValue('W5', 'N° TRABAJADORES EN EL TRABAJO');

  $hojaActiva->setCellValue('A6', 'SEVEN´S INGENIEROS SELVA S.A.C. ');
  $hojaActiva->setCellValue('G6', '20609935651');
  $hojaActiva->setCellValue('K6', '---');
  $hojaActiva->setCellValue('Q6', 'ARQUITECTURA E INGENIERIA');
  $hojaActiva->setCellValue('W6', '---');

  $hojaActiva->setCellValue('B7', 'VERIFICACIÓN DE EQUIPOS DE SEGURIDAD');
  $hojaActiva->setCellValue('G7', 'CASCO');
  $hojaActiva->setCellValue('I7', 'CORTA VIENTO');
  $hojaActiva->setCellValue('K7', 'LENTES DE SEGURIDAD');
  $hojaActiva->setCellValue('M7', "GUANTES DE NITRILO");
  $hojaActiva->setCellValue('O7', "GUANTES DE JEBE");
  $hojaActiva->setCellValue('Q7', 'GUANTES DE CUERO');
  $hojaActiva->setCellValue('S7', 'ZAPATOS DE SEGURIDAD');
  $hojaActiva->setCellValue('U7', "PROTECTORES AUDITIVOS");
  $hojaActiva->setCellValue('W7', "ARNES");
  $hojaActiva->setCellValue('Y7', "CARETA");
  $hojaActiva->setCellValue('AA7', "BOTAS DE JEBE");
  $hojaActiva->setCellValue('AC7', "CAPOTIN");

  $hojaActiva->setCellValue('A8', "#");
  $hojaActiva->setCellValue('B8', "APELLIDOS Y NOMBRES");
  $hojaActiva->setCellValue('G8', "SI");
  $hojaActiva->setCellValue('H8', "NO");
  $hojaActiva->setCellValue('I8', "SI");
  $hojaActiva->setCellValue('J8', "NO");
  $hojaActiva->setCellValue('K8', "SI");
  $hojaActiva->setCellValue('L8', "NO");
  $hojaActiva->setCellValue('M8', "SI");
  $hojaActiva->setCellValue('N8', "NO");
  $hojaActiva->setCellValue('O8', "SI");
  $hojaActiva->setCellValue('P8', "NO");
  $hojaActiva->setCellValue('Q8', "SI");
  $hojaActiva->setCellValue('R8', "NO");
  $hojaActiva->setCellValue('S8', "SI");
  $hojaActiva->setCellValue('T8', "NO");
  $hojaActiva->setCellValue('U8', "SI");
  $hojaActiva->setCellValue('V8', "NO");
  $hojaActiva->setCellValue('W8', "SI");
  $hojaActiva->setCellValue('X8', "NO");
  $hojaActiva->setCellValue('Y8', "SI");
  $hojaActiva->setCellValue('Z8', "NO");
  $hojaActiva->setCellValue('AA8', "SI");
  $hojaActiva->setCellValue('AB8', "NO");
  $hojaActiva->setCellValue('AC8', "SI");
  $hojaActiva->setCellValue('AD8', "NO");


  require_once "../modelos/Formatos_varios.php"; 
  $formatos_varios=new FormatosVarios();  

  $rspta=$formatos_varios->formato_ats($_GET["id_proyecto"]);
  // echo json_encode($rspta, true);
  $spreadsheet->getActiveSheet()->getStyle('D2')->getFont()->setBold(true);
  $hojaActiva->setCellValue('D2', $rspta['proyecto']['nombre_proyecto']);
  $hojaActiva->setCellValue('K6', 'PJ. YUNGAY NRO. 151 P.J. SANTA ROSA LAMBAYEQUE CHICLAYO CHICLAYO');

  $fila_1 = 9;

  foreach ($rspta['data'] as $key => $reg) {   
    
    $spreadsheet->getActiveSheet()->getStyle('A'.$fila_1)->getAlignment()->setHorizontal('center');
    $spreadsheet->getActiveSheet()->getStyle('B'.$fila_1.':AD'.$fila_1)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
    $spreadsheet->getActiveSheet()->getStyle('A'.$fila_1, ($key+1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
    $spreadsheet->getActiveSheet()->getStyle('B'.$fila_1, $reg['trabajador'])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));

    
    $hojaActiva->mergeCells('B'.$fila_1.':F'.$fila_1); #aprellidos y nombres
    $hojaActiva->setCellValue('A'.$fila_1, ($key+1));
    $hojaActiva->setCellValue('B'.$fila_1, $reg['trabajador']);
    $spreadsheet->getActiveSheet()->getStyle('AD'.$fila_1)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));

    $fila_1++;    
  }

  // redirect output to client browser
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="fortmato_check_list_epps.xlsx"');
  header('Cache-Control: max-age=0');

  $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
  $writer->save('php://output');

?>
