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
  public function insertar_asistencia_y_resumen_q_s_asistencia_hn( $resumen_qs, $fecha_i, $fecha_f) {
    // $data_asistencia = json_decode($asistencia, true);
    $data_resumen_qs = json_decode($resumen_qs, true);
    $pruebas = "";
    $sw = true;

    $buscar_asistencia = "";

    $retorno = ""; 
    // registramos o editamos las "resumen q s asistencia"
    foreach ($data_resumen_qs as $indice1 => $key_r) {

      $idtrabajador     = $key_r['id_trabajador'];
      $idresumen_q_s_asistencia = $key_r['idresumen_q_s_asistencia'];
      $ids_q_asistencia = $key_r['ids_q_asistencia'];
      $num_semana       = $key_r['num_semana'];

      if (empty($idresumen_q_s_asistencia)) {
        # insertamos un nuevo registro
        $sql_5 = "INSERT INTO resumen_q_s_asistencia(idtrabajador_por_proyecto,ids_q_asistencia, numero_q_s, fecha_q_s_inicio, fecha_q_s_fin, total_hn, total_dias_asistidos_hn, sabatical, pago_parcial_hn, adicional_descuento, pago_quincenal_hn, user_created) 
				VALUES ('$idtrabajador', '$ids_q_asistencia', '$num_semana', '" . $key_r['fecha_q_s_inicio'] . "', '" . $key_r['fecha_q_s_fin'] . "', '" . $key_r['total_hn'] . 
        "', '" . $key_r['dias_asistidos_hn'] . "', '" . $key_r['sabatical'] . "', '" . $key_r['pago_parcial_hn'] . "', '" . $key_r['adicional_descuento'] . "', '" .
        $key_r['pago_quincenal_hn'] . "', '".$_SESSION['idusuario']."')";
        $retorno =  ejecutarConsulta_retornarID($sql_5); if ($retorno['status'] == false) {  return $retorno; }
        $id_rqsa = $retorno['data'];
        //B I T A C O R A -------
        $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('asistencia_trabajador', '$id_rqsa', 'Crear registro', '".$_SESSION['idusuario']."')";
        $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }

        $sql_delete = "UPDATE asistencia_trabajador SET estado='0' WHERE idresumen_q_s_asistencia='$idresumen_q_s_asistencia';";
        $retorno = ejecutarConsulta($sql_delete); if ($retorno['status'] == false) {  return $retorno; }

        // registramos o editamos las "asistencias de cada trabajador"
        foreach ($key_r['array_datos_asistencia'] as $indice => $key_a) {
          $idasistencia_trabajador = $key_a['idasistencia_trabajador'];
          if (empty($idasistencia_trabajador)) {
            // insertamos un nuevo registro
            $sql_2 = "INSERT INTO asistencia_trabajador (idresumen_q_s_asistencia, horas_normal_dia, pago_normal_dia, sueldo_diario, fecha_asistencia, nombre_dia, user_created)			
            VALUES ('$id_rqsa', '" . $key_a['horas_normal_dia'] . "', '" . $key_a['pago_normal_dia'] ."', '" . $key_a['sueldo_diario'] . "', '" . $key_a['fecha_asistida'] . "', '" . $key_a['nombre_dia'] . "', '".$_SESSION['idusuario']."' )";
            $new_registro = ejecutarConsulta_retornarID($sql_2); if ($new_registro['status'] == false) {  return $new_registro; }

            //B I T A C O R A -------
            $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('asistencia_trabajador', '".$new_registro['data']."', 'Crear registro', '".$_SESSION['idusuario']."')";
            $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }

          } else {
            # editamos el registro existente
            $sql_3 =  "UPDATE asistencia_trabajador SET idresumen_q_s_asistencia='$id_rqsa', horas_normal_dia='" . $key_a['horas_normal_dia'] .
              "', pago_normal_dia='" . $key_a['pago_normal_dia'] . "', sueldo_diario ='" . $key_a['sueldo_diario'] . "',  fecha_asistencia = '" . $key_a['fecha_asistida'] .
              "', nombre_dia = '" . $key_a['nombre_dia'] . "', estado ='1', user_updated='".$_SESSION['idusuario']."' WHERE idasistencia_trabajador='$idasistencia_trabajador';";
            $edita_registro = ejecutarConsulta($sql_3);  if ($edita_registro['status'] == false) {  return $edita_registro; }

            //B I T A C O R A -------
            $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('asistencia_trabajador', '$idasistencia_trabajador', 'Editar registro', '".$_SESSION['idusuario']."')";
            $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }
          }
        }

      } else {
        # editamos el registro encontrado
        $sql_6 = "UPDATE resumen_q_s_asistencia SET  idtrabajador_por_proyecto='$idtrabajador', ids_q_asistencia = '$ids_q_asistencia', numero_q_s='$num_semana', 
        fecha_q_s_inicio='" .  $key_r['fecha_q_s_inicio'] . "', fecha_q_s_fin='" . $key_r['fecha_q_s_fin'] . "', total_hn='" . $key_r['total_hn'] .
        "', total_dias_asistidos_hn='" . $key_r['dias_asistidos_hn'] . "', sabatical='" . $key_r['sabatical'] ."', pago_parcial_hn='" . $key_r['pago_parcial_hn'] . "',
        adicional_descuento='" . $key_r['adicional_descuento'] . "', pago_quincenal_hn='" . $key_r['pago_quincenal_hn'] ."', user_updated='".$_SESSION['idusuario']."' 
        WHERE idresumen_q_s_asistencia = '$idresumen_q_s_asistencia';";
        $retorno = ejecutarConsulta($sql_6); if ($retorno['status'] == false) {  return $retorno; }

        //B I T A C O R A -------
        $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('asistencia_trabajador', '$idresumen_q_s_asistencia', 'Editar registro', '".$_SESSION['idusuario']."')";
        $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }

        $sql_delete = "UPDATE asistencia_trabajador SET estado='0' WHERE idresumen_q_s_asistencia='$idresumen_q_s_asistencia';";
        $retorno = ejecutarConsulta($sql_delete); if ($retorno['status'] == false) {  return $retorno; }
        
        // registramos o editamos las "asistencias de cada trabajador"
        foreach ($key_r['array_datos_asistencia'] as $indice2 => $key_a) {
          $idasistencia_trabajador = $key_a['idasistencia_trabajador'];  
          // $dddd[] = ['n'=>$indice1, 'id_trabajador' => $key_a['id_trabajador'],  'h_n_d' => $key_a['horas_normal_dia'],'p_n_d' => $key_a['pago_normal_dia'],'p_n_d' => $key_a['fecha_asistida'], 'n_d' => $key_a['nombre_dia'],];
          if (empty($idasistencia_trabajador)) {
            // insertamos un nuevo registro
            $sql_2 = "INSERT INTO asistencia_trabajador (idresumen_q_s_asistencia, horas_normal_dia, pago_normal_dia, sueldo_diario, fecha_asistencia, nombre_dia,  user_created)			
            VALUES ('$idresumen_q_s_asistencia', '" . $key_a['horas_normal_dia'] . "', '" . $key_a['pago_normal_dia'] . "', '" . $key_a['sueldo_diario'] . "', '" .
            $key_a['fecha_asistida'] . "', '" . $key_a['nombre_dia'] . "', '".$_SESSION['idusuario']."' )";
            $new_registro = ejecutarConsulta_retornarID($sql_2); if ($new_registro['status'] == false) {  return $new_registro; }

            //B I T A C O R A -------
            $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('asistencia_trabajador', '".$new_registro['data']."', 'Crear registro', '".$_SESSION['idusuario']."')";
            $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }

          } else {
            # editamos el registro existente
            $sql_3 =  "UPDATE asistencia_trabajador SET idresumen_q_s_asistencia='$idresumen_q_s_asistencia', horas_normal_dia='" . $key_a['horas_normal_dia'] .
              "', pago_normal_dia='" . $key_a['pago_normal_dia'] . "', sueldo_diario='" . $key_a['sueldo_diario'] . "',  fecha_asistencia = '" . $key_a['fecha_asistida'] .
              "', nombre_dia = '" . $key_a['nombre_dia'] . "', estado='1', user_updated='".$_SESSION['idusuario']."' WHERE idasistencia_trabajador='$idasistencia_trabajador';";
            $edita_registro = ejecutarConsulta($sql_3);  if ($edita_registro['status'] == false) {  return $edita_registro; }

            //B I T A C O R A -------
            $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('asistencia_trabajador', '$idasistencia_trabajador', 'Editar registro', '".$_SESSION['idusuario']."')";
            $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }
          }
        }
      }
    }
    return $retorno;
  }

  public function insertar_asistencia_y_resumen_q_s_asistencia_he( $resumen_qs, $fecha_i, $fecha_f) {
    // $data_asistencia = json_decode($asistencia, true);
    $data_resumen_qs = json_decode($resumen_qs, true);
    $pruebas = "";
    $sw = true;

    $buscar_asistencia = "";

    $retorno = "";
    // registramos o editamos las "resumen q s asistencia"
    foreach ($data_resumen_qs as $indice => $key_r) {

      $idtrabajador     = $key_r['id_trabajador'];
      $idresumen_q_s_asistencia = $key_r['idresumen_q_s_asistencia'];
      $ids_q_asistencia = $key_r['ids_q_asistencia'];
      $num_semana       = $key_r['num_semana'];

      if (empty($idresumen_q_s_asistencia)) {
        # insertamos un nuevo registro
        $sql_5 = "INSERT INTO resumen_q_s_asistencia(idtrabajador_por_proyecto,ids_q_asistencia, numero_q_s, fecha_q_s_inicio, fecha_q_s_fin, total_he, total_dias_asistidos_he, pago_parcial_he, pago_quincenal_he, user_created) 
				VALUES ('$idtrabajador', '$ids_q_asistencia', '$num_semana', '" . $key_r['fecha_q_s_inicio'] . "', '" . $key_r['fecha_q_s_fin'] . "', '" . $key_r['total_he'] . 
        "', '" . $key_r['dias_asistidos_he'] . "', '" . $key_r['pago_parcial_he'] . "' , '" . $key_r['pago_quincenal_he'] . "', '".$_SESSION['idusuario']."')";
        $retorno =  ejecutarConsulta_retornarID($sql_5); if ($retorno['status'] == false) {  return $retorno; }
        $id_rqsa = $retorno['data'];
        //B I T A C O R A -------
        $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('asistencia_trabajador', '$id_rqsa', 'Crear registro', '".$_SESSION['idusuario']."')";
        $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }

        $sql_delete = "UPDATE asistencia_trabajador SET estado='0' WHERE idresumen_q_s_asistencia='$idresumen_q_s_asistencia';";
        $retorno = ejecutarConsulta($sql_delete); if ($retorno['status'] == false) {  return $retorno; }

        // registramos o editamos las "asistencias de cada trabajador"
        foreach ($key_r['array_datos_asistencia'] as $indice => $key_a) {
          $idasistencia_trabajador = $key_a['idasistencia_trabajador'];
          if (empty($idasistencia_trabajador)) {
            // insertamos un nuevo registro
            $sql_2 = "INSERT INTO asistencia_trabajador (idresumen_q_s_asistencia, horas_extras_dia, pago_horas_extras, sueldo_diario, fecha_asistencia, nombre_dia, user_created)			
            VALUES ('$id_rqsa',  '" . $key_a['horas_extras_dia'] . "', '" . $key_a['pago_horas_extras'] . "', '" . $key_a['sueldo_diario'] . "', '" . $key_a['fecha_asistida'] . "', '" . $key_a['nombre_dia'] . "', '".$_SESSION['idusuario']."' )";
            $new_registro = ejecutarConsulta_retornarID($sql_2); if ($new_registro['status'] == false) {  return $new_registro; }

            //B I T A C O R A -------
            $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('asistencia_trabajador', '".$new_registro['data']."', 'Crear registro', '".$_SESSION['idusuario']."')";
            $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }

          } else {
            # editamos el registro existente
            $sql_3 =  "UPDATE asistencia_trabajador SET idresumen_q_s_asistencia='$id_rqsa', horas_extras_dia='" . $key_a['horas_extras_dia'] .
              "', pago_horas_extras='" . $key_a['pago_horas_extras'] . "', sueldo_diario='" . $key_a['sueldo_diario'] . "', fecha_asistencia = '" . $key_a['fecha_asistida'] .
              "', nombre_dia = '" . $key_a['nombre_dia'] . "', estado = '1', user_updated='".$_SESSION['idusuario']."' WHERE idasistencia_trabajador='$idasistencia_trabajador';";
            $edita_registro = ejecutarConsulta($sql_3);  if ($edita_registro['status'] == false) {  return $edita_registro; }

            //B I T A C O R A -------
            $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('asistencia_trabajador', '$idasistencia_trabajador', 'Editar registro', '".$_SESSION['idusuario']."')";
            $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }
          }
        }

      } else {
        # editamos el registro encontrado
        $sql_6 = "UPDATE resumen_q_s_asistencia SET  idtrabajador_por_proyecto='$idtrabajador', ids_q_asistencia = '$ids_q_asistencia', numero_q_s='$num_semana', 
        fecha_q_s_inicio='" . $key_r['fecha_q_s_inicio'] . "', fecha_q_s_fin='" . $key_r['fecha_q_s_fin'] . "', total_he='" . $key_r['total_he'] . "', 
        total_dias_asistidos_he='" . $key_r['dias_asistidos_he'] . "', pago_parcial_he='" . $key_r['pago_parcial_he'] . "', pago_quincenal_he='" . $key_r['pago_quincenal_he'] ."', 
        user_updated='".$_SESSION['idusuario']."' WHERE idresumen_q_s_asistencia = '$idresumen_q_s_asistencia';";
        $retorno = ejecutarConsulta($sql_6); if ($retorno['status'] == false) {  return $retorno; }

        //B I T A C O R A -------
        $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('asistencia_trabajador', '$idresumen_q_s_asistencia', 'Editar registro', '".$_SESSION['idusuario']."')";
        $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }

        $sql_delete = "UPDATE asistencia_trabajador SET estado='0' WHERE idresumen_q_s_asistencia='$idresumen_q_s_asistencia';";
        $retorno = ejecutarConsulta($sql_delete); if ($retorno['status'] == false) {  return $retorno; }

        // registramos o editamos las "asistencias de cada trabajador"
        foreach ($key_r['array_datos_asistencia'] as $indice => $key_a) {
          $idasistencia_trabajador = $key_a['idasistencia_trabajador'];
          if (empty($idasistencia_trabajador)) {
            // insertamos un nuevo registro
            $sql_2 = "INSERT INTO asistencia_trabajador (idresumen_q_s_asistencia, horas_extras_dia, pago_horas_extras, sueldo_diario, fecha_asistencia, nombre_dia, user_created)			
            VALUES ('$idresumen_q_s_asistencia',  '" . $key_a['horas_extras_dia'] . "', '" . $key_a['pago_horas_extras'] . "', '" . $key_a['sueldo_diario'] . "', '" . $key_a['fecha_asistida'] . "', '" . $key_a['nombre_dia'] . "', '".$_SESSION['idusuario']."' )";
            $new_registro = ejecutarConsulta_retornarID($sql_2); if ($new_registro['status'] == false) {  return $new_registro; }

            //B I T A C O R A -------
            $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('asistencia_trabajador', '".$new_registro['data']."', 'Crear registro', '".$_SESSION['idusuario']."')";
            $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }

          } else {
            # editamos el registro existente
            $sql_3 =  "UPDATE asistencia_trabajador SET idresumen_q_s_asistencia='$idresumen_q_s_asistencia', horas_extras_dia='" . $key_a['horas_extras_dia'] .
              "', pago_horas_extras='" . $key_a['pago_horas_extras'] . "', sueldo_diario='" . $key_a['sueldo_diario'] . "', fecha_asistencia = '" . $key_a['fecha_asistida'] .
              "', nombre_dia = '" . $key_a['nombre_dia'] . "', estado = '1', user_updated='".$_SESSION['idusuario']."' WHERE idasistencia_trabajador='$idasistencia_trabajador';";
            $edita_registro = ejecutarConsulta($sql_3);  if ($edita_registro['status'] == false) {  return $edita_registro; }

            //B I T A C O R A -------
            $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('asistencia_trabajador', '$idasistencia_trabajador', 'Editar registro', '".$_SESSION['idusuario']."')";
            $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }
          }
        }
      }
    }
    return $retorno;
  }

  //Implementar un método para listar asistencia
  public function tbla_principal($nube_idproyecto) {
    $data_array = Array();
    $sql = "SELECT tpp.idtrabajador_por_proyecto, t.idtrabajador AS idtrabajador, t.nombres AS nombre, t.tipo_documento as tipo_doc, 
		t.numero_documento AS num_doc, t.imagen_perfil AS imagen, tpp.sueldo_hora, tpp.sueldo_mensual, tpp.sueldo_diario,
		 p.fecha_inicio AS fecha_inicio_proyect, tp.nombre AS tipo_trabajador, o.nombre_ocupacion as desempenio
		FROM trabajador AS t, trabajador_por_proyecto AS tpp, tipo_trabajador AS tp, ocupacion as o,  proyecto AS p
		WHERE t.idtrabajador = tpp.idtrabajador AND tpp.idproyecto = p.idproyecto AND t.idtipo_trabajador = tp.idtipo_trabajador AND t.idocupacion = o.idocupacion
    AND tpp.idproyecto = '$nube_idproyecto' GROUP BY tpp.idtrabajador_por_proyecto ORDER BY tpp.orden_trabajador ASC;";
    $agrupar_trabajdor = ejecutarConsultaArray($sql);  if ($agrupar_trabajdor['status'] == false) {  return $agrupar_trabajdor; }

    foreach ($agrupar_trabajdor['data'] as $key => $value) {
      $sql_2 = "SELECT SUM(adicional_descuento) AS adicional_descuento, SUM(sabatical) AS total_sabatical, SUM(total_hn) AS total_hn, SUM(total_he) AS total_he, 
			SUM((total_dias_asistidos_hn + total_dias_asistidos_he)) AS total_dias_asistidos, SUM(pago_quincenal_hn + pago_quincenal_he) AS pago_quincenal
			FROM resumen_q_s_asistencia 
			WHERE  estado = '1' AND estado_delete = '1' AND idtrabajador_por_proyecto = '" . $value['idtrabajador_por_proyecto'] . "';";
      $sab = ejecutarConsultaSimpleFila($sql_2);   if ($sab['status'] == false) {  return $sab; }

      $id_resumen = empty($sab['data']) ? '0' : (empty($sab['data']['total_hn']) ? '0' : $sab['data']['total_hn'] ) ;
      $sql_3 = "SELECT SUM(atr.horas_normal_dia) AS total_horas_normal, SUM(atr.horas_extras_dia) AS total_horas_extras
      FROM asistencia_trabajador AS atr WHERE  atr.estado = '1' AND atr.estado_delete = '1' AND atr.idresumen_q_s_asistencia = '$id_resumen' ";
      $asistencia = ejecutarConsultaSimpleFila($sql_3);   if ($asistencia['status'] == false) {  return $asistencia; }

      $data_array[] = [
        'idtrabajador_por_proyecto' => $value['idtrabajador_por_proyecto'],
        'idtrabajador'              => $value['idtrabajador'],
        'nombre'                    => $value['nombre'],
        'tipo_doc'                  => $value['tipo_doc'],
        'num_doc'                   => $value['num_doc'],
        'imagen'                    => $value['imagen'],
        'sueldo_hora'               => $value['sueldo_hora'],
        'sueldo_diario'             => $value['sueldo_diario'],
        'sueldo_mensual'            => $value['sueldo_mensual'],
        'total_horas_normal'        => empty($sab['data']) ? 0 : (empty($sab['data']['total_hn']) ? 0 : floatval($sab['data']['total_hn'])),
        'total_horas_extras'        => empty($sab['data']) ? 0 : (empty($sab['data']['total_he']) ? 0 : floatval($sab['data']['total_he'])),        
        'fecha_inicio_proyect'      => $value['fecha_inicio_proyect'],
        'tipo_trabajador'           => $value['tipo_trabajador'],
        'desempenio'                => $value['desempenio'],
        'total_sabatical'           => empty($sab['data']) ? 0 : (empty($sab['data']['total_sabatical']) ? 0 : floatval($sab['data']['total_sabatical'])),
        'pago_quincenal'            => empty($sab['data']) ? 0 : (empty($sab['data']['pago_quincenal']) ? 0 : floatval($sab['data']['pago_quincenal'])),
        'adicional_descuento'       => empty($sab['data']) ? 0 : (empty($sab['data']['adicional_descuento']) ? 0 : floatval($sab['data']['adicional_descuento'])),
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
  public function listar_s_q_botones($nube_idproyecto) {
    $sql = "SELECT sqa.ids_q_asistencia, sqa.idproyecto, sqa.numero_q_s, sqa.fecha_q_s_inicio, sqa.fecha_q_s_fin
		FROM s_q_asistencia as sqa WHERE sqa.estado = '1' AND sqa.estado_delete = '1' AND sqa.idproyecto='$nube_idproyecto'";
    $btn_asistencia =  ejecutarConsultaArray($sql); if ($btn_asistencia['status'] == false) {  return $btn_asistencia; }

    $sql_2 = "SELECT fecha_inicio_actividad, fecha_fin_actividad FROM proyecto WHERE idproyecto = '$nube_idproyecto'";
    $proyecto =  ejecutarConsultaSimpleFila($sql_2); if ($proyecto['status'] == false) {  return $proyecto; }
    return $retorno = ['status' => true, 'message' => 'todo oka ps', 'data' =>['btn_asistencia' =>$btn_asistencia['data'],'proyecto' => $proyecto['data'] ], ];
  }

  //ver detalle quincena
  public function ver_detalle_quincena($ids_q_asistencia, $f1, $f2, $nube_idproyect, $n_f_i_p, $n_f_f_p) {
    // Regulamos los dias
    $fecha_array = [];
    $fechaInicio      = new DateTime($f1);
    $fechaFin         = new DateTime($f2);
    $num_dia = 1; $dia_regular = 0; $estado_regular = false;

    $weekday_regular = $fechaInicio->format("w");
    if ($weekday_regular == "0") { $dia_regular = 0;  #$fechaInicio->modify('-1 day'); # sumar 1 dias
    } else if ($weekday_regular == "1") { $dia_regular = 1; $fechaInicio->modify('-1 day'); # sumar 1 dias    
    } else if ($weekday_regular == "2") { $dia_regular = 2; $fechaInicio->modify('-2 day'); # sumar 1 dias  
    } else if ($weekday_regular == "3") { $dia_regular = 3; $fechaInicio->modify('-3 day'); # sumar 1 dias     
    } else if ($weekday_regular == "4") { $dia_regular = 4; $fechaInicio->modify('-4 day'); # sumar 1 dias       
    } else if ($weekday_regular == "5") { $dia_regular = 5; $fechaInicio->modify('-5 day'); # sumar 1 dias         
    } else if ($weekday_regular == "6") { $dia_regular = 6; $fechaInicio->modify('-6 day'); }

    while ($fechaInicio <= $fechaFin) {
      $fecha_array[] = [ 'n' =>$num_dia, 'dia_regular' => ( $num_dia <= $dia_regular ? true : false), 'fecha_dia' => $fechaInicio->format("Y-m-d"), 'nombre_dia' => nombre_dia_semana($fechaInicio->format("Y-m-d"))  ];
      $fechaInicio->modify('+1 day'); # sumar 1 dias
      $num_dia++;
    }
   
    // extraemos todos lo trabajadores del proyecto
    $sql1 = "SELECT tpp.idtrabajador_por_proyecto, o.nombre_ocupacion, tp.nombre as tipo_trabajador, t.nombres, t.tipo_documento, 
    t.numero_documento, t.imagen_perfil, tpp.sueldo_mensual, tpp.sueldo_semanal, tpp.sueldo_diario, tpp.sueldo_hora, tpp.estado, tpp.fecha_inicio, tpp.fecha_fin
		FROM trabajador_por_proyecto AS tpp, trabajador AS t, tipo_trabajador AS tp, ocupacion AS o
		WHERE tpp.idtrabajador = t.idtrabajador  AND o.idocupacion = t.idocupacion AND t.idtipo_trabajador = tp.idtipo_trabajador 
		AND  tpp.idproyecto = '$nube_idproyect' AND tp.nombre ='Obrero'  ORDER BY tpp.orden_trabajador ASC ;";
    $trabajador = ejecutarConsultaArray($sql1); if ($trabajador['status'] == false) {  return $trabajador; }

    $data = [];
    $extras = "";   

    foreach ($trabajador['data'] as $indice => $key) {
      $id_tpp = $key['idtrabajador_por_proyecto'];      
      $fechas_asistencia = [];      

      $sql2 = "SELECT sueldo_mensual, sueldo_semanal, sueldo_diario, fecha_desde, fecha_hasta 
      FROM sueldo WHERE idtrabajador_por_proyecto = '$id_tpp' AND estado ='1' AND estado_delete ='1';";
      $sueldo = ejecutarConsultaArray($sql2); if ($sueldo['status'] == false) {  return $sueldo; }       

      // Validamos si tiene sueldos
      if ( !empty($sueldo['data']) ) {

        $show_hide_tpp = false; 
        foreach ($sueldo['data'] as $key0 => $val_s) { // Buscamos si esta dentro de la semana
          if ( validar_fecha_menor_igual_que($val_s['fecha_desde'], $f1) && validar_fecha_mayor_igual_que($val_s['fecha_hasta'], $f2)  ) {
            $show_hide_tpp = true; break;
          } else if ( validar_fecha_menor_igual_que($val_s['fecha_desde'], $f1) && fecha_dentro_de_rango($val_s['fecha_hasta'], $f1, $f2)  ) {
            $show_hide_tpp = true; break;
          } else if ( fecha_dentro_de_rango($val_s['fecha_desde'], $f1, $f2) && fecha_dentro_de_rango($val_s['fecha_hasta'], $f1, $f2) ) {
            $show_hide_tpp = true; break;
          } else if ( fecha_dentro_de_rango($val_s['fecha_desde'], $f1, $f2) && validar_fecha_mayor_igual_que($val_s['fecha_hasta'], $f2) ) {
            $show_hide_tpp = true; break;
          }          
        }

        // Ocultamos o mostramos segun los buscado
        if ( $show_hide_tpp == true) {
          $sql3 = "SELECT idresumen_q_s_asistencia, idtrabajador_por_proyecto, fecha_q_s_inicio, total_hn, total_he, total_dias_asistidos_hn, total_dias_asistidos_he, sabatical, sabatical_manual_1, sabatical_manual_2, pago_parcial_hn, pago_parcial_he, adicional_descuento, descripcion_descuento, pago_quincenal_hn, pago_quincenal_he, estado_envio_contador 
          FROM resumen_q_s_asistencia WHERE idtrabajador_por_proyecto = '$id_tpp' AND ids_q_asistencia = '$ids_q_asistencia' AND estado = '1' AND estado_delete = '1';";
          $extras = ejecutarConsultaSimpleFila($sql3); if ($extras['status'] == false) {  return $extras; }      

          if ( empty($extras['data']) ) {
            foreach ($fecha_array as $key1 => $val1) {

              $dia_bloqueado = true; $i_sueldo = 0;
              foreach ($sueldo['data'] as $key0 => $val_s) { // Buscamos si esta dentro de la semana
                if ( fecha_dentro_de_rango($val1['fecha_dia'], $val_s['fecha_desde'], $val_s['fecha_hasta'])  ) {
                  $dia_bloqueado = false; $i_sueldo = $val_s['sueldo_diario']; break;
                } 
              }

              $fechas_asistencia[] = [     
                'dia_regular'               => $dia_bloqueado,
                'idasistencia_trabajador'   => "",      
                'idresumen_q_s_asistencia' => "",   
                'fecha_asistencia'          => $val1['fecha_dia'],
                'nombre_dia'                => "",
                'sueldo_diario'             => $i_sueldo,
                'horas_normal_dia'          => "0",                
                'pago_normal_dia'           => "0",
                'horas_extras_dia'          => "0",
                'pago_horas_extras'         => "0",
                'descripcion_justificacion' => "",
                'doc_justificacion'         => "",
                'estado'                    => "",
                'estado_delete'             => "",               
              ];
            }        
          } else {
            $id_resumen = $extras['data']['idresumen_q_s_asistencia'] ;

            // extraemos la asistencia por trabajador
            $sql4 = "SELECT * FROM asistencia_trabajador  AS atr 
            WHERE atr.idresumen_q_s_asistencia = '$id_resumen' AND atr.estado = '1' AND atr.estado_delete = '1';";
            $asistencia = ejecutarConsultaArray($sql4);  if ($asistencia['status'] == false) {  return $asistencia; }      

            foreach ($fecha_array as $key2 => $val2) {

              $fecha_encontrado = false; $indice_encontrado = 0;
              foreach ($asistencia['data'] as $key3 => $val3) { if ($val2['fecha_dia'] == $val3['fecha_asistencia'] ) { $fecha_encontrado = true; $indice_encontrado = $key3; } }
              
              $dia_bloqueado = true; $i_sueldo = 0;
              foreach ($sueldo['data'] as $key0 => $val_s) { // Buscamos si esta dentro de la semana
                if ( fecha_dentro_de_rango($val2['fecha_dia'], $val_s['fecha_desde'], $val_s['fecha_hasta'])  ) {
                  $dia_bloqueado = false; $i_sueldo =  $val_s['sueldo_diario']; break;
                } 
              }

              if ($fecha_encontrado) {
                $fechas_asistencia[] = [     
                  'dia_regular'               => $dia_bloqueado,
                  'idasistencia_trabajador'   => $asistencia['data'][$indice_encontrado]['idasistencia_trabajador'],      
                  'idresumen_q_s_asistencia' => $asistencia['data'][$indice_encontrado]['idresumen_q_s_asistencia'],   
                  'fecha_asistencia'          => $asistencia['data'][$indice_encontrado]['fecha_asistencia'],
                  'nombre_dia'                => $asistencia['data'][$indice_encontrado]['nombre_dia'],
                  'sueldo_diario'             => $i_sueldo,
                  'horas_normal_dia'          => $asistencia['data'][$indice_encontrado]['horas_normal_dia'],
                  'pago_normal_dia'           => $asistencia['data'][$indice_encontrado]['pago_normal_dia'],
                  'horas_extras_dia'          => $asistencia['data'][$indice_encontrado]['horas_extras_dia'],
                  'pago_horas_extras'         => $asistencia['data'][$indice_encontrado]['pago_horas_extras'],
                  'descripcion_justificacion' => $asistencia['data'][$indice_encontrado]['descripcion_justificacion'],
                  'doc_justificacion'         => $asistencia['data'][$indice_encontrado]['doc_justificacion'],
                  'estado'                    => $asistencia['data'][$indice_encontrado]['estado'],
                  'estado_delete'             => $asistencia['data'][$indice_encontrado]['estado_delete'],               
                ];
              } else {
                $fechas_asistencia[] = [     
                  'dia_regular'               => $dia_bloqueado,
                  'idasistencia_trabajador'   => "",      
                  'idresumen_q_s_asistencia' => "",   
                  'fecha_asistencia'          => $val2['fecha_dia'],
                  'nombre_dia'                => "",
                  'sueldo_diario'             => $i_sueldo,
                  'horas_normal_dia'          => "0",
                  'pago_normal_dia'           => "0",
                  'horas_extras_dia'          => "0",
                  'pago_horas_extras'         => "0",
                  'descripcion_justificacion' => "",
                  'doc_justificacion'         => "",
                  'estado'                    => "",
                  'estado_delete'             => "",               
                ];
              }          
            }       
          }

          $data[] = [
            "idtrabajador_por_proyecto" => $key['idtrabajador_por_proyecto'],
            "nombre_ocupacion"          => $key['nombre_ocupacion'],
            "tipo_trabajador"           => $key['tipo_trabajador'],
            "nombres"                   => $key['nombres'],
            "tipo_documento"            => $key['tipo_documento'],
            "numero_documento"          => $key['numero_documento'],
            "imagen_perfil"             => $key['imagen_perfil'],
            "sueldo_mensual"            => $key['sueldo_mensual'],
            "sueldo_semanal"            => $key['sueldo_semanal'],
            "sueldo_diario"             => $key['sueldo_diario'],
            "sueldo_hora"               => $key['sueldo_hora'],
            "estado_trabajador"         => $key['estado'],
            "fecha_inicio_t"            => $key['fecha_inicio'],
            "fecha_fin_t"               => $key['fecha_fin'],
            "asistencia"                => $fechas_asistencia,

            'idresumen_q_s_asistencia'  => empty($extras['data']) ? "" : ( empty($extras['data']['idresumen_q_s_asistencia']) ? "" : $extras['data']['idresumen_q_s_asistencia']),
            'fecha_registro'            => empty($extras['data']) ? "" : ( empty($extras['data']['fecha_q_s_inicio']) ?        "" : $extras['data']['fecha_q_s_inicio']),
            'total_hn'                  => empty($extras['data']) ? 0  : ( empty($extras['data']['total_hn']) ?                0 : intval($extras['data']['total_hn']) ),
            'total_he'                  => empty($extras['data']) ? 0  : ( empty($extras['data']['total_he']) ?                0 : floatval($extras['data']['total_he']) ),
            'total_dias_asistidos_hn'   => empty($extras['data']) ? 0  : ( empty($extras['data']['total_dias_asistidos_hn']) ? 0 : floatval($extras['data']['total_dias_asistidos_hn']) ),
            'total_dias_asistidos_he'   => empty($extras['data']) ? 0  : ( empty($extras['data']['total_dias_asistidos_he']) ? 0 : floatval($extras['data']['total_dias_asistidos_he']) ),
            'sabatical'                 => empty($extras['data']) ? 0  : ( empty($extras['data']['sabatical']) ?               0 : floatval($extras['data']['sabatical']) ),
            'sabatical_manual_1'        => empty($extras['data']) ? "-": ( empty($extras['data']['sabatical_manual_1']) ?     "" : $extras['data']['sabatical_manual_1']),
            'sabatical_manual_2'        => empty($extras['data']) ? "-": ( empty($extras['data']['sabatical_manual_2']) ?     "" : $extras['data']['sabatical_manual_2']),
            'pago_parcial_hn'           => empty($extras['data']) ? 0  : ( empty($extras['data']['pago_parcial_hn']) ?         0 : floatval($extras['data']['pago_parcial_hn']) ),
            'pago_parcial_he'           => empty($extras['data']) ? 0  : ( empty($extras['data']['pago_parcial_he']) ?         0 : floatval($extras['data']['pago_parcial_he']) ),
            'adicional_descuento'       => empty($extras['data']) ? 0  : ( empty($extras['data']['adicional_descuento']) ?     0 : floatval($extras['data']['adicional_descuento']) ),
            'descripcion_descuento'     => empty($extras['data']) ? "" : ( empty($extras['data']['descripcion_descuento']) ?  "" : $extras['data']['descripcion_descuento']),
            'pago_quincenal_hn'         => empty($extras['data']) ? 0  : ( empty($extras['data']['pago_quincenal_hn']) ?       0 : floatval($extras['data']['pago_quincenal_hn']) ),
            'pago_quincenal_he'         => empty($extras['data']) ? 0  : ( empty($extras['data']['pago_quincenal_he']) ?       0 : floatval($extras['data']['pago_quincenal_he']) ),
            'estado_envio_contador'     => empty($extras['data']) ? "" : ( empty($extras['data']['estado_envio_contador']) ?  "" : $extras['data']['estado_envio_contador']),
          ];
        }              
      }          
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
    $sql = "SELECT rqsa.idtrabajador_por_proyecto, sqa.numero_q_s, sqa.fecha_q_s_inicio, sqa.fecha_q_s_fin,
    ast.idasistencia_trabajador, ast.idresumen_q_s_asistencia, ast.horas_normal_dia, ast.pago_normal_dia, ast.horas_extras_dia, ast.pago_horas_extras, ast.fecha_asistencia, ast.nombre_dia,
    t.nombres AS trabajador, t.tipo_documento, t.numero_documento, ast.estado
    FROM resumen_q_s_asistencia AS rqsa, asistencia_trabajador as ast, s_q_asistencia as sqa, trabajador_por_proyecto AS tpp, proyecto AS p, trabajador AS t
    WHERE rqsa.idresumen_q_s_asistencia = ast.idresumen_q_s_asistencia AND  rqsa.ids_q_asistencia = sqa.ids_q_asistencia AND rqsa.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto AND  tpp.idproyecto = p.idproyecto AND tpp.idtrabajador = t.idtrabajador AND
    rqsa.idtrabajador_por_proyecto = '$idtrabajador_x_proyecto' AND sqa.estado = '1' AND sqa.estado_delete = '1' AND rqsa.estado = '1' AND rqsa.estado_delete = '1' AND ast.estado = '1' AND ast.estado_delete = '1'
    ORDER BY  sqa.numero_q_s ASC;";
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

  public function tbla_qs_individual($id) {
    $sql = "SELECT rqsa.idresumen_q_s_asistencia, rqsa.idtrabajador_por_proyecto, sqa.numero_q_s, sqa.fecha_q_s_inicio, 
		sqa.fecha_q_s_fin, rqsa.total_hn, rqsa.total_he, (rqsa.total_dias_asistidos_hn + rqsa.total_dias_asistidos_he) as total_dias_asistidos, rqsa.sabatical, rqsa.sabatical_manual_1, 
		rqsa.sabatical_manual_2, rqsa.pago_parcial_hn, rqsa.pago_parcial_he, rqsa.adicional_descuento, rqsa.descripcion_descuento, 
		(rqsa.pago_quincenal_hn + rqsa.pago_quincenal_he) as pago_quincenal, rqsa.estado_envio_contador, rqsa.recibos_x_honorarios, rqsa.estado, p.fecha_pago_obrero, 
		t.nombres AS trabajador, t.tipo_documento, t.numero_documento
		FROM resumen_q_s_asistencia AS rqsa, s_q_asistencia as sqa, trabajador_por_proyecto AS tpp, proyecto AS p, trabajador AS t
		WHERE rqsa.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto AND rqsa.ids_q_asistencia = sqa.ids_q_asistencia AND tpp.idproyecto = p.idproyecto AND tpp.idtrabajador = t.idtrabajador AND
		 rqsa.idtrabajador_por_proyecto = '$id' AND sqa.estado = '1' AND sqa.estado_delete = '1' AND rqsa.estado = '1' AND rqsa.estado_delete = '1'
		ORDER BY  sqa.numero_q_s ASC;";
    return ejecutarConsulta($sql);
  }

  public function suma_qs_individual($idtrabajador_x_proyecto) {
    $sql = "SELECT  p.fecha_pago_obrero
		FROM  trabajador_por_proyecto AS tpp, proyecto AS p
		WHERE tpp.idproyecto = p.idproyecto 
		AND tpp.idtrabajador_por_proyecto = '$idtrabajador_x_proyecto';";
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
    $insert = ejecutarConsulta_retornarID($sql); if ( $insert['status'] == false) {return $insert; }

    //B I T A C O R A -------
    $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('resumen_q_s_asistencia', '".$insert['data']."', 'Crear adicional descuento', '".$_SESSION['idusuario']."')";
    $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }

    return $insert;
  }

  public function editar_detalle_adicionales($idresumen_q_s_asistencia, $idtrabajador_por_proyecto, $fecha_registro, $detalle_adicional) {
    $sql = "UPDATE resumen_q_s_asistencia 
		SET  idtrabajador_por_proyecto='$idtrabajador_por_proyecto', fecha_q_s_inicio='$fecha_registro', descripcion_descuento = '$detalle_adicional', user_updated = '".$_SESSION['idusuario']."'
		WHERE idresumen_q_s_asistencia = '$idresumen_q_s_asistencia';";
    $editar = ejecutarConsulta($sql);  if ( $editar['status'] == false) {return $editar; }

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
    $sql = "SELECT idproyecto, fecha_inicio_actividad, fecha_fin_actividad, plazo_actividad, fecha_pago_obrero, permanente_pago_obrero
		FROM proyecto WHERE idproyecto = '$id'";

    return ejecutarConsultaSimpleFila($sql);
  }

  public function editar_fechas_actividad($id_proyecto, $fecha_inicio_actividad, $fecha_fin_actividad, $plazo_actividad, $fecha_pago_obrero, $permanente_pago_obrero) {
    
    $sql = "UPDATE proyecto SET fecha_inicio_actividad='$fecha_inicio_actividad', fecha_fin_actividad= '$fecha_fin_actividad',
		plazo_actividad = '$plazo_actividad', fecha_pago_obrero ='$fecha_pago_obrero', permanente_pago_obrero ='$permanente_pago_obrero',  user_updated = '".$_SESSION['idusuario']."'
		WHERE idproyecto = '$id_proyecto'";
    $fecha = ejecutarConsulta($sql);  if ( $fecha['status'] == false) {return $fecha; }

    # enviamos a papelera a todas las S o Q
    $sql_p = "UPDATE s_q_asistencia SET estado='0' WHERE idproyecto='$id_proyecto'";
    $papelera = ejecutarConsulta($sql_p);  if ( $papelera['status'] == false) {return $papelera; }

    $fechaInicio      = new DateTime($fecha_inicio_actividad);
    $fechaFin         = new DateTime($fecha_fin_actividad);
    $num_quincena = 1; $dia_regular = 0; $estado_regular = false;

    $retorno = [];

    while ($fechaInicio <= $fechaFin) {
      $weekday_regular = $fechaInicio->format("w");
      if ($weekday_regular == "0") { $dia_regular = -1; 
      } else if ($weekday_regular == "1") { $dia_regular = -2;       
      } else if ($weekday_regular == "2") { $dia_regular = -3;         
      } else if ($weekday_regular == "3") { $dia_regular = -4;           
      } else if ($weekday_regular == "4") { $dia_regular = -5;           
      } else if ($weekday_regular == "5") { $dia_regular = -6;            
      } else if ($weekday_regular == "6") { $dia_regular = -7; }

      if ($fecha_pago_obrero == 'semanal') {
        $f_ii = $fechaInicio->format("Y-m-d");
        if ($estado_regular) { $fechaInicio->modify('+6 day'); } else { $sum_dia = 7 + $dia_regular; $fechaInicio->modify("+$sum_dia day"); $estado_regular = true; } 
        $f_ff = $fechaInicio->format("Y-m-d"); 
        #$retorno[] = [  'num_quincena'=>$num_quincena, 'f1'=>$f_ii, 'f2'=>$f_ff  ];
        $sql_0 = "SELECT ids_q_asistencia, idproyecto, numero_q_s, fecha_q_s_inicio, fecha_q_s_fin 
        FROM s_q_asistencia WHERE numero_q_s='$num_quincena' AND idproyecto = '$id_proyecto' ";
        $buscando = ejecutarConsultaSimpleFila($sql_0); if ( $buscando['status'] == false) {return $buscando; }

        if (empty($buscando['data'])) {
          $sql_1 = "INSERT INTO s_q_asistencia(idproyecto, numero_q_s, fecha_q_s_inicio, fecha_q_s_fin) 
          VALUES ('$id_proyecto','$num_quincena','$f_ii','$f_ff')";
          $creando = ejecutarConsulta($sql_1); if ( $creando['status'] == false) {return $creando; }
        } else {
          $id = $buscando['data']['ids_q_asistencia'];
          $sql_2 = "UPDATE s_q_asistencia SET fecha_q_s_inicio='$f_ii', fecha_q_s_fin='$f_ff', estado='1' WHERE ids_q_asistencia='$id'";
          $editando = ejecutarConsulta($sql_2); if ( $editando['status'] == false) {return $editando; }
        }         

        $fechaInicio->modify('+1 day'); # sumar 1 dias
      } else if ($fecha_pago_obrero == 'quincenal') {
        $f_ii = $fechaInicio->format("Y-m-d");
        if ($estado_regular) { $fechaInicio->modify('+13 day'); } else { $sum_dia = 14 + $dia_regular; $fechaInicio->modify("+$sum_dia day"); $estado_regular = true; } 
        $f_ff = $fechaInicio->format("Y-m-d");
        #$retorno[] = [  'num_quincena'=>$num_quincena, 'f1'=>$f_ii, 'f2'=>$f_ff  ];
        $sql_0 = "SELECT ids_q_asistencia, idproyecto, numero_q_s, fecha_q_s_inicio, fecha_q_s_fin 
        FROM s_q_asistencia WHERE numero_q_s='$num_quincena' AND idproyecto = '$id_proyecto' ";
        $buscando = ejecutarConsultaSimpleFila($sql_0); if ( $buscando['status'] == false) {return $buscando; }

        if (empty($buscando['data'])) {
          $sql_1 = "INSERT INTO s_q_asistencia(idproyecto, numero_q_s, fecha_q_s_inicio, fecha_q_s_fin) 
          VALUES ('$id_proyecto','$num_quincena','$f_ii','$f_ff')";
          $creando = ejecutarConsulta($sql_1); if ( $creando['status'] == false) {return $creando; }
        } else {
          $id = $buscando['data']['ids_q_asistencia'];
          $sql_2 = "UPDATE s_q_asistencia SET fecha_q_s_inicio='$f_ii', fecha_q_s_fin='$f_ff', estado='1' WHERE ids_q_asistencia='$id'";
          $editando = ejecutarConsulta($sql_2); if ( $editando['status'] == false) {return $editando; }
        }    
        $fechaInicio->modify('+1 day'); # sumar 1 dias
      }
      $num_quincena++;
    }

    //B I T A C O R A -------
    $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('proyecto', '".$id_proyecto."', 'Editar fechas de actividad', '".$_SESSION['idusuario']."')";
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
