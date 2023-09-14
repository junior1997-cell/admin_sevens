<?php
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;

$spreadsheet = new Spreadsheet();
$spreadsheet->getProperties()->setCreator("Sevens Ingenieros")->setTitle("Formato Asistencia de obreros");

// ══════════════════════════════════════════ - D A T O S   D E L   T R A B A J A D O R - ══════════════════════════════════════════

require_once "../modelos/Formatos_varios.php";
$formatos_varios = new FormatosVarios();

$proyecto = $formatos_varios->datos_proyecto($_GET["id_proyecto"]);

$f1               = $proyecto['data']['fecha_inicio'];
$f2               = $proyecto['data']['fecha_fin'];
$id_proyecto      = $_GET["id_proyecto"];
$fecha_pago_obrero= $proyecto['data']['fecha_pago_obrero'];

// Convertir las fechas a objetos DateTime
$fechaInicio      = new DateTime($f1);
$fechaFin         = new DateTime($f2);

$num_quincena = 1; $contador = 0;

$dia_regular = 0; $estado_regular = false;

// Generar hojas por quincena
while ($fechaInicio <= $fechaFin) {
  // Crear una hoja por cada quincena
  if ($contador > 0) { $spreadsheet->createSheet(); }

  if ($fecha_pago_obrero == 'semanal') {
    $spreadsheet->setActiveSheetIndex($contador)->setTitle('S' . $num_quincena);
  } else if ($fecha_pago_obrero == 'quincenal') {
    $spreadsheet->setActiveSheetIndex($contador)->setTitle('Q' . $num_quincena);
  }  

  $hojaActiva = $spreadsheet->setActiveSheetIndex($contador);
  
  // $hojaActiva = $spreadsheet->getActiveSheet();

  // ══════════════════════════════════════════ - P L A N T I L L A - ══════════════════════════════════════════
  plantilla_stylo_header($spreadsheet, $fecha_pago_obrero);
  // plantilla($spreadsheet, $fecha_pago_obrero);

  // ══════════════════════════════════════════ - INSERTAMOS LOS NOMBRES DE LOS HEADS - ══════════════════════════════════════════
  plantilla_nombre_head($hojaActiva, $fecha_pago_obrero);
  $spreadsheet->getActiveSheet()->getStyle('D2')->getFont()->setBold(true);
  

  $hojaActiva->setCellValue('D5', $proyecto['data']['nombre_proyecto']);
  $hojaActiva->setCellValue('D6', $proyecto['data']['ubicacion']);
  $hojaActiva->setCellValue('D7', $proyecto['data']['empresa']);

  // ══════════════════════════════════════════ - L O G O - ══════════════════════════════════════════
  plantilla_logo($spreadsheet);
  $hojaActiva->mergeCells('A1:C4'); #imagen

  // ══════════════════════════════════════════ - D A T O  S   D E L   T R A B A J A D O R - ══════════════════════════════════════════
  
  $fila_1 = 11;

  $weekday_regular = $fechaInicio->format("w");
  if ($weekday_regular == "0") { $dia_regular = -1; 
  } else if ($weekday_regular == "1") { $dia_regular = -2;       
  } else if ($weekday_regular == "2") { $dia_regular = -3;         
  } else if ($weekday_regular == "3") { $dia_regular = -4;           
  } else if ($weekday_regular == "4") { $dia_regular = -5;           
  } else if ($weekday_regular == "5") { $dia_regular = -6;            
  } else if ($weekday_regular == "6") { $dia_regular = -7; }
  
  if ($fecha_pago_obrero == 'semanal') {   

    $f_i = nombre_mes($fechaInicio->format("Y-m-d")); 
    $fechaInicio->modify('+1 weeks'); # sumar 7 dias
    $f_f = nombre_mes($fechaInicio->format("Y-m-d"));    
    $hojaActiva->setCellValue('H8',"23 de $f_i AL de DE $f_f"); //fecha ejem : 23 DE ABRIL AL 28 DE ABRIL

    $rspta_t = $formatos_varios->ver_datos_trabajador($f1, $f2, $id_proyecto, $num_quincena, 7);  #echo json_encode($rspta_t) ; die();

    $fechaInicio->modify('+1 day'); # sumar 1 dias

    foreach ($rspta_t['data'] as $key => $reg) {
      $spreadsheet->getActiveSheet()->getStyle('A' . $fila_1)->getAlignment()->setHorizontal('center');
      $spreadsheet->getActiveSheet()->getStyle('A' . $fila_1 . ':S' . $fila_1)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
      // $spreadsheet->getActiveSheet()->getStyle('A' . $fila_1, ($key + 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
      // $spreadsheet->getActiveSheet()->getStyle('B' . $fila_1, $reg['trabajador'])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
  
      $hojaActiva->setCellValue('A' . $fila_1, ($key + 1));                 # Auto increment
      $hojaActiva->mergeCells('B' . $fila_1 . ':D' . $fila_1);              # unir columnas - apellidos y nombres
      $hojaActiva->setCellValue('B' . $fila_1, $reg['nombres_trabajador']); # apellidos y nombres   
      $hojaActiva->setCellValue('E' . $fila_1, $reg['numero_documento']);   # DNI
      $hojaActiva->setCellValue('F' . $fila_1, $reg['fecha_inicio_t']);     # Fecha incio trabajo
      $hojaActiva->setCellValue('G' . $fila_1, $reg['nombre_ocupacion']);   # Ocupacion
  
      $hojaActiva->setCellValue('N' . $fila_1, $reg['total_hn']);   # total HN
      $hojaActiva->setCellValue('O' . $fila_1, $reg['total_dias_asistidos']);   # Total dias
      $hojaActiva->setCellValue('P' . $fila_1, $reg['sueldo_mensual']);   # Sueldo Mensual
      $hojaActiva->setCellValue('Q' . $fila_1, $reg['sueldo_diario']);   # Sueldo diario
      $hojaActiva->setCellValue('R' . $fila_1, $reg['sueldo_semanal']);   # Sueldo Semanal
      $hojaActiva->setCellValue('S' . $fila_1, $reg['pago_quincenal']);   # Pago Semanl o Quincenal
      $spreadsheet->getActiveSheet()->getStyle('S' . $fila_1)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
  
      $fila_1++;
    }  
    
  } else if ($fecha_pago_obrero == 'quincenal') {

    $f_i = nombre_mes($fechaInicio->format("Y-m-d")); $d_i = $fechaInicio->format("d"); $f_ii = $fechaInicio->format("Y-m-d");
    if ($estado_regular) { $fechaInicio->modify('+13 day'); } else { $sum_dia = 14 + $dia_regular; $fechaInicio->modify("+$sum_dia day"); $estado_regular = true; } 
    $f_f = nombre_mes($fechaInicio->format("Y-m-d")); $d_f = $fechaInicio->format("d"); $f_ff = $fechaInicio->format("Y-m-d");
    $hojaActiva->setCellValue('H8',"$d_i de $f_i AL $d_f de $f_f"); //fecha ejem : 23 DE ABRIL AL 28 DE ABRIL

    $rspta_t = $formatos_varios->ver_datos_trabajador($f_ii, $f_ff, $id_proyecto, $num_quincena, 14);  echo json_encode($rspta_t) ; die();

    $fechaInicio->modify('+1 day'); # sumar 1 dias

    foreach ($rspta_t['data'] as $key => $reg) {
      $spreadsheet->getActiveSheet()->getStyle('A' . $fila_1)->getAlignment()->setHorizontal('center');
      $spreadsheet->getActiveSheet()->getStyle('A' . $fila_1 . ':Z' . $fila_1)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
      // $spreadsheet->getActiveSheet()->getStyle('A' . $fila_1, ($key + 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
      // $spreadsheet->getActiveSheet()->getStyle('B' . $fila_1, $reg['trabajador'])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
  
      $hojaActiva->setCellValue('A' . $fila_1, ($key + 1));                 # Auto increment
      $hojaActiva->mergeCells('B' . $fila_1 . ':D' . $fila_1);              # unir columnas - apellidos y nombres
      $hojaActiva->setCellValue('B' . $fila_1, $reg['nombres_trabajador']); # apellidos y nombres   
      $hojaActiva->setCellValue('E' . $fila_1, $reg['numero_documento']);   # DNI
      $hojaActiva->setCellValue('F' . $fila_1, $reg['fecha_inicio_t']);     # Fecha incio trabajo
      $hojaActiva->setCellValue('G' . $fila_1, $reg['nombre_ocupacion']);   # Ocupacion
  
      $hojaActiva->setCellValue('U' . $fila_1, $reg['total_hn']);   # total HN
      $hojaActiva->setCellValue('V' . $fila_1, $reg['total_dias_asistidos']);   # Total dias
      $hojaActiva->setCellValue('W' . $fila_1, $reg['sueldo_mensual']);   # Sueldo Mensual
      $hojaActiva->setCellValue('X' . $fila_1, $reg['sueldo_diario']);   # Sueldo diario
      $hojaActiva->setCellValue('Y' . $fila_1, $reg['sueldo_semanal']);   # Sueldo Semanal
      $hojaActiva->setCellValue('Z' . $fila_1, $reg['pago_quincenal']);   # Pago Semanl o Quincenal
      $spreadsheet->getActiveSheet()->getStyle('Z' . $fila_1)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
  
      $fila_1++;
    } 
  }

  // Incrementar variables
  $num_quincena++;
  $contador++;  
}

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


// function plantilla($hoja, $fecha_pago_obrero) {
//   //FILAS A1 HASTA S4
//   $hoja->getActiveSheet()->getSheetView()->setZoomScale(95);
//   $hoja->getActiveSheet()->getStyle('A:Z')->getAlignment()->setVertical('center');    

//   //INICIO FILAS A5 A6 A7 HASTA S5 S6 S7
//   $hoja->getActiveSheet()->mergeCells('A5:C5'); //PROYECTO
//   $hoja->getActiveSheet()->mergeCells('A6:C6'); //UBICACIÓN
//   $hoja->getActiveSheet()->mergeCells('A7:C7'); //EMPRESA  

//   // ══════════════════════════════════════════ - FILAS A8 A9 A10 - ══════════════════════════════════════════
//   //DIMENCIONES
//   $hoja->getActiveSheet()->getColumnDimension('A')->setWidth(5);  # N°
//   $hoja->getActiveSheet()->getColumnDimension('D')->setWidth(25); # nombre
//   $hoja->getActiveSheet()->getColumnDimension('E')->setWidth(15); # dni
//   $hoja->getActiveSheet()->getColumnDimension('F')->setWidth(15); # fecha de ingreso
//   $hoja->getActiveSheet()->getColumnDimension('G')->setWidth(20); # ocupacion   
//   // MERGE
//   $hoja->getActiveSheet()->mergeCells('A8:A10'); # N°
//   $hoja->getActiveSheet()->mergeCells('B8:D10'); # NOMBRES Y APELLIDOS
//   $hoja->getActiveSheet()->mergeCells('E8:E10'); # DNI
//   $hoja->getActiveSheet()->mergeCells('F8:F10'); # FECHA INGRESO
//   $hoja->getActiveSheet()->mergeCells('G8:G10'); # OCUPACIÓN

//   if ($fecha_pago_obrero == 'semanal') {
//     $hoja->getActiveSheet()->mergeCells('D1:S2'); //NOMBRE EMPRESA
//     $hoja->getActiveSheet()->mergeCells('D3:S4'); //VACIO

//     $hoja->getActiveSheet()->mergeCells('D5:S5'); //GET PROYECTO
//     $hoja->getActiveSheet()->mergeCells('D6:S6'); //GET UBICACIÓN
//     $hoja->getActiveSheet()->mergeCells('D7:S7'); //GET UBICACIÓN

//     //DIMENCIONES PARA LOS DÍAS
//     $hoja->getActiveSheet()->getColumnDimension('H')->setWidth(5); # D
//     $hoja->getActiveSheet()->getColumnDimension('I')->setWidth(5); # L
//     $hoja->getActiveSheet()->getColumnDimension('J')->setWidth(5); # M
//     $hoja->getActiveSheet()->getColumnDimension('K')->setWidth(5); # M
//     $hoja->getActiveSheet()->getColumnDimension('L')->setWidth(5); # J
//     $hoja->getActiveSheet()->getColumnDimension('M')->setWidth(5); # V  

//     $hoja->getActiveSheet()->mergeCells('H8:M8');  # fecha ejem : 23 DE ABRIL AL 28 DE ABRIL

//     $hoja->getActiveSheet()->getColumnDimension('O')->setWidth(7); # dia
//     $hoja->getActiveSheet()->getColumnDimension('P')->setWidth(15);# sueldo x mes
//     $hoja->getActiveSheet()->getColumnDimension('Q')->setWidth(15);# pago x dia
//     $hoja->getActiveSheet()->getColumnDimension('R')->setWidth(20);# pago semanal
//     $hoja->getActiveSheet()->getColumnDimension('S')->setWidth(15);# sueldo quicenal o semanal  

//     $hoja->getActiveSheet()->mergeCells('N8:N10'); # HORAS
//     $hoja->getActiveSheet()->mergeCells('O8:O10'); # DÍA
//     $hoja->getActiveSheet()->mergeCells('P8:P10'); # SUELDO
//     $hoja->getActiveSheet()->mergeCells('Q8:Q10'); # PAGO X DIA
//     $hoja->getActiveSheet()->mergeCells('R8:R10'); # pago semanal
//     $hoja->getActiveSheet()->mergeCells('S8:S10'); # SUELDO
//   } else if ($fecha_pago_obrero == 'quincenal') {
//     $hoja->getActiveSheet()->mergeCells('D1:Z2'); //NOMBRE EMPRESA
//     $hoja->getActiveSheet()->mergeCells('D3:Z4'); //VACIO

//     $hoja->getActiveSheet()->mergeCells('D5:Z5'); //GET PROYECTO
//     $hoja->getActiveSheet()->mergeCells('D6:Z6'); //GET UBICACIÓN
//     $hoja->getActiveSheet()->mergeCells('D7:Z7'); //GET UBICACIÓN

//     //DIMENCIONES PARA LOS DÍAS
//     $hoja->getActiveSheet()->getColumnDimension('H')->setWidth(5); # D
//     $hoja->getActiveSheet()->getColumnDimension('I')->setWidth(5); # L
//     $hoja->getActiveSheet()->getColumnDimension('J')->setWidth(5); # M
//     $hoja->getActiveSheet()->getColumnDimension('K')->setWidth(5); # M
//     $hoja->getActiveSheet()->getColumnDimension('L')->setWidth(5); # J
//     $hoja->getActiveSheet()->getColumnDimension('M')->setWidth(5); # V
//     $hoja->getActiveSheet()->getColumnDimension('N')->setWidth(5); # S
//     $hoja->getActiveSheet()->getColumnDimension('O')->setWidth(5); # D
//     $hoja->getActiveSheet()->getColumnDimension('P')->setWidth(5); # L
//     $hoja->getActiveSheet()->getColumnDimension('Q')->setWidth(5); # M
//     $hoja->getActiveSheet()->getColumnDimension('R')->setWidth(5); # M
//     $hoja->getActiveSheet()->getColumnDimension('S')->setWidth(5); # J 
//     $hoja->getActiveSheet()->getColumnDimension('T')->setWidth(5); # V    

//     $hoja->getActiveSheet()->mergeCells('H8:T8');  # fecha ejem : 23 DE ABRIL AL 28 DE ABRIL

//     $hoja->getActiveSheet()->getColumnDimension('U')->setWidth(7); # dia
//     $hoja->getActiveSheet()->getColumnDimension('W')->setWidth(15);# sueldo x mes
//     $hoja->getActiveSheet()->getColumnDimension('X')->setWidth(15);# pago x dia
//     $hoja->getActiveSheet()->getColumnDimension('Y')->setWidth(20);# pago semanal
//     $hoja->getActiveSheet()->getColumnDimension('Z')->setWidth(15);# sueldo quicenal o semanal  

//     $hoja->getActiveSheet()->mergeCells('U8:U10'); # HORAS
//     $hoja->getActiveSheet()->mergeCells('V8:V10'); # DÍA
//     $hoja->getActiveSheet()->mergeCells('W8:W10'); # SUELDO
//     $hoja->getActiveSheet()->mergeCells('X8:X10'); # PAGO X DIA
//     $hoja->getActiveSheet()->mergeCells('Y8:Y10'); # pago semanal
//     $hoja->getActiveSheet()->mergeCells('Z8:Z10'); # SUELDO
//   }  
// }

function plantilla_stylo_header($hoja, $fecha_pago_obrero) {

  // ══════════════════════════════════════════ - STYES HEADERS - ══════════════════════════════════════════
  $hoja->getActiveSheet()->getStyle('D1:Z2')->getFont()->setBold(true); # Empresa a cargo
  $hoja->getActiveSheet()->getStyle('A5:C7')->getFont()->setBold(true); # Proyecto, Ubicacion, Empresa  
  $hoja->getActiveSheet()->getStyle('A5:C7')->getAlignment()->setWrapText(true); # Proyecto, Ubicacion, Empresa

  $hoja->getActiveSheet()->getStyle('D1:Z4')->getAlignment()->setHorizontal('center');
  $hoja->getActiveSheet()->getStyle('A8:Z10')->getAlignment()->setHorizontal('center'); # titulos
  $hoja->getActiveSheet()->getStyle('A8:Z10')->getFont()->setBold(true); # titulos

  if ($fecha_pago_obrero == 'semanal') {
    $hoja->getActiveSheet()->getStyle('A1:S10')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
  } else if ($fecha_pago_obrero == 'quincenal') {
    $hoja->getActiveSheet()->getStyle('A1:Z10')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
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
    $hojaActiva->setCellValue('N8', 'HORAS');       # HORAS
    $hojaActiva->setCellValue('O8', 'DÍA');         # DÍA
    $hojaActiva->setCellValue('P8', 'SUELDO X MES');# SUELDO
    $hojaActiva->setCellValue('Q8', 'PAGO X DIA');  # PAGO X DIA
    $hojaActiva->setCellValue('R8', 'PAGO SEMANAL');# pago semanal
    $hojaActiva->setCellValue('S8', 'SUELDO');      # SUELDO  
  } else if ($fecha_pago_obrero == 'quincenal') {
    $hojaActiva->setCellValue('U8', 'HORAS');       # HORAS
    $hojaActiva->setCellValue('V8', 'DÍA');         # DÍA
    $hojaActiva->setCellValue('W8', 'SUELDO X MES');# SUELDO
    $hojaActiva->setCellValue('X8', 'PAGO X DIA');  # PAGO X DIA
    $hojaActiva->setCellValue('Y8', 'PAGO SEMANAL');# pago semanal
    $hojaActiva->setCellValue('Z8', 'SUELDO');      # SUELDO
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
