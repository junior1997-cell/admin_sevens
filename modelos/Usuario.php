<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Usuario
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  //Implementamos un método para insertar registros
  public function insertar($trabajador, $cargo, $login, $clave, $permisos) {
    // insertamos al usuario
    $sql = "INSERT INTO usuario ( idtrabajador, cargo, login, password,user_created) VALUES ('$trabajador', '$cargo', '$login', '$clave','" . $_SESSION['idusuario'] . "')";
    $data_user = ejecutarConsulta_retornarID($sql);

    if ($data_user['status'] == false){return $data_user; }

    //add registro en nuestra bitacora
    $sql2 = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('usuario','" . $data_user['data'] . "','Registrar','" . $_SESSION['idusuario'] . "')";
    $bitacora1 = ejecutarConsulta($sql2);

    if ( $bitacora1['status'] == false) {return $bitacora1; }

    // marcamos al trabajador como usuario
    $sql3 = "UPDATE trabajador SET estado_usuario='1', user_updated= '" . $_SESSION['idusuario'] . "' WHERE idtrabajador='$trabajador';";
    $update_estado_traba= ejecutarConsulta($sql3);
    if ( $update_estado_traba['status'] == false) {return $update_estado_traba; }

    //add registro en nuestra bitacora
    $sql2 = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('trabajador','" . $trabajador . "','Editamso el estado_usuario para convertir en usuario','" . $_SESSION['idusuario'] . "')";
    $bitacora1 = ejecutarConsulta($sql2);

    if ( $bitacora1['status'] == false) {return $bitacora1; }

    $num_elementos = 0; $sw = "";

    if ( !empty($permisos) ) {

      while ($num_elementos < count($permisos)) {
        
        $idusuarionew = $data_user['data'];

        $sql_detalle = "INSERT INTO usuario_permiso(idusuario, idpermiso, user_created) VALUES('$idusuarionew', '$permisos[$num_elementos]','" . $_SESSION['idusuario'] . "')";

        $sw = ejecutarConsulta_retornarID($sql_detalle);  

        if ( $sw['status'] == false) {return $sw; }

        //add registro en nuestra bitacora
        $sql2 = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('usuario_permiso','" .  $sw['data'] . "','Registrar permisos','" . $_SESSION['idusuario'] . "')";
        $bitacora = ejecutarConsulta($sql2);

        if ( $bitacora['status'] == false) {return $bitacora; }

        $num_elementos++;

      }

      return $sw;

    }else{

      return $data_user;

    }

  }

  //Implementamos un método para editar registros
  public function editar($idusuario, $trabajador_old, $trabajador, $cargo, $login, $clave, $permisos) {
    $update_user = '[]';

    if (!empty($trabajador)) {

      $sql = "UPDATE usuario SET idtrabajador='$trabajador', cargo='$cargo', login='$login', password='$clave', user_updated= '" . $_SESSION['idusuario'] . "' WHERE idusuario='$idusuario'";
      $update_user = ejecutarConsulta_retornarID($sql);
      
      if ( $update_user['status']== false ) { return $update_user; }   

      //add registro en nuestra bitacora
      $sql1_1 = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('usuario','" . $update_user['data'] . "','Editar usuario','" . $_SESSION['idusuario'] . "')";
      $bitacora1 = ejecutarConsulta($sql1_1);
      if ( $bitacora1['status'] == false) {return $bitacora1; }  

      // desmarcamos al trabajador old como usuario
      $sql3 = "UPDATE trabajador SET estado_usuario='0', user_updated= '" . $_SESSION['idusuario'] . "' WHERE idtrabajador='$trabajador_old';";
      $old= ejecutarConsulta($sql3);
      if ( $old['status'] == false) {return $old; }

      //add registro en nuestra bitacora
      $sql3_1 = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('trabajador','" . $trabajador_old . "','Cambio de estado_usuario para dejar de ser usuario ','" . $_SESSION['idusuario'] . "')";
      $bitacora3_1 = ejecutarConsulta($sql3_1);
      if ( $bitacora3_1['status'] == false) {return $bitacora3_1; }  

      // marcamos al trabajador new como usuario
      $sql4 = "UPDATE trabajador SET estado_usuario='1', user_updated= '" . $_SESSION['idusuario'] . "' WHERE idtrabajador='$trabajador';";
      $new=ejecutarConsulta($sql4);
      if ( $new['status'] == false) {return $new; }  

      //add registro en nuestra bitacora
      $sql4_1 = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('trabajador','" . $trabajador . "','Cambio de estado_usuario new','" . $_SESSION['idusuario'] . "')";
      $bitacora4_1 = ejecutarConsulta($sql4_1);

      if ( $bitacora4_1['status'] == false) {return $bitacora4_1; }  

      
    } else {

      $sql = "UPDATE usuario SET 
      idtrabajador='$trabajador_old', cargo='$cargo', login='$login', password='$clave', user_updated= '" . $_SESSION['idusuario'] . "' WHERE idusuario='$idusuario'";
      $update_user = ejecutarConsulta($sql);

      if ($update_user['status'] == false) {return $update_user; }     
      
      //add registro en nuestra bitacora
      $sql5_1 = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('usuario', '$idusuario' ,'Editamos los campos del usuario','" . $_SESSION['idusuario'] . "')";
      $bitacora5_1 = ejecutarConsulta($sql5_1);

      if ( $bitacora5_1['status'] == false) {return $bitacora5_1; }  

    }

    $num_elementos = 0; $sw = "";

    if ($permisos != "") {      

      //Eliminamos todos los permisos asignados para volverlos a registrar
      $sqldel = "DELETE FROM usuario_permiso WHERE idusuario='$idusuario'";
      $delete_permiso = ejecutarConsulta($sqldel);

      if ( $delete_permiso['status'] == false ) { return $delete_permiso; } 

      //add registro en nuestra bitacora
      $sqld = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('usuario_permiso','','Borando definitivamente registros de permisos para volver a registrar','" . $_SESSION['idusuario'] . "')";
      $bitacorad = ejecutarConsulta($sqld);

      if ( $bitacorad['status'] == false) {return $bitacorad; }

      while ($num_elementos < count($permisos)) {

        $sql_detalle = "INSERT INTO usuario_permiso(idusuario, idpermiso,user_created) VALUES('$idusuario', '$permisos[$num_elementos]','" . $_SESSION['idusuario'] . "')";

        $sw = ejecutarConsulta_retornarID($sql_detalle);  

        if ( $sw['status'] == false) {return $sw; }

        //add registro en nuestra bitacora
        $sqlsw = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('usuario_permiso','" .  $sw['data'] . "','Asigamos nuevos persmisos cuando editamos usuario','" . $_SESSION['idusuario'] . "')";
        $bitacorasw = ejecutarConsulta($sqlsw);

        if ( $bitacorasw['status'] == false) {return $bitacorasw; }

        $num_elementos = $num_elementos + 1;

      }

      return $sw;
    
    }else{

      //Eliminamos todos los permisos asignados para volverlos a registrar
      $sqldel = "DELETE FROM usuario_permiso WHERE idusuario='$idusuario'";
      $delete =  ejecutarConsulta($sqldel); 

      if ( $delete['status'] == false) {return $delete; }    

      //add registro en nuestra bitacora
      $sqlde = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('usuario_permiso','','Borando definitivamente registros de permisos para volver a registrar','" . $_SESSION['idusuario'] . "')";
      $bitacorade = ejecutarConsulta($sqlde);

      if ( $bitacorade['status'] == false) {return $bitacorade; }

      return $delete;
    }

  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idusuario) {
    $sql = "UPDATE usuario SET estado='0', user_trash= '" . $_SESSION['idusuario'] . "' WHERE idusuario='$idusuario'";

    $desactivar = ejecutarConsulta($sql);
    
    if ( $desactivar['status'] == false) {return $desactivar; }    

    //add registro en nuestra bitacora
    $sqlde = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('usuario_permiso','$idusuario','Registro desactivado','" . $_SESSION['idusuario'] . "')";
    $bitacorade = ejecutarConsulta($sqlde);

    if ( $bitacorade['status'] == false) {return $bitacorade; }   

    return $desactivar;
  }

  //Implementamos un método para activar :: !!sin usar ::
  public function activar($idusuario) {
    $sql = "UPDATE usuario SET estado='1', user_updated= '" . $_SESSION['idusuario'] . "' WHERE idusuario='$idusuario'";

    $activar= ejecutarConsulta($sql);
        
    if ( $activar['status'] == false) {return $activar; }    

    //add registro en nuestra bitacora
    $sqlde = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('usuario_permiso','$idusuario','Registro activado','" . $_SESSION['idusuario'] . "')";
    $bitacorade = ejecutarConsulta($sqlde);

    if ( $bitacorade['status'] == false) {return $bitacorade; }   

    return $activar;
  }

  //Implementamos un método para eliminar usuario
  public function eliminar($idusuario) {
    $sql = "UPDATE usuario SET estado_delete='0',user_delete= '" . $_SESSION['idusuario'] . "' WHERE idusuario='$idusuario'";

    $eliminar= ejecutarConsulta($sql);
        
    if ( $eliminar['status'] == false) {return $eliminar; }    

    //add registro en nuestra bitacora
    $sqlde = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('usuario_permiso','$idusuario','Registro Eliminado','" . $_SESSION['idusuario'] . "')";
    $bitacorade = ejecutarConsulta($sqlde);

    if ( $bitacorade['status'] == false) {return $bitacorade; }   

    return $eliminar;

  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idusuario) {
    $sql = "SELECT u.idusuario, u.idtrabajador, u.cargo, u.login, u.password, u.estado, t.nombres FROM usuario AS u, trabajador AS t WHERE u.idusuario='$idusuario' AND u.idtrabajador = t.idtrabajador;";

    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para listar los registros
  public function listar() {
    $sql = "SELECT u.idusuario, t.nombres, t.tipo_documento, t.numero_documento, t.telefono, t.email, u.cargo, u.login, t.imagen_perfil, t.tipo_documento, u.estado
		FROM usuario as u, trabajador as t
		WHERE  u.idtrabajador = t.idtrabajador  AND u.estado=1 AND u.estado_delete=1 ORDER BY t.nombres ASC;";
    return ejecutarConsulta($sql);
  }
  //Implementar un método para listar los permisos marcados
  public function listarmarcados($idusuario) {
    $sql = "SELECT * FROM usuario_permiso WHERE idusuario='$idusuario' ";
    return ejecutarConsulta($sql);
  }

  //Función para verificar el acceso al sistema
  public function verificar($login, $clave) {
    $sql = "SELECT u.idusuario, t.nombres, t.tipo_documento, t.numero_documento, t.telefono, t.email, u.cargo, u.login, t.imagen_perfil, t.tipo_documento
		FROM usuario as u, trabajador as t
		WHERE u.login='$login' AND u.password='$clave' AND t.estado=1 and u.estado=1 and u.estado_delete=1 and u.idtrabajador = t.idtrabajador;";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Seleccionar Trabajador Select2
  public function select2_trabajador() {
    $sql = "SELECT idtrabajador as id, nombres as nombre, tipo_documento as documento, numero_documento, imagen_perfil
		FROM trabajador WHERE estado='1' AND estado_delete='1' AND estado_usuario = '0' ORDER BY nombres ASC;";
    return ejecutarConsulta($sql);
  }
  
}

?>
