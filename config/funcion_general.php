<?php
// validamos la repeticion de funciones
if (!function_exists('ejecutarConsulta')) {

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
}
  

?>