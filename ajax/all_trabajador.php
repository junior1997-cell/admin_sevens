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
      $idtrabajador		= isset($_POST["idtrabajador"])? limpiarCadena($_POST["idtrabajador"]):"";
      $nombre 		    = isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
      $tipo_documento	= isset($_POST["tipo_documento"])? limpiarCadena($_POST["tipo_documento"]):"";
      $num_documento	= isset($_POST["num_documento"])? limpiarCadena($_POST["num_documento"]):"";
      $direccion		  = isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
      $telefono		    = isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
      $nacimiento		    = isset($_POST["nacimiento"])? limpiarCadena($_POST["nacimiento"]):"";
      $edad		          = isset($_POST["edad"])? limpiarCadena($_POST["edad"]):"";
      $c_bancaria		    = isset($_POST["c_bancaria"])? limpiarCadena($_POST["c_bancaria"]):"";
      $email			      = isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
      $banco			      = isset($_POST["banco"])? limpiarCadena($_POST["banco"]):"";
      $titular_cuenta		= isset($_POST["titular_cuenta"])? limpiarCadena($_POST["titular_cuenta"]):"";

      $imagen1			    = isset($_POST["foto1"])? limpiarCadena($_POST["foto1"]):"";
      $imagen2			    = isset($_POST["foto2"])? limpiarCadena($_POST["foto2"]):"";
      $imagen3			    = isset($_POST["foto3"])? limpiarCadena($_POST["foto3"]):"";

      switch ($_GET["op"]) {

        case 'guardaryeditar':

          // imgen de perfil
          if (!file_exists($_FILES['foto1']['tmp_name']) || !is_uploaded_file($_FILES['foto1']['tmp_name'])) {

						$imagen1=$_POST["foto1_actual"]; $flat_img1 = false;

					} else {

						$ext1 = explode(".", $_FILES["foto1"]["name"]); $flat_img1 = true;						

            $imagen1 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

            move_uploaded_file($_FILES["foto1"]["tmp_name"], "../dist/img/usuarios/" . $imagen1);
						
					}

          // imgen DNI ANVERSO
          if (!file_exists($_FILES['foto2']['tmp_name']) || !is_uploaded_file($_FILES['foto2']['tmp_name'])) {

						$imagen2=$_POST["foto2_actual"]; $flat_img2 = false;

					} else {

						$ext2 = explode(".", $_FILES["foto2"]["name"]); $flat_img2 = true;

            $imagen2 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext2);

            move_uploaded_file($_FILES["foto2"]["tmp_name"], "../dist/img/usuarios/" . $imagen2);
						
					}

          // imgen DNI REVERSO
          if (!file_exists($_FILES['foto3']['tmp_name']) || !is_uploaded_file($_FILES['foto3']['tmp_name'])) {

						$imagen3=$_POST["foto3_actual"]; $flat_img3 = false;

					} else {

						$ext3 = explode(".", $_FILES["foto3"]["name"]); $flat_img3 = true;
            
            $imagen3 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext3);

            move_uploaded_file($_FILES["foto3"]["tmp_name"], "../dist/img/usuarios/" . $imagen3);
						
					}

          if (empty($idtrabajador)){

            $rspta=$trabajador->insertar($nombre, $tipo_documento, $num_documento, $direccion, $telefono, $nacimiento, $edad,  $c_bancaria, $email, $banco, $titular_cuenta, $imagen1, $imagen2, $imagen3);
            
            echo $rspta ? "ok" : "No se pudieron registrar todos los datos del Trabajador";
  
          }else {

            // validamos si existe LA IMG para eliminarlo
            if ($flat_img1 == true) {

              $datos_f1 = $trabajador->obtenerImg($idtrabajador);

              $img1_ant = $datos_f1->fetch_object()->imagen_perfil;

              if ($img1_ant != "") {

                unlink("../dist/img/usuarios/" . $img1_ant);
              }
            }

            if ($flat_img2 == true) {

              $datos_f2 = $trabajador->obtenerImg($idtrabajador);

              $img2_ant = $datos_f2->fetch_object()->imagen_dni_anverso;

              if ($img2_ant != "") {

                unlink("../dist/img/usuarios/" . $img2_ant);
              }
            }

            if ($flat_img3 == true) {

              $datos_f3 = $trabajador->obtenerImg($idtrabajador);

              $img3_ant = $datos_f3->fetch_object()->imagen_dni_reverso;

              if ($img3_ant != "") {

                unlink("../dist/img/usuarios/" . $img3_ant);
              }
            }

            // editamos un trabajador existente
            $rspta=$trabajador->editar($idtrabajador, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $nacimiento, $edad, $c_bancaria, $email, $banco, $titular_cuenta, $imagen1, $imagen2, $imagen3);
            
            echo $rspta ? "ok" : "Trabajador no se pudo actualizar";
          }            

        break;

        case 'desactivar':

          $rspta=$trabajador->desactivar($idtrabajador);

 				  echo $rspta ? "Usuario Desactivado" : "Trabajador no se puede desactivar";

        break;

        case 'activar':

          $rspta=$trabajador->activar($idtrabajador);

 				  echo $rspta ? "Usuario activado" : "Trabajador no se puede activar";

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

          $imagen_error = "this.src='../dist/svg/user_default.svg'";
          
          while ($reg=$rspta->fetch_object()){
            $data[]=array(
              "0"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idtrabajador.')"><i class="fas fa-pencil-alt"></i></button>'.
                ' <button class="btn btn-danger" onclick="desactivar('.$reg->idtrabajador.')"><i class="far fa-trash-alt  "></i></button>'.
                ' <button class="btn btn-info" onclick="verdatos('.$reg->idtrabajador.')"><i class="far fa-eye"></i></button>':
                '<button class="btn btn-warning" onclick="mostrar('.$reg->idtrabajador.')"><i class="fa fa-pencil-alt"></i></button>'.
                ' <button class="btn btn-primary" onclick="activar('.$reg->idtrabajador.')"><i class="fa fa-check"></i></button>'.
                ' <button class="btn btn-info" onclick="verdatos('.$reg->idtrabajador.')"><i class="far fa-eye"></i></button>',
              "1"=>'<div class="user-block">
                <img class="img-circle" src="../dist/img/usuarios/'. $reg->imagen_perfil .'" alt="User Image" onerror="'.$imagen_error.'">
                <span class="username"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'. $reg->nombres .'</p></span>
                <span class="description">'. $reg->tipo_documento .': '. $reg->numero_documento .' </span>
                </div>',
              "2"=> '<b>'.$reg->banco .': </b>'. $reg->cuenta_bancaria,
              "3"=>$reg->telefono,
              "4"=>$reg->fecha_nacimiento.' : '.$reg->edad,
              "5"=>($reg->estado)?'<span class="text-center badge badge-success">Activado</span>':
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
           
          //Validamos el acceso solo al usuario logueado y autorizado.
          if ($_SESSION['trabajador']==1) {

            $rspta=$trabajador->verdatos($idtrabajador);
            //Codificar el resultado utilizando json
            echo json_encode($rspta);
          }
        break;

        case 'select2Banco': 

          $rspta = $trabajador->select2_banco();
      
          while ($reg = $rspta->fetch_object())  {

            echo '<option value=' . $reg->id . '>' . $reg->nombre .'</option>';
          }

        break;
      }

      //Fin de las validaciones de acceso
    } else {

      require 'noacceso.php';
    }
  }

  ob_end_flush();

?>