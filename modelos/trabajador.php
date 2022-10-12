<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Trabajador
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  //Implementamos un método para insertar registros
  public function insertar($idproyecto, $trabajador, $tipo_trabajador, $desempenio, $sueldo_mensual, $sueldo_diario, $sueldo_hora, $fecha_inicio, $fecha_fin, $cantidad_dias)
  {
    $sql_1 = "SELECT t.nombres as trabajador, t.tipo_documento,t.numero_documento, tip.nombre as tipo, tpp.desempenio, 
    tpp.sueldo_mensual, tpp.estado, tpp.estado_delete
    FROM trabajador_por_proyecto as tpp, trabajador as t,  tipo_trabajador as tip
    WHERE tpp.idtrabajador = t.idtrabajador and tpp.idtipo_trabjador = tip.idtipo_trabajador
    AND  tpp.idproyecto ='$idproyecto' AND tpp.idtrabajador ='$trabajador';";
    $buscando = ejecutarConsultaArray($sql_1);

    if (empty($buscando['data'])) {
      $sql_2 = "INSERT INTO trabajador_por_proyecto (idproyecto, idtrabajador, idtipo_trabajador, idocupacion, sueldo_mensual, sueldo_diario, sueldo_hora, fecha_inicio, fecha_fin, cantidad_dias, user_created)
      VALUES ('$idproyecto', '$trabajador', '$tipo_trabajador', '$desempenio', '$sueldo_mensual', '$sueldo_diario', '$sueldo_hora', '$fecha_inicio', '$fecha_fin', '$cantidad_dias','" . $_SESSION['idusuario'] . "')";
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
      $sw = array( 'status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ul>'.$info_repetida.'</ul>', 'id_tabla' => '' );
      return $sw;
    }
  }

  //Implementamos un método para editar registros
  public function editar($idtrabajador_por_proyecto, $idproyecto, $trabajador, $tipo_trabajador, $desempenio, $sueldo_mensual, $sueldo_diario, $sueldo_hora, $fecha_inicio, $fecha_fin, $cantidad_dias)
  {
    $sql = "UPDATE trabajador_por_proyecto SET  idtrabajador='$trabajador',  idtipo_trabajador ='$tipo_trabajador', idocupacion='$desempenio', 
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
    $sql = "SELECT tpp.idtrabajador_por_proyecto, tpp.idtrabajador, tpp.idproyecto, tpp.idocupacion, tpp.idtipo_trabajador, 
    tpp.desempenio, tpp.sueldo_mensual, tpp.sueldo_diario, tpp.sueldo_hora, tpp.fecha_inicio, tpp.fecha_fin, tpp.cantidad_dias,
    oc.nombre_ocupacion, tp.nombre as nombre_tipo_trabajador
    FROM trabajador_por_proyecto as tpp, ocupacion as oc, tipo_trabajador as tp
    WHERE tpp.idocupacion = oc.idocupacion AND tpp.idtipo_trabajador = tp.idtipo_trabajador AND tpp.idtrabajador_por_proyecto='$idtrabajador'";
    $mostrar_data = ejecutarConsultaSimpleFila($sql); if ($mostrar_data['status'] == false) { return  $mostrar_data;}

    $trabajador = $mostrar_data['data']['idtrabajador'];

    $sql3 = "SELECT doc.iddetalle_ocupacion, doc.idtrabajador, doc.idocupacion, o.nombre_ocupacion 
    FROM detalle_ocupacion as doc,  ocupacion as o  
    WHERE doc.idocupacion = o.idocupacion AND doc.idtrabajador = '$trabajador';";
    $detalle_ocupacion = ejecutarConsultaArray($sql3); if ($detalle_ocupacion['status'] == false) { return  $detalle_ocupacion;}
    $html_ocupacion = "";
    foreach ($detalle_ocupacion['data'] as $key => $value2) {
      $html_ocupacion .=  $value2['nombre_ocupacion'].'; ';
    }    

    $data = array(
      'idtrabajador_por_proyecto'=> $mostrar_data['data']['idtrabajador_por_proyecto'],  
      'idtrabajador'            => $mostrar_data['data']['idtrabajador'], 
      'idproyecto'              => $mostrar_data['data']['idproyecto'], 
      'iddesempenio'             => $mostrar_data['data']['idocupacion'], 
      'idtipo_trabajador'       => $mostrar_data['data']['idtipo_trabajador'],    
      'sueldo_mensual'          => $mostrar_data['data']['sueldo_mensual'], 
      'sueldo_diario'           => $mostrar_data['data']['sueldo_diario'],  
      'sueldo_hora'             => $mostrar_data['data']['sueldo_hora'],          
      'fecha_inicio'            => $mostrar_data['data']['fecha_inicio'], 
      'fecha_fin'               => $mostrar_data['data']['fecha_fin'], 
      'cantidad_dias'           => $mostrar_data['data']['cantidad_dias'], 
      'nombre_desempenio'        => $mostrar_data['data']['nombre_ocupacion'], 
      'nombre_tipo_trabajador'  => $mostrar_data['data']['nombre_tipo_trabajador'], 
      
      'html_ocupacion'=>  $html_ocupacion
    );

    return $retorno=['status'=>true, 'message'=>'todo oka ps', 'data'=>$data];
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function ver_datos_trabajador($idtrabajador) {
    $sql = "SELECT  t.nombres, t.tipo_documento, t.numero_documento,  t.imagen_perfil as imagen, t.telefono, t.fecha_nacimiento,
    t.email, t.direccion, t.titular_cuenta, t.imagen_perfil, tpp.idtrabajador_por_proyecto, tpp.idtrabajador, tpp.idproyecto, tpp.idocupacion, tpp.idtipo_trabajador, 
    tpp.desempenio, tpp.sueldo_mensual, tpp.sueldo_diario, tpp.sueldo_hora, tpp.fecha_inicio, tpp.fecha_fin, tpp.cantidad_dias,
    oc.nombre_ocupacion, tp.nombre as nombre_tipo_trabajador
    FROM trabajador_por_proyecto as tpp, ocupacion as oc, tipo_trabajador as tp, trabajador as t
    WHERE tpp.idocupacion = oc.idocupacion AND tpp.idtrabajador = t.idtrabajador AND tpp.idtipo_trabajador = tp.idtipo_trabajador AND tpp.idtrabajador_por_proyecto='$idtrabajador'";
    $mostrar_data = ejecutarConsultaSimpleFila($sql); if ($mostrar_data['status'] == false) { return  $mostrar_data;}

    $trabajador = $mostrar_data['data']['idtrabajador'];

    $sql3 = "SELECT doc.iddetalle_ocupacion, doc.idtrabajador, doc.idocupacion, o.nombre_ocupacion 
    FROM detalle_ocupacion as doc,  ocupacion as o  
    WHERE doc.idocupacion = o.idocupacion AND doc.idtrabajador = '$trabajador';";
    $detalle_ocupacion = ejecutarConsultaArray($sql3); if ($detalle_ocupacion['status'] == false) { return  $detalle_ocupacion;}
    $html_ocupacion = "";
    foreach ($detalle_ocupacion['data'] as $key => $value2) {
      $html_ocupacion .=  '<li >'.$value2['nombre_ocupacion'].'. </li>';
    }

    $sql2 = "SELECT cbt.idcuenta_banco_trabajador, cbt.idtrabajador, cbt.idbancos, cbt.cuenta_bancaria, cbt.cci, cbt.banco_seleccionado, b.nombre as banco
    FROM cuenta_banco_trabajador as cbt, bancos as b
    WHERE cbt.idbancos = b.idbancos AND cbt.idtrabajador='$idtrabajador' ORDER BY cbt.idcuenta_banco_trabajador ASC ;";
    $bancos = ejecutarConsultaArray($sql2);
    if ($bancos['status'] == false) { return  $bancos;}
    return $retorno=['status'=>true, 'message'=>'todo oka ps', 
      'data'=>['trabajador'=>$mostrar_data['data'], 'bancos'=>$bancos['data'], 'html_ocupacion'=>'<ol class="pl-3">'.$html_ocupacion. '</ol>']
    ];
  }

  //Implementar un método para listar los registros
  public function tbla_principal($nube_idproyecto, $estado) {
    
    $data = [];

    $sql = "SELECT t.idtrabajador, t.nombres, t.tipo_documento, t.numero_documento,  t.imagen_perfil as imagen, t.telefono, t.fecha_nacimiento,
    t.email, tpp.desempenio, tpp.sueldo_mensual, tpp.sueldo_diario, tpp.sueldo_hora, tpp.fecha_inicio, tpp.fecha_fin, tpp.estado, 
    tpp.idtrabajador_por_proyecto, tpp.idtipo_trabajador, tt.nombre as nombre_tipo, oc.nombre_ocupacion as desempeno
		FROM trabajador_por_proyecto as tpp, trabajador as t, proyecto AS p, tipo_trabajador as tt, ocupacion as oc
		WHERE tpp.idproyecto = p.idproyecto AND tpp.idtrabajador = t.idtrabajador AND tt.idtipo_trabajador=tpp.idtipo_trabajador and tpp.idocupacion = oc.idocupacion
    AND tpp.idproyecto = '$nube_idproyecto' AND tpp.estado='$estado' AND tpp.estado_delete='1' ORDER BY t.nombres ASC";
    $trabajdor = ejecutarConsultaArray($sql);
    if ($trabajdor['status'] == false) { return  $trabajdor;}

    foreach ($trabajdor['data'] as $key => $value) {
      $id = $value['idtrabajador'];
      $sql2 = "SELECT cbt.idcuenta_banco_trabajador, cbt.idtrabajador, cbt.idbancos, cbt.cuenta_bancaria, cbt.cci, cbt.banco_seleccionado, b.nombre as banco
      FROM cuenta_banco_trabajador as cbt, bancos as b
      WHERE cbt.idbancos = b.idbancos AND cbt.banco_seleccionado ='1' AND cbt.idtrabajador='$id' ;";
      $bancos = ejecutarConsultaSimpleFila($sql2);
      if ($bancos['status'] == false) { return  $bancos;}

      $data[] = array(
        'idtrabajador'    => $value['idtrabajador'],  
        'trabajador'      => $value['nombres'], 
        'tipo_documento'  => $value['tipo_documento'], 
        'numero_documento'=> $value['numero_documento'], 
        'imagen_perfil'   => $value['imagen'],          
        'desempenio'      => $value['desempenio'], 
        'telefono'        => $value['telefono'], 
        'desempenio'      => $value['desempenio'],         
        'fecha_nacimiento'=> $value['fecha_nacimiento'],
        'email'           => $value['email'],
        'sueldo_diario'   =>$value['sueldo_diario'],
        'sueldo_hora'     =>$value['sueldo_hora'],
        'fecha_inicio'    =>$value['fecha_inicio'],
        'fecha_fin'       =>$value['fecha_fin'],
        'estado'          =>$value['estado'],
        'idtrabajador_por_proyecto' =>$value['idtrabajador_por_proyecto'],        
        'idtipo_trabajador'=>$value['idtipo_trabajador'],
        'nombre_tipo'     =>$value['nombre_tipo'],
        'desempeno'=>$value['desempeno'],

        'banco'           => (empty($bancos['data']) ? "": $bancos['data']['banco']), 
        'cuenta_bancaria' => (empty($bancos['data']) ? "" : $bancos['data']['cuenta_bancaria']), 
        'cci'             => (empty($bancos['data']) ? "" : $bancos['data']['cci']), 
      );
    }
    return $retorno=['status'=>true, 'message'=>'todo oka ps', 'data'=>$data];
  }

  //Seleccionar Trabajador Select2
  public function m_datos_trabajador($idtrabajador) {
    $sql = "SELECT t.numero_documento, t.idtipo_trabajador
		FROM trabajador  as t
		WHERE t.idtrabajador='$idtrabajador' AND t.estado='1' ";
    $tipo_trabajdor =  ejecutarConsultaSimpleFila($sql); if ($tipo_trabajdor['status'] == false) { return  $tipo_trabajdor;}

    $sql3 = "SELECT doc.iddetalle_ocupacion, doc.idtrabajador, doc.idocupacion, o.nombre_ocupacion 
    FROM detalle_ocupacion as doc, trabajador as t, ocupacion as o  
    WHERE doc.idtrabajador = t.idtrabajador AND doc.idocupacion = o.idocupacion AND t.idtrabajador = '$idtrabajador';";
    $detalle_ocupacion = ejecutarConsultaArray($sql3); if ($detalle_ocupacion['status'] == false) { return  $detalle_ocupacion;}
    $html_ocupacion = "";
    foreach ($detalle_ocupacion['data'] as $key => $value2) {
      $html_ocupacion .=  $value2['nombre_ocupacion'].'; ';
    }    

    return $retorno=['status'=>true, 'message'=>'todo oka ps', 'data'=>['tipo_trabajdor'=>$tipo_trabajdor['data'],'html_ocupacion'=>$html_ocupacion]];
  }

}

?>
