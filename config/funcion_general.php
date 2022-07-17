<?php
// validamos la repeticion de funciones
if (!function_exists('ejecutarConsulta')) {

  

  /*  ══════════════════════════════════════════ - F E C H A S - ══════════════════════════════════════════ */

  function nombre_dia_mes_anio( $fecha_entrada ) {

    $fecha_parse = new FechaEs($fecha_entrada);
    $dia = $fecha_parse->getDDDD().PHP_EOL;
    $mun_dia = $fecha_parse->getdd().PHP_EOL;
    $mes = $fecha_parse->getMMMM().PHP_EOL;
    $anio = $fecha_parse->getYYYY().PHP_EOL;
    $fecha_nombre_completo = "$dia, <br> $mun_dia de <b>$mes</b>  del $anio";

    return $fecha_nombre_completo;
  }

  function nombre_mes( $fecha_entrada ) {

    $fecha_parse = new FechaEs($fecha_entrada);
    
    $mes_nombre = $fecha_parse->getMMMM().PHP_EOL;

    return $mes_nombre;
  }

  // NOMBRE DIA DE SEMANA
  function nombre_dia_semana($fecha) {

    $nombre_dia_semana = "";

    if (!empty($fecha) || $fecha != '0000-00-00') {

      $fechas = new FechaEs($fecha);

      $dia = $fechas->getDDDD().PHP_EOL;

      $nombre_dia_semana = $dia;
    }

    return $nombre_dia_semana;
  }

  function sumar_dias( $cant, $fecha )  {    
    return date("Y-m-d",strtotime( "$cant days" , strtotime( $fecha ) ) ); 
  }

  function validar_fecha_menor_que($fecha_menor, $fecha_mayor) {
    $fecha_1 = strtotime( $fecha_menor );
    $fecha_2 = strtotime( $fecha_mayor );
    if ($fecha_1 < $fecha_2) { return true; }    
    return false;
  }

  function validar_fecha_menor_igual_que($fecha_menor, $fecha_mayor) {
    $fecha_1 = strtotime( $fecha_menor );
    $fecha_2 = strtotime( $fecha_mayor );
    if ($fecha_1 <= $fecha_2) { return true; }    
    return false;
  }

  // convierte de una fecha(dd-mm-aa): 23-12-2021 a una fecha(aa-mm-dd): 2021-12-23
  function format_a_m_d( $fecha ) {
    $fecha_convert = "";
    if (empty($fecha) || $fecha == '0000-00-00') { }else{
      $fecha_expl = explode("-", $fecha);
      $fecha_convert =  $fecha_expl[2]."-".$fecha_expl[1]."-".$fecha_expl[0];
    }
    return $fecha_convert;
  }

  // convierte de una fecha(aa-mm-dd): 2021-12-23 a una fecha(dd-mm-aa): 23-12-2021
  function format_d_m_a( $fecha ) {
    $fecha_convert = "";
    if (empty($fecha) || $fecha == '0000-00-00') { }else{
      $fecha_expl = explode("-", $fecha);
      $fecha_convert =  $fecha_expl[2]."-".$fecha_expl[1]."-".$fecha_expl[0];
    }
    return $fecha_convert;
  }

  /*  ══════════════════════════════════════════ - N U M E R I C O S - ══════════════════════════════════════════ */

  function multiplo_number($numero, $multiplo) {  if($numero%$multiplo == 0){ return true; }else{ return false; } }

  /*  ══════════════════════════════════════════ - S T R I N G - ══════════════════════════════════════════ */
  
  function quitar_guion($numero) { return str_replace("-", "", $numero); }

  /*  ══════════════════════════════════════════ - S U B I R   D O C S  - ══════════════════════════════════════════ */
  /*  ══════════════════════════════════════════ - A P I S - ══════════════════════════════════════════ */
  /*  ══════════════════════════════════════════ - M E N S A J E S - ══════════════════════════════════════════ */

  /*  ══════════════════════════════════════════ - O T R O S - ══════════════════════════════════════════ */

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
}
  

?>