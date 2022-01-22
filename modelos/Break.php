<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Breaks
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}
	
	//Implementamos un método para insertar registros
	public function insertar_editar($array_break,$idproyecto){

		$desglese_break = json_decode($array_break,true); $sw = true;

		// registramos o editamos los "Break por semana"
		foreach ($desglese_break as $indice => $key) {
		
			if ( empty($key['idbreak'])) {

				// insertamos un nuevo registro
				$sql_2="INSERT INTO breaks (idproyecto, fecha_compra, dia_semana, cantidad, costo_parcial, descripcion)
				VALUES ('$idproyecto', '".$key['fecha_compra']."', '".$key['dia_semana']."', '".$key['cantidad_compra']."', '".$key['precio_compra']."', '".$key['descripcion_compra']."')";

				ejecutarConsulta($sql_2) or $sw = false;

			} else {
				# editamos el registro existente
				$sql_3="UPDATE breaks SET idproyecto='$idproyecto', 
				fecha_compra='".$key['fecha_compra']."', 
				dia_semana='".$key['dia_semana']."', 
				cantidad='".$key['cantidad_compra']."', 
				costo_parcial='".$key['precio_compra']."',
				descripcion='".$key['descripcion_compra']."'	
				WHERE idbreak='".$key['idbreak']."';";
				
				ejecutarConsulta($sql_3) or $sw = false;
			}

		}
		return $sw;	
	}

	///////////////////////CONSULTAS BREAK///////////////////////
	//listar_semana_botones
	public function listarsemana_botones($nube_idproyecto){
		$sql="SELECT p.idproyecto, p.fecha_inicio, p.fecha_fin, p.plazo, p.fecha_pago_obrero, p.fecha_valorizacion FROM proyecto as p WHERE p.idproyecto='$nube_idproyecto'";
		return ejecutarConsultaSimpleFila($sql);
	}
	//ver detalle semana a semana
	public function ver_detalle_semana_dias($f1,$f2,$nube_idproyect){
		//var_dump($f1,$f2,$nube_idproyect);die();
		$sql="SELECT * FROM breaks WHERE idproyecto='$nube_idproyect' AND fecha_compra BETWEEN '$f1' AND '$f2' ";
		return ejecutarConsultaArray($sql);
	}	
	///////////////////////CONSULTAS BREAK///////////////////////

	//Implementar un método para listar los registros
	public function listar($nube_idproyecto)
	{
		$sql="SELECT t.idtrabajador, t.nombres, t.tipo_documento, t.numero_documento, t.cuenta_bancaria, t.imagen_perfil as imagen, 
		tp.idcargo_trabajador , tp.desempenio, tp.sueldo_mensual, tp.sueldo_diario, tp.sueldo_hora, tp.estado, tp.idtrabajador_por_proyecto, 
		tp.estado, b.nombre as banco, ct.nombre as cargo, ct.idtipo_trabjador as idtipo_trabjador, tt.nombre as nombre_tipo
		FROM trabajador_por_proyecto as tp, trabajador as t, proyecto AS p, bancos AS b, cargo_trabajador as ct, tipo_trabajador as tt
		WHERE tp.idproyecto = p.idproyecto AND tp.idproyecto = '$nube_idproyecto'   AND tp.idtrabajador = t.idtrabajador AND t.idbancos = b.idbancos AND
		ct.idcargo_trabajador=tp.idcargo_trabajador AND tt.idtipo_trabajador=ct.idtipo_trabjador";
		return ejecutarConsulta($sql);		
	}

}

?>