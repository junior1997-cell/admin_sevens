<?php
ob_start();
if (strlen(session_id()) < 1){
	session_start();//Validamos si existe o no la sesión
}
require_once "../modelos/materiales.php";

$materiales=new Materiales();

$idproducto       = isset($_POST["idproducto"])? limpiarCadena($_POST["idproducto"]):"";	
$nombre			  = isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$precio_unitario  = isset($_POST["precio_unitario"])? limpiarCadena($_POST["precio_unitario"]):"";
$descripcion	  = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
$foto1		      = isset($_POST["foto1"])? limpiarCadena($_POST["foto1"]):"";


switch ($_GET["op"]){
	case 'guardaryeditar':
		if (!isset($_SESSION["nombre"])) {

		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.

		} else {
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['recurso']==1)
			{
				// imgen de perfil
				if (!file_exists($_FILES['foto1']['tmp_name']) || !is_uploaded_file($_FILES['foto1']['tmp_name'])) {

					$imagen1=$_POST["foto1_actual"]; $flat_img1 = false;

				} else {

					$ext1 = explode(".", $_FILES["foto1"]["name"]); $flat_img1 = true;						

					$imagen1 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

					move_uploaded_file($_FILES["foto1"]["tmp_name"], "../dist/img/materiales/" . $imagen1);
				
				}


				if (empty($idproducto)){
					//var_dump($idproyecto,$idproveedor);
					$rspta=$materiales->insertar($nombre,$precio_unitario,$descripcion,$imagen1);
					echo $rspta ? "ok" : "No se pudieron registrar todos los datos del proveedor";
				}
				else {
					// validamos si existe LA IMG para eliminarlo
					if ($flat_img1 == true) {

						$datos_f1 = $materiales->obtenerImg($idproducto);
			
						$img1_ant = $datos_f1->fetch_object()->imagen;
			
						if ($img1_ant != "") {
			
							unlink("../dist/img/materiales/" . $img1_ant);
						}
					}
					$rspta=$materiales->editar($idproducto,$precio_unitario,$nombre,$descripcion,$imagen1);
					//var_dump($idproducto,$idproveedor);
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
				$rspta=$materiales->desactivar($idproducto);
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
				$rspta=$materiales->activar($idproducto);
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
			if ($_SESSION['recurso']==1)
			{
				//$idproducto='1';
				$rspta=$materiales->mostrar($idproducto);
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
				
				$rspta=$materiales->listar();
		 		//Vamos a declarar un array
		 		$data= Array();
				$imagen = '';
		 		while ($reg=$rspta->fetch_object()){
					 if (empty($reg->imagen)) {
						$imagen='img_material_defect.jpg';
					 } else {
						$imagen=$reg->imagen;
					 }
					 
		 			$data[]=array(
		 				"0"=>($reg->estado)?'<button class="btn btn-warning btn-sm" onclick="mostrar('.$reg->idproducto.')"><i class="fas fa-pencil-alt"></i></button>'.
		 					' <button class="btn btn-danger btn-sm" onclick="desactivar('.$reg->idproducto.')"><i class="far fa-trash-alt  "></i></button>':
							 '<button class="btn btn-warning btn-sm" onclick="mostrar('.$reg->idproducto.')"><i class="fa fa-pencil-alt"></i></button>'.
		 					' <button class="btn btn-primary btn-sm" onclick="activar('.$reg->idproducto.')"><i class="fa fa-check"></i></button>',
						"1"=>'<div class="user-block">
								<img class="profile-user-img img-responsive img-circle" src="../dist/img/materiales/'.$imagen.'" alt="user image">
								<span class="username"><p style="margin-bottom: 0px !important;">'.$reg->nombre.'</p></span>
								<span class="description">'.substr($reg->descripcion, 0, 30).'...</span>
							 </div>',
		 				"2"=>$reg->descripcion,
		 				"3"=>$reg->precio_unitario,
		 				"4"=>($reg->estado)?'<span class="text-center badge badge-success">Activado</span>':
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