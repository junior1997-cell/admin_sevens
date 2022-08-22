<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {

    if ($_SESSION['viatico'] == 1) {
      
      require_once "../modelos/Transporte.php";

      $transporte = new Transporte();
      
      date_default_timezone_set('America/Lima');
      $date_now = date("d-m-Y h.i.s A");   

      $idproyecto = isset($_POST["idproyecto"]) ? limpiarCadena($_POST["idproyecto"]) : "";
      $idproveedor = isset($_POST["idproveedor"]) ? limpiarCadena($_POST["idproveedor"]) : "";
      $idtransporte = isset($_POST["idtransporte"]) ? limpiarCadena($_POST["idtransporte"]) : "";
      $fecha_viaje = isset($_POST["fecha_viaje"]) ? limpiarCadena($_POST["fecha_viaje"]) : "";
      $tipo_viajero = isset($_POST["tipo_viajero"]) ? limpiarCadena($_POST["tipo_viajero"]) : "";
      $tipo_ruta = isset($_POST["tipo_ruta"]) ? limpiarCadena($_POST["tipo_ruta"]) : "";
      $cantidad = isset($_POST["cantidad"]) ? limpiarCadena($_POST["cantidad"]) : "";
      $precio_unitario = isset($_POST["precio_unitario"]) ? limpiarCadena($_POST["precio_unitario"]) : "";
      $precio_parcial = isset($_POST["precio_parcial"]) ? limpiarCadena($_POST["precio_parcial"]) : "";
      $ruta = isset($_POST["ruta"]) ? limpiarCadena($_POST["ruta"]) : "";
      $descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
      $glosa = isset($_POST["glosa"]) ? limpiarCadena($_POST["glosa"]) : "";

      $forma_pago = isset($_POST["forma_pago"]) ? limpiarCadena($_POST["forma_pago"]) : "";
      $tipo_comprobante = isset($_POST["tipo_comprobante"]) ? limpiarCadena($_POST["tipo_comprobante"]) : "";
      $nro_comprobante = isset($_POST["nro_comprobante"]) ? limpiarCadena($_POST["nro_comprobante"]) : "";
      $subtotal = isset($_POST["subtotal"]) ? limpiarCadena($_POST["subtotal"]) : "";
      $igv = isset($_POST["igv"]) ? limpiarCadena($_POST["igv"]) : "";
      $val_igv = isset($_POST["val_igv"]) ? limpiarCadena($_POST["val_igv"]) : "";
      $tipo_gravada = isset($_POST["tipo_gravada"]) ? limpiarCadena($_POST["tipo_gravada"]) : "";
      $ruc_proveedor = isset($_POST["ruc_proveedor"]) ? limpiarCadena($_POST["ruc_proveedor"]) : "";
      
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

            $comprobante = $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

            move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/transporte/comprobante/" . $comprobante);
          }

          if (empty($idtransporte)) {
            //var_dump($idproyecto,$idproveedor);
            $rspta = $transporte->insertar(
              $idproyecto,
              $idproveedor,
              $fecha_viaje,
              $tipo_viajero,
              $tipo_ruta,
              $cantidad,
              $precio_unitario,
              $precio_parcial,
              $ruta,
              $descripcion,
              $forma_pago,
              $tipo_comprobante,
              $nro_comprobante,
              $subtotal,
              $igv,
              $val_igv,
              $tipo_gravada,
              $comprobante,
              $glosa,
              $ruc_proveedor
            );
            echo json_encode($rspta, true);
          } else {
            //validamos si existe comprobante para eliminarlo
            if ($flat_ficha1 == true) {
              $datos_ficha1 = $transporte->ficha_tec($idtransporte);

              $ficha1_ant = $datos_ficha1['data']->fetch_object()->comprobante;

              if ($ficha1_ant != "") {
                unlink("../dist/docs/transporte/comprobante/" . $ficha1_ant);
              }
            }

            $rspta = $transporte->editar(
              $idtransporte,
              $idproyecto,
              $idproveedor,
              $fecha_viaje,
              $tipo_viajero,
              $tipo_ruta,
              $cantidad,
              $precio_unitario,
              $precio_parcial,
              $ruta,
              $descripcion,
              $forma_pago,
              $tipo_comprobante,
              $nro_comprobante,
              $subtotal,
              $igv,
              $val_igv,
              $tipo_gravada,
              $comprobante,
              $glosa,
              $ruc_proveedor
            );
            //var_dump($idtransporte,$idproveedor);
            echo json_encode($rspta,true);
          }

        break;

        case 'desactivar':
          $rspta = $transporte->desactivar($_GET['id_tabla']);
          echo json_encode($rspta,true);
        break;

        case 'eliminar':
          $rspta = $transporte->eliminar($_GET['id_tabla']);
          echo json_encode($rspta,true);
        break;

        case 'mostrar':
          $rspta = $transporte->mostrar($idtransporte);
          echo json_encode($rspta,true);
        break;

        case 'verdatos':
          $rspta = $transporte->verdatos($idtransporte);
          echo json_encode($rspta,true);
        break;

        case 'total':
          
          $rspta = $transporte->total($_POST['idproyecto'], $_POST['fecha_1'], $_POST['fecha_2'], $_POST['id_proveedor'], $_POST['comprobante']);
          echo json_encode($rspta,true);
        break;

        case 'listar':
         // $idproyecto = $_GET["idproyecto"];
          $rspta = $transporte->listar($_GET["idproyecto"],$_GET["fecha_1"], $_GET["fecha_2"], $_GET["id_proveedor"], $_GET["comprobante"]);
          //Vamos a declarar un array
          $data = [];
          $comprobante = '';
          $cont = 1;
          if ($rspta['status']) {

            while ($reg = $rspta['data']->fetch_object()) {
              // empty($reg->comprobante)?$comprobante='<div><center><a type="btn btn-danger" class=""><i class="far fa-times-circle fa-2x"></i></a></center></div>':$comprobante='<center><a target="_blank" href="../dist/docs/transporte/comprobante/'.$reg->comprobante.'"><i class="far fa-file-pdf fa-2x" style="color:#ff0000c4"></i></a></center>';

                empty($reg->comprobante)
                  ? ($comprobante = '<div><center><a type="btn btn-danger" class=""><i class="fas fa-file-invoice-dollar fa-2x text-gray-50"></i></a></center></div>')
                  : ($comprobante = '<div><center><a type="btn btn-danger" class=""  href="#" onclick="modal_comprobante(' . "'" . $reg->comprobante . "'" . ',' . "'" . $reg->tipo_comprobante . "'" . ',' . "'" . $reg->numero_comprobante . "'" . ')"><i class="fas fa-file-invoice-dollar fa-2x"></i></a></center></div>');
                
                if (strlen($reg->descripcion) >= 20) { $descripcion = substr($reg->descripcion, 0, 20) . '...'; } else { $descripcion = $reg->descripcion; }

               $tool = '"tooltip"';

               $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>";

                $data[] = [
                "0" => $cont++,
                "1" => '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->idtransporte . ')"><i class="fas fa-pencil-alt"></i></button>' .
                      ' <button class="btn btn-danger btn-sm" onclick="eliminar(' . $reg->idtransporte . ',' . "'" . $reg->tipo_comprobante . "'" . ',' . "'" . $reg->numero_comprobante . "'" . ')"><i class="fas fa-skull-crossbones"></i> </button>' .
                      ' <button class="btn btn-info btn-sm" onclick="ver_datos(' . $reg->idtransporte . ')"><i class="far fa-eye"></i></button>',
                "2" => $reg->forma_de_pago,
                "3" => '<div class="user-block">
                            <span class="username" style="margin-left: 0px !important;"> <p class="text-primary" style="margin-bottom: 0.2rem !important";>' . $reg->tipo_comprobante . '</p> </span>
                            <span class="description" style="margin-left: 0px !important;">N° ' . (empty($reg->numero_comprobante) ? " - " : $reg->numero_comprobante) . '</span>         
                        </div>',
                "4" => date("d/m/Y", strtotime($reg->fecha_viaje)),
                "5" => 'S/ ' . number_format($reg->subtotal, 2, '.', ','),
                "6" => 'S/ ' . number_format($reg->igv, 2, '.', ','),
                "7" => 'S/ ' . number_format($reg->precio_parcial, 2, '.', ','),
                "8" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly="">' . $reg->descripcion . '</textarea>',
                "9" => $comprobante.''. $toltip,
                "10" => $reg->tipo_viajero,
                "11" => $reg->tipo_ruta,
                "12" => $reg->ruta,
                "13" => $reg->cantidad,
                "14" => $reg->precio_unitario,
                "15" => $reg->val_igv,
                "16" => $reg->tipo_gravada,
                "17" => $reg->glosa,
                "18" => $reg->razon_social,
                "19" => $reg->tipo_comprobante,
                "20" => $reg->ruc,
                "21" => $reg->direccion,
                "22" => $reg->numero_comprobante,
              ];
            }

            $results = [
              "sEcho" => 1, //Información para el datatables
              "iTotalRecords" => count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
              "data" => $data,
            ];

          } else {

            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          }

          echo json_encode($results);

        break;

        case 'select2Proveedor':

          $rspta = $transporte->select2_proveedor();
          while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->idproveedor . '>' . $reg->razon_social . ' - ' . $reg->ruc . '</option>';
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
