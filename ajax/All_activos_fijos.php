<?php
ob_start();
if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
}
require_once "../modelos/All_activos_fijos.php";

$all_activos_fijos = new All_activos_fijos();

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

//comprobante
$idcompra_af_general_comprob = isset($_POST["idcompra_af_general_comprob"]) ? limpiarCadena($_POST["idcompra_af_general_comprob"]) : "";
$doc1 = isset($_POST["doc1"]) ?$_POST["doc1"] : "";
$doc_old_1 = isset($_POST["doc_old_1"]) ? limpiarCadena($_POST["doc_old_1"]) : "";

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
                    $datos_f1 =$all_activos_fijos->obtener_comprobante_af_g($idcompra_af_general_comprob);

                    $doc1_ant = $datos_f1['comprobante'];

                    if ($doc1_ant != "") {
                        unlink("../dist/docs/activos_fijos_general/comprobantes_compra_activos_f/" . $doc1_ant);
                    }
                }

                // editamos un documento existente
                $rspta =  $all_activos_fijos->editar_comprobante_af_g($idcompra_af_general_comprob,$doc1);

                echo $rspta ? "ok" : "Documento no se pudo actualizar";
                    //Fin de las validaciones de acceso
            } else {
                require 'noacceso.php';
            }
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
                        "0" =>
                            ($reg['estado'] == '1'
                                ? '<button class="btn btn-info btn-sm" onclick="ver_compras_af_g('.$reg['idtabla'].')" data-toggle="tooltip" data-original-title="Ver detalle compra"><i class="fa fa-eye"></i></button>' .
                                    ' <button class="btn btn-warning btn-sm" onclick="editar_detalle_compras('.$reg['idtabla'].')" data-toggle="tooltip" data-original-title="Editar compra"><i class="fas fa-pencil-alt"></i></button>'.
                                    ' <button class="btn btn-danger btn-sm" onclick="anular('.$reg['idtabla'].')" data-toggle="tooltip" data-original-title="Anular Compra"><i class="far fa-trash-alt"></i></button>'
                                : '<button class="btn btn-info btn-sm" onclick="ver_compras_af_g(' .$reg['idtabla']. ')"data-toggle="tooltip" data-original-title="Ver detalle"><i class="fa fa-eye"></i></button>' . 
                                ' <button class="btn btn-success btn-sm" onclick="des_anular('.$reg['idtabla'].')" data-toggle="tooltip" data-original-title="Recuperar Compra"><i class="fas fa-check"></i></button>'),
                        "1" => date("d/m/Y", strtotime($reg['fecha_compra'])),
                        "2" => '<div class="user-block">
                                    <span class="description" style="margin-left: 0px !important;"><b>'.((empty($reg['idproyecto'])) ? 'General' : $reg['codigo_proyecto']).'</b></span>
                                    <span class="username" style="margin-left: 0px !important;"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'. $reg['razon_social'] .'</p></span>
                                    <span class="description" style="margin-left: 0px !important;"><b>Cel: </b><a class="text-body" href="tel:+51'.quitar_guion($reg['telefono']).'" data-toggle="tooltip" data-original-title="Llamar al proveedor.">'. $reg['telefono'] . '</a> </span>
                                   
                                </div>',
                        "3" => '<div class="user-block">
                                <span class="username" style="margin-left: 0px !important;"><p style="margin-bottom: 0.2rem !important"; >'.$tipo_comprobante1.'</p></span>
                                <span class="description" style="margin-left: 0px !important;">Número: '. $serie_comprobante .' </span>
                            </div>',
                        "4" => number_format($reg['total'], 2, '.', ','),
                        "5" => '<div class="text-center text-nowrap"> <button class="btn btn-' .
                                $c .
                                ' btn-xs m-t-2px" onclick="listar_pagos_af_g(' . $reg['idtabla'] . ',' . $reg['total'] .',' .floatval($reg['deposito']).')">
                                <i class="fas fa-' .  $icon . ' nav-icon"></i> ' . $nombre .'</button>'.' 
                                <button style="font-size: 14px;" class="btn btn-'.$cc.' btn-sm">'.number_format(floatval($reg['deposito']), 2, '.', ',').'</button></div>',
                        "6" => number_format($saldo, 2, '.', ','),
                        "7" => (empty($reg['idproyecto'])) ?'<center><button class="btn btn-info" onclick="comprobante_compra_af_g(' . $reg['idtabla']  .', \'' .  $reg['imagen_comprobante'] .  '\')"><i class="fas fa-file-invoice fa-lg"></i></button></center>':'<center><button class="btn btn-info" onclick="comprobante_compras(' . $reg['idtabla']  .', \'' .  $reg['imagen_comprobante'] .  '\')"><i class="fas fa-file-invoice fa-lg"></i></button></center>',
                        "8" => $reg['descripcion'],
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

                while ($reg = $rspta->fetch_object()) {
                    $data[] = [
                        "0" => '<button class="btn btn-info btn-sm" onclick="listar_facuras_proveedor_af_g(' . $reg->idproveedor . ')" data-toggle="tooltip" data-original-title="Ver detalle"><i class="fa fa-eye"></i></button>',
                        "1" => $reg->razon_social,
                        "2" => $reg->total,
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

                while ($reg = $rspta->fetch_object()) {
                    $data[] = [
                        "0" =>
                            '<center><button class="btn btn-info btn-sm" onclick="ver_compras_af_g(' .
                            $reg->idcompra_af_general .
                            ')" data-toggle="tooltip" data-original-title="Ver detalle">Ver detalle <i class="fa fa-eye"></i></button></center>',
                        "1" => date("d/m/Y", strtotime($reg->fecha_compra)),
                        "2" => $reg->tipo_comprobante,
                        "3" => $reg->serie_comprobante,
                        "4" => $reg->total,
                        "5" => $reg->descripcion,
                        "6" => $reg->estado == '1' ? '<span class="badge bg-success">Aceptado</span>' : '<span class="badge bg-danger">Anulado</span>',
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
                                    <td>'.$reg->precio_con_igv.'</td>
                                    <td>'.$reg->descuento.'</td>
                                    <td>'.$subtotal.'</td></tr>';
						}
				echo '<tfoot>
                        <td colspan="4"></td>
                        <th class="text-center">
                            <h5>Subtotal</h5>
                            <h5>IGV</h5>
                            <h5>TOTAL</h5>
                        </th>
                        <th>
                            <h5 class="text-right subtotal"  style="font-weight: bold;">S/'.$rspta2['subtotal'].'</h5>
                            <h5 class="text-right igv_comp" style="font-weight: bold;">S/'.$rspta2['igv'].'</h5>
                            <b>
                                <h4 class="text-right total"  style="font-weight: bold;">S/'.$rspta2['total'].'</h4>
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
                //contenido

                require_once "../modelos/Activos_fijos.php";

                $activos_fijos=new Activos_fijos();

                $rspta =$activos_fijos->lista_para_compras();
                //Vamos a declarar un array
                $datas = [];
                // echo json_encode($rspta);
                $img_parametro = ""; $img = ""; $imagen_error = "this.src='../dist/img/default/default_activos_fijos_empresa.png'";    $color_stock = "";   $ficha_tecnica = ""; 
                
                while ($reg = $rspta->fetch_object()) {

                    if (empty($reg->imagen)) {
                        $img='src="../dist/img/default/default_activos_fijos_empresa.png"';
                        $img_parametro="default_activos_fijos_empresa.png";
                    } else {
                        $img='src="../dist/docs/activos_fijos_general/img_activos_fijos/'.$reg->imagen.'"';
                        $img_parametro=$reg->imagen;
                    }

                    !empty($reg->ficha_tecnica)
                        ? ($ficha_tecnica = '<center><a target="_blank" href="../dist/docs/activos_fijos_general/ficha_tecnica_activos_fijos/' . $reg->ficha_tecnica . '"><i class="far fa-file-pdf fa-2x text-success"></i></a></center>')
                        : ($ficha_tecnica = '<center><span class="text-center"> <i class="far fa-times-circle fa-2x text-danger"></i></span></center>');

                    $datas[] = [
                        "0" =>
                            '<button class="btn btn-warning" onclick="agregarDetalleCompraActivos(' . $reg->idactivos_fijos  . 
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
                        "2" => $reg->beneficiario,
                        "3" => $reg->cuenta_destino,
                        "4" => $reg->banco,
                        "5" => '<div data-toggle="tooltip" data-original-title="' . $reg->titular_cuenta . '">' . $titular_cuenta . '</div>',
                        "6" => date("d/m/Y", strtotime($reg->fecha_pago)),
                        "7" => empty($reg->descripcion) ? '-' : '<div data-toggle="tooltip" data-original-title="' . $reg->descripcion . '">' . $descripcion . '</div>',
                        "8" => $reg->numero_operacion,
                        "9" => number_format($reg->monto, 2, '.', ','),
                        "10" => $imagen,
                        "11" => $reg->estado ? '<span class="text-center badge badge-success">Activado</span>' . $toltip : '<span class="text-center badge badge-danger">Desactivado</span>' . $toltip,
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

    /**
     * ==============FIN SECCION PAGOS=====
     */


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
