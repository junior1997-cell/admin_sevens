<?php
  //Incluímos inicialmente la conexión a la base de datos
  require "../config/Conexion.php";

  class AllTrabajador
  {
    //Implementamos nuestro constructor
    public function __construct()
    {
    }

    //Implementamos un método para insertar registros
    public function insertar( $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $nacimiento, $edad, $c_bancaria, $c_bancaria_format, $email, $banco, $titular_cuenta, $imagen1, $imagen2, $imagen3, $cci, $cci_format, $tipo, $ocupacion, $ruc, $cv_documentado, $cv_nodocumentado)
    {
      //var_dump($nombre, $tipo_documento, $num_documento, $direccion, $telefono, $nacimiento, $edad, $c_bancaria, $email, $banco, $titular_cuenta, $imagen1, $imagen2, $imagen3, $cci, $tipo, $ocupacion, $ruc, $cv_documentado, $cv_nodocumentado);
      $sql="INSERT INTO trabajador ( idbancos, nombres, tipo_documento, numero_documento, fecha_nacimiento, edad, cuenta_bancaria, cuenta_bancaria_format, titular_cuenta, direccion, telefono, email, imagen_perfil, imagen_dni_anverso, imagen_dni_reverso, cci, cci_format, idtipo_trabajador , idocupacion, ruc, cv_documentado, cv_no_documentado)
      VALUES ( '$banco', '$nombre', '$tipo_documento', '$num_documento', '$nacimiento', '$edad', '$c_bancaria', '$c_bancaria_format', '$titular_cuenta', '$direccion', '$telefono', '$email', '$imagen1', '$imagen2', '$imagen3', '$cci', '$cci_format', '$tipo', '$ocupacion', '$ruc', '$cv_documentado', '$cv_nodocumentado')";
      
      return ejecutarConsulta($sql);
        
    }

      //Implementamos un método para editar registros $cci, $tipo, $ocupacion, $ruc, $cv_documentado, $cv_nodocumentado
    public function editar($idtrabajador, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $nacimiento, $edad, $c_bancaria, $c_bancaria_format, $email, $banco, $titular_cuenta, $imagen1, $imagen2, $imagen3, $cci, $cci_format, $tipo, $ocupacion, $ruc, $cv_documentado, $cv_nodocumentado)
    {
      $sql="UPDATE trabajador SET idbancos='$banco', nombres='$nombre', tipo_documento='$tipo_documento', numero_documento='$num_documento', 
      fecha_nacimiento='$nacimiento', edad='$edad', cuenta_bancaria='$c_bancaria', cuenta_bancaria_format='$c_bancaria_format', titular_cuenta='$titular_cuenta',direccion='$direccion', 
      telefono='$telefono', email='$email', imagen_perfil ='$imagen1', imagen_dni_anverso ='$imagen2', imagen_dni_reverso ='$imagen3',
      cci='$cci', cci_format = '$cci_format', idtipo_trabajador ='$tipo', idocupacion='$ocupacion', ruc='$ruc', cv_documentado='$cv_documentado', cv_no_documentado='$cv_nodocumentado'
      WHERE idtrabajador='$idtrabajador'";	
      
      return ejecutarConsulta($sql);
      
    }

    //Implementamos un método para desactivar categorías
    public function desactivar($idtrabajador, $descripcion)
    {
      $sql="UPDATE trabajador SET estado='0', descripcion_expulsion = '$descripcion' WHERE idtrabajador='$idtrabajador'";

      return ejecutarConsulta($sql);
    }
      //Implementamos un método para desactivar categorías
    public function desactivar_1($idtrabajador)
    {
      $sql="UPDATE trabajador SET estado='0' WHERE idtrabajador='$idtrabajador'";

      return ejecutarConsulta($sql);
    }

    //Implementamos un método para activar categorías
    public function activar($idtrabajador)
    {
      $sql="UPDATE trabajador SET estado='1' WHERE idtrabajador='$idtrabajador'";

      return ejecutarConsulta($sql);
    }

    //Implementamos un método para activar categorías
    public function eliminar($idtrabajador)
    {
      $sql="UPDATE trabajador SET estado_delete='0' WHERE idtrabajador='$idtrabajador'";

      return ejecutarConsulta($sql);
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idtrabajador)
    {
      $sql="SELECT * FROM trabajador WHERE idtrabajador='$idtrabajador'";

      return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function verdatos($idtrabajador)
    {
      $sql="SELECT 
      t.idbancos as idbancos, t.nombres as nombres, t.tipo_documento as tipo_documento, t.numero_documento as numero_documento,
      t.fecha_nacimiento as fecha_nacimiento, t.cuenta_bancaria_format , t.cci_format,
      t.titular_cuenta as titular_cuenta, t.direccion as direccion, t.telefono as telefono, t.email as email,
      t.imagen_perfil as imagen_perfil, t.imagen_dni_anverso as imagen_dni_anverso, t.cv_documentado, t.cv_no_documentado,
      t.imagen_dni_reverso as imagen_dni_reverso, b.nombre as banco 
      FROM trabajador t, bancos b WHERE t.idtrabajador='$idtrabajador' AND t.idbancos =b.idbancos";

      return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para listar los registros
    public function listar()
    {
      $sql="SELECT t.idtrabajador,  t.nombres, t.tipo_documento, t.numero_documento, t.fecha_nacimiento, t.edad, t.cuenta_bancaria_format, 
      t.cci_format, t.telefono, t.imagen_perfil,  t.estado, b.nombre AS banco, tt.nombre AS nombre_tipo, o.nombre_ocupacion AS nombre_ocupacion 
      FROM trabajador AS t, bancos AS b,  tipo_trabajador as tt, ocupacion as o
      WHERE t.idbancos = b.idbancos AND  t.idocupacion =o.idocupacion  AND tt.idtipo_trabajador= t.idtipo_trabajador AND  t.estado = 1 AND t.estado_delete = 1 ORDER BY  t.nombres ASC ;";

      return ejecutarConsulta($sql);		
    }

    //Implementar un método para listar los registros
    public function listar_expulsado()
    {
      $sql="SELECT t.idtrabajador,  t.nombres, t.tipo_documento, t.numero_documento, t.fecha_nacimiento, t.edad, t.cuenta_bancaria_format, 
      t.cci_format, t.telefono, t.imagen_perfil, t.descripcion_expulsion, t.estado, b.nombre AS banco, tt.nombre AS nombre_tipo, o.nombre_ocupacion AS nombre_ocupacion 
      FROM trabajador AS t, bancos AS b,  tipo_trabajador as tt, ocupacion as o
      WHERE t.idbancos = b.idbancos AND  t.idocupacion =o.idocupacion  AND tt.idtipo_trabajador= t.idtipo_trabajador AND  t.estado = 0;";

      return ejecutarConsulta($sql);		
    }

    // obtebnemos los DOCS para eliminar
    public function obtenerImg($idtrabajador) {

      $sql = "SELECT imagen_perfil, imagen_dni_anverso, imagen_dni_reverso FROM trabajador WHERE idtrabajador='$idtrabajador'";

      return ejecutarConsulta($sql);
    }
    // obtebnemos los DOCS para eliminar
    public function obtenercv($idtrabajador) {

      $sql = "SELECT cv_documentado, cv_no_documentado FROM trabajador WHERE idtrabajador='$idtrabajador'";

      return ejecutarConsulta($sql);
    }

    public function select2_banco() {
      $sql="SELECT idbancos as id, nombre, alias FROM bancos WHERE estado='1' AND idbancos > 1 ORDER BY nombre ASC;";
      return ejecutarConsulta($sql);		
    }

    public function formato_banco($idbanco){
      $sql="SELECT nombre, formato_cta, formato_cci, formato_detracciones FROM bancos WHERE estado='1' AND idbancos = '$idbanco';";
      return ejecutarConsultaSimpleFila($sql);		
    }

  }

?>
