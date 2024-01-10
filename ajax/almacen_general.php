<?php
ob_start();
if (strlen(session_id()) < 1) {
  session_start(); //Validamos si existe o no la sesión
}

if (!isset($_SESSION["nombre"])) {
  $retorno = ['status' => 'login', 'message' => 'Tu sesion a terminado pe, inicia nuevamente', 'data' => []];
  echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
} else {

  if ($_SESSION['calendario'] == 1) {

    require_once "../modelos/Almacen_general.php";

    $almacen_general = new Almacen_general($_SESSION['idusuario']);

    date_default_timezone_set('America/Lima');
    $date_now = date("d-m-Y h.i.s A");
    $imagen_error = "this.src='../dist/svg/404-v2.svg'";
    $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

    $scheme_host =  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/admin_sevens/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/');

    // input no usados        
    $idalmacen_general  = isset($_POST["idalmacen_general"]) ? limpiarCadena($_POST["idalmacen_general"]) : "";
    $nombre_almacen     = isset($_POST["nombre_almacen"]) ? limpiarCadena($_POST["nombre_almacen"]) : "";
    $descripcion        = isset($_POST["descripcion"]) ? encodeCadenaHtml($_POST["descripcion"]) : "";

    // ::::::::::::::::::: ALMACEN GENERAL ::::::::::::::::::::::::::::::::::::::::::::
    $idalmacen_resumen_ag     = isset($_POST["idalmacen_resumen_ag"]) ? limpiarCadena($_POST["idalmacen_resumen_ag"]) : "";
    $idproyecto_ag     = isset($_POST["idproyecto_ag"]) ? limpiarCadena($_POST["idproyecto_ag"]) : "";
    $fecha_ingreso_ag  = isset($_POST["fecha_ingreso_ag"]) ? limpiarCadena($_POST["fecha_ingreso_ag"]) : "";
    $dia_ingreso_ag    = isset($_POST["dia_ingreso_ag"]) ? limpiarCadena($_POST["dia_ingreso_ag"]) : "";

    switch ($_GET["op"]) {

      case 'guardar_y_editar_almacen':

        if (empty($idalmacen_general)) {
          //var_dump($idproyecto,$idproveedor);
          $rspta = $almacen_general->insertar($nombre_almacen, $descripcion);
          echo json_encode($rspta, true);
        } else {
          $rspta = $almacen_general->editar($idalmacen_general, $nombre_almacen, $descripcion);
          //var_dump($idactivos_fijos,$idproveedor);
          echo json_encode($rspta, true);
        }

        break;

      case 'desactivar':
        $rspta = $almacen_general->desactivar($_GET["id_tabla"]);
        echo json_encode($rspta, true);
        break;

      case 'eliminar':
        $rspta = $almacen_general->eliminar($_GET["id_tabla"]);
        echo json_encode($rspta, true);
        break;

      case 'mostrar':
        $rspta = $almacen_general->mostrar($idalmacen_general);
        //Codificar el resultado utilizando json
        echo json_encode($rspta, true);
        break;

      case 'tabla_principal':
        $rspta = $almacen_general->tabla_principal($_GET["id_categoria"]);
        //Vamos a declarar un array
        $data = [];
        $cont = 1;

        if ($rspta['status'] == true) {
          foreach ($rspta['data'] as $key => $reg) {

            $data[] = [
              "0" => $cont++,
              "1" => $reg['estado'] ? '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg['idalmacen_general'] . ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' .
                ' <button class="btn btn-danger btn-sm" onclick="eliminar(' . $reg['idalmacen_general'] . ', \'' . encodeCadenaHtml($reg['nombre_almacen']) . '\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i></button>' .
                ' <!-- <button class="btn btn-info btn-sm" onclick="verdatos(' . $reg['idalmacen_general'] . ')" data-toggle="tooltip" data-original-title="Ver datos"><i class="far fa-eye"></i></button> -->' :
                '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg['idalmacen_general'] . ')"><i class="fa fa-pencil-alt"></i></button>' .
                ' <button class="btn btn-primary btn-sm" onclick="activar(' . $reg['idalmacen_general'] . ')"><i class="fa fa-check"></i></button>',
              "2" => $reg['nombre_almacen'],
              "3" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly="">' . $reg['descripcion'] . '</textarea>',
            ];
          }

          $results = [
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
            "data" => $data,
          ];

          echo json_encode($results);
        } else {
          echo $rspta['code_error'] . ' - ' . $rspta['message'] . ' ' . $rspta['data'];
        }

        break;

      case 'tabla_detalle':
        $rspta = $almacen_general->tabla_detalle($_GET["id_proyecto"], $_GET["id_almacen"]);
        //Vamos a declarar un array
        $data = [];
        $cont = 1;

        if ($rspta['status'] == true) {
          foreach ($rspta['data'] as $key => $reg) {

            $data[] = [
              "0" => $cont++,
              "1" => $reg['proyecto'],
              "2" => $reg['fecha_envio'],
              "3" =>  $reg['producto'],
              "4" =>  $reg['cantidad'],
            ];
          }

          $results = [
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
            "data" => $data,
          ];

          echo json_encode($results);
        } else {
          echo $rspta['code_error'] . ' - ' . $rspta['message'] . ' ' . $rspta['data'];
        }

        break;

      case 'lista_de_categorias':

        $rspta = $almacen_general->lista_de_categorias();
        //Codificar el resultado utilizando json
        echo json_encode($rspta, true);

        break;


        // ══════════════════════════════════════  A L M A C E N E S   G E N E R A L E S ══════════════════════════════════════
      case 'guardar_y_editar_almacen_general':

        if (empty($idalmacen_resumen_ag)) {
          $rspta = $almacen->crear_producto_ag($idproyecto_ag, $fecha_ingreso_ag, $dia_ingreso, $_POST["idproducto_ag"], $_POST["id_ar_ag"], $_POST["almacen_general_ag"], $_POST["cantidad_ag"]);
          echo json_encode($rspta, true);
        } else {
          $rspta = $almacen->editar_producto_ag();
          echo json_encode($rspta, true);
        }

        break;
      case 'select2_proyect':

        $rspta = $almacen_general->select2_proyect();
        $cont = 1;
        $data = "";

        if ($rspta['status'] == true) {

          foreach ($rspta['data'] as $key => $value) {
            $data .= '<option value="' . $value['idproyecto'] . '" >' . $value['nombre_codigo'] . '</option>';
          }

          $retorno = array(
            'status' => true,
            'message' => 'Salió todo ok',
            'data' => $data,
          );

          echo json_encode($retorno, true);
        } else {

          echo json_encode($rspta, true);
        }
        break;

      case 'select2ProductosComprados':

        $rspta = $almacen_general->select2_recursos_almacen($_GET["idproyecto"]);
        $cont = 1;
        $data = "";

        if ($rspta['status'] == true) {
          foreach ($rspta['data'] as $key => $value) {
            $data .= '<option style=" font-size: 12px; "  value="' . $value['idproducto'] . '" id_ar = "' . $value['idalmacen_resumen'] . '" unidad_medida="' . $value['unidad_medida'] . '" >' . $value['nombre_producto'] . ' - ' . $value['categoria'] . ' - Saldo: ' . $value['total_stok'] . ' - ' . $value['abreviacion'] . '</option>';
          }
          $retorno = array(
            'status' => true,
            'message' => 'Salió todo ok',
            'data' => $data,
          );
          echo json_encode($retorno, true);
        } else {
          echo json_encode($rspta, true);
        }
      break;

      
      case 'marcas_x_producto':
      $rspta = $almacen->marcas_x_producto($_POST["id_proyecto"], $_POST["id_producto"]);
      //Codificar el resultado utilizando json
      echo json_encode($rspta, true);
      break;

      default:
        $rspta = ['status' => 'error_code', 'message' => 'Te has confundido en escribir en el <b>swich.</b>', 'data' => []];
        echo json_encode($rspta, true);
        break;
    }
  } else {
    $retorno = ['status' => 'nopermiso', 'message' => 'Tu sesion a terminado pe, inicia nuevamente', 'data' => []];
    echo json_encode($retorno);
  }
}

ob_end_flush();
