<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Trabajador
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($idproyecto,$trabajador, $tipo_trabajador, $cargo, $desempenio, $sueldo_mensual, $sueldo_diario, $sueldo_hora)
	{
		$sql="INSERT INTO trabajador_por_proyecto (idproyecto, idtrabajador, tipo_trabajador, cargo, desempenio, sueldo_mensual, sueldo_diario, sueldo_hora)
		VALUES ('$idproyecto', '$trabajador', '$tipo_trabajador', '$cargo', '$desempenio', '$sueldo_mensual', '$sueldo_diario', '$sueldo_hora')";
		
		return ejecutarConsulta($sql);
			
	}

	//Implementamos un método para editar registros
	public function editar( $idtrabajador_por_proyecto,$trabajador, $tipo_trabajador, $cargo, $desempenio, $sueldo_mensual, $sueldo_diario, $sueldo_hora )
	{
		$sql="UPDATE trabajador_por_proyecto SET  idtrabajador='$trabajador', 
    tipo_trabajador='$tipo_trabajador', cargo='$cargo', desempenio='$desempenio', sueldo_mensual='$sueldo_mensual', 
    sueldo_diario='$sueldo_diario',	sueldo_hora='$sueldo_hora' WHERE idtrabajador_por_proyecto='$idtrabajador_por_proyecto'";	
		
		return ejecutarConsulta($sql);
		
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($idtrabajador)
	{
		$sql="UPDATE trabajador_por_proyecto SET estado='0' WHERE idtrabajador_por_proyecto='$idtrabajador'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idtrabajador)
	{
		$sql="UPDATE trabajador_por_proyecto SET estado='1' WHERE idtrabajador_por_proyecto='$idtrabajador'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idtrabajador)
	{
		$sql="SELECT * FROM trabajador_por_proyecto WHERE idtrabajador_por_proyecto='$idtrabajador'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function verdatos($idtrabajador)
	{
		$sql="SELECT 
		t.idbancos as idbancos, 
		t.nombres as nombres, 
		t.tipo_documento as tipo_documento, 
		t.numero_documento as numero_documento,
		t.fecha_nacimiento as fecha_nacimiento,
		tp.desempenio as desempeno,
		tp.cargo as cargo,
		tp.tipo_trabajador as tipo_trabajador ,
		t.cuenta_bancaria as cuenta_bancaria,
		t.titular_cuenta as titular_cuenta,
		tp.sueldo_mensual as sueldo_mensual,
		tp.sueldo_diario as sueldo_diario,
		tp.sueldo_hora as sueldo_hora,
		t.direccion as direccion,
		t.telefono as telefono,
		t.email as email,
		t.imagen_perfil as imagen,
		b.nombre as banco 
		FROM trabajador AS t, bancos AS b,  trabajador_por_proyecto AS tp
		WHERE tp.idtrabajador = t.idtrabajador AND tp.idtrabajador_por_proyecto = '$idtrabajador' AND t.idbancos =b.idbancos;";

		return ejecutarConsultaSimpleFila($sql);
	}
	//Implementar un método para listar los registros
	public function listar($nube_idproyecto)
	{
		$sql="SELECT t.idtrabajador, t.nombres, t.tipo_documento, t.numero_documento, t.cuenta_bancaria, t.imagen_perfil as imagen, tp.tipo_trabajador, tp.cargo, tp.desempenio, tp.sueldo_mensual, tp.sueldo_diario, tp.sueldo_hora, tp.estado, tp.idtrabajador_por_proyecto, tp.estado, b.nombre as banco
		FROM trabajador_por_proyecto as tp, trabajador as t, proyecto AS p, bancos AS b
		WHERE tp.idproyecto = p.idproyecto AND tp.idproyecto = '$nube_idproyecto'   AND tp.idtrabajador = t.idtrabajador AND t.idbancos = b.idbancos;";
		return ejecutarConsulta($sql);		
	}

  	//Seleccionar Trabajador Select2
	public function select2_trabajador()
	{
		$sql="SELECT idtrabajador as id, nombres as nombre, tipo_documento as documento, numero_documento FROM trabajador WHERE estado='1';";
		return ejecutarConsulta($sql);		
	}
	//Seleccionar Trabajador Select2
		public function m_datos_trabajador($idtrabajador)
		{
			$sql="SELECT
			t.numero_documento,
			t.idtipo_trabajador as idtipo_trabajador,
            t.idocupacion,
            o.nombre_ocupacion as nombre_ocupacion
			FROM 
			trabajador  as t, 
            ocupacion as o
			WHERE 
			t.idtrabajador='$idtrabajador' AND t.estado='1' AND t.idocupacion=o.idocupacion";
			return ejecutarConsultaSimpleFila($sql);		
		}

}

?>