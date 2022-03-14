<?php

  ob_start();

  if (strlen(session_id()) < 1) {

    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {

    header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.

  } else {

    //Validamos el acceso solo al usuario logueado y autorizado.
    if ($_SESSION['recurso'] == 1) {

      require_once "../modelos/AllTrabajador.php";

      $trabajador = new AllTrabajador();

      //$idtrabajador,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$nacimiento,$tipo_trabajador,$desempenio,$c_bancaria,$email,$cargo,$banco,$tutular_cuenta,$sueldo_diario,$sueldo_mensual,$sueldo_hora,$imagen	
      $idtrabajador	  	= isset($_POST["idtrabajador"])? limpiarCadena($_POST["idtrabajador"]):"";
      $nombre 		      = isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
      $tipo_documento 	= isset($_POST["tipo_documento"])? limpiarCadena($_POST["tipo_documento"]):"";
      $num_documento  	= isset($_POST["num_documento"])? limpiarCadena($_POST["num_documento"]):"";
      $direccion		    = isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
      $telefono		      = isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
      $nacimiento		    = isset($_POST["nacimiento"])? limpiarCadena($_POST["nacimiento"]):"";
      $edad		          = isset($_POST["edad"])? limpiarCadena($_POST["edad"]):"";
      $c_bancaria		    = isset($_POST["c_bancaria"])? limpiarCadena($_POST["c_bancaria"]):"";
      $c_bancaria_format= isset($_POST["c_bancaria"])? limpiarCadena($_POST["c_bancaria"]):"";
      $email			      = isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
      $banco			      = isset($_POST["banco"])? limpiarCadena($_POST["banco"]):"";
      $titular_cuenta		= isset($_POST["titular_cuenta"])? limpiarCadena($_POST["titular_cuenta"]):"";
      
      $cci	          	= isset($_POST["cci"])? limpiarCadena($_POST["cci"]):"";
      $cci_format      	= isset($_POST["cci"])? limpiarCadena($_POST["cci"]):"";
      $tipo	          	= isset($_POST["tipo"])? limpiarCadena($_POST["tipo"]):"";
      $ocupacion	      = isset($_POST["ocupacion"])? limpiarCadena($_POST["ocupacion"]):"";
      $ruc	          	= isset($_POST["ruc"])? limpiarCadena($_POST["ruc"]):"";

      $imagen1			    = isset($_POST["foto1"])? limpiarCadena($_POST["foto1"]):"";
      $imagen2			    = isset($_POST["foto2"])? limpiarCadena($_POST["foto2"]):"";
      $imagen3			    = isset($_POST["foto3"])? limpiarCadena($_POST["foto3"]):"";
      //cvs
      $cv_documentado			    = isset($_POST["doc4"])? limpiarCadena($_POST["doc4"]):"";
      $cv_nodocumentado			  = isset($_POST["doc5"])? limpiarCadena($_POST["doc5"]):"";
      //$cci,$tipo,$ocupacion,$ruc,$cv_documentado,$cv_nodocumentado
      switch ($_GET["op"]) {

        case 'guardaryeditar':

          // imgen de perfil
          if (!file_exists($_FILES['foto1']['tmp_name']) || !is_uploaded_file($_FILES['foto1']['tmp_name'])) {

						$imagen1=$_POST["foto1_actual"]; $flat_img1 = false;

					} else {

						$ext1 = explode(".", $_FILES["foto1"]["name"]); $flat_img1 = true;						

            $imagen1 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

            move_uploaded_file($_FILES["foto1"]["tmp_name"], "../dist/docs/all_trabajador/perfil/" . $imagen1);
						
					}

          // imgen DNI ANVERSO
          if (!file_exists($_FILES['foto2']['tmp_name']) || !is_uploaded_file($_FILES['foto2']['tmp_name'])) {

						$imagen2=$_POST["foto2_actual"]; $flat_img2 = false;

					} else {

						$ext2 = explode(".", $_FILES["foto2"]["name"]); $flat_img2 = true;

            $imagen2 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext2);

            move_uploaded_file($_FILES["foto2"]["tmp_name"], "../dist/docs/all_trabajador/dni_anverso/" . $imagen2);
						
					}

          // imgen DNI REVERSO
          if (!file_exists($_FILES['foto3']['tmp_name']) || !is_uploaded_file($_FILES['foto3']['tmp_name'])) {

						$imagen3=$_POST["foto3_actual"]; $flat_img3 = false;

					} else {

						$ext3 = explode(".", $_FILES["foto3"]["name"]); $flat_img3 = true;
            
            $imagen3 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext3);

            move_uploaded_file($_FILES["foto3"]["tmp_name"], "../dist/docs/all_trabajador/dni_reverso/" . $imagen3);
						
					}
          // cv documentado
          if (!file_exists($_FILES['doc4']['tmp_name']) || !is_uploaded_file($_FILES['doc4']['tmp_name'])) {

            $cv_documentado=$_POST["doc_old_4"]; $flat_cv1 = false;

          } else {

            $ext3 = explode(".", $_FILES["doc4"]["name"]); $flat_cv1 = true;
            
            $cv_documentado = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext3);

            move_uploaded_file($_FILES["doc4"]["tmp_name"], "../dist/docs/all_trabajador/cv_documentado/" .  $cv_documentado);
            
          }
          // cv  no documentado
          if (!file_exists($_FILES['doc5']['tmp_name']) || !is_uploaded_file($_FILES['doc5']['tmp_name'])) {

            $cv_nodocumentado=$_POST["doc_old_5"]; $flat_cv2 = false;

          } else {

            $ext3 = explode(".", $_FILES["doc5"]["name"]); $flat_cv2 = true;
            
            $cv_nodocumentado = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext3);

            move_uploaded_file($_FILES["doc5"]["tmp_name"], "../dist/docs/all_trabajador/cv_no_documentado/" . $cv_nodocumentado);
            
          }

          if (empty($idtrabajador)){

            $rspta=$trabajador->insertar($nombre, $tipo_documento, $num_documento, $direccion, $telefono, $nacimiento, $edad, str_replace("-", "", $c_bancaria), $c_bancaria_format, $email, $banco, $titular_cuenta, $imagen1, $imagen2, $imagen3, str_replace("-", "", $cci), $cci_format, $tipo, $ocupacion, $ruc, $cv_documentado, $cv_nodocumentado);
            
            echo $rspta ? "ok" : "No se pudieron registrar todos los datos del Trabajador";
  
          }else {

            // validamos si existe LA IMG para eliminarlo
            if ($flat_img1 == true) {

              $datos_f1 = $trabajador->obtenerImg($idtrabajador);

              $img1_ant = $datos_f1->fetch_object()->imagen_perfil;

              if ($img1_ant != "") {

                unlink("../dist/docs/all_trabajador/perfil/" . $img1_ant);
              }
            }

            //imagen_dni_anverso
            if ($flat_img2 == true) {

              $datos_f2 = $trabajador->obtenerImg($idtrabajador);

              $img2_ant = $datos_f2->fetch_object()->imagen_dni_anverso;

              if ($img2_ant != "") {

                unlink("../dist/docs/all_trabajador/dni_anverso/" . $img2_ant);
              }
            }

            //imagen_dni_reverso
            if ($flat_img3 == true) {

              $datos_f3 = $trabajador->obtenerImg($idtrabajador);

              $img3_ant = $datos_f3->fetch_object()->imagen_dni_reverso;

              if ($img3_ant != "") {

                unlink("../dist/docs/all_trabajador/dni_reverso/" . $img3_ant);
              }
            }
            //cvs
            if ($flat_cv1 == true) {

              $datos_cv1 = $trabajador->obtenercv($idtrabajador);

              $cv1_ant = $datos_cv1->fetch_object()->cv_documentado;

              if ($cv1_ant != "") {

                unlink("../dist/docs/all_trabajador/cv_documentado/" . $cv1_ant);
              }
            }

            if ($flat_cv2 == true) {

              $datos_cv2 = $trabajador->obtenercv($idtrabajador);

              $cv2_ant = $datos_cv2->fetch_object()->cv_no_documentado;

              if ($cv2_ant != "") {

                unlink("../dist/docs/all_trabajador/cv_no_documentado/" . $cv2_ant);
              }
            }

            // editamos un trabajador existente
            $rspta=$trabajador->editar($idtrabajador, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $nacimiento, $edad, str_replace("-", "", $c_bancaria), $c_bancaria_format, $email, $banco, $titular_cuenta, $imagen1, $imagen2, $imagen3, str_replace("-", "", $cci), $cci_format, $tipo, $ocupacion, $ruc, $cv_documentado, $cv_nodocumentado);
            
            echo $rspta ? "ok" : "Trabajador no se pudo actualizar";
          }            

        break;

        case 'desactivar':

          $ok = ['ok'=> true, 'redirected'=> true, 'status'=> 200];

          $error = ['ok'=> false, 'redirected'=> true, 'status'=> 404];

          $idtrabajador = $_GET["idtrabajador"];  $descripcion = $_GET["descripcion"];

          $rspta=$trabajador->desactivar($idtrabajador, $descripcion);

 				  echo $rspta ? json_encode($ok) : json_encode($error);

        break;

        case 'activar':

          $rspta=$trabajador->activar($idtrabajador);

 				  echo $rspta ? "Trabajador activado" : "Trabajador no se puede activar";

        break;
        
        case 'desactivar_1':

          $rspta=$trabajador->desactivar_1($idtrabajador);

 				  echo $rspta ? "Trabajador Descativado" : "Trabajador no se puede Descativar";

        break;

        case 'eliminar':

          $rspta=$trabajador->eliminar($idtrabajador);

 				  echo $rspta ? "ok" : "Trabajador no se puede eliminado";

        break;

        case 'mostrar':

          $rspta=$trabajador->mostrar($idtrabajador);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);

        break;

        case 'listar':          

          $rspta=$trabajador->listar();
          //Vamos a declarar un array
          $data= Array();
          $cont=1;

          $imagen_error = "this.src='../dist/svg/user_default.svg'";
          
          while ($reg=$rspta->fetch_object()){
            $data[]=array(
              "0"=>$cont++,
              "1"=>($reg->estado)?'<button class="btn btn-warning btn-sm" onclick="mostrar('.$reg->idtrabajador.')"><i class="fas fa-pencil-alt"></i></button>'.
                ' <button class="btn btn-danger btn-sm" onclick="eliminar('.$reg->idtrabajador.')"><i class="fas fa-skull-crossbones"></i></button>'.
                ' <button class="btn btn-info btn-sm" onclick="verdatos('.$reg->idtrabajador.')"><i class="far fa-eye"></i></button>':
                ' <button class="btn btn-info btn-sm" onclick="verdatos('.$reg->idtrabajador.')"><i class="far fa-eye"></i></button>',
              "2"=>'<div class="user-block">
                <img class="img-circle" src="../dist/docs/all_trabajador/perfil/'. $reg->imagen_perfil .'" alt="User Image" onerror="'.$imagen_error.'">
                <span class="username"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'. $reg->nombres .'</p></span>
                <span class="description">'. $reg->tipo_documento .': '. $reg->numero_documento .' </span>
                </div>',
              "3"=> $reg->nombre_tipo,
              "4"=> $reg->nombre_ocupacion,
              "5"=>'<a href="tel:+51'.quitar_guion($reg->telefono).'" data-toggle="tooltip" data-original-title="Llamar al trabajador.">'. $reg->telefono . '</a>',
              "6"=>format_d_m_a($reg->fecha_nacimiento).'<b>: </b>'.$reg->edad,
              "7"=> '<b>'.$reg->banco .': </b>'. $reg->cuenta_bancaria_format .' <br> <b>CCI: </b>'.$reg->cci_format,
              "8"=>($reg->estado)?'<span class="text-center badge badge-success">Activado</span>':
              '<span class="text-center badge badge-danger">Desactivado</span>'
              );
          }
          $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
            "data"=>$data);
          echo json_encode($results);

        break;  

        case 'listar_expulsado':
          $rspta=$trabajador->listar_expulsado();
          //Vamos a declarar un array
          $data= Array();
          $cont=1;
          $imagen_error = "this.src='../dist/svg/user_default.svg'";
          
          while ($reg=$rspta->fetch_object()){
            $data[]=array(
              "0"=>$cont++,
              "1"=>($reg->estado)?'<button class="btn btn-warning btn-sm" onclick="mostrar('.$reg->idtrabajador.')"><i class="fas fa-pencil-alt"></i></button>'.
                ' <button class="btn btn-danger btn-sm" onclick="desactivar('.$reg->idtrabajador.')"><i class="far fa-trash-alt  "></i></button>'.
                ' <button class="btn btn-info btn-sm" onclick="verdatos('.$reg->idtrabajador.')"><i class="far fa-eye"></i></button>':
                '<button class="btn btn-warning btn-sm" onclick="mostrar('.$reg->idtrabajador.')"><i class="fa fa-pencil-alt"></i></button>'.
                ' <button class="btn btn-primary btn-sm" onclick="activar('.$reg->idtrabajador.')"><i class="fa fa-check"></i></button>'.
                ' <button class="btn btn-info btn-sm" onclick="verdatos('.$reg->idtrabajador.')"><i class="far fa-eye"></i></button>',
              "2"=>'<div class="user-block">
                <img class="img-circle" src="../dist/docs/all_trabajador/perfil/'. $reg->imagen_perfil .'" alt="User Image" onerror="'.$imagen_error.'">
                <span class="username"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'. $reg->nombres .'</p></span>
                <span class="description">'. $reg->tipo_documento .': '. $reg->numero_documento .'<br>'.format_d_m_a($reg->fecha_nacimiento).' : '.$reg->edad.' años</span>
                </div>',
              "3"=> '<div class="center-vertical">'. $reg->nombre_tipo .'</div>',
              "4"=> $reg->nombre_ocupacion,
              "5"=> '<a href="tel:+51'.quitar_guion($reg->telefono).'" data-toggle="tooltip" data-original-title="Llamar al trabajador.">'. $reg->telefono . '</a>',
              "6"=> $reg->descripcion_expulsion ,
              "7"=>($reg->estado)?'<span class="text-center badge badge-success">Activado</span>':
              '<span class="text-center badge badge-danger">Desactivado</span>'
              );
          }
          $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
            "data"=>$data);
          echo json_encode($results);
        break;
        
        case 'verdatos':
          $rspta=$trabajador->verdatos($idtrabajador);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);
        break;

        case 'select2Banco': 

          $rspta = $trabajador->select2_banco();
      
          while ($reg = $rspta->fetch_object())  {

            echo '<option value=' . $reg->id . '>' . $reg->nombre . ((empty($reg->alias)) ? "" : " - $reg->alias" ) .'</option>';
          }

        break;

        case 'formato_banco':
           
          $rspta=$trabajador->formato_banco($_POST["idbanco"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);
           
        break;
      }

      //Fin de las validaciones de acceso
    } else {

      require 'noacceso.php';
    }
  }

  function quitar_guion($numero){ return str_replace("-", "", $numero); } 

   // convierte de una fecha(dd-mm-aa): 23-12-2021 a una fecha(aa-mm-dd): 2021-12-23
   function format_a_m_d( $fecha ) {

    if (!empty($fecha)) {

      $fecha_expl = explode("-", $fecha);

      $fecha_convert =  $fecha_expl[0]."-".$fecha_expl[1]."-".$fecha_expl[2];

    }else{

      $fecha_convert = "";
    }   

    return $fecha_convert;
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
