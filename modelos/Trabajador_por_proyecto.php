<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class TrabajadorPorProyecto
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  //Implementamos un método para insertar registros
  public function insertar( $idproyecto, $trabajador, $desempenio, $sueldo_diario, $sueldo_mensual, 
  $sueldo_hora, $fecha_inicio, $fecha_fin, $cantidad_dias )
  {
    $sql_1 = "SELECT t.nombres as trabajador, t.tipo_documento,t.numero_documento, tip.nombre as tipo, tpp.desempenio, 
    tpp.sueldo_mensual, tpp.estado, tpp.estado_delete
    FROM trabajador_por_proyecto as tpp, trabajador as t,  tipo_trabajador as tip
    WHERE tpp.idtrabajador = t.idtrabajador and t.idtipo_trabajador = tip.idtipo_trabajador
    AND  tpp.idproyecto ='$idproyecto' AND tpp.idtrabajador ='$trabajador';";
    $buscando = ejecutarConsultaArray($sql_1);

    if (empty($buscando['data'])) {
      $sql_orden = "SELECT orden_trabajador FROM trabajador_por_proyecto WHERE idproyecto = '$idproyecto' ORDER BY orden_trabajador DESC LIMIT 1;";
      $orden = ejecutarConsulta($sql_orden); if ( $orden['status'] == false) {return $orden; }  
      $num_orden = empty($orden['data']) ? 1 : ( empty($orden['data']['orden_trabajador']) ? 1 : $orden['data']['orden_trabajador'] ); 

      $sql_2 = "INSERT INTO trabajador_por_proyecto (idproyecto, idtrabajador, iddesempenio, sueldo_mensual, sueldo_diario, sueldo_hora, fecha_inicio, fecha_fin, cantidad_dias, orden_trabajador, user_created)
      VALUES ('$idproyecto', '$trabajador', '$desempenio', '$sueldo_mensual', '$sueldo_diario', '$sueldo_hora', '$fecha_inicio', '$fecha_fin', '$cantidad_dias', '$num_orden', '" . $_SESSION['idusuario'] . "')";
      $insertar =  ejecutarConsulta_retornarID($sql_2); if ($insertar['status'] == false) {  return $insertar; } 
      
      //add registro en nuestra bitacora
      $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('trabajador_por_proyecto','".$insertar['data']."','Nuevo trabajador al proyecto: ".$idproyecto."','" . $_SESSION['idusuario'] . "')";
      $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
      
      return $insertar;

    } else {
      $info_repetida = ''; 

      foreach ($buscando['data'] as $key => $value) {
        $info_repetida .= '<li class="text-left font-size-13px">
          <span class="font-size-15px text-danger"><b>Nombre: </b>'.$value['trabajador'].'</span><br>
          <b>'.$value['tipo_documento'].': </b>'.$value['numero_documento'].'<br>
          <b>Tipo: </b>'.$value['tipo'].'<br>
          <b>Desempeño: </b>'.$value['desempenio'].'<br>
          <b>Sueldo Mes: </b>'.$value['sueldo_mensual'].'<br>
          <b>Papelera: </b>'.( $value['estado']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO') .' <b>|</b>
          <b>Eliminado: </b>'. ($value['estado_delete']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO').'<br>
          <hr class="m-t-2px m-b-2px">
        </li>'; 
      }
      $sw = array( 'status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ul class="pl-3">'.$info_repetida.'</ul>', 'id_tabla' => '' );
      return $sw;
    }
  }

  //Implementamos un método para editar registros
  public function editar($idtrabajador_por_proyecto, $idproyecto, $trabajador, $desempenio, $sueldo_diario, $sueldo_mensual, 
  $sueldo_hora, $fecha_inicio, $fecha_fin, $cantidad_dias) {
    $sql = "UPDATE trabajador_por_proyecto SET idproyecto = '$idproyecto', idtrabajador='$trabajador', iddesempenio='$desempenio', 
		sueldo_mensual='$sueldo_mensual', sueldo_diario='$sueldo_diario', sueldo_hora='$sueldo_hora', fecha_inicio='$fecha_inicio', fecha_fin='$fecha_fin', cantidad_dias='$cantidad_dias',user_updated= '" . $_SESSION['idusuario'] . "'
		WHERE idtrabajador_por_proyecto='$idtrabajador_por_proyecto'";

    $editar =  ejecutarConsulta($sql); if ($editar['status'] == false) {  return $editar; } 

 		//add registro en nuestra bitacora
     $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('trabajador_por_proyecto','$idtrabajador_por_proyecto','Editar trabajador de proyecto','" . $_SESSION['idusuario'] . "')";
     $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }     

    return $editar;
  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idtrabajador) {
    $sql = "UPDATE trabajador_por_proyecto SET estado='0',user_trash= '" . $_SESSION['idusuario'] . "' WHERE idtrabajador_por_proyecto='$idtrabajador'";
		$desactivar= ejecutarConsulta($sql);

		if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('trabajador_por_proyecto','".$idtrabajador."','Trabajador de proyecto desactivado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $desactivar;

  }

  //Implementamos un método para activar categorías
  public function activar($idtrabajador) {
    $sql = "UPDATE trabajador_por_proyecto SET estado='1' WHERE idtrabajador_por_proyecto='$idtrabajador'";
    return ejecutarConsulta($sql);
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idtrabajador) {
    $sql = "SELECT tpp.idtrabajador_por_proyecto, tpp.idtrabajador, tpp.idproyecto, tpp.iddesempenio, tpp.sueldo_mensual, tpp.sueldo_diario, 
    tpp.sueldo_hora, tpp.fecha_inicio, tpp.fecha_fin, tpp.cantidad_dias, d.nombre_desempenio, oc.nombre_ocupacion, tp.nombre as nombre_tipo
    FROM trabajador_por_proyecto as tpp, desempenio as d, trabajador as t, ocupacion as oc, tipo_trabajador as tp
    WHERE tpp.iddesempenio = d.iddesempenio AND t.idocupacion = oc.idocupacion AND t.idtipo_trabajador = tp.idtipo_trabajador AND tpp.idtrabajador_por_proyecto='$idtrabajador'";
    $mostrar_data = ejecutarConsultaSimpleFila($sql); if ($mostrar_data['status'] == false) { return  $mostrar_data;}

    $trabajador = $mostrar_data['data']['idtrabajador'];

    $sql3 = "SELECT doc.iddetalle_desempenio, doc.idtrabajador, doc.iddesempenio, o.nombre_desempenio 
    FROM detalle_desempenio as doc,  desempenio as o  
    WHERE doc.iddesempenio = o.iddesempenio AND doc.idtrabajador = '$trabajador';";
    $detalle_desempenio = ejecutarConsultaArray($sql3); if ($detalle_desempenio['status'] == false) { return  $detalle_desempenio;}
    $html_desempenio = "";
    foreach ($detalle_desempenio['data'] as $key => $value2) {
      $html_desempenio .=  $value2['nombre_desempenio'].'; ';
    }    

    $data = array(
      'idtrabajador_por_proyecto'=> $mostrar_data['data']['idtrabajador_por_proyecto'],  
      'idtrabajador'            => $mostrar_data['data']['idtrabajador'], 
      'idproyecto'              => $mostrar_data['data']['idproyecto'], 
      'iddesempenio'            => $mostrar_data['data']['iddesempenio'],  
      'sueldo_mensual'          => $mostrar_data['data']['sueldo_mensual'], 
      'sueldo_diario'           => $mostrar_data['data']['sueldo_diario'],  
      'sueldo_hora'             => $mostrar_data['data']['sueldo_hora'],          
      'fecha_inicio'            => $mostrar_data['data']['fecha_inicio'], 
      'fecha_fin'               => $mostrar_data['data']['fecha_fin'], 
      'cantidad_dias'           => $mostrar_data['data']['cantidad_dias'], 
      'nombre_desempenio'       => $mostrar_data['data']['nombre_desempenio'], 
      'nombre_ocupacion'        => $mostrar_data['data']['nombre_ocupacion'], 
      'nombre_tipo'             => $mostrar_data['data']['nombre_tipo'], 
      
      'html_desempenio'=>  $html_desempenio
    );

    return $retorno=['status'=>true, 'message'=>'todo oka ps', 'data'=>$data];
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function ver_datos_trabajador($idtrabajador) {
    $sql = "SELECT  t.nombres, t.tipo_documento, t.numero_documento,  t.imagen_perfil as imagen, t.telefono, t.fecha_nacimiento,
    t.email, t.direccion, t.titular_cuenta, t.imagen_perfil, tpp.idtrabajador_por_proyecto, tpp.idtrabajador, tpp.idproyecto, 
    t.idocupacion, t.idtipo_trabajador, 
    tpp.desempenio, tpp.sueldo_mensual, tpp.sueldo_diario, tpp.sueldo_hora, tpp.fecha_inicio, tpp.fecha_fin, tpp.cantidad_dias,
    oc.nombre_ocupacion, tp.nombre as nombre_tipo_trabajador
    FROM trabajador_por_proyecto as tpp, ocupacion as oc, tipo_trabajador as tp, trabajador as t
    WHERE t.idocupacion = oc.idocupacion AND tpp.idtrabajador = t.idtrabajador AND t.idtipo_trabajador = tp.idtipo_trabajador 
    AND tpp.idtrabajador_por_proyecto='$idtrabajador'";
    $mostrar_data = ejecutarConsultaSimpleFila($sql); if ($mostrar_data['status'] == false) { return  $mostrar_data;}

    $trabajador = $mostrar_data['data']['idtrabajador'];

    $sql3 = "SELECT doc.iddetalle_desempenio, doc.idtrabajador, doc.iddesempenio, o.nombre_desempenio 
    FROM detalle_desempenio as doc, desempenio as o  
    WHERE doc.iddesempenio = o.iddesempenio AND doc.idtrabajador = '$trabajador';";
    $detalle_desempenio = ejecutarConsultaArray($sql3); if ($detalle_desempenio['status'] == false) { return  $detalle_desempenio;}
    $html_desempenio = "";
    foreach ($detalle_desempenio['data'] as $key => $value2) {
      $html_desempenio .=  '<li >'.$value2['nombre_desempenio'].'. </li>';
    }

    $sql2 = "SELECT cbt.idcuenta_banco_trabajador, cbt.idtrabajador, cbt.idbancos, cbt.cuenta_bancaria, cbt.cci, cbt.banco_seleccionado, b.nombre as banco
    FROM cuenta_banco_trabajador as cbt, bancos as b
    WHERE cbt.idbancos = b.idbancos AND cbt.idtrabajador='$idtrabajador' ORDER BY cbt.idcuenta_banco_trabajador ASC ;";
    $bancos = ejecutarConsultaArray($sql2);
    if ($bancos['status'] == false) { return  $bancos;}
    return $retorno=['status'=>true, 'message'=>'todo oka ps', 
      'data'=>['trabajador'=>$mostrar_data['data'], 'bancos'=>$bancos['data'], 'html_desempenio'=>'<ol class="pl-3">'.$html_desempenio. '</ol>']
    ];
  }

  //Implementar un método para listar los registros
  public function tbla_principal($nube_idproyecto, $estado) {
    
    $data = [];

    $sql = "SELECT t.idtrabajador, t.nombres, t.tipo_documento, t.numero_documento,  t.imagen_perfil as imagen, t.telefono, t.fecha_nacimiento,
    t.email, tpp.desempenio, tpp.sueldo_mensual, tpp.sueldo_diario, tpp.sueldo_hora, tpp.fecha_inicio, tpp.fecha_fin, tpp.estado, 
    tpp.idtrabajador_por_proyecto, t.idtipo_trabajador, tt.nombre as nombre_tipo, oc.nombre_ocupacion, d.nombre_desempenio
		FROM trabajador_por_proyecto as tpp, trabajador as t,  tipo_trabajador as tt, ocupacion as oc, desempenio as d
		WHERE tpp.idtrabajador = t.idtrabajador AND tt.idtipo_trabajador=t.idtipo_trabajador AND t.idocupacion = oc.idocupacion 
    AND d.iddesempenio = tpp.iddesempenio
    AND tpp.idproyecto = '$nube_idproyecto' AND tpp.estado='$estado' AND tpp.estado_delete='1' ORDER BY tpp.orden_trabajador ASC";
    $trabajdor = ejecutarConsultaArray($sql); if ($trabajdor['status'] == false) { return  $trabajdor;}

    foreach ($trabajdor['data'] as $key => $value) {
      $id = $value['idtrabajador'];
      $sql2 = "SELECT cbt.idcuenta_banco_trabajador, cbt.idtrabajador, cbt.idbancos, cbt.cuenta_bancaria, cbt.cci, cbt.banco_seleccionado, b.nombre as banco
      FROM cuenta_banco_trabajador as cbt, bancos as b
      WHERE cbt.idbancos = b.idbancos AND cbt.banco_seleccionado ='1' AND cbt.idtrabajador='$id' ;";
      $bancos = ejecutarConsultaSimpleFila($sql2); if ($bancos['status'] == false) { return  $bancos;}     

      $data[] = array(
        'orden' => $key+1,
        'idtrabajador_por_proyecto' =>$value['idtrabajador_por_proyecto'],
        'idtrabajador'    => $value['idtrabajador'],  
        'trabajador'      => $value['nombres'], 
        'tipo_documento'  => $value['tipo_documento'], 
        'numero_documento'=> $value['numero_documento'], 
        'imagen_perfil'   => $value['imagen'],          
        'telefono'        => $value['telefono'],         
        'fecha_nacimiento'=> $value['fecha_nacimiento'],
        'email'           => $value['email'],
        'sueldo_diario'   =>$value['sueldo_diario'],
        'sueldo_hora'     =>$value['sueldo_hora'],
        'fecha_inicio'    =>$value['fecha_inicio'],
        'fecha_fin'       =>$value['fecha_fin'],
        'estado'          =>$value['estado'],
                
        'idtipo_trabajador'=>$value['idtipo_trabajador'],
        'nombre_tipo'     =>$value['nombre_tipo'],
        'nombre_ocupacion'=> $value['nombre_ocupacion'],
        'nombre_desempeno'=>$value['nombre_desempenio'],

        'banco'           => (empty($bancos['data']) ? "": $bancos['data']['banco']), 
        'cuenta_bancaria' => (empty($bancos['data']) ? "" : $bancos['data']['cuenta_bancaria']), 
        'cci'             => (empty($bancos['data']) ? "" : $bancos['data']['cci']), 
      );
    }
    return $retorno=['status'=>true, 'message'=>'todo oka ps', 'data'=>$data];
  }

  //Seleccionar Trabajador Select2
  public function m_datos_trabajador($idtrabajador) {
    $sql = "SELECT tt.nombre as nombre_tipo, o.nombre_ocupacion FROM trabajador as t, tipo_trabajador as tt, ocupacion as o
    WHERE t.idtipo_trabajador = tt.idtipo_trabajador and t.idocupacion = o.idocupacion and t.idtrabajador='$idtrabajador' ";
    $tipo_trabajador =  ejecutarConsultaSimpleFila($sql); if ($tipo_trabajador['status'] == false) { return  $tipo_trabajador;}

    $sql3 = "SELECT doc.iddetalle_desempenio, doc.idtrabajador, doc.iddesempenio, o.nombre_desempenio 
    FROM detalle_desempenio as doc, trabajador as t, desempenio as o  
    WHERE doc.idtrabajador = t.idtrabajador AND doc.iddesempenio = o.iddesempenio AND t.idtrabajador = '$idtrabajador';";
    $detalle_desempenio = ejecutarConsultaArray($sql3); if ($detalle_desempenio['status'] == false) { return  $detalle_desempenio;}
    $html_desempenio = "";
    foreach ($detalle_desempenio['data'] as $key => $value2) {
      $html_desempenio .=  $value2['nombre_desempenio'].'| ';
    }    

    return $retorno=['status'=>true, 'message'=>'todo oka ps', 'data'=>['trabajador'=>$tipo_trabajador['data'],'html_desempenio'=>$html_desempenio]];
  }

  // :::::::::::::::::::::::::::::::::::::::::::::::: O R D E N   T R A B A J A D O R ::::::::::::::::::::::::::::::::::::::::::::::::
  //Seleccionar Trabajador Select2
  public function editar_orden_trabajador($orden_trabajador) {   
    $cont = 1;
    foreach ($orden_trabajador as $key => $value) {

      $sql = "UPDATE trabajador_por_proyecto SET orden_trabajador='$cont' WHERE idtrabajador_por_proyecto='$value' ";
      $update_orden =  ejecutarConsulta($sql); if ($update_orden['status'] == false) { return  $update_orden;}
      $cont++;
    }    

    return $retorno=['status'=>true, 'message'=>'todo oka ps', 'data'=>[]];
  }

}

?>
