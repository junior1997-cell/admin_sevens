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
    if ($_SESSION['valorizacion'] == 1) {

      require_once "../modelos/Valorizacion_concreto.php";

      $valorizacion_concreto = new Valorizacionconcreto();

      date_default_timezone_set('America/Lima'); $date_now = date("d-m-Y h.i.s A");

      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>'; 

      $scheme_host =  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/admin_sevens/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');

      $idproyecto		  = isset($_POST["idproyecto"])? limpiarCadena($_POST["idproyecto"]):"";
      $idvalorizacion = isset($_POST["idvalorizacion"])? limpiarCadena($_POST["idvalorizacion"]):"";
      $nombre_val_concreto = isset($_POST["nombre_val_concreto"])? limpiarCadena($_POST["nombre_val_concreto"]):"";
      $fecha_inicio	  = isset($_POST["fecha_inicio"])? limpiarCadena($_POST["fecha_inicio"]):"";
      $fecha_fin	    = isset($_POST["fecha_fin"])? limpiarCadena($_POST["fecha_fin"]):"";
      $numero_q_s	    = isset($_POST["numero_q_s"])? limpiarCadena($_POST["numero_q_s"]):"";

      $doc_old_7		  = isset($_POST["doc_old_7"])? limpiarCadena($_POST["doc_old_7"]):"";
      $doc7		        = isset($_POST["doc7"])? limpiarCadena($_POST["doc7"]):"";


      switch ($_GET["op"]) {

        case 'guardaryeditar':

          // doc
          if (!file_exists($_FILES['doc7']['tmp_name']) || !is_uploaded_file($_FILES['doc7']['tmp_name'])) {

						$doc =$_POST["doc_old_7"]; $flat_doc1 = false;

					} else {

						$ext1 = explode(".", $_FILES["doc7"]["name"]); $flat_doc1 = true;						

            $doc  = $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

            move_uploaded_file($_FILES["doc7"]["tmp_name"], "../dist/docs/valorizacion_concreto/documento/" . $doc ); 
						
					}

          // REGISTRAMOS EN VALORIZACIONES ::::::::::::

          if (empty($idvalorizacion)){
            // Registramos docs en valorización4
            $rspta=$valorizacion_concreto->insertar_valorizacion($idproyecto,$nombre_val_concreto, $fecha_inicio, $fecha_fin, $numero_q_s, $doc);
            
            echo json_encode($rspta, true) ;
            
          }else {

            // validamos si existe EL DOC para eliminarlo
            if ($flat_doc1 == true) {

              $datos_f1 = $valorizacion_concreto->obtenerDocV($idvalorizacion);

              $doc1_ant = $datos_f1['data']->fetch_object()->doc_valorizacion;

              if (validar_url_completo($scheme_host. "dist/docs/valorizacion_concreto/documento/" . $doc1_ant)  == 200) {

                unlink("../dist/docs/valorizacion/documento/" . $doc1_ant);
              }
            }

            // editamos un trabajador existente
            $rspta=$valorizacion_concreto->editar_valorizacion($idproyecto, $idvalorizacion, $nombre_val_concreto, $fecha_inicio, $fecha_fin, $numero_q_s, $doc);
            
            echo json_encode($rspta, true) ;              
          }
                   

        break; 

        case 'desactivar':

          $rspta=$valorizacion_concreto->desactivar( $_GET['nombre_tabla'], $_GET['nombre_columna'], $_GET['id_tabla']);
          echo json_encode($rspta, true) ;

        break;

        case 'eliminar':

          $rspta=$valorizacion_concreto->eliminar($_GET['nombre_tabla'], $_GET['nombre_columna'], $_GET['id_tabla']);
          echo json_encode($rspta, true) ;
	
        break;
            

        case 'mostrar':

          $rspta=$valorizacion_concreto->mostrar($idtrabajador);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true) ;

        break;

        case 'mostrar-docs-quincena':

          $nube_idproyecto = $_POST["nube_idproyecto"]; $fecha_i = $_POST["fecha_i"]; $fecha_f = $_POST["fecha_f"];
          // $nube_idproyecto = 1; $fecha_i = '2021-10-22'; $fecha_f = '2021-11-19';

          $rspta = $valorizacion_concreto->ver_detalle_quincena($fecha_i, $fecha_f, $nube_idproyecto );
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
