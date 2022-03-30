<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }
  require_once "../modelos/proveedor.php";

  $proveedor = new Proveedor();

  $idproveedor_proyecto = isset($_POST["idproveedor_proyecto"]) ? limpiarCadena($_POST["idproveedor_proyecto"]) : "";
  $idproyecto = isset($_POST["idproyecto"]) ? limpiarCadena($_POST["idproyecto"]) : "";
  $idproveedor = isset($_POST["proveedor"]) ? limpiarCadena($_POST["proveedor"]) : "";

  switch ($_GET["op"]) {
    
    case 'select2_proveedor':
      $rspta = $proveedor->select2_proveedor();

      while ($reg = $rspta->fetch_object()) {
        echo '<option value=' . $reg->idproveedor . '>' . $reg->razon_social . ' - ' . $reg->ruc . '</option>';
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
  }
  ob_end_flush();
?>
