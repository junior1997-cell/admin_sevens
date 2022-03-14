<?php
ob_start();
if (strlen(session_id()) < 1){
	session_start();//Validamos si existe o no la sesión
}
require_once "../modelos/AllMaquinaria.php";

$all_maquinaria=new Allmaquinarias();

//$idmaquinaria,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$c_bancaria,$c_detracciones,$banco,$titular_cuenta	
$idmaquinaria		= isset($_POST["idmaquinaria"])? limpiarCadena($_POST["idmaquinaria"]):"";
$nombre_maquina		= isset($_POST["nombre_maquina"])? limpiarCadena($_POST["nombre_maquina"]):"";
$codigo_m 		    = isset($_POST["codigo_m"])? limpiarCadena($_POST["codigo_m"]):"";
$proveedor 		    = isset($_POST["proveedor"])? limpiarCadena($_POST["proveedor"]):"";
$tipo 		    	= isset($_POST["tipo"])? limpiarCadena($_POST["tipo"]):"";

switch ($_GET["op"]){
	case 'guardaryeditar':
		if (!isset($_SESSION["nombre"])) {

		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.

		} else {
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['recurso']==1)
			{
				$clavehash="";


				if (empty($idmaquinaria)){
					$rspta=$all_maquinaria->insertar($nombre_maquina,$codigo_m,$proveedor,$tipo);
					echo $rspta ? "ok" : "No se pudieron registrar todos los datos";
				}
				else {
					$rspta=$all_maquinaria->editar($idmaquinaria,$nombre_maquina,$codigo_m,$proveedor,$tipo);
					echo $rspta ? "ok" : "No se pudo actualizar";
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
			if ($_SESSION['recurso']==1)
			{
				$rspta=$all_maquinaria->desactivar($idmaquinaria);
 				echo $rspta ? "Máquina o equipo Desactivada" : "Máquina o equipo no se puede desactivar";
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
			if ($_SESSION['recurso']==1)
			{
				$rspta=$all_maquinaria->activar($idmaquinaria);
 				echo $rspta ? "Máquina o equipo activada" : "Máquina o equipo se puede activar";
			//Fin de las validaciones de acceso
			}
			else
			{
		  	require 'noacceso.php';
			}
		}		
	break;

	case 'eliminar':
		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['recurso']==1)
			{
				$rspta=$all_maquinaria->eliminar($idmaquinaria);
 				echo $rspta ? "ok" : "Máquina o equipo se puede eliminar";
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
			if ($_SESSION['recurso']==1)
			{
				$rspta=$all_maquinaria->mostrar($idmaquinaria);
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

	case 'listar_maquinas':
		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['recurso']==1)
			{
				$tipo = '1';
				$rspta=$all_maquinaria->listar($tipo);
				$cont=1;
		 		//Vamos a declarar un array
		 		$data= Array();
				 //idbancos,razon_social,tipo_documento,ruc,direccion,telefono,cuenta_bancaria,cuenta_detracciones,titular_cuenta

		 		while ($reg=$rspta->fetch_object()){
		 			$data[]=array(
						"0"=>$cont++,
		 				"1"=>($reg->estado)?'<button class="btn btn-warning btn-sm" onclick="mostrar('.$reg->idmaquinaria.')"><i class="fas fa-pencil-alt"></i></button>'.
							 ' <button class="btn btn-danger btn-sm" onclick="eliminar('.$reg->idmaquinaria.')"><i class="fas fa-skull-crossbones"></i></button>':
							 '<button class="btn btn-warning btn-sm" onclick="mostrar('.$reg->idmaquinaria.')"><i class="fa fa-pencil-alt"></i></button>'.
		 					' <button class="btn btn-primary btn-sm" onclick="activar('.$reg->idmaquinaria.')"><i class="fa fa-check"></i></button>',
		 				"2"=>$reg->nombre,
		 				"3"=>$reg->modelo, 
		 				"4"=>$reg->razon_social,
						"5"=>($reg->estado)?'<span class="text-center badge badge-success">Activado</span>':'<span class="text-center badge badge-danger">Desactivado</span>'
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
	case 'listar_equipos':
		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['recurso']==1)
			{
				$tipo = '2';
				$rspta=$all_maquinaria->listar($tipo);
				$cont=1;
		 		//Vamos a declarar un array
		 		$data= Array();
				 //idbancos,razon_social,tipo_documento,ruc,direccion,telefono,cuenta_bancaria,cuenta_detracciones,titular_cuenta

		 		while ($reg=$rspta->fetch_object()){
		 			$data[]=array(
						"0"=>$cont++,
		 				"1"=>($reg->estado)?'<button class="btn btn-warning btn-sm" onclick="mostrar('.$reg->idmaquinaria.')"><i class="fas fa-pencil-alt"></i></button>'.
							 ' <button class="btn btn-danger btn-sm" onclick="eliminar('.$reg->idmaquinaria.')"><i class="fas fa-skull-crossbones"></i></button>':
							 '<button class="btn btn-warning btn-sm" onclick="mostrar('.$reg->idmaquinaria.')"><i class="fa fa-pencil-alt"></i></button>'.
		 					' <button class="btn btn-primary btn-sm" onclick="activar('.$reg->idmaquinaria.')"><i class="fa fa-check"></i></button>',
		 				"2"=>$reg->nombre,
		 				"3"=>$reg->modelo,
		 				"4"=>$reg->razon_social,
						"5"=>($reg->estado)?'<span class="text-center badge badge-success">Activado</span>':'<span class="text-center badge badge-danger">Desactivado</span>'
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
	case 'select2_proveedor': 

		$rspta=$all_maquinaria->select2_proveedor();

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