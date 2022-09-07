<?php
  //Incluímos inicialmente la conexión a la base de datos
  require "../config/Conexion_v2.php";

  class AllCalendario
  {
    //Implementamos nuestro constructor
    public function __construct()
    {
    }

    //Implementamos un método para insertar registros
    public function insertar( $titulo, $descripcion, $fecha_feriado, $fecha_invertida, $background_color, $text_color) {
        
      $sql="INSERT INTO calendario ( titulo, descripcion, fecha_feriado, fecha_invertida, background_color, text_color, user_created)
      VALUES ( '$titulo', '$descripcion', '$fecha_feriado', '$fecha_invertida', '$background_color', '$text_color','" . $_SESSION['idusuario'] . "')";

      $intertar =  ejecutarConsulta_retornarID($sql); if ($intertar['status'] == false) {  return $intertar; } 

      //add registro en nuestra bitacora
      $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('calendario','".$intertar['data']."','Nuevo registro calendario','" . $_SESSION['idusuario'] . "')";
      $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   

      $sql2 = "SELECT idproyecto FROM calendario_por_proyecto GROUP BY idproyecto;";
      $proyecto = ejecutarConsultaArray($sql2);
      
      if ($proyecto['status']== false){return $proyecto; }

      $sw = "";
      foreach ($proyecto['data'] as $indice => $key) {
        $idproyecto = $key['idproyecto'];
        $sql3="INSERT INTO calendario_por_proyecto (idproyecto, titulo, descripcion, fecha_feriado, background_color, text_color, user_created)
        VALUES ('$idproyecto', '$titulo', '$descripcion', '$fecha_feriado', '$background_color', '$text_color','" . $_SESSION['idusuario'] . "')";

        $sw =  ejecutarConsulta_retornarID($sql3); if ($sw['status'] == false) {  return $sw; } 

        //add registro en nuestra bitacora
        $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('calendario_por_proyecto','".$sw['data']."','Nuevo registro en la secc de allcalendario para calendario por proyecto','" . $_SESSION['idusuario'] . "')";
        $bitacora_c = ejecutarConsulta($sql_bit); if ( $bitacora_c['status'] == false) {return $bitacora_c; }             
      }

      return $sw;

    }

    //Implementamos un método para editar registros
    public function editar($idcalendario, $titulo, $descripcion, $fecha_feriado, $fecha_invertida, $background_color, $text_color)
    {
      $sql="UPDATE calendario SET titulo = '$titulo', descripcion = '$descripcion', fecha_feriado = '$fecha_feriado', 
      fecha_invertida= '$fecha_invertida', background_color = '$background_color', text_color = '$text_color',user_updated= '" . $_SESSION['idusuario'] . "'
      WHERE idcalendario='$idcalendario'";	     

      $editar =  ejecutarConsulta($sql);
      if ( $editar['status'] == false) {return $editar; } 
  
      //add registro en nuestra bitacora
      $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('calendario','$idcalendario','Calendario editado','" . $_SESSION['idusuario'] . "')";
      $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }  
  
      return $editar;
      
    }

    //Implementamos un método para desactivar categorías
    public function desactivar($idcalendario)
    {
      $sql="UPDATE calendario SET estado='0',user_trash= '" . $_SESSION['idusuario'] . "' WHERE idcalendario='$idcalendario'";

      $desactivar= ejecutarConsulta($sql);

      if ($desactivar['status'] == false) {  return $desactivar; }
      
      //add registro en nuestra bitacora
      $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('calendario','".$idcalendario."','fecha calendario desactivado','" . $_SESSION['idusuario'] . "')";
      $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
      
      return $desactivar;
    }

    //Implementamos un método para activar categorías
    public function activar($idcalendario)
    {
      $sql="UPDATE calendario SET estado='1',user_updated= '" . $_SESSION['idusuario'] . "' WHERE idcalendario='$idcalendario'";
      $activar= ejecutarConsulta($sql);

      if ($activar['status'] == false) {  return $activar; }
      
      //add registro en nuestra bitacora
      $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('calendario','".$idcalendario."','fecha calendario recuperada','" . $_SESSION['idusuario'] . "')";
      $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
      
      return $activar;
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

      $count_n = ejecutarConsultaSimpleFila($sql2); 
      $count_la = ejecutarConsultaSimpleFila($sql3); 
      $count_lo = ejecutarConsultaSimpleFila($sql4);
      	
      $results = array(
        "fechas" =>$fechas['data'],
        "count_n" =>$count_n['data']['count_n'],
        "count_la" =>$count_la['data']['count_la'],
        "count_lo" =>$count_lo['data']['count_lo'],
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
