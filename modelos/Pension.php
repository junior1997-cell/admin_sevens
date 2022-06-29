<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Pension
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }
  // :::::::::::::::::::::::::: S E C C I O N   P E N S I O N  ::::::::::::::::::::::::::::::::::::::::::
  public function tabla_principal($nube_idproyecto)  {
    $sql = "SELECT p.idpension, p.idproyecto, p.idproveedor,p.descripcion, pr_v.razon_social, pr_v.direccion, p.estado
		FROM pension as p, proyecto as py, proveedor as pr_v
		WHERE p.estado=1 AND p.idproyecto='$nube_idproyecto' AND p.idproyecto=py.idproyecto AND p.idproveedor=pr_v.idproveedor";
    return ejecutarConsulta($sql);
  }

  public function insertar_pension($idproyecto_p, $proveedor, $descripcion_pension)  {
   
    $sql = "INSERT INTO pension(idproyecto, idproveedor,descripcion) VALUES ('$idproyecto_p','$proveedor','$descripcion_pension')";
    return  ejecutarConsulta($sql);
  }

  public function editar_pension($idproyecto_p,$idpension,$proveedor,$descripcion_pension) {
    $sql = "UPDATE pension SET idproyecto='$idproyecto_p',idproveedor='$proveedor',descripcion='$descripcion_pension' WHERE idpension='$idpension'";
    return ejecutarConsulta($sql);
  }

  public function mostrar_pension($idpension) {

    $sql = "SELECT * FROM pension WHERE idpension = '$idpension';";
    return ejecutarConsultaSimpleFila($sql);
     
  }

  public function total_pension($idproyecto ) {

    $sql = "SELECT SUM( dp.monto) as total FROM `pension` as p , detalle_pension as dp
    WHERE p.idpension = dp.idpension AND p.idproyecto ='$idproyecto' AND dp.estado = 1 AND dp.estado_delete=1;";
    $total = ejecutarConsultaSimpleFila($sql);

    if ($total['status'] == false) { return $total; }


    $sql2 = "SELECT SUM(fp.monto) AS total_pago FROM factura_pension fp, pension as p 
    WHERE fp.idpension=p.idpension AND p.idproyecto ='$idproyecto ' AND fp.estado=1 AND  fp.estado_delete='1'"; 
    $total_deposito = ejecutarConsultaSimpleFila($sql2);

    if ($total_deposito['status'] == false) { return $total_deposito; }

      return $retorno = ['status' => true, 'message' => 'todo oka ps', 'data' => ['total'=>$total['data']['total'], 'total_deposito'=>$total_deposito['data']['total_pago']],];

  }

  public function total_x_pension($idpension)
  {
    $sql = "SELECT SUM(monto) AS total_m FROM detalle_pension WHERE idpension ='$idpension' AND estado='1' AND  estado_delete='1';";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function total_pago_x_pension($idpension)
  {
    $sql = "SELECT SUM(fp.monto) AS total_pago FROM factura_pension fp, pension as p 
    WHERE fp.idpension=p.idpension AND fp.idpension='$idpension' AND fp.estado=1 AND  fp.estado_delete='1'";
    return ejecutarConsultaSimpleFila($sql);
  }

  // :::::::::::::::::::::::::: S E C C I O N   D E T A L L E   P E N S I O N  ::::::::::::::::::::::::::
  public function tbla_detalle_pension($idpension)
  {
    $sql = "SELECT * FROM detalle_pension WHERE  idpension ='$idpension' AND estado='1' AND  estado_delete='1' ORDER BY fecha_inicial DESC";
    return ejecutarConsulta($sql);
  }
  // :::::::::::::::::::::::::: S E C C I O N   P A G O S  ::::::::::::::::::::::::::::::::::::::::::::::

  public function insertar_comprobante($idpension_f, $forma_pago, $tipo_comprobante, $nro_comprobante, $monto, $fecha_emision, $descripcion, $subtotal, $igv, $val_igv, $tipo_gravada, $imagen2)
  {
    $sql = "INSERT INTO factura_pension (idpension ,nro_comprobante, fecha_emision, monto, igv,val_igv,tipo_gravada, subtotal,forma_de_pago, tipo_comprobante, descripcion, comprobante) 
		VALUES ('$idpension_f','$nro_comprobante','$fecha_emision','$monto','$igv','$val_igv','$tipo_gravada','$subtotal','$forma_pago','$tipo_comprobante','$descripcion','$imagen2')";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para editar registros
  public function editar_comprobante($idfactura_pension, $idpension_f, $forma_pago, $tipo_comprobante, $nro_comprobante, $monto, $fecha_emision, $descripcion, $subtotal, $igv, $val_igv, $tipo_gravada, $imagen2)
  {
    $sql = "UPDATE `factura_pension` SET idpension ='$idpension_f', forma_de_pago='$forma_pago', nro_comprobante='$nro_comprobante', fecha_emision='$fecha_emision',	monto='$monto',	igv='$igv',	val_igv='$val_igv',	tipo_gravada='$tipo_gravada',	subtotal='$subtotal',	tipo_comprobante='$tipo_comprobante',	descripcion='$descripcion',	comprobante='$imagen2' WHERE idfactura_pension='$idfactura_pension';";
    return ejecutarConsulta($sql);
    //return $vaa;
  }

  public function tbla_comprobante($idpension)
  {
    $sql = "SELECT * FROM factura_pension WHERE idpension  ='$idpension' AND estado='1' AND  estado_delete='1' ORDER BY fecha_emision DESC";
    return ejecutarConsulta($sql);
  }

  //mostrar_comprobante
  public function mostrar_comprobante($idfactura_pension)
  {
    $sql = "SELECT * FROM factura_pension WHERE idfactura_pension ='$idfactura_pension'";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function total_monto_comp($idpension)
  {
    $sql = "SELECT SUM(monto) as total FROM factura_pension WHERE idpension='$idpension' AND estado='1' AND  estado_delete='1'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementamos un método para activar
  public function eliminar_comprobante($idfactura_pension)
  {
    //var_dump($idfactura_pension);die();
    $sql = "UPDATE factura_pension SET estado_delete='0' WHERE idfactura_pension ='$idfactura_pension'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para activar
  public function desactivar_comprobante($idfactura_pension)
  {
    //var_dump($idfactura_pension);die();
    $sql = "UPDATE factura_pension SET estado='0' WHERE idfactura_pension ='$idfactura_pension'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para desactivar
  public function activar_comprobante($idfactura_pension)
  {
    //var_dump($idpago_servicio);die();
    $sql = "UPDATE factura_pension SET estado='1' WHERE idfactura_pension ='$idfactura_pension'";
    return ejecutarConsulta($sql);
  }

  // obtebnemos los DOCS para eliminar
  public function obtenerDoc($idfactura_pension)
  {
    $sql = "SELECT comprobante FROM factura_pension WHERE idfactura_pension  ='$idfactura_pension'";

    return ejecutarConsulta($sql);
  }

  
}

?>
