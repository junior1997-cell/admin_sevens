<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class ServicioEquipo
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	public function insertar($idproyecto,$maquinaria,$fecha_inicio,$fecha_fin,$horometro_inicial,$horometro_final,$horas,$costo_unitario,$costo_parcial,$unidad_m,$dias,$mes,$descripcion)
	{
		$sql="INSERT INTO servicio (idproyecto,idmaquinaria,horometro_inicial,horometro_final,horas,costo_parcial,costo_unitario,fecha_entrega,fecha_recojo,unidad_medida,dias_uso,meses_uso,descripcion) 
		VALUES ('$idproyecto','$maquinaria','$horometro_inicial','$horometro_final','$horas','$costo_parcial','$costo_unitario','$fecha_inicio','$fecha_fin','$unidad_m','$dias','$mes','$descripcion')";
		return ejecutarConsulta($sql);
			
	}

	//Implementar un método para listar los registros AGRUPADOS
	public function listar($nube_idproyecto)
	{
		$sql="SELECT 
		s.idmaquinaria as idmaquinaria,
		s.idproyecto as idproyecto,
		s.unidad_medida as unidad_medida,
		m.nombre as maquina,
        p.razon_social as razon_social,
		m.codigo_maquina as codigo_maquina,
		COUNT(s.idmaquinaria) as cantidad_veces, 
		SUM(s.horas) as Total_horas, 
		s.costo_unitario as costo_unitario, 
		SUM(s.costo_parcial) as costo_parcial,
		SUM(s.horas)as horas,
		s.estado as estado
		FROM servicio as s, maquinaria as m, proveedor as p
		WHERE s.estado = 1 
		AND s.idproyecto='$nube_idproyecto'
		AND m.tipo = 2 
		AND s.idmaquinaria=m.idmaquinaria
        AND m.idproveedor=p.idproveedor
		GROUP BY s.idmaquinaria";
		return ejecutarConsulta($sql);		
	}
	//pago servicio
	public function pago_servicio($idmaquinaria,$nube_idproyecto){
		$sql="SELECT SUM(ps.monto) as monto FROM pago_servicio as ps 
		WHERE ps.estado=1 AND  ps.id_maquinaria ='$idmaquinaria' AND ps.idproyecto='$nube_idproyecto'";
		return ejecutarConsultaSimpleFila($sql);
	}

	/*===============================================
	===========SECCION FUNCIONES POR SERVICIO========
	================================================*/

	//ver detallete por maquina
	public function ver_detalle_m($idmaquinaria,$idproyecto){

		$sql="SELECT * FROM servicio as s WHERE s.idmaquinaria='$idmaquinaria' AND s.idproyecto='$idproyecto' ORDER BY idservicio DESC";

		return ejecutarConsulta($sql);	

	}
	//suma_horas_costoparcial
	public function suma_horas_costoparcial($idmaquinaria,$idproyecto){
		$sql="SELECT 
		SUM(s.horas) as horas, 
		SUM(s.costo_parcial) as costo_parcial  
		FROM servicio as s 
		WHERE s.idmaquinaria='$idmaquinaria' AND s.idproyecto='$idproyecto' AND s.estado='1'";

		return ejecutarConsultaSimpleFila($sql);	
	}
	
	//Implementamos un método para editar registros
	public function editar($idservicio,$idproyecto,$maquinaria,$fecha_inicio,$fecha_fin,$horometro_inicial,$horometro_final,$horas,$costo_unitario,$costo_parcial,$unidad_m,$dias,$mes,$descripcion)
		{
		$sql="UPDATE servicio SET 
		idproyecto='$idproyecto',
		idmaquinaria='$maquinaria',
		horometro_inicial='$horometro_inicial',
		horometro_final='$horometro_final',
		horas='$horas',
		costo_parcial='$costo_parcial',
		costo_unitario='$costo_unitario',
		fecha_entrega='$fecha_inicio',
		fecha_recojo='$fecha_fin',
		unidad_medida='$unidad_m',
		dias_uso='$dias',
		meses_uso='$mes',
		descripcion='$descripcion'
		 WHERE idservicio ='$idservicio'";	
		return ejecutarConsulta($sql);	
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($idservicio){
		$sql="UPDATE servicio SET estado='0' WHERE idservicio ='$idservicio '";
		return ejecutarConsulta($sql);
	}
	//Implementamos un método para activar categorías
	public function activar($idservicio ){
		$sql="UPDATE servicio SET estado='1' WHERE idservicio='$idservicio '";
		return ejecutarConsulta($sql);
	}
	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idservicio)
		{
		$sql="SELECT
		s.idservicio as idservicio,
		s.idproyecto as idproyecto,
		s.idmaquinaria as idmaquinaria,
		s.horometro_inicial as horometro_inicial,
		s.horometro_final as horometro_final,
		s.horas as horas,
		s.costo_parcial as costo_parcial,
		s.costo_unitario as costo_unitario,
		s.fecha_entrega as fecha_entrega,
		s.fecha_recojo as fecha_recojo,
		s.unidad_medida as unidad_medida,
		s.dias_uso as dias_uso,
		s.meses_uso as meses_uso,
		s.descripcion as descripcion,
		m.nombre as nombre_maquina,
		m.codigo_maquina as codigo_maquina,
		p.razon_social as razon_social
		FROM servicio as s, maquinaria as m, proveedor as p 
		WHERE s.idservicio ='$idservicio' AND s.idmaquinaria = m.idmaquinaria AND m.idproveedor=p.idproveedor";
		return ejecutarConsultaSimpleFila($sql);
	}
	//Seleccionar Trabajador Select2
	public function select2_servicio()
		{
		$sql="SELECT 
		mq.idmaquinaria as idmaquinaria, 
		mq.nombre as nombre, 
		mq.codigo_maquina as codigo_maquina, 
		p.razon_social as nombre_proveedor, 
		mq.idproveedor as idproveedor
		FROM maquinaria as mq, proveedor as p 
		WHERE mq.idproveedor=p.idproveedor AND mq.estado='1' AND mq.tipo=2";
		return ejecutarConsulta($sql);		
	}

	/**
	 * ====================================
	 *SECCION PAGO MAQUINARIA
	 * ====================================
	 */
	public function insertar_pago($idproyecto_pago,$beneficiario_pago,
	                       		  $forma_pago,$tipo_pago,$cuenta_destino_pago,
								  $banco_pago,$titular_cuenta_pago,$fecha_pago,
								  $monto_pago,$numero_op_pago,$descripcion_pago,
								  $id_maquinaria_pago,$imagen1)
	 {

		$sql="INSERT INTO pago_servicio (idproyecto,beneficiario,forma_pago,tipo_pago,cuenta_destino,id_banco,titular_cuenta,fecha_pago,monto,numero_operacion,descripcion,id_maquinaria,imagen) 
		VALUES ('$idproyecto_pago','$beneficiario_pago','$forma_pago','$tipo_pago','$cuenta_destino_pago','$banco_pago','$titular_cuenta_pago','$fecha_pago','$monto_pago','$numero_op_pago','$descripcion_pago','$id_maquinaria_pago','$imagen1')";
		return ejecutarConsulta($sql);
			
	}
	//Implementamos un método para editar registros
	public function editar_pago($idpago_servicio,$idproyecto_pago,$beneficiario_pago,
								$forma_pago,$tipo_pago,$cuenta_destino_pago,
								$banco_pago,$titular_cuenta_pago,$fecha_pago,
								$monto_pago,$numero_op_pago,$descripcion_pago,$id_maquinaria_pago,$imagen1){
		$sql="UPDATE pago_servicio SET
		idproyecto='$idproyecto_pago',
		beneficiario='$beneficiario_pago',
		forma_pago='$forma_pago',
		tipo_pago='$tipo_pago',
		cuenta_destino='$cuenta_destino_pago',
		id_banco='$banco_pago',
		titular_cuenta='$titular_cuenta_pago',
		fecha_pago='$fecha_pago',
		monto='$monto_pago',
		numero_operacion='$numero_op_pago',
		descripcion='$descripcion_pago',
		imagen='$imagen1',
		id_maquinaria='$id_maquinaria_pago'
		WHERE idpago_servicio='$idpago_servicio'";	
		return ejecutarConsulta($sql);	
	}
	//Listar pagos
	public function listar_pagos($idmaquinaria,$idproyecto){
		//var_dump($idproyecto,$idmaquinaria);die();
		$sql ="SELECT
		ps.idpago_servicio as idpago_servicio,
		ps.idproyecto as idproyecto,
		ps.id_maquinaria as id_maquinaria,
		ps.forma_pago as forma_pago,
		ps.tipo_pago as tipo_pago,
		ps.beneficiario as beneficiario,
		ps.cuenta_destino as cuenta_destino,
		ps.titular_cuenta as titular_cuenta,
		ps.fecha_pago as fecha_pago,
		ps.descripcion as descripcion,
		ps.id_banco as id_banco,
		bn.nombre as banco,
		ps.numero_operacion as numero_operacion,
		ps.monto as monto,
		ps.imagen as imagen,
		ps.estado as estado
		FROM pago_servicio ps, bancos as bn 
		WHERE ps.idproyecto='$idproyecto' AND ps.id_maquinaria='$idmaquinaria' AND bn.idbancos=ps.id_banco";
		return ejecutarConsulta($sql);
	}
	//Implementamos un método para desactivar categorías
	public function desactivar_pagos($idpago_servicio){
		//var_dump($idpago_servicio);die();
		$sql="UPDATE pago_servicio SET estado='0' WHERE idpago_servicio ='$idpago_servicio'";
		return ejecutarConsulta($sql);
	}
	//Implementamos un método para activar categorías
	public function activar_pagos($idpago_servicio){
		$sql="UPDATE pago_servicio SET estado='1' WHERE idpago_servicio ='$idpago_servicio'";
		return ejecutarConsulta($sql);
	}
	//Mostrar datos para editar Pago servicio.
	public function mostrar_pagos($idpago_servicio){
		$sql = "SELECT
		ps.idpago_servicio as idpago_servicio,
		ps.idproyecto as idproyecto,
		ps.id_maquinaria as id_maquinaria,
        mq.nombre as nombre_maquina,
		ps.forma_pago as forma_pago,
		ps.tipo_pago as tipo_pago,
		ps.beneficiario as beneficiario,
		ps.cuenta_destino as cuenta_destino,
		ps.titular_cuenta as titular_cuenta,
		ps.fecha_pago as fecha_pago,
		ps.descripcion as descripcion,
		ps.id_banco as id_banco,
		bn.nombre as banco,
		ps.numero_operacion as numero_operacion,
		ps.monto as monto,
		ps.imagen as imagen,
		ps.estado as estado
		FROM pago_servicio ps, bancos as bn, maquinaria as mq
		WHERE idpago_servicio='$idpago_servicio' AND ps.id_banco = bn.idbancos AND mq.idmaquinaria=ps.id_maquinaria";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function suma_total_pagos($idmaquinaria,$idproyecto){
		$sql="SELECT SUM(ps.monto) as total_monto
		FROM pago_servicio as ps
		WHERE ps.idproyecto ='$idproyecto' AND ps.id_maquinaria='$idmaquinaria' AND ps.estado='1'";
		return ejecutarConsultaSimpleFila($sql);

	}

	
    // obtebnemos los DOCS para eliminar
    public function obtenerImg($idpago_servicio) {

		$sql = "SELECT imagen FROM pago_servicio WHERE idpago_servicio='$idpago_servicio'";
  
		return ejecutarConsulta($sql);
	}

	//mostrar datos del proveedor y maquina en form
	public function most_datos_prov_pago($idmaquinaria){
		$sql = "SELECT * FROM maquinaria as m, proveedor as p  WHERE m.idproveedor=p.idproveedor AND m.idmaquinaria='$idmaquinaria'";
		return ejecutarConsultaSimpleFila($sql);
	}

}

?>