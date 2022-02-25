<?php
ob_start();
if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
}
require_once "../modelos/All_activos_fijos.php";

$all_activos_fijos = new All_activos_fijos();

require_once "../modelos/Activos_fijos_proyecto.php";

$ctivos_fijos_proy = new Activos_fijos_proyecto();

//$idproyecto = isset($_POST["idproyecto"]) ? limpiarCadena($_POST["idproyecto"]) : "";
$idcompra_af_general = isset($_POST["idcompra_af_general"]) ? limpiarCadena($_POST["idcompra_af_general"]) : "";
$idproveedor = isset($_POST["idproveedor"]) ? limpiarCadena($_POST["idproveedor"]) : "";
$fecha_compra = isset($_POST["fecha_compra"]) ? limpiarCadena($_POST["fecha_compra"]) : "";
$tipo_comprovante = isset($_POST["tipo_comprovante"]) ? limpiarCadena($_POST["tipo_comprovante"]) : "";
$serie_comprovante = isset($_POST["serie_comprovante"]) ? limpiarCadena($_POST["serie_comprovante"]) : "";
$descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";

$subtotal_compra = isset($_POST["subtotal_compra"]) ? limpiarCadena($_POST["subtotal_compra"]) : "";
$igv_compra = isset($_POST["igv_compra"]) ? limpiarCadena($_POST["igv_compra"]) : "";
$total_compra_af_g = isset($_POST["total_compra_af_g"]) ? limpiarCadena($_POST["total_compra_af_g"]) : "";

//============factura========================
//$idproyectof,$idfacturacompra,$idcompra_af_general,$codigo
$idproyectof = isset($_POST["idproyectof"]) ? limpiarCadena($_POST["idproyectof"]) : "";
$idfacturacompra = isset($_POST["idfacturacompra"]) ? limpiarCadena($_POST["idfacturacompra"]) : "";
$idcomp_proyecto = isset($_POST["idcomp_proyecto"]) ? limpiarCadena($_POST["idcomp_proyecto"]) : "";
$codigo = isset($_POST["codigo"]) ? limpiarCadena($_POST["codigo"]) : "";
$monto_compraa = isset($_POST["monto_compraa"]) ? limpiarCadena($_POST["monto_compraa"]) : "";
$fecha_emision = isset($_POST["fecha_emision"]) ? limpiarCadena($_POST["fecha_emision"]) : "";
$descripcion_f = isset($_POST["descripcion_f"]) ? limpiarCadena($_POST["descripcion_f"]) : "";
$subtotal_compraa = isset($_POST["subtotal_compraa"]) ? limpiarCadena($_POST["subtotal_compraa"]) : "";
$igv_compraa = isset($_POST["igv_compraa"]) ? limpiarCadena($_POST["igv_compraa"]) : "";
$nota = isset($_POST["nota"]) ? limpiarCadena($_POST["nota"]) : "";

$doc_img = isset($_POST["foto2"]) ? limpiarCadena($_POST["foto2"]) : "";

///==============DATOS PAGO COMPRA==============

$beneficiario_pago = isset($_POST["beneficiario_pago"]) ? limpiarCadena($_POST["beneficiario_pago"]) : "";
$forma_pago = isset($_POST["forma_pago"]) ? limpiarCadena($_POST["forma_pago"]) : "";
$tipo_pago = isset($_POST["tipo_pago"]) ? limpiarCadena($_POST["tipo_pago"]) : "";
$cuenta_destino_pago = isset($_POST["cuenta_destino_pago"]) ? limpiarCadena($_POST["cuenta_destino_pago"]) : "";
$banco_pago = isset($_POST["banco_pago"]) ? limpiarCadena($_POST["banco_pago"]) : "";
$titular_cuenta_pago = isset($_POST["titular_cuenta_pago"]) ? limpiarCadena($_POST["titular_cuenta_pago"]) : "";
$fecha_pago = isset($_POST["fecha_pago"]) ? limpiarCadena($_POST["fecha_pago"]) : "";
$monto_pago = isset($_POST["monto_pago"]) ? limpiarCadena($_POST["monto_pago"]) : "";
$numero_op_pago = isset($_POST["numero_op_pago"]) ? limpiarCadena($_POST["numero_op_pago"]) : "";
$descripcion_pago = isset($_POST["descripcion_pago"]) ? limpiarCadena($_POST["descripcion_pago"]) : "";
$idcompra_af_general_p = isset($_POST["idcompra_af_general_p"]) ? limpiarCadena($_POST["idcompra_af_general_p"]) : "";
$idpago_af_general= isset($_POST["idpago_af_general"]) ? limpiarCadena($_POST["idpago_af_general"]) : "";

$idproveedor_pago = isset($_POST["idproveedor_pago"]) ? limpiarCadena($_POST["idproveedor_pago"]) : "";

$imagen1 = isset($_POST["foto1"]) ?$_POST["foto1"]: "";

// :::::::::::::::::::::::::::::::::::: D A T O S   M A T E R I A L E S ::::::::::::::::::::::::::::::::::::::
$idproducto_p     = isset($_POST["idproducto_p"]) ? limpiarCadena($_POST["idproducto_p"]) : "" ;
$unidad_medida_p  = isset($_POST["unidad_medida_p"]) ? limpiarCadena($_POST["unidad_medida_p"]) : "" ;
$color_p          = isset($_POST["color_p"]) ? limpiarCadena($_POST["color_p"]) : "" ;
$categoria_insumos_af_p    = isset($_POST["categoria_insumos_af_p"]) ? limpiarCadena($_POST["categoria_insumos_af_p"]) : "" ;
$nombre_p         = isset($_POST["nombre_p"]) ? limpiarCadena($_POST["nombre_p"]) : "" ;
$modelo_p         = isset($_POST["modelo_p"]) ? limpiarCadena($_POST["modelo_p"]) : "" ;
$serie_p          = isset($_POST["serie_p"]) ? limpiarCadena($_POST["serie_p"]) : "" ;
$marca_p          = isset($_POST["marca_p"]) ? limpiarCadena($_POST["marca_p"]) : "" ;
$estado_igv_p     = isset($_POST["estado_igv_p"]) ? limpiarCadena($_POST["estado_igv_p"]) : "" ;
$precio_unitario_p= isset($_POST["precio_unitario_p"]) ? limpiarCadena($_POST["precio_unitario_p"]) : "" ;      
$precio_sin_igv_p = isset($_POST["precio_sin_igv_p"]) ? limpiarCadena($_POST["precio_sin_igv_p"]) : "" ;
$precio_igv_p     = isset($_POST["precio_igv_p"]) ? limpiarCadena($_POST["precio_igv_p"]) : "" ;
$precio_total_p   = isset($_POST["precio_total_p"]) ? limpiarCadena($_POST["precio_total_p"]) : "" ;      
$descripcion_p    = isset($_POST["descripcion_p"]) ? limpiarCadena($_POST["descripcion_p"]) : "" ; 
$img_pefil_p      = isset($_POST["fotop2"]) ? limpiarCadena($_POST["fotop2"]) : "" ;
$ficha_tecnica_p  = isset($_POST["doct2"]) ? limpiarCadena($_POST["doct2"]) : "" ;

switch ($_GET["op"]) {

    case 'guardaryeditarcompraactivo': 
        
        if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
        } else {
                //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['activo_fijo_general'] == 1) {
                    //contenido
                if (empty($idcompra_af_general)) {

                    $rspta = $all_activos_fijos->insertar(
                        $idproveedor,
                        $fecha_compra,
                        $tipo_comprovante,
                        $serie_comprovante,
                        $descripcion,
                        $subtotal_compra,
                        $igv_compra,
                        $total_compra_af_g,
                        $_POST["idactivos_fijos"],
                        $_POST["unidad_medida"], 
                        $_POST["nombre_color"],
                        $_POST["cantidad"],
                        $_POST["precio_sin_igv"],
                        $_POST["precio_igv"],
                        $_POST["precio_con_igv"],
                        $_POST["descuento"],
                        $_POST["ficha_tecnica_activo"]
                    );
                    //precio_sin_igv,precio_igv,precio_total
                    echo $rspta ? "ok" : "No se pudieron registrar todos los datos de la compra";
                } else {
                    $rspta=$all_activos_fijos->editar($idcompra_af_general,$idproveedor, $fecha_compra, $tipo_comprovante,
                    $serie_comprovante, $descripcion, $subtotal_compra,
                    $igv_compra, $total_compra_af_g,
                    $_POST["idactivos_fijos"],
                    $_POST["unidad_medida"], $_POST["nombre_color"],  $_POST["cantidad"],
                    $_POST["precio_sin_igv"], $_POST["precio_igv"],
                    $_POST["precio_con_igv"], $_POST["descuento"],
                    $_POST["ficha_tecnica_activo"]);

                    echo $rspta ? "ok" : "Compra no se pudo actualizar";
                }  
                //Fin de las validaciones de acceso
            } else {
                require 'noacceso.php';
            }
        }      
                
    break;

    case 'guardaryeditar_comprobante_af_g':
        //comprobante
        $idcompra_af_g_o_p = isset($_POST["idcompra_af_g_o_p"]) ? limpiarCadena($_POST["idcompra_af_g_o_p"]) : "";
        $doc1 = isset($_POST["doc1"]) ?$_POST["doc1"] : "";
        $doc_old_1 = isset($_POST["doc_old_1"]) ? limpiarCadena($_POST["doc_old_1"]) : "";

        if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
        } else {
                //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['activo_fijo_general'] == 1) {
                    //contenido

                // imgen de perfil
                if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {
                    $doc1 = $_POST["doc_old_1"];
                    $flat_comprob = false;
                } else {
                    $ext1 = explode(".", $_FILES["doc1"]["name"]);
                    $flat_comprob = true;

                    $doc1 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

                    move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/activos_fijos_general/comprobantes_compra_activos_f/" . $doc1);
                }
                //Borramos el comprobante
                if ($flat_comprob == true) {
                    $datos_f1 =$all_activos_fijos->obtener_comprobante_af_g($idcompra_af_g_o_p);

                    $doc1_ant = $datos_f1['comprobante'];

                    if ($doc1_ant != "") {
                        unlink("../dist/docs/activos_fijos_general/comprobantes_compra_activos_f/" . $doc1_ant);
                    }
                }

                // editamos un documento existente
                $rspta =  $all_activos_fijos->editar_comprobante_af_g($idcompra_af_g_o_p,$doc1);

                echo $rspta ? "ok" : "Documento no se pudo actualizar";
                    //Fin de las validaciones de acceso
            } else {
                require 'noacceso.php';
            }
        }  
    break;

    case 'guardaryeditar_comprobante_af_p':

        //comprobante
        $comp_idcompra_af_proyecto = isset($_POST["comp_idcompra_af_proyecto"]) ? limpiarCadena($_POST["comp_idcompra_af_proyecto"]) : "";
        $doc2 = isset($_POST["doc2"]) ?$_POST["doc2"] : "";
        $doc_old_2 = isset($_POST["doc_old_2"]) ? limpiarCadena($_POST["doc_old_2"]) : "";
    
        if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
        } else {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['activo_fijo_general'] == 1) {    
                // imgen de comprobante
                if (!file_exists($_FILES['doc2']['tmp_name']) || !is_uploaded_file($_FILES['doc2']['tmp_name'])) {
                    $doc_comprobante = $_POST["doc_old_2"];
                    $flat_comprob = false;
                } else {
                    $ext1 = explode(".", $_FILES["doc2"]["name"]);
                    $flat_comprob = true;

                    $doc_comprobante = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

                    move_uploaded_file($_FILES["doc2"]["tmp_name"], "../dist/docs/activos_fijos_proyecto/comprobantes_activos_fijos_p/" . $doc_comprobante);
                }
                //Borramos el comprobante
                if ($flat_comprob == true) {
                    $datos_f1 =  $ctivos_fijos_proy->obtener_comprobante_comprasa_af_p($comp_idcompra_af_proyecto);

                    $doc2_ant = $datos_f1->fetch_object()->comprobante;

                    if ($doc2_ant != "") {
                        unlink("../dist/docs/activos_fijos_proyecto/comprobantes_activos_fijos_p/" . $doc2_ant);
                    }
                }

                // editamos un documento existente
                $rspta =  $ctivos_fijos_proy->editar_comprobante($comp_idcompra_af_proyecto,$doc_comprobante);

                //echo $comp_idcompra_af_proyecto;
                echo $rspta ? "ok" : "Documento no se pudo actualizar";
            } else {
                require 'noacceso.php';
            }
        }
    break;
    
  case 'guardar_y_editar_materiales':
    // imgen
    if (!file_exists($_FILES['fotop2']['tmp_name']) || !is_uploaded_file($_FILES['fotop2']['tmp_name'])) {

      $img_pefil_p = $_POST["fotop2_actual"];

      $flat_img1 = false;

    } else {

      $ext1 = explode(".", $_FILES["fotop2"]["name"]);

      $flat_img1 = true;

      $img_pefil_p = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

      move_uploaded_file($_FILES["fotop2"]["tmp_name"], "../dist/docs/material/img_perfil/" . $img_pefil_p);
    }

    // ficha técnica
    if (!file_exists($_FILES['doct2']['tmp_name']) || !is_uploaded_file($_FILES['doct2']['tmp_name'])) {

      $ficha_tecnica_p = $_POST["doc_oldt_2"];

      $flat_ficha1 = false;

    } else {

      $ext1 = explode(".", $_FILES["doct2"]["name"]);

      $flat_ficha1 = true;

      $ficha_tecnica_p = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

      move_uploaded_file($_FILES["doct2"]["tmp_name"], "../dist/docs/material/ficha_tecnica/" . $ficha_tecnica_p);
    }

    if (empty($idproducto)) {
      //var_dump($idproyecto,$idproveedor);
      $rspta = $all_activos_fijos->insertar_material( $unidad_medida_p, $color_p, $categoria_insumos_af_p, $nombre_p, $modelo_p, $serie_p, $marca_p, $estado_igv_p, $precio_unitario_p, $precio_igv_p, $precio_sin_igv_p, $precio_total_p, $ficha_tecnica_p, $descripcion_p,  $img_pefil_p);
      
      echo $rspta ? "ok" : "No se pudieron registrar todos los datos";

    } 
  break;


    case 'anular':
        if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
        } else {
                //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['activo_fijo_general'] == 1) {
                    //contenido

                $rspta = $all_activos_fijos->desactivar($idcompra_af_general);

                echo $rspta ? "ok" : "Compra no se puede Anular";
            //Fin de las validaciones de acceso
            } else {
                require 'noacceso.php';
            }
        }       
    break;

    case 'des_anular':
        if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
        } else {
                //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['activo_fijo_general'] == 1) {
                    //contenido
        
                $rspta = $all_activos_fijos->activar($idcompra_af_general);

                echo $rspta ? "ok" : "Compra no se puede recuperar";
                //Fin de las validaciones de acceso
            } else {
                require 'noacceso.php';
            }
        }
    break;
    //listar_compras
    case 'listar_compra_activo_f_g':
        if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
        } else {
                //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['activo_fijo_general'] == 1) {
                    //contenido

                $rspta = $all_activos_fijos->listar_compra_activo_f_g();
                //Vamos a declarar un array
                $data = [];

                $c = "";
                $cc = "";
                $nombre = "";
                $info = "";
                $icon = "";
                $serie_comprobante = "";
                $tipo_comprobante1 = "";
                $num_comprob = "";

                foreach ($rspta as $key => $reg) {
                    
                    $saldo= floatval($reg['total'])-floatval($reg['deposito']);

                    $tipo_comprobante1 = $reg['tipo_comprobante'];

                    if ($saldo == $reg['total']) {
                        $estado = '<span class="text-center badge badge-danger">Sin pagar</span>';
                        $c="danger";
                        $nombre="Pagar";
                        $icon="dollar-sign";
                        $cc="danger";
                    }else{
                                
                        if ($saldo<$reg['total'] && $saldo>"0" ) {

                            $estado = '<span class="text-center badge badge-warning">En proceso</span>';
                            $c="warning";
                            $nombre="Pagar";
                            $icon="dollar-sign";
                            $cc="warning";
                        } else {
                            if ($saldo<="0" || $saldo=="0") {
                                $estado = '<span class="text-center badge badge-success">Pagado</span>';
                                $c="success";
                                $nombre="Ver";
                                $info="info";
                                $icon="eye";
                                $cc="success";
                            }else{
                                $estado = '<span class="text-center badge badge-success">Error</span>';
                            }
                        }  

                    }

                    empty($reg['serie_comprobante']) ? ($serie_comprobante = "-") : ($serie_comprobante = $reg['serie_comprobante']);
                    $data[] = [
                        "0" =>(empty($reg['idproyecto'])) ?($reg['estado'] == '1'? '<button class="btn btn-info btn-sm" onclick="ver_compras_af_g('.$reg['idtabla'].')" data-toggle="tooltip" data-original-title="Ver detalle compra"><i class="fa fa-eye"></i></button>' .
                                    ' <button class="btn btn-warning btn-sm" onclick="editar_detalle_compras('.$reg['idtabla'].')" data-toggle="tooltip" data-original-title="Editar compra"><i class="fas fa-pencil-alt"></i></button>'.
                                    ' <button class="btn btn-danger btn-sm" onclick="anular('.$reg['idtabla'].')" data-toggle="tooltip" data-original-title="Anular Compra"><i class="far fa-trash-alt"></i></button>'
                                : '<button class="btn btn-info btn-sm" onclick="ver_compras_af_g(' .$reg['idtabla']. ')"data-toggle="tooltip" data-original-title="Ver detalle"><i class="fa fa-eye"></i></button>' . 
                                ' <button class="btn btn-success btn-sm" onclick="des_anular('.$reg['idtabla'].')" data-toggle="tooltip" data-original-title="Recuperar Compra"><i class="fas fa-check"></i></button>') :
                                ($reg['estado'] == '1'? '<button class="btn btn-info btn-sm " onclick="ver_compras_af_p('.$reg['idtabla'].')" data-toggle="tooltip" data-original-title="Ver detalle compra"><i class="fa fa-eye"></i></button>' .
                                    ' <button class="btn btn-warning btn-sm" disabled onclick="editar_detalle_compras_af_p('.$reg['idtabla'].')" data-toggle="tooltip" data-original-title="Editar compra"><i class="fas fa-pencil-alt"></i></button>'.
                                    ' <button class="btn btn-danger btn-sm" disabled onclick="anular_af_p('.$reg['idtabla'].')" data-toggle="tooltip" data-original-title="Anular Compra"><i class="far fa-trash-alt"></i></button>'
                                : '<button class="btn btn-info btn-sm" disabled onclick="ver_compras_af_p(' .$reg['idtabla']. ')"data-toggle="tooltip" data-original-title="Ver detalle"><i class="fa fa-eye"></i></button>' . 
                                ' <button class="btn btn-success btn-sm" disabled onclick="des_anular_af_p('.$reg['idtabla'].')" data-toggle="tooltip" data-original-title="Recuperar Compra"><i class="fas fa-check"></i></button>'),
                        "1" => '<textarea class="form-control text_area_clss" cols="30" rows="2">'. $reg['descripcion'].'</textarea>',
                       
                        "2" => date("d/m/Y", strtotime($reg['fecha_compra'])),
                        "3" => '<div class="user-block">
                                    <span class="description" style="margin-left: 0px !important;"><b>'.((empty($reg['idproyecto'])) ? 'General' : $reg['codigo_proyecto']).'</b></span>
                                    <span class="username" style="margin-left: 0px !important;"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'. $reg['razon_social'] .'</p></span>
                                    <span class="description" style="margin-left: 0px !important;"><b>Cel: </b><a class="text-body" href="tel:+51'.quitar_guion($reg['telefono']).'" data-toggle="tooltip" data-original-title="Llamar al proveedor.">'. $reg['telefono'] . '</a> </span>
                                   
                                </div>',
                        "4" => '<div class="user-block">
                                <span class="username" style="margin-left: 0px !important;"><p style="margin-bottom: 0.2rem !important"; >'.$tipo_comprobante1.'</p></span>
                                <span class="description" style="margin-left: 0px !important;">Número: '. $serie_comprobante .' </span>
                            </div>',
                        "5" => number_format($reg['total'], 2, '.', ','),
                        "6" => (empty($reg['idproyecto'])) ?'<div class="text-center text-nowrap"> <button class="btn btn-' .$c .' btn-xs m-t-2px" onclick="listar_pagos_af_g(' . $reg['idtabla'] . ',' . $reg['total'] .',' .floatval($reg['deposito']).')">
                                <i class="fas fa-' .  $icon . ' nav-icon"></i> ' . $nombre .'</button>'.' 
                                <button style="font-size: 14px;" class="btn btn-'.$cc.' btn-sm">'.number_format(floatval($reg['deposito']), 2, '.', ',').'</button></div>':
                                '<div class="text-center text-nowrap"> <button class="btn btn-' .$c .' btn-xs m-t-2px" disabled onclick="listar_pagos(' . $reg['idtabla'] . ',' . $reg['total'] .',' .floatval($reg['deposito']).')">
                                <i class="fas fa-' .  $icon . ' nav-icon"></i> ' . $nombre .'</button>'.' 
                                <button style="font-size: 14px;" class="btn btn-'.$cc.' btn-sm" disabled>'.number_format(floatval($reg['deposito']), 2, '.', ',').'</button></div>',
                        "7" => number_format($saldo, 2, '.', ','),
                        "8" => (empty($reg['idproyecto'])) ?'<center><button class="btn btn-outline-info btn-sm" onclick="comprobante_compra_af_g(' . $reg['idtabla']  .', \'' .  $reg['imagen_comprobante'] .  '\')"><i class="fas fa-file-invoice fa-lg"></i></button></center>':
                        '<center><button class="btn btn-outline-info btn-sm" onclick="comprobante_compras(' . $reg['idtabla']  .', \'' .  $reg['imagen_comprobante'] .  '\')"><i class="fas fa-file-invoice fa-lg"></i></button></center>',
                        "9" => $reg['estado'] == '1' ? '<span class="badge bg-success">Aceptado</span>' : '<span class="badge bg-danger">Anulado</span>',
                    ];
                }
                $results = [
                    "sEcho" => 1, //Información para el datatables
                    "iTotalRecords" => count($data), //enviamos el total registros al datatable
                    "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
                    "data" => $data,
                ];
                echo json_encode($results);       
                
        } else {
            require 'noacceso.php';
            }
        }

    break;
    //tbl listar compras por proveedor
    case 'listar_compraxporvee_af_g':

        if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
        } else {
                //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['activo_fijo_general'] == 1) {
                    //contenido


                $rspta = $all_activos_fijos->listar_compraxporvee_af_g();
                //Vamos a declarar un array
                $data = [];
                $c = "info";
                $nombre = "Ver";
                $info = "info";
                $icon = "eye";


                foreach ($rspta as $key => $value) {
                    $data[] = [
                        "0" =>'<button class="btn btn-info btn-sm" onclick="listar_facuras_proveedor_af_g(' . $value['idproveedor'] . ')" data-toggle="tooltip" data-original-title="Ver detalle"><i class="fa fa-eye"></i></button>',
                        "1" => '<div class="user-block">
                                <span class="username" style="margin-left: 0px !important;"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'. $value['razon_social'].'</p></span>
                                <span class="description" style="margin-left: 0px !important;"><b>'. $value['tipo_documento'].' </b>'.$value['ruc'].'</span></div>',
                        "2" => number_format($value['total'], 2, '.', ','),
                    ];
                }
                $results = [
                    "sEcho" => 1, //Información para el datatables
                    "iTotalRecords" => count($data), //enviamos el total registros al datatable
                    "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
                    "aaData" => $data,
                ];
                echo json_encode($results);

            } else {
            require 'noacceso.php';
            }
         }

    break;
	
    case 'listar_detalle_compraxporvee':
        if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
        } else {
                //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['activo_fijo_general'] == 1) {
                    //contenido

                $rspta = $all_activos_fijos->listar_detalle_comprax_provee($_GET["idproveedor"]);
                //Vamos a declarar un array
                $data = [];
               // <span class="description" style="margin-left: 0px !important;"><b>'.((empty($value['idproyecto'])) ? 'General':$value['idproyecto'] ).' </b></span>
                foreach ($rspta as $key => $value) {
                    $data[] = [
                        "0" => (empty($value['idproyecto'])) ?'<center><button class="btn btn-info btn-sm" onclick="ver_compras_af_g(' . $value['idtabla'].')" data-toggle="tooltip" data-original-title="Ver detalle">Ver detalle <i class="fa fa-eye"></i></button></center>':
                                '<center><button class="btn btn-info btn-sm" onclick="ver_compras_af_p(' . $value['idtabla'].')" data-toggle="tooltip" data-original-title="Ver detalle">Ver detalle <i class="fa fa-eye"></i></button></center>',
                        "1" => date("d/m/Y", strtotime($value['fecha_compra'])),
                        "2" => '<div class="user-block">
                                <span class="username" style="margin-left: 0px !important;"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'.((empty($value['idproyecto'])) ? 'General' : $value['codigo_proyecto']).'</p></span>                                   
                                </div>',
                        "3" =>'<div class="user-block">
                        <span class="username" style="margin-left: 0px !important;"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'. $value['tipo_comprobante'] .'</p></span> 
                        <span class="description" style="margin-left: 0px !important;"><b>'.$value['serie_comprobante'].'</b></span>                                  
                        </div>',
                        "4" =>number_format($value['total'], 2, '.', ','),
                        "5" => $value['descripcion'],
                        "6" => $value['estado'] == '1' ? '<span class="badge bg-success">Aceptado</span>' : '<span class="badge bg-danger">Anulado</span>',
                    ];
                }
                $results = [
                    "sEcho" => 1, //Información para el datatables
                    "iTotalRecords" => count($data), //enviamos el total registros al datatable
                    "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
                    "aaData" => $data,
                ];
                echo json_encode($results);

            } else {
                require 'noacceso.php';
            }
        }

    break;

    case 'ver_detalle_compras_af_g':
        if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
        } else {
                //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['activo_fijo_general'] == 1) {
                    //contenido
                //Recibimos el idingreso
				$idcompra_af_general=$_GET['idcompra_af_general'];
				//$id_compra='2';

				$rspta  =  $all_activos_fijos->listarDetalle($idcompra_af_general);
                $rspta2 =  $all_activos_fijos->ver_compra($idcompra_af_general);
				$subtotal=0;
                $ficha='';
				echo '<thead style="background-color:#A9D0F5">
                                                <th>Ficha técnica</th>
                                                <th>Material</th>
                                                <th>Cantidad</th>
                                                <th>Precio Compra</th>
                                                <th>Descuento</th>
                                                <th>Subtotal</th>
										</thead>';

				while ($reg = $rspta->fetch_object())
						{
                           $subtotal = ($reg->cantidad*$reg->precio_con_igv)-$reg->descuento;
                           
                           empty($reg->ficha_tecnica)
                           ? ($ficha = '<a ><i class="far fa-file-pdf fa-2x" style="color:#000000c4"></i></a>')
                           : ($ficha = '<a target="_blank" href="../dist/docs/activos_fijos_general/ficha_tecnica_activos_fijos/' . $reg->ficha_tecnica . '"><i class="far fa-file-pdf fa-2x" style="color:#ff0000c4"></i></a>');
							echo '<tr class="filas">
                                    <td>'.$ficha.'</td>
                                    <td>'.$reg->nombre.'</td>
                                    <td>'.$reg->cantidad.'</td>
                                    <td>'.number_format($reg->precio_con_igv, 2, '.', ',').'</td>
                                    <td>'.number_format($reg->descuento, 2, '.', ',').'</td>
                                    <td>'.number_format($subtotal, 2, '.', ',').'</td></tr>';
						}
				echo '<tfoot>
                        <td colspan="4"></td>
                        <th class="text-center">
                            <h5>Subtotal</h5>
                            <h5>IGV</h5>
                            <h5>TOTAL</h5>
                        </th>
                        <th>
                            <h5 class="text-right subtotal"  style="font-weight: bold;">S/'.number_format($rspta2['subtotal'], 2, '.', ',').'</h5>
                            <h5 class="text-right igv_comp" style="font-weight: bold;">S/'.number_format($rspta2['igv'], 2, '.', ',').'</h5>
                            <b>
                                <h4 class="text-right total"  style="font-weight: bold;">S/'.number_format($rspta2['total'], 2, '.', ',').'</h4>
                            </b>
                    </tfoot>';
            } else {
                require 'noacceso.php';
            }
        }

    break;

    case 'ver_compra':
        if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
        } else {
                //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['activo_fijo_general'] == 1) {
                    //contenido
                //$idpago_af_general='1';
                $rspta = $all_activos_fijos->ver_compra($idcompra_af_general);
                //Codificar el resultado utilizando json
                echo json_encode($rspta);
                //Fin de las validaciones de acceso
                             
            } else {
                require 'noacceso.php';
            }
        }

    break;

    case 'ver_compra_editar':

        if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
        } else {
                //Validamos el acceso solo al usuario logueado y autorizado.
                if ($_SESSION['activo_fijo_general'] == 1) {
                    //contenido
            
                $rspta = $all_activos_fijos->mostrar_compra_para_editar($idcompra_af_general);
                //Codificar el resultado utilizando json
                echo json_encode($rspta);

            } else {
                require 'noacceso.php';
            }
        }

                     
    break;

    case 'listarActivoscompra':
        if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
        } else {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['activo_fijo_general'] == 1) {


                $rspta =$all_activos_fijos->lista_activos_para_compras();
                //Vamos a declarar un array
                $datas = [];
                // echo json_encode($rspta);
                $img_parametro = ""; $img = ""; $imagen_error = "this.src='../dist/img/default/img_defecto_activo_fijo.png'";    $color_stock = "";   $ficha_tecnica = ""; 
                
                while ($reg = $rspta->fetch_object()) {

                    if (empty($reg->imagen)) {
                        $img='src="../dist/img/default/img_defecto_activo_fijo.png"';
                        $img_parametro="img_defecto_activo_fijo.png";
                    } else {
                        $img='src="../dist/docs/activos_fijos_general/img_activos_fijos/'.$reg->imagen.'"';
                        $img_parametro=$reg->imagen;
                    }

                    !empty($reg->ficha_tecnica)
                        ? ($ficha_tecnica = '<center><a target="_blank" href="../dist/docs/activos_fijos_general/ficha_tecnica_activos_fijos/' . $reg->ficha_tecnica . '"><i class="far fa-file-pdf fa-2x text-success"></i></a></center>')
                        : ($ficha_tecnica = '<center><span class="text-center"> <i class="far fa-times-circle fa-2x text-danger"></i></span></center>');

                    $datas[] = [
                        "0" =>
                            '<button class="btn btn-warning" onclick="agregarDetalleCompraActivos(' . $reg->idproducto  . 
                            ', \'' .  $reg->nombre .  '\', \'' .  $reg->nombre_medida.  '\', \'' . $reg->nombre_color.  '\', \'' . $reg->precio_sin_igv . '\', \'' .
                            $reg->igv. '\', \'' . $reg->precio_con_igv.  '\', \'' . $img_parametro . '\', \'' .$reg->ficha_tecnica .'\')" 
                            data-toggle="tooltip" data-original-title="Agregar Activo"><span class="fa fa-plus"></span></button>',
                        "1" =>
                            '<div class="user-block w-px-200">
                                <img class="profile-user-img img-responsive img-circle" ' . $img .' alt="user image" onerror="'.$imagen_error.'">
                                <span class="username"><p style="margin-bottom: 0px !important;">' . $reg->nombre . '</p></span>
                                <span class="description"><b>Color: </b>'. $reg->nombre_color.'</span>
                            </div>',
                        "2" => $reg->marca,
                        "3" => number_format($reg->precio_con_igv, 2, '.', ','),
                        "4" => '<textarea class="form-control text_area_clss" cols="30" rows="2">'.$reg->descripcion.'</textarea>',
                        "5" => $ficha_tecnica,
                    ];
                }

                $results = [
                    "sEcho" => 1, //Información para el datatables
                    "iTotalRecords" => count($datas), //enviamos el total registros al datatable
                    "iTotalDisplayRecords" => count($datas), //enviamos el total registros a visualizar
                    "aaData" => $datas,
                ];
                echo json_encode($results);

        
            } else {
                require 'noacceso.php';
            }
        }

    break;
    case 'listarActivoscompra2':
        if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
        } else {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['activo_fijo_general'] == 1) {

                $rspta =$all_activos_fijos->lista_activos_para_compras();
                //Vamos a declarar un array
                $datas = [];
                // echo json_encode($rspta);
                $img_parametro = ""; $img = ""; $imagen_error = "this.src='../dist/img/default/img_defecto_activo_fijo.png'";    $color_stock = "";   $ficha_tecnica = ""; 
                
                while ($reg = $rspta->fetch_object()) {

                    if (empty($reg->imagen)) {
                        $img='src="../dist/img/default/img_defecto_activo_fijo.png"';
                        $img_parametro="img_defecto_activo_fijo.png";
                    } else {
                        $img='src="../dist/docs/activos_fijos_general/img_activos_fijos/'.$reg->imagen.'"';
                        $img_parametro=$reg->imagen;
                    }

                    !empty($reg->ficha_tecnica)
                        ? ($ficha_tecnica = '<center><a target="_blank" href="../dist/docs/activos_fijos_general/ficha_tecnica_activos_fijos/' . $reg->ficha_tecnica . '"><i class="far fa-file-pdf fa-2x text-success"></i></a></center>')
                        : ($ficha_tecnica = '<center><span class="text-center"> <i class="far fa-times-circle fa-2x text-danger"></i></span></center>');

                    $datas[] = [
                        "0" =>
                            '<button class="btn btn-warning" onclick="agregarDetalleCompraActivos_p(' . $reg->idactivos_fijos  . 
                            ', \'' .  $reg->nombre .  '\', \'' .  $reg->nombre_medida.  '\', \'' . $reg->nombre_color.  '\', \'' . $reg->subtotal . '\', \'' .
                            $reg->igv. '\', \'' . $reg->total.  '\', \'' . $img_parametro . '\', \'' .$reg->ficha_tecnica .'\')" 
                            data-toggle="tooltip" data-original-title="Agregar Activo"><span class="fa fa-plus"></span></button>',
                        "1" =>
                            '<div class="user-block w-px-200">
                                <img class="profile-user-img img-responsive img-circle" ' . $img .' alt="user image" onerror="'.$imagen_error.'">
                                <span class="username"><p style="margin-bottom: 0px !important;">' . $reg->nombre . '</p></span>
                                <span class="description"><b>Color: </b>'. $reg->nombre_color.'</span>
                            </div>',
                        "2" => $reg->marca,
                        "3" => number_format($reg->total, 2, '.', ','),
                        "4" => $reg->descripcion,
                        "5" => $ficha_tecnica,
                    ];
                }

                $results = [
                    "sEcho" => 1, //Información para el datatables
                    "iTotalRecords" => count($datas), //enviamos el total registros al datatable
                    "iTotalDisplayRecords" => count($datas), //enviamos el total registros a visualizar
                    "aaData" => $datas,
                ];
                echo json_encode($results);

        
            } else {
                require 'noacceso.php';
            }
        }

    break;

    case 'selectProveedor':

        if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
        } else {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['activo_fijo_general'] == 1) {
                //contenido
                                
                require_once "../modelos/AllProveedor.php";
                $proveedor = new Proveedor();

                $rspta = $proveedor->listar_compra();

                while ($reg = $rspta->fetch_object()) {
                    echo '<option value=' . $reg->idproveedor . '>' . $reg->razon_social . ' - ' . $reg->ruc . '</option>';
                }

            } else {
                require 'noacceso.php';
            }
        }

    break;
    /**
     * ==============SECCION PAGOS=====
     */

    //MOSTRANDO DATOS DE PROVEEDOR
    case 'most_datos_prov_pago':
        if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
        } else {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['activo_fijo_general'] == 1) {

                $rspta = $all_activos_fijos->most_datos_prov_pago($_POST["idcompra_af_general"]);
                //Codificar el resultado utilizando json
                echo json_encode($rspta);
                //Fin de las validaciones de acceso
            } else {
                require 'noacceso.php';
            }
        }
    break;

    case 'guardaryeditar_pago':
        if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
        } else {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['activo_fijo_general'] == 1) {
                // imgen de perfil
                if (!file_exists($_FILES['foto1']['tmp_name']) || !is_uploaded_file($_FILES['foto1']['tmp_name'])) {
                    $imagen1 = $_POST["foto1_actual"];
                    $flat_img1 = false;
                } else {
                    $ext1 = explode(".", $_FILES["foto1"]["name"]);
                    $flat_img1 = true;

                    $imagen1 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

                    move_uploaded_file($_FILES["foto1"]["tmp_name"], "../dist/docs/activos_fijos_general/comprobantes_pagos_activos_f/" . $imagen1);
                }

                if (empty($idpago_af_general)) {
                    $rspta = $all_activos_fijos->insertar_pago(
                        $idcompra_af_general_p,
                        $beneficiario_pago,
                        $forma_pago,
                        $tipo_pago,
                        $cuenta_destino_pago,
                        $banco_pago,
                        $titular_cuenta_pago,
                        $fecha_pago,
                        $monto_pago,
                        $numero_op_pago,
                        $descripcion_pago,
                        $imagen1
                    );
                    echo $rspta ? "ok" : "No se pudieron registrar todos los datos de servicio";
                } else {
                    // validamos si existe LA IMG para eliminarlo
                    if ($flat_img1 == true) {
                        $datos_f1 = $all_activos_fijos->obtenerImg($idpago_af_general);

                        $img1_ant = $datos_f1->fetch_object()->imagen;

                        if ($img1_ant != "") {
                            unlink("../dist/docs/activos_fijos_general/comprobantes_pagos_activos_f/" . $img1_ant);
                        }
                    }

                    $rspta = $all_activos_fijos->editar_pago(
                        $idpago_af_general,
                        $idcompra_af_general_p,
                        $beneficiario_pago,
                        $forma_pago,
                        $tipo_pago,
                        $cuenta_destino_pago,
                        $banco_pago,
                        $titular_cuenta_pago,
                        $fecha_pago,
                        $monto_pago,
                        $numero_op_pago,
                        $descripcion_pago,
                        $imagen1
                    );

                    echo $rspta ? "ok" : "Servicio no se pudo actualizar";
                }
                //Fin de las validaciones de acceso
            } else {
                require 'noacceso.php';
            }
        }
    break;

    case 'desactivar_pagos':
        if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
        } else {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['activo_fijo_general'] == 1) {
                $rspta = $all_activos_fijos->desactivar_pagos($idpago_af_general);
                echo $rspta ? "Pago Anulado" : "Pago no se puede Anular";
                //Fin de las validaciones de acceso
            } else {
                require 'noacceso.php';
            }
        }
    break;

    case 'activar_pagos':
        if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
        } else {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['activo_fijo_general'] == 1) {
                $rspta = $all_activos_fijos->activar_pagos($idpago_af_general);
                echo $rspta ? "Pago Restablecido" : "Pago no se pudo Restablecido";
                //Fin de las validaciones de acceso
            } else {
                require 'noacceso.php';
            }
        }
    break;

    case 'listar_pagos_proveedor':

        if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
        } else {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['activo_fijo_general'] == 1) {

                $rspta = $all_activos_fijos->listar_pagos_af_g( $_GET["idcompra_af_general"]);
                //Vamos a declarar un array
                $data = [];

                $suma = 0;
                $imagen = '';

                while ($reg = $rspta->fetch_object()) {
                    $suma = $suma + $reg->monto;
                    if (strlen($reg->descripcion) >= 20) {
                        $descripcion = substr($reg->descripcion, 0, 20) . '...';
                    } else {
                        $descripcion = $reg->descripcion;
                    }
                    if (strlen($reg->titular_cuenta) >= 20) {
                        $titular_cuenta = substr($reg->titular_cuenta, 0, 20) . '...';
                    } else {
                        $titular_cuenta = $reg->titular_cuenta;
                    }
                    empty($reg->imagen)
                        ? ($imagen = '<div><center><a type="btn btn-danger" class=""><i class="far fa-times-circle fa-2x"></i></a></center></div>')
                        : ($imagen = '<div><center><a type="btn btn-danger" class=""  href="#" onclick="ver_modal_vaucher(' . "'" . $reg->imagen . "'" . ')"><i class="fas fa-file-invoice-dollar fa-2x"></i></a></center></div>');
                    $tool = '"tooltip"';
                    $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>";
                    $data[] = [
                        "0" => $reg->estado
                            ? '<button class="btn btn-warning btn-sm" onclick="mostrar_pagos(' .
                                $reg->idpago_af_general.
                                ')"><i class="fas fa-pencil-alt"></i></button>' .
                                ' <button class="btn btn-danger btn-sm" onclick="desactivar_pagos(' .
                                $reg->idpago_af_general.
                                ')"><i class="far fa-trash-alt"></i></button>'
                            : '<button class="btn btn-warning btn-sm" onclick="mostrar_pagos(' .
                                $reg->idpago_af_general.
                                ')"><i class="fa fa-pencil-alt"></i></button>' .
                                ' <button class="btn btn-primary btn-sm" onclick="activar_pagos(' .
                                $reg->idpago_af_general.
                                ')"><i class="fa fa-check"></i></button>',
                        "1" => $reg->forma_pago,
                        "2" =>'<div class="user-block">
                        <span class="username" style="margin-left: 0px !important;"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'. $reg->beneficiario.'</p></span>
                        <span class="description" style="margin-left: 0px !important;" data-toggle="tooltip" data-original-title="' . $reg->titular_cuenta . '"><b>titular: </b>' . $titular_cuenta . '</span></div>',
                        "3" => '<div class="user-block">
                        <span class="username" style="margin-left: 0px !important;"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'.$reg->banco.'</p></span>
                        <span class="description" style="margin-left: 0px !important;" data-toggle="tooltip" data-original-title="' . $reg->cuenta_destino . '"><b>C: </b>' . $reg->cuenta_destino . '</span></div>',
                        "4" => date("d/m/Y", strtotime($reg->fecha_pago)),
                        "5" => empty($reg->descripcion) ? '-' : '<div data-toggle="tooltip" data-original-title="' . $reg->descripcion . '">' . $descripcion . '</div>',
                        "6" => number_format($reg->monto, 2, '.', ','),
                        "7" => $imagen,
                        "8" => $reg->estado ? '<span class="text-center badge badge-success">Activado</span>' . $toltip : '<span class="text-center badge badge-danger">Desactivado</span>' . $toltip,
                    ];
                }
                //$suma=array_sum($rspta->fetch_object()->monto);
                $results = [
                    "sEcho" => 1, //Información para el datatables
                    "iTotalRecords" => count($data), //enviamos el total registros al datatable
                    "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
                    "data" => $data,
                    "suma" => $suma,
                ];
                echo json_encode($results);
                //Fin de las validaciones de acceso
            } else {
                require 'noacceso.php';
            }
        }
    break;

    case 'suma_total_pagos':
        $idcompra_af_general = $_POST["idcompra_af_general"];
        $rspta = $all_activos_fijos->suma_total_pagos($idcompra_af_general);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'mostrar_pagos':

        if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
        } else {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['activo_fijo_general'] == 1) {

                $rspta = $all_activos_fijos->mostrar_pagos($idpago_af_general);
                //Codificar el resultado utilizando json
                echo json_encode($rspta);
                //Fin de las validaciones de acceso
            } else {
                require 'noacceso.php';
            }
        }
    break;
    /**seccion pagos activos fijos por proyecto */
    case 'most_datos_prov_pago_af_p':
        if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
        } else {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['activo_fijo_general'] == 1) {

                $rspta = $ctivos_fijos_proy->most_datos_prov_pago($_POST["idcompra_af_proyecto"]);
                //Codificar el resultado utilizando json
                echo json_encode($rspta);
                //Fin de las validaciones de acceso
            } else {
                require 'noacceso.php';
            }
        }
    break;
    case 'listar_pagos_af_p':
        if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
        } else {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['activo_fijo_general'] == 1) {

                $rspta = $ctivos_fijos_proy->listar_pagos_af_p($_GET["idcompra_af_proyecto"]);
                $data = [];
                $suma = 0;
                $imagen = '';

                while ($reg = $rspta->fetch_object()) {
                    $suma = $suma + $reg->monto;
                    if (strlen($reg->descripcion) >= 20) {
                        $descripcion = substr($reg->descripcion, 0, 20) . '...';
                    } else {
                        $descripcion = $reg->descripcion;
                    }
                    if (strlen($reg->titular_cuenta) >= 20) {
                        $titular_cuenta = substr($reg->titular_cuenta, 0, 20) . '...';
                    } else {
                        $titular_cuenta = $reg->titular_cuenta;
                    }
                    empty($reg->imagen)
                        ? ($imagen = '<div><center><a type="btn btn-danger" class=""><i class="far fa-times-circle fa-2x"></i></a></center></div>')
                        : ($imagen = '<div><center><a type="btn btn-danger" class=""  href="#" onclick="ver_modal_vaucher_af_p(' . "'" . $reg->imagen . "'" . ')"><i class="fas fa-file-invoice-dollar fa-2x"></i></a></center></div>');
                    $tool = '"tooltip"';
                    $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>";
                    $data[] = [
                        "0" => $reg->estado
                            ? '<button class="btn btn-warning btn-sm" onclick="mostrar_pagos_af_p(' .$reg->idpago_af_proyecto .')"><i class="fas fa-pencil-alt"></i></button>' .
                                ' <button class="btn btn-danger btn-sm" onclick="desactivar_pagos_af_p(' .$reg->idpago_af_proyecto .')"><i class="far fa-trash-alt"></i></button>'
                            : '<button class="btn btn-warning btn-sm" onclick="mostrar_pagos_af_p(' .$reg->idpago_af_proyecto .')"><i class="fa fa-pencil-alt"></i></button>' .
                             ' <button class="btn btn-primary btn-sm" onclick="activar_pagos_af_p(' .$reg->idpago_af_proyecto .')"><i class="fa fa-check"></i></button>',
                        "1" => $reg->forma_pago,
                        "2" =>'<div class="user-block">
                        <span class="username" style="margin-left: 0px !important;"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'. $reg->beneficiario.'</p></span>
                        <span class="description" style="margin-left: 0px !important;" data-toggle="tooltip" data-original-title="' . $reg->titular_cuenta . '"><b>titular: </b>' . $titular_cuenta . '</span></div>',
                        "3" => '<div class="user-block">
                        <span class="username" style="margin-left: 0px !important;"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'.$reg->banco.'</p></span>
                        <span class="description" style="margin-left: 0px !important;" data-toggle="tooltip" data-original-title="' . $reg->cuenta_destino . '"><b>C: </b>' . $reg->cuenta_destino . '</span></div>',
                        "4" => date("d/m/Y", strtotime($reg->fecha_pago)),
                        "5" => empty($reg->descripcion) ? '-' : '<div data-toggle="tooltip" data-original-title="' . $reg->descripcion . '">' . $descripcion . '</div>',
                        "6" => number_format($reg->monto, 2, '.', ','),
                        "7" => $imagen,
                        "8" => $reg->estado ? '<span class="text-center badge badge-success">Activado</span>' . $toltip : '<span class="text-center badge badge-danger">Desactivado</span>' . $toltip,
                    ];
                }
                //$suma=array_sum($rspta->fetch_object()->monto);
                $results = [
                    "sEcho" => 1, //Información para el datatables
                    "iTotalRecords" => count($data), //enviamos el total registros al datatable
                    "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
                    "data" => $data,
                    "suma" => $suma,
                ];
                echo json_encode($results);
                //Fin de las validaciones de acceso
            } else {
                require 'noacceso.php';
            }
        }
    break;
    case 'suma_total_pagos_af_p':
        $rspta = $ctivos_fijos_proy->suma_total_pagos($_POST["idcompra_af_proyecto"]);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;
    case 'guardaryeditar_pago_af_p':

        $beneficiario_pago_af_p = isset($_POST["beneficiario_pago_af_p"]) ? limpiarCadena($_POST["beneficiario_pago_af_p"]) : "";
        $forma_pago_af_p = isset($_POST["forma_pago_af_p"]) ? limpiarCadena($_POST["forma_pago_af_p"]) : "";
        $tipo_pago_af_p = isset($_POST["tipo_pago_af_p"]) ? limpiarCadena($_POST["tipo_pago_af_p"]) : "";
        $cuenta_destino_pago_af_p = isset($_POST["cuenta_destino_pago_af_p"]) ? limpiarCadena($_POST["cuenta_destino_pago_af_p"]) : "";
        $banco_pago_af_p = isset($_POST["banco_pago_af_p"]) ? limpiarCadena($_POST["banco_pago_af_p"]) : "";
        $titular_cuenta_pago_af_p = isset($_POST["titular_cuenta_pago_af_p"]) ? limpiarCadena($_POST["titular_cuenta_pago_af_p"]) : "";
        $fecha_pago_af_p = isset($_POST["fecha_pago_af_p"]) ? limpiarCadena($_POST["fecha_pago_af_p"]) : "";
        $monto_pago_af_p = isset($_POST["monto_pago_af_p"]) ? limpiarCadena($_POST["monto_pago_af_p"]) : "";
        $numero_op_pago_af_p = isset($_POST["numero_op_pago_af_p"]) ? limpiarCadena($_POST["numero_op_pago_af_p"]) : "";
        $descripcion_pago_af_p = isset($_POST["descripcion_pago_af_p"]) ? limpiarCadena($_POST["descripcion_pago_af_p"]) : "";
        $idcompra_af_proyecto_p = isset($_POST["idcompra_af_proyecto"]) ? limpiarCadena($_POST["idcompra_af_proyecto"]) : "";
        
        $idpago_af_proyecto = isset($_POST["idpago_af_proyecto"]) ? limpiarCadena($_POST["idpago_af_proyecto"]) : "";
        
        $idproveedor_pago = isset($_POST["idproveedor_pago_af_p"]) ? limpiarCadena($_POST["idproveedor_pago_af_p"]) : "";

        $comrobante_af_p = isset($_POST["foto11"]) ? limpiarCadena($_POST["foto11"]) : "";

       // $idpago_af_proyecto,$idcompra_af_proyecto_p,$beneficiario_pago_af_p,$forma_pago_af_p,$tipo_pago_af_p,$cuenta_destino_pago_af_p,
        //$banco_pago_af_p,$titular_cuenta_pago_af_p,$fecha_pago_af_p,$monto_pago_af_p,$numero_op_pago_af_p,$descripcion_pago_af_p,$comrobante_af_p

        if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
        } else {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['activo_fijo_general'] == 1) {
                // imgen de perfil
                if (!file_exists($_FILES['foto11']['tmp_name']) || !is_uploaded_file($_FILES['foto11']['tmp_name'])) {
                    $comrobante_af_p = $_POST["foto11_actual"];
                    $flat_img1 = false;
                } else {
                    $ext1 = explode(".", $_FILES["foto11"]["name"]);
                    $flat_img1 = true;

                    $comrobante_af_p = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

                    move_uploaded_file($_FILES["foto11"]["tmp_name"], "../dist/docs/activos_fijos_proyecto/comprobantes_pagos_activos_fijos_p/" . $comrobante_af_p);
                }

                if (empty($idpago_af_proyecto)) {
                    //$idpago_af_proyecto,$idcompra_af_proyecto_p,$descripcion_pago,$numero_op_pago,$monto_pago,$fecha_pago,$titular_cuenta_pago,$banco_pago,$cuenta_destino_pago,$tipo_pago,$forma_pago,$beneficiario_pago

                    $rspta = $ctivos_fijos_proy->insertar_pago_af_p($idcompra_af_proyecto_p,$beneficiario_pago_af_p,$forma_pago_af_p,$tipo_pago_af_p,$cuenta_destino_pago_af_p,
                    $banco_pago_af_p,$titular_cuenta_pago_af_p,$fecha_pago_af_p,$monto_pago_af_p,$numero_op_pago_af_p,$descripcion_pago_af_p,$comrobante_af_p);
                    echo $rspta ? "ok" : "No se pudieron registrar todos los datos";
                } else {
                    // validamos si existe LA IMG para eliminarlo
                    if ($flat_img1 == true) {
                        $datos_f1 = $ctivos_fijos_proy->obtenerImg($idpago_af_proyecto);

                        $img1_ant = $datos_f1->fetch_object()->imagen;

                        if ($img1_ant != "") {
                            unlink("../dist/docs/activos_fijos_proyecto/comprobantes_pagos_activos_fijos_p/" . $img1_ant);
                        }
                    }

                    $rspta = $ctivos_fijos_proy->editar_pago($idpago_af_proyecto,$idcompra_af_proyecto_p,$beneficiario_pago_af_p,$forma_pago_af_p,$tipo_pago_af_p,$cuenta_destino_pago_af_p,
                    $banco_pago_af_p,$titular_cuenta_pago_af_p,$fecha_pago_af_p,$monto_pago_af_p,$numero_op_pago_af_p,$descripcion_pago_af_p,$comrobante_af_p);

                    echo $rspta ? "ok" : "Servicio no se pudo actualizar";
                }
                //Fin de las validaciones de acceso
            } else {
                require 'noacceso.php';
            }
        }
    break;
    case 'desactivar_pagos_af_p':
        if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
        } else {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['activo_fijo_general'] == 1) {
                $rspta = $ctivos_fijos_proy->desactivar_pagos($_POST['idpago_af_proyecto']);
                echo $rspta ? "Pago Anulado" : "Pago no se puede Anular";
                //Fin de las validaciones de acceso
            } else {
                require 'noacceso.php';
            }
        }
    break;
    case 'activar_pagos_af_p':
        if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
        } else {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['activo_fijo_general'] == 1) {
                $rspta = $ctivos_fijos_proy->activar_pagos($_POST['idpago_af_proyecto']);
                echo $rspta ? "Pago Restablecido" : "Pago no se pudo Restablecido";
                //Fin de las validaciones de acceso
            } else {
                require 'noacceso.php';
            }
        }
    break;
    case 'mostrar_pagos_af_p':
        if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
        } else {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['activo_fijo_general'] == 1) {

                $rspta = $ctivos_fijos_proy->mostrar_pagos($_POST['idpago_af_proyecto']);
                //Codificar el resultado utilizando json
                echo json_encode($rspta);
                //Fin de las validaciones de acceso
            } else {
                require 'noacceso.php';
            }
        }
    break;
    
    /**
     * ==============FIN SECCION PAGOS=====
     */

    /**SECCION-ACTIVOS FIJOS POR PROYECTO*/

    case 'editarcompra_proyecto': 
        
        $idproyecto = isset($_POST["idproyecto_proy"]) ? limpiarCadena($_POST["idproyecto_proy"]) : "";
        $idcompra_af_proyecto = isset($_POST["idcompra_af_proy"]) ? limpiarCadena($_POST["idcompra_af_proy"]) : "";
        $idproveedor = isset($_POST["idproveedor_proy"]) ? limpiarCadena($_POST["idproveedor_proy"]) : "";
        $fecha_compra = isset($_POST["fecha_compra_proy"]) ? limpiarCadena($_POST["fecha_compra_proy"]) : "";
        $tipo_comprobante = isset($_POST["tipo_comprobante_proy"]) ? limpiarCadena($_POST["tipo_comprobante_proy"]) : "";
        $serie_comprobante = isset($_POST["serie_comprobante_proy"]) ? limpiarCadena($_POST["serie_comprobante_proy"]) : "";
        $descripcion = isset($_POST["descripcion_proy"]) ? limpiarCadena($_POST["descripcion_proy"]) : "";

        $subtotal_compra = isset($_POST["subtotal_compra_proy"]) ? limpiarCadena($_POST["subtotal_compra_proy"]) : "";
        $igv_compra = isset($_POST["igv_compra_proy"]) ? limpiarCadena($_POST["igv_compra_proy"]) : "";
        $total_compra_af_p = isset($_POST["total_compra_af_proy"]) ? limpiarCadena($_POST["total_compra_af_proy"]) : "";

        if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
        } else {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['activo_fijo_general'] == 1) {

                    $rspta=$ctivos_fijos_proy->editar($idcompra_af_proyecto, $idproyecto,$idproveedor, $fecha_compra,$tipo_comprobante,$serie_comprobante, 
                    $descripcion, $total_compra_af_p,$subtotal_compra,$igv_compra,$_POST["idactivos_fijos_p"],$_POST["unidad_medida_p"],
                    $_POST["nombre_color_p"],  $_POST["cantidad_p"],$_POST["precio_sin_igv_p"], $_POST["precio_igv_p"],
                    $_POST["precio_con_igv_p"], $_POST["descuento_p"],$_POST["ficha_tecnica_activo_p"]);

                    echo $rspta ? "ok" : "Compra no se pudo actualizar";

            } else {
                require 'noacceso.php';
            }
        }         
    break;
    case 'anular_af_p':
        
        if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
        } else {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['activo_fijo_general'] == 1) {
                
                $rspta = $ctivos_fijos_proy->desactivar($_POST['idcompra_af_proyecto']);

                echo $rspta ? "ok" : "Compra no se puede Anular";
                //Fin de las validaciones de acceso
            } else {
                require 'noacceso.php';
            }
        }       
    break;
    case 'des_anular_af_p':
        
        if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
        } else {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['activo_fijo_general'] == 1) {
                
                $rspta = $ctivos_fijos_proy->activar($_POST['idcompra_af_proyecto']);

                echo $rspta ? "ok" : "Compra no se puede recuperar";
                //Fin de las validaciones de acceso
            } else {
                require 'noacceso.php';
            }
        }        
    break;
    case 'ver_compra_af_p':
                        
        if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
        } else {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['activo_fijo_general'] == 1) {

                $rspta = $all_activos_fijos->ver_compra_proyecto($_POST['idcompra_proyecto']);
                //Codificar el resultado utilizando json
                echo json_encode($rspta);
                //Fin de las validaciones de acceso
            } else {
                require 'noacceso.php';
            }
        }         
    break;
    case 'ver_detalle_compras_af_p':
                
        if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
        } else {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['activo_fijo_general'] == 1) {

				$rspta  =  $all_activos_fijos->listarDetalle_proyecto($_GET['idcompra_proyecto']);
                $rspta2 =  $all_activos_fijos->ver_compra_proyecto($_GET['idcompra_proyecto']);
				$subtotal=0;
                $ficha='';
				echo '<thead style="background-color:#A9D0F5">
                                                <th>Ficha técnica</th>
                                                <th>Activo</th>
                                                <th>Cantidad</th>
                                                <th>Precio Compra</th>
                                                <th>Descuento</th>
                                                <th>Subtotal</th>
										</thead>';

				while ($reg = $rspta->fetch_object())
						{
                           $subtotal = ($reg->cantidad*$reg->precio_con_igv)-$reg->descuento;
                           
                           empty($reg->ficha_tecnica)
                           ? ($ficha = '<a ><i class="far fa-file-pdf fa-2x" style="color:#000000c4"></i></a>')
                           : ($ficha = '<a target="_blank" href="../dist/docs/activos_fijos_general/ficha_tecnica_activos_fijos/' . $reg->ficha_tecnica . '"><i class="far fa-file-pdf fa-2x" style="color:#ff0000c4"></i></a>');
							echo '<tr class="filas">
                                    <td>'.$ficha.'</td>
                                    <td>'.$reg->nombre.'</td>
                                    <td>'.$reg->cantidad.'</td>
                                    <td>'.number_format($reg->precio_con_igv, 2, '.', ',').'</td>
                                    <td>'.number_format($reg->descuento, 2, '.', ',').'</td>
                                    <td>'.number_format($subtotal, 2, '.', ',').'</td></tr>';
						}
				echo '<tfoot>
                        <td colspan="4"></td>
                        <th class="text-center">
                            <h5>Subtotal</h5>
                            <h5>IGV</h5>
                            <h5>TOTAL</h5>
                        </th>
                        <th>
                            <h5 class="text-right subtotal"  style="font-weight: bold;">S/'.number_format($rspta2['subtotal'], 2, '.', ',').'</h5>
                            <h5 class="text-right igv_comp" style="font-weight: bold;">S/'.number_format($rspta2['igv'], 2, '.', ',').'</h5>
                            <b>
                                <h4 class="text-right total"  style="font-weight: bold;">S/'.number_format($rspta2['total'], 2, '.', ',').'</h4>
                            </b>
                    </tfoot>';

            } else {
                require 'noacceso.php';
            }
        }
    break;


    case 'ver_compra_editar_af_p':
                        
        if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
        } else {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['activo_fijo_general'] == 1) {
                
                $rspta = $ctivos_fijos_proy->mostrar_compra_para_editar($_POST['idcompra_af_proyecto']);
                //Codificar el resultado utilizando json
                echo json_encode($rspta);
            } else {
                require 'noacceso.php';
            }
        }                 
    break;

    /** SECCCION AGREGAR PRODUCTO */



    case 'salir':
        //Limpiamos las variables de sesión
        session_unset();
        //Destruìmos la sesión
        session_destroy();
        //Redireccionamos al login
        header("Location: ../index.php");

    break;


}

function quitar_guion($numero){ return str_replace("-", "", $numero); }

ob_end_flush();
?>
