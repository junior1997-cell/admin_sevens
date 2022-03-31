<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
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
            echo $rspta ? "ok" : "No se pudieron registrar todos los datos del proveedor";
          } else {
            $rspta = $proveedor->editar($idproveedor, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $c_bancaria, $cci, $c_detracciones, $banco, $titular_cuenta);
            echo $rspta ? "ok" : "Trabador no se pudo actualizar";
          }
        break;

        case 'desactivar':
          $rspta = $proveedor->desactivar($idproveedor);
          echo $rspta ? "Usuario Desactivado" : "Usuario no se puede desactivar";
        break;

        case 'activar':
          $rspta = $proveedor->activar($idproveedor);
          echo $rspta ? "Usuario activado" : "Usuario no se puede activar";
        break;

        case 'eliminar':
          $rspta = $proveedor->eliminar($idproveedor);
          echo $rspta ? "ok" : "Proveedor no se puede Elimniar";
        break;

        case 'mostrar':
          $rspta = $proveedor->mostrar($idproveedor);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);
        break;

        case 'listar':
          $rspta = $proveedor->listar();
          //Vamos a declarar un array
          $data = [];  $cont = 1; 

          if ($rspta['status']) {   
          
            foreach ($rspta['data'] as $key => $value) {           
            
              $data[] = [
                "0" => $cont++,
                "1" => $value['estado'] ? '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $value['idproveedor'] . ')"><i class="fas fa-pencil-alt"></i></button>' .
                    ' <button class="btn btn-danger btn-sm" onclick="eliminar(' . $value['idproveedor'] . ')"><i class="fas fa-skull-crossbones"></i></button>'
                  : '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $value['idproveedor'] . ')"><i class="fa fa-pencil-alt"></i></button>' .
                    ' <button class="btn btn-primary btn-sm" onclick="activar(' . $value['idproveedor'] . ')"><i class="fa fa-check"></i></button>',
                "2" => '<div class="user-block">
                  <span class="username ml-0" ><p class="text-primary m-b-02rem">' . $value['razon_social'] .'</p></span> 
                  <span class="description ml-0"><b>' . $value['tipo_documento'] . '</b>: ' . $value['ruc'] . ' </span>
                  <span class="description ml-0"><b>Cel.:</b>' . '<a href="tel:+51' . quitar_guion($value['telefono']) . '" data-toggle="tooltip" data-original-title="Llamar al PROVEEDOR.">' .
                  $value['telefono'] . '</a>' . ' </span>
                </div>',
                "3" => $value['direccion'],
                "4" => '<div class="w-250px"><b>Cta. Banc.:</b>' . $value['cuenta_bancaria'] . '<br> <b>CCI:</b> ' . $value['cci'] . ' <br> <b>Cta. Dtrac.:</b> ' . $value['cuenta_detracciones'] . '</div>',
                "5" => $value['titular_cuenta'],
                "6" => $value['estado'] ? '<span class="text-center badge badge-success">Activado</span>' : '<span class="text-center badge badge-danger">Desactivado</span>',
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

        case 'select2Banco':
          $rspta = $proveedor->select2_banco();

          if ($rspta['status']) {
            foreach ($rspta['data'] as $key => $value) {
              echo '<option value=' . $value['id'] . '>' . $value['nombre'] . (empty($value['alias']) ? "" : ' - ' . $value['alias'] ) . '</option>';
            }
          } else {
            echo json_encode($rspta, true); 
          }
        break;

        case 'formato_banco':
          $rspta = $proveedor->formato_banco($_POST["idbanco"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);
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
    } else {
      require 'noacceso.php';
    }    
  }  

  function quitar_guion($numero) {
    return str_replace("-", "", $numero);
  }

  ob_end_flush();
?>
