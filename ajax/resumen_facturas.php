<?php
  ob_start();

  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {

    header("Location: login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.

  } else {
    //Validamos el acceso solo al usuario logueado y autorizado.
    if ($_SESSION['resumen_factura'] == 1) {    

      require_once "../modelos/Resumen_facturas.php";
      
      $resumen_fact = new Resumenfacturas();

      switch ($_GET["op"]) {

        case 'listar_facturas_compras':
          
          $rspta = $resumen_fact->facturas_compras($_GET['id_proyecto']);
          $cont = 1;
          //Vamos a declarar un array
          $data = [];

          foreach ($rspta as $key => $value) {

            $btn_tipo = (empty($value['comprobante'])) ? 'btn-outline-info' : 'btn-info';       
            
            $data[] = [
              "0" => $cont++,
              "1" => format_d_m_a( $value['fecha'] ),
              "2" => '<center>'.$value['tipo_comprobante'].'</center>',
              "3" => $value['serie_comprobante'],
              "4" => '<span class="text-primary font-weight-bold">' . $value['proveedor'] . '</span>',
              "5" => number_format($value['total'], 2, ".", ",") ,
              "6" => number_format($value['subtotal'], 2, ".", ","),
              "7" => number_format($value['igv'], 2, ".", ","),
              "8" => $value['glosa'],
              "9" => $value['tipo_gravada'],
              "10" => '<center> <button class="btn '.$btn_tipo.' btn-sm" onclick="modal_comprobante( \'' . $value['comprobante'] .'\', \''. $value['fecha'] .'\', \''. $value['tipo_comprobante'] .'\', \''. $value['serie_comprobante'] .'\', \''. $value['ruta']  . '\')"><i class="fas fa-file-invoice fa-lg"></i></button> </center>',
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

        case 'suma_totales':
          
          $rspta = $resumen_fact->suma_totales($_POST['id_proyecto']);

          echo json_encode($rspta);

        break;


        case 'listar_facturas_otros_gastos':
          if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
          } else {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['resumen_activo_fijo_general'] == 1) {
              $rspta = $resumen_fact->facturas_otros_gastos($_GET['id_proyecto']);
              $cont = 1;
              //Vamos a declarar un array
              $data = [];

              //$imagen_error = "this.src='../dist/img/default/img_defecto_activo_fijo.png'";
              while ($reg = $rspta->fetch_object()) {
                $data[] = [
                  "0" => $cont++,
                  "1" => '<div> 
                        <span class="text-primary font-weight-bold">- - - </span>
                      </div>',
                  "2" => $reg->numero_comprobante,
                  "3" => $reg->fecha_o_s,
                  "4" => '<span> S/. ' . number_format($reg->subtotal, 2, ".", ",") . '</span>',
                  "5" => 'S/. ' . number_format($reg->igv, 2, ".", ","),
                  "6" => 'S/. ' . number_format($reg->costo_parcial, 2, ".", ","),
                ];
              }

              $results = [
                "sEcho" => 1, //Información para el datatables
                "iTotalRecords" => count($data), //enviamos el total registros al datatable
                "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
                "aaData" => $data,
              ];
              echo json_encode($results);
              //Fin de las validaciones de acceso
            } else {
              require 'noacceso.php';
            }
          }

        break;

        case 'total_facturas_otros_gastos':
          if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
          } else {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['resumen_activo_fijo_general'] == 1) {
              $rspta = $resumen_fact->suma_total_otros_gastos($_POST['id_proyecto']);

              echo json_encode($rspta);
              //Fin de las validaciones de acceso
            } else {
              require 'noacceso.php';
            }
          }

        break;

        case 'listar_facturas_transporte':
          if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
          } else {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['resumen_activo_fijo_general'] == 1) {
              $rspta = $resumen_fact->facturas_transporte($_GET['id_proyecto']);
              $cont = 1;
              //Vamos a declarar un array
              $data = [];

              //$imagen_error = "this.src='../dist/img/default/img_defecto_activo_fijo.png'";
              while ($reg = $rspta->fetch_object()) {
                $data[] = [
                  "0" => $cont++,
                  "1" => '<div> 
                        <span class="text-primary font-weight-bold">- - - </span>
                      </div>',
                  "2" => $reg->numero_comprobante,
                  "3" => $reg->fecha_viaje,
                  "4" => '<span> S/. ' . number_format($reg->subtotal, 2, ".", ",") . '</span>',
                  "5" => 'S/. ' . number_format($reg->igv, 2, ".", ","),
                  "6" => 'S/. ' . number_format($reg->precio_parcial, 2, ".", ","),
                ];
              }

              $results = [
                "sEcho" => 1, //Información para el datatables
                "iTotalRecords" => count($data), //enviamos el total registros al datatable
                "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
                "aaData" => $data,
              ];
              echo json_encode($results);
              //Fin de las validaciones de acceso
            } else {
              require 'noacceso.php';
            }
          }

        break;

        case 'total_facturas_transporte':
          if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
          } else {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['resumen_activo_fijo_general'] == 1) {
              $rspta = $resumen_fact->suma_total_transporte($_POST['id_proyecto']);

              echo json_encode($rspta);
              //Fin de las validaciones de acceso
            } else {
              require 'noacceso.php';
            }
          }

        break;

        case 'listar_facturas_hospedaje':
          if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
          } else {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['resumen_activo_fijo_general'] == 1) {
              $rspta = $resumen_fact->facturas_hospedaje($_GET['id_proyecto']);
              $cont = 1;
              //Vamos a declarar un array
              $data = [];

              //$imagen_error = "this.src='../dist/img/default/img_defecto_activo_fijo.png'";
              while ($reg = $rspta->fetch_object()) {
                $data[] = [
                  "0" => $cont++,
                  "1" => '<div> 
                        <span class="text-primary font-weight-bold">- - - </span>
                      </div>',
                  "2" => $reg->numero_comprobante,
                  "3" => $reg->fecha_comprobante,
                  "4" => '<span> S/. ' . number_format($reg->subtotal, 2, ".", ",") . '</span>',
                  "5" => 'S/. ' . number_format($reg->igv, 2, ".", ","),
                  "6" => 'S/. ' . number_format($reg->precio_parcial, 2, ".", ","),
                ];
              }

              $results = [
                "sEcho" => 1, //Información para el datatables
                "iTotalRecords" => count($data), //enviamos el total registros al datatable
                "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
                "aaData" => $data,
              ];
              echo json_encode($results);
              //Fin de las validaciones de acceso
            } else {
              require 'noacceso.php';
            }
          }

        break;

        case 'total_facturas_hospedaje':
          if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
          } else {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['resumen_activo_fijo_general'] == 1) {
              $rspta = $resumen_fact->suma_total_hospedaje($_POST['id_proyecto']);

              echo json_encode($rspta);
              //Fin de las validaciones de acceso
            } else {
              require 'noacceso.php';
            }
          }

        break;

        case 'listar_facturas_pension':
          if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
          } else {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['resumen_activo_fijo_general'] == 1) {
              $rspta = $resumen_fact->facturas_pension($_GET['id_proyecto']);
              $cont = 1;
              //Vamos a declarar un array
              $data = [];

              //$imagen_error = "this.src='../dist/img/default/img_defecto_activo_fijo.png'";
              while ($reg = $rspta->fetch_object()) {
                $data[] = [
                  "0" => $cont++,
                  "1" =>
                    '<div> 
                        <span class="text-primary font-weight-bold">' .
                    $reg->razon_social .
                    ' </span>
                      </div>',
                  "2" => $reg->nro_comprobante,
                  "3" => $reg->fecha_emision,
                  "4" => '<span> S/. ' . number_format($reg->subtotal, 2, ".", ",") . '</span>',
                  "5" => 'S/. ' . number_format($reg->igv, 2, ".", ","),
                  "6" => 'S/. ' . number_format($reg->monto, 2, ".", ","),
                ];
              }

              $results = [
                "sEcho" => 1, //Información para el datatables
                "iTotalRecords" => count($data), //enviamos el total registros al datatable
                "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
                "aaData" => $data,
              ];
              echo json_encode($results);
              //Fin de las validaciones de acceso
            } else {
              require 'noacceso.php';
            }
          }

        break;

        case 'total_facturas_pension':
          if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
          } else {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['resumen_activo_fijo_general'] == 1) {
              $rspta = $resumen_fact->suma_total_pension($_POST['id_proyecto']);

              echo json_encode($rspta);
              //Fin de las validaciones de acceso
            } else {
              require 'noacceso.php';
            }
          }

        break;

        case 'listar_facturas_break':
          if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
          } else {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['resumen_activo_fijo_general'] == 1) {
              $rspta = $resumen_fact->facturas_break($_GET['id_proyecto']);
              $cont = 1;
              //Vamos a declarar un array
              $data = [];

              //$imagen_error = "this.src='../dist/img/default/img_defecto_activo_fijo.png'";
              while ($reg = $rspta->fetch_object()) {
                $data[] = [
                  "0" => $cont++,
                  "1" =>
                    '<div> 
                        <span class="text-primary font-weight-bold"> Semana. ' .
                    $reg->numero_semana .
                    '</span>
                      </div>',
                  "2" => $reg->nro_comprobante,
                  "3" => $reg->fecha_emision,
                  "4" => '<span> S/. ' . number_format($reg->subtotal, 2, ".", ",") . '</span>',
                  "5" => 'S/. ' . number_format($reg->igv, 2, ".", ","),
                  "6" => 'S/. ' . number_format($reg->monto, 2, ".", ","),
                ];
              }

              $results = [
                "sEcho" => 1, //Información para el datatables
                "iTotalRecords" => count($data), //enviamos el total registros al datatable
                "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
                "aaData" => $data,
              ];
              echo json_encode($results);
              //Fin de las validaciones de acceso
            } else {
              require 'noacceso.php';
            }
          }

        break;

        case 'total_facturas_break':
          if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
          } else {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['resumen_activo_fijo_general'] == 1) {
              $rspta = $resumen_fact->suma_total_break($_POST['id_proyecto']);

              echo json_encode($rspta);
              //Fin de las validaciones de acceso
            } else {
              require 'noacceso.php';
            }
          }

        break;

        case 'listar_facturas_comidas_ex':
          if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
          } else {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['resumen_activo_fijo_general'] == 1) {
              $rspta = $resumen_fact->facturas_comida_extra($_GET['id_proyecto']);
              $cont = 1;
              //Vamos a declarar un array
              $data = [];

              //$imagen_error = "this.src='../dist/img/default/img_defecto_activo_fijo.png'";
              while ($reg = $rspta->fetch_object()) {
                $data[] = [
                  "0" => $cont++,
                  "1" => '<div> 
                        <span class="text-primary font-weight-bold">- - - </span>
                      </div>',
                  "2" => $reg->numero_comprobante,
                  "3" => $reg->fecha_comida,
                  "4" => '<span> S/. ' . number_format($reg->subtotal, 2, ".", ",") . '</span>',
                  "5" => 'S/. ' . number_format($reg->igv, 2, ".", ","),
                  "6" => 'S/. ' . number_format($reg->costo_parcial, 2, ".", ","),
                ];
              }

              $results = [
                "sEcho" => 1, //Información para el datatables
                "iTotalRecords" => count($data), //enviamos el total registros al datatable
                "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
                "aaData" => $data,
              ];
              echo json_encode($results);
              //Fin de las validaciones de acceso
            } else {
              require 'noacceso.php';
            }
          }

        break;

        case 'total_facturas_comidas_ex':
          if (!isset($_SESSION["nombre"])) {
            header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
          } else {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['resumen_activo_fijo_general'] == 1) {
              $rspta = $resumen_fact->suma_total_comida_extra($_POST['id_proyecto']);

              echo json_encode($rspta);
              //Fin de las validaciones de acceso
            } else {
              require 'noacceso.php';
            }
          }

        break;
      }
      
      //Fin de las validaciones de acceso
    } else {
      require 'noacceso.php';
    }
  }

  // convierte de una fecha(aa-mm-dd): 2021-12-23 a una fecha(dd-mm-aa): 23-12-2021
  function format_d_m_a($fecha) {

    $fecha_convert = "";

    if (!empty($fecha) || $fecha != '0000-00-00') {

      $fecha_expl = explode("-", $fecha);

      $fecha_convert = $fecha_expl[2] . "-" . $fecha_expl[1] . "-" . $fecha_expl[0];

    } 

    return $fecha_convert;
  }

  ob_end_flush();
?>
