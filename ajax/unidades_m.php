<?php
ob_start();
if (strlen(session_id()) < 1){
	session_start();//Validamos si existe o no la sesión
}
require_once "../modelos/Unidades_m.php";

$unidades_m=new Unidades_m();

$idunidad_medida=isset($_POST["idunidad_medida"])? limpiarCadena($_POST["idunidad_medida"]):"";
$nombre_medida=isset($_POST["nombre_medida"])? limpiarCadena($_POST["nombre_medida"]):"";
$abreviacion=isset($_POST["abreviacion"])? limpiarCadena($_POST["abreviacion"]):"";

switch ($_GET["op"]){
	case 'guardaryeditar_unidades_m':
		if (empty($idunidad_medida)){
			$rspta=$unidades_m->insertar($nombre_medida,$abreviacion);
			echo $rspta ? "ok" : "Unidad de medida no se pudo registrar";
		}
		else {
			$rspta=$unidades_m->editar($idunidad_medida,$nombre_medida,$abreviacion);
			echo $rspta ? "ok" : "Unidad de medida no se pudo actualizar";
		}
	break;

	case 'desactivar_unidades_m':
		$rspta=$unidades_m->desactivar($idunidad_medida);
 		echo $rspta ? "Unidad de medida Desactivada" : "Unidad de medida no se puede desactivar";
	break;

	case 'activar_unidades_m':
		$rspta=$unidades_m->activar($idunidad_medida);
 		echo $rspta ? "Unidad de medida activada" : "Unidad de medida no se puede activar";
	break;

	case 'mostrar_unidades_m':
		$rspta=$unidades_m->mostrar($idunidad_medida);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listar__unidades_m':
		$rspta=$unidades_m->listar();
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>($reg->estado)?'<button class="btn btn-warning btn-sm" onclick="mostrar_unidades_m('.$reg->idunidad_medida.')"><i class="fas fa-pencil-alt"></i></button>'.
 					' <button class="btn btn-danger btn-sm" onclick="desactivar_unidades_m('.$reg->idunidad_medida.')"><i class="far fa-trash-alt"></i></button>':
 					'<button class="btn btn-warning btn-sm" onclick="mostrar_unidades_m('.$reg->idunidad_medida.')"><i class="fas fa-pencil-alt"></i></button>'.
 					' <button class="btn btn-primary btn-sm" onclick="activar_unidades_m('.$reg->idunidad_medida.')"><i class="fa fa-check"></i></button>',
 				"1"=>$reg->nombre_medida,
 				"2"=>$reg->abreviacion,
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
	case "selectUnidad":
        $rspta = $unidades_m->select();

        while ($reg = $rspta->fetch_object()) {
          echo '<option  value=' . $reg->idunidad_medida . '>' . $reg->nombre_medida . ' - '. $reg->abreviacion.'</option>';
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