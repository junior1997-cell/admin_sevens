<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
		$retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
		echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {

    if ($_SESSION['planilla_seguro'] == 1) {

      require_once "../modelos/Planillas_seguros.php";

      $planillas_seguros = new Planillas_seguros();

      date_default_timezone_set('America/Lima');
      $date_now = date("d-m-Y h.i.s A");      
      
      $idproyecto         = isset($_POST["idproyecto"]) ? limpiarCadena($_POST["idproyecto"]) : "";
      $idproveedor        = isset($_POST["idproveedor"]) ? limpiarCadena($_POST["idproveedor"]) : "";
      $ruc_proveedor      = isset($_POST["ruc_proveedor"]) ? limpiarCadena($_POST["ruc_proveedor"]) : "";
      $idplanilla_seguro  = isset($_POST["idplanilla_seguro"]) ? limpiarCadena($_POST["idplanilla_seguro"]) : "";
      $fecha_p_s          = isset($_POST["fecha_p_s"]) ? limpiarCadena($_POST["fecha_p_s"]) : "";
      $precio_parcial     = isset($_POST["precio_parcial"]) ? limpiarCadena($_POST["precio_parcial"]) : "";
      $glosa              = isset($_POST["glosa"]) ? limpiarCadena($_POST["glosa"]) : "";
      $descripcion        = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
      $forma_pago         = isset($_POST["forma_pago"]) ? limpiarCadena($_POST["forma_pago"]) : "";
      $tipo_comprobante   = isset($_POST["tipo_comprobante"]) ? limpiarCadena($_POST["tipo_comprobante"]) : "";
      $nro_comprobante    = isset($_POST["nro_comprobante"]) ? limpiarCadena($_POST["nro_comprobante"]) : "";
      $subtotal           = isset($_POST["subtotal"]) ? limpiarCadena($_POST["subtotal"]) : "";
      $igv                = isset($_POST["igv"]) ? limpiarCadena($_POST["igv"]) : "";
      $val_igv            = isset($_POST["val_igv"]) ? limpiarCadena($_POST["val_igv"]) : "";
      $tipo_gravada       = isset($_POST["tipo_gravada"]) ? limpiarCadena($_POST["tipo_gravada"]) : "";

      $foto2 = isset($_POST["doc1"]) ? limpiarCadena($_POST["doc1"]) : "";
      //$subtotal,$igv
      switch ($_GET["op"]) {
        case 'guardaryeditar':

          // Comprobante
          if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {
            $comprobante = $_POST["doc_old_1"];
            $flat_ficha1 = false;
          } else {
            $ext1 = explode(".", $_FILES["doc1"]["name"]);
            $flat_ficha1 = true;
            $comprobante = $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);
            move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/planilla_seguro/comprobante/" . $comprobante);
          }

          if (empty($idplanilla_seguro)) {
            //var_dump($idproyecto,$idproveedor);
            $rspta = $planillas_seguros->insertar($idproyecto, $idproveedor,$ruc_proveedor, $fecha_p_s, $precio_parcial, $subtotal, $igv, $val_igv, $tipo_gravada, $glosa, $descripcion, $forma_pago, $tipo_comprobante, $nro_comprobante, $comprobante);
            echo json_encode($rspta, true);
          } else {
            //validamos si existe comprobante para eliminarlo
            if ($flat_ficha1 == true) {
              $datos_ficha1 = $planillas_seguros->ficha_tec($idplanilla_seguro);
              $ficha1_ant = $datos_ficha1['data']->fetch_object()->comprobante;
              if ($ficha1_ant != "") {  unlink("../dist/docs/planilla_seguro/comprobante/" . $ficha1_ant); }
            }

            $rspta = $planillas_seguros->editar($idplanilla_seguro, $idproyecto,$idproveedor, $fecha_p_s, $precio_parcial, $subtotal, $igv, $val_igv, $tipo_gravada,$glosa, $descripcion, $forma_pago, $tipo_comprobante, $nro_comprobante, $comprobante);
            //var_dump($idplanilla_seguro,$idproveedor);
            echo json_encode($rspta, true);
          }

        break;

        case 'desactivar':
          $rspta = $planillas_seguros->desactivar($_GET["id_tabla"]);
          echo json_encode($rspta, true);
        break;

        case 'eliminar':
          $rspta = $planillas_seguros->eliminar($_GET["id_tabla"]);
          echo json_encode($rspta, true);
        break;

        case 'activar':
          $rspta = $planillas_seguros->activar($_GET["id_tabla"]);
          echo json_encode($rspta, true);
        break;

        case 'mostrar':
          $rspta = $planillas_seguros->mostrar($idplanilla_seguro);
            //Codificar el resultado utilizando json
            echo json_encode($rspta, true);
        break;

        case 'ver_detalle':
          $rspta = $planillas_seguros->ver_detalle($idplanilla_seguro);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;

        case 'total':
          $rspta = $planillas_seguros->total($_POST["idproyecto"],$_POST['fecha_1'],$_POST['fecha_2'],$_POST['id_proveedor'],$_POST['comprobante']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;

        case 'tbla_principal':

          $rspta = $planillas_seguros->listar($_GET["idproyecto"],$_GET["fecha_1"], $_GET["fecha_2"], $_GET["id_proveedor"], $_GET["comprobante"]);
          //Vamos a declarar un array
          $data = [];     $cont = 1;

          $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

          if ($rspta['status'] == true) {
            while ($reg = $rspta['data']->fetch_object()) {                

              $comprobante = (empty($reg->comprobante) ? '<div><center><a type="btn btn-danger" class="" data-toggle="tooltip" data-original-title="Vacio"><i class="fas fa-file-invoice-dollar fa-2x text-gray-50"></i></a></center></div>' : '<div><center><a type="btn btn-danger" class=""  href="#" onclick="modal_comprobante(' . "'" . $reg->comprobante . "'" . ')" data-toggle="tooltip" data-original-title="Ver doc."><i class="fas fa-file-invoice-dollar fa-2x"></i></a></center></div>');
              
              $data[] = [
                "0" => $cont++,
                "1" => ($reg->estado ? '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->idplanilla_seguro .')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' .
                    ' <button class="btn btn-danger btn-sm" onclick="eliminar(' . $reg->idplanilla_seguro .', \''.encodeCadenaHtml($reg->tipo_comprobante . (empty($reg->numero_comprobante) ? "" : " - ". $reg->numero_comprobante)).'\')" data-toggle="tooltip" data-original-title="Papelera o Eliminar"><i class="fas fa-skull-crossbones"></i></button>'
                  : '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->idplanilla_seguro .')" data-toggle="tooltip" data-original-title="Editar"><i class="fa fa-pencil-alt"></i></button>' .
                    ' <button class="btn btn-primary btn-sm" onclick="activar(' . $reg->idplanilla_seguro . ')"data-toggle="tooltip" data-original-title="Activar"><i class="fa fa-check"></i></button>').
                    ' <button class="btn btn-info btn-sm" onclick="ver_detalle(' . $reg->idplanilla_seguro . ')" data-toggle="tooltip" data-original-title="Ver detalle"><i class="fa fa-eye"></i></button>',
                "2" => $reg->fecha_p_s,
                "3" => '<div class="user-block">'.
                  '<span class="username ml-0" > <p class="text-primary m-b-02rem" >' .$reg->razon_social . '</p> </span>'.
                  '<span class="description ml-0">' . $reg->tipo_documento. ": " .$reg->ruc . '</span>'.      
                '</div>',
                "4" => $reg->forma_de_pago,
                "5" => '<div class="user-block">'.
                  '<span class="username ml-0" > <p class="text-primary m-b-02rem" >' .$reg->tipo_comprobante . '</p> </span>'.
                  '<span class="description ml-0">N° ' . (empty($reg->numero_comprobante) ? " - " : $reg->numero_comprobante) . '</span>'.      
                '</div>',
                "6" => '<div class="formato-numero-conta"> <span>S/</span>' . number_format($reg->subtotal, 2, '.', ',').'</div>',
                "7" => '<div class="formato-numero-conta"> <span>S/</span>' . number_format($reg->igv, 2, '.', ',').'</div>',
                "8" => '<div class="formato-numero-conta"> <span>S/</span>' . number_format($reg->costo_parcial, 2, '.', ',').'</div>',
                "9" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly="">' . $reg->descripcion . '</textarea>',
                "10" => $comprobante  . $toltip,
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
