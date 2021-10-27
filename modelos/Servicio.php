<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Servicios
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}
	//$idservicio,$idproyecto,$maquinaria,$fecha_inicio,$fecha_fin,$horometro_inicial,$horometro_final,$horas,$costo_unitario,$costo_parcial
	//Implementamos un método para insertar registros
	public function insertar($idproyecto,$maquinaria,$fecha_inicio,$fecha_fin,$horometro_inicial,$horometro_final,$horas,$costo_unitario,$costo_parcial,$unidad_m)
	{
		//var_dump($idproyecto,$idproveedor);die();
		$sql="INSERT INTO servicio (idproyecto,idmaquinaria,horometro_inicial,horometro_final,horas,costo_parcial,costo_unitario,fecha_entrega,fecha_recojo,unidad_medida ) 
		VALUES ('$idproyecto','$maquinaria','$horometro_inicial','$horometro_final','$horas','$costo_parcial','$costo_unitario','$fecha_inicio','$fecha_fin','$unidad_m')";
		return ejecutarConsulta($sql);
			
	}

	//Implementar un método para listar los registros
	public function listar($nube_idproyecto)
	{
		$sql="SELECT 
		s.idmaquinaria as idmaquinaria,
		s.idproyecto as idproyecto,
		m.nombre as maquina,
		m.codigo_maquina as codigo_maquina,
		COUNT(s.idmaquinaria) as cantidad_veces, 
		SUM(s.horas) as Total_horas, 
		s.costo_unitario as costo_unitario, 
		SUM(s.costo_parcial) as costo_parcial,
		s.estado as estado
		FROM servicio as s, maquinaria as m
		WHERE s.estado = 1 
		AND s.idproyecto='$nube_idproyecto' 
		AND s.idmaquinaria=m.idmaquinaria 
		GROUP BY s.idmaquinaria";
		return ejecutarConsulta($sql);		
	}
	//pago servicio
	public function pago_servicio($idmaquinaria){
		$sql="SELECT SUM(ps.monto) as monto FROM pago_servicio as ps 
		WHERE ps.id_maquinaria ='$idmaquinaria'";
		return ejecutarConsultaSimpleFila($sql);
	}

	/*===============================================
	===========SECCION FUNCIONES POR SERVICIO========
	================================================*/

	//ver detallete por maquina
	public function ver_detalle_m($idmaquinaria,$idproyecto){

		$sql="SELECT * FROM servicio as s WHERE s.idmaquinaria='$idmaquinaria' AND s.idproyecto='$idproyecto'";

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
	public function editar($idservicio,$idproyecto,$maquinaria,$fecha_inicio,$fecha_fin,$horometro_inicial,$horometro_final,$horas,$costo_unitario,$costo_parcial,$unidad_m)
	{
		//var_dump($idservicio,$idproyecto,$maquinaria,$fecha_inicio,$fecha_fin,$horometro_inicial,$horometro_final,$horas,$costo_unitario,$costo_parcial);die();
		///var_dump($idservicio ,$idproveedor);die();
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
		unidad_medida='$unidad_m'
		 WHERE idservicio ='$idservicio'";	
		return ejecutarConsulta($sql);	
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($idservicio)
	{
		$sql="UPDATE servicio SET estado='0' WHERE idservicio ='$idservicio '";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idservicio )
	{
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
		WHERE mq.idproveedor=p.idproveedor AND mq.estado='1' AND mq.tipo=1";
		return ejecutarConsulta($sql);		
	}

}

?>