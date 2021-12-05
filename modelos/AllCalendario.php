<?php
  //Incluímos inicialmente la conexión a la base de datos
  require "../config/Conexion.php";

  class AllCalendario
  {
    //Implementamos nuestro constructor
    public function __construct()
    {
    }

    //Implementamos un método para insertar registros
    public function insertar( $titulo, $descripcion, $fecha_feriado, $fecha_invertida, $background_color, $text_color)
    {
        
      $sql="INSERT INTO calendario ( titulo, descripcion, fecha_feriado, fecha_invertida, background_color, text_color)
      VALUES ( '$titulo', '$descripcion', '$fecha_feriado', '$fecha_invertida', '$background_color', '$text_color')";
      $all_calendario = ejecutarConsulta($sql);
      
      $sql2 = "SELECT idproyecto FROM calendario_por_proyecto GROUP BY idproyecto;";
      $proyecto = ejecutarConsultaArray($sql2);
      
      foreach ($proyecto as $indice => $key) {
        $idproyecto = $key['idproyecto'];
        $sql3="INSERT INTO calendario_por_proyecto (idproyecto, titulo, descripcion, fecha_feriado, background_color, text_color)
        VALUES ('$idproyecto', '$titulo', '$descripcion', '$fecha_feriado', '$background_color', '$text_color')";
        ejecutarConsulta($sql3);
      }

      return $all_calendario;
        
    }

      //Implementamos un método para editar registros
    public function editar($idcalendario, $titulo, $descripcion, $fecha_feriado, $fecha_invertida, $background_color, $text_color)
    {
      $sql="UPDATE calendario SET titulo = '$titulo', descripcion = '$descripcion', fecha_feriado = '$fecha_feriado', 
      fecha_invertida= '$fecha_invertida', background_color = '$background_color', text_color = '$text_color'
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

    //Implementar un método para listar los registros
    public function listar()
    {

      $sql="SELECT c.idcalendario AS id, c.titulo AS title, c.descripcion , c.fecha_feriado AS start, c.fecha_invertida,
      c.background_color AS backgroundColor, c.background_color AS borderColor, c.text_color AS textColor, c.all_day AS allDay
      FROM calendario AS c  
      WHERE c.estado = 1;";

      $sql2 = "SELECT COUNT(idcalendario)  as count_n FROM calendario WHERE background_color = '#FF0000';";
      $sql3 = "SELECT COUNT(idcalendario) as count_la FROM calendario WHERE background_color = '#FFF700';";
      $sql4 = "SELECT COUNT(idcalendario)as count_lo  FROM calendario WHERE background_color = '#28A745';";

      $fechas = ejecutarConsultaArray($sql);	

      $count_n = ejecutarConsultaSimpleFila($sql2); $count_la = ejecutarConsultaSimpleFila($sql3); $count_lo = ejecutarConsultaSimpleFila($sql4);
      	
      $results = array(
        "fechas" =>$fechas,
        "count_n" =>$count_n['count_n'],
        "count_la" =>$count_la['count_la'],
        "count_lo" =>$count_lo['count_lo'],
      );
      return $results;
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
