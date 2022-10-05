<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class ValorizacionFierro
{
  
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  //Implementamos un método para insertar registros
	public function insertar($idproyecto, $nombre_doc, $numero_valorizacion, $fecha_inicial, $fecha_final, $doc_fierro)	{		
    $sql="INSERT INTO fierro_por_valorizacion( idproyecto, nombre_doc, numero_valorizacion, fecha_inicial, fecha_final, documento) 
    VALUES ('$idproyecto','$nombre_doc','$numero_valorizacion','$fecha_inicial','$fecha_final','$doc_fierro')";
    $crear = ejecutarConsulta_retornarID($sql); if ( $crear['status'] == false) {return $crear; }	

    //B I T A C O R A -------
    $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('fierro_por_valorizacion', '".$crear['data']."', 'Crear doc fierro', '".$_SESSION['idusuario']."')";
    $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }

    return $crear;
	}

  //Implementamos un método para editar registros
	public function editar($idproyecto, $idfierro_por_valorizacion, $nombre_doc, $numero_valorizacion, $fecha_inicial, $fecha_final, $doc_fierro)
	{
		$sql="UPDATE fierro_por_valorizacion SET
    idproyecto='$idproyecto',
    nombre_doc='$nombre_doc',
    numero_valorizacion='$numero_valorizacion',
    fecha_inicial='$fecha_inicial',
    fecha_final='$fecha_final',
    documento='$doc_fierro' 
		WHERE idfierro_por_valorizacion='$idfierro_por_valorizacion'";	
		$editar = ejecutarConsulta($sql);	 if ( $editar['status'] == false) {return $editar; }	

    //B I T A C O R A -------
    $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('fierro_por_valorizacion', '".$idfierro_por_valorizacion."', 'Crear doc fierro', '".$_SESSION['idusuario']."')";
    $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }

    return $editar;
	}
  
  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idasistencia_trabajador) {
    $sql = "SELECT tp.idtrabajador_por_proyecto, t.nombres , t.tipo_documento as documento, t.numero_documento, tp.cargo, t.imagen_perfil, 
		atr.fecha_asistencia, atr.horas_normal_dia, atr.horas_extras_dia 
		FROM trabajador AS t, trabajador_por_proyecto AS tp, asistencia_trabajador AS atr 
		WHERE t.idtrabajador = tp.idtrabajador AND tp.idtrabajador_por_proyecto = atr.idtrabajador_por_proyecto AND atr.idasistencia_trabajador = '$idasistencia_trabajador';";
    return ejecutarConsultaSimpleFila($sql);
  }

  // Data para listar lo bototnes por quincena
  public function listarquincenas($nube_idproyecto) {
    $sql = "SELECT p.idproyecto, p.fecha_inicio, p.fecha_fin, p.plazo, p.fecha_pago_obrero, p.fecha_valorizacion 
		FROM proyecto as p 
		WHERE p.idproyecto = '$nube_idproyecto' AND p.fecha_inicio != p.fecha_fin";

    return ejecutarConsultaSimpleFila($sql);
  }

  //ver detalle quincena (cuando presiono el boton de cada quincena)
  public function mostrar_docs_quincena($nube_idproyect, $f1, $f2,  $numero_q_s) {
    $sql = "SELECT idfierro_por_valorizacion, idproyecto, nombre_doc, numero_valorizacion, fecha_inicial, fecha_final, documento 
    FROM fierro_por_valorizacion 
    WHERE  idproyecto = '$nube_idproyect' AND numero_valorizacion ='$numero_q_s' ;";
    return ejecutarConsultaSimpleFila($sql);    
  }

  public function todos_los_docs($nube_idproyect) {
    $sql = "SELECT idfierro_por_valorizacion, idproyecto, nombre_doc, numero_valorizacion, fecha_inicial, fecha_final, documento 
    FROM fierro_por_valorizacion 
    WHERE  idproyecto = '$nube_idproyect';";
    return ejecutarConsultaArray($sql);    
  }


  //---------------------------------------------------
  // obtebnemos los DOCS para eliminar
  public function optener_doc_para_eliminar($idfierro_por_valorizacion) {
    $sql = "SELECT documento FROM fierro_por_valorizacion WHERE idfierro_por_valorizacion='$idfierro_por_valorizacion'";
    return ejecutarConsultaSimpleFila($sql);
  }


}
 
?>
