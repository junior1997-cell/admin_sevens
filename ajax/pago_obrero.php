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
    if ($_SESSION['pago_trabajador'] == 1) {

      require_once "../modelos/Pago_obrero.php";
      require_once "../modelos/Fechas.php";

      $pagoobrero = new PagoObrero();

      date_default_timezone_set('America/Lima');
      $date_now = date("d-m-Y h.i.s A");

      // DATA - agregar pago x quincena o semana	
      $idpagos_q_s_obrero 		  = isset($_POST["idpagos_q_s_obrero"])? limpiarCadena($_POST["idpagos_q_s_obrero"]):"";
      $idresumen_q_s_asistencia = isset($_POST["idresumen_q_s_asistencia"])? limpiarCadena($_POST["idresumen_q_s_asistencia"]):"";
      $forma_pago	      = isset($_POST["forma_pago"])? limpiarCadena($_POST["forma_pago"]):"";
      $cuenta_deposito  = isset($_POST['cuenta_deposito'])? $_POST['cuenta_deposito']:"";
      $monto 		        = isset($_POST['monto'])? $_POST['monto']:"";
      $fecha_pago 		  = isset($_POST['fecha_pago'])? $_POST['fecha_pago']:"";
      $descripcion 		  = isset($_POST['descripcion'])? $_POST['descripcion']:"";
      $numero_comprobante = isset($_POST["numero_comprobante"])? limpiarCadena($_POST["numero_comprobante"]):"";

      $doc_old_1 		    = isset($_POST['doc_old_1'])? $_POST['doc_old_1']:"";
      $doc1 		        = isset($_POST['doc1'])? $_POST['doc1']:"";

      $doc_old_2 		    = isset($_POST['doc_old_2'])? $_POST['doc_old_2']:"";
      $doc2 	                      = isset($_POST['doc2'])? $_POST['doc2']:"";

      // DATA - recibos por honorarios
      $numero_comprobante_rh	      = isset($_POST["numero_comprobante_rh"])? limpiarCadena($_POST["numero_comprobante_rh"]):"";
      $idresumen_q_s_asistencia_rh	= isset($_POST["idresumen_q_s_asistencia_rh"])? limpiarCadena($_POST["idresumen_q_s_asistencia_rh"]):"";
      

      switch ($_GET["op"]){

        case 'listar_tbla_principal':
          $nube_idproyecto = $_GET["nube_idproyecto"];         

          $rspta=$pagoobrero->listar_tbla_principal($nube_idproyecto); 
          //echo json_encode($rspta, true);
          //Vamos a declarar un array
          $data= Array();
          $cont=1;
          $imagen_error = "this.src='../dist/svg/user_default.svg'";

          $Object = new DateTime();
          $Object->setTimezone(new DateTimeZone('America/Lima'));
          $date_actual = $Object->format("d-m-Y");  

          if ($rspta['status']) {
            
            foreach ( $rspta['data'] as $key => $value) {
              $btn_depositos = "";
              $saldo = floatval($value['pago_quincenal']) - floatval($value['total_deposito']);

              // Pintamos el bonton depositos segun las cantidades            
              if ( floatval($value['total_deposito']) == 0) {
                $btn_depositos = "btn-danger";
              } else {
                if ( floatval($value['total_deposito']) > 0 && floatval($value['total_deposito'])  < floatval($value['pago_quincenal'])) {
                  $btn_depositos = "btn-warning";
                } else {
                  if ( floatval($value['total_deposito']) >= floatval($value['pago_quincenal'])) {
                    $btn_depositos = "btn-success";
                  }
                }              
              }

              $data[]=array(
                "0"=>$cont++,
                "1"=>'<div class="user-block">
                  <img class="img-circle" src="../dist/docs/all_trabajador/perfil/'. $value['imagen_perfil'] .'" alt="User Image" onerror="'.$imagen_error.'">
                  <span class="username"><p class="text-primary m-b-02rem" >'. $value['nombres_trabajador'] .'</p></span>
                  <span class="description">'. $value['nombre_tipo'].' / '.$value['nombre_cargo'] .' ─ '. $value['tipo_documento'] .': '. $value['numero_documento'] .' </span>                  
                </div>',                
                "2"=>$value['banco'],
                "3"=>$value['cuenta_bancaria'],            
                "4"=>$value['total_hn'].' / '. $value['total_he'],      
                "5"=>$value['sabatical'],           
                "6"=>'S/ '.  number_format($value['sueldo_mensual'], 2, '.', ','),               
                "7"=>'S/ '.  number_format($value['pago_quincenal'], 2, '.', ','),
                "8"=>'<div class="formato-numero-conta "> 
                  <button class="btn '.$btn_depositos.' btn-sm mr-1" onclick="detalle_q_s_trabajador( '.$value['idtrabajador_por_proyecto'] .', \'' . $value['fecha_pago_obrero'] .  '\', \'' . $value['nombres_trabajador'] . '\', \'' .  $value['cuenta_bancaria'] . '\' ); table_show_hide(2);">
                    <i class="far fa-eye"></i> Pagar
                  </button> 
                  <button style="font-size: 14px;" class="btn '.$btn_depositos.' btn-sm">S/ '.number_format($value['total_deposito'], 2, '.', ',').'</button>
                </div>',
                "9"=>'S/ ' . number_format($saldo, 2, '.', ','),
                "10"=>$value['sum_estado_envio_contador'], 
                "11"=>$value['fecha_inicio'],
                "12"=> $date_actual,
                "13"=>$value['fecha_fin'],    

                "14"=>$value['nombres_trabajador'], 
                "15"=>$value['nombre_tipo'], 
                "16"=>$value['nombre_cargo'],   
                "17"=>$value['tipo_documento'],
                "18"=>$value['numero_documento'],
                "19"=>$value['total_hn'],
                "20"=>$value['total_he'],
                "21"=>'S/ '.number_format($value['total_deposito'], 2, '.', ','),     
              );
            }
            $results = array(
              "sEcho"=>1, //Información para el datatables
              "iTotalRecords"=>count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
              "data"=>$data);
            echo json_encode($results, true);
          } else {
            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          }
          
        break;

        case 'mostrar_sumas_totales_tbla_principal':
          $rspta=$pagoobrero->mostrar_total_tbla_principal($_POST["id_proyecto"]);
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true);
        break;       
        
        
        // :::::::::::::::::::::::::: P A G O S  U N   S O L O   O B R E R O S ::::::::::::::::::::::::::::::::::::::::::::::
        case 'guardar_y_editar_pagos_x_q_s':
          	
          //*DOC 1*//
          if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {
            $flat_doc1 = false;  $doc1 = $_POST["doc_old_1"];
          } else {
            $flat_doc1 = true;  $ext_doc1 = explode(".", $_FILES["doc1"]["name"]);              
            $doc1 = $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext_doc1);
            move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/pago_obrero/baucher_deposito/" . $doc1);            
          }	

          //*DOC 2*//
          if (!file_exists($_FILES['doc2']['tmp_name']) || !is_uploaded_file($_FILES['doc2']['tmp_name'])) {
            $flat_doc2 = false;
            $doc2      = $_POST["doc_old_2"];
          } else {
            $flat_doc2 = true;
            $ext_doc2  = explode(".", $_FILES["doc2"]["name"]);              
            $doc2 = $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext_doc2);
            move_uploaded_file($_FILES["doc2"]["tmp_name"], "../dist/docs/pago_obrero/recibos_x_honorarios/" . $doc2);            
          }	

          // registramos un nuevo: pago x mes
          if (empty($idpagos_q_s_obrero)){

            $rspta=$pagoobrero->insertar_pagos_x_q_s( $idresumen_q_s_asistencia, $forma_pago, $cuenta_deposito, $monto, $fecha_pago, $descripcion, $numero_comprobante, $doc1, $doc2);
            
            echo json_encode( $rspta, true);

          }else {

            // validamos si existe el "baucher" para eliminarlo
            if ($flat_doc1 == true) {
              $datos_f1 = $pagoobrero->obtenerDocs($idpagos_q_s_obrero);
              $doc1_ant = $datos_f1['data']->fetch_object()->baucher;
              if ($doc1_ant != "") { unlink("../dist/docs/pago_obrero/baucher_deposito/" . $doc1_ant); }
            }

            // eliminados si existe el "doc en la BD"
            if ($flat_doc2 == true) {
              $datos_f2 = $pagoobrero->obtenerDocs($idpagos_q_s_obrero);
              $doc2_ant = $datos_f2['data']->fetch_object()->recibos_x_honorarios;
              if ( !empty($doc2_ant) ) { unlink("../dist/docs/pago_obrero/recibos_x_honorarios/" . $doc2_ant); }
            }

            // editamos un pago x mes existente
            $rspta=$pagoobrero->editar_pagos_x_q_s( $idpagos_q_s_obrero, $idresumen_q_s_asistencia, $forma_pago, $cuenta_deposito, $monto, $fecha_pago, $descripcion, $numero_comprobante, $doc1, $doc2);
            
            echo json_encode( $rspta, true);
          }

        break;
        
        case 'listar_tbla_q_s':

          $rspta=$pagoobrero->listar_tbla_q_s( $_POST["id_trabajdor_x_proyecto"]);
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true);

        break;

        case 'listar_tbla_pagos_x_q_s':

          $idresumen_q_s_asistencia = $_GET["idresumen_q_s_asistencia"];

          $rspta=$pagoobrero->listar_tbla_pagos_x_q_s($idresumen_q_s_asistencia);
          //Vamos a declarar un array
          $data= Array();
          $cont = 1;
          $imagen_error = "this.src='../dist/svg/user_default.svg'";
          if ($rspta['status']) {
            while ($reg=$rspta['data']->fetch_object()){
              $baucher_deposito = !empty($reg->baucher)
                ? ( '<center><a target="_blank" href="../dist/docs/pago_obrero/baucher_deposito/'.$reg->baucher.'"><i class="far fa-file-pdf fa-2x text-success"></i></a></center>')
                : ('<center><span class="text-center"> <i class="far fa-times-circle fa-2x text-danger"></i></span></center>');

              $recibos_x_honorarios = !empty($reg->recibos_x_honorarios)
              ? ( '<center><a target="_blank" href="../dist/docs/pago_obrero/recibos_x_honorarios/'.$reg->recibos_x_honorarios.'"><i class="far fa-file-pdf fa-2x text-success"></i></a></center>')
              : ('<center><span class="text-center"> <i class="far fa-times-circle fa-2x text-danger"></i></span></center>');
  
              $data[]=array(    
                "0"=>$cont++,
                "1"=>($reg->estado)?'<button class="btn btn-warning btn-sm" onclick="mostrar_pagos_x_q_s('.$reg->idpagos_q_s_obrero .')"><i class="fas fa-pencil-alt"></i></button>'.
                  ' <button class="btn btn-danger btn-sm" onclick="desactivar_pago_x_q_s('.$reg->idpagos_q_s_obrero .')"><i class="far fa-trash-alt"></i></button>':
                  '<button class="btn btn-warning btn-sm" onclick="mostrar_pagos_x_q_s('.$reg->idpagos_q_s_obrero .')"><i class="fa fa-pencil-alt"></i></button>'.
                  ' <button class="btn btn-primary btn-sm" onclick="activar_pago_x_q_s('.$reg->idpagos_q_s_obrero .')"><i class="fa fa-check"></i></button>',           
                "2"=>$reg->fecha_pago	,
                "3"=>$reg->cuenta_deposito	,
                "4"=>$reg->forma_de_pago	,
                "5"=>'S/ '. number_format($reg->monto_deposito, 2, ".", ","),
                "6"=>$baucher_deposito,
                "7"=> $recibos_x_honorarios,
                "8"=>'<textarea cols="30" rows="1" class="textarea_datatable" readonly="">'.$reg->descripcion.'</textarea>',
                "9"=>($reg->estado)?'<span class="text-center badge badge-success">Activado</span>':'<span class="text-center badge badge-danger">Desactivado</span>'
                );
  
                
            }
            $results = array(
              "sEcho"=>1, //Información para el datatables
              "iTotalRecords"=>count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
              "data"=>$data);
            echo json_encode($results, true);
          } else {
            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          }
          
          
        break;

        case 'desactivar_pago_x_q_s':

          $rspta=$pagoobrero->desactivar_pago_q_s( $_POST['idpagos_q_s_obrero'] );

          echo json_encode( $rspta, true);

        break;

        case 'activar_pago_x_q_s':

          $rspta=$pagoobrero->activar_pago_q_s( $_POST['idpagos_q_s_obrero'] );

          echo json_encode( $rspta, true);

        break;

        case 'mostrar_pagos_x_q_s':

          $rspta=$pagoobrero->mostrar_pagos_x_mes($_POST["idpagos_q_s_obrero"]);
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true);

        break;

        case 'listar_tbla_recibo_por_honorario':

          $rspta=$pagoobrero->tabla_recibo_por_honorario($_GET["id_q_s"]);          
          //Vamos a declarar un array
          $data= Array();   $cont=1;
           
          while ($reg = $rspta['data']->fetch_object()) {
            $baucher = empty($reg->baucher) ? ( '<center><i class="far fa-file-pdf fa-2x text-gray-50"></i></center>') : ( '<center><a target="_blank" href="../dist/docs/pago_administrador/baucher_deposito/' . $reg->baucher . '"><i class="far fa-file-pdf fa-2x text-danger" ></i></a></center>');
            $recibos_x_honorarios = empty($reg->recibos_x_honorarios) ? ( '<center><i class="far fa-file-pdf fa-2x text-gray-50"></i></center>') : ( '<center><a target="_blank" href="../dist/docs/pago_administrador/recibos_x_honorarios/' . $reg->recibos_x_honorarios . '"><i class="far fa-file-pdf fa-2x text-danger" ></i></a></center>');
            
            $data[]=array(
              "0"=>$cont++,               
              "1"=> '<textarea cols="30" rows="1" class="textarea_datatable" readonly="">'.$reg->descripcion.'</textarea>',
              "2"=> $reg->fecha_pago,
              "3"=> number_format($reg->monto_deposito, 2, ".", ","),
              "4"=> $reg->tipo_comprobante .' - '. $reg->numero_comprobante,
              "5"=> $baucher,
              "6"=> $recibos_x_honorarios,
            );
          }

          $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
            "data"=>$data
          );

          echo json_encode($results);
        break;

        // :::::::::::::::::::::::::: P A G O S  M U L T P L E S   O B R E R O S ::::::::::::::::::::::::::::::::::::::::::::::

        case 'listarquincenas_botones':

          $nube_idproyecto = $_POST["nube_idproyecto"];

          $rspta=$pagoobrero->listarquincenas_botones($nube_idproyecto);
          
          echo json_encode($rspta, true);	 //Codificar el resultado utilizando json

        break;

        case 'tabla_obreros_pago':

          $rspta=$pagoobrero->tabla_obreros_pago($_POST["id_proyecto"], $_POST["num_quincena"]);
          
          echo json_encode($rspta, true);	 //Codificar el resultado utilizando json

        break;

        case 'tbla_pagos_por_obrero':

          $idresumen_q_s_asistencia = $_GET["idresumen_q_s_asistencia"];

          $rspta=$pagoobrero->listar_tbla_pagos_x_q_s($idresumen_q_s_asistencia);
          //Vamos a declarar un array
          $data= Array();
          $cont = 1;
          $imagen_error = "this.src='../dist/svg/user_default.svg'";
          if ($rspta['status']) {
            while ($reg=$rspta['data']->fetch_object()){
              $baucher_deposito = !empty($reg->baucher)
                ? ( '<center><a target="_blank" href="../dist/docs/pago_obrero/baucher_deposito/'.$reg->baucher.'"><i class="far fa-file-pdf fa-2x text-success"></i></a></center>')
                : ( '<center><span class="text-center"> <i class="far fa-times-circle fa-2x text-danger"></i></span></center>');
              $recibos_x_honorarios =!empty($reg->baucher)
              ? ( '<center><a target="_blank" href="../dist/docs/pago_obrero/recibos_x_honorarios/'.$reg->baucher.'"><i class="far fa-file-pdf fa-2x text-success"></i></a></center>')
              : ( '<center><span class="text-center"> <i class="far fa-times-circle fa-2x text-danger"></i></span></center>');

              $data[]=array(    
                "0"=>$cont++,
                "1"=>($reg->estado)?'<button class="btn btn-warning btn-sm" onclick="mostrar_pagos_x_q_s('.$reg->idpagos_q_s_obrero .')"><i class="fas fa-pencil-alt"></i></button>'.
                  ' <button class="btn btn-danger btn-sm" onclick="desactivar_pago_x_q_s('.$reg->idpagos_q_s_obrero .')"><i class="far fa-trash-alt"></i></button>':
                  '<button class="btn btn-warning btn-sm" onclick="mostrar_pagos_x_q_s('.$reg->idpagos_q_s_obrero .')"><i class="fa fa-pencil-alt"></i></button>'.
                  ' <button class="btn btn-primary btn-sm" onclick="activar_pago_x_q_s('.$reg->idpagos_q_s_obrero .')"><i class="fa fa-check"></i></button>',           
                "2"=>$reg->fecha_pago,
                "3"=>'<p class="m-b-1px"><b>Forma:</b>'.$reg->forma_de_pago.'</p> <p class="m-b-1px"><b>Cta:</b>'.$reg->cuenta_deposito.'</p>',
                "4"=>'S/ '. number_format($reg->monto_deposito, 2, ".", ","),
                "5"=>$baucher_deposito,
                "6"=>$recibos_x_honorarios,
                "7"=>'<textarea cols="30" rows="1" class="textarea_datatable" readonly="">'.$reg->descripcion.'</textarea>',
                );
  
                
            }
            $results = array(
              "sEcho"=>1, //Información para el datatables
              "iTotalRecords"=>count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
              "data"=>$data);
            echo json_encode($results, true);
          } else {
            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          }
          
          
        break;

        // :::::::::::::::::::::::::: R E C I B O S   P O R   H O N O R A R I O ::::::::::::::::::::::::::::::::::::::::::::::

        case 'guardar_y_editar_recibo_x_honorario':
          	
          

          // registramos un nuevo: recibo x honorario
          if (empty($idresumen_q_s_asistencia_rh)){

            $rspta=["status"=> false, "message"=> 'no hay id manolo', "data"=> [], ];            
            echo json_encode( $rspta, true);

          }else {

            

            // editamos un recibo x honorario existente
            $rspta=$pagoobrero->editar_recibo_x_honorario($idresumen_q_s_asistencia_rh, $numero_comprobante_rh, $doc2);            
            echo json_encode( $rspta, true);
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