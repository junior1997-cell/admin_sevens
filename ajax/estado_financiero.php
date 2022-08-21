<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {

    if ($_SESSION['estado_financiero'] == 1) {
      
      require_once "../modelos/Estado_financiero.php";

      $estadofinanciero = new EstadoFinanciero();

      date_default_timezone_set('America/Lima');
      $date_now = date("d-m-Y h.i.s A");

      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

      //  ESTADO FINANCIERO
      $idproyecto           = isset($_GET["nube_idproyecto"]) ? limpiarCadena($_GET["nube_idproyecto"]) : "";
      $idestado_financiero  = isset($_GET["idestado_financiero"]) ? limpiarCadena($_GET["idestado_financiero"]) : "";
      $caja                 = isset($_GET["caja"]) ? limpiarCadena($_GET["caja"] ) : "";
      $garantia             = isset($_GET["garantia"]) ? limpiarCadena($_GET["garantia"] ) : "";

      //  PROYECCIONES
      $idproyeccion_p = isset($_POST["idproyeccion_p"]) ? limpiarCadena($_POST["idproyeccion_p"]) : "";
      $idproyecto_p   = isset($_POST["idproyecto_p"]) ? limpiarCadena($_POST["idproyecto_p"]) : "";
      $fecha_p        = isset($_POST["fecha_p"]) ? limpiarCadena($_POST["fecha_p"] ) : "";
      $caja_p         = isset($_POST["caja_p"]) ? limpiarCadena($_POST["caja_p"] ) : "";
      $descripcion_p  = isset($_POST["descripcion_p"]) ? limpiarCadena($_POST["descripcion_p"] ) : "";

      switch ($_GET["op"]) {

        // ══════════════════════════════════════ ESTADO FINANCIERO ══════════════════════════════════════
        case 'guardar_y_editar_estado_financiero':
          
          if (empty($idestado_financiero)) {
            
            $rspta = $estadofinanciero->insertar_estado_financiero( $idproyecto, $caja, $garantia);
            
            echo json_encode( $rspta, true);

          } else {            
             
            $rspta = $estadofinanciero->editar_estado_financiero($idestado_financiero, $idproyecto, $caja, $garantia);
            
            echo json_encode( $rspta, true) ;
          }
        break;        
    
        case 'estado_financiero':

          $rspta = $estadofinanciero->estado_financiero($_POST["nube_idproyecto"]);
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true) ;

        break;
    
        // ══════════════════════════════════════ PROYECIONES ══════════════════════════════════════ 

        case 'listar_fechas_proyeccion':

          $rspta = $estadofinanciero->listar_fechas_proyeccion( $_POST["idproyecto"] );

          echo json_encode( $rspta, true) ;

        break;   

        case 'mostrar_fecha_proyeccion':

          $rspta = $estadofinanciero->mostrar_fecha_proyeccion($_POST["idproyeccion"]);
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true) ;

        break;

        case 'tbla_principal_fecha_proyeccion':
          $rspta = $estadofinanciero->tbla_principal_fecha_proyeccion($_GET["idproyecto"]);
          //Vamos a declarar un array
          $data = [];  $cont=1;
          

          if ($rspta['status'] == true) {
            while ($reg = $rspta['data']->fetch_object()) {          
              
              $data[] = [
                "0"=>$cont++,
                "1" => $reg->estado ? '<button class="btn btn-warning btn-sm" onclick="mostrar_fecha_proyeccion(' . $reg->idproyeccion . ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' .
                ' <button class="btn btn-danger btn-sm" onclick="eliminar_fechas_proyeccion(' . $reg->idproyeccion .', \''.encodeCadenaHtml(date("d/m/Y", strtotime($reg->fecha)) . ' | ' . number_format($reg->caja, 2, '.', ',')).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i></button>' : 
                '<button class="btn btn-warning btn-sm" onclick="mostrar_fecha_proyeccion(' . $reg->idproyeccion . ')"><i class="fa fa-pencil-alt"></i></button>',
                "2" => $reg->fecha,
                "3" => $reg->caja,
                "4" => $reg->total_gasto,
                "5" =>'<textarea cols="30" rows="1" class="textarea_datatable" readonly="">' . $reg->descripcion . '</textarea>',
                "6" => ($reg->estado ? '<span class="text-center badge badge-success">Activado</span>' : '<span class="text-center badge badge-danger">Desactivado</span>').$toltip,
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

        case 'guardar_y_editar_proyecciones':
          
          if (empty($idproyeccion_p)) {
            
            $rspta = $estadofinanciero->insertar_proyecciones( $idproyecto_p,format_a_m_d( $fecha_p), $caja_p, $descripcion_p);
            
            echo json_encode( $rspta, true);

          } else {            
             
            $rspta = $estadofinanciero->editar_proyecciones($idproyeccion_p, $idproyecto_p, format_a_m_d($fecha_p), $caja_p, $descripcion_p);
            
            echo json_encode( $rspta, true) ;
          }
        break;  

        case 'desactivar_fechas_proyeccion':

          $rspta = $estadofinanciero->desactivar_fechas_proyeccion( $_GET["id_tabla"] );

          echo json_encode( $rspta, true) ;

        break;      

        case 'eliminar_fechas_proyeccion':

          $rspta = $estadofinanciero->eliminar_fechas_proyeccion( $_GET["id_tabla"] );

          echo json_encode( $rspta, true) ;

        break;

        // ══════════════════════════════════════ D E T A L L E   P R O Y E C I O N E S ══════════════════════════════════════ 
        case 'tbla_principal_detalle_proyeccion':
          $rspta = $estadofinanciero->tbla_principal_detalle_proyeccion($_POST["idproyecto"],$_POST["idproyeccion"]);
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true) ;
          
        break;

        case 'guardar_y_editar_detalle_proyecciones':
          $_post = json_decode(file_get_contents('php://input'),true);
          
          if ( empty($_post["data_array"]["detalle"]) || empty($_post["data_array"]["idproyeccion"]) ) {
            
            $rspta = ['status' => 'error_ing_pool', 'user' => $_SESSION['nombre'], 'message' => 'Tus items estan vacios, <b>ingresa algunos</b> para tener un registro exitoso.', 'data' =>$_post["data_array"]  ];
            echo json_encode( $rspta, true) ;

          } else {            
             
            $rspta = $estadofinanciero->guardar_y_editar_detalle_proyecciones( $_post["data_array"] );
            
            echo json_encode( $rspta, true) ;
          }
        break;  
        // ══════════════════════════════════════ S U B   D E T A L L E   P R O Y E C I O N E S ══════════════════════════════════════

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
