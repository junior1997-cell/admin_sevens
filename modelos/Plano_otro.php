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

    //Implementamos un método para eliminar carpeta
    public function eliminar_carpeta($idcarpeta)
    {
      $sql="UPDATE carpeta_plano_otro SET estado_delete='0' WHERE idcarpeta = '$idcarpeta'";

      return ejecutarConsulta($sql);
    }

    //Implementamos un método para eliminar plano
    public function eliminar_plano($idplano_otro)
    {
      $sql="UPDATE plano_otro SET estado_delete='0' WHERE idplano_otro='$idplano_otro'";

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


    //Implementar un método para listar los registros
    public function listar_carpeta($nube_proyecto)
    {
      $sql="SELECT * FROM carpeta_plano_otro AS cpo WHERE cpo.idproyecto = '$nube_proyecto' AND estado_delete='1' AND estado='1' ";

      return ejecutarConsulta($sql);		
    }

    //Implementar un método para listar los registros
    public function listar_plano($id_carpeta)
    {
      $sql="SELECT * FROM plano_otro AS po WHERE po.id_carpeta = '$id_carpeta'  AND estado_delete='1' AND estado='1'";

      return ejecutarConsulta($sql);		
    }

    // obtebnemos los DOCS para eliminar
    public function obtenerDoc($idplano_otro) {

      $sql = "SELECT doc FROM plano_otro WHERE idplano_otro='$idplano_otro'";

      return ejecutarConsulta($sql);
    }  

  }

?>
