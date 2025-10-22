<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class ResumenGasto
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  public function tabla_principal($idproyecto, $fecha_1, $fecha_2, $id_proveedor, $comprobante, $visto_bueno) {
    $data = Array(); $data_comprobante = Array(); $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = "";

    $scheme_host=  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/admin_sevens/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');
    $host       = $_SERVER['HTTP_HOST'];
    $estado_vb  = (empty($visto_bueno) ? "estado_user_vb IN ('0','1')" : "estado_user_vb =$visto_bueno" );

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
    cpp.total, cpp.subtotal, cpp.igv,  p.razon_social, cpp.glosa, cpp.tipo_gravada, cpp.comprobante,
    cpp.id_user_vb, cpp.nombre_user_vb, cpp.imagen_user_vb, cpp.estado_user_vb,  DATE_FORMAT(cpp.updated_at_vb, '%d/%m/%Y %h:%i %p') as updated_at_vb
		FROM compra_por_proyecto as cpp, proveedor as p 
		WHERE cpp.idproveedor=p.idproveedor AND cpp.estado = '1' AND cpp.estado_delete = '1' AND cpp.$estado_vb AND  cpp.idproyecto = $idproyecto
    $filtro_proveedor $filtro_comprobante $filtro_fecha ORDER BY cpp.fecha_compra DESC;";
    $compra = ejecutarConsultaArray($sql);

    if ($compra['status'] == false) { return $compra; }

    if (!empty($compra['data'])) {
      foreach ($compra['data'] as $key => $value) {
        $id_compra = $value['idcompra_proyecto'];
        $sql3 = "SELECT COUNT(comprobante) as cant FROM factura_compra_insumo WHERE idcompra_proyecto='$id_compra' AND estado='1' AND estado_delete='1'";
        $cant_comprob = ejecutarConsultaSimpleFila($sql3);
        if ($cant_comprob['status'] == false) { return $cant_comprob; }

        $data[] = array(
        	"idproyecto"        => $value['idproyecto'],
          "idtabla"           => $value['idcompra_proyecto'],
          "bd_nombre_tabla"   => 'compra_por_proyecto',
          "bd_nombre_id_tabla"=> 'idcompra_proyecto',
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
          "id_user_vb"        => $value['id_user_vb'],
          "nombre_user_vb"    => $value['nombre_user_vb'],
          "imagen_user_vb"    => $value['imagen_user_vb'],
          "estado_user_vb"    => $value['estado_user_vb'],
          "updated_at_vb"    => $value['updated_at_vb'],
          "detalle"           => true,
          "comprobante_multiple" => true,
          'cant_comprobante' => (empty($cant_comprob['data']) ? 0 : (empty($cant_comprob['data']['cant']) ? 0 : floatval($cant_comprob['data']['cant']) ) ),
        );                      
      }
    }
    
    $sql_3 = "SELECT fci.comprobante , fci.idcompra_proyecto
    FROM factura_compra_insumo as fci, compra_por_proyecto as cpp, proveedor as p 
    WHERE fci.idcompra_proyecto = cpp.idcompra_proyecto AND  cpp.idproveedor=p.idproveedor AND fci.estado='1' AND fci.estado_delete='1' 
    AND cpp.estado = '1' AND cpp.estado_delete = '1' AND cpp.$estado_vb AND  cpp.idproyecto = $idproyecto
    $filtro_proveedor $filtro_comprobante $filtro_fecha ORDER BY cpp.fecha_compra DESC;";
    $comprob = ejecutarConsultaArray($sql_3);
    if ($comprob['status'] == false) { return $comprob; }

    foreach ($comprob['data'] as $key => $valor) {
      if (!empty($valor['comprobante'])) {            
        if ( validar_url( $scheme_host, 'dist/docs/compra_insumo/comprobante_compra/', $valor['comprobante']) == true) {
          $data_comprobante[] = array(
            "idcompra"       => $valor['idcompra_proyecto'],
            "vall"       =>validar_url( $scheme_host, 'dist/docs/compra_insumo/comprobante_compra/', $valor['comprobante']),
            "comprobante"    => $valor['comprobante'],
            "carpeta"        => 'compra_insumo',
            "subcarpeta"     => 'comprobante_compra',
            "host"           => $host,
            "ruta_file"      => $scheme_host.'dist/docs/compra_insumo/comprobante_compra/'.$valor['comprobante'],
          );
        }
      }                  
    }

    // FACTURAS - COMPRAS ACTIVO FIJO ═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    // $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = "";

    // if ( !empty($fecha_1) && !empty($fecha_2) ) {
    //   $filtro_fecha = "AND cafg.fecha_compra BETWEEN '$fecha_1' AND '$fecha_2'";
    // } else {
    //   if (!empty($fecha_1)) {
    //     $filtro_fecha = "AND cafg.fecha_compra = '$fecha_1'";
    //   }else{
    //     if (!empty($fecha_2)) {
    //       $filtro_fecha = "AND cafg.fecha_compra = '$fecha_2'";
    //     }     
    //   }      
    // }    

    // if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND p.ruc = '$id_proveedor'"; }

    // if ( empty($comprobante) ) { } else {
    //   $filtro_comprobante = "AND cafg.tipo_comprobante = '$comprobante'"; 
    // } 

    // // var_dump($filtro_proveedor , $filtro_fecha ,$filtro_comprobante); die();

    // $sql = "SELECT cafg.idcompra_af_general , cafg.fecha_compra, cafg.tipo_comprobante,	cafg.serie_comprobante, cafg.descripcion, 
    // cafg.total, cafg.subtotal, cafg.igv,  p.razon_social, cafg.glosa, cafg.tipo_gravada, cafg.comprobante
		// FROM compra_af_general as cafg, proveedor as p 
		// WHERE cafg.idproveedor=p.idproveedor AND cafg.estado = '1' AND cafg.estado_delete = '1' 
    // $filtro_proveedor $filtro_comprobante $filtro_fecha ORDER BY cafg.fecha_compra DESC;";
    // $compra = ejecutarConsultaArray($sql);

    // if ($compra['status'] == false) { return $compra; }

    // if (!empty($compra['data'])) {
    //   foreach ($compra['data'] as $key => $value) {
    //     $data[] = array(
    //     	"idproyecto"        => '',
    //       "idtabla"           => $value['idcompra_af_general'],
    //       "bd_nombre_tabla"   => 'compra_af_general',
    //       "bd_nombre_id_tabla"=> 'idcompra_af_general',
    //       "fecha"             => $value['fecha_compra'],
    //       "tipo_comprobante"  => (empty($value['tipo_comprobante']) ? '' : $value['tipo_comprobante'] ),
    //       "serie_comprobante" => $value['serie_comprobante'],
    //       "proveedor"         => $value['razon_social'],
    //       "total"             => $value['total'],          
    //       "subtotal"          => $value['subtotal'],
    //       "igv"               => $value['igv'],
    //       "glosa"             => $value['glosa'],
    //       "tipo_gravada"      => $value['tipo_gravada'],
    //       "comprobante"       => $value['comprobante'],
    //       "carpeta"           => 'compra_activo_fijo',
    //       "subcarpeta"        => 'comprobante_compra',
    //       "ruta"              => 'dist/docs/compra_activo_fijo/comprobante_compra/',
    //       "modulo"            => 'COMPRAS ACTIVO FIJO',
    //       "detalle"            => true,
    //       "comprobante_multiple" => false,
    //      "cant_comprobante" => 0,
    //     );

    //     if (!empty($value['comprobante'])) {
    //       if ( validar_url( $scheme_host, 'dist/docs/compra_activo_fijo/comprobante_compra/', $value['comprobante']) ) {
    //         $data_comprobante[] = array(
    //           "comprobante"    => $value['comprobante'],
    //           "carpeta"        => 'compra_activo_fijo',
    //           "subcarpeta"     => 'comprobante_compra',
    //           "host"           => $host,
    //           "ruta_file"      => $scheme_host.'dist/docs/compra_activo_fijo/comprobante_compra/'.$value['comprobante'],
    //         );
    //       }          
    //     }        
    //   }
    // }

    // FACTURAS - SERVICIO MAQUINA ════════════════════════════════════════════════════════════════════════════════════════════════════════
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

    $sql2 = "SELECT f.idfactura, f.idproyecto, f.codigo, f.tipo_comprobante, f.fecha_emision, f.monto, f.subtotal, f.igv,
    f.nota, mq.nombre, mq.tipo, prov.razon_social, f.descripcion, f.imagen, f.id_user_vb, f.nombre_user_vb, f.imagen_user_vb, f.estado_user_vb, DATE_FORMAT(f.updated_at_vb, '%d/%m/%Y %h:%i %p') as updated_at_vb
    FROM factura as f, proyecto as p, maquinaria as mq, proveedor as prov
    WHERE f.idmaquinaria=mq.idmaquinaria AND mq.idproveedor=prov.idproveedor AND f.idproyecto=p.idproyecto 
    AND f.estado = '1' AND f.estado_delete = '1' AND mq.tipo = '1' AND f.$estado_vb AND f.idproyecto = $idproyecto $filtro_proveedor $filtro_comprobante $filtro_fecha
    ORDER BY f.fecha_emision DESC;";
    $maquinaria_equipo =  ejecutarConsultaArray($sql2);

    if ($maquinaria_equipo['status'] == false) { return $maquinaria_equipo; }

    if (!empty($maquinaria_equipo['data'])) {
      foreach ($maquinaria_equipo['data'] as $key => $value) {
        $data[] = array(
        	"idproyecto"        => $value['idproyecto'],
          "idtabla"           => $value['idfactura'],
          "bd_nombre_tabla"   => 'factura',
          "bd_nombre_id_tabla"=> 'idfactura',
          "fecha"             => $value['fecha_emision'],
          "tipo_comprobante"  => $value['tipo_comprobante'],
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
          "modulo"              => 'SERVICIO MAQUINA',
          "id_user_vb"        => $value['id_user_vb'],
          "nombre_user_vb"    => $value['nombre_user_vb'],
          "imagen_user_vb"    => $value['imagen_user_vb'],
          "estado_user_vb"    => $value['estado_user_vb'],
          "updated_at_vb"    => $value['updated_at_vb'],
          "detalle"            => false,
          "comprobante_multiple" => false,
          "cant_comprobante" => 0,
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

    // FACTURAS - SERVICIO EQUIPO ════════════════════════════════════════════════════════════════════════════════════════════════════════

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

    $sql2 = "SELECT f.idfactura, f.idproyecto, f.codigo,  f.tipo_comprobante, f.fecha_emision, f.monto, f.subtotal, f.igv,
    f.nota, mq.nombre, mq.tipo, prov.razon_social, f.descripcion, f.imagen, f.id_user_vb, f.nombre_user_vb, f.imagen_user_vb, f.estado_user_vb, DATE_FORMAT(f.updated_at_vb, '%d/%m/%Y %h:%i %p') as updated_at_vb
    FROM factura as f, proyecto as p, maquinaria as mq, proveedor as prov
    WHERE f.idmaquinaria=mq.idmaquinaria AND mq.idproveedor=prov.idproveedor AND f.idproyecto=p.idproyecto 
    AND f.estado = '1' AND f.estado_delete = '1' AND f.$estado_vb AND mq.tipo = '2' AND  f.idproyecto = $idproyecto $filtro_proveedor $filtro_comprobante $filtro_fecha
    ORDER BY f.fecha_emision DESC;";
    $maquinaria_equipo =  ejecutarConsultaArray($sql2);

    if ($maquinaria_equipo['status'] == false) { return $maquinaria_equipo; }

    if (!empty($maquinaria_equipo['data'])) {
      foreach ($maquinaria_equipo['data'] as $key => $value) {
        $data[] = array(
        	"idproyecto"        => $value['idproyecto'],
          "idtabla"           => $value['idfactura'],
          "bd_nombre_tabla"   => 'factura',
          "bd_nombre_id_tabla"=> 'idfactura',
          "fecha"             => $value['fecha_emision'],
          "tipo_comprobante"  => $value['tipo_comprobante'],
          "serie_comprobante" => $value['codigo'],
          "proveedor"         => $value['razon_social'],
          "total"             => $value['monto'],          
          "subtotal"          => $value['subtotal'],
          "igv"               => $value['igv'],
          "glosa"             => 'EQUIPO',
          "tipo_gravada"      => 'GRAVADA',
          "comprobante"       => $value['imagen'],
          "carpeta"           => 'servicio_equipo',
          "subcarpeta"        => 'comprobante_servicio',
          "ruta"              => 'dist/docs/servicio_equipo/comprobante_servicio/',
          "modulo"              => 'SERVICIO EQUIPO',
          "id_user_vb"        => $value['id_user_vb'],
          "nombre_user_vb"    => $value['nombre_user_vb'],
          "imagen_user_vb"    => $value['imagen_user_vb'],
          "estado_user_vb"    => $value['estado_user_vb'],
          "updated_at_vb"    => $value['updated_at_vb'],
          "detalle"            => false,
          "comprobante_multiple" => false,
          "cant_comprobante" => 0,
        );
        if (!empty($value['imagen'])) {
          if (validar_url( $scheme_host, 'dist/docs/servicio_equipo/comprobante_servicio/', $value['imagen'])) {
            $data_comprobante[] = array(
              "comprobante"       => $value['imagen'],
              "carpeta"           => 'servicio_equipo',
              "subcarpeta"        => 'comprobante_servicio',
              "host"              => $host,
              "ruta_file"         => $scheme_host.'dist/docs/servicio_equipo/comprobante_servicio/'.$value['imagen'],
            );
          }
          
        }
      }
    }


    // FACTURAS - SUB CONTRATO ═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = "";

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND s.fecha_subcontrato BETWEEN '$fecha_1' AND '$fecha_2'";
    } else if (!empty($fecha_1)) {      
      $filtro_fecha = "AND s.fecha_subcontrato = '$fecha_1'";
    }else if (!empty($fecha_2)) {        
      $filtro_fecha = "AND s.fecha_subcontrato = '$fecha_2'";           
    }    

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND p.ruc = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND s.tipo_comprobante = '$comprobante'"; 
    }
    
    $sql3 = "SELECT s.idsubcontrato, s.idproyecto, s.idproveedor, s.tipo_comprobante, s.numero_comprobante, s.forma_de_pago, 
    s.fecha_subcontrato, s.val_igv, s.subtotal, s.igv, s.costo_parcial, s.descripcion, s.glosa, s.comprobante, p.razon_social, p.tipo_documento, 
    p.ruc, s.id_user_vb, s.nombre_user_vb, s.imagen_user_vb, s.estado_user_vb, DATE_FORMAT(s.updated_at_vb, '%d/%m/%Y %h:%i %p') as updated_at_vb    
    FROM subcontrato AS s, proveedor as p
    WHERE s.idproveedor = p.idproveedor and s.estado = '1' AND s.estado_delete = '1' AND s.$estado_vb AND idproyecto = $idproyecto $filtro_proveedor $filtro_comprobante $filtro_fecha ORDER BY s.fecha_subcontrato DESC;";
    $sub_contrato =  ejecutarConsultaArray($sql3);

    if ($sub_contrato['status'] == false) { return $sub_contrato; }

    if (!empty($sub_contrato['data'])) {
      foreach ($sub_contrato['data'] as $key => $value) {
        $data[] = array(
        	"idproyecto"        => $value['idproyecto'],
          "idtabla"           => $value['idsubcontrato'],
          "bd_nombre_tabla"   => 'subcontrato',
          "bd_nombre_id_tabla"=> 'idsubcontrato',
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
          "id_user_vb"        => $value['id_user_vb'],
          "nombre_user_vb"    => $value['nombre_user_vb'],
          "imagen_user_vb"    => $value['imagen_user_vb'],
          "estado_user_vb"    => $value['estado_user_vb'],
          "updated_at_vb"    => $value['updated_at_vb'],
          "detalle"           => false,
          "comprobante_multiple" => false,
          "cant_comprobante" => 0,
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

    // FACTURAS - MANO DE OBRA ═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = "";

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND mdo.fecha_deposito BETWEEN '$fecha_1' AND '$fecha_2'";
    } else if (!empty($fecha_1)) {      
      $filtro_fecha = "AND mdo.fecha_deposito = '$fecha_1'";
    }else if (!empty($fecha_2)) {        
      $filtro_fecha = "AND mdo.fecha_deposito = '$fecha_2'";           
    }    

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND p.ruc = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND mdo.tipo_comprobante = '$comprobante'"; 
    }
    
    $sql3 = "SELECT mdo.idmano_de_obra, mdo.idproyecto, mdo.idproveedor, mdo.fecha_inicial, mdo.fecha_final, mdo.fecha_deposito, mdo.tipo_comprobante, 
    mdo.numero_comprobante, mdo.monto, mdo.glosa, mdo.tipo_gravada, mdo.descripcion, mdo.id_user_vb, mdo.nombre_user_vb, mdo.imagen_user_vb, mdo.estado_user_vb, DATE_FORMAT(mdo.updated_at_vb, '%d/%m/%Y %h:%i %p') as updated_at_vb,
    p.razon_social, p.tipo_documento, p.ruc
    FROM mano_de_obra AS mdo, proveedor as p
    WHERE mdo.idproveedor = p.idproveedor  and mdo.estado = '1' AND mdo.estado_delete = '1' AND mdo.$estado_vb AND idproyecto = $idproyecto $filtro_proveedor $filtro_comprobante $filtro_fecha ORDER BY mdo.fecha_deposito DESC;";
    $mano_de_obra =  ejecutarConsultaArray($sql3);

    if ($mano_de_obra['status'] == false) { return $mano_de_obra; }

    if (!empty($mano_de_obra['data'])) {
      foreach ($mano_de_obra['data'] as $key => $value) {
        $data[] = array(
        	"idproyecto"        => $value['idproyecto'],
          "idtabla"           => $value['idmano_de_obra'],
          "bd_nombre_tabla"   => 'mano_de_obra',
          "bd_nombre_id_tabla"=> 'idmano_de_obra',
          "fecha"             => $value['fecha_deposito'],
          "tipo_comprobante"  => (empty($value['tipo_comprobante']) ? '' :$value['tipo_comprobante'] ) ,
          "serie_comprobante" => $value['numero_comprobante'],
          "proveedor"         => $value['razon_social'],
          "total"             => $value['monto'],
          "igv"               => 0,
          "subtotal"          => $value['monto'],
          "glosa"             => $value['glosa'],
          "tipo_gravada"      => $value['tipo_gravada'],
          "comprobante"       => '',
          "carpeta"           => 'mano_de_obra',
          "subcarpeta"        => 'comprobante',
          "ruta"              => 'dist/docs/mano_de_obra/comprobante/',
          "modulo"            => 'MANO DE OBRA',
          "id_user_vb"        => $value['id_user_vb'],
          "nombre_user_vb"    => $value['nombre_user_vb'],
          "imagen_user_vb"    => $value['imagen_user_vb'],
          "estado_user_vb"    => $value['estado_user_vb'],
          "updated_at_vb"    => $value['updated_at_vb'],
          "detalle"           => false,
          "comprobante_multiple" => false,
          "cant_comprobante" => 0,
        );
        if (!empty($value['comprobante'])) {
          if ( validar_url( $scheme_host, 'dist/docs/mano_de_obra/comprobante/', $value['comprobante']) ) {
            $data_comprobante[] = array(
              "comprobante"       => $value['comprobante'],
              "carpeta"           => 'mano_de_obra',
              "subcarpeta"        => 'comprobante',
              "host"              => $host,
              "ruta_file"         => $scheme_host.'dist/docs/mano_de_obra/comprobante/'.$value['comprobante'],
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

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND prov.ruc = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND ps.tipo_comprobante = '$comprobante'"; 
    }

    $sql3 = "SELECT ps.idplanilla_seguro, ps.idproyecto, ps.tipo_comprobante, ps.numero_comprobante, ps.forma_de_pago, 
    ps.fecha_p_s, ps.subtotal, ps.igv, ps.costo_parcial, ps.descripcion, ps.val_igv, ps.tipo_gravada, ps.comprobante, ps.glosa,
    ps.id_user_vb, ps.nombre_user_vb, ps.imagen_user_vb, ps.estado_user_vb, DATE_FORMAT(ps.updated_at_vb, '%d/%m/%Y %h:%i %p') as updated_at_vb,
    prov.razon_social, prov.tipo_documento, prov.ruc
    FROM planilla_seguro as ps, proyecto as p, proveedor as prov 
    WHERE ps.idproyecto = p.idproyecto and ps.idproveedor = prov.idproveedor and ps.estado ='1' and ps.estado_delete = '1' AND ps.$estado_vb
    AND ps.idproyecto = $idproyecto $filtro_proveedor $filtro_comprobante $filtro_fecha ORDER BY ps.fecha_p_s DESC;";
    $planilla_seguro =  ejecutarConsultaArray($sql3);

    if ($planilla_seguro['status'] == false) { return $planilla_seguro; }

    if (!empty($planilla_seguro['data'])) {
      foreach ($planilla_seguro['data'] as $key => $value) {
        $data[] = array(
        	"idproyecto"        => $value['idproyecto'],
          "idtabla"           => $value['idplanilla_seguro'],
          "bd_nombre_tabla"   => 'planilla_seguro',
          "bd_nombre_id_tabla"=> 'idplanilla_seguro',
          "fecha"             => $value['fecha_p_s'],
          "tipo_comprobante"  => (empty($value['tipo_comprobante']) ? '' :$value['tipo_comprobante'] ),
          "serie_comprobante" => $value['numero_comprobante'],
          "proveedor"         => $value['razon_social'],
          "total"             => $value['costo_parcial'],
          "igv"               => $value['igv'],
          "subtotal"          => $value['subtotal'],
          "glosa"             => $value['glosa'],
          "tipo_gravada"      => $value['tipo_gravada'],
          "comprobante"       => $value['comprobante'],
          "carpeta"           => 'planilla_seguro',
          "subcarpeta"        => 'comprobante',
          "ruta"              => 'dist/docs/planilla_seguro/comprobante/',
          "modulo"            => 'PLANILLA SEGURO',
          "id_user_vb"        => $value['id_user_vb'],
          "nombre_user_vb"    => $value['nombre_user_vb'],
          "imagen_user_vb"    => $value['imagen_user_vb'],
          "estado_user_vb"    => $value['estado_user_vb'],
          "updated_at_vb"    => $value['updated_at_vb'],
          "detalle"           => false,
          "comprobante_multiple" => false,
          "cant_comprobante" => 0,
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
    costo_parcial, subtotal, igv, glosa, comprobante, tipo_gravada, id_user_vb, nombre_user_vb, imagen_user_vb, estado_user_vb, DATE_FORMAT(updated_at_vb, '%d/%m/%Y %h:%i %p') as updated_at_vb
    FROM otro_gasto 
    WHERE  estado = '1' AND estado_delete = '1' AND $estado_vb AND idproyecto = $idproyecto $filtro_proveedor $filtro_comprobante $filtro_fecha ORDER BY fecha_g DESC;";
    $otro_gasto =  ejecutarConsultaArray($sql3);

    if ($otro_gasto['status'] == false) { return $otro_gasto; }

    if (!empty($otro_gasto['data'])) {
      foreach ($otro_gasto['data'] as $key => $value) {
        $data[] = array(
        	"idproyecto"        => $value['idproyecto'],
          "idtabla"           => $value['idotro_gasto'],
          "bd_nombre_tabla"   => 'otro_gasto',
          "bd_nombre_id_tabla"=> 'idotro_gasto',
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
          "id_user_vb"        => $value['id_user_vb'],
          "nombre_user_vb"    => $value['nombre_user_vb'],
          "imagen_user_vb"    => $value['imagen_user_vb'],
          "estado_user_vb"    => $value['estado_user_vb'],
          "updated_at_vb"    => $value['updated_at_vb'],
          "detalle"           => false,
          "comprobante_multiple" => false,
          "cant_comprobante" => 0,
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

    $sql4 = "SELECT t.idtransporte, t.idproyecto, p.razon_social, t.tipo_comprobante, t.numero_comprobante, t.fecha_viaje, t.precio_parcial, 
    t.subtotal, t.igv,  t.comprobante , t.glosa , t.tipo_gravada, t.id_user_vb, t.nombre_user_vb, t.imagen_user_vb, t.estado_user_vb, DATE_FORMAT(t.updated_at_vb, '%d/%m/%Y %h:%i %p') as updated_at_vb
    FROM transporte AS t, proveedor AS p
    WHERE t.idproveedor = p.idproveedor  AND t.estado = '1' AND t.estado_delete = '1' AND t.$estado_vb AND  t.idproyecto = $idproyecto $filtro_proveedor $filtro_comprobante $filtro_fecha
    ORDER BY t.fecha_viaje DESC;";
    $transporte =  ejecutarConsultaArray($sql4);

    if ($transporte['status'] == false) { return $transporte; }

    if (!empty($transporte['data'])) {
      foreach ($transporte['data'] as $key => $value) {
        $data[] = array(
        	"idproyecto"        => $value['idproyecto'],
          "idtabla"           => $value['idtransporte'],
          "bd_nombre_tabla"   => 'transporte',
          "bd_nombre_id_tabla"=> 'idtransporte',
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
          "id_user_vb"        => $value['id_user_vb'],
          "nombre_user_vb"    => $value['nombre_user_vb'],
          "imagen_user_vb"    => $value['imagen_user_vb'],
          "estado_user_vb"    => $value['estado_user_vb'],
          "updated_at_vb"    => $value['updated_at_vb'],
          "detalle"           => false,
          "comprobante_multiple" => false,
          "cant_comprobante" => 0,
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
    precio_parcial, glosa, comprobante, tipo_gravada, id_user_vb, nombre_user_vb, imagen_user_vb, estado_user_vb,  DATE_FORMAT(updated_at_vb, '%d/%m/%Y %h:%i %p') as updated_at_vb
    FROM hospedaje 
    WHERE estado = '1' AND estado_delete = '1' AND $estado_vb AND  idproyecto = $idproyecto $filtro_proveedor $filtro_comprobante $filtro_fecha
    ORDER BY fecha_comprobante DESC;";
    $hospedaje =  ejecutarConsultaArray($sql5);

    if ($hospedaje['status'] == false) { return $hospedaje; }

    if (!empty($hospedaje['data'])) {
      foreach ($hospedaje['data'] as $key => $value) {
        $data[] = array(
        	"idproyecto"        => $value['idproyecto'],
          "idtabla"           => $value['idhospedaje'],
          "bd_nombre_tabla"   => 'hospedaje',
          "bd_nombre_id_tabla"=> 'idhospedaje',
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
          "id_user_vb"        => $value['id_user_vb'],
          "nombre_user_vb"    => $value['nombre_user_vb'],
          "imagen_user_vb"    => $value['imagen_user_vb'],
          "estado_user_vb"    => $value['estado_user_vb'],
          "updated_at_vb"    => $value['updated_at_vb'],
          "detalle"           => false,
          "comprobante_multiple" => false,
          "cant_comprobante" => 0,
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
      $filtro_fecha = "AND dp.fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
    } else {
      if (!empty($fecha_1)) {
        $filtro_fecha = "AND dp.fecha_emision = '$fecha_1'";
      }else{
        if (!empty($fecha_2)) {
          $filtro_fecha = "AND dp.fecha_emision = '$fecha_2'";
        }     
      }      
    }    

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND prov.ruc = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND dp.tipo_comprobante = '$comprobante'"; 
    }

    $sql6 = "SELECT dp.iddetalle_pension, dp.idpension, dp.fecha_inicial, dp.fecha_final, dp.cantidad_persona, dp.subtotal, dp.igv, 
    dp.val_igv, dp.precio_parcial, dp.forma_pago, dp.tipo_comprobante, dp.fecha_emision, dp.tipo_gravada, dp.glosa, dp.numero_comprobante, dp.descripcion, 
    dp.comprobante, dp.id_user_vb, dp.nombre_user_vb, dp.imagen_user_vb, dp.estado_user_vb, DATE_FORMAT(dp.updated_at_vb, '%d/%m/%Y %h:%i %p') as updated_at_vb,
    prov.razon_social, p.idproyecto
    FROM detalle_pension as dp, pension as p, proveedor as prov
    WHERE dp.idpension = p.idpension AND p.idproveedor = prov.idproveedor AND dp.estado = '1' AND dp.estado_delete = '1' AND p.estado = '1' AND p.estado_delete = '1'
    AND dp.$estado_vb AND  p.idproyecto = $idproyecto $filtro_proveedor $filtro_comprobante $filtro_fecha
    ORDER BY dp.fecha_emision DESC;";
    $factura_pension =  ejecutarConsultaArray($sql6);

    if ($factura_pension['status'] == false) { return $factura_pension; }

    if (!empty($factura_pension['data'])) {
      foreach ($factura_pension['data'] as $key => $value) {
        $data[] = array(
        	"idproyecto"        => $value['idproyecto'],
          "idtabla"           => $value['iddetalle_pension'],
          "bd_nombre_tabla"   => 'detalle_pension',
          "bd_nombre_id_tabla"=> 'iddetalle_pension',
          "fecha"             => $value['fecha_emision'],
          "tipo_comprobante"  => (empty($value['tipo_comprobante']) ? '' : $value['tipo_comprobante'] ),
          "serie_comprobante" => $value['numero_comprobante'],
          "proveedor"         => $value['razon_social'],
          "total"             => $value['precio_parcial'],          
          "subtotal"          => $value['subtotal'],
          "igv"               => $value['igv'],
          "glosa"             => $value['glosa'],
          "tipo_gravada"      => $value['tipo_gravada'],
          "comprobante"       => $value['comprobante'],
          "carpeta"           => 'pension',
          "subcarpeta"        => 'comprobante',
          "ruta"              => 'dist/docs/pension/comprobante/',
          "modulo"            => 'PENSION',
          "id_user_vb"        => $value['id_user_vb'],
          "nombre_user_vb"    => $value['nombre_user_vb'],
          "imagen_user_vb"    => $value['imagen_user_vb'],
          "estado_user_vb"    => $value['estado_user_vb'],
          "updated_at_vb"    => $value['updated_at_vb'],
          "detalle"           => false,
          "comprobante_multiple" => false,
          "cant_comprobante" => 0,
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

    // FACTURAS - BREACK ═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
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
    fb.monto, fb.subtotal, fb.igv, fb.glosa, fb.comprobante, fb.tipo_gravada, fb.id_user_vb, fb.nombre_user_vb, fb.imagen_user_vb, fb.estado_user_vb, DATE_FORMAT(fb.updated_at_vb, '%d/%m/%Y %h:%i %p') as updated_at_vb
		FROM factura_break as fb, semana_break as sb
		WHERE  fb.idsemana_break = sb.idsemana_break  
    AND fb.estado = '1' AND fb.estado_delete = '1' AND sb.estado = '1' AND sb.estado_delete = '1' AND fb.$estado_vb AND  sb.idproyecto = $idproyecto
     $filtro_proveedor $filtro_comprobante $filtro_fecha
    ORDER BY fb.fecha_emision DESC;";
    $factura_break =  ejecutarConsultaArray($sql7);

    if ($factura_break['status'] == false) { return $factura_break; }

    if (!empty($factura_break['data'])) {
      foreach ($factura_break['data'] as $key => $value) {
        $data[] = array(
        	"idproyecto"        => $value['idproyecto'],
          "idtabla"           => $value['idfactura_break'],
          "bd_nombre_tabla"   => 'factura_break',
          "bd_nombre_id_tabla"=> 'idfactura_break',
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
          "id_user_vb"        => $value['id_user_vb'],
          "nombre_user_vb"    => $value['nombre_user_vb'],
          "imagen_user_vb"    => $value['imagen_user_vb'],
          "estado_user_vb"    => $value['estado_user_vb'],
          "updated_at_vb"    => $value['updated_at_vb'],
          "detalle"            => false,
          "comprobante_multiple" => false,
          "cant_comprobante" => 0,
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
    costo_parcial, subtotal, igv, glosa, comprobante, tipo_gravada, id_user_vb, nombre_user_vb, imagen_user_vb, estado_user_vb, DATE_FORMAT(updated_at_vb, '%d/%m/%Y %h:%i %p') as updated_at_vb
		FROM comida_extra
		WHERE  estado = '1' AND estado_delete = '1' AND $estado_vb AND idproyecto = '$idproyecto' $filtro_proveedor $filtro_comprobante $filtro_fecha
    ORDER BY fecha_comida DESC;";
    $comida_extra =  ejecutarConsultaArray($sql8);

    if ($comida_extra['status'] == false) { return $comida_extra; }

    if (!empty($comida_extra['data'])) {
      foreach ($comida_extra['data'] as $key => $value) {
        $data[] = array(
        	"idproyecto"        => $value['idproyecto'],
          "idtabla"           => $value['idcomida_extra'],
          "bd_nombre_tabla"   => 'comida_extra',
          "bd_nombre_id_tabla"=> 'idcomida_extra',
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
          "id_user_vb"        => $value['id_user_vb'],
          "nombre_user_vb"    => $value['nombre_user_vb'],
          "imagen_user_vb"    => $value['imagen_user_vb'],
          "estado_user_vb"    => $value['estado_user_vb'],
          "updated_at_vb"    => $value['updated_at_vb'],
          "detalle"           => false,
          "comprobante_multiple" => false,
          "cant_comprobante" => 0,
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
    // $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = "";

    // if ( !empty($fecha_1) && !empty($fecha_2) ) {
    //   $filtro_fecha = "AND oi.fecha_i BETWEEN '$fecha_1' AND '$fecha_2'";
    // } else {
    //   if (!empty($fecha_1)) {
    //     $filtro_fecha = "AND oi.fecha_i = '$fecha_1'";
    //   }else{
    //     if (!empty($fecha_2)) {
    //       $filtro_fecha = "AND oi.fecha_i = '$fecha_2'";
    //     }     
    //   }      
    // }    

    // if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND oi.ruc = '$id_proveedor'"; }

    // if ( empty($comprobante) ) { } else {
    //   $filtro_comprobante = "AND oi.tipo_comprobante = '$comprobante'"; 
    // }

    // $sql9 = "SELECT oi.idotro_ingreso, oi.idproyecto, oi.idproveedor, oi.ruc, oi.razon_social, oi.direccion, oi.tipo_comprobante, 
    // oi.numero_comprobante, oi.forma_de_pago, oi.fecha_i, oi.subtotal, oi.igv, oi.costo_parcial, oi.descripcion, oi.glosa, 
    // oi.comprobante, oi.val_igv, oi.tipo_gravada, oi.id_user_vb, oi.nombre_user_vb, oi.imagen_user_vb, oi.estado_user_vb
    // FROM otro_ingreso as oi, proyecto as p
    // WHERE oi.idproyecto = p.idproyecto AND oi.estado = '1' AND oi.estado_delete = '1' 
    // AND oi.idproyecto = $idproyecto $filtro_proveedor $filtro_comprobante $filtro_fecha
    // ORDER BY oi.fecha_i DESC;";
    // $otra_factura =  ejecutarConsultaArray($sql9);

    // if ($otra_factura['status'] == false) { return $otra_factura; }

    // if (!empty($otra_factura['data'])) {
    //   foreach ($otra_factura['data'] as $key => $value) {
    //     $data[] = array(
    //     	"idproyecto"        => $value['idproyecto'],
    //       "idtabla"           => $value['idotro_ingreso'],
    //       "bd_nombre_tabla"   => 'otro_ingreso',
    //       "bd_nombre_id_tabla"=> 'idotro_ingreso',
    //       "fecha"             => $value['fecha_i'],
    //       "tipo_comprobante"  => (empty($value['tipo_comprobante']) ? '' : $value['tipo_comprobante']) ,
    //       "serie_comprobante" => $value['numero_comprobante'],
    //       "proveedor"         => $value['razon_social'],
    //       "total"             => $value['costo_parcial'],
    //       "igv"               => $value['igv'],
    //       "subtotal"          => $value['subtotal'],
    //       "glosa"             => $value['glosa'],
    //       "tipo_gravada"      => $value['tipo_gravada'],
    //       "comprobante"       => $value['comprobante'],
    //       "carpeta"           => 'otro_ingreso',
    //       "subcarpeta"        => 'comprobante',
    //       "ruta"              => 'dist/docs/otro_ingreso/comprobante/',
    //       "modulo"            => 'OTRO INGRESO',
    //       "id_user_vb"        => $value['id_user_vb'],
    //       "nombre_user_vb"    => $value['nombre_user_vb'],
    //       "imagen_user_vb"    => $value['imagen_user_vb'],
    //       "estado_user_vb"    => $value['estado_user_vb'],
    //       "detalle"           => false,
    //        "comprobante_multiple" => false,
    //        "cant_comprobante" => 0,
    //     );
    //     if (!empty($value['comprobante'])) {
    //       if ( validar_url( $scheme_host, 'dist/docs/otro_ingreso/comprobante/', $value['comprobante']) ) {
    //         $data_comprobante[] = array(
    //           "comprobante"       => $value['comprobante'],
    //           "carpeta"           => 'otro_ingreso',
    //           "subcarpeta"        => 'comprobante',
    //           "host"              => $host,
    //           "ruta_file"         => $scheme_host.'dist/docs/otro_ingreso/comprobante/'.$value['comprobante'],
    //         );
    //       }          
    //     }
    //   }
    // }

    // FACTURAS - OTRA FACTURA ═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    // $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = "";

    // if ( !empty($fecha_1) && !empty($fecha_2) ) {
    //   $filtro_fecha = "AND of.fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
    // } else {
    //   if (!empty($fecha_1)) {
    //     $filtro_fecha = "AND of.fecha_emision = '$fecha_1'";
    //   }else{
    //     if (!empty($fecha_2)) {
    //       $filtro_fecha = "AND of.fecha_emision = '$fecha_2'";
    //     }     
    //   }      
    // }    

    // if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND p.ruc = '$id_proveedor'"; }

    // if ( empty($comprobante) ) { } else {
    //   $filtro_comprobante = "AND of.tipo_comprobante = '$comprobante'"; 
    // }

    // $sql9 = "SELECT of.idotra_factura, of.fecha_emision, of.tipo_comprobante, of.numero_comprobante, p.razon_social, of.costo_parcial, 
    // of.subtotal, of.igv, of.glosa, of.comprobante, of.tipo_gravada, of.id_user_vb, of.nombre_user_vb, of.imagen_user_vb, of.estado_user_vb
    // FROM otra_factura AS of, proveedor p
    // WHERE of.idproveedor = p.idproveedor AND of.estado = '1' AND of.estado_delete = '1' $filtro_proveedor $filtro_comprobante $filtro_fecha
    // ORDER BY of.fecha_emision DESC;";
    // $otra_factura =  ejecutarConsultaArray($sql9);

    // if ($otra_factura['status'] == false) { return $otra_factura; }

    // if (!empty($otra_factura['data'])) {
    //   foreach ($otra_factura['data'] as $key => $value) {
    //     $data[] = array(
    //     	"idproyecto"        => '',
    //       "idtabla"           => $value['idotra_factura'],
    //       "bd_nombre_tabla"   => 'otra_factura',
    //       "bd_nombre_id_tabla"=> 'idotra_factura',
    //       "fecha"             => $value['fecha_emision'],
    //       "tipo_comprobante"  => (empty($value['tipo_comprobante']) ? '' : $value['tipo_comprobante']) ,
    //       "serie_comprobante" => $value['numero_comprobante'],
    //       "proveedor"         => $value['razon_social'],
    //       "total"             => $value['costo_parcial'],
    //       "igv"               => $value['igv'],
    //       "subtotal"          => $value['subtotal'],
    //       "glosa"             => $value['glosa'],
    //       "tipo_gravada"      => $value['tipo_gravada'],
    //       "comprobante"       => $value['comprobante'],
    //       "carpeta"           => 'otra_factura',
    //       "subcarpeta"        => 'comprobante',
    //       "ruta"              => 'dist/docs/otra_factura/comprobante/',
    //       "modulo"            => 'OTRA FACTURA',
    //       "id_user_vb"        => $value['id_user_vb'],
    //       "nombre_user_vb"    => $value['nombre_user_vb'],
    //       "imagen_user_vb"    => $value['imagen_user_vb'],
    //       "estado_user_vb"    => $value['estado_user_vb'],
    //       "detalle"           => false,
    //        "comprobante_multiple" => false,
    //       "cant_comprobante" => 0,
    //     );
    //     if (!empty($value['comprobante'])) {
    //       if ( validar_url( $scheme_host, 'dist/docs/otra_factura/comprobante/', $value['comprobante']) ) {
    //         $data_comprobante[] = array(
    //           "comprobante"       => $value['comprobante'],
    //           "carpeta"           => 'otra_factura',
    //           "subcarpeta"        => 'comprobante',
    //           "host"              => $host,
    //           "ruta_file"         => $scheme_host.'dist/docs/otra_factura/comprobante/'.$value['comprobante'],
    //         );
    //       }          
    //     }
    //   }
    // }

    // FACTURAS - PAGO ADMINSTRADOR ═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = "";

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND pxma.fecha_pago BETWEEN '$fecha_1' AND '$fecha_2'";
    } else {
      if (!empty($fecha_1)) {
        $filtro_fecha = "AND pxma.fecha_pago = '$fecha_1'";
      }else{
        if (!empty($fecha_2)) {
          $filtro_fecha = "AND pxma.fecha_pago = '$fecha_2'";
        }     
      }      
    }    

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND t.numero_documento = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND fmpa.tipo_comprobante = '$comprobante'"; 
    }

    $sql9 = "SELECT tpp.idproyecto, pxma.idpagos_x_mes_administrador, pxma.idfechas_mes_pagos_administrador, pxma.cuenta_deposito, pxma.forma_de_pago, pxma.monto, 
    pxma.fecha_pago, pxma.baucher, pxma.descripcion, fmpa.recibos_x_honorarios, fmpa.tipo_comprobante, fmpa.numero_comprobante, t.nombres as trabajador, t.imagen_perfil, t.tipo_documento, t.numero_documento,
    pxma.id_user_vb, pxma.nombre_user_vb, pxma.imagen_user_vb, pxma.estado_user_vb, DATE_FORMAT(pxma.updated_at_vb, '%d/%m/%Y %h:%i %p') as updated_at_vb
    FROM pagos_x_mes_administrador as pxma, fechas_mes_pagos_administrador as fmpa, trabajador_por_proyecto as tpp, trabajador t
    WHERE pxma.idfechas_mes_pagos_administrador = fmpa.idfechas_mes_pagos_administrador AND fmpa.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto
    AND tpp.idtrabajador = t.idtrabajador AND pxma.estado = '1' AND pxma.estado_delete = '1' AND tpp.idproyecto = '$idproyecto' AND pxma.$estado_vb
    $filtro_proveedor $filtro_comprobante $filtro_fecha
    ORDER BY pxma.fecha_pago DESC;";
    $otra_factura =  ejecutarConsultaArray($sql9);

    if ($otra_factura['status'] == false) { return $otra_factura; }

    if (!empty($otra_factura['data'])) {
      foreach ($otra_factura['data'] as $key => $value) {
        $data[] = array(
        	"idproyecto"        => $value['idproyecto'],
          "idtabla"           => $value['idpagos_x_mes_administrador'],
          "bd_nombre_tabla"   => 'pagos_x_mes_administrador',
          "bd_nombre_id_tabla"=> 'idpagos_x_mes_administrador',
          "fecha"             => $value['fecha_pago'],
          "tipo_comprobante"  => (empty($value['tipo_comprobante']) ? '' : $value['tipo_comprobante']) ,
          "serie_comprobante" => $value['numero_comprobante'],
          "proveedor"         => $value['trabajador'],
          "total"             => $value['monto'],
          "igv"               => 0,
          "subtotal"          => $value['monto'],
          "glosa"             => '',
          "tipo_gravada"      => 'NO GRAVADA',
          "comprobante"       => $value['recibos_x_honorarios'],
          "carpeta"           => 'pago_administrador',
          "subcarpeta"        => 'recibos_x_honorarios',
          "ruta"              => 'dist/docs/pago_administrador/recibos_x_honorarios/',
          "modulo"            => 'PAGO ADMINISTRADOR',
          "id_user_vb"        => $value['id_user_vb'],
          "nombre_user_vb"    => $value['nombre_user_vb'],
          "imagen_user_vb"    => $value['imagen_user_vb'],
          "estado_user_vb"    => $value['estado_user_vb'],
          "updated_at_vb"    => $value['updated_at_vb'],
          "detalle"           => false,
          "comprobante_multiple" => false,
          "cant_comprobante" => 0,
        );
        if (!empty($value['recibos_x_honorarios'])) {
          if ( validar_url( $scheme_host, 'dist/docs/pago_administrador/recibos_x_honorarios/', $value['recibos_x_honorarios']) ) {
            $data_comprobante[] = array(
              "comprobante"       => $value['recibos_x_honorarios'],
              "carpeta"           => 'pago_administrador',
              "subcarpeta"        => 'recibos_x_honorarios',
              "host"              => $host,
              "ruta_file"         => $scheme_host.'dist/docs/pago_administrador/recibos_x_honorarios/'.$value['recibos_x_honorarios'],
            );
          }          
        }
      }
    }

    // FACTURAS - PAGO OBRERO ═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = "";

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND pqso.fecha_pago BETWEEN '$fecha_1' AND '$fecha_2'";
    } else {
      if (!empty($fecha_1)) {
        $filtro_fecha = "AND pqso.fecha_pago = '$fecha_1'";
      }else{
        if (!empty($fecha_2)) {
          $filtro_fecha = "AND pqso.fecha_pago = '$fecha_2'";
        }     
      }      
    }    

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND t.numero_documento = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND rqsa.tipo_comprobante = '$comprobante'"; 
    }

    $sql9 = "SELECT tpp.idproyecto, pqso.idpagos_q_s_obrero, pqso.idresumen_q_s_asistencia, pqso.cuenta_deposito, pqso.forma_de_pago, pqso.monto_deposito, 
    pqso.fecha_pago, pqso.baucher, pqso.descripcion, rqsa.recibos_x_honorarios, rqsa.tipo_comprobante, rqsa.numero_comprobante, t.nombres as trabajador, t.imagen_perfil, t.tipo_documento, t.numero_documento,
    pqso.id_user_vb, pqso.nombre_user_vb, pqso.imagen_user_vb, pqso.estado_user_vb,  DATE_FORMAT(pqso.updated_at_vb, '%d/%m/%Y %h:%i %p') as updated_at_vb
    FROM pagos_q_s_obrero as pqso, resumen_q_s_asistencia as rqsa, trabajador_por_proyecto as tpp, trabajador t
    WHERE pqso.idresumen_q_s_asistencia = rqsa.idresumen_q_s_asistencia AND rqsa.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto
    AND tpp.idtrabajador = t.idtrabajador AND pqso.estado = '1' AND pqso.estado_delete = '1' AND tpp.idproyecto = '$idproyecto' AND pqso.$estado_vb
    $filtro_proveedor $filtro_comprobante $filtro_fecha
    ORDER BY pqso.fecha_pago DESC;";
    $otra_factura =  ejecutarConsultaArray($sql9);

    if ($otra_factura['status'] == false) { return $otra_factura; }

    if (!empty($otra_factura['data'])) {
      foreach ($otra_factura['data'] as $key => $value) {
        $data[] = array(
        	"idproyecto"        => $value['idproyecto'],
          "idtabla"           => $value['idpagos_q_s_obrero'],
          "bd_nombre_tabla"   => 'pagos_q_s_obrero',
          "bd_nombre_id_tabla"=> 'idpagos_q_s_obrero',
          "fecha"             => $value['fecha_pago'],
          "tipo_comprobante"  => (empty($value['tipo_comprobante']) ? '' : $value['tipo_comprobante']) ,
          "serie_comprobante" => $value['numero_comprobante'],
          "proveedor"         => $value['trabajador'],
          "total"             => $value['monto_deposito'],
          "igv"               => 0,
          "subtotal"          => $value['monto_deposito'],
          "glosa"             => '',
          "tipo_gravada"      => 'NO GRAVADA',
          "comprobante"       => $value['recibos_x_honorarios'],
          "carpeta"           => 'pago_obrero',
          "subcarpeta"        => 'recibos_x_honorarios',
          "ruta"              => 'dist/docs/pago_obrero/recibos_x_honorarios/',
          "modulo"            => 'PAGO OBRERO',
          "id_user_vb"        => $value['id_user_vb'],
          "nombre_user_vb"    => $value['nombre_user_vb'],
          "imagen_user_vb"    => $value['imagen_user_vb'],
          "estado_user_vb"    => $value['estado_user_vb'],
          "updated_at_vb"    => $value['updated_at_vb'],
          "detalle"           => false,
          "comprobante_multiple" => false,
          "cant_comprobante" => 0,
        );
        if (!empty($value['recibos_x_honorarios'])) {
          if ( validar_url( $scheme_host, 'dist/docs/pago_obrero/recibos_x_honorarios/', $value['recibos_x_honorarios']) ) {
            $data_comprobante[] = array(
              "comprobante"       => $value['recibos_x_honorarios'],
              "carpeta"           => 'pago_obrero',
              "subcarpeta"        => 'recibos_x_honorarios',
              "host"              => $host,
              "ruta_file"         => $scheme_host.'dist/docs/pago_obrero/recibos_x_honorarios/'.$value['recibos_x_honorarios'],
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

  public function suma_totales($idproyecto, $fecha_1, $fecha_2, $id_proveedor, $comprobante, $visto_bueno) {

    $data = Array(); $total = 0; $subtotal = 0; $igv = 0;
    $estado_vb = ($visto_bueno == '' ? "estado_user_vb >=0" : "estado_user_vb =$visto_bueno" );

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
    WHERE cpp.idproveedor = p.idproveedor AND cpp.estado = '1' AND cpp.estado_delete = '1' AND cpp.$estado_vb AND  cpp.idproyecto = $idproyecto $filtro_proveedor $filtro_comprobante $filtro_fecha ;";
    $compra = ejecutarConsultaSimpleFila($sql);

    if ($compra['status'] == false) { return $compra; }

    $total    += (empty($compra['data'])) ? 0 : ( empty($compra['data']['total']) ? 0 : floatval($compra['data']['total']) );
    $subtotal += (empty($compra['data'])) ? 0 : ( empty($compra['data']['subtotal']) ? 0 : floatval($compra['data']['subtotal']) );
    $igv      += (empty($compra['data'])) ? 0 : ( empty($compra['data']['igv']) ? 0 : floatval($compra['data']['igv']) );

    // SUMAS TOTALES - COMPRAS DE ACTIVO FIJO --------------------------------------------------------------------------------
    // $filtro_proveedor = ""; $filtro_comprobante = ""; $filtro_fecha = "";

    // if ( !empty($fecha_1) && !empty($fecha_2) ) {
    //   $filtro_fecha = "AND cafg.fecha_compra BETWEEN '$fecha_1' AND '$fecha_2'";
    // } else {
    //   if (!empty($fecha_1)) {
    //     $filtro_fecha = "AND cafg.fecha_compra = '$fecha_1'";
    //   }else{
    //     if (!empty($fecha_2)) {
    //       $filtro_fecha = "AND cafg.fecha_compra = '$fecha_2'";
    //     }     
    //   }      
    // }    

    // if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND p.ruc = '$id_proveedor'"; }

    // if ( empty($comprobante) ) { } else {
    //   $filtro_comprobante = "AND cafg.tipo_comprobante = '$comprobante'"; 
    // }

    // $sql = "SELECT SUM(cafg.total) AS total, SUM(cafg.subtotal) AS subtotal, SUM(cafg.igv) AS igv
    // FROM compra_af_general  AS cafg, proveedor p
    // WHERE cafg.idproveedor = p.idproveedor AND cafg.estado = '1' AND cafg.estado_delete = '1'  $filtro_proveedor $filtro_comprobante $filtro_fecha ;";
    // $compra = ejecutarConsultaSimpleFila($sql);

    // if ($compra['status'] == false) { return $compra; }

    // $total    += (empty($compra['data'])) ? 0 : ( empty($compra['data']['total']) ? 0 : floatval($compra['data']['total']) );
    // $subtotal += (empty($compra['data'])) ? 0 : ( empty($compra['data']['subtotal']) ? 0 : floatval($compra['data']['subtotal']) );
    // $igv      += (empty($compra['data'])) ? 0 : ( empty($compra['data']['igv']) ? 0 : floatval($compra['data']['igv']) );
     
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
    AND f.estado = '1' AND f.estado_delete = '1' AND f.$estado_vb AND f.idproyecto = $idproyecto $filtro_proveedor $filtro_comprobante $filtro_fecha;";
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
    WHERE s.idproveedor = p.idproveedor and s.estado = '1' AND s.estado_delete = '1' AND s.$estado_vb AND  idproyecto = $idproyecto 
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

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND prov.ruc = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND ps.tipo_comprobante = '$comprobante'"; 
    }

    $sql4 = "SELECT SUM(ps.subtotal) AS subtotal, SUM(ps.igv) AS igv, SUM(ps.costo_parcial) AS total
    FROM planilla_seguro as ps, proyecto as p, proveedor as prov 
    WHERE ps.idproyecto = p.idproyecto and ps.idproveedor = prov.idproveedor and ps.estado ='1' and ps.estado_delete = '1' AND ps.$estado_vb
     AND  ps.idproyecto = $idproyecto $filtro_proveedor $filtro_comprobante $filtro_fecha;";
    $otro_gasto = ejecutarConsultaSimpleFila($sql4);

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

    $sql5 = "SELECT SUM(costo_parcial) as total, SUM(subtotal) AS subtotal, SUM(igv) AS igv
    FROM otro_gasto  
    WHERE estado = '1' AND estado_delete = '1' AND $estado_vb AND  idproyecto = $idproyecto $filtro_proveedor $filtro_comprobante $filtro_fecha;";
    $otro_gasto = ejecutarConsultaSimpleFila($sql5);

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

    $sql6 = "SELECT SUM(t.precio_parcial) AS total, SUM(t.subtotal) AS subtotal, SUM(t.igv) AS igv
    FROM transporte AS t, proveedor AS p
    WHERE t.idproveedor = p.idproveedor  AND t.estado = '1' AND t.estado_delete = '1' AND t.$estado_vb AND t.idproyecto = $idproyecto $filtro_proveedor $filtro_comprobante $filtro_fecha;";
    $transporte = ejecutarConsultaSimpleFila($sql6);

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
    $sql7 = "SELECT SUM(precio_parcial) as total , SUM(subtotal) AS subtotal, SUM(igv) AS igv
    FROM hospedaje WHERE estado = '1' AND estado_delete = '1' AND $estado_vb AND idproyecto = $idproyecto $filtro_proveedor $filtro_comprobante $filtro_fecha
    ORDER BY fecha_comprobante DESC;";
    $hospedaje = ejecutarConsultaSimpleFila($sql7);

    if ($hospedaje['status'] == false) { return $hospedaje; }
    
    $total    += (empty($hospedaje['data'])) ? 0 : ( empty($hospedaje['data']['total']) ? 0 : floatval($hospedaje['data']['total']) );
    $subtotal += (empty($hospedaje['data'])) ? 0 : ( empty($hospedaje['data']['subtotal']) ? 0 : floatval($hospedaje['data']['subtotal']) );
    $igv      += (empty($hospedaje['data'])) ? 0 : ( empty($hospedaje['data']['igv']) ? 0 : floatval($hospedaje['data']['igv']) );

    // SUMAS TOTALES - FACTURA PENSION --------------------------------------------------------------------------------
    $filtro_proveedor = ""; $filtro_comprobante = ""; $filtro_fecha = "";

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND dp.fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
    } else {
      if (!empty($fecha_1)) {
        $filtro_fecha = "AND dp.fecha_emision = '$fecha_1'";
      }else{
        if (!empty($fecha_2)) {
          $filtro_fecha = "AND dp.fecha_emision = '$fecha_2'";
        }     
      }      
    }    

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND prov.ruc = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND dp.tipo_comprobante = '$comprobante'"; 
    }
    $sql8 = "SELECT SUM(dp.precio_parcial) AS total, SUM(dp.subtotal) AS subtotal, SUM(dp.igv) AS igv
		FROM detalle_pension as dp, pension as p, proveedor as prov
		WHERE dp.idpension = p.idpension AND prov.idproveedor = p.idproveedor  AND p.estado = '1' AND p.estado_delete = '1' AND dp.$estado_vb 
    AND  p.idproyecto = $idproyecto AND dp.estado = '1' AND dp.estado_delete = '1' $filtro_proveedor $filtro_comprobante $filtro_fecha ;";
    $factura_pension = ejecutarConsultaSimpleFila($sql8);

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
    $sql9 = "SELECT SUM(fb.monto) AS total, SUM(fb.subtotal) AS subtotal, SUM(fb.igv) AS igv
		FROM factura_break as fb, semana_break as sb
		WHERE  fb.idsemana_break = sb.idsemana_break AND fb.estado = '1' AND fb.estado_delete = '1' AND sb.estado = '1' AND fb.$estado_vb AND  sb.idproyecto = $idproyecto
    AND sb.estado_delete = '1' $filtro_proveedor $filtro_comprobante $filtro_fecha ;";
    $factura_break = ejecutarConsultaSimpleFila($sql9);

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
    $sql10 = "SELECT SUM(costo_parcial) AS total, SUM(subtotal) AS subtotal, SUM(igv) AS igv
		FROM comida_extra
		WHERE  estado = '1' AND estado_delete = '1' AND $estado_vb AND  idproyecto = $idproyecto $filtro_proveedor $filtro_comprobante $filtro_fecha;";
    $comida_extra = ejecutarConsultaSimpleFila($sql10);

    if ($comida_extra['status'] == false) { return $comida_extra; }
    
    $total    += (empty($comida_extra['data'])) ? 0 : ( empty($comida_extra['data']['total']) ? 0 : floatval($comida_extra['data']['total']) );
    $subtotal += (empty($comida_extra['data'])) ? 0 : ( empty($comida_extra['data']['subtotal']) ? 0 : floatval($comida_extra['data']['subtotal']) );
    $igv      += (empty($comida_extra['data'])) ? 0 : ( empty($comida_extra['data']['igv']) ? 0 : floatval($comida_extra['data']['igv']) );

    // SUMAS TOTALES - OTRO INGRESO --------------------------------------------------------------------------------

    // $filtro_proveedor = ""; $filtro_comprobante = ""; $filtro_fecha = "";

    // if ( !empty($fecha_1) && !empty($fecha_2) ) {
    //   $filtro_fecha = "AND oi.fecha_i BETWEEN '$fecha_1' AND '$fecha_2'";
    // } else {
    //   if (!empty($fecha_1)) {
    //     $filtro_fecha = "AND oi.fecha_i = '$fecha_1'";
    //   }else{
    //     if (!empty($fecha_2)) {
    //       $filtro_fecha = "AND oi.fecha_i = '$fecha_2'";
    //     }     
    //   }      
    // }    

    // if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND oi.ruc = '$id_proveedor'"; }

    // if ( empty($comprobante) ) { } else {
    //   $filtro_comprobante = "AND oi.tipo_comprobante = '$comprobante'"; 
    // }

    // $sql9 = "SELECT SUM(oi.subtotal) as subtotal, SUM(oi.igv) as igv, SUM(oi.costo_parcial) as total
    // FROM otro_ingreso as oi, proyecto as p
    // WHERE oi.idproyecto = p.idproyecto AND oi.estado = '1' AND oi.estado_delete = '1' AND  oi.idproyecto = $idproyecto $filtro_proveedor $filtro_comprobante $filtro_fecha";
    // $otra_factura = ejecutarConsultaSimpleFila($sql9);

    // if ($otra_factura['status'] == false) { return $otra_factura; } 
    
    // $total    += (empty($otra_factura['data'])) ? 0 : ( empty($otra_factura['data']['total']) ? 0 : floatval($otra_factura['data']['total']) );
    // $subtotal += (empty($otra_factura['data'])) ? 0 : ( empty($otra_factura['data']['subtotal']) ? 0 : floatval($otra_factura['data']['subtotal']) );
    // $igv      += (empty($otra_factura['data'])) ? 0 : ( empty($otra_factura['data']['igv']) ? 0 : floatval($otra_factura['data']['igv']) );

    // SUMAS TOTALES - OTRA FACTURA --------------------------------------------------------------------------------
    // $filtro_proveedor = ""; $filtro_comprobante = ""; $filtro_fecha = "";

    // if ( !empty($fecha_1) && !empty($fecha_2) ) {
    //   $filtro_fecha = "AND of.fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
    // } else {
    //   if (!empty($fecha_1)) {
    //     $filtro_fecha = "AND of.fecha_emision = '$fecha_1'";
    //   }else{
    //     if (!empty($fecha_2)) {
    //       $filtro_fecha = "AND of.fecha_emision = '$fecha_2'";
    //     }     
    //   }      
    // }    

    // if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND p.ruc = '$id_proveedor'"; }

    // if ( empty($comprobante) ) { } else {
    //   $filtro_comprobante = "AND of.tipo_comprobante = '$comprobante'"; 
    // }
    // $sql9 = "SELECT SUM(of.costo_parcial) AS total, SUM(of.subtotal) AS subtotal, SUM(of.igv) AS igv
    // FROM otra_factura AS of, proveedor p
    // WHERE of.idproveedor = p.idproveedor AND of.estado = '1' AND of.estado_delete = '1' $filtro_proveedor $filtro_comprobante $filtro_fecha";
    // $otra_factura = ejecutarConsultaSimpleFila($sql9);

    // if ($otra_factura['status'] == false) { return $otra_factura; } 
    
    // $total    += (empty($otra_factura['data'])) ? 0 : ( empty($otra_factura['data']['total']) ? 0 : floatval($otra_factura['data']['total']) );
    // $subtotal += (empty($otra_factura['data'])) ? 0 : ( empty($otra_factura['data']['subtotal']) ? 0 : floatval($otra_factura['data']['subtotal']) );
    // $igv      += (empty($otra_factura['data'])) ? 0 : ( empty($otra_factura['data']['igv']) ? 0 : floatval($otra_factura['data']['igv']) );
    
    // SUMAS TOTALES - PAGO ADMINISTRADOR --------------------------------------------------------------------------------
    $filtro_proveedor = ""; $filtro_comprobante = ""; $filtro_fecha = "";

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND pxma.fecha_pago BETWEEN '$fecha_1' AND '$fecha_2'";
    } else {
      if (!empty($fecha_1)) {
        $filtro_fecha = "AND pxma.fecha_pago = '$fecha_1'";
      }else{
        if (!empty($fecha_2)) {
          $filtro_fecha = "AND pxma.fecha_pago = '$fecha_2'";
        }     
      }      
    }    

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND t.numero_documento = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND fmpa.tipo_comprobante = '$comprobante'"; 
    }
    $sql11 = "SELECT SUM(pxma.monto) total, SUM(pxma.monto) AS subtotal
    FROM pagos_x_mes_administrador as pxma, fechas_mes_pagos_administrador as fmpa, trabajador_por_proyecto as tpp, trabajador t
    WHERE pxma.idfechas_mes_pagos_administrador = fmpa.idfechas_mes_pagos_administrador AND fmpa.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto  AND tpp.idtrabajador = t.idtrabajador
    AND pxma.estado = '1' AND pxma.estado_delete = '1' AND pxma.$estado_vb AND tpp.idproyecto = '$idproyecto' $filtro_proveedor $filtro_comprobante $filtro_fecha;";
    $pago_administrador = ejecutarConsultaSimpleFila($sql11);

    if ($pago_administrador['status'] == false) { return $pago_administrador; }
    
    $total    += (empty($pago_administrador['data'])) ? 0 : ( empty($pago_administrador['data']['total']) ? 0 : floatval($pago_administrador['data']['total']) );
    $subtotal += (empty($pago_administrador['data'])) ? 0 : ( empty($pago_administrador['data']['subtotal']) ? 0 : floatval($pago_administrador['data']['subtotal']) );
    $igv      += 0;

    // SUMAS TOTALES - PAGO OBRERO --------------------------------------------------------------------------------
    $filtro_proveedor = ""; $filtro_comprobante = ""; $filtro_fecha = "";

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND pqso.fecha_pago BETWEEN '$fecha_1' AND '$fecha_2'";
    } else {
      if (!empty($fecha_1)) {
        $filtro_fecha = "AND pqso.fecha_pago = '$fecha_1'";
      }else{
        if (!empty($fecha_2)) {
          $filtro_fecha = "AND pqso.fecha_pago = '$fecha_2'";
        }     
      }      
    }    

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND t.numero_documento = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND rqsa.tipo_comprobante = '$comprobante'"; 
    }
    $sql12 = "SELECT SUM(pqso.monto_deposito) total, SUM(pqso.monto_deposito) AS subtotal
    FROM pagos_q_s_obrero as pqso, resumen_q_s_asistencia as rqsa, trabajador_por_proyecto as tpp, trabajador t
    WHERE pqso.idresumen_q_s_asistencia = rqsa.idresumen_q_s_asistencia AND rqsa.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto AND tpp.idtrabajador = t.idtrabajador
    AND pqso.estado = '1' AND pqso.estado_delete = '1' AND pqso.$estado_vb AND tpp.idproyecto = '$idproyecto' $filtro_proveedor $filtro_comprobante $filtro_fecha;";
    $pago_obrero = ejecutarConsultaSimpleFila($sql12);

    if ($pago_obrero['status'] == false) { return $pago_obrero; }
    
    $total    += (empty($pago_obrero['data'])) ? 0 : ( empty($pago_obrero['data']['total']) ? 0 : floatval($pago_obrero['data']['total']) );
    $subtotal += (empty($pago_obrero['data'])) ? 0 : ( empty($pago_obrero['data']['subtotal']) ? 0 : floatval($pago_obrero['data']['subtotal']) );
    $igv      += 0;

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

  // ════════════════════════════════════════ S E L E C T 2   -   P R O V E E D O R ════════════════════════════════════════
  public function select_proveedores($id)  {

    $data = Array();

    $sql_1 = "SELECT idproveedor, razon_social, ruc FROM proveedor WHERE estado = '1' AND estado_delete = '1';";
    $proveedor = ejecutarConsultaArray($sql_1);    
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
    
    $sql_2 = "SELECT ruc, razon_social FROM otro_gasto WHERE idproyecto = '$id' AND estado = '1' AND estado_delete = '1' AND ruc != '' AND razon_social != '' GROUP BY ruc;";
    $otro_gasto = ejecutarConsultaArray($sql_2);
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

    $sql_3 = "SELECT ruc, razon_social  FROM hospedaje WHERE idproyecto = '$id' AND estado = '1' AND estado_delete = '1' AND ruc != '' AND razon_social != '' GROUP BY ruc;";
    $hospedaje = ejecutarConsultaArray($sql_3);
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

    $sql_4 = "SELECT ruc, razon_social  FROM comida_extra WHERE idproyecto = '$id' AND estado = '1' AND estado_delete = '1' AND ruc != '' AND razon_social != '' GROUP BY ruc;";
    $comida_extra = ejecutarConsultaArray($sql_4);
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

    $sql_5 = "SELECT t.idtrabajador, t.nombres as trabajador, t.imagen_perfil, t.tipo_documento, t.numero_documento
    FROM pagos_x_mes_administrador as pxma, fechas_mes_pagos_administrador as fmpa, trabajador_por_proyecto as tpp, trabajador t
    WHERE pxma.idfechas_mes_pagos_administrador = fmpa.idfechas_mes_pagos_administrador AND fmpa.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto
    AND tpp.idtrabajador = t.idtrabajador AND pxma.estado = '1' AND pxma.estado_delete = '1' AND tpp.idproyecto = '$id' GROUP BY t.idtrabajador;";
    $comida_extra = ejecutarConsultaArray($sql_5);
    if ($comida_extra['status'] == false) { return $comida_extra; }

    if ( !empty($comida_extra['data']) ) {
      foreach ($comida_extra['data'] as $key => $value) {
        $data[] = array(
          "id" =>  $value['idtrabajador'],
          "razon_social" =>  $value['trabajador'],
          "ruc" =>  $value['numero_documento'],
        );
      }      
    } 

    $sql_6 = "SELECT t.idtrabajador, t.nombres as trabajador, t.imagen_perfil, t.tipo_documento, t.numero_documento
    FROM pagos_q_s_obrero as pqso, resumen_q_s_asistencia as rqsa, trabajador_por_proyecto as tpp, trabajador t
    WHERE pqso.idresumen_q_s_asistencia = rqsa.idresumen_q_s_asistencia AND rqsa.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto
    AND tpp.idtrabajador = t.idtrabajador AND pqso.estado = '1' AND pqso.estado_delete = '1' AND tpp.idproyecto = '$id' GROUP BY t.idtrabajador;";
    $comida_extra = ejecutarConsultaArray($sql_6);
    if ($comida_extra['status'] == false) { return $comida_extra; }

    if ( !empty($comida_extra['data']) ) {
      foreach ($comida_extra['data'] as $key => $value) {
        $data[] = array(
          "id" =>  $value['idtrabajador'],
          "razon_social" =>  $value['trabajador'],
          "ruc" =>  $value['numero_documento'],
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
  
  // ════════════════════════════════════════ VISTO BUENO ════════════════════════════════════════
  public function visto_bueno($nombre_tabla, $nombre_id_tabla, $id_tabla, $accion) {

    $id_user_vb = $_SESSION["idusuario"]; $nombre_user_vb = $_SESSION["nombre"]; $imagen_user_vb = $_SESSION["imagen"];

    if ($accion == 'agregar') {
      $sql = "UPDATE $nombre_tabla SET estado='1', id_user_vb = '$id_user_vb', nombre_user_vb = '$nombre_user_vb', 
      imagen_user_vb = '$imagen_user_vb', estado_user_vb = '1'
      WHERE $nombre_id_tabla ='$id_tabla' ";
      $agregar = ejecutarConsulta($sql); if ( $agregar['status'] == false) {return $agregar; } 

      //add registro en nuestra bitacora
      $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('$nombre_tabla','".$id_tabla."','Agregar Visto bueno','" . $_SESSION['idusuario'] . "')";
      $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; } 

      return $agregar;

    } else if ($accion == 'quitar') {
      $sql = "UPDATE $nombre_tabla SET estado='1', id_user_vb = '', nombre_user_vb = '', imagen_user_vb ='', estado_user_vb = '0'
      WHERE $nombre_id_tabla ='$id_tabla'";
      $quitar = ejecutarConsulta($sql); if ( $quitar['status'] == false) {return $quitar; } 

      //add registro en nuestra bitacora
      $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('$nombre_tabla','".$id_tabla."','Quitar Visto bueno','" . $_SESSION['idusuario'] . "')";
      $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; } 

      return $quitar;
    } 
  }

  // ════════════════════════════════════════ DETALLE MODULO ════════════════════════════════════════
  // detalle_servicio_maquina
  public function detalle_servicio_maquina($id) {
    $sql = "SELECT mq.nombre as nombre_maquina, prov.razon_social, f.codigo, f.fecha_emision,  f.subtotal, f.igv, f.monto as total, 
    f.nota, f.descripcion, f.imagen as comprobante
    FROM factura as f, proyecto as p, maquinaria as mq, proveedor as prov
    WHERE f.idmaquinaria=mq.idmaquinaria AND mq.idproveedor=prov.idproveedor AND f.idproyecto=p.idproyecto 
    AND f.estado = '1' AND f.estado_delete = '1' AND mq.tipo = '1' AND  f.idfactura = '$id';";
    return ejecutarConsultaSimpleFila($sql);
  }

  // detalle_servicio_equipo
  public function detalle_servicio_equipo($id) {
    $sql = "SELECT mq.nombre as nombre_maquina, prov.razon_social, f.codigo, f.fecha_emision,  f.subtotal, f.igv, f.monto as total, 
    f.nota, f.descripcion, f.imagen as comprobante
    FROM factura as f, proyecto as p, maquinaria as mq, proveedor as prov
    WHERE f.idmaquinaria=mq.idmaquinaria AND mq.idproveedor=prov.idproveedor AND f.idproyecto=p.idproyecto 
    AND f.estado = '1' AND f.estado_delete = '1' AND mq.tipo = '2' AND  f.idfactura = '$id';";
    return ejecutarConsultaSimpleFila($sql);
  }

  // detalle_sub_contrato
  public function detalle_sub_contrato($id) {
    $sql = "SELECT p.razon_social, p.tipo_documento, p.ruc, s.forma_de_pago, s.tipo_comprobante, s.numero_comprobante, s.fecha_subcontrato, 
    s.val_igv, s.subtotal, s.igv, s.costo_parcial as total, s.descripcion, s.glosa, s.comprobante    
    FROM subcontrato AS s, proveedor as p
    WHERE s.idproveedor = p.idproveedor and s.estado = '1' AND s.estado_delete = '1' AND s.idsubcontrato = '$id';";
    return ejecutarConsultaSimpleFila($sql);
  }

  // detalle_sub_contrato
  public function detalle_mano_de_obra($id) {
    $sql = "SELECT mdo.idmano_de_obra, mdo.idproyecto, mdo.idproveedor, mdo.fecha_inicial, mdo.fecha_final, mdo.fecha_deposito, mdo.monto, mdo.descripcion, 
    p.razon_social, p.tipo_documento, p.ruc 
    FROM mano_de_obra as mdo, proveedor as p
    WHERE mdo.idproveedor = p.idproveedor and idmano_de_obra = '$id';";
    return ejecutarConsultaSimpleFila($sql);
  }

  // detalle_sub_contrato
  public function detalle_planilla_seguro($id) {
    $sql = "SELECT ps.forma_de_pago, ps.tipo_comprobante, ps.numero_comprobante, ps.fecha_p_s, ps.subtotal, ps.igv, ps.costo_parcial as total, 
     ps.val_igv, ps.tipo_gravada, ps.comprobante, ps.descripcion
    FROM planilla_seguro as ps, proyecto as p
    WHERE ps.idproyecto = p.idproyecto and ps.estado ='1' and ps.estado_delete = '1' AND ps.idplanilla_seguro = '$id';";
    return ejecutarConsultaSimpleFila($sql);
  }

  // detalle_sub_contrato
  public function detalle_otro_gasto($id) {
    $sql = "SELECT  razon_social, ruc, forma_de_pago, tipo_comprobante, numero_comprobante, fecha_g, subtotal, val_igv, igv, glosa, 
    costo_parcial as total, tipo_gravada, comprobante, descripcion
    FROM otro_gasto 
    WHERE  estado = '1' AND estado_delete = '1' AND idotro_gasto = '$id';";
    return ejecutarConsultaSimpleFila($sql);
  }

  // detalle_sub_contrato
  public function detalle_transporte($id) {
    $sql = "SELECT t.idtransporte, t.idproyecto, p.razon_social, p.tipo_documento, p.ruc, t.forma_de_pago, t.tipo_comprobante, t.numero_comprobante,
    t.fecha_viaje, t.cantidad, t.precio_unitario, t.subtotal, t.igv, t.val_igv, t.precio_parcial as total,  t.comprobante , t.glosa , t.tipo_gravada, 
    t.tipo_viajero, t.tipo_ruta, t.ruta, t.descripcion
    FROM transporte AS t, proveedor AS p
    WHERE t.idproveedor = p.idproveedor  AND t.estado = '1' AND t.estado_delete = '1' AND  t.idtransporte = '$id';";
    return ejecutarConsultaSimpleFila($sql);
  }

  // detalle_sub_contrato
  public function detalle_hospedaje($id) {
    $sql = "SELECT  idhospedaje, idproyecto, razon_social, ruc, forma_de_pago, tipo_comprobante, numero_comprobante, fecha_comprobante, cantidad, 
    unidad, precio_unitario, subtotal, igv, val_igv, precio_parcial as total, glosa, comprobante, tipo_gravada, descripcion, fecha_inicio, fecha_fin
    FROM hospedaje 
    WHERE estado = '1' AND estado_delete = '1' AND  idhospedaje = '$id';";
    return ejecutarConsultaSimpleFila($sql);
  }

  // detalle_sub_contrato
  public function detalle_pension($id) {
    $sql = "SELECT dp.iddetalle_pension, dp.idpension, dp.fecha_inicial, dp.fecha_final, dp.cantidad_persona, dp.subtotal, dp.igv, 
    dp.val_igv, dp.precio_parcial, dp.forma_pago, dp.tipo_comprobante, dp.fecha_emision, dp.tipo_gravada, dp.glosa, dp.numero_comprobante, 
    dp.descripcion, dp.comprobante, dp.id_user_vb, dp.nombre_user_vb, dp.imagen_user_vb, dp.estado_user_vb, prov.razon_social, 
    prov.tipo_documento, prov.ruc, p.idproyecto
    FROM detalle_pension as dp, pension as p, proveedor as prov
    WHERE dp.idpension = p.idpension AND p.idproveedor = prov.idproveedor AND dp.estado = '1' AND dp.estado_delete = '1' 
    AND  dp.iddetalle_pension = '$id';";
    return ejecutarConsultaSimpleFila($sql);
  }

  // detalle_sub_contrato
  public function detalle_break($id) {
    $sql = "SELECT sb.idproyecto, fb.idfactura_break, fb.razon_social, fb.ruc, fb.forma_de_pago, fb.fecha_emision, fb.tipo_comprobante, 
    fb.nro_comprobante, fb.subtotal, fb.igv, fb.val_igv, fb.monto as total, fb.glosa, fb.comprobante, fb.tipo_gravada, fb.descripcion
    FROM factura_break as fb, semana_break as sb
    WHERE  fb.idsemana_break = sb.idsemana_break  
    AND fb.estado = '1' AND fb.estado_delete = '1' AND sb.estado = '1' AND sb.estado_delete = '1' AND  fb.idfactura_break = '$id';";
    return ejecutarConsultaSimpleFila($sql);
  }

  // detalle_sub_contrato
  public function detalle_comida_extra($id) {
    $sql = "SELECT idproyecto, idcomida_extra, razon_social, ruc, forma_de_pago, fecha_comida, tipo_comprobante, numero_comprobante, 
    subtotal, igv, val_igv, costo_parcial as total, glosa, comprobante, tipo_gravada, descripcion
    FROM comida_extra
    WHERE  estado = '1' AND estado_delete = '1' AND idcomida_extra = '$id';";
    return ejecutarConsultaSimpleFila($sql);
  }

  // detalle_pago_administrador
  public function detalle_pago_administrador($id) {
    $sql = "SELECT tpp.idproyecto, pxma.idpagos_x_mes_administrador, pxma.idfechas_mes_pagos_administrador, pxma.cuenta_deposito, pxma.forma_de_pago, pxma.monto, 
    pxma.fecha_pago, pxma.baucher, pxma.descripcion, fmpa.recibos_x_honorarios, fmpa.tipo_comprobante, fmpa.numero_comprobante, t.nombres as trabajador, t.imagen_perfil, t.tipo_documento, t.numero_documento, 
    pxma.id_user_vb, pxma.nombre_user_vb, pxma.imagen_user_vb, pxma.estado_user_vb
    FROM pagos_x_mes_administrador as pxma, fechas_mes_pagos_administrador as fmpa, trabajador_por_proyecto as tpp, trabajador t
    WHERE pxma.idfechas_mes_pagos_administrador = fmpa.idfechas_mes_pagos_administrador AND fmpa.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto
    AND tpp.idtrabajador = t.idtrabajador AND pxma.idpagos_x_mes_administrador = '$id';";
    return ejecutarConsultaSimpleFila($sql);
  }

  // detalle_pago_administrador
  public function detalle_pago_obrero($id) {
    $sql = "SELECT tpp.idproyecto, pqso.idpagos_q_s_obrero, pqso.idresumen_q_s_asistencia, pqso.cuenta_deposito, pqso.forma_de_pago, pqso.monto_deposito, 
    pqso.fecha_pago, pqso.baucher, pqso.descripcion, rqsa.recibos_x_honorarios, rqsa.tipo_comprobante, rqsa.numero_comprobante, t.nombres as trabajador, t.imagen_perfil, t.tipo_documento, t.numero_documento,
    pqso.id_user_vb, pqso.nombre_user_vb, pqso.imagen_user_vb, pqso.estado_user_vb
    FROM pagos_q_s_obrero as pqso, resumen_q_s_asistencia as rqsa, trabajador_por_proyecto as tpp, trabajador t
    WHERE pqso.idresumen_q_s_asistencia = rqsa.idresumen_q_s_asistencia AND rqsa.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto
    AND tpp.idtrabajador = t.idtrabajador AND pqso.idpagos_q_s_obrero = '$id';";
    return ejecutarConsultaSimpleFila($sql);
  }

  // ════════════════════════════════════════ ACCIONES ════════════════════════════════════════
  // eliminar permanente
  public function eliminar_permanente($nombre_tabla, $nombre_id_tabla, $id_tabla) {
    $sql = "UPDATE $nombre_tabla SET estado_delete='0' WHERE $nombre_id_tabla ='$id_tabla'";
    $eliminar =  ejecutarConsulta($sql);
    if ( $eliminar['status'] == false) {return $eliminar; }  

    //add registro en nuestra bitacora
    $sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('$nombre_tabla','$id_tabla','Eliminado $nombre_tabla','" . $_SESSION['idusuario'] . "')";
    $bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  

    return $eliminar;
  }
  
}

?>
