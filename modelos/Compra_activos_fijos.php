<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Compra_activos_fijos
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  /* =========================== S E C C I O N   C O M P R A S   D E   A C T I V O S   F I J O S =========================== */
  
  //Implementamos un método para insertar registros
  public function insertar($idproveedor, $fecha_compra,  $tipo_comprobante,  $serie_comprobante, $val_igv,  $descripcion, $glosa,
    $total_compra, $subtotal_compra, $igv_compra, $estado_detraccion, $idproducto, $unidad_medida,  $nombre_color,
    $cantidad, $precio_sin_igv, $precio_igv, $precio_con_igv, $descuento, $tipo_gravada, $ficha_tecnica_producto ) {

    $sql_1 = "SELECT ruc FROM proveedor WHERE idproveedor ='$idproveedor';";
    $proveedor = ejecutarConsultaSimpleFila($sql_1);  if ($proveedor['status'] == false) { return  $proveedor;}

    $ruc = $proveedor['data']['ruc'];

    $sql_2 = "SELECT p.razon_social, p.tipo_documento, p.ruc, 
    cafg.fecha_compra, cafg.tipo_comprobante, cafg.serie_comprobante, cafg.glosa, cafg.total, cafg.estado, cafg.estado_delete 
    FROM compra_af_general as cafg, proveedor as p 
    WHERE cafg.idproveedor = p.idproveedor AND p.ruc ='$ruc' AND cafg.tipo_comprobante ='$tipo_comprobante' AND cafg.serie_comprobante = '$serie_comprobante'";
    $compra_existe = ejecutarConsultaArray($sql_2); if ($compra_existe['status'] == false) { return  $compra_existe;}

    if (empty($compra_existe['data']) || $tipo_comprobante == 'Ninguno') {

      $sql_3 = "INSERT INTO compra_af_general(idproveedor, fecha_compra, tipo_comprobante, serie_comprobante, val_igv, descripcion, glosa, subtotal, igv, total, tipo_gravada, user_created)
      VALUES ('$idproveedor', '$fecha_compra', '$tipo_comprobante', '$serie_comprobante', '$val_igv', '$descripcion', '$glosa', '$subtotal_compra', '$igv_compra', '$total_compra', '$tipo_gravada','" . $_SESSION['idusuario'] . "')";      
      $idcompra_af_generalnew = ejecutarConsulta_retornarID($sql_3); if ($idcompra_af_generalnew['status'] == false) { return  $idcompra_af_generalnew;}

      //add registro en nuestra bitacora
      $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('compra_af_general','".$idcompra_af_generalnew['data']."','Nueva compra de activo fijo registrada','" . $_SESSION['idusuario'] . "')";
      $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   


      $num_elementos = 0;
      $sw = true;     

      while ($num_elementos < count($idproducto)) {

        $subtotal_activo_g = (floatval($cantidad[$num_elementos]) * floatval($precio_con_igv[$num_elementos])) - $descuento[$num_elementos];

        $sql_detalle = "INSERT INTO detalle_compra_af_g(idcompra_af_general, idproducto, unidad_medida, color, ficha_tecnica_producto, cantidad, precio_sin_igv, igv, precio_con_igv, descuento, subtotal, user_created) 
        VALUES ('".$idcompra_af_generalnew['data']."','$idproducto[$num_elementos]', '$unidad_medida[$num_elementos]', '$nombre_color[$num_elementos]', '$ficha_tecnica_producto[$num_elementos]','$cantidad[$num_elementos]', '$precio_sin_igv[$num_elementos]', '$precio_igv[$num_elementos]', '$precio_con_igv[$num_elementos]', '$descuento[$num_elementos]', '$subtotal_activo_g','" . $_SESSION['idusuario'] . "')";
        $sw = ejecutarConsulta_retornarID($sql_detalle); if ($sw['status'] == false) {    return $sw ; }


        //add registro en nuestra bitacora
        $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('detalle_compra_af_g','".$sw['data']."','Detalle de la compra de activo fijo num registro ".$idcompra_af_generalnew['data']."','" . $_SESSION['idusuario'] . "')";
        $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   


        $num_elementos = $num_elementos + 1;
      }  
      return $sw;
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
  public function editar( $idcompra_af_general, $idproveedor, $fecha_compra,  $tipo_comprobante,  $serie_comprobante, $val_igv,  $descripcion, $glosa,
    $total_compra, $subtotal_compra, $igv_compra, $estado_detraccion, $idproducto, $unidad_medida,  $nombre_color,
    $cantidad, $precio_sin_igv, $precio_igv, $precio_con_igv, $descuento, $tipo_gravada, $ficha_tecnica_producto ) {      

    if ( !empty($idcompra_af_general) ) {
      //Eliminamos todos los permisos asignados para volverlos a registrar
      $sqldel = "DELETE FROM detalle_compra_af_g WHERE idcompra_af_general='$idcompra_af_general';";
      $delete = ejecutarConsulta($sqldel); if ( $delete['status'] == false ) { return $delete; }

      $sql = "UPDATE compra_af_general SET idproveedor='$idproveedor', fecha_compra='$fecha_compra', tipo_comprobante='$tipo_comprobante',
      serie_comprobante='$serie_comprobante', val_igv = '$val_igv', subtotal='$subtotal_compra', igv='$igv_compra', total='$total_compra', 
      tipo_gravada = '$tipo_gravada', descripcion='$descripcion', glosa = '$glosa',user_updated= '" . $_SESSION['idusuario'] . "'
      WHERE idcompra_af_general = '$idcompra_af_general'";
      $compra = ejecutarConsulta($sql); if ($compra['status'] == false ) { return $compra; }

      //add registro en nuestra bitacora
      $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('compra_af_general','$idcompra_af_general','Compra activo fijo editada','" . $_SESSION['idusuario'] . "')";
      $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }  


      $num_elementos = 0;

      while ($num_elementos < count($idproducto)) {

        $subtotal_activo_g = (floatval($cantidad[$num_elementos]) * floatval($precio_con_igv[$num_elementos])) - $descuento[$num_elementos];

        $sql_detalle = "INSERT INTO detalle_compra_af_g(idcompra_af_general, idproducto, unidad_medida, color, ficha_tecnica_producto, cantidad, precio_sin_igv, igv, precio_con_igv, descuento,subtotal) 
        VALUES ('$idcompra_af_general','$idproducto[$num_elementos]', '$unidad_medida[$num_elementos]',  '$nombre_color[$num_elementos]', '$ficha_tecnica_producto[$num_elementos]','$cantidad[$num_elementos]', '$precio_sin_igv[$num_elementos]', '$precio_igv[$num_elementos]', '$precio_con_igv[$num_elementos]', '$descuento[$num_elementos]', '$subtotal_activo_g')";
        $detalle_compra = ejecutarConsulta_retornarID($sql_detalle); if ($detalle_compra['status'] == false) { return $detalle_compra ; }


        //add registro en nuestra bitacora
        $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('detalle_compra_af_g','".$detalle_compra['data']."','Detalle de la compra de activo fijo num registro ".$idcompra_af_general."','" . $_SESSION['idusuario'] . "')";
        $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   


        $num_elementos = $num_elementos + 1;

      }

      return $detalle_compra;      
      
    }else{
      return $retorno = ['status' => false, 'data' =>'sin data' ];
    }
  }

  public function mostrar_compra_para_editar($idcompra_af_general) {
    $sql = "SELECT  cafg.idcompra_af_general, cafg.idproveedor, cafg.fecha_compra, cafg.tipo_comprobante, cafg.serie_comprobante, cafg.val_igv, 
    cafg.descripcion, cafg.glosa, cafg.subtotal, cafg.igv, cafg.total, cafg.estado 
    FROM compra_af_general as cafg
    WHERE cafg.idcompra_af_general='$idcompra_af_general'";

    $compra_af_general = ejecutarConsultaSimpleFila($sql);
    if ($compra_af_general['status'] == false) { return $compra_af_general;}

    $sql_2 = "SELECT dcafg.idproducto, dcafg.ficha_tecnica_producto, dcafg.cantidad, dcafg.precio_sin_igv, dcafg.igv, dcafg.precio_con_igv, 
    dcafg.descuento, dcafg.unidad_medida, dcafg.color, p.nombre as nombre_producto, p.imagen
    FROM detalle_compra_af_g as dcafg, producto as p
    WHERE dcafg.idcompra_af_general='$idcompra_af_general' AND  dcafg.idproducto=p.idproducto";

    $activos = ejecutarConsultaArray($sql_2);
    if ($activos['status'] == false ) { return $activos; }

    $results = [
      "idcompra_af_general" => $compra_af_general['data']['idcompra_af_general'],      
      "idproyecto" => '',
      "idproveedor" => $compra_af_general['data']['idproveedor'],
      "fecha_compra" => $compra_af_general['data']['fecha_compra'],
      "tipo_comprobante" => $compra_af_general['data']['tipo_comprobante'],
      "serie_comprobante" => $compra_af_general['data']['serie_comprobante'],
      "val_igv" => $compra_af_general['data']['val_igv'],
      "descripcion" => $compra_af_general['data']['descripcion'],
      "glosa" => $compra_af_general['data']['glosa'],
      "subtotal" => $compra_af_general['data']['subtotal'],
      "igv" => $compra_af_general['data']['igv'],
      "total" => $compra_af_general['data']['total'],
      "estado" => $compra_af_general['data']['estado'],
      "producto" => $activos['data'],
    ];

    return $retorno = ["status" => true, "message" => 'todo oka', "data" => $results];     
       
  }

  //Implementamos un método para desactivar categorías
  public function anular_compra($idcompra_af_general) {
    $sql = "UPDATE compra_af_general SET estado='0' ,user_trash= '" . $_SESSION['idusuario'] . "' WHERE idcompra_af_general='$idcompra_af_general'";
		$desactivar= ejecutarConsulta($sql);

		if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('compra_af_general','".$idcompra_af_general."','Compra activo general desactivado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $desactivar;
  }

  //Implementamos un método para eliminar compra activos fijos
  public function eliminar_compra($idcompra_af_general) {
    $sql = "UPDATE compra_af_general SET estado_delete='0',user_delete= '" . $_SESSION['idusuario'] . "'  WHERE idcompra_af_general='$idcompra_af_general'";
		$eliminar =  ejecutarConsulta($sql);
		if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('compra_af_general','$idcompra_af_general','Compra activo general Eliminado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
		
		return $eliminar;
  }

  //Implementar un método para listar los registros
  public function tbla_compra_activos_fijos() {
    $datos = [];

    $sql_1 = "SELECT cafg.idcompra_af_general, cafg.idproveedor, cafg.fecha_compra, cafg.tipo_comprobante, cafg.serie_comprobante, 
    cafg.descripcion, cafg.subtotal, cafg.igv, cafg.total, cafg.comprobante as imagen_comprobante, p.razon_social, p.telefono, cafg.estado, cafg.glosa
    FROM compra_af_general as cafg, proveedor as p 
    WHERE cafg.idproveedor=p.idproveedor AND cafg.estado=1  AND cafg.estado_delete=1
    ORDER BY cafg.idcompra_af_general DESC";

    $general = ejecutarConsultaArray($sql_1);

    if ($general['status']) {

      if (!empty($general['data'])) {

        foreach ($general['data'] as $key => $value) {
  
          $id_af_g = $value['idcompra_af_general'];
  
          $sql_1_2 = "SELECT SUM(monto) as total_pago_compras_af FROM pago_af_general WHERE idcompra_af_general='$id_af_g' AND estado=1  AND estado_delete=1";
          $total_pago = ejecutarConsultaSimpleFila($sql_1_2);
          
          if ($total_pago['status']) {
            $datos[] = [
              "idtabla"           => $value['idcompra_af_general'],
              "idproyecto"        => '',
              "idproveedor"       => $value['idproveedor'],
              "fecha_compra"      => $value['fecha_compra'],
              "tipo_comprobante"  => $value['tipo_comprobante'],
              "serie_comprobante" => $value['serie_comprobante'],
              "descripcion"       => $value['descripcion'],
              "subtotal"          => empty($value['subtotal']) ? '0' : $value['subtotal'],
              "igv"               => empty($value['igv']) ? '0' : $value['igv'],
              "total"             => empty($value['total']) ? '0' : $value['total'],
              "imagen_comprobante"=> $value['imagen_comprobante'],
              "razon_social"      => $value['razon_social'],
              "telefono"          => $value['telefono'],
              "estado"            => $value['estado'],
              "glosa"             => $value['glosa'],
              "codigo_proyecto"   => '',
              "deposito"          => ($reval1 = empty($total_pago['data']) ? '0' : ($dataelse1 = empty($total_pago['data']['total_pago_compras_af']) ? '0' : $total_pago['data']['total_pago_compras_af'])),
            ];
          } else {
            return $total_pago;
          }          
        }
      }
    } else {
      return $general;
    }
    

    $sql_2 = "SELECT  cpp.idproyecto, cpp.idcompra_proyecto, cpp.idproveedor, cpp.fecha_compra, cpp.tipo_comprobante, cpp.serie_comprobante,
    cpp.descripcion, cpp.subtotal, cpp.igv, cpp.total, cpp.comprobante as imagen_comprobante, p.razon_social, p.telefono, cpp.estado, 
    proy.nombre_proyecto, proy.nombre_codigo, cpp.glosa
    FROM compra_por_proyecto as cpp, proveedor as p, proyecto as proy
    WHERE cpp.idproveedor=p.idproveedor
    AND cpp.idproyecto=proy.idproyecto AND cpp.estado=1  AND cpp.estado_delete=1
    ORDER BY cpp.idcompra_proyecto DESC";

    $proyecto = ejecutarConsultaArray($sql_2);

    if ($proyecto['status']) {

      if (!empty($proyecto['data'])) {

        foreach ($proyecto['data'] as $key => $value) {
  
          $idcompra = $value['idcompra_proyecto'];
  
          $sql_2_2 = "SELECT SUM(monto) as total_pago_compras FROM pago_compras 
          WHERE idcompra_proyecto='$idcompra' AND estado=1  AND estado_delete=1";
          $total_pago = ejecutarConsultaSimpleFila($sql_2_2);
          if ($total_pago['status'] == false  ) { return $total_pago; }

          $sql_2_3 = "SELECT COUNT(dc.iddetalle_compra) as contador FROM detalle_compra dc, producto as p 
          WHERE idcompra_proyecto='$idcompra' AND dc.idproducto=p.idproducto AND p.idcategoria_insumos_af!=1";
          $detalle_factura = ejecutarConsultaSimpleFila($sql_2_3);          
          if ($detalle_factura['status'] == false ) { return $detalle_factura; }

          if (floatval($detalle_factura['data']['contador']) > 0) {
            $datos[] = [                
              "idtabla"           => $value['idcompra_proyecto'],
              "idproyecto"        => $value['idproyecto'],
              "idproveedor"       => $value['idproveedor'],
              "fecha_compra"      => $value['fecha_compra'],
              "tipo_comprobante"  => $value['tipo_comprobante'],
              "serie_comprobante" => $value['serie_comprobante'],
              "descripcion"       => $value['descripcion'],
              "subtotal"          => empty($value['subtotal']) ? '0' : $value['subtotal'],
              "igv"               => empty($value['igv']) ? '0' : $value['igv'],
              "total"             => empty($value['total']) ? '0' : $value['total'],
              "imagen_comprobante"=> $value['imagen_comprobante'],
              "razon_social"      => $value['razon_social'],
              "telefono"          => $value['telefono'],
              "estado"            => $value['estado'],
              "glosa"             => $value['glosa'],
              "codigo_proyecto"   => $value['nombre_codigo'],
              "deposito"          => ($reval2 = empty($total_pago['data']) ? '0' : ($dataelse2 = empty($total_pago['total_pago_compras']) ? '0' : $total_pago['total_pago_compras'])),
            ];
          }          
        }
      }
    } else {
      return $proyecto;
    }    

    return $retorno=["status" => true, "data"=>$datos, ];
  }

  /* =========================== S E C C I O N   C O M P R O B A N T E  C O M P R A =========================== */

  public function editar_comprobante_af_g($idcompra_af_general, $doc_comprobante) {
    //var_dump($idcompra_af_general,$doc_comprobante);die();
    $sql = "UPDATE compra_af_general SET comprobante='$doc_comprobante' ,user_updated= '" . $_SESSION['idusuario'] . "' WHERE idcompra_af_general ='$idcompra_af_general'";
    $edit= ejecutarConsulta($sql); if ($edit['status'] == false) {  return $edit; }

    //add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('compra_af_general','$idcompra_af_general','Actulización del comprobante','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }  
    return $edit;

  }

  // obtebnemos los DOCS para eliminar
  public function obtener_comprobante_af_g($idcompra_af_general) {
    $sql = "SELECT comprobante FROM compra_af_general WHERE idcompra_af_general ='$idcompra_af_general'";
    return ejecutarConsultaSimpleFila($sql);
  }

  /* =========================== S E C C I O N   C O M P R A S   P O R   P R O V E E D O R =========================== */

  //Implementar un método para listar los registros x proveedor
  public function tbla_compra_x_porveedor() {
    $total = 0;
    $totales_proveedor = [];

    $sq_l = "SELECT idproveedor, razon_social, ruc, tipo_documento FROM proveedor";
    $proveedor = ejecutarConsultaArray($sq_l);

    if ($proveedor['status']) {
      foreach ($proveedor['data'] as $key => $value) {
        $total = 0; $cont = 0;
        $id = $value['idproveedor'];
  
        // activo fijos general
        $sq_2 = "SELECT  SUM(total) as total_general, COUNT(idcompra_af_general) AS cont  FROM compra_af_general WHERE idproveedor=$id AND estado=1  AND estado_delete=1";
        $compra_general = ejecutarConsultaSimpleFila($sq_2);
        //  retornamos el error
        if ($compra_general['status'] == false ) { return $compra_general; }
        
        $total += empty($compra_general['data']) ? 0 : ($retVal = empty($compra_general['data']['total_general']) ? 0 : floatval($compra_general['data']['total_general']));
        $cont += empty($compra_general['data']) ? 0 : ($retVal = empty($compra_general['data']['cont']) ? 0 : floatval($compra_general['data']['cont']));
  
        $sql_3 = "SELECT `idcompra_proyecto` FROM `compra_por_proyecto` WHERE `idproveedor`='$id' AND  estado=1  AND estado_delete=1";
        $compras_proveedor = ejecutarConsultaArray($sql_3);
        //  retornamos el error
        if ($compras_proveedor['status'] == false ) { return $compras_proveedor; }

        foreach ($compras_proveedor['data'] as $key => $val) {
          $idcompra_proyecto = $val['idcompra_proyecto'];
  
          $sql_3_1 = "SELECT COUNT(dc.iddetalle_compra) as contador FROM detalle_compra dc, producto as p 
          WHERE idcompra_proyecto='$idcompra_proyecto' AND dc.idproducto=p.idproducto AND p.idcategoria_insumos_af!=1 AND dc.estado=1  AND dc.estado_delete=1";
          $detalle_factura = ejecutarConsultaSimpleFila($sql_3_1);
          //  retornamos el error
          if ($detalle_factura['status'] == false ) { return $detalle_factura; }

          if (floatval($detalle_factura['data']['contador']) > 0) {
            // activo fijos proyecto
            $sql_3 = "SELECT SUM(total) as total_proyecto, COUNT(idcompra_proyecto) AS cont FROM compra_por_proyecto WHERE idcompra_proyecto='$idcompra_proyecto'  AND estado=1  AND estado_delete=1";
            $compra_proyecto = ejecutarConsultaSimpleFila($sql_3);
            //  retornamos el error
            if ($compra_proyecto['status'] == false ) { return $compra_proyecto; }
  
            $total += empty($compra_proyecto['data']) ? 0 : ($retVal = empty($compra_proyecto['data']['total_proyecto']) ? 0 : floatval($compra_proyecto['data']['total_proyecto']));
            $cont += empty($compra_proyecto['data']) ? 0 : ($retVal = empty($compra_proyecto['data']['cont']) ? 0 : floatval($compra_proyecto['data']['cont']));
          }
        }
  
        if ($total > 0) {
          $totales_proveedor[] = [
            "idproveedor" => $value['idproveedor'],
            "razon_social" => $value['razon_social'],
            "ruc" => $value['ruc'],
            "tipo_documento" => $value['tipo_documento'],
            "total" => $total,
            "cont" => $cont,
          ];
        }
      }
      return $retorno=["status" => true, "data"=>$totales_proveedor, ] ;
    } else {
      return $proveedor;
    }
  }

  //Implementar un método para listar los registros x proveedor
  public function tbla_detalle_compra_x_porveedor($idproveedor) {
    $data = [];

    $sql_1 = "SELECT cafg.idcompra_af_general, cafg.idproveedor, cafg.fecha_compra, cafg.tipo_comprobante, cafg.serie_comprobante,
    cafg.descripcion, cafg.total, cafg.comprobante as imagen_comprobante, p.razon_social, p.telefono, cafg.estado as estado
    FROM compra_af_general as cafg, proveedor as p 
    WHERE cafg.idproveedor=p.idproveedor AND  cafg.idproveedor=$idproveedor  AND cafg.estado=1  AND cafg.estado_delete=1
    ORDER BY cafg.idcompra_af_general DESC";
    $compra_general = ejecutarConsultaArray($sql_1);

    if ($compra_general['status']) {

      if (!empty($compra_general['data'])) {
        foreach ($compra_general['data'] as $key => $value) {
          $data[] = [
            "idtabla" => $value['idcompra_af_general'],
            "idproyecto" => '',
            "idproveedor" => $value['idproveedor'],
            "fecha_compra" => $value['fecha_compra'],
            "tipo_comprobante" => $value['tipo_comprobante'],
            "serie_comprobante" => $value['serie_comprobante'],
            "descripcion" => $value['descripcion'],
            "total" => empty($value['total']) ? '0' : $value['total'],
            "imagen_comprobante" => $value['imagen_comprobante'],
            "razon_social" => $value['razon_social'],
            "telefono" => $value['telefono'],
            "estado" => $value['estado'],
            "codigo_proyecto" => '',
          ];
        }
      }
  
      $sql_2 = "SELECT cp.idproyecto, cp.idcompra_proyecto, cp.idproveedor, cp.fecha_compra, cp.tipo_comprobante, cp.serie_comprobante,
      cp.descripcion, cp.total, cp.comprobante as imagen_comprobante, p.razon_social, p.telefono, cp.estado, proy.nombre_proyecto,
      proy.nombre_codigo
      FROM compra_por_proyecto as cp, proveedor as p, proyecto as proy
      WHERE cp.idproveedor=p.idproveedor AND  cp.idproveedor=$idproveedor AND cp.estado=1  AND cp.estado_delete=1
      AND cp.idproyecto=proy.idproyecto ORDER BY cp.idcompra_proyecto DESC";  
      $compra_insumo = ejecutarConsultaArray($sql_2);

      if ($compra_insumo['status']) {

        if (!empty($compra_insumo['data'])) {
  
          foreach ($compra_insumo['data'] as $key => $value) {
    
            $idcompra_proyecto = $value['idcompra_proyecto'];
    
            $sql_3_1 = "SELECT COUNT(dc.iddetalle_compra) as contador FROM detalle_compra dc, producto as p 
            WHERE idcompra_proyecto='$idcompra_proyecto' AND dc.idproducto=p.idproducto AND p.idcategoria_insumos_af!=1 AND dc.estado=1  AND dc.estado_delete=1";
            $detalle_factura = ejecutarConsultaSimpleFila($sql_3_1);
            
            if ($detalle_factura['status']) {
              if (floatval($detalle_factura['data']['contador']) > 0) {
                $data[] = [
                  "idtabla" => $value['idcompra_proyecto'],
                  "idproyecto" => $value['idproyecto'],
                  "idproveedor" => $value['idproveedor'],
                  "fecha_compra" => $value['fecha_compra'],
                  "tipo_comprobante" => $value['tipo_comprobante'],
                  "serie_comprobante" => $value['serie_comprobante'],
                  "descripcion" => $value['descripcion'],
                  "total" => empty($value['total']) ? '0' : $value['total'],
                  "imagen_comprobante" => $value['imagen_comprobante'],
                  "razon_social" => $value['razon_social'],
                  "telefono" => $value['telefono'],
                  "estado" => $value['estado'],
                  "codigo_proyecto" => $value['nombre_codigo'],
                ];
              }
              
            } else {
              return $detalle_factura;
            }            
          }
        }
        return $retorno = ["status" => true,  "data" => $data] ;
      } else {
        return $compra_insumo;
      }
    } else {
      return $compra_general;
    }        
  }

  //mostrar detalles uno a uno de la factura
  public function ver_detalle_compra($idcompra) {
    

    $sql = "SELECT  cafg.idcompra_af_general, cafg.idproveedor, cafg.fecha_compra,	cafg.tipo_comprobante, cafg.serie_comprobante,
		cafg.descripcion, cafg.subtotal, cafg.igv,	cafg.total,	p.razon_social, p.tipo_documento, p.ruc, p.direccion, p.telefono,	
    cafg.estado, cafg.glosa, cafg.tipo_gravada, cafg.val_igv
    FROM compra_af_general as cafg, proveedor as p 
    WHERE  cafg.idproveedor=p.idproveedor AND cafg.idcompra_af_general='$idcompra'";

    $compra = ejecutarConsultaSimpleFila($sql); if ($compra['status'] == false) { return $compra;}

    $sql_2 = "SELECT dcafg.idproducto, dcafg.ficha_tecnica_producto as ficha_tecnica_old, p.ficha_tecnica as ficha_tecnica_new,	dcafg.cantidad, dcafg.precio_sin_igv,	
    dcafg.igv, dcafg.precio_con_igv, dcafg.descuento, dcafg.subtotal,	p.nombre, p.imagen, dcafg.unidad_medida,
    ciaf.nombre as clasificacion
		FROM detalle_compra_af_g as dcafg, producto as p, categoria_insumos_af as ciaf
		WHERE dcafg.idproducto=p.idproducto AND p.idcategoria_insumos_af = ciaf.idcategoria_insumos_af AND dcafg.idcompra_af_general='$idcompra' ";

    $activos = ejecutarConsultaArray($sql_2); if ($activos['status'] == false ) { return $activos; }

    $results = [
      "idcompra_af_general" => $compra['data']['idcompra_af_general'],  
      "idproveedor"         => $compra['data']['idproveedor'],
      "fecha_compra"        => $compra['data']['fecha_compra'],
      "tipo_comprobante"    => $compra['data']['tipo_comprobante'],
      "serie_comprobante"   => $compra['data']['serie_comprobante'],
      "val_igv"             => $compra['data']['val_igv'],
      "descripcion"         => $compra['data']['descripcion'],
      "glosa"               => $compra['data']['glosa'],
      "subtotal"            => $compra['data']['subtotal'],
      "igv"                 => $compra['data']['igv'],
      "total"               => $compra['data']['total'],
      "tipo_gravada"        => $compra['data']['tipo_gravada'],
      "estado"              => $compra['data']['estado'],

      "idproveedor"         => $compra['data']['idproveedor'],
      "razon_social"        => $compra['data']['razon_social'],
      "tipo_documento"      => $compra['data']['tipo_documento'],
      "ruc"                 => $compra['data']['ruc'],
      "direccion"           => $compra['data']['direccion'],
      "telefono"            => $compra['data']['telefono'],

      "detalle_producto" => $activos['data'],
    ];

    return $retorno = ["status" => true, "message" => 'todo oka', "data" => $results];     
  }

  /* =========================== S E C C I O N   P A G O S =========================== */

  public function insertar_pago($idcompra_af_general_p, $beneficiario_pago, $forma_pago, $tipo_pago, $cuenta_destino_pago, $banco_pago, $titular_cuenta_pago, $fecha_pago, $monto_pago, $numero_op_pago, $descripcion_pago, $imagen1) {
    $sql = "INSERT INTO pago_af_general(idcompra_af_general,beneficiario,forma_pago,tipo_pago,cuenta_destino,idbancos,
    titular_cuenta,fecha_pago,monto,numero_operacion,descripcion,imagen, user_created) 
    VALUES('$idcompra_af_general_p', '$beneficiario_pago', '$forma_pago', '$tipo_pago', '$cuenta_destino_pago', '$banco_pago',
    '$titular_cuenta_pago', '$fecha_pago', '$monto_pago', '$numero_op_pago', '$descripcion_pago', '$imagen1','" . $_SESSION['idusuario'] . "')";
		$insertar =  ejecutarConsulta_retornarID($sql); 
		if ($insertar['status'] == false) {  return $insertar; } 
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('pago_af_general','".$insertar['data']."','Nuevo pago a la compra de activos fijo con id  ".$idcompra_af_general_p."','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; } 

    return $insertar;
  }

  //Implementamos un método para editar registros
  public function editar_pago( $idpago_af_general, $idcompra_af_general_p, $beneficiario_pago, $forma_pago, $tipo_pago, $cuenta_destino_pago, $banco_pago,  $titular_cuenta_pago, $fecha_pago, $monto_pago, $numero_op_pago, $descripcion_pago, $imagen1 ) {
    $sql = "UPDATE pago_af_general SET
        idcompra_af_general ='$idcompra_af_general_p',
        beneficiario='$beneficiario_pago',
        forma_pago='$forma_pago',
        tipo_pago='$tipo_pago',
        cuenta_destino='$cuenta_destino_pago',
        idbancos='$banco_pago',
        titular_cuenta='$titular_cuenta_pago',
        fecha_pago='$fecha_pago',
        monto='$monto_pago',
        numero_operacion='$numero_op_pago',
        descripcion='$descripcion_pago',
        imagen='$imagen1',
        user_updated= '" . $_SESSION['idusuario'] . "'
        WHERE idpago_af_general='$idpago_af_general'";
        $editar =  ejecutarConsulta($sql);
        if ( $editar['status'] == false) {return $editar; }
    		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('pago_af_general','$idpago_af_general','Pago editado a la compra de activos fijo con id ".$idcompra_af_general_p."','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; } 
    return $editar; 
  }

  //Listar pagos-normal
  public function tbla_pagos_activo_fijo($idcompra_af_general) {
    $sql = "SELECT
            pafg.idpago_af_general  as idpago_af_general,
            pafg.forma_pago as forma_pago,
            pafg.tipo_pago as tipo_pago,
            pafg.beneficiario as beneficiario,
            pafg.cuenta_destino as cuenta_destino,
            pafg.titular_cuenta as titular_cuenta,
            pafg.fecha_pago as fecha_pago,
            pafg.descripcion as descripcion,
            pafg.idbancos as id_banco,
            bn.nombre as banco,
            pafg.numero_operacion as numero_operacion,
            pafg.monto as monto,
            pafg.imagen as imagen,
            pafg.estado as estado
            FROM pago_af_general pafg, bancos as bn 
            WHERE pafg.idcompra_af_general='$idcompra_af_general' AND bn.idbancos=pafg.idbancos AND  pafg.estado=1  AND  pafg.estado_delete=1 ORDER BY pafg.fecha_pago DESC";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para desactivar categorías
  public function desactivar_pagos($idcompra_af_general) {
    //var_dump($idpago_compras);die();
    $sql = "UPDATE pago_af_general SET estado='0',user_trash= '" . $_SESSION['idusuario'] . "' WHERE idpago_af_general ='$idcompra_af_general'";
		$desactivar= ejecutarConsulta($sql);

		if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('pago_af_general','".$idcompra_af_general."','Pago compra activo fijo general desactivado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $desactivar;
  }

  //Implementamos un método para desactivar categorías
  public function eliminar_pagos($idcompra_af_general) {
    //var_dump($idpago_compras);die();
    $sql = "UPDATE pago_af_general SET estado_delete='0',user_delete= '" . $_SESSION['idusuario'] . "'  WHERE idpago_af_general ='$idcompra_af_general'";
		$eliminar =  ejecutarConsulta($sql);
		if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('pago_af_general','$idcompra_af_general','Pago compra activo fijo general Eliminado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
		
		return $eliminar;
  }

  //Mostrar datos para editar Pago servicio.
  public function mostrar_pagos($idcompra_af_general) {
    $sql = "SELECT
            pafg.idpago_af_general as idpago_af_general,
            pafg.idcompra_af_general as idcompra_af_general,
            pafg.forma_pago as forma_pago,
            pafg.tipo_pago as tipo_pago,
            pafg.beneficiario as beneficiario,
            pafg.cuenta_destino as cuenta_destino,
            pafg.titular_cuenta as titular_cuenta,
            pafg.fecha_pago as fecha_pago,
            pafg.descripcion as descripcion,
            pafg.idbancos as idbancos,
            bn.nombre as banco,
            pafg.numero_operacion as numero_operacion,
            pafg.monto as monto,
            pafg.imagen as imagen,
            pafg.estado as estado
            FROM pago_af_general pafg, bancos as bn
            WHERE pafg.idpago_af_general='$idcompra_af_general' AND pafg.idbancos = bn.idbancos";

    return ejecutarConsultaSimpleFila($sql);
  }

  // consulta para totales
  public function suma_total_pagos($idcompra_af_general) {
    $sql = "SELECT SUM(pafg.monto) as total_monto
		FROM pago_af_general as pafg
		WHERE  pafg.idcompra_af_general='$idcompra_af_general' AND pafg.estado='1' AND pafg.estado_delete='1'";
    return ejecutarConsultaSimpleFila($sql);
  }

  // obtebnemos los DOCS para eliminar
  public function obtenerImg($idpago_af_general) {
    $sql = "SELECT imagen FROM pago_af_general WHERE idpago_af_general='$idpago_af_general'";
    return ejecutarConsulta($sql);
  }

  //mostrar datos del proveedor y maquina en form
  public function most_datos_prov_pago($idcompra_af_general) {
    $sql = "SELECT * FROM compra_af_general as cafg, proveedor as p  
    WHERE cafg.idproveedor=p.idproveedor AND cafg.idcompra_af_general='$idcompra_af_general'";
    return ejecutarConsultaSimpleFila($sql);
  }
  

}

?>
