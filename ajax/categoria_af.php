<?php
ob_start();
if (strlen(session_id()) < 1){
	session_start();//Validamos si existe o no la sesión
}
require_once "../modelos/Categoria_af.php";

$categoria_af=new Categoria_af();

$idcategoria_insumos_af =isset($_POST["idcategoria_insumos_af"])? limpiarCadena($_POST["idcategoria_insumos_af"]):"";
$nombre_categoria_af=isset($_POST["nombre_categoria_af"])? limpiarCadena($_POST["nombre_categoria_af"]):"";

switch ($_GET["op"]){
	case 'guardaryeditar_c_insumos_af':

		if (empty($idcategoria_insumos_af)){
			$rspta=$categoria_af->insertar($nombre_categoria_af);
			echo $rspta ? "ok" : "Categoría no se pudo registrar";
		}
		else {
			$rspta=$categoria_af->editar($idcategoria_insumos_af,$nombre_categoria_af);
			echo $rspta ? "ok" : "Categoría no se pudo actualizar";
		}
	break;

	case 'desactivar':
		$rspta=$categoria_af->desactivar($idcategoria_insumos_af);
 		echo $rspta ? "Categoría  Desactivado" : "Categoría no no se puede desactivar";
	break;

	case 'delete':
		$rspta=$categoria_af->delete($idcategoria_insumos_af);
 		echo $rspta ? "Categoría eliminada" : "Categoría no no se puede eliminar";
	break;

	case 'mostrar':
		//$idcategoria_insumos_af='1';
		$rspta=$categoria_af->mostrar($idcategoria_insumos_af);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listar_c_insumos_af':
		$rspta=$categoria_af->listar();
 		//Vamos a declarar un array
 		$data= Array();
		 $cont=1;
 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
				"0"=>$cont++,
 				"1"=>($reg->estado)?'<button class="btn btn-warning btn-sm" onclick="mostrar_c_insumos_af('.$reg->idcategoria_insumos_af.')"><i class="fas fa-pencil-alt"></i></button>'.
					 ' <button class="btn btn-danger btn-sm" onclick="eliminar_c_insumos_af('.$reg->idcategoria_insumos_af.')"><i class="fas fa-skull-crossbones"></i></button>':'',
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
	case "selectcategoria_af":
        $rspta = $categoria_af->select();

        while ($reg = $rspta->fetch_object()) {
          echo '<option  value=' . $reg->idcategoria_insumos_af  . '>' . $reg->nombre . '</option>';
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