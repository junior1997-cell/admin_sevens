<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Persona
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}	 

	//CAPTURAR PERSONA  DE RENIEC 
	public function datos_reniec($dni)
	{ 
		$url = "https://dniruc.apisperu.com/api/v1/dni/".$dni."?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Imp1bmlvcmNlcmNhZG9AdXBldS5lZHUucGUifQ.bzpY1fZ7YvpHU5T83b9PoDxHPaoDYxPuuqMqvCwYqsM";
		//  Iniciamos curl
		$curl = curl_init();
		// Desactivamos verificación SSL
		curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, 0 );
		// Devuelve respuesta aunque sea falsa
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
		// Especificamo los MIME-Type que son aceptables para la respuesta.
		curl_setopt( $curl, CURLOPT_HTTPHEADER, [ 'Accept: application/json' ] );
		// Establecemos la URL
		curl_setopt( $curl, CURLOPT_URL, $url );
		// Ejecutmos curl
		$json = curl_exec( $curl );
		// Cerramos curl
		curl_close( $curl );
  
		$respuestas = json_decode( $json, true );
  
		return $respuestas;
	}

	//CAPTURAR PERSONA  DE RENIEC
	public function datos_sunat($ruc)
	{ 
		$url = "https://dniruc.apisperu.com/api/v1/ruc/".$ruc."?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Imp1bmlvcmNlcmNhZG9AdXBldS5lZHUucGUifQ.bzpY1fZ7YvpHU5T83b9PoDxHPaoDYxPuuqMqvCwYqsM";
		//  Iniciamos curl
		$curl = curl_init();
		// Desactivamos verificación SSL
		curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, 0 );
		// Devuelve respuesta aunque sea falsa
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
		// Especificamo los MIME-Type que son aceptables para la respuesta.
		curl_setopt( $curl, CURLOPT_HTTPHEADER, [ 'Accept: application/json' ] );
		// Establecemos la URL
		curl_setopt( $curl, CURLOPT_URL, $url );
		// Ejecutmos curl
		$json = curl_exec( $curl );
		// Cerramos curl
		curl_close( $curl );
  
		$respuestas = json_decode( $json, true );
  
		return $respuestas;    	
		
	}
}

?>