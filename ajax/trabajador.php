<?php

	ob_start();

	if (strlen(session_id()) < 1){
		session_start();//Validamos si existe o no la sesión
	}
  
  if (!isset($_SESSION["nombre"])) {

    header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.

  } else {

    //Validamos el acceso solo al usuario logueado y autorizado.
    if ($_SESSION['trabajador'] == 1) {

      require_once "../modelos/trabajador.php";

      $trabajadorproyecto=new Trabajador();

      //$idtrabajador,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$nacimiento,$tipo_trabajador,$desempenio,$c_bancaria,$email,$cargo,$banco,$tutular_cuenta,$sueldo_diario,$sueldo_mensual,$sueldo_hora,$imagen	
      $idproyecto		  = isset($_POST["idproyecto"])? limpiarCadena($_POST["idproyecto"]):"";
      $idtrabajador_por_proyecto		= isset($_POST["idtrabajador_por_proyecto"])? limpiarCadena($_POST["idtrabajador_por_proyecto"]):"";
      $trabajador		  = isset($_POST["trabajador"])? limpiarCadena($_POST["trabajador"]):"";

      //$tipo_trabajador= isset($_POST["tipo_trabajador"])? limpiarCadena($_POST["tipo_trabajador"]):"";
      $desempenio	    = isset($_POST["desempenio"])? limpiarCadena($_POST["desempenio"]):"";      
      $cargo			    = isset($_POST["cargo"])? limpiarCadena($_POST["cargo"]):"";
      
      $sueldo_diario	= isset($_POST["sueldo_diario"])? limpiarCadena($_POST["sueldo_diario"]):"";
      $sueldo_mensual = isset($_POST['sueldo_mensual'])? $_POST['sueldo_mensual']:"";
      $sueldo_hora 		= isset($_POST['sueldo_hora'])? $_POST['sueldo_hora']:"";

      $fecha_inicio 		= isset($_POST['fecha_inicio'])? $_POST['fecha_inicio']:"";
      $fecha_fin 		    = isset($_POST['fecha_fin'])? $_POST['fecha_fin']:""; 
      $cantidad_dias 		= isset($_POST['cantidad_dias'])? $_POST['cantidad_dias']:"";


      switch ($_GET["op"]){

        case 'guardaryeditar':
          	
          // registramos un nuevo trabajador
          if (empty($idtrabajador_por_proyecto)){

            $rspta=$trabajadorproyecto->insertar($idproyecto,$trabajador, $cargo, $desempenio, $sueldo_mensual, $sueldo_diario, $sueldo_hora, format_a_m_d($fecha_inicio), format_a_m_d($fecha_fin), $cantidad_dias);
            
            echo $rspta ? "ok" : "No se pudieron registrar todos los datos del usuario";

          }else {
            // editamos un trabajador existente
            $rspta=$trabajadorproyecto->editar($idtrabajador_por_proyecto,$trabajador, $cargo, $desempenio, $sueldo_mensual, $sueldo_diario, $sueldo_hora, format_a_m_d($fecha_inicio), format_a_m_d($fecha_fin), $cantidad_dias);
            
            echo $rspta ? "ok" : "Trabador no se pudo actualizar";
          }

        break;

        case 'desactivar':

          $rspta=$trabajadorproyecto->desactivar($idtrabajador_por_proyecto);

          echo $rspta ? "Usuario Desactivado" : "Usuario no se puede desactivar";	

        break;

        case 'activar':

          $rspta=$trabajadorproyecto->activar($idtrabajador_por_proyecto);

          echo $rspta ? "Usuario activado" : "Usuario no se puede activar";

        break;

        case 'mostrar':

          $rspta=$trabajadorproyecto->mostrar($idtrabajador_por_proyecto);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);

        break;
        
        case 'verdatos':

          $rspta=$trabajadorproyecto->verdatos($idtrabajador_por_proyecto);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);

        break;

        case 'listar':
          $nube_idproyecto = $_GET["nube_idproyecto"];

          $rspta=$trabajadorproyecto->listar($nube_idproyecto);
          //Vamos a declarar un array
          $data= Array(); $cont = 1;

          $imagen_error = "this.src='../dist/svg/user_default.svg'";
          
          while ($reg=$rspta->fetch_object()){
            $data[]=array(
              "0"=> $cont++,
              "1"=>($reg->estado)?'<button class="btn btn-warning btn-sm mb-1" onclick="mostrar('.$reg->idtrabajador_por_proyecto.','.$reg->idtipo_trabjador.')"><i class="fas fa-pencil-alt"></i></button>'.
                ' <button class="btn btn-danger btn-sm mb-1" onclick="desactivar('.$reg->idtrabajador_por_proyecto.')"><i class="far fa-trash-alt  "></i></button>'.
                ' <button class="btn btn-info btn-sm mb-1" onclick="verdatos('.$reg->idtrabajador_por_proyecto.')"><i class="far fa-eye"></i></button>':
                '<button class="btn btn-warning btn-sm mb-1" onclick="mostrar('.$reg->idtrabajador_por_proyecto.','.$reg->idtipo_trabjador.')"><i class="fa fa-pencil-alt"></i></button>'.
                ' <button class="btn btn-primary btn-sm mb-1" onclick="activar('.$reg->idtrabajador_por_proyecto.')"><i class="fa fa-check"></i></button>'.
                ' <button class="btn btn-info btn-sm mb-1" onclick="verdatos('.$reg->idtrabajador_por_proyecto.')"><i class="far fa-eye"></i></button>',
              "2"=>'<div class="user-block">
                <img class="img-circle" src="../dist/img/usuarios/'. $reg->imagen .'" alt="User Image" onerror="'.$imagen_error.'">
                <span class="username"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'. $reg->nombres .'</p></span>
                <span class="description">'. $reg->tipo_documento .': '. $reg->numero_documento .' </span>
                </div>',
              "3"=>'<div class="text-nowrap"><b>Fecha inicio: </b>'. ( empty($reg->fecha_inicio) ? '--' : format_d_m_a($reg->fecha_inicio) ). '<br> 
                <b>Fecha fin: </b>'.( empty($reg->fecha_fin) ? '--' : format_d_m_a($reg->fecha_fin) ) . '</div>',
              "4"=>'<b>'.$reg->banco .': </b>'. $reg->cuenta_bancaria,
              "5"=>'S/. '.number_format( $reg->sueldo_mensual, 2, '.', ','),
              "6"=>$reg->nombre_tipo.' / '.$reg->cargo,
              "7"=>($reg->estado)?'<span class="text-center badge badge-success">Activado</span>':
              '<span class="text-center badge badge-danger">Desactivado</span>'
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

          $rspta = $trabajadorproyecto->select2_trabajador();
      
          while ($reg = $rspta->fetch_object())  {

            echo '<option  value=' . $reg->id . '>' . $reg->nombre .' - '. $reg->numero_documento . '</option>';
          }

        break;

        case 'm_datos_trabajador':
          $idtrabajador = $_POST["idtrabajador"];
          // $idtrabajador = '8';
           $rspta=$trabajadorproyecto->m_datos_trabajador($idtrabajador);
           //Codificar el resultado utilizando json
           echo json_encode($rspta);
 
         break;

         case 'select_cargo':
           $id_tipo = $_GET["id_tipo"];
           // $idtrabajador = '8';
            $rspta=$trabajadorproyecto->select_cargo($id_tipo);

            while ($reg = $rspta->fetch_object())  {

              echo '<option  value=' . $reg->idcargo_trabajador  . '>' . $reg->nombre .'</option>';
            }
  
         break;
        
      }

    } else {

      require 'noacceso.php';
    }
  }

  // convierte de una fecha(aa-mm-dd): 23-12-2021 a una fecha(dd-mm-aa): 2021-12-23
  function format_a_m_d( $fecha ) {

    if (!empty($fecha)) {

      $fecha_expl = explode("-", $fecha);

      $fecha_convert =  $fecha_expl[2]."-".$fecha_expl[1]."-".$fecha_expl[0];

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