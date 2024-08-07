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
  public function insertar($tipo_documento, $num_documento, $razon_social, $direccion, $empresa_acargo,  $tipo_comprobante, $nro_comprobante, 
  $forma_pago, $fecha_emision, $val_igv, $subtotal, $igv, $precio_parcial,$descripcion, $glosa, $comprobante, $tipo_gravada) {

    $sql_1 = "SELECT  p.razon_social, p.tipo_documento, p.ruc, o_f.tipo_comprobante, o_f.numero_comprobante, o_f.fecha_emision, 
    o_f.costo_parcial, o_f.forma_de_pago, o_f.estado, o_f.estado_delete
    FROM otra_factura as o_f, proveedor as p
    WHERE p.idproveedor = o_f.idproveedor and p.ruc ='$num_documento' AND o_f.tipo_comprobante ='$tipo_comprobante' and o_f.numero_comprobante ='$nro_comprobante';";
		$prov = ejecutarConsultaArray($sql_1); if ($prov['status'] == false) { return  $prov;}

    if (empty($prov['data']) || $tipo_comprobante == 'Ninguno') {      
      $idproveedor = '';
      if ( empty($num_documento) ) {
        $idproveedor = '1';
      } else {
        $sql_2 = "SELECT * FROM proveedor WHERE ruc = '$num_documento'";
        $resul_provedor = ejecutarConsultaSimpleFila($sql_2); if ($resul_provedor['status'] == false) { return  $resul_provedor;}
        // var_dump($tipo_documento, $num_documento, $razon_social, $direccion);die();
        if (empty($resul_provedor['data'])) {
          $sql_3 = "INSERT INTO proveedor (idbancos,tipo_documento, ruc, razon_social, direccion)
          VALUES ('1','$tipo_documento', '$num_documento', '$razon_social', '$direccion')";
          $proveedor = ejecutarConsulta_retornarID($sql_3); if ($proveedor['status'] == false) { return  $proveedor;}
          $idproveedor = $proveedor['data'];
        } else {
          $idproveedor = $resul_provedor['data']['idproveedor'];
        }
      }      

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
  public function editar( $idotra_factura, $tipo_documento, $num_documento, $razon_social, $direccion, $empresa_acargo,  
  $tipo_comprobante, $nro_comprobante, $forma_pago, $fecha_emision, $val_igv, $subtotal, $igv, $precio_parcial, 
  $descripcion, $glosa, $comprobante, $tipo_gravada)  {

    $idproveedor = '';
    if ( empty($num_documento) ) {
      $idproveedor = '1';
    } else {
      $sql_2 = "SELECT * FROM proveedor WHERE ruc = '$num_documento'";
      $resul_provedor = ejecutarConsultaSimpleFila($sql_2); if ($resul_provedor['status'] == false) { return  $resul_provedor;}
      if (empty($resul_provedor['data'])) {
        $sql_3 = "INSERT INTO proveedor (idbancos,tipo_documento, ruc, razon_social, direccion)
        VALUES ('1','$tipo_documento', '$num_documento', '$razon_social', '$direccion')";
        $proveedor = ejecutarConsulta_retornarID($sql_3); if ($proveedor['status'] == false) { return  $proveedor;}
        $idproveedor = $proveedor['data'];
      } else {
        $idproveedor = $resul_provedor['data']['idproveedor'];
      }
    }      
    
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
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('otra_factura','".$idotra_factura."','Registro enviado a papelera','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $desactivar;
  }

  //Implementamos un método para desactivar categorías
  public function eliminar($idotra_factura)
  {
    $sql = "UPDATE otra_factura SET estado_delete='0', user_delete= '" . $_SESSION['idusuario'] . "'  WHERE idotra_factura ='$idotra_factura'";
		$eliminar =  ejecutarConsulta($sql);
		if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('otra_factura','$idotra_factura','Registro Eliminado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
		
		return $eliminar;
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idotra_factura)
  {
    $sql = "SELECT o_f.idotra_factura, o_f.idproveedor, o_f.idempresa_a_cargo, o_f.tipo_comprobante, o_f.numero_comprobante, o_f.forma_de_pago, o_f.fecha_emision, 
    o_f.val_igv, o_f.subtotal, o_f.igv, o_f.costo_parcial, o_f.descripcion, o_f.glosa, o_f.comprobante, o_f.tipo_gravada, o_f.estado, o_f.estado_delete,
    ec.razon_social as ec_razon_social, ec.tipo_documento as ec_tipo_documento, ec.numero_documento ec_numero_documento, ec.logo as ec_logo, p.ruc, p.razon_social,p.tipo_documento
    FROM otra_factura AS of, empresa_a_cargo AS ec, proveedor as p
    WHERE o_f.idempresa_a_cargo = ec.idempresa_a_cargo AND o_f.idproveedor = p.idproveedor AND o_f.idotra_factura ='$idotra_factura'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para listar los registros
  public function tbla_principal($empresa_a_cargo, $fecha_1,$fecha_2,$id_proveedor,$comprobante) {
  
    $filtro_empresa_a_cargo = ""; $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = ""; 

    if (empty($empresa_a_cargo) ) {  $filtro_empresa_a_cargo = ""; } else { $filtro_empresa_a_cargo = "AND o_f.idempresa_a_cargo = '$empresa_a_cargo'"; }

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND o_f.fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
    } else if (!empty($fecha_1)) {      
      $filtro_fecha = "AND o_f.fecha_emision = '$fecha_1'";
    }else if (!empty($fecha_2)) {        
      $filtro_fecha = "AND o_f.fecha_emision = '$fecha_2'";
    }   

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND o_f.idproveedor = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else { $filtro_comprobante = "AND o_f.tipo_comprobante = '$comprobante'"; }  

    $sql = "SELECT o_f.idotra_factura,o_f.idproveedor,o_f.tipo_comprobante,o_f.numero_comprobante,o_f.forma_de_pago,o_f.fecha_emision,o_f.subtotal,o_f.igv,o_f.costo_parcial,o_f.descripcion,o_f.glosa,o_f.comprobante,o_f.estado,p.razon_social, p.ruc 
    FROM otra_factura as o_f, proveedor as p WHERE o_f.estado=1 AND o_f.estado_delete=1 AND o_f.idproveedor=p.idproveedor $filtro_empresa_a_cargo $filtro_proveedor $filtro_comprobante $filtro_fecha ORDER BY idotra_factura DESC";
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
