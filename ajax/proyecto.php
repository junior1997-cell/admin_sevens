<?php
  ob_start();

  if (strlen(session_id()) < 1){

    session_start();//Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {    
     
		header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		die();

	} else {
    //Validamos el acceso solo al usuario logueado y autorizado.
    if ($_SESSION['escritorio'] == 1) {

      require_once "../modelos/Proyecto.php";

      $proyecto = new Proyecto();

      $idproyecto				    = isset($_POST["idproyecto"])? limpiarCadena($_POST["idproyecto"]):""; 
      $tipo_documento			  = isset($_POST["tipo_documento"])? limpiarCadena($_POST["tipo_documento"]):"";
      $numero_documento		  = isset($_POST["numero_documento"])? limpiarCadena($_POST["numero_documento"]):"";
      $empresa				      = isset($_POST["empresa"])? limpiarCadena($_POST["empresa"]):"";
      $nombre_proyecto		  = isset($_POST["nombre_proyecto"])? limpiarCadena($_POST["nombre_proyecto"]):"";
      $nombre_codigo		    = isset($_POST["nombre_codigo"])? limpiarCadena($_POST["nombre_codigo"]):"";
      $ubicacion				    = isset($_POST["ubicacion"])? limpiarCadena($_POST["ubicacion"]):"";
      $actividad_trabajo		= isset($_POST["actividad_trabajo"])? limpiarCadena($_POST["actividad_trabajo"]):"";
      $empresa_acargo 		  = isset($_POST['empresa_acargo'])? limpiarCadena($_POST['empresa_acargo']):"";
      $costo					      = isset($_POST["costo"])? limpiarCadena($_POST["costo"]):"";
      $fecha_inicio			    = isset($_POST["fecha_inicio"])? limpiarCadena($_POST["fecha_inicio"]):"";
      $fecha_fin				    = isset($_POST["fecha_fin"])? limpiarCadena($_POST["fecha_fin"]):"";
      $fecha_inicio_actividad= isset($_POST["fecha_inicio_actividad"])? limpiarCadena($_POST["fecha_inicio_actividad"]):"";
      $fecha_fin_actividad	= isset($_POST["fecha_fin_actividad"])? limpiarCadena($_POST["fecha_fin_actividad"]):"";
      $plazo_actividad		  = isset($_POST["plazo_actividad"])? limpiarCadena($_POST["plazo_actividad"]):"";
      $plazo		            = isset($_POST["plazo"])? limpiarCadena($_POST["plazo"]):""; 
      $dias_habiles		      = isset($_POST["dias_habiles"])? limpiarCadena($_POST["dias_habiles"]):"";

      $fecha_pago_obrero		= isset($_POST["fecha_pago_obrero"])? limpiarCadena($_POST["fecha_pago_obrero"]):"";
      $fecha_valorizacion		= isset($_POST["fecha_valorizacion"])? limpiarCadena($_POST["fecha_valorizacion"]):"";

      $permanente_pago_obrero		= isset($_POST["permanente_pago_obrero"])? limpiarCadena($_POST["permanente_pago_obrero"]):"";
       
      $doc1; $doc2; $doc3; $doc4; $doc5; $doc6;
      // $idproyecto,$tipo_documento,$numero_documento,$empresa,$nombre_proyecto,$ubicacion,$actividad_trabajo,
      // $empresa_acargo,$costo,$fecha_inicio,$fecha_fin,$doc1_contrato_obra,$doc2_entrega_terreno,$doc3_inicio_obra,
      switch ($_GET["op"]){

        case 'guardaryeditar':
          
          $fecha_inicio_actividad =  format_a_m_d( $fecha_inicio_actividad);          
          $fecha_fin_actividad =  format_a_m_d( $fecha_fin_actividad);

          $fecha_inicio =  format_a_m_d( $fecha_inicio);          
          $fecha_fin =  format_a_m_d( $fecha_fin);

          //*DOC 1*//
          if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {

            $flat_doc1 = false;  $doc1 = $_POST["doc_old_1"];

          } else {

            $flat_doc1 = true;  $ext_doc1 = explode(".", $_FILES["doc1"]["name"]);            
              
            $doc1 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext_doc1);

            move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/valorizacion/" . $doc1);
            
          }	

          //*DOC 2*//
          if (!file_exists($_FILES['doc2']['tmp_name']) || !is_uploaded_file($_FILES['doc2']['tmp_name'])) {

            $flat_doc2 = false;

            $doc2      = $_POST["doc_old_2"];

          } else {

            $flat_doc2 = true;

            $ext_doc2     = explode(".", $_FILES["doc2"]["name"]);
              
            $doc2 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext_doc2);

            move_uploaded_file($_FILES["doc2"]["tmp_name"], "../dist/docs/valorizacion/" . $doc2);
            
          }	

          //*DOC 3*//
          if (!file_exists($_FILES['doc3']['tmp_name']) || !is_uploaded_file($_FILES['doc3']['tmp_name'])) {

            $flat_doc3 = false;

            $doc3      = $_POST["doc_old_3"];

          } else {

            $flat_doc3 = true;

            $ext_doc3     = explode(".", $_FILES["doc3"]["name"]);
              
            $doc3 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext_doc3);

            move_uploaded_file($_FILES["doc3"]["tmp_name"], "../dist/docs/valorizacion/" . $doc3);
            
          }	

          //*DOC 4*//
          if (!file_exists($_FILES['doc4']['tmp_name']) || !is_uploaded_file($_FILES['doc4']['tmp_name'])) {

            $flat_doc4 = false;

            $doc4      = $_POST["doc_old_4"];

          } else {

            $flat_doc4 = true;

            $ext_doc4     = explode(".", $_FILES["doc4"]["name"]);
              
            $doc4 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext_doc4);

            move_uploaded_file($_FILES["doc4"]["tmp_name"], "../dist/docs/valorizacion/" . $doc4);
            
          }	

          //*DOC 5*//
          if (!file_exists($_FILES['doc5']['tmp_name']) || !is_uploaded_file($_FILES['doc5']['tmp_name'])) {

            $flat_doc5 = false;

            $doc5      = $_POST["doc_old_5"];

          } else {

            $flat_doc5 = true;

            $ext_doc5     = explode(".", $_FILES["doc5"]["name"]);
              
            $doc5 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext_doc5);

            move_uploaded_file($_FILES["doc5"]["tmp_name"], "../dist/docs/valorizacion/" . $doc5);
            
          }	

          //*DOC 6*//
          if (!file_exists($_FILES['doc6']['tmp_name']) || !is_uploaded_file($_FILES['doc6']['tmp_name'])) {

            $flat_doc6 = false;

            $doc6      = $_POST["doc_old_6"];

          } else {

            $flat_doc6 = true;

            $ext_doc6     = explode(".", $_FILES["doc6"]["name"]);
            
            // echo json_encode($_FILES['doc6']); 
            $doc6 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext_doc6);

            move_uploaded_file($_FILES["doc6"]["tmp_name"], "../dist/docs/valorizacion/" . $doc6);
            
          }          

          if (empty($idproyecto)){
            // insertamos en la bd
            $rspta=$proyecto->insertar($tipo_documento, $numero_documento, $empresa, $nombre_proyecto, $nombre_codigo, $ubicacion, $actividad_trabajo, $empresa_acargo, quitar_formato_miles($costo), $fecha_inicio_actividad, $fecha_fin_actividad, $plazo_actividad, $fecha_inicio, $fecha_fin, $plazo, $dias_habiles, $doc1, $doc2, $doc3, $doc4, $doc5, $doc6, $fecha_pago_obrero, $fecha_valorizacion, $permanente_pago_obrero);
            // echo $rspta ;
            echo $rspta ? "ok" : "No se pudieron registrar todos los datos del proyecto";

          } else {
            // validamos si existe el doc para eliminarlo
            if ($flat_doc1 == true) {

              $datos_f1 = $proyecto->obtenerDocs($idproyecto);

              $doc1_ant = $datos_f1->fetch_object()->doc1_contrato_obra;

              if ($doc1_ant != "") {

                unlink("../dist/docs/valorizacion/" . $doc1_ant);
              }
            }

            if ($flat_doc2 == true) {

              $datos_f2 = $proyecto->obtenerDocs($idproyecto);

              $doc2_ant = $datos_f2->fetch_object()->doc2_entrega_terreno;

              if ($doc2_ant != "") {

                unlink("../dist/docs/valorizacion/" . $doc2_ant);
              }
            }

            if ($flat_doc3 == true) {

              $datos_f3 = $proyecto->obtenerDocs($idproyecto);

              $doc3_ant = $datos_f3->fetch_object()->doc3_inicio_obra;

              if ($doc3_ant != "") {

                unlink("../dist/docs/valorizacion/" . $doc3_ant);
              }
            }

            if ($flat_doc4 == true) {

              $datos_f4 = $proyecto->obtenerDocs($idproyecto);

              $doc4_ant = $datos_f4->fetch_object()->doc4_presupuesto;

              if ($doc4_ant != "") {

                unlink("../dist/docs/valorizacion/" . $doc4_ant);
              }
            }

            if ($flat_doc5 == true) {

              $datos_f5 = $proyecto->obtenerDocs($idproyecto);

              $doc5_ant = $datos_f5->fetch_object()->doc5_analisis_costos_unitarios;

              if ($doc5_ant != "") {

                unlink("../dist/docs/valorizacion/" . $doc5_ant);
              }
            }

            if ($flat_doc6 == true) {

              $datos_f6 = $proyecto->obtenerDocs($idproyecto);

              $doc6_ant = $datos_f6->fetch_object()->doc6_insumos;

              if ($doc6_ant != "") {

                unlink("../dist/docs/valorizacion/" . $doc6_ant);
              }
            }

            $rspta=$proyecto->editar($idproyecto, $tipo_documento, $numero_documento, $empresa, $nombre_proyecto, $nombre_codigo, $ubicacion, $actividad_trabajo, $empresa_acargo, quitar_formato_miles($costo), $fecha_inicio_actividad, $fecha_fin_actividad, $plazo_actividad, $fecha_inicio, $fecha_fin, $plazo, $dias_habiles, $doc1, $doc2, $doc3, $doc4, $doc5, $doc6, $fecha_pago_obrero, $fecha_valorizacion, $permanente_pago_obrero);
            
            echo $rspta ? "ok" : "Proyecto no se pudo actualizar";
          }
            
        break;
      
        case 'empezar_proyecto':

          $rspta=$proyecto->empezar_proyecto($idproyecto);

          echo $rspta ? "ok" : "No se logro empezar el proyecto";	

        break;

        case 'terminar_proyecto':

          $rspta=$proyecto->terminar_proyecto($idproyecto);

          echo $rspta ? "ok" : "No se logro terminar el proyecto";

        break;

        case 'reiniciar_proyecto':

          $rspta=$proyecto->reiniciar_proyecto($idproyecto);

          echo $rspta ? "ok" : "No se logro reiniciar el proyecto";	

        break;

        case 'mostrar':

          $rspta=$proyecto->mostrar($idproyecto);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);	

        break;

        case 'tablero':
          $rspta=$proyecto->tablero();
          //Codificar el resultado utilizando json
          echo json_encode($rspta);
        break;

        case 'listar':

          $rspta=$proyecto->listar();
          //Vamos a declarar un array
          $data= Array();
          $cont=1;
          while ($reg=$rspta->fetch_object()){

            $estado = "";
            $acciones = "";

            if ($reg->estado == '2') {

              $estado = '<span class="text-center badge badge-danger">No empezado</span>';
              $acciones = '<button class="btn btn-success btn-sm" onclick="empezar_proyecto('.$reg->idproyecto.')" data-toggle="tooltip" data-original-title="Empezar proyecto" /*style="margin-right: 3px !important;"*/><i class="fa fa-check"></i></button>';
            } else {

              if ($reg->estado == '1') {

                $estado = '<span class="text-center badge badge-warning">En proceso</span>';
                $acciones = '<button class="btn btn-danger btn-sm" onclick="terminar_proyecto('.$reg->idproyecto.')" data-toggle="tooltip" data-original-title="Terminar proyecto" /*style="margin-right: 3px !important;"*/><i class="fas fa-times"></i></button>';
              } else {

                $estado = '<span class="text-center badge badge-success">Terminado</span>';
                $acciones = '<button class="btn btn-primary btn-sm" onclick="reiniciar_proyecto('.$reg->idproyecto.')" data-toggle="tooltip" data-original-title="Reiniciar proyecto" /*style="margin-right: 3px !important;"*/><i class="fas fa-sync-alt"></i></button>';
              }                
            }

            if (strlen($reg->empresa) >= 20 ) { $empresa = substr($reg->empresa, 0, 20).'...';  } else { $empresa = $reg->empresa; }

            if (strlen($reg->ubicacion) >= 20 ) { $ubicacion = substr($reg->ubicacion, 0, 20).'...';  } else { $ubicacion = $reg->ubicacion; }

            if (strlen($reg->nombre_proyecto) >= 21 ) { $nombre_proyecto = substr($reg->nombre_proyecto, 0, 21).'...'; } else { $nombre_proyecto = $reg->nombre_proyecto; }
              
            $abrir_proyecto = "'$reg->idproyecto', '$reg->nombre_codigo', '$reg->fecha_inicio', '$reg->fecha_fin'";

            $docs= "'$reg->doc1_contrato_obra', '$reg->doc2_entrega_terreno', '$reg->doc3_inicio_obra', '$reg->doc4_presupuesto', '$reg->doc5_analisis_costos_unitarios', '$reg->doc6_insumos'";
            
            $tool = '"tooltip"';   $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>";                

            $data[]=array(
              "0"=>$cont++,
              "1"=>'<div class="asignar_paint_'.$reg->idproyecto.'"> 
                <button class="btn bg-secondary btn-sm"  onclick="abrir_proyecto('.$abrir_proyecto.')" data-toggle="tooltip" data-original-title="Abrir proyecto" id="icon_folder_'.$reg->idproyecto.'">
                  <i class="fas fa-folder"></i>
                </button> 
                <button class="btn btn-warning btn-sm" onclick="mostrar('.$reg->idproyecto.')" data-toggle="tooltip" data-original-title="Editar" /*style="margin-right: 3px !important;"*/>
                  <i class="fas fa-pencil-alt"></i> 
                </button>
                '.$acciones.'
                <button class="btn bg-info btn-sm" onclick="mostrar_detalle('.$reg->idproyecto.')" data-toggle="tooltip" data-original-title="Ver detalle proyecto">
                  <i class="fas fa-eye"></i>
                </button> 
              </div>',
              "2"=>'<div class="user-block asignar_paint_'.$reg->idproyecto.'">
                <img class="img-circle" src="../dist/svg/empresa-logo.svg" alt="User Image">
                <span class="username"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'. $empresa .'</p></span>
                <span class="description">'. $reg->tipo_documento .': '. $reg->numero_documento .' </span>
              </div>',              
              "3"=> '<div class="asignar_paint_'.$reg->idproyecto.'">  <span class="description" >'.$reg->nombre_codigo.'</span> </div>' ,
              "4"=> '<div class="asignar_paint_'.$reg->idproyecto.'">'. $ubicacion.'</div>',             
              "5"=> '<div class="asignar_paint_'.$reg->idproyecto.'"> <div class="justify-content-between"><span><b> S/. </b></span> <span >'. number_format($reg->costo, 2, '.', ',').'</span></div> </div>',
              "6"=> $reg->empresa,
              "7"=> $reg->tipo_documento . ': '. $reg->numero_documento,
              "8"=> $reg->ubicacion,
              "9"=> $reg->fecha_inicio,
              "10"=> $reg->fecha_fin,
              "11"=> $reg->plazo,
              "12"=> $reg->dias_habiles,
              "13"=> $reg->fecha_valorizacion,
              "14"=> $reg->fecha_pago_obrero,
              "15"=> ($reg->permanente_pago_obrero) ? 'SI' : 'NO',
              "16"=>'<div class="asignar_paint_'.$reg->idproyecto.'">
                <center>
                  <a type="btn btn-danger" class=""  href="#"  onclick="ver_modal_docs('.$docs.')"data-toggle="tooltip" data-original-title="Ver documentos" >
                    <img src="../dist/svg/pdf.svg" class="card-img-top" height="35" width="30" >
                  </a>
                </center>
              </div>',                  
              "17"=> '<div class="asignar_paint_'.$reg->idproyecto.'">'. $estado.'</div>'.$toltip
            );
          }
          $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
            "data"=>$data);
          echo json_encode($results);          
          
        break;
        
        case 'listar-proyectos-terminados':

          $rspta=$proyecto->listar_proyectos_terminados();
          //Vamos a declarar un array
          $data= Array();
          $cont=1;

          while ($reg=$rspta->fetch_object()){

            $estado = "";
            $acciones = "";

            if ($reg->estado == '2') {

              $estado = '<span class="text-center badge badge-danger">No empezado</span>';
              $acciones = '<button class="btn btn-success btn-sm" onclick="empezar_proyecto('.$reg->idproyecto.')" data-toggle="tooltip" data-original-title="Empezar proyecto" /*style="margin-right: 3px !important;"*/><i class="fa fa-check"></i></button>';
            } else {

              if ($reg->estado == '1') {

                $estado = '<span class="text-center badge badge-warning">En proceso</span>';
                $acciones = '<button class="btn btn-danger btn-sm" onclick="terminar_proyecto('.$reg->idproyecto.')" data-toggle="tooltip" data-original-title="Terminar proyecto" /*style="margin-right: 3px !important;"*/><i class="fas fa-times"></i></button>';
              } else {

                $estado = '<span class="text-center badge badge-success">Terminado</span>';
                $acciones = '<button class="btn btn-primary btn-sm" onclick="reiniciar_proyecto('.$reg->idproyecto.')" data-toggle="tooltip" data-original-title="Reiniciar proyecto" /*style="margin-right: 3px !important;"*/><i class="fas fa-sync-alt"></i></button>';
              }                
            }

            if (strlen($reg->empresa) >= 20 ) { $empresa = substr($reg->empresa, 0, 20).'...';  } else { $empresa = $reg->empresa; }

            if (strlen($reg->ubicacion) >= 20 ) { $ubicacion = substr($reg->ubicacion, 0, 20).'...';  } else { $ubicacion = $reg->ubicacion; }

            if (strlen($reg->nombre_proyecto) >= 21 ) { $nombre_proyecto = substr($reg->nombre_proyecto, 0, 21).'...'; } else { $nombre_proyecto = $reg->nombre_proyecto; }
              
              $abrir_proyecto = "'$reg->idproyecto', '$reg->nombre_codigo', '$reg->fecha_inicio', '$reg->fecha_fin'";

              $docs= "'$reg->doc1_contrato_obra', '$reg->doc2_entrega_terreno', '$reg->doc3_inicio_obra', '$reg->doc4_presupuesto', '$reg->doc5_analisis_costos_unitarios', '$reg->doc6_insumos'";
              
              $tool = '"tooltip"';   $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>";                

              $data[]=array(
                "0"=>$cont++,
                "1"=>'<div class="asignar_paint_'.$reg->idproyecto.'"> 
                  <button class="btn bg-secondary btn-sm"  onclick="abrir_proyecto('.$abrir_proyecto.')" data-toggle="tooltip" data-original-title="Abrir proyecto" id="icon_folder_'.$reg->idproyecto.'">
                    <i class="fas fa-folder"></i>
                  </button> 
                  <button class="btn btn-warning btn-sm" onclick="mostrar('.$reg->idproyecto.')" data-toggle="tooltip" data-original-title="Editar" /*style="margin-right: 3px !important;"*/>
                    <i class="fas fa-pencil-alt"></i> 
                  </button>
                  '.$acciones.'
                  <button class="btn bg-info btn-sm" onclick="mostrar_detalle('.$reg->idproyecto.')" data-toggle="tooltip" data-original-title="Ver detalle proyecto">
                    <i class="fas fa-eye"></i>
                  </button> 
                </div>',
                "2"=>'<div class="user-block asignar_paint_'.$reg->idproyecto.'">
                  <img class="img-circle" src="../dist/svg/empresa-logo.svg" alt="User Image">
                  <span class="username"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'. $empresa .'</p></span>
                  <span class="description">'. $reg->tipo_documento .': '. $reg->numero_documento .' </span>
                </div>',              
                "3"=> '<div class="asignar_paint_'.$reg->idproyecto.'">  <span class="description" >'.$reg->nombre_codigo.'</span> </div>' ,
                "4"=> '<div class="asignar_paint_'.$reg->idproyecto.'">'. $ubicacion.'</div>',             
                "5"=> '<div class="asignar_paint_'.$reg->idproyecto.'"> <div class="justify-content-between"><span><b> S/. </b></span> <span >'. number_format($reg->costo, 2, '.', ',').'</span></div> </div>',
                "6"=> $reg->empresa,
                "7"=> $reg->tipo_documento . ': '. $reg->numero_documento,
                "8"=> $reg->ubicacion,
                "9"=> $reg->fecha_inicio,
                "10"=> $reg->fecha_fin,
                "11"=> $reg->plazo,
                "12"=> $reg->dias_habiles,
                "13"=> $reg->fecha_valorizacion,
                "14"=> $reg->fecha_pago_obrero,
                "15"=> ($reg->permanente_pago_obrero) ? 'SI' : 'NO',
                "16"=>'<div class="asignar_paint_'.$reg->idproyecto.'">
                  <center>
                    <a type="btn btn-danger" class=""  href="#"  onclick="ver_modal_docs('.$docs.')"data-toggle="tooltip" data-original-title="Ver documentos" >
                      <img src="../dist/svg/pdf.svg" class="card-img-top" height="35" width="30" >
                    </a>
                  </center>
                </div>',                  
                "17"=> '<div class="asignar_paint_'.$reg->idproyecto.'">'. $estado.'</div>'.$toltip
              );
          }
          $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
            "data"=>$data);
          echo json_encode($results);          
          
        break;

        case 'listar_feriados':

          $rspta=$proyecto->listar_feriados($idproyecto);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);	

        break;

        case 'mostrar-rango-fechas-feriadas':

          $fecha_i = $_POST["fecha_i"]; $fecha_f = $_POST["fecha_f"];

          $rspta=$proyecto->listar_rango_feriados($fecha_i, $fecha_f);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);	

        break;

        case 'fecha_fin-es-feriado':

          $fecha_f = $_POST["fecha_fin"];
          
        break;

        // buscar datos de RENIEC
        case 'reniec':

          $dni = $_POST["dni"];

          $rspta = $proyecto->datos_reniec($dni);

          echo json_encode($rspta);

        break;
        // buscar datos de SUNAT
        case 'sunat':

          $ruc = $_POST["ruc"];

          $rspta = $proyecto->datos_sunat($ruc);

          echo json_encode($rspta);

        break;
        
      }

    }else {
      require 'noacceso.php';
    }
  }

  function quitar_formato_miles($number) {

    $sin_format = 0;

    if ( !empty($number) ) { $sin_format = floatval(str_replace(",", "", $number)); }
    
    return $sin_format;
  }

  // convierte de una fecha(dd-mm-aa): 23-12-2021 a una fecha(aa-mm-dd): 2021-12-23
  function format_a_m_d( $fecha ) {

    if (!empty($fecha)) {

      $fecha_expl = explode("-", $fecha);

      $fecha_convert =  $fecha_expl[2]."-".$fecha_expl[1]."-".$fecha_expl[0];

    }else{

      $fecha_convert = "";
    }   

    return $fecha_convert;
  }

  ob_end_flush();
?>