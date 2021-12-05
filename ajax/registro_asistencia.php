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
      $idproyecto		          = isset($_POST["idproyecto"])? limpiarCadena($_POST["idproyecto"]):"";
      $idasistencia_trabajador= isset($_POST["idasistencia_trabajador"])? limpiarCadena($_POST["idasistencia_trabajador"]):"";
      
      $fecha	                = isset($_POST["fecha"])? limpiarCadena($_POST["fecha"]):"";

      switch ($_GET["op"]){

        case 'guardaryeditar':
         
          $rspta=$asist_trabajador->insertar($idproyecto, $_POST["trabajador"], $_POST["horas_trabajo"], $fecha);

          echo $rspta ? "ok" : "No se pudieron registrar todos los datos del usuario";          

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
          //$f1 = $_POST["f1"];
          /*$f1 = $_POST["f1"];
          $f2 = $_POST["f2"];
          $nube_idproyect = $_POST["nube_idproyect"];*/
          $f1 = '2021-07-09';
          $f2 = '2021-07-23';
          $nube_idproyect = '1';

          $rspta=$asist_trabajador->ver_detalle_quincena($f1,$f2,$nube_idproyect);

          //Vamos a declarar un array
          // $data= Array();           
          // while ($reg=$rspta->fetch_object()){  $data[]=array( "idtrabajador"=>$reg->idtrabajador); }

          //Codificar el resultado utilizando json
          echo json_encode($rspta);		
        break;

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

        case 'listarquincenas':

          $nube_idproyecto = $_POST["nube_idproyecto"];

          $rspta=$asist_trabajador->listarquincenas_b($nube_idproyecto);

          //Codificar el resultado utilizando json
          echo json_encode($rspta);	

        break;
        // lista la tabla principal 
        case 'listar':

          $nube_idproyecto = $_GET["nube_idproyecto"];
          
          $rspta=$asist_trabajador->listar($nube_idproyecto);
          //Vamos a declarar un array
          $data= Array();

          $jornal_diario = '';

          $sueldo_acumudado='';

          while ($reg=$rspta->fetch_object()){
            //$jonal_diario=$reg->sueldo_hora*($reg->total_horas+$reg->horas_extras);
            $jornal_diario=$reg->sueldo_hora*8;

            $sueldo_acumudado=$reg->sueldo_hora*($reg->total_horas_normal+$reg->total_horas_extras);

            $ver_asistencia="'$reg->idtrabajador_por_proyecto','$reg->fecha_inicio_proyect'";

            $data[]=array(
              "0"=>'<button class="btn btn-info" onclick="ver_asistencias_individual('.$ver_asistencia.')"><i class="far fa-eye"></i></button>',
              "1"=>'<div class="user-block">
                <span class="username" style="margin-left: 0px !important;"><p class="text-primary"style="margin-bottom: 0.2rem !important"; ><b 
                style="color: #000000 !important;">'. $reg->cargo .' : </b> <br> '. $reg->nombre .'</p></span>
                <span class="description" style="margin-left: 0px !important;">'. $reg->tipo_doc .': '. $reg->num_doc .' </span>
                </div>',              
              "2"=> round($reg->total_horas_normal+$reg->total_horas_extras, 1),
              "3"=> round(($reg->total_horas_normal+$reg->total_horas_extras)/8, 1),
              "4"=> $reg->sueldo_hora,
              "5"=> $jornal_diario,
              "6"=> $reg->sueldo_mensual,              
              "7"=> $reg->total_sabatical,
              "8"=> round($sueldo_acumudado, 1),
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

        break;
        // lista la tabla individual por trabajador
        case 'listar_asis_individual':

          $idtrabajador_proyecto = $_GET["idtrabajadorproyecto"];
          
          $rspta=$asist_trabajador->listar_asis_individual($idtrabajador_proyecto);
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
              "6"=> $reg->fecha_asistencia,
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
      } // end switch

    } else {

      require 'noacceso.php';
    }
  }
	ob_end_flush();

?>