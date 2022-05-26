<?php
  ob_start();

  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {
    //Validamos el acceso solo al usuario logueado y autorizado.
    if ($_SESSION['resumen_factura'] == 1) {    

      require_once "../modelos/Resumen_facturas.php";
      
      $resumen_factura = new Resumenfacturas();      

      switch ($_GET["op"]) {

        case 'listar_facturas_compras':
          
          $rspta = $resumen_factura->facturas_compras($_GET['id_proyecto'], $_GET['fecha_1'], $_GET['fecha_2'], $_GET['id_proveedor'], $_GET['comprobante'] );
          // echo json_encode($rspta);
          //Vamos a declarar un array
          $data = []; $cont = 1;   
          $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';        
          
          if ($rspta['status'] == true) {
            foreach ($rspta['data']['datos'] as $key => $value) {

              $documento = (empty($value['comprobante'])) ? '<center> <button class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-original-title="Vacío" ><i class="fas fa-file-invoice fa-lg"></i></button> </center>' : '<center> <button class="btn btn-info btn-sm" onclick="modal_comprobante( \'' . $value['comprobante'] .'\', \''. $value['fecha'] .'\', \''. $value['tipo_comprobante'] .'\', \''. $value['serie_comprobante'] .'\', \''. $value['ruta'] .'\', \''. $value['carpeta'] .'\', \''. $value['subcarpeta'] . '\')" data-toggle="tooltip" data-original-title="Ver Comprobante"><i class="fas fa-file-invoice fa-lg"></i></button> </center>'  ;   
              
              $data[] = [
                "0" => $cont++,
                "1" => $value['fecha'],
                "2" => '<center>'.$value['tipo_comprobante'].'</center>',
                "3" => $value['serie_comprobante'],
                "4" => '<span class="text-primary font-weight-bold">' . $value['proveedor'] . '</span>',
                "5" => number_format($value['total'], 2, ".", ",") ,
                "6" => number_format($value['subtotal'], 2, ".", ","),
                "7" => number_format($value['igv'], 2, ".", ","),
                "8" => $value['glosa'],
                "9" => $value['tipo_gravada'],
                "10" => $documento.$toltip,
                "11" => $value['modulo'],
              ];
            }
  
            $results = [
              "sEcho" => 1, //Información para el datatables
              "iTotalRecords" => count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
              "aaData" => $data,
            ];
            echo json_encode($results, true);
          } else {
            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          }

        break;

        case 'suma_totales':
          
          $rspta = $resumen_factura->suma_totales($_POST['id_proyecto'], $_POST['fecha_1'], $_POST['fecha_2'], $_POST['id_proveedor'], $_POST['comprobante']);

          echo json_encode($rspta, true);

        break;

        case 'data_comprobantes':                  

          $rspta = $resumen_factura->facturas_compras($_POST['id_proyecto'], $_POST['fecha_1'], $_POST['fecha_2'], $_POST['id_proveedor'], $_POST['comprobante'] );
          
          echo json_encode($rspta, true);

        break;

        // Select2 - Proveedores
        case 'select2Proveedor':

          $rspta = $resumen_factura->select_proveedores();

          $estado = true;

          if ($rspta['status'] == true) {
            foreach ($rspta['data'] as $key => $value) {         

              if ($estado) {
                echo '<option value="0" >Todos</option>';
                $estado = false;
              }
  
              echo '<option  value=' . $value['ruc'] . '>' . $value['razon_social'] . ' - ' . $value['ruc'] . '</option>';
            }
          } else {
            return  $rspta;
          }

        break;

      }
      
      //Fin de las validaciones de acceso
    } else {
      $retorno = ['status'=>'nopermiso', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
      echo json_encode($retorno);
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
