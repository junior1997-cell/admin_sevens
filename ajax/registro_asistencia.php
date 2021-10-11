<?php
ob_start();
if (strlen(session_id()) < 1){
	session_start();//Validamos si existe o no la sesión
}
require_once "../modelos/registro_asistencia.php";

$asist_trabajador=new Asistencia_trabajador();

//$idasistencia_trabajador,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$c_bancaria,$c_detracciones,$banco,$titular_cuenta	

$idasistencia_trabajador		= isset($_POST["idasistencia"])? limpiarCadena($_POST["idasistencia"]):"";
$trabajador 		    = isset($_POST["trabajador"])? limpiarCadena($_POST["trabajador"]):"";
$horas_trabajo_dia	    = isset($_POST["horas_tabajo"])? limpiarCadena($_POST["horas_tabajo"]):"";   



switch ($_GET["op"]){
	case 'guardaryeditar':
		if (!isset($_SESSION["nombre"])) {

		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.

		} else {
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['acceso']==1)
			{
				$horas_acumuladas='';
				$horas_trabajo='';
				$sabatical='';
				$pago_dia='';
				$horas_extras='';
				$pago_horas_extras='';
				$horas_desglose = substr($horas_trabajo_dia, 0, 2).'.'.(floatval(substr($horas_trabajo_dia, 3, 5))*100)/60;


				$sueldoxhora_trab=$asist_trabajador->sueldoxhora($trabajador);
				$datos=$asist_trabajador->horas_acumulada($trabajador);

				if ($datos==NULL) {
					if (floatval($horas_desglose)>8) {
						$horas_extras=floatval($horas_desglose)-8;
						$pago_horas_extras=$horas_extras*$sueldoxhora_trab['sueldo_hora'];
						$horas_trabajo=8;
					}else{
						$horas_extras=0;
						$pago_horas_extras=0;
						$horas_trabajo=floatval($horas_desglose);
					}
					$sabatical=0;
					$pago_dia=floatval($horas_trabajo)*$sueldoxhora_trab['sueldo_hora'];

				}else{
					$horas_acumuladas=floatval($horas_desglose)+$datos['horas_trabajo'];

					$caculamos = floatval( substr($horas_acumuladas/44, 0, 1));

					if ( $caculamos == $datos['sabatical'] && $horas_acumuladas < 44) {

						$sabatical=0;

					}else {

						if ( $caculamos == $datos['sabatical'] && $horas_acumuladas >= 44) {

							$sabatical=0;

						}else {

							$sabatical=1;
						}						 
					}

					if (floatval($horas_desglose)>8) {
						$horas_extras=floatval($horas_desglose)-8;
						$pago_horas_extras=$horas_extras*$sueldoxhora_trab['sueldo_hora'];
						$horas_trabajo=8;
					}else{
						$horas_extras=0;
						$pago_horas_extras=0;
						$horas_trabajo=floatval($horas_desglose);
					}
					$pago_dia=floatval($horas_desglose)*$sueldoxhora_trab['sueldo_hora'];
				}

				// var_dump($sabatical,substr($horas_acumuladas/44, 0, 1));die;

				if (empty($idasistencia_trabajador)){
					$rspta=$asist_trabajador->insertar($trabajador,$horas_trabajo,$pago_dia,$horas_extras,$pago_horas_extras,$sabatical);
					echo $rspta ? "ok" : "No se pudieron registrar todos los datos del usuario";
				}
				else {
					$rspta=$asist_trabajador->editar($idasistencia_trabajador,$trabajador,$horas_trabajo,$pago_dia,$horas_extras,$pago_horas_extras,$sabatical);
					echo $rspta ? "ok" : "Trabador no se pudo actualizar";
				}
				echo "hora acumulada: $horas_acumuladas, sabatical: $sabatical, divicion: $caculamos "." bd_sabatico".$datos['sabatical'];
				$horas_acumuladas='';
				$horas_trabajo='';
				$sabatical='';
				$pago_dia='';
				$horas_extras='';
				$pago_horas_extras='';
				$horas_desglose ='';
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
			if ($_SESSION['acceso']==1)
			{
				$rspta=$asist_trabajador->desactivar($idasistencia_trabajador);
 				echo $rspta ? "Usuario Desactivado" : "Usuario no se puede desactivar";
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
			if ($_SESSION['acceso']==1)
			{
				$rspta=$asist_trabajador->activar($idasistencia_trabajador);
 				echo $rspta ? "Usuario activado" : "Usuario no se puede activar";
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
			if ($_SESSION['acceso']==1)
			{
				$rspta=$asist_trabajador->mostrar($idasistencia_trabajador);
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
			if ($_SESSION['acceso']==1)
			{
				$rspta=$asist_trabajador->listar();
		 		//Vamos a declarar un array
		 		$data= Array();
				 //idbancos,razon_social,tipo_documento,ruc,direccion,telefono,cuenta_bancaria,cuenta_detracciones,titular_cuenta
					$jonal_diario = '';
		 		while ($reg=$rspta->fetch_object()){
					//$jonal_diario=$reg->sueldo_hora*($reg->total_horas+$reg->horas_extras);
					$jonal_diario=$reg->sueldo_hora*8;
		 			$data[]=array(
		 				"0"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idtrabajador.')"><i class="fas fa-pencil-alt"></i></button>'.
		 					' <button class="btn btn-danger" onclick="desactivar('.$reg->idtrabajador.')"><i class="far fa-trash-alt  "></i></button>':
							 '<button class="btn btn-warning" onclick="mostrar('.$reg->idtrabajador.')"><i class="fa fa-pencil-alt"></i></button>'.
		 					' <button class="btn btn-primary" onclick="activar('.$reg->idtrabajador.')"><i class="fa fa-check"></i></button>',
						"1"=>'<div class="user-block">
							<span class="username" style="margin-left: 0px !important;"><p class="text-primary"style="margin-bottom: 0.2rem !important"; ><b 
							style="color: #000000 !important;">'. $reg->cargo .' : </b> '. $reg->nombre .'</p></span>
							<span class="description" style="margin-left: 0px !important;">'. $reg->tipo_doc .': '. $reg->num_doc .' </span>
							</div>',
		 				"2"=>$reg->total_horas,
		 				"3"=>$reg->horas_extras,
		 				"4"=>$reg->sueldo_mensual,
		 				"5"=>$reg->sueldo_hora,
		 				"6"=>$jonal_diario,
		 				"7"=>$reg->total_sabatical,
		 				"8"=>($reg->estado)?'<span class="text-center badge badge-success">Activado</span>':
		 				'<span class="text-center badge badge-danger">Desactivado</span>'
		 				);

						 $jonal_diario=0;
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

	case 'select2Trabajador': 

		$rspta = $asist_trabajador->select2_trabajador();

			while ($reg = $rspta->fetch_object())
				{
				echo '<option value=' . $reg->id . '>'.$reg->cargo .' - '. $reg->nombre .' - '. $reg->numero_documento . '</option>';
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