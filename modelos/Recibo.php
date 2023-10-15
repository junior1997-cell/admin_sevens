<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Recibo
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  public function insertar($idproyecto,$tipo_documento,$num_documento,$nombre,$fecha_pago,$monto,$costo,$servicio,$recibo,$voucher)
  {

    $sql = "INSERT INTO recibo_x_honorario(idproyecto,tipo_documento, numero_documento,nombres, fecha_pago, monto_total, costo_operacion, servicio,recibo, voucher,user_created) 
    VALUES ('$idproyecto','$tipo_documento','$num_documento','$nombre','$fecha_pago','$monto','$costo','$servicio','$recibo','$voucher','" . $_SESSION['idusuario'] . "')";
    return ejecutarConsulta($sql);

  }

  //Implementamos un método para editar registros
  public function editar($idproyecto,$idrecibo_x_honorario,$tipo_documento,$num_documento,$nombre,$fecha_pago,$monto,$costo,$servicio,$recibo,$voucher)
  {
    $sql = "UPDATE recibo_x_honorario 
    SET idproyecto='$idproyecto',nombres='$nombre',tipo_documento='$tipo_documento',
    numero_documento='$num_documento',fecha_pago='$fecha_pago',servicio='$servicio',
    monto_total='$monto',recibo='$recibo',voucher='$voucher',
    costo_operacion='$costo' WHERE idrecibo_x_honorario='$idrecibo_x_honorario'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idrecibo_x_honorario)
  {
    $sql = "UPDATE recibo_x_honorario SET estado='0' WHERE idrecibo_x_honorario ='$idrecibo_x_honorario'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para desactivar categorías
  public function eliminar($idrecibo_x_honorario)
  {
    $sql = "UPDATE recibo_x_honorario SET estado_delete='0' WHERE idrecibo_x_honorario ='$idrecibo_x_honorario'";
    return ejecutarConsulta($sql);
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idrecibo_x_honorario)
  {
    $sql = "SELECT*FROM recibo_x_honorario WHERE idrecibo_x_honorario ='$idrecibo_x_honorario'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para listar los registros
  public function listar($idproyecto)
  {
    $sql = "SELECT idrecibo_x_honorario, idproyecto, nombres, tipo_documento, numero_documento, fecha_pago, servicio, monto_total, recibo, voucher, costo_operacion 
    FROM recibo_x_honorario WHERE idproyecto='$idproyecto' and estado='1' and estado_delete='1' ORDER BY idrecibo_x_honorario DESC";
    return ejecutarConsulta($sql);
  }

  //Seleccionar un comprobante
  public function eliminar_img($idrecibo_x_honorario) {
    $sql = "SELECT recibo, voucher FROM recibo_x_honorario WHERE idrecibo_x_honorario='$idrecibo_x_honorario'";
    return ejecutarConsulta($sql);
  }



}

?>
