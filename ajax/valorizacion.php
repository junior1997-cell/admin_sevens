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

      date_default_timezone_set('America/Lima'); $date_now = date("d-m-Y h.i.s A");

      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>'; 

      $scheme_host =  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/admin_sevens/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');

      $idproyecto		  = isset($_POST["idproyecto"])? limpiarCadena($_POST["idproyecto"]):"";
      $idvalorizacion = isset($_POST["idvalorizacion"])? limpiarCadena($_POST["idvalorizacion"]):"";
      $indice	        = isset($_POST["indice"])? limpiarCadena($_POST["indice"]):"";
      $nombre	        = isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
      $fecha_inicio	  = isset($_POST["fecha_inicio"])? limpiarCadena($_POST["fecha_inicio"]):"";
      $fecha_fin	    = isset($_POST["fecha_fin"])? limpiarCadena($_POST["fecha_fin"]):"";
      $numero_q_s	    = isset($_POST["numero_q_s"])? limpiarCadena($_POST["numero_q_s"]):"";

      $doc_old_7		  = isset($_POST["doc_old_7"])? limpiarCadena($_POST["doc_old_7"]):"";
      $doc7		        = isset($_POST["doc7"])? limpiarCadena($_POST["doc7"]):"";

      //--------------------------R E S U M E N   Q S ---------------------------------
      
      $idresumen_q_s_valorizacion	= isset($_POST["idresumen_q_s_valorizacion"])? limpiarCadena($_POST["idresumen_q_s_valorizacion"]):"";
      $numero_q_s_resumen_oculto	= isset($_POST["numero_q_s_resumen_oculto"])? limpiarCadena($_POST["numero_q_s_resumen_oculto"]):"";
      $idproyecto_q_s		          = isset($_POST["idproyecto_q_s"])? limpiarCadena($_POST["idproyecto_q_s"]):"";
      $fecha_inicial		          = isset($_POST["fecha_inicial"])? limpiarCadena($_POST["fecha_inicial"]):"";
      $fecha_final		            = isset($_POST["fecha_final"])? limpiarCadena($_POST["fecha_final"]):"";
      $monto_programado		        = isset($_POST["monto_programado"])? limpiarCadena($_POST["monto_programado"]):"";
      $monto_valorizado		        = isset($_POST["monto_valorizado"])? limpiarCadena($_POST["monto_valorizado"]):"";
      $monto_gastado		          = isset($_POST["monto_gastado"])? limpiarCadena($_POST["monto_gastado"]):"";

     // $idresumen_q_s_valorizacion, $numero_q_s_resumen_oculto,$idproyecto_q_s, $fecha_inicial,$fecha_final,$monto_programado,$monto_valorizado,$monto_gastado

      switch ($_GET["op"]) {

        case 'guardaryeditar':

          // doc
          if (!file_exists($_FILES['doc7']['tmp_name']) || !is_uploaded_file($_FILES['doc7']['tmp_name'])) {

						$doc =$_POST["doc_old_7"]; $flat_doc1 = false;

					} else {

						$ext1 = explode(".", $_FILES["doc7"]["name"]); $flat_doc1 = true;						

            $doc  = $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

            move_uploaded_file($_FILES["doc7"]["tmp_name"], "../dist/docs/valorizacion/documento/" . $doc ); 
						
					}

          // Resgistramos docs en proyecto ::::::::::::
          if ($nombre == 'Copia del contrato' || $nombre == 'Cronograma de obra valorizado' || $nombre == 'Acta de entrega de terreno' || $nombre == 'Acta de inicio de obra' || $nombre == 'Certificado de habilidad del ingeniero residente' ) {

            if ($nombre == 'Copia del contrato') {
              // validamos si existe EL DOC para eliminarlo
              if ($flat_doc1 == true) {

                $datos_f1 = $valorizacion->obtenerDocP($idproyecto, 'doc1_contrato_obra');

                $doc1_ant = $datos_f1['data']->fetch_object()->doc_p;

                if ( validar_url_completo($scheme_host. "dist/docs/valorizacion/documento/" . $doc1_ant)  == 200 ) {
                  unlink("../dist/docs/valorizacion/documento/" . $doc1_ant);
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

                  if (validar_url_completo($scheme_host. "dist/docs/valorizacion/documento/" . $doc1_ant)  == 200) {
                    unlink("../dist/docs/valorizacion/documento/" . $doc1_ant);
                  }
                }

                $rspta=$valorizacion->editar_proyecto($idproyecto, $doc, 'doc7_cronograma_obra_valorizad');

              } else {

                if ($nombre == 'Acta de entrega de terreno') {
                  // validamos si existe EL DOC para eliminarlo
                  if ($flat_doc1 == true) {

                    $datos_f1 = $valorizacion->obtenerDocP($idproyecto, 'doc2_entrega_terreno');

                    $doc1_ant = $datos_f1['data']->fetch_object()->doc_p;

                    if (validar_url_completo($scheme_host. "dist/docs/valorizacion/documento/" . $doc1_ant)  == 200) {
                      unlink("../dist/docs/valorizacion/documento/" . $doc1_ant);
                    }
                  }

                  $rspta=$valorizacion->editar_proyecto($idproyecto, $doc, 'doc2_entrega_terreno'); 

                } else {

                  if ($nombre == 'Acta de inicio de obra') {
                    // validamos si existe EL DOC para eliminarlo
                    if ($flat_doc1 == true) {

                      $datos_f1 = $valorizacion->obtenerDocP($idproyecto, 'doc3_inicio_obra');

                      $doc1_ant = $datos_f1['data']->fetch_object()->doc_p;

                      if (validar_url_completo($scheme_host. "dist/docs/valorizacion/documento/" . $doc1_ant)  == 200) {
                        unlink("../dist/docs/valorizacion/documento/" . $doc1_ant);
                      }
                    }

                    $rspta=$valorizacion->editar_proyecto($idproyecto, $doc, 'doc3_inicio_obra');

                  } else {

                    if ($nombre == 'Certificado de habilidad del ingeniero residente') {
                      // validamos si existe EL DOC para eliminarlo
                      if ($flat_doc1 == true) {

                        $datos_f1 = $valorizacion->obtenerDocP($idproyecto, 'doc8_certificado_habilidad_ing_residnt');

                        $doc1_ant = $datos_f1['data']->fetch_object()->doc_p;

                        if (validar_url_completo($scheme_host. "dist/docs/valorizacion/documento/" . $doc1_ant)  == 200) {
                          unlink("../dist/docs/valorizacion/documento/" . $doc1_ant);
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
              
              echo json_encode($rspta, true) ;
              
            }else {
  
              // validamos si existe EL DOC para eliminarlo
              if ($flat_doc1 == true) {
  
                $datos_f1 = $valorizacion->obtenerDocV($idvalorizacion);
  
                $doc1_ant = $datos_f1['data']->fetch_object()->doc_valorizacion;
  
                if (validar_url_completo($scheme_host. "dist/docs/valorizacion/documento/" . $doc1_ant)  == 200) {
  
                  unlink("../dist/docs/valorizacion/documento/" . $doc1_ant);
                }
              }
  
              // editamos un trabajador existente
              $rspta=$valorizacion->editar_valorizacion($idproyecto, $idvalorizacion, $indice, $nombre, $fecha_inicio, $fecha_fin, $numero_q_s, $doc);
              
              echo json_encode($rspta, true) ;              
            }
          }                      

        break; 

        case 'desactivar':

          $rspta=$valorizacion->desactivar( $_GET['nombre_tabla'], $_GET['nombre_columna'], $_GET['id_tabla']);
          echo json_encode($rspta, true) ;

        break;

        case 'eliminar':

          $rspta=$valorizacion->eliminar($_GET['nombre_tabla'], $_GET['nombre_columna'], $_GET['id_tabla']);
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
          $rspta=$valorizacion->listarquincenas($_POST["nube_idproyecto"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
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
  
              $info_eliminar = '\''.encodeCadenaHtml('<del>Valorización Nº '. $value['numero_q_s'] .'</del> - <del>'. $value['indice'].' '. $value['nombre'].'</del>').'\', \''.$value['nombre_tabla'].'\', \''.$value['nombre_columna'].'\', \''.$value['idtabla'].'\'';
  
              $info_editar = '\''.$value['idtabla'].'\', \''.$value['indice'].'\',\''.$value['nombre'].'\', \''.$value['doc_valorizacion'].'\', \''.$value['fecha_inicio'].'\', \''.$value['fecha_fin'].'\', \''.$value['numero_q_s'].'\'';
  
              $parametros_ver_doc='\'' . $value['doc_valorizacion'] .'\', \'' . $value['indice'] .'\', \'' . $value['nombre'] .'\', \'' . $value['numero_q_s'] .'\'';
  
              $btn_tipo = (empty($value['doc_valorizacion']) ? 'btn-outline-info' : 'btn-info'); 
              
              $data[]=array(
                "0"=> $cont++,
                "1"=>'<button class="btn btn-warning btn-sm" onclick="editar('.$info_editar.')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>'.
                  ($value['numero_q_s']=='General'? ' <button class="btn btn-danger btn-sm disabled"><i class="fas fa-skull-crossbones"></i></button>': 
                ' <button class="btn btn-danger btn-sm" onclick="eliminar('.$info_eliminar.')" data-toggle="tooltip" data-original-title="Eliminar"><i class="fas fa-skull-crossbones"></i></button>'),
                "2"=>'<span class="text-bold">Valorización Nº '. $value['numero_q_s'] .'</span>',  
                "3"=>'<span class="text-bold">'.$value['indice'].' '. $value['nombre'] .'</span>',  
                "4"=>'<span class="text-primary text-bold">'. $value['fecha_inicio'] .' - ' . $value['fecha_fin'] .'</span>',  
                "5"=>'<center><button class="btn '.$btn_tipo.' btn-sm" onclick="modal_comprobante('.$parametros_ver_doc.')" data-toggle="tooltip" data-original-title="Ver doc"><i class="fas fa-file-invoice fa-lg"></i> </button> </center>'.$toltip,       
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
        
        //--------------------------R E S U M E N   Q S ---------------------------------
        case 'guardaryeditar_resumen_q_s':         
            
          $rspta=$valorizacion->insertar_editar_resumen_q_s($_POST["resumen_qs"], $_POST["idproyecto"]);            
          echo json_encode($rspta, true) ;            
         
        break; 

        case 'tbla_resumen_q_s':
          $rspta=$valorizacion->tbla_resumen_q_s($_POST['idproyecto'], $_POST['array_fechas'] );
          echo json_encode($rspta, true); 
        break;                 

        case 'total_montos_resumen_q_s':
          $rspta=$valorizacion->list_total_montos_resumen_q_s($_POST['idproyecto_q_s']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true) ;
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

  function quitar_formato_miles($number) {

    $sin_format = 0;

    if ( !empty($number) ) { $sin_format = floatval(str_replace(",", "", $number)); }
    
    return $sin_format;
  }


?>
