<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {

    header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.  
    
  } else {

    if ($_SESSION['Resumen_recibo_por_honorario'] == 1) {

      require_once "../modelos/Resumen_rh.php";

      $resumen_rh = new Resumen_rh();

      
      switch ($_GET["op"]) {

        case 'listar_resumen_rh':

          $rspta = $resumen_rh->resumen_rh();
          $cont = 1;
          //Vamos a declarar un array
          $data = [];

          foreach ($rspta['data'] as $key => $value) {

            $btn_tipo = (empty($value['comprobante'])) ? 'btn-outline-info' : 'btn-info';       
            
            $data[] = [

              "0" => $cont++,
              "1" => $value['modulo'],
              "2" => '<span class="text-primary font-weight-bold">' . $value['trabajador_razon_social'] . '</span>',
              "3" =>'S/. '.number_format($value['total'], 2, ".", ",") ,
              "4" => '<center> <button class="btn '.$btn_tipo.' btn-sm" onclick="modal_comprobante( \'' . $value['comprobante'] .'\', \''. $value['ruta']  . '\', \''. $value['trabajador_razon_social']  . '\')"><i class="fas fa-file-invoice fa-lg"></i></button> </center>',

            ];
          }

          $results = [
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data,
          ];
          echo json_encode($results);
         

        break;

        case 'data_recibos_honorarios':                  

          $rspta = $resumen_rh->resumen_rh();
          
          echo json_encode($rspta['data_recibos_honorarios']);

        break;
      
        case 'salir':
          //Limpiamos las variables de sesión
          session_unset();
          //Destruìmos la sesión
          session_destroy();
          //Redireccionamos al login
          header("Location: ../index.php");
      
        break;
      }
      
    } else {

      require 'noacceso.php';
    }
  }

  ob_end_flush();
?>
