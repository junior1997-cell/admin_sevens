<?php
  //Incluímos inicialmente la conexión a la base de datos
  require "../config/Conexion.php";

  class PlanoOtro
  {
    //Implementamos nuestro constructor
    public function __construct()
    {
    }

    //Implementamos un método para insertar registros
    public function insertar_carpeta($idproyecto, $nombre, $descripcion){    
       
      $sql="INSERT INTO carpeta_plano_otro ( idproyecto, nombre, descripcion) VALUES ( '$idproyecto', '$nombre', '$descripcion')";
      
      return ejecutarConsulta($sql);
        
    }

      //Implementamos un método para editar registros
    public function editar_carpeta($idcarpeta , $idproyecto, $nombre, $descripcion)
    {
      $sql="UPDATE carpeta_plano_otro SET idproyecto = '$idproyecto', nombre = '$nombre', descripcion = '$descripcion'
      WHERE idcarpeta = '$idcarpeta '";	
      
      return ejecutarConsulta($sql);
      
    }

    //Implementamos un método para insertar registros
    public function insertar_plano($id_carpeta, $nombre, $descripcion, $imagen1){    
       
      $sql="INSERT INTO plano_otro ( id_carpeta, nombre, descripcion, doc) VALUES ( '$id_carpeta', '$nombre', '$descripcion', '$imagen1')";
      
      return ejecutarConsulta($sql);
        
    }

      //Implementamos un método para editar registros
    public function editar_plano($idplano_otro, $id_carpeta, $nombre, $descripcion, $imagen1)
    {
      $sql="UPDATE plano_otro SET id_carpeta = '$id_carpeta', nombre = '$nombre', descripcion = '$descripcion', doc = '$imagen1'
      WHERE idplano_otro='$idplano_otro'";	
      
      return ejecutarConsulta($sql);
      
    }

    //Implementamos un método para desactivar categorías
    public function desactivar_carpeta($idcarpeta)
    {
      $sql="UPDATE carpeta_plano_otro SET estado='0' WHERE idcarpeta = '$idcarpeta'";

      return ejecutarConsulta($sql);
    }

    //Implementamos un método para activar categorías
    public function activar_carpeta($idcarpeta)
    {
      $sql="UPDATE carpeta_plano_otro SET estado='1' WHERE idcarpeta = '$idcarpeta'";

      return ejecutarConsulta($sql);
    }

    //Implementamos un método para desactivar categorías
    public function desactivar_plano($idplano_otro)
    {
      $sql="UPDATE plano_otro SET estado='0' WHERE idplano_otro='$idplano_otro'";

      return ejecutarConsulta($sql);
    }

    //Implementamos un método para activar categorías
    public function activar_plano($idplano_otro)
    {
      $sql="UPDATE plano_otro SET estado='1' WHERE idplano_otro='$idplano_otro'";

      return ejecutarConsulta($sql);
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar_carpeta($idcarpeta)
    {
      $sql="SELECT * FROM carpeta_plano_otro WHERE idcarpeta='$idcarpeta'";

      return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar_plano($idplano_otro)
    {
      $sql="SELECT * FROM plano_otro WHERE idplano_otro='$idplano_otro'";

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
    public function listar_carpeta($nube_proyecto)
    {
      $sql="SELECT * FROM carpeta_plano_otro AS cpo WHERE cpo.idproyecto = '$nube_proyecto'";

      return ejecutarConsulta($sql);		
    }

    //Implementar un método para listar los registros
    public function listar_plano($id_carpeta)
    {
      $sql="SELECT * FROM plano_otro AS po WHERE po.id_carpeta = '$id_carpeta'";

      return ejecutarConsulta($sql);		
    }

    // obtebnemos los DOCS para eliminar
    public function obtenerDoc($idplano_otro) {

      $sql = "SELECT doc FROM plano_otro WHERE idplano_otro='$idplano_otro'";

      return ejecutarConsulta($sql);
    }  

  }

?>
