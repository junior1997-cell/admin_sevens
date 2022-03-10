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
		      VALUES ('$idproyecto', '$idproveedor', '$tipo_comprobante', '$numero_comprobante', '$forma_de_pago', '$fecha_subcontrato', '$val_igv', '$subtotal', '$igv', '$costo_parcial', '$descripcion','SUB CONTRATO','$comprobante')";
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

				$sql_2="SELECT SUM(monto) as total_deposito FROM pago_subcontrato WHERE idsubcontrato='$id' AND estado='1' AND  estado_delete='1';";

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
					"comprobante"        => $value['comprobante'],
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

	//:::::::::...........S E C C C I Ó N  P A G O S ....::::::::::::

	public function datos_proveedor($idsubcontrato)
	{
	$sql = "SELECT sc.idsubcontrato, p.idbancos, p.razon_social, p.cuenta_bancaria, p.cuenta_detracciones, p.titular_cuenta
	FROM subcontrato as sc, proveedor as p WHERE sc.idsubcontrato='$idsubcontrato' AND sc.idproveedor=p.idproveedor";
	return ejecutarConsultaSimpleFila($sql);
	}
	
	public function insertar_pago($idsubcontrato_pago, $beneficiario_pago, $forma_pago, $tipo_pago,
		$cuenta_destino_pago, $banco_pago, $titular_cuenta_pago, $fecha_pago, $monto_pago, $numero_op_pago, $descripcion_pago, $imagen1 )
	{
		$sql="INSERT INTO pago_subcontrato( idsubcontrato, idbancos, forma_pago, tipo_pago, beneficiario, cuenta_destino, titular_cuenta, fecha_pago, numero_operacion, monto, descripcion, comprobante) 
		VALUES ('$idsubcontrato_pago','$banco_pago','$forma_pago','$tipo_pago','$beneficiario_pago','$cuenta_destino_pago','$titular_cuenta_pago','$fecha_pago','$numero_op_pago','$monto_pago','$descripcion_pago','$imagen1')";
	    return ejecutarConsulta($sql);
    }

	public function editar_pago($idpago_subcontrato,$idsubcontrato_pago, $beneficiario_pago, $forma_pago, $tipo_pago,
		$cuenta_destino_pago, $banco_pago, $titular_cuenta_pago, $fecha_pago, $monto_pago, $numero_op_pago, $descripcion_pago, $imagen1 )
	{
		$sql="UPDATE pago_subcontrato SET 

			idsubcontrato    ='$idsubcontrato_pago',
			idbancos         ='$banco_pago',
			forma_pago       ='$forma_pago',
			tipo_pago        ='$tipo_pago',
			beneficiario     ='$beneficiario_pago',
			cuenta_destino   ='$cuenta_destino_pago',
			titular_cuenta   ='$titular_cuenta_pago',
			fecha_pago       ='$fecha_pago',
			numero_operacion ='$numero_op_pago',
			monto            ='$monto_pago',
			descripcion      ='$descripcion_pago',
			comprobante      ='$imagen1'

			WHERE idpago_subcontrato='$idpago_subcontrato'";

		return ejecutarConsulta($sql);
	}
	public function listar_pagos($idsubcontrato,$tipo)
	{
		$sql="SELECT ps.idpago_subcontrato,ps.idbancos,ps.forma_pago,ps.tipo_pago,ps.beneficiario,ps.estado,
		ps.cuenta_destino,ps.titular_cuenta,ps.fecha_pago,ps.numero_operacion,ps.monto,ps.descripcion,ps.comprobante, b.nombre as bancos
		FROM pago_subcontrato as ps, bancos as b 
		WHERE ps.idsubcontrato='$idsubcontrato' AND ps.idbancos=b.idbancos AND ps.estado=1 AND ps.estado_delete=1 AND ps.tipo_pago='$tipo'";
		return ejecutarConsulta($sql);
	}
   //------------------

	public function desactivar_pagos($idpago_subcontrato )
	{
		$sql="UPDATE pago_subcontrato SET estado='0' WHERE idpago_subcontrato ='$idpago_subcontrato'";
		return ejecutarConsulta($sql);
	}

	public function activar_pagos($idpago_subcontrato )
	{
		$sql="UPDATE pago_subcontrato SET estado='1' WHERE idpago_subcontrato ='$idpago_subcontrato'";
		return ejecutarConsulta($sql);
	}

	public function eliminar_pagos($idpago_subcontrato )
	{
		$sql="UPDATE pago_subcontrato SET estado_delete='0' WHERE idpago_subcontrato ='$idpago_subcontrato'";
		return ejecutarConsulta($sql);
	}
	
	public function mostrar_pagos($idpago_subcontrato )
	{
		$sql="SELECT*FROM pago_subcontrato WHERE idpago_subcontrato ='$idpago_subcontrato'";

		return ejecutarConsultaSimpleFila($sql);
	}

	//total
	public function total_pagos($idsubcontrato,$tipo){

		$sql="SELECT SUM(monto) as monto_parcial_deposito FROM pago_subcontrato 
			 WHERE idsubcontrato='$idsubcontrato' AND estado=1 AND estado_delete=1 AND tipo_pago='$tipo';";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function obtenerImg($idpago_subcontrato)
	{
		$sql="SELECT comprobante FROM pago_subcontrato WHERE idpago_subcontrato='$idpago_subcontrato'";
		return ejecutarConsulta($sql);
	}

	public function select2_banco()
	{
		$sql = "SELECT idbancos as id, nombre, alias FROM bancos WHERE estado='1' ORDER BY idbancos ASC;";
		return ejecutarConsulta($sql);
	}

}

?>