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
		//var_dump($idproyecto, $doc, $columna, '1111');die();

		$sql="UPDATE proyecto SET $columna = '$doc' WHERE idproyecto = '$idproyecto'"; 
		
		return ejecutarConsulta($sql); 
	}

	//Editamos el DOC1 del proyecto
	public function insertar_valorizacion($idproyecto,$indice, $nombre, $fecha_inicio, $fecha_fin, $numero_q_s, $doc) { 

		$sql="INSERT INTO valorizacion ( idproyecto,indice, nombre, fecha_inicio, fecha_fin, numero_q_s, doc_valorizacion ) 
		VALUES ('$idproyecto', '$indice', '$nombre', '$fecha_inicio', '$fecha_fin', '$numero_q_s', '$doc')"; 
		
		return ejecutarConsulta($sql); 
	}
	
	//Implementamos un método para editar registros
	public function editar_valorizacion( $idproyecto, $idvalorizacion, $indice, $nombre, $fecha_inicio, $fecha_fin, $numero_q_s, $doc)
	{
		
		$sql="UPDATE valorizacion SET 
		idproyecto = '$idproyecto', 
		indice = '$indice', 
		nombre = '$nombre', 
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

		$sql="SELECT v.idvalorizacion, v.idproyecto, v.indice, v.nombre, v.doc_valorizacion, v.fecha_inicio, v.estado
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

	public function tabla_principal($nube_idproyecto)
	{
		$data = Array();   

		$sql1="SELECT * FROM valorizacion WHERE estado=1 AND estado_delete=1 AND idproyecto='$nube_idproyecto' ORDER BY  numero_q_s DESC, indice ASC";
		$valorizacion = ejecutarConsultaArray($sql1);

		if (!empty($valorizacion)) {

			foreach ($valorizacion as $key => $value1) {
			  $data[] = array(
				'nombre_tabla'     => 'valorizacion',
				'idtabla'          => $value1['idvalorizacion'],
				'nombre_columna'   => 'idvalorizacion',
				'indice'           => $value1['indice'],
				'nombre'           => $value1['nombre'],
				'doc_valorizacion' => $value1['doc_valorizacion'],
				'fecha_inicio'     => $value1['fecha_inicio'],
				'fecha_fin'        => $value1['fecha_fin'],
				'numero_q_s'       => $value1['numero_q_s'],
				
			  );
			}

		}

		$sql2="SELECT  doc1_contrato_obra,doc2_entrega_terreno,doc3_inicio_obra,doc7_cronograma_obra_valorizad,doc8_certificado_habilidad_ing_residnt,estado, idproyecto
		FROM proyecto WHERE estado_delete=1 AND idproyecto='$nube_idproyecto'";
		$documentos_proyect = ejecutarConsultaSimpleFila($sql2);

		if (!empty($documentos_proyect)) {

			$data[] = array(

				'nombre_tabla'     => 'proyecto',
				'idtabla'          => $documentos_proyect['idproyecto'],
				'nombre_columna'   => 'doc1_contrato_obra',
				'indice'           => '1',
				'nombre'           => 'Acta de contrato de obra',
				'doc_valorizacion' => $documentos_proyect['doc1_contrato_obra'],
        'fecha_inicio'     => ' - - - ',
				'fecha_fin'        => ' - - - ',
				'numero_q_s'       => 'General',
			
			);
			
			$data[] = array(

				'nombre_tabla'     => 'proyecto',
				'idtabla'          => $documentos_proyect['idproyecto'],
				'nombre_columna'   => 'doc2_entrega_terreno',
				'indice'           => '2',
				'nombre'           => 'Acta de entrega de terreno',
				'doc_valorizacion' => $documentos_proyect['doc2_entrega_terreno'],
        'fecha_inicio'     => ' - - - ',
				'fecha_fin'        => ' - - - ',
				'numero_q_s'       => 'General',
			
			);
      			
			$data[] = array(

				'nombre_tabla'     => 'proyecto',
				'idtabla'          => $documentos_proyect['idproyecto'],
				'nombre_columna'   => 'doc3_inicio_obra',
				'indice'           => '3',
				'nombre'           => 'Acta de inicio de obra',
				'doc_valorizacion' => $documentos_proyect['doc3_inicio_obra'],
        'fecha_inicio'     => ' - - - ',
				'fecha_fin'        => ' - - - ',
				'numero_q_s'       => 'General',
			
			);

      $data[] = array(

				'nombre_tabla'     => 'proyecto',
				'idtabla'          => $documentos_proyect['idproyecto'],
				'nombre_columna'   => 'doc7_cronograma_obra_valorizad',
				'indice'           => '7',
				'nombre'           => 'Cronograma de obra valorizado',
				'doc_valorizacion' => $documentos_proyect['doc7_cronograma_obra_valorizad'],
        'fecha_inicio'     => ' - - - ',
				'fecha_fin'        => ' - - - ',
				'numero_q_s'       => 'General',
				
			);
      
      $data[] = array( 

				'nombre_tabla'     => 'proyecto',
				'idtabla'          => $documentos_proyect['idproyecto'],
				'nombre_columna'   => 'doc8_certificado_habilidad_ing_residnt',
				'indice'           => '8',
				'nombre'           => 'Certificado de habilidad del ingeniero residente',
				'doc_valorizacion' => $documentos_proyect['doc8_certificado_habilidad_ing_residnt'],
        'fecha_inicio'     => ' - - - ',
				'fecha_fin'        => ' - - - ',
				'numero_q_s'       => 'General',
				
			);
      
		}
    return $data;
	}
//---------------------------------------------------

  //Implementamos un método para desactivar 
	public function desactivar($nombre_tabla,$nombre_columna,$idtabla)
	{
		$sql="UPDATE $nombre_tabla SET estado='0' WHERE $nombre_columna ='$idtabla'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para elimnar
	public function eliminar($nombre_tabla,$nombre_columna,$idtabla)
	{
		$sql="UPDATE $nombre_tabla SET estado_delete='0' WHERE $nombre_columna ='$idtabla'";
		return ejecutarConsulta($sql);
	}
//---------------------------------------------------
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