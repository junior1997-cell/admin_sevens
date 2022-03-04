<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Valorizacion
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Editamos el DOC1 del proyecto
	public function editar_proyecto($idproyecto, $doc, $columna) { 

		$sql="UPDATE proyecto SET $columna = '$doc' WHERE idproyecto = '$idproyecto'"; 
		
		return ejecutarConsulta($sql); 
	}

	//Editamos el DOC1 del proyecto
	public function insertar_valorizacion($idproyecto, $nombre, $fecha_inicio, $fecha_fin, $numero_q_s, $doc) { 

		$sql="INSERT INTO valorizacion ( idproyecto, nombre, fecha_inicio, fecha_fin, numero_q_s, doc_valorizacion ) 
		VALUES ('$idproyecto', '$nombre', '$fecha_inicio', '$fecha_fin', '$numero_q_s', '$doc')"; 
		
		return ejecutarConsulta($sql); 
	}
	
	//Implementamos un método para editar registros
	public function editar_valorizacion( $idproyecto, $idvalorizacion, $nombre, $fecha_inicio, $fecha_fin, $numero_q_s, $doc)
	{
		//var_dump($idasistencia_trabajador,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$c_bancaria,$c_detracciones,$banco,$titular_cuenta);die;
		
		$sql="UPDATE valorizacion SET idproyecto = '$idproyecto', nombre = '$nombre', 
		fecha_inicio = '$fecha_inicio',
		fecha_fin = '$fecha_fin' , 
		numero_q_s = '$numero_q_s', 
		doc_valorizacion = '$doc'
		WHERE idvalorizacion = '$idvalorizacion'";	
		
		return ejecutarConsulta($sql);		
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idasistencia_trabajador)
	{
		$sql="SELECT tp.idtrabajador_por_proyecto, t.nombres , t.tipo_documento as documento, t.numero_documento, tp.cargo, t.imagen_perfil, 
		atr.fecha_asistencia, atr.horas_normal_dia, atr.horas_extras_dia 
		FROM trabajador AS t, trabajador_por_proyecto AS tp, asistencia_trabajador AS atr 
		WHERE t.idtrabajador = tp.idtrabajador AND tp.idtrabajador_por_proyecto = atr.idtrabajador_por_proyecto AND atr.idasistencia_trabajador = '$idasistencia_trabajador';";
		return ejecutarConsultaSimpleFila($sql);
	}	
	
	// Data para listar lo bototnes por quincena
	public function listarquincenas($nube_idproyecto){

		$sql="SELECT p.idproyecto, p.fecha_inicio, p.fecha_fin, p.plazo, p.fecha_pago_obrero, p.fecha_valorizacion 
		FROM proyecto as p 
		WHERE p.idproyecto = '$nube_idproyecto' AND p.fecha_inicio != p.fecha_fin";
        
		return ejecutarConsultaSimpleFila($sql);
	}

	//ver detalle quincena (cuando presiono el boton de cada quincena)
	public function ver_detalle_quincena($f1, $f2, $nube_idproyect){

		$sql="SELECT v.idvalorizacion, v.idproyecto, v.nombre, v.doc_valorizacion, v.fecha_inicio, v.estado
		FROM valorizacion as v
		WHERE v.idproyecto = '$nube_idproyect' AND v.fecha_inicio BETWEEN '$f1' AND '$f2';";
		$data1 = ejecutarConsultaArray($sql);

		$sql2 = "SELECT p.idproyecto, p.doc1_contrato_obra AS doc1, p.doc2_entrega_terreno AS doc81, p.doc3_inicio_obra AS doc82, p.doc7_cronograma_obra_valorizad AS doc4, p.doc8_certificado_habilidad_ing_residnt AS doc83 
		FROM proyecto as p 
		WHERE p.idproyecto = '$nube_idproyect';";
		$data2 = ejecutarConsultaSimpleFila($sql2);
		
		$results = array(
			"data1" => $data1,
			"data2" => $data2,
			"count_data1" => count( $data1)
		);

		return $results ;
	}

	// obtebnemos los DOCS para eliminar
    public function obtenerDocV($idvalorizacion) {

		$sql = "SELECT doc_valorizacion FROM valorizacion WHERE idvalorizacion='$idvalorizacion'";
  
		return ejecutarConsulta($sql);
	}

	// obtebnemos los DOCS para eliminar
    public function obtenerDocP($idproyecto, $columna ) {

		$sql = "SELECT $columna AS doc_p FROM proyecto WHERE idproyecto = '$idproyecto'";
  
		return ejecutarConsulta($sql);
	}
}

?>