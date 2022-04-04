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

        echo json_encode($rspta, true);

      break;
      
      /* ══════════════════════════════════════ T R A B A J A D O R  ══════════════════════════════════════ */
      case 'select2Trabajador': 

        $rspta = $ajax_general->select2_trabajador();  $cont = 1; $data = "";

        if ($rspta['status']) {

          foreach ($rspta['data'] as $key => $value) {

            $data .= '<option  value=' . $value['id'] . '>' . $cont++ . '. ' . $value['nombre'] .' - '. $value['numero_documento'] . '</option>';
          }

          $retorno = array(
            'status' => true, 
            'message' => 'Salió todo ok', 
            'data' => $data, 
          );
  
          echo json_encode($retorno, true);

        } else {

          echo json_encode($rspta, true); 
        } 
      break;

      case "select2TipoTrabajador":

        $rspta = $ajax_general->select2_tipo_trabajador(); $cont = 1; $data = "";

        if ($rspta['status']) {

          foreach ($rspta['data'] as $key => $value) {

            $data .= '<option  value=' . $value['idtipo_trabajador']  . '>' . $value['nombre'] . '</option>';
          }

          $retorno = array(
            'status' => true, 
            'message' => 'Salió todo ok', 
            'data' => $data, 
          );
  
          echo json_encode($retorno, true);

        } else {

          echo json_encode($rspta, true); 
        }        
      break;

      case 'select2CargoTrabajdorId':         
         
        $rspta=$ajax_general->select2_cargo_trabajador_id( $_POST["idtipo"] ); $cont = 1; $data = "";

        if ($rspta['status']) {

          foreach ($rspta['data'] as $key => $value) {

            $data .= '<option  value=' . $value['idcargo_trabajador']  . '>' . $value['nombre'] .'</option>';
          }

          $retorno = array(
            'status' => true, 
            'message' => 'Salió todo ok', 
            'data' => $data, 
          );
  
          echo json_encode($retorno, true);

        } else {

          echo json_encode($rspta, true); 
        }
      break;

      case 'select2OcupacionTrabajador':         
         
        $rspta=$ajax_general->select2_ocupacion_trabajador( ); $cont = 1; $data = "";

        if ($rspta['status']) {

          foreach ($rspta['data'] as $key => $value) {

            $data .= '<option  value=' .  $value['id']  . '>' .  $value['nombre'] .'</option>';
          }

          $retorno = array(
            'status' => true, 
            'message' => 'Salió todo ok', 
            'data' => $data, 
          );
  
          echo json_encode($retorno, true);

        } else {

          echo json_encode($rspta, true); 
        }
      break;
      
      /* ══════════════════════════════════════ P R O V E E D O R  ══════════════════════════════════════ */
      case 'select2Proveedor': 
    
        $rspta=$ajax_general->select2_proveedor();  $cont = 1; $data = "";

        if ($rspta['status']) {

          foreach ($rspta['data'] as $key => $value) {  

            $data .= '<option value=' .  $value['idproveedor'] . '>' .$cont++.'. '.  $value['razon_social'] .' - '.  $value['ruc'] . '</option>';      
          }

          $retorno = array(
            'status' => true, 
            'message' => 'Salió todo ok', 
            'data' => $data, 
          );
  
          echo json_encode($retorno, true);

        } else {

          echo json_encode($rspta, true); 
        }
      break;
      
      /* ══════════════════════════════════════ B A N C O  ══════════════════════════════════════ */
      case 'select2Banco': 
    
        $rspta = $ajax_general->select2_banco(); $cont = 1; $data = "";

        if ($rspta['status']) {

          foreach ($rspta['data'] as $key => $value) {    
            
            $data .= '<option value=' . $value['id'] . '>' . $value['nombre'] . (empty($value['alias']) ? "" : ' -'. $value['alias'] ) .'</option>';
          }

          $retorno = array(
            'status' => true, 
            'message' => 'Salió todo ok', 
            'data' => $data, 
          );
  
          echo json_encode($retorno, true);

        } else {

          echo json_encode($rspta, true); 
        }
      break;

      case 'formato_banco':
               
        $rspta=$ajax_general->formato_banco($_POST["idbanco"]);
        //Codificar el resultado utilizando json
        echo json_encode($rspta, true);
         
      break;
      
      /* ══════════════════════════════════════ C O L O R ══════════════════════════════════════ */
      case 'select2Color': 
    
        $rspta = $ajax_general->select2_color(); $cont = 1; $data = "";
        
        if ($rspta['status']) {

          foreach ($rspta['data'] as $key => $value) {    

            $data .= '<option value=' . $value['id'] . '>' . $value['nombre'] .'</option>';
          }

          $retorno = array(
            'status' => true, 
            'message' => 'Salió todo ok', 
            'data' => $data, 
          );
  
          echo json_encode($retorno, true);

        } else {

          echo json_encode($rspta, true); 
        }
      break;
      
      /* ══════════════════════════════════════ U N I D A D   D E   M E D I D A  ══════════════════════════════════════ */
      case 'select2UnidaMedida': 
    
        $rspta = $ajax_general->select2_unidad_medida(); $cont = 1; $data = "";

        if ($rspta['status']) {

          foreach ($rspta['data'] as $key => $value) {    

            $data .= '<option value=' . $value['id'] . '>' . $value['nombre'] . ' - ' . $value['abreviacion'] .'</option>';
          }

          $retorno = array(
            'status' => true, 
            'message' => 'Salió todo ok', 
            'data' => $data, 
          );
  
          echo json_encode($retorno, true);

        } else {

          echo json_encode($rspta, true); 
        }
      break;
      
      /* ══════════════════════════════════════ C A T E G O R I A ══════════════════════════════════════ */
      case 'select2Categoria': 
    
        $rspta = $ajax_general->select2_categoria(); $cont = 1; $data = "";

        if ($rspta['status']) {

          foreach ($rspta['data'] as $key => $value) {  

            $data .= '<option value=' . $value['id'] . '>' . $value['nombre'] .'</option>';
          }

          $retorno = array(
            'status' => true, 
            'message' => 'Salió todo ok', 
            'data' => $data, 
          );
  
          echo json_encode($retorno, true);

        } else {

          echo json_encode($rspta, true); 
        }
      break;
    }
      
  }

  ob_end_flush();
?>
