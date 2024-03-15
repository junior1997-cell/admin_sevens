<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {

    if ($_SESSION['clasificacion_grupo'] == 1) {
      
      require_once "../modelos/Clasificacion_de_grupo.php";
      require_once "../modelos/Compra_insumos.php";
      require_once "../modelos/Sub_contrato.php";

      $clasificacion_de_grupo = new Clasificacion_de_grupo();
      $compra_insumos         = new Compra_insumos();   
			$sub_contrato           = new Sub_contrato();

      date_default_timezone_set('America/Lima');   $date_now = date("d_m_Y__h_i_s_A");

      $imagen_error = "this.src='../dist/svg/404-v2.svg'";
      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';
      $scheme_host =  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/admin_sevens/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');

      // :::::::::::::::::::::::::: S E C C I O N   G R U P O  ::::::::::::::::::::::::::
      $idproyecto       = isset($_POST["idproyecto"]) ? limpiarCadena($_POST["idproyecto"]) : "";
      $idclasificacion_grupo    = isset($_POST["idclasificacion_grupo"]) ? limpiarCadena($_POST["idclasificacion_grupo"]) : "";
      $nombre_grupo      = isset($_POST["nombre_grupo"]) ? encodeCadenaHtml($_POST["nombre_grupo"] ) : "";
      $descripcion_grupo = isset($_POST["descripcion_grupo"]) ? encodeCadenaHtml($_POST["descripcion_grupo"] ) : "";

      // :::::::::::::::::::::::::: S E C C I O N   A S I G N A C I O N   D E   P R O Y E C T O  ::::::::::::::::::::::::::
      $id_proyecto_grupo = isset($_POST["id_proyecto_grupo"]) ? $_POST["id_proyecto_grupo"]  : "";
      
      switch ($_GET["op"]) {
        // :::::::::::::::::::::::::: S E C C I O N   G R U P O  ::::::::::::::::::::::::::
        case 'guardar_y_editar_grupo':
          
          if (empty($idclasificacion_grupo)) {
            
            $rspta = $clasificacion_de_grupo->insertar_grupo($idproyecto, $nombre_grupo, $descripcion_grupo);
            
            echo json_encode( $rspta, true);

          } else {            
             
            $rspta = $clasificacion_de_grupo->editar_grupo($idproyecto, $idclasificacion_grupo, $nombre_grupo, $descripcion_grupo);
            
            echo json_encode( $rspta, true) ;
          }
        break;
    
        case 'desactivar_grupo':
          $rspta = $clasificacion_de_grupo->desactivar_grupo( $_GET["id_tabla"] );
          echo json_encode( $rspta, true) ;
        break;      

        case 'eliminar_grupo':
          $rspta = $clasificacion_de_grupo->eliminar_grupo( $_GET["id_tabla"] );
          echo json_encode( $rspta, true) ;
        break;

        case 'activar_grupo':
          $rspta = $clasificacion_de_grupo->activar_grupo( $_GET["id_tabla"] );
          echo json_encode( $rspta, true) ;
        break;
    
        case 'mostrar_grupo':
          $rspta = $clasificacion_de_grupo->mostrar_grupo($idclasificacion_grupo);          
          echo json_encode( $rspta, true) ;
        break;
    
        case 'tbla_principal_grupo':
          $rspta = $clasificacion_de_grupo->tbla_principal_grupo($_GET["id_proyecto"]);
          //Vamos a declarar un array
          $data = [];  $cont=1;         

          if ($rspta['status'] == true) {
            while ($reg = $rspta['data']->fetch_object()) {  

              $disabed_uso_general = ($reg->idclasificacion_grupo == 1 ? 'disabled' : '');
              $funcion_eliminar = ($reg->idclasificacion_grupo > 1 ? 'eliminar_grupo(' . $reg->idclasificacion_grupo .', \''.encodeCadenaHtml($reg->nombre).'\')' : '');
              $message_tooltip = ($reg->idclasificacion_grupo == 1 ? 'No se puede eliminar' : 'Eliminar o papelera');
              $data[] = [
                "0"=>$cont++,
                "1" =>  ' <button class="btn bg-gradient-dark btn-sm" onclick="mostrar_proyectos_asignados(' . $reg->idclasificacion_grupo. ');" data-toggle="tooltip" data-original-title="Asignar a un proyecto"><i class="fa-solid fa-file-import"></i></button>' .
                ' <button class="btn btn-warning btn-sm" onclick="mostrar_grupo(' . $reg->idclasificacion_grupo. ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' .
                ($reg->estado ? ' <button class="btn btn-danger btn-sm '. $disabed_uso_general.' " onclick="'.$funcion_eliminar.'" data-toggle="tooltip" data-original-title="'.$message_tooltip.'"><i class="fas fa-skull-crossbones"></i></button>' : 
                ' <button class="btn btn-success btn-sm" onclick="activar_grupo(' . $reg->idclasificacion_grupo .', \''.encodeCadenaHtml($reg->nombre).'\')" data-toggle="tooltip" data-original-title="Activar"><i class="fa-solid fa-check"></i></button>'),
                "2" => $reg->nombre,                
                "3" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly="">' . $reg->descripcion . '</textarea>',
                "4" => ($reg->estado ? '<span class="text-center badge badge-success">Activo</span>' : '<span class="text-center badge badge-danger">Desactivado</span>') . $toltip,
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

        case 'lista_de_grupo':

          $rspta = $clasificacion_de_grupo->lista_de_grupo($idproyecto);
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true) ;

        break;

        case 'proyectos_asignados':
          //Obtenemos todos los permisos de la tabla permisos          
          $rspta = $clasificacion_de_grupo->lista_de_proyectos();
      
          //Obtener los permisos asignados al usuario
          $id = $_POST['id'];
          $marcados = $clasificacion_de_grupo->proyectos_y_grupos($id);
          //Declaramos el array para almacenar todos los permisos marcados
          $proyecto_array = array();
      
          //Almacenar los permisos asignados al usuario en el array
          while ($per = $marcados['data']->fetch_object()) {
            array_push($proyecto_array, $per->idproyecto);
          }
      
          //Mostramos la lista de permisos en la vista y si están o no marcados
          echo '<div class="card"><div class="card-body" style="overflow-x: auto;"><div class="row" >';
          foreach ($rspta['data'] as $key => $val) {
            $estado = "";
            if ($val['estado'] == '2') {  
              $estado = '<span class="text-center badge badge-danger">No empezado</span>';              
            } else if ($val['estado'] == '1') {  
              $estado = '<span class="text-center badge badge-warning">En proceso</span>';              
            } else {  
              $estado = '<span class="text-center badge badge-success">Terminado</span>';              
            }
            if ($key % 7 === 0) {   echo '<div class="col-md-12 col-lg-12 col-xl-12" >';   } # abrimos el: col-lg-2      
            
            $sw = in_array($val['idproyecto'], $proyecto_array) ? 'checked' : '';
      
            echo '<div class="form-group">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="id_proyecto_grupo_' . $val['idproyecto'] . '" name="id_proyecto_grupo[]" value="' . $val['idproyecto'] . '" ' . $sw . '>
                <label class="custom-control-label" for="id_proyecto_grupo_' . $val['idproyecto'] . '">' . $val['nombre_codigo'] .' '. $estado . '</label>
              </div>
            </div> ';
            if (($key + 1) % 7 === 0 || $key === count($rspta['data']) - 1) { echo "</div>"; } # cerramos el: col-lg-2
          }
          echo '</div></div></div>';
        break;

        case 'guardar_y_editar_proyecto_grupo':
          
          if ( !empty($_POST["idclasificacion_grupo_p"]) ) {            
            $rspta = $clasificacion_de_grupo->asigar_grupo_a_proyecto($_POST["idclasificacion_grupo_p"],$_POST["id_proyecto_grupo"]);            
            echo json_encode( $rspta, true);

          } else {             
            $retorno = ['status' => 'error_ing_pool', 'message' => 'Los datos no estan completos.', 'data' =>[], 'user' =>$_SESSION["nombre"]];            
            echo json_encode( $retorno, true) ;
          }
        break;

        // :::::::::::::::::::::::::: S E C C I O N    C O M P R A   Y   S U B C O N T R A T O ::::::::::::::::::::::::::

        case 'tbla_principal_compra_subcontrato':
          $rspta = $clasificacion_de_grupo->tbla_principal_compra_subcontrato($_GET["id_proyecto"], $_GET["idclasificacion_grupo"], $_GET["fecha_1"], $_GET["fecha_2"], $_GET["id_proveedor"], $_GET["comprobante"]);
          //Vamos a declarar un array
          $data = [];  $cont=1;    
          //echo json_encode( $rspta, true) ;  

          if ($rspta['status'] == true) {

            $detalle_fila = '';  $data_comprobante = ''; $btn_tipo = ''; $btn_comprobante_toltip = ''; $btn_detalle_toltip = '';

            foreach ($rspta['data'] as $key => $reg) {   

              if ($reg['modulo'] == 'compra_insumos') {
                $detalle_fila     = 'ver_detalle_compras(' . $reg['idcompra_proyecto'] .','. $reg['idproducto'] .')';
                $data_comprobante = 'comprobante_compras(\''.$cont.'\', \''.$reg['idcompra_proyecto'].'\' , \''.$reg['tipo_comprobante'].' '.(empty($reg['serie_comprobante']) ?  "" :  '- '.$reg['serie_comprobante']).'\', \''.$reg['proveedor'].'\', \''.$reg['fecha_compra'].'\')'; 
                $btn_tipo         = (empty($reg['cant_comprobantes']) ? 'btn-outline-info' : 'btn-info');  
                $btn_comprobante_toltip   = (empty($reg['cant_comprobantes']) ? 'Vacío' : ($reg['cant_comprobantes']==1 ?  $reg['cant_comprobantes'].' comprobante' : $reg['cant_comprobantes'].' comprobantes'));
                $btn_detalle_toltip = 'Ver detalle compra';
              } else if ($reg['modulo'] == 'subcontrato') {
                $detalle_fila     = 'ver_detalle_subcontrato(' . $reg['idcompra_proyecto'] .')';
                $data_comprobante = 'comprobante_subcontrato(\''.$cont.'\', \''.$reg['comprobante'].'\' , \''.$reg['tipo_comprobante'].' '.(empty($reg['serie_comprobante']) ?  "" :  ''.$reg['serie_comprobante']).' - '.$reg['proveedor'].'\')'; 
                $btn_tipo         = (empty($reg['comprobante']) ? 'btn-outline-info' : 'btn-info');  
                $btn_comprobante_toltip   = (empty($reg['comprobante']) ? 'Vacío' : 'Ver comprobante');   
                $btn_detalle_toltip = 'Ver detalle Subcontrato';
              }                     
              
              $data[] = [
                "0"=>$cont,
                "1" => '<button class="btn btn-info btn-sm" onclick="'.$detalle_fila.'" data-toggle="tooltip" data-original-title="'.$btn_detalle_toltip.'"><i class="fa fa-eye"></i></button>' ,
                "2" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'. $reg['nombre_producto'] .'</textarea>',
                "3" => $reg['nombre_dia'],
                "4" => $reg['fecha_compra'],
                "5" => $reg['cantidad'],
                "6" => $reg['precio_con_igv'],
                "7" => $reg['descuento'],
                "8" => $reg['subtotal'],
                "9" => '<div class="w-150px recorte-text text-bold text-primary" data-toggle="tooltip" data-original-title="'. $reg['proveedor'] .'">'. $reg['proveedor'] .'</div>',
                "10" => '<center> <button class="btn '.$btn_tipo.' btn-sm" onclick="'. $data_comprobante .'" data-toggle="tooltip" data-original-title="'.$btn_comprobante_toltip.'"><i class="fas fa-file-invoice fa-lg"></i></button> </center>'.$toltip,                
              ];
              $cont++;
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

        case 'total_compra_subcontrato':

          $rspta = $clasificacion_de_grupo->total_compra_subcontrato($_POST["id_proyecto"], $_POST["idclasificacion_grupo"], $_POST["fecha_1"], $_POST["fecha_2"], $_POST["id_proveedor"], $_POST["comprobante"]);
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true) ;

        break;

        // :::::::::::::::::::::::::: S E C C I O N   C O M P R O B A N T E  C O M P R A :::::::::::::::::::::::::: 
        case 'tbla_comprobantes_compra':
          $cont_compra = $_GET["num_orden"];
          $id_compra = $_GET["id_compra"];
          $rspta = $compra_insumos->tbla_comprobantes( $id_compra );
          //Vamos a declarar un array
          $data = []; $cont = 1;        
          
          if ($rspta['status']) {
            while ($reg = $rspta['data']->fetch_object()) {
              $data[] = [
                "0" => $cont,
                "1" => '<div class="text-nowrap">'.             
                  '<a class="btn btn-info btn-sm" href="../dist/docs/compra_insumo/comprobante_compra/'.$reg->comprobante.'"  download="'.$cont_compra.'·'.$cont.' '.removeSpecialChar((empty($reg->serie_comprobante) ?  " " :  ' ─ '.$reg->serie_comprobante).' ─ '.$reg->razon_social).' ─ '. format_d_m_a($reg->fecha_compra).'" data-toggle="tooltip" data-original-title="Descargar" ><i class="fas fa-cloud-download-alt"></i></a>' .
                '</div>'.$toltip,
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

        // :::::::::::::::::::::::::: S E C C I O N  S U B C O N T R A T O :::::::::::::::::::::::::: 
        case 'ver_datos_subcontrato':
          $rspta=$sub_contrato->verdatos($_POST["idsubcontrato"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);	
				break;

        // :::::::::::::::::::::::::: S E C C I O N    R E S U M E N ::::::::::::::::::::::::::

        case 'tbla_principal_resumen':
          $rspta = $clasificacion_de_grupo->tbla_principal_resumen($_GET["idproyecto"]);
          //Vamos a declarar un array
          $data = [];  $cont=1;         

          if ($rspta['status'] == true) {
            foreach ($rspta['data'] as $key => $reg) {            
              
              $data[] = [
                "0"=>$cont++,
                "1" => $reg['grupo'] ,
                "2" => $reg['descuento_total'],
                "3" => $reg['precio_total'],        
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

        case 'total_resumen':

          $rspta = $clasificacion_de_grupo->total_resumen($idproyecto);
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true) ;

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
