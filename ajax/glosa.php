<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }
  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {
    
    require_once "../modelos/Glosa.php";

    $glosa = new Glosa();

    $idglosa            = isset($_POST["idglosa"]) ? limpiarCadena($_POST["idglosa"]) : "";
    $nombre_glosa       = isset($_POST["nombre_glosa"]) ? limpiarCadena($_POST["nombre_glosa"]) : "";
    $descripcion_glosa  = isset($_POST["descripcion_glosa"]) ? limpiarCadena($_POST["descripcion_glosa"]) : "";

    switch ($_GET["op"]) {
      case 'guardar_y_editar_glosa':
        if (empty($idglosa)) {
          $rspta = $glosa->insertar_glosa($nombre_glosa, $descripcion_glosa);
          echo json_encode( $rspta, true) ;
        } else {
          $rspta = $glosa->editar_glosa($idglosa, $nombre_glosa, $descripcion_glosa);
          echo json_encode( $rspta, true) ;
        }
      break;

      case 'desactivar':
        $rspta = $glosa->desactivar_glosa($_GET["id_tabla"]);
        echo json_encode( $rspta, true) ;
      break;

      case 'eliminar':
        $rspta = $glosa->eliminar_glosa($_GET["id_tabla"]);
        echo json_encode( $rspta, true) ;
      break;

      case 'mostrar_glosa':
        $rspta = $glosa->mostrar_glosa($idglosa);
        //Codificar el resultado utilizando json
        echo json_encode( $rspta, true) ;
      break;

      case 'tabla_principal_glosa':
        $rspta = $glosa->tabla_principal_glosa();
        //Vamos a declarar un array
        $data = []; $cont = 1;

        $toltip = '<script> $(function() { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

        if ($rspta['status']) {
          while ($reg = $rspta['data']->fetch_object()) {
            $data[] = [
              "0" => $cont++,
              "1" => $reg->estado ? '<button class="btn btn-warning btn-sm" onclick="mostrar_glosa(' . $reg->idglosa . ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' .
              ' <button class="btn btn-danger btn-sm" onclick="eliminar_glosa(' . $reg->idglosa .', \''.encodeCadenaHtml($reg->nombre_glosa).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i></button>'
              : '<button class="btn btn-warning btn-sm" onclick="mostrar_glosa(' . $reg->idglosa . ')"><i class="fa fa-pencil-alt"></i></button>' .
              ' <button class="btn btn-primary btn-sm" onclick="activar_glosa(' . $reg->idglosa . ')"><i class="fa fa-check"></i></button>',
              "2" => $reg->nombre_glosa,
              "3" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly="">' . $reg->descripcion . '</textarea>' ,
              "4" => ($reg->estado ? '<span class="text-center badge badge-success">Activado</span>' : '<span class="text-center badge badge-danger">Desactivado</span>').$toltip,
            ];
          }
          $results = [
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data,
          ];
          echo json_encode($results, true) ;
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
