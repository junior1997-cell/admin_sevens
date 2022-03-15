<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Resumenfacturas
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  public function facturas_compras($idproyecto, $fecha_1, $fecha_2, $id_proveedor, $comprobante)
  {
    $data = Array(); $data_comprobante = Array(); $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = "";

    $link_host = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/';

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND cpp.fecha_compra BETWEEN '$fecha_1' AND '$fecha_2'";
    } else {
      if (!empty($fecha_1)) {
        $filtro_fecha = "AND cpp.fecha_compra = '$fecha_1'";
      }else{
        if (!empty($fecha_2)) {
          $filtro_fecha = "AND cpp.fecha_compra = '$fecha_2'";
        }     
      }      
    }    

    if (empty($id_proveedor) || $id_proveedor == 0) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND p.ruc = '$id_proveedor'"; }

    if ( empty($comprobante) || $comprobante == 0) { $filtro_comprobante = "AND cpp.tipo_comprobante IN ('Factura','Boleta')"; } else {
      $filtro_comprobante = "AND cpp.tipo_comprobante = '$comprobante'"; 
    } 

    $sql = "SELECT cpp.idproyecto, cpp.idcompra_proyecto, cpp.fecha_compra, cpp.tipo_comprobante,	cpp.serie_comprobante, cpp.descripcion, 
    cpp.total, cpp.subtotal, cpp.igv,  p.razon_social, cpp.glosa, cpp.tipo_gravada, cpp.comprobante
		FROM compra_por_proyecto as cpp, proveedor as p 
		WHERE cpp.idproveedor=p.idproveedor AND cpp.estado = '1' AND cpp.estado_delete = '1' 
    $filtro_comprobante $filtro_proveedor $filtro_fecha ORDER BY cpp.fecha_compra DESC;";

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
          "subtotal"          => $value['subtotal'],
          "igv"               => $value['igv'],
          "glosa"             => $value['glosa'],
          "tipo_gravada"      => $value['tipo_gravada'],
          "comprobante"       => $value['comprobante'],
          "ruta"              => 'dist/docs/compra/comprobante_compra/',
          "modulo"              => 'COMPRAS',
        );

        if (!empty($value['comprobante'])) {
          if ( validar_url( $link_host, 'dist/docs/compra/comprobante_compra/', $value['comprobante']) ) {
            $data_comprobante[] = array(
              "comprobante"       => $value['comprobante'],
              "carpeta_file"      => 'dist/docs/compra/comprobante_compra/',
              "host"              => $link_host,
              "ruta_nube"         => $link_host.'dist/docs/compra/comprobante_compra/'.$value['comprobante'],
              "ruta_local"        => 'http://localhost/admin_sevens/dist/docs/compra/comprobante_compra/'.$value['comprobante'],
            );
          }          
        }        
      }
    }

    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = "";

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND f.fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
    } else {
      if (!empty($fecha_1)) {
        $filtro_fecha = "AND f.fecha_emision = '$fecha_1'";
      }else{
        if (!empty($fecha_2)) {
          $filtro_fecha = "AND f.fecha_emision = '$fecha_2'";
        }     
      }      
    }    
    if ( empty($comprobante) || $comprobante == 0) { $filtro_comprobante = "AND f.tipo_comprobante IN ('Factura','Boleta')"; } else {
      $filtro_comprobante = "AND f.tipo_comprobante = '$comprobante'"; 
    } 
    if (empty($id_proveedor) || $id_proveedor == 0) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND prov.ruc = '$id_proveedor'"; }

    $sql2 = "SELECT f.idfactura, f.idproyecto, f.codigo, f.fecha_emision, f.monto, f.subtotal, f.igv,
    f.nota, mq.nombre, prov.razon_social, f.descripcion, f.imagen
    FROM factura as f, proyecto as p, maquinaria as mq, proveedor as prov
    WHERE f.idmaquinaria=mq.idmaquinaria AND mq.idproveedor=prov.idproveedor AND f.idproyecto=p.idproyecto 
    AND f.estado = '1' AND f.estado_delete = '1'  $filtro_fecha $filtro_proveedor $filtro_comprobante
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
          "subtotal"          => $value['subtotal'],
          "igv"               => $value['igv'],
          "glosa"             => 'MAQUINARIA',
          "tipo_gravada"      => 'GRAVADA',
          "comprobante"       => $value['imagen'],
          "ruta"              => 'dist/docs/servicio_maquina/comprobante_servicio/',
          "modulo"              => 'MAQUINA Y/O EQUIPO',
        );
        if (!empty($value['imagen'])) {
          if (validar_url( $link_host, 'dist/docs/servicio_maquina/comprobante_servicio/', $value['imagen'])) {
            $data_comprobante[] = array(
              "comprobante"       => $value['imagen'],
              "carpeta_file"      => 'dist/docs/servicio_maquina/comprobante_servicio/',
              "host"              => $link_host,
              "ruta_nube"         => $link_host.'dist/docs/servicio_maquina/comprobante_servicio/'.$value['imagen'],
              "ruta_local"        => 'http://localhost/admin_sevens/dist/docs/servicio_maquina/comprobante_servicio/'.$value['imagen'],
            );
          }
          
        }
      }
    }

    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = "";

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND fecha_g BETWEEN '$fecha_1' AND '$fecha_2'";
    } else {
      if (!empty($fecha_1)) {
        $filtro_fecha = "AND fecha_g = '$fecha_1'";
      }else{
        if (!empty($fecha_2)) {
          $filtro_fecha = "AND fecha_g = '$fecha_2'";
        }     
      }      
    }    

    if (empty($id_proveedor) || $id_proveedor == 0) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND ruc = '$id_proveedor'"; }

    if ( empty($comprobante) || $comprobante == 0) { $filtro_comprobante = "AND tipo_comprobante IN ('Factura','Boleta')"; } else {
      $filtro_comprobante = "AND tipo_comprobante = '$comprobante'"; 
    }

    $sql3 = "SELECT idproyecto, idotro_gasto, razon_social, tipo_comprobante, numero_comprobante, fecha_g, 
    costo_parcial, subtotal, igv, glosa, comprobante
    FROM otro_gasto 
    WHERE  estado = '1' AND estado_delete = '1'  $filtro_fecha $filtro_proveedor $filtro_comprobante ORDER BY fecha_g DESC;";

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
          "ruta"              => 'dist/docs/otro_gasto/comprobante/',
          "modulo"              => 'OTRO GASTO',
        );
        if (!empty($value['comprobante'])) {
          if ( validar_url( $link_host, 'dist/docs/otro_gasto/comprobante/', $value['comprobante']) ) {
            $data_comprobante[] = array(
              "comprobante"       => $value['comprobante'],
              "carpeta_file"      => 'dist/docs/otro_gasto/comprobante/',
              "host"              => $link_host,
              "ruta_nube"         => $link_host.'dist/docs/otro_gasto/comprobante/'.$value['comprobante'],
              "ruta_local"        => 'http://localhost/admin_sevens/dist/docs/otro_gasto/comprobante/'.$value['comprobante'],
            );
          }          
        }
      }
    }

    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = "";

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND t.fecha_viaje BETWEEN '$fecha_1' AND '$fecha_2'";
    } else {
      if (!empty($fecha_1)) {
        $filtro_fecha = "AND t.fecha_viaje = '$fecha_1'";
      }else{
        if (!empty($fecha_2)) {
          $filtro_fecha = "AND t.fecha_viaje = '$fecha_2'";
        }     
      }      
    }    

    if (empty($id_proveedor) || $id_proveedor == 0) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND p.ruc = '$id_proveedor'"; }

    if ( empty($comprobante) || $comprobante == 0) { $filtro_comprobante = "AND  t.tipo_comprobante IN ('Factura','Boleta')"; } else {
      $filtro_comprobante = "AND t.tipo_comprobante = '$comprobante'"; 
    }

    $sql4 = "SELECT t.idtransporte, t.idproyecto, p.razon_social, t.tipo_comprobante, t.numero_comprobante, t.fecha_viaje, 
    t.precio_parcial, t.subtotal, t.igv,  t.comprobante , t.glosa 
    FROM transporte AS t, proveedor AS p
    WHERE t.idproveedor = p.idproveedor  AND t.estado = '1' AND t.estado_delete = '1' $filtro_fecha $filtro_proveedor $filtro_comprobante
    ORDER BY t.fecha_viaje DESC;";

    $transporte =  ejecutarConsultaArray($sql4);

    if (!empty($transporte)) {
      foreach ($transporte as $key => $value) {
        $data[] = array(
        	"idproyecto"        => $value['idproyecto'],
          "idtabla"           => $value['idtransporte'],
          "fecha"             => $value['fecha_viaje'],
          "tipo_comprobante"  => (empty($value['tipo_comprobante'])) ? '' : $retVal5 = ($value['tipo_comprobante'] == 'Factura') ? 'FT' : $retVal6 = ($value['tipo_comprobante'] == 'Boleta') ? 'BV' : '' ,
          "serie_comprobante" => $value['numero_comprobante'],
          "proveedor"         => $value['razon_social'],
          "total"             => $value['precio_parcial'],          
          "subtotal"          => $value['subtotal'],
          "igv"               => $value['igv'],
          "glosa"             => $value['glosa'],
          "tipo_gravada"      => 'GRAVADA',
          "comprobante"       => $value['comprobante'],
          "ruta"              => 'dist/docs/transporte/comprobante/',
          "modulo"              => 'TRANSPORTE',
        );
        if (!empty($value['comprobante'])) {
          if ( validar_url( $link_host, 'dist/docs/transporte/comprobante/', $value['comprobante']) ) {
            $data_comprobante[] = array(
              "comprobante"       => $value['comprobante'],
              "carpeta_file"      => 'dist/docs/transporte/comprobante/',
              "host"              => $link_host,
              "ruta_nube"         => $link_host.'dist/docs/transporte/comprobante/'.$value['comprobante'],
              "ruta_local"        => 'http://localhost/admin_sevens/dist/docs/transporte/comprobante/'.$value['comprobante'],
            );
          }          
        }
      }
    }

    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = "";

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND fecha_comprobante BETWEEN '$fecha_1' AND '$fecha_2'";
    } else {
      if (!empty($fecha_1)) {
        $filtro_fecha = "AND fecha_comprobante = '$fecha_1'";
      }else{
        if (!empty($fecha_2)) {
          $filtro_fecha = "AND fecha_comprobante = '$fecha_2'";
        }     
      }      
    }    

    if (empty($id_proveedor) || $id_proveedor == 0) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND ruc = '$id_proveedor'"; }

    if ( empty($comprobante) || $comprobante == 0) { $filtro_comprobante = "AND  tipo_comprobante IN ('Factura','Boleta')"; } else {
      $filtro_comprobante = "AND tipo_comprobante = '$comprobante'"; 
    }

    $sql5 = "SELECT  idhospedaje, idproyecto, razon_social, fecha_comprobante, tipo_comprobante, numero_comprobante, subtotal, igv, 
    precio_parcial, glosa, comprobante
    FROM hospedaje 
    WHERE estado = '1' AND estado_delete = '1' $filtro_fecha $filtro_proveedor $filtro_comprobante
    ORDER BY fecha_comprobante DESC;";

    $hospedaje =  ejecutarConsultaArray($sql5);

    if (!empty($hospedaje)) {
      foreach ($hospedaje as $key => $value) {
        $data[] = array(
        	"idproyecto"        => $value['idproyecto'],
          "idtabla"           => $value['idhospedaje'],
          "fecha"             => $value['fecha_comprobante'],
          "tipo_comprobante"  => (empty($value['tipo_comprobante'])) ? '' : $retVal5 = ($value['tipo_comprobante'] == 'Factura') ? 'FT' : $retVal6 = ($value['tipo_comprobante'] == 'Boleta') ? 'BV' : '' ,
          "serie_comprobante" => $value['numero_comprobante'],
          "proveedor"         => $value['razon_social'],
          "total"             => $value['precio_parcial'],
          "igv"               => $value['igv'],
          "subtotal"          => $value['subtotal'],
          "glosa"             => $value['glosa'],
          "tipo_gravada"      => 'GRAVADA',
          "comprobante"       => $value['comprobante'],
          "ruta"              => 'dist/docs/hospedaje/comprobante/',
          "modulo"              => 'HOSPEDAJE',
        );
        if (!empty($value['comprobante'])) {
          if ( validar_url( $link_host, 'dist/docs/hospedaje/comprobante/', $value['comprobante']) ) {
            $data_comprobante[] = array(
              "comprobante"       => $value['comprobante'],
              "carpeta_file"      => 'dist/docs/hospedaje/comprobante/',
              "host"              => $link_host,
              "ruta_nube"         => $link_host.'dist/docs/hospedaje/comprobante/'.$value['comprobante'],
              "ruta_local"        => 'http://localhost/admin_sevens/dist/docs/hospedaje/comprobante/'.$value['comprobante'],
            );
          }          
        }
      }
    }

    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = "";

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND fp.fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
    } else {
      if (!empty($fecha_1)) {
        $filtro_fecha = "AND fp.fecha_emision = '$fecha_1'";
      }else{
        if (!empty($fecha_2)) {
          $filtro_fecha = "AND fp.fecha_emision = '$fecha_2'";
        }     
      }      
    }    

    if (empty($id_proveedor) || $id_proveedor == 0) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND prov.ruc = '$id_proveedor'"; }

    if ( empty($comprobante) || $comprobante == 0) { $filtro_comprobante = "AND fp.tipo_comprobante IN ('Factura','Boleta')"; } else {
      $filtro_comprobante = "AND fp.tipo_comprobante = '$comprobante'"; 
    }

    $sql6 = "SELECT p.idproyecto, fp.idfactura_pension, prov.razon_social, fp.tipo_comprobante, fp.nro_comprobante, fp.fecha_emision, 
    fp.monto, fp.subtotal, fp.igv, fp.comprobante, fp.glosa
		FROM factura_pension as fp, pension as p, proveedor as prov
		WHERE fp.idpension = p.idpension AND prov.idproveedor = p.idproveedor  AND p.estado = '1' AND p.estado_delete = '1' AND fp.estado = '1' AND fp.estado_delete = '1'
    $filtro_fecha $filtro_proveedor $filtro_comprobante
    ORDER BY fp.fecha_emision DESC;";

    $factura_pension =  ejecutarConsultaArray($sql6);

    if (!empty($factura_pension)) {
      foreach ($factura_pension as $key => $value) {
        $data[] = array(
        	"idproyecto"        => $value['idproyecto'],
          "idtabla"           => $value['idfactura_pension'],
          "fecha"             => $value['fecha_emision'],
          "tipo_comprobante"  => (empty($value['tipo_comprobante'])) ? '' : $retVal5 = ($value['tipo_comprobante'] == 'Factura') ? 'FT' : $retVal6 = ($value['tipo_comprobante'] == 'Boleta') ? 'BV' : '' ,
          "serie_comprobante" => $value['nro_comprobante'],
          "proveedor"         => $value['razon_social'],
          "total"             => $value['monto'],          
          "subtotal"          => $value['subtotal'],
          "igv"               => $value['igv'],
          "glosa"             => $value['glosa'],
          "tipo_gravada"      => 'GRAVADA',
          "comprobante"       => $value['comprobante'],
          "ruta"              => 'dist/docs/pension/comprobante/',
          "modulo"              => 'PENSION',
        );
        if (!empty($value['comprobante'])) {
          if ( validar_url( $link_host, 'dist/docs/pension/comprobante/', $value['comprobante']) ) {
            $data_comprobante[] = array(
              "comprobante"       => $value['comprobante'],
              "carpeta_file"      => 'dist/docs/pension/comprobante/',
              "host"              => $link_host,
              "ruta_nube"         => $link_host.'dist/docs/pension/comprobante/'.$value['comprobante'],
              "ruta_local"        => 'http://localhost/admin_sevens/dist/docs/pension/comprobante/'.$value['comprobante'],
            );
          }          
        }
      }
    }

    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = "";

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND fb.fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
    } else {
      if (!empty($fecha_1)) {
        $filtro_fecha = "AND fb.fecha_emision = '$fecha_1'";
      }else{
        if (!empty($fecha_2)) {
          $filtro_fecha = "AND fb.fecha_emision = '$fecha_2'";
        }     
      }      
    }    

    if (empty($id_proveedor) || $id_proveedor == 0) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND fb.ruc = '$id_proveedor'"; }

    if ( empty($comprobante) || $comprobante == 0) { $filtro_comprobante = "AND fb.tipo_comprobante IN ('Factura','Boleta')"; } else {
      $filtro_comprobante = "AND fb.tipo_comprobante = '$comprobante'"; 
    }

    $sql7 = "SELECT sb.idproyecto, fb.idfactura_break, fb.fecha_emision, fb.tipo_comprobante, fb.nro_comprobante, fb.razon_social,  
    fb.monto, fb.subtotal, fb.igv, fb.glosa,  fb.comprobante
		FROM factura_break as fb, semana_break as sb
		WHERE  fb.idsemana_break = sb.idsemana_break  
    AND fb.estado = '1' AND fb.estado_delete = '1' AND sb.estado = '1' AND sb.estado_delete = '1' $filtro_fecha $filtro_proveedor $filtro_comprobante
    ORDER BY fb.fecha_emision DESC;";

    $factura_break =  ejecutarConsultaArray($sql7);

    if (!empty($factura_break)) {
      foreach ($factura_break as $key => $value) {
        $data[] = array(
        	"idproyecto"        => $value['idproyecto'],
          "idtabla"           => $value['idfactura_break'],
          "fecha"             => $value['fecha_emision'],
          "tipo_comprobante"  => (empty($value['tipo_comprobante'])) ? '' : $retVal5 = ($value['tipo_comprobante'] == 'Factura') ? 'FT' : $retVal6 = ($value['tipo_comprobante'] == 'Boleta') ? 'BV' : '' ,
          "serie_comprobante" => $value['nro_comprobante'],
          "proveedor"         => $value['razon_social'],
          "total"             => $value['monto'],          
          "subtotal"          => $value['subtotal'],
          "igv"               => $value['igv'],
          "glosa"             => $value['glosa'],
          "tipo_gravada"      => 'GRAVADA',
          "comprobante"       => $value['comprobante'],
          "ruta"              => 'dist/docs/break/comprobante/',
          "modulo"              => 'BREAK',
        );
        if (!empty($value['comprobante'])) {
          if ( validar_url( $link_host, 'dist/docs/break/comprobante/', $value['comprobante']) ) {
            $data_comprobante[] = array(
              "comprobante"       => $value['comprobante'],
              "carpeta_file"      => 'dist/docs/break/comprobante/',
              "host"              => $link_host,
              "ruta_nube"         => $link_host.'dist/docs/break/comprobante/'.$value['comprobante'],
              "ruta_local"        => 'http://localhost/admin_sevens/dist/docs/break/comprobante/'.$value['comprobante'],
            );
          }          
        }
      }
    }

    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = "";

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND fecha_comida BETWEEN '$fecha_1' AND '$fecha_2'";
    } else {
      if (!empty($fecha_1)) {
        $filtro_fecha = "AND fecha_comida = '$fecha_1'";
      }else{
        if (!empty($fecha_2)) {
          $filtro_fecha = "AND fecha_comida = '$fecha_2'";
        }     
      }      
    }    

    if (empty($id_proveedor) || $id_proveedor == 0) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND ruc = '$id_proveedor'"; }

    if ( empty($comprobante) || $comprobante == 0) { $filtro_comprobante = "AND tipo_comprobante IN ('Factura','Boleta')"; } else {
      $filtro_comprobante = "AND tipo_comprobante = '$comprobante'"; 
    }

    $sql8 = "SELECT idproyecto, idcomida_extra, fecha_comida, tipo_comprobante, numero_comprobante, razon_social, 
    costo_parcial, subtotal, igv, glosa, comprobante
		FROM comida_extra
		WHERE  estado = '1' AND estado_delete = '1' $filtro_fecha $filtro_proveedor $filtro_comprobante
    ORDER BY fecha_comida DESC;";

    $comida_extra =  ejecutarConsultaArray($sql8);

    if (!empty($comida_extra)) {
      foreach ($comida_extra as $key => $value) {
        $data[] = array(
        	"idproyecto"        => $value['idproyecto'],
          "idtabla"           => $value['idcomida_extra'],
          "fecha"             => $value['fecha_comida'],
          "tipo_comprobante"  => (empty($value['tipo_comprobante'])) ? '' : $retVal5 = ($value['tipo_comprobante'] == 'Factura') ? 'FT' : $retVal6 = ($value['tipo_comprobante'] == 'Boleta') ? 'BV' : '' ,
          "serie_comprobante" => $value['numero_comprobante'],
          "proveedor"         => $value['razon_social'],
          "total"             => $value['costo_parcial'],          
          "subtotal"          => $value['subtotal'],
          "igv"               => $value['igv'],
          "glosa"             => $value['glosa'],
          "tipo_gravada"      => 'GRAVADA',
          "comprobante"       => $value['comprobante'],
          "ruta"              => 'dist/docs/comida_extra/comprobante/',
          "modulo"              => 'COMIDA EXTRA',
        );
        if (!empty($value['comprobante'])) {
          if ( validar_url( $link_host, 'dist/docs/comida_extra/comprobante/', $value['comprobante']) ) {
            $data_comprobante[] = array(
              "comprobante"       => $value['comprobante'],
              "carpeta_file"      => 'dist/docs/comida_extra/comprobante/',
              "host"              => $link_host,
              "ruta_nube"         => $link_host.'dist/docs/comida_extra/comprobante/'.$value['comprobante'],
              "ruta_local"        => 'http://localhost/admin_sevens/dist/docs/comida_extra/comprobante/'.$value['comprobante'],
            );
          }          
        }
      }
    }

    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = "";

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND of.fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
    } else {
      if (!empty($fecha_1)) {
        $filtro_fecha = "AND of.fecha_emision = '$fecha_1'";
      }else{
        if (!empty($fecha_2)) {
          $filtro_fecha = "AND of.fecha_emision = '$fecha_2'";
        }     
      }      
    }    

    if (empty($id_proveedor) || $id_proveedor == 0) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND p.ruc = '$id_proveedor'"; }

    if ( empty($comprobante) || $comprobante == 0) { $filtro_comprobante = "AND of.tipo_comprobante IN ('Factura','Boleta')"; } else {
      $filtro_comprobante = "AND of.tipo_comprobante = '$comprobante'"; 
    }

    $sql9 = "SELECT of.idotra_factura, of.fecha_emision, of.tipo_comprobante, of.numero_comprobante, p.razon_social, of.costo_parcial, 
    of.subtotal, of.igv, of.glosa, of.comprobante 
    FROM otra_factura AS of, proveedor p
    WHERE of.idproveedor = p.idproveedor AND of.estado = '1' AND of.estado_delete = '1' $filtro_fecha $filtro_proveedor $filtro_comprobante
    ORDER BY of.fecha_emision DESC;";

    $otra_factura =  ejecutarConsultaArray($sql9);

    if (!empty($otra_factura)) {
      foreach ($otra_factura as $key => $value) {
        $data[] = array(
        	"idproyecto"        => '',
          "idtabla"           => $value['idotra_factura'],
          "fecha"             => $value['fecha_emision'],
          "tipo_comprobante"  => (empty($value['tipo_comprobante'])) ? '' : $retVal5 = ($value['tipo_comprobante'] == 'Factura') ? 'FT' : $retVal6 = ($value['tipo_comprobante'] == 'Boleta') ? 'BV' : '' ,
          "serie_comprobante" => $value['numero_comprobante'],
          "proveedor"         => $value['razon_social'],
          "total"             => $value['costo_parcial'],
          "igv"               => $value['igv'],
          "subtotal"          => $value['subtotal'],
          "glosa"             => $value['glosa'],
          "tipo_gravada"      => 'GRAVADA',
          "comprobante"       => $value['comprobante'],
          "ruta"              => 'dist/docs/otra_factura/comprobante/',
          "modulo"              => 'OTRA FACTURA',
        );
        if (!empty($value['comprobante'])) {
          if ( validar_url( $link_host, 'dist/docs/otra_factura/comprobante/', $value['comprobante']) ) {
            $data_comprobante[] = array(
              "comprobante"       => $value['comprobante'],
              "carpeta_file"      => 'dist/docs/otra_factura/comprobante/',
              "host"              => $link_host,
              "ruta_nube"         => $link_host.'dist/docs/otra_factura/comprobante/'.$value['comprobante'],
              "ruta_local"        => 'http://localhost/admin_sevens/dist/docs/otra_factura/comprobante/'.$value['comprobante'],
            );
          }          
        }
      }
    }

    $retorno = array(
      "data"              => $data,
      "data_comprobante"  => $data_comprobante,
    );

    return $retorno;
  }

  public function suma_totales($idproyecto, $fecha_1, $fecha_2, $id_proveedor, $comprobante) {

    $data = Array(); $total = 0; $subtotal = 0; $igv = 0;

    // SUMAS TOTALES - COMPRA --------------------------------------------------------------------------------
    $filtro_proveedor = ""; $filtro_comprobante = ""; $filtro_fecha = "";

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND cpp.fecha_compra BETWEEN '$fecha_1' AND '$fecha_2'";
    } else {
      if (!empty($fecha_1)) {
        $filtro_fecha = "AND cpp.fecha_compra = '$fecha_1'";
      }else{
        if (!empty($fecha_2)) {
          $filtro_fecha = "AND cpp.fecha_compra = '$fecha_2'";
        }     
      }      
    }    

    if (empty($id_proveedor) || $id_proveedor == 0) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND p.ruc = '$id_proveedor'"; }

    if ( empty($comprobante) || $comprobante == 0) { $filtro_comprobante = "AND cpp.tipo_comprobante IN ('Factura','Boleta')"; } else {
      $filtro_comprobante = "AND cpp.tipo_comprobante = '$comprobante'"; 
    }

    $sql = "SELECT SUM(cpp.total) AS total, SUM(cpp.subtotal) AS subtotal, SUM(cpp.igv) AS igv
    FROM compra_por_proyecto AS cpp, proveedor p
    WHERE cpp.idproveedor = p.idproveedor AND cpp.estado = '1' AND cpp.estado_delete = '1' $filtro_proveedor $filtro_comprobante $filtro_fecha ;";
    $compra = ejecutarConsultaSimpleFila($sql);

    $total    += (empty($compra)) ? 0 : ( empty($compra['total']) ? 0 : floatval($compra['total']) );
    $subtotal += (empty($compra)) ? 0 : ( empty($compra['subtotal']) ? 0 : floatval($compra['subtotal']) );
    $igv      += (empty($compra)) ? 0 : ( empty($compra['igv']) ? 0 : floatval($compra['igv']) );
     
    // SUMAS TOTALES - MAQUINARIA EQUIPO --------------------------------------------------------------------------------
    $filtro_proveedor = ""; $filtro_comprobante = ""; $filtro_fecha = "";

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND f.fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
    } else {
      if (!empty($fecha_1)) {
        $filtro_fecha = "AND f.fecha_emision = '$fecha_1'";
      }else{
        if (!empty($fecha_2)) {
          $filtro_fecha = "AND f.fecha_emision = '$fecha_2'";
        }     
      }      
    }    

    if (empty($id_proveedor) || $id_proveedor == 0) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND prov.ruc = '$id_proveedor'"; }

    if ( empty($comprobante) || $comprobante == 0) { $filtro_comprobante = "AND f.tipo_comprobante IN ('Factura','Boleta')"; } else {
      $filtro_comprobante = "AND f.tipo_comprobante = '$comprobante'"; 
    }

    $sql2 = "SELECT SUM(f.monto) AS total , SUM(f.subtotal) AS subtotal, SUM(f.igv) AS igv
    FROM factura as f, proyecto as p, maquinaria as mq, proveedor as prov
    WHERE f.idmaquinaria=mq.idmaquinaria AND mq.idproveedor=prov.idproveedor AND f.idproyecto=p.idproyecto 
    AND f.estado = '1' AND f.estado_delete = '1' $filtro_proveedor $filtro_comprobante $filtro_fecha;";
    $maquinaria = ejecutarConsultaSimpleFila($sql2);

    $total    += (empty($maquinaria)) ? 0 : ( empty($maquinaria['total']) ? 0 : floatval($maquinaria['total']) );
    $subtotal += (empty($maquinaria)) ? 0 : ( empty($maquinaria['subtotal']) ? 0 : floatval($maquinaria['subtotal']) );
    $igv      += (empty($maquinaria)) ? 0 : ( empty($maquinaria['igv']) ? 0 : floatval($maquinaria['igv']) );

    // SUMAS TOTALES - OTRO SERVICIO --------------------------------------------------------------------------------
    $filtro_proveedor = ""; $filtro_comprobante = ""; $filtro_fecha = "";

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND fecha_g BETWEEN '$fecha_1' AND '$fecha_2'";
    } else {
      if (!empty($fecha_1)) {
        $filtro_fecha = "AND fecha_g = '$fecha_1'";
      }else{
        if (!empty($fecha_2)) {
          $filtro_fecha = "AND fecha_g = '$fecha_2'";
        }     
      }      
    }    

    if (empty($id_proveedor) || $id_proveedor == 0) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND ruc = '$id_proveedor'"; }

    if ( empty($comprobante) || $comprobante == 0) { $filtro_comprobante = "AND tipo_comprobante IN ('Factura','Boleta')"; } else {
      $filtro_comprobante = "AND tipo_comprobante = '$comprobante'"; 
    }

    $sql3 = "SELECT SUM(costo_parcial) as total, SUM(subtotal) AS subtotal, SUM(igv) AS igv
    FROM otro_gasto  
    WHERE estado = '1' AND estado_delete = '1' $filtro_proveedor $filtro_comprobante $filtro_fecha;";
    $otro_gasto = ejecutarConsultaSimpleFila($sql3);
    
    $total    += (empty($otro_gasto)) ? 0 : ( empty($otro_gasto['total']) ? 0 : floatval($otro_gasto['total']) );
    $subtotal += (empty($otro_gasto)) ? 0 : ( empty($otro_gasto['subtotal']) ? 0 : floatval($otro_gasto['subtotal']) );
    $igv      += (empty($otro_gasto)) ? 0 : ( empty($otro_gasto['igv']) ? 0 : floatval($otro_gasto['igv']) );

    // SUMAS TOTALES - TRASNPORTE --------------------------------------------------------------------------------
    $filtro_proveedor = ""; $filtro_comprobante = ""; $filtro_fecha = "";

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND t.fecha_viaje BETWEEN '$fecha_1' AND '$fecha_2'";
    } else {
      if (!empty($fecha_1)) {
        $filtro_fecha = "AND t.fecha_viaje = '$fecha_1'";
      }else{
        if (!empty($fecha_2)) {
          $filtro_fecha = "AND t.fecha_viaje = '$fecha_2'";
        }     
      }      
    }    

    if (empty($id_proveedor) || $id_proveedor == 0) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND p.ruc = '$id_proveedor'"; }

    if ( empty($comprobante) || $comprobante == 0) { $filtro_comprobante = "AND t.tipo_comprobante IN ('Factura','Boleta')"; } else {
      $filtro_comprobante = "AND t.tipo_comprobante = '$comprobante'"; 
    }

    $sql4 = "SELECT SUM(t.precio_parcial) AS total, SUM(t.subtotal) AS subtotal, SUM(t.igv) AS igv
    FROM transporte AS t, proveedor AS p
    WHERE t.idproveedor = p.idproveedor  AND t.estado = '1' AND t.estado_delete = '1' $filtro_proveedor $filtro_comprobante $filtro_fecha;";
    $transporte = ejecutarConsultaSimpleFila($sql4);
    
    $total    += (empty($transporte)) ? 0 : ( empty($transporte['total']) ? 0 : floatval($transporte['total']) );
    $subtotal += (empty($transporte)) ? 0 : ( empty($transporte['subtotal']) ? 0 : floatval($transporte['subtotal']) );
    $igv      += (empty($transporte)) ? 0 : ( empty($transporte['igv']) ? 0 : floatval($transporte['igv']) );

    // SUMAS TOTALES - HOSPEDAJE --------------------------------------------------------------------------------
    $filtro_proveedor = ""; $filtro_comprobante = ""; $filtro_fecha = "";

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND fecha_comprobante BETWEEN '$fecha_1' AND '$fecha_2'";
    } else {
      if (!empty($fecha_1)) {
        $filtro_fecha = "AND fecha_comprobante = '$fecha_1'";
      }else{
        if (!empty($fecha_2)) {
          $filtro_fecha = "AND fecha_comprobante = '$fecha_2'";
        }     
      }      
    }    

    if (empty($id_proveedor) || $id_proveedor == 0) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND ruc = '$id_proveedor'"; }

    if ( empty($comprobante) || $comprobante == 0) { $filtro_comprobante = "AND tipo_comprobante IN ('Factura','Boleta')"; } else {
      $filtro_comprobante = "AND tipo_comprobante = '$comprobante'"; 
    }
    $sql5 = "SELECT SUM(precio_parcial) as total , SUM(subtotal) AS subtotal, SUM(igv) AS igv
    FROM hospedaje WHERE estado = '1' AND estado_delete = '1' $filtro_proveedor $filtro_comprobante $filtro_fecha
    ORDER BY fecha_comprobante DESC;";
    $hospedaje = ejecutarConsultaSimpleFila($sql5);
    
    $total    += (empty($hospedaje)) ? 0 : ( empty($hospedaje['total']) ? 0 : floatval($hospedaje['total']) );
    $subtotal += (empty($hospedaje)) ? 0 : ( empty($hospedaje['subtotal']) ? 0 : floatval($hospedaje['subtotal']) );
    $igv      += (empty($hospedaje)) ? 0 : ( empty($hospedaje['igv']) ? 0 : floatval($hospedaje['igv']) );

    // SUMAS TOTALES - FACTURA PENSION --------------------------------------------------------------------------------
    $filtro_proveedor = ""; $filtro_comprobante = ""; $filtro_fecha = "";

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND fp.fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
    } else {
      if (!empty($fecha_1)) {
        $filtro_fecha = "AND fp.fecha_emision = '$fecha_1'";
      }else{
        if (!empty($fecha_2)) {
          $filtro_fecha = "AND fp.fecha_emision = '$fecha_2'";
        }     
      }      
    }    

    if (empty($id_proveedor) || $id_proveedor == 0) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND prov.ruc = '$id_proveedor'"; }

    if ( empty($comprobante) || $comprobante == 0) { $filtro_comprobante = "AND fp.tipo_comprobante IN ('Factura','Boleta')"; } else {
      $filtro_comprobante = "AND fp.tipo_comprobante = '$comprobante'"; 
    }
    $sql6 = "SELECT SUM(fp.monto) AS total, SUM(fp.subtotal) AS subtotal, SUM(fp.igv) AS igv
		FROM factura_pension as fp, pension as p, proveedor as prov
		WHERE fp.idpension = p.idpension AND prov.idproveedor = p.idproveedor  AND p.estado = '1' AND p.estado_delete = '1' 
    AND fp.estado = '1' AND fp.estado_delete = '1' $filtro_proveedor $filtro_comprobante $filtro_fecha ;";
    $factura_pension = ejecutarConsultaSimpleFila($sql6);
    
    $total    += (empty($factura_pension)) ? 0 : ( empty($factura_pension['total']) ? 0 : floatval($factura_pension['total']) );
    $subtotal += (empty($factura_pension)) ? 0 : ( empty($factura_pension['subtotal']) ? 0 : floatval($factura_pension['subtotal']) );
    $igv      += (empty($factura_pension)) ? 0 : ( empty($factura_pension['igv']) ? 0 : floatval($factura_pension['igv']) );

    // SUMAS TOTALES - FACTURA BREACK --------------------------------------------------------------------------------
    $filtro_proveedor = ""; $filtro_comprobante = ""; $filtro_fecha = "";

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND fb.fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
    } else {
      if (!empty($fecha_1)) {
        $filtro_fecha = "AND fb.fecha_emision = '$fecha_1'";
      }else{
        if (!empty($fecha_2)) {
          $filtro_fecha = "AND fb.fecha_emision = '$fecha_2'";
        }     
      }      
    }    

    if (empty($id_proveedor) || $id_proveedor == 0) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND fb.ruc = '$id_proveedor'"; }

    if ( empty($comprobante) || $comprobante == 0) { $filtro_comprobante = "AND fb.tipo_comprobante IN ('Factura','Boleta')"; } else {
      $filtro_comprobante = "AND fb.tipo_comprobante = '$comprobante'"; 
    }
    $sql7 = "SELECT SUM(fb.monto) AS total, SUM(fb.subtotal) AS subtotal, SUM(fb.igv) AS igv
		FROM factura_break as fb, semana_break as sb
		WHERE  fb.idsemana_break = sb.idsemana_break AND fb.estado = '1' AND fb.estado_delete = '1' AND sb.estado = '1' 
    AND sb.estado_delete = '1' $filtro_proveedor $filtro_comprobante $filtro_fecha ;";
    $factura_break = ejecutarConsultaSimpleFila($sql7);
    
    $total    += (empty($factura_break)) ? 0 : ( empty($factura_break['total']) ? 0 : floatval($factura_break['total']) );
    $subtotal += (empty($factura_break)) ? 0 : ( empty($factura_break['subtotal']) ? 0 : floatval($factura_break['subtotal']) );
    $igv      += (empty($factura_break)) ? 0 : ( empty($factura_break['igv']) ? 0 : floatval($factura_break['igv']) );

    // SUMAS TOTALES - COMIDA EXTRA --------------------------------------------------------------------------------
    $filtro_proveedor = ""; $filtro_comprobante = ""; $filtro_fecha = "";

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND fecha_comida BETWEEN '$fecha_1' AND '$fecha_2'";
    } else {
      if (!empty($fecha_1)) {
        $filtro_fecha = "AND fecha_comida = '$fecha_1'";
      }else{
        if (!empty($fecha_2)) {
          $filtro_fecha = "AND fecha_comida = '$fecha_2'";
        }     
      }      
    }    

    if (empty($id_proveedor) || $id_proveedor == 0) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND ruc = '$id_proveedor'"; }

    if ( empty($comprobante) || $comprobante == 0) { $filtro_comprobante = "AND tipo_comprobante IN ('Factura','Boleta')"; } else {
      $filtro_comprobante = "AND tipo_comprobante = '$comprobante'"; 
    }
    $sql8 = "SELECT SUM(costo_parcial) AS total, SUM(subtotal) AS subtotal, SUM(igv) AS igv
		FROM comida_extra
		WHERE  estado = '1' AND estado_delete = '1' $filtro_proveedor $filtro_comprobante $filtro_fecha;";
    $comida_extra = ejecutarConsultaSimpleFila($sql8);
    
    $total    += (empty($comida_extra)) ? 0 : ( empty($comida_extra['total']) ? 0 : floatval($comida_extra['total']) );
    $subtotal += (empty($comida_extra)) ? 0 : ( empty($comida_extra['subtotal']) ? 0 : floatval($comida_extra['subtotal']) );
    $igv      += (empty($comida_extra)) ? 0 : ( empty($comida_extra['igv']) ? 0 : floatval($comida_extra['igv']) );

    // SUMAS TOTALES - OTRA FACTURA --------------------------------------------------------------------------------
    $filtro_proveedor = ""; $filtro_comprobante = ""; $filtro_fecha = "";

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND of.fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
    } else {
      if (!empty($fecha_1)) {
        $filtro_fecha = "AND of.fecha_emision = '$fecha_1'";
      }else{
        if (!empty($fecha_2)) {
          $filtro_fecha = "AND of.fecha_emision = '$fecha_2'";
        }     
      }      
    }    

    if (empty($id_proveedor) || $id_proveedor == 0) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND p.ruc = '$id_proveedor'"; }

    if ( empty($comprobante) || $comprobante == 0) { $filtro_comprobante = "AND of.tipo_comprobante IN ('Factura','Boleta')"; } else {
      $filtro_comprobante = "AND of.tipo_comprobante = '$comprobante'"; 
    }
    $sql9 = "SELECT SUM(of.costo_parcial) AS total, SUM(of.subtotal) AS subtotal, SUM(of.igv) AS igv
    FROM otra_factura AS of, proveedor p
    WHERE of.idproveedor = p.idproveedor AND of.estado = '1' AND of.estado_delete = '1' $filtro_proveedor $filtro_comprobante $filtro_fecha";
    $otra_factura = ejecutarConsultaSimpleFila($sql9);
    
    $total    += (empty($otra_factura)) ? 0 : ( empty($otra_factura['total']) ? 0 : floatval($otra_factura['total']) );
    $subtotal += (empty($otra_factura)) ? 0 : ( empty($otra_factura['subtotal']) ? 0 : floatval($otra_factura['subtotal']) );
    $igv      += (empty($otra_factura)) ? 0 : ( empty($otra_factura['igv']) ? 0 : floatval($otra_factura['igv']) );


    $data = array( "total" => $total, "subtotal" => $subtotal, "igv" => $igv,  );

    return $data ;
  }  

  // SELECT2
  public function select_proveedores()  {

    $data = Array();

    $sql = "SELECT idproveedor, razon_social, ruc FROM proveedor WHERE estado = '1' AND estado_delete = '1';";
    $proveedor = ejecutarConsultaArray($sql);    

    if ( !empty($proveedor) ) {
      foreach ($proveedor as $key => $value) {
        $data[] = array(
          "id" =>  $value['idproveedor'],
          "razon_social" =>  $value['razon_social'],
          "ruc" =>  $value['ruc'],
        );
      }      
    }   
    
    $sql2 = "SELECT ruc, razon_social FROM otro_gasto WHERE estado = '1' AND estado_delete = '1' AND ruc != '' AND razon_social != '';";
    $otro_gasto = ejecutarConsultaArray($sql2);

    if ( !empty($otro_gasto) ) {
      foreach ($otro_gasto as $key => $value) {
        $data[] = array(
          "id" =>  '',
          "razon_social" =>  $value['razon_social'],
          "ruc" =>  $value['ruc'],
        );
      }      
    } 

    $sql2 = "SELECT ruc, razon_social  FROM hospedaje WHERE estado = '1' AND estado_delete = '1' AND ruc != '' AND razon_social != '';";
    $hospedaje = ejecutarConsultaArray($sql2);

    if ( !empty($hospedaje) ) {
      foreach ($hospedaje as $key => $value) {
        $data[] = array(
          "id" =>  '',
          "razon_social" =>  $value['razon_social'],
          "ruc" =>  $value['ruc'],
        );
      }      
    } 

    $sql2 = "SELECT ruc, razon_social  FROM comida_extra WHERE estado = '1' AND estado_delete = '1' AND ruc != '' AND razon_social != '';";
    $comida_extra = ejecutarConsultaArray($sql2);

    if ( !empty($comida_extra) ) {
      foreach ($comida_extra as $key => $value) {
        $data[] = array(
          "id" =>  '',
          "razon_social" =>  $value['razon_social'],
          "ruc" =>  $value['ruc'],
        );
      }      
    } 

    return $data;
  }  

}

function validar_url(  $host, $ruta, $file )  {
  $tipo='nube_host';
  $armar_ruta = "";
  if ($tipo == 'local_host') { $armar_ruta = "http://localhost/admin_sevens/" . $ruta . $file; } else { if ($tipo == 'nube_host') { $armar_ruta = $host . $ruta . $file; } }

  if (empty($armar_ruta)) {
    return false;
  }

  // get_headers() realiza una petición GET por defecto,
  // cambiar el método predeterminadao a HEAD
  // Ver http://php.net/manual/es/function.get-headers.php
  stream_context_set_default([
    'http' => [
      'method' => 'HEAD',
    ],
  ]);
  $headers = @get_headers($armar_ruta);
  sscanf($headers[0], 'HTTP/%*d.%*d %d', $httpcode);

  // Aceptar solo respuesta 200 (Ok), 301 (redirección permanente) o 302 (redirección temporal)
  $accepted_response = [200, 301, 302];
  if (in_array($httpcode, $accepted_response)) {
    return true;
  } else {
    return false;
  }    
}

?>
