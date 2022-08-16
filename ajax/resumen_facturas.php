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
      require_once "../modelos/Compra_insumos.php";
      
      $resumen_factura = new Resumenfacturas();   
      $compra_insumo = new Compra_insumos();
      
      $imagen_error = "this.src='../dist/svg/user_default.svg'";
      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

      switch ($_GET["op"]) {

        case 'listar_facturas_compras':
          
          $rspta = $resumen_factura->facturas_compras($_GET['id_proyecto'], $_GET['fecha_1'], $_GET['fecha_2'], $_GET['id_proveedor'], $_GET['comprobante'] );
          // echo json_encode($rspta);
          //Vamos a declarar un array
          $data = []; $cont = 1;       
          
          if ($rspta['status'] == true) {
            foreach ($rspta['data']['datos'] as $key => $value) {

              $documento = (empty($value['comprobante'])) ? '<center> <button class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-original-title="Vacío" ><i class="fas fa-file-invoice fa-lg"></i></button> </center>' : '<center> <button class="btn btn-info btn-sm" onclick="modal_comprobante( \'' . $value['comprobante'] .'\', \''. $value['fecha'] .'\', \''. $value['tipo_comprobante'] .'\', \''. $value['serie_comprobante'] .'\', \''. $value['ruta'] .'\', \''. $value['carpeta'] .'\', \''. $value['subcarpeta'] . '\')" data-toggle="tooltip" data-original-title="Ver Comprobante"><i class="fas fa-file-invoice fa-lg"></i></button> </center>'  ;   
              
              if ($value['comprobante_multiple'] == true) {
                $documento = ($value['cant_comprobante'] == 0) ? '<center> <button class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-original-title="Vacío" ><i class="fas fa-file-invoice fa-lg"></i></button> </center>' : '<center> <button class="btn btn-info btn-sm" onclick="comprobante_multiple( \'' . $value['idtabla'] .'\', \''. $value['fecha'] .'\', \''. $value['tipo_comprobante'] .'\', \''. $value['serie_comprobante'] .'\', \''. $value['ruta'] .'\', \''. $value['carpeta'] .'\', \''. $value['subcarpeta'] . '\')" data-toggle="tooltip" data-original-title="'.($value['cant_comprobante']>1? $value['cant_comprobante'].'comprobantes.':'1 comprobante.').'"><i class="fas fa-file-invoice fa-lg"></i></button> </center>'  ;
              }

              $data[] = [
                "0" => $cont++,
                "1" => $value['fecha'],
                "2" => '<center>'.$value['tipo_comprobante'].'</center>',
                "3" => $value['serie_comprobante'],
                "4" => $value['ruc'],
                "5" => '<span class="text-primary font-weight-bold">' . $value['proveedor'] . '</span>',                
                "6" => number_format($value['subtotal'], 2, ".", ","),
                "7" => number_format($value['igv'], 2, ".", ","),
                "8" => number_format($value['total'], 2, ".", ",") ,
                "9" => $value['glosa'],
                "10" => $value['tipo_gravada'],
                "11" => $documento.$toltip,
                "12" => $value['modulo'],
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

        case 'tbla_comprobantes_multiple_compra_insumo':          
          
          $rspta = $compra_insumo->tbla_comprobantes( $_GET["id_tabla"] );
          //Vamos a declarar un array
          $data = []; $cont = 1;        
          
          if ($rspta['status']) {
            while ($reg = $rspta['data']->fetch_object()) {
              $data[] = [
                "0" => $cont,
                "1" => '<div class="text-nowrap">'.                
                ' <a class="btn btn-info btn-sm " href="../dist/docs/compra_insumo/comprobante_compra/'.$reg->comprobante.'"  download="'.$reg->tipo_comprobante.removeSpecialChar((empty($reg->serie_comprobante) ?  " " :  ' ─ '.$reg->serie_comprobante).' ─ '.$reg->razon_social).' ─ '. format_d_m_a($reg->fecha_compra).'" data-toggle="tooltip" data-original-title="Descargar" ><i class="fas fa-cloud-download-alt"></i></a>' .           
                '</div>'.$toltip,
                "2" => '<a class="btn btn-info btn-sm" href="../dist/docs/compra_insumo/comprobante_compra/'.$reg->comprobante.'" target="_blank" rel="noopener noreferrer"><i class="fas fa-receipt"></i></a>' ,
                "3" => $reg->updated_at,
              ];
              $cont++;
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

        // ════════════════════════════════════════ S E L E C T 2   -   P R O V E E D O R ════════════════════════════════════════
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

        default: 
          $rspta = ['status'=>'error_code', 'message'=>'Te has confundido en escribir en el <b>swich.</b>', 'data'=>[]]; echo json_encode($rspta, true); 
        break;

      }
      
      //Fin de las validaciones de acceso
    } else {
      $retorno = ['status'=>'nopermiso', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
      echo json_encode($retorno);
    }
  }

  ob_end_flush();
?>
