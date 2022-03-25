<?php
ob_start();

	if (strlen(session_id()) < 1){
		session_start();//Validamos si existe o no la sesión
	}
  
  if (!isset($_SESSION["nombre"])) {

    header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.

  } else {
    //Validamos el acceso solo al usuario logueado y autorizado.
    if ($_SESSION['asistencia_obrero'] == 1) {

      require_once "../modelos/Asistencia_obrero.php";

      $asistencia_obrero=new Asistencia_obrero();      

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

          echo $rspta ? "ok" : "No se pudieron registrar todos los datos del trabajador";          
          
        break;        

        case 'mostrar_editar':

          $rspta=$asistencia_obrero->mostrar($idasistencia_trabajador);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);

        break;        

        case 'ver_datos_quincena':
          
          $f1 = $_POST["f1"];
          $f2 = $_POST["f2"];
          $nube_idproyect = $_POST["nube_idproyect"];
          // $f1 = '2021-07-09'; $f2 = '2021-07-23'; $nube_idproyect = '1';

          $rspta=$asistencia_obrero->ver_detalle_quincena($f1,$f2,$nube_idproyect);

          //Codificar el resultado utilizando json
          echo json_encode($rspta);		
        break;
        
        // listamos los botones de la quincena o semana
        case 'listarquincenas_botones':

          $nube_idproyecto = $_POST["nube_idproyecto"];

          $rspta=$asistencia_obrero->listarquincenas_botones($nube_idproyecto);
          
          echo json_encode($rspta);	 //Codificar el resultado utilizando json

        break;

        // lista la tabla principal 
        case 'tbla_principal':

          $nube_idproyecto = $_GET["nube_idproyecto"];
          
          $rspta=$asistencia_obrero->tbla_principal($nube_idproyecto);
          //Vamos a declarar un array
          $data= Array(); $cont = 1;

          $jornal_diario = '';  $sueldo_acumudado=''; $imagen_error = "this.src='../dist/svg/user_default.svg'";
          
          foreach (json_decode($rspta, true) as $key => $value) {

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

          echo json_encode($results);
          // echo $rspta;

        break;

        case 'suma_total_acumulado':
          $rspta=$asistencia_obrero->total_acumulado_trabajadores($_POST["nube_idproyecto"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);
        break;               

        // :::::::::::::::::::::::::::::::::::: S E C C I O N   P A G O   C O N T A D O R  ::::::::::::::::::::::::::::::::::::::

        case 'agregar_quitar_pago_al_contador':

          if (empty($_POST["idresumen_q_s_asistencia"])) {

            $rspta = false;

            echo $rspta ? "ok" : "No se pudieron registrar el pago al contador"; 

          } else {

            $rspta = $asistencia_obrero->quitar_editar_pago_al_contador($_POST["idresumen_q_s_asistencia"], $_POST["estado_envio_contador"]);

            echo $rspta ? "ok" : "No se pudieron realizar los cambios del pago al contador";
          }
          
        break; 

        case 'agregar_quitar_pago_al_contador_todos':          

          $rspta = $asistencia_obrero->quitar_editar_pago_al_contador_todos($_POST["array_pago_contador"], $_POST["estado_envio_contador"]);

          echo $rspta ? "ok" : "No se pudieron realizar los cambios del pago al contador";          
          
        break; 

        // :::::::::::::::::::::::::::::::::::: S E C C I O N   S A B A T I C A L  ::::::::::::::::::::::::::::::::::::::

        case 'agregar_quitar_sabatical_manual':

          $rspta = $asistencia_obrero->insertar_quitar_editar_sabatical_manual($_POST["idresumen_q_s_asistencia"], $_POST["fecha_asist"], $_POST["sueldo_x_hora"], $_POST["fecha_q_s_inicio"], $_POST["fecha_q_s_fin"], $_POST["numero_q_s"], $_POST["id_trabajador_x_proyecto"], $_POST["numero_sabado"], $_POST["estado_sabatical_manual"] );

          echo $rspta ? "ok" : "No se pudieron GUARDAR el sabatical.";          
          
        break;

        case 'agregar_quitar_sabatical_manual_todos':

          if ( empty( json_decode($_POST["sabatical_trabajador"], true) ) ) {

            echo "No hay trabajadores para asignar los sabaticales"; 

          } else {

            $rspta = $asistencia_obrero->insertar_quitar_sabatical_manual_todos($_POST["sabatical_trabajador"], $_POST["estado_sabatical_manual"] );

            echo $rspta ? "ok" : "No se pudieron GUARDAR los sabaticales";
          }
          
        break;

        // :::::::::::::::::::::::::::::::::::: S E C C I O N   D I A S   P O R   T R A B A J A D O R ::::::::::::::::::::::::::::::::::::::

        case 'listar_asis_individual':

          $idtrabajador_x_proyecto = $_GET["idtrabajadorproyecto"];
          
          $rspta=$asistencia_obrero->tbla_asis_individual($idtrabajador_x_proyecto);
          //Vamos a declarar un array
          $data= Array(); 
          
          $imagen_error = "this.src='../dist/svg/user_default.svg'";
          
          while ($reg=$rspta->fetch_object()){

            $tool = '"tooltip"';   $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>";

            $justificacion = "$reg->idasistencia_trabajador, $reg->horas_normal_dia, '$reg->estado'";

            $data[]=array(
              "0"=> (empty($reg->doc_justificacion)) ? '<button class="btn btn-outline-info btn-sm" onclick="justificar('.$justificacion.')" data-toggle="tooltip" data-original-title="Justificarse"><i class="far fa-flag"></i></button>' : '<button class="btn btn-info btn-sm" onclick="justificar('.$justificacion.')" data-toggle="tooltip" data-original-title="Justificarse"><i class="far fa-flag"></i></button>',
              "1"=> '<div class="user-block text-nowrap">
                <img class="img-circle" src="../dist/img/usuarios/'. $reg->imagen_perfil .'" alt="User Image" onerror="'.$imagen_error.'">
                <span class="username" ><p class="text-primary"style="margin-bottom: 0.2rem !important"; > '.$reg->trabajador .'</p></span>
                <span class="description" > <b>'. $reg->tipo_doc .'</b>: '. $reg->num_doc .' </span>
              </div>',
              "2"=> $reg->horas_normal_dia,
              "3"=> 'S/ '. $reg->pago_normal_dia,
              "4"=> $reg->horas_extras_dia,
              "5"=> 'S/ '. $reg->pago_horas_extras,
              "6"=> '<b>Fecha: </b>'. format_d_m_a($reg->fecha_asistencia) ."<br> <b>Día: </b>". $reg->nombre_dia,
              "7"=>($reg->estado)?'<span class="text-center badge badge-success">Activado</span>'.$toltip : '<span class="text-center badge badge-danger">Desactivado</span>'.$toltip
            );
          }

          $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
            "data"=>$data
          );

          echo json_encode($results);
        break;

        case 'desactivar_dia':

          $rspta=$asistencia_obrero->desactivar_dia($idasistencia_trabajador);

          echo $rspta ? "Usuario Desactivado" : "Usuario no se puede desactivar";	

        break;

        case 'activar_dia':

          $rspta=$asistencia_obrero->activar_dia($idasistencia_trabajador);

          echo $rspta ? "Usuario activado" : "Usuario no se puede activar";

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
              
            $doc1 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext_doc1);

            move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/asistencia_obrero/justificacion/" . $doc1);
            
          }	

          // registramos un nuevo: recibo x honorario
          if (empty($idasistencia_trabajador_j)){

            $rspta = '0';
            
            echo $rspta ? "ok" : "No se logro registrar la justificación";

          }else {

            // eliminados si existe el "doc en la BD"
            if ($flat_doc1 == true) {

              $datos_f1 = $asistencia_obrero->imgJustificacion($idasistencia_trabajador_j);

              $doc1_ant = $datos_f1->fetch_object()->doc_justificacion;

              if ( !empty($doc1_ant) ) {

                unlink("../dist/docs/asistencia_obrero/justificacion/" . $doc1_ant);
              }
            }

            // editamos un recibo x honorario existente
            $rspta=$asistencia_obrero->editar_justificacion($idasistencia_trabajador_j, $detalle_j, $doc1);
            
            echo $rspta ? "ok" : "La justificación no se pudo actualizar";
          }

        break;

        case 'mostrar_justificacion':

          $rspta=$asistencia_obrero->mostrar_justificacion($_POST["idasistencia_trabajador"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);

        break;

        // :::::::::::::::::::::::::::::::::::: S E C C I O N   Q-S  P O R   T R A B A J A D O R  ::::::::::::::::::::::::::::::::::::::

        case 'tabla_qs_individual':

          $idtrabajador_x_proyecto = $_GET["idtrabajadorproyecto"];
          
          $rspta=$asistencia_obrero->tbla_qs_individual($idtrabajador_x_proyecto);
          //Vamos a declarar un array
          $data= Array(); 
          
          $imagen_error = "this.src='../dist/svg/user_default.svg'";
          
          while ($reg=$rspta->fetch_object()){

            $tool = '"tooltip"';   $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>";

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

          echo json_encode($results);
        break;

        case 'suma_qs_individual':           

          $rspta=$asistencia_obrero->suma_qs_individual($_POST["idtrabajadorproyecto"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);		

        break;

        case 'desactivar_qs':

          $rspta=$asistencia_obrero->desactivar_qs($_POST["idresumen_q_s_asistencia"]);

          echo $rspta ? "ok" : "Semana no se puede desactivar";	

        break;

        case 'activar_qs':

          $rspta=$asistencia_obrero->activar_qs($_POST["idresumen_q_s_asistencia"]);

          echo $rspta ? "ok" : "Semana no se puede activar";

        break;

        // :::::::::::::::::::::::::::::::::::: S E C C I O N   A D I C I O N A L   D E S C U E N T O ::::::::::::::::::::::::::::::::::::::
        
        // Agregamos o editamos el detalle adicional de: "resumen_q_s_asistencia"
        case 'guardaryeditar_adicional_descuento':

          if (empty($_POST["idresumen_q_s_asistencia"])) {

            $rspta = $asistencia_obrero->insertar_detalle_adicional( $_POST["idtrabajador_por_proyecto"], $_POST["fecha_q_s"], $detalle_adicional);

            echo $rspta ? "ok" : "No se pudieron registrar la descripcion del descuento"; 

          } else {

            $rspta = $asistencia_obrero->editar_detalle_adicionales($_POST["idresumen_q_s_asistencia"], $_POST["idtrabajador_por_proyecto"], $_POST["fecha_q_s"],$_POST["detalle_adicional"]);

            echo $rspta ? "ok" : "No se pudieron registrar la descripcion del descuento";
          }
          
        break;

        case 'descripcion_adicional_descuento':

          $rspta=$asistencia_obrero->descripcion_adicional_descuento($_POST["id_adicional"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);
        break;

        // :::::::::::::::::::::::::::::::::::: S E C C I O N   F E C H A S   A C T I V I D A D ::::::::::::::::::::::::::::::::::::::
        case 'fechas_actividad':

          $rspta=$asistencia_obrero->fechas_actividad($_POST["id_proyecto"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);
        break;

        case 'guardar_y_editar_fechas_actividad':

          // registramos un nuevo: recibo x honorario
          if (empty($id_proyecto_f)){

            $rspta = '0';
            
            echo $rspta ? "ok" : "No se logro registrar la justificación";

          }else {             

            // editamos un recibo x honorario existente
            $rspta=$asistencia_obrero->editar_fechas_actividad($id_proyecto_f, format_d_m_a($fecha_inicio_actividad), format_d_m_a($fecha_fin_actividad), $plazo_actividad);
            
            echo $rspta ? "ok" : "La fechas no se pudo actualizar";
          }

        break;
      } // end switch

    } else {

      require 'noacceso.php';
    }
  }

  // convierte de una fecha(aa-mm-dd): 2021-12-23 a una fecha(dd-mm-aa): 23-12-2021
  function format_d_m_a( $fecha ) {

    if (!empty($fecha) || $fecha != '0000-00-00') {

      $fecha_expl = explode("-", $fecha);

      $fecha_convert =  $fecha_expl[2]."-".$fecha_expl[1]."-".$fecha_expl[0];

    }else{

      $fecha_convert = "";
    }   

    return $fecha_convert;
  }
	ob_end_flush();

?>