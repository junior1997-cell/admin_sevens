<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {

    header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.

  } else {

    if ($_SESSION['recurso'] == 1) {
      
      require_once "../modelos/Materiales.php";

      $materiales = new Materiales();

      $idproducto = isset($_POST["idproducto"]) ? limpiarCadena($_POST["idproducto"]) : "";
      $idcategoria = isset($_POST["idcategoria_insumos_af"]) ? limpiarCadena($_POST["idcategoria_insumos_af"]) : "";

      $nombre = isset($_POST["nombre_material"]) ? encodeCadenaHtml($_POST["nombre_material"] ) : "";
      $modelo = isset($_POST["modelo"]) ? encodeCadenaHtml($_POST["modelo"] ) : "";
      $serie = isset($_POST["serie"]) ? encodeCadenaHtml($_POST["serie"] ) : "";
      $marca = isset($_POST["marca"]) ? encodeCadenaHtml($_POST["marca"]) : "";
      $precio_unitario = isset($_POST["precio_unitario"]) ? limpiarCadena($_POST["precio_unitario"]) : "";
      $descripcion = isset($_POST["descripcion_material"]) ? encodeCadenaHtml($_POST["descripcion_material"]) : "";      

      $estado_igv = isset($_POST["estado_igv"]) ? limpiarCadena($_POST["estado_igv"]) : "";
      $monto_igv = isset($_POST["monto_igv"]) ? limpiarCadena($_POST["monto_igv"]) : "";
      $precio_real = isset($_POST["precio_real"]) ? limpiarCadena($_POST["precio_real"]) : "";
      
      $unid_medida = isset($_POST["unid_medida"]) ? limpiarCadena($_POST["unid_medida"]) : "";
      $color = isset($_POST["color"]) ? limpiarCadena($_POST["color"]) : "";
      $total_precio = isset($_POST["total_precio"]) ? limpiarCadena($_POST["total_precio"]) : "";

      $imagen1 = isset($_POST["imagen1"]) ? limpiarCadena($_POST["imagen1"]) : "";
      $imagen_ficha = isset($_POST["doc2"]) ? limpiarCadena($_POST["doc2"]) : ""; 

      switch ($_GET["op"]) {

        case 'guardaryeditar':
          // imgen
          if (!file_exists($_FILES['imagen1']['tmp_name']) || !is_uploaded_file($_FILES['imagen1']['tmp_name'])) {

            $imagen1 = $_POST["imagen1_actual"];

            $flat_img1 = false;

          } else {

            $ext1 = explode(".", $_FILES["imagen1"]["name"]);

            $flat_img1 = true;

            $imagen1 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

            move_uploaded_file($_FILES["imagen1"]["tmp_name"], "../dist/docs/material/img_perfil/" . $imagen1);
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
            
            $rspta = $materiales->insertar($idcategoria, $nombre, $modelo, $serie, $marca, $precio_unitario, $descripcion, $imagen1, $ficha_tecnica, $estado_igv, $monto_igv, $precio_real, $unid_medida, $color, $total_precio);
            
            echo $rspta ? "ok" : "No se pudieron registrar todos los datos del proveedor";

          } else {

            // validamos si existe LA IMG para eliminarlo
            if ($flat_img1 == true) {

              $datos_f1 = $materiales->obtenerImg($idproducto);

              $img1_ant = $datos_f1->fetch_object()->imagen;

              if ($img1_ant != "") {

                unlink("../dist/docs/material/img_perfil/" . $img1_ant);
              }
            }
             
            $rspta = $materiales->editar($idproducto, $idcategoria, $nombre, $modelo, $serie, $marca, $precio_unitario, $descripcion, $imagen1, $ficha_tecnica, $estado_igv, $monto_igv, $precio_real, $unid_medida, $color, $total_precio);
            
            echo $rspta ? "ok" : "Trabador no se pudo actualizar";
          }
        break;
    
        case 'desactivar':

          $rspta = $materiales->desactivar($idproducto);

          echo $rspta ? "material Desactivado" : "material no se puede desactivar";

        break;
    
        case 'activar':

          $rspta = $materiales->activar($idproducto);

          echo $rspta ? "Material activado" : "material no se puede activar";

        break;

        case 'eliminar':

          $rspta = $materiales->eliminar($idproducto);

          echo $rspta ? "ok" : "material no se puede eliminar";

        break;
    
        case 'mostrar':

          $rspta = $materiales->mostrar($idproducto);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);

        break;
    
        case 'listar':
          $rspta = $materiales->listar();
          //Vamos a declarar un array
          $data = [];
          $imagen = '';
          $ficha_tecnica = '';
          $monto_igv = '';
          $imagen_error = "this.src='../dist/svg/default_producto.svg'";
          $cont=1;

          while ($reg = $rspta->fetch_object()) {

            if (empty($reg->imagen)) { $imagen = 'img_material_defect.jpg';  } else { $imagen = $reg->imagen;   }

            empty($reg->ficha_tecnica) ? ($ficha_tecnica = '<center><i class="far fa-file-pdf fa-2x text-gray-50"></i></center>') : ($ficha_tecnica = '<center><a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg->ficha_tecnica . '"><i class="far fa-file-pdf fa-2x text-danger" ></i></a></center>');
            
            empty($reg->precio_igv) ? ($monto_igv = '-') : ($monto_igv = $reg->precio_igv);
            
            $data[] = [
              "0"=>$cont++,
              "1" => $reg->estado ? '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->idproducto . ')"><i class="fas fa-pencil-alt"></i></button>' .
              ' <button class="btn btn-danger btn-sm" onclick="eliminar(' . $reg->idproducto . ')"><i class="fas fa-skull-crossbones"></i></button>' : 
              '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->idproducto . ')"><i class="fa fa-pencil-alt"></i></button>',
              "2" =>
                '<div class="user-block">
                  <img class="profile-user-img img-responsive img-circle" src="../dist/docs/material/img_perfil/' . $imagen . '" alt="user image" onerror="'.$imagen_error.'">
                  <span class="username"><p style="margin-bottom: 0px !important;">' . $reg->nombre . '</p></span>
                  <span class="description">' . substr($reg->descripcion, 0, 30) . '...</span>
                </div>',
              "3" => $reg->nombre_medida,
              "4" => $reg->marca,
              "5" =>'S/. '. number_format($reg->precio_unitario, 2, '.', ','),
              "6" =>'S/. '.number_format($reg->precio_sin_igv, 2, '.', ','),
              "7" =>'S/. '. number_format($monto_igv, 2, '.', ','),
              "8" =>'S/. '.number_format($reg->precio_total, 2, '.', ','),
              "9" => $ficha_tecnica,
              "10" => $reg->estado ? '<span class="text-center badge badge-success">Activado</span>' : '<span class="text-center badge badge-danger">Desactivado</span>',
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
