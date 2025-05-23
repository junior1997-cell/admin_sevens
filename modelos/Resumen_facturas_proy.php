<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class ResumenfacturasProyecto
{
  //Implementamos nuestro constructor
  public function __construct() {}

  public function facturas_compras($idproyecto, $empresa_a_cargo, $fecha_1, $fecha_2, $id_proveedor, $comprobante, $visto_bueno, $modulo)
  {

    $data = array();
    $data_comprobante = array();
    $filtro_proveedor = "";
    $filtro_fecha = "";
    $filtro_comprobante = "";

    $scheme_host      =  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/admin_sevens/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/');
    $host             = $_SERVER['HTTP_HOST'];
    $estado_vb        = (empty($visto_bueno) ? "estado_user_vb_rf IN ('0','1')" : "estado_user_vb_rf =$visto_bueno");
    $idempresa_a_cargo = (empty($empresa_a_cargo) ? "idempresa_a_cargo >= '1'" : "idempresa_a_cargo = '$empresa_a_cargo'");

    // FACTURAS - COMPRAS ═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    if ($modulo == 'todos' || $modulo == 'compra_insumo') {

      if (!empty($fecha_1) && !empty($fecha_2)) {
        $filtro_fecha = "AND cpp.fecha_compra BETWEEN '$fecha_1' AND '$fecha_2'";
      } else if (!empty($fecha_1)) {
        $filtro_fecha = "AND cpp.fecha_compra = '$fecha_1'";
      } else if (!empty($fecha_2)) {
        $filtro_fecha = "AND cpp.fecha_compra = '$fecha_2'";
      }

      if (empty($id_proveedor)) {
        $filtro_proveedor = "";
      } else {
        $filtro_proveedor = "AND p.ruc = '$id_proveedor'";
      }

      if (empty($comprobante)) {
        $filtro_comprobante = "AND cpp.tipo_comprobante IN ('Factura','Boleta','Nota de Crédito')";
      } else {
        $filtro_comprobante = "AND cpp.tipo_comprobante = '$comprobante'";
      }

      // var_dump($filtro_proveedor , $filtro_fecha ,$filtro_comprobante); die();

      $sql = "SELECT cpp.idproyecto, cpp.idcompra_proyecto, cpp.fecha_compra, cpp.tipo_comprobante,	cpp.serie_comprobante, cpp.descripcion, 
      cpp.total, cpp.subtotal, cpp.igv,  p.razon_social, p.tipo_documento, p.ruc, cpp.glosa, cpp.tipo_gravada, cpp.comprobante,
      cpp.id_user_vb_rf, cpp.nombre_user_vb_rf, cpp.imagen_user_vb_rf, cpp.estado_user_vb_rf
      FROM compra_por_proyecto as cpp, proyecto AS pry, proveedor as p 
      WHERE cpp.idproveedor=p.idproveedor and cpp.idproyecto='$idproyecto' and pry.idproyecto = cpp.idproyecto AND cpp.estado = '1' AND cpp.estado_delete = '1' 
      and pry.$idempresa_a_cargo AND cpp.$estado_vb $filtro_proveedor $filtro_comprobante $filtro_fecha ORDER BY cpp.fecha_compra DESC;";
      $compra = ejecutarConsultaArray($sql);

      if ($compra['status'] == false) {
        return $compra;
      }

      if (!empty($compra['data'])) {
        foreach ($compra['data'] as $key => $value) {
          $id_compra = $value['idcompra_proyecto'];
          $sql3 = "SELECT COUNT(comprobante) as cant FROM factura_compra_insumo WHERE idcompra_proyecto='$id_compra' AND estado='1' AND estado_delete='1'";
          $cant_comprob = ejecutarConsultaSimpleFila($sql3);
          if ($cant_comprob['status'] == false) {
            return $cant_comprob;
          }

          if ($value['tipo_comprobante'] == 'Factura') {
            $tipo_comprob  = 'FT';
          } elseif ($value['tipo_comprobante'] == 'Boleta') {
            $tipo_comprob  = 'BV';
          } elseif ($value['tipo_comprobante'] == 'Nota de Crédito') {
            $tipo_comprob  = 'NC';
          }

          //$tipo_comprobantee  = (empty($value['tipo_comprobante']) ? '' : ($value['tipo_comprobante'] == 'Factura' ? 'FT' : ($value['tipo_comprobante'] == 'Boleta' ? 'BV' : ($value['tipo_comprobante'] == 'Nota de Crédito' ? 'NC' : ''))));

          $data[] = array(
            "idproyecto"        => $value['idproyecto'],
            "idtabla"           => $value['idcompra_proyecto'],
            "bd_nombre_tabla"   => 'compra_por_proyecto',
            "bd_nombre_id_tabla" => 'idcompra_proyecto',
            "fecha"             => $value['fecha_compra'],
            "tipo_comprobante"  => $tipo_comprob,
            "serie_comprobante" => $value['serie_comprobante'],
            "proveedor"         => $value['razon_social'],
            "tipo_documento"    => $value['tipo_documento'],
            "ruc"               => $value['ruc'],
            "total"             => $value['total'],
            "subtotal"          => $value['subtotal'],
            "igv"               => $value['igv'],
            "glosa"             => $value['glosa'],
            "tipo_gravada"      => $value['tipo_gravada'],
            "comprobante"       => $value['comprobante'],
            "carpeta"           => 'compra_insumo',
            "subcarpeta"        => 'comprobante_compra',
            "ruta"              => 'dist/docs/compra_insumo/comprobante_compra/',
            "modulo"            => 'COMPRAS',
            "id_user_vb_rf"     => $value['id_user_vb_rf'],
            "nombre_user_vb_rf" => $value['nombre_user_vb_rf'],
            "imagen_user_vb_rf" => $value['imagen_user_vb_rf'],
            "estado_user_vb_rf" => $value['estado_user_vb_rf'],
            "comprobante_multiple" => true,
            'cant_comprobante' => (empty($cant_comprob['data']) ? 0 : (empty($cant_comprob['data']['cant']) ? 0 : floatval($cant_comprob['data']['cant']))),
          );

          if (!empty($value['comprobante'])) {
            if (validar_url($scheme_host, 'dist/docs/compra_insumo/comprobante_compra/', $value['comprobante'])) {
              $data_comprobante[] = array(
                "comprobante"    => $value['comprobante'],
                "carpeta"        => 'compra_insumo',
                "subcarpeta"     => 'comprobante_compra',
                "host"           => $host,
                "ruta_file"      => $scheme_host . 'dist/docs/compra_insumo/comprobante_compra/' . $value['comprobante'],
              );
            }
          }
        }
      }
    }

    // FACTURAS - SERVICIO MAQUINA ════════════════════════════════════════════════════════════════════════════════════════════════════════
    $filtro_proveedor = "";
    $filtro_fecha = "";
    $filtro_comprobante = "";

    if ($modulo == 'todos' || $modulo == 'servicio_maquina') {

      if (!empty($fecha_1) && !empty($fecha_2)) {
        $filtro_fecha = "AND f.fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
      } else if (!empty($fecha_1)) {
        $filtro_fecha = "AND f.fecha_emision = '$fecha_1'";
      } else if (!empty($fecha_2)) {
        $filtro_fecha = "AND f.fecha_emision = '$fecha_2'";
      }

      if (empty($comprobante)) {
        $filtro_comprobante = "AND f.tipo_comprobante IN ('Factura','Boleta','Nota de Crédito')";
      } else {
        $filtro_comprobante = "AND f.tipo_comprobante = '$comprobante'";
      }

      if (empty($id_proveedor)) {
        $filtro_proveedor = "";
      } else {
        $filtro_proveedor = "AND prov.ruc = '$id_proveedor'";
      }

      $sql2 = "SELECT f.idfactura, f.idproyecto, f.codigo, f.fecha_emision, f.monto, f.subtotal, f.igv,
      f.nota,f.tipo_comprobante,
      CASE
            WHEN f.tipo_comprobante = 'Boleta' THEN 'BV'
            WHEN f.tipo_comprobante = 'Factura' THEN 'FT'
            ELSE 'OTRO'
        END AS tipo_compr, mq.nombre, prov.razon_social, prov.tipo_documento, prov.ruc, f.descripcion, f.imagen,
      f.id_user_vb_rf, f.nombre_user_vb_rf, f.imagen_user_vb_rf, f.estado_user_vb_rf
      FROM factura as f, proyecto as p, maquinaria as mq, proveedor as prov 
      WHERE f.idmaquinaria=mq.idmaquinaria AND mq.idproveedor=prov.idproveedor AND f.idproyecto=p.idproyecto and f.idproyecto='$idproyecto'
      AND f.estado = '1' AND f.estado_delete = '1' AND mq.tipo = '1' and p.$idempresa_a_cargo AND f.$estado_vb $filtro_proveedor $filtro_comprobante $filtro_fecha
      ORDER BY f.fecha_emision DESC;";
      $maquinaria_equipo =  ejecutarConsultaArray($sql2);

      if ($maquinaria_equipo['status'] == false) {
        return $maquinaria_equipo;
      }

      if (!empty($maquinaria_equipo['data'])) {
        foreach ($maquinaria_equipo['data'] as $key => $value) {
          $data[] = array(
            "idproyecto"        => $value['idproyecto'],
            "idtabla"           => $value['idfactura'],
            "bd_nombre_tabla"   => 'factura',
            "bd_nombre_id_tabla" => 'idfactura',
            "fecha"             => $value['fecha_emision'],
            "tipo_comprobante"  => $value['tipo_compr'],
            "serie_comprobante" => $value['codigo'],
            "proveedor"         => $value['razon_social'],
            "tipo_documento"    => $value['tipo_documento'],
            "ruc"               => $value['ruc'],
            "total"             => $value['monto'],
            "subtotal"          => $value['subtotal'],
            "igv"               => $value['igv'],
            "glosa"             => 'MAQUINARIA',
            "tipo_gravada"      => 'GRAVADA',
            "comprobante"       => $value['imagen'],
            "carpeta"           => 'servicio_maquina',
            "subcarpeta"        => 'comprobante_servicio',
            "ruta"              => 'dist/docs/servicio_maquina/comprobante_servicio/',
            "modulo"            => 'MAQUINA',
            "id_user_vb_rf"     => $value['id_user_vb_rf'],
            "nombre_user_vb_rf" => $value['nombre_user_vb_rf'],
            "imagen_user_vb_rf" => $value['imagen_user_vb_rf'],
            "estado_user_vb_rf" => $value['estado_user_vb_rf'],
            "comprobante_multiple" => false,
            "cant_comprobante" => 0,
          );
          if (!empty($value['imagen'])) {
            if (validar_url($scheme_host, 'dist/docs/servicio_maquina/comprobante_servicio/', $value['imagen'])) {
              $data_comprobante[] = array(
                "comprobante"       => $value['imagen'],
                "carpeta"           => 'servicio_maquina',
                "subcarpeta"        => 'comprobante_servicio',
                "host"              => $host,
                "ruta_file"         => $scheme_host . 'dist/docs/servicio_maquina/comprobante_servicio/' . $value['imagen'],
              );
            }
          }
        }
      }
    }

    // FACTURAS - SERVICIO EQUIPO ════════════════════════════════════════════════════════════════════════════════════════════════════════
    $filtro_proveedor = "";
    $filtro_fecha = "";
    $filtro_comprobante = "";

    if ($modulo == 'todos' || $modulo == 'servicio_equipo') {

      if (!empty($fecha_1) && !empty($fecha_2)) {
        $filtro_fecha = "AND f.fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
      } else if (!empty($fecha_1)) {
        $filtro_fecha = "AND f.fecha_emision = '$fecha_1'";
      } else if (!empty($fecha_2)) {
        $filtro_fecha = "AND f.fecha_emision = '$fecha_2'";
      }

      if (empty($comprobante)) {
        $filtro_comprobante = "AND f.tipo_comprobante IN ('Factura','Boleta','Nota de Crédito')";
      } else {
        $filtro_comprobante = "AND f.tipo_comprobante = '$comprobante'";
      }

      if (empty($id_proveedor)) {
        $filtro_proveedor = "";
      } else {
        $filtro_proveedor = "AND prov.ruc = '$id_proveedor'";
      }

      $sql2 = "SELECT f.idfactura, f.idproyecto, f.codigo, f.fecha_emision, f.monto, f.subtotal, f.igv,
      f.nota, f.tipo_comprobante,
      CASE
          WHEN f.tipo_comprobante = 'Boleta' THEN 'BV'
          WHEN f.tipo_comprobante = 'Factura' THEN 'FT'
          ELSE 'OTRO'
      END AS tipo_compr,
      mq.nombre, prov.razon_social, prov.tipo_documento, prov.ruc, f.descripcion, f.imagen,
      f.id_user_vb_rf, f.nombre_user_vb_rf, f.imagen_user_vb_rf, f.estado_user_vb_rf
      FROM factura as f, proyecto as p, maquinaria as mq, proveedor as prov
      WHERE f.idmaquinaria=mq.idmaquinaria AND mq.idproveedor=prov.idproveedor AND f.idproyecto=p.idproyecto and f.idproyecto='$idproyecto'
      AND f.estado = '1' AND f.estado_delete = '1' AND mq.tipo = '2' and p.$idempresa_a_cargo AND f.$estado_vb $filtro_proveedor $filtro_comprobante $filtro_fecha
      ORDER BY f.fecha_emision DESC;";
      $maquinaria_equipo =  ejecutarConsultaArray($sql2);

      if ($maquinaria_equipo['status'] == false) {
        return $maquinaria_equipo;
      }

      if (!empty($maquinaria_equipo['data'])) {
        foreach ($maquinaria_equipo['data'] as $key => $value) {
          $data[] = array(
            "idproyecto"        => $value['idproyecto'],
            "idtabla"           => $value['idfactura'],
            "bd_nombre_tabla"   => 'factura',
            "bd_nombre_id_tabla" => 'idfactura',
            "fecha"             => $value['fecha_emision'],
            "tipo_comprobante"  => $value['tipo_compr'],
            "serie_comprobante" => $value['codigo'],
            "proveedor"         => $value['razon_social'],
            "tipo_documento"    => $value['tipo_documento'],
            "ruc"               => $value['ruc'],
            "total"             => $value['monto'],
            "subtotal"          => $value['subtotal'],
            "igv"               => $value['igv'],
            "glosa"             => 'MAQUINARIA',
            "tipo_gravada"      => 'GRAVADA',
            "comprobante"       => $value['imagen'],
            "carpeta"           => 'servicio_equipo',
            "subcarpeta"        => 'comprobante_servicio',
            "ruta"              => 'dist/docs/servicio_equipo/comprobante_servicio/',
            "modulo"            => 'EQUIPO',
            "id_user_vb_rf"     => $value['id_user_vb_rf'],
            "nombre_user_vb_rf" => $value['nombre_user_vb_rf'],
            "imagen_user_vb_rf" => $value['imagen_user_vb_rf'],
            "estado_user_vb_rf" => $value['estado_user_vb_rf'],
            "comprobante_multiple" => false,
            "cant_comprobante" => 0,
          );
          if (!empty($value['imagen'])) {
            if (validar_url($scheme_host, 'dist/docs/servicio_equipo/comprobante_servicio/', $value['imagen'])) {
              $data_comprobante[] = array(
                "comprobante"       => $value['imagen'],
                "carpeta"           => 'servicio_equipo',
                "subcarpeta"        => 'comprobante_servicio',
                "host"              => $host,
                "ruta_file"         => $scheme_host . 'dist/docs/servicio_equipo/comprobante_servicio/' . $value['imagen'],
              );
            }
          }
        }
      }
    }

    // FACTURAS - SUB CONTRATO ═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    $filtro_proveedor = "";
    $filtro_fecha = "";
    $filtro_comprobante = "";

    if ($modulo == 'todos' || $modulo == 'sub_contrato') {

      if (!empty($fecha_1) && !empty($fecha_2)) {
        $filtro_fecha = "AND s.fecha_subcontrato BETWEEN '$fecha_1' AND '$fecha_2'";
      } else if (!empty($fecha_1)) {
        $filtro_fecha = "AND s.fecha_subcontrato = '$fecha_1'";
      } else if (!empty($fecha_2)) {
        $filtro_fecha = "AND s.fecha_subcontrato = '$fecha_2'";
      }

      if (empty($id_proveedor)) {
        $filtro_proveedor = "";
      } else {
        $filtro_proveedor = "AND p.ruc = '$id_proveedor'";
      }

      if (empty($comprobante)) {
        $filtro_comprobante = "AND s.tipo_comprobante IN ('Factura','Boleta','Nota de Crédito')";
      } else {
        $filtro_comprobante = "AND s.tipo_comprobante = '$comprobante'";
      }

      $sql3 = "SELECT s.idsubcontrato, s.idproyecto, s.idproveedor, s.tipo_comprobante, s.numero_comprobante, s.forma_de_pago, 
      s.fecha_subcontrato, s.val_igv, s.subtotal, s.igv, s.costo_parcial, s.descripcion, s.glosa, s.tipo_gravada, s.comprobante, p.razon_social, p.tipo_documento, 
      p.ruc, s.id_user_vb, s.nombre_user_vb, s.imagen_user_vb, s.estado_user_vb,
      s.id_user_vb_rf, s.nombre_user_vb_rf, s.imagen_user_vb_rf, s.estado_user_vb_rf    
      FROM subcontrato AS s, proveedor as p, proyecto AS pry
      WHERE s.idproveedor = p.idproveedor and pry.idproyecto = s.idproyecto and s.estado = '1' AND s.estado_delete = '1' and s.idproyecto='$idproyecto'
      and pry.$idempresa_a_cargo AND s.$estado_vb $filtro_proveedor $filtro_comprobante $filtro_fecha ORDER BY s.fecha_subcontrato DESC;";
      $sub_contrato =  ejecutarConsultaArray($sql3);

      if ($sub_contrato['status'] == false) {
        return $sub_contrato;
      }

      if (!empty($sub_contrato['data'])) {
        foreach ($sub_contrato['data'] as $key => $value) {

          if ($value['tipo_comprobante'] == 'Factura') {
            $tipo_comprob  = 'FT';
          } elseif ($value['tipo_comprobante'] == 'Boleta') {
            $tipo_comprob  = 'BV';
          } elseif ($value['tipo_comprobante'] == 'Nota de Crédito') {
            $tipo_comprob  = 'NC';
          }

          $data[] = array(
            "idproyecto"        => $value['idproyecto'],
            "idtabla"           => $value['idsubcontrato'],
            "bd_nombre_tabla"   => 'subcontrato',
            "bd_nombre_id_tabla" => 'idsubcontrato',
            "fecha"             => $value['fecha_subcontrato'],
            "tipo_comprobante"  => $tipo_comprob, //(empty($value['tipo_comprobante']) ? '' : ($value['tipo_comprobante'] == 'Factura' ? 'FT' : ($value['tipo_comprobante'] == 'Boleta'? 'BV' : ''))) ,
            "serie_comprobante" => $value['numero_comprobante'],
            "proveedor"         => $value['razon_social'],
            "tipo_documento"    => $value['tipo_documento'],
            "ruc"               => $value['ruc'],
            "total"             => $value['costo_parcial'],
            "subtotal"          => $value['subtotal'],
            "igv"               => $value['igv'],
            "glosa"             => $value['glosa'],
            "tipo_gravada"      => $value['tipo_gravada'],
            "comprobante"       => $value['comprobante'],
            "carpeta"           => 'sub_contrato',
            "subcarpeta"        => 'comprobante_subcontrato',
            "ruta"              => 'dist/docs/sub_contrato/comprobante_subcontrato/',
            "modulo"            => 'SUB CONTRATO',
            "id_user_vb_rf"     => $value['id_user_vb_rf'],
            "nombre_user_vb_rf" => $value['nombre_user_vb_rf'],
            "imagen_user_vb_rf" => $value['imagen_user_vb_rf'],
            "estado_user_vb_rf" => $value['estado_user_vb_rf'],
            "comprobante_multiple" => false,
            "cant_comprobante" => 0,
          );

          if (!empty($value['comprobante'])) {
            if (validar_url($scheme_host, 'dist/docs/sub_contrato/comprobante_subcontrato/', $value['comprobante'])) {
              $data_comprobante[] = array(
                "comprobante"    => $value['comprobante'],
                "carpeta"        => 'sub_contrato',
                "subcarpeta"     => 'comprobante_subcontrato',
                "host"           => $host,
                "ruta_file"      => $scheme_host . 'dist/docs/sub_contrato/comprobante_subcontrato/' . $value['comprobante'],
              );
            }
          }
        }
      }
    }

    // FACTURAS - PLANILLA SEGURO ═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    $filtro_proveedor = "";
    $filtro_fecha = "";
    $filtro_comprobante = "";

    if ($modulo == 'todos' || $modulo == 'planilla_seguro') {

      if (!empty($fecha_1) && !empty($fecha_2)) {
        $filtro_fecha = "AND ps.fecha_p_s BETWEEN '$fecha_1' AND '$fecha_2'";
      } else if (!empty($fecha_1)) {
        $filtro_fecha = "AND ps.fecha_p_s = '$fecha_1'";
      } else if (!empty($fecha_2)) {
        $filtro_fecha = "AND ps.fecha_p_s = '$fecha_2'";
      }

      if (empty($id_proveedor)) {
        $filtro_proveedor = "";
      } else {
        $filtro_proveedor = "AND prov.ruc = '$id_proveedor'";
      }

      if (empty($comprobante)) {
        $filtro_comprobante = "AND ps.tipo_comprobante IN ('Factura','Boleta','Nota de Crédito')";
      } else {
        $filtro_comprobante = "AND ps.tipo_comprobante = '$comprobante'";
      }

      $sql3 = "SELECT ps.idplanilla_seguro, ps.idproyecto, ps.tipo_comprobante, ps.numero_comprobante, ps.forma_de_pago, 
      ps.fecha_p_s, ps.subtotal, ps.igv, ps.costo_parcial, ps.descripcion, ps.val_igv, ps.tipo_gravada, ps.comprobante, ps.glosa,
      ps.id_user_vb, ps.nombre_user_vb, ps.imagen_user_vb, ps.estado_user_vb, prov.razon_social, prov.tipo_documento, prov.ruc,
      ps.id_user_vb_rf, ps.nombre_user_vb_rf, ps.imagen_user_vb_rf, ps.estado_user_vb_rf
      FROM planilla_seguro as ps, proyecto as p, proveedor as prov 
      WHERE ps.idproyecto='$idproyecto' and ps.idproyecto = p.idproyecto and ps.idproveedor = prov.idproveedor and ps.estado ='1' and ps.estado_delete = '1' 
      and p.$idempresa_a_cargo AND ps.$estado_vb
      $filtro_proveedor $filtro_comprobante $filtro_fecha ORDER BY ps.fecha_p_s DESC;";
      $planilla_seguro =  ejecutarConsultaArray($sql3);

      if ($planilla_seguro['status'] == false) {
        return $planilla_seguro;
      }

      if (!empty($planilla_seguro['data'])) {
        foreach ($planilla_seguro['data'] as $key => $value) {

          if ($value['tipo_comprobante'] == 'Factura') {
            $tipo_comprob  = 'FT';
          } elseif ($value['tipo_comprobante'] == 'Boleta') {
            $tipo_comprob  = 'BV';
          } elseif ($value['tipo_comprobante'] == 'Nota de Crédito') {
            $tipo_comprob  = 'NC';
          }

          $data[] = array(
            "idproyecto"        => $value['idproyecto'],
            "idtabla"           => $value['idplanilla_seguro'],
            "bd_nombre_tabla"   => 'planilla_seguro',
            "bd_nombre_id_tabla" => 'idplanilla_seguro',
            "fecha"             => $value['fecha_p_s'],
            "tipo_comprobante"  => $tipo_comprob, //(empty($value['tipo_comprobante']) ? '' : ($value['tipo_comprobante'] == 'Factura' ? 'FT' : ($value['tipo_comprobante'] == 'Boleta'? 'BV' : ''))) ,
            "serie_comprobante" => $value['numero_comprobante'],
            "proveedor"         => $value['razon_social'],
            "tipo_documento"    => $value['tipo_documento'],
            "ruc"               => $value['ruc'],
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
            "id_user_vb_rf"     => $value['id_user_vb_rf'],
            "nombre_user_vb_rf" => $value['nombre_user_vb_rf'],
            "imagen_user_vb_rf" => $value['imagen_user_vb_rf'],
            "estado_user_vb_rf" => $value['estado_user_vb_rf'],
            "comprobante_multiple" => false,
            "cant_comprobante" => 0,
          );
          if (!empty($value['comprobante'])) {
            if (validar_url($scheme_host, 'dist/docs/planilla_seguro/comprobante/', $value['comprobante'])) {
              $data_comprobante[] = array(
                "comprobante"       => $value['comprobante'],
                "carpeta"           => 'planilla_seguro',
                "subcarpeta"        => 'comprobante',
                "host"              => $host,
                "ruta_file"         => $scheme_host . 'dist/docs/planilla_seguro/comprobante/' . $value['comprobante'],
              );
            }
          }
        }
      }
    }

    // FACTURAS - OTRO GASTO ═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    $filtro_proveedor = "";
    $filtro_fecha = "";
    $filtro_comprobante = "";

    if ($modulo == 'todos' || $modulo == 'otro_gasto') {

      if (!empty($fecha_1) && !empty($fecha_2)) {
        $filtro_fecha = "AND og.fecha_g BETWEEN '$fecha_1' AND '$fecha_2'";
      } else if (!empty($fecha_1)) {
        $filtro_fecha = "AND og.fecha_g = '$fecha_1'";
      } else if (!empty($fecha_2)) {
        $filtro_fecha = "AND og.fecha_g = '$fecha_2'";
      }

      if (empty($id_proveedor)) {
        $filtro_proveedor = "";
      } else {
        $filtro_proveedor = "AND og.ruc = '$id_proveedor'";
      }

      if (empty($comprobante)) {
        $filtro_comprobante = "AND og.tipo_comprobante IN ('Factura','Boleta','Nota de Crédito')";
      } else {
        $filtro_comprobante = "AND og.tipo_comprobante = '$comprobante'";
      }

      $sql3 = "SELECT og.idproyecto, og.idotro_gasto, og.razon_social, og.ruc, og.tipo_comprobante, og.numero_comprobante, og.fecha_g, 
      og.costo_parcial, og.subtotal, og.igv, og.glosa, og.comprobante, og.tipo_gravada,
      og.id_user_vb_rf, og.nombre_user_vb_rf, og.imagen_user_vb_rf, og.estado_user_vb_rf
      FROM otro_gasto as og, proyecto AS pry 
      WHERE og.idproyecto='$idproyecto' and pry.idproyecto = og.idproyecto and og.estado = '1' AND og.estado_delete = '1'
      and pry.$idempresa_a_cargo AND og.$estado_vb $filtro_proveedor $filtro_comprobante $filtro_fecha ORDER BY og.fecha_g DESC;";
      $otro_gasto =  ejecutarConsultaArray($sql3);

      if ($otro_gasto['status'] == false) {
        return $otro_gasto;
      }

      if (!empty($otro_gasto['data'])) {
        foreach ($otro_gasto['data'] as $key => $value) {

          if ($value['tipo_comprobante'] == 'Factura') {
            $tipo_comprob  = 'FT';
          } elseif ($value['tipo_comprobante'] == 'Boleta') {
            $tipo_comprob  = 'BV';
          } elseif ($value['tipo_comprobante'] == 'Nota de Crédito') {
            $tipo_comprob  = 'NC';
          }

          $data[] = array(
            "idproyecto"        => $value['idproyecto'],
            "idtabla"           => $value['idotro_gasto'],
            "bd_nombre_tabla"   => 'otro_gasto',
            "bd_nombre_id_tabla" => 'idotro_gasto',
            "fecha"             => $value['fecha_g'],
            "tipo_comprobante"  => $tipo_comprob, // (empty($value['tipo_comprobante'])) ? '' : $retVal3 = ($value['tipo_comprobante'] == 'Factura') ? 'FT' : $retVal4 = ($value['tipo_comprobante'] == 'Boleta') ? 'BV' : '' ,
            "serie_comprobante" => $value['numero_comprobante'],
            "proveedor"         => $value['razon_social'],
            "tipo_documento"    => 'RUC',
            "ruc"               => $value['ruc'],
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
            "id_user_vb_rf"     => $value['id_user_vb_rf'],
            "nombre_user_vb_rf" => $value['nombre_user_vb_rf'],
            "imagen_user_vb_rf" => $value['imagen_user_vb_rf'],
            "estado_user_vb_rf" => $value['estado_user_vb_rf'],
            "comprobante_multiple" => false,
            "cant_comprobante" => 0,
          );
          if (!empty($value['comprobante'])) {
            if (validar_url($scheme_host, 'dist/docs/otro_gasto/comprobante/', $value['comprobante'])) {
              $data_comprobante[] = array(
                "comprobante"       => $value['comprobante'],
                "carpeta"           => 'otro_gasto',
                "subcarpeta"        => 'comprobante',
                "host"              => $host,
                "ruta_file"         => $scheme_host . 'dist/docs/otro_gasto/comprobante/' . $value['comprobante'],
              );
            }
          }
        }
      }
    }

    // FACTURAS - TRANSPORTE ═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    $filtro_proveedor = "";
    $filtro_fecha = "";
    $filtro_comprobante = "";

    if ($modulo == 'todos' || $modulo == 'transporte') {

      if (!empty($fecha_1) && !empty($fecha_2)) {
        $filtro_fecha = "AND t.fecha_viaje BETWEEN '$fecha_1' AND '$fecha_2'";
      } else if (!empty($fecha_1)) {
        $filtro_fecha = "AND t.fecha_viaje = '$fecha_1'";
      } else if (!empty($fecha_2)) {
        $filtro_fecha = "AND t.fecha_viaje = '$fecha_2'";
      }

      if (empty($id_proveedor)) {
        $filtro_proveedor = "";
      } else {
        $filtro_proveedor = "AND p.ruc = '$id_proveedor'";
      }

      if (empty($comprobante)) {
        $filtro_comprobante = "AND  t.tipo_comprobante IN ('Factura','Boleta','Nota de Crédito')";
      } else {
        $filtro_comprobante = "AND t.tipo_comprobante = '$comprobante'";
      }

      $sql4 = "SELECT t.idtransporte, t.idproyecto, p.razon_social, p.tipo_documento, p.ruc, t.tipo_comprobante, t.numero_comprobante, 
      t.fecha_viaje, t.precio_parcial, t.subtotal, t.igv,  t.comprobante , t.glosa , t.tipo_gravada,
      t.id_user_vb_rf, t.nombre_user_vb_rf, t.imagen_user_vb_rf, t.estado_user_vb_rf
      FROM transporte AS t, proveedor AS p, proyecto AS pry 
      WHERE t.idproyecto='$idproyecto' and t.idproveedor = p.idproveedor and pry.idproyecto = t.idproyecto AND t.estado = '1' AND t.estado_delete = '1' 
      and pry.$idempresa_a_cargo AND t.$estado_vb 
      $filtro_proveedor $filtro_comprobante $filtro_fecha ORDER BY t.fecha_viaje DESC;";
      $transporte =  ejecutarConsultaArray($sql4);

      if ($transporte['status'] == false) {
        return $transporte;
      }

      if (!empty($transporte['data'])) {
        foreach ($transporte['data'] as $key => $value) {

          if ($value['tipo_comprobante'] == 'Factura') {
            $tipo_comprob  = 'FT';
          } elseif ($value['tipo_comprobante'] == 'Boleta') {
            $tipo_comprob  = 'BV';
          } elseif ($value['tipo_comprobante'] == 'Nota de Crédito') {
            $tipo_comprob  = 'NC';
          }

          $data[] = array(
            "idproyecto"        => $value['idproyecto'],
            "idtabla"           => $value['idtransporte'],
            "bd_nombre_tabla"   => 'transporte',
            "bd_nombre_id_tabla" => 'idtransporte',
            "fecha"             => $value['fecha_viaje'],
            "tipo_comprobante"  => $tipo_comprob, // (empty($value['tipo_comprobante'])) ? '' : $retVal5 = ($value['tipo_comprobante'] == 'Factura') ? 'FT' : $retVal6 = ($value['tipo_comprobante'] == 'Boleta') ? 'BV' : '' ,
            "serie_comprobante" => $value['numero_comprobante'],
            "proveedor"         => $value['razon_social'],
            "tipo_documento"    => $value['tipo_documento'],
            "ruc"               => $value['ruc'],
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
            "id_user_vb_rf"     => $value['id_user_vb_rf'],
            "nombre_user_vb_rf" => $value['nombre_user_vb_rf'],
            "imagen_user_vb_rf" => $value['imagen_user_vb_rf'],
            "estado_user_vb_rf" => $value['estado_user_vb_rf'],
            "comprobante_multiple" => false,
            "cant_comprobante" => 0,
          );
          if (!empty($value['comprobante'])) {
            if (validar_url($scheme_host, 'dist/docs/transporte/comprobante/', $value['comprobante'])) {
              $data_comprobante[] = array(
                "comprobante"       => $value['comprobante'],
                "carpeta"           => 'transporte',
                "subcarpeta"        => 'comprobante',
                "host"              => $host,
                "ruta_file"         => $scheme_host . 'dist/docs/transporte/comprobante/' . $value['comprobante'],
              );
            }
          }
        }
      }
    }

    // FACTURAS - HOSPEDAJE ═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    $filtro_proveedor = "";
    $filtro_fecha = "";
    $filtro_comprobante = "";

    if ($modulo == 'todos' || $modulo == 'hospedaje') {

      if (!empty($fecha_1) && !empty($fecha_2)) {
        $filtro_fecha = "AND h.fecha_comprobante BETWEEN '$fecha_1' AND '$fecha_2'";
      } else if (!empty($fecha_1)) {
        $filtro_fecha = "AND h.fecha_comprobante = '$fecha_1'";
      } else if (!empty($fecha_2)) {
        $filtro_fecha = "AND h.fecha_comprobante = '$fecha_2'";
      }

      if (empty($id_proveedor)) {
        $filtro_proveedor = "";
      } else {
        $filtro_proveedor = "AND ruc = '$id_proveedor'";
      }

      if (empty($comprobante)) {
        $filtro_comprobante = "AND h.tipo_comprobante IN ('Factura','Boleta','Nota de Crédito')";
      } else {
        $filtro_comprobante = "AND h.tipo_comprobante = '$comprobante'";
      }

      $sql5 = "SELECT h.idhospedaje, h.idproyecto, h.razon_social, h.ruc, h.fecha_comprobante, h.tipo_comprobante, h.numero_comprobante, h.subtotal, h.igv, 
      precio_parcial, h.glosa, h.comprobante, h.tipo_gravada,
      id_user_vb_rf, h.nombre_user_vb_rf, h.imagen_user_vb_rf, h.estado_user_vb_rf
      FROM hospedaje as h, proyecto AS pry 
      WHERE h.idproyecto='$idproyecto' and pry.idproyecto = h.idproyecto and h.estado = '1' AND h.estado_delete = '1' 
      and pry.$idempresa_a_cargo AND h.$estado_vb $filtro_proveedor $filtro_comprobante $filtro_fecha
      ORDER BY h.fecha_comprobante DESC;";
      $hospedaje =  ejecutarConsultaArray($sql5);

      if ($hospedaje['status'] == false) {
        return $hospedaje;
      }

      if (!empty($hospedaje['data'])) {
        foreach ($hospedaje['data'] as $key => $value) {

          if ($value['tipo_comprobante'] == 'Factura') {
            $tipo_comprob  = 'FT';
          } elseif ($value['tipo_comprobante'] == 'Boleta') {
            $tipo_comprob  = 'BV';
          } elseif ($value['tipo_comprobante'] == 'Nota de Crédito') {
            $tipo_comprob  = 'NC';
          }

          $data[] = array(
            "idproyecto"        => $value['idproyecto'],
            "idtabla"           => $value['idhospedaje'],
            "bd_nombre_tabla"   => 'hospedaje',
            "bd_nombre_id_tabla" => 'idhospedaje',
            "fecha"             => $value['fecha_comprobante'],
            "tipo_comprobante"  => $tipo_comprob, //(empty($value['tipo_comprobante'])) ? '' : $retVal5 = ($value['tipo_comprobante'] == 'Factura') ? 'FT' : $retVal6 = ($value['tipo_comprobante'] == 'Boleta') ? 'BV' : '' ,
            "serie_comprobante" => $value['numero_comprobante'],
            "proveedor"         => $value['razon_social'],
            "tipo_documento"    => 'RUC',
            "ruc"               => $value['ruc'],
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
            "id_user_vb_rf"     => $value['id_user_vb_rf'],
            "nombre_user_vb_rf" => $value['nombre_user_vb_rf'],
            "imagen_user_vb_rf" => $value['imagen_user_vb_rf'],
            "estado_user_vb_rf" => $value['estado_user_vb_rf'],
            "comprobante_multiple" => false,
            "cant_comprobante" => 0,
          );
          if (!empty($value['comprobante'])) {
            if (validar_url($scheme_host, 'dist/docs/hospedaje/comprobante/', $value['comprobante'])) {
              $data_comprobante[] = array(
                "comprobante"       => $value['comprobante'],
                "carpeta"           => 'hospedaje',
                "subcarpeta"        => 'comprobante',
                "host"              => $host,
                "ruta_file"         => $scheme_host . 'dist/docs/hospedaje/comprobante/' . $value['comprobante'],
              );
            }
          }
        }
      }
    }

    // FACTURAS - PENSION ═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    $filtro_proveedor = "";
    $filtro_fecha = "";
    $filtro_comprobante = "";

    if ($modulo == 'todos' || $modulo == 'pension') {

      if (!empty($fecha_1) && !empty($fecha_2)) {
        $filtro_fecha = "AND dp.fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
      } else if (!empty($fecha_1)) {
        $filtro_fecha = "AND dp.fecha_emision = '$fecha_1'";
      } else if (!empty($fecha_2)) {
        $filtro_fecha = "AND dp.fecha_emision = '$fecha_2'";
      }

      if (empty($id_proveedor)) {
        $filtro_proveedor = "";
      } else {
        $filtro_proveedor = "AND prov.ruc = '$id_proveedor'";
      }

      if (empty($comprobante)) {
        $filtro_comprobante = "AND dp.tipo_comprobante IN ('Factura','Boleta','Nota de Crédito')";
      } else {
        $filtro_comprobante = "AND dp.tipo_comprobante = '$comprobante'";
      }

      $sql6 = "SELECT dp.iddetalle_pension, dp.idpension, dp.fecha_inicial, dp.fecha_final, dp.cantidad_persona, dp.subtotal, dp.igv, 
      dp.val_igv, dp.precio_parcial, dp.forma_pago, dp.tipo_comprobante, dp.fecha_emision, dp.tipo_gravada, dp.glosa, dp.numero_comprobante, dp.descripcion, 
      dp.comprobante, dp.id_user_vb, dp.nombre_user_vb, dp.imagen_user_vb, dp.estado_user_vb, prov.razon_social, prov.tipo_documento, prov.ruc, p.idproyecto,
      dp.id_user_vb_rf, dp.nombre_user_vb_rf, dp.imagen_user_vb_rf, dp.estado_user_vb_rf
      FROM detalle_pension as dp, pension as p, proveedor as prov, proyecto AS pry 
      WHERE p.idproyecto='$idproyecto' and dp.idpension = p.idpension and pry.idproyecto = p.idproyecto AND prov.idproveedor = p.idproveedor  AND p.estado = '1' AND p.estado_delete = '1' AND dp.estado = '1' AND dp.estado_delete = '1' 
      and pry.$idempresa_a_cargo AND dp.$estado_vb
      $filtro_proveedor $filtro_comprobante $filtro_fecha
      ORDER BY dp.fecha_emision DESC;";
      $factura_pension =  ejecutarConsultaArray($sql6);

      if ($factura_pension['status'] == false) {
        return $factura_pension;
      }

      if (!empty($factura_pension['data'])) {
        foreach ($factura_pension['data'] as $key => $value) {

          if ($value['tipo_comprobante'] == 'Factura') {
            $tipo_comprob  = 'FT';
          } elseif ($value['tipo_comprobante'] == 'Boleta') {
            $tipo_comprob  = 'BV';
          } elseif ($value['tipo_comprobante'] == 'Nota de Crédito') {
            $tipo_comprob  = 'NC';
          }

          $data[] = array(
            "idproyecto"        => $value['idproyecto'],
            "idtabla"           => $value['iddetalle_pension'],
            "bd_nombre_tabla"   => 'detalle_pension',
            "bd_nombre_id_tabla" => 'iddetalle_pension',
            "fecha"             => $value['fecha_emision'],
            "tipo_comprobante"  => $tipo_comprob, //(empty($value['tipo_comprobante'])) ? '' : $retVal5 = ($value['tipo_comprobante'] == 'Factura') ? 'FT' : $retVal6 = ($value['tipo_comprobante'] == 'Boleta') ? 'BV' : '' ,
            "serie_comprobante" => $value['numero_comprobante'],
            "proveedor"         => $value['razon_social'],
            "tipo_documento"    => $value['tipo_documento'],
            "ruc"               => $value['ruc'],
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
            "id_user_vb_rf"     => $value['id_user_vb_rf'],
            "nombre_user_vb_rf" => $value['nombre_user_vb_rf'],
            "imagen_user_vb_rf" => $value['imagen_user_vb_rf'],
            "estado_user_vb_rf" => $value['estado_user_vb_rf'],
            "comprobante_multiple" => false,
            "cant_comprobante" => 0,
          );
          if (!empty($value['comprobante'])) {
            if (validar_url($scheme_host, 'dist/docs/pension/comprobante/', $value['comprobante'])) {
              $data_comprobante[] = array(
                "comprobante"       => $value['comprobante'],
                "carpeta"           => 'pension',
                "subcarpeta"        => 'comprobante',
                "host"              => $host,
                "ruta_file"         => $scheme_host . 'dist/docs/pension/comprobante/' . $value['comprobante'],
              );
            }
          }
        }
      }
    }

    // FACTURAS - BREACK ═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    $filtro_proveedor = "";
    $filtro_fecha = "";
    $filtro_comprobante = "";

    if ($modulo == 'todos' || $modulo == 'breack') {

      if (!empty($fecha_1) && !empty($fecha_2)) {
        $filtro_fecha = "AND fb.fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
      } else if (!empty($fecha_1)) {
        $filtro_fecha = "AND fb.fecha_emision = '$fecha_1'";
      } else if (!empty($fecha_2)) {
        $filtro_fecha = "AND fb.fecha_emision = '$fecha_2'";
      }

      if (empty($id_proveedor)) {
        $filtro_proveedor = "";
      } else {
        $filtro_proveedor = "AND fb.ruc = '$id_proveedor'";
      }

      if (empty($comprobante)) {
        $filtro_comprobante = "AND fb.tipo_comprobante IN ('Factura','Boleta','Nota de Crédito')";
      } else {
        $filtro_comprobante = "AND fb.tipo_comprobante = '$comprobante'";
      }

      $sql7 = "SELECT sb.idproyecto, fb.idfactura_break, fb.fecha_emision, fb.tipo_comprobante, fb.nro_comprobante, fb.razon_social, fb.ruc, 
      fb.monto, fb.subtotal, fb.igv, fb.glosa,  fb.comprobante, fb.tipo_gravada,
      fb.id_user_vb_rf, fb.nombre_user_vb_rf, fb.imagen_user_vb_rf, fb.estado_user_vb_rf
      FROM factura_break as fb, semana_break as sb, proyecto AS pry 
      WHERE sb.idproyecto='$idproyecto' and fb.idsemana_break = sb.idsemana_break  and  pry.idproyecto = sb.idproyecto
      AND fb.estado = '1' AND fb.estado_delete = '1' AND sb.estado = '1' AND sb.estado_delete = '1' 
      and pry.$idempresa_a_cargo AND fb.$estado_vb
      $filtro_proveedor $filtro_comprobante $filtro_fecha ORDER BY fb.fecha_emision DESC;";
      $factura_break =  ejecutarConsultaArray($sql7);

      if ($factura_break['status'] == false) {
        return $factura_break;
      }

      if (!empty($factura_break['data'])) {
        foreach ($factura_break['data'] as $key => $value) {

          if ($value['tipo_comprobante'] == 'Factura') {
            $tipo_comprob  = 'FT';
          } elseif ($value['tipo_comprobante'] == 'Boleta') {
            $tipo_comprob  = 'BV';
          } elseif ($value['tipo_comprobante'] == 'Nota de Crédito') {
            $tipo_comprob  = 'NC';
          }

          $data[] = array(
            "idproyecto"        => $value['idproyecto'],
            "idtabla"           => $value['idfactura_break'],
            "bd_nombre_tabla"   => 'factura_break',
            "bd_nombre_id_tabla" => 'idfactura_break',
            "fecha"             => $value['fecha_emision'],
            "tipo_comprobante"  => $tipo_comprob, //(empty($value['tipo_comprobante'])) ? '' : $retVal5 = ($value['tipo_comprobante'] == 'Factura') ? 'FT' : $retVal6 = ($value['tipo_comprobante'] == 'Boleta') ? 'BV' : '' ,
            "serie_comprobante" => $value['nro_comprobante'],
            "proveedor"         => $value['razon_social'],
            "tipo_documento"    => 'RUC',
            "ruc"               => $value['ruc'],
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
            "id_user_vb_rf"     => $value['id_user_vb_rf'],
            "nombre_user_vb_rf" => $value['nombre_user_vb_rf'],
            "imagen_user_vb_rf" => $value['imagen_user_vb_rf'],
            "estado_user_vb_rf" => $value['estado_user_vb_rf'],
            "comprobante_multiple" => false,
            "cant_comprobante" => 0,
          );
          if (!empty($value['comprobante'])) {
            if (validar_url($scheme_host, 'dist/docs/break/comprobante/', $value['comprobante'])) {
              $data_comprobante[] = array(
                "comprobante"       => $value['comprobante'],
                "carpeta"           => 'break',
                "subcarpeta"        => 'comprobante',
                "host"              => $host,
                "ruta_file"         => $scheme_host . 'dist/docs/break/comprobante/' . $value['comprobante'],
              );
            }
          }
        }
      }
    }

    // FACTURAS - COMIDA EXTRA ═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    $filtro_proveedor = "";
    $filtro_fecha = "";
    $filtro_comprobante = "";

    if ($modulo == 'todos' || $modulo == 'comida_extra') {

      if (!empty($fecha_1) && !empty($fecha_2)) {
        $filtro_fecha = "AND ce.fecha_comida BETWEEN '$fecha_1' AND '$fecha_2'";
      } else if (!empty($fecha_1)) {
        $filtro_fecha = "AND ce.fecha_comida = '$fecha_1'";
      } else if (!empty($fecha_2)) {
        $filtro_fecha = "AND ce.fecha_comida = '$fecha_2'";
      }

      if (empty($id_proveedor)) {
        $filtro_proveedor = "";
      } else {
        $filtro_proveedor = "AND ce.ruc = '$id_proveedor'";
      }

      if (empty($comprobante)) {
        $filtro_comprobante = "AND ce.tipo_comprobante IN ('Factura','Boleta','Nota de Crédito')";
      } else {
        $filtro_comprobante = "AND ce.tipo_comprobante = '$comprobante'";
      }

      $sql8 = "SELECT ce.idproyecto, ce.idcomida_extra, ce.fecha_comida, ce.tipo_comprobante, ce.numero_comprobante, ce.razon_social, ce.ruc,
      ce.costo_parcial, ce.subtotal, ce.igv, ce.glosa, ce.comprobante, ce.tipo_gravada,
      ce.id_user_vb_rf, ce.nombre_user_vb_rf, ce.imagen_user_vb_rf, ce.estado_user_vb_rf
      FROM comida_extra as ce, proyecto AS pry 
      WHERE ce.idproyecto='$idproyecto' and pry.idproyecto = ce.idproyecto and ce.estado = '1' AND ce.estado_delete = '1' 
      and pry.$idempresa_a_cargo AND ce.$estado_vb $filtro_proveedor $filtro_comprobante $filtro_fecha
      ORDER BY ce.fecha_comida DESC;";
      $comida_extra =  ejecutarConsultaArray($sql8);

      if ($comida_extra['status'] == false) {
        return $comida_extra;
      }

      if (!empty($comida_extra['data'])) {
        foreach ($comida_extra['data'] as $key => $value) {

          if ($value['tipo_comprobante'] == 'Factura') {
            $tipo_comprob  = 'FT';
          } elseif ($value['tipo_comprobante'] == 'Boleta') {
            $tipo_comprob  = 'BV';
          } elseif ($value['tipo_comprobante'] == 'Nota de Crédito') {
            $tipo_comprob  = 'NC';
          }

          $data[] = array(
            "idproyecto"        => $value['idproyecto'],
            "idtabla"           => $value['idcomida_extra'],
            "bd_nombre_tabla"   => 'comida_extra',
            "bd_nombre_id_tabla" => 'idcomida_extra',
            "fecha"             => $value['fecha_comida'],
            "tipo_comprobante"  => $tipo_comprob, //(empty($value['tipo_comprobante'])) ? '' : $retVal5 = ($value['tipo_comprobante'] == 'Factura') ? 'FT' : $retVal6 = ($value['tipo_comprobante'] == 'Boleta') ? 'BV' : '' ,
            "serie_comprobante" => $value['numero_comprobante'],
            "proveedor"         => $value['razon_social'],
            "tipo_documento"    => 'RUC',
            "ruc"               => $value['ruc'],
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
            "id_user_vb_rf"     => $value['id_user_vb_rf'],
            "nombre_user_vb_rf" => $value['nombre_user_vb_rf'],
            "imagen_user_vb_rf" => $value['imagen_user_vb_rf'],
            "estado_user_vb_rf" => $value['estado_user_vb_rf'],
            "comprobante_multiple" => false,
            "cant_comprobante"  => 0,
          );
          if (!empty($value['comprobante'])) {
            if (validar_url($scheme_host, 'dist/docs/comida_extra/comprobante/', $value['comprobante'])) {
              $data_comprobante[] = array(
                "comprobante"       => $value['comprobante'],
                "carpeta"           => 'comida_extra',
                "subcarpeta"        => 'comprobante',
                "host"              => $host,
                "ruta_file"         => $scheme_host . 'dist/docs/comida_extra/comprobante/' . $value['comprobante'],
              );
            }
          }
        }
      }
    }

    // FACTURAS - OTRA FACTURA-PROYECTO ═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    $filtro_proveedor = "";
    $filtro_fecha = "";
    $filtro_comprobante = "";

    if ($modulo == 'todos' || $modulo == 'otra_factura') {

      if (!empty($fecha_1) && !empty($fecha_2)) {
        $filtro_fecha = "AND ofp.fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
      } else if (!empty($fecha_1)) {
        $filtro_fecha = "AND ofp.fecha_emision = '$fecha_1'";
      } else if (!empty($fecha_2)) {
        $filtro_fecha = "AND ofp.fecha_emision = '$fecha_2'";
      }

      if (empty($id_proveedor)) {
        $filtro_proveedor = "";
      } else {
        $filtro_proveedor = "AND p.ruc = '$id_proveedor'";
      }

      if (empty($comprobante)) {
        $filtro_comprobante = "AND ofp.tipo_comprobante IN ('Factura','Boleta','Nota de Crédito')";
      } else {
        $filtro_comprobante = "AND ofp.tipo_comprobante = '$comprobante'";
      }

      $sql9 = "SELECT ofp.idotra_factura_proyecto, ofp.idproyecto, ofp.fecha_emision, ofp.tipo_comprobante, ofp.numero_comprobante, p.razon_social, p.tipo_documento, p.ruc,
      ofp.costo_parcial, ofp.subtotal, ofp.igv, ofp.glosa, ofp.comprobante , ofp.tipo_gravada,
      ofp.id_user_vb_rf, ofp.nombre_user_vb_rf, ofp.imagen_user_vb_rf, ofp.estado_user_vb_rf
      FROM otra_factura_proyecto AS ofp, proveedor p, empresa_a_cargo as ec, proyecto as pry
      WHERE ofp.idproyecto='$idproyecto' and pry.idproyecto=ofp.idproyecto  and ofp.idempresa_a_cargo = ec.idempresa_a_cargo and ofp.idproveedor = p.idproveedor AND ofp.estado = '1' AND ofp.estado_delete = '1' 
      and ec.$idempresa_a_cargo AND ofp.$estado_vb
      $filtro_proveedor $filtro_comprobante $filtro_fecha ORDER BY ofp.fecha_emision DESC;";
      $otra_factura =  ejecutarConsultaArray($sql9);

      if ($otra_factura['status'] == false) {
        return $otra_factura;
      }

      if (!empty($otra_factura['data'])) {
        foreach ($otra_factura['data'] as $key => $value) {

          if ($value['tipo_comprobante'] == 'Factura') {
            $tipo_comprob  = 'FT';
          } elseif ($value['tipo_comprobante'] == 'Boleta') {
            $tipo_comprob  = 'BV';
          } elseif ($value['tipo_comprobante'] == 'Nota de Crédito') {
            $tipo_comprob  = 'NC';
          }

          $data[] = array(
            "idproyecto"        => $value['idproyecto'],
            "idtabla"           => $value['idotra_factura_proyecto'],
            "bd_nombre_tabla"   => 'otra_factura_proyecto',
            "bd_nombre_id_tabla" => 'idotra_factura_proyecto',
            "fecha"             => $value['fecha_emision'],
            "tipo_comprobante"  => $tipo_comprob, //(empty($value['tipo_comprobante'])) ? '' : $retVal5 = ($value['tipo_comprobante'] == 'Factura') ? 'FT' : $retVal6 = ($value['tipo_comprobante'] == 'Boleta') ? 'BV' : '' ,
            "serie_comprobante" => $value['numero_comprobante'],
            "proveedor"         => $value['razon_social'],
            "tipo_documento"    => $value['tipo_documento'],
            "ruc"               => $value['ruc'],
            "total"             => $value['costo_parcial'],
            "igv"               => $value['igv'],
            "subtotal"          => $value['subtotal'],
            "glosa"             => $value['glosa'],
            "tipo_gravada"      => $value['tipo_gravada'],
            "comprobante"       => $value['comprobante'],
            "carpeta"           => 'otra_factura',
            "subcarpeta"        => 'comprobante',
            "ruta"              => 'dist/docs/otra_factura/comprobante/',
            "modulo"              => 'OTRA FACTURA',
            "id_user_vb_rf"     => $value['id_user_vb_rf'],
            "nombre_user_vb_rf" => $value['nombre_user_vb_rf'],
            "imagen_user_vb_rf" => $value['imagen_user_vb_rf'],
            "estado_user_vb_rf" => $value['estado_user_vb_rf'],
            "comprobante_multiple" => false,
            "cant_comprobante"  => 0,
          );
          if (!empty($value['comprobante'])) {
            if (validar_url($scheme_host, 'dist/docs/otra_factura/comprobante/', $value['comprobante'])) {
              $data_comprobante[] = array(
                "comprobante"       => $value['comprobante'],
                "carpeta"           => 'otra_factura',
                "subcarpeta"        => 'comprobante',
                "host"              => $host,
                "ruta_file"         => $scheme_host . 'dist/docs/otra_factura/comprobante/' . $value['comprobante'],
              );
            }
          }
        }
      }
    }

    $retorno = array(
      "status" => true,
      "message" => 'todo oka',
      "data" => [
        "datos"             => $data,
        "data_comprobante"  => $data_comprobante,
      ]
    );

    return $retorno;
  }

  public function suma_totales($idproyecto, $fecha_1, $fecha_2, $id_proveedor, $comprobante, $visto_bueno, $modulo)
  {

    $data = array();
    $total = 0;
    $subtotal = 0;
    $igv = 0;
    $estado_vb  = (empty($visto_bueno) ? "estado_user_vb_rf IN ('0','1')" : "estado_user_vb_rf =$visto_bueno");

    // SUMAS TOTALES - COMPRA --------------------------------------------------------------------------------
    $filtro_proveedor = "";
    $filtro_comprobante = "";
    $filtro_fecha = "";

    if ($modulo == 'todos' || $modulo == 'compra_insumo') {

      if (!empty($fecha_1) && !empty($fecha_2)) {
        $filtro_fecha = "AND cpp.fecha_compra BETWEEN '$fecha_1' AND '$fecha_2'";
      } else if (!empty($fecha_1)) {
        $filtro_fecha = "AND cpp.fecha_compra = '$fecha_1'";
      } else if (!empty($fecha_2)) {
        $filtro_fecha = "AND cpp.fecha_compra = '$fecha_2'";
      }

      if (empty($id_proveedor)) {
        $filtro_proveedor = "";
      } else {
        $filtro_proveedor = "AND p.ruc = '$id_proveedor'";
      }

      if (empty($comprobante)) {
        $filtro_comprobante = "AND cpp.tipo_comprobante IN ('Factura','Boleta','Nota de Crédito')";
      } else {
        $filtro_comprobante = "AND cpp.tipo_comprobante = '$comprobante'";
      }

      $sql = "SELECT SUM(cpp.total) AS total, SUM(cpp.subtotal) AS subtotal, SUM(cpp.igv) AS igv
      FROM compra_por_proyecto AS cpp, proveedor p
      WHERE cpp.idproveedor = p.idproveedor AND cpp.estado = '1' AND cpp.estado_delete = '1' AND cpp.$estado_vb $filtro_proveedor $filtro_comprobante $filtro_fecha ;";
      $compra = ejecutarConsultaSimpleFila($sql);

      if ($compra['status'] == false) {
        return $compra;
      }

      $total    += (empty($compra['data'])) ? 0 : (empty($compra['data']['total']) ? 0 : floatval($compra['data']['total']));
      $subtotal += (empty($compra['data'])) ? 0 : (empty($compra['data']['subtotal']) ? 0 : floatval($compra['data']['subtotal']));
      $igv      += (empty($compra['data'])) ? 0 : (empty($compra['data']['igv']) ? 0 : floatval($compra['data']['igv']));
    }

    // SUMAS TOTALES - MAQUINARIA --------------------------------------------------------------------------------
    $filtro_proveedor = "";
    $filtro_comprobante = "";
    $filtro_fecha = "";

    if ($modulo == 'todos' || $modulo == 'servicio_maquina') {

      if (!empty($fecha_1) && !empty($fecha_2)) {
        $filtro_fecha = "AND f.fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
      } else if (!empty($fecha_1)) {
        $filtro_fecha = "AND f.fecha_emision = '$fecha_1'";
      } else if (!empty($fecha_2)) {
        $filtro_fecha = "AND f.fecha_emision = '$fecha_2'";
      }

      if (empty($id_proveedor)) {
        $filtro_proveedor = "";
      } else {
        $filtro_proveedor = "AND prov.ruc = '$id_proveedor'";
      }

      if (empty($comprobante)) {
        $filtro_comprobante = "AND f.tipo_comprobante IN ('Factura','Boleta','Nota de Crédito')";
      } else {
        $filtro_comprobante = "AND f.tipo_comprobante = '$comprobante'";
      }

      $sql2 = "SELECT SUM(f.monto) AS total , SUM(f.subtotal) AS subtotal, SUM(f.igv) AS igv
      FROM factura as f, proyecto as p, maquinaria as mq, proveedor as prov
      WHERE f.idmaquinaria=mq.idmaquinaria AND mq.idproveedor=prov.idproveedor AND f.idproyecto=p.idproyecto 
      AND f.estado = '1' AND f.estado_delete = '1' AND mq.tipo = '1' AND f.$estado_vb $filtro_proveedor $filtro_comprobante $filtro_fecha;";
      $maquinaria = ejecutarConsultaSimpleFila($sql2);

      if ($maquinaria['status'] == false) {
        return $maquinaria;
      }

      $total    += (empty($maquinaria['data'])) ? 0 : (empty($maquinaria['data']['total']) ? 0 : floatval($maquinaria['data']['total']));
      $subtotal += (empty($maquinaria['data'])) ? 0 : (empty($maquinaria['data']['subtotal']) ? 0 : floatval($maquinaria['data']['subtotal']));
      $igv      += (empty($maquinaria['data'])) ? 0 : (empty($maquinaria['data']['igv']) ? 0 : floatval($maquinaria['data']['igv']));
    }

    // SUMAS TOTALES - MAQUINARIA --------------------------------------------------------------------------------
    $filtro_proveedor = "";
    $filtro_comprobante = "";
    $filtro_fecha = "";

    if ($modulo == 'todos' || $modulo == 'servicio_maquina') {

      if (!empty($fecha_1) && !empty($fecha_2)) {
        $filtro_fecha = "AND f.fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
      } else if (!empty($fecha_1)) {
        $filtro_fecha = "AND f.fecha_emision = '$fecha_1'";
      } else if (!empty($fecha_2)) {
        $filtro_fecha = "AND f.fecha_emision = '$fecha_2'";
      }

      if (empty($id_proveedor)) {
        $filtro_proveedor = "";
      } else {
        $filtro_proveedor = "AND prov.ruc = '$id_proveedor'";
      }

      if (empty($comprobante)) {
        $filtro_comprobante = "AND f.tipo_comprobante IN ('Factura','Boleta','Nota de Crédito')";
      } else {
        $filtro_comprobante = "AND f.tipo_comprobante = '$comprobante'";
      }

      $sql2 = "SELECT SUM(f.monto) AS total , SUM(f.subtotal) AS subtotal, SUM(f.igv) AS igv
      FROM factura as f, proyecto as p, maquinaria as mq, proveedor as prov
      WHERE f.idmaquinaria=mq.idmaquinaria AND mq.idproveedor=prov.idproveedor AND f.idproyecto=p.idproyecto 
      AND f.estado = '1' AND f.estado_delete = '1' AND mq.tipo = '1' AND f.$estado_vb $filtro_proveedor $filtro_comprobante $filtro_fecha;";
      $maquinaria = ejecutarConsultaSimpleFila($sql2);

      if ($maquinaria['status'] == false) {
        return $maquinaria;
      }

      $total    += (empty($maquinaria['data'])) ? 0 : (empty($maquinaria['data']['total']) ? 0 : floatval($maquinaria['data']['total']));
      $subtotal += (empty($maquinaria['data'])) ? 0 : (empty($maquinaria['data']['subtotal']) ? 0 : floatval($maquinaria['data']['subtotal']));
      $igv      += (empty($maquinaria['data'])) ? 0 : (empty($maquinaria['data']['igv']) ? 0 : floatval($maquinaria['data']['igv']));
    }

    // SUMAS TOTALES - SUB CONTRATO --------------------------------------------------------------------------------
    $filtro_proveedor = "";
    $filtro_comprobante = "";
    $filtro_fecha = "";

    if ($modulo == 'todos' || $modulo == 'sub_contrato') {
      if (!empty($fecha_1) && !empty($fecha_2)) {
        $filtro_fecha = "AND s.fecha_subcontrato BETWEEN '$fecha_1' AND '$fecha_2'";
      } else if (!empty($fecha_1)) {
        $filtro_fecha = "AND s.fecha_subcontrato = '$fecha_1'";
      } else if (!empty($fecha_2)) {
        $filtro_fecha = "AND s.fecha_subcontrato = '$fecha_2'";
      }

      if (empty($id_proveedor)) {
        $filtro_proveedor = "";
      } else {
        $filtro_proveedor = "AND p.ruc = '$id_proveedor'";
      }

      if (empty($comprobante)) {
        $filtro_comprobante = "AND s.tipo_comprobante IN ('Factura','Boleta','Nota de Crédito')";
      } else {
        $filtro_comprobante = "AND s.tipo_comprobante = '$comprobante'";
      }

      $sql3 = "SELECT SUM(s.subtotal) as subtotal, SUM(s.igv) as igv, SUM(s.costo_parcial) as total
      FROM subcontrato AS s, proveedor as p
      WHERE s.idproveedor = p.idproveedor and s.estado = '1' AND s.estado_delete = '1' AND s.$estado_vb 
      $filtro_proveedor $filtro_comprobante $filtro_fecha;";
      $otro_gasto = ejecutarConsultaSimpleFila($sql3);

      if ($otro_gasto['status'] == false) {
        return $otro_gasto;
      }

      $total    += (empty($otro_gasto['data'])) ? 0 : (empty($otro_gasto['data']['total']) ? 0 : floatval($otro_gasto['data']['total']));
      $subtotal += (empty($otro_gasto['data'])) ? 0 : (empty($otro_gasto['data']['subtotal']) ? 0 : floatval($otro_gasto['data']['subtotal']));
      $igv      += (empty($otro_gasto['data'])) ? 0 : (empty($otro_gasto['data']['igv']) ? 0 : floatval($otro_gasto['data']['igv']));
    }

    // SUMAS TOTALES - PLANILLA SEGURO --------------------------------------------------------------------------------

    $filtro_proveedor = "";
    $filtro_comprobante = "";
    $filtro_fecha = "";

    if ($modulo == 'todos' || $modulo == 'planilla_seguro') {
      if (!empty($fecha_1) && !empty($fecha_2)) {
        $filtro_fecha = "AND ps.fecha_p_s BETWEEN '$fecha_1' AND '$fecha_2'";
      } else if (!empty($fecha_1)) {
        $filtro_fecha = "AND ps.fecha_p_s = '$fecha_1'";
      } else if (!empty($fecha_2)) {
        $filtro_fecha = "AND ps.fecha_p_s = '$fecha_2'";
      }

      if (empty($id_proveedor)) {
        $filtro_proveedor = "";
      } else {
        $filtro_proveedor = "AND prov.ruc = '$id_proveedor'";
      }

      if (empty($comprobante)) {
        $filtro_comprobante = "AND ps.tipo_comprobante IN ('Factura','Boleta','Nota de Crédito')";
      } else {
        $filtro_comprobante = "AND ps.tipo_comprobante = '$comprobante'";
      }

      $sql4 = "SELECT SUM(ps.subtotal) AS subtotal, SUM(ps.igv) AS igv, SUM(ps.costo_parcial) AS total
      FROM planilla_seguro as ps, proyecto as p, proveedor as prov 
      WHERE ps.idproyecto = p.idproyecto and ps.idproveedor = prov.idproveedor and ps.estado ='1' and ps.estado_delete = '1' AND ps.$estado_vb
      $filtro_proveedor $filtro_comprobante $filtro_fecha;";
      $otro_gasto = ejecutarConsultaSimpleFila($sql4);

      if ($otro_gasto['status'] == false) {
        return $otro_gasto;
      }

      $total    += (empty($otro_gasto['data'])) ? 0 : (empty($otro_gasto['data']['total']) ? 0 : floatval($otro_gasto['data']['total']));
      $subtotal += (empty($otro_gasto['data'])) ? 0 : (empty($otro_gasto['data']['subtotal']) ? 0 : floatval($otro_gasto['data']['subtotal']));
      $igv      += (empty($otro_gasto['data'])) ? 0 : (empty($otro_gasto['data']['igv']) ? 0 : floatval($otro_gasto['data']['igv']));
    }

    // SUMAS TOTALES - OTRO SERVICIO --------------------------------------------------------------------------------
    $filtro_proveedor = "";
    $filtro_comprobante = "";
    $filtro_fecha = "";

    if ($modulo == 'todos' || $modulo == 'sub_contrato') {

      if (!empty($fecha_1) && !empty($fecha_2)) {
        $filtro_fecha = "AND fecha_g BETWEEN '$fecha_1' AND '$fecha_2'";
      } else if (!empty($fecha_1)) {
        $filtro_fecha = "AND fecha_g = '$fecha_1'";
      } else if (!empty($fecha_2)) {
        $filtro_fecha = "AND fecha_g = '$fecha_2'";
      }

      if (empty($id_proveedor)) {
        $filtro_proveedor = "";
      } else {
        $filtro_proveedor = "AND ruc = '$id_proveedor'";
      }

      if (empty($comprobante)) {
        $filtro_comprobante = "AND tipo_comprobante IN ('Factura','Boleta','Nota de Crédito')";
      } else {
        $filtro_comprobante = "AND tipo_comprobante = '$comprobante'";
      }

      $sql3 = "SELECT SUM(costo_parcial) as total, SUM(subtotal) AS subtotal, SUM(igv) AS igv
      FROM otro_gasto  
      WHERE estado = '1' AND estado_delete = '1' AND $estado_vb $filtro_proveedor $filtro_comprobante $filtro_fecha;";
      $otro_gasto = ejecutarConsultaSimpleFila($sql3);

      if ($otro_gasto['status'] == false) {
        return $otro_gasto;
      }

      $total    += (empty($otro_gasto['data'])) ? 0 : (empty($otro_gasto['data']['total']) ? 0 : floatval($otro_gasto['data']['total']));
      $subtotal += (empty($otro_gasto['data'])) ? 0 : (empty($otro_gasto['data']['subtotal']) ? 0 : floatval($otro_gasto['data']['subtotal']));
      $igv      += (empty($otro_gasto['data'])) ? 0 : (empty($otro_gasto['data']['igv']) ? 0 : floatval($otro_gasto['data']['igv']));
    }


    // SUMAS TOTALES - TRASNPORTE --------------------------------------------------------------------------------
    $filtro_proveedor = "";
    $filtro_comprobante = "";
    $filtro_fecha = "";

    if ($modulo == 'todos' || $modulo == 'transporte') {

      if (!empty($fecha_1) && !empty($fecha_2)) {
        $filtro_fecha = "AND t.fecha_viaje BETWEEN '$fecha_1' AND '$fecha_2'";
      } else if (!empty($fecha_1)) {
        $filtro_fecha = "AND t.fecha_viaje = '$fecha_1'";
      } else if (!empty($fecha_2)) {
        $filtro_fecha = "AND t.fecha_viaje = '$fecha_2'";
      }

      if (empty($id_proveedor)) {
        $filtro_proveedor = "";
      } else {
        $filtro_proveedor = "AND p.ruc = '$id_proveedor'";
      }

      if (empty($comprobante)) {
        $filtro_comprobante = "AND t.tipo_comprobante IN ('Factura','Boleta','Nota de Crédito')";
      } else {
        $filtro_comprobante = "AND t.tipo_comprobante = '$comprobante'";
      }

      $sql4 = "SELECT SUM(t.precio_parcial) AS total, SUM(t.subtotal) AS subtotal, SUM(t.igv) AS igv
      FROM transporte AS t, proveedor AS p
      WHERE t.idproveedor = p.idproveedor  AND t.estado = '1' AND t.estado_delete = '1' AND t.$estado_vb $filtro_proveedor $filtro_comprobante $filtro_fecha;";
      $transporte = ejecutarConsultaSimpleFila($sql4);

      if ($transporte['status'] == false) {
        return $transporte;
      }

      $total    += (empty($transporte['data'])) ? 0 : (empty($transporte['data']['total']) ? 0 : floatval($transporte['data']['total']));
      $subtotal += (empty($transporte['data'])) ? 0 : (empty($transporte['data']['subtotal']) ? 0 : floatval($transporte['data']['subtotal']));
      $igv      += (empty($transporte['data'])) ? 0 : (empty($transporte['data']['igv']) ? 0 : floatval($transporte['data']['igv']));
    }


    // SUMAS TOTALES - HOSPEDAJE --------------------------------------------------------------------------------
    $filtro_proveedor = "";
    $filtro_comprobante = "";
    $filtro_fecha = "";
    if ($modulo == 'todos' || $modulo == 'hospedaje') {
      if (!empty($fecha_1) && !empty($fecha_2)) {
        $filtro_fecha = "AND fecha_comprobante BETWEEN '$fecha_1' AND '$fecha_2'";
      } else if (!empty($fecha_1)) {
        $filtro_fecha = "AND fecha_comprobante = '$fecha_1'";
      } else if (!empty($fecha_2)) {
        $filtro_fecha = "AND fecha_comprobante = '$fecha_2'";
      }

      if (empty($id_proveedor)) {
        $filtro_proveedor = "";
      } else {
        $filtro_proveedor = "AND ruc = '$id_proveedor'";
      }

      if (empty($comprobante)) {
        $filtro_comprobante = "AND tipo_comprobante IN ('Factura','Boleta','Nota de Crédito')";
      } else {
        $filtro_comprobante = "AND tipo_comprobante = '$comprobante'";
      }

      $sql5 = "SELECT SUM(precio_parcial) as total , SUM(subtotal) AS subtotal, SUM(igv) AS igv
      FROM hospedaje WHERE estado = '1' AND estado_delete = '1' AND $estado_vb $filtro_proveedor $filtro_comprobante $filtro_fecha
      ORDER BY fecha_comprobante DESC;";
      $hospedaje = ejecutarConsultaSimpleFila($sql5);

      if ($hospedaje['status'] == false) {
        return $hospedaje;
      }

      $total    += (empty($hospedaje['data'])) ? 0 : (empty($hospedaje['data']['total']) ? 0 : floatval($hospedaje['data']['total']));
      $subtotal += (empty($hospedaje['data'])) ? 0 : (empty($hospedaje['data']['subtotal']) ? 0 : floatval($hospedaje['data']['subtotal']));
      $igv      += (empty($hospedaje['data'])) ? 0 : (empty($hospedaje['data']['igv']) ? 0 : floatval($hospedaje['data']['igv']));
    }


    // SUMAS TOTALES - FACTURA PENSION --------------------------------------------------------------------------------
    $filtro_proveedor = "";
    $filtro_comprobante = "";
    $filtro_fecha = "";

    if ($modulo == 'todos' || $modulo == 'pension') {
      if (!empty($fecha_1) && !empty($fecha_2)) {
        $filtro_fecha = "AND dp.fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
      } else if (!empty($fecha_1)) {
        $filtro_fecha = "AND dp.fecha_emision = '$fecha_1'";
      } else if (!empty($fecha_2)) {
        $filtro_fecha = "AND dp.fecha_emision = '$fecha_2'";
      }

      if (empty($id_proveedor)) {
        $filtro_proveedor = "";
      } else {
        $filtro_proveedor = "AND prov.ruc = '$id_proveedor'";
      }

      if (empty($comprobante)) {
        $filtro_comprobante = "AND dp.tipo_comprobante IN ('Factura','Boleta','Nota de Crédito')";
      } else {
        $filtro_comprobante = "AND dp.tipo_comprobante = '$comprobante'";
      }

      $sql6 = "SELECT SUM(dp.precio_parcial) AS total, SUM(dp.subtotal) AS subtotal, SUM(dp.igv) AS igv
      FROM detalle_pension as dp, pension as p, proveedor as prov
      WHERE dp.idpension = p.idpension AND prov.idproveedor = p.idproveedor  AND p.estado = '1' AND p.estado_delete = '1' 
      AND dp.estado = '1' AND dp.estado_delete = '1' AND dp.$estado_vb $filtro_proveedor $filtro_comprobante $filtro_fecha ;";
      $factura_pension = ejecutarConsultaSimpleFila($sql6);

      if ($factura_pension['status'] == false) {
        return $factura_pension;
      }

      $total    += (empty($factura_pension['data'])) ? 0 : (empty($factura_pension['data']['total']) ? 0 : floatval($factura_pension['data']['total']));
      $subtotal += (empty($factura_pension['data'])) ? 0 : (empty($factura_pension['data']['subtotal']) ? 0 : floatval($factura_pension['data']['subtotal']));
      $igv      += (empty($factura_pension['data'])) ? 0 : (empty($factura_pension['data']['igv']) ? 0 : floatval($factura_pension['data']['igv']));
    }


    // SUMAS TOTALES - FACTURA BREACK --------------------------------------------------------------------------------
    $filtro_proveedor = "";
    $filtro_comprobante = "";
    $filtro_fecha = "";

    if ($modulo == 'todos' || $modulo == 'breack') {

      if (!empty($fecha_1) && !empty($fecha_2)) {
        $filtro_fecha = "AND fb.fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
      } else if (!empty($fecha_1)) {
        $filtro_fecha = "AND fb.fecha_emision = '$fecha_1'";
      } else if (!empty($fecha_2)) {
        $filtro_fecha = "AND fb.fecha_emision = '$fecha_2'";
      }

      if (empty($id_proveedor)) {
        $filtro_proveedor = "";
      } else {
        $filtro_proveedor = "AND fb.ruc = '$id_proveedor'";
      }

      if (empty($comprobante)) {
        $filtro_comprobante = "AND fb.tipo_comprobante IN ('Factura','Boleta','Nota de Crédito')";
      } else {
        $filtro_comprobante = "AND fb.tipo_comprobante = '$comprobante'";
      }
      $sql7 = "SELECT SUM(fb.monto) AS total, SUM(fb.subtotal) AS subtotal, SUM(fb.igv) AS igv
      FROM factura_break as fb, semana_break as sb
      WHERE  fb.idsemana_break = sb.idsemana_break AND fb.estado = '1' AND fb.estado_delete = '1' AND sb.estado = '1' 
      AND sb.estado_delete = '1' AND fb.$estado_vb $filtro_proveedor $filtro_comprobante $filtro_fecha ;";
      $factura_break = ejecutarConsultaSimpleFila($sql7);

      if ($factura_break['status'] == false) {
        return $factura_break;
      }

      $total    += (empty($factura_break['data'])) ? 0 : (empty($factura_break['data']['total']) ? 0 : floatval($factura_break['data']['total']));
      $subtotal += (empty($factura_break['data'])) ? 0 : (empty($factura_break['data']['subtotal']) ? 0 : floatval($factura_break['data']['subtotal']));
      $igv      += (empty($factura_break['data'])) ? 0 : (empty($factura_break['data']['igv']) ? 0 : floatval($factura_break['data']['igv']));
    }


    // SUMAS TOTALES - COMIDA EXTRA --------------------------------------------------------------------------------
    $filtro_proveedor = "";
    $filtro_comprobante = "";
    $filtro_fecha = "";

    if ($modulo == 'todos' || $modulo == 'comida_extra') {

      if (!empty($fecha_1) && !empty($fecha_2)) {
        $filtro_fecha = "AND fecha_comida BETWEEN '$fecha_1' AND '$fecha_2'";
      } else if (!empty($fecha_1)) {
        $filtro_fecha = "AND fecha_comida = '$fecha_1'";
      } else if (!empty($fecha_2)) {
        $filtro_fecha = "AND fecha_comida = '$fecha_2'";
      }

      if (empty($id_proveedor)) {
        $filtro_proveedor = "";
      } else {
        $filtro_proveedor = "AND ruc = '$id_proveedor'";
      }

      if (empty($comprobante)) {
        $filtro_comprobante = "AND tipo_comprobante IN ('Factura','Boleta','Nota de Crédito')";
      } else {
        $filtro_comprobante = "AND tipo_comprobante = '$comprobante'";
      }

      $sql8 = "SELECT SUM(costo_parcial) AS total, SUM(subtotal) AS subtotal, SUM(igv) AS igv
      FROM comida_extra
      WHERE  estado = '1' AND estado_delete = '1' AND $estado_vb $filtro_proveedor $filtro_comprobante $filtro_fecha;";
      $comida_extra = ejecutarConsultaSimpleFila($sql8);

      if ($comida_extra['status'] == false) {
        return $comida_extra;
      }

      $total    += (empty($comida_extra['data'])) ? 0 : (empty($comida_extra['data']['total']) ? 0 : floatval($comida_extra['data']['total']));
      $subtotal += (empty($comida_extra['data'])) ? 0 : (empty($comida_extra['data']['subtotal']) ? 0 : floatval($comida_extra['data']['subtotal']));
      $igv      += (empty($comida_extra['data'])) ? 0 : (empty($comida_extra['data']['igv']) ? 0 : floatval($comida_extra['data']['igv']));
    }

    // SUMAS TOTALES - OTRA FACTURA --------------------------------------------------------------------------------
    $filtro_proveedor = "";
    $filtro_comprobante = "";
    $filtro_fecha = "";

    if ($modulo == 'todos' || $modulo == 'otra_factura') {

      if (!empty($fecha_1) && !empty($fecha_2)) {
        $filtro_fecha = "AND ofp.fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
      } else if (!empty($fecha_1)) {
        $filtro_fecha = "AND ofp.fecha_emision = '$fecha_1'";
      } else if (!empty($fecha_2)) {
        $filtro_fecha = "AND ofp.fecha_emision = '$fecha_2'";
      }

      if (empty($id_proveedor)) {
        $filtro_proveedor = "";
      } else {
        $filtro_proveedor = "AND p.ruc = '$id_proveedor'";
      }

      if (empty($comprobante)) {
        $filtro_comprobante = "AND ofp.tipo_comprobante IN ('Factura','Boleta','Nota de Crédito')";
      } else {
        $filtro_comprobante = "AND ofp.tipo_comprobante = '$comprobante'";
      }
      $sql9 = "SELECT SUM(ofp.costo_parcial) AS total, SUM(ofp.subtotal) AS subtotal, SUM(ofp.igv) AS igv
      FROM otra_factura AS ofp, proveedor p
      WHERE ofp.idproveedor = p.idproveedor AND ofp.estado = '1' AND ofp.estado_delete = '1' AND ofp.$estado_vb $filtro_proveedor $filtro_comprobante $filtro_fecha";
      $otra_factura = ejecutarConsultaSimpleFila($sql9);

      if ($otra_factura['status'] == false) {
        return $otra_factura;
      }

      $total    += (empty($otra_factura['data'])) ? 0 : (empty($otra_factura['data']['total']) ? 0 : floatval($otra_factura['data']['total']));
      $subtotal += (empty($otra_factura['data'])) ? 0 : (empty($otra_factura['data']['subtotal']) ? 0 : floatval($otra_factura['data']['subtotal']));
      $igv      += (empty($otra_factura['data'])) ? 0 : (empty($otra_factura['data']['igv']) ? 0 : floatval($otra_factura['data']['igv']));
    }

    $data = array(
      "status" => true,
      "message" => 'todo oka',
      "data" => [
        "total" => $total,
        "subtotal" => $subtotal,
        "igv" => $igv,
      ]
    );

    return $data;
  }

  // ════════════════════════════════════════ VISTO BUENO ════════════════════════════════════════
  public function visto_bueno($nombre_tabla, $nombre_id_tabla, $id_tabla, $accion)
  {

    $id_user_vb = $_SESSION["idusuario"];
    $nombre_user_vb = $_SESSION["nombre"];
    $imagen_user_vb = $_SESSION["imagen"];

    if ($accion == 'agregar') {
      $sql = "UPDATE $nombre_tabla SET estado='1', id_user_vb_rf = '$id_user_vb', nombre_user_vb_rf = '$nombre_user_vb',user_updated= '" . $_SESSION['idusuario'] . "', 
      imagen_user_vb_rf = '$imagen_user_vb', estado_user_vb_rf = '1'
      WHERE $nombre_id_tabla ='$id_tabla' ";

      $visto_b =  ejecutarConsulta($sql);
      if ($visto_b['status'] == false) {
        return $visto_b;
      }
      //add registro en nuestra bitacora
      $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('$nombre_tabla','$id_tabla','agregar visto bueno para la factura" . $id_tabla . "','" . $_SESSION['idusuario'] . "')";
      $bitacora = ejecutarConsulta($sql_bit);
      if ($bitacora['status'] == false) {
        return $bitacora;
      }
      return $visto_b;
    } else if ($accion == 'quitar') {
      $sql = "UPDATE $nombre_tabla SET estado='1', id_user_vb_rf = '', nombre_user_vb_rf = '', imagen_user_vb_rf ='', estado_user_vb_rf = '0',user_updated= '" . $_SESSION['idusuario'] . "'
      WHERE $nombre_id_tabla ='$id_tabla'";
      $visto_b =  ejecutarConsulta($sql);
      if ($visto_b['status'] == false) {
        return $visto_b;
      }
      //add registro en nuestra bitacora
      $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('$nombre_tabla','$id_tabla','Quitar visto bueno para la factura" . $id_tabla . "','" . $_SESSION['idusuario'] . "')";
      $bitacora = ejecutarConsulta($sql_bit);
      if ($bitacora['status'] == false) {
        return $bitacora;
      }
      return $visto_b;
    }
  }

  // ════════════════════════════════════════ DETALLE MODULO ════════════════════════════════════════
  // detalle_servicio_maquina
  public function detalle_servicio_maquina($id)
  {
    $sql = "SELECT mq.nombre as nombre_maquina, prov.razon_social, f.codigo, f.fecha_emision,  f.subtotal, f.igv, f.monto as total, 
    f.nota, f.descripcion, f.imagen as comprobante, f.tipo_comprobante
    FROM factura as f, proyecto as p, maquinaria as mq, proveedor as prov
    WHERE f.idmaquinaria=mq.idmaquinaria AND mq.idproveedor=prov.idproveedor AND f.idproyecto=p.idproyecto 
    AND f.estado = '1' AND f.estado_delete = '1' AND mq.tipo = '1' AND  f.idfactura = '$id';";
    return ejecutarConsultaSimpleFila($sql);
  }

  // detalle_servicio_equipo
  public function detalle_servicio_equipo($id)
  {
    $sql = "SELECT mq.nombre as nombre_maquina, prov.razon_social, f.codigo, f.fecha_emision,  f.subtotal, f.igv, f.monto as total, 
    f.nota, f.descripcion, f.imagen as comprobante, f.tipo_comprobante
    FROM factura as f, proyecto as p, maquinaria as mq, proveedor as prov
    WHERE f.idmaquinaria=mq.idmaquinaria AND mq.idproveedor=prov.idproveedor AND f.idproyecto=p.idproyecto 
    AND f.estado = '1' AND f.estado_delete = '1' AND mq.tipo = '2' AND  f.idfactura = '$id';";
    return ejecutarConsultaSimpleFila($sql);
  }

  // detalle_sub_contrato
  public function detalle_sub_contrato($id)
  {
    $sql = "SELECT p.razon_social, p.tipo_documento, p.ruc, s.forma_de_pago, s.tipo_comprobante, s.numero_comprobante, s.fecha_subcontrato, 
    s.val_igv, s.subtotal, s.igv, s.costo_parcial as total, s.descripcion, s.glosa, s.comprobante    
    FROM subcontrato AS s, proveedor as p
    WHERE s.idproveedor = p.idproveedor and s.estado = '1' AND s.estado_delete = '1' AND s.idsubcontrato = '$id';";
    return ejecutarConsultaSimpleFila($sql);
  }

  // detalle_sub_contrato
  public function detalle_planilla_seguro($id)
  {
    $sql = "SELECT ps.forma_de_pago, ps.tipo_comprobante, ps.numero_comprobante, ps.fecha_p_s, ps.subtotal, ps.igv, ps.costo_parcial as total, 
     ps.val_igv, ps.tipo_gravada, ps.comprobante, ps.descripcion
    FROM planilla_seguro as ps, proyecto as p
    WHERE ps.idproyecto = p.idproyecto and ps.estado ='1' and ps.estado_delete = '1' AND ps.idplanilla_seguro = '$id';";
    return ejecutarConsultaSimpleFila($sql);
  }

  // detalle_sub_contrato
  public function detalle_otro_gasto($id)
  {
    $sql = "SELECT  razon_social, ruc, forma_de_pago, tipo_comprobante, numero_comprobante, fecha_g, subtotal, val_igv, igv, glosa, 
    costo_parcial as total, tipo_gravada, comprobante, descripcion
    FROM otro_gasto 
    WHERE  estado = '1' AND estado_delete = '1' AND idotro_gasto = '$id';";
    return ejecutarConsultaSimpleFila($sql);
  }

  // detalle_sub_contrato
  public function detalle_transporte($id)
  {
    $sql = "SELECT t.idtransporte, t.idproyecto, p.razon_social, p.tipo_documento, p.ruc, t.forma_de_pago, t.tipo_comprobante, t.numero_comprobante,
    t.fecha_viaje, t.cantidad, t.precio_unitario, t.subtotal, t.igv, t.val_igv, t.precio_parcial as total,  t.comprobante , t.glosa , t.tipo_gravada, 
    t.tipo_viajero, t.tipo_ruta, t.ruta, t.descripcion
    FROM transporte AS t, proveedor AS p
    WHERE t.idproveedor = p.idproveedor  AND t.estado = '1' AND t.estado_delete = '1' AND  t.idtransporte = '$id';";
    return ejecutarConsultaSimpleFila($sql);
  }

  // detalle_sub_contrato
  public function detalle_hospedaje($id)
  {
    $sql = "SELECT  idhospedaje, idproyecto, razon_social, ruc, forma_de_pago, tipo_comprobante, numero_comprobante, fecha_comprobante, cantidad, 
    unidad, precio_unitario, subtotal, igv, val_igv, precio_parcial as total, glosa, comprobante, tipo_gravada, descripcion, fecha_inicio, fecha_fin
    FROM hospedaje 
    WHERE estado = '1' AND estado_delete = '1' AND  idhospedaje = '$id';";
    return ejecutarConsultaSimpleFila($sql);
  }

  // detalle_sub_contrato
  public function detalle_pension($id)
  {
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
  public function detalle_break($id)
  {
    $sql = "SELECT sb.idproyecto, fb.idfactura_break, fb.razon_social, fb.ruc, fb.forma_de_pago, fb.fecha_emision, fb.tipo_comprobante, 
    fb.nro_comprobante, fb.subtotal, fb.igv, fb.val_igv, fb.monto as total, fb.glosa, fb.comprobante, fb.tipo_gravada, fb.descripcion
    FROM factura_break as fb, semana_break as sb
    WHERE  fb.idsemana_break = sb.idsemana_break  
    AND fb.estado = '1' AND fb.estado_delete = '1' AND sb.estado = '1' AND sb.estado_delete = '1' AND  fb.idfactura_break = '$id';";
    return ejecutarConsultaSimpleFila($sql);
  }

  // detalle_sub_contrato
  public function detalle_comida_extra($id)
  {
    $sql = "SELECT idproyecto, idcomida_extra, razon_social, ruc, forma_de_pago, fecha_comida, tipo_comprobante, numero_comprobante, 
    subtotal, igv, val_igv, costo_parcial as total, glosa, comprobante, tipo_gravada, descripcion
    FROM comida_extra
    WHERE  estado = '1' AND estado_delete = '1' AND idcomida_extra = '$id';";
    return ejecutarConsultaSimpleFila($sql);
  }

  // detalle_pago_administrador
  public function detalle_pago_administrador($id)
  {
    $sql = "SELECT tpp.idproyecto, pxma.idpagos_x_mes_administrador, pxma.idfechas_mes_pagos_administrador, pxma.cuenta_deposito, pxma.forma_de_pago, pxma.monto, 
    pxma.fecha_pago, pxma.baucher, pxma.descripcion, fmpa.recibos_x_honorarios, fmpa.tipo_comprobante, fmpa.numero_comprobante, t.nombres as trabajador, t.imagen_perfil, t.tipo_documento, t.numero_documento, 
    pxma.id_user_vb, pxma.nombre_user_vb, pxma.imagen_user_vb, pxma.estado_user_vb
    FROM pagos_x_mes_administrador as pxma, fechas_mes_pagos_administrador as fmpa, trabajador_por_proyecto as tpp, trabajador t
    WHERE pxma.idfechas_mes_pagos_administrador = fmpa.idfechas_mes_pagos_administrador AND fmpa.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto
    AND tpp.idtrabajador = t.idtrabajador AND pxma.idpagos_x_mes_administrador = '$id';";
    return ejecutarConsultaSimpleFila($sql);
  }

  // detalle_pago_administrador
  public function detalle_pago_obrero($id)
  {
    $sql = "SELECT tpp.idproyecto, pqso.idpagos_q_s_obrero, pqso.idresumen_q_s_asistencia, pqso.cuenta_deposito, pqso.forma_de_pago, pqso.monto_deposito, 
    pqso.fecha_pago, pqso.baucher, pqso.descripcion, rqsa.recibos_x_honorarios, rqsa.tipo_comprobante, rqsa.numero_comprobante, t.nombres as trabajador, t.imagen_perfil, t.tipo_documento, t.numero_documento,
    pqso.id_user_vb, pqso.nombre_user_vb, pqso.imagen_user_vb, pqso.estado_user_vb
    FROM pagos_q_s_obrero as pqso, resumen_q_s_asistencia as rqsa, trabajador_por_proyecto as tpp, trabajador t
    WHERE pqso.idresumen_q_s_asistencia = rqsa.idresumen_q_s_asistencia AND rqsa.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto
    AND tpp.idtrabajador = t.idtrabajador AND pqso.idpagos_q_s_obrero = '$id';";
    return ejecutarConsultaSimpleFila($sql);
  }

  // detalle_pago_administrador
  public function detalle_otra_factura($id, $name_tabla)
  {
    $sql = "";

    if ($name_tabla == 'otra_factura') {

      $sql = "SELECT ofp.idotra_factura, ofp.idproveedor, ofp.idempresa_a_cargo, ofp.tipo_comprobante, ofp.numero_comprobante, ofp.forma_de_pago, 
    ofp.fecha_emision, ofp.val_igv, ofp.subtotal, ofp.igv, ofp.costo_parcial, ofp.descripcion, ofp.glosa, ofp.comprobante, ofp.tipo_gravada, 
    ofp.estado, ofp.estado_delete, 
    ofp.id_user_vb, ofp.nombre_user_vb, ofp.imagen_user_vb, ofp.estado_user_vb,
    prov.razon_social, prov.tipo_documento, prov.ruc
    FROM otra_factura as ofp, proveedor as prov  
    WHERE ofp.idproveedor = prov.idproveedor AND ofp.idotra_factura =  '$id';";

    } else {
      
      $sql = "SELECT ofp.idotra_factura_proyecto, ofp.idproveedor, ofp.idempresa_a_cargo, ofp.tipo_comprobante, ofp.numero_comprobante, 
    ofp.forma_de_pago, ofp.fecha_emision, ofp.val_igv, ofp.subtotal, ofp.igv, ofp.costo_parcial, ofp.descripcion, ofp.glosa, 
    ofp.comprobante, ofp.tipo_gravada, ofp.estado, ofp.estado_delete, ofp.id_user_vb, ofp.nombre_user_vb, ofp.imagen_user_vb, 
    ofp.estado_user_vb, prov.razon_social, prov.tipo_documento, prov.ruc 
    FROM otra_factura_proyecto as ofp, proveedor as prov 
    WHERE ofp.idproveedor = prov.idproveedor AND ofp.idotra_factura_proyecto = '$id';";
    }

    return ejecutarConsultaSimpleFila($sql);
  }

  // ════════════════════════════════════════ ACCIONES ════════════════════════════════════════
  // eliminar permanente
  public function eliminar_permanente($nombre_tabla, $nombre_id_tabla, $id_tabla)
  {
    $sql = "UPDATE $nombre_tabla SET estado_delete='0',user_delete= '" . $_SESSION['idusuario'] . "'  WHERE $nombre_id_tabla ='$id_tabla'";
    $eliminar =  ejecutarConsulta($sql);
    if ($eliminar['status'] == false) {
      return $eliminar;
    }

    //add registro en nuestra bitacora
    $sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('$nombre_tabla','$id_tabla','Factura eliminada desde el modulo de resumen de facturas','" . $_SESSION['idusuario'] . "')";
    $bitacora = ejecutarConsulta($sql);
    if ($bitacora['status'] == false) {
      return $bitacora;
    }

    return $eliminar;
  }

  // ════════════════════════════════════════ S E L E C T 2   -   P R O V E E D O R ════════════════════════════════════════
  public function select_proveedores()
  {

    $data = array();

    $sql = "SELECT idproveedor, razon_social, ruc FROM proveedor WHERE estado = '1' AND estado_delete = '1';";
    $proveedor = ejecutarConsultaArray($sql);
    if ($proveedor['status'] == false) {
      return $proveedor;
    }

    if (!empty($proveedor['data'])) {
      foreach ($proveedor['data'] as $key => $value) {
        $data[] = array(
          "id" =>  $value['idproveedor'],
          "razon_social" =>  $value['razon_social'],
          "ruc" =>  $value['ruc'],
        );
      }
    }

    $sql2 = "SELECT ruc, razon_social FROM otro_gasto WHERE estado = '1' AND estado_delete = '1' AND ruc != '' AND razon_social != '' GROUP BY ruc;";
    $otro_gasto = ejecutarConsultaArray($sql2);
    if ($otro_gasto['status'] == false) {
      return $otro_gasto;
    }

    if (!empty($otro_gasto['data'])) {
      foreach ($otro_gasto['data'] as $key => $value) {
        $data[] = array(
          "id" =>  '',
          "razon_social" =>  $value['razon_social'],
          "ruc" =>  $value['ruc'],
        );
      }
    }

    $sql2 = "SELECT ruc, razon_social  FROM hospedaje WHERE estado = '1' AND estado_delete = '1' AND ruc != '' AND razon_social != '' GROUP BY ruc;";
    $hospedaje = ejecutarConsultaArray($sql2);
    if ($hospedaje['status'] == false) {
      return $hospedaje;
    }

    if (!empty($hospedaje['data'])) {
      foreach ($hospedaje['data'] as $key => $value) {
        $data[] = array(
          "id" =>  '',
          "razon_social" =>  $value['razon_social'],
          "ruc" =>  $value['ruc'],
        );
      }
    }

    $sql2 = "SELECT ruc, razon_social  FROM comida_extra WHERE estado = '1' AND estado_delete = '1' AND ruc != '' AND razon_social != '' GROUP BY ruc;";
    $comida_extra = ejecutarConsultaArray($sql2);
    if ($comida_extra['status'] == false) {
      return $comida_extra;
    }

    if (!empty($comida_extra['data'])) {
      foreach ($comida_extra['data'] as $key => $value) {
        $data[] = array(
          "id" =>  '',
          "razon_social" =>  $value['razon_social'],
          "ruc" =>  $value['ruc'],
        );
      }
    }
    $retorno = array(
      "status" => true,
      "message" => 'todo oka',
      "data" =>   $data
    );
    return $retorno;
  }
}
