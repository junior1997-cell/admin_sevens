<?php

	ob_start();

	if (strlen(session_id()) < 1){
		session_start();//Validamos si existe o no la sesión
	}
  
  if (!isset($_SESSION["nombre"])) {

    header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.

  } else {

    //Validamos el acceso solo al usuario logueado y autorizado.
    if ($_SESSION['pago_trabajador'] == 1) {

      require_once "../modelos/Pago_obrero.php";
      require_once "../modelos/Fechas.php";

      $pagoobrero = new PagoObrero();

      // DATA - agregar pago x quincena o semana	
      $idpagos_q_s_obrero 		  = isset($_POST["idpagos_q_s_obrero"])? limpiarCadena($_POST["idpagos_q_s_obrero"]):"";
      $idresumen_q_s_asistencia = isset($_POST["idresumen_q_s_asistencia"])? limpiarCadena($_POST["idresumen_q_s_asistencia"]):"";
      $forma_pago	      = isset($_POST["forma_pago"])? limpiarCadena($_POST["forma_pago"]):"";
      $cuenta_deposito  = isset($_POST['cuenta_deposito'])? $_POST['cuenta_deposito']:"";
      $monto 		        = isset($_POST['monto'])? $_POST['monto']:"";
      $descripcion 		  = isset($_POST['descripcion'])? $_POST['descripcion']:"";
      $doc_old_1 		    = isset($_POST['doc_old_1'])? $_POST['doc_old_1']:"";
      $doc1 		        = isset($_POST['doc1'])? $_POST['doc1']:"";

      // DATA - recibos por honorarios
      $idresumen_q_s_asistencia_rh		= isset($_POST["idresumen_q_s_asistencia_rh"])? limpiarCadena($_POST["idresumen_q_s_asistencia_rh"]):"";
      $doc2 	          = isset($_POST['doc2'])? $_POST['doc2']:"";

      switch ($_GET["op"]){

        case 'guardar_y_editar_pagos_x_q_s':
          	
          //*DOC 1*//
          if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {

            $flat_doc1 = false;  $doc1 = $_POST["doc_old_1"];

          } else {

            $flat_doc1 = true;  $ext_doc1 = explode(".", $_FILES["doc1"]["name"]);            
              
            $doc1 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext_doc1);

            move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/pago_obrero/baucher_deposito/" . $doc1);
            
          }	

          // registramos un nuevo: pago x mes
          if (empty($idpagos_q_s_obrero)){

            $rspta=$pagoobrero->insertar_pagos_x_q_s( $idresumen_q_s_asistencia, $forma_pago, $cuenta_deposito, $monto, $descripcion, $doc1);
            
            echo $rspta ? "ok" : "No se pudo registrar el Depósito";

          }else {

            // validamos si existe el "baucher" para eliminarlo
            if ($flat_doc1 == true) {

              $datos_f1 = $pagoobrero->obtenerDocs($idpagos_q_s_obrero);

              $doc1_ant = $datos_f1->fetch_object()->baucher;

              if ($doc1_ant != "") {

                unlink("../dist/docs/pago_obrero/baucher_deposito/" . $doc1_ant);
              }
            }

            // editamos un pago x mes existente
            $rspta=$pagoobrero->editar_pagos_x_q_s( $idpagos_q_s_obrero, $idresumen_q_s_asistencia, $forma_pago, $cuenta_deposito, $monto, $descripcion, $doc1);
            
            echo $rspta ? "ok" : "No se pudo Actualizar el Depósito";
          }

        break;   
        
        case 'guardar_y_editar_recibo_x_honorario':
          	
          //*DOC 2*//
          if (!file_exists($_FILES['doc2']['tmp_name']) || !is_uploaded_file($_FILES['doc2']['tmp_name'])) {

            $flat_doc2 = false;

            $doc2      = $_POST["doc_old_2"];

          } else {

            $flat_doc2 = true;

            $ext_doc2  = explode(".", $_FILES["doc2"]["name"]);
              
            $doc2 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext_doc2);

            move_uploaded_file($_FILES["doc2"]["tmp_name"], "../dist/docs/pago_obrero/recibos_x_honorarios/" . $doc2);
            
          }	

          // registramos un nuevo: recibo x honorario
          if (empty($idresumen_q_s_asistencia_rh)){

            $rspta="0";
            
            echo $rspta ? "ok" : "No se pudieron registrar el Recibo por Honorario";

          }else {

            // eliminados si existe el "doc en la BD"
            if ($flat_doc2 == true) {

              $datos_f2 = $pagoobrero->obtenerDocs2($idresumen_q_s_asistencia_rh);

              $doc2_ant = $datos_f2->fetch_object()->recibos_x_honorarios;

              if ( !empty($doc2_ant) ) {

                unlink("../dist/docs/pago_obrero/recibos_x_honorarios/" . $doc2_ant);
              }
            }

            // editamos un recibo x honorario existente
            $rspta=$pagoobrero->editar_recibo_x_honorario($idresumen_q_s_asistencia_rh, $doc2);
            
            echo $rspta ? "ok" : "Recibo por Honorario no se pudo actualizar";
          }

        break;

        case 'listar_tbla_principal':
          $nube_idproyecto = $_GET["nube_idproyecto"];         

          $rspta=$pagoobrero->listar_tbla_principal($nube_idproyecto);
          //Vamos a declarar un array
          $data= Array();
          $cont=1;
          $imagen_error = "this.src='../dist/svg/user_default.svg'";

          $Object = new DateTime();
          $Object->setTimezone(new DateTimeZone('America/Lima'));
          $date_actual = $Object->format("d-m-Y");           

          foreach ( json_decode($rspta, true) as $key => $value) {
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
                <img class="img-circle" src="../dist/img/usuarios/'. $value['imagen_perfil'] .'" alt="User Image" onerror="'.$imagen_error.'">
                <span class="username"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'. $value['nombres_trabajador'] .'</p></span>
                <span class="description">'. $value['tipo_documento'] .': '. $value['numero_documento'] .' </span>
                <span class="description">'. $value['nombre_tipo'].' / '.$value['nombre_cargo'] .' </span>
              </div>',
              
              "2"=>$value['total_hn'].' / '. $value['total_he'],
              "3"=>$value['sabatical'],              
              "4"=>'S/. '.  number_format($value['sueldo_mensual'], 2, '.', ','),               
              "5"=>'S/. '.  number_format($value['pago_quincenal'], 2, '.', ','),
              "6"=>'<div class="justify-content-between "> 
                <button class="btn btn-info btn-sm " onclick="detalle_q_s_trabajador( '.$value['idtrabajador_por_proyecto'] .', \'' . $value['fecha_pago_obrero'] .  '\', \'' . $value['nombres_trabajador'] . '\', \'' .  $value['cuenta_bancaria'] . '\' )">
                  <i class="far fa-eye"></i> Detalle
                </button> 
                <button style="font-size: 14px;" class="btn '.$btn_depositos.' btn-sm">S/. '.number_format($value['total_deposito'], 2, '.', ',').'</button>
              </div>',
              "7"=>'S./ ' . number_format($saldo, 2, '.', ','),
              "8"=>$value['sum_estado_envio_contador'], 
              "9"=>format_d_m_a($value['fecha_inicio']),
              "10"=> $date_actual,
              "11"=>format_d_m_a($value['fecha_fin']),
              "12"=>'<a href="tel:+51'.quitar_guion($value['telefono']).'" data-toggle="tooltip" data-original-title="Llamar al trabajador.">'. $value['telefono'] . '</a>'
            );
          }
          $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
            "data"=>$data);
          echo json_encode($results);
        break;

        case 'mostrar_deposito_total_tbla_principal':
          $rspta=$pagoobrero->mostrar_total_tbla_principal($_POST["id_proyecto"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);
        break;

        case 'listar_tbla_q_s':

          $rspta=$pagoobrero->listar_tbla_q_s( $_POST["id_trabajdor_x_proyecto"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);

        break;

        case 'listar_tbla_pagos_x_q_s':

          $idresumen_q_s_asistencia = $_GET["idresumen_q_s_asistencia"];

          $rspta=$pagoobrero->listar_tbla_pagos_x_q_s($idresumen_q_s_asistencia);
          //Vamos a declarar un array
          $data= Array();
          $cont = 1;
          $imagen_error = "this.src='../dist/svg/user_default.svg'";
          
          while ($reg=$rspta->fetch_object()){
            !empty($reg->baucher)
              ? ($baucher_deposito = '<center><a target="_blank" href="../dist/docs/pago_obrero/baucher_deposito/'.$reg->baucher.'"><i class="far fa-file-pdf fa-2x text-success"></i></a></center>')
              : ($baucher_deposito = '<center><span class="text-center"> <i class="far fa-times-circle fa-2x text-danger"></i></span></center>');

            $data[]=array(    
              "0"=>$cont++,
              "1"=>($reg->estado)?'<button class="btn btn-warning btn-sm" onclick="mostrar_pagos_x_q_s('.$reg->idpagos_q_s_obrero .')"><i class="fas fa-pencil-alt"></i></button>'.
                ' <button class="btn btn-danger btn-sm" onclick="desactivar_pago_x_q_s('.$reg->idpagos_q_s_obrero .')"><i class="far fa-trash-alt"></i></button>':
                '<button class="btn btn-warning btn-sm" onclick="mostrar_pagos_x_q_s('.$reg->idpagos_q_s_obrero .')"><i class="fa fa-pencil-alt"></i></button>'.
                ' <button class="btn btn-primary btn-sm" onclick="activar_pago_x_q_s('.$reg->idpagos_q_s_obrero .')"><i class="fa fa-check"></i></button>',           
              "2"=>$reg->cuenta_deposito	,
              "3"=>$reg->forma_de_pago	,
              "4"=>'S/. '. number_format($reg->monto_deposito, 2, ".", ","),
              "5"=>$baucher_deposito,
              "6"=>'<textarea cols="30" rows="1" class="text_area_clss" readonly="">'.$reg->descripcion.'</textarea>',
              "7"=>($reg->estado)?'<span class="text-center badge badge-success">Activado</span>':'<span class="text-center badge badge-danger">Desactivado</span>'
              );

              
          }
          $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
            "data"=>$data);
          echo json_encode($results);
        break;

        case 'mostrar_pagos_x_q_s':

          $rspta=$pagoobrero->mostrar_pagos_x_mes($_POST["idpagos_q_s_obrero"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);

        break;

        case 'desactivar_pago_x_q_s':

          $rspta=$pagoobrero->desactivar_pago_q_s( $_POST['idpagos_q_s_obrero'] );

          echo $rspta ? "ok" : "NO se puede anular";

        break;

        case 'activar_pago_x_q_s':

          $rspta=$pagoobrero->activar_pago_q_s( $_POST['idpagos_q_s_obrero'] );

          echo $rspta ? "ok" : "NO se puede ReActivar";

        break;

        case 'select2Trabajador': 

          $rspta = $pagoobrero->select2_trabajador();
      
          while ($reg = $rspta->fetch_object())  {

            echo '<option value=' . $reg->id . '>' . $reg->nombre .' - '. $reg->numero_documento . '</option>';
          }

        break;
        
      }

    } else {

      require 'noacceso.php';
    }
  }

  function quitar_guion($numero){ return str_replace("-", "", $numero); }

  function nombre_dia_mes_anio( $fecha_entrada ) {

    $fecha_parse = new FechaEs($fecha_entrada);
    $dia = $fecha_parse->getDDDD().PHP_EOL;
    $mun_dia = $fecha_parse->getdd().PHP_EOL;
    $mes = $fecha_parse->getMMMM().PHP_EOL;
    $anio = $fecha_parse->getYYYY().PHP_EOL;
    $fecha_nombre_completo = "$dia, <br> $mun_dia de <b>$mes</b>  del $anio";

    return $fecha_nombre_completo;
  }

  function nombre_mes( $fecha_entrada ) {

    $fecha_parse = new FechaEs($fecha_entrada);
    
    $mes_nombre = $fecha_parse->getMMMM().PHP_EOL;

    return $mes_nombre;
  }

  // convierte de una fecha(dd-mm-aa): 23-12-2021 a una fecha(aa-mm-dd): 2021-12-23
  function format_a_m_d( $fecha ) {

    if (!empty($fecha)) {

      $fecha_expl = explode("-", $fecha);

      $fecha_convert =  $fecha_expl[0]."-".$fecha_expl[1]."-".$fecha_expl[2];

    }else{

      $fecha_convert = "";
    }   

    return $fecha_convert;
  }

  // convierte de una fecha(aa-mm-dd): 2021-12-23 a una fecha(dd-mm-aa): 23-12-2021
  function format_d_m_a( $fecha ) {

    if (!empty($fecha)) {

      $fecha_expl = explode("-", $fecha);

      $fecha_convert =  $fecha_expl[2]."-".$fecha_expl[1]."-".$fecha_expl[0];

    }else{

      $fecha_convert = "";
    }   

    return $fecha_convert;
  }

	ob_end_flush();

?>