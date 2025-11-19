<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Compra_insumos
{
  public $id_usr_sesion; public $id_proyecto_sesion;
  //Implementamos nuestro constructor
  public function __construct()
  {
    $this->id_usr_sesion =  isset($_SESSION['idusuario']) ? $_SESSION["idusuario"] : 0;
		$this->id_proyecto_sesion = isset($_SESSION['idproyecto'] ) ? $_SESSION['idproyecto']  : 0;
  }

  // ::::::::::::::::::::::::::::::::::::::::: S E C C I O N   C O M P R A  ::::::::::::::::::::::::::::::::::::::::: 

  //Implementamos un método para insertar registros
  public function insertar( $idproyecto, $idproveedor, $fecha_compra,  $tipo_comprobante,  $serie_comprobante,$slt2_serie_comprobante, $val_igv,  $descripcion, $glosa,
    $total_compra, $subtotal_compra, $igv_compra, $estado_detraccion, $idproducto, $unidad_medida,  $nombre_color, $nombre_marca,
    $cantidad, $precio_sin_igv, $precio_igv, $precio_con_igv, $descuento, $tipo_gravada, $ficha_tecnica_producto ) {    

    $sql_2 = "SELECT p.razon_social, p.tipo_documento, p.ruc, cpp.fecha_compra, cpp.tipo_comprobante, cpp.serie_comprobante, cpp.glosa, cpp.total, cpp.estado, cpp.estado_delete 
    FROM compra_por_proyecto as cpp, proveedor as p 
    WHERE cpp.idproveedor = p.idproveedor AND p.ruc = (SELECT ruc FROM proveedor WHERE idproveedor = '$idproveedor') AND cpp.tipo_comprobante ='$tipo_comprobante' AND cpp.serie_comprobante = '$serie_comprobante'";
    $compra_existe = ejecutarConsultaArray($sql_2);   if ($compra_existe['status'] == false) { return  $compra_existe;}

    if (empty($compra_existe['data']) || $tipo_comprobante == 'Ninguno') {
      $sql_3 = "INSERT INTO compra_por_proyecto(idproyecto, idproveedor, fecha_compra, tipo_comprobante, serie_comprobante,nc_serie_comprobante, val_igv, descripcion, glosa, total, subtotal, igv, tipo_gravada, estado_detraccion)
      VALUES ('$idproyecto', '$idproveedor', '$fecha_compra', '$tipo_comprobante', '$serie_comprobante','$slt2_serie_comprobante', '$val_igv', '$descripcion', '$glosa', '$total_compra', '$subtotal_compra', '$igv_compra', '$tipo_gravada', '$estado_detraccion')";
      $compra_new = ejecutarConsulta_retornarID($sql_3, 'C'); if ($compra_new['status'] == false) { return  $compra_new;}

      $ii = 0;
      $compra_detalle_new = "";

      if ( !empty($compra_new['data']) ) {
      
        while ($ii < count($idproducto)) {
          $id = $compra_new['data'];
          $subtotal_producto = (floatval($cantidad[$ii]) * floatval($precio_con_igv[$ii])) - $descuento[$ii];

          // ::::::::::: buscando grupo para asignar :::::::::::
          $sql_4 = "SELECT dc.idproducto, dc.idclasificacion_grupo
          from detalle_compra as dc
          INNER JOIN compra_por_proyecto as cpp on cpp.idcompra_proyecto = dc.idcompra_proyecto
          where cpp.idproyecto = '$idproyecto' and dc.idproducto = '$idproducto[$ii]' AND dc.idclasificacion_grupo != 1 
          GROUP BY dc.idproducto, dc.idclasificacion_grupo;";
          $grupo =  ejecutarConsultaSimpleFila($sql_4); if ($grupo['status'] == false) { return  $grupo;}
          $id_grupo = (empty($grupo['data']) ? 1 : (empty($grupo['data']['idclasificacion_grupo']) ? 1 : $grupo['data']['idclasificacion_grupo'] ) );

          $sql_detalle = "INSERT INTO detalle_compra(idcompra_proyecto, idproducto, idclasificacion_grupo, unidad_medida, color, marca, cantidad, precio_sin_igv, igv, precio_con_igv, descuento, subtotal, ficha_tecnica_producto, user_created) 
          VALUES ('$id','$idproducto[$ii]', '$id_grupo', '$unidad_medida[$ii]',  '$nombre_color[$ii]', '$nombre_marca[$ii]', '$cantidad[$ii]', '$precio_sin_igv[$ii]', '$precio_igv[$ii]', '$precio_con_igv[$ii]', '$descuento[$ii]', '$subtotal_producto', '$ficha_tecnica_producto[$ii]','$this->id_usr_sesion')";
          $compra_detalle_new =  ejecutarConsulta_retornarID($sql_detalle, 'C'); if ($compra_detalle_new['status'] == false) { return  $compra_detalle_new;}
          $id_dc = $compra_detalle_new['data'];

          // ::::::::::: Enviar a almacen :::::::::::
          $sql_ra = "SELECT * FROM almacen_resumen WHERE idproyecto = '$idproyecto' and idproducto ='$idproducto[$ii]'";
          $r_a = ejecutarConsultaArray($sql_ra); if ( $r_a['status'] == false) {return $r_a; } 
          
          $tipo =  ($id_grupo == '11' ? 'EPP' : 'PN' ) ; // buscamos el tipo
          if( empty($r_a['data']) ) {            
            $sql = "INSERT INTO almacen_resumen( idproyecto, idproducto, tipo, total_stok, total_ingreso) 
            VALUES ('$idproyecto', '$idproducto[$ii]', '$tipo', '$cantidad[$ii]', '$cantidad[$ii]' )";
            $ar = ejecutarConsulta_retornarID($sql, 'C'); if ( $ar['status'] == false) {return $ar; }
            $id_ar_ip = $ar['data'];

            $sql_2 = "INSERT INTO almacen_detalle( idalmacen_resumen, idproyecto_destino, idalmacen_general, iddetalle_compra, tipo_mov, marca, fecha, cantidad, descripcion)      
            VALUES ($id_ar_ip, $idproyecto, NULL, $id_dc, 'IPC', '$nombre_marca[$ii]', '$fecha_compra',  '$cantidad[$ii]', 'INGRESO DE COMPRAS')";         
            $new_entrada = ejecutarConsulta_retornarID($sql_2, 'C'); if ( $new_entrada['status'] == false) {return $new_entrada; }
          }else{
            foreach ($r_a['data'] as $key => $val) {
              $id_ar = $val['idalmacen_resumen'];
              $sql = "UPDATE almacen_resumen SET idproducto='$idproducto[$ii]', tipo='$tipo', total_stok= total_stok + $cantidad[$ii] , total_ingreso= total_ingreso + $cantidad[$ii]
              WHERE idalmacen_resumen='$id_ar';";
              $ar = ejecutarConsulta($sql, 'U'); if ( $ar['status'] == false) {return $ar; }

              $sql_2 = "INSERT INTO almacen_detalle( idalmacen_resumen, idproyecto_destino, idalmacen_general, iddetalle_compra, tipo_mov, marca, fecha, cantidad, descripcion)      
              VALUES ($id_ar, $idproyecto, NULL, $id_dc, 'IPC', '$nombre_marca[$ii]', '$fecha_compra',  '$cantidad[$ii]', 'INGRESO DE COMPRAS')";         
              $new_entrada = ejecutarConsulta_retornarID($sql_2, 'C'); if ( $new_entrada['status'] == false) {return $new_entrada; }
            }
          }
          $ii = $ii + 1;
        }
      }
      return $compra_detalle_new;
    } else {

      $info_repetida = ''; 

      foreach ($compra_existe['data'] as $key => $value) {
        $info_repetida .= '<li class="text-left font-size-13px">
          <b class="font-size-18px text-danger">'.$value['tipo_comprobante'].': </b> <span class="font-size-18px text-danger">'.$value['serie_comprobante'].'</span><br>
          <b>Razón Social: </b>'.$value['razon_social'].'<br>
          <b>'.$value['tipo_documento'].': </b>'.$value['ruc'].'<br>          
          <b>Fecha: </b>'.format_d_m_a($value['fecha_compra']).'<br>
          <b>Total: </b>'.number_format($value['total'], 2, '.', ',').'<br>
          <b>Glosa: </b>'.$value['glosa'].'<br>
          <b>Papelera: </b>'.( $value['estado']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO') .' <b>|</b> 
          <b>Eliminado: </b>'. ($value['estado_delete']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO').'<br>
          <hr class="m-t-2px m-b-2px">
        </li>'; 
      }
      return $sw = array( 'status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ol>'.$info_repetida.'</ol>', 'id_tabla' => '' );      
    }      
  }

  //Implementamos un método para editar registros
  public function editar( $idcompra_proyecto, $idproyecto, $idproveedor, $fecha_compra, $tipo_comprobante, $serie_comprobante,$slt2_serie_comprobante, $val_igv,  
  $descripcion, $glosa, $total_venta, $subtotal_compra, $igv_compra, $estado_detraccion, $idproducto, $unidad_medida, $nombre_color, $nombre_marca,
  $cantidad, $precio_sin_igv, $precio_igv, $precio_con_igv, $descuento, $tipo_gravada, $ficha_tecnica_producto ) {

    if ( !empty($idcompra_proyecto) ) {
  
      # DEVOLVEMOS EL STOK
      $sqldel = "SELECT * FROM detalle_compra WHERE idcompra_proyecto='$idcompra_proyecto';";
      $get_compra = ejecutarConsultaArray($sqldel);  if ($get_compra['status'] == false) { return $get_compra; }
      foreach ($get_compra['data'] as $key => $val) {
        //Eliminamos todos los permisos asignados para volverlos a registrar
        $sqldel = "DELETE FROM almacen_detalle WHERE iddetalle_compra='".$val['iddetalle_compra']."';";
        $del_ad = ejecutarConsulta($sqldel);  if ($del_ad['status'] == false) { return $del_ad; }

        $sql = "UPDATE almacen_resumen SET  total_stok= total_stok - ".$val['cantidad']." , total_ingreso= total_ingreso - ".$val['cantidad']."
          WHERE idproyecto = '$idproyecto' and idproducto ='".$val['idproducto']."';";
          $ar = ejecutarConsulta($sql, 'U'); if ( $ar['status'] == false) {return $ar; }
      }
      
      //Eliminamos todos los permisos asignados para volverlos a registrar
      $sqldel = "DELETE FROM detalle_compra WHERE idcompra_proyecto='$idcompra_proyecto';";
      $delete_compra = ejecutarConsulta($sqldel);  if ($delete_compra['status'] == false) { return $delete_compra; }

      $sql = "UPDATE compra_por_proyecto SET idproyecto = '$idproyecto', idproveedor = '$idproveedor', fecha_compra = '$fecha_compra',
      tipo_comprobante = '$tipo_comprobante', serie_comprobante = '$serie_comprobante',nc_serie_comprobante='$slt2_serie_comprobante', val_igv = '$val_igv', descripcion = '$descripcion',
      glosa = '$glosa', total = '$total_venta', subtotal = '$subtotal_compra', igv = '$igv_compra', tipo_gravada = '$tipo_gravada',
      estado_detraccion = '$estado_detraccion',user_updated= '$this->id_usr_sesion' WHERE idcompra_proyecto = '$idcompra_proyecto'";
      $update_compra = ejecutarConsulta($sql, 'U'); if ($update_compra['status'] == false) { return $update_compra; }

      $ii = 0; $detalle_compra = "";

      while ($ii < count($idproducto)) {
        $subtotal_producto = (floatval($cantidad[$ii]) * floatval($precio_con_igv[$ii])) - $descuento[$ii];

        // buscando grupo
        $sql_4 = "SELECT dc.idproducto, dc.idclasificacion_grupo
        from detalle_compra as dc
        INNER JOIN compra_por_proyecto as cpp on cpp.idcompra_proyecto = dc.idcompra_proyecto
        where cpp.idproyecto = '$idproyecto' and dc.idproducto = '$idproducto[$ii]' AND dc.idclasificacion_grupo != 1 
        GROUP BY dc.idproducto, dc.idclasificacion_grupo;";        
        $grupo =  ejecutarConsultaSimpleFila($sql_4); if ($grupo['status'] == false) { return  $grupo;}
        $id_grupo = (empty($grupo['data']) ? 1 : (empty($grupo['data']['idclasificacion_grupo']) ? 1 : $grupo['data']['idclasificacion_grupo'] ) );

        $sql_detalle = "INSERT INTO detalle_compra(idcompra_proyecto, idproducto,	idclasificacion_grupo, unidad_medida, color, marca, cantidad, precio_sin_igv, igv, precio_con_igv, descuento, subtotal, ficha_tecnica_producto, user_created) 
        VALUES ('$idcompra_proyecto', '$idproducto[$ii]', '$id_grupo', '$unidad_medida[$ii]', '$nombre_color[$ii]', '$nombre_marca[$ii]', '$cantidad[$ii]', '$precio_sin_igv[$ii]', '$precio_igv[$ii]', '$precio_con_igv[$ii]', '$descuento[$ii]', '$subtotal_producto', '$ficha_tecnica_producto[$ii]','$this->id_usr_sesion')";
        $detalle_compra = ejecutarConsulta_retornarID($sql_detalle, 'C'); if ($detalle_compra['status'] == false) { return $detalle_compra; }
        $id_dc = $detalle_compra['data'];

        // ::::::::::: Enviar a almacen :::::::::::
        $sql_ra = "SELECT * FROM almacen_resumen WHERE idproyecto = '$idproyecto' and idproducto ='$idproducto[$ii]'";
        $r_a = ejecutarConsultaArray($sql_ra); if ( $r_a['status'] == false) {return $r_a; } 
        
        $tipo =  ($id_grupo == '11' ? 'EPP' : 'PN' ) ; // buscamos el tipo
        if( empty($r_a['data']) ) {            
          $sql = "INSERT INTO almacen_resumen( idproyecto, idproducto, tipo, total_stok, total_ingreso) 
          VALUES ('$idproyecto', '$idproducto[$ii]', '$tipo', '$cantidad[$ii]', '$cantidad[$ii]' )";
          $ar = ejecutarConsulta_retornarID($sql, 'C'); if ( $ar['status'] == false) {return $ar; }
          $id_ar_ip = $ar['data'];

          $sql_2 = "INSERT INTO almacen_detalle( idalmacen_resumen, idproyecto_destino, idalmacen_general, iddetalle_compra, tipo_mov, marca, fecha, cantidad, descripcion)      
          VALUES ($id_ar_ip, $idproyecto, NULL, $id_dc, 'IPC', '$nombre_marca[$ii]', '$fecha_compra',  '$cantidad[$ii]', 'INGRESO DE COMPRAS')";         
          $new_entrada = ejecutarConsulta_retornarID($sql_2, 'C'); if ( $new_entrada['status'] == false) {return $new_entrada; }
        }else{
          foreach ($r_a['data'] as $key => $val) {
            $id_ar = $val['idalmacen_resumen'];
            $sql = "UPDATE almacen_resumen SET idproducto='$idproducto[$ii]', tipo='$tipo', total_stok= total_stok + $cantidad[$ii] , total_ingreso= total_ingreso + $cantidad[$ii]
            WHERE idalmacen_resumen='$id_ar';";
            $ar = ejecutarConsulta($sql, 'U'); if ( $ar['status'] == false) {return $ar; }

            $sql_2 = "INSERT INTO almacen_detalle( idalmacen_resumen, idproyecto_destino, idalmacen_general, iddetalle_compra, tipo_mov, marca, fecha, cantidad, descripcion)      
            VALUES ($id_ar, $idproyecto, NULL, $id_dc, 'IPC', '$nombre_marca[$ii]', '$fecha_compra',  '$cantidad[$ii]', 'INGRESO DE COMPRAS')";         
            $new_entrada = ejecutarConsulta_retornarID($sql_2, 'C'); if ( $new_entrada['status'] == false) {return $new_entrada; }
          }
        }
        
        $ii = $ii + 1;
      }
      return $detalle_compra; 
    } else { 
      return $retorno = ['status'=>false, 'mesage'=>'no hay nada', 'data'=>'sin data', ]; 
    }
  }

  public function mostrar_compra_para_editar($id_compras_x_proyecto) {
    $data_producto = []; 
    $sql = "SELECT  cpp.idcompra_proyecto, cpp.idproyecto, cpp.idproveedor, cpp.fecha_compra, cpp.tipo_comprobante, cpp.serie_comprobante,cpp.nc_serie_comprobante, cpp.val_igv, 
    cpp.descripcion, cpp.glosa, cpp.subtotal, cpp.igv, cpp.total, cpp.estado_detraccion, cpp.estado
    FROM compra_por_proyecto as cpp
    WHERE idcompra_proyecto='$id_compras_x_proyecto';";
    $compra = ejecutarConsultaSimpleFila($sql);  if ($compra['status'] == false) { return $compra; }

    $sql_2 = "SELECT 	dc.idproducto, dc.ficha_tecnica_producto, dc.cantidad, dc.precio_sin_igv, dc.igv, dc.precio_con_igv,
		dc.descuento,	p.nombre as nombre_producto, p.imagen, dc.unidad_medida, dc.color, dc.marca, ciaf.nombre AS categoria
		FROM detalle_compra AS dc, producto AS p, unidad_medida AS um, color AS c, categoria_insumos_af AS ciaf
		WHERE p.idcategoria_insumos_af = ciaf.idcategoria_insumos_af  AND  dc.idproducto=p.idproducto AND p.idcolor = c.idcolor 
    AND p.idunidad_medida = um.idunidad_medida and idcompra_proyecto='$id_compras_x_proyecto';";
    $producto = ejecutarConsultaArray($sql_2);   if ($producto['status'] == false) { return $producto;  }

    foreach ($producto['data'] as $key => $val) {
      $id = $val['idproducto']; $marca = $val['marca']; $array_marca_id = []; $array_marca = []; $marca_html_option = ""; $array_marca_name = [];
      
      $sql3 = "SELECT dm.iddetalle_marca, m.idmarca, m.nombre_marca FROM detalle_marca as dm, marca as m WHERE dm.idmarca=m.idmarca AND dm.idproducto = '$id';";
      $detalle_marca = ejecutarConsultaArray($sql3); if ($detalle_marca['status'] == false) { return  $detalle_marca;}
      if ( empty($detalle_marca['data']) ) { 
        array_push($array_marca_id, '1' );
        $array_marca[] = [ 'id' => 1, 'nombre' => 'SIN MARCA', 'selected' => 'selected' ];
        $marca_html_option = '<option value="SIN MARCA" selected >SIN MARCA</option>';
        array_push($array_marca_name, 'SIN MARCA' );
      } else { 
        foreach ($detalle_marca['data'] as $key => $val2) { array_push($array_marca_id, $val2['idmarca'] ); }
        foreach ($detalle_marca['data'] as $key => $val2) { $array_marca[] = [ 'id' => $val2['idmarca'], 'nombre' => $val2['nombre_marca'], 'selected' => ( $marca == $val2['nombre_marca'] ? 'selected' : '' ) ]; }
        foreach ($detalle_marca['data'] as $key => $val2) { $marca_html_option .= '<option value="'.$val2['nombre_marca'].'" '.( $marca == $val2['nombre_marca'] ? 'selected' : '' ).' >'.$val2['nombre_marca'].'</option>'; }
        foreach ($detalle_marca['data'] as $key => $val2) { array_push($array_marca_name, $val2['nombre_marca'] ); }
      }
      
      

      $data_producto[] = [
        "idproducto"      => $val['idproducto'],
        "ficha_tecnica_producto" => $val['ficha_tecnica_producto'],
        "cantidad"        => $val['cantidad'],
        "precio_sin_igv"  => $val['precio_sin_igv'],
        "igv"             => $val['igv'],
        "precio_con_igv"  => $val['precio_con_igv'],
        "descuento"       => $val['descuento'],
        "nombre_producto" => $val['nombre_producto'],
        "imagen"          => $val['imagen'],
        "unidad_medida"   => $val['unidad_medida'],
        "color"           => $val['color'],
        "marca"           => $val['marca'],
        "categoria"       => $val['categoria'],

        'id_marca'        => $array_marca_id,
        'marcas'          => $array_marca_name,
        'array_marcas'    => $array_marca,
        'marca_html_option'=> $marca_html_option,
      ];
    }   

    $results = [
      "idcompra_x_proyecto" => $compra['data']['idcompra_proyecto'],      
      "idproyecto"          => $compra['data']['idproyecto'],
      "idproveedor"         => $compra['data']['idproveedor'],
      "fecha_compra"        => $compra['data']['fecha_compra'],
      "tipo_comprobante"    => $compra['data']['tipo_comprobante'],
      "serie_comprobante"   => $compra['data']['serie_comprobante'],
      "nc_serie_comprobante"=> $compra['data']['nc_serie_comprobante'],
      "val_igv"             => empty($compra['data'])? 0 : (empty($compra['data']['val_igv']) ? 0 : floatval($compra['data']['val_igv']) ) ,
      "descripcion"         => $compra['data']['descripcion'],
      "glosa"               => $compra['data']['glosa'],
      "subtotal"            => empty($compra['data'])? 0 : (empty($compra['data']['subtotal']) ? 0 : floatval($compra['data']['subtotal']) ) ,
      "igv"                 => empty($compra['data'])? 0 : (empty($compra['data']['igv']) ? 0 : floatval($compra['data']['igv']) ) ,
      "total"               => empty($compra['data'])? 0 : (empty($compra['data']['total']) ? 0 : floatval($compra['data']['total']) ) ,
      "estado_detraccion"   => $compra['data']['estado_detraccion'],
      "estado"              => $compra['data']['estado'],
      "producto"            => $data_producto,
    ];

    return $retorno = ["status" => true, "message" => 'todo oka', "data" => $results] ;
  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idcompra_proyecto) {
    # DEVOLVEMOS EL STOK
    $sqldel = "SELECT * FROM detalle_compra WHERE idcompra_proyecto='$idcompra_proyecto';";
    $get_compra = ejecutarConsultaArray($sqldel);  if ($get_compra['status'] == false) { return $get_compra; }
    foreach ($get_compra['data'] as $key => $val) {
      //Eliminamos todos los permisos asignados para volverlos a registrar
      $sqldel = "DELETE FROM almacen_detalle WHERE iddetalle_compra='".$val['iddetalle_compra']."';";
      $del_ad = ejecutarConsulta($sqldel);  if ($del_ad['status'] == false) { return $del_ad; }

      $sql = "UPDATE almacen_resumen SET  total_stok= total_stok - ".$val['cantidad']." , total_ingreso= total_ingreso - ".$val['cantidad']."
        WHERE idproyecto = '$this->id_proyecto_sesion' and idproducto ='".$val['idproducto']."';";
        $ar = ejecutarConsulta($sql, 'U'); if ( $ar['status'] == false) {return $ar; }
    }

    $sql = "UPDATE compra_por_proyecto SET estado='0',user_trash= '$this->id_usr_sesion' WHERE idcompra_proyecto='$idcompra_proyecto'";
		return ejecutarConsulta($sql, 'T');
		
  }

  //Implementamos un método para activar categorías
  public function activar($idcompra_por_proyecto) {
    $sql = "UPDATE compra_por_proyecto SET estado='1' WHERE idcompra_proyecto='$idcompra_por_proyecto'";
    return ejecutarConsulta($sql, 'RT');
  }

  //Implementamos un método para activar categorías
  public function eliminar($idcompra_por_proyecto) {

    # DEVOLVEMOS EL STOK
    $sqldel = "SELECT * FROM detalle_compra WHERE idcompra_proyecto='$idcompra_por_proyecto';";
    $get_compra = ejecutarConsultaArray($sqldel);  if ($get_compra['status'] == false) { return $get_compra; }
    foreach ($get_compra['data'] as $key => $val) {
      //Eliminamos todos los permisos asignados para volverlos a registrar
      $sqldel = "DELETE FROM almacen_detalle WHERE iddetalle_compra='".$val['iddetalle_compra']."';";
      $del_ad = ejecutarConsulta($sqldel);  if ($del_ad['status'] == false) { return $del_ad; }

      $sql = "UPDATE almacen_resumen SET  total_stok= total_stok - ".$val['cantidad']." , total_ingreso= total_ingreso - ".$val['cantidad']."
        WHERE idproyecto = '$this->id_proyecto_sesion' and idproducto ='".$val['idproducto']."';";
        $ar = ejecutarConsulta($sql, 'U'); if ( $ar['status'] == false) {return $ar; }
    }

    $sql = "UPDATE compra_por_proyecto SET estado_delete='0',user_delete= '$this->id_usr_sesion' WHERE idcompra_proyecto='$idcompra_por_proyecto'";
		return  ejecutarConsulta($sql, 'D');		
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idcompra_por_proyecto) {
    $sql = "SELECT * FROM compra_por_proyecto WHERE idcompra_por_proyecto='$idcompra_por_proyecto'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para listar los registros
  public function tbla_principal($nube_idproyecto, $fecha_1, $fecha_2, $id_proveedor, $comprobante) {

    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = ""; 

    if ( !empty($fecha_1) && !empty($fecha_2) ) { $filtro_fecha = "AND cpp.fecha_compra BETWEEN '$fecha_1' AND '$fecha_2'"; } else if (!empty($fecha_1)) { $filtro_fecha = "AND cpp.fecha_compra = '$fecha_1'"; }else if (!empty($fecha_2)) { $filtro_fecha = "AND cpp.fecha_compra = '$fecha_2'"; }    

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND cpp.idproveedor = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else { $filtro_comprobante = "AND cpp.tipo_comprobante = '$comprobante'"; } 

    $data = Array();
    $scheme_host=  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/admin_sevens/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');

    $sql = "SELECT cpp.idproyecto, cpp.idcompra_proyecto, cpp.idproveedor, cpp.fecha_compra, cpp.tipo_comprobante, cpp.serie_comprobante,	
    cpp.descripcion, cpp.total, cpp.comprobante, cpp.estado_detraccion, cpp.glosa, p.razon_social, p.telefono,	cpp.estado 
		FROM compra_por_proyecto as cpp, proveedor as p 
		WHERE cpp.idproyecto='$nube_idproyecto' AND cpp.idproveedor=p.idproveedor AND cpp.estado = '1' AND cpp.estado_delete = '1' $filtro_proveedor $filtro_comprobante $filtro_fecha
		ORDER BY cpp.fecha_compra DESC ";
    $compra = ejecutarConsultaArray($sql);
    if ($compra['status'] == false) { return $compra; }

    foreach ($compra['data'] as $key => $value) {
      $idcompra_proyecto = $value['idcompra_proyecto'];
      $sql2 = "SELECT SUM(monto) as total_pago_compras FROM pago_compras WHERE idcompra_proyecto='$idcompra_proyecto' AND estado='1' AND estado_delete='1'";
      $pagos = ejecutarConsultaSimpleFila($sql2);
      if ($pagos['status'] == false) { return $pagos; }

      $sql3 = "SELECT COUNT(comprobante) as cant_comprobantes FROM factura_compra_insumo WHERE idcompra_proyecto='$idcompra_proyecto' AND estado='1' AND estado_delete='1'";
      $cant_comprobantes = ejecutarConsultaSimpleFila($sql3);
      if ($cant_comprobantes['status'] == false) { return $cant_comprobantes; }

      $data[] = [
        'idproyecto'        => $value['idproyecto'],
        'idcompra_proyecto' => $value['idcompra_proyecto'],
        'idproveedor'       => $value['idproveedor'],
        'fecha_compra'      => $value['fecha_compra'],
        'tipo_comprobante'  => $value['tipo_comprobante'],
        'serie_comprobante' => $value['serie_comprobante'],
        'descripcion'       => $value['descripcion'],
        'total'             => $value['total'],
        'comprobante'       => $value['comprobante'],
        'estado_detraccion' => $value['estado_detraccion'],
        'glosa'             => $value['glosa'],
        'razon_social'      => $value['razon_social'],
        'telefono'          => $value['telefono'],
        'estado'            => $value['estado'],
        'total_pago_compras'=> (empty($pagos['data']['total_pago_compras']) ? 0 : floatval($pagos['data']['total_pago_compras']) ),
        'cant_comprobantes' => (empty($cant_comprobantes['data']['cant_comprobantes']) ? 0 : floatval($cant_comprobantes['data']['cant_comprobantes']) ),
      ];
    }

    return $retorno = ['status' => true, 'message' => 'todo ok pe.', 'data' =>$data, 'affected_rows' =>$compra['affected_rows'],  ] ;
  }

  //pago servicio
  public function pago_servicio($idcompra_proyecto) {

    $sql = "SELECT SUM(monto) as total_pago_compras
		FROM pago_compras 
		WHERE idcompra_proyecto='$idcompra_proyecto' AND estado='1' AND estado_delete='1'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para listar los registros x proveedor
  public function listar_compraxporvee($nube_idproyecto) {
    // $idproyecto=2;
    $data = [];
    $sql = "SELECT cpp.idproyecto as idproyecto, COUNT(cpp.idcompra_proyecto) as cantidad, p.idproveedor as idproveedor, p.razon_social as razon_social, p.telefono
		FROM compra_por_proyecto as cpp, proveedor as p 
		WHERE cpp.idproyecto='$nube_idproyecto' AND cpp.idproveedor=p.idproveedor AND cpp.estado = '1' AND cpp.estado_delete = '1'
    GROUP BY cpp.idproveedor ORDER BY p.razon_social ASC";
     $compraxporv = ejecutarConsultaArray($sql); if ($compraxporv['status'] == false) { return  $compraxporv;}
    //return ejecutarConsultaArray($sql);
    foreach ($compraxporv['data'] as $key => $value) {

      $id = $value['idproveedor'];
     //AND tipo_comprobante<>'Nota de Crédito'
      $sql2 = "SELECT  SUM(cpp.total) as total
      FROM compra_por_proyecto as cpp
      WHERE cpp.idproyecto='$nube_idproyecto' AND cpp.idproveedor='$id'  AND cpp.estado = '1' AND cpp.estado_delete = '1'
      AND tipo_comprobante<>'Nota de Crédito'";
      $total_1 = ejecutarConsultaSimpleFila($sql2); if ($total_1['status'] == false) { return  $total_1;} 

      $sql3 = "SELECT  SUM(cpp.total) as total_NC
      FROM compra_por_proyecto as cpp
      WHERE cpp.idproyecto='$nube_idproyecto' AND cpp.idproveedor='$id' AND tipo_comprobante='Nota de Crédito' AND cpp.estado = '1' AND cpp.estado_delete = '1'";
      $NC = ejecutarConsultaSimpleFila($sql3); if ($NC['status'] == false) { return  $NC;}

      $total_11  = empty($total_1['data']) ? 0 : (empty($total_1['data']['total']) ? 0 : floatval($total_1['data']['total']));
      $total_nc  = empty($NC['data']) ? 0 : (empty($NC['data']['total_NC']) ? 0 : floatval($NC['data']['total_NC']));

      $total = $total_11-$total_nc;

      $data[] = array(
        'orden'        => $key+1,
        'idproyecto'   =>$value['idproyecto'],
        'cantidad'     => $value['cantidad'],  
        'idproveedor'  => $value['idproveedor'], 
        'razon_social' => $value['razon_social'],       
        'telefono'     => $value['telefono'],
        'total'        => $total,
      );
    }

    return $retorno=['status'=>true, 'message'=>'todo oka ps', 'data'=>$data];      

  }

  //Implementar un método para listar los registros x proveedor
  public function listar_detalle_comprax_provee($idproyecto, $idproveedor) {

    $sql = "SELECT * FROM compra_por_proyecto 
    WHERE idproyecto='$idproyecto' AND idproveedor='$idproveedor' AND estado = '1' AND estado_delete = '1'";

    return ejecutarConsulta($sql);
  }

  //mostrar detalles uno a uno de la factura
  public function ver_detalle_compra($idcompra) {    

    $sql = "SELECT cpp.idcompra_proyecto, cpp.idproyecto, cpp.idproveedor, p.razon_social , p.tipo_documento, p.ruc, p.direccion, p.telefono, 
		cpp.fecha_compra, cpp.tipo_comprobante, cpp.serie_comprobante,cpp.nc_serie_comprobante, cpp.val_igv,	cpp.descripcion, cpp.glosa,	cpp.subtotal, cpp.igv, cpp.total, 
    cpp.tipo_gravada, cpp.estado, cpp.estado_detraccion
		FROM compra_por_proyecto as cpp, proveedor as p 
		WHERE idcompra_proyecto='$idcompra'  AND cpp.idproveedor = p.idproveedor;";

    $compra = ejecutarConsultaSimpleFila($sql); if ($compra['status'] == false) { return $compra; }

    $sql_2 = "SELECT dc.idproducto, dc.ficha_tecnica_producto  as ficha_tecnica_old, p.ficha_tecnica as ficha_tecnica_new,
		dc.cantidad, dc.unidad_medida, dc.color, dc.precio_sin_igv, dc.igv, dc.precio_con_igv, dc.descuento, dc.subtotal,
		p.nombre as nombre, p.imagen, ciaf.nombre AS categoria, um.abreviacion
		FROM detalle_compra AS dc, producto AS p, unidad_medida AS um, color AS c, categoria_insumos_af AS ciaf
		WHERE p.idcategoria_insumos_af = ciaf.idcategoria_insumos_af  AND  dc.idproducto=p.idproducto AND p.idcolor = c.idcolor 
    AND p.idunidad_medida = um.idunidad_medida and idcompra_proyecto='$idcompra';";

    $producto = ejecutarConsultaArray($sql_2);  if ($producto['status'] == false) { return $producto;  }

    $results = [
      "idcompra_x_proyecto" => $compra['data']['idcompra_proyecto'],      
      "idproyecto"          => $compra['data']['idproyecto'],      
      "fecha_compra"        => $compra['data']['fecha_compra'],
      "tipo_comprobante"    => $compra['data']['tipo_comprobante'],
      "serie_comprobante"   => $compra['data']['serie_comprobante'],
      "nc_serie_comprobante"=> $compra['data']['nc_serie_comprobante'],
      "val_igv"             => $compra['data']['val_igv'],
      "descripcion"         => $compra['data']['descripcion'],
      "glosa"               => $compra['data']['glosa'],
      "subtotal"            => $compra['data']['subtotal'],
      "igv"                 => $compra['data']['igv'],
      "total"               => $compra['data']['total'],
      "tipo_gravada"        => $compra['data']['tipo_gravada'],
      "estado_detraccion"   => $compra['data']['estado_detraccion'],
      "estado"              => $compra['data']['estado'],

      "idproveedor"         => $compra['data']['idproveedor'],
      "razon_social"        => $compra['data']['razon_social'],
      "tipo_documento"      => $compra['data']['tipo_documento'],
      "ruc"                 => $compra['data']['ruc'],
      "direccion"           => $compra['data']['direccion'],
      "telefono"            => $compra['data']['telefono'],

      "detalle_producto"    => $producto['data'],
    ];

    return $retorno = ["status" => true, "message" => 'todo oka', "data" => $results] ;
  }

  // ::::::::::::::::::::::::::::::::::::::::: S E C C I O N   P A G O S ::::::::::::::::::::::::::::::::::::::::: 


  // :::::::::::::::::::::::::: S E C C I O N   C O M P R O B A N T E  :::::::::::::::::::::::::: 
  public function tbla_comprobantes($id_compra) {
    //var_dump($idfacturacompra);die();
    $sql = "SELECT fci.idfactura_compra_insumo, fci.idcompra_proyecto, fci.comprobante, fci.estado, fci.estado_delete, fci.created_at, 
    fci.updated_at, cpp.tipo_comprobante, cpp.serie_comprobante, p.razon_social, cpp.fecha_compra
    FROM factura_compra_insumo as fci, compra_por_proyecto as cpp, proveedor as p
    WHERE fci.idcompra_proyecto = cpp.idcompra_proyecto AND cpp.idproveedor = p.idproveedor AND fci.idcompra_proyecto = '$id_compra' AND fci.estado=1 AND fci.estado_delete=1;";
    return ejecutarConsulta($sql);
  }

  public function agregar_comprobante( $id_compra_proyecto, $doc_comprobante ) {
    //var_dump($idfacturacompra);die();
    $sql = "INSERT INTO factura_compra_insumo ( idcompra_proyecto, comprobante, user_created ) 
    VALUES ( '$id_compra_proyecto', '$doc_comprobante','$this->id_usr_sesion')";
		$insertar =  ejecutarConsulta_retornarID($sql); 
		if ($insertar['status'] == false) {  return $insertar; } 
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('factura_compra_insumo','".$insertar['data']."','Comprobante registrado','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   

    return $insertar;
  }

  public function editar_comprobante($idfactura_compra_insumo, $doc_comprobante) {
    //var_dump($idfacturacompra);die();
    $sql = "UPDATE factura_compra_insumo SET comprobante='$doc_comprobante',user_updated= '$this->id_usr_sesion'
    WHERE idfactura_compra_insumo ='$idfactura_compra_insumo'";
		$editar= ejecutarConsulta($sql);

		if ($editar['status'] == false) {  return $editar; }
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('factura_compra_insumo','$idfactura_compra_insumo','Comprobante editado','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; } 
		
		return $editar;

  }

  // obtebnemos los DOCS para eliminar
  public function comprobantes_compra($id_compra) {
    $sql = "SELECT idfactura_compra_insumo, idcompra_proyecto, comprobante
    FROM factura_compra_insumo WHERE estado=1 AND estado_delete=1 AND idcompra_proyecto ='$id_compra'";
    return ejecutarConsultaArray($sql);
  }

  // obtebnemos los DOCS para eliminar
  public function obtener_comprobante($idfactura_compra_insumo) {
    $sql = "SELECT comprobante FROM factura_compra_insumo WHERE idfactura_compra_insumo ='$idfactura_compra_insumo'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para activar categorías
  public function eliminar_comprobante($idpago_compras) {
    $sql = "UPDATE factura_compra_insumo SET estado_delete='0',user_delete= '$this->id_usr_sesion' WHERE idfactura_compra_insumo ='$idpago_compras'";
		$eliminar =  ejecutarConsulta($sql);
		if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('factura_compra_insumo','$idpago_compras','Comprobante Eliminado','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
		
		return $eliminar;
  }

  //Implementamos un método para activar categorías
  public function desactivar_comprobante($idpago_compras) {
    $sql = "UPDATE factura_compra_insumo SET estado='0',user_trash= '$this->id_usr_sesion' WHERE idfactura_compra_insumo ='$idpago_compras'";
		$desactivar= ejecutarConsulta($sql);

		if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('factura_compra_insumo','".$idpago_compras."','Comprobante desactivado','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $desactivar;

  }

  // :::::::::::::::::::::::::: S E C C I O N   M A T E R I A L E S ::::::::::::::::::::::::::


  // ::::::::::::::::::::::::::::::::::::::::: S I N C R O N I Z A R  ::::::::::::::::::::::::::::::::::::::::: 
  public function sincronizar_comprobante() {
    $sql = "SELECT idcompra_proyecto, comprobante FROM compra_por_proyecto WHERE comprobante != 'null' AND comprobante != '';";
    $comprobantes = ejecutarConsultaArray($sql);
    if ($comprobantes == false) {  return $comprobantes; }

    foreach ($comprobantes['data'] as $key => $value) {
      $id_compra = $value['idcompra_proyecto']; $comprobante = $value['comprobante'];
      $sql2 = "INSERT INTO factura_compra_insumo ( idcompra_proyecto, comprobante ) VALUES ( '$id_compra', '$comprobante')";
      $factura_compra = ejecutarConsulta($sql2);
      if ($factura_compra == false) {  return $factura_compra; }
    }

    $sql3 = "SELECT	idcompra_proyecto, comprobante FROM factura_compra_insumo ;";
    $factura_compras = ejecutarConsultaArray($sql3);
    if ($factura_compras == false) {  return $factura_compras; }

    return $retorno = ['status'=>true, 'message'=>'todo oka', 'data'=>['comprobante'=>$comprobantes['data'],'factura_compras'=>$factura_compras['data'],], ];
  }  

/* ═════SERIES COMPROBANTES PARA NOTAS DE CREDITOS═══════════════ */
  public function select2_serie_comprobante($idproyecto)
  {
  $sql = "SELECT tipo_comprobante,serie_comprobante FROM compra_por_proyecto 
  WHERE (tipo_comprobante='Boleta' or tipo_comprobante ='Factura') and idproyecto='$idproyecto';";
  return ejecutarConsultaArray($sql); 
  }

}

?>
