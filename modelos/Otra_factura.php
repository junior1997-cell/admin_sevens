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
  public function tbla_principal( $fecha_1,$fecha_2,$id_proveedor,$comprobante)
  {
  
    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = ""; 

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
    FROM otra_factura as of, proveedor as p WHERE of.estado=1 AND of.estado_delete=1 AND of.idproveedor=p.idproveedor  $filtro_proveedor $filtro_comprobante $filtro_fecha ORDER BY idotra_factura DESC";
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
