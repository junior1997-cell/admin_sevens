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
    public function insertar( $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $nacimiento, $edad, $c_bancaria, $email, $banco, $titular_cuenta, $imagen1, $imagen2, $imagen3)
    {
      $sql="INSERT INTO trabajador ( idbancos, nombres, tipo_documento, numero_documento, fecha_nacimiento, edad, cuenta_bancaria, titular_cuenta, direccion, telefono, email, imagen_perfil, imagen_dni_anverso, imagen_dni_reverso)
      VALUES ( '$banco', '$nombre', '$tipo_documento', '$num_documento', '$nacimiento', '$edad', '$c_bancaria', '$titular_cuenta', '$direccion', '$telefono', '$email', '$imagen1', '$imagen2', '$imagen3')";
      
      return ejecutarConsulta($sql);
        
    }

      //Implementamos un método para editar registros
    public function editar($idtrabajador, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $nacimiento, $edad, $c_bancaria, $email, $banco, $titular_cuenta, $imagen1, $imagen2, $imagen3)
    {
      $sql="UPDATE trabajador SET idbancos='$banco', nombres='$nombre', tipo_documento='$tipo_documento', numero_documento='$num_documento', 
      fecha_nacimiento='$nacimiento', edad='$edad', cuenta_bancaria='$c_bancaria', titular_cuenta='$titular_cuenta',direccion='$direccion', 
      telefono='$telefono', email='$email', imagen_perfil ='$imagen1', imagen_dni_anverso ='$imagen2', imagen_dni_reverso ='$imagen3'
      WHERE idtrabajador='$idtrabajador'";	
      
      return ejecutarConsulta($sql);
      
    }

    //Implementamos un método para desactivar categorías
    public function desactivar($idtrabajador)
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
      t.idbancos as idbancos, 
      t.nombres as nombres, 
      t.tipo_documento as tipo_documento, 
      t.numero_documento as numero_documento,
      t.fecha_nacimiento as fecha_nacimiento,
      t.cuenta_bancaria as cuenta_bancaria,
      t.titular_cuenta as titular_cuenta,
      t.direccion as direccion,
      t.telefono as telefono,
      t.email as email,
      t.imagen_perfil as imagen_perfil , 
      t.imagen_dni_anverso as imagen_dni_anverso,
      t.imagen_dni_reverso as imagen_dni_reverso,
      b.nombre as banco 
      FROM trabajador t, bancos b 
      WHERE t.idtrabajador='$idtrabajador' AND t.idbancos =b.idbancos";

      return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para listar los registros
    public function listar()
    {
      $sql="SELECT t.idtrabajador, t.nombres, t.tipo_documento, t.numero_documento, t.fecha_nacimiento, t.edad, t.cuenta_bancaria, t.telefono, t.imagen_perfil, t.estado, b.nombre AS banco 
      FROM trabajador AS t, bancos AS b
      WHERE t.idbancos = b.idbancos; ";

      return ejecutarConsulta($sql);		
    }

    // obtebnemos los DOCS para eliminar
    public function obtenerImg($idtrabajador) {

      $sql = "SELECT imagen_perfil, imagen_dni_anverso, imagen_dni_reverso FROM trabajador WHERE idtrabajador='$idtrabajador'";

      return ejecutarConsulta($sql);
    }

    public function select2_banco()
    {
      $sql="SELECT idbancos as id, nombres as nombre FROM bancos WHERE estado='1';";
      return ejecutarConsulta($sql);		
    }

  }

?>