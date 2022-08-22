<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {
    //Validamos el acceso solo al material logueado y autorizado.
		if ($_SESSION['viatico']==1){
      require_once "../modelos/Hospedaje.php";

      $hospedaje = new Hospedaje();

      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

      $idproyecto = isset($_POST["idproyecto"]) ? limpiarCadena($_POST["idproyecto"]) : "";
      $idhospedaje = isset($_POST["idhospedaje"]) ? limpiarCadena($_POST["idhospedaje"]) : "";
      $fecha_inicio = isset($_POST["fecha_inicio"]) ? limpiarCadena($_POST["fecha_inicio"]) : "";
      $fecha_fin = isset($_POST["fecha_fin"]) ? limpiarCadena($_POST["fecha_fin"]) : "";
      $cantidad = isset($_POST["cantidad"]) ? limpiarCadena($_POST["cantidad"]) : "";
      $unidad = isset($_POST["unidad"]) ? limpiarCadena($_POST["unidad"]) : "";
      $precio_unitario = isset($_POST["precio_unitario"]) ? limpiarCadena($_POST["precio_unitario"]) : "";
      $precio_parcial = isset($_POST["precio_parcial"]) ? limpiarCadena($_POST["precio_parcial"]) : "";
      $descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";

      $forma_pago = isset($_POST["forma_pago"]) ? limpiarCadena($_POST["forma_pago"]) : "";
      $tipo_comprobante = isset($_POST["tipo_comprobante"]) ? limpiarCadena($_POST["tipo_comprobante"]) : "";
      $fecha_comprobante = isset($_POST["fecha_comprobante"]) ? limpiarCadena($_POST["fecha_comprobante"]) : "";
      $nro_comprobante = isset($_POST["nro_comprobante"]) ? limpiarCadena($_POST["nro_comprobante"]) : "";
      $subtotal = isset($_POST["subtotal"]) ? limpiarCadena($_POST["subtotal"]) : "";
      $igv = isset($_POST["igv"]) ? limpiarCadena($_POST["igv"]) : "";
      $val_igv = isset($_POST["val_igv"]) ? limpiarCadena($_POST["val_igv"]) : "";
      $tipo_gravada = isset($_POST["tipo_gravada"]) ? limpiarCadena($_POST["tipo_gravada"]) : "";

      $ruc = isset($_POST["num_documento"]) ? limpiarCadena($_POST["num_documento"]) : "";
      $razon_social = isset($_POST["razon_social"]) ? limpiarCadena($_POST["razon_social"]) : "";
      $direccion = isset($_POST["direccion"]) ? limpiarCadena($_POST["direccion"]) : "";

      $foto2 = isset($_POST["doc1"]) ? limpiarCadena($_POST["doc1"]) : "";

      switch ($_GET["op"]) {
        case 'guardaryeditar':
          // Comprobante
          if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {
            $comprobante = $_POST["doc_old_1"];
            $flat_ficha1 = false;
          } else {
            $ext1 = explode(".", $_FILES["doc1"]["name"]);
            $flat_ficha1 = true;

            $comprobante = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

            move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/hospedaje/comprobante/" . $comprobante);
          }

          if (empty($idhospedaje)) {
            //var_dump($idproyecto,$idproveedor);
            $rspta = $hospedaje->insertar(  $idproyecto,  $fecha_inicio,  $fecha_fin,  $cantidad,  $unidad,  $precio_unitario,  $precio_parcial,  $descripcion,  $forma_pago,  $tipo_comprobante,  $fecha_comprobante,  $nro_comprobante,  $subtotal,  $igv,  $val_igv,  $tipo_gravada,  $comprobante,  $ruc,  $razon_social,  $direccion );
            echo json_encode($rspta, true);
          } else {
            //validamos si existe comprobante para eliminarlo
            if ($flat_ficha1 == true) {
              $datos_ficha1 = $hospedaje->comprobante_hospedaje($idhospedaje);
              $ficha1_ant = $datos_ficha1['data']->fetch_object()->comprobante;
              if ($ficha1_ant != "") { unlink("../dist/docs/hospedaje/comprobante/" . $ficha1_ant);  }
            }

            $rspta = $hospedaje->editar( $idhospedaje, $idproyecto, $fecha_inicio, $fecha_fin, $cantidad, $unidad, $precio_unitario, $precio_parcial, $descripcion, $forma_pago, $tipo_comprobante, $fecha_comprobante, $nro_comprobante, $subtotal, $igv, $val_igv, $tipo_gravada, $comprobante, $ruc, $razon_social, $direccion  );
            //var_dump($idhospedaje,$idproveedor);
            echo json_encode($rspta, true);
          }
        break;

        case 'desactivar':
          $rspta = $hospedaje->desactivar($_GET["id_tabla"]);
          echo json_encode($rspta, true);
        break;

        case 'activar':
          $rspta = $hospedaje->activar($idhospedaje);
          echo json_encode($rspta, true);
        break;

        case 'eliminar':
          $rspta = $hospedaje->eliminar($_GET["id_tabla"]);
          echo json_encode($rspta, true);
        break;

        case 'mostrar':
          $rspta = $hospedaje->mostrar($idhospedaje);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;

        case 'verdatos':
          $rspta = $hospedaje->mostrar($idhospedaje);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;

        case 'total':
          $rspta = $hospedaje->total($_POST["idproyecto"], $_POST["fecha_1"], $_POST["fecha_2"], $_POST["id_proveedor"], $_POST["comprobante"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;

        case 'tabla_principal':
          $rspta = $hospedaje->tabla_principal($_GET["idproyecto"], $_GET["fecha_1"], $_GET["fecha_2"], $_GET["id_proveedor"], $_GET["comprobante"]);
          //Vamos a declarar un array
          $data = [];  $cont = 1;

          if ($rspta['status'] == true) {
            while ($reg = $rspta['data']->fetch_object()) {
              $comprobante = empty($reg->comprobante)
                ? ('<div><center><a type="btn btn-danger" class=""><i class="fas fa-file-invoice-dollar fa-2x text-gray-50"></i></a></center></div>')
                : ('<div><center><a type="btn btn-danger" class=""  href="#" onclick="modal_comprobante(\'' . $reg->comprobante . '\', \'' .encodeCadenaHtml($reg->tipo_comprobante .' - '.(empty($reg->numero_comprobante) ? " - " : $reg->numero_comprobante)). '\')"><i class="fas fa-file-invoice-dollar fa-2x"></i></a></center></div>');
              
              $data[] = [
                "0" => $cont++,
                "1" => $reg->estado
                  ? '<button class="btn btn-sm btn-warning" onclick="mostrar(' .  $reg->idhospedaje . ')"><i class="fas fa-pencil-alt"></i></button>' .
                    ' <button class="btn btn-sm btn-danger" onclick="eliminar(' . $reg->idhospedaje .', \''.encodeCadenaHtml($reg->tipo_comprobante .' - '.(empty($reg->numero_comprobante) ? " - " : $reg->numero_comprobante)).'\')"><i class="fas fa-skull-crossbones"></i> </button>' .
                    ' <button class="btn btn-sm btn-info" onclick="ver_datos(' . $reg->idhospedaje . ')"><i class="far fa-eye"></i></button>'
                  : '<button class="btn btn-sm btn-warning" onclick="mostrar(' . $reg->idhospedaje . ')"><i class="fa fa-pencil-alt"></i></button>' .
                    ' <button class="btn btn-sm btn-primary" onclick="activar(' . $reg->idhospedaje . ')"><i class="fa fa-check"></i></button>' .
                    ' <button class="btn btn-sm btn-info" onclick="ver_datos(' . $reg->idhospedaje . ')"><i class="far fa-eye"></i></button>',
                "2" => $reg->fecha_comprobante,
                "3" =>'<div class="w-200px recorte-text" data-toggle="tooltip" data-original-title="'.encodeCadenaHtml($reg->razon_social) .' - '.$reg->ruc .'">'. ( empty($reg->razon_social) ? 'Sin Razón social' : $reg->razon_social ) .'</div>' ,
                "4" => $reg->forma_de_pago,
                "5" => '<span ><b class="text-primary">'.$reg->tipo_comprobante.'</b>'. (empty($reg->numero_comprobante)?"":' - '.$reg->numero_comprobante ).'</span>',                                
                "6" => $reg->precio_parcial,
                "7" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly="">' . $reg->descripcion . '</textarea>',
                "8" => $comprobante . $toltip,

                "9" => $reg->ruc, 
                "10" => $reg->tipo_comprobante,
                "11" => $reg->numero_comprobante,
                "12" => number_format($reg->subtotal, 2, '.', ','),
                "13" => number_format($reg->igv, 2, '.', ','),
                "14" => $reg->val_igv,   
                "15" => $reg->unidad,        
                "16" => date("d/m/Y", strtotime($reg->fecha_inicio)),
                "17" => date("d/m/Y", strtotime($reg->fecha_fin)),
                "18" => $reg->cantidad,
                "19" => number_format($reg->precio_unitario, 2, '.', ','),                
                "20" => $reg->glosa,
                "21" => $reg->tipo_gravada,
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

        // ════════════════════════════════════════ S E L E C T 2   -   P R O V E E D O R ════════════════════════════════════════
        case 'select2Proveedor':

          $rspta = $hospedaje->select2Proveedor($_GET['idproyecto']);

          $data = "";

          if ($rspta['status'] == true) {

            foreach ($rspta['data'] as $key => $reg) { 
              $data .= '<option value="' . $reg['ruc'] . '" >' . (empty($reg['razon_social']) ? '': $reg['razon_social'] .' - ') . $reg['ruc']. '</option>';
            }
            $retorno = array(
              'status' => true, 
              'message' => 'Salió todo ok', 
              'data' => '<option value="vacio">Sin proveedor</option>'.$data, 
            );
    
            echo json_encode($retorno, true);

          } else {

            echo json_encode($rspta, true); 
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
