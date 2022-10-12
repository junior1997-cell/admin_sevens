<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Valorizacionconcreto
{
  
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  //Implementamos un método para insertar registros
	public function insertar($idproyecto,$nombre_val_concreto, $fecha_inicio, $fecha_fin, $numero_q_s, $doc_fierro)	{		
    $sql="INSERT INTO concreto_por_valorizacion(idproyecto, nombre_doc, numero_valorizacion, fecha_inicial, fecha_final, documento) 
    VALUES ('$idproyecto','$nombre_val_concreto','$numero_q_s','$fecha_inicio','$fecha_fin','$doc_fierro')";
    $crear = ejecutarConsulta_retornarID($sql); if ( $crear['status'] == false) {return $crear; }	

    //B I T A C O R A -------
    $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('concreto_por_valorizacion', '".$crear['data']."', 'Crear doc fierro', '".$_SESSION['idusuario']."')";
    $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }

    return $crear;
	}

  //Implementamos un método para editar registros
	public function editar($idvalorizacion,$idproyecto,$nombre_val_concreto, $fecha_inicio, $fecha_fin, $numero_q_s, $doc_fierro)
	{
		$sql="UPDATE concreto_por_valorizacion SET 
    idproyecto='$idproyecto',
    nombre_doc='$nombre_val_concreto',
    numero_valorizacion='$numero_q_s',
    fecha_inicial='$fecha_inicio',
    fecha_final='$fecha_fin',
    documento='$doc_fierro'
    WHERE idconcreto_por_valorizacion='$idvalorizacion'";	
		$editar = ejecutarConsulta($sql);	 if ( $editar['status'] == false) {return $editar; }	

    //B I T A C O R A -------
    $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('concreto_por_valorizacion', '".$idvalorizacion."', 'editar concreto vaorización', '".$_SESSION['idusuario']."')";
    $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }

    return $editar;
	}

  //ver detalle quincena (cuando presiono el boton de cada quincena)
  public function mostrar_docs_quincena($nube_idproyect, $f1, $f2,  $numero_q_s) {
    $sql = "SELECT idconcreto_por_valorizacion, idproyecto, nombre_doc, numero_valorizacion, fecha_inicial, fecha_final, documento
    FROM concreto_por_valorizacion 
    WHERE  idproyecto = '$nube_idproyect' AND numero_valorizacion ='$numero_q_s' ;";
    return ejecutarConsultaSimpleFila($sql);    
  }

  public function todos_los_docs($nube_idproyect) {
    $sql = "SELECT idconcreto_por_valorizacion, idproyecto, nombre_doc, numero_valorizacion, fecha_inicial, fecha_final, documento 
    FROM concreto_por_valorizacion 
    WHERE  idproyecto = '$nube_idproyect';";
    return ejecutarConsultaArray($sql);    
  }

  // Data para listar lo bototnes por quincena
  public function listarquincenas($nube_idproyecto) {
    $sql = "SELECT p.idproyecto, p.fecha_inicio, p.fecha_fin, p.plazo, p.fecha_valorizacion 
    FROM proyecto as p 
    WHERE p.idproyecto = '$nube_idproyecto' AND p.fecha_inicio != p.fecha_fin";

    return ejecutarConsultaSimpleFila($sql);
  }


  //---------------------------------------------------
  // obtebnemos los DOCS para eliminar
  public function optener_doc_para_eliminar($idconcreto_por_valorizacion) {
    $sql = "SELECT documento FROM concreto_por_valorizacion WHERE idconcreto_por_valorizacion='$idconcreto_por_valorizacion'";
    return ejecutarConsultaSimpleFila($sql);
  }


}
 
?>
