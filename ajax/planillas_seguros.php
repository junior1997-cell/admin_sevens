<?php
ob_start();
if (strlen(session_id()) < 1){
	session_start();//Validamos si existe o no la sesión
}
require_once "../modelos/Planillas_seguros.php";

$planillas_seguros=new Planillas_seguros();
 //transporte.js $idplanilla_seguro,$idproyecto,$fecha_p_s,,,,,$precio_parcial,,$descripcion,$foto2
$idproyecto       = isset($_POST["idproyecto"])? limpiarCadena($_POST["idproyecto"]):"";	
$idplanilla_seguro     = isset($_POST["idplanilla_seguro"])? limpiarCadena($_POST["idplanilla_seguro"]):"";	

$fecha_p_s      = isset($_POST["fecha_p_s"])? limpiarCadena($_POST["fecha_p_s"]):"";

$precio_parcial   = isset($_POST["precio_parcial"])? limpiarCadena($_POST["precio_parcial"]):"";

$descripcion	  = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";

$forma_pago = isset($_POST["forma_pago"])? limpiarCadena($_POST["forma_pago"]):"";
$tipo_comprobante = isset($_POST["tipo_comprobante"])? limpiarCadena($_POST["tipo_comprobante"]):"";
$nro_comprobante  = isset($_POST["nro_comprobante"])? limpiarCadena($_POST["nro_comprobante"]):"";
$subtotal         = isset($_POST["subtotal"])? limpiarCadena($_POST["subtotal"]):"";
$igv              = isset($_POST["igv"])? limpiarCadena($_POST["igv"]):"";

$foto2		      = isset($_POST["doc1"])? limpiarCadena($_POST["doc1"]):"";
//$subtotal,$igv  
switch ($_GET["op"]){
	case 'guardaryeditar':
		if (!isset($_SESSION["nombre"])) {

		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los materials logueados al sistema.

		} else {
			//Validamos el acceso solo al material logueado y autorizado.
			if ($_SESSION['planilla_seguro']==1)
			{

				// Comprobante
				if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {

					$comprobante=$_POST["doc_old_1"]; $flat_ficha1 = false;

				} else {

					$ext1 = explode(".", $_FILES["doc1"]["name"]); $flat_ficha1 = true;						

					$comprobante = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

					move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/planilla_seguro/comprobante/" . $comprobante);
				
				}


				if (empty($idplanilla_seguro)){
					//var_dump($idproyecto,$idproveedor);
					$rspta=$planillas_seguros->insertar($idproyecto,$fecha_p_s,$precio_parcial,$subtotal,$igv,$descripcion,$forma_pago,$tipo_comprobante,$nro_comprobante,$comprobante);
					echo $rspta ? "ok" : "No se pudieron registrar todos los datos";
				}
				else {
					//validamos si existe comprobante para eliminarlo
					if ($flat_ficha1 == true) {

						$datos_ficha1 = $planillas_seguros->ficha_tec($idplanilla_seguro);
			
						$ficha1_ant = $datos_ficha1->fetch_object()->comprobante;
			
						if ($ficha1_ant != "") {
			
							unlink("../dist/docs/planilla_seguro/comprobante/" . $ficha1_ant);
						}
					}

					$rspta=$planillas_seguros->editar($idplanilla_seguro,$idproyecto,$fecha_p_s,$precio_parcial,$subtotal,$igv,$descripcion,$forma_pago,$tipo_comprobante,$nro_comprobante,$comprobante);
					//var_dump($idplanilla_seguro,$idproveedor);
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
			if ($_SESSION['planilla_seguro']==1)
			{
				$rspta=$planillas_seguros->desactivar($idplanilla_seguro);
 				echo $rspta ? "material Desactivado" : "material no se puede desactivar";
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
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los materials logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al material logueado y autorizado.
			if ($_SESSION['planilla_seguro']==1)
			{
				$rspta=$planillas_seguros->eliminar($idplanilla_seguro);
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
			if ($_SESSION['planilla_seguro']==1)
			{
				$rspta=$planillas_seguros->activar($idplanilla_seguro);
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
			if ($_SESSION['planilla_seguro']==1)
			{
				//$idplanilla_seguro='1';
				$rspta=$planillas_seguros->mostrar($idplanilla_seguro);
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
			if ($_SESSION['planilla_seguro']==1)
			{
				//$idplanilla_seguro='1';
				$rspta=$planillas_seguros->mostrar($idplanilla_seguro);
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

	case 'listar':
		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los materials logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al material logueado y autorizado.
			if ($_SESSION['planilla_seguro']==1)
			{
				$idproyecto= $_GET["idproyecto"];
				$rspta=$planillas_seguros->listar($idproyecto);
		 		//Vamos a declarar un array
		 		$data= Array();
				$comprobante = '';
				$cont=1;
		 		while ($reg=$rspta->fetch_object()){

					// empty($reg->comprobante)?$comprobante='<div><center><a type="btn btn-danger" class=""><i class="far fa-times-circle fa-2x"></i></a></center></div>':$comprobante='<center><a target="_blank" href="../dist/docs/planilla_seguro/comprobante/'.$reg->comprobante.'"><i class="far fa-file-pdf fa-2x" style="color:#ff0000c4"></i></a></center>';
		 			
					
					 empty($reg->comprobante)?$comprobante='<div><center><a type="btn btn-danger" class=""><i class="fas fa-file-invoice-dollar fa-2x text-gray-50"></i></a></center></div>':$comprobante='<div><center><a type="btn btn-danger" class=""  href="#" onclick="modal_comprobante('."'".$reg->comprobante."'".')"><i class="fas fa-file-invoice-dollar fa-2x"></i></a></center></div>';
					 if (strlen($reg->descripcion) >= 20 ) { $descripcion = substr($reg->descripcion, 0, 20).'...';  } else { $descripcion = $reg->descripcion; }
					 $tool = '"tooltip"';   $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>"; 
					 $data[]=array(
						"0"=>$cont++,
		 				"1"=>($reg->estado)?'<button class="btn btn-warning btn-sm" onclick="mostrar('.$reg->idplanilla_seguro.')"><i class="fas fa-pencil-alt"></i></button>'.
		 					' <button class="btn btn-danger btn-sm" onclick="eliminar('.$reg->idplanilla_seguro.')"><i class="fas fa-skull-crossbones"></i></button>':
							'<button class="btn btn-warning btn-sm" onclick="mostrar('.$reg->idplanilla_seguro.')"><i class="fa fa-pencil-alt"></i></button>'.
		 					' <button class="btn btn-primary btn-sm" onclick="activar('.$reg->idplanilla_seguro.')"><i class="fa fa-check"></i></button>',
						"2"=>$reg->forma_de_pago,  
						"3"=>'<div class="user-block">
								<span class="username" style="margin-left: 0px !important;"> <p class="text-primary" style="margin-bottom: 0.2rem !important";>'.$reg->tipo_comprobante.'</p> </span>
								<span class="description" style="margin-left: 0px !important;">N° '.(empty($reg->numero_comprobante)?" - ":$reg->numero_comprobante).'</span>         
							</div>',
						"4"=> date("d/m/Y", strtotime($reg->fecha_p_s)), 
						"5"=>'S/. '.number_format($reg->subtotal, 2, '.', ','),
						"6"=>'S/. '.number_format($reg->igv, 2, '.', ','),
						"7"=>'S/. '.number_format($reg->costo_parcial, 2, '.', ','),
					   	"8"=>'<textarea cols="30" rows="1" class="text_area_clss" readonly="">'.$reg->descripcion.'</textarea>',
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