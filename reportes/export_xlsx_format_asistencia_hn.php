<?php
  require '../vendor/autoload.php';

  use PhpOffice\PhpSpreadsheet\Spreadsheet;
  use PhpOffice\PhpSpreadsheet\IOFactory;
  use PhpOffice\PhpSpreadsheet\Style\Border;
  use PhpOffice\PhpSpreadsheet\Style\Color;
    use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


  $spreadsheet = new Spreadsheet();
  $spreadsheet->getProperties()->setCreator("Sevens Ingenieros")->setTitle("Formato Asistencia de obreros");

  // ══════════════════════════════════════════ - D A T O S   D E L   T R A B A J A D O R - ══════════════════════════════════════════

  require_once "../modelos/Formatos_varios.php";
  require_once "../modelos/Asistencia_obrero.php";

  $formatos_varios = new FormatosVarios();
  $asistencia_obrero=new Asistencia_obrero(); 

  $proyecto = $formatos_varios->datos_proyecto($_GET["idproyecto"]);

  $data_asistencia = $asistencia_obrero->ver_detalle_quincena($_GET["ids_q_asistencia"], $_GET["f1"],$_GET["f2"],$_GET["idproyecto"], $_GET["n_f_i_p"], $_GET["n_f_f_p"]);

  $f1               = $_GET["f1"];
  $f2               = $_GET["f2"];
  $id_proyecto      = $_GET["idproyecto"];
  $fecha_pago_obrero= $proyecto['data']['datos_proyecto']['fecha_pago_obrero'];
  $num_qs           = floatval($_GET["i"]) ;

  // Convertir las fechas a objetos DateTime
  $fechaInicio      = new DateTime($f1);
  $fechaFin         = new DateTime($f2);  
  
  $hojaActiva = $spreadsheet->getActiveSheet();

  // ══════════════════════════════════════════ - P L A N T I L L A - ══════════════════════════════════════════
  plantilla_stylo_header($spreadsheet, $fecha_pago_obrero);
  plantilla($spreadsheet, $fecha_pago_obrero);

  // ══════════════════════════════════════════ - INSERTAMOS LOS NOMBRES DE LOS HEADS - ══════════════════════════════════════════
  plantilla_nombre_head($hojaActiva, $fecha_pago_obrero);
  $hojaActiva->getStyle('D1')->getFont()->setBold(true);
  $hojaActiva->getStyle('D1')->getFont()->setSize(16);
  
  $hojaActiva->setCellValue('D1', $proyecto['data']['datos_proyecto']['empresa_acargo']);
  $hojaActiva->setCellValue('D5', $proyecto['data']['datos_proyecto']['nombre_proyecto']);
  $hojaActiva->setCellValue('D6', $proyecto['data']['datos_proyecto']['ubicacion']);
  $hojaActiva->setCellValue('D7', $proyecto['data']['datos_proyecto']['empresa']);

  // ══════════════════════════════════════════ - L O G O - ══════════════════════════════════════════
  plantilla_logo($spreadsheet);
  $hojaActiva->mergeCells('A1:C4'); #imagen

  // ══════════════════════════════════════════ - D A T O  S   D E L   T R A B A J A D O R - ══════════════════════════════════════════
  
  $fila_1 = 11; 
  
  if ($fecha_pago_obrero == 'semanal') {   
    // nombre de hoja actual
    $spreadsheet->setActiveSheetIndex(0)->setTitle('S' . ($num_qs +1));

    $f_i = nombre_mes($fechaInicio->format("Y-m-d")); $d_i = $fechaInicio->format("d");
    $f_f = nombre_mes($fechaFin->format("Y-m-d"));    $d_f = $fechaFin->format("d");
    $hojaActiva->setCellValue('H8',"$d_i de $f_i AL $d_f de $f_f"); //fecha ejem : 23 DE ABRIL AL 28 DE ABRIL  
    $total_q = 0;
    $dias_sem = ['0'=>'H','1'=>'I','2'=>'J','3'=>'K','4'=>'L','5'=>'M','6'=>'N'];
    foreach ($data_asistencia['data'][0]['asistencia'] as $key2 => $val2) {       
      $hojaActiva->setCellValue($dias_sem[$key2] . '9' , substr($val2['fecha_asistencia'], 8, 2));
      $hojaActiva->setCellValue($dias_sem[$key2] .'10', nombre_dia_semana_v1($val2['fecha_asistencia']));           
    }
    
    foreach ($data_asistencia['data'] as $key => $reg) {

      if ( !empty($reg['imagen_perfil']) ) {
        // Add png image to comment background
        $drawing = $drawing = new PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName($reg['nombres']);
        $drawing->setPath('../dist/docs/all_trabajador/perfil/'.$reg['imagen_perfil']);
        $drawing->setWidthAndHeight(150, 150);
        $comment = $hojaActiva->getComment('B' . $fila_1);
        $comment->setBackgroundImage($drawing);
        // Set the size of the comment equal to the size of the image 
        $comment->setSizeAsBackgroundImage();
      }

      $spreadsheet->getActiveSheet()->getStyle('A' . $fila_1)->getAlignment()->setHorizontal('center');
      $spreadsheet->getActiveSheet()->getStyle('A' . $fila_1 . ':w' . $fila_1)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));      
  
      $hojaActiva->setCellValue('A' . $fila_1, ($key + 1));                 # Auto increment
      $hojaActiva->mergeCells('B' . $fila_1 . ':D' . $fila_1);              # unir columnas - apellidos y nombres
      $hojaActiva->setCellValue('B' . $fila_1, $reg['nombres']);            # apellidos y nombres   
      $hojaActiva->setCellValue('E' . $fila_1, $reg['numero_documento']);   # DNI
      $hojaActiva->setCellValue('F' . $fila_1, $reg['fecha_inicio_t']);     # Fecha incio trabajo
      $hojaActiva->setCellValue('G' . $fila_1, $reg['nombre_ocupacion']);   # Ocupacion

      foreach ($reg['asistencia'] as $key3 => $val3) {
        $hojaActiva->setCellValue($dias_sem[$key3] . $fila_1, $val3['horas_normal_dia']); 
        if (floatval($val3['horas_normal_dia']) > 0) {
          $spreadsheet->getActiveSheet()->getStyle($dias_sem[$key3] . $fila_1)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('008000');
        } else {
          $spreadsheet->getActiveSheet()->getStyle($dias_sem[$key3] . $fila_1)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF0000');
        } 
      }
  
      $hojaActiva->setCellValue('O' . $fila_1, $reg['total_hn']);                 # total HN
      $hojaActiva->setCellValue('P' . $fila_1, $reg['total_dias_asistidos_hn']);  # Total dias
      $hojaActiva->setCellValue('Q' . $fila_1, $reg['sueldo_semanal']);           # Sueldo Semanal
      $hojaActiva->setCellValue('R' . $fila_1, $reg['sueldo_diario']);            # Sueldo diario
      $hojaActiva->setCellValue('S' . $fila_1, $reg['sueldo_hora']);              # Sueldo hora
      $hojaActiva->getStyle('S' . $fila_1)->getNumberFormat()->setFormatCode('0.000');
      $hojaActiva->setCellValue('T' . $fila_1, $reg['sabatical']);                # Sueldo hora
      $hojaActiva->setCellValue('U' . $fila_1, $reg['pago_parcial_hn']);          # Pago parcial
      $hojaActiva->setCellValue('V' . $fila_1, $reg['adicional_descuento_hn']);      # Adicional descuento
      $hojaActiva->setCellValue('W' . $fila_1, $reg['pago_quincenal_hn']);        # Pago Semanal
      $spreadsheet->getActiveSheet()->getStyle('W' . $fila_1)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));     
      
      $spreadsheet->getActiveSheet()->getStyle('E' . $fila_1)->getNumberFormat()
      ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
      
      $total_q += floatval($reg['pago_quincenal_hn']);
      $fila_1++;
    }  
    $hojaActiva->getStyle('W' . $fila_1)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
    $hojaActiva->getStyle('W' . $fila_1)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
    $hojaActiva->getStyle('W' . $fila_1)->getFont()->setBold(true); # Negrita   
    $hojaActiva->getStyle('W' . $fila_1)->getFont()->setSize(14);
    $hojaActiva->setCellValue('W' . $fila_1, $total_q);
    $hojaActiva->getStyle('O11:W' . $fila_1)->getNumberFormat()->setFormatCode('#,##0.00');
  } else if ($fecha_pago_obrero == 'quincenal') {
    $spreadsheet->setActiveSheetIndex(0)->setTitle('S' . ($num_qs +1));

    $f_i = nombre_mes($fechaInicio->format("Y-m-d")); $d_i = $fechaInicio->format("d");
    $f_f = nombre_mes($fechaFin->format("Y-m-d")); $d_f = $fechaFin->format("d");
    $hojaActiva->setCellValue('H8',"$d_i de $f_i AL $d_f de $f_f"); //fecha ejem : 23 DE ABRIL AL 28 DE ABRIL

    foreach ($data_asistencia['data'] as $key => $reg) {
      $spreadsheet->getActiveSheet()->getStyle('A' . $fila_1)->getAlignment()->setHorizontal('center');
      $spreadsheet->getActiveSheet()->getStyle('A' . $fila_1 . ':Z' . $fila_1)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
      // $spreadsheet->getActiveSheet()->getStyle('A' . $fila_1, ($key + 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
      // $spreadsheet->getActiveSheet()->getStyle('B' . $fila_1, $reg['trabajador'])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
  
      $hojaActiva->setCellValue('A' . $fila_1, ($key + 1));                 # Auto increment
      $hojaActiva->mergeCells('B' . $fila_1 . ':D' . $fila_1);              # unir columnas - apellidos y nombres
      $hojaActiva->setCellValue('B' . $fila_1, $reg['nombres']); # apellidos y nombres   
      $hojaActiva->setCellValue('E' . $fila_1, $reg['numero_documento']);   # DNI
      $hojaActiva->setCellValue('F' . $fila_1, $reg['fecha_inicio_t']);     # Fecha incio trabajo
      $hojaActiva->setCellValue('G' . $fila_1, $reg['nombre_ocupacion']);   # Ocupacion
  
      $hojaActiva->setCellValue('U' . $fila_1, $reg['total_hn']);   # total HN
      $hojaActiva->setCellValue('V' . $fila_1, $reg['total_dias_asistidos_hn']);   # Total dias
      $hojaActiva->setCellValue('W' . $fila_1, $reg['sueldo_mensual']);   # Sueldo Mensual
      $hojaActiva->setCellValue('X' . $fila_1, $reg['sueldo_diario']);   # Sueldo diario
      $hojaActiva->setCellValue('Y' . $fila_1, $reg['sueldo_semanal']);   # Sueldo Semanal
      $hojaActiva->setCellValue('Z' . $fila_1, $reg['pago_quincenal_hn']);   # Pago Semanl o Quincenal
      $spreadsheet->getActiveSheet()->getStyle('Z' . $fila_1)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
  
      $fila_1++;
    } 
  }

  // redirect output to client browser
  header('content-type: text/html; charset: utf-8');
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="Asistencia_trabajador_HN.xlsx"');
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


  function plantilla($hoja, $fecha_pago_obrero) {
    //FILAS A1 HASTA S4
    $hoja->getActiveSheet()->getSheetView()->setZoomScale(80);
    $hoja->getActiveSheet()->getStyle('A:Z')->getAlignment()->setVertical('center');    

    //INICIO FILAS A5 A6 A7 HASTA S5 S6 S7
    $hoja->getActiveSheet()->mergeCells('A5:C5'); //PROYECTO
    $hoja->getActiveSheet()->mergeCells('A6:C6'); //UBICACIÓN
    $hoja->getActiveSheet()->mergeCells('A7:C7'); //EMPRESA  

    // ══════════════════════════════════════════ - FILAS A8 A9 A10 - ══════════════════════════════════════════
    //DIMENCIONES
    $hoja->getActiveSheet()->getColumnDimension('A')->setWidth(5);  # N°
    $hoja->getActiveSheet()->getColumnDimension('D')->setWidth(25); # nombre
    $hoja->getActiveSheet()->getColumnDimension('E')->setWidth(15); # dni
    $hoja->getActiveSheet()->getColumnDimension('F')->setWidth(15); # fecha de ingreso
    $hoja->getActiveSheet()->getColumnDimension('G')->setWidth(20); # ocupacion   
    // MERGE
    $hoja->getActiveSheet()->mergeCells('A8:A10'); # N°
    $hoja->getActiveSheet()->mergeCells('B8:D10'); # NOMBRES Y APELLIDOS
    $hoja->getActiveSheet()->mergeCells('E8:E10'); # DNI
    $hoja->getActiveSheet()->mergeCells('F8:F10'); # FECHA INGRESO
    $hoja->getActiveSheet()->mergeCells('G8:G10'); # OCUPACIÓN

    if ($fecha_pago_obrero == 'semanal') {
      $hoja->getActiveSheet()->mergeCells('D1:W2'); //NOMBRE EMPRESA
      $hoja->getActiveSheet()->mergeCells('D3:W4'); //VACIO

      $hoja->getActiveSheet()->mergeCells('D5:W5'); //GET PROYECTO
      $hoja->getActiveSheet()->mergeCells('D6:W6'); //GET UBICACIÓN
      $hoja->getActiveSheet()->mergeCells('D7:W7'); //GET UBICACIÓN

      //DIMENCIONES PARA LOS DÍAS
      $hoja->getActiveSheet()->getColumnDimension('H')->setWidth(5); # D
      $hoja->getActiveSheet()->getColumnDimension('I')->setWidth(5); # L
      $hoja->getActiveSheet()->getColumnDimension('J')->setWidth(5); # M
      $hoja->getActiveSheet()->getColumnDimension('K')->setWidth(5); # M
      $hoja->getActiveSheet()->getColumnDimension('L')->setWidth(5); # J
      $hoja->getActiveSheet()->getColumnDimension('M')->setWidth(5); # V  
      $hoja->getActiveSheet()->getColumnDimension('N')->setWidth(5); # S

      $hoja->getActiveSheet()->mergeCells('H8:N8');  # fecha ejem : 23 DE ABRIL AL 28 DE ABRIL
      
      $hoja->getActiveSheet()->getColumnDimension('P')->setWidth(7); # DÍA
      $hoja->getActiveSheet()->getColumnDimension('Q')->setWidth(12); # SUELDO X SEMANA
      $hoja->getActiveSheet()->getColumnDimension('R')->setWidth(10); # SUELDO X DIA
      $hoja->getActiveSheet()->getColumnDimension('S')->setWidth(10);  # SUELDO X HORA
      $hoja->getActiveSheet()->getColumnDimension('T')->setWidth(7); # SAB
      $hoja->getActiveSheet()->getColumnDimension('U')->setWidth(15); # PAGO PARCIAL 
      $hoja->getActiveSheet()->getColumnDimension('V')->setWidth(12); # ADICIONAL DESCUENTO 
      $hoja->getActiveSheet()->getColumnDimension('W')->setWidth(15); # PAGO SEMANAL 
      
      $hoja->getActiveSheet()->mergeCells('O8:O10'); # TOTAL HORAS
      $hoja->getActiveSheet()->mergeCells('P8:P10'); # TOTAL DÍA
      $hoja->getActiveSheet()->mergeCells('Q8:Q10'); # SUELDO X SEMANAL
      $hoja->getActiveSheet()->mergeCells('R8:R10'); # SUELDO X DIA
      $hoja->getActiveSheet()->mergeCells('S8:S10'); # SUELDO X HORA
      $hoja->getActiveSheet()->mergeCells('T8:T10'); # SAB
      $hoja->getActiveSheet()->mergeCells('U8:U10'); # PAGO PARCIAL
      $hoja->getActiveSheet()->mergeCells('V8:V10'); # ADICIONAL DESCUENTO
      $hoja->getActiveSheet()->mergeCells('W8:W10'); # PAGO SEMANAL
    } else if ($fecha_pago_obrero == 'quincenal') {
      $hoja->getActiveSheet()->mergeCells('D1:AC2'); //NOMBRE EMPRESA
      $hoja->getActiveSheet()->mergeCells('D3:AC4'); //VACIO

      $hoja->getActiveSheet()->mergeCells('D5:AC5'); //GET PROYECTO
      $hoja->getActiveSheet()->mergeCells('D6:AC6'); //GET UBICACIÓN
      $hoja->getActiveSheet()->mergeCells('D7:AC7'); //GET UBICACIÓN

      //DIMENCIONES PARA LOS DÍAS
      $hoja->getActiveSheet()->getColumnDimension('H')->setWidth(5); # D
      $hoja->getActiveSheet()->getColumnDimension('I')->setWidth(5); # L
      $hoja->getActiveSheet()->getColumnDimension('J')->setWidth(5); # M
      $hoja->getActiveSheet()->getColumnDimension('K')->setWidth(5); # M
      $hoja->getActiveSheet()->getColumnDimension('L')->setWidth(5); # J
      $hoja->getActiveSheet()->getColumnDimension('M')->setWidth(5); # V
      $hoja->getActiveSheet()->getColumnDimension('N')->setWidth(5); # S
      $hoja->getActiveSheet()->getColumnDimension('O')->setWidth(5); # D
      $hoja->getActiveSheet()->getColumnDimension('P')->setWidth(5); # L
      $hoja->getActiveSheet()->getColumnDimension('Q')->setWidth(5); # M
      $hoja->getActiveSheet()->getColumnDimension('R')->setWidth(5); # M
      $hoja->getActiveSheet()->getColumnDimension('S')->setWidth(5); # J 
      $hoja->getActiveSheet()->getColumnDimension('T')->setWidth(5); # V    

      $hoja->getActiveSheet()->mergeCells('H8:T8');  # fecha ejem : 23 DE ABRIL AL 28 DE ABRIL

      $hoja->getActiveSheet()->getColumnDimension('U')->setWidth(7); # TOTAL DÍA
      $hoja->getActiveSheet()->getColumnDimension('W')->setWidth(12);# SUELDO X SEMANAL
      $hoja->getActiveSheet()->getColumnDimension('X')->setWidth(10);# SUELDO X DIA
      $hoja->getActiveSheet()->getColumnDimension('Y')->setWidth(10);# SUELDO X HORA
      $hoja->getActiveSheet()->getColumnDimension('Z')->setWidth(7);# SAB
      $hoja->getActiveSheet()->getColumnDimension('AA')->setWidth(15);# PAGO PARCIAL
      $hoja->getActiveSheet()->getColumnDimension('AB')->setWidth(12);# ADICIONAL DESCUENTO
      $hoja->getActiveSheet()->getColumnDimension('AC')->setWidth(15);# PAGO QUINCENAL

      $hoja->getActiveSheet()->mergeCells('U8:U10'); # TOTAL HORAS
      $hoja->getActiveSheet()->mergeCells('V8:V10'); # TOTAL DÍA
      $hoja->getActiveSheet()->mergeCells('W8:W10'); # SUELDO X SEMANAL
      $hoja->getActiveSheet()->mergeCells('X8:X10'); # SUELDO X DIA
      $hoja->getActiveSheet()->mergeCells('Y8:Y10'); # SUELDO X HORA
      $hoja->getActiveSheet()->mergeCells('Z8:Z10'); # SAB
      $hoja->getActiveSheet()->mergeCells('AA8:AA10'); # PAGO PARCIAL
      $hoja->getActiveSheet()->mergeCells('AB8:AB10'); # ADICIONAL DESCUENTO
      $hoja->getActiveSheet()->mergeCells('AC8:AC10'); # PAGO QUINCENAL
    }  
  }

  function plantilla_stylo_header($hoja, $fecha_pago_obrero) {

    // ══════════════════════════════════════════ - STYES HEADERS - ══════════════════════════════════════════
    $hoja->getActiveSheet()->getStyle('D1:Z2')->getFont()->setBold(true); # Empresa a cargo
    $hoja->getActiveSheet()->getStyle('A5:C7')->getFont()->setBold(true); # Proyecto, Ubicacion, Empresa  
    $hoja->getActiveSheet()->getStyle('A5:C7')->getAlignment()->setWrapText(true); # Proyecto, Ubicacion, Empresa

    $hoja->getActiveSheet()->getStyle('D1:AC4')->getAlignment()->setHorizontal('center');
    $hoja->getActiveSheet()->getStyle('A8:AC10')->getAlignment()->setHorizontal('center'); # titulos
    $hoja->getActiveSheet()->getStyle('A8:AC10')->getFont()->setBold(true); # titulos
    

    if ($fecha_pago_obrero == 'semanal') {
      $hoja->getActiveSheet()->getStyle('A1:W10')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
      $hoja->getActiveSheet()->getStyle('A8:G10')->getAlignment()->setWrapText(true);
      $hoja->getActiveSheet()->getStyle('O8:AC10')->getAlignment()->setWrapText(true);
    } else if ($fecha_pago_obrero == 'quincenal') {
      $hoja->getActiveSheet()->getStyle('A1:AC10')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
      $hoja->getActiveSheet()->getStyle('A8:G10')->getAlignment()->setWrapText(true);
      $hoja->getActiveSheet()->getStyle('V8:AC10')->getAlignment()->setWrapText(true);
    }  
  }

  function plantilla_nombre_head($hojaActiva, $fecha_pago_obrero) {   

    $hojaActiva->setCellValue('A5', 'PROYECTO'); //PROYECTO
    $hojaActiva->setCellValue('A6', 'UBICACIÓN'); //UBICACIÓN
    $hojaActiva->setCellValue('A7', 'EMPRESA'); //EMPRESA

    $hojaActiva->setCellValue('A8', 'N°'); //N°
    $hojaActiva->setCellValue('B8', 'NOMBRES'); //NOMBRES
    $hojaActiva->setCellValue('E8', 'DNI'); //DNI
    $hojaActiva->setCellValue('F8', 'FECHA INGRESO'); //FECHA INGRESO
    $hojaActiva->setCellValue('G8', 'OCUPACIÓN'); //OCUPACIÓN  

    if ($fecha_pago_obrero == 'semanal') {
      $hojaActiva->setCellValue('O8', "TOTAL \n HORAS");      # HORAS
      $hojaActiva->setCellValue('P8', "TOTAL \n DÍA");        # DÍA
      $hojaActiva->setCellValue('Q8', "SUELDO \n SEMANAL"); # SUELDO X SEMANAL
      $hojaActiva->setCellValue('R8', "SUELDO \n X DIA");     # SUELDO X DIA
      $hojaActiva->setCellValue('S8', "SUELDO \n X HORA");    # SUELDO X HORA
      $hojaActiva->setCellValue('T8', "SAB");                 # SABATICAL
      $hojaActiva->setCellValue('U8', "PAGO \n PARCIAL");     # PAGO PARCIAL 
      $hojaActiva->setCellValue('V8', "ADICIONAL \n DESCTO"); # ADICIONAL DESCUENTO 
      $hojaActiva->setCellValue('W8', "PAGO \n SEMANAL");     # PAGO SEMANAL 
      
    } else if ($fecha_pago_obrero == 'quincenal') {
      $hojaActiva->setCellValue('U8', "TOTAL \n HORAS");      # HORAS
      $hojaActiva->setCellValue('V8', "TOTAL \n DÍA");        # DÍA
      $hojaActiva->setCellValue('W8', "SUELDO \n QUINCENAL"); # SUELDO X SEMANA
      $hojaActiva->setCellValue('X8', "SUELDO \n X DIA");     # SUELDO X DIA
      $hojaActiva->setCellValue('Y8', "SUELDO \n X HORA");    # SUELDO X HORA
      $hojaActiva->setCellValue('Z8', "SAB");                 # SABATICAL
      $hojaActiva->setCellValue('AA8', "PAGO \n PARCIAL");    # PAGO PARCIAL 
      $hojaActiva->setCellValue('AB8', "ADICIONAL \n DESCTO");# ADICIONAL DESCUENTO 
      $hojaActiva->setCellValue('AC8', "PAGO \n QUINCENAL");  # PAGO QUINCENAL  
    }  
  }

  function plantilla_logo($spreadsheet) {
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
  }
