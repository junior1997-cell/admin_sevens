<?php
ob_start();

if (strlen(session_id()) < 1) {
  session_start(); //Validamos si existe o no la sesión
}

if (!isset($_SESSION["nombre"])) {
  header("Location: login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
} else {
  //Validamos el acceso solo al usuario logueado y autorizado.
  if ($_SESSION['compra'] == 1) {

    require_once "../modelos/Resumen_insumos.php";

    $resumen_insumos = new ResumenInsumos();

    switch ($_GET["op"]) {

      case 'listar_tbla_principal':

        $idproyecto = $_GET["id_proyecto"];

        $rspta = $resumen_insumos->listar_tbla_principal($idproyecto);
        //Vamos a declarar un array
        $data = [];

        $imagen_error = "this.src='../dist/svg/default_producto.svg'";

        while ($reg = $rspta->fetch_object()) {

          $precio_promedio = number_format($reg->precio_con_igv / $reg->count_productos, 2, ".", ",");

          $data[] = [             
            "0" => '<div class="user-block"> <img class="profile-user-img img-responsive img-circle" src="../dist/docs/material/img_perfil/' . $reg->imagen . '" alt="User Image" onerror="' .  $imagen_error .  '">
                <span class="username"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >' . $reg->nombre_producto . '</p></span>
                <span class="description"> <b>Color:</b> ' . $reg->nombre_color .'</span>
              </div>',
            "1" => $reg->nombre_medida,
            "2" => $reg->cantidad_total,
            "3" => '<button class="btn btn-info btn-sm mb-2" onclick="tbla_facuras(' . $reg->idproyecto . ', ' . $reg->idproducto . ', \'' . $reg->nombre_producto . '\', \'' .  $precio_promedio . '\', \'' .  number_format($reg->precio_total, 2, ".", ",") . '\')"><i class="far fa-eye"></i></button> 
            <span> S/. ' . number_format($reg->promedio_precio, 2, ".", ",") . '</span>',
            "4" => 'S/. ' . number_format($reg->precio_actual, 2, ".", ","),
            "5" => 'S/. ' . number_format($reg->precio_total, 2, ".", ","),             
          ];
        }

        $results = [
          "sEcho" => 1, //Información para el datatables
          "iTotalRecords" => count($data), //enviamos el total registros al datatable
          "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
          "aaData" => $data,
        ];
        echo json_encode($results);

        break;

      case 'ver_precios_y_mas':
        $idproyecto = $_GET["idproyecto"];
        $idproducto = $_GET["idproducto"];

        $rspta = $resumen_insumos->ver_precios_y_mas($idproyecto, $idproducto);
        //Vamos a declarar un array
        $data = [];

        $imagen_error = "this.src='../dist/svg/user_default.svg'";
        $ficha_tecnica = "";

        while ($reg = $rspta->fetch_object()) {
          // validamos si existe una ficha tecnica
          !empty($reg->ficha_tecnica)
            ? ($ficha_tecnica = '<center><a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg->ficha_tecnica . '"><i class="far fa-file-pdf fa-lg text-success"></i></a></center>')
            : ($ficha_tecnica = '<center><i class="far fa-file-pdf fa-lg text-gray-50"></i></center>');

          $data[] = [    
            "0" => '<button class="btn btn-warning btn-sm" onclick="editar_detalle_compras(' . $reg->idcompra_proyecto . ')" data-toggle="tooltip" data-original-title="Editar compra"><i class="fas fa-pencil-alt"></i></button>',
            "1" => '<span class="text-primary font-weight-bold" >' . $reg->proveedor . '</span>',      
            "2" => date("d/m/Y", strtotime($reg->fecha_compra)),
            "3" => $reg->cantidad,
            "4" => '<b>' . number_format($reg->precio_igv, 2, ".", ",") . '</b>',
            "5" => 'S/. ' . number_format($reg->descuento, 2, ".", ","),
            "6" => 'S/. ' . number_format($reg->subtotal, 2, ".", ","),
            // "7" => $ficha_tecnica,
          ];
        }

        $results = [
          "sEcho" => 1, //Información para el datatables
          "iTotalRecords" => count($data), //enviamos el total registros al datatable
          "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
          "aaData" => $data,
        ];
        echo json_encode($results);

        break;

      case 'suma_total_compras':
        $idproyecto = $_POST["idproyecto"];

        $rspta = $resumen_insumos->suma_total_compras($idproyecto);

        echo json_encode($rspta);
        break;
    }
    //Fin de las validaciones de acceso
  } else {
    require 'noacceso.php';
  }
}
ob_end_flush();
?>
