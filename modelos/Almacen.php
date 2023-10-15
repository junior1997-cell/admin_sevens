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
       
      $data_almacen = [];
      foreach ($dias_rango as $key2 => $val2) {
        
        $sql_1 = "SELECT idalmacen_salida, idalmacen_resumen, fecha_ingreso, dia_ingreso, cantidad, marca 
        FROM almacen_salida WHERE estado = '1' AND estado_delete = '1' AND idalmacen_resumen = '$id_r' AND fecha_ingreso = '$val2';";
        $salida = ejecutarConsultaArray($sql_1); if ($salida['status'] == false) { return $salida; }
        $sql_1_1 = "SELECT SUM( cantidad ) as cantidad
        FROM almacen_salida WHERE estado = '1' AND estado_delete = '1' AND idalmacen_resumen = '$id_r' AND fecha_ingreso = '$val2';";
        $salida_sum = ejecutarConsultaSimpleFila($sql_1_1); if ($salida_sum['status'] == false) { return $salida_sum; }

        $sql_2 = "SELECT dc.idproducto, dc.cantidad FROM compra_por_proyecto as cpp, detalle_compra as dc 
        WHERE cpp.idcompra_proyecto = dc.idcompra_proyecto AND cpp.fecha_compra = '$val2' AND cpp.idproyecto = '$idproyecto' AND cpp.estado = '1' AND cpp.estado_delete = '1' AND dc.idproducto = '$id_p';";
        $entrada = ejecutarConsultaArray($sql_2); if ($entrada['status'] == false) { return $entrada; }
        $sql_2_1 = "SELECT SUM( dc.cantidad ) as cantidad FROM compra_por_proyecto as cpp, detalle_compra as dc 
        WHERE cpp.idcompra_proyecto = dc.idcompra_proyecto AND cpp.fecha_compra = '$val2' AND cpp.idproyecto = '$idproyecto' AND cpp.estado = '1' AND cpp.estado_delete = '1' AND dc.idproducto = '$id_p';";
        $entrada_sum = ejecutarConsultaSimpleFila($sql_2_1); if ($entrada_sum['status'] == false) { return $entrada_sum; }

        $data_almacen[] =  [
          'fecha'=> $val2, 
          'salida'=> $salida['data'],          
          'salida_sum'=> empty($salida_sum['data']) ? 0 : (empty($salida_sum['data']['cantidad']) ? 0 : floatval($salida_sum['data']['cantidad']) ) ,
          'entrada'=> $entrada['data'],          
          'entrada_sum'=> empty($entrada_sum['data']) ? 0 : (empty($entrada_sum['data']['cantidad']) ? 0 : floatval($entrada_sum['data']['cantidad']) ) ,      
        ];
      }  
      
      // $sql_3 = "SELECT SUM(dc.cantidad) AS cantidad  FROM compra_por_proyecto as cpp, detalle_compra as dc 
      // WHERE cpp.idcompra_proyecto = dc.idcompra_proyecto AND cpp.estado = '1' AND cpp.estado_delete = '1' AND cpp.idproyecto = '$idproyecto' AND dc.idproducto = '$id_p';";
      // $entrada = ejecutarConsultaSimpleFila($sql_3); if ($entrada['status'] == false) { return $entrada; }

      $resumen_producto[] = [
        'idalmacen_resumen' => empty($sal_ant['data']) ? '' : (empty($sal_ant['data']['idalmacen_resumen']) ? '' : $sal_ant['data']['idalmacen_resumen']),
        'idproyecto'        => $val1['idproyecto'],
        'idproducto'        => $val1['idproducto'],
        'saldo_anterior'    => empty($sal_ant['data']) ? 0 : (empty($sal_ant['data']['saldo_anterior']) ? 0 : floatval($sal_ant['data']['saldo_anterior'])) ,
        'entrada_total'     => empty($val1['cantidad']) ? 0 :  floatval($val1['cantidad'] ) ,
        'nombre_producto'   => $val1['nombre_producto'],
        'unidad_medida'     => $val1['nombre_medida'],
        'abreviacion_um'    => $val1['abreviacion'],
        'categoria'         => $val1['clasificacion'],
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

    $sql_0 = "SELECT als.idalmacen_salida, als.idalmacen_resumen, als.fecha_ingreso, als.dia_ingreso, als.cantidad, als.marca, p.idproducto, p.nombre as producto 
    FROM almacen_salida as als, almacen_resumen as ar, producto as p
    WHERE als.idalmacen_resumen = ar.idalmacen_resumen and ar.idproducto = p.idproducto AND  als.estado ='1' AND als.estado_delete = '1' 
    AND als.fecha_ingreso = '$fecha' AND ar.idproducto = '$id_producto' AND ar.idproyecto = '$id_proyecto' ORDER BY p.nombre ASC;";    
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
	public function desactivar_x_dia($idalmacen_salida)
	{
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

  // ══════════════════════════════════════ O T R O S   S A L D O S ══════════════════════════════════════
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
    $sql_0 = "SELECT cpp.idcompra_proyecto, dc.iddetalle_compra, dc.idproducto, sum(dc.cantidad) as cantidad, dc.marca,
    um.nombre_medida, um.abreviacion, pr.nombre AS nombre_producto, pr.modelo, ci.nombre as clasificacion    
		FROM proyecto AS p, compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr, categoria_insumos_af AS ci, unidad_medida AS um 
		WHERE p.idproyecto = cpp.idproyecto AND cpp.idcompra_proyecto = dc.idcompra_proyecto AND dc.idproducto = pr.idproducto
    AND um.idunidad_medida  = pr.idunidad_medida AND pr.idcategoria_insumos_af = ci.idcategoria_insumos_af
    AND cpp.idproyecto = '$idproyecto'
    AND cpp.estado = '1' AND cpp.estado_delete = '1' AND dc.idclasificacion_grupo != '11' GROUP BY dc.idproducto ORDER BY pr.nombre ASC;";    
    return ejecutarConsultaArray($sql_0);
  }

}

?>
