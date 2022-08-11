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
            
            echo json_encode($rspta, true);

          }else {
            // editamos un trabajador existente
            $rspta=$trabajadorproyecto->editar($idtrabajador_por_proyecto,$trabajador, $cargo, $desempenio, $sueldo_mensual, $sueldo_diario, $sueldo_hora, format_a_m_d($fecha_inicio), format_a_m_d($fecha_fin), $cantidad_dias);
            
            echo json_encode($rspta, true);
          }

        break;

        case 'desactivar':

          $rspta=$trabajadorproyecto->desactivar($idtrabajador_por_proyecto);

          echo json_encode($rspta, true);	

        break;

        case 'activar':

          $rspta=$trabajadorproyecto->activar($idtrabajador_por_proyecto);

          echo json_encode($rspta, true);

        break;

        case 'mostrar':

          $rspta=$trabajadorproyecto->mostrar($idtrabajador_por_proyecto);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);

        break;
        
        case 'ver_datos_trabajador':

          $rspta=$trabajadorproyecto->ver_datos_trabajador($idtrabajador_por_proyecto);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);

        break;

        case 'tbla_principal':
          $nube_idproyecto = $_GET["nube_idproyecto"];

          $rspta=$trabajadorproyecto->tbla_principal($nube_idproyecto, $_GET["estado"]);
          //Vamos a declarar un array
          $data= Array(); $cont = 1;
          // '<b>'.$reg->banco .': </b>'. $reg->cuenta_bancaria

          $imagen_error = "this.src='../dist/svg/user_default.svg'";
          if ($rspta['status'] == true) {
            foreach ($rspta['data'] as $key => $value) { 
              $data[]=array(
                "0"=> $cont++,
                "1"=>($value['estado'])?'<button class="btn btn-warning btn-sm mb-1" onclick="mostrar('.$value['idtrabajador_por_proyecto'].','.$value['idtipo_trabjador'].')"><i class="fas fa-pencil-alt"></i></button>'.
                  ' <button class="btn btn-danger btn-sm mb-1" onclick="desactivar('.$value['idtrabajador_por_proyecto'].')"><i class="far fa-trash-alt  "></i></button>'.
                  ' <button class="btn btn-info btn-sm mb-1" onclick="verdatos('.$value['idtrabajador_por_proyecto'].')"><i class="far fa-eye"></i></button>':
                  '<button class="btn btn-warning btn-sm mb-1" onclick="mostrar('.$value['idtrabajador_por_proyecto'].','.$value['idtipo_trabjador'].')"><i class="fa fa-pencil-alt"></i></button>'.
                  ' <button class="btn btn-primary btn-sm mb-1" onclick="activar('.$value['idtrabajador_por_proyecto'].')"><i class="fa fa-check"></i></button>'.
                  ' <button class="btn btn-info btn-sm mb-1" onclick="verdatos('.$value['idtrabajador_por_proyecto'].')"><i class="far fa-eye"></i></button>',
                "2"=>'<div class="user-block">
                  <img class="img-circle" src="../dist/docs/all_trabajador/perfil/'. $value['imagen_perfil'] .'" alt="User Image" onerror="'.$imagen_error.'">
                  <span class="username"><p class="text-primary m-b-02rem" >'. $value['trabajador'] .'</p></span>
                  <span class="description">'. $value['tipo_documento'] .': '. $value['numero_documento'] .' </span>
                  </div>',
                "3"=>'<div class="text-nowrap"><b>Fecha inicio: </b>'. ( empty($value['fecha_inicio']) ? '--' : format_d_m_a($value['fecha_inicio']) ). '<br> 
                  <b>Fecha fin: </b>'.( empty($value['fecha_fin']) ? '--' : format_d_m_a($value['fecha_fin']) ) . '</div>',
                "4"=>'<b>'.$value['banco'] .': </b>'. $value['cuenta_bancaria'],
                "5"=>'S/ '.number_format( $value['sueldo_mensual'], 2, '.', ','),
                "6"=>$value['nombre_tipo'].' / '.$value['cargo'],
                "7"=>($value['estado'])?'<span class="text-center badge badge-success">Activado</span>':
                '<span class="text-center badge badge-danger">Desactivado</span>'
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

        case 'm_datos_trabajador':
          $idtrabajador = $_POST["idtrabajador"];
          // $idtrabajador = '8';
          $rspta=$trabajadorproyecto->m_datos_trabajador($idtrabajador);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
 
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