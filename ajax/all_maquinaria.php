<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {
    if ($_SESSION['recurso'] == 1) {

      require_once "../modelos/AllMaquinaria.php";

      $all_maquinaria = new Allmaquinarias();
    
      //$idmaquinaria,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$c_bancaria,$c_detracciones,$banco,$titular_cuenta
      $idmaquinaria = isset($_POST["idmaquinaria"]) ? limpiarCadena($_POST["idmaquinaria"]) : "";
      $nombre_maquina = isset($_POST["nombre_maquina"]) ? limpiarCadena($_POST["nombre_maquina"]) : "";
      $codigo_m = isset($_POST["codigo_m"]) ? limpiarCadena($_POST["codigo_m"]) : "";
      $proveedor = isset($_POST["proveedor"]) ? limpiarCadena($_POST["proveedor"]) : "";
      $tipo = isset($_POST["tipo"]) ? limpiarCadena($_POST["tipo"]) : "";
    
      switch ($_GET["op"]) {
        case 'guardaryeditar':
          if (empty($idmaquinaria)) {
            $rspta = $all_maquinaria->insertar($nombre_maquina, $codigo_m, $proveedor, $tipo);
            echo json_encode($rspta, true);
          } else {
            $rspta = $all_maquinaria->editar($idmaquinaria, $nombre_maquina, $codigo_m, $proveedor, $tipo);
            echo json_encode($rspta, true);
          }
        break;
    
        case 'desactivar':

          $rspta = $all_maquinaria->desactivar($_GET["id_tabla"]);

          echo json_encode($rspta, true);

        break;    
    
        case 'eliminar':

          $rspta = $all_maquinaria->eliminar($_GET["id_tabla"]);

          echo json_encode($rspta, true);

        break;
    
        case 'mostrar':

          $rspta = $all_maquinaria->mostrar($idmaquinaria);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);

        break;
    
        case 'listar_maquinas':
           
          $rspta = $all_maquinaria->listar('1');
          
          //Vamos a declarar un array
          $data = []; $cont = 1;
          $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

          if ($rspta['status']) {
            while ($reg = $rspta['data']->fetch_object()) {
              $data[] = [
                "0" => $cont++,
                "1" => $reg->estado ? '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->idmaquinaria .')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-danger btn-sm" onclick="eliminar(' . $reg->idmaquinaria .', \''.encodeCadenaHtml($reg->nombre).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i></button>'
                  : '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->idmaquinaria . ')"><i class="fa fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-primary btn-sm" onclick="activar(' . $reg->idmaquinaria . ')"><i class="fa fa-check"></i></button>',
                "2" => $reg->nombre,
                "3" => $reg->modelo,
                "4" => $reg->razon_social,
                "5" => ($reg->estado ? '<span class="text-center badge badge-success">Activado</span>' : '<span class="text-center badge badge-danger">Desactivado</span>').$toltip,
                "6" => 'Maquinaria',
              ];
            }
            $results = [
              "sEcho" => 1, //Información para el datatables
              "iTotalRecords" => count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
              "data" => $data,
            ];
  
            echo json_encode($results, true);
          } else {
            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          }         
          
        break;

        case 'listar_equipos':
          $rspta = $all_maquinaria->listar('2');
          
          //Vamos a declarar un array
          $data = []; $cont = 1;

          if ($rspta['status']) {
            while ($reg = $rspta['data']->fetch_object()) {
              $data[] = [
                "0" => $cont++,
                "1" => $reg->estado ? '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->idmaquinaria .')"><i class="fas fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-danger btn-sm" onclick="eliminar(' . $reg->idmaquinaria . ')"><i class="fas fa-skull-crossbones"></i></button>'
                  : '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->idmaquinaria . ')"><i class="fa fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-primary btn-sm" onclick="activar(' . $reg->idmaquinaria . ')"><i class="fa fa-check"></i></button>',
                "2" => $reg->nombre,
                "3" => $reg->modelo,
                "4" => $reg->razon_social,
                "5" => $reg->estado ? '<span class="text-center badge badge-success">Activado</span>' : '<span class="text-center badge badge-danger">Desactivado</span>',
                "6" => 'Equipo',
              ];
            }
            $results = [
              "sEcho" => 1, //Información para el datatables
              "iTotalRecords" => count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
              "data" => $data,
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

    } else {
      $retorno = ['status'=>'nopermiso', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
      echo json_encode($retorno);
    }    
  } 
  
  ob_end_flush();
?>
