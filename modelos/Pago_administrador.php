<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class PagoAdministrador
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($idproyecto,$trabajador, $tipo_trabajador, $cargo, $desempenio, $sueldo_mensual, $sueldo_diario, $sueldo_hora)
	{
		$sql="INSERT INTO trabajador_por_proyecto (idproyecto, idtrabajador, tipo_trabajador, cargo, desempenio, sueldo_mensual, sueldo_diario, sueldo_hora)
		VALUES ('$idproyecto', '$trabajador', '$tipo_trabajador', '$cargo', '$desempenio', '$sueldo_mensual', '$sueldo_diario', '$sueldo_hora')";
		
		return ejecutarConsulta($sql);
			
	}

	//Implementamos un método para editar registros
	public function editar( $idtrabajador_por_proyecto,$trabajador, $tipo_trabajador, $cargo, $desempenio, $sueldo_mensual, $sueldo_diario, $sueldo_hora )
	{
		$sql="UPDATE trabajador_por_proyecto SET  idtrabajador='$trabajador', 
    tipo_trabajador='$tipo_trabajador', cargo='$cargo', desempenio='$desempenio', sueldo_mensual='$sueldo_mensual', 
    sueldo_diario='$sueldo_diario',	sueldo_hora='$sueldo_hora' WHERE idtrabajador_por_proyecto='$idtrabajador_por_proyecto'";	
		
		return ejecutarConsulta($sql);
		
	}

	//Implementar un método para listar los registros
	public function listar_tbla_principal($nube_idproyecto)
	{
		$sql="SELECT t.idtrabajador, t.nombres, t.tipo_documento, t.numero_documento, t.cuenta_bancaria_format AS cuenta_bancaria, t.cci, t.imagen_perfil, t.telefono,
		tpp.desempenio, tpp.sueldo_mensual, tpp.sueldo_diario, tpp.sueldo_hora, tpp.estado, tpp.idtrabajador_por_proyecto, tpp.estado, 
		tpp.fecha_inicio, tpp.fecha_fin, tpp.cantidad_dias, b.nombre as banco, ct.nombre AS cargo, tt.nombre AS tipo
		FROM trabajador_por_proyecto as tpp, cargo_trabajador AS ct, tipo_trabajador AS tt, trabajador as t, proyecto AS p, bancos AS b
		WHERE tpp.idproyecto = p.idproyecto AND tpp.idproyecto = '$nube_idproyecto'   AND tpp.idtrabajador = t.idtrabajador AND 
		t.idbancos = b.idbancos AND tpp.idcargo_trabajador = ct.idcargo_trabajador AND ct.idtipo_trabjador = tt.idtipo_trabajador 
		AND tt.nombre != 'Obrero';";
		return ejecutarConsulta($sql);		
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar_fechas_mes($idtrabajador_x_proyecto)
	{
		$sql="SELECT idfechas_mes_pagos_administrador, idtrabajador_por_proyecto, fecha_inicial, fecha_final, nombre_mes, cant_dias_mes, cant_dias_laborables, sueldo_mensual, monto_x_mes, estado
		FROM fechas_mes_pagos_administrador WHERE idtrabajador_por_proyecto = '$idtrabajador_x_proyecto' ;";

		return ejecutarConsultaArray($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function listar_pagos_x_mes($idfechas_mes_pagos)
	{
		$sql="SELECT idpagos_x_mes_administrador, idfechas_mes_pagos_administrador, cuenta_deposito, forma_de_pago, monto, baucher, recibos_x_honorarios, descripcion, estado
		FROM pagos_x_mes_administrador WHERE idfechas_mes_pagos_administrador = '$idfechas_mes_pagos' ";

		return ejecutarConsulta($sql);
	}

  	//Seleccionar Trabajador Select2
	public function select2_trabajador()
	{
		$sql="SELECT idtrabajador as id, nombres as nombre, tipo_documento as documento, numero_documento FROM trabajador WHERE estado='1';";
		return ejecutarConsulta($sql);		
	}

}

?>