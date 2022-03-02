<?php
ob_start();

	if (strlen(session_id()) < 1){
		session_start();//Validamos si existe o no la sesión
	}
  
  if (!isset($_SESSION["nombre"])) {

    header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.

  } else {
    //Validamos el acceso solo al usuario logueado y autorizado.
    if ($_SESSION['asistencia_trabajador'] == 1) {

      require_once "../modelos/registro_asistencia.php";

      $asist_trabajador=new Asistencia_trabajador();      

      // :::::::::::::::::::::::::::::::::::: D A T O S  A S I S T E N C I A ::::::::::::::::::::::::::::::::::::::   
      $detalle_adicional	= isset($_POST["detalle_adicional"])? limpiarCadena($_POST["detalle_adicional"]):"";

      // :::::::::::::::::::::::::::::::::::: D A T O S   J S U T I F I C A C I O N ::::::::::::::::::::::::::::::::::::::
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

          $data_asistencia = $_POST["asistencia"];  $extras = $_POST["extras"]; $fecha_i = $_POST["fecha_inicial"]; $fecha_f = $_POST["fecha_final"];
                     
          $rspta=$asist_trabajador->insertar_asistencia_y_resumen_q_s_asistencia( $data_asistencia, $extras, $fecha_i, $fecha_f);

          echo $rspta ? "ok" : "No se pudieron registrar todos los datos del trabajador";          
          
        break;

        // Agregamos o editamos el detalle adicional de: "resumen_q_s_asistencia"
        case 'guardaryeditar_adicional_descuento':

          if (empty($_POST["idresumen_q_s_asistencia"])) {

            $rspta = $asist_trabajador->insertar_detalle_adicional( $_POST["idtrabajador_por_proyecto"], $_POST["fecha_q_s"], $detalle_adicional);

            echo $rspta ? "ok" : "No se pudieron registrar la descripcion del descuento"; 

          } else {

            $rspta = $asist_trabajador->editar_detalle_adicionales($_POST["idresumen_q_s_asistencia"], $_POST["idtrabajador_por_proyecto"], $_POST["fecha_q_s"],$_POST["detalle_adicional"]);

            echo $rspta ? "ok" : "No se pudieron registrar la descripcion del descuento";
          }
          
        break;

        // activamos el sabatical manual
        case 'agregar_quitar_sabatical_manual':

          if (empty($_POST["idresumen_q_s_asistencia"])) {

            $rspta = $asist_trabajador->insertar_sabatical_manual( $_POST["fecha_asist"], $_POST["sueldo_x_hora"],  $_POST["fecha_q_s_inicio"], $_POST["fecha_q_s_fin"], $_POST["numero_q_s"], $_POST["id_trabajador_x_proyecto"], $_POST["numero_sabado"], $_POST["estado_sabatical_manual"] );

            echo $rspta ? "ok" : "No se pudieron registrar el pago al contador"; 

          } else {

            $rspta = $asist_trabajador->quitar_editar_sabatical_manual($_POST["idresumen_q_s_asistencia"], $_POST["fecha_asist"], $_POST["sueldo_x_hora"], $_POST["fecha_q_s_inicio"], $_POST["fecha_q_s_fin"], $_POST["numero_q_s"], $_POST["id_trabajador_x_proyecto"], $_POST["numero_sabado"], $_POST["estado_sabatical_manual"] );

            echo $rspta ? "ok" : "No se pudieron registrar el pago al contador";
          }
          
        break;

        // enviamos el pagos de quincena o semana al contador
        case 'guardaryeditar_pago_al_contador':

          if (empty($_POST["idresumen_q_s_asistencia"])) {

            $rspta = $asist_trabajador->insertar_pago_al_contador( $_POST["id_trabajador_x_proyecto"], $_POST["fecha_q_s_inicio"], $_POST["estado_envio_contador"]);

            echo $rspta ? "ok" : "No se pudieron registrar el pago al contador"; 

          } else {

            $rspta = $asist_trabajador->quitar_editar_pago_al_contador($_POST["idresumen_q_s_asistencia"], $_POST["id_trabajador_x_proyecto"], $_POST["fecha_q_s_inicio"], $_POST["estado_envio_contador"]);

            echo $rspta ? "ok" : "No se pudieron realizar los cambios del pago al contador";
          }
          
        break;

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

              $datos_f1 = $asist_trabajador->imgJustificacion($idasistencia_trabajador_j);

              $doc1_ant = $datos_f1->fetch_object()->doc_justificacion;

              if ( !empty($doc1_ant) ) {

                unlink("../dist/docs/asistencia_obrero/justificacion/" . $doc1_ant);
              }
            }

            // editamos un recibo x honorario existente
            $rspta=$asist_trabajador->editar_justificacion($idasistencia_trabajador_j, $detalle_j, $doc1);
            
            echo $rspta ? "ok" : "La justificación no se pudo actualizar";
          }

        break;

        case 'desactivar':

          $rspta=$asist_trabajador->desactivar($idasistencia_trabajador);

          echo $rspta ? "Usuario Desactivado" : "Usuario no se puede desactivar";	

        break;

        case 'activar':

          $rspta=$asist_trabajador->activar($idasistencia_trabajador);

          echo $rspta ? "Usuario activado" : "Usuario no se puede activar";

        break;

        case 'desactivar_qs':

          $rspta=$asist_trabajador->desactivar_qs($_POST["idresumen_q_s_asistencia"]);

          echo $rspta ? "ok" : "Semana no se puede desactivar";	

        break;

        case 'activar_qs':

          $rspta=$asist_trabajador->activar_qs($_POST["idresumen_q_s_asistencia"]);

          echo $rspta ? "ok" : "Semana no se puede activar";

        break;

        case 'mostrar_editar':

          $rspta=$asist_trabajador->mostrar($idasistencia_trabajador);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);

        break;

        case 'mostrar_justificacion':

          $rspta=$asist_trabajador->mostrar_justificacion($_POST["idasistencia_trabajador"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);

        break;

        case 'ver_datos_quincena':
          
          $f1 = $_POST["f1"];
          $f2 = $_POST["f2"];
          $nube_idproyect = $_POST["nube_idproyect"];
          // $f1 = '2021-07-09'; $f2 = '2021-07-23'; $nube_idproyect = '1';

          $rspta=$asist_trabajador->ver_detalle_quincena($f1,$f2,$nube_idproyect);

          //Codificar el resultado utilizando json
          echo json_encode($rspta);		
        break;

        // lo voy a borrar cuando no lo nesecite
        case 'ver_datos_quincena_xdia':
          //$f1 = $_POST["f1"];
          $f1 = $_POST["f1"];
          $f2 = $_POST["f2"];
          $nube_idproyect = $_POST["nube_idproyect"];
          $idtrabajador = $_POST["idtrabajador"];
          /*$f1 = '01/10/2021';
          $f2 = '15/10/2021';
          $nube_idproyect = '1';
          $idtrabajador = '1';*/
          $data= Array();

          $rspta=$asist_trabajador->ver_detalle_quincena_dias($f1,$f2,$nube_idproyect,$idtrabajador);

          while ($reg=$rspta->fetch_object()){

            $data[]=array(
              "idasistencia_trabajador"=>$reg->idasistencia_trabajador,
              "idtrabajador"=>$reg->idtrabajador,
              "horas_trabajador"=>$reg->horas_trabajador,
              "horas_extras_dia"=>$reg->horas_extras_dia,
              "fecha_asistencia"=>$reg->fecha_asistencia
            );
          }
          
          //Codificar el resultado utilizando json
          echo json_encode($data);		
        break;
        
        // listamos los botones de la quincena o semana
        case 'listarquincenas_botones':

          $nube_idproyecto = $_POST["nube_idproyecto"];

          $rspta=$asist_trabajador->listarquincenas_botones($nube_idproyecto);
          
          echo json_encode($rspta);	 //Codificar el resultado utilizando json

        break;

        // lista la tabla principal 
        case 'tbla_principal':

          $nube_idproyecto = $_GET["nube_idproyecto"];
          
          $rspta=$asist_trabajador->tbla_principal($nube_idproyecto);
          //Vamos a declarar un array
          $data= Array(); $cont = 1;

          $jornal_diario = '';  $sueldo_acumudado=''; $imagen_error = "this.src='../dist/svg/user_default.svg'";
          
          foreach (json_decode($rspta, true) as $key => $value) {

            $ver_asistencia="'".$value['idtrabajador_por_proyecto']."','".$value['fecha_inicio_proyect']."'";

            $data[]=array(
              "0"=> $cont++,
              "1"=>'<button class="btn btn-info btn-sm" onclick="ver_q_s_individual('.$value['idtrabajador_por_proyecto'].')">
                <i class="far fa-calendar-alt"></i>
              </button>
              <button class="btn btn-info btn-sm" onclick="ver_asistencias_individual('.$ver_asistencia.')">
                <i class="far fa-clock"></i>
              </button>',
              "2"=>'<div class="user-block text-nowrap">
                <img class="img-circle" src="../dist/img/usuarios/'. $value['imagen'] .'" alt="User Image" onerror="'.$imagen_error.'">
                <span class="username" ><p class="text-primary"style="margin-bottom: 0.2rem !important"; ><b 
                style="color: #000000 !important;">'. $value['cargo'] .': </b> <br>'. $value['nombre'] .'</p></span>
                <span class="description" >'. $value['tipo_doc'] .': '. $value['num_doc'] .' </span>
              </div>',              
              "3"=> round($value['total_horas_normal'] + $value['total_horas_extras'], 2),
              "4"=> round(($value['total_horas_normal'] + $value['total_horas_extras'])/8, 1),
              "5"=> 'S/. '.$value['sueldo_hora'],
              "6"=> 'S/. '.$value['sueldo_diario'],
              "7"=> 'S/. '.number_format($value['sueldo_mensual'], 2, '.', ','),              
              "8"=> $value['total_sabatical'],
              "9"=> 'S/. '.number_format($value['pago_quincenal'], 1, '.', ',') ,
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
          $rspta=$asist_trabajador->total_acumulado_trabajadores($_POST["nube_idproyecto"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);
        break;

        // lista la tabla individual por trabajador
        case 'listar_asis_individual':

          $idtrabajador_x_proyecto = $_GET["idtrabajadorproyecto"];
          
          $rspta=$asist_trabajador->tbla_asis_individual($idtrabajador_x_proyecto);
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
              "3"=> 'S/. '. $reg->pago_normal_dia,
              "4"=> $reg->horas_extras_dia,
              "5"=> 'S/. '. $reg->pago_horas_extras,
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

        // lista la tabla quincena semana por trabajador
        case 'listar_qs_individual':

          $idtrabajador_x_proyecto = $_GET["idtrabajadorproyecto"];
          
          $rspta=$asist_trabajador->tbla_qs_individual($idtrabajador_x_proyecto);
          //Vamos a declarar un array
          $data= Array(); 
          
          $imagen_error = "this.src='../dist/svg/user_default.svg'";
          
          while ($reg=$rspta->fetch_object()){

            $tool = '"tooltip"';   $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>";

            $pago = ($reg->fecha_pago_obrero = 'quincenal') ? 'Quincena' : 'Semana ' ;

            $opciones = "'$reg->idresumen_q_s_asistencia', '$pago' ";

            $data[]=array(
              "0"=> ($reg->estado) ? '<button class="btn btn-danger btn-sm" onclick="desactivar_qs('. $opciones .')" data-toggle="tooltip" data-original-title="Desactivar"><i class="fas fa-trash-alt"></i></button>' :
                '<button class="btn btn-success btn-sm" onclick="activar_qs('. $opciones .')" data-toggle="tooltip" data-original-title="Activar"><i class="fas fa-check"></i></button>',
              "1"=> $reg->numero_q_s,
              "2"=> format_d_m_a($reg->fecha_q_s_inicio) . ' - ' . format_d_m_a($reg->fecha_q_s_fin),
              "3"=> 'S/. '. number_format($reg->pago_parcial_hn, 2, '.', ',') . ' / ' . number_format($reg->pago_parcial_he, 2, '.', ','),
              "4"=> 'S/. '. number_format($reg->adicional_descuento, 2, '.', ','),
              "5"=> $reg->sabatical,
              "6"=> 'S/. '. number_format($reg->pago_quincenal, 2, '.', ','),
              "7"=> ($reg->estado_envio_contador) ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times text-danger"></i>' ,
              "8"=>($reg->estado)?'<span class="text-center badge badge-success">Activado</span>'.$toltip : '<span class="text-center badge badge-danger">Desactivado</span>'.$toltip
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
        
        // no se utiliza
        case 'suma_qs_individual':           

          $rspta=$asist_trabajador->suma_qs_individual($_POST["idtrabajadorproyecto"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);		

        break;

        // listamos para registrar asistencia
        case 'lista_trabajador': 

          // $nube_idproyecto = 1;
          $nube_idproyecto = $_POST["nube_idproyecto"]; 

          $rspta = $asist_trabajador->lista_trabajador($nube_idproyecto);

          $datos = Array();

          while ($reg = $rspta->fetch_object()){

            $datos[]=array(
              "idtrabajador_por_proyecto"=>$reg->idtrabajador_por_proyecto,
              "imagen_perfil"=>$reg->imagen_perfil,
              "nombres"=>$reg->nombres,
              "documento"=>$reg->documento,
              "numero_documento"=>$reg->numero_documento,
              "cargo"=>$reg->cargo
            );
          }
          // enviamos los datos codificado
          echo json_encode($datos);

        break;

        case 'descripcion_adicional_descuento':

          $rspta=$asist_trabajador->descripcion_adicional_descuento($_POST["id_adicional"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);
        break;

        // :::::::::::::::::::::::::::::::::::: S E C C I O N   F E C H A S   A C T I V I D A D ::::::::::::::::::::::::::::::::::::::
        case 'fechas_actividad':

          $rspta=$asist_trabajador->fechas_actividad($_POST["id_proyecto"]);
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
            $rspta=$asist_trabajador->editar_fechas_actividad($id_proyecto_f, format_d_m_a($fecha_inicio_actividad), format_d_m_a($fecha_fin_actividad), $plazo_actividad);
            
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