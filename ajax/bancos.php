<?php
	ob_start();
		if (strlen(session_id()) < 1){
			session_start();//Validamos si existe o no la sesión
		}
		require_once "../modelos/Bancos.php";

		$bancos=new Bancos();

		$idbancos =					isset($_POST["idbancos"])? limpiarCadena($_POST["idbancos"]):"";
		$nombre =					isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";

		$formato_cci =				isset($_POST["formato_cci"])? limpiarCadena($_POST["formato_cci"]):"";
		$formato_cta =	isset($_POST["formato_cta"])? limpiarCadena($_POST["formato_cta"]):"";

		switch ($_GET["op"]){
			case 'guardaryeditar_bancos':
				if (empty($idbancos)){
					$rspta=$bancos->insertar($nombre, $formato_cta, $formato_cci);
					echo $rspta ? "ok" : "Bancos no se pudo registrar";
				}
				else {
					$rspta=$bancos->editar($idbancos,$nombre, $formato_cta, $formato_cci);
					echo $rspta ? "ok" : "Bancos no se pudo actualizar";
				}
			break;

			case 'desactivar_bancos':
				$rspta=$bancos->desactivar($idbancos);
				echo $rspta ? "bancos Desactivada" : "bancos no se puede desactivar";
			break;

			case 'activar_bancos':
				$rspta=$bancos->activar($idbancos);
				echo $rspta ? "bancos activada" : "bancos no se puede activar";
			break;

			case 'mostrar_bancos':
				$rspta=$bancos->mostrar($idbancos);
				//Codificar el resultado utilizando json
				echo json_encode($rspta);
			break;

			case 'listar':
				$rspta=$bancos->listar();
				//Vamos a declarar un array
				$data= Array();

				$cta = "00000000000000000000000000000"; $cci = "00000000000000000000000000000";

				while ($reg=$rspta->fetch_object()){
					$data[]=array(
						"0"=>($reg->estado)?'<button class="btn btn-warning btn-sm" onclick="mostrar_bancos('.$reg->idbancos.')"><i class="fas fa-pencil-alt"></i></button>'.
							' <button class="btn btn-danger btn-sm" onclick="desactivar_bancos('.$reg->idbancos.')"><i class="far fa-trash-alt"></i></button>':
							'<button class="btn btn-warning btn-sm" onclick="mostrar_bancos('.$reg->idbancos.')"><i class="fas fa-pencil-alt"></i></button>'.
							' <button class="btn btn-primary btn-sm" onclick="activar_bancos('.$reg->idbancos.')"><i class="fa fa-check"></i></button>',
						"1"=>$reg->nombre,
						"2"=>'<span> <b>Formato CTA :</b>'.$reg->formato_cta.'<br> <b>Ej. cta: </b>'.darFormatoBanco($cta, $reg->formato_cta).'</span> <br> <span> <b>Formato CCI :</b>'.$reg->formato_cci.'<br>  <b>Ej. cci: </b>'.darFormatoBanco($cci, $reg->formato_cci).'</span>',
						"3"=>($reg->estado)?'<span class="text-center badge badge-success">Activado</span>':
						'<span class="text-center badge badge-danger">Desactivado</span>'
						);
				}
				$results = array(
					"sEcho"=>1, //Información para el datatables
					"iTotalRecords"=>count($data), //enviamos el total registros al datatable
					"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
					"aaData"=>$data);
				echo json_encode($results);

			break;
			case "selectbancos":
				$rspta = $bancos->select();

				while ($reg = $rspta->fetch_object()) {
				echo '<option  value=' . $reg->nombre . '>' . $reg->nombre . '</option>';
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

		function darFormatoBanco($numero, $formato){

			$format_array = explode("-", $formato); $format_cuenta = ""; $cont_format = 0; $indi = 0;

			foreach ($format_array as $indice => $key) {

				if ($key == "__" || $key == "0_" || $key == "1_" || $key == "2_" || $key == "3_" || $key == "4_" || $key == "5_" || $key == "6_" || $key == "7_" || $key == "8_" || $key == "9_") {

					$cont_format = $cont_format + 0;
					
				} else {
					
					if (intval($key) == 0) {

						$format_cuenta .= substr($numero, $cont_format, $key );

						$cont_format = $cont_format + intval($key);  //$indi = $indice;
					} else {
						$format_cuenta .= substr($numero, $cont_format, $key ) . '-' ;

						$cont_format = $cont_format + intval($key);
					}	
				}  
			}			
			return substr($format_cuenta, 0, -1);
		} 

	ob_end_flush();
?>