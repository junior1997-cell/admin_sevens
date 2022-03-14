<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {

    header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.

  } else {
    
    if ($_SESSION['activo_fijo_general'] == 1) {

      require_once "../modelos/All_activos_fijos.php";
      require_once "../modelos/AllProveedor.php";
      require_once "../modelos/Activos_fijos.php";   
      require_once "../modelos/Compra.php";
      
      $all_activos_fijos = new All_activos_fijos();
      $proveedor = new Proveedor();
      $activos_fijos = new Activos_fijos();
      $compra = new Compra();

      // :::::::::::::::::::::::::::::::::::: D A T O S  C O M P R A   A C T I V O S ::::::::::::::::::::::::::::::::::::::

      $idproyecto         = isset($_POST["idproyecto"]) ? limpiarCadena($_POST["idproyecto"]) : "";
      $idcompra_af_general= isset($_POST["idcompra_af_general"]) ? limpiarCadena($_POST["idcompra_af_general"]) : "";
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
      $img_pefil_p      = isset($_POST["fotop2"]) ? limpiarCadena($_POST["fotop2"]) : "";
      $ficha_tecnica_p  = isset($_POST["doct2"]) ? limpiarCadena($_POST["doct2"]) : "";

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
      
            $rspta = $all_activos_fijos->insertar( $idproveedor, $fecha_compra,  $tipo_comprobante, $serie_comprobante, $val_igv, $descripcion, 
            $glosa, $total_venta, $subtotal_compra, $igv_compra, $estado_detraccion, $_POST["idproducto"], $_POST["unidad_medida"], 
            $_POST["nombre_color"], $_POST["cantidad"], $_POST["precio_sin_igv"], $_POST["precio_igv"],  $_POST["precio_con_igv"], $_POST["descuento"], 
            $tipo_gravada, $_POST["ficha_tecnica_producto"] );
            
            echo $rspta ? "ok" : "No se pudieron registrar todos los datos de la compra";
      
          } else {
      
            $rspta = $all_activos_fijos->editar( $idcompra_af_general, $idproveedor, $fecha_compra,  $tipo_comprobante, $serie_comprobante, 
            $val_igv, $descripcion, $glosa, $total_venta, $subtotal_compra, $igv_compra, $estado_detraccion, $_POST["idproducto"], $_POST["unidad_medida"], 
            $_POST["nombre_color"], $_POST["cantidad"], $_POST["precio_sin_igv"], $_POST["precio_igv"],  $_POST["precio_con_igv"], $_POST["descuento"], 
            $tipo_gravada, $_POST["ficha_tecnica_producto"] );
      
            echo $rspta ? "ok" : "Compra no se pudo actualizar";
          }
      
        break;
      
        case 'guardaryeditar_comprobante':
          //comprobante
          $idcompra_af_g_o_p = isset($_POST["idcompra_af_g_o_p"]) ? limpiarCadena($_POST["idcompra_af_g_o_p"]) : "";
          $doc1 = isset($_POST["doc1"]) ? $_POST["doc1"] : "";
          $doc_old_1 = isset($_POST["doc_old_1"]) ? limpiarCadena($_POST["doc_old_1"]) : "";
      
          // imgen de perfil
          if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {

            $doc1 = $_POST["doc_old_1"];

            $flat_comprob = false;

          } else {

            $ext1 = explode(".", $_FILES["doc1"]["name"]);

            $flat_comprob = true;
  
            $doc1 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);
  
            move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/activos_fijos_general/comprobantes_compra_activos_f/" . $doc1);
          }
          
          //Borramos el comprobante
          if ($flat_comprob == true) {

            $datos_f1 = $all_activos_fijos->obtener_comprobante_af_g($idcompra_af_g_o_p);
  
            $doc1_ant = $datos_f1['comprobante'];
  
            if ($doc1_ant != "") {  unlink("../dist/docs/activos_fijos_general/comprobantes_compra_activos_f/" . $doc1_ant); }
          }
  
          // editamos un documento existente
          $rspta = $all_activos_fijos->editar_comprobante_af_g($idcompra_af_g_o_p, $doc1);
  
          echo $rspta ? "ok" : "Documento no se pudo actualizar";

        break;        
      
        case 'anular':

          $rspta = $all_activos_fijos->desactivar($idcompra_af_general);
      
          echo $rspta ? "ok" : "Compra no se puede Anular";

        break;
      
        case 'des_anular':

          $rspta = $all_activos_fijos->activar($idcompra_af_general);
      
          echo $rspta ? "ok" : "Compra no se puede recuperar";

        break;
      
        case 'eliminar_compra':

          $rspta = $all_activos_fijos->eliminar_compra($idcompra_af_general);
      
          echo $rspta ? "ok" : "Compra no se puede Eliminar";

        break;
      
        //listar facturas_compra activos
        case 'listar_compra_activos':
          $rspta = $all_activos_fijos->listar_compra_activo_f_g();
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

          foreach ($rspta as $key => $reg) {

            $saldo = floatval($reg['total']) - floatval($reg['deposito']);  

            $btn_tipo = (empty( $reg['imagen_comprobante'])) ? 'btn-outline-info' : 'btn-info';             
  
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
              "1" => empty($reg['idproyecto']) ? ($reg['estado'] == '1'  ? '<button class="btn btn-info btn-sm" onclick="ver_detalle_compras_general(' . $reg['idtabla'] . ')" data-toggle="tooltip" data-original-title="Ver detalle compra"><i class="fa fa-eye"></i></button>' .
                    ' <button class="btn btn-warning btn-sm" onclick="mostrar_compra_general(' . $reg['idtabla'] . ')" data-toggle="tooltip" data-original-title="Editar compra"><i class="fas fa-pencil-alt"></i></button>' .
                    ' <button class="btn btn-danger  btn-sm" onclick="eliminar_compra(' . $reg['idtabla'] . ')"><i class="fas fa-skull-crossbones"></i> </button>'
                  : '<button class="btn btn-info btn-sm" onclick="ver_detalle_compras_general(' . $reg['idtabla'] . ')"data-toggle="tooltip" data-original-title="Ver detalle"><i class="fa fa-eye"></i></button>' .
                    ' <button class="btn btn-success btn-sm" onclick="des_anular(' . $reg['idtabla'] . ')" data-toggle="tooltip" data-original-title="Recuperar Compra"><i class="fas fa-check"></i></button>')
                : ($reg['estado'] == '1' ? '<button class="btn btn-info btn-sm " onclick="ver_detalle_compras_proyecto(' . $reg['idtabla'] . ')" data-toggle="tooltip" data-original-title="Ver detalle compra"><i class="fa fa-eye"></i></button>' .
                    ' <button class="btn btn-warning btn-sm" disabled onclick="mostrar_compra_proyecto(' . $reg['idtabla'] . ')" data-toggle="tooltip" data-original-title="Editar compra"><i class="fas fa-pencil-alt"></i></button>' .
                    ' <button class="btn btn-danger  btn-sm" disabled onclick="eliminar_compra(' . $reg['idtabla'] . ')"><i class="fas fa-skull-crossbones"></i> </button>'
                  : '<button class="btn btn-info btn-sm" disabled onclick="ver_detalle_compras_proyecto(' . $reg['idtabla'] . ')"data-toggle="tooltip" data-original-title="Ver detalle"><i class="fa fa-eye"></i></button>' .
                    ' <button class="btn btn-success btn-sm" disabled onclick="des_anular_af_p(' . $reg['idtabla'] . ')" data-toggle="tooltip" data-original-title="Recuperar Compra"><i class="fas fa-check"></i></button>'),
              "2" => '<textarea class="form-control textarea_datatable " cols="30" rows="1">' . $reg['descripcion'] . '</textarea>',      
              "3" => date("d/m/Y", strtotime($reg['fecha_compra'])),
              "4" => '<div class="user-block">'. 
                '<span class="description ml-0" ><b>' . (empty($reg['idproyecto']) ? 'General' : $reg['codigo_proyecto']) . '</b></span>'.
                '<span class="username ml-0" ><p class="text-primary m-b-02rem" >' . $reg['razon_social'] . '</p></span>'.                
              '</div>',
              "5" => '<span class="" ><b>' . $reg['tipo_comprobante'] .  '</b> '.(empty($reg['serie_comprobante']) ?  "" :  '- '.$reg['serie_comprobante']).'</span>',
              "6" => number_format($reg['total'], 2, '.', ','),
              "7" => empty($reg['idproyecto']) ? '<div class="text-center text-nowrap">'.
                '<button class="btn btn-' . $c . ' btn-xs m-t-2px" onclick="listar_pagos_af_g(' . $reg['idtabla'] . ',' . $reg['total'] . ',' . floatval($reg['deposito']) . ')"> <i class="fas fa-' . $icon . ' nav-icon"></i> ' . $nombre . '</button>' . 
                ' <button style="font-size: 14px;" class="btn btn-' . $cc . ' btn-sm">' . number_format(floatval($reg['deposito']), 2, '.', ',') . '</button>'.
              '</div>' : 
              '<div class="text-center text-nowrap">'.
                '<button class="btn btn-' . $c . ' btn-xs m-t-2px" disabled onclick="listar_pagos(' . $reg['idtabla'] . ',' . $reg['total'] . ',' . floatval($reg['deposito']) . ')"><i class="fas fa-' . $icon . ' nav-icon"></i> ' . $nombre . '</button>' . 
                ' <button style="font-size: 14px;" class="btn btn-' . $cc . ' btn-sm" disabled>' . number_format(floatval($reg['deposito']), 2, '.', ',') . '</button>'.
              '</div>',
              "8" => number_format($saldo, 2, '.', ','),
              "9" => empty($reg['idproyecto']) ? '<center><button class="btn '.$btn_tipo.' btn-sm" onclick="comprobante_compra_af_g(' . $reg['idtabla'] . ', \'' . $reg['imagen_comprobante'] . '\')"><i class="fas fa-file-invoice fa-lg"></i></button></center>'.$toltip : 
              '<center><button class="btn '.$btn_tipo.' btn-sm" onclick="comprobante_compras(' . $reg['idtabla'] . ', \'' . $reg['imagen_comprobante'] . '\')"><i class="fas fa-file-invoice fa-lg"></i></button></center>'.$toltip,
            ];
            
          }
          $results = [
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "data" => $data,
          ];
          echo json_encode($results);
      
        break;
      
        //tbl listar compras por proveedor
        case 'listar_compraxporvee_af_g':
          $rspta = $all_activos_fijos->listar_compraxporvee_af_g();
          //Vamos a declarar un array
          $data = [];
          $c = "info";
          $nombre = "Ver";
          $info = "info";
          $icon = "eye";
          $cont = 1;
  
          foreach ($rspta as $key => $value) {
            $data[] = [
              "0" => $cont++,
              "1" => '<button class="btn btn-info btn-sm" onclick="listar_facuras_proveedor_af_g(' . $value['idproveedor'] . ')" data-toggle="tooltip" data-original-title="Ver detalle"><i class="fa fa-eye"></i></button>',
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
          echo json_encode($results);
      
        break;
      
        case 'listar_detalle_compraxporvee':

          $rspta = $all_activos_fijos->listar_detalle_comprax_provee($_GET["idproveedor"]);
          //Vamos a declarar un array
          $data = [];
          
          foreach ($rspta as $key => $value) {
            $data[] = [
              "0" => empty($value['idproyecto']) ? '<center><button class="btn btn-info btn-sm" onclick="ver_detalle_compras_general(' . $value['idtabla'] . ')" data-toggle="tooltip" data-original-title="Ver detalle">Ver detalle <i class="fa fa-eye"></i></button></center>'
                : '<center><button class="btn btn-info btn-sm" onclick="ver_detalle_compras_proyecto(' . $value['idtabla'] . ')" data-toggle="tooltip" data-original-title="Ver detalle">Ver detalle <i class="fa fa-eye"></i></button></center>',
              "1" => date("d/m/Y", strtotime($value['fecha_compra'])),
              "2" => '<div class="user-block">'.
                '<span class="username ml-0" ><p class="text-primary m-b-02rem"  >' . (empty($value['idproyecto']) ? 'General' : $value['codigo_proyecto']) . '</p></span>                                   
              </div>',
              "3" => '<div class="user-block">'.
                '<span class="username ml-0"><p class="text-primary m-b-02rem" >' . $value['tipo_comprobante'] . '</p></span>'. 
                '<span class="description ml-0"><b>' . $value['serie_comprobante'] . '</b></span>                                  
              </div>',
              "4" => number_format($value['total'], 2, '.', ','),
              "5" => '<textarea cols="30" rows="1" class="text_area_clss" readonly >'.$value['descripcion'].'</textarea>',
              "6" => $value['estado'] == '1' ? '<span class="badge bg-success">Aceptado</span>' : '<span class="badge bg-danger">Anulado</span>',
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
      
        case 'ver_detalle_compras_general':
          
          //Recibimos el idingreso
          $idcompra_af_general = $_GET['idcompra_af_general'];

          $rspta = $all_activos_fijos->ver_compra_general($idcompra_af_general);
          $rspta2 = $all_activos_fijos->listarDetalleGeneral($idcompra_af_general);         

          $subtotal = 0;  $ficha = '';

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
      
        case 'ver_compra_editar':
          
          $rspta = $all_activos_fijos->mostrar_compra_para_editar($idcompra_af_general);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);
      
        break;

        // :::::::::::::::::::::::::: S E C C I O N   P R O V E E D O R  ::::::::::::::::::::::::::
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

        case 'guardar_y_editar_materiales':
          // imgen
          if (!file_exists($_FILES['fotop2']['tmp_name']) || !is_uploaded_file($_FILES['fotop2']['tmp_name'])) {

            $img_pefil_p = $_POST["fotop2_actual"];
      
            $flat_img1 = false;

          } else {

            $ext1 = explode(".", $_FILES["fotop2"]["name"]);
      
            $flat_img1 = true;
      
            $img_pefil_p = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);
      
            move_uploaded_file($_FILES["fotop2"]["tmp_name"], "../dist/docs/material/img_perfil/" . $img_pefil_p);
          }
      
          // ficha técnica
          if (!file_exists($_FILES['doct2']['tmp_name']) || !is_uploaded_file($_FILES['doct2']['tmp_name'])) {

            $ficha_tecnica_p = $_POST["doc_oldt_2"];
      
            $flat_ficha1 = false;

          } else {

            $ext1 = explode(".", $_FILES["doct2"]["name"]);
      
            $flat_ficha1 = true;
      
            $ficha_tecnica_p = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);
      
            move_uploaded_file($_FILES["doct2"]["tmp_name"], "../dist/docs/material/ficha_tecnica/" . $ficha_tecnica_p);
          }
      
          if (empty($idproducto_p)) {

            //var_dump($idproyecto,$idproveedor);
            $rspta = $activos_fijos->insertar( $unidad_medida_p, $color_p, $categoria_insumos_af_p, $nombre_p, $modelo_p,
            $serie_p, $marca_p, $estado_igv_p, $precio_unitario_p, $precio_igv_p, $precio_sin_igv_p, $precio_total_p, $ficha_tecnica_p,
            $descripcion_p, $img_pefil_p );
      
            echo $rspta ? "ok" : "No se pudieron registrar todos los datos";

          } else {
      
            // validamos si existe LA IMG para eliminarlo
            if ($flat_img1 == true) {

              $datos_f1 = $activos_fijos->obtenerImg($idproducto_p);
      
              $img1_ant = $datos_f1->fetch_object()->imagen;
      
              if ($img1_ant != "") {  unlink("../dist/docs/material/img_perfil/" . $img1_ant); }
            }
      
            $rspta = $activos_fijos->editar( $idproducto_p, $unidad_medida_p, $color_p, $categoria_insumos_af_p, $nombre_p, $modelo_p,
            $serie_p, $marca_p, $estado_igv_p, $precio_unitario_p, $precio_igv_p, $precio_sin_igv_p, $precio_total_p, $ficha_tecnica_p,
            $descripcion_p, $img_pefil_p );
              
            echo $rspta ? "ok" : "No se pudo actualizar";
          }
        break;

        case 'listarActivoscompra':
          
          $rspta = $all_activos_fijos->lista_activos_para_compras();
          //Vamos a declarar un array
          $datas = [];
          // echo json_encode($rspta);
          $img_parametro = "";
          $img = "";
          $imagen_error = "this.src='../dist/img/default/img_defecto_activo_fijo.png'";
          $color_stock = "";
          $ficha_tecnica = "";

          $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';              

          while ($reg = $rspta->fetch_object()) {
            if (empty($reg->imagen)) {
              $img = 'src="../dist/img/default/img_defecto_activo_fijo.png"';
              $img_parametro = "img_defecto_activo_fijo.png";
            } else {
              $img = 'src="../dist/docs/material/img_perfil/' . $reg->imagen . '"';
              $img_parametro = $reg->imagen;
            }
  
            !empty($reg->ficha_tecnica) ? ($ficha_tecnica = '<center><a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg->ficha_tecnica . '"><i class="far fa-file-pdf fa-2x text-success"></i></a></center>')
              : ($ficha_tecnica = '<center><span class="text-center"> <i class="far fa-times-circle fa-2x text-danger"></i></span></center>');
  
            $datas[] = [
              "0" => '<button class="btn btn-warning" onclick="agregarDetalleComprobante(' . $reg->idproducto . ', \'' .  htmlspecialchars($reg->nombre, ENT_QUOTES) . '\', \'' . $reg->nombre_medida . '\', \'' . $reg->nombre_color . '\', \'' . $reg->precio_sin_igv . '\', \'' . $reg->igv . '\', \'' . $reg->precio_con_igv . '\', \'' .  $img_parametro . '\', \'' . $reg->ficha_tecnica . '\')" data-toggle="tooltip" data-original-title="Agregar Activo"><span class="fa fa-plus"></span></button>',
              "1" => '<div class="user-block w-px-200">'.
                '<img class="profile-user-img img-responsive img-circle" ' .  $img . ' alt="user image" onerror="' . $imagen_error .  '">'.
                '<span class="username"><p style="margin-bottom: 0px !important;">' . $reg->nombre . '</p></span>
                <span class="description"><b>Color: </b>' . $reg->nombre_color . '</span>'.
              '</div>',
              "2" => $reg->marca,
              "3" => number_format($reg->precio_con_igv, 2, '.', ','),
              "4" => '<textarea class="form-control textarea_datatable" cols="30" rows="2">' . $reg->descripcion . '</textarea>',
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
      
        // :::::::::::::::::::::::::: S E C C I O N   P A G O  :::::::::::::::::::::::::: 
      
        case 'most_datos_prov_pago':

          $rspta = $all_activos_fijos->most_datos_prov_pago($_POST["idcompra_af_general"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);

        break;
      
        case 'guardaryeditar_pago':
          // imgen de perfil
          if (!file_exists($_FILES['foto1']['tmp_name']) || !is_uploaded_file($_FILES['foto1']['tmp_name'])) {
            $imagen1 = $_POST["foto1_actual"];
            $flat_img1 = false;
          } else {
            $ext1 = explode(".", $_FILES["foto1"]["name"]);
            $flat_img1 = true;
  
            $imagen1 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);
  
            move_uploaded_file($_FILES["foto1"]["tmp_name"], "../dist/docs/activos_fijos_general/comprobantes_pagos_activos_f/" . $imagen1);
          }
  
          if (empty($idpago_af_general)) {
            $rspta = $all_activos_fijos->insertar_pago( $idcompra_af_general_p, $beneficiario_pago, $forma_pago, $tipo_pago, $cuenta_destino_pago,
            $banco_pago, $titular_cuenta_pago, $fecha_pago, $monto_pago, $numero_op_pago, $descripcion_pago, $imagen1 );
            echo $rspta ? "ok" : "No se pudieron registrar todos los datos de servicio";
          } else {
            // validamos si existe LA IMG para eliminarlo
            if ($flat_img1 == true) {
              $datos_f1 = $all_activos_fijos->obtenerImg($idpago_af_general);
  
              $img1_ant = $datos_f1->fetch_object()->imagen;
  
              if ($img1_ant != "") { unlink("../dist/docs/activos_fijos_general/comprobantes_pagos_activos_f/" . $img1_ant); }
            }
  
            $rspta = $all_activos_fijos->editar_pago( $idpago_af_general, $idcompra_af_general_p, $beneficiario_pago, $forma_pago, $tipo_pago,
            $cuenta_destino_pago, $banco_pago, $titular_cuenta_pago, $fecha_pago, $monto_pago, $numero_op_pago, $descripcion_pago, $imagen1 );
  
            echo $rspta ? "ok" : "Servicio no se pudo actualizar";
          }
        break;
      
        case 'desactivar_pagos':

          $rspta = $all_activos_fijos->desactivar_pagos($idpago_af_general);

          echo $rspta ? "Pago Anulado" : "Pago no se puede Anular";

        break;
      
        case 'activar_pagos':

          $rspta = $all_activos_fijos->activar_pagos($idpago_af_general);

          echo $rspta ? "Pago Restablecido" : "Pago no se pudo Restablecido";

        break;
      
        case 'eliminar_pagos':

          $rspta = $all_activos_fijos->eliminar_pagos($idpago_af_general);

          echo $rspta ? "Pago Anulado" : "Pago no se puede Anular";

        break;
      
        case 'listar_pagos_proveedor':
          $rspta = $all_activos_fijos->listar_pagos_af_g($_GET["idcompra_af_general"]);
          //Vamos a declarar un array
          $data = [];
          $cont = 1;
          $suma = 0;
          $imagen = '';
  
          while ($reg = $rspta->fetch_object()) {
            $suma = $suma + $reg->monto;
            
            if (strlen($reg->titular_cuenta) >= 20) {
              $titular_cuenta = substr($reg->titular_cuenta, 0, 20) . '...';
            } else {
              $titular_cuenta = $reg->titular_cuenta;
            }
            empty($reg->imagen) ? ($imagen = '<div><center><a type="btn btn-danger" class=""><i class="far fa-times-circle fa-2x"></i></a></center></div>') : ($imagen = '<div><center><a type="btn btn-danger" class=""  href="#" onclick="ver_modal_vaucher(' . "'" . $reg->imagen . "'" . ')"><i class="fas fa-file-invoice-dollar fa-2x"></i></a></center></div>');
            
            $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

            $data[] = [
              "0" => $cont++,
              "1" => $reg->estado ? '<button class="btn btn-warning btn-sm" onclick="mostrar_pagos(' . $reg->idpago_af_general . ')"><i class="fas fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-danger btn-sm" onclick="desactivar_pagos(' . $reg->idpago_af_general . ')"><i class="fas fa-times"></i></button>' .
                  ' <button class="btn btn-danger  btn-sm" onclick="eliminar_pagos(' . $reg->idpago_af_general . ')"><i class="fas fa-skull-crossbones"></i> </button>'
                : '<button class="btn btn-warning btn-sm" onclick="mostrar_pagos(' . $reg->idpago_af_general . ')"><i class="fa fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-primary btn-sm" onclick="activar_pagos(' . $reg->idpago_af_general . ')"><i class="fa fa-check"></i></button>',
              "2" => $reg->forma_pago,
              "3" => '<div class="user-block">'.
                '<span class="username ml-0"><p class="text-primary m-b-02rem" >' . $reg->beneficiario . '</p></span>
                <span class="description ml-0" data-toggle="tooltip" data-original-title="' .  $reg->titular_cuenta . '"><b>titular: </b>' . $titular_cuenta . '</span>'.
              '</div>',
              "4" => '<div class="user-block">
                <span class="username ml-0"><p class="text-primary m-b-02rem" >' . $reg->banco . '</p></span>
                <span class="description ml-0" data-toggle="tooltip" data-original-title="' . $reg->cuenta_destino . '"><b>C: </b>' . $reg->cuenta_destino . '</span>'.
              '</div>',
              "5" => date("d/m/Y", strtotime($reg->fecha_pago)),
              "6" => '<textarea class="form-control textarea_datatable" cols="30" rows="1">' . $reg->descripcion . '</textarea>',
              "7" => 'S/. ' . number_format($reg->monto, 2, '.', ','),
              "8" => $imagen,
              "9" => $reg->estado ? '<span class="text-center badge badge-success">Activado</span>' . $toltip : '<span class="text-center badge badge-danger">Desactivado</span>' . $toltip,
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

          echo json_encode($results);

        break;
      
        case 'suma_total_pagos':

          $idcompra_af_general = $_POST["idcompra_af_general"];

          $rspta = $all_activos_fijos->suma_total_pagos($idcompra_af_general);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);

        break;
      
        case 'mostrar_pagos':

          $rspta = $all_activos_fijos->mostrar_pagos($idpago_af_general);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);

        break;
      
        //:::::::V E R  D E T A L L E  C O M P R A S  P R O Y E C T O::::::::::::
            
        case 'ver_detalle_compras_proyecto':
          
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

        // ::::::::::::::::::::::::::::::::::::::::: S E C C I O N   S E L E C T 2  ::::::::::::::::::::::::::::::::::::::::: 
    
        case 'select2Proveedor': 
      
          $rspta=$all_activos_fijos->select2_proveedor();
      
          while ($reg = $rspta->fetch_object())	{
      
            echo '<option value=' . $reg->idproveedor . '>' . $reg->razon_social .' - '. $reg->ruc . '</option>';
      
          }
      
        break;
      
        case 'select2Banco': 
      
          $rspta = $all_activos_fijos->select2_banco();
      
          while ($reg = $rspta->fetch_object())  {
      
            echo '<option value=' . $reg->id . '>' . $reg->nombre . ((empty($reg->alias)) ? "" : " - $reg->alias" ) .'</option>';
          }
      
        break;
      
        case 'select2Color': 
      
          $rspta = $all_activos_fijos->select2_color();
      
          while ($reg = $rspta->fetch_object())  {
      
            echo '<option value=' . $reg->id . '>' . $reg->nombre .'</option>';
          }
      
        break;
      
        case 'select2UnidaMedida': 
      
          $rspta = $all_activos_fijos->select2_unidad_medida();
      
          while ($reg = $rspta->fetch_object())  {
      
            echo '<option value=' . $reg->id . '>' . $reg->nombre . ' - ' . $reg->abreviacion .'</option>';
          }
      
        break;
      
        case 'select2Categoria': 
      
          $rspta = $all_activos_fijos->select2_categoria();
      
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

  function quitar_guion($numero) {
    return str_replace("-", "", $numero);
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

  ob_end_flush();
?>
