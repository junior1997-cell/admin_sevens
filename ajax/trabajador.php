<?php
ob_start();
if (strlen(session_id()) < 1){
	session_start();//Validamos si existe o no la sesión
}
require_once "../modelos/trabajador.php";

$trabajador=new Trabajador();

//$idtrabajador,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$nacimiento,$tipo_trabajador,$desempenio,$c_bancaria,$email,$cargo,$banco,$tutular_cuenta,$sueldo_diario,$sueldo_mensual,$sueldo_hora,$imagen	
$idproyecto		= isset($_POST["idproyecto"])? limpiarCadena($_POST["idproyecto"]):"";
$idtrabajador		= isset($_POST["idtrabajador"])? limpiarCadena($_POST["idtrabajador"]):"";
$nombre 		    = isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$tipo_documento	    = isset($_POST["tipo_documento"])? limpiarCadena($_POST["tipo_documento"]):"";
$num_documento	    = isset($_POST["num_documento"])? limpiarCadena($_POST["num_documento"]):"";
$direccion		    = isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
$telefono		    = isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
$nacimiento		    = isset($_POST["nacimiento"])? limpiarCadena($_POST["nacimiento"]):"";
$tipo_trabajador	= isset($_POST["tipo_trabajador"])? limpiarCadena($_POST["tipo_trabajador"]):"";
$desempenio	        = isset($_POST["desempenio"])? limpiarCadena($_POST["desempenio"]):"";
$c_bancaria		    = isset($_POST["c_bancaria"])? limpiarCadena($_POST["c_bancaria"]):"";
$email			    = isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
$cargo			    = isset($_POST["cargo"])? limpiarCadena($_POST["cargo"]):"";
$banco			    = isset($_POST["banco"])? limpiarCadena($_POST["banco"]):"";
$tutular_cuenta		= isset($_POST["tutular_cuenta"])? limpiarCadena($_POST["tutular_cuenta"]):"";
$sueldo_diario		= isset($_POST["sueldo_diario"])? limpiarCadena($_POST["sueldo_diario"]):"";
$sueldo_mensual 	= isset($_POST['sueldo_mensual'])? $_POST['sueldo_mensual']:"";
$sueldo_hora 		= isset($_POST['sueldo_hora'])? $_POST['sueldo_hora']:"";
$imagen			    = isset($_POST["foto2"])? limpiarCadena($_POST["foto2"]):"";


switch ($_GET["op"]){
	case 'guardaryeditar':
		if (!isset($_SESSION["nombre"])) {

		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.

		} else {
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['trabajador']==1)
			{
				$clavehash="";

				// if ( !empty($imagen) ) {
					if (!file_exists($_FILES['foto2']['tmp_name']) || !is_uploaded_file($_FILES['foto2']['tmp_name'])) {

						$imagen=$_POST["foto2_actual"];
					} else {

						$ext = explode(".", $_FILES["foto2"]["name"]);

						if ($_FILES['foto2']['type'] == "image/jpg" || $_FILES['foto2']['type'] == "image/jpeg" || $_FILES['foto2']['type'] == "image/png")
						{
							$imagen = round(microtime(true)) . '.' . end($ext);

							move_uploaded_file($_FILES["foto2"]["tmp_name"], "../dist/img/usuarios/" . $imagen);
						}
					}
				// }	
				// regitramso un nuevo trabajador
				if (empty($idtrabajador)){

					$rspta=$trabajador->insertar($idproyecto,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$nacimiento,$tipo_trabajador,$desempenio,$c_bancaria,$email,$cargo,$banco,$tutular_cuenta,$sueldo_diario,$sueldo_mensual,$sueldo_hora,$imagen);
					
					echo $rspta ? "ok" : "No se pudieron registrar todos los datos del usuario";

				}else {
					// editamos un trabajador existente
					$rspta=$trabajador->editar($idtrabajador,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$nacimiento,$tipo_trabajador,$desempenio,$c_bancaria,$email,$cargo,$banco,$tutular_cuenta,$sueldo_diario,$sueldo_mensual,$sueldo_hora,$imagen);
					
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
			if ($_SESSION['trabajador']==1)
			{
				$rspta=$trabajador->desactivar($idtrabajador);
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
			if ($_SESSION['trabajador']==1)
			{
				$rspta=$trabajador->activar($idtrabajador);
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
			if ($_SESSION['trabajador']==1)
			{
				$rspta=$trabajador->mostrar($idtrabajador);
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
	
	case 'verdatos':
		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['trabajador']==1)
			{
				$rspta=$trabajador->verdatos($idtrabajador);
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
		if (!isset($_SESSION["nombre"])){

		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.

		}else{

			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['trabajador']==1)	{

				$nube_idproyecto = $_GET["nube_idproyecto"];

				$rspta=$trabajador->listar($nube_idproyecto);
		 		//Vamos a declarar un array
		 		$data= Array();

				$imagen_error = "this.src='../dist/svg/user_default.svg'";
				
		 		while ($reg=$rspta->fetch_object()){
		 			$data[]=array(
		 				"0"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idtrabajador.')"><i class="fas fa-pencil-alt"></i></button>'.
		 					' <button class="btn btn-danger" onclick="desactivar('.$reg->idtrabajador.')"><i class="far fa-trash-alt  "></i></button>'.
							' <button class="btn btn-info" onclick="verdatos('.$reg->idtrabajador.')"><i class="far fa-eye"></i></button>':
							 '<button class="btn btn-warning" onclick="mostrar('.$reg->idtrabajador.')"><i class="fa fa-pencil-alt"></i></button>'.
		 					' <button class="btn btn-primary" onclick="activar('.$reg->idtrabajador.')"><i class="fa fa-check"></i></button>'.
							' <button class="btn btn-info" onclick="verdatos('.$reg->idtrabajador.')"><i class="far fa-eye"></i></button>',
						"1"=>'<div class="user-block">
							 <img class="img-circle" src="../dist/img/usuarios/'. $reg->imagen .'" alt="User Image" onerror="'.$imagen_error.'">
							 <span class="username"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'. $reg->nombres .'</p></span>
							 <span class="description">'. $reg->tipo_documento .': '. $reg->numero_documento .' </span>
						 	</div>',
		 				"2"=>$reg->cuenta_bancaria,
		 				"3"=>$reg->sueldo_mensual,
		 				"4"=>$reg->tipo_trabajador.' / '.$reg->cargo,
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