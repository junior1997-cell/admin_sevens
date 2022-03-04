<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Otro_gasto
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }
  //$idotro_gasto,$idproyecto,$fecha_viaje,$tipo_viajero,$tipo_ruta,$cantidad,$precio_unitario,$precio_parcial,$ruta,$descripcion,$foto2
  //Implementamos un método para insertar registros
  public function insertar($idproyecto, $fecha_g, $precio_parcial, $subtotal, $igv, $descripcion, $forma_pago, $tipo_comprobante, $nro_comprobante, $comprobante, $ruc, $razon_social, $direccion, $glosa)
  {
    $sql = "INSERT INTO otro_gasto (idproyecto, tipo_comprobante, numero_comprobante, forma_de_pago, fecha_g, costo_parcial,subtotal,igv,descripcion, comprobante,ruc,razon_social,direccion,glosa) 
		VALUES ('$idproyecto','$tipo_comprobante','$nro_comprobante','$forma_pago','$fecha_g','$precio_parcial','$subtotal','$igv','$descripcion','$comprobante','$ruc', '$razon_social', '$direccion','$glosa')";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para editar registros
  public function editar($idotro_gasto, $idproyecto, $fecha_g, $precio_parcial, $subtotal, $igv, $descripcion, $forma_pago, $tipo_comprobante, $nro_comprobante, $comprobante, $ruc, $razon_social, $direccion, $glosa)
  {
    $sql = "UPDATE otro_gasto SET 
		idproyecto='$idproyecto',
		fecha_g='$fecha_g',
		costo_parcial='$precio_parcial',
		subtotal='$subtotal',
		igv='$igv',
		descripcion='$descripcion',
		forma_de_pago='$forma_pago',
		tipo_comprobante='$tipo_comprobante',
		numero_comprobante='$nro_comprobante',
		comprobante='$comprobante',
    ruc='$ruc',
    razon_social='$razon_social',
    direccion='$direccion',
    glosa='$glosa'

		WHERE idotro_gasto='$idotro_gasto'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idotro_gasto)
  {
    $sql = "UPDATE otro_gasto SET estado='0' WHERE idotro_gasto ='$idotro_gasto'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para activar categorías
  public function activar($idotro_gasto)
  {
    $sql = "UPDATE otro_gasto SET estado='1' WHERE idotro_gasto ='$idotro_gasto'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para desactivar categorías
  public function eliminar($idotro_gasto)
  {
    $sql = "UPDATE otro_gasto SET estado_delete='0' WHERE idotro_gasto ='$idotro_gasto'";
    return ejecutarConsulta($sql);
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idotro_gasto)
  {
    $sql = "SELECT*FROM otro_gasto   
		WHERE idotro_gasto ='$idotro_gasto'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para listar los registros
  public function listar($idproyecto)
  {
    $sql = "SELECT*FROM otro_gasto WHERE idproyecto='$idproyecto' AND estado_delete='1' AND estado='1' ORDER BY idotro_gasto DESC";
    return ejecutarConsulta($sql);
  }

  //Seleccionar un comprobante
  public function ficha_tec($idotro_gasto)
  {
    $sql = "SELECT comprobante FROM otro_gasto WHERE idotro_gasto='$idotro_gasto'";
    return ejecutarConsulta($sql);
  }
  //total
  public function total($idproyecto)
  {
    $sql = "SELECT SUM(costo_parcial) as precio_parcial FROM otro_gasto WHERE idproyecto='$idproyecto' AND estado=1 AND estado_delete='1'";
    return ejecutarConsultaSimpleFila($sql);
  }
}

?>
