<?php
ob_start();
if (strlen(session_id()) < 1){
	session_start();//Validamos si existe o no la sesión
}
require_once "../modelos/Servicio.php";
require_once "../modelos/Fechas.php";

$servicios=new Servicios();

//SERVICIOS...
$idservicio 		= isset($_POST["idservicio"])? limpiarCadena($_POST["idservicio"]):"";	
$idproyecto			= isset($_POST["idproyecto"])? limpiarCadena($_POST["idproyecto"]):"";
$maquinaria 		= isset($_POST["maquinaria"])? limpiarCadena($_POST["maquinaria"]):"";
$fecha_inicio 		= isset($_POST["fecha_inicio"])? limpiarCadena($_POST["fecha_inicio"]):"";
$fecha_fin 			= isset($_POST["fecha_fin"])? limpiarCadena($_POST["fecha_fin"]):"";
$horometro_inicial 	= isset($_POST["horometro_inicial"])? limpiarCadena($_POST["horometro_inicial"]):"";
$horometro_final 	= isset($_POST["horometro_final"])? limpiarCadena($_POST["horometro_final"]):"";
$horas				= isset($_POST["horas"])? limpiarCadena($_POST["horas"]):"";
$costo_unitario 	= isset($_POST["costo_unitario"])? limpiarCadena($_POST["costo_unitario"]):"";
$costo_parcial 		= isset($_POST["costo_parcial"])? limpiarCadena($_POST["costo_parcial"]):"";
$unidad_m 		    = isset($_POST["unidad_m"])? limpiarCadena($_POST["unidad_m"]):"";
$dias 		        = isset($_POST["dias"])? limpiarCadena($_POST["dias"]):"";
$mes 		        = isset($_POST["mes"])? limpiarCadena($_POST["mes"]):"";
$descripcion 	    = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
//PAGOS...


switch ($_GET["op"]){
	/*=======================
	=========================
	//SECCION DE SERVICIOS
	=========================
	=========================*/
	case 'guardaryeditar':
		if (!isset($_SESSION["nombre"])) {

		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.

		} else {
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['servicio_maquina']==1)
			{
				$clavehash="";


				if (empty($idservicio)){
					
					$rspta=$servicios->insertar($idproyecto,$maquinaria,$fecha_inicio,$fecha_fin,$horometro_inicial,$horometro_final,$horas,$costo_unitario,$costo_parcial,$unidad_m,$dias,$mes,$descripcion);
					echo $rspta ? "ok" : "No se pudieron registrar todos los datos de servicio";
				}
				else {
					
					$rspta=$servicios->editar($idservicio,$idproyecto,$maquinaria,$fecha_inicio,$fecha_fin,$horometro_inicial,$horometro_final,$horas,$costo_unitario,$costo_parcial,$unidad_m,$dias,$mes,$descripcion);
					
					echo $rspta ? "ok" : "Servicio no se pudo actualizar";
				}
				//Fin de las validaciones de acceso
			} else {

		  		require 'noacceso.php';
			}
		}		
	break;

	case 'desactivar':
		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['servicio_maquina']==1)
			{
				$rspta=$servicios->desactivar($idservicio);
 				echo $rspta ? "Servicio Anulado" : "Servicio no se puede Anular";
			//Fin de las validaciones de acceso
			}
			else
			{
		  	require 'noacceso.php';
			}
		}		
	break;

	case 'activar':
		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['servicio_maquina']==1)
			{
				$rspta=$servicios->activar($idservicio);
 				echo $rspta ? "Servicio Restablecido" : "Servicio no se pudo Restablecido";
			//Fin de las validaciones de acceso
			}
			else
			{
		  	require 'noacceso.php';
			}
		}		
	break;

	case 'mostrar':
		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['servicio_maquina']==1)
			{
				//$idservicioo='1';
				$rspta=$servicios->mostrar($idservicio);
		 		//Codificar el resultado utilizando json
		 		echo json_encode($rspta);
			//Fin de las validaciones de acceso
			}
			else
			{
		  	require 'noacceso.php';
			}
		}		
	break;

	case 'listar':
		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['servicio_maquina']==1)
			{	
				//$_GET["nube_idproyecto"]
				$nube_idproyecto =$_GET["nube_idproyecto"];
				$rspta=$servicios->listar($nube_idproyecto);
		 		//Vamos a declarar un array
		 		$data= Array();
		 		$datos= Array();
				$monto = 0;
				$c="";
				$nombre="";
				$icon="";
		 		while ($reg=$rspta->fetch_object()){
					//$parametros="'$reg->idservicio','$reg->idproyecto'";
					$rspta2=$servicios->pago_servicio($reg->idmaquinaria,$reg->idproyecto);

					empty($rspta2)?$saldo=0:$saldo = $reg->costo_parcial-$rspta2['monto'];
					empty($rspta2['monto'])?$monto="0.00":$monto = $rspta2['monto'];
					//empty($rspta2['monto']?($monto="0.00"?$clase="dangar":$clase="warning"): ($monto = $rspta2['monto'] ? 'verdadero2' : 'falso');
					if ($saldo == $reg->costo_parcial) {

						$estado = '<span class="text-center badge badge-danger">Sin pagar</span>';
						$c="danger";
						$nombre="Pagar";
						$icon="dollar-sign";

					  } else {
		
						if ($saldo<$reg->costo_parcial && $saldo!="0" ) {
		
						  $estado = '<span class="text-center badge badge-warning">En proceso</span>';
						  $c="warning";
						  $nombre="Pagar";
						  $icon="dollar-sign";
						} else {
							if ($saldo=="0") {
								$estado = '<span class="text-center badge badge-success">Pagado</span>';
								$c="info";
								$nombre="Ver";
								$info="info";
								$icon="eye";
							}else{
								$estado = '<span class="text-center badge badge-success">Error</span>';
							}
							//$estado = '<span class="text-center badge badge-success">Terminado</span>';
						}                
					  }
					  $unidad_medida="'$reg->idmaquinaria','$reg->idproyecto','$reg->unidad_medida'";
		 			$data[]=array(
		 				"0"=>' <button class="btn btn-info" onclick="listar_detalle('.$unidad_medida.')"><i class="far fa-eye"></i></button>',
		 				"1"=>'<div class="user-block">
						 <span class="username" style="margin-left: 0px !important;"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'. $reg->maquina .'</p></span>
						 <span class="description" style="margin-left: 0px !important;">'. $reg->codigo_maquina .' </span>
						 </div>',
		 				"2"=>$reg->razon_social,		 				
		 				"3"=>$reg->unidad_medida,		 				
		 				"4"=>$reg->cantidad_veces,		 				
		 				"5"=>$reg->costo_parcial,
		 				"6"=>' <button class="btn btn-'.$c.'" onclick="listar_pagos('.$reg->idmaquinaria.','.$reg->idproyecto.')"><i class="fas fa-'.$icon.' nav-icon"></i> '.$nombre.'</button> '.'
						 <button class="btn btn-'.$c.'">'.$monto.'</button> ',
		 				"7"=>$saldo,
		 				"8"=>$estado
		 				);

		 		}
		 		$results = array(
		 			"sEcho"=>1, //Información para el datatables
		 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
		 			"iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
		 			"data"=>$data);
		 		echo json_encode($results);
			//Fin de las validaciones de acceso
			}
			else
			{
		  	require 'noacceso.php';
			}
		}
	break;
		
	case 'ver_detalle_maquina':
		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['trabajador']==1)
			{
				$idmaquinaria=$_GET["idmaquinaria"];
				$idproyecto=$_GET["idproyecto"];
				/*$idmaquinaria='1';
				$idproyecto='1';*/
				$rspta=$servicios->ver_detalle_m($idmaquinaria,$idproyecto);
				$fecha_entreg='';
				$fecha_recoj='';
				$fecha='';
				//Vamos a declarar un array
					$data= Array();
					
					while ($reg=$rspta->fetch_object()){
						//empty($fecha_recojo)?setlocale(LC_ALL,"es_ES").''.date('l d-m-Y', strtotime($reg->fecha_entrega)):$reg->fecha_entrega.'/'.$reg->fecha_recojo,
						if (empty($reg->fecha_recojo) || $reg->fecha_recojo=='0000-00-00') {
							$fechas=new FechaEs($reg->fecha_entrega);
							$dia=$fechas->getDDDD().PHP_EOL;
							$mun_dia=$fechas->getdd().PHP_EOL;
							$mes=$fechas->getMMMM().PHP_EOL;
							$anio=$fechas->getYYYY().PHP_EOL;
							$fecha_entreg="$dia, $mun_dia de $mes del $anio";
							$fecha="<b style=".'color:#1570cf;'.">$fecha_entreg</b>";
						}else{
							$fechas=new FechaEs($reg->fecha_entrega);
							//----------
							$dia=$fechas->getDDDD().PHP_EOL;
							$mun_dia=$fechas->getdd().PHP_EOL;
							$mes=$fechas->getMMMM().PHP_EOL;
							$anio=$fechas->getYYYY().PHP_EOL;
							$fecha_entreg="$dia, $mun_dia de $mes del $anio";
							//----------
							$fechas=new FechaEs($reg->fecha_recojo);
							$dia2=$fechas->getDDDD().PHP_EOL;
							$mun_dia2=$fechas->getdd().PHP_EOL;
							$mes2=$fechas->getMMMM().PHP_EOL;
							$anio2=$fechas->getYYYY().PHP_EOL;
							$fecha_recoj="$dia2, $mun_dia2 de $mes2 del $anio2";
							$fecha="<b style=".'color:#1570cf;'.">$fecha_entreg </b> / <br> <b  style=".'color:#ff0000;'.">$fecha_recoj<b>";

						}
						if (strlen($reg->descripcion) >= 20 ) { $descripcion = substr($reg->descripcion, 0, 20).'...';  } else { $descripcion = $reg->descripcion; }
						
						$tool = '"tooltip"';   $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>"; 
						
						$data[]=array(
							"0"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idservicio.','.$reg->idmaquinaria.')"><i class="fas fa-pencil-alt"></i></button>'.
							' <button class="btn btn-danger" onclick="desactivar('.$reg->idservicio .','.$reg->idmaquinaria.')"><i class="far fa-trash-alt"></i></button>':
							'<button class="btn btn-warning" onclick="mostrar('.$reg->idservicio.','.$reg->idmaquinaria.')"><i class="fa fa-pencil-alt"></i></button>'.
							' <button class="btn btn-primary" onclick="activar('.$reg->idservicio.','.$reg->idmaquinaria.')"><i class="fa fa-check"></i></button>',
							"1"=>$fecha,
							"2"=>empty($reg->horometro_inicial) || $reg->horometro_inicial=='0.00'?'-':$reg->horometro_inicial,
							"3"=>empty($reg->horometro_final) || $reg->horometro_final=='0.00'?'-':$reg->horometro_final,
							"4"=>empty($reg->horas)|| $reg->horas=='0.00'?'-':$reg->horas,
							"5"=>empty($reg->costo_unitario) || $reg->costo_unitario=='0.00'?'-':$reg->costo_unitario,
							"6"=>empty($reg->unidad_medida)?'-':$reg->unidad_medida,
							"7"=>empty($reg->costo_parcial)?'-':$reg->costo_parcial,
							"8"=>empty($reg->descripcion)?'-':'<div data-toggle="tooltip" data-original-title="'.$reg->descripcion.'">'.$descripcion.'</div>',
							"9"=>($reg->estado)?'<span class="text-center badge badge-success">Activado</span>'.$toltip:
							'<span class="text-center badge badge-danger">Desactivado</span>'.$toltip
							);
					}
					$results = array(
						"sEcho"=>1, //Información para el datatables
						"iTotalRecords"=>count($data), //enviamos el total registros al datatable
						"iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
						"data"=>$data);
					echo json_encode($results);
			//Fin de las validaciones de acceso
			}
			else
			{
		  	require 'noacceso.php';
			}
		}		
	break;

	case 'suma_horas_costoparcial':

		$idmaquinaria=$_POST["idmaquinaria"];
		$idproyecto=$_POST["idproyecto"];
		//$idmaquinaria='1';
		//$idproyecto='1';

		$rspta=$servicios->suma_horas_costoparcial($idmaquinaria,$idproyecto);
		//Codificar el resultado utilizando json
		echo json_encode($rspta);
		//Fin de las validaciones de acceso


	break;

	case 'select2_servicio': 

		$rspta=$servicios->select2_servicio();

		while ($reg = $rspta->fetch_object())
			{
			echo '<option value=' . $reg->idmaquinaria . '>' . $reg->nombre .' : '. $reg->codigo_maquina .' ---> ' .$reg->nombre_proveedor.'</option>';
			}
	break;

	/**
	 * ====================================
	 *SECCION PAGO MAQUINARIA
	 * ====================================
	 */
	case 'mostrar_datos_para_reg_p':

		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['servicio_maquina']==1)
			{
				//$idservicioo='1';
				$rspta=$servicios->mostrar_datos_pago($idservicio);
		 		//Codificar el resultado utilizando json
		 		echo json_encode($rspta);
			//Fin de las validaciones de acceso
			}
			else
			{
		  	require 'noacceso.php';
			}
		}		
	break;


	case 'salir':
		//Limpiamos las variables de sesión   
        session_unset();
        //Destruìmos la sesión
        session_destroy();
        //Redireccionamos al login
        header("Location: ../index.php");

	break;
}
ob_end_flush();
?>