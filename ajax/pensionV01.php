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

      require_once "../modelos/PensionV01.php";

      $pension=new PensionV01();
      
      date_default_timezone_set('America/Lima');
      $date_now = date("d-m-Y h.i.s A");

      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

      //============Comprobantes========================
      $idpension_f         = isset($_POST["idpension_f"])? limpiarCadena($_POST["idpension_f"]):"";
      $idfactura_pension   = isset($_POST["idfactura_pension"])? limpiarCadena($_POST["idfactura_pension"]):"";
      $forma_pago          = isset($_POST["forma_pago"])? limpiarCadena($_POST["forma_pago"]):"";
      $tipo_comprobante    = isset($_POST["tipo_comprobante"])? limpiarCadena($_POST["tipo_comprobante"]):"";

      $nro_comprobante     = isset($_POST["nro_comprobante"])? limpiarCadena($_POST["nro_comprobante"]):"";
      $monto               = isset($_POST["monto"])? limpiarCadena($_POST["monto"]):"";
      $fecha_emision       = isset($_POST["fecha_emision"])? limpiarCadena($_POST["fecha_emision"]):"";
      $descripcion         = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
      $subtotal            = isset($_POST["subtotal"])? limpiarCadena($_POST["subtotal"]):"";
      $igv                 = isset($_POST["igv"])? limpiarCadena($_POST["igv"]):"";
      $val_igv          = isset($_POST["val_igv"])? limpiarCadena($_POST["val_igv"]):"";
      $tipo_gravada     = isset($_POST["tipo_gravada"])? limpiarCadena($_POST["tipo_gravada"]):"";  

      $imagen2             = isset($_POST["doc1"])? limpiarCadena($_POST["doc1"]):"";
      //------------------pension-------------
      $idproyecto_p        = isset($_POST["idproyecto_p"])? limpiarCadena($_POST["idproyecto_p"]):"";
      $idpension           = isset($_POST["idpension"])? limpiarCadena($_POST["idpension"]):"";
      $proveedor           = isset($_POST["proveedor"])? limpiarCadena($_POST["proveedor"]):"";
      $p_desayuno          = isset($_POST["p_desayuno"])? limpiarCadena($_POST["p_desayuno"]):"";
      $p_almuerzo          = isset($_POST["p_almuerzo"])? limpiarCadena($_POST["p_almuerzo"]):"";
      $p_cena              = isset($_POST["p_cena"])? limpiarCadena($_POST["p_cena"]):"";
      $descripcion_pension = isset($_POST["descripcion_pension"])? limpiarCadena($_POST["descripcion_pension"]):"";
      //$idproyecto_p,$idpension,$proveedor,$p_desayuno,$p_almuerzo,$p_cena
      //$idfactura_pension ,$idpension_f,$tipo_comprobante,$nro_comprobante,$monto,$fecha_emision,$descripcion,$subtotal,$igv

      switch ($_GET["op"]){

        case 'guardaryeditar':

            $rspta=$pension->insertar_editar($_POST['array_detalle_pen'],$_POST['array_semana_pen']);
            
           echo json_encode($rspta,true);
           // echo $rspta ;

        break;

        case 'listar_semana_botones':

          $nube_idproyecto = $_POST["nube_idproyecto"];

          $rspta=$pension->listarsemana_botones($nube_idproyecto);

          //Codificar el resultado utilizando json
          echo json_encode($rspta,true);	

        break;
        case 'ver_datos_semana':
          
          $f1 = $_POST["f1"];
          $f2 = $_POST["f2"];
          $nube_idproyect = $_POST["nube_idproyect"];
          $id_pen = $_POST["id_pen"];
          $rspta=$pension->ver_detalle_semana_dias($f1,$f2,$nube_idproyect,$id_pen);

          //Codificar el resultado utilizando json
          echo json_encode($rspta,true);		
        break;

        case 'guardaryeditar_Comprobante':

            // imgen de perfil
          if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {

              $imagen2=$_POST["doc_old_1"]; $flat_img1 = false;

            } else {

              $ext1 = explode(".", $_FILES["doc1"]["name"]); $flat_img1 = true;						

              $imagen2 = $date_now .' '.rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

              move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/pension/comprobante/" . $imagen2);
            
            }


          if (empty($idfactura_pension )){
            
            $rspta=$pension->insertar_comprobante($idpension_f,$forma_pago,$tipo_comprobante,$nro_comprobante,$monto,$fecha_emision,$descripcion,$subtotal,$igv,$val_igv,$tipo_gravada,$imagen2);
            echo json_encode($rspta,true);
          } else {
            // validamos si existe LA IMG para eliminarlo
            if ($flat_img1 == true) {

              $datos_f1 = $pension->obtenerDoc($idfactura_pension );
        
              $img1_ant = $datos_f1['data']->fetch_object()->comprobante;
        
              if ($img1_ant != "") {
        
                unlink("../dist/docs/pension/comprobante/" . $img1_ant);
              }

            }
            
            $rspta=$pension->editar_comprobante($idfactura_pension,$idpension_f,$forma_pago,$tipo_comprobante,$nro_comprobante,$monto,$fecha_emision,$descripcion,$subtotal,$igv,$val_igv,$tipo_gravada,$imagen2);
            
            echo json_encode($rspta,true);
          }

        break;
        
        case 'listar_comprobantes':

          //$idpension_f ='5';
          //$_GET['idpension_f']
          $rspta=$pension->listar_comprobantes($_GET['idpension']);

          //Vamos a declarar un array
          $data= Array();
          $comprobante='';
          $subtotal=0;
          $igv=0;
          $monto=0;
          $cont=1;
          if ($rspta['status']) {

            while ($reg=$rspta['data']->fetch_object()){
              $subtotal=round($reg->subtotal, 2);
              $igv=round($reg->igv, 2);
              $monto=round($reg->monto, 2 );
              
              $comprobante= empty($reg->comprobante)?'<div><center><a type="btn btn-danger" class=""><i class="fas fa-file-invoice-dollar fa-2x text-gray-50"></i></a></center></div>':'<div><center><a type="btn btn-danger" class=""  href="#" onclick="ver_modal_comprobante('."'".$reg->comprobante."'".')"><i class="fas fa-file-invoice fa-2x"></i></a></center></div>';
               
              $data[]=array(
                "0"=>$cont++,
                "1"=>($reg->estado)?'<button class="btn btn-warning btn-sm" onclick="mostrar_comprobante('.$reg->idfactura_pension  .')"><i class="fas fa-pencil-alt"></i></button>'.
                ' <button class="btn btn-danger btn-sm" onclick="eliminar_comprobante('.$reg->idfactura_pension  .', \''. $reg->tipo_comprobante .' '.(empty($reg->nro_comprobante)?" - ":$reg->nro_comprobante).'\')"><i class="fas fa-skull-crossbones"></i></button>':
                '<button class="btn btn-warning btn-sm" onclick="mostrar_comprobante('.$reg->idfactura_pension  .')"><i class="fa fa-pencil-alt"></i></button>'.
                ' <button class="btn btn-primary btn-sm" onclick="activar_comprobante('.$reg->idfactura_pension  .')"><i class="fa fa-check"></i></button>',                
                "2"=>$reg->fecha_emision,
                "3"=> empty($reg->forma_de_pago)?' - ':$reg->forma_de_pago, 
                "4"=>'<span class="text-nowrap"><b class="text-primary">'.$reg->tipo_comprobante.'</b> - '.(empty($reg->nro_comprobante)?" - ":$reg->nro_comprobante).'</span>',
                "5"=>'S/ '.number_format($subtotal, 2, '.', ','), 
                "6"=>'S/ '.number_format($igv, 2, '.', ','),
                "7"=>'S/ '.number_format($monto, 2, '.', ','),
                "8"=>'<textarea cols="30" rows="1" class="textarea_datatable" readonly="">'.$reg->descripcion.'</textarea>',
                "9"=>$comprobante . $toltip
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

        case 'desactivar_comprobante':

          $rspta=$pension->desactivar_comprobante($_GET["id_tabla"]);
          echo json_encode($rspta,true);
		
        break;
      
        case 'activar_comprobante':

          $rspta=$pension->activar_comprobante($_GET["id_tabla"]);
          echo json_encode($rspta,true);
	
        break;

        case 'eliminar_comprobante':

          $rspta=$pension->eliminar_comprobante($idfactura_pension);
          echo json_encode($rspta,true);
		
        break;
        
        case 'mostrar_comprobante':
          //$idpago_Comprobante='1';
          $rspta=$pension->mostrar_comprobante($idfactura_pension );
            //Codificar el resultado utilizando json
            echo json_encode($rspta);
	
        break;

        case 'total_monto':
          //falta
          $rspta=$pension->total_monto_comp($idpension);
           echo json_encode($rspta); 
      
        break;
        //------------ fin Comprobantes---------------------
        //------------registrar pension--------------
        case 'guardaryeditar_pension':

          if (empty($idpension)){
            
            $rspta=$pension->insertar_pension($idproyecto_p,$proveedor,$p_desayuno,$p_almuerzo,$p_cena,$descripcion_pension,$_POST['servicio_p']);
            echo json_encode($rspta);
          }
          else {
            
            $rspta=$pension->editar_pension($idproyecto_p,$idpension,$proveedor,$p_desayuno,$p_almuerzo,$p_cena,$descripcion_pension,$_POST['servicio_p']);
            
            echo json_encode($rspta);
          }

        break;

        case 'tabla_principal':

          $rspta=$pension->tabla_principal($_GET['nube_idproyecto']);
          //Vamos a declarar un array
          $data= Array();
          $total=0;
          $total_pagos=0;
          $Saldo=0;
          $cont=1;

          $c="";
          $nombre="";
          $icon="";
          $cc="";

          if ($rspta['status']) {

            while ($reg=$rspta['data']->fetch_object()){ 

              $total=$pension->total_x_pension($reg->idpension);
              $rspta2=$pension->total_pago_x_pension($reg->idpension);

              $total_pagos =$rspta2['data']['total_pago'];
              $saldo = $total-$total_pagos;
              if($saldo==$total){
                $c="danger";
                $nombre=" Pagar";
                $icon="dollar-sign";
                $cc="danger";
              }else if ($saldo<$total && $saldo>"0" ) {
                $c="warning";
                $nombre=" Pagar";
                $icon="dollar-sign";
                $cc="warning";
              } else if ($saldo<="0" || $saldo=="0") {               
                $c="success";
                $nombre=" Ver";
                $info="info";
                $icon="eye";
                $cc="success"; 
              }

              $data[]=array(
                "0"=>$cont++,
                "1"=>'<button class="btn btn-warning btn-sm" onclick="mostrar_pension('.$reg->idpension.')"><i class="fas fa-pencil-alt"></i></button>'.
                ' <button class="btn btn-info btn-sm" onclick="ingresar_a_pension('.$reg->idpension.','.$reg->idproyecto.',\'' . $reg->razon_social.  '\')"><span class="d-none d-sm-inline-block">Ingresar</span> <i class="fas fa-sign-in-alt"></i></button>',
                "2"=>'<div class="user-block">
                  <span style="font-weight: bold;" ><p class="text-primary"style="margin-bottom: 0.2rem !important"; > Pensión. '.$reg->razon_social.'</p></span>
                  <span style="font-weight: bold; font-size: 15px;">'.$reg->direccion.' </span>
                  </div>',
                "3"=>'<textarea cols="30" rows="2" class="textarea_datatable" readonly="">'.$reg->descripcion.'</textarea>',
                "4"=>'<b>'.number_format($total, 2, '.', ',').'</b>', 
                "5"=>' <button class="btn btn-info btn-sm" onclick="ver_detalle_x_servicio( '.$reg->idpension.')">Ver Servicios <i class="far fa-eye"></i></button>',
                "6"=>'<div class="text-center"> <button class="btn btn-'.$c.' btn-sm m-t-2px" onclick="listar_comprobantes('.$reg->idpension.')"><i class="fas fa-'. $icon.'"> </i>'.$nombre.'</button> 
                <button class="btn btn-'.$cc.' btn-sm">'.number_format($total_pagos, 2, '.', ',').'</button></div>',
                "7"=>number_format($saldo, 2, '.', ',')
                  
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

        case 'ver_detalle_x_servicio':

          $rspta=$pension->ver_detalle_x_servicio($_GET['idpension']);
          //Vamos a declarar un array
          $data= Array();
          $cont=1;
          if ($rspta['status']) {
            while ($reg=$rspta['data']->fetch_object()){ 
              $data[]=array(
                "0"=>$cont++,
                "1"=>'<div class="user-block">
                  <span style="font-weight: bold;" ><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'.$reg->nombre_servicio .'</p></span></div>',
                "2"=>'<b>'.number_format($reg->precio, 2, '.', ',').'</b>', 
                "3"=>'<b>'.$reg->cantidad_total_platos.'</b>', 
                "4"=>'<b>'.number_format($reg->adicional_descuento, 2, '.', ',').'</b>', 
                "5"=>'<b>'.number_format($reg->total, 2, '.', ',').'</b>'
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