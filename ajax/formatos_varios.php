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
    if ($_SESSION['asistencia_obrero'] == 1) {

      require_once "../modelos/Formatos_varios.php";

      $formatos_varios=new FormatosVarios();  
      
      date_default_timezone_set('America/Lima');  $date_now = date("d-m-Y h.i.s A");
      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';
      
      $plazo_actividad	= isset($_POST["plazo_actividad"])? limpiarCadena($_POST["plazo_actividad"]):"";
      
      switch ($_GET["op"]){        

        case 'data_format_ats':         

          $rspta=$formatos_varios->formato_ats($_POST["nube_idproyecto"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);		
          
        break; 

        default: 
          $rspta = ['status'=>'error_code', 'message'=>'Te has confundido en escribir en el <b>swich.</b>', 'data'=>[]]; echo json_encode($rspta, true); 
        break;

      } // end switch

    } else {
      $retorno = ['status'=>'nopermiso', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
      echo json_encode($retorno);
    }
  }

	ob_end_flush();

?>