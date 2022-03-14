<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Proyecto
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($tipo_documento, $numero_documento, $empresa, $nombre_proyecto, $nombre_codigo, $ubicacion, $actividad_trabajo, $empresa_acargo, $costo, $fecha_inicio_actividad, $fecha_fin_actividad, $plazo_actividad, $fecha_inicio, $fecha_fin,$plazo , $dias_habiles, $doc1, $doc2, $doc3, $doc4, $doc5, $doc6, $fecha_pago_obrero, $fecha_valorizacion, $permanente_pago_obrero)
	{
		$doc7 = ""; $doc8 = ""; $calendario_error = "No hay feriados, agregue alguno";		
		
		// prepoaramos la consulta del proyecto
		$sql="INSERT INTO proyecto ( tipo_documento, numero_documento, empresa, nombre_proyecto, nombre_codigo, ubicacion, actividad_trabajo, empresa_acargo, costo,  fecha_inicio, fecha_fin, plazo, dias_habiles, doc1_contrato_obra, doc2_entrega_terreno, doc3_inicio_obra, doc4_presupuesto, doc5_analisis_costos_unitarios, doc6_insumos, doc7_cronograma_obra_valorizad, doc8_certificado_habilidad_ing_residnt, fecha_pago_obrero, fecha_valorizacion, permanente_pago_obrero) 
		VALUES ('$tipo_documento', '$numero_documento', '$empresa', '$nombre_proyecto', '$nombre_codigo', '$ubicacion', '$actividad_trabajo', '$empresa_acargo', '$costo',  '$fecha_inicio', '$fecha_fin', '$dias_habiles', '$plazo', '$doc1', '$doc2', '$doc3', '$doc4', '$doc5', '$doc6', '$doc7', '$doc8', '$fecha_pago_obrero', '$fecha_valorizacion', '$permanente_pago_obrero');";
		
		// ejecutamos la consulta, Insertamos el registro de proyecto
		$id_proyect = ejecutarConsulta_retornarID($sql) ;

		// creamos la pensión: desayuno almuerzo y cena
		// $sql_pension = "INSERT INTO pension(idproyecto, tipo_pension, precio_variable) 
		// VALUES ('$id_proyect','Desayuno','0'), ('$id_proyect','Almuerzo','0'), ('$id_proyect','Cena','0')";
		// ejecutarConsulta($sql_pension);

		// extraemos todas fechas
		$sql2 = "SELECT titulo, descripcion, fecha_feriado, background_color, text_color FROM calendario WHERE estado = 1;";
		$proyecto =  ejecutarConsultaArray($sql2) ;

		if (!empty($proyecto) ) {			

			// insertamos las fechas al nuevo proyecto
			foreach ($proyecto as $indice => $key) {

				$titulo = $key['titulo']; $descripcion = $key['descripcion']; $fecha_feriado = $key['fecha_feriado']; $background_color = $key['background_color']; $text_color = $key['text_color'];
				
				$sql3="INSERT INTO calendario_por_proyecto (idproyecto, titulo, descripcion, fecha_feriado, background_color, text_color)
				VALUES ('$id_proyect', '$titulo', '$descripcion', '$fecha_feriado', '$background_color', '$text_color')";

				$calendario_proyect = ejecutarConsulta($sql3);
			}
		} else {
			$calendario_proyect = $id_proyect > 0 ? true : false ;			 
		}
		
		// $sql2=	$tipo_documento.$numero_documento.$empresa.$nombre_proyecto.$ubicacion.$actividad_trabajo.$empresa_acargo.$costo.$fecha_inicio.$fecha_fin.$doc1.$doc2.$doc3;
		 
		return $calendario_proyect;	 	
	}

	//Implementamos un método para editar registros
	public function editar($idproyecto, $tipo_documento, $numero_documento, $empresa, $nombre_proyecto, $nombre_codigo, $ubicacion, $actividad_trabajo, $empresa_acargo, $costo, $fecha_inicio_actividad, $fecha_fin_actividad, $plazo_actividad, $fecha_inicio, $fecha_fin, $plazo, $dias_habiles, $doc1, $doc2, $doc3, $doc4, $doc5, $doc6, $fecha_pago_obrero, $fecha_valorizacion, $permanente_pago_obrero)
	{
		 
		$sql="UPDATE proyecto SET tipo_documento = '$tipo_documento', numero_documento = '$numero_documento', 
			empresa = '$empresa', nombre_proyecto = '$nombre_proyecto', nombre_codigo = '$nombre_codigo',  ubicacion = '$ubicacion',
			actividad_trabajo = '$actividad_trabajo', empresa_acargo = '$empresa_acargo', 
			costo = '$costo',  
			fecha_inicio = '$fecha_inicio', fecha_fin = '$fecha_fin', plazo = '$plazo', dias_habiles='$dias_habiles',
			doc1_contrato_obra = '$doc1', doc2_entrega_terreno = '$doc2', doc3_inicio_obra = '$doc3',
			doc4_presupuesto = '$doc4', doc5_analisis_costos_unitarios = '$doc5', doc6_insumos = '$doc6', 
			fecha_pago_obrero = '$fecha_pago_obrero', fecha_valorizacion = '$fecha_valorizacion', permanente_pago_obrero = '$permanente_pago_obrero'
		WHERE idproyecto='$idproyecto'";		 

		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar EDITAR EL DOC VALORIZACIONES
	public function editar_valorizacion($idproyecto, $doc7)
	{
		 
		$sql="UPDATE proyecto SET excel_valorizaciones = '$doc7' WHERE idproyecto = '$idproyecto'";
			
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para empezar proyecto
	public function empezar_proyecto($idproyecto)
	{
		$sql="UPDATE proyecto SET estado='1' WHERE idproyecto='$idproyecto'";

		return ejecutarConsulta($sql);
	}

	//Implementamos un método para terminar proyecto
	public function terminar_proyecto($idproyecto)
	{
		$sql="UPDATE proyecto SET estado='0' WHERE idproyecto='$idproyecto'";

		return ejecutarConsulta($sql);
	}

	//Implementamos un método para reniciar proyecto
	public function reiniciar_proyecto($idproyecto)
	{
		$sql="UPDATE proyecto SET estado='2' WHERE idproyecto='$idproyecto'";

		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idproyecto)
	{
		$sql="SELECT * FROM proyecto WHERE idproyecto='$idproyecto'";

		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM proyecto as p WHERE p.estado = 1 OR p.estado = 2;";
		return ejecutarConsulta($sql);		
	}

	//Implementar un método para listar los registros de proyectos terminados
	public function listar_proyectos_terminados()
	{
		$sql="SELECT * FROM proyecto as p WHERE p.estado = 0;";
		return ejecutarConsulta($sql);		
	}
	// obtebnemos los DOCS para eliminar
	public function obtenerDocs($idproyecto) {

        $sql = "SELECT doc1_contrato_obra, doc2_entrega_terreno, doc3_inicio_obra, doc4_presupuesto, doc5_analisis_costos_unitarios, doc6_insumos FROM proyecto WHERE idproyecto='$idproyecto'";

        return ejecutarConsulta($sql);
    }

	// optenemos el total de PROYECTOS, PROVEEDORES, TRABAJADORES, SERVICIO
	public function tablero() {

        $sql = "SELECT COUNT(p.empresa) AS cantidad_proyectos FROM proyecto AS p;";
		$sql2 = "SELECT COUNT(p.idproveedor) AS cantidad_proveedores FROM proveedor AS p WHERE p.estado = 1;";
		$sql3 = "SELECT COUNT(t.nombres) AS cantidad_trabajadores FROM trabajador AS t WHERE t.estado = 1;";
		$sql4 = "SELECT COUNT(s.idservicio) AS cantidad_servicios FROM servicio AS s;";

		$proyecto = ejecutarConsultaSimpleFila($sql);
		$proveedor = ejecutarConsultaSimpleFila($sql2);
		$trabajador = ejecutarConsultaSimpleFila($sql3);
		$servicio = ejecutarConsultaSimpleFila($sql4);

		$results = array(
			"proyecto" =>$proyecto,
			"proveedor" =>$proveedor,
			"trabajador" =>$trabajador,
			"servicio" =>$servicio,
		);
        return $results;
    }	 

	// optenemos el los feriados
	public function listar_feriados() {

        $sql = "SELECT c.fecha_invertida FROM calendario AS c WHERE c.estado = 1;";
		
        return ejecutarConsultaArray($sql);
    }

	// optenemos el rango de feriados
	public function listar_rango_feriados($fecha_i, $fecha_f) {

        $sql = "SELECT COUNT( c.fecha_feriado) AS count_feriado
		FROM calendario as c
		WHERE c.estado = 1 AND c.fecha_feriado BETWEEN '$fecha_i' AND '$fecha_f';";
		
        return ejecutarConsultaSimpleFila($sql);
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