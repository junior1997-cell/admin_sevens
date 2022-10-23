<?php
ob_start();
if (strlen(session_id()) < 1) {
  session_start(); //Validamos si existe o no la sesión
}

if (!isset($_SESSION["nombre"])) {
  $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
  echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
} else {

  if ($_SESSION['compra_insumos'] == 1) {
    
    require_once "../modelos/Compra_insumos.php";
    require_once "../modelos/AllProveedor.php";
    require_once "../modelos/Materiales.php";

    $compra_insumos = new Compra_insumos();
    $proveedor = new AllProveedor();
    $insumos = new Materiales();      
    
    date_default_timezone_set('America/Lima');  $date_now = date("d-m-Y h.i.s A");
    $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

    $scheme_host =  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/admin_sevens/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');


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

    // :::::::::::::::::::::::::::::::::::: D A T O S   P A G O   C O M P R A ::::::::::::::::::::::::::::::::::::::
    $beneficiario_pago  = isset($_POST["beneficiario_pago"]) ? limpiarCadena($_POST["beneficiario_pago"]) : "";
    $forma_pago         = isset($_POST["forma_pago"]) ? limpiarCadena($_POST["forma_pago"]) : "";
    $tipo_pago          = isset($_POST["tipo_pago"]) ? limpiarCadena($_POST["tipo_pago"]) : "";
    $cuenta_destino_pago = isset($_POST["cuenta_destino_pago"]) ? limpiarCadena($_POST["cuenta_destino_pago"]) : "";
    $banco_pago         = isset($_POST["banco_pago"]) ? limpiarCadena($_POST["banco_pago"]) : "";
    $titular_cuenta_pago = isset($_POST["titular_cuenta_pago"]) ? limpiarCadena($_POST["titular_cuenta_pago"]) : "";
    $fecha_pago         = isset($_POST["fecha_pago"]) ? limpiarCadena($_POST["fecha_pago"]) : "";
    $monto_pago         = isset($_POST["monto_pago"]) ? limpiarCadena($_POST["monto_pago"]) : "";
    $numero_op_pago     = isset($_POST["numero_op_pago"]) ? limpiarCadena($_POST["numero_op_pago"]) : "";
    $descripcion_pago   = isset($_POST["descripcion_pago"]) ? limpiarCadena($_POST["descripcion_pago"]) : "";
    $idcompra_proyecto_p = isset($_POST["idcompra_proyecto_p"]) ? limpiarCadena($_POST["idcompra_proyecto_p"]) : "";
    $idpago_compras     = isset($_POST["idpago_compras"]) ? limpiarCadena($_POST["idpago_compras"]) : ""; 
    $idproveedor_pago   = isset($_POST["idproveedor_pago"]) ? limpiarCadena($_POST["idproveedor_pago"]) : "";
    $imagen1            = isset($_POST["doc3"]) ? limpiarCadena($_POST["doc3"]) : "";

    // :::::::::::::::::::::::::::::::::::: D A T O S   C O M P R O B A N T E ::::::::::::::::::::::::::::::::::::::
    $id_compra_proyecto = isset($_POST["id_compra_proyecto"]) ? limpiarCadena($_POST["id_compra_proyecto"]) : "";
    $idfactura_compra_insumo = isset($_POST["idfactura_compra_insumo"]) ? limpiarCadena($_POST["idfactura_compra_insumo"]) : "";
    $doc_comprobante               = isset($_POST["doc1"]) ? limpiarCadena($_POST["doc1"]) : "";
    $doc_old_1          = isset($_POST["doc_old_1"]) ? limpiarCadena($_POST["doc_old_1"]) : "";

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
    $tipo_documento_prov= isset($_POST["tipo_documento_prov"])? limpiarCadena($_POST["tipo_documento_prov"]):"";
    $num_documento_prov	= isset($_POST["num_documento_prov"])? limpiarCadena($_POST["num_documento_prov"]):"";
    $direccion_prov		  = isset($_POST["direccion_prov"])? limpiarCadena($_POST["direccion_prov"]):"";
    $telefono_prov		  = isset($_POST["telefono_prov"])? limpiarCadena($_POST["telefono_prov"]):"";
    $c_bancaria_prov		= isset($_POST["c_bancaria_prov"])? limpiarCadena($_POST["c_bancaria_prov"]):"";
    $cci_prov		    	  = isset($_POST["cci_prov"])? limpiarCadena($_POST["cci_prov"]):"";
    $c_detracciones_prov= isset($_POST["c_detracciones_prov"])? limpiarCadena($_POST["c_detracciones_prov"]):"";
    $banco_prov			    = isset($_POST["banco_prov"])? limpiarCadena($_POST["banco_prov"]):"";
    $titular_cuenta_prov= isset($_POST["titular_cuenta_prov"])? limpiarCadena($_POST["titular_cuenta_prov"]):"";

    switch ($_GET["op"]) {   
      
      // :::::::::::::::::::::::::: S E C C I O N   M A T E R I A L E S ::::::::::::::::::::::::::
      case 'guardar_y_editar_materiales':
        // imgen
        if (!file_exists($_FILES['foto2']['tmp_name']) || !is_uploaded_file($_FILES['foto2']['tmp_name'])) {
    
          $img_pefil_p = $_POST["foto2_actual"];
    
          $flat_img1 = false;
    
        } else {
    
          $ext1 = explode(".", $_FILES["foto2"]["name"]);
    
          $flat_img1 = true;
    
          $img_pefil_p = $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);
    
          move_uploaded_file($_FILES["foto2"]["tmp_name"], "../dist/docs/material/img_perfil/" . $img_pefil_p);
        }
    
        // ficha técnica
        if (!file_exists($_FILES['doc2']['tmp_name']) || !is_uploaded_file($_FILES['doc2']['tmp_name'])) {
    
          $ficha_tecnica_p = $_POST["doc_old_2"];
    
          $flat_ficha1 = false;
    
        } else {
    
          $ext1 = explode(".", $_FILES["doc2"]["name"]);
    
          $flat_ficha1 = true;
    
          $ficha_tecnica_p = $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);
    
          move_uploaded_file($_FILES["doc2"]["tmp_name"], "../dist/docs/material/ficha_tecnica/" . $ficha_tecnica_p);
        }
    
        if (empty($idproducto_p)) {
          //var_dump($idproyecto,$idproveedor);
          $rspta = $insumos->insertar($nombre_p, $idcategoria_p, $unidad_medida_p, $_POST["marcas_p"], $descripcion_p, $color_p, $modelo_p, $serie_p, $estado_igv_p, $precio_unitario_p, $precio_sin_igv_p, $precio_igv_p, $precio_total_p, $ficha_tecnica_p, $img_pefil_p );
          
          echo json_encode($rspta, true);
    
        } else {
    
          // validamos si existe LA IMG para eliminarlo
          if ($flat_img1 == true) {
    
            $datos_f1 = $insumos->obtenerImg($idproducto_p);    
            $img1_ant = (empty($datos_f1['data']) ? '' : $datos_f1['data']['imagen']);
    
            if (!empty( $img1_ant)) { unlink("../dist/docs/material/img_perfil/" . $img1_ant); }
          }
          
          $rspta = $insumos->editar($idproducto_p, $nombre_p, $idcategoria_p, $unidad_medida_p, $_POST["marcas_p"], $descripcion_p, $color_p, $modelo_p, $serie_p, $estado_igv_p, $precio_unitario_p, $precio_sin_igv_p, $precio_igv_p, $precio_total_p, $ficha_tecnica_p, $img_pefil_p);
          //var_dump($idactivos_fijos,$idproveedor);
          echo json_encode($rspta, true);
        }
    
      break;
    
      case 'mostrar_materiales':
    
        $rspta = $insumos->mostrar($idproducto_p);
        //Codificar el resultado utilizando json
        echo json_encode($rspta, true);
    
      break;
        
      // :::::::::::::::::::::::::: S E C C I O N   P R O V E E D O R  ::::::::::::::::::::::::::
      case 'guardar_proveedor':
    
        if (empty($idproveedor_prov)){
    
          $rspta=$proveedor->insertar($nombre_prov, $tipo_documento_prov, $num_documento_prov, $direccion_prov, $telefono_prov,
          $c_bancaria_prov, $cci_prov, $c_detracciones_prov, $banco_prov, $titular_cuenta_prov);
          
          echo json_encode($rspta, true);
        }else{
          $rspta=$proveedor->editar($idproveedor_prov, $nombre_prov, $tipo_documento_prov, $num_documento_prov, $direccion_prov, $telefono_prov,
          $c_bancaria_prov, $cci_prov, $c_detracciones_prov, $banco_prov, $titular_cuenta_prov);
          
          echo json_encode($rspta, true);
        }
    
      break;

      case 'mostrar_editar_proveedor':
        $rspta = $proveedor->mostrar($_POST["idproveedor"]);
        //Codificar el resultado utilizando json
        echo json_encode($rspta, true);
      break;
    
      // :::::::::::::::::::::::::: S E C C I O N   C O M P R A  ::::::::::::::::::::::::::
      case 'guardaryeditarcompra':

        if (empty($idcompra_proyecto)) {

          $rspta = $compra_insumos->insertar( $idproyecto, $idproveedor, $fecha_compra,  $tipo_comprobante, $serie_comprobante, $val_igv, $descripcion, 
          $glosa, $total_venta, $subtotal_compra, $igv_compra, $estado_detraccion, $_POST["idproducto"], $_POST["unidad_medida"], 
          $_POST["nombre_color"], $_POST["cantidad"], $_POST["precio_sin_igv"], $_POST["precio_igv"],  $_POST["precio_con_igv"], $_POST["descuento"], 
          $tipo_gravada, $_POST["ficha_tecnica_producto"] );

          echo json_encode($rspta, true);
        } else {

          $rspta = $compra_insumos->editar( $idcompra_proyecto, $idproyecto, $idproveedor, $fecha_compra,  $tipo_comprobante, $serie_comprobante, $val_igv, 
          $descripcion, $glosa, $total_venta, $subtotal_compra, $igv_compra, $estado_detraccion, $_POST["idproducto"], $_POST["unidad_medida"], 
          $_POST["nombre_color"], $_POST["cantidad"], $_POST["precio_sin_igv"], $_POST["precio_igv"],  $_POST["precio_con_igv"], $_POST["descuento"], 
          $tipo_gravada, $_POST["ficha_tecnica_producto"] );
    
          echo json_encode($rspta, true);
        }
    
      break;      
      
      case 'anular':
        $rspta = $compra_insumos->desactivar($_GET["id_tabla"]);
    
        echo json_encode($rspta, true);
    
      break;
    
      case 'des_anular':
        $rspta = $compra_insumos->activar($_GET["id_tabla"]);
    
        echo json_encode($rspta, true);
    
      break;

      case 'eliminar_compra':

        $rspta = $compra_insumos->eliminar($_GET["id_tabla"]);
    
        echo json_encode($rspta, true);
    
      break;
    
      case 'tbla_principal':
        $rspta = $compra_insumos->tbla_principal($_GET["nube_idproyecto"], $_GET["fecha_1"], $_GET["fecha_2"], $_GET["id_proveedor"], $_GET["comprobante"]);
        
        //Vamos a declarar un array
        $data = []; $cont = 1;
        $c = "";
        $cc = "";
        $nombre = "";
        $info = "";
        $icon = "";
        $stdo_detraccion = "";
        $serie_comprobante = "";
        $function_tipo_comprob = "";
        $list_segun_estado_detracc = "";
         
        $num_comprob = "";
        
        if ($rspta['status'] == true) {
          foreach ($rspta['data'] as $key => $reg) {
      
            $saldo = $reg['total'] - $reg['total_pago_compras'];       
      
            if ($saldo == $reg['total']) {
              $estado = '<span class="text-center badge badge-danger">Sin pagar</span>';
              $c = "danger";
              $nombre = "Pagar";
              $icon = "dollar-sign";
              $cc = "danger";
            } else {
              if ($saldo < $reg['total'] && $saldo > "0") {
                $estado = '<span class="text-center badge badge-warning">En proceso</span>';
                $c = "warning";
                $nombre = "Pagar";
                $icon = "dollar-sign";
                $cc = "warning";
              } else {
                if ($saldo <= "0" || $saldo == "0") {
                  $estado = '<span class="text-center badge badge-success">Pagado</span>';
                  $c = "success";
                  $nombre = "Ver";
                  $info = "info";
                  $icon = "eye";
                  $cc = "success";
                } else {
                  $estado = '<span class="text-center badge badge-success">Error</span>';
                }
                //$estado = '<span class="text-center badge badge-success">Terminado</span>';
              }
            }
      
            if ($reg['estado_detraccion'] == "1") {
      
              $deposito_Actual = 0;
      
              if ($reg['total_pago_compras'] == null || empty($reg['total_pago_compras'])) {
                $deposito_Actual = 0;
              } else {
                $deposito_Actual = $reg['total_pago_compras'];
              }
      
              $list_segun_estado_detracc = '<div class="text-center formato-numero-conta"> <button class="btn btn-' .  $c . ' btn-xs" onclick="listar_pagos_detraccion(' . $reg['idcompra_proyecto'] . ',' . $reg['idproyecto'] . ',' . $reg['total'] . ',' . $deposito_Actual .')">'.
                  '<i class="fas fa-' . $icon .' nav-icon"></i> ' .$nombre .
                '</button>' .
                ' <button style="font-size: 14px;" class="btn btn-' . $cc . ' btn-sm">' . number_format($reg['total_pago_compras'], 2, '.', ',') . '</button>'.
              '</div>';
      
            } else {
      
              $deposito_Actual = 0;
      
              if ($reg['total_pago_compras'] == null || empty($reg['total_pago_compras'])) {
                $deposito_Actual = 0;
              } else {
                $deposito_Actual = $reg['total_pago_compras'];
              }
      
              $list_segun_estado_detracc = '<div class="text-center text-nowrap formato-numero-conta">'. 
                '<button class="btn btn-' . $c . ' btn-xs m-t-2px" onclick="listar_pagos(' . $reg['idcompra_proyecto'] . ',' .  $reg['idproyecto'] .  ',' .    $reg['total'] . ', ' .  $deposito_Actual . ')">'.
                  '<i class="fas fa-' . $icon . ' nav-icon"></i> ' . $nombre . 
                '</button>' .
                ' <button style="font-size: 14px;" class="btn btn-' .  $cc . ' btn-sm">' .  number_format($reg['total_pago_compras'], 2, '.', ',') . '</button>'.
              '</div>';
            }
      
            $vercomprobantes = '\''.$reg['idcompra_proyecto'].'\',\''.$reg['comprobante'].'\''; 
      
            $btn_tipo = (empty($reg['cant_comprobantes']) ? 'btn-outline-info' : 'btn-info');       
            $clss_disabled = (empty($reg['cant_comprobantes']) ? 'disabled' : '');       
            $descrip_toltip = (empty($reg['cant_comprobantes']) ? 'Vacío' : ($reg['cant_comprobantes']==1 ?  $reg['cant_comprobantes'].' comprobante' : $reg['cant_comprobantes'].' comprobantes'));       
      
            $data[] = [
              "0" => $cont,
              "1" => $reg['estado'] == '1' ? '<button class="btn btn-info btn-sm" onclick="ver_detalle_compras(' . $reg['idcompra_proyecto'] . ')" data-toggle="tooltip" data-original-title="Ver detalle compra"><i class="fa fa-eye"></i></button>' .
                    ' <button class="btn btn-warning btn-sm" onclick="mostrar_compra_insumo(' . $reg['idcompra_proyecto'] . ')" data-toggle="tooltip" data-original-title="Editar compra"><i class="fas fa-pencil-alt"></i></button>' .                  
                    ' <button class="btn btn-danger  btn-sm" onclick="eliminar_compra(' . $reg['idcompra_proyecto'] .', \''.encodeCadenaHtml('<del><b>' . $reg['tipo_comprobante'] .  '</b> '.(empty($reg['serie_comprobante']) ?  "" :  '- '.$reg['serie_comprobante']).'</del> <del>'.$reg['razon_social'].'</del>'). '\')"><i class="fas fa-skull-crossbones"></i> </button>'
                  : '<button class="btn btn-info btn-sm" onclick="ver_detalle_compras(' .  $reg['idcompra_proyecto'] . ')"data-toggle="tooltip" data-original-title="Ver detalle"><i class="fa fa-eye"></i></button>' .
                    ' <button class="btn btn-success btn-sm" onclick="des_anular(' . $reg['idcompra_proyecto'] . ')" data-toggle="tooltip" data-original-title="Recuperar Compra"><i class="fas fa-check"></i></button>',
              "2" => $reg['fecha_compra'],
              "3" => '<span class="text-primary font-weight-bold" >' . $reg['razon_social'] . '</span>',
              "4" =>'<span class="" ><b>' . $reg['tipo_comprobante'] .  '</b> '.(empty($reg['serie_comprobante']) ?  "" :  '- '.$reg['serie_comprobante']).'</span>',
              "5" => empty($reg['estado_detraccion']) ? ($stdo_detraccion = "No") : ($stdo_detraccion = 'Si'),
              "6" => $reg['total'],
              // "7" => $list_segun_estado_detracc,
              // "8" => number_format($reg['total_pago_compras'], 2, '.', ','),
              // "9" => number_format($saldo, 2, '.', ','),
              "7" => '<center> <button class="btn '.$btn_tipo.' btn-sm" onclick="comprobante_compras(' . $vercomprobantes . ', \''.$cont.'\', \''.encodeCadenaHtml($reg['tipo_comprobante'].' '.(empty($reg['serie_comprobante']) ?  "" :  '- '.$reg['serie_comprobante'])).'\', \''.$reg['razon_social'].'\', \''.format_d_m_a($reg['fecha_compra']).'\')" data-toggle="tooltip" data-original-title="'.$descrip_toltip.'"><i class="fas fa-file-invoice fa-lg"></i></button> </center>',
              "8" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.$reg['descripcion'].'</textarea>',
              "9" => '<div class="custom-control custom-checkbox">
                        <input class="custom-control-input custom-control-input-danger custom-control-input-outline check_add_doc " '.$clss_disabled.' type="checkbox" id="check_descarga_'.$reg['idcompra_proyecto'].'" onchange="add_remove_comprobante( '.$reg['idcompra_proyecto'].', \''.$reg['comprobante'].'\', \'' .encodeCadenaHtml('<b>' . $reg['tipo_comprobante'] .  '</b> '.(empty($reg['serie_comprobante']) ?  "" :  '- '.$reg['serie_comprobante'])).'\')">
                        <label for="check_descarga_'.$reg['idcompra_proyecto'].'" class="custom-control-label check_add_doc cursor-pointer"></label> '.
                        '<i class="cargando_check m-r-10px hidden fas fa-spinner fa-pulse"></i>'.
                        (empty($reg['cant_comprobantes']) ? '<button class="btn '.$btn_tipo.' btn-xs '.$clss_disabled.'"  data-toggle="tooltip" data-original-title="'.$descrip_toltip.'" ><i class="fas fa-cloud-download-alt"></i></button>' : '<button class="btn '.$btn_tipo.' btn-xs '.$clss_disabled.' descarga_compra_'.$reg['idcompra_proyecto'].'" onclick="download_no_multimple(\''.$reg['idcompra_proyecto'].'\',\''.$cont .'\', \''.removeSpecialChar($reg['tipo_comprobante'].' '.(empty($reg['serie_comprobante']) ?  "" :  ' ─ '.$reg['serie_comprobante']).' ─ '.$reg['razon_social']).' ─ '. format_d_m_a($reg['fecha_compra']).'\')" data-toggle="tooltip" data-original-title="'.$descrip_toltip.'" ><i class="fas fa-cloud-download-alt"></i></button>'). 
                      '</div>'.$toltip,
            ];
            $cont++;
          }
          $results = [
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "data" => $data,
          ];
          echo json_encode($results, true);
        } else {
          echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
        }
    
      break;
    
      case 'listar_compraxporvee':
        $nube_idproyecto = $_GET["nube_idproyecto"];
        $rspta = $compra_insumos->listar_compraxporvee($nube_idproyecto);
        //Vamos a declarar un array
        $data = []; $cont = 1;
        $c = "info";
        $nombre = "Ver";
        $info = "info";
        $icon = "eye";
        
        if ($rspta['status']) {
          while ($reg = $rspta['data']->fetch_object()) {
            $data[] = [
              "0" => $cont++,
              "1" => '<button class="btn btn-info btn-sm" onclick="listar_facuras_proveedor(' . $reg->idproveedor . ',' . $reg->idproyecto . ')" data-toggle="tooltip" data-original-title="Ver detalle"><i class="fa fa-eye"></i></button>',
              "2" => $reg->razon_social,
              "3" => "<center>$reg->cantidad</center>",
              "4" => $reg->telefono,
              "5" => number_format($reg->total, 2, '.', ','),
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
    
      case 'listar_detalle_compraxporvee':
        
        $rspta = $compra_insumos->listar_detalle_comprax_provee($_GET["idproyecto"], $_GET["idproveedor"]);
        //Vamos a declarar un array
        $data = []; $cont = 1;
        
        if ($rspta['status']) {
          while ($reg = $rspta['data']->fetch_object()) {
            $data[] = [
              "0" => $cont++,
              "1" => '<center><button class="btn btn-info btn-sm" onclick="ver_detalle_compras(' . $reg->idcompra_proyecto . ')" data-toggle="tooltip" data-original-title="Ver detalle">Ver detalle <i class="fa fa-eye"></i></button></center>',
              "2" => $reg->fecha_compra,
              "3" => $reg->tipo_comprobante,
              "4" => $reg->serie_comprobante,
              "5" => number_format($reg->total, 2, '.', ','),
              "6" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.$reg->descripcion.'</textarea>',
              "7" => $reg->estado == '1' ? '<span class="badge bg-success">Aceptado</span>' : '<span class="badge bg-danger">Anulado</span>',
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
           
      
      // :::::::::::::::::::::::::: S E C C I O N   C O M P R O B A N T E  :::::::::::::::::::::::::: 
      case 'tbla_comprobantes_compra':
        $cont_compra = $_GET["num_orden"];
        $id_compra = $_GET["id_compra"];
        $rspta = $compra_insumos->tbla_comprobantes( $id_compra );
        //Vamos a declarar un array
        $data = []; $cont = 1;        
        
        if ($rspta['status']) {
          while ($reg = $rspta['data']->fetch_object()) {
            $data[] = [
              "0" => $cont,
              "1" => '<div class="text-nowrap">'.
              ' <button type="button" class="btn btn-warning btn-sm" onclick="mostrar_editar_comprobante(' . $reg->idfactura_compra_insumo .','.$id_compra.', \''.$reg->comprobante.'\', \''.$cont.'. '.date("d/m/Y h:i:s a", strtotime($reg->updated_at)).'\')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>'.              
              ' <a class="btn btn-info btn-sm " href="../dist/docs/compra_insumo/comprobante_compra/'.$reg->comprobante.'"  download="'.$cont_compra.'·'.$cont.' '.removeSpecialChar((empty($reg->serie_comprobante) ?  " " :  ' ─ '.$reg->serie_comprobante).' ─ '.$reg->razon_social).' ─ '. format_d_m_a($reg->fecha_compra).'" data-toggle="tooltip" data-original-title="Descargar" ><i class="fas fa-cloud-download-alt"></i></a>' .              
              ' <button type="button" class="btn btn-danger btn-sm" onclick="eliminar_comprobante_insumo(' . $reg->idfactura_compra_insumo .', \''.encodeCadenaHtml($cont.'. '.date("d/m/Y h:i:s a", strtotime($reg->updated_at))).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i></button> 
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

      case 'guardaryeditar_comprobante':
        // COMPROBANTE
        if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {

          $doc_comprobante = $_POST["doc_old_1"];
          $flat_comprob = false;

        } else {

          $ext1 = explode(".", $_FILES["doc1"]["name"]);
          $flat_comprob = true;    
          $doc_comprobante = $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);    
          move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/compra_insumo/comprobante_compra/" . $doc_comprobante);
        }        

        if ( empty($idfactura_compra_insumo) ) {
          // agregar un documento
          $rspta = $compra_insumos->agregar_comprobante($id_compra_proyecto, $doc_comprobante);    
          echo json_encode($rspta, true);
        } else {
          //Borramos el comprobante
          if ($flat_comprob == true) {
            $datos_f1 = $compra_insumos->obtener_comprobante($idfactura_compra_insumo);    
            $doc1_ant = $datos_f1['data']->fetch_object()->comprobante;    
            if (!empty($doc1_ant) ) { unlink("../dist/docs/compra_insumo/comprobante_compra/" . $doc1_ant); }
          }
          // editamos un documento existente
          $rspta = $compra_insumos->editar_comprobante($idfactura_compra_insumo, $doc_comprobante);    
          echo json_encode($rspta, true);
        }
    
      break;

      case 'eliminar_comprobante':
        $rspta = $compra_insumos->eliminar_comprobante($_GET["id_tabla"]);    
        echo json_encode($rspta, true);
      break;

      case 'desactivar_comprobante':
        $rspta = $compra_insumos->desactivar_comprobante($_GET["id_tabla"]);    
        echo json_encode($rspta, true);
      break;

      case 'ver_comprobante_compra':

        $rspta = $compra_insumos->comprobantes_compra($_POST['id_compra']);
        //Codificar el resultado utilizando json
        echo json_encode($rspta, true);
    
      break; 

      // :::::::::::::::::::::::::: S E C C I O N   P A G O  ::::::::::::::::::::::::::     

         
    
      // ::::::::::::::::::::::::::::::::::::::::: S I N C R O N I Z A R  :::::::::::::::::::::::::::::::::::::::::
      case 'sincronizar_comprobante':

        $rspta = $compra_insumos->sincronizar_comprobante();
        //Codificar el resultado utilizando json
        echo json_encode($rspta, true);

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
