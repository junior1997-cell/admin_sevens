<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Otra_factura_Proyecto
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }
  //$idotra_factura_proyecto,$idproyecto,$fecha_viaje,$tipo_viajero,$tipo_ruta,$cantidad,$precio_unitario,$precio_parcial,$ruta,$descripcion,$foto2
  //Implementamos un método para insertar registros
  public function insertar($idProyecto,$tipo_documento, $num_documento, $razon_social, $direccion, $empresa_acargo,  $tipo_comprobante, $nro_comprobante, 
  $forma_pago, $fecha_emision, $val_igv, $subtotal, $igv, $precio_parcial,$descripcion, $glosa, $comprobante, $tipo_gravada) {

    $sql_1 = "SELECT  p.razon_social, p.tipo_documento, p.ruc, ofp.tipo_comprobante, ofp.numero_comprobante, ofp.fecha_emision, 
    ofp.costo_parcial, ofp.forma_de_pago, ofp.estado, ofp.estado_delete
    FROM otra_factura_proyecto as ofp, proveedor as p
    WHERE p.idproveedor = ofp.idproveedor and p.ruc ='$num_documento' AND ofp.tipo_comprobante ='$tipo_comprobante' and ofp.numero_comprobante ='$nro_comprobante';";
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
      //  var_dump($idProyecto,$idproveedor,$empresa_acargo);die();
      $sql = "INSERT INTO otra_factura_proyecto (idproyecto,idproveedor, idempresa_a_cargo, tipo_comprobante, numero_comprobante, forma_de_pago, 
      fecha_emision, val_igv, subtotal, igv, costo_parcial, descripcion, glosa, comprobante, tipo_gravada, user_created) 
		  VALUES ('$idProyecto','$idproveedor', '$empresa_acargo', '$tipo_comprobante', '$nro_comprobante', '$forma_pago', '$fecha_emision', 
      '$val_igv', '$subtotal', '$igv', '$precio_parcial', '$descripcion', '$glosa', '$comprobante', '$tipo_gravada','" . $_SESSION['idusuario'] . "')";
      $insertar =  ejecutarConsulta_retornarID($sql); 
      // var_dump($insertar['data']);die();
      if ($insertar['status'] == false) {  return $insertar; } 
      
      //add registro en nuestra bitacora
      $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('otra_factura_proyecto','".$insertar['data']."','Nueva otra factura registrada','" . $_SESSION['idusuario'] . "')";
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
  public function editar( $idotra_factura_proyecto,$idProyecto, $tipo_documento, $num_documento, $razon_social, $direccion, $empresa_acargo,  
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
    
    $sql = "UPDATE otra_factura_proyecto SET 
    idproyecto        ='$idProyecto',
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

		WHERE idotra_factura_proyecto='$idotra_factura_proyecto'";
    $editar= ejecutarConsulta($sql);

		if ($editar['status'] == false) {  return $editar; }
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('otra_factura_proyecto','$idotra_factura_proyecto','Otra factura editada','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }

    return $editar;
  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idotra_factura_proyecto)
  {
    $sql = "UPDATE otra_factura_proyecto SET estado='0',user_trash= '" . $_SESSION['idusuario'] . "' WHERE idotra_factura_proyecto ='$idotra_factura_proyecto'";
		$desactivar= ejecutarConsulta($sql);

		if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('otra_factura_proyecto','".$idotra_factura_proyecto."','Registro enviado a papelera','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $desactivar;
  }

  //Implementamos un método para desactivar categorías
  public function eliminar($idotra_factura_proyecto)
  {
    $sql = "UPDATE otra_factura_proyecto SET estado_delete='0', user_delete= '" . $_SESSION['idusuario'] . "'  WHERE idotra_factura_proyecto ='$idotra_factura_proyecto'";
		$eliminar =  ejecutarConsulta($sql);
		if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('otra_factura_proyecto','$idotra_factura_proyecto','Registro Eliminado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
		
		return $eliminar;
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idotra_factura_proyecto)
  {
    $sql = "SELECT ofp.idotra_factura_proyecto,ofp.idproyecto, ofp.idproveedor, ofp.idempresa_a_cargo, ofp.tipo_comprobante, ofp.numero_comprobante, ofp.forma_de_pago, ofp.fecha_emision, 
    ofp.val_igv, ofp.subtotal, ofp.igv, ofp.costo_parcial, ofp.descripcion, ofp.glosa, ofp.comprobante, ofp.tipo_gravada, ofp.estado, ofp.estado_delete,
    ec.razon_social as ec_razon_social, ec.tipo_documento as ec_tipo_documento, ec.numero_documento ec_numero_documento, ec.logo as ec_logo, p.ruc, p.razon_social,p.tipo_documento
    FROM otra_factura_proyecto as ofp
    INNER JOIN empresa_a_cargo as ec on ofp.idempresa_a_cargo = ec.idempresa_a_cargo
    INNER JOIN proveedor as p on  ofp.idproveedor = p.idproveedor
    WHERE ofp.idotra_factura_proyecto ='$idotra_factura_proyecto'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para listar los registros
  public function tbla_principal($id_proyecto,$empresa_a_cargo, $fecha_1,$fecha_2,$id_proveedor,$comprobante) {
  
    $filtro_empresa_a_cargo = ""; $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = ""; 

    if (empty($empresa_a_cargo) ) {  $filtro_empresa_a_cargo = ""; } else { $filtro_empresa_a_cargo = "AND ofp.idempresa_a_cargo = '$empresa_a_cargo'"; }

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND ofp.fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
    } else if (!empty($fecha_1)) {      
      $filtro_fecha = "AND ofp.fecha_emision = '$fecha_1'";
    }else if (!empty($fecha_2)) {        
      $filtro_fecha = "AND ofp.fecha_emision = '$fecha_2'";
    }   

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND ofp.idproveedor = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else { $filtro_comprobante = "AND ofp.tipo_comprobante = '$comprobante'"; }  

    $sql = "SELECT ofp.idotra_factura_proyecto,ofp.idproveedor,ofp.tipo_comprobante,ofp.numero_comprobante,ofp.forma_de_pago,ofp.fecha_emision,ofp.subtotal,ofp.igv,ofp.costo_parcial,ofp.descripcion,ofp.glosa,ofp.comprobante,ofp.estado,p.razon_social  
    FROM otra_factura_proyecto as ofp, proveedor as p WHERE ofp.idproyecto ='$id_proyecto' AND ofp.estado=1 AND ofp.estado_delete=1 AND ofp.idproveedor=p.idproveedor $filtro_empresa_a_cargo $filtro_proveedor $filtro_comprobante $filtro_fecha ORDER BY idotra_factura_proyecto DESC";
    return ejecutarConsulta($sql);

  }

  //total
  public function total() {
    $sql = "SELECT SUM(costo_parcial) as precio_parcial FROM otra_factura_proyecto WHERE estado=1 AND estado_delete='1'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Seleccionar un comprobante
  public function ObtnerCompr($idotra_factura_proyecto) {
    $sql = "SELECT comprobante FROM otra_factura_proyecto WHERE idotra_factura_proyecto='$idotra_factura_proyecto'";
    return ejecutarConsulta($sql);
  }
  	
}

?>
