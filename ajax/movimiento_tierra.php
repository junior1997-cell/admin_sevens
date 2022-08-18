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

     $movimiento_tierra = new Movimiento_tierra();

      date_default_timezone_set('America/Lima');
      $date_now = date("d-m-Y h.i.s A");
      //$idproyecto,$idtipo_tierra,$nombre,$modulo,$descripcion

      $idproyecto     = isset($_POST["idproyecto"]) ? limpiarCadena($_POST["idproyecto"]) : "";
      $idtipo_tierra  = isset($_POST["idtipo_tierra"]) ? limpiarCadena($_POST["idtipo_tierra"]) : "";
      $nombre         = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
      $modulo         = isset($_POST["modulo"]) ? limpiarCadena($_POST["modulo"]) : "";
      $descripcion    = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";

      //----------------detalle item----------------
      //  $idmovimiento_tierra,$idtipo_tierra_det,$idproveedor,$fecha,$nombre_dia,$cantidad,$precio_unitario,$total,$descripcion_tierra 

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

        case 'guardaryeditar':

          if (empty($idtipo_tierra)) {
            
            $rspta =$movimiento_tierra->insertar($idproyecto,$nombre,$modulo,$descripcion);
            
            echo json_encode( $rspta, true);

          } else {

            $rspta =$movimiento_tierra->editar($idproyecto,$idtipo_tierra,$nombre,$modulo,$descripcion);
            
            echo json_encode( $rspta, true) ;
          }
        break;
    
        case 'desactivar':

          $rspta =$movimiento_tierra->desactivar( $_GET["id_tabla"] );

          echo json_encode( $rspta, true) ;

        break;      

        case 'eliminar':

          $rspta =$movimiento_tierra->eliminar( $_GET["id_tabla"] );

          echo json_encode( $rspta, true) ;

        break;
    
        case 'mostrar':

          $rspta =$movimiento_tierra->mostrar($idtipo_tierra);
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true) ;

        break;
    
        case 'tbla_principal':
          $rspta =$movimiento_tierra->tbla_principal($_GET['proyecto']);
          //Vamos a declarar un array
          $data = [];
          $imagen_error = "this.src='../dist/svg/404-v2.svg'";
          $cont=1;
          $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

          if ($rspta['status'] == true) {

            while ($reg = $rspta['data']->fetch_object()) {
              
              $data[] = [
                "0"=>$cont++,
                "1" => '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->idtipo_tierra . ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' .
                ' <button class="btn btn-danger btn-sm" onclick="eliminar(' . $reg->idtipo_tierra .', \''.encodeCadenaHtml($reg->nombre).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i></button>',
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

        case 'listar_items':
        $rspta =$movimiento_tierra->listar_items($_POST['proyecto_nube']);
        echo json_encode( $rspta, true) ;

        break;

        //-----------------------------------------------------------------------------------------
        //----------------------- S E C C I O N  S E G Ú N  I T E M -------------------------------
        //-----------------------------------------------------------------------------------------
        case 'guardaryeditar_tierra':

          if (empty($idmovimiento_tierra)) {
            
            $rspta =$movimiento_tierra->insertar_detalle_item($idtipo_tierra_det,$idproveedor,$fecha,$nombre_dia,$cantidad,$precio_unitario,quitar_formato_miles($total));
            
            echo json_encode($rspta);

          } else {

            $rspta =$movimiento_tierra->editar_detalle_item($idmovimiento_tierra,$idtipo_tierra_det,$idproveedor,$fecha,$nombre_dia,$cantidad,$precio_unitario,quitar_formato_miles($total));
            
            echo json_encode($rspta) ;
          }
        break;

        case 'tbla_principal_tierra':
         // $_GET['id_proyecto'],$_GET['idtipo_tierra'],$_GET['nombre_item'],$_GET['fecha_i'],$_GET['fecha_f'],$_GET['proveedor'],$_GET['comprobante']
          $rspta = $movimiento_tierra->tbla_principal_tierra($_GET['id_proyecto'],$_GET['idtipo_tierra'],$_GET['fecha_i'],$_GET['fecha_f'],$_GET['proveedor'],$_GET['comprobante']);
          //Vamos a declarar un array
          $data = [];  $cont=1;         
          $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

          if ($rspta['status'] == true) {
            while ($reg = $rspta['data']->fetch_object()) {              
              
              $data[] = [
                "0"=>$cont++,
                "1" => '<button class="btn btn-warning btn-sm" onclick="mostrar_detalle_item(' . $reg->idmovimiento_tierra . ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' .
                ' <button class="btn btn-danger btn-sm" onclick="eliminar_detalle_item(' . $reg->idmovimiento_tierra .', \''.$reg->nombre.'\', \''.$reg->fecha.'\' )" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i></button>',
                "2" => $reg->nombre_dia,
                "3" => $reg->fecha,
                "4" => $reg->cantidad,
                "5" => $reg->precio_unitario,
                "6" => $reg->total,
                "7" => $reg->razon_social,
                "8" => ($reg->estado ? '<span class="text-center badge badge-success">Activado</span>' : '<span class="text-center badge badge-danger">Desactivado</span>') .$toltip,                
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

        case 'desactivar_detalle_item':

          $rspta =$movimiento_tierra->desactivar_detalle_item( $_GET["id_tabla"] );

          echo json_encode( $rspta, true) ;

        break;      

        case 'eliminar_detalle_item':

          $rspta =$movimiento_tierra->eliminar_detalle_item( $_GET["id_tabla"] );

          echo json_encode( $rspta, true) ;

        break;
    
        case 'mostrar_detalle_item':

          $rspta =$movimiento_tierra->mostrar_detalle_item($idmovimiento_tierra);
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true) ;

        break;

        case 'mostrar_total_det_item':
          // $_POST['idtipo_tierra'],$_POST['fecha_i'],$_POST['fecha_f'],$_POST['proveedor'],$_POST['comprobante']
          $rspta =$movimiento_tierra->mostrar_total_det_item($_POST['idtipo_tierra'],$_POST['fecha_i'],$_POST['fecha_f'],$_POST['proveedor'],$_POST['comprobante']);
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true) ;

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
                "1" => $reg->nombre ,
                "2" => 'M3' ,
                "3" => $reg->cantidad,
                "4" => $reg->precio_unitario,
                "5" => $reg->total,        
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
