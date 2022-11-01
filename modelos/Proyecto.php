<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Proyecto
{

  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  //Implementamos un método para insertar registros
  public function insertar( $tipo_documento, $numero_documento, $empresa, $nombre_proyecto, $nombre_codigo, $ubicacion, $actividad_trabajo, $empresa_acargo, $costo, $garantia, $fecha_inicio_actividad, $fecha_fin_actividad, $plazo_actividad, $fecha_inicio, $fecha_fin, $plazo, $dias_habiles, $doc1, $doc2, $doc3, $doc4, $doc5, $doc6, $fecha_pago_obrero, $fecha_valorizacion, $permanente_pago_obrero  ) {
    $doc7 = "";
    $doc8 = "";
    $calendario_error = "No hay feriados, agregue alguno";

    // prepoaramos la consulta del proyecto
    $sql = "INSERT INTO proyecto ( tipo_documento, numero_documento, empresa, nombre_proyecto, nombre_codigo, ubicacion, actividad_trabajo, idempresa_a_cargo, costo, garantia,  fecha_inicio, fecha_fin, plazo, dias_habiles, doc1_contrato_obra, doc2_entrega_terreno, doc3_inicio_obra, doc4_presupuesto, doc5_analisis_costos_unitarios, doc6_insumos, doc7_cronograma_obra_valorizad, doc8_certificado_habilidad_ing_residnt, fecha_pago_obrero, fecha_valorizacion, permanente_pago_obrero,user_created) 
		VALUES ('$tipo_documento', '$numero_documento', '$empresa', '$nombre_proyecto', '$nombre_codigo', '$ubicacion', '$actividad_trabajo', '$empresa_acargo', '$costo', '$garantia', '$fecha_inicio', '$fecha_fin', '$dias_habiles', '$plazo', '$doc1', '$doc2', '$doc3', '$doc4', '$doc5', '$doc6', '$doc7', '$doc8', '$fecha_pago_obrero', '$fecha_valorizacion', '$permanente_pago_obrero', '" . $_SESSION['idusuario'] . "');";

    $id_proyect = ejecutarConsulta_retornarID($sql); if ($id_proyect['status'] == false) { return $id_proyect; }

    //add registro en nuestra bitacora
    $sql2 = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('proyecto','" . $id_proyect['data'] . "','Registrar','" . $_SESSION['idusuario'] . "')";
    $bitacora1 = ejecutarConsulta($sql2);  if ( $bitacora1['status'] == false) {return $bitacora1; }

    // CREAMOS FECHAS DE VALORIZACION ----------------------------------------------------------
    if ($fecha_valorizacion == "quincenal") {
      
      $fechas_val = fechas_valorizacion_quincena($fecha_inicio, $fecha_fin,);
      
      foreach ($fechas_val as $key => $val) {
        $sql_2 = "INSERT INTO resumen_q_s_valorizacion(idproyecto, numero_q_s, fecha_inicio, fecha_fin, fecha_inicio_oculto, fecha_fin_oculto, monto_programado, monto_valorizado, monto_gastado, user_created) 
        VALUES ('".$id_proyect['data']."','".$val['num_q_s']."','".$val['fecha_inicio']."','".$val['fecha_fin']."','".$val['fecha_inicio']."','".$val['fecha_fin']."','0','0', '0', '".$_SESSION['idusuario']."')";
        $insertando = ejecutarConsulta_retornarID($sql_2); if ($insertando['status'] == false) { return $insertando; }

        //B I T A C O R A -------
        $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('resumen_q_s_valorizacion', '".$insertando['data']."', 'Crear registro', '".$_SESSION['idusuario']."')";
        $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }
      }
      
    } else if ($fecha_valorizacion == "mensual") {       
    
      $fechas_val = fechas_valorizacion_mensual($fecha_inicio, $fecha_fin);
      //console.log(fechas_btn);
  
      foreach ($fechas_val as $key => $val) {
        $sql_2 = "INSERT INTO resumen_q_s_valorizacion(idproyecto, numero_q_s, fecha_inicio, fecha_fin, fecha_inicio_oculto, fecha_fin_oculto, monto_programado, monto_valorizado, monto_gastado, user_created) 
        VALUES ('".$id_proyect['data']."','".$val['num_q_s']."','".$val['fecha_inicio']."','".$val['fecha_fin']."','".$val['fecha_inicio']."','".$val['fecha_fin']."','0','0', '0', '".$_SESSION['idusuario']."')";
        $insertando = ejecutarConsulta_retornarID($sql_2); if ($insertando['status'] == false) { return $insertando; }

        //B I T A C O R A -------
        $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('resumen_q_s_valorizacion', '".$insertando['data']."', 'Crear registro', '".$_SESSION['idusuario']."')";
        $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }
      }
    
    } else if ($fecha_valorizacion == "al finalizar") {        
  
      $sql_2 = "INSERT INTO resumen_q_s_valorizacion(idproyecto, numero_q_s, fecha_inicio, fecha_fin, fecha_inicio_oculto, fecha_fin_oculto, monto_programado, monto_valorizado, monto_gastado, user_created) 
      VALUES ('".$id_proyect['data']."','1','$fecha_inicio','$fecha_fin', '$fecha_inicio','$fecha_fin','0','0', '0', '".$_SESSION['idusuario']."')";
      $insertando = ejecutarConsulta_retornarID($sql_2); if ($insertando['status'] == false) { return $insertando; }

      //B I T A C O R A -------
      $sql_b = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('resumen_q_s_valorizacion', '".$insertando['data']."', 'Crear registro', '".$_SESSION['idusuario']."')";
      $bitacora = ejecutarConsulta($sql_b); if ( $bitacora['status'] == false) {return $bitacora; }
    }
    

    // extraemos todas fechas ------------------------------------------------------------------
    $sql2 = "SELECT titulo, descripcion, fecha_feriado, background_color, text_color FROM calendario WHERE estado = 1;";
    $proyecto = ejecutarConsultaArray($sql2);
    if ($proyecto['status'] == false) {return $proyecto;}

    if (!empty($proyecto['data'])) {

      $id_proyect = $id_proyect['id_tabla'];

      // insertamos las fechas al nuevo proyecto
      foreach ($proyecto['data'] as $indice => $key) {

        $titulo = $key['titulo'];  $descripcion = $key['descripcion']; $fecha_feriado = $key['fecha_feriado']; $background_color = $key['background_color']; $text_color = $key['text_color'];

        $sql3 = "INSERT INTO calendario_por_proyecto (idproyecto, titulo, descripcion, fecha_feriado, background_color, text_color,user_created)
				VALUES ('$id_proyect', '$titulo', '$descripcion', '$fecha_feriado', '$background_color', '$text_color','" . $_SESSION['idusuario'] . "')";
        $calendario_proyect = ejecutarConsulta_retornarID($sql3); if ($calendario_proyect['status'] == false) {return $calendario_proyect;}

        //add registro en nuestra bitacora
        $sql3_1 = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('calendario_por_proyecto','" . $calendario_proyect['data'] . "','Registramos la fechas de feriadod al nuevo proyecto','" . $_SESSION['idusuario'] . "')";
        $bitacora3_1 = ejecutarConsulta($sql3_1); if ( $bitacora3_1['status'] == false) {return $bitacora3_1; }
      }

      return $calendario_proyect;
    } else {
      return $proyecto;
    }

    // $sql2=	$tipo_documento.$numero_documento.$empresa.$nombre_proyecto.$ubicacion.$actividad_trabajo.$empresa_acargo.$costo.$fecha_inicio.$fecha_fin.$doc1.$doc2.$doc3;
  }

  //Implementamos un método para editar registros
  public function editar(  $idproyecto, $tipo_documento, $numero_documento, $empresa, $nombre_proyecto, $nombre_codigo, $ubicacion, $actividad_trabajo, $empresa_acargo, $costo, $garantia, $fecha_inicio_actividad, $fecha_fin_actividad, $plazo_actividad, $fecha_inicio, $fecha_fin, $plazo, $dias_habiles, $doc1, $doc2, $doc3, $doc4, $doc5, $doc6, $fecha_pago_obrero, $fecha_valorizacion, $permanente_pago_obrero  ) {
    $sql = "UPDATE proyecto SET tipo_documento = '$tipo_documento', numero_documento = '$numero_documento', 
			empresa = '$empresa', nombre_proyecto = '$nombre_proyecto', nombre_codigo = '$nombre_codigo',  ubicacion = '$ubicacion',
			actividad_trabajo = '$actividad_trabajo', idempresa_a_cargo = '$empresa_acargo', costo = '$costo',  garantia = '$garantia',  
			fecha_inicio = '$fecha_inicio', fecha_fin = '$fecha_fin', plazo = '$plazo', dias_habiles='$dias_habiles',
			doc1_contrato_obra = '$doc1', doc2_entrega_terreno = '$doc2', doc3_inicio_obra = '$doc3',
			doc4_presupuesto = '$doc4', doc5_analisis_costos_unitarios = '$doc5', doc6_insumos = '$doc6', 
			fecha_pago_obrero = '$fecha_pago_obrero', fecha_valorizacion = '$fecha_valorizacion', 
      permanente_pago_obrero = '$permanente_pago_obrero',
      user_updated= '" . $_SESSION['idusuario'] . "'
		WHERE idproyecto='$idproyecto'";
    $edit =  ejecutarConsulta($sql); if ( $edit['status'] == false) {return $edit; }

    //add registro en nuestra bitacora
    $sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('proyecto','$idproyecto','Editamos registro proyecto','" . $_SESSION['idusuario'] . "')";
    $bitacora = ejecutarConsulta($sql);  if ( $bitacora['status'] == false) {return $bitacora; }    

    return $edit;
  }

  //Implementamos un método para editar EDITAR EL DOC VALORIZACIONES
  public function editar_valorizacion($idproyecto, $doc7)  {
    $sql = "UPDATE proyecto SET excel_valorizaciones = '$doc7', user_updated= '" . $_SESSION['idusuario'] . "' WHERE idproyecto = '$idproyecto'";

    $edit= ejecutarConsulta($sql);
    if ( $edit['status'] == false) {return $edit; }

    //add registro en nuestra bitacora
    $sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('proyecto','$idproyecto','Editamos registro proyecto DOC VALORIZACIONES','" . $_SESSION['idusuario'] . "')";
    $bitacora = ejecutarConsulta($sql);
    
    if ( $bitacora['status'] == false) {return $bitacora; }

    return $edit;

  }

  //Implementamos un método para empezar proyecto
  public function empezar_proyecto($idproyecto)  {
    $sql = "UPDATE proyecto SET estado='1', user_updated= '" . $_SESSION['idusuario'] . "' WHERE idproyecto='$idproyecto'";

    $empesar_p= ejecutarConsulta($sql);
    if ( $empesar_p['status'] == false) {return $empesar_p; }

    //add registro en nuestra bitacora
    $sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('proyecto','$idproyecto','Empezar proyecto','" . $_SESSION['idusuario'] . "')";
    $bitacora = ejecutarConsulta($sql);
    
    if ( $bitacora['status'] == false) {return $bitacora; }

    return $empesar_p;

  }

  //Implementamos un método para terminar proyecto
  public function terminar_proyecto($idproyecto)  {
    $sql = "UPDATE proyecto SET estado='0', user_updated= '" . $_SESSION['idusuario'] . "'  WHERE idproyecto='$idproyecto'";

    $terminar_p = ejecutarConsulta($sql);

    if ( $terminar_p['status'] == false) {return $terminar_p; }

    //add registro en nuestra bitacora
    $sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('proyecto','$idproyecto','Terminar proyecto','" . $_SESSION['idusuario'] . "')";
    $bitacora = ejecutarConsulta($sql);
    
    if ( $bitacora['status'] == false) {return $bitacora; }

    return $bitacora;

  }

  //Implementamos un método para reniciar proyecto
  public function reiniciar_proyecto($idproyecto)  {
    $sql = "UPDATE proyecto SET estado='2', user_updated= '" . $_SESSION['idusuario'] . "' WHERE idproyecto='$idproyecto'";

    $terminar_p = ejecutarConsulta($sql);
    
    //add registro en nuestra bitacora
    $sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('proyecto','$idproyecto','Reniciar proyecto','" . $_SESSION['idusuario'] . "')";
    $bitacora = ejecutarConsulta($sql);
    
    if ( $bitacora['status'] == false) {return $bitacora; }

    return $terminar_p;
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idproyecto)  {
    $sql = "SELECT p.idproyecto, p.idempresa_a_cargo, p.tipo_documento, p.numero_documento, p.empresa, p.nombre_proyecto, p.nombre_codigo, p.ubicacion, p.
    actividad_trabajo, p.empresa_acargo, p.costo, p.garantia, p.fecha_inicio_actividad, p.fecha_fin_actividad, p.plazo_actividad, p.fecha_inicio, p.fecha_fin, p.
    plazo, p.dias_habiles, p.doc1_contrato_obra, p.doc2_entrega_terreno, p.doc3_inicio_obra, p.doc4_presupuesto, p.doc5_analisis_costos_unitarios, p.
    doc6_insumos, p.doc7_cronograma_obra_valorizad, p.doc8_certificado_habilidad_ing_residnt, p.feriado_domingo, p.fecha_pago_obrero, p.
    fecha_valorizacion, p.permanente_pago_obrero, p.estado, 
    ec.razon_social as ec_razon_social, ec.tipo_documento as ec_tipo_documento, ec.numero_documento as ec_numero_documento, ec.logo as ec_logo
    FROM proyecto as p, empresa_a_cargo as ec
    WHERE  p.idempresa_a_cargo = ec.idempresa_a_cargo AND p.idproyecto ='$idproyecto'";

    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para listar los registros
  public function tbla_principal($estado)  {
    # 0: terminado
    # 1: en proceso
    # 2: no empezado
    # 3: listar todos

    $sql_estado = "";
    if ($estado == 3) { $sql_estado = "p.estado >= 0 "; } else { $sql_estado = "p.estado = '$estado'"; }

    $sql = "SELECT p.idproyecto, p.idempresa_a_cargo, p.tipo_documento, p.numero_documento, p.empresa, p.nombre_proyecto, p.nombre_codigo, 
    p.ubicacion, p.actividad_trabajo, p.empresa_acargo, p.costo, p.garantia, p.fecha_inicio_actividad, p.fecha_fin_actividad, p.plazo_actividad, 
    p.fecha_inicio, p.fecha_fin, p.plazo, p.dias_habiles, p.doc1_contrato_obra, p.doc2_entrega_terreno, p.doc3_inicio_obra, p.doc4_presupuesto, 
    p.doc5_analisis_costos_unitarios, p.doc6_insumos, p.doc7_cronograma_obra_valorizad, p.doc8_certificado_habilidad_ing_residnt, 
    p.feriado_domingo, p.fecha_pago_obrero, p.fecha_valorizacion, p.permanente_pago_obrero, p.estado, p.estado_delete, p.created_at, 
    p.updated_at, p.user_trash, p.user_delete, p.user_created, p.user_updated, 
    ec.razon_social as ec_razon_social, ec.tipo_documento as ec_tipo_documento, ec.numero_documento as ec_numero_documento, ec.logo as ec_logo
    FROM proyecto as p, empresa_a_cargo as ec
    WHERE p.idempresa_a_cargo = ec.idempresa_a_cargo and $sql_estado ORDER BY p.idproyecto DESC;";
    return ejecutarConsulta($sql);
  }

  //Implementar un método para listar los registros de proyectos terminados
  public function listar_proyectos_terminados()  {
    $sql = "SELECT * FROM proyecto as p WHERE p.estado = 0;";
    return ejecutarConsulta($sql);
  }  

  // optenemos el total de PROYECTOS, PROVEEDORES, TRABAJADORES, SERVICIO
  public function tablero()  {
    $sql = "SELECT COUNT(p.empresa) AS cantidad_proyectos FROM proyecto AS p;";
    $sql2 = "SELECT COUNT(p.idproveedor) AS cantidad_proveedores FROM proveedor AS p WHERE estado_delete='1' AND p.estado = 1;";
    $sql3 = "SELECT COUNT(t.nombres) AS cantidad_trabajadores FROM trabajador AS t WHERE estado_delete='1' AND t.estado = 1;";
    $sql4 = "SELECT COUNT(idcompra_proyecto) AS cantidad_compra_insumos FROM compra_por_proyecto WHERE estado_delete='1' AND estado='1';";

    $data1 = ejecutarConsultaSimpleFila($sql);
    $data2 = ejecutarConsultaSimpleFila($sql2);
    $data3 = ejecutarConsultaSimpleFila($sql3);
    $data4 = ejecutarConsultaSimpleFila($sql4);

    $results = [
      "status" => true,
      "data" => [
        "proyecto"  => (empty($data1['data']) ? 0 : (empty($data1['data']['cantidad_proyectos']) ? 0 : $data1['data']['cantidad_proyectos'] ) ),
        "proveedor" => (empty($data2['data']) ? 0 : (empty($data2['data']['cantidad_proveedores']) ? 0 : $data2['data']['cantidad_proveedores'] ) ),
        "trabajador"=> (empty($data3['data']) ? 0 : (empty($data3['data']['cantidad_trabajadores']) ? 0 : $data3['data']['cantidad_trabajadores'] ) ),
        "servicio"  => (empty($data4['data']) ? 0 : (empty($data4['data']['cantidad_compra_insumos']) ? 0 : $data4['data']['cantidad_compra_insumos'] ) ),
      ],
    ];
    return $results;
  }

  // optenemos el total de PROYECTOS, PROVEEDORES, TRABAJADORES, SERVICIO
  public function box_proyecto()  {
    # 0: terminado
    # 1: en proceso
    # 2: no empezado
    # 3: listar todos

    $sql = "SELECT COUNT(empresa) AS cant_proceso FROM proyecto WHERE estado = 1;";
    $sql2 = "SELECT COUNT(empresa) AS cant_no_empezado FROM proyecto WHERE estado = 2;";
    $sql3 = "SELECT COUNT(empresa) AS cant_terminado FROM proyecto  WHERE estado = 0;";
    $sql4 = "SELECT COUNT(empresa) AS cant_todos FROM proyecto ;";

    $data1 = ejecutarConsultaSimpleFila($sql);
    $data2 = ejecutarConsultaSimpleFila($sql2);
    $data3 = ejecutarConsultaSimpleFila($sql3);
    $data4 = ejecutarConsultaSimpleFila($sql4);

    $results = [
      "status" => true,
      "data" => [
        "cant_proceso"      => (empty($data1['data']) ? 0 : (empty($data1['data']['cant_proceso']) ? 0 : $data1['data']['cant_proceso'] ) ),
        "cant_no_emmpezado" => (empty($data2['data']) ? 0 : (empty($data2['data']['cant_no_empezado']) ? 0 : $data2['data']['cant_no_empezado'] ) ),
        "cant_teminado"     => (empty($data3['data']) ? 0 : (empty($data3['data']['cant_terminado']) ? 0 : $data3['data']['cant_terminado'] ) ),
        "cant_todos"        => (empty($data4['data']) ? 0 : (empty($data4['data']['cant_todos']) ? 0 : $data4['data']['cant_todos'] ) ),
      ],
    ];
    return $results;
  }

  // optenemos el los feriados
  public function listar_feriados()  {
    $sql = "SELECT c.fecha_feriado, c.fecha_invertida FROM calendario AS c WHERE c.estado = 1;";

    return ejecutarConsultaArray($sql);
  }

  // optenemos el rango de feriados
  public function listar_rango_feriados($fecha_i, $fecha_f)  {
    $sql = "SELECT COUNT( c.fecha_feriado) AS count_feriado
		FROM calendario as c
		WHERE c.estado = 1 AND c.fecha_feriado BETWEEN '$fecha_i' AND '$fecha_f';";

    return ejecutarConsultaSimpleFila($sql);
  }

  // obtebnemos los DOCS para eliminar
  public function obtenerDocs($idproyecto)  {
    $sql = "SELECT doc1_contrato_obra, doc2_entrega_terreno, doc3_inicio_obra, doc4_presupuesto, doc5_analisis_costos_unitarios, doc6_insumos FROM proyecto WHERE idproyecto='$idproyecto'";

    return ejecutarConsultaSimpleFila($sql);
  }

  //CAPTURAR PERSONA  DE RENIEC
  public function datos_reniec($dni)  {
    $url = "https://dniruc.apisperu.com/api/v1/dni/" . $dni . "?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Imp1bmlvcmNlcmNhZG9AdXBldS5lZHUucGUifQ.bzpY1fZ7YvpHU5T83b9PoDxHPaoDYxPuuqMqvCwYqsM";
    //  Iniciamos curl
    $curl = curl_init();
    // Desactivamos verificación SSL
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    // Devuelve respuesta aunque sea falsa
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    // Especificamo los MIME-Type que son aceptables para la respuesta.
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Accept: application/json']);
    // Establecemos la URL
    curl_setopt($curl, CURLOPT_URL, $url);
    // Ejecutmos curl
    $json = curl_exec($curl);
    // Cerramos curl
    curl_close($curl);

    $respuestas = json_decode($json, true);

    return $respuestas;
  }

  //CAPTURAR PERSONA  DE RENIEC
  public function datos_sunat($ruc)  {
    $url = "https://dniruc.apisperu.com/api/v1/ruc/" . $ruc . "?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Imp1bmlvcmNlcmNhZG9AdXBldS5lZHUucGUifQ.bzpY1fZ7YvpHU5T83b9PoDxHPaoDYxPuuqMqvCwYqsM";
    //  Iniciamos curl
    $curl = curl_init();
    // Desactivamos verificación SSL
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    // Devuelve respuesta aunque sea falsa
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    // Especificamo los MIME-Type que son aceptables para la respuesta.
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Accept: application/json']);
    // Establecemos la URL
    curl_setopt($curl, CURLOPT_URL, $url);
    // Ejecutmos curl
    $json = curl_exec($curl);
    // Cerramos curl
    curl_close($curl);

    $respuestas = json_decode($json, true);

    return $respuestas;
  }
  
}

?>
