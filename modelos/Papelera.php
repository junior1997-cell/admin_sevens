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
          'nombre_proyecto'  => 'General',
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
          'nombre_proyecto'  => 'General',
          'created_at'      => $value2['created_at'],
          'updated_at'      => $value2['updated_at'],
        );
      }
    }

    $sql_3 = "SELECT cpt.idcarpeta, cpt.nombre, cpt.estado, cpt.created_at, cpt.updated_at,p.nombre_codigo
    FROM carpeta_plano_otro as cpt, proyecto as p
    WHERE cpt.estado = '0' AND cpt.estado_delete = '1' AND cpt.idproyecto = '$nube_idproyecto' AND cpt.idproyecto=p.idproyecto";
    $carpeta_plano_otro = ejecutarConsultaArray($sql_3);

    if (!empty($carpeta_plano_otro)) {
      foreach ($carpeta_plano_otro as $key => $value3) {
        $data[] = array(
          'nombre_tabla'    => 'carpeta_plano_otro',
          'nombre_id_tabla' => 'idcarpeta',
          'id_tabla'        => $value3['idcarpeta'],
          'modulo'          => 'Carpeta planos y Otros',
          'nombre_archivo'  => $value3['nombre'],
          'descripcion'     => '- - -',
          'nombre_proyecto'  => $value3['nombre_codigo'],
          'created_at'      => $value3['created_at'],
          'updated_at'      => $value3['updated_at'],
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
          'nombre_proyecto'  => 'General',
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
          'nombre_proyecto'  => 'General',
          'created_at'      => $value5['created_at'],
          'updated_at'      => $value5['updated_at'],
        );
      }
    }

    $sql_6 = "SELECT cx.idcomida_extra, cx.tipo_comprobante, cx.numero_comprobante, cx.descripcion, cx.estado, cx.created_at, cx.updated_at, p.nombre_codigo
    FROM comida_extra as cx, proyecto as p
    WHERE cx.estado = '0' AND cx.estado_delete = '1' AND cx.idproyecto ='$nube_idproyecto' AND  cx.idproyecto=p.idproyecto;";
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
          'nombre_proyecto'  => $value6['nombre_codigo'],
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
          'nombre_proyecto'  => 'General',
          'created_at'      => $value7['created_at'],
          'updated_at'      => $value7['updated_at'],
        );
      }
    }

    $sql_8 = "SELECT cpp.idcompra_proyecto, cpp.tipo_comprobante, cpp.serie_comprobante, cpp.descripcion,  cpp.estado, cpp.created_at, cpp.updated_at,p.nombre_codigo
    FROM compra_por_proyecto as cpp, proyecto as p
    WHERE cpp.estado = '0' AND cpp.estado_delete = '1' AND cpp.idproyecto = '$nube_idproyecto' AND  cpp.idproyecto=p.idproyecto";
    $compra_por_proyecto = ejecutarConsultaArray($sql_8);

    if (!empty($compra_por_proyecto)) {
      foreach ($compra_por_proyecto as $key => $value8) {
        $data[] = array(
          'nombre_tabla'    => 'compra_por_proyecto',
          'nombre_id_tabla' => 'idcompra_proyecto',
          'modulo'          => 'Compras',
          'id_tabla'        => $value8['idcompra_proyecto'],
          'nombre_archivo'  => $value8['tipo_comprobante'] . ' ─ ' . $value8['serie_comprobante'],
          'descripcion'     => $value8['descripcion'],
          'nombre_proyecto'  => $value8['nombre_codigo'],
          'created_at'      => $value8['created_at'],
          'updated_at'      => $value8['updated_at'],
        );
      }
    }

    $sql_9 = "SELECT f.idfactura, f.codigo, f.descripcion, f.estado, f.created_at, f.updated_at,p.nombre_codigo
    FROM factura AS f, maquinaria AS m, proyecto AS p
    WHERE f.idmaquinaria = m.idmaquinaria AND m.tipo = '1' AND f.estado = '0' AND f.estado_delete = '1' AND 
    f.idproyecto = '$nube_idproyecto' AND  f.idproyecto=p.idproyecto;";
    $factura_m = ejecutarConsultaArray($sql_9);

    if (!empty($factura_m)) {
      foreach ($factura_m as $key => $value9) {
        $data[] = array(
          'nombre_tabla'    => 'factura',
          'nombre_id_tabla' => 'idfactura',
          'modulo'          => 'Factura Servicio Maquina',
          'id_tabla'        => $value9['idfactura'],
          'nombre_archivo'  => 'Factura ─ ' . $value9['codigo'],
          'descripcion'     => $value9['descripcion'],
          'nombre_proyecto'  => $value9['nombre_codigo'],
          'created_at'      => $value9['created_at'],
          'updated_at'      => $value9['updated_at'],
        );
      }
    }

    $sql_10 = "SELECT f.idfactura, f.codigo, f.descripcion, f.estado, f.created_at, f.updated_at,p.nombre_codigo
    FROM factura AS f, maquinaria AS m, proyecto AS p
    WHERE f.idmaquinaria = m.idmaquinaria AND m.tipo = '2' AND f.estado = '0' AND f.estado_delete = '1' AND 
    f.idproyecto = '$nube_idproyecto' AND  f.idproyecto=p.idproyecto;";

    $factura_e = ejecutarConsultaArray($sql_10);

    if (!empty($factura_e)) {
      foreach ($factura_e as $key => $value10) {
        $data[] = array(
          'nombre_tabla'    => 'factura',
          'nombre_id_tabla' => 'idfactura',
          'modulo'          => 'Factura Servicio Equipo',
          'id_tabla'        => $value10['idfactura'],
          'nombre_archivo'  => 'Factura ─ ' . $value10['codigo'],
          'descripcion'     => $value10['descripcion'],
          'nombre_proyecto'  => $value10['nombre_codigo'],
          'created_at'      => $value10['created_at'],
          'updated_at'      => $value10['updated_at'],
        );
      }
    }

    $sql_11 = "SELECT fb.idfactura_break, fb.tipo_comprobante, fb.nro_comprobante, fb.descripcion, fb.estado, fb.created_at, fb.updated_at,p.nombre_codigo
    FROM factura_break AS fb, semana_break AS sb, proyecto AS p
    WHERE fb.idsemana_break = sb.idsemana_break AND fb.estado = '0' AND fb.estado_delete = '1' AND sb.idproyecto = '$nube_idproyecto'  AND sb.idproyecto = p.idproyecto";
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
          'nombre_proyecto'  => $value11['nombre_codigo'],
          'created_at'      => $value11['created_at'],
          'updated_at'      => $value11['updated_at'],
        );
      }
    }

    $sql_12 = "SELECT fp.idfactura_pension, fp.tipo_comprobante, fp.nro_comprobante, fp.descripcion, fp.estado,  fp.created_at, fp.updated_at, proy.nombre_codigo
    FROM factura_pension AS fp, pension AS p, proyecto AS proy
    WHERE fp.idpension = p.idpension AND fp.estado = '0' AND fp.estado_delete = '1' AND p.idproyecto = '$nube_idproyecto' AND p.idproyecto = proy.idproyecto;";
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
          'nombre_proyecto'  => $value12['nombre_codigo'],
          'created_at'      => $value12['created_at'],
          'updated_at'      => $value12['updated_at'],
        );
      }
    }

    $sql_13 = "SELECT h.idhospedaje, h.tipo_comprobante, h.numero_comprobante, h.descripcion, h.estado, h.estado_delete, h.created_at, h.updated_at, p.nombre_codigo
    FROM hospedaje as h, proyecto AS p 
    WHERE h.estado = '0' AND h.estado_delete = '1' AND h.idproyecto = '$nube_idproyecto' AND h.idproyecto = p.idproyecto;";
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
          'nombre_proyecto'  => $value13['nombre_codigo'],
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
          'nombre_proyecto'  => 'General',
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
          'nombre_proyecto'  => 'General',
          'created_at'      => $value15['created_at'],
          'updated_at'      => $value15['updated_at'],
        );
      }
    }
    
    $sql_16 = "SELECT os.idotro_servicio, os.tipo_comprobante, os.numero_comprobante, os.descripcion, os.estado, os.created_at, os.updated_at, p.nombre_codigo
    FROM otro_servicio as os, proyecto AS p 
    WHERE os.estado = '0' AND os.estado_delete = '1' AND  os.idproyecto  = '$nube_idproyecto' AND os.idproyecto = p.idproyecto;";
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
          'nombre_proyecto'  => $value16['nombre_codigo'],
          'created_at'      => $value16['created_at'],
          'updated_at'      => $value16['updated_at'],
        );
      }
    }

    $sql_17 = "SELECT pqso.idpagos_q_s_obrero, pqso.monto_deposito, t.nombres AS trabajador, pqso.descripcion, pqso.estado,  pqso.created_at, pqso.updated_at, p.nombre_codigo 
    FROM pagos_q_s_obrero AS pqso, resumen_q_s_asistencia AS rqsa, trabajador_por_proyecto AS tpp, trabajador AS t, proyecto AS p 
    WHERE pqso.idresumen_q_s_asistencia = rqsa.idresumen_q_s_asistencia AND rqsa.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto AND tpp.idtrabajador = t.idtrabajador AND
    tpp.idproyecto = '$nube_idproyecto' AND pqso.estado = '0' AND pqso.estado_delete = '1' AND tpp.idproyecto = p.idproyecto; ";
    $pagos_q_s_obrero = ejecutarConsultaArray($sql_17);

    if (!empty($pagos_q_s_obrero)) {
      foreach ($pagos_q_s_obrero as $key => $value17) {
        $data[] = array(
          'nombre_tabla'    => 'pagos_q_s_obrero',
          'nombre_id_tabla' => 'idpagos_q_s_obrero',
          'modulo'          => 'Pago Obrero',
          'id_tabla'        => $value17['idpagos_q_s_obrero'],
          'nombre_archivo'  => $value17['trabajador'] . ' ─ S/.' . number_format($value17['monto_deposito'], 2, '.', ','),
          'descripcion'     => $value17['descripcion'],
          'nombre_proyecto'  => $value17['nombre_codigo'],
          'created_at'      => $value17['created_at'],
          'updated_at'      => $value17['updated_at'],
        );
      }
    }

    $sql_18 = "SELECT pxma.idpagos_x_mes_administrador, t.nombres AS trabajador, pxma.monto, pxma.descripcion, pxma.estado, pxma.created_at, pxma.updated_at, p.nombre_codigo 
    FROM pagos_x_mes_administrador AS pxma, fechas_mes_pagos_administrador AS fmpa, trabajador_por_proyecto AS tpp, trabajador AS t, proyecto AS p 
    WHERE pxma.idfechas_mes_pagos_administrador = fmpa.idfechas_mes_pagos_administrador AND fmpa.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto 
    AND tpp.idtrabajador = t.idtrabajador AND pxma.estado = '0' AND pxma.estado_delete = '1' AND tpp.idproyecto = '$nube_idproyecto' AND tpp.idproyecto = p.idproyecto;";
    $pagos_x_mes_administrador = ejecutarConsultaArray($sql_18);

    if (!empty($pagos_x_mes_administrador)) {
      foreach ($pagos_x_mes_administrador as $key => $value18) {
        $data[] = array(
          'nombre_tabla'    => 'pagos_x_mes_administrador',
          'nombre_id_tabla' => 'idpagos_x_mes_administrador',
          'modulo'          => 'Pago Administrador',
          'id_tabla'        => $value18['idpagos_x_mes_administrador'],
          'nombre_archivo'  => $value18['trabajador'] . ' ─ S/.' .  number_format($value18['monto'], 2, '.', ',') ,
          'descripcion'     => $value18['descripcion'],
          'nombre_proyecto'  => $value18['nombre_codigo'],
          'created_at'      => $value18['created_at'],
          'updated_at'      => $value18['updated_at'],
        );
      }
    }

    $sql_19 = "SELECT idpago_af_general,beneficiario,monto,descripcion,created_at,updated_at
    FROM pago_af_general WHERE  estado='0' AND estado_delete='1'";
    $pago_af_general = ejecutarConsultaArray($sql_19);

    if (!empty($pago_af_general)) {
      foreach ($pago_af_general as $key => $value19) {
        $data[] = array(
          'nombre_tabla'    => 'pago_af_general',
          'nombre_id_tabla' => 'idpago_af_general',
          'modulo'          => 'Pago activo general',
          'id_tabla'        => $value19['idpago_af_general'],
          'nombre_archivo'  => $value19['beneficiario'] . ' ─ S/.' . number_format($value19['monto'], 2, '.', ',') ,
          'descripcion'     => $value19['descripcion'],
          'nombre_proyecto'  => 'General',
          'created_at'      => $value19['created_at'],
          'updated_at'      => $value19['updated_at'],
        );
      }
    }

    $sql_20 = "SELECT pc.idpago_compras, pc.beneficiario, pc.descripcion, pc.monto, pc.created_at, pc.updated_at, p.nombre_codigo
    FROM pago_compras as pc, compra_por_proyecto as cpp, proyecto as p
    WHERE pc.estado='0' AND pc.estado_delete='1' AND cpp.idproyecto='$nube_idproyecto' AND pc.idcompra_proyecto=cpp.idcompra_proyecto AND cpp.idproyecto=p.idproyecto";

    $pago_compras = ejecutarConsultaArray($sql_20);

    if (!empty($pago_compras)) {
      foreach ($pago_compras as $key => $value20) {
        $data[] = array(
          'nombre_tabla'    => 'pago_compras',
          'nombre_id_tabla' => 'idpago_compras',
          'modulo'          => 'Pago compras',
          'id_tabla'        => $value20['idpago_compras'],
          'nombre_archivo'  => $value20['beneficiario'] . ' ─ S/.' . number_format($value20['monto'], 2, '.', ',') ,
          'descripcion'     => $value20['descripcion'],
          'nombre_proyecto'  => $value20['nombre_codigo'],
          'created_at'      => $value20['created_at'],
          'updated_at'      => $value20['updated_at'],
        );
      }
    }
    
    $sql21 = "SELECT ps.idpago_servicio, ps.beneficiario, ps.descripcion, ps.monto, ps.created_at, ps.updated_at, p.nombre_codigo
    FROM pago_servicio AS ps, maquinaria AS m, proyecto AS p 
    WHERE ps.estado='0' AND ps.estado_delete=1 AND  m.idmaquinaria=ps.id_maquinaria  AND m.tipo=1 AND ps.idproyecto=p.idproyecto AND ps.idproyecto='$nube_idproyecto'";

    $pago_servicio_maquina = ejecutarConsultaArray($sql21);

    if (!empty($pago_servicio_maquina)) {
      foreach ($pago_servicio_maquina as $key => $value21) {
        $data[] = array(
          'nombre_tabla'    => 'pago_servicio',
          'nombre_id_tabla' => 'idpago_servicio',
          'modulo'          => 'Pago Servicio Maquinaria',
          'id_tabla'        => $value21['idpago_servicio'],
          'nombre_archivo'  => $value21['beneficiario'] . ' ─ S/.' . number_format($value21['monto'], 2, '.', ',') ,
          'descripcion'     => $value21['descripcion'],
          'nombre_proyecto'  => $value21['nombre_codigo'],
          'created_at'      => $value21['created_at'],
          'updated_at'      => $value21['updated_at'],
        );
      }
    }

    $sql22 = "SELECT ps.idpago_servicio, ps.beneficiario, ps.descripcion, ps.monto, ps.created_at, ps.updated_at, p.nombre_codigo
    FROM pago_servicio AS ps, maquinaria AS m, proyecto AS p 
    WHERE ps.estado='0' AND ps.estado_delete=1 AND  m.idmaquinaria=ps.id_maquinaria  AND m.tipo=2 AND ps.idproyecto=p.idproyecto AND ps.idproyecto='$nube_idproyecto'";

    $pago_servicio_equipo = ejecutarConsultaArray($sql22);

    if (!empty($pago_servicio_equipo)) {
      foreach ($pago_servicio_equipo as $key => $value22) {
        $data[] = array(
          'nombre_tabla'    => 'pago_servicio',
          'nombre_id_tabla' => 'idpago_servicio',
          'modulo'          => 'Pago Servicio Equipo',
          'id_tabla'        => $value22['idpago_servicio'],
          'nombre_archivo'  => $value22['beneficiario'] . ' ─ S/.' . number_format($value22['monto'], 2, '.', ',') ,
          'descripcion'     => $value22['descripcion'],
          'nombre_proyecto'  => $value22['nombre_codigo'],
          'created_at'      => $value22['created_at'],
          'updated_at'      => $value22['updated_at'],
        );
      }
    }

    $sql23 = "SELECT ps.idpago_subcontrato, ps.beneficiario, ps.monto, ps.descripcion, ps.created_at, ps.updated_at, p.nombre_codigo
    FROM pago_subcontrato AS ps, subcontrato as s, proyecto as p
    WHERE ps.estado='0' AND ps.estado_delete='1' AND ps.idsubcontrato=s.idsubcontrato AND s.idproyecto=p.idproyecto AND s.idproyecto='$nube_idproyecto'";

    $pago_subcontrato = ejecutarConsultaArray($sql23);

    if (!empty($pago_subcontrato)) {
      foreach ($pago_subcontrato as $key => $value23) {
        $data[] = array(
          'nombre_tabla'    => 'pago_subcontrato',
          'nombre_id_tabla' => 'idpago_subcontrato',
          'modulo'          => 'Pago sub contrato',
          'id_tabla'        => $value23['idpago_subcontrato'],
          'nombre_archivo'  => $value23['beneficiario'] . ' ─ S/.' . number_format($value23['monto'], 2, '.', ',') ,
          'descripcion'     => $value23['descripcion'],
          'nombre_proyecto'  => $value23['nombre_codigo'],
          'created_at'      => $value23['created_at'],
          'updated_at'      => $value23['updated_at'],
        );
      }
    }
    
    $sql24 = "SELECT ps.idplanilla_seguro, ps.tipo_comprobante, ps.numero_comprobante, ps.costo_parcial, ps.created_at, ps.updated_at, p.nombre_codigo
    FROM planilla_seguro as ps, proyecto as p
    WHERE ps.idproyecto=p.idproyecto AND ps.estado='0' AND ps.estado_delete='1' AND ps.idproyecto='$nube_idproyecto'";

    $planilla_seguro = ejecutarConsultaArray($sql24);

    if (!empty($planilla_seguro)) {
      foreach ($planilla_seguro as $key => $value24) {
        $data[] = array(
          'nombre_tabla'    => 'planilla_seguro',
          'nombre_id_tabla' => 'idplanilla_seguro',
          'modulo'          => 'Planillas y seguros',
          'id_tabla'        => $value24['idplanilla_seguro'],
          'nombre_archivo'  => $value24['tipo_comprobante'] .' : ' . $value24['numero_comprobante'] .' ─ S/. ' .number_format($value24['costo_parcial'], 2, '.', ',') ,
          'descripcion'     => '- - -',
          'nombre_proyecto'  => $value24['nombre_codigo'],
          'created_at'      => $value24['created_at'],
          'updated_at'      => $value24['updated_at'],
        );
      }
    }
    
    $sql25 = "SELECT po.idplano_otro, po.nombre, po.descripcion, po.created_at, po.updated_at, p.nombre_codigo , cpo.nombre as nombre_capeta
    FROM plano_otro AS po, carpeta_plano_otro AS cpo, proyecto AS p 
    WHERE  po.estado='0' AND po.estado_delete='1' AND po.id_carpeta=cpo.idcarpeta AND cpo.idproyecto=p.idproyecto AND  cpo.idproyecto='$nube_idproyecto'";

    $plano_otro = ejecutarConsultaArray($sql25);

    if (!empty($plano_otro)) {
      foreach ($plano_otro as $key => $value25) {
        $data[] = array(
          'nombre_tabla'    => 'plano_otro',
          'nombre_id_tabla' => 'idplano_otro',
          'modulo'          => 'Planos y otros',
          'id_tabla'        => $value25['idplano_otro'],
          'nombre_archivo'  => $value25['nombre'] .'  -- carpera ' . $value25['nombre_capeta'],
          'descripcion'     => $value25['descripcion'],
          'nombre_proyecto'  => $value25['nombre_codigo'],
          'created_at'      => $value25['created_at'],
          'updated_at'      => $value25['updated_at'],
        );
      }
    }

    $sql26 = "SELECT p.idproducto,p.nombre,p.descripcion,p.precio_total,p.created_at,p.updated_at
    FROM producto as p WHERE p.estado='0' AND p.estado_delete='1' AND p.idcategoria_insumos_af=1";

    $producto_insumo = ejecutarConsultaArray($sql26);

    if (!empty($producto_insumo)) {
      foreach ($producto_insumo as $key => $value26) {
        $data[] = array(
          'nombre_tabla'    => 'producto',
          'nombre_id_tabla' => 'idproducto',
          'modulo'          => 'Insumos',
          'id_tabla'        => $value26['idproducto'],
          'nombre_archivo'  => $value26['nombre'].' ─ S/. ' .number_format($value26['precio_total'], 2, '.', ',') ,
          'descripcion'     => $value26['descripcion'],
          'nombre_proyecto'  => 'General',
          'created_at'      => $value26['created_at'],
          'updated_at'      => $value26['updated_at'],
        );
      }
    }

    $sql27 = "SELECT p.idproducto,p.nombre,p.descripcion,p.precio_total,p.created_at,p.updated_at
    FROM producto as p WHERE p.estado='0' AND p.estado_delete='1' AND p.idcategoria_insumos_af!=1";

    $producto_insumo = ejecutarConsultaArray($sql27);

    if (!empty($producto_insumo)) {
      foreach ($producto_insumo as $key => $value27) {
        $data[] = array(
          'nombre_tabla'    => 'producto',
          'nombre_id_tabla' => 'idproducto',
          'modulo'          => 'Activos fijos',
          'id_tabla'        => $value27['idproducto'],
          'nombre_archivo'  => $value27['nombre'].' ─ S/. ' .number_format($value27['precio_total'], 2, '.', ',') ,
          'descripcion'     => $value27['descripcion'],
          'nombre_proyecto'  => 'General',
          'created_at'      => $value27['created_at'],
          'updated_at'      => $value27['updated_at'],
        );
      }
    }

    $sql28 = "SELECT idproveedor,razon_social,ruc,created_at,updated_at FROM proveedor WHERE estado='0'AND estado_delete=1";

    $proveedor = ejecutarConsultaArray($sql28);

    if (!empty($proveedor)) {
      foreach ($proveedor as $key => $value28) {
        $data[] = array(
          'nombre_tabla'    => 'proveedor',
          'nombre_id_tabla' => 'idproveedor',
          'modulo'          => 'Proveedores',
          'id_tabla'        => $value28['idproveedor'],
          'nombre_archivo'  => $value28['razon_social'].' ─ '.$value28['ruc'] ,
          'descripcion'     => '- - - ',
          'nombre_proyecto'  => 'General',
          'created_at'      => $value28['created_at'],
          'updated_at'      => $value28['updated_at'],
        );
      }
    }
        
    $sql29 = "SELECT s.idservicio, s.descripcion, s.costo_parcial, s.created_at, s.updated_at, m.nombre, p.nombre_codigo
    FROM servicio AS s, maquinaria AS m, proyecto AS p
    WHERE s.estado='0' AND s.estado_delete='1' AND s.idmaquinaria=m.idmaquinaria AND s.idproyecto=p.idproyecto AND m.tipo='1' AND s.idproyecto='$nube_idproyecto'";

    $servicio_maquina = ejecutarConsultaArray($sql29);

    if (!empty($servicio_maquina)) {
      foreach ($servicio_maquina as $key => $value29) {
        $data[] = array(
          'nombre_tabla'    => 'servicio',
          'nombre_id_tabla' => 'idservicio',
          'modulo'          => 'Servicio Maquinaria',
          'id_tabla'        => $value29['idservicio'],
          'nombre_archivo'  => $value29['nombre'] .'  ─ S/. ' .number_format($value29['costo_parcial'], 2, '.', ',') ,
          'descripcion'     => $value29['descripcion'],
          'nombre_proyecto'  => $value29['nombre_codigo'],
          'created_at'      => $value29['created_at'],
          'updated_at'      => $value29['updated_at'],
        );
      }
    }

    $sql30 = "SELECT s.idservicio, s.descripcion, s.costo_parcial, s.created_at, s.updated_at, m.nombre, p.nombre_codigo
    FROM servicio AS s, maquinaria AS m, proyecto AS p
    WHERE s.estado='0' AND s.estado_delete='1' AND s.idmaquinaria=m.idmaquinaria AND s.idproyecto=p.idproyecto AND m.tipo='2' AND s.idproyecto='$nube_idproyecto'";

    $servicio_equipo = ejecutarConsultaArray($sql30);

    if (!empty($servicio_equipo)) {
      foreach ($servicio_equipo as $key => $value30) {
        $data[] = array(
          'nombre_tabla'    => 'servicio',
          'nombre_id_tabla' => 'idservicio',
          'modulo'          => 'Servicio Equipos',
          'id_tabla'        => $value30['idservicio'],
          'nombre_archivo'  => $value30['nombre'] .'  ─ S/. ' .number_format($value30['costo_parcial'], 2, '.', ',') ,
          'descripcion'     => $value30['descripcion'],
          'nombre_proyecto'  => $value30['nombre_codigo'],
          'created_at'      => $value30['created_at'],
          'updated_at'      => $value30['updated_at'],
        );
      }
    }

    $sql31 = "SELECT sb.idsubcontrato, sb.tipo_comprobante,sb.numero_comprobante, sb.costo_parcial, sb.descripcion, sb.created_at, sb.updated_at, prov.razon_social, p.nombre_codigo
    FROM subcontrato as sb, proveedor as prov , proyecto as p
    WHERE sb.estado='0' AND sb.estado_delete=1 AND sb.idproveedor=prov.idproveedor AND sb.idproyecto=p.idproyecto AND sb.idproyecto='$nube_idproyecto'";

    $subcontrato = ejecutarConsultaArray($sql31);

    if (!empty($subcontrato)) {
      foreach ($subcontrato as $key => $value31) {
        $data[] = array(
          'nombre_tabla'    => 'subcontrato',
          'nombre_id_tabla' => 'idsubcontrato',
          'modulo'          => 'Sub contrato',
          'id_tabla'        => $value31['idsubcontrato'],
          'nombre_archivo'  => $value31['tipo_comprobante'] .': '. $value31['numero_comprobante'].'─ S/. ' .number_format($value31['costo_parcial'], 2, '.', ',') ,
          'descripcion'     => $value31['descripcion'],
          'nombre_proyecto'  => $value31['nombre_codigo'],
          'created_at'      => $value31['created_at'],
          'updated_at'      => $value31['updated_at'],
        );
      }
    }

    $sql32 = "SELECT idtipo_trabajador, nombre, created_at, updated_at FROM tipo_trabajador WHERE estado='0' AND estado_delete='1'";

    $tipo_trabajador = ejecutarConsultaArray($sql32);

    if (!empty($tipo_trabajador)) {
      foreach ($tipo_trabajador as $key => $value32) {
        $data[] = array(
          'nombre_tabla'    => 'tipo_trabajador',
          'nombre_id_tabla' => 'idtipo_trabajador',
          'modulo'          => 'Tipo Trabajador',
          'id_tabla'        => $value32['idtipo_trabajador'],
          'nombre_archivo'  => $value32['nombre'] ,
          'descripcion'     => '- - -',
          'nombre_proyecto'  => 'General',
          'created_at'      => $value32['created_at'],
          'updated_at'      => $value32['updated_at'],
        );
      }
    }

    $sql33 = "SELECT idtrabajador,nombres,ruc,created_at,updated_at,descripcion_expulsion FROM trabajador WHERE estado='0' AND estado_delete='1'";

    $trabajador = ejecutarConsultaArray($sql33);

    if (!empty($trabajador)) {
      foreach ($trabajador as $key => $value33) {
        $data[] = array(
          'nombre_tabla'    => 'trabajador',
          'nombre_id_tabla' => 'idtrabajador',
          'modulo'          => 'Trabajador',
          'id_tabla'        => $value33['idtrabajador'],
          'nombre_archivo'  => $value33['nombres'].'-'.$value33['ruc'] ,
          'descripcion'     =>  $value33['descripcion_expulsion'],
          'nombre_proyecto'  => 'General',
          'created_at'      => $value33['created_at'],
          'updated_at'      => $value33['updated_at'],
        );
      }
    }
    
    $sql34 = "SELECT tpp.idtrabajador_por_proyecto, tpp.desempenio, tpp.created_at, tpp.updated_at, t.nombres,p.nombre_codigo
    FROM trabajador_por_proyecto as tpp,  proyecto as p, trabajador as t
    WHERE tpp.estado='0' AND tpp.estado_delete=1 AND tpp.idproyecto=p.idproyecto AND tpp.idtrabajador=t.idtrabajador AND tpp.idproyecto='$nube_idproyecto'";

    $trabajador_por_proyecto = ejecutarConsultaArray($sql34);

    if (!empty($trabajador_por_proyecto)) {
      foreach ($trabajador_por_proyecto as $key => $value34) {
        $data[] = array(
          'nombre_tabla'    => 'trabajador_por_proyecto',
          'nombre_id_tabla' => 'idtrabajador_por_proyecto',
          'modulo'          => 'Trabajador por proyecto',
          'id_tabla'        => $value34['idtrabajador_por_proyecto'],
          'nombre_archivo'  => $value34['nombres'] .'- -'. $value34['desempenio'],
          'descripcion'     => '- - - ',
          'nombre_proyecto'  => $value34['nombre_codigo'],
          'created_at'      => $value34['created_at'],
          'updated_at'      => $value34['updated_at'],
        );
      }
    }

    
    $sql35 = "SELECT t.idtransporte, t.tipo_comprobante , t.numero_comprobante , t.descripcion , t.created_at , t.updated_at, p.nombre_codigo
    FROM transporte as t, proyecto as p
    WHERE t.estado='0' AND t.estado_delete='1' AND t.idproyecto=p.idproyecto AND t.idproyecto='$nube_idproyecto'";

    $transporte = ejecutarConsultaArray($sql35);

    if (!empty($transporte)) {
      foreach ($transporte as $key => $value35) {
        $data[] = array(
          'nombre_tabla'    => 'transporte',
          'nombre_id_tabla' => 'idtransporte',
          'modulo'          => 'Transporte',
          'id_tabla'        => $value35['idtransporte'],
          'nombre_archivo'  => $value35['tipo_comprobante'] .'  - -  '. $value35['numero_comprobante'],
          'descripcion'     => $value35['descripcion'],
          'nombre_proyecto'  => $value35['nombre_codigo'],
          'created_at'      => $value35['created_at'],
          'updated_at'      => $value35['updated_at'],
        );
      }
    }
    
    $sql36 = "SELECT idunidad_medida, nombre_medida, abreviacion, created_at, updated_at FROM unidad_medida WHERE  estado='0' AND estado_delete=1";

    $unidad_medida = ejecutarConsultaArray($sql36);

    if (!empty($unidad_medida)) {
      foreach ($unidad_medida as $key => $value36) {
        $data[] = array(
          'nombre_tabla'    => 'unidad_medida',
          'nombre_id_tabla' => 'idunidad_medida',
          'modulo'          => 'Unidad medida',
          'id_tabla'        => $value36['idunidad_medida'],
          'nombre_archivo'  => $value36['nombre_medida'].'-'.$value36['abreviacion'] ,
          'descripcion'     => ' - - - ',
          'nombre_proyecto'  => 'General',
          'created_at'      => $value36['created_at'],
          'updated_at'      => $value36['updated_at'],
        );
      }
    }
        
    $sql37 = "SELECT u.idusuario, u.cargo, u.created_at, u.updated_at, t.nombres FROM usuario as u, trabajador as t WHERE u.estado='0' AND u.estado_delete='1' AND u.idtrabajador=t.idtrabajador";

    $usuario = ejecutarConsultaArray($sql37);

    if (!empty($usuario)) {
      foreach ($usuario as $key => $value37) {
        $data[] = array(
          'nombre_tabla'    => 'usuario',
          'nombre_id_tabla' => 'idusuario',
          'modulo'          => 'Usuario',
          'id_tabla'        => $value37['idusuario'],
          'nombre_archivo'  => $value37['nombres'].'-'.$value37['cargo'] ,
          'descripcion'     => ' - - - ',
          'nombre_proyecto'  => 'General',
          'created_at'      => $value37['created_at'],
          'updated_at'      => $value37['updated_at'],
        );
      }
    }
  $sql38 = "SELECT of.idotra_factura, of.tipo_comprobante, of.numero_comprobante, of.costo_parcial, of.created_at, of.updated_at,p.razon_social
    FROM otra_factura as of, proveedor as p
    WHERE of.estado='0' AND of.estado_delete='1' AND of.idproveedor=p.idproveedor";

    $otra_factura = ejecutarConsultaArray($sql38);

    if (!empty($otra_factura)) {
      foreach ($otra_factura as $key => $value38) {
        $data[] = array(
          'nombre_tabla'    => 'otra_factura',
          'nombre_id_tabla' => 'idotra_factura',
          'modulo'          => 'Otras facturas',
          'id_tabla'        => $value38['idotra_factura'],
          'nombre_archivo'  => $value38['tipo_comprobante'].' - '.$value38['numero_comprobante'].'─ S/. ' .number_format($value38['costo_parcial'], 2, '.', ',') ,
          'descripcion'     => ' - - - ',
          'nombre_proyecto'  => 'General',
          'created_at'      => $value38['created_at'],
          'updated_at'      => $value38['updated_at'],
        );
      }
    }

    $sql39 = "SELECT rqsa.idresumen_q_s_asistencia, rqsa.numero_q_s, rqsa.fecha_q_s_inicio, rqsa.fecha_q_s_fin,rqsa.created_at, rqsa.updated_at, t.nombres, p.nombre_codigo
    FROM resumen_q_s_asistencia as rqsa, trabajador_por_proyecto as tpp, trabajador as t, proyecto as p 
    WHERE rqsa.estado='0' AND rqsa.estado_delete='1' AND tpp.idtrabajador_por_proyecto=rqsa.idtrabajador_por_proyecto AND  t.idtrabajador=tpp.idtrabajador AND p.idproyecto=tpp.idproyecto AND tpp.idproyecto='$nube_idproyecto'";

    $resumen_q_s_asistencia = ejecutarConsultaArray($sql39);

    if (!empty($resumen_q_s_asistencia)) {
      foreach ($resumen_q_s_asistencia as $key => $value39) {
        $data[] = array(
          'nombre_tabla'    => 'resumen_q_s_asistencia',
          'nombre_id_tabla' => 'idresumen_q_s_asistencia',
          'modulo'          => 'Asistencia por semanas',
          'id_tabla'        => $value39['idresumen_q_s_asistencia'],
          'nombre_archivo'  => $value39['nombres'].' - '.$value39['fecha_q_s_inicio'].' ─ '.$value39['fecha_q_s_fin'] ,
          'descripcion'     => '- - - ',
          'nombre_proyecto' => $value39['nombre_codigo'],
          'created_at'      => $value39['created_at'],
          'updated_at'      => $value39['updated_at'],
        );
      }
    }
    ///--------------------------------------
    $sql40 = "SELECT sb.idsemana_break,sb.numero_semana, sb.fecha_inicial, sb.fecha_final, sb.total, sb.created_at, sb.updated_at, p.nombre_codigo
    FROM semana_break as sb, proyecto as p 
    WHERE sb.estado=0 AND sb.estado_delete=1 AND sb.idproyecto=p.idproyecto AND sb.idproyecto='$nube_idproyecto'";

    $semana_break = ejecutarConsultaArray($sql40);

    if (!empty($semana_break)) {
      foreach ($semana_break as $key => $value40) {
        $data[] = array(
          'nombre_tabla'    => 'semana_break',
          'nombre_id_tabla' => 'idsemana_break',
          'modulo'          => 'Breaks por semanas',
          'id_tabla'        => $value40['idsemana_break'],
          'nombre_archivo'  => 'Semana Nº '.$value40['numero_semana'].' - '.$value40['fecha_inicial'].' ─ '.$value40['fecha_final'] ,
          'descripcion'     => '- - - ',
          'nombre_proyecto'  => $value40['nombre_codigo'],
          'created_at'      => $value40['created_at'],
          'updated_at'      => $value40['updated_at'],
        );
      }
    }

    $sql41 = "SELECT sp.idsemana_pension, sp.fecha_inicio, sp.fecha_fin, sp.numero_semana, sp.created_at, sp.updated_at, ser_p.nombre_servicio, p.nombre_codigo, prov.razon_social
    FROM semana_pension as sp, servicio_pension ser_p, pension as pen, proyecto as p, proveedor as prov
    WHERE sp.estado='0' AND sp.estado_delete='1' AND sp.idservicio_pension=ser_p.idservicio_pension AND ser_p.idpension=pen.idpension AND pen.idproyecto=p.idproyecto AND pen.idproveedor=prov.idproveedor AND pen.idproyecto='$nube_idproyecto'";

    $semana_pension = ejecutarConsultaArray($sql41);

    if (!empty($semana_pension)) {
      foreach ($semana_pension as $key => $value41) {
        $data[] = array(
          'nombre_tabla'    => 'semana_pension',
          'nombre_id_tabla' => 'idsemana_pension',
          'modulo'          => 'Pensión por semanas',
          'id_tabla'        => $value41['idsemana_pension'],
          'nombre_archivo'  => 'Semana Nº '.$value41['numero_semana'].' : '.$value41['fecha_inicio'].' ─ '.$value41['fecha_fin'].' - Servicio: '.$value41['nombre_servicio'].' -  Proveedor: '.$value41['razon_social'],
          'descripcion'     => ' - - - ',
          'nombre_proyecto'  => $value41['nombre_codigo'],
          'created_at'      => $value41['created_at'],
          'updated_at'      => $value41['updated_at'],
        );
      }
    }

    
    $sql42 = "SELECT ser_p.idservicio_pension, ser_p.nombre_servicio, ser_p.precio, ser_p.created_at, ser_p.updated_at, p.nombre_codigo, prov.razon_social
    FROM servicio_pension  as ser_p, pension as pen, proyecto as p, proveedor as prov 
    WHERE ser_p.estado='0' AND ser_p.estado_delete='1' AND ser_p.idpension=pen.idpension AND pen.idproyecto=p.idproyecto AND pen.idproveedor=prov.idproveedor AND pen.idproyecto='$nube_idproyecto'";

    $servicio_pension = ejecutarConsultaArray($sql42);

    if (!empty($servicio_pension)) {
      foreach ($servicio_pension as $key => $value42) {
        $data[] = array(
          'nombre_tabla'    => 'servicio_pension',
          'nombre_id_tabla' => 'idservicio_pension',
          'modulo'          => 'Servicio pensión',
          'id_tabla'        => $value42['idservicio_pension'],
          'nombre_archivo'  => 'Servicio: '.$value42['nombre_servicio'].'('.$value42['precio'].') - pertenece a: '.$value42['razon_social'], 
          'descripcion'     => '- - - ',
          'nombre_proyecto'  => $value42['nombre_codigo'],
          'created_at'      => $value42['created_at'],
          'updated_at'      => $value42['updated_at'],
        );
      }
    }

   $sql43 = "SELECT v.idvalorizacion,v.nombre, v.fecha_inicio, v.fecha_fin, v.created_at, v.updated_at, p.nombre_codigo FROM valorizacion as v, proyecto as p
   WHERE v.estado='0' AND v.estado_delete='1' AND v.idproyecto=p.idproyecto AND v.idproyecto='$nube_idproyecto'";

    $valorizacion = ejecutarConsultaArray($sql43);

    if (!empty($valorizacion)) {
      foreach ($valorizacion as $key => $value43) {
        $data[] = array(
          'nombre_tabla'    => 'valorizacion',
          'nombre_id_tabla' => 'idvalorizacion',
          'modulo'          => 'Valorización',
          'id_tabla'        => $value43['idvalorizacion'],
          'nombre_archivo'  => $value43['nombre'].'  -- documento que pertenece a las fechas: '.$value43['fecha_inicio'].'─'.$value43['fecha_fin'] ,
          'descripcion'     => '- - - ',
          'nombre_proyecto' => $value43['nombre_codigo'],
          'created_at'      => $value43['created_at'],
          'updated_at'      => $value43['updated_at'],
        );
      }
    }

    $sql44 = "SELECT fpa.idfechas_mes_pagos_administrador, fpa.fecha_inicial, fpa.fecha_final, fpa.monto_x_mes, fpa.created_at, fpa.updated_at, t.nombres, p.nombre_codigo
    FROM fechas_mes_pagos_administrador AS fpa, trabajador_por_proyecto as tpp, trabajador as t, proyecto as p
    WHERE fpa.estado='0' AND fpa.estado_delete='1' AND fpa.idtrabajador_por_proyecto=tpp.idtrabajador_por_proyecto AND tpp.idtrabajador=t.idtrabajador AND tpp.idproyecto=p.idproyecto AND tpp.idproyecto='$nube_idproyecto'";

    $fechas_mes_pagos_administrador = ejecutarConsultaArray($sql44);

    if (!empty($fechas_mes_pagos_administrador)) {
      foreach ($fechas_mes_pagos_administrador as $key => $value44) {
        $data[] = array(
          'nombre_tabla'    => 'fechas_mes_pagos_administrador',
          'nombre_id_tabla' => 'idfechas_mes_pagos_administrador',
          'modulo'          => 'Fechas mes pagos administrador',
          'id_tabla'        => $value44['idfechas_mes_pagos_administrador'],
          'nombre_archivo'  => $value44['nombres'].'('.$value44['monto_x_mes'].')  - '.$value44['fecha_inicial'].'─'.$value44['fecha_final'] ,
          'descripcion'     => '- - - ',
          'nombre_proyecto'  => $value44['nombre_codigo'],
          'created_at'      => $value44['created_at'],
          'updated_at'      => $value44['updated_at'],
        );
      }
    }



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
