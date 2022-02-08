<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Resumen_general
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}
	public function r_compras($idproyecto){
		$pago_total=0; 
		$Arraycompras= Array(); 

		$sql="SELECT cpp.idcompra_proyecto, cpp.idproyecto, cpp.idproveedor, cpp.fecha_compra, cpp.monto_total, p.razon_social, cpp.descripcion 
		FROM compra_por_proyecto as cpp, proveedor as p
		WHERE cpp.idproyecto='$idproyecto' AND cpp.idproveedor=p.idproveedor AND cpp.estado='1' ORDER by cpp.idcompra_proyecto ASC";

		$compras=ejecutarConsultaArray($sql);

		if (!empty($compras)) {
			
			foreach ($compras as $key => $value) {

				$idcompra=$value['idcompra_proyecto'];

				$sql_2="SELECT SUM(pc.monto) as total_p FROM pago_compras as pc WHERE pc.idcompra_proyecto='$idcompra' AND pc.estado='1' GROUP BY idcompra_proyecto";
				$t_monto= ejecutarConsultaSimpleFila($sql_2);

				if (empty($t_monto)) {
					$pago_total=0;
				}else{
					$pago_total=$t_monto['total_p'];
				}

				$Arraycompras[]= array(
					"idcompra_proyecto"     => $value['idcompra_proyecto'],
					"idproyecto"     	 => $value['idproyecto'],
					"idproveedor"    => $value['idproveedor'],
					"fecha_compra"    => $value['fecha_compra'],
					"monto_total"    => $value['monto_total'],
					"proveedor"    => $value['razon_social'],
					"descripcion"    => $value['descripcion'],

					"monto_pago_total"       =>$pago_total

				);

			}

		}

		return $Arraycompras;

		
	}

	public function r_serv_maquinaria_equipos($idproyecto,$tipo)
	{
		$serv_maquinaria= Array();

		$sql="SELECT s.idmaquinaria as idmaquinaria, s.idproyecto as idproyecto, m.nombre as maquina, p.razon_social as razon_social, COUNT(s.idmaquinaria) as cantidad_veces, SUM(s.costo_parcial) as costo_parcial 
		FROM servicio as s, maquinaria as m, proveedor as p 
		WHERE s.estado = 1 AND s.idproyecto='$idproyecto' AND m.tipo = '$tipo' AND s.idmaquinaria=m.idmaquinaria AND m.idproveedor=p.idproveedor 
		GROUP BY s.idmaquinaria";

		$maquinaria=ejecutarConsultaArray($sql);

		if (!empty($maquinaria)) {
			
			foreach ($maquinaria as $key => $value) {

				$idmaquinaria=$value['idmaquinaria'];

				$sql_2="SELECT SUM(ps.monto) as monto_pag_ser_maq FROM pago_servicio ps WHERE ps.idproyecto='$idproyecto' AND ps.id_maquinaria='$idmaquinaria' AND ps.estado=1 GROUP by id_maquinaria";
				$ser_maq_monto= ejecutarConsultaSimpleFila($sql_2);

				$serv_maquinaria[]= array(

					"idmaquinaria"      => $value['idmaquinaria'],
					"idproyecto"      => $value['idproyecto'],
					"maquina"     	 	=> $value['maquina'],
					"cantidad_veces"    => $value['cantidad_veces'],
					"costo_parcial"     => $value['costo_parcial'],
					"proveedor"         => $value['razon_social'],

					"monto_pag_ser_maq"       =>$ser_maq_monto['monto_pag_ser_maq']

				);

			}

		}

		return $serv_maquinaria;

	}

	//ver detallete por maquina-equipo
	public function ver_detalle_maq_equ($idmaquinaria,$idproyecto)
	{

		$sql="SELECT * FROM servicio as s WHERE s.idmaquinaria='$idmaquinaria' AND s.idproyecto='$idproyecto' ORDER BY idservicio DESC";

		return ejecutarConsulta($sql);	

	}
}

?>