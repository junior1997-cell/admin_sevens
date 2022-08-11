<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Resumen_rh
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }
  public function resumen_rh()
  {
     
    $scheme_host=  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/admin_sevens/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');
    $host       = $_SERVER['HTTP_HOST'];

    $data = Array();  $data_recibos_honorarios = Array(); $monto_total= 0;

    $sql_1="SELECT sc.idsubcontrato, sc.idproyecto, sc.idproveedor, sc.fecha_subcontrato, sc.costo_parcial, sc.comprobante, p.nombre_codigo, prv.razon_social
    FROM subcontrato as sc, proyecto as p, proveedor as prv 
    WHERE  sc.estado=1 AND sc.estado_delete=1   AND sc.tipo_comprobante='Recibo por honorario' AND sc.idproyecto=p.idproyecto AND sc.idproveedor=prv.idproveedor;";
    $subcontrato = ejecutarConsultaArray($sql_1);
    
    if ($subcontrato['status'] == false) { return $subcontrato; }

    if (!empty($subcontrato['data'])) {

      foreach ($subcontrato['data'] as $key => $value) {

        $data[] = array(
        	"idproyecto"             => $value['idproyecto'],
          "idtabla"                => $value['idsubcontrato'],
          "codigo_proyecto"        => $value['nombre_codigo'],
          "trabajador_razon_social"=> $value['razon_social'],
          "fecha"                  => $value['fecha_subcontrato'],
          "total"                  => $value['costo_parcial'],
          "comprobante"            => $value['comprobante'],
          "carpeta"                => 'sub_contrato',
          "subcarpeta"             => 'comprobante_subcontrato',
          "ruta"                   => '../dist/docs/sub_contrato/comprobante_subcontrato/',
          "modulo"                 => 'SUB CONTRATOS',
        );
        
        if (!empty($value['comprobante'])) {
          if ( validar_url( $scheme_host, 'dist/docs/sub_contrato/comprobante_subcontrato/', $value['comprobante']) ) {
            $data_recibos_honorarios[] = array(
              "comprobante"       => $value['comprobante'],
              "carpeta"           => 'sub_contrato',
              "subcarpeta"        => 'comprobante_subcontrato',
              "host"              => $host,
              "ruta_file"         => $scheme_host.'dist/docs/sub_contrato/comprobante_subcontrato/'.$value['comprobante'],
            );
          }          
        } 

        if (!empty($value['costo_parcial'])) {
          $monto_total+= $value['costo_parcial'];
        }
      } 
    }

    $sql_2="SELECT pxma.idpagos_x_mes_administrador, tpp.idproyecto, p.nombre_codigo, t.nombres as trabajador, t.tipo_documento, 
    t.numero_documento, pxma.monto, pxma.tipo_comprobante, pxma.recibos_x_honorarios, pxma.fecha_pago
    FROM pagos_x_mes_administrador as pxma, fechas_mes_pagos_administrador as fmpa, trabajador_por_proyecto as tpp, trabajador as t, proyecto as p
    WHERE pxma.idfechas_mes_pagos_administrador = fmpa.idfechas_mes_pagos_administrador AND fmpa.idtrabajador_por_proyecto=tpp.idtrabajador_por_proyecto 
    AND tpp.idtrabajador=t.idtrabajador AND tpp.idproyecto=p.idproyecto AND pxma.estado=1 AND pxma.estado_delete=1  
    ORDER BY t.nombres ASC";    
    $pagos_adm = ejecutarConsultaArray($sql_2);

    if ($pagos_adm['status'] == false) { return $pagos_adm; }

    if (!empty($pagos_adm['data'])) {

      foreach ($pagos_adm['data'] as $key => $value) {

        $data[] = array(
          "idproyecto"             => $value['idproyecto'],
          "idtabla"                => $value['idpagos_x_mes_administrador'],
          "codigo_proyecto"        => $value['nombre_codigo'],
          "trabajador_razon_social"=> $value['trabajador'],
          "fecha"                  => $value['fecha_pago'],
          "total"                  => $value['monto'],
          "comprobante"            => $value['recibos_x_honorarios'],
          "carpeta"                => 'pago_administrador',
          "subcarpeta"             => 'recibos_x_honorarios',
          "ruta"                   => '../dist/docs/pago_administrador/recibos_x_honorarios/', 
          "modulo"                 => 'PAGO ADMINISTRADOR',
        );
                
        if (!empty($value['recibos_x_honorarios'])) {
          if ( validar_url( $scheme_host, 'dist/docs/pago_administrador/recibos_x_honorarios/', $value['recibos_x_honorarios']) ) {
            $data_recibos_honorarios[] = array(
              "comprobante"       => $value['recibos_x_honorarios'],
              "carpeta"           => 'pago_administrador',
              "subcarpeta"        => 'recibos_x_honorarios',
              "host"              => $host,
              "ruta_file"         => $scheme_host.'dist/docs/pago_administrador/recibos_x_honorarios/'.$value['recibos_x_honorarios'],
            );
          }          
        } 

        if (!empty($value['monto'])) {          
          $monto_total+= $value['monto'];
        } 

      }  
    }

    $sql_3="SELECT pqso.idpagos_q_s_obrero, tpp.idproyecto, p.nombre_codigo, t.nombres as  trabajador, t.tipo_documento, t.numero_documento, 
    pqso.fecha_pago, pqso.monto_deposito, pqso.tipo_comprobante, pqso.recibos_x_honorarios
    FROM pagos_q_s_obrero as pqso, resumen_q_s_asistencia as rqsa, trabajador_por_proyecto as tpp, trabajador as t,  proyecto as p
    WHERE  pqso.idresumen_q_s_asistencia = rqsa.idresumen_q_s_asistencia  AND rqsa.idtrabajador_por_proyecto= tpp.idtrabajador_por_proyecto 
    AND tpp.idtrabajador=t.idtrabajador AND tpp.idproyecto=p.idproyecto AND pqso.estado=1 AND pqso.estado_delete=1;";
    $pagos_obrero = ejecutarConsultaArray($sql_3);

    if ($pagos_obrero['status'] == false) { return $pagos_obrero; }

    if (!empty($pagos_obrero['data'])) {

      foreach ($pagos_obrero['data'] as $key => $value) {

        $data[] = array(  
          "idproyecto"             => $value['idproyecto'],
          "idtabla"                => $value['idpagos_q_s_obrero'],
          "codigo_proyecto"        => $value['nombre_codigo'],
          "trabajador_razon_social"=> $value['trabajador'],
          "fecha"                  => $value['fecha_pago'],
          "total"                  => $value['monto_deposito'],
          "comprobante"            => $value['recibos_x_honorarios'],
          "carpeta"                => 'pago_obrero',
          "subcarpeta"             => 'recibos_x_honorarios',
          "ruta"                   => '../dist/docs/pago_obrero/recibos_x_honorarios/', 
          "modulo"                 => 'PAGO OBRERO',  
        );

        if (!empty($value['recibos_x_honorarios'])) {
          if ( validar_url( $scheme_host, 'dist/docs/pago_obrero/recibos_x_honorarios/', $value['recibos_x_honorarios']) ) {
            $data_recibos_honorarios[] = array(
              "comprobante"       => $value['recibos_x_honorarios'],
              "carpeta"           => 'pago_obrero',
              "subcarpeta"        => 'recibos_x_honorarios',
              "host"              => $host,
              "ruta_file"         => $scheme_host.'dist/docs/pago_obrero/recibos_x_honorarios/'.$value['recibos_x_honorarios'],
            );
          }          
        } 
        
        if (!empty($value['monto_deposito'])) {
          $monto_total+= $value['monto_deposito'];
        } 
      }  
    }
      
    $retorno = array( 
      "status"=> true,
      "message"=> 'todo oka',
      "data"=> [
        "datos"                     => $data,
        "data_recibos_honorarios"  => $data_recibos_honorarios,
        "monto_total_rh"           => $monto_total,
      ]
    );
    return $retorno;
  }

}

?>
