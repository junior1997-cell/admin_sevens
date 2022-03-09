<?php
ob_start();
if (strlen(session_id()) < 1){
	session_start();//Validamos si existe o no la sesión
}
require_once "../modelos/Sub_contrato.php";

$sub_contrato=new Sub_contrato();
 // // idsubcontrato,fecha_subcontrato,numero_comprobante,subtotal,igv,val_igv,costo_parcial,descripcion,doc1,idproveedor,tipo_comprobante,forma_de_pago
$idproyecto       = isset($_POST["idproyecto"])? limpiarCadena($_POST["idproyecto"]):"";	
$idproveedor       = isset($_POST["idproveedor"])? limpiarCadena($_POST["idproveedor"]):"";	
$idsubcontrato     = isset($_POST["idsubcontrato"])? limpiarCadena($_POST["idsubcontrato"]):"";	
$fecha_subcontrato      = isset($_POST["fecha_subcontrato"])? limpiarCadena($_POST["fecha_subcontrato"]):"";

$descripcion	  = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";

$forma_de_pago       = isset($_POST["forma_de_pago"])? limpiarCadena($_POST["forma_de_pago"]):"";
$tipo_comprobante    = isset($_POST["tipo_comprobante"])? limpiarCadena($_POST["tipo_comprobante"]):"";
$numero_comprobante  = isset($_POST["numero_comprobante"])? limpiarCadena($_POST["numero_comprobante"]):"";
$subtotal            = isset($_POST["subtotal"])? limpiarCadena($_POST["subtotal"]):"";
$igv                 = isset($_POST["igv"])? limpiarCadena($_POST["igv"]):"";
$costo_parcial       = isset($_POST["costo_parcial"])? limpiarCadena($_POST["costo_parcial"]):"";
$val_igv             = isset($_POST["val_igv"])? limpiarCadena($_POST["val_igv"]):"";

$foto2 = isset($_POST["doc1"]) ? limpiarCadena($_POST["doc1"]) : "";

switch ($_GET["op"]){
	case 'guardaryeditar':
		if (!isset($_SESSION["nombre"])) {

		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los materials logueados al sistema.

		} else {
			//Validamos el acceso solo al material logueado y autorizado.
			if ($_SESSION['viatico']==1)
			{

				// Comprobante
				if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {

					$comprobante=$_POST["doc_old_1"]; $flat_ficha1 = false;

				} else {

					$ext1 = explode(".", $_FILES["doc1"]["name"]); $flat_ficha1 = true;						

					$comprobante = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

					move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/sub_contrato/comprobante_subcontrato/" . $comprobante);
				
				}


				if (empty($idsubcontrato)){
					//var_dump($idproyecto,$idproveedor);
					$rspta=$sub_contrato->insertar($idproyecto, $idproveedor, $tipo_comprobante, $numero_comprobante, $forma_de_pago, $fecha_subcontrato, $val_igv, $subtotal, $igv, $costo_parcial, $descripcion, $comprobante);
					echo $rspta ? "ok" : "No se pudieron registrar todos los datos";
				}
				else {
					//validamos si existe comprobante para eliminarlo
					if ($flat_ficha1 == true) {

						$datos_ficha1 = $sub_contrato->ficha_tec($idsubcontrato);
			
						$ficha1_ant = $datos_ficha1->fetch_object()->comprobante;
			
						if ($ficha1_ant != "") {
			
							unlink("../dist/docs/sub_contrato/comprobante_subcontrato/" . $ficha1_ant);
						}
					}

					$rspta=$sub_contrato->editar($idsubcontrato, $idproyecto, $idproveedor, $tipo_comprobante, $numero_comprobante, $forma_de_pago, $fecha_subcontrato, $val_igv, $subtotal, $igv, $costo_parcial, $descripcion, $comprobante);
					
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
			//Validamos el acceso solo al  logueado y autorizado.
			if ($_SESSION['viatico']==1)
			{
				$rspta=$sub_contrato->desactivar($idsubcontrato);
 				echo $rspta ? " Desactivado" : "No se puede desactivar";
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
		  header("Location: ../vistas/login.html");
		}
		else
		{
			
			if ($_SESSION['viatico']==1)
			{
				$rspta=$sub_contrato->activar($idsubcontrato);
 				echo $rspta ? "Activado" : "No se puede activar";
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
			//Validamos el acceso solo al  logueado y autorizado.
			if ($_SESSION['viatico']==1)
			{
				$rspta=$sub_contrato->eliminar($idsubcontrato);
 				echo $rspta ? " Eliminado" : "No se puede Eliminar";
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
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al material logueado y autorizado.
			if ($_SESSION['viatico']==1)
			{
				//$idsubcontrato='1';
				$rspta=$sub_contrato->mostrar($idsubcontrato);
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
			if ($_SESSION['viatico']==1)
			{
				//$idsubcontrato='1';
				$rspta=$sub_contrato->verdatos($idsubcontrato);
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
			if ($_SESSION['viatico']==1)
			{

				$rspta=$sub_contrato->total($idproyecto);
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
			if ($_SESSION['viatico']==1)
			{
				$idproyecto= $_GET["idproyecto"];
				$rspta=$sub_contrato->listar($idproyecto);
		 		//Vamos a declarar un array
		 		$data= Array();
				$comprobante = '';
				$cont=1;
				$saldo=0; $estado=''; $c=''; $nombre=''; $icon=''; $info=''; 

				foreach ($rspta as $key => $reg) {
					
					 empty($reg['comprobante'])?$comprobante='<div><center><a type="btn btn-danger" class=""><i class="fas fa-file-invoice-dollar fa-2x text-gray-50"></i></a></center></div>':$comprobante='<div><center><a type="btn btn-danger" class=""  href="#" onclick="modal_comprobante('."'".$reg['comprobante']."'".')"><i class="fas fa-file-invoice-dollar fa-2x"></i></a></center></div>';
					
					 $saldo = $reg['costo_parcial'] - $reg['total_deposito']; 

					if ($saldo == $reg['costo_parcial']) {
						$estado = '<span class="text-center badge badge-danger">Sin pagar</span>';
						$c      = "danger";
						$nombre = "Pagar";
						$icon   = "dollar-sign";
					} else {
						if ($saldo < $reg['costo_parcial'] && $saldo > "0") {
							$estado = '<span class="text-center badge badge-warning">En proceso</span>';
							$c = "warning";
							$nombre = "Pagar";
							$icon = "dollar-sign";
						} else {

							if ($saldo <= "0" || $saldo == "0") {
								$estado = '<span class="text-center badge badge-success">Pagado</span>';
								$c = "success";
								$nombre = "Ver";
								$info = "success";
								$icon = "eye";
							} else {
							    $estado = '<span class="text-center badge badge-success">Error</span>';
							}
							//$estado = '<span class="text-center badge badge-success">Terminado</span>';
						}
					}

					$data[]=array(
						"0"=>$cont++,
		 				"1"=>($reg['estado'])?'<button class="btn btn-warning btn-sm" onclick="mostrar('.$reg['idsubcontrato'].')"><i class="fas fa-pencil-alt"></i></button>'.
		 					' <button class="btn btn-danger btn-sm" onclick="desactivar('.$reg['idsubcontrato'].')"><i class="fas fa-times"></i></button>'.
							' <button class="btn btn-danger  btn-sm" onclick="eliminar(' . $reg['idsubcontrato'] . ')"><i class="fas fa-skull-crossbones"></i> </button>'.
		 					' <button class="btn btn-info btn-sm" onclick="ver_datos('.$reg['idsubcontrato'].')"><i class="far fa-eye"></i></button>':
							'<button class="btn btn-warning btn-sm" onclick="mostrar('.$reg['idsubcontrato'].')"><i class="fa fa-pencil-alt"></i></button>'.
		 					' <button class="btn btn-primary btn-sm" onclick="activar('.$reg['idsubcontrato'].')"><i class="fa fa-check"></i></button>'.
		 					' <button class="btn btn-info btn-sm" onclick="ver_datos('.$reg['idsubcontrato'].')"><i class="far fa-eye"></i></button>',
						"2"=>$reg['forma_de_pago'], 
						"3"=>'<div class="user-block">
								<span class="username" style="margin-left: 0px !important;"> <p class="text-primary" style="margin-bottom: 0.2rem !important";>'.$reg['tipo_comprobante'].'</p> </span>
								<span class="description" style="margin-left: 0px !important;">N° '.(empty($reg['numero_comprobante'])?" - ":$reg['numero_comprobante']).'</span>         
							 </div>',
						"4"=> date("d/m/Y", strtotime($reg['fecha_subcontrato'])), 
						"5"=>'S/. '.number_format($reg['subtotal'], 2, '.', ','),
						"6"=>'S/. '.number_format($reg['igv'], 2, '.', ','),
						"7"=>'S/. '.number_format($reg['costo_parcial'], 2, '.', ','),
						"8"=>'<div class="text-center text-nowrap"> 
								<button class="btn btn-' . $c . ' btn-xs" onclick="listar_pagos(' .$reg['idsubcontrato']. ')"><i class="fas fa-' . $icon . ' nav-icon"></i> ' . $nombre . '</button> ' .
								' <button style="font-size: 14px;" class="btn btn-' . $c . ' btn-xs">' . number_format($reg['total_deposito'], 2, '.', ',') . '</button> 
							</div>',
						"9"=>number_format($saldo, 2, '.', ','),
					   	"10"=>'<textarea cols="30" rows="1" class="text_area_clss" readonly="">'.$reg['descripcion'].'</textarea>',
						"11"=>$comprobante
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

	case 'select2Proveedor': 

		$rspta=$sub_contrato->select2_proveedor();

		while ($reg = $rspta->fetch_object())	{

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