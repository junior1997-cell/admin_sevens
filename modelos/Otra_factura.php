<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Otra_factura
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }
  //$idotra_factura,$idproyecto,$fecha_viaje,$tipo_viajero,$tipo_ruta,$cantidad,$precio_unitario,$precio_parcial,$ruta,$descripcion,$foto2
  //Implementamos un método para insertar registros
  public function insertar($idproveedor, $empresa_acargo, $ruc_proveedor, $tipo_comprobante, $nro_comprobante, $forma_pago, $fecha_emision, $val_igv, $subtotal, $igv, $precio_parcial, $descripcion, $glosa, $comprobante, $tipo_gravada)
  {
    $sql_1 = "SELECT  p.razon_social, p.tipo_documento, p.ruc, of.tipo_comprobante, of.numero_comprobante, of.fecha_emision, 
    of.costo_parcial, of.forma_de_pago, of.estado, of.estado_delete
    FROM otra_factura as of, proveedor as p
    WHERE p.idproveedor = of.idproveedor and p.ruc ='$ruc_proveedor' AND of.tipo_comprobante ='$tipo_comprobante' and of.numero_comprobante ='$nro_comprobante';";
		$prov = ejecutarConsultaArray($sql_1);
		if ($prov['status'] == false) { return  $prov;}

    if (empty($prov['data']) || $tipo_comprobante == 'Ninguno') {
      $sql = "INSERT INTO otra_factura (idproveedor, idempresa_a_cargo, tipo_comprobante, numero_comprobante, forma_de_pago, fecha_emision, val_igv, subtotal, igv, costo_parcial, descripcion, glosa, comprobante, tipo_gravada, user_created) 
		  VALUES ('$idproveedor', '$empresa_acargo', '$tipo_comprobante', '$nro_comprobante', '$forma_pago', '$fecha_emision', '$val_igv', '$subtotal', '$igv', '$precio_parcial', '$descripcion', '$glosa', '$comprobante', '$tipo_gravada','" . $_SESSION['idusuario'] . "')";
      $insertar =  ejecutarConsulta_retornarID($sql); 
      if ($insertar['status'] == false) {  return $insertar; } 
      
      //add registro en nuestra bitacora
      $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('otra_factura','".$insertar['data']."','Nueva otra factura registrada','" . $_SESSION['idusuario'] . "')";
      $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }  
      return $insertar;
    } else {
      $info_repetida = ''; 

			foreach ($prov['data'] as $key => $value) {
				$info_repetida .= '<li class="text-left font-size-13px">
				<span class="font-size-18px text-danger"><b >'.$value['tipo_comprobante'].': </b> '.$value['numero_comprobante'].'</span><br>
				<b>Razón Social: </b>'.$value['razon_social'].'<br>
				<b>'.$value['tipo_documento'].': </b>'.$value['ruc'].'<br>          
				<b>Fecha: </b>'.format_d_m_a($value['fecha_emision']).'<br>
				<b>Costo: </b>'.$value['costo_parcial'].'<br>
				<b>Papelera: </b>'.( $value['estado']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO') .' <b>|</b> 
				<b>Eliminado: </b>'. ($value['estado_delete']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO').'<br>
				<hr class="m-t-2px m-b-2px">
				</li>'; 
			}
			return $sw = array( 'status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ol>'.$info_repetida.'</ol>', 'id_tabla' => '' );
    }    
  }

  //Implementamos un método para editar registros
  public function editar($idotra_factura, $idproveedor, $empresa_acargo, $tipo_comprobante, $nro_comprobante, $forma_pago, $fecha_emision, $val_igv, $subtotal, $igv, $precio_parcial, $descripcion, $glosa, $comprobante, $tipo_gravada)
  {
    $sql = "UPDATE otra_factura SET 
    idproveedor       ='$idproveedor',
    idempresa_a_cargo ='$empresa_acargo',
    tipo_comprobante  ='$tipo_comprobante',
    numero_comprobante='$nro_comprobante',
    forma_de_pago     ='$forma_pago',
    fecha_emision     ='$fecha_emision',
    val_igv           ='$val_igv',
    subtotal          ='$subtotal',
    igv               ='$igv',
    costo_parcial     ='$precio_parcial',
    descripcion       ='$descripcion',
    glosa             ='$glosa',
    comprobante       ='$comprobante',
    tipo_gravada      ='$tipo_gravada',
    user_updated      = '" . $_SESSION['idusuario'] . "'

		WHERE idotra_factura='$idotra_factura'";
    $editar= ejecutarConsulta($sql);

		if ($editar['status'] == false) {  return $editar; }
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('otra_factura','$idotra_factura','Otra factura editada','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }

    return $editar;
  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idotra_factura)
  {
    $sql = "UPDATE otra_factura SET estado='0',user_trash= '" . $_SESSION['idusuario'] . "' WHERE idotra_factura ='$idotra_factura'";
		$desactivar= ejecutarConsulta($sql);

		if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('otra_factura','".$idotra_factura."','Otra factura desactivada','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $desactivar;
  }

  //Implementamos un método para desactivar categorías
  public function eliminar($idotra_factura)
  {
    $sql = "UPDATE otra_factura SET estado_delete='0',user_delete= '" . $_SESSION['idusuario'] . "'  WHERE idotra_factura ='$idotra_factura'";
		$eliminar =  ejecutarConsulta($sql);
		if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('otra_factura','$idotra_factura','Otra factura Eliminada','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
		
		return $eliminar;
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idotra_factura)
  {
    $sql = "SELECT of.idotra_factura, of.idproveedor, of.idempresa_a_cargo, of.tipo_comprobante, of.numero_comprobante, of.forma_de_pago, of.fecha_emision, 
    of.val_igv, of.subtotal, of.igv, of.costo_parcial, of.descripcion, of.glosa, of.comprobante, of.tipo_gravada, of.estado, of.estado_delete,
    ec.razon_social as ec_razon_social, ec.tipo_documento as ec_tipo_documento, ec.numero_documento ec_numero_documento, ec.logo as ec_logo
    FROM otra_factura AS of, empresa_a_cargo AS ec
    WHERE of.idempresa_a_cargo = ec.idempresa_a_cargo AND of.idotra_factura ='$idotra_factura'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para listar los registros
  public function tbla_principal($empresa_a_cargo, $fecha_1,$fecha_2,$id_proveedor,$comprobante) {
  
    $filtro_empresa_a_cargo = ""; $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = ""; 

    if (empty($empresa_a_cargo) ) {  $filtro_empresa_a_cargo = ""; } else { $filtro_empresa_a_cargo = "AND of.idempresa_a_cargo = '$empresa_a_cargo'"; }

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND of.fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
    } else if (!empty($fecha_1)) {      
      $filtro_fecha = "AND of.fecha_emision = '$fecha_1'";
    }else if (!empty($fecha_2)) {        
      $filtro_fecha = "AND of.fecha_emision = '$fecha_2'";
    }   

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND of.idproveedor = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else { $filtro_comprobante = "AND of.tipo_comprobante = '$comprobante'"; }  

    $sql = "SELECT of.idotra_factura,of.idproveedor,of.tipo_comprobante,of.numero_comprobante,of.forma_de_pago,of.fecha_emision,of.subtotal,of.igv,of.costo_parcial,of.descripcion,of.glosa,of.comprobante,of.estado,p.razon_social  
    FROM otra_factura as of, proveedor as p WHERE of.estado=1 AND of.estado_delete=1 AND of.idproveedor=p.idproveedor $filtro_empresa_a_cargo $filtro_proveedor $filtro_comprobante $filtro_fecha ORDER BY idotra_factura DESC";
    return ejecutarConsulta($sql);

  }

  //total
  public function total() {
    $sql = "SELECT SUM(costo_parcial) as precio_parcial FROM otra_factura WHERE estado=1 AND estado_delete='1'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Seleccionar un comprobante
  public function ObtnerCompr($idotra_factura) {
    $sql = "SELECT comprobante FROM otra_factura WHERE idotra_factura='$idotra_factura'";
    return ejecutarConsulta($sql);
  }
  	
}

?>
