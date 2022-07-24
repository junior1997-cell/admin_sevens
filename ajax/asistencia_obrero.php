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

      require_once "../modelos/Asistencia_obrero.php";

      $asistencia_obrero=new Asistencia_obrero();  
      
      date_default_timezone_set('America/Lima');
      $date_now = date("d-m-Y h.i.s A");
      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

      // :::::::::::::::::::::::::::::::::::: D A T O S  A S I S T E N C I A ::::::::::::::::::::::::::::::::::::::   
      $detalle_adicional	= isset($_POST["detalle_adicional"])? limpiarCadena($_POST["detalle_adicional"]):"";

      // :::::::::::::::::::::::::::::::::::: D A T O S   J U S  T I F I C A C I O N ::::::::::::::::::::::::::::::::::::::
      $idasistencia_trabajador_j	= isset($_POST["idasistencia_trabajador_j"])? limpiarCadena($_POST["idasistencia_trabajador_j"]):"";
      $detalle_j	= isset($_POST["detalle_j"])? limpiarCadena($_POST["detalle_j"]):"";
      $doc1	= isset($_POST["doc1"])? $_POST["doc1"]:"";

      // :::::::::::::::::::::::::::::::::::: D A T O S   F E C H A S   D E   A C T I V I D A D E S ::::::::::::::::::::::::::::::::::::::
      $id_proyecto_f	= isset($_POST["id_proyecto_f"])? limpiarCadena($_POST["id_proyecto_f"]):"";
      $fecha_inicio_actividad	= isset($_POST["fecha_inicio_actividad"])? limpiarCadena($_POST["fecha_inicio_actividad"]):"";
      $fecha_fin_actividad	= isset($_POST["fecha_fin_actividad"])? limpiarCadena($_POST["fecha_fin_actividad"]):"";
      $plazo_actividad	= isset($_POST["plazo_actividad"])? limpiarCadena($_POST["plazo_actividad"]):"";
      
      switch ($_GET["op"]){
        // Gurdamos cada dia de asistencia del OBRERO
        case 'guardaryeditar':

          $data_asistencia = $_POST["asistencia"];  $resumen_qs = $_POST["resumen_qs"]; $fecha_i = $_POST["fecha_inicial"]; $fecha_f = $_POST["fecha_final"];
                     
          $rspta=$asistencia_obrero->insertar_asistencia_y_resumen_q_s_asistencia( $data_asistencia, $resumen_qs, $fecha_i, $fecha_f);

          echo json_encode($rspta, true);        
          
        break;        

        case 'mostrar_editar':

          $rspta=$asistencia_obrero->mostrar($idasistencia_trabajador);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);

        break;        

        case 'ver_datos_quincena':
          
          $f1 = $_POST["f1"];
          $f2 = $_POST["f2"];
          $nube_idproyect = $_POST["nube_idproyect"];
          // $f1 = '2021-07-09'; $f2 = '2021-07-23'; $nube_idproyect = '1';

          $rspta=$asistencia_obrero->ver_detalle_quincena($f1,$f2,$nube_idproyect);

          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);		
        break;
        
        // listamos los botones de la quincena o semana
        case 'listarquincenas_botones':

          $nube_idproyecto = $_POST["nube_idproyecto"];

          $rspta=$asistencia_obrero->listarquincenas_botones($nube_idproyecto);
          
          echo json_encode($rspta, true);	 //Codificar el resultado utilizando json

        break;

        // lista la tabla principal 
        case 'tbla_principal':

          $nube_idproyecto = $_GET["nube_idproyecto"];
          
          $rspta=$asistencia_obrero->tbla_principal($nube_idproyecto);
          //Vamos a declarar un array
          $data= Array(); $cont = 1;

          $jornal_diario = '';  $sueldo_acumudado=''; $imagen_error = "this.src='../dist/svg/user_default.svg'";
          
          foreach ($rspta['data'] as $key => $value) {

            $ver_asistencia="'".$value['idtrabajador_por_proyecto']."','".$value['fecha_inicio_proyect']."'";

            $data[]=array(
              "0"=> $cont++,
              "1"=>'<center><button class="btn btn-info btn-sm" onclick="tabla_qs_individual('.$value['idtrabajador_por_proyecto'].')">
                <i class="far fa-calendar-alt"></i>
              </button>
              <button class="btn btn-info btn-sm" onclick="ver_asistencias_individual('.$ver_asistencia.')">
                <i class="far fa-clock"></i>
              </button></center>',
              "2"=>'<div class="user-block text-nowrap">
                <img class="img-circle" src="../dist/docs/all_trabajador/perfil/'. $value['imagen'] .'" alt="User Image" onerror="'.$imagen_error.'">
                <span class="username" ><p class="text-primary mb-0" >
                  <b class="text-dark-0"  >'. 
                    $value['cargo'] .' - <span class="font-size-14px text-muted font-weight-normal" >'. $value['tipo_doc'] .': '. $value['num_doc'] .' </span>
                  </b> <br>'. $value['nombre'] .'</p>
                </span>                
              </div>',              
              "3"=> '<center>' . round($value['total_horas_normal'] + $value['total_horas_extras'], 2) . '</center>',
              "4"=> '<center>' . number_format(($value['total_horas_normal'] + $value['total_horas_extras'])/8, 2, '.', ',') . '</center>',
              "5"=> 'S/ '.$value['sueldo_hora'],
              "6"=> 'S/ '.$value['sueldo_diario'],
              "7"=> 'S/ '.number_format($value['sueldo_mensual'], 2, '.', ','),              
              "8"=> '<center>' . $value['total_sabatical'] . '</center>',
              "9"=> 'S/ ' . number_format($value['adicional_descuento'], 2, '.', ','),
              "10"=> 'S/ ' . number_format($value['pago_quincenal'], 2, '.', ',') ,
              "11"=> $value['cargo'] ,
              "12"=> $value['nombre'] ,
              "13"=> $value['tipo_doc'] .': '. $value['num_doc'] ,
            );

            $jornal_diario=0;

            $sueldo_acumudado=0;
          }           

          $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
            "data"=>$data
          );

          echo json_encode($results, true);
          // echo $rspta;

        break;

        case 'suma_total_acumulado':
          $rspta=$asistencia_obrero->total_acumulado_trabajadores($_POST["nube_idproyecto"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;               

        // :::::::::::::::::::::::::::::::::::: S E C C I O N   P A G O   C O N T A D O R  ::::::::::::::::::::::::::::::::::::::

        case 'agregar_quitar_pago_al_contador':

          if (empty($_GET["idresumen_q_s_asistencia"])) {

            $rspta = ['status'=>false, 'message'=>'salio error pe', 'data' => [] ];

            echo json_encode($rspta, true);

          } else {

            $rspta = $asistencia_obrero->quitar_editar_pago_al_contador($_GET["idresumen_q_s_asistencia"], $_GET["estado_envio_contador"]);

            echo json_encode($rspta, true);
          }
          
        break; 

        case 'agregar_quitar_pago_al_contador_todos':
          $_post = json_decode(file_get_contents('php://input'),true);
          $array_pago_contador = $_post["array_pago_contador"];
          $estado_envio_contador = $_post["estado_envio_contador"];
          $rspta = $asistencia_obrero->quitar_editar_pago_al_contador_todos($array_pago_contador, $estado_envio_contador);

          echo json_encode($rspta, true);       
          
        break; 

        // :::::::::::::::::::::::::::::::::::: S E C C I O N   S A B A T I C A L  ::::::::::::::::::::::::::::::::::::::

        case 'agregar_quitar_sabatical_manual':
          $_post = json_decode(file_get_contents('php://input'),true);
          $rspta = $asistencia_obrero->insertar_quitar_editar_sabatical_manual($_post["idresumen_q_s_asistencia"], $_post["fecha_asist"], $_post["sueldo_x_hora"], $_post["fecha_q_s_inicio"], $_post["fecha_q_s_fin"], $_post["numero_q_s"], $_post["id_trabajador_x_proyecto"], $_post["numero_sabado"], $_post["estado_sabatical_manual"] );

          echo json_encode($rspta, true);
          
        break;

        case 'agregar_quitar_sabatical_manual_todos':
          $_post = json_decode(file_get_contents('php://input'),true);
          if ( empty( $_post["sabatical_trabajador"] ) ) {

            $rspta = ['status'=>false, 'message'=>'salio error pe', 'data' => [] ];

            echo json_encode($rspta, true);

          } else {

            $rspta = $asistencia_obrero->insertar_quitar_sabatical_manual_todos($_post["sabatical_trabajador"], $_post["estado_sabatical_manual"] );

            echo json_encode($rspta, true);
          }
          
        break;

        // :::::::::::::::::::::::::::::::::::: S E C C I O N   D I A S   P O R   T R A B A J A D O R ::::::::::::::::::::::::::::::::::::::

        case 'listar_asis_individual':

          $idtrabajador_x_proyecto = $_GET["idtrabajadorproyecto"];
          
          $rspta=$asistencia_obrero->tbla_asis_individual($idtrabajador_x_proyecto);
          //Vamos a declarar un array
          $data= Array(); 
          
          $imagen_error = "this.src='../dist/svg/user_default.svg'";
          
          while ($reg=$rspta['data']->fetch_object()){              

            $justificacion = "'".encodeCadenaHtml($reg->trabajador)."',$reg->idasistencia_trabajador, $reg->horas_normal_dia, '$reg->estado'";
            $imagen_perfil = (empty($reg->imagen_perfil) ? '../dist/svg/user_default.svg' : "../dist/docs/all_trabajador/perfil/$reg->imagen_perfil" );
            
            $data[]=array(
              "0"=> (empty($reg->descripcion_justificacion)) ? '<button class="btn btn-outline-info btn-sm" onclick="justificar('.$justificacion.')" data-toggle="tooltip" data-original-title="Justificacion Vacía"><i class="far fa-flag"></i></button>' : '<button class="btn btn-info btn-sm" onclick="justificar('.$justificacion.')" data-toggle="tooltip" data-original-title="Justificado"><i class="far fa-flag"></i></button>',
              "1"=> '<div class="user-block text-nowrap">
                <img class="img-circle" src="'.$imagen_perfil.'" alt="User Image" onerror="'.$imagen_error.'">
                <span class="username" ><p class="text-primary m-b-02rem" > '.$reg->trabajador .'</p></span>
                <span class="description" > <b>'. $reg->tipo_doc .'</b>: '. $reg->num_doc .' </span>
              </div>',
              "2"=> $reg->horas_normal_dia,
              "3"=> 'S/ '. $reg->pago_normal_dia,
              "4"=> $reg->horas_extras_dia,
              "5"=> 'S/ '. $reg->pago_horas_extras,
              "6"=> '<b>Fecha: </b>'. format_d_m_a($reg->fecha_asistencia) ."<br> <b>Día: </b>". $reg->nombre_dia,
              "7"=>($reg->estado?'<span class="text-center badge badge-success">Activado</span>' : '<span class="text-center badge badge-danger">Desactivado</span>').$toltip
            );
          }

          $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
            "data"=>$data
          );

          echo json_encode($results, true);
        break;

        case 'desactivar_dia':

          $rspta=$asistencia_obrero->desactivar_dia($idasistencia_trabajador);

          echo json_encode($rspta, true);

        break;

        case 'activar_dia':

          $rspta=$asistencia_obrero->activar_dia($idasistencia_trabajador);

          echo json_encode($rspta, true);

        break;  

        // :::::::::::::::::::::::::::::::::::: S E C C I O N   J U S T I F I C A C I Ó N  ::::::::::::::::::::::::::::::::::::::
        
        case 'guardar_y_editar_justificacion':
          	
          //*DOC 2*//
          if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {

            $flat_doc1 = false;

            $doc1      = $_POST["doc_old_1"];

          } else {

            $flat_doc1 = true;

            $ext_doc1  = explode(".", $_FILES["doc1"]["name"]);
              
            $doc1 = $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext_doc1);

            move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/asistencia_obrero/justificacion/" . $doc1);
            
          }	

          // registramos un nuevo: recibo x honorario
          if (empty($idasistencia_trabajador_j)){

            $rspta = '0';
            
            echo json_encode($rspta, true);

          }else {

            // eliminados si existe el "doc en la BD"
            if ($flat_doc1 == true) {

              $datos_f1 = $asistencia_obrero->imgJustificacion($idasistencia_trabajador_j);

              $doc1_ant = $datos_f1['data']->fetch_object()->doc_justificacion;

              if ( !empty($doc1_ant) ) {

                unlink("../dist/docs/asistencia_obrero/justificacion/" . $doc1_ant);
              }
            }

            // editamos un recibo x honorario existente
            $rspta=$asistencia_obrero->editar_justificacion($idasistencia_trabajador_j, $detalle_j, $doc1);
            
            echo json_encode($rspta, true);
          }

        break;

        case 'mostrar_justificacion':

          $rspta=$asistencia_obrero->mostrar_justificacion($_POST["idasistencia_trabajador"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);

        break;

        // :::::::::::::::::::::::::::::::::::: S E C C I O N   Q-S  P O R   T R A B A J A D O R  ::::::::::::::::::::::::::::::::::::::

        case 'tabla_qs_individual':

          $idtrabajador_x_proyecto = $_GET["idtrabajadorproyecto"];
          
          $rspta=$asistencia_obrero->tbla_qs_individual($idtrabajador_x_proyecto);
          //Vamos a declarar un array
          $data= Array(); 
          
          $imagen_error = "this.src='../dist/svg/user_default.svg'";
          $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

          if ($rspta['status'] == true) {
            while ($reg=$rspta['data']->fetch_object()){            

              $pago = ($reg->fecha_pago_obrero = 'quincenal') ? 'Quincena' : 'Semana ' ;
  
              $opciones = "'$reg->idresumen_q_s_asistencia', '$pago' ";
  
              $data[]=array(
                "0"=> '<center>' . ($reg->estado ? '<button class="btn btn-danger btn-sm" onclick="desactivar_qs('. $opciones .')" data-toggle="tooltip" data-original-title="Desactivar"><i class="fas fa-trash-alt"></i></button>' :
                  '<button class="btn btn-success btn-sm" onclick="activar_qs('. $opciones .')" data-toggle="tooltip" data-original-title="Activar"><i class="fas fa-check"></i></button>') . '</center>' ,
                 
                "1"=> '<center><b>' . $reg->numero_q_s . '</b> ─ '. format_d_m_a($reg->fecha_q_s_inicio) . ' - ' . format_d_m_a($reg->fecha_q_s_fin) . '</center>',
                "2"=> $reg->total_hn . ' / ' . $reg->total_he,
                "3"=> '<center>' . $reg->total_dias_asistidos . '</center>',
                "4"=> 'S/ '. number_format($reg->pago_parcial_hn, 2, '.', ',') . ' / ' . number_format($reg->pago_parcial_he, 2, '.', ','),
                "5"=> 'S/ '. number_format($reg->adicional_descuento, 2, '.', ','),
                "6"=> '<center>' . $reg->sabatical . '</center>',
                "7"=> 'S/ '. number_format($reg->pago_quincenal, 2, '.', ','),
                "8"=> '<center>' . ($reg->estado_envio_contador ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times text-danger"></i>') . '</center>' ,
                "9"=> '<center>' . ($reg->estado?'<span class="text-center badge badge-success">Activado</span>' : '<span class="text-center badge badge-danger">Desactivado</span>') . '</center>'.$toltip,
                "10"=> $reg->trabajdor,
                "11"=> $reg->tipo_documento . ': ' . $reg->numero_documento,
                "12"=> $reg->numero_q_s
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

        case 'suma_qs_individual':           

          $rspta=$asistencia_obrero->suma_qs_individual($_POST["idtrabajadorproyecto"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);		

        break;

        case 'desactivar_qs':

          $rspta=$asistencia_obrero->desactivar_qs($_GET["idresumen_q_s_asistencia"]);

          echo json_encode($rspta, true);

        break;

        case 'activar_qs':

          $rspta=$asistencia_obrero->activar_qs($_GET["idresumen_q_s_asistencia"]);

          echo json_encode($rspta, true);

        break;

        // :::::::::::::::::::::::::::::::::::: S E C C I O N   A D I C I O N A L   D E S C U E N T O ::::::::::::::::::::::::::::::::::::::
        
        // Agregamos o editamos el detalle adicional de: "resumen_q_s_asistencia"
        case 'guardaryeditar_adicional_descuento':

          if (empty($_POST["idresumen_q_s_asistencia"])) {

            $rspta = $asistencia_obrero->insertar_detalle_adicional( $_POST["idtrabajador_por_proyecto"], $_POST["fecha_q_s"], $detalle_adicional);

            echo json_encode($rspta, true);

          } else {

            $rspta = $asistencia_obrero->editar_detalle_adicionales($_POST["idresumen_q_s_asistencia"], $_POST["idtrabajador_por_proyecto"], $_POST["fecha_q_s"],$_POST["detalle_adicional"]);

            echo json_encode($rspta, true);
          }
          
        break;

        case 'descripcion_adicional_descuento':
          if ( empty($_POST["id_adicional"]) ) {
            $rspta = ['status'=>true, 'message'=>'no hay id, que lastima', 'data'=>[], ];
            echo json_encode($rspta, true);
          } else {
            $rspta=$asistencia_obrero->descripcion_adicional_descuento($_POST["id_adicional"]);
            //Codificar el resultado utilizando json
            echo json_encode($rspta, true);
          }          
        break;

        // :::::::::::::::::::::::::::::::::::: S E C C I O N   F E C H A S   A C T I V I D A D ::::::::::::::::::::::::::::::::::::::
        case 'fechas_actividad':

          $rspta=$asistencia_obrero->fechas_actividad($_POST["id_proyecto"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;

        case 'guardar_y_editar_fechas_actividad':

          // registramos un nuevo: recibo x honorario
          if (empty($id_proyecto_f)){

            $rspta = ['status' => false, 'message' => 'salio error pe', 'data' => [],];
            
            echo json_encode($rspta, true);

          }else {             

            // editamos un recibo x honorario existente
            $rspta=$asistencia_obrero->editar_fechas_actividad($id_proyecto_f, format_d_m_a($fecha_inicio_actividad), format_d_m_a($fecha_fin_actividad), $plazo_actividad);
            
            echo json_encode($rspta, true);
          }

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