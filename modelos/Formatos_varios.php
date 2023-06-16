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
    $trabajdor = ejecutarConsultaArray($sql); if ($trabajdor['status'] == false) { return  $trabajdor;};

    $sql_2 = "SELECT idproyecto,nombre_proyecto,nombre_codigo,actividad_trabajo,empresa_acargo,ubicacion,numero_documento,empresa
    FROM proyecto WHERE idproyecto ='$nube_idproyecto';" ; 
    $datosproyect = ejecutarConsultaSimpleFila($sql_2); if ($datosproyect['status'] == false) { return  $datosproyect;};

    $cant_array = count($trabajdor['data']);
    $cant_array_mitad = count($trabajdor['data'])/2;

    foreach ($trabajdor['data'] as $key => $value) {
      $id = $value['idtrabajador'];
      $sql2 = "SELECT cbt.idcuenta_banco_trabajador, cbt.idtrabajador, cbt.idbancos, cbt.cuenta_bancaria, cbt.cci, cbt.banco_seleccionado, b.nombre as banco
      FROM cuenta_banco_trabajador as cbt, bancos as b
      WHERE cbt.idbancos = b.idbancos AND cbt.banco_seleccionado ='1' AND cbt.idtrabajador='$id' ;";
      $bancos = ejecutarConsultaSimpleFila($sql2); if ($bancos['status'] == false) { return  $bancos;}     

      $data[] = array(
        'cant_array'=> $cant_array,
        'cant_array_mitad'=> $cant_array_mitad,
        'orden'           => $key+1,
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
    $proyecto = [
      'idproyecto' => $datosproyect['data']['idproyecto'],
      'nombre_proyecto'=> $datosproyect['data']['nombre_proyecto'],
      'nombre_codigo'=> $datosproyect['data']['nombre_codigo'],
      'actividad_trabajo'=> $datosproyect['data']['actividad_trabajo'],
      'empresa_acargo'=> $datosproyect['data']['empresa_acargo'],
      'ubicacion'=> $datosproyect['data']['ubicacion'],
      'numero_documento'=> $datosproyect['data']['numero_documento'],
      'empresa'=> $datosproyect['data']['empresa']
    ]; 
    return $retorno=['status'=>true, 'message'=>'todo oka ps', 'data'=>$data,'proyecto'=>$proyecto];
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

  // ══════════════════════════════════════════ - ASISTENCIA TRABAJADOR - ══════════════════════════════════════════

  //listar botones de la quincena o semana
  public function datos_proyecto($nube_idproyecto) {
    $sql = "SELECT p.idproyecto, p.fecha_inicio_actividad AS fecha_inicio, p.fecha_fin_actividad AS fecha_fin, p.plazo_actividad AS plazo, 
    p.fecha_pago_obrero, p.fecha_valorizacion, p.nombre_proyecto, p.nombre_codigo,p.actividad_trabajo,p.empresa,p.ubicacion
    FROM proyecto as p WHERE p.idproyecto='$nube_idproyecto'";
    return  ejecutarConsultaSimpleFila($sql);

  }

  //ver detalle quincenal o semanal
  public function ver_datos_trabajador($f1, $f2, $nube_idproyect, $num_quincena, $cant_dias_asistencia) {

    // extraemos todos lo trabajadores del proyecto
    $sql2 = "SELECT tpp.idtrabajador_por_proyecto, o.nombre_ocupacion, tp.nombre as tipo_trabajador, t.nombres, t.tipo_documento, 
    t.numero_documento, tpp.sueldo_mensual, tpp.sueldo_semanal, tpp.sueldo_diario, tpp.sueldo_hora, tpp.estado, tpp.fecha_inicio, tpp.fecha_fin
    FROM trabajador_por_proyecto AS tpp, trabajador AS t, tipo_trabajador AS tp, ocupacion AS o
    WHERE tpp.idtrabajador = t.idtrabajador  AND o.idocupacion = t.idocupacion AND t.idtipo_trabajador = tp.idtipo_trabajador 
    AND  tpp.idproyecto = '$nube_idproyect' AND tp.nombre ='Obrero' ORDER BY tpp.orden_trabajador ASC ;";
    $trabajador = ejecutarConsultaArray($sql2); if ($trabajador['status'] == false) {  return $trabajador; }    

    $data = [];
    $extras = "";    

    foreach ($trabajador['data'] as $indice => $key) {
      $id_tpp = $key['idtrabajador_por_proyecto']; $d_asistencia = [];

      // extraemos la asistencia por trabajador
      $sql3 = "SELECT * FROM asistencia_trabajador  AS atr 
      WHERE atr.idtrabajador_por_proyecto = '$id_tpp' AND atr.estado='1' AND atr.estado_delete='1' AND atr.fecha_asistencia BETWEEN '$f1' AND '$f2';";
      $asistencia = ejecutarConsultaArray($sql3);  if ($asistencia['status'] == false) {  return $asistencia; }

      $f_i  = new DateTime($f1); $dia_regular = 0;
      $weekday_regular = $f_i->format("w");
      if ($weekday_regular == "0") { $dia_regular = -1; 
      } else if ($weekday_regular == "1") { $dia_regular = -2;       
      } else if ($weekday_regular == "2") { $dia_regular = -3;         
      } else if ($weekday_regular == "3") { $dia_regular = -4;           
      } else if ($weekday_regular == "4") { $dia_regular = -5;           
      } else if ($weekday_regular == "5") { $dia_regular = -6;            
      } else if ($weekday_regular == "6") { $dia_regular = -7; }
      
      $f_i->modify("$dia_regular day");

      for ($i=0; $i <= ($dia_regular * -1) ; $i++) { 
        $d_asistencia[] = [
          "estado_regular"            => true,
          "idasistencia_trabajador"   => "",
          "idtrabajador_por_proyecto" => "",
          "horas_normal_dia"          => "",
          "pago_normal_dia"           => "",
          "horas_extras_dia"          => "",
          "pago_horas_extras"         => "",
          "fecha_asistencia"          => $f_i->format("Y-m-d"),
          "nombre_dia"                => nombre_dia_semana($f_i->format("Y-m-d")),
          "nombre_dia_v1"             => nombre_dia_semana_v1($f_i->format("Y-m-d")),
          "nombre_dia_v2"             => nombre_dia_semana_v2($f_i->format("Y-m-d")),
          "descripcion_justificacion" => "",
          "estado"                    => "",
          "estado_delete"             => "",
        ];
        $f_i->modify("+1 day");
      }
      for ($i=0; $i <= $cant_dias_asistencia + $dia_regular; $i++) { 
        // if (condition) {
        //   # code...
        // } else {
        //   # code...
        // }
        
        $d_asistencia[] = [
          "estado_regular"            => false,
          "idasistencia_trabajador"   => "",
          "idtrabajador_por_proyecto" => "",
          "horas_normal_dia"          => "",
          "pago_normal_dia"           => "",
          "horas_extras_dia"          => "",
          "pago_horas_extras"         => "",
          "fecha_asistencia"          => "",
          "nombre_dia"                => "",
          "descripcion_justificacion" => "",
          "estado"                    => "",
          "estado_delete"             => "",
        ];
        $f_i->modify("+1 day");
      }
      

      $sql4 = "SELECT idresumen_q_s_asistencia, idtrabajador_por_proyecto, numero_q_s, fecha_q_s_inicio, fecha_q_s_fin, total_hn, total_he, total_dias_asistidos, sabatical, sabatical_manual_1, sabatical_manual_2, pago_parcial_hn, pago_parcial_he, adicional_descuento, descripcion_descuento, pago_quincenal, estado_envio_contador 
      FROM resumen_q_s_asistencia WHERE idtrabajador_por_proyecto = '$id_tpp' AND estado='1' AND estado_delete='1' AND numero_q_s = '$num_quincena';";
      $extras = ejecutarConsultaSimpleFila($sql4); if ($extras['status'] == false) {  return $extras; }       

      if ( validar_fecha_menor_igual_que($f2, $key['fecha_fin']) == true || fecha_dentro_de_rango($key['fecha_fin'],$f1, $f2 ) ) {
        $data[] = [
          "f_i_sq"                    => $f1,
          "f_f_sq"                    => $f2,
          "idtrabajador_por_proyecto" =>  $key['idtrabajador_por_proyecto'],
          "nombre_ocupacion"          => $key['nombre_ocupacion'],
          "tipo_trabajador"           => $key['tipo_trabajador'],
          "nombres_trabajador"        => $key['nombres'],
          "tipo_documento"            => $key['tipo_documento'],
          "numero_documento"          => $key['numero_documento'],
          "sueldo_mensual"            => empty($key['sueldo_mensual']) ? 0 : floatval($key['sueldo_mensual']) ,
          "sueldo_semanal"            => empty($key['sueldo_semanal']) ? 0 : floatval($key['sueldo_semanal']),
          "sueldo_diario"             => empty($key['sueldo_diario']) ? 0 : floatval($key['sueldo_diario']),
          "sueldo_hora"               => empty($key['sueldo_hora']) ? 0 : floatval($key['sueldo_hora']),
          "estado_trabajador"         => $key['estado'],
          "fecha_inicio_t"            => $key['fecha_inicio'],
          "fecha_fin_t"               => $key['fecha_fin'],
          "asistencia"                => $asistencia['data'],

          'idresumen_q_s_asistencia'  => empty($extras['data']) ? "" : ( empty($extras['data']['idresumen_q_s_asistencia']) ? "" : $extras['data']['idresumen_q_s_asistencia']),
          'fecha_registro'            => empty($extras['data']) ? "" : ( empty($extras['data']['fecha_q_s_inicio']) ?        "" : $extras['data']['fecha_q_s_inicio']),
          'total_hn'                  => empty($extras['data']) ? 0  : ( empty($extras['data']['total_hn']) ?                0 : intval($extras['data']['total_hn']) ),
          'total_he'                  => empty($extras['data']) ? 0  : ( empty($extras['data']['total_he']) ?                0 : floatval($extras['data']['total_he']) ),
          'total_dias_asistidos'      => empty($extras['data']) ? 0  : ( empty($extras['data']['total_dias_asistidos']) ?    0 : floatval($extras['data']['total_dias_asistidos']) ),
          'sabatical'                 => empty($extras['data']) ? 0  : ( empty($extras['data']['sabatical']) ?               0 : floatval($extras['data']['sabatical']) ),
          'sabatical_manual_1'        => empty($extras['data']) ? "-": ( empty($extras['data']['sabatical_manual_1']) ?      "" : $extras['data']['sabatical_manual_1']),
          'sabatical_manual_2'        => empty($extras['data']) ? "-": ( empty($extras['data']['sabatical_manual_2']) ?      "" : $extras['data']['sabatical_manual_2']),
          'pago_parcial_hn'           => empty($extras['data']) ? 0  : ( empty($extras['data']['pago_parcial_hn']) ?         0 : floatval($extras['data']['pago_parcial_hn']) ),
          'pago_parcial_he'           => empty($extras['data']) ? 0  : ( empty($extras['data']['pago_parcial_he']) ?         0 : floatval($extras['data']['pago_parcial_he']) ),
          'adicional_descuento'       => empty($extras['data']) ? 0  : ( empty($extras['data']['adicional_descuento']) ?     0 : floatval($extras['data']['adicional_descuento']) ),
          'descripcion_descuento'     => empty($extras['data']) ? "" : ( empty($extras['data']['descripcion_descuento']) ?   "" : $extras['data']['descripcion_descuento']),
          'pago_quincenal'            => empty($extras['data']) ? 0  : ( empty($extras['data']['pago_quincenal']) ?          0 : floatval($extras['data']['pago_quincenal']) ),
          'estado_envio_contador'     => empty($extras['data']) ? "" : ( empty($extras['data']['estado_envio_contador']) ?   "" : $extras['data']['estado_envio_contador']),
        ];
      }      
    }

    return $retorno = ['status' => true, 'message' => 'todo oka ps', 'data' => $data];
    // var_dump($data);die();
  }
}

?>
