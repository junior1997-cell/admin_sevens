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
    if ($_SESSION['compra_insumos'] == 1) {

      require_once "../modelos/Resumen_activos_fijos.php";
      require_once "../modelos/Compra_insumos.php";
      require_once "../modelos/AllProveedor.php";
      require_once "../modelos/Activos_fijos.php";
      
      $resumen_activo_fijo = new ResumenActivoFijo();
      $compra = new Compra_insumos();
      $proveedor = new AllProveedor();
      $activos_fijos = new Activos_fijos();

      date_default_timezone_set('America/Lima');
      $date_now = date("d-m-Y h.i.s A");

      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

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
      $idgrupo          = isset($_POST["idtipo_tierra_concreto"]) ? limpiarCadena($_POST["idtipo_tierra_concreto"]) : "" ;
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

          $imagen_error = "this.src='../dist/svg/404-v2.svg'";

          while ($reg = $rspta['data']->fetch_object()) {

            $precio_promedio = number_format($reg->precio_con_igv / $reg->count_productos, 2, ".", ",");
            $imagen = (empty($reg->imagen) ? '../dist/docs/material/img_perfil/producto-sin-foto.svg' : '../dist/docs/material/img_perfil/'.$reg->imagen) ;

            $data[] = [             
              "0"  => $count++,       
              "1" => '<button class="btn btn-warning btn-sm" onclick="mostrar_material(' . $reg->idproducto . ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>
                <button class="btn btn-info btn-sm" onclick="mostrar_detalle_material(' . $reg->idproducto . ')" data-toggle="tooltip" data-original-title="Detalle Activo Fijo"><i class="far fa-eye"></i></button>',
              "2" => $reg->idproducto,      
              "3" => '<div class="user-block"> 
                <img class="profile-user-img img-responsive img-circle cursor-pointer" src="' . $imagen . '" onclick="ver_img_material(\'' . $imagen . '\', \''.encodeCadenaHtml($reg->nombre_producto).'\');" alt="User Image" onerror="' .  $imagen_error .  '" data-toggle="tooltip" data-original-title="Ver imagen">
                <span class="username"><p class="text-primary m-b-02rem" >' . $reg->nombre_producto . '</p></span>
                <span class="description"> '.(empty($reg->modelo) ? '' : '<b class="d-none">═</b> <b >Modelo:</b> ' . $reg->modelo ).'</span>
              </div>',
              "4" => $reg->grupo,
              "5" => $reg->marca,
              "6" => $reg->nombre_medida,
              "7" => $reg->cantidad_total,
              "8" => '<button class="btn btn-info btn-sm mb-2" onclick="tbla_facuras(' . $reg->idproyecto . ', ' . $reg->idproducto . ', \'' .  htmlspecialchars($reg->nombre_producto, ENT_QUOTES) . '\', \'' .  $precio_promedio . '\', \'' .  number_format($reg->precio_total, 2, ".", ",") . '\')" data-toggle="tooltip" data-original-title="Ver compras"><i class="far fa-eye"></i></button>'.$toltip,
              "9" => number_format($reg->promedio_precio, 2, ".", ""),
              "10" => number_format($reg->precio_actual, 2, ".", ""),
              "11" => number_format($reg->precio_total, 2, ".", ""),             
              "12" => $reg->nombre_producto,             
              "13" => $reg->modelo,             
            ];
          }

          $results = [
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data,
          ];
          echo json_encode($results, true);

        break;

        case 'suma_total_maquinaria':
          $idproyecto = $_POST["idproyecto"];

          $rspta = $resumen_activo_fijo->suma_total_maquinaria($idproyecto);

          echo json_encode($rspta, true);
        break;
        
        // -------------------------------------
        case 'tbla_principal_equipo':

          $idproyecto = $_GET["id_proyecto"];

          $rspta = $resumen_activo_fijo->tbla_principal_equipo($idproyecto);
          //Vamos a declarar un array
          $data = []; $count = 1;

          $imagen_error = "this.src='../dist/svg/404-v2.svg'";

          while ($reg = $rspta['data']->fetch_object()) {

            $precio_promedio = number_format($reg->precio_con_igv / $reg->count_productos, 2, ".", ",");
            $imagen = (empty($reg->imagen) ? '../dist/docs/material/img_perfil/producto-sin-foto.svg' : '../dist/docs/material/img_perfil/'.$reg->imagen) ;

            $data[] = [             
              "0"  => $count++,       
              "1" => '<button class="btn btn-warning btn-sm" onclick="mostrar_material(' . $reg->idproducto . ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>
                <button class="btn btn-info btn-sm" onclick="mostrar_detalle_material(' . $reg->idproducto . ')" data-toggle="tooltip" data-original-title="Detalle Activo Fijo"><i class="far fa-eye"></i></button>',
              "2" => $reg->idproducto,      
              "3" => '<div class="user-block"> 
                <img class="profile-user-img img-responsive img-circle cursor-pointer" src="' . $imagen . '" onclick="ver_img_material(\'' . $imagen . '\', \''.encodeCadenaHtml($reg->nombre_producto).'\');" alt="User Image" onerror="' .  $imagen_error .  '" data-toggle="tooltip" data-original-title="Ver imagen">
                <span class="username"><p class="text-primary m-b-02rem" >' . $reg->nombre_producto . '</p></span>
                <span class="description"> '.(empty($reg->modelo) ? '' : '<b class="d-none">═</b> <b >Modelo:</b> ' . $reg->modelo ).'</span>
              </div>',
              "4" => $reg->grupo,
              "5" => $reg->marca,
              "6" => $reg->nombre_medida,
              "7" => $reg->cantidad_total,
              "8" => '<button class="btn btn-info btn-sm mb-2" onclick="tbla_facuras(' . $reg->idproyecto . ', ' . $reg->idproducto . ', \'' .  htmlspecialchars($reg->nombre_producto, ENT_QUOTES) . '\', \'' .  $precio_promedio . '\', \'' .  number_format($reg->precio_total, 2, ".", ",") . '\')" data-toggle="tooltip" data-original-title="Ver compras"><i class="far fa-eye"></i></button>'.$toltip,
              "9" => number_format($reg->promedio_precio, 2, ".", ""),
              "10" => number_format($reg->precio_actual, 2, ".", ""),
              "11" => number_format($reg->precio_total, 2, ".", ""),             
              "12" => $reg->nombre_producto,             
              "13" => $reg->modelo,               
            ];
          }

          $results = [
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data,
          ];
          echo json_encode($results, true);

        break;

        case 'suma_total_equipo':
          $idproyecto = $_POST["idproyecto"];

          $rspta = $resumen_activo_fijo->suma_total_equipo($idproyecto);

          echo json_encode($rspta, true);
        break;
        
        // -------------------------------------
        case 'tbla_principal_herramienta':

          $idproyecto = $_GET["id_proyecto"];

          $rspta = $resumen_activo_fijo->tbla_principal_herramienta($idproyecto);
          //Vamos a declarar un array
          $data = []; $count = 1;

          $imagen_error = "this.src='../dist/svg/404-v2.svg'";

          while ($reg = $rspta['data']->fetch_object()) {

            $precio_promedio = number_format($reg->precio_con_igv / $reg->count_productos, 2, ".", ",");
            $imagen = (empty($reg->imagen) ? '../dist/docs/material/img_perfil/producto-sin-foto.svg' : '../dist/docs/material/img_perfil/'.$reg->imagen) ;

            $data[] = [             
              "0"  => $count++,       
              "1" => '<button class="btn btn-warning btn-sm" onclick="mostrar_material(' . $reg->idproducto . ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>
                <button class="btn btn-info btn-sm" onclick="mostrar_detalle_material(' . $reg->idproducto . ')" data-toggle="tooltip" data-original-title="Detalle Activo Fijo"><i class="far fa-eye"></i></button>',
              "2" => $reg->idproducto,      
              "3" => '<div class="user-block"> 
                <img class="profile-user-img img-responsive img-circle cursor-pointer" src="' . $imagen . '" onclick="ver_img_material(\'' . $imagen . '\', \''.encodeCadenaHtml($reg->nombre_producto).'\');" alt="User Image" onerror="' .  $imagen_error .  '" data-toggle="tooltip" data-original-title="Ver imagen">
                <span class="username"><p class="text-primary m-b-02rem" >' . $reg->nombre_producto . '</p></span>
                <span class="description"> '.(empty($reg->modelo) ? '' : '<b class="d-none">═</b> <b >Modelo:</b> ' . $reg->modelo ).'</span>
              </div>',
              "4" => $reg->grupo,
              "5" => $reg->marca,
              "6" => $reg->nombre_medida,
              "7" => $reg->cantidad_total,
              "8" => '<button class="btn btn-info btn-sm mb-2" onclick="tbla_facuras(' . $reg->idproyecto . ', ' . $reg->idproducto . ', \'' .  htmlspecialchars($reg->nombre_producto, ENT_QUOTES) . '\', \'' .  $precio_promedio . '\', \'' .  number_format($reg->precio_total, 2, ".", ",") . '\')" data-toggle="tooltip" data-original-title="Ver compras"><i class="far fa-eye"></i></button>'.$toltip,
              "9" => number_format($reg->promedio_precio, 2, ".", ""),
              "10" => number_format($reg->precio_actual, 2, ".", ""),
              "11" => number_format($reg->precio_total, 2, ".", ""),             
              "12" => $reg->nombre_producto,             
              "13" => $reg->modelo,              
            ];
          }

          $results = [
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data,
          ];
          echo json_encode($results, true);

        break;

        case 'suma_total_herramienta':
          $idproyecto = $_POST["idproyecto"];

          $rspta = $resumen_activo_fijo->suma_total_herramienta($idproyecto);

          echo json_encode($rspta, true);
        break;
        
        // -------------------------------------
        case 'tbla_principal_oficina':

          $idproyecto = $_GET["id_proyecto"];

          $rspta = $resumen_activo_fijo->tbla_principal_oficina($idproyecto);
          //Vamos a declarar un array
          $data = []; $count = 1;

          $imagen_error = "this.src='../dist/svg/404-v2.svg'";

          while ($reg = $rspta['data']->fetch_object()) {

            $precio_promedio = number_format($reg->precio_con_igv / $reg->count_productos, 2, ".", ",");
            $imagen = (empty($reg->imagen) ? '../dist/docs/material/img_perfil/producto-sin-foto.svg' : '../dist/docs/material/img_perfil/'.$reg->imagen) ;

            $data[] = [             
              "0"  => $count++,       
              "1" => '<button class="btn btn-warning btn-sm" onclick="mostrar_material(' . $reg->idproducto . ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>
                <button class="btn btn-info btn-sm" onclick="mostrar_detalle_material(' . $reg->idproducto . ')" data-toggle="tooltip" data-original-title="Detalle Activo Fijo"><i class="far fa-eye"></i></button>',
              "2" => $reg->idproducto,      
              "3" => '<div class="user-block"> 
                <img class="profile-user-img img-responsive img-circle cursor-pointer" src="' . $imagen . '" onclick="ver_img_material(\'' . $imagen . '\', \''.encodeCadenaHtml($reg->nombre_producto).'\');" alt="User Image" onerror="' .  $imagen_error .  '" data-toggle="tooltip" data-original-title="Ver imagen">
                <span class="username"><p class="text-primary m-b-02rem" >' . $reg->nombre_producto . '</p></span>
                <span class="description"> '.(empty($reg->modelo) ? '' : '<b class="d-none">═</b> <b >Modelo:</b> ' . $reg->modelo ).'</span>
              </div>',
              "4" => $reg->grupo,
              "5" => $reg->marca,
              "6" => $reg->nombre_medida,
              "7" => $reg->cantidad_total,
              "8" => '<button class="btn btn-info btn-sm mb-2" onclick="tbla_facuras(' . $reg->idproyecto . ', ' . $reg->idproducto . ', \'' .  htmlspecialchars($reg->nombre_producto, ENT_QUOTES) . '\', \'' .  $precio_promedio . '\', \'' .  number_format($reg->precio_total, 2, ".", ",") . '\')" data-toggle="tooltip" data-original-title="Ver compras"><i class="far fa-eye"></i></button>'.$toltip,
              "9" => number_format($reg->promedio_precio, 2, ".", ""),
              "10" => number_format($reg->precio_actual, 2, ".", ""),
              "11" => number_format($reg->precio_total, 2, ".", ""),             
              "12" => $reg->nombre_producto,             
              "13" => $reg->modelo,         
            ];
          }

          $results = [
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data,
          ];
          echo json_encode($results, true);

        break;

        case 'suma_total_oficina':
          $idproyecto = $_POST["idproyecto"];

          $rspta = $resumen_activo_fijo->suma_total_oficina($idproyecto);

          echo json_encode($rspta, true);
        break;

        
        // :::::::::::::::::::::::::: S E C C I O N   P R O V E E D O R :::::::::::::::::::::::::: 
        case 'guardar_proveedor':

          if (empty($idproveedor_prov)){

            $rspta=$proveedor->insertar($nombre_prov, $tipo_documento_prov, $num_documento_prov, $direccion_prov, $telefono_prov,
            $c_bancaria_prov, $cci_prov, $c_detracciones_prov, $banco_prov, $titular_cuenta_prov);
            
            echo json_encode($rspta, true);
          }else{

            $rspta = $proveedor->editar($idproveedor_prov, $nombre_prov, $tipo_documento_prov, $num_documento_prov, $direccion_prov, $telefono_prov,
            $c_bancaria_prov, $cci_prov, $c_detracciones_prov, $banco_prov, $titular_cuenta_prov);
            echo json_encode($rspta, true);
          }

        break;

        case 'mostrar_editar_proveedor':
          $rspta = $proveedor->mostrar($_POST["idproveedor"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
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
      
            $img_pefil_p =  $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);
      
            move_uploaded_file($_FILES["foto2"]["tmp_name"], "../dist/docs/material/img_perfil/" . $img_pefil_p);
          }
      
          // ficha técnica
          if (!file_exists($_FILES['doc2']['tmp_name']) || !is_uploaded_file($_FILES['doc2']['tmp_name'])) {
      
            $ficha_tecnica_p = $_POST["doc_old_2"];
      
            $flat_ficha1 = false;
      
          } else {
      
            $ext1 = explode(".", $_FILES["doc2"]["name"]);
      
            $flat_ficha1 = true;
      
            $ficha_tecnica_p =  $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);
      
            move_uploaded_file($_FILES["doc2"]["tmp_name"], "../dist/docs/material/ficha_tecnica/" . $ficha_tecnica_p);
          }
      
          if (empty($idproducto_p)) {
            //var_dump($idproyecto,$idproveedor);
            $rspta = $activos_fijos->insertar( $unidad_medida_p, $color_p, $categoria_insumos_af_p,$idgrupo, $nombre_p, $modelo_p, $serie_p, $marca_p, $estado_igv_p, $precio_unitario_p, $precio_igv_p, $precio_sin_igv_p, $precio_total_p, $ficha_tecnica_p, $descripcion_p,  $img_pefil_p);
            
            echo json_encode($rspta, true);
      
          } else {
            // validamos si existe LA IMG para eliminarlo
            if ($flat_img1 == true) {

              $datos_f1 = $activos_fijos->obtenerImg($idproducto_p);

              $img1_ant = $datos_f1['data']->fetch_object()->imagen;

              if ($img1_ant != "") {

                unlink("../dist/docs/material/img_perfil/" . $img1_ant);
              }
            }
            
            $rspta = $activos_fijos->editar( $idproducto_p, $unidad_medida_p, $color_p, $categoria_insumos_af_p,$idgrupo, $nombre_p, $modelo_p, $serie_p, $marca_p, $estado_igv_p, $precio_unitario_p, $precio_igv_p, $precio_sin_igv_p, $precio_total_p, $ficha_tecnica_p, $descripcion_p,  $img_pefil_p);
            //var_dump($idactivos_fijos,$idproveedor);
            echo json_encode($rspta, true);
          }
      
        break;

        case 'mostrar_materiales':

          $rspta = $activos_fijos->mostrar($idproducto_p);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
      
        break;

        case 'listarMaterialescompra':     

          $rspta = $resumen_activo_fijo->listar_productos();
          //Vamos a declarar un array
          $datas = [];
          $imagen_error = "this.src='../dist/svg/404-v2.svg'";
          $color_stock = "";  
          
          if ($rspta['status'] == true) {
            while ($reg = $rspta['data']->fetch_object()) {
        
              $img = (!empty($reg->imagen) ? "../dist/docs/material/img_perfil/$reg->imagen" :  "../dist/docs/material/img_perfil/producto-sin-foto.svg" );
        
              $ficha_tecnica = (!empty($reg->ficha_tecnica) ? '<center><a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg->ficha_tecnica . '"><i class="far fa-file-pdf fa-2x text-success"></i></a></center>': '<center><span class="text-center"> <i class="far fa-times-circle fa-2x text-danger"></i></span></center>');
              
              $datas[] = [
                "0" => '<button class="btn btn-warning" onclick="agregarDetalleComprobante(' . $reg->idproducto . ', \'' . htmlspecialchars($reg->nombre, ENT_QUOTES) . '\', \'' . $reg->nombre_medida . '\', \'' . $reg->nombre_color . '\', \'' . $reg->precio_sin_igv . '\', \'' . $reg->precio_igv . '\', \'' . $reg->precio_total . '\', \'' . $reg->imagen . '\', \'' . $reg->ficha_tecnica . '\')" data-toggle="tooltip" data-original-title="Agregar Producto">
                  <span class="fa fa-plus"></span>
                </button>',
                "1" => '<div class="user-block w-250px"> <img class="profile-user-img img-responsive img-circle" src="' . $img .  '" alt="user image" onerror="' . $imagen_error . '"> 
                  <span class="username"><p style="margin-bottom: 0px !important;">' .   $reg->nombre . '</p></span> 
                  <span class="description"><b>Color: </b>' .$reg->nombre_color . '</span>
                  <span class="description"><b>Marca: </b>' .$reg->marca . '</span>
                </div>',
                "2" => $reg->categoria,
                "3" => number_format($reg->precio_unitario, 2, '.', ','),
                "4" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.$reg->descripcion.'</textarea>',
                "5" => $ficha_tecnica . $toltip,
              ];
            }
        
            $results = [
              "sEcho" => 1, //Información para el datatables
              "iTotalRecords" => count($datas), //enviamos el total registros al datatable
              "iTotalDisplayRecords" => count($datas), //enviamos el total registros a visualizar
              "aaData" => $datas,
            ];
            echo json_encode($results, true);
          } else {
            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          } 
        break;

        // :::::::::::::::::::::::::: S E C C I O N   C O M P R A ::::::::::::::::::::::::::
        case 'guardar_y_editar_compra':
          if (empty($idcompra_proyecto)) {
            $rspta = $compra->insertar( $idproyecto, $idproveedor, $fecha_compra,  $tipo_comprobante, $serie_comprobante, $val_igv, $descripcion, 
            $glosa, $total_venta, $subtotal_compra, $igv_compra, $estado_detraccion, $_POST["idproducto"], $_POST["unidad_medida"], 
            $_POST["nombre_color"], $_POST["cantidad"], $_POST["precio_sin_igv"], $_POST["precio_igv"],  $_POST["precio_con_igv"], $_POST["descuento"], 
            $tipo_gravada, $_POST["ficha_tecnica_producto"]);
            //precio_sin_igv,precio_igv,precio_total
            echo json_encode($rspta, true);
          } else {
            $rspta = $compra->editar( $idcompra_proyecto, $idproyecto, $idproveedor, $fecha_compra,  $tipo_comprobante, $serie_comprobante, $val_igv, $descripcion, 
            $glosa, $total_venta, $subtotal_compra, $igv_compra, $estado_detraccion, $_POST["idproducto"], $_POST["unidad_medida"], 
            $_POST["nombre_color"], $_POST["cantidad"], $_POST["precio_sin_igv"], $_POST["precio_igv"],  $_POST["precio_con_igv"], $_POST["descuento"], 
            $tipo_gravada, $_POST["ficha_tecnica_producto"] );
      
            echo json_encode($rspta, true);
          }
      
        break;

        case 'ver_compra_editar':
          $rspta = $compra->mostrar_compra_para_editar($idcompra_proyecto);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
      
        break;     
        
        case 'tbla_facturas':
          $idproyecto = $_GET["idproyecto"];
          $idproducto = $_GET["idproducto"];

          $rspta = $resumen_activo_fijo->tbla_facturas($idproyecto, $idproducto);
          //Vamos a declarar un array
          $data = []; $cont = 1;

          $imagen_error = "this.src='../dist/svg/user_default.svg'";
          $ficha_tecnica = "";
          if ($rspta['status']) {
            foreach ($rspta['data'] as $key => $reg) {
              // validamos si existe una ficha tecnica
              !empty($reg->ficha_tecnica)
                ? ($ficha_tecnica = '<center><a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg['ficha_tecnica'] . '"><i class="far fa-file-pdf fa-lg text-success"></i></a></center>')
                : ($ficha_tecnica = '<center><i class="far fa-file-pdf fa-lg text-gray-50"></i></center>');
              
              $btn_tipo = (empty($reg['cant_comprobantes']) ? 'btn-outline-info' : 'btn-info');
              $descrip_toltip = (empty($reg['cant_comprobantes']) ? 'Vacío' : ($reg['cant_comprobantes']==1 ?  $reg['cant_comprobantes'].' comprobante' : $reg['cant_comprobantes'].' comprobantes'));       

              $data[] = [    
                "0" => $cont++,
                "1" => '<button class="btn btn-info btn-sm" onclick="ver_detalle_compras(' . $reg['idcompra_proyecto'] . ')" data-toggle="tooltip" data-original-title="Ver detalle compra"><i class="fa fa-eye"></i></button>' .
                ' <button class="btn btn-warning btn-sm" onclick="editar_detalle_compras(' . $reg['idcompra_proyecto'] . ')" data-toggle="tooltip" data-original-title="Editar compra"><i class="fas fa-pencil-alt"></i></button>'. $toltip,
                "2" => '<span class="text-primary font-weight-bold" >' . $reg['proveedor'] . '</span>',      
                "3" =>'<span class="" ><b>' . $reg['tipo_comprobante'] .  '</b> '.(empty($reg['serie_comprobante']) ?  "" :  '- '.$reg['serie_comprobante']).'</span>',  
                "4" => $reg['fecha_compra'],
                "5" => number_format($reg['cantidad'], 2, ".", ","),
                "6" => '<b>' . number_format($reg['precio_con_igv'], 2, ".", ",") . '</b>',
                "7" => number_format($reg['descuento'], 2, ".", ""),
                "8" => number_format($reg['subtotal'], 2, ".", ""),
                "9" => '<center> <button class="btn '.$btn_tipo.' btn-sm" onclick="comprobante_compras(\''.$reg['idcompra_proyecto'].'\', \''.$cont.'\', \''.encodeCadenaHtml($reg['tipo_comprobante'].' '.(empty($reg['serie_comprobante']) ?  "" :  '- '.$reg['serie_comprobante'])).'\', \''.format_d_m_a($reg['fecha_compra']).'\')" data-toggle="tooltip" data-original-title="'.$descrip_toltip.'"><i class="fas fa-file-invoice fa-lg"></i></button> </center>'.$toltip,

                // "7" => $ficha_tecnica,
              ];
            }

            $results = [
              "sEcho" => 1, //Información para el datatables
              "iTotalRecords" => count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
              "aaData" => $data,
            ];
            echo json_encode($results, true);
              
          } else {
            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          }
        break;

        case 'sumas_factura_x_material':
          $rspta = $resumen_activo_fijo->sumas_factura_x_material($_POST["idproyecto"], $_POST["idproducto"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
      
        break; 

        case 'ver_detalle_compras':
          
          $rspta = $compra->ver_compra($_GET['id_compra']);
          $rspta2 = $compra->ver_detalle_compra($_GET['id_compra']);

          $subtotal = 0;    $ficha = ''; 

          echo '<!-- Tipo de Empresa -->
            <div class="col-lg-6">
              <div class="form-group">
                <label class="font-size-15px" for="idproveedor">Proveedor</label>
                <h5 class="form-control form-control-sm" >'.$rspta['data']['razon_social'].'</h5>
              </div>
            </div>
            <!-- fecha -->
            <div class="col-lg-3">
              <div class="form-group">
                <label class="font-size-15px" for="fecha_compra">Fecha </label>
                <span class="form-control form-control-sm"><i class="far fa-calendar-alt"></i>&nbsp;&nbsp;&nbsp;'.format_d_m_a($rspta['data']['fecha_compra']).' </span>
              </div>
            </div>
            <!-- fecha -->
            <div class="col-lg-3">
              <div class="form-group">
                <label class="font-size-15px" for="fecha_compra">Glosa </label>
                <span class="form-control form-control-sm">'.$rspta['data']['glosa'].' </span>
              </div>
            </div>
            <!-- Tipo de comprobante -->
            <div class="col-lg-3">
              <div class="form-group">
                <label class="font-size-15px" for="tipo_comprovante">Tipo Comprobante</label>
                <span  class="form-control form-control-sm"> '. ((empty($rspta['data']['tipo_comprobante'])) ? '- - -' :  $rspta['data']['tipo_comprobante'])  .' </span>
              </div>
            </div>
            <!-- serie_comprovante-->
            <div class="col-lg-2">
              <div class="form-group">
                <label class="font-size-15px" for="serie_comprovante">N° de Comprobante</label>
                <span  class="form-control form-control-sm"> '. ((empty($rspta['data']['serie_comprobante'])) ? '- - -' :  $rspta['data']['serie_comprobante']).' </span>
              </div>
            </div>
            <!-- IGV-->
            <div class="col-lg-1 " >
              <div class="form-group">
                <label class="font-size-15px" for="igv">IGV</label>
                <span class="form-control form-control-sm"> '.$rspta['data']['val_igv'].' </span>                                 
              </div>
            </div>
            <!-- Descripcion-->
            <div class="col-lg-6">
              <div class="form-group">
                <label class="font-size-15px" for="descripcion">Descripción </label> <br />
                <textarea class="form-control form-control-sm" readonly rows="1">'.((empty($rspta['data']['descripcion'])) ? '- - -' :$rspta['data']['descripcion']).'</textarea>
              </div>
          </div>';

          $tbody = ""; $cont = 1;

          while ($reg = $rspta2['data']->fetch_object()) {

            $ficha = empty($reg->ficha_tecnica) ? ( '<i class="far fa-file-pdf fa-lg text-gray-50" data-toggle="tooltip" data-original-title="Vacío"></i>') : ( '<a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg->ficha_tecnica . '" data-toggle="tooltip" data-original-title="Ver Ficha Técnica"><i class="far fa-file-pdf fa-lg text-primary"></i></a>');
            
            $img_product = '../dist/docs/material/img_perfil/'. (empty($reg->imagen) ? 'producto-sin-foto.svg' : $reg->imagen );

            $tbody .= '<tr class="filas">
              <td class="text-center p-6px">' . $cont++ . '</td>
              <td class="text-center p-6px">' . $ficha . '</td>
              <td class="text-left p-6px">
                <div class="user-block text-nowrap">
                  <img class="profile-user-img img-responsive img-circle cursor-pointer" onclick="ver_img_material(\''.$img_product.'\', \'' . encodeCadenaHtml( $reg->nombre) . '\', null)" src="'. $img_product .'" alt="user image" onerror="this.src=\'../dist/svg/404-v2.svg\';" data-toggle="tooltip" data-original-title="Ver img" >
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
            <table class="table table-striped table-bordered table-condensed table-hover" id="tabla_detalle_factura">
              <thead class="bg-color-ff6c046b">
                <tr class="text-center hidden">
                  <th class="p-10px">Proveedor:</th>
                  <th class="text-center p-10px" colspan="9" >'.$rspta['data']['razon_social'].'</th>
                </tr>
                <tr class="text-center hidden">                
                  <th class="text-center p-10px" colspan="2" >'.((empty($rspta['data']['tipo_comprobante'])) ? '' :  $rspta['data']['tipo_comprobante']). ' ─ ' . ((empty($rspta['data']['serie_comprobante'])) ? '' :  $rspta['data']['serie_comprobante']) .'</th>
                  <th class="p-10px">Fecha:</th>
                  <th class="text-center p-10px" colspan="3" >'.format_d_m_a($rspta['data']['fecha_compra']).'</th>
                  <th class="p-10px">Glosa:</th>
                  <th class="text-center p-10px" colspan="3" >'.$rspta['data']['glosa'].'</th>
                </tr>
                <tr class="text-center">
                  <th class="text-center p-10px" >#</th>
                  <th class="text-center p-10px">F.T.</th>
                  <th class="p-10px">Material</th>
                  <th class="p-10px">U.M.</th>
                  <th class="p-10px">Cant.</th>
                  <th class="p-10px">V/U</th>
                  <th class="p-10px">IGV</th>
                  <th class="p-10px">P/U</th>
                  <th class="p-10px">Desct.</th>
                  <th class="p-10px">Subtotal</th>
                </tr>
              </thead>
              <tbody>'.$tbody.'</tbody>          
              <tfoot>
                <tr>
                  <td class="p-0" colspan="8"></td>
                  <td class="p-0 text-right"> <h6 class="mt-1 mb-1 mr-1">'.$rspta['data']['tipo_gravada'].'</h6> </td>
                  <td class="p-0 text-right">
                    <h6 class="mt-1 mb-1 mr-1 pl-1 font-weight-bold text-nowrap formato-numero-conta"><span>S/</span>' . number_format($rspta['data']['subtotal'], 2, '.',',') . '</h6>
                  </td>
                </tr>
                <tr>
                  <td class="p-0" colspan="8"></td>
                  <td class="p-0 text-right">
                    <h6 class="mt-1 mb-1 mr-1">IGV('.( ( empty($rspta['data']['val_igv']) ? 0 : floatval($rspta['data']['val_igv']) )  * 100 ).'%)</h6>
                  </td>
                  <td class="p-0 text-right">
                    <h6 class="mt-1 mb-1 mr-1 pl-1 font-weight-bold text-nowrap formato-numero-conta"><span>S/</span>' . number_format($rspta['data']['igv'], 2, '.',',') . '</h6>
                  </td>
                </tr>
                <tr>
                  <td class="p-0" colspan="8"></td>
                  <td class="p-0 text-right"> <h5 class="mt-1 mb-1 mr-1 font-weight-bold">TOTAL</h5> </td>
                  <td class="p-0 text-right">
                    <h5 class="mt-1 mb-1 mr-1 pl-1 font-weight-bold text-nowrap formato-numero-conta"><span>S/</span>' . number_format($rspta['data']['total'], 2, '.',',') . '</h5>
                  </td>
                </tr>
              </tfoot>
            </table>
          </div> ';

        break;

                
        // :::::::::::::::::::::::::: S E C C I O N   C O M P R O B A N T E  :::::::::::::::::::::::::: 

        case 'tbla_comprobantes_compra':
          $cont_compra = $_GET["num_orden"];
          $id_compra = $_GET["id_compra"];
          $rspta = $compra->tbla_comprobantes( $id_compra );
          //Vamos a declarar un array
          $data = []; $cont = 1;        
          
          if ($rspta['status']) {
            while ($reg = $rspta['data']->fetch_object()) {
              $data[] = [
                "0" => $cont,
                "1" => '<div class="text-nowrap">'.
                ' <a class="btn btn-info btn-sm " href="../dist/docs/compra_insumo/comprobante_compra/'.$reg->comprobante.'"  download="'.$cont_compra.'·'.$cont.' '.removeSpecialChar((empty($reg->serie_comprobante) ?  " " :  ' ─ '.$reg->serie_comprobante).' ─ '.$reg->razon_social).' ─ '. format_d_m_a($reg->fecha_compra).'" data-toggle="tooltip" data-original-title="Descargar" ><i class="fas fa-cloud-download-alt"></i></a>              
                </div>'.$toltip,
                "2" => '<a class="btn btn-info btn-sm" href="../dist/docs/compra_insumo/comprobante_compra/'.$reg->comprobante.'" target="_blank" rel="noopener noreferrer"><i class="fas fa-receipt"></i></a>' ,
                "3" => $reg->updated_at,
              ];
              $cont++;
            }
            $results = [
              "sEcho" => 1, //Información para el datatables
              "iTotalRecords" => count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
              "aaData" => $data,
            ];
            echo json_encode($results, true);
          } else {
            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          }
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

  ob_end_flush();
?>
