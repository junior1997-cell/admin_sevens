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
    if ($_SESSION['escritorio'] == 1) {

      require_once "../modelos/Proyecto.php";

      $proyecto = new Proyecto();

      date_default_timezone_set('America/Lima'); $date_now = date("d-m-Y h.i.s A");

      $scheme_host =  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/admin_sevens/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');

      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

      $idproyecto				    = isset($_POST["idproyecto"])? limpiarCadena($_POST["idproyecto"]):""; 
      $tipo_documento			  = isset($_POST["tipo_documento"])? limpiarCadena($_POST["tipo_documento"]):"";
      $numero_documento		  = isset($_POST["num_documento"])? limpiarCadena($_POST["num_documento"]):"";
      $empresa				      = isset($_POST["empresa"])? limpiarCadena($_POST["empresa"]):"";
      $nombre_proyecto		  = isset($_POST["nombre_proyecto"])? limpiarCadena($_POST["nombre_proyecto"]):"";
      $nombre_codigo		    = isset($_POST["nombre_codigo"])? limpiarCadena($_POST["nombre_codigo"]):"";
      $ubicacion				    = isset($_POST["ubicacion"])? limpiarCadena($_POST["ubicacion"]):"";
      $actividad_trabajo		= isset($_POST["actividad_trabajo"])? limpiarCadena($_POST["actividad_trabajo"]):"";
      $empresa_acargo 		  = isset($_POST['empresa_acargo'])? limpiarCadena($_POST['empresa_acargo']):"";
      $costo					      = isset($_POST["costo"])? limpiarCadena($_POST["costo"]):"";
      $garantia					    = isset($_POST["garantia"])? limpiarCadena($_POST["garantia"]):"";
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
              
            $doc1 = $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext_doc1);

            move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/valorizacion/documento/" . $doc1);
            
          }	

          //*DOC 2*//
          if (!file_exists($_FILES['doc2']['tmp_name']) || !is_uploaded_file($_FILES['doc2']['tmp_name'])) {

            $flat_doc2 = false;

            $doc2      = $_POST["doc_old_2"];

          } else {

            $flat_doc2 = true;

            $ext_doc2     = explode(".", $_FILES["doc2"]["name"]);
              
            $doc2 = $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext_doc2);

            move_uploaded_file($_FILES["doc2"]["tmp_name"], "../dist/docs/valorizacion/documento/" . $doc2);
            
          }	

          //*DOC 3*//
          if (!file_exists($_FILES['doc3']['tmp_name']) || !is_uploaded_file($_FILES['doc3']['tmp_name'])) {

            $flat_doc3 = false;

            $doc3      = $_POST["doc_old_3"];

          } else {

            $flat_doc3 = true;

            $ext_doc3     = explode(".", $_FILES["doc3"]["name"]);
              
            $doc3 = $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext_doc3);

            move_uploaded_file($_FILES["doc3"]["tmp_name"], "../dist/docs/valorizacion/documento/" . $doc3);
            
          }	

          //*DOC 4*//
          if (!file_exists($_FILES['doc4']['tmp_name']) || !is_uploaded_file($_FILES['doc4']['tmp_name'])) {

            $flat_doc4 = false;

            $doc4      = $_POST["doc_old_4"];

          } else {

            $flat_doc4 = true;

            $ext_doc4     = explode(".", $_FILES["doc4"]["name"]);
              
            $doc4 = $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext_doc4);

            move_uploaded_file($_FILES["doc4"]["tmp_name"], "../dist/docs/valorizacion/documento/" . $doc4);
            
          }	

          //*DOC 5*//
          if (!file_exists($_FILES['doc5']['tmp_name']) || !is_uploaded_file($_FILES['doc5']['tmp_name'])) {

            $flat_doc5 = false;

            $doc5      = $_POST["doc_old_5"];

          } else {

            $flat_doc5 = true;

            $ext_doc5     = explode(".", $_FILES["doc5"]["name"]);
              
            $doc5 = $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext_doc5);

            move_uploaded_file($_FILES["doc5"]["tmp_name"], "../dist/docs/valorizacion/documento/" . $doc5);
            
          }	

          //*DOC 6*//
          if (!file_exists($_FILES['doc6']['tmp_name']) || !is_uploaded_file($_FILES['doc6']['tmp_name'])) {

            $flat_doc6 = false;

            $doc6      = $_POST["doc_old_6"];

          } else {

            $flat_doc6 = true;

            $ext_doc6     = explode(".", $_FILES["doc6"]["name"]);
            
            // echo json_encode($_FILES['doc6']); 
            $doc6 = $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext_doc6);

            move_uploaded_file($_FILES["doc6"]["tmp_name"], "../dist/docs/valorizacion/documento/" . $doc6);
            
          }          

          if (empty($idproyecto)){
            // insertamos en la bd
            $rspta=$proyecto->insertar($tipo_documento, $numero_documento, $empresa, $nombre_proyecto, $nombre_codigo, $ubicacion, $actividad_trabajo, $empresa_acargo, quitar_formato_miles($costo), $garantia, $fecha_inicio_actividad, $fecha_fin_actividad, $plazo_actividad, $fecha_inicio, $fecha_fin, $plazo, $dias_habiles, $doc1, $doc2, $doc3, $doc4, $doc5, $doc6, $fecha_pago_obrero, $fecha_valorizacion, $permanente_pago_obrero);
            // echo $rspta ;
            echo json_encode($rspta, true);

          } else {
            // validamos si existe el doc para eliminarlo
            if ($flat_doc1 == true) {

              $datos_f1 = $proyecto->obtenerDocs($idproyecto);

              $doc1_ant = $datos_f1['data']['doc1_contrato_obra'];

              if (validar_url_completo($scheme_host. "dist/docs/valorizacion/documento/" . $doc1_ant)  == 200) {
                unlink("../dist/docs/valorizacion/documento/" . $doc1_ant);
              }
            }

            if ($flat_doc2 == true) {

              $datos_f2 = $proyecto->obtenerDocs($idproyecto);

              $doc2_ant = $datos_f2['data']['doc2_entrega_terreno'];

              if (validar_url_completo($scheme_host. "dist/docs/valorizacion/documento/" . $doc2_ant)  == 200) {
                unlink("../dist/docs/valorizacion/documento/" . $doc2_ant);
              }
            }

            if ($flat_doc3 == true) {

              $datos_f3 = $proyecto->obtenerDocs($idproyecto);

              $doc3_ant = $datos_f3['data']['doc3_inicio_obra'];

              if (validar_url_completo($scheme_host. "dist/docs/valorizacion/documento/" . $doc3_ant)  == 200) {
                unlink("../dist/docs/valorizacion/documento/" . $doc3_ant);
              }
            }

            if ($flat_doc4 == true) {

              $datos_f4 = $proyecto->obtenerDocs($idproyecto);

              $doc4_ant = $datos_f4['data']['doc4_presupuesto'];

              if (validar_url_completo($scheme_host. "dist/docs/valorizacion/documento/" . $doc4_ant)  == 200) {
                unlink("../dist/docs/valorizacion/documento/" . $doc4_ant);
              }
            }

            if ($flat_doc5 == true) {

              $datos_f5 = $proyecto->obtenerDocs($idproyecto);

              $doc5_ant = $datos_f5['data']['doc5_analisis_costos_unitarios'];

              if (validar_url_completo($scheme_host. "dist/docs/valorizacion/documento/" . $doc5_ant)  == 200) {
                unlink("../dist/docs/valorizacion/documento/" . $doc5_ant);
              }
            }

            if ($flat_doc6 == true) {

              $datos_f6 = $proyecto->obtenerDocs($idproyecto);

              $doc6_ant = $datos_f6['data']['doc6_insumos'];

              if (validar_url_completo($scheme_host. "dist/docs/valorizacion/documento/" . $doc6_ant)  == 200) {
                unlink("../dist/docs/valorizacion/documento/" . $doc6_ant);
              }
            }

            $rspta=$proyecto->editar($idproyecto, $tipo_documento, $numero_documento, $empresa, $nombre_proyecto, $nombre_codigo, $ubicacion, $actividad_trabajo, $empresa_acargo, quitar_formato_miles($costo), $garantia, $fecha_inicio_actividad, $fecha_fin_actividad, $plazo_actividad, $fecha_inicio, $fecha_fin, $plazo, $dias_habiles, $doc1, $doc2, $doc3, $doc4, $doc5, $doc6, $fecha_pago_obrero, $fecha_valorizacion, $permanente_pago_obrero);
            
            echo json_encode( $rspta, true);
          }
            
        break;
      
        case 'empezar_proyecto':

          $rspta=$proyecto->empezar_proyecto($_GET["id_tabla"]);

          echo json_encode($rspta, true);	

        break;

        case 'terminar_proyecto':

          $rspta=$proyecto->terminar_proyecto($_GET["id_tabla"]);

          echo json_encode($rspta, true);	

        break;

        case 'reiniciar_proyecto':

          $rspta=$proyecto->reiniciar_proyecto($_GET["id_tabla"]);

          echo json_encode($rspta, true);	

        break;

        case 'mostrar':

          $rspta=$proyecto->mostrar($idproyecto);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);	

        break;

        case 'tablero':
          $rspta=$proyecto->tablero();
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);	
        break;

        case 'box_proyecto':
          $rspta=$proyecto->box_proyecto();
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);	
        break;

        case 'tbla_principal':

          $rspta = $proyecto->tbla_principal($_GET["estado"]);
          //Vamos a declarar un array
          $data= Array();  $cont=1;

          if ($rspta['status']) {
            foreach ($rspta['data'] as $key => $value) {

              $estado = ""; $acciones = "";
  
              if ($value['estado'] == '2') {  
                $estado = '<span class="text-center badge badge-danger">No empezado</span>';
                $acciones = '<button class="btn btn-success btn-sm" onclick="empezar_proyecto('.$value['idproyecto'].', \''.encodeCadenaHtml($value['nombre_codigo']).'\')" data-toggle="tooltip" data-original-title="Empezar proyecto" /*style="margin-right: 3px !important;"*/><i class="fa fa-check"></i></button>';
              } else if ($value['estado'] == '1') {  
                $estado = '<span class="text-center badge badge-warning">En proceso</span>';
                $acciones = '<button class="btn btn-danger btn-sm" onclick="terminar_proyecto('.$value['idproyecto'].', \''.encodeCadenaHtml($value['nombre_codigo']).'\')" data-toggle="tooltip" data-original-title="Terminar proyecto" /*style="margin-right: 3px !important;"*/><i class="fas fa-times"></i></button>';
              } else {  
                $estado = '<span class="text-center badge badge-success">Terminado</span>';
                $acciones = '<button class="btn btn-primary btn-sm" onclick="reiniciar_proyecto('.$value['idproyecto'].', \''.encodeCadenaHtml($value['nombre_codigo']).'\')" data-toggle="tooltip" data-original-title="Reiniciar proyecto" /*style="margin-right: 3px !important;"*/><i class="fas fa-sync-alt"></i></button>';            
              }
  
              $empresa = cortar_string($value['empresa'], 20, '...');  
              $ubicacion = cortar_string($value['ubicacion'], 20, '...');  
              $nombre_proyecto = cortar_string($value['nombre_proyecto'], 20, '...'); 
                
              $abrir_proyecto = ' \''.$value['idproyecto'].'\', \''.$value['ec_razon_social'].'\', \''.$value['nombre_codigo'].'\', \''.$value['fecha_inicio'].'\', \''.$value['fecha_fin'].'\'';
  
              $docs= '\''.$value['doc1_contrato_obra'].'\', \''.$value['doc2_entrega_terreno'].'\', \''.$value['doc3_inicio_obra'].'\', \''.$value['doc4_presupuesto'].'\', \''.$value['doc5_analisis_costos_unitarios'].'\', \''.$value['doc6_insumos'].'\'';
  
              $data[]=array(
                "0"=>$cont++,
                "1"=>'<div class="asignar_paint_'.$value['idproyecto'].'"> 
                  <button class="btn bg-secondary btn-sm" onclick="abrir_proyecto('.$abrir_proyecto.')" data-toggle="tooltip" data-original-title="Abrir proyecto" id="icon_folder_'.$value['idproyecto'].'">
                    <i class="fas fa-folder"></i>
                  </button> 
                  <button class="btn btn-warning btn-sm" onclick="mostrar('.$value['idproyecto'].')" data-toggle="tooltip" data-original-title="Editar" /*style="margin-right: 3px !important;"*/>
                    <i class="fas fa-pencil-alt"></i> 
                  </button>
                  '.$acciones.'
                  <button class="btn bg-info btn-sm" onclick="mostrar_detalle('.$value['idproyecto'].')" data-toggle="tooltip" data-original-title="Ver detalle proyecto">
                    <i class="fas fa-eye"></i>
                  </button> 
                </div>',
                "2"=>'<div class="user-block asignar_paint_'.$value['idproyecto'].'">
                  <img class="img-circle" src="../dist/svg/empresa-logo.svg" alt="User Image">
                  <span class="username"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'. $empresa .'</p></span>
                  <span class="description">'. $value['tipo_documento'] .': '. $value['numero_documento'] .' </span>
                </div>',              
                "3"=> '<div class="asignar_paint_'.$value['idproyecto'].'">  <span class="description" >'.$value['nombre_codigo'].'</span> </div>' ,
                "4"=> '<div class="asignar_paint_'.$value['idproyecto'].'">'. $ubicacion.'</div>',             
                "5"=>  number_format($value['costo'], 2, '.', ''),
                "6"=> $value['empresa'],
                "7"=> $value['tipo_documento'] . ': '. $value['numero_documento'],
                "8"=> $value['ubicacion'],
                "9"=> $value['fecha_inicio'],
                "10"=> $value['fecha_fin'],
                "11"=> $value['plazo'],
                "12"=> $value['dias_habiles'],
                "13"=> $value['fecha_valorizacion'],
                "14"=> $value['fecha_pago_obrero'],
                "15"=> ($value['permanente_pago_obrero']) ? 'SI' : 'NO',
                "16"=>'<div class="asignar_paint_'.$value['idproyecto'].'">
                  <center>
                    <a type="btn btn-danger" class=""  href="#"  onclick="ver_modal_docs('.$docs.')"data-toggle="tooltip" data-original-title="Ver documentos" >
                      <img src="../dist/svg/pdf.svg" class="card-img-top" height="35" width="30" >
                    </a>
                  </center>
                </div>',                  
                "17"=> '<div class="asignar_paint_'.$value['idproyecto'].'">'. $estado.'</div>'.$toltip
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

        case 'tbla_principal_para_todos_los_modulos':

          $rspta = $proyecto->tbla_principal($_GET["estado"]);
          //Vamos a declarar un array
          $data= Array();  $cont=1;

          if ($rspta['status']) {
            foreach ($rspta['data'] as $key => $value) {

              $estado = ""; $acciones = "";
  
              if ($value['estado'] == '2') {  
                $estado = '<span class="text-center badge badge-danger">No empezado</span>';
                $acciones = '<button class="btn btn-success btn-sm" onclick="empezar_proyecto('.$value['idproyecto'].', \''.encodeCadenaHtml($value['nombre_codigo']).'\')" data-toggle="tooltip" data-original-title="Empezar proyecto" /*style="margin-right: 3px !important;"*/><i class="fa fa-check"></i></button>';
              } else if ($value['estado'] == '1') {  
                $estado = '<span class="text-center badge badge-warning">En proceso</span>';
                $acciones = '<button class="btn btn-danger btn-sm" onclick="terminar_proyecto('.$value['idproyecto'].', \''.encodeCadenaHtml($value['nombre_codigo']).'\')" data-toggle="tooltip" data-original-title="Terminar proyecto" /*style="margin-right: 3px !important;"*/><i class="fas fa-times"></i></button>';
              } else {  
                $estado = '<span class="text-center badge badge-success">Terminado</span>';
                $acciones = '<button class="btn btn-primary btn-sm" onclick="reiniciar_proyecto('.$value['idproyecto'].', \''.encodeCadenaHtml($value['nombre_codigo']).'\')" data-toggle="tooltip" data-original-title="Reiniciar proyecto" /*style="margin-right: 3px !important;"*/><i class="fas fa-sync-alt"></i></button>';            
              }
  
              $empresa = cortar_string($value['empresa'], 20, '...');  
                  
              $abrir_proyecto = ' \''.$value['idproyecto'].'\', \''.$value['ec_razon_social'].'\', \''.$value['nombre_codigo'].'\', \''.$value['fecha_inicio'].'\', \''.$value['fecha_fin'].'\'';
  
              $docs= '\''.$value['doc1_contrato_obra'].'\', \''.$value['doc2_entrega_terreno'].'\', \''.$value['doc3_inicio_obra'].'\', \''.$value['doc4_presupuesto'].'\', \''.$value['doc5_analisis_costos_unitarios'].'\', \''.$value['doc6_insumos'].'\'';
  
              $data[]=array(
                "0"=>$cont++,
                "1"=>'<div class="asignar_paint_'.$value['idproyecto'].'"> 
                  <button class="btn bg-secondary btn-sm" onclick="abrir_proyecto_para_todos_los_modulos('.$abrir_proyecto.')" data-toggle="tooltip" data-original-title="Abrir proyecto" id="icon_folder_'.$value['idproyecto'].'">
                    <i class="fas fa-folder"></i>
                  </button> 
                </div>',
                "2"=>'<div class="user-block asignar_paint_'.$value['idproyecto'].'">                  
                  <span class="username ml-0"><p class="text-primary m-02rem" >'. $empresa .'</p></span>
                  <span class="description ml-0">'. $value['tipo_documento'] .': '. $value['numero_documento'] .' </span>
                </div>',              
                "3"=> '<div class="asignar_paint_'.$value['idproyecto'].'">  <span class="description" >'.$value['nombre_codigo'].'</span> </div>' ,
                "4"=>  number_format($value['costo'], 2, '.', ''),                 
                "5"=> '<div class="asignar_paint_'.$value['idproyecto'].'">'. $estado.'</div>'.$toltip
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
        
        case 'listar-proyectos-terminados':

          $rspta=$proyecto->listar_proyectos_terminados();
          //Vamos a declarar un array
          $data= Array();  $cont=1;

          if ($rspta['status']) {
            foreach ($rspta['data'] as $key => $value) {

              $estado = ""; $acciones = "";
  
              if ($value['estado'] == '2') {
  
                $estado = '<span class="text-center badge badge-danger">No empezado</span>';
                $acciones = '<button class="btn btn-success btn-sm" onclick="empezar_proyecto('.$value['idproyecto'].', \''.encodeCadenaHtml($value['nombre_codigo']).'\')" data-toggle="tooltip" data-original-title="Empezar proyecto" /*style="margin-right: 3px !important;"*/><i class="fa fa-check"></i></button>';
              } else {
  
                if ($value['estado'] == '1') {
  
                  $estado = '<span class="text-center badge badge-warning">En proceso</span>';
                  $acciones = '<button class="btn btn-danger btn-sm" onclick="terminar_proyecto('.$value['idproyecto'].', \''.encodeCadenaHtml($value['nombre_codigo']).'\')" data-toggle="tooltip" data-original-title="Terminar proyecto" /*style="margin-right: 3px !important;"*/><i class="fas fa-times"></i></button>';
                } else {
  
                  $estado = '<span class="text-center badge badge-success">Terminado</span>';
                  $acciones = '<button class="btn btn-primary btn-sm" onclick="reiniciar_proyecto('.$value['idproyecto'].', \''.encodeCadenaHtml($value['nombre_codigo']).'\')" data-toggle="tooltip" data-original-title="Reiniciar proyecto" /*style="margin-right: 3px !important;"*/><i class="fas fa-sync-alt"></i></button>';
                }                
              }
  
              if (strlen($value['empresa']) >= 20 ) { $empresa = substr($value['empresa'], 0, 20).'...';  } else { $empresa = $value['empresa']; }
  
              if (strlen($value['ubicacion']) >= 20 ) { $ubicacion = substr($value['ubicacion'], 0, 20).'...';  } else { $ubicacion = $value['ubicacion']; }
  
              if (strlen($value['nombre_proyecto']) >= 21 ) { $nombre_proyecto = substr($value['nombre_proyecto'], 0, 21).'...'; } else { $nombre_proyecto = $value['nombre_proyecto']; }
                
              $abrir_proyecto = ' \''.$value['idproyecto'].'\', \''.$value['nombre_codigo'].'\', \''.$value['fecha_inicio'].'\', \''.$value['fecha_fin'].'\'';
  
              $docs= '\''.$value['doc1_contrato_obra'].'\', \''.$value['doc2_entrega_terreno'].'\', \''.$value['doc3_inicio_obra'].'\', \''.$value['doc4_presupuesto'].'\', \''.$value['doc5_analisis_costos_unitarios'].'\', \''.$value['doc6_insumos'].'\'';
              
              $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';                
  
              $data[]=array(
                "0"=>$cont++,
                "1"=>'<div class="asignar_paint_'.$value['idproyecto'].'"> 
                  <button class="btn bg-secondary btn-sm"  onclick="abrir_proyecto('.$abrir_proyecto.')" data-toggle="tooltip" data-original-title="Abrir proyecto" id="icon_folder_'.$value['idproyecto'].'">
                    <i class="fas fa-folder"></i>
                  </button> 
                  <button class="btn btn-warning btn-sm" onclick="mostrar('.$value['idproyecto'].')" data-toggle="tooltip" data-original-title="Editar" /*style="margin-right: 3px !important;"*/>
                    <i class="fas fa-pencil-alt"></i> 
                  </button>
                  '.$acciones.'
                  <button class="btn bg-info btn-sm" onclick="mostrar_detalle('.$value['idproyecto'].')" data-toggle="tooltip" data-original-title="Ver detalle proyecto">
                    <i class="fas fa-eye"></i>
                  </button> 
                </div>',
                "2"=>'<div class="user-block asignar_paint_'.$value['idproyecto'].'">
                  <img class="img-circle" src="../dist/svg/empresa-logo.svg" alt="User Image">
                  <span class="username"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'. $empresa .'</p></span>
                  <span class="description">'. $value['tipo_documento'] .': '. $value['numero_documento'] .' </span>
                </div>',              
                "3"=> '<div class="asignar_paint_'.$value['idproyecto'].'">  <span class="description" >'.$value['nombre_codigo'].'</span> </div>' ,
                "4"=> '<div class="asignar_paint_'.$value['idproyecto'].'">'. $ubicacion.'</div>',             
                "5"=> '<div class="asignar_paint_'.$value['idproyecto'].'"> <div class="justify-content-between"><span><b> S/ </b></span> <span >'. number_format($value['costo'], 2, '.', ',').'</span></div> </div>',
                "6"=> $value['empresa'],
                "7"=> $value['tipo_documento'] . ': '. $value['numero_documento'],
                "8"=> $value['ubicacion'],
                "9"=> $value['fecha_inicio'],
                "10"=> $value['fecha_fin'],
                "11"=> $value['plazo'],
                "12"=> $value['dias_habiles'],
                "13"=> $value['fecha_valorizacion'],
                "14"=> $value['fecha_pago_obrero'],
                "15"=> ($value['permanente_pago_obrero']) ? 'SI' : 'NO',
                "16"=>'<div class="asignar_paint_'.$value['idproyecto'].'">
                  <center>
                    <a type="btn btn-danger" class=""  href="#"  onclick="ver_modal_docs('.$docs.')"data-toggle="tooltip" data-original-title="Ver documentos" >
                      <img src="../dist/svg/pdf.svg" class="card-img-top" height="35" width="30" >
                    </a>
                  </center>
                </div>',                  
                "17"=> '<div class="asignar_paint_'.$value['idproyecto'].'">'. $estado.'</div>'.$toltip
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

        case 'listar_feriados':

          $rspta=$proyecto->listar_feriados($idproyecto);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);	

        break;

        case 'mostrar-rango-fechas-feriadas':

          $fecha_i = $_POST["fecha_i"]; $fecha_f = $_POST["fecha_f"];

          $rspta=$proyecto->listar_rango_feriados($fecha_i, $fecha_f);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);	

        break;

        case 'fecha_fin-es-feriado':

          $fecha_f = $_POST["fecha_fin"];
          
        break;

        // buscar datos de RENIEC
        case 'reniec':

          $dni = $_POST["dni"];

          $rspta = $proyecto->datos_reniec($dni);

          echo json_encode($rspta, true);	

        break;
        // buscar datos de SUNAT
        case 'sunat':

          $ruc = $_POST["ruc"];

          $rspta = $proyecto->datos_sunat($ruc);

          echo json_encode($rspta, true);	
        break;

        default: 
          $rspta = ['status'=>'error_code', 'message'=>'Te has confundido en escribir en el <b>swich.</b>', 'data'=>[]]; echo json_encode($rspta, true); 
        break;
        
      }

    }else {
      $retorno = ['status'=>'nopermiso', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
      echo json_encode($retorno);
    }
  }

  ob_end_flush();
?>