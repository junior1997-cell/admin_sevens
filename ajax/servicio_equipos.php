<?php
ob_start();
if (strlen(session_id()) < 1){
	session_start();//Validamos si existe o no la sesión
}
require_once "../modelos/servicio_equipos.php";
require_once "../modelos/Fechas.php";

$servicioequipo= new ServicioEquipo();

//============SERVICIOS========================
$idservicio 		= isset($_POST["idservicio"])? limpiarCadena($_POST["idservicio"]):"";	
$idproyecto			= isset($_POST["idproyecto"])? limpiarCadena($_POST["idproyecto"]):"";
$maquinaria 		= isset($_POST["maquinaria"])? limpiarCadena($_POST["maquinaria"]):"";
$fecha_inicio 		= isset($_POST["fecha_inicio"])? limpiarCadena($_POST["fecha_inicio"]):"";
$fecha_fin 			= isset($_POST["fecha_fin"])? limpiarCadena($_POST["fecha_fin"]):"";
$horometro_inicial 	= isset($_POST["horometro_inicial"])? limpiarCadena($_POST["horometro_inicial"]):"";
$horometro_final 	= isset($_POST["horometro_final"])? limpiarCadena($_POST["horometro_final"]):"";
$horas				= isset($_POST["horas"])? limpiarCadena($_POST["horas"]):"";
$costo_unitario 	= isset($_POST["costo_unitario"])? limpiarCadena($_POST["costo_unitario"]):"";
$cantidad 		    = isset($_POST["cantidad"])? limpiarCadena($_POST["cantidad"]):"";
$costo_parcial 		= isset($_POST["costo_parcial"])? limpiarCadena($_POST["costo_parcial"]):"";
$unidad_m 		    = isset($_POST["unidad_m"])? limpiarCadena($_POST["unidad_m"]):"";
$dias 		        = isset($_POST["dias"])? limpiarCadena($_POST["dias"]):"";
$mes 		        = isset($_POST["mes"])? limpiarCadena($_POST["mes"]):"";
$descripcion 	    = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
//============PAGOS========================
$beneficiario_pago   = isset($_POST["beneficiario_pago"])? limpiarCadena($_POST["beneficiario_pago"]):"";
$forma_pago          = isset($_POST["forma_pago"])? limpiarCadena($_POST["forma_pago"]):"";
$tipo_pago           = isset($_POST["tipo_pago"])? limpiarCadena($_POST["tipo_pago"]):"";
$cuenta_destino_pago = isset($_POST["cuenta_destino_pago"])? limpiarCadena($_POST["cuenta_destino_pago"]):"";
$banco_pago          = isset($_POST["banco_pago"])? limpiarCadena($_POST["banco_pago"]):"";
$titular_cuenta_pago = isset($_POST["titular_cuenta_pago"])? limpiarCadena($_POST["titular_cuenta_pago"]):"";
$fecha_pago          = isset($_POST["fecha_pago"])? limpiarCadena($_POST["fecha_pago"]):"";
$monto_pago          = isset($_POST["monto_pago"])? limpiarCadena($_POST["monto_pago"]):"";
$numero_op_pago      = isset($_POST["numero_op_pago"])? limpiarCadena($_POST["numero_op_pago"]):"";
$descripcion_pago    = isset($_POST["descripcion_pago"])? limpiarCadena($_POST["descripcion_pago"]):"";
$id_maquinaria_pago  = isset($_POST["id_maquinaria_pago"])? limpiarCadena($_POST["id_maquinaria_pago"]):"";
$idpago_servicio     = isset($_POST["idpago_servicio"])? limpiarCadena($_POST["idpago_servicio"]):"";
$idproyecto_pago     = isset($_POST["idproyecto_pago"])? limpiarCadena($_POST["idproyecto_pago"]):"";

$imagen1			 = isset($_POST["foto1"])? limpiarCadena($_POST["foto1"]):"";
//============factura========================
$idproyectof         = isset($_POST["idproyectof"])? limpiarCadena($_POST["idproyectof"]):"";
$idfactura           = isset($_POST["idfactura"])? limpiarCadena($_POST["idfactura"]):"";
$idmaquina           = isset($_POST["idmaquina"])? limpiarCadena($_POST["idmaquina"]):"";
$codigo              = isset($_POST["codigo"])? limpiarCadena($_POST["codigo"]):"";
$monto               = isset($_POST["monto"])? limpiarCadena($_POST["monto"]):"";
$fecha_emision       = isset($_POST["fecha_emision"])? limpiarCadena($_POST["fecha_emision"]):"";
$descripcion_f       = isset($_POST["descripcion_f"])? limpiarCadena($_POST["descripcion_f"]):"";

$imagen2             = isset($_POST["foto2"])? limpiarCadena($_POST["foto2"]):"";
//$idproyectof,$idmaquina,$codigo,$monto,$fecha_emision,$descripcion_f,$foto2
switch ($_GET["op"]){
	/*=====ECCION DE SERVICIOS=========*/
	case 'guardaryeditar':
		if (!isset($_SESSION["nombre"])) {

		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.

		} else {
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['servicio_equipo']==1)
			{
				$clavehash="";


				if (empty($idservicio)){
					
					$rspta=$servicioequipo->insertar($idproyecto,$maquinaria,$fecha_inicio,$fecha_fin,$horometro_inicial,$horometro_final,$horas,$costo_unitario,$costo_parcial,$unidad_m,$dias,$mes,$descripcion,$cantidad);
					echo $rspta ? "ok" : "No se pudieron registrar todos los datos de servicio";
				}
				else {
					
					$rspta=$servicioequipo->editar($idservicio,$idproyecto,$maquinaria,$fecha_inicio,$fecha_fin,$horometro_inicial,$horometro_final,$horas,$costo_unitario,$costo_parcial,$unidad_m,$dias,$mes,$descripcion,$cantidad);
					
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
			if ($_SESSION['servicio_equipo']==1)
			{
				$rspta=$servicioequipo->desactivar($idservicio);
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
			if ($_SESSION['servicio_equipo']==1)
			{
				$rspta=$servicioequipo->activar($idservicio);
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
			if ($_SESSION['servicio_equipo']==1)
			{
				//$idservicioo='1';
				$rspta=$servicioequipo->mostrar($idservicio);
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
			if ($_SESSION['servicio_equipo']==1)
			{	
				//$_GET["nube_idproyecto"]
				$nube_idproyecto =$_GET["nube_idproyecto"];
				$rspta=$servicioequipo->listar($nube_idproyecto);
		 		//Vamos a declarar un array
				 setlocale(LC_MONETARY, 'en_US');
		 		$data= Array();
		 		$datos= Array();
				$monto = 0;
				$c="";
				$nombre="";
				$icon="";
				//$c_parcial = 0;
		 		while ($reg=$rspta->fetch_object()){
					//$parametros="'$reg->idservicio','$reg->idproyecto'";
					$rspta2=$servicioequipo->pago_servicio($reg->idmaquinaria,$reg->idproyecto);

					empty($rspta2)?$saldo=0:$saldo = $reg->costo_parcial-$rspta2['monto'];
					empty($rspta2['monto'])?$monto="0.00":$monto = $rspta2['monto'];
					//empty($rspta2['monto']?($monto="0.00"?$clase="dangar":$clase="warning"): ($monto = $rspta2['monto'] ? 'verdadero2' : 'falso');
					//$c_parcial = number_format($reg->costo_parcial, 2, '.', ',');
					if ($saldo == $reg->costo_parcial) {

						$estado = '<span class="text-center badge badge-danger">Sin pagar</span>';
						$c="danger";
						$nombre="Pagar";
						$icon="dollar-sign";

					  } else {
		
						if ($saldo<$reg->costo_parcial && $saldo>"0" ) {
		
						  $estado = '<span class="text-center badge badge-warning">En proceso</span>';
						  $c="warning";
						  $nombre="Pagar";
						  $icon="dollar-sign";
						} else {
							if ($saldo<="0") {
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
					  $verdatos="'$reg->idmaquinaria','$reg->idproyecto','$reg->costo_parcial','$monto'";

		 			$data[]=array(
		 				"0"=>' <button class="btn btn-info" onclick="listar_detalle('.$unidad_medida.')"><i class="far fa-eye"></i></button>',
		 				"1"=>'<div class="user-block">
						 <span class="username" style="margin-left: 0px !important;"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'. $reg->maquina .'</p></span>
						 <span class="description" style="margin-left: 0px !important;">'. $reg->codigo_maquina .' </span>
						 </div>',
		 				"2"=>$reg->razon_social,		 				
		 				"3"=>$reg->unidad_medida,		 				
		 				"4"=>$reg->cantidad_veces,		 				
		 				"5"=>number_format($reg->costo_parcial, 2, '.', ','),
		 				"6"=>'<div class="text-center"> <button class="btn btn-'.$c.' btn-xs" onclick="listar_pagos('.$verdatos.')"><i class="fas fa-'.$icon.' nav-icon"></i> '.$nombre.'</button> '.'
						 <button class="btn btn-'.$c.' btn-xs">'.number_format($monto, 2, '.', ',').'</button> </div>',
		 				"7"=>number_format($saldo, 2, '.', ','),
		 				"8"=>'<center> <button class="btn btn-info" onclick="listar_facturas('.$unidad_medida.')"><i class="fas fa-file-invoice fa-lg"></i></button> </center>',
		 				"9"=>$estado
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
				$rspta=$servicioequipo->ver_detalle_m($idmaquinaria,$idproyecto);
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
							"5"=>empty($reg->costo_unitario) || $reg->costo_unitario=='0.00'?'-':number_format($reg->costo_unitario, 2, '.', ','),
							"6"=>empty($reg->unidad_medida)?'-':$reg->unidad_medida,
							"7"=>empty($reg->cantidad)?'-':$reg->cantidad,
							"8"=>empty($reg->costo_parcial)?'-':number_format($reg->costo_parcial, 2, '.', ','),
							"9"=>empty($reg->descripcion)?'-':'<div data-toggle="tooltip" data-original-title="'.$reg->descripcion.'">'.$descripcion.'</div>',
							"10"=>($reg->estado)?'<span class="text-center badge badge-success">Activado</span>'.$toltip:
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

		$rspta=$servicioequipo->suma_horas_costoparcial($idmaquinaria,$idproyecto);
		//Codificar el resultado utilizando json
		echo json_encode($rspta);
		//Fin de las validaciones de acceso


	break;

	case 'select2_servicio': 

		$rspta=$servicioequipo->select2_servicio();

		while ($reg = $rspta->fetch_object())
			{
			echo '<option value=' . $reg->idmaquinaria . '>' . $reg->nombre .' : '. $reg->codigo_maquina .' ---> ' .$reg->nombre_proveedor.'</option>';
			}
	break;

	/**
	 * ========SECCION PAGOS===================
	 */
	case 'most_datos_prov_pago':

		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['servicio_equipo']==1)
			{
				//$idservicioo='1';
				$idmaquinaria=$_POST["idmaquinaria"];
				$rspta=$servicioequipo->most_datos_prov_pago($idmaquinaria);
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

	case 'guardaryeditar_pago':
		if (!isset($_SESSION["nombre"])) {

		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.

		} else {
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['servicio_equipo']==1)
			{
					// imgen de perfil
				if (!file_exists($_FILES['foto1']['tmp_name']) || !is_uploaded_file($_FILES['foto1']['tmp_name'])) {

						$imagen1=$_POST["foto1_actual"]; $flat_img1 = false;

					} else {

						$ext1 = explode(".", $_FILES["foto1"]["name"]); $flat_img1 = true;						

						$imagen1 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

						move_uploaded_file($_FILES["foto1"]["tmp_name"], "../dist/img/vauchers_pagos/" . $imagen1);
					
				}


				if (empty($idpago_servicio)){
					
					$rspta=$servicioequipo->insertar_pago($idproyecto_pago,$beneficiario_pago,$forma_pago,$tipo_pago,$cuenta_destino_pago,$banco_pago,$titular_cuenta_pago,$fecha_pago,$monto_pago,$numero_op_pago,$descripcion_pago,$id_maquinaria_pago,$imagen1);
					echo $rspta ? "ok" : "No se pudieron registrar todos los datos de servicio";
				}
				else {
					// validamos si existe LA IMG para eliminarlo
					if ($flat_img1 == true) {

						$datos_f1 = $servicioequipo->obtenerImg($idpago_servicio);
			
						$img1_ant = $datos_f1->fetch_object()->imagen;
			
						if ($img1_ant != "") {
			
							unlink("../dist/img/vauchers_pagos/" . $img1_ant);
						}
					}
					
					$rspta=$servicioequipo->editar_pago($idpago_servicio,$idproyecto_pago,$beneficiario_pago,$forma_pago,$tipo_pago,$cuenta_destino_pago,$banco_pago,$titular_cuenta_pago,$fecha_pago,$monto_pago,$numero_op_pago,$descripcion_pago,$id_maquinaria_pago,$imagen1);
					
					echo $rspta ? "ok" : "Servicio no se pudo actualizar";
				}
				//Fin de las validaciones de acceso
			} else {

		  		require 'noacceso.php';
			}
		}		
	break;

	case 'desactivar_pagos':
		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['servicio_equipo']==1)
			{
				$rspta=$servicioequipo->desactivar_pagos($idpago_servicio);
 				echo $rspta ? "Servicio Anulado" : "Servicio no se puede Anular";
			//Fin de las validaciones de acceso
			}
			else
			{
		  	require 'noacceso.php';
			}
		}		
	break;

	case 'activar_pagos':
		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['servicio_equipo']==1)
			{
				$rspta=$servicioequipo->activar_pagos($idpago_servicio);
 				echo $rspta ? "Servicio Restablecido" : "Servicio no se pudo Restablecido";
			//Fin de las validaciones de acceso
			}
			else
			{
		  	require 'noacceso.php';
			}
		}		
	break;

	case 'listar_pagos_proveedor':
		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['servicio_equipo']==1)
			{	
				//$_GET["nube_idproyecto"]
				$idmaquinaria =$_GET["idmaquinaria"];
				$idproyecto =$_GET["idproyecto"];
				$tipopago ='Proveedor';
				//$idmaquinaria ='3';
				//$idproyecto ='2';
				$rspta=$servicioequipo->listar_pagos($idmaquinaria,$idproyecto,$tipopago);
		 		//Vamos a declarar un array
				 //$banco='';
		 		$data= Array();
				$suma=0;
				$imagen='';
		 		while ($reg=$rspta->fetch_object()){
					$suma=$suma+$reg->monto;
					if (strlen($reg->descripcion) >= 20 ) { $descripcion = substr($reg->descripcion, 0, 20).'...';  } else { $descripcion = $reg->descripcion; }
					if (strlen($reg->titular_cuenta) >= 20 ) { $titular_cuenta = substr($reg->titular_cuenta, 0, 20).'...';  } else {$titular_cuenta = $reg->titular_cuenta; }
					empty($reg->imagen)?$imagen='<div><center><a type="btn btn-danger" class=""><i class="far fa-sad-tear fa-2x"></i></a></center></div>':$imagen='<div><center><a type="btn btn-danger" class=""  href="#" onclick="ver_modal_vaucher('."'".$reg->imagen."'".')"><i class="fas fa-file-invoice-dollar fa-2x"></i></a></center></div>';
					$tool = '"tooltip"';   $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>"; 
		 			$data[]=array(
		 				"0"=>($reg->estado)?'<button class="btn btn-warning btn-sm" onclick="mostrar_pagos('.$reg->idpago_servicio.','.$reg->id_maquinaria.')"><i class="fas fa-pencil-alt"></i></button>'.
						 ' <button class="btn btn-danger btn-sm" onclick="desactivar_pagos('.$reg->idpago_servicio.','.$reg->id_maquinaria.')"><i class="far fa-trash-alt"></i></button>':
						 '<button class="btn btn-warning btn-sm" onclick="mostrar_pagos('.$reg->idpago_servicio.','.$reg->id_maquinaria.')"><i class="fa fa-pencil-alt"></i></button>'.
						 ' <button class="btn btn-primary btn-sm" onclick="activar_pagos('.$reg->idpago_servicio.','.$reg->id_maquinaria.')"><i class="fa fa-check"></i></button>',
		 				"1"=>$reg->forma_pago,	 				
		 				"2"=>$reg->beneficiario,		 				
		 				"3"=>$reg->cuenta_destino,		 				
		 				"4"=>$reg->banco,
		 				"5"=>'<div data-toggle="tooltip" data-original-title="'.$reg->titular_cuenta.'">'.$titular_cuenta.'</div>',
		 				"6"=>$reg->fecha_pago,
		 				"7"=>empty($reg->descripcion)?'-':'<div data-toggle="tooltip" data-original-title="'.$reg->descripcion.'">'.$descripcion.'</div>',
		 				"8"=>$reg->numero_operacion,
		 				"9"=>number_format($reg->monto, 2, '.', ','),
						"10"=>$imagen,
					   	"11"=>($reg->estado)?'<span class="text-center badge badge-success">Activado</span>'.$toltip:
						 '<span class="text-center badge badge-danger">Desactivado</span>'.$toltip
		 				);

		 		}
				//$suma=array_sum($rspta->fetch_object()->monto);
		 		$results = array(
		 			"sEcho"=>1, //Información para el datatables
		 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
		 			"iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
		 			"data"=>$data,
					"suma"=>$suma);
		 		echo json_encode($results);
			//Fin de las validaciones de acceso
			}
			else
			{
		  	require 'noacceso.php';
			}
		}
	break;
	case 'listar_pagos_detraccion':
		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['servicio_equipo']==1)
			{	
				//$_GET["nube_idproyecto"]
				$idmaquinaria =$_GET["idmaquinaria"];
				$idproyecto =$_GET["idproyecto"];
				$tipopago ='Detraccion';
				//$idmaquinaria ='3';
				//$idproyecto ='2';
				$rspta=$servicioequipo->listar_pagos($idmaquinaria,$idproyecto,$tipopago);
		 		//Vamos a declarar un array
				 //$banco='';
		 		$data= Array();
				$suma=0;
				$imagen='';
		 		while ($reg=$rspta->fetch_object()){
					$suma=$suma+$reg->monto;
					if (strlen($reg->descripcion) >= 20 ) { $descripcion = substr($reg->descripcion, 0, 20).'...';  } else { $descripcion = $reg->descripcion; }
					if (strlen($reg->titular_cuenta) >= 20 ) { $titular_cuenta = substr($reg->titular_cuenta, 0, 20).'...';  } else {$titular_cuenta = $reg->titular_cuenta; }
					empty($reg->imagen)?$imagen='<div><center><a type="btn btn-danger" class=""><i class="far fa-sad-tear fa-2x"></i></a></center></div>':$imagen='<div><center><a type="btn btn-danger" class=""  href="#" onclick="ver_modal_vaucher('."'".$reg->imagen."'".')"><i class="fas fa-file-invoice-dollar fa-2x"></i></a></center></div>';
					$tool = '"tooltip"';   $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>"; 
		 			$data[]=array(
		 				"0"=>($reg->estado)?'<button class="btn btn-warning btn-sm" onclick="mostrar_pagos('.$reg->idpago_servicio.','.$reg->id_maquinaria.')"><i class="fas fa-pencil-alt"></i></button>'.
						 ' <button class="btn btn-danger btn-sm" onclick="desactivar_pagos('.$reg->idpago_servicio.','.$reg->id_maquinaria.')"><i class="far fa-trash-alt"></i></button>':
						 '<button class="btn btn-warning btn-sm" onclick="mostrar_pagos('.$reg->idpago_servicio.','.$reg->id_maquinaria.')"><i class="fa fa-pencil-alt"></i></button>'.
						 ' <button class="btn btn-primary btn-sm" onclick="activar_pagos('.$reg->idpago_servicio.','.$reg->id_maquinaria.')"><i class="fa fa-check"></i></button>',
		 				"1"=>$reg->forma_pago,	 				
		 				"2"=>$reg->beneficiario,		 				
		 				"3"=>$reg->cuenta_destino,		 				
		 				"4"=>$reg->banco,
		 				"5"=>'<div data-toggle="tooltip" data-original-title="'.$reg->titular_cuenta.'">'.$titular_cuenta.'</div>',
		 				"6"=>$reg->fecha_pago,
		 				"7"=>empty($reg->descripcion)?'-':'<div data-toggle="tooltip" data-original-title="'.$reg->descripcion.'">'.$descripcion.'</div>',
		 				"8"=>$reg->numero_operacion,
		 				"9"=>number_format($reg->monto, 2, '.', ','),
						"10"=>$imagen,
					   	"11"=>($reg->estado)?'<span class="text-center badge badge-success">Activado</span>'.$toltip:
						 '<span class="text-center badge badge-danger">Desactivado</span>'.$toltip
		 				);

		 		}
				//$suma=array_sum($rspta->fetch_object()->monto);
		 		$results = array(
		 			"sEcho"=>1, //Información para el datatables
		 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
		 			"iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
		 			"data"=>$data,
					"suma"=>$suma);
		 		echo json_encode($results);
			//Fin de las validaciones de acceso
			}
			else
			{
		  	require 'noacceso.php';
			}
		}
	break;
	case 'suma_total_pagos_proveedor':

		$idmaquinaria=$_POST["idmaquinaria"];
		$idproyecto=$_POST["idproyecto"];
		$tipopago ='Proveedor';
		//$idmaquinaria='1';
		//$idproyecto='1';

		$rspta=$servicioequipo->suma_total_pagos($idmaquinaria,$idproyecto,$tipopago);
		//Codificar el resultado utilizando json
		echo json_encode($rspta);
		//Fin de las validaciones de acceso


	break;
	case 'suma_total_pagos_detracc':

		$idmaquinaria=$_POST["idmaquinaria"];
		$idproyecto=$_POST["idproyecto"];
		$tipopago ='Detraccion';
		//$idmaquinaria='1';
		//$idproyecto='1';

		$rspta=$servicioequipo->suma_total_pagos($idmaquinaria,$idproyecto,$tipopago);
		//Codificar el resultado utilizando json
		echo json_encode($rspta);
		//Fin de las validaciones de acceso


	break;
	case 'total_costo_parcial_pago':

		$idmaquinaria=$_POST["idmaquinaria"];
		$idproyecto=$_POST["idproyecto"];
		//$idmaquinaria='1';
		//$idproyecto='2';

		$rspta=$servicioequipo->total_costo_parcial_pago($idmaquinaria,$idproyecto);
		//Codificar el resultado utilizando json
		echo json_encode($rspta);
		//Fin de las validaciones de acceso


	break;
	
	case 'mostrar_pagos':
		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['servicio_equipo']==1)
			{
				//$idpago_servicio='1';
				$rspta=$servicioequipo->mostrar_pagos($idpago_servicio);
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

	/**
	 * ========SECCION FACTURAS===================
	*/
	case 'guardaryeditar_factura':
		if (!isset($_SESSION["nombre"])) {

		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.

		} else {
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['servicio_equipo']==1)
			{
					// imgen de perfil
				if (!file_exists($_FILES['foto2']['tmp_name']) || !is_uploaded_file($_FILES['foto2']['tmp_name'])) {

						$imagen2=$_POST["foto2_actual"]; $flat_img1 = false;

					} else {

						$ext1 = explode(".", $_FILES["foto2"]["name"]); $flat_img1 = true;						

						$imagen2 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

						move_uploaded_file($_FILES["foto2"]["tmp_name"], "../dist/img/facturas/" . $imagen2);
					
				}


				if (empty($idfactura)){
					
					$rspta=$servicioequipo->insertar_factura($idproyectof,$idmaquina,$codigo,$monto,$fecha_emision,$descripcion_f,$imagen2);
					echo $rspta ? "ok" : "No se pudieron registrar todos los datos de servicio";
				}
				else {
					// validamos si existe LA IMG para eliminarlo
					if ($flat_img1 == true) {

						$datos_f1 = $servicioequipo->obtenerImg($idfactura);
			
						$img1_ant = $datos_f1->fetch_object()->imagen;
			
						if ($img1_ant != "") {
			
							unlink("../dist/img/facturas/" . $img1_ant);
						}
					}
					
					$rspta=$servicioequipo->editar_factura($idfactura,$idproyectof,$idmaquina,$codigo,$monto,$fecha_emision,$descripcion_f,$imagen2);
					
					echo $rspta ? "ok" : "Servicio no se pudo actualizar";
				}
				//Fin de las validaciones de acceso
			} else {

		  		require 'noacceso.php';
			}
		}		
	break;

	case 'listar_facturas':
		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['servicio_equipo']==1)
			{	
				//$_GET["nube_idproyecto"]
				$idmaquinaria =$_GET["idmaquinaria"];
				$idproyecto =$_GET["idproyecto"];
				//$idmaquinaria ='3';
				//$idproyecto ='2';
				$rspta=$servicioequipo->listar_facturas($idmaquinaria,$idproyecto);
		 		//Vamos a declarar un array
				 //$banco='';
		 		$data= Array();
				$suma=0;
				$imagen='';
		 		while ($reg=$rspta->fetch_object()){
					$suma=$suma+$reg->monto;
					if (strlen($reg->descripcion) >= 20 ) { $descripcion = substr($reg->descripcion, 0, 20).'...';  } else { $descripcion = $reg->descripcion; }
					empty($reg->imagen)?$imagen='<div><center><a type="btn btn-danger" class=""><i class="far fa-sad-tear fa-2x"></i></a></center></div>':$imagen='<div><center><a type="btn btn-danger" class=""  href="#" onclick="ver_modal_factura('."'".$reg->imagen."'".')"><i class="fas fa-file-invoice fa-2x"></i></a></center></div>';
					$tool = '"tooltip"';   $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>"; 
		 			$data[]=array(
		 				"0"=>($reg->estado)?'<button class="btn btn-warning btn-sm" onclick="mostrar_factura('.$reg->idfactura.')"><i class="fas fa-pencil-alt"></i></button>'.
						 ' <button class="btn btn-danger btn-sm" onclick="desactivar_factura('.$reg->idfactura.')"><i class="far fa-trash-alt"></i></button>':
						 '<button class="btn btn-warning btn-sm" onclick="mostrar_factura('.$reg->idfactura.')"><i class="fa fa-pencil-alt"></i></button>'.
						 ' <button class="btn btn-primary btn-sm" onclick="activar_factura('.$reg->idfactura.')"><i class="fa fa-check"></i></button>',
		 				"1"=>$reg->codigo,	 				
		 				"2"=>$reg->fecha_emision,		 				
		 				"3"=>empty($reg->descripcion)?'-':'<div data-toggle="tooltip" data-original-title="'.$reg->descripcion.'">'.$descripcion.'</div>',
		 				"4"=>number_format($reg->monto, 2, '.', ','),
						"5"=>$imagen,
					   	"6"=>($reg->estado)?'<span class="text-center badge badge-success">Activado</span>'.$toltip:
						 '<span class="text-center badge badge-danger">Desactivado</span>'.$toltip
		 				);

		 		}
				//$suma=array_sum($rspta->fetch_object()->monto);
		 		$results = array(
		 			"sEcho"=>1, //Información para el datatables
		 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
		 			"iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
		 			"data"=>$data,
					"suma"=>$suma);
		 		echo json_encode($results);
			//Fin de las validaciones de acceso
			}
			else
			{
		  	require 'noacceso.php';
			}
		}
	break;

	case 'desactivar_factura':
		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['servicio_equipo']==1)
			{
				$rspta=$servicioequipo->desactivar_factura($idfactura);
 				echo $rspta ? "Servicio Anulado" : "Servicio no se puede Anular";
			//Fin de las validaciones de acceso
			}
			else
			{
		  	require 'noacceso.php';
			}
		}		
	break;

	case 'activar_factura':
		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['servicio_equipo']==1)
			{
				$rspta=$servicioequipo->activar_factura($idfactura);
 				echo $rspta ? "Servicio Restablecido" : "Servicio no se pudo Restablecido";
			//Fin de las validaciones de acceso
			}
			else
			{
		  	require 'noacceso.php';
			}
		}		
	break;
	
	case 'mostrar_factura':
		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['servicio_equipo']==1)
			{
				//$idpago_servicio='1';
				$rspta=$servicioequipo->mostrar_factura($idfactura);
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

	case 'total_monto_f':

		$idmaquinaria=$_POST["idmaquinaria"];
		$idproyecto=$_POST["idproyecto"];
		//$idmaquinaria='1';
		//$idproyecto='1';

		$rspta=$servicioequipo->total_monto_f($idmaquinaria,$idproyecto);
		//Codificar el resultado utilizando json
		echo json_encode($rspta);
		//Fin de las validaciones de acceso


	break;

	case 'total_costo_parcial':

		$idmaquinaria=$_POST["idmaquinaria"];
		$idproyecto=$_POST["idproyecto"];
		//$idmaquinaria='1';
		//$idproyecto='1';

		$rspta=$servicioequipo->total_costo_parcial($idmaquinaria,$idproyecto);
		//Codificar el resultado utilizando json
		echo json_encode($rspta);
		//Fin de las validaciones de acceso


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