<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Allmaquinarias
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  //Implementamos un método para insertar registros
  public function insertar($nombre_maquina, $codigo_m, $proveedor, $tipo)
  {
    $sql = "INSERT INTO maquinaria (nombre,codigo_maquina,idproveedor,tipo,user_created)
		VALUES ('$nombre_maquina','$codigo_m','$proveedor','$tipo','" . $_SESSION['idusuario'] . "')";
      $intertar =  ejecutarConsulta_retornarID($sql); 
      if ($intertar['status'] == false) {  return $intertar; } 

      //add registro en nuestra bitacora
      $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('maquinaria','".$intertar['data']."','Nueva maquina registrada','" . $_SESSION['idusuario'] . "')";
      $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   

      return $intertar;
  }

  //Implementamos un método para editar registros
  public function editar($idmaquinaria, $nombre_maquina, $codigo_m, $proveedor, $tipo)
  {
    $sql = "UPDATE maquinaria SET 
		nombre='$nombre_maquina',
		codigo_maquina='$codigo_m',
		idproveedor='$proveedor',
		tipo='$tipo',
    user_updated= '" . $_SESSION['idusuario'] . "'
		WHERE idmaquinaria ='$idmaquinaria'";

    $editar =  ejecutarConsulta($sql);
    if ( $editar['status'] == false) {return $editar; } 

    //add registro en nuestra bitacora
    $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('maquinaria','$idmaquinaria','Maquina editada','" . $_SESSION['idusuario'] . "')";
    $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }  

    return $editar;

  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idmaquinaria)
  {
    $sql = "UPDATE maquinaria SET estado='0',user_trash= '" . $_SESSION['idusuario'] . "'  WHERE idmaquinaria='$idmaquinaria'";
    $desactivar= ejecutarConsulta($sql);

    if ($desactivar['status'] == false) {  return $desactivar; }

    //add registro en nuestra bitacora
    $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('maquinaria','".$idmaquinaria."','Maquina desactivada','" . $_SESSION['idusuario'] . "')";
    $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   

    return $desactivar;

  }

  //Implementamos un método para activar categorías
  public function activar($idmaquinaria)
  {
    $sql = "UPDATE maquinaria SET estado='1' WHERE idmaquinaria='$idmaquinaria'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para activar categorías
  public function eliminar($idmaquinaria)
  {
    $sql = "UPDATE maquinaria SET estado_delete='0',user_delete= '" . $_SESSION['idusuario'] . "' WHERE idmaquinaria='$idmaquinaria'";
    $eliminar =  ejecutarConsulta($sql);

    if ( $eliminar['status'] == false) {return $eliminar; }  

    //add registro en nuestra bitacora
    $sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('maquinaria','$idmaquinaria','Maquinaria Eliminada','" . $_SESSION['idusuario'] . "')";
    $bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  

    return $eliminar;
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idmaquinaria)
  {
    $sql = "SELECT 
		mq.idmaquinaria as idmaquinaria,
		mq.idproveedor as idproveedor,
		p.razon_social as razon_social,
		mq.nombre as nombre, 
		mq.codigo_maquina as modelo, 
		mq.tipo as tipo, 
		mq.estado as estado
		FROM maquinaria as mq, proveedor as p WHERE mq.idmaquinaria='$idmaquinaria' AND mq.idproveedor=p.idproveedor";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para listar maquinas y equipos
  public function listar($tipo)
  {
    $sql = "SELECT 
		mq.idmaquinaria as idmaquinaria,
		p.razon_social as razon_social,
		mq.nombre as nombre, 
		mq.codigo_maquina as modelo, 
		mq.tipo as tipo, 
		mq.estado as estado
		
		FROM maquinaria as mq, proveedor as p WHERE mq.idproveedor=p.idproveedor AND mq.tipo='$tipo' AND mq.estado_delete='1'  AND mq.estado='1' ORDER BY  mq.nombre ASC";
    return ejecutarConsulta($sql);
  }
  //Seleccionar Trabajador Select2
  public function select2_proveedor()
  {
    $sql = "SELECT idproveedor,razon_social,ruc FROM proveedor WHERE estado='1' AND estado_delete='1'";
    return ejecutarConsulta($sql);
  }
}

?>
