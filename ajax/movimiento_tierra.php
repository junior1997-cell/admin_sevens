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
      
      require_once "../modelos/Movimiento_tierra.php";
      require_once "../modelos/Compra_insumos.php";

     $movimiento_tierra = new Movimiento_tierra();
     $compra_insumos = new Compra_insumos();

      date_default_timezone_set('America/Lima'); $date_now = date("d-m-Y h.i.s A");
      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

      // :::::::::::::::::::::::::: S E C C I O N   G R U P O  ::::::::::::::::::::::::::
      $idproyecto       = isset($_POST["idproyecto"]) ? limpiarCadena($_POST["idproyecto"]) : "";
      $idtipo_tierra    = isset($_POST["idtipo_tierra"]) ? limpiarCadena($_POST["idtipo_tierra"]) : "";
      $modulo           = isset($_POST["modulo"]) ? limpiarCadena($_POST["modulo"]) : "";
      $nombre_item      = isset($_POST["nombre_item"]) ? encodeCadenaHtml($_POST["nombre_item"] ) : "";
      $columna_bombeado = isset($_POST["columna_servicio_bombeado"]) ? ( empty($_POST["columna_servicio_bombeado"]) || $_POST["columna_servicio_bombeado"] == '0'  ? '0' : '1' ) : "";
      // $columna_descripcion= isset($_POST["columna_descripcion"]) ? ( empty($_POST["columna_descripcion"]) ? '0' : '1' ) : "";
      $descripcion_item = isset($_POST["descripcion_item"]) ? encodeCadenaHtml($_POST["descripcion_item"] ) : "";

      // :::::::::::::::::::::::::: S E C C I O N   T I E R R A  ::::::::::::::::::::::::::
      $idmovimiento_tierra = isset($_POST["idmovimiento_tierra"]) ? limpiarCadena($_POST["idmovimiento_tierra"]) : "";
      $idtipo_tierra_det   = isset($_POST["idtipo_tierra_det"]) ? limpiarCadena($_POST["idtipo_tierra_det"]) : "";
      $idproveedor         = isset($_POST["idproveedor"]) ? limpiarCadena($_POST["idproveedor"]) : "";
      $fecha               = isset($_POST["fecha"]) ? limpiarCadena($_POST["fecha"]) : "";
      $nombre_dia          = isset($_POST["nombre_dia"]) ? limpiarCadena($_POST["nombre_dia"]) : "";
      $cantidad            = isset($_POST["cantidad"]) ? limpiarCadena($_POST["cantidad"]) : "";
      $precio_unitario     = isset($_POST["precio_unitario"]) ? limpiarCadena($_POST["precio_unitario"]) : "";
      $total               = isset($_POST["total"]) ? limpiarCadena($_POST["total"]) : "";
      $descripcion_tierra  = isset($_POST["descripcion_tierra"]) ? limpiarCadena($_POST["descripcion_tierra"]) : "";
      
      switch ($_GET["op"]) {

        case 'guardar_y_editar_grupo':

          if (empty($idtipo_tierra)) {
            
            $rspta =$movimiento_tierra->insertar_grupo($idproyecto, $nombre_item, $modulo, $columna_bombeado, $descripcion_item);
            
            echo json_encode( $rspta, true);

          } else {

            $rspta =$movimiento_tierra->editar_grupo($idproyecto, $idtipo_tierra, $nombre_item, $modulo, $columna_bombeado, $descripcion_item);
            
            echo json_encode( $rspta, true) ;
          }
        break;
    
        case 'desactivar_grupo':

          $rspta =$movimiento_tierra->desactivar_grupo( $_GET["id_tabla"] );

          echo json_encode( $rspta, true) ;

        break;      

        case 'eliminar_grupo':

          $rspta =$movimiento_tierra->eliminar_grupo( $_GET["id_tabla"] );

          echo json_encode( $rspta, true) ;

        break;
    
        case 'mostrar_grupo':

          $rspta =$movimiento_tierra->mostrar_grupo($idtipo_tierra);
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true) ;

        break;
    
        case 'tbla_principal_grupo':
          $rspta =$movimiento_tierra->tbla_principal_grupo($_GET['id_proyecto']);
          //Vamos a declarar un array
          $data = [];
          $imagen_error = "this.src='../dist/svg/404-v2.svg'";
          $cont=1;
          $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

          if ($rspta['status'] == true) {

            while ($reg = $rspta['data']->fetch_object()) {
              
              $data[] = [
                "0"=>$cont++,
                "1" => '<button class="btn btn-warning btn-sm" onclick="mostrar_grupo(' . $reg->idtipo_tierra_concreto . ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' .
                ' <button class="btn btn-danger btn-sm" onclick="eliminar_grupo(' . $reg->idtipo_tierra_concreto .', \''.encodeCadenaHtml($reg->nombre).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i></button>',
                "2" => $reg->nombre,
                "3" => $reg->descripcion,
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

        //-----------------------------------------------------------------------------------------
        //----------------------------------- Tabs -----------------------------------------------
        //-----------------------------------------------------------------------------------------

        case 'listar_de_grupo':
        $rspta =$movimiento_tierra->listar_de_grupo($_POST['proyecto_nube']);
        echo json_encode( $rspta, true) ;

        break;

        //-----------------------------------------------------------------------------------------
        //----------------------- S E C C I O N  T I E R R A -------------------------------
        //-----------------------------------------------------------------------------------------
        case 'guardar_y_editar_tierra':

          if (empty($idmovimiento_tierra)) {
            
            $rspta =$movimiento_tierra->insertar_detalle_item($idtipo_tierra_det,$idproveedor,$fecha,$nombre_dia,$cantidad,$precio_unitario,quitar_formato_miles($total));
            
            echo json_encode($rspta);

          } else {

            $rspta =$movimiento_tierra->editar_detalle_item($idmovimiento_tierra,$idtipo_tierra_det,$idproveedor,$fecha,$nombre_dia,$cantidad,$precio_unitario,quitar_formato_miles($total));
            
            echo json_encode($rspta) ;
          }
        break;

        case 'tbla_principal_tierra':
          $rspta = $movimiento_tierra->tbla_principal_tierra($_GET["id_proyecto"], $_GET["idtipo_tierra"], $_GET["fecha_1"], $_GET["fecha_2"], $_GET["id_proveedor"], $_GET["comprobante"]);
          //Vamos a declarar un array
          $data = [];  $cont=1;         

          if ($rspta['status'] == true) {

            foreach ($rspta['data'] as $key => $reg) {   

              $data_comprobante = '\''.$reg['idcompra_proyecto'].'\' , \''.removeSpecialChar($reg['tipo_comprobante'].' '.(empty($reg['serie_comprobante']) ?  "" :  '- '.$reg['serie_comprobante'])).'\', \''.removeSpecialChar($reg['proveedor']).'\', \''.format_d_m_a($reg['fecha_compra']).'\''; 
              $btn_tipo = (empty($reg['cant_comprobantes']) ? 'btn-outline-info' : 'btn-info');  
              $descrip_toltip = (empty($reg['cant_comprobantes']) ? 'Vacío' : ($reg['cant_comprobantes']==1 ?  $reg['cant_comprobantes'].' comprobante' : $reg['cant_comprobantes'].' comprobantes'));       

              $data[] = [
                "0"=>$cont,
                "1" => '<button class="btn btn-info btn-sm" onclick="ver_detalle_compras(' . $reg['idcompra_proyecto'] . ')" data-toggle="tooltip" data-original-title="Ver detalle compra"><i class="fa fa-eye"></i></button>' ,
                "2" => '<div class="bg-color-242244245 " style="overflow: auto; resize: vertical; height: 35px;" >'. $reg['nombre_producto'] .'</div>',
                "3" => $reg['nombre_dia'],
                "4" => $reg['fecha_compra'],
                "5" => number_format($reg['cantidad_sin_bombeado'], 2, '.',','),
                "6" => $reg['subtotal_sin_bombeado'],
                "7" => $reg['subtotal_bombeado'],
                "8" => $reg['descuento'],
                "9" => $reg['total_compra'],
                "10" => $reg['proveedor'],
                "11" => '<center> <button class="btn '.$btn_tipo.' btn-sm" onclick="comprobante_compras(\''.$cont.'\', '. $data_comprobante .')" data-toggle="tooltip" data-original-title="'.$descrip_toltip.'"><i class="fas fa-file-invoice fa-lg"></i></button> </center>'.$toltip,                
              ];
              $cont++;
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

        case 'total_tierra':
          // $_POST['idtipo_tierra'],$_POST['fecha_i'],$_POST['fecha_f'],$_POST['proveedor'],$_POST['comprobante']
          $rspta =$movimiento_tierra->total_tierra($_POST['id_proyecto'], $_POST['idtipo_tierra'],$_POST['fecha_i'],$_POST['fecha_f'],$_POST['proveedor'],$_POST['comprobante']);
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true) ;

        break;

        case 'desactivar_tierra':

          $rspta =$movimiento_tierra->desactivar_tierra( $_GET["id_tabla"] );

          echo json_encode( $rspta, true) ;

        break;      

        case 'eliminar_tierra':

          $rspta =$movimiento_tierra->eliminar_tierra( $_GET["id_tabla"] );

          echo json_encode( $rspta, true) ;

        break;
    
        case 'mostrar_tierra':

          $rspta =$movimiento_tierra->mostrar_tierra($idmovimiento_tierra);
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true) ;

        break;   

        // :::::::::::::::::::::::::: S E C C I O N   C O M P R O B A N T E  :::::::::::::::::::::::::: 
        case 'tbla_comprobantes_compra':
          $cont_compra = $_GET["num_orden"];
          $id_compra = $_GET["id_compra"];
          $rspta = $compra_insumos->tbla_comprobantes( $id_compra );
          //Vamos a declarar un array
          $data = []; $cont = 1;        
          
          if ($rspta['status']) {
            while ($reg = $rspta['data']->fetch_object()) {
              $data[] = [
                "0" => $cont,
                "1" => '<div class="text-nowrap">'.             
                  '<a class="btn btn-info btn-sm" href="../dist/docs/compra_insumo/comprobante_compra/'.$reg->comprobante.'"  download="'.$cont_compra.'·'.$cont.' '.removeSpecialChar((empty($reg->serie_comprobante) ?  " " :  ' ─ '.$reg->serie_comprobante).' ─ '.$reg->razon_social).' ─ '. format_d_m_a($reg->fecha_compra).'" data-toggle="tooltip" data-original-title="Descargar" ><i class="fas fa-cloud-download-alt"></i></a>' .
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

        //-----------------------------------------------------------------------------------------
        //----------------------- S E C C I O N    R E S U M E N -------------------------------
        //-----------------------------------------------------------------------------------------

        case 'tbla_principal_resumen':
          $rspta = $movimiento_tierra->tbla_principal_resumen($_GET["idproyecto"]);
          //Vamos a declarar un array
          $data = [];  $cont=1;         

          if ($rspta['status'] == true) {
            while ($reg = $rspta['data']->fetch_object()) {              
              
              $data[] = [
                "0"=>$cont++,
                "1" => $reg->grupo ,
                "2" => $reg->um_abreviacion ,
                "3" => $reg->cantidad_total,
                "4" => $reg->promedio_precio,
                "5" => $reg->descuento_total,
                "6" => $reg->precio_total,       
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

        case 'total_resumen':

          $rspta = $movimiento_tierra->total_resumen($idproyecto);
          //Codificar el resultado utilizando json
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

  function quitar_formato_miles($number) {

    $sin_format = 0;

    if ( !empty($number) ) { $sin_format = floatval(str_replace(",", "", $number)); }
    
    return $sin_format;
  }
  
  ob_end_flush();
?>
