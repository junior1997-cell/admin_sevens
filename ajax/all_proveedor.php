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
      require_once "../modelos/AllProveedor.php";

      $proveedor = new AllProveedor();

      $idproyecto   = isset($_POST["idproyecto"]) ? limpiarCadena($_POST["idproyecto"]) : "";
      $idproveedor  = isset($_POST["idproveedor"]) ? limpiarCadena($_POST["idproveedor"]) : "";
      $nombre       = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
      $tipo_documento = isset($_POST["tipo_documento"]) ? limpiarCadena($_POST["tipo_documento"]) : "";
      $num_documento= isset($_POST["num_documento"]) ? limpiarCadena($_POST["num_documento"]) : "";
      $direccion    = isset($_POST["direccion"]) ? limpiarCadena($_POST["direccion"]) : "";
      $telefono     = isset($_POST["telefono"]) ? limpiarCadena($_POST["telefono"]) : "";
      $c_bancaria   = isset($_POST["c_bancaria"]) ? limpiarCadena($_POST["c_bancaria"]) : "";
      $cci          = isset($_POST["cci"]) ? limpiarCadena($_POST["cci"]) : "";
      $c_detracciones = isset($_POST["c_detracciones"]) ? limpiarCadena($_POST["c_detracciones"]) : "";
      $banco        = isset($_POST["banco"]) ? limpiarCadena($_POST["banco"]) : "";
      $titular_cuenta= isset($_POST["titular_cuenta"]) ? limpiarCadena($_POST["titular_cuenta"]) : "";

      switch ($_GET["op"]) {

        case 'guardaryeditar':

          if (empty($idproveedor)) {
            $rspta = $proveedor->insertar($nombre, $tipo_documento, $num_documento, $direccion, $telefono, $c_bancaria, $cci, $c_detracciones, $banco, $titular_cuenta);
            echo json_encode($rspta, true);
          } else {
            $rspta = $proveedor->editar($idproveedor, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $c_bancaria, $cci, $c_detracciones, $banco, $titular_cuenta);
            echo json_encode($rspta, true);
          }
        break;

        case 'desactivar':
          $rspta = $proveedor->desactivar($_GET["id_tabla"]);
          echo json_encode($rspta, true);
        break;

        case 'eliminar':
          $rspta = $proveedor->eliminar($_GET["id_tabla"]);
          echo json_encode($rspta, true);
        break;

        case 'mostrar':
          $rspta = $proveedor->mostrar($idproveedor);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;

        case 'mostrar_mas_detalle':
          $rspta = $proveedor->mostrar($idproveedor);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;

        case 'tbla_principal':
          $rspta = $proveedor->tbla_principal();
          //Vamos a declarar un array
          $data = [];  $cont = 1; 

          $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

          if ($rspta['status']) {   
          
            foreach ($rspta['data'] as $key => $value) {   

              $razon_social = '';  $direccion = ''; $titular_cuenta = '';

              $razon_social = $value['razon_social'];
              $direccion = $value['direccion']; 
              $titular_cuenta = $value['titular_cuenta']; 
              
              $data[] = [
                "0" => $cont++,
                "1" => $value['estado'] ? '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $value['idproveedor'] . ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' .
                    ' <button class="btn btn-danger btn-sm" onclick="eliminar(' . $value['idproveedor'] .', \''.encodeCadenaHtml($value['razon_social']).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i></button>'.
                    ' <button class="btn btn-info btn-sm" onclick="ver_mas_detalles('.$value['idproveedor'].')" data-toggle="tooltip" data-original-title="Ver mas datalles."><i class="far fa-eye"></i></button>'
                    : '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $value['idproveedor'] . ')"><i class="fa fa-pencil-alt"></i></button>' .
                    ' <button class="btn btn-primary btn-sm" onclick="activar(' . $value['idproveedor'] . ')"><i class="fa fa-check"></i></button>',
                "2" => '<div class="user-block">
                  <span class="username ml-0" ><p class="text-primary m-b-02rem">' . $razon_social .'</p></span> 
                  <span class="description ml-0"><b>' . $value['tipo_documento'] . '</b>: ' . $value['ruc'] . ' </span>'.
                '</div>',
                "3" => $direccion . (empty($value['telefono'])? '' : '<br>' . '<span class="text-gray font-size-13px"><b>Cel:</b> <a href="tel:+51' . quitar_guion($value['telefono']) . '" data-toggle="tooltip" data-original-title="Llamar al PROVEEDOR.">' . $value['telefono'] . '</a></span>' ) ,
                "4" => ($value['nombre_banco'] == 'SIN BANCO' ? 'SIN BANCO' : '<div class="w-250px"><b>'.$value['nombre_banco'].':</b>' . $value['cuenta_bancaria'] . '<br> <b>CCI:</b> ' . $value['cci'] . '</div>' ).$toltip ,
                "5" => $titular_cuenta,                
                "6" => $value['razon_social'],
                "7" => $value['tipo_documento'],
                "8" => $value['ruc'],
                "9" => $value['nombre_banco'],
                "10" => $value['cuenta_bancaria'],
                "11" => $value['cci'],
                "12" => $value['cuenta_detracciones'],
                "13" => $value['titular_cuenta'],
                "14" => $value['direccion'],
                "15" => $value['telefono'],
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
