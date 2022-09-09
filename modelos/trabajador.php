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
  public function insertar($idproyecto, $trabajador, $cargo, $desempenio, $sueldo_mensual, $sueldo_diario, $sueldo_hora, $fecha_inicio, $fecha_fin, $cantidad_dias)
  {
    $sql_1 = "SELECT t.nombres as trabajador, t.tipo_documento,t.numero_documento, ct.nombre as cargo, tip.nombre as tipo, tpp.desempenio, 
    tpp.sueldo_mensual, tpp.estado, tpp.estado_delete
    FROM trabajador_por_proyecto as tpp, trabajador as t, cargo_trabajador as ct, tipo_trabajador as tip
    WHERE tpp.idtrabajador = t.idtrabajador AND tpp.idcargo_trabajador = ct.idcargo_trabajador and ct.idtipo_trabjador = tip.idtipo_trabajador
    AND  tpp.idproyecto ='$idproyecto' AND tpp.idtrabajador ='$trabajador';";
    $buscando = ejecutarConsultaArray($sql_1);

    if (empty($buscando['data'])) {
      $sql_2 = "INSERT INTO trabajador_por_proyecto (idproyecto, idtrabajador, idcargo_trabajador, desempenio, sueldo_mensual, sueldo_diario, sueldo_hora, fecha_inicio, fecha_fin, cantidad_dias, user_created)
      VALUES ('$idproyecto', '$trabajador', '$cargo', '$desempenio', '$sueldo_mensual', '$sueldo_diario', '$sueldo_hora', '$fecha_inicio', '$fecha_fin', '$cantidad_dias','" . $_SESSION['idusuario'] . "')";
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
          <b>'.$value['tipo'].': </b>'.$value['cargo'].'<br>
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
  public function editar($idtrabajador_por_proyecto, $trabajador, $cargo, $desempenio, $sueldo_mensual, $sueldo_diario, $sueldo_hora, $fecha_inicio, $fecha_fin, $cantidad_dias)
  {
    $sql = "UPDATE trabajador_por_proyecto SET  idtrabajador='$trabajador',  idcargo_trabajador ='$cargo', desempenio='$desempenio', 
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
    $sql = "SELECT  tp.idtrabajador_por_proyecto,	tp.idtrabajador,tp.idproyecto, tp.idcargo_trabajador,	tp.desempenio, tp.sueldo_mensual,	tp.sueldo_diario,	tp.sueldo_hora,	tp.fecha_inicio, tp.fecha_fin, tp.cantidad_dias, tt.idtipo_trabajador,	ct.idcargo_trabajador, o.nombre_ocupacion
		FROM trabajador_por_proyecto as tp, trabajador as t, cargo_trabajador as ct, tipo_trabajador as tt, ocupacion as o
		WHERE tp.idtrabajador_por_proyecto='$idtrabajador' AND ct.idcargo_trabajador=tp.idcargo_trabajador AND ct.idtipo_trabjador=tt.idtipo_trabajador
		AND t.idocupacion=o.idocupacion	AND t.idtrabajador = tp.idtrabajador";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function ver_datos_trabajador($idtrabajador) {
    $sql = "SELECT t.nombres, t.tipo_documento, t.numero_documento,	t.fecha_nacimiento,	tp.desempenio as desempeno,	
    tp.idcargo_trabajador  as cargo, t.titular_cuenta,	tp.sueldo_mensual, tp.sueldo_diario,	tp.sueldo_hora,	
    tp.fecha_inicio, tp.fecha_fin, tp.cantidad_dias, t.direccion, t.telefono,	t.email, t.imagen_perfil,
    tt.nombre tipo_trabajador, ct.nombre cargo_trabajador
		FROM trabajador AS t,  trabajador_por_proyecto AS tp, cargo_trabajador as ct, tipo_trabajador as tt
		WHERE tp.idtrabajador = t.idtrabajador AND tp.idtrabajador_por_proyecto = '$idtrabajador' AND ct.idcargo_trabajador= tp.idcargo_trabajador 
    AND ct.idtipo_trabjador=tt.idtipo_trabajador";
    $trabajador = ejecutarConsultaSimpleFila($sql);
    if ($trabajador['status'] == false) { return  $trabajador;}

    $sql2 = "SELECT cbt.idcuenta_banco_trabajador, cbt.idtrabajador, cbt.idbancos, cbt.cuenta_bancaria, cbt.cci, cbt.banco_seleccionado, b.nombre as banco
    FROM cuenta_banco_trabajador as cbt, bancos as b
    WHERE cbt.idbancos = b.idbancos AND cbt.idtrabajador='$idtrabajador' ORDER BY cbt.idcuenta_banco_trabajador ASC ;";
    $bancos = ejecutarConsultaArray($sql2);
    if ($bancos['status'] == false) { return  $bancos;}
    return $retorno=['status'=>true, 'message'=>'todo oka ps', 'data'=>['trabajador'=>$trabajador['data'], 'bancos'=>$bancos['data'],]];
  }

  //Implementar un método para listar los registros
  public function tbla_principal($nube_idproyecto, $estado) {
    
    $data = [];

    $sql = "SELECT t.idtrabajador, t.nombres, t.tipo_documento, t.numero_documento,  t.imagen_perfil as imagen, tp.idcargo_trabajador , 
    tp.desempenio, tp.sueldo_mensual, tp.sueldo_diario, tp.sueldo_hora, tp.fecha_inicio, tp.fecha_fin, tp.estado, tp.idtrabajador_por_proyecto, 
		ct.nombre as cargo, ct.idtipo_trabjador, tt.nombre as nombre_tipo
		FROM trabajador_por_proyecto as tp, trabajador as t, proyecto AS p, cargo_trabajador as ct, tipo_trabajador as tt
		WHERE tp.idproyecto = p.idproyecto AND tp.idproyecto = '$nube_idproyecto'   AND tp.idtrabajador = t.idtrabajador AND 
		ct.idcargo_trabajador=tp.idcargo_trabajador AND tt.idtipo_trabajador=ct.idtipo_trabjador AND tp.estado='$estado' AND tp.estado_delete='1' ORDER BY t.nombres ASC";
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
        'idcargo_trabajador' => $value['idcargo_trabajador'],          
        'desempenio'      => $value['desempenio'], 
        'sueldo_mensual'  => $value['sueldo_mensual'],
        'sueldo_diario'   =>$value['sueldo_diario'],
        'sueldo_hora'     =>$value['sueldo_hora'],
        'fecha_inicio'    =>$value['fecha_inicio'],
        'fecha_fin'       =>$value['fecha_fin'],
        'estado'          =>$value['estado'],
        'idtrabajador_por_proyecto' =>$value['idtrabajador_por_proyecto'],
        'cargo'           =>$value['cargo'],
        'idtipo_trabjador'=>$value['idtipo_trabjador'],
        'nombre_tipo'     =>$value['nombre_tipo'],

        'banco'           => (empty($bancos['data']) ? "": $bancos['data']['banco']), 
        'cuenta_bancaria' => (empty($bancos['data']) ? "" : $bancos['data']['cuenta_bancaria']), 
        'cci'             => (empty($bancos['data']) ? "" : $bancos['data']['cci']), 
      );
    }
    return $retorno=['status'=>true, 'message'=>'todo oka ps', 'data'=>$data];
  }

  //Seleccionar Trabajador Select2
  public function m_datos_trabajador($idtrabajador) {
    $sql = "SELECT t.numero_documento, t.idtipo_trabajador, t.idocupacion, o.nombre_ocupacion
		FROM trabajador  as t, ocupacion as o
		WHERE t.idtrabajador='$idtrabajador' AND t.estado='1' AND t.idocupacion=o.idocupacion";

    return ejecutarConsultaSimpleFila($sql);
  }

}

?>
