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
      $estado_igv = isset($_POST["estado_igv"]) ? limpiarCadena($_POST["estado_igv"]) : "";
      $monto_igv = isset($_POST["monto_igv"]) ? limpiarCadena($_POST["monto_igv"]) : "";
      $precio_real = isset($_POST["precio_real"]) ? limpiarCadena($_POST["precio_real"]) : "";      
      $unidad_medida = isset($_POST["unidad_medida"]) ? limpiarCadena($_POST["unidad_medida"]) : "";
      $color = isset($_POST["color"]) ? limpiarCadena($_POST["color"]) : "";
      $total_precio = isset($_POST["total_precio"]) ? limpiarCadena($_POST["total_precio"]) : "";

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
    
        case 'desactivar':

          $rspta = $concreto_agregado->desactivar( $_GET["id_tabla"] );

          echo json_encode( $rspta, true) ;

        break;      

        case 'eliminar':

          $rspta = $concreto_agregado->eliminar( $_GET["id_tabla"] );

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

        case 'tbla_principal_concreto':
          $rspta = $concreto_agregado->tbla_principal_concreto($_GET["idtipo_tierra"]);
          //Vamos a declarar un array
          $data = [];  $cont=1;         

          if ($rspta['status'] == true) {
            while ($reg = $rspta['data']->fetch_object()) {              
              
              $data[] = [
                "0"=>$cont++,
                "1" => $reg->estado ? '<button class="btn btn-warning btn-sm" onclick="mostrar_item(' . $reg->idconcreto_agregado . ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' .
                ' <button class="btn btn-danger btn-sm" onclick="eliminar_item(' . $reg->idconcreto_agregado .', \''.encodeCadenaHtml($reg->total).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i></button>' : 
                '<button class="btn btn-warning btn-sm" onclick="mostrar_item(' . $reg->idconcreto_agregado . ')"><i class="fa fa-pencil-alt"></i></button>',
                "2" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly="">' . $reg->descripcion . '</textarea>',
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

        // :::::::::::::::::::::::::: S E C C I O N    R E S U M E N ::::::::::::::::::::::::::

        case 'tbla_principal_resumen':
          $rspta = $concreto_agregado->tbla_principal_resumen($_GET["id_proyecto"]);
          //Vamos a declarar un array
          $data = [];  $cont=1;         

          if ($rspta['status'] == true) {
            while ($reg = $rspta['data']->fetch_object()) {              
              
              $data[] = [
                "0"=>$cont++,
                "1" => $reg->estado ? '<button class="btn btn-warning btn-sm" onclick="mostrar_item(' . $reg->idconcreto_agregado . ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' .
                ' <button class="btn btn-danger btn-sm" onclick="eliminar_item(' . $reg->idconcreto_agregado .', \''.encodeCadenaHtml($reg->total).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i></button>' : 
                '<button class="btn btn-warning btn-sm" onclick="mostrar_item(' . $reg->idconcreto_agregado . ')"><i class="fa fa-pencil-alt"></i></button>',
                "2" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly="">' . $reg->descripcion . '</textarea>',
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
