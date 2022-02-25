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
			require_once "../modelos/Compra.php";
			$compra = new Compra();

			switch ($_GET["op"]){

				/**tipo clasificacion MAQUINARIA */
				case 'listar_tbla_principal_maq':
					
					$clacificacion =2;
					$rspta=$resumen_af_g->listar_tbla_principal_general($clacificacion);
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
						"3"=>'<button class="btn btn-info btn-sm mb-2" onclick="ver_precios_y_mas_maq('.$reg['idproducto'] .', \'' . $reg['nombre_producto'] . '\', \''. $reg['promedio_precio'] . '\', \''. number_format($reg['subtotal'], 2, ".", ",") .'\')"><i class="far fa-eye"></i></button><span> S/. '. number_format($reg['promedio_precio'], 2, ".", ",") . '</span>'  ,
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

				case 'ver_precios_y_mas_maq':

					 $idproducto = $_GET["idproducto"];

					$rspta=$resumen_af_g->ver_precios_y_mas($idproducto);
					//Vamos a declarar un array
					$data= Array();	
          
					$ficha_tecnica = ""; 

					foreach ($rspta as $key => $reg) {
						// validamos si existe una ficha tecnica
						!empty($reg['ficha_tecnica']) ? ($ficha_tecnica = '<center><a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg['ficha_tecnica'] . '"><i class="far fa-file-pdf fa-2x text-success"></i></a></center>') : ($ficha_tecnica = '<center><span class="text-center"> <i class="far fa-times-circle fa-2x text-danger"></i></span></center>');
									
						$data[]=array(
							"0"=>'<button class="btn btn-warning btn-sm" onclick="editar_detalle_compras('.$reg['idcompra'].')" data-toggle="tooltip" data-original-title="Editar compra"><i class="fas fa-pencil-alt"></i></button>',
							"1"=>'<span class="text-primary font-weight-bold" >' . $reg['proveedor'] . '</span>',  
							"2"=>date("d/m/Y", strtotime($reg['fecha_compra'])),
							"3"=>$reg['cantidad'],
							"4"=> '<h4> <b>'. number_format( $reg['precio_con_igv'], 2, ".", ",") .'</b> </h4>',
							"5"=>'S/. '. number_format($reg['descuento'] , 2, ".", ","),
							"6"=>'S/. '. number_format($reg['subtotal'], 2, ".", ","),
							"7"=>$ficha_tecnica,
						);
					}

					$results = array(
						"sEcho"=>1, //Información para el datatables
						"iTotalRecords"=>count($data), //enviamos el total registros al datatable
						"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
						"aaData"=>$data);
					echo json_encode($results);

				break;

				case 'suma_total_compras_maq':
					$clacificacion =2;
					$rspta = $resumen_af_g->suma_total_compras($clacificacion);

					echo json_encode($rspta);
				break;
				/**tipo clasificacion EQUIPOS */
				case 'listar_tbla_principal_equip':
					
					$clacificacion =3;
					$rspta=$resumen_af_g->listar_tbla_principal_general($clacificacion);
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
						"3"=>'<button class="btn btn-info btn-sm mb-2" onclick="ver_precios_y_mas_equip('.$reg['idproducto'] .', \'' . $reg['nombre_producto'] . '\', \''. $reg['promedio_precio'] . '\', \''. number_format($reg['subtotal'], 2, ".", ",") .'\')"><i class="far fa-eye"></i></button><span> S/. '. number_format($reg['promedio_precio'], 2, ".", ",") . '</span>'  ,
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

				case 'ver_precios_y_mas_equip':

					 $idproducto = $_GET["idproducto"];

					$rspta=$resumen_af_g->ver_precios_y_mas($idproducto);
					//Vamos a declarar un array
					$data= Array();	
          
					$ficha_tecnica = ""; 

					foreach ($rspta as $key => $reg) {
						// validamos si existe una ficha tecnica
						!empty($reg['ficha_tecnica']) ? ($ficha_tecnica = '<center><a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg['ficha_tecnica'] . '"><i class="far fa-file-pdf fa-2x text-success"></i></a></center>') : ($ficha_tecnica = '<center><span class="text-center"> <i class="far fa-times-circle fa-2x text-danger"></i></span></center>');
									
						$data[]=array(
							"0"=>'<button class="btn btn-warning btn-sm" onclick="editar_detalle_compras('.$reg['idcompra'].')" data-toggle="tooltip" data-original-title="Editar compra"><i class="fas fa-pencil-alt"></i></button>',
							"1"=>'<span class="text-primary font-weight-bold" >' . $reg['proveedor'] . '</span>',  
							"2"=>date("d/m/Y", strtotime($reg['fecha_compra'])),
							"3"=>$reg['cantidad'],
							"4"=> '<h4> <b>'. number_format( $reg['precio_con_igv'], 2, ".", ",") .'</b> </h4>',
							"5"=>'S/. '. number_format($reg['descuento'] , 2, ".", ","),
							"6"=>'S/. '. number_format($reg['subtotal'], 2, ".", ","),
							"7"=>$ficha_tecnica,
						);
					}

					$results = array(
						"sEcho"=>1, //Información para el datatables
						"iTotalRecords"=>count($data), //enviamos el total registros al datatable
						"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
						"aaData"=>$data);
					echo json_encode($results);

				break;

				case 'suma_total_compras_equip':
					$clacificacion =3;
					$rspta = $resumen_af_g->suma_total_compras($clacificacion);

					echo json_encode($rspta);
				break;
				/**tipo clasificacion HERRAMIENTAS */
				case 'listar_tbla_principal_herra':
					
					$clacificacion =4;
					$rspta=$resumen_af_g->listar_tbla_principal_general($clacificacion);
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
						"3"=>'<button class="btn btn-info btn-sm mb-2" onclick="ver_precios_y_mas_herra('.$reg['idproducto'] .', \'' . $reg['nombre_producto'] . '\', \''. $reg['promedio_precio'] . '\', \''. number_format($reg['subtotal'], 2, ".", ",") .'\')"><i class="far fa-eye"></i></button><span> S/. '. number_format($reg['promedio_precio'], 2, ".", ",") . '</span>'  ,
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

				case 'ver_precios_y_mas_herra':

					 $idproducto = $_GET["idproducto"];

					$rspta=$resumen_af_g->ver_precios_y_mas($idproducto);
					//Vamos a declarar un array
					$data= Array();	
          
					$ficha_tecnica = ""; 

					foreach ($rspta as $key => $reg) {
						// validamos si existe una ficha tecnica
						!empty($reg['ficha_tecnica']) ? ($ficha_tecnica = '<center><a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg['ficha_tecnica'] . '"><i class="far fa-file-pdf fa-2x text-success"></i></a></center>') : ($ficha_tecnica = '<center><span class="text-center"> <i class="far fa-times-circle fa-2x text-danger"></i></span></center>');
									
						$data[]=array(
							"0"=>'<button class="btn btn-warning btn-sm" onclick="editar_detalle_compras('.$reg['idcompra'].')" data-toggle="tooltip" data-original-title="Editar compra"><i class="fas fa-pencil-alt"></i></button>',
							"1"=>'<span class="text-primary font-weight-bold" >' . $reg['proveedor'] . '</span>',  
							"2"=>date("d/m/Y", strtotime($reg['fecha_compra'])),
							"3"=>$reg['cantidad'],
							"4"=> '<h4> <b>'. number_format( $reg['precio_con_igv'], 2, ".", ",") .'</b> </h4>',
							"5"=>'S/. '. number_format($reg['descuento'] , 2, ".", ","),
							"6"=>'S/. '. number_format($reg['subtotal'], 2, ".", ","),
							"7"=>$ficha_tecnica,
						);
					}

					$results = array(
						"sEcho"=>1, //Información para el datatables
						"iTotalRecords"=>count($data), //enviamos el total registros al datatable
						"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
						"aaData"=>$data);
					echo json_encode($results);

				break;

				case 'suma_total_compras_herra':
					$clacificacion =4;
					$rspta = $resumen_af_g->suma_total_compras($clacificacion);

					echo json_encode($rspta);
				break;
				/**tipo clasificacion OFICINA */
				case 'listar_tbla_principal_oficina':
					
					$clacificacion =5;
					$rspta=$resumen_af_g->listar_tbla_principal_general($clacificacion);
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
						"3"=>'<button class="btn btn-info btn-sm mb-2" onclick="ver_precios_y_mas_oficina('.$reg['idproducto'] .', \'' . $reg['nombre_producto'] . '\', \''. $reg['promedio_precio'] . '\', \''. number_format($reg['subtotal'], 2, ".", ",") .'\')"><i class="far fa-eye"></i></button><span> S/. '. number_format($reg['promedio_precio'], 2, ".", ",") . '</span>'  ,
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

				case 'ver_precios_y_mas_oficina':

					 $idproducto = $_GET["idproducto"];

					$rspta=$resumen_af_g->ver_precios_y_mas($idproducto);
					//Vamos a declarar un array
					$data= Array();	
          
					$ficha_tecnica = ""; 

					foreach ($rspta as $key => $reg) {
						// validamos si existe una ficha tecnica
						!empty($reg['ficha_tecnica']) ? ($ficha_tecnica = '<center><a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg['ficha_tecnica'] . '"><i class="far fa-file-pdf fa-2x text-success"></i></a></center>') : ($ficha_tecnica = '<center><span class="text-center"> <i class="far fa-times-circle fa-2x text-danger"></i></span></center>');
									
						$data[]=array(
							"0"=>'<button class="btn btn-warning btn-sm" onclick="editar_detalle_compras('.$reg['idcompra'].')" data-toggle="tooltip" data-original-title="Editar compra"><i class="fas fa-pencil-alt"></i></button>',
							"1"=>'<span class="text-primary font-weight-bold" >' . $reg['proveedor'] . '</span>',  
							"2"=>date("d/m/Y", strtotime($reg['fecha_compra'])),
							"3"=>$reg['cantidad'],
							"4"=> '<h4> <b>'. number_format( $reg['precio_con_igv'], 2, ".", ",") .'</b> </h4>',
							"5"=>'S/. '. number_format($reg['descuento'] , 2, ".", ","),
							"6"=>'S/. '. number_format($reg['subtotal'], 2, ".", ","),
							"7"=>$ficha_tecnica,
						);
					}

					$results = array(
						"sEcho"=>1, //Información para el datatables
						"iTotalRecords"=>count($data), //enviamos el total registros al datatable
						"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
						"aaData"=>$data);
					echo json_encode($results);

				break;

				case 'suma_total_compras_oficina':
					$clacificacion =5;
					$rspta = $resumen_af_g->suma_total_compras($clacificacion);

					echo json_encode($rspta);
				break;

				/**editar compra por proyecto */
				case 'ver_compra_editar':
	
					$rspta = $compra->mostrar_compra_para_editar($idcompra_proyecto);
					//Codificar el resultado utilizando json
					echo json_encode($rspta);
				
				break;

				case 'listarMaterialescompra':
					

					$rspta = $resumen_af_g->listar_productos();
					//Vamos a declarar un array
					$datas = [];
					// echo json_encode($rspta);
					$img = "";
					$imagen_error = "this.src='../dist/svg/default_producto.svg'";
					$color_stock = "";
					$ficha_tecnica = "";

					while ($reg = $rspta->fetch_object()) {

					if (!empty($reg->imagen)) {   $img = "../dist/docs/material/img_perfil/$reg->imagen"; } else { $img = "../dist/svg/default_producto.svg"; }

					!empty($reg->ficha_tecnica) ? ($ficha_tecnica = '<center><a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg->ficha_tecnica . '"><i class="far fa-file-pdf fa-2x text-success"></i></a></center>') : ($ficha_tecnica = '<center><span class="text-center"> <i class="far fa-times-circle fa-2x text-danger"></i></span></center>');
					
					$datas[] = [
						"0" => '<button class="btn btn-warning" onclick="agregarDetalleComprobante(' . $reg->idproducto . ', \'' . htmlspecialchars($reg->nombre, ENT_QUOTES) . '\', \'' . $reg->nombre_medida . '\', \'' . $reg->nombre_color . '\', \'' . $reg->precio_sin_igv . '\', \'' . $reg->precio_igv . '\', \'' . $reg->precio_total . '\', \'' . $reg->imagen . '\', \'' . $reg->ficha_tecnica . '\')" data-toggle="tooltip" data-original-title="Agregar Planta">
						<span class="fa fa-plus"></span>
						</button>',
						"1" => '<div class="user-block w-px-200"> <img class="profile-user-img img-responsive img-circle" src="' . $img .  '" alt="user image" onerror="' . $imagen_error . '"> 
						<span class="username"><p style="margin-bottom: 0px !important;">' .   $reg->nombre . '</p></span> 
						<span class="description"><b>Color: </b>' .$reg->nombre_color . '</span>
						<span class="description"><b>Marca: </b>' .$reg->marca . '</span>
						</div>',
						"2" => $reg->categoria,
						"3" => number_format($reg->precio_unitario, 2, '.', ','),
						"4" => '<textarea cols="30" rows="1" class="text_area_clss" readonly >'.$reg->descripcion.'</textarea>',
						"5" => $ficha_tecnica,
					];
					}

					$results = [
					"sEcho" => 1, //Información para el datatables
					"iTotalRecords" => count($datas), //enviamos el total registros al datatable
					"iTotalDisplayRecords" => count($datas), //enviamos el total registros a visualizar
					"aaData" => $datas,
					];
					echo json_encode($results);
				break;

			}
			/** ==========FIN CLASIFICACIONES==============0 */
			//Fin de las validaciones de acceso
		} else {

			require 'noacceso.php';
		}
	}
	ob_end_flush();
?>