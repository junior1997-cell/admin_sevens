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

      require_once "../modelos/Epp.php";

      $epp = new Epp();
            
      date_default_timezone_set('America/Lima');  $date_now = date("d-m-Y h.i.s A");   
      $scheme_host =  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/admin_sevens/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');
      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';
      
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
      
            $comprobante = $date_now .' '.random_int(0, 20) . round(microtime(true)) . random_int(21, 41) . '.' . end($ext1);
      
            move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/otro_gasto/comprobante/" . $comprobante);
          }
      
          if (empty($idotro_gasto)) {
            //var_dump($idproyecto,$idproveedor);
            $rspta = $epp->insertar($idproyecto, $fecha_g, $precio_parcial, $subtotal, $igv,$val_igv,$tipo_gravada, $descripcion, $forma_pago, $tipo_comprobante, $nro_comprobante, $comprobante, $ruc, $razon_social, $direccion, $glosa);
            
            echo json_encode($rspta,true);
      
          } else {
            //validamos si existe comprobante para eliminarlo
            if ($flat_ficha1 == true) {
      
              $datos_ficha1 = $epp->ficha_tec($idotro_gasto);
      
              $ficha1_ant = $datos_ficha1['data']->fetch_object()->comprobante;
      
              if ($ficha1_ant != "") {
      
                unlink("../dist/docs/otro_gasto/comprobante/" . $ficha1_ant);
              }
            }
      
            $rspta = $epp->editar($idotro_gasto, $idproyecto, $fecha_g, $precio_parcial, $subtotal, $igv,$val_igv,$tipo_gravada, $descripcion, $forma_pago, $tipo_comprobante, $nro_comprobante, $comprobante, $ruc, $razon_social, $direccion,$glosa);
            //var_dump($idotro_gasto,$idproveedor);
            echo json_encode($rspta,true);
          }
        break;
      
        case 'desactivar':
      
          $rspta = $epp->desactivar($_GET['id_tabla']);
      
          echo json_encode($rspta,true);
      
        break;

        case 'eliminar':
      
          $rspta = $epp->eliminar($_GET['id_tabla']);
      
          echo json_encode($rspta,true);
      
        break;
      
        case 'mostrar':
      
          $rspta = $epp->mostrar($idotro_gasto);
          //Codificar el resultado utilizando json
          echo json_encode($rspta,true);
      
        break;
      
        case 'verdatos':
      
          $rspta = $epp->mostrar($idotro_gasto);
          //Codificar el resultado utilizando json
          echo json_encode($rspta,true);
      
        break;
      
        case 'listar_trabajdor':
          $rspta = $epp->trabajador_proyecto($_GET["idproyecto"]);
          //Vamos a declarar un array
          $data = [];
          
          $cont = 1;
          if ($rspta['status'] == true) {
            while ($reg = $rspta['data']->fetch_object()) {

              $data[] = [
                "0" => $cont++,
                "1" => $reg->nombres,
                "2" => $reg->talla_ropa,
                "3" => $reg->talla_zapato,
                "4" => $reg->idtrabajador_por_proyecto,
                
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
          $rspta = $epp->total($_POST['idproyecto'], $_POST['fecha_1'], $_POST['fecha_2'], $_POST['id_proveedor'], $_POST['comprobante'], $_POST['glosa'] );
          //Codificar el resultado utilizando json
          echo json_encode($rspta,true);
      
        break;
      
        // case 'selecct_provedor_og':

        //   $rspta = $epp->selecct_provedor_og($_GET['idproyecto']); $cont = 1; $data = "";

        //   if ($rspta['status'] == true) {
  
        //     foreach ($rspta['data'] as $key => $value) {  

        //         $data .= '<option value=' .$value['ruc']. '>'.( !empty($value['razon_social']) ? $value['razon_social'].' - ' : '') .$value['ruc'].'</option>';
    
        //     }
  
        //     $retorno = array(
        //       'status' => true, 
        //       'message' => 'Salió todo ok', 
        //       'data' => '<option value="vacio">Sin proveedor</option>'.$data, 
        //     );
    
        //     echo json_encode($retorno, true);
  
        //   } else {
  
        //     echo json_encode($rspta, true); 
        //   }

        // break;

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
