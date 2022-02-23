<?php 
	ob_start();

	if (strlen(session_id()) < 1){

		session_start();//Validamos si existe o no la sesión
	}

	if (!isset($_SESSION["nombre"])) {

		header("Location: login.html");//Validamos el acceso solo a los usuarios logueados al sistema.

	} else {
		//Validamos el acceso solo al usuario logueado y autorizado.
		if ($_SESSION['compra']==1)	{ 

			require_once "../modelos/Resumen_activos_fijos_general.php";

			$resumen_af_g=new Resumen_activos_fijos_general();

			switch ($_GET["op"]){
				
				case 'listar_tbla_principal':

					$rspta=$resumen_af_g->listar_tbla_principal();
					//Vamos a declarar un array
					$data= Array();

          			$imagen_error = "this.src='../dist/img/default/img_defecto_activo_fijo.png'";
					//'.$reg->idproyecto.', '.$reg['idproducto'] .', \'' . $reg['nombre_producto'] . '\', \''. $reg['promedio_precio'] . '\', \''. number_format($reg['subtotal'], 2, ".", ",") .'\'
					foreach ($rspta as $key => $reg) {

						$data[]=array(

						"0"=>'<div class="user-block">
							<img class="img-circle" src="../dist/docs/material/img_perfil/'. $reg['imagen'] .'" alt="User Image" onerror="'.$imagen_error.'">
							<span class="username"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'. $reg['nombre_producto'] .'</p></span>
							<span class="description"> <b>Color:</b> '. $reg['nombre_color'] .' </span>
						</div>',
						"1"=>$reg['nombre_medida'],
						"2"=>$reg['cantidad'],
						"3"=>'<button class="btn btn-info btn-sm mb-2" onclick="ver_precios_y_mas('.$reg['idproducto'] .', \'' . $reg['nombre_producto'] . '\', \''. $reg['promedio_precio'] . '\', \''. number_format($reg['subtotal'], 2, ".", ",") .'\')"><i class="far fa-eye"></i></button><span> S/. '. number_format($reg['promedio_precio'], 2, ".", ",") . '</span>'  ,
						"4"=>'S/. '.number_format($reg['precio_actual'], 2, ".", ","),
						"5"=>'S/. '. number_format($reg['subtotal'], 2, ".", ",")
						);
					}

					$results = array(
						"sEcho"=>1, //Información para el datatables
						"iTotalRecords"=>count($data), //enviamos el total registros al datatable
						"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
						"aaData"=>$data);
					echo json_encode($results);

				break;

				case 'ver_precios_y_mas':

					 $idproducto = $_GET["idproducto"];

					$rspta=$resumen_af_g->ver_precios_y_mas($idproducto);
					//Vamos a declarar un array
					$data= Array();	
          
					$ficha_tecnica = ""; 

					foreach ($rspta as $key => $reg) {
						// validamos si existe una ficha tecnica
						!empty($reg['ficha_tecnica']) ? ($ficha_tecnica = '<center><a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg['ficha_tecnica'] . '"><i class="far fa-file-pdf fa-2x text-success"></i></a></center>') : ($ficha_tecnica = '<center><span class="text-center"> <i class="far fa-times-circle fa-2x text-danger"></i></span></center>');
									
						$data[]=array(
							"0" =>date("d/m/Y", strtotime($reg['fecha_compra'])),
							"1"=>$reg['cantidad'],
							"2"=> '<h4> <b>'. number_format( $reg['precio_con_igv'], 2, ".", ",") .'</b> </h4>',
							"3"=>'S/. '. number_format($reg['descuento'] , 2, ".", ","),
							"4"=>'S/. '. number_format($reg['subtotal'], 2, ".", ","),
							"5"=>$ficha_tecnica,
						);
					}

					$results = array(
						"sEcho"=>1, //Información para el datatables
						"iTotalRecords"=>count($data), //enviamos el total registros al datatable
						"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
						"aaData"=>$data);
					echo json_encode($results);

				break;

				case 'suma_total_compras':

					$rspta = $resumen_af_g->suma_total_compras();

					echo json_encode($rspta);
				break;
			}
			//Fin de las validaciones de acceso
		} else {

			require 'noacceso.php';
		}
	}
	ob_end_flush();
?>