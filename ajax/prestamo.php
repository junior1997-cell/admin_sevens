<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {

    if ($_SESSION['recurso'] == 1) {
      
      require_once "../modelos/Prestamos.php";

      $prestamo = new Prestamos();

      date_default_timezone_set('America/Lima');
      $date_now = date("d-m-Y h.i.s A");

      $idprestamo = isset($_POST["idprestamo"]) ? limpiarCadena($_POST["idprestamo"]) : "";
      $id_proyecto_prestamo = isset($_POST["id_proyecto_prestamo"]) ? limpiarCadena($_POST["id_proyecto_prestamo"]) : "";

      $entidad_prestamo = isset($_POST["entidad_prestamo"]) ? limpiarCadena($_POST["entidad_prestamo"] ) : "";
      $fecha_inicio_prestamo = isset($_POST["fecha_inicio_prestamo"]) ? limpiarCadena($_POST["fecha_inicio_prestamo"] ) : "";
      $fecha_fin_prestamo = isset($_POST["fecha_fin_prestamo"]) ? limpiarCadena($_POST["fecha_fin_prestamo"] ) : "";
      $monto_prestamo = isset($_POST["monto_prestamo"]) ? limpiarCadena($_POST["monto_prestamo"]) : "";
      $descripcion_prestamo = isset($_POST["descripcion_prestamo"]) ? limpiarCadena($_POST["descripcion_prestamo"]) : ""; 

      //$idprestamo,$id_proyecto_prestamo,$entidad_prestamo,$fecha_inicio_prestamo,$fecha_fin_prestamo,$monto_prestamo,$descripcion_prestamo

      $idpago_prestamo = isset($_POST["idpago_prestamo"]) ? limpiarCadena($_POST["idpago_prestamo"]) : ""; 
      $idprestamo_p = isset($_POST["idprestamo_p"]) ? limpiarCadena($_POST["idprestamo_p"]) : ""; 
      $fecha_pago_p = isset($_POST["fecha_pago_p"]) ? limpiarCadena($_POST["fecha_pago_p"]) : ""; 
      $monto_pago_p = isset($_POST["monto_pago_p"]) ? limpiarCadena($_POST["monto_pago_p"]) : ""; 
      $descripcion_pago_p = isset($_POST["descripcion_pago_p"]) ? limpiarCadena($_POST["descripcion_pago_p"]) : ""; 
      $imagen1 = isset($_POST["doc1"]) ? limpiarCadena($_POST["doc1"]) : "";

      //$idpago_prestamo,$idprestamo_p,$fecha_pago_p,$monto_pago_p,$descripcion_pago_p,$imagen1
      switch ($_GET["op"]) {
        // ========= ============= ================== ============
          //:::: S E C C I Ó N   D E   P R É S T A M O S ::::::
        // ========= ============= ================== ============
        case 'guardar_y_editar_prestamo':

          if (empty($idprestamo)) {
            
            $rspta = $prestamo->insertar($id_proyecto_prestamo,$entidad_prestamo,$fecha_inicio_prestamo,$fecha_fin_prestamo,$monto_prestamo,$descripcion_prestamo);
            
            echo json_encode( $rspta, true);

          } else {
            $rspta = $prestamo->editar($idprestamo,$id_proyecto_prestamo,$entidad_prestamo,$fecha_inicio_prestamo,$fecha_fin_prestamo,$monto_prestamo,$descripcion_prestamo);
            
            echo json_encode( $rspta, true) ;
          }
        break;
    
        case 'desactivar':

          $rspta = $prestamo->desactivar( $_GET["id_tabla"] );

          echo json_encode( $rspta, true) ;

        break;      

        case 'eliminar':

          $rspta = $prestamo->eliminar( $_GET["id_tabla"] );

          echo json_encode( $rspta, true) ;

        break;
    
        case 'mostrar_prestamo':

          $rspta = $prestamo->mostrar_prestamo($_POST['idprestamo']);
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true) ;

        break;
    
        case 'tbla_prestamos':

          $rspta = $prestamo->tbla_prestamos( $_GET['idproyecto'] );
          //Vamos a declarar un array
          $data = [];
          $cont=1;

          $c = "";
          $nombre = "";
          $icon = "";
          $cc = "";
          $deuda =0;
          $descrip_toltip="Clic en botón para entrar el detalle de pagos";
          $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

          if ($rspta['status'] == true) {

            foreach ($rspta['data'] as $key => $reg) {

              $deuda = $reg['monto'] - $reg['total_pago_compras'];       
      
              if ($deuda == $reg['monto']) {

                $c = "danger";
                $nombre = "Pagar";
                $icon = "dollar-sign";
                $cc = "danger";

              } else {

                if ($deuda < $reg['monto'] && $deuda > "0") {

                  $c = "warning";
                  $nombre = "Pagar";
                  $icon = "dollar-sign";
                  $cc = "warning";

                } else {

                  if ($deuda == "0" || $deuda == "0") {

                    $c = "success";
                    $nombre = "Ver";
                    $info = "info";
                    $icon = "eye";
                    $cc = "success";

                  } else {
                  
                    if ($reg['total_pago_compras'] > $deuda) {

                      $c = "info";
                      $nombre = "Excedido";
                      $info = "info";
                      $icon = "eye";
                      $cc = "info";

                    } else {
                    
                      $estado = '<span class="text-center badge badge-success">Error</span>';
                    }
                  }
                  //$estado = '<span class="text-center badge badge-success">Terminado</span>';
                }
              }

              $list_pago = '<div class="text-center formato-numero-conta"> 
                              <button class="btn btn-' .  $c . ' btn-xs" onclick="listar_pagos_prestamos(' . $reg['idprestamo'] . ', \''.$reg['entidad'].'\',' .  $reg['monto'] . ',' . $deuda . ')">'.
                                '<i class="fas fa-' . $icon .' nav-icon"></i> ' .$nombre .
                              '</button>' .
                              ' <button style="font-size: 14px;" class="btn btn-' . $cc . ' btn-sm"> S/' . number_format($reg['total_pago_compras'], 2, '.', ',') . '</button>'.
                            '</div>';

              $data[] = [
                "0"=>$cont++,
                "1" => '<button class="btn btn-warning btn-sm" onclick="editar_prestamo(' .$reg['idprestamo']. ')"><i class="fas fa-pencil-alt"></i></button>',
                "2" => $reg['entidad'],
                "3" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.$reg['descripcion'].'</textarea>',
                "4" =>'S/ '. number_format($reg['monto'], 2, '.', ','),
                "5" =>$list_pago,
                "6" =>'S/ '. number_format($deuda, 2, '.', ','),
                "7" =>$reg['fecha_inicio'], 
                "8" =>$reg['fecha_fin'],
              ];
            }
  
            $results = [
              "sEcho" => 1, //Información para el datatables
              "iTotalRecords" => count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
              "data" => $data,
            ];
  
            echo json_encode( $results, true) ;

          } else {

            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];

          }
          
        break;

        case 'mostrar_total_tbla_prestamo':

          //$rspta = $prestamo->mostrar_total_tbla_prestamo('2');
          $rspta = $prestamo->mostrar_total_tbla_prestamo($_POST["nube_idproyecto"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);

        break;

        // ========= ============= ================== ============
          //:::: S E C C I Ó N  P A G O   P R É S T A M O S ::::::
        // ========= ============= ================== ============

        case 'guardar_y_editar_pago_prestamo':
          // imgen
          if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {

            $imagen1 = $_POST["doc_old_1"];

            $flat_img1 = false;

          } else {

            $ext1 = explode(".", $_FILES["doc1"]["name"]);

            $flat_img1 = true;

            $imagen1 = $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

            move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/pago_prestamo/" . $imagen1);
          }

          if (empty($idpago_prestamo)) {
            
            $rspta = $prestamo->insertar_pago_prestamo($idprestamo_p,$fecha_pago_p,$monto_pago_p,$descripcion_pago_p,$imagen1);
            
            echo json_encode( $rspta, true);

          } else {

            // validamos si existe LA IMG para eliminarlo
            if ($flat_img1 == true) {

              $datos_f1 = $prestamo->obtenerImg_pago_prestamo($idpago_prestamo);

              $img1_ant = $datos_f1['data']['comprobante'];

              if ($img1_ant != "") {

                unlink("../dist/docs/pago_prestamo/" . $img1_ant);
              }
            }
             
            $rspta = $prestamo->editar_pago_prestamo($idpago_prestamo,$idprestamo_p,$fecha_pago_p,$monto_pago_p,$descripcion_pago_p,$imagen1);
            
            echo json_encode( $rspta, true) ;
          }
        break;

        case 'listar_pagos_prestamos':

          $rspta = $prestamo->listar_pagos_prestamos( $_GET['idprestamo'] );
          //Vamos a declarar un array
          $data = [];
          $cont=1;

          $descrip_toltip="Clic en botón para entrar el detalle de pagos";
          $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';
          $pago_prest = "_pago_prestamo";
          if ($rspta['status'] == true) {

            foreach ($rspta['data'] as $key => $reg) {

              $comprobante = empty($reg['comprobante']) ? ( '<center> <i class="fas fa-file-invoice-dollar fa-2x text-gray-50" data-toggle="tooltip" data-original-title="Vacío"></i></center>') : ( '<center><i class="fas fa-file-invoice-dollar fa-2x cursor-pointer text-blue" onclick="modal_comprobante('."'".$reg['comprobante']."'".', \''.$reg['fecha'].'\''. ')" data-toggle="tooltip" data-original-title="Ver Baucher"></i></center>');
              $datos_edit = '\''.$reg['idpago_prestamo'].'\', \''.$reg['idprestamo'].'\', \''.$reg['fecha'].'\', \''.$reg['monto'].'\', \''.$reg['descripcion'].'\', \''.$reg['comprobante'].'\'';
              $data[] = [
                "0"=>$cont++,
                "1" => '<button class="btn btn-warning btn-sm" onclick="editar_pago_prest(' . $datos_edit. ')" data-toggle="tooltip" data-original-title="Ver detalle compra"><i class="fas fa-pencil-alt"></i></button>' .
                ' <button class="btn btn-danger btn-sm" onclick="eliminar(' . $reg['idpago_prestamo'] . ', \''. (empty($reg['monto']) ? " - " : $reg['monto']).'\', \''.$pago_prest.'\')" data-toggle="tooltip" data-original-title="Editar compra"><i class="fas fa-skull-crossbones"></i></button>',
                "2" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.$reg['descripcion'].'</textarea>',
                "3" => 'S/ '. number_format($reg['monto'], 2, '.', ','),
                "4" =>$reg['fecha'],
                "5" =>$comprobante,
              ];
            }
  
            $results = [
              "sEcho" => 1, //Información para el datatables
              "iTotalRecords" => count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
              "data" => $data,
            ];
  
            echo json_encode( $results, true) ;

          } else {

            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];

          }
          
        break;

        case 'mostrar_total_tbla_pago_prestamo':

          //$rspta = $prestamo->mostrar_total_tbla_prestamo('2');
          $rspta = $prestamo->mostrar_total_tbla_pago_prestamo($_POST["idprestamo"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);

        break;


        case 'desactivar_pago_prestamo':

          $rspta = $prestamo->desactivar_pago_prestamo($_GET["id_tabla"] );

          echo json_encode( $rspta, true) ;

        break;      

        case 'eliminar_pago_prestamo':

          $rspta = $prestamo->eliminar_pago_prestamo($_GET["id_tabla"] );

          echo json_encode( $rspta, true) ;

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
