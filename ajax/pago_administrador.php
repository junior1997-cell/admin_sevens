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

      require_once "../modelos/Pago_administrador.php";
      require_once "../modelos/Fechas.php";

      $pago_administrador = new PagoAdministrador();

      // setlocale(LC_TIME, "spanish");

      // DATA - agregar pago x mes
      $idpagos_x_mes_administrador = isset($_POST["idpagos_x_mes_administrador"])? limpiarCadena($_POST["idpagos_x_mes_administrador"]):"";

      $idfechas_mes_pagos_administrador_pxm = isset($_POST["idfechas_mes_pagos_administrador_pxm"])? limpiarCadena($_POST["idfechas_mes_pagos_administrador_pxm"]):"";
      $id_tabajador_x_proyecto_pxm	        = isset($_POST["id_tabajador_x_proyecto_pxm"])? limpiarCadena($_POST["id_tabajador_x_proyecto_pxm"]):"";      
      $fecha_inicial_pxm	= isset($_POST["fecha_inicial_pxm"])? limpiarCadena($_POST["fecha_inicial_pxm"]):"";      
      $fecha_final_pxm	  = isset($_POST["fecha_final_pxm"])? limpiarCadena($_POST["fecha_final_pxm"]):"";
      $mes_nombre_pxm     = isset($_POST['mes_nombre_pxm'])? $_POST['mes_nombre_pxm']:"";
      $dias_mes_pxm 		  = isset($_POST['dias_mes_pxm'])? $_POST['dias_mes_pxm']:"";
      $dias_regular_pxm   = isset($_POST['dias_regular_pxm'])? $_POST['dias_regular_pxm']:"";
      $sueldo_mensual_pxm = isset($_POST['sueldo_mensual_pxm'])? $_POST['sueldo_mensual_pxm']:"";
      $monto_x_mes_pxm 	  = isset($_POST['monto_x_mes_pxm'])? $_POST['monto_x_mes_pxm']:"";
      
      $forma_pago	= isset($_POST["forma_pago"])? limpiarCadena($_POST["forma_pago"]):"";
      $cuenta_deposito = isset($_POST['cuenta_deposito'])? $_POST['cuenta_deposito']:"";
      $monto 		= isset($_POST['monto'])? $_POST['monto']:"";
      $descripcion 		= isset($_POST['descripcion'])? $_POST['descripcion']:"";
      $doc_old_1 		= isset($_POST['doc_old_1'])? $_POST['doc_old_1']:"";
      $doc1 		= isset($_POST['doc1'])? $_POST['doc1']:"";
      
      // DATA - recibos por honorarios
      $idfechas_mes_pagos_administrador_rh  = isset($_POST["idfechas_mes_pagos_administrador_rh"])? limpiarCadena($_POST["idfechas_mes_pagos_administrador_rh"]):"";
      $id_tabajador_x_proyecto_rh	          = isset($_POST["id_tabajador_x_proyecto_rh"])? limpiarCadena($_POST["id_tabajador_x_proyecto_rh"]):"";      
      $fecha_inicial_rh	= isset($_POST["fecha_inicial_rh"])? limpiarCadena($_POST["fecha_inicial_rh"]):"";      
      $fecha_final_rh	  = isset($_POST["fecha_final_rh"])? limpiarCadena($_POST["fecha_final_rh"]):"";
      $mes_nombre_rh    = isset($_POST['mes_nombre_rh'])? $_POST['mes_nombre_rh']:"";
      $dias_mes_rh 		  = isset($_POST['dias_mes_rh'])? $_POST['dias_mes_rh']:"";
      $dias_regular_rh  = isset($_POST['dias_regular_rh'])? $_POST['dias_regular_rh']:"";
      $sueldo_mensual_rh= isset($_POST['sueldo_mensual_rh'])? $_POST['sueldo_mensual_rh']:"";
      $monto_x_mes_rh 	= isset($_POST['monto_x_mes_rh'])? $_POST['monto_x_mes_rh']:"";
      $doc_old_2        = isset($_POST['doc_old_2'])? $_POST['doc_old_2']:"";
      $doc2 	          = isset($_POST['doc2'])? $_POST['doc2']:"";

      switch ($_GET["op"]){

        case 'guardar_y_editar_pagos_x_mes':
          
          //*DOC 1*//
          if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {

            $flat_doc1 = false;  $doc1 = $_POST["doc_old_1"];

          } else {

            $flat_doc1 = true;  $ext_doc1 = explode(".", $_FILES["doc1"]["name"]);            
              
            $doc1 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext_doc1);

            move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/pago_administrador/baucher_deposito/" . $doc1);
            
          }	

          // registramos un nuevo: pago x mes
          if (empty($idpagos_x_mes_administrador)){

            $rspta=$pago_administrador->insertar_pagos_x_mes( $idfechas_mes_pagos_administrador_pxm, $id_tabajador_x_proyecto_pxm, $fecha_inicial_pxm, $fecha_final_pxm, $mes_nombre_pxm, $dias_mes_pxm, 
            $dias_regular_pxm, $sueldo_mensual_pxm, $monto_x_mes_pxm, $forma_pago, $cuenta_deposito, $monto, $descripcion, $doc1);
            
            echo $rspta ;

          }else {

            // validamos si existe el "baucher" para eliminarlo
            if ($flat_doc1 == true) {

              $datos_f1 = $pago_administrador->obtenerDocs($idfechas_mes_pagos_administrador_pxm);

              $doc1_ant = $datos_f1->fetch_object()->baucher;

              if ($doc1_ant != "") {

                unlink("../dist/docs/pago_administrador/baucher_deposito/" . $doc1_ant);
              }
            }

            // editamos un pago x mes existente
            $rspta=$pago_administrador->editar_pagos_x_mes($idpagos_x_mes_administrador, $idfechas_mes_pagos_administrador_pxm, $id_tabajador_x_proyecto_pxm, $fecha_inicial_pxm, $fecha_final_pxm, $mes_nombre_pxm, $dias_mes_pxm, 
            $dias_regular_pxm, $sueldo_mensual_pxm, $monto_x_mes_pxm, $forma_pago, $cuenta_deposito, $monto, $descripcion, $doc1);
            
            echo $rspta;
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

            move_uploaded_file($_FILES["doc2"]["tmp_name"], "../dist/docs/pago_administrador/recibos_x_honorarios/" . $doc2);
            
          }	

          // registramos un nuevo: recibo x honorario
          if (empty($idfechas_mes_pagos_administrador_rh)){

            $rspta=$pago_administrador->insertar_recibo_x_honorario($id_tabajador_x_proyecto_rh, $fecha_inicial_rh, $fecha_final_rh, $mes_nombre_rh, $dias_mes_rh, $dias_regular_rh, $sueldo_mensual_rh, $monto_x_mes_rh, $doc2);
            
            echo $rspta ? "ok" : "No se pudieron registrar el Recibo por Honorario";

          }else {

            if ($flat_doc2 == true) {

              $datos_f2 = $pago_administrador->obtenerDocs2($idfechas_mes_pagos_administrador_rh);

              $doc2_ant = $datos_f2->fetch_object()->recibos_x_honorarios;

              if ($doc2_ant != "") {

                unlink("../dist/docs/pago_administrador/recibos_x_honorarios/" . $doc2_ant);
              }
            }

            // editamos un recibo x honorario existente
            $rspta=$pago_administrador->editar_recibo_x_honorario($idfechas_mes_pagos_administrador_rh, $id_tabajador_x_proyecto_rh, $fecha_inicial_rh, $fecha_final_rh, $mes_nombre_rh, $dias_mes_rh, $dias_regular_rh, $sueldo_mensual_rh, $monto_x_mes_rh, $doc2);
            
            echo $rspta ? "ok" : "Recibo por Honorario no se pudo actualizar";
          }

        break;

        case 'listar_tbla_principal':
          $nube_idproyecto = $_GET["nube_idproyecto"]; 
          // $nube_idproyecto = 1;          

          $rspta=$pago_administrador->listar_tbla_principal($nube_idproyecto);
          $cont=1;
          // echo $rspta;

          //Vamos a declarar un array
          $data= Array();

          $imagen_error = "this.src='../dist/svg/user_default.svg'";

          $Object = new DateTime();
          $Object->setTimezone(new DateTimeZone('America/Lima'));
          $date_actual = $Object->format("d-m-Y"); 

          $count_dia = ""; $fecha_inicio = ""; $fecha_fin = "";         
           
          foreach (json_decode($rspta, true) as $key => $value) {

            $deshabilitado = ""; $btn_depositos = ""; $ultimo_mes_pago = ""; $siguiente_mes_pago = ""; 
            $pago_acumulado_hasta_hoy = 0; $pago_total = 0;

            // validamos: "FECHA INICIO", "CANTIDAD DIAS HASTA HOY", 
            if (validar_fecha_espanol(format_d_m_a($value['fecha_inicio']))) {
              $count_dia = cantidad_dias_trabajado($value['fecha_inicio'], $value['fecha_fin']); 
              $fecha_inicio = format_d_m_a($value['fecha_inicio']);
              $fecha_inicio_nombre = format_d_m_a($value['fecha_inicio']);
            } else {
              $count_dia = "-"; $fecha_inicio = "- - -"; $fecha_inicio_nombre = "- - -";
            }

            // validamos: "FECHA FIN"
            if ( validar_fecha_espanol(format_d_m_a($value['fecha_fin'])) ) {
              $fecha_fin = format_d_m_a($value['fecha_fin']);
              $fecha_fin_nombre = format_d_m_a($value['fecha_fin']);
            } else {
              $fecha_fin = "- - -"; $fecha_fin_nombre = "- - -";
            }

            // validamos: "ULTIMO PAGO", "SIGUIENTE PAGO", "BOTONES DE DETALLE"
            if (validar_fecha_espanol(format_d_m_a($value['fecha_fin'])) == false || validar_fecha_espanol(format_d_m_a($value['fecha_inicio'])) == false) {
              
              $deshabilitado = "disabled";            

            } else {

              $ultimo_mes_pago = calcular_ultimo_pago($value['fecha_inicio'], $value['fecha_fin']);

              $siguiente_mes_pago = calcular_siguiente_pago($value['fecha_inicio'], $value['fecha_fin']);

              $pagos_por_trabajador = calcular_pagos_x_trabajdor($value['fecha_inicio'], $value['fecha_fin'], $value['sueldo_mensual']);

              $pago_acumulado_hasta_hoy = $pagos_por_trabajador['monto_hasta_hoy'];
              $pago_total =  $pagos_por_trabajador['monto_total'];

              $deshabilitado = "";
            }
            
            // Pintamos el bonton depositos segun las cantidades            
            if ( floatval($value['cantidad_deposito']) == 0) {
              $btn_depositos = "btn-danger";
            } else {
              if ( floatval($value['cantidad_deposito']) > 0) {
                $btn_depositos = "btn-warning";
              } else {
                # code...
              }              
            }
            
            $data[]=array(
              "0"=>$cont++,               
              "1"=>'<div class="user-block">
                <img class="img-circle" src="../dist/img/usuarios/'. $value['imagen_perfil'] .'" alt="User Image" onerror="'.$imagen_error.'">
                <span class="username"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'. $value['nombres'] .'</p></span>
                <span class="description">'. $value['tipo_documento'] .': '. $value['numero_documento'] .' </span>
                <span class="description">'. $value['tipo'].' / '.$value['cargo'] .' </span>
              </div>',
              "2"=> $fecha_inicio_nombre,
              "3"=>$date_actual,
              "4"=>$fecha_fin_nombre,
              "5"=>$count_dia,
              
              "6"=>'S/. ' . number_format($value['sueldo_mensual'], 2, '.', ',') ,
              "7" =>'S/. ' . number_format( $pago_total , 2, '.', ',') ,
              "8"=>'S/. '. number_format($pago_acumulado_hasta_hoy, 2, '.', ',') ,
              "9" =>'<div class="justify-content-between "> 
                <button class="btn btn-info btn-sm" '. $deshabilitado . ' onclick="detalle_fechas_mes_trabajador('.$value['idtrabajador_por_proyecto'].', \'' . $value['nombres'] . '\', \'' . $fecha_inicio. '\', \'' . $date_actual. '\', \'' . $fecha_fin .'\', \''.$value['sueldo_mensual'] .'\', \''. $value['cuenta_bancaria'] .'\', \''. $count_dia .'\')">
                  <i class="far fa-eye"></i> Detalle
                </button> 
                <button style="font-size: 14px;" class="btn '.$btn_depositos.' btn-xs">S/. '.number_format($value['cantidad_deposito'], 2, '.', ',').'</button>
              </div>',
              "10"=>'S/. ' . number_format($pago_acumulado_hasta_hoy - floatval($value['cantidad_deposito']), 2, '.', ',') ,
              "11"=>$ultimo_mes_pago,
              "12"=>$siguiente_mes_pago,
              "13"=>'<a href="tel:+51'.quitar_guion($value['telefono']).'" data-toggle="tooltip" data-original-title="Llamar al trabajador.">'. $value['telefono'] . '</a>'
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

        case 'mostrar_total_tbla_principal':

          $rspta=$pago_administrador->mostrar_total_tbla_principal($_POST["nube_idproyecto"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);

        break;

        case 'mostrar_fechas_mes':

          $rspta=$pago_administrador->mostrar_fechas_mes($_POST["id_tabajador_x_proyecto"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);

        break;
        
        case 'listar_tbla_pagos_x_mes':
          $idfechas_mes_pagos = $_GET["idfechas_mes_pagos"];

          $rspta=$pago_administrador->listar_pagos_x_mes($idfechas_mes_pagos);
          //Vamos a declarar un array
          $data= Array();
          $cont=1;
          $imagen_error = "this.src='../dist/svg/user_default.svg'";
          
          while ($reg=$rspta->fetch_object()){
            !empty($reg->baucher)
              ? ($baucher_deposito = '<center><a target="_blank" href="../dist/docs/pago_administrador/baucher_deposito/'.$reg->baucher.'"><i class="far fa-file-pdf fa-2x text-success"></i></a></center>')
              : ($baucher_deposito = '<center><span class="text-center"> <i class="far fa-times-circle fa-2x text-danger"></i></span></center>');

            $data[]=array(    
              "0"=>$cont++,
              "1"=>($reg->estado)?'<button class="btn btn-warning btn-sm" onclick="mostrar_pagos_x_mes('.$reg->idpagos_x_mes_administrador .')"><i class="fas fa-pencil-alt"></i></button>'.
                ' <button class="btn btn-danger btn-sm" onclick="desactivar_pago_x_mes('.$reg->idpagos_x_mes_administrador .')"><i class="far fa-trash-alt"></i></button>':
                '<button class="btn btn-warning btn-sm" onclick="mostrar_pagos_x_mes('.$reg->idpagos_x_mes_administrador .')"><i class="fa fa-pencil-alt"></i></button>'.
                ' <button class="btn btn-primary btn-sm" onclick="activar_pago_x_mes('.$reg->idpagos_x_mes_administrador .')"><i class="fa fa-check"></i></button>',           
              "2"=>$reg->cuenta_deposito	,
              "3"=>$reg->forma_de_pago	,
              "4"=>'S/. '. number_format($reg->monto, 2, ".", ","),
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

        case 'mostrar_pagos_x_mes':

          $rspta=$pago_administrador->mostrar_pagos_x_mes($_POST["idpagos_x_mes_administrador"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);

        break;

        case 'desactivar_pago_x_mes':
          $rspta=$pago_administrador->desactivar_pago_x_mes($_POST["idpagos_x_mes_administrador"]);
          echo $rspta ? "ok" : "NO se puede anular";
        break;
      
        case 'activar_pago_x_mes':
          $rspta=$pago_administrador->activar_pago_x_mes($_POST["idpagos_x_mes_administrador"]);
          echo $rspta ? "ok" : "NO se puede ReActivar";
        break;        
      }

    } else {

      require 'noacceso.php';
    }
  }

  function quitar_guion($numero){ return str_replace("-", "", $numero); }

  function cantidad_dias_trabajado($fecha_inicio, $fecha_fin){

    // ontenemos la fecha actual segun la zona horaria - "America/Lima"
    $Object_fecha = new DateTime();
    $Object_fecha->setTimezone(new DateTimeZone('America/Lima'));
    $date_actual = $Object_fecha->format("Y-m-d"); 

    $fecha_hoy = strtotime( $date_actual );
    $fecha_1 = strtotime( $fecha_inicio );
    $fecha_2 = strtotime( $fecha_fin );

    $diferencia = "";
    if ($fecha_hoy >= $fecha_2) {
      $datetime1 = date_create($fecha_inicio);
      $datetime2 = date_create($fecha_fin);
      $contador = date_diff($datetime1, $datetime2);
      $differenceFormat = '%a';
      $diferencia = ($contador->format($differenceFormat))+1;
    } else {
      if ($fecha_hoy >= $fecha_1) {
        $datetime1 = date_create($fecha_inicio);
        $datetime2 = date_create($date_actual);
        $contador = date_diff($datetime1, $datetime2);
        $differenceFormat = '%a';
        $diferencia = ($contador->format($differenceFormat))+1;
      } else {
        $diferencia = "En espera...";
      }
    }   
    
    return $diferencia;
    
  }

  function validar_fecha_espanol($fecha){
    $valores = explode('-', $fecha);
    if(count($valores) == 3 && checkdate($valores[1], $valores[0], $valores[2])){
      return true;
      }
    return false;
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

  // calculamos el "ULTIMO PAGO"
  function calcular_ultimo_pago($fecha_inicio, $fecha_fin) {

    // ontenemos la fecha actual segun la zona horaria - "America/Lima"
    $Object_fecha = new DateTime();
    $Object_fecha->setTimezone(new DateTimeZone('America/Lima'));
    $date_actual = $Object_fecha->format("Y-m-d"); 

    $fecha_hoy = strtotime( $date_actual );
    $fecha_1 = strtotime( $fecha_inicio );
    $fecha_2 = strtotime( $fecha_fin );

    $ultimo_pago = "";

    if ($fecha_hoy > $fecha_2) {
      $ultimo_pago = "Terminó";
    } else {
      if ($fecha_hoy >= $fecha_1 && $fecha_hoy <= $fecha_2) {

        $mes_anterior = date("Y-m-d",strtotime( '-1 month' , strtotime ( $date_actual ) ) );      
  
        $ultimo_pago =  date("Y-m-t", strtotime($mes_anterior)) ;
  
        if (strtotime($ultimo_pago) >= $fecha_1 && strtotime($ultimo_pago) <= $fecha_2) {
          $ultimo_pago = format_d_m_a( $ultimo_pago );
        } else {
          $ultimo_pago = "En espera...";
        }      
  
      } else {
        $ultimo_pago = "En espera...";
      }
    }  
    
    return $ultimo_pago ;
  }

  // calcular el "PAGO SIGUIENTE"
  function calcular_siguiente_pago($fecha_inicio, $fecha_fin) {

    // ontenemos la fecha actual segun la zona horaria - "America/Lima"
    $Object_fecha = new DateTime();
    $Object_fecha->setTimezone(new DateTimeZone('America/Lima'));
    $date_actual = $Object_fecha->format("Y-m-d"); 

    $fecha_hoy = strtotime( $date_actual );
    $fecha_1 = strtotime( $fecha_inicio );
    $fecha_2 = strtotime( $fecha_fin );

    $siguiente_pago = "";

    if ($fecha_hoy > $fecha_2) {
      $siguiente_pago = "Terminó";
    } else {
      if ($fecha_hoy >= $fecha_1 && $fecha_hoy <= $fecha_2) {
         
        //  "fecha - pago " despues del primer mes
        // $mes_posterior = date("Y-m-d",strtotime( '+1 month' , strtotime ( $date_actual ) ) );      

        $siguiente_pago =  date("Y-m-t", strtotime($date_actual)) ;
  
        if (strtotime($siguiente_pago) >= $fecha_1 && strtotime($siguiente_pago) <= $fecha_2) {
          $siguiente_pago = format_d_m_a($siguiente_pago);
        } else {
          $siguiente_pago = format_d_m_a($fecha_fin);
        }
  
      } else {
        $siguiente_pago = "En espera...";
      }
    }

    return $siguiente_pago;
  }

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

  function sumar_dias( $cant, $fecha )  {
    
    return date("Y-m-d",strtotime( "$cant days" , strtotime( $fecha ) ) ); 
  }

  function validar_fecha_menor_que($fecha_menor, $fecha_mayor) {
    $fecha_1 = strtotime( $fecha_menor );
    $fecha_2 = strtotime( $fecha_mayor );

    if ($fecha_1 < $fecha_2) {
      return true;
    }
    
    return false;
  }

  function calcular_pagos_x_trabajdor($fecha_inicio, $fecha_fin, $sueldo_mensual) {
    // ontenemos la fecha actual segun la zona horaria - "America/Lima"
    $Object_fecha = new DateTime();
    $Object_fecha->setTimezone(new DateTimeZone('America/Lima'));
    $date_actual = $Object_fecha->format("Y-m-d");

    $estado_fin_bucle = false; $dias_mes = 0; $fecha_i = $fecha_inicio; $fecha_f = $fecha_fin;

    $fechas_array = array();

    while ($estado_fin_bucle == false) {

      $dias_mes = date( 't', strtotime( $fecha_i ) );

      $dias_regular =  (floatval($dias_mes) - floatval( date("d", strtotime( $fecha_i )) )) + 1;

      $fecha_f = sumar_dias(($dias_regular-1), $fecha_i );

      // validamos para terminar el bucle
      if (validar_fecha_menor_que( $fecha_f, $fecha_fin) == false ) {
        $fecha_f = $fecha_fin;
        $dias_regular = date("d", strtotime( $fecha_fin ));
        $estado_fin_bucle = true;
      }

      // asignamos las fechas a un array
      $fechas_array[] = array(
        'fecha_i'=> $fecha_i, 
        'dias_regular'=> $dias_regular, 
        'fecha_f'=> $fecha_f, 
        'mes_nombre'=> nombre_mes($fecha_i),
        'dias_mes'=> floatval( $dias_mes )
      );
      
      $fecha_i = sumar_dias(1, $fecha_f );
    }

    $monto_total = 0; $monto_hasta_hoy = 0;

    // calculamos: "monto total de pagos x mes", "monto total hasta hoy"
    foreach ($fechas_array as $key => $value) {

      $monto_total += ($sueldo_mensual / $value['dias_mes']) * $value['dias_regular'];

      $fecha_hoy = strtotime( $date_actual );
      $fecha_1 = strtotime( $value['fecha_i'] );
      $fecha_2 = strtotime( $value['fecha_f'] );

      if ($fecha_hoy >= $fecha_1 && $fecha_hoy >= $fecha_2) {
        $monto_hasta_hoy += ($sueldo_mensual / $value['dias_mes']) * $value['dias_regular'];
      } else {
        if ($fecha_hoy >= $fecha_1 && $fecha_hoy <= $fecha_2) {
          $dias_trabajo = (floatval( date("d", strtotime( $date_actual )) ) - floatval( date("d", strtotime( $value['fecha_i'] )) )) + 1;
          $monto_hasta_hoy += ($sueldo_mensual / $value['dias_mes']) * $dias_trabajo ;
        }
      }     
    }

    return $enviar = array( 'monto_total' => $monto_total, 'monto_hasta_hoy' => $monto_hasta_hoy);
  }

	ob_end_flush();

?>