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
      
      require_once "../modelos/Concreto_agregado.php";

      $concreto_agregado = new ConcretoAgregado();

      date_default_timezone_set('America/Lima');
      $date_now = date("d-m-Y h.i.s A");

      $imagen_error = "this.src='../dist/svg/404-v2.svg'";
      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

      // :::::::::::::::::::::::::: S E C C I O N   I T E M S  ::::::::::::::::::::::::::
      $idproyecto       = isset($_POST["idproyecto"]) ? limpiarCadena($_POST["idproyecto"]) : "";
      $idtipo_tierra    = isset($_POST["idtipo_tierra"]) ? limpiarCadena($_POST["idtipo_tierra"]) : "";
      $modulo           = isset($_POST["modulo"]) ? limpiarCadena($_POST["modulo"]) : "";
      $nombre_item      = isset($_POST["nombre_item"]) ? encodeCadenaHtml($_POST["nombre_item"] ) : "";
      $columna_calidad  = isset($_POST["columna_calidad"]) ? ( empty($_POST["columna_calidad"]) ? '0' : '1' ) : "";
      $columna_descripcion= isset($_POST["columna_descripcion"]) ? ( empty($_POST["columna_descripcion"]) ? '0' : '1' ) : "";
      $descripcion_item = isset($_POST["descripcion_item"]) ? encodeCadenaHtml($_POST["descripcion_item"] ) : "";
      
      
      // :::::::::::::::::::::::::: S E C C I O N   I T E M S  ::::::::::::::::::::::::::
      $idtipo_tierra_c   = isset($_POST["idtipo_tierra_c"]) ? limpiarCadena($_POST["idtipo_tierra_c"]) : "";
      $idconcreto_agregado      = isset($_POST["idconcreto_agregado"]) ? limpiarCadena($_POST["idconcreto_agregado"]) : "";
      $idproveedor              = isset($_POST["idproveedor"]) ? limpiarCadena($_POST["idproveedor"]) : "";      
      $fecha                    = isset($_POST["fecha"]) ? limpiarCadena($_POST["fecha"]) : "";
      $nombre_dia               = isset($_POST["nombre_dia"]) ? limpiarCadena($_POST["nombre_dia"]) : "";
      $calidad                  = isset($_POST["calidad"]) ? limpiarCadena($_POST["calidad"]) : "";
      $cantidad                 = isset($_POST["cantidad"]) ? limpiarCadena($_POST["cantidad"]) : "";
      $precio_unitario          = isset($_POST["precio_unitario"]) ? limpiarCadena($_POST["precio_unitario"]) : "";
      $total                    = isset($_POST["total"]) ? limpiarCadena($_POST["total"]) : "";
      $descripcion_concreto     = isset($_POST["descripcion_concreto"]) ? limpiarCadena($_POST["descripcion_concreto"]) : "";

      

      switch ($_GET["op"]) {
        // :::::::::::::::::::::::::: S E C C I O N   I T E M S  ::::::::::::::::::::::::::
        case 'guardar_y_editar_items':
          
          if (empty($idtipo_tierra)) {
            
            $rspta = $concreto_agregado->insertar_item($idproyecto, $nombre_item, $modulo, $columna_calidad, $columna_descripcion, $descripcion_item);
            
            echo json_encode( $rspta, true);

          } else {            
             
            $rspta = $concreto_agregado->editar_item($idproyecto, $idtipo_tierra, $nombre_item, $modulo, $columna_calidad, $columna_descripcion, $descripcion_item);
            
            echo json_encode( $rspta, true) ;
          }
        break;
    
        case 'desactivar_item':

          $rspta = $concreto_agregado->desactivar_item( $_GET["id_tabla"] );

          echo json_encode( $rspta, true) ;

        break;      

        case 'eliminar_item':

          $rspta = $concreto_agregado->eliminar_item( $_GET["id_tabla"] );

          echo json_encode( $rspta, true) ;

        break;
    
        case 'mostrar_item':

          $rspta = $concreto_agregado->mostrar_item($idtipo_tierra);
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true) ;

        break;
    
        case 'tbla_principal_item':
          $rspta = $concreto_agregado->tbla_principal_item($_GET["id_proyecto"]);
          //Vamos a declarar un array
          $data = [];  $cont=1;         

          if ($rspta['status'] == true) {
            while ($reg = $rspta['data']->fetch_object()) {              
              
              $data[] = [
                "0"=>$cont++,
                "1" => $reg->estado ? '<button class="btn btn-warning btn-sm" onclick="mostrar_item(' . $reg->idtipo_tierra . ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' .
                ' <button class="btn btn-danger btn-sm" onclick="eliminar_item(' . $reg->idtipo_tierra .', \''.encodeCadenaHtml($reg->nombre).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i></button>' : 
                '<button class="btn btn-warning btn-sm" onclick="mostrar_item(' . $reg->idtipo_tierra . ')"><i class="fa fa-pencil-alt"></i></button>',
                "2" => $reg->nombre,
                "3" => ($reg->columna_calidad ? '<span class="text-center badge badge-success">Si</span>' : '<span class="text-center badge badge-danger">No</span>'),
                "4" => ($reg->columna_descripcion ? '<span class="text-center badge badge-success">Si</span>' : '<span class="text-center badge badge-danger">No</span>') .$toltip,
                "5" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly="">' . $reg->descripcion . '</textarea>',
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

        case 'lista_de_items':

          $rspta = $concreto_agregado->lista_de_items($idproyecto);
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true) ;

        break;

        // :::::::::::::::::::::::::: S E C C I O N    C O N C R E T O    A G R E G A D O::::::::::::::::::::::::::
        case 'guardar_y_editar_concreto':
          
          if (empty($idconcreto_agregado)) {
            
            $rspta = $concreto_agregado->insertar_concreto($idtipo_tierra_c, $idproveedor, $fecha, $nombre_dia, $calidad, $cantidad, $precio_unitario, $total, $descripcion_concreto );
            
            echo json_encode( $rspta, true);

          } else {            
             
            $rspta = $concreto_agregado->editar_concreto($idconcreto_agregado, $idtipo_tierra_c, $idproveedor, $fecha, $nombre_dia, $calidad, $cantidad, $precio_unitario, $total, $descripcion_concreto );
            
            echo json_encode( $rspta, true) ;
          }
        break;

        case 'mostrar_concreto':

          $rspta = $concreto_agregado->mostrar_concreto($idconcreto_agregado);
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true) ;

        break;

        case 'tbla_principal_concreto':
          $rspta = $concreto_agregado->tbla_principal_concreto($_GET["id_proyecto"], $_GET["idtipo_tierra"], $_GET["fecha_1"], $_GET["fecha_2"], $_GET["id_proveedor"], $_GET["comprobante"]);
          //Vamos a declarar un array
          $data = [];  $cont=1;         

          if ($rspta['status'] == true) {
            while ($reg = $rspta['data']->fetch_object()) {              
              
              $data[] = [
                "0"=>$cont++,
                "1" => $reg->estado ? '<button class="btn btn-warning btn-sm" onclick="mostrar_concreto(' . $reg->idconcreto_agregado . ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' .
                ' <button class="btn btn-danger btn-sm" onclick="eliminar_concreto(' . $reg->idconcreto_agregado .', \''.encodeCadenaHtml( $reg->nombre_dia.' '.date("d/m/Y", strtotime($reg->fecha)). ' | '.number_format($reg->total,2,'.',',')  ).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i></button>' : 
                '<button class="btn btn-warning btn-sm" onclick="mostrar_concreto(' . $reg->idconcreto_agregado . ')"><i class="fa fa-pencil-alt"></i></button>',
                "2" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly="">' . $reg->detalle . '</textarea>',
                "3" => $reg->nombre_dia,
                "4" => $reg->fecha,
                "5" => $reg->calidad,
                "6" => $reg->cantidad,
                "7" => $reg->precio_unitario,
                "8" => $reg->total,
                "9" => $reg->razon_social,
                "10" => ($reg->estado ? '<span class="text-center badge badge-success">Activado</span>' : '<span class="text-center badge badge-danger">Desactivado</span>') .$toltip,                
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

        case 'total_concreto':

          $rspta = $concreto_agregado->total_concreto($_POST["id_proyecto"], $_POST["idtipo_tierra"], $_POST["fecha_1"], $_POST["fecha_2"], $_POST["id_proveedor"], $_POST["comprobante"]);
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true) ;

        break;

        case 'desactivar_concreto':

          $rspta = $concreto_agregado->desactivar_concreto( $_GET["id_tabla"] );

          echo json_encode( $rspta, true) ;

        break;      

        case 'eliminar_concreto':

          $rspta = $concreto_agregado->eliminar_concreto( $_GET["id_tabla"] );

          echo json_encode( $rspta, true) ;

        break;

        // :::::::::::::::::::::::::: S E C C I O N    R E S U M E N ::::::::::::::::::::::::::

        case 'tbla_principal_resumen':
          $rspta = $concreto_agregado->tbla_principal_resumen($_GET["idproyecto"]);
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

          $rspta = $concreto_agregado->total_resumen($idproyecto);
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
  
  ob_end_flush();
?>