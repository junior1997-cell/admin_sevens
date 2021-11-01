<?php
  //Incluímos inicialmente la conexión a la base de datos
  require "../config/Conexion.php";

  class Calendario
  {
    //Implementamos nuestro constructor
    public function __construct()
    {
    }

    //Implementamos un método para insertar registros
    public function insertar( $titulo, $descripcion, $fecha_feriado, $background_color, $text_color)
    {
        
      $sql="INSERT INTO calendario ( titulo, descripcion, fecha_feriado, background_color, text_color)
      VALUES ( '$titulo', '$descripcion', '$fecha_feriado', '$background_color', '$text_color')";
      
      return ejecutarConsulta($sql);
        
    }

      //Implementamos un método para editar registros
    public function editar($idcalendario, $titulo, $descripcion, $fecha_feriado, $background_color, $text_color)
    {
      $sql="UPDATE calendario SET idcalendario = '$idcalendario', titulo = '$titulo', descripcion = '$descripcion',
       fecha_feriado = '$fecha_feriado', background_color = '$background_color', text_color = '$text_color'
      WHERE idcalendario='$idcalendario'";	
      
      return ejecutarConsulta($sql);
      
    }

    //Implementamos un método para desactivar categorías
    public function desactivar($idcalendario)
    {
      $sql="UPDATE calendario SET estado='0' WHERE idcalendario='$idcalendario'";

      return ejecutarConsulta($sql);
    }

    //Implementamos un método para activar categorías
    public function activar($idcalendario)
    {
      $sql="UPDATE calendario SET estado='1' WHERE idcalendario='$idcalendario'";

      return ejecutarConsulta($sql);
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idcalendario)
    {
      $sql="SELECT * FROM calendario WHERE idcalendario='$idcalendario'";

      return ejecutarConsultaSimpleFila($sql);
    }

    

    //Implementar un método para listar los registros
    public function listar()
    {
      $sql="SELECT c.idcalendario AS id, c.titulo AS title, c.descripcion , c.fecha_feriado AS start, 
      c.background_color AS backgroundColor, c.background_color AS borderColor, c.text_color AS textColor, c.all_day AS allDay
      FROM calendario AS c  
      WHERE c.estado = 1;";

      return ejecutarConsultaArray($sql);		
    }    

    public function listar_e()
    {
      $sql="SELECT c.idcalendario AS id, c.titulo AS title, c.descripcion , c.fecha_feriado AS start, 
      c.background_color AS backgroundColor, c.background_color AS borderColor, c.text_color AS textColor, c.all_day AS allDay
      FROM calendario AS c  
      WHERE c.estado = 0;";

      return ejecutarConsultaArray($sql);		
    }    

  }

?>
