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

      $idalmacen_resumen     = isset($_POST["idalmacen_resumen"])? limpiarCadena($_POST["idalmacen_resumen"]):"";      
      $idproyecto     = isset($_POST["idproyecto"])? limpiarCadena($_POST["idproyecto"]):"";      
      $fecha_ingreso	= isset($_POST["fecha_ingreso"])? limpiarCadena($_POST["fecha_ingreso"]):"";
      $dia_ingreso	  = isset($_POST["dia_ingreso"])? limpiarCadena($_POST["dia_ingreso"]):"";

      // ::::::::::::::::::: ALMACEN X DIA ::::::::::::::::::::::::::::::::::::::::::::

      $idalmacen_salida_xp  = isset($_POST["idalmacen_salida_xp"])? limpiarCadena($_POST["idalmacen_salida_xp"]):"";      
      $idalmacen_resumen_xp = isset($_POST["idalmacen_resumen_xp"])? limpiarCadena($_POST["idalmacen_resumen_xp"]):"";      
      $idproyecto_xp        = isset($_POST["idproyecto_xp"])? limpiarCadena($_POST["idproyecto_xp"]):"";      
      $producto_xp	        = isset($_POST["producto_xp"])? limpiarCadena($_POST["producto_xp"]):"";
      $fecha_ingreso_xp	    = isset($_POST["fecha_ingreso_xp"])? limpiarCadena($_POST["fecha_ingreso_xp"]):"";
      $dia_ingreso_xp	      = isset($_POST["dia_ingreso_xp"])? limpiarCadena($_POST["dia_ingreso_xp"]):"";
      $marca_xp	            = isset($_POST["marca_xp"])? limpiarCadena($_POST["marca_xp"]):"";
      $cantidad_xp	        = isset($_POST["cantidad_xp"])? limpiarCadena($_POST["cantidad_xp"]):"";

      // ::::::::::::::::::: ALMACEN GENERAL ::::::::::::::::::::::::::::::::::::::::::::
      $idalmacen_resumen_ag     = isset($_POST["idalmacen_resumen_ag"])? limpiarCadena($_POST["idalmacen_resumen_ag"]):"";      
      $idproyecto_ag     = isset($_POST["idproyecto_ag"])? limpiarCadena($_POST["idproyecto_ag"]):"";      
      $fecha_ingreso_ag	= isset($_POST["fecha_ingreso_ag"])? limpiarCadena($_POST["fecha_ingreso_ag"]):"";
      $dia_ingreso_ag	  = isset($_POST["dia_ingreso_ag"])? limpiarCadena($_POST["dia_ingreso_ag"]):"";

      switch ($_GET["op"]) {  

        case 'guardar_y_editar_almacen':

          if (empty($idalmacen_resumen)) {
            $rspta = $almacen->insertar_almacen($idproyecto, $fecha_ingreso, $dia_ingreso, $_POST["idproducto"], $_POST["marca"], $_POST["cantidad"] );
            echo json_encode($rspta, true);
          } else {
            $rspta = $almacen->editar_almacen();
            echo json_encode($rspta, true);
          }
          
        break; 

        case 'guardar_y_editar_almacen_x_dia':

          if (empty($idalmacen_salida_xp)) {
            $rspta = $almacen->insertar_almacen_x_dia( );
            echo json_encode($rspta, true);
          } else {
            $rspta = $almacen->editar_almacen_x_dia($idalmacen_salida_xp, $idalmacen_resumen_xp, $idproyecto_xp, $producto_xp, $fecha_ingreso_xp, $dia_ingreso_xp, $marca_xp, $cantidad_xp);
            echo json_encode($rspta, true);
          }
          
        break; 

        case 'tabla_almacen':         

          $rspta = $almacen->tbla_principal($_POST["id_proyecto"], $_POST["fip"], $_POST["ffp"], $_POST["fpo"] );
          echo json_encode($rspta, true); die();
          // $rspta = $almacen->tbla_principal(6, '2023-04-18', '2023-04-22', 'semanal' );

          $codigoHTMLbodyProducto =''; 
          $codigoHTMLhead1=""; $codigoHTMLhead2=""; $codigoHTMLhead3=""; $codigoHTMLhead4=""; $codigoHTMLhead5="" ;          

          foreach ($rspta['data']['fechas'] as $key => $val) {
            $codigoHTMLhead1 .= '<th colspan="'.$val['cantidad_dias'].'">'.$val['name_month'] . ' - ' . $val['name_year'].'</th>';
            foreach ($val['dia'] as $key => $val2) {
              $codigoHTMLhead2 .= '<th class="style-head">'.$val2['number_day'].'</th>';     
              $codigoHTMLhead4 .= '<th class="style-head">'.$val2['name_day_abrev'].'</th>';
            }
          }          

          echo '<thead class="st_tr_style bg-color-ffd146">
          <tr class="thead-f1">
            <th rowspan="4">#</th> 
            <th rowspan="4">Code</th> 
            <th rowspan="4">Producto</th>
            <th rowspan="4">UND</th> 
            <th rowspan="4">TIPO </th>           
            '.$codigoHTMLhead1.'
            <th rowspan="4">ENTRADA/ <br> SALIDA</th> 
            <th rowspan="4">SALDO</th>
          </tr>';

          echo '<tr class="thead-f2">'. $codigoHTMLhead2 . '</tr>';
          echo '<tr class="thead-f3">'; 
            foreach ($rspta['data']['data_sq'] as $key => $val) { echo '<th class="text-nowrap" colspan="'.$val['colspan'].'">'.$val['nombre_sq'].' '. $val['num_sq'].'</th>'; }
          echo'</tr>';
          echo '<tr class="thead-f4">'. $codigoHTMLhead4 . '</tr>';
          // echo $codigoHTMLhead5;                                             
          echo '</thead>'; //die();

          echo '<tbody class="data_tbody_almacen"> ';       

          foreach ($rspta['data']['producto'] as $key => $val) {
            $color_filas =  ($key%2==0 ? 'bg-color-e9e9e9' : '') ;
            $html_dias = ''; $html_dias_sum = ''; 

            foreach ($val['almacen'] as $key2 => $val2) {              
              $html_dias .= '<td class="cursor-pointer '.$color_filas.'" data-toggle="tooltip" data-original-title="'.$val2['entrada_group'].'" onclick="modal_ver_almacen(\''.$val2['fecha'].'\', \''.$val['idalmacen_resumen'].'\');">'.$val2['entrada_cant'].'</td>';
              $html_dias_sum .= '<td class="cursor-pointer '.$color_filas.'" data-toggle="tooltip" data-original-title="'.$val2['salida_group'].'" onclick="modal_ver_almacen(\''.$val2['fecha'].'\', \''.$val['idalmacen_resumen'].'\');">'.$val2['salida_cant'].'</td>';
            }

            $saldo = floatval($val['stok']) ;
            $codigoHTMLbodyProducto = '<tr class="text-nowrap '.$color_filas.'">
              <td rowspan="2">'.($key +1).'</td>
              <td rowspan="2">'.$val['idproducto'].'</td> 
              <td class="text_producto text-nowrap" rowspan="2"> <span class="name_producto_'.$val['idproducto'].'">'.$val['nombre_producto'].'</span> <br> <small><b>Clasf:</b> '.$val['categoria'].' </small></td>
              <td rowspan="2">'.$val['um_abreviacion'].'</td>
              <td > Entrada </td>          
              '.$html_dias.'          
              <td> <span class="entrada_total_'.$val['idproducto'].'">'.number_format($val['total_entrada'] , 2,',','.').'</span> </td>
              <td rowspan="2" class="'.($saldo < 0 ? 'text-danger' : '').'"><span class="saldo_total_'.$val['idproducto'].'">'.number_format($saldo, 2,',','.').'</span></td>
            </tr>';

            echo $codigoHTMLbodyProducto .' <tr><td class="'.$color_filas.'">Salida</td>  '.$html_dias_sum.'<td class="'.$color_filas.'"><span class="salida_total_'.$val['idproducto'].'">'.number_format($val['total_salida'], 2,',','.').'</span></td> </tr>'; 
            $html_dias ='';            

          }

          echo '</tbody>';

          //Codificar el resultado utilizando json
          // echo json_encode($rspta, true);
        break;  

        // ══════════════════════════════════════  A L M A C E N E S   G E N E R A L E S ══════════════════════════════════════
        case 'guardar_y_editar_almacen_general':

          if (empty($idalmacen_resumen_ag)) {
            $rspta = $almacen->crear_producto_ag($idproyecto_ag, $fecha_ingreso_ag, $dia_ingreso, $_POST["idproducto_ag"], $_POST["id_ar_ag"], $_POST["almacen_general_ag"], $_POST["cantidad_ag"] );
            echo json_encode($rspta, true);
          } else {
            $rspta = $almacen->editar_producto_ag();
            echo json_encode($rspta, true);
          }
          
        break;

        case 'otros_almacenes':
          $rspta = $almacen->otros_almacenes();
          echo json_encode( $rspta, true) ;
        break;

        case 'tabla-almacen-resumen':          

          $rspta=$almacen->tbla_principal_resumen($_GET["id_proyecto"]);
          
          //Vamos a declarar un array
          $data= Array(); $cont=1;

          if ($rspta['status'] == true) {

            foreach ($rspta['data'] as $key => $val) {               
          
              $data[]=array(
                "0"=>$cont++,
                "1"=>'<button class="btn btn-info btn-sm" onclick="modal_ver_almacen(null, '. $val['idalmacen_resumen'].')" data-toggle="tooltip" data-original-title="Ver Movimientos"><i class="fas fa-eye"></i></button>',
                "2"=> $val['idproducto_f'],
                "3"=>'<div > <span class="username"><p class="text-primary m-b-02rem" >'. $val['nombre_producto'] .'</p></span> </div>',
                "4"=> $val['um_abreviacion'],
                "5"=> $val['total_stok'],
                "6"=> $val['total_ingreso'],
                "7"=> $val['total_egreso']  . $toltip,              
                "8"=> 0,              
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

        // ══════════════════════════════════════ A L M A C E N   X   D I A ══════════════════════════════════════
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
        
        case 'tbla-ver-almacen':          

          $rspta=$almacen->tbla_ver_almacen($_GET["id_producto"], $_GET["fecha"] );
          
          //Vamos a declarar un array
          $data= Array(); $cont=1;

          if ($rspta['status'] == true) {

            foreach ($rspta['data'] as $key => $val) {               
          
              $data[]=array(
                "0"=>$cont++,
                "1"=>'<button class="btn btn-warning btn-sm" onclick="ver_editar_almacen_x_dia('.$val['idalmacen_detalle'].')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>'.
                  ' <button class="btn btn-danger btn-sm" onclick="eliminar_x_dia('.$val['idalmacen_detalle'].', \''.$val['cantidad'].'\', \''.encodeCadenaHtml($val['nombre_producto']).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i></button>',
                "2"=>'<div > <span class="username"><p class="text-primary m-b-02rem" >'. $val['tipo_mov'] .'</p></span> </div>',
                "3"=> $val['fecha'] ,
                "4"=> $val['name_day'] ,
                "5"=> $val['cantidad'],
                "6"=> '',              
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

        // ══════════════════════════════════════  S A L D O S  A N T E R I O R E S ══════════════════════════════════════
        case 'guardar_y_editar_saldo_anterior':

          if (empty($idalmacen_resumen)) {
            $rspta = $almacen->guardar_y_editar_saldo_anterior(  $_POST["idproyecto_sa"], $_POST["idproducto_sa"], $_POST["saldo_anterior"] );
            echo json_encode($rspta, true);
          } else {
            $rspta = $almacen->editar_almacen();
            echo json_encode($rspta, true);
          }
          
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

        case 'select2ProductosComprados': 
    
          $rspta = $almacen->select2_productos_comprados($_GET["idproyecto"]); $cont = 1; $data = "";
          
          if ($rspta['status'] == true) {  
            foreach ($rspta['data'] as $key => $value) {   
              $data .= '<option value="' . $value['idproducto'] . '" id_ar = "'.$value['idalmacen_resumen'].'" unidad_medida="' . $value['unidad_medida'] . '" >' . $value['nombre_producto'] .' - '. $value['categoria'] .' - Saldo: '. $value['saldo'] .'</option>';
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
