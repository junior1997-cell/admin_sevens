<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Mano_de_obra
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  // :::::::::::::::::::::::::: S E C C I O N   P E N S I O N  ::::::::::::::::::::::::::::::::::::::::::
  public function tabla_principal($nube_idproyecto, $fecha_1, $fecha_2, $id_proveedor, $comprobante)  {
    $data = [];

    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = ""; 

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND mdo.fecha_deposito BETWEEN '$fecha_1' AND '$fecha_2'";
    } else if (!empty($fecha_1)) {      
      $filtro_fecha = "AND mdo.fecha_deposito = '$fecha_1'";
    }else if (!empty($fecha_2)) {        
      $filtro_fecha = "AND mdo.fecha_deposito = '$fecha_2'";
    }    

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND mdo.idproveedor = '$id_proveedor'"; }

    $sql = "SELECT mdo.idmano_de_obra, mdo.idproyecto, mdo.idproveedor, mdo.fecha_inicial, mdo.fecha_final, mdo.fecha_deposito, mdo.monto, mdo.descripcion, p.razon_social, p.tipo_documento, p.ruc 
    FROM mano_de_obra as mdo, proveedor as p
    WHERE mdo.idproveedor = p.idproveedor and mdo.idproyecto = '$nube_idproyecto' AND mdo.estado='1' AND mdo.estado_delete='1' $filtro_proveedor $filtro_fecha ORDER BY p.razon_social ASC ";
    return ejecutarConsultaArray($sql);

  }

  public function total_mdo($idproyecto, $fecha_1, $fecha_2, $id_proveedor, $comprobante ) {

    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = ""; 

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND fecha_inicial BETWEEN '$fecha_1' AND '$fecha_2'";
    } else if (!empty($fecha_1)) {      
      $filtro_fecha = "AND fecha_inicial = '$fecha_1'";
    }else if (!empty($fecha_2)) {        
      $filtro_fecha = "AND fecha_inicial = '$fecha_2'";
    }    

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND idproveedor = '$id_proveedor'"; }    

    $sql = "SELECT SUM( monto ) as total FROM mano_de_obra WHERE idproyecto ='$idproyecto' AND estado = '1' AND estado_delete= '1' $filtro_proveedor $filtro_fecha;";
    return ejecutarConsultaSimpleFila($sql);

  }

  public function insertar_mdo($idproyecto, $idproveedor, $ruc_proveedor, $fecha_inicial, $fecha_final, $fecha_deposito, $monto, $descripcion)  {

    $sql_1 = "SELECT mdo.idmano_de_obra, mdo.idproyecto, mdo.idproveedor, mdo.fecha_inicial, mdo.fecha_final, mdo.fecha_deposito, mdo.monto, mdo.descripcion, p.razon_social, p.tipo_documento, p.ruc 
    FROM mano_de_obra as mdo, proveedor as p
    WHERE mdo.idproveedor = p.idproveedor and mdo.idproyecto = '$idproyecto' AND p.ruc = '$ruc_proveedor' AND mdo.fecha_deposito = '$fecha_deposito' ;";

    $val_compr = ejecutarConsultaArray($sql_1);
    if ($val_compr['status'] == false) { return  $val_compr;}

    if (empty($val_compr['data'])) {
    
      $sql = "INSERT INTO mano_de_obra( idproyecto, idproveedor, fecha_inicial, fecha_final, fecha_deposito, monto, descripcion, user_created)
      VALUES ('$idproyecto','$idproveedor','$fecha_inicial','$fecha_final', '$fecha_deposito', '$monto', '$descripcion', '" . $_SESSION['idusuario'] . "')";
      $insert =  ejecutarConsulta_retornarID($sql);

      //add registro en nuestra bitacora
      $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('mano_de_obra','".$insert['data']."','Agregar Mano de Obra','" . $_SESSION['idusuario'] . "')";
      $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }  

      return $insert ;
    } else {

      $info_repetida = '';

      foreach ($val_compr['data'] as $key => $value) {
        //$fecha = strtotime($value['created_at']);
        $info_repetida .= '<li class="text-left font-size-13px">
        <b>Razón social: </b>'.$value['razon_social'].'<br>
        <b>'.$value['tipo_documento'].': </b>'.$value['ruc'].'<br>
        <b>Fecha de cración: </b>'.extr_fecha_creacion($value['created_at']).'<br>
        <b>Periodo: </b>'.format_d_m_a($value['fecha_inicial']) .' - '. format_d_m_a($value['fecha_final']).'<br>
        <hr class="m-t-2px m-b-2px">
        </li>';
      }
      return $sw = array( 'status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ol>'.$info_repetida.'</ol>', 'id_tabla' => '' );
    }

  }

  public function editar_mdo($idmano_de_obra, $idproyecto, $idproveedor, $fecha_inicial, $fecha_final, $fecha_deposito, $monto, $descripcion) {
    $sql = "UPDATE mano_de_obra SET idproyecto='$idproyecto',idproveedor='$idproveedor',fecha_inicial='$fecha_inicial',
    fecha_final='$fecha_final', fecha_deposito='$fecha_deposito', monto='$monto',descripcion='$descripcion', user_updated= '" . $_SESSION['idusuario'] . "' 
    WHERE idmano_de_obra ='$idmano_de_obra'";
    $edita = ejecutarConsulta($sql);

    //add registro en nuestra bitacora
    $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('mano_de_obra','".$idmano_de_obra."','Editar Mano de Obra','" . $_SESSION['idusuario'] . "')";
    $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; } 
    
    return $edita;
  }

  public function mostrar_mdo($idmano_de_obra) {
    
    $sql = "SELECT mdo.idmano_de_obra, mdo.idproyecto, mdo.idproveedor, mdo.fecha_inicial, mdo.fecha_final, mdo.fecha_deposito, mdo.monto, mdo.descripcion, 
    p.razon_social, p.tipo_documento, p.ruc 
    FROM mano_de_obra as mdo, proveedor as p
    WHERE mdo.idproveedor = p.idproveedor and idmano_de_obra = '$idmano_de_obra';";
    return ejecutarConsultaSimpleFila($sql);    
  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idmano_de_obra)
  {
    $sql = "UPDATE mano_de_obra SET estado='0',user_trash= '" . $_SESSION['idusuario'] . "' WHERE idmano_de_obra ='$idmano_de_obra'";
    $desactivar= ejecutarConsulta($sql);

    if ($desactivar['status'] == false) {  return $desactivar; }
    
    //add registro en nuestra bitacora
    $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('mano_de_obra','".$idmano_de_obra."','Papelera Mano de Obra','" . $_SESSION['idusuario'] . "')";
    $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
    
    return $desactivar;
  }

  //Implementamos un método para activar categorías
  public function activar($idmano_de_obra)
  {
    $sql = "UPDATE mano_de_obra SET estado='1' WHERE idmano_de_obra ='$idmano_de_obra'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para activar categorías
  public function eliminar($idmano_de_obra)
  {
    $sql = "UPDATE mano_de_obra SET estado_delete='0', user_delete= '" . $_SESSION['idusuario'] . "' WHERE idmano_de_obra ='$idmano_de_obra'";
    $eliminar =  ejecutarConsulta($sql);
    if ( $eliminar['status'] == false) {return $eliminar; }  
    
    //add registro en nuestra bitacora
    $sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('mano_de_obra','$idmano_de_obra','Eliminado Mano de Obra','" . $_SESSION['idusuario'] . "')";
    $bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
    
    return $eliminar;
  }  
  
}

?>
