<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Sub_contrato
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($idproyecto, $idproveedor, $tipo_comprobante, $numero_comprobante, $forma_de_pago, $fecha_subcontrato, $val_igv, $subtotal, $igv, $costo_parcial, $descripcion, $comprobante)
	{
	
		$sql="INSERT INTO subcontrato(idproyecto, idproveedor, tipo_comprobante, numero_comprobante, forma_de_pago, fecha_subcontrato, val_igv, subtotal, igv, costo_parcial, descripcion, glosa, comprobante) 
		      VALUES ('$idproyecto', '$idproveedor', '$tipo_comprobante', '$numero_comprobante', '$forma_de_pago', '$fecha_subcontrato', '$val_igv', '$subtotal', '$igv', '$costo_parcial', '$descripcion','Sub contrato','$comprobante')";
		return ejecutarConsulta($sql);
			
	}

	//Implementamos un método para editar registros
	public function editar($idsubcontrato, $idproyecto, $idproveedor, $tipo_comprobante, $numero_comprobante, $forma_de_pago, $fecha_subcontrato, $val_igv, $subtotal, $igv, $costo_parcial, $descripcion, $comprobante)
	{
		$sql="UPDATE subcontrato SET 
		idsubcontrato='$idsubcontrato',
		idproyecto='$idproyecto',
		idproveedor='$idproveedor',
		tipo_comprobante='$tipo_comprobante',
		numero_comprobante='$numero_comprobante',
		forma_de_pago='$forma_de_pago',
		fecha_subcontrato='$fecha_subcontrato',
		val_igv='$val_igv',
		subtotal='$subtotal',
		igv='$igv',
		costo_parcial='$costo_parcial',
		descripcion='$descripcion',
		comprobante='$comprobante'
		 WHERE idsubcontrato='$idsubcontrato'";	
		return ejecutarConsulta($sql);	
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($idsubcontrato )
	{
		$sql="UPDATE subcontrato SET estado='0' WHERE idsubcontrato ='$idsubcontrato'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idsubcontrato )
	{
		$sql="UPDATE subcontrato SET estado='1' WHERE idsubcontrato ='$idsubcontrato'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar categorías
	public function eliminar($idsubcontrato )
	{
		$sql="UPDATE subcontrato SET estado_delete='0' WHERE idsubcontrato ='$idsubcontrato'";
		return ejecutarConsulta($sql);
	}
	

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idsubcontrato )
	{
		$sql="SELECT*FROM subcontrato WHERE idsubcontrato ='$idsubcontrato'";

		return ejecutarConsultaSimpleFila($sql);
	}

	public function verdatos($idsubcontrato)
	{
		$sql="SELECT sc.idsubcontrato,sc.idproyecto,sc.idproveedor,sc.tipo_comprobante,sc.numero_comprobante,sc.forma_de_pago,sc.fecha_subcontrato,sc.glosa,sc.subtotal,sc.igv,sc.costo_parcial,sc.descripcion,sc.comprobante, p.razon_social, p.ruc
		FROM subcontrato as sc, proveedor as p WHERE sc.idsubcontrato='$idsubcontrato' AND sc.idproveedor=p.idproveedor;";

		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar($idproyecto)
	{
		$list_subcontrato= Array(); 

		$sql_1="SELECT*FROM subcontrato WHERE idproyecto='$idproyecto' AND estado='1' AND  estado_delete='1' ORDER BY fecha_subcontrato DESC";
		$subcontrato =ejecutarConsultaArray($sql_1);

		if (!empty($subcontrato)) {
			
			foreach ($subcontrato as $key => $value) {

				$id=$value['idsubcontrato'];

				$sql_2="SELECT SUM(monto) as total_deposito FROM pago_subcontrato WHERE idsubcontrato='$id';";

				$total_deposito= ejecutarConsultaSimpleFila($sql_2);

				$list_subcontrato[]= array(

					"idsubcontrato"      => $value['idsubcontrato'],
					"idproyecto"     	 => $value['idproyecto'],
					"idproveedor"        => $value['idproveedor'],
					"tipo_comprobante"   => $value['tipo_comprobante'],
					"forma_de_pago"      => $value['forma_de_pago'],
					"numero_comprobante" => $value['numero_comprobante'],
					"fecha_subcontrato"  => $value['fecha_subcontrato'],
					"subtotal"           => $value['subtotal'],
					"igv"                => $value['igv'],
					"costo_parcial"      => $value['costo_parcial'],
					"descripcion"        => $value['descripcion'],
					"estado"             => $value['estado'],

					"total_deposito"     => ($retVal_2 = empty($total_deposito) ? 0 : ($retVal_3 = empty($total_deposito['total_deposito']) ? 0 : $total_deposito['total_deposito'])),

				);	
				
			}
		}

		return $list_subcontrato;	
	}

	//Select2 Proveedor
	public function select2_proveedor()
	{
	$sql = "SELECT idproveedor, razon_social, ruc FROM proveedor WHERE  estado=1 AND estado_delete=1";
	return ejecutarConsulta($sql);
	}
	

	//Seleccionar un comprobante
	public function ficha_tec($idsubcontrato)
	{
		$sql="SELECT comprobante FROM subcontrato WHERE idsubcontrato='$idsubcontrato'";
		return ejecutarConsulta($sql);		
	}
	//total
	public function total($idproyecto){
		$sql="SELECT SUM(costo_parcial) as precio_parcial FROM subcontrato WHERE idproyecto='$idproyecto' AND estado=1 AND estado_delete=1";
		return ejecutarConsultaSimpleFila($sql);
	}

}

?>