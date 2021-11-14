<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Proyecto
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($tipo_documento, $numero_documento, $empresa, $nombre_proyecto, $nombre_codigo, $ubicacion, $actividad_trabajo,$empresa_acargo,$costo,$fecha_inicio,$fecha_fin,$plazo,$doc1,$doc2,$doc3,$doc4,$doc5,$doc6)
	{
		$doc7 = ""; $doc8 = "";
		// insertamos al usuario
		$sql="INSERT INTO proyecto ( tipo_documento, numero_documento, empresa, nombre_proyecto, nombre_codigo, ubicacion, actividad_trabajo, empresa_acargo, costo, fecha_inicio, fecha_fin, plazo, doc1_contrato_obra, doc2_entrega_terreno, doc3_inicio_obra, doc4_presupuesto, doc5_analisis_costos_unitarios, doc6_insumos, doc7_cronograma_obra_valorizad, doc8_certificado_habilidad_ing_residnt) 
		VALUES ('$tipo_documento', '$numero_documento', '$empresa', '$nombre_proyecto', '$nombre_codigo', '$ubicacion', '$actividad_trabajo', '$empresa_acargo', '$costo', '$fecha_inicio', '$fecha_fin', '$plazo', '$doc1', '$doc2', '$doc3', '$doc4', '$doc5', '$doc6', '$doc7', '$doc8');";
		return ejecutarConsulta($sql);
		// $sql2=	$tipo_documento.$numero_documento.$empresa.$nombre_proyecto.$ubicacion.$actividad_trabajo.$empresa_acargo.$costo.$fecha_inicio.$fecha_fin.$doc1.$doc2.$doc3;
		 
		// return $sql;
	 	
	}

	//Implementamos un método para editar registros
	public function editar($idproyecto, $tipo_documento, $numero_documento, $empresa, $nombre_proyecto, $nombre_codigo, $ubicacion, $actividad_trabajo,$empresa_acargo,$costo,$fecha_inicio,$fecha_fin,$plazo,$doc1,$doc2,$doc3,$doc4,$doc5,$doc6)
	{
		 
		$sql="UPDATE proyecto SET tipo_documento = '$tipo_documento', numero_documento = '$numero_documento', 
			empresa = '$empresa', nombre_proyecto = '$nombre_proyecto', nombre_codigo = '$nombre_codigo',  ubicacion = '$ubicacion',
			actividad_trabajo = '$actividad_trabajo', empresa_acargo = '$empresa_acargo', 
			costo = '$costo', fecha_inicio = '$fecha_inicio', fecha_fin = '$fecha_fin', plazo = '$plazo', 
			doc1_contrato_obra = '$doc1', doc2_entrega_terreno = '$doc2', doc3_inicio_obra = '$doc3',
			doc4_presupuesto = '$doc4', doc5_analisis_costos_unitarios = '$doc5', doc6_insumos = '$doc6'
		 WHERE idproyecto='$idproyecto'";
			
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar EDITAR EL DOC VALORIZACIONES
	public function editar_valorizacion($idproyecto, $doc7)
	{
		 
		$sql="UPDATE proyecto SET excel_valorizaciones = '$doc7' WHERE idproyecto = '$idproyecto'";
			
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para empezar proyecto
	public function empezar_proyecto($idproyecto)
	{
		$sql="UPDATE proyecto SET estado='1' WHERE idproyecto='$idproyecto'";

		return ejecutarConsulta($sql);
	}

	//Implementamos un método para terminar proyecto
	public function terminar_proyecto($idproyecto)
	{
		$sql="UPDATE proyecto SET estado='0' WHERE idproyecto='$idproyecto'";

		return ejecutarConsulta($sql);
	}

	//Implementamos un método para reniciar proyecto
	public function reiniciar_proyecto($idproyecto)
	{
		$sql="UPDATE proyecto SET estado='2' WHERE idproyecto='$idproyecto'";

		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idproyecto)
	{
		$sql="SELECT * FROM proyecto WHERE idproyecto='$idproyecto'";

		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM proyecto as p WHERE p.estado = 1 OR p.estado = 2;";
		return ejecutarConsulta($sql);		
	}

	//Implementar un método para listar los registros de proyectos terminados
	public function listar_proyectos_terminados()
	{
		$sql="SELECT * FROM proyecto as p WHERE p.estado = 0;";
		return ejecutarConsulta($sql);		
	}
	// obtebnemos los DOCS para eliminar
	public function obtenerDocs($idproyecto) {

        $sql = "SELECT doc1_contrato_obra, doc2_entrega_terreno, doc3_inicio_obra, doc4_presupuesto, doc5_analisis_costos_unitarios, doc6_insumos FROM proyecto WHERE idproyecto='$idproyecto'";

        return ejecutarConsulta($sql);
    }

	// optenemos el total de PROYECTOS
	public function tablero_proyectos() {

        $sql = "SELECT COUNT(p.empresa) AS cantidad_proyectos FROM proyecto AS p;";
		
        return ejecutarConsultaSimpleFila($sql);
    }
	// optenemos el total de PROVEEDORES
	public function tablero_proveedores() {

        $sql = "SELECT COUNT(p.idproveedor) AS cantidad_proveedores FROM proveedor AS p WHERE p.estado = 1;";
		
        return ejecutarConsultaSimpleFila($sql);
    }
	// optenemos el total de TRABAJADORES
	public function tablero_trabajadores() {

        $sql = "SELECT COUNT(t.nombres) AS cantidad_trabajadores FROM trabajador AS t WHERE t.estado = 1;";
		
        return ejecutarConsultaSimpleFila($sql);
    }
	// optenemos el total de SERVICIO
	public function tablero_servicio() {

        $sql = "SELECT COUNT(s.idservicio) AS cantidad_servicios FROM servicio AS s;";
		
        return ejecutarConsultaSimpleFila($sql);
    }
}

?>