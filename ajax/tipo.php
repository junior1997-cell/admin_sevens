<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {
    
    require_once "../modelos/Tipo.php";

    $tipo = new Tipo();

    $idtipo_trabajador = isset($_POST["idtipo_trabajador"]) ? limpiarCadena($_POST["idtipo_trabajador"]) : "";
    $nombre_tipo = isset($_POST["nombre_tipo"]) ? limpiarCadena($_POST["nombre_tipo"]) : "";

    switch ($_GET["op"]) {
      case 'guardaryeditar_tipo':
        if (empty($idtipo_trabajador)) {
          $rspta = $tipo->insertar($nombre_tipo);
          echo json_encode( $rspta, true) ;
        } else {
          $rspta = $tipo->editar($idtipo_trabajador, $nombre_tipo);
          echo json_encode( $rspta, true) ;
        }
      break;

      case 'desactivar_tipo':
        $rspta = $tipo->desactivar($_GET["id_tabla"]);
        echo json_encode( $rspta, true) ;
      break;

      case 'eliminar_tipo':
        $rspta = $tipo->eliminar($_GET["id_tabla"]);
        echo json_encode( $rspta, true) ;
      break;

      case 'mostrar_tipo':
        $rspta = $tipo->mostrar($idtipo_trabajador);
        //Codificar el resultado utilizando json
        echo json_encode( $rspta, true) ;
      break;

      case 'listar_tipo':
        $rspta = $tipo->listar();
        //Vamos a declarar un array
        $data = [];  $cont = 1;

        $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

        if ($rspta['status']) {
          while ($reg = $rspta['data']->fetch_object()) {
            $data[] = [
              "0" => $cont++,
              "1" => $reg->estado ? '<button class="btn btn-warning btn-sm" onclick="mostrar_tipo(' . $reg->idtipo_trabajador . ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-danger  btn-sm" onclick="eliminar_tipo(' .  $reg->idtipo_trabajador .', \''.encodeCadenaHtml($reg->nombre).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i> </button>'
                : '<button class="btn btn-warning btn-sm" onclick="mostrar_tipo(' . $reg->idtipo_trabajador . ')"><i class="fas fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-primary btn-sm" onclick="activar_tipo(' . $reg->idtipo_trabajador . ')"><i class="fa fa-check"></i></button>',
              "2" => $reg->nombre,
              "3" => ($reg->estado ? '<span class="text-center badge badge-success">Activado</span>' : '<span class="text-center badge badge-danger">Desactivado</span>').$toltip,
            ];
          }
          $results = [
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data,
          ];
          echo json_encode($results, true);
        } else {
          echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
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

      default: 
        $rspta = ['status'=>'error_code', 'message'=>'Te has confundido en escribir en el <b>swich.</b>', 'data'=>[]]; echo json_encode($rspta, true); 
      break;
    }
  }
  
  
  ob_end_flush();
?>
