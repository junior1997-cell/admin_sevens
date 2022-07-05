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
      
      require_once "../modelos/Chart_compra_insumo.php";

      $chart_compra_insumo = new ChartCompraInsumo();

      date_default_timezone_set('America/Lima');
      $date_now = date("d-m-Y h.i.s A");

      $idproducto = isset($_POST["idproducto"]) ? limpiarCadena($_POST["idproducto"]) : "";
      $idcategoria = isset($_POST["idcategoria_insumos_af"]) ? limpiarCadena($_POST["idcategoria_insumos_af"]) : "";

      switch ($_GET["op"]) {
        
        case 'box_content_reporte':
          $rspta = $chart_compra_insumo->box_content_reporte($_POST["idnubeproyecto"]);
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true) ;
        break;

        case 'chart_linea':
          $rspta = $chart_compra_insumo->chart_linea($_POST["idnubeproyecto"], 2022);
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true) ;
        break;
       
      }
    } else {
      $retorno = ['status'=>'nopermiso', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
      echo json_encode($retorno);
    }  
  } 
  
  ob_end_flush();
?>
