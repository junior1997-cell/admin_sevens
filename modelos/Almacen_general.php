<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Almacen_general
{
  //Implementamos nuestro variable global
	public $id_usr_sesion;

  //Implementamos nuestro constructor
	public function __construct($id_usr_sesion = 0)
	{
		$this->id_usr_sesion = $id_usr_sesion;
	}

  //Implementamos un método para insertar registros
  public function insertar( $nombre, $descripcion )  {

    $sql = "SELECT nombre_almacen, descripcion, estado, estado_delete FROM almacen_general WHERE nombre_almacen = '$nombre';";
    $buscando = ejecutarConsultaArray($sql); if ( $buscando['status'] == false) {return $buscando; } 

    
    if ( empty($buscando['data']) ) {
      $sql = "INSERT INTO almacen_general(nombre_almacen, descripcion, user_created ) VALUES ('$nombre','$descripcion', '$this->id_usr_sesion')";
      $insertar =  ejecutarConsulta_retornarID($sql); if ($insertar['status'] == false) {  return $insertar; } 

      //add registro en nuestra bitacora
      $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('nombre_almacen','".$insertar['data']."','Nuevo registrado','$this->id_usr_sesion')";
      $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }       
      
      return $insertar;

    } else {
      $info_repetida = ''; 

      foreach ($buscando['data'] as $key => $value) {
        $info_repetida .= '<li class="text-left font-size-13px">
          <b>Nombre: </b>'.$value['nombre_almacen'].'<br>
          <b>Descripcion: </b>'.$value['descripcion'].'<br>            
          <b>Papelera: </b>'.( $value['estado']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO') .'<br>
          <b>Eliminado: </b>'. ($value['estado_delete']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO').'<br>
          <hr class="m-t-2px m-b-2px">
        </li>'; 
      }
      $sw = array( 'status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ul>'.$info_repetida.'</ul>', 'id_tabla' => '' );
      return $sw;
    }      
    
  }

  //Implementamos un método para editar registros
  public function editar($idalmacen_general, $nombre, $descripcion)  {
    
    $sql = "UPDATE almacen_general SET nombre_almacen='$nombre', descripcion='$descripcion', user_updated = '$this->id_usr_sesion'
		WHERE idalmacen_general = '$idalmacen_general';";
    $editar =  ejecutarConsulta($sql); if ( $editar['status'] == false) {return $editar; }     

    //add registro en nuestra bitacora
    $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('almacen_general', '$idalmacen_general','Registro editado','$this->id_usr_sesion')";
    $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }  

    return $editar;
  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idalmacen_general) {
  
    $sql = "UPDATE almacen_general SET estado='0',user_trash= '$this->id_usr_sesion'  WHERE idalmacen_general ='$idalmacen_general'";
    $desactivar= ejecutarConsulta($sql); if ($desactivar['status'] == false) {  return $desactivar; }
    
    //add registro en nuestra bitacora
    $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('almacen_general','$idalmacen_general','Registro desactivado','$this->id_usr_sesion')";
    $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
    
    return $desactivar;
  }

  //Implementamos un método para activar categorías
  public function activar($idalmacen_general)  {
    $sql = "UPDATE almacen_general SET estado='1' WHERE idalmacen_general ='$idalmacen_general'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para eliminar
  public function eliminar($idalmacen_general)  {
    $sql = "UPDATE almacen_general SET estado_delete='0',user_delete= '$this->id_usr_sesion' WHERE idalmacen_general ='$idalmacen_general'";
    $eliminar =  ejecutarConsulta($sql);   if ( $eliminar['status'] == false) {return $eliminar; }  
    
    //add registro en nuestra bitacora
    $sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('almacen_general','$idalmacen_general','Registro Eliminado','$this->id_usr_sesion')";
    $bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
    
    return $eliminar;
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idalmacen_general) {   
    $sql = "SELECT idalmacen_general, nombre_almacen, descripcion
    FROM almacen_general WHERE idalmacen_general ='$idalmacen_general'; ";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para listar los registros
  public function tabla_principal(  ) {        
    $sql = "SELECT idalmacen_general, nombre_almacen, descripcion, estado, estado_delete 
    FROM almacen_general WHERE estado='1' AND estado_delete='1' ORDER BY nombre_almacen ASC;";
   return ejecutarConsulta($sql);    
  }

   //Implementar un método para listar los registros
   public function tabla_detalle( $id_proyecto, $id_almacen ) {        
    $sql = "SELECT apg.idalmacen_producto_guardado, apg.idalmacen_general, apg.idalmacen_resumen, apg.fecha_envio, apg.cantidad, prd.nombre as producto, 
    pry.nombre_codigo as proyecto
    FROM almacen_producto_guardado as apg, almacen_resumen as ar, producto as prd, proyecto as pry
    WHERE apg.idalmacen_resumen = ar.idalmacen_resumen AND ar.idproducto = prd.idproducto AND ar.idproyecto = pry.idproyecto 
    AND apg.estado = '1' AND apg.estado_delete = '1' AND ar.estado = '1' AND ar.estado_delete = '1'
    ORDER BY pry.nombre_codigo ASC;";
   return ejecutarConsulta($sql);    
  }

  //Seleccionar Trabajador Select2
  public function lista_de_categorias() {
    $sql = "SELECT idalmacen_general as idcategoria, nombre_almacen as nombre 
    FROM almacen_general WHERE estado='1' AND estado_delete='1' ; ";
    return ejecutarConsultaArray($sql);
  }

  //Seleccionar Trabajador Select2
  public function obtenerImg($idproducto) {
    $sql = "SELECT imagen FROM producto WHERE idproducto='$idproducto'";
    return ejecutarConsulta($sql);
  }

  //Seleccionar una ficha tecnica
  public function ficha_tec($idproducto)  {
    $sql = "SELECT ficha_tecnica FROM producto WHERE idproducto='$idproducto'";
    return ejecutarConsulta($sql);
  }

}

?>
