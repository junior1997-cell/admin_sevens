<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Resumenfacturas
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  public function facturas_compras($idproyecto)
  {
    $data = Array();

    $sql = "SELECT cpp.idproyecto, cpp.idcompra_proyecto, cpp.fecha_compra, cpp.tipo_comprobante,	cpp.serie_comprobante, cpp.descripcion, 
    cpp.subtotal, cpp.igv, cpp.total, p.razon_social, cpp.glosa, cpp.tipo_gravada, cpp.comprobante
		FROM compra_por_proyecto as cpp, proveedor as p 
		WHERE cpp.idproveedor=p.idproveedor AND cpp.estado = '1' AND cpp.estado_delete = '1' 
    AND cpp.tipo_comprobante IN ('Factura','Boleta') ORDER BY cpp.fecha_compra DESC;";

    $compra = ejecutarConsultaArray($sql);

    if (!empty($compra)) {
      foreach ($compra as $key => $value) {
        $data[] = array(
        	"idproyecto"        => $value['idproyecto'],
          "idtabla"           => $value['idcompra_proyecto'],
          "fecha"             => $value['fecha_compra'],
          "tipo_comprobante"  => (empty($value['tipo_comprobante'])) ? '' : $retVal1 = ($value['tipo_comprobante'] == 'Factura') ? 'FT' : $retVal2 = ($value['tipo_comprobante'] == 'Boleta') ? 'BV' : '' ,
          "serie_comprobante" => $value['serie_comprobante'],
          "proveedor"         => $value['razon_social'],
          "total"             => $value['total'],
          "igv"               => $value['igv'],
          "subtotal"          => $value['subtotal'],
          "glosa"             => $value['glosa'],
          "tipo_gravada"      => $value['tipo_gravada'],
          "comprobante"       => $value['comprobante'],
          "ruta"              => '../dist/docs/compra/comprobante_compra/',
        );
      }
    }

    $sql2 = "SELECT f.idfactura, f.idproyecto, f.codigo, f.fecha_emision, f.monto, f.igv, f.subtotal, 
    f.nota, mq.nombre, prov.razon_social, f.descripcion, f.imagen
    FROM factura as f, proyecto as p, maquinaria as mq, proveedor as prov
    WHERE f.idmaquinaria=mq.idmaquinaria AND mq.idproveedor=prov.idproveedor AND f.idproyecto=p.idproyecto 
    AND f.estado = '1' AND f.estado_delete = '1'  
    ORDER BY f.fecha_emision DESC;";

    $maquinaria_equipo =  ejecutarConsultaArray($sql2);

    if (!empty($maquinaria_equipo)) {
      foreach ($maquinaria_equipo as $key => $value) {
        $data[] = array(
        	"idproyecto"        => $value['idproyecto'],
          "idtabla"           => $value['idfactura'],
          "fecha"             => $value['fecha_emision'],
          "tipo_comprobante"  => 'FT',
          "serie_comprobante" => $value['codigo'],
          "proveedor"         => $value['razon_social'],
          "total"             => $value['monto'],
          "igv"               => $value['igv'],
          "subtotal"          => $value['subtotal'],
          "glosa"             => 'MAQUINARIA',
          "tipo_gravada"      => 'GRAVADA',
          "comprobante"       => $value['imagen'],
          "ruta"              => '../dist/docs/servicio_maquina/comprobante_servicio/',
        );
      }
    }

    $sql3 = "SELECT idproyecto, idotro_gasto, razon_social, tipo_comprobante, numero_comprobante, fecha_g, costo_parcial, subtotal, igv, glosa, comprobante
    FROM otro_gasto WHERE  estado = '1' AND estado_delete = '1' AND tipo_comprobante IN ('Factura','Boleta') ORDER BY fecha_g DESC;";

    $otro_gasto =  ejecutarConsultaArray($sql3);

    if (!empty($otro_gasto)) {
      foreach ($otro_gasto as $key => $value) {
        $data[] = array(
        	"idproyecto"        => $value['idproyecto'],
          "idtabla"           => $value['idotro_gasto'],
          "fecha"             => $value['fecha_g'],
          "tipo_comprobante"  => (empty($value['tipo_comprobante'])) ? '' : $retVal3 = ($value['tipo_comprobante'] == 'Factura') ? 'FT' : $retVal4 = ($value['tipo_comprobante'] == 'Boleta') ? 'BV' : '' ,
          "serie_comprobante" => $value['numero_comprobante'],
          "proveedor"         => $value['razon_social'],
          "total"             => $value['costo_parcial'],
          "igv"               => $value['igv'],
          "subtotal"          => $value['subtotal'],
          "glosa"             => $value['glosa'],
          "tipo_gravada"      => 'GRAVADA',
          "comprobante"       => $value['comprobante'],
          "ruta"              => '../dist/docs/otro_gasto/comprobante/',
        );
      }
    }

    return $data;
  }

  public function suma_totales($idproyecto) {

    $data = Array(); $total = 0; $subtotal = 0; $igv = 0;

    // COMPRA - SUMAS TOTALES --------------------------------------------------------------------------------
    $sql = "SELECT SUM(total) AS total, SUM(subtotal) AS subtotal, SUM(igv) AS igv
    FROM compra_por_proyecto WHERE estado = '1' AND estado_delete = '1' AND tipo_comprobante IN ('Factura','Boleta');";
    $compra = ejecutarConsultaSimpleFila($sql);

    $total    += (empty($compra)) ? 0 : ( empty($compra['total']) ? 0 : floatval($compra['total']) );
    $subtotal += (empty($compra)) ? 0 : ( empty($compra['subtotal']) ? 0 : floatval($compra['subtotal']) );
    $igv      += (empty($compra)) ? 0 : ( empty($compra['igv']) ? 0 : floatval($compra['igv']) );
     
    // COMPRA - MAQUINARIA EQUIPO --------------------------------------------------------------------------------
    $sql2 = "SELECT SUM(monto) as total, SUM(subtotal) AS subtotal, SUM(igv) AS igv
    FROM factura WHERE estado = '1' AND estado_delete = '1';";
    $maquinaria = ejecutarConsultaSimpleFila($sql2);

    $total    += (empty($maquinaria)) ? 0 : ( empty($maquinaria['total']) ? 0 : floatval($maquinaria['total']) );
    $subtotal += (empty($maquinaria)) ? 0 : ( empty($maquinaria['subtotal']) ? 0 : floatval($maquinaria['subtotal']) );
    $igv      += (empty($maquinaria)) ? 0 : ( empty($maquinaria['igv']) ? 0 : floatval($maquinaria['igv']) );

    // COMPRA - OTRO SERVICIO --------------------------------------------------------------------------------
    $sql3 = "SELECT SUM(costo_parcial) as total, SUM(subtotal) AS subtotal, SUM(igv) AS igv
    FROM otro_gasto  WHERE estado = '1' AND estado_delete = '1' AND tipo_comprobante IN ('Factura','Boleta');";
    $otro_gasto = ejecutarConsultaSimpleFila($sql3);
    
    $total    += (empty($otro_gasto)) ? 0 : ( empty($otro_gasto['total']) ? 0 : floatval($otro_gasto['total']) );
    $subtotal += (empty($otro_gasto)) ? 0 : ( empty($otro_gasto['subtotal']) ? 0 : floatval($otro_gasto['subtotal']) );
    $igv      += (empty($otro_gasto)) ? 0 : ( empty($otro_gasto['igv']) ? 0 : floatval($otro_gasto['igv']) );

    // COMPRA - OTRO SERVICIO --------------------------------------------------------------------------------
    
    $data = array( "total" => $total, "subtotal" => $subtotal, "igv" => $igv,  );

    return $data ;
  }  


  public function facturas_transporte($idproyecto)
  {
    $sql = "SELECT*FROM transporte WHERE idproyecto='$idproyecto' AND tipo_comprobante='Factura' AND estado=1 ORDER BY fecha_viaje DESC;";
    return ejecutarConsulta($sql);
  }

  public function suma_total_transporte($idproyecto)
  {
    $sql = "SELECT SUM(precio_parcial) as monto_total FROM transporte WHERE idproyecto='$idproyecto' AND tipo_comprobante='Factura' AND estado=1;";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function facturas_hospedaje($idproyecto)
  {
    $sql = "SELECT*FROM hospedaje WHERE idproyecto='$idproyecto' AND tipo_comprobante='Factura' AND estado=1 ORDER BY fecha_comprobante DESC;";
    return ejecutarConsulta($sql);
  }

  public function suma_total_hospedaje($idproyecto)
  {
    $sql = "SELECT SUM(precio_parcial) as monto_total FROM hospedaje WHERE idproyecto='$idproyecto' AND tipo_comprobante='Factura' AND estado=1 ORDER BY fecha_comprobante DESC;";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function facturas_pension($idproyecto)
  {
    $sql = "SELECT fp.idfactura_pension,fp.tipo_comprobante, fp.nro_comprobante, fp.forma_de_pago, fp.fecha_emision, fp.monto, fp.igv, fp.subtotal, prov.razon_social,fp.estado
		FROM factura_pension as fp, pension as p, proveedor as prov
		WHERE fp.idpension=p.idpension AND prov.idproveedor=p.idproveedor AND p.idproyecto='$idproyecto' AND fp.estado=1 AND fp.tipo_comprobante='Factura'  ORDER BY fecha_emision DESC;";

    return ejecutarConsulta($sql);
  }

  public function suma_total_pension($idproyecto)
  {
    $sql = "SELECT SUM(fp.monto) as monto_total
		FROM factura_pension as fp, pension as p 
		WHERE fp.idpension=p.idpension  AND p.idproyecto='$idproyecto' AND fp.estado=1 AND fp.tipo_comprobante='Factura';";

    return ejecutarConsultaSimpleFila($sql);
  }

  public function facturas_break($idproyecto)
  {
    $sql = "SELECT fb.idfactura_break,fb.idsemana_break, fb.nro_comprobante, fb.fecha_emision, fb.monto, fb.igv, fb.subtotal, fb.tipo_comprobante, fb.descripcion, sb.numero_semana
		FROM factura_break as fb, semana_break as sb
		WHERE  fb.idsemana_break=sb.idsemana_break AND fb.tipo_comprobante='Factura' AND fb.estado=1 AND sb.idproyecto='$idproyecto'  ORDER BY fecha_emision DESC;";

    return ejecutarConsulta($sql);
  }

  public function suma_total_break($idproyecto)
  {
    $sql = "SELECT SUM(fb.monto) as monto_total
		FROM factura_break as fb, semana_break as sb
		WHERE  fb.idsemana_break=sb.idsemana_break AND fb.tipo_comprobante='Factura' AND fb.estado=1 AND sb.idproyecto='$idproyecto';";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function facturas_comida_extra($idproyecto)
  {
    $sql = "SELECT idcomida_extra, numero_comprobante, fecha_comida, subtotal, igv, costo_parcial, estado
		FROM comida_extra
		WHERE tipo_comprobante='Factura' AND idproyecto='$idproyecto' AND estado=1  ORDER BY fecha_comida DESC;";

    return ejecutarConsulta($sql);
  }

  public function suma_total_comida_extra($idproyecto)
  {
    $sql = "SELECT SUM(costo_parcial) as monto_total
		FROM comida_extra
		WHERE tipo_comprobante='Factura' AND idproyecto='$idproyecto' AND estado=1;";

    return ejecutarConsultaSimpleFila($sql);
  }
}

?>
