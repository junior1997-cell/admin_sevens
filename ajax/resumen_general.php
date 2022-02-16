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

    $resumen_general = new Resumen_general();

    switch ($_GET["op"]) {

      case 'listar_r_compras':

        $data = Array(); $datatable = Array();

        $deuda = $_POST['deuda'];

        $t_monto = 0;
        $t_pagos = 0;
        $t_saldo = 0;   
        $saldo_x_fila = 0;     

        $rspta = $resumen_general->r_compras($_POST['idproyecto'], $_POST['fecha_filtro'], $_POST['id_proveedor']);

        foreach ($rspta as $key => $value) {

          $saldo_x_fila = floatval($value['monto_total']) - floatval($value['monto_pago_total']);

          if ($deuda == '' || $deuda == null || $deuda == 'todos') {
            $datatable[] = array(
              '0' => $key+1, 
              '1' => $value['proveedor'],
              '2' => format_d_m_a($value['fecha_compra']),
              '3' => '<textarea cols="30" rows="1" class="text_area_clss" readonly >'.$value['descripcion'].'</textarea>',
              '4' => '<button class="btn btn-info btn-xs" onclick="ver_detalle_compras('.$value['idcompra_proyecto'].')"><i class="fa fa-eye"></i></button>',
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
                  '3' => '<textarea cols="30" rows="1" class="text_area_clss" readonly >'.$value['descripcion'].'</textarea>',
                  '4' => '<button class="btn btn-info btn-xs" onclick="ver_detalle_compras('.$value['idcompra_proyecto'].')"><i class="fa fa-eye"></i></button>',
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
                    '3' => '<textarea cols="30" rows="1" class="text_area_clss" readonly >'.$value['descripcion'].'</textarea>',
                    '4' => '<button class="btn btn-info btn-xs" onclick="ver_detalle_compras('.$value['idcompra_proyecto'].')"><i class="fa fa-eye"></i></button>',
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
                      '3' => '<textarea cols="30" rows="1" class="text_area_clss" readonly >'.$value['descripcion'].'</textarea>',
                      '4' => '<button class="btn btn-info btn-xs" onclick="ver_detalle_compras('.$value['idcompra_proyecto'].')"><i class="fa fa-eye"></i></button>',
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

      case 'ver_detalle_compras':
        $rspta = $resumen_general->detalles_compras($_GET['id_compra']);
        $rspta2 = $resumen_general->ver_compras($_GET['id_compra']);

        $subtotal = 0;
        $ficha = '';
        echo '<thead style="background-color:#A9D0F5">
						<th>Ficha técnica</th>
						<th>Material</th>
						<th>Cantidad</th>
						<th>Precio Compra</th>
						<th>Descuento</th>
						<th>Subtotal</th>
				</thead>';

        while ($reg = $rspta->fetch_object()) {
          $subtotal = $reg->cantidad * $reg->precio_venta - $reg->descuento;

          empty($reg->ficha_tecnica)
            ? ($ficha = '<a ><i class="far fa-file-pdf fa-2x" style="color:#000000c4"></i></a>')
            : ($ficha = '<a target="_blank" href="../dist/ficha_tecnica_materiales/' . $reg->ficha_tecnica . '"><i class="far fa-file-pdf fa-2x" style="color:#ff0000c4"></i></a>');
          echo '<tr class="filas">
							<td>' .
            $ficha .
            '</td>
							<td>' .
            $reg->nombre .
            '</td>
							<td>' .
            $reg->cantidad .
            '</td>
							<td>' .
            $reg->precio_venta .
            '</td>
							<td>' .
            $reg->descuento .
            '</td>
							<td>' .
            $subtotal .
            '</td></tr>';
        }
        echo '<tfoot>
						<td colspan="4"></td>
						<th class="text-center">
							<h5>Subtotal</h5>
							<h5>IGV</h5>
							<h5>TOTAL</h5>
						</th>
						<th>
							<h5 class="text-right subtotal"  style="font-weight: bold;">S/' .
          $rspta2['subtotal_compras'] .
          '</h5>
							<h5 class="text-right igv_comp" style="font-weight: bold;">S/' .
          $rspta2['igv_compras_proyect'] .
          '</h5>
							<b>
								<h4 class="text-right total"  style="font-weight: bold;">S/' .
          $rspta2['monto_total'] .
          '</h4>
							</b>
					</tfoot>';

      break;

      case 'listar_r_serv_maquinaria':
         
        $tipo = '1';

        $data = Array(); $datatable = Array();

        $deuda = $_POST['deuda'];

        $t_monto = 0;
        $t_pagos = 0;
        $t_saldo = 0;   
        $saldo_x_fila = 0;

        $rspta = $resumen_general->r_serv_maquinaria_equipos($_POST['idproyecto'], $_POST['fecha_filtro'], $_POST['id_proveedor'], $tipo);

        foreach ($rspta as $key => $value) {

          $saldo_x_fila = floatval($value['costo_parcial']) - floatval($value['deposito']);

          if ($deuda == '' || $deuda == null || $deuda == 'todos') {
            $datatable[] = array(
              '0' => $key+1, 
              '1' => $value['proveedor'],
              '2' => '- - -',
              '3' => '<textarea cols="30" rows="1" class="text_area_clss" readonly >- - -</textarea>',
              '4' => '<button class="btn btn-info btn-xs" onclick="ver_detalle('.$value['idmaquinaria'].', \'' . $value['idproyecto'].  '\', \'' .'Servicio Maquinaria:'.  '\', \'' . $value['proveedor'] . '\')"><i class="fa fa-eye"></i></button>',
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
                  '1' => $value['proveedor'],
                  '2' => '- - -',
                  '3' => '<textarea cols="30" rows="1" class="text_area_clss" readonly >- - -</textarea>',
                  '4' => '<button class="btn btn-info btn-xs" onclick="ver_detalle('.$value['idmaquinaria'].', \'' . $value['idproyecto'].  '\', \'' .'Servicio Maquinaria:'.  '\', \'' . $value['proveedor'] . '\')"><i class="fa fa-eye"></i></button>',
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
                    '1' => $value['proveedor'],
                    '2' => '- - -',
                    '3' => '<textarea cols="30" rows="1" class="text_area_clss" readonly >- - -</textarea>',
                    '4' => '<button class="btn btn-info btn-xs" onclick="ver_detalle('.$value['idmaquinaria'].', \'' . $value['idproyecto'].  '\', \'' .'Servicio Maquinaria:'.  '\', \'' . $value['proveedor'] . '\')"><i class="fa fa-eye"></i></button>',
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
                      '1' => $value['proveedor'],
                      '2' => '- - -',
                      '3' => '<textarea cols="30" rows="1" class="text_area_clss" readonly >- - -</textarea>',
                      '4' => '<button class="btn btn-info btn-xs" onclick="ver_detalle('.$value['idmaquinaria'].', \'' . $value['idproyecto'].  '\', \'' .'Servicio Maquinaria:'.  '\', \'' . $value['proveedor'] . '\')"><i class="fa fa-eye"></i></button>',
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

      case 'listar_r_serv_equipos':

        $tipo = '2';

        $data = Array(); $datatable = Array();

        $deuda = $_POST['deuda'];

        $t_monto = 0;
        $t_pagos = 0;
        $t_saldo = 0;   
        $saldo_x_fila = 0;

        $rspta = $resumen_general->r_serv_maquinaria_equipos($_POST['idproyecto'], $_POST['fecha_filtro'], $_POST['id_proveedor'], $tipo);

        foreach ($rspta as $key => $value) {

          $saldo_x_fila = floatval($value['costo_parcial']) - floatval($value['deposito']);

          if ($deuda == '' || $deuda == null || $deuda == 'todos') {
            $datatable[] = array(
              '0' => $key+1, 
              '1' => $value['proveedor'],
              '2' => '- - -',
              '3' => '<textarea cols="30" rows="1" class="text_area_clss" readonly >- - -</textarea>',
              '4' => '<button class="btn btn-info btn-xs" onclick="ver_detalle('.$value['idmaquinaria'].', \'' . $value['idproyecto'].  '\', \'' .'Servicio Maquinaria:'.  '\', \'' . $value['proveedor'] . '\')"><i class="fa fa-eye"></i></button>',
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
                  '1' => $value['proveedor'],
                  '2' => '- - -',
                  '3' => '<textarea cols="30" rows="1" class="text_area_clss" readonly >- - -</textarea>',
                  '4' => '<button class="btn btn-info btn-xs" onclick="ver_detalle('.$value['idmaquinaria'].', \'' . $value['idproyecto'].  '\', \'' .'Servicio Maquinaria:'.  '\', \'' . $value['proveedor'] . '\')"><i class="fa fa-eye"></i></button>',
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
                    '1' => $value['proveedor'],
                    '2' => '- - -',
                    '3' => '<textarea cols="30" rows="1" class="text_area_clss" readonly >- - -</textarea>',
                    '4' => '<button class="btn btn-info btn-xs" onclick="ver_detalle('.$value['idmaquinaria'].', \'' . $value['idproyecto'].  '\', \'' .'Servicio Maquinaria:'.  '\', \'' . $value['proveedor'] . '\')"><i class="fa fa-eye"></i></button>',
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
                      '1' => $value['proveedor'],
                      '2' => '- - -',
                      '3' => '<textarea cols="30" rows="1" class="text_area_clss" readonly >- - -</textarea>',
                      '4' => '<button class="btn btn-info btn-xs" onclick="ver_detalle('.$value['idmaquinaria'].', \'' . $value['idproyecto'].  '\', \'' .'Servicio Maquinaria:'.  '\', \'' . $value['proveedor'] . '\')"><i class="fa fa-eye"></i></button>',
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

      case 'ver_detalle_maquina':
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
            $fechas = new FechaEs($reg->fecha_entrega);
            $dia = $fechas->getDDDD() . PHP_EOL;
            $mun_dia = $fechas->getdd() . PHP_EOL;
            $mes = $fechas->getMMMM() . PHP_EOL;
            $anio = $fechas->getYYYY() . PHP_EOL;
            $fecha_entreg = "$dia, $mun_dia de $mes del $anio";
            $fecha = "<b style=" . 'color:#1570cf;' . ">$fecha_entreg</b>";
          } else {
            $fechas = new FechaEs($reg->fecha_entrega);
            //----------
            $dia = $fechas->getDDDD() . PHP_EOL;
            $mun_dia = $fechas->getdd() . PHP_EOL;
            $mes = $fechas->getMMMM() . PHP_EOL;
            $anio = $fechas->getYYYY() . PHP_EOL;
            $fecha_entreg = "$dia, $mun_dia de $mes del $anio";
            //----------
            $fechas = new FechaEs($reg->fecha_recojo);
            $dia2 = $fechas->getDDDD() . PHP_EOL;
            $mun_dia2 = $fechas->getdd() . PHP_EOL;
            $mes2 = $fechas->getMMMM() . PHP_EOL;
            $anio2 = $fechas->getYYYY() . PHP_EOL;
            $fecha_recoj = "$dia2, $mun_dia2 de $mes2 del $anio2";
            $fecha = "<b style=" . 'color:#1570cf;' . ">$fecha_entreg </b> / <br> <b  style=" . 'color:#ff0000;' . ">$fecha_recoj<b>";
          }
          if (strlen($reg->descripcion) >= 20) {
            $descripcion = substr($reg->descripcion, 0, 20) . '...';
          } else {
            $descripcion = $reg->descripcion;
          }

          $tool = '"tooltip"';
          $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>";

          $data[] = [
            "0" => $fecha,
            "1" => empty($reg->unidad_medida) ? '-' : $reg->unidad_medida,
            "2" => empty($reg->cantidad) ? '-' : $reg->cantidad,
            "3" => empty($reg->costo_unitario) || $reg->costo_unitario == '0.00' ? '-' : number_format($reg->costo_unitario, 2, '.', ','),
            "4" => empty($reg->costo_parcial) ? '-' : number_format($reg->costo_parcial, 2, '.', ','),
            "5" => empty($reg->descripcion) ? '-' : '<div data-toggle="tooltip" data-original-title="' . $reg->descripcion . '">' . $descripcion . '</div>',
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

      case 'listar_r_transportes':

        $data = Array(); $datatable = Array();

        $deuda = $_POST['deuda'];

        $t_monto = 0;
        $t_pagos = 0;
        $t_saldo = 0;   
        $saldo_x_fila = 0;

        $rspta = $resumen_general->r_transportes($_POST['idproyecto'], $_POST['fecha_filtro'], $_POST['id_proveedor']);
        
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
              '3' => '<textarea cols="30" rows="1" class="text_area_clss" readonly >'.$value['descripcion'].'</textarea>',
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
                '3' => '<textarea cols="30" rows="1" class="text_area_clss" readonly >'.$value['descripcion'].'</textarea>',
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

      case 'listar_r_hospedajes':

        $data = Array(); $datatable = Array();

        $deuda = $_POST['deuda'];

        $t_monto = 0;
        $t_pagos = 0;
        $t_saldo = 0;   
        $saldo_x_fila = 0;

        $rspta = $resumen_general->r_hospedajes($_POST['idproyecto'], $_POST['fecha_filtro'], $_POST['id_proveedor']);

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
              '3' => '<textarea cols="30" rows="1" class="text_area_clss" readonly >'.$value['descripcion'].'</textarea>',
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
                '3' => '<textarea cols="30" rows="1" class="text_area_clss" readonly >'.$value['descripcion'].'</textarea>',
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

      case 'listar_r_comidas_extras':

        $data = Array(); $datatable = Array();

        $deuda = $_POST['deuda'];

        $t_monto = 0;
        $t_pagos = 0;
        $t_saldo = 0;   
        $saldo_x_fila = 0;

        $rspta = $resumen_general->r_comidas_extras($_POST['idproyecto'], $_POST['fecha_filtro'], $_POST['id_proveedor']);

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
              '3' => '<textarea cols="30" rows="1" class="text_area_clss" readonly >'.$value['descripcion'].'</textarea>',
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
                '3' => '<textarea cols="30" rows="1" class="text_area_clss" readonly >'.$value['descripcion'].'</textarea>',
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

      case 'listar_r_breaks':

        $data = Array(); $datatable = Array();

        $deuda = $_POST['deuda'];

        $t_monto = 0;
        $t_pagos = 0;
        $t_saldo = 0;   
        $saldo_x_fila = 0;

        $rspta = $resumen_general->r_breaks($_POST['idproyecto'], $_POST['fecha_filtro'], $_POST['id_proveedor']);

        foreach ($rspta as $key => $value) {

          $saldo_x_fila = 0;

          if ($deuda == '' || $deuda == null || $deuda == 'todos') {
            $datatable[] = array(
              '0' => $key+1, 
              '1' => '- - -',
              '2' =>  format_d_m_a($value['fecha_inicial']) .' - '. format_d_m_a($value['fecha_final']),
              '3' => '<textarea cols="30" rows="1" class="text_area_clss" readonly >- - -</textarea>',
              '4' => '<button class="btn btn-info btn-xs" onclick="listar_comprobantes_breaks('.$value['idsemana_break'] .')"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button>',
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
                '3' => '<textarea cols="30" rows="1" class="text_area_clss" readonly >- - -</textarea>',
                '4' => '<button class="btn btn-info btn-xs" onclick="listar_comprobantes_breaks('.$value['idsemana_break'] .')"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button>',
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

      case 'listar_comprobantes_breaks':
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

      case 'listar_r_pensiones':
        $data = Array(); $datatable = Array();

        $deuda = $_POST['deuda'];

        $t_monto = 0;
        $t_pagos = 0;
        $t_saldo = 0;   
        $saldo_x_fila = 0;

        $rspta = $resumen_general->r_pensiones($_POST['idproyecto'], $_POST['id_proveedor']);

        foreach ($rspta as $key => $value) {

          $saldo_x_fila = floatval($value['monto_total_pension']) - floatval($value['deposito']);

          if ($deuda == '' || $deuda == null || $deuda == 'todos') {
            $datatable[] = array(
              '0' => $key+1, 
              '1' => $value['proveedor'],
              '2' => '- - -',
              '3' => '- - -',
              '4' => '<button class="btn btn-info btn-sm" onclick="ver_detalle_x_servicio_p('.$value['idpension'].')"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button>
                      <button class="btn btn-info btn-sm" onclick="listar_comprobantes_pension('.$value['idpension'].')"><i class="far fa-file-pdf fa-lg btn-info nav-icon"></i></button>',
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
                  '4' => '<button class="btn btn-info btn-sm" onclick="ver_detalle_x_servicio_p('.$value['idpension'].')"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button>
                          <button class="btn btn-info btn-sm" onclick="listar_comprobantes_pension('.$value['idpension'].')"><i class="far fa-file-pdf fa-lg btn-info nav-icon"></i></button>',
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
                    '4' => '<button class="btn btn-info btn-sm" onclick="ver_detalle_x_servicio_p('.$value['idpension'].')"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button>
                            <button class="btn btn-info btn-sm" onclick="listar_comprobantes_pension('.$value['idpension'].')"><i class="far fa-file-pdf fa-lg btn-info nav-icon"></i></button>',
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
                      '4' => '<button class="btn btn-info btn-sm" onclick="ver_detalle_x_servicio_p('.$value['idpension'].')"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button>
                              <button class="btn btn-info btn-sm" onclick="listar_comprobantes_pension('.$value['idpension'].')"><i class="far fa-file-pdf fa-lg btn-info nav-icon"></i></button>',
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

      case 'ver_detalle_x_servicio':
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

      case 'listar_comprobantes_pension':
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

      case 'listar_r_trab_administrativo':

        $data = Array(); $datatable = Array();

        $deuda = $_POST['deuda'];

        $t_monto = 0;
        $t_pagos = 0;
        $t_saldo = 0;   
        $saldo_x_fila = 0;

        $rspta = $resumen_general->r_trab_administrativo($_POST['idproyecto'], $_POST['id_trabajador']);

        foreach ($rspta as $key => $value) {

          $saldo_x_fila = floatval($value['total_montos_x_meses']) - floatval($value['deposito']);

          if ($deuda == '' || $deuda == null || $deuda == 'todos') {
            $datatable[] = array(
              '0' => $key+1, 
              '1' => $value['nombres'],
              '2' => '- - -',
              '3' => '<textarea cols="30" rows="1" class="text_area_clss" readonly >- - -</textarea>',
              '4' => '<button class="btn btn-info btn-sm" onclick="ver_detalle_pagos_x_trab_adm('.$value['idtrabajador_por_proyecto'] .', \'' .$value['nombres'].  '\')"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button',
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
                  '3' => '<textarea cols="30" rows="1" class="text_area_clss" readonly >- - -</textarea>',
                  '4' => '<button class="btn btn-info btn-sm" onclick="ver_detalle_pagos_x_trab_adm('.$value['idtrabajador_por_proyecto'] .', \'' .$value['nombres'].  '\')"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button',
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
                    '3' => '<textarea cols="30" rows="1" class="text_area_clss" readonly >- - -</textarea>',
                    '4' => '<button class="btn btn-info btn-sm" onclick="ver_detalle_pagos_x_trab_adm('.$value['idtrabajador_por_proyecto'] .', \'' .$value['nombres'].  '\')"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button',
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
                      '3' => '<textarea cols="30" rows="1" class="text_area_clss" readonly >- - -</textarea>',
                      '4' => '<button class="btn btn-info btn-sm" onclick="ver_detalle_pagos_x_trab_adm('.$value['idtrabajador_por_proyecto'] .', \'' .$value['nombres'].  '\')"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button',
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

      case 'ver_detalle_pagos_x_trab_adms':

        $rspta = $resumen_general->r_detalle_trab_administrativo($_POST['idtrabajador_por_proyecto']);

        //Codificar el resultado utilizando json
        echo json_encode($rspta);

      break;     

      case 'listar_r_trabajador_obrero':

        $data = Array(); $datatable = Array();

        $deuda = $_POST['deuda'];

        $t_monto = 0;
        $t_pagos = 0;
        $t_saldo = 0;   
        $saldo_x_fila = 0;

        $rspta = $resumen_general->r_trabajador_obrero($_POST['idproyecto'], $_POST['id_trabajador']);
        
        foreach ($rspta as $key => $value) {

          $saldo_x_fila = floatval($value['pago_quincenal']) - floatval($value['deposito']);

          if ($deuda == '' || $deuda == null || $deuda == 'todos') {
            $datatable[] = array(
              '0' => $key+1, 
              '1' => $value['nombres'],
              '2' => '- - -',
              '3' => '<textarea cols="30" rows="1" class="text_area_clss" readonly >- - -</textarea>',
              '4' => '<button class="btn btn-info btn-sm" onclick="ver_detalle_pagos_x_trab_obrero('.$value['idtrabajador_por_proyecto'].', \'' .$value['nombres']. '\')"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button>',
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
                  '3' => '<textarea cols="30" rows="1" class="text_area_clss" readonly >- - -</textarea>',
                  '4' => '<button class="btn btn-info btn-sm" onclick="ver_detalle_pagos_x_trab_obrero('.$value['idtrabajador_por_proyecto'].', \'' .$value['nombres']. '\')"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button>',
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
                    '3' => '<textarea cols="30" rows="1" class="text_area_clss" readonly >- - -</textarea>',
                    '4' => '<button class="btn btn-info btn-sm" onclick="ver_detalle_pagos_x_trab_obrero('.$value['idtrabajador_por_proyecto'].', \'' .$value['nombres']. '\')"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button>',
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
                      '3' => '<textarea cols="30" rows="1" class="text_area_clss" readonly >- - -</textarea>',
                      '4' => '<button class="btn btn-info btn-sm" onclick="ver_detalle_pagos_x_trab_obrero('.$value['idtrabajador_por_proyecto'].', \'' .$value['nombres']. '\')"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button>',
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

      case 'ver_detalle_pagos_x_trab_obrero':

        $rspta = $resumen_general->r_detalle_x_obrero($_POST['idtrabajador_por_proyecto']);

        //Codificar el resultado utilizando json
        echo json_encode($rspta);

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

  if (!empty($fecha)) {

    $fecha_expl = explode("-", $fecha);

    $fecha_convert = $fecha_expl[2] . "-" . $fecha_expl[1] . "-" . $fecha_expl[0];

  } else {

    $fecha_convert = "";
  }

  return $fecha_convert;
}

ob_end_flush();
?>
