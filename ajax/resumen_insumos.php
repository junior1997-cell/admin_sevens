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

      require_once "../modelos/Resumen_insumos.php";
      require_once "../modelos/Compra_insumos.php";
      require_once "../modelos/AllProveedor.php";
      require_once "../modelos/Activos_fijos.php";

      $activos_fijos = new Activos_fijos();
      $resumen_insumos = new ResumenInsumos();
      $compra = new Compra_insumos();
      $proveedor = new AllProveedor();

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
      // input no usados        
      $color_p          = isset($_POST["color_p"]) ? limpiarCadena($_POST["color_p"]) : "" ;    
      $modelo_p         = isset($_POST["modelo_p"]) ? encodeCadenaHtml($_POST["modelo_p"]) : "" ;
      $serie_p          = isset($_POST["serie_p"]) ? limpiarCadena($_POST["serie_p"]) : "" ;      
      $estado_igv_p     = isset($_POST["estado_igv_p"]) ? limpiarCadena($_POST["estado_igv_p"]) : "" ;
      $precio_unitario_p= isset($_POST["precio_unitario_p"]) ? limpiarCadena($_POST["precio_unitario_p"]) : "" ;      
      $precio_sin_igv_p = isset($_POST["precio_sin_igv_p"]) ? limpiarCadena($_POST["precio_sin_igv_p"]) : "" ;
      $precio_igv_p     = isset($_POST["precio_igv_p"]) ? limpiarCadena($_POST["precio_igv_p"]) : "" ;
      $precio_total_p   = isset($_POST["precio_total_p"]) ? limpiarCadena($_POST["precio_total_p"]) : "" ;

      // input usados
      $idproducto_p     = isset($_POST["idproducto_p"]) ? limpiarCadena($_POST["idproducto_p"]) : "" ;
      $nombre_p         = isset($_POST["nombre_p"]) ? encodeCadenaHtml($_POST["nombre_p"]) : "" ;
      $idcategoria_p    = isset($_POST["categoria_insumos_af_p"]) ? limpiarCadena($_POST["categoria_insumos_af_p"]) : "" ;
      $unidad_medida_p  = isset($_POST["unidad_medida_p"]) ? limpiarCadena($_POST["unidad_medida_p"]) : "" ;      
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

        case 'tbla_principal':

          $idproyecto = $_GET["id_proyecto"];

          $rspta = $resumen_insumos->tbla_principal($idproyecto);
          //Vamos a declarar un array
          $data = []; $count = 1;

          $imagen_error = "this.src='../dist/svg/404-v2.svg'";

          if ($rspta['status'] == true) {
            foreach ($rspta['data'] as $key => $reg) {

              $precio_promedio = number_format($reg['precio_con_igv'] / $reg['count_productos'], 2, ".", ",");
              $imagen = (empty($reg['imagen']) ? '../dist/docs/material/img_perfil/producto-sin-foto.svg' : '../dist/docs/material/img_perfil/'.$reg['imagen']) ;

              $data[] = [     
                "0"  => $count++,       
                "1" => '<button class="btn bg-gradient-dark btn-sm" onclick="agregar_grupos(' . $reg['idproducto'] .', '.$reg['idclasificacion_grupo'] . ')" data-toggle="tooltip" data-original-title="Agregar grupo"><i class="fa-solid fa-layer-group"></i></button>
                  <button class="btn btn-warning btn-sm" onclick="mostrar_material(' . $reg['idproducto'] . ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>
                  <button class="btn btn-info btn-sm" onclick="mostrar_detalle_material(' . $reg['idproducto'] . ')" data-toggle="tooltip" data-original-title="Ver detalle insumo"><i class="far fa-eye"></i></button>',       
                "2" => $reg['idproducto'],    
                "3" => '<div class="user-block"> 
                  <img class="profile-user-img img-responsive img-circle cursor-pointer" src="' . $imagen . '" onclick="ver_img_material(\'' . $imagen . '\', \''.encodeCadenaHtml($reg['nombre_producto']).'\');" alt="User Image" onerror="' .  $imagen_error .  '" data-toggle="tooltip" data-original-title="Ver imagen">
                  <span class="username"><p class="text-primary m-b-02rem" >' . $reg['nombre_producto'] . '</p></span>
                  <span class="description"> '.(empty($reg['modelo']) ? '' : '<b class="d-none">═</b> <b >Modelo:</b> ' . $reg['modelo'] ).'</span>
                </div>',
                "4" => $reg['grupo'],
                "5" => '<div class="bg-color-242244245 " style="overflow: auto; resize: vertical; height: 45px;" >'. $reg['html_marca'] .'</div>',
                "6" => $reg['nombre_medida'],
                "7" => number_format($reg['cantidad_total'],2, ".", ","),
                "8" => '<button class="btn btn-info btn-sm mb-2" onclick="tbla_facuras(' . $reg['idproyecto'] . ', ' . $reg['idproducto'] . ', \'' .  htmlspecialchars($reg['nombre_producto'], ENT_QUOTES) . '\', \'' .  $precio_promedio . '\', \'' .  number_format($reg['precio_total'], 2, ".", ",") . '\')" data-toggle="tooltip" data-original-title="Ver compras"><i class="far fa-eye"></i></button>'. $toltip,
                "9" => number_format($reg['promedio_precio'], 2, ".", ""),
                "10" => number_format($reg['precio_actual'], 2, ".", ""),
                "11" => number_format($reg['precio_total'], 2, ".", ""),             
         
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
      
            $img_pefil_p = $date_now .' '. random_int(0, 20) . round(microtime(true)) . random_int(21, 41) . '.' . end($ext1);
      
            move_uploaded_file($_FILES["foto2"]["tmp_name"], "../dist/docs/material/img_perfil/" . $img_pefil_p);
          }
      
          // ficha técnica
          if (!file_exists($_FILES['doc2']['tmp_name']) || !is_uploaded_file($_FILES['doc2']['tmp_name'])) {
      
            $ficha_tecnica_p = $_POST["doc_old_2"];
      
            $flat_ficha1 = false;
      
          } else {
      
            $ext1 = explode(".", $_FILES["doc2"]["name"]);
      
            $flat_ficha1 = true;
      
            $ficha_tecnica_p = $date_now .' '. random_int(0, 20) . round(microtime(true)) . random_int(21, 41) . '.' . end($ext1);
      
            move_uploaded_file($_FILES["doc2"]["tmp_name"], "../dist/docs/material/ficha_tecnica/" . $ficha_tecnica_p);
          }
      
          if (empty($idproducto_p)) {
            //var_dump($idproyecto,$idproveedor);
            $rspta = $activos_fijos->insertar( $nombre_p, $idcategoria_p, $unidad_medida_p, $_POST["marcas_p"], $descripcion_p, $color_p, $modelo_p, $serie_p, $estado_igv_p, $precio_unitario_p, $precio_sin_igv_p, $precio_igv_p, $precio_total_p, $ficha_tecnica_p, $img_pefil_p);
            
            echo json_encode($rspta, true);
      
          } else {
            // validamos si existe LA IMG para eliminarlo
            if ($flat_img1 == true) {

              $datos_f1 = $activos_fijos->obtenerImg($idproducto_p);

              $img1_ant = $datos_f1['data']->fetch_object()->imagen;

              if ($img1_ant != "") { unlink("../dist/docs/material/img_perfil/" . $img1_ant); }
            }
            
            $rspta = $activos_fijos->editar( $idproducto_p, $nombre_p, $idcategoria_p, $unidad_medida_p, $_POST["marcas_p"], $descripcion_p, $color_p, $modelo_p, $serie_p, $estado_igv_p, $precio_unitario_p, $precio_sin_igv_p, $precio_igv_p, $precio_total_p, $ficha_tecnica_p, $img_pefil_p);
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

          $rspta = $resumen_insumos->listar_productos();
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

        // :::::::::::::::::::::::::: S E C C I O N   G R U P O S ::::::::::::::::::::::::::
        case 'actualizar_grupo':
            
          $rspta = $resumen_insumos->actualizar_grupo( $_POST["idproducto_g"], $_POST["idclasificacion_grupo_g"]);
          //var_dump($idactivos_fijos,$idproveedor);
          echo json_encode($rspta, true);          
      
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

        case 'tbla_facturas':
          $idproyecto = $_GET["idproyecto"];
          $idproducto = $_GET["idproducto"];

          $rspta = $resumen_insumos->tbla_facturas($idproyecto, $idproducto);
          //Vamos a declarar un array
          $data = []; $cont = 1;

          $imagen_error = "this.src='../dist/svg/user_default.svg'";
          $ficha_tecnica = "";
          if ($rspta['status']) {
            // idcompra_proyecto,num_orden, num_comprobante, fecha
            foreach ($rspta['data'] as $key => $reg) {
              // validamos si existe una ficha tecnica
              !empty($reg['ficha_tecnica'])
                ? ($ficha_tecnica = '<center><a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg['ficha_tecnica'] . '"><i class="far fa-file-pdf fa-lg text-success"></i></a></center>')
                : ($ficha_tecnica = '<center><i class="far fa-file-pdf fa-lg text-gray-50"></i></center>');

              $btn_tipo = (empty($reg['cant_comprobantes']) ? 'btn-outline-info' : 'btn-info');
              $descrip_toltip = (empty($reg['cant_comprobantes']) ? 'Vacío' : ($reg['cant_comprobantes']==1 ?  $reg['cant_comprobantes'].' comprobante' : $reg['cant_comprobantes'].' comprobantes'));       

              $data[] = [    
                "0" => $cont++,
                "1" => '<button class="btn btn-info btn-sm" onclick="ver_detalle_compras(' . $reg['idcompra_proyecto'] . ', ' .$reg['idproducto'] . ')" data-toggle="tooltip" data-original-title="Ver detalle compra"><i class="fa fa-eye"></i></button>' .
                ' <button class="btn btn-warning btn-sm" onclick="mostrar_compra_insumo(' . $reg['idcompra_proyecto'] .  ')" data-toggle="tooltip" data-original-title="Editar compra"><i class="fas fa-pencil-alt"></i></button>'. $toltip ,
                "2" => '<span class="text-primary font-weight-bold" >' . $reg['proveedor']. '</span>',    
                "3" =>'<span class="" ><b>' . $reg['tipo_comprobante'] .  '</b> '.(empty($reg['serie_comprobante']) ?  "" :  '- '.$reg['serie_comprobante']).'</span>',  
                "4" => '<div class="bg-color-242244245 " style="overflow: auto; resize: vertical; height: 45px;" >'. $reg['html_marca'] .'</div>',
                "5" => $reg['fecha_compra'],
                "6" => number_format($reg['cantidad'], 2, ".", ","),
                "7" =>  number_format($reg['precio_con_igv'], 2, ".", ","),
                "8" => number_format($reg['descuento'], 2, ".", ""),
                "9" => number_format($reg['subtotal'], 2, ".", ""),
                "10" => '<center> <button class="btn '.$btn_tipo.' btn-sm" onclick="comprobante_compras(\''.$reg['idcompra_proyecto'].'\', \''.$cont.'\', \''.encodeCadenaHtml($reg['tipo_comprobante'].' '.(empty($reg['serie_comprobante']) ?  "" :  '- '.$reg['serie_comprobante'])).'\', \''.format_d_m_a($reg['fecha_compra']).'\')" data-toggle="tooltip" data-original-title="'.$descrip_toltip.'"><i class="fas fa-file-invoice fa-lg"></i></button> </center>'.$toltip,
                
                "11" => $reg['tipo_comprobante'],
                "12" => $reg['serie_comprobante']

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
          $rspta = $resumen_insumos->sumas_factura_x_material($_POST["idproyecto"], $_POST["idproducto"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
      
        break;

        case 'ver_compra_editar':
          $rspta = $compra->mostrar_compra_para_editar($idcompra_proyecto);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
      
        break;
        
        case 'suma_total_compras':
          $idproyecto = $_POST["idproyecto"];

          $rspta = $resumen_insumos->suma_total_compras($idproyecto);

          echo json_encode($rspta, true);
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
