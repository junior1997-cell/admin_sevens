<?php
ob_start();
if (strlen(session_id()) < 1){
	session_start();//Validamos si existe o no la sesión
}
require_once "../modelos/Tipo.php";

$tipo=new Tipo();

$idtipo =isset($_POST["idtipo"])? limpiarCadena($_POST["idtipo"]):"";
$nombre_tipo=isset($_POST["nombre_tipo"])? limpiarCadena($_POST["nombre_tipo"]):"";

switch ($_GET["op"]){
	case 'guardaryeditar_tipo':
		if (empty($idtipo)){
			$rspta=$tipo->insertar($nombre_tipo);
			echo $rspta ? "ok" : "tipo de medida no se pudo registrar";
		}
		else {
			$rspta=$tipo->editar($idtipo,$nombre_tipo);
			echo $rspta ? "ok" : "tipo de medida no se pudo actualizar";
		}
	break;

	case 'desactivar_tipo':
		$rspta=$tipo->desactivar($idtipo);
 		echo $rspta ? "tipo de medida Desactivada" : "tipo de medida no se puede desactivar";
	break;

	case 'activar_tipo':
		$rspta=$tipo->activar($idtipo);
 		echo $rspta ? "tipo de medida activada" : "tipo de medida no se puede activar";
	break;

	case 'mostrar_tipo':
		$rspta=$tipo->mostrar($idtipo);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listar_tipo':
		$rspta=$tipo->listar();
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>($reg->estado)?'<button class="btn btn-warning btn-xs" onclick="mostrar_tipo('.$reg->idtipo .')"><i class="fas fa-pencil-alt"></i></button>'.
 					' <button class="btn btn-danger btn-xs" onclick="desactivar_tipo('.$reg->idtipo .')"><i class="far fa-trash-alt"></i></button>':
 					'<button class="btn btn-warning btn-xs" onclick="mostrar_tipo('.$reg->idtipo .')"><i class="fas fa-pencil-alt"></i></button>'.
 					' <button class="btn btn-primary btn-xs" onclick="activar_tipo('.$reg->idtipo .')"><i class="fa fa-check"></i></button>',
 				"1"=>$reg->nombre_tipo,
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
	case "selecttipo_tipo":
        $rspta = $tipo->select();

        while ($reg = $rspta->fetch_object()) {
          echo '<option  value=' . $reg->idtipo  . '>' . $reg->nombre_tipo . '</option>';
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