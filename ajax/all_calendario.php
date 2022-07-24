<?php

  ob_start();

  if (strlen(session_id()) < 1) {

    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {

    //Validamos el acceso solo al usuario logueado y autorizado.
    if ($_SESSION['recurso'] == 1) {

      require_once "../modelos/AllCalendario.php";

      $calendario = new AllCalendario();

      $idcalendario		  = isset($_POST["idcalendario"])? limpiarCadena($_POST["idcalendario"]):"";
      $idproyecto 		  = isset($_POST["idproyecto"])? limpiarCadena($_POST["idproyecto"]):"";
      $fecha_feriado	  = isset($_POST["fecha_feriado"])? limpiarCadena($_POST["fecha_feriado"]):"";
      $fecha_invertida	  = isset($_POST["fecha_invertida"])? limpiarCadena($_POST["fecha_invertida"]):"";
      $text_color	      = isset($_POST["text_color"])? limpiarCadena($_POST["text_color"]):"";
      $titulo		        = isset($_POST["titulo"])? limpiarCadena($_POST["titulo"]):"";
      $background_color = isset($_POST["background_color"])? limpiarCadena($_POST["background_color"]):"";
      $descripcion	  	= isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
      

      switch ($_GET["op"]) {

        case 'guardaryeditar':          

          if (empty($idcalendario)){

            $rspta=$calendario->insertar($titulo, $descripcion, $fecha_feriado, $fecha_invertida, $background_color, $text_color);
            
            echo json_encode($rspta, true);	
  
          }else {

            // editamos un trabajador existente
            $rspta=$calendario->editar($idcalendario, $titulo, $descripcion, $fecha_feriado, $fecha_invertida, $background_color, $text_color);
            
            echo json_encode($rspta, true);	
          }            

        break;

        case 'desactivar':

          $rspta=$calendario->desactivar($_GET["id_tabla"]);

 				  echo json_encode($rspta, true);	

        break;

        case 'activar':

          $rspta=$calendario->activar($_GET["id_tabla"]);

 				  echo json_encode($rspta, true);	

        break;        

        case 'listar-calendario':          

          $rspta=$calendario->listar();
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);	      

        break;
        case 'listar-calendario-e':          

          $rspta=$calendario->listar_e();
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);	

        break;

        default: 
          $rspta = ['status'=>'error_code', 'message'=>'Te has confundido en escribir en el <b>swich.</b>', 'data'=>[]]; echo json_encode($rspta, true); 
        break;
      }

      //Fin de las validaciones de acceso
    } else {
      $retorno = ['status'=>'nopermiso', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
      echo json_encode($retorno);
    }
  }

  ob_end_flush();

?>
