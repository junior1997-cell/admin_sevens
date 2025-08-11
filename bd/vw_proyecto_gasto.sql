SELECT p.idproyecto, LPAD(p.idproyecto, 3, '0') as codproyecto, p.idempresa_a_cargo, p.tipo_documento, p.numero_documento, p.empresa, 
CASE WHEN LENGTH(p.empresa) <= 15 THEN p.empresa ELSE CONCAT(LEFT(p.empresa, 15), '...') END AS empresa_recorte_20, 
p.nombre_proyecto, p.nombre_codigo, CASE WHEN LENGTH(p.nombre_codigo) <= 15 THEN p.nombre_codigo ELSE CONCAT(LEFT(p.nombre_codigo, 15), '...') END AS nombre_codigo_recorte_20, 
p.ubicacion, p.actividad_trabajo, p.empresa_acargo, p.costo, p.garantia, p.fecha_inicio_actividad, p.fecha_fin_actividad, p.plazo_actividad, 
p.fecha_inicio, p.fecha_fin, p.plazo, p.dias_habiles, p.doc1_contrato_obra, p.doc2_entrega_terreno, p.doc3_inicio_obra, p.doc4_presupuesto, 
p.doc5_analisis_costos_unitarios, p.doc6_insumos, p.doc7_cronograma_obra_valorizad, p.doc8_certificado_habilidad_ing_residnt, doc9_acta_conformidad, doc10_contrato_adenda,
p.feriado_domingo, p.fecha_pago_obrero, p.fecha_valorizacion, p.permanente_pago_obrero, p.estado, p.estado_delete, p.created_at, 
p.updated_at, p.user_trash, p.user_delete, p.user_created, p.user_updated, 
ec.razon_social as ec_razon_social, ec.tipo_documento as ec_tipo_documento, ec.numero_documento as ec_numero_documento, ec.logo as ec_logo,
ggpp.gasto
FROM proyecto as p
INNER JOIN empresa_a_cargo as ec on ec.idempresa_a_cargo = p.idempresa_a_cargo
INNER JOIN (

    select gp.idproyecto, SUM( gp.gasto) as gasto  from (

        SELECT  cpp.idproyecto,  SUM(cpp.total) AS gasto
        FROM compra_por_proyecto as cpp
        WHERE cpp.estado='1' AND cpp.estado_delete='1' GROUP BY cpp.idproyecto

        UNION ALL

        SELECT  f.idproyecto, SUM(f.monto) AS gasto
        FROM factura as f
        INNER JOIN maquinaria as m on m.idmaquinaria = f.idmaquinaria
        WHERE m.estado = '1' AND m.estado_delete = '1'  and  f.estado = '1' AND f.estado_delete = '1' GROUP BY f.idproyecto

        UNION ALL

        SELECT  s.idproyecto, SUM(s.costo_parcial) AS gasto
        FROM subcontrato AS s
        WHERE s.estado = '1' AND s.estado_delete = '1' GROUP BY s.idproyecto

        UNION ALL

        SELECT  mdo.idproyecto, SUM(mdo.monto) AS gasto
        FROM mano_de_obra AS mdo
        WHERE mdo.estado = '1' AND mdo.estado_delete = '1' GROUP BY mdo.idproyecto

        UNION ALL

        SELECT  ps.idproyecto, SUM(ps.costo_parcial) AS gasto
        FROM planilla_seguro as ps
        WHERE  ps.estado = '1' AND ps.estado_delete = '1'  GROUP BY ps.idproyecto

        UNION ALL

        SELECT og.idproyecto,  SUM(og.costo_parcial) AS gasto
        FROM otro_gasto AS og
        WHERE og.estado = '1' AND og.estado_delete = '1'  GROUP BY og.idproyecto

        UNION ALL

        SELECT t.idproyecto, SUM(t.precio_parcial) AS gasto
        FROM transporte as t
        WHERE t.estado='1' AND t.estado_delete='1' GROUP BY t.idproyecto

        UNION ALL

        SELECT h.idproyecto, SUM(h.precio_parcial) AS gasto
        FROM hospedaje as h
        WHERE  h.estado='1' AND h.estado_delete='1' GROUP BY h.idproyecto

        UNION ALL

        SELECT pen.idproyecto, SUM(dp.precio_parcial) AS gasto
        FROM detalle_pension AS dp
        inner join pension as pen on pen.idpension = dp.idpension
        WHERE dp.estado = '1' AND dp.estado_delete = '1' GROUP BY pen.idproyecto

        UNION ALL

        SELECT sb.idproyecto, SUM(sb.total) AS gasto
        FROM semana_break as sb
        WHERE  sb.estado='1' AND sb.estado_delete='1' GROUP BY sb.idproyecto

        UNION ALL

        SELECT ce.idproyecto, SUM(ce.costo_parcial) AS gasto
        FROM comida_extra as ce, proyecto as p 
        WHERE ce.estado='1' AND ce.estado_delete='1' GROUP BY ce.idproyecto

        UNION ALL

        SELECT  tpp.idproyecto, SUM(monto) AS gasto
        FROM trabajador_por_proyecto as tpp
        INNER JOIN trabajador as t ON tpp.idtrabajador=t.idtrabajador
        INNER JOIN tipo_trabajador as tt ON t.idtipo_trabajador =tt.idtipo_trabajador 
        INNER JOIN fechas_mes_pagos_administrador as fmpa on fmpa.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto
        INNER JOIN pagos_x_mes_administrador as pxma on pxma.idfechas_mes_pagos_administrador = fmpa.idfechas_mes_pagos_administrador
        WHERE tt.nombre !='Obrero' AND tpp.estado = '1' AND tpp.estado_delete = '1' and pxma.estado='1' and pxma.estado_delete = '1' GROUP BY tpp.idproyecto

        UNION ALL

        SELECT sqa.idproyecto, SUM( pqs.monto_deposito) as gasto  
        FROM pagos_q_s_obrero as pqs
        INNER JOIN resumen_q_s_asistencia rqs on rqs.idresumen_q_s_asistencia = pqs.idresumen_q_s_asistencia
        inner join s_q_asistencia  as  sqa on sqa.ids_q_asistencia    = rqs.ids_q_asistencia
        where  pqs.estado='1' and pqs.estado_delete = '1' GROUP BY sqa.idproyecto

        UNION ALL

        SELECT sqa.idproyecto, (SUM( rqs.pago_quincenal_hn)+SUM( rqs.pago_quincenal_he)) as gasto
        FROM resumen_q_s_asistencia as rqs
        inner join s_q_asistencia  as  sqa on rqs.ids_q_asistencia =sqa.ids_q_asistencia
        WHERE rqs.estado_envio_contador='1' GROUP by sqa.idproyecto

    ) as gp GROUP BY gp.idproyecto
) AS ggpp on ggpp.idproyecto = p.idproyecto

