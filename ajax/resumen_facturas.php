<?php 
	ob_start();

	if (strlen(session_id()) < 1){

		session_start();//Validamos si existe o no la sesión
	}

	if (!isset($_SESSION["nombre"])) {

		header("Location: login.html");//Validamos el acceso solo a los usuarios logueados al sistema.

	} else {
		//Validamos el acceso solo al usuario logueado y autorizado.
		if ($_SESSION['resumen_activo_fijo_general']==1)	{ 
			//Resumen facturas

			require_once "../modelos/Resumen_facturas.php";
			$resumen_fact=new Resumenfacturas();

			switch ($_GET["op"]){

				case 'listar_facturas_compras':
																																										
					if (!isset($_SESSION["nombre"])) {
						header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
					} else {
							//Validamos el acceso solo al usuario logueado y autorizado.
						if ($_SESSION['resumen_activo_fijo_general'] == 1) {
							
							$rspta=$resumen_fact->facturas_compras($_GET['id_proyecto']);
							$cont=1;
							//Vamos a declarar un array
							$data= Array();

							//$imagen_error = "this.src='../dist/img/default/img_defecto_activo_fijo.png'";
							while ($reg = $rspta->fetch_object()) {

								$data[]=array(
								"0"=>$cont++,
								"1" => '<div> 
											<span class="text-primary font-weight-bold">' .  $reg->razon_social . '</span>
										</div>',
								"2" =>$reg->serie_comprovante,
								"3"=>$reg->fecha_compra,
								"4"=>'<span> S/. '. number_format($reg->subtotal, 2, ".", ",") . '</span>'  ,
								"5"=>'S/. '.number_format($reg->igv, 2, ".", ","),
								"6"=>'S/. '. number_format($reg->monto_total, 2, ".", ",")
								);
							}

							$results = array(
								"sEcho"=>1, //Información para el datatables
								"iTotalRecords"=>count($data), //enviamos el total registros al datatable
								"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
								"aaData"=>$data);
							echo json_encode($results);
							//Fin de las validaciones de acceso
						} else {
							require 'noacceso.php';
						}
					} 

				break;
				case 'suma_total_compras_maq':
																																					
					if (!isset($_SESSION["nombre"])) {
						header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
					} else {
							//Validamos el acceso solo al usuario logueado y autorizado.
						if ($_SESSION['resumen_activo_fijo_general'] == 1) {

							$clacificacion =2;
							$rspta = $resumen_fact->suma_total_compras($clacificacion);

							echo json_encode($rspta);

							//Fin de las validaciones de acceso
						} else {
							require 'noacceso.php';
						}
					} 

				break;

				case 'listar_facturas_maquinaria':
																																					
					if (!isset($_SESSION["nombre"])) {
						header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
					} else {
							//Validamos el acceso solo al usuario logueado y autorizado.
						if ($_SESSION['resumen_activo_fijo_general'] == 1) {

							$rspta=$resumen_fact->facturas_maquinarias_equipos($_GET['id_proyecto'],'1');
							$cont=1;
							//Vamos a declarar un array
							$data= Array();

							//$imagen_error = "this.src='../dist/img/default/img_defecto_activo_fijo.png'";
							while ($reg = $rspta->fetch_object()) {

								$data[]=array(
								"0"=>$cont++,
								"1" => '<div> 
											<span class="text-primary font-weight-bold">' .  $reg->razon_social . '</span>
										</div>',
								"2" =>$reg->codigo,
								"3"=>$reg->fecha_emision,
								"4"=>'<span> S/. '. number_format($reg->subtotal, 2, ".", ",") . '</span>'  ,
								"5"=>'S/. '.number_format($reg->igv, 2, ".", ","),
								"6"=>'S/. '. number_format($reg->monto, 2, ".", ",")
								);
							}

							$results = array(
								"sEcho"=>1, //Información para el datatables
								"iTotalRecords"=>count($data), //enviamos el total registros al datatable
								"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
								"aaData"=>$data);
							echo json_encode($results);
							//Fin de las validaciones de acceso
						} else {
							require 'noacceso.php';
						}
					} 

				break;
				case 'suma_total_compras_equip':
																																
					if (!isset($_SESSION["nombre"])) {
						header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
					} else {
							//Validamos el acceso solo al usuario logueado y autorizado.
						if ($_SESSION['resumen_activo_fijo_general'] == 1) {

							$rspta = $resumen_fact->suma_total_maquinaria_equipos();

							echo json_encode($rspta);
							//Fin de las validaciones de acceso
						} else {
							require 'noacceso.php';
						}
					} 
					
				break;

				case 'listar_facturas_equipos':
																											
					if (!isset($_SESSION["nombre"])) {
						header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
					} else {
							//Validamos el acceso solo al usuario logueado y autorizado.
						if ($_SESSION['resumen_activo_fijo_general'] == 1) {

							$rspta=$resumen_fact->facturas_maquinarias_equipos($_GET['id_proyecto'],'2');
							$cont=1;
							//Vamos a declarar un array
							$data= Array();

							//$imagen_error = "this.src='../dist/img/default/img_defecto_activo_fijo.png'";
							while ($reg = $rspta->fetch_object()) {

								$data[]=array(
								"0"=>$cont++,
								"1" => '<div> 
											<span class="text-primary font-weight-bold">' .  $reg->razon_social . '</span>
										</div>',
								"2" =>$reg->codigo,
								"3"=>$reg->fecha_emision,
								"4"=>'<span> S/. '. number_format($reg->subtotal, 2, ".", ",") . '</span>'  ,
								"5"=>'S/. '.number_format($reg->igv, 2, ".", ","),
								"6"=>'S/. '. number_format($reg->monto, 2, ".", ",")
								);
							}

							$results = array(
								"sEcho"=>1, //Información para el datatables
								"iTotalRecords"=>count($data), //enviamos el total registros al datatable
								"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
								"aaData"=>$data);
							echo json_encode($results);
							//Fin de las validaciones de acceso
						} else {
							require 'noacceso.php';
						}
					} 

				break;
				case 'suma_total_compras_herra':
																						
					if (!isset($_SESSION["nombre"])) {
						header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
					} else {
							//Validamos el acceso solo al usuario logueado y autorizado.
						if ($_SESSION['resumen_activo_fijo_general'] == 1) {

							$rspta = $resumen_fact->suma_total_maquinaria_equipos();

							echo json_encode($rspta);
							//Fin de las validaciones de acceso
						} else {
							require 'noacceso.php';
						}
					} 

				break;

			}
			//:::::::::::::::::::::::::::::::: */
			/** ========================0 */
			//Fin de las validaciones de acceso
		} else {

			require 'noacceso.php';
		}
	}
	ob_end_flush();
?>