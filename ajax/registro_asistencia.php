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

      //$idasistencia_trabajador,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$c_bancaria,$c_detracciones,$banco,$titular_cuenta	
      //$idproyecto		          = isset($_POST["idproyecto"])? limpiarCadena($_POST["idproyecto"]):"";
      //$idasistencia_trabajador= isset($_POST["idasistencia_trabajador"])? limpiarCadena($_POST["idasistencia_trabajador"]):"";      
      $detalle_adicional	= isset($_POST["detalle_adicional"])? limpiarCadena($_POST["detalle_adicional"]):"";

      
      switch ($_GET["op"]){
        // Gurdamos cada dia de asistencia del OBRERO
        case 'guardaryeditar':

          $data_asistencia = $_POST["asistencia"];  $extras = $_POST["extras"]; $fecha_i = $_POST["fecha_inicial"]; $fecha_f = $_POST["fecha_final"];
                     
          $rspta=$asist_trabajador->insertar_asistencia_y_resumen_q_s_asistencia( $data_asistencia, $extras, $fecha_i, $fecha_f);

          echo $rspta ? "ok" : "No se pudieron registrar todos los datos del trabajador";          
          
        break;
        // Agregamos o editamos el detalle adicional de: "resumen_q_s_asistencia"
        case 'guardaryeditar_adicional_descuento':

          if (empty($_POST["idsumas_adicionales"])) {

            $rspta = $asist_trabajador->insertar_detalle_adicional( $_POST["idtrabajador_por_proyecto"], $_POST["fecha_q_s"],$detalle_adicional);

            echo $rspta ? "ok" : "No se pudieron registrar la descripcion del descuento"; 

          } else {

            $rspta = $asist_trabajador->editar_detalle_adicionales($_POST["idsumas_adicionales"], $_POST["idtrabajador_por_proyecto"], $_POST["fecha_q_s"],$_POST["detalle_adicional"]);

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

        case 'desactivar':

          $rspta=$asist_trabajador->desactivar($idasistencia_trabajador);

          echo $rspta ? "Usuario Desactivado" : "Usuario no se puede desactivar";	

        break;

        case 'activar':

          $rspta=$asist_trabajador->activar($idasistencia_trabajador);

          echo $rspta ? "Usuario activado" : "Usuario no se puede activar";

        break;

        case 'mostrar_editar':

          $rspta=$asist_trabajador->mostrar($idasistencia_trabajador);
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
        case 'listar':

          $nube_idproyecto = $_GET["nube_idproyecto"];
          
          $rspta=$asist_trabajador->listar($nube_idproyecto);
          //Vamos a declarar un array
          $data= Array();

          $jornal_diario = '';  $sueldo_acumudado=''; $imagen_error = "this.src='../dist/svg/user_default.svg'";
          
          foreach (json_decode($rspta, true) as $key => $value) {
            //$jonal_diario=$reg->sueldo_hora*($reg->total_horas+$reg->horas_extras);
            $jornal_diario=$value['sueldo_hora']*8;

            $sueldo_acumudado=$value['sueldo_hora']*($value['total_horas_normal']+$value['total_horas_extras']);

            $ver_asistencia="'".$value['idtrabajador_por_proyecto']."','".$value['fecha_inicio_proyect']."'";

            $data[]=array(
              "0"=>'<button class="btn btn-info" onclick="ver_asistencias_individual('.$ver_asistencia.')"><i class="far fa-eye"></i></button>',
              "1"=>'<div class="user-block">
              <img class="img-circle" src="../dist/img/usuarios/'. $value['imagen'] .'" alt="User Image" onerror="'.$imagen_error.'">
                <span class="username" style="/*margin-left: 0px !important;*/"><p class="text-primary"style="margin-bottom: 0.2rem !important"; ><b 
                style="color: #000000 !important;">'. $value['cargo'] .' : </b> <br> '. $value['nombre'] .'</p></span>
                <span class="description" style="/*margin-left: 0px !important;*/">'. $value['tipo_doc'] .': '. $value['num_doc'] .' </span>
              </div>',              
              "2"=> round($value['total_horas_normal'] + $value['total_horas_extras'], 2),
              "3"=> round(($value['total_horas_normal'] + $value['total_horas_extras'])/8, 1),
              "4"=> $value['sueldo_hora'],
              "5"=> $jornal_diario,
              "6"=> number_format($value['sueldo_mensual'], 2, '.', ','),              
              "7"=> $value['total_sabatical'],
              "8"=> number_format($sueldo_acumudado, 1, '.', ',') ,
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
          
          $rspta=$asist_trabajador->listar_asis_individual($idtrabajador_x_proyecto);
          //Vamos a declarar un array
          $data= Array();
          
          while ($reg=$rspta->fetch_object()){

            $tool = '"tooltip"';   $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>";

            $justificacion = "$reg->idasistencia_trabajador, $reg->horas_normal_dia, '$reg->estado'";

            $data[]=array(
              "0"=> ($reg->estado)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idasistencia_trabajador.')" data-toggle="tooltip" data-original-title="Editar" ><i class="fas fa-pencil-alt"></i></button>'.
              ' <button class="btn btn-danger" onclick="desactivar('.$reg->idasistencia_trabajador.')" data-toggle="tooltip" data-original-title="Desactivar"><i class="far fa-trash-alt  "></i></button>'.
              ' <button class="btn btn-info" onclick="justificar('.$justificacion.')" data-toggle="tooltip" data-original-title="Justificarse"><i class="far fa-flag"></i></button>':
              '<button class="btn btn-warning" onclick="mostrar('.$reg->idasistencia_trabajador.')" data-toggle="tooltip" data-original-title="Editar"><i class="fa fa-pencil-alt"></i></button>'.
              ' <button class="btn btn-primary" onclick="activar('.$reg->idasistencia_trabajador.')" data-toggle="tooltip" data-original-title="Activar"><i class="fa fa-check"></i></button>'.
              ' <button class="btn btn-info" onclick="justificar('.$justificacion.')" data-toggle="tooltip" data-original-title="Justificarse"><i class="far fa-flag"></i></button>',
              "1"=> $reg->trabajador,
              "2"=> $reg->horas_normal_dia,
              "3"=> $reg->pago_normal_dia,
              "4"=> $reg->horas_extras_dia,
              "5"=> $reg->pago_horas_extras,
              "6"=> '<b>Fecha: </b>'. $reg->fecha_asistencia ."<br> <b>Día: </b>". $reg->nombre_dia,
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
        
        // no se utiliza
        case 'ver_asistencia_trab':

          $idtrabajador= '1';

          $rspta=$asist_trabajador->registro_asist_trab($idtrabajador);
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
      } // end switch

    } else {

      require 'noacceso.php';
    }
  }
	ob_end_flush();

?>