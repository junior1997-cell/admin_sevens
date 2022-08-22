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

      require_once "../modelos/Otro_gasto.php";

      $otro_gasto = new Otro_gasto();

            
      date_default_timezone_set('America/Lima');
      $date_now = date("d-m-Y h.i.s A");   
      
      $idproyecto = isset($_POST["idproyecto"]) ? limpiarCadena($_POST["idproyecto"]) : "";
      $idotro_gasto = isset($_POST["idotro_gasto"]) ? limpiarCadena($_POST["idotro_gasto"]) : "";      
      $fecha_g = isset($_POST["fecha_g"]) ? limpiarCadena($_POST["fecha_g"]) : "";
      $forma_pago = isset($_POST["forma_pago"]) ? limpiarCadena($_POST["forma_pago"]) : "";
      $tipo_comprobante = isset($_POST["tipo_comprobante"]) ? limpiarCadena($_POST["tipo_comprobante"]) : "";
      $nro_comprobante = isset($_POST["nro_comprobante"]) ? limpiarCadena($_POST["nro_comprobante"]) : "";
      $subtotal = isset($_POST["subtotal"]) ? limpiarCadena($_POST["subtotal"]) : "";
      $igv = isset($_POST["igv"]) ? limpiarCadena($_POST["igv"]) : "";
      $val_igv          = isset($_POST["val_igv"])? limpiarCadena($_POST["val_igv"]):"";
      $tipo_gravada     = isset($_POST["tipo_gravada"])? limpiarCadena($_POST["tipo_gravada"]):"";  
      
      $precio_parcial = isset($_POST["precio_parcial"]) ? limpiarCadena($_POST["precio_parcial"]) : "";
      $descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";

      $ruc = isset($_POST["num_documento"]) ? limpiarCadena($_POST["num_documento"]) : "";
      $razon_social = isset($_POST["razon_social"]) ? limpiarCadena($_POST["razon_social"]) : "";
      $direccion = isset($_POST["direccion"]) ? limpiarCadena($_POST["direccion"]) : "";
      $glosa = isset($_POST["glosa"]) ? limpiarCadena($_POST["glosa"]) : "";

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
      
            $comprobante = $date_now .' '.rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);
      
            move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/otro_gasto/comprobante/" . $comprobante);
          }
      
          if (empty($idotro_gasto)) {
            //var_dump($idproyecto,$idproveedor);
            $rspta = $otro_gasto->insertar($idproyecto, $fecha_g, $precio_parcial, $subtotal, $igv,$val_igv,$tipo_gravada, $descripcion, $forma_pago, $tipo_comprobante, $nro_comprobante, $comprobante, $ruc, $razon_social, $direccion, $glosa);
            
            echo json_encode($rspta,true);
      
          } else {
            //validamos si existe comprobante para eliminarlo
            if ($flat_ficha1 == true) {
      
              $datos_ficha1 = $otro_gasto->ficha_tec($idotro_gasto);
      
              $ficha1_ant = $datos_ficha1['data']->fetch_object()->comprobante;
      
              if ($ficha1_ant != "") {
      
                unlink("../dist/docs/otro_gasto/comprobante/" . $ficha1_ant);
              }
            }
      
            $rspta = $otro_gasto->editar($idotro_gasto, $idproyecto, $fecha_g, $precio_parcial, $subtotal, $igv,$val_igv,$tipo_gravada, $descripcion, $forma_pago, $tipo_comprobante, $nro_comprobante, $comprobante, $ruc, $razon_social, $direccion,$glosa);
            //var_dump($idotro_gasto,$idproveedor);
            echo json_encode($rspta,true);
          }
        break;
      
        case 'desactivar':
      
          $rspta = $otro_gasto->desactivar($_GET['id_tabla']);
      
          echo json_encode($rspta,true);
      
        break;

        case 'eliminar':
      
          $rspta = $otro_gasto->eliminar($_GET['id_tabla']);
      
          echo json_encode($rspta,true);
      
        break;
      
        case 'mostrar':
      
          $rspta = $otro_gasto->mostrar($idotro_gasto);
          //Codificar el resultado utilizando json
          echo json_encode($rspta,true);
      
        break;
      
        case 'verdatos':
      
          $rspta = $otro_gasto->mostrar($idotro_gasto);
          //Codificar el resultado utilizando json
          echo json_encode($rspta,true);
      
        break;
      
        case 'listar':
          $rspta = $otro_gasto->listar($_GET["idproyecto"],$_GET["fecha_1"], $_GET["fecha_2"], $_GET["id_proveedor"], $_GET["comprobante"]);
          //Vamos a declarar un array
          $data = [];
          $comprobante = '';
          $cont = 1;
          if ($rspta['status']) {
            while ($reg = $rspta['data']->fetch_object()) {

              empty($reg->comprobante)
                ? ($comprobante = '<div><center><a type="btn btn-danger" class=""><i class="fas fa-file-invoice-dollar fa-2x text-gray-50"></i></a></center></div>')
                : ($comprobante = '<div><center><a type="btn btn-danger" class=""  href="#" onclick="modal_comprobante(' . "'" . $reg->comprobante . "'" . ',' . "'" . $reg->tipo_comprobante . "'" . ',' . "'" .(empty($reg->numero_comprobante) ? " - " : $reg->numero_comprobante). "'" . ')"><i class="fas fa-file-invoice-dollar fa-2x"></i></a></center></div>');
              if (strlen($reg->descripcion) >= 20) {
                $descripcion = substr($reg->descripcion, 0, 20) . '...';
              } else {
                $descripcion = $reg->descripcion;
              }
              $tool = '"tooltip"';
              $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>";
              $data[] = [
                "0" => $cont++,
                "1" => '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->idotro_gasto . ')"><i class="fas fa-pencil-alt"></i></button>' .
                    ' <button class="btn btn-danger  btn-sm" onclick="eliminar(' . $reg->idotro_gasto . ',' . "'" . $reg->tipo_comprobante . "'" . ',' . "'" . (empty($reg->numero_comprobante) ? " - " : $reg->numero_comprobante). "'". ')"><i class="fas fa-skull-crossbones"></i> </button>'.
                    ' <button class="btn btn-info btn-sm" onclick="ver_datos(' . $reg->idotro_gasto . ')" data-toggle="tooltip" data-original-title="Ver detalle"><i class="fa fa-eye"></i></button>',
                "2" =>'<div class="user-block">
                  <span class="username" style="margin-left: 0px !important;"> <p class="text-primary" style="margin-bottom: 0.2rem !important";>' .
                  ((empty($reg->razon_social)) ? 'Sin Razón social' : $reg->razon_social ) .
                  '</p> </span>
                    <span class="description" style="margin-left: 0px !important;">N° ' .
                  (empty($reg->ruc) ? "Sin Ruc" : $reg->ruc) .
                  '</span>         
                  </div>',
                "3" => $reg->forma_de_pago,
                "4" =>'<div class="user-block">
                    <span class="username" style="margin-left: 0px !important;"> <p class="text-primary" style="margin-bottom: 0.2rem !important";>' .
                  $reg->tipo_comprobante .
                  '</p> </span>
                    <span class="description" style="margin-left: 0px !important;">N° ' .
                  (empty($reg->numero_comprobante) ? " - " : $reg->numero_comprobante) .
                  '</span>         
                  </div>',
                "5" => $reg->fecha_g,
                "6" =>'S/ '. number_format($reg->subtotal, 2, '.', ','),
                "7" =>'S/ '. number_format($reg->igv, 2, '.', ','),
                "8" =>'S/ '. number_format($reg->costo_parcial, 2, '.', ','),
                "9" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly="">' . $reg->descripcion . '</textarea>',
                "10" => $comprobante. $toltip,
                "11"=>$reg->ruc,
                "12"=>$reg->razon_social,
                "13"=>$reg->direccion,
                "14"=>$reg->tipo_comprobante,
                "15"=>$reg->numero_comprobante,
                "16"=>$reg->tipo_gravada,
                "17"=>$reg->glosa,
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
      
        case 'total':
          // $idproyecto,$fecha_1,$fecha_2,$id_proveedor,$comprobante
          $rspta = $otro_gasto->total($_POST['idproyecto'], $_POST['fecha_1'], $_POST['fecha_2'], $_POST['id_proveedor'], $_POST['comprobante']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta,true);
      
        break;
      
        case 'selecct_provedor_og':

          $rspta = $otro_gasto->selecct_provedor_og($_GET['idproyecto']); $cont = 1; $data = "";

          if ($rspta['status']) {
  
            foreach ($rspta['data'] as $key => $value) {  

                $data .= '<option value=' .$value['ruc']. '>'.( !empty($value['razon_social']) ? $value['razon_social'].' - ' : '') .$value['ruc'].'</option>';
    
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
