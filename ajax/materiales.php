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
      
      require_once "../modelos/Materiales.php";
      require_once "../modelos/Compra_insumos.php";

      $materiales = new Materiales();

      date_default_timezone_set('America/Lima'); $date_now = date("d-m-Y h.i.s A");

      $scheme_host =  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/admin_sevens/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');

      // input no usados        
      $idcategoria    = isset($_POST["categoria_insumos_af"]) ? limpiarCadena($_POST["categoria_insumos_af"]) : "" ;
      $color          = isset($_POST["color"]) ? limpiarCadena($_POST["color"]) : "" ;    
      $modelo         = isset($_POST["modelo"]) ? encodeCadenaHtml($_POST["modelo"]) : "" ;
      $serie          = isset($_POST["serie"]) ? limpiarCadena($_POST["serie"]) : "" ;      
      $estado_igv     = isset($_POST["estado_igv"]) ? limpiarCadena($_POST["estado_igv"]) : "" ;
      $precio_unitario= isset($_POST["precio_unitario"]) ? limpiarCadena($_POST["precio_unitario"]) : "" ;      
      $precio_sin_igv = isset($_POST["precio_sin_igv"]) ? limpiarCadena($_POST["precio_sin_igv"]) : "" ;
      $precio_igv     = isset($_POST["precio_igv"]) ? limpiarCadena($_POST["precio_igv"]) : "" ;
      $precio_total   = isset($_POST["precio_total"]) ? limpiarCadena($_POST["precio_total"]) : "" ;      

      // input usados
      $idproducto     = isset($_POST["idproducto"]) ? limpiarCadena($_POST["idproducto"]) : "" ;
      $nombre         = isset($_POST["nombre"]) ? encodeCadenaHtml($_POST["nombre"]) : "" ;      
      $unidad_medida  = isset($_POST["unidad_medida"]) ? limpiarCadena($_POST["unidad_medida"]) : "" ;      
      $descripcion    = isset($_POST["descripcion"]) ? encodeCadenaHtml($_POST["descripcion"]) : "" ; 

      $imagen1 = isset($_POST["imagen1"]) ? limpiarCadena($_POST["imagen1"]) : "";
      $imagen_ficha = isset($_POST["doc2"]) ? limpiarCadena($_POST["doc2"]) : ""; 

      switch ($_GET["op"]) {

        case 'guardaryeditar':
          // imgen
          if (!file_exists($_FILES['imagen1']['tmp_name']) || !is_uploaded_file($_FILES['imagen1']['tmp_name'])) {

            $imagen1 = $_POST["imagen1_actual"];

            $flat_img1 = false;

          } else {

            $ext1 = explode(".", $_FILES["imagen1"]["name"]);

            $flat_img1 = true;

            $imagen1 = $date_now .' '. random_int(0, 20) . round(microtime(true)) . random_int(21, 41) . '.' . end($ext1);

            move_uploaded_file($_FILES["imagen1"]["tmp_name"], "../dist/docs/material/img_perfil/" . $imagen1);
          }

          // ficha técnica
          if (!file_exists($_FILES['doc2']['tmp_name']) || !is_uploaded_file($_FILES['doc2']['tmp_name'])) {

            $ficha_tecnica = $_POST["doc_old_2"];

            $flat_ficha1 = false;

          } else {

            $ext1 = explode(".", $_FILES["doc2"]["name"]);

            $flat_ficha1 = true;

            $ficha_tecnica = $date_now .' '. random_int(0, 20) . round(microtime(true)) . random_int(21, 41) . '.' . end($ext1);

            move_uploaded_file($_FILES["doc2"]["tmp_name"], "../dist/docs/material/ficha_tecnica/" . $ficha_tecnica);
          }

          if (empty($idproducto)) {
            
            $rspta = $materiales->insertar($nombre, $idcategoria, $unidad_medida, $_POST["marcas"], $descripcion, $color, $modelo, $serie, $estado_igv, $precio_unitario, $precio_sin_igv, $precio_igv, $precio_total, $ficha_tecnica, $imagen1);
            
            echo json_encode( $rspta, true);

          } else {

            // validamos si existe LA IMG para eliminarlo
            if ($flat_img1 == true) {
              $datos_f1 = $materiales->obtenerImg($idproducto);
              $img1_ant = $datos_f1['data']['imagen'];
              if ( !empty($img1_ant) ) { unlink("../dist/docs/material/img_perfil/" . $img1_ant); }
            }
             
            $rspta = $materiales->editar($idproducto, $nombre, $idcategoria, $unidad_medida, $_POST["marcas"], $descripcion, $color, $modelo, $serie, $estado_igv, $precio_unitario, $precio_sin_igv, $precio_igv, $precio_total, $ficha_tecnica, $imagen1);
            
            echo json_encode( $rspta, true) ;
          }
        break;
    
        case 'desactivar':

          $rspta = $materiales->desactivar( $_GET["id_tabla"] );

          echo json_encode( $rspta, true) ;

        break;      

        case 'eliminar':

          $rspta = $materiales->eliminar( $_GET["id_tabla"] );

          echo json_encode( $rspta, true) ;

        break;
    
        case 'mostrar':

          $rspta = $materiales->mostrar($idproducto);
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true) ;

        break;
    
        case 'tbla_principal':
          $rspta = $materiales->tbla_principal();
          //Vamos a declarar un array
          $data = [];
          $imagen_error = "this.src='../dist/svg/404-v2.svg'";
          $cont=1;
          $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

          if ($rspta['status'] == true) {            
            
            foreach ($rspta['data'] as $key => $reg) {

              $imagen = (empty($reg['imagen']) ? 'producto-sin-foto.svg' : $reg['imagen']) ;
              
              $ficha_tecnica = empty($reg['ficha_tecnica']) ? ( '<center><i class="far fa-file-pdf fa-2x text-gray-50"></i></center>') : ( '<center><a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg['ficha_tecnica'] . '"><i class="far fa-file-pdf fa-2x text-danger" ></i></a></center>');
              
              $monto_igv = (empty($reg['precio_igv']) ?  '-' :  $reg['precio_igv']);
              
              $data[] = [
                "0"=>$cont++,
                "1" => '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg['idproducto']. ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' .
                ' <button class="btn btn-danger btn-sm" onclick="eliminar(' . $reg['idproducto'] .', \''.encodeCadenaHtml($reg['nombre']).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i></button>'. 
                ' <button class="btn btn-info btn-sm" onclick="verdatos('.$reg['idproducto'].')" data-toggle="tooltip" data-original-title="Ver datos"><i class="far fa-eye"></i></button>',
                "2" => $reg['idproducto'],
                "3" =>
                  '<div class="user-block">
                    <img class="profile-user-img img-responsive img-circle cursor-pointer" src="../dist/docs/material/img_perfil/' . $imagen . '" alt="user image" onerror="'.$imagen_error.'" onclick="ver_perfil(\'../dist/docs/material/img_perfil/' . $imagen . '\', \''.encodeCadenaHtml($reg['nombre']).'\');" data-toggle="tooltip" data-original-title="Ver imagen">
                    <span class="username"><p class="mb-0" >' . decodeCadenaHtml($reg['nombre']) . '</p></span>
                    <span class="description">' . '...</span>
                  </div>',
                "4" => $reg['nombre_medida'],
                "5" => '<div class="bg-color-242244245 " style="overflow: auto; resize: vertical; height: 45px;" >'. $reg['marca'] .'</div>',  
                "6" => $reg['promedio_precio'],
                "7" => '<button class="btn btn-info btn-sm mb-2" onclick="tbla_facuras(' . $reg['idproducto'] . ', \'' .  htmlspecialchars($reg['nombre'], ENT_QUOTES) . '\', \'' .  $reg['promedio_precio'] . '\')" data-toggle="tooltip" data-original-title="Ver compras"><i class="far fa-eye"></i></button>'. $toltip,
                "8" => $ficha_tecnica . $toltip, 
                               
                "9" => decodeCadenaHtml($reg['nombre']),
                "10" => $reg['descripcion'],
                "11" => $reg['marca_export'],
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
        //=====================================C O M P R A S  P O R   P R O D U C T O=======================================
        //=====================================C O M P R A S  P O R   P R O D U C T O=======================================
        //=====================================C O M P R A S  P O R   P R O D U C T O=======================================

        case 'tbla_facturas':
          $idproducto = $_GET["idproducto"];

          $rspta = $materiales->tbla_facturas($idproducto);
          //Vamos a declarar un array
          $data = []; $cont = 1;
          $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';
          $imagen_error = "this.src='../dist/svg/user_default.svg'";
          $ficha_tecnica = "";
          if ($rspta['status'] == true) {
            // idcompra_proyecto,num_orden, num_comprobante, fecha
            foreach ($rspta['data'] as $key => $reg) {
              // validamos si existe una ficha tecnica
              !empty($reg['ficha_tecnica'])
                ? ($ficha_tecnica = '<center><a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg['ficha_tecnica'] . '"><i class="far fa-file-pdf fa-lg text-success"></i></a></center>')
                : ($ficha_tecnica = '<center><i class="far fa-file-pdf fa-lg text-gray-50"></i></center>');

              $btn_tipo = (empty($reg['cant_comprobantes']) ? 'btn-outline-info' : 'btn-info');
              $descrip_toltip = (empty($reg['cant_comprobantes']) ? 'Vacío' : ($reg['cant_comprobantes']==1 ?  $reg['cant_comprobantes'].' comprobante' : $reg['cant_comprobantes'].' comprobantes'));       

              $data[] = [    
                "0" => $cont++,
                "1" => '<button class="btn btn-info btn-sm" onclick="ver_detalle_compras(' . $reg['idcompra_proyecto'] . ', ' .$reg['idproducto'] . ')" data-toggle="tooltip" data-original-title="Ver detalle compra"><i class="fa fa-eye"></i></button>' .
                ' <button class="btn btn-warning btn-sm" onclick="mostrar_compra_insumo(' . $reg['idcompra_proyecto'] .  ')" data-toggle="tooltip" data-original-title="Editar compra"><i class="fas fa-pencil-alt"></i></button>'. $toltip ,
                "2" => '<span class="text-primary font-weight-bold" >' . $reg['proveedor']. '</span>',    
                "3" =>'<span class="" ><b>' . $reg['tipo_comprobante'] .  '</b> '.(empty($reg['serie_comprobante']) ?  "" :  '- '.$reg['serie_comprobante']).'</span>',  
                "4" => $reg['nombre_codigo'],
                "5" => $reg['fecha_compra'],
                "6" => $reg['cantidad'],
                "7" => $reg['precio_con_igv'],
                "8" => $reg['descuento'],
                "9" => $reg['subtotal'],
                "10" => '<center> <button class="btn '.$btn_tipo.' btn-sm" onclick="comprobante_compras(\''.$reg['idcompra_proyecto'].'\', \''.$cont.'\', \''.encodeCadenaHtml($reg['tipo_comprobante'].' '.(empty($reg['serie_comprobante']) ?  "" :  '- '.$reg['serie_comprobante'])).'\', \''.format_d_m_a($reg['fecha_compra']).'\')" data-toggle="tooltip" data-original-title="'.$descrip_toltip.'"><i class="fas fa-file-invoice fa-lg"></i></button> </center>'.$toltip,
                
                "11" => $reg['tipo_comprobante'],
                "12" => $reg['serie_comprobante']

              ];
            }

            $results = [
              "sEcho" => 1, //Información para el datatables
              "iTotalRecords" => count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
              "aaData" => $data,
            ];
            echo json_encode($results, true);

          } else {
            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          }
        break;
        // :::::::::::::::::::::::::: S E C C I O N   C O M P R O B A N T E  :::::::::::::::::::::::::: 

        case 'tbla_comprobantes_compra':
          $cont_compra = $_GET["num_orden"];
          $id_compra = $_GET["id_compra"];
          $rspta = $materiales->tbla_comprobantes( $id_compra );
          //Vamos a declarar un array
          $data = []; $cont = 1;        
          $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';
          
          if ($rspta['status'] == true) {
            while ($reg = $rspta['data']->fetch_object()) {
              $data[] = [
                "0" => $cont,
                "1" => '<div class="text-nowrap">'.
                ' <a class="btn btn-info btn-sm " href="../dist/docs/compra_insumo/comprobante_compra/'.$reg->comprobante.'"  download="'.$cont_compra.'·'.$cont.' '.removeSpecialChar((empty($reg->serie_comprobante) ?  " " :  ' ─ '.$reg->serie_comprobante).' ─ '.$reg->razon_social).' ─ '. format_d_m_a($reg->fecha_compra).'" data-toggle="tooltip" data-original-title="Descargar" ><i class="fas fa-cloud-download-alt"></i></a>              
                </div>'.$toltip,
                "2" => '<a class="btn btn-info btn-sm" href="../dist/docs/compra_insumo/comprobante_compra/'.$reg->comprobante.'" target="_blank" rel="noopener noreferrer"><i class="fas fa-receipt"></i></a>' ,
                "3" => $reg->updated_at,
              ];
              $cont++;
            }
            $results = [
              "sEcho" => 1, //Información para el datatables
              "iTotalRecords" => count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
              "aaData" => $data,
            ];
            echo json_encode($results, true);
          } else {
            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
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
