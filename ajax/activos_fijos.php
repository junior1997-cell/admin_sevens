<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {

    header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.

  } else {

    if ($_SESSION['calendario'] == 1) {
      
      require_once "../modelos/Activos_fijos.php";

      $activos_fijos = new Activos_fijos();       

      $idproducto     = isset($_POST["idproducto"]) ? limpiarCadena($_POST["idproducto"]) : "" ;
      $unidad_medida  = isset($_POST["unid_medida"]) ? limpiarCadena($_POST["unid_medida"]) : "" ;
      $color          = isset($_POST["color"]) ? limpiarCadena($_POST["color"]) : "" ;
      $idcategoria    = isset($_POST["categoria_insumos_af"]) ? limpiarCadena($_POST["categoria_insumos_af"]) : "" ;
      $nombre         = isset($_POST["nombre"]) ? encodeCadenaHtml($_POST["nombre"]) : "" ;
      $modelo         = isset($_POST["modelo"]) ? encodeCadenaHtml($_POST["modelo"]) : "" ;
      $serie          = isset($_POST["serie"]) ? limpiarCadena($_POST["serie"]) : "" ;
      $marca          = isset($_POST["marca"]) ? encodeCadenaHtml($_POST["marca"]) : "" ;
      $estado_igv     = isset($_POST["estado_igv"]) ? limpiarCadena($_POST["estado_igv"]) : "" ;
      $precio_unitario= isset($_POST["precio_unitario"]) ? limpiarCadena($_POST["precio_unitario"]) : "" ;      
      $precio_sin_igv = isset($_POST["precio_sin_igv"]) ? limpiarCadena($_POST["precio_sin_igv"]) : "" ;
      $precio_igv     = isset($_POST["precio_igv"]) ? limpiarCadena($_POST["precio_igv"]) : "" ;
      $precio_total   = isset($_POST["precio_total"]) ? limpiarCadena($_POST["precio_total"]) : "" ;      
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

            $imagen1 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

            move_uploaded_file($_FILES["foto1"]["tmp_name"], "../dist/docs/material/img_perfil/" . $imagen1);
          }

          // ficha técnica
          if (!file_exists($_FILES['doc2']['tmp_name']) || !is_uploaded_file($_FILES['doc2']['tmp_name'])) {

            $ficha_tecnica = $_POST["doc_old_2"];

            $flat_ficha1 = false;

          } else {

            $ext1 = explode(".", $_FILES["doc2"]["name"]);

            $flat_ficha1 = true;

            $ficha_tecnica = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

            move_uploaded_file($_FILES["doc2"]["tmp_name"], "../dist/docs/material/ficha_tecnica/" . $ficha_tecnica);
          }

          if (empty($idproducto)) {
            //var_dump($idproyecto,$idproveedor);
            $rspta = $activos_fijos->insertar( $unidad_medida, $color, $idcategoria, $nombre, $modelo, $serie, $marca, $estado_igv, $precio_unitario, $precio_igv, $precio_sin_igv, $precio_total, $ficha_tecnica, $descripcion,  $imagen1);
            
            echo $rspta ? "ok" : "No se pudieron registrar todos los datos";

          } else {

            // validamos si existe LA IMG para eliminarlo
            if ($flat_img1 == true) {

              $datos_f1 = $activos_fijos->obtenerImg($idproducto);

              $img1_ant = $datos_f1->fetch_object()->imagen;

              if ($img1_ant != "") {

                unlink("../dist/docs/material/img_perfil/" . $img1_ant);
              }
            }
            
            $rspta = $activos_fijos->editar( $idproducto, $unidad_medida, $color, $idcategoria, $nombre, $modelo, $serie, $marca, $estado_igv, $precio_unitario, $precio_igv, $precio_sin_igv, $precio_total, $ficha_tecnica, $descripcion,  $imagen1);
            //var_dump($idactivos_fijos,$idproveedor);
            echo $rspta ? "ok" : "No se pudo actualizar";
          }

        break;

        case 'desactivar':

          $rspta = $activos_fijos->desactivar($idproducto);

          echo $rspta ? "ok" : "Activo no se puede desactivar";

        break;

        case 'activar':

          $rspta = $activos_fijos->activar($idproducto);

          echo $rspta ? "ok" : "Activo no se puede activar";

        break;
        case 'eliminar':

          $rspta = $activos_fijos->eliminar($idproducto);

          echo $rspta ? "ok" : "Activo no se puede eliminar";

        break;

        case 'mostrar':

          $rspta = $activos_fijos->mostrar($idproducto);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);

        break;

        case 'listar':
          $rspta = $activos_fijos->listar();
          //Vamos a declarar un array
          $data = [];
          $imagen = '';
          $ficha_tecnica = '';
          $igv = '';

          $imagen_error = "this.src='../dist/svg/default_producto.svg'";
          $cont=1;
          while ($reg = $rspta->fetch_object()) {

            if (empty($reg->imagen)) {
              $imagen = 'src="../dist/svg/default_producto.svg"';
            } else {
              $imagen = 'src="../dist/docs/material/img_perfil/' . $reg->imagen . '"';
            }

            empty($reg->ficha_tecnica)
              ? ($ficha_tecnica = '<div><center><a type="btn btn-danger" class=""><i class="far fa-file-pdf fa-2x text-gray-50"></i></a></center></div>')
              : ($ficha_tecnica = '<center><a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg->ficha_tecnica . '"><i class="far fa-file-pdf fa-2x" style="color:#ff0000c4"></i></a></center>');
            
            empty($reg->precio_igv) ? ($igv = '-') : ($igv = $reg->precio_igv);

            $data[] = [
              "0"=>$cont++,
              "1" => $reg->estado ? '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->idproducto . ')"><i class="fas fa-pencil-alt"></i></button>' .
                ' <button class="btn btn-danger btn-sm" onclick="eliminar(' . $reg->idproducto . ')"><i class="fas fa-skull-crossbones"></i></button>':
                '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->idproducto . ')"><i class="fa fa-pencil-alt"></i></button>' .
                ' <button class="btn btn-primary btn-sm" onclick="activar(' . $reg->idproducto . ')"><i class="fa fa-check"></i></button>',
              "2" =>'<div class="user-block"> <img class="profile-user-img img-responsive img-circle" ' . $imagen . ' alt="user image" onerror="'.$imagen_error.'">
                <span class="username"><p style="margin-bottom: 0px !important;">' . $reg->nombre . '</p></span>
                <span class="description"><b>Marca: </b>' . $reg->marca . '</span>
                <span class="description"><b>Color: </b>' . $reg->nombre_color . '</span>
              </div>',
              "3" => $reg->categoria, 
              "4" =>'S/. '. number_format($reg->precio_unitario, 2, '.', ','),
              "5" =>'S/. '. number_format($reg->precio_sin_igv, 2, '.', ','),
              "6" =>'S/. '. number_format($igv, 2, '.', ','),
              "7" =>'S/. '. number_format($reg->precio_total, 2, '.', ','),
              "8" => $ficha_tecnica,
              "9" => $reg->estado ? '<span class="text-center badge badge-success">Activado</span>' : '<span class="text-center badge badge-danger">Desactivado</span>',
            ];
          }

          $results = [
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
            "data" => $data,
          ];

          echo json_encode($results);

        break;

        case 'select2Color': 

          $rspta = $activos_fijos->select2_color();
      
          while ($reg = $rspta->fetch_object())  {

            echo '<option value=' . $reg->id . '>' . $reg->nombre .'</option>';
          }

        break;

        case 'select2UnidaMedida': 

          $rspta = $activos_fijos->select2_unidad_medida();
      
          while ($reg = $rspta->fetch_object())  {

            echo '<option value=' . $reg->id . '>' . $reg->nombre . ' - ' . $reg->abreviacion .'</option>';
          }

        break;

        case 'select2Categoria': 

          $rspta = $activos_fijos->select2_categoria();
      
          while ($reg = $rspta->fetch_object())  {

            echo '<option value=' . $reg->id . '>' . $reg->nombre .'</option>';
          }

        break;

        case 'salir':
          //Limpiamos las variables de sesión
          session_unset();
          //Destruìmos la sesión
          session_destroy();
          //Redireccionamos al login
          header("Location: ../index.php");

        break;
      }

    } else {
      require 'noacceso.php';
    }    
  }  
  
  ob_end_flush();
?>
