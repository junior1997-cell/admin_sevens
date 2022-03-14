<?php
ob_start();
if (strlen(session_id()) < 1){
	session_start();//Validamos si existe o no la sesión
}
require_once "../modelos/Cargo.php";

$cargo=new Cargo();

$idcargo_trabajador =isset($_POST["idcargo_trabajador"])? limpiarCadena($_POST["idcargo_trabajador"]):"";
$idtipo_trabjador_c  =isset($_POST["idtipo_trabjador_c"])? limpiarCadena($_POST["idtipo_trabjador_c"]):"";
$nombre=isset($_POST["nombre_cargo"])? limpiarCadena($_POST["nombre_cargo"]):"";

switch ($_GET["op"]){
	case 'guardaryeditar_cargo':
		if (empty($idcargo_trabajador )){
			$rspta=$cargo->insertar($idtipo_trabjador_c,$nombre);
			echo $rspta ? "ok" : "Cargo no se pudo registrar";
		}
		else {
			$rspta=$cargo->editar($idcargo_trabajador,$idtipo_trabjador_c,$nombre);
			echo $rspta ? "ok" : "Cargo no se pudo actualizar";
		}
	break;

	case 'desactivar':
		$rspta=$cargo->desactivar($idcargo_trabajador);
 		echo $rspta ? "Cargo Desactivado" : "Cargo no se puede desactivar";
	break;

	case 'activar':
		$rspta=$cargo->activar($idcargo_trabajador);
 		echo $rspta ? "Cargo activada" : "Cargo no se puede activar";
	break;

	case 'eliminar':
		$rspta=$cargo->eliminar($idcargo_trabajador);
 		echo $rspta ? "Cargo Eliminado" : "Cargo no se puede Eliminar";
	break;

	case 'mostrar':
		//$idcargo_trabajador='1';
		$rspta=$cargo->mostrar($idcargo_trabajador);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listar_cargo':
		$rspta=$cargo->listar();
 		//Vamos a declarar un array
 		$data= Array();
		 $cont=1;
 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
				"0"=>$cont++,
 				"1"=>($reg->estado)?'<button class="btn btn-warning btn-sm" onclick="mostrar_cargo('.$reg->idcargo_trabajador.')"><i class="fas fa-pencil-alt"></i></button>'.
					 ' <button class="btn btn-danger  btn-sm" onclick="eliminar_cargo(' . $reg->idcargo_trabajador . ')"><i class="fas fa-skull-crossbones"></i> </button>':
 					'<button class="btn btn-warning btn-sm" onclick="mostrar_cargo('.$reg->idcargo_trabajador.')"><i class="fa fa-pencil-alt"></i></button>'.
 					' <button class="btn btn-primary btn-sm" onclick="activar_cargo('.$reg->idcargo_trabajador.')"><i class="fa fa-check"></i></button>',
 				"2"=>$reg->nombre_tipo_t,
 				"3"=>$reg->nombre,
 				"4"=>($reg->estado)?'<span class="text-center badge badge-success">Activado</span>':
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
	case "selectcargo":
        $rspta = $cargo->select();

        while ($reg = $rspta->fetch_object()) {
          echo '<option  value=' . $reg->idcargo_trabajador  . '>' . $reg->nombre . '</option>';
        }
        break;
	case "selecttipotrabajador":
		$rspta = $cargo->select_tipo_trab();

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