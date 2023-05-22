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
//   //FILAS A1 HASTA S4
//   $spreadsheet->getActiveSheet()->getSheetView()->setZoomScale(95);
//   $spreadsheet->getActiveSheet()->getStyle('A:S')->getAlignment()->setVertical('center');
//   $spreadsheet->getActiveSheet()->mergeCells('A1:C4');//logo
//   $spreadsheet->getActiveSheet()->mergeCells('D1:S2');//NOMBRE EMPRESA
//   $spreadsheet->getActiveSheet()->mergeCells('D3:S4');//nOMBRE SEMANA

//   //INICIO FILAS A5 A6 A7 HASTA S5 S6 S7
//   $spreadsheet->getActiveSheet()->mergeCells('A5:C5');//PROYECTO
//   $spreadsheet->getActiveSheet()->mergeCells('A6:C6');//UBICACIÓN
//   $spreadsheet->getActiveSheet()->mergeCells('A7:C7');//EMPRESA
  
//   $spreadsheet->getActiveSheet()->mergeCells('D5:S5'); //GET PROYECTO
//   $spreadsheet->getActiveSheet()->mergeCells('D6:S6');//GET UBICACIÓN
//   $spreadsheet->getActiveSheet()->mergeCells('D7:S7');//GET UBICACIÓN

//   //==================FILAS A8 A9 A10====================
//   //DIMENCIONES
//   $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
//   $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(25);
//   $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(15);
//   $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(15);
//   $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
//   $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(7);

//   //DIMENCIONES PARA LOS DÍAS
//   $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(7);
//   $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(7);
//   $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(7);
//   $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(7);
//   $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(7);
//   $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(7);

//   $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(15);
//   $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setWidth(15); 
//   $spreadsheet->getActiveSheet()->getColumnDimension('R')->setWidth(20); 
//   $spreadsheet->getActiveSheet()->getColumnDimension('S')->setWidth(15); 

//   $spreadsheet->getActiveSheet()->mergeCells('A8:A10');//N°
//   $spreadsheet->getActiveSheet()->mergeCells('B8:D10');//NOMBRES
//   $spreadsheet->getActiveSheet()->mergeCells('E8:E10');//DNI
//   $spreadsheet->getActiveSheet()->mergeCells('F8:F10');//FECHA INGRESO
//   $spreadsheet->getActiveSheet()->mergeCells('G8:G10'); //OCUPACIÓN

//   $spreadsheet->getActiveSheet()->mergeCells('H8:M8');//fecha ejem : 23 DE ABRIL AL 28 DE ABRIL

//   $spreadsheet->getActiveSheet()->mergeCells('N8:N10'); //HORAS
//   $spreadsheet->getActiveSheet()->mergeCells('O8:O10'); //DÍA
//   $spreadsheet->getActiveSheet()->mergeCells('P8:P10'); //SUELDO
//   $spreadsheet->getActiveSheet()->mergeCells('Q8:Q10'); //PAGO X DIA
//   $spreadsheet->getActiveSheet()->mergeCells('R8:R10'); //pago semanal
//   $spreadsheet->getActiveSheet()->mergeCells('S8:S10'); //SUELDO

//   //=========INSERTAMOS LOS NOMBRES DE LOS HEADS=============

//   $hojaActiva->setCellValue('D1', 'SEVEN´S INGENIEROS SELVA S.A.C.    R.U.C :  20609935651');

//   $hojaActiva->setCellValue('A5','PROYECTO');//PROYECTO
//   $hojaActiva->setCellValue('A6','UBICACIÓN');//UBICACIÓN
//   $hojaActiva->setCellValue('A7','EMPRESA');//EMPRESA

//   $hojaActiva->setCellValue('A8','N°');//N°
//   $hojaActiva->setCellValue('B8','NOMBRES');//NOMBRES
//   $hojaActiva->setCellValue('E8','DNI');//DNI
//   $hojaActiva->setCellValue('F8','FECHA INGRESO');//FECHA INGRESO
//   $hojaActiva->setCellValue('G8','OCUPACIÓN'); //OCUPACIÓN

//   $hojaActiva->setCellValue('H8','ejem : 23 DE ABRIL AL 28 DE ABRIL');//fecha ejem : 23 DE ABRIL AL 28 DE ABRIL

//   $hojaActiva->setCellValue('N8','HORAS'); //HORAS
//   $hojaActiva->setCellValue('O8','DÍA'); //DÍA
//   $hojaActiva->setCellValue('P8','SUELDO'); //SUELDO
//   $hojaActiva->setCellValue('Q8','PAGO X DIA'); //PAGO X DIA
//   $hojaActiva->setCellValue('R8','PAGO SEMANAL'); //pago semanal
//   $hojaActiva->setCellValue('S8','SUELDO'); //SUELDO

//  //===========================STYES HEADERS=======================
//   $spreadsheet->getActiveSheet()->getStyle('D1:S1')->getAlignment()->setHorizontal('center');

//   $spreadsheet->getActiveSheet()->getStyle('A8:A10')->getAlignment()->setHorizontal('center');
//   $spreadsheet->getActiveSheet()->getStyle('B8:D10')->getAlignment()->setHorizontal('center');
//   $spreadsheet->getActiveSheet()->getStyle('E8:E10')->getAlignment()->setHorizontal('center');
//   $spreadsheet->getActiveSheet()->getStyle('F8:F10')->getAlignment()->setHorizontal('center');
//   $spreadsheet->getActiveSheet()->getStyle('G8:G10')->getAlignment()->setHorizontal('center');
//   $spreadsheet->getActiveSheet()->getStyle('H8:M8')->getAlignment()->setHorizontal('center');

//   $spreadsheet->getActiveSheet()->getStyle('N8')->getAlignment()->setHorizontal('center');

//   $spreadsheet->getActiveSheet()->getStyle('O8')->getAlignment()->setHorizontal('center');
//   $spreadsheet->getActiveSheet()->getStyle('P8')->getAlignment()->setHorizontal('center');
//   $spreadsheet->getActiveSheet()->getStyle('Q8')->getAlignment()->setHorizontal('center');
//   $spreadsheet->getActiveSheet()->getStyle('R8')->getAlignment()->setHorizontal('center');
//   $spreadsheet->getActiveSheet()->getStyle('S8')->getAlignment()->setHorizontal('center');
//   //----------------------------
//   $spreadsheet->getActiveSheet()->getStyle('A5:C5')->getAlignment()->setWrapText(true);
//   $spreadsheet->getActiveSheet()->getStyle('A6:C6')->getAlignment()->setWrapText(true);
//   $spreadsheet->getActiveSheet()->getStyle('A7:C7')->getAlignment()->setWrapText(true);

//   $spreadsheet->getActiveSheet()->getStyle('D1:S1')->getFont()->setBold(true);
//   $spreadsheet->getActiveSheet()->getStyle('A5:C5')->getFont()->setBold(true);
//   $spreadsheet->getActiveSheet()->getStyle('A6:C6')->getFont()->setBold(true);
//   $spreadsheet->getActiveSheet()->getStyle('A7:C7')->getFont()->setBold(true);

//   $spreadsheet->getActiveSheet()->getStyle('A8:A10')->getFont()->setBold(true);
//   $spreadsheet->getActiveSheet()->getStyle('B8:D10')->getFont()->setBold(true);
//   $spreadsheet->getActiveSheet()->getStyle('E8:E10')->getFont()->setBold(true);
//   $spreadsheet->getActiveSheet()->getStyle('F8:F10')->getFont()->setBold(true);
//   $spreadsheet->getActiveSheet()->getStyle('G8:G10')->getFont()->setBold(true);

//   $spreadsheet->getActiveSheet()->getStyle('H8:M8')->getFont()->setBold(true);

//   $spreadsheet->getActiveSheet()->getStyle('N8')->getFont()->setBold(true);
//   $spreadsheet->getActiveSheet()->getStyle('O8')->getFont()->setBold(true);
//   $spreadsheet->getActiveSheet()->getStyle('P8')->getFont()->setBold(true);
//   $spreadsheet->getActiveSheet()->getStyle('Q8')->getFont()->setBold(true);
//   $spreadsheet->getActiveSheet()->getStyle('R8')->getFont()->setBold(true);
//   $spreadsheet->getActiveSheet()->getStyle('S8')->getFont()->setBold(true);


//===========================LOGO=====================================

  // $spreadsheet->getActiveSheet()->getStyle('A1:S10')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
 
  // // Add png image to comment background
  // $drawing = $drawing = new PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
  // $drawing->setName('Paid');
  // $drawing->setDescription('Paid');
  // $drawing->setPath('../dist/img/logo-principal.png'); // put your path and image here
  // $drawing->setCoordinates('A1');
  // $drawing->setWidthAndHeight(90, 90);
  // $drawing->setOffsetY(5);
  // $drawing->setOffsetX(50);
  // $drawing->setRotation(0);
  // $drawing->getShadow()->setVisible(true);
  // $drawing->getShadow()->setDirection(45);
  // $drawing->setWorksheet($spreadsheet->getActiveSheet());

  // $hojaActiva->mergeCells('A1:C4'); #imagen



  require_once "../modelos/Formatos_varios.php"; 
  $formatos_varios=new FormatosVarios(); 

  //listar botones de la quincena o semana
  $rspta=$formatos_varios->datos_proyecto($_GET["id_proyecto"]);

  // Fechas de inicio y fin
  $f1 = $rspta['data']['fecha_inicio'];
  $f2 = $rspta['data']['fecha_fin'];
  $fecha_pago_obrero = $rspta['data']['fecha_pago_obrero'];

  // Convertir las fechas a objetos DateTime
  $fechaInicio = new DateTime($f1);
  $fechaFin = new DateTime($f2);
  
  if ($fecha_pago_obrero=='semanal') {

    $dia_regular = 0; $weekday_regular = obtenerNombreDia($fechaInicio->format('Y-m-d')); $estado_regular = false;
    
    if ($weekday_regular == "Do") { $dia_regular = -1; } else { if ($weekday_regular == "Lu") { $dia_regular = -2; } else { if ($weekday_regular == "Ma") { $dia_regular = -3; } else { if ($weekday_regular == "Mi") { $dia_regular = -4; } else { if ($weekday_regular == "Ju") { $dia_regular = -5; } else { if ($weekday_regular == "Vi") { $dia_regular = -6; } else { if ($weekday_regular == "Sá") { $dia_regular = -7; } } } } } } }

    
    $fecha = new DateTime($f1); $fecha_f; $fecha_i = $fechaInicio->format('Y-m-d');

//     $fechaInicio = new DateTime();  // Assuming $fechaInicio is a valid DateTime object
// $fecha_i = $fechaInicio->format('Y-m-d');
    // var_dump($fecha_i);die();



    $cal_mes  = false; $i=0;  $cont=0;

    while ($cal_mes == false) {

      $cont = $cont+1; $fecha_i = $fecha;

      if ($estado_regular) {
        $fecha_f = sumaFecha(6, $fecha_i->format('Y-m-d'));
      } else {
        // var_dump(7+$dia_regular.' '.$fecha_i);die();
        $fecha_f = sumaFecha(7+$dia_regular, $fecha_i); $estado_regular = true;
      }           

      $val_fecha_f = new DateTime( format_a_m_d($fecha_f) ); $val_fecha_proyecto = $fechaFin;
      
      // var_dump($val_fecha_f);die();

      if ($val_fecha_f >= $val_fecha_proyecto) { $cal_mes = true; }else{ $cal_mes = false;}
      // var_dump($fecha_f);die();
      
      $fecha = sumaFecha(1,$fecha_f);

      $i++;
      
    } 




    
    

    var_dump('hola fecha 215 '.$fecha_i.' '.$fecha_f);die();


    $num_semana = 1;

    // Generar hojas por semana
    while ($fechaInicio <= $fechaFin) {

      // Crear una hoja por cada semana
      $hoja = $spreadsheet->createSheet();
      $hoja->setTitle('S' . $num_semana);
      // $hoja->setCellValue('A1', $fechaInicio->format('Y-m-d'));

      // //FILAS A1 HASTA S4
      $hoja->getSheetView()->setZoomScale(95);
      $hoja->getStyle('A:S')->getAlignment()->setVertical('center');
      $hoja->mergeCells('A1:C4'); // Logo
      $hoja->mergeCells('D1:S2'); // Nombre empresa
      $hoja->mergeCells('D3:S4'); // Nombre semana
      
      //INICIO FILAS A5 A6 A7 HASTA S5 S6 S7
      $hoja->mergeCells('A5:C5');//PROYECTO
      $hoja->mergeCells('A6:C6');//UBICACIÓN
      $hoja->mergeCells('A7:C7');//EMPRESA
      
      $hoja->mergeCells('D5:S5'); //GET PROYECTO
      $hoja->mergeCells('D6:S6');//GET UBICACIÓN
      $hoja->mergeCells('D7:S7');//GET UBICACIÓN

        //==================FILAS A8 A9 A10====================
      //DIMENCIONES
      $hoja->getColumnDimension('A')->setWidth(5);
      $hoja->getColumnDimension('D')->setWidth(25);
      $hoja->getColumnDimension('E')->setWidth(15);
      $hoja->getColumnDimension('F')->setWidth(15);
      $hoja->getColumnDimension('G')->setWidth(20);
      $hoja->getColumnDimension('O')->setWidth(7);

      //DIMENCIONES PARA LOS DÍAS
      $hoja->getColumnDimension('H')->setWidth(7);
      $hoja->getColumnDimension('I')->setWidth(7);
      $hoja->getColumnDimension('J')->setWidth(7);
      $hoja->getColumnDimension('K')->setWidth(7);
      $hoja->getColumnDimension('L')->setWidth(7);
      $hoja->getColumnDimension('M')->setWidth(7);

      $hoja->getColumnDimension('P')->setWidth(15);
      $hoja->getColumnDimension('Q')->setWidth(15); 
      $hoja->getColumnDimension('R')->setWidth(20); 
      $hoja->getColumnDimension('S')->setWidth(15); 

      $hoja->mergeCells('A8:A10');//N°
      $hoja->mergeCells('B8:D10');//NOMBRES
      $hoja->mergeCells('E8:E10');//DNI
      $hoja->mergeCells('F8:F10');//FECHA INGRESO
      $hoja->mergeCells('G8:G10'); //OCUPACIÓN

      $hoja->mergeCells('H8:M8');//fecha ejem : 23 DE ABRIL AL 28 DE ABRIL

      $hoja->mergeCells('N8:N10'); //HORAS
      $hoja->mergeCells('O8:O10'); //DÍA
      $hoja->mergeCells('P8:P10'); //SUELDO
      $hoja->mergeCells('Q8:Q10'); //PAGO X DIA
      $hoja->mergeCells('R8:R10'); //pago semanal
      $hoja->mergeCells('S8:S10'); //SUELDO

      //===========================STYES HEADERS=======================
      $hoja->getStyle('D1:S1')->getAlignment()->setHorizontal('center');
      $hoja->getStyle('D3:S4')->getAlignment()->setHorizontal('center');
      // $hoja->mergeCells('D3:S4');
      $hoja->getStyle('A8:A10')->getAlignment()->setHorizontal('center');
      $hoja->getStyle('B8:D10')->getAlignment()->setHorizontal('center');
      $hoja->getStyle('E8:E10')->getAlignment()->setHorizontal('center');
      $hoja->getStyle('F8:F10')->getAlignment()->setHorizontal('center');
      $hoja->getStyle('G8:G10')->getAlignment()->setHorizontal('center');
      $hoja->getStyle('H8:M8')->getAlignment()->setHorizontal('center');

      $hoja->getStyle('N8')->getAlignment()->setHorizontal('center');

      $hoja->getStyle('O8')->getAlignment()->setHorizontal('center');
      $hoja->getStyle('P8')->getAlignment()->setHorizontal('center');
      $hoja->getStyle('Q8')->getAlignment()->setHorizontal('center');
      $hoja->getStyle('R8')->getAlignment()->setHorizontal('center');
      $hoja->getStyle('S8')->getAlignment()->setHorizontal('center');
      //----------------------------
      $hoja->getStyle('A5:C5')->getAlignment()->setWrapText(true);
      $hoja->getStyle('A6:C6')->getAlignment()->setWrapText(true);
      $hoja->getStyle('A7:C7')->getAlignment()->setWrapText(true);

      $hoja->getStyle('D1:S1')->getFont()->setBold(true);
      $hoja->getStyle('A5:C5')->getFont()->setBold(true);
      $hoja->getStyle('A6:C6')->getFont()->setBold(true);
      $hoja->getStyle('A7:C7')->getFont()->setBold(true);

      $hoja->getStyle('A8:A10')->getFont()->setBold(true);
      $hoja->getStyle('B8:D10')->getFont()->setBold(true);
      $hoja->getStyle('E8:E10')->getFont()->setBold(true);
      $hoja->getStyle('F8:F10')->getFont()->setBold(true);
      $hoja->getStyle('G8:G10')->getFont()->setBold(true);

      $hoja->getStyle('H8:M8')->getFont()->setBold(true);

      $hoja->getStyle('N8')->getFont()->setBold(true);
      $hoja->getStyle('O8')->getFont()->setBold(true);
      $hoja->getStyle('P8')->getFont()->setBold(true);
      $hoja->getStyle('Q8')->getFont()->setBold(true);
      $hoja->getStyle('R8')->getFont()->setBold(true);
      $hoja->getStyle('S8')->getFont()->setBold(true);

      //=========INSERTAMOS LOS NOMBRES DE LOS HEADS=============

      $hoja->setCellValue('D1', 'SEVEN´S INGENIEROS SELVA S.A.C.    R.U.C :  20609935651');
      $hoja->setCellValue('D3' , 'ASISTENCIA - SEMANA '.$num_semana);

      $hoja->setCellValue('A5','PROYECTO');//PROYECTO
      $hoja->setCellValue('A6','UBICACIÓN');//UBICACIÓN
      $hoja->setCellValue('A7','EMPRESA');//EMPRESA

      //-------------llenamos PROYECTO UBICACIÓN EMPRESA
      $hoja->setCellValue('D5',$rspta['data']['nombre_proyecto']); //GET PROYECTO
      $hoja->setCellValue('D6',$rspta['data']['ubicacion']);//GET UBICACIÓN
      $hoja->setCellValue('D7',$rspta['data']['empresa']);//GET EMPRESA

      $hoja->setCellValue('A8','N°');//N°
      $hoja->setCellValue('B8','NOMBRES');//NOMBRES
      $hoja->setCellValue('E8','DNI');//DNI
      $hoja->setCellValue('F8','FECHA INGRESO');//FECHA INGRESO
      $hoja->setCellValue('G8','OCUPACIÓN'); //OCUPACIÓN

      $hoja->setCellValue('H8','ejem : 23 DE ABRIL AL 28 DE ABRIL');//fecha ejem : 23 DE ABRIL AL 28 DE ABRIL

      $hoja->setCellValue('N8','HORAS'); //HORAS
      $hoja->setCellValue('O8','DÍA'); //DÍA
      $hoja->setCellValue('P8','SUELDO'); //SUELDO
      $hoja->setCellValue('Q8','PAGO X DIA'); //PAGO X DIA
      $hoja->setCellValue('R8','PAGO SEMANAL'); //pago semanal
      $hoja->setCellValue('S8','SUELDO'); //SUELDO

      $hoja->setCellValue('H8', 'Del ' . $fechaInicio->format('d') . ' DE ' . $fechaInicio->format('F') . ' AL ' . $capffsemana->format('d') . ' DE ' . $capffsemana->format('F'));



      $hoja->getStyle('A1:S10')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
 
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
    
      $hoja->mergeCells('A1:C4'); #imagen

      // // Obtener la fecha y el mes
      // $fecha = $fechaInicio->format('d');
      // $mes = strftime('%B', $fechaInicio->getTimestamp());
      // $primeraFecha ="";
      // // Actualizar la fecha de inicio al inicio de la siguiente semana
      

      //     // Actualizar la primera fecha y la última fecha
      // if ($primeraFecha === null) {
      //   $primeraFecha = $fechaInicio;
        
      // }
      // //var_dump($primeraFecha->format('d').'  --- '.$mes);die();
      // $ultimaFecha = $fechaInicio;
      // //var_dump( $primeraFecha.'  .--- '.$mes);die();
      // $hoja->setCellValue('H8',$primeraFecha.' AL '.$ultimaFecha);//fecha ejem : 23 DE ABRIL AL 28 DE ABRIL
      $num_semana++;
      $fechaInicio->modify('+1 week');
      //var_dump($fechaInicio);die();
    }

  }else{

    $num_quincena = 1;

    // Generar hojas por quincena
    while ($fechaInicio <= $fechaFin) {
      // Crear una hoja por cada quincena
      $hoja = $spreadsheet->createSheet();
      $hoja->setTitle('Q' . $num_quincena++);
      // $hoja->setCellValue('A1', $fechaInicio->format('Y-m-d'));
      $hoja->getSheetView()->setZoomScale(95);
      $hoja->getStyle('A:S')->getAlignment()->setVertical('center');
      $hoja->mergeCells('A1:C4'); // Logo
      $hoja->mergeCells('D1:S2'); // Nombre empresa
      $hoja->mergeCells('D3:S4'); // Nombre semana


      // Actualizar la fecha de inicio al inicio de la siguiente quincena
      $fechaInicio->modify('+2 weeks');
    }
  
  }


  // Establecer la hoja activa en la primera hoja (índice 0)
  //  $spreadsheet->setActiveSheetIndex(0);

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

  //extraer_dia_semana
  function obtenerNombreDia($fecha) {
    $diasSemana = array("Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sá");
    $nombreDia = date('N', strtotime($fecha));
    return $diasSemana[$nombreDia];
  }

  function sumaFecha($d, $fecha) {
    // var_dump($fecha.'fechaString ');die();
    $fechaString = $fecha->format('Y-m-d');
    // var_dump($fecha.'fechaString '.$fechaString);die();
    $nuevaFecha = date('Y-m-d', strtotime($fechaString . ' + ' . $d . ' days'));
    // var_dump($nuevaFecha);die();
    return $nuevaFecha;
}

?>
