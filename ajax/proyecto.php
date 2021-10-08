<?php
  ob_start();

  if (strlen(session_id()) < 1){

    session_start();//Validamos si existe o no la sesión
  }
  if (!isset($_SESSION["nombre"])) {

		header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		
	} else {
    require_once "../modelos/Proyecto.php";

    $proyecto = new Proyecto();

    $idproyecto				    = isset($_POST["idproyecto"])? limpiarCadena($_POST["idproyecto"]):""; 
    $tipo_documento			  = isset($_POST["tipo_documento"])? limpiarCadena($_POST["tipo_documento"]):"";
    $numero_documento		  = isset($_POST["numero_documento"])? limpiarCadena($_POST["numero_documento"]):"";
    $empresa				      = isset($_POST["empresa"])? limpiarCadena($_POST["empresa"]):"";
    $nombre_proyecto		  = isset($_POST["nombre_proyecto"])? limpiarCadena($_POST["nombre_proyecto"]):"";
    $ubicacion				    = isset($_POST["ubicacion"])? limpiarCadena($_POST["ubicacion"]):"";
    $actividad_trabajo		= isset($_POST["actividad_trabajo"])? limpiarCadena($_POST["actividad_trabajo"]):"";
    $empresa_acargo 		  = isset($_POST['empresa_acargo'])? $_POST['empresa_acargo']:"";
    $costo					      = isset($_POST["costo"])? limpiarCadena($_POST["costo"]):"";
    $fecha_inicio			    = substr(isset($_POST["fecha_inicio_fin"])? limpiarCadena($_POST["fecha_inicio_fin"]):"", 0, 10);
    $fecha_fin				    = substr(isset($_POST["fecha_inicio_fin"])? limpiarCadena($_POST["fecha_inicio_fin"]):"", 13, 22);
    $plazo		            = isset($_POST["plazo"])? limpiarCadena($_POST["plazo"]):"";

    $doc1_contrato_obra		= isset($_POST["doc1"])? limpiarCadena($_POST["doc1"]):"";
    $doc_old_1		        = isset($_POST["doc_old_1"])? limpiarCadena($_POST["doc_old_1"]):"";

    $doc2_entrega_terreno	= isset($_POST["doc2"])? limpiarCadena($_POST["doc2"]):"";
    $doc_old_2	          = isset($_POST["doc_old_2"])? limpiarCadena($_POST["doc_old_2"]):"";

    $doc3_inicio_obra		  = isset($_POST["doc3"])? limpiarCadena($_POST["doc3"]):"";
    $doc_old_2		        = isset($_POST["doc_old_3"])? limpiarCadena($_POST["doc_old_3"]):"";

    // $idproyecto,$tipo_documento,$numero_documento,$empresa,$nombre_proyecto,$ubicacion,$actividad_trabajo,$empresa_acargo,$costo,$fecha_inicio,$fecha_fin,$doc1_contrato_obra,$doc2_entrega_terreno,$doc3_inicio_obra,
    switch ($_GET["op"]){

      case 'guardaryeditar':

        if (!isset($_SESSION["nombre"])) {

          header("Location: login.html");//Validamos el acceso solo a los usuarios logueados al sistema.

        } else {
          //Validamos el acceso solo al usuario logueado y autorizado.
          if ($_SESSION['acceso']==1)	{

            //*DOC 1*//
            if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {

              $flat_doc1 = false;

              $doc1      = $_POST["doc_old_1"];

            } else {

              $flat_doc1 = true;

              $ext_p     = explode(".", $_FILES["doc1"]["name"]);

              if ( $_FILES['doc1']['type'] == "application/pdf" ) {
                
                $doc1 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext_p);

                move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/pdf/" . $doc1);
              }
            }	

            //*DOC 2*//
            if (!file_exists($_FILES['doc2']['tmp_name']) || !is_uploaded_file($_FILES['doc2']['tmp_name'])) {

              $flat_doc2 = false;

              $doc2      = $_POST["doc_old_2"];

            } else {

              $flat_doc2 = true;

              $ext_p     = explode(".", $_FILES["doc2"]["name"]);

              if ( $_FILES['doc2']['type'] == "application/pdf" ) {
                
                $doc2 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext_p);

                move_uploaded_file($_FILES["doc2"]["tmp_name"], "../dist/pdf/" . $doc1);
              }
            }	

            //*DOC 3*//
            if (!file_exists($_FILES['doc3']['tmp_name']) || !is_uploaded_file($_FILES['doc3']['tmp_name'])) {

              $flat_doc3 = false;

              $doc3      = $_POST["doc_old_3"];

            } else {

              $flat_doc3 = true;

              $ext_p     = explode(".", $_FILES["doc3"]["name"]);

              if ( $_FILES['doc3']['type'] == "application/pdf" ) {
                
                $doc3 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext_p);

                move_uploaded_file($_FILES["doc3"]["tmp_name"], "../dist/pdf/" . $doc1);
              }
            }	

            if (empty($idproyecto)){

              $rspta=$proyecto->insertar($tipo_documento,$numero_documento,$empresa,$nombre_proyecto,$ubicacion,$actividad_trabajo,$empresa_acargo,$costo,$fecha_inicio,$fecha_fin,$doc1,$doc2,$doc3);

              echo $rspta ? "ok" : "No se pudieron registrar todos los datos del usuario";

            } else {
              if ($flat_foto == true) {

                $datos_f        = $proyecto->obtenerDoc1($idcomunicado);

                $nombre_img_ant = $datos_f->fetch_object()->foto;

                if ($nombre_img_ant != "") {

                  unlink("../dist/pdf/" . $nombre_img_ant);
                }
              }

              $rspta=$proyecto->editar($idproyecto,$tipo_documento,$numero_documento,$empresa,$nombre_proyecto,$ubicacion,$actividad_trabajo,$empresa_acargo,$costo,$fecha_inicio,$fecha_fin,$doc1,$doc2,$doc3);
              
              echo $rspta ? "ok" : "Usuario no se pudo actualizar";
            }
            //Fin de las validaciones de acceso
          } else {

            require 'noacceso.php';
          }
        }		
      break;

      case 'desactivar':
        if (!isset($_SESSION["nombre"])){

          header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.

        }	else {
          //Validamos el acceso solo al usuario logueado y autorizado.
          if ($_SESSION['acceso']==1) {

            $rspta=$proyecto->desactivar($idusuario);

            echo $rspta ? "ok" : "Usuario no se puede desactivar";
            //Fin de las validaciones de acceso
          } else {

            require 'noacceso.php';
          }
        }		
      break;

      case 'activar':
        if (!isset($_SESSION["nombre"]))
        {
          header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
        }	else {

          //Validamos el acceso solo al usuario logueado y autorizado.
          if ($_SESSION['acceso']==1)	{

            $rspta=$proyecto->activar($idusuario);

            echo $rspta ? "ok" : "Usuario no se puede activar";
            //Fin de las validaciones de acceso
          }	else {

            require 'noacceso.php';
          }
        }		
      break;

      case 'mostrar':

        if (!isset($_SESSION["nombre"])){

          header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.

        }else{

          //Validamos el acceso solo al usuario logueado y autorizado.
          if ($_SESSION['acceso']==1)	{

            $rspta=$proyecto->mostrar($idusuario);
            //Codificar el resultado utilizando json
            echo json_encode($rspta);
            //Fin de las validaciones de acceso
          }else{

            require 'noacceso.php';
          }
        }		
      break;

      case 'listar':
        if (!isset($_SESSION["nombre"])){

          header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.

        }else{
          //Validamos el acceso solo al usuario logueado y autorizado.
          if ($_SESSION['acceso']==1)	{

            $rspta=$proyecto->listar();
            //Vamos a declarar un array
            $data= Array();

            while ($reg=$rspta->fetch_object()){
              $data[]=array(
                "0"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idusuario.')"><i class="fas fa-pencil-alt"></i></button>'.
                  ' <button class="btn btn-danger" onclick="desactivar('.$reg->idusuario.')"><i class="far fa-trash-alt  "></i></button>':
                  '<button class="btn btn-warning" onclick="mostrar('.$reg->idusuario.')"><i class="fas fa-pencil-alt"></i></button>'.
                  ' <button class="btn btn-primary" onclick="activar('.$reg->idusuario.')"><i class="fa fa-check"></i></button>',
                "1"=>'<div class="user-block">
                    <img class="img-circle" src="../dist/img/usuarios/'. $reg->imagen .'" alt="User Image">
                    <span class="username"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'. $reg->nombres .'</p></span>
                    <span class="description">'. $reg->tipo_documento .': '. $reg->numero_documento .' </span>
                  </div>',
                "2"=>$reg->telefono,
                "3"=>$reg->login,
                "4"=>$reg->cargo,
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
          //Fin de las validaciones de acceso
          }
          else
          {
            require 'noacceso.php';
          }
        }
      break;	 
      
    }
  }
  ob_end_flush();
?>