<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Otra_factura
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }
  //$idotra_factura,$idproyecto,$fecha_viaje,$tipo_viajero,$tipo_ruta,$cantidad,$precio_unitario,$precio_parcial,$ruta,$descripcion,$foto2
  //Implementamos un método para insertar registros
  public function insertar($idproveedor, $tipo_comprobante, $nro_comprobante, $forma_pago, $fecha_emision, $val_igv, $subtotal, $igv, $precio_parcial, $descripcion, $glosa, $comprobante, $tipo_gravada)
  {
    $sql = "INSERT INTO otra_factura (idproveedor, tipo_comprobante, numero_comprobante, forma_de_pago, fecha_emision, val_igv, subtotal, igv, costo_parcial, descripcion, glosa, comprobante, tipo_gravada) 
		VALUES ('$idproveedor', '$tipo_comprobante', '$nro_comprobante', '$forma_pago', '$fecha_emision', '$val_igv', '$subtotal', '$igv', '$precio_parcial', '$descripcion', '$glosa', '$comprobante', '$tipo_gravada')";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para editar registros
  public function editar($idotra_factura, $idproveedor, $tipo_comprobante, $nro_comprobante, $forma_pago, $fecha_emision, $val_igv, $subtotal, $igv, $precio_parcial, $descripcion, $glosa, $comprobante, $tipo_gravada)
  {
    $sql = "UPDATE otra_factura SET 
    `idproveedor`       ='$idproveedor',
    `tipo_comprobante`  ='$tipo_comprobante',
    `numero_comprobante`='$nro_comprobante',
    `forma_de_pago`     ='$forma_pago',
    `fecha_emision`     ='$fecha_emision',
    `val_igv`           ='$val_igv',
    `subtotal`          ='$subtotal',
    `igv`               ='$igv',
    `costo_parcial`     ='$precio_parcial',
    `descripcion`       ='$descripcion',
    `glosa`             ='$glosa',
    `comprobante`       ='$comprobante',
    `tipo_gravada`      ='$tipo_gravada'

		WHERE idotra_factura='$idotra_factura'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idotra_factura)
  {
    $sql = "UPDATE otra_factura SET estado='0' WHERE idotra_factura ='$idotra_factura'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para desactivar categorías
  public function eliminar($idotra_factura)
  {
    $sql = "UPDATE otra_factura SET estado_delete='0' WHERE idotra_factura ='$idotra_factura'";
    return ejecutarConsulta($sql);
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idotra_factura)
  {
    $sql = "SELECT*FROM otra_factura   
		WHERE idotra_factura ='$idotra_factura'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para listar los registros
  public function listar()
  {
    $sql = "SELECT*FROM otra_factura WHERE  estado_delete='1' AND estado='1' ORDER BY idotra_factura DESC";
    return ejecutarConsulta($sql);
  }

  //Seleccionar un comprobante
  public function ObtnerCompr($idotra_factura)
  {
    $sql = "SELECT comprobante FROM otra_factura WHERE idotra_factura='$idotra_factura'";
    return ejecutarConsulta($sql);
  }
  //total
  public function total()
  {
    $sql = "SELECT SUM(costo_parcial) as precio_parcial FROM otra_factura WHERE estado=1 AND estado_delete='1'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Select2 Proveedor
	public function select2_proveedor()
	{
    $sql = "SELECT idproveedor, razon_social, ruc FROM proveedor WHERE  estado=1 AND estado_delete=1";
    return ejecutarConsulta($sql);
	}
	
}

?>
