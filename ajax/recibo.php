<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {

    if ($_SESSION['otro_gasto'] == 1) {

      require_once "../modelos/Recibo.php";

      $recibo_rh = new Recibo();
            
      date_default_timezone_set('America/Lima');  $date_now = date("d-m-Y h.i.s A");   
      $scheme_host =  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/admin_sevens/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');
      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';
      
      $idproyecto            = isset($_POST["idproyecto"]) ? limpiarCadena($_POST["idproyecto"]) : "";     
      $idrecibo_x_honorario  = isset($_POST["idrecibo_x_honorario"]) ? limpiarCadena($_POST["idrecibo_x_honorario"]) : "";
      $tipo_documento        = isset($_POST["tipo_documento"]) ? limpiarCadena($_POST["tipo_documento"]) : "";
      $num_documento         = isset($_POST["num_documento"]) ? limpiarCadena($_POST["num_documento"]) : "";
      $nombre                = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
      $fecha_pago            = isset($_POST["fecha_pago"]) ? limpiarCadena($_POST["fecha_pago"]) : "";
      $monto                 = isset($_POST["monto"]) ? limpiarCadena($_POST["monto"]) : "";
      $costo                 = isset($_POST["costo"])? limpiarCadena($_POST["costo"]):"";
      $servicio              = isset($_POST["servicio"])? limpiarCadena($_POST["servicio"]):"";  

      $doc1                  = isset($_POST["doc1"]) ? limpiarCadena($_POST["doc1"]) : "";
      $doc2                  = isset($_POST["doc2"]) ? limpiarCadena($_POST["doc2"]) : "";
      //idproyecto,$idrecibo_x_honorario,$tipo_documento,$num_documento,$nombre,$fecha_pago,$monto,$costo,$servicio,$recibo,$voucher
      switch ($_GET["op"]) {

        case 'guardaryeditar':
          // Comprobante
          if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {
      
            $recibo = $_POST["doc_old_1"];
      
            $flat_ficha1 = false;
      
          } else {
      
            $ext1 = explode(".", $_FILES["doc1"]["name"]);
      
            $flat_ficha1 = true;
      
            $recibo = $date_now .' '.random_int(0, 20) . round(microtime(true)) . random_int(21, 41) . '.' . end($ext1);
      
            move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/compra_rh/recibo/" . $recibo);

          }

          // voucher
          if (!file_exists($_FILES['doc2']['tmp_name']) || !is_uploaded_file($_FILES['doc2']['tmp_name'])) {

            $voucher = $_POST["doc_old_2"];
      
            $flat_ficha2 = false;
      
          } else {
      
            $ext1 = explode(".", $_FILES["doc2"]["name"]);
      
            $flat_ficha2 = true;
      
            $voucher = $date_now .' '.random_int(0, 20) . round(microtime(true)) . random_int(21, 41) . '.' . end($ext1);
      
            move_uploaded_file($_FILES["doc2"]["tmp_name"], "../dist/docs/compra_rh/voucher/" . $voucher);

          }
      
          if (empty($idrecibo_x_honorario)) {
            //var_dump($idproyecto,$idproveedor);
            $rspta = $recibo_rh->insertar($idproyecto,$tipo_documento,$num_documento,$nombre,$fecha_pago,$monto,$costo,$servicio,$recibo,$voucher);
            
            echo json_encode($rspta,true);
      
          } else {
            //validamos si existe recibo para eliminarlo
            if ($flat_ficha1 == true) {
      
              $datos_ficha1 = $recibo_rh->ficha_tec($idotro_gasto);
      
              $ficha1_ant = $datos_ficha1['data']->fetch_object()->recibo;
      
              if ($ficha1_ant != "") { unlink("../dist/docs/otro_gasto/recibo/" . $ficha1_ant); }
            }
            
            //validamos si existe voucher para eliminarlo
            if ($flat_ficha2 == true) {

              $datos_ficha2 = $recibo_rh->ficha_tec($idotro_gasto);
      
              $ficha2_ant = $datos_ficha2['data']->fetch_object()->voucher;
      
              if ($ficha2_ant != "") { unlink("../dist/docs/otro_gasto/voucher/" . $ficha2_ant);}
            }
      
            $rspta = $recibo_rh->editar($idproyecto,$idrecibo_x_honorario,$tipo_documento,$num_documento,$nombre,$fecha_pago,$monto,$costo,$servicio,$recibo,$voucher);
            //var_dump($idotro_gasto,$idproveedor);
            echo json_encode($rspta,true);
          }
        break;
      
        case 'desactivar':
      
          $rspta = $recibo_rh->desactivar($_GET['id_tabla']);
      
          echo json_encode($rspta,true);
      
        break;

        case 'eliminar':
      
          $rspta = $recibo_rh->eliminar($_GET['id_tabla']);
      
          echo json_encode($rspta,true);
      
        break;
      
        case 'mostrar':
      
          $rspta = $recibo_rh->mostrar($idrecibo_x_honorario);
          //Codificar el resultado utilizando json
          echo json_encode($rspta,true);
      
        break;
      
        case 'verdatos':
      
          $rspta = $recibo_rh->mostrar($idotro_gasto);
          //Codificar el resultado utilizando json
          echo json_encode($rspta,true);
      
        break;
      
        case 'listar':
          $rspta = $recibo_rh->listar($_GET["idproyecto"]);
          //Vamos a declarar un array
          $data = [];
          
          $cont = 1;
          $carp1="recibo";
          $carp2="voucher";
          if ($rspta['status'] == true) {

            while ($reg = $rspta['data']->fetch_object()) {

              $recibo = empty($reg->recibo)
                ? ( '<div><center><a type="btn btn-danger" class=""><i class="fas fa-file-invoice-dollar fa-2x text-gray-50"></i></a></center></div>')
                : ( '<div><center><a type="btn btn-danger" class=""  href="#" onclick="modal_comprobante(' . "'" . $reg->recibo . "'" . ',' . "'" . $reg->nombres . "'" . ',' . "'" . $carp1 . "'" . ')"><i class="fas fa-file-invoice-dollar fa-2x"></i></a></center></div>');
                
              $voucher = empty($reg->voucher)
                ? ( '<div><center><a type="btn btn-danger" class=""><i class="fas fa-file-invoice-dollar fa-2x text-gray-50"></i></a></center></div>')
                : ( '<div><center><a type="btn btn-danger" class=""  href="#" onclick="modal_comprobante(' . "'" . $reg->voucher . "'" . ',' . "'" . $reg->nombres . "'" . ',' . "'" . $carp2 . "'" . ')"><i class="fas fa-file-invoice-dollar fa-2x"></i></a></center></div>');
              
              $data[] = [
                "0" => $cont++,
                "1" => '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->idrecibo_x_honorario . ')"><i class="fas fa-pencil-alt"></i></button>' .
                    ' <button class="btn btn-danger  btn-sm" onclick="eliminar(' . $reg->idrecibo_x_honorario . ',' . "'" . $reg->nombres . "'" . ')"><i class="fas fa-skull-crossbones"></i> </button>',
                "2" =>'<div class="user-block">
                  <span class="username ml-0" > <p class="text-primary m-b-02rem" >' . $reg->nombres . '</p> </span>
                  <span class="description ml-0" >D.N.I : ' . $reg->numero_documento . '</span>         
                </div>',
                "3" => $reg->fecha_pago,
                "4" => $reg->servicio,
                "5" => $reg->monto_total,
                "6" => $recibo,
                "7" => $voucher,
                "8" => $reg->costo_operacion,
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
