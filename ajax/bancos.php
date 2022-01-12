<?php
ob_start();
if (strlen(session_id()) < 1){
	session_start();//Validamos si existe o no la sesión
}
require_once "../modelos/Bancos.php";

$bancos=new Bancos();

$idbancos=isset($_POST["idbancos"])? limpiarCadena($_POST["idbancos"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";

switch ($_GET["op"]){
	case 'guardaryeditar_bancos':
		if (empty($idbancos)){
			$rspta=$bancos->insertar($nombre);
			echo $rspta ? "ok" : "bancos no se pudo registrar";
		}
		else {
			$rspta=$bancos->editar($idbancos,$nombre);
			echo $rspta ? "ok" : "bancos no se pudo actualizar";
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

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>($reg->estado)?'<button class="btn btn-warning btn-sm" onclick="mostrar_bancos('.$reg->idbancos.')"><i class="fas fa-pencil-alt"></i></button>'.
 					' <button class="btn btn-danger btn-sm" onclick="desactivar_bancos('.$reg->idbancos.')"><i class="far fa-trash-alt"></i></button>':
 					'<button class="btn btn-warning btn-sm" onclick="mostrar_bancos('.$reg->idbancos.')"><i class="fas fa-pencil-alt"></i></button>'.
 					' <button class="btn btn-primary btn-sm" onclick="activar_bancos('.$reg->idbancos.')"><i class="fa fa-check"></i></button>',
 				"1"=>$reg->nombre,
 				"2"=>($reg->estado)?'<span class="text-center badge badge-success">Activado</span>':
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
ob_end_flush();
?>