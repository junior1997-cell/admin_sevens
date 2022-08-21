<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {

    if ($_SESSION['servicio_equipo'] == 1) {

      require_once "../modelos/Servicio_equipos.php";
      require_once "../modelos/Fechas.php";
      $servicioequipos = new ServicioEquipos();
      
      
      date_default_timezone_set('America/Lima');
      $date_now = date("d-m-Y h.i.s A");


      //============SERVICIOS========================

      $idservicio        = isset($_POST["idservicio"]) ? limpiarCadena($_POST["idservicio"]) : "";
      $idproyecto        = isset($_POST["idproyecto"]) ? limpiarCadena($_POST["idproyecto"]) : "";
      $maquinaria        = isset($_POST["maquinaria"]) ? limpiarCadena($_POST["maquinaria"]) : "";
      $fecha_inicio      = isset($_POST["fecha_inicio"]) ? limpiarCadena($_POST["fecha_inicio"]) : "";
      $fecha_fin         = isset($_POST["fecha_fin"]) ? limpiarCadena($_POST["fecha_fin"]) : "";
      $horometro_inicial = isset($_POST["horometro_inicial"]) ? limpiarCadena($_POST["horometro_inicial"]) : "";
      $horometro_final   = isset($_POST["horometro_final"]) ? limpiarCadena($_POST["horometro_final"]) : "";
      $horas             = isset($_POST["horas"]) ? limpiarCadena($_POST["horas"]) : "";
      $costo_unitario    = isset($_POST["costo_unitario"]) ? limpiarCadena($_POST["costo_unitario"]) : "";
      $cantidad          = isset($_POST["cantidad"]) ? limpiarCadena($_POST["cantidad"]) : "";
      $costo_adicional   = isset($_POST["costo_adicional"]) ? limpiarCadena($_POST["costo_adicional"]) : "";
      $costo_parcial     = isset($_POST["costo_parcial"]) ? limpiarCadena($_POST["costo_parcial"]) : "";
      $unidad_m          = isset($_POST["unidad_m"]) ? limpiarCadena($_POST["unidad_m"]) : "";
      $dias              = isset($_POST["dias"]) ? limpiarCadena($_POST["dias"]) : "";
      $mes               = isset($_POST["mes"]) ? limpiarCadena($_POST["mes"]) : "";
      $descripcion       = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";

      //============PAGOS========================

      $beneficiario_pago   = isset($_POST["beneficiario_pago"]) ? limpiarCadena($_POST["beneficiario_pago"]) : "";
      $forma_pago          = isset($_POST["forma_pago"]) ? limpiarCadena($_POST["forma_pago"]) : "";
      $tipo_pago           = isset($_POST["tipo_pago"]) ? limpiarCadena($_POST["tipo_pago"]) : "";
      $cuenta_destino_pago = isset($_POST["cuenta_destino_pago"]) ? limpiarCadena($_POST["cuenta_destino_pago"]) : "";
      $banco_pago          = isset($_POST["banco_pago"]) ? limpiarCadena($_POST["banco_pago"]) : "";
      $titular_cuenta_pago = isset($_POST["titular_cuenta_pago"]) ? limpiarCadena($_POST["titular_cuenta_pago"]) : "";
      $fecha_pago          = isset($_POST["fecha_pago"]) ? limpiarCadena($_POST["fecha_pago"]) : "";
      $monto_pago          = isset($_POST["monto_pago"]) ? limpiarCadena($_POST["monto_pago"]) : "";
      $numero_op_pago      = isset($_POST["numero_op_pago"]) ? limpiarCadena($_POST["numero_op_pago"]) : "";
      $descripcion_pago    = isset($_POST["descripcion_pago"]) ? limpiarCadena($_POST["descripcion_pago"]) : "";
      $id_maquinaria_pago  = isset($_POST["id_maquinaria_pago"]) ? limpiarCadena($_POST["id_maquinaria_pago"]) : "";
      $idpago_servicio     = isset($_POST["idpago_servicio"]) ? limpiarCadena($_POST["idpago_servicio"]) : "";
      $idproyecto_pago     = isset($_POST["idproyecto_pago"]) ? limpiarCadena($_POST["idproyecto_pago"]) : "";

      $imagen1             = isset($_POST["doc1"]) ? limpiarCadena($_POST["doc1"]) : "";

      //============factura========================

      $idproyectof    = isset($_POST["idproyectof"]) ? limpiarCadena($_POST["idproyectof"]) : "";
      $idfactura      = isset($_POST["idfactura"]) ? limpiarCadena($_POST["idfactura"]) : "";
      $idmaquina      = isset($_POST["idmaquina"]) ? limpiarCadena($_POST["idmaquina"]) : "";
      $codigo         = isset($_POST["codigo"]) ? limpiarCadena($_POST["codigo"]) : "";
      $monto          = isset($_POST["monto"]) ? limpiarCadena($_POST["monto"]) : "";
      $fecha_emision  = isset($_POST["fecha_emision"]) ? limpiarCadena($_POST["fecha_emision"]) : "";
      $descripcion_f  = isset($_POST["descripcion_f"]) ? limpiarCadena($_POST["descripcion_f"]) : "";
      $subtotal       = isset($_POST["subtotal"]) ? limpiarCadena($_POST["subtotal"]) : "";
      $igv            = isset($_POST["igv"]) ? limpiarCadena($_POST["igv"]) : "";
      $nota           = isset($_POST["nota"]) ? limpiarCadena($_POST["nota"]) : "";
      $val_igv          = isset($_POST["val_igv"])? limpiarCadena($_POST["val_igv"]):"";
      $tipo_gravada     = isset($_POST["tipo_gravada"])? limpiarCadena($_POST["tipo_gravada"]):"";  

      $imagen2 = isset($_POST["doc2"]) ? limpiarCadena($_POST["doc2"]) : "";

      switch ($_GET["op"]) {
        //---------------------------------------------------------------------------------
        //----------------------T A B L A   P R I N C I P A L------------------------------
        //---------------------------------------------------------------------------------
        
        case 'listar':

          $nube_idproyecto = $_GET["nube_idproyecto"];
          $rspta = $servicioequipos->listar($nube_idproyecto);
          //Vamos a declarar un array
          setlocale(LC_MONETARY, 'en_US');
          $data = [];

          $c = ""; $nombre = ""; $icon = "";
          $cc = ""; $nombree = ""; $icons = "";
          $fecha_i="";  $fecha_f=""; 
          $cont = 1;

          if ($rspta['status'] == true) {

            foreach ($rspta['data'] as $key => $reg) {


              if ($reg['saldo'] == $reg['costo_parcial']) {

                $estado = '<span class="text-center badge badge-danger">Sin pagar</span>';
                $c = "danger"; $nombre = "Pagar"; $icon = "dollar-sign"; 
              } else {

                if ($reg['saldo'] < $reg['costo_parcial'] && $reg['saldo'] > "0") {

                  $estado = '<span class="text-center badge badge-warning">En proceso</span>';
                  $c = "warning"; $nombre = "Pagar"; $icon = "dollar-sign";

                } else {

                  if ($reg['saldo'] <= "0" || $reg['saldo'] == "0") {

                    $estado = '<span class="text-center badge badge-success">Pagado</span>';
                    $c = "success"; $nombre = "Ver"; $info = "success"; $icon = "eye";

                  } else {
                    $estado = '<span class="text-center badge badge-success">Error</span>';
                  }
                }
              }

              if ($reg['saldo_factura'] == $reg['costo_parcial']) {
                
                $cc = "danger";
              } else {
                if ($reg['saldo_factura'] < $reg['costo_parcial'] && $reg['saldo_factura'] > "0") {
                  $cc = "warning";

                } else {
                  if ($reg['saldo_factura'] <= "0") {
                    $cc = "success"; $info = "success"; $icons = "eye";
                  }
                }
              }

              $verdatos = '\''.$reg['idmaquinaria'].'\', \''.$reg['idproyecto'].'\', \''.$reg['costo_parcial'].'\', \''.$reg['total_pagos'].'\', \''.$reg['maquina'].'\', \''.$fecha_i.'\', \''.$fecha_f.'\'';

              $unidad_medida = '\''.$reg['idmaquinaria'].'\', \''.$reg['idproyecto'].'\', \''.$reg['unidad_medida'].'\', \''.$reg['maquina'].'\', \''. $fecha_i.'\', \''. $fecha_f.'\'';

              $data[] = [
                "0" => $cont++,
                "1" => ' <button class="btn btn-info btn-sm" onclick="listar_detalle(' . $unidad_medida . '); mostrar_form_table(2); show_hide_filtro();"><i class="far fa-eye"></i></button>',
                "2" =>'<div class="user-block">
                        <span class="username" style="margin-left: 0px !important;"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >' .$reg['maquina'] .'</p></span>
                        <span class="description" style="margin-left: 0px !important;">' .$reg['codigo_maquina'].' </span>
                      </div>',
                "3" => $reg['razon_social'],
                "4" => $reg['unidad_medida'],
                "5" => $reg['cantidad_veces'],
                "6" =>'S/ '. number_format($reg['costo_parcial'], 2, '.', ','),
                "7" =>'<div class="text-center text-nowrap"> 
                        <button class="btn btn-' .$c .' btn-xs" onclick="listar_pagos(' .$verdatos.'); mostrar_form_table(3); show_hide_filtro();"><i class="fas fa-' .$icon .' nav-icon"></i> ' .$nombre .'</button> ' .
                        '<button style="font-size: 14px;" class="btn btn-' .$c .' btn-xs">' .number_format($reg['total_pagos'], 2, '.', ',') .'</button> 
                      </div>',
                "8" => number_format($reg['saldo'], 2, '.', ','),
                "9" =>'<div class="text-center text-nowrap">
                        <button class="btn btn-' .$cc .' btn-sm" onclick="listar_facturas(' .$unidad_medida .'); mostrar_form_table(4); show_hide_filtro();"><i class="fas fa-file-invoice fa-lg btn-' .$cc .' nav-icon"></i></button> ' .
                        ' <button style="font-size: 14px;" class="btn btn-' . $cc . ' btn-sm">' . number_format($reg['total_comprob_fact'], 2, '.', ',') . '</button> 
                      </div>',
                "10" => $estado,

              ];
            }
            $results = [
              "sEcho" => 1, //Información para el datatables
              "iTotalRecords" => count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
              "data" => $data,
            ];
            echo json_encode($results);

          } else {
            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          } 
        break;

        //-------------------------------------------------------------------------------
        //----------------------S E C C   F U N C I O N E S  P O R  S E R V--------------
        //-------------------------------------------------------------------------------

        case 'guardaryeditar':
  
          if (empty($idservicio)) {

            $rspta = $servicioequipos->insertar($idproyecto, $maquinaria, $fecha_inicio, $fecha_fin, $horometro_inicial, $horometro_final, $horas, $costo_unitario,
              $costo_adicional, $costo_parcial, $unidad_m, $dias, $mes, $descripcion, $cantidad );
            echo json_encode($rspta);

          } else {

            $rspta = $servicioequipos->editar( $idservicio, $idproyecto, $maquinaria, $fecha_inicio, $fecha_fin, $horometro_inicial, $horometro_final, $horas, 
            $costo_unitario, $costo_adicional, $costo_parcial, $unidad_m, $dias, $mes, $descripcion, $cantidad );

          echo json_encode($rspta);
          }

        break;
  
        case 'desactivar':

          $rspta = $servicioequipos->desactivar($_GET['id_tabla']);
          echo json_encode($rspta,true);

        break;
  
        case 'eliminar':

          $rspta = $servicioequipos->eliminar($_GET['id_tabla']);
          echo json_encode($rspta,true);

        break;
  
        case 'activar':

          $rspta = $servicioequipos->activar($idservicio);
          echo json_encode($rspta,true);

        break;
  
        case 'mostrar':

          $rspta = $servicioequipos->mostrar($idservicio);
          echo json_encode($rspta,true);

        break;
  
        case 'ver_detalle_maquina':

          //$idmaquinaria = $_GET["idmaquinaria"];$idproyecto = $_GET["idproyecto"];$fecha_i = $_GET["fecha_i"];$fecha_f = $_GET["fecha_f"];$proveedor = $_GET["proveedor"]; $comprobante = $_GET["comprobante"];

          $rspta = $servicioequipos->ver_detalle_m($_GET["idmaquinaria"], $_GET["idproyecto"],$_GET["fecha_i"],$_GET["fecha_f"]);
          $fecha_entreg = '';
          $fecha_recoj = '';
          $fecha = '';
          //Vamos a declarar un array
          $data = [];
          $cont = 1;
          $f_eliminar="";

          if ($rspta['status'] == true) {

            while ($reg = $rspta['data']->fetch_object()) {

              if (empty($reg->fecha_recojo) || $reg->fecha_recojo == '0000-00-00') {

                $fecha_entreg = nombre_dia_semana($reg->fecha_entrega);

                $fecha = '<b class="text-primary">' . $fecha_entreg . ', ' . format_d_m_a($reg->fecha_entrega) . '</b>';

              } else {

                $fecha_entreg = nombre_dia_semana($reg->fecha_entrega);

                $fecha_recoj = nombre_dia_semana($reg->fecha_recojo);

                $fecha = '<b class="text-primary">' . $fecha_entreg . ', ' . format_d_m_a($reg->fecha_entrega) . ' </b> / <br> <b  class="text-danger"> ' . $fecha_recoj . ', ' . format_d_m_a($reg->fecha_recojo) . '<b>';

              }

              $f_eliminar = '\''.format_d_m_a($reg->fecha_entrega).'\',\''.format_d_m_a($reg->fecha_recojo).'\'';

              $tool = '"tooltip"';
              $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>";

              $data[] = [
                "0" => $cont++,
                "1" => '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->idservicio . ',' . $reg->idmaquinaria . ')"><i class="fas fa-pencil-alt"></i></button>' .
                    ' <button class="btn btn-danger btn-sm" onclick="eliminar(' . $reg->idservicio . ',' . $reg->idmaquinaria . ',' . $f_eliminar. ')"><i class="fas fa-skull-crossbones"></i></button>',
                "2" => $fecha,
                "3" => empty($reg->horometro_inicial) || $reg->horometro_inicial == '0.00' ? '-' : $reg->horometro_inicial,
                "4" => empty($reg->horometro_final) || $reg->horometro_final == '0.00' ? '-' : $reg->horometro_final,
                "5" => empty($reg->horas) || $reg->horas == '0.00' ? '-' : $reg->horas,
                "6" => empty($reg->costo_unitario) || $reg->costo_unitario == '0.00' ?  'S/ 0.00 ' :'S/ '. number_format($reg->costo_unitario, 2, '.', ','),
                "7" => empty($reg->unidad_medida) ? '-' : $reg->unidad_medida,
                "8" => empty($reg->cantidad) ? '-' : $reg->cantidad,
                "9" => empty($reg->costo_parcial) ? 'S/ 0.00' : 'S/ '. number_format($reg->costo_parcial, 2, '.', ','),
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
          } else {
            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          } 

        break;
  
        case 'total_costo_parcial_detalle':
  
          $rspta = $servicioequipos->total_costo_parcial_detalle($_POST["idmaquinaria"], $_POST["idproyecto"],$_POST["fecha_i"], $_POST["fecha_f"]);

          echo json_encode($rspta,true);
  
        break;
    
        //-------------------------------------------------------------------------------
        //----------------------S E C C   P A G O  P O R  S E R V------------------------
        //-------------------------------------------------------------------------------
        
        case 'most_datos_prov_pago':

          $idmaquinaria = $_POST["idmaquinaria"];
          $rspta = $servicioequipos->most_datos_prov_pago($idmaquinaria);
          echo json_encode($rspta,true);

        break;
  
        case 'guardaryeditar_pago':

          // imgen de perfil
          if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {
            $imagen1 = $_POST["doc_old_1"];
            $flat_img1 = false;
          } else {
            $ext1 = explode(".", $_FILES["doc1"]["name"]);
            $flat_img1 = true;

            $imagen1 = $date_now.''.rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

            move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/servicio_equipo/comprobante_pago/" . $imagen1);
          }

          if (empty($idpago_servicio)) {

            $rspta = $servicioequipos->insertar_pago( $idproyecto_pago, $beneficiario_pago, $forma_pago, $tipo_pago, $cuenta_destino_pago, $banco_pago,
              $titular_cuenta_pago, $fecha_pago, $monto_pago, $numero_op_pago, $descripcion_pago, $id_maquinaria_pago, $imagen1 );

            echo json_encode($rspta,true);

          } else {

            if ($flat_img1 == true) {
              $datos_f1 = $servicioequipos->obtenerImg($idpago_servicio);

              $img1_ant = $datos_f1['data']->fetch_object()->imagen;

              if ($img1_ant != "") {
                unlink("../dist/docs/servicio_equipo/comprobante_pago/" . $img1_ant);
              }
            }

            $rspta = $servicioequipos->editar_pago($idpago_servicio, $idproyecto_pago, $beneficiario_pago, $forma_pago, $tipo_pago, $cuenta_destino_pago, $banco_pago,
              $titular_cuenta_pago, $fecha_pago, $monto_pago, $numero_op_pago, $descripcion_pago, $id_maquinaria_pago, $imagen1 );

              echo json_encode($rspta,true);
          }

        break;
  
        case 'desactivar_pagos':

          $rspta = $servicioequipos->desactivar_pagos($_GET['id_tabla']);
          echo json_encode($rspta,true);

        break;
  
        case 'activar_pagos':

          $rspta = $servicioequipos->activar_pagos($_GET['id_tabla']);
          echo json_encode($rspta,true);

        break;
  
        case 'eliminar_pagos':

          $rspta = $servicioequipos->eliminar_pagos($_GET['id_tabla']);
          echo json_encode($rspta,true);

        break;
  
        case 'listar_pagos_proveedor':

          $idmaquinaria = $_GET["idmaquinaria"];
          $idproyecto = $_GET["idproyecto"];
          $tipopago = 'Proveedor';

          $rspta = $servicioequipos->listar_pagos($idmaquinaria, $idproyecto, $tipopago,$_GET["fecha_i"],$_GET["fecha_f"]);

          $data = [];
          $suma = 0;
          $imagen = '';
          $fecha_i="";  $fecha_f=""; 
          $cont = 1;

          if ($rspta['status'] == true) {

            while ($reg = $rspta['data']->fetch_object()) {
              $suma = $suma + $reg->monto;
              
              empty($reg->imagen)
                ? ($imagen = '<div><center><a type="btn btn-danger" class=""><i class="fas fa-file-invoice-dollar fa-2x text-gray-50"></i></a></center></div>')
                : ($imagen = '<div><center><a type="btn btn-danger" class=""  href="#" onclick="ver_modal_vaucher(' . "'" . $reg->imagen . "'" . ',' . "'" . $reg->numero_operacion . "'" . ')"><i class="fas fa-file-invoice-dollar fa-2x"></i></a></center></div>');
             
              $tool = '"tooltip"';
              $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>";
             
              $data[] = [
                "0" => $cont++,
                "1" => '<button class="btn btn-warning btn-sm" onclick="mostrar_pagos(' .$reg->idpago_servicio .',' .$reg->id_maquinaria .')"><i class="fas fa-pencil-alt"></i></button>' .
                    ' <button class="btn btn-danger btn-sm" onclick="eliminar_pagos(' .$reg->idpago_servicio .',' .$reg->id_maquinaria .',' . "'" . $reg->numero_operacion . "'" . ',' . "'" . $fecha_i . "'" . ',' . "'" . $fecha_f . "'" . ' )"><i class="fas fa-skull-crossbones"></i></button>',
                "2" => $reg->forma_pago,
                "3" => '<div class="user-block">
                  <span class="username ml-0"><p class="text-primary m-b-02rem" >'. $reg->beneficiario .'</p></span>
                  <span class="description ml-0"><b>'. $reg->banco .'</b>: '. $reg->cuenta_destino .' </span>
                  <span class="description ml-0"><b>Titular: </b>: '. $reg->titular_cuenta .' </span>            
                </div>',
                "4" => date("d/m/Y", strtotime($reg->fecha_pago)),
                "5" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.(empty($reg->descripcion) ? '- - -' : $reg->descripcion ).'</textarea>',
                "6" => $reg->numero_operacion,
                "7" =>'S/ '. number_format($reg->monto, 2, '.', ','),
                "8" => $imagen,
                "9" => $reg->estado ? '<span class="text-center badge badge-success">Activado</span>' . $toltip : '<span class="text-center badge badge-danger">Desactivado</span>' . $toltip,
              ];
            }

            $results = [
              "sEcho" => 1, //Información para el datatables
              "iTotalRecords" => count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
              "data" => $data,
              "suma" => $suma,
            ];
            echo json_encode($results);
          } else {
            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          } 
        break;
  
        case 'listar_pagos_detraccion':

          $idmaquinaria = $_GET["idmaquinaria"];
          $idproyecto = $_GET["idproyecto"];
          $tipopago = 'Detraccion';

          $rspta = $servicioequipos->listar_pagos($idmaquinaria, $idproyecto, $tipopago,$_GET["fecha_i"],$_GET["fecha_f"]);
          $data = [];
          $suma = 0;
          $imagen = '';
          $fecha_i="";  $fecha_f="";
          $cont = 1;

          if ($rspta['status'] == true) {

            while ($reg = $rspta['data']->fetch_object()) {
              $suma = $suma + $reg->monto;
              
              empty($reg->imagen)
                ? ($imagen = '<div><center><a type="btn btn-danger" class=""><i class="fas fa-file-invoice-dollar fa-2x text-gray-50"></i></a></center></div>')
                : ($imagen = '<div><center><a type="btn btn-danger" class=""  href="#" onclick="ver_modal_vaucher(' . "'" . $reg->imagen . "'" . ')"><i class="fas fa-file-invoice-dollar fa-2x"></i></a></center></div>');
              $tool = '"tooltip"';
              $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>";
              $data[] = [
                "0" => $cont++,
                "1" => '<button class="btn btn-warning btn-sm" onclick="mostrar_pagos(' . $reg->idpago_servicio . ',' .$reg->id_maquinaria . ')"><i class="fas fa-pencil-alt"></i></button>' . 
                    ' <button class="btn btn-danger btn-sm" onclick="eliminar_pagos(' . $reg->idpago_servicio . ',' . $reg->id_maquinaria . ')"><i class="fas fa-skull-crossbones"></i></button>',

                "2" => $reg->forma_pago,
                "3" => '<div class="user-block">
                  <span class="username ml-0"><p class="text-primary m-b-02rem" >'. $reg->beneficiario .'</p></span>
                  <span class="description ml-0"><b>'. $reg->banco .'</b>: '. $reg->cuenta_destino .' </span>
                  <span class="description ml-0"><b>Titular: </b>: '. $reg->titular_cuenta .' </span>            
                </div>',
                "4" => date("d/m/Y", strtotime($reg->fecha_pago)),
                "5" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.(empty($reg->descripcion) ? '- - -' : $reg->descripcion ).'</textarea>',
                "6" => $reg->numero_operacion,
                "7" =>'S/ '. number_format($reg->monto, 2, '.', ','),
                "8" => $imagen,
                "9" => $reg->estado ? '<span class="text-center badge badge-success">Activado</span>' . $toltip : '<span class="text-center badge badge-danger">Desactivado</span>' . $toltip,
             
              ];
            }

            $results = [
              "sEcho" => 1, //Información para el datatables
              "iTotalRecords" => count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
              "data" => $data,
              "suma" => $suma,
            ];
            echo json_encode($results);

          } else {
            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          } 

        break;
  
        case 'suma_total_pagos_proveedor':

          $idmaquinaria = $_POST["idmaquinaria"];
          $idproyecto = $_POST["idproyecto"];
          $tipopago = 'Proveedor';

          $rspta = $servicioequipos->suma_total_pagos($idmaquinaria, $idproyecto, $tipopago,$_POST["fecha_i"],$_POST["fecha_f"]);
          echo json_encode($rspta,true);

        break;
  
        case 'suma_total_pagos_detracc':

          $idmaquinaria = $_POST["idmaquinaria"];
          $idproyecto = $_POST["idproyecto"];
          $tipopago = 'Detraccion';
  
          $rspta = $servicioequipos->suma_total_pagos($idmaquinaria, $idproyecto, $tipopago,$_POST["fecha_i"],$_POST["fecha_f"]);
          echo json_encode($rspta,true);

  
        break;
  
        case 'total_costo_parcial_pago':

          $idmaquinaria = $_POST["idmaquinaria"];
          $idproyecto = $_POST["idproyecto"];
  
          $rspta = $servicioequipos->total_costo_parcial_pago($idmaquinaria, $idproyecto);
          echo json_encode($rspta,true);
  
        break;
  
        case 'mostrar_pagos':

          $rspta = $servicioequipos->mostrar_pagos($idpago_servicio);
          echo json_encode($rspta,true);

        break;

        //-------------------------------------------------------------------------------
        //----------------------S E C C   F A C T U R A S--------------------------------
        //-------------------------------------------------------------------------------

        case 'guardaryeditar_factura':

          // imgen de perfil
          if (!file_exists($_FILES['doc2']['tmp_name']) || !is_uploaded_file($_FILES['doc2']['tmp_name'])) {
            $imagen2 = $_POST["doc_old_2"];
            $flat_img1 = false;
          } else {
            $ext1 = explode(".", $_FILES["doc2"]["name"]);
            $flat_img1 = true;

            $imagen2 = $date_now.''.rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

            move_uploaded_file($_FILES["doc2"]["tmp_name"], "../dist/docs/servicio_equipo/comprobante_servicio/" . $imagen2);
          }

          if (empty($idfactura)) {
            $rspta = $servicioequipos->insertar_factura($idproyectof, $idmaquina, $codigo, $monto, $fecha_emision, $descripcion_f, $imagen2, $subtotal, $igv, $val_igv, $tipo_gravada, $nota);
            echo json_encode($rspta,true);
          } else {

            if ($flat_img1 == true) {
              $datos_f1 = $servicioequipos->obtenerDoc($idfactura);

              $img1_ant = $datos_f1['data']->fetch_object()->imagen;

              if ($img1_ant != "") {
                unlink("../dist/docs/servicio_equipo/comprobante_servicio/" . $img1_ant);
              }
            }

            $rspta = $servicioequipos->editar_factura($idfactura, $idproyectof, $idmaquina, $codigo, $monto, $fecha_emision, $descripcion_f, $imagen2, $subtotal, $igv, $val_igv, $tipo_gravada, $nota);

            echo json_encode($rspta,true);
          }

        break;
  
        case 'listar_facturas':

          $idmaquinaria = $_GET["idmaquinaria"];
          $idproyecto = $_GET["idproyecto"];

          $rspta = $servicioequipos->listar_facturas($idmaquinaria, $idproyecto,$_GET["fecha_i"],$_GET["fecha_f"]);

          $data = [];
          $suma = 0;
          $imagen = '';

          $cont = 1;

          if ($rspta['status'] == true) {

            while ($reg = $rspta['data']->fetch_object()) {

              $suma = $suma + $reg->monto;
              
              empty($reg->imagen)
                ? ($imagen = '<div><center><a type="btn btn-danger" class=""><i class="fas fa-file-invoice-dollar fa-2x text-gray-50"></i></a></center></div>')
                : ($imagen = '<div><center><a type="btn btn-danger" class=""  href="#" onclick="ver_modal_factura(' . "'" . $reg->imagen . "'" . ',' . "'" . $reg->codigo . "'" . ')"><i class="fas fa-file-invoice fa-2x"></i></a></center></div>');
              $tool = '"tooltip"';

              $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>";

              $data[] = [
                "0" => $cont++,
                "1" => '<button class="btn btn-warning btn-sm" onclick="mostrar_factura(' . $reg->idfactura . ')"><i class="fas fa-pencil-alt"></i></button>' .
                    ' <button class="btn btn-danger btn-sm" onclick="eliminar_factura(' . $reg->idfactura . ',' . "'" . $reg->codigo . "'" . ')"><i class="fas fa-skull-crossbones"></i></button>',
                "2" => $reg->codigo,
                "3" => date("d/m/Y", strtotime($reg->fecha_emision)),            
                "4" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.(empty($reg->nota) ? '- - -' : $reg->nota ).'</textarea>',
                "5" => 'S/ '.number_format($reg->subtotal, 2, '.', ','),
                "6" => 'S/ '.number_format($reg->igv, 2, '.', ','),
                "7" => 'S/ '.number_format($reg->monto, 2, '.', ','),
                "8" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.(empty($reg->descripcion) ? '- - -' : $reg->descripcion ).'</textarea>',
                "9" => $imagen,
                "10" => $reg->estado ? '<span class="text-center badge badge-success">Activado</span>' . $toltip : '<span class="text-center badge badge-danger">Desactivado</span>' . $toltip,
              ];
            }

            $results = [
              "sEcho" => 1, //Información para el datatables
              "iTotalRecords" => count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
              "data" => $data,
              "suma" => $suma,
            ];
            echo json_encode($results);

          } else {
            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          } 
        break;
  
        case 'desactivar_factura':

          $rspta = $servicioequipos->desactivar_factura($_GET['id_tabla']);
          echo json_encode($rspta,true);

        break;
  
        case 'activar_factura':

          $rspta = $servicioequipos->activar_factura($idfactura);
          echo json_encode($rspta,true);

        break;
  
        case 'eliminar_factura':

          $rspta = $servicioequipos->eliminar_factura($_GET['id_tabla']);
          echo json_encode($rspta,true);

        break;
  
        case 'mostrar_factura':

          $rspta = $servicioequipos->mostrar_factura($idfactura);
          echo json_encode($rspta,true);

        break;
  
        case 'total_monto_f':
          $idmaquinaria = $_POST["idmaquinaria"];
          $idproyecto = $_POST["idproyecto"];
  
          $rspta = $servicioequipos->total_monto_f($idmaquinaria, $idproyecto,$_POST["fecha_i"],$_POST["fecha_f"]);
          echo json_encode($rspta,true);
  
        break;
  
        case 'total_costo_parcial':
          $idmaquinaria = $_POST["idmaquinaria"];
          $idproyecto = $_POST["idproyecto"];

          $rspta = $servicioequipos->total_costo_parcial($idmaquinaria, $idproyecto);
          echo json_encode($rspta,true);
  
        break;
  
        case 'select2Banco':
          $rspta = $servicioequipos->select2_banco();
  
          if ($rspta['status'] == true) {

            while ($reg = $rspta['data']->fetch_object()) {
              echo '<option value=' . $reg->id . '>' . $reg->nombre . (empty($reg->alias) ? "" : " - $reg->alias") . '</option>';
            }
          } else {
            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          } 
        break;
  
        case 'formato_banco':
          $rspta = $servicioequipos->formato_banco($_POST["idbanco"]);
          echo json_encode($rspta,true);
  
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
