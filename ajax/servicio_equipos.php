<?php
ob_start();
if (strlen(session_id()) < 1) {
  session_start(); //Validamos si existe o no la sesión
}
require_once "../modelos/Servicio_equipos.php";
require_once "../modelos/Fechas.php";

$servicioequipos = new ServicioEquipos();

//============SERVICIOS========================
$idservicio = isset($_POST["idservicio"]) ? limpiarCadena($_POST["idservicio"]) : "";
$idproyecto = isset($_POST["idproyecto"]) ? limpiarCadena($_POST["idproyecto"]) : "";
$maquinaria = isset($_POST["maquinaria"]) ? limpiarCadena($_POST["maquinaria"]) : "";
$fecha_inicio = isset($_POST["fecha_inicio"]) ? limpiarCadena($_POST["fecha_inicio"]) : "";
$fecha_fin = isset($_POST["fecha_fin"]) ? limpiarCadena($_POST["fecha_fin"]) : "";
$horometro_inicial = isset($_POST["horometro_inicial"]) ? limpiarCadena($_POST["horometro_inicial"]) : "";
$horometro_final = isset($_POST["horometro_final"]) ? limpiarCadena($_POST["horometro_final"]) : "";
$horas = isset($_POST["horas"]) ? limpiarCadena($_POST["horas"]) : "";
$costo_unitario = isset($_POST["costo_unitario"]) ? limpiarCadena($_POST["costo_unitario"]) : "";
$cantidad = isset($_POST["cantidad"]) ? limpiarCadena($_POST["cantidad"]) : "";
$costo_adicional = isset($_POST["costo_adicional"]) ? limpiarCadena($_POST["costo_adicional"]) : "";
$costo_parcial = isset($_POST["costo_parcial"]) ? limpiarCadena($_POST["costo_parcial"]) : "";
$unidad_m = isset($_POST["unidad_m"]) ? limpiarCadena($_POST["unidad_m"]) : "";
$dias = isset($_POST["dias"]) ? limpiarCadena($_POST["dias"]) : "";
$mes = isset($_POST["mes"]) ? limpiarCadena($_POST["mes"]) : "";
$descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
//============PAGOS========================
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
$id_maquinaria_pago = isset($_POST["id_maquinaria_pago"]) ? limpiarCadena($_POST["id_maquinaria_pago"]) : "";
$idpago_servicio = isset($_POST["idpago_servicio"]) ? limpiarCadena($_POST["idpago_servicio"]) : "";
$idproyecto_pago = isset($_POST["idproyecto_pago"]) ? limpiarCadena($_POST["idproyecto_pago"]) : "";

$imagen1 = isset($_POST["doc1"]) ? limpiarCadena($_POST["doc1"]) : "";
//============factura========================
$idproyectof = isset($_POST["idproyectof"]) ? limpiarCadena($_POST["idproyectof"]) : "";
$idfactura = isset($_POST["idfactura"]) ? limpiarCadena($_POST["idfactura"]) : "";
$idmaquina = isset($_POST["idmaquina"]) ? limpiarCadena($_POST["idmaquina"]) : "";
$codigo = isset($_POST["codigo"]) ? limpiarCadena($_POST["codigo"]) : "";
$monto = isset($_POST["monto"]) ? limpiarCadena($_POST["monto"]) : "";
$fecha_emision = isset($_POST["fecha_emision"]) ? limpiarCadena($_POST["fecha_emision"]) : "";
$descripcion_f = isset($_POST["descripcion_f"]) ? limpiarCadena($_POST["descripcion_f"]) : "";
$subtotal = isset($_POST["subtotal"]) ? limpiarCadena($_POST["subtotal"]) : "";
$igv = isset($_POST["igv"]) ? limpiarCadena($_POST["igv"]) : "";
$nota = isset($_POST["nota"]) ? limpiarCadena($_POST["nota"]) : "";

$imagen2 = isset($_POST["doc2"]) ? limpiarCadena($_POST["doc2"]) : "";
//$idproyectof,$idmaquina,$codigo,$monto,$fecha_emision,$descripcion_f,$doc2
switch ($_GET["op"]) {
  /*=====ECCION DE SERVICIOS=========*/
  case 'guardaryeditar':
    if (!isset($_SESSION["nombre"])) {
      header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
    } else {
      //Validamos el acceso solo al usuario logueado y autorizado.
      if ($_SESSION['servicio_equipo'] == 1) {
        $clavehash = "";

        if (empty($idservicio)) {
          $rspta = $servicioequipos->insertar(
            $idproyecto,
            $maquinaria,
            $fecha_inicio,
            $fecha_fin,
            $horometro_inicial,
            $horometro_final,
            $horas,
            $costo_unitario,
            $costo_adicional,
            $costo_parcial,
            $unidad_m,
            $dias,
            $mes,
            $descripcion,
            $cantidad
          );
          echo $rspta ? "ok" : "No se pudieron registrar todos los datos de servicio";
        } else {
          $rspta = $servicioequipos->editar(
            $idservicio,
            $idproyecto,
            $maquinaria,
            $fecha_inicio,
            $fecha_fin,
            $horometro_inicial,
            $horometro_final,
            $horas,
            $costo_unitario,
            $costo_adicional,
            $costo_parcial,
            $unidad_m,
            $dias,
            $mes,
            $descripcion,
            $cantidad
          );

          echo $rspta ? "ok" : "Servicio no se pudo actualizar";
        }
        //Fin de las validaciones de acceso
      } else {
        require 'noacceso.php';
      }
    }
  break;

  case 'desactivar':
    if (!isset($_SESSION["nombre"])) {
      header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
    } else {
      //Validamos el acceso solo al usuario logueado y autorizado.
      if ($_SESSION['servicio_equipo'] == 1) {
        $rspta = $servicioequipos->desactivar($idservicio);
        echo $rspta ? "Servicio Anulado" : "Servicio no se puede Anular";
        //Fin de las validaciones de acceso
      } else {
        require 'noacceso.php';
      }
    }
  break;

  case 'eliminar':
    if (!isset($_SESSION["nombre"])) {
      header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
    } else {
      //Validamos el acceso solo al usuario logueado y autorizado.
      if ($_SESSION['servicio_equipo'] == 1) {
        $rspta = $servicioequipos->eliminar($idservicio);
        echo $rspta ? "Servicio Eliminado" : "Servicio no se puede Eliminar";
        //Fin de las validaciones de acceso
      } else {
        require 'noacceso.php';
      }
    }
  break;

  case 'activar':
    if (!isset($_SESSION["nombre"])) {
      header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
    } else {
      //Validamos el acceso solo al usuario logueado y autorizado.
      if ($_SESSION['servicio_equipo'] == 1) {
        $rspta = $servicioequipos->activar($idservicio);
        echo $rspta ? "Servicio Restablecido" : "Servicio no se pudo Restablecido";
        //Fin de las validaciones de acceso
      } else {
        require 'noacceso.php';
      }
    }
  break;

  case 'mostrar':
    if (!isset($_SESSION["nombre"])) {
      header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
    } else {
      //Validamos el acceso solo al usuario logueado y autorizado.
      if ($_SESSION['servicio_equipo'] == 1) {
        //$idservicioo='1';
        $rspta = $servicioequipos->mostrar($idservicio);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        //Fin de las validaciones de acceso
      } else {
        require 'noacceso.php';
      }
    }
  break;

  case 'listar':
    if (!isset($_SESSION["nombre"])) {
      header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
    } else {
      //Validamos el acceso solo al usuario logueado y autorizado.
      if ($_SESSION['servicio_equipo'] == 1) {
        //$_GET["nube_idproyecto"]
        $nube_idproyecto = $_GET["nube_idproyecto"];
        $rspta = $servicioequipos->listar($nube_idproyecto);
        //Vamos a declarar un array
        setlocale(LC_MONETARY, 'en_US');
        $data = [];
        $datos = [];
        $monto = 0;
        $c = "";
        $nombre = "";
        $icon = "";
        //----
        $monto_factura = 0;
        $cc = "";
        $nombree = "";
        $icons = "";
        $cont = 1;
        //$c_parcial = 0;
        while ($reg = $rspta->fetch_object()) {
          //$parametros="'$reg->idservicio','$reg->idproyecto'";
          $rspta2 = $servicioequipos->pago_servicio($reg->idmaquinaria, $reg->idproyecto);
          $rspta3 = $servicioequipos->monto_factura($reg->idmaquinaria, $reg->idproyecto);

          empty($rspta2) ? ($saldo = 0) : ($saldo = $reg->costo_parcial - $rspta2['monto']);
          empty($rspta2['monto']) ? ($monto = "0.00") : ($monto = $rspta2['monto']);

          empty($rspta3) ? ($saldo_factura = 0) : ($saldo_factura = $reg->costo_parcial - $rspta3['monto_factura']);
          empty($rspta3['monto_factura']) ? ($monto_factura = "0.00") : ($monto_factura = $rspta3['monto_factura']);
          //empty($rspta2['monto']?($monto="0.00"?$clase="dangar":$clase="warning"): ($monto = $rspta2['monto'] ? 'verdadero2' : 'falso');
          //$c_parcial = number_format($reg->costo_parcial, 2, '.', ',');
          if ($saldo == $reg->costo_parcial) {
            $estado = '<span class="text-center badge badge-danger">Sin pagar</span>';
            $c = "danger";
            $nombre = "Pagar";
            $icon = "dollar-sign";
          } else {
            if ($saldo < $reg->costo_parcial && $saldo > "0") {
              $estado = '<span class="text-center badge badge-warning">En proceso</span>';
              $c = "warning";
              $nombre = "Pagar";
              $icon = "dollar-sign";
            } else {
              if ($saldo <= "0" || $saldo == "0") {
                $estado = '<span class="text-center badge badge-success">Pagado</span>';
                $c = "success";
                $nombre = "Ver";
                $info = "success";
                $icon = "eye";
              } else {
                $estado = '<span class="text-center badge badge-success">Error</span>';
              }
              //$estado = '<span class="text-center badge badge-success">Terminado</span>';
            }
          }

          if ($saldo_factura == $reg->costo_parcial) {
            $cc = "danger";
          } else {
            if ($saldo_factura < $reg->costo_parcial && $saldo_factura > "0") {
              $cc = "warning";
            } else {
              if ($saldo_factura <= "0") {
                $cc = "success";
                $info = "success";
                $icons = "eye";
              }
            }
          }
          $unidad_medida = "'$reg->idmaquinaria','$reg->idproyecto','$reg->unidad_medida'";
          $verdatos = "'$reg->idmaquinaria','$reg->idproyecto','$reg->costo_parcial','$monto'";

          $data[] = [
            "0" => $cont++,
            "1" => ' <button class="btn btn-info btn-sm" onclick="listar_detalle(' . $unidad_medida . ')"><i class="far fa-eye"></i></button>',
            "2" =>'<div class="user-block">
                    <span class="username" style="margin-left: 0px !important;"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >' .$reg->maquina .'</p></span>
                    <span class="description" style="margin-left: 0px !important;">' .$reg->codigo_maquina .' </span>
                  </div>',
            "3" => $reg->razon_social,
            "4" => $reg->unidad_medida,
            "5" => $reg->cantidad_veces,
            "6" =>'S/. '. number_format($reg->costo_parcial, 2, '.', ','),
            "7" =>
              '<div class="text-center text-nowrap"> <button class="btn btn-' .
              $c .
              ' btn-xs" onclick="listar_pagos(' .
              $verdatos .
              ')"><i class="fas fa-' .
              $icon .
              ' nav-icon"></i> ' .
              $nombre .
              '</button> ' .
              '
						 <button style="font-size: 14px;" class="btn btn-' .
              $c .
              ' btn-xs">' .
              number_format($monto, 2, '.', ',') .
              '</button> </div>',
            "8" => number_format($saldo, 2, '.', ','),
            "9" =>
              '<div class="text-center text-nowrap"> <button class="btn btn-' .
              $cc .
              ' btn-sm" onclick="listar_facturas(' .
              $unidad_medida .
              ')"><i class="fas fa-file-invoice fa-lg btn-' .
              $cc .
              ' nav-icon"></i></button> ' .
              '
						 <button style="font-size: 14px;" class="btn btn-' .
              $cc .
              ' btn-sm">' .
              number_format($monto_factura, 2, '.', ',') .
              '</button> </div>',

            "10" => $estado,
          ];
          // "8"=>'<center> <button class="btn btn-info" onclick="listar_facturas('.$unidad_medida.')"><i class="fas fa-file-invoice fa-lg"></i></button> </center>',
        }
        $results = [
          "sEcho" => 1, //Información para el datatables
          "iTotalRecords" => count($data), //enviamos el total registros al datatable
          "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
          "data" => $data,
        ];
        echo json_encode($results);
        //Fin de las validaciones de acceso
      } else {
        require 'noacceso.php';
      }
    }
  break;

  case 'ver_detalle_maquina':
    if (!isset($_SESSION["nombre"])) {
      header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
    } else {
      //Validamos el acceso solo al usuario logueado y autorizado.
      if ($_SESSION['trabajador'] == 1) {
        $idmaquinaria = $_GET["idmaquinaria"];
        $idproyecto = $_GET["idproyecto"];
        /*$idmaquinaria='1';
         $idproyecto='1';*/
        $rspta = $servicioequipos->ver_detalle_m($idmaquinaria, $idproyecto);
        $fecha_entreg = '';
        $fecha_recoj = '';
        $fecha = '';
        //Vamos a declarar un array
        $data = [];
        $cont = 1;

        while ($reg = $rspta->fetch_object()) {
          //empty($fecha_recojo)?setlocale(LC_ALL,"es_ES").''.date('l d-m-Y', strtotime($reg->fecha_entrega)):$reg->fecha_entrega.'/'.$reg->fecha_recojo,
          if (empty($reg->fecha_recojo) || $reg->fecha_recojo == '0000-00-00') {
            $fecha_entreg = nombre_dia_semana($reg->fecha_entrega);

            $fecha = '<b class="text-primary">' . $fecha_entreg . ', ' . format_d_m_a($reg->fecha_entrega) . '</b>';
          } else {
            $fecha_entreg = nombre_dia_semana($reg->fecha_entrega);

            $fecha_recoj = nombre_dia_semana($reg->fecha_recojo);

            $fecha = '<b class="text-primary">' . $fecha_entreg . ', ' . format_d_m_a($reg->fecha_entrega) . ' </b> / <br> <b  class="text-danger"> ' . $fecha_recoj . ', ' . format_d_m_a($reg->fecha_recojo) . '<b>';
          }

          $tool = '"tooltip"';
          $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>";

          $data[] = [
            "0" => $cont++,
            "1" => $reg->estado
              ? '<button class="btn btn-warning btn-sm" onclick="mostrar(' .
                $reg->idservicio .
                ',' .
                $reg->idmaquinaria .
                ')"><i class="fas fa-pencil-alt"></i></button>' .
                ' <button class="btn btn-danger btn-sm" onclick="eliminar(' .
                $reg->idservicio .
                ',' .
                $reg->idmaquinaria .
                ')"><i class="fas fa-skull-crossbones"></i></button>'
              : '<button class="btn btn-warning btn-sm" onclick="mostrar(' .
                $reg->idservicio .
                ',' .
                $reg->idmaquinaria .
                ')"><i class="fa fa-pencil-alt"></i></button>' .
                ' <button class="btn btn-primary btn-sm" onclick="activar(' .
                $reg->idservicio .
                ',' .
                $reg->idmaquinaria .
                ')"><i class="fa fa-check"></i></button>',
            "2" => $fecha,
            "3" => empty($reg->horometro_inicial) || $reg->horometro_inicial == '0.00' ? '-' : $reg->horometro_inicial,
            "4" => empty($reg->horometro_final) || $reg->horometro_final == '0.00' ? '-' : $reg->horometro_final,
            "5" => empty($reg->horas) || $reg->horas == '0.00' ? '-' : $reg->horas,
            "6" => empty($reg->costo_unitario) || $reg->costo_unitario == '0.00' ?  'S/. 0.00 ' :'S/. '. number_format($reg->costo_unitario, 2, '.', ','),
            "7" => empty($reg->unidad_medida) ? '-' : $reg->unidad_medida,
            "8" => empty($reg->cantidad) ? '-' : $reg->cantidad,
            "9" => empty($reg->costo_parcial) ? 'S/. 0.00' : 'S/. '. number_format($reg->costo_parcial, 2, '.', ','),
            "10" => empty($reg->descripcion) ? '-' : '<textarea cols="30" rows="1" class="textarea_datatable" readonly >' . $reg->descripcion . '</textarea>',
            "11" => $reg->estado ? '<span class="text-center badge badge-success">Activado</span>' . $toltip : '<span class="text-center badge badge-danger">Desactivado</span>' . $toltip,
          ];
        }
        $results = [
          "sEcho" => 1, //Información para el datatables
          "iTotalRecords" => count($data), //enviamos el total registros al datatable
          "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
          "data" => $data,
        ];
        echo json_encode($results);
        //Fin de las validaciones de acceso
      } else {
        require 'noacceso.php';
      }
    }
  break;

  case 'suma_horas_costoparcial':
    $idmaquinaria = $_POST["idmaquinaria"];
    $idproyecto = $_POST["idproyecto"];
    //$idmaquinaria='1';
    //$idproyecto='1';

    $rspta = $servicioequipos->suma_horas_costoparcial($idmaquinaria, $idproyecto);
    //Codificar el resultado utilizando json
    echo json_encode($rspta);
    //Fin de las validaciones de acceso

  break;

  case 'select2_servicio':
    $rspta = $servicioequipos->select2_servicio();

    while ($reg = $rspta->fetch_object()) {
      echo '<option value=' . $reg->idmaquinaria . '>' . $reg->nombre . ' : ' . $reg->codigo_maquina . ' ---> ' . $reg->nombre_proveedor . '</option>';
    }
  break;

  /**
   * ========SECCION PAGOS===================
   */
  case 'most_datos_prov_pago':
    if (!isset($_SESSION["nombre"])) {
      header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
    } else {
      //Validamos el acceso solo al usuario logueado y autorizado.
      if ($_SESSION['servicio_equipo'] == 1) {
        //$idservicioo='1';
        $idmaquinaria = $_POST["idmaquinaria"];
        $rspta = $servicioequipos->most_datos_prov_pago($idmaquinaria);
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
      if ($_SESSION['servicio_equipo'] == 1) {
        // imgen de perfil
        if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {
          $imagen1 = $_POST["doc_old_1"];
          $flat_img1 = false;
        } else {
          $ext1 = explode(".", $_FILES["doc1"]["name"]);
          $flat_img1 = true;

          $imagen1 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

          move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/servicio_equipo/comprobante_pago/" . $imagen1);
        }

        if (empty($idpago_servicio)) {
          $rspta = $servicioequipos->insertar_pago(
            $idproyecto_pago,
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
            $id_maquinaria_pago,
            $imagen1
          );
          echo $rspta ? "ok" : "No se pudieron registrar todos los datos de servicio";
        } else {
          // validamos si existe LA IMG para eliminarlo
          if ($flat_img1 == true) {
            $datos_f1 = $servicioequipos->obtenerImg($idpago_servicio);

            $img1_ant = $datos_f1->fetch_object()->imagen;

            if ($img1_ant != "") {
              unlink("../dist/docs/servicio_equipo/comprobante_pago/" . $img1_ant);
            }
          }

          $rspta = $servicioequipos->editar_pago(
            $idpago_servicio,
            $idproyecto_pago,
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
            $id_maquinaria_pago,
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
      if ($_SESSION['servicio_equipo'] == 1) {
        $rspta = $servicioequipos->desactivar_pagos($idpago_servicio);
        echo $rspta ? "Servicio Anulado" : "Servicio no se puede Anular";
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
      if ($_SESSION['servicio_equipo'] == 1) {
        $rspta = $servicioequipos->activar_pagos($idpago_servicio);
        echo $rspta ? "Servicio Restablecido" : "Servicio no se pudo Restablecido";
        //Fin de las validaciones de acceso
      } else {
        require 'noacceso.php';
      }
    }
  break;

  case 'eliminar_pagos':
    if (!isset($_SESSION["nombre"])) {
      header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
    } else {
      //Validamos el acceso solo al usuario logueado y autorizado.
      if ($_SESSION['servicio_equipo'] == 1) {
        $rspta = $servicioequipos->eliminar_pagos($idpago_servicio);
        echo $rspta ? "Servicio Eliminado" : "Servicio no se puede Eliminar";
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
      if ($_SESSION['servicio_equipo'] == 1) {
        //$_GET["nube_idproyecto"]
        $idmaquinaria = $_GET["idmaquinaria"];
        $idproyecto = $_GET["idproyecto"];
        $tipopago = 'Proveedor';
        $rspta = $servicioequipos->listar_pagos($idmaquinaria, $idproyecto, $tipopago);
        //Vamos a declarar un array
        //$banco='';
        $data = [];
        $suma = 0;
        $imagen = '';

        $cont = 1;
        while ($reg = $rspta->fetch_object()) {
          $suma = $suma + $reg->monto;
          
          empty($reg->imagen)
            ? ($imagen = '<div><center><a type="btn btn-danger" class=""><i class="fas fa-file-invoice-dollar fa-2x text-gray-50"></i></a></center></div>')
            : ($imagen = '<div><center><a type="btn btn-danger" class=""  href="#" onclick="ver_modal_vaucher(' . "'" . $reg->imagen . "'" . ')"><i class="fas fa-file-invoice-dollar fa-2x"></i></a></center></div>');
          $tool = '"tooltip"';
          $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>";
          $data[] = [
            "0" => $cont++,
            "1" => $reg->estado
              ? '<button class="btn btn-warning btn-sm" onclick="mostrar_pagos(' .$reg->idpago_servicio .',' .$reg->id_maquinaria .')"><i class="fas fa-pencil-alt"></i></button>' .
                ' <button class="btn btn-danger btn-sm" onclick="eliminar_pagos(' .$reg->idpago_servicio .',' .$reg->id_maquinaria .')"><i class="fas fa-skull-crossbones"></i></button>'
              : '<button class="btn btn-warning btn-sm" onclick="mostrar_pagos(' .$reg->idpago_servicio .',' .$reg->id_maquinaria .')"><i class="fa fa-pencil-alt"></i></button>' .
                ' <button class="btn btn-primary btn-sm" onclick="activar_pagos(' .$reg->idpago_servicio .',' .$reg->id_maquinaria .')"><i class="fa fa-check"></i></button>',
            "2" => $reg->forma_pago,
            "3" => '<div class="user-block">
              <span class="username ml-0"><p class="text-primary m-b-02rem" >'. $reg->beneficiario .'</p></span>
              <span class="description ml-0"><b>'. $reg->banco .'</b>: '. $reg->cuenta_destino .' </span>
              <span class="description ml-0"><b>Titular: </b>: '. $reg->titular_cuenta .' </span>            
            </div>',
            "4" => date("d/m/Y", strtotime($reg->fecha_pago)),
            "5" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.(empty($reg->descripcion) ? '- - -' : $reg->descripcion ).'</textarea>',
            "6" => $reg->numero_operacion,
            "7" =>'S/. '. number_format($reg->monto, 2, '.', ','),
            "8" => $imagen,
            "9" => $reg->estado ? '<span class="text-center badge badge-success">Activado</span>' . $toltip : '<span class="text-center badge badge-danger">Desactivado</span>' . $toltip,
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

  case 'listar_pagos_detraccion':
    if (!isset($_SESSION["nombre"])) {
      header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
    } else {
      //Validamos el acceso solo al usuario logueado y autorizado.
      if ($_SESSION['servicio_equipo'] == 1) {
        //$_GET["nube_idproyecto"]
        $idmaquinaria = $_GET["idmaquinaria"];
        $idproyecto = $_GET["idproyecto"];
        $tipopago = 'Detraccion';
        //$idmaquinaria ='3';
        //$idproyecto ='2';
        $rspta = $servicioequipos->listar_pagos($idmaquinaria, $idproyecto, $tipopago);
        //Vamos a declarar un array
        //$banco='';
        $data = [];
        $suma = 0;
        $imagen = '';
        $cont = 1;

        while ($reg = $rspta->fetch_object()) {
          $suma = $suma + $reg->monto;
          
          empty($reg->imagen)
            ? ($imagen = '<div><center><a type="btn btn-danger" class=""><i class="fas fa-file-invoice-dollar fa-2x text-gray-50"></i></a></center></div>')
            : ($imagen = '<div><center><a type="btn btn-danger" class=""  href="#" onclick="ver_modal_vaucher(' . "'" . $reg->imagen . "'" . ')"><i class="fas fa-file-invoice-dollar fa-2x"></i></a></center></div>');
          $tool = '"tooltip"';
          $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>";
          $data[] = [
            "0" => $cont++,
            "1" => $reg->estado
              ? '<button class="btn btn-warning btn-sm" onclick="mostrar_pagos(' .
                $reg->idpago_servicio .
                ',' .
                $reg->id_maquinaria .
                ')"><i class="fas fa-pencil-alt"></i></button>' .
                ' <button class="btn btn-danger btn-sm" onclick="eliminar_pagos(' .
                $reg->idpago_servicio .
                ',' .
                $reg->id_maquinaria .
                ')"><i class="fas fa-skull-crossbones"></i></button>'
              : '<button class="btn btn-warning btn-sm" onclick="mostrar_pagos(' .
                $reg->idpago_servicio .
                ',' .
                $reg->id_maquinaria .
                ')"><i class="fa fa-pencil-alt"></i></button>' .
                ' <button class="btn btn-primary btn-sm" onclick="activar_pagos(' .
                $reg->idpago_servicio .
                ',' .
                $reg->id_maquinaria .
                ')"><i class="fa fa-check"></i></button>',
            "2" => $reg->forma_pago,
            "3" => '<div class="user-block">
              <span class="username ml-0"><p class="text-primary m-b-02rem" >'. $reg->beneficiario .'</p></span>
              <span class="description ml-0"><b>'. $reg->banco .'</b>: '. $reg->cuenta_destino .' </span>
              <span class="description ml-0"><b>Titular: </b>: '. $reg->titular_cuenta .' </span>            
            </div>',
            "4" => date("d/m/Y", strtotime($reg->fecha_pago)),
            "5" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.(empty($reg->descripcion) ? '- - -' : $reg->descripcion ).'</textarea>',
            "6" => $reg->numero_operacion,
            "7" =>'S/. '. number_format($reg->monto, 2, '.', ','),
            "8" => $imagen,
            "9" => $reg->estado ? '<span class="text-center badge badge-success">Activado</span>' . $toltip : '<span class="text-center badge badge-danger">Desactivado</span>' . $toltip,
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

  case 'suma_total_pagos_proveedor':
    $idmaquinaria = $_POST["idmaquinaria"];
    $idproyecto = $_POST["idproyecto"];
    $tipopago = 'Proveedor';
    //$idmaquinaria='1';
    //$idproyecto='1';

    $rspta = $servicioequipos->suma_total_pagos($idmaquinaria, $idproyecto, $tipopago);
    //Codificar el resultado utilizando json
    echo json_encode($rspta);
    //Fin de las validaciones de acceso

  break;

  case 'suma_total_pagos_detracc':
    $idmaquinaria = $_POST["idmaquinaria"];
    $idproyecto = $_POST["idproyecto"];
    $tipopago = 'Detraccion';
    //$idmaquinaria='1';
    //$idproyecto='1';

    $rspta = $servicioequipos->suma_total_pagos($idmaquinaria, $idproyecto, $tipopago);
    //Codificar el resultado utilizando json
    echo json_encode($rspta);
    //Fin de las validaciones de acceso

  break;

  case 'total_costo_parcial_pago':
    $idmaquinaria = $_POST["idmaquinaria"];
    $idproyecto = $_POST["idproyecto"];
    //$idmaquinaria='1';
    //$idproyecto='2';

    $rspta = $servicioequipos->total_costo_parcial_pago($idmaquinaria, $idproyecto);
    //Codificar el resultado utilizando json
    echo json_encode($rspta);
    //Fin de las validaciones de acceso

  break;

  case 'mostrar_pagos':
    if (!isset($_SESSION["nombre"])) {
      header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
    } else {
      //Validamos el acceso solo al usuario logueado y autorizado.
      if ($_SESSION['servicio_equipo'] == 1) {
        //$idpago_servicio='1';
        $rspta = $servicioequipos->mostrar_pagos($idpago_servicio);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        //Fin de las validaciones de acceso
      } else {
        require 'noacceso.php';
      }
    }
  break;

  /**
   * ========SECCION FACTURAS===================
   */
  case 'guardaryeditar_factura':
    if (!isset($_SESSION["nombre"])) {
      header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
    } else {
      //Validamos el acceso solo al usuario logueado y autorizado.
      if ($_SESSION['servicio_equipo'] == 1) {
        // imgen de perfil
        if (!file_exists($_FILES['doc2']['tmp_name']) || !is_uploaded_file($_FILES['doc2']['tmp_name'])) {
          $imagen2 = $_POST["doc_old_2"];
          $flat_img1 = false;
        } else {
          $ext1 = explode(".", $_FILES["doc2"]["name"]);
          $flat_img1 = true;

          $imagen2 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

          move_uploaded_file($_FILES["doc2"]["tmp_name"], "../dist/docs/servicio_equipo/comprobante_servicio/" . $imagen2);
        }

        if (empty($idfactura)) {
          $rspta = $servicioequipos->insertar_factura($idproyectof, $idmaquina, $codigo, $monto, $fecha_emision, $descripcion_f, $imagen2, $subtotal, $igv, $nota);
          echo $rspta ? "ok" : "No se pudieron registrar todos los datos de servicio";
        } else {
          // validamos si existe LA IMG para eliminarlo
          if ($flat_img1 == true) {
            $datos_f1 = $servicioequipos->obtenerImg($idfactura);

            $img1_ant = $datos_f1->fetch_object()->imagen;

            if ($img1_ant != "") {
              unlink("../dist/docs/servicio_equipo/comprobante_servicio/" . $img1_ant);
            }
          }

          $rspta = $servicioequipos->editar_factura($idfactura, $idproyectof, $idmaquina, $codigo, $monto, $fecha_emision, $descripcion_f, $imagen2, $subtotal, $igv, $nota);

          echo $rspta ? "ok" : "Servicio no se pudo actualizar";
        }
        //Fin de las validaciones de acceso
      } else {
        require 'noacceso.php';
      }
    }
  break;

  case 'listar_facturas':
    if (!isset($_SESSION["nombre"])) {
      header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
    } else {
      //Validamos el acceso solo al usuario logueado y autorizado.
      if ($_SESSION['servicio_equipo'] == 1) {
        //$_GET["nube_idproyecto"]
        $idmaquinaria = $_GET["idmaquinaria"];
        $idproyecto = $_GET["idproyecto"];
        //$idmaquinaria ='3';
        //$idproyecto ='2';
        $rspta = $servicioequipos->listar_facturas($idmaquinaria, $idproyecto);
        //Vamos a declarar un array
        //$banco='';
        $data = [];
        $suma = 0;
        $imagen = '';

        $cont = 1;

        while ($reg = $rspta->fetch_object()) {

          $suma = $suma + $reg->monto;
          
          empty($reg->imagen)
            ? ($imagen = '<div><center><a type="btn btn-danger" class=""><i class="fas fa-file-invoice-dollar fa-2x text-gray-50"></i></a></center></div>')
            : ($imagen = '<div><center><a type="btn btn-danger" class=""  href="#" onclick="ver_modal_factura(' . "'" . $reg->imagen . "'" . ')"><i class="fas fa-file-invoice fa-2x"></i></a></center></div>');
          $tool = '"tooltip"';

          $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>";

          $data[] = [
            "0" => $cont++,
            "1" => $reg->estado
              ? '<button class="btn btn-warning btn-sm" onclick="mostrar_factura(' .
                $reg->idfactura .
                ')"><i class="fas fa-pencil-alt"></i></button>' .
                ' <button class="btn btn-danger btn-sm" onclick="eliminar_factura(' .
                $reg->idfactura .
                ')"><i class="fas fa-skull-crossbones"></i></button>'
              : '<button class="btn btn-warning btn-sm" onclick="mostrar_factura(' .
                $reg->idfactura .
                ')"><i class="fa fa-pencil-alt"></i></button>' .
                ' <button class="btn btn-primary btn-sm" onclick="activar_factura(' .
                $reg->idfactura .
                ')"><i class="fa fa-check"></i></button>',
            "2" => $reg->codigo,
            "3" => date("d/m/Y", strtotime($reg->fecha_emision)),            
            "4" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.(empty($reg->nota) ? '- - -' : $reg->nota ).'</textarea>',
            "5" => 'S/. '.number_format($reg->subtotal, 2, '.', ','),
            "6" => 'S/. '.number_format($reg->igv, 2, '.', ','),
            "7" => 'S/. '.number_format($reg->monto, 2, '.', ','),
            "8" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.(empty($reg->descripcion) ? '- - -' : $reg->descripcion ).'</textarea>',
            "9" => $imagen,
            "10" => $reg->estado ? '<span class="text-center badge badge-success">Activado</span>' . $toltip : '<span class="text-center badge badge-danger">Desactivado</span>' . $toltip,
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

  case 'desactivar_factura':
    if (!isset($_SESSION["nombre"])) {
      header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
    } else {
      //Validamos el acceso solo al usuario logueado y autorizado.
      if ($_SESSION['servicio_equipo'] == 1) {
        $rspta = $servicioequipos->desactivar_factura($idfactura);
        echo $rspta ? "Servicio Anulado" : "Servicio no se puede Anular";
        //Fin de las validaciones de acceso
      } else {
        require 'noacceso.php';
      }
    }
  break;

  case 'activar_factura':
    if (!isset($_SESSION["nombre"])) {
      header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
    } else {
      //Validamos el acceso solo al usuario logueado y autorizado.
      if ($_SESSION['servicio_equipo'] == 1) {
        $rspta = $servicioequipos->activar_factura($idfactura);
        echo $rspta ? "Servicio Restablecido" : "Servicio no se pudo Restablecido";
        //Fin de las validaciones de acceso
      } else {
        require 'noacceso.php';
      }
    }
  break;

  case 'eliminar_factura':
    if (!isset($_SESSION["nombre"])) {
      header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
    } else {
      //Validamos el acceso solo al usuario logueado y autorizado.
      if ($_SESSION['servicio_equipo'] == 1) {
        $rspta = $servicioequipos->eliminar_factura($idfactura);
        echo $rspta ? "Servicio Aliminado" : "Servicio no se puede Aliminar";
        //Fin de las validaciones de acceso
      } else {
        require 'noacceso.php';
      }
    }
  break;

  case 'mostrar_factura':
    if (!isset($_SESSION["nombre"])) {
      header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
    } else {
      //Validamos el acceso solo al usuario logueado y autorizado.
      if ($_SESSION['servicio_equipo'] == 1) {
        //$idpago_servicio='1';
        $rspta = $servicioequipos->mostrar_factura($idfactura);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        //Fin de las validaciones de acceso
      } else {
        require 'noacceso.php';
      }
    }
  break;

  case 'total_monto_f':
    $idmaquinaria = $_POST["idmaquinaria"];
    $idproyecto = $_POST["idproyecto"];
    //$idmaquinaria='1';
    //$idproyecto='1';

    $rspta = $servicioequipos->total_monto_f($idmaquinaria, $idproyecto);
    //Codificar el resultado utilizando json
    echo json_encode($rspta);
    //Fin de las validaciones de acceso

  break;

  case 'total_costo_parcial':
    $idmaquinaria = $_POST["idmaquinaria"];
    $idproyecto = $_POST["idproyecto"];
    //$idmaquinaria='1';
    //$idproyecto='1';

    $rspta = $servicioequipos->total_costo_parcial($idmaquinaria, $idproyecto);
    //Codificar el resultado utilizando json
    echo json_encode($rspta);
    //Fin de las validaciones de acceso

  break;

  case 'select2Banco':
    $rspta = $servicioequipos->select2_banco();

    while ($reg = $rspta->fetch_object()) {
      echo '<option value=' . $reg->id . '>' . $reg->nombre . (empty($reg->alias) ? "" : " - $reg->alias") . '</option>';
    }

  break;

  case 'formato_banco':
    $rspta = $servicioequipos->formato_banco($_POST["idbanco"]);
    //Codificar el resultado utilizando json
    echo json_encode($rspta);

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

// convierte de una fecha(aa-mm-dd): 2021-12-23 a una fecha(dd-mm-aa): 23-12-2021
function format_d_m_a($fecha)
{
  $fecha_convert = "";

  if (!empty($fecha) || $fecha != '0000-00-00') {
    $fecha_expl = explode("-", $fecha);

    $fecha_convert = $fecha_expl[2] . "-" . $fecha_expl[1] . "-" . $fecha_expl[0];
  }

  return $fecha_convert;
}

// NOMBRE DIA DE SEMANA
function nombre_dia_semana($fecha)
{
  $nombre_dia_semana = "";

  if (!empty($fecha) || $fecha != '0000-00-00') {
    $fechas = new FechaEs($fecha);
    $dia = $fechas->getDDDD() . PHP_EOL;

    $nombre_dia_semana = $dia;
  }

  return $nombre_dia_semana;
}
ob_end_flush();
?>
