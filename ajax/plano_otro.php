<?php

  ob_start();

  if (strlen(session_id()) < 1) {

    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {

    header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.

  } else {

    //Validamos el acceso solo al usuario logueado y autorizado.
    if ($_SESSION['plano_otro'] == 1) {

      require_once "../modelos/Plano_otro.php";

      $plano_otro = new PlanoOtro();

      //$idtrabajador,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$nacimiento,$tipo_trabajador,$desempenio,$c_bancaria,$email,$cargo,$banco,$tutular_cuenta,$sueldo_diario,$sueldo_mensual,$sueldo_hora,$imagen	
      $idproyecto		        = isset($_POST["idproyecto"])? limpiarCadena($_POST["idproyecto"]):"";
      $idcarpeta		        = isset($_POST["idcarpeta"])? limpiarCadena($_POST["idcarpeta"]):"";
      $nombre_carpeta       = isset($_POST["nombre_carpeta"])? limpiarCadena($_POST["nombre_carpeta"]):"";
      $descripcion_carpeta  = isset($_POST["descripcion_carpeta"])? limpiarCadena($_POST["descripcion_carpeta"]):"";

      $idplano_otro		= isset($_POST["idplano_otro"])? limpiarCadena($_POST["idplano_otro"]):"";
      $id_carpeta		  = isset($_POST["id_carpeta"])? limpiarCadena($_POST["id_carpeta"]):"";
      $nombre 		    = isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
      $descripcion	  = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";  
      $doc1	          = isset($_POST["doc1"])? limpiarCadena($_POST["doc1"]):"";      
      $doc_old_1	    = isset($_POST["doc_old_1"])? limpiarCadena($_POST["doc_old_1"]):"";  

      switch ($_GET["op"]) {

        case 'guardaryeditar_carpeta':

          if (empty($idcarpeta)){

            $rspta=$plano_otro->insertar_carpeta($idproyecto, $nombre_carpeta, $descripcion_carpeta);
            
            echo $rspta ? "ok" : "No se pudieron registrar todos los datos de la carpeta";
  
          }else {

            // editamos un documento existente
            $rspta=$plano_otro->editar_carpeta( $idcarpeta, $idproyecto, $nombre_carpeta, $descripcion_carpeta);
            
            echo $rspta ? "ok" : "Carpeta no se pudo actualizar";
          }            

        break;

        case 'guardaryeditar_plano':

          // imgen de perfil
          if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {

						$imagen1=$_POST["doc_old_1"]; $flat_img1 = false;

					} else {

						$ext1 = explode(".", $_FILES["doc1"]["name"]); $flat_img1 = true;						

            $imagen1 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

            move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/plano_otro/archivos/" . $imagen1);
						
					}

          if (empty($idplano_otro)){

            $rspta=$plano_otro->insertar_plano($id_carpeta, $nombre, $descripcion, $imagen1);
            
            echo $rspta ? "ok" : "No se pudieron registrar todos los datos del documento";
  
          }else {

            if ($flat_img1 == true) {

              $datos_f1 = $plano_otro->obtenerDoc($idplano_otro);

              $doc1_ant = $datos_f1->fetch_object()->doc;

              if ($doc1_ant != "") {

                unlink("../dist/docs/plano_otro/archivos/" . $doc1_ant);
              }
            }

            // editamos un documento existente
            $rspta=$plano_otro->editar_plano( $idplano_otro, $id_carpeta, $nombre, $descripcion, $imagen1);
            
            echo $rspta ? "ok" : "Documento no se pudo actualizar";
          }            

        break;

        case 'desactivar_carpeta':

          $rspta = $plano_otro->desactivar_carpeta($idplano_otro);

 				  echo $rspta ? "Carpeta Desactivada" : "Carpeta no se puede desactivar";

        break;

        case 'activar_carpeta':

          $rspta = $plano_otro->activar_carpeta($idplano_otro);

 				  echo $rspta ? "Carpeta activada" : "Carpeta no se puede activar";

        break;
        
        case 'eliminar_carpeta':

          $rspta = $plano_otro->eliminar_carpeta($idplano_otro);

 				  echo $rspta ? "Carpeta Eliminada" : "Carpeta no se puede Eliminar";

        break;

        case 'desactivar_plano':

          $rspta = $plano_otro->desactivar_plano($idplano_otro);

 				  echo $rspta ? "Documento Desactivado" : "Documento no se puede desactivar";

        break;

        case 'activar_plano':

          $rspta = $plano_otro->activar_plano($idplano_otro);

 				  echo $rspta ? "Documento activado" : "Documento no se puede activar";

        break;

        case 'eliminar_plano':

          $rspta = $plano_otro->eliminar_plano($idplano_otro);

 				  echo $rspta ? "Documento Eliminada" : "Documento no se puede Eliminada";

        break;

        case 'mostrar_carpeta':

          $rspta=$plano_otro->mostrar_carpeta($idplano_otro);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);

        break;

        case 'mostrar_plano':

          $rspta=$plano_otro->mostrar_plano($idplano_otro);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);

        break;

        case 'listar_carpeta':          

          $nube_proyecto = $_GET["nube_idproyecto"];

          $rspta=$plano_otro->listar_carpeta($nube_proyecto);
          //Vamos a declarar un array
          $data= Array();         
          $cont = 1;
          while ($reg=$rspta->fetch_object()){           
            
            $docs= "'$reg->nombre', '$reg->idcarpeta'";

            $data[]=array(
              "0"=>$cont++,
              "1"=>($reg->estado)?'<button class="btn btn-warning btn-sm" onclick="mostrar_carpeta('.$reg->idcarpeta.')"><i class="fas fa-pencil-alt"></i></button>'.
                ' <button class="btn btn-danger btn-sm" onclick="eliminar_carpeta('.$reg->idcarpeta.')"><i class="fas fa-skull-crossbones"></i></button>'.
                ' <button class="btn btn-info btn-sm" onclick="listar_plano('.$docs.')"><i class="far fa-eye"></i></button>':
                ' <button class="btn btn-warning btn-sm" onclick="mostrar_carpeta('.$reg->idcarpeta.')"><i class="fa fa-pencil-alt"></i></button>'.
                ' <button class="btn btn-primary btn-sm" onclick="activar_carpeta('.$reg->idcarpeta.')"><i class="fa fa-check"></i></button>'.
                ' <button class="btn btn-info btn-sm" onclick="listar_plano('.$docs.')"><i class="far fa-eye"></i></button>',
              "2"=>'<div class="user-block">
                <img class="img-circle" src="../dist/svg/carpeta.svg" alt="User Image" ">
                <span class="username"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'. $reg->nombre .'</p></span>
                <span class="description"><b>Creado el:</b> '. date("d/m/Y g:i a", strtotime($reg->created_at)) .' </span>
              </div>',
              "3"=>'<textarea cols="30" rows="1" class="text_area_clss" readonly="">'.$reg->descripcion.'</textarea>',
              "4"=>($reg->estado)?'<span class="text-center badge badge-success">Activado</span>':'<span class="text-center badge badge-danger">Desactivado</span>'
            );
          }
          $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
            "data"=>$data);
          echo json_encode($results);

        break;

        case 'listar_plano':
          
          $id_carpeta = $_GET["id_carpeta"];

          $rspta=$plano_otro->listar_plano($id_carpeta);
          //Vamos a declarar un array
          $data= Array();

          $imagen_error = "this.src='../dist/svg/user_default.svg'";
          $cont=1;
          while ($reg=$rspta->fetch_object()){

            $exten1 = explode(".", $reg->doc );  $exten2 = end($exten1); $img = ""; //$descripcion="";
            
            $docs= "'$reg->nombre', '$reg->descripcion', '$reg->doc'";           

            if ( $exten2 == "xls") {
              $img = '<img src="../dist/svg/xls.svg" height="auto" width="60" >';
            } else {
              if ( $exten2 == "xlsx" ) {
                $img = '<img src="../dist/svg/xlsx.svg" height="auto" width="60" >';
              }else{
                if ( $exten2 == "csv" ) {
                  $img = '<img src="../dist/svg/csv.svg" height="auto" width="60" >';
                }else{
                  if ( $exten2 == "xlsm" ) {
                    $img = '<img src="../dist/svg/xlsm.svg" height="auto" width="60" >';
                  }else{
                    if ( $exten2 == "pdf" ) {
                      $img = '<img src="../dist/svg/pdf.svg" height="auto" width="60" >';
                    }else{
                      if ( $exten2 == "dwg" ) {
                        $img = '<img src="../dist/svg/dwg.svg" height="auto" width="60" >';
                      }else{
                        if ( $exten2 == "zip" || $exten2 == "rar" || $exten2 == "iso" ) {
                          $img ='<img src="../dist/img/default/zip.png" height="auto" width="60" >';
                        }else{
                          if ( $exten2 == "jpeg" || $exten2 == "jpg" || $exten2 == "jpe" || $exten2 == "jfif" || $exten2 == "gif" || $exten2 == "png" || $exten2 == "tiff" || $exten2 == "tif" || $exten2 == "webp" || $exten2 == "bmp" ) { 
                            $img = '<img src="../dist/docs/plano_otro/archivos/'.$reg->doc.'" height="auto" width="60" >';
                          }else{
                            if ( $exten2 == "docx" || $exten2 == "docm" || $exten2 == "dotx" || $exten2 == "dotm" || $exten2 == "doc" || $exten2 == "dot"  ) {     
                              $img = '<img src="../dist/svg/docx.svg" height="auto" width="60" >';
                            }else{
                              if ($exten2 == "") {
                                $img = '<img src="../dist/svg/doc_uploads_no.svg" height="auto" width="60" >';
                              }else{
                                $img = '<img src="../dist/svg/doc_default.svg" height="auto" width="60" >';
                              }                              
                            }
                          }
                        }
                      }
                    }
                  }
                }
              }
            }

            if (strlen($reg->descripcion) >= 55 ) { $descripcion = substr($reg->descripcion, 0, 55).'...'; } else { $descripcion = $reg->descripcion; }
            // echo $descripcion;

            $data[]=array(
              "0"=>$cont++,
              "1"=>($reg->estado)?'<button class="btn btn-warning btn-sm" onclick="mostrar_plano('.$reg->idplano_otro.')"><i class="fas fa-pencil-alt"></i></button>'.
                ' <button class="btn btn-danger  btn-sm" onclick="eliminar_plano('.$reg->idplano_otro.')"><i class="fas fa-skull-crossbones"></i></button>'.
                ' <button class="btn btn-info  btn-sm" onclick="ver_modal_docs('.$docs.')"><i class="far fa-eye"></i></button>':
                ' <button class="btn btn-warning  btn-sm" onclick="mostrar_plano('.$reg->idplano_otro.')"><i class="fa fa-pencil-alt"></i></button>'.
                ' <button class="btn btn-primary  btn-sm" onclick="activar_plano('.$reg->idplano_otro.')"><i class="fa fa-check"></i></button>'.
                ' <button class="btn btn-info  btn-sm" onclick="ver_modal_docs('.$docs.')"><i class="far fa-eye"></i></button>',
              "2"=>$reg->nombre,
              "3"=>'<textarea cols="30" rows="1" class="text_area_clss" readonly="">'.$reg->descripcion.'</textarea>',   
              "4" => '<div ">
                <center>
                  <a type="btn btn-danger" class=""  href="#"  onclick="ver_modal_docs('.$docs.')"data-toggle="tooltip" data-original-title="Ver documentos" >
                    '.$img.'
                  </a>
                </center>
              </div>',                      
              "5"=>($reg->estado)?'<span class="text-center badge badge-success">Activado</span>':'<span class="text-center badge badge-danger">Desactivado</span>'
            );
          }
          $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
            "data"=>$data);
          echo json_encode($results);

        break;
        
      }

      //Fin de las validaciones de acceso
    } else {

      require 'noacceso.php';
    }
  }

  ob_end_flush();

?>
