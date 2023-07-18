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

  public function insertar_almacen($fecha_ingreso, $dia_ingreso, $idproducto, $marca, $cantidad){

    $ii = 0;
    while ($ii < count($idproducto)) {
      
      $sql_0 = "INSERT INTO almacen_x_proyecto(idproducto, fecha_ingreso, dia_ingreso, cantidad, marca, user_created)
      VALUES ('$idproducto[$ii]','$fecha_ingreso', '$dia_ingreso',  '$cantidad[$ii]', '$marca[$ii]','$this->id_usr_sesion')";         
      $new_almancen = ejecutarConsulta_retornarID($sql_0); if ( $new_almancen['status'] == false) {return $new_almancen; }  
      $id = $new_almancen['data'];
      //add registro en nuestra bitacora
      $sql_5 = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('almacen_x_proyecto','$id','Crear registro','$this->id_usr_sesion')";
      $bitacora = ejecutarConsulta($sql_5); if ( $bitacora['status'] == false) {return $bitacora; }  

      $ii++;
    }
   
    return $retorno = ['status' => true, 'message' => 'todo oka ps', 'data' => ''];
  }

  public function editar_almacen($fecha_ingreso, $dia_ingreso, $idproducto, $marca, $cantidad){

  }

  public function insertar_almacen_x_dia( $producto_xp, $fecha_ingreso_xp, $dia_ingreso_xp, $marca_xp, $cantidad_xp){ 

  }

  public function editar_almacen_x_dia($idalmacen_x_proyecto_xp, $producto_xp, $fecha_ingreso_xp, $dia_ingreso_xp, $marca_xp, $cantidad_xp){    
      
    $sql_0 = "UPDATE almacen_x_proyecto SET idproducto='$producto_xp',fecha_ingreso='$fecha_ingreso_xp',dia_ingreso='$dia_ingreso_xp',
    cantidad='$cantidad_xp',marca='$marca_xp', user_updated='$this->id_usr_sesion' 
    WHERE idalmacen_x_proyecto='$idalmacen_x_proyecto_xp';";         
    $new_almancen = ejecutarConsulta($sql_0); if ( $new_almancen['status'] == false) {return $new_almancen; }  
    
    //add registro en nuestra bitacora
    $sql_5 = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('almacen_x_proyecto','$idalmacen_x_proyecto_xp','Actualizar registro','$this->id_usr_sesion')";
    $bitacora = ejecutarConsulta($sql_5); if ( $bitacora['status'] == false) {return $bitacora; }  
    
    return $retorno = ['status' => true, 'message' => 'todo oka ps', 'data' => ''];
  }

  //Implementar un método para listar los registros
  public function tbla_principal($idproyecto, $fip, $ffp, $fpo) {

    $resumen_producto = []; $data_meses= []; $data_dias = []; $data_sq = [];

    
    
    $meses_rango= extraer_meses_de_rango( $fip, $ffp );
    $dias_rango = extraer_dias_de_rango( $fip, $ffp);

    foreach ($meses_rango as $key1 => $val1) {
      foreach ($dias_rango as $key2 => $val2) {
        if ( date('m',strtotime($val1)) == date('m',strtotime($val2)) ) {
          array_push($data_dias, $val2);
        }
      }    
      $data_meses[] = ['mes'  => $val1, 'dia'  => $data_dias, 'cantidad_dias'  => count($data_dias) , ]; #asigamos las fechas a un mes
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

    $sql_0 = "SELECT cpp.idcompra_proyecto, dc.iddetalle_compra, dc.idproducto, sum(dc.cantidad) as cantidad, dc.marca,
    um.nombre_medida, um.nombre_medida, um.abreviacion, pr.nombre AS nombre_producto, pr.modelo, ci.nombre as clasificacion    
		FROM proyecto AS p, compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr, categoria_insumos_af AS ci, unidad_medida AS um 
		WHERE p.idproyecto = cpp.idproyecto AND cpp.idcompra_proyecto = dc.idcompra_proyecto AND dc.idproducto = pr.idproducto
    AND um.idunidad_medida  = pr.idunidad_medida AND pr.idcategoria_insumos_af = ci.idcategoria_insumos_af
    AND cpp.idproyecto = '$idproyecto'
    AND cpp.estado = '1' AND cpp.estado_delete = '1' GROUP BY dc.idproducto ORDER BY pr.nombre ASC;";    
    $producto = ejecutarConsultaArray($sql_0); if ($producto['status'] == false) { return $producto; }   

    foreach ($producto['data'] as $key1 => $val1) {
      
      $idproducto   = $val1['idproducto'];

      $data_almacen = [];
      foreach ($dias_rango as $key2 => $val2) {
        
        $sql_1 = "SELECT axp.idalmacen_x_proyecto, axp.idproducto, axp.fecha_ingreso, axp.dia_ingreso, axp.cantidad, axp.marca
        FROM almacen_x_proyecto AS axp
        WHERE axp.idproducto = '$idproducto' AND axp.fecha_ingreso = '$val2' AND axp.estado = '1' AND axp.estado_delete = '1';";
        $almacen = ejecutarConsultaArray($sql_1); if ($almacen['status'] == false) { return $almacen; }
        $data_almacen[] =  [
          'fecha'=> $val2, 
          'data'=> $almacen['data'],          
        ];
      }      

      $resumen_producto[] = [
        'idcompra_proyecto' => $val1['idcompra_proyecto'],
        'iddetalle_compra'  => $val1['iddetalle_compra'],
        'idproducto'        => $val1['idproducto'],
        'cantidad'          => empty($val1['cantidad']) ? 0 : floatval($val1['cantidad']) ,
        'marca'             => $val1['marca'],
        'nombre_medida'     => $val1['nombre_medida'],
        'abreviacion'       => $val1['abreviacion'],
        'nombre_producto'   => $val1['nombre_producto'],        
        'modelo'            => $val1['modelo'],        
        'clasificacion'     => $val1['clasificacion'],
        'almacen'           => $data_almacen,        
      ];
    }
    return $retorno = [
      'status'  => true, 
      'data'    => [
        'producto'      => $resumen_producto, 
        'fechas'        => $data_meses, 
        'dias'          => $dias_rango, 
        'cant_dias'     => count($dias_rango) ,   
        'num_dia_regular'=> $dia_regular ,   
        'data_sq'       => $data_sq
      ] , 
      'message' => 'todo bien'
    ];
  }

  public function ver_almacen( $id_proyecto, $id_almacen, $id_producto,  ) {

    $sql_0 = "SELECT idalmacen_x_proyecto, idproducto, idtrabajador_por_proyecto, fecha_ingreso, dia_ingreso, cantidad, marca 
    FROM almacen_x_proyecto WHERE idalmacen_x_proyecto = '$id_almacen';";    
    $almacen = ejecutarConsultaSimpleFila($sql_0);

    $sql_0 = "SELECT  dc.marca,  pr.nombre AS nombre_producto
		FROM compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr
		WHERE cpp.idcompra_proyecto = dc.idcompra_proyecto AND dc.idproducto = pr.idproducto     
    AND cpp.idproyecto = '$id_proyecto' AND dc.idproducto = '$id_producto'
    AND cpp.estado = '1' AND cpp.estado_delete = '1'  GROUP BY dc.idproducto, dc.marca ORDER BY pr.nombre ASC;";    
    $marcas = ejecutarConsultaArray($sql_0);

    $data = [
      'idalmacen_x_proyecto' => $almacen['data']['idalmacen_x_proyecto'],
      'idproducto'          => $almacen['data']['idproducto'],
      'idtrabajador_por_proyecto' => $almacen['data']['idtrabajador_por_proyecto'],
      'fecha_ingreso'       => $almacen['data']['fecha_ingreso'],
      'dia_ingreso'         => $almacen['data']['dia_ingreso'],
      'cantidad'            => $almacen['data']['cantidad'],
      'marca'               => $almacen['data']['marca'],      
      'marca_array'               => $marcas['data'],      
    ];

    return $retorno = [
      'status'  => true, 
      'data'    => $data , 
      'message' => 'todo bien'
    ];
          
  }

  public function tbla_ver_almacen($fecha, $id_producto) {

    $sql_0 = "SELECT axp.idalmacen_x_proyecto, axp.idproducto, axp.fecha_ingreso, axp.dia_ingreso, axp.cantidad, axp.marca, axp.estado, p.nombre as producto, p.imagen
    FROM almacen_x_proyecto as axp, producto as p
    WHERE axp.idproducto = p.idproducto AND axp.fecha_ingreso = '$fecha' AND axp.idproducto ='$id_producto' AND axp.estado = '1' AND axp.estado_delete = '1' ORDER BY p.nombre ASC;";    
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

  public function select2_productos($idproyecto){
    $sql_0 = "SELECT cpp.idcompra_proyecto, dc.iddetalle_compra, dc.idproducto, sum(dc.cantidad) as cantidad, dc.marca,
    um.nombre_medida, um.nombre_medida, um.abreviacion, pr.nombre AS nombre_producto, pr.modelo, ci.nombre as clasificacion    
		FROM proyecto AS p, compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr, categoria_insumos_af AS ci, unidad_medida AS um 
		WHERE p.idproyecto = cpp.idproyecto AND cpp.idcompra_proyecto = dc.idcompra_proyecto AND dc.idproducto = pr.idproducto
    AND um.idunidad_medida  = pr.idunidad_medida AND pr.idcategoria_insumos_af = ci.idcategoria_insumos_af
    AND cpp.idproyecto = '$idproyecto'
    AND cpp.estado = '1' AND cpp.estado_delete = '1' GROUP BY dc.idproducto ORDER BY pr.nombre ASC;";    
    return ejecutarConsultaArray($sql_0);
  }

}

?>
