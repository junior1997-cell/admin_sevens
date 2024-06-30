<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Almacen
{
  //Implementamos nuestro variable global
	public $id_usr_sesion;

  //Implementamos nuestro constructor
	public function __construct($id_usr_sesion = 0)
	{
		$this->id_usr_sesion = $id_usr_sesion;
	}

  public function insertar_almacen($idproyecto_origen, $idproyecto_destino, $idalmacen_general, $tipo_mov, $fecha_ingreso, $idproducto, $tipo_prod, $marca, $cantidad, $descripcion){

    $ii = 0;
    while ($ii < count($idproducto)) {
      $sql_1 = "SELECT * FROM almacen_resumen WHERE idproducto = '$idproducto[$ii]' and idproyecto = '$idproyecto_origen';";
      $exist_producto = ejecutarConsultaSimpleFila($sql_1); if ( $exist_producto['status'] == false) {return $exist_producto; }
      $id_ar = "";
      if ( empty($exist_producto['data']) ) {
        
        if ($tipo_mov == 'IPC' || $tipo_mov == 'IEP' || $tipo_mov == 'IPG') {
          $sql_1 = "INSERT INTO almacen_resumen( idproyecto, idproducto, tipo, total_stok,  total_ingreso) 
          VALUES ($idproyecto_origen, $idproducto[$ii], '$tipo_prod[$ii]', ( total_stok + $cantidad[$ii] ), ( total_ingreso + $cantidad[$ii] ) );";
          $new_resumen = ejecutarConsulta_retornarID($sql_1, 'C'); if ( $new_resumen['status'] == false) {return $new_resumen; }
          $id_ar = $new_resumen['data'];
        } else if ($tipo_mov == 'EPO' || $tipo_mov == 'EPT' || $tipo_mov == 'EEP' || $tipo_mov == 'EPG') { 
          $sql_1 = "INSERT INTO almacen_resumen( idproyecto, idproducto, tipo, total_stok,  total_egreso) 
          VALUES ($idproyecto_origen, $idproducto[$ii], '$tipo_prod[$ii]', (total_stok - $cantidad[$ii] ), (total_egreso + $cantidad[$ii] ) );";
          $new_resumen = ejecutarConsulta_retornarID($sql_1, 'C'); if ( $new_resumen['status'] == false) {return $new_resumen; }
          $id_ar = $new_resumen['data'];
        }                 

        $sql_2 = "INSERT INTO almacen_detalle( idalmacen_resumen, idproyecto_destino, idalmacen_general, tipo_mov, fecha, marca, cantidad, descripcion)      
        VALUES ($id_ar, $idproyecto_destino[$ii], $idalmacen_general[$ii], '$tipo_mov', '$fecha_ingreso',  '$marca[$ii]', '$cantidad[$ii]', '$descripcion')";         
        $new_salida = ejecutarConsulta_retornarID($sql_2, 'C'); if ( $new_salida['status'] == false) {return $new_salida; }  

        if ($tipo_mov == 'EEP') { # INSERTAMOS EL INGRESO AL PROYECTO DESTINO
          $id_ar_iep = '';

          $sql_1 = "SELECT * FROM almacen_resumen WHERE idproducto = '$idproducto[$ii]' and idproyecto = '$idproyecto_destino[$ii]';";
          $exist_producto_iep = ejecutarConsultaSimpleFila($sql_1); if ( $exist_producto['status'] == false) {return $exist_producto; }

          if (empty($exist_producto_iep['data'])) {
            $sql_1 = "INSERT INTO almacen_resumen( idproyecto, idproducto, tipo, total_stok,  total_ingreso) 
            VALUES ($idproyecto_destino[$ii], $idproducto[$ii], '$tipo_prod[$ii]', ( total_stok + $cantidad[$ii] ), ( total_ingreso + $cantidad[$ii] ) );";
            $new_resumen = ejecutarConsulta_retornarID($sql_1, 'C'); if ( $new_resumen['status'] == false) {return $new_resumen; }
            $id_ar_iep = $new_resumen['data'];
          }else{
            $id_ar_iep = $exist_producto_iep['data']['idalmacen_resumen'];
            $sql_1= "UPDATE almacen_resumen SET idproyecto=$idproyecto_destino[$ii], idproducto=$idproducto[$ii], tipo='$tipo_prod[$ii]', total_stok= ( total_stok + $cantidad[$ii] ), 
            total_ingreso= ( total_ingreso + $cantidad[$ii] ) WHERE idalmacen_resumen='$id_ar_iep';";
            $update_resumen = ejecutarConsulta($sql_1, 'U'); if ( $update_resumen['status'] == false) {return $update_resumen; }
          }

          $sql_2 = "INSERT INTO almacen_detalle( idalmacen_resumen, idproyecto_destino, idalmacen_general, tipo_mov, fecha, marca, cantidad, descripcion)      
          VALUES ($id_ar_iep, $idproyecto_destino[$ii], NULL, 'IEP', '$fecha_ingreso', '$marca[$ii]', '$cantidad[$ii]', '$descripcion')";         
          $new_salida = ejecutarConsulta_retornarID($sql_2, 'C'); if ( $new_salida['status'] == false) {return $new_salida; }
        }

        if ($tipo_mov == 'EPG') { #INSERTAMOS EN EL ALMACEN GENERAL
          $id_ar_igp = '';

          $sql_1 = "SELECT agr.* FROM almacen_general_resumen AS agr WHERE agr.idalmacen_general = $idalmacen_general[$ii] AND agr.idproducto = $idproducto[$ii] ;";
          $exist_producto_igp = ejecutarConsultaSimpleFila($sql_1); if ( $exist_producto['status'] == false) {return $exist_producto; }

          if (empty($exist_producto_igp['data'])) {
            $sql_1 = "INSERT INTO almacen_general_resumen( idalmacen_general, idproducto, tipo, total_stok,  total_ingreso) 
            VALUES ($idalmacen_general[$ii], $idproducto[$ii], '$tipo_prod[$ii]', ( total_stok + $cantidad[$ii] ), ( total_ingreso + $cantidad[$ii] ) );";
            $new_resumen = ejecutarConsulta_retornarID($sql_1, 'C'); if ( $new_resumen['status'] == false) {return $new_resumen; }
            $id_ar_igp = $new_resumen['data'];
          }else{
            $id_ar_igp = $exist_producto_igp['data']['idalmacen_general_resumen'];
            $sql_1= "UPDATE almacen_general_resumen SET idalmacen_general=$idalmacen_general[$ii], idproducto=$idproducto[$ii], tipo='$tipo_prod[$ii]', total_stok= ( total_stok + $cantidad[$ii] ), 
            total_ingreso= ( total_ingreso + $cantidad[$ii] ) WHERE idalmacen_general_resumen='$id_ar_igp';";
            $update_resumen = ejecutarConsulta($sql_1, 'U'); if ( $update_resumen['status'] == false) {return $update_resumen; }
          }

          $sql_2 = "INSERT INTO almacen_general_detalle( idalmacen_general_resumen, idproyecto, tipo_mov, fecha, cantidad, descripcion)      
          VALUES ($id_ar_igp, $idproyecto_origen, 'IGP', '$fecha_ingreso',  '$cantidad[$ii]', '$descripcion')";         
          $new_ingreso = ejecutarConsulta_retornarID($sql_2, 'C'); if ( $new_ingreso['status'] == false) {return $new_ingreso; }
        }
        
      } else {

        $id_ar = $exist_producto['data']['idalmacen_resumen'];

        if ($tipo_mov == 'IP' || $tipo_mov == 'IEP' || $tipo_mov == 'IPG') {
          $sql_1= "UPDATE almacen_resumen SET idproyecto=$idproyecto_origen, idproducto=$idproducto[$ii], tipo='$tipo_prod[$ii]', total_stok= ( total_stok + $cantidad[$ii] ), 
          total_egreso= ( total_egreso + $cantidad[$ii] ) WHERE idalmacen_resumen='$id_ar';";
          $update_resumen = ejecutarConsulta($sql_1, 'U'); if ( $update_resumen['status'] == false) {return $update_resumen; }  
        } else if ($tipo_mov == 'EPO' || $tipo_mov == 'EPT' || $tipo_mov == 'EEP' || $tipo_mov == 'EPG') { 
          $sql_1= "UPDATE almacen_resumen SET idproyecto=$idproyecto_origen, idproducto=$idproducto[$ii], tipo='$tipo_prod[$ii]', total_stok= ( total_stok - $cantidad[$ii] ), 
          total_egreso= ( total_egreso + $cantidad[$ii] ) WHERE idalmacen_resumen='$id_ar';";
          $update_resumen = ejecutarConsulta($sql_1, 'U'); if ( $update_resumen['status'] == false) {return $update_resumen; }  
        }
        
        $sql_2 = "INSERT INTO almacen_detalle( idalmacen_resumen, idproyecto_destino, idalmacen_general,  tipo_mov, fecha, marca, cantidad, descripcion)      
        VALUES ($id_ar, $idproyecto_destino[$ii], $idalmacen_general[$ii], '$tipo_mov', '$fecha_ingreso', '$marca[$ii]', '$cantidad[$ii]', '$descripcion')"; 
        $new_salida = ejecutarConsulta_retornarID($sql_2, 'C'); if ( $new_salida['status'] == false) {return $new_salida; }  

        if ($tipo_mov == 'EEP') { # INSERTAMOS EL INGRESO AL PROYECTO DESTINO
          $id_ar_iep = '';

          $sql_1 = "SELECT * FROM almacen_resumen WHERE idproducto = '$idproducto[$ii]' and idproyecto = '$idproyecto_destino[$ii]';";
          $exist_producto_iep = ejecutarConsultaSimpleFila($sql_1); if ( $exist_producto['status'] == false) {return $exist_producto; }

          if (empty($exist_producto_iep['data'])) {
            $sql_1 = "INSERT INTO almacen_resumen( idproyecto, idproducto, tipo, total_stok,  total_ingreso) 
            VALUES ($idproyecto_destino[$ii], $idproducto[$ii], '$tipo_prod[$ii]', ( total_stok + $cantidad[$ii] ), ( total_ingreso + $cantidad[$ii] ) );";
            $new_resumen = ejecutarConsulta_retornarID($sql_1, 'C'); if ( $new_resumen['status'] == false) {return $new_resumen; }
            $id_ar_iep = $new_resumen['data'];
          }else{
            $id_ar_iep = $exist_producto_iep['data']['idalmacen_resumen'];
            $sql_1= "UPDATE almacen_resumen SET idproyecto=$idproyecto_destino[$ii], idproducto=$idproducto[$ii], tipo='$tipo_prod[$ii]', total_stok= ( total_stok + $cantidad[$ii] ), 
            total_ingreso= ( total_ingreso + $cantidad[$ii] ) WHERE idalmacen_resumen='$id_ar_iep';";
            $update_resumen = ejecutarConsulta($sql_1, 'U'); if ( $update_resumen['status'] == false) {return $update_resumen; }
          }

          $sql_2 = "INSERT INTO almacen_detalle( idalmacen_resumen, idproyecto_destino, idalmacen_general, tipo_mov,  fecha, marca, cantidad, descripcion)      
          VALUES ($id_ar_iep, $idproyecto_destino[$ii], NULL, 'IEP', '$fecha_ingreso', '$marca[$ii]', '$cantidad[$ii]', '$descripcion')";         
          $new_salida = ejecutarConsulta_retornarID($sql_2, 'C'); if ( $new_salida['status'] == false) {return $new_salida; }
        }

        if ($tipo_mov == 'EPG') { #INSERTAMOS EN EL ALMACEN GENERAL
          $id_ar_igp = '';

          $sql_1 = "SELECT agr.* FROM almacen_general_resumen AS agr WHERE agr.idalmacen_general = $idalmacen_general[$ii] AND agr.idproducto = $idproducto[$ii] ;";
          $exist_producto_igp = ejecutarConsultaSimpleFila($sql_1); if ( $exist_producto['status'] == false) {return $exist_producto; }

          if (empty($exist_producto_igp['data'])) {
            $sql_1 = "INSERT INTO almacen_general_resumen( idalmacen_general, idproducto, tipo, total_stok,  total_ingreso) 
            VALUES ($idalmacen_general[$ii], $idproducto[$ii], '$tipo_prod[$ii]', ( total_stok + $cantidad[$ii] ), ( total_ingreso + $cantidad[$ii] ) );";
            $new_resumen = ejecutarConsulta_retornarID($sql_1, 'C'); if ( $new_resumen['status'] == false) {return $new_resumen; }
            $id_ar_igp = $new_resumen['data'];
          }else{
            $id_ar_igp = $exist_producto_igp['data']['idalmacen_general_resumen'];
            $sql_1= "UPDATE almacen_general_resumen SET idalmacen_general=$idalmacen_general[$ii], idproducto=$idproducto[$ii], tipo='$tipo_prod[$ii]', total_stok= ( total_stok + $cantidad[$ii] ), 
            total_ingreso= ( total_ingreso + $cantidad[$ii] ) WHERE idalmacen_general_resumen='$id_ar_igp';";
            $update_resumen = ejecutarConsulta($sql_1, 'U'); if ( $update_resumen['status'] == false) {return $update_resumen; }
          }

          $sql_2 = "INSERT INTO almacen_general_detalle( idalmacen_general_resumen, idproyecto, tipo_mov, fecha, cantidad, descripcion)      
          VALUES ($id_ar_igp, $idproyecto_origen, 'IGP', '$fecha_ingreso',  '$cantidad[$ii]', '$descripcion')";
          $new_ingreso = ejecutarConsulta_retornarID($sql_2, 'C'); if ( $new_ingreso['status'] == false) {return $new_ingreso; }
        }
        
      }        

      $ii++;
    }
   
    return $retorno = ['status' => true, 'message' => 'todo oka ps', 'data' => ''];
  }

  public function editar_almacen(){

  }

  public function insertar_almacen_x_dia( ){ 

  }

  public function editar_almacen_x_dia($idalmacen_salida_xp, $idalmacen_resumen_xp, $idproyecto_xp, $producto_xp, $fecha_ingreso_xp, $dia_ingreso_xp, $marca_xp, $cantidad_xp){    
      
    $sql_1 = "SELECT * FROM almacen_resumen WHERE idproducto = '$producto_xp' and idproyecto = '$idproyecto_xp';";
    $exist_producto = ejecutarConsultaSimpleFila($sql_1); if ( $exist_producto['status'] == false) {return $exist_producto; }
    
    if ( empty($exist_producto['data']) ) {
      $sql_1 = "INSERT INTO almacen_resumen( idproyecto, idproducto,  user_created) 
      VALUES ('$idproyecto_xp','$producto_xp', '$this->id_usr_sesion');";
      $new_resumen = ejecutarConsulta_retornarID($sql_1); if ( $new_resumen['status'] == false) {return $new_resumen; }
      $id_r = $new_resumen['data'];
      //add registro en nuestra bitacora
      $sql_5 = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('almacen_resumen','$id_r','Crear registro','$this->id_usr_sesion')";
      $bitacora = ejecutarConsulta($sql_5); if ( $bitacora['status'] == false) {return $bitacora; }

      $sql_0 = "INSERT INTO almacen_salida( idalmacen_resumen, fecha_ingreso, dia_ingreso, cantidad, marca, user_created)
      VALUES ('$id_r', '$fecha_ingreso_xp', '$dia_ingreso_xp',  '$cantidad_xp', '$marca_xp', '$this->id_usr_sesion')";         
      $new_salida = ejecutarConsulta_retornarID($sql_0); if ( $new_salida['status'] == false) {return $new_salida; }  
      $id_s = $new_salida['data'];
      //add registro en nuestra bitacora
      $sql_5 = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('almacen_salida','$id_s','Crear registro','$this->id_usr_sesion')";
      $bitacora = ejecutarConsulta($sql_5); if ( $bitacora['status'] == false) {return $bitacora; }
    } else {
      $id_r = $exist_producto['data']['idalmacen_resumen'];
      $sql_0 = "UPDATE almacen_salida SET idalmacen_resumen='$id_r', fecha_ingreso='$fecha_ingreso_xp',
      dia_ingreso='$dia_ingreso_xp',cantidad='$cantidad_xp',marca='$marca_xp', user_updated='$this->id_usr_sesion' 
      WHERE idalmacen_salida='$idalmacen_salida_xp';";         
      $new_almancen = ejecutarConsulta($sql_0); if ( $new_almancen['status'] == false) {return $new_almancen; }  
      
      //add registro en nuestra bitacora
      $sql_5 = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('almacen_salida','$idalmacen_salida_xp','Actualizar registro','$this->id_usr_sesion')";
      $bitacora = ejecutarConsulta($sql_5); if ( $bitacora['status'] == false) {return $bitacora; }  
    }
    
    
    
    return $retorno = ['status' => true, 'message' => 'todo oka ps', 'data' => ''];
  }

  //Implementar un método para listar los registros
  public function tbla_principal($idproyecto, $fip, $ffp, $fpo) {

    $resumen_producto = []; $data_meses= []; $data_dias = [];  $data_num_dia = []; $data_nombre_dia = []; $data_nombre_abrev_dia = []; $data_sq = []; 

    $sql = "SELECT fecha, name_day, name_day_abrev, number_day, name_month, name_month_abrev, number_month, name_year FROM fechas_de_calendario WHERE fecha BETWEEN '$fip' AND '$ffp' GROUP BY number_month;";    
    $meses_rango = ejecutarConsultaArray($sql); if ($meses_rango['status'] == false) { return $meses_rango; }   

    $sql = "SELECT * FROM fechas_de_calendario WHERE fecha BETWEEN '$fip' AND '$ffp' ;";    
    $dias_rango = ejecutarConsultaArray($sql); if ($dias_rango['status'] == false) { return $dias_rango; }  

    // $meses_rango= extraer_meses_de_rango( $fip, $ffp ); return $meses_rango;
    // $dias_rango = extraer_dias_de_rango( $fip, $ffp);

    foreach ($meses_rango['data'] as $key1 => $val1) {
      $mes = $val1['number_month'];
      $sql = "SELECT * FROM fechas_de_calendario  WHERE number_month = '$mes' and fecha BETWEEN '$fip' AND '$ffp';";    
      $dia_mes = ejecutarConsultaArray($sql); if ($meses_rango['status'] == false) { return $meses_rango; }         
        
      $data_meses[] = ['name_month'  => $val1['name_month'], 'name_year'  => $val1['name_year'], 'dia'  => $dia_mes['data'],  'cantidad_dias'  => count($dia_mes['data']) , ]; #asigamos las fechas a un mes
     
    }

    // quincena o semanas
    $nombre_sq = ''; $cant_sq = '';
    if ($fpo == 'semanal') { $nombre_sq = 'Semana'; $cant_sq = 7 ;
    } else if ($fpo == 'quincenal') { $nombre_sq = 'Quincena'; $cant_sq = 14 ; }

    $fechaInicio = new DateTime($fip); $weekday_regular = $fechaInicio->format("w"); $dia_regular = 0;

    if ($weekday_regular == "0") { $dia_regular = $cant_sq;        # regulamos - domingo
    } else if ($weekday_regular == "1") { $dia_regular = 6; # regulamos - lunes  
    } else if ($weekday_regular == "2") { $dia_regular = 5; # regulamos - martes
    } else if ($weekday_regular == "3") { $dia_regular = 4; # regulamos - miercoles   
    } else if ($weekday_regular == "4") { $dia_regular = 3; # regulamos - jueves    
    } else if ($weekday_regular == "5") { $dia_regular = 2; # regulamos - viernes      
    } else if ($weekday_regular == "6") { $dia_regular = 1;}# regulamos - sabado 

    $cant_dias = count($dias_rango['data']); $sumando = $dia_regular; $estado = true; $count_sq = 1; $colspan = $dia_regular;
    //  ($cant_dias - ($sumando - $cant_sq) )
    while ($estado == true) {      
      if ( $cant_dias < $cant_sq ) {        #validamos si el rango de fechas en menor a: $cant_sq
        $data_sq[] = ['colspan'  =>  $dia_regular, 's'  => $sumando, 'c'  => $cant_dias, 'w'  => $weekday_regular, 'nombre_sq'  => $nombre_sq, 'num_sq'  => $count_sq, ];
        $estado = false;   
      } else if ( $sumando < $cant_dias ) { #semana regulada
        $data_sq[] = ['colspan'  =>  $colspan, 's'  => $sumando, 'c'  => $cant_dias, 'w'  => $weekday_regular, 'nombre_sq'  => $nombre_sq, 'num_sq'  => $count_sq, ];        
      } else {                              # validamos la ultima semana
        $data_sq[] = ['colspan'  => ($cant_dias - ($sumando - $cant_sq) ), 's'  => $sumando, 'c'  => $cant_dias, 'w'  =>$weekday_regular, 'nombre_sq'  => $nombre_sq, 'num_sq'  => $count_sq, ];
        $estado = false;
      }
      $count_sq += 1;  $colspan = $cant_sq; $sumando += $cant_sq;            
    }

    $sql_0 = "SELECT ar.*, prod.nombre as producto, ci.nombre as categoria_insumos, um.abreviacion as um_abreviacion, um.nombre_medida as um_nombre
    FROM almacen_resumen as ar
    INNER JOIN producto as prod ON prod.idproducto = ar.idproducto
    INNER JOIN categoria_insumos_af AS ci ON ci.idcategoria_insumos_af = prod.idcategoria_insumos_af
    INNER JOIN unidad_medida AS um ON um.idunidad_medida = prod.idunidad_medida
    WHERE ar.idproyecto =  '$idproyecto' ORDER BY prod.nombre ASC;";    
    $producto = ejecutarConsultaArray($sql_0); if ($producto['status'] == false) { return $producto; }   

    foreach ($producto['data'] as $key1 => $val1) { 

      $id_ar   = $val1['idalmacen_resumen'];
       
      $data_almacen = [];
      foreach ($dias_rango['data'] as $key2 => $val2) {
        $d_fecha = $val2['fecha'];
        // ENTRADA =======================================================
        $sql_1 = "SELECT SUM(cantidad) AS cant FROM almacen_detalle 
        WHERE estado = '1' AND estado_delete = '1' AND idalmacen_resumen = '$id_ar' AND fecha = '$d_fecha' AND tipo_mov IN ('IPC', 'IEP', 'IPG');";
        $entrada = ejecutarConsultaSimpleFila($sql_1); if ($entrada['status'] == false) { return $entrada; }      

        $sql_1_1 = "SELECT  GROUP_CONCAT(CASE WHEN ad.cantidad = FLOOR(ad.cantidad) THEN ROUND(ad.cantidad, 0) WHEN ad.cantidad = ROUND(ad.cantidad, 1) THEN ROUND(ad.cantidad, 1) ELSE TRIM(TRAILING '0' FROM ROUND(ad.cantidad, 2))
        END SEPARATOR ', ') as cant_group FROM almacen_detalle as ad 
        where estado = '1' AND estado_delete = '1' AND idalmacen_resumen = '$id_ar' AND fecha = '$d_fecha' AND tipo_mov IN ('IPC', 'IEP', 'IPG') GROUP BY ad.idalmacen_resumen;";
        $e_cant_group = ejecutarConsultaSimpleFila($sql_1_1); if ($e_cant_group['status'] == false) { return $e_cant_group; }    

        // SALIDA =======================================================
        $sql_2 = "SELECT SUM(cantidad) AS cant FROM almacen_detalle 
        WHERE estado = '1' AND estado_delete = '1' AND idalmacen_resumen = '$id_ar' AND fecha = '$d_fecha' AND tipo_mov IN ('EPO', 'EPT', 'EEP', 'EPG');";
        $salida = ejecutarConsultaSimpleFila($sql_2); if ($salida['status'] == false) { return $salida; }        

        $sql_1_1 = "SELECT  GROUP_CONCAT(CASE WHEN ad.cantidad = FLOOR(ad.cantidad) THEN ROUND(ad.cantidad, 0) WHEN ad.cantidad = ROUND(ad.cantidad, 1) THEN ROUND(ad.cantidad, 1) ELSE TRIM(TRAILING '0' FROM ROUND(ad.cantidad, 2))
        END SEPARATOR ', ') as cant_group FROM almacen_detalle as ad 
        where estado = '1' AND estado_delete = '1' AND idalmacen_resumen = '$id_ar' AND fecha = '$d_fecha' AND tipo_mov IN ('EPO', 'EPT', 'EEP', 'EPG') GROUP BY ad.idalmacen_resumen;";
        $s_cant_group = ejecutarConsultaSimpleFila($sql_1_1); if ($s_cant_group['status'] == false) { return $s_cant_group; } 

        $data_almacen[] =  [
          'fecha'         => $d_fecha, 
          'entrada_cant'  => empty($entrada['data'])      ? '-' : (empty($entrada['data']['cant'])            ? '-' : floatval($entrada['data']['cant']) ) ,
          'entrada_group' => empty($e_cant_group['data']) ? '-' : (empty($e_cant_group['data']['cant_group']) ? '-' : $e_cant_group['data']['cant_group'] ) ,
          'salida_cant'   => empty($salida['data'])       ? '-' : (empty($salida['data']['cant'])             ? '-' : floatval($salida['data']['cant']) ) ,
          'salida_group'  => empty($s_cant_group['data']) ? '-' : (empty($s_cant_group['data']['cant_group']) ? '-' : $s_cant_group['data']['cant_group'] ) ,      
        ];
      }  
      
      $resumen_producto[] = [
        'idalmacen_resumen' => $val1['idalmacen_resumen'],
        'idproyecto'        => $val1['idproyecto'],
        'idproducto'        => $val1['idproducto'],        
        'total_entrada'     => empty($val1['total_ingreso']) ? 0 :  floatval($val1['total_ingreso'] ) ,
        'total_salida'      => empty($val1['total_egreso']) ? 0 :  floatval($val1['total_egreso'] ) ,
        'stok'              => empty($val1['total_stok']) ? 0 :  floatval($val1['total_stok'] ) ,
        'nombre_producto'   => $val1['producto'],
        'um_nombre'         => $val1['um_nombre'],
        'um_abreviacion'    => $val1['um_abreviacion'],
        'categoria'         => $val1['categoria_insumos'],
        'almacen'           => $data_almacen,        
      ];
    }
    return $retorno = [
      'status'  => true, 
      'data'    => [
        'producto'      => $resumen_producto, 
        'fechas'        => $data_meses, 
        #'dias'          => $dias_rango, 
        'cant_dias'     => count($dias_rango['data']) ,   
        'num_dia_regular'=> $dia_regular ,   
        'data_sq'       => $data_sq
      ] , 
      'message' => 'todo bien'
    ];
  }  

  public function ver_almacen( $id_proyecto, $id_almacen_s, $id_producto ) {

    $sql_0 = "SELECT als.idalmacen_salida, als.idalmacen_resumen, ar.idproducto, als.fecha_ingreso, als.dia_ingreso, als.cantidad, als.marca
    FROM almacen_salida as als, almacen_resumen as ar
    WHERE als.idalmacen_resumen = ar.idalmacen_resumen AND als.estado ='1' AND als.estado_delete = '1' AND als.idalmacen_salida = '$id_almacen_s';";    
    $almacen = ejecutarConsultaSimpleFila($sql_0);

    $sql_0 = "SELECT  dc.marca,  pr.nombre AS nombre_producto
		FROM compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr
		WHERE cpp.idcompra_proyecto = dc.idcompra_proyecto AND dc.idproducto = pr.idproducto     
    AND cpp.idproyecto = '$id_proyecto' AND dc.idproducto = '$id_producto'
    AND cpp.estado = '1' AND cpp.estado_delete = '1'  GROUP BY dc.idproducto, dc.marca ORDER BY pr.nombre ASC;";    
    $marcas = ejecutarConsultaArray($sql_0);

    $data = [
      'idalmacen_salida'  => $almacen['data']['idalmacen_salida'],
      'idalmacen_resumen' => $almacen['data']['idalmacen_resumen'],
      'idproducto'        => $almacen['data']['idproducto'],
      'fecha_ingreso'     => $almacen['data']['fecha_ingreso'],
      'dia_ingreso'       => $almacen['data']['dia_ingreso'],
      'cantidad'          => $almacen['data']['cantidad'],
      'marca'             => $almacen['data']['marca'],      
      'marca_array'       => empty($marcas['data']) ? [0=>['marca'=>'SIN MARCA',  'nombre_producto'=>'']] : $marcas['data'] ,      
    ];

    return $retorno = [
      'status'  => true, 
      'data'    => $data , 
      'message' => 'todo bien'
    ];
          
  }

  public function tbla_ver_almacen_detalle(  $idalmacen_resumen, $fecha, $tipo_mov ) {

    $sql_fecha    = empty($fecha) || $fecha == 'null'  ? '' : "AND ad.fecha = '$fecha'";
    $sql_tipo_mov = empty($tipo_mov) || $tipo_mov == 'null'  ? '' :  ($tipo_mov == 'ENTRADA' ? "AND ad.tipo_mov IN ('IPC', 'IEP', 'IPG')" : ($tipo_mov == 'SALIDA' ? "AND ad.tipo_mov IN ('EPO', 'EPT', 'EEP', 'EPG')" : ""));

    $sql_0 = "SELECT idalmacen_detalle, ad.idalmacen_resumen, ad.idproyecto_destino, ad.idalmacen_general, ad.tipo_mov, ad.marca, ad.fecha, ad.name_day, ad.name_month, 
    ad.name_year,  ad.cantidad, CASE  WHEN ad.tipo_mov IN ('EPO', 'EPT', 'EEP', 'EPG') THEN ad.cantidad * -1 ELSE  ad.cantidad END AS cantidad_real,
    ad.stok_anterior, ad.stok_actual, ad.descripcion, ad.estado, ar.tipo, 
    CASE ad.tipo_mov
      WHEN 'EPO'  THEN 'EGRESO DE PROYECTO A OBRA'
      WHEN 'EPT'  THEN 'EGRESO DE PROYECTO A EPP'
      WHEN 'EEP'  THEN 'EGRESO ENTRE PROYECTOS'
      WHEN 'EPG'  THEN 'EGRESO DE PROYECTO A ALMACEN GENERAL'
      WHEN 'IPC'  THEN 'INGRESO A PROYECTO DE COMPRA'
      WHEN 'IEP'  THEN 'INGRESO ENTRE PROYECTOS'
      WHEN 'IPG'  THEN 'INGRESO A PROYECTO DE ALMACEN GENERAL'
    END AS tipo_movimiento ,
    CASE WHEN ad.tipo_mov IN ('EPO', 'EPT', 'EEP', 'EPG') THEN 'text-danger' ELSE 'text-primary' END AS class_tipo_mov ,
    CASE WHEN ad.tipo_mov IN ('EPO', 'EPT', 'EEP', 'EPG') THEN 'EGRESO' ELSE 'INGRESO' END AS tipo_mov_1 ,
    CASE  ad.tipo_mov
      WHEN 'EPO'  THEN 'DE PROYECTO A OBRA'
      WHEN 'EPT'  THEN 'DE PROYECTO A EPP'
      WHEN 'EEP'  THEN 'ENTRE PROYECTOS'
      WHEN 'EPG'  THEN 'DE PROYECTO A ALMACEN GENERAL'
      WHEN 'IPC'  THEN 'A PROYECTO DE COMPRA'
      WHEN 'IEP'  THEN 'ENTRE PROYECTOS'
      WHEN 'IPG'  THEN 'A PROYECTO DE ALMACEN GENERAL' 
    END AS tipo_mov_2 ,
    CASE ad.tipo_mov
      WHEN 'EPO'  THEN proy.nombre_codigo
      WHEN 'EPT'  THEN trab.nombres
      WHEN 'EEP'  THEN proy.nombre_codigo
      WHEN 'EPG'  THEN ag.nombre_almacen
      WHEN 'IPC'  THEN CONCAT('<b>', cpp.tipo_comprobante, '</b> ', cpp.serie_comprobante )
      WHEN 'IEP'  THEN proy.nombre_codigo
      WHEN 'IPG'  THEN ag.nombre_almacen
    END AS destino ,
    p.nombre as nombre_producto
    FROM almacen_detalle as ad 
    INNER JOIN almacen_resumen as ar ON ar.idalmacen_resumen = ad.idalmacen_resumen
    INNER JOIN producto as p ON p.idproducto = ar.idproducto
    LEFT JOIN proyecto as proy ON proy.idproyecto = ad.idproyecto_destino 
    LEFT JOIN almacen_general as ag ON ag.idalmacen_general = ad.idalmacen_general
    LEFT JOIN trabajador_por_proyecto as tpt ON tpt.idtrabajador_por_proyecto = ad.idtrabajador_por_proyecto
    LEFT JOIN trabajador as trab ON trab.idtrabajador = tpt.idtrabajador
    LEFT JOIN detalle_compra as dc ON dc.iddetalle_compra = ad.iddetalle_compra
    LEFT JOIN compra_por_proyecto as cpp ON cpp.idcompra_proyecto = dc.idcompra_proyecto
    WHERE ad.estado = '1' AND ad.estado_delete = '1' AND ad.idalmacen_resumen = '$idalmacen_resumen' $sql_tipo_mov  $sql_fecha 
    ORDER BY ad.fecha DESC;";    //return $sql_0;
    return ejecutarConsultaArray($sql_0);
          
  }

  public function marcas_x_producto($id_proyecto, $id_producto) {
    $array_marca = [];
    $sql_0 = "SELECT ac.marca, ar.idproducto
    FROM almacen_detalle as ac
    INNER JOIN almacen_resumen as ar ON ar.idalmacen_resumen = ac.idalmacen_resumen
    WHERE ar.idproyecto = '$id_proyecto' AND ar.idproducto = '$id_producto'
    GROUP BY ar.idproducto, ac.marca
    ORDER BY ac.marca ASC;";    
    $marca = ejecutarConsultaArray($sql_0); if ($marca['status'] == false) { return $marca; }  

    if ( empty($marca['data']) ) {
      $array_marca[] = [ 'idproducto' => $id_producto, 'marca' => 'SIN MARCA', 'selected' => 'selected' ];
    } else {
      foreach ($marca['data'] as $key => $val) { $array_marca[] = [ 'idproducto' => $id_producto, 'marca' => $val['marca'] ]; }
    }
    return $retorno = ['status'=> true, 'message' => 'Salió todo ok,', 'data' => $array_marca ];  
  }

  //Implementamos un método para desactivar almacen_salida
	public function desactivar_x_dia($idalmacen_salida)	{
		$sql="UPDATE almacen_salida SET estado='0' ,user_trash= '$this->id_usr_sesion' WHERE idalmacen_salida='$idalmacen_salida'";
		$desactivar= ejecutarConsulta($sql);	if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('almacen_salida','$idalmacen_salida','Almacen salida desactivado','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $desactivar;
	}

	//Implementamos un método para activar almacen_salida
	public function activar_x_dia($idalmacen_salida){
		$sql="UPDATE almacen_salida SET estado='1' WHERE idalmacen_salida='$idalmacen_salida'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar almacen_salida
	public function eliminar_x_dia($idalmacen_salida)	{
		$sql="UPDATE almacen_salida SET estado_delete='0',user_delete= '$this->id_usr_sesion' WHERE idalmacen_salida='$idalmacen_salida'";
		$eliminar =  ejecutarConsulta($sql); if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('almacen_salida','$idalmacen_salida','Almacen salida Eliminado','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
		
		return $eliminar;
	}

  // ══════════════════════════════════════  A L M A C E N E S   G E N E R A L E S ══════════════════════════════════════
  public function crear_producto_ag($idproyecto_ag, $fecha_ingreso_ag, $dia_ingreso, $idproducto_ag, $id_ar_ag, $almacen_general_ag, $cantidad_ag){

    $ii2 = 0; $info_repetida = '';
    while ($ii2 < count($idproducto_ag)) {
      // buscamos en el "almacen_producto_guardado"
      $sql_b = "SELECT al.nombre_almacen, p.nombre as producto, apg.fecha_envio, apg.cantidad, apg.estado, apg.estado_delete
      FROM almacen_producto_guardado as apg, almacen_resumen ar, almacen_general al, producto as p
      WHERE apg.idalmacen_resumen = ar.idalmacen_resumen AND apg.idalmacen_general = al.idalmacen_general AND ar.idproducto = p.idproducto 
      AND ar.idproyecto = '$idproyecto_ag' AND apg.idalmacen_resumen = '$id_ar_ag[$ii2]' AND al.idalmacen_general = '$almacen_general_ag[$ii2]'";
      $buscando = ejecutarConsultaArray($sql_b); if ($buscando['status'] == false) { return $buscando; }        
    
      foreach ($buscando['data'] as $key => $val1) {
        $info_repetida .= '<li class="text-left font-size-13px">
        <span class="font-size-18px text-danger"><b >Almacen</b> '.$val1['nombre_almacen'].'</span><br>
        <b>Producto: </b>'.$val1['producto'].'<br>
        <b>Fecha: </b>'.$val1['fecha_envio'].'<br>
        <b>Cantidad: </b>'.$val1['cantidad'].'<br>
        <b>Papelera: </b>'.( $val1['estado']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO') .' <b>|</b>
        <b>Eliminado: </b>'. ($val1['estado_delete']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO').'<br>
        <hr class="m-t-2px m-b-2px">
        </li>';
      } 
      $ii2++;
    }

    if ( !empty( $info_repetida ) ) { return $sw = array( 'status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ol>'.$info_repetida.'</ol>', 'id_tabla' => '' ); }

    $ii = 0;
    while ($ii < count($idproducto_ag)) {      

      if ( empty($id_ar_ag[$ii]) || $id_ar_ag[$ii] == 0 || $id_ar_ag[$ii] == '0' ) {
        $sql_1 = "INSERT INTO almacen_resumen( idproyecto, idproducto, user_created) 
        VALUES ('$idproyecto_ag','$idproducto_ag[$ii]', '$this->id_usr_sesion');";
        $new_resumen = ejecutarConsulta_retornarID($sql_1); if ( $new_resumen['status'] == false) {return $new_resumen; }
        $id_r = $new_resumen['data'];
        //add registro en nuestra bitacora
        $sql_5 = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('almacen_resumen','$id_r','Crear registro','$this->id_usr_sesion')";
        $bitacora = ejecutarConsulta($sql_5); if ( $bitacora['status'] == false) {return $bitacora; }
        
        $sql_0 = "INSERT INTO almacen_producto_guardado( idalmacen_general, idalmacen_resumen, fecha_envio, dia_envio, cantidad) 
        VALUES ('$almacen_general_ag[$ii]','$id_r','$fecha_ingreso_ag','$dia_ingreso', '$cantidad_ag[$ii]')";
        $creando = ejecutarConsulta_retornarID($sql_0); if ($creando['status'] == false) { return $creando; }  
        $id = $creando['data'];
        //add registro en nuestra bitacora
        $sql_5 = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('almacen_producto_guardado','$id','Crear registro','$this->id_usr_sesion')";
        $bitacora = ejecutarConsulta($sql_5); if ( $bitacora['status'] == false) {return $bitacora; }

      } else {
        $sql_0 = "INSERT INTO almacen_producto_guardado( idalmacen_general, idalmacen_resumen, fecha_envio, dia_envio, cantidad) 
        VALUES ('$almacen_general_ag[$ii]','$id_ar_ag[$ii]','$fecha_ingreso_ag','$dia_ingreso', '$cantidad_ag[$ii]')";
        $creando = ejecutarConsulta_retornarID($sql_0); if ($creando['status'] == false) { return $creando; }  
        $id = $creando['data'];
        //add registro en nuestra bitacora
        $sql_5 = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('almacen_producto_guardado','$id','Crear registro','$this->id_usr_sesion')";
        $bitacora = ejecutarConsulta($sql_5); if ( $bitacora['status'] == false) {return $bitacora; }
      }    
      $ii++;
    }   
    return $retorno = ['status' => true, 'message' => 'todo oka ps', 'data' => ''];
  }

  public function editar_producto_ag(){

  }

  //Implementar un método para listar los registros
  public function tbla_principal_resumen($idproyecto) {    
        
    $sql_0 = "SELECT ar.*, LPAD(p.idproducto, 5, '0') AS idproducto_f , p.nombre as nombre_producto, um.nombre_medida, um.abreviacion as um_abreviacion, ciaf.nombre as categoria
    FROM almacen_resumen AS ar
    INNER JOIN producto AS p ON p.idproducto = ar.idproducto
    INNER JOIN unidad_medida as um ON um.idunidad_medida = p.idunidad_medida
    INNER JOIN categoria_insumos_af as ciaf ON ciaf.idcategoria_insumos_af = p.idcategoria_insumos_af 
    WHERE ar.idproyecto = '$idproyecto' AND ar.estado = '1' AND ar.estado_delete = '1' ORDER BY left(p.nombre, 2) ASC, ar.total_stok DESC;";    
   return ejecutarConsultaArray($sql_0); 

  } 

  //Implementar un método para listar los registros
  public function tbla_principal_resumen_stock($idproyecto, $unidad_medida, $categoria, $es_epp) {    
    $filtro_unidad_medida = ""; $filtro_categoria = ""; $filtro_es_epp = ""; 

    if (empty($unidad_medida) ) { } else { $filtro_unidad_medida  = "AND p.idunidad_medida = '$unidad_medida'"; }
    if ( empty($categoria) )    { } else { $filtro_categoria      = "AND p.idcategoria_insumos_af = '$categoria'"; } 
    if ( empty($es_epp) )       { } else { $filtro_es_epp         = "AND ar.tipo = '$es_epp'"; } 

    $sql_0 = "SELECT ar.*, LPAD(p.idproducto, 5, '0') AS idproducto_f , p.nombre as nombre_producto, um.nombre_medida, um.abreviacion as um_abreviacion, ciaf.nombre as categoria
    FROM almacen_resumen AS ar
    INNER JOIN producto AS p ON p.idproducto = ar.idproducto
    INNER JOIN unidad_medida as um ON um.idunidad_medida = p.idunidad_medida
    INNER JOIN categoria_insumos_af as ciaf ON ciaf.idcategoria_insumos_af = p.idcategoria_insumos_af 
    WHERE ar.idproyecto = '$idproyecto' AND ar.total_stok > 0 AND ar.estado = '1' AND ar.estado_delete = '1' $filtro_unidad_medida $filtro_categoria $filtro_es_epp
    ORDER BY ar.total_stok DESC;";    
    return ejecutarConsultaArray($sql_0); 

  } 

  //Implementar un método para listar los registros
  public function otros_almacenes() {            
    $sql_0 = "SELECT idalmacen_general, nombre_almacen, descripcion FROM almacen_general WHERE estado = '1' AND estado_delete = '1' ORDER BY nombre_almacen ASC;";    
    return ejecutarConsultaArray($sql_0);     
  } 

  // ══════════════════════════════════════  S A L D O S  A N T E R I O R E S ══════════════════════════════════════
  public function guardar_y_editar_saldo_anterior( $idproyecto_sa, $idproducto_sa, $saldo_anterior ) {

    $ii = 0;
    while ($ii < count($idproducto_sa)) {
      $sql_1 = "SELECT * FROM almacen_resumen WHERE idproducto = '$idproducto_sa[$ii]' and idproyecto = '$idproyecto_sa[$ii]';";
      $exist_producto = ejecutarConsultaSimpleFila($sql_1); if ( $exist_producto['status'] == false) {return $exist_producto; }

      if ( empty($exist_producto['data']) ) {
        $sql_1 = "INSERT INTO almacen_resumen( idproyecto, idproducto, saldo_anterior, user_created) 
        VALUES ('$idproyecto_sa[$ii]','$idproducto_sa[$ii]', '$saldo_anterior[$ii]', '$this->id_usr_sesion');";
        $new_resumen = ejecutarConsulta_retornarID($sql_1); if ( $new_resumen['status'] == false) {return $new_resumen; }
        $id_r = $new_resumen['data'];
        //add registro en nuestra bitacora
        $sql_5 = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('almacen_resumen','$id_r','Crear registro','$this->id_usr_sesion')";
        $bitacora = ejecutarConsulta($sql_5); if ( $bitacora['status'] == false) {return $bitacora; }
       
      } else {
        $id_r = $exist_producto['data']['idalmacen_resumen'];
        $sql_0 = "UPDATE almacen_resumen SET idproyecto='$idproyecto_sa[$ii]', idproducto='$idproducto_sa[$ii]', saldo_anterior='$saldo_anterior[$ii]' 
        WHERE idalmacen_resumen='$id_r'";         
        $new_salida = ejecutarConsulta_retornarID($sql_0); if ( $new_salida['status'] == false) {return $new_salida; }  
        $id_s = $new_salida['data'];
        //add registro en nuestra bitacora
        $sql_5 = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('almacen_salida','$id_s','Crear registro','$this->id_usr_sesion')";
        $bitacora = ejecutarConsulta($sql_5); if ( $bitacora['status'] == false) {return $bitacora; }
      }        

      $ii++;
    }
   
    return $retorno = ['status' => true, 'message' => 'todo oka ps', 'data' => ''];
          
  }


  public function tbla_saldos_anteriores($id_proyecto, $id_producto) {
    $data = [];
    $sql_0 = "SELECT ar.idalmacen_resumen, ar.idproyecto, ar.idproducto, ar.saldo_anterior,  p.nombre as producto, 
    um.nombre_medida as unidad_medida, um.abreviacion as abreviacion_um, ciaf.nombre as categoria, pro.nombre_codigo as proyecto
    FROM almacen_resumen as ar, proyecto as pro, producto as p, unidad_medida as um, categoria_insumos_af as ciaf
    WHERE ar.idproyecto = pro.idproyecto and ar.idproducto = p.idproducto AND p.idunidad_medida = um.idunidad_medida AND p.idcategoria_insumos_af = ciaf.idcategoria_insumos_af
    AND ar.estado = '1' AND ar.estado_delete = '1' AND ar.idproducto = '$id_producto' ORDER BY p.nombre ASC;";    
    $saldos = ejecutarConsultaArray($sql_0); if ($saldos['status'] == false) { return $saldos; } 
    
    foreach ($saldos['data'] as $key => $value) {
      $id_r = $value['idalmacen_resumen'];
      $id_pro = $value['idproyecto'];
      $sql_1 = "SELECT SUM( cantidad ) as cantidad_s
      FROM almacen_salida WHERE estado = '1' AND estado_delete = '1' AND idalmacen_resumen = '$id_r' ;";
      $salida = ejecutarConsultaSimpleFila($sql_1); if ($salida['status'] == false) { return $salida; }

      $sql_2 = "SELECT SUM(dc.cantidad) as cantidad_e FROM compra_por_proyecto as cpp, detalle_compra as dc 
      WHERE cpp.idcompra_proyecto = dc.idcompra_proyecto AND cpp.estado = '1' AND cpp.estado_delete = '1' AND cpp.idproyecto = '$id_pro' AND dc.idproducto = '$id_producto';";
      $entrada = ejecutarConsultaSimpleFila($sql_2); if ($entrada['status'] == false) { return $entrada; }

      $data[] = [
        'idalmacen_resumen' => $value['idalmacen_resumen'], 
        'idproyecto'        => $value['idproyecto'], 
        'idproducto'        => $value['idproducto'], 
        'saldo_anterior'    => $value['saldo_anterior'],         
        'producto'          => $value['producto'], 
        'unidad_medida'     => $value['unidad_medida'], 
        'abreviacion_um'    => $value['abreviacion_um'], 
        'categoria'         => $value['categoria'], 
        'proyecto'          => $value['proyecto'], 
        'entrada'           => empty($entrada['data']) ? 0 : (empty($entrada['data']['cantidad_e']) ? 0 : floatval($entrada['data']['cantidad_e'])) ,
        'salida'            => empty($salida['data']) ? 0 : (empty($salida['data']['cantidad_s']) ? 0 : floatval($salida['data']['cantidad_s'])) ,        
      ];
    }

    return $retorno = [
      'status'  => true, 
      'data'    =>  $data   , 
      'message' => 'todo bien'
    ];
  }

  // ══════════════════════════════════════ T R A N F E R E N C I A   E N T R E   P R O Y E C T O ══════════════════════════════════════ 
  public function guardar_y_editar_tep( $proyecto_tep, $fecha_tep, $idproducto_tep, $marca_tep, $cantidad_tep ){
    $sql_0 = "SELECT p.idproyecto, p.nombre_codigo, 
    CASE p.estado WHEN 0 THEN 'Terminado' WHEN 1 THEN 'En proceso' ELSE 'No empezado' END as estado
    FROM proyecto as p ORDER BY p.idproyecto DESC;";    
    return ejecutarConsultaArray($sql_0);
  }

  // ══════════════════════════════════════ SELECT 2 ══════════════════════════════════════ 

  public function select2_productos_todos($idproyecto){
    $sql_0 = "SELECT pr.idproducto, pr.nombre AS nombre_producto, um.nombre_medida, um.abreviacion,  pr.modelo, ci.nombre as clasificacion    
		FROM  producto AS pr, categoria_insumos_af AS ci, unidad_medida AS um 
		WHERE pr.idcategoria_insumos_af = ci.idcategoria_insumos_af AND um.idunidad_medida  = pr.idunidad_medida 
    AND pr.estado = '1' AND pr.estado_delete = '1' ORDER BY pr.nombre ASC;";    
    return ejecutarConsultaArray($sql_0);
  }

  public function select2_productos($idproyecto){
    $resumen_producto = [];
    $sql_0 = "SELECT ar.*, p.nombre as nombre_producto, um.nombre_medida, um.abreviacion as um_abreviacion, ciaf.nombre as categoria
    FROM almacen_resumen AS ar
    INNER JOIN producto AS p ON p.idproducto = ar.idproducto
    INNER JOIN unidad_medida as um ON um.idunidad_medida = p.idunidad_medida
    INNER JOIN categoria_insumos_af as ciaf ON ciaf.idcategoria_insumos_af = p.idcategoria_insumos_af 
    WHERE ar.tipo <> 'EPP' AND ar.idproyecto = '$idproyecto' AND ar.estado = '1' AND ar.estado_delete = '1' ORDER BY p.nombre  ASC;";    
    $producto = ejecutarConsultaArray($sql_0);
    
    foreach ($producto['data'] as $key => $val1) {      

      $resumen_producto[] = [
        'idalmacen_resumen' => $val1['idalmacen_resumen'],
        'idproducto'        => $val1['idproducto'],    
        'tipo'              => $val1['tipo'],    
        'nombre_producto'   => $val1['nombre_producto'],
        'unidad_medida'     => $val1['nombre_medida'],
        'abreviacion_um'    => $val1['um_abreviacion'],
        'categoria'         => $val1['categoria'],
        'salida_sum'        => (empty($val1['total_egreso'])   ? 0 : floatval($val1['total_egreso']) ) ,                   
        'entrada_sum'       => (empty($val1['total_ingreso']) ? 0 : floatval($val1['total_ingreso']) ) ,       
        'saldo'             => (empty($val1['total_stok'])    ? 0 : floatval($val1['total_stok']) ) ,
      ];
    }

    return $retorno = [
      'status'  => true, 
      'data'    => $resumen_producto , 
      'message' => 'todo bien'
    ]; 
  }

  public function select2ProductosMasEPP($idproyecto){
    $resumen_producto = [];
    $sql_0 = "SELECT ar.*, p.nombre as nombre_producto, um.nombre_medida, um.abreviacion as um_abreviacion, ciaf.nombre as categoria
    FROM almacen_resumen AS ar
    INNER JOIN producto AS p ON p.idproducto = ar.idproducto
    INNER JOIN unidad_medida as um ON um.idunidad_medida = p.idunidad_medida
    INNER JOIN categoria_insumos_af as ciaf ON ciaf.idcategoria_insumos_af = p.idcategoria_insumos_af 
    WHERE ar.idproyecto = '$idproyecto' AND ar.estado = '1' AND ar.estado_delete = '1' ORDER BY p.nombre  ASC;";    
    $producto = ejecutarConsultaArray($sql_0);
    
    foreach ($producto['data'] as $key => $val1) {      

      $resumen_producto[] = [
        'idalmacen_resumen' => $val1['idalmacen_resumen'],
        'idproducto'        => $val1['idproducto'],        
        'tipo'              => $val1['tipo'],
        'nombre_producto'   => $val1['nombre_producto'],
        'unidad_medida'     => $val1['nombre_medida'],
        'abreviacion_um'    => $val1['um_abreviacion'],
        'categoria'         => $val1['categoria'],
        'salida_sum'        => (empty($val1['total_egreso'])   ? 0 : floatval($val1['total_egreso']) ) ,                   
        'entrada_sum'       => (empty($val1['total_ingreso']) ? 0 : floatval($val1['total_ingreso']) ) ,       
        'saldo'             => (empty($val1['total_stok'])    ? 0 : floatval($val1['total_stok']) ) ,
      ];
    }

    return $retorno = [
      'status'  => true, 
      'data'    => $resumen_producto , 
      'message' => 'todo bien'
    ]; 
  }

  public function select2_proyecto($idproyecto){
    $sql_0 = "SELECT p.idproyecto, p.nombre_codigo, 
    CASE p.estado WHEN 0 THEN 'Terminado' WHEN 1 THEN 'En proceso' ELSE 'No empezado' END as estado
    FROM proyecto as p WHERE p.idproyecto <> $idproyecto ORDER BY p.idproyecto DESC;";    
    return ejecutarConsultaArray($sql_0);
  }

  public function select2_unidad_medida(){
    $sql_0 = "SELECT * FROM unidad_medida WHERE estado = '1' AND estado_delete = '1' ORDER BY nombre_medida DESC;";    
    return ejecutarConsultaArray($sql_0);
  }

  public function select2_categoria(){
    $sql_0 = "SELECT * FROM categoria_insumos_af WHERE estado = '1' AND estado_delete = '1' ORDER BY nombre DESC;";    
    return ejecutarConsultaArray($sql_0);
  }

}

?>
