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
$n_f_i_p          = $proyecto['data']['datos_proyecto']['fecha_inicio'];
$n_f_f_p          = $proyecto['data']['datos_proyecto']['fecha_fin'];
$id_proyecto      = $_GET["id_proyecto"];
$fecha_pago_obrero= $proyecto['data']['datos_proyecto']['fecha_pago_obrero'];
// var_dump($fecha_pago_obrero); die();
$num_quincena = 1; $contador = 0;

$dia_regular = 0; $estado_regular = false;

// Generar hojas por quincena
foreach ($proyecto['data']['s_q_asistencia'] as $key => $reg){

  $ids_q_asistencia =$reg['ids_q_asistencia']; 
  $numero_q_s =$reg['numero_q_s']; 
  // solo el dia ejem: 06 
  $f_i_dia               = date("d", strtotime($reg['fecha_q_s_inicio']));
  $f_f_dia               = date("d", strtotime($reg['fecha_q_s_fin']));
  //FECHA SIMPLE SIN CAMBIOS
  $fechaInicio_sc     = date("Y-m-d", strtotime($reg['fecha_q_s_inicio']));
  $fechaFin_sc        = date("Y-m-d", strtotime($reg['fecha_q_s_fin']));

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
  

  $hojaActiva->setCellValue('D5', $proyecto['data']['datos_proyecto']['nombre_proyecto']);
  $hojaActiva->setCellValue('D6', $proyecto['data']['datos_proyecto']['ubicacion']);
  $hojaActiva->setCellValue('D7', $proyecto['data']['datos_proyecto']['empresa']);

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
    $cont=0;
    $totalCant = count($rspta_t['data']);
    // echo json_encode($totalCantidad,true);die();
    foreach ($rspta_t['data'][0]['asistencia'] as $k => $val) {

      $partes = explode("-",  $val['fecha_asistencia']);
      $dia = $partes[2];
      $nombre_d=$val['nombre_dia'] =="Sábado"?substr($val['nombre_dia'], 0, 1)."a":substr($val['nombre_dia'], 0, 2);

      $hojaActiva->setCellValue($Colum_Semana[$k]  ."9",$nombre_d );
      $hojaActiva->setCellValue($Colum_Semana[$k]  ."10", $dia );

    }
    
    foreach ($rspta_t['data'] as $key => $reg) {
      $cont=$key + 1;
      $spreadsheet->getActiveSheet()->getStyle('A' . $fila_1)->getAlignment()->setHorizontal('center');
      $spreadsheet->getActiveSheet()->getStyle('A' . $fila_1 . ':O' . $fila_1)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));

      $hojaActiva->setCellValue('A' . $fila_1, ($cont));                 # Auto increment
      $hojaActiva->mergeCells('B' . $fila_1 . ':D' . $fila_1);              # unir columnas - apellidos y nombres
      $hojaActiva->setCellValue('B' . $fila_1, $reg['nombres']);            # apellidos y nombres   
      $hojaActiva->setCellValue('E' . $fila_1, $reg['numero_documento']);   # DNI
      $hojaActiva->setCellValue('F' . $fila_1, $reg['fecha_inicio_t']);     # Fecha incio trabajo
      $hojaActiva->setCellValue('G' . $fila_1, $reg['nombre_ocupacion']);   # Ocupacion

      //$Colum_Semana = array('H', 'I', 'J', 'K', 'L', 'M');
      foreach ($reg['asistencia'] as $key2 => $reg2) {
        $hojaActiva->setCellValue($Colum_Semana[$key2] . $fila_1, '');
      }

      $spreadsheet->getActiveSheet()->getStyle('O' . $fila_1)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
      //PROCESO PARA PINTAR FILAS VACIAS
      if ($cont==$totalCant) {

        $fila_2=$fila_1+1;

        for ($i = $cont; $i < $cont+5; $i++) {

          $spreadsheet->getActiveSheet()->getStyle('A' . $fila_2)->getAlignment()->setHorizontal('center');
          $spreadsheet->getActiveSheet()->getStyle('A' . $fila_2 . ':O' . $fila_2)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
    
          // echo json_encode($fila_1,true);die();
          $hojaActiva->setCellValue('A' . $fila_2, ($i+1));                 # Auto increment
          $hojaActiva->mergeCells('B' . $fila_2 . ':D' . $fila_2);              # unir columnas - apellidos y nombres
          $hojaActiva->setCellValue('B' . $fila_2, '');   # apellidos y nombres   
          $hojaActiva->setCellValue('E' . $fila_2, '');   # DNI
          $hojaActiva->setCellValue('F' . $fila_2, '');   # Fecha incio trabajo
          $hojaActiva->setCellValue('G' . $fila_2, '');   # Ocupacion

          $fila_2++;
        }

      }

      $fila_1++;

      
    }  
        // echo json_encode($fila_1,true);die();
    
  } else if ($fecha_pago_obrero == 'quincenal') {

    //NOMBRE DEL MES
    $mes_f_i = nombre_mes($fechaInicio->format("Y-m-d")); 
    $mes_f_f = nombre_mes($fechaFin->format("Y-m-d"));   

    $hojaActiva->setCellValue('H8',"$f_i_dia de $mes_f_i al $f_f_dia de $mes_f_f"); //fecha ejem : 23 DE ABRIL AL 28 DE ABRIL

    $rspta_t = $formatos_varios->ver_detalle_sem_quin($ids_q_asistencia,$fechaInicio_sc, $fechaFin_sc, $id_proyecto, $n_f_i_p, $n_f_f_p);
    //  echo json_encode($rspta_t['data'][0]['asistencia'],true);die();

    $fila_9=9;    $fila_10=9;
    $cont=0;
    $totalCant = count($rspta_t['data']);

    foreach ($rspta_t['data'][0]['asistencia'] as $k => $val) {

      $partes = explode("-",  $val['fecha_asistencia']);
      $dia = $partes[2];

      $nombre_d=$val['nombre_dia'] =="Sábado"?substr($val['nombre_dia'], 0, 1)."a":substr($val['nombre_dia'], 0, 2);
      //  echo json_encode($nombre_d,true);die();
      $hojaActiva->setCellValue($Colum_quins[$k]  ."9",$nombre_d );
      $hojaActiva->setCellValue($Colum_quins[$k]  ."10", $dia );

    }

    foreach ($rspta_t['data'] as $key => $reg) {
      $cont=$key + 1;
      $spreadsheet->getActiveSheet()->getStyle('A' . $fila_1)->getAlignment()->setHorizontal('center');
      $spreadsheet->getActiveSheet()->getStyle('A' . $fila_1 . ':V' . $fila_1)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));

      $hojaActiva->setCellValue('A' . $fila_1, ($cont));                 # Auto increment
      $hojaActiva->mergeCells('B' . $fila_1 . ':D' . $fila_1);              # unir columnas - apellidos y nombres
      $hojaActiva->setCellValue('B' . $fila_1, $reg['nombres']);            # apellidos y nombres   
      $hojaActiva->setCellValue('E' . $fila_1, $reg['numero_documento']);   # DNI
      $hojaActiva->setCellValue('F' . $fila_1, $reg['fecha_inicio_t']);     # Fecha incio trabajo
      $hojaActiva->setCellValue('G' . $fila_1, $reg['nombre_ocupacion']);   # Ocupacion

      // $Colum_quins = array('H', 'I', 'J', 'K', 'L', 'M', 'N','O', 'P', 'Q', 'R', 'S', 'T', 'U');
      foreach ($reg['asistencia'] as $key2 => $reg2) {
        $hojaActiva->setCellValue($Colum_quins[$key2] . $fila_1, '');
      }


      $spreadsheet->getActiveSheet()->getStyle('V' . $fila_1)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
      //PROCESO PARA PINTAR FILAS VACIAS
      if ($cont==$totalCant) {

        $fila_2=$fila_1+1;

        for ($i = $cont; $i < $cont+5; $i++) {

          $spreadsheet->getActiveSheet()->getStyle('A' . $fila_2)->getAlignment()->setHorizontal('center');
          $spreadsheet->getActiveSheet()->getStyle('A' . $fila_2 . ':V' . $fila_2)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
    
          // echo json_encode($fila_1,true);die();
          $hojaActiva->setCellValue('A' . $fila_2, ($i+1));                 # Auto increment
          $hojaActiva->mergeCells('B' . $fila_2 . ':D' . $fila_2);              # unir columnas - apellidos y nombres
          $hojaActiva->setCellValue('B' . $fila_2, '');   # apellidos y nombres   
          $hojaActiva->setCellValue('E' . $fila_2, '');   # DNI
          $hojaActiva->setCellValue('F' . $fila_2, '');   # Fecha incio trabajo
          $hojaActiva->setCellValue('G' . $fila_2, '');   # Ocupacion

          $fila_2++;
        }

      }
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
    $hoja->getActiveSheet()->mergeCells('D1:O2'); //NOMBRE EMPRESA
    $hoja->getActiveSheet()->mergeCells('D3:O4'); //VACIO

    $hoja->getActiveSheet()->mergeCells('D5:O5'); //GET PROYECTO
    $hoja->getActiveSheet()->mergeCells('D6:O6'); //GET UBICACIÓN
    $hoja->getActiveSheet()->mergeCells('D7:O7'); //GET UBICACIÓN

    //DIMENCIONES PARA LOS DÍAS
    $hoja->getActiveSheet()->getColumnDimension('H')->setWidth(5); # D
    $hoja->getActiveSheet()->getColumnDimension('I')->setWidth(5); # L
    $hoja->getActiveSheet()->getColumnDimension('J')->setWidth(5); # M
    $hoja->getActiveSheet()->getColumnDimension('K')->setWidth(5); # M
    $hoja->getActiveSheet()->getColumnDimension('L')->setWidth(5); # J
    $hoja->getActiveSheet()->getColumnDimension('M')->setWidth(5); # V  
    $hoja->getActiveSheet()->getColumnDimension('N')->setWidth(5); # S  

    $hoja->getActiveSheet()->mergeCells('H8:N8');  # fecha ejem : 23 DE ABRIL AL 28 DE ABRIL

    $hoja->getActiveSheet()->getColumnDimension('O')->setWidth(40); # DESCRIP 

    $hoja->getActiveSheet()->mergeCells('O8:O10'); # DESCRIP

  } else if ($fecha_pago_obrero == 'quincenal') {

    $hoja->getActiveSheet()->mergeCells('D1:V2'); //NOMBRE EMPRESA
    $hoja->getActiveSheet()->mergeCells('D3:V4'); //VACIO

    $hoja->getActiveSheet()->mergeCells('D5:V5'); //GET PROYECTO
    $hoja->getActiveSheet()->mergeCells('D6:V6'); //GET UBICACIÓN
    $hoja->getActiveSheet()->mergeCells('D7:V7'); //GET UBICACIÓN

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

    $hoja->getActiveSheet()->getColumnDimension('V')->setWidth(40); # dia
    $hoja->getActiveSheet()->mergeCells('V8:V10'); # DESCRIPCIÓN
    
  }  
}

function plantilla_stylo_header($hoja, $fecha_pago_obrero) {

  // ══════════════════════════════════════════ - STYES HEADERS - ══════════════════════════════════════════
  $hoja->getActiveSheet()->getStyle('D1:V2')->getFont()->setBold(true); # Empresa a cargo
  $hoja->getActiveSheet()->getStyle('A5:C7')->getFont()->setBold(true); # Proyecto, Ubicacion, Empresa  
  $hoja->getActiveSheet()->getStyle('A5:C7')->getAlignment()->setWrapText(true); # Proyecto, Ubicacion, Empresa

  $hoja->getActiveSheet()->getStyle('D1:V4')->getAlignment()->setHorizontal('center');
  $hoja->getActiveSheet()->getStyle('A8:V10')->getAlignment()->setHorizontal('center'); # titulos
  $hoja->getActiveSheet()->getStyle('A8:V10')->getFont()->setBold(true); # titulos

  if ($fecha_pago_obrero == 'semanal') {
    $hoja->getActiveSheet()->getStyle('A1:O10')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
  } else if ($fecha_pago_obrero == 'quincenal') {
    $hoja->getActiveSheet()->getStyle('A1:V10')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
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
    $hojaActiva->setCellValue('O8', 'DESCRIPCIÓN');       # DESCRIPCIÓN 
  } else if ($fecha_pago_obrero == 'quincenal') {
    $hojaActiva->setCellValue('V8', 'DESCRIPCIÓN');       # DESCRIPCIÓN
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




