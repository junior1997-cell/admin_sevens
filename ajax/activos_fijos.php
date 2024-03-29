<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {

    if ($_SESSION['calendario'] == 1) {
      
      require_once "../modelos/Activos_fijos.php";

      $activos_fijos = new Activos_fijos();       

      date_default_timezone_set('America/Lima');  $date_now = date("d_m_Y__h_i_s_A");
      $imagen_error = "this.src='../dist/svg/404-v2.svg'";
      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

      $scheme_host =  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/admin_sevens/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');

      // input no usados        
      $color          = isset($_POST["color"]) ? limpiarCadena($_POST["color"]) : "" ;    
      $modelo         = isset($_POST["modelo"]) ? encodeCadenaHtml($_POST["modelo"]) : "" ;
      $serie          = isset($_POST["serie"]) ? limpiarCadena($_POST["serie"]) : "" ;      
      $estado_igv     = isset($_POST["estado_igv"]) ? limpiarCadena($_POST["estado_igv"]) : "" ;
      $precio_unitario= isset($_POST["precio_unitario"]) ? limpiarCadena($_POST["precio_unitario"]) : "" ;      
      $precio_sin_igv = isset($_POST["precio_sin_igv"]) ? limpiarCadena($_POST["precio_sin_igv"]) : "" ;
      $precio_igv     = isset($_POST["precio_igv"]) ? limpiarCadena($_POST["precio_igv"]) : "" ;
      $precio_total   = isset($_POST["precio_total"]) ? limpiarCadena($_POST["precio_total"]) : "" ;

      // input usados
      $idproducto     = isset($_POST["idproducto"]) ? limpiarCadena($_POST["idproducto"]) : "" ;
      $nombre         = isset($_POST["nombre"]) ? encodeCadenaHtml($_POST["nombre"]) : "" ;
      $idcategoria    = isset($_POST["categoria_insumos_af"]) ? limpiarCadena($_POST["categoria_insumos_af"]) : "" ;
      $unidad_medida  = isset($_POST["unidad_medida"]) ? limpiarCadena($_POST["unidad_medida"]) : "" ;      
      $descripcion    = isset($_POST["descripcion"]) ? encodeCadenaHtml($_POST["descripcion"]) : "" ;  

      $img_pefil = isset($_POST["foto1"]) ? limpiarCadena($_POST["foto1"]) : "" ;
      $ficha_tecnica = isset($_POST["doc2"]) ? limpiarCadena($_POST["doc2"]) : "" ;

      switch ($_GET["op"]) {

        case 'guardaryeditar':

          // imgen
          if (!file_exists($_FILES['foto1']['tmp_name']) || !is_uploaded_file($_FILES['foto1']['tmp_name'])) {

            $imagen1 = $_POST["foto1_actual"];

            $flat_img1 = false;

          } else {

            $ext1 = explode(".", $_FILES["foto1"]["name"]);

            $flat_img1 = true;

            $imagen1 = $date_now .'__'. random_int(0, 20) . round(microtime(true)) . random_int(21, 41) . '.' . end($ext1);

            move_uploaded_file($_FILES["foto1"]["tmp_name"], "../dist/docs/material/img_perfil/" . $imagen1);
          }

          // ficha técnica
          if (!file_exists($_FILES['doc2']['tmp_name']) || !is_uploaded_file($_FILES['doc2']['tmp_name'])) {

            $ficha_tecnica = $_POST["doc_old_2"];

            $flat_ficha1 = false;

          } else {

            $ext1 = explode(".", $_FILES["doc2"]["name"]);

            $flat_ficha1 = true;

            $ficha_tecnica = $date_now .'__'. random_int(0, 20) . round(microtime(true)) . random_int(21, 41) . '.' . end($ext1);

            move_uploaded_file($_FILES["doc2"]["tmp_name"], "../dist/docs/material/ficha_tecnica/" . $ficha_tecnica);
          }

          if (empty($idproducto)) {
            //var_dump($idproyecto,$idproveedor);
            $rspta = $activos_fijos->insertar( $nombre, $idcategoria, $unidad_medida, $_POST["marcas"], $descripcion, $color, $modelo, $serie, $estado_igv, $precio_unitario, $precio_sin_igv, $precio_igv, $precio_total, $ficha_tecnica, $imagen1);
            
            echo json_encode( $rspta, true);

          } else {

            // validamos si existe LA IMG para eliminarlo
            if ($flat_img1 == true) {
              $datos_f1 = $activos_fijos->obtenerImg($idproducto);
              $img1_ant = $datos_f1['data']->fetch_object()->imagen;
              if ( !empty($img1_ant) ) { unlink("../dist/docs/material/img_perfil/" . $img1_ant); }
            }
            
            $rspta = $activos_fijos->editar($idproducto, $nombre, $idcategoria, $unidad_medida, $_POST["marcas"], $descripcion, $color, $modelo, $serie, $estado_igv, $precio_unitario, $precio_sin_igv, $precio_igv, $precio_total, $ficha_tecnica, $imagen1);
            //var_dump($idactivos_fijos,$idproveedor);
            echo json_encode( $rspta, true);
          }

        break;

        case 'desactivar':

          $rspta = $activos_fijos->desactivar($_GET["id_tabla"] );

          echo json_encode( $rspta, true);

        break;

        case 'eliminar':

          $rspta = $activos_fijos->eliminar($_GET["id_tabla"] );

          echo json_encode( $rspta, true);

        break;

        case 'mostrar':

          $rspta = $activos_fijos->mostrar($idproducto);
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true);

        break;

        case 'tabla_principal':
          $rspta = $activos_fijos->tabla_principal($_GET["id_categoria"]);
          //Vamos a declarar un array
          $data = [];         
          $cont=1;          

          if ($rspta['status'] == true) {
            foreach ($rspta['data'] as $key => $reg) {
              
              $imagen = (empty($reg['imagen']) ? 'producto-sin-foto.svg' : $reg['imagen'] );
  
              $ficha_tecnica = empty($reg['ficha_tecnica'])
                ? ( '<div><center><a type="btn btn-danger" class=""><i class="far fa-file-pdf fa-2x text-gray-50"></i></a></center></div>')
                : ( '<center><a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg['ficha_tecnica'] . '"><i class="far fa-file-pdf fa-2x" style="color:#ff0000c4"></i></a></center>');

              $data[] = [
                "0"=>$cont++,
                "1" => $reg['estado'] ? '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg['idproducto'] . ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-danger btn-sm" onclick="eliminar(' . $reg['idproducto'] .', \''.encodeCadenaHtml($reg['nombre']).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i></button>'. 
                  ' <button class="btn btn-info btn-sm" onclick="verdatos('.$reg['idproducto'].')" data-toggle="tooltip" data-original-title="Ver datos"><i class="far fa-eye"></i></button>':
                  '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg['idproducto'] . ')"><i class="fa fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-primary btn-sm" onclick="activar(' . $reg['idproducto'] . ')"><i class="fa fa-check"></i></button>',
                "2" => $reg['idproducto'],
                "3" =>'<div class="user-block">'.
                  '<img class="profile-user-img img-responsive img-circle cursor-pointer" src="../dist/docs/material/img_perfil/' . $imagen . '" alt="user image" onerror="'.$imagen_error.'" onclick="ver_perfil(\'../dist/docs/material/img_perfil/' . $imagen . '\', \''.encodeCadenaHtml($reg['nombre']).'\');" data-toggle="tooltip" data-original-title="Ver imagen">
                  <span class="username"><p class="mb-0">' . $reg['nombre'] . '</p></span>
                  <span class="description"><b>--</b> </span>
                </div>',
                "4" => $reg['categoria'], 
                "5" => $reg['nombre_medida'], 
                "6" => '<div class="bg-color-242244245 " style="overflow: auto; resize: vertical; height: 45px;" >'. $reg['marca'] .'</div>',
                "7" =>  $reg['promedio_precio'],
                "8" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly="">' . $reg['descripcion'] . '</textarea>',
                "9" => $ficha_tecnica . $toltip ,
                
                "10" => $reg['nombre'],
                "11" => $reg['marca_export'],
              ];
            }
  
            $results = [
              "sEcho" => 1, //Información para el datatables
              "iTotalRecords" => count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
              "data" => $data,
            ];
  
            echo json_encode($results);
          } else {
            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          }

        break;     
        
        case 'lista_de_categorias':

          $rspta = $activos_fijos->lista_de_categorias();
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true);

        break;

        case 'salir':
          //Limpiamos las variables de sesión
          session_unset();
          //Destruìmos la sesión
          session_destroy();
          //Redireccionamos al login
          header("Location: ../index.php");

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
