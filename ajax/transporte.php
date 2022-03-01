<?php
ob_start();
if (strlen(session_id()) < 1){
	session_start();//Validamos si existe o no la sesión
}
require_once "../modelos/Transporte.php";

$transporte=new Transporte();
 //transporte.js $idtransporte,$idproyecto,$fecha_viaje,$tipo_viajero,$tipo_ruta,$cantidad,$precio_unitario,$precio_parcial,$ruta,$descripcion,$foto2
$idproyecto       = isset($_POST["idproyecto"])? limpiarCadena($_POST["idproyecto"]):"";	
$idtransporte     = isset($_POST["idtransporte"])? limpiarCadena($_POST["idtransporte"]):"";	
$fecha_viaje      = isset($_POST["fecha_viaje"])? limpiarCadena($_POST["fecha_viaje"]):"";
$tipo_viajero	  = isset($_POST["tipo_viajero"])? limpiarCadena($_POST["tipo_viajero"]):"";
$tipo_ruta	      = isset($_POST["tipo_ruta"])? limpiarCadena($_POST["tipo_ruta"]):"";
$cantidad           = isset($_POST["cantidad"])? limpiarCadena($_POST["cantidad"]):"";
$precio_unitario  = isset($_POST["precio_unitario"])? limpiarCadena($_POST["precio_unitario"]):"";
$precio_parcial   = isset($_POST["precio_parcial"])? limpiarCadena($_POST["precio_parcial"]):"";
$ruta             = isset($_POST["ruta"])? limpiarCadena($_POST["ruta"]):"";
$descripcion	  = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";

$forma_pago = isset($_POST["forma_pago"])? limpiarCadena($_POST["forma_pago"]):"";
$tipo_comprobante = isset($_POST["tipo_comprobante"])? limpiarCadena($_POST["tipo_comprobante"]):"";
$nro_comprobante  = isset($_POST["nro_comprobante"])? limpiarCadena($_POST["nro_comprobante"]):"";
$subtotal         = isset($_POST["subtotal"])? limpiarCadena($_POST["subtotal"]):"";
$igv              = isset($_POST["igv"])? limpiarCadena($_POST["igv"]):"";

$foto2		      = isset($_POST["foto2"])? limpiarCadena($_POST["foto2"]):"";

switch ($_GET["op"]){
	case 'guardaryeditar':
		if (!isset($_SESSION["nombre"])) {

		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los materials logueados al sistema.

		} else {
			//Validamos el acceso solo al material logueado y autorizado.
			if ($_SESSION['viatico']==1)
			{

				// Comprobante
				if (!file_exists($_FILES['foto2']['tmp_name']) || !is_uploaded_file($_FILES['foto2']['tmp_name'])) {

					$comprobante=$_POST["foto2_actual"]; $flat_ficha1 = false;

				} else {

					$ext1 = explode(".", $_FILES["foto2"]["name"]); $flat_ficha1 = true;						

					$comprobante = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

					move_uploaded_file($_FILES["foto2"]["tmp_name"], "../dist/img/comprob_transporte/" . $comprobante);
				
				}


				if (empty($idtransporte)){
					//var_dump($idproyecto,$idproveedor);
					$rspta=$transporte->insertar($idproyecto,$fecha_viaje,$tipo_viajero,$tipo_ruta,$cantidad,$precio_unitario,$precio_parcial,$ruta,$descripcion,$forma_pago,$tipo_comprobante,$nro_comprobante,$subtotal,$igv,$comprobante);
					echo $rspta ? "ok" : "No se pudieron registrar todos los datos";
				}
				else {
					//validamos si existe comprobante para eliminarlo
					if ($flat_ficha1 == true) {

						$datos_ficha1 = $transporte->ficha_tec($idtransporte);
			
						$ficha1_ant = $datos_ficha1->fetch_object()->comprobante;
			
						if ($ficha1_ant != "") {
			
							unlink("../dist/img/comprob_transporte/" . $ficha1_ant);
						}
					}

					$rspta=$transporte->editar($idtransporte,$idproyecto,$fecha_viaje,$tipo_viajero,$tipo_ruta,$cantidad,$precio_unitario,$precio_parcial,$ruta,$descripcion,$forma_pago,$tipo_comprobante,$nro_comprobante,$subtotal,$igv,$comprobante);
					//var_dump($idtransporte,$idproveedor);
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
			if ($_SESSION['viatico']==1)
			{
				$rspta=$transporte->desactivar($idtransporte);
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
			if ($_SESSION['viatico']==1)
			{
				$rspta=$transporte->activar($idtransporte);
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
			if ($_SESSION['viatico']==1)
			{
				//$idtransporte='1';
				$rspta=$transporte->mostrar($idtransporte);
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
				//$idtransporte='1';
				$rspta=$transporte->mostrar($idtransporte);
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

				$rspta=$transporte->total($idproyecto);
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
				$rspta=$transporte->listar($idproyecto);
		 		//Vamos a declarar un array
		 		$data= Array();
				$comprobante = '';
				$cont=1;
		 		while ($reg=$rspta->fetch_object()){

					// empty($reg->comprobante)?$comprobante='<div><center><a type="btn btn-danger" class=""><i class="far fa-times-circle fa-2x"></i></a></center></div>':$comprobante='<center><a target="_blank" href="../dist/img/comprob_transporte/'.$reg->comprobante.'"><i class="far fa-file-pdf fa-2x" style="color:#ff0000c4"></i></a></center>';
		 			
					
					 empty($reg->comprobante)?$comprobante='<div><center><a type="btn btn-danger" class=""><i class="far fa-times-circle fa-2x"></i></a></center></div>':$comprobante='<div><center><a type="btn btn-danger" class=""  href="#" onclick="modal_comprobante('."'".$reg->comprobante."'".')"><i class="fas fa-file-invoice-dollar fa-2x"></i></a></center></div>';
					 if (strlen($reg->descripcion) >= 20 ) { $descripcion = substr($reg->descripcion, 0, 20).'...';  } else { $descripcion = $reg->descripcion; }
					 $tool = '"tooltip"';   $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>"; 
					 $data[]=array(
						"0"=>$cont++,
		 				"1"=>($reg->estado)?'<button class="btn btn-warning btn-sm" onclick="mostrar('.$reg->idtransporte.')"><i class="fas fa-pencil-alt"></i></button>'.
		 					' <button class="btn btn-danger btn-sm" onclick="desactivar('.$reg->idtransporte.')"><i class="far fa-trash-alt"></i></button>'.
		 					' <button class="btn btn-info btn-sm" onclick="ver_datos('.$reg->idtransporte.')"><i class="far fa-eye"></i></button>':
							'<button class="btn btn-warning btn-sm" onclick="mostrar('.$reg->idtransporte.')"><i class="fa fa-pencil-alt"></i></button>'.
		 					' <button class="btn btn-primary btn-sm" onclick="activar('.$reg->idtransporte.')"><i class="fa fa-check"></i></button>'.
		 					' <button class="btn btn-info btn-sm" onclick="ver_datos('.$reg->idtransporte.')"><i class="far fa-eye"></i></button>',
						"2"=>$reg->forma_de_pago, 
						"3"=>'<div class="user-block">
								<span class="username" style="margin-left: 0px !important;"> <p class="text-primary" style="margin-bottom: 0.2rem !important";>'.$reg->tipo_comprobante.'</p> </span>
								<span class="description" style="margin-left: 0px !important;">N° '.(empty($reg->numero_comprobante)?" - ":$reg->numero_comprobante).'</span>         
							</div>',
						"4"=> date("d/m/Y", strtotime($reg->fecha_viaje)), 
						"5"=>number_format($reg->subtotal, 2, '.', ','),
						"6"=>number_format($reg->igv, 2, '.', ','),
						"7"=>number_format($reg->precio_parcial, 2, '.', ','),
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