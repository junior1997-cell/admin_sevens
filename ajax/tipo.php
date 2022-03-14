<?php
ob_start();
if (strlen(session_id()) < 1){
	session_start();//Validamos si existe o no la sesión
}
require_once "../modelos/Tipo.php";

$tipo=new Tipo();

$idtipo_trabajador =isset($_POST["idtipo_trabajador"])? limpiarCadena($_POST["idtipo_trabajador"]):"";
$nombre_tipo=isset($_POST["nombre_tipo"])? limpiarCadena($_POST["nombre_tipo"]):"";

switch ($_GET["op"]){
	case 'guardaryeditar_tipo':
		if (empty($idtipo_trabajador)){
			$rspta=$tipo->insertar($nombre_tipo);
			echo $rspta ? "ok" : "Tipoa no se pudo registrar";
		}
		else {
			$rspta=$tipo->editar($idtipo_trabajador,$nombre_tipo);
			echo $rspta ? "ok" : "Tipo no se pudo actualizar";
		}
	break;

	case 'desactivar_tipo':
		$rspta=$tipo->desactivar($idtipo_trabajador);
 		echo $rspta ? "Tipo Desactivada" : "Tipo no se puede desactivar";
	break;

	case 'activar_tipo':
		$rspta=$tipo->activar($idtipo_trabajador);
 		echo $rspta ? "Tipo activada" : "Tipoida no se puede activar";
	break;

	case 'eliminar_tipo':
		$rspta=$tipo->eliminar($idtipo_trabajador);
 		echo $rspta ? "Tipo de medida eliminado" : "Tipo  no se puede eliminar";
	break;

	case 'mostrar_tipo':
		$rspta=$tipo->mostrar($idtipo_trabajador);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listar_tipo':
		$rspta=$tipo->listar();
 		//Vamos a declarar un array
 		$data= Array();
		 $cont=1;
 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
				"0"=>$cont++,
 				"1"=>($reg->estado)?'<button class="btn btn-warning btn-sm" onclick="mostrar_tipo('.$reg->idtipo_trabajador.')"><i class="fas fa-pencil-alt"></i></button>'.
					' <button class="btn btn-danger  btn-sm" onclick="eliminar_tipo(' . $reg->idtipo_trabajador . ')"><i class="fas fa-skull-crossbones"></i> </button>':
 					'<button class="btn btn-warning btn-sm" onclick="mostrar_tipo('.$reg->idtipo_trabajador.')"><i class="fas fa-pencil-alt"></i></button>'.
 					' <button class="btn btn-primary btn-sm" onclick="activar_tipo('.$reg->idtipo_trabajador .')"><i class="fa fa-check"></i></button>',
 				"2"=>$reg->nombre,
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
	case "selecttipo_tipo":
        $rspta = $tipo->select();

        while ($reg = $rspta->fetch_object()) {
          echo '<option  value=' . $reg->idtipo_trabajador  . '>' . $reg->nombre . '</option>';
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