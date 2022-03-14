<?php
ob_start();
if (strlen(session_id()) < 1){
	session_start();//Validamos si existe o no la sesión
}
require_once "../modelos/AllProveedor.php";

$proveedor=new Proveedor();

//$idproveedor,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$c_bancaria,$c_detracciones,$banco,$titular_cuenta	
$idproyecto			= isset($_POST["idproyecto"])? limpiarCadena($_POST["idproyecto"]):"";
$idproveedor		= isset($_POST["idproveedor"])? limpiarCadena($_POST["idproveedor"]):"";
$nombre 		    = isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$tipo_documento		= isset($_POST["tipo_documento"])? limpiarCadena($_POST["tipo_documento"]):"";
$num_documento	    = isset($_POST["num_documento"])? limpiarCadena($_POST["num_documento"]):"";
$direccion		    = isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
$telefono		    = isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
$c_bancaria		    = isset($_POST["c_bancaria"])? limpiarCadena($_POST["c_bancaria"]):"";
$cci		    	= isset($_POST["cci"])? limpiarCadena($_POST["cci"]):"";
$c_detracciones		= isset($_POST["c_detracciones"])? limpiarCadena($_POST["c_detracciones"]):"";
$banco			    = isset($_POST["banco"])? limpiarCadena($_POST["banco"]):"";
$titular_cuenta		= isset($_POST["titular_cuenta"])? limpiarCadena($_POST["titular_cuenta"]):"";



switch ($_GET["op"]){
	case 'guardaryeditar':
		if (!isset($_SESSION["nombre"])) {

		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.

		} else {
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['recurso']==1)
			{
				$clavehash="";


				if (empty($idproveedor)){
					$rspta=$proveedor->insertar($nombre,$tipo_documento,$num_documento,$direccion,$telefono,$c_bancaria, $cci, $c_detracciones,$banco,$titular_cuenta);
					echo $rspta ? "ok" : "No se pudieron registrar todos los datos del proveedor";
				}
				else {
					$rspta=$proveedor->editar($idproveedor,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$c_bancaria, $cci, $c_detracciones,$banco,$titular_cuenta);
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
			if ($_SESSION['recurso']==1)
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
			if ($_SESSION['recurso']==1)
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
				$rspta=$proveedor->eliminar($idproveedor);
 				echo $rspta ? "ok" : "Proveedor no se puede Elimniar";
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
			if ($_SESSION['recurso']==1)
			{
				//$nube_idproyecto = $_GET["nube_idproyecto"];
				$rspta=$proveedor->listar();
		 		//Vamos a declarar un array
		 		$data= Array();
				 $cont=1;
				 //idbancos,razon_social,tipo_documento,ruc,direccion,telefono,cuenta_bancaria,cuenta_detracciones,titular_cuenta

		 		while ($reg=$rspta->fetch_object()){
		 			$data[]=array(
						"0"=>$cont++,
		 				"1"=>($reg->estado)?'<button class="btn btn-warning btn-sm" onclick="mostrar('.$reg->idproveedor.')"><i class="fas fa-pencil-alt"></i></button>'.
							 ' <button class="btn btn-danger btn-sm" onclick="eliminar('.$reg->idproveedor.')"><i class="fas fa-skull-crossbones"></i></button>':
							 '<button class="btn btn-warning btn-sm" onclick="mostrar('.$reg->idproveedor.')"><i class="fa fa-pencil-alt"></i></button>'.
		 					' <button class="btn btn-primary btn-sm" onclick="activar('.$reg->idproveedor.')"><i class="fa fa-check"></i></button>',
						"2"=>'<div class="user-block">
							<span class="username" style="margin-left: 0px !important;"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'. $reg->razon_social .'</p></span>
							<span class="description" style="margin-left: 0px !important;"><b>'. $reg->tipo_documento .'</b>: '. $reg->ruc .' </span>
							<span class="description" style="margin-left: 0px !important;"><b>Cel.:</b>'. '<a href="tel:+51'.quitar_guion($reg->telefono).'" data-toggle="tooltip" data-original-title="Llamar al PROVEEDOR.">'. $reg->telefono . '</a>' .' </span>
							</div>',
		 				"3"=>$reg->direccion,
		 				"4"=> '<div class="w-px-250"><b>Cta. Banc.:</b>'. $reg->cuenta_bancaria. '<br> <b>CCI:</b> '. $reg->cci.' <br> <b>Cta. Dtrac.:</b> '.$reg->cuenta_detracciones. '</div>',
		 				"5"=>$reg->titular_cuenta,
		 				"6"=>($reg->estado)?'<span class="text-center badge badge-success">Activado</span>':'<span class="text-center badge badge-danger">Desactivado</span>'
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
	
	case 'select2Banco': 

		$rspta = $proveedor->select2_banco();
	
		while ($reg = $rspta->fetch_object())  {

		  echo '<option value=' . $reg->id . '>' . $reg->nombre . ((empty($reg->alias)) ? "" : " - $reg->alias" ) .'</option>';
		}

	  break;

	case 'formato_banco':
           
		$rspta=$proveedor->formato_banco($_POST["idbanco"]);
		//Codificar el resultado utilizando json
		echo json_encode($rspta);
		 
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
function quitar_guion($numero){ return str_replace("-", "", $numero); } 
ob_end_flush();
?>