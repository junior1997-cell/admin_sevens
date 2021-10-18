<?php
  ob_start();

  if (strlen(session_id()) < 1){

    session_start();//Validamos si existe o no la sesión
  }
  if (!isset($_SESSION["nombre"])) {    
     
		header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		die();

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
    $empresa_acargo 		  = isset($_POST['empresa_acargo'])? limpiarCadena($_POST['empresa_acargo']):"";
    $costo					      = isset($_POST["costo"])? limpiarCadena($_POST["costo"]):"";
    $fecha_inicio			    = substr(isset($_POST["fecha_inicio_fin"])? $_POST["fecha_inicio_fin"]:"", 0, 10);
    $fecha_fin				    = substr(isset($_POST["fecha_inicio_fin"])? $_POST["fecha_inicio_fin"]:"", 13, 22);
    $plazo		            = isset($_POST["plazo"])? limpiarCadena($_POST["plazo"]):"";

    $doc1_contrato_obra		= isset($_POST["doc1"])? limpiarCadena($_POST["doc1"]):"";
    $doc_old_1		        = isset($_POST["doc_old_1"])? limpiarCadena($_POST["doc_old_1"]):"";

    $doc2_entrega_terreno	= isset($_POST["doc2"])? limpiarCadena($_POST["doc2"]):"";
    $doc_old_2	          = isset($_POST["doc_old_2"])? limpiarCadena($_POST["doc_old_2"]):"";

    $doc3_inicio_obra		  = isset($_POST["doc3"])? limpiarCadena($_POST["doc3"]):"";
    $doc_old_2		        = isset($_POST["doc_old_3"])? limpiarCadena($_POST["doc_old_3"]):"";

    // $idproyecto,$tipo_documento,$numero_documento,$empresa,$nombre_proyecto,$ubicacion,$actividad_trabajo,
    // $empresa_acargo,$costo,$fecha_inicio,$fecha_fin,$doc1_contrato_obra,$doc2_entrega_terreno,$doc3_inicio_obra,
    switch ($_GET["op"]){

      case 'guardaryeditar':

        if (!isset($_SESSION["nombre"])) {

          header("Location: login.html");//Validamos el acceso solo a los usuarios logueados al sistema.

        } else {
          //Validamos el acceso solo al usuario logueado y autorizado.
          if ($_SESSION['escritorio']==1)	{

            //*DOC 1*//
            if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {

              $flat_doc1 = false;

              $doc1      = $_POST["doc_old_1"];

            } else {

              $flat_doc1 = true;

              $ext_doc1     = explode(".", $_FILES["doc1"]["name"]);

              if ( $_FILES['doc1']['type'] == "application/pdf" ) {
                
                $doc1 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext_doc1);

                move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/pdf/" . $doc1);
              }
            }	

            //*DOC 2*//
            if (!file_exists($_FILES['doc2']['tmp_name']) || !is_uploaded_file($_FILES['doc2']['tmp_name'])) {

              $flat_doc2 = false;

              $doc2      = $_POST["doc_old_2"];

            } else {

              $flat_doc2 = true;

              $ext_doc2     = explode(".", $_FILES["doc2"]["name"]);

              if ( $_FILES['doc2']['type'] == "application/pdf" ) {
                
                $doc2 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext_doc2);

                move_uploaded_file($_FILES["doc2"]["tmp_name"], "../dist/pdf/" . $doc2);
              }
            }	

            //*DOC 3*//
            if (!file_exists($_FILES['doc3']['tmp_name']) || !is_uploaded_file($_FILES['doc3']['tmp_name'])) {

              $flat_doc3 = false;

              $doc3      = $_POST["doc_old_3"];

            } else {

              $flat_doc3 = true;

              $ext_doc3     = explode(".", $_FILES["doc3"]["name"]);

              if ( $_FILES['doc3']['type'] == "application/pdf" ) {
                
                $doc3 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext_doc3);

                move_uploaded_file($_FILES["doc3"]["tmp_name"], "../dist/pdf/" . $doc3);
              }
            }	

            if (empty($idproyecto)){
              // insertamos en la bd
              $rspta=$proyecto->insertar($tipo_documento,$numero_documento,$empresa,$nombre_proyecto,$ubicacion,$actividad_trabajo,$empresa_acargo,$costo,$fecha_inicio,$fecha_fin,$plazo,$doc1,$doc2,$doc3);
              // echo $rspta ;
              echo $rspta ? "ok" : "No se pudieron registrar todos los datos del proyecto";

            } else {
              // validamos si existe el doc para eliminarlo
              if ($flat_doc1 == true) {

                $datos_f1 = $proyecto->obtenerDoc1($idproyecto);

                $doc1_ant = $datos_f1->fetch_object()->doc1_contrato_obra;

                if ($doc1_ant != "") {

                  unlink("../dist/pdf/" . $doc1_ant);
                }
              }

              if ($flat_doc2 == true) {

                $datos_f2 = $proyecto->obtenerDoc2($idproyecto);

                $doc2_ant = $datos_f2->fetch_object()->doc2_entrega_terreno;

                if ($doc2_ant != "") {

                  unlink("../dist/pdf/" . $doc2_ant);
                }
              }

              if ($flat_doc3 == true) {

                $datos_f3 = $proyecto->obtenerDoc3($idproyecto);

                $doc3_ant = $datos_f3->fetch_object()->doc3_inicio_obra;

                if ($doc3_ant != "") {

                  unlink("../dist/pdf/" . $doc3_ant);
                }
              }

              $rspta=$proyecto->editar($idproyecto,$tipo_documento,$numero_documento,$empresa,$nombre_proyecto,$ubicacion,$actividad_trabajo,$empresa_acargo,$costo,$fecha_inicio,$fecha_fin,$plazo,$doc1,$doc2,$doc3);
              
              echo $rspta ? "ok" : "Proyecto no se pudo actualizar";
            }
            //Fin de las validaciones de acceso
          } else {

            require 'noacceso.php';
          }
        }		
      break;

      case 'empezar_proyecto':
        if (!isset($_SESSION["nombre"])){

          header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
          die();

        }	else {
          //Validamos el acceso solo al usuario logueado y autorizado.
          if ($_SESSION['escritorio']==1) {

            $rspta=$proyecto->empezar_proyecto($idproyecto);

            echo $rspta ? "ok" : "No se logro empezar el proyecto";
            //Fin de las validaciones de acceso
          } else {

            require 'noacceso.php';
          }
        }		
      break;

      case 'terminar_proyecto':
        if (!isset($_SESSION["nombre"]))
        {
          header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
          die();
        }	else {

          //Validamos el acceso solo al usuario logueado y autorizado.
          if ($_SESSION['escritorio']==1)	{

            $rspta=$proyecto->terminar_proyecto($idproyecto);

            echo $rspta ? "ok" : "No se logro terminar el proyecto";
            //Fin de las validaciones de acceso
          }	else {

            require 'noacceso.php';
          }
        }		
      break;

      case 'reiniciar_proyecto':
        if (!isset($_SESSION["nombre"]))
        {
          header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
          die();
        }	else {

          //Validamos el acceso solo al usuario logueado y autorizado.
          if ($_SESSION['escritorio']==1)	{

            $rspta=$proyecto->reiniciar_proyecto($idproyecto);

            echo $rspta ? "ok" : "No se logro reiniciar el proyecto";
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
          if ($_SESSION['escritorio']==1)	{

            $rspta=$proyecto->mostrar($idproyecto);
            //Codificar el resultado utilizando json
            echo json_encode($rspta);
            //Fin de las validaciones de acceso
          }else{

            require 'noacceso.php';
          }
        }		
      break;

      case 'tablero-proyectos':

        if (!isset($_SESSION["nombre"])){

          header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.

        }else{

          //Validamos el acceso solo al usuario logueado y autorizado.
          if ($_SESSION['escritorio']==1)	{

            $rspta=$proyecto->tablero_proyectos();
            //Codificar el resultado utilizando json
            echo json_encode($rspta);
            //Fin de las validaciones de acceso
          }else{

            require 'noacceso.php';
          }
        }		
      break;
      case 'tablero-proveedores':

        if (!isset($_SESSION["nombre"])){

          header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.

        }else{

          //Validamos el acceso solo al usuario logueado y autorizado.
          if ($_SESSION['escritorio']==1)	{

            $rspta=$proyecto->tablero_proveedores();
            //Codificar el resultado utilizando json
            echo json_encode($rspta);
            //Fin de las validaciones de acceso
          }else{

            require 'noacceso.php';
          }
        }		
      break;
      case 'tablero-trabjadores':

        if (!isset($_SESSION["nombre"])){

          header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.

        }else{

          //Validamos el acceso solo al usuario logueado y autorizado.
          if ($_SESSION['escritorio']==1)	{

            $rspta=$proyecto->tablero_trabajadores();
            //Codificar el resultado utilizando json
            echo json_encode($rspta);
            //Fin de las validaciones de acceso
          }else{

            require 'noacceso.php';
          }
        }		
      break;
      case 'tablero-servicio':

        if (!isset($_SESSION["nombre"])){

          header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.

        }else{

          //Validamos el acceso solo al usuario logueado y autorizado.
          if ($_SESSION['escritorio']==1)	{

            $rspta=$proyecto->tablero_servicio();
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

        } else {
          //Validamos el acceso solo al usuario logueado y autorizado.
          if ( $_SESSION['escritorio'] == 1 )	{

            $rspta=$proyecto->listar();
            //Vamos a declarar un array
            $data= Array();

            while ($reg=$rspta->fetch_object()){

              $estado = "";
              $acciones = "";

              if ($reg->estado == '2') {

                $estado = '<span class="text-center badge badge-danger">No empezado</span>';
                $acciones = '<button class="btn btn-success" onclick="empezar_proyecto('.$reg->idproyecto.')" data-toggle="tooltip" data-original-title="Empezar proyecto" /*style="margin-right: 3px !important;"*/><i class="fa fa-check"></i></button>';
              } else {

                if ($reg->estado == '1') {

                  $estado = '<span class="text-center badge badge-warning">En proceso</span>';
                  $acciones = '<button class="btn btn-danger" onclick="terminar_proyecto('.$reg->idproyecto.')" data-toggle="tooltip" data-original-title="Terminar proyecto" /*style="margin-right: 3px !important;"*/><i class="fas fa-times"></i></button>';
                } else {

                  $estado = '<span class="text-center badge badge-success">Terminado</span>';
                  $acciones = '<button class="btn btn-primary" onclick="reiniciar_proyecto('.$reg->idproyecto.')" data-toggle="tooltip" data-original-title="Reiniciar proyecto" /*style="margin-right: 3px !important;"*/><i class="fas fa-sync-alt"></i></button>';
                }                
              }

              if (strlen($reg->empresa) >= 20 ) { $empresa = substr($reg->empresa, 0, 20).'...';  } else { $empresa = $reg->empresa; }

              if (strlen($reg->ubicacion) >= 20 ) { $ubicacion = substr($reg->ubicacion, 0, 20).'...';  } else { $ubicacion = $reg->ubicacion; }

              if (strlen($reg->nombre_proyecto) >= 21 ) { $nombre_proyecto = substr($reg->nombre_proyecto, 0, 21).'...'; } else { $nombre_proyecto = $reg->nombre_proyecto; }
                
                $abrir_proyecto = "'$reg->idproyecto', '$reg->nombre_proyecto'";

                $docs= "'$reg->doc1_contrato_obra', '$reg->doc2_entrega_terreno', '$reg->doc3_inicio_obra'";

                $tool = '"tooltip"';   $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>";                

                $data[]=array(
                  "0"=>'<button class="btn bg-secondary"  onclick="abrir_proyecto('.$abrir_proyecto.')" data-toggle="tooltip" data-original-title="Abrir proyecto" id="icon_folder_'.$reg->idproyecto.'">
                      <i class="fas fa-folder"></i>
                    </button> 
                    <button class="btn btn-warning" onclick="mostrar('.$reg->idproyecto.')" data-toggle="tooltip" data-original-title="Editar" /*style="margin-right: 3px !important;"*/>
                      <i class="fas fa-pencil-alt"></i> 
                    </button>
                    '.$acciones.'
                    <button class="btn bg-info" onclick="mostrar_detalle('.$reg->idproyecto.')" data-toggle="tooltip" data-original-title="Ver detalle proyecto">
                      <i class="fas fa-eye"></i>
                    </button> ',
                  "1"=>'<div class="user-block">
                      <img class="img-circle" src="../dist/svg/empresa-logo.svg" alt="User Image">
                      <span class="username"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'. $empresa .'</p></span>
                      <span class="description">'. $reg->tipo_documento .': '. $reg->numero_documento .' </span>
                    </div>',
                  "2"=> '<span class="description" >'.$nombre_proyecto.'</span>' ,
                  "3"=>$ubicacion,
                  "4"=>$reg->costo,
                  "5"=>'<center>
                      <a type="btn btn-danger" class=""  href="#"  onclick="ver_modal_docs('.$docs.')"data-toggle="tooltip" data-original-title="Ver documentos" >
                        <img src="../dist/svg/pdf.svg" class="card-img-top" height="35" width="30" >
                      </a>
                    </center>',
                  "6"=>'<center>
                    <a type="btn btn-danger" class=""  href="#"  onclick="ver_modal_docs('.$docs.')"data-toggle="tooltip" data-original-title="Ver documentos" >
                      <img src="../dist/svg/logo-excel.svg" class="card-img-top" height="35" width="30" >
                    </a>
                  </center>',
                  "7"=> $estado.''.$toltip
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