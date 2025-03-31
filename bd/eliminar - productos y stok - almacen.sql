
select ar.idproyecto, ad.* from almacen_detalle as ad
inner JOIN almacen_resumen as ar on ar.idalmacen_resumen = ad.idalmacen_resumen
where ar.idproducto = 01432 and ar.idproyecto = 014

SELECT agd.* FROM almacen_general_detalle as agd 
INNER JOIN almacen_general_resumen as agr on agr.idalmacen_general_resumen = agd.idalmacen_general_resumen
where agr.idproducto = 01432 and agd.idproyecto = 014 


SELECT agr.idalmacen_general, p.* 
FROM almacen_general_resumen as agr 
INNER JOIN producto as p on p.idproducto = agr.idproducto
where agr.idalmacen_general_resumen = 2 and agr.idalmacen_general = 1

-- :::::::::::::::::::::::::::::
-- ALMACEN PROYECTO
-- :::::::::::::::::::::::::::::

-- Buscar Producto
select  ad.* from almacen_detalle as ad
inner JOIN almacen_resumen as ar on ar.idalmacen_resumen = ad.idalmacen_resumen
where ar.idproducto = 02644 and ar.idproyecto = 014 order by ad.idalmacen_detalle desc

-- Eliminar del detalle
DELETE ad FROM almacen_detalle as ad WHERE ad.idalmacen_detalle = 6069

select * FROM almacen_resumen AS ar WHERE ar.idproducto = 02644 AND ar.idproyecto = 14;

-- Actualizar Stock Resumen
UPDATE almacen_resumen
SET total_stok = total_stok + 1, total_egreso = total_egreso - 1
WHERE idproducto = 02644 AND idproyecto = 14;


-- :::::::::::::::::::::::::::::
-- ALMACEN GENERAL
-- :::::::::::::::::::::::::::::

select agd.*
FROM almacen_general_resumen AS agr
inner join almacen_general_detalle as agd on agd.idalmacen_general_resumen = agr.idalmacen_general_resumen
WHERE agr.idproducto = 02644 AND agd.idproyecto = 14 and agr.idalmacen_general = 2 order by agd.idalmacen_general_detalle desc;

-- Eliminamos el detalle
DELETE agd FROM almacen_general_detalle AS agd where agd.idalmacen_general_detalle = 303

-- ACtulizamos el stock
select agr.* FROM almacen_general_resumen AS agr WHERE agr.idproducto = 02644 and agr.idalmacen_general = 2

UPDATE almacen_general_resumen AS agr
SET agr.total_stok = agr.total_stok - 1, agr.total_ingreso = agr.total_ingreso - 1
WHERE agr.idproducto = 02644 AND agr.idalmacen_general = 2;


SELECT * FROM almacen_general
