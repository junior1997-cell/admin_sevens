<?php
ob_start();
if (strlen(session_id()) < 1){
	session_start();//Validamos si existe o no la sesión
}
require_once "../modelos/Compra.php";

$compra=new Compra();

$idproyecto		       = isset($_POST["idproyecto"])? limpiarCadena($_POST["idproyecto"]):""; 
$idcompra_proyecto	   = isset($_POST["idcompra_proyecto"])? limpiarCadena($_POST["idcompra_proyecto"]):""; 
$idproveedor		   = isset($_POST["idproveedor"])? limpiarCadena($_POST["idproveedor"]):"";
$fecha_compra	       = isset($_POST["fecha_compra"])? limpiarCadena($_POST["fecha_compra"]):"";
$tipo_comprovante	   = isset($_POST["tipo_comprovante"])? limpiarCadena($_POST["tipo_comprovante"]):"";
$serie_comprovante	   = isset($_POST["serie_comprovante"])? limpiarCadena($_POST["serie_comprovante"]):"";
$descripcion		   = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
$total_venta		   = isset($_POST["total_venta"])? limpiarCadena($_POST["total_venta"]):"";
//$idproyecto, $idproveedor, $fecha_compra, $tipo_comprovante, $serie_comprovante, $descripcion
//,$_POST["idproducto"],$_POST["cantidad"]_POST["precio_unitario"],$_POST["descuento"]
//============factura========================
//$idproyectof,$idfacturacompra,$idcompra_proyecto,$codigo
$idproyectof         = isset($_POST["idproyectof"])? limpiarCadena($_POST["idproyectof"]):"";
$idfacturacompra     = isset($_POST["idfacturacompra"])? limpiarCadena($_POST["idfacturacompra"]):"";
$idcomp_proyecto     = isset($_POST["idcomp_proyecto"])? limpiarCadena($_POST["idcomp_proyecto"]):"";
$codigo              = isset($_POST["codigo"])? limpiarCadena($_POST["codigo"]):"";
$monto_compraa       = isset($_POST["monto_compraa"])? limpiarCadena($_POST["monto_compraa"]):"";
$fecha_emision       = isset($_POST["fecha_emision"])? limpiarCadena($_POST["fecha_emision"]):"";
$descripcion_f       = isset($_POST["descripcion_f"])? limpiarCadena($_POST["descripcion_f"]):"";
$subtotal_compraa    = isset($_POST["subtotal_compraa"])? limpiarCadena($_POST["subtotal_compraa"]):"";
$igv_compraa         = isset($_POST["igv_compraa"])? limpiarCadena($_POST["igv_compraa"]):"";
$nota                = isset($_POST["nota"])? limpiarCadena($_POST["nota"]):"";

$doc_img               = isset($_POST["foto2"])? limpiarCadena($_POST["foto2"]):"";

///==============DATOS PAGO COMPRA==============

$beneficiario_pago   = isset($_POST["beneficiario_pago"])? limpiarCadena($_POST["beneficiario_pago"]):"";
$forma_pago          = isset($_POST["forma_pago"])? limpiarCadena($_POST["forma_pago"]):"";
$tipo_pago           = isset($_POST["tipo_pago"])? limpiarCadena($_POST["tipo_pago"]):"";
$cuenta_destino_pago = isset($_POST["cuenta_destino_pago"])? limpiarCadena($_POST["cuenta_destino_pago"]):"";
$banco_pago          = isset($_POST["banco_pago"])? limpiarCadena($_POST["banco_pago"]):"";
$titular_cuenta_pago = isset($_POST["titular_cuenta_pago"])? limpiarCadena($_POST["titular_cuenta_pago"]):"";
$fecha_pago          = isset($_POST["fecha_pago"])? limpiarCadena($_POST["fecha_pago"]):"";
$monto_pago          = isset($_POST["monto_pago"])? limpiarCadena($_POST["monto_pago"]):"";
$numero_op_pago      = isset($_POST["numero_op_pago"])? limpiarCadena($_POST["numero_op_pago"]):"";
$descripcion_pago    = isset($_POST["descripcion_pago"])? limpiarCadena($_POST["descripcion_pago"]):"";
$idcompra_proyecto_p  = isset($_POST["idcompra_proyecto_p"])? limpiarCadena($_POST["idcompra_proyecto_p"]):"";
$idpago_compras      = isset($_POST["idpago_compras"])? limpiarCadena($_POST["idpago_compras"]):"";
///$idpago_compras,$idcompra_proyecto_p,$descripcion_pago,$numero_op_pago,$monto_pago,$fecha_pago,$titular_cuenta_pago,$banco_pago,$cuenta_destino_pago,$tipo_pago,$forma_pago,$beneficiario_pago
$idproveedor_pago     = isset($_POST["idproveedor_pago"])? limpiarCadena($_POST["idproveedor_pago"]):"";

$imagen1			 = isset($_POST["foto1"])? limpiarCadena($_POST["foto1"]):"";

switch ($_GET["op"]){

	case 'guardaryeditarcompra':
		if (!isset($_SESSION["nombre"])) {

		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.

		} else {
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['acceso']==1)
			{
				$clavehash="";				 
				
				if ( !empty($clave) ) {
					//Hash SHA256 en la contraseña
					$clavehash = hash("SHA256",$clave);
				} else {
					if (!empty($clave_old)) {
						// enviamos la contraseña antigua
						$clavehash = $clave_old;
					} else {
						//Hash SHA256 en la contraseña
						$clavehash = hash("SHA256","1234");
					}
					
					
				}				

				if (empty($idusuario)){

					$rspta=$compra->insertar($idproyecto,$idproveedor,$fecha_compra,$tipo_comprovante,$serie_comprovante,$descripcion,$total_venta,$_POST["idproducto"],$_POST["cantidad"], $_POST["precio_unitario"],$_POST["descuento"]);

					echo $rspta ? "ok" : "No se pudieron registrar todos los datos del usuario";
				} else {
				//	$rspta=$compra->editar($idusuario, $trabajador_old, $trabajador, $cargo, $login, $clavehash, $permiso);
					
					echo $rspta ? "ok" : "Usuario no se pudo actualizar";
				}
				//Fin de las validaciones de acceso
			} else {

		  		require 'noacceso.php';
			}
		}		
	break;

	case 'anular':
		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['acceso']==1) {

				$rspta=$compra->desactivar($idcompra_proyecto);

 				echo $rspta ? "ok" : "Compra no se puede Anular";
				//Fin de las validaciones de acceso
			} else {

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
				$rspta=$compra->activar($idusuario);
 				echo $rspta ? "ok" : "Usuario no se puede activar";
			//Fin de las validaciones de acceso
			}
			else
			{
		  	require 'noacceso.php';
			}
		}		
	break;

	case 'listar_compra':
		$nube_idproyecto= $_GET["nube_idproyecto"];
		$rspta=$compra->listar_compra($nube_idproyecto);
		//Vamos a declarar un array
		$data= Array();
		$c="info";
		$nombre="Ver";
		$info="info";
		$icon="eye";

		while ($reg=$rspta->fetch_object()){

			$rspta2=$compra->pago_servicio($reg->idcompra_proyecto);

			empty($rspta2)?$saldo=0:$saldo = $reg->monto_total-$rspta2['total_pago_compras'];

			$data[]=array(
				"0"=>(($reg->estado=='Aceptado')?'<button class="btn btn-warning btn-sm" onclick="mostrar('.$reg->idcompra_proyecto.')" data-toggle="tooltip" data-original-title="Ver detalle"><i class="fa fa-eye"></i></button>'.
					' <button class="btn btn-danger btn-sm"  onclick="anular('.$reg->idcompra_proyecto.')" data-toggle="tooltip" data-original-title="Anular venta"><i class="far fa-trash-alt"></i></button>':
					'<button class="btn btn-warning btn-sm" onclick="mostrar('.$reg->idcompra_proyecto.')"data-toggle="tooltip" data-original-title="Ver detalle"><i class="fa fa-eye"></i></button>').
					' ',
				"1"=>date("d/m/Y", strtotime($reg->fecha_compra)),
				"2"=>$reg->razon_social,
				"3"=>$reg->tipo_comprovante,
				"4"=>$reg->serie_comprovante,
				"5"=>$reg->monto_total,
				"6"=>'<div class="text-center"> <button class="btn btn-'.$c.' btn-xs" onclick="listar_pagos('.$reg->idcompra_proyecto.','.$reg->idproyecto.')"><i class="fas fa-'.$icon.' nav-icon"></i> '.$reg->idcompra_proyecto.'</button> '.'
				<button class="btn btn-'.$c.' btn-xs">'.number_format($reg->monto_total, 2, '.', ',').'</button> </div>',
				"7"=>number_format($saldo, 2, '.', ','),
				"8"=>'<center> <button class="btn btn-info" onclick="facturas_compras('.$reg->idcompra_proyecto.','.$reg->idproyecto.')"><i class="fas fa-file-invoice fa-lg"></i></button> </center>',
				"9"=>$reg->descripcion,
				"10"=>($reg->estado=='Aceptado')?'<span class="badge bg-success">Aceptado</span>':
				'<span class="badge bg-danger">Anulado</span>'
				);
		}
		$results = array(
			"sEcho"=>1, //Información para el datatables
			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
			"aaData"=>$data);
		echo json_encode($results);

	break;
	
	case 'listar_compraxporvee':
		$nube_idproyecto= $_GET["nube_idproyecto"];
		$rspta=$compra->listar_compraxporvee($nube_idproyecto);
		//Vamos a declarar un array
		$data= Array();
		$c="info";
		$nombre="Ver";
		$info="info";
		$icon="eye";

		while ($reg=$rspta->fetch_object()){
			$data[]=array(
				"0"=>'<button class="btn btn-info btn-sm" onclick="listar_facuras_proveedor('.$reg->idproveedor.','.$reg->idproyecto.')" data-toggle="tooltip" data-original-title="Ver detalle"><i class="fa fa-eye"></i></button>',
				"1"=>$reg->razon_social,
				"2"=>$reg->total
				);
		}
		$results = array(
			"sEcho"=>1, //Información para el datatables
			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
			"aaData"=>$data);
		echo json_encode($results);

	break;
	case 'listar_detalle_compraxporvee':

		$idproyecto= $_GET["idproyecto"];
		$idproveedor= $_GET["idproveedor"];
		/*$idproyecto= '2';
		$idproveedor= '4';*/
		$rspta=$compra->listar_detalle_comprax_provee($idproyecto,$idproveedor);
		//Vamos a declarar un array
		$data= Array();

		while ($reg=$rspta->fetch_object()){
			$data[]=array(
				"0"=>'<center><button class="btn btn-info btn-sm" onclick="ver_detalle_compras('.$reg->idcompra_proyecto.')" data-toggle="tooltip" data-original-title="Ver detalle">Ver detalle <i class="fa fa-eye"></i></button></center>',
				"1"=>date("d/m/Y", strtotime($reg->fecha_compra)),
				"2"=>$reg->tipo_comprovante,
				"3"=>$reg->serie_comprovante,
				"4"=>$reg->monto_total,
				"5"=>$reg->descripcion,
				"6"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':
				'<span class="label bg-red">Anulado</span>'
				);
		}
		$results = array(
			"sEcho"=>1, //Información para el datatables
			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
			"aaData"=>$data);
		echo json_encode($results);

	break;

	case 'ver_compra':
		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['servicio_maquina']==1)
			{
				//$idpago_compras ='1';
				$rspta=$compra->ver_compra($idcompra_proyecto);
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

    case 'listarMaterialescompra':
        require_once "../modelos/Materiales.php";
        $planta=new Materiales();

        $rspta = $planta->listar();
        //Vamos a declarar un array
        $datas= Array();
        // echo json_encode($rspta);
            $img = "";
            $color_stock ="";
			$ficha_tecnica="";
        while ( $reg = $rspta->fetch_object() ){
            if (!empty($reg->imagen)) {
                $img = $reg->imagen;
            } else {
                $img = "img_material_defect.jpg";        
            }  
			empty($reg->ficha_tecnica)?$ficha_tecnica='<a target="_blank" href="../dist/ficha_tecnica_materiales/'.$reg->ficha_tecnica.'"><i class="far fa-file-pdf fa-2x" style="color:#000000c4"></i></a>':$ficha_tecnica='<a target="_blank" href="../dist/ficha_tecnica_materiales/'.$reg->ficha_tecnica.'"><i class="far fa-file-pdf fa-2x" style="color:#ff0000c4"></i></a>';
            //empty($reg->ficha_tecnica)?$ficha_tecnica='si':$ficha_tecnica='no';
			$datas[]=array(
                "0"=>'<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idproducto.',\''.$reg->nombre.'\',\''.$reg->precio_unitario.'\',\''.$img.'\')" data-toggle="tooltip" data-original-title="Agregar Planta"><span class="fa fa-plus"></span></button>',
                "1"=>'<div class="user-block">
                        <img class="profile-user-img img-responsive img-circle" src="../dist/img/materiales/'.$img.'" alt="user image">
                        <span class="username"><p style="margin-bottom: 0px !important;">'.$reg->nombre.'</p></span>
                        <span class="description">...</span>
                    </div>',
                "2"=>$reg->marca,
                "3"=>$reg->precio_unitario,
                "4"=>$reg->descripcion,
                "5"=>$ficha_tecnica
                );
        }

        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($datas), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($datas), //enviamos el total registros a visualizar
            "aaData"=>$datas
        );
        echo json_encode($results);
    break;

	case 'selectProveedor': 
        require_once "../modelos/AllProveedor.php";
        $proveedor = new Proveedor();

		$rspta = $proveedor->listar_compra();


		while ($reg = $rspta->fetch_object())
				{
				echo '<option value=' . $reg->idproveedor. '>' . $reg->razon_social .' - '. $reg->ruc . '</option>';
				}
	break;
	/**======================== */
	/**SECCION FACTURAS */
	case 'guardaryeditar_factura':
		if (!isset($_SESSION["nombre"])) {

		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.

		} else {
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['servicio_maquina']==1)
			{
					// imgen de perfil
				if (!file_exists($_FILES['foto2']['tmp_name']) || !is_uploaded_file($_FILES['foto2']['tmp_name'])) {

						$doc_img=$_POST["foto2_actual"]; $flat_img1 = false;

					} else {

						$ext1 = explode(".", $_FILES["foto2"]["name"]); $flat_img1 = true;						

						$doc_img = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

						move_uploaded_file($_FILES["foto2"]["tmp_name"], "../dist/img/facturas_compras/" . $doc_img);
					
				}


				if (empty($idfacturacompra)){
					
					$rspta=$compra->insertar_factura($idproyectof,$idcomp_proyecto,$codigo,$monto_compraa,$fecha_emision,$descripcion_f,$doc_img,$subtotal_compraa,$igv_compraa);
					echo $rspta ? "ok" : "No se pudieron registrar todos los datos de factura compras";
				}
				else {
					// validamos si existe LA IMG para eliminarlo
					if ($flat_img1 == true) {

						$datos_f1 = $compra->obtenerDoc($idfacturacompra);
			
						$img_doc_ant = $datos_f1->fetch_object()->imagen;
			
						if ($img_doc_ant != "") {
			
							unlink("../dist/img/facturas_compras/".$img_doc_ant);
						}
					}
					
					$rspta=$compra->editar_factura($idproyectof,$idfacturacompra,$idcomp_proyecto,$codigo,$monto_compraa,$fecha_emision,$descripcion_f,$doc_img,$subtotal_compraa,$igv_compraa);
					
					echo $rspta ? "ok" : "Servicio no se pudo actualizar";
				}
				//Fin de las validaciones de acceso
			} else {

		  		require 'noacceso.php';
			}
		}		
	break;

	case 'listar_facturas':
		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['servicio_maquina']==1)
			{	
				//$_GET["nube_idproyecto"]
				$idcompra_proyecto =$_GET["idcompra_proyecto"];
				$idproyecto =$_GET["idproyecto"];
				//$idmaquinaria ='3';
				//$idproyecto ='2';
				$rspta=$compra->listar_facturas($idcompra_proyecto,$idproyecto);
		 		//Vamos a declarar un array
				 //$banco='';
		 		$data= Array();
				$suma=0;
				$imagen='';
		 		while ($reg=$rspta->fetch_object()){
					$suma=$suma+$reg->monto;
					if (strlen($reg->descripcion) >= 20 ) { $descripcion = substr($reg->descripcion, 0, 20).'...';  } else { $descripcion = $reg->descripcion; }
					if (strlen($reg->nota) >= 20 ) { $nota = substr($reg->nota, 0, 20).'...';  } else { $nota = $reg->nota; }
					empty($reg->imagen)?$imagen='<div><center><a type="btn btn-danger" class=""><i class="far fa-sad-tear fa-2x"></i></a></center></div>':$imagen='<div><center><a type="btn btn-danger" class=""  href="#" onclick="ver_modal_factura('."'".$reg->imagen."'".')"><i class="fas fa-file-invoice fa-2x"></i></a></center></div>';
					$tool = '"tooltip"';   $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>"; 
		 			$data[]=array(
		 				"0"=>($reg->estado)?'<button class="btn btn-warning btn-sm" onclick="mostrar_factura('.$reg->idfacturacompra .')"><i class="fas fa-pencil-alt"></i></button>'.
						 ' <button class="btn btn-danger btn-sm" onclick="desactivar_factura('.$reg->idfacturacompra .')"><i class="far fa-trash-alt"></i></button>':
						 '<button class="btn btn-warning btn-sm" onclick="mostrar_factura('.$reg->idfacturacompra .')"><i class="fa fa-pencil-alt"></i></button>'.
						 ' <button class="btn btn-primary btn-sm" onclick="activar_factura('.$reg->idfacturacompra .')"><i class="fa fa-check"></i></button>',
		 				"1"=>$reg->codigo,	 				
		 				"2"=>date("d/m/Y", strtotime($reg->fecha_emision)),
		 				"3"=>number_format($reg->subtotal, 4, '.', ','),
		 				"4"=>number_format($reg->igv, 4, '.', ','),
		 				"5"=>number_format($reg->monto, 2, '.', ','),
		 				"6"=>empty($reg->descripcion)?'-':'<div data-toggle="tooltip" data-original-title="'.$reg->descripcion.'">'.$descripcion.'</div>',
						"7"=>$imagen,
					   	"8"=>($reg->estado)?'<span class="text-center badge badge-success">Activado</span>'.$toltip:
						 '<span class="text-center badge badge-danger">Desactivado</span>'.$toltip
		 				);

		 		}
				//$suma=array_sum($rspta->fetch_object()->monto);
		 		$results = array(
		 			"sEcho"=>1, //Información para el datatables
		 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
		 			"iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
		 			"data"=>$data,
					"suma"=>$suma);
		 		echo json_encode($results);
			//Fin de las validaciones de acceso
			}
			else
			{
		  	require 'noacceso.php';
			}
		}
	break;

	case 'total_monto_f':

		$idcompra_proyecto=$_POST["idcompra_proyecto"];
		$idproyecto=$_POST["idproyecto"];
		//$idmaquinaria='1';
		//$idproyecto='1';

		$rspta=$compra->total_monto_f($idcompra_proyecto,$idproyecto);
		//Codificar el resultado utilizando json
		echo json_encode($rspta);
		//Fin de las validaciones de acceso


	break;

	case 'desactivar_factura':
		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['servicio_maquina']==1)
			{
				$rspta=$compra->desactivar_factura($idfacturacompra);
 				echo $rspta ? "Factura Anulada" : "Factura no se puede Anular";
			//Fin de las validaciones de acceso
			}
			else
			{
		  	require 'noacceso.php';
			}
		}		
	break;

	case 'activar_factura':
		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['servicio_maquina']==1)
			{
				$rspta=$compra->activar_factura($idfacturacompra);
 				echo $rspta ? "Factura Restablecida" : "Factura no se pudo Restablecido";
			//Fin de las validaciones de acceso
			}
			else
			{
		  	require 'noacceso.php';
			}
		}		
	break;

	case 'mostrar_factura':
		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['servicio_maquina']==1)
			{
				//$idpago_compras ='1';
				$rspta=$compra->mostrar_factura($idfacturacompra);
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
	/**========== FIN FACTURAS ============= */

		
	/**
	 * ==============SECCION PAGOS=====
	 */

	//MOSTRANDO DATOS DE PROVEEDOR
	case 'most_datos_prov_pago':

		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['servicio_maquina']==1)
			{
				//$idservicioo='1';
				$idcompra_proyecto=$_POST["idcompra_proyecto"];
				//$idcompra_proyecto='1';
				$rspta=$compra->most_datos_prov_pago($idcompra_proyecto);
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

	
	case 'guardaryeditar_pago':
		if (!isset($_SESSION["nombre"])) {

		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.

		} else {
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['servicio_maquina']==1)
			{
					// imgen de perfil
				if (!file_exists($_FILES['foto1']['tmp_name']) || !is_uploaded_file($_FILES['foto1']['tmp_name'])) {

						$imagen1=$_POST["foto1_actual"]; $flat_img1 = false;

					} else {

						$ext1 = explode(".", $_FILES["foto1"]["name"]); $flat_img1 = true;						

						$imagen1 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

						move_uploaded_file($_FILES["foto1"]["tmp_name"], "../dist/img/vauchers_pagos/" . $imagen1);
					
				}


				if (empty($idpago_compras )){
					//$idpago_compras,$idcompra_proyecto_p,$descripcion_pago,$numero_op_pago,$monto_pago,$fecha_pago,$titular_cuenta_pago,$banco_pago,$cuenta_destino_pago,$tipo_pago,$forma_pago,$beneficiario_pago
					
					$rspta=$compra->insertar_pago($idcompra_proyecto_p,$idproveedor_pago,$beneficiario_pago,$forma_pago,$tipo_pago,$cuenta_destino_pago,$banco_pago,$titular_cuenta_pago,$fecha_pago,$monto_pago,$numero_op_pago,$descripcion_pago,$imagen1);
					echo $rspta ? "ok" : "No se pudieron registrar todos los datos de servicio";
				}else {
					// validamos si existe LA IMG para eliminarlo
					if ($flat_img1 == true) {

						$datos_f1 = $compra->obtenerImg($idpago_compras );
			
						$img1_ant = $datos_f1->fetch_object()->imagen;
			
						if ($img1_ant != "") {
			
							unlink("../dist/img/vauchers_pagos/" . $img1_ant);
						}
					}
					
					$rspta=$compra->editar_pago($idpago_compras,$idcompra_proyecto_p,$idproveedor_pago,$beneficiario_pago,$forma_pago,$tipo_pago,$cuenta_destino_pago,$banco_pago,$titular_cuenta_pago,$fecha_pago,$monto_pago,$numero_op_pago,$descripcion_pago,$idcompra_proyecto,$imagen1);
					
					echo $rspta ? "ok" : "Servicio no se pudo actualizar";
				}
				//Fin de las validaciones de acceso
			} else {

		  		require 'noacceso.php';
			}
		}		
	break;

	case 'desactivar_pagos':
		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['servicio_maquina']==1)
			{
				$rspta=$compra->desactivar_pagos($idpago_compras);
 				echo $rspta ? "Pago Anulado" : "Pago no se puede Anular";
			//Fin de las validaciones de acceso
			}
			else
			{
		  	require 'noacceso.php';
			}
		}		
	break;

	case 'activar_pagos':
		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['servicio_maquina']==1)
			{
				$rspta=$compra->activar_pagos($idpago_compras);
 				echo $rspta ? "Pago Restablecido" : "Pago no se pudo Restablecido";
			//Fin de las validaciones de acceso
			}
			else
			{
		  	require 'noacceso.php';
			}
		}		
	break;

	case 'listar_pagos_proveedor':
		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['servicio_maquina']==1)
			{	
				//$_GET["nube_idproyecto"]
				$idcompra_proyecto =$_GET["idcompra_proyecto"];
				/*$idproyecto =$_GET["idproyecto"];
				$tipopago ='Proveedor';*/
				//$idmaquinaria ='3';
				//$idproyecto ='2';
				$rspta=$compra->listar_pagos($idcompra_proyecto);
		 		//Vamos a declarar un array
				 //$banco='';
		 		$data= Array();
				$suma=0;
				$imagen='';
		 		while ($reg=$rspta->fetch_object()){
					$suma=$suma+$reg->monto;
					if (strlen($reg->descripcion) >= 20 ) { $descripcion = substr($reg->descripcion, 0, 20).'...';  } else { $descripcion = $reg->descripcion; }
					if (strlen($reg->titular_cuenta) >= 20 ) { $titular_cuenta = substr($reg->titular_cuenta, 0, 20).'...';  } else {$titular_cuenta = $reg->titular_cuenta; }
					empty($reg->imagen)?$imagen='<div><center><a type="btn btn-danger" class=""><i class="far fa-sad-tear fa-2x"></i></a></center></div>':$imagen='<div><center><a type="btn btn-danger" class=""  href="#" onclick="ver_modal_vaucher('."'".$reg->imagen."'".')"><i class="fas fa-file-invoice-dollar fa-2x"></i></a></center></div>';
					$tool = '"tooltip"';   $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>"; 
		 			$data[]=array(
		 				"0"=>($reg->estado)?'<button class="btn btn-warning btn-sm" onclick="mostrar_pagos('.$reg->idpago_compras .')"><i class="fas fa-pencil-alt"></i></button>'.
						 ' <button class="btn btn-danger btn-sm" onclick="desactivar_pagos('.$reg->idpago_compras .')"><i class="far fa-trash-alt"></i></button>':
						 '<button class="btn btn-warning btn-sm" onclick="mostrar_pagos('.$reg->idpago_compras .')"><i class="fa fa-pencil-alt"></i></button>'.
						 ' <button class="btn btn-primary btn-sm" onclick="activar_pagos('.$reg->idpago_compras .')"><i class="fa fa-check"></i></button>',
		 				"1"=>$reg->forma_pago,	 				
		 				"2"=>$reg->beneficiario,		 				
		 				"3"=>$reg->cuenta_destino,		 				
		 				"4"=>$reg->banco,
		 				"5"=>'<div data-toggle="tooltip" data-original-title="'.$reg->titular_cuenta.'">'.$titular_cuenta.'</div>',
		 				"6"=>date("d/m/Y", strtotime($reg->fecha_pago)),
		 				"7"=>empty($reg->descripcion)?'-':'<div data-toggle="tooltip" data-original-title="'.$reg->descripcion.'">'.$descripcion.'</div>',
		 				"8"=>$reg->numero_operacion,
		 				"9"=>number_format($reg->monto, 2, '.', ','),
						"10"=>$imagen,
					   	"11"=>($reg->estado)?'<span class="text-center badge badge-success">Activado</span>'.$toltip:
						 '<span class="text-center badge badge-danger">Desactivado</span>'.$toltip
		 				);

		 		}
				//$suma=array_sum($rspta->fetch_object()->monto);
		 		$results = array(
		 			"sEcho"=>1, //Información para el datatables
		 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
		 			"iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
		 			"data"=>$data,
					"suma"=>$suma);
		 		echo json_encode($results);
			//Fin de las validaciones de acceso
			}
			else
			{
		  	require 'noacceso.php';
			}
		}
	break;
	case 'listar_pagos_detraccion':
		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['servicio_maquina']==1)
			{	
				//$_GET["nube_idproyecto"]
				$idmaquinaria =$_GET["idmaquinaria"];
				$idproyecto =$_GET["idproyecto"];
				$tipopago ='Detraccion';
				//$idmaquinaria ='3';
				//$idproyecto ='2';
				$rspta=$compra->listar_pagos($idmaquinaria,$idproyecto,$tipopago);
		 		//Vamos a declarar un array
				 //$banco='';
		 		$data= Array();
				$suma=0;
				$imagen='';
		 		while ($reg=$rspta->fetch_object()){
					$suma=$suma+$reg->monto;
					if (strlen($reg->descripcion) >= 20 ) { $descripcion = substr($reg->descripcion, 0, 20).'...';  } else { $descripcion = $reg->descripcion; }
					if (strlen($reg->titular_cuenta) >= 20 ) { $titular_cuenta = substr($reg->titular_cuenta, 0, 20).'...';  } else {$titular_cuenta = $reg->titular_cuenta; }
					empty($reg->imagen)?$imagen='<div><center><a type="btn btn-danger" class=""><i class="far fa-sad-tear fa-2x"></i></a></center></div>':$imagen='<div><center><a type="btn btn-danger" class=""  href="#" onclick="ver_modal_vaucher('."'".$reg->imagen."'".')"><i class="fas fa-file-invoice-dollar fa-2x"></i></a></center></div>';
					$tool = '"tooltip"';   $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>"; 
		 			$data[]=array(
		 				"0"=>($reg->estado)?'<button class="btn btn-warning btn-sm" onclick="mostrar_pagos('.$reg->idpago_compras .','.$reg->id_maquinaria.')"><i class="fas fa-pencil-alt"></i></button>'.
						 ' <button class="btn btn-danger btn-sm" onclick="desactivar_pagos('.$reg->idpago_compras .','.$reg->id_maquinaria.')"><i class="far fa-trash-alt"></i></button>':
						 '<button class="btn btn-warning btn-sm" onclick="mostrar_pagos('.$reg->idpago_compras .','.$reg->id_maquinaria.')"><i class="fa fa-pencil-alt"></i></button>'.
						 ' <button class="btn btn-primary btn-sm" onclick="activar_pagos('.$reg->idpago_compras .','.$reg->id_maquinaria.')"><i class="fa fa-check"></i></button>',
		 				"1"=>$reg->forma_pago ,	 				
		 				"2"=>$reg->beneficiario,		 				
		 				"3"=>$reg->cuenta_destino,		 				
		 				"4"=>$reg->banco,
		 				"5"=>'<div data-toggle="tooltip" data-original-title="'.$reg->titular_cuenta.'">'.$titular_cuenta.'</div>',
		 				"6"=>date("d/m/Y", strtotime($reg->fecha_pago)),
		 				"7"=>empty($reg->descripcion)?'-':'<div data-toggle="tooltip" data-original-title="'.$reg->descripcion.'">'.$descripcion.'</div>',
		 				"8"=>$reg->numero_operacion,
		 				"9"=>number_format($reg->monto, 2, '.', ','),
						"10"=>$imagen,
					   	"11"=>($reg->estado)?'<span class="text-center badge badge-success">Activado</span>'.$toltip:
						 '<span class="text-center badge badge-danger">Desactivado</span>'.$toltip
		 				);

		 		}
				//$suma=array_sum($rspta->fetch_object()->monto);
		 		$results = array(
		 			"sEcho"=>1, //Información para el datatables
		 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
		 			"iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
		 			"data"=>$data,
					"suma"=>$suma);
		 		echo json_encode($results);
			//Fin de las validaciones de acceso
			}
			else
			{
		  	require 'noacceso.php';
			}
		}
	break;
	case 'suma_total_pagos_proveedor':

		$idcompra_proyecto=$_POST["idcompra_proyecto"];
		//$idproyecto=$_POST["idproyecto"];
		//$tipopago ='Proveedor';
		//$idmaquinaria='1';
		//$idproyecto='1';

		$rspta=$compra->suma_total_pagos($idcompra_proyecto);
		//Codificar el resultado utilizando json
		echo json_encode($rspta);
		//Fin de las validaciones de acceso


	break;
	case 'suma_total_pagos_detracc':

		$idmaquinaria=$_POST["idmaquinaria"];
		$idproyecto=$_POST["idproyecto"];
		$tipopago ='Detraccion';
		//$idmaquinaria='1';
		//$idproyecto='1';

		$rspta=$compra->suma_total_pagos($idmaquinaria,$idproyecto,$tipopago);
		//Codificar el resultado utilizando json
		echo json_encode($rspta);
		//Fin de las validaciones de acceso


	break;
	case 'total_costo_parcial_pago':

		$idmaquinaria=$_POST["idmaquinaria"];
		$idproyecto=$_POST["idproyecto"];
		//$idmaquinaria='1';
		//$idproyecto='2';

		$rspta=$compra->total_costo_parcial_pago($idmaquinaria,$idproyecto);
		//Codificar el resultado utilizando json
		echo json_encode($rspta);
		//Fin de las validaciones de acceso


	break;
	
	case 'mostrar_pagos':
		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['servicio_maquina']==1)
			{
				//$idpago_compras ='1';
				$rspta=$compra->mostrar_pagos($idpago_compras);
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


	/**
	 * ==============FIN SECCION PAGOS=====
	 */

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