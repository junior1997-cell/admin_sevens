<?php
ob_start();
if (strlen(session_id()) < 1){
	session_start();//Validamos si existe o no la sesión
}
require_once "../modelos/proveedor.php";

$proveedor=new Proveedor();

//$idproveedor,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$c_bancaria,$c_detracciones,$banco,$titular_cuenta	

$idproveedor		= isset($_POST["idproveedor"])? limpiarCadena($_POST["idproveedor"]):"";
$nombre 		    = isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$tipo_documento	    = isset($_POST["tipo_documento"])? limpiarCadena($_POST["tipo_documento"]):"";
$num_documento	    = isset($_POST["num_documento"])? limpiarCadena($_POST["num_documento"]):"";
$direccion		    = isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
$telefono		    = isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
$c_bancaria		    = isset($_POST["c_bancaria"])? limpiarCadena($_POST["c_bancaria"]):"";
$c_detracciones		= isset($_POST["c_detracciones"])? limpiarCadena($_POST["c_detracciones"]):"";
$banco			    = isset($_POST["banco"])? limpiarCadena($_POST["banco"]):"";
$titular_cuenta		= isset($_POST["titular_cuenta"])? limpiarCadena($_POST["titular_cuenta"]):"";



switch ($_GET["op"]){
	case 'guardaryeditar':
		if (!isset($_SESSION["nombre"])) {

		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.

		} else {
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['proveedor']==1)
			{
				$clavehash="";


				if (empty($idproveedor)){
					$rspta=$proveedor->insertar($nombre,$tipo_documento,$num_documento,$direccion,$telefono,$c_bancaria,$c_detracciones,$banco,$titular_cuenta);
					echo $rspta ? "ok" : "No se pudieron registrar todos los datos del usuario";
				}
				else {
					$rspta=$proveedor->editar($idproveedor,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$c_bancaria,$c_detracciones,$banco,$titular_cuenta);
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
				$rspta=$proveedor->desactivar($idproveedor);
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
				$rspta=$proveedor->activar($idproveedor);
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
				$rspta=$proveedor->mostrar($idproveedor);
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
				$rspta=$proveedor->listar();
		 		//Vamos a declarar un array
		 		$data= Array();
				 //idbancos,razon_social,tipo_documento,ruc,direccion,telefono,cuenta_bancaria,cuenta_detracciones,titular_cuenta

		 		while ($reg=$rspta->fetch_object()){
		 			$data[]=array(
		 				"0"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idproveedor.')"><i class="fas fa-pencil-alt"></i></button>'.
		 					' <button class="btn btn-danger" onclick="desactivar('.$reg->idproveedor.')"><i class="far fa-trash-alt  "></i></button>':
							 '<button class="btn btn-warning" onclick="mostrar('.$reg->idproveedor.')"><i class="fa fa-pencil-alt"></i></button>'.
		 					' <button class="btn btn-primary" onclick="activar('.$reg->idproveedor.')"><i class="fa fa-check"></i></button>',
						"1"=>'<div class="user-block">
							<span class="username" style="margin-left: 0px !important;"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'. $reg->razon_social .'</p></span>
							<span class="description" style="margin-left: 0px !important;">'. $reg->tipo_documento .': '. $reg->ruc .' </span>
							</div>',
		 				"2"=>$reg->direccion,
		 				"3"=>$reg->cuenta_bancaria.' / '.$reg->cuenta_detracciones,
		 				"4"=>$reg->titular_cuenta,
		 				"5"=>($reg->estado)?'<span class="text-center badge badge-success">Activado</span>':
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