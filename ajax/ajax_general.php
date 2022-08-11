<?php
  ob_start();

  if (strlen(session_id()) < 1) {

    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {

    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.

  } else {     

    require_once "../modelos/Ajax_general.php";

    $ajax_general = new Ajax_general();
    
    $imagen_error = "this.src='../dist/svg/404-v2.svg'";

    $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

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

            $data .= '<option  value=' . $value['id'] . ' title="'.$value['imagen_perfil'].'">' . $cont++ . '. ' . $value['nombre'] .' - '. $value['numero_documento'] . '</option>';
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
         
        $rspta=$ajax_general->select2_cargo_trabajador_id( $_GET["idtipo"] ); $cont = 1; $data = "";

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

            $data .= '<option value="' .  $value['idproveedor'] . '" ruc="'.$value['ruc'].'">' .$cont++.'. '.  $value['razon_social'] .' - '.  $value['ruc'] . '</option>';      
          }

          $retorno = array(
            'status' => true, 
            'message' => 'Salió todo ok', 
            'data' => '<option value="1" ruc="">Anónimo - 00000000000</option>' . $data, 
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
            
            $data .= '<option value=' . $value['id'] . ' title="'.$value['icono'].'">' . $value['nombre'] . (empty($value['alias']) ? "" : ' -'. $value['alias'] ) .'</option>';
          }

          $retorno = array(
            'status' => true, 
            'message' => 'Salió todo ok', 
            'data' => '<option value="1">SIN BANCO</option>'.$data, 
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

            $data .= '<option value=' . $value['id'] . ' title="'.$value['hexadecimal'].'" >' . $value['nombre'] .'</option>';
          }

          $retorno = array(
            'status' => true, 
            'message' => 'Salió todo ok', 
            'data' => '<option value="1" title="#ffffff00" >SIN COLOR</option>'.$data, 
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

      case 'select2Categoria_all': 
    
        $rspta = $ajax_general->select2_categoria_all(); $cont = 1; $data = "";

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

      /* ══════════════════════════════════════ P R O D U C T O ══════════════════════════════════════ */
      case 'tblaActivosFijos':
          
        $rspta = $ajax_general->tblaActivosFijos();
        //Vamos a declarar un array
        $datas = []; 

        if ($rspta['status'] == true) {
          
          while ($reg = $rspta['data']->fetch_object()) {

            $img_parametro = ""; $img = "";  $ficha_tecnica = "";  $color_stock = "";
  
            if (empty($reg->imagen)) {
              $img = 'src="../dist/docs/material/img_perfil/producto-sin-foto.svg"';
            } else {
              $img = 'src="../dist/docs/material/img_perfil/' . $reg->imagen . '"';
              $img_parametro = $reg->imagen;
            }
  
            !empty($reg->ficha_tecnica) ? ($ficha_tecnica = '<center><a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg->ficha_tecnica . '" data-toggle="tooltip" data-original-title="Ver Ficha Técnica"><i class="far fa-file-pdf fa-2x text-success"></i></a></center>')
              : ($ficha_tecnica = '<center><span class="text-center" data-toggle="tooltip" data-original-title="Vacío"> <i class="far fa-times-circle fa-2x text-danger"></i></span></center>');
  
            $datas[] = [
              "0" => '<button class="btn btn-warning" onclick="agregarDetalleComprobante(' . $reg->idproducto . ', \'' .  htmlspecialchars($reg->nombre, ENT_QUOTES) . '\', \'' . $reg->nombre_medida . '\', \'' . $reg->nombre_color . '\', \'' . $reg->precio_sin_igv . '\', \'' . $reg->igv . '\', \'' . $reg->precio_con_igv . '\', \'' .  $img_parametro . '\', \'' . $reg->ficha_tecnica . '\')" data-toggle="tooltip" data-original-title="Agregar Activo"><span class="fa fa-plus"></span></button>',
              "1" => '<div class="user-block w-250px">'.
                '<img class="profile-user-img img-responsive img-circle" ' .  $img . ' alt="user image" onerror="' . $imagen_error .  '">'.
                '<span class="username"><p style="margin-bottom: 0px !important;">' . $reg->nombre . '</p></span>
                <span class="description"><b>Color: </b>' . $reg->nombre_color . '</span>'.
              '</div>',
              "2" => $reg->categoria,
              "3" => number_format($reg->precio_con_igv, 2, '.', ','),
              "4" => '<textarea class="form-control textarea_datatable" cols="30" rows="1">' . $reg->descripcion . '</textarea>',
              "5" => $ficha_tecnica . $toltip,
            ];
          }
  
          $results = [
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($datas), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($datas), //enviamos el total registros a visualizar
            "aaData" => $datas,
          ];
          echo json_encode($results, true);
        } else {
          echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
        }
    
      break;

      case 'tblaInsumos':
          
        $rspta = $ajax_general->tblaInsumos();
        //Vamos a declarar un array
        $datas = []; 

        if ($rspta['status'] == true) {

          while ($reg = $rspta['data']->fetch_object()) {

            $img_parametro = ""; $img = "";  $ficha_tecnica = "";  $color_stock = "";
  
            if (empty($reg->imagen)) {
              $img = 'src="../dist/docs/material/img_perfil/producto-sin-foto.svg"';
            } else {
              $img = 'src="../dist/docs/material/img_perfil/' . $reg->imagen . '"';
              $img_parametro = $reg->imagen;
            }
  
            !empty($reg->ficha_tecnica) ? ($ficha_tecnica = '<center><a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg->ficha_tecnica . '" data-toggle="tooltip" data-original-title="Ver Ficha Técnica"><i class="far fa-file-pdf fa-2x text-success"></i></a></center>')
              : ($ficha_tecnica = '<center><span class="text-center" data-toggle="tooltip" data-original-title="Vacío"> <i class="far fa-times-circle fa-2x text-danger"></i></span></center>');
  
            $datas[] = [
              "0" => '<button class="btn btn-warning" onclick="agregarDetalleComprobante(' . $reg->idproducto . ', \'' .  htmlspecialchars($reg->nombre, ENT_QUOTES) . '\', \'' . $reg->nombre_medida . '\', \'' . $reg->nombre_color . '\', \'' . $reg->precio_sin_igv . '\', \'' . $reg->igv . '\', \'' . $reg->precio_con_igv . '\', \'' .  $img_parametro . '\', \'' . $reg->ficha_tecnica . '\')" data-toggle="tooltip" data-original-title="Agregar Activo"><span class="fa fa-plus"></span></button>',
              "1" => '<div class="user-block w-250px">'.
                '<img class="profile-user-img img-responsive img-circle" ' .  $img . ' alt="user image" onerror="' . $imagen_error .  '">'.
                '<span class="username"><p style="margin-bottom: 0px !important;">' . $reg->nombre . '</p></span>
                <span class="description"><b>Color: </b>' . $reg->nombre_color . '</span>'.
              '</div>',
              "2" => $reg->categoria,
              "3" => number_format($reg->precio_con_igv, 2, '.', ','),
              "4" => '<textarea class="form-control textarea_datatable" cols="30" rows="1">' . $reg->descripcion . '</textarea>',
              "5" => $ficha_tecnica . $toltip,
            ];
          }
  
          $results = [
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($datas), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($datas), //enviamos el total registros a visualizar
            "aaData" => $datas,
          ];

          echo json_encode($results, true);
        } else {

          echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
        }
    
      break;

      case 'tblaInsumosYActivosFijos':
          
        $rspta = $ajax_general->tblaInsumosYActivosFijos(); 
        //echo json_encode($rspta, true);
        //Vamos a declarar un array
        $datas = []; 

        if ($rspta['status'] == true) {

          while ($reg = $rspta['data']->fetch_object()) {

            $img_parametro = ""; $img = "";  $color_stock = "";
  
            if (empty($reg->imagen)) {
              $img = 'src="../dist/docs/material/img_perfil/producto-sin-foto.svg"';
            } else {
              $img = 'src="../dist/docs/material/img_perfil/' . $reg->imagen . '"';
              $img_parametro = $reg->imagen;
            }
  
            $ficha_tecnica = !empty($reg->ficha_tecnica) ? ( '<center><a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg->ficha_tecnica . '" data-toggle="tooltip" data-original-title="Ver Ficha Técnica"><i class="far fa-file-pdf fa-2x text-success"></i></a></center>')
              : ( '<center><span class="text-center" data-toggle="tooltip" data-original-title="Vacío"> <i class="far fa-times-circle fa-2x text-danger"></i></span></center>');
  
            $datas[] = [
              "0" => '<button class="btn btn-warning" onclick="agregarDetalleComprobante(' . $reg->idproducto . ', \'' .  htmlspecialchars($reg->nombre, ENT_QUOTES) . '\', \'' . $reg->nombre_medida . '\', \'' . $reg->nombre_color . '\', \'' . $reg->precio_sin_igv . '\', \'' . $reg->igv . '\', \'' . $reg->precio_con_igv . '\', \'' .  $img_parametro . '\', \'' . $reg->ficha_tecnica . '\')" data-toggle="tooltip" data-original-title="Agregar Activo"><span class="fa fa-plus"></span></button>',
              "1" => '<div class="user-block w-250px">'.
                '<img class="profile-user-img img-responsive img-circle" ' .  $img . ' alt="user image" onerror="' . $imagen_error .  '">'.
                '<span class="username"><p class="mb-0" >' . $reg->nombre . '</p></span>
                <span class="description"><b>Color: </b>' . $reg->nombre_color . '</span>'.
              '</div>',
              "2" => $reg->categoria,
              "3" => number_format($reg->precio_con_igv, 2, '.', ','),
              "4" => '<textarea class="form-control textarea_datatable" cols="30" rows="1">' . $reg->descripcion . '</textarea>',
              "5" => $ficha_tecnica . $toltip,
            ];
          }
  
          $results = [
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($datas), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($datas), //enviamos el total registros a visualizar
            "aaData" => $datas,
          ];

          echo json_encode($results, true);
        } else {

          echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
        }
    
      break;

      default: 
        $rspta = ['status'=>'error_code', 'message'=>'Te has confundido en escribir en el <b>swich.</b>', 'data'=>[]]; echo json_encode($rspta, true); 
      break;
    }
      
  }

  ob_end_flush();
?>
