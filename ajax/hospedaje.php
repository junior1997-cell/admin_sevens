<?php
ob_start();
if (strlen(session_id()) < 1){
	session_start();//Validamos si existe o no la sesión
}
require_once "../modelos/Hospedaje.php";

$hospedaje=new Hospedaje();
//$idproyecto,$idhospedaje,$fecha_inicio,$fecha_fin,$cantidad,$unidad,$precio_unitario,$precio_parcial,$descripcion,$foto2
$idproyecto       = isset($_POST["idproyecto"])? limpiarCadena($_POST["idproyecto"]):"";	
$idhospedaje      = isset($_POST["idhospedaje"])? limpiarCadena($_POST["idhospedaje"]):"";	
$fecha_inicio     = isset($_POST["fecha_inicio"])? limpiarCadena($_POST["fecha_inicio"]):"";
$fecha_fin	      = isset($_POST["fecha_fin"])? limpiarCadena($_POST["fecha_fin"]):"";
$cantidad         = isset($_POST["cantidad"])? limpiarCadena($_POST["cantidad"]):"";
$unidad           = isset($_POST["unidad"])? limpiarCadena($_POST["unidad"]):"";
$precio_unitario  = isset($_POST["precio_unitario"])? limpiarCadena($_POST["precio_unitario"]):"";
$precio_parcial   = isset($_POST["precio_parcial"])? limpiarCadena($_POST["precio_parcial"]):"";
$descripcion	  = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";

$foto2		      = isset($_POST["foto2"])? limpiarCadena($_POST["foto2"]):"";

switch ($_GET["op"]){
	case 'guardaryeditar':
		if (!isset($_SESSION["nombre"])) {

		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los materials logueados al sistema.

		} else {
			//Validamos el acceso solo al material logueado y autorizado.
			if ($_SESSION['recurso']==1)
			{

				// Comprobante
				if (!file_exists($_FILES['foto2']['tmp_name']) || !is_uploaded_file($_FILES['foto2']['tmp_name'])) {

					$comprobante=$_POST["foto2_actual"]; $flat_ficha1 = false;

				} else {

					$ext1 = explode(".", $_FILES["foto2"]["name"]); $flat_ficha1 = true;						

					$comprobante = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

					move_uploaded_file($_FILES["foto2"]["tmp_name"], "../dist/img/comprob_hospedajes/" . $comprobante);
				
				}


				if (empty($idhospedaje)){
					//var_dump($idproyecto,$idproveedor);
					$rspta=$hospedaje->insertar($idproyecto,$fecha_inicio,$fecha_fin,$cantidad,$unidad,$precio_unitario,$precio_parcial,$descripcion,$comprobante);
					echo $rspta ? "ok" : "No se pudieron registrar todos los datos del proveedor";
				}
				else {
					//validamos si existe comprobante para eliminarlo
					if ($flat_ficha1 == true) {

						$datos_ficha1 = $hospedaje->ficha_tec($idhospedaje);
			
						$ficha1_ant = $datos_ficha1->fetch_object()->comprobante;
			
						if ($ficha1_ant != "") {
			
							unlink("../dist/img/comprob_hospedajes/" . $ficha1_ant);
						}
					}

					$rspta=$hospedaje->editar($idhospedaje,$idproyecto,$fecha_inicio,$fecha_fin,$cantidad,$unidad,$precio_unitario,$precio_parcial,$descripcion,$comprobante);
					//var_dump($idhospedaje,$idproveedor);
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
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los materials logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al material logueado y autorizado.
			if ($_SESSION['recurso']==1)
			{
				$rspta=$hospedaje->desactivar($idhospedaje);
 				echo $rspta ? "material Desactivado" : "material no se puede desactivar";
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
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los materials logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al material logueado y autorizado.
			if ($_SESSION['recurso']==1)
			{
				$rspta=$hospedaje->activar($idhospedaje);
 				echo $rspta ? "Material activado" : "material no se puede activar";
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
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los materials logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al material logueado y autorizado.
			if ($_SESSION['recurso']==1)
			{
				//$idhospedaje='1';
				$rspta=$hospedaje->mostrar($idhospedaje);
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
	case 'total':
		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los materials logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al material logueado y autorizado.
			if ($_SESSION['recurso']==1)
			{

				$rspta=$hospedaje->total();
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
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los materials logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al material logueado y autorizado.
			if ($_SESSION['recurso']==1)
			{
				
				$rspta=$hospedaje->listar();
		 		//Vamos a declarar un array
		 		$data= Array();
				$imagen = '';
				$comprobante = '';
				$monto_igv = '';
		 		while ($reg=$rspta->fetch_object()){

					 empty($reg->comprobante)?$comprobante='<div><center><a type="btn btn-danger" class=""><i class="far fa-times-circle fa-2x"></i></a></center></div>':$comprobante='<center><a target="_blank" href="../dist/img/comprob_hospedajes/'.$reg->comprobante.'"><i class="far fa-file-pdf fa-2x" style="color:#ff0000c4"></i></a></center>';
		 			
					 $data[]=array(
		 				"0"=>($reg->estado)?'<button class="btn btn-warning btn-sm" onclick="mostrar('.$reg->idhospedaje.')"><i class="fas fa-pencil-alt"></i></button>'.
		 					' <button class="btn btn-danger btn-sm" onclick="desactivar('.$reg->idhospedaje.')"><i class="far fa-trash-alt"></i></button>':
							 '<button class="btn btn-warning btn-sm" onclick="mostrar('.$reg->idhospedaje.')"><i class="fa fa-pencil-alt"></i></button>'.
		 					' <button class="btn btn-primary btn-sm" onclick="activar('.$reg->idhospedaje.')"><i class="fa fa-check"></i></button>',
						"1"=>$reg->fecha_inicio,
		 				"2"=>$reg->fecha_fin,
		 				"3"=>$reg->descripcion,
		 				"4"=>$reg->cantidad,
		 				"5"=>$reg->unidad,
		 				"6"=>$reg->precio_unitario,
		 				"7"=>$reg->precio_parcial,
		 				"8"=>$comprobante,
		 				"9"=>($reg->estado)?'<span class="text-center badge badge-success">Activado</span>':
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