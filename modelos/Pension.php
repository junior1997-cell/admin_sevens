<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Pension
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }
  // :::::::::::::::::::::::::: S E C C I O N   P E N S I O N  ::::::::::::::::::::::::::::::::::::::::::
  public function tabla_principal($nube_idproyecto)  {
    $data = [];
    $sql = "SELECT p.idpension, p.idproyecto, p.idproveedor, p.descripcion, pr_v.razon_social, pr_v.direccion, p.estado, p.updated_at
		FROM pension as p, proyecto as py, proveedor as pr_v
		WHERE p.idproyecto=py.idproyecto AND p.idproveedor=pr_v.idproveedor AND p.idproyecto='$nube_idproyecto' AND p.estado='1' AND p.estado_delete='1' ORDER BY pr_v.razon_social ASC ";
    $pension = ejecutarConsultaArray($sql);
    if ($pension['status'] == false) { return $pension; }
    
    foreach ($pension['data'] as $key => $value) {
      $id = $value['idpension'];
      $sql_2 = "SELECT SUM(precio_parcial) AS total_gasto FROM detalle_pension WHERE idpension ='$id' AND estado='1' AND  estado_delete='1';";
      $total_x_pension = ejecutarConsultaSimpleFila($sql_2);
      if ($total_x_pension['status'] == false) { return $total_x_pension; }

      $data[] = [
        'idpension' => $value['idpension'],
        'idproyecto' => $value['idproyecto'],
        'idproveedor' => $value['idproveedor'],
        'descripcion' => $value['descripcion'],
        'razon_social' => $value['razon_social'],
        'direccion' => $value['direccion'],
        'estado' => $value['estado'],
        'updated_at' => $value['updated_at'],
        'total_gasto' => (empty($total_x_pension['data']) ? 0 : ( empty($total_x_pension['data']['total_gasto']) ? 0 : $total_x_pension['data']['total_gasto'])),
      ];
    }   
    return $retorno = ['status'=> true, 'message'=> 'todo oka bro', 'data'=> $data,];
  }

  public function insertar_pension($idproyecto_p, $proveedor, $descripcion_pension)  {
   
    $sql = "INSERT INTO pension(idproyecto, idproveedor,descripcion) VALUES ('$idproyecto_p','$proveedor','$descripcion_pension')";
    return  ejecutarConsulta($sql);
  }

  public function editar_pension($idproyecto_p,$idpension,$proveedor,$descripcion_pension) {
    $sql = "UPDATE pension SET idproyecto='$idproyecto_p',idproveedor='$proveedor',descripcion='$descripcion_pension' WHERE idpension='$idpension'";
    return ejecutarConsulta($sql);
  }

  public function mostrar_pension($idpension) {
    
    $sql = "SELECT * FROM pension WHERE idpension = '$idpension';";
    return ejecutarConsultaSimpleFila($sql);    
  }

  public function total_pension($idproyecto ) {

    $sql = "SELECT SUM( dp.precio_parcial) as total FROM `pension` as p , detalle_pension as dp
    WHERE p.idpension = dp.idpension AND p.idproyecto ='$idproyecto' AND dp.estado = 1 AND dp.estado_delete=1;";
    return ejecutarConsultaSimpleFila($sql);

  }

  // :::::::::::::::::::::::::: S E C C I O N   D E T A L L E   P E N S I O N  ::::::::::::::::::::::::::
  public function tbla_detalle_pension($idpension)
  {
    $sql = "SELECT * FROM detalle_pension WHERE  idpension ='$idpension' AND estado='1' AND  estado_delete='1' ORDER BY fecha_inicial DESC";
    return ejecutarConsulta($sql);
  }

  public function insertar_detalles_pension($id_pension,$fecha_inicial,$fecha_final,$cantidad_persona,$subtotal,$igv,$val_igv,$monto,$forma_pago,$tipo_comprobante,$fecha_emision,$tipo_gravada,$nro_comprobante,$descripcion_detalle,$imagen2)  {
   
    $sql = "INSERT INTO detalle_pension(idpension, fecha_inicial, fecha_final, cantidad_persona, subtotal, igv, val_igv, precio_parcial, forma_pago, tipo_comprobante, fecha_emision, tipo_gravada, glosa, numero_comprobante, descripcion, comprobante) 
    VALUES ('$id_pension','$fecha_inicial','$fecha_final','$cantidad_persona','$subtotal','$igv','$val_igv','$monto','$forma_pago','$tipo_comprobante','$fecha_emision','$tipo_gravada','ALIMENTACION','$nro_comprobante','$descripcion_detalle','$imagen2')";
    return  ejecutarConsulta($sql);
  }

  public function editar_detalles_pension($iddetalle_pension,$id_pension,$fecha_inicial,$fecha_final,$cantidad_persona,$subtotal, $igv,$val_igv,$monto,$forma_pago,$tipo_comprobante,$fecha_emision,$tipo_gravada,$nro_comprobante,$descripcion_detalle,$imagen2) {
    $sql = "UPDATE detalle_pension SET idpension='$id_pension',fecha_inicial='$fecha_inicial',fecha_final='$fecha_final',
    cantidad_persona='$cantidad_persona',subtotal='$subtotal',igv='$igv',val_igv='$val_igv',precio_parcial='$monto',
    forma_pago='$forma_pago',tipo_comprobante='$tipo_comprobante',fecha_emision='$fecha_emision',glosa='ALIMENTACION', tipo_gravada='$tipo_gravada', numero_comprobante='$nro_comprobante',
    descripcion='$descripcion_detalle',comprobante='$imagen2' WHERE iddetalle_pension='$iddetalle_pension'";
    return ejecutarConsulta($sql);
  }

  public function mostrar_detalle_pension($iddetalle_pension) {

    $sql = "SELECT * FROM detalle_pension WHERE  iddetalle_pension ='$iddetalle_pension';";
    return ejecutarConsultaSimpleFila($sql);
     
  }

  public function total_detalle_pension($idpension) {

    $sql = "SELECT SUM(cantidad_persona) AS total_pers, SUM(precio_parcial) AS total_monto,  SUM(subtotal) as subtotal,  SUM(igv) as igv
    FROM detalle_pension 
    WHERE idpension='$idpension' AND estado='1' AND estado_delete='1';";
    return ejecutarConsultaSimpleFila($sql);

  }
  
  //Implementamos un método para activar
  public function desactivar_detalle_comprobante($iddetalle_pension)
  {
    $sql = "UPDATE detalle_pension SET estado_delete='0' WHERE iddetalle_pension ='$iddetalle_pension'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para activar
  public function eliminar_detalle_comprobante($iddetalle_pension)
  {
    $sql = "UPDATE detalle_pension SET estado='0' WHERE iddetalle_pension ='$iddetalle_pension'";
    return ejecutarConsulta($sql);
  }

  // obtebnemos los DOCS para eliminar
  public function obtenerDoc($iddetalle_pension)
  {
    $sql = "SELECT comprobante FROM detalle_pension WHERE iddetalle_pension='$iddetalle_pension'";

    return ejecutarConsulta($sql);
  }

  
}

?>
