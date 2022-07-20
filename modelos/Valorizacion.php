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
    //var_dump($idproyecto, $doc, $columna, '1111');die();

    $sql = "UPDATE proyecto SET $columna = '$doc' WHERE idproyecto = '$idproyecto'";

    return ejecutarConsulta($sql);
  }

  //Editamos el DOC1 del proyecto
  public function insertar_valorizacion($idproyecto, $indice, $nombre, $fecha_inicio, $fecha_fin, $numero_q_s, $doc) {
    $sql = "INSERT INTO valorizacion ( idproyecto,indice, nombre, fecha_inicio, fecha_fin, numero_q_s, doc_valorizacion ) 
		VALUES ('$idproyecto', '$indice', '$nombre', '$fecha_inicio', '$fecha_fin', '$numero_q_s', '$doc')";

    return ejecutarConsulta($sql);
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
		doc_valorizacion = '$doc'
		WHERE idvalorizacion = '$idvalorizacion'";

    return ejecutarConsulta($sql);
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
  public function ver_detalle_quincena($f1, $f2, $nube_idproyect) {
    $sql = "SELECT v.idvalorizacion, v.idproyecto, v.indice, v.nombre, v.doc_valorizacion, v.fecha_inicio, v.estado
		FROM valorizacion as v
		WHERE v.idproyecto = '$nube_idproyect' AND v.fecha_inicio BETWEEN '$f1' AND '$f2';";
    $data1 = ejecutarConsultaArray($sql);
    if ($data1['status'] == false) { return $data1; }

    $sql2 = "SELECT p.idproyecto, p.doc1_contrato_obra AS doc1, p.doc2_entrega_terreno AS doc81, p.doc3_inicio_obra AS doc82, p.doc7_cronograma_obra_valorizad AS doc4, p.doc8_certificado_habilidad_ing_residnt AS doc83 
		FROM proyecto as p 
		WHERE p.idproyecto = '$nube_idproyect';";
    $data2 = ejecutarConsultaSimpleFila($sql2);
    if ($data2['status'] == false) { return $data2; }

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
    $valorizacion = ejecutarConsultaArray($sql1);

    if ($valorizacion['status'] == false) { return $valorizacion; }

    if (!empty($valorizacion['data'])) {
      foreach ($valorizacion['data'] as $key => $value1) {
        $monto_total_gastado = suma_totales($nube_idproyecto, $value1['fecha_inicio'], $value1['fecha_fin']);
        $data[] = [
          'nombre_tabla' => 'valorizacion',
          'idtabla' => $value1['idvalorizacion'],
          'nombre_columna' => 'idvalorizacion',
          'indice' => $value1['indice'],
          'nombre' => $value1['nombre'],
          'doc_valorizacion' => $value1['doc_valorizacion'],
          'fecha_inicio' => $value1['fecha_inicio'],
          'fecha_fin' => $value1['fecha_fin'],
          'numero_q_s' => $value1['numero_q_s'],
          'monto_total_gastado' => $monto_total_gastado,
        ];
      }
    }

    $sql2 = "SELECT  doc1_contrato_obra,doc2_entrega_terreno,doc3_inicio_obra,doc7_cronograma_obra_valorizad,doc8_certificado_habilidad_ing_residnt,estado, idproyecto
		FROM proyecto WHERE estado_delete=1 AND idproyecto='$nube_idproyecto'";
    $documentos_proyect = ejecutarConsultaSimpleFila($sql2);

    if ($documentos_proyect['status'] == false) { return $documentos_proyect; }

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
    $sql = "UPDATE $nombre_tabla SET estado='0' WHERE $nombre_columna ='$idtabla'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para elimnar
  public function eliminar($nombre_tabla, $nombre_columna, $idtabla) {
    $sql = "UPDATE $nombre_tabla SET estado_delete='0' WHERE $nombre_columna ='$idtabla'";
    return ejecutarConsulta($sql);
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

  //--------------------------------R ES U E M E N _Q_S --------------------------------

  public function insertar_editar_resumen_q_s($array_val) {
    foreach (json_decode( $array_val, true) as $key => $value) {
      $idresumen_q_s_valorizacion   = $value['idresumen_q_s_valorizacion'];
      $idproyecto       = $value['idproyecto'];
      $numero_q_s       = $value['numero_q_s'];
      $fecha_inicial    = $value['fecha_inicio'];
      $fecha_final      = $value['fecha_fin'];
      $monto_programado = $value['monto_programado'];
      $monto_valorizado = $value['monto_valorizado'];

      $sql_1 = "SELECT * FROM resumen_q_s_valorizacion WHERE idproyecto='$idproyecto' AND numero_q_s='$numero_q_s'";
      $buscando=ejecutarConsultaArray($sql_1);
      if ($buscando['status'] == false) { return $buscando; }

      if ( empty($buscando['data']) ) {
        $sql_2 = "INSERT INTO resumen_q_s_valorizacion(idproyecto, numero_q_s, fecha_inicio, fecha_fin, monto_programado, monto_valorizado, monto_gastado) 
        VALUES ('$idproyecto','$numero_q_s','$fecha_inicial','$fecha_final','$monto_programado','$monto_valorizado','0')";
        $insertando = ejecutarConsulta($sql_2); 
        if ($insertando['status'] == false) { return $insertando; }
      } else {         
        $sql_3 = "UPDATE resumen_q_s_valorizacion SET idproyecto='$idproyecto', numero_q_s='$numero_q_s', fecha_inicio='$fecha_inicial',
        fecha_fin='$fecha_final', monto_programado='$monto_programado', monto_valorizado='$monto_valorizado', monto_gastado='0' 
        WHERE numero_q_s='$numero_q_s'";
        $editando =  ejecutarConsulta($sql_3); 
        if ($editando['status'] == false) { return $editando; }
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
      $val_q_s =  ejecutarConsultaSimpleFila($sql);
      if ($val_q_s['status'] == false) { return $val_q_s; }

      $monto_gastado = suma_totales($idproyecto, $value['fecha_inicio'],$value['fecha_fin']);
      $data[] = [
        'idresumen_q_s_valorizacion' => (empty($val_q_s['data']) ? '' : $val_q_s['data']['idresumen_q_s_valorizacion']),
        'idproyecto' => $idproyecto,
        'numero_q_s' => $value['num_q_s'],
        'fecha_inicio' => $value['fecha_inicio'],
        'fecha_fin' => $value['fecha_fin'],
        'monto_programado' => (empty($val_q_s['data']) ? 0 : (empty($val_q_s['data']['monto_programado']) ? 0 : floatval($val_q_s['data']['monto_programado']) ) ),
        'monto_valorizado' => (empty($val_q_s['data']) ? 0 : (empty($val_q_s['data']['monto_valorizado']) ? 0 : floatval($val_q_s['data']['monto_valorizado']) ) ),
        'monto_gastado' => $monto_gastado,
      ];
    }

    $sql_2 = "SELECT idproyecto,  nombre_codigo,  costo, fecha_inicio, fecha_fin, feriado_domingo,  fecha_valorizacion, permanente_pago_obrero
    FROM proyecto WHERE idproyecto='$idproyecto';";
    $proyecto =  ejecutarConsultaSimpleFila($sql_2);
    if ($proyecto['status'] == false) { return $proyecto; }
    
    return $retorno = [ 'status' => true, 'message' => 'todo oka', 'data' =>['montos' =>$data, 'proyecto' => (empty($proyecto['data']) ? 0 : (empty($proyecto['data']['costo']) ? 0 : floatval($proyecto['data']['costo']) ) )] ] ;  
  }

  public function list_total_montos_resumen_q_s($idproyecto_q_s) {
    $data_totales = []; $monto_gastado=0;
    $sql = "SELECT SUM(monto_programado) as m_programado,  SUM(monto_valorizado) as m_valorizado FROM resumen_q_s_valorizacion WHERE idproyecto='$idproyecto_q_s' AND estado=1 AND estado_delete=1;";
    $total_montos_r_q_s =  ejecutarConsultaSimpleFila($sql);
    if ($total_montos_r_q_s['status'] == false) { return $total_montos_r_q_s; }

    $sql = "SELECT * FROM resumen_q_s_valorizacion WHERE idproyecto='$idproyecto_q_s' AND estado=1 AND estado_delete=1;";
    $resumen_valorizacion =  ejecutarConsultaArray($sql);

    if ($resumen_valorizacion['status'] == false) { return $resumen_valorizacion; }

    foreach ($resumen_valorizacion['data'] as $key => $value1) {
      $monto_gastado += suma_totales($idproyecto_q_s,$value1['fecha_inicio'],$value1['fecha_fin']);
    }

    $data_totales = [
      'm_programado' => $total_montos_r_q_s['data']['m_programado'],
      'm_valorizado' => $total_montos_r_q_s['data']['m_valorizado'],
      'm_gastado' => $monto_gastado
    ];

    return $retorno = [ 'status' => true, 'message' => 'todo oka', 'data' => $data_totales] ; 

  }


}

function suma_totales($idproyecto, $fecha_1, $fecha_2) {

  $data = Array(); $total = 0; $subtotal = 0; $igv = 0;

  // SUMAS TOTALES - COMPRA INSUMO --------------------------------------------------------------------------------
  $filtro_fecha = "";

  if ( !empty($fecha_1) && !empty($fecha_2) ) {
    $filtro_fecha = "AND cpp.fecha_compra BETWEEN '$fecha_1' AND '$fecha_2'";
  } else {
    if (!empty($fecha_1)) {
      $filtro_fecha = "AND cpp.fecha_compra = '$fecha_1'";
    }else{
      if (!empty($fecha_2)) {
        $filtro_fecha = "AND cpp.fecha_compra = '$fecha_2'";
      }     
    }      
  }    

  $sql = "SELECT SUM(cpp.total) AS total, SUM(cpp.subtotal) AS subtotal, SUM(cpp.igv) AS igv
  FROM compra_por_proyecto AS cpp, proveedor p
  WHERE cpp.idproveedor = p.idproveedor AND cpp.estado = '1' AND cpp.estado_delete = '1'  AND  cpp.idproyecto = $idproyecto $filtro_fecha ;";
  $compra = ejecutarConsultaSimpleFila($sql);

  if ($compra['status'] == false) { return $compra; }

  $total    += (empty($compra['data'])) ? 0 : ( empty($compra['data']['total']) ? 0 : floatval($compra['data']['total']) );
  $subtotal += (empty($compra['data'])) ? 0 : ( empty($compra['data']['subtotal']) ? 0 : floatval($compra['data']['subtotal']) );
  $igv      += (empty($compra['data'])) ? 0 : ( empty($compra['data']['igv']) ? 0 : floatval($compra['data']['igv']) );

  // SUMAS TOTALES - COMPRAS DE ACTIVO FIJO --------------------------------------------------------------------------------
  // $filtro_fecha = "";

  // if ( !empty($fecha_1) && !empty($fecha_2) ) {
  //   $filtro_fecha = "AND cafg.fecha_compra BETWEEN '$fecha_1' AND '$fecha_2'";
  // } else {
  //   if (!empty($fecha_1)) {
  //     $filtro_fecha = "AND cafg.fecha_compra = '$fecha_1'";
  //   }else{
  //     if (!empty($fecha_2)) {
  //       $filtro_fecha = "AND cafg.fecha_compra = '$fecha_2'";
  //     }     
  //   }      
  // }    

  // $sql = "SELECT SUM(cafg.total) AS total, SUM(cafg.subtotal) AS subtotal, SUM(cafg.igv) AS igv
  // FROM compra_af_general  AS cafg, proveedor p
  // WHERE cafg.idproveedor = p.idproveedor AND cafg.estado = '1' AND cafg.estado_delete = '1' $filtro_fecha ;";
  // $compra = ejecutarConsultaSimpleFila($sql);

  // if ($compra['status'] == false) { return $compra; }

  // $total    += (empty($compra['data'])) ? 0 : ( empty($compra['data']['total']) ? 0 : floatval($compra['data']['total']) );
  // $subtotal += (empty($compra['data'])) ? 0 : ( empty($compra['data']['subtotal']) ? 0 : floatval($compra['data']['subtotal']) );
  // $igv      += (empty($compra['data'])) ? 0 : ( empty($compra['data']['igv']) ? 0 : floatval($compra['data']['igv']) );
    
  // SUMAS TOTALES - MAQUINARIA EQUIPO --------------------------------------------------------------------------------
  $filtro_fecha = "";

  if ( !empty($fecha_1) && !empty($fecha_2) ) {
    $filtro_fecha = "AND f.fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
  } else {
    if (!empty($fecha_1)) {
      $filtro_fecha = "AND f.fecha_emision = '$fecha_1'";
    }else{
      if (!empty($fecha_2)) {
        $filtro_fecha = "AND f.fecha_emision = '$fecha_2'";
      }     
    }      
  }    

  $sql2 = "SELECT SUM(f.monto) AS total , SUM(f.subtotal) AS subtotal, SUM(f.igv) AS igv
  FROM factura as f, proyecto as p, maquinaria as mq, proveedor as prov
  WHERE f.idmaquinaria=mq.idmaquinaria AND mq.idproveedor=prov.idproveedor AND f.idproyecto=p.idproyecto 
  AND f.estado = '1' AND f.estado_delete = '1'  AND f.idproyecto = $idproyecto $filtro_fecha;";
  $maquinaria = ejecutarConsultaSimpleFila($sql2);

  if ($maquinaria['status'] == false) { return $maquinaria; } 

  $total    += (empty($maquinaria['data'])) ? 0 : ( empty($maquinaria['data']['total']) ? 0 : floatval($maquinaria['data']['total']) );
  $subtotal += (empty($maquinaria['data'])) ? 0 : ( empty($maquinaria['data']['subtotal']) ? 0 : floatval($maquinaria['data']['subtotal']) );
  $igv      += (empty($maquinaria['data'])) ? 0 : ( empty($maquinaria['data']['igv']) ? 0 : floatval($maquinaria['data']['igv']) );

  // SUMAS TOTALES - SUB CONTRATO --------------------------------------------------------------------------------
  $filtro_fecha = "";

  if ( !empty($fecha_1) && !empty($fecha_2) ) {
    $filtro_fecha = "AND s.fecha_subcontrato BETWEEN '$fecha_1' AND '$fecha_2'";
  } else {
    if (!empty($fecha_1)) {
      $filtro_fecha = "AND s.fecha_subcontrato = '$fecha_1'";
    }else{
      if (!empty($fecha_2)) {
        $filtro_fecha = "AND s.fecha_subcontrato = '$fecha_2'";
      }     
    }      
  }    

  $sql3 = "SELECT SUM(s.subtotal) as subtotal, SUM(s.igv) as igv, SUM(s.costo_parcial) as total
  FROM subcontrato AS s, proveedor as p
  WHERE s.idproveedor = p.idproveedor and s.estado = '1' AND s.estado_delete = '1'  AND  idproyecto = $idproyecto $filtro_fecha;";
  $otro_gasto = ejecutarConsultaSimpleFila($sql3);

  if ($otro_gasto['status'] == false) { return $otro_gasto; } 
  
  $total    += (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['total']) ? 0 : floatval($otro_gasto['data']['total']) );
  $subtotal += (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['subtotal']) ? 0 : floatval($otro_gasto['data']['subtotal']) );
  $igv      += (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['igv']) ? 0 : floatval($otro_gasto['data']['igv']) );

  // SUMAS TOTALES - PLANILLA SEGURO --------------------------------------------------------------------------------

  $filtro_fecha = "";

  if ( !empty($fecha_1) && !empty($fecha_2) ) {
    $filtro_fecha = "AND ps.fecha_p_s BETWEEN '$fecha_1' AND '$fecha_2'";
  } else {
    if (!empty($fecha_1)) {
      $filtro_fecha = "AND ps.fecha_p_s = '$fecha_1'";
    }else{
      if (!empty($fecha_2)) {
        $filtro_fecha = "AND ps.fecha_p_s = '$fecha_2'";
      }     
    }      
  }    

  $sql3 = "SELECT SUM(ps.subtotal) AS subtotal, SUM(ps.igv) AS igv, SUM(ps.costo_parcial) AS total
  FROM planilla_seguro as ps, proyecto as p
  WHERE ps.idproyecto = p.idproyecto and ps.estado ='1' and ps.estado_delete = '1' 
    AND  ps.idproyecto = $idproyecto $filtro_fecha;";
  $otro_gasto = ejecutarConsultaSimpleFila($sql3);

  if ($otro_gasto['status'] == false) { return $otro_gasto; } 
  
  $total    += (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['total']) ? 0 : floatval($otro_gasto['data']['total']) );
  $subtotal += (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['subtotal']) ? 0 : floatval($otro_gasto['data']['subtotal']) );
  $igv      += (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['igv']) ? 0 : floatval($otro_gasto['data']['igv']) );

  // SUMAS TOTALES - OTRO GASTO --------------------------------------------------------------------------------
  $filtro_fecha = "";

  if ( !empty($fecha_1) && !empty($fecha_2) ) {
    $filtro_fecha = "AND fecha_g BETWEEN '$fecha_1' AND '$fecha_2'";
  } else {
    if (!empty($fecha_1)) {
      $filtro_fecha = "AND fecha_g = '$fecha_1'";
    }else{
      if (!empty($fecha_2)) {
        $filtro_fecha = "AND fecha_g = '$fecha_2'";
      }     
    }      
  }    

  $sql3 = "SELECT SUM(costo_parcial) as total, SUM(subtotal) AS subtotal, SUM(igv) AS igv
  FROM otro_gasto  
  WHERE estado = '1' AND estado_delete = '1'  AND  idproyecto = $idproyecto $filtro_fecha;";
  $otro_gasto = ejecutarConsultaSimpleFila($sql3);

  if ($otro_gasto['status'] == false) { return $otro_gasto; } 
  
  $total    += (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['total']) ? 0 : floatval($otro_gasto['data']['total']) );
  $subtotal += (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['subtotal']) ? 0 : floatval($otro_gasto['data']['subtotal']) );
  $igv      += (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['igv']) ? 0 : floatval($otro_gasto['data']['igv']) );

  // SUMAS TOTALES - TRASNPORTE --------------------------------------------------------------------------------
  $filtro_fecha = "";

  if ( !empty($fecha_1) && !empty($fecha_2) ) {
    $filtro_fecha = "AND t.fecha_viaje BETWEEN '$fecha_1' AND '$fecha_2'";
  } else {
    if (!empty($fecha_1)) {
      $filtro_fecha = "AND t.fecha_viaje = '$fecha_1'";
    }else{
      if (!empty($fecha_2)) {
        $filtro_fecha = "AND t.fecha_viaje = '$fecha_2'";
      }     
    }      
  }    

  $sql4 = "SELECT SUM(t.precio_parcial) AS total, SUM(t.subtotal) AS subtotal, SUM(t.igv) AS igv
  FROM transporte AS t, proveedor AS p
  WHERE t.idproveedor = p.idproveedor  AND t.estado = '1' AND t.estado_delete = '1' AND t.idproyecto = $idproyecto  $filtro_fecha;";
  $transporte = ejecutarConsultaSimpleFila($sql4);

  if ($transporte['status'] == false) { return $transporte; }
  
  $total    += (empty($transporte['data'])) ? 0 : ( empty($transporte['data']['total']) ? 0 : floatval($transporte['data']['total']) );
  $subtotal += (empty($transporte['data'])) ? 0 : ( empty($transporte['data']['subtotal']) ? 0 : floatval($transporte['data']['subtotal']) );
  $igv      += (empty($transporte['data'])) ? 0 : ( empty($transporte['data']['igv']) ? 0 : floatval($transporte['data']['igv']) );

  // SUMAS TOTALES - HOSPEDAJE --------------------------------------------------------------------------------
  $filtro_fecha = "";

  if ( !empty($fecha_1) && !empty($fecha_2) ) {
    $filtro_fecha = "AND fecha_comprobante BETWEEN '$fecha_1' AND '$fecha_2'";
  } else {
    if (!empty($fecha_1)) {
      $filtro_fecha = "AND fecha_comprobante = '$fecha_1'";
    }else{
      if (!empty($fecha_2)) {
        $filtro_fecha = "AND fecha_comprobante = '$fecha_2'";
      }     
    }      
  }    

  $sql5 = "SELECT SUM(precio_parcial) as total , SUM(subtotal) AS subtotal, SUM(igv) AS igv
  FROM hospedaje WHERE estado = '1' AND estado_delete = '1' AND idproyecto = $idproyecto  $filtro_fecha
  ORDER BY fecha_comprobante DESC;";
  $hospedaje = ejecutarConsultaSimpleFila($sql5);

  if ($hospedaje['status'] == false) { return $hospedaje; }
  
  $total    += (empty($hospedaje['data'])) ? 0 : ( empty($hospedaje['data']['total']) ? 0 : floatval($hospedaje['data']['total']) );
  $subtotal += (empty($hospedaje['data'])) ? 0 : ( empty($hospedaje['data']['subtotal']) ? 0 : floatval($hospedaje['data']['subtotal']) );
  $igv      += (empty($hospedaje['data'])) ? 0 : ( empty($hospedaje['data']['igv']) ? 0 : floatval($hospedaje['data']['igv']) );

  // SUMAS TOTALES - FACTURA PENSION --------------------------------------------------------------------------------
  $filtro_fecha = "";

  if ( !empty($fecha_1) && !empty($fecha_2) ) {
    $filtro_fecha = "AND fp.fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
  } else {
    if (!empty($fecha_1)) {
      $filtro_fecha = "AND fp.fecha_emision = '$fecha_1'";
    }else{
      if (!empty($fecha_2)) {
        $filtro_fecha = "AND fp.fecha_emision = '$fecha_2'";
      }     
    }      
  }    

  $sql6 = "SELECT SUM(fp.monto) AS total, SUM(fp.subtotal) AS subtotal, SUM(fp.igv) AS igv
  FROM factura_pension as fp, pension as p, proveedor as prov
  WHERE fp.idpension = p.idpension AND prov.idproveedor = p.idproveedor  AND p.estado = '1' AND p.estado_delete = '1' AND  p.idproyecto = $idproyecto
  AND fp.estado = '1' AND fp.estado_delete = '1' $filtro_fecha ;";
  $factura_pension = ejecutarConsultaSimpleFila($sql6);

  if ($factura_pension['status'] == false) { return $factura_pension; }
  
  $total    += (empty($factura_pension['data'])) ? 0 : ( empty($factura_pension['data']['total']) ? 0 : floatval($factura_pension['data']['total']) );
  $subtotal += (empty($factura_pension['data'])) ? 0 : ( empty($factura_pension['data']['subtotal']) ? 0 : floatval($factura_pension['data']['subtotal']) );
  $igv      += (empty($factura_pension['data'])) ? 0 : ( empty($factura_pension['data']['igv']) ? 0 : floatval($factura_pension['data']['igv']) );

  // SUMAS TOTALES - FACTURA BREACK --------------------------------------------------------------------------------
  $filtro_fecha = "";

  if ( !empty($fecha_1) && !empty($fecha_2) ) {
    $filtro_fecha = "AND fb.fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
  } else {
    if (!empty($fecha_1)) {
      $filtro_fecha = "AND fb.fecha_emision = '$fecha_1'";
    }else{
      if (!empty($fecha_2)) {
        $filtro_fecha = "AND fb.fecha_emision = '$fecha_2'";
      }     
    }      
  }    

  $sql7 = "SELECT SUM(fb.monto) AS total, SUM(fb.subtotal) AS subtotal, SUM(fb.igv) AS igv
  FROM factura_break as fb, semana_break as sb
  WHERE  fb.idsemana_break = sb.idsemana_break AND fb.estado = '1' AND fb.estado_delete = '1' AND sb.estado = '1'  AND  sb.idproyecto = $idproyecto
  AND sb.estado_delete = '1' $filtro_fecha ;";
  $factura_break = ejecutarConsultaSimpleFila($sql7);

  if ($factura_break['status'] == false) { return $factura_break; }
  
  $total    += (empty($factura_break['data'])) ? 0 : ( empty($factura_break['data']['total']) ? 0 : floatval($factura_break['data']['total']) );
  $subtotal += (empty($factura_break['data'])) ? 0 : ( empty($factura_break['data']['subtotal']) ? 0 : floatval($factura_break['data']['subtotal']) );
  $igv      += (empty($factura_break['data'])) ? 0 : ( empty($factura_break['data']['igv']) ? 0 : floatval($factura_break['data']['igv']) );

  // SUMAS TOTALES - COMIDA EXTRA --------------------------------------------------------------------------------
  $filtro_fecha = "";

  if ( !empty($fecha_1) && !empty($fecha_2) ) {
    $filtro_fecha = "AND fecha_comida BETWEEN '$fecha_1' AND '$fecha_2'";
  } else {
    if (!empty($fecha_1)) {
      $filtro_fecha = "AND fecha_comida = '$fecha_1'";
    }else{
      if (!empty($fecha_2)) {
        $filtro_fecha = "AND fecha_comida = '$fecha_2'";
      }     
    }      
  }    

  $sql8 = "SELECT SUM(costo_parcial) AS total, SUM(subtotal) AS subtotal, SUM(igv) AS igv
  FROM comida_extra
  WHERE  estado = '1' AND estado_delete = '1' AND  idproyecto = $idproyecto $filtro_fecha;";
  $comida_extra = ejecutarConsultaSimpleFila($sql8);

  if ($comida_extra['status'] == false) { return $comida_extra; }
  
  $total    += (empty($comida_extra['data'])) ? 0 : ( empty($comida_extra['data']['total']) ? 0 : floatval($comida_extra['data']['total']) );
  $subtotal += (empty($comida_extra['data'])) ? 0 : ( empty($comida_extra['data']['subtotal']) ? 0 : floatval($comida_extra['data']['subtotal']) );
  $igv      += (empty($comida_extra['data'])) ? 0 : ( empty($comida_extra['data']['igv']) ? 0 : floatval($comida_extra['data']['igv']) );

  // SUMAS TOTALES - OTRO INGRESO --------------------------------------------------------------------------------

  // $filtro_fecha = "";

  // if ( !empty($fecha_1) && !empty($fecha_2) ) {
  //   $filtro_fecha = "AND oi.fecha_i BETWEEN '$fecha_1' AND '$fecha_2'";
  // } else {
  //   if (!empty($fecha_1)) {
  //     $filtro_fecha = "AND oi.fecha_i = '$fecha_1'";
  //   }else{
  //     if (!empty($fecha_2)) {
  //       $filtro_fecha = "AND oi.fecha_i = '$fecha_2'";
  //     }     
  //   }      
  // }    

  // $sql9 = "SELECT SUM(oi.subtotal) as subtotal, SUM(oi.igv) as igv, SUM(oi.costo_parcial) as total
  // FROM otro_ingreso as oi, proyecto as p
  // WHERE oi.idproyecto = p.idproyecto AND oi.estado = '1' AND oi.estado_delete = '1' AND  oi.idproyecto = $idproyecto $filtro_fecha";
  // $otra_factura = ejecutarConsultaSimpleFila($sql9);

  // if ($otra_factura['status'] == false) { return $otra_factura; } 
  
  // $total    += (empty($otra_factura['data'])) ? 0 : ( empty($otra_factura['data']['total']) ? 0 : floatval($otra_factura['data']['total']) );
  // $subtotal += (empty($otra_factura['data'])) ? 0 : ( empty($otra_factura['data']['subtotal']) ? 0 : floatval($otra_factura['data']['subtotal']) );
  // $igv      += (empty($otra_factura['data'])) ? 0 : ( empty($otra_factura['data']['igv']) ? 0 : floatval($otra_factura['data']['igv']) );

  // SUMAS TOTALES - OTRA FACTURA --------------------------------------------------------------------------------
  // $filtro_fecha = "";

  // if ( !empty($fecha_1) && !empty($fecha_2) ) {
  //   $filtro_fecha = "AND of.fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
  // } else {
  //   if (!empty($fecha_1)) {
  //     $filtro_fecha = "AND of.fecha_emision = '$fecha_1'";
  //   }else{
  //     if (!empty($fecha_2)) {
  //       $filtro_fecha = "AND of.fecha_emision = '$fecha_2'";
  //     }     
  //   }      
  // }    

  // $sql9 = "SELECT SUM(of.costo_parcial) AS total, SUM(of.subtotal) AS subtotal, SUM(of.igv) AS igv
  // FROM otra_factura AS of, proveedor p
  // WHERE of.idproveedor = p.idproveedor AND of.estado = '1' AND of.estado_delete = '1' $filtro_fecha";
  // $otra_factura = ejecutarConsultaSimpleFila($sql9);

  // if ($otra_factura['status'] == false) { return $otra_factura; } 
  
  // $total    += (empty($otra_factura['data'])) ? 0 : ( empty($otra_factura['data']['total']) ? 0 : floatval($otra_factura['data']['total']) );
  // $subtotal += (empty($otra_factura['data'])) ? 0 : ( empty($otra_factura['data']['subtotal']) ? 0 : floatval($otra_factura['data']['subtotal']) );
  // $igv      += (empty($otra_factura['data'])) ? 0 : ( empty($otra_factura['data']['igv']) ? 0 : floatval($otra_factura['data']['igv']) );


  $data = array( 
    "status"=> true,
    "message"=> 'todo oka',
    "data"=> [
      "total" => $total, 
      "subtotal" => $subtotal, 
      "igv" => $igv,  
    ]      
  );

  return $total ;
}  
?>
