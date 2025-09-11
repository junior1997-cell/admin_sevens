<?php

  ob_start();

  if (strlen(session_id()) < 1) {

    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {

    //Validamos el acceso solo al usuario logueado y autorizado.
    if ($_SESSION['valorizacion_concreto'] == 1) {

      require_once "../modelos/Concreto_control.php";

      $concreto_control = new Concreto_control();

      date_default_timezone_set('America/Lima'); $date_now = date("d_m_Y__h_i_s_A");

      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>'; 

      $scheme_host =  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/admin_sevens/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');


      $idcontrol_concreto          = isset($_POST["idcontrol_concreto"]) ? limpiarCadena($_POST["idcontrol_concreto"]) : "";
      $idproyectocontrol_concreto  = isset($_POST["idproyectocontrol_concreto"]) ? limpiarCadena($_POST["idproyectocontrol_concreto"]) : "";
      $fecha_concreto              = isset($_POST["fecha_concreto"]) ? limpiarCadena($_POST["fecha_concreto"]) : "";
      $r_cemento_usado             = isset($_POST["r_cemento_usado"]) ? limpiarCadena($_POST["r_cemento_usado"]) : "";
      $descripcion_concreto        = isset($_POST["descripcion_concreto"]) ? limpiarCadena($_POST["descripcion_concreto"]) : "";
      
      $cuadrilla                  = isset($_POST["cuadrilla"]) ? limpiarCadena($_POST["cuadrilla"]) : "";
      $hora_inicio                = isset($_POST["hora_inicio"]) ? limpiarCadena($_POST["hora_inicio"]) : "";
      $hora_termino               = isset($_POST["hora_termino"]) ? limpiarCadena($_POST["hora_termino"]) : "";
      $duracion_vaciado           = isset($_POST["duracion_vaciado"]) ? limpiarCadena($_POST["duracion_vaciado"]) : "";

      /**SUB NIVEL */
      $idcontrol_concreto_sn     = isset($_POST["idcontrol_concreto_sn"]) ? limpiarCadena($_POST["idcontrol_concreto_sn"]) : "";
      $idcontrol_concreto_p_sn   = isset($_POST["idcontrol_concreto_padre_sn"]) ? limpiarCadena($_POST["idcontrol_concreto_padre_sn"]) : "";
      $idproyecto_sn             = isset($_POST["idproyecto_sn"]) ? limpiarCadena($_POST["idproyecto_sn"]) : "";
      $prefijo_sn                = isset($_POST["prefijo_sn"]) ? limpiarCadena($_POST["prefijo_sn"]) : "";
      $codigo_padre_sn           = isset($_POST["codigo_padre_sn"]) ? limpiarCadena($_POST["codigo_padre_sn"]) : "";
      $codigo_hijo_sn            = isset($_POST["codigo_hijo_sn"]) ? limpiarCadena($_POST["codigo_hijo_sn"]) : "";
      $fecha_sn                  = isset($_POST["fecha_sn"]) ? limpiarCadena($_POST["fecha_sn"]) : "";
      $descripcion_sn            = isset($_POST["descripcion_sn"]) ? limpiarCadena($_POST["descripcion_sn"]) : "";
      $cantidad_sn               = isset($_POST["cantidad_sn"]) ? limpiarCadena($_POST["cantidad_sn"]) : "";
      $largo_sn                  = isset($_POST["largo_sn"]) ? limpiarCadena($_POST["largo_sn"]) : "";
      $ancho_sn                  = isset($_POST["ancho_sn"]) ? limpiarCadena($_POST["ancho_sn"]) : "";
      $alto_sn                   = isset($_POST["alto_sn"]) ? limpiarCadena($_POST["alto_sn"]) : "";
      $altura_vaciado_sn         = isset($_POST["altura_vaciado_sn"]) ? limpiarCadena($_POST["altura_vaciado_sn"]) : "";
      $calidad_fc_kg_cm2_sn      = isset($_POST["calidad_fc_kg_cm2_sn"]) ? limpiarCadena($_POST["calidad_fc_kg_cm2_sn"]) : "";
      $bolsas_m3_sn              = isset($_POST["bolsas_m3_sn"]) ? limpiarCadena($_POST["bolsas_m3_sn"]) : "";
      $piedra_m3_sn              = isset($_POST["piedra_m3_sn"]) ? limpiarCadena($_POST["piedra_m3_sn"]) : "";
      $arena_m3_sn               = isset($_POST["arena_m3_sn"]) ? limpiarCadena($_POST["arena_m3_sn"]) : "";
      $hormigon_m3_sn            = isset($_POST["hormigon_m3_sn"]) ? limpiarCadena($_POST["hormigon_m3_sn"]) : "";
      $concreto_proyectado_m3_sn = isset($_POST["concreto_proyectado_m3_sn"]) ? limpiarCadena($_POST["concreto_proyectado_m3_sn"]) : "";
      $cemento_proyectado_m3_sn  = isset($_POST["cemento_proyectado_m3_sn"]) ? limpiarCadena($_POST["cemento_proyectado_m3_sn"]) : "";
      $dosificacion_sn           = isset($_POST["dosificacion_sn"]) ? limpiarCadena($_POST["dosificacion_sn"]) : "";


      switch ($_GET["op"]) {
       /*============================   DOSIFICACIONES =============================*/
        case 'guardar_y_editar':          
            
            $rspta = $concreto_control->insertar($_POST["dosificaciones"]);
            
            echo json_encode( $rspta, true);

        break;

        case 'listar_dosificacion_concreto':          

          $rspta = $concreto_control->listar_dosificacion_concreto();
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true) ;

        break;
        
        /*============================   NIVEL 1 =============================*/
        case 'guardar_y_editar_nivel1': 
          
          if (empty($idcontrol_concreto)) {
            $rspta = $concreto_control->insertar_nivel1($idproyectocontrol_concreto,$fecha_concreto,$r_cemento_usado,$descripcion_concreto,$cuadrilla,$hora_inicio,$hora_termino,$duracion_vaciado);
            echo json_encode( $rspta, true);           
          } else {
            $rspta = $concreto_control->editar_nivel1($idcontrol_concreto, $idproyectocontrol_concreto,$fecha_concreto,$r_cemento_usado,$descripcion_concreto,$cuadrilla,$hora_inicio,$hora_termino,$duracion_vaciado);
            echo json_encode( $rspta, true);
          }
            
        break;
        
        case 'listar_concreto':          

          $rspta = $concreto_control->listar_concreto($_GET['fecha_i_r'],$_GET['fecha_f_r']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true) ;
        break;
        /*============================ SUB NIVEL =============================*/

        case 'guardar_y_editar_subnivel': 
          
          if (empty($idcontrol_concreto_sn)) {
            $rspta = $concreto_control->insertar_subnivel( $idcontrol_concreto_p_sn,$idproyecto_sn, $prefijo_sn, $codigo_padre_sn, $fecha_sn, $descripcion_sn, $cantidad_sn,
                                                        $largo_sn, $ancho_sn, $alto_sn, $altura_vaciado_sn, $calidad_fc_kg_cm2_sn, $bolsas_m3_sn, $piedra_m3_sn,
                                                        $arena_m3_sn, $hormigon_m3_sn, $concreto_proyectado_m3_sn, $cemento_proyectado_m3_sn,$dosificacion_sn);
            echo json_encode( $rspta, true);
          } else {
            $rspta = $concreto_control->editar_subnivel( $idcontrol_concreto_sn,$idcontrol_concreto_p_sn, $idproyecto_sn, $prefijo_sn, $codigo_padre_sn, $fecha_sn, $descripcion_sn, $cantidad_sn,
                                                      $largo_sn, $ancho_sn, $alto_sn, $altura_vaciado_sn, $calidad_fc_kg_cm2_sn, $bolsas_m3_sn, $piedra_m3_sn,
                                                      $arena_m3_sn, $hormigon_m3_sn, $concreto_proyectado_m3_sn, $cemento_proyectado_m3_sn,$dosificacion_sn);
            echo json_encode( $rspta, true);
          }
            
        break;

        case  'mostrar_concreto':

          $rspta = $concreto_control->mostrar_concreto($_POST["idcontrol_concreto"], $_POST["nivel"] );
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true) ;

        break;

        case 'eliminar_concreto_control':
          $rspta = $concreto_control->eliminar_concreto_control($_GET["idcontrol_concreto"], $_GET["codigo"], $_GET["nivel"] );
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true) ;
        break;
        
        case 'mostrar-docs-quincena':          

          $rspta = $concreto_control->mostrar_docs_quincena($_POST["nube_idproyecto"], $_POST["fecha_i"], $_POST["fecha_f"], $_POST["numero_q_s"] );
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true) ;

        break;

        case 'todos_los_docs':          

          $rspta = $concreto_control->todos_los_docs($_POST["nube_idproyecto"] );
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true) ;

        break;
        
        case 'listarquincenas':
          $rspta=$concreto_control->listarquincenas($_POST["nube_idproyecto"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break; 

        default: 
          $rspta = ['status'=>'error_code', 'message'=>'Te has confundido en escribir en el <b>swich.</b>', 'data'=>[]]; echo json_encode($rspta, true); 
        break;
      }

      //Fin de las validaciones de acceso
    } else {
      $retorno = ['status'=>'nopermiso', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data'=>[] ];
      echo json_encode($retorno, true);
    }
  }

  ob_end_flush();

?>
