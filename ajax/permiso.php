<?php 
	ob_start();

	if (strlen(session_id()) < 1){

		session_start();//Validamos si existe o no la sesión
	}

	if (!isset($_SESSION["nombre"])) {

		header("Location: login.html");//Validamos el acceso solo a los usuarios logueados al sistema.

	} else {
		//Validamos el acceso solo al usuario logueado y autorizado.
		if ($_SESSION['acceso']==1)	{ 

			require_once "../modelos/Permiso.php";

			$permiso=new Permiso();

			switch ($_GET["op"]){
				
				case 'listar':
					$rspta=$permiso->listar();
					//Vamos a declarar un array
					$cont=1;
					$data= Array();

					while ($reg=$rspta->fetch_object()){

						$data[]=array(
							"0"=>$cont++,
							"1"=>'<button class="btn btn-info btn-sm" onclick="mostrar_usuarios('.$reg->idpermiso.')"><i class="fas fa-eye"></i></button>',
							"2"=>$reg->nombre
						);
					}

					$results = array(
						"sEcho"=>1, //Información para el datatables
						"iTotalRecords"=>count($data), //enviamos el total registros al datatable
						"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
						"aaData"=>$data);
					echo json_encode($results);

				break;

				case 'listar_usuario':

					$id_permiso = $_GET["id"];

					$rspta=$permiso->ver_usuarios($id_permiso);
					$cont=1;
					//Vamos a declarar un array
					$data= Array();
					$imagen_error = "this.src='../dist/svg/user_default.svg'";
					while ($reg=$rspta->fetch_object()){

						$data[]=array(
							"0"=>$cont++,
							"1"=>'<div class="user-block">
								<img class="img-circle" src="../dist/img/usuarios/'. $reg->imagen_perfil .'" alt="User Image" onerror="'.$imagen_error.'">
								<span class="username"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'. $reg->nombres .'</p></span>
								<span class="description">'. $reg->tipo_documento .': '. $reg->numero_documento .' </span>
							</div>',
							"2"=>$reg->cargo, 
							"3"=>substr ( $reg->created_at , 0, ((strlen($reg->created_at))-3) )
						);
					}

					$results = array(
						"sEcho"=>1, //Información para el datatables
						"iTotalRecords"=>count($data), //enviamos el total registros al datatable
						"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
						"aaData"=>$data);
					echo json_encode($results);

				break;
			}
			//Fin de las validaciones de acceso
		} else {

			require 'noacceso.php';
		}
	}
	ob_end_flush();
?>