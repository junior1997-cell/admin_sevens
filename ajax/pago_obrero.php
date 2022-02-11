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
      $idproyecto		  = isset($_POST["idproyecto"])? limpiarCadena($_POST["idproyecto"]):"";
      $idtrabajador_por_proyecto		= isset($_POST["idtrabajador_por_proyecto"])? limpiarCadena($_POST["idtrabajador_por_proyecto"]):"";
      $trabajador		  = isset($_POST["trabajador"])? limpiarCadena($_POST["trabajador"]):"";

      // DATA - recibos por honorarios
      $idresumen_q_s_asistencia_rh		= isset($_POST["idresumen_q_s_asistencia_rh"])? limpiarCadena($_POST["idresumen_q_s_asistencia_rh"]):"";
      $doc2 	          = isset($_POST['doc2'])? $_POST['doc2']:"";

      switch ($_GET["op"]){

        case 'guardaryeditar':
          	
          // registramos un nuevo trabajador
          if (empty($idtrabajador_por_proyecto)){

            $rspta=$pagoobrero->insertar($idproyecto,$trabajador, $tipo_trabajador, $cargo, $desempenio, $sueldo_mensual, $sueldo_diario, $sueldo_hora);
            
            echo $rspta ? "ok" : "No se pudieron registrar todos los datos del usuario";

          }else {
            // editamos un trabajador existente
            $rspta=$pagoobrero->editar($idtrabajador_por_proyecto,$trabajador, $tipo_trabajador, $cargo, $desempenio, $sueldo_mensual, $sueldo_diario, $sueldo_hora);
            
            echo $rspta ? "ok" : "Trabador no se pudo actualizar";
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

            move_uploaded_file($_FILES["doc2"]["tmp_name"], "../dist/pago_obrero/recibos_x_honorarios/" . $doc2);
            
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

                unlink("../dist/pago_obrero/recibos_x_honorarios/" . $doc2_ant);
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

          $imagen_error = "this.src='../dist/svg/user_default.svg'";

          $Object = new DateTime();
          $Object->setTimezone(new DateTimeZone('America/Lima'));
          $date_actual = $Object->format("d-m-Y");
          
          while ($reg=$rspta->fetch_object()){
            $data[]=array(
              "0"=>'<div class="user-block">
                <img class="img-circle" src="../dist/img/usuarios/'. $reg->imagen_perfil .'" alt="User Image" onerror="'.$imagen_error.'">
                <span class="username"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'. $reg->nombres_trabajador .'</p></span>
                <span class="description">'. $reg->tipo_documento .': '. $reg->numero_documento .' </span>
                <span class="description">'. $reg->nombre_tipo.' / '.$reg->nombre_cargo .' </span>
              </div>',
              
              "1"=>$reg->total_hn.' / '. $reg->total_he,
              "2"=>$reg->sabatical,              
              "3"=>'S/. '.  number_format($reg->sueldo_mensual, 2, '.', ','), 
              "4"=>$reg->sum_estado_envio_contador, 
              "5"=>'S/. '.  number_format($reg->pago_quincenal, 2, '.', ','),
              "6"=>'<div class="justify-content-between "> 
                <button class="btn btn-info btn-sm " onclick="detalle_q_s_trabajador( '.$reg->idtrabajador_por_proyecto.', \'' . $reg->fecha_pago_obrero .  '\', \'' . $reg->nombres_trabajador. '\' )">
                  <i class="far fa-eye"></i> Detalle
                </button> 
                <button style="font-size: 14px;" class="btn btn-danger btn-xs">S/. 0.00</button>
              </div>',
              "7"=>'S./ 0.00',
              "8"=>format_d_m_a($reg->fecha_inicio),
              "9"=> $date_actual,
              "10"=>format_d_m_a($reg->fecha_fin),
              "11"=>'<a href="tel:+51'.quitar_guion($reg->telefono).'" data-toggle="tooltip" data-original-title="Llamar al trabajador.">'. $reg->telefono . '</a>'
            );
          }
          $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
            "data"=>$data);
          echo json_encode($results);
        break;

        case 'mostrar_q_s':

          $rspta=$pagoobrero->mostrar_q_s( $_POST["id_trabajdor_x_proyecto"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);

        break;

        case 'desactivar':

          $rspta=$pagoobrero->desactivar($idtrabajador_por_proyecto);

          echo $rspta ? "Usuario Desactivado" : "Usuario no se puede desactivar";	

        break;

        case 'activar':

          $rspta=$pagoobrero->activar($idtrabajador_por_proyecto);

          echo $rspta ? "Usuario activado" : "Usuario no se puede activar";

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