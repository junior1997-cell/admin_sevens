<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class PagoAdministrador
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  // ══════════════════════════════════════ PRINCIPAL ══════════════════════════════════════
  //Implementar un método para listar los registros
  public function listar_tbla_principal($nube_idproyecto) {
    $data = [];
    $data_fechas = [];

    $sql_1 = "SELECT t.idtrabajador, t.nombres, t.tipo_documento, t.numero_documento, t.imagen_perfil, t.telefono,
		tpp.desempenio, tpp.sueldo_mensual, tpp.sueldo_diario, tpp.sueldo_hora, tpp.idtrabajador_por_proyecto, tpp.estado, 
		tpp.fecha_inicio, tpp.fecha_fin, tpp.cantidad_dias, tpp.cantidad_dias, o.nombre_ocupacion, tt.nombre AS tipo
		FROM trabajador_por_proyecto as tpp, ocupacion AS o, tipo_trabajador AS tt, trabajador as t, proyecto AS p
		WHERE tpp.idproyecto = p.idproyecto AND tpp.idproyecto = '$nube_idproyecto'   AND tpp.idtrabajador = t.idtrabajador
    AND t.idocupacion = o.idocupacion AND t.idtipo_trabajador = tt.idtipo_trabajador AND tt.nombre != 'Obrero' ORDER BY t.nombres ASC ;";
    $trabajador = ejecutarConsultaArray($sql_1); if ($trabajador['status'] == false) { return $trabajador; }
    
    foreach ($trabajador['data'] as $key => $value) {
      $id_trabajdor = $value['idtrabajador_por_proyecto'];

      $sql_2 = "SELECT fmpg.idtrabajador_por_proyecto, SUM(pxma.monto) deposito_por_trabajdor
      FROM fechas_mes_pagos_administrador AS fmpg, pagos_x_mes_administrador AS pxma
      WHERE fmpg.idtrabajador_por_proyecto = '$id_trabajdor' AND fmpg.idfechas_mes_pagos_administrador = pxma.idfechas_mes_pagos_administrador AND pxma.estado = '1' and pxma.estado_delete = '1';";

      $depositos = ejecutarConsultaSimpleFila($sql_2); if ($depositos['status'] == false) { return $depositos; }

      $cant_depo = empty($depositos['data']) ? 0 : (empty($depositos['data']['deposito_por_trabajdor']) ? 0 : floatval($depositos['data']['deposito_por_trabajdor']) ) ;
        

      $sql_3 = "SELECT idfechas_mes_pagos_administrador, idtrabajador_por_proyecto, fecha_inicial, fecha_final, nombre_mes, cant_dias_mes, cant_dias_laborables, sueldo_mensual, monto_x_mes, numero_comprobante, recibos_x_honorarios, estado
      FROM fechas_mes_pagos_administrador WHERE idtrabajador_por_proyecto = '$id_trabajdor' ;";
      $fechas_mes = ejecutarConsultaArray($sql_3); if ($fechas_mes['status'] == false) { return $fechas_mes; }
      
      foreach ($fechas_mes['data'] as $key => $element) {
        $id = $element['idfechas_mes_pagos_administrador'];

        $sql_4 = "SELECT SUM(monto) AS suma_monto_depositado FROM pagos_x_mes_administrador WHERE idfechas_mes_pagos_administrador ='$id' AND estado = '1' and estado_delete = '1';";
        $pagos_x_mes = ejecutarConsultaSimpleFila($sql_4); if ($pagos_x_mes['status'] == false) { return $pagos_x_mes; }

        $data_fechas[] = [
          "idfechas_mes_pagos_administrador" => $element['idfechas_mes_pagos_administrador'],
          "idtrabajador_por_proyecto" => $element['idtrabajador_por_proyecto'],
          "fecha_inicial" => $element['fecha_inicial'],
          "fecha_final" => $element['fecha_final'],
          "nombre_mes" => $element['nombre_mes'],
          "cant_dias_mes" => $element['cant_dias_mes'],
          "cant_dias_laborables" => $element['cant_dias_laborables'],
          "sueldo_mensual" => $element['sueldo_mensual'],
          "monto_x_mes" => $element['monto_x_mes'],
          "numero_comprobante" => $element['numero_comprobante'],
          "recibos_x_honorarios" => $element['recibos_x_honorarios'],
          "estado" => $element['estado'],
          "suma_monto_depositado" => ( empty($pagos_x_mes['data']) ? 0 : (empty($pagos_x_mes['data']['suma_monto_depositado']) ? 0 : floatval( $pagos_x_mes['data']['suma_monto_depositado']) )),
        ];
      }      

      $id = $value['idtrabajador'];
      $sql_4 = "SELECT cbt.idcuenta_banco_trabajador, cbt.idtrabajador, cbt.idbancos, cbt.cuenta_bancaria, cbt.cci, cbt.banco_seleccionado, b.nombre as banco
      FROM cuenta_banco_trabajador as cbt, bancos as b
      WHERE cbt.idbancos = b.idbancos AND cbt.banco_seleccionado ='1' AND cbt.idtrabajador='$id' ;";
      $bancos = ejecutarConsultaSimpleFila($sql_4); if ($bancos['status'] == false) { return $bancos; }

      $data[] = [
        'idtrabajador' => $value['idtrabajador'],
        'nombres' => $value['nombres'],
        'tipo_documento' => $value['tipo_documento'],
        'numero_documento' => $value['numero_documento'],
        'imagen_perfil' => $value['imagen_perfil'],
        'telefono' => $value['telefono'],
        'desempenio' => $value['desempenio'],
        'sueldo_mensual' => $value['sueldo_mensual'],
        'sueldo_diario' => $value['sueldo_diario'],
        'sueldo_hora' => $value['sueldo_hora'],
        'estado' => $value['estado'],
        'idtrabajador_por_proyecto' => $value['idtrabajador_por_proyecto'],
        'fecha_inicio' => $value['fecha_inicio'],
        'fecha_fin' => $value['fecha_fin'],
        'cantidad_dias' => $value['cantidad_dias'],
        'cantidad_dias' => $value['cantidad_dias'],
        'nombre_ocupacion' => $value['nombre_ocupacion'],
        'tipo' => $value['tipo'],

        'banco'           => (empty($bancos['data']) ? "": $bancos['data']['banco']), 
        'cuenta_bancaria' => (empty($bancos['data']) ? "" : $bancos['data']['cuenta_bancaria']), 
        'cci'             => (empty($bancos['data']) ? "" : $bancos['data']['cci']), 

        'cantidad_deposito' => $cant_depo,

        'data_fechas' => $data_fechas,
      ];
    }    

    return $retorno = ['status'=> true, 'message'=> 'todo oka bro', 'data'=> $data,];
  }

  // Obtenemos los totales
  public function mostrar_total_tbla_principal($id) {
    $sql_1 = "SELECT  SUM(pxma.monto) AS monto_total_depositado_x_proyecto
		FROM trabajador_por_proyecto AS tpp, fechas_mes_pagos_administrador AS fmpa, pagos_x_mes_administrador AS pxma
		WHERE tpp.idproyecto = '$id' AND fmpa.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto 
    AND fmpa.idfechas_mes_pagos_administrador = pxma.idfechas_mes_pagos_administrador AND pxma.estado = '1';";
    $monto_1 = ejecutarConsultaSimpleFila($sql_1); if ($monto_1['status'] == false) { return $monto_1; }

    $sql_1 = "SELECT SUM(tpp.sueldo_mensual) AS sueldo_mesual_x_proyecto
		FROM trabajador_por_proyecto AS tpp,  ocupacion AS o, tipo_trabajador AS tt
        WHERE tpp.idproyecto = '$id' AND  t.idocupacion = o.idocupacion AND t.idtipo_trabajador = tt.idtipo_trabajador AND tt.nombre != 'Obrero';";
    $monto_2 = ejecutarConsultaSimpleFila($sql_1); if ($monto_2['status'] == false) { return $monto_2; }

    $data = [
      'monto_total_depositado_x_proyecto' => (empty($monto_1['data']) ? 0 : $monto_1['data']['monto_total_depositado_x_proyecto']),
      'sueldo_mesual_x_proyecto'          => (empty($monto_2['data']) ? 0 : $monto_2['data']['sueldo_mesual_x_proyecto']),
    ];

    return $retorno = ['status'=> true, 'message'=> 'todo oka bro', 'data'=> $data,];
  }

  // ══════════════════════════════════════ TABLA MES ══════════════════════════════════════
  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar_fechas_mes($idtrabajador_x_proyecto) {
    $data_array = [];

    $sql = "SELECT idfechas_mes_pagos_administrador, idtrabajador_por_proyecto, fecha_inicial, fecha_final, nombre_mes, cant_dias_mes, cant_dias_laborables, sueldo_mensual, monto_x_mes, numero_comprobante, recibos_x_honorarios, estado
		FROM fechas_mes_pagos_administrador WHERE idtrabajador_por_proyecto = '$idtrabajador_x_proyecto' ;";
    $fechas_mes = ejecutarConsultaArray($sql); if ($fechas_mes['status'] == false) { return $fechas_mes; }
    
    foreach ($fechas_mes['data'] as $key => $value) {
      $id = $value['idfechas_mes_pagos_administrador'];

      $sql_2 = "SELECT SUM(monto) AS suma_monto_depositado FROM pagos_x_mes_administrador 
      WHERE idfechas_mes_pagos_administrador ='$id' AND estado = '1' AND estado_delete = '1';";
      $pagos_x_mes = ejecutarConsultaSimpleFila($sql_2); if ($pagos_x_mes['status'] == false) { return $pagos_x_mes; }

      $sql_3 = "SELECT COUNT(recibos_x_honorarios) as cant_rh FROM pagos_x_mes_administrador 
      WHERE idfechas_mes_pagos_administrador = '$id' AND estado = '1' AND estado_delete = '1' AND recibos_x_honorarios IS NOT NULL AND recibos_x_honorarios != '';";
      $cant_rh = ejecutarConsultaSimpleFila($sql_3); if ($cant_rh['status'] == false) { return $cant_rh; }

      $data_array[] = [
        "idfechas_mes_pagos_administrador" => $value['idfechas_mes_pagos_administrador'],
        "idtrabajador_por_proyecto" => $value['idtrabajador_por_proyecto'],
        "fecha_inicial" => $value['fecha_inicial'],
        "fecha_final" => $value['fecha_final'],
        "nombre_mes" => $value['nombre_mes'],
        "cant_dias_mes" => $value['cant_dias_mes'],
        "cant_dias_laborables" => $value['cant_dias_laborables'],
        "sueldo_mensual" => $value['sueldo_mensual'],
        "monto_x_mes" => $value['monto_x_mes'],
        "estado" => $value['estado'],
        "suma_monto_depositado" => ( empty($pagos_x_mes['data']) ? 0 : (empty($pagos_x_mes['data']['suma_monto_depositado']) ? 0 : floatval($pagos_x_mes['data']['suma_monto_depositado'])) ),
        "cant_rh"=> (empty($cant_rh['data']) ? 0 : (empty($cant_rh['data']['cant_rh']) ? 0 : floatval( $cant_rh['data']['cant_rh'])) )
      ];
    }   

    return $retorno = ['status'=> true, 'message'=> 'todo oka bro', 'data'=> $data_array,];
  }

  // ══════════════════════════════════════ PAGOS MES ══════════════════════════════════════
  //Implementamos un método para insertar registros
  public function insertar_pagos_x_mes( $idfechas_mes_pagos_administrador_pxm, $id_tabajador_x_proyecto_pxm, $fecha_inicial_pxm, $fecha_final_pxm, $mes_nombre_pxm, $dias_mes_pxm, $dias_regular_pxm, $sueldo_mensual_pxm, $monto_x_mes_pxm, $forma_pago, $cuenta_deposito, $monto, $fecha_pago, $descripcion, $numero_comprobante, $doc1, $doc2  ) {
    $id_fecha_mes = "";

    if (empty($idfechas_mes_pagos_administrador_pxm)) {
      $sql_1 = "INSERT INTO fechas_mes_pagos_administrador (idfechas_mes_pagos_administrador, idtrabajador_por_proyecto, fecha_inicial, fecha_final, nombre_mes, cant_dias_mes, cant_dias_laborables, sueldo_mensual, monto_x_mes )
			VALUES ('$idfechas_mes_pagos_administrador_pxm', '$id_tabajador_x_proyecto_pxm', '$fecha_inicial_pxm', '$fecha_final_pxm', '$mes_nombre_pxm', '$dias_mes_pxm', '$dias_regular_pxm', '$sueldo_mensual_pxm', '$monto_x_mes_pxm' )";

      $id_fecha_mes = ejecutarConsulta_retornarID($sql_1); if ($id_fecha_mes['status'] == false) { return $id_fecha_mes; }
      $id_fecha_mes = $id_fecha_mes['data'];
    } else {
      $id_fecha_mes = $idfechas_mes_pagos_administrador_pxm;
    }

    $sql_2 = "INSERT INTO pagos_x_mes_administrador ( idfechas_mes_pagos_administrador, cuenta_deposito, forma_de_pago, monto, fecha_pago, numero_comprobante,  baucher, recibos_x_honorarios, descripcion)
		VALUES ('$id_fecha_mes', '$cuenta_deposito', '$forma_pago', '$monto', '$fecha_pago', '$numero_comprobante', '$doc1', '$doc2', '$descripcion')";

    $pagos_x_mes = ejecutarConsulta($sql_2); if ($pagos_x_mes['status'] == false) { return $pagos_x_mes; }

    return $retorno = ['status'=> true, 'message'=> 'todo oka bro', 'data'=> [], "id_tabla" => $id_fecha_mes];
  }

  //Implementamos un método para editar registros
  public function editar_pagos_x_mes( $idpagos_x_mes_administrador, $idfechas_mes_pagos_administrador_pxm, $id_tabajador_x_proyecto_pxm, $fecha_inicial_pxm, $fecha_final_pxm, $mes_nombre_pxm, $dias_mes_pxm, $dias_regular_pxm, $sueldo_mensual_pxm, $monto_x_mes_pxm, $forma_pago, $cuenta_deposito, $monto, $fecha_pago, $descripcion, $numero_comprobante, $doc1, $doc2 ) {
    $id_fecha_mes = "";

    if (empty($idfechas_mes_pagos_administrador_pxm)) {
      $sql_1 = "INSERT INTO fechas_mes_pagos_administrador (idfechas_mes_pagos_administrador, idtrabajador_por_proyecto, fecha_inicial, fecha_final, nombre_mes, cant_dias_mes, cant_dias_laborables, sueldo_mensual, monto_x_mes)
			VALUES ('$idfechas_mes_pagos_administrador_pxm', '$id_tabajador_x_proyecto_pxm', '$fecha_inicial_pxm', '$fecha_final_pxm', '$mes_nombre_pxm', '$dias_mes_pxm', '$dias_regular_pxm', '$sueldo_mensual_pxm', '$monto_x_mes_pxm' )";

      $id_fecha_mes = ejecutarConsulta_retornarID($sql_1);if ($id_fecha_mes['status'] == false) { return $id_fecha_mes; }
      $id_fecha_mes = $id_fecha_mes['data'];
    } else {
      $id_fecha_mes = $idfechas_mes_pagos_administrador_pxm;
    }

    $sql_2 = "UPDATE pagos_x_mes_administrador SET  idfechas_mes_pagos_administrador='$id_fecha_mes', cuenta_deposito='$cuenta_deposito', 
		forma_de_pago='$forma_pago', monto='$monto', fecha_pago='$fecha_pago', numero_comprobante='$numero_comprobante', baucher='$doc1', recibos_x_honorarios='$doc2', descripcion='$descripcion'
		WHERE idpagos_x_mes_administrador='$idpagos_x_mes_administrador'";
    $pagos_x_mes = ejecutarConsulta($sql_2); if ($pagos_x_mes['status'] == false) { return $pagos_x_mes; }

    return $retorno = ['status'=> true, 'message'=> 'todo oka bro', 'data'=> [], "id_tabla" => $id_fecha_mes];
  }    

  //Implementar un método para mostrar los datos de un registro a modificar
  public function listar_pagos_x_mes($idfechas_mes_pagos) {
    $sql = "SELECT idpagos_x_mes_administrador, idfechas_mes_pagos_administrador, cuenta_deposito, forma_de_pago, monto, fecha_pago, baucher, recibos_x_honorarios, descripcion, estado
		FROM pagos_x_mes_administrador WHERE idfechas_mes_pagos_administrador = '$idfechas_mes_pagos' AND estado = '1' AND estado_delete = '1'";

    return ejecutarConsulta($sql);
  }

  // Mostramos datos: pagos por mes, para editar
  public function mostrar_pagos_x_mes($id) {
    $sql = "SELECT idpagos_x_mes_administrador, idfechas_mes_pagos_administrador, cuenta_deposito, forma_de_pago, monto, fecha_pago, baucher, descripcion 
		FROM pagos_x_mes_administrador WHERE idpagos_x_mes_administrador = '$id';";

    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementamos un método para desactivar
  public function desactivar_pago_x_mes($id) {
    $sql = "UPDATE pagos_x_mes_administrador SET estado='0' WHERE idpagos_x_mes_administrador='$id'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para activar
  public function activar_pago_x_mes($id) {
    $sql = "UPDATE pagos_x_mes_administrador SET estado='1' WHERE idpagos_x_mes_administrador='$id'";
    return ejecutarConsulta($sql);
  }

  // obtebnemos los "BAUCHER DE DEPOSITOS" para eliminar
  public function obtenerDocs($id) {
    $sql = "SELECT baucher FROM pagos_x_mes_administrador WHERE idpagos_x_mes_administrador = '$id'";
    return ejecutarConsulta($sql);
  }

  // obtebnemos los "RECIBO X HONORARIO" para eliminar
  public function obtenerDocs2($id) {
    $sql = "SELECT recibos_x_honorarios FROM pagos_x_mes_administrador WHERE idpagos_x_mes_administrador='$id'";
    return ejecutarConsulta($sql);
  }

  // obtebnemos los "RECIBO X HONORARIO" para eliminar
  public function tabla_recibo_por_honorario($id) {
    $sql = "SELECT idpagos_x_mes_administrador, idfechas_mes_pagos_administrador,  monto, fecha_pago, tipo_comprobante, numero_comprobante, 
    recibos_x_honorarios, baucher, descripcion 
    FROM pagos_x_mes_administrador WHERE idfechas_mes_pagos_administrador = '$id' AND estado = '1' AND estado_delete = '1'";
    return ejecutarConsulta($sql);
  }

  // ══════════════════════════════════════ OTROS ══════════════════════════════════════
  //Implementamos un método para insertar registros
  public function insertar_recibo_x_honorario($id_tabajador_x_proyecto_rh, $fecha_inicial_rh, $fecha_final_rh, $mes_nombre_rh, $dias_mes_rh, $dias_regular_rh, $sueldo_mensual_rh, $monto_x_mes_rh, $numero_comprobante_rh, $doc2) {
    $sql = "INSERT INTO fechas_mes_pagos_administrador (idtrabajador_por_proyecto, fecha_inicial, fecha_final, nombre_mes, cant_dias_mes, cant_dias_laborables, sueldo_mensual, monto_x_mes, numero_comprobante, recibos_x_honorarios)
		VALUES ('$id_tabajador_x_proyecto_rh', '$fecha_inicial_rh', '$fecha_final_rh', '$mes_nombre_rh', '$dias_mes_rh', '$dias_regular_rh', '$sueldo_mensual_rh', '$monto_x_mes_rh', '$numero_comprobante_rh', '$doc2')";

    return ejecutarConsulta($sql);
  }

  //Implementamos un método para editar registros
  public function editar_recibo_x_honorario($idfechas_mes_pagos_administrador_rh, $id_tabajador_x_proyecto_rh, $fecha_inicial_rh, $fecha_final_rh, $mes_nombre_rh, $dias_mes_rh, $dias_regular_rh, $sueldo_mensual_rh, $monto_x_mes_rh, $numero_comprobante_rh, $doc2) {
    $sql = "UPDATE fechas_mes_pagos_administrador SET idtrabajador_por_proyecto='$id_tabajador_x_proyecto_rh', fecha_inicial='$fecha_inicial_rh', fecha_final='$fecha_final_rh', nombre_mes='$mes_nombre_rh', cant_dias_mes='$dias_mes_rh',
		 cant_dias_laborables='$dias_regular_rh', sueldo_mensual='$sueldo_mensual_rh',	monto_x_mes='$monto_x_mes_rh', numero_comprobante='$numero_comprobante_rh', recibos_x_honorarios='$doc2' 
		WHERE idfechas_mes_pagos_administrador='$idfechas_mes_pagos_administrador_rh'";

    return ejecutarConsulta($sql);
  }

  // ══════════════════════════════════════ VER TODOS LO PAGOS ══════════════════════════════════════
  public function ver_todos_pagos($idtrabajador) {
    $sql = "SELECT pxma.idpagos_x_mes_administrador as idpago, fmpa.fecha_inicial, fmpa.fecha_final, fmpa.nombre_mes, fmpa.monto_x_mes, 
    pxma.monto as monto_pago, pxma.fecha_pago, pxma.tipo_comprobante, pxma.numero_comprobante, pxma.estado
    FROM trabajador_por_proyecto as tpp
    INNER JOIN fechas_mes_pagos_administrador as fmpa ON fmpa.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto
    INNER JOIN pagos_x_mes_administrador as pxma ON pxma.idfechas_mes_pagos_administrador = fmpa.idfechas_mes_pagos_administrador
    where tpp.idtrabajador_por_proyecto = '$idtrabajador'
    ORDER BY pxma.fecha_pago ASC;";
     return ejecutarConsulta($sql);
  }
  
}

?>
