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

            $rspta=$pagoobrero->insertar($idproyecto,$trabajador, $tipo_trabajador, $cargo, $desempenio, $sueldo_mensual, $sueldo_diario, $sueldo_hora);
            
            echo $rspta ? "ok" : "No se pudieron registrar todos los datos del usuario";

          }else {
            // editamos un trabajador existente
            $rspta=$pagoobrero->editar($idtrabajador_por_proyecto,$trabajador, $tipo_trabajador, $cargo, $desempenio, $sueldo_mensual, $sueldo_diario, $sueldo_hora);
            
            echo $rspta ? "ok" : "Trabador no se pudo actualizar";
          }

        break;

        case 'desactivar':

          $rspta=$pagoobrero->desactivar($idtrabajador_por_proyecto);

          echo $rspta ? "Usuario Desactivado" : "Usuario no se puede desactivar";	

        break;

        case 'activar':

          $rspta=$pagoobrero->activar($idtrabajador_por_proyecto);

          echo $rspta ? "Usuario activado" : "Usuario no se puede activar";

        break;

        case 'mostrar':

          $rspta=$pagoobrero->mostrar($idtrabajador_por_proyecto);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);

        break;

        case 'listar_tbla_principal':
          $nube_idproyecto = $_GET["nube_idproyecto"];         

          $rspta=$pagoobrero->listar_tbla_principal($nube_idproyecto);
          //Vamos a declarar un array
          $data= Array();

          $imagen_error = "this.src='../dist/svg/user_default.svg'";
          
          while ($reg=$rspta->fetch_object()){
            $data[]=array(
              "0"=>'<div class="user-block">
                <img class="img-circle" src="../dist/img/usuarios/'. $reg->imagen_perfil .'" alt="User Image" onerror="'.$imagen_error.'">
                <span class="username"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'. $reg->nombres_trabajador .'</p></span>
                <span class="description">'. $reg->tipo_documento .': '. $reg->numero_documento .' </span>
                <span class="description">'. $reg->nombre_tipo.' / '.$reg->nombre_cargo .' </span>
              </div>',
              "1"=>nombre_dia_mes_anio($reg->fecha_inicio),
              "2"=>'hoy',
              "3"=>nombre_dia_mes_anio($reg->fecha_fin),
              "4"=>$reg->total_hn.' / '. $reg->total_he,
              "5"=>$reg->sabatical,              
              "6"=>'S/. '.  number_format($reg->sueldo_mensual, 2, '.', ','), 
              "7"=>$reg->sum_estado_envio_contador, 
              "8"=>'S/. '.  number_format($reg->pago_quincenal, 2, '.', ','),
              "9"=>'<div class="justify-content-between "> 
                <button class="btn btn-info btn-sm " onclick="detalle_q_s_trabajador( )">
                  <i class="far fa-eye"></i> Detalle
                </button> 
                <button style="font-size: 14px;" class="btn btn-danger btn-xs">S/. 0.00</button>
              </div>',
              "10"=>'S./ 0.00',
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

	ob_end_flush();

?>