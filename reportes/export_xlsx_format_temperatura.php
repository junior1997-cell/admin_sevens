<?php 
  require '../vendor/autoload.php'; 
  use PhpOffice\PhpSpreadsheet\Spreadsheet;  
  use PhpOffice\PhpSpreadsheet\IOFactory;
  use PhpOffice\PhpSpreadsheet\Style\Border;
  use PhpOffice\PhpSpreadsheet\Style\Color;


  require_once "../modelos/Formatos_varios.php"; 
  $formatos_varios=new FormatosVarios();  

  $rspta=$formatos_varios->formato_ats($_GET["id_proyecto"]);


  $spreadsheet = new Spreadsheet();
  $spreadsheet->getProperties()->setCreator("Sevens Ingenieros")->setTitle("Formato Temperatura");
  
  $spreadsheet->setActiveSheetIndex(0);
  $spreadsheet->getActiveSheet()->getStyle('A:M')->getAlignment()->setVertical('center');
  $spreadsheet->getActiveSheet()->getStyle('C1:L4')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('A8:M8')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('A9:M9')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('A9:M9')->getAlignment()->setWrapText(true);
  $spreadsheet->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('L1')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('A5:A7')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('F8')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('J8')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('A9:M9')->getFont()->setBold(true);
  
  $spreadsheet->getActiveSheet()->getRowDimension('9')->setRowHeight(50);
  $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
  $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
  $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(30);
  $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(12);
  $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(12);
  $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(12);
  $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(12);
  $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(12);
  $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(12);
  $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(12);

  $spreadsheet->getActiveSheet()->getRowDimension(5)->setRowHeight(40);
  $spreadsheet->getActiveSheet()->getRowDimension(6)->setRowHeight(40);
  $spreadsheet->getActiveSheet()->getRowDimension(7)->setRowHeight(40);
  $spreadsheet->getActiveSheet()->getRowDimension(8)->setRowHeight(40);

  $spreadsheet->getActiveSheet()->getStyle('A1:M9')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
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
  $hojaActiva->mergeCells('C1:K2'); #nombre plantilla 
  $hojaActiva->mergeCells('C3:K4'); #tarea a realizar
  $hojaActiva->mergeCells('L1:M2'); #Revision
  $hojaActiva->mergeCells('L3:M3'); #fecha
  $hojaActiva->mergeCells('L4:M4'); #nombre dia  
  $hojaActiva->mergeCells('A5:B5'); #Proyecto
  $hojaActiva->mergeCells('C5:M5'); #Proyecto nombre
  $hojaActiva->mergeCells('A6:B6'); #Ubicacion
  $hojaActiva->mergeCells('C6:M6'); #Ubicacion nombre
  $hojaActiva->mergeCells('A7:B7'); #Empresa
  $hojaActiva->mergeCells('C7:M7'); #Empresa nombre
  $hojaActiva->mergeCells('B9:E9'); #aprellidos y nombres
  $hojaActiva->mergeCells('A8:E8'); #Espaio vacio
  $hojaActiva->mergeCells('F8:I8'); #Ingreso
  $hojaActiva->mergeCells('J8:M8'); #Salida
  

  $hojaActiva->setCellValue('C1', 'REGISTRO - SATURACIÓN DE OXÍGENO Y TEMPERATURA');
  // $hojaActiva->setCellValue('C3', '---');

  $hojaActiva->setCellValue('L1', 'REVISION:');
  $hojaActiva->setCellValue('L3', '---');
  $hojaActiva->setCellValue('L4', '---');

  $hojaActiva->setCellValue('A5', 'Proyecto:');
  $hojaActiva->setCellValue('A6', 'Ubicación:');
  $hojaActiva->setCellValue('A7', 'Empresa:');

  $hojaActiva->setCellValue('F8', 'Ingreso');
  $hojaActiva->setCellValue('J8', 'Salida');

  $hojaActiva->setCellValue('A9', 'N°');
  $hojaActiva->setCellValue('B9', 'Apellidos y Nombres');
  $hojaActiva->setCellValue('F9', 'Firma');
  $hojaActiva->setCellValue('G9', 'Hora del registro ');
  $hojaActiva->setCellValue('H9', "T (°C) \n (34°-37.5°)");
  $hojaActiva->setCellValue('I9', "%S.O. \n (87-100)");
  $hojaActiva->setCellValue('J9', 'Firma');
  $hojaActiva->setCellValue('K9', 'Hora del registro ');
  $hojaActiva->setCellValue('L9', "T (°C) \n (34°-37.5°)");
  $hojaActiva->setCellValue('M9', "%S.O. \n (87-100)");

  $hojaActiva->setCellValue('C7', $rspta['proyecto']['eac_razon_social'] . '                      RUC :'. $rspta['proyecto']['eac_numero_documento']);


  // echo json_encode($rspta, true);
  $hojaActiva->setCellValue('C3', $rspta['proyecto']['nombre_proyecto']);
  $hojaActiva->setCellValue('C5', $rspta['proyecto']['nombre_proyecto']);
  $hojaActiva->setCellValue('C6', $rspta['proyecto']['ubicacion']);

  $fila_1 = 10;
  $cont=0;
  $k=0;
  $totalCant = count($rspta['data']);

  foreach ($rspta['data'] as $key => $reg) { 
    $k=$k+1;
    $spreadsheet->getActiveSheet()->getStyle('A'.$fila_1)->getAlignment()->setHorizontal('center');
    $spreadsheet->getActiveSheet()->getStyle('B'.$fila_1.':M'.$fila_1)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
    $spreadsheet->getActiveSheet()->getStyle('A'.$fila_1, ($k))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
    $spreadsheet->getActiveSheet()->getStyle('B'.$fila_1, $reg['trabajador'])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
  
    $hojaActiva->mergeCells('B'.$fila_1.':E'.$fila_1); #aprellidos y nombres
    $hojaActiva->setCellValue('A'.$fila_1, ($k));
    $hojaActiva->setCellValue('B'.$fila_1, $reg['trabajador']);
    $spreadsheet->getActiveSheet()->getStyle('M'.$fila_1)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));

    //PROCESO PARA PINTAR FILAS VACIAS
    if ($k==$totalCant) {

      $fila_2=$fila_1+1;

      for ($i = $k; $i < $k+16; $i++) {

        $spreadsheet->getActiveSheet()->getStyle('A' . $fila_2)->getAlignment()->setHorizontal('center');
        $spreadsheet->getActiveSheet()->getRowDimension($fila_2)->setRowHeight(50);
        $spreadsheet->getActiveSheet()->getStyle('A'.$fila_1, ($i+1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
        $spreadsheet->getActiveSheet()->getStyle('A'.$fila_2.':M'.$fila_2)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));

        $hojaActiva->mergeCells('B'.$fila_2.':E'.$fila_2); #aprellidos y nombres
        $hojaActiva->setCellValue('A'.$fila_2, ($i+1));
        $hojaActiva->setCellValue('B'.$fila_2, "");

        $fila_2++;
      }

    }
    $spreadsheet->getActiveSheet()->getRowDimension($fila_1)->setRowHeight(50);
    $fila_1++;
    
  }

  // redirect output to client browser
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="fortmato_temperatura.xlsx"');
  header('Cache-Control: max-age=0');

  $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
  $writer->save('php://output');

?>
