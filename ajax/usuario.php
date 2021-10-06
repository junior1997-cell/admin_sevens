<?php
ob_start();
if (strlen(session_id()) < 1){
	session_start();//Validamos si existe o no la sesión
}
require_once "../modelos/Usuario.php";

$usuario=new Usuario();

$idusuario		= isset($_POST["idusuario"])? limpiarCadena($_POST["idusuario"]):"";
$nombre 		= isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$tipo_documento	= isset($_POST["tipo_documento"])? limpiarCadena($_POST["tipo_documento"]):"";
$num_documento	= isset($_POST["num_documento"])? limpiarCadena($_POST["num_documento"]):"";
$direccion		= isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
$telefono		= isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
$email			= isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
$cargo			= isset($_POST["cargo"])? limpiarCadena($_POST["cargo"]):"";
$login			= isset($_POST["login"])? limpiarCadena($_POST["login"]):"";
$clave			= isset($_POST["password"])? limpiarCadena($_POST["password"]):"";
$clave_old		= isset($_POST["password-old"])? limpiarCadena($_POST["password-old"]):"";
$imagen			= isset($_POST["foto2"])? limpiarCadena($_POST["foto2"]):"";
$permiso 		= isset($_POST['permiso'])? $_POST['permiso']:"";

switch ($_GET["op"]){
	case 'guardaryeditar':
		if (!isset($_SESSION["nombre"])) {

		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.

		} else {
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['acceso']==1)
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
				
				if ( !empty($clave) ) {
					//Hash SHA256 en la contraseña
					$clavehash = hash("SHA256",$clave);
				} else {
					// enviamos la contraseña antigua
					$clavehash = $clave_old;
				}				

				if (empty($idusuario)){
					$rspta=$usuario->insertar($nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$cargo,$login,$clavehash,$imagen,$permiso);
					echo $rspta ? "ok" : "No se pudieron registrar todos los datos del usuario";
				}
				else {
					$rspta=$usuario->editar($idusuario,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$cargo,$login,$clavehash,$imagen,$permiso);
					echo $rspta ? "ok" : "Usuario no se pudo actualizar";
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
			if ($_SESSION['acceso']==1)
			{
				$rspta=$usuario->desactivar($idusuario);
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
				$rspta=$usuario->activar($idusuario);
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
				$rspta=$usuario->mostrar($idusuario);
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
				$rspta=$usuario->listar();
		 		//Vamos a declarar un array
		 		$data= Array();

		 		while ($reg=$rspta->fetch_object()){
		 			$data[]=array(
		 				"0"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idusuario.')"><i class="fas fa-pencil-alt"></i></button>'.
		 					' <button class="btn btn-danger" onclick="desactivar('.$reg->idusuario.')"><i class="far fa-trash-alt  "></i></button>':
		 					'<button class="btn btn-warning" onclick="mostrar('.$reg->idusuario.')"><i class="fas fa-pencil-alt"></i></button>'.
		 					' <button class="btn btn-primary" onclick="activar('.$reg->idusuario.')"><i class="fa fa-check"></i></button>',
		 				"1"=>$reg->nombre,
		 				"2"=>$reg->tipo_documento,
		 				"3"=>$reg->num_documento,
		 				"4"=>$reg->telefono,
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

	case 'permisos':
		//Obtenemos todos los permisos de la tabla permisos
		require_once "../modelos/Permiso.php";
		$permiso = new Permiso();
		$rspta = $permiso->listar();

		//Obtener los permisos asignados al usuario
		$id=$_GET['id'];
		$marcados = $usuario->listarmarcados($id);
		//Declaramos el array para almacenar todos los permisos marcados
		$valores=array();

		//Almacenar los permisos asignados al usuario en el array
		while ($per = $marcados->fetch_object()){

			array_push($valores, $per->idpermiso);
		}

		//Mostramos la lista de permisos en la vista y si están o no marcados
		while ($reg = $rspta->fetch_object()){

			$sw=in_array($reg->idpermiso,$valores)?'checked':'';

			echo '<li> <input   type="checkbox" '.$sw.'  name="permiso[]" value="'.$reg->idpermiso.'">'.$reg->nombre.' </li>';
		}
	break;

	case 'verificar':
		$logina=$_POST['logina'];
	    $clavea=$_POST['clavea'];

	    //Hash SHA256 en la contraseña
		$clavehash=hash("SHA256",$clavea);

		$rspta=$usuario->verificar($logina, $clavehash);

		$fetch=$rspta->fetch_object();

		if (isset($fetch))
	    {
	        //Declaramos las variables de sesión
	        $_SESSION['idusuario']=$fetch->idusuario;
	        $_SESSION['nombre']=$fetch->nombre;
	        $_SESSION['imagen']=$fetch->imagen;
	        $_SESSION['login']=$fetch->login;
			$_SESSION['cargo']=$fetch->cargo;
			$_SESSION['tipo_documento']=$fetch->tipo_documento;
			$_SESSION['num_documento']=$fetch->num_documento;
			$_SESSION['telefono']=$fetch->telefono;
			$_SESSION['email']=$fetch->email;

	        //Obtenemos los permisos del usuario
	    	$marcados = $usuario->listarmarcados($fetch->idusuario);

	    	//Declaramos el array para almacenar todos los permisos marcados
			$valores=array();

			//Almacenamos los permisos marcados en el array
			while ($per = $marcados->fetch_object())
			{
				array_push($valores, $per->idpermiso);
			}

			//Determinamos los accesos del usuario
			in_array(1,$valores)?$_SESSION['trabajadores']=1:$_SESSION['trabajadores']=0;
			in_array(2,$valores)?$_SESSION['proveedores']=1:$_SESSION['proveedores']=0;
			in_array(3,$valores)?$_SESSION['acceso']=1:$_SESSION['acceso']=0;
			in_array(4,$valores)?$_SESSION['escritorio']=1:$_SESSION['escritorio']=0;
			// in_array(5,$valores)?$_SESSION['acceso']=1:$_SESSION['acceso']=0;
			// in_array(6,$valores)?$_SESSION['consultac']=1:$_SESSION['consultac']=0;
			// in_array(7,$valores)?$_SESSION['consultav']=1:$_SESSION['consultav']=0;

	    }
	    echo json_encode($fetch);
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