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
    if ($_SESSION['trabajador'] == 1) {

      require_once "../modelos/Trabajador_por_proyecto.php";
      require_once "../modelos/AllTrabajador.php";

      $trabajadorproyecto=new TrabajadorPorProyecto();
      $all_trabajador=new AllTrabajador();

      date_default_timezone_set('America/Lima');  $date_now = date("d-m-Y h.i.s A");

      $imagen_error = "this.src='../dist/svg/user_default.svg'";
      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

      $idproyecto		    = isset($_POST["idproyecto"])? limpiarCadena($_POST["idproyecto"]):"";
      $idtrabajador_por_proyecto		= isset($_POST["idtrabajador_por_proyecto"])? limpiarCadena($_POST["idtrabajador_por_proyecto"]):"";
      $trabajador		    = isset($_POST["trabajador"])? limpiarCadena($_POST["trabajador"]):"";
      $desempenio		    = isset($_POST["desempenio"])? limpiarCadena($_POST["desempenio"]):"";
      
      $sueldo_diario	  = isset($_POST["sueldo_diario"])? limpiarCadena($_POST["sueldo_diario"]):"";
      $sueldo_mensual   = isset($_POST['sueldo_mensual'])? $_POST['sueldo_mensual']:"";
      $sueldo_hora 		  = isset($_POST['sueldo_hora'])? $_POST['sueldo_hora']:"";

      $fecha_inicio 		= isset($_POST['fecha_inicio'])? $_POST['fecha_inicio']:"";
      $fecha_fin 		    = isset($_POST['fecha_fin'])? $_POST['fecha_fin']:""; 
      $cantidad_dias 		= isset($_POST['cantidad_dias'])? $_POST['cantidad_dias']:"";      

      // ::::::::::::::::::::::::::::::: ALL TRABAJADOR :::::::::::::::::::::::::::::::
      $idtrabajador_all	  	= isset($_POST["idtrabajador_all"])? limpiarCadena($_POST["idtrabajador_all"]):"";
      $nombre_all 		      = isset($_POST["nombre_all"])? limpiarCadena($_POST["nombre_all"]):"";
      $tipo_documento_all 	= isset($_POST["tipo_documento_all"])? limpiarCadena($_POST["tipo_documento_all"]):"";
      $num_documento_all  	= isset($_POST["num_documento_all"])? limpiarCadena($_POST["num_documento_all"]):"";
      $direccion_all		    = isset($_POST["direccion_all"])? limpiarCadena($_POST["direccion_all"]):"";
      $telefono_all		      = isset($_POST["telefono_all"])? limpiarCadena($_POST["telefono_all"]):"";
      $nacimiento_all		    = isset($_POST["nacimiento_all"])? limpiarCadena($_POST["nacimiento_all"]):"";
      $edad_all		          = isset($_POST["edad_all"])? limpiarCadena($_POST["edad_all"]):"";      
      $email_all			      = isset($_POST["email_all"])? limpiarCadena($_POST["email_all"]):"";
      $banco_seleccionado   = isset($_POST["banco_seleccionado"])? $_POST["banco_seleccionado"] :"";
      $banco			          = isset($_POST["banco_array"])?$_POST["banco_array"]:"";      
      $cta_bancaria		      = isset($_POST["cta_bancaria"])?$_POST["cta_bancaria"]:"";
      $cta_bancaria_format  = isset($_POST["cta_bancaria"])?$_POST["cta_bancaria"]:"";
      $cci	          	    = isset($_POST["cci"])?$_POST["cci"]:"";
      $cci_format      	    = isset($_POST["cci"])? $_POST["cci"]:"";
      $titular_cuenta_all		= isset($_POST["titular_cuenta_all"])? limpiarCadena($_POST["titular_cuenta_all"]):"";
      $tipo_all	          	= isset($_POST["tipo_all"])? limpiarCadena($_POST["tipo_all"]):"";
      $ocupacion_all	      = isset($_POST["ocupacion_all"])? $_POST["ocupacion_all"]:"";
      $ruc_all	          	= isset($_POST["ruc_all"])? limpiarCadena($_POST["ruc_all"]):"";

      $imagen1			    = isset($_POST["foto1"])? limpiarCadena($_POST["foto1"]):"";
      $imagen2			    = isset($_POST["foto2"])? limpiarCadena($_POST["foto2"]):"";
      $imagen3			    = isset($_POST["foto3"])? limpiarCadena($_POST["foto3"]):"";

      $cv_documentado		= isset($_POST["doc4"])? limpiarCadena($_POST["doc4"]):"";
      $cv_nodocumentado = isset($_POST["doc5"])? limpiarCadena($_POST["doc5"]):"";


      switch ($_GET["op"]){

        case 'guardaryeditar':
          	
          // registramos un nuevo trabajador
          if (empty($idtrabajador_por_proyecto)){

            $rspta=$trabajadorproyecto->insertar( $idproyecto, $trabajador, $desempenio, quitar_formato_miles($sueldo_diario), quitar_formato_miles($sueldo_mensual), 
            quitar_formato_miles($sueldo_hora), format_a_m_d($fecha_inicio), format_a_m_d($fecha_fin), $cantidad_dias );
            
            echo json_encode($rspta, true);

          }else {
            // editamos un trabajador existente
            $rspta=$trabajadorproyecto->editar( $idtrabajador_por_proyecto, $idproyecto, $trabajador, $desempenio, quitar_formato_miles($sueldo_diario), quitar_formato_miles($sueldo_mensual), 
            quitar_formato_miles($sueldo_hora), format_a_m_d($fecha_inicio), format_a_m_d($fecha_fin), $cantidad_dias );
            
            echo json_encode($rspta, true);
          }

        break;

        case 'desactivar':

          $rspta=$trabajadorproyecto->desactivar($idtrabajador_por_proyecto);

          echo json_encode($rspta, true);	

        break;

        case 'activar':

          $rspta=$trabajadorproyecto->activar($idtrabajador_por_proyecto);

          echo json_encode($rspta, true);

        break;

        case 'mostrar':

          $rspta=$trabajadorproyecto->mostrar($idtrabajador_por_proyecto);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);

        break;
        
        case 'ver_datos_trabajador':

          $rspta=$trabajadorproyecto->ver_datos_trabajador($idtrabajador_por_proyecto);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);

        break;

        case 'tbla_principal':
          $nube_idproyecto = $_GET["nube_idproyecto"];

          $rspta=$trabajadorproyecto->tbla_principal($nube_idproyecto, $_GET["estado"]);
          //Vamos a declarar un array
          $data= Array(); $cont = 1;
          
          if ($rspta['status'] == true) {
            foreach ($rspta['data'] as $key => $value) { 
              $imagen = (empty($value['imagen_perfil']) ? '../dist/svg/user_default.svg' : '../dist/docs/all_trabajador/perfil/'. $value['imagen_perfil']) ;
              $data[]=array(
                "0"=> $cont++,
                "1"=>'<button class="btn btn-warning btn-sm mb-1" onclick="mostrar('.$value['idtrabajador_por_proyecto'].','.$value['idtipo_trabajador'].')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>'.
                  ' <button class="btn btn-danger btn-sm mb-1" onclick="desactivar('.$value['idtrabajador_por_proyecto'].')" data-toggle="tooltip" data-original-title="Desactivar"><i class="far fa-trash-alt  "></i></button>'.
                  ' <button class="btn btn-info btn-sm mb-1" onclick="verdatos('.$value['idtrabajador_por_proyecto'].')" data-toggle="tooltip" data-original-title="Ver detalle"><i class="far fa-eye"></i></button>',
                "2"=>'<div class="user-block">
                  <img class="img-circle cursor-pointer" src="../dist/docs/all_trabajador/perfil/'. $value['imagen_perfil'] .'" alt="User Image" onerror="'.$imagen_error.'" onclick="ver_perfil(\'' . $imagen . '\', \''.encodeCadenaHtml($value['trabajador']).'\');" data-toggle="tooltip" data-original-title="Ver imagen">
                  <span class="username"><p class="text-primary m-b-02rem" >'. $value['trabajador'] .'</p></span>
                  <span class="description">'. $value['tipo_documento'] .': '. $value['numero_documento'] .' </span>
                  </div>',
                "3"=>'<div class="text-nowrap"><b>Inicio: </b>'. ( empty($value['fecha_inicio']) ? '--' : format_d_m_a($value['fecha_inicio']) ). '<br> 
                  <b>Fin: </b>'.( empty($value['fecha_fin']) ? '--' : format_d_m_a($value['fecha_fin']) ) . '</div>',
                "4"=> '<a data-toggle="tooltip" data-original-title="Realizar llamada" href="tel:+51'.quitar_guion($value['telefono']).'">'.$value['telefono'].'</a>' ,
                "5"=> '<a data-toggle="tooltip" data-original-title="Enviar correo" href="mailto:'.$value['email'].'">'.$value['email'].'</a>' ,
                "6"=> $value['fecha_nacimiento'],
                "7"=>$value['nombre_tipo'] ,
                "8"=>$value['nombre_desempeno'],
                "9"=>'<b>'.$value['banco'] .': </b>'. $value['cuenta_bancaria'] . '<br> <b>CCI: </b>'. $value['cci'] . $toltip ,                
                
                "10"=> $value['trabajador'],
                "11"=>$value['numero_documento'],
                "12"=>( empty($value['fecha_inicio']) ? '--' : format_d_m_a($value['fecha_inicio']) ),
                "13"=>( empty($value['fecha_fin']) ? '--' : format_d_m_a($value['fecha_fin']) ),
                "14"=>$value['banco'],
                "15"=>$value['cuenta_bancaria'],

                );
            }
            $results = array(
              "sEcho"=>1, //Información para el datatables
              "iTotalRecords"=>count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
              "data"=>$data);
            echo json_encode($results, true);
          } else {
            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          }          
          
        break;        

        case 'm_datos_trabajador':
          $idtrabajador = $_POST["idtrabajador"];
          // $idtrabajador = '8';
          $rspta=$trabajadorproyecto->m_datos_trabajador($idtrabajador);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
 
        break;  

        case 'ver_lista_orden':

          $rspta=$trabajadorproyecto->tbla_principal($idproyecto, 1);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);

        break;

        // :::::::::::::::::::::::::::::::::::::::::::::::: ALL-TRABAJADOR ::::::::::::::::::::::::::::::::::::::::::::::::
        
        case 'mostrar_editar_trabajador':         
          
          $rspta=$all_trabajador->mostrar( $_POST["idtrabajador"] );
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
 
        break;

        case 'guardar_y_editar_all_trabajador':

          // imgen de perfil
          if (!file_exists($_FILES['foto1']['tmp_name']) || !is_uploaded_file($_FILES['foto1']['tmp_name'])) {
						$imagen1=$_POST["foto1_actual"]; $flat_img1 = false;
					} else {
						$ext1 = explode(".", $_FILES["foto1"]["name"]); $flat_img1 = true;
            $imagen1 = $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);
            move_uploaded_file($_FILES["foto1"]["tmp_name"], "../dist/docs/all_trabajador/perfil/" . $imagen1);						
					}

          // imgen DNI ANVERSO
          if (!file_exists($_FILES['foto2']['tmp_name']) || !is_uploaded_file($_FILES['foto2']['tmp_name'])) {
						$imagen2=$_POST["foto2_actual"]; $flat_img2 = false;
					} else {
						$ext2 = explode(".", $_FILES["foto2"]["name"]); $flat_img2 = true;
            $imagen2 = $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext2);
            move_uploaded_file($_FILES["foto2"]["tmp_name"], "../dist/docs/all_trabajador/dni_anverso/" . $imagen2);						
					}

          // imgen DNI REVERSO
          if (!file_exists($_FILES['foto3']['tmp_name']) || !is_uploaded_file($_FILES['foto3']['tmp_name'])) {
						$imagen3=$_POST["foto3_actual"]; $flat_img3 = false;
					} else {
						$ext3 = explode(".", $_FILES["foto3"]["name"]); $flat_img3 = true;            
            $imagen3 = $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext3);
            move_uploaded_file($_FILES["foto3"]["tmp_name"], "../dist/docs/all_trabajador/dni_reverso/" . $imagen3);						
					}

          // cv documentado
          if (!file_exists($_FILES['doc4']['tmp_name']) || !is_uploaded_file($_FILES['doc4']['tmp_name'])) {
            $cv_documentado=$_POST["doc_old_4"]; $flat_cv1 = false;
          } else {
            $ext3 = explode(".", $_FILES["doc4"]["name"]); $flat_cv1 = true;            
            $cv_documentado = $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext3);
            move_uploaded_file($_FILES["doc4"]["tmp_name"], "../dist/docs/all_trabajador/cv_documentado/" .  $cv_documentado);            
          }

          // cv  no documentado
          if (!file_exists($_FILES['doc5']['tmp_name']) || !is_uploaded_file($_FILES['doc5']['tmp_name'])) {
            $cv_nodocumentado=$_POST["doc_old_5"]; $flat_cv2 = false;
          } else {
            $ext3 = explode(".", $_FILES["doc5"]["name"]); $flat_cv2 = true;            
            $cv_nodocumentado = $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext3);
            move_uploaded_file($_FILES["doc5"]["tmp_name"], "../dist/docs/all_trabajador/cv_no_documentado/" . $cv_nodocumentado);            
          }

          if (empty($idtrabajador_all)){            
            $rspta=$all_trabajador->insertar( $nombre_all, $tipo_documento_all, $num_documento_all, $direccion_all, $telefono_all, 
            format_a_m_d( $nacimiento_all), 
            $edad_all, $email_all, $banco_seleccionado, $banco, $cta_bancaria, $cci,  $titular_cuenta_all, $tipo_all, $_POST["desempenio_all"], 
            $ocupacion_all, $ruc_all, $imagen1, $imagen2, $imagen3, $cv_documentado, $cv_nodocumentado);            
            echo json_encode($rspta, true);  
          }else {

            // validamos si existe LA IMG para eliminarlo
            if ($flat_img1 == true) {
              $datos_f1 = $all_trabajador->obtenerImg($idtrabajador_all);
              $img1_ant = $datos_f1['data']['imagen_perfil'];
              if ($img1_ant != "") { unlink("../dist/docs/all_trabajador/perfil/" . $img1_ant);  }
            }

            //imagen_dni_anverso
            if ($flat_img2 == true) {
              $datos_f2 = $all_trabajador->obtenerImg($idtrabajador_all);
              $img2_ant = $datos_f2['data']['imagen_dni_anverso'];
              if ($img2_ant != "") { unlink("../dist/docs/all_trabajador/dni_anverso/" . $img2_ant); }
            }

            //imagen_dni_reverso
            if ($flat_img3 == true) {
              $datos_f3 = $all_trabajador->obtenerImg($idtrabajador_all);
              $img3_ant = $datos_f3['data']['imagen_dni_reverso'];
              if ($img3_ant != "") { unlink("../dist/docs/all_trabajador/dni_reverso/" . $img3_ant); }
            }

            //cvs
            if ($flat_cv1 == true) {
              $datos_cv1 = $all_trabajador->obtenercv($idtrabajador_all);
              $cv1_ant = $datos_cv1['data']['cv_documentado'];
              if ($cv1_ant != "") { unlink("../dist/docs/all_trabajador/cv_documentado/" . $cv1_ant); }
            }

            if ($flat_cv2 == true) {
              $datos_cv2 = $all_trabajador->obtenercv($idtrabajador_all);
              $cv2_ant = $datos_cv2['data']['cv_no_documentado'];
              if ($cv2_ant != "") { unlink("../dist/docs/all_trabajador/cv_no_documentado/" . $cv2_ant); }
            }

            // editamos un trabajador existente
            $rspta=$all_trabajador->editar( $idtrabajador_all, $nombre_all, $tipo_documento_all, $num_documento_all, $direccion_all, $telefono_all, 
            format_a_m_d( $nacimiento_all), 
            $edad_all, $email_all, $banco_seleccionado, $banco, $cta_bancaria, $cci,  $titular_cuenta_all, $tipo_all, $_POST["desempenio_all"], 
            $ocupacion_all, $ruc_all, $imagen1, $imagen2, $imagen3, $cv_documentado, $cv_nodocumentado );
            
            echo json_encode($rspta, true);
          }            

        break;

        // :::::::::::::::::::::::::::::::::::::::::::::::: O R D E N   T R A B A J A D O R ::::::::::::::::::::::::::::::::::::::::::::::::
        case 'guardar_y_editar_orden_trabajador':
          
          // editamos un trabajador existente
          $rspta=$trabajadorproyecto->editar_orden_trabajador( $_POST["td_order_trabajador"] );            
          echo json_encode($rspta, true);                 

        break;


        default: 
          $rspta = ['status'=>'error_code', 'message'=>'Te has confundido en escribir en el <b>swich.</b>', 'data'=>[]]; echo json_encode($rspta, true); 
        break;
        
      }

    } else {
      $retorno = ['status'=>'nopermiso', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
      echo json_encode($retorno);
    }
  }

	ob_end_flush();

?>