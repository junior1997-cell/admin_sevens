<?php

  ob_start();

  if (strlen(session_id()) < 1) {

    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {

    header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.

  } else {

    //Validamos el acceso solo al usuario logueado y autorizado.
    if ($_SESSION['valorizacion'] == 1) {

      require_once "../modelos/Valorizacion.php";

      $valorizacion = new Valorizacion();

      //$idtrabajador,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$nacimiento,$tipo_trabajador,$desempenio,$c_bancaria,$email,$cargo,$banco,$tutular_cuenta,$sueldo_diario,$sueldo_mensual,$sueldo_hora,$imagen	
      $idproyecto		  = isset($_POST["idproyecto"])? limpiarCadena($_POST["idproyecto"]):"";
      $idvalorizacion = isset($_POST["idvalorizacion"])? limpiarCadena($_POST["idvalorizacion"]):"";
      $nombre	        = isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
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

            $doc  = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

            move_uploaded_file($_FILES["doc7"]["tmp_name"], "../dist/docs/valorizacion/" . $doc ); 
						
					}

          // Resgistramos docs en proyecto ::::::::::::
          if ($nombre == 'doc1' || $nombre == 'doc4' || $nombre == 'doc8.1' || $nombre == 'doc8.2' || $nombre == 'doc8.3' ) {

            if ($nombre == 'doc1') {
              // validamos si existe EL DOC para eliminarlo
              if ($flat_doc1 == true) {

                $datos_f1 = $valorizacion->obtenerDocP($idproyecto, 'doc1_contrato_obra');

                $doc1_ant = $datos_f1->fetch_object()->doc_p;

                if ($doc1_ant != "") {

                  unlink("../dist/docs/valorizacion/" . $doc1_ant);
                }
              }

              $rspta=$valorizacion->editar_proyecto($idproyecto, $doc, 'doc1_contrato_obra');

            } else {

              if ($nombre == 'doc4') {
                // validamos si existe EL DOC para eliminarlo
                if ($flat_doc1 == true) {

                  $datos_f1 = $valorizacion->obtenerDocP($idproyecto, 'doc7_cronograma_obra_valorizad');

                  $doc1_ant = $datos_f1->fetch_object()->doc_p;

                  if ($doc1_ant != "") {

                    unlink("../dist/docs/valorizacion/" . $doc1_ant);
                  }
                }

                $rspta=$valorizacion->editar_proyecto($idproyecto, $doc, 'doc7_cronograma_obra_valorizad');

              } else {

                if ($nombre == 'doc8.1') {
                  // validamos si existe EL DOC para eliminarlo
                  if ($flat_doc1 == true) {

                    $datos_f1 = $valorizacion->obtenerDocP($idproyecto, 'doc2_entrega_terreno');

                    $doc1_ant = $datos_f1->fetch_object()->doc_p;

                    if ($doc1_ant != "") {

                      unlink("../dist/docs/valorizacion/" . $doc1_ant);
                    }
                  }

                  $rspta=$valorizacion->editar_proyecto($idproyecto, $doc, 'doc2_entrega_terreno'); 

                } else {

                  if ($nombre == 'doc8.2') {
                    // validamos si existe EL DOC para eliminarlo
                    if ($flat_doc1 == true) {

                      $datos_f1 = $valorizacion->obtenerDocP($idproyecto, 'doc3_inicio_obra');

                      $doc1_ant = $datos_f1->fetch_object()->doc_p;

                      if ($doc1_ant != "") {

                        unlink("../dist/docs/valorizacion/" . $doc1_ant);
                      }
                    }

                    $rspta=$valorizacion->editar_proyecto($idproyecto, $doc, 'doc3_inicio_obra');

                  } else {

                    if ($nombre == 'doc8.3') {
                      // validamos si existe EL DOC para eliminarlo
                      if ($flat_doc1 == true) {

                        $datos_f1 = $valorizacion->obtenerDocP($idproyecto, 'doc8_certificado_habilidad_ing_residnt');

                        $doc1_ant = $datos_f1->fetch_object()->doc_p;

                        if ($doc1_ant != "") {

                          unlink("../dist/docs/valorizacion/" . $doc1_ant);
                        }
                      }

                      $rspta = $valorizacion->editar_proyecto($idproyecto, $doc, 'doc8_certificado_habilidad_ing_residnt');
                    }
                  }
                }
              }
            }
            
            echo $rspta ? "ok" : "No se pudieron registrar todos los datos del Documento";

          } else {
            // REGISTRAMOS EN VALORIZACIONES ::::::::::::
            if (empty($idvalorizacion)){
              // Registramos docs en valorización
              $rspta=$valorizacion->insertar_valorizacion($idproyecto, $nombre, $fecha_inicio, $fecha_fin, $numero_q_s, $doc);
              
              echo $rspta ? "ok" : "No se pudieron registrar todos los datos del Documento";
              
            }else {
  
              // validamos si existe EL DOC para eliminarlo
              if ($flat_doc1 == true) {
  
                $datos_f1 = $valorizacion->obtenerDocV($idvalorizacion);
  
                $doc1_ant = $datos_f1->fetch_object()->doc_valorizacion;
  
                if ($doc1_ant != "") {
  
                  unlink("../dist/docs/valorizacion/" . $doc1_ant);
                }
              }
  
              // editamos un trabajador existente
              $rspta=$valorizacion->editar_valorizacion($idproyecto, $idvalorizacion, $nombre, $fecha_inicio, $fecha_fin, $numero_q_s, $doc);
              
              echo $rspta ? "ok" : "Documento no se pudo actualizar";
            }
          }                      

        break;       

        case 'mostrar':

          $rspta=$valorizacion->mostrar($idtrabajador);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);

        break;

        case 'mostrar-docs-quincena':

          $nube_idproyecto = $_POST["nube_idproyecto"]; $fecha_i = $_POST["fecha_i"]; $fecha_f = $_POST["fecha_f"];
          // $nube_idproyecto = 1; $fecha_i = '2021-10-22'; $fecha_f = '2021-11-19';

          $rspta = $valorizacion->ver_detalle_quincena($fecha_i, $fecha_f, $nube_idproyecto );
          //Codificar el resultado utilizando json
          echo json_encode($rspta);

        break;
        
        case 'listarquincenas':

          // $nube_idproyecto = $_POST["nube_idproyecto"];
          $nube_idproyecto = 1;

          $rspta=$valorizacion->listarquincenas($nube_idproyecto);

          //Codificar el resultado utilizando json
          echo json_encode($rspta);	

        break;        
      }

      //Fin de las validaciones de acceso
    } else {

      require 'noacceso.php';
    }
  }

  ob_end_flush();

?>
