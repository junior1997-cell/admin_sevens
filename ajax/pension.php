<?php

	ob_start();

	if (strlen(session_id()) < 1){
		session_start();//Validamos si existe o no la sesión
	}
  
  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {

    //Validamos el acceso solo al usuario logueado y autorizado.
    if ($_SESSION['viatico'] == 1) {

      require_once "../modelos/Pension.php";

      $pension=new Pension();
      
      date_default_timezone_set('America/Lima');
      $date_now = date("d-m-Y h.i.s A");

      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

      //------------------pension-------------
      $idproyecto_p        = isset($_POST["idproyecto_p"])? limpiarCadena($_POST["idproyecto_p"]):"";
      $idpension           = isset($_POST["idpension"])? limpiarCadena($_POST["idpension"]):"";
      $proveedor           = isset($_POST["proveedor"])? limpiarCadena($_POST["proveedor"]):"";
      $descripcion_pension = isset($_POST["descripcion_pension"])? limpiarCadena($_POST["descripcion_pension"]):"";

      //------------------D e t a l l e  p e n s i o n -------------

      $iddetalle_pension     = isset($_POST["iddetalle_pension"])? limpiarCadena($_POST["iddetalle_pension"]):"";
      $id_pension            = isset($_POST["id_pension"])? limpiarCadena($_POST["id_pension"]):"";
      $fecha_inicial           = isset($_POST["fecha_inicial"])? limpiarCadena($_POST["fecha_inicial"]):"";
      $fecha_final           = isset($_POST["fecha_final"])? limpiarCadena($_POST["fecha_final"]):"";
      $cantidad_persona      = isset($_POST["cantidad_persona"])? limpiarCadena($_POST["cantidad_persona"]):"";

      $descripcion_detalle   = isset($_POST["descripcion_detalle"])? limpiarCadena($_POST["descripcion_detalle"]):"";
      $forma_pago          = isset($_POST["forma_pago"])? limpiarCadena($_POST["forma_pago"]):"";
      $tipo_comprobante    = isset($_POST["tipo_comprobante"])? limpiarCadena($_POST["tipo_comprobante"]):"";
      $nro_comprobante     = isset($_POST["nro_comprobante"])? limpiarCadena($_POST["nro_comprobante"]):"";
      $monto               = isset($_POST["monto"])? limpiarCadena($_POST["monto"]):"";
      $fecha_emision       = isset($_POST["fecha_emision"])? limpiarCadena($_POST["fecha_emision"]):"";
      $subtotal            = isset($_POST["subtotal"])? limpiarCadena($_POST["subtotal"]):"";
      $igv                 = isset($_POST["igv"])? limpiarCadena($_POST["igv"]):"";
      $val_igv          = isset($_POST["val_igv"])? limpiarCadena($_POST["val_igv"]):"";
      $tipo_gravada     = isset($_POST["tipo_gravada"])? limpiarCadena($_POST["tipo_gravada"]):"";  
      

      $imagen2             = isset($_POST["doc1"])? limpiarCadena($_POST["doc1"]):"";

      switch ($_GET["op"]){
        // :::::::::::::::::::::::::: S E C C I O N   P E N S I O N  ::::::::::::::::::::::::::::::::::::::::::
        case 'guardaryeditar_pension':

          if (empty($idpension)){            
            $rspta=$pension->insertar_pension($idproyecto_p,$proveedor,$descripcion_pension);
            echo json_encode($rspta,true);
          } else {            
            $rspta=$pension->editar_pension($idproyecto_p,$idpension,$proveedor,$descripcion_pension);            
            echo json_encode($rspta,true);
          }

        break;

        case 'tabla_principal':

          $rspta=$pension->tabla_principal($_GET['nube_idproyecto']);
          //Vamos a declarar un array
          $data= Array();
          
          $cont=1;          

          if ($rspta['status']) {
            foreach ($rspta['data'] as $key => $reg) {

              $data[]=array(
                "0"=>$cont++,
                "1"=>'<button class="btn btn-warning btn-sm" onclick="mostrar_pension('.$reg['idpension'].')"><i class="fas fa-pencil-alt"></i></button>'.
                ' <button class="btn btn-info btn-sm" onclick="ingresar_a_pension('.$reg['idpension'].',\'' . $reg['razon_social'].  '\', \'\', \'\', \'\', \'\' ); mostrar_form_table(2);"><span class="d-none d-sm-inline-block">Ingresar</span> <i class="fas fa-sign-in-alt"></i></button>',
                "2"=>'<div class="user-block">
                  <span ><p class="text-primary m-b-02rem font-weight-bold" > Pensión. '.$reg['razon_social'].'</p></span>
                  <span class=" text-gray font-size-13px">'.$reg['direccion'].' </span>
                  </div>',
                "3"=>'<textarea cols="30" rows="2" class="textarea_datatable" readonly="">'.$reg['descripcion'].'</textarea>',
                "4"=>$reg['total_gasto'],       
                "5"=>date("d/m/Y", strtotime($reg['updated_at'])) .' <b>'. date("h:i", strtotime($reg['updated_at'])) .'</b> '. date("a", strtotime($reg['updated_at']))               
              );

            }
            $results = array(
              "sEcho"=>1, //Información para el datatables
              "iTotalRecords"=>count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
              "aaData"=>$data);
            echo json_encode($results,true);
            
          } else {

            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          }
        
        break;

        case 'mostrar_pension':
          $rspta=$pension->mostrar_pension($idpension);
            //Codificar el resultado utilizando json
            echo json_encode($rspta,true);
        break;
        
        case 'total_pension':

          //$idpago_Comprobante='1';
          $rspta=$pension->total_pension($_POST['idproyecto']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta,true);

        break;

        // :::::::::::::::::::::::::: S E C C I O N   D E T A L L E   P E N S I O N  ::::::::::::::::::::::::::

        case 'guardaryeditar_detalle_pension':

          // imgen de perfil
          if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {

            $imagen2=$_POST["doc_old_1"]; $flat_img1 = false;

          } else {

            $ext1 = explode(".", $_FILES["doc1"]["name"]); $flat_img1 = true;						

            $imagen2 = $date_now .' '.rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

            move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/pension/comprobante/" . $imagen2);
          
          }

          if (empty($iddetalle_pension)){
            
            $rspta=$pension->insertar_detalles_pension($id_pension,$fecha_inicial,$fecha_final,$cantidad_persona,$subtotal,$igv,$val_igv,$monto,$forma_pago,$tipo_comprobante,$fecha_emision,$tipo_gravada,$nro_comprobante,$descripcion_detalle,$imagen2);
            echo json_encode($rspta);
          }
          else {

            // validamos si existe LA IMG para eliminarlo
            if ($flat_img1 == true) {

              $datos_f1 = $pension->obtenerDoc($iddetalle_pension);
        
              $img1_ant = $datos_f1['data']->fetch_object()->comprobante;
        
              if ($img1_ant != "") {
        
                unlink("../dist/docs/pension/comprobante/" . $img1_ant);
              }

            }
            
            $rspta=$pension->editar_detalles_pension($iddetalle_pension,$id_pension,$fecha_inicial,$fecha_final,$cantidad_persona,$subtotal,$igv,$val_igv,$monto,$forma_pago,$tipo_comprobante,$fecha_emision,$tipo_gravada,$nro_comprobante,$descripcion_detalle,$imagen2);
            
            echo json_encode($rspta);
          }

        break;

        case 'tbla_detalle_comprobante':

          $rspta=$pension->tbla_detalle_pension($_GET['id_pension'], $_GET["fecha_1"], $_GET["fecha_2"], $_GET["id_proveedor"], $_GET["comprobante"]);

          //Vamos a declarar un array
          $data= Array();  $cont=1;

          if ($rspta['status']) {

            while ($reg=$rspta['data']->fetch_object()){ 
              $comprobante= empty($reg->comprobante)?'<div><center><a type="btn btn-danger" class=""><i class="fas fa-file-invoice-dollar fa-2x text-gray-50"></i></a></center></div>':'<div><center><a type="btn btn-danger" class=""  href="#" onclick="ver_modal_comprobante('."'".$reg->comprobante."'".','."'".$reg->tipo_comprobante."'".','."'".$reg->numero_comprobante."'".')"><i class="fas fa-file-invoice fa-2x"></i></a></center></div>';

              $data[]=array(
                "0"=>$cont++,
                "1"=>($reg->estado)?'<button class="btn btn-warning btn-sm" onclick="mostar_editar_detalle_pension('.$reg->iddetalle_pension.')"><i class="fas fa-pencil-alt"></i></button>'.
                ' <button class="btn btn-danger btn-sm" onclick="eliminar_detalle_pension('.$reg->iddetalle_pension  .', \''. $reg->fecha_inicial.'\', \''.$reg->fecha_final.'\')"><i class="fas fa-skull-crossbones"></i></button>':'',                
                "2"=>'<textarea cols="30" rows="1" class="textarea_datatable" readonly="">'.$reg->descripcion.'</textarea>',
                "3"=> date("d/m/Y", strtotime($reg->fecha_inicial)) .' - '. date("d/m/Y", strtotime($reg->fecha_final)),
                "4"=>number_format($reg->cantidad_persona, 2, '.', ','),
                "5"=> empty($reg->forma_pago)?' - ':$reg->forma_pago, 
                "6"=>'<span class="text-nowrap"><b class="text-primary">'.$reg->tipo_comprobante.'</b>'.(empty($reg->numero_comprobante)?"":' ─ '.$reg->numero_comprobante).'</span>',
                "7"=>$reg->fecha_emision,
                "8"=>number_format($reg->subtotal, 2, '.', ''), 
                "9"=>number_format($reg->igv, 2, '.', ''),
                "10"=>number_format($reg->precio_parcial, 2, '.', ''),
                "11"=>$comprobante . $toltip,

                "12"=>date("d/m/Y", strtotime($reg->fecha_inicial)),
                "13"=>date("d/m/Y", strtotime($reg->fecha_final)),
                "14"=>$reg->tipo_comprobante,
                "15"=>$reg->numero_comprobante,
                "16"=>$reg->val_igv,                
              );
            }
            //$suma=array_sum($rspta->fetch_object()->monto);
            $results = array(
              "sEcho"=>1, //Información para el datatables
              "iTotalRecords"=>count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
              "data"=>$data
            );
            echo json_encode($results,true);

          } else {

            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          }

        break;

        case 'mostar_editar_detalle_pension':
          $rspta=$pension->mostrar_detalle_pension($_POST['id_detalle_pension']);
            //Codificar el resultado utilizando json
            echo json_encode($rspta,true);
        break;

        case 'total_detalle_pension':

          //$idpago_Comprobante='1';
          $rspta=$pension->total_detalle_pension($_POST['id_pension'], $_POST["fecha_1"], $_POST["fecha_2"], $_POST["id_proveedor"], $_POST["comprobante"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta,true);

        break;
        
        case 'desactivar_detalle_comprobante':

          $rspta=$pension->desactivar_detalle_comprobante($_GET['id_tabla']);
          echo json_encode($rspta,true);
		
        break;

        case 'eliminar_detalle_comprobante':

          $rspta=$pension->eliminar_detalle_comprobante($_GET['id_tabla']);
          echo json_encode($rspta,true);
		
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