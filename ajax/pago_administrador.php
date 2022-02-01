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

        case 'desactivar':

          $rspta=$pago_administrador->desactivar($idtrabajador_por_proyecto);

          echo $rspta ? "Usuario Desactivado" : "Usuario no se puede desactivar";	

        break;

        case 'activar':

          $rspta=$pago_administrador->activar($idtrabajador_por_proyecto);

          echo $rspta ? "Usuario activado" : "Usuario no se puede activar";

        break;

        case 'mostrar':

          $rspta=$pago_administrador->mostrar($idtrabajador_por_proyecto);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);

        break;
        
        case 'verdatos':

          $rspta=$pago_administrador->verdatos($idtrabajador_por_proyecto);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);

        break;

        case 'listar_tbla_principal':
          $nube_idproyecto = $_GET["nube_idproyecto"];

          $rspta=$pago_administrador->listar_tbla_principal($nube_idproyecto);
          //Vamos a declarar un array
          $data= Array();

          $imagen_error = "this.src='../dist/svg/user_default.svg'";
          
          while ($reg=$rspta->fetch_object()){
            $data[]=array(
              "0"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idtrabajador_por_proyecto.')"><i class="fas fa-pencil-alt"></i></button>'.
                ' <button class="btn btn-danger" onclick="desactivar('.$reg->idtrabajador_por_proyecto.')"><i class="far fa-trash-alt  "></i></button>'.
                ' <button class="btn btn-info" onclick="verdatos('.$reg->idtrabajador_por_proyecto.')"><i class="far fa-eye"></i></button>':
                '<button class="btn btn-warning" onclick="mostrar('.$reg->idtrabajador_por_proyecto.')"><i class="fa fa-pencil-alt"></i></button>'.
                ' <button class="btn btn-primary" onclick="activar('.$reg->idtrabajador_por_proyecto.')"><i class="fa fa-check"></i></button>'.
                ' <button class="btn btn-info" onclick="verdatos('.$reg->idtrabajador_por_proyecto.')"><i class="far fa-eye"></i></button>',
              "1"=>'<div class="user-block">
                <img class="img-circle" src="../dist/img/usuarios/'. $reg->imagen_perfil .'" alt="User Image" onerror="'.$imagen_error.'">
                <span class="username"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'. $reg->nombres .'</p></span>
                <span class="description">'. $reg->tipo_documento .': '. $reg->numero_documento .' </span>
                </div>',
              "2"=>'13-10-2021',
              "3"=>'30-01-2022',
              "4"=>'03-05-2022',
              "5" => '60',
              "6"=>'30-12-2022',
              "7"=>($reg->estado)?'<span class="text-center badge badge-success">Activado</span>':
              '<span class="text-center badge badge-danger">Desactivado</span>',
              "8" => '0',
              "9"=>$reg->tipo.' / '.$reg->cargo,
              "10" => '0',
              "11"=>'<div class="text-center"> <button class="btn btn-info btn-sm" onclick="listar_comprobantes()"><i class="fas fa-dollar-sign nav-icon"></i> Pagar</button> <button style="font-size: 14px;" class="btn btn-danger btn-xs">0.00</button></div>',
              "12" => '0',
              "13"=>$reg->tipo.' / '.$reg->cargo,
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

          $rspta = $pago_administrador->select2_trabajador();
      
          while ($reg = $rspta->fetch_object())  {

            echo '<option value=' . $reg->id . '>' . $reg->nombre .' - '. $reg->numero_documento . '</option>';
          }

        break;
        
      }

    } else {

      require 'noacceso.php';
    }
  }

	ob_end_flush();

?>