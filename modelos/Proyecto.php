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
	public function insertar($tipo_documento,$numero_documento,$empresa,$nombre_proyecto,$ubicacion,$actividad_trabajo,$empresa_acargo,$costo,$fecha_inicio,$fecha_fin,$plazo,$doc1,$doc2,$doc3)
	{
		// insertamos al usuario
		$sql="INSERT INTO proyecto ( tipo_documento, numero_documento, empresa,nombre_proyecto, ubicacion, actividad_trabajo, empresa_acargo, costo, fecha_inicio, fecha_fin, plazo, doc1_contrato_obra, doc2_entrega_terreno, doc3_inicio_obra) 
		VALUES ('$tipo_documento','$numero_documento','$empresa','$nombre_proyecto','$ubicacion','$actividad_trabajo','$empresa_acargo','$costo','$fecha_inicio','$fecha_fin','$plazo','$doc1','$doc2','$doc3');";
		return ejecutarConsulta($sql);
		// $sql2=	$tipo_documento.$numero_documento.$empresa.$nombre_proyecto.$ubicacion.$actividad_trabajo.$empresa_acargo.$costo.$fecha_inicio.$fecha_fin.$doc1.$doc2.$doc3;
		 
		// return $sql;
	 	
	}

	//Implementamos un método para editar registros
	public function editar($idproyecto,$tipo_documento,$numero_documento,$empresa,$nombre_proyecto,$ubicacion,$actividad_trabajo,$empresa_acargo,$costo,$fecha_inicio,$fecha_fin,$plazo,$doc1,$doc2,$doc3)
	{
		 
		$sql="UPDATE proyecto SET tipo_documento = '$tipo_documento', numero_documento = '$numero_documento', 
			empresa = '$empresa', nombre_proyecto = '$nombre_proyecto', ubicacion = '$ubicacion',
			actividad_trabajo = '$actividad_trabajo', empresa_acargo = '$empresa_acargo', 
			costo = '$costo', fecha_inicio = '$fecha_inicio', fecha_fin = '$fecha_fin', plazo = '$plazo', 
			doc1_contrato_obra = '$doc1', doc2_entrega_terreno = '$doc2', doc3_inicio_obra = '$doc3'
		 WHERE idproyecto='$idproyecto'";
			
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar categorías
	public function empezar_proyecto($idproyecto)
	{
		$sql="UPDATE proyecto SET estado='1' WHERE idproyecto='$idproyecto'";

		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function terminar_proyecto($idproyecto)
	{
		$sql="UPDATE proyecto SET estado='0' WHERE idproyecto='$idproyecto'";

		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
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
		$sql="SELECT *	FROM admin_sevens.proyecto;";
		return ejecutarConsulta($sql);		
	}

	public function obtenerDoc1($idproyecto) {

        $sql = "SELECT doc1_contrato_obra FROM proyecto WHERE idproyecto='$idproyecto'";

        return ejecutarConsulta($sql);
    }

	public function obtenerDoc2($idproyecto) {

        $sql = "SELECT doc2_entrega_terreno FROM proyecto WHERE idproyecto='$idproyecto'";
		
        return ejecutarConsulta($sql);
    }

	public function obtenerDoc3($idproyecto) {

        $sql = "SELECT doc3_inicio_obra FROM proyecto WHERE idproyecto='$idproyecto'";
		
        return ejecutarConsulta($sql);
    }
}

?>