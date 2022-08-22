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
      $total = ejecutarConsultaSimpleFila($sql_2);
      if ($total['status'] == false) { return $total; }

      $data[] = [
        'idpension' => $value['idpension'],
        'idproyecto' => $value['idproyecto'],
        'idproveedor' => $value['idproveedor'],
        'descripcion' => $value['descripcion'],
        'razon_social' => $value['razon_social'],
        'direccion' => $value['direccion'],
        'estado' => $value['estado'],
        'updated_at' => $value['updated_at'],
        'total_gasto' => (empty($total['data']) ? 0 : ( empty($total['data']['total_gasto']) ? 0 : floatval( $total['data']['total_gasto'])) ),
      ];
    }   
    return $retorno = ['status'=> true, 'message'=> 'todo oka bro', 'data'=> $data,];
  }

  public function insertar_pension($idproyecto_p, $proveedor, $descripcion_pension)  {

    $sql_1 = "SELECT p.idproyecto, p.idproveedor, p.estado,p.created_at, p.estado_delete, pr.razon_social, pr.tipo_documento, pr.ruc
    FROM pension as p, proveedor as pr
    WHERE  p.idproveedor = pr.idproveedor AND  p.idproyecto = '$idproyecto_p' AND p.idproveedor = '$proveedor';";

    $val_compr = ejecutarConsultaArray($sql_1);

    if ($val_compr['status'] == false) { return  $val_compr;}

    if (empty($val_compr['data'])) {
    
      $sql = "INSERT INTO pension(idproyecto,idproveedor,descripcion) VALUES ('$idproyecto_p','$proveedor','$descripcion_pension')";
      return  ejecutarConsulta($sql);

    } else {

      $info_repetida = '';

      foreach ($val_compr['data'] as $key => $value) {
        //$fecha = strtotime($value['created_at']);
        $info_repetida .= '<li class="text-left font-size-13px">
        <b>Pensión - Razón social: </b>'.$value['razon_social'].'<br>
        <b>Ruc: </b>'.$value['ruc'].'<br>
        <b>Fecha de cración: </b>'.extr_fecha_creacion($value['created_at']).'<br>
        <hr class="m-t-2px m-b-2px">
        </li>';
      }
      return $sw = array( 'status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ol>'.$info_repetida.'</ol>', 'id_tabla' => '' );
    }

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

  public function tbla_detalle_pension($idpension, $fecha_1, $fecha_2, $id_proveedor, $comprobante) {

    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = ""; 

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
    } else if (!empty($fecha_1)) {      
      $filtro_fecha = "AND fecha_emision = '$fecha_1'";
    }else if (!empty($fecha_2)) {        
      $filtro_fecha = "AND fecha_emision = '$fecha_2'";
    }    

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND tipo_comprobante = '$comprobante'"; 
    } 

    $sql = "SELECT * FROM detalle_pension 
    WHERE  idpension ='$idpension' AND estado='1' AND  estado_delete='1'  $filtro_comprobante $filtro_fecha
    ORDER BY fecha_inicial DESC";
    return ejecutarConsulta($sql);
  }

  public function total_detalle_pension($idpension, $fecha_1, $fecha_2, $id_proveedor, $comprobante) {

    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = ""; 

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
    } else if (!empty($fecha_1)) {      
      $filtro_fecha = "AND fecha_emision = '$fecha_1'";
    }else if (!empty($fecha_2)) {        
      $filtro_fecha = "AND fecha_emision = '$fecha_2'";
    }    

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND tipo_comprobante = '$comprobante'"; 
    } 

    $sql = "SELECT SUM(cantidad_persona) AS total_pers, SUM(precio_parcial) AS total_monto,  SUM(subtotal) as subtotal,  SUM(igv) as igv
    FROM detalle_pension 
    WHERE idpension='$idpension' AND estado='1' AND estado_delete='1' $filtro_comprobante $filtro_fecha;";
    return ejecutarConsultaSimpleFila($sql);

  }

  public function insertar_detalles_pension($id_pension,$fecha_inicial,$fecha_final,$cantidad_persona,$subtotal,$igv,$val_igv,$monto,$forma_pago,$tipo_comprobante,$fecha_emision,$tipo_gravada,$nro_comprobante,$descripcion_detalle,$imagen2)  {
    
    $sql_1="SELECT idpension, forma_pago, tipo_comprobante, numero_comprobante,created_at, estado, estado_delete 
    FROM detalle_pension WHERE idpension = '$id_pension' AND tipo_comprobante='$tipo_comprobante' AND numero_comprobante='$nro_comprobante';";

    $val_compr = ejecutarConsultaArray($sql_1);

    if ($val_compr['status'] == false) { return  $val_compr;}

    if (empty($val_compr['data']) || $tipo_comprobante=='Ninguno') {

      $sql = "INSERT INTO detalle_pension(idpension, fecha_inicial, fecha_final, cantidad_persona, subtotal, igv, val_igv, precio_parcial, forma_pago, tipo_comprobante, fecha_emision, tipo_gravada, glosa, numero_comprobante, descripcion, comprobante) 
      VALUES ('$id_pension','$fecha_inicial','$fecha_final','$cantidad_persona','$subtotal','$igv','$val_igv','$monto','$forma_pago','$tipo_comprobante','$fecha_emision','$tipo_gravada','ALIMENTACION','$nro_comprobante','$descripcion_detalle','$imagen2')";
      return  ejecutarConsulta($sql);

    } else {

      $info_repetida = '';

      foreach ($val_compr['data'] as $key => $value) {
        $info_repetida .= '<li class="text-left font-size-13px">
        <span class="font-size-18px text-danger"><b >'.$value['tipo_comprobante'].': </b> '.$value['numero_comprobante'].'</span><br>
        <b>Fecha creación: </b>'.extr_fecha_creacion($value['created_at']).'<br>
        <b>Forma de pago: </b>'.$value['forma_pago'].'<br>
        <b>Papelera: </b>'.( $value['estado']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO') .' <b>|</b>
        <b>Eliminado: </b>'. ($value['estado_delete']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO').'<br>
        <hr class="m-t-2px m-b-2px">
        </li>';
      }
      return $sw = array( 'status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ol>'.$info_repetida.'</ol>', 'id_tabla' => '' );
    }

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
  
  //Implementamos un método para activar
  public function desactivar_detalle_comprobante($iddetalle_pension)  {
    $sql = "UPDATE detalle_pension SET estado_delete='0' WHERE iddetalle_pension ='$iddetalle_pension'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para activar
  public function eliminar_detalle_comprobante($iddetalle_pension)  {
    $sql = "UPDATE detalle_pension SET estado='0' WHERE iddetalle_pension ='$iddetalle_pension'";
    return ejecutarConsulta($sql);
  }

  // obtebnemos los DOCS para eliminar
  public function obtenerDoc($iddetalle_pension)  {
    $sql = "SELECT comprobante FROM detalle_pension WHERE iddetalle_pension='$iddetalle_pension'";

    return ejecutarConsulta($sql);
  }

  
}

?>
