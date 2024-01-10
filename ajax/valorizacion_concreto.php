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
    if ($_SESSION['valorizacion_concreto'] == 1) {

      require_once "../modelos/Valorizacion_concreto.php";

      $valorizacion_concreto = new Valorizacionconcreto();

      date_default_timezone_set('America/Lima'); $date_now = date("d_m_Y__h_i_s_A");

      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>'; 

      $scheme_host =  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/admin_sevens/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');

      $idproyecto		  = isset($_POST["idproyecto"])? limpiarCadena($_POST["idproyecto"]):"";
      $idvalorizacion = isset($_POST["idconcreto_por_valorizacion"])? limpiarCadena($_POST["idconcreto_por_valorizacion"]):"";
      $nombre_val_concreto = isset($_POST["nombre_doc"])? limpiarCadena($_POST["nombre_doc"]):"";
      $fecha_inicio	  = isset($_POST["fecha_inicial"])? limpiarCadena($_POST["fecha_inicial"]):"";
      $fecha_fin	    = isset($_POST["fecha_final"])? limpiarCadena($_POST["fecha_final"]):"";
      $numero_q_s	    = isset($_POST["numero_valorizacion"])? limpiarCadena($_POST["numero_valorizacion"]):"";

      $doc_old_7		  = isset($_POST["doc_old_1"])? limpiarCadena($_POST["doc_old_1"]):"";
      $doc1		        = isset($_POST["doc1"])? limpiarCadena($_POST["doc1"]):"";

      // $idproyecto,$nombre_val_concreto, $fecha_inicio, $fecha_fin, $numero_q_s, $doc
      switch ($_GET["op"]) {

        case 'guardar_y_editar_fierro':
          
          // ficha técnica
          if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {

            $doc_concreto = $_POST["doc_old_1"];

            $flat_doc1 = false;

          } else {

            $ext1 = explode(".", $_FILES["doc1"]["name"]);

            $flat_doc1 = true;

            $doc_concreto = $date_now .'__'. random_int(0, 20) . round(microtime(true)) . random_int(21, 41) . '.' . end($ext1);

            move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/valorizacion_concreto/documento/" . $doc_concreto);
          }

          if (empty($idvalorizacion)) {
            
            $rspta = $valorizacion_concreto->insertar($idproyecto,$nombre_val_concreto, $fecha_inicio, $fecha_fin, $numero_q_s, $doc_concreto);
            
            echo json_encode( $rspta, true);

          } else {

            // validamos si existe LA IMG para eliminarlo
            if ($flat_doc1 == true) {

              $doc_bd = $valorizacion_concreto->optener_doc_para_eliminar($idvalorizacion);
              $doc_concreto_delete = $doc_bd['data']['documento'];
              
              if ( validar_url_completo($scheme_host. "dist/docs/valorizacion_concreto/documento/" . $doc_concreto_delete)  == 200) {
                unlink("../dist/docs/valorizacion_concreto/documento/" . $doc_concreto_delete);
              }
            }
             
            $rspta = $valorizacion_concreto->editar($idvalorizacion,$idproyecto,$nombre_val_concreto, $fecha_inicio, $fecha_fin, $numero_q_s, $doc_concreto);
            
            echo json_encode( $rspta, true) ;
          }
        break;

        case 'mostrar-docs-quincena':          

          $rspta = $valorizacion_concreto->mostrar_docs_quincena($_POST["nube_idproyecto"], $_POST["fecha_i"], $_POST["fecha_f"], $_POST["numero_q_s"] );
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true) ;

        break;

        case 'todos_los_docs':          

          $rspta = $valorizacion_concreto->todos_los_docs($_POST["nube_idproyecto"] );
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true) ;

        break;
        
        case 'listarquincenas':
          $rspta=$valorizacion_concreto->listarquincenas($_POST["nube_idproyecto"]);
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
