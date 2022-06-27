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

    $sql_1="SELECT sc.idsubcontrato, sc.idproyecto, sc.idproveedor,sc.costo_parcial, sc.comprobante, p.nombre_codigo, prv.razon_social
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

    $sql_2="SELECT pagos_adm.idfechas_mes_pagos_administrador,pagos_adm.idtrabajador_por_proyecto, pagos_adm.monto_x_mes, pagos_adm.recibos_x_honorarios, t.nombres, t_proy.idproyecto, p.nombre_codigo
    FROM fechas_mes_pagos_administrador as pagos_adm, trabajador_por_proyecto as t_proy, trabajador as t, proyecto as p
    WHERE pagos_adm.estado=1 AND pagos_adm.estado_delete=1 AND pagos_adm.recibos_x_honorarios!='' AND pagos_adm.idtrabajador_por_proyecto=t_proy.idtrabajador_por_proyecto 
    AND t_proy.idtrabajador=t.idtrabajador AND t_proy.idproyecto=p.idproyecto;";    
    $pagos_adm = ejecutarConsultaArray($sql_2);

    if ($pagos_adm['status'] == false) { return $pagos_adm; }

    if (!empty($pagos_adm['data'])) {

      foreach ($pagos_adm['data'] as $key => $value) {

        $data[] = array(
          "idproyecto"             => $value['idproyecto'],
          "idtabla"                => $value['idfechas_mes_pagos_administrador'],
          "codigo_proyecto"        => $value['nombre_codigo'],
          "trabajador_razon_social"=> $value['nombres'],
          "total"                  => $value['monto_x_mes'],
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

        if (!empty($value['monto_x_mes'])) {          
          $monto_total+= $value['monto_x_mes'];
        } 

      }  
    }

    $sql_3="SELECT r_q_asist.idresumen_q_s_asistencia,r_q_asist.idtrabajador_por_proyecto, r_q_asist.pago_quincenal, r_q_asist.recibos_x_honorarios, t.nombres, t_proy.idproyecto, p.nombre_codigo
    FROM resumen_q_s_asistencia as r_q_asist, trabajador_por_proyecto as t_proy, trabajador as t,  proyecto as p
    WHERE r_q_asist.estado=1 AND r_q_asist.estado_delete=1 AND r_q_asist.recibos_x_honorarios!='' AND r_q_asist.idtrabajador_por_proyecto= t_proy.idtrabajador_por_proyecto 
    AND t_proy.idtrabajador=t.idtrabajador AND t_proy.idproyecto=p.idproyecto;";
    $pagos_obrero = ejecutarConsultaArray($sql_3);

    if ($pagos_obrero['status'] == false) { return $pagos_obrero; }

    if (!empty($pagos_obrero['data'])) {

      foreach ($pagos_obrero['data'] as $key => $value) {

        $data[] = array(  
          "idproyecto"             => $value['idproyecto'],
          "idtabla"                => $value['idresumen_q_s_asistencia'],
          "codigo_proyecto"        => $value['nombre_codigo'],
          "trabajador_razon_social"=> $value['nombres'],
          "total"                  => $value['pago_quincenal'],
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
        
        if (!empty($value['pago_quincenal'])) {
          $monto_total+= $value['pago_quincenal'];
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
