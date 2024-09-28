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

    $idalmacen_general_ag = isset($_POST["idalmacen_general_ag"]) ? limpiarCadena($_POST["idalmacen_general_ag"]) : "";
    $fecha_ingreso_ag     = isset($_POST["fecha_ingreso_ag"]) ? limpiarCadena($_POST["fecha_ingreso_ag"]) : "";
    $dia_ingreso_ag       = isset($_POST["dia_ingreso_ag"]) ? limpiarCadena($_POST["dia_ingreso_ag"]) : "";

    // ::::::::::::::::::: TRANSFERENCIAS ALMACEN GENERAL O A PROYECTO ::::::::::::::::::::::::::::::::

    /*$tranferencia              = isset($_POST["tranferencia"]) ? limpiarCadena($_POST["tranferencia"]) : "";
    $name_alm_proyecto         = isset($_POST["name_alm_proyecto"]) ? limpiarCadena($_POST["name_alm_proyecto"]) : "";
    $fecha_transf_proy_alm     = isset($_POST["fecha_transf_proy_alm"]) ? limpiarCadena($_POST["fecha_transf_proy_alm"]) : "";
    $idalmacen_general_origen  = isset($_POST["idalmacen_general_origen"]) ? limpiarCadena($_POST["idalmacen_general_origen"]) : "";*/

    
    // ::::::::::::::::::: INGRESO DIRECTO  ::::::::::::::::::::::::::::::::::::::::::::
    $almacen_tup            = isset($_POST["almacen_tup"])? limpiarCadena($_POST["almacen_tup"]):"";   
    $fecha_tup	            = isset($_POST["fecha_tup"])? limpiarCadena($_POST["fecha_tup"]):"";

    switch ($_GET["op"]) {

        // ══════════════════════════════════════ secc 1 ════════════════════════════════

        //I N I C I O  A L M A C E N E S  G E N E R A L E S

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

        //F I N  A L M A C E N E S  G E N E R A L E S

        // ══════════════════════════════════════ secc 2 ════════════════════════════════

        //I N I C I O  I N S E R T  Y  L I S T  D A T A  E N  L O S  A L M A C E N E S  G.

        //Listar almacenes en la cabecera
      case 'lista_de_categorias':
        $rspta = $almacen_general->lista_de_categorias();
        echo json_encode($rspta, true);
      break;

      case 'tabla_detalle':

        $rspta = $almacen_general->tabla_detalle($_GET["id_proyecto"], $_GET["id_almacen"], $_GET['stock']);

        $data = [];
        $cont = 1;

        if ($rspta['status'] == true) {

          foreach ($rspta['data'] as $key => $reg) {
            $data[] = [
              "0" => $cont++,
              "1" => '<textarea cols="70" rows="2" class="textarea_datatable bg-light w-100 " readonly="" style=" font-size: 11px;">' . $reg['nombre_producto'] . ' - ' . $reg['abreviacion'] . '</textarea>',
              "2" => $reg['total_stok'],
              "3" =>  $reg['total_ingreso'],
              "4" =>  $reg['total_egreso'],
              "5" => '<button type="button" class="btn btn-info btn-sm" onclick="detalle_almacen_general(' . $reg['idalmacen_general'] . ', \'' . encodeCadenaHtml($reg['idalmacen_general_resumen']) . '\', \'' . encodeCadenaHtml($reg['nombre_producto']) . '\')" data-toggle="tooltip" data-original-title="Ver Movimientos"><i class="far fa-eye"></i></button>'.$toltip
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

      case 'tabla_detalle_almacen_general':

        $rspta = $almacen_general->tabla_detalle_almacen_general($_GET["id_almacen_transf"], $_GET["idalmacen_general_resumen"]);

        $data = [];
        $cont = 1;

        if ($rspta['status'] == true) {

          foreach ($rspta['data'] as $key => $reg) {
            $data[] = [
              "0" => $cont++,
              "1" => '<textarea cols="70" rows="2" class="textarea_datatable bg-light w-100 " readonly="" style=" font-size: 11px;">' . $reg['tipo_movimiento'] . '</textarea>',
              "2" => $reg['fecha'],
              "3" =>  $reg['cantidad'],
              "4" =>  $reg['nombre_proyecto_almacen']
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

      case 'guardar_y_editar_almacen_general':

        if (!empty($idalmacen_general_ag)) {

          $rspta = $almacen_general->insertar_alm_general(
            $idalmacen_general_ag,
            $fecha_ingreso_ag,
            $dia_ingreso_ag,
            $_POST["idproducto_ag"],
            $_POST["proyecto_ag"],
            $_POST["id_ar_ag"],
            $_POST["cantidad_ag"],
            $_POST["stok"],
            $_POST["t_egreso"],
            $_POST["t_ingreso"],
            $_POST['tipo_mov']
          );

          echo json_encode($rspta, true);
        } else {
          echo json_encode(['status' => true, 'message' => 'todo oka ps', 'data' => ''], true);
        }

      break;
      
      case 'marcas_x_producto':
        $rspta = $almacen_general->marcas_x_producto($_POST["id_producto"]);          
        //Codificar el resultado utilizando json $_POST["id_producto"] 
        echo json_encode($rspta, true);
      break; 

      case 'select2_proyect_almacen':

        $rspta = $almacen_general->select2_proyect_almacen($_GET['tipo_transf'], $_GET['id_almacen_g']);

        $cont = 1;
        $data = "";

        if ($rspta['status'] == true) {

          foreach ($rspta['data'] as $key => $value) {
            $data .= '<option value="' . $value['id'] . '" >' . $value['nombre'] . '</option>';
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
            $data .= '<option style=" font-size: 12px; "  value="' . $value['idproducto'] . '"  id_ar = "' . $value['idalmacen_resumen'] . '" tipo_mov = "' . $value['tipo'] . '" stok="' . $value['total_stok'] . '" t_egreso="' . $value['total_egreso'] . '" t_ingreso="' . $value['total_ingreso'] . '" unidad_medida="' . $value['unidad_medida'] . '" >' . $value['nombre_producto'] . ' - ' . $value['categoria'] . ' - Stock: ' . $value['total_stok'] . ' - ' . $value['abreviacion'] . '</option>';
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

      case 'select2Productos': 
  
        $rspta = $almacen_general->select2_productos_todos(); $cont = 1; $data = "";
        
        if ($rspta['status'] == true) {  
          foreach ($rspta['data'] as $key => $value) {   
            $data .= '<option value="' . $value['idproducto'] . '" unidad_medida="' . $value['nombre_medida'] . '" >' . $value['nombre_producto'] .' - '. $value['clasificacion'] .'</option>';
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

        //F I N I  I N S E R T  Y  L I S T  D A T A  E N  L O S  A L M A C E N E S  G.

        // ══════════════════════════════════════ secc 3 ════════════════════════════════

        //  I N S E R T  E N T R E  L O S  A L M A C E N  G.   O  P R O Y E C T O.
      case 'guardar_transf_almacen_proyecto':

        $array_data_g             = $_POST["array_data_g"];
        $idalmacen_general_origen = $_POST["idalmacen_general_origen"];
        $tranferencia             = $_POST["tranferencia"];
        $name_alm_proyecto        = $_POST["name_alm_proyecto"];
        $fecha_transf_proy_alm    = $_POST["fecha_transf_proy_alm"];

        $rspta = $almacen_general->guardar_transf_almacen_proyecto(
          $array_data_g,
          $idalmacen_general_origen,
          $tranferencia,
          $name_alm_proyecto,
          $fecha_transf_proy_alm
        );

        echo json_encode($rspta, true);

      break;

      // ══════════════════════════  I N G R E S O  D I R E C T O ══════════════════════════════════════
      case 'guardar_y_prod_id_tup':
        $rspta = $almacen_general->guardar_y_prod_id_tup( $almacen_tup, $fecha_tup, $_POST["idproducto_tup"], $_POST["marca_tup"], $_POST["cantidad_tup"] );
        echo json_encode($rspta, true);           
      break;    

        //listar_productos de almacen para realizar transferencia
      case 'transferencia_a_proy_almacen':
        $rspta = $almacen_general->transferencia_a_proy_almacen($_GET["id_almacen"]);
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
