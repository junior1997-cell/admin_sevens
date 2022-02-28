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

    require_once "../modelos/Resumen_insumos.php";
    require_once "../modelos/Compra.php";
    require_once "../modelos/AllProveedor.php";

    $resumen_insumos = new ResumenInsumos();
    $compra = new Compra();
    $proveedor = new Proveedor();

    // :::::::::::::::::::::::::::::::::::: D A T O S   C O M P R A ::::::::::::::::::::::::::::::::::::::
    $idproyecto = isset($_POST["idproyecto"]) ? limpiarCadena($_POST["idproyecto"]) : "";
    $idcompra_proyecto = isset($_POST["idcompra_proyecto"]) ? limpiarCadena($_POST["idcompra_proyecto"]) : "";
    $idproveedor = isset($_POST["idproveedor"]) ? limpiarCadena($_POST["idproveedor"]) : "";
    $fecha_compra = isset($_POST["fecha_compra"]) ? limpiarCadena($_POST["fecha_compra"]) : "";
    $tipo_comprovante = isset($_POST["tipo_comprovante"]) ? limpiarCadena($_POST["tipo_comprovante"]) : "";
    $serie_comprovante = isset($_POST["serie_comprovante"]) ? limpiarCadena($_POST["serie_comprovante"]) : "";
    $descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
    $subtotal_compra = isset($_POST["subtotal_compra"]) ? limpiarCadena($_POST["subtotal_compra"]) : "";
    $igv_compra = isset($_POST["igv_compra"]) ? limpiarCadena($_POST["igv_compra"]) : "";
    $total_venta = isset($_POST["total_venta"]) ? limpiarCadena($_POST["total_venta"]) : "";
    $estado_detraccion = isset($_POST["estado_detraccion"]) ? limpiarCadena($_POST["estado_detraccion"]) : "";

    // :::::::::::::::::::::::::::::::::::: D A T O S   M A T E R I A L E S ::::::::::::::::::::::::::::::::::::::
    $idproducto_p     = isset($_POST["idproducto_p"]) ? limpiarCadena($_POST["idproducto_p"]) : "" ;
    $unidad_medida_p  = isset($_POST["unidad_medida_p"]) ? limpiarCadena($_POST["unidad_medida_p"]) : "" ;
    $color_p          = isset($_POST["color_p"]) ? limpiarCadena($_POST["color_p"]) : "" ;
    $categoria_insumos_af_p    = isset($_POST["categoria_insumos_af_p"]) ? limpiarCadena($_POST["categoria_insumos_af_p"]) : "" ;
    $nombre_p         = isset($_POST["nombre_p"]) ? limpiarCadena($_POST["nombre_p"]) : "" ;
    $modelo_p         = isset($_POST["modelo_p"]) ? limpiarCadena($_POST["modelo_p"]) : "" ;
    $serie_p          = isset($_POST["serie_p"]) ? limpiarCadena($_POST["serie_p"]) : "" ;
    $marca_p          = isset($_POST["marca_p"]) ? limpiarCadena($_POST["marca_p"]) : "" ;
    $estado_igv_p     = isset($_POST["estado_igv_p"]) ? limpiarCadena($_POST["estado_igv_p"]) : "" ;
    $precio_unitario_p= isset($_POST["precio_unitario_p"]) ? limpiarCadena($_POST["precio_unitario_p"]) : "" ;      
    $precio_sin_igv_p = isset($_POST["precio_sin_igv_p"]) ? limpiarCadena($_POST["precio_sin_igv_p"]) : "" ;
    $precio_igv_p     = isset($_POST["precio_igv_p"]) ? limpiarCadena($_POST["precio_igv_p"]) : "" ;
    $precio_total_p   = isset($_POST["precio_total_p"]) ? limpiarCadena($_POST["precio_total_p"]) : "" ;      
    $descripcion_p    = isset($_POST["descripcion_p"]) ? limpiarCadena($_POST["descripcion_p"]) : "" ; 
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

      case 'guardar_y_editar_compra':
        if (empty($idcompra_proyecto)) {
          $rspta = $compra->insertar( $idproyecto, $idproveedor, $fecha_compra, $tipo_comprovante, $serie_comprovante, $descripcion, 
          $total_venta, $subtotal_compra, $igv_compra, $estado_detraccion,  $_POST["idproducto"], $_POST["unidad_medida"], 
          $_POST["nombre_color"], $_POST["cantidad"], $_POST["precio_sin_igv"], $_POST["precio_igv"], $_POST["precio_con_igv"],
            $_POST["descuento"], $_POST["ficha_tecnica_producto"]);
          //precio_sin_igv,precio_igv,precio_total
          echo $rspta ? "ok" : "No se pudieron registrar todos los datos de la compra";
        } else {
          $rspta = $compra->editar( $idcompra_proyecto, $idproyecto, $idproveedor, $fecha_compra,  $tipo_comprovante, $serie_comprovante, 
          $descripcion, $total_venta, $subtotal_compra, $igv_compra, $estado_detraccion, $_POST["idproducto"], $_POST["unidad_medida"], 
          $_POST["nombre_color"], $_POST["cantidad"], $_POST["precio_sin_igv"], $_POST["precio_igv"], $_POST["precio_con_igv"], $_POST["descuento"],
            $_POST["ficha_tecnica_producto"] );
    
          echo $rspta ? "ok" : "Compra no se pudo actualizar";
        }
    
      break;

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
          $rspta = $resumen_insumos->insertar_material( $unidad_medida_p, $color_p, $categoria_insumos_af_p, $nombre_p, $modelo_p, $serie_p, $marca_p, $estado_igv_p, $precio_unitario_p, $precio_igv_p, $precio_sin_igv_p, $precio_total_p, $ficha_tecnica_p, $descripcion_p,  $img_pefil_p);
          
          echo $rspta ? "ok" : "No se pudieron registrar todos los datos";
    
        } else {

        }
    
      break;

      case 'guardar_proveedor':

        if (empty($idproveedor_prov)){

          $rspta=$proveedor->insertar($nombre_prov, $tipo_documento_prov, $num_documento_prov, $direccion_prov, $telefono_prov,
          $c_bancaria_prov, $cci_prov, $c_detracciones_prov, $banco_prov, $titular_cuenta_prov);
          
          echo $rspta ? "ok" : "No se pudieron registrar todos los datos del proveedor";
        }

      break;

      case 'tbla_principal':

        $idproyecto = $_GET["id_proyecto"];

        $rspta = $resumen_insumos->tbla_principal($idproyecto);
        //Vamos a declarar un array
        $data = []; $count = 1;

        $imagen_error = "this.src='../dist/svg/default_producto.svg'";

        while ($reg = $rspta->fetch_object()) {

          $precio_promedio = number_format($reg->precio_con_igv / $reg->count_productos, 2, ".", ",");

          $data[] = [     
            "0"  => $count++,       
            "1" => '<button class="btn btn-warning btn-sm" onclick="mostrar_insumo(' . $reg->idproducto . ')"><i class="fas fa-pencil-alt"></i></button>
              <button class="btn btn-info btn-sm" onclick="mostrar_detalle_insumo(' . $reg->idproducto . ')"><i class="far fa-eye"></i></button>',       
            "2" => '<div class="user-block"> <img class="profile-user-img img-responsive img-circle" src="../dist/docs/material/img_perfil/' . $reg->imagen . '" alt="User Image" onerror="' .  $imagen_error .  '"><span class="username"><p class="text-primary" style="margin-bottom: 0.2rem !important"; >' . $reg->nombre_producto . '</p></span><span class="description"> <b class="hidden">-</b> <b class="hidden">Modelo:</b> ' . $reg->modelo .'</span>
              </div>',
            "3" => $reg->nombre_color,
            "4" => $reg->marca,
            "5" => $reg->nombre_medida,
            "6" => $reg->cantidad_total,
            "7" => '<button class="btn btn-info btn-sm mb-2" onclick="tbla_facuras(' . $reg->idproyecto . ', ' . $reg->idproducto . ', \'' . $reg->nombre_producto . '\', \'' .  $precio_promedio . '\', \'' .  number_format($reg->precio_total, 2, ".", ",") . '\')"><i class="far fa-eye"></i></button>',
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

      case 'tbla_facturas':
        $idproyecto = $_GET["idproyecto"];
        $idproducto = $_GET["idproducto"];

        $rspta = $resumen_insumos->tbla_facturas($idproyecto, $idproducto);
        //Vamos a declarar un array
        $data = [];

        $imagen_error = "this.src='../dist/svg/user_default.svg'";
        $ficha_tecnica = "";

        while ($reg = $rspta->fetch_object()) {
          // validamos si existe una ficha tecnica
          !empty($reg->ficha_tecnica)
            ? ($ficha_tecnica = '<center><a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg->ficha_tecnica . '"><i class="far fa-file-pdf fa-lg text-success"></i></a></center>')
            : ($ficha_tecnica = '<center><i class="far fa-file-pdf fa-lg text-gray-50"></i></center>');

          $data[] = [    
            "0" => '<button class="btn btn-warning btn-sm" onclick="editar_detalle_compras(' . $reg->idcompra_proyecto . ')" data-toggle="tooltip" data-original-title="Editar compra"><i class="fas fa-pencil-alt"></i></button>',
            "1" => '<span class="text-primary font-weight-bold" >' . $reg->proveedor . '</span>',      
            "2" => date("d/m/Y", strtotime($reg->fecha_compra)),
            "3" => $reg->cantidad,
            "4" => '<b>' . number_format($reg->precio_igv, 2, ".", ",") . '</b>',
            "5" => 'S/. ' . number_format($reg->descuento, 2, ".", ","),
            "6" => 'S/. ' . number_format($reg->subtotal, 2, ".", ","),
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

      case 'formato_banco':
           
        $rspta=$proveedor->formato_banco($_POST["idbanco"]);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
         
      break;
      
      case 'listarMaterialescompra':     

        $rspta = $resumen_insumos->listar_productos();
        //Vamos a declarar un array
        $datas = [];
        // echo json_encode($rspta);
        $img = "";
        $imagen_error = "this.src='../dist/svg/default_producto.svg'";
        $color_stock = "";
        $ficha_tecnica = "";
    
        while ($reg = $rspta->fetch_object()) {
    
          if (!empty($reg->imagen)) {   $img = "../dist/docs/material/img_perfil/$reg->imagen"; } else { $img = "../dist/svg/default_producto.svg"; }
    
          !empty($reg->ficha_tecnica) ? ($ficha_tecnica = '<center><a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg->ficha_tecnica . '"><i class="far fa-file-pdf fa-2x text-success"></i></a></center>') : ($ficha_tecnica = '<center><span class="text-center"> <i class="far fa-times-circle fa-2x text-danger"></i></span></center>');
          
          $datas[] = [
            "0" => '<button class="btn btn-warning" onclick="agregarDetalleComprobante(' . $reg->idproducto . ', \'' . htmlspecialchars($reg->nombre, ENT_QUOTES) . '\', \'' . $reg->nombre_medida . '\', \'' . $reg->nombre_color . '\', \'' . $reg->precio_sin_igv . '\', \'' . $reg->precio_igv . '\', \'' . $reg->precio_total . '\', \'' . $reg->imagen . '\', \'' . $reg->ficha_tecnica . '\')" data-toggle="tooltip" data-original-title="Agregar Planta">
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
            "5" => $ficha_tecnica,
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

      case 'ver_compra_editar':
        $rspta = $compra->mostrar_compra_para_editar($idcompra_proyecto);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    
      break;
      
      case 'suma_total_compras':
        $idproyecto = $_POST["idproyecto"];

        $rspta = $resumen_insumos->suma_total_compras($idproyecto);

        echo json_encode($rspta);
      break;

      // SELECT2
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
ob_end_flush();
?>
