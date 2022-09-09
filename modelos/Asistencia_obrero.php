<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Asistencia_obrero
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  //Implementamos un método para insertar registros
  public function insertar_asistencia_y_resumen_q_s_asistencia($asistencia, $resumen_qs, $fecha_i, $fecha_f) {
    $data_asistencia = json_decode($asistencia, true);
    $data_resumen_qs = json_decode($resumen_qs, true);
    $pruebas = "";
    $sw = true;

    $buscar_asistencia = "";
    $buscar_extras = "";

    // registramos o editamos las "asistencias de cada trabajador"
    foreach ($data_asistencia as $indice => $key) {
      // $pruebas = $key['fecha_asistida'];
      // buscamos la existencia de una asistencia
      $sql_1 = "SELECT idasistencia_trabajador FROM asistencia_trabajador WHERE idtrabajador_por_proyecto='" . $key['id_trabajador'] . "' AND fecha_asistencia = '" . $key['fecha_asistida'] . "';";
      $buscar_asistencia = ejecutarConsultaSimpleFila($sql_1);
      if ($buscar_asistencia['status'] == false) {  return $buscar_asistencia; }

      if (empty($buscar_asistencia['data'])) {
        // insertamos un nuevo registro
        $sql_2 = "INSERT INTO asistencia_trabajador (idtrabajador_por_proyecto, horas_normal_dia, pago_normal_dia, horas_extras_dia, pago_horas_extras, fecha_asistencia, nombre_dia, user_created)			
				VALUES ('" . $key['id_trabajador'] . "', '" . $key['horas_normal_dia'] . "', '" . $key['pago_normal_dia'] . "', '" .
        $key['horas_extras_dia'] . "', '" . $key['pago_horas_extras'] . "', '" . $key['fecha_asistida'] . "', '" . $key['nombre_dia'] . "', '".$_SESSION['idusuario']."' )";
        $new_registro = ejecutarConsulta_retornarID($sql_2);
        if ($new_registro['status'] == false) {  return $new_registro; }

        //B I T A C O R A -------
        $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('asistencia_trabajador', '".$new_registro['data']."', 'Crear registro', '".$_SESSION['idusuario']."')";
        $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }

      } else {
        # editamos el registro existente
        $sql_3 =  "UPDATE asistencia_trabajador SET idtrabajador_por_proyecto='" . $key['id_trabajador'] . "', horas_normal_dia='" . $key['horas_normal_dia'] .
          "', pago_normal_dia='" . $key['pago_normal_dia'] . "', horas_extras_dia='" . $key['horas_extras_dia'] .
          "', pago_horas_extras='" . $key['pago_horas_extras'] . "', fecha_asistencia = '" . $key['fecha_asistida'] .
          "', nombre_dia = '" . $key['nombre_dia'] . "', user_updated='".$_SESSION['idusuario']."' WHERE idasistencia_trabajador='" . $buscar_asistencia['data']['idasistencia_trabajador'] . "';";
        $edita_registro = ejecutarConsulta($sql_3);
        if ($edita_registro['status'] == false) {  return $edita_registro; }

        //B I T A C O R A -------
        $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('asistencia_trabajador', '".$buscar_asistencia['data']['idasistencia_trabajador']."', 'Editar registro', '".$_SESSION['idusuario']."')";
        $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }
      }
    }

    $retorno = "";
    // registramos o editamos las "resumen q s asistencia"
    foreach ($data_resumen_qs as $indice => $keys) {
      $sql_4 = "SELECT idresumen_q_s_asistencia FROM resumen_q_s_asistencia 
      WHERE idtrabajador_por_proyecto = '" . $keys['id_trabajador'] . "' AND fecha_q_s_inicio = '" . $keys['fecha_q_s_inicio'] . "';";
      $buscar_extras = ejecutarConsultaSimpleFila($sql_4);
      if ($buscar_extras['status'] == false) {  return $buscar_extras; }

      if (empty($buscar_extras['data'])) {
        # insertamos un nuevo registro
        $sql_5 = "INSERT INTO resumen_q_s_asistencia(idtrabajador_por_proyecto, numero_q_s, fecha_q_s_inicio, fecha_q_s_fin, total_hn, total_he, total_dias_asistidos, sabatical, pago_parcial_hn, pago_parcial_he, adicional_descuento, pago_quincenal, user_created) 
				VALUES ('" . $keys['id_trabajador'] . "', '" . $keys['num_semana'] . "', '" . $keys['fecha_q_s_inicio'] . "', '" . $keys['fecha_q_s_fin'] .
        "', '" . $keys['total_hn'] . "', '" . $keys['total_he'] . "', '" . $keys['dias_asistidos'] . "', '" . $keys['sabatical'] . "', '" .
        $keys['pago_parcial_hn'] . "', '" . $keys['pago_parcial_he'] . "', '" . $keys['adicional_descuento'] . "', '" . $keys['pago_quincenal'] . "', '".$_SESSION['idusuario']."')";
        $retorno =  ejecutarConsulta_retornarID($sql_5);
        if ($retorno['status'] == false) {  return $retorno; }

        //B I T A C O R A -------
        $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('asistencia_trabajador', '".$retorno['data']."', 'Crear registro', '".$_SESSION['idusuario']."')";
        $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }

      } else {
        # editamos el registro encontrado
        $sql_6 = "UPDATE resumen_q_s_asistencia SET  idtrabajador_por_proyecto='" . $keys['id_trabajador'] . "', numero_q_s='" . $keys['num_semana'] .
        "', fecha_q_s_inicio='" .  $keys['fecha_q_s_inicio'] . "', fecha_q_s_fin='" . $keys['fecha_q_s_fin'] . "', total_hn='" . $keys['total_hn'] .
        "', total_he='" . $keys['total_he'] . "', total_dias_asistidos='" . $keys['dias_asistidos'] . "', sabatical='" . $keys['sabatical'] .
        "', pago_parcial_hn='" . $keys['pago_parcial_hn'] . "', pago_parcial_he='" . $keys['pago_parcial_he'] . "', 
        adicional_descuento='" . $keys['adicional_descuento'] . "', pago_quincenal='" . $keys['pago_quincenal'] ."', user_updated='".$_SESSION['idusuario']."' 
        WHERE idresumen_q_s_asistencia = '" . $buscar_extras['data']['idresumen_q_s_asistencia'] . "';";
        $retorno = ejecutarConsulta($sql_6);
        if ($retorno['status'] == false) {  return $retorno; }

        //B I T A C O R A -------
        $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('asistencia_trabajador', '".$buscar_extras['data']['idresumen_q_s_asistencia']."', 'Editar registro', '".$_SESSION['idusuario']."')";
        $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }
      }
    }
    return $retorno;
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idasistencia_trabajador) {
    $sql = "SELECT tp.idtrabajador_por_proyecto, t.nombres , t.tipo_documento as documento, t.numero_documento, tp.cargo, t.imagen_perfil, atr.fecha_asistencia, atr.horas_normal_dia, atr.horas_extras_dia 
		FROM trabajador AS t, trabajador_por_proyecto AS tp, asistencia_trabajador AS atr 
		WHERE t.idtrabajador = tp.idtrabajador AND tp.idtrabajador_por_proyecto = atr.idtrabajador_por_proyecto AND atr.idasistencia_trabajador = '$idasistencia_trabajador';";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para listar asistencia
  public function tbla_principal($nube_idproyecto) {
    $data_array = Array();
    $sql = "SELECT atr.idtrabajador_por_proyecto, t.idtrabajador AS idtrabajador, t.nombres AS nombre, t.tipo_documento as tipo_doc, 
		t.numero_documento AS num_doc, t.imagen_perfil AS imagen, tpp.sueldo_hora, tpp.sueldo_mensual, tpp.sueldo_diario,
		SUM(atr.horas_normal_dia) AS total_horas_normal, SUM(atr.horas_extras_dia) AS total_horas_extras, 
		atr.estado as estado, p.fecha_inicio AS fecha_inicio_proyect, c.nombre AS cargo
		FROM trabajador AS t, trabajador_por_proyecto AS tpp, cargo_trabajador AS c, asistencia_trabajador AS atr,  proyecto AS p
		WHERE t.idtrabajador = tpp.idtrabajador AND tpp.idtrabajador_por_proyecto = atr.idtrabajador_por_proyecto 
		AND tpp.idproyecto = p.idproyecto AND tpp.idcargo_trabajador = c.idcargo_trabajador AND atr.estado = '1' AND atr.estado_delete = '1' 
		AND tpp.idproyecto = '$nube_idproyecto' 
		GROUP BY tpp.idtrabajador_por_proyecto ORDER BY t.nombres ASC;";
    $agrupar_trabajdor = ejecutarConsultaArray($sql);

    if ($agrupar_trabajdor['status'] == false) {  return $agrupar_trabajdor; }

    foreach ($agrupar_trabajdor['data'] as $key => $value) {
      $sql_2 = "SELECT SUM(adicional_descuento) AS adicional_descuento, SUM(sabatical) AS total_sabatical, SUM(total_hn) AS total_hn, SUM(total_he) AS total_he, 
			SUM(total_dias_asistidos) AS total_dias_asistidos, SUM(pago_quincenal) AS pago_quincenal
			FROM resumen_q_s_asistencia 
			WHERE  estado = '1' AND estado_delete = '1' AND idtrabajador_por_proyecto = '" . $value['idtrabajador_por_proyecto'] . "';";
      $sab = ejecutarConsultaSimpleFila($sql_2);
      if ($sab['status'] == false) {  return $sab; }

      $data_array[] = [
        'idtrabajador_por_proyecto' => $value['idtrabajador_por_proyecto'],
        'idtrabajador' => $value['idtrabajador'],
        'nombre' => $value['nombre'],
        'tipo_doc' => $value['tipo_doc'],
        'num_doc' => $value['num_doc'],
        'imagen' => $value['imagen'],
        'sueldo_hora' => $value['sueldo_hora'],
        'sueldo_diario' => $value['sueldo_diario'],
        'sueldo_mensual' => $value['sueldo_mensual'],
        'total_horas_normal' => empty($sab['data']) ? 0 : (empty($sab['data']['total_hn']) ? 0 : floatval($sab['data']['total_hn'])),
        'total_horas_extras' => empty($sab['data']) ? 0 : (empty($sab['data']['total_he']) ? 0 : floatval($sab['data']['total_he'])),
        'estado' => $value['estado'],
        'fecha_inicio_proyect' => $value['fecha_inicio_proyect'],
        'cargo' => $value['cargo'],
        'total_sabatical' => empty($sab['data']) ? 0 : (empty($sab['data']['total_sabatical']) ? 0 : floatval($sab['data']['total_sabatical'])),
        'pago_quincenal' => empty($sab['data']) ? 0 : (empty($sab['data']['pago_quincenal']) ? 0 : floatval($sab['data']['pago_quincenal'])),
        'adicional_descuento' => empty($sab['data']) ? 0 : (empty($sab['data']['adicional_descuento']) ? 0 : floatval($sab['data']['adicional_descuento'])),
      ];       
    }

    return $retorno = ['status' => true, 'message' => 'todo oka ps', 'data' => $data_array, ];
  }

  public function total_acumulado_trabajadores($id_proyecto) {
    $sql = "SELECT SUM(rqsa.pago_quincenal) AS pago_quincenal
		FROM resumen_q_s_asistencia AS rqsa, trabajador_por_proyecto AS tpp, proyecto AS p
		WHERE rqsa.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto AND tpp.idproyecto = p.idproyecto AND rqsa.estado = '1' 
		AND rqsa.estado_delete = '1' AND tpp.idproyecto = '$id_proyecto'; ";
    return ejecutarConsultaSimpleFila($sql);
  }

  //listar botones de la quincena o semana
  public function listarquincenas_botones($nube_idproyecto) {
    $sql = "SELECT p.idproyecto, p.fecha_inicio_actividad AS fecha_inicio, p.fecha_fin_actividad AS fecha_fin, p.plazo_actividad AS plazo, 
		p.fecha_pago_obrero, p.fecha_valorizacion 
		FROM proyecto as p WHERE p.idproyecto='$nube_idproyecto'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //ver detalle quincena
  public function ver_detalle_quincena($f1, $f2, $nube_idproyect) {
    // sql por siacaso - luego lo borro si no lo nescito
    // $sql="SELECT t.idtrabajador as idtrabajador, t.nombres as nombres, t.tipo_documento as tipo_doc, t.numero_documento as num_doc, tpp.cargo as cargo , t.imagen_perfil as imagen_perfil, tpp.sueldo_hora as sueldo_hora, tpp.sueldo_diario as sueldo_diario, tpp.sueldo_mensual as sueldo_mensual, SUM(atr.horas_normal_dia) as horas_normal_dia, SUM(atr.horas_extras_dia) as horas_extras_dia, SUM(atr.sabatical) as total_sabatical, atr.estado as estado, p.fecha_inicio as fecha_inicio_proyect FROM asistencia_trabajador as atr, trabajador_por_proyecto AS tpp, trabajador as t, proyecto as p
    // WHERE atr.idtrabajador_por_proyecto=tpp.idtrabajador_por_proyecto AND tpp.estado=1 AND tpp.idproyecto='$nube_idproyect' AND tpp.idproyecto=p.idproyecto
    // AND atr.fecha_asistencia BETWEEN '$f1' AND '$f2'
    // GROUP BY atr.idtrabajador_por_proyecto;";

    // extraemos todos lo trabajadores del proyecto
    $sql2 = "SELECT tpp.idtrabajador_por_proyecto, ct.nombre as cargo, tp.nombre as tipo_trabajador, t.nombres, t.tipo_documento, 
    t.numero_documento, tpp.sueldo_mensual, tpp.sueldo_diario, tpp.sueldo_hora, tpp.estado
		FROM trabajador_por_proyecto AS tpp, trabajador AS t, tipo_trabajador AS tp, cargo_trabajador AS ct
		WHERE tpp.idtrabajador = t.idtrabajador  AND ct.idcargo_trabajador = tpp.idcargo_trabajador AND ct.idtipo_trabjador = tp.idtipo_trabajador 
		AND  tpp.idproyecto = '$nube_idproyect' AND tp.nombre ='Obrero' ORDER BY t.nombres ASC ;";
    $trabajador = ejecutarConsultaArray($sql2);
    if ($trabajador['status'] == false) {  return $trabajador; }

    $data = [];
    $extras = "";

    $idresumen_q_s_asistencia = "";
    $fecha_registro = "";
    $total_hn = "";
    $total_he = "";
    $total_dias_asistidos = "";
    $sabatical = "";
    $sabatical_manual_1 = "";
    $sabatical_manual_2 = "";
    $pago_parcial_hn = "";
    $pago_parcial_he = "";
    $adicional_descuento = "";
    $descripcion_descuento = "";
    $pago_quincenal = "";
    $estado_envio_contador = "";

    foreach ($trabajador['data'] as $indice => $key) {
      $id_trabajador_proyect = $key['idtrabajador_por_proyecto'];

      // extraemos la asistencia por trabajador
      $sql3 = "SELECT * FROM asistencia_trabajador  AS atr 
			WHERE atr.idtrabajador_por_proyecto = '$id_trabajador_proyect' AND atr.fecha_asistencia BETWEEN '$f1' AND '$f2';";
      $asistencia = ejecutarConsultaArray($sql3);
      if ($asistencia['status'] == false) {  return $asistencia; }

      $sql4 = "SELECT idresumen_q_s_asistencia, idtrabajador_por_proyecto, fecha_q_s_inicio, total_hn, total_he, total_dias_asistidos, sabatical, sabatical_manual_1, sabatical_manual_2, pago_parcial_hn, pago_parcial_he, adicional_descuento, descripcion_descuento, pago_quincenal, estado_envio_contador 
			FROM resumen_q_s_asistencia WHERE idtrabajador_por_proyecto = '$id_trabajador_proyect' AND fecha_q_s_inicio = '$f1';";
      $extras = ejecutarConsultaSimpleFila($sql4);
      if ($extras['status'] == false) {  return $extras; }

      if (empty($extras['data'])) {
        $idresumen_q_s_asistencia = "";
        $fecha_q_s_inicio = "";
        $total_hn = 0;
        $total_he = 0;
        $total_dias_asistidos = 0;
        $sabatical = 0;
        $sabatical_manual_1 = "-";
        $sabatical_manual_2 = "-";
        $pago_parcial_hn = 0;
        $pago_parcial_he = 0;
        $adicional_descuento = 0;
        $descripcion_descuento = "";
        $pago_quincenal = 0;
        $estado_envio_contador = "";
      } else {
        $idresumen_q_s_asistencia = $extras['data']['idresumen_q_s_asistencia'];
        $fecha_q_s_inicio = $extras['data']['fecha_q_s_inicio'];
        $total_hn = $extras['data']['total_hn'];
        $total_he = $extras['data']['total_he'];
        $total_dias_asistidos = $extras['data']['total_dias_asistidos'];
        $sabatical = $extras['data']['sabatical'];
        $sabatical_manual_1 = $extras['data']['sabatical_manual_1'];
        $sabatical_manual_2 = $extras['data']['sabatical_manual_2'];
        $pago_parcial_hn = $extras['data']['pago_parcial_hn'];
        $pago_parcial_he = $extras['data']['pago_parcial_he'];
        $adicional_descuento = $extras['data']['adicional_descuento'];
        $descripcion_descuento = $extras['data']['descripcion_descuento'];
        $pago_quincenal = $extras['data']['pago_quincenal'];
        $estado_envio_contador = $extras['data']['estado_envio_contador'];
      }

      $data[] = [
        "idtrabajador_por_proyecto" => $key['idtrabajador_por_proyecto'],
        "cargo" => $key['cargo'],
        "tipo_trabajador" => $key['tipo_trabajador'],
        "nombres" => $key['nombres'],
        "tipo_documento" => $key['tipo_documento'],
        "numero_documento" => $key['numero_documento'],
        "sueldo_mensual" => $key['sueldo_mensual'],
        "sueldo_diario" => $key['sueldo_diario'],
        "sueldo_hora" => $key['sueldo_hora'],
        "estado_trabajador" => $key['estado'],
        "asistencia" => $asistencia['data'],

        'idresumen_q_s_asistencia' => $idresumen_q_s_asistencia,
        'fecha_registro' => $fecha_q_s_inicio,
        'total_hn' => $total_hn,
        'total_he' => $total_he,
        'total_dias_asistidos' => $total_dias_asistidos,
        'sabatical' => $sabatical,
        'sabatical_manual_1' => $sabatical_manual_1,
        'sabatical_manual_2' => $sabatical_manual_2,
        'pago_parcial_hn' => $pago_parcial_hn,
        'pago_parcial_he' => $pago_parcial_he,
        'adicional_descuento' => $adicional_descuento,
        'descripcion_descuento' => $descripcion_descuento,
        'pago_quincenal' => $pago_quincenal,
        'estado_envio_contador' => $estado_envio_contador,
      ];

      $idresumen_q_s_asistencia = "";
      $fecha_registro = "";
      $total_hn = "";
      $total_he = "";
      $total_dias_asistidos = "";
      $sabatical = "";
      $sabatical_manual_1 = "-";
      $sabatical_manual_2 = "-";
      $pago_parcial_hn = "";
      $pago_parcial_he = "";
      $adicional_descuento = "";
      $descripcion_descuento = "";
      $pago_quincenal = "";
    }

    return $retorno = ['status' => true, 'message' => 'todo oka ps', 'data' => $data];
  }

  // :::::::::::::::::::::::::::::::::::: S E C C I O N   P A G O   C O N T A D O R  ::::::::::::::::::::::::::::::::::::::

  public function quitar_editar_pago_al_contador($idresumen_q_s_asistencia, $estado_envio_contador) {
    $sql = "UPDATE resumen_q_s_asistencia SET estado_envio_contador= '$estado_envio_contador', user_updated = '".$_SESSION['idusuario']."'
		WHERE idresumen_q_s_asistencia = '$idresumen_q_s_asistencia';";
    $update_pago = ejecutarConsulta($sql);
    if ( $update_pago['status'] == false) {return $update_pago; }

    $accion = ($estado_envio_contador == '1' ? 'Envio pago contador' : 'Anular envio pago contador') ;
    //B I T A C O R A -------
    $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('resumen_q_s_asistencia', '".$idresumen_q_s_asistencia."', '$accion', '".$_SESSION['idusuario']."')";
    $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }

    return $update_pago;
  }

  public function quitar_editar_pago_al_contador_todos($array_pago_contador, $estado_envio_contador) {
    //$data_conta = json_decode($array_pago_contador, true);
    $sw = "";

    foreach ($array_pago_contador as $key => $value) { 

      $idresumen_q_s_asistencia = $value['idresumen_q_s_asistencia'];

      $sql = "UPDATE resumen_q_s_asistencia SET estado_envio_contador= '$estado_envio_contador', user_updated = '".$_SESSION['idusuario']."'
      WHERE idresumen_q_s_asistencia = '$idresumen_q_s_asistencia';";
      $sw = ejecutarConsulta($sql);
      if ($sw['status'] == false) {  return $sw; }

      $accion = ($estado_envio_contador == '1' ? 'Envio pago contador - Multiple' : 'Anular envio pago contador - Multiple') ;
      //B I T A C O R A -------
      $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('resumen_q_s_asistencia', '".$idresumen_q_s_asistencia."', '$accion', '".$_SESSION['idusuario']."')";
      $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }
    }
    
    return $retorno = ['status' => true, 'message' => 'todo oka ps', 'data' => $sw];
  }

  // :::::::::::::::::::::::::::::::::::: S E C C I O N   S A B A T I C A L  ::::::::::::::::::::::::::::::::::::::

  public function insertar_quitar_editar_sabatical_manual($idresumen_q_s_asistencia, $fecha_asistida, $sueldo_x_hora, $fecha_q_s_inicio, $fecha_q_s_fin, $numero_q_s, $id_trabajador_x_proyecto, $numero_sabado, $estado_sabatical_manual) {
    $horas = 0; $pago_normal = 0;
    $sabatical = 0; $total_dias = 0; $total_hn = 0; $pago_parcial_hn = 0; $pago_quincenal = 0;

    // buscamos la: ASISTENCIA
    $sql_1 = "SELECT atr.idasistencia_trabajador FROM asistencia_trabajador AS atr WHERE atr.idtrabajador_por_proyecto = '$id_trabajador_x_proyecto' AND atr.fecha_asistencia = '$fecha_asistida';";
    $buscando_asist = ejecutarConsultaSimpleFila($sql_1);
    if ($buscando_asist['status'] == false) {  return $buscando_asist; }

    if ($estado_sabatical_manual == '1') {
      $horas = 8;
      $pago_normal = floatval($sueldo_x_hora) * 8;
    } else {
      $horas = 0;
      $pago_normal = 0;
    }

    // validamos la insercion en: ASISTENCIA TRABAJDOR
    if (empty($buscando_asist['data'])) {
      $sql_2 = "INSERT INTO asistencia_trabajador(idtrabajador_por_proyecto, horas_normal_dia, pago_normal_dia, horas_extras_dia, pago_horas_extras, fecha_asistencia, nombre_dia, user_created) 
			VALUES ('$id_trabajador_x_proyecto','$horas','$pago_normal','0', '0', '$fecha_asistida', 'Sábado', '".$_SESSION['idusuario']."')";
      $insert_asistencia = ejecutarConsulta_retornarID($sql_2) ;
      if ($insert_asistencia['status'] == false) {  return $insert_asistencia; }

      //B I T A C O R A -------
      $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('asistencia_trabajador', '".$insert_asistencia['data']."', 'Crear registro', '".$_SESSION['idusuario']."')";
      $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }
    } else {
      $sql_3 = "UPDATE asistencia_trabajador SET idtrabajador_por_proyecto = '$id_trabajador_x_proyecto', horas_normal_dia = '$horas', 
			pago_normal_dia = '$pago_normal', horas_extras_dia  = '0', pago_horas_extras = '0', fecha_asistencia = '$fecha_asistida', nombre_dia = 'Sábado'
			WHERE idasistencia_trabajador = '" . $buscando_asist['data']['idasistencia_trabajador'] . "';";
      $update_asistencia = ejecutarConsulta($sql_3);
      if ($update_asistencia['status'] == false) {  return $update_asistencia; }

      //B I T A C O R A -------
      $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('asistencia_trabajador', '".$buscando_asist['data']['idasistencia_trabajador']."', 'Editar registro', '".$_SESSION['idusuario']."')";
      $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }
    }

     
    // validamos la insercion en el: RESUMEN Q S ASISTENCIA
    if (empty($idresumen_q_s_asistencia)) {
      $sql_4 = "INSERT INTO resumen_q_s_asistencia( idtrabajador_por_proyecto, numero_q_s, fecha_q_s_inicio, fecha_q_s_fin, sabatical,  sabatical_manual_$numero_sabado, total_dias_asistidos, total_hn, pago_parcial_hn, pago_quincenal, user_created) 
      VALUES ('$id_trabajador_x_proyecto', '$numero_q_s', '$fecha_q_s_inicio', '$fecha_q_s_fin', '1',  '$estado_sabatical_manual', '1', '8', $pago_normal, $pago_normal, '".$_SESSION['idusuario']."' );";
      $insert_resumen = ejecutarConsulta_retornarID($sql_4);
      if ( $insert_resumen['status'] == false) {return $insert_resumen; }

      $accion = ($estado_sabatical_manual == '1' ? 'Agregar' : 'Quitar' );
      //B I T A C O R A -------
      $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('resumen_q_s_asistencia', '".$insert_resumen['data']."', '$accion sabatical $numero_sabado manual', '".$_SESSION['idusuario']."')";
      $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }
      
      return $insert_resumen;

    } else {
      $sql_5 = "SELECT sabatical, total_dias_asistidos, total_hn, pago_parcial_hn, pago_quincenal FROM resumen_q_s_asistencia WHERE idresumen_q_s_asistencia = '$idresumen_q_s_asistencia';";
      $cant_sab = ejecutarConsultaSimpleFila($sql_5);
      if ($cant_sab['status'] == false) {  return $cant_sab; }

      if (!empty($cant_sab['data'])) {        
        $sabatical        = empty($cant_sab['data']['sabatical']) ? 0 : floatval($cant_sab['data']['sabatical']);
        $total_dias       = empty($cant_sab['data']['total_dias_asistidos']) ? 0 : floatval($cant_sab['data']['total_dias_asistidos']); 
        $total_hn         = empty($cant_sab['data']['total_hn']) ? 0 : floatval($cant_sab['data']['total_hn']); 
        $pago_parcial_hn  = empty($cant_sab['data']['pago_parcial_hn']) ? 0 : floatval($cant_sab['data']['pago_parcial_hn']); 
        $pago_quincenal   = empty($cant_sab['data']['pago_quincenal']) ? 0 : floatval($cant_sab['data']['pago_quincenal']);
      }

      if ($estado_sabatical_manual == '1') {
        $horas = 8;
        $pago_normal = floatval($sueldo_x_hora) * 8;
      } else {
        $horas = 0;
        $pago_normal = 0;
      }      

      if ($sabatical == 0) {
        $sabatical       = 1;
        $total_dias     += 1;
        $total_hn       += 8 ;
        $pago_parcial_hn+= floatval($sueldo_x_hora) * 8;
        $pago_quincenal += floatval($sueldo_x_hora) * 8;
      } else {
        if ($sabatical == 1) {           
          if ( $estado_sabatical_manual == '1') {
            $sabatical       = 2 ;
            $total_dias     += 1;
            $total_hn       += 8;
            $pago_parcial_hn+= floatval($sueldo_x_hora) * 8;
            $pago_quincenal += floatval($sueldo_x_hora) * 8;
          } else {
            $sabatical       = 0;
            $total_dias     -= 1;
            $total_hn       -= 8;
            $pago_parcial_hn-= floatval($sueldo_x_hora) * 8;
            $pago_quincenal -= floatval($sueldo_x_hora) * 8;
          }          
        } else {
          if ($sabatical == 2) {
            $sabatical       = 1;
            $total_dias     -= 1;
            $total_hn       -= 8;
            $pago_parcial_hn-= floatval($sueldo_x_hora) * 8;
            $pago_quincenal -= floatval($sueldo_x_hora) * 8;
          }
        }
      }

      $sql_6 = "UPDATE resumen_q_s_asistencia 
			SET  idtrabajador_por_proyecto='$id_trabajador_x_proyecto', fecha_q_s_inicio='$fecha_q_s_inicio', fecha_q_s_fin='$fecha_q_s_fin',
			numero_q_s = '$numero_q_s', sabatical = '$sabatical', sabatical_manual_$numero_sabado = '$estado_sabatical_manual',
      total_dias_asistidos = '$total_dias', total_hn = '$total_hn', pago_parcial_hn = '$pago_parcial_hn', pago_quincenal = '$pago_quincenal', user_updated ='".$_SESSION['idusuario']."'
			WHERE idresumen_q_s_asistencia = '$idresumen_q_s_asistencia';";
      $update_resumen = ejecutarConsulta($sql_6);
      if ( $update_resumen['status'] == false) {return $update_resumen; }

      $accion = ($estado_sabatical_manual == '1' ? 'Agregar' : 'Quitar' );
      //B I T A C O R A -------
      $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('resumen_q_s_asistencia', '".$idresumen_q_s_asistencia."', '$accion sabatical $numero_sabado manual', '".$_SESSION['idusuario']."')";
      $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }

      return $update_resumen; 
    }     
  }

  public function insertar_quitar_sabatical_manual_todos($sabatical_trabajador, $estado_sabatical_manual) {
    //$data_sabatical = json_decode($sabatical_trabajador, true);
    $retorno = "";

    if (!empty($sabatical_trabajador)) {
      foreach ($sabatical_trabajador as $key => $value) {
        $idresumen_q_s_asistencia = $value['idresumen_q_s_asistencia'];

        $fecha_asistida = $value['fecha_asistida'];
        $id_trabajador_x_proyecto = $value['id_trabajador'];
        $sueldo_x_hora = $value['sueldo_hora'];
        $numero_sabado = $value['numero_sabado'];
        $numero_q_s = $value['numero_q_s'];
        $fecha_q_s_inicio = $value['fecha_q_s_inicio'];
        $fecha_q_s_fin = $value['fecha_q_s_fin'];

        // buscamos la: ASISTENCIA
        $sql_1 = "SELECT atr.idasistencia_trabajador FROM asistencia_trabajador AS atr 
				WHERE atr.idtrabajador_por_proyecto = '$id_trabajador_x_proyecto' AND atr.fecha_asistencia = '$fecha_asistida';";
        $buscando_asist = ejecutarConsultaSimpleFila($sql_1);
        if ($buscando_asist['status'] == false) {  return $buscando_asist; }

        $horas = 0;
        $pago_normal = 0;

        $sabatical = 0; $total_dias = 0; $total_hn = 0; $pago_parcial_hn = 0; $pago_quincenal = 0;

        if ($estado_sabatical_manual == '1') {
          $horas = 8;
          $pago_normal = floatval($sueldo_x_hora) * 8;
        } else {
          $horas = 0;
          $pago_normal = 0;
        }

        // validamos la insercion en: ASISTENCIA TRABAJDOR
        if (empty($buscando_asist['data'])) {
          $sql_2 = "INSERT INTO asistencia_trabajador(idtrabajador_por_proyecto, horas_normal_dia, pago_normal_dia, horas_extras_dia, pago_horas_extras, fecha_asistencia, nombre_dia, user_created) 
					VALUES ('$id_trabajador_x_proyecto','$horas','$pago_normal','0', '0', '$fecha_asistida', 'Sábado', '".$_SESSION['idusuario']."')";
          $insert_asistencia = ejecutarConsulta_retornarID($sql_2);
          if ($insert_asistencia['status'] == false) {  return $insert_asistencia; }

          //B I T A C O R A -------
          $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('asistencia_trabajador', '".$insert_asistencia['data']."', 'Crear registro', '".$_SESSION['idusuario']."')";
          $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }
        } else {
          $sql_3 = "UPDATE asistencia_trabajador SET idtrabajador_por_proyecto = '$id_trabajador_x_proyecto', horas_normal_dia = '$horas', 
					pago_normal_dia = '$pago_normal', horas_extras_dia  = '0', pago_horas_extras = '0', fecha_asistencia = '$fecha_asistida', 
          nombre_dia = 'Sábado', user_updated ='".$_SESSION['idusuario']."'
					WHERE idasistencia_trabajador = '" . $buscando_asist['data']['idasistencia_trabajador'] . "';";
          $update_asistencia = ejecutarConsulta($sql_3);
          if ($update_asistencia['status'] == false) {  return $update_asistencia; }

          //B I T A C O R A -------
          $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('asistencia_trabajador', '".$buscando_asist['data']['idasistencia_trabajador']."', 'Editar registro', '".$_SESSION['idusuario']."')";
          $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }
        }

        // validamos la insercion en el: RESUMEN Q S ASISTENCIA
        if (empty($idresumen_q_s_asistencia)) {
          $sql_4 = "INSERT INTO resumen_q_s_asistencia( idtrabajador_por_proyecto, numero_q_s, fecha_q_s_inicio, fecha_q_s_fin, sabatical,  sabatical_manual_$numero_sabado, total_dias_asistidos, total_hn, pago_parcial_hn, pago_quincenal, user_created) 
          VALUES ('$id_trabajador_x_proyecto', '$numero_q_s', '$fecha_q_s_inicio', '$fecha_q_s_fin', '1',  '$estado_sabatical_manual', '1', '8', $pago_normal, $pago_normal, '".$_SESSION['idusuario']."' );";
          $retorno = ejecutarConsulta_retornarID($sql_4);
          if ($retorno['status'] == false) {  return $retorno; }

          $accion = ($estado_sabatical_manual == '1' ? 'Agregar' : 'Quitar' );

          //B I T A C O R A -------
          $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('resumen_q_s_asistencia', '".$retorno['data']."', '$accion sabatical $numero_sabado manual - multiple', '".$_SESSION['idusuario']."')";
          $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }
        } else {
          $sql_5 = "SELECT sabatical, total_dias_asistidos, total_hn, pago_parcial_hn, pago_quincenal FROM resumen_q_s_asistencia WHERE idresumen_q_s_asistencia = '$idresumen_q_s_asistencia';";
          $cant_sab = ejecutarConsultaSimpleFila($sql_5);
          if ($cant_sab['status'] == false) {  return $cant_sab; } 

          if (!empty($cant_sab['data'])) {        
            $sabatical        = empty($cant_sab['data']['sabatical']) ? 0 : floatval($cant_sab['data']['sabatical']);
            $total_dias       = empty($cant_sab['data']['total_dias_asistidos']) ? 0 : floatval($cant_sab['data']['total_dias_asistidos']); 
            $total_hn         = empty($cant_sab['data']['total_hn']) ? 0 : floatval($cant_sab['data']['total_hn']); 
            $pago_parcial_hn  = empty($cant_sab['data']['pago_parcial_hn']) ? 0 : floatval($cant_sab['data']['pago_parcial_hn']); 
            $pago_quincenal   = empty($cant_sab['data']['pago_quincenal']) ? 0 : floatval($cant_sab['data']['pago_quincenal']);
          }

          if ($estado_sabatical_manual == '1') {
            $horas = 8;
            $pago_normal = floatval($sueldo_x_hora) * 8;
          } else {
            $horas = 0;
            $pago_normal = 0;
          }      

          if ($sabatical == 0) {
            $sabatical       = 1;
            $total_dias     += 1;
            $total_hn       += 8 ;
            $pago_parcial_hn+= floatval($sueldo_x_hora) * 8;
            $pago_quincenal += floatval($sueldo_x_hora) * 8;
          } else {
            if ($sabatical == 1) {           
              if ( $estado_sabatical_manual == '1') {
                $sabatical       = 2 ;
                $total_dias     += 1;
                $total_hn       += 8;
                $pago_parcial_hn+= floatval($sueldo_x_hora) * 8;
                $pago_quincenal += floatval($sueldo_x_hora) * 8;
              } else {
                $sabatical       = 0;
                $total_dias     -= 1;
                $total_hn       -= 8;
                $pago_parcial_hn-= floatval($sueldo_x_hora) * 8;
                $pago_quincenal -= floatval($sueldo_x_hora) * 8;
              }          
            } else {
              if ($sabatical == 2) {
                $sabatical       = 1;
                $total_dias     -= 1;
                $total_hn       -= 8;
                $pago_parcial_hn-= floatval($sueldo_x_hora) * 8;
                $pago_quincenal -= floatval($sueldo_x_hora) * 8;
              }
            }
          }

          $sql_6 = "UPDATE resumen_q_s_asistencia 
          SET  idtrabajador_por_proyecto='$id_trabajador_x_proyecto', fecha_q_s_inicio='$fecha_q_s_inicio', fecha_q_s_fin='$fecha_q_s_fin',
          numero_q_s = '$numero_q_s', sabatical = '$sabatical', sabatical_manual_$numero_sabado = '$estado_sabatical_manual',
          total_dias_asistidos = '$total_dias', total_hn = '$total_hn', pago_parcial_hn = '$pago_parcial_hn', pago_quincenal = '$pago_quincenal', user_updated ='".$_SESSION['idusuario']."'
          WHERE idresumen_q_s_asistencia = '$idresumen_q_s_asistencia';";
          $retorno = ejecutarConsulta($sql_6);
          if ($retorno['status'] == false) {  return $retorno; }
          
          $accion = ($estado_sabatical_manual == '1' ? 'Agregar' : 'Quitar' );
          //B I T A C O R A -------
          $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('resumen_q_s_asistencia', '".$idresumen_q_s_asistencia."', '$accion sabatical $numero_sabado manual - multiple', '".$_SESSION['idusuario']."')";
          $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }
        }
      }

      return $retorno;
    }else{
      return  $retorno = ['status' => false, 'message' => 'todo oka ps', 'data' => []];
    }   
  }

  // :::::::::::::::::::::::::::::::::::: S E C C I O N   D I A S   P O R   T R A B A J A D O R ::::::::::::::::::::::::::::::::::::::

  public function tbla_asis_individual($idtrabajador_x_proyecto) {
    $sql = "SELECT atra.idasistencia_trabajador, atra.idasistencia_trabajador,  atra.horas_normal_dia, atra.pago_normal_dia, atra.horas_extras_dia, 
		atra.pago_horas_extras, atra.fecha_asistencia, atra.nombre_dia, atra.estado, t.nombres as trabajador, 
		t.tipo_documento as tipo_doc, t.numero_documento AS num_doc, t.imagen_perfil , atra.doc_justificacion, atra.descripcion_justificacion
		FROM asistencia_trabajador AS atra, trabajador_por_proyecto AS tp, trabajador AS t
		WHERE atra.idtrabajador_por_proyecto = tp.idtrabajador_por_proyecto AND tp.idtrabajador = t.idtrabajador 
		AND atra.idtrabajador_por_proyecto = '$idtrabajador_x_proyecto' ORDER BY  atra.fecha_asistencia DESC; ";
    return ejecutarConsulta($sql);
  }

  public function editar_dia($idasistencia_trabajador, $trabajador, $horas_trabajo, $pago_dia, $horas_extras, $pago_horas_extras, $sabatical) {
    //var_dump($idasistencia_trabajador,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$c_bancaria,$c_detracciones,$banco,$titular_cuenta);die;

    $sql = "UPDATE asistencia_trabajador SET idtrabajador='$trabajador', horas_trabajador='$horas_trabajo',	pago_dia='$pago_dia',
		horas_extras_dia='$horas_extras',	pago_horas_extras='$pago_horas_extras',	sabatical='$sabatical', user_updated = '".$_SESSION['idusuario']."'
    WHERE idasistencia_trabajador='$idasistencia_trabajador'";
    $editar = ejecutarConsulta($sql);
    if ( $editar['status'] == false) {return $editar; }

    //B I T A C O R A -------
    $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('asistencia_trabajador', '".$idasistencia_trabajador."', 'Editar hora', '".$_SESSION['idusuario']."')";
    $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }

    return $editar;
  }

  public function desactivar_dia($idasistencia_trabajador) {
    $sql = "UPDATE asistencia_trabajador SET estado='0', user_trash='".$_SESSION['idusuario']."' WHERE idasistencia_trabajador='$idasistencia_trabajador'";
    $desactivar = ejecutarConsulta($sql);
    if ( $desactivar['status'] == false) {return $desactivar; }

    //B I T A C O R A -------
    $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('asistencia_trabajador', '".$idasistencia_trabajador."', 'Papelera', '".$_SESSION['idusuario']."')";
    $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }

    return $desactivar;
  }

  public function activar_dia($idasistencia_trabajador) {
    $sql = "UPDATE asistencia_trabajador SET estado='1', user_trash='".$_SESSION['idusuario']."' WHERE idasistencia_trabajador='$idasistencia_trabajador'";
    $activar = ejecutarConsulta($sql);
    if ( $activar['status'] == false) {return $activar; }

    //B I T A C O R A -------
    $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('asistencia_trabajador', '".$idasistencia_trabajador."', 'Recuperar de papelera', '".$_SESSION['idusuario']."')";
    $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }

    return $activar;
  }

  // :::::::::::::::::::::::::::::::::::: S E C C I O N   J U S T I F I C A C I Ó N  ::::::::::::::::::::::::::::::::::::::

  public function editar_justificacion($idasistencia_trabajador_j, $detalle_j, $doc) {
    $sql = "UPDATE asistencia_trabajador SET descripcion_justificacion='$detalle_j', doc_justificacion='$doc', user_updated='".$_SESSION['idusuario']."'
		WHERE idasistencia_trabajador = '$idasistencia_trabajador_j';";
    $justificacion = ejecutarConsulta($sql);
    if ( $justificacion['status'] == false) {return $justificacion; }

    //B I T A C O R A -------
    $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('asistencia_trabajador', '".$idasistencia_trabajador_j."', 'Editar justificación', '".$_SESSION['idusuario']."')";
    $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }

    return $justificacion;
  }

  public function mostrar_justificacion($idasistencia_trabajador_j) {
    $sql = "SELECT idasistencia_trabajador, descripcion_justificacion, doc_justificacion 
		FROM asistencia_trabajador
		WHERE idasistencia_trabajador =  '$idasistencia_trabajador_j';";

    return ejecutarConsultaSimpleFila($sql);
  }

  // :::::::::::::::::::::::::::::::::::: S E C C I O N   Q-S  P O R   T R A B A J A D O R  ::::::::::::::::::::::::::::::::::::::

  public function tbla_qs_individual($idtrabajador_x_proyecto) {
    $sql = "SELECT rqsa.idresumen_q_s_asistencia, rqsa.idtrabajador_por_proyecto, rqsa.numero_q_s, rqsa.fecha_q_s_inicio, 
		rqsa.fecha_q_s_fin, rqsa.total_hn, rqsa.total_he, rqsa.total_dias_asistidos, rqsa.sabatical, rqsa.sabatical_manual_1, 
		rqsa.sabatical_manual_2, rqsa.pago_parcial_hn, rqsa.pago_parcial_he, rqsa.adicional_descuento, rqsa.descripcion_descuento, 
		rqsa.pago_quincenal, rqsa.estado_envio_contador, rqsa.recibos_x_honorarios, rqsa.estado, p.fecha_pago_obrero, 
		t.nombres AS trabajdor, t.tipo_documento, t.numero_documento
		FROM resumen_q_s_asistencia AS rqsa, trabajador_por_proyecto AS tpp, proyecto AS p, trabajador AS t
		WHERE rqsa.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto AND tpp.idproyecto = p.idproyecto AND tpp.idtrabajador = t.idtrabajador AND
		 rqsa.idtrabajador_por_proyecto = '$idtrabajador_x_proyecto' 
		ORDER BY  numero_q_s ASC; ";
    return ejecutarConsulta($sql);
  }

  public function suma_qs_individual($idtrabajador_x_proyecto) {
    $sql = "SELECT  SUM(rqsa.pago_quincenal) AS pago_quincenal, SUM(rqsa.sabatical) AS sabatical, 
    SUM(rqsa.total_dias_asistidos) AS total_dias_asistidos, SUM(rqsa.adicional_descuento) AS adicional_descuento, p.fecha_pago_obrero
		FROM resumen_q_s_asistencia AS rqsa, trabajador_por_proyecto AS tpp, proyecto AS p
		WHERE rqsa.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto AND tpp.idproyecto = p.idproyecto 
		AND rqsa.idtrabajador_por_proyecto = '$idtrabajador_x_proyecto' AND rqsa.estado = '1';";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function desactivar_qs($id) {
    $sql = "UPDATE resumen_q_s_asistencia SET estado='0', user_trash='".$_SESSION['idusuario']."' WHERE idresumen_q_s_asistencia='$id'";
    $desactivar = ejecutarConsulta($sql);

    //B I T A C O R A -------
    $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('resumen_q_s_asistencia', '".$id."', 'Papelera', '".$_SESSION['idusuario']."')";
    $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }

    return $desactivar;
  }

  public function activar_qs($id) {
    $sql = "UPDATE resumen_q_s_asistencia SET estado='1', user_trash='".$_SESSION['idusuario']."' WHERE idresumen_q_s_asistencia='$id'";
    $activar = ejecutarConsulta($sql);

    //B I T A C O R A -------
    $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('resumen_q_s_asistencia', '".$id."', 'Recuperar de papelera', '".$_SESSION['idusuario']."')";
    $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }

    return $activar;
  }

  // :::::::::::::::::::::::::::::::::::: S E C C I O N   A D I C I O N A L   D E S C U E N T O ::::::::::::::::::::::::::::::::::::::

  public function insertar_detalle_adicional($idtrabajador_por_proyecto, $fecha_registro, $detalle_adicional) {
    $sql = "INSERT INTO resumen_q_s_asistencia(idtrabajador_por_proyecto, fecha_q_s_inicio, descripcion_descuento, user_created) 
		VALUES ('$idtrabajador_por_proyecto', '$fecha_registro', '$detalle_adicional', '".$_SESSION['idusuario']."' )";
    $insert = ejecutarConsulta_retornarID($sql);

    //B I T A C O R A -------
    $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('resumen_q_s_asistencia', '".$insert['data']."', 'Crear adicional descuento', '".$_SESSION['idusuario']."')";
    $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }

    return $insert;
  }

  public function editar_detalle_adicionales($idresumen_q_s_asistencia, $idtrabajador_por_proyecto, $fecha_registro, $detalle_adicional) {
    $sql = "UPDATE resumen_q_s_asistencia 
		SET  idtrabajador_por_proyecto='$idtrabajador_por_proyecto', fecha_q_s_inicio='$fecha_registro', descripcion_descuento = '$detalle_adicional', user_updated = '".$_SESSION['idusuario']."'
		WHERE idresumen_q_s_asistencia = '$idresumen_q_s_asistencia';";
    $editar = ejecutarConsulta($sql);
    if ( $editar['status'] == false) {return $editar; }

    //B I T A C O R A -------
    $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('resumen_q_s_asistencia', '".$idresumen_q_s_asistencia."', 'Editar descripción adicional descuento', '".$_SESSION['idusuario']."')";
    $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }

    return $editar;
  }

  public function descripcion_adicional_descuento($id_adicional) {
    $sql = "SELECT descripcion_descuento FROM resumen_q_s_asistencia WHERE idresumen_q_s_asistencia = '$id_adicional';";
    return ejecutarConsultaSimpleFila($sql);    
  }

  // :::::::::::::::::::::::::::::::::::: S E C C I O N   F E C H A S   A C T I V I D A D ::::::::::::::::::::::::::::::::::::::

  public function fechas_actividad($id) {
    $sql = "SELECT idproyecto, fecha_inicio_actividad, fecha_fin_actividad, plazo_actividad
		FROM proyecto 
		WHERE idproyecto = '$id'";

    return ejecutarConsultaSimpleFila($sql);
  }

  public function editar_fechas_actividad($id_proyecto_f, $fecha_inicio_actividad, $fecha_fin_actividad, $plazo_actividad) {
    $sql = "UPDATE proyecto SET fecha_inicio_actividad='$fecha_inicio_actividad', fecha_fin_actividad= '$fecha_fin_actividad',
		plazo_actividad = '$plazo_actividad', user_updated = '".$_SESSION['idusuario']."'
		WHERE idproyecto = '$id_proyecto_f'";
    $fecha = ejecutarConsulta($sql);
    if ( $fecha['status'] == false) {return $fecha; }

    //B I T A C O R A -------
    $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('proyecto', '".$id_proyecto_f."', 'Editar fechas de actividad', '".$_SESSION['idusuario']."')";
    $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }

    return $fecha;
  }

  // :::::::::::::::::::::::::::::::::::: S E C C I O N   O B T E N E R   I M G ::::::::::::::::::::::::::::::::::::::

  // obtebnemos los "DOC JUSTIFICACION para eliminar
  public function imgJustificacion($id) {
    $sql = "SELECT doc_justificacion FROM asistencia_trabajador WHERE idasistencia_trabajador = '$id'";
    return ejecutarConsulta($sql);
  }
}

?>
