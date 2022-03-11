<?php
ob_start();

if (strlen(session_id()) < 1) {
  session_start(); //Validamos si existe o no la sesión
}

if (!isset($_SESSION["nombre"])) {
  header("Location: login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
} else {
  //Validamos el acceso solo al usuario logueado y autorizado.
  if ($_SESSION['compra'] == 1) {

    require_once "../modelos/Resumen_activos_fijos.php";
    require_once "../modelos/Compra.php";
    require_once "../modelos/AllProveedor.php";
    require_once "../modelos/Activos_fijos.php";
    
    $resumen_activo_fijo = new ResumenActivoFijo();
    $compra = new Compra();
    $proveedor = new Proveedor();
    $activos_fijos = new Activos_fijos();

    // :::::::::::::::::::::::::::::::::::: D A T O S   C O M P R A ::::::::::::::::::::::::::::::::::::::
    $idproyecto         = isset($_POST["idproyecto"]) ? limpiarCadena($_POST["idproyecto"]) : "";
    $idcompra_proyecto  = isset($_POST["idcompra_proyecto"]) ? limpiarCadena($_POST["idcompra_proyecto"]) : "";
    $idproveedor        = isset($_POST["idproveedor"]) ? limpiarCadena($_POST["idproveedor"]) : "";
    $fecha_compra       = isset($_POST["fecha_compra"]) ? limpiarCadena($_POST["fecha_compra"]) : "";
    $glosa              = isset($_POST["glosa"]) ? limpiarCadena($_POST["glosa"]) : "";
    $tipo_comprobante   = isset($_POST["tipo_comprobante"]) ? limpiarCadena($_POST["tipo_comprobante"]) : "";    
    $serie_comprobante  = isset($_POST["serie_comprobante"]) ? limpiarCadena($_POST["serie_comprobante"]) : "";
    $val_igv            = isset($_POST["val_igv"]) ? limpiarCadena($_POST["val_igv"]) : "";
    $descripcion        = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
    $subtotal_compra    = isset($_POST["subtotal_compra"]) ? limpiarCadena($_POST["subtotal_compra"]) : "";
    $tipo_gravada       = isset($_POST["tipo_gravada"]) ? limpiarCadena($_POST["tipo_gravada"]) : "";    
    $igv_compra         = isset($_POST["igv_compra"]) ? limpiarCadena($_POST["igv_compra"]) : "";
    $total_venta        = isset($_POST["total_venta"]) ? limpiarCadena($_POST["total_venta"]) : "";
    $estado_detraccion  = isset($_POST["estado_detraccion"]) ? limpiarCadena($_POST["estado_detraccion"]) : "";

    // :::::::::::::::::::::::::::::::::::: D A T O S   M A T E R I A L E S ::::::::::::::::::::::::::::::::::::::
    $idproducto_p     = isset($_POST["idproducto_p"]) ? limpiarCadena($_POST["idproducto_p"]) : "" ;
    $unidad_medida_p  = isset($_POST["unidad_medida_p"]) ? limpiarCadena($_POST["unidad_medida_p"]) : "" ;
    $color_p          = isset($_POST["color_p"]) ? limpiarCadena($_POST["color_p"]) : "" ;
    $categoria_insumos_af_p    = isset($_POST["categoria_insumos_af_p"]) ? limpiarCadena($_POST["categoria_insumos_af_p"]) : "" ;
    $nombre_p         = isset($_POST["nombre_p"]) ? encodeCadenaHtml($_POST["nombre_p"]) : "" ;
    $modelo_p         = isset($_POST["modelo_p"]) ? encodeCadenaHtml($_POST["modelo_p"]) : "" ;
    $serie_p          = isset($_POST["serie_p"]) ? encodeCadenaHtml($_POST["serie_p"]) : "" ;
    $marca_p          = isset($_POST["marca_p"]) ? encodeCadenaHtml($_POST["marca_p"]) : "" ;
    $estado_igv_p     = isset($_POST["estado_igv_p"]) ? limpiarCadena($_POST["estado_igv_p"]) : "" ;
    $precio_unitario_p= isset($_POST["precio_unitario_p"]) ? limpiarCadena($_POST["precio_unitario_p"]) : "" ;      
    $precio_sin_igv_p = isset($_POST["precio_sin_igv_p"]) ? limpiarCadena($_POST["precio_sin_igv_p"]) : "" ;
    $precio_igv_p     = isset($_POST["precio_igv_p"]) ? limpiarCadena($_POST["precio_igv_p"]) : "" ;
    $precio_total_p   = isset($_POST["precio_total_p"]) ? limpiarCadena($_POST["precio_total_p"]) : "" ;      
    $descripcion_p    = isset($_POST["descripcion_p"]) ? encodeCadenaHtml($_POST["descripcion_p"]) : "" ; 
    $img_pefil_p      = isset($_POST["foto2"]) ? limpiarCadena($_POST["foto2"]) : "" ;
    $ficha_tecnica_p  = isset($_POST["doc2"]) ? limpiarCadena($_POST["doc2"]) : "" ;

    // :::::::::::::::::::::::::::::::::::: D A T O S   P R O V E E D O R ::::::::::::::::::::::::::::::::::::::
    $idproveedor_prov		= isset($_POST["idproveedor_prov"])? limpiarCadena($_POST["idproveedor_prov"]):"";
    $nombre_prov 		    = isset($_POST["nombre_prov"])? limpiarCadena($_POST["nombre_prov"]):"";
    $tipo_documento_prov		= isset($_POST["tipo_documento_prov"])? limpiarCadena($_POST["tipo_documento_prov"]):"";
    $num_documento_prov	    = isset($_POST["num_documento_prov"])? limpiarCadena($_POST["num_documento_prov"]):"";
    $direccion_prov		    = isset($_POST["direccion_prov"])? limpiarCadena($_POST["direccion_prov"]):"";
    $telefono_prov		    = isset($_POST["telefono_prov"])? limpiarCadena($_POST["telefono_prov"]):"";
    $c_bancaria_prov		    = isset($_POST["c_bancaria_prov"])? limpiarCadena($_POST["c_bancaria_prov"]):"";
    $cci_prov		    	= isset($_POST["cci_prov"])? limpiarCadena($_POST["cci_prov"]):"";
    $c_detracciones_prov		= isset($_POST["c_detracciones_prov"])? limpiarCadena($_POST["c_detracciones_prov"]):"";
    $banco_prov			    = isset($_POST["banco_prov"])? limpiarCadena($_POST["banco_prov"]):"";
    $titular_cuenta_prov		= isset($_POST["titular_cuenta_prov"])? limpiarCadena($_POST["titular_cuenta_prov"]):"";

    switch ($_GET["op"]) {      
      
      // -------------------------------------
      case 'tbla_principal_maquinaria':

        $idproyecto = $_GET["id_proyecto"];

        $rspta = $resumen_activo_fijo->tbla_principal_maquinaria($idproyecto);
        //Vamos a declarar un array
        $data = []; $count = 1;

        $imagen_error = "this.src='../dist/svg/default_producto.svg'";

        while ($reg = $rspta->fetch_object()) {

          $precio_promedio = number_format($reg->precio_con_igv / $reg->count_productos, 2, ".", ",");

          $data[] = [             
            "0"  => $count++,       
            "1" => '<button class="btn btn-warning btn-sm" onclick="mostrar_material(' . $reg->idproducto . ')"><i class="fas fa-pencil-alt"></i></button>
              <button class="btn btn-info btn-sm" onclick="mostrar_detalle_material(' . $reg->idproducto . ')"><i class="far fa-eye"></i></button>',       
            "2" => '<div class="user-block"> <img class="profile-user-img img-responsive img-circle" src="../dist/docs/material/img_perfil/' . $reg->imagen . '" alt="User Image" onerror="' .  $imagen_error .  '"><span class="username"><p class="text-primary" style="margin-bottom: 0.2rem !important"; >' . $reg->nombre_producto . '</p></span><span class="description"> '.(empty($reg->modelo) ? '' : '<b class="d-none">═</b> <b >Modelo:</b> ' . $reg->modelo ).'</span></div>',
            "3" => $reg->nombre_color,
            "4" => $reg->marca,
            "5" => $reg->nombre_medida,
            "6" => $reg->cantidad_total,
            "7" => '<button class="btn btn-info btn-sm mb-2" onclick="tbla_facuras(' . $reg->idproyecto . ', ' . $reg->idproducto . ', \'' .  htmlspecialchars($reg->nombre_producto, ENT_QUOTES) . '\', \'' .  $precio_promedio . '\', \'' .  number_format($reg->precio_total, 2, ".", ",") . '\')"><i class="far fa-eye"></i></button>',
            "8" => 'S/. ' . number_format($reg->promedio_precio, 2, ".", ","),
            "9" => 'S/. ' . number_format($reg->precio_actual, 2, ".", ","),
            "10" => 'S/. ' . number_format($reg->precio_total, 2, ".", ","),             
          ];
        }

        $results = [
          "sEcho" => 1, //Información para el datatables
          "iTotalRecords" => count($data), //enviamos el total registros al datatable
          "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
          "aaData" => $data,
        ];
        echo json_encode($results);

      break;

      case 'suma_total_maquinaria':
        $idproyecto = $_POST["idproyecto"];

        $rspta = $resumen_activo_fijo->suma_total_maquinaria($idproyecto);

        echo json_encode($rspta);
      break;
      
      // -------------------------------------
      case 'tbla_principal_equipo':

        $idproyecto = $_GET["id_proyecto"];

        $rspta = $resumen_activo_fijo->tbla_principal_equipo($idproyecto);
        //Vamos a declarar un array
        $data = []; $count = 1;

        $imagen_error = "this.src='../dist/svg/default_producto.svg'";

        while ($reg = $rspta->fetch_object()) {

          $precio_promedio = number_format($reg->precio_con_igv / $reg->count_productos, 2, ".", ",");

          $data[] = [             
            "0"  => $count++,       
            "1" => '<button class="btn btn-warning btn-sm" onclick="mostrar_material(' . $reg->idproducto . ')"><i class="fas fa-pencil-alt"></i></button>
              <button class="btn btn-info btn-sm" onclick="mostrar_detalle_material(' . $reg->idproducto . ')"><i class="far fa-eye"></i></button>',       
            "2" => '<div class="user-block"> <img class="profile-user-img img-responsive img-circle" src="../dist/docs/material/img_perfil/' . $reg->imagen . '" alt="User Image" onerror="' .  $imagen_error .  '"><span class="username"><p class="text-primary" style="margin-bottom: 0.2rem !important"; >' . $reg->nombre_producto . '</p></span><span class="description"> '.(empty($reg->modelo) ? '' : '<b class="d-none">═</b> <b >Modelo:</b> ' . $reg->modelo ).'</span></div>',
            "3" => $reg->nombre_color,
            "4" => $reg->marca,
            "5" => $reg->nombre_medida,
            "6" => $reg->cantidad_total,
            "7" => '<button class="btn btn-info btn-sm mb-2" onclick="tbla_facuras(' . $reg->idproyecto . ', ' . $reg->idproducto . ', \'' .  htmlspecialchars($reg->nombre_producto, ENT_QUOTES) . '\', \'' .  $precio_promedio . '\', \'' .  number_format($reg->precio_total, 2, ".", ",") . '\')"><i class="far fa-eye"></i></button>',
            "8" => 'S/. ' . number_format($reg->promedio_precio, 2, ".", ","),
            "9" => 'S/. ' . number_format($reg->precio_actual, 2, ".", ","),
            "10" => 'S/. ' . number_format($reg->precio_total, 2, ".", ","),             
          ];
        }

        $results = [
          "sEcho" => 1, //Información para el datatables
          "iTotalRecords" => count($data), //enviamos el total registros al datatable
          "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
          "aaData" => $data,
        ];
        echo json_encode($results);

      break;

      case 'suma_total_equipo':
        $idproyecto = $_POST["idproyecto"];

        $rspta = $resumen_activo_fijo->suma_total_equipo($idproyecto);

        echo json_encode($rspta);
      break;
      
      // -------------------------------------
      case 'tbla_principal_herramienta':

        $idproyecto = $_GET["id_proyecto"];

        $rspta = $resumen_activo_fijo->tbla_principal_herramienta($idproyecto);
        //Vamos a declarar un array
        $data = []; $count = 1;

        $imagen_error = "this.src='../dist/svg/default_producto.svg'";

        while ($reg = $rspta->fetch_object()) {

          $precio_promedio = number_format($reg->precio_con_igv / $reg->count_productos, 2, ".", ",");

          $data[] = [             
            "0"  => $count++,       
            "1" => '<button class="btn btn-warning btn-sm" onclick="mostrar_material(' . $reg->idproducto . ')"><i class="fas fa-pencil-alt"></i></button>
              <button class="btn btn-info btn-sm" onclick="mostrar_detalle_material(' . $reg->idproducto . ')"><i class="far fa-eye"></i></button>',       
            "2" => '<div class="user-block"> <img class="profile-user-img img-responsive img-circle" src="../dist/docs/material/img_perfil/' . $reg->imagen . '" alt="User Image" onerror="' .  $imagen_error .  '"><span class="username"><p class="text-primary" style="margin-bottom: 0.2rem !important"; >' . $reg->nombre_producto . '</p></span><span class="description"> '.(empty($reg->modelo) ? '' : '<b class="d-none">═</b> <b >Modelo:</b> ' . $reg->modelo ).'</span></div>',
            "3" => $reg->nombre_color,
            "4" => $reg->marca,
            "5" => $reg->nombre_medida,
            "6" => $reg->cantidad_total,
            "7" => '<button class="btn btn-info btn-sm mb-2" onclick="tbla_facuras(' . $reg->idproyecto . ', ' . $reg->idproducto . ', \'' .  htmlspecialchars($reg->nombre_producto, ENT_QUOTES) . '\', \'' .  $precio_promedio . '\', \'' .  number_format($reg->precio_total, 2, ".", ",") . '\')"><i class="far fa-eye"></i></button>',
            "8" => 'S/. ' . number_format($reg->promedio_precio, 2, ".", ","),
            "9" => 'S/. ' . number_format($reg->precio_actual, 2, ".", ","),
            "10" => 'S/. ' . number_format($reg->precio_total, 2, ".", ","),             
          ];
        }

        $results = [
          "sEcho" => 1, //Información para el datatables
          "iTotalRecords" => count($data), //enviamos el total registros al datatable
          "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
          "aaData" => $data,
        ];
        echo json_encode($results);

      break;

      case 'suma_total_herramienta':
        $idproyecto = $_POST["idproyecto"];

        $rspta = $resumen_activo_fijo->suma_total_herramienta($idproyecto);

        echo json_encode($rspta);
      break;
      
      // -------------------------------------
      case 'tbla_principal_oficina':

        $idproyecto = $_GET["id_proyecto"];

        $rspta = $resumen_activo_fijo->tbla_principal_oficina($idproyecto);
        //Vamos a declarar un array
        $data = []; $count = 1;

        $imagen_error = "this.src='../dist/svg/default_producto.svg'";

        while ($reg = $rspta->fetch_object()) {

          $precio_promedio = number_format($reg->precio_con_igv / $reg->count_productos, 2, ".", ",");

          $data[] = [             
            "0"  => $count++,       
            "1" => '<button class="btn btn-warning btn-sm" onclick="mostrar_material(' . $reg->idproducto . ')"><i class="fas fa-pencil-alt"></i></button>
              <button class="btn btn-info btn-sm" onclick="mostrar_detalle_material(' . $reg->idproducto . ')"><i class="far fa-eye"></i></button>',       
            "2" => '<div class="user-block"> <img class="profile-user-img img-responsive img-circle" src="../dist/docs/material/img_perfil/' . $reg->imagen . '" alt="User Image" onerror="' .  $imagen_error .  '"><span class="username"><p class="text-primary" style="margin-bottom: 0.2rem !important"; >' . $reg->nombre_producto . '</p></span><span class="description"> '.(empty($reg->modelo) ? '' : '<b class="d-none">═</b> <b >Modelo:</b> ' . $reg->modelo ).'</span></div>',
            "3" => $reg->nombre_color,
            "4" => $reg->marca,
            "5" => $reg->nombre_medida,
            "6" => $reg->cantidad_total,
            "7" => '<button class="btn btn-info btn-sm mb-2" onclick="tbla_facuras(' . $reg->idproyecto . ', ' . $reg->idproducto . ', \'' .  htmlspecialchars($reg->nombre_producto, ENT_QUOTES) . '\', \'' .  $precio_promedio . '\', \'' .  number_format($reg->precio_total, 2, ".", ",") . '\')"><i class="far fa-eye"></i></button>',
            "8" => 'S/. ' . number_format($reg->promedio_precio, 2, ".", ","),
            "9" => 'S/. ' . number_format($reg->precio_actual, 2, ".", ","),
            "10" => 'S/. ' . number_format($reg->precio_total, 2, ".", ","),             
          ];
        }

        $results = [
          "sEcho" => 1, //Información para el datatables
          "iTotalRecords" => count($data), //enviamos el total registros al datatable
          "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
          "aaData" => $data,
        ];
        echo json_encode($results);

      break;

      case 'suma_total_oficina':
        $idproyecto = $_POST["idproyecto"];

        $rspta = $resumen_activo_fijo->suma_total_oficina($idproyecto);

        echo json_encode($rspta);
      break;

      
      // :::::::::::::::::::::::::: S E C C I O N   P R O V E E D O R :::::::::::::::::::::::::: 
      case 'guardar_proveedor':

        if (empty($idproveedor_prov)){

          $rspta=$proveedor->insertar($nombre_prov, $tipo_documento_prov, $num_documento_prov, $direccion_prov, $telefono_prov,
          $c_bancaria_prov, $cci_prov, $c_detracciones_prov, $banco_prov, $titular_cuenta_prov);
          
          echo $rspta ? "ok" : "No se pudieron registrar todos los datos del proveedor";
        }

      break;

      case 'formato_banco':
           
        $rspta=$proveedor->formato_banco($_POST["idbanco"]);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
         
      break;

      // :::::::::::::::::::::::::: S E C C I O N   M A T E R I A L E S ::::::::::::::::::::::::::
      case 'guardar_materiales':
        // imgen
        if (!file_exists($_FILES['foto2']['tmp_name']) || !is_uploaded_file($_FILES['foto2']['tmp_name'])) {
    
          $img_pefil_p = $_POST["foto2_actual"];
    
          $flat_img1 = false;
    
        } else {
    
          $ext1 = explode(".", $_FILES["foto2"]["name"]);
    
          $flat_img1 = true;
    
          $img_pefil_p = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);
    
          move_uploaded_file($_FILES["foto2"]["tmp_name"], "../dist/docs/material/img_perfil/" . $img_pefil_p);
        }
    
        // ficha técnica
        if (!file_exists($_FILES['doc2']['tmp_name']) || !is_uploaded_file($_FILES['doc2']['tmp_name'])) {
    
          $ficha_tecnica_p = $_POST["doc_old_2"];
    
          $flat_ficha1 = false;
    
        } else {
    
          $ext1 = explode(".", $_FILES["doc2"]["name"]);
    
          $flat_ficha1 = true;
    
          $ficha_tecnica_p = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);
    
          move_uploaded_file($_FILES["doc2"]["tmp_name"], "../dist/docs/material/ficha_tecnica/" . $ficha_tecnica_p);
        }
    
        if (empty($idproducto_p)) {
          //var_dump($idproyecto,$idproveedor);
          $rspta = $activos_fijos->insertar( $unidad_medida_p, $color_p, $categoria_insumos_af_p, $nombre_p, $modelo_p, $serie_p, $marca_p, $estado_igv_p, $precio_unitario_p, $precio_igv_p, $precio_sin_igv_p, $precio_total_p, $ficha_tecnica_p, $descripcion_p,  $img_pefil_p);
          
          echo $rspta ? "ok" : "No se pudieron registrar todos los datos";
    
        } else {
          // validamos si existe LA IMG para eliminarlo
          if ($flat_img1 == true) {

            $datos_f1 = $activos_fijos->obtenerImg($idproducto_p);

            $img1_ant = $datos_f1->fetch_object()->imagen;

            if ($img1_ant != "") {

              unlink("../dist/docs/material/img_perfil/" . $img1_ant);
            }
          }
          
          $rspta = $activos_fijos->editar( $idproducto_p, $unidad_medida_p, $color_p, $categoria_insumos_af_p, $nombre_p, $modelo_p, $serie_p, $marca_p, $estado_igv_p, $precio_unitario_p, $precio_igv_p, $precio_sin_igv_p, $precio_total_p, $ficha_tecnica_p, $descripcion_p,  $img_pefil_p);
          //var_dump($idactivos_fijos,$idproveedor);
          echo $rspta ? "ok" : "No se pudo actualizar";
        }
    
      break;

      case 'mostrar_materiales':

        $rspta = $activos_fijos->mostrar($idproducto_p);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    
      break;

      case 'listarMaterialescompra':     

        $rspta = $resumen_activo_fijo->listar_productos();
        //Vamos a declarar un array
        $datas = [];
        // echo json_encode($rspta);
        $img = "";
        $imagen_error = "this.src='../dist/svg/default_producto.svg'";
        $color_stock = "";
        $ficha_tecnica = "";
        
        $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';
        
        while ($reg = $rspta->fetch_object()) {
    
          if (!empty($reg->imagen)) {   $img = "../dist/docs/material/img_perfil/$reg->imagen"; } else { $img = "../dist/svg/default_producto.svg"; }
    
          !empty($reg->ficha_tecnica) ? ($ficha_tecnica = '<center><a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg->ficha_tecnica . '"><i class="far fa-file-pdf fa-2x text-success"></i></a></center>') : ($ficha_tecnica = '<center><span class="text-center"> <i class="far fa-times-circle fa-2x text-danger"></i></span></center>');
          
          $datas[] = [
            "0" => '<button class="btn btn-warning" onclick="agregarDetalleComprobante(' . $reg->idproducto . ', \'' . htmlspecialchars($reg->nombre, ENT_QUOTES) . '\', \'' . $reg->nombre_medida . '\', \'' . $reg->nombre_color . '\', \'' . $reg->precio_sin_igv . '\', \'' . $reg->precio_igv . '\', \'' . $reg->precio_total . '\', \'' . $reg->imagen . '\', \'' . $reg->ficha_tecnica . '\')" data-toggle="tooltip" data-original-title="Agregar Producto">
              <span class="fa fa-plus"></span>
            </button>',
            "1" => '<div class="user-block w-px-200"> <img class="profile-user-img img-responsive img-circle" src="' . $img .  '" alt="user image" onerror="' . $imagen_error . '"> 
              <span class="username"><p style="margin-bottom: 0px !important;">' .   $reg->nombre . '</p></span> 
              <span class="description"><b>Color: </b>' .$reg->nombre_color . '</span>
              <span class="description"><b>Marca: </b>' .$reg->marca . '</span>
            </div>',
            "2" => $reg->categoria,
            "3" => number_format($reg->precio_unitario, 2, '.', ','),
            "4" => '<textarea cols="30" rows="1" class="text_area_clss" readonly >'.$reg->descripcion.'</textarea>',
            "5" => $ficha_tecnica . $toltip,
          ];
        }
    
        $results = [
          "sEcho" => 1, //Información para el datatables
          "iTotalRecords" => count($datas), //enviamos el total registros al datatable
          "iTotalDisplayRecords" => count($datas), //enviamos el total registros a visualizar
          "aaData" => $datas,
        ];
        echo json_encode($results);
      break;

      // :::::::::::::::::::::::::: S E C C I O N   C O M P R A ::::::::::::::::::::::::::
      case 'guardar_y_editar_compra':
        if (empty($idcompra_proyecto)) {
          $rspta = $compra->insertar( $idproyecto, $idproveedor, $fecha_compra,  $tipo_comprobante, $serie_comprobante, $val_igv, $descripcion, 
          $glosa, $total_venta, $subtotal_compra, $igv_compra, $estado_detraccion, $_POST["idproducto"], $_POST["unidad_medida"], 
          $_POST["nombre_color"], $_POST["cantidad"], $_POST["precio_sin_igv"], $_POST["precio_igv"],  $_POST["precio_con_igv"], $_POST["descuento"], 
          $tipo_gravada, $_POST["ficha_tecnica_producto"]);
          //precio_sin_igv,precio_igv,precio_total
          echo $rspta ? "ok" : "No se pudieron registrar todos los datos de la compra";
        } else {
          $rspta = $compra->editar( $idcompra_proyecto, $idproyecto, $idproveedor, $fecha_compra,  $tipo_comprobante, $serie_comprobante, $val_igv, $descripcion, 
          $glosa, $total_venta, $subtotal_compra, $igv_compra, $estado_detraccion, $_POST["idproducto"], $_POST["unidad_medida"], 
          $_POST["nombre_color"], $_POST["cantidad"], $_POST["precio_sin_igv"], $_POST["precio_igv"],  $_POST["precio_con_igv"], $_POST["descuento"], 
          $tipo_gravada, $_POST["ficha_tecnica_producto"] );
    
          echo $rspta ? "ok" : "Compra no se pudo actualizar";
        }
    
      break;

      case 'ver_compra_editar':
        $rspta = $compra->mostrar_compra_para_editar($idcompra_proyecto);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    
      break;     
      
      case 'tbla_facturas':
        $idproyecto = $_GET["idproyecto"];
        $idproducto = $_GET["idproducto"];

        $rspta = $resumen_activo_fijo->tbla_facturas($idproyecto, $idproducto);
        //Vamos a declarar un array
        $data = []; $cont = 1;

        $imagen_error = "this.src='../dist/svg/user_default.svg'";
        $ficha_tecnica = "";

        while ($reg = $rspta->fetch_object()) {
          // validamos si existe una ficha tecnica
          !empty($reg->ficha_tecnica)
            ? ($ficha_tecnica = '<center><a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg->ficha_tecnica . '"><i class="far fa-file-pdf fa-lg text-success"></i></a></center>')
            : ($ficha_tecnica = '<center><i class="far fa-file-pdf fa-lg text-gray-50"></i></center>');

          $data[] = [    
            "0" => $cont++,
            "1" => '<button class="btn btn-info btn-sm" onclick="ver_detalle_compras(' . $reg->idcompra_proyecto . ')" data-toggle="tooltip" data-original-title="Ver detalle compra"><i class="fa fa-eye"></i></button>' .
            ' <button class="btn btn-warning btn-sm" onclick="editar_detalle_compras(' . $reg->idcompra_proyecto . ')" data-toggle="tooltip" data-original-title="Editar compra"><i class="fas fa-pencil-alt"></i></button>',
            "2" => '<span class="text-primary font-weight-bold" >' . $reg->proveedor . '</span>',      
            "3" => date("d/m/Y", strtotime($reg->fecha_compra)),
            "4" => $reg->cantidad,
            "5" => '<b>' . number_format($reg->precio_con_igv, 2, ".", ",") . '</b>',
            "6" => 'S/. ' . number_format($reg->descuento, 2, ".", ","),
            "7" => 'S/. ' . number_format($reg->subtotal, 2, ".", ","),
            // "7" => $ficha_tecnica,
          ];
        }

        $results = [
          "sEcho" => 1, //Información para el datatables
          "iTotalRecords" => count($data), //enviamos el total registros al datatable
          "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
          "aaData" => $data,
        ];
        echo json_encode($results);

      break;

      case 'sumas_factura_x_material':
        $rspta = $resumen_activo_fijo->sumas_factura_x_material($_POST["idproyecto"], $_POST["idproducto"]);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    
      break; 

      case 'ver_detalle_compras':
        
        $rspta = $compra->ver_compra($_GET['id_compra']);
        $rspta2 = $compra->ver_detalle_compra($_GET['id_compra']);

        $subtotal = 0;    $ficha = ''; 

        echo '<!-- Tipo de Empresa -->
          <div class="col-lg-6">
            <div class="form-group">
              <label class="font-size-15px" for="idproveedor">Proveedor</label>
              <h5 class="form-control form-control-sm" >'.$rspta['razon_social'].'</h5>
            </div>
          </div>
          <!-- fecha -->
          <div class="col-lg-3">
            <div class="form-group">
              <label class="font-size-15px" for="fecha_compra">Fecha </label>
              <span class="form-control form-control-sm"><i class="far fa-calendar-alt"></i>&nbsp;&nbsp;&nbsp;'.format_d_m_a($rspta['fecha_compra']).' </span>
            </div>
          </div>
          <!-- fecha -->
          <div class="col-lg-3">
            <div class="form-group">
              <label class="font-size-15px" for="fecha_compra">Glosa </label>
              <span class="form-control form-control-sm">'.$rspta['glosa'].' </span>
            </div>
          </div>
          <!-- Tipo de comprobante -->
          <div class="col-lg-3">
            <div class="form-group">
              <label class="font-size-15px" for="tipo_comprovante">Tipo Comprobante</label>
              <span  class="form-control form-control-sm"> '. ((empty($rspta['tipo_comprobante'])) ? '- - -' :  $rspta['tipo_comprobante'])  .' </span>
            </div>
          </div>
          <!-- serie_comprovante-->
          <div class="col-lg-2">
            <div class="form-group">
              <label class="font-size-15px" for="serie_comprovante">N° de Comprobante</label>
              <span  class="form-control form-control-sm"> '. ((empty($rspta['serie_comprobante'])) ? '- - -' :  $rspta['serie_comprobante']).' </span>
            </div>
          </div>
          <!-- IGV-->
          <div class="col-lg-1 " >
            <div class="form-group">
              <label class="font-size-15px" for="igv">IGV</label>
              <span class="form-control form-control-sm"> '.$rspta['val_igv'].' </span>                                 
            </div>
          </div>
          <!-- Descripcion-->
          <div class="col-lg-6">
            <div class="form-group">
              <label class="font-size-15px" for="descripcion">Descripción </label> <br />
              <textarea class="form-control form-control-sm" readonly rows="1">'.((empty($rspta['descripcion'])) ? '- - -' :$rspta['descripcion']).'</textarea>
            </div>
        </div>';

        $tbody = "";

        while ($reg = $rspta2->fetch_object()) {

          empty($reg->ficha_tecnica) ? ($ficha = '<i class="far fa-file-pdf fa-lg text-gray-50"></i>') : ($ficha = '<a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg->ficha_tecnica . '"><i class="far fa-file-pdf fa-lg text-primary"></i></a>');
          
          $tbody .= '<tr class="filas">
            <td class="text-center p-6px">' . $ficha . '</td>
            <td class="text-left p-6px">
              <div class="user-block text-nowrap">
                <img class="profile-user-img img-responsive img-circle cursor-pointer" src="../dist/docs/material/img_perfil/'.$reg->imagen.'" alt="user image" onerror="this.src=\'../dist/svg/default_producto.svg\';" >
                <span class="username"><p class="mb-0 ">' . $reg->nombre . '</p></span>
                <span class="description"><b>Color: </b>' . $reg->color . '</span>
              </div>
            </td>
            <td class="text-left p-6px">' . $reg->unidad_medida . '</td>
            <td class="text-center p-6px">' . $reg->cantidad . '</td>		
            <td class="text-right p-6px">' . number_format($reg->precio_sin_igv, 2, '.',',') . '</td>
            <td class="text-right p-6px">' . number_format($reg->igv, 2, '.',',') . '</td>
            <td class="text-right p-6px">' . number_format($reg->precio_con_igv, 2, '.',',') . '</td>
            <td class="text-right p-6px">' . number_format($reg->descuento, 2, '.',',') . '</td>
            <td class="text-right p-6px">' . number_format($reg->subtotal, 2, '.',',') .'</td>
          </tr>';
        }         

        echo '<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
          <table class="table table-striped table-bordered table-condensed table-hover">
            <thead style="background-color:#ff6c046b">
              <th class="text-center p-10px">F.T.</th>
              <th class="p-10px">Material</th>
              <th class="p-10px">U.M.</th>
              <th class="p-10px">Cant.</th>
              <th class="p-10px">V/U</th>
              <th class="p-10px">IGV</th>
              <th class="p-10px">P/U</th>
              <th class="p-10px">Desct.</th>
              <th class="p-10px">Subtotal</th>
            </thead>
            <tbody>'.$tbody.'</tbody>          
            <tfoot>
              <td colspan="7"></td>
              <th class="text-right">
                <h6>Subtotal</h6>
                <h6>IGV</h6>
                <h5 class="font-weight-bold">TOTAL</h5>
              </th>
              <th class="text-right">
                <h6 class="font-weight-bold">S/. ' . number_format($rspta['subtotal'], 2, '.',',') . '</h6>
                <h6 class="font-weight-bold">S/. ' . number_format($rspta['igv'], 2, '.',',') . '</h6>
                <h5 class="font-weight-bold">S/. ' . number_format($rspta['total'], 2, '.',',') . '</h5>
              </th>
            </tfoot>
          </table>
        </div> ';

      break;
      

      // :::::::::::::::::::::::::: S E L E C T 2 ::::::::::::::::::::::::::
      case 'select2Proveedor': 

        $rspta=$compra->select2_proveedor();
    
        while ($reg = $rspta->fetch_object())	{
    
          echo '<option value=' . $reg->idproveedor . '>' . $reg->razon_social .' - '. $reg->ruc . '</option>';
    
        }
    
      break;
    
      case 'select2Banco': 
    
        $rspta = $compra->select2_banco();
    
        while ($reg = $rspta->fetch_object())  {
    
          echo '<option value=' . $reg->id . '>' . $reg->nombre . ((empty($reg->alias)) ? "" : " - $reg->alias" ) .'</option>';
        }
    
      break;
    
      case 'select2Color': 
    
        $rspta = $compra->select2_color();
    
        while ($reg = $rspta->fetch_object())  {
    
          echo '<option value=' . $reg->id . '>' . $reg->nombre .'</option>';
        }
    
      break;
    
      case 'select2UnidaMedida': 
    
        $rspta = $compra->select2_unidad_medida();
    
        while ($reg = $rspta->fetch_object())  {
    
          echo '<option value=' . $reg->id . '>' . $reg->nombre . ' - ' . $reg->abreviacion .'</option>';
        }
    
      break;
    
      case 'select2Categoria': 
    
        $rspta = $compra->select2_categoria();
    
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

// convierte de una fecha(aa-mm-dd): 2021-12-23 a una fecha(dd-mm-aa): 23-12-2021
function format_d_m_a($fecha) {

  $fecha_convert = "";

  if (!empty($fecha) || $fecha != '0000-00-00') {

    $fecha_expl = explode("-", $fecha);

    $fecha_convert = $fecha_expl[2] . "-" . $fecha_expl[1] . "-" . $fecha_expl[0];

  } 

  return $fecha_convert;
}

function quitar_guion($numero) {
  return str_replace("-", "", $numero);
}

ob_end_flush();
?>
