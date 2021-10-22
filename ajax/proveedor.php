<?php
ob_start();
if (strlen(session_id()) < 1){
	session_start();//Validamos si existe o no la sesión
}
require_once "../modelos/proveedor.php";

$proveedor=new Proveedor();

$idproveedor_proyecto = isset($_POST["idproveedor_proyecto"])? limpiarCadena($_POST["idproveedor_proyecto"]):"";	
$idproyecto			  = isset($_POST["idproyecto"])? limpiarCadena($_POST["idproyecto"]):"";
$idproveedor		  = isset($_POST["proveedor"])? limpiarCadena($_POST["proveedor"]):"";


switch ($_GET["op"]){
	case 'guardaryeditar':
		if (!isset($_SESSION["nombre"])) {

		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.

		} else {
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['proveedor']==1)
			{
				$clavehash="";


				if (empty($idproveedor_proyecto)){
					//var_dump($idproyecto,$idproveedor);
					$rspta=$proveedor->insertar($idproyecto,$idproveedor);
					echo $rspta ? "ok" : "No se pudieron registrar todos los datos del proveedor";
				}
				else {
					$rspta=$proveedor->editar($idproveedor_proyecto,$idproveedor);
					//var_dump($idproveedor_proyecto,$idproveedor);
					echo $rspta ? "ok" : "Trabador no se pudo actualizar";
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
			if ($_SESSION['proveedor']==1)
			{
				$rspta=$proveedor->desactivar($idproveedor_proyecto);
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
			if ($_SESSION['proveedor']==1)
			{
				$rspta=$proveedor->activar($idproveedor_proyecto);
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
			if ($_SESSION['proveedor']==1)
			{
				//$idproveedor_proyectoo='1';
				$rspta=$proveedor->mostrar($idproveedor_proyecto);
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
			if ($_SESSION['proveedor']==1)
			{
				$nube_idproyecto = $_GET["nube_idproyecto"];
				$rspta=$proveedor->listar($nube_idproyecto);
		 		//Vamos a declarar un array
		 		$data= Array();
				 //idbancos,razon_social,tipo_documento,ruc,direccion,telefono,cuenta_bancaria,cuenta_detracciones,titular_cuenta
				//$parametros = '';
		 		while ($reg=$rspta->fetch_object()){
					//$parametros="'$reg->idproveedor_proyecto','$reg->idproyecto'";
		 			$data[]=array(
		 				"0"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idproveedor_proyecto .')"><i class="fas fa-pencil-alt"></i></button>'.
		 					' <button class="btn btn-danger" onclick="desactivar('.$reg->idproveedor_proyecto .')"><i class="far fa-trash-alt  "></i></button>'.
							 ' <button class="btn btn-info" onclick="ver_datos('.$reg->idproveedor_proyecto.')"><i class="far fa-eye"></i></button>':
							 '<button class="btn btn-warning" onclick="mostrar('.$reg->idproveedor_proyecto .')"><i class="fa fa-pencil-alt"></i></button>'.
		 					' <button class="btn btn-primary" onclick="activar('.$reg->idproveedor_proyecto .')"><i class="fa fa-check"></i></button>'.
							 ' <button class="btn btn-info" onclick="ver_datos('.$reg->idproveedor_proyecto.','.$reg->idproyecto.')"><i class="far fa-eye"></i></button>',
						"1"=>'<div class="user-block">
							<span class="username" style="margin-left: 0px !important;"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'. $reg->razon_social .'</p></span>
							<span class="description" style="margin-left: 0px !important;">'. $reg->tipo_documento .': '. $reg->ruc .' </span>
							</div>',
		 				"2"=>$reg->direccion,
		 				"3"=>$reg->nombre_banco,
		 				"4"=>$reg->cuenta_bancaria.' / '.$reg->cuenta_detracciones,
		 				"5"=>$reg->titular_cuenta,
		 				"6"=>($reg->estado)?'<span class="text-center badge badge-success">Activado</span>':
		 				'<span class="text-center badge badge-danger">Desactivado</span>'
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
		
	case 'ver_datos':
		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['trabajador']==1)
			{
				$idproveedor_proyecto='1';
				/*$idproyecto='1';*/
				$rspta=$proveedor->ver_datos($idproveedor_proyecto);
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

	case 'select2_proveedor': 

		$rspta=$proveedor->select2_proveedor();

		while ($reg = $rspta->fetch_object())
				{
				echo '<option value=' . $reg->idproveedor . '>' . $reg->razon_social .' - '. $reg->ruc . '</option>';
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