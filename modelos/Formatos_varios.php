<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class FormatosVarios
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  
  //Implementar un método para listar asistencia
  public function formato_ats($nube_idproyecto) {
     
    $data = [];

    $sql = "SELECT t.idtrabajador, t.nombres, t.tipo_documento, t.numero_documento,  t.imagen_perfil as imagen, t.telefono, t.fecha_nacimiento,
    t.email, tpp.desempenio, tpp.sueldo_mensual, tpp.sueldo_diario, tpp.sueldo_hora, tpp.fecha_inicio, tpp.fecha_fin, tpp.estado, 
    tpp.idtrabajador_por_proyecto, t.idtipo_trabajador, tt.nombre as nombre_tipo, oc.nombre_ocupacion, d.nombre_desempenio
		FROM trabajador_por_proyecto as tpp, trabajador as t,  tipo_trabajador as tt, ocupacion as oc, desempenio as d
		WHERE tpp.idtrabajador = t.idtrabajador AND tt.idtipo_trabajador=t.idtipo_trabajador AND t.idocupacion = oc.idocupacion 
    AND d.iddesempenio = tpp.iddesempenio
    AND tpp.idproyecto = '$nube_idproyecto' AND tpp.estado='1' AND tpp.estado_delete='1' ORDER BY tpp.orden_trabajador ASC";
    $trabajdor = ejecutarConsultaArray($sql); if ($trabajdor['status'] == false) { return  $trabajdor;}

    foreach ($trabajdor['data'] as $key => $value) {
      $id = $value['idtrabajador'];
      $sql2 = "SELECT cbt.idcuenta_banco_trabajador, cbt.idtrabajador, cbt.idbancos, cbt.cuenta_bancaria, cbt.cci, cbt.banco_seleccionado, b.nombre as banco
      FROM cuenta_banco_trabajador as cbt, bancos as b
      WHERE cbt.idbancos = b.idbancos AND cbt.banco_seleccionado ='1' AND cbt.idtrabajador='$id' ;";
      $bancos = ejecutarConsultaSimpleFila($sql2); if ($bancos['status'] == false) { return  $bancos;}     

      $data[] = array(
        'orden' => $key+1,
        'idtrabajador_por_proyecto' =>$value['idtrabajador_por_proyecto'],
        'idtrabajador'    => $value['idtrabajador'],  
        'trabajador'      => $value['nombres'], 
        'tipo_documento'  => $value['tipo_documento'], 
        'numero_documento'=> $value['numero_documento'], 
        'imagen_perfil'   => $value['imagen'],          
        'telefono'        => $value['telefono'],         
        'fecha_nacimiento'=> $value['fecha_nacimiento'],
        'email'           => $value['email'],
        'sueldo_diario'   =>$value['sueldo_diario'],
        'sueldo_hora'     =>$value['sueldo_hora'],
        'fecha_inicio'    =>$value['fecha_inicio'],
        'fecha_fin'       =>$value['fecha_fin'],
        'estado'          =>$value['estado'],
                
        'idtipo_trabajador'=>$value['idtipo_trabajador'],
        'nombre_tipo'     =>$value['nombre_tipo'],
        'nombre_ocupacion'=> $value['nombre_ocupacion'],
        'nombre_desempeno'=>$value['nombre_desempenio'],

        'banco'           => (empty($bancos['data']) ? "": $bancos['data']['banco']), 
        'cuenta_bancaria' => (empty($bancos['data']) ? "" : $bancos['data']['cuenta_bancaria']), 
        'cci'             => (empty($bancos['data']) ? "" : $bancos['data']['cci']), 
      );
    }
    return $retorno=['status'=>true, 'message'=>'todo oka ps', 'data'=>$data];
  }

  //Implementar un método para listar asistencia
  public function formato_temperatura($nube_idproyecto) {
     
    $sql = "SELECT atr.idtrabajador_por_proyecto, t.idtrabajador AS idtrabajador, t.nombres AS nombre, t.tipo_documento as tipo_doc, 
		t.numero_documento AS num_doc, t.imagen_perfil AS imagen, tpp.sueldo_hora, tpp.sueldo_mensual, tpp.sueldo_diario,
		SUM(atr.horas_normal_dia) AS total_horas_normal, SUM(atr.horas_extras_dia) AS total_horas_extras, 
		atr.estado as estado, p.fecha_inicio AS fecha_inicio_proyect, tp.nombre AS tipo_trabajador, o.nombre_ocupacion as desempenio
		FROM trabajador AS t, trabajador_por_proyecto AS tpp, tipo_trabajador AS tp, ocupacion as o, asistencia_trabajador AS atr,  proyecto AS p
		WHERE t.idtrabajador = tpp.idtrabajador AND tpp.idtrabajador_por_proyecto = atr.idtrabajador_por_proyecto 
		AND tpp.idproyecto = p.idproyecto AND t.idtipo_trabajador = tp.idtipo_trabajador AND t.idocupacion = o.idocupacion
    AND atr.estado = '1' AND atr.estado_delete = '1' AND tpp.idproyecto = '$nube_idproyecto' 
		GROUP BY tpp.idtrabajador_por_proyecto ORDER BY tpp.orden_trabajador ASC;";
    return ejecutarConsultaArray($sql);      
  }

  //Implementar un método para listar asistencia
  public function formato_check_list_epps($nube_idproyecto) {
     
    $sql = "SELECT atr.idtrabajador_por_proyecto, t.idtrabajador AS idtrabajador, t.nombres AS nombre, t.tipo_documento as tipo_doc, 
		t.numero_documento AS num_doc, t.imagen_perfil AS imagen, tpp.sueldo_hora, tpp.sueldo_mensual, tpp.sueldo_diario,
		SUM(atr.horas_normal_dia) AS total_horas_normal, SUM(atr.horas_extras_dia) AS total_horas_extras, 
		atr.estado as estado, p.fecha_inicio AS fecha_inicio_proyect, tp.nombre AS tipo_trabajador, o.nombre_ocupacion as desempenio
		FROM trabajador AS t, trabajador_por_proyecto AS tpp, tipo_trabajador AS tp, ocupacion as o, asistencia_trabajador AS atr,  proyecto AS p
		WHERE t.idtrabajador = tpp.idtrabajador AND tpp.idtrabajador_por_proyecto = atr.idtrabajador_por_proyecto 
		AND tpp.idproyecto = p.idproyecto AND t.idtipo_trabajador = tp.idtipo_trabajador AND t.idocupacion = o.idocupacion
    AND atr.estado = '1' AND atr.estado_delete = '1' AND tpp.idproyecto = '$nube_idproyecto' 
		GROUP BY tpp.idtrabajador_por_proyecto ORDER BY tpp.orden_trabajador ASC;";
    return ejecutarConsultaArray($sql);      
  }


}

?>
