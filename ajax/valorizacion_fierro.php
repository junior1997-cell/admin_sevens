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
    if ($_SESSION['valorizacion_fierro'] == 1) {

      require_once "../modelos/Valorizacion_fierro.php";

      $valorizacion_fierro = new ValorizacionFierro();

      date_default_timezone_set('America/Lima'); $date_now = date("d_m_Y__h_i_s_A");

      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>'; 

      $scheme_host =  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/admin_sevens/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');

      $idproyecto		      = isset($_POST["idproyecto"])? limpiarCadena($_POST["idproyecto"]):"";
      $idfierro_por_valorizacion = isset($_POST["idfierro_por_valorizacion"])? limpiarCadena($_POST["idfierro_por_valorizacion"]):"";
      $nombre_doc	        = isset($_POST["nombre_doc"])? limpiarCadena($_POST["nombre_doc"]):"";
      $numero_valorizacion= isset($_POST["numero_valorizacion"])? limpiarCadena($_POST["numero_valorizacion"]):"";
      $fecha_inicial	    = isset($_POST["fecha_inicial"])? limpiarCadena($_POST["fecha_inicial"]):"";
      $fecha_final	      = isset($_POST["fecha_final"])? limpiarCadena($_POST["fecha_final"]):"";
      $doc_old_1		      = isset($_POST["doc_old_1"])? limpiarCadena($_POST["doc_old_1"]):"";

      switch ($_GET["op"]) {  
        case 'guardar_y_editar_fierro':
          
          // ficha técnica
          if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {

            $doc_fierro = $_POST["doc_old_1"];

            $flat_doc1 = false;

          } else {

            $ext1 = explode(".", $_FILES["doc1"]["name"]);

            $flat_doc1 = true;

            $doc_fierro = $date_now .'__'. random_int(0, 20) . round(microtime(true)) . random_int(21, 41) . '.' . end($ext1);

            move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/valorizacion_fierro/documento/" . $doc_fierro);
          }

          if (empty($idfierro_por_valorizacion)) {
            
            $rspta = $valorizacion_fierro->insertar($idproyecto, $nombre_doc, $numero_valorizacion, $fecha_inicial, $fecha_final, $doc_fierro);
            
            echo json_encode( $rspta, true);

          } else {

            // validamos si existe LA IMG para eliminarlo
            if ($flat_doc1 == true) {

              $doc_bd = $valorizacion_fierro->optener_doc_para_eliminar($idfierro_por_valorizacion);
              $doc_fierro_delete = $doc_bd['data']['documento'];
              
              if ( validar_url_completo($scheme_host. "dist/docs/valorizacion_fierro/documento/" . $doc_fierro_delete)  == 200) {
                unlink("../dist/docs/valorizacion_fierro/documento/" . $doc_fierro_delete);
              }
            }
             
            $rspta = $valorizacion_fierro->editar($idproyecto, $idfierro_por_valorizacion, $nombre_doc, $numero_valorizacion, $fecha_inicial, $fecha_final, $doc_fierro);
            
            echo json_encode( $rspta, true) ;
          }
        break;

        case 'mostrar':

          $rspta=$valorizacion_fierro->mostrar($idtrabajador);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true) ;

        break;

        case 'mostrar-docs-quincena':          

          $rspta = $valorizacion_fierro->mostrar_docs_quincena($_POST["nube_idproyecto"], $_POST["fecha_i"], $_POST["fecha_f"], $_POST["numero_q_s"] );
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true) ;

        break;

        case 'todos_los_docs':          

          $rspta = $valorizacion_fierro->todos_los_docs($_POST["nube_idproyecto"] );
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true) ;

        break;
        
        case 'listarquincenas':
          $rspta=$valorizacion_fierro->listarquincenas($_POST["nube_idproyecto"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break; 


        default: 
          $rspta = ['status'=>'error_code', 'message'=>'Te has confundido en escribir en el <b>swich.</b>', 'data'=>[]]; echo json_encode($rspta, true); 
        break;
      }

      //Fin de las validaciones de acceso
    } else {
      $retorno = ['status'=>'nopermiso', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data'=>[] ];
      echo json_encode($retorno, true);
    }
  }

  ob_end_flush();

?>
