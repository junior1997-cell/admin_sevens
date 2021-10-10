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
	public function insertar($tipo_documento,$numero_documento,$empresa,$nombre_proyecto,$ubicacion,$actividad_trabajo,$empresa_acargo,$costo,$fecha_inicio,$fecha_fin,$doc1,$doc2,$doc3)
	{
		// insertamos al usuario
		$sql="INSERT INTO proyecto ( tipo_documento, numero_documento, empresa,nombre_proyecto, ubicacion, actividad_trabajo, empresa_acargo, costo, fecha_inicio, fecha_fin, doc1_contrato_obra, doc2_entrega_terreno, doc3_inicio_obra) 
		VALUES ('$tipo_documento','$numero_documento','$empresa','$nombre_proyecto','$ubicacion','$actividad_trabajo','$empresa_acargo','$costo','$fecha_inicio','$fecha_fin','$doc1','$doc2','$doc3');";
		return ejecutarConsulta($sql);
		// $sql2=	$tipo_documento.$numero_documento.$empresa.$nombre_proyecto.$ubicacion.$actividad_trabajo.$empresa_acargo.$costo.$fecha_inicio.$fecha_fin.$doc1.$doc2.$doc3;
		 
		// return $sql;
	 	
	}

	//Implementamos un método para editar registros
	public function editar($idusuario,$trabajador_old,$trabajador,$cargo,$login,$clave,$permisos)
	{
		 
		$sql="UPDATE proyecto SET idtrabajador='$trabajador', cargo='$cargo',login='$login',password='$clave' WHERE idusuario='$idusuario'";
			
		ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($idproyecto)
	{
		$sql="UPDATE proyecto SET estado='0' WHERE idproyecto='$idproyecto'";

		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idproyecto)
	{
		$sql="UPDATE proyecto SET estado='1' WHERE idproyecto='$idproyecto'";

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

        $sql = "SELECT doc1_contrato_obra FROM proyecto WHERE idproyecto='$idproyecto'";
		
        return ejecutarConsulta($sql);
    }

	public function obtenerDoc3($idproyecto) {

        $sql = "SELECT doc1_contrato_obra FROM proyecto WHERE idproyecto='$idproyecto'";
		
        return ejecutarConsulta($sql);
    }
}

?>