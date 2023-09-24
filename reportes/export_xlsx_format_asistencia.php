<?php
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;

$spreadsheet = new Spreadsheet();
$spreadsheet->getProperties()->setCreator("Sevens Ingenieros")->setTitle("Formato Asistencia de obreros");

// ══════════════════════════════════════════ - D A T O S   D E L   T R A B A J A D O R - ══════════════════════════════════════════
$Colum_Semana = array('H', 'I', 'J', 'K', 'L', 'M', 'N');
$Colum_quins = array('H', 'I', 'J', 'K', 'L', 'M', 'N','O', 'P', 'Q', 'R', 'S', 'T', 'U');

require_once "../modelos/Formatos_varios.php";
$formatos_varios = new FormatosVarios();

$proyecto = $formatos_varios->datos_proyecto($_GET["id_proyecto"]);
// var_dump($proyecto['ee']['data']); die();
$n_f_i_p          = $proyecto['e']['data']['fecha_inicio'];
$n_f_f_p          = $proyecto['e']['data']['fecha_fin'];
$id_proyecto      = $_GET["id_proyecto"];
$fecha_pago_obrero= $proyecto['e']['data']['fecha_pago_obrero'];
// var_dump($fecha_pago_obrero); die();
$num_quincena = 1; $contador = 0;

$dia_regular = 0; $estado_regular = false;

// Generar hojas por quincena
foreach ($proyecto['ee']['data'] as $key => $reg){

  $ids_q_asistencia =$reg['ids_q_asistencia']; 
  $numero_q_s =$reg['numero_q_s']; 
  // solo el dia ejem: 06 
  $f_i_dia               = date("d", strtotime($reg['fecha_q_s_inicio']));
  $f_f_dia               = date("d", strtotime($reg['fecha_q_s_fin']));
  //FECHA SIMPLE SIN CAMBIOS
  $fechaInicio_sc     = date("Y-m-d", strtotime($reg['fecha_q_s_inicio']));
  $fechaFin_sc     = date("Y-m-d", strtotime($reg['fecha_q_s_fin']));

  // Convertir las fechas a objetos DateTime 
  $fechaInicio      = new DateTime($reg['fecha_q_s_inicio']);
  $fechaFin         = new DateTime($reg['fecha_q_s_fin']);


  // Crear una hoja por cada quincena
  if ($contador > 0) { $spreadsheet->createSheet(); }

  if ($fecha_pago_obrero == 'semanal') {
    $spreadsheet->setActiveSheetIndex($contador)->setTitle('S' . $numero_q_s);
  } else if ($fecha_pago_obrero == 'quincenal') {
    $spreadsheet->setActiveSheetIndex($contador)->setTitle('Q' . $numero_q_s);
  }  

  $hojaActiva = $spreadsheet->setActiveSheetIndex($contador);

  // ══════════════════════════════════════════ - P L A N T I L L A - ═════════════════════════════════════
  plantilla_stylo_header($spreadsheet, $fecha_pago_obrero);
  plantilla($spreadsheet, $fecha_pago_obrero);

  // ════════════════════- INSERTAMOS LOS NOMBRES DE LOS HEADS - ══════════════════════════════════════════
  plantilla_nombre_head($hojaActiva, $fecha_pago_obrero);
  $spreadsheet->getActiveSheet()->getStyle('D2')->getFont()->setBold(true);
  

  $hojaActiva->setCellValue('D5', $proyecto['e']['data']['nombre_proyecto']);
  $hojaActiva->setCellValue('D6', $proyecto['e']['data']['ubicacion']);
  $hojaActiva->setCellValue('D7', $proyecto['e']['data']['empresa']);

  // ══════════════════════════════════════════ - L O G O - ════════════════════════════════════════════════
  plantilla_logo($spreadsheet);
  $hojaActiva->mergeCells('A1:C4'); #imagen

  // ═════════════════════════════════ - D A T O  S   D E L   T R A B A J A D O R - ══════════════════════════════════════════

  $fila_1 = 11;
  
  if ($fecha_pago_obrero == 'semanal') {  
    //NOMBRE DEL MES
    $mes_f_i = nombre_mes($fechaInicio->format("Y-m-d")); 
    $mes_f_f = nombre_mes($fechaFin->format("Y-m-d"));   

    $hojaActiva->setCellValue('H8',"$f_i_dia de $mes_f_i al $f_f_dia de $mes_f_f"); //fecha ejem : 23 DE ABRIL AL 28 DE ABRIL

    $rspta_t = $formatos_varios->ver_detalle_sem_quin($ids_q_asistencia,$fechaInicio_sc, $fechaFin_sc, $id_proyecto, $n_f_i_p, $n_f_f_p);
    //echo json_encode($rspta_t['data'][0]['asistencia'],true);die();

    $fila_9=9;    $fila_10=9;

    foreach ($rspta_t['data'][0]['asistencia'] as $k => $val) {

      $partes = explode("-",  $val['fecha_asistencia']);
      $dia = $partes[2];
      $nombre_d=$val['nombre_dia'] =="Sábado"?substr($val['nombre_dia'], 0, 1)."a":substr($val['nombre_dia'], 0, 2);

      $hojaActiva->setCellValue($Colum_Semana[$k]  ."9",$nombre_d );
      $hojaActiva->setCellValue($Colum_Semana[$k]  ."10", $dia );

    }
    
    foreach ($rspta_t['data'] as $key => $reg) {

      $total_h = $reg['total_hn']+$reg['total_he'];
      $Pago = $reg['sueldo_diario']*$reg['total_dias_asistidos_hn'];

      $spreadsheet->getActiveSheet()->getStyle('A' . $fila_1)->getAlignment()->setHorizontal('center');
      $spreadsheet->getActiveSheet()->getStyle('A' . $fila_1 . ':T' . $fila_1)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));

      $hojaActiva->setCellValue('A' . $fila_1, ($key + 1));                 # Auto increment
      $hojaActiva->mergeCells('B' . $fila_1 . ':D' . $fila_1);              # unir columnas - apellidos y nombres
      $hojaActiva->setCellValue('B' . $fila_1, $reg['nombres']);            # apellidos y nombres   
      $hojaActiva->setCellValue('E' . $fila_1, $reg['numero_documento']);   # DNI
      $hojaActiva->setCellValue('F' . $fila_1, $reg['fecha_inicio_t']);     # Fecha incio trabajo
      $hojaActiva->setCellValue('G' . $fila_1, $reg['nombre_ocupacion']);   # Ocupacion

      //$Colum_Semana = array('H', 'I', 'J', 'K', 'L', 'M');
      foreach ($reg['asistencia'] as $key2 => $reg2) {
        $hojaActiva->setCellValue($Colum_Semana[$key2] . $fila_1, $reg2['horas_normal_dia']);
      }

      $hojaActiva->setCellValue('O' . $fila_1, $total_h);   # total HN
      $hojaActiva->setCellValue('P' . $fila_1, $reg['total_dias_asistidos_hn']);   # Total dias
      $hojaActiva->setCellValue('Q' . $fila_1, $reg['sueldo_mensual']);   # Sueldo Mensual
      $hojaActiva->setCellValue('R' . $fila_1, $reg['sueldo_diario']);   # Sueldo diario
      $hojaActiva->setCellValue('S' . $fila_1, $reg['sueldo_semanal']);   # Sueldo Semanal
      $hojaActiva->setCellValue('T' . $fila_1, $Pago);   # Pago Semanl o Quincenal
      $spreadsheet->getActiveSheet()->getStyle('S' . $fila_1)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
  
      $fila_1++;
    }  
    
  } else if ($fecha_pago_obrero == 'quincenal') {

    //NOMBRE DEL MES
    $mes_f_i = nombre_mes($fechaInicio->format("Y-m-d")); 
    $mes_f_f = nombre_mes($fechaFin->format("Y-m-d"));   

    $hojaActiva->setCellValue('H8',"$f_i_dia de $mes_f_i al $f_f_dia de $mes_f_f"); //fecha ejem : 23 DE ABRIL AL 28 DE ABRIL

    $rspta_t = $formatos_varios->ver_detalle_sem_quin($ids_q_asistencia,$fechaInicio_sc, $fechaFin_sc, $id_proyecto, $n_f_i_p, $n_f_f_p);
    //  echo json_encode($rspta_t['data'][0]['asistencia'],true);die();

    $fila_9=9;    $fila_10=9;

    foreach ($rspta_t['data'][0]['asistencia'] as $k => $val) {

      $partes = explode("-",  $val['fecha_asistencia']);
      $dia = $partes[2];

      $nombre_d=$val['nombre_dia'] =="Sábado"?substr($val['nombre_dia'], 0, 1)."a":substr($val['nombre_dia'], 0, 2);
      //  echo json_encode($nombre_d,true);die();
      $hojaActiva->setCellValue($Colum_quins[$k]  ."9",$nombre_d );
      $hojaActiva->setCellValue($Colum_quins[$k]  ."10", $dia );

    }

    foreach ($rspta_t['data'] as $key => $reg) {

      $total_h = $reg['total_hn']+$reg['total_he'];
      $Pago = $reg['sueldo_diario']*$reg['total_dias_asistidos_hn'];

      $spreadsheet->getActiveSheet()->getStyle('A' . $fila_1)->getAlignment()->setHorizontal('center');
      $spreadsheet->getActiveSheet()->getStyle('A' . $fila_1 . ':AA' . $fila_1)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));

      $hojaActiva->setCellValue('A' . $fila_1, ($key + 1));                 # Auto increment
      $hojaActiva->mergeCells('B' . $fila_1 . ':D' . $fila_1);              # unir columnas - apellidos y nombres
      $hojaActiva->setCellValue('B' . $fila_1, $reg['nombres']);            # apellidos y nombres   
      $hojaActiva->setCellValue('E' . $fila_1, $reg['numero_documento']);   # DNI
      $hojaActiva->setCellValue('F' . $fila_1, $reg['fecha_inicio_t']);     # Fecha incio trabajo
      $hojaActiva->setCellValue('G' . $fila_1, $reg['nombre_ocupacion']);   # Ocupacion

      // $Colum_quins = array('H', 'I', 'J', 'K', 'L', 'M', 'N','O', 'P', 'Q', 'R', 'S', 'T', 'U');
      foreach ($reg['asistencia'] as $key2 => $reg2) {
        $hojaActiva->setCellValue($Colum_quins[$key2] . $fila_1, $reg2['horas_normal_dia']);
      }

      $hojaActiva->setCellValue('V' . $fila_1, $total_h);   # total HN
      $hojaActiva->setCellValue('W' . $fila_1, $reg['total_dias_asistidos_hn']);   # Total dias
      $hojaActiva->setCellValue('X' . $fila_1, $reg['sueldo_mensual']);   # Sueldo Mensual
      $hojaActiva->setCellValue('Y' . $fila_1, $reg['sueldo_diario']);   # Sueldo diario
      $hojaActiva->setCellValue('Z' . $fila_1, $reg['sueldo_semanal']);   # Sueldo Semanal
      $hojaActiva->setCellValue('AA' . $fila_1, $Pago);   # Pago Semanl o Quincenal

      $spreadsheet->getActiveSheet()->getStyle('AA' . $fila_1)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
  
      $fila_1++;
    } 
  }

  $contador++;  
}

// redirect output to client browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Asistencia_trabajador.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');

function plantilla($hoja, $fecha_pago_obrero) {
  //FILAS A1 HASTA S4
  $hoja->getActiveSheet()->getSheetView()->setZoomScale(95);
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
    $hoja->getActiveSheet()->mergeCells('D1:T2'); //NOMBRE EMPRESA
    $hoja->getActiveSheet()->mergeCells('D3:T4'); //VACIO

    $hoja->getActiveSheet()->mergeCells('D5:T5'); //GET PROYECTO
    $hoja->getActiveSheet()->mergeCells('D6:T6'); //GET UBICACIÓN
    $hoja->getActiveSheet()->mergeCells('D7:T7'); //GET UBICACIÓN

    //DIMENCIONES PARA LOS DÍAS
    $hoja->getActiveSheet()->getColumnDimension('H')->setWidth(5); # D
    $hoja->getActiveSheet()->getColumnDimension('I')->setWidth(5); # L
    $hoja->getActiveSheet()->getColumnDimension('J')->setWidth(5); # M
    $hoja->getActiveSheet()->getColumnDimension('K')->setWidth(5); # M
    $hoja->getActiveSheet()->getColumnDimension('L')->setWidth(5); # J
    $hoja->getActiveSheet()->getColumnDimension('M')->setWidth(5); # V  
    $hoja->getActiveSheet()->getColumnDimension('N')->setWidth(5); # S  

    $hoja->getActiveSheet()->mergeCells('H8:N8');  # fecha ejem : 23 DE ABRIL AL 28 DE ABRIL

    // $hoja->getActiveSheet()->getColumnDimension('O')->setWidth(7); # dia
    $hoja->getActiveSheet()->getColumnDimension('P')->setWidth(7);# dia
    $hoja->getActiveSheet()->getColumnDimension('Q')->setWidth(15);# sueldo x mes
    $hoja->getActiveSheet()->getColumnDimension('R')->setWidth(15);# pago x dia
    $hoja->getActiveSheet()->getColumnDimension('S')->setWidth(15);# pago semanal
    $hoja->getActiveSheet()->getColumnDimension('T')->setWidth(15);# sueldo quicenal o semanal  

    // $hoja->getActiveSheet()->mergeCells('N8:N10'); # 
    $hoja->getActiveSheet()->mergeCells('O8:O10'); # HORAS
    $hoja->getActiveSheet()->mergeCells('P8:P10'); # DÍA
    $hoja->getActiveSheet()->mergeCells('Q8:Q10'); # SUELDO
    $hoja->getActiveSheet()->mergeCells('R8:R10'); # PAGO X DIA
    $hoja->getActiveSheet()->mergeCells('S8:S10'); # pago semanal
    $hoja->getActiveSheet()->mergeCells('T8:T10'); # SUELDO

  } else if ($fecha_pago_obrero == 'quincenal') {

    $hoja->getActiveSheet()->mergeCells('D1:AA2'); //NOMBRE EMPRESA
    $hoja->getActiveSheet()->mergeCells('D3:AA4'); //VACIO

    $hoja->getActiveSheet()->mergeCells('D5:AA5'); //GET PROYECTO
    $hoja->getActiveSheet()->mergeCells('D6:AA6'); //GET UBICACIÓN
    $hoja->getActiveSheet()->mergeCells('D7:AA7'); //GET UBICACIÓN

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
    $hoja->getActiveSheet()->getColumnDimension('U')->setWidth(5); # S    

    $hoja->getActiveSheet()->mergeCells('H8:U8');  # fecha ejem : 23 DE ABRIL AL 28 DE ABRIL

    $hoja->getActiveSheet()->getColumnDimension('W')->setWidth(7); # dia
    $hoja->getActiveSheet()->getColumnDimension('X')->setWidth(15);# sueldo x mes
    $hoja->getActiveSheet()->getColumnDimension('Y')->setWidth(15);# pago x dia
    $hoja->getActiveSheet()->getColumnDimension('Z')->setWidth(20);# pago semanal
    $hoja->getActiveSheet()->getColumnDimension('AA')->setWidth(15);# sueldo quicenal o semanal  

    // $hoja->getActiveSheet()->mergeCells('U8:U10'); 
    $hoja->getActiveSheet()->mergeCells('V8:V10'); # HORAS
    $hoja->getActiveSheet()->mergeCells('W8:W10'); # DÍA
    $hoja->getActiveSheet()->mergeCells('X8:X10'); # SUELDO
    $hoja->getActiveSheet()->mergeCells('Y8:Y10'); # PAGO X DIA
    $hoja->getActiveSheet()->mergeCells('Z8:Z10'); # pago semanal
    $hoja->getActiveSheet()->mergeCells('AA8:AA10'); # SUELDO
  }  
}

function plantilla_stylo_header($hoja, $fecha_pago_obrero) {

  // ══════════════════════════════════════════ - STYES HEADERS - ══════════════════════════════════════════
  $hoja->getActiveSheet()->getStyle('D1:AA2')->getFont()->setBold(true); # Empresa a cargo
  $hoja->getActiveSheet()->getStyle('A5:C7')->getFont()->setBold(true); # Proyecto, Ubicacion, Empresa  
  $hoja->getActiveSheet()->getStyle('A5:C7')->getAlignment()->setWrapText(true); # Proyecto, Ubicacion, Empresa

  $hoja->getActiveSheet()->getStyle('D1:AA4')->getAlignment()->setHorizontal('center');
  $hoja->getActiveSheet()->getStyle('A8:AA10')->getAlignment()->setHorizontal('center'); # titulos
  $hoja->getActiveSheet()->getStyle('A8:AA10')->getFont()->setBold(true); # titulos

  if ($fecha_pago_obrero == 'semanal') {
    $hoja->getActiveSheet()->getStyle('A1:T10')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
  } else if ($fecha_pago_obrero == 'quincenal') {
    $hoja->getActiveSheet()->getStyle('A1:AA10')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
  }  
}

function plantilla_nombre_head($hojaActiva, $fecha_pago_obrero) {
  $hojaActiva->setCellValue('D1', 'SEVEN´S INGENIEROS SELVA S.A.C.    R.U.C :  20609935651');

  $hojaActiva->setCellValue('A5', 'PROYECTO'); //PROYECTO
  $hojaActiva->setCellValue('A6', 'UBICACIÓN'); //UBICACIÓN
  $hojaActiva->setCellValue('A7', 'EMPRESA'); //EMPRESA

  $hojaActiva->setCellValue('A8', 'N°'); //N°
  $hojaActiva->setCellValue('B8', 'NOMBRES'); //NOMBRES
  $hojaActiva->setCellValue('E8', 'DNI'); //DNI
  $hojaActiva->setCellValue('F8', 'FECHA INGRESO'); //FECHA INGRESO
  $hojaActiva->setCellValue('G8', 'OCUPACIÓN'); //OCUPACIÓN  

  if ($fecha_pago_obrero == 'semanal') {
    $hojaActiva->setCellValue('O8', 'HORAS');       # HORAS
    $hojaActiva->setCellValue('P8', 'DÍA');         # DÍA
    $hojaActiva->setCellValue('Q8', 'SUELDO X MES');# SUELDO
    $hojaActiva->setCellValue('R8', 'PAGO X DIA');  # PAGO X DIA
    $hojaActiva->setCellValue('S8', 'PAGO SEMANAL');# pago semanal
    $hojaActiva->setCellValue('T8', 'SUELDO');      # SUELDO  
  } else if ($fecha_pago_obrero == 'quincenal') {
    $hojaActiva->setCellValue('V8', 'HORAS');       # HORAS
    $hojaActiva->setCellValue('W8', 'DÍA');         # DÍA
    $hojaActiva->setCellValue('X8', 'SUELDO X MES');# SUELDO
    $hojaActiva->setCellValue('Y8', 'PAGO X DIA');  # PAGO X DIA
    $hojaActiva->setCellValue('Z8', 'PAGO SEMANAL');# pago semanal
    $hojaActiva->setCellValue('AA8', 'SUELDO');      # SUELDO
  }  
}

function plantilla_logo($hoja) {
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
  $drawing->setWorksheet($hoja->getActiveSheet());
}




