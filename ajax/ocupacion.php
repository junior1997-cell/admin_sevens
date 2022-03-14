<?php
ob_start();
if (strlen(session_id()) < 1){
	session_start();//Validamos si existe o no la sesión
}
require_once "../modelos/Ocupacion.php";

$ocupacion=new Ocupacion();

$idocupacion=isset($_POST["idocupacion"])? limpiarCadena($_POST["idocupacion"]):"";
$nombre_ocupacion=isset($_POST["nombre_ocupacion"])? limpiarCadena($_POST["nombre_ocupacion"]):"";

switch ($_GET["op"]){
	case 'guardaryeditar_ocupacion':
		if (empty($idocupacion)){
			$rspta=$ocupacion->insertar($nombre_ocupacion);
			echo $rspta ? "ok" : "ocupacion de medida no se pudo registrar";
		}
		else {
			$rspta=$ocupacion->editar($idocupacion,$nombre_ocupacion);
			echo $rspta ? "ok" : "ocupacion de medida no se pudo actualizar";
		}
	break;

	case 'desactivar_ocupacion':
		$rspta=$ocupacion->desactivar($idocupacion);
 		echo $rspta ? "ocupacion de medida Desactivada" : "ocupacion de medida no se puede desactivar";
	break;

	case 'activar_ocupacion':
		$rspta=$ocupacion->activar($idocupacion);
 		echo $rspta ? "ocupacion de medida activada" : "ocupacion de medida no se puede activar";
	break;

	case 'eliminar_ocupacion':
		$rspta=$ocupacion->eliminar($idocupacion);
 		echo $rspta ? "ocupacion de medida Desactivada" : "ocupacion de medida no se puede desactivar";
	break;

	case 'mostrar_ocupacion':
		$rspta=$ocupacion->mostrar($idocupacion);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listar_ocupacion':
		$rspta=$ocupacion->listar();
 		//Vamos a declarar un array
 		$data= Array();
		 $cont=1;
 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
				"0"=>$cont++,
 				"1"=>($reg->estado)?'<button class="btn btn-warning btn-sm" onclick="mostrar_ocupacion('.$reg->idocupacion.')"><i class="fas fa-pencil-alt"></i></button>'.
					' <button class="btn btn-danger  btn-sm" onclick="eliminar_ocupacion(' . $reg->idocupacion . ')"><i class="fas fa-skull-crossbones"></i> </button>':
 					'<button class="btn btn-warning btn-sm" onclick="mostrar_ocupacion('.$reg->idocupacion.')"><i class="fas fa-pencil-alt"></i></button>'.
 					' <button class="btn btn-primary btn-sm" onclick="activar_ocupacion('.$reg->idocupacion.')"><i class="fa fa-check"></i></button>',
 				"2"=>$reg->nombre_ocupacion,
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
	case "selectocupacion":
        $rspta = $ocupacion->select();

        while ($reg = $rspta->fetch_object()) {
          echo '<option  value=' . $reg->idocupacion . '>' . $reg->nombre_ocupacion . '</option>';
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