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
          
          $rspta = $resumen_factura->facturas_compras($_GET['id_proyecto'], $_GET['empresa_a_cargo'], $_GET['fecha_1'], $_GET['fecha_2'], $_GET['id_proveedor'], $_GET['comprobante'], $_GET['visto_bueno'], $_GET['modulo'] );
          // echo json_encode($rspta);
          //Vamos a declarar un array
          $data = []; $cont = 1;       
          
          if ($rspta['status'] == true) {
            foreach ($rspta['data']['datos'] as $key => $value) {

              $documento = (empty($value['comprobante'])) ? '<center> <button class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-original-title="Vacío" ><i class="fas fa-file-invoice fa-lg"></i></button> </center>' : '<center> <button class="btn btn-info btn-sm" onclick="modal_comprobante( \'' . $value['comprobante'] .'\', \''. $value['fecha'] .'\', \''. $value['tipo_comprobante'] .'\', \''. $value['serie_comprobante'] .'\', \''. $value['ruta'] .'\', \''. $value['carpeta'] .'\', \''. $value['subcarpeta'] . '\')" data-toggle="tooltip" data-original-title="Ver Comprobante"><i class="fas fa-file-invoice fa-lg"></i></button> </center>'  ;   
              $tipo_comprobante = '<b>'.$value['tipo_comprobante'] .'</b>'.   (empty($value['serie_comprobante']) ? '' : ' ─ ' . $value['serie_comprobante']);
              $add_remove_vb_rf = ($value['estado_user_vb_rf']) ? '\''.$value['bd_nombre_tabla'].'\', \''.$value['bd_nombre_id_tabla'] .'\', \''.$value['idtabla'] .'\', \'quitar\', \''. $tipo_comprobante .'\'' : '\''.$value['bd_nombre_tabla'].'\', \''.$value['bd_nombre_id_tabla'] .'\', \''.$value['idtabla'] .'\', \'agregar\', \''. $tipo_comprobante .'\''  ;
              $eliminar = '\''.$value['bd_nombre_tabla'].'\', \''.$value['bd_nombre_id_tabla']  .'\', \'' . $value['idtabla'] .'\', \'' . encodeCadenaHtml($tipo_comprobante) .'\'';

              if ($value['comprobante_multiple'] == true) {
                $documento = ($value['cant_comprobante'] == 0) ? '<center> <button class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-original-title="Vacío" ><i class="fas fa-file-invoice fa-lg"></i></button> </center>' : '<center> <button class="btn btn-info btn-sm" onclick="comprobante_multiple( \'' . $value['idtabla'] .'\', \''. $value['fecha'] .'\', \''. $value['tipo_comprobante'] .'\', \''. $value['serie_comprobante'] .'\', \''. $value['ruta'] .'\', \''. $value['carpeta'] .'\', \''. $value['subcarpeta'] . '\')" data-toggle="tooltip" data-original-title="'.($value['cant_comprobante']>1? $value['cant_comprobante'].'comprobantes.':'1 comprobante.').'"><i class="fas fa-file-invoice fa-lg"></i></button> </center>'  ;
              }
              // $total = $value['total'];
              $subtotal = ($value['tipo_comprobante']=='NC' ? -1*$value['subtotal'] :$value['subtotal']);
              $igv = ($value['tipo_comprobante']=='NC' ? -1*$value['igv'] :$value['igv']);
              $total = ($value['tipo_comprobante']=='NC' ? -1*$value['total'] :$value['total']);
              $data[] = [
                "0" => $cont++,
                "1" => '<div class="text-nowrap"> ' . 
                  '<button class="btn btn-info btn-sm" onclick="detalle_'.$value['carpeta'].'('.$add_remove_vb_rf.')" data-toggle="tooltip" data-original-title="Ver detalle"><i class="fas fa-eye"></i></button>'.
                  ($value['estado_user_vb_rf'] ? ' <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-original-title="Quitar visto bueno" onclick="visto_bueno(\''.$value['bd_nombre_tabla'].'\', \''.$value['bd_nombre_id_tabla'] .'\', \''.$value['idtabla'] .'\', \'quitar\', \''. $tipo_comprobante .'\')" ><i class="fas fa-times"></i></button>'  : ' <button class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-original-title="Dar visto bueno" onclick="visto_bueno(\''.$value['bd_nombre_tabla'].'\', \''.$value['bd_nombre_id_tabla'] .'\', \''.$value['idtabla'] .'\', \'agregar\', \''. $tipo_comprobante .'\')" ><i class="fas fa-check"></i></button>' ).
                  
                '<div class="btn-group ml-1">
                    <button type="button" class="btn btn-info btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown"><i class="fa-solid fa-gear"></i> </button>
                    <div class="dropdown-menu" role="menu" style="box-shadow: 0px 0rem 2rem 8px rgb(0 0 0 / 64%) !important;">                    
                      <a class="dropdown-item" href="#" onclick="eliminar_permanente('.$eliminar.')"><i class="far fa-trash-alt text-danger"></i> Eliminar permanente</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="#" onclick="ver_bitacora(\''.$value['bd_nombre_tabla'].'\', \''.$value['bd_nombre_id_tabla'] .'\', \''.$value['idtabla'] .'\', \'quitar\', \''. $tipo_comprobante .'\');"><i class="fa-solid fa-arrow-right-arrow-left text-fuchsia"></i> Ver movimientos</a>
                    </div>
                  </div>
                </div>',
                "2" =>    $value['estado_user_vb_rf'] ? 
                '<div class="user-block">
                  <img class="img-circle" src="../dist/docs/all_trabajador/perfil/'. $value['imagen_user_vb_rf'].  '" width="30" data-toggle="tooltip" data-original-title="'.$value['nombre_user_vb_rf'].'" alt="User Image" onerror="' . $imagen_error . '">
                  <div class="description pt-2">'. $value['nombre_user_vb_rf'] .'</div>
                  <div class="description "> <span class="font-size-10px text-muted">'. $value['updated_at_vb_rf']  .'</span>  </div>
                </div>' : '<i class="far fa-hand-point-left texto-parpadeante"></i>'  ,
                "3" => $value['fecha'],
                "4" => '<center>'.$value['tipo_comprobante'].'</center>',
                "5" => $value['serie_comprobante'],
                "6" => $value['ruc'],
                "7" => '<div class="w-150px recorte-text text-bold text-primary" data-toggle="tooltip" data-original-title="'. $value['proveedor'] .'">'. $value['proveedor'] .'</div>',                
                "8" => $subtotal,
                "9" => $igv,
                "10" => $total,
                "11" => $value['glosa'],
                "12" => $value['tipo_gravada'],
                "13" => $documento.$toltip,
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
          
          $rspta = $resumen_factura->suma_totales($_POST['id_proyecto'], $_POST['fecha_1'], $_POST['fecha_2'], $_POST['id_proveedor'], $_POST['comprobante'], $_POST['visto_bueno'], $_POST['modulo']);

          echo json_encode($rspta, true);

        break;

        case 'data_comprobantes':                  

          $rspta = $resumen_factura->facturas_compras($_POST['id_proyecto'], $_GET['empresa_a_cargo'], $_POST['fecha_1'], $_POST['fecha_2'], $_POST['id_proveedor'], $_POST['comprobante'], $_POST['visto_bueno'], $_POST['modulo'] );
          
          echo json_encode($rspta, true);

        break;

        case 'tbla_comprobantes_multiple_compra_insumo':          
          
          $rspta = $compra_insumo->tbla_comprobantes( $_GET["id_tabla"] );
          //Vamos a declarar un array
          $data = []; $cont = 1;        
          
          if ($rspta['status'] == true) {
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

        // ════════════════════════════════════════ VISTO BUENO ════════════════════════════════════════

        case 'visto_bueno':
          $rspta = $resumen_factura->visto_bueno($_GET['name_tabla'], $_GET['name_id_tabla'], $_GET['id_tabla'], $_GET['accion']);
          echo json_encode($rspta, true);
        break; 

        // ════════════════════════════════════════ S E C C I O N   -  D E T A L L E   M O D U L O S ════════════════════════════════════════

        
        
        case 'detalle_servicio_maquina':
          $rspta = $resumen_factura->detalle_servicio_maquina($_POST['id_tabla']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;

        case 'detalle_servicio_equipo':
          $rspta = $resumen_factura->detalle_servicio_equipo($_POST['id_tabla']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;

        case 'detalle_sub_contrato':
          $rspta = $resumen_factura->detalle_sub_contrato($_POST['id_tabla']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;

        case 'detalle_planilla_seguro':
          $rspta = $resumen_factura->detalle_planilla_seguro($_POST['id_tabla']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;

        case 'detalle_otro_gasto':
          $rspta = $resumen_factura->detalle_otro_gasto($_POST['id_tabla']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;

        case 'detalle_transporte':
          $rspta = $resumen_factura->detalle_transporte($_POST['id_tabla']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;

        case 'detalle_hospedaje':
          $rspta = $resumen_factura->detalle_hospedaje($_POST['id_tabla']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;

        case 'detalle_pension':
          $rspta = $resumen_factura->detalle_pension($_POST['id_tabla']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);
        break;

        case 'detalle_break':
          $rspta = $resumen_factura->detalle_break($_POST['id_tabla']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;

        case 'detalle_comida_extra':
          $rspta = $resumen_factura->detalle_comida_extra($_POST['id_tabla']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;

        case 'detalle_pago_administrador':
          $rspta = $resumen_factura->detalle_pago_administrador($_POST['id_tabla']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;

        case 'detalle_pago_obrero':
          $rspta = $resumen_factura->detalle_pago_obrero($_POST['id_tabla']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;

        case 'detalle_otra_factura':
          $rspta = $resumen_factura->detalle_otra_factura($_POST['id_tabla'], $_POST['name_tabla']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;
        
        // ════════════════════════════════════════ ACCIONES ════════════════════════════════════════
        case 'eliminar_comprobante':
          $rspta = $resumen_factura->eliminar_permanente($_GET['nombre_tabla'], $_GET['nombre_id_tabla'], $_GET['id_tabla']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
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
