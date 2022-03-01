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
			$parametro_list_general="listarMaterialescompra_general"; $parametro_list_proyecto="listarMaterialescompra_proyecto";
			//Resumen activos general
			require_once "../modelos/Resumen_activos_fijos_general.php";
			$resumen_af_g=new Resumen_activos_fijos_general();

			// ::::::::::::::::::::::::::::::::: D A T O S   C O M P R A S  G E N E R A L :::::::::::::::::::::::::::::

			$idcompra_af_general = isset($_POST["idcompra_general"]) ? limpiarCadena($_POST["idcompra_general"]) : "";
			$idproveedor = isset($_POST["idproveedor"]) ? limpiarCadena($_POST["idproveedor"]) : "";
			$fecha_compra = isset($_POST["fecha_compra"]) ? limpiarCadena($_POST["fecha_compra"]) : "";
			$tipo_comprovante = isset($_POST["tipo_comprovante"]) ? limpiarCadena($_POST["tipo_comprovante"]) : "";
			$serie_comprovante = isset($_POST["serie_comprovante"]) ? limpiarCadena($_POST["serie_comprovante"]) : "";
			$descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";

			$subtotal_compra = isset($_POST["subtotal_compra"]) ? limpiarCadena($_POST["subtotal_compra"]) : "";
			$igv_compra = isset($_POST["igv_compra"]) ? limpiarCadena($_POST["igv_compra"]) : "";
			$total_compra = isset($_POST["total_venta"]) ? limpiarCadena($_POST["total_venta"]) : "";

			//$estado_detraccion = isset($_POST["estado_detraccion"]) ? limpiarCadena($_POST["estado_detraccion"]) : "";
			//:::::::::::::::::::::::::::::::: D A T O S  C O M P R A S  P O R  P R O Y E C T O:::::::::::::::::::::::::::::
			
			$idproyecto_p = isset($_POST["idproyecto_proy"]) ? limpiarCadena($_POST["idproyecto_proy"]) : "";
			$idcompra_proyecto_p = isset($_POST["idcompra_af_proy"]) ? limpiarCadena($_POST["idcompra_af_proy"]) : "";
			$idproveedor_p = isset($_POST["idproveedor_proy"]) ? limpiarCadena($_POST["idproveedor_proy"]) : "";
			$fecha_compra_p = isset($_POST["fecha_compra_proy"]) ? limpiarCadena($_POST["fecha_compra_proy"]) : "";
			$tipo_comprobante_p = isset($_POST["tipo_comprobante_proy"]) ? limpiarCadena($_POST["tipo_comprobante_proy"]) : "";
			$serie_comprobante_p = isset($_POST["serie_comprobante_proy"]) ? limpiarCadena($_POST["serie_comprobante_proy"]) : "";
			$descripcion_p = isset($_POST["descripcion_proy"]) ? limpiarCadena($_POST["descripcion_proy"]) : "";
			
			$total_compra_proy_p = isset($_POST["total_compra_proy"]) ? limpiarCadena($_POST["total_compra_proy"]) : "";
			$subtotal_compra_p = isset($_POST["subtotal_compra_proy"]) ? limpiarCadena($_POST["subtotal_compra_proy"]) : "";
			$igv_compra_p = isset($_POST["igv_compra_proy"]) ? limpiarCadena($_POST["igv_compra_proy"]) : "";
			
			$estado_detraccion_p = isset($_POST["estado_detraccion"]) ? limpiarCadena($_POST["estado_detraccion"]) : "";
			// :::::::::::::::::::::::::::::::::::: D A T O S   M A T E R I A L E S ::::::::::::::::::::::::::::::::::::::

			$idproducto_p     = isset($_POST["idproducto_p"]) ? limpiarCadena($_POST["idproducto_p"]) : "" ;
			$unidad_medida_p  = isset($_POST["unidad_medida_p"]) ? limpiarCadena($_POST["unidad_medida_p"]) : "" ;
			$color_p          = isset($_POST["color_p"]) ? limpiarCadena($_POST["color_p"]) : "" ;
			$categoria_insumos_af_p    = isset($_POST["categoria_insumos_af_p"]) ? limpiarCadena($_POST["categoria_insumos_af_p"]) : "" ;
			$nombre_p         = isset($_POST["nombre_p"]) ? limpiarCadena($_POST["nombre_p"]) : "" ;
			$modelo_p         = isset($_POST["modelo_p"]) ? limpiarCadena($_POST["modelo_p"]) : "" ;
			$serie_p          = isset($_POST["serie_p"]) ? limpiarCadena($_POST["serie_p"]) : "" ;
			$marca_p          = isset($_POST["marca_p"]) ? limpiarCadena($_POST["marca_p"]) : "" ;
			$estado_igv_p     = isset($_POST["estado_igv_p"]) ? limpiarCadena($_POST["estado_igv_p"]) : "" ;
			$precio_unitario_p= isset($_POST["precio_unitario_p"]) ? limpiarCadena($_POST["precio_unitario_p"]) : "" ;      
			$precio_sin_igv_p = isset($_POST["precio_sin_igv_p"]) ? limpiarCadena($_POST["precio_sin_igv_p"]) : "" ;
			$precio_igv_p     = isset($_POST["precio_igv_p"]) ? limpiarCadena($_POST["precio_igv_p"]) : "" ;
			$precio_total_p   = isset($_POST["precio_total_p"]) ? limpiarCadena($_POST["precio_total_p"]) : "" ;      
			$descripcion_p    = isset($_POST["descripcion_p"]) ? limpiarCadena($_POST["descripcion_p"]) : "" ; 
			$img_pefil_p      = isset($_POST["foto2"]) ? limpiarCadena($_POST["foto2"]) : "" ;
			$ficha_tecnica_p  = isset($_POST["doc2"]) ? limpiarCadena($_POST["doc2"]) : "" ;

			// :::::::::::::::::::::::::::::::::::: D A T O S   P R O V E E D O R ::::::::::::::::::::::::::::::::::::::
			
			$idproveedor		= isset($_POST["idproveedor"])? limpiarCadena($_POST["idproveedor"]):"";
			$nombre 		    = isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
			$tipo_documento		= isset($_POST["tipo_documento"])? limpiarCadena($_POST["tipo_documento"]):"";
			$num_documento	    = isset($_POST["num_documento"])? limpiarCadena($_POST["num_documento"]):"";
			$direccion		    = isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
			$telefono		    = isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
			$c_bancaria		    = isset($_POST["c_bancaria"])? limpiarCadena($_POST["c_bancaria"]):"";
			$cci		    	= isset($_POST["cci"])? limpiarCadena($_POST["cci"]):"";
			$c_detracciones		= isset($_POST["c_detracciones"])? limpiarCadena($_POST["c_detracciones"]):"";
			$banco			    = isset($_POST["banco"])? limpiarCadena($_POST["banco"]):"";
			$titular_cuenta		= isset($_POST["titular_cuenta"])? limpiarCadena($_POST["titular_cuenta"]):"";

			switch ($_GET["op"]){

				//:::::::::::::::::::CLASIFICACIONES::::::::::::: */			
				/**tipo clasificacion MAQUINARIA */
				case 'listar_tbla_principal_maq':
																																										
					if (!isset($_SESSION["nombre"])) {
						header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
					} else {
							//Validamos el acceso solo al usuario logueado y autorizado.
						if ($_SESSION['resumen_activo_fijo_general'] == 1) {

							$clacificacion =2;
							$rspta=$resumen_af_g->listar_tbla_principal_general($clacificacion);
							$cont=1;
							//Vamos a declarar un array
							$data= Array();

							$imagen_error = "this.src='../dist/img/default/img_defecto_activo_fijo.png'";
							//'.$reg->idproyecto.', '.$reg['idproducto'] .', \'' . $reg['nombre_producto'] . '\', \''. $reg['promedio_precio'] . '\', \''. number_format($reg['subtotal'], 2, ".", ",") .'\'
							foreach ($rspta as $key => $reg) {

								$data[]=array(
								"0"=>$cont++,
								"1"=>'<button class="btn btn-warning btn-sm" onclick="mostrar_insumo('.$reg['idproducto'] .')"><i class="fas fa-pencil-alt"></i></button>
									<button class="btn btn-info btn-sm" onclick="mostrar_detalle_material('.$reg['idproducto'] .')"><i class="far fa-eye"></i></button>',
								"2" => '<div class="user-block"> 
											<img class="profile-user-img img-responsive img-circle" src="../dist/docs/material/img_perfil/' . $reg['imagen'] . '" alt="User Image" onerror="' .  $imagen_error .  '">
											<span class="username"><p class="text-primary" style="margin-bottom: 0.2rem !important"; >' .  $reg['nombre_producto'] . '</p>
											</span><span class="description"> '.(empty($reg['modelo']) ? '' : '<b class="d-none">═</b> <b >Modelo:</b> ' . $reg['modelo'] ).'</span>
										</div>',
								"3" =>$reg['nombre_color'],
								"4"=>$reg['nombre_medida'],
								"5"=>$reg['cantidad'],
								"6"=>'<button class="btn btn-info btn-sm mb-2" onclick="ver_precios_y_mas_maq('.$reg['idproducto'] .', \'' . $reg['nombre_producto'] . '\', \''. $reg['promedio_precio'] . '\', \''. number_format($reg['subtotal'], 2, ".", ",") .'\')"><i class="far fa-eye"></i></button>',
								"7"=>'<span> S/. '. number_format($reg['promedio_precio'], 2, ".", ",") . '</span>'  ,
								"8"=>'S/. '.number_format($reg['precio_actual'], 2, ".", ","),
								"9"=>'S/. '. number_format($reg['subtotal'], 2, ".", ",")
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

				case 'ver_precios_y_mas_maq':
																																										
					if (!isset($_SESSION["nombre"])) {
						header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
					} else {
							//Validamos el acceso solo al usuario logueado y autorizado.
						if ($_SESSION['resumen_activo_fijo_general'] == 1) {

							$idproducto = $_GET["idproducto"];

							$rspta=$resumen_af_g->ver_precios_y_mas($idproducto);
							//Vamos a declarar un array
							$data= Array();	
				
							$ficha_tecnica = ""; 
							$cont=1;
							foreach ($rspta as $key => $reg) {
								// validamos si existe una ficha tecnica
								!empty($reg['ficha_tecnica']) ? ($ficha_tecnica = '<center><a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg['ficha_tecnica'] . '"><i class="far fa-file-pdf fa-2x text-success"></i></a></center>') : ($ficha_tecnica = '<center><span class="text-center"> <i class="far fa-times-circle fa-2x text-danger"></i></span></center>');
								
								$data[]=array(
									"0"=>$cont++,
									"1"=>(empty($reg['idproyecto'])) ?('<button class="btn btn-warning btn-sm" onclick="editar_detalle_compras_general('.$reg['idcompra'].', \'' . $parametro_list_general . '\');" data-toggle="tooltip" data-original-title="Editar compra"><i class="fas fa-pencil-alt"></i></button>'):
									('<button class="btn btn-warning btn-sm" onclick="editar_detalle_compras_proyecto('.$reg['idcompra'].', \'' . $parametro_list_proyecto . '\');" data-toggle="tooltip" data-original-title="Editar compra"><i class="fas fa-pencil-alt"></i></button>'),
									"2"=>'<span class="text-primary font-weight-bold" >' . $reg['proveedor'] . '</span>',  
									"3"=>date("d/m/Y", strtotime($reg['fecha_compra'])),
									"4"=>$reg['cantidad'],
									"5"=> '<h4> <b>'. number_format( $reg['precio_con_igv'], 2, ".", ",") .'</b> </h4>',
									"6"=>'S/. '. number_format($reg['descuento'] , 2, ".", ","),
									"7"=>'S/. '. number_format($reg['subtotal'], 2, ".", ","),
									"8"=>$ficha_tecnica,
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
							$rspta = $resumen_af_g->suma_total_compras($clacificacion);

							echo json_encode($rspta);

							//Fin de las validaciones de acceso
						} else {
							require 'noacceso.php';
						}
					} 

				break;

				/**tipo clasificacion EQUIPOS */
				case 'listar_tbla_principal_equip':
																																					
					if (!isset($_SESSION["nombre"])) {
						header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
					} else {
							//Validamos el acceso solo al usuario logueado y autorizado.
						if ($_SESSION['resumen_activo_fijo_general'] == 1) {
							$clacificacion =3;
							$rspta=$resumen_af_g->listar_tbla_principal_general($clacificacion);
							$cont=1;
							//Vamos a declarar un array
							$data= Array();

							$imagen_error = "this.src='../dist/img/default/img_defecto_activo_fijo.png'";
							//'.$reg->idproyecto.', '.$reg['idproducto'] .', \'' . $reg['nombre_producto'] . '\', \''. $reg['promedio_precio'] . '\', \''. number_format($reg['subtotal'], 2, ".", ",") .'\'
							foreach ($rspta as $key => $reg) {

								$data[]=array(
									"0"=>$cont++,
									"1"=>'<button class="btn btn-warning btn-sm" onclick="mostrar_insumo('.$reg['idproducto'] .')"><i class="fas fa-pencil-alt"></i></button>
										<button class="btn btn-info btn-sm" onclick="mostrar_detalle_material('.$reg['idproducto'] .')"><i class="far fa-eye"></i></button>',
									"2" => '<div class="user-block"> 
												<img class="profile-user-img img-responsive img-circle" src="../dist/docs/material/img_perfil/' . $reg['imagen'] . '" alt="User Image" onerror="' .  $imagen_error .  '">
												<span class="username"><p class="text-primary" style="margin-bottom: 0.2rem !important"; >' .  $reg['nombre_producto'] . '</p>
												</span><span class="description"> '.(empty($reg['modelo']) ? '' : '<b class="d-none">═</b> <b >Modelo:</b> ' . $reg['modelo'] ).'</span>
											</div>',
									"3" =>$reg['nombre_color'],
									"4"=>$reg['nombre_medida'],
									"5"=>$reg['cantidad'],
									"6"=>'<button class="btn btn-info btn-sm mb-2" onclick="ver_precios_y_mas_maq('.$reg['idproducto'] .', \'' . $reg['nombre_producto'] . '\', \''. $reg['promedio_precio'] . '\', \''. number_format($reg['subtotal'], 2, ".", ",") .'\')"><i class="far fa-eye"></i></button>',
									"7"=>'<span> S/. '. number_format($reg['promedio_precio'], 2, ".", ",") . '</span>'  ,
									"8"=>'S/. '.number_format($reg['precio_actual'], 2, ".", ","),
									"9"=>'S/. '. number_format($reg['subtotal'], 2, ".", ",")
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

				case 'ver_precios_y_mas_equip':
																																					
					if (!isset($_SESSION["nombre"])) {
						header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
					} else {
							//Validamos el acceso solo al usuario logueado y autorizado.
						if ($_SESSION['resumen_activo_fijo_general'] == 1) {


							$idproducto = $_GET["idproducto"];

							$rspta=$resumen_af_g->ver_precios_y_mas($idproducto);
							//Vamos a declarar un array
							$data= Array();	
				
							$ficha_tecnica = ""; 
							$cont=1;
							foreach ($rspta as $key => $reg) {
								// validamos si existe una ficha tecnica
								!empty($reg['ficha_tecnica']) ? ($ficha_tecnica = '<center><a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg['ficha_tecnica'] . '"><i class="far fa-file-pdf fa-2x text-success"></i></a></center>') : ($ficha_tecnica = '<center><span class="text-center"> <i class="far fa-times-circle fa-2x text-danger"></i></span></center>');
											
								$data[]=array(
									"0"=>$cont++,
									"1"=>(empty($reg['idproyecto'])) ?('<button class="btn btn-warning btn-sm" onclick="editar_detalle_compras_general('.$reg['idcompra'].', \'' . $parametro_list_general . '\');" data-toggle="tooltip" data-original-title="Editar compra"><i class="fas fa-pencil-alt"></i></button>'):
									('<button class="btn btn-warning btn-sm" onclick="editar_detalle_compras_proyecto('.$reg['idcompra'].', \'' . $parametro_list_proyecto . '\');" data-toggle="tooltip" data-original-title="Editar compra"><i class="fas fa-pencil-alt"></i></button>'),
									"2"=>'<span class="text-primary font-weight-bold" >' . $reg['proveedor'] . '</span>',  
									"3"=>date("d/m/Y", strtotime($reg['fecha_compra'])),
									"4"=>$reg['cantidad'],
									"5"=> '<h4> <b>'. number_format( $reg['precio_con_igv'], 2, ".", ",") .'</b> </h4>',
									"6"=>'S/. '. number_format($reg['descuento'] , 2, ".", ","),
									"7"=>'S/. '. number_format($reg['subtotal'], 2, ".", ","),
									"8"=>$ficha_tecnica,
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

							$clacificacion =3;
							$rspta = $resumen_af_g->suma_total_compras($clacificacion);

							echo json_encode($rspta);
							//Fin de las validaciones de acceso
						} else {
							require 'noacceso.php';
						}
					} 
					
				break;

				/**tipo clasificacion HERRAMIENTAS */
				case 'listar_tbla_principal_herra':
																											
					if (!isset($_SESSION["nombre"])) {
						header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
					} else {
							//Validamos el acceso solo al usuario logueado y autorizado.
						if ($_SESSION['resumen_activo_fijo_general'] == 1) {

							$clacificacion =4;
							$rspta=$resumen_af_g->listar_tbla_principal_general($clacificacion);
							$cont=1;
							//Vamos a declarar un array
							$data= Array();

							$imagen_error = "this.src='../dist/img/default/img_defecto_activo_fijo.png'";
							//'.$reg->idproyecto.', '.$reg['idproducto'] .', \'' . $reg['nombre_producto'] . '\', \''. $reg['promedio_precio'] . '\', \''. number_format($reg['subtotal'], 2, ".", ",") .'\'
							foreach ($rspta as $key => $reg) {

								$data[]=array(
									"0"=>$cont++,
									"1"=>'<button class="btn btn-warning btn-sm" onclick="mostrar_insumo('.$reg['idproducto'] .')"><i class="fas fa-pencil-alt"></i></button>
										<button class="btn btn-info btn-sm" onclick="mostrar_detalle_material('.$reg['idproducto'] .')"><i class="far fa-eye"></i></button>',
									"2" => '<div class="user-block"> 
												<img class="profile-user-img img-responsive img-circle" src="../dist/docs/material/img_perfil/' . $reg['imagen'] . '" alt="User Image" onerror="' .  $imagen_error .  '">
												<span class="username"><p class="text-primary" style="margin-bottom: 0.2rem !important"; >' .  $reg['nombre_producto'] . '</p>
												</span><span class="description"> '.(empty($reg['modelo']) ? '' : '<b class="d-none">═</b> <b >Modelo:</b> ' . $reg['modelo'] ).'</span>
											</div>',
									"3" =>$reg['nombre_color'],
									"4"=>$reg['nombre_medida'],
									"5"=>$reg['cantidad'],
									"6"=>'<button class="btn btn-info btn-sm mb-2" onclick="ver_precios_y_mas_maq('.$reg['idproducto'] .', \'' . $reg['nombre_producto'] . '\', \''. $reg['promedio_precio'] . '\', \''. number_format($reg['subtotal'], 2, ".", ",") .'\')"><i class="far fa-eye"></i></button>',
									"7"=>'<span> S/. '. number_format($reg['promedio_precio'], 2, ".", ",") . '</span>'  ,
									"8"=>'S/. '.number_format($reg['precio_actual'], 2, ".", ","),
									"9"=>'S/. '. number_format($reg['subtotal'], 2, ".", ",")
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

				case 'ver_precios_y_mas_herra':
																													
					if (!isset($_SESSION["nombre"])) {
						header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
					} else {
							//Validamos el acceso solo al usuario logueado y autorizado.
						if ($_SESSION['resumen_activo_fijo_general'] == 1) {

							$idproducto = $_GET["idproducto"];

							$rspta=$resumen_af_g->ver_precios_y_mas($idproducto);
							//Vamos a declarar un array
							$data= Array();	
				
							$ficha_tecnica = ""; 
							$cont=1;
							foreach ($rspta as $key => $reg) {
								// validamos si existe una ficha tecnica
								!empty($reg['ficha_tecnica']) ? ($ficha_tecnica = '<center><a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg['ficha_tecnica'] . '"><i class="far fa-file-pdf fa-2x text-success"></i></a></center>') : ($ficha_tecnica = '<center><span class="text-center"> <i class="far fa-times-circle fa-2x text-danger"></i></span></center>');
											
								$data[]=array(
									"0"=>$cont++,
									"1"=>(empty($reg['idproyecto'])) ?('<button class="btn btn-warning btn-sm" onclick="editar_detalle_compras_general('.$reg['idcompra'].', \'' . $parametro_list_general . '\');" data-toggle="tooltip" data-original-title="Editar compra"><i class="fas fa-pencil-alt"></i></button>'):
									('<button class="btn btn-warning btn-sm" onclick="editar_detalle_compras_proyecto('.$reg['idcompra'].', \'' . $parametro_list_proyecto . '\');" data-toggle="tooltip" data-original-title="Editar compra"><i class="fas fa-pencil-alt"></i></button>'),
									"2"=>'<span class="text-primary font-weight-bold" >' . $reg['proveedor'] . '</span>',  
									"3"=>date("d/m/Y", strtotime($reg['fecha_compra'])),
									"4"=>$reg['cantidad'],
									"5"=> '<h4> <b>'. number_format( $reg['precio_con_igv'], 2, ".", ",") .'</b> </h4>',
									"6"=>'S/. '. number_format($reg['descuento'] , 2, ".", ","),
									"7"=>'S/. '. number_format($reg['subtotal'], 2, ".", ","),
									"8"=>$ficha_tecnica,
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

							$clacificacion =4;
							$rspta = $resumen_af_g->suma_total_compras($clacificacion);

							echo json_encode($rspta);
							//Fin de las validaciones de acceso
						} else {
							require 'noacceso.php';
						}
					} 

				break;

				/**tipo clasificacion OFICINA */

				case 'listar_tbla_principal_oficina':
																						
					if (!isset($_SESSION["nombre"])) {
						header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
					} else {
							//Validamos el acceso solo al usuario logueado y autorizado.
						if ($_SESSION['resumen_activo_fijo_general'] == 1) {

							$clacificacion =5;
							$rspta=$resumen_af_g->listar_tbla_principal_general($clacificacion);
							$cont=1;
							//Vamos a declarar un array
							$data= Array();

							$imagen_error = "this.src='../dist/img/default/img_defecto_activo_fijo.png'";
							//'.$reg->idproyecto.', '.$reg['idproducto'] .', \'' . $reg['nombre_producto'] . '\', \''. $reg['promedio_precio'] . '\', \''. number_format($reg['subtotal'], 2, ".", ",") .'\'
							foreach ($rspta as $key => $reg) {

								$data[]=array(
									"0"=>$cont++,
									"1"=>'<button class="btn btn-warning btn-sm" onclick="mostrar_insumo('.$reg['idproducto'] .')"><i class="fas fa-pencil-alt"></i></button>
										<button class="btn btn-info btn-sm" onclick="mostrar_detalle_material('.$reg['idproducto'] .')"><i class="far fa-eye"></i></button>',
									"2" => '<div class="user-block"> 
												<img class="profile-user-img img-responsive img-circle" src="../dist/docs/material/img_perfil/' . $reg['imagen'] . '" alt="User Image" onerror="' .  $imagen_error .  '">
												<span class="username"><p class="text-primary" style="margin-bottom: 0.2rem !important"; >' .  $reg['nombre_producto'] . '</p>
												</span><span class="description"> '.(empty($reg['modelo']) ? '' : '<b class="d-none">═</b> <b >Modelo:</b> ' . $reg['modelo'] ).'</span>
											</div>',
									"3" =>$reg['nombre_color'],
									"4"=>$reg['nombre_medida'],
									"5"=>$reg['cantidad'],
									"6"=>'<button class="btn btn-info btn-sm mb-2" onclick="ver_precios_y_mas_maq('.$reg['idproducto'] .', \'' . $reg['nombre_producto'] . '\', \''. $reg['promedio_precio'] . '\', \''. number_format($reg['subtotal'], 2, ".", ",") .'\')"><i class="far fa-eye"></i></button>',
									"7"=>'<span> S/. '. number_format($reg['promedio_precio'], 2, ".", ",") . '</span>'  ,
									"8"=>'S/. '.number_format($reg['precio_actual'], 2, ".", ","),
									"9"=>'S/. '. number_format($reg['subtotal'], 2, ".", ",")
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

				case 'ver_precios_y_mas_oficina':
															
					if (!isset($_SESSION["nombre"])) {
						header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
					} else {
							//Validamos el acceso solo al usuario logueado y autorizado.
						if ($_SESSION['resumen_activo_fijo_general'] == 1) {
							$idproducto = $_GET["idproducto"];

							$rspta=$resumen_af_g->ver_precios_y_mas($idproducto);
							//Vamos a declarar un array
							$data= Array();	
				
							$ficha_tecnica = ""; 
							$cont=1;
							foreach ($rspta as $key => $reg) {
								// validamos si existe una ficha tecnica
								!empty($reg['ficha_tecnica']) ? ($ficha_tecnica = '<center><a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg['ficha_tecnica'] . '"><i class="far fa-file-pdf fa-2x text-success"></i></a></center>') : ($ficha_tecnica = '<center><span class="text-center"> <i class="far fa-times-circle fa-2x text-danger"></i></span></center>');
											
								$data[]=array(
									"0"=>$cont++,
									"1"=>(empty($reg['idproyecto'])) ?('<button class="btn btn-warning btn-sm" onclick="editar_detalle_compras_general('.$reg['idcompra'].', \'' . $parametro_list_general . '\');" data-toggle="tooltip" data-original-title="Editar compra"><i class="fas fa-pencil-alt"></i></button>'):
									('<button class="btn btn-warning btn-sm" onclick="editar_detalle_compras_proyecto('.$reg['idcompra'].', \'' . $parametro_list_proyecto . '\');" data-toggle="tooltip" data-original-title="Editar compra"><i class="fas fa-pencil-alt"></i></button>'),
									"2"=>'<span class="text-primary font-weight-bold" >' . $reg['proveedor'] . '</span>',  
									"3"=>date("d/m/Y", strtotime($reg['fecha_compra'])),
									"4"=>$reg['cantidad'],
									"5"=> '<h4> <b>'. number_format( $reg['precio_con_igv'], 2, ".", ",") .'</b> </h4>',
									"6"=>'S/. '. number_format($reg['descuento'] , 2, ".", ","),
									"7"=>'S/. '. number_format($reg['subtotal'], 2, ".", ","),
									"8"=>$ficha_tecnica,
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

				case 'suma_total_compras_oficina':
																	
					if (!isset($_SESSION["nombre"])) {
						header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
					} else {
							//Validamos el acceso solo al usuario logueado y autorizado.
						if ($_SESSION['resumen_activo_fijo_general'] == 1) {

							$clacificacion =5;
							$rspta = $resumen_af_g->suma_total_compras($clacificacion);

							echo json_encode($rspta);
					
							//Fin de las validaciones de acceso
						} else {
							require 'noacceso.php';
						}
					} 
				break;

				//:::::::::::::::::::FIN CLASIFICACIONES::::::::::::: */

				//:::::::::::::::::::EDITAR COMPRA GENERAL, GUARDAR(MATERIALES Y PROVEEDOR)::::::::::::: */

				case 'ver_compra_editar_general':
					//activos fijos general
					require_once "../modelos/All_activos_fijos.php";
					$all_activos_fijos = new All_activos_fijos();


					if (!isset($_SESSION["nombre"])) {
						header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
					} else {
							//Validamos el acceso solo al usuario logueado y autorizado.
							if ($_SESSION['resumen_activo_fijo_general'] == 1) {
								//contenido
						
							$rspta = $all_activos_fijos->mostrar_compra_para_editar($idcompra_af_general);
							//Codificar el resultado utilizando json
							echo json_encode($rspta);
			
						} else {
							require 'noacceso.php';
						}
					}
			
								 
				break;

				case 'listarMaterialescompra_general':
												
					if (!isset($_SESSION["nombre"])) {
						header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
					} else {
							//Validamos el acceso solo al usuario logueado y autorizado.
						if ($_SESSION['resumen_activo_fijo_general'] == 1) {
								//contenido	
							$rspta = $resumen_af_g->listar_solo_activos();
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

							//Fin de las validaciones de acceso
						} else {
							require 'noacceso.php';
						}
					} 

				break;

				case 'guardaryeditarcomprageneral':
					//activos fijos general
					require_once "../modelos/All_activos_fijos.php";
					$all_activos_fijos = new All_activos_fijos();

					if (!isset($_SESSION["nombre"])) {
						header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
					} else {
							//Validamos el acceso solo al usuario logueado y autorizado.
						if ($_SESSION['resumen_activo_fijo_general'] == 1) {
								//contenido
							if (empty($idcompra_af_general)) {
			
								$rspta ="";
								//precio_sin_igv,precio_igv,precio_total
								echo $rspta ? "ok" : "No se pudieron registrar todos los datos de la compra";
							} else {

								$rspta=$all_activos_fijos->editar($idcompra_af_general,$idproveedor, $fecha_compra, $tipo_comprovante,
								$serie_comprovante, $descripcion, $subtotal_compra,$igv_compra, $total_compra,
								$_POST["idproducto"],$_POST["unidad_medida"], $_POST["nombre_color"],  $_POST["cantidad"],$_POST["precio_sin_igv"], 
								$_POST["precio_igv"],$_POST["precio_con_igv"], $_POST["descuento"], $_POST["ficha_tecnica_producto"]);
			
								echo $rspta ? "ok" : "Compra no se pudo actualizar";
							}  
							//Fin de las validaciones de acceso
						} else {
							require 'noacceso.php';
						}
					}    
				break;
				
				case 'guardar_y_editar_materiales':
							
					if (!isset($_SESSION["nombre"])) {
						header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
					} else {
							//Validamos el acceso solo al usuario logueado y autorizado.
						if ($_SESSION['resumen_activo_fijo_general'] == 1) {
								//contenido	
			
							require_once "../modelos/Activos_fijos.php";
							$activos_fijos = new Activos_fijos();    

							// imgen
							if (!file_exists($_FILES['foto2']['tmp_name']) || !is_uploaded_file($_FILES['foto2']['tmp_name'])) {

							$img_pefil_p = $_POST["foto2_actual"];

							$flat_img1 = false;

							} else {

							$ext1 = explode(".", $_FILES["foto2"]["name"]);

							$flat_img1 = true;

							$img_pefil_p = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

							move_uploaded_file($_FILES["foto2"]["tmp_name"], "../dist/docs/material/img_perfil/" . $img_pefil_p);
							}

							// ficha técnica
							if (!file_exists($_FILES['doc2']['tmp_name']) || !is_uploaded_file($_FILES['doc2']['tmp_name'])) {

							$ficha_tecnica_p = $_POST["doc_old_2"];

							$flat_ficha1 = false;

							} else {

							$ext1 = explode(".", $_FILES["doc2"]["name"]);

							$flat_ficha1 = true;

							$ficha_tecnica_p = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

							move_uploaded_file($_FILES["doc2"]["tmp_name"], "../dist/docs/material/ficha_tecnica/" . $ficha_tecnica_p);
							}

							if (empty($idproducto_p)) {
							//var_dump($idproyecto,$idproveedor);
							$rspta = $activos_fijos->insertar( $unidad_medida_p, $color_p, $categoria_insumos_af_p, $nombre_p, $modelo_p, $serie_p, $marca_p, $estado_igv_p, $precio_unitario_p, $precio_igv_p, $precio_sin_igv_p, $precio_total_p, $ficha_tecnica_p, $descripcion_p,  $img_pefil_p);
							
							echo $rspta ? "ok" : "No se pudieron registrar todos los datos";

							} else {

							// validamos si existe LA IMG para eliminarlo
							if ($flat_img1 == true) {

								$datos_f1 = $activos_fijos->obtenerImg($idproducto_p);

								$img1_ant = $datos_f1->fetch_object()->imagen;

								if ($img1_ant != "") {

								unlink("../dist/docs/material/img_perfil/" . $img1_ant);
								}
							}
							
							$rspta = $activos_fijos->editar( $idproducto_p, $unidad_medida_p, $color_p, $categoria_insumos_af_p, $nombre_p, $modelo_p, $serie_p, $marca_p, $estado_igv_p, $precio_unitario_p, $precio_igv_p, $precio_sin_igv_p, $precio_total_p, $ficha_tecnica_p, $descripcion_p,  $img_pefil_p);
							//var_dump($idactivos_fijos,$idproveedor);
							echo $rspta ? "ok" : "No se pudo actualizar";
							}

						} else {
							require 'noacceso.php';
						}
					} 

				break;

				case 'guardaryeditar_proveedor':
					if (!isset($_SESSION["nombre"])) {
			
					  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
			
					} else {
						//Validamos el acceso solo al usuario logueado y autorizado.
						if ($_SESSION['resumen_activo_fijo_general']==1)
						{
							require_once "../modelos/AllProveedor.php";

							$proveedor=new Proveedor();
			
			
							if (empty($idproveedor)){
								$rspta=$proveedor->insertar($nombre,$tipo_documento,$num_documento,$direccion,$telefono,$c_bancaria, $cci, $c_detracciones,$banco,$titular_cuenta);
								echo $rspta ? "ok" : "No se pudieron registrar todos los datos del proveedor";
							}
							else {
								$rspta=$proveedor->editar($idproveedor,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$c_bancaria, $cci, $c_detracciones,$banco,$titular_cuenta);
								echo $rspta ? "ok" : "Trabador no se pudo actualizar";
							}
							//Fin de las validaciones de acceso
						} else {
			
							  require 'noacceso.php';
						}
					}		
				break;

				case 'mostrar_productos':
					
					if (!isset($_SESSION["nombre"])) {
						header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
					} else {
							//Validamos el acceso solo al usuario logueado y autorizado.
						if ($_SESSION['resumen_activo_fijo_general'] == 1) {
								//contenido	

							require_once "../modelos/Activos_fijos.php";
							$activos_fijos = new Activos_fijos();    

							$rspta = $activos_fijos->mostrar($_POST['idproducto_p']);
							//Codificar el resultado utilizando json
							echo json_encode($rspta);
							//Fin de las validaciones de acceso
						} else {
							require 'noacceso.php';
						}
					} 
		  
				break;

				case 'mostrar_detalle_material':

					if (!isset($_SESSION["nombre"])) {
						header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
					} else {
							//Validamos el acceso solo al usuario logueado y autorizado.
						if ($_SESSION['resumen_activo_fijo_general'] == 1) {
								//contenido	

							require_once "../modelos/Activos_fijos.php";
							$activos_fijos = new Activos_fijos();    

							$rspta = $activos_fijos->mostrar($_POST['idproducto_p']);
							//Codificar el resultado utilizando json
							echo json_encode($rspta);
							//Fin de las validaciones de acceso
						} else {
							require 'noacceso.php';
						}
					} 
				break;
		  
				//:::::::::::::::::::FIN EDITAR COMPRA GENERAL, GUARDAR(MATERIALES Y PROVEEDOR)::::::::::::: */

				//:::::::::::::::::::EDITAR COMPRA POR PROYECTO::::::::::::: */

				case 'listarMaterialescompra_proyecto':

					if (!isset($_SESSION["nombre"])) {
						header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
					} else {
							//Validamos el acceso solo al usuario logueado y autorizado.
						if ($_SESSION['resumen_activo_fijo_general'] == 1) {
								//contenido					
							$rspta = $resumen_af_g->listar_insumos_activo_general();
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
								"0" => '<button class="btn btn-warning" onclick="agregarDetalleCompra_proy(' . $reg->idproducto . ', \'' . htmlspecialchars($reg->nombre, ENT_QUOTES) . '\', \'' . $reg->nombre_medida . '\', \'' . $reg->nombre_color . '\', \'' . $reg->precio_sin_igv . '\', \'' . $reg->precio_igv . '\', \'' . $reg->precio_total . '\', \'' . $reg->imagen . '\', \'' . $reg->ficha_tecnica . '\')" data-toggle="tooltip" data-original-title="Agregar Planta">
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
							//Fin de las validaciones de acceso
						} else {
							require 'noacceso.php';
						}
					} 
				break;

				case 'ver_compra_editar_proyecto':
										
					if (!isset($_SESSION["nombre"])) {
						header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
					} else {
							//Validamos el acceso solo al usuario logueado y autorizado.
						if ($_SESSION['resumen_activo_fijo_general'] == 1) {
								//contenido
							//compras
							require_once "../modelos/Compra.php";
							$compra = new Compra();
							
							$rspta = $compra->mostrar_compra_para_editar($_POST['idcompra_proyecto']);
							//Codificar el resultado utilizando json
							echo json_encode($rspta);
							//Fin de las validaciones de acceso
						} else {
							require 'noacceso.php';
						}
					} 
				
				break;

				case 'guardaryeditarcompra_por_proyecto':
					
					if (!isset($_SESSION["nombre"])) {
						header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
					} else {
							//Validamos el acceso solo al usuario logueado y autorizado.
						if ($_SESSION['resumen_activo_fijo_general'] == 1) {
								//contenido
							//compras
							require_once "../modelos/Compra.php";
							$compra = new Compra();

							if (empty($idcompra_proyecto_p)) {
								$rspta ='';
							echo $rspta ? "ok" : "No se pudieron registrar todos los datos de la compra";
							} else {
							$rspta = $compra->editar(
								$idcompra_proyecto_p, $idproyecto_p, $idproveedor_p, $fecha_compra_p, $tipo_comprobante_p, $serie_comprobante_p,
								$descripcion_p, $total_compra_proy_p, $subtotal_compra_p, $igv_compra_p, $estado_detraccion_p,
								$_POST["idactivos_proyecto_p"], $_POST["unidad_medida_compra_p"], $_POST["nombre_color_p"], $_POST["cantidad_p"],
								$_POST["precio_sin_igv_compra_p"], $_POST["precio_igv_compra_p"], $_POST["precio_con_igv_p"], $_POST["descuento_p"],
								$_POST["ficha_tecnica_activo_p"]
							);
						
							echo $rspta ? "ok" : "Compra no se pudo actualizar";
							}
							//Fin de las validaciones de acceso
						} else {
							require 'noacceso.php';
						}
					} 
				
				break;


				//:::::::::::::::::::FIN EDITAR COMPRA POR PROYECTO::::::::::::: */
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