<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Resumen_activos_fijos_general
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  //Implementar un método para listar los registros
  public function listar_tbla_principal_general($clacificacion) {
    $data_productos = [];
    $sql_1 = "SELECT p.idproducto, p.nombre, p.imagen, p.precio_total as precio_actual, um.nombre_medida, c.nombre_color, p.modelo
		FROM producto as p, unidad_medida as um, color as c
		WHERE p.idunidad_medida=um.idunidad_medida AND p.idcolor=c.idcolor AND p.idcategoria_insumos_af='$clacificacion' ORDER BY p.nombre ASC";

    $producto = ejecutarConsultaArray($sql_1);

    if ($producto['status']) {
      if (!empty($producto['data'])) {
        foreach ($producto['data'] as $key => $value) {
          $cantidad = 0;
          $descuento = 0;
          $subtotal = 0;
          $promedio_precio = 0;
          $promedio_total = 0;
  
          $idproducto = $value['idproducto'];
          $sql_2 = "SELECT SUM(`cantidad`) as cantidad, SUM(`descuento`) as descuento, SUM(`subtotal`)  as subtotal,  AVG(`precio_con_igv`) AS promedio_precio 
          FROM `detalle_compra` WHERE idproducto=$idproducto";
          $compra_p = ejecutarConsultaSimpleFila($sql_2);
          if ( $compra_p['status'] == false ) { return $compra_p; }

          $cantidad += empty($compra_p['data']['cantidad']) ? 0 : floatval($compra_p['data']['cantidad']);
          $descuento += empty($compra_p['data']['descuento']) ? 0 : floatval($compra_p['data']['descuento']);
          $subtotal += empty($compra_p['data']['subtotal']) ? 0 : floatval($compra_p['data']['subtotal']);
          $promedio_precio += empty($compra_p['data']['promedio_precio']) ? 0 : floatval($compra_p['data']['promedio_precio']);
  
          $sql_3 = "SELECT SUM(`cantidad`) as cantidad, SUM(`descuento`) as descuento, SUM(`subtotal`)  as subtotal,  AVG(`precio_con_igv`) AS promedio_precio 
          FROM `detalle_compra_af_g` WHERE idproducto=$idproducto";
          $compra_af_g = ejecutarConsultaSimpleFila($sql_3);
          if ( $compra_af_g['status'] == false ) { return $compra_af_g; }
  
          $cantidad += empty($compra_af_g['data']['cantidad']) ? 0 : floatval($compra_af_g['data']['cantidad']);
          $descuento += empty($compra_af_g['data']['descuento']) ? 0 : floatval($compra_af_g['data']['descuento']);
          $subtotal += empty($compra_af_g['data']['subtotal']) ? 0 : floatval($compra_af_g['data']['subtotal']);
          $promedio_precio += empty($compra_af_g['data']['promedio_precio']) ? 0 : floatval($compra_af_g['data']['promedio_precio']);
  
          if ($compra_p['data']['promedio_precio'] != 0 && $compra_af_g['data']['promedio_precio']) {
            $promedio_total = $promedio_precio / 2;
          } else {
            $promedio_total = $promedio_precio;
          }
  
          if ($cantidad > 0) {
            $data_productos[] = [
              "idproducto" => $value['idproducto'],
              "nombre_producto" => $value['nombre'],
              "modelo" => $value['modelo'],
              "imagen" => $value['imagen'],
              "precio_actual" => $value['precio_actual'],
              "nombre_medida" => $value['nombre_medida'],
              "nombre_color" => $value['nombre_color'],
              "cantidad" => $cantidad,
              "descuento" => $descuento,
              "subtotal" => $subtotal,
              "promedio_precio" => $promedio_total,
            ];
          }          
        }
      }
      return $retorno = ["status" => true, "message" => 'Todo oka', "data" => $data_productos,];
    } else {
      return $producto;
    }
  }

  public function ver_precios_y_mas($idproducto) {
    $data = [];
     
    $sql_1 = "SELECT  cafg.idcompra_af_general, cafg.fecha_compra, dcafg.ficha_tecnica_producto AS ficha_tecnica, cafg.comprobante,
		cafg.tipo_comprobante,cafg.serie_comprobante,pr.nombre AS nombre_producto, dcafg.cantidad, dcafg.precio_con_igv , dcafg.descuento , dcafg.subtotal, prov.razon_social AS proveedor
		FROM compra_af_general AS cafg, detalle_compra_af_g AS dcafg, producto AS pr,proveedor AS prov
		WHERE cafg.idcompra_af_general = dcafg.idcompra_af_general AND dcafg.idproducto = pr.idproducto AND cafg.estado = '1' AND cafg.idproveedor = prov.idproveedor 
		AND dcafg.idproducto = '$idproducto' ORDER BY cafg.fecha_compra DESC";

    $compra_af_general = ejecutarConsultaArray($sql_1);

    if ($compra_af_general['status']) {

      if (!empty($compra_af_general['data'])) {       

        foreach ($compra_af_general['data'] as $key => $value) {

         // $cantidad_comprobantes = (!empty($value['cantid_comprobantes'])) ? $value['cantid_comprobantes'] : '' ;
          $data[] = [
            'idproyecto' => '',
            'idcompra' => $value['idcompra_af_general'],
            'fecha_compra' => $value['fecha_compra'],
            'ficha_tecnica' => $value['ficha_tecnica'],
            'tipo_comprobante' => $value['tipo_comprobante'],
            'serie_comprobante' => $value['serie_comprobante'],
            'nombre_producto' => $value['nombre_producto'],
            'cantidad' => $value['cantidad'],
            'precio_con_igv' => $value['precio_con_igv'],
            'descuento' => $value['descuento'],
            'subtotal' => $value['subtotal'],
            'proveedor' => $value['proveedor'],
            'cant_comprobantes' => $value['comprobante'],
            'modulo' => 'Compras de Activo Fijo',
          ];
        }
      }
    } else {
      return $compra_af_general;
    }

    $sql_2 = "SELECT cpp.idproyecto,cpp.idcompra_proyecto, cpp.fecha_compra, dc.ficha_tecnica_producto AS ficha_tecnica, cpp.tipo_comprobante, cpp.serie_comprobante,
		pr.nombre AS nombre_producto, dc.cantidad, dc.precio_con_igv, dc.descuento, dc.subtotal, prov.razon_social AS proveedor
		FROM proyecto AS p, compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr, proveedor AS prov
		WHERE p.idproyecto = cpp.idproyecto AND cpp.idcompra_proyecto = dc.idcompra_proyecto 
		AND dc.idproducto = pr.idproducto AND cpp.estado = '1' AND cpp.idproveedor = prov.idproveedor 
		AND dc.idproducto = '$idproducto' ORDER BY cpp.fecha_compra DESC";

    $compras_proyecto = ejecutarConsultaArray($sql_2);

    if ($compras_proyecto['status']) {
      if (!empty($compras_proyecto['data'])) {
        foreach ($compras_proyecto['data'] as $key => $value) {

          $idcompra_proyecto = $value['idcompra_proyecto'];
    
          $sql3 = "SELECT COUNT(comprobante) as cant_comprobantes FROM factura_compra_insumo WHERE idcompra_proyecto='$idcompra_proyecto' AND estado='1' AND estado_delete='1'";
          $cant_comprobantes = ejecutarConsultaSimpleFila($sql3);
          if ($cant_comprobantes['status'] == false) { return $cant_comprobantes; }


          $data[] = [
            'idproyecto' => $value['idproyecto'],
            'idcompra' => $value['idcompra_proyecto'],
            'fecha_compra' => $value['fecha_compra'],
            'ficha_tecnica' => $value['ficha_tecnica'],            
            'tipo_comprobante' => $value['tipo_comprobante'],
            'serie_comprobante' => $value['serie_comprobante'],
            'nombre_producto' => $value['nombre_producto'],
            'cantidad' => $value['cantidad'],
            'precio_con_igv' => $value['precio_con_igv'],
            'descuento' => $value['descuento'],
            'subtotal' => $value['subtotal'],
            'proveedor' => $value['proveedor'],
            'cant_comprobantes' => (empty($cant_comprobantes['data']['cant_comprobantes']) ? 0 : floatval($cant_comprobantes['data']['cant_comprobantes']) ),
            'modulo' => 'Compras de Insumo',
          ];
        }
      }
    } else {
      return $compras_proyecto;
    }

    return $retorno = ['status'=> true, 'message'=> 'todo oka', 'data'=> $data,];
  }

  public function suma_total_compras($clacificacion) {
    $data_totales = [];
    $cantidad = 0;
    $subtotal = 0;

    $sql_1 = "SELECT p.idproducto,p.nombre,p.imagen,p.precio_total as precio_actual,um.nombre_medida, c.nombre_color
		FROM producto as p, unidad_medida as um, color as c
		WHERE p.idunidad_medida=um.idunidad_medida AND p.idcolor=c.idcolor AND p.idcategoria_insumos_af='$clacificacion'";
    $producto = ejecutarConsultaArray($sql_1);

    if ($producto['status']) {
      if (!empty($producto['data'])) {
        foreach ($producto['data'] as $key => $value) {

          $idproducto = $value['idproducto'];
          $sql_2 = "SELECT SUM(`cantidad`) as cantidad, SUM(`descuento`) as descuento, SUM(`subtotal`)  as subtotal,  AVG(`precio_con_igv`) AS promedio_precio 
          FROM `detalle_compra` WHERE idproducto=$idproducto";
          $compra_p = ejecutarConsultaSimpleFila($sql_2);
          if ($compra_p['status'] == false) { return $compra_p; }
  
          $cantidad += empty($compra_p['data']['cantidad']) ? 0 : floatval($compra_p['data']['cantidad']);
          $subtotal += empty($compra_p['data']['subtotal']) ? 0 : floatval($compra_p['data']['subtotal']);
  
          $sql_3 = "SELECT SUM(`cantidad`) as cantidad, SUM(`descuento`) as descuento, SUM(`subtotal`)  as subtotal,  AVG(`precio_con_igv`) AS promedio_precio 
          FROM `detalle_compra_af_g` WHERE idproducto=$idproducto";
          $compra_af_g = ejecutarConsultaSimpleFila($sql_3);
          if ($compra_af_g['status'] == false) { return $compra_af_g; }
  
          $cantidad += empty($compra_af_g['data']['cantidad']) ? 0 : floatval($compra_af_g['data']['cantidad']);
          $subtotal += empty($compra_af_g['data']['subtotal']) ? 0 : floatval($compra_af_g['data']['subtotal']);
        }
      }

      $data_totales = [ "total_cantidad" => $cantidad, "total_monto" => $subtotal, ];

      return $retorno = [ "status" => true, "message" =>'todo ok', "data" => $data_totales ];

    } else {
      return $producto;
    }
    
  }

  public function sumas_factura_x_material( $idproducto_) {
    $data_productos = [];
    $cantidad = 0;
    $descuento = 0;
    $subtotal = 0;
    $promedio_precio = 0;
    $promedio_total = 0;

    $sql_1 = "SELECT p.idproducto,p.nombre,p.imagen,p.precio_total as precio_actual,um.nombre_medida, c.nombre_color, p.modelo
		FROM producto as p, unidad_medida as um, color as c
		WHERE p.idunidad_medida=um.idunidad_medida AND p.idcolor=c.idcolor AND p.idproducto = '$idproducto_'";

    $producto = ejecutarConsultaSimpleFila($sql_1);

    if ($producto['status']) {
      if (!empty($producto['data'])) {
  
        $idproducto = $producto['data']['idproducto'];
        $sql_2 = "SELECT SUM(`cantidad`) as cantidad, SUM(`descuento`) as descuento, SUM(`subtotal`)  as subtotal,  AVG(`precio_con_igv`) AS promedio_precio 
        FROM `detalle_compra` WHERE idproducto=$idproducto";
        $compra_p = ejecutarConsultaSimpleFila($sql_2);
        if ($compra_p['status'] == false) { return $compra_p; }
  
        $cantidad += empty($compra_p['data']['cantidad']) ? 0 : floatval($compra_p['data']['cantidad']);
        $descuento += empty($compra_p['data']['descuento']) ? 0 : floatval($compra_p['data']['descuento']);
        $subtotal += empty($compra_p['data']['subtotal']) ? 0 : floatval($compra_p['data']['subtotal']);
        $promedio_precio += empty($compra_p['data']['promedio_precio']) ? 0 : floatval($compra_p['data']['promedio_precio']);
  
        $sql_3 = "SELECT SUM(`cantidad`) as cantidad, SUM(`descuento`) as descuento, SUM(`subtotal`)  as subtotal,  AVG(`precio_con_igv`) AS promedio_precio 
        FROM `detalle_compra_af_g` WHERE idproducto=$idproducto";
        $compra_af_g = ejecutarConsultaSimpleFila($sql_3);
        if ($compra_af_g['status'] == false) { return $compra_af_g; }
  
        $cantidad += empty($compra_af_g['data']['cantidad']) ? 0 : floatval($compra_af_g['data']['cantidad']);
        $descuento += empty($compra_af_g['data']['descuento']) ? 0 : floatval($compra_af_g['data']['descuento']);
        $subtotal += empty($compra_af_g['data']['subtotal']) ? 0 : floatval($compra_af_g['data']['subtotal']);
        $promedio_precio += empty($compra_af_g['data']['promedio_precio']) ? 0 : floatval($compra_af_g['data']['promedio_precio']);
  
        if ($compra_p['data']['promedio_precio'] != 0 && $compra_af_g['data']['promedio_precio']) {
          $promedio_total = $promedio_precio / 2;
        } else {
          $promedio_total = $promedio_precio;
        }
  
        if ($cantidad > 0) {
          $data_productos = [
            "idproducto" => $producto['data']['idproducto'],
            "nombre_producto" => $producto['data']['nombre'],
            "modelo" => $producto['data']['modelo'],
            "imagen" => $producto['data']['imagen'],
            "precio_actual" => $producto['data']['precio_actual'],
            "nombre_medida" => $producto['data']['nombre_medida'],
            "nombre_color" => $producto['data']['nombre_color'],
            "cantidad" => $cantidad,
            "descuento" => strval($descuento),
            "subtotal" =>$subtotal,
            "precio_promedio" => $promedio_total,
          ];
        }        
      }

      return $retorno = [ "status" => true, "message" =>'todo ok', "data" => $data_productos];

    } else {
      return $producto;
    }
    
  }

  //.METODOS PARA EDITAR COMPRA POR PROYECTO
  //Listamos los productos a selecionar
  public function listar_insumos_activo_general() {
    $sql = "SELECT
				p.idproducto AS idproducto,
				p.idunidad_medida AS idunidad_medida,
				p.idcolor AS idcolor,
				p.nombre AS nombre,
				p.marca AS marca,
				ciaf.nombre AS categoria,
				p.descripcion AS descripcion,
				p.imagen AS imagen,
				p.estado_igv AS estado_igv,
				p.precio_unitario AS precio_unitario,
				p.precio_igv AS precio_igv,
				p.precio_sin_igv AS precio_sin_igv,
				p.precio_total AS precio_total,
				p.ficha_tecnica AS ficha_tecnica,
				p.estado AS estado,
				c.nombre_color AS nombre_color,
				um.nombre_medida AS nombre_medida
			FROM producto p, unidad_medida AS um, color AS c, categoria_insumos_af AS ciaf
			WHERE um.idunidad_medida=p.idunidad_medida  AND c.idcolor=p.idcolor  AND ciaf.idcategoria_insumos_af = p.idcategoria_insumos_af
			ORDER BY p.nombre ASC";

    return ejecutarConsulta($sql);
  }

  //.METODOS PARA EDITAR COMPRA POR PROYECTO
  //Listamos los productos a selecionar
  public function listar_solo_activos() {
    $sql = "SELECT
				p.idproducto AS idproducto,
				p.idunidad_medida AS idunidad_medida,
				p.idcolor AS idcolor,
				p.nombre AS nombre,
				p.marca AS marca,
				ciaf.nombre AS categoria,
				p.descripcion AS descripcion,
				p.imagen AS imagen,
				p.estado_igv AS estado_igv,
				p.precio_unitario AS precio_unitario,
				p.precio_igv AS precio_igv,
				p.precio_sin_igv AS precio_sin_igv,
				p.precio_total AS precio_total,
				p.ficha_tecnica AS ficha_tecnica,
				p.estado AS estado,
				c.nombre_color AS nombre_color,
				um.nombre_medida AS nombre_medida
			FROM producto p, unidad_medida AS um, color AS c, categoria_insumos_af AS ciaf
			WHERE um.idunidad_medida=p.idunidad_medida  AND c.idcolor=p.idcolor  AND ciaf.idcategoria_insumos_af = p.idcategoria_insumos_af AND p.idcategoria_insumos_af != '1'
			ORDER BY p.nombre ASC";

    return ejecutarConsulta($sql);
  }

  //Implementamos un método para editar registros
  public function editar_por_proyecto(
    $idcompra_proyecto,
    $idproyecto,
    $idproveedor,
    $fecha_compra,
    $tipo_comprovante,
    $serie_comprovante,
    $descripcion,
    $total_venta,
    $subtotal_compra,
    $igv_compra,
    $estado_detraccion,
    $idproducto,
    $unidad_medida,
    $nombre_color,
    $cantidad,
    $precio_sin_igv,
    $precio_igv,
    $precio_total,
    $descuento,
    $ficha_tecnica_producto ) {

    if ($idcompra_proyecto != "") {
      //Eliminamos todos los permisos asignados para volverlos a registrar
      $sqldel = "DELETE FROM detalle_compra WHERE idcompra_proyecto='$idcompra_proyecto';";
      ejecutarConsulta($sqldel);

      $sql = "UPDATE compra_por_proyecto SET idproyecto = '$idproyecto', idproveedor = '$idproveedor', fecha_compra = '$fecha_compra',
            tipo_comprovante = '$tipo_comprovante', serie_comprovante = '$serie_comprovante', descripcion = '$descripcion',
            monto_total = '$total_venta', subtotal_compras_proyect = '$subtotal_compra', igv_compras_proyect = '$igv_compra', 
            estado_detraccion = '$estado_detraccion' WHERE idcompra_proyecto = '$idcompra_proyecto'";
      ejecutarConsulta($sql);

      $num_elementos = 0;
      $sw = true;

      while ($num_elementos < count($idproducto)) {
        $subtotal_producto = floatval($cantidad[$num_elementos]) * floatval($precio_total[$num_elementos]) + $descuento[$num_elementos];
        $sql_detalle = "INSERT INTO detalle_compra(idcompra_proyecto, idproducto, unidad_medida, color, cantidad, precio_venta, igv,  precio_con_igv, descuento, subtotal, ficha_tecnica_producto) 
                VALUES ('$idcompra_proyecto', '$idproducto[$num_elementos]', '$unidad_medida[$num_elementos]', '$nombre_color[$num_elementos]', '$cantidad[$num_elementos]', '$precio_sin_igv[$num_elementos]', '$precio_igv[$num_elementos]', '$precio_total[$num_elementos]', '$descuento[$num_elementos]', '$subtotal_producto', '$ficha_tecnica_producto[$num_elementos]')";
        ejecutarConsulta($sql_detalle) or ($sw = false);

        $num_elementos = $num_elementos + 1;
      }
    }

    if ($idcompra_proyecto != "") {
      return $sw;
    } else {
      return false;
    }
  }
}

?>
