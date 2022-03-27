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
      
      /* ══════════════════════════════════════ T R A B A J A D O R  ══════════════════════════════════════ */
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
      
      /* ══════════════════════════════════════ P R O V E E D O R  ══════════════════════════════════════ */
      case 'select2Proveedor': 
    
        $rspta=$ajax_general->select2_proveedor();

        $cont = 1;
    
        while ($reg = $rspta->fetch_object())	{
    
          echo '<option value=' . $reg->idproveedor . '>' .$cont++.'. '. $reg->razon_social .' - '. $reg->ruc . '</option>';
    
        }
    
      break;
      
      /* ══════════════════════════════════════ B A N C O  ══════════════════════════════════════ */
      case 'select2Banco': 
    
        $rspta = $ajax_general->select2_banco();
    
        while ($reg = $rspta->fetch_object())  {
    
          echo '<option value=' . $reg->id . '>' . $reg->nombre . ((empty($reg->alias)) ? "" : " - $reg->alias" ) .'</option>';
        }
    
      break;

      case 'formato_banco':
               
        $rspta=$proveedor->formato_banco($_POST["idbanco"]);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
         
      break;
      
      /* ══════════════════════════════════════ C O L O R ══════════════════════════════════════ */
      case 'select2Color': 
    
        $rspta = $ajax_general->select2_color();
    
        while ($reg = $rspta->fetch_object())  {
    
          echo '<option value=' . $reg->id . '>' . $reg->nombre .'</option>';
        }
    
      break;
      
      /* ══════════════════════════════════════ U N I D A D   D E   M E D I D A  ══════════════════════════════════════ */
      case 'select2UnidaMedida': 
    
        $rspta = $ajax_general->select2_unidad_medida();
    
        while ($reg = $rspta->fetch_object())  {
    
          echo '<option value=' . $reg->id . '>' . $reg->nombre . ' - ' . $reg->abreviacion .'</option>';
        }
    
      break;
      
      /* ══════════════════════════════════════ C A T E G O R I A ══════════════════════════════════════ */
      case 'select2Categoria': 
    
        $rspta = $ajax_general->select2_categoria();
    
        while ($reg = $rspta->fetch_object())  {
    
          echo '<option value=' . $reg->id . '>' . $reg->nombre .'</option>';
        }
    
      break;
    }
      
  }

  ob_end_flush();
?>
