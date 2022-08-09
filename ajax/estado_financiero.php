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
    
        case 'tbla_principal':
          $rspta = $estadofinanciero->tbla_principal();
          //Vamos a declarar un array
          $data = [];
          $imagen_error = "this.src='../dist/svg/404-v2.svg'";
          $cont=1;
          $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

          if ($rspta['status'] == true) {
            while ($reg = $rspta['data']->fetch_object()) {

              $imagen = (empty($reg->imagen) ? 'producto-sin-foto.svg' : $reg->imagen) ;
              
              $ficha_tecnica = empty($reg->ficha_tecnica) ? ( '<center><i class="far fa-file-pdf fa-2x text-gray-50"></i></center>') : ( '<center><a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg->ficha_tecnica . '"><i class="far fa-file-pdf fa-2x text-danger" ></i></a></center>');
              
              $monto_igv = (empty($reg->precio_igv) ?  '-' :  $reg->precio_igv);
              
              $data[] = [
                "0"=>$cont++,
                "1" => $reg->estado ? '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->idproducto . ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' .
                ' <button class="btn btn-danger btn-sm" onclick="eliminar(' . $reg->idproducto .', \''.encodeCadenaHtml($reg->nombre).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i></button>'. 
                ' <button class="btn btn-info btn-sm" onclick="verdatos('.$reg->idproducto.')" data-toggle="tooltip" data-original-title="Ver datos"><i class="far fa-eye"></i></button>' : 
                '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->idproducto . ')"><i class="fa fa-pencil-alt"></i></button>',
                "2" =>
                  '<div class="user-block">
                    <img class="profile-user-img img-responsive img-circle cursor-pointer" src="../dist/docs/material/img_perfil/' . $imagen . '" alt="user image" onerror="'.$imagen_error.'" onclick="ver_perfil(\'../dist/docs/material/img_perfil/' . $imagen . '\', \''.encodeCadenaHtml($reg->nombre).'\');" data-toggle="tooltip" data-original-title="Ver imagen">
                    <span class="username"><p style="margin-bottom: 0px !important;">' . $reg->nombre . '</p></span>
                    <span class="description">' . substr($reg->descripcion, 0, 30) . '...</span>
                  </div>',
                "3" => $reg->nombre_medida,
                "4" => $reg->marca,
                "5" =>'S/ '. number_format($reg->precio_unitario, 2, '.', ','),
                "6" =>'S/ '.number_format($reg->precio_sin_igv, 2, '.', ','),
                "7" =>'S/ '. number_format($monto_igv, 2, '.', ','),
                "8" =>'S/ '.number_format($reg->precio_total, 2, '.', ','),
                "9" => $ficha_tecnica,
                "10" => ($reg->estado ? '<span class="text-center badge badge-success">Activado</span>' : '<span class="text-center badge badge-danger">Desactivado</span>').$toltip,
                "11" => $reg->nombre,
                "12" => $reg->nombre_color,
                "13" => $reg->descripcion,
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

        // ══════════════════════════════════════ PROYECIONES ══════════════════════════════════════ 

        case 'guardar_y_editar_proyecciones':
          
          if (empty($idproyeccion_p)) {
            
            $rspta = $estadofinanciero->insertar_proyecciones( $idproyecto_p,format_a_m_d( $fecha_p), $caja_p, $descripcion_p);
            
            echo json_encode( $rspta, true);

          } else {            
             
            $rspta = $estadofinanciero->editar_proyecciones($idproyeccion_p, $idproyecto_p, format_a_m_d($fecha_p), $caja_p, $descripcion_p);
            
            echo json_encode( $rspta, true) ;
          }
        break;  

        case 'desactivar':

          $rspta = $estadofinanciero->desactivar( $_GET["id_tabla"] );

          echo json_encode( $rspta, true) ;

        break;      

        case 'eliminar':

          $rspta = $estadofinanciero->eliminar( $_GET["id_tabla"] );

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
