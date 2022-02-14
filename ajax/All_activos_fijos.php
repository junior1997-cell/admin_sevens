<?php
ob_start();
if (strlen(session_id()) < 1){
	session_start();//Validamos si existe o no la sesión
}
require_once "../modelos/Activos_fijos.php";

$activos_fijos=new Activos_fijos();
   //idactivos_fijos,numero_comprobante,tipo_comprobante,fecha_comprobante,forma_de_pago,subtotal,igv,total,nombre_de_activo,modelo_de_activo,serie_de_activo,marca_de_activo,descripcion,$foto2

$idactivos_fijos     = isset($_POST["idactivos_fijos"])? limpiarCadena($_POST["idactivos_fijos"]):"";	
$numero_comprobante     = isset($_POST["numero_comprobante"])? limpiarCadena($_POST["numero_comprobante"]):"";	
$tipo_comprobante     = isset($_POST["tipo_comprobante"])? limpiarCadena($_POST["tipo_comprobante"]):"";	
$fecha_comprobante     = isset($_POST["fecha_comprobante"])? limpiarCadena($_POST["fecha_comprobante"]):"";	
$forma_de_pago     = isset($_POST["forma_de_pago"])? limpiarCadena($_POST["forma_de_pago"]):"";	
$subtotal     = isset($_POST["subtotal"])? limpiarCadena($_POST["subtotal"]):"";	
$igv     = isset($_POST["igv"])? limpiarCadena($_POST["igv"]):"";	
$total     = isset($_POST["total"])? limpiarCadena($_POST["total"]):"";	
$nombre_de_activo     = isset($_POST["nombre_de_activo"])? limpiarCadena($_POST["nombre_de_activo"]):"";	
$marca_de_activo     = isset($_POST["marca_de_activo"])? limpiarCadena($_POST["marca_de_activo"]):"";	
$descripcion     = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";

$foto2		      = isset($_POST["foto2"])? limpiarCadena($_POST["foto2"]):"";

switch ($_GET["op"]){
	case 'guardaryeditar':
		if (!isset($_SESSION["nombre"])) {

		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los materials logueados al sistema.

		} else {
			//Validamos el acceso solo al material logueado y autorizado.
			if ($_SESSION['otro_servicio']==1)
			{

				// Comprobante
				if (!file_exists($_FILES['foto2']['tmp_name']) || !is_uploaded_file($_FILES['foto2']['tmp_name'])) {

					$comprobante=$_POST["foto2_actual"]; $flat_ficha1 = false;

				} else {

					$ext1 = explode(".", $_FILES["foto2"]["name"]); $flat_ficha1 = true;						

					$comprobante = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

					move_uploaded_file($_FILES["foto2"]["tmp_name"], "../dist/img/facturas_activos_fijos/" . $comprobante);
				
				}


				if (empty($idactivos_fijos)){
					//var_dump($idproyecto,$idproveedor);
					$rspta=$activos_fijos->insertar($numero_comprobante,$tipo_comprobante,$fecha_comprobante,
						$forma_de_pago,$subtotal,$igv,$total,$nombre_de_activo,$modelo_de_activo,$serie_de_activo,$marca_de_activo,$descripcion,$comprobante);
					echo $rspta ? "ok" : "No se pudieron registrar todos los datos";
				}
				else {
					//validamos si existe comprobante para eliminarlo
					if ($flat_ficha1 == true) {

						$datos_ficha1 = $activos_fijos->ficha_tec($idactivos_fijos);
			
						$ficha1_ant = $datos_ficha1->fetch_object()->comprobante;
			
						if ($ficha1_ant != "") {
			
							unlink("../dist/img/facturas_activos_fijos/" . $ficha1_ant);
						}
					}

					$rspta=$activos_fijos->editar($idactivos_fijos,$numero_comprobante,$tipo_comprobante,$fecha_comprobante,
					$forma_de_pago,$subtotal,$igv,$total,$nombre_de_activo,$modelo_de_activo,$serie_de_activo,$marca_de_activo,$descripcion,$comprobante);
					//var_dump($idactivos_fijos,$idproveedor);
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
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los materials logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al material logueado y autorizado.
			if ($_SESSION['otro_servicio']==1)
			{
				$rspta=$activos_fijos->desactivar($idactivos_fijos);
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
			if ($_SESSION['otro_servicio']==1)
			{
				$rspta=$activos_fijos->activar($idactivos_fijos);
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
			if ($_SESSION['otro_servicio']==1)
			{
				//$idactivos_fijos='1';
				$rspta=$activos_fijos->mostrar($idactivos_fijos);
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
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los materials logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al material logueado y autorizado.
			if ($_SESSION['otro_servicio']==1)
			{
				//$idactivos_fijos='1';
				$rspta=$activos_fijos->mostrar($idactivos_fijos);
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
			if ($_SESSION['otro_servicio']==1)
			{

				$rspta=$activos_fijos->total($idproyecto);
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
			if ($_SESSION['otro_servicio']==1)
			{
				$idproyecto= $_GET["idproyecto"];
				$rspta=$activos_fijos->listar($idproyecto);
		 		//Vamos a declarar un array
		 		$data= Array();
				$comprobante = '';
		 		while ($reg=$rspta->fetch_object()){

					// empty($reg->comprobante)?$comprobante='<div><center><a type="btn btn-danger" class=""><i class="far fa-times-circle fa-2x"></i></a></center></div>':$comprobante='<center><a target="_blank" href="../dist/img/facturas_activos_fijos/'.$reg->comprobante.'"><i class="far fa-file-pdf fa-2x" style="color:#ff0000c4"></i></a></center>';
		 			
					
					 empty($reg->comprobante)?$comprobante='<div><center><a type="btn btn-danger" class=""><i class="far fa-times-circle fa-2x"></i></a></center></div>':$comprobante='<div><center><a type="btn btn-danger" class=""  href="#" onclick="modal_comprobante('."'".$reg->comprobante."'".')"><i class="fas fa-file-invoice-dollar fa-2x"></i></a></center></div>';
					 if (strlen($reg->descripcion) >= 20 ) { $descripcion = substr($reg->descripcion, 0, 20).'...';  } else { $descripcion = $reg->descripcion; }
					 $tool = '"tooltip"';   $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>"; 
					 $data[]=array(
		 				"0"=>($reg->estado)?'<button class="btn btn-warning btn-sm" onclick="mostrar('.$reg->idactivos_fijos.')"><i class="fas fa-pencil-alt"></i></button>'.
		 					' <button class="btn btn-danger btn-sm" onclick="desactivar('.$reg->idactivos_fijos.')"><i class="far fa-trash-alt"></i></button>':
							'<button class="btn btn-warning btn-sm" onclick="mostrar('.$reg->idactivos_fijos.')"><i class="fa fa-pencil-alt"></i></button>'.
		 					' <button class="btn btn-primary btn-sm" onclick="activar('.$reg->idactivos_fijos.')"><i class="fa fa-check"></i></button>',
						"1"=>$reg->forma_de_pago, 
						"2"=>$reg->tipo_comprobante, 
						"3"=> empty($reg->numero_comprobante)?' - ':$reg->numero_comprobante, 
						"4"=> date("d/m/Y", strtotime($reg->fecha_o_s)), 
						"5"=>number_format($reg->subtotal, 2, '.', ','),
						"6"=>number_format($reg->igv, 2, '.', ','),
						"7"=>number_format($reg->costo_parcial, 2, '.', ','),
					   	"8"=>empty($reg->descripcion)?'-':'<div data-toggle="tooltip" data-original-title="'.$reg->descripcion.'">'.$descripcion.'</div>',
						"9"=>$comprobante,
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
	
	case 'total':
		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los materials logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al material logueado y autorizado.
			if ($_SESSION['planilla_seguro']==1)
			{

				$rspta=$planillas_seguros->total($idproyecto);
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