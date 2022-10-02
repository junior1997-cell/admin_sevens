<?php
  //Incluímos inicialmente la conexión a la base de datos
  require "../config/Conexion_v2.php";

  class Calendario
  {
    //Implementamos nuestro constructor
    public function __construct()
    {
    }

    //Implementamos un método para insertar registros
    public function insertar($idproyecto, $titulo, $descripcion, $fecha_feriado, $background_color, $text_color) {
        
      $sql="INSERT INTO calendario_por_proyecto (idproyecto, titulo, descripcion, fecha_feriado, background_color, text_color)
      VALUES ( '$idproyecto', '$titulo', '$descripcion', '$fecha_feriado', '$background_color', '$text_color')";      
      $insert = ejecutarConsulta_retornarID($sql); if ( $insert['status'] == false) {return $insert; }

      //B I T A C O R A -------
      $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('calendario_por_proyecto', '".$insert['data']."', 'Crear Feriado.', '".$_SESSION['idusuario']."')";
      $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }
      
      return $insert;
    }

    //Implementamos un método para editar registros
    public function editar($idcalendario, $idproyecto, $titulo, $descripcion, $fecha_feriado, $background_color, $text_color) {
      $sql="UPDATE calendario_por_proyecto SET idproyecto = '$idproyecto', titulo = '$titulo', descripcion = '$descripcion',
       fecha_feriado = '$fecha_feriado', background_color = '$background_color', text_color = '$text_color'
      WHERE idcalendario_por_proyecto='$idcalendario'";	      
      $editar = ejecutarConsulta($sql); if ( $editar['status'] == false) {return $editar; }

      //B I T A C O R A -------
      $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('calendario_por_proyecto', '".$idcalendario."', 'Editar Feriado.', '".$_SESSION['idusuario']."')";
      $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }
      
      return $editar;
    }

    //Implementamos un método para desactivar ESTADO
    public function desactivar($idcalendario) {
      $sql="UPDATE calendario_por_proyecto SET estado='0' WHERE idcalendario_por_proyecto='$idcalendario'";
      $desactivar = ejecutarConsulta($sql); if ( $desactivar['status'] == false) {return $desactivar; }

      //B I T A C O R A -------
      $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('calendario_por_proyecto', '".$idcalendario."', 'Desactivar Feriado.', '".$_SESSION['idusuario']."')";
      $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }

      return $desactivar;
    }

    //Implementamos un método para activar ESTADO
    public function activar($idcalendario) {
      $sql="UPDATE calendario_por_proyecto SET estado='1' WHERE idcalendario_por_proyecto='$idcalendario'";
      $activar = ejecutarConsulta($sql); if ( $activar['status'] == false) {return $activar; }

      //B I T A C O R A -------
      $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('calendario_por_proyecto', '".$idcalendario."', 'Activar Feriado.', '".$_SESSION['idusuario']."')";
      $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }

      return $activar;
    } 

    //Implementamos un método para desactivar DOMINGO
    public function desactivar_domingo($idproyecto)  {
      $sql="UPDATE proyecto SET feriado_domingo='false' WHERE idproyecto='$idproyecto'";
      $desact_domingo = ejecutarConsulta($sql); if ( $desact_domingo['status'] == false) {return $desact_domingo; }

      //B I T A C O R A -------
      $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('proyecto', '".$idproyecto."', 'Desactivar feriado domingo.', '".$_SESSION['idusuario']."')";
      $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }

      return $desact_domingo;
    }

    //Implementamos un método para activar DOMINGO
    public function activar_domingo($idproyecto) {
      $sql="UPDATE proyecto SET feriado_domingo='true' WHERE idproyecto='$idproyecto'";
      $activar_domingo = ejecutarConsulta($sql); if ( $activar_domingo['status'] == false) {return $activar_domingo; }

      //B I T A C O R A -------
      $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('proyecto', '".$idproyecto."', 'Activar feriado domingo.', '".$_SESSION['idusuario']."')";
      $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }

      return $activar_domingo;
    }

    //Implementar un método para listar los registros
    public function listar( $idproyecto) {
      $sql="SELECT c.idcalendario AS id, c.titulo AS title, c.descripcion , c.fecha_feriado AS start, 
      c.background_color AS backgroundColor, c.background_color AS borderColor, c.text_color AS textColor, c.all_day AS allDay
      FROM calendario AS c  
      WHERE c.estado = 1;";

      $sql2 = "SELECT cp.idcalendario_por_proyecto AS id, cp.idproyecto, cp.titulo AS title, cp.descripcion , cp.fecha_feriado AS start, 
            cp.background_color AS backgroundColor, cp.background_color AS borderColor, cp.text_color AS textColor, cp.all_day AS allDay
      FROM calendario_por_proyecto AS cp
      WHERE cp.estado = 1 AND cp.idproyecto = '$idproyecto';";

      $sql3="SELECT plazo, fecha_inicio, fecha_fin FROM proyecto WHERE idproyecto = '$idproyecto';";
      
      $a = ejecutarConsultaArray($sql); $b = ejecutarConsultaArray($sql2); $c = ejecutarConsultaSimpleFila($sql3);

      $data =  [ 'status' => true, 'message' => 'todo oka ps', 'data' =>  ['data1' => $b['data'], 'data2' => $c['data']]] ;
      return $data;
    }    

    public function listar_e($idproyecto) {
      $sql="SELECT cp.idcalendario_por_proyecto AS id, cp.idproyecto, cp.titulo AS title, cp.descripcion , cp.fecha_feriado AS start, 
      cp.background_color AS backgroundColor, cp.background_color AS borderColor, cp.text_color AS textColor, cp.all_day AS allDay
      FROM calendario_por_proyecto AS cp
      WHERE cp.estado = 0 AND cp.idproyecto = '$idproyecto';";

      return ejecutarConsultaArray($sql);		
    }   
    
    //Implementamos un método para activar DOMINGO
    public function estado_domingo($idproyecto) {
      $sql="SELECT feriado_domingo FROM proyecto WHERE idproyecto='$idproyecto'";
      return ejecutarConsultaSimpleFila($sql);
    }

    //Implementamos un método para activar DOMINGO
    public function detalle_dias_proyecto($idproyecto) {
      $sql="SELECT fecha_feriado  FROM calendario  WHERE estado = 1;";
      $a = ejecutarConsultaArray($sql); if ($a['status'] == false) {  return $a; }

      $sql2="SELECT fecha_feriado  FROM calendario_por_proyecto WHERE idproyecto = '$idproyecto' AND estado = 1;";
      $b = ejecutarConsultaArray($sql2); if ($b['status'] == false) {  return $b; }
      
      $sql3="SELECT plazo, fecha_inicio, fecha_fin FROM proyecto WHERE idproyecto = '$idproyecto';";
      $data2 = ejecutarConsultaSimpleFila($sql3); if ($data2['status'] == false) {  return $a; }

      $results = [
        'status' => true, 'message' => 'todo oka ps', 
        'data' => [ "data1" => array_merge($a['data'],$b['data']), "data2" => $data2['data'] ]
      ];
      
      return $results;
    }

  }

?>
