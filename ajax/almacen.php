<?php
  ob_start();

  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {
    //Validamos el acceso solo al usuario logueado y autorizado.
    if ($_SESSION['compra_insumos'] == 1) {

      require_once "../modelos/Almacen.php";
      require_once "../modelos/Resumen_insumos.php";

      $almacen = new Almacen($_SESSION['idusuario']);
      $resumen_insumos = new ResumenInsumos($_SESSION['idusuario']);

      setlocale(LC_ALL, 'es_ES.utf8');
      date_default_timezone_set('America/Lima');
      $imagen_error = "this.src='../dist/svg/user_default.svg'";
      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

      // ::::::::::::::::::: TRANFERENCIA USO PROYECTO ::::::::::::::::::::::::::::::::::::::::::::
      $idproyecto_origen_tup  = isset($_POST["idproyecto_origen_tup"])? limpiarCadena($_POST["idproyecto_origen_tup"]):"";   
      $fecha_tup	            = isset($_POST["fecha_tup"])? limpiarCadena($_POST["fecha_tup"]):"";
      $descripcion_tup	      = isset($_POST["descripcion_tup"])? limpiarCadena($_POST["descripcion_tup"]):"";     

      // ::::::::::::::::::: TRANFERENCIA ENTRE PROYECTO ::::::::::::::::::::::::::::::::::::::::::::
      $idproyecto_origen_tep  = isset($_POST["idproyecto_origen_tep"])? limpiarCadena($_POST["idproyecto_origen_tep"]):"";   
      $fecha_tep	            = isset($_POST["fecha_tep"])? limpiarCadena($_POST["fecha_tep"]):"";
      $descripcion_tep	      = isset($_POST["descripcion_tep"])? limpiarCadena($_POST["descripcion_tep"]):"";

      // ::::::::::::::::::: TRANFERENCIA A ALMACEN GENERAL ::::::::::::::::::::::::::::::::::::::::::::
      $idproyecto_origen_tag  = isset($_POST["idproyecto_origen_tag"])? limpiarCadena($_POST["idproyecto_origen_tag"]):"";   
      $fecha_tag	            = isset($_POST["fecha_tag"])? limpiarCadena($_POST["fecha_tag"]):"";
      $descripcion_tag	      = isset($_POST["descripcion_tag"])? limpiarCadena($_POST["descripcion_tag"]):"";

      // ::::::::::::::::::: ALMACEN X DIA ::::::::::::::::::::::::::::::::::::::::::::
      $idalmacen_salida_xp  = isset($_POST["idalmacen_salida_xp"])? limpiarCadena($_POST["idalmacen_salida_xp"]):"";      
      $idalmacen_resumen_xp = isset($_POST["idalmacen_resumen_xp"])? limpiarCadena($_POST["idalmacen_resumen_xp"]):"";      
      $idproyecto_xp        = isset($_POST["idproyecto_xp"])? limpiarCadena($_POST["idproyecto_xp"]):"";      
      $producto_xp	        = isset($_POST["producto_xp"])? limpiarCadena($_POST["producto_xp"]):"";
      $fecha_ingreso_xp	    = isset($_POST["fecha_ingreso_xp"])? limpiarCadena($_POST["fecha_ingreso_xp"]):"";
      $dia_ingreso_xp	      = isset($_POST["dia_ingreso_xp"])? limpiarCadena($_POST["dia_ingreso_xp"]):"";
      $marca_xp	            = isset($_POST["marca_xp"])? limpiarCadena($_POST["marca_xp"]):"";
      $cantidad_xp	        = isset($_POST["cantidad_xp"])? limpiarCadena($_POST["cantidad_xp"]):"";

      switch ($_GET["op"]) {  

        // ══════════════════════════════════════  T R A N S F E R E N C I A   U S O   P R O Y E C T O ══════════════════════════════════════
        case 'guardar_y_editar_tup':
          $rspta = $almacen->insertar_almacen( $idproyecto_origen_tup, $_POST["idproyecto_destino_tup"], $_POST["idalmacen_general_tup"], 'EPO',
          $fecha_tup, $_POST["idproducto_tup"], $_POST["tipo_prod_tup"], $_POST["marca_tup"], $_POST["cantidad_tup"], $descripcion_tup );
          echo json_encode($rspta, true);           
        break;         

        case 'tabla_almacen':         

          $rspta = $almacen->tbla_principal($_POST["id_proyecto"], $_POST["nombre_insumo"], $_POST["fip"], $_POST["ffp"], $_POST["fpo"] );
          //echo json_encode($rspta, true); die();
          // $rspta = $almacen->tbla_principal(6, '2023-04-18', '2023-04-22', 'semanal' );

          $codigoHTMLbodyProducto =''; 
          $codigoHTMLhead1=""; $codigoHTMLhead2=""; $codigoHTMLhead3=""; $codigoHTMLhead4=""; $codigoHTMLhead5="" ;          

          foreach ($rspta['data']['fechas'] as $key => $val) {
            $codigoHTMLhead1 .= '<th class="py-0 " colspan="'.$val['cantidad_dias'].'">'.$val['name_month'] . ' - ' . $val['name_year'].'</th>';
            foreach ($val['dia'] as $key => $val2) {
              $codigoHTMLhead2 .= '<th class="py-0">'.$val2['number_day'].'</th>';     
              $codigoHTMLhead4 .= '<th class="py-0">'.$val2['name_day_abrev'].'</th>';
            }
          }          

          echo '<thead class="st_tr_style bg-white">
          <tr class="thead-f1">
            <th class="py-0 " rowspan="4">#</th> 
            <th class="py-0 " rowspan="4">Code</th> 
            <th class="py-0 " rowspan="4">Producto</th>
            <th class="py-0 " rowspan="4">UND</th> 
            <th class="py-0 " rowspan="4">TIPO </th>           
            '.$codigoHTMLhead1.'
            <th class="py-0 " rowspan="4">ENTRADA/ <br> SALIDA</th> 
            <th class="py-0 " rowspan="4">SALDO</th>
          </tr>';

          echo '<tr class="py-0 thead-f2">'. $codigoHTMLhead2 . '</tr>';
          echo '<tr class="py-0 thead-f3">'; 
            foreach ($rspta['data']['data_sq'] as $key => $val) { echo '<th class="py-0 text-nowrap" colspan="'.$val['colspan'].'">'.$val['nombre_sq'].' '. $val['num_sq'].'</th>'; }
          echo'</tr>';
          echo '<tr class="py-0 thead-f4">'. $codigoHTMLhead4 . '</tr>';
          // echo $codigoHTMLhead5;                                             
          echo '</thead>'; //die();

          echo '<tbody class="data_tbody_almacen"> ';       

          foreach ($rspta['data']['producto'] as $key => $val) {
            // $color_filas =  ($key%2==0 ? 'bg-color-e9e9e9' : '') ;
            $color_filas = '';
            $html_dias_entrada = ''; $html_dias_salida = ''; 

            foreach ($val['almacen'] as $key2 => $val2) {              
              $html_dias_entrada .= '<td class="py-0 text-success cursor-pointer '.$color_filas.'" data-toggle="tooltip" data-original-title="'.$val2['entrada_group'].'" onclick="modal_ver_almacen(\''.$val2['fecha'].'\', \''.$val['idalmacen_resumen'] .'\', \'ENTRADA\');">'.$val2['entrada_cant'].'</td>';
              $html_dias_salida .= '<td class="py-0 text-danger cursor-pointer '.$color_filas.'" data-toggle="tooltip" data-original-title="'.$val2['salida_group'].'" onclick="modal_ver_almacen(\''.$val2['fecha'].'\', \''.$val['idalmacen_resumen'] .'\', \'SALIDA\');">'.$val2['salida_cant'].'</td>';
            }

            $saldo = floatval($val['stok']) ;
            $codigoHTMLbodyProducto = '<tr class="text-nowrap '.$color_filas.'">
              <td class="py-0" rowspan="2">'.($key +1).'</td>
              <td class="py-0" rowspan="2">'.$val['idproducto'].'</td> 
              <td class="py-0 text_producto text-nowrap" rowspan="2"> <span class="name_producto_'.$val['idproducto'].'">'.$val['nombre_producto'].'</span> <br> <small><b>Clasf:</b> '.$val['categoria'].' </small></td>
              <td class="py-0 " rowspan="2">'.$val['um_abreviacion'].'</td>
              <td class="py-0 "> Entrada </td>          
              '.$html_dias_entrada.'          
              <td class="py-0"> <span class="entrada_total_'.$val['idproducto'].'">'.number_format($val['total_entrada'] , 2,',','.').'</span> </td>
              <td class="py-0 '.($saldo < 0 ? 'text-danger' : '').'" rowspan="2"><span class="saldo_total_'.$val['idproducto'].'">'.number_format($saldo, 2,',','.').'</span></td>
            </tr>';

            echo $codigoHTMLbodyProducto .' <tr><td class="py-0 '.$color_filas.'">Salida</td>  '.$html_dias_salida.'<td class="py-0 '.$color_filas.'"><span class="salida_total_'.$val['idproducto'].'">'.number_format($val['total_salida'], 2,',','.').'</span></td> </tr>';             

          }

          echo '</tbody>';

          //Codificar el resultado utilizando json
          // echo json_encode($rspta, true);
        break;  

        case 'tabla-almacen-resumen':          

          $rspta=$almacen->tbla_principal_resumen($_GET["id_proyecto"]);
          
          //Vamos a declarar un array
          $data= Array(); $cont=1;

          if ($rspta['status'] == true) {

            foreach ($rspta['data'] as $key => $val) {               
          
              $data[]=array(
                "0"=>$cont++,
                "1"=>'<button class="btn bg-gradient-dark btn-sm py-0" onclick="agregar_grupos(' . $val['idproducto'] .', \''.$val['idclasificacion_grupo'] . '\')" data-toggle="tooltip" data-original-title="Agregar grupo" title="Agregar grupo"><i class="fa-solid fa-layer-group"></i></button>
                  <button class="btn btn-info btn-sm py-0" onclick="modal_ver_almacen(null, '. $val['idalmacen_resumen'].')" data-toggle="tooltip" data-original-title="Ver Movimientos"><i class="fas fa-eye"></i></button>' . $toltip,
                "2"=> $val['idproducto_f'],
                "3"=>'<div > <span class="username"><p class="text-primary m-b-02rem" >'. $val['nombre_producto'] .'</p></span> </div>',
                "4"=>  ($val['nombre_clasificacion_grupo'] == null ? 'por clasificar' :  $val['nombre_clasificacion_grupo'] ),
                "5"=> $val['um_abreviacion'],
                "6"=> $val['total_ingreso'],
                "7"=> $val['total_egreso'],
                "8"=> $val['total_stok'] ,       
              );
            }
            $results = array(
              "sEcho"=>1, //Información para el datatables
              "iTotalRecords"=>count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
              "data"=>$data);
            echo json_encode($results, true);

          } else {
            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          }
        break;

        // ══════════════════════════════════════  T R A N S F E R E N C I A   E N T R E   P R O Y E C T O ══════════════════════════════════════
        case 'guardar_y_editar_tep':          
          $rspta = $almacen->insertar_almacen( $idproyecto_origen_tep, $_POST["idproyecto_destino_tep"], $_POST["idalmacen_general_tep"], 'EEP',
          $fecha_tep, $_POST["idproducto_tep"], $_POST["tipo_prod_tep"], $_POST["marca_tep"], $_POST["cantidad_tep"], $descripcion_tep );
          echo json_encode($rspta, true);          
        break; 

        case 'tbla_saldos_anteriores':          

          $rspta=$almacen->tbla_saldos_anteriores($_GET["idproyecto"], $_GET["idproducto"]);
          
          //Vamos a declarar un array
          $data= Array(); $cont=1;

          if ($rspta['status'] == true) {

            foreach ($rspta['data'] as $key => $val) {               
          
              $data[]=array(
                "0"=>$cont++,
                "1"=>'<div > <span class="username"><p class="text-primary m-b-02rem" >'. $val['proyecto'] .'</p></span> </div>'  ,
                "2"=> $val['entrada'],
                "3"=> $val['salida'],
                "4"=> $val['entrada'] - $val['salida'],              
              );
            }
            $results = array(
              "sEcho"=>1, //Información para el datatables
              "iTotalRecords"=>count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
              "data"=>$data);
            echo json_encode($results, true);

          } else {
            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          }
        break;

        // ══════════════════════════════════════  T R A N S F E R E N C I A   A L M A C E N   G E N E R A L ══════════════════════════════════════
        case 'guardar_y_editar_tag':
          $rspta = $almacen->insertar_almacen( $idproyecto_origen_tag, $_POST["idproyecto_destino_tag"], $_POST["idalmacen_general_tag"], 'EPG',
          $fecha_tag, $_POST["idproducto_tag"], $_POST["tipo_prod_tag"], $_POST["marca_tag"], $_POST["cantidad_tag"], $descripcion_tag );
          echo json_encode($rspta, true);           
        break;

        case 'otros_almacenes':
          $rspta = $almacen->otros_almacenes();
          echo json_encode( $rspta, true) ;
        break;          

        // ══════════════════════════════════════ A L M A C E N   X   D I A ══════════════════════════════════════
        case 'guardar_y_editar_almacen_x_dia':

          if (empty($idalmacen_salida_xp)) {
            $rspta = $almacen->insertar_almacen_x_dia( );
            echo json_encode($rspta, true);
          } else {
            $rspta = $almacen->editar_almacen_x_dia($idalmacen_salida_xp, $idalmacen_resumen_xp, $idproyecto_xp, $producto_xp, $fecha_ingreso_xp, $dia_ingreso_xp, $marca_xp, $cantidad_xp);
            echo json_encode($rspta, true);
          }
          
        break; 
        case 'desactivar_x_dia':
          $rspta = $almacen->desactivar_x_dia($_GET["id_tabla"]);
          echo json_encode( $rspta, true) ;
        break;
  
        case 'eliminar_x_dia':
          $rspta = $almacen->eliminar_x_dia($_GET["id_tabla"]);
          echo json_encode( $rspta, true) ;
        break;

        case 'ver_almacen':
          $rspta = $almacen->ver_almacen( $_POST["id_proyecto"], $_POST["id_almacen_s"], $_POST["id_producto"] );          
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;    
        
        case 'tbla-ver-almacen-detalle':          

          $rspta=$almacen->tbla_ver_almacen_detalle($_GET["idalmacen_resumen"], $_GET["fecha"], $_GET["tipo_mov"] );          
          
          $data= Array(); $cont=1;            # DEFINIMOS VARIABLES

          if (is_array($rspta)) {             # VALIDAMOS IS ES UN ARRAY
            if ($rspta['status'] == true) {   # VALIDAMOS LOS DATOS ESTAN CORRECTOS

              foreach ($rspta['data'] as $key => $val) {               
            
                $data[]=array(
                  "0"=>$cont++,
                  //"1"=>'<button class="btn btn-warning btn-sm" onclick="ver_editar_almacen_x_dia('.$val['idalmacen_detalle'].')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>'.
                  //  ' <button class="btn btn-danger btn-sm" onclick="eliminar_x_dia('.$val['idalmacen_detalle'].', \''.$val['cantidad'].'\', \''.encodeCadenaHtml($val['nombre_producto']).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i></button>',
                  "1"=> $val['fecha'] , 
                  "2"=> '<div > <span class="'. $val['class_tipo_mov'] .'">'. $val['tipo_mov_1'] .'</span> <span>'. $val['tipo_mov_2'] .'</span> </div>',
                  "3"=>  $val['destino'] ,
                  "4"=> $val['cantidad_real'],
                  "5"=> '<div class="bg-color-242244245 " style="overflow: auto; resize: vertical; height: 25px;" >'.
                   '<b>Desc.: </b>'. $val['descripcion'] . '<br>'.
                   '<b>Dia: </b>'. $val['name_day'] . '<br>'.
                   '<b>Marca: </b>'. $val['marca'] . '<br>'.
                   '<b>Cod. Movimiento: </b>'. $val['idalmacen_detalle_v2'] . '<br>'.
                   '<b>Cod. Almacen Prod.: </b>'. $val['idalmacen_resumen_v2'] . '<br>'.
                  '</div>',            
                );
              }
              $results = array(
                "sEcho"=>1, //Información para el datatables
                "iTotalRecords"=>count($data), //enviamos el total registros al datatable
                "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
                "data"=>$data);
              echo json_encode($results, true);
  
            } else {
              echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
            }
          } else {
            echo json_encode($rspta, true);
          }          
          
        break;        

        // ══════════════════════════════════════ TRASNFERENCIA MASIVA ══════════════════════════════════════ 
        case 'guardar_y_editar_tm':
          $rspta = $almacen->insertar_almacen( $_POST["idproyecto_origen_tm"], $_POST["idproyecto_destino_tm"], $_POST["almacen_destino_tm"], 'EPG',
          $_POST["fecha_tm"], $_POST["idproducto_tm"], $_POST["tipo_prod_tm"], $_POST["marca_tm"], $_POST["cantidad_trns"], $_POST["descripcion_tm"] );
          echo json_encode($rspta, true);           
        break;

        case 'transferencia-masiva-almacen':          

          $rspta=$almacen->tbla_principal_resumen_stock($_GET["id_proyecto"], $_GET["unidad_medida"], $_GET["categoria"], $_GET["es_epp"]);
          $ag = $almacen->otros_almacenes();
          $almacen_general = '';
          foreach ($ag['data'] as $key => $val) {
            $almacen_general .= '<option value="'. $val['idalmacen_general'].'">'. $val['nombre_almacen'].'</option>';
          }
          
          //Vamos a declarar un array
          $data= Array(); $cont=1;        
          
          echo '<table class="table table-sm table-hover">
          <thead>
            <tr>
              <th style="width: 10px">#</th>
              <th>Producto</th>
              <th style="width: 100px">UM.</th>
              <th style="width: 100px">Marca</th>
              <th style="width: 200px"> Almacen <select id="almacen_general_tm"  class="w-200px font-size-12px" onchange="cambiar_de_almacen(this)" >'.$almacen_general.' </select> </th>
              <th style="width: 100px">Stock</th>              
              <th style="width: 150px">Cant. 
                <select id="enviar_todo_tm" class="w-150px font-size-12px" placeholder="Marca" onchange=" enviar_todo_stok(this)">
                  <option value="" >Seleccione</option>
                  <option value="0" >Limpiar</option>
                  <option value="1" >Enviar todo</option>
                </select>
              </th>
              <th style="width: 60px"><i class="fa-solid fa-list-check"></i>
                <div class="custom-control custom-switch cursor-pointer" data-toggle="tooltip" data-original-title="Activar todos">
                  <input class="custom-control-input" type="checkbox" id="marcar_todo" onchange="Activar_masivo();">
                  <label for="marcar_todo" class="custom-control-label cursor-pointer"></label>
                </div>
              </th>
            </tr>
          </thead>
          <tbody>';

          foreach ($rspta['data'] as $key => $val) {      
            $option_marcas = '';
            $marcas = $almacen->marcas_x_producto($_GET["id_proyecto"], $val['idproducto']); 
            foreach ($marcas['data'] as $key2 => $val2) {
              $option_marcas .= '<option value="'. $val2['marca'].'">'. $val2['marca'].'</option>';
            }
            
            echo '<tr>
              <td>'. $key +1 .'</td>
              <td>
                <input type="hidden" name="idproducto_tm[]" id="idproducto_tm'. $key +1 .'" value="'.$val['idproducto'].'" disabled />        
                <input type="hidden" name="tipo_prod_tm[]" id="tipo_prod_tm'. $key +1 .'" value="'.$val['tipo'].'" disabled /> 
                <input type="hidden" name="idproyecto_destino_tm[]" id="idproyecto_destino_tm'. $key +1 .'"  value="NULL" disabled />  
                '. $val['nombre_producto'] .'
              </td>
              <td>'.$val['um_abreviacion'].'</td>
              <td>
                <div class="form-group mb-0">                  
                  <select name="marca_tm[]" id="marca_tm'. ($key +1).'" class="form-control form-control-sm w-200px marca_all_tm" disabled>'.$option_marcas.' </select>
                </div>    
              </td>
              <td>
                <div class="form-group mb-0">                  
                  <select name="almacen_destino_tm[]"  id="almacen_destino_tm'. ($key +1).'" class="form-control form-control-sm w-200px almacen_destino_all_tm" disabled>'.$almacen_general.' </select>
                </div>    
              </td>
              <td class="text-right"> <span id="total_stok_tm_'. $key +1 .'"  >'. number_format($val['total_stok'], 2, '.', ',') .'</span> </td>
              <td>
                <div class="form-group mb-0">                  
                  <input type="number" class="form-control form-control-sm w-150px cant_all_tm" name="cantidad_tr'. ($key +1) .'" id="cantidad__trns'. $key +1 .'" onkeyup="replicar_data_input(\'#cantidad__trns'. $key +1 .'\', \'#cantidad__trns_env'. $key +1 .'\')" disabled placeholder="cantidad"  step="0.01" max="'.$val['total_stok'].'"/>
                  <input type="hidden" name="cantidad_trns[]" class="form-control" id="cantidad__trns_env'. ($key +1).'" disabled/>
                </div>     
              </td>
              <td>
                <div class="custom-control custom-switch">
                  <input class="custom-control-input checked_all" type="checkbox" id="customCheckbox'. $key +1 .'" onchange="update_valueChec('. $key +1 .')" >
                  <label for="customCheckbox'.  $key +1 .'" class="custom-control-label cursor-pointer"></label>
                </div>     
              </td>
            </tr>'; 
        
            // $data[]=array(
            //   "0"=>$cont++,
            //   "1"=>'<button class="btn btn-info btn-sm" onclick="modal_ver_almacen(null, '. $val['idalmacen_resumen'].')" data-toggle="tooltip" data-original-title="Ver Movimientos"><i class="fas fa-eye"></i></button>' . $toltip,
            //   "2"=> $val['idproducto_f'],
            //   "3"=>'<div > <span class="username"><p class="text-primary m-b-02rem" >'. $val['nombre_producto'] .'</p></span> </div>',
            //   "4"=> $val['um_abreviacion'],
            //   "5"=> $val['total_ingreso'],
            //   "6"=> $val['total_egreso'],
            //   "7"=> $val['total_stok'] ,       
            // );
          }
          echo '</tbody>  </table>';
          
        break;

         // :::::::::::::::::::::::::: S E C C I O N   G R U P O S ::::::::::::::::::::::::::
        case 'actualizar_grupo':
            
          $rspta = $resumen_insumos->actualizar_grupo( $_POST["idproducto_g"], $_POST["idclasificacion_grupo_g"], $_POST['idproyecto_grp']);
          //var_dump($idactivos_fijos,$idproveedor);
          echo json_encode($rspta, true);          
      
        break;

        // ══════════════════════════════════════ SELECT 2 ══════════════════════════════════════ 

        case 'select2ProductosTodos': 
    
          $rspta = $almacen->select2_productos_todos($_GET["idproyecto"]); $cont = 1; $data = "";
          
          if ($rspta['status'] == true) {  
            foreach ($rspta['data'] as $key => $value) {   
              $data .= '<option value="' . $value['idproducto'] . '" unidad_medida="' . $value['nombre_medida'] . '" >' . $value['nombre_producto'] .' - '. $value['clasificacion'] .'</option>';
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

        case 'select2Productos': 
    
          $rspta = $almacen->select2_productos($_GET["idproyecto"]); $cont = 1; $data = "";
          
          if ($rspta['status'] == true) {  
            foreach ($rspta['data'] as $key => $value) {   
              $idpr   = $value['idproducto'];
              $id_ar  = $value['idalmacen_resumen'];
              $um     = $value['unidad_medida'];
              $saldo = $value['saldo'];
              $tipo = $value['tipo'];
              $data .= '<option value="'.$idpr.'" id_ar= "'.$id_ar.'" saldo= "'.$saldo.'" tipo= "'.$tipo.'" unidad_medida="'.$um.'" >' . $value['nombre_producto'] .' - '. $value['categoria'] .' - Saldo: '. $saldo .'</option>';
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

        case 'select2ProductosMasEPP': 
    
          $rspta = $almacen->select2ProductosMasEPP($_GET["idproyecto"]); $cont = 1; $data = "";
          
          if ($rspta['status'] == true) {  
            foreach ($rspta['data'] as $key => $value) {   
              $idpr   = $value['idproducto'];
              $id_ar  = $value['idalmacen_resumen'];
              $um     = $value['unidad_medida'];
              $saldo = $value['saldo'];
              $tipo = $value['tipo'];
              $data .= '<option value="'.$idpr.'" id_ar= "'.$id_ar.'" saldo= "'.$saldo.'" tipo= "'.$tipo.'" unidad_medida="'.$um.'" >' . $value['nombre_producto'] .' - '. $value['categoria'] .' - Saldo: '. $saldo .'</option>';
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

        case 'select2Proyecto': 
    
          $rspta = $almacen->select2_proyecto($_SESSION['idproyecto'] ); $cont = 1; $data = "";
          
          if ($rspta['status'] == true) {  
            foreach ($rspta['data'] as $key => $value) {   
              $id   = $value['idproyecto'];  $estado=  $value['estado'];  
              $data .= '<option value="'.$id.'" estado="'.$estado.'" >' . $value['nombre_codigo'] .'</option>';
            }  
            $retorno = array( 'status' => true, 'message' => 'Salió todo ok', 'data' => $data, );    
            echo json_encode($retorno, true);  
          } else {  
            echo json_encode($rspta, true); 
          }
        break;

        case 'select2UnidadMedida': 
    
          $rspta = $almacen->select2_unidad_medida(); $cont = 1; $data = "";
          
          if ($rspta['status'] == true) {  
            foreach ($rspta['data'] as $key => $value) {   
              $data .= '<option value="' . $value['idunidad_medida'] . '" >' . $value['abreviacion'] .' - '. $value['nombre_medida'] .'</option>';
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

        case 'select2Categoria': 
    
          $rspta = $almacen->select2_categoria(); $cont = 1; $data = "";
          
          if ($rspta['status'] == true) {  
            foreach ($rspta['data'] as $key => $value) {   
              $data .= '<option value="' . $value['idcategoria_insumos_af'] . '" >' . $value['nombre']  .'</option>';
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

        case 'marcas_x_producto':
          $rspta = $almacen->marcas_x_producto($_POST["id_proyecto"], $_POST["id_producto"]);          
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break; 

        default: 
          $rspta = ['status'=>'error_code', 'message'=>'Te has confundido en escribir en el <b>swich.</b>', 'data'=>[]]; echo json_encode($rspta, true); 
        break;
        
      }    

      //Fin de las validaciones de acceso
    } else {
      $retorno = ['status'=>'nopermiso', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
      echo json_encode($retorno);
    }
  }

  ob_end_flush();
?>
