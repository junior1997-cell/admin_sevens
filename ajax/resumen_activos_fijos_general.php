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
    if ($_SESSION['resumen_activo_fijo_general'] == 1) {
      
      //Resumen activos general
      require_once "../modelos/Resumen_activos_fijos_general.php";
      require_once "../modelos/Activos_fijos.php";
      require_once "../modelos/Compra_activos_fijos.php";
      require_once "../modelos/AllProveedor.php";
      require_once "../modelos/Compra_insumos.php";

      $resumen_af_g = new Resumen_activos_fijos_general();
      $compra_activos_fijos = new Compra_activos_fijos();
      $activos_fijos = new Activos_fijos();
      $proveedor = new AllProveedor();   
      $compra = new Compra_insumos();

      date_default_timezone_set('America/Lima');
      $date_now = date("d-m-Y h.i.s A");

      $op_general = "ActivosFijos";
      $op_proyecto = "Insumos";
      $imagen_error = "this.src='../dist/svg/404-v2.svg'";
      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

      // ::::::::::::::::::::::::::::::::: D A T O S   C O M P R A S   G E N E R A L   Y/O   P R O Y E C T O :::::::::::::::::::::::::::::
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


      // :::::::::::::::::::::::::::::::::::: D A T O S   M A T E R I A L E S ::::::::::::::::::::::::::::::::::::::
      $idproducto_p     = isset($_POST["idproducto_p"]) ? limpiarCadena($_POST["idproducto_p"]) : "" ;
      $unidad_medida_p  = isset($_POST["unidad_medida_p"]) ? limpiarCadena($_POST["unidad_medida_p"]) : "" ;
      $color_p          = isset($_POST["color_p"]) ? limpiarCadena($_POST["color_p"]) : "" ;
      $categoria_insumos_af_p    = isset($_POST["categoria_insumos_af_p"]) ? limpiarCadena($_POST["categoria_insumos_af_p"]) : "" ;
      $id_grupo         = isset($_POST["idtipo_tierra_concreto"]) ? limpiarCadena($_POST["idtipo_tierra_concreto"]) : "" ;
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

        // ::::::::::::::::::::::::::::::::::::::: S E C C I O N   R E S U M E N   D E   A C T I V O S :::::::::::::::::::::::::::::::::::::::      
        
        case 'tbla_principal':
          
          $rspta = $resumen_af_g->listar_tbla_principal_general($_GET["id_categoria"]);
          
          //Vamos a declarar un array
          $data = []; $cont = 1;        

          if ($rspta['status']) {
            foreach ($rspta['data'] as $key => $reg) {
              $data[] = [
                "0" => '<center>' . $cont++ . '</center>',
                "1" => '<button class="btn btn-warning btn-sm" onclick="mostrar_material(' . $reg['idproducto'] . ', \'\')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>
                  <button class="btn btn-info btn-sm" onclick="mostrar_detalle_material(' . $reg['idproducto'] . ')" data-toggle="tooltip" data-original-title="Ver detalle"><i class="far fa-eye"></i></button>',
                "2" => '<div class="user-block"> 
                  <img class="profile-user-img img-responsive cursor-pointer img-circle" src="../dist/docs/material/img_perfil/' . (empty($reg['imagen'])? 'producto-sin-foto.svg' : $reg['imagen'] )  . '" onclick="ver_perfil(\'../dist/docs/material/img_perfil/' . (empty($reg['imagen'])? 'producto-sin-foto.svg' : $reg['imagen'] ) . '\', \''.encodeCadenaHtml($reg['nombre_producto']).'\');" alt="User Image" onerror="' . $imagen_error . '">
                  <span class="username"><p class="text-primary" style="margin-bottom: 0.2rem !important"; >' . $reg['nombre_producto'] . '</p>
                  </span><span class="description"> ' . (empty($reg['modelo']) ? '' : '<b class="d-none">═</b> <b >Modelo:</b> ' . $reg['modelo']) . '</span>
                </div>',
                "3" => $reg['nombre_color'],
                "4" => $reg['nombre_medida'],
                "5" => '<center>' . $reg['cantidad'] . '</center>',
                "6" => '<button class="btn btn-info btn-sm mb-2" onclick="tbla_facuras(' . $reg['idproducto'] . ', \'' . htmlspecialchars($reg['nombre_producto'], ENT_QUOTES) . '\', \'' . $reg['promedio_precio'] . '\', \'' . number_format($reg['subtotal'], 2, ".", ",") . '\')" data-toggle="tooltip" data-original-title="Ver facturas"><i class="far fa-eye"></i></button>'.$toltip,
                "7" => number_format($reg['promedio_precio'], 2, ".", "") ,
                "8" => number_format($reg['precio_actual'], 2, ".", ""),
                "9" => number_format($reg['subtotal'], 2, ".", ""),
                "10" => $reg['nombre_producto'], 
                "11" => $reg['modelo'],
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
        
        case 'suma_total_compras':

          $rspta = $resumen_af_g->suma_total_compras($_POST["id_categoria_suma"]);

          echo json_encode($rspta, true);

        break;
    

        // ::::::::::::::::::::::::::::::::::::::: S E C C I O N  C O M P R A   G E N E R A L :::::::::::::::::::::::::::::::::::::::

        case 'guardar_y_editar_compra_ActivosFijos':
          if (empty($idcompra_af_general)) {
            $rspta = ["status"=> false, "message"=> 'error', "data"=> 'nell vacio', ];
            echo json_encode($rspta, true);
          } else {
            $rspta = $compra_activos_fijos->editar( $idcompra_af_general, $idproveedor, $fecha_compra,  $tipo_comprobante, $serie_comprobante, 
            $val_igv, $descripcion, $glosa, $total_venta, $subtotal_compra, $igv_compra, $estado_detraccion, $_POST["idproducto"], $_POST["unidad_medida"], 
            $_POST["nombre_color"], $_POST["cantidad"], $_POST["precio_sin_igv"], $_POST["precio_igv"],  $_POST["precio_con_igv"], $_POST["descuento"], 
            $tipo_gravada, $_POST["ficha_tecnica_producto"] );

            echo json_encode($rspta, true);
          }
          
        break;  

        case 'tbla_facturas':
          $idproducto = $_GET["idproducto"];

          $rspta = $resumen_af_g->ver_precios_y_mas($idproducto);
          //Vamos a declarar un array
          $data = [];  $cont = 1;

          if ($rspta['status']) {
            foreach ($rspta['data'] as $key => $reg) {
              $ficha_tecnica = ""; $comprobantes="";
              !empty($reg['ficha_tecnica'])
                ? ($ficha_tecnica = '<center><a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg['ficha_tecnica'] . '"><i class="far fa-file-pdf fa-2x text-success"></i></a></center>')
                : ($ficha_tecnica = '<center><span class="text-center"> <i class="far fa-times-circle fa-2x text-danger"></i></span></center>');
    
                $btn_tipo = (empty($reg['cant_comprobantes']) ? 'btn-outline-info' : 'btn-info');
                $descrip_toltip = (empty($reg['cant_comprobantes']) ? 'Vacío' : ($reg['cant_comprobantes']==1 ?  $reg['cant_comprobantes'].' comprobante' : $reg['cant_comprobantes'].' comprobantes'));       
  
                (!empty($reg['idproyecto'])?
                $comprobantes='<center> <button class="btn '.$btn_tipo.' btn-sm" onclick="comprobantes_compras(\''.$reg['idcompra'].'\', \''.$cont.'\', \''.encodeCadenaHtml($reg['tipo_comprobante'].' '.(empty($reg['serie_comprobante']) ?  "" :  '- '.$reg['serie_comprobante'])).'\', \''.format_d_m_a($reg['fecha_compra']).'\')" data-toggle="tooltip" data-original-title="'.$descrip_toltip.'"><i class="fas fa-file-invoice fa-lg"></i></button> </center>'.$toltip :
                $comprobantes='<center> <button class="btn '.$btn_tipo.' btn-sm" onclick="comprobante_unico(\''.$reg['cant_comprobantes'].'\', \''.$cont.'\', \''.encodeCadenaHtml($reg['tipo_comprobante'].' '.(empty($reg['serie_comprobante']) ?  "" :  '- '.$reg['serie_comprobante'])).'\', \''.format_d_m_a($reg['fecha_compra']).'\')" data-toggle="tooltip" data-original-title="'.$descrip_toltip.'"><i class="fas fa-file-invoice fa-lg"></i></button> </center>'.$toltip);

              $data[] = [
                "0" => $cont++,
                "1" => empty($reg['idproyecto']) ? '<button class="btn btn-info btn-sm" onclick="ver_detalle_compras(' . $reg['idcompra'] . ', \'' . $op_general . '\')" data-toggle="tooltip" data-original-title="Ver detalle compra"><i class="fa fa-eye"></i></button>'.
                  ' <button class="btn btn-warning btn-sm" onclick="editar_detalle_compras(' .  $reg['idcompra'] . ', \'' . $op_general . '\');" data-toggle="tooltip" data-original-title="Editar compra"><i class="fas fa-pencil-alt"></i></button>' : 
                  '<button class="btn btn-info btn-sm" onclick="ver_detalle_compras(' . $reg['idcompra'] . ', \'' . $op_proyecto . '\')" data-toggle="tooltip" data-original-title="Ver detalle compra"><i class="fa fa-eye"></i></button>'.
                  ' <button class="btn btn-warning btn-sm" onclick="editar_detalle_compras(' .  $reg['idcompra'] . ', \'' . $op_proyecto . '\');" data-toggle="tooltip" data-original-title="Editar compra"><i class="fas fa-pencil-alt"></i></button>',
                "2" => $reg['modulo'],
                "3" => '<span class="text-primary font-weight-bold" >' . $reg['proveedor'] . '</span>',
                "4" =>'<span class="" ><b>' . $reg['tipo_comprobante'] .  '</b> '.(empty($reg['serie_comprobante']) ?  "" :  '- '.$reg['serie_comprobante']).'</span>',  
                "5" => $reg['fecha_compra'],
                "6" => number_format($reg['cantidad'], 2, ".", ","),
                "7" => '<b class="h5 font-weight-bold">' . number_format($reg['precio_con_igv'], 2, ".", ",") . '</b>',
                "8" => number_format($reg['descuento'], 2, ".", ""),
                "9" => number_format($reg['subtotal'], 2, ".", ""),
                "10" => $comprobantes,
                // '<center> <button class="btn '.$btn_tipo.' btn-sm" onclick="comprobante_compras(\''.$reg['idproyecto'].'\', \''.$cont.'\', \''.encodeCadenaHtml($reg['tipo_comprobante'].' '.(empty($reg['serie_comprobante']) ?  "" :  '- '.$reg['serie_comprobante'])).'\', \''.format_d_m_a($reg['fecha_compra']).'\')" data-toggle="tooltip" data-original-title="'.$descrip_toltip.'"><i class="fas fa-file-invoice fa-lg"></i></button> </center>'.$toltip,
                "11" => $ficha_tecnica,
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

        case 'ver_compra_editar_ActivosFijos':        

          $rspta = $compra_activos_fijos->mostrar_compra_para_editar($_POST["idcompra"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);

        break; 

        case 'sumas_factura_x_material':
          $rspta = $resumen_af_g->sumas_factura_x_material($_POST["idproducto"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
      
        break; 
        
        case 'ver_detalle_compras_ActivosFijos':
          
          $rspta = $compra_activos_fijos->ver_compra_general($_GET['id_compra']);
          $rspta2 = $compra_activos_fijos->ver_detalle_compra_general($_GET['id_compra']);

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

            $ficha = empty($reg->ficha_tecnica) ? ( '<i class="far fa-file-pdf fa-lg text-gray-50" data-toggle="tooltip" data-original-title="Vacío"></i>') : ('<a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg->ficha_tecnica . '" data-toggle="tooltip" data-original-title="Ver Ficha Técnica"><i class="far fa-file-pdf fa-lg text-primary"></i></a>');
            $img_product = '../dist/docs/material/img_perfil/'. (empty($reg->imagen) ? 'producto-sin-foto.svg' : $reg->imagen );

            $tbody .= '<tr class="filas">
            <td class="text-center p-6px">' . $cont++ . '</td>
              <td class="text-center p-6px">' . $ficha . '</td>
              <td class="text-left p-6px">
                <div class="user-block text-nowrap">
                  <img class="profile-user-img img-responsive img-circle cursor-pointer" onclick="ver_img_material(\''.$img_product.'\', \'' . encodeCadenaHtml( $reg->nombre) . '\', null)" src="'.  $img_product.'" alt="user image" onerror="this.src=\'../dist/svg/404-v2.svg\';" data-toggle="tooltip" data-original-title="Ver img" >
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
              <thead style="background-color:#127ab6ba">
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

        // ::::::::::::::::::::::::::::::::::::::: S E C C I O N   C O M P R A  x  P R O Y E C T O :::::::::::::::::::::::::::::::::::::::      
        
        case 'guardar_y_editar_compra_Insumos':

          if (empty($idcompra_proyecto)) {
            $rspta = '';
            echo $rspta ? "ok" : "No se pudieron registrar todos los datos de la compra";
          } else {
            $rspta = $compra->editar($idcompra_proyecto, $idproyecto, $idproveedor, $fecha_compra,  $tipo_comprobante, $serie_comprobante, $val_igv, 
            $descripcion, $glosa, $total_venta, $subtotal_compra, $igv_compra, $estado_detraccion, $_POST["idproducto"], $_POST["unidad_medida"], 
            $_POST["nombre_color"], $_POST["cantidad"], $_POST["precio_sin_igv"], $_POST["precio_igv"],  $_POST["precio_con_igv"], $_POST["descuento"], 
            $tipo_gravada, $_POST["ficha_tecnica_producto"]);

            echo json_encode($rspta, true);
          }

        break;

        case 'ver_compra_editar_Insumos':
          
          $rspta = $compra->mostrar_compra_para_editar($_POST['idcompra']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);

        break;

        case 'ver_detalle_compras_Insumos':
          
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
              <thead style="background-color:#ff6c046b">
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
              </thead>
              <tbody>'.$tbody.'</tbody>          
              <tfoot>
                <tr>
                  <td class="p-0" colspan="8"></td>
                  <td class="p-0 text-right"> <h6 class="mt-1 mb-1 mr-1">'.$rspta['data']['tipo_gravada'].'</h6> </td>
                  <td class="p-0 text-right">
                    <h6 class="mt-1 mb-1 mr-1 font-weight-bold text-nowrap">S/ ' . number_format($rspta['data']['subtotal'], 2, '.',',') . '</h6>
                  </td>
                </tr>
                <tr>
                  <td class="p-0" colspan="8"></td>
                  <td class="p-0 text-right">
                    <h6 class="mt-1 mb-1 mr-1">IGV('.( ( empty($rspta['data']['val_igv']) ? 0 : floatval($rspta['data']['val_igv']) )  * 100 ).'%)</h6>
                  </td>
                  <td class="p-0 text-right">
                    <h6 class="mt-1 mb-1 mr-1 font-weight-bold text-nowrap">S/ ' . number_format($rspta['data']['igv'], 2, '.',',') . '</h6>
                  </td>
                </tr>
                <tr>
                  <td class="p-0" colspan="8"></td>
                  <td class="p-0 text-right"> <h5 class="mt-1 mb-1 mr-1 font-weight-bold">TOTAL</h5> </td>
                  <td class="p-0 text-right">
                    <h5 class="mt-1 mb-1 mr-1 font-weight-bold text-nowrap">S/ ' . number_format($rspta['data']['total'], 2, '.',',') . '</h5>
                  </td>
                </tr>
              </tfoot>
            </table>
          </div> ';

        break;

        // ::::::::::::::::::::::::::::::::::::::: S E C C I O N   M A T E R I A L E S :::::::::::::::::::::::::::::::::::::::
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
            $rspta = $activos_fijos->insertar( $unidad_medida_p, $color_p, $categoria_insumos_af_p,$id_grupo, $nombre_p, $modelo_p,
              $serie_p, $marca_p, $estado_igv_p, $precio_unitario_p, $precio_igv_p, $precio_sin_igv_p, $precio_total_p,  $ficha_tecnica_p,
              $descripcion_p,  $img_pefil_p );

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

            $rspta = $activos_fijos->editar( $idproducto_p, $unidad_medida_p, $color_p, $categoria_insumos_af_p,$id_grupo, $nombre_p, $modelo_p,
              $serie_p, $marca_p, $estado_igv_p, $precio_unitario_p, $precio_igv_p, $precio_sin_igv_p, $precio_total_p, $ficha_tecnica_p,
              $descripcion_p, $img_pefil_p );
            //var_dump($idactivos_fijos,$idproveedor);
            echo json_encode($rspta, true);
          }

        break;

        case 'listarMateriales_general':
          $rspta = $resumen_af_g->listar_solo_activos();
          //Vamos a declarar un array
          $datas = [];
          // echo json_encode($rspta);
          $img = "";
          $imagen_error = "this.src='../dist/svg/default_producto.svg'";
          $color_stock = "";
          $ficha_tecnica = "";

          while ($reg = $rspta['data']->fetch_object()) {

            if (!empty($reg->imagen)) { $img = "../dist/docs/material/img_perfil/$reg->imagen"; } else { $img = "../dist/svg/default_producto.svg"; }

            !empty($reg->ficha_tecnica) ? ($ficha_tecnica = '<center><a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg->ficha_tecnica . '"><i class="far fa-file-pdf fa-2x text-success"></i></a></center>') : ($ficha_tecnica = '<center><span class="text-center"> <i class="far fa-times-circle fa-2x text-danger"></i></span></center>');

            $datas[] = [
              "0" => '<button class="btn btn-warning" onclick="agregarDetalleComprobante(' . $reg->idproducto . ', \'' . htmlspecialchars($reg->nombre, ENT_QUOTES) . '\', \'' . $reg->nombre_medida . '\', \'' . $reg->nombre_color . '\', \'' . $reg->precio_sin_igv . '\', \'' . $reg->precio_igv . '\', \'' . $reg->precio_total . '\', \'' . $reg->imagen . '\', \'' .  $reg->ficha_tecnica . '\')" data-toggle="tooltip" data-original-title="Agregar Planta">
                <span class="fa fa-plus"></span>
              </button>',
              "1" => '<div class="user-block w-250px"> <img class="profile-user-img img-responsive img-circle" src="' . $img . '" alt="user image" onerror="' . $imagen_error .'"> 
                <span class="username"><p class="mb-0" >' . $reg->nombre . '</p></span>  
                <span class="description"><b>Color: </b>' . $reg->nombre_color . '</span>
                <span class="description"><b>Marca: </b>' . $reg->marca . '</span>
              </div>',
              "2" => $reg->categoria,
              "3" => number_format($reg->precio_unitario, 2, '.', ','),
              "4" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >' . $reg->descripcion . '</textarea>',
              "5" => $ficha_tecnica,
            ];
          }

          $results = [
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($datas), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($datas), //enviamos el total registros a visualizar
            "aaData" => $datas,
          ];
          echo json_encode($results, true);

        break;

        case 'listarMateriales_proyecto':
          $rspta = $resumen_af_g->listar_insumos_activo_general();
          //Vamos a declarar un array
          $datas = [];
          // echo json_encode($rspta);
          $img = "";
          $imagen_error = "this.src='../dist/svg/default_producto.svg'";
          $color_stock = "";
          $ficha_tecnica = "";

          while ($reg = $rspta['data']->fetch_object()) {

            if (!empty($reg->imagen)) { $img = "../dist/docs/material/img_perfil/$reg->imagen"; } else { $img = "../dist/svg/default_producto.svg"; }

            !empty($reg->ficha_tecnica) ? ($ficha_tecnica = '<center><a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg->ficha_tecnica . '"><i class="far fa-file-pdf fa-2x text-success"></i></a></center>') : ($ficha_tecnica = '<center><span class="text-center"> <i class="far fa-times-circle fa-2x text-danger"></i></span></center>');

            $datas[] = [
              "0" => '<button class="btn btn-warning" onclick="agregarDetalleComprobante(' . $reg->idproducto . ', \'' . htmlspecialchars($reg->nombre, ENT_QUOTES) . '\', \'' . $reg->nombre_medida . '\', \'' . $reg->nombre_color . '\', \'' . $reg->precio_sin_igv . '\', \'' . $reg->precio_igv . '\', \'' . $reg->precio_total . '\', \'' . $reg->imagen . '\', \'' .  $reg->ficha_tecnica . '\')" data-toggle="tooltip" data-original-title="Agregar Planta">
                <span class="fa fa-plus"></span>
              </button>',
              "1" => '<div class="user-block w-250px"> <img class="profile-user-img img-responsive img-circle" src="' . $img . '" alt="user image" onerror="' . $imagen_error .'"> 
                <span class="username"><p class="mb-0" >' . $reg->nombre . '</p></span>  
                <span class="description"><b>Color: </b>' . $reg->nombre_color . '</span>
                <span class="description"><b>Marca: </b>' . $reg->marca . '</span>
              </div>',
              "2" => $reg->categoria,
              "3" => number_format($reg->precio_unitario, 2, '.', ','),
              "4" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >' . $reg->descripcion . '</textarea>',
              "5" => $ficha_tecnica,
            ];
          }

          $results = [
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($datas), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($datas), //enviamos el total registros a visualizar
            "aaData" => $datas,
          ];
          echo json_encode($results, true);
        break;

        case 'mostrar_producto':

          $rspta = $activos_fijos->mostrar($_POST['idproducto_p']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);

        break;

        case 'mostrar_detalle_material':

          $rspta = $activos_fijos->mostrar($_POST['idproducto_p']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);

        break;

        // ::::::::::::::::::::::::::::::::::::::: S E C C I O N   P R O V E E D O R ::::::::::::::::::::::::::::::::::::::: 
        
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

        default: 
          $rspta = ['status'=>'error_code', 'message'=>'Te has confundido en escribir en el <b>swich.</b>', 'data'=>[]]; echo json_encode($rspta, true); 
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
      }
      //Fin de las validaciones de acceso
    } else {
      $retorno = ['status'=>'nopermiso', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
      echo json_encode($retorno);
    }
  }

  ob_end_flush();
?>
