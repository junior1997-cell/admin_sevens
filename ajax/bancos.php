<?php
ob_start();
if (strlen(session_id()) < 1) {
  session_start(); //Validamos si existe o no la sesión
}
require_once "../modelos/Bancos.php";

$bancos = new Bancos();

$idbancos = isset($_POST["idbancos"]) ? limpiarCadena($_POST["idbancos"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$alias = isset($_POST["alias"]) ? limpiarCadena($_POST["alias"]) : "";

$formato_cci = isset($_POST["formato_cci"]) ? limpiarCadena($_POST["formato_cci"]) : "";
$formato_cta = isset($_POST["formato_cta"]) ? limpiarCadena($_POST["formato_cta"]) : "";
$formato_detracciones = isset($_POST["formato_detracciones"]) ? limpiarCadena($_POST["formato_detracciones"]) : "";

switch ($_GET["op"]) {
  case 'guardaryeditar_bancos':
    if (empty($idbancos)) {
      $rspta = $bancos->insertar($nombre, $alias, $formato_cta, $formato_cci, $formato_detracciones);
      echo $rspta ? "ok" : "Banco no se pudo registrar";
    } else {
      $rspta = $bancos->editar($idbancos, $nombre, $alias, $formato_cta, $formato_cci, $formato_detracciones);
      echo $rspta ? "ok" : "Banco no se pudo actualizar";
    }
  break;

  case 'desactivar_bancos':
    $rspta = $bancos->desactivar($idbancos);
    echo $rspta ? "Banco Desactivada" : "Banco no se puede desactivar";
  break;

  case 'activar_bancos':
    $rspta = $bancos->activar($idbancos);
    echo $rspta ? "Banco activada" : "Banco no se puede activar";
  break;

  case 'eliminar_bancos':
    $rspta = $bancos->eliminar($idbancos);
    echo $rspta ? "Banco Eliminado" : "Banco no se puede Eliminar";
  break;

  case 'mostrar_bancos':
    $rspta = $bancos->mostrar($idbancos);
    //Codificar el resultado utilizando json
    echo json_encode($rspta);
  break;

  case 'listar':
    $rspta = $bancos->listar();
    //Vamos a declarar un array
    $data = [];

    $cta = "00000000000000000000000000000";
    $cci = "00000000000000000000000000000";
    $detraccion = "00000000000000000000000000000";
    $cont=1;
    while ($reg = $rspta->fetch_object()) {
      $data[] = [
        "0"=>$cont++,
        "1" => $reg->estado
          ? '<button class="btn btn-warning btn-sm" onclick="mostrar_bancos(' . $reg->idbancos . ')"><i class="fas fa-pencil-alt"></i></button>' .
            ' <button class="btn btn-danger  btn-sm" onclick="eliminar_bancos(' . $reg->idbancos . ')"><i class="fas fa-skull-crossbones"></i> </button>':
            '<button class="btn btn-warning btn-sm" onclick="mostrar_bancos(' . $reg->idbancos . ')"><i class="fas fa-pencil-alt"></i></button>' . 
            ' <button class="btn btn-primary btn-sm" onclick="activar_bancos(' . $reg->idbancos . ')"><i class="fa fa-check"></i></button>',
        "2" => '<span class="username"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'. $reg->nombre .'</p></span>
          <span class="text-muted">'. $reg->alias .' </span>',
        "3" =>
          '<span> <b>Formato CTA :</b>' .
          $reg->formato_cta .
          '<br> 
						<b>Ej. cta: </b>' .
          darFormatoBanco($cta, $reg->formato_cta) .
          '</span> 
						<br> <span> <b>Formato CCI :</b>' .
          $reg->formato_cci .
          '<br>  
						<b>Ej. cci: </b>' .
          darFormatoBanco($cci, $reg->formato_cci) .
          '</span>
						<br> <span> <b>Formato Detrac. :</b>' .
          $reg->formato_detracciones .
          '<br>  
						<b>Ej. cci: </b>' .
          darFormatoBanco($detraccion, $reg->formato_detracciones) .
          '</span>',
        "4" => $reg->estado ? '<span class="text-center badge badge-success">Activado</span>' : '<span class="text-center badge badge-danger">Desactivado</span>',
      ];
    }
    $results = [
      "sEcho" => 1, //Información para el datatables
      "iTotalRecords" => count($data), //enviamos el total registros al datatable
      "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
      "aaData" => $data,
    ];
    echo json_encode($results);

  break;
  case "selectbancos":
    $rspta = $bancos->select();

    while ($reg = $rspta->fetch_object()) {
      echo '<option  value=' . $reg->nombre . '>' . $reg->nombre . '</option>';
    }
    break;
  case "selectbancos_2":
    $rspta = $bancos->select();

    while ($reg = $rspta->fetch_object()) {
      echo '<option  value=' . $reg->idbancos . '>' . $reg->nombre . '</option>';
    }
  break;
  case 'salir':
    //Limpiamos las variables de sesión
    session_unset();
    //Destruìmos la sesión
    session_destroy();
    //Redireccionamos al login
    header("Location: ../index.php");

  break;
}

function darFormatoBanco($numero, $formato)
{
  $format_array = explode("-", $formato);
  $format_cuenta = "";
  $cont_format = 0;
  $indi = 0;

  foreach ($format_array as $indice => $key) {
    if ($key == "__" || $key == "0_" || $key == "1_" || $key == "2_" || $key == "3_" || $key == "4_" || $key == "5_" || $key == "6_" || $key == "7_" || $key == "8_" || $key == "9_") {
      $cont_format = $cont_format + 0;
    } else {
      if (intval($key) == 0) {
        $format_cuenta .= substr($numero, $cont_format, $key);

        $cont_format = $cont_format + intval($key); //$indi = $indice;
      } else {
        $format_cuenta .= substr($numero, $cont_format, $key) . '-';

        $cont_format = $cont_format + intval($key);
      }
    }
  }
  return substr($format_cuenta, 0, -1);
}

ob_end_flush();
?>
