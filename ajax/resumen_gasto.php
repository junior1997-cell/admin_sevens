<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {

    if ($_SESSION['resumen_gasto'] == 1) {
      
      require_once "../modelos/Resumen_gasto.php";
      require_once "../modelos/Compra_insumos.php";

      $resumen_gasto = new ResumenGasto();
      $compra_insumo = new Compra_insumos();

      date_default_timezone_set('America/Lima');
      $date_now = date("d-m-Y h.i.s A");

      $imagen_error = "this.src='../dist/svg/user_default.svg'";
      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';    

      switch ($_GET["op"]) {        

        // ════════════════════════════════════════ S E C C I O N   - R E S U M E N ════════════════════════════════════════

        case 'tabla_principal':
          
          $rspta = $resumen_gasto->tabla_principal($_GET['id_proyecto'], $_GET['fecha_1'], $_GET['fecha_2'], $_GET['id_proveedor'], $_GET['comprobante'], $_GET['estado_vb'] );
          // echo json_encode($rspta);
          //Vamos a declarar un array
          $data = []; $cont = 1;                 
          
          if ($rspta['status'] == true) {
            foreach ($rspta['data']['datos'] as $key => $value) {

              $documento = (empty($value['comprobante'])) ? '<center> <button class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-original-title="Vacío" ><i class="fas fa-file-invoice fa-lg"></i></button> </center>' : '<center> <button class="btn btn-info btn-sm" onclick="modal_comprobante( \'' . $value['comprobante'] .'\', \''. $value['fecha'] .'\', \''. $value['tipo_comprobante'] .'\', \''. $value['serie_comprobante'] .'\', \''. $value['ruta'] .'\', \''. $value['carpeta'] .'\', \''. $value['subcarpeta'] . '\')" data-toggle="tooltip" data-original-title="Ver Comprobante"><i class="fas fa-file-invoice fa-lg"></i></button> </center>'  ;   
              $tipo_comprobante = '<b>'.$value['tipo_comprobante'] .'</b>'.   (empty($value['serie_comprobante']) ? '' : ' ─ ' . $value['serie_comprobante']);
              $add_remove_vb = ($value['estado_user_vb']) ? '\''.$value['bd_nombre_tabla'].'\', \''.$value['bd_nombre_id_tabla'] .'\', \''.$value['idtabla'] .'\', \'quitar\', \''. $tipo_comprobante .'\'' : '\''.$value['bd_nombre_tabla'].'\', \''.$value['bd_nombre_id_tabla'] .'\', \''.$value['idtabla'] .'\', \'agregar\', \''. $tipo_comprobante .'\''  ;
              $eliminar = '\''.$value['bd_nombre_tabla'].'\', \''.$value['bd_nombre_id_tabla']  .'\', \'' . $value['idtabla'] .'\', \'' . encodeCadenaHtml($tipo_comprobante) .'\'';
              
              if ($value['comprobante_multiple'] == true) {
                $documento = ($value['cant_comprobante'] == 0) ? '<center> <button class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-original-title="Vacío" ><i class="fas fa-file-invoice fa-lg"></i></button> </center>' : '<center> <button class="btn btn-info btn-sm" onclick="comprobante_multiple( \'' . $value['idtabla'] .'\', \''. $value['fecha'] .'\', \''. $value['tipo_comprobante'] .'\', \''. $value['serie_comprobante'] .'\', \''. $value['ruta'] .'\', \''. $value['carpeta'] .'\', \''. $value['subcarpeta'] . '\')" data-toggle="tooltip" data-original-title="'.($value['cant_comprobante']>1? $value['cant_comprobante'].'comprobantes.':'1 comprobante.').'"><i class="fas fa-file-invoice fa-lg"></i></button> </center>'  ;
              }

              $data[] = [
                "0" => $cont++,
                "1" => '<div class="text-nowrap"> ' . 
                  '<button class="btn btn-info btn-sm" onclick="detalle_'.$value['carpeta'].'('.$value['idtabla'].','.$add_remove_vb.')" data-toggle="tooltip" data-original-title="Ver detalle"><i class="fas fa-eye"></i></button>'.
                  ($value['estado_user_vb'] ? ' <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-original-title="Quitar visto bueno" onclick="visto_bueno(\''.$value['bd_nombre_tabla'].'\', \''.$value['bd_nombre_id_tabla'] .'\', \''.$value['idtabla'] .'\', \'quitar\', \''. $tipo_comprobante .'\')" ><i class="fas fa-times"></i></button>'  : ' <button class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-original-title="Dar visto bueno" onclick="visto_bueno(\''.$value['bd_nombre_tabla'].'\', \''.$value['bd_nombre_id_tabla'] .'\', \''.$value['idtabla'] .'\', \'agregar\', \''. $tipo_comprobante .'\')" ><i class="fas fa-check"></i></button>' ).
                  ' <button class="btn btn-danger btn-sm" onclick="eliminar_permanente('.$eliminar.')" data-toggle="tooltip" data-original-title="Eliminar permanente"><i class="far fa-trash-alt"></i></button>'.
                '</div>' ,
                "2" => $value['estado_user_vb'] ? '<img class="img-circle" src="../dist/docs/all_trabajador/perfil/'. $value['imagen_user_vb'].  '" width="30" data-toggle="tooltip" data-original-title="'.$value['nombre_user_vb'].'" alt="User Image" onerror="' . $imagen_error . '">' : '<i class="far fa-hand-point-left texto-parpadeante"></i>'  ,
                "3" => $value['fecha'] ,
                "4" => $tipo_comprobante,
                "5" => $value['modulo'],
                "6" => $value['glosa'],
                "7" => '<span class="text-primary font-weight-bold">' . $value['proveedor'] . '</span>',                
                "8" => number_format($value['subtotal'], 2, ".", ","),
                "9" => number_format($value['igv'], 2, ".", ","),
                "10" => number_format($value['total'], 2, ".", ","),
                "11" => $documento.$toltip,
                "12" => $value['estado_user_vb'],                
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
          
          $rspta = $resumen_gasto->suma_totales($_POST['id_proyecto'], $_POST['fecha_1'], $_POST['fecha_2'], $_POST['id_proveedor'], $_POST['comprobante'], $_POST['estado_vb_suma']);

          echo json_encode($rspta, true);

        break;

        case 'data_comprobantes':                  

          $rspta = $resumen_gasto->tabla_principal($_POST['id_proyecto'], $_POST['fecha_1'], $_POST['fecha_2'], $_POST['id_proveedor'], $_POST['comprobante'], $_POST['estado_vb_zip']);
          
          echo json_encode($rspta, true);

        break;
        
        // ════════════════════════════════════════ VISTO BUENO ════════════════════════════════════════

        case 'visto_bueno':
          $rspta = $resumen_gasto->visto_bueno($_GET['name_tabla'], $_GET['name_id_tabla'], $_GET['id_tabla'], $_GET['accion']);
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

        // ════════════════════════════════════════ S E C C I O N   -  D E T A L L E   M O D U L O S ════════════════════════════════════════

        case 'detalle_compra_insumo':
          
          $rspta = $compra_insumo->ver_compra($_POST['id_tabla']);
          $rspta2 = $compra_insumo->ver_detalle_compra($_POST['id_tabla']);

          $subtotal = 0;    $ficha = ''; 

          echo '<!-- Tipo de Empresa -->
            <div class="col-lg-6">
              <div class="form-group">
                <label class="font-size-15px" for="idproveedor">Proveedor</label>
                <h5 class="form-control form-control-sm" >'.$rspta['data']['razon_social'].'</h5>
              </div>
            </div>
            <!-- fecha -->
            <div class="col-lg-3">
              <div class="form-group">
                <label class="font-size-15px" for="fecha_compra">Fecha </label>
                <span class="form-control form-control-sm"><i class="far fa-calendar-alt"></i>&nbsp;&nbsp;&nbsp;'.format_d_m_a($rspta['data']['fecha_compra']).' </span>
              </div>
            </div>
            <!-- fecha -->
            <div class="col-lg-3">
              <div class="form-group">
                <label class="font-size-15px" for="fecha_compra">Glosa </label>
                <span class="form-control form-control-sm">'.$rspta['data']['glosa'].' </span>
              </div>
            </div>
            <!-- Tipo de comprobante -->
            <div class="col-lg-3">
              <div class="form-group">
                <label class="font-size-15px" for="tipo_comprovante">Tipo Comprobante</label>
                <span  class="form-control form-control-sm"> '. ((empty($rspta['data']['tipo_comprobante'])) ? '- - -' :  $rspta['data']['tipo_comprobante'])  .' </span>
              </div>
            </div>
            <!-- serie_comprovante-->
            <div class="col-lg-2">
              <div class="form-group">
                <label class="font-size-15px" for="serie_comprovante">N° de Comprobante</label>
                <span  class="form-control form-control-sm"> '. ((empty($rspta['data']['serie_comprobante'])) ? '- - -' :  $rspta['data']['serie_comprobante']).' </span>
              </div>
            </div>
            <!-- IGV-->
            <div class="col-lg-1 " >
              <div class="form-group">
                <label class="font-size-15px" for="igv">IGV</label>
                <span class="form-control form-control-sm"> '.$rspta['data']['val_igv'].' </span>                                 
              </div>
            </div>
            <!-- Descripcion-->
            <div class="col-lg-6">
              <div class="form-group">
                <label class="font-size-15px" for="descripcion">Descripción </label> <br />
                <textarea class="form-control form-control-sm" readonly rows="1">'.((empty($rspta['data']['descripcion'])) ? '- - -' :$rspta['data']['descripcion']).'</textarea>
              </div>
          </div>';

          $tbody = ""; $cont = 1;

          while ($reg = $rspta2['data']->fetch_object()) {

            $ficha = empty($reg->ficha_tecnica) ? ( '<i class="far fa-file-pdf fa-lg text-gray-50" data-toggle="tooltip" data-original-title="Vacío"></i>') : ( '<a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg->ficha_tecnica . '" data-toggle="tooltip" data-original-title="Ver Ficha Técnica"><i class="far fa-file-pdf fa-lg text-primary"></i></a>');
            
            $img_product = '../dist/docs/material/img_perfil/'. (empty($reg->imagen) ? 'producto-sin-foto.svg' : $reg->imagen );

            $tbody .= '<tr class="filas">
              <td class="text-center p-6px">' . $cont++ . '</td>
              <td class="text-center p-6px">' . $ficha . '</td>
              <td class="text-left p-6px">
                <div class="user-block text-nowrap">
                  <img class="profile-user-img img-responsive img-circle cursor-pointer" onclick="ver_img_material(\''.$img_product.'\', \'' . encodeCadenaHtml( $reg->nombre) . '\', null)" src="'. $img_product .'" alt="user image" onerror="this.src=\'../dist/svg/404-v2.svg\';" data-toggle="tooltip" data-original-title="Ver img" >
                  <span class="username"><p class="mb-0 ">' . $reg->nombre . '</p></span>
                  <span class="description"><b>Color: </b>' . $reg->color . '</span>
                </div>
              </td>
              <td class="text-left p-6px">' . $reg->unidad_medida . '</td>
              <td class="text-center p-6px">' . $reg->cantidad . '</td>		
              <td class="text-right p-6px">' . number_format($reg->precio_sin_igv, 2, '.',',') . '</td>
              <td class="text-right p-6px">' . number_format($reg->igv, 2, '.',',') . '</td>
              <td class="text-right p-6px">' . number_format($reg->precio_con_igv, 2, '.',',') . '</td>
              <td class="text-right p-6px">' . number_format($reg->descuento, 2, '.',',') . '</td>
              <td class="text-right p-6px">' . number_format($reg->subtotal, 2, '.',',') .'</td>
            </tr>';
          }         

          echo '<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
            <table class="table table-striped table-bordered table-condensed table-hover" id="tabla_detalle_factura">
              <thead style="background-color:#ff6c046b">
                <tr class="text-center hidden">
                  <th class="p-10px">Proveedor:</th>
                  <th class="text-center p-10px" colspan="9" >'.$rspta['data']['razon_social'].'</th>
                </tr>
                <tr class="text-center hidden">                
                  <th class="text-center p-10px" colspan="2" >'.((empty($rspta['data']['tipo_comprobante'])) ? '' :  $rspta['data']['tipo_comprobante']). ' ─ ' . ((empty($rspta['data']['serie_comprobante'])) ? '' :  $rspta['data']['serie_comprobante']) .'</th>
                  <th class="p-10px">Fecha:</th>
                  <th class="text-center p-10px" colspan="3" >'.format_d_m_a($rspta['data']['fecha_compra']).'</th>
                  <th class="p-10px">Glosa:</th>
                  <th class="text-center p-10px" colspan="3" >'.$rspta['data']['glosa'].'</th>
                </tr>
                <tr class="text-center">
                  <th class="text-center p-10px" >#</th>
                  <th class="text-center p-10px">F.T.</th>
                  <th class="p-10px">Material</th>
                  <th class="p-10px">U.M.</th>
                  <th class="p-10px">Cant.</th>
                  <th class="p-10px">V/U</th>
                  <th class="p-10px">IGV</th>
                  <th class="p-10px">P/U</th>
                  <th class="p-10px">Desct.</th>
                  <th class="p-10px">Subtotal</th>
                </tr>
              </thead>
              <tbody>'.$tbody.'</tbody>          
              <tfoot>
                <tr>
                  <td class="p-0" colspan="8"></td>
                  <td class="p-0 text-right"> <h6 class="mt-1 mb-1 mr-1">'.$rspta['data']['tipo_gravada'].'</h6> </td>
                  <td class="p-0 text-right">
                    <h6 class="mt-1 mb-1 mr-1 pl-1 font-weight-bold text-nowrap formato-numero-conta"><span>S/</span>' . number_format($rspta['data']['subtotal'], 2, '.',',') . '</h6>
                  </td>
                </tr>
                <tr>
                  <td class="p-0" colspan="8"></td>
                  <td class="p-0 text-right">
                    <h6 class="mt-1 mb-1 mr-1">IGV('.( ( empty($rspta['data']['val_igv']) ? 0 : floatval($rspta['data']['val_igv']) )  * 100 ).'%)</h6>
                  </td>
                  <td class="p-0 text-right">
                    <h6 class="mt-1 mb-1 mr-1 pl-1 font-weight-bold text-nowrap formato-numero-conta"><span>S/</span>' . number_format($rspta['data']['igv'], 2, '.',',') . '</h6>
                  </td>
                </tr>
                <tr>
                  <td class="p-0" colspan="8"></td>
                  <td class="p-0 text-right"> <h5 class="mt-1 mb-1 mr-1 font-weight-bold">TOTAL</h5> </td>
                  <td class="p-0 text-right">
                    <h5 class="mt-1 mb-1 mr-1 pl-1 font-weight-bold text-nowrap formato-numero-conta"><span>S/</span>' . number_format($rspta['data']['total'], 2, '.',',') . '</h5>
                  </td>
                </tr>
              </tfoot>
            </table>
          </div> ';

        break;   
        
        case 'detalle_servicio_maquina':
          $rspta = $resumen_gasto->detalle_servicio_maquina($_POST['id_tabla']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;

        case 'detalle_servicio_equipo':
          $rspta = $resumen_gasto->detalle_servicio_equipo($_POST['id_tabla']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;

        case 'detalle_sub_contrato':
          $rspta = $resumen_gasto->detalle_sub_contrato($_POST['id_tabla']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;

        case 'detalle_planilla_seguro':
          $rspta = $resumen_gasto->detalle_planilla_seguro($_POST['id_tabla']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;

        case 'detalle_otro_gasto':
          $rspta = $resumen_gasto->detalle_otro_gasto($_POST['id_tabla']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;

        case 'detalle_transporte':
          $rspta = $resumen_gasto->detalle_transporte($_POST['id_tabla']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;

        case 'detalle_hospedaje':
          $rspta = $resumen_gasto->detalle_hospedaje($_POST['id_tabla']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;

        case 'detalle_pension':
          $rspta = $resumen_gasto->detalle_pension($_POST['id_tabla']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);
        break;

        case 'detalle_break':
          $rspta = $resumen_gasto->detalle_break($_POST['id_tabla']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;

        case 'detalle_comida_extra':
          $rspta = $resumen_gasto->detalle_comida_extra($_POST['id_tabla']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;

        case 'detalle_pago_administrador':
          $rspta = $resumen_gasto->detalle_pago_administrador($_POST['id_tabla']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;

        case 'detalle_pago_obrero':
          $rspta = $resumen_gasto->detalle_pago_obrero($_POST['id_tabla']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;
        
        // ════════════════════════════════════════ ACCIONES ════════════════════════════════════════
        case 'eliminar_comprobante':
          $rspta = $resumen_gasto->eliminar_permanente($_GET['nombre_tabla'], $_GET['nombre_id_tabla'], $_GET['id_tabla']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;

        // ════════════════════════════════════════ S E L E C T 2   -   P R O V E E D O R ════════════════════════════════════════
        case 'select2Proveedor':

          $rspta = $resumen_gasto->select_proveedores($_GET['idproyecto']);

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
    } else {
      $retorno = ['status'=>'nopermiso', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
      echo json_encode($retorno);
    }  
  } 
    
  ob_end_flush();
?>
