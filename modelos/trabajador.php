<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Trabajador
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  //Implementamos un método para insertar registros
  public function insertar($idproyecto, $trabajador, $cargo, $desempenio, $sueldo_mensual, $sueldo_diario, $sueldo_hora, $fecha_inicio, $fecha_fin, $cantidad_dias)
  {
    $sql = "INSERT INTO trabajador_por_proyecto (idproyecto, idtrabajador, idcargo_trabajador, desempenio, sueldo_mensual, sueldo_diario, sueldo_hora, fecha_inicio, fecha_fin, cantidad_dias)
		VALUES ('$idproyecto', '$trabajador', '$cargo', '$desempenio', '$sueldo_mensual', '$sueldo_diario', '$sueldo_hora', '$fecha_inicio', '$fecha_fin', '$cantidad_dias')";

    return ejecutarConsulta($sql);
  }

  //Implementamos un método para editar registros
  public function editar($idtrabajador_por_proyecto, $trabajador, $cargo, $desempenio, $sueldo_mensual, $sueldo_diario, $sueldo_hora, $fecha_inicio, $fecha_fin, $cantidad_dias)
  {
    $sql = "UPDATE trabajador_por_proyecto SET  idtrabajador='$trabajador',  idcargo_trabajador ='$cargo', desempenio='$desempenio', 
		sueldo_mensual='$sueldo_mensual', sueldo_diario='$sueldo_diario', sueldo_hora='$sueldo_hora', fecha_inicio='$fecha_inicio', fecha_fin='$fecha_fin', cantidad_dias='$cantidad_dias'
		WHERE idtrabajador_por_proyecto='$idtrabajador_por_proyecto'";

    return ejecutarConsulta($sql);
  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idtrabajador)
  {
    $sql = "UPDATE trabajador_por_proyecto SET estado='0' WHERE idtrabajador_por_proyecto='$idtrabajador'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para activar categorías
  public function activar($idtrabajador)
  {
    $sql = "UPDATE trabajador_por_proyecto SET estado='1' WHERE idtrabajador_por_proyecto='$idtrabajador'";
    return ejecutarConsulta($sql);
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idtrabajador)
  {
    $sql = "SELECT  
			tp.idtrabajador_por_proyecto, 
			tp.idtrabajador,
			tp.idproyecto,
			tp.idcargo_trabajador,
			tp.desempenio,
			tp.sueldo_mensual,
			tp.sueldo_diario,
			tp.sueldo_hora,
			tp.fecha_inicio, tp.fecha_fin, tp.cantidad_dias,
			tt.idtipo_trabajador,
			ct.idcargo_trabajador,
			o.nombre_ocupacion
		FROM trabajador_por_proyecto as tp, trabajador as t, cargo_trabajador as ct, tipo_trabajador as tt, ocupacion as o
		WHERE tp.idtrabajador_por_proyecto='$idtrabajador' 
			AND ct.idcargo_trabajador=tp.idcargo_trabajador 
			AND ct.idtipo_trabjador=tt.idtipo_trabajador
			AND t.idocupacion=o.idocupacion
			AND t.idtrabajador = tp.idtrabajador";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function verdatos($idtrabajador)
  {
    $sql = "SELECT 
		t.idbancos as idbancos, 
		t.nombres as nombres, 
		t.tipo_documento as tipo_documento, 
		t.numero_documento as numero_documento,
		t.fecha_nacimiento as fecha_nacimiento,
		tp.desempenio as desempeno,
		tp.idcargo_trabajador  as cargo,
		t.cuenta_bancaria as cuenta_bancaria,
		t.titular_cuenta as titular_cuenta,
		tp.sueldo_mensual as sueldo_mensual,
		tp.sueldo_diario as sueldo_diario,
		tp.sueldo_hora as sueldo_hora,
		tp.fecha_inicio, tp.fecha_fin, tp.cantidad_dias,
		t.direccion as direccion,
		t.telefono as telefono,
		t.email as email,
		t.imagen_perfil as imagen,
		b.nombre as banco,  tt.nombre tipo_trabajador,  ct.nombre cargo_trabajador
		FROM trabajador AS t, bancos AS b,  trabajador_por_proyecto AS tp, cargo_trabajador as ct, tipo_trabajador as tt
		WHERE tp.idtrabajador = t.idtrabajador AND tp.idtrabajador_por_proyecto = '$idtrabajador' AND t.idbancos =b.idbancos AND ct.idcargo_trabajador= tp.idcargo_trabajador AND ct.idtipo_trabjador=tt.idtipo_trabajador";

    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para listar los registros
  public function listar($nube_idproyecto)
  {
    $sql = "SELECT t.idtrabajador, t.nombres, t.tipo_documento, t.numero_documento, t.cuenta_bancaria_format AS cuenta_bancaria, t.imagen_perfil as imagen, 
		tp.idcargo_trabajador , tp.desempenio, tp.sueldo_mensual, tp.sueldo_diario, tp.sueldo_hora, tp.fecha_inicio, tp.fecha_fin, tp.estado, tp.idtrabajador_por_proyecto, 
		tp.estado, b.nombre as banco, ct.nombre as cargo, ct.idtipo_trabjador as idtipo_trabjador, tt.nombre as nombre_tipo
		FROM trabajador_por_proyecto as tp, trabajador as t, proyecto AS p, bancos AS b, cargo_trabajador as ct, tipo_trabajador as tt
		WHERE tp.idproyecto = p.idproyecto AND tp.idproyecto = '$nube_idproyecto'   AND tp.idtrabajador = t.idtrabajador AND t.idbancos = b.idbancos AND
		ct.idcargo_trabajador=tp.idcargo_trabajador AND tt.idtipo_trabajador=ct.idtipo_trabjador ORDER BY t.nombres ASC";
    return ejecutarConsulta($sql);
  }

  //Seleccionar Trabajador Select2
  public function select2_trabajador()
  {
    $sql = "SELECT idtrabajador as id, nombres as nombre, tipo_documento as documento, numero_documento FROM trabajador WHERE estado='1';";
    return ejecutarConsulta($sql);
  }

  //Seleccionar Trabajador Select2
  public function m_datos_trabajador($idtrabajador)
  {
    $sql = "SELECT
		t.numero_documento,
		t.idtipo_trabajador as idtipo_trabajador,
		t.idocupacion,
		o.nombre_ocupacion as nombre_ocupacion
		FROM trabajador  as t, ocupacion as o
		WHERE t.idtrabajador='$idtrabajador' AND t.estado='1' AND t.idocupacion=o.idocupacion";

    return ejecutarConsultaSimpleFila($sql);
  }

  //Seleccionar Trabajador Select2
  public function select_cargo($id_tipo)
  {
    $sql = "SELECT * FROM cargo_trabajador 
		WHERE idtipo_trabjador='$id_tipo' AND estado=1";

    return ejecutarConsulta($sql);
  }
}

?>
