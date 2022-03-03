<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Papelera
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  //INSERTAR - DEPOSTOS
  public function tabla_principal($nube_idproyecto)
  {
    $data = Array();   

    $sql_1 = "SELECT idbancos,  nombre, alias, created_at, updated_at, estado FROM bancos WHERE estado = '0' AND estado_delete= '1';";
    $banco = ejecutarConsultaArray($sql_1);

    if (!empty($banco)) {
      foreach ($banco as $key => $value1) {
        $data[] = array(
          'nombre_tabla'    => 'bancos',
          'nombre_id_tabla' => 'idbancos',
          'id_tabla'        => $value1['idbancos'],
          'modulo'          => 'Bancos',
          'nombre_archivo'  => $value1['nombre'] .' - ' . $value1['alias'],
          'descripcion'     => '- - -',
          'created_at'      => $value1['created_at'],
          'updated_at'      => $value1['updated_at'],
        );
      }
    }

    $sql_2 = "SELECT idcargo_trabajador,  nombre, estado, created_at, updated_at FROM cargo_trabajador WHERE estado = '0' AND estado_delete = '1';";
    $cargo_trabajador = ejecutarConsultaArray($sql_2);

    if (!empty($cargo_trabajador)) {
      foreach ($cargo_trabajador as $key => $value2) {
        $data[] = array(
          'nombre_tabla'    => 'cargo_trabajador',
          'nombre_id_tabla' => 'idcargo_trabajador',
          'id_tabla'        => $value2['idcargo_trabajador'],
          'modulo'          => 'Cargo Trabajdor',
          'nombre_archivo'  => $value2['nombre'],
          'descripcion'     => '- - -',
          'created_at'      => $value2['created_at'],
          'updated_at'      => $value2['updated_at'],
        );
      }
    }

    $sql_3 = "SELECT idcarpeta, nombre, estado, created_at, updated_at FROM carpeta_plano_otro WHERE estado = '0' AND estado_delete = '1' AND idproyecto = '$nube_idproyecto';";
    $carpeta_plano_otro = ejecutarConsultaArray($sql_3);

    if (!empty($carpeta_plano_otro)) {
      foreach ($carpeta_plano_otro as $key => $value3) {
        $data[] = array(
          'nombre_tabla'    => 'carpeta_plano_otro',
          'nombre_id_tabla' => 'idcarpeta',
          'id_tabla' => $value3['idcarpeta'],
          'modulo'          => 'Planos y Otros',
          'nombre_archivo' => $value3['nombre'],
          'descripcion' => '- - -',
          'created_at' => $value3['created_at'],
          'updated_at' => $value3['updated_at'],
        );
      }
    }
    
    $sql_4 = "SELECT idcategoria_insumos_af, nombre, estado, created_at, updated_at FROM categoria_insumos_af WHERE estado = '0' AND estado_delete = '1';";
    $categoria_insumos_af = ejecutarConsultaArray($sql_4);

    if (!empty($categoria_insumos_af)) {
      foreach ($categoria_insumos_af as $key => $value4) {
        $data[] = array(
          'nombre_tabla'    => 'categoria_insumos_af',
          'nombre_id_tabla' => 'idcategoria_insumos_af',
          'id_tabla'        => $value4['idcategoria_insumos_af'],
          'modulo'          => 'Clasificación de Productos',
          'nombre_archivo'  => $value4['nombre'],
          'descripcion'     => '- - -',
          'created_at'      => $value4['created_at'],
          'updated_at'      => $value4['updated_at'],
        );
      }
    }

    $sql_5 = "SELECT idcolor, nombre_color, estado, created_at, updated_at FROM color WHERE estado = '0' AND estado_delete = '1';";
    $color = ejecutarConsultaArray($sql_5);

    if (!empty($color)) {
      foreach ($color as $key => $value5) {
        $data[] = array(
          'nombre_tabla'    => 'color',
          'nombre_id_tabla' => 'idcolor',
          'id_tabla'        => $value5['idcolor'],
          'modulo'          => 'Color',
          'nombre_archivo'  => $value5['nombre_color'],
          'descripcion'     => '- - -',
          'created_at'      => $value5['created_at'],
          'updated_at'      => $value5['updated_at'],
        );
      }
    }

    $sql_6 = "SELECT idcomida_extra, tipo_comprobante, numero_comprobante, descripcion, estado, created_at, updated_at 
    FROM comida_extra WHERE estado = '0' AND estado_delete = '1' AND idproyecto = 1;";
    $comida_extra = ejecutarConsultaArray($sql_6);

    if (!empty($comida_extra)) {
      foreach ($comida_extra as $key => $value6) {
        $data[] = array(
          'nombre_tabla'    => 'comida_extra',
          'nombre_id_tabla' => 'idcomida_extra',
          'id_tabla'        => $value6['idcomida_extra'],
          'modulo'          => 'Comida Extras',
          'nombre_archivo'  => $value6['tipo_comprobante'] . ' ─ ' . $value6['numero_comprobante'],
          'descripcion'     => $value6['descripcion'],
          'created_at'      => $value6['created_at'],
          'updated_at'      => $value6['updated_at'],
        );
      }
    }

    $sql_7 = "SELECT idcompra_af_general, tipo_comprobante, serie_comprobante, descripcion, estado, created_at, updated_at 
    FROM compra_af_general WHERE estado = '0' AND estado_delete = '1'";
    $comida_extra = ejecutarConsultaArray($sql_7);

    if (!empty($compra_af_general)) {
      foreach ($compra_af_general as $key => $value7) {
        $data[] = array(
          'nombre_tabla'    => 'compra_af_general',
          'nombre_id_tabla' => 'idcompra_af_general',
          'id_tabla'        => $value7['idcompra_af_general'],
          'modulo'          => 'All Activos Fijo',
          'nombre_archivo'  => $value7['tipo_comprobante'] . ' ─ ' . $value7['serie_comprobante'],
          'descripcion'     => $value7['descripcion'],
          'created_at'      => $value7['created_at'],
          'updated_at'      => $value7['updated_at'],
        );
      }
    }

    $sql_8 = "SELECT idcompra_proyecto, tipo_comprovante, serie_comprovante, descripcion,  estado, created_at, updated_at 
    FROM compra_por_proyecto WHERE estado = '0' AND estado_delete = '1' AND idproyecto = '$nube_idproyecto';";
    $compra_por_proyecto = ejecutarConsultaArray($sql_8);

    if (!empty($compra_por_proyecto)) {
      foreach ($compra_por_proyecto as $key => $value8) {
        $data[] = array(
          'nombre_tabla'    => 'compra_por_proyecto',
          'nombre_id_tabla' => 'idcompra_proyecto',
          'modulo'          => 'Compras',
          'id_tabla'        => $value8['idcompra_proyecto'],
          'nombre_archivo'  => $value8['tipo_comprovante'] . ' ─ ' . $value8['serie_comprovante'],
          'descripcion'     => $value8['descripcion'],
          'created_at'      => $value8['created_at'],
          'updated_at'      => $value8['updated_at'],
        );
      }
    }

    $sql_9 = "SELECT f.idfactura, f.codigo, f.descripcion, f.estado, f.created_at, f.updated_at
    FROM factura AS f, maquinaria AS m 
    WHERE f.idmaquinaria = m.idmaquinaria AND m.tipo = '1' AND f.estado = '0' AND f.estado_delete = '1' AND f.idproyecto = '$nube_idproyecto';";
    $factura_m = ejecutarConsultaArray($sql_9);

    if (!empty($factura_m)) {
      foreach ($factura_m as $key => $value9) {
        $data[] = array(
          'nombre_tabla'    => 'factura',
          'nombre_id_tabla' => 'idfactura',
          'modulo'          => 'Servicio Maquina',
          'id_tabla'        => $value9['idfactura'],
          'nombre_archivo'  => 'Factura ─ ' . $value9['codigo'],
          'descripcion'     => $value9['descripcion'],
          'created_at'      => $value9['created_at'],
          'updated_at'      => $value9['updated_at'],
        );
      }
    }

    $sql_10 = "SELECT f.idfactura, f.codigo, f.descripcion, f.estado, f.created_at, f.updated_at
    FROM factura AS f, maquinaria AS m 
    WHERE f.idmaquinaria = m.idmaquinaria AND m.tipo = '2' AND f.estado = '0' AND f.estado_delete = '1' AND f.idproyecto = '$nube_idproyecto';";

    $factura_e = ejecutarConsultaArray($sql_10);

    if (!empty($factura_e)) {
      foreach ($factura_e as $key => $value10) {
        $data[] = array(
          'nombre_tabla'    => 'factura',
          'nombre_id_tabla' => 'idfactura',
          'modulo'          => 'Servicio Equipo',
          'id_tabla'        => $value10['idfactura'],
          'nombre_archivo'  => 'Factura ─ ' . $value10['codigo'],
          'descripcion'     => $value10['descripcion'],
          'created_at'      => $value10['created_at'],
          'updated_at'      => $value10['updated_at'],
        );
      }
    }

    $sql_11 = "SELECT fb.idfactura_break, fb.tipo_comprobante, fb.nro_comprobante, fb.descripcion, fb.estado, fb.created_at, fb.updated_at 
    FROM factura_break AS fb, semana_break AS sb 
    WHERE fb.idsemana_break = sb.idsemana_break AND fb.estado = '0' AND fb.estado_delete = '1' AND sb.idproyecto = '$nube_idproyecto';";
    $factura_break = ejecutarConsultaArray($sql_11);

    if (!empty($factura_break)) {
      foreach ($factura_break as $key => $value11) {
        $data[] = array(
          'nombre_tabla'    => 'factura',
          'nombre_id_tabla' => 'idfactura_break',
          'modulo'          => 'Breack',
          'id_tabla'        => $value11['idfactura_break'],
          'nombre_archivo'  => $value11['tipo_comprobante'] .' ─ ' . $value11['nro_comprobante'],
          'descripcion'     => $value11['descripcion'],
          'created_at'      => $value11['created_at'],
          'updated_at'      => $value11['updated_at'],
        );
      }
    }

    $sql_12 = "SELECT fp.idfactura_pension, fp.tipo_comprobante, fp.nro_comprobante, fp.descripcion, fp.estado,  fp.created_at, fp.updated_at
    FROM factura_pension AS fp, pension AS p
    WHERE fp.idpension = p.idpension AND fp.estado = '0' AND fp.estado_delete = '1' AND p.idproyecto = '$nube_idproyecto';";
    $factura_pension = ejecutarConsultaArray($sql_12);

    if (!empty($factura_pension)) {
      foreach ($factura_pension as $key => $value12) {
        $data[] = array(
          'nombre_tabla'    => 'factura_pension',
          'nombre_id_tabla' => 'idfactura_pension',
          'modulo'          => 'Pensión',
          'id_tabla'        => $value12['idfactura_pension'],
          'nombre_archivo'  => $value12['tipo_comprobante'] .' ─ ' . $value12['nro_comprobante'],
          'descripcion'     => $value12['descripcion'],
          'created_at'      => $value12['created_at'],
          'updated_at'      => $value12['updated_at'],
        );
      }
    }

    $sql_13 = "SELECT idhospedaje, tipo_comprobante, numero_comprobante, descripcion, estado, estado_delete, created_at, updated_at 
    FROM hospedaje 
    WHERE estado = '0' AND estado_delete = '1' AND idproyecto = '$nube_idproyecto';";
    $hospedaje = ejecutarConsultaArray($sql_13);

    if (!empty($hospedaje)) {
      foreach ($hospedaje as $key => $value13) {
        $data[] = array(
          'nombre_tabla'    => 'hospedaje',
          'nombre_id_tabla' => 'idhospedaje',
          'modulo'          => 'Hospedaje',
          'id_tabla'        => $value13['idhospedaje'],
          'nombre_archivo'  => $value13['tipo_comprobante'] .' ─ ' . $value13['numero_comprobante'],
          'descripcion'     => $value13['descripcion'],
          'created_at'      => $value13['created_at'],
          'updated_at'      => $value13['updated_at'],
        );
      }
    }

    $sql_14 = "SELECT m.idmaquinaria,  m.nombre, p.razon_social AS proveedor, m.tipo, m.estado, m.created_at, m.updated_at
    FROM maquinaria AS m, proveedor AS p
    WHERE m.idproveedor = p.idproveedor AND m.estado = '0' AND m.estado_delete = '1';";
    $maquinaria = ejecutarConsultaArray($sql_14);

    if (!empty($maquinaria)) {
      foreach ($maquinaria as $key => $value14) {
        $data[] = array(
          'nombre_tabla'    => 'maquinaria',
          'nombre_id_tabla' => 'idmaquinaria',
          'modulo'          => 'Maquinaria y Equipos',
          'id_tabla'        => $value14['idmaquinaria'],
          'nombre_archivo'  => $value14['nombre'] . ' ─ ' .  ($value14['tipo'] == '1' ? 'Maquina' : 'Equipo' ),
          'descripcion'     => $value14['proveedor'],
          'created_at'      => $value14['created_at'],
          'updated_at'      => $value14['updated_at'],
        );
      }
    }

    $sql_15 = "SELECT idocupacion, nombre_ocupacion, estado, created_at, updated_at FROM ocupacion WHERE estado  = '0' AND estado_delete = '1';";
    $ocupacion = ejecutarConsultaArray($sql_15);

    if (!empty($ocupacion)) {
      foreach ($ocupacion as $key => $value15) {
        $data[] = array(
          'nombre_tabla'    => 'ocupacion',
          'nombre_id_tabla' => 'idocupacion',
          'modulo'          => 'Ocupación',
          'id_tabla'        => $value15['idocupacion'],
          'nombre_archivo'  => $value15['nombre_ocupacion'],
          'descripcion'     => '- - -',
          'created_at'      => $value15['created_at'],
          'updated_at'      => $value15['updated_at'],
        );
      }
    }
    
    $sql_16 = "SELECT idotro_servicio, tipo_comprobante, numero_comprobante, descripcion, estado, created_at, updated_at 
    FROM otro_servicio WHERE estado = '0' AND estado_delete = '1' AND  idproyecto  = '$nube_idproyecto';";
    $otro_servicio = ejecutarConsultaArray($sql_16);

    if (!empty($otro_servicio)) {
      foreach ($otro_servicio as $key => $value16) {
        $data[] = array(
          'nombre_tabla'    => 'otro_servicio',
          'nombre_id_tabla' => 'idotro_servicio',
          'modulo'          => 'Otros Gastos',
          'id_tabla'        => $value16['idotro_servicio'],
          'nombre_archivo'  => $value16['tipo_comprobante'] . ' ─ ' . $value16['numero_comprobante'] ,
          'descripcion'     => $value16['descripcion'],
          'created_at'      => $value16['created_at'],
          'updated_at'      => $value16['updated_at'],
        );
      }
    }

    $sql_17 = "SELECT pqso.idpagos_q_s_obrero, pqso.monto_deposito, t.nombres AS trabajador, pqso.descripcion, pqso.estado,  pqso.created_at, pqso.updated_at 
    FROM pagos_q_s_obrero AS pqso, resumen_q_s_asistencia AS rqsa, trabajador_por_proyecto AS tpp, trabajador AS t
    WHERE pqso.idresumen_q_s_asistencia = rqsa.idresumen_q_s_asistencia AND rqsa.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto AND tpp.idtrabajador = t.idtrabajador AND
    tpp.idproyecto = '$nube_idproyecto' AND pqso.estado = '0' AND pqso.estado_delete = '1';";
    $pagos_q_s_obrero = ejecutarConsultaArray($sql_17);

    if (!empty($pagos_q_s_obrero)) {
      foreach ($pagos_q_s_obrero as $key => $value17) {
        $data[] = array(
          'nombre_tabla'    => 'pagos_q_s_obrero',
          'nombre_id_tabla' => 'idpagos_q_s_obrero',
          'modulo'          => 'Pago Obrero',
          'id_tabla'        => $value17['idpagos_q_s_obrero'],
          'nombre_archivo'  => $value17['trabajador'] . ' ─ S/.' . $value17['monto_deposito'] ,
          'descripcion'     => $value17['descripcion'],
          'created_at'      => $value17['created_at'],
          'updated_at'      => $value17['updated_at'],
        );
      }
    }

    $sql_18 = "SELECT pxma.idpagos_x_mes_administrador, t.nombres AS trabajador, pxma.monto, pxma.descripcion, pxma.estado, pxma.created_at, pxma.updated_at 
    FROM pagos_x_mes_administrador AS pxma, fechas_mes_pagos_administrador AS fmpa, trabajador_por_proyecto AS tpp, trabajador AS t
    WHERE pxma.idfechas_mes_pagos_administrador = fmpa.idfechas_mes_pagos_administrador AND fmpa.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto 
    AND tpp.idtrabajador = t.idtrabajador AND pxma.estado = '0' AND pxma.estado_delete = '1' AND tpp.idproyecto = '1';";
    $pagos_x_mes_administrador = ejecutarConsultaArray($sql_18);

    if (!empty($pagos_x_mes_administrador)) {
      foreach ($pagos_x_mes_administrador as $key => $value18) {
        $data[] = array(
          'nombre_tabla'    => 'pagos_x_mes_administrador',
          'nombre_id_tabla' => 'idpagos_x_mes_administrador',
          'modulo'          => 'Pago Administrador',
          'id_tabla'        => $value18['idpagos_x_mes_administrador'],
          'nombre_archivo'  => $value18['trabajador'] . ' ─ S/.' . $value18['monto'] ,
          'descripcion'     => $value18['descripcion'],
          'created_at'      => $value18['created_at'],
          'updated_at'      => $value18['updated_at'],
        );
      }
    }

    $sql_19 = "SELECT";
    $sql_20 = "SELECT";

    

    return $data;
  }

  //Desactivar DEPOSITO
  public function recuperar($nombre_tabla, $nombre_id_tabla, $id_tabla)
  {
    $sql = "UPDATE $nombre_tabla SET estado='1' WHERE $nombre_id_tabla ='$id_tabla'";
    return ejecutarConsulta($sql);
  }

  //Activar DEPOSITO
  public function eliminar_permanente($nombre_tabla, $nombre_id_tabla, $id_tabla)
  {
    $sql = "UPDATE $nombre_tabla SET estado_delete='0' WHERE $nombre_id_tabla ='$id_tabla'";
    return ejecutarConsulta($sql);
  }
}

?>
