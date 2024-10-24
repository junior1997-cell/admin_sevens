<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Valorizacion
{
  
  //Implementamos nuestro constructor
  public function __construct()
  {
  }
  
  //Editamos el DOC1 del proyecto
  public function editar_proyecto($idproyecto, $doc, $columna) {

    $sql = "UPDATE proyecto SET $columna = '$doc', user_updated= '".$_SESSION['idusuario']."' WHERE idproyecto = '$idproyecto'";
    $update_columna = ejecutarConsulta($sql);

    //B I T A C O R A -------
    $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('proyecto', '$idproyecto', 'Actualizar la columna: $columna', '".$_SESSION['idusuario']."')";
    $bitacora = ejecutarConsulta($sql_b);
    if ( $bitacora['status'] == false) {return $bitacora; }

    return $update_columna;
  }

  //Editamos el DOC1 del proyecto
  public function insertar_valorizacion($idproyecto, $indice, $nombre, $fecha_inicio, $fecha_fin, $numero_q_s, $doc) {
    $sql = "INSERT INTO valorizacion ( idproyecto,indice, nombre, fecha_inicio, fecha_fin, numero_q_s, doc_valorizacion, user_created ) 
		VALUES ('$idproyecto', '$indice', '$nombre', '$fecha_inicio', '$fecha_fin', '$numero_q_s', '$doc', '".$_SESSION['idusuario']."')";
    $new_valorizacion = ejecutarConsulta_retornarID($sql);

    //B I T A C O R A -------
    $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('valorizacion', '".$new_valorizacion['data']."', 'Crear registro', '".$_SESSION['idusuario']."')";
    $bitacora = ejecutarConsulta($sql_b);
    if ( $bitacora['status'] == false) {return $bitacora; }

    return $new_valorizacion;
  }

  //Implementamos un método para editar registros
  public function editar_valorizacion($idproyecto, $idvalorizacion, $indice, $nombre, $fecha_inicio, $fecha_fin, $numero_q_s, $doc) {
    $sql = "UPDATE valorizacion SET 
		idproyecto = '$idproyecto', 
		indice = '$indice', 
		nombre = '$nombre', 
		fecha_inicio = '$fecha_inicio',
		fecha_fin = '$fecha_fin' , 
		numero_q_s = '$numero_q_s', 
		doc_valorizacion = '$doc',
    user_updated = '".$_SESSION['idusuario']."'
		WHERE idvalorizacion = '$idvalorizacion'";
    $new_val =  ejecutarConsulta($sql);

    //B I T A C O R A -------
    $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('valorizacion', '".$idvalorizacion."', 'Crear registro', '".$_SESSION['idusuario']."')";
    $bitacora = ejecutarConsulta($sql_b);
    if ( $bitacora['status'] == false) {return $bitacora; }

    return $new_val;
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
  public function listar_quincenas_btn($nube_idproyecto) {
    $sql_1 = "SELECT p.idproyecto, p.fecha_inicio, p.fecha_fin, p.plazo, p.fecha_pago_obrero, p.fecha_valorizacion 
		FROM proyecto as p 
		WHERE p.idproyecto = '$nube_idproyecto' AND p.fecha_inicio != p.fecha_fin";
    $proyecto = ejecutarConsultaSimpleFila($sql_1); if ($proyecto['status'] == false) { return $proyecto; }

    $sql_2 = "SELECT idresumen_q_s_valorizacion, idproyecto, numero_q_s, fecha_inicio, fecha_fin, fecha_inicio_oculto, fecha_fin_oculto
    FROM resumen_q_s_valorizacion WHERE idproyecto = '$nube_idproyecto' AND estado=1 AND estado_delete=1  ORDER BY numero_q_s ASC; "; 
    $fechas = ejecutarConsultaArray($sql_2); if ($fechas['status'] == false) { return $fechas; }

    $results = [
      "status" => true,
      "message" => 'Todo oka',
      "data" => [
        "idproyecto"          => $proyecto['data']['idproyecto'],
        "fecha_inicio"        => $proyecto['data']['fecha_inicio'],
        "fecha_fin"           => $proyecto['data']['fecha_fin'],
        "plazo"               => $proyecto['data']['plazo'],
        "fecha_pago_obrero"   => $proyecto['data']['fecha_pago_obrero'],
        "fecha_valorizacion"  => $proyecto['data']['fecha_valorizacion'],
        "fechas_val"          => $fechas['data'],
      ],
    ];
    return $results;
  }

  //ver detalle quincena (cuando presiono el boton de cada quincena)
  public function ver_detalle_quincena($f1, $f2, $nube_idproyect) {
    $sql = "SELECT v.idvalorizacion, v.idproyecto, v.indice, v.nombre, v.doc_valorizacion, v.fecha_inicio, v.estado
		FROM valorizacion as v
		WHERE v.idproyecto = '$nube_idproyect' AND v.fecha_inicio BETWEEN '$f1' AND '$f2' ;";
    $data1 = ejecutarConsultaArray($sql);  if ($data1['status'] == false) { return $data1; }

    $sql2 = "SELECT p.idproyecto, p.doc1_contrato_obra AS doc1, p.doc2_entrega_terreno AS doc81, p.doc3_inicio_obra AS doc82, p.doc7_cronograma_obra_valorizad AS doc4, p.doc8_certificado_habilidad_ing_residnt AS doc83 
		FROM proyecto as p 
		WHERE p.idproyecto = '$nube_idproyect' ;";
    $data2 = ejecutarConsultaSimpleFila($sql2); if ($data2['status'] == false) { return $data2; }

    $results = [
      "status" => true,
      "message" => 'Todo oka',
      "data" => [
        "data1" => $data1['data'],
        "data2" => $data2['data'],
        "count_data1" => count($data1),
      ],
    ];

    return $results;
  }

  public function tabla_principal($nube_idproyecto)  {
    $data = [];
    $scheme_host=  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/admin_sevens/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');

    $sql1 = "SELECT * FROM valorizacion WHERE estado=1 AND estado_delete=1 AND idproyecto='$nube_idproyecto' ORDER BY  numero_q_s DESC, indice ASC";
    $valorizacion = ejecutarConsultaArray($sql1); if ($valorizacion['status'] == false) { return $valorizacion; }

    if (!empty($valorizacion['data'])) {
      foreach ($valorizacion['data'] as $key => $value1) {
        $monto_total_gastado = suma_totales_modulos($nube_idproyecto, $value1['fecha_inicio'], $value1['fecha_fin']);
        $data[] = [
          'nombre_tabla'        => 'valorizacion',
          'idtabla'             => $value1['idvalorizacion'],
          'nombre_columna'      => 'idvalorizacion',
          'indice'              => $value1['indice'],
          'nombre'              => $value1['nombre'],
          'doc_valorizacion'    => $value1['doc_valorizacion'],
          'fecha_inicio'        => $value1['fecha_inicio'],
          'fecha_fin'           => $value1['fecha_fin'],
          'numero_q_s'          => $value1['numero_q_s'],
          'monto_total_gastado' => $monto_total_gastado,
        ];
      }
    }

    $sql2 = "SELECT  doc1_contrato_obra,doc2_entrega_terreno,doc3_inicio_obra,doc7_cronograma_obra_valorizad,doc8_certificado_habilidad_ing_residnt,estado, idproyecto
		FROM proyecto WHERE estado_delete=1 AND idproyecto='$nube_idproyecto'";
    $documentos_proyect = ejecutarConsultaSimpleFila($sql2); if ($documentos_proyect['status'] == false) { return $documentos_proyect; }

    if (!empty($documentos_proyect['data'])) {
      $data[] = [
        'nombre_tabla' => 'proyecto',
        'idtabla' => $documentos_proyect['data']['idproyecto'],
        'nombre_columna' => 'doc1_contrato_obra',
        'indice' => '1',
        'nombre' => 'Acta de contrato de obra',
        'doc_valorizacion' => $documentos_proyect['data']['doc1_contrato_obra'],
        'fecha_inicio' => ' - - - ',
        'fecha_fin' => ' - - - ',
        'numero_q_s' => 'General',
      ];

      $data[] = [
        'nombre_tabla' => 'proyecto',
        'idtabla' => $documentos_proyect['data']['idproyecto'],
        'nombre_columna' => 'doc2_entrega_terreno',
        'indice' => '2',
        'nombre' => 'Acta de entrega de terreno',
        'doc_valorizacion' => $documentos_proyect['data']['doc2_entrega_terreno'],
        'fecha_inicio' => ' - - - ',
        'fecha_fin' => ' - - - ',
        'numero_q_s' => 'General',
      ];

      $data[] = [
        'nombre_tabla' => 'proyecto',
        'idtabla' => $documentos_proyect['data']['idproyecto'],
        'nombre_columna' => 'doc3_inicio_obra',
        'indice' => '3',
        'nombre' => 'Acta de inicio de obra',
        'doc_valorizacion' => $documentos_proyect['data']['doc3_inicio_obra'],
        'fecha_inicio' => ' - - - ',
        'fecha_fin' => ' - - - ',
        'numero_q_s' => 'General',
      ];

      $data[] = [
        'nombre_tabla' => 'proyecto',
        'idtabla' => $documentos_proyect['data']['idproyecto'],
        'nombre_columna' => 'doc7_cronograma_obra_valorizad',
        'indice' => '7',
        'nombre' => 'Cronograma de obra valorizado',
        'doc_valorizacion' => $documentos_proyect['data']['doc7_cronograma_obra_valorizad'],
        'fecha_inicio' => ' - - - ',
        'fecha_fin' => ' - - - ',
        'numero_q_s' => 'General',
      ];

      $data[] = [
        'nombre_tabla' => 'proyecto',
        'idtabla' => $documentos_proyect['data']['idproyecto'],
        'nombre_columna' => 'doc8_certificado_habilidad_ing_residnt',
        'indice' => '8',
        'nombre' => 'Certificado de habilidad del ingeniero residente',
        'doc_valorizacion' => $documentos_proyect['data']['doc8_certificado_habilidad_ing_residnt'],
        'fecha_inicio' => ' - - - ',
        'fecha_fin' => ' - - - ',
        'numero_q_s' => 'General',
      ];
    }
    return $retorno = [ 'status' => true, 'message' => 'todo oka', 'data' => $data] ; 
  }
  //---------------------------------------------------

  //Implementamos un método para desactivar
  public function desactivar($nombre_tabla, $nombre_columna, $idtabla) {
    $sql = "UPDATE $nombre_tabla SET estado='0', user_trash = '".$_SESSION['idusuario']."' WHERE $nombre_columna ='$idtabla'";
    $desactivar = ejecutarConsulta($sql); if ( $desactivar['status'] == false) {return $desactivar; }

    //B I T A C O R A -------
    $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('$nombre_tabla', '".$idtabla."', 'Desactivar', '".$_SESSION['idusuario']."')";
    $bitacora = ejecutarConsulta($sql_b);  if ( $bitacora['status'] == false) {return $bitacora; }

    return $desactivar; 
  }

  //Implementamos un método para elimnar
  public function eliminar($nombre_tabla, $nombre_columna, $idtabla) {
    $sql = "UPDATE $nombre_tabla SET estado_delete='0', user_delete = '".$_SESSION['idusuario']."' WHERE $nombre_columna ='$idtabla'";
    $eliminar = ejecutarConsulta($sql);
    if ( $eliminar['status'] == false) {return $eliminar; }

    //B I T A C O R A -------
    $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('$nombre_tabla', '".$idtabla."', 'Eliminar', '".$_SESSION['idusuario']."')";
    $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }

    return $eliminar; 
  }
  //---------------------------------------------------
  // obtebnemos los DOCS para eliminar
  public function obtenerDocV($idvalorizacion) {
    $sql = "SELECT doc_valorizacion FROM valorizacion WHERE idvalorizacion='$idvalorizacion'";
    return ejecutarConsulta($sql);
  }

  // obtebnemos los DOCS para eliminar
  public function obtenerDocP($idproyecto, $columna) {
    $sql = "SELECT $columna AS doc_p FROM proyecto WHERE idproyecto = '$idproyecto'";
    return ejecutarConsulta($sql);
  }

  // =============================== R E S U M E N   Q S ======================================

  public function insertar_editar_resumen_q_s($array_val, $idproyecto) {    

    foreach (json_decode( $array_val, true) as $key => $value) {
      $idresumen        = $value['idresumen_q_s_valorizacion'];
      $idproyecto       = $value['idproyecto'];
      $numero_q_s       = $value['numero_q_s'];
      $fecha_inicial    = $value['fecha_inicio'];
      $fecha_final      = $value['fecha_fin'];
      $monto_programado = $value['monto_programado'];
      $monto_valorizado = $value['monto_valorizado'];      

      if ( empty($idresumen) ) {
        return $retorno = ['status' => 'error', 'message' => 'todo oka ps', 'data' => [], ];
      } else {         
        $sql_3 = "UPDATE resumen_q_s_valorizacion SET idproyecto='$idproyecto', numero_q_s='$numero_q_s', fecha_inicio='$fecha_inicial',
        fecha_fin='$fecha_final', monto_programado='$monto_programado', monto_valorizado='$monto_valorizado', monto_gastado='0', 
        estado='1', estado_delete='1', user_updated='".$_SESSION['idusuario']."' WHERE idresumen_q_s_valorizacion='$idresumen'";
        $editando =  ejecutarConsulta($sql_3); if ($editando['status'] == false) { return $editando; }

        //B I T A C O R A -------
        $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('resumen_q_s_valorizacion', '".$idresumen."', 'Editar registro montos', '".$_SESSION['idusuario']."')";
        $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }
      } 
    }    
    return $retorno = ['status' => true, 'message' => 'todo oka ps', 'data' => [], ];
  }  

  public function tbla_resumen_q_s($idproyecto, $array_fechas) {
    $data = [];
     
    foreach (json_decode($array_fechas, true) as $key => $value) {
      $num = $value['num_q_s'];
      $sql = "SELECT idresumen_q_s_valorizacion, monto_programado, monto_valorizado FROM resumen_q_s_valorizacion 
      WHERE idproyecto='$idproyecto' AND numero_q_s='$num' AND estado=1 AND estado_delete=1;";
      $val_q_s =  ejecutarConsultaSimpleFila($sql);  if ($val_q_s['status'] == false) { return $val_q_s; }

      // $monto_gastado = suma_totales_modulos($idproyecto, $value['fecha_inicio_oculto'], $value['fecha_fin_oculto']);
      // "CALL suma_de_modulos_rango_fecha;";
      $sql_modulos = "CALL suma_de_modulos_rango_fecha($idproyecto,'".$value['fecha_inicio_oculto']."', '".$value['fecha_fin_oculto']."', 'SUMA');";
      $sum_modulos =  ejecutarConsultaSimpleFilaStore($sql_modulos);  if ($sum_modulos['status'] == false) { return $sum_modulos; }

      $data[] = [
        'idresumen_q_s_valorizacion'=> (empty($val_q_s['data']) ? '' : $val_q_s['data']['idresumen_q_s_valorizacion']),
        'idproyecto'                => $idproyecto,
        'numero_q_s'                => $value['num_q_s'],
        'fecha_inicio'              => $value['fecha_inicio'],
        'fecha_fin'                 => $value['fecha_fin'],
        'fecha_inicio_oculto'       => $value['fecha_inicio_oculto'],
        'fecha_fin_oculto'          => $value['fecha_fin_oculto'],
        'monto_programado'          => (empty($val_q_s['data']) ? 0 : (empty($val_q_s['data']['monto_programado']) ? 0 : floatval($val_q_s['data']['monto_programado']) ) ),
        'monto_valorizado'          => (empty($val_q_s['data']) ? 0 : (empty($val_q_s['data']['monto_valorizado']) ? 0 : floatval($val_q_s['data']['monto_valorizado']) ) ),
        'monto_gastado'             => (empty($sum_modulos['data']) ? 0 : (empty($sum_modulos['data']['total']) ? 0 : floatval($sum_modulos['data']['total']) ) ),
      ];
    }

    $sql_2 = "SELECT idproyecto,  nombre_codigo,  costo, garantia, fecha_inicio, fecha_fin, feriado_domingo,  fecha_valorizacion, permanente_pago_obrero
    FROM proyecto WHERE idproyecto='$idproyecto' ;";
    $proyecto =  ejecutarConsultaSimpleFila($sql_2);  if ($proyecto['status'] == false) { return $proyecto; }
    
    return $retorno = [ 'status' => true, 
      'message' => 'todo oka', 
      'data' =>[
        'montos' =>$data, 
        'proyecto_costo' => (empty($proyecto['data']) ? 0 : (empty($proyecto['data']['costo']) ? 0 : floatval($proyecto['data']['costo']) ) ),
        'proyecto_garantia' => (empty($proyecto['data']) ? 0 : (empty($proyecto['data']['garantia']) ? 0 : floatval($proyecto['data']['garantia']) ) )
      ],      
    ];  
  }

  public function list_total_montos_resumen_q_s($idproyecto_q_s) {
    $data_totales = []; $monto_gastado=0;
    $sql = "SELECT SUM(monto_programado) as m_programado,  SUM(monto_valorizado) as m_valorizado FROM resumen_q_s_valorizacion WHERE idproyecto='$idproyecto_q_s' AND estado=1 AND estado_delete=1;";
    $total_montos_r_q_s =  ejecutarConsultaSimpleFila($sql); if ($total_montos_r_q_s['status'] == false) { return $total_montos_r_q_s; }

    $sql = "SELECT * FROM resumen_q_s_valorizacion WHERE idproyecto='$idproyecto_q_s' AND estado=1 AND estado_delete=1;";
    $resumen_valorizacion =  ejecutarConsultaArray($sql);  if ($resumen_valorizacion['status'] == false) { return $resumen_valorizacion; }

    foreach ($resumen_valorizacion['data'] as $key => $value1) {
      $monto_gastado += suma_totales_modulos($idproyecto_q_s,$value1['fecha_inicio'],$value1['fecha_fin']);
    }

    $data_totales = [
      'm_programado' => $total_montos_r_q_s['data']['m_programado'],
      'm_valorizado' => $total_montos_r_q_s['data']['m_valorizado'],
      'm_gastado' => $monto_gastado
    ];

    return $retorno = [ 'status' => true, 'message' => 'todo oka', 'data' => $data_totales] ; 

  }

  public function detalle_x_modulo($idproyecto) {

    $sql1 = "SELECT min(fecha_inicio_oculto) as fecha_incio FROM resumen_q_s_valorizacion WHERE idproyecto = '$idproyecto' and estado=1 AND estado_delete=1";
    $fecha_1 =  ejecutarConsultaSimpleFila($sql1); if ($fecha_1['status'] == false) { return $fecha_1; }  
    $sql2 = "SELECT max(fecha_fin_oculto) as fecha_fin FROM resumen_q_s_valorizacion WHERE idproyecto = '$idproyecto' and estado=1 AND estado_delete=1";
    $fecha_2 =  ejecutarConsultaSimpleFila($sql2); if ($fecha_2['status'] == false) { return $fecha_2; }  
    $fecha_init = $fecha_1['data']['fecha_incio'];
    $fecha_fin = $fecha_2['data']['fecha_fin'];

    $sql_modulos = "CALL suma_de_modulos_rango_fecha($idproyecto,' $fecha_init', '$fecha_fin', 'DETALLE');";
    return  ejecutarConsultaArrayStore($sql_modulos);  

  }

  // :::::::::::::::::::::::::::::::::::::::::: U P D A T E   F E C H A S  O C U L T A S :::::::::::::::::::::::::::::::
  public function mostrar_para_editar_fechas($idproyecto) {
    
    $sql = "SELECT * FROM resumen_q_s_valorizacion WHERE idproyecto='$idproyecto' AND estado=1 AND estado_delete=1 ORDER BY numero_q_s ASC;;";
    return ejecutarConsultaArray($sql);    

  }

  public function guardar_y_editar_fecha_oculta($idtabla, $idproyecto_fo, $array_fechas_p_1, $array_fechas_p_2,  $array_fechas_1, $array_fechas_2) {
    
    $cont = 1;
    $sql = "UPDATE resumen_q_s_valorizacion SET estado='0',estado_delete='0', user_delete ='".$_SESSION['idusuario']."' WHERE idproyecto ='".$idproyecto_fo[0]."'";
    $delete_f = ejecutarConsulta($sql); if ( $delete_f['status'] == false) {return $delete_f; }

    //B I T A C O R A -------
    $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('resumen_q_s_valorizacion', '".$idproyecto_fo[0]."', 'Eliminar fechas segun proyecto: ".$idproyecto_fo[0]."', '".$_SESSION['idusuario']."')";
    $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }

    foreach ($idtabla as $key => $value) {
      if ( isset( $array_fechas_p_1[$key] ) ) {       
      
        if (empty($value) || $value == 0) {
          
          $sql = "INSERT INTO resumen_q_s_valorizacion(idproyecto, numero_q_s, fecha_inicio, fecha_fin, fecha_inicio_oculto, fecha_fin_oculto, user_created) 
          VALUES ('".$idproyecto_fo[$key]."','$cont','".$array_fechas_p_1[$key]."','".$array_fechas_p_2[$key]."','".$array_fechas_1[$key]."','".$array_fechas_2[$key]."', '".$_SESSION['idusuario']."')";
          $sw = ejecutarConsulta_retornarID($sql); if ($sw['status'] == false) { return $sw; }

          //B I T A C O R A -------
          $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('resumen_q_s_valorizacion', '".$sw['data']."', 'Crear registro fechas ocultas', '".$_SESSION['idusuario']."')";
          $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }
        } else {
          $sql = "UPDATE resumen_q_s_valorizacion SET numero_q_s = '$cont', 
          fecha_inicio='".$array_fechas_p_1[$key]."', fecha_fin='".$array_fechas_p_2[$key]."', 
          fecha_inicio_oculto='".$array_fechas_1[$key]."', fecha_fin_oculto='".$array_fechas_2[$key]."', estado='1', estado_delete='1',
          user_updated = '".$_SESSION['idusuario']."' 
          WHERE idresumen_q_s_valorizacion='$value'";
          $sw = ejecutarConsulta($sql); if ($sw['status'] == false) { return $sw; }

          //B I T A C O R A -------
          $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('resumen_q_s_valorizacion', '".$value."', 'Editar registro fechas ocultas', '".$_SESSION['idusuario']."')";
          $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }
        } 
        $cont++;    
      }             
    }  
    
    return $retorno = [ 'status' => true, 'message' => 'todo oka', 'data' => $cont] ;
  }

}
 
?>
