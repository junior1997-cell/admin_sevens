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

      require_once "../modelos/Otro_ingreso.php";
      require_once "../modelos/AllProveedor.php";

      $otro_ingreso = new Otro_ingreso();
      $proveedor = new AllProveedor();

      date_default_timezone_set('America/Lima');
      $date_now = date("d-m-Y h.i.s A");

      $idotro_ingreso     = isset($_POST["idotro_ingreso"]) ? limpiarCadena($_POST["idotro_ingreso"]) : ""; 
      $idproyecto       = isset($_POST["idproyecto"]) ? limpiarCadena($_POST["idproyecto"]) : "";      
      $idproveedor      = isset($_POST["idproveedor"]) ? limpiarCadena($_POST["idproveedor"]) : "";     
      $fecha_i          = isset($_POST["fecha_i"]) ? limpiarCadena($_POST["fecha_i"]) : "";
      $forma_pago       = isset($_POST["forma_pago"]) ? limpiarCadena($_POST["forma_pago"]) : "";
      $tipo_comprobante = isset($_POST["tipo_comprobante"]) ? limpiarCadena($_POST["tipo_comprobante"]) : "";
      $nro_comprobante  = isset($_POST["nro_comprobante"]) ? limpiarCadena($_POST["nro_comprobante"]) : "";
      $subtotal         = isset($_POST["subtotal"]) ? limpiarCadena($_POST["subtotal"]) : "";
      $igv              = isset($_POST["igv"]) ? limpiarCadena($_POST["igv"]) : "";
      $val_igv          = isset($_POST["val_igv"])? limpiarCadena($_POST["val_igv"]):"";
      $tipo_gravada     = isset($_POST["tipo_gravada"])? limpiarCadena($_POST["tipo_gravada"]):"";  
      
      $precio_parcial   = isset($_POST["precio_parcial"]) ? limpiarCadena($_POST["precio_parcial"]) : "";
      $descripcion      = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";

      $ruc              = isset($_POST["ruc"]) ? limpiarCadena($_POST["ruc"]) : "";
      $razon_social     = isset($_POST["razon_social"]) ? limpiarCadena($_POST["razon_social"]) : "";
      $direccion        = isset($_POST["direccion"]) ? limpiarCadena($_POST["direccion"]) : "";
      $glosa            = isset($_POST["glosa"]) ? limpiarCadena($_POST["glosa"]) : "";

      $foto2 = isset($_POST["doc1"]) ? limpiarCadena($_POST["doc1"]) : "";

      // :::::::::::::::::::::::::::::::::::: D A T O S   P R O V E E D O R ::::::::::::::::::::::::::::::::::::::
      $idproveedor_prov		= isset($_POST["idproveedor_prov"])? limpiarCadena($_POST["idproveedor_prov"]):"";
      $nombre_prov 		    = isset($_POST["nombre_prov"])? limpiarCadena($_POST["nombre_prov"]):"";
      $tipo_documento_prov= isset($_POST["tipo_documento_prov"])? limpiarCadena($_POST["tipo_documento_prov"]):"";
      $num_documento_prov	= isset($_POST["num_documento_prov"])? limpiarCadena($_POST["num_documento_prov"]):"";
      $direccion_prov		  = isset($_POST["direccion_prov"])? limpiarCadena($_POST["direccion_prov"]):"";
      $telefono_prov		  = isset($_POST["telefono_prov"])? limpiarCadena($_POST["telefono_prov"]):"";
      $c_bancaria_prov		= isset($_POST["c_bancaria_prov"])? limpiarCadena($_POST["c_bancaria_prov"]):"";
      $cci_prov		    	  = isset($_POST["cci_prov"])? limpiarCadena($_POST["cci_prov"]):"";
      $c_detracciones_prov= isset($_POST["c_detracciones_prov"])? limpiarCadena($_POST["c_detracciones_prov"]):"";
      $banco_prov			    = isset($_POST["banco_prov"])? limpiarCadena($_POST["banco_prov"]):"";
      $titular_cuenta_prov= isset($_POST["titular_cuenta_prov"])? limpiarCadena($_POST["titular_cuenta_prov"]):"";
      
      switch ($_GET["op"]) {

        case 'guardar_y_editar_otros_ingresos':
          // Comprobante
          if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {
      
            $comprobante = $_POST["doc_old_1"];
      
            $flat_ficha1 = false;
      
          } else {
      
            $ext1 = explode(".", $_FILES["doc1"]["name"]);
      
            $flat_ficha1 = true;
      
            $comprobante = $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);
      
            move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/otro_ingreso/comprobante/" . $comprobante);
          }
      
          if (empty($idotro_ingreso)) {
            //var_dump($idproyecto,$idproveedor);
            $rspta = $otro_ingreso->insertar($idproyecto, $idproveedor , $fecha_i, $precio_parcial, $subtotal, $igv,$val_igv,$tipo_gravada, $descripcion, $forma_pago, $tipo_comprobante, $nro_comprobante, $comprobante, $ruc, $razon_social, $direccion, $glosa);
            
            echo json_encode($rspta, true);
      
          } else {
            //validamos si existe comprobante para eliminarlo
            if ($flat_ficha1 == true) {
      
              $datos_ficha1 = $otro_ingreso->ficha_tec($idotro_ingreso);
      
              $ficha1_ant = $datos_ficha1['data']->fetch_object()->comprobante;
      
              if ($ficha1_ant != "") {
      
                unlink("../dist/docs/otro_ingreso/comprobante/" . $ficha1_ant);
              }
            }
      
            $rspta = $otro_ingreso->editar($idotro_ingreso, $idproyecto, $idproveedor , $fecha_i, $precio_parcial, $subtotal, $igv,$val_igv,$tipo_gravada, $descripcion, $forma_pago, $tipo_comprobante, $nro_comprobante, $comprobante, $ruc, $razon_social, $direccion,$glosa);
            //var_dump($idotro_ingreso,$idproveedor);
            echo json_encode($rspta, true);
          }
        break;
      
        case 'desactivar':
      
          $rspta = $otro_ingreso->desactivar($idotro_ingreso);
      
          echo json_encode($rspta, true);
      
        break;
      
        case 'activar':
      
          $rspta = $otro_ingreso->activar($idotro_ingreso);
      
          echo json_encode($rspta, true);
      
        break;

        case 'eliminar':
      
          $rspta = $otro_ingreso->eliminar($idotro_ingreso);
      
          echo json_encode($rspta, true);
      
        break;
      
        case 'mostrar':
      
          $rspta = $otro_ingreso->mostrar($idotro_ingreso);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
      
        break;
      
        case 'verdatos':
      
          $rspta = $otro_ingreso->mostrar($idotro_ingreso);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
      
        break;
      
        case 'tbla_principal':
          $rspta = $otro_ingreso->tbla_principal($_GET["idproyecto"]);
          //Vamos a declarar un array
          $data = [];  $cont = 1;

          if ($rspta['status'] == true) {
            while ($reg = $rspta['data']->fetch_object()) {
              
              $comprobante = empty($reg->comprobante) ? ( '<div><center><a type="btn btn-danger" class=""><i class="fas fa-file-invoice-dollar fa-2x text-gray-50"></i></a></center></div>') : ('<div><center><a type="btn btn-danger" class=""  href="#" onclick="modal_comprobante(\'' . $reg->comprobante .'\', \''. $reg->fecha_i .'\', \''. $reg->tipo_comprobante .'\', \''. $reg->numero_comprobante .'\', \''. 'dist/docs/otro_ingreso/comprobante/'  . '\')"><i class="fas fa-file-invoice-dollar fa-2x"></i></a></center></div>');
              
              $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

              $data[] = [
                "0" => $cont++,
                "1" => $reg->estado ? '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->idotro_ingreso . ')"><i class="fas fa-pencil-alt"></i></button>' .
                    ' <button class="btn btn-danger  btn-sm" onclick="eliminar(' . $reg->idotro_ingreso . ')"><i class="fas fa-skull-crossbones"></i> </button>'.
                    ' <button class="btn btn-info btn-sm" onclick="ver_datos('.$reg->idotro_ingreso.')" data-toggle="tooltip" data-original-title="Ver datos"><i class="far fa-eye"></i></button>':
                    '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->idotro_ingreso . ')"><i class="fa fa-pencil-alt"></i></button>' .
                    ' <button class="btn btn-primary btn-sm" onclick="activar(' . $reg->idotro_ingreso . ')"><i class="fa fa-check"></i></button>',
                "2" => $reg->forma_de_pago,
                "3" =>'<div class="user-block">
                    <span class="username ml-0" > <p class="text-primary m-b-02rem">' . $reg->tipo_comprobante . '</p> </span>
                    <span class="description ml-0" >N° ' . (empty($reg->numero_comprobante) ? " - " : $reg->numero_comprobante) . '</span>         
                  </div>',
                "4" => date("d/m/Y", strtotime($reg->fecha_i)),
                "5" =>'S/ '. number_format($reg->subtotal, 2, '.', ','),
                "6" =>'S/ '. number_format($reg->igv, 2, '.', ','),
                "7" =>'S/ '. number_format($reg->costo_parcial, 2, '.', ','),
                "8" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly="">' . $reg->descripcion . '</textarea>',
                "9" => $comprobante,
                "10" => $reg->estado ? '<span class="text-center badge badge-success">Activado</span>' . $toltip : '<span class="text-center badge badge-danger">Desactivado</span>' . $toltip,
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
      
        case 'total':
      
          $rspta = $otro_ingreso->total($idproyecto);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
      
        break;

        // :::::::::::::::::::::::::: S E C C I O N   P R O V E E D O R  ::::::::::::::::::::::::::
        case 'guardar_proveedor':
      
          if (empty($idproveedor_prov)){
      
            $rspta=$proveedor->insertar($nombre_prov, $tipo_documento_prov, $num_documento_prov, $direccion_prov, $telefono_prov,
            $c_bancaria_prov, $cci_prov, $c_detracciones_prov, $banco_prov, $titular_cuenta_prov);
            
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
