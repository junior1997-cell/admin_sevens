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
    require_once "../modelos/Compra_insumos.php";
    require_once "../modelos/Compra_activos_fijos.php";

    $ajax_general = new Ajax_general();
    $compra_insumos = new Compra_insumos();
    $compra_activos_fijos = new Compra_activos_fijos();
    
    $scheme_host =  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/admin_sevens/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');
    $imagen_error = "this.src='../dist/svg/404-v2.svg'";
    $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

    // :::::::::::::::::::::::::::::::::::: D A T O S   C O M P R A ::::::::::::::::::::::::::::::::::::::
    $idproyecto         = isset($_POST["idproyecto"]) ? limpiarCadena($_POST["idproyecto"]) : "";
    $idcompra_proyecto  = isset($_POST["idcompra_proyecto"]) ? limpiarCadena($_POST["idcompra_proyecto"]) : "";
    $idproveedor        = isset($_POST["idproveedor"]) ? limpiarCadena($_POST["idproveedor"]) : "";
    $fecha_compra       = isset($_POST["fecha_compra"]) ? limpiarCadena($_POST["fecha_compra"]) : "";
    $glosa              = isset($_POST["glosa"]) ? limpiarCadena($_POST["glosa"]) : "";
    $tipo_comprobante   = isset($_POST["tipo_comprobante"]) ? limpiarCadena($_POST["tipo_comprobante"]) : "";    
    $serie_comprobante  = isset($_POST["serie_comprobante"]) ? limpiarCadena($_POST["serie_comprobante"]) : "";
    $slt2_serie_comprobante  = isset($_POST["slt2_serie_comprobante"]) ? limpiarCadena($_POST["slt2_serie_comprobante"]) : "";
    $val_igv            = isset($_POST["val_igv"]) ? limpiarCadena($_POST["val_igv"]) : "";
    $descripcion        = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
    $subtotal_compra    = isset($_POST["subtotal_compra"]) ? limpiarCadena($_POST["subtotal_compra"]) : "";
    $tipo_gravada       = isset($_POST["tipo_gravada"]) ? limpiarCadena($_POST["tipo_gravada"]) : "";    
    $igv_compra         = isset($_POST["igv_compra"]) ? limpiarCadena($_POST["igv_compra"]) : "";
    $total_venta        = isset($_POST["total_venta"]) ? limpiarCadena($_POST["total_venta"]) : "";
    $estado_detraccion  = isset($_POST["estado_detraccion"]) ? limpiarCadena($_POST["estado_detraccion"]) : "";


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

      // Update idsesion Proyecto
      case 'update_id_sesion':        
        $_SESSION['idproyecto'] = isset($_POST["idproyecto"]) ? $_POST["idproyecto"] : 0;        

        $retorno = array( 'status' => true, 'message' => 'Salió todo ok', 'data' => [], );
        echo json_encode($retorno, true);
      break;

      /* ══════════════════════════════════════ B I T A C O R A  ══════════════════════════════════════ */
      case 'tabla_bitacora':
        $rspta = $ajax_general->tabla_bitacora($_GET["nombre_tabla"], $_GET["id_tabla"]);        
        //Vamos a declarar un array
        $data = []; $cont = 1;
        
        if ($rspta['status'] == true) {

          foreach ($rspta['data'] as $key => $value) {            

            $data[] = [
              "0"=>$cont++,              
              "1" => $value['id_tabla'],
              "2" => '<span class="fw-semibold text-primary">'.$value['nombre_tabla'].'</span>',
              "3" => $value['accion'],
              "4" => $value['responsable'],
              "5" => '<div class="textarea_datatable bg-light" style="overflow: auto; resize: vertical; height: 45px;">' .$value['sql_d'].'</div>',                             
              "6" => $value['created_at']
            ];
          }
          $results = [
            'status'=> true,
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data,
          ];
          echo json_encode($results, true) ;
        } else {
          echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
        }

      break;
      /* ══════════════════════════════════════ T R A B A J A D O R  ══════════════════════════════════════ */
      case 'select2Trabajador': 

        $rspta = $ajax_general->select2_trabajador();  $cont = 1; $data = "";

        if ($rspta['status'] == true) {

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

      case 'select2TrabajadorPorProyecto': 

        $rspta = $ajax_general->select2_trabajador_por_proyecto( $_GET['id_proyecto'] );  $cont = 1; $data = "";

        if ($rspta['status'] == true) {

          foreach ($rspta['data'] as $key => $value) {
            $data .= '<option  value=' . $value['id'] . ' title="'.$value['imagen_perfil'].'">' . $cont++ . '. ' . $value['nombres'] .' - '. $value['numero_documento'] . '</option>';
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

        if ($rspta['status'] == true) {

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

        if ($rspta['status'] == true) {

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

        if ($rspta['status'] == true) {

          foreach ($rspta['data'] as $key => $value) {

            $data .= '<option  value=' .  $value['id']  . '>' .  $value['nombre'] .'</option>';
          }

          $retorno = array(
            'status' => true, 
            'message' => 'Salió todo ok', 
            'data' => '<option  value="1" >NINGUNO</option>' . $data, 
          );
  
          echo json_encode($retorno, true);

        } else {

          echo json_encode($rspta, true); 
        }
      break;      

      /* ══════════════════════════════════════ D E S E M P E Ñ O ══════════════════════════════════════ */

      case 'select2DesempenioTrabajador':         
         
        $rspta=$ajax_general->select2_desempenio_trabajador( ); $cont = 1; $data = "";

        if ($rspta['status'] == true) {

          foreach ($rspta['data'] as $key => $value) {

            $data .= '<option  value=' .  $value['id']  . '>' .  $value['nombre'] .'</option>';
          }

          $retorno = array(
            'status' => true, 
            'message' => 'Salió todo ok', 
            'data' => '<option  value="1" >NINGUNO</option>' . $data, 
          );
  
          echo json_encode($retorno, true);

        } else {

          echo json_encode($rspta, true); 
        }
      break;

      case 'select2DesempenioPorTrabajdor': 
    
        $rspta = $ajax_general->select2_desempenio_por_trabajdor($_GET["id_trabajador"]); $cont = 1; $data = "";

        if ($rspta['status'] == true) {

          foreach ($rspta['data'] as $key => $value) {  

            $data .= '<option value="'.$value['iddesempenio'].'" idtrabajador="'.$value['idtrabajador'].'">' . $value['nombre_desempenio'] .'</option>';
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

        if ($rspta['status'] == true) {

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

      case 'select2ProveedorFiltro': 
    
        $rspta=$ajax_general->select2_proveedor_filtro();  $cont = 1; $data = "";

        if ($rspta['status'] == true) {

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

      case 'select2ProveedorFiltroCompra': 
    
        $rspta=$ajax_general->select2ProveedorFiltroCompra($_SESSION['idproyecto'] );  $cont = 1; $data = "";

        if ($rspta['status'] == true) {

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

        if ($rspta['status'] == true) {

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
        
        if ($rspta['status'] == true) {

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

        if ($rspta['status'] == true) {

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

        if ($rspta['status'] == true) {

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

        if ($rspta['status'] == true) {

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

      

      /* ══════════════════════════════════════ T I P O   T I E R R A   C O N C R E T O ══════════════════════════════════════ */
      case 'select2TierraConcreto': 
    
        $rspta = $ajax_general->select2_tierra_concreto(); $cont = 1; $data = "";

        if ($rspta['status'] == true) {

          foreach ($rspta['data'] as $key => $value) {  

            $data .= '<option value=' . $value['id'] . '>' . $value['nombre'] .'</option>';
          }

          $retorno = array(
            'status' => true, 
            'message' => 'Salió todo ok', 
            'data' => '<option value="1" title="insumo" >Insumo</option>'.$data, 
          );
  
          echo json_encode($retorno, true);

        } else {

          echo json_encode($rspta, true); 
        }
      break;

      /* ══════════════════════════════════════ CLASIFICACION DE GRUPO ══════════════════════════════════════ */
      case 'select2ClasificacionGrupo': 
    
        $rspta = $ajax_general->select2_clasificacion_grupo(); $cont = 1; $data = "";

        if ($rspta['status'] == true) {

          foreach ($rspta['data'] as $key => $value) {  

            $data .= '<option value=' . $value['id'] . '>' . $value['nombre'] .'</option>';
          }

          $retorno = array(
            'status' => true, 
            'message' => 'Salió todo ok', 
            'data' => '<option value="1" title="insumo" >USOS GENERALES</option>'.$data, 
          );
  
          echo json_encode($retorno, true);

        } else {

          echo json_encode($rspta, true); 
        }
      break;

      /* ══════════════════════════════════════ P R O D U C T O ══════════════════════════════════════ */
      case 'mostrar_producto':
        $rspta = $ajax_general->mostrar_producto($_POST["idproducto"]); 
        echo json_encode($rspta, true);
      break;

      case 'buscar_precio_x_marca':
        $rspta = $ajax_general->buscar_precio_x_marca($_POST["idproducto"], $_POST["marca"]); 
        echo json_encode($rspta, true);
      break;

      case 'tblaActivosFijos':
          
        $rspta = $ajax_general->tblaActivosFijos(); 
        //echo json_encode($rspta, true);
        //Vamos a declarar un array
        $datas = []; 

        if ($rspta['status'] == true) {
          foreach ($rspta['data'] as $key => $reg) {

            $img_parametro = ""; $img = "";  $color_stock = "";
  
            if (empty($reg['imagen'])) {
              $img = 'src="../dist/docs/material/img_perfil/producto-sin-foto.svg"';
            } else {
              $img = 'src="../dist/docs/material/img_perfil/' . $reg['imagen'] . '"';
              $img_parametro = $reg['imagen'];
            }
  
            $ficha_tecnica = !empty($reg['ficha_tecnica']) ? ( '<center><a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg['ficha_tecnica'] . '" data-toggle="tooltip" data-original-title="Ver Ficha Técnica"><i class="far fa-file-pdf fa-2x text-success"></i></a></center>')
              : ( '<center><span class="text-center" data-toggle="tooltip" data-original-title="Vacío"> <i class="far fa-times-circle fa-2x text-danger"></i></span></center>');
  
            $datas[] = [
              "0" => '<button class="btn btn-warning" onclick="agregarDetalleComprobante(' . $reg['idproducto'] . ', \'' .  htmlspecialchars($reg['nombre'], ENT_QUOTES) . '\', \'' . $reg['categoria'] . '\', \'' . $reg['nombre_medida'] . '\', \'SIN COLOR\', \'' . $reg['promedio_precio'] . '\', 0, \'' . $reg['promedio_precio'] . '\', \'' .  $img_parametro . '\', \'' . $reg['ficha_tecnica'] . '\')" data-toggle="tooltip" data-original-title="Agregar Activo"><span class="fa fa-plus"></span></button>',
              "1" => '<div class="user-block w-250px">'.
                '<img class="profile-user-img img-responsive img-circle" ' .  $img . ' alt="user image" onerror="' . $imagen_error .  '">'.
                '<span class="username"><p class="mb-0" >' . $reg['nombre'] . '</p></span>
                <span class="description"><b>UM: </b>' . $reg['nombre_medida'] . '</span>'.
              '</div>',
              "2" => $reg['categoria'],
              "3" => $reg['promedio_precio'],
              "4" => '<textarea class="form-control textarea_datatable" readonly cols="30" rows="1">' . $reg['descripcion'] . '</textarea>' . $toltip ,
              "5" => $reg['idproducto'] ,
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
        //echo json_encode($rspta, true);
        //Vamos a declarar un array
        $datas = []; 

        if ($rspta['status'] == true) {
          foreach ($rspta['data'] as $key => $reg) {

            $img_parametro = ""; $img = "";  $color_stock = "";
  
            if (empty($reg['imagen'])) {
              $img = 'src="../dist/docs/material/img_perfil/producto-sin-foto.svg"';
            } else {
              $img = 'src="../dist/docs/material/img_perfil/' . $reg['imagen'] . '"';
              $img_parametro = $reg['imagen'];
            }
  
            $ficha_tecnica = !empty($reg['ficha_tecnica']) ? ( '<center><a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg['ficha_tecnica'] . '" data-toggle="tooltip" data-original-title="Ver Ficha Técnica"><i class="far fa-file-pdf fa-2x text-success"></i></a></center>')
              : ( '<center><span class="text-center" data-toggle="tooltip" data-original-title="Vacío"> <i class="far fa-times-circle fa-2x text-danger"></i></span></center>');
  
            $datas[] = [
              "0" => '<button class="btn btn-warning" onclick="agregarDetalleComprobante(' . $reg['idproducto'] . ', \'' .  htmlspecialchars($reg['nombre'], ENT_QUOTES) . '\', \'' . $reg['categoria'] . '\', \''  . $reg['nombre_medida'] . '\', \'SIN COLOR\', \'' . $reg['promedio_precio'] . '\', 0, \'' . $reg['promedio_precio'] . '\', \'' .  $img_parametro . '\', \'' . $reg['ficha_tecnica'] . '\')" data-toggle="tooltip" data-original-title="Agregar Activo"><span class="fa fa-plus"></span></button>',
              "1" => '<div class="user-block w-250px">'.
                '<img class="profile-user-img img-responsive img-circle" ' .  $img . ' alt="user image" onerror="' . $imagen_error .  '">'.
                '<span class="username"><p class="mb-0" >' . $reg['nombre'] . '</p></span>
                <span class="description"><b>UM: </b>' . $reg['nombre_medida'] . '</span>'.
              '</div>',
              "2" => $reg['categoria'],
              "3" => $reg['promedio_precio'],
              "4" => '<textarea class="form-control textarea_datatable" readonly cols="30" rows="1">' . $reg['descripcion'] . '</textarea>' . $toltip ,
              "5" => $reg['idproducto'] ,
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
          foreach ($rspta['data'] as $key => $reg) {

            $img_parametro = ""; $img = "";  $color_stock = "";
  
            if (empty($reg['imagen'])) {
              $img = '../dist/docs/material/img_perfil/producto-sin-foto.svg';
            } else {
              $img = '../dist/docs/material/img_perfil/' . $reg['imagen'] ;
              $img_parametro = $reg['imagen'];
            }  
            
            $data_btn = 'btn-add-producto-'.$reg['idproducto'];
            $datas[] = [
              "0" => '<button class="btn btn-warning '.$data_btn.'" onclick="agregarDetalleComprobante(' . $reg['idproducto'] . ')" data-toggle="tooltip" data-original-title="Agregar Activo"><span class="fa fa-plus"></span></button>',
              "1" => '<div class="user-block w-250px">'.
                '<img class="profile-user-img img-responsive img-circle cursor-pointer" src="'.$img.'" onclick="ver_img_material(\''.$img.'\', \''.encodeCadenaHtml($reg['nombre']).'\')" alt="user image" onerror="' . $imagen_error .  '">'.
                '<span class="username"><p class="mb-0" >' . $reg['nombre'] . '</p></span>
                <span class="description"><b>UM: </b>' . $reg['nombre_medida'] . '</span>
                <span style="display: none;" class="promedio_precio_'.$reg['idproducto'].'">' . $reg['promedio_precio'] . '</span>'.
              '</div>',
              "2" => '<select onchange="buscar_precio_x_marca(this, ' . $reg['idproducto'] . ');" name="marca_table" id="marca_table_'.$reg['idproducto'].'">'.$reg['marcas_html'].'</select>',
              "3" => $reg['categoria'],
              "4" => '<span id="precio_table_'.$reg['idproducto'].'">'.$reg['promedio_precio'].'</span>' ,
              "5" => '<textarea class="form-control textarea_datatable" readonly cols="30" rows="1">' . $reg['descripcion'] . '</textarea>' . $toltip ,
              "6" => $reg['idproducto'] ,
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
      /* ══════════════════════════════════════ S E R V i C I O S  M A Q U I N A R I A ════════════════════════════ */

      case 'select2_servicio_maquina':
        $tipo ='1';
        $rspta = $ajax_general->select2_servicio($tipo);
        $data = "";

        if ($rspta['status'] == true) {

           foreach ($rspta['data'] as $key => $reg) { 
            $data .= '<option value=' . $reg['idmaquinaria'] . '>' . $reg['nombre'] . ' : ' . $reg['codigo_maquina'] . ' -> ' . $reg['nombre_proveedor'] . '</option>';
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

      /* ══════════════════════════════════════ S E R V i C I O S  E Q U I P O S ════════════════════════════ */

      case 'select2_servicio_equipo':
        $tipo ='2';
        $rspta = $ajax_general->select2_servicio($tipo);
        $data = "";
        if ($rspta['status'] == true) {

          foreach ($rspta['data'] as $key => $reg) { 
           $data .= '<option value=' . $reg['idmaquinaria'] . '>' . $reg['nombre'] . ' : ' . $reg['codigo_maquina'] . ' -> ' . $reg['nombre_proveedor'] . '</option>';
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

      /* ══════════════════════════════════════ C O M P R A   D E   I N S U M O ════════════════════════════ */

      case 'ver_compra_editar':
        $rspta = $compra_insumos->mostrar_compra_para_editar($_POST["idcompra_proyecto"]);
        //Codificar el resultado utilizando json
        echo json_encode($rspta, true);    
      break;

      case 'detalle_compra_de_insumo':

        $id_insumo  = isset($_GET["id_insumo"]) ? limpiarCadena($_GET["id_insumo"]) : "";
        $class_resaltar_insumo = ( empty($id_insumo) ? "" : "bg-warning") ;

        $rspta      = $compra_insumos->ver_detalle_compra($_GET['id_compra']);

        $subtotal = 0; 

        $inputs = '<!-- Tipo de Empresa -->
          <div class="col-lg-6">
            <div class="form-group">
              <label class="font-size-15px" for="idproveedor">Proveedor</label>
              <h5 class="form-control form-control-sm" >'.$rspta['data']['razon_social'].'</h5>
            </div>
          </div>
          <!-- fecha -->
          <div class="col-lg-3">
            <div class="form-group">
              <label class="font-size-15px" for="fecha_compra">Fecha </label>
              <span class="form-control form-control-sm"><i class="far fa-calendar-alt"></i>&nbsp;&nbsp;&nbsp;'.format_d_m_a($rspta['data']['fecha_compra']).' </span>
            </div>
          </div>
          <!-- fecha -->
          <div class="col-lg-3">
            <div class="form-group">
              <label class="font-size-15px" for="fecha_compra">Glosa </label>
              <span class="form-control form-control-sm">'.$rspta['data']['glosa'].' </span>
            </div>
          </div>
          <!-- Tipo de comprobante -->
          <div class="col-lg-3">
            <div class="form-group">
              <label class="font-size-15px" for="tipo_comprobante">Tipo Comprobante</label>
              <span  class="form-control form-control-sm"> '. ((empty($rspta['data']['tipo_comprobante'])) ? '- - -' :  $rspta['data']['tipo_comprobante'])  .' </span>
            </div>
          </div>
          <!-- Nota de Factura
          <label for="slt2_serie_comprobante">Nro. Factura <sup class="text-danger">(Para Nota de credito*)</sup></label>-->
          <div class="col-lg-2">
            <div class="form-group">
              <label class="font-size-15px" for="nc_serie_comprobante">N° Factura</label>
              <span  class="form-control form-control-sm"> '. ((empty($rspta['data']['nc_serie_comprobante'])) ? '- - -' :  $rspta['data']['nc_serie_comprobante']).' </span>
            </div>
          </div>
          <!-- serie_comprobante-->
          <div class="col-lg-2">
            <div class="form-group">
              <label class="font-size-15px" for="serie_comprovante">N° de Comprobante</label>
              <span  class="form-control form-control-sm"> '. ((empty($rspta['data']['serie_comprobante'])) ? '- - -' :  $rspta['data']['serie_comprobante']).' </span>
            </div>
          </div>
          <!-- IGV-->
          <div class="col-lg-1 " >
            <div class="form-group">
              <label class="font-size-15px" for="igv">IGV</label>
              <span class="form-control form-control-sm"> '.$rspta['data']['val_igv'].' </span>                                 
            </div>
          </div>
          <!-- Descripcion-->
          <div class="col-lg-4">
            <div class="form-group">
              <label class="font-size-15px" for="descripcion">Descripción </label> <br />
              <textarea class="form-control form-control-sm" readonly rows="1">'.((empty($rspta['data']['descripcion'])) ? '- - -' :$rspta['data']['descripcion']).'</textarea>
            </div>
        </div>';

        $tbody = ""; $cont = 1;
        foreach ($rspta['data']['detalle_producto'] as $key => $reg) {

          $bg_resaltar = ($id_insumo == $reg['idproducto']? $class_resaltar_insumo : "" );
          $ficha = empty($reg['ficha_tecnica']) ? ( '<i class="fa-regular fa-file-pdf fa-2x '. $bg_resaltar.' text-gray-50"></i>') : ( '<a target="_blank" href="dist/docs/material/ficha_tecnica/' . $reg['ficha_tecnica'] . '"><i class="fa-regular fa-file-pdf fa-2x text-primary"></i></a>');
          $img_product = '../dist/docs/material/img_perfil/'. (empty($reg['imagen']) ? 'producto-sin-foto.svg' : $reg['imagen'] );

          $tbody .= '<tr class="filas ">
            <td class="text-center p-6px">' . $cont++ . '</td>
            <td class="text-center p-6px">' . $ficha . '</td>
            <td class="text-left p-6px">
              <div class="user-block text-nowrap">
                <img class="profile-user-img img-responsive img-circle cursor-pointer '. $bg_resaltar.'" src="../'.$img_product.'" alt="user image" onclick="ver_img_material(\''.$img_product.'\', \'' . encodeCadenaHtml( $reg['nombre']) . '\', null)" onerror="this.src=\'../dist/svg/404-v2.svg\';" >
                <span class="username"><p class="mb-0 '. $bg_resaltar.'">' . $reg['nombre'] . '</p></span>
                <span class="description '. $bg_resaltar.'"><b>Categoría: </b>' . $reg['categoria'] . '</span>
              </div>
            </td>
            <td class="text-left p-6px"> <span class="'. $bg_resaltar.'">' . $reg['unidad_medida'] . '</span></td>
            <td class="text-center p-6px"><span class="'. $bg_resaltar.'">' . $reg['cantidad'] . '</span></td>		
            <td class="text-right p-6px"><span class="'. $bg_resaltar.'">' . number_format($reg['precio_sin_igv'], 2, '.',',') . '</span></td>
            <td class="text-right p-6px"><span class="'. $bg_resaltar.'">' . number_format($reg['igv'], 2, '.',',') . '</span></td>
            <td class="text-right p-6px"><span class="'. $bg_resaltar.'">' . number_format($reg['precio_con_igv'], 2, '.',',') . '</span></td>
            <td class="text-right p-6px"><span class="'. $bg_resaltar.'">' . number_format($reg['descuento'], 2, '.',',') . '</span></td>
            <td class="text-right p-6px"><span class="'. $bg_resaltar.'">' . number_format($reg['subtotal'], 2, '.',',') .'</span></td>
          </tr>';
        }         

        $tabla_detalle = '<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
          <table class="table table-striped table-bordered table-condensed table-hover" id="tabla_detalle_compra_de_insumo">
            <thead style="background-color:#ff6c046b">
              <tr class="text-center hidden">
                <th class="p-10px">Proveedor:</th>
                <th class="text-center p-10px" colspan="9" >'.$rspta['data']['razon_social'].'</th>
              </tr>
              <tr class="text-center hidden">                
                <th class="text-center p-10px" colspan="2" >'.((empty($rspta['data']['tipo_comprobante'])) ? '' :  $rspta['data']['tipo_comprobante']). ' ─ ' . ((empty($rspta['data']['serie_comprobante'])) ? '' :  $rspta['data']['serie_comprobante']) .'</th>
                <th class="p-10px">Fecha:</th>
                <th class="text-center p-10px" colspan="3" >'.format_d_m_a($rspta['data']['fecha_compra']).'</th>
                <th class="p-10px">Glosa:</th>
                <th class="text-center p-10px" colspan="3" >'.$rspta['data']['glosa'].'</th>
              </tr>
              <tr class="text-center">
                <th class="text-center p-10px" >#</th>
                <th class="text-center p-10px">F.T.</th>
                <th class="p-10px">Material</th>
                <th class="p-10px">U.M.</th>
                <th class="p-10px">Cant.</th>
                <th class="p-10px">V/U</th>
                <th class="p-10px">IGV</th>
                <th class="p-10px">P/U</th>
                <th class="p-10px">Desct.</th>
                <th class="p-10px">Subtotal</th>
              </tr>
            </thead>
            <tbody>'.$tbody.'</tbody>          
            <tfoot>
              <tr>
                  <td class="p-0" colspan="8"></td>
                  <td class="p-0 text-right"> <h6 class="mt-1 mb-1 mr-1">'.$rspta['data']['tipo_gravada'].'</h6> </td>
                  <td class="p-0 text-right">
                    <h6 class="mt-1 mb-1 mr-1 pl-1 font-weight-bold text-nowrap formato-numero-conta"><span>S/</span>' . number_format($rspta['data']['subtotal'], 2, '.',',') . '</h6>
                  </td>
                </tr>
                <tr>
                  <td class="p-0" colspan="8"></td>
                  <td class="p-0 text-right">
                    <h6 class="mt-1 mb-1 mr-1">IGV('.( ( empty($rspta['data']['val_igv']) ? 0 : floatval($rspta['data']['val_igv']) )  * 100 ).'%)</h6>
                  </td>
                  <td class="p-0 text-right">
                    <h6 class="mt-1 mb-1 mr-1 pl-1 font-weight-bold text-nowrap formato-numero-conta"><span>S/</span>' . number_format($rspta['data']['igv'], 2, '.',',') . '</h6>
                  </td>
                </tr>
                <tr>
                  <td class="p-0" colspan="8"></td>
                  <td class="p-0 text-right"> <h5 class="mt-1 mb-1 mr-1 font-weight-bold">TOTAL</h5> </td>
                  <td class="p-0 text-right">
                    <h5 class="mt-1 mb-1 mr-1 pl-1 font-weight-bold text-nowrap formato-numero-conta"><span>S/</span>' . number_format($rspta['data']['total'], 2, '.',',') . '</h5>
                  </td>
                </tr>
            </tfoot>
          </table>
        </div> ';
        $retorno = ['status' => true, 'message' => 'todo oka', 'data' => $inputs . $tabla_detalle ];
        echo json_encode( $retorno, true );

      break;       

      // :::::::::::::::::::::::::: S E C C I O N   C O M P R A ::::::::::::::::::::::::::
      case 'guardar_y_editar_compra':
        if (empty($idcompra_proyecto)) {
          $rspta = $compra_insumos->insertar( $idproyecto, $idproveedor, $fecha_compra,  $tipo_comprobante, $serie_comprobante,$slt2_serie_comprobante, $val_igv, $descripcion, 
          $glosa, $total_venta, $subtotal_compra, $igv_compra, $estado_detraccion, $_POST["idproducto"], $_POST["unidad_medida"], 
          $_POST["nombre_color"], $_POST["nombre_marca"], $_POST["cantidad"], $_POST["precio_sin_igv"], $_POST["precio_igv"],  $_POST["precio_con_igv"], $_POST["descuento"], 
          $tipo_gravada, $_POST["ficha_tecnica_producto"]);
          //precio_sin_igv,precio_igv,precio_total
          echo json_encode($rspta, true);
        } else {
          $rspta = $compra_insumos->editar( $idcompra_proyecto, $idproyecto, $idproveedor, $fecha_compra,  $tipo_comprobante, $serie_comprobante,$slt2_serie_comprobante, $val_igv, 
          $descripcion, $glosa, $total_venta, $subtotal_compra, $igv_compra, $estado_detraccion, $_POST["idproducto"], $_POST["unidad_medida"], 
          $_POST["nombre_color"], $_POST["nombre_marca"], $_POST["cantidad"], $_POST["precio_sin_igv"], $_POST["precio_igv"],  $_POST["precio_con_igv"], $_POST["descuento"], 
          $tipo_gravada, $_POST["ficha_tecnica_producto"] );
    
          echo json_encode($rspta, true);
        }
    
      break;

      /* ══════════════════════════════════════ C O M P R A   D E   A C T I V O   F I J O  ════════════════════════════ */

      case 'detalle_compra_de_activo_fijo':

        $id_activo_fijo  = isset($_GET["id_activo_fijo"]) ? limpiarCadena($_GET["id_activo_fijo"]) : "";
        $class_resaltar_insumo = ( empty($id_activo_fijo) ? "" : "bg-warning") ;

        $rspta      = $compra_activos_fijos->ver_detalle_compra($_GET['id_compra']);

        $subtotal = 0;    $ficha = ''; 

        $inputs = '<!-- Tipo de Empresa -->
          <div class="col-lg-6">
            <div class="form-group">
              <label class="font-size-15px" for="idproveedor">Proveedor</label>
              <h5 class="form-control form-control-sm" >'.$rspta['data']['razon_social'].'</h5>
            </div>
          </div>
          <!-- fecha -->
          <div class="col-lg-3">
            <div class="form-group">
              <label class="font-size-15px" for="fecha_compra">Fecha </label>
              <span class="form-control form-control-sm"><i class="far fa-calendar-alt"></i>&nbsp;&nbsp;&nbsp;'.format_d_m_a($rspta['data']['fecha_compra']).' </span>
            </div>
          </div>
          <!-- fecha -->
          <div class="col-lg-3">
            <div class="form-group">
              <label class="font-size-15px" for="fecha_compra">Glosa </label>
              <span class="form-control form-control-sm">'.$rspta['data']['glosa'].' </span>
            </div>
          </div>
          <!-- Tipo de comprobante -->
          <div class="col-lg-3">
            <div class="form-group">
              <label class="font-size-15px" for="tipo_comprovante">Tipo Comprobante</label>
              <span  class="form-control form-control-sm"> '. ((empty($rspta['data']['tipo_comprobante'])) ? '- - -' :  $rspta['data']['tipo_comprobante'])  .' </span>
            </div>
          </div>
          <!-- serie_comprovante-->
          <div class="col-lg-2">
            <div class="form-group">
              <label class="font-size-15px" for="serie_comprovante">N° de Comprobante</label>
              <span  class="form-control form-control-sm"> '. ((empty($rspta['data']['serie_comprobante'])) ? '- - -' :  $rspta['data']['serie_comprobante']).' </span>
            </div>
          </div>
          <!-- IGV-->
          <div class="col-lg-1 " >
            <div class="form-group">
              <label class="font-size-15px" for="igv">IGV</label>
              <span class="form-control form-control-sm"> '.$rspta['data']['val_igv'].' </span>                                 
            </div>
          </div>
          <!-- Descripcion-->
          <div class="col-lg-6">
            <div class="form-group">
              <label class="font-size-15px" for="descripcion">Descripción </label> <br />
              <textarea class="form-control form-control-sm" readonly rows="1">'.((empty($rspta['data']['descripcion'])) ? '- - -' :$rspta['data']['descripcion']).'</textarea>
            </div>
        </div>';

        $tbody = ""; $cont = 1;

        foreach ( $rspta['data']['detalle_producto'] as $key => $reg) {

          $bg_resaltar = ($id_activo_fijo == $reg['idproducto']? $class_resaltar_insumo : "" );
          $ficha = empty($reg['ficha_tecnica']) ? ( '<i class="fa-regular fa-file-pdf fa-2x '. $bg_resaltar.' text-gray-50"></i>') : ( '<a target="_blank" href="dist/docs/material/ficha_tecnica/' . $reg['ficha_tecnica'] . '"><i class="fa-regular fa-file-pdf fa-2x text-primary"></i></a>');
          $img_product = '../dist/docs/material/img_perfil/'. (empty($reg['imagen']) ? 'producto-sin-foto.svg' : $reg['imagen'] );

          $tbody .= '<tr class="filas ">
            <td class="text-center p-6px">' . $cont++ . '</td>
            <td class="text-center p-6px">' . $ficha . '</td>
            <td class="text-left p-6px">
              <div class="user-block text-nowrap">
                <img class="profile-user-img img-responsive img-circle cursor-pointer '. $bg_resaltar.'" src="../'.$img_product.'" alt="user image" onclick="ver_img_material(\''.$img_product.'\', \'' . encodeCadenaHtml( $reg['nombre']) . '\', null)" onerror="this.src=\'../dist/svg/404-v2.svg\';" >
                <span class="username"><p class="mb-0 '. $bg_resaltar.'">' . $reg['nombre'] . '</p></span>
                <span class="description '. $bg_resaltar.'"><b>Clasificación: </b>' . $reg['clasificacion'] . '</span>
              </div>
            </td>
            <td class="text-left p-6px"> <span class="'. $bg_resaltar.'">' . $reg['unidad_medida'] . '</span></td>
            <td class="text-center p-6px"><span class="'. $bg_resaltar.'">' . $reg['cantidad'] . '</span></td>		
            <td class="text-right p-6px"><span class="'. $bg_resaltar.'">' . number_format($reg['precio_sin_igv'], 2, '.',',') . '</span></td>
            <td class="text-right p-6px"><span class="'. $bg_resaltar.'">' . number_format($reg['igv'], 2, '.',',') . '</span></td>
            <td class="text-right p-6px"><span class="'. $bg_resaltar.'">' . number_format($reg['precio_con_igv'], 2, '.',',') . '</span></td>
            <td class="text-right p-6px"><span class="'. $bg_resaltar.'">' . number_format($reg['descuento'], 2, '.',',') . '</span></td>
            <td class="text-right p-6px"><span class="'. $bg_resaltar.'">' . number_format($reg['subtotal'], 2, '.',',') .'</span></td>
          </tr>';
        }         

        $tabla_detalle = '<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
          <table class="table table-striped table-bordered table-condensed table-hover" id="tabla_detalle_compra_de_insumo">
            <thead class="bg-color-127ab6ba">
              <tr class="text-center hidden">
                <th class="p-10px">Proveedor:</th>
                <th class="text-center p-10px" colspan="9" >'.$rspta['data']['razon_social'].'</th>
              </tr>
              <tr class="text-center hidden">                
                <th class="text-center p-10px" colspan="2" >'.((empty($rspta['data']['tipo_comprobante'])) ? '' :  $rspta['data']['tipo_comprobante']). ' ─ ' . ((empty($rspta['data']['serie_comprobante'])) ? '' :  $rspta['data']['serie_comprobante']) .'</th>
                <th class="p-10px">Fecha:</th>
                <th class="text-center p-10px" colspan="3" >'.format_d_m_a($rspta['data']['fecha_compra']).'</th>
                <th class="p-10px">Glosa:</th>
                <th class="text-center p-10px" colspan="3" >'.$rspta['data']['glosa'].'</th>
              </tr>
              <tr class="text-center">
                <th class="text-center p-10px" >#</th>
                <th class="text-center p-10px">F.T.</th>
                <th class="p-10px">Material</th>
                <th class="p-10px">U.M.</th>
                <th class="p-10px">Cant.</th>
                <th class="p-10px">V/U</th>
                <th class="p-10px">IGV</th>
                <th class="p-10px">P/U</th>
                <th class="p-10px">Desct.</th>
                <th class="p-10px">Subtotal</th>
              </tr>
            </thead>
            <tbody>'.$tbody.'</tbody>          
            <tfoot>
              <tr>
                  <td class="p-0" colspan="8"></td>
                  <td class="p-0 text-right"> <h6 class="mt-1 mb-1 mr-1">'.$rspta['data']['tipo_gravada'].'</h6> </td>
                  <td class="p-0 text-right">
                    <h6 class="mt-1 mb-1 mr-1 pl-1 font-weight-bold text-nowrap formato-numero-conta"><span>S/</span>' . number_format($rspta['data']['subtotal'], 2, '.',',') . '</h6>
                  </td>
                </tr>
                <tr>
                  <td class="p-0" colspan="8"></td>
                  <td class="p-0 text-right">
                    <h6 class="mt-1 mb-1 mr-1">IGV('.( ( empty($rspta['data']['val_igv']) ? 0 : floatval($rspta['data']['val_igv']) )  * 100 ).'%)</h6>
                  </td>
                  <td class="p-0 text-right">
                    <h6 class="mt-1 mb-1 mr-1 pl-1 font-weight-bold text-nowrap formato-numero-conta"><span>S/</span>' . number_format($rspta['data']['igv'], 2, '.',',') . '</h6>
                  </td>
                </tr>
                <tr>
                  <td class="p-0" colspan="8"></td>
                  <td class="p-0 text-right"> <h5 class="mt-1 mb-1 mr-1 font-weight-bold">TOTAL</h5> </td>
                  <td class="p-0 text-right">
                    <h5 class="mt-1 mb-1 mr-1 pl-1 font-weight-bold text-nowrap formato-numero-conta"><span>S/</span>' . number_format($rspta['data']['total'], 2, '.',',') . '</h5>
                  </td>
                </tr>
            </tfoot>
          </table>
        </div> ';
        $retorno = ['status' => true, 'message' => 'todo oka', 'data' => $inputs . $tabla_detalle ,];
        echo json_encode( $retorno, true );

      break;

      /* ══════════════════════════════════════ E M P R E S A   A   C A R G O ══════════════════════════════════════ */
      case 'select2EmpresaACargo': 
    
        $rspta = $ajax_general->select2_empresa_a_cargo(); $cont = 1; $data = "";
        
        if ($rspta['status'] == true) {

          foreach ($rspta['data'] as $key => $value) { 
            $data .= '<option value="' . $value['id'] . '" title="'.$value['logo'].'" >' . $value['nombre'] . ' - ' . $value['numero_documento'].'</option>';
          }

          $retorno = array(
            'status' => true, 
            'message' => 'Salió todo ok', 
            'data' => '<option value="1" title="emogi-carita-feliz.svg" >NINGUNO</option>'.$data, 
          );
  
          echo json_encode($retorno, true);

        } else {

          echo json_encode($rspta, true); 
        }
      break;

      /* ══════════════════════════════════════ M A R C A S  ════════════════════════════ */
      case 'select2Marcas': 
    
        $rspta = $ajax_general->marcas(); $cont = 1; $data = "";
        
        if ($rspta['status'] == true) {

          foreach ($rspta['data'] as $key => $value) { 
            $data .= '<option value="' . $value['idmarca'] . '" title="'.$value['nombre_marca'].'" >' . $value['nombre_marca'] .'</option>';
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

      default: 
        $rspta = ['status'=>'error_code', 'message'=>'Te has confundido en escribir en el <b>swich.</b>', 'data'=>[]]; echo json_encode($rspta, true); 
      break;
    }
      
  }

  ob_end_flush();
?>
