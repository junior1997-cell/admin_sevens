<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class ResumenGasto
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  public function facturas_compras($idproyecto, $fecha_1, $fecha_2, $id_proveedor, $comprobante) {
    $data = Array(); $data_comprobante = Array(); $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = "";

    $scheme_host=  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/admin_sevens/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');
    $host       = $_SERVER['HTTP_HOST'];

    // FACTURAS - COMPRAS INSUMOS ═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
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

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND p.ruc = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND cpp.tipo_comprobante = '$comprobante'"; 
    } 

    // var_dump($filtro_proveedor , $filtro_fecha ,$filtro_comprobante); die();

    $sql = "SELECT cpp.idproyecto, cpp.idcompra_proyecto, cpp.fecha_compra, cpp.tipo_comprobante,	cpp.serie_comprobante, cpp.descripcion, 
    cpp.total, cpp.subtotal, cpp.igv,  p.razon_social, cpp.glosa, cpp.tipo_gravada, cpp.comprobante
		FROM compra_por_proyecto as cpp, proveedor as p 
		WHERE cpp.idproveedor=p.idproveedor AND cpp.estado = '1' AND cpp.estado_delete = '1' AND  cpp.idproyecto = $idproyecto
    $filtro_proveedor $filtro_comprobante $filtro_fecha ORDER BY cpp.fecha_compra DESC;";
    $compra = ejecutarConsultaArray($sql);

    if ($compra['status'] == false) { return $compra; }

    if (!empty($compra['data'])) {
      foreach ($compra['data'] as $key => $value) {
        $data[] = array(
        	"idproyecto"        => $value['idproyecto'],
          "idtabla"           => $value['idcompra_proyecto'],
          "fecha"             => $value['fecha_compra'],
          "tipo_comprobante"  => (empty($value['tipo_comprobante']) ? '' : $value['tipo_comprobante'] ),
          "serie_comprobante" => $value['serie_comprobante'],
          "proveedor"         => $value['razon_social'],
          "total"             => $value['total'],          
          "subtotal"          => $value['subtotal'],
          "igv"               => $value['igv'],
          "glosa"             => $value['glosa'],
          "tipo_gravada"      => $value['tipo_gravada'],
          "comprobante"       => $value['comprobante'],
          "carpeta"           => 'compra_insumo',
          "subcarpeta"        => 'comprobante_compra',
          "ruta"              => 'dist/docs/compra_insumo/comprobante_compra/',
          "modulo"            => 'COMPRAS INSUMOS',
          "detalle"            => true,
        );

        if (!empty($value['comprobante'])) {
          if ( validar_url( $scheme_host, 'dist/docs/compra_insumo/comprobante_compra/', $value['comprobante']) ) {
            $data_comprobante[] = array(
              "comprobante"    => $value['comprobante'],
              "carpeta"        => 'compra_insumo',
              "subcarpeta"     => 'comprobante_compra',
              "host"           => $host,
              "ruta_file"      => $scheme_host.'dist/docs/compra_insumo/comprobante_compra/'.$value['comprobante'],
            );
          }          
        }        
      }
    }

    // FACTURAS - COMPRAS ACTIVO FIJO ═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = "";

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND cafg.fecha_compra BETWEEN '$fecha_1' AND '$fecha_2'";
    } else {
      if (!empty($fecha_1)) {
        $filtro_fecha = "AND cafg.fecha_compra = '$fecha_1'";
      }else{
        if (!empty($fecha_2)) {
          $filtro_fecha = "AND cafg.fecha_compra = '$fecha_2'";
        }     
      }      
    }    

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND p.ruc = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND cafg.tipo_comprobante = '$comprobante'"; 
    } 

    // var_dump($filtro_proveedor , $filtro_fecha ,$filtro_comprobante); die();

    $sql = "SELECT cafg.idcompra_af_general , cafg.fecha_compra, cafg.tipo_comprobante,	cafg.serie_comprobante, cafg.descripcion, 
    cafg.total, cafg.subtotal, cafg.igv,  p.razon_social, cafg.glosa, cafg.tipo_gravada, cafg.comprobante
		FROM compra_af_general as cafg, proveedor as p 
		WHERE cafg.idproveedor=p.idproveedor AND cafg.estado = '1' AND cafg.estado_delete = '1' 
    $filtro_proveedor $filtro_comprobante $filtro_fecha ORDER BY cafg.fecha_compra DESC;";
    $compra = ejecutarConsultaArray($sql);

    if ($compra['status'] == false) { return $compra; }

    if (!empty($compra['data'])) {
      foreach ($compra['data'] as $key => $value) {
        $data[] = array(
        	"idproyecto"        => '',
          "idtabla"           => $value['idcompra_af_general'],
          "fecha"             => $value['fecha_compra'],
          "tipo_comprobante"  => (empty($value['tipo_comprobante']) ? '' : $value['tipo_comprobante'] ),
          "serie_comprobante" => $value['serie_comprobante'],
          "proveedor"         => $value['razon_social'],
          "total"             => $value['total'],          
          "subtotal"          => $value['subtotal'],
          "igv"               => $value['igv'],
          "glosa"             => $value['glosa'],
          "tipo_gravada"      => $value['tipo_gravada'],
          "comprobante"       => $value['comprobante'],
          "carpeta"           => 'compra_activo_fijo',
          "subcarpeta"        => 'comprobante_compra',
          "ruta"              => 'dist/docs/compra_activo_fijo/comprobante_compra/',
          "modulo"            => 'COMPRAS ACTIVO FIJO',
          "detalle"            => true,
        );

        if (!empty($value['comprobante'])) {
          if ( validar_url( $scheme_host, 'dist/docs/compra_activo_fijo/comprobante_compra/', $value['comprobante']) ) {
            $data_comprobante[] = array(
              "comprobante"    => $value['comprobante'],
              "carpeta"        => 'compra_activo_fijo',
              "subcarpeta"     => 'comprobante_compra',
              "host"           => $host,
              "ruta_file"      => $scheme_host.'dist/docs/compra_activo_fijo/comprobante_compra/'.$value['comprobante'],
            );
          }          
        }        
      }
    }

    // FACTURAS - SERVICIO MAQUINA Y/O EQUIPO ════════════════════════════════════════════════════════════════════════════════════════════════════════
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
    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND f.tipo_comprobante = '$comprobante'"; 
    } 
    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND prov.ruc = '$id_proveedor'"; }

    $sql2 = "SELECT f.idfactura, f.idproyecto, f.codigo, f.fecha_emision, f.monto, f.subtotal, f.igv,
    f.nota, mq.nombre, prov.razon_social, f.descripcion, f.imagen
    FROM factura as f, proyecto as p, maquinaria as mq, proveedor as prov
    WHERE f.idmaquinaria=mq.idmaquinaria AND mq.idproveedor=prov.idproveedor AND f.idproyecto=p.idproyecto 
    AND f.estado = '1' AND f.estado_delete = '1' AND  f.idproyecto = $idproyecto $filtro_proveedor $filtro_comprobante $filtro_fecha
    ORDER BY f.fecha_emision DESC;";
    $maquinaria_equipo =  ejecutarConsultaArray($sql2);

    if ($maquinaria_equipo['status'] == false) { return $maquinaria_equipo; }

    if (!empty($maquinaria_equipo['data'])) {
      foreach ($maquinaria_equipo['data'] as $key => $value) {
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
          "carpeta"           => 'servicio_maquina',
          "subcarpeta"        => 'comprobante_servicio',
          "ruta"              => 'dist/docs/servicio_maquina/comprobante_servicio/',
          "modulo"              => 'MAQUINA Y/O EQUIPO',
          "detalle"            => false,
        );
        if (!empty($value['imagen'])) {
          if (validar_url( $scheme_host, 'dist/docs/servicio_maquina/comprobante_servicio/', $value['imagen'])) {
            $data_comprobante[] = array(
              "comprobante"       => $value['imagen'],
              "carpeta"           => 'servicio_maquina',
              "subcarpeta"        => 'comprobante_servicio',
              "host"              => $host,
              "ruta_file"         => $scheme_host.'dist/docs/servicio_maquina/comprobante_servicio/'.$value['imagen'],
            );
          }
          
        }
      }
    }
    // FACTURAS - SUB CONTRATO ═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = "";

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND s.fecha_subcontrato BETWEEN '$fecha_1' AND '$fecha_2'";
    } else {
      if (!empty($fecha_1)) {
        $filtro_fecha = "AND s.fecha_subcontrato = '$fecha_1'";
      }else{
        if (!empty($fecha_2)) {
          $filtro_fecha = "AND s.fecha_subcontrato = '$fecha_2'";
        }     
      }      
    }    

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND p.ruc = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND s.tipo_comprobante = '$comprobante'"; 
    }
    
    $sql3 = "SELECT s.idsubcontrato, s.idproyecto, s.idproveedor, s.tipo_comprobante, s.numero_comprobante, s.forma_de_pago, 
    s.fecha_subcontrato, s.val_igv, s.subtotal, s.igv, s.costo_parcial, s.descripcion, s.glosa, s.comprobante, p.razon_social, p.tipo_documento, p.ruc
    FROM subcontrato AS s, proveedor as p
    WHERE s.idproveedor = p.idproveedor and s.estado = '1' AND s.estado_delete = '1' AND idproyecto = $idproyecto $filtro_proveedor $filtro_comprobante $filtro_fecha ORDER BY s.fecha_subcontrato DESC;";
    $otro_gasto =  ejecutarConsultaArray($sql3);

    if ($otro_gasto['status'] == false) { return $otro_gasto; }

    if (!empty($otro_gasto['data'])) {
      foreach ($otro_gasto['data'] as $key => $value) {
        $data[] = array(
        	"idproyecto"        => $value['idproyecto'],
          "idtabla"           => $value['idsubcontrato'],
          "fecha"             => $value['fecha_subcontrato'],
          "tipo_comprobante"  => (empty($value['tipo_comprobante']) ? '' :$value['tipo_comprobante'] ) ,
          "serie_comprobante" => $value['numero_comprobante'],
          "proveedor"         => $value['razon_social'],
          "total"             => $value['costo_parcial'],
          "igv"               => $value['igv'],
          "subtotal"          => $value['subtotal'],
          "glosa"             => $value['glosa'],
          "tipo_gravada"      => '',
          "comprobante"       => $value['comprobante'],
          "carpeta"           => 'sub_contrato',
          "subcarpeta"        => 'comprobante_subcontrato',
          "ruta"              => 'dist/docs/sub_contrato/comprobante_subcontrato/',
          "modulo"            => 'SUB CONTRATO',
          "detalle"           => false,
        );
        if (!empty($value['comprobante'])) {
          if ( validar_url( $scheme_host, 'dist/docs/sub_contrato/comprobante_subcontrato/', $value['comprobante']) ) {
            $data_comprobante[] = array(
              "comprobante"       => $value['comprobante'],
              "carpeta"           => 'sub_contrato',
              "subcarpeta"        => 'comprobante_subcontrato',
              "host"              => $host,
              "ruta_file"         => $scheme_host.'dist/docs/sub_contrato/comprobante_subcontrato/'.$value['comprobante'],
            );
          }          
        }
      }
    }
    // FACTURAS - PLANILLA SEGURO ═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = "";

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND ps.fecha_p_s BETWEEN '$fecha_1' AND '$fecha_2'";
    } else {
      if (!empty($fecha_1)) {
        $filtro_fecha = "AND ps.fecha_p_s = '$fecha_1'";
      }else{
        if (!empty($fecha_2)) {
          $filtro_fecha = "AND ps.fecha_p_s = '$fecha_2'";
        }     
      }      
    }    

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND ps.idplanilla_seguro = '0'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND ps.tipo_comprobante = '$comprobante'"; 
    }

    $sql3 = "SELECT ps.idplanilla_seguro, ps.idproyecto, ps.tipo_comprobante, ps.numero_comprobante, ps.forma_de_pago, 
    ps.fecha_p_s, ps.subtotal, ps.igv, ps.costo_parcial, ps.descripcion, ps.val_igv, ps.tipo_gravada, ps.comprobante
    FROM planilla_seguro as ps, proyecto as p
    WHERE ps.idproyecto = p.idproyecto and ps.estado ='1' and ps.estado_delete = '1' 
    AND ps.idproyecto = $idproyecto $filtro_proveedor $filtro_comprobante $filtro_fecha ORDER BY ps.fecha_p_s DESC;";
    $otro_gasto =  ejecutarConsultaArray($sql3);

    if ($otro_gasto['status'] == false) { return $otro_gasto; }

    if (!empty($otro_gasto['data'])) {
      foreach ($otro_gasto['data'] as $key => $value) {
        $data[] = array(
        	"idproyecto"        => $value['idproyecto'],
          "idtabla"           => $value['idplanilla_seguro'],
          "fecha"             => $value['fecha_p_s'],
          "tipo_comprobante"  => (empty($value['tipo_comprobante']) ? '' :$value['tipo_comprobante'] ),
          "serie_comprobante" => $value['numero_comprobante'],
          "proveedor"         => '',
          "total"             => $value['costo_parcial'],
          "igv"               => $value['igv'],
          "subtotal"          => $value['subtotal'],
          "glosa"             => '',
          "tipo_gravada"      => $value['tipo_gravada'],
          "comprobante"       => $value['comprobante'],
          "carpeta"           => 'planilla_seguro',
          "subcarpeta"        => 'comprobante',
          "ruta"              => 'dist/docs/planilla_seguro/comprobante/',
          "modulo"            => 'PLANILLA SEGURO',
          "detalle"           => false,
        );
        if (!empty($value['comprobante'])) {
          if ( validar_url( $scheme_host, 'dist/docs/planilla_seguro/comprobante/', $value['comprobante']) ) {
            $data_comprobante[] = array(
              "comprobante"       => $value['comprobante'],
              "carpeta"           => 'planilla_seguro',
              "subcarpeta"        => 'comprobante',
              "host"              => $host,
              "ruta_file"         => $scheme_host.'dist/docs/planilla_seguro/comprobante/'.$value['comprobante'],
            );
          }          
        }
      }
    }

    // FACTURAS - OTRO GASTO ═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
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

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND idotro_gasto = '0'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND tipo_comprobante = '$comprobante'"; 
    }

    $sql3 = "SELECT idproyecto, idotro_gasto, razon_social, tipo_comprobante, numero_comprobante, fecha_g, 
    costo_parcial, subtotal, igv, glosa, comprobante, tipo_gravada
    FROM otro_gasto 
    WHERE  estado = '1' AND estado_delete = '1' AND idproyecto = $idproyecto $filtro_proveedor $filtro_comprobante $filtro_fecha ORDER BY fecha_g DESC;";
    $otro_gasto =  ejecutarConsultaArray($sql3);

    if ($otro_gasto['status'] == false) { return $otro_gasto; }

    if (!empty($otro_gasto['data'])) {
      foreach ($otro_gasto['data'] as $key => $value) {
        $data[] = array(
        	"idproyecto"        => $value['idproyecto'],
          "idtabla"           => $value['idotro_gasto'],
          "fecha"             => $value['fecha_g'],
          "tipo_comprobante"  => (empty($value['tipo_comprobante']) ? '' :$value['tipo_comprobante'] ),
          "serie_comprobante" => $value['numero_comprobante'],
          "proveedor"         => $value['razon_social'],
          "total"             => $value['costo_parcial'],
          "igv"               => $value['igv'],
          "subtotal"          => $value['subtotal'],
          "glosa"             => $value['glosa'],
          "tipo_gravada"      => $value['tipo_gravada'],
          "comprobante"       => $value['comprobante'],
          "carpeta"           => 'otro_gasto',
          "subcarpeta"        => 'comprobante',
          "ruta"              => 'dist/docs/otro_gasto/comprobante/',
          "modulo"            => 'OTRO GASTO',
          "detalle"           => false,
        );
        if (!empty($value['comprobante'])) {
          if ( validar_url( $scheme_host, 'dist/docs/otro_gasto/comprobante/', $value['comprobante']) ) {
            $data_comprobante[] = array(
              "comprobante"       => $value['comprobante'],
              "carpeta"           => 'otro_gasto',
              "subcarpeta"        => 'comprobante',
              "host"              => $host,
              "ruta_file"         => $scheme_host.'dist/docs/otro_gasto/comprobante/'.$value['comprobante'],
            );
          }          
        }
      }
    }

    // FACTURAS - TRANSPORTE ═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
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

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND p.ruc = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND t.tipo_comprobante = '$comprobante'"; 
    }

    $sql4 = "SELECT t.idtransporte, t.idproyecto, p.razon_social, t.tipo_comprobante, t.numero_comprobante, t.fecha_viaje, 
    t.precio_parcial, t.subtotal, t.igv,  t.comprobante , t.glosa , t.tipo_gravada
    FROM transporte AS t, proveedor AS p
    WHERE t.idproveedor = p.idproveedor  AND t.estado = '1' AND t.estado_delete = '1' AND  t.idproyecto = $idproyecto $filtro_proveedor $filtro_comprobante $filtro_fecha
    ORDER BY t.fecha_viaje DESC;";
    $transporte =  ejecutarConsultaArray($sql4);

    if ($transporte['status'] == false) { return $transporte; }

    if (!empty($transporte['data'])) {
      foreach ($transporte['data'] as $key => $value) {
        $data[] = array(
        	"idproyecto"        => $value['idproyecto'],
          "idtabla"           => $value['idtransporte'],
          "fecha"             => $value['fecha_viaje'],
          "tipo_comprobante"  => (empty($value['tipo_comprobante']) ? '' : $value['tipo_comprobante'] ),
          "serie_comprobante" => $value['numero_comprobante'],
          "proveedor"         => $value['razon_social'],
          "total"             => $value['precio_parcial'],          
          "subtotal"          => $value['subtotal'],
          "igv"               => $value['igv'],
          "glosa"             => $value['glosa'],
          "tipo_gravada"      => $value['tipo_gravada'],
          "comprobante"       => $value['comprobante'],
          "carpeta"           => 'transporte',
          "subcarpeta"        => 'comprobante',
          "ruta"              => 'dist/docs/transporte/comprobante/',
          "modulo"            => 'TRANSPORTE',
          "detalle"           => false,
        );
        if (!empty($value['comprobante'])) {
          if ( validar_url( $scheme_host, 'dist/docs/transporte/comprobante/', $value['comprobante']) ) {
            $data_comprobante[] = array(
              "comprobante"       => $value['comprobante'],
              "carpeta"           => 'transporte',
              "subcarpeta"        => 'comprobante',
              "host"              => $host,
              "ruta_file"         => $scheme_host.'dist/docs/transporte/comprobante/'.$value['comprobante'],
            );
          }          
        }
      }
    }

    // FACTURAS - HOSPEDAJE ═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
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

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND ruc = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND tipo_comprobante = '$comprobante'"; 
    }

    $sql5 = "SELECT  idhospedaje, idproyecto, razon_social, fecha_comprobante, tipo_comprobante, numero_comprobante, subtotal, igv, 
    precio_parcial, glosa, comprobante , tipo_gravada
    FROM hospedaje 
    WHERE estado = '1' AND estado_delete = '1' AND  idproyecto = $idproyecto $filtro_proveedor $filtro_comprobante $filtro_fecha
    ORDER BY fecha_comprobante DESC;";
    $hospedaje =  ejecutarConsultaArray($sql5);

    if ($hospedaje['status'] == false) { return $hospedaje; }

    if (!empty($hospedaje['data'])) {
      foreach ($hospedaje['data'] as $key => $value) {
        $data[] = array(
        	"idproyecto"        => $value['idproyecto'],
          "idtabla"           => $value['idhospedaje'],
          "fecha"             => $value['fecha_comprobante'],
          "tipo_comprobante"  => (empty($value['tipo_comprobante']) ? '' :$value['tipo_comprobante'] ) ,
          "serie_comprobante" => $value['numero_comprobante'],
          "proveedor"         => $value['razon_social'],
          "total"             => $value['precio_parcial'],
          "igv"               => $value['igv'],
          "subtotal"          => $value['subtotal'],
          "glosa"             => $value['glosa'],
          "tipo_gravada"      => $value['tipo_gravada'],
          "comprobante"       => $value['comprobante'],
          "carpeta"           => 'hospedaje',
          "subcarpeta"        => 'comprobante',
          "ruta"              => 'dist/docs/hospedaje/comprobante/',
          "modulo"            => 'HOSPEDAJE',
          "detalle"           => false,
        );
        if (!empty($value['comprobante'])) {
          if ( validar_url( $scheme_host, 'dist/docs/hospedaje/comprobante/', $value['comprobante']) ) {
            $data_comprobante[] = array(
              "comprobante"       => $value['comprobante'],
              "carpeta"           => 'hospedaje',
              "subcarpeta"        => 'comprobante',
              "host"              => $host,
              "ruta_file"         => $scheme_host.'dist/docs/hospedaje/comprobante/'.$value['comprobante'],
            );
          }          
        }
      }
    }

    // FACTURAS - PENSION ═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
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

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND prov.ruc = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND fp.tipo_comprobante = '$comprobante'"; 
    }

    $sql6 = "SELECT p.idproyecto, fp.idfactura_pension, prov.razon_social, fp.tipo_comprobante, fp.nro_comprobante, fp.fecha_emision, 
    fp.monto, fp.subtotal, fp.igv, fp.comprobante, fp.glosa , fp.tipo_gravada
		FROM factura_pension as fp, pension as p, proveedor as prov
		WHERE fp.idpension = p.idpension AND prov.idproveedor = p.idproveedor  AND p.estado = '1' AND p.estado_delete = '1' 
    AND fp.estado = '1' AND fp.estado_delete = '1' AND  p.idproyecto = $idproyecto
     $filtro_proveedor $filtro_comprobante $filtro_fecha
    ORDER BY fp.fecha_emision DESC;";
    $factura_pension =  ejecutarConsultaArray($sql6);

    if ($factura_pension['status'] == false) { return $factura_pension; }

    if (!empty($factura_pension['data'])) {
      foreach ($factura_pension['data'] as $key => $value) {
        $data[] = array(
        	"idproyecto"        => $value['idproyecto'],
          "idtabla"           => $value['idfactura_pension'],
          "fecha"             => $value['fecha_emision'],
          "tipo_comprobante"  => (empty($value['tipo_comprobante']) ? '' : $value['tipo_comprobante'] ),
          "serie_comprobante" => $value['nro_comprobante'],
          "proveedor"         => $value['razon_social'],
          "total"             => $value['monto'],          
          "subtotal"          => $value['subtotal'],
          "igv"               => $value['igv'],
          "glosa"             => $value['glosa'],
          "tipo_gravada"      => $value['tipo_gravada'],
          "comprobante"       => $value['comprobante'],
          "carpeta"           => 'pension',
          "subcarpeta"        => 'comprobante',
          "ruta"              => 'dist/docs/pension/comprobante/',
          "modulo"            => 'PENSION',
          "detalle"           => false,
        );
        if (!empty($value['comprobante'])) {
          if ( validar_url( $scheme_host, 'dist/docs/pension/comprobante/', $value['comprobante']) ) {
            $data_comprobante[] = array(
              "comprobante"       => $value['comprobante'],
              "carpeta"           => 'pension',
              "subcarpeta"        => 'comprobante',
              "host"              => $host,
              "ruta_file"         => $scheme_host.'dist/docs/pension/comprobante/'.$value['comprobante'],
            );
          }          
        }
      }
    }

    // FACTURAS - BRACK ═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
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

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND fb.ruc = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND fb.tipo_comprobante = '$comprobante'"; 
    }

    $sql7 = "SELECT sb.idproyecto, fb.idfactura_break, fb.fecha_emision, fb.tipo_comprobante, fb.nro_comprobante, fb.razon_social,  
    fb.monto, fb.subtotal, fb.igv, fb.glosa,  fb.comprobante, fb.tipo_gravada
		FROM factura_break as fb, semana_break as sb
		WHERE  fb.idsemana_break = sb.idsemana_break  
    AND fb.estado = '1' AND fb.estado_delete = '1' AND sb.estado = '1' AND sb.estado_delete = '1' AND  sb.idproyecto = $idproyecto
     $filtro_proveedor $filtro_comprobante $filtro_fecha
    ORDER BY fb.fecha_emision DESC;";
    $factura_break =  ejecutarConsultaArray($sql7);

    if ($factura_break['status'] == false) { return $factura_break; }

    if (!empty($factura_break['data'])) {
      foreach ($factura_break['data'] as $key => $value) {
        $data[] = array(
        	"idproyecto"        => $value['idproyecto'],
          "idtabla"           => $value['idfactura_break'],
          "fecha"             => $value['fecha_emision'],
          "tipo_comprobante"  => (empty($value['tipo_comprobante']) ? '' : $value['tipo_comprobante'] ),
          "serie_comprobante" => $value['nro_comprobante'],
          "proveedor"         => $value['razon_social'],
          "total"             => $value['monto'],          
          "subtotal"          => $value['subtotal'],
          "igv"               => $value['igv'],
          "glosa"             => $value['glosa'],
          "tipo_gravada"      => $value['tipo_gravada'],
          "comprobante"       => $value['comprobante'],
          "carpeta"           => 'break',
          "subcarpeta"        => 'comprobante',
          "ruta"              => 'dist/docs/break/comprobante/',
          "modulo"            => 'BREAK',
          "detalle"            => false,
        );
        if (!empty($value['comprobante'])) {
          if ( validar_url( $scheme_host, 'dist/docs/break/comprobante/', $value['comprobante']) ) {
            $data_comprobante[] = array(
              "comprobante"       => $value['comprobante'],
              "carpeta"           => 'break',
              "subcarpeta"        => 'comprobante',
              "host"              => $host,
              "ruta_file"         => $scheme_host.'dist/docs/break/comprobante/'.$value['comprobante'],
            );
          }          
        }
      }
    }

    // FACTURAS - COMIDA EXTRA ═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
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

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND ruc = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND tipo_comprobante = '$comprobante'"; 
    }

    $sql8 = "SELECT idproyecto, idcomida_extra, fecha_comida, tipo_comprobante, numero_comprobante, razon_social, 
    costo_parcial, subtotal, igv, glosa, comprobante, tipo_gravada
		FROM comida_extra
		WHERE  estado = '1' AND estado_delete = '1' AND idproyecto = $idproyecto $filtro_proveedor $filtro_comprobante $filtro_fecha
    ORDER BY fecha_comida DESC;";
    $comida_extra =  ejecutarConsultaArray($sql8);

    if ($comida_extra['status'] == false) { return $comida_extra; }

    if (!empty($comida_extra['data'])) {
      foreach ($comida_extra['data'] as $key => $value) {
        $data[] = array(
        	"idproyecto"        => $value['idproyecto'],
          "idtabla"           => $value['idcomida_extra'],
          "fecha"             => $value['fecha_comida'],
          "tipo_comprobante"  => (empty($value['tipo_comprobante']) ? '' : $value['tipo_comprobante'] ) ,
          "serie_comprobante" => $value['numero_comprobante'],
          "proveedor"         => $value['razon_social'],
          "total"             => $value['costo_parcial'],          
          "subtotal"          => $value['subtotal'],
          "igv"               => $value['igv'],
          "glosa"             => $value['glosa'],
          "tipo_gravada"      => $value['tipo_gravada'],
          "comprobante"       => $value['comprobante'],
          "carpeta"           => 'comida_extra',
          "subcarpeta"        => 'comprobante',
          "ruta"              => 'dist/docs/comida_extra/comprobante/',
          "modulo"            => 'COMIDA EXTRA',
          "detalle"           => false,
        );
        if (!empty($value['comprobante'])) {
          if ( validar_url( $scheme_host, 'dist/docs/comida_extra/comprobante/', $value['comprobante']) ) {
            $data_comprobante[] = array(
              "comprobante"       => $value['comprobante'],
              "carpeta"           => 'comida_extra',
              "subcarpeta"        => 'comprobante',
              "host"              => $host,
              "ruta_file"         => $scheme_host.'dist/docs/comida_extra/comprobante/'.$value['comprobante'],
            );
          }          
        }
      }
    }
    // FACTURAS - OTRO INGRESO ═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = "";

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND oi.fecha_i BETWEEN '$fecha_1' AND '$fecha_2'";
    } else {
      if (!empty($fecha_1)) {
        $filtro_fecha = "AND oi.fecha_i = '$fecha_1'";
      }else{
        if (!empty($fecha_2)) {
          $filtro_fecha = "AND oi.fecha_i = '$fecha_2'";
        }     
      }      
    }    

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND oi.ruc = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND oi.tipo_comprobante = '$comprobante'"; 
    }

    $sql9 = "SELECT oi.idotro_ingreso, oi.idproyecto, oi.idproveedor, oi.ruc, oi.razon_social, oi.direccion, oi.tipo_comprobante, 
    oi.numero_comprobante, oi.forma_de_pago, oi.fecha_i, oi.subtotal, oi.igv, oi.costo_parcial, oi.descripcion, oi.glosa, 
    oi.comprobante, oi.val_igv, oi.tipo_gravada
    FROM otro_ingreso as oi, proyecto as p
    WHERE oi.idproyecto = p.idproyecto AND oi.estado = '1' AND oi.estado_delete = '1' 
    AND oi.idproyecto = $idproyecto $filtro_proveedor $filtro_comprobante $filtro_fecha
    ORDER BY oi.fecha_i DESC;";
    $otra_factura =  ejecutarConsultaArray($sql9);

    if ($otra_factura['status'] == false) { return $otra_factura; }

    if (!empty($otra_factura['data'])) {
      foreach ($otra_factura['data'] as $key => $value) {
        $data[] = array(
        	"idproyecto"        => $value['idproyecto'],
          "idtabla"           => $value['idotro_ingreso'],
          "fecha"             => $value['fecha_i'],
          "tipo_comprobante"  => (empty($value['tipo_comprobante']) ? '' : $value['tipo_comprobante']) ,
          "serie_comprobante" => $value['numero_comprobante'],
          "proveedor"         => $value['razon_social'],
          "total"             => $value['costo_parcial'],
          "igv"               => $value['igv'],
          "subtotal"          => $value['subtotal'],
          "glosa"             => $value['glosa'],
          "tipo_gravada"      => $value['tipo_gravada'],
          "comprobante"       => $value['comprobante'],
          "carpeta"           => 'otro_ingreso',
          "subcarpeta"        => 'comprobante',
          "ruta"              => 'dist/docs/otro_ingreso/comprobante/',
          "modulo"            => 'OTRO INGRESO',
          "detalle"           => false,
        );
        if (!empty($value['comprobante'])) {
          if ( validar_url( $scheme_host, 'dist/docs/otro_ingreso/comprobante/', $value['comprobante']) ) {
            $data_comprobante[] = array(
              "comprobante"       => $value['comprobante'],
              "carpeta"           => 'otro_ingreso',
              "subcarpeta"        => 'comprobante',
              "host"              => $host,
              "ruta_file"         => $scheme_host.'dist/docs/otro_ingreso/comprobante/'.$value['comprobante'],
            );
          }          
        }
      }
    }

    // FACTURAS - OTRA FACTURA ═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
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

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND p.ruc = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND of.tipo_comprobante = '$comprobante'"; 
    }

    $sql9 = "SELECT of.idotra_factura, of.fecha_emision, of.tipo_comprobante, of.numero_comprobante, p.razon_social, of.costo_parcial, 
    of.subtotal, of.igv, of.glosa, of.comprobante , of.tipo_gravada
    FROM otra_factura AS of, proveedor p
    WHERE of.idproveedor = p.idproveedor AND of.estado = '1' AND of.estado_delete = '1' $filtro_proveedor $filtro_comprobante $filtro_fecha
    ORDER BY of.fecha_emision DESC;";
    $otra_factura =  ejecutarConsultaArray($sql9);

    if ($otra_factura['status'] == false) { return $otra_factura; }

    if (!empty($otra_factura['data'])) {
      foreach ($otra_factura['data'] as $key => $value) {
        $data[] = array(
        	"idproyecto"        => '',
          "idtabla"           => $value['idotra_factura'],
          "fecha"             => $value['fecha_emision'],
          "tipo_comprobante"  => (empty($value['tipo_comprobante']) ? '' : $value['tipo_comprobante']) ,
          "serie_comprobante" => $value['numero_comprobante'],
          "proveedor"         => $value['razon_social'],
          "total"             => $value['costo_parcial'],
          "igv"               => $value['igv'],
          "subtotal"          => $value['subtotal'],
          "glosa"             => $value['glosa'],
          "tipo_gravada"      => $value['tipo_gravada'],
          "comprobante"       => $value['comprobante'],
          "carpeta"           => 'otra_factura',
          "subcarpeta"        => 'comprobante',
          "ruta"              => 'dist/docs/otra_factura/comprobante/',
          "modulo"            => 'OTRA FACTURA',
          "detalle"           => false,
        );
        if (!empty($value['comprobante'])) {
          if ( validar_url( $scheme_host, 'dist/docs/otra_factura/comprobante/', $value['comprobante']) ) {
            $data_comprobante[] = array(
              "comprobante"       => $value['comprobante'],
              "carpeta"           => 'otra_factura',
              "subcarpeta"        => 'comprobante',
              "host"              => $host,
              "ruta_file"         => $scheme_host.'dist/docs/otra_factura/comprobante/'.$value['comprobante'],
            );
          }          
        }
      }
    }

    $retorno = array(
      "status"=> true,
      "message"=> 'todo oka',
      "data"=> [
        "datos"              => $data,
        "data_comprobante"  => $data_comprobante,
      ]
      
    );

    return $retorno;
  }

  public function suma_totales($idproyecto, $fecha_1, $fecha_2, $id_proveedor, $comprobante) {

    $data = Array(); $total = 0; $subtotal = 0; $igv = 0;

    // SUMAS TOTALES - COMPRA INSUMO --------------------------------------------------------------------------------
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

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND p.ruc = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND cpp.tipo_comprobante = '$comprobante'"; 
    }

    $sql = "SELECT SUM(cpp.total) AS total, SUM(cpp.subtotal) AS subtotal, SUM(cpp.igv) AS igv
    FROM compra_por_proyecto AS cpp, proveedor p
    WHERE cpp.idproveedor = p.idproveedor AND cpp.estado = '1' AND cpp.estado_delete = '1' AND  cpp.idproyecto = $idproyecto $filtro_proveedor $filtro_comprobante $filtro_fecha ;";
    $compra = ejecutarConsultaSimpleFila($sql);

    if ($compra['status'] == false) { return $compra; }

    $total    += (empty($compra['data'])) ? 0 : ( empty($compra['data']['total']) ? 0 : floatval($compra['data']['total']) );
    $subtotal += (empty($compra['data'])) ? 0 : ( empty($compra['data']['subtotal']) ? 0 : floatval($compra['data']['subtotal']) );
    $igv      += (empty($compra['data'])) ? 0 : ( empty($compra['data']['igv']) ? 0 : floatval($compra['data']['igv']) );

    // SUMAS TOTALES - COMPRAS DE ACTIVO FIJO --------------------------------------------------------------------------------
    $filtro_proveedor = ""; $filtro_comprobante = ""; $filtro_fecha = "";

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND cafg.fecha_compra BETWEEN '$fecha_1' AND '$fecha_2'";
    } else {
      if (!empty($fecha_1)) {
        $filtro_fecha = "AND cafg.fecha_compra = '$fecha_1'";
      }else{
        if (!empty($fecha_2)) {
          $filtro_fecha = "AND cafg.fecha_compra = '$fecha_2'";
        }     
      }      
    }    

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND p.ruc = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND cafg.tipo_comprobante = '$comprobante'"; 
    }

    $sql = "SELECT SUM(cafg.total) AS total, SUM(cafg.subtotal) AS subtotal, SUM(cafg.igv) AS igv
    FROM compra_af_general  AS cafg, proveedor p
    WHERE cafg.idproveedor = p.idproveedor AND cafg.estado = '1' AND cafg.estado_delete = '1'  $filtro_proveedor $filtro_comprobante $filtro_fecha ;";
    $compra = ejecutarConsultaSimpleFila($sql);

    if ($compra['status'] == false) { return $compra; }

    $total    += (empty($compra['data'])) ? 0 : ( empty($compra['data']['total']) ? 0 : floatval($compra['data']['total']) );
    $subtotal += (empty($compra['data'])) ? 0 : ( empty($compra['data']['subtotal']) ? 0 : floatval($compra['data']['subtotal']) );
    $igv      += (empty($compra['data'])) ? 0 : ( empty($compra['data']['igv']) ? 0 : floatval($compra['data']['igv']) );
     
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

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND prov.ruc = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND f.tipo_comprobante = '$comprobante'"; 
    }

    $sql2 = "SELECT SUM(f.monto) AS total , SUM(f.subtotal) AS subtotal, SUM(f.igv) AS igv
    FROM factura as f, proyecto as p, maquinaria as mq, proveedor as prov
    WHERE f.idmaquinaria=mq.idmaquinaria AND mq.idproveedor=prov.idproveedor AND f.idproyecto=p.idproyecto 
    AND f.estado = '1' AND f.estado_delete = '1' AND  f.idproyecto = $idproyecto $filtro_proveedor $filtro_comprobante $filtro_fecha;";
    $maquinaria = ejecutarConsultaSimpleFila($sql2);

    if ($maquinaria['status'] == false) { return $maquinaria; } 

    $total    += (empty($maquinaria['data'])) ? 0 : ( empty($maquinaria['data']['total']) ? 0 : floatval($maquinaria['data']['total']) );
    $subtotal += (empty($maquinaria['data'])) ? 0 : ( empty($maquinaria['data']['subtotal']) ? 0 : floatval($maquinaria['data']['subtotal']) );
    $igv      += (empty($maquinaria['data'])) ? 0 : ( empty($maquinaria['data']['igv']) ? 0 : floatval($maquinaria['data']['igv']) );

    // SUMAS TOTALES - SUB CONTRATO --------------------------------------------------------------------------------
    $filtro_proveedor = ""; $filtro_comprobante = ""; $filtro_fecha = "";

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND s.fecha_subcontrato BETWEEN '$fecha_1' AND '$fecha_2'";
    } else {
      if (!empty($fecha_1)) {
        $filtro_fecha = "AND s.fecha_subcontrato = '$fecha_1'";
      }else{
        if (!empty($fecha_2)) {
          $filtro_fecha = "AND s.fecha_subcontrato = '$fecha_2'";
        }     
      }      
    }    

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND p.ruc = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND s.tipo_comprobante = '$comprobante'"; 
    }

    $sql3 = "SELECT SUM(s.subtotal) as subtotal, SUM(s.igv) as igv, SUM(s.costo_parcial) as total
    FROM subcontrato AS s, proveedor as p
    WHERE s.idproveedor = p.idproveedor and s.estado = '1' AND s.estado_delete = '1' AND  idproyecto = $idproyecto 
    $filtro_proveedor $filtro_comprobante $filtro_fecha;";
    $otro_gasto = ejecutarConsultaSimpleFila($sql3);

    if ($otro_gasto['status'] == false) { return $otro_gasto; } 
    
    $total    += (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['total']) ? 0 : floatval($otro_gasto['data']['total']) );
    $subtotal += (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['subtotal']) ? 0 : floatval($otro_gasto['data']['subtotal']) );
    $igv      += (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['igv']) ? 0 : floatval($otro_gasto['data']['igv']) );

    // SUMAS TOTALES - PLANILLA SEGURO --------------------------------------------------------------------------------

    $filtro_proveedor = ""; $filtro_comprobante = ""; $filtro_fecha = "";

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND ps.fecha_p_s BETWEEN '$fecha_1' AND '$fecha_2'";
    } else {
      if (!empty($fecha_1)) {
        $filtro_fecha = "AND ps.fecha_p_s = '$fecha_1'";
      }else{
        if (!empty($fecha_2)) {
          $filtro_fecha = "AND ps.fecha_p_s = '$fecha_2'";
        }     
      }      
    }    

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND  ps.idplanilla_seguro = '0'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND ps.tipo_comprobante = '$comprobante'"; 
    }

    $sql3 = "SELECT SUM(ps.subtotal) AS subtotal, SUM(ps.igv) AS igv, SUM(ps.costo_parcial) AS total
    FROM planilla_seguro as ps, proyecto as p
    WHERE ps.idproyecto = p.idproyecto and ps.estado ='1' and ps.estado_delete = '1'
     AND  ps.idproyecto = $idproyecto $filtro_proveedor $filtro_comprobante $filtro_fecha;";
    $otro_gasto = ejecutarConsultaSimpleFila($sql3);

    if ($otro_gasto['status'] == false) { return $otro_gasto; } 
    
    $total    += (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['total']) ? 0 : floatval($otro_gasto['data']['total']) );
    $subtotal += (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['subtotal']) ? 0 : floatval($otro_gasto['data']['subtotal']) );
    $igv      += (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['igv']) ? 0 : floatval($otro_gasto['data']['igv']) );

    // SUMAS TOTALES - OTRO GASTO --------------------------------------------------------------------------------
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

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND ruc = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND tipo_comprobante = '$comprobante'"; 
    }

    $sql3 = "SELECT SUM(costo_parcial) as total, SUM(subtotal) AS subtotal, SUM(igv) AS igv
    FROM otro_gasto  
    WHERE estado = '1' AND estado_delete = '1' AND  idproyecto = $idproyecto $filtro_proveedor $filtro_comprobante $filtro_fecha;";
    $otro_gasto = ejecutarConsultaSimpleFila($sql3);

    if ($otro_gasto['status'] == false) { return $otro_gasto; } 
    
    $total    += (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['total']) ? 0 : floatval($otro_gasto['data']['total']) );
    $subtotal += (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['subtotal']) ? 0 : floatval($otro_gasto['data']['subtotal']) );
    $igv      += (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['igv']) ? 0 : floatval($otro_gasto['data']['igv']) );

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

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND p.ruc = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND t.tipo_comprobante = '$comprobante'"; 
    }

    $sql4 = "SELECT SUM(t.precio_parcial) AS total, SUM(t.subtotal) AS subtotal, SUM(t.igv) AS igv
    FROM transporte AS t, proveedor AS p
    WHERE t.idproveedor = p.idproveedor  AND t.estado = '1' AND t.estado_delete = '1' AND  t.idproyecto = $idproyecto $filtro_proveedor $filtro_comprobante $filtro_fecha;";
    $transporte = ejecutarConsultaSimpleFila($sql4);

    if ($transporte['status'] == false) { return $transporte; }
    
    $total    += (empty($transporte['data'])) ? 0 : ( empty($transporte['data']['total']) ? 0 : floatval($transporte['data']['total']) );
    $subtotal += (empty($transporte['data'])) ? 0 : ( empty($transporte['data']['subtotal']) ? 0 : floatval($transporte['data']['subtotal']) );
    $igv      += (empty($transporte['data'])) ? 0 : ( empty($transporte['data']['igv']) ? 0 : floatval($transporte['data']['igv']) );

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

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND ruc = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND tipo_comprobante = '$comprobante'"; 
    }
    $sql5 = "SELECT SUM(precio_parcial) as total , SUM(subtotal) AS subtotal, SUM(igv) AS igv
    FROM hospedaje WHERE estado = '1' AND estado_delete = '1' AND idproyecto = $idproyecto $filtro_proveedor $filtro_comprobante $filtro_fecha
    ORDER BY fecha_comprobante DESC;";
    $hospedaje = ejecutarConsultaSimpleFila($sql5);

    if ($hospedaje['status'] == false) { return $hospedaje; }
    
    $total    += (empty($hospedaje['data'])) ? 0 : ( empty($hospedaje['data']['total']) ? 0 : floatval($hospedaje['data']['total']) );
    $subtotal += (empty($hospedaje['data'])) ? 0 : ( empty($hospedaje['data']['subtotal']) ? 0 : floatval($hospedaje['data']['subtotal']) );
    $igv      += (empty($hospedaje['data'])) ? 0 : ( empty($hospedaje['data']['igv']) ? 0 : floatval($hospedaje['data']['igv']) );

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

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND prov.ruc = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND fp.tipo_comprobante = '$comprobante'"; 
    }
    $sql6 = "SELECT SUM(fp.monto) AS total, SUM(fp.subtotal) AS subtotal, SUM(fp.igv) AS igv
		FROM factura_pension as fp, pension as p, proveedor as prov
		WHERE fp.idpension = p.idpension AND prov.idproveedor = p.idproveedor  AND p.estado = '1' AND p.estado_delete = '1' AND  p.idproyecto = $idproyecto
    AND fp.estado = '1' AND fp.estado_delete = '1' $filtro_proveedor $filtro_comprobante $filtro_fecha ;";
    $factura_pension = ejecutarConsultaSimpleFila($sql6);

    if ($factura_pension['status'] == false) { return $factura_pension; }
    
    $total    += (empty($factura_pension['data'])) ? 0 : ( empty($factura_pension['data']['total']) ? 0 : floatval($factura_pension['data']['total']) );
    $subtotal += (empty($factura_pension['data'])) ? 0 : ( empty($factura_pension['data']['subtotal']) ? 0 : floatval($factura_pension['data']['subtotal']) );
    $igv      += (empty($factura_pension['data'])) ? 0 : ( empty($factura_pension['data']['igv']) ? 0 : floatval($factura_pension['data']['igv']) );

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

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND fb.ruc = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND fb.tipo_comprobante = '$comprobante'"; 
    }
    $sql7 = "SELECT SUM(fb.monto) AS total, SUM(fb.subtotal) AS subtotal, SUM(fb.igv) AS igv
		FROM factura_break as fb, semana_break as sb
		WHERE  fb.idsemana_break = sb.idsemana_break AND fb.estado = '1' AND fb.estado_delete = '1' AND sb.estado = '1' AND  sb.idproyecto = $idproyecto
    AND sb.estado_delete = '1' $filtro_proveedor $filtro_comprobante $filtro_fecha ;";
    $factura_break = ejecutarConsultaSimpleFila($sql7);

    if ($factura_break['status'] == false) { return $factura_break; }
    
    $total    += (empty($factura_break['data'])) ? 0 : ( empty($factura_break['data']['total']) ? 0 : floatval($factura_break['data']['total']) );
    $subtotal += (empty($factura_break['data'])) ? 0 : ( empty($factura_break['data']['subtotal']) ? 0 : floatval($factura_break['data']['subtotal']) );
    $igv      += (empty($factura_break['data'])) ? 0 : ( empty($factura_break['data']['igv']) ? 0 : floatval($factura_break['data']['igv']) );

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

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND ruc = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND tipo_comprobante = '$comprobante'"; 
    }
    $sql8 = "SELECT SUM(costo_parcial) AS total, SUM(subtotal) AS subtotal, SUM(igv) AS igv
		FROM comida_extra
		WHERE  estado = '1' AND estado_delete = '1' AND  idproyecto = $idproyecto $filtro_proveedor $filtro_comprobante $filtro_fecha;";
    $comida_extra = ejecutarConsultaSimpleFila($sql8);

    if ($comida_extra['status'] == false) { return $comida_extra; }
    
    $total    += (empty($comida_extra['data'])) ? 0 : ( empty($comida_extra['data']['total']) ? 0 : floatval($comida_extra['data']['total']) );
    $subtotal += (empty($comida_extra['data'])) ? 0 : ( empty($comida_extra['data']['subtotal']) ? 0 : floatval($comida_extra['data']['subtotal']) );
    $igv      += (empty($comida_extra['data'])) ? 0 : ( empty($comida_extra['data']['igv']) ? 0 : floatval($comida_extra['data']['igv']) );

    // SUMAS TOTALES - OTRO INGRESO --------------------------------------------------------------------------------

    $filtro_proveedor = ""; $filtro_comprobante = ""; $filtro_fecha = "";

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND oi.fecha_i BETWEEN '$fecha_1' AND '$fecha_2'";
    } else {
      if (!empty($fecha_1)) {
        $filtro_fecha = "AND oi.fecha_i = '$fecha_1'";
      }else{
        if (!empty($fecha_2)) {
          $filtro_fecha = "AND oi.fecha_i = '$fecha_2'";
        }     
      }      
    }    

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND oi.ruc = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND oi.tipo_comprobante = '$comprobante'"; 
    }

    $sql9 = "SELECT SUM(oi.subtotal) as subtotal, SUM(oi.igv) as igv, SUM(oi.costo_parcial) as total
    FROM otro_ingreso as oi, proyecto as p
    WHERE oi.idproyecto = p.idproyecto AND oi.estado = '1' AND oi.estado_delete = '1' AND  oi.idproyecto = $idproyecto $filtro_proveedor $filtro_comprobante $filtro_fecha";
    $otra_factura = ejecutarConsultaSimpleFila($sql9);

    if ($otra_factura['status'] == false) { return $otra_factura; } 
    
    $total    += (empty($otra_factura['data'])) ? 0 : ( empty($otra_factura['data']['total']) ? 0 : floatval($otra_factura['data']['total']) );
    $subtotal += (empty($otra_factura['data'])) ? 0 : ( empty($otra_factura['data']['subtotal']) ? 0 : floatval($otra_factura['data']['subtotal']) );
    $igv      += (empty($otra_factura['data'])) ? 0 : ( empty($otra_factura['data']['igv']) ? 0 : floatval($otra_factura['data']['igv']) );

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

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND p.ruc = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND of.tipo_comprobante = '$comprobante'"; 
    }
    $sql9 = "SELECT SUM(of.costo_parcial) AS total, SUM(of.subtotal) AS subtotal, SUM(of.igv) AS igv
    FROM otra_factura AS of, proveedor p
    WHERE of.idproveedor = p.idproveedor AND of.estado = '1' AND of.estado_delete = '1' $filtro_proveedor $filtro_comprobante $filtro_fecha";
    $otra_factura = ejecutarConsultaSimpleFila($sql9);

    if ($otra_factura['status'] == false) { return $otra_factura; } 
    
    $total    += (empty($otra_factura['data'])) ? 0 : ( empty($otra_factura['data']['total']) ? 0 : floatval($otra_factura['data']['total']) );
    $subtotal += (empty($otra_factura['data'])) ? 0 : ( empty($otra_factura['data']['subtotal']) ? 0 : floatval($otra_factura['data']['subtotal']) );
    $igv      += (empty($otra_factura['data'])) ? 0 : ( empty($otra_factura['data']['igv']) ? 0 : floatval($otra_factura['data']['igv']) );


    $data = array( 
      "status"=> true,
      "message"=> 'todo oka',
      "data"=> [
        "total" => $total, 
        "subtotal" => $subtotal, 
        "igv" => $igv,  
      ]      
    );

    return $data ;
  }  

  // SELECT2
  public function select_proveedores()  {

    $data = Array();

    $sql = "SELECT idproveedor, razon_social, ruc FROM proveedor WHERE estado = '1' AND estado_delete = '1';";
    $proveedor = ejecutarConsultaArray($sql);    
    if ($proveedor['status'] == false) { return $proveedor; }

    if ( !empty($proveedor['data']) ) {
      foreach ($proveedor['data'] as $key => $value) {
        $data[] = array(
          "id" =>  $value['idproveedor'],
          "razon_social" =>  $value['razon_social'],
          "ruc" =>  $value['ruc'],
        );
      }      
    }   
    
    $sql2 = "SELECT ruc, razon_social FROM otro_gasto WHERE estado = '1' AND estado_delete = '1' AND ruc != '' AND razon_social != '';";
    $otro_gasto = ejecutarConsultaArray($sql2);
    if ($otro_gasto['status'] == false) { return $otro_gasto; }

    if ( !empty($otro_gasto['data']) ) {
      foreach ($otro_gasto['data'] as $key => $value) {
        $data[] = array(
          "id" =>  '',
          "razon_social" =>  $value['razon_social'],
          "ruc" =>  $value['ruc'],
        );
      }      
    } 

    $sql2 = "SELECT ruc, razon_social  FROM hospedaje WHERE estado = '1' AND estado_delete = '1' AND ruc != '' AND razon_social != '';";
    $hospedaje = ejecutarConsultaArray($sql2);
    if ($hospedaje['status'] == false) { return $hospedaje; }

    if ( !empty($hospedaje['data']) ) {
      foreach ($hospedaje['data'] as $key => $value) {
        $data[] = array(
          "id" =>  '',
          "razon_social" =>  $value['razon_social'],
          "ruc" =>  $value['ruc'],
        );
      }      
    } 

    $sql2 = "SELECT ruc, razon_social  FROM comida_extra WHERE estado = '1' AND estado_delete = '1' AND ruc != '' AND razon_social != '';";
    $comida_extra = ejecutarConsultaArray($sql2);
    if ($comida_extra['status'] == false) { return $comida_extra; }

    if ( !empty($comida_extra['data']) ) {
      foreach ($comida_extra['data'] as $key => $value) {
        $data[] = array(
          "id" =>  '',
          "razon_social" =>  $value['razon_social'],
          "ruc" =>  $value['ruc'],
        );
      }      
    } 
    $retorno = array( 
      "status"=> true,
      "message"=> 'todo oka',
      "data"=>   $data
    );
    return $retorno;
  }
  
}

function validar_url( $host, $ruta, $file )  {

  $armar_ruta = $host . $ruta . $file;  

  if (empty($armar_ruta)) { return false; }

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
