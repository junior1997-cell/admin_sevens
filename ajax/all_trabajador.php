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
    if ($_SESSION['recurso'] == 1) {

      require_once "../modelos/AllTrabajador.php";

      $trabajador = new AllTrabajador();

      date_default_timezone_set('America/Lima');  $date_now = date("d-m-Y h.i.s A");

      $imagen_error = "this.src='../dist/svg/user_default.svg'";
      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';
      
      $idtrabajador	  	= isset($_POST["idtrabajador"])? limpiarCadena($_POST["idtrabajador"]):"";
      $nombre 		      = isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
      $tipo_documento 	= isset($_POST["tipo_documento"])? limpiarCadena($_POST["tipo_documento"]):"";
      $num_documento  	= isset($_POST["num_documento"])? limpiarCadena($_POST["num_documento"]):"";
      $direccion		    = isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
      $telefono		      = isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
      $nacimiento		    = isset($_POST["nacimiento"])? limpiarCadena($_POST["nacimiento"]):"";
      $edad		          = isset($_POST["edad"])? limpiarCadena($_POST["edad"]):"";      
      $email			      = isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
      $banco_seleccionado= isset($_POST["banco_seleccionado"])? $_POST["banco_seleccionado"] :"";
      $banco			      = isset($_POST["banco_array"])?$_POST["banco_array"]:"";      
      $cta_bancaria		  = isset($_POST["cta_bancaria"])?$_POST["cta_bancaria"]:"";
      $cta_bancaria_format= isset($_POST["cta_bancaria"])?$_POST["cta_bancaria"]:"";
      $cci	          	= isset($_POST["cci"])?$_POST["cci"]:"";
      $cci_format      	= isset($_POST["cci"])? $_POST["cci"]:"";
      $titular_cuenta		= isset($_POST["titular_cuenta"])? limpiarCadena($_POST["titular_cuenta"]):"";
      $tipo	          	= isset($_POST["tipo"])? limpiarCadena($_POST["tipo"]):"";
      $ocupacion	      = isset($_POST["ocupacion"])? limpiarCadena($_POST["ocupacion"]):"";

      $ruc	          	= isset($_POST["ruc"])? limpiarCadena($_POST["ruc"]):"";

      $imagen1			    = isset($_POST["foto1"])? limpiarCadena($_POST["foto1"]):"";
      $imagen2			    = isset($_POST["foto2"])? limpiarCadena($_POST["foto2"]):"";
      $imagen3			    = isset($_POST["foto3"])? limpiarCadena($_POST["foto3"]):"";

      $cv_documentado		= isset($_POST["doc4"])? limpiarCadena($_POST["doc4"]):"";
      $cv_nodocumentado = isset($_POST["doc5"])? limpiarCadena($_POST["doc5"]):"";
      switch ($_GET["op"]) {

        case 'guardaryeditar':

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

          if (empty($idtrabajador)){
            
            $rspta=$trabajador->insertar($nombre, $tipo_documento, $num_documento, $direccion, $telefono, format_a_m_d($nacimiento), 
            $edad,  $email, $banco_seleccionado, $banco, $cta_bancaria, $cci, $titular_cuenta, $tipo, $_POST["desempenio"], $ocupacion, $ruc, $imagen1, $imagen2, $imagen3, $cv_documentado, $cv_nodocumentado);
            
            echo json_encode($rspta, true);
  
          }else {

            // validamos si existe LA IMG para eliminarlo
            if ($flat_img1 == true) {
              $datos_f1 = $trabajador->obtenerImg($idtrabajador);
              $img1_ant = $datos_f1['data']['imagen_perfil'];
              if ($img1_ant != "") { unlink("../dist/docs/all_trabajador/perfil/" . $img1_ant);  }
            }

            //imagen_dni_anverso
            if ($flat_img2 == true) {
              $datos_f2 = $trabajador->obtenerImg($idtrabajador);
              $img2_ant = $datos_f2['data']['imagen_dni_anverso'];
              if ($img2_ant != "") { unlink("../dist/docs/all_trabajador/dni_anverso/" . $img2_ant); }
            }

            //imagen_dni_reverso
            if ($flat_img3 == true) {
              $datos_f3 = $trabajador->obtenerImg($idtrabajador);
              $img3_ant = $datos_f3['data']['imagen_dni_reverso'];
              if ($img3_ant != "") { unlink("../dist/docs/all_trabajador/dni_reverso/" . $img3_ant); }
            }

            //cvs
            if ($flat_cv1 == true) {
              $datos_cv1 = $trabajador->obtenercv($idtrabajador);
              $cv1_ant = $datos_cv1['data']['cv_documentado'];
              if ($cv1_ant != "") { unlink("../dist/docs/all_trabajador/cv_documentado/" . $cv1_ant); }
            }

            if ($flat_cv2 == true) {
              $datos_cv2 = $trabajador->obtenercv($idtrabajador);
              $cv2_ant = $datos_cv2['data']['cv_no_documentado'];
              if ($cv2_ant != "") { unlink("../dist/docs/all_trabajador/cv_no_documentado/" . $cv2_ant); }
            }

            // editamos un trabajador existente
            $rspta=$trabajador->editar($idtrabajador, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, format_a_m_d( $nacimiento), 
            $edad, $email, $banco_seleccionado, $banco, $cta_bancaria, $cci,  $titular_cuenta, $tipo, $_POST["desempenio"], $ocupacion, $ruc, $imagen1, $imagen2, $imagen3, $cv_documentado, $cv_nodocumentado);
            
            echo json_encode($rspta, true);
          }            

        break;

        case 'desactivar':

          $rspta=$trabajador->desactivar($_GET["idtrabajador"], $_GET["descripcion"]);

          echo json_encode($rspta, true);

        break;

        case 'activar':

          $rspta=$trabajador->activar($_GET["idtrabajador"]);

          echo json_encode($rspta, true);

        break;

        case 'eliminar':

          $rspta=$trabajador->eliminar($_GET["idtrabajador"]);

          echo json_encode($rspta, true);

        break;

        case 'mostrar':

          $rspta=$trabajador->mostrar($idtrabajador);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);

        break;

        case 'tbla_principal':          

          $rspta=$trabajador->tbla_principal(1);
          
          //Vamos a declarar un array
          $data= Array(); $cont=1;

          if ($rspta['status'] == true) {

            foreach ($rspta['data'] as $key => $value) {             
              $imagen = (empty($value['imagen_perfil']) ? '../dist/svg/user_default.svg' : '../dist/docs/all_trabajador/perfil/'. $value['imagen_perfil']) ;
          
              $data[]=array(
                "0"=>$cont++,
                "1"=>($value['estado'])?'<button class="btn btn-warning btn-sm" onclick="mostrar('.$value['idtrabajador'].')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>'.
                  ' <button class="btn btn-danger btn-sm" onclick="eliminar('.$value['idtrabajador'].', \''.encodeCadenaHtml($value['trabajador']).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i></button>'.
                  ' <button class="btn btn-info btn-sm" onclick="verdatos('.$value['idtrabajador'].')"data-toggle="tooltip" data-original-title="ver datos"><i class="far fa-eye"></i></button>':
                  ' <button class="btn btn-info btn-sm" onclick="verdatos('.$value['idtrabajador'].')" data-toggle="tooltip" data-original-title="Ver datos"><i class="far fa-eye"></i></button>',
                "2"=>'<div class="user-block">
                  <img class="img-circle cursor-pointer" src="../dist/docs/all_trabajador/perfil/'. $value['imagen_perfil'] .'" alt="User Image" onerror="'.$imagen_error.'" onclick="ver_perfil(\'' . $imagen . '\', \''.encodeCadenaHtml($value['trabajador']).'\');" data-toggle="tooltip" data-original-title="Ver imagen">
                  <span class="username"><p class="text-primary m-b-02rem" >'. $value['trabajador'] .'</p></span>
                  <span class="description">'. $value['tipo_documento'] .': '. $value['numero_documento'] .' </span>
                  </div>',
                "3"=> $value['nombre_tipo'],
                "4"=> $value['nombre_ocupacion'],
                "5"=> '<div class="bg-color-242244245 " style="overflow: auto; resize: vertical; height: 45px;" >'.$value['html_desempenio'].'</div>',
                "6"=>'<a href="tel:+51'.quitar_guion($value['telefono']).'" data-toggle="tooltip" data-original-title="Llamar al trabajador.">'. $value['telefono'] . '</a>',
                "7"=>format_d_m_a($value['fecha_nacimiento']).'<b>: </b>'. '<b>'.$value['edad'].'</b>' ,
                "8"=> '<b>'.$value['banco'] .': </b>'. $value['cuenta_bancaria'] .' <br> <b>CCI: </b>'.$value['cci'],

                "9"=>(($value['estado'])?'<span class="text-center badge badge-success">Activado</span>': '<span class="text-center badge badge-danger">Desactivado</span>').$toltip,
                "10"=> $value['trabajador'],
                "11"=> $value['tipo_documento'],
                "12"=> $value['numero_documento'],
                "13"=> format_d_m_a($value['fecha_nacimiento']),
                "14"=>$value['edad'],
                "15"=> $value['banco'],
                "16"=> $value['cuenta_bancaria'],
                "17"=> $value['cci'],
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

        case 'listar_expulsado':
          $rspta=$trabajador->tbla_principal(0);
          //Vamos a declarar un array
          $data= Array(); $cont=1;
          
          if ($rspta['status']) {

            foreach ($rspta['data'] as $key => $value) { 
              $data[]=array(
                "0"=>$cont++,
                "1"=>($value['estado'])?'<button class="btn btn-warning btn-sm" onclick="mostrar('.$value['idtrabajador'].')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>'.
                  ' <button class="btn btn-danger btn-sm" onclick="desactivar('.$value['idtrabajador'].')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="far fa-trash-alt  "></i></button>'.
                  ' <button class="btn btn-info btn-sm" onclick="verdatos('.$value['idtrabajador'].')" data-toggle="tooltip" data-original-title="Ver datos"><i class="far fa-eye"></i></button>':
                  '<button class="btn btn-warning btn-sm" onclick="mostrar('.$value['idtrabajador'].')" data-toggle="tooltip" data-original-title="Editar"><i class="fa fa-pencil-alt"></i></button>'.
                  ' <button class="btn btn-primary btn-sm" onclick="activar('.$value['idtrabajador'].', \''.encodeCadenaHtml($value['trabajador']).'\')" data-toggle="tooltip" data-original-title="Recuperar"><i class="fa fa-check"></i></button>'.
                  ' <button class="btn btn-info btn-sm" onclick="verdatos('.$value['idtrabajador'].')" data-toggle="tooltip" data-original-title="Mostrar"><i class="far fa-eye"></i></button>',
                "2"=>'<div class="user-block">
                  <img class="img-circle" src="../dist/docs/all_trabajador/perfil/'. $value['imagen_perfil'] .'" alt="User Image" onerror="'.$imagen_error.'">
                  <span class="username"><p class="text-primary m-b-02rem" >'. $value['trabajador'] .'</p></span>
                  <span class="description">'. $value['tipo_documento'] .': '. $value['numero_documento'] .'<br>'.format_d_m_a($value['fecha_nacimiento']).' : '.$value['edad'].' años</span>
                  </div>',
                "3"=> '<div class="center-vertical">'. $value['nombre_tipo'] .'</div>',
                "4"=> '<div class="bg-color-242244245 " style="overflow: auto; resize: vertical; height: 45px;" >'.$value['html_desempenio'].'</div>',
                "5"=> '<a href="tel:+51'.quitar_guion($value['telefono']).'" data-toggle="tooltip" data-original-title="Llamar al trabajador.">'. $value['telefono'] . '</a>',
                "6"=> $value['descripcion_expulsion'] ,                
                "7"=>(($value['estado'])?'<span class="text-center badge badge-success">Activado</span>':
                '<span class="text-center badge badge-danger">Desactivado</span>').$toltip,

                "8"=> $value['trabajador'],
                "9"=> $value['tipo_documento'],
                "10"=> $value['numero_documento'],
                "11"=> format_d_m_a($value['fecha_nacimiento']),
                "12"=>$value['edad'],
                "13"=> $value['banco'],
                "14"=> $value['cuenta_bancaria'],
                "15"=> $value['cci'],
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
        
        case 'verdatos':
          $rspta=$trabajador->verdatos($idtrabajador);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;        

        case 'formato_banco':           
          $rspta=$trabajador->formato_banco($_POST["idbanco"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);           
        break;

        /* =========================== S E C C I O N   R E C U P E R A R   B A N C O S =========================== */
        case 'recuperar_banco':           
          $rspta=$trabajador->recuperar_banco();
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);           
        break;

        default: 
          $rspta = ['status'=>'error_code', 'message'=>'Te has confundido en escribir en el <b>swich.</b>', 'data'=>[]]; echo json_encode($rspta, true); 
        break;

      }

      //Fin de las validaciones de acceso
    } else {
      $retorno = ['status'=>'nopermiso', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
      echo json_encode($retorno);
    }
  }

  function calculaedad($fechanacimiento){
    $ano_diferencia = '-';
    if (empty($fechanacimiento) || $fechanacimiento=='0000-00-00') { } else{
      list($ano,$mes,$dia) = explode("-",$fechanacimiento);
      $ano_diferencia  = date("Y") - $ano;
      $mes_diferencia = date("m") - $mes;
      $dia_diferencia   = date("d") - $dia;
      if ($dia_diferencia < 0 || $mes_diferencia < 0)
        $ano_diferencia--;
    } 
    
    return $ano_diferencia;
  }

  ob_end_flush();

?>
