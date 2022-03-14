<?php
ob_start();
if (strlen(session_id()) < 1){
	session_start();//Validamos si existe o no la sesión
}
require_once "../modelos/Color.php";

$color=new Color();

$idcolor=isset($_POST["idcolor"])? limpiarCadena($_POST["idcolor"]):"";
$nombre=isset($_POST["nombre_color"])? limpiarCadena($_POST["nombre_color"]):"";

switch ($_GET["op"]){
	case 'guardaryeditar':
		if (empty($idcolor)){
			$rspta=$color->insertar($nombre);
			echo $rspta ? "ok" : "color no se pudo registrar";
		}
		else {
			$rspta=$color->editar($idcolor,$nombre);
			echo $rspta ? "ok" : "color no se pudo actualizar";
		}
	break;

	case 'desactivar':
		$rspta=$color->desactivar($idcolor);
 		echo $rspta ? "color Desactivada" : "color no se puede desactivar";
	break;

	case 'activar':
		$rspta=$color->activar($idcolor);
 		echo $rspta ? "color activada" : "color no se puede activar";
	break;

	case 'eliminar':
		$rspta=$color->eliminar($idcolor);
 		echo $rspta ? "color Eliminada" : "color no se puede Eliminar";
	break;

	case 'mostrar':
		$rspta=$color->mostrar($idcolor);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listar':
		$rspta=$color->listar();
 		//Vamos a declarar un array
 		$data= Array();
		 $cont=1;
 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
				"0"=>$cont++,
 				"1"=>($reg->estado)?'<button class="btn btn-warning btn-sm" onclick="mostrar('.$reg->idcolor.')"><i class="fas fa-pencil-alt"></i></button>'.
					 ' <button class="btn btn-danger  btn-sm" onclick="eliminar_color(' . $reg->idcolor . ')"><i class="fas fa-skull-crossbones"></i> </button>':
 					'<button class="btn btn-warning btn-sm" onclick="mostrar('.$reg->idcolor.')"><i class="fa fa-pencil-alt"></i></button>'.
 					' <button class="btn btn-primary btn-sm" onclick="activar('.$reg->idcolor.')"><i class="fa fa-check"></i></button>',
 				"2"=>$reg->nombre_color,
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
	case "selectcolor":
        $rspta = $color->select();

        while ($reg = $rspta->fetch_object()) {
          echo '<option  value=' . $reg->idcolor . '>' . $reg->nombre_color . '</option>';
        }
    break;
	case "selectcolor_2":
        $rspta = $color->select();

        while ($reg = $rspta->fetch_object()) {
          echo '<option  value=' . $reg->nombre_color . '>' . $reg->nombre_color . '</option>';
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