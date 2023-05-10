<?php 
  require '../vendor/autoload.php'; 
  use PhpOffice\PhpSpreadsheet\Spreadsheet;  
  use PhpOffice\PhpSpreadsheet\IOFactory;
  use PhpOffice\PhpSpreadsheet\Style\Border;
  use PhpOffice\PhpSpreadsheet\Style\Color;

  $spreadsheet = new Spreadsheet();
  $spreadsheet->getProperties()->setCreator("Sevens Ingenieros")->setTitle("Formato Check List");
  
  $spreadsheet->setActiveSheetIndex(0);
  $hojaActiva = $spreadsheet->getActiveSheet();
  //FILAS A1 HASTA S4
  $spreadsheet->getActiveSheet()->getSheetView()->setZoomScale(95);
  $spreadsheet->getActiveSheet()->getStyle('A:S')->getAlignment()->setVertical('center');
  $spreadsheet->getActiveSheet()->mergeCells('A1:C4');//logo
  $spreadsheet->getActiveSheet()->mergeCells('D1:S2');//NOMBRE EMPRESA
  $spreadsheet->getActiveSheet()->mergeCells('D3:S4');//nOMBRE SEMANA

  //INICIO FILAS A5 A6 A7 HASTA S5 S6 S7
  $spreadsheet->getActiveSheet()->mergeCells('A5:C5');//PROYECTO
  $spreadsheet->getActiveSheet()->mergeCells('A6:C6');//UBICACIÓN
  $spreadsheet->getActiveSheet()->mergeCells('A7:C7');//EMPRESA
  
  $spreadsheet->getActiveSheet()->mergeCells('D5:S5'); //GET PROYECTO
  $spreadsheet->getActiveSheet()->mergeCells('D6:S6');//GET UBICACIÓN
  $spreadsheet->getActiveSheet()->mergeCells('D7:S7');//GET UBICACIÓN

  //==================FILAS A8 A9 A10====================
  //DIMENCIONES
  $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
  $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(25);
  $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(15);
  $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(15);
  $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
  $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(7);

  //DIMENCIONES PARA LOS DÍAS
  $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(7);
  $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(7);
  $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(7);
  $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(7);
  $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(7);
  $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(7);

  $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(15);
  $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setWidth(15); 
  $spreadsheet->getActiveSheet()->getColumnDimension('R')->setWidth(20); 
  $spreadsheet->getActiveSheet()->getColumnDimension('S')->setWidth(15); 

  $spreadsheet->getActiveSheet()->mergeCells('A8:A10');//N°
  $spreadsheet->getActiveSheet()->mergeCells('B8:D10');//NOMBRES
  $spreadsheet->getActiveSheet()->mergeCells('E8:E10');//DNI
  $spreadsheet->getActiveSheet()->mergeCells('F8:F10');//FECHA INGRESO
  $spreadsheet->getActiveSheet()->mergeCells('G8:G10'); //OCUPACIÓN

  $spreadsheet->getActiveSheet()->mergeCells('H8:M8');//fecha ejem : 23 DE ABRIL AL 28 DE ABRIL

  $spreadsheet->getActiveSheet()->mergeCells('N8:N10'); //HORAS
  $spreadsheet->getActiveSheet()->mergeCells('O8:O10'); //DÍA
  $spreadsheet->getActiveSheet()->mergeCells('P8:P10'); //SUELDO
  $spreadsheet->getActiveSheet()->mergeCells('Q8:Q10'); //PAGO X DIA
  $spreadsheet->getActiveSheet()->mergeCells('R8:R10'); //pago semanal
  $spreadsheet->getActiveSheet()->mergeCells('S8:S10'); //SUELDO

  //=========INSERTAMOS LOS NOMBRES DE LOS HEADS=============

  $hojaActiva->setCellValue('D1', 'SEVEN´S INGENIEROS SELVA S.A.C.    R.U.C :  20609935651');

  $hojaActiva->setCellValue('A5','PROYECTO');//PROYECTO
  $hojaActiva->setCellValue('A6','UBICACIÓN');//UBICACIÓN
  $hojaActiva->setCellValue('A7','EMPRESA');//EMPRESA

  $hojaActiva->setCellValue('A8','N°');//N°
  $hojaActiva->setCellValue('B8','NOMBRES');//NOMBRES
  $hojaActiva->setCellValue('E8','DNI');//DNI
  $hojaActiva->setCellValue('F8','FECHA INGRESO');//FECHA INGRESO
  $hojaActiva->setCellValue('G8','OCUPACIÓN'); //OCUPACIÓN

  $hojaActiva->setCellValue('H8','ejem : 23 DE ABRIL AL 28 DE ABRIL');//fecha ejem : 23 DE ABRIL AL 28 DE ABRIL

  $hojaActiva->setCellValue('N8','HORAS'); //HORAS
  $hojaActiva->setCellValue('O8','DÍA'); //DÍA
  $hojaActiva->setCellValue('P8','SUELDO'); //SUELDO
  $hojaActiva->setCellValue('Q8','PAGO X DIA'); //PAGO X DIA
  $hojaActiva->setCellValue('R8','PAGO SEMANAL'); //pago semanal
  $hojaActiva->setCellValue('S8','SUELDO'); //SUELDO



 //===========================STYES HEADERS=======================
  $spreadsheet->getActiveSheet()->getStyle('D1:S1')->getAlignment()->setHorizontal('center');

  $spreadsheet->getActiveSheet()->getStyle('A8:A10')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('B8:D10')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('E8:E10')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('F8:F10')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('G8:G10')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('H8:M8')->getAlignment()->setHorizontal('center');

  $spreadsheet->getActiveSheet()->getStyle('N8')->getAlignment()->setHorizontal('center');

  $spreadsheet->getActiveSheet()->getStyle('O8')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('P8')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('Q8')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('R8')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('S8')->getAlignment()->setHorizontal('center');
  //----------------------------
  $spreadsheet->getActiveSheet()->getStyle('A5:C5')->getAlignment()->setWrapText(true);
  $spreadsheet->getActiveSheet()->getStyle('A6:C6')->getAlignment()->setWrapText(true);
  $spreadsheet->getActiveSheet()->getStyle('A7:C7')->getAlignment()->setWrapText(true);

  $spreadsheet->getActiveSheet()->getStyle('D1:S1')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('A5:C5')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('A6:C6')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('A7:C7')->getFont()->setBold(true);

  $spreadsheet->getActiveSheet()->getStyle('A8:A10')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('B8:D10')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('E8:E10')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('F8:F10')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('G8:G10')->getFont()->setBold(true);

  $spreadsheet->getActiveSheet()->getStyle('H8:M8')->getFont()->setBold(true);

  $spreadsheet->getActiveSheet()->getStyle('N8')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('O8')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('P8')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('Q8')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('R8')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('S8')->getFont()->setBold(true);


//===========================LOGO=====================================

  $spreadsheet->getActiveSheet()->getStyle('A1:S10')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
 
  // Add png image to comment background
  $drawing = $drawing = new PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
  $drawing->setName('Paid');
  $drawing->setDescription('Paid');
  $drawing->setPath('../dist/img/logo-principal.png'); // put your path and image here
  $drawing->setCoordinates('A1');
  $drawing->setWidthAndHeight(90, 90);
  $drawing->setOffsetY(5);
  $drawing->setOffsetX(50);
  $drawing->setRotation(0);
  $drawing->getShadow()->setVisible(true);
  $drawing->getShadow()->setDirection(45);
  $drawing->setWorksheet($spreadsheet->getActiveSheet());

  $hojaActiva->mergeCells('A1:C4'); #imagen



  // require_once "../modelos/Formatos_varios.php"; 
  // $formatos_varios=new FormatosVarios();  

  // $rspta=$formatos_varios->formato_ats($_GET["id_proyecto"]);
  // // echo json_encode($rspta, true);
  // $spreadsheet->getActiveSheet()->getStyle('D2')->getFont()->setBold(true);
  // $hojaActiva->setCellValue('D2', $rspta['proyecto']['nombre_proyecto']);
  // $hojaActiva->setCellValue('K6', 'PJ. YUNGAY NRO. 151 P.J. SANTA ROSA LAMBAYEQUE CHICLAYO CHICLAYO');

  // $fila_1 = 9;

  // foreach ($rspta['data'] as $key => $reg) {   
    
  //   $spreadsheet->getActiveSheet()->getStyle('A'.$fila_1)->getAlignment()->setHorizontal('center');
  //   $spreadsheet->getActiveSheet()->getStyle('B'.$fila_1.':AD'.$fila_1)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
  //   $spreadsheet->getActiveSheet()->getStyle('A'.$fila_1, ($key+1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
  //   $spreadsheet->getActiveSheet()->getStyle('B'.$fila_1, $reg['trabajador'])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));

    
  //   $hojaActiva->mergeCells('B'.$fila_1.':F'.$fila_1); #aprellidos y nombres
  //   $hojaActiva->setCellValue('A'.$fila_1, ($key+1));
  //   $hojaActiva->setCellValue('B'.$fila_1, $reg['trabajador']);
  //   $spreadsheet->getActiveSheet()->getStyle('AD'.$fila_1)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));

  //   $fila_1++;    
  // }

  // redirect output to client browser
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="Asistencia_trabajador.xlsx"');
  header('Cache-Control: max-age=0');

  $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
  $writer->save('php://output');

?>
