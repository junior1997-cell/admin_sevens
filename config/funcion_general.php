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
}
  

?>