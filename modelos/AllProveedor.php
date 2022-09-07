<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class AllProveedor
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  //Implementamos un método para insertar registros
  public function insertar($nombre, $tipo_documento, $num_documento, $direccion, $telefono, $c_bancaria, $cci, $c_detracciones, $banco, $titular_cuenta) {
    $sw = Array();
    $sql_0 = "SELECT * FROM proveedor WHERE ruc = '$num_documento' ";
    $existe = ejecutarConsultaArray($sql_0);
    if ($existe['status'] == false) {  return $existe; }

    if (empty($existe['data'])) {
      $sql = "INSERT INTO proveedor (idbancos, razon_social, tipo_documento, ruc, direccion, telefono, cuenta_bancaria, cci, cuenta_detracciones, titular_cuenta,user_created)
      VALUES ('$banco', '$nombre', '$tipo_documento', '$num_documento', '$direccion', '$telefono', '$c_bancaria', '$cci', '$c_detracciones', '$titular_cuenta','" . $_SESSION['idusuario'] . "')";
      $sw =  ejecutarConsulta_retornarID($sql);     
      if ($sw['status'] == false) {  return $sw; } 

      //add registro en nuestra bitacora
      $sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('proveedor','".$sw['data']."','Nuevo proveedor registrado','" . $_SESSION['idusuario'] . "')";
      $bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }   

    } else{

      $info_repetida = ''; 

      foreach ($existe['data'] as $key => $value) {
        $info_repetida .= '<li class="text-left font-size-13px">
          <b>Razón Social: </b>'.$value['razon_social'].'<br>
          <b>'.$value['tipo_documento'].': </b>'.$value['ruc'].'<br>
          <b>Papelera: </b>'.( $value['estado']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO') .'<br>
          <b>Eliminado: </b>'. ($value['estado_delete']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO').'<br>
          <hr class="m-t-2px m-b-2px">
        </li>'; 
      }
      $sw = array( 'status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ol>'.$info_repetida.'</ol>', 'id_tabla' => '' );
    }

    return $sw;
  }

  //Implementamos un método para editar registros
  public function editar($idproveedor, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $c_bancaria, $cci, $c_detracciones, $banco, $titular_cuenta)
  {
    //var_dump($idproveedor,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$c_bancaria,$c_detracciones,$banco,$titular_cuenta);die;

    $sql = "UPDATE proveedor SET idbancos='$banco',	razon_social='$nombre',	tipo_documento='$tipo_documento', ruc='$num_documento',
		direccion='$direccion',	telefono='$telefono',	cuenta_bancaria='$c_bancaria', cci='$cci', cuenta_detracciones='$c_detracciones',
		titular_cuenta='$titular_cuenta', user_updated= '" . $_SESSION['idusuario'] . "' WHERE idproveedor='$idproveedor'";

    $edit_proveedor = ejecutarConsulta_retornarID($sql);
    if ($edit_proveedor['status'] == false) {  return $edit_proveedor; }

    //add registro en nuestra bitacora
    $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('proveedor','$idproveedor','Proveedor editado','" . $_SESSION['idusuario'] . "')";
    $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   

    return $sw = array( 'status' => true, 'message' => 'solio oka', 'data' => $idproveedor, 'id_tabla' => $idproveedor );
  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idproveedor)
  {
    $sql = "UPDATE proveedor SET estado='0',user_trash= '" . $_SESSION['idusuario'] . "' WHERE idproveedor='$idproveedor'";
    $desactivar= ejecutarConsulta($sql);

    if ($desactivar['status'] == false) {  return $desactivar; }

    //add registro en nuestra bitacora
    $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('proveedor','".$idproveedor."','Proveedor desactivado','" . $_SESSION['idusuario'] . "')";
    $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   

    return $desactivar;
  }

  //Implementamos un método para activar categorías
  public function activar($idproveedor)
  {
    $sql = "UPDATE proveedor SET estado='1' WHERE idproveedor='$idproveedor'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para eliminar
  public function eliminar($idproveedor)
  {
    $sql = "UPDATE proveedor SET estado_delete='0',user_delete= '" . $_SESSION['idusuario'] . "' WHERE idproveedor='$idproveedor'";
    $eliminar =  ejecutarConsulta($sql);
      
    if ( $eliminar['status'] == false) {return $eliminar; }  

    //add registro en nuestra bitacora
    $sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('proveedor','$idproveedor','Provedor Eliminado','" . $_SESSION['idusuario'] . "')";
    $bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  

    return $eliminar;
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idproveedor)
  {
    $sql = "SELECT p.idproveedor, p.idbancos, p.razon_social, p.tipo_documento, p.ruc, p.direccion, p.telefono, p.cuenta_bancaria, 
    p.cci, p.cuenta_detracciones, p.titular_cuenta, p.updated_at, b.nombre AS nombre_banco, b.icono AS icono_banco
    FROM proveedor as p, bancos AS b
    WHERE p.idbancos = b.idbancos AND idproveedor = '$idproveedor'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para listar los registros
  public function tbla_principal()
  {
    $sql = "SELECT p.idproveedor, p.idbancos, p.razon_social, p.tipo_documento, p.ruc, p.direccion, p.telefono, p.cuenta_bancaria, 
    p.cci, p.cuenta_detracciones, p.titular_cuenta, p.estado, p.estado_delete, p.created_at, p.updated_at, b.nombre AS nombre_banco
    FROM proveedor AS p, bancos AS b
    WHERE p.idbancos = b.idbancos AND p.idproveedor>1 AND p.estado=1 AND p.estado_delete=1 
    ORDER BY  p.razon_social ASC";
    return ejecutarConsultaArray($sql);
  }  

}

?>
