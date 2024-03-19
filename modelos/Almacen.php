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

  public function insertar_almacen($idproyecto, $fecha_ingreso, $dia_ingreso, $idproducto, $marca, $cantidad){

    $ii = 0;
    while ($ii < count($idproducto)) {
      $sql_1 = "SELECT * FROM almacen_resumen WHERE idproducto = '$idproducto[$ii]' and idproyecto = '$idproyecto';";
      $exist_producto = ejecutarConsultaSimpleFila($sql_1); if ( $exist_producto['status'] == false) {return $exist_producto; }

      if ( empty($exist_producto['data']) ) {
        $sql_1 = "INSERT INTO almacen_resumen( idproyecto, idproducto, user_created) 
        VALUES ('$idproyecto','$idproducto[$ii]', '$this->id_usr_sesion');";
        $new_resumen = ejecutarConsulta_retornarID($sql_1); if ( $new_resumen['status'] == false) {return $new_resumen; }
        $id_r = $new_resumen['data'];
        //add registro en nuestra bitacora
        $sql_5 = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('almacen_resumen','$id_r','Crear registro','$this->id_usr_sesion')";
        $bitacora = ejecutarConsulta($sql_5); if ( $bitacora['status'] == false) {return $bitacora; }

        $sql_0 = "INSERT INTO almacen_salida( idalmacen_resumen, fecha_ingreso, dia_ingreso, cantidad, marca, user_created)
        VALUES ('$id_r', '$fecha_ingreso', '$dia_ingreso',  '$cantidad[$ii]', '$marca[$ii]', '$this->id_usr_sesion')";         
        $new_salida = ejecutarConsulta_retornarID($sql_0); if ( $new_salida['status'] == false) {return $new_salida; }  
        $id_s = $new_salida['data'];
        //add registro en nuestra bitacora
        $sql_5 = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('almacen_salida','$id_s','Crear registro','$this->id_usr_sesion')";
        $bitacora = ejecutarConsulta($sql_5); if ( $bitacora['status'] == false) {return $bitacora; }
      } else {
        $id_r = $exist_producto['data']['idalmacen_resumen'];
        $sql_0 = "INSERT INTO almacen_salida( idalmacen_resumen, fecha_ingreso, dia_ingreso, cantidad, marca, user_created)
        VALUES ('$id_r', '$fecha_ingreso', '$dia_ingreso',  '$cantidad[$ii]', '$marca[$ii]', '$this->id_usr_sesion')";         
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
    
    $meses_rango= extraer_meses_de_rango( $fip, $ffp );
    $dias_rango = extraer_dias_de_rango( $fip, $ffp);

    foreach ($meses_rango as $key1 => $val1) {
      foreach ($dias_rango as $key2 => $val2) {
        if ( date('m',strtotime($val1)) == date('m',strtotime($val2)) ) {
          $data_dias[] = ['num_dia'  =>  date('d',strtotime($val2)), 'nombre_abrev_dia'  => nombre_dia_semana_v1($val2)];
          // array_push($data_dias, $val2);
          // array_push($data_num_dia,  );
          // array_push($data_nombre_dia,  nombre_dia_semana($val2) );
          // array_push($data_nombre_abrev_dia,    );
        }
      }    
      $data_meses[] = ['mes'  => $val1, 'dia'  => $data_dias,  'cantidad_dias'  => count($data_dias) , ]; #asigamos las fechas a un mes
      $data_dias    = []; #limpiamos para volver a llenar las fechas
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

    $cant_dias = count($dias_rango); $sumando = $dia_regular; $estado = true; $count_sq = 1; $colspan = $dia_regular;

    while ($estado == true) {        
      if ( $sumando < $cant_dias ) {   
        $data_sq[] = ['colspan'  => $colspan, 'nombre_sq'  => $nombre_sq, 'num_sq'  => $count_sq, ];        
      } else {   
        $data_sq[] = ['colspan'  => ($cant_dias - ($sumando - $cant_sq) ), 'nombre_sq'  => $nombre_sq, 'num_sq'  => $count_sq, ];
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
      foreach ($dias_rango as $key2 => $val2) {
        
        // ENTRADA =======================================================
        $sql_1 = "SELECT SUM(cantidad) AS cant FROM almacen_detalle 
        WHERE estado = '1' AND estado_delete = '1' AND idalmacen_resumen = '$id_ar' AND fecha = '$val2' AND tipo_mov IN ('IP', 'IEP', 'IPG');";
        $entrada = ejecutarConsultaSimpleFila($sql_1); if ($entrada['status'] == false) { return $entrada; }      

        $sql_1_1 = "SELECT  GROUP_CONCAT(CAST(ad.cantidad AS UNSIGNED) SEPARATOR ', ') as cant_group FROM almacen_detalle as ad 
        where estado = '1' AND estado_delete = '1' AND idalmacen_resumen = '$id_ar' AND fecha = '$val2' AND tipo_mov IN ('IP', 'IEP', 'IPG') GROUP BY ad.idalmacen_resumen;";
        $e_cant_group = ejecutarConsultaSimpleFila($sql_1_1); if ($e_cant_group['status'] == false) { return $e_cant_group; }    

        // SALIDA =======================================================
        $sql_2 = "SELECT SUM(cantidad) AS cant FROM almacen_detalle 
        WHERE estado = '1' AND estado_delete = '1' AND idalmacen_resumen = '$id_ar' AND fecha = '$val2' AND tipo_mov IN ('EP', 'EEP', 'EPG');";
        $salida = ejecutarConsultaSimpleFila($sql_2); if ($salida['status'] == false) { return $salida; }        

        $sql_1_1 = "SELECT  GROUP_CONCAT(CAST(ad.cantidad AS UNSIGNED) SEPARATOR ', ') as cant_group FROM almacen_detalle as ad 
        where estado = '1' AND estado_delete = '1' AND idalmacen_resumen = '$id_ar' AND fecha = '$val2' AND tipo_mov IN ('EP', 'EEP', 'EPG') GROUP BY ad.idalmacen_resumen;";
        $s_cant_group = ejecutarConsultaSimpleFila($sql_1_1); if ($s_cant_group['status'] == false) { return $s_cant_group; } 

        $data_almacen[] =  [
          'fecha'         => $val2, 
          'entrada_cant'  => empty($entrada['data'])      ? '-' : (empty($entrada['data']['cant'])            ? '-' : floatval($entrada['data']['cant']) ) ,
          'entrada_group' => empty($e_cant_group['data']) ? '-' : (empty($e_cant_group['data']['cant_group']) ? '-' : $e_cant_group['data']['cant_group'] ) ,
          'salida_cant'   => empty($salida['data'])       ? '-' : (empty($salida['data']['cant'])             ? '-' : floatval($salida['data']['cant']) ) ,
          'salida_group'  => empty($s_cant_group['data']) ? '-' : (empty($s_cant_group['data']['cant_group']) ? '-' : $s_cant_group['data']['cant_group'] ) ,      
        ];
      }  
      
      $resumen_producto[] = [
        'idalmacen_resumen' => empty($sal_ant['data']) ? '' : (empty($sal_ant['data']['idalmacen_resumen']) ? '' : $sal_ant['data']['idalmacen_resumen']),
        'idproyecto'        => $val1['idproyecto'],
        'idproducto'        => $val1['idproducto'],        
        'total_entrada'     => empty($val1['total_ingreso']) ? 0 :  floatval($val1['total_ingreso'] ) ,
        'total_salida'      => empty($val1['total_egreso']) ? 0 :  floatval($val1['total_egreso'] ) ,
        'stok'             => empty($val1['total_stok']) ? 0 :  floatval($val1['total_stok'] ) ,
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
        'cant_dias'     => count($dias_rango) ,   
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

  public function tbla_ver_almacen($id_proyecto, $fecha, $id_producto) {
    $sql_fecha= empty($fecha) || $fecha == 'null'  ? '' : "AND als.fecha_ingreso = '$fecha'";
    $sql_0 = "SELECT als.idalmacen_salida, als.idalmacen_resumen, als.fecha_ingreso, als.dia_ingreso, als.cantidad, als.marca, p.idproducto, p.nombre as producto 
    FROM almacen_salida as als, almacen_resumen as ar, producto as p
    WHERE als.idalmacen_resumen = ar.idalmacen_resumen and ar.idproducto = p.idproducto AND  als.estado ='1' AND als.estado_delete = '1' 
    $sql_fecha AND ar.idproducto = '$id_producto' AND ar.idproyecto = '$id_proyecto' ORDER BY p.nombre ASC;";    
    return ejecutarConsultaArray($sql_0);
          
  }

  public function marcas_x_producto($id_proyecto, $id_producto) {

    $sql_0 = "SELECT  dc.marca,  pr.nombre AS nombre_producto
		FROM compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr
		WHERE cpp.idcompra_proyecto = dc.idcompra_proyecto AND dc.idproducto = pr.idproducto     
    AND cpp.idproyecto = '$id_proyecto' AND dc.idproducto = '$id_producto'
    AND cpp.estado = '1' AND cpp.estado_delete = '1'  GROUP BY dc.idproducto, dc.marca ORDER BY pr.nombre ASC;";    
    return ejecutarConsultaArray($sql_0);
          
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
  public function tbla_principal_resumen($idproyecto, $fip, $ffp, $fpo) {

    $resumen_producto = []; $data_meses= []; $data_dias = [];  $data_num_dia = []; $data_nombre_dia = []; $data_nombre_abrev_dia = []; $data_sq = [];    
        
    $sql_0 = "SELECT cpp.idcompra_proyecto, cpp.idproyecto, dc.iddetalle_compra, dc.idproducto, sum(dc.cantidad) as cantidad, dc.marca,
    um.nombre_medida, um.nombre_medida, um.abreviacion, pr.nombre AS nombre_producto, pr.modelo, ci.nombre as clasificacion    
    FROM proyecto AS p, compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr, categoria_insumos_af AS ci, unidad_medida AS um 
    WHERE p.idproyecto = cpp.idproyecto AND cpp.idcompra_proyecto = dc.idcompra_proyecto AND dc.idproducto = pr.idproducto
    AND um.idunidad_medida  = pr.idunidad_medida AND pr.idcategoria_insumos_af = ci.idcategoria_insumos_af
    AND cpp.idproyecto = '$idproyecto'
    AND cpp.estado = '1' AND cpp.estado_delete = '1' GROUP BY dc.idproducto ORDER BY pr.nombre ASC;";    
    $producto = ejecutarConsultaArray($sql_0); if ($producto['status'] == false) { return $producto; }   

    foreach ($producto['data'] as $key1 => $val1) { 

      $id_p   = $val1['idproducto'];

      $sql_1 = "SELECT idalmacen_resumen, idproyecto, idproducto, saldo_anterior 
      FROM almacen_resumen WHERE estado = '1' AND estado_delete = '1' AND idproducto = '$id_p' AND idproyecto = '$idproyecto';";   
      $sal_ant = ejecutarConsultaSimpleFila($sql_1); if ($sal_ant['status'] == false) { return $sal_ant; }

      $id_r    = empty($sal_ant['data']) ? 0 : (empty($sal_ant['data']['idalmacen_resumen']) ? 0 : $sal_ant['data']['idalmacen_resumen']);            
        
      $sql_1_1 = "SELECT SUM( cantidad ) as cantidad
      FROM almacen_salida WHERE estado = '1' AND estado_delete = '1' AND idalmacen_resumen = '$id_r' ;";
      $salida_sum = ejecutarConsultaSimpleFila($sql_1_1); if ($salida_sum['status'] == false) { return $salida_sum; }
      
      $sql_2_1 = "SELECT SUM( dc.cantidad ) as cantidad FROM compra_por_proyecto as cpp, detalle_compra as dc 
      WHERE cpp.idcompra_proyecto = dc.idcompra_proyecto AND  cpp.idproyecto = '$idproyecto' AND cpp.estado = '1' AND cpp.estado_delete = '1' AND dc.idproducto = '$id_p';";
      $entrada_sum = ejecutarConsultaSimpleFila($sql_2_1); if ($entrada_sum['status'] == false) { return $entrada_sum; }        
      
      $sql_3 = "SELECT  SUM(apg.cantidad) as cant_sum, GROUP_CONCAT( cantidad , ' ') as cant_group
      FROM almacen_producto_guardado as apg, almacen_general as ag
      WHERE apg.idalmacen_resumen = '$id_r' AND apg.estado = '1'  AND apg.estado_delete = '1';";
      $almacen = ejecutarConsultaSimpleFila($sql_3); if ($almacen['status'] == false) { return $almacen; }

      $resumen_producto[] = [
        'idalmacen_resumen' => $id_r,
        'idproyecto'        => $val1['idproyecto'],
        'idproducto'        => $val1['idproducto'],
        'saldo_anterior'    => empty($sal_ant['data']) ? 0 : (empty($sal_ant['data']['saldo_anterior']) ? 0 : floatval($sal_ant['data']['saldo_anterior'])) ,
        'entrada_total'     => empty($val1['cantidad']) ? 0 :  floatval($val1['cantidad'] ) ,
        'nombre_producto'   => $val1['nombre_producto'],
        'unidad_medida'     => $val1['nombre_medida'],
        'abreviacion_um'    => $val1['abreviacion'],
        'categoria'         => $val1['clasificacion'],
        'cant_sum_oa'       => empty( $almacen['data'] ) ? 0 : ( empty($almacen['data']['cant_sum']) ? 0 : floatval($almacen['data']['cant_sum'])  ) ,
        'cant_group_oa'     => empty( $almacen['data'] ) ? '' : ( empty($almacen['data']['cant_group']) ? '' : $almacen['data']['cant_group']  ) ,
        'salida_sum'        => empty($salida_sum['data']) ? 0 : (empty($salida_sum['data']['cantidad']) ? 0 : floatval($salida_sum['data']['cantidad']) ) ,                   
        'entrada_sum'       => empty($entrada_sum['data']) ? 0 : (empty($entrada_sum['data']['cantidad']) ? 0 : floatval($entrada_sum['data']['cantidad']) ) ,       
      ];
    }
    return $retorno = [
      'status'  => true, 
      'data'    => [
        'producto'  => $resumen_producto, 
        'fechas'    => $data_meses,         
        'data_sq'   => $data_sq
      ] , 
      'message' => 'todo bien'
    ];
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

  // ══════════════════════════════════════ SELECT 2 ══════════════════════════════════════ 

  public function select2_productos_todos($idproyecto){
    $sql_0 = "SELECT pr.idproducto, pr.nombre AS nombre_producto, um.nombre_medida, um.abreviacion,  pr.modelo, ci.nombre as clasificacion    
		FROM  producto AS pr, categoria_insumos_af AS ci, unidad_medida AS um 
		WHERE pr.idcategoria_insumos_af = ci.idcategoria_insumos_af AND um.idunidad_medida  = pr.idunidad_medida 
    AND pr.estado = '1' AND pr.estado_delete = '1' ORDER BY pr.nombre ASC;";    
    return ejecutarConsultaArray($sql_0);
  }

  public function select2_productos_comprados($idproyecto){
    $resumen_producto = [];
    $sql_0 = "SELECT cpp.idcompra_proyecto, dc.iddetalle_compra, dc.idproducto, sum(dc.cantidad) as cantidad, dc.marca,
    um.nombre_medida, um.abreviacion, pr.nombre AS nombre_producto, pr.modelo, ci.nombre as clasificacion    
		FROM proyecto AS p, compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr, categoria_insumos_af AS ci, unidad_medida AS um 
		WHERE p.idproyecto = cpp.idproyecto AND cpp.idcompra_proyecto = dc.idcompra_proyecto AND dc.idproducto = pr.idproducto
    AND um.idunidad_medida  = pr.idunidad_medida AND pr.idcategoria_insumos_af = ci.idcategoria_insumos_af
    AND cpp.idproyecto = '$idproyecto'
    AND cpp.estado = '1' AND cpp.estado_delete = '1' AND dc.idclasificacion_grupo != '11' GROUP BY dc.idproducto ORDER BY pr.nombre ASC;";    
    $producto = ejecutarConsultaArray($sql_0);
    
    foreach ($producto['data'] as $key => $val1) {

      $id_p   = $val1['idproducto'];

      $sql_1 = "SELECT idalmacen_resumen, idproyecto, idproducto, saldo_anterior 
      FROM almacen_resumen WHERE estado = '1' AND estado_delete = '1' AND idproducto = '$id_p' AND idproyecto = '$idproyecto';";   
      $sal_ant = ejecutarConsultaSimpleFila($sql_1); if ($sal_ant['status'] == false) { return $sal_ant; }

      $id_r    = empty($sal_ant['data']) ? 0 : (empty($sal_ant['data']['idalmacen_resumen']) ? 0 : $sal_ant['data']['idalmacen_resumen']);            
        
      $sql_1_1 = "SELECT SUM( cantidad ) as cantidad
      FROM almacen_salida WHERE estado = '1' AND estado_delete = '1' AND idalmacen_resumen = '$id_r' ;";
      $salida_sum = ejecutarConsultaSimpleFila($sql_1_1); if ($salida_sum['status'] == false) { return $salida_sum; }
      
      $sql_2_1 = "SELECT SUM( dc.cantidad ) as cantidad FROM compra_por_proyecto as cpp, detalle_compra as dc 
      WHERE cpp.idcompra_proyecto = dc.idcompra_proyecto AND  cpp.idproyecto = '$idproyecto' AND cpp.estado = '1' AND cpp.estado_delete = '1' AND dc.idproducto = '$id_p';";
      $entrada_sum = ejecutarConsultaSimpleFila($sql_2_1); if ($entrada_sum['status'] == false) { return $entrada_sum; }        
      
      $sql_3 = "SELECT  SUM(apg.cantidad) as cant_sum
      FROM almacen_producto_guardado as apg, almacen_general as ag
      WHERE apg.idalmacen_resumen = '$id_r' AND apg.estado = '1'  AND apg.estado_delete = '1';";
      $almacen = ejecutarConsultaSimpleFila($sql_3); if ($almacen['status'] == false) { return $almacen; }

      $saldo_anterior = empty($sal_ant['data']) ? 0 : (empty($sal_ant['data']['saldo_anterior']) ? 0 : floatval($sal_ant['data']['saldo_anterior'])) ;        
      $cant_sum_oa    = empty( $almacen['data'] ) ? 0 : ( empty($almacen['data']['cant_sum']) ? 0 : floatval($almacen['data']['cant_sum'])  ) ;
      $salida_t         = empty($salida_sum['data']) ? 0 : (empty($salida_sum['data']['cantidad']) ? 0 : floatval($salida_sum['data']['cantidad']) ) ;                   
      $entrada_t       = empty($entrada_sum['data']) ? 0 : (empty($entrada_sum['data']['cantidad']) ? 0 : floatval($entrada_sum['data']['cantidad']) ) ;

      $saldo = ($saldo_anterior + $entrada_t) - ($salida_t + $cant_sum_oa) ;

      $resumen_producto[] = [
        'idalmacen_resumen' => $id_r,
        'idproducto'        => $val1['idproducto'],
        'saldo_anterior'    => empty($sal_ant['data']) ? 0 : (empty($sal_ant['data']['saldo_anterior']) ? 0 : floatval($sal_ant['data']['saldo_anterior'])) ,
        'entrada_total'     => empty($val1['cantidad']) ? 0 :  floatval($val1['cantidad'] ) ,
        'nombre_producto'   => $val1['nombre_producto'],
        'unidad_medida'     => $val1['nombre_medida'],
        'abreviacion_um'    => $val1['abreviacion'],
        'categoria'         => $val1['clasificacion'],
        'cant_sum_oa'       => empty( $almacen['data'] ) ? 0 : ( empty($almacen['data']['cant_sum']) ? 0 : floatval($almacen['data']['cant_sum'])  ) ,
        'salida_sum'        => empty($salida_sum['data']) ? 0 : (empty($salida_sum['data']['cantidad']) ? 0 : floatval($salida_sum['data']['cantidad']) ) ,                   
        'entrada_sum'       => empty($entrada_sum['data']) ? 0 : (empty($entrada_sum['data']['cantidad']) ? 0 : floatval($entrada_sum['data']['cantidad']) ) ,       
        'saldo'      => $saldo,
      ];
    }

    return $retorno = [
      'status'  => true, 
      'data'    => $resumen_producto , 
      'message' => 'todo bien'
    ]; 
  }

}

?>
