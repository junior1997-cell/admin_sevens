<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Otro_ingreso
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }
  //$idotro_ingreso,$idproyecto,$fecha_viaje,$tipo_viajero,$tipo_ruta,$cantidad,$precio_unitario,$precio_parcial,$ruta,$descripcion,$foto2
  //Implementamos un método para insertar registros
  public function insertar($idproyecto, $idproveedor, $fecha_i, $precio_parcial, $subtotal, $igv,$val_igv,$tipo_gravada, $descripcion, $forma_pago, $tipo_comprobante, $nro_comprobante, $comprobante, $ruc, $razon_social, $direccion, $glosa)
  {
    $sql = "INSERT INTO otro_ingreso (idproyecto, idproveedor, tipo_comprobante, numero_comprobante, forma_de_pago, fecha_i, costo_parcial,subtotal,igv,val_igv,tipo_gravada,descripcion, comprobante,ruc,razon_social,direccion,glosa) 
		VALUES ('$idproyecto', '$idproveedor', '$tipo_comprobante', '$nro_comprobante', '$forma_pago', '$fecha_i', '$precio_parcial', '$subtotal', '$igv', '$val_igv', '$tipo_gravada', '$descripcion', '$comprobante', '$ruc', '$razon_social', '$direccion', '$glosa')";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para editar registros
  public function editar($idotro_ingreso, $idproyecto, $idproveedor, $fecha_i, $precio_parcial, $subtotal, $igv,$val_igv,$tipo_gravada, $descripcion, $forma_pago, $tipo_comprobante, $nro_comprobante, $comprobante, $ruc, $razon_social, $direccion, $glosa)
  {
    $sql = "UPDATE otro_ingreso SET 
		idproyecto='$idproyecto',
    idproveedor='$idproveedor',
		fecha_i='$fecha_i',
		costo_parcial='$precio_parcial',
		subtotal='$subtotal',
		igv='$igv',
		val_igv='$val_igv',
		tipo_gravada='$tipo_gravada',
		descripcion='$descripcion',
		forma_de_pago='$forma_pago',
		tipo_comprobante='$tipo_comprobante',
		numero_comprobante='$nro_comprobante',
		comprobante='$comprobante',
    ruc='$ruc',
    razon_social='$razon_social',
    direccion='$direccion',
    glosa='$glosa'

		WHERE idotro_ingreso='$idotro_ingreso'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idotro_ingreso) {
    $sql = "UPDATE otro_ingreso SET estado='0' WHERE idotro_ingreso ='$idotro_ingreso'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para activar categorías
  public function activar($idotro_ingreso) {
    $sql = "UPDATE otro_ingreso SET estado='1' WHERE idotro_ingreso ='$idotro_ingreso'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para desactivar categorías
  public function eliminar($idotro_ingreso) {
    $sql = "UPDATE otro_ingreso SET estado_delete='0' WHERE idotro_ingreso ='$idotro_ingreso'";
    return ejecutarConsulta($sql);
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idotro_ingreso) {
    $sql = "SELECT oi.idotro_ingreso, oi.idproyecto, oi.idproveedor, oi.ruc, oi.razon_social, oi.direccion, oi.tipo_comprobante, 
    oi.numero_comprobante, oi.forma_de_pago, oi.fecha_i, oi.subtotal, oi.igv, oi.costo_parcial, oi.descripcion, oi.glosa, oi.comprobante, 
    oi.val_igv, oi.tipo_gravada, oi.estado, oi.estado_delete, oi.created_at, oi.updated_at, p.razon_social, p.tipo_documento, p.ruc
    FROM otro_ingreso as oi, proveedor as p
    WHERE oi.idproveedor = p.idproveedor and  oi.idotro_ingreso ='$idotro_ingreso'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para listar los registros
  public function tbla_principal($idproyecto) {
    $sql = "SELECT*FROM otro_ingreso WHERE idproyecto='$idproyecto' AND estado_delete='1' AND estado='1' ORDER BY idotro_ingreso DESC";
    return ejecutarConsulta($sql);
  }

  //total
  public function total($idproyecto) {
    $sql = "SELECT SUM(costo_parcial) as precio_parcial FROM otro_ingreso WHERE idproyecto='$idproyecto' AND estado='1' AND estado_delete='1'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Seleccionar un comprobante
  public function ficha_tec($idotro_ingreso) {
    $sql = "SELECT comprobante FROM otro_ingreso WHERE idotro_ingreso='$idotro_ingreso'";
    return ejecutarConsulta($sql);
  }

}

?>
