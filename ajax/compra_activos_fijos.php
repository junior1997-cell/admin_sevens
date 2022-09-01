<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {
    
    if ($_SESSION['compra_activo_fijo'] == 1) {

      require_once "../modelos/Compra_activos_fijos.php";
      require_once "../modelos/AllProveedor.php";
      require_once "../modelos/Activos_fijos.php";   
      require_once "../modelos/Compra_insumos.php";
      
      $compra_activos_fijos = new Compra_activos_fijos();
      $proveedor = new AllProveedor();
      $activos_fijos = new Activos_fijos();
      $compra = new Compra_insumos();

      date_default_timezone_set('America/Lima');
      $date_now = date("d-m-Y h.i.s A");

      // :::::::::::::::::::::::::::::::::::: D A T O S  C O M P R A   A C T I V O S ::::::::::::::::::::::::::::::::::::::

      $idproyecto         = isset($_POST["idproyecto"]) ? limpiarCadena($_POST["idproyecto"]) : "";
      $idcompra_af_general= isset($_POST["idcompra_af_general"]) ? limpiarCadena($_POST["idcompra_af_general"]) : "";
      $idgrupo             = isset($_POST["idtipo_tierra_concreto"]) ? limpiarCadena($_POST["idtipo_tierra_concreto"]) : "";
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

      // :::::::::::::::::::::::::::::::::::: D A T O S   C O M P R O B A N T E   C O M P R A   ::::::::::::::::::::::::::::::::::::::
      $idcompra           = isset($_POST["idcompra"]) ? limpiarCadena($_POST["idcompra"]) : "";
      $doc1               = isset($_POST["doc1"]) ? $_POST["doc1"] : "";
      $doc_old_1          = isset($_POST["doc_old_1"]) ? limpiarCadena($_POST["doc_old_1"]) : "";

      // :::::::::::::::::::::::::::::::::::: D A T O S  P A G O  C O M P R A ::::::::::::::::::::::::::::::::::::::

      $beneficiario_pago  = isset($_POST["beneficiario_pago"]) ? limpiarCadena($_POST["beneficiario_pago"]) : "";
      $forma_pago         = isset($_POST["forma_pago"]) ? limpiarCadena($_POST["forma_pago"]) : "";
      $tipo_pago          = isset($_POST["tipo_pago"]) ? limpiarCadena($_POST["tipo_pago"]) : "";
      $cuenta_destino_pago= isset($_POST["cuenta_destino_pago"]) ? limpiarCadena($_POST["cuenta_destino_pago"]) : "";
      $banco_pago         = isset($_POST["banco_pago"]) ? limpiarCadena($_POST["banco_pago"]) : "";
      $titular_cuenta_pago= isset($_POST["titular_cuenta_pago"]) ? limpiarCadena($_POST["titular_cuenta_pago"]) : "";
      $fecha_pago         = isset($_POST["fecha_pago"]) ? limpiarCadena($_POST["fecha_pago"]) : "";
      $monto_pago         = isset($_POST["monto_pago"]) ? limpiarCadena($_POST["monto_pago"]) : "";
      $numero_op_pago     = isset($_POST["numero_op_pago"]) ? limpiarCadena($_POST["numero_op_pago"]) : "";
      $descripcion_pago   = isset($_POST["descripcion_pago"]) ? limpiarCadena($_POST["descripcion_pago"]) : "";
      $idcompra_af_general_p = isset($_POST["idcompra_af_general_p"]) ? limpiarCadena($_POST["idcompra_af_general_p"]) : "";
      $idpago_af_general  = isset($_POST["idpago_af_general"]) ? limpiarCadena($_POST["idpago_af_general"]) : "";
      $idproveedor_pago   = isset($_POST["idproveedor_pago"]) ? limpiarCadena($_POST["idproveedor_pago"]) : "";
      $imagen1            = isset($_POST["foto1"]) ? $_POST["foto1"] : "";

      // :::::::::::::::::::::::::::::::::::: D A T O S   M A T E R I A L E S ::::::::::::::::::::::::::::::::::::::

      $idproducto_p     = isset($_POST["idproducto_p"]) ? limpiarCadena($_POST["idproducto_p"]) : "";
      $unidad_medida_p  = isset($_POST["unidad_medida_p"]) ? limpiarCadena($_POST["unidad_medida_p"]) : "";
      $color_p          = isset($_POST["color_p"]) ? limpiarCadena($_POST["color_p"]) : "";
      $categoria_insumos_af_p = isset($_POST["categoria_insumos_af_p"]) ? limpiarCadena($_POST["categoria_insumos_af_p"]) : "";
      $nombre_p         = isset($_POST["nombre_p"]) ? encodeCadenaHtml($_POST["nombre_p"]) : "";
      $modelo_p         = isset($_POST["modelo_p"]) ? encodeCadenaHtml($_POST["modelo_p"]) : "";
      $serie_p          = isset($_POST["serie_p"]) ? encodeCadenaHtml($_POST["serie_p"]) : "";
      $marca_p          = isset($_POST["marca_p"]) ? encodeCadenaHtml($_POST["marca_p"]) : "";
      $estado_igv_p     = isset($_POST["estado_igv_p"]) ? limpiarCadena($_POST["estado_igv_p"]) : "";
      $precio_unitario_p= isset($_POST["precio_unitario_p"]) ? limpiarCadena($_POST["precio_unitario_p"]) : "";
      $precio_sin_igv_p = isset($_POST["precio_sin_igv_p"]) ? limpiarCadena($_POST["precio_sin_igv_p"]) : "";
      $precio_igv_p     = isset($_POST["precio_igv_p"]) ? limpiarCadena($_POST["precio_igv_p"]) : "";
      $precio_total_p   = isset($_POST["precio_total_p"]) ? limpiarCadena($_POST["precio_total_p"]) : "";
      $descripcion_p    = isset($_POST["descripcion_p"]) ? encodeCadenaHtml($_POST["descripcion_p"]) : "";
      $img_pefil_p      = isset($_POST["foto2"]) ? limpiarCadena($_POST["foto2"]) : "";
      $ficha_tecnica_p  = isset($_POST["doc2"]) ? limpiarCadena($_POST["doc2"]) : "";

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

        // :::::::::::::::::::::::::: S E C C I O N   C O M P R A   D E   A C T I V O S  ::::::::::::::::::::::::::

        case 'guardaryeditarcompraactivo':
      
          if (empty($idcompra_af_general)) {
      
            $rspta = $compra_activos_fijos->insertar( $idproveedor, $fecha_compra,  $tipo_comprobante, $serie_comprobante, $val_igv, $descripcion, 
            $glosa, $total_venta, $subtotal_compra, $igv_compra, $estado_detraccion, $_POST["idproducto"], $_POST["unidad_medida"], 
            $_POST["nombre_color"], $_POST["cantidad"], $_POST["precio_sin_igv"], $_POST["precio_igv"],  $_POST["precio_con_igv"], $_POST["descuento"], 
            $tipo_gravada, $_POST["ficha_tecnica_producto"] );
            
            echo json_encode( $rspta, true);
      
          } else {
      
            $rspta = $compra_activos_fijos->editar( $idcompra_af_general, $idproveedor, $fecha_compra,  $tipo_comprobante, $serie_comprobante, 
            $val_igv, $descripcion, $glosa, $total_venta, $subtotal_compra, $igv_compra, $estado_detraccion, $_POST["idproducto"], $_POST["unidad_medida"], 
            $_POST["nombre_color"], $_POST["cantidad"], $_POST["precio_sin_igv"], $_POST["precio_igv"],  $_POST["precio_con_igv"], $_POST["descuento"], 
            $tipo_gravada, $_POST["ficha_tecnica_producto"] );
      
            echo json_encode( $rspta, true);
          }
      
        break;
      
        case 'guardar_y_editar_comprobante_activo_fijo':     
      
          // imgen de perfil
          if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {

            $doc1 = $_POST["doc_old_1"];

            $flat_comprob = false;

          } else {

            $ext1 = explode(".", $_FILES["doc1"]["name"]);

            $flat_comprob = true;
  
            $doc1 = $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);
  
            move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/compra_activo_fijo/comprobante_compra/" . $doc1);
          }
          
          //Borramos el comprobante
          if ($flat_comprob == true) {

            $datos_f1 = $compra_activos_fijos->obtener_comprobante_af_g($idcompra);
  
            $doc1_ant = $datos_f1['data']['comprobante'];
  
            if ($doc1_ant != "") {  unlink("../dist/docs/compra_activo_fijo/comprobante_compra/" . $doc1_ant); }
          }
  
          // editamos un documento existente
          $rspta = $compra_activos_fijos->editar_comprobante_af_g($idcompra, $doc1);
  
          echo json_encode( $rspta, true);

        break;   
        
        case 'guardar_y_editar_comprobante_insumo':
          // imgen de perfil
          if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {
  
            $doc_comprobante = $_POST["doc_old_1"];
  
            $flat_comprob = false;
  
          } else {
  
            $ext1 = explode(".", $_FILES["doc1"]["name"]);
  
            $flat_comprob = true;
      
            $doc_comprobante = $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);
      
            move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/compra_insumo/comprobante_compra/" . $doc_comprobante);
          }
  
          //Borramos el comprobante
          if ($flat_comprob == true) {
  
            $datos_f1 = $compra->obtener_comprobante($idcompra);
      
            $doc1_ant = $datos_f1['data']->fetch_object()->comprobante;
      
            if ($doc1_ant != "") {
              unlink("../dist/docs/compra_insumo/comprobante_compra/" . $doc1_ant);
            }
          }
      
          // editamos un documento existente
          $rspta = $compra->editar_comprobante($idcompra, $doc_comprobante);
      
          echo json_encode( $rspta, true);
      
        break;
      
        case 'anular_compra':

          $rspta = $compra_activos_fijos->anular_compra($_GET["id_tabla"]);
      
          echo json_encode( $rspta, true);

        break;
      
        case 'eliminar_compra':

          $rspta = $compra_activos_fijos->eliminar_compra($_GET["id_tabla"]);
      
          echo json_encode( $rspta, true);

        break;

        case 'ver_compra_editar':
          
          $rspta = $compra_activos_fijos->mostrar_compra_para_editar($idcompra_af_general);
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true);
      
        break;
      
        //listar facturas_compra activos
        case 'tbla_compra_activos_fijos':
          $rspta = $compra_activos_fijos->tbla_compra_activos_fijos();
          //Vamos a declarar un array
          $data = [];
  
          $c = "";
          $cc = "";
          $nombre = "";
          $info = "";
          $icon = "";
          $num_comprob = "";
          $cont = 1;
          
          $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

          if ($rspta['status'] == true) {
            foreach ($rspta['data'] as $key => $reg) {

              $saldo = floatval($reg['total']) - floatval($reg['deposito']);  
  
              $btn_tipo = (empty( $reg['imagen_comprobante'])) ? 'btn-outline-info' : 'btn-info';   
              $tooltip_comprobante = (empty( $reg['imagen_comprobante'])) ? 'data-toggle="tooltip" data-original-title="Vacío"' : 'data-toggle="tooltip" data-original-title="Comprobante"';   
    
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
                }
              }            
  
              $data[] = [
                "0" => $cont++,
                "1" => empty($reg['idproyecto']) ? ($reg['estado'] == '1'  ? '<button class="btn btn-info btn-sm" onclick="ver_detalle_compras_activo_fijo(' . $reg['idtabla'] . ')" data-toggle="tooltip" data-original-title="Ver detalle compra"><i class="fa fa-eye"></i></button>' .
                      ' <button class="btn btn-sm btn-warning" onclick="mostrar_compra_general(' . $reg['idtabla'] . ')" data-toggle="tooltip" data-original-title="Editar compra"><i class="fas fa-pencil-alt"></i></button>' .
                      ' <button class="btn btn-sm btn-danger" onclick="eliminar_compra(' . $reg['idtabla'] .', \''.encodeCadenaHtml($reg['razon_social']).' - '.date("d/m/Y", strtotime($reg['fecha_compra'])).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i> </button>'
                    : ' <button class="btn btn-sm btn-info" onclick="ver_detalle_compras_activo_fijo(' . $reg['idtabla'] . ')"data-toggle="tooltip" data-original-title="Ver detalle"><i class="fa fa-eye"></i></button>' .
                      ' <button class="btn btn-sm btn-success" onclick="des_anular(' . $reg['idtabla'] . ')" data-toggle="tooltip" data-original-title="Recuperar Compra"><i class="fas fa-check"></i></button>')
                  : ($reg['estado'] == '1' ? '<button class="btn btn-info btn-sm " onclick="ver_detalle_compras_insumo(' . $reg['idtabla'] . ')" data-toggle="tooltip" data-original-title="Ver detalle compra"><i class="fa fa-eye"></i></button>' .
                      ' <button class="btn btn-sm btn-warning" disabled onclick="mostrar_compra_proyecto(' . $reg['idtabla'] . ')" data-toggle="tooltip" data-original-title="Editar compra"><i class="fas fa-pencil-alt"></i></button>' .
                      ' <button class="btn btn-sm btn-danger" disabled onclick="eliminar_compra(' . $reg['idtabla'] . ')"><i class="fas fa-skull-crossbones"></i> </button>'
                    : ' <button class="btn btn-sm btn-info" disabled onclick="ver_detalle_compras_insumo(' . $reg['idtabla'] . ')"data-toggle="tooltip" data-original-title="Ver detalle"><i class="fa fa-eye"></i></button>' .
                      ' <button class="btn btn-sm btn-success " disabled onclick="des_anular_af_p(' . $reg['idtabla'] . ')" data-toggle="tooltip" data-original-title="Recuperar Compra"><i class="fas fa-check"></i></button>'),
                "2" => '<textarea class="form-control textarea_datatable" readonly cols="30" rows="1">' . $reg['descripcion'] . '</textarea>',      
                "3" => $reg['fecha_compra'],
                "4" => '<div class="user-block">'. 
                  '<span class="description ml-0" ><b>' . (empty($reg['idproyecto']) ? 'General' : $reg['codigo_proyecto']) . '</b></span>'.
                  '<span class="username ml-0" ><p class="text-primary m-b-02rem" >' . $reg['razon_social'] . '</p></span>'.                
                '</div>',
                "5" => '<span class="" ><b>' . $reg['tipo_comprobante'] .  '</b> '.(empty($reg['serie_comprobante']) ?  "" :  '- '.$reg['serie_comprobante']).'</span>',
                "6" => number_format($reg['total'], 2, '.', ','),
                "7" => empty($reg['idproyecto']) ? '<div class="text-center text-nowrap">'.
                  '<button class="btn btn-' . $c . ' btn-xs m-t-2px" onclick="tbla_pagos_activo_fijo(' . $reg['idtabla'] . ', ' . $reg['total'] . ', ' . floatval($reg['deposito']) .', \''.encodeCadenaHtml($reg['razon_social']) .'\')"> <i class="fas fa-' . $icon . ' nav-icon"></i> ' . $nombre . '</button>' . 
                  ' <button style="font-size: 14px;" class="btn btn-' . $cc . ' btn-sm">' . number_format(floatval($reg['deposito']), 2, '.', ',') . '</button>'.
                '</div>' : 
                '<div class="text-center text-nowrap">'.
                  '<button class="btn btn-' . $c . ' btn-xs m-t-2px" disabled onclick="tbla_pagos_insumos(' . $reg['idtabla'] . ',' . $reg['total'] . ',' . floatval($reg['deposito']) . ')"><i class="fas fa-' . $icon . ' nav-icon"></i> ' . $nombre . '</button>' . 
                  ' <button style="font-size: 14px;" class="btn btn-' . $cc . ' btn-sm" disabled>' . number_format(floatval($reg['deposito']), 2, '.', ',') . '</button>'.
                '</div>',
                "8" => number_format($saldo, 2, '.', ','),
                "9" => (empty($reg['idproyecto']) ? '<center><button class="btn '.$btn_tipo.' btn-sm" onclick="comprobante_compra_activo_fijo(' . $reg['idtabla'] . ', \'' . $reg['imagen_comprobante'] . '\')" '.$tooltip_comprobante.' ><i class="fas fa-file-invoice fa-lg"></i></button></center>' : 
                '<center><button class="btn '.$btn_tipo.' btn-sm" onclick="comprobante_compra_insumo(' . $reg['idtabla'] . ', \'' . $reg['imagen_comprobante'] . '\')"  '.$tooltip_comprobante.' ><i class="fas fa-file-invoice fa-lg"></i></button></center>').$toltip,
                "10" => (empty($reg['idproyecto']) ? 'General' : $reg['codigo_proyecto']),
                "11" => $reg['razon_social'],
                "12" => $reg['tipo_comprobante'],
                "13" => $reg['serie_comprobante'],
                "14" => number_format($reg['subtotal'], 2, '.', ','),
                "15" => number_format($reg['igv'], 2, '.', ','),
                "16" => number_format(floatval($reg['deposito']), 2, '.', ','),
                "17" => number_format($saldo, 2, '.', ','),
                "18" => $reg['glosa'],
              ];
              
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
            
        case 'ver_detalle_compras_activo_fijo':
          
          //Recibimos el idingreso
          $idcompra_af_general = $_GET['idcompra_af_general'];

          $rspta = $compra_activos_fijos->ver_compra_general($idcompra_af_general);
          $rspta2 = $compra_activos_fijos->ver_detalle_compra_general($idcompra_af_general);         

          $subtotal = 0;  $ficha = '';

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
            <!-- Glosa -->
            <div class="col-lg-3">
              <div class="form-group">
                <label class="font-size-15px" for="fecha_compra">Glosa </label>
                <span class="form-control form-control-sm">'. ($rspta['data']['glosa']=='CONBUSTIBLE' ? '<i class="fas fa-gas-pump"></i> ' : '<i class="fas fa-hammer"></i> ' ) . $rspta['data']['glosa'].' </span>
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

            empty($reg->ficha_tecnica_old) ? ($ficha =  (empty($reg->ficha_tecnica_new) ? '<i class="far fa-file-pdf fa-lg text-gray-50" data-toggle="tooltip" data-original-title="Vacío"></i>' : '<a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg->ficha_tecnica_new . '" data-toggle="tooltip" data-original-title="Ver Ficha Técnica"><i class="far fa-file-pdf fa-lg text-primary"></i></a>' )  ) : ($ficha = '<a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg->ficha_tecnica_old . '" data-toggle="tooltip" data-original-title="Ver Ficha Técnica"><i class="far fa-file-pdf fa-lg text-primary"></i></a>');
            $img_product = '../dist/docs/material/img_perfil/'. (empty($reg->imagen) ? 'producto-sin-foto.svg' : $reg->imagen );

            $tbody .= '<tr class="filas">
              <td class="text-center p-6px">' . $cont++ . '</td>
              <td class="text-center p-6px">' . $ficha . '</td>
              <td class="text-left p-6px">
                <div class="user-block text-nowrap">
                  <img class="profile-user-img img-responsive img-circle cursor-pointer" src="'.$img_product.'" onclick="ver_img_material(\''.$img_product.'\', \'' . encodeCadenaHtml( $reg->nombre) . '\', null)" alt="user image" data-toggle="tooltip" data-original-title="Ver img" onerror="this.src=\'../dist/svg/404-v2.svg\';" >'.
                  '<span class="username"> <p class="mb-0 ">' . $reg->nombre . '</p></span>
                  <span class="description"> <b>Color: </b>' . $reg->color . '</span>
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
              <thead class="bg-color-127ab6ba">
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
                  <th class="text-center p-10px" data-toggle="tooltip" data-original-title="Ficha Técnica">F.T.</th>
                  <th class="p-10px">Material</th>
                  <th class="p-10px" data-toggle="tooltip" data-original-title="Unidad de Medida">U.M.</th>
                  <th class="p-10px" data-toggle="tooltip" data-original-title="Cantidad">Cant.</th>
                  <th class="p-10px" data-toggle="tooltip" data-original-title="Valor Unitario">V/U</th>
                  <th class="p-10px">IGV</th>
                  <th class="p-10px" data-toggle="tooltip" data-original-title="Precio Unitario">P/U</th>
                  <th class="p-10px" data-toggle="tooltip" data-original-title="Descuento">Desct.</th>
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

        // :::::::::::::::::::::::::: S E C C I O N   C O M P R A S   X   P R O V E E D O R  ::::::::::::::::::::::::::
        case 'tbla_compra_x_porveedor':
          $rspta = $compra_activos_fijos->tbla_compra_x_porveedor();
          //Vamos a declarar un array
          $data = [];
          $c = "info";
          $nombre = "Ver";
          $info = "info";
          $icon = "eye";
          $cont = 1;
  
          if ($rspta['status']) {

            foreach ($rspta['data'] as $key => $value) {
              $data[] = [
                "0" => $cont++,
                "1" => '<button class="btn btn-info btn-sm" onclick="listar_facuras_x_proveedor(' . $value['idproveedor'] . ', \'' .encodeCadenaHtml($value['razon_social']). '\')" data-toggle="tooltip" data-original-title="Ver detalle"><i class="fa fa-eye"></i></button>',
                "2" =>'<div class="user-block">'.
                  '<span class="username ml-0" ><p class="text-primary m-b-02rem">' .  $value['razon_social'] . '</p></span>'.
                  '<span class="description ml-0"><b>' . $value['tipo_documento'] . ' </b>' .  $value['ruc'] .'</span>'.
                '</div>',
                "3" => $value['cont'],
                "4" => number_format($value['total'], 2, '.', ','),
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
      
        case 'tbla_detalle_compra_x_porveedor':

          $rspta = $compra_activos_fijos->tbla_detalle_compra_x_porveedor($_GET["idproveedor"]);
          //Vamos a declarar un array
          $data = []; $cont = 1;

          if ($rspta['status']) {
            foreach ($rspta['data'] as $key => $value) {
              $data[] = [
                "0" => $cont++,
                "1" => empty($value['idproyecto']) ? '<center><button class="btn btn-info btn-sm" onclick="ver_detalle_compras_activo_fijo(' . $value['idtabla'] . ')" data-toggle="tooltip" data-original-title="Ver detalle">Ver detalle <i class="fa fa-eye"></i></button></center>'
                  : '<center><button class="btn btn-info btn-sm" onclick="ver_detalle_compras_insumo(' . $value['idtabla'] . ')" data-toggle="tooltip" data-original-title="Ver detalle">Ver detalle <i class="fa fa-eye"></i></button></center>',
                "2" => $value['fecha_compra'],
                "3" => '<div class="user-block">'.
                  '<span class="username ml-0" ><p class="text-primary m-b-02rem"  >' . (empty($value['idproyecto']) ? 'General' : $value['codigo_proyecto']) . '</p></span>                                   
                </div>',
                "4" => '<span class="" ><b>' . $value['tipo_comprobante'] .  '</b> '.(empty($value['serie_comprobante']) ?  "" :  '- '.$value['serie_comprobante']).'</span>',
                "5" => number_format($value['total'], 2, '.', ','),
                "6" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.$value['descripcion'].'</textarea>',
                "7" => $value['estado'] == '1' ? '<span class="badge bg-success">Aceptado</span>' : '<span class="badge bg-danger">Anulado</span>',
              ];
            }
            
            $results = [
              "sEcho" => 1, //Información para el datatables
              "iTotalRecords" => count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
              "aaData" => $data,
            ];
  
            echo json_encode($results);
          } else {
            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          }
      
        break;

        // :::::::::::::::::::::::::: S E C C I O N   P R O V E E D O R  ::::::::::::::::::::::::::
        case 'guardar_proveedor':
      
          if (empty($idproveedor_prov)){
      
            $rspta=$proveedor->insertar($nombre_prov, $tipo_documento_prov, $num_documento_prov, $direccion_prov, $telefono_prov,
            $c_bancaria_prov, $cci_prov, $c_detracciones_prov, $banco_prov, $titular_cuenta_prov);
            
            echo json_encode( $rspta, true);
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
            $rspta = $activos_fijos->insertar( $unidad_medida_p, $color_p, $categoria_insumos_af_p, $idgrupo, $nombre_p, $modelo_p,
            $serie_p, $marca_p, $estado_igv_p, $precio_unitario_p, $precio_igv_p, $precio_sin_igv_p, $precio_total_p, $ficha_tecnica_p,
            $descripcion_p, $img_pefil_p );
      
            echo json_encode( $rspta, true);

          } else {
      
            // validamos si existe LA IMG para eliminarlo
            if ($flat_img1 == true) {

              $datos_f1 = $activos_fijos->obtenerImg($idproducto_p);
      
              $img1_ant = $datos_f1['data']->fetch_object()->imagen;
      
              if ($img1_ant != "") {  unlink("../dist/docs/material/img_perfil/" . $img1_ant); }
            }
      
            $rspta = $activos_fijos->editar( $idproducto_p, $unidad_medida_p, $color_p, $categoria_insumos_af_p, $idgrupo, $nombre_p, $modelo_p,
            $serie_p, $marca_p, $estado_igv_p, $precio_unitario_p, $precio_igv_p, $precio_sin_igv_p, $precio_total_p, $ficha_tecnica_p,
            $descripcion_p, $img_pefil_p );
              
            echo json_encode( $rspta, true);
          }
        break;        

        case 'mostrar_activo_fijo':

          $rspta = $activos_fijos->mostrar($_POST["idproducto"]);
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true);

        break;
      
        // :::::::::::::::::::::::::: S E C C I O N   P A G O  :::::::::::::::::::::::::: 
      
        case 'most_datos_prov_pago':

          $rspta = $compra_activos_fijos->most_datos_prov_pago($_POST["idcompra_af_general"]);
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true);

        break;
      
        case 'guardaryeditar_pago':
          // imgen de perfil
          if (!file_exists($_FILES['foto1']['tmp_name']) || !is_uploaded_file($_FILES['foto1']['tmp_name'])) {
            $imagen1 = $_POST["foto1_actual"];
            $flat_img1 = false;
          } else {
            $ext1 = explode(".", $_FILES["foto1"]["name"]);
            $flat_img1 = true;  
            $imagen1 = $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);  
            move_uploaded_file($_FILES["foto1"]["tmp_name"], "../dist/docs/compra_activo_fijo/comprobante_pago/" . $imagen1);
          }
  
          if (empty($idpago_af_general)) {
            $rspta = $compra_activos_fijos->insertar_pago( $idcompra_af_general_p, $beneficiario_pago, $forma_pago, $tipo_pago, $cuenta_destino_pago,
            $banco_pago, $titular_cuenta_pago, $fecha_pago, $monto_pago, $numero_op_pago, $descripcion_pago, $imagen1 );
            echo json_encode( $rspta, true);
          } else {
            // validamos si existe LA IMG para eliminarlo
            if ($flat_img1 == true) {
              $datos_f1 = $compra_activos_fijos->obtenerImg($idpago_af_general);  
              $img1_ant = $datos_f1['data']->fetch_object()->imagen;  
              if ($img1_ant != "") { unlink("../dist/docs/compra_activo_fijo/comprobante_pago/" . $img1_ant); }
            }
  
            $rspta = $compra_activos_fijos->editar_pago( $idpago_af_general, $idcompra_af_general_p, $beneficiario_pago, $forma_pago, $tipo_pago,
            $cuenta_destino_pago, $banco_pago, $titular_cuenta_pago, $fecha_pago, $monto_pago, $numero_op_pago, $descripcion_pago, $imagen1 );
  
            echo json_encode( $rspta, true);
          }
        break;
      
        case 'desactivar_pagos':

          $rspta = $compra_activos_fijos->desactivar_pagos($_GET["id_tabla"]);

          echo json_encode( $rspta, true);

        break;
      
        case 'eliminar_pagos':

          $rspta = $compra_activos_fijos->eliminar_pagos($_GET["id_tabla"]);

          echo json_encode( $rspta, true);

        break;
      
        case 'tbla_pagos_activo_fijo':
          $rspta = $compra_activos_fijos->tbla_pagos_activo_fijo($_GET["idcompra_af_general"]);
          //Vamos a declarar un array
          $data = [];
          $cont = 1;
          $suma = 0;
          $imagen = '';
  
          while ($reg = $rspta['data']->fetch_object()) {
            $suma = $suma + $reg->monto;
            
            if (strlen($reg->titular_cuenta) >= 20) {
              $titular_cuenta = substr($reg->titular_cuenta, 0, 20) . '...';
            } else {
              $titular_cuenta = $reg->titular_cuenta;
            }
            empty($reg->imagen) ? ($imagen = '<center><i class="far fa-file fa-2x text-primary" data-toggle="tooltip" data-original-title="Vacío"></i></center>') : ($imagen = '<center><i class="fas fa-file-invoice-dollar fa-2x cursor-pointer text-primary" onclick="ver_modal_vaucher(\'' .  $reg->imagen . '\', \''.$reg->beneficiario.'\')" data-toggle="tooltip" data-original-title="Comprobante"></i></center>');
            
            $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

            $data[] = [
              "0" => $cont++,
              "1" => $reg->estado ? '<button class="btn btn-warning btn-sm" onclick="mostrar_pagos(' . $reg->idpago_af_general . ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' .                  
                  ' <button class="btn btn-danger  btn-sm" onclick="eliminar_pagos(' . $reg->idpago_af_general .', \''.encodeCadenaHtml($reg->forma_pago.' - '.$reg->beneficiario).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i> </button>'
                : '<button class="btn btn-warning btn-sm" onclick="mostrar_pagos(' . $reg->idpago_af_general . ')"><i class="fa fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-primary btn-sm" onclick="activar_pagos(' . $reg->idpago_af_general . ')"><i class="fa fa-check"></i></button>',
              "2" => $reg->forma_pago,
              "3" => '<div class="user-block">'.
                '<span class="username ml-0 text-left text-primary">' . $reg->beneficiario . '</span>'.
                '<span class="description ml-0 text-left" data-toggle="tooltip" data-original-title="' .  $reg->titular_cuenta . '"><b>titular: </b>' . $titular_cuenta . '</span>'.
              '</div>',
              "4" => '<div class="user-block">'.
                '<span class="username ml-0 text-left">' . $reg->banco . '</span>'.
                '<span class="description ml-0 text-left" data-toggle="tooltip" data-original-title="' . $reg->cuenta_destino . '"><b>Cta: </b>' . $reg->cuenta_destino . '</span>'.
              '</div>',
              "5" => $reg->fecha_pago,
              "6" => '<textarea class="form-control textarea_datatable" readonly cols="30" rows="1">' . $reg->descripcion . '</textarea>',
              "7" => 'S/ ' . number_format($reg->monto, 2, '.', ','),
              "8" => $imagen . $toltip,
              "9" => $reg->tipo_pago,
              "10" => $reg->beneficiario,
              "11" => $reg->titular_cuenta,
              "12" => $reg->banco,
              "13" => $reg->cuenta_destino,

            ];
          }
          //$suma=array_sum($rspta->fetch_object()->monto);
          $results = [
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
            "data" => $data,
            "suma" => $suma,
          ];

          echo json_encode($results, true);

        break;
      
        case 'suma_total_pagos':

          $idcompra_af_general = $_POST["idcompra_af_general"];

          $rspta = $compra_activos_fijos->suma_total_pagos($idcompra_af_general);
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true);

        break;
      
        case 'mostrar_pagos':

          $rspta = $compra_activos_fijos->mostrar_pagos($idpago_af_general);
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true);

        break;
      
        // :::::::::::::::::::::::::: C O M P R A S   D E  I N S U M O S ::::::::::::::::::::::::::
            
        case 'ver_detalle_compras_insumo':
          
          $rspta = $compra->ver_compra($_GET['id_compra']);
          $rspta2 = $compra->ver_detalle_compra($_GET['id_compra']);

          $subtotal = 0;  $ficha = ''; 

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
            <!-- Glosa -->
            <div class="col-lg-3">
              <div class="form-group">
                <label class="font-size-15px" for="fecha_compra">Glosa </label>
                <span class="form-control form-control-sm">'. ($rspta['data']['glosa']=='CONBUSTIBLE' ? '<i class="fas fa-gas-pump"></i> ' : '<i class="fas fa-hammer"></i> ' ) . $rspta['data']['glosa'].' </span>
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
            empty($reg->ficha_tecnica_old) ? ($ficha =  (empty($reg->ficha_tecnica_new) ? '<i class="far fa-file-pdf fa-lg text-gray-50" data-toggle="tooltip" data-original-title="Vacío"></i>' : '<a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg->ficha_tecnica_new . '" data-toggle="tooltip" data-original-title="Ver Ficha Técnica"><i class="far fa-file-pdf fa-lg text-primary"></i></a>' )  ) : ($ficha = '<a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg->ficha_tecnica_old . '" data-toggle="tooltip" data-original-title="Ver Ficha Técnica"><i class="far fa-file-pdf fa-lg text-primary"></i></a>');

            $img_product = '../dist/docs/material/img_perfil/'. (empty($reg->imagen) ? 'producto-sin-foto.svg' : $reg->imagen );

            $tbody .= '<tr class="filas">
              <td class="text-center p-6px">' . $cont++ . '</td>              
              <td class="text-center p-6px">' . $ficha . '</td>
              <td class="text-left p-6px">
                <div class="user-block text-nowrap">
                  <img class="profile-user-img img-responsive img-circle cursor-pointer" src="'.$img_product.'" onclick="ver_img_material(\''.$img_product.'\', \'' . encodeCadenaHtml( $reg->nombre) . '\', null)" alt="user image" data-toggle="tooltip" data-original-title="Ver img" onerror="this.src=\'../dist/svg/404-v2.svg\';" >'.
                  '<span class="username"> <p class="mb-0 ">' . $reg->nombre . '</p></span>
                  <span class="description"> <b>Color: </b>' . $reg->color . '</span>
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
                <tr class="text-center "> 
                  <th class="text-center p-10px" >#</th>
                  <th class="text-center p-10px" data-toggle="tooltip" data-original-title="Ficha Técnica">F.T.</th>
                  <th class="p-10px">Material</th>
                  <th class="p-10px" data-toggle="tooltip" data-original-title="Unidad de Medida">U.M.</th>
                  <th class="p-10px" data-toggle="tooltip" data-original-title="Cantidad">Cant.</th>
                  <th class="p-10px" data-toggle="tooltip" data-original-title="Valor Unitario">V/U</th>
                  <th class="p-10px">IGV</th>
                  <th class="p-10px" data-toggle="tooltip" data-original-title="Precio Unitario">P/U</th>
                  <th class="p-10px" data-toggle="tooltip" data-original-title="Descuento">Desct.</th>
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
