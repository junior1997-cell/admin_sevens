<?php
  ob_start();

  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  // validamos los accesos al sistema
  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {

    if ($_SESSION['resumen_general'] == 1) {

      require_once "../modelos/Resumen_general.php";
      require_once "../modelos/Fechas.php";
      require_once "../modelos/Compra_insumos.php";

      $resumen_general = new Resumen_general();
      $compra_insumos = new Compra_insumos();

      switch ($_GET["op"]) {

        // TABLA - COMPRAS INSUMOS
        case 'tbla_compras':

          $data = Array(); $datatable = Array();

          $deuda = $_POST['deuda'];

          $t_monto = 0;
          $t_pagos = 0;
          $t_saldo = 0;   
          $saldo_x_fila = 0;     

          $rspta = $resumen_general->tabla_compras($_POST['idproyecto'], $_POST['fecha_filtro_1'], $_POST['fecha_filtro_2'], $_POST['id_proveedor']);

          foreach ($rspta['data'] as $key => $value) {

            $saldo_x_fila = floatval($value['monto_total']) - floatval($value['monto_pago_total']);

            if ($deuda == '' || $deuda == null || $deuda == 'todos') {
              $datatable[] = array(
                '0' => $key+1, 
                '1' => $value['proveedor'],
                '2' => $value['fecha_compra'],
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
                    '2' => $value['fecha_compra'],
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
                      '2' => $value['fecha_compra'],
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
                        '2' => $value['fecha_compra'],
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
            'status' => true,
            'menssage' => 'todo oka psss',
            'data' =>[
              't_monto' => $t_monto, 
              't_pagos' => $t_pagos,
              't_saldo' => $t_saldo,
              'datatable' => $datatable
            ]
            
          );

          //Codificar el resultado utilizando json
          echo json_encode($data);
              
        break;    
       

        // TABLA - MAQUINARIA
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

          foreach ($rspta['data'] as $key => $value) {

            $saldo_x_fila = floatval($value['costo_parcial']) - floatval($value['deposito']);

            if ($deuda == '' || $deuda == null || $deuda == 'todos') {
              $datatable[] = array(
                '0' => $key+1, 
                '1' => $value['maquina'] .' - '. $value['proveedor'],
                '2' => $value['fecha_entrega'],
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
                    '2' => $value['fecha_entrega'],
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
                      '2' => $value['fecha_entrega'],
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
                        '2' => $value['fecha_entrega'],
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
            'status' => true,
            'menssage' => 'todo oka psss',
            'data' =>[
              't_monto' => $t_monto, 
              't_pagos' => $t_pagos,
              't_saldo' => $t_saldo,
              'datatable' => $datatable
            ]
          );

          //Codificar el resultado utilizando json
          echo json_encode($data);
              
        break;
        
        // TABLA - EQUIPOS
        case 'tbla_equipos':

          $tipo = '2';

          $data = Array(); $datatable = Array();

          $deuda = $_POST['deuda'];

          $t_monto = 0;
          $t_pagos = 0;
          $t_saldo = 0;   
          $saldo_x_fila = 0;

          $rspta = $resumen_general->tabla_maquinaria_y_equipo($_POST['idproyecto'], $_POST['fecha_filtro_1'], $_POST['fecha_filtro_2'], $_POST['id_proveedor'], $tipo);

          foreach ($rspta['data'] as $key => $value) {

            $saldo_x_fila = floatval($value['costo_parcial']) - floatval($value['deposito']);

            if ($deuda == '' || $deuda == null || $deuda == 'todos') {
              $datatable[] = array(
                '0' => $key+1, 
                '1' => $value['maquina'] .' - '. $value['proveedor'],
                '2' => $value['fecha_entrega'],
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
                    '2' => $value['fecha_entrega'],
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
                      '2' => $value['fecha_entrega'],
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
                        '2' => $value['fecha_entrega'],
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
            'status' => true,
            'menssage' => 'todo oka psss',
            'data' =>[
              't_monto' => $t_monto, 
              't_pagos' => $t_pagos,
              't_saldo' => $t_saldo,
              'datatable' => $datatable
            ]
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

          while ($reg = $rspta['data']->fetch_object()) {
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
          echo json_encode($results, true);
        break;

        // TABLA - SUB CONTRATO
        case 'tbla_sub_contrato':

          $data = Array(); $datatable = Array();

          $deuda = $_POST['deuda'];

          $t_monto = 0;
          $t_pagos = 0;
          $t_saldo = 0;   
          $saldo_x_fila = 0;

          $rspta = $resumen_general->tabla_sub_contrato($_POST['idproyecto'], $_POST['fecha_filtro_1'], $_POST['fecha_filtro_2'], $_POST['id_proveedor']);
          //echo json_encode($rspta, true);
          foreach ($rspta['data'] as $key => $value) {

            $saldo_x_fila = floatval($value['costo_parcial']) - floatval($value['total_deposito']);
            
            if ( !empty($value['comprobante']) ) {
              $comprobante = '<a target="_blank"  href="../dist/docs/sub_contrato/comprobante_subcontrato/'.$value['comprobante'].'"> <i class="far fa-file-pdf"  style="font-size: 23px;"></i></a>';
            } else {
              $comprobante = '<a> <i class="far fa-times-circle"  style="font-size: 23px;"></i></a>';
            }

            if ($deuda == '' || $deuda == null || $deuda == 'todos') {
              $datatable[] = array(
                '0' => $key+1, 
                '1' => $value['razon_social'], 
                '2' => $value['fecha_subcontrato'],
                '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.$value['descripcion'].'</textarea>',
                '4' => $comprobante,
                '5' => number_format($value['costo_parcial'], 2, '.', ',' ),
                '6' => number_format($value['total_deposito'], 2, '.', ',' ),
                '7' => number_format($saldo_x_fila , 2, '.', ',' ),
              );
    
              $t_monto += floatval($value['costo_parcial']);
              $t_pagos += floatval($value['total_deposito']);
              $t_saldo += floatval($saldo_x_fila);
            } else {
              if ($deuda == 'sindeuda') {
                $datatable[] = array(
                  '0' => $key+1, 
                  '1' => $value['razon_social'], 
                  '2' => $value['fecha_subcontrato'],
                  '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.$value['descripcion'].'</textarea>',
                  '4' => $comprobante,
                  '5' => number_format($value['costo_parcial'], 2, '.', ',' ),
                  '6' => number_format($value['total_deposito'], 2, '.', ',' ),
                  '7' => number_format($saldo_x_fila , 2, '.', ',' ),
                );
      
                $t_monto += floatval($value['costo_parcial']);
                $t_pagos += floatval($value['total_deposito']);
                $t_saldo += floatval($saldo_x_fila);
              }else{
                if ($deuda == 'condeuda') {
                  if ($saldo_x_fila > 0) {
                    $datatable[] = array(
                      '0' => $key+1, 
                      '1' => $value['razon_social'], 
                      '2' => $value['fecha_subcontrato'],
                      '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.$value['descripcion'].'</textarea>',
                      '4' => $comprobante,
                      '5' => number_format($value['costo_parcial'], 2, '.', ',' ),
                      '6' => number_format($value['total_deposito'], 2, '.', ',' ),
                      '7' => number_format($saldo_x_fila , 2, '.', ',' ),
                    );
          
                    $t_monto += floatval($value['costo_parcial']);
                    $t_pagos += floatval($value['total_deposito']);
                    $t_saldo += floatval($saldo_x_fila);
                  }
                }else{
                  if ($deuda == 'conexcedente') {
                    if ($saldo_x_fila < 0) {
                      $datatable[] = array(
                        '0' => $key+1, 
                        '1' => $value['razon_social'], 
                        '2' => $value['fecha_subcontrato'],
                        '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.$value['descripcion'].'</textarea>',
                        '4' => $comprobante,
                        '5' => number_format($value['costo_parcial'], 2, '.', ',' ),
                        '6' => number_format($value['total_deposito'], 2, '.', ',' ),
                        '7' => number_format($saldo_x_fila , 2, '.', ',' ),
                      );
            
                      $t_monto += floatval($value['costo_parcial']);
                      $t_pagos += floatval($value['total_deposito']);
                      $t_saldo += floatval($saldo_x_fila);
                    }
                  }
                }
              }
            }                  
          }

          $data = array(
            'status' => true,
            'menssage' => 'todo oka psss',
            'data' =>[
              't_monto' => $t_monto, 
              't_pagos' => $t_pagos,
              't_saldo' => $t_saldo,
              'datatable' => $datatable
            ]
          );

          //Codificar el resultado utilizando json
          echo json_encode($data, true);

        break;

        // TABLA - MANO DE OBRA
        case 'tbla_mano_de_obra':

          $data = Array(); $datatable = Array();

          $deuda = $_POST['deuda'];

          $t_monto = 0;
          $t_pagos = 0;
          $t_saldo = 0;   
          $saldo_x_fila = 0;

          $rspta = $resumen_general->tabla_mano_de_obra($_POST['idproyecto'], $_POST['fecha_filtro_1'], $_POST['fecha_filtro_2'], $_POST['id_proveedor']);
          //echo json_encode($rspta, true);
          foreach ($rspta['data'] as $key => $value) {

            $saldo_x_fila = 0;           
            
            $comprobante = '<a> <i class="far fa-times-circle"  style="font-size: 23px;"></i></a>';            

            if ($deuda == '' || $deuda == null || $deuda == 'todos') {
              $datatable[] = array(
                '0' => $key+1, 
                '1' => $value['razon_social'], 
                '2' => $value['fecha_deposito'],
                '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.$value['descripcion'].'</textarea>',
                '4' => $comprobante,
                '5' => number_format($value['monto'], 2, '.', ',' ),
                '6' => number_format($value['monto'], 2, '.', ',' ),
                '7' => number_format($saldo_x_fila , 2, '.', ',' ),
              );
    
              $t_monto += floatval($value['monto']);
              $t_pagos += floatval($value['monto']);
              $t_saldo += floatval($saldo_x_fila);
            } else {
              if ($deuda == 'sindeuda') {
                $datatable[] = array(
                  '0' => $key+1, 
                  '1' => $value['razon_social'], 
                  '2' => $value['fecha_deposito'],
                  '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.$value['descripcion'].'</textarea>',
                  '4' => $comprobante,
                  '5' => number_format($value['monto'], 2, '.', ',' ),
                  '6' => number_format($value['monto'], 2, '.', ',' ),
                  '7' => number_format($saldo_x_fila , 2, '.', ',' ),
                );
      
                $t_monto += floatval($value['monto']);
                $t_pagos += floatval($value['monto']);
                $t_saldo += floatval($saldo_x_fila);
              }
            }                  
          }

          $data = array(
            'status' => true,
            'menssage' => 'todo oka psss',
            'data' =>[
              't_monto' => $t_monto, 
              't_pagos' => $t_pagos,
              't_saldo' => $t_saldo,
              'datatable' => $datatable
            ]
          );

          //Codificar el resultado utilizando json
          echo json_encode($data, true);

        break;

        // TABLA - PANILLA SEGURO
        case 'tbla_planilla_seguro':

          $data = Array(); $datatable = Array();

          $deuda = $_POST['deuda'];

          $t_monto = 0;
          $t_pagos = 0;
          $t_saldo = 0;   
          $saldo_x_fila = 0;

          $rspta = $resumen_general->tabla_planilla_seguro($_POST['idproyecto'], $_POST['fecha_filtro_1'], $_POST['fecha_filtro_2'], $_POST['id_proveedor']);

          foreach ($rspta['data'] as $key => $value) {

            $saldo_x_fila = 0;
            
            if ( !empty($value['comprobante']) ) {
              $comprobante = '<a target="_blank"  href="../dist/docs/otro_gasto/comprobante/'.$value['comprobante'].'"> <i class="far fa-file-pdf"  style="font-size: 23px;"></i></a>';
            } else {
              $comprobante = '<a> <i class="far fa-times-circle"  style="font-size: 23px;"></i></a>';
            }

            if ($deuda == '' || $deuda == null || $deuda == 'todos') {
              $datatable[] = array(
                '0' => $key+1, 
                '1' => $value['razon_social'], 
                '2' => $value['fecha_p_s'],
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
                  '2' => $value['fecha_p_s'],
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
            'status' => true,
            'menssage' => 'todo oka psss',
            'data' =>[
              't_monto' => $t_monto, 
              't_pagos' => $t_pagos,
              't_saldo' => $t_saldo,
              'datatable' => $datatable
            ]
          );

          //Codificar el resultado utilizando json
          echo json_encode($data);

        break;

        // TABLA - OTROS GASTOS
        case 'tbla_otros_gastos':

          $data = Array(); $datatable = Array();

          $deuda = $_POST['deuda'];

          $t_monto = 0;
          $t_pagos = 0;
          $t_saldo = 0;   
          $saldo_x_fila = 0;

          $rspta = $resumen_general->tabla_otros_gastos($_POST['idproyecto'], $_POST['fecha_filtro_1'], $_POST['fecha_filtro_2']);

          foreach ($rspta['data'] as $key => $value) {

            $saldo_x_fila = 0;
            $comprobante = ( empty($value['comprobante']) ? '<a> <i class="far fa-times-circle"  style="font-size: 23px;"></i></a>' : '<a target="_blank"  href="../dist/docs/otro_gasto/comprobante/'.$value['comprobante'].'"> <i class="far fa-file-pdf"  style="font-size: 23px;"></i></a>' ) ;            

            if ($deuda == '' || $deuda == null || $deuda == 'todos') {
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
            'status' => true,
            'menssage' => 'todo oka psss',
            'data' =>[
              't_monto' => $t_monto, 
              't_pagos' => $t_pagos,
              't_saldo' => $t_saldo,
              'datatable' => $datatable
            ]
          );

          //Codificar el resultado utilizando json
          echo json_encode($data);

        break;
        
        // TABLA - TRANSPORTE
        case 'tbla_transportes':

          $data = Array(); $datatable = Array();

          $deuda = $_POST['deuda'];

          $t_monto = 0;
          $t_pagos = 0;
          $t_saldo = 0;   
          $saldo_x_fila = 0;

          $rspta = $resumen_general->tabla_transportes($_POST['idproyecto'], $_POST['fecha_filtro_1'], $_POST['fecha_filtro_2']);
          
          foreach ($rspta['data'] as $key => $value) {

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
                '2' => $value['fecha_viaje'],
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
                  '2' => $value['fecha_viaje'],
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
            'status' => true,
            'menssage' => 'todo oka psss',
            'data' =>[
              't_monto' => $t_monto, 
              't_pagos' => $t_pagos,
              't_saldo' => $t_saldo,
              'datatable' => $datatable
            ]
          );

          //Codificar el resultado utilizando json
          echo json_encode($data);

        break;
        
        // TABLA - HOSPEDAJE
        case 'tbla_hospedajes':

          $data = Array(); $datatable = Array();

          $deuda = $_POST['deuda'];

          $t_monto = 0;
          $t_pagos = 0;
          $t_saldo = 0;   
          $saldo_x_fila = 0;

          $rspta = $resumen_general->tabla_hospedajes($_POST['idproyecto'], $_POST['fecha_filtro_1'], $_POST['fecha_filtro_2']);

          foreach ($rspta['data'] as $key => $value) {

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
            'status' => true,
            'menssage' => 'todo oka psss',
            'data' =>[
              't_monto' => $t_monto, 
              't_pagos' => $t_pagos,
              't_saldo' => $t_saldo,
              'datatable' => $datatable
            ]
          );

          //Codificar el resultado utilizando json
          echo json_encode($data);

        break;

        // TABLA - PENSIONES
        case 'tbla_pensiones':
          $data = Array(); $datatable = Array();

          $deuda = $_POST['deuda'];

          $t_monto = 0;
          $t_pagos = 0;
          $t_saldo = 0;   
          $saldo_x_fila = 0;

          $rspta = $resumen_general->tabla_pensiones($_POST['idproyecto'], $_POST['fecha_filtro_1'], $_POST['fecha_filtro_2'], $_POST['id_proveedor']);
          
          foreach ($rspta['data'] as $key => $value) {

            $saldo_x_fila = 0;
            $comprobante = ( empty($value['comprobante']) ? '<a> <i class="far fa-times-circle"  style="font-size: 23px;"></i></a>' : '<a target="_blank"  href="../dist/docs/pension/comprobante/'.$value['comprobante'].'"> <i class="far fa-file-pdf"  style="font-size: 23px;"></i></a>' ) ;

            if ($deuda == '' || $deuda == null || $deuda == 'todos') {
              $datatable[] = array(
                '0' => $key+1, 
                '1' => $value['razon_social'],
                '2' => $value['fecha_emision'],
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
                if ($saldo_x_fila == 0) {
                  $datatable[] = array(
                    '0' => $key+1, 
                    '1' => $value['proveedor'],
                    '2' => $value['fecha_emision'],
                    '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.$value['descripcion'].'</textarea>',
                    '4' => '<button class="btn btn-info btn-sm" onclick="mostrar_detalle_pension('.$value['iddetalle_pension'].')"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button>
                            <button class="btn btn-info btn-sm" onclick="mostrar_comprobantes_pension('.$value['iddetalle_pension'].')"><i class="far fa-file-pdf fa-lg btn-info nav-icon"></i></button>',
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
          }
          $data = array(
            'status' => true,
            'menssage' => 'todo oka psss',
            'data' =>[
              't_monto' => $t_monto, 
              't_pagos' => $t_pagos,
              't_saldo' => $t_saldo,
              'datatable' => $datatable
            ]
          );
          //Codificar el resultado utilizando json
          echo json_encode($data);

        break;

        case 'mostrar_detalle_pension':
          $rspta = $resumen_general->ver_detalle_x_servicio($_GET['idpension']);
          //Vamos a declarar un array
          $data = [];
          $cont = 1;
          while ($reg = $rspta['data']->fetch_object()) {
            $data[] = [
              "0" =>$cont++,
              "1"=>'<textarea cols="30" rows="1" class="textarea_datatable" readonly="">'.$reg->descripcion.'</textarea>',
              "2"=> date("d/m/Y", strtotime($reg->fecha_inicial)) .' - '. date("d/m/Y", strtotime($reg->fecha_final)),
              "3"=>number_format($reg->cantidad_persona, 2, '.', ','),
              "4"=>'S/ '.number_format($reg->monto, 2, '.', ','),
            ];
            
          }
          $results = [
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data,
          ];
          echo json_encode($results, true);

        break;

        case 'mostrar_comprobantes_pension':
          //$idpension_f ='5';
          //$_GET['idpension_f']
          $rspta = $resumen_general->listar_comprobantes_pension($_GET['idpension']);

          //Vamos a declarar un array
          $data = [];      

          while ($reg = $rspta['data']->fetch_object()) {

            $comprobante = empty($reg->comprobante)
              ? ( '<div><center><a type="btn btn-danger" class=""><i class="far fa-times-circle fa-2x"></i></a></center></div>')
              : ('<div><center><a type="btn btn-danger" target="_blank"  href="../dist/docs/pension/comprobante/' . $reg->comprobante . '" ><i class="fas fa-file-invoice fa-2x"></i></a></center></div>');

            $tool = '"tooltip"';
            $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>";

            $data[] = [
              "0" => empty($reg->forma_pago) ? ' - ' : $reg->forma_pago,
              "1" => (empty($reg->tipo_comprobante) ? ' - ' : $reg->tipo_comprobante ). (empty($reg->numero_comprobante) ? '' : ' ─ ' .$reg->numero_comprobante),
              "2" => date("d/m/Y", strtotime($reg->fecha_emision)),
              "3" => number_format($reg->subtotal, 2, '.', ','),
              "4" => number_format($reg->igv, 2, '.', ','),
              "5" => number_format($reg->precio_parcial, 2, '.', ','),
              "6" => '<textarea cols="30" rows="2" class="textarea_datatable" readonly="">'. $reg->descripcion.'</textarea>',
              "7" => $comprobante,
            ];
          }
          //$suma=array_sum($rspta->fetch_object()->monto);
          $results = [
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
            "data" => $data,
          ];
          echo json_encode($results, true);
        break;        
        
        // TABLA - BREACKS
        case 'tbla_breaks':

          $data = Array(); $datatable = Array();

          $deuda = $_POST['deuda'];

          $t_monto = 0;
          $t_pagos = 0;
          $t_saldo = 0;   
          $saldo_x_fila = 0;

          $rspta = $resumen_general->tabla_breaks($_POST['idproyecto'], $_POST['fecha_filtro_1'], $_POST['fecha_filtro_2']);

          foreach ($rspta['data'] as $key => $value) {

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
            'status' => true,
            'menssage' => 'todo oka psss',
            'data' =>[
              't_monto' => $t_monto, 
              't_pagos' => $t_pagos,
              't_saldo' => $t_saldo,
              'datatable' => $datatable
            ]
          );

          //Codificar el resultado utilizando json
          echo json_encode($data);

        break;

        // TABLA - COMIDA EXTRA
        case 'tbla_comidas_extras':

          $data = Array(); $datatable = Array();

          $deuda = $_POST['deuda'];

          $t_monto = 0;
          $t_pagos = 0;
          $t_saldo = 0;   
          $saldo_x_fila = 0;

          $rspta = $resumen_general->tabla_comidas_extras($_POST['idproyecto'], $_POST['fecha_filtro_1'], $_POST['fecha_filtro_2']);

          foreach ($rspta['data'] as $key => $value) {

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
                '2' => $value['fecha_comida'],
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
                  '2' => $value['fecha_comida'],
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
            'status' => true,
            'menssage' => 'todo oka psss',
            'data' =>[
              't_monto' => $t_monto, 
              't_pagos' => $t_pagos,
              't_saldo' => $t_saldo,
              'datatable' => $datatable
            ]
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

          while ($reg = $rspta['data']->fetch_object()) {
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
          echo json_encode($results, true);
        break; 
        
        // TABLA - PAGO ADMINSTRADOR
        case 'tbla_administrativo':

          $data = Array(); $datatable = Array();

          $deuda = $_POST['deuda'];

          $t_monto = 0;
          $t_pagos = 0;
          $t_saldo = 0;   
          $saldo_x_fila = 0;

          $rspta = $resumen_general->tabla_administrativo($_POST['idproyecto'], $_POST['id_trabajador']);

          foreach ($rspta['data'] as $key => $value) {

            //$saldo_x_fila = floatval($value['total_montos_x_meses']) - floatval($value['deposito']);
            $saldo_x_fila = 0;

            if ($deuda == '' || $deuda == null || $deuda == 'todos') {
              $datatable[] = array(
                '0' => $key+1, 
                '1' => $value['nombres'],
                '2' => '- - -',
                '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >- - -</textarea>',
                '4' => '<button class="btn btn-info btn-sm" onclick="mostrar_detalle_administrativo('.$value['idtrabajador_por_proyecto'] .', \'' .$value['nombres'].  '\')"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button',
                '5' => number_format($value['deposito'], 2, '.', ',' ),
                '6' => number_format($value['deposito'], 2, '.', ',' ),
                '7' => number_format($saldo_x_fila , 2, '.', ',' ),
              );
    
              $t_monto += floatval($value['deposito']);
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
                    '5' => number_format($value['deposito'], 2, '.', ',' ),
                    '6' => number_format($value['deposito'], 2, '.', ',' ),
                    '7' => number_format($saldo_x_fila , 2, '.', ',' ),
                  );
        
                  $t_monto += floatval($value['deposito']);
                  $t_pagos += floatval($value['deposito']);
                  $t_saldo += floatval($saldo_x_fila);
                }
              } 
              // else {
              //   if ($deuda == 'condeuda') {
              //     if ($saldo_x_fila > 0) {
              //       $datatable[] = array(
              //         '0' => $key+1, 
              //         '1' => $value['nombres'],
              //         '2' => '- - -',
              //         '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >- - -</textarea>',
              //         '4' => '<button class="btn btn-info btn-sm" onclick="mostrar_detalle_administrativo('.$value['idtrabajador_por_proyecto'] .', \'' .$value['nombres'].  '\')"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button',
              //         '5' => number_format($value['total_montos_x_meses'], 2, '.', ',' ),
              //         '6' => number_format($value['deposito'], 2, '.', ',' ),
              //         '7' => number_format($saldo_x_fila , 2, '.', ',' ),
              //       );
          
              //       $t_monto += floatval($value['total_montos_x_meses']);
              //       $t_pagos += floatval($value['deposito']);
              //       $t_saldo += floatval($saldo_x_fila);
              //     }
              //   }else{
              //     if ($deuda == 'conexcedente') {
              //       if ($saldo_x_fila < 0) {
              //         $datatable[] = array(
              //           '0' => $key+1, 
              //           '1' => $value['nombres'],
              //           '2' => '- - -',
              //           '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >- - -</textarea>',
              //           '4' => '<button class="btn btn-info btn-sm" onclick="mostrar_detalle_administrativo('.$value['idtrabajador_por_proyecto'] .', \'' .$value['nombres'].  '\')"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button',
              //           '5' => number_format($value['total_montos_x_meses'], 2, '.', ',' ),
              //           '6' => number_format($value['deposito'], 2, '.', ',' ),
              //           '7' => number_format($saldo_x_fila , 2, '.', ',' ),
              //         );
            
              //         $t_monto += floatval($value['total_montos_x_meses']);
              //         $t_pagos += floatval($value['deposito']);
              //         $t_saldo += floatval($saldo_x_fila);
              //       }
              //     }
              //   }
              // }            
            }
          }
          $data = array(
            'status' => true,
            'menssage' => 'todo oka psss',
            'data' =>[
              't_monto' => $t_monto, 
              't_pagos' => $t_pagos,
              't_saldo' => $t_saldo,
              'datatable' => $datatable
            ]
          );
          //Codificar el resultado utilizando json
          echo json_encode($data, true);

        break;

        case 'mostrar_detalle_administrativo':

          $rspta = $resumen_general->r_detalle_trab_administrativo($_POST['idtrabajador_por_proyecto']);

          //Codificar el resultado utilizando json
          echo json_encode($rspta);

        break;     
        
        // TABLA - PAGO OBRERO
        case 'tbla_obrero':

          $data = Array(); $datatable = Array();

          $deuda = $_POST['deuda'];

          $t_monto = 0;
          $t_pagos = 0;
          $t_saldo = 0;   
          $saldo_x_fila = 0;

          $rspta = $resumen_general->tabla_obrero($_POST['idproyecto'], $_POST['fecha_filtro_1'], $_POST['fecha_filtro_2']);
          
          foreach ($rspta['data'] as $key => $value) {

            // Convertir las fechas a objetos DateTime 
            $fechaInicio      = new DateTime($value['fecha_q_s_inicio']);
            $fechaFin         = new DateTime($value['fecha_q_s_fin']);

            // solo el dia ejem: 06 
            $f_i_dia          = date("d", strtotime($value['fecha_q_s_inicio']));
            $f_f_dia          = date("d", strtotime($value['fecha_q_s_fin']));

            $mes_f_i = nombre_mes($fechaInicio->format("Y-m-d")); 
            $mes_f_f = nombre_mes($fechaFin->format("Y-m-d"));   
        
            $del_al="$f_i_dia de $mes_f_i al $f_f_dia de $mes_f_f"; //fecha ejem : 23 DE ABRIL AL 28 DE ABRIL

            $s_q= $value['fecha_pago_obrero']=='semanal'? 'Semana N° : ':'Quincena N° : ';

            if ($deuda == '' || $deuda == null || $deuda == 'todos') {

              $datatable[] = array(
                '0' => $key+1, 
                '1' => $s_q.''. $value['numero_q_s'],
                '2' =>  $del_al,
                '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >- - -</textarea>',
                '4' => '<button class="btn btn-info btn-sm" onclick="" readonly><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button>',
                '5' => number_format($value['total_programado'], 2, '.', ',' ),
                '6' => number_format($value['total_deposito'], 2, '.', ',' ),
                '7' => number_format($value['saldo'] , 2, '.', ',' ),
              );
    
              $t_monto += floatval($value['total_programado']);
              $t_pagos += floatval($value['total_deposito']);
              $t_saldo += floatval($value['saldo']);
            } else {
              if ($deuda == 'sindeuda') {
                if ($value['saldo'] == 0) {
                  $datatable[] = array(
                    '0' => $key+1, 
                    '1' => $s_q.''. $value['numero_q_s'],
                    '2' =>  $del_al,
                    '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >- - -</textarea>',
                    '4' => '<button class="btn btn-info btn-sm" onclick="" readonly><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button>',
                    '5' => number_format($value['total_programado'], 2, '.', ',' ),
                    '6' => number_format($value['total_deposito'], 2, '.', ',' ),
                    '7' => number_format($value['saldo'] , 2, '.', ',' ),
                  );
        
                  $t_monto += floatval($value['total_programado']);
                  $t_pagos += floatval($value['total_deposito']);
                  $t_saldo += floatval($value['saldo']);
                }
              } else {
                if ($deuda == 'condeuda') {
                  if ($value['saldo'] > 0) {
                    $datatable[] = array(
                      '0' => $key+1, 
                      '1' => $s_q.''. $value['numero_q_s'],
                      '2' =>  $del_al,
                      '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >- - -</textarea>',
                      '4' => '<button class="btn btn-info btn-sm" onclick="" readonly><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button>',
                      '5' => number_format($value['total_programado'], 2, '.', ',' ),
                      '6' => number_format($value['total_deposito'], 2, '.', ',' ),
                      '7' => number_format($value['saldo'] , 2, '.', ',' ),
                    );
          
                    $t_monto += floatval($value['total_programado']);
                    $t_pagos += floatval($value['total_deposito']);
                    $t_saldo += floatval($value['saldo']);
                  }
                }else{
                  if ($deuda == 'conexcedente') {
                    if ($value['saldo'] < 0) {
                      $datatable[] = array(
                        '0' => $key+1, 
                        '1' => $s_q.''. $value['numero_q_s'],
                        '2' =>  $del_al,
                        '3' => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >- - -</textarea>',
                        '4' => '<button class="btn btn-info btn-sm" onclick="" readonly><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button>',
                        '5' => number_format($value['total_programado'], 2, '.', ',' ),
                        '6' => number_format($value['total_deposito'], 2, '.', ',' ),
                        '7' => number_format($value['saldo'] , 2, '.', ',' ),
                      );
            
                      $t_monto += floatval($value['total_programado']);
                      $t_pagos += floatval($value['total_deposito']);
                      $t_saldo += floatval($value['saldo']);
                    }
                  }
                }
              }            
            }
          }
          $data = array(
            'status' => true,
            'menssage' => 'todo oka psss',
            'data' =>[
              't_monto' => $t_monto, 
              't_pagos' => $t_pagos,
              't_saldo' => $t_saldo,
              'datatable' => $datatable
            ]
          );

          //Codificar el resultado utilizando json
          echo json_encode($data);

        break;

        case 'mostrar_detalle_obrero':

          $rspta = $resumen_general->r_detalle_x_obrero($_POST['idtrabajador_por_proyecto']);

          //Codificar el resultado utilizando json
          echo json_encode($rspta);

        break;         

        // Select2 - Proveedores
        case 'select2_proveedores':

          $rspta = $resumen_general->select_proveedores();

          $estado = true;

          while ($reg = $rspta['data']->fetch_object()) {

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

          while ($reg = $rspta['data']->fetch_object()) {

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
