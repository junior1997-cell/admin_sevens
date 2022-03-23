<?php
  ob_start();

  if (strlen(session_id()) < 1) {

    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {

    header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.

  } else {     

    require_once "../modelos/Ajax_general.php";

    $ajax_general = new Ajax_general();
     

    switch ($_GET["op"]) {       

      // buscar datos de RENIEC
      case 'reniec':

        $dni = $_POST["dni"];

        $rspta = $ajax_general->datos_reniec($dni);

        echo json_encode($rspta);

      break;
      
      // buscar datos de SUNAT
      case 'sunat':

        $ruc = $_POST["ruc"];

        $rspta = $ajax_general->datos_sunat($ruc);

        echo json_encode($rspta);

      break;

      case 'select2Trabajador': 

        $rspta = $ajax_general->select2_trabajador();

        $cont = 1;
    
        while ($reg = $rspta->fetch_object())  {

          echo '<option  value=' . $reg->id . '>' . $cont++ . '. ' . $reg->nombre .' - '. $reg->numero_documento . '</option>';
        }

      break;

      case "select2TipoTrabajador":
        $rspta = $ajax_general->select2_tipo_trabajador();

        while ($reg = $rspta->fetch_object()) {
          echo '<option  value=' . $reg->idtipo_trabajador  . '>' . $reg->nombre . '</option>';
        }
      break;

      case 'select2CargoTrabajdorId':
         
         
        $rspta=$ajax_general->select2_cargo_trabajador_id( $_POST["idtipo"] );

        while ($reg = $rspta->fetch_object())  {

          echo '<option  value=' . $reg->idcargo_trabajador  . '>' . $reg->nombre .'</option>';
        }

      break;
    }
      
  }

  ob_end_flush();
?>
