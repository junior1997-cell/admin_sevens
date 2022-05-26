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

      require_once "../modelos/Valorizacion.php";

      $valorizacion = new Valorizacion();

      date_default_timezone_set('America/Lima');
      $date_now = date("d-m-Y h:i:s A");

      $idproyecto		  = isset($_POST["idproyecto"])? limpiarCadena($_POST["idproyecto"]):"";
      $idvalorizacion = isset($_POST["idvalorizacion"])? limpiarCadena($_POST["idvalorizacion"]):"";
      $indice	        = isset($_POST["indice"])? limpiarCadena($_POST["indice"]):"";
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

            $doc  = $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

            move_uploaded_file($_FILES["doc7"]["tmp_name"], "../dist/docs/valorizacion/" . $doc ); 
						
					}

          // Resgistramos docs en proyecto ::::::::::::
          if ($nombre == 'Copia del contrato' || $nombre == 'Cronograma de obra valorizado' || $nombre == 'Acta de entrega de terreno' || $nombre == 'Acta de inicio de obra' || $nombre == 'Certificado de habilidad del ingeniero residente' ) {

            if ($nombre == 'Copia del contrato') {
              // validamos si existe EL DOC para eliminarlo
              if ($flat_doc1 == true) {

                $datos_f1 = $valorizacion->obtenerDocP($idproyecto, 'doc1_contrato_obra');

                $doc1_ant = $datos_f1['data']->fetch_object()->doc_p;

                if ($doc1_ant != "") {

                  unlink("../dist/docs/valorizacion/" . $doc1_ant);
                }
              }
              //echo $idproyecto, $doc, 'doc1_contrato_obra';
              $rspta=$valorizacion->editar_proyecto($idproyecto, $doc, 'doc1_contrato_obra');

            } else {

              if ($nombre == 'Cronograma de obra valorizado') {
                // validamos si existe EL DOC para eliminarlo
                if ($flat_doc1 == true) {

                  $datos_f1 = $valorizacion->obtenerDocP($idproyecto, 'doc7_cronograma_obra_valorizad');

                  $doc1_ant = $datos_f1['data']->fetch_object()->doc_p;

                  if ($doc1_ant != "") {

                    unlink("../dist/docs/valorizacion/" . $doc1_ant);
                  }
                }

                $rspta=$valorizacion->editar_proyecto($idproyecto, $doc, 'doc7_cronograma_obra_valorizad');

              } else {

                if ($nombre == 'Acta de entrega de terreno') {
                  // validamos si existe EL DOC para eliminarlo
                  if ($flat_doc1 == true) {

                    $datos_f1 = $valorizacion->obtenerDocP($idproyecto, 'doc2_entrega_terreno');

                    $doc1_ant = $datos_f1['data']->fetch_object()->doc_p;

                    if ($doc1_ant != "") {

                      unlink("../dist/docs/valorizacion/" . $doc1_ant);
                    }
                  }

                  $rspta=$valorizacion->editar_proyecto($idproyecto, $doc, 'doc2_entrega_terreno'); 

                } else {

                  if ($nombre == 'Acta de inicio de obra') {
                    // validamos si existe EL DOC para eliminarlo
                    if ($flat_doc1 == true) {

                      $datos_f1 = $valorizacion->obtenerDocP($idproyecto, 'doc3_inicio_obra');

                      $doc1_ant = $datos_f1['data']->fetch_object()->doc_p;

                      if ($doc1_ant != "") {

                        unlink("../dist/docs/valorizacion/" . $doc1_ant);
                      }
                    }

                    $rspta=$valorizacion->editar_proyecto($idproyecto, $doc, 'doc3_inicio_obra');

                  } else {

                    if ($nombre == 'Certificado de habilidad del ingeniero residente') {
                      // validamos si existe EL DOC para eliminarlo
                      if ($flat_doc1 == true) {

                        $datos_f1 = $valorizacion->obtenerDocP($idproyecto, 'doc8_certificado_habilidad_ing_residnt');

                        $doc1_ant = $datos_f1['data']->fetch_object()->doc_p;

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
            
            echo json_encode($rspta, true) ;

          } else {

            // REGISTRAMOS EN VALORIZACIONES ::::::::::::

            if (empty($idvalorizacion)){
              // Registramos docs en valorización
              $rspta=$valorizacion->insertar_valorizacion($idproyecto, $indice, $nombre, $fecha_inicio, $fecha_fin, $numero_q_s, $doc);
              
              echo $rspta ? "ok" : "No se pudieron registrar todos los datos del Documento";
              
            }else {
  
              // validamos si existe EL DOC para eliminarlo
              if ($flat_doc1 == true) {
  
                $datos_f1 = $valorizacion->obtenerDocV($idvalorizacion);
  
                $doc1_ant = $datos_f1['data']->fetch_object()->doc_valorizacion;
  
                if ($doc1_ant != "") {
  
                  unlink("../dist/docs/valorizacion/" . $doc1_ant);
                }
              }
  
              // editamos un trabajador existente
              $rspta=$valorizacion->editar_valorizacion($idproyecto, $idvalorizacion, $indice, $nombre, $fecha_inicio, $fecha_fin, $numero_q_s, $doc);
              
              echo json_encode($rspta, true) ;              
            }
          }                      

        break; 

        case 'desactivar':

          $rspta=$valorizacion->desactivar( $_POST['nombre_tabla'],$_POST['nombre_columna'],$_POST['idtabla']);
          echo json_encode($rspta, true) ;

        break;

        case 'eliminar':

          $rspta=$valorizacion->eliminar($_POST['nombre_tabla'],$_POST['nombre_columna'],$_POST['idtabla']);
          echo json_encode($rspta, true) ;
	
        break;
            

        case 'mostrar':

          $rspta=$valorizacion->mostrar($idtrabajador);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true) ;

        break;

        case 'mostrar-docs-quincena':

          $nube_idproyecto = $_POST["nube_idproyecto"]; $fecha_i = $_POST["fecha_i"]; $fecha_f = $_POST["fecha_f"];
          // $nube_idproyecto = 1; $fecha_i = '2021-10-22'; $fecha_f = '2021-11-19';

          $rspta = $valorizacion->ver_detalle_quincena($fecha_i, $fecha_f, $nube_idproyecto );
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true) ;

        break;
        
        case 'listarquincenas':

          // $nube_idproyecto = $_POST["nube_idproyecto"];
          $nube_idproyecto = 1;

          $rspta=$valorizacion->listarquincenas($nube_idproyecto);

          //Codificar el resultado utilizando json
          echo json_encode($rspta, true) ;	

        break; 

        case 'listar_tbla_principal':

          $nube_idproyecto = $_GET["nube_idproyecto"];         

          $rspta=$valorizacion->tabla_principal($nube_idproyecto);
          //echo json_encode($rspta);
          $data= Array();
          
          $cont=1;    
          
          if ($rspta['status'] == true) {
            foreach ( $rspta['data'] as $key => $value) {

              $btn_tipo=""; $info_eliminar=''; $info_editar=''; $parametros_ver_doc='';
  
              $info_eliminar = '\''.$value['nombre_tabla'].'\', \''.$value['nombre_columna'].'\', \''.$value['idtabla'].'\',';
  
              $info_editar = '\''.$value['idtabla'].'\', \''.$value['indice'].'\',\''.$value['nombre'].'\', \''.$value['doc_valorizacion'].'\', \''.$value['fecha_inicio'].'\', \''.$value['fecha_fin'].'\', \''.$value['numero_q_s'].'\'';
  
              $parametros_ver_doc='\'' . $value['doc_valorizacion'] .'\', \'' . $value['indice'] .'\', \'' . $value['nombre'] .'\', \'' . $value['numero_q_s'] .'\'';
  
              $btn_tipo = (empty($value['doc_valorizacion'])) ? 'btn-outline-info' : 'btn-info'; 
              
              $data[]=array(
                "0"=> $cont++,
                "1"=>'<button class="btn btn-warning btn-sm" onclick="editar('.$info_editar.')"><i class="fas fa-pencil-alt"></i></button>'.
                  ($value['numero_q_s']=='General'? ' <button class="btn btn-danger btn-sm disabled"><i class="fas fa-skull-crossbones"></i></button>': 
                ' <button class="btn btn-danger btn-sm" onclick="eliminar('.$info_eliminar.')"><i class="fas fa-skull-crossbones"></i></button>'),
                "2"=>'<span class="text-bold">Valorización Nº '. $value['numero_q_s'] .'</span>',  
                "3"=>'<span class="text-bold">'.$value['indice'].' '. $value['nombre'] .'</span>',  
                "4"=>'<span class="text-primary text-bold">'. $value['fecha_inicio'] .' - ' . $value['fecha_fin'] .'</span>',  
                "5"=>'<center><button class="btn '.$btn_tipo.' btn-sm" onclick="modal_comprobante('.$parametros_ver_doc.')"><i class="fas fa-file-invoice fa-lg"></i> </button> </center>',       
              );
            }
            $results = array(
              "sEcho"=>1, //Información para el datatables
              "iTotalRecords"=>count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
              "data"=>$data
            );
            echo json_encode($results, true);
          } else {
            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          }
         
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
