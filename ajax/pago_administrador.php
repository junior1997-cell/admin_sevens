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

      $pago_administrador = new PagoAdministrador();

      //$idtrabajador,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$nacimiento,$tipo_trabajador,$desempenio,$c_bancaria,$email,$cargo,$banco,$tutular_cuenta,$sueldo_diario,$sueldo_mensual,$sueldo_hora,$imagen	
      $idproyecto		  = isset($_POST["idproyecto"])? limpiarCadena($_POST["idproyecto"]):"";
      $idtrabajador_por_proyecto		= isset($_POST["idtrabajador_por_proyecto"])? limpiarCadena($_POST["idtrabajador_por_proyecto"]):"";
      $trabajador		  = isset($_POST["trabajador"])? limpiarCadena($_POST["trabajador"]):"";

      $tipo_trabajador= isset($_POST["tipo_trabajador"])? limpiarCadena($_POST["tipo_trabajador"]):"";
      $desempenio	    = isset($_POST["desempenio"])? limpiarCadena($_POST["desempenio"]):"";      
      $cargo			    = isset($_POST["cargo"])? limpiarCadena($_POST["cargo"]):"";
      
      $sueldo_diario	= isset($_POST["sueldo_diario"])? limpiarCadena($_POST["sueldo_diario"]):"";
      $sueldo_mensual = isset($_POST['sueldo_mensual'])? $_POST['sueldo_mensual']:"";
      $sueldo_hora 		= isset($_POST['sueldo_hora'])? $_POST['sueldo_hora']:"";

      switch ($_GET["op"]){

        case 'guardaryeditar':
          	
          // registramos un nuevo trabajador
          if (empty($idtrabajador_por_proyecto)){

            $rspta=$pago_administrador->insertar($idproyecto,$trabajador, $tipo_trabajador, $cargo, $desempenio, $sueldo_mensual, $sueldo_diario, $sueldo_hora);
            
            echo $rspta ? "ok" : "No se pudieron registrar todos los datos del usuario";

          }else {
            // editamos un trabajador existente
            $rspta=$pago_administrador->editar($idtrabajador_por_proyecto,$trabajador, $tipo_trabajador, $cargo, $desempenio, $sueldo_mensual, $sueldo_diario, $sueldo_hora);
            
            echo $rspta ? "ok" : "Trabador no se pudo actualizar";
          }

        break;

        case 'listar_tbla_principal':
          $nube_idproyecto = $_GET["nube_idproyecto"];

          $rspta=$pago_administrador->listar_tbla_principal($nube_idproyecto);
          //Vamos a declarar un array
          $data= Array();

          $imagen_error = "this.src='../dist/svg/user_default.svg'";

          $Object = new DateTime();
          $Object->setTimezone(new DateTimeZone('America/Lima'));
          $date_actual = $Object->format("d-m-Y"); 

          $count_dia = ""; $fecha_inicio = ""; $fecha_fin = "";

          while ($reg=$rspta->fetch_object()){

            // validamos antes de sacar la diferencia
            if (validar_fecha_espanol(format_d_m_a($reg->fecha_inicio))) {
              $count_dia = diferencia_de_fechas($reg->fecha_inicio); 
              $fecha_inicio = format_d_m_a($reg->fecha_inicio);
            } else {
              $count_dia = "-"; $fecha_inicio = "- - -";
            }

            // validamos la fecha antes de imprimir
            validar_fecha_espanol(format_d_m_a($reg->fecha_fin)) ? $fecha_fin = format_d_m_a($reg->fecha_fin) : $fecha_fin = "- - -" ;
            
            $data[]=array(               
              "0"=>'<div class="user-block">
                <img class="img-circle" src="../dist/img/usuarios/'. $reg->imagen_perfil .'" alt="User Image" onerror="'.$imagen_error.'">
                <span class="username"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'. $reg->nombres .'</p></span>
                <span class="description">'. $reg->tipo_documento .': '. $reg->numero_documento .' </span>
                <span class="description">'. $reg->tipo.' / '.$reg->cargo .' </span>
                </div>',
              "1"=>$fecha_inicio,
              "2"=>$date_actual,
              "3"=>$fecha_fin,
              "4"=>$count_dia,
              "5"=>'31-02-2022',
              "6"=>'30-12-2022',
              "7"=>'S/.' . $reg->sueldo_mensual,
              "8"=>'S/. 300.00',
              "9" =>'<div class="text-center"> <button class="btn btn-info btn-sm" onclick="detalle_fechas_mes_trabajador('.$reg->idtrabajador_por_proyecto.', \'' . $reg->nombres . '\', \'' . $fecha_inicio. '\', \'' . $date_actual. '\', \'' . $fecha_fin .'\', \''.$reg->sueldo_mensual.'\')">
              <i class="far fa-eye"></i> Detalle
              </button> 
              <button style="font-size: 14px;" class="btn btn-danger btn-xs">S/. 4,500.00</button></div>',
              "10"=>'S/. 10',
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

        case 'mostrar_fechas_mes':

          $rspta=$pago_administrador->mostrar_fechas_mes($idtrabajador_por_proyecto);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);

        break;
        
        case 'listar_tbla_pagos_x_mes':
          $nube_idproyecto = $_GET["nube_idproyecto"];

          $rspta=$pago_administrador->listar_tbla_principal($nube_idproyecto);
          //Vamos a declarar un array
          $data= Array();

          $imagen_error = "this.src='../dist/svg/user_default.svg'";
          
          while ($reg=$rspta->fetch_object()){
            !empty($reg->estado)
              ? ($baucher_deposito = '<center><a target="_blank" href="../dist/pago_administrador/pago_obrero.pdf"><i class="far fa-file-pdf fa-2x text-success"></i></a></center>')
              : ($baucher_deposito = '<center><span class="text-center"> <i class="far fa-times-circle fa-2x text-danger"></i></span></center>');

            !empty($reg->estado)
              ? ($recibo_x_h = '<center><a target="_blank" href="../dist/pago_administrador/pago_obrero.pdf"><i class="far fa-file-pdf fa-2x text-success"></i></a></center>')
              : ($recibo_x_h = '<center><span class="text-center"> <i class="far fa-times-circle fa-2x text-danger"></i></span></center>');

            $data[]=array(               
              "0"=>'0989-768568756-568',
              "1"=>'efectivo',
              "2"=>'S/. 300',
              "3"=>$baucher_deposito,
              "4"=>$recibo_x_h,
              "5"=>'el pago es por la efciencia del trabajdor',
              "6"=>($reg->estado)?'<span class="text-center badge badge-success">Activado</span>':'<span class="text-center badge badge-danger">Desactivado</span>'
              );
          }
          $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
            "data"=>$data);
          echo json_encode($results);
        break;
        
      }

    } else {

      require 'noacceso.php';
    }
  }

  function quitar_guion($numero){ return str_replace("-", "", $numero); }

  function diferencia_de_fechas($fecha_pasada){
    $Object_fecha = new DateTime();
    $Object_fecha->setTimezone(new DateTimeZone('America/Lima'));
    $date_actual = $Object_fecha->format("Y-m-d"); 

    $fecha_1 = strtotime( format_a_m_d($date_actual) );
    $fecha_2 = strtotime( format_a_m_d($fecha_pasada) );

    $diferencia = "";

    if ($fecha_1 > $fecha_2) {
      $datetime1 = date_create($fecha_pasada);
      $datetime2 = date_create($date_actual);
      $contador = date_diff($datetime1, $datetime2);
      $differenceFormat = '%a';
      $diferencia = $contador->format($differenceFormat);
    } else {
      $diferencia = "En espera...";
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

	ob_end_flush();

?>