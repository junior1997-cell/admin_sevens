<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Resumen_rh
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }
  public function resumen_rh()
  {
    $link_host = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/';

    $data = Array();  $data_recibos_honorarios = Array();

    $sql_1="SELECT sc.idsubcontrato, sc.idproyecto, sc.idproveedor,sc.costo_parcial, sc.comprobante, p.nombre_codigo, prv.razon_social
    FROM subcontrato as sc, proyecto as p, proveedor as prv 
    WHERE  sc.estado=1 AND sc.estado_delete=1   AND sc.tipo_comprobante='Recibo por honorario' AND sc.idproyecto=p.idproyecto AND sc.idproveedor=prv.idproveedor;";
    $subcontrato = ejecutarConsultaArray($sql_1);

    if (!empty($subcontrato)) {

      foreach ($subcontrato as $key => $value) {

        $data[] = array(

        	"idproyecto"                => $value['idproyecto'],
          "idtabla"                   => $value['idsubcontrato'],
          "codigo_proyecto"           => $value['nombre_codigo'],
          "trabajador_razon_social"   => $value['razon_social'],
          "total"                     => $value['costo_parcial'],
          "comprobante"               => $value['comprobante'],
          "ruta"                      => '../dist/docs/sub_contrato/comprobante_subcontrato/',
          "modulo"                    => 'SUB CONTRATOS',

        );
        
        if (!empty($value['comprobante'])) {
          if ( validar_url( $link_host, 'dist/docs/sub_contrato/comprobante_subcontrato/', $value['comprobante']) ) {
            $data_recibos_honorarios[] = array(
              "comprobante"       => $value['comprobante'],
              "carpeta_file"      => 'dist/docs/sub_contrato/comprobante_subcontrato/',
              "host"              => $link_host,
              "ruta_nube"         => $link_host.'dist/docs/sub_contrato/comprobante_subcontrato/'.$value['comprobante'],
              "ruta_local"        => 'http://localhost/admin_sevens/dist/docs/sub_contrato/comprobante_subcontrato/'.$value['comprobante'],
            );
          }          
        } 

      }  

    }

    $sql_2="SELECT pagos_adm.idfechas_mes_pagos_administrador,pagos_adm.idtrabajador_por_proyecto, pagos_adm.monto_x_mes, pagos_adm.recibos_x_honorarios, t.nombres, t_proy.idproyecto, p.nombre_codigo
    FROM fechas_mes_pagos_administrador as pagos_adm, trabajador_por_proyecto as t_proy, trabajador as t, proyecto as p
    WHERE pagos_adm.estado=1 AND pagos_adm.estado_delete=1 AND pagos_adm.recibos_x_honorarios!='' AND pagos_adm.idtrabajador_por_proyecto=t_proy.idtrabajador_por_proyecto 
    AND t_proy.idtrabajador=t.idtrabajador AND t_proy.idproyecto=p.idproyecto;";
    
      $pagos_adm = ejecutarConsultaArray($sql_2);

      if (!empty($pagos_adm)) {

        foreach ($pagos_adm as $key => $value) {

          $data[] = array(

            "idproyecto"                => $value['idproyecto'],
            "idtabla"                   => $value['idfechas_mes_pagos_administrador'],
            "codigo_proyecto"           => $value['nombre_codigo'],
            "trabajador_razon_social"   => $value['nombres'],
            "total"                     => $value['monto_x_mes'],
            "comprobante"               => $value['recibos_x_honorarios'],
            "ruta"                      => '../dist/docs/pago_administrador/recibos_x_honorarios/', 
            "modulo"                    => 'PAGO ADMINISTRADOR',

          );
                 
          if (!empty($value['recibos_x_honorarios'])) {
            if ( validar_url( $link_host, 'dist/docs/pago_administrador/recibos_x_honorarios/', $value['recibos_x_honorarios']) ) {
              $data_recibos_honorarios[] = array(
                "comprobante"       => $value['recibos_x_honorarios'],
                "carpeta_file"      => 'dist/docs/pago_administrador/recibos_x_honorarios/',
                "host"              => $link_host,
                "ruta_nube"         => $link_host.'dist/docs/pago_administrador/recibos_x_honorarios/'.$value['recibos_x_honorarios'],
                "ruta_local"        => 'http://localhost/admin_sevens/dist/docs/pago_administrador/recibos_x_honorarios/'.$value['recibos_x_honorarios'],
              );
            }          
          } 

        }  
      }

      $sql_3="SELECT r_q_asist.idresumen_q_s_asistencia,r_q_asist.idtrabajador_por_proyecto, r_q_asist.pago_quincenal, r_q_asist.recibos_x_honorarios, t.nombres, t_proy.idproyecto, p.nombre_codigo
      FROM resumen_q_s_asistencia as r_q_asist, trabajador_por_proyecto as t_proy, trabajador as t,  proyecto as p
      WHERE r_q_asist.estado=1 AND r_q_asist.estado_delete=1 AND r_q_asist.recibos_x_honorarios!='' AND r_q_asist.idtrabajador_por_proyecto= t_proy.idtrabajador_por_proyecto 
      AND t_proy.idtrabajador=t.idtrabajador AND t_proy.idproyecto=p.idproyecto;";

      $pagos_obrero = ejecutarConsultaArray($sql_3);

      if (!empty($pagos_obrero)) {
  
        foreach ($pagos_obrero as $key => $value) {
  
          $data[] = array(
  
            "idproyecto"                => $value['idproyecto'],
            "idtabla"                   => $value['idresumen_q_s_asistencia'],
            "codigo_proyecto"           => $value['nombre_codigo'],
            "trabajador_razon_social"   => $value['nombres'],
            "total"                     => $value['pago_quincenal'],
            "comprobante"               => $value['recibos_x_honorarios'],
            "ruta"                      => '../dist/docs/pago_obrero/recibos_x_honorarios/', 
            "modulo"                    => 'PAGO OBRERO',
  
          );

          if (!empty($value['recibos_x_honorarios'])) {
            if ( validar_url( $link_host, 'dist/docs/pago_obrero/recibos_x_honorarios/', $value['recibos_x_honorarios']) ) {
              $data_recibos_honorarios[] = array(
                "comprobante"       => $value['recibos_x_honorarios'],
                "carpeta_file"      => 'dist/docs/pago_obrero/recibos_x_honorarios/',
                "host"              => $link_host,
                "ruta_nube"         => $link_host.'dist/docs/pago_obrero/recibos_x_honorarios/'.$value['recibos_x_honorarios'],
                "ruta_local"        => 'http://localhost/admin_sevens/dist/docs/pago_obrero/recibos_x_honorarios/'.$value['recibos_x_honorarios'],
              );
            }          
          } 

        }  
      }

      
    $retorno = array(
      "data"              => $data,
      "data_recibos_honorarios"  => $data_recibos_honorarios,
    );
    return $retorno;
  }

}


function validar_url( $host, $ruta, $file )  {
  $tipo = 'nube_host';
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
