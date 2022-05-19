<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class AllProveedor
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  //Implementamos un método para insertar registros
  public function insertar($nombre, $tipo_documento, $num_documento, $direccion, $telefono, $c_bancaria, $cci, $c_detracciones, $banco, $titular_cuenta) {
    $sw = Array();
    $sql_0 = "SELECT * FROM proveedor WHERE ruc = '$num_documento' AND razon_social = '$nombre'";
    $existe = ejecutarConsultaArray($sql_0);

    if (empty($existe['data'])) {
      $sql = "INSERT INTO proveedor (idbancos, razon_social, tipo_documento, ruc, direccion, telefono, cuenta_bancaria, cci, cuenta_detracciones, titular_cuenta)
      VALUES ('$banco', '$nombre', '$tipo_documento', '$num_documento', '$direccion', '$telefono', '$c_bancaria', '$cci', '$c_detracciones', '$titular_cuenta')";
      $sw =  ejecutarConsulta_retornarID($sql);      
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

    $sql = "UPDATE proveedor SET idbancos='$banco',
		razon_social='$nombre',
		tipo_documento='$tipo_documento', 
		ruc='$num_documento',
		direccion='$direccion',
		telefono='$telefono',
		cuenta_bancaria='$c_bancaria', cci='$cci', 
		cuenta_detracciones='$c_detracciones',
		titular_cuenta='$titular_cuenta' 
		WHERE idproveedor='$idproveedor'";

    return ejecutarConsulta($sql);
  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idproveedor)
  {
    $sql = "UPDATE proveedor SET estado='0' WHERE idproveedor='$idproveedor'";
    return ejecutarConsulta($sql);
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
    $sql = "UPDATE proveedor SET estado_delete='0' WHERE idproveedor='$idproveedor'";
    return ejecutarConsulta($sql);
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
