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

			require_once "../modelos/Resumen_insumos.php";

			$resumen_insumos=new ResumenInsumos();

			switch ($_GET["op"]){
				
				case 'listar_tbla_principal':

          $idproyecto = $_GET["id_proyecto"];

					$rspta=$resumen_insumos->listar_tbla_principal($idproyecto);
					//Vamos a declarar un array
					$data= Array();

          $imagen_error = "this.src='../dist/svg/default_producto.svg'";

					while ($reg=$rspta->fetch_object()){

            $precio_promedio = number_format($reg->precio_con_igv/$reg->count_productos, 2, ".", ",");
						$data[]=array(
              // "0"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idusuario.')"><i class="fas fa-pencil-alt"></i></button>'.
              //   ' <button class="btn btn-danger" onclick="desactivar('.$reg->idusuario.')"><i class="far fa-trash-alt  "></i></button>':
              //   '<button class="btn btn-warning" onclick="mostrar('.$reg->idusuario.')"><i class="fas fa-pencil-alt"></i></button>'.
              //   ' <button class="btn btn-primary" onclick="activar('.$reg->idusuario.')"><i class="fa fa-check"></i></button>',
              "0"=>'<div class="user-block">
                 <img class="img-circle" src="../dist/img/materiales/'. $reg->imagen .'" alt="User Image" onerror="'.$imagen_error.'">
                 <span class="username"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'. $reg->nombre_producto .'</p></span>
                 <span class="description"> <b>Color:</b> '. $reg->nombre_color .' </span>
               </div>',
              "1"=>$reg->nombre_medida,
              "2"=>$reg->cantidad_total,
              "3"=>'<span> S/. '. number_format($reg->promedio_precio, 2, ".", ",") . '</span>  <button class="btn btn-info btn-sm" onclick="ver_precios_y_mas('.$reg->idproyecto.', '.$reg->idproducto .', \'' . $reg->nombre_producto . '\', \''. $precio_promedio . '\', \''. number_format($reg->precio_total, 2, ".", ",") .'\')"><i class="far fa-eye"></i></button>' ,
              "4"=>'S/. '.number_format($reg->precio_actual, 2, ".", ","),
              "5"=>'S/. '. number_format($reg->precio_total, 2, ".", ",") ,
              // "4"=>($reg->estado)?'<span class="text-center badge badge-success">Activado</span>':
              // '<span class="text-center badge badge-danger">Desactivado</span>'
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

					$idproyecto = $_GET["idproyecto"]; $idproducto = $_GET["idproducto"];

					$rspta=$resumen_insumos->ver_precios_y_mas($idproyecto, $idproducto);
					//Vamos a declarar un array
					$data= Array();	
          
          $imagen_error = "this.src='../dist/svg/user_default.svg'"; $ficha_tecnica = ""; 

					while ($reg=$rspta->fetch_object()){

            // validamos si existe una ficha tecnica
            !empty($reg->ficha_tecnica) ? ($ficha_tecnica = '<center><a target="_blank" href="../dist/ficha_tecnica_materiales/' . $reg->ficha_tecnica . '"><i class="far fa-file-pdf fa-2x text-success"></i></a></center>') : ($ficha_tecnica = '<center><span class="text-center"> <i class="far fa-times-circle fa-2x text-danger"></i></span></center>');
						
            $data[]=array(
							// "0"=>'<div class="user-block">
							// 	<img class="img-circle" src="../dist/img/usuarios/'. $reg->imagen_perfil .'" alt="User Image" onerror="'.$imagen_error.'">
							// 	<span class="username"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'. $reg->nombres .'</p></span>
							// 	<span class="description">'. $reg->tipo_documento .': '. $reg->numero_documento .' </span>
							// </div>',
              "0" =>date("d/m/Y", strtotime($reg->fecha_compra)),
							"1"=>$reg->cantidad,
							"2"=> '<h4> <b>'. number_format( $reg->precio_igv, 2, ".", ",") .'</b> </h4>',
              "3"=>'S/. '. number_format($reg->descuento , 2, ".", ","),
              "4"=>'S/. '. number_format($reg->subtotal, 2, ".", ","),
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

          $idproyecto = $_POST["idproyecto"];

          $rspta = $resumen_insumos->suma_total_compras($idproyecto);

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