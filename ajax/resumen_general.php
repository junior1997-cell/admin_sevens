<?php
ob_start();

if (strlen(session_id()) < 1) {
  session_start(); //Validamos si existe o no la sesión
}

// validamos los accesos al sistema
if (!isset($_SESSION["nombre"])) {
  //Validamos el acceso solo a los usuarios logueados al sistema.
  header("Location: ../vistas/login.html");

} else {

  if ($_SESSION['resumen_general'] == 1) {

    require_once "../modelos/Resumen_general.php";
    require_once "../modelos/Fechas.php";
    require_once "../modelos/Compra.php";

    $resumen_general = new Resumen_general();
    $compra = new Compra();

    switch ($_GET["op"]) {

      // TABLA
      case 'tbla_compras':

        $data = Array(); $datatable = Array();

        $deuda = $_POST['deuda'];

        $t_monto = 0;
        $t_pagos = 0;
        $t_saldo = 0;   
        $saldo_x_fila = 0;     

        $rspta = $resumen_general->tabla_compras($_POST['idproyecto'], $_POST['fecha_filtro_1'], $_POST['fecha_filtro_2'], $_POST['id_proveedor']);

        foreach ($rspta as $key => $value) {

          $saldo_x_fila = floatval($value['monto_total']) - floatval($value['monto_pago_total']);

          if ($deuda == '' || $deuda == null || $deuda == 'todos') {
            $datatable[] = array(
              '0' => $key+1, 
              '1' => $value['proveedor'],
              '2' => format_d_m_a($value['fecha_compra']),
              '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.$value['descripcion'].'</textarea>',
              '4' => '<button class="btn btn-info btn-sm" onclick="mostrar_detalle_compras('.$value['idcompra_proyecto'].')"><i class="fa fa-eye"></i></button>',
              '5' => number_format($value['monto_total'], 2, '.', ',' ),
              '6' => number_format($value['monto_pago_total'], 2, '.', ',' ),
              '7' => number_format($saldo_x_fila , 2, '.', ',' ),
            );
  
            $t_monto += floatval($value['monto_total']);
            $t_pagos += floatval($value['monto_pago_total']);
            $t_saldo += floatval($saldo_x_fila);
          } else {

            if ($deuda == 'sindeuda') {
              if ($saldo_x_fila == 0) {
                $datatable[] = array(
                  '0' => $key+1, 
                  '1' => $value['proveedor'],
                  '2' => format_d_m_a($value['fecha_compra']),
                  '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.$value['descripcion'].'</textarea>',
                  '4' => '<button class="btn btn-info btn-sm" onclick="mostrar_detalle_compras('.$value['idcompra_proyecto'].')"><i class="fa fa-eye"></i></button>',
                  '5' => number_format($value['monto_total'], 2, '.', ',' ),
                  '6' => number_format($value['monto_pago_total'], 2, '.', ',' ),
                  '7' => number_format($saldo_x_fila , 2, '.', ',' ),
                );
      
                $t_monto += floatval($value['monto_total']);
                $t_pagos += floatval($value['monto_pago_total']);
                $t_saldo += floatval($saldo_x_fila);
              }
            } else {
              if ($deuda == 'condeuda') {
                if ($saldo_x_fila > 0) {
                  $datatable[] = array(
                    '0' => $key+1, 
                    '1' => $value['proveedor'],
                    '2' => format_d_m_a($value['fecha_compra']),
                    '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.$value['descripcion'].'</textarea>',
                    '4' => '<button class="btn btn-info btn-sm" onclick="mostrar_detalle_compras('.$value['idcompra_proyecto'].')"><i class="fa fa-eye"></i></button>',
                    '5' => number_format($value['monto_total'], 2, '.', ',' ),
                    '6' => number_format($value['monto_pago_total'], 2, '.', ',' ),
                    '7' => number_format($saldo_x_fila , 2, '.', ',' ),
                  );

                  $t_monto += floatval($value['monto_total']);                  
                  $t_pagos += floatval($value['monto_pago_total']);
                  $t_saldo += floatval($saldo_x_fila);
                }
              } else {
                if ($deuda == 'conexcedente') {
                  if ($saldo_x_fila < 0) {
                    $datatable[] = array(
                      '0' => $key+1, 
                      '1' => $value['proveedor'],
                      '2' => format_d_m_a($value['fecha_compra']),
                      '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.$value['descripcion'].'</textarea>',
                      '4' => '<button class="btn btn-info btn-sm" onclick="mostrar_detalle_compras('.$value['idcompra_proyecto'].')"><i class="fa fa-eye"></i></button>',
                      '5' => number_format($value['monto_total'], 2, '.', ',' ),
                      '6' => number_format($value['monto_pago_total'], 2, '.', ',' ),
                      '7' => number_format($saldo_x_fila , 2, '.', ',' ),
                    );
  
                    $t_monto += floatval($value['monto_total']);                  
                    $t_pagos += floatval($value['monto_pago_total']);
                    $t_saldo += floatval($saldo_x_fila);
                  }
                } 
              }
            }            
          }          
        }

        $data = array(
          't_monto' => $t_monto, 
          't_pagos' => $t_pagos,
          't_saldo' => $t_saldo,
          'datatable' => $datatable
        );

        //Codificar el resultado utilizando json
        echo json_encode($data);
            
      break;       

      case 'mostrar_detalle_compras':
        
        $rspta = $compra->ver_compra($_GET['id_compra']);
        $rspta2 = $compra->ver_detalle_compra($_GET['id_compra']);

        $subtotal = 0;    $ficha = ''; 

        echo '<!-- Tipo de Empresa -->
          <div class="col-lg-6">
            <div class="form-group">
              <label class="font-size-15px" for="idproveedor">Proveedor</label>
              <h5 class="form-control form-control-sm" >'.$rspta['razon_social'].'</h5>
            </div>
          </div>
          <!-- fecha -->
          <div class="col-lg-3">
            <div class="form-group">
              <label class="font-size-15px" for="fecha_compra">Fecha </label>
              <span class="form-control form-control-sm"><i class="far fa-calendar-alt"></i>&nbsp;&nbsp;&nbsp;'.format_d_m_a($rspta['fecha_compra']).' </span>
            </div>
          </div>
          <!-- fecha -->
          <div class="col-lg-3">
            <div class="form-group">
              <label class="font-size-15px" for="fecha_compra">Glosa </label>
              <span class="form-control form-control-sm">'.$rspta['glosa'].' </span>
            </div>
          </div>
          <!-- Tipo de comprobante -->
          <div class="col-lg-3">
            <div class="form-group">
              <label class="font-size-15px" for="tipo_comprovante">Tipo Comprobante</label>
              <span  class="form-control form-control-sm"> '. ((empty($rspta['tipo_comprobante'])) ? '- - -' :  $rspta['tipo_comprobante'])  .' </span>
            </div>
          </div>
          <!-- serie_comprovante-->
          <div class="col-lg-2">
            <div class="form-group">
              <label class="font-size-15px" for="serie_comprovante">N° de Comprobante</label>
              <span  class="form-control form-control-sm"> '. ((empty($rspta['serie_comprobante'])) ? '- - -' :  $rspta['serie_comprobante']).' </span>
            </div>
          </div>
          <!-- IGV-->
          <div class="col-lg-1 " >
            <div class="form-group">
              <label class="font-size-15px" for="igv">IGV</label>
              <span class="form-control form-control-sm"> '.$rspta['val_igv'].' </span>                                 
            </div>
          </div>
          <!-- Descripcion-->
          <div class="col-lg-6">
            <div class="form-group">
              <label class="font-size-15px" for="descripcion">Descripción </label> <br />
              <textarea class="form-control form-control-sm" readonly rows="1">'.((empty($rspta['descripcion'])) ? '- - -' :$rspta['descripcion']).'</textarea>
            </div>
        </div>';

        $tbody = "";

        while ($reg = $rspta2->fetch_object()) {

          empty($reg->ficha_tecnica) ? ($ficha = '<i class="far fa-file-pdf fa-lg text-gray-50"></i>') : ($ficha = '<a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg->ficha_tecnica . '"><i class="far fa-file-pdf fa-lg text-primary"></i></a>');
          
          $tbody .= '<tr class="filas">
            <td class="text-center p-6px">' . $ficha . '</td>
            <td class="text-left p-6px">
              <div class="user-block text-nowrap">
                <img class="profile-user-img img-responsive img-circle cursor-pointer" src="../dist/docs/material/img_perfil/'.$reg->imagen.'" alt="user image" onerror="this.src=\'../dist/svg/default_producto.svg\';" >
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
          <table class="table table-striped table-bordered table-condensed table-hover">
            <thead style="background-color:#ff6c046b">
              <th class="text-center p-10px">F.T.</th>
              <th class="p-10px">Material</th>
              <th class="p-10px">U.M.</th>
              <th class="p-10px">Cant.</th>
              <th class="p-10px">V/U</th>
              <th class="p-10px">IGV</th>
              <th class="p-10px">P/U</th>
              <th class="p-10px">Desct.</th>
              <th class="p-10px">Subtotal</th>
            </thead>
            <tbody>'.$tbody.'</tbody>          
            <tfoot>
              <td colspan="7"></td>
              <th class="text-right">
                <h6>Subtotal</h6>
                <h6>IGV</h6>
                <h5 class="font-weight-bold">TOTAL</h5>
              </th>
              <th class="text-right">
                <h6 class="font-weight-bold">S/. ' . number_format($rspta['subtotal'], 2, '.',',') . '</h6>
                <h6 class="font-weight-bold">S/. ' . number_format($rspta['igv'], 2, '.',',') . '</h6>
                <h5 class="font-weight-bold">S/. ' . number_format($rspta['total'], 2, '.',',') . '</h5>
              </th>
            </tfoot>
          </table>
        </div> ';

      break;

      // TABLA
      case 'tbla_maquinaria':
         
        $tipo = '1';

        // $_POST['idproyecto'], $_POST['fecha_filtro_1'], $_POST['fecha_filtro_2'], $_POST['id_proveedor']
        // $idproyecto = 1; $fecha_filtro_1 =""; $fecha_filtro_2=""; $id_proveedor="";
        $data = Array(); $datatable = Array();

        $deuda = $_POST['deuda'];

        $t_monto = 0;
        $t_pagos = 0;
        $t_saldo = 0;   
        $saldo_x_fila = 0;

        $rspta = $resumen_general->tabla_maquinaria_y_equipo($_POST['idproyecto'], $_POST['fecha_filtro_1'], $_POST['fecha_filtro_2'], $_POST['id_proveedor'], $tipo);

        foreach ($rspta as $key => $value) {

          $saldo_x_fila = floatval($value['costo_parcial']) - floatval($value['deposito']);

          if ($deuda == '' || $deuda == null || $deuda == 'todos') {
            $datatable[] = array(
              '0' => $key+1, 
              '1' => $value['maquina'] .' - '. $value['proveedor'],
              '2' => format_d_m_a($value['fecha_entrega']),
              '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >- - -</textarea>',
              '4' => '<button class="btn btn-info btn-sm" onclick="mostrar_detalle_maquinaria_equipo('.$value['idmaquinaria'] .', \'' . $value['idproyecto']. '\', \'' .'Servicio Maquinaria:' . '\', \'' . $value['proveedor'] . '\', \'' . $value['maquina'] . '\')"><i class="fa fa-eye"></i></button>',
              '5' => number_format($value['costo_parcial'], 2, '.', ',' ),
              '6' => number_format($value['deposito'], 2, '.', ',' ),
              '7' => number_format($saldo_x_fila , 2, '.', ',' ),
            );
  
            $t_monto += floatval($value['costo_parcial']);
            $t_pagos += floatval($value['deposito']);
            $t_saldo += floatval($saldo_x_fila);
          } else {
            if ($deuda == 'sindeuda') {
              if ($saldo_x_fila == 0) {
                $datatable[] = array(
                  '0' => $key+1, 
                  '1' => $value['maquina'] .' - '. $value['proveedor'],
                  '2' => format_d_m_a($value['fecha_entrega']),
                  '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >- - -</textarea>',
                  '4' => '<button class="btn btn-info btn-sm" onclick="mostrar_detalle_maquinaria_equipo('.$value['idmaquinaria'].', \'' . $value['idproyecto'].  '\', \'' .'Servicio Maquinaria:'.  '\', \'' . $value['proveedor'] . '\', \'' . $value['maquina'] . '\')"><i class="fa fa-eye"></i></button>',
                  '5' => number_format($value['costo_parcial'], 2, '.', ',' ),
                  '6' => number_format($value['deposito'], 2, '.', ',' ),
                  '7' => number_format($saldo_x_fila , 2, '.', ',' ),
                );
      
                $t_monto += floatval($value['costo_parcial']);
                $t_pagos += floatval($value['deposito']);
                $t_saldo += floatval($saldo_x_fila);
              }
            } else {
              if ($deuda == 'condeuda') {
                if ($saldo_x_fila > 0) {
                  $datatable[] = array(
                    '0' => $key+1, 
                    '1' => $value['maquina'] .' - '. $value['proveedor'],
                    '2' => format_d_m_a($value['fecha_entrega']),
                    '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >- - -</textarea>',
                    '4' => '<button class="btn btn-info btn-sm" onclick="mostrar_detalle_maquinaria_equipo('.$value['idmaquinaria'].', \'' . $value['idproyecto'].  '\', \'' .'Servicio Maquinaria:'.  '\', \'' . $value['proveedor'] . '\', \'' . $value['maquina'] . '\')"><i class="fa fa-eye"></i></button>',
                    '5' => number_format($value['costo_parcial'], 2, '.', ',' ),
                    '6' => number_format($value['deposito'], 2, '.', ',' ),
                    '7' => number_format($saldo_x_fila , 2, '.', ',' ),
                  );
        
                  $t_monto += floatval($value['costo_parcial']);
                  $t_pagos += floatval($value['deposito']);
                  $t_saldo += floatval($saldo_x_fila);
                }
              } else {
                if ($deuda == 'conexcedente') {
                  if ($saldo_x_fila < 0) {
                    $datatable[] = array(
                      '0' => $key+1, 
                      '1' => $value['maquina'] .' - '. $value['proveedor'],
                      '2' => format_d_m_a($value['fecha_entrega']),
                      '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >- - -</textarea>',
                      '4' => '<button class="btn btn-info btn-sm" onclick="mostrar_detalle_maquinaria_equipo('.$value['idmaquinaria'].', \'' . $value['idproyecto'].  '\', \'' .'Servicio Maquinaria:'.  '\', \'' . $value['proveedor'] . '\', \'' . $value['maquina'] . '\')"><i class="fa fa-eye"></i></button>',
                      '5' => number_format($value['costo_parcial'], 2, '.', ',' ),
                      '6' => number_format($value['deposito'], 2, '.', ',' ),
                      '7' => number_format($saldo_x_fila , 2, '.', ',' ),
                    );
          
                    $t_monto += floatval($value['costo_parcial']);
                    $t_pagos += floatval($value['deposito']);
                    $t_saldo += floatval($saldo_x_fila);
                  }
                }
              }
            }            
          }          
        }

        $data = array(
          't_monto' => $t_monto, 
          't_pagos' => $t_pagos,
          't_saldo' => $t_saldo,
          'datatable' => $datatable
        );

        //Codificar el resultado utilizando json
        echo json_encode($data);
             
      break;
      
      // TABLA
      case 'tbla_equipos':

        $tipo = '2';

        $data = Array(); $datatable = Array();

        $deuda = $_POST['deuda'];

        $t_monto = 0;
        $t_pagos = 0;
        $t_saldo = 0;   
        $saldo_x_fila = 0;

        $rspta = $resumen_general->tabla_maquinaria_y_equipo($_POST['idproyecto'], $_POST['fecha_filtro_1'], $_POST['fecha_filtro_2'], $_POST['id_proveedor'], $tipo);

        foreach ($rspta as $key => $value) {

          $saldo_x_fila = floatval($value['costo_parcial']) - floatval($value['deposito']);

          if ($deuda == '' || $deuda == null || $deuda == 'todos') {
            $datatable[] = array(
              '0' => $key+1, 
              '1' => $value['maquina'] .' - '. $value['proveedor'],
              '2' => format_d_m_a($value['fecha_entrega']),
              '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >- - -</textarea>',
              '4' => '<button class="btn btn-info btn-sm" onclick="mostrar_detalle_maquinaria_equipo('.$value['idmaquinaria'].', \'' . $value['idproyecto'].  '\', \'' .'Servicio Equipo:'.  '\', \'' . $value['proveedor'] . '\', \'' . $value['maquina'] . '\')"><i class="fa fa-eye"></i></button>',
              '5' => number_format($value['costo_parcial'], 2, '.', ',' ),
              '6' => number_format($value['deposito'], 2, '.', ',' ),
              '7' => number_format($saldo_x_fila , 2, '.', ',' ),
            );
  
            $t_monto += floatval($value['costo_parcial']);
            $t_pagos += floatval($value['deposito']);
            $t_saldo += floatval($saldo_x_fila);
          } else {
            if ($deuda == 'sindeuda') {
              if ($saldo_x_fila == 0) {
                $datatable[] = array(
                  '0' => $key+1, 
                  '1' => $value['maquina'] .' - '. $value['proveedor'],
                  '2' => format_d_m_a($value['fecha_entrega']),
                  '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >- - -</textarea>',
                  '4' => '<button class="btn btn-info btn-sm" onclick="mostrar_detalle_maquinaria_equipo('.$value['idmaquinaria'].', \'' . $value['idproyecto'].  '\', \'' .'Servicio Equipo:'.  '\', \'' . $value['proveedor'] . '\', \'' . $value['maquina'] . '\')"><i class="fa fa-eye"></i></button>',
                  '5' => number_format($value['costo_parcial'], 2, '.', ',' ),
                  '6' => number_format($value['deposito'], 2, '.', ',' ),
                  '7' => number_format($saldo_x_fila , 2, '.', ',' ),
                );
      
                $t_monto += floatval($value['costo_parcial']);
                $t_pagos += floatval($value['deposito']);
                $t_saldo += floatval($saldo_x_fila);
              }
            } else {
              if ($deuda == 'condeuda') {
                if ($saldo_x_fila > 0) {
                  $datatable[] = array(
                    '0' => $key+1, 
                    '1' => $value['maquina'] .' - '. $value['proveedor'],
                    '2' => format_d_m_a($value['fecha_entrega']),
                    '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >- - -</textarea>',
                    '4' => '<button class="btn btn-info btn-sm" onclick="mostrar_detalle_maquinaria_equipo('.$value['idmaquinaria'].', \'' . $value['idproyecto'].  '\', \'' .'Servicio Equipo:'.  '\', \'' . $value['proveedor'] . '\', \'' . $value['maquina'] . '\')"><i class="fa fa-eye"></i></button>',
                    '5' => number_format($value['costo_parcial'], 2, '.', ',' ),
                    '6' => number_format($value['deposito'], 2, '.', ',' ),
                    '7' => number_format($saldo_x_fila , 2, '.', ',' ),
                  );
        
                  $t_monto += floatval($value['costo_parcial']);
                  $t_pagos += floatval($value['deposito']);
                  $t_saldo += floatval($saldo_x_fila);
                }
              } else {
                if ($deuda == 'conexcedente') {
                  if ($saldo_x_fila < 0) {
                    $datatable[] = array(
                      '0' => $key+1, 
                      '1' => $value['maquina'] .' - '. $value['proveedor'],
                      '2' => format_d_m_a($value['fecha_entrega']),
                      '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >- - -</textarea>',
                      '4' => '<button class="btn btn-info btn-sm" onclick="mostrar_detalle_maquinaria_equipo('.$value['idmaquinaria'].', \'' . $value['idproyecto'].  '\', \'' .'Servicio Equipo:'.  '\', \'' . $value['proveedor'] . '\', \'' . $value['maquina'] . '\')"><i class="fa fa-eye"></i></button>',
                      '5' => number_format($value['costo_parcial'], 2, '.', ',' ),
                      '6' => number_format($value['deposito'], 2, '.', ',' ),
                      '7' => number_format($saldo_x_fila , 2, '.', ',' ),
                    );
          
                    $t_monto += floatval($value['costo_parcial']);
                    $t_pagos += floatval($value['deposito']);
                    $t_saldo += floatval($saldo_x_fila);
                  }
                }
              }
            }            
          }
        }

        $data = array(
          't_monto' => $t_monto, 
          't_pagos' => $t_pagos,
          't_saldo' => $t_saldo,
          'datatable' => $datatable
        );
        //Codificar el resultado utilizando json
        echo json_encode($data);

      break;

      case 'mostrar_detalle_maquinaria_equipo':
        $idmaquinaria = $_GET["idmaquinaria"];
        $idproyecto = $_GET["idproyecto"];

        $rspta = $resumen_general->ver_detalle_maq_equ($idmaquinaria, $idproyecto);
        $fecha_entreg = '';
        $fecha_recoj = '';
        $fecha = '';
        //Vamos a declarar un array
        $data = [];

        while ($reg = $rspta->fetch_object()) {
          if (empty($reg->fecha_recojo) || $reg->fecha_recojo == '0000-00-00') {
             
            $fecha_entreg = nombre_dia_semana($reg->fecha_entrega);

            $fecha = '<b class="text-primary text-nowrap" >'.$fecha_entreg.', '. format_d_m_a( $reg->fecha_entrega).'</b>';
          } else {            
             
            $fecha_entreg = nombre_dia_semana($reg->fecha_entrega);
             
            $fecha_recoj = nombre_dia_semana($reg->fecha_recojo);             
             
            $fecha = '<b class="text-primary text-nowrap" > '.$fecha_entreg .', '. format_d_m_a( $reg->fecha_entrega) .'</b> / <br> 
            <b  class="text-danger text-nowrap">'.$fecha_recoj .', '.format_d_m_a( $reg->fecha_recojo) .'<b>';
          }           

          $tool = '"tooltip"';
          $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>";

          $data[] = [
            "0" => $fecha,
            "1" => empty($reg->unidad_medida) ? '-' : $reg->unidad_medida,
            "2" => empty($reg->cantidad) ? '-' : $reg->cantidad,
            "3" => empty($reg->costo_unitario) || $reg->costo_unitario == '0.00' ? '-' : number_format($reg->costo_unitario, 2, '.', ','),
            "4" => empty($reg->costo_parcial) ? '-' : number_format($reg->costo_parcial, 2, '.', ','),
            "5" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.$reg->descripcion.'</textarea>'  ,
          ];
        }
        $results = [
          "sEcho" => 1, //Información para el datatables
          "iTotalRecords" => count($data), //enviamos el total registros al datatable
          "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
          "data" => $data,
        ];
        echo json_encode($results);
      break;
      
      // TABLA
      case 'tbla_transportes':

        $data = Array(); $datatable = Array();

        $deuda = $_POST['deuda'];

        $t_monto = 0;
        $t_pagos = 0;
        $t_saldo = 0;   
        $saldo_x_fila = 0;

        $rspta = $resumen_general->tabla_transportes($_POST['idproyecto'], $_POST['fecha_filtro_1'], $_POST['fecha_filtro_2']);
        
        foreach ($rspta as $key => $value) {

          $saldo_x_fila = 0; $comprobante ='';

          if ( !empty($value['comprobante']) ) {
            $comprobante = '<a target="_blank"  href="../dist/img/comprob_transporte/'.$value['comprobante'].'"> <i class="far fa-file-pdf"  style="font-size: 23px;"></i></a>';
          } else {
            $comprobante = '<a> <i class="far fa-times-circle"  style="font-size: 23px;"></i></a>';
          }

          if ($deuda == '' || $deuda == null || $deuda == 'todos') {
            $datatable[] = array(
              '0' => $key+1, 
              '1' => '- - -',
              '2' => format_d_m_a($value['fecha_viaje']),
              '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.$value['descripcion'].'</textarea>',
              '4' =>  $comprobante,
              '5' => number_format($value['precio_parcial'], 2, '.', ',' ),
              '6' => number_format($value['precio_parcial'], 2, '.', ',' ),
              '7' => number_format($saldo_x_fila , 2, '.', ',' ),
            );
  
            $t_monto += floatval($value['precio_parcial']);
            $t_pagos += floatval($value['precio_parcial']);
            $t_saldo += floatval($saldo_x_fila);
          } else {
            if ($deuda == 'sindeuda') {
              $datatable[] = array(
                '0' => $key+1, 
                '1' => '- - -',
                '2' => format_d_m_a($value['fecha_viaje']),
                '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.$value['descripcion'].'</textarea>',
                '4' =>  $comprobante,
                '5' => number_format($value['precio_parcial'], 2, '.', ',' ),
                '6' => number_format($value['precio_parcial'], 2, '.', ',' ),
                '7' => number_format($saldo_x_fila , 2, '.', ',' ),
              );
    
              $t_monto += floatval($value['precio_parcial']);
              $t_pagos += floatval($value['precio_parcial']);
              $t_saldo += floatval($saldo_x_fila);
            }
          }                   
        }

        $data = array(
          't_monto' => $t_monto, 
          't_pagos' => $t_pagos,
          't_saldo' => $t_saldo,
          'datatable' => $datatable
        );

        //Codificar el resultado utilizando json
        echo json_encode($data);

      break;
      
      // TABLA
      case 'tbla_hospedajes':

        $data = Array(); $datatable = Array();

        $deuda = $_POST['deuda'];

        $t_monto = 0;
        $t_pagos = 0;
        $t_saldo = 0;   
        $saldo_x_fila = 0;

        $rspta = $resumen_general->tabla_hospedajes($_POST['idproyecto'], $_POST['fecha_filtro_1'], $_POST['fecha_filtro_2']);

        foreach ($rspta as $key => $value) {

          $saldo_x_fila = 0;
          
          if ( !empty($value['comprobante']) ) {
            $comprobante = '<a target="_blank"  href="../dist/img/comprob_hospedajes/'.$value['comprobante'].'"> <i class="far fa-file-pdf"  style="font-size: 23px;"></i></a>';
          } else {
            $comprobante = '<a> <i class="far fa-times-circle"  style="font-size: 23px;"></i></a>';
          }

          if ($deuda == '' || $deuda == null || $deuda == 'todos') {
            $datatable[] = array(
              '0' => $key+1, 
              '1' => '- - -', 
              '2' => format_d_m_a($value['fecha_comprobante']),
              '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.$value['descripcion'].'</textarea>',
              '4' => $comprobante,
              '5' => number_format($value['precio_parcial'], 2, '.', ',' ),
              '6' => number_format($value['precio_parcial'], 2, '.', ',' ),
              '7' => number_format($saldo_x_fila , 2, '.', ',' ),
            );
  
            $t_monto += floatval($value['precio_parcial']);
            $t_pagos += floatval($value['precio_parcial']);
            $t_saldo += floatval($saldo_x_fila);
          } else {
            if ($deuda == 'sindeuda') {
              $datatable[] = array(
                '0' => $key+1, 
                '1' => '- - -', 
                '2' => $value['fecha_comprobante'],
                '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.$value['descripcion'].'</textarea>',
                '4' => $comprobante,
                '5' => number_format($value['precio_parcial'], 2, '.', ',' ),
                '6' => number_format($value['precio_parcial'], 2, '.', ',' ),
                '7' => number_format($saldo_x_fila , 2, '.', ',' ),
              );
    
              $t_monto += floatval($value['precio_parcial']);
              $t_pagos += floatval($value['precio_parcial']);
              $t_saldo += floatval($saldo_x_fila);
            }
          }                    
        }

        $data = array(
          't_monto' => $t_monto, 
          't_pagos' => $t_pagos,
          't_saldo' => $t_saldo,
          'datatable' => $datatable
        );

        //Codificar el resultado utilizando json
        echo json_encode($data);

      break;
      
      // TABLA
      case 'tbla_comidas_extras':

        $data = Array(); $datatable = Array();

        $deuda = $_POST['deuda'];

        $t_monto = 0;
        $t_pagos = 0;
        $t_saldo = 0;   
        $saldo_x_fila = 0;

        $rspta = $resumen_general->tabla_comidas_extras($_POST['idproyecto'], $_POST['fecha_filtro_1'], $_POST['fecha_filtro_2']);

        foreach ($rspta as $key => $value) {

          $saldo_x_fila = 0; $comprobante ='';

          if ( !empty($value['comprobante']) ) {
            $comprobante = '<a target="_blank"  href="../dist/img/comidas_extras/'.$value['comprobante'].'"> <i class="far fa-file-pdf"  style="font-size: 23px;"></i></a>';
          } else {
            $comprobante = '<a> <i class="far fa-times-circle"  style="font-size: 23px;"></i></a>';
          }

          if ($deuda == '' || $deuda == null || $deuda == 'todos') {
            $datatable[] = array(
              '0' => $key+1, 
              '1' => '- - -',
              '2' => format_d_m_a($value['fecha_comida']),
              '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.$value['descripcion'].'</textarea>',
              '4' => $comprobante,
              '5' => number_format($value['costo_parcial'], 2, '.', ',' ),
              '6' => number_format($value['costo_parcial'], 2, '.', ',' ),
              '7' => number_format($saldo_x_fila , 2, '.', ',' ),
            );
  
            $t_monto += floatval($value['costo_parcial']);
            $t_pagos += floatval($value['costo_parcial']);
            $t_saldo += floatval($saldo_x_fila);
          } else {
            if ($deuda == 'sindeuda') {
              $datatable[] = array(
                '0' => $key+1, 
                '1' => '- - -',
                '2' => format_d_m_a($value['fecha_comida']),
                '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.$value['descripcion'].'</textarea>',
                '4' => $comprobante,
                '5' => number_format($value['costo_parcial'], 2, '.', ',' ),
                '6' => number_format($value['costo_parcial'], 2, '.', ',' ),
                '7' => number_format($saldo_x_fila , 2, '.', ',' ),
              );
    
              $t_monto += floatval($value['costo_parcial']);
              $t_pagos += floatval($value['costo_parcial']);
              $t_saldo += floatval($saldo_x_fila);
            }
          }
        }

        $data = array(
          't_monto' => $t_monto, 
          't_pagos' => $t_pagos,
          't_saldo' => $t_saldo,
          'datatable' => $datatable
        );

        //Codificar el resultado utilizando json
        echo json_encode($data);

      break;
      
      // TABLA
      case 'tbla_breaks':

        $data = Array(); $datatable = Array();

        $deuda = $_POST['deuda'];

        $t_monto = 0;
        $t_pagos = 0;
        $t_saldo = 0;   
        $saldo_x_fila = 0;

        $rspta = $resumen_general->tabla_breaks($_POST['idproyecto'], $_POST['fecha_filtro_1'], $_POST['fecha_filtro_2']);

        foreach ($rspta as $key => $value) {

          $saldo_x_fila = 0;

          if ($deuda == '' || $deuda == null || $deuda == 'todos') {
            $datatable[] = array(
              '0' => $key+1, 
              '1' => '- - -',
              '2' =>  format_d_m_a($value['fecha_inicial']) .' - '. format_d_m_a($value['fecha_final']),
              '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >- - -</textarea>',
              '4' => '<button class="btn btn-info btn-sm" onclick="mostrar_comprobantes_breaks('.$value['idsemana_break'] .')"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button>',
              '5' => number_format($value['total'], 2, '.', ',' ),
              '6' => number_format($value['total'], 2, '.', ',' ),
              '7' => number_format($saldo_x_fila , 2, '.', ',' ),
            );
  
            $t_monto += floatval($value['total']);
            $t_pagos += floatval($value['total']);
            $t_saldo += floatval($saldo_x_fila);
          } else {
            if ($deuda == 'sindeuda') {
              $datatable[] = array(
                '0' => $key+1, 
                '1' => '- - -',
                '2' =>  format_d_m_a($value['fecha_inicial']) .' - '. format_d_m_a($value['fecha_final']),
                '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >- - -</textarea>',
                '4' => '<button class="btn btn-info btn-sm" onclick="mostrar_comprobantes_breaks('.$value['idsemana_break'] .')"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button>',
                '5' => number_format($value['total'], 2, '.', ',' ),
                '6' => number_format($value['total'], 2, '.', ',' ),
                '7' => number_format($saldo_x_fila , 2, '.', ',' ),
              );
    
              $t_monto += floatval($value['total']);
              $t_pagos += floatval($value['total']);
              $t_saldo += floatval($saldo_x_fila);
            }
          }
        }

        $data = array(
          't_monto' => $t_monto, 
          't_pagos' => $t_pagos,
          't_saldo' => $t_saldo,
          'datatable' => $datatable
        );

        //Codificar el resultado utilizando json
        echo json_encode($data);

      break;

      case 'mostrar_comprobantes_breaks':
        $rspta = $resumen_general->listar_comprobantes_breaks($_GET['idsemana_break']);

        //Vamos a declarar un array
        $data = [];
        $comprobante = '';
        $subtotal = 0;
        $igv = 0;
        $monto = 0;

        while ($reg = $rspta->fetch_object()) {
          $subtotal = round($reg->subtotal, 2);
          $igv = round($reg->igv, 2);
          $monto = round($reg->monto, 2);
          if (strlen($reg->descripcion) >= 20) {
            $descripcion = substr($reg->descripcion, 0, 20) . '...';
          } else {
            $descripcion = $reg->descripcion;
          }
          empty($reg->comprobante)
            ? ($comprobante = '<div><center><a type="btn btn-danger" class=""><i class="far fa-times-circle fa-2x"></i></a></center></div>')
            : ($comprobante = '<div><center><a type="btn btn-danger" target="_blank" href="../dist/img/comprob_breaks/' . $reg->comprobante . '"><i class="fas fa-file-invoice fa-2x"></i></a></center></div>');
          $tool = '"tooltip"';
          $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>";
          $data[] = [
            "0" => empty($reg->forma_de_pago) ? ' - ' : $reg->forma_de_pago,
            "1" => empty($reg->tipo_comprobante) ? ' - ' : $reg->tipo_comprobante,
            "2" => empty($reg->nro_comprobante) ? ' - ' : $reg->nro_comprobante,
            "3" => date("d/m/Y", strtotime($reg->fecha_emision)),
            "4" => number_format($subtotal, 2, '.', ','),
            "5" => number_format($igv, 2, '.', ','),
            "6" => number_format($monto, 2, '.', ','),
            "7" => empty($reg->descripcion) ? '-' : '<div data-toggle="tooltip" data-original-title="' . $reg->descripcion . '">' . $descripcion . '</div>',
            "8" => $comprobante,
          ];
        }
        $results = [
          "sEcho" => 1, //Información para el datatables
          "iTotalRecords" => count($data), //enviamos el total registros al datatable
          "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
          "data" => $data,
        ];
        echo json_encode($results);
      break;
      
      // TABLA
      case 'tbla_pensiones':
        $data = Array(); $datatable = Array();

        $deuda = $_POST['deuda'];

        $t_monto = 0;
        $t_pagos = 0;
        $t_saldo = 0;   
        $saldo_x_fila = 0;

        $rspta = $resumen_general->tabla_pensiones($_POST['idproyecto'], $_POST['id_proveedor']);

        foreach ($rspta as $key => $value) {

          $saldo_x_fila = floatval($value['monto_total_pension']) - floatval($value['deposito']);

          if ($deuda == '' || $deuda == null || $deuda == 'todos') {
            $datatable[] = array(
              '0' => $key+1, 
              '1' => $value['proveedor'],
              '2' => '- - -',
              '3' => '- - -',
              '4' => '<button class="btn btn-info btn-sm" onclick="mostrar_detalle_pension('.$value['idpension'].')"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button>
                      <button class="btn btn-info btn-sm" onclick="mostrar_comprobantes_pension('.$value['idpension'].')"><i class="far fa-file-pdf fa-lg btn-info nav-icon"></i></button>',
              '5' => number_format($value['monto_total_pension'], 2, '.', ',' ),
              '6' => number_format($value['deposito'], 2, '.', ',' ),
              '7' => number_format($saldo_x_fila , 2, '.', ',' ),
            );
  
            $t_monto += floatval($value['monto_total_pension']);
            $t_pagos += floatval($value['deposito']);
            $t_saldo += floatval($saldo_x_fila);
          } else {
            if ($deuda == 'sindeuda') {
              if ($saldo_x_fila == 0) {
                $datatable[] = array(
                  '0' => $key+1, 
                  '1' => $value['proveedor'],
                  '2' => '- - -',
                  '3' => '- - -',
                  '4' => '<button class="btn btn-info btn-sm" onclick="mostrar_detalle_pension('.$value['idpension'].')"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button>
                          <button class="btn btn-info btn-sm" onclick="mostrar_comprobantes_pension('.$value['idpension'].')"><i class="far fa-file-pdf fa-lg btn-info nav-icon"></i></button>',
                  '5' => number_format($value['monto_total_pension'], 2, '.', ',' ),
                  '6' => number_format($value['deposito'], 2, '.', ',' ),
                  '7' => number_format($saldo_x_fila , 2, '.', ',' ),
                );
      
                $t_monto += floatval($value['monto_total_pension']);
                $t_pagos += floatval($value['deposito']);
                $t_saldo += floatval($saldo_x_fila);
              }
            } else {
              if ($deuda == 'condeuda') {
                if ($saldo_x_fila > 0) {
                  $datatable[] = array(
                    '0' => $key+1, 
                    '1' => $value['proveedor'],
                    '2' => '- - -',
                    '3' => '- - -',
                    '4' => '<button class="btn btn-info btn-sm" onclick="mostrar_detalle_pension('.$value['idpension'].')"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button>
                            <button class="btn btn-info btn-sm" onclick="mostrar_comprobantes_pension('.$value['idpension'].')"><i class="far fa-file-pdf fa-lg btn-info nav-icon"></i></button>',
                    '5' => number_format($value['monto_total_pension'], 2, '.', ',' ),
                    '6' => number_format($value['deposito'], 2, '.', ',' ),
                    '7' => number_format($saldo_x_fila , 2, '.', ',' ),
                  );
        
                  $t_monto += floatval($value['monto_total_pension']);
                  $t_pagos += floatval($value['deposito']);
                  $t_saldo += floatval($saldo_x_fila);
                }
              }else{
                if ($deuda == 'conexcedente') {
                  if ($saldo_x_fila < 0) {
                    $datatable[] = array(
                      '0' => $key+1, 
                      '1' => $value['proveedor'],
                      '2' => '- - -',
                      '3' => '- - -',
                      '4' => '<button class="btn btn-info btn-sm" onclick="mostrar_detalle_pension('.$value['idpension'].')"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button>
                              <button class="btn btn-info btn-sm" onclick="mostrar_comprobantes_pension('.$value['idpension'].')"><i class="far fa-file-pdf fa-lg btn-info nav-icon"></i></button>',
                      '5' => number_format($value['monto_total_pension'], 2, '.', ',' ),
                      '6' => number_format($value['deposito'], 2, '.', ',' ),
                      '7' => number_format($saldo_x_fila , 2, '.', ',' ),
                    );
          
                    $t_monto += floatval($value['monto_total_pension']);
                    $t_pagos += floatval($value['deposito']);
                    $t_saldo += floatval($saldo_x_fila);
                  }
                }
              }
            }            
          }
        }
        $data = array(
          't_monto' => $t_monto, 
          't_pagos' => $t_pagos,
          't_saldo' => $t_saldo,
          'datatable' => $datatable
        );
        //Codificar el resultado utilizando json
        echo json_encode($data);

      break;

      case 'mostrar_detalle_pension':
        $rspta = $resumen_general->ver_detalle_x_servicio($_GET['idpension']);
        //Vamos a declarar un array
        $data = [];
        $cont = 1;
        while ($reg = $rspta->fetch_object()) {
          $data[] = [
            "0" =>
              '<div class="user-block">
					  <span style="font-weight: bold;" ><p class="text-primary"style="margin-bottom: 0.2rem !important"; >' .
              $cont .
              '. ' .
              $reg->nombre_servicio .
              '</p></span></div>',
            "1" => '<b>' . number_format($reg->precio, 2, '.', ',') . '</b>',
            "2" => '<b>' . $reg->cantidad_total_platos . '</b>',
            "3" => '<b>' . number_format($reg->adicional_descuento, 2, '.', ',') . '</b>',
            "4" => '<b>' . number_format($reg->total, 2, '.', ',') . '</b>',
          ];
          $cont++;
        }
        $results = [
          "sEcho" => 1, //Información para el datatables
          "iTotalRecords" => count($data), //enviamos el total registros al datatable
          "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
          "aaData" => $data,
        ];
        echo json_encode($results);

      break;

      case 'mostrar_comprobantes_pension':
        //$idpension_f ='5';
        //$_GET['idpension_f']
        $rspta = $resumen_general->listar_comprobantes_pension($_GET['idpension']);

        //Vamos a declarar un array
        $data = [];
        $comprobante = '';
        $subtotal = 0;
        $igv = 0;
        $monto = 0;

        while ($reg = $rspta->fetch_object()) {
          $subtotal = round($reg->subtotal, 2);
          $igv = round($reg->igv, 2);
          $monto = round($reg->monto, 2);

          if (strlen($reg->descripcion) >= 20) {
            $descripcion = substr($reg->descripcion, 0, 20) . '...';
          } else {
            $descripcion = $reg->descripcion;
          }

          empty($reg->comprobante)
            ? ($comprobante = '<div><center><a type="btn btn-danger" class=""><i class="far fa-times-circle fa-2x"></i></a></center></div>')
            : ($comprobante = '<div><center><a type="btn btn-danger" target="_blank"  href="../dist/img/comprob_pension/' . $reg->comprobante . '" ><i class="fas fa-file-invoice fa-2x"></i></a></center></div>');

          $tool = '"tooltip"';
          $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>";

          $data[] = [
            "0" => empty($reg->forma_de_pago) ? ' - ' : $reg->forma_de_pago,
            "1" => empty($reg->tipo_comprobante) ? ' - ' : $reg->tipo_comprobante,
            "2" => empty($reg->nro_comprobante) ? ' - ' : $reg->nro_comprobante,
            "3" => date("d/m/Y", strtotime($reg->fecha_emision)),
            "4" => number_format($subtotal, 2, '.', ','),
            "5" => number_format($igv, 2, '.', ','),
            "6" => number_format($monto, 2, '.', ','),
            "7" => empty($reg->descripcion) ? '-' : '<div data-toggle="tooltip" data-original-title="' . $reg->descripcion . '">' . $descripcion . '</div>',
            "8" => $comprobante,
          ];
        }
        //$suma=array_sum($rspta->fetch_object()->monto);
        $results = [
          "sEcho" => 1, //Información para el datatables
          "iTotalRecords" => count($data), //enviamos el total registros al datatable
          "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
          "data" => $data,
        ];
        echo json_encode($results);
      break;
      
      // TABLA
      case 'tbla_administrativo':

        $data = Array(); $datatable = Array();

        $deuda = $_POST['deuda'];

        $t_monto = 0;
        $t_pagos = 0;
        $t_saldo = 0;   
        $saldo_x_fila = 0;

        $rspta = $resumen_general->tabla_administrativo($_POST['idproyecto'], $_POST['id_trabajador']);

        foreach ($rspta as $key => $value) {

          $saldo_x_fila = floatval($value['total_montos_x_meses']) - floatval($value['deposito']);

          if ($deuda == '' || $deuda == null || $deuda == 'todos') {
            $datatable[] = array(
              '0' => $key+1, 
              '1' => $value['nombres'],
              '2' => '- - -',
              '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >- - -</textarea>',
              '4' => '<button class="btn btn-info btn-sm" onclick="mostrar_detalle_administrativo('.$value['idtrabajador_por_proyecto'] .', \'' .$value['nombres'].  '\')"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button',
              '5' => number_format($value['total_montos_x_meses'], 2, '.', ',' ),
              '6' => number_format($value['deposito'], 2, '.', ',' ),
              '7' => number_format($saldo_x_fila , 2, '.', ',' ),
            );
  
            $t_monto += floatval($value['total_montos_x_meses']);
            $t_pagos += floatval($value['deposito']);
            $t_saldo += floatval($saldo_x_fila);
          } else {
            if ($deuda == 'sindeuda') {
              if ($saldo_x_fila == 0) {
                $datatable[] = array(
                  '0' => $key+1, 
                  '1' => $value['nombres'],
                  '2' => '- - -',
                  '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >- - -</textarea>',
                  '4' => '<button class="btn btn-info btn-sm" onclick="mostrar_detalle_administrativo('.$value['idtrabajador_por_proyecto'] .', \'' .$value['nombres'].  '\')"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button',
                  '5' => number_format($value['total_montos_x_meses'], 2, '.', ',' ),
                  '6' => number_format($value['deposito'], 2, '.', ',' ),
                  '7' => number_format($saldo_x_fila , 2, '.', ',' ),
                );
      
                $t_monto += floatval($value['total_montos_x_meses']);
                $t_pagos += floatval($value['deposito']);
                $t_saldo += floatval($saldo_x_fila);
              }
            } else {
              if ($deuda == 'condeuda') {
                if ($saldo_x_fila > 0) {
                  $datatable[] = array(
                    '0' => $key+1, 
                    '1' => $value['nombres'],
                    '2' => '- - -',
                    '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >- - -</textarea>',
                    '4' => '<button class="btn btn-info btn-sm" onclick="mostrar_detalle_administrativo('.$value['idtrabajador_por_proyecto'] .', \'' .$value['nombres'].  '\')"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button',
                    '5' => number_format($value['total_montos_x_meses'], 2, '.', ',' ),
                    '6' => number_format($value['deposito'], 2, '.', ',' ),
                    '7' => number_format($saldo_x_fila , 2, '.', ',' ),
                  );
        
                  $t_monto += floatval($value['total_montos_x_meses']);
                  $t_pagos += floatval($value['deposito']);
                  $t_saldo += floatval($saldo_x_fila);
                }
              }else{
                if ($deuda == 'conexcedente') {
                  if ($saldo_x_fila < 0) {
                    $datatable[] = array(
                      '0' => $key+1, 
                      '1' => $value['nombres'],
                      '2' => '- - -',
                      '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >- - -</textarea>',
                      '4' => '<button class="btn btn-info btn-sm" onclick="mostrar_detalle_administrativo('.$value['idtrabajador_por_proyecto'] .', \'' .$value['nombres'].  '\')"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button',
                      '5' => number_format($value['total_montos_x_meses'], 2, '.', ',' ),
                      '6' => number_format($value['deposito'], 2, '.', ',' ),
                      '7' => number_format($saldo_x_fila , 2, '.', ',' ),
                    );
          
                    $t_monto += floatval($value['total_montos_x_meses']);
                    $t_pagos += floatval($value['deposito']);
                    $t_saldo += floatval($saldo_x_fila);
                  }
                }
              }
            }            
          }
        }
        $data = array(
          't_monto' => $t_monto, 
          't_pagos' => $t_pagos,
          't_saldo' => $t_saldo,
          'datatable' => $datatable
        );
        //Codificar el resultado utilizando json
        echo json_encode($data);

      break;

      case 'mostrar_detalle_administrativo':

        $rspta = $resumen_general->r_detalle_trab_administrativo($_POST['idtrabajador_por_proyecto']);

        //Codificar el resultado utilizando json
        echo json_encode($rspta);

      break;     
      
      // TABLA
      case 'tbla_obrero':

        $data = Array(); $datatable = Array();

        $deuda = $_POST['deuda'];

        $t_monto = 0;
        $t_pagos = 0;
        $t_saldo = 0;   
        $saldo_x_fila = 0;

        $rspta = $resumen_general->tabla_obrero($_POST['idproyecto'], $_POST['id_trabajador']);
        
        foreach ($rspta as $key => $value) {

          $saldo_x_fila = floatval($value['pago_quincenal']) - floatval($value['deposito']);

          if ($deuda == '' || $deuda == null || $deuda == 'todos') {
            $datatable[] = array(
              '0' => $key+1, 
              '1' => $value['nombres'],
              '2' => '- - -',
              '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >- - -</textarea>',
              '4' => '<button class="btn btn-info btn-sm" onclick="mostrar_detalle_obrero('.$value['idtrabajador_por_proyecto'].', \'' .$value['nombres']. '\')"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button>',
              '5' => number_format($value['pago_quincenal'], 2, '.', ',' ),
              '6' => number_format($value['deposito'], 2, '.', ',' ),
              '7' => number_format($saldo_x_fila , 2, '.', ',' ),
            );
  
            $t_monto += floatval($value['pago_quincenal']);
            $t_pagos += floatval($value['deposito']);
            $t_saldo += floatval($saldo_x_fila);
          } else {
            if ($deuda == 'sindeuda') {
              if ($saldo_x_fila == 0) {
                $datatable[] = array(
                  '0' => $key+1, 
                  '1' => $value['nombres'],
                  '2' => '- - -',
                  '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >- - -</textarea>',
                  '4' => '<button class="btn btn-info btn-sm" onclick="mostrar_detalle_obrero('.$value['idtrabajador_por_proyecto'].', \'' .$value['nombres']. '\')"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button>',
                  '5' => number_format($value['pago_quincenal'], 2, '.', ',' ),
                  '6' => number_format($value['deposito'], 2, '.', ',' ),
                  '7' => number_format($saldo_x_fila , 2, '.', ',' ),
                );
      
                $t_monto += floatval($value['pago_quincenal']);
                $t_pagos += floatval($value['deposito']);
                $t_saldo += floatval($saldo_x_fila);
              }
            } else {
              if ($deuda == 'condeuda') {
                if ($saldo_x_fila > 0) {
                  $datatable[] = array(
                    '0' => $key+1, 
                    '1' => $value['nombres'],
                    '2' => '- - -',
                    '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >- - -</textarea>',
                    '4' => '<button class="btn btn-info btn-sm" onclick="mostrar_detalle_obrero('.$value['idtrabajador_por_proyecto'].', \'' .$value['nombres']. '\')"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button>',
                    '5' => number_format($value['pago_quincenal'], 2, '.', ',' ),
                    '6' => number_format($value['deposito'], 2, '.', ',' ),
                    '7' => number_format($saldo_x_fila , 2, '.', ',' ),
                  );
        
                  $t_monto += floatval($value['pago_quincenal']);
                  $t_pagos += floatval($value['deposito']);
                  $t_saldo += floatval($saldo_x_fila);
                }
              }else{
                if ($deuda == 'conexcedente') {
                  if ($saldo_x_fila < 0) {
                    $datatable[] = array(
                      '0' => $key+1, 
                      '1' => $value['nombres'],
                      '2' => '- - -',
                      '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >- - -</textarea>',
                      '4' => '<button class="btn btn-info btn-sm" onclick="mostrar_detalle_obrero('.$value['idtrabajador_por_proyecto'].', \'' .$value['nombres']. '\')"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button>',
                      '5' => number_format($value['pago_quincenal'], 2, '.', ',' ),
                      '6' => number_format($value['deposito'], 2, '.', ',' ),
                      '7' => number_format($saldo_x_fila , 2, '.', ',' ),
                    );
          
                    $t_monto += floatval($value['pago_quincenal']);
                    $t_pagos += floatval($value['deposito']);
                    $t_saldo += floatval($saldo_x_fila);
                  }
                }
              }
            }            
          }
        }
        $data = array(
          't_monto' => $t_monto, 
          't_pagos' => $t_pagos,
          't_saldo' => $t_saldo,
          'datatable' => $datatable
        );

        //Codificar el resultado utilizando json
        echo json_encode($data);

      break;

      case 'mostrar_detalle_obrero':

        $rspta = $resumen_general->r_detalle_x_obrero($_POST['idtrabajador_por_proyecto']);

        //Codificar el resultado utilizando json
        echo json_encode($rspta);

      break;
      
      // TABLA
      case 'tbla_otros_gastos':

        $data = Array(); $datatable = Array();

        $deuda = $_POST['deuda'];

        $t_monto = 0;
        $t_pagos = 0;
        $t_saldo = 0;   
        $saldo_x_fila = 0;

        $rspta = $resumen_general->tabla_otros_gastos($_POST['idproyecto'], $_POST['fecha_filtro_1'], $_POST['fecha_filtro_2']);

        foreach ($rspta as $key => $value) {

          $saldo_x_fila = 0;
          
          if ( !empty($value['comprobante']) ) {
            $comprobante = '<a target="_blank"  href="../dist/docs/otro_gasto/comprobante/'.$value['comprobante'].'"> <i class="far fa-file-pdf"  style="font-size: 23px;"></i></a>';
          } else {
            $comprobante = '<a> <i class="far fa-times-circle"  style="font-size: 23px;"></i></a>';
          }

          if ($deuda == '' || $deuda == null || $deuda == 'todos') {
            $datatable[] = array(
              '0' => $key+1, 
              '1' => '- - -', 
              '2' => format_d_m_a($value['fecha_g']),
              '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.$value['descripcion'].'</textarea>',
              '4' => $comprobante,
              '5' => number_format($value['costo_parcial'], 2, '.', ',' ),
              '6' => number_format($value['costo_parcial'], 2, '.', ',' ),
              '7' => number_format($saldo_x_fila , 2, '.', ',' ),
            );
  
            $t_monto += floatval($value['costo_parcial']);
            $t_pagos += floatval($value['costo_parcial']);
            $t_saldo += floatval($saldo_x_fila);
          } else {
            if ($deuda == 'sindeuda') {
              $datatable[] = array(
                '0' => $key+1, 
                '1' => '- - -', 
                '2' => $value['fecha_g'],
                '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.$value['descripcion'].'</textarea>',
                '4' => $comprobante,
                '5' => number_format($value['costo_parcial'], 2, '.', ',' ),
                '6' => number_format($value['costo_parcial'], 2, '.', ',' ),
                '7' => number_format($saldo_x_fila , 2, '.', ',' ),
              );
    
              $t_monto += floatval($value['costo_parcial']);
              $t_pagos += floatval($value['costo_parcial']);
              $t_saldo += floatval($saldo_x_fila);
            }
          }                    
        }

        $data = array(
          't_monto' => $t_monto, 
          't_pagos' => $t_pagos,
          't_saldo' => $t_saldo,
          'datatable' => $datatable
        );

        //Codificar el resultado utilizando json
        echo json_encode($data);

      break;

      // Select2 - Proveedores
      case 'select2_proveedores':

        $rspta = $resumen_general->select_proveedores();

        $estado = true;

        while ($reg = $rspta->fetch_object()) {

          if ($estado) {
            echo '<option value="0" >Todos</option>';
            $estado = false;
          }

          echo '<option  value=' . $reg->idproveedor . '>' . $reg->razon_social . ' - ' . $reg->ruc . '</option>';
        }

      break;

      // Select2 - Trabajdores
      case 'select2_trabajadores':

        $rspta = $resumen_general->selecct_trabajadores($_GET['idproyecto']);

        $estado = true;

        while ($reg = $rspta->fetch_object()) {

          if ($estado) {
            echo '<option value="0" >Todos</option>';
            $estado = false;
          }
          echo '<option  value=' . $reg->idtrabajador_por_proyecto . '>' . $reg->nombres . ' - ' . $reg->numero_documento . '</option>';
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

// NOMBRE DIA DE SEMANA
function nombre_dia_semana($fecha) {

  $nombre_dia_semana = "";

  if (!empty($fecha) || $fecha != '0000-00-00' ) {

    $fechas = new FechaEs($fecha);
    $dia = $fechas->getDDDD() . PHP_EOL;

    $nombre_dia_semana = $dia;

  }

  return $nombre_dia_semana;
}

ob_end_flush();
?>
