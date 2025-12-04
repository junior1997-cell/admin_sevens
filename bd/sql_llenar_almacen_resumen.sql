INSERT INTO almacen_resumen(idproyecto, idproducto, tipo, saldo_anterior, total_stok, total_ingreso) 
SELECT cpp.idproyecto as idproyecto, dc.idproducto as idproducto, null as tipo,SUM( dc.cantidad) as saldo_anterior, 
SUM( dc.cantidad) as total_stok, SUM( dc.cantidad) as total_ingreso 
from detalle_compra as dc inner JOIN compra_por_proyecto as cpp on dc.idcompra_proyecto =cpp.idcompra_proyecto 
GROUP BY cpp.idproyecto, dc.idproducto;