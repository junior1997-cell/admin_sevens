<?php
  //Incluímos inicialmente la conexión a la base de datos
  require "../config/Conexion_v2.php";

  class AllTrabajador
  {
    //Implementamos nuestro constructor
    public function __construct()
    {
    }

    //Implementamos un método para insertar registros
    public function insertar( $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $nacimiento, $edad,  $email, $banco_seleccionado, $banco, $cta_bancaria,  $cci,  $titular_cuenta, $tipo, $desempenio, $ocupacion, $ruc, $imagen1, $imagen2, $imagen3, $cv_documentado, $cv_nodocumentado) {
      $sw = Array();
      $sql_0 = "SELECT t.nombres, t.tipo_documento, t.numero_documento, tip.nombre as tipo, t.estado, t.estado_delete, t.fecha_nacimiento, t.edad
      FROM trabajador as t, tipo_trabajador as tip
      WHERE t.idtipo_trabajador = tip.idtipo_trabajador and  numero_documento = '$num_documento';";
      $existe = ejecutarConsultaArray($sql_0); if ($existe['status'] == false) { return $existe;}
      
      if ( empty($existe['data']) ) {

        $sql_2="INSERT INTO trabajador ( idtipo_trabajador, idocupacion, nombres, tipo_documento, numero_documento, fecha_nacimiento, edad, titular_cuenta, direccion, telefono, email,  ruc, imagen_perfil, imagen_dni_anverso, imagen_dni_reverso,  cv_documentado, cv_no_documentado,user_created)
        VALUES ( '$tipo', '$ocupacion', '$nombre', '$tipo_documento', '$num_documento', '$nacimiento', '$edad', '$titular_cuenta', '$direccion', '$telefono', '$email', '$ruc', '$imagen1', '$imagen2', '$imagen3', '$cv_documentado', '$cv_nodocumentado','" . $_SESSION['idusuario'] . "')";
        $new_trabajador = ejecutarConsulta_retornarID($sql_2);  if ($new_trabajador['status'] == false) { return $new_trabajador;}
        
        foreach ($desempenio as $key => $value) {
          $sql_3 = "INSERT INTO detalle_desempenio( idtrabajador, iddesempenio) VALUES ('".$new_trabajador['data']."','$value')";
          $insert_desempenio = ejecutarConsulta($sql_3);  if ($insert_desempenio['status'] == false) { return  $insert_desempenio;}
        }
        
        //add registro en nuestra bitacora
        $sql_4 = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('trabajador','".$new_trabajador['data']."','Registro Nuevo Trabajador','" . $_SESSION['idusuario'] . "')";
        $bitacora = ejecutarConsulta($sql_4); if ( $bitacora['status'] == false) {return $bitacora; }  
        
        $num_elementos = 0;
        while ($num_elementos < count($banco)) {
          $id = $new_trabajador['data'];
          $sql_detalle = "";
          if ($num_elementos == $banco_seleccionado) {
            $sql_detalle = "INSERT INTO cuenta_banco_trabajador( idtrabajador, idbancos, cuenta_bancaria, cci, banco_seleccionado,user_created) VALUES ('$id','$banco[$num_elementos]', '$cta_bancaria[$num_elementos]',  '$cci[$num_elementos]', '1','" . $_SESSION['idusuario'] . "')";
          } else {
            $sql_detalle = "INSERT INTO cuenta_banco_trabajador( idtrabajador, idbancos, cuenta_bancaria, cci, banco_seleccionado,user_created) VALUES ('$id','$banco[$num_elementos]', '$cta_bancaria[$num_elementos]',  '$cci[$num_elementos]', '0','" . $_SESSION['idusuario'] . "')";
          }          
          
          $banco_new =  ejecutarConsulta_retornarID($sql_detalle); if ($banco_new['status'] == false) { return  $banco_new;}

          //add registro en nuestra bitacora
          $sql_5 = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('cuenta_banco_trabajador','".$banco_new['data']."','Registrando cuenta bancaria trabajador','" . $_SESSION['idusuario'] . "')";
          $bitacora = ejecutarConsulta($sql_5); if ( $bitacora['status'] == false) {return $bitacora; }  

          $num_elementos = $num_elementos + 1;
        }

        $sw = array( 'status' => true, 'message' => 'noduplicado', 'data' => $new_trabajador['data'], 'id_tabla' =>$new_trabajador['id_tabla'] );

      } else {
        $info_repetida = ''; 

        foreach ($existe['data'] as $key => $value) {
          $info_repetida .= '<li class="text-left font-size-13px">
            <span class="font-size-15px text-danger"><b>Nombre: </b>'.$value['nombres'].'</span><br>
            <b>'.$value['tipo_documento'].': </b>'.$value['numero_documento'].'<br>
            <b>Tipo: </b>'.$value['tipo'].'<br>
            '.$value['edad'].': <b>'.$value['edad'].'</b><br>
            <b>Papelera: </b>'.( $value['estado']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO') .' <b>|</b>
            <b>Eliminado: </b>'. ($value['estado_delete']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO').'<br>
            <hr class="m-t-2px m-b-2px">
          </li>'; 
        }
        $sw = array( 'status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ul>'.$info_repetida.'</ul>', 'id_tabla' => '' );
      }      
      
      return $sw;        
    }

    //Implementamos un método para editar registros $cci, $tipo, $ocupacion, $ruc, $cv_documentado, $cv_nodocumentado
    public function editar($idtrabajador, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $nacimiento, $edad,  $email, $banco_seleccionado, $banco, $cta_bancaria,  $cci, $titular_cuenta, $tipo, $desempenio, $ocupacion, $ruc, $imagen1, $imagen2, $imagen3, $cv_documentado, $cv_nodocumentado) {
      $sql="UPDATE trabajador SET idocupacion = '$ocupacion', nombres='$nombre', tipo_documento='$tipo_documento', numero_documento='$num_documento', fecha_nacimiento='$nacimiento', edad='$edad',  titular_cuenta='$titular_cuenta',direccion='$direccion', 
      telefono='$telefono', email='$email', imagen_perfil ='$imagen1', imagen_dni_anverso ='$imagen2', imagen_dni_reverso ='$imagen3',
      idtipo_trabajador ='$tipo',  ruc='$ruc', cv_documentado='$cv_documentado', 
      cv_no_documentado='$cv_nodocumentado', user_updated= '" . $_SESSION['idusuario'] . "'
      WHERE idtrabajador='$idtrabajador'";	      
      $trabajdor = ejecutarConsulta($sql);  if ($trabajdor['status'] == false) { return  $trabajdor;}

      $sql ="DELETE FROM detalle_desempenio WHERE idtrabajador= '$idtrabajador'";
      $delete_desempenio = ejecutarConsulta($sql);  if ($delete_desempenio['status'] == false) { return  $delete_desempenio;}

      foreach ($desempenio as $key => $value) {
        $sql = "INSERT INTO detalle_desempenio( idtrabajador, iddesempenio) VALUES ('$idtrabajador','$value')";
        $insert_desempenio = ejecutarConsulta($sql);  if ($insert_desempenio['status'] == false) { return  $insert_desempenio;}
      }

      #eliminar
      $sql ="DELETE FROM cuenta_banco_trabajador WHERE idtrabajador= '$idtrabajador'";      

      //add registro en nuestra bitacora
      $sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('trabajador','".$idtrabajador."','Editamos el registro Trabajador','" . $_SESSION['idusuario'] . "')";
      $bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
      
      $sql2 = "DELETE FROM cuenta_banco_trabajador WHERE idtrabajador='$idtrabajador';";
      $delete = ejecutarConsulta($sql2);  if ($delete['status'] == false) { return  $delete;}

      $num_elementos = 0; $compra_new = [];
      while ($num_elementos < count($banco)) {         
        $sql_detalle = "";
        if ($num_elementos == $banco_seleccionado) {
          $sql_detalle = "INSERT INTO cuenta_banco_trabajador( idtrabajador, idbancos, cuenta_bancaria, cci, banco_seleccionado,user_created) VALUES ('$idtrabajador','$banco[$num_elementos]', '$cta_bancaria[$num_elementos]',  '$cci[$num_elementos]', '1','" . $_SESSION['idusuario'] . "')";
        } else {
          $sql_detalle = "INSERT INTO cuenta_banco_trabajador( idtrabajador, idbancos, cuenta_bancaria, cci, banco_seleccionado,user_created) VALUES ('$idtrabajador','$banco[$num_elementos]', '$cta_bancaria[$num_elementos]',  '$cci[$num_elementos]', '0','" . $_SESSION['idusuario'] . "')";
        }          
        
        $banco_new =  ejecutarConsulta_retornarID($sql_detalle);
        if ($banco_new['status'] == false) { return  $banco_new;}

        //add registro en nuestra bitacora
        $sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('cuenta_banco_trabajador','".$banco_new['data']."','Registrando cuenta bancaria trabajador en la sección editar','" . $_SESSION['idusuario'] . "')";
        $bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  

        $num_elementos = $num_elementos + 1;
      }

      $sw = array( 'status' => true, 'message' => 'todo oka', 'data' => $idtrabajador, 'id_tabla' => $idtrabajador );

      return $sw;      
    }

    //Implementamos un método para desactivar 
    public function desactivar($idtrabajador, $descripcion) {
      $sql="UPDATE trabajador SET estado='0', descripcion_expulsion = '$descripcion',user_trash= '" . $_SESSION['idusuario'] . "' WHERE idtrabajador='$idtrabajador'";
      $desactivar =  ejecutarConsulta($sql);

      if ( $desactivar['status'] == false) {return $desactivar; }  

      //add registro en nuestra bitacora
      $sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('trabajador','.$idtrabajador.','Desativar el registro Trabajador','" . $_SESSION['idusuario'] . "')";
      $bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  

      return $desactivar;
    }

    //Implementamos un método para desactivar 
    public function desactivar_1($idtrabajador) {
      $sql="UPDATE trabajador SET estado='0',user_trash= '" . $_SESSION['idusuario'] . "' WHERE idtrabajador='$idtrabajador'";
      return ejecutarConsulta($sql);
    }

    //Implementamos un método para activar 
    public function activar($idtrabajador) {
      $sql="UPDATE trabajador SET estado='1',user_updated= '" . $_SESSION['idusuario'] . "' WHERE idtrabajador='$idtrabajador'";
      $activar =  ejecutarConsulta($sql);
      
      if ( $activar['status'] == false) {return $activar; }  

      //add registro en nuestra bitacora
      $sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('trabajador','.$idtrabajador.','Habilitar el registro Trabajador','" . $_SESSION['idusuario'] . "')";
      $bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  

      return $activar;
    }

    //Implementamos un método para activar 
    public function eliminar($idtrabajador) {
      $sql="UPDATE trabajador SET estado_delete='0',user_delete= '" . $_SESSION['idusuario'] . "' WHERE idtrabajador='$idtrabajador'";
      $eliminar =  ejecutarConsulta($sql);
      
      if ( $eliminar['status'] == false) {return $eliminar; }  

      //add registro en nuestra bitacora
      $sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('trabajador','.$idtrabajador.','Eliminar registro Trabajador','" . $_SESSION['idusuario'] . "')";
      $bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  

      return $eliminar;
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idtrabajador) {
      $array_desempenio = [];
      $sql="SELECT * FROM trabajador WHERE idtrabajador='$idtrabajador'";
      $trabajador = ejecutarConsultaSimpleFila($sql);
      if ($trabajador['status'] == false) { return  $trabajador;}

      $sql2 = "SELECT cbt.idcuenta_banco_trabajador, cbt.idtrabajador, cbt.idbancos, cbt.cuenta_bancaria, cbt.cci, cbt.banco_seleccionado, b.nombre as banco
      FROM cuenta_banco_trabajador as cbt, bancos as b
      WHERE cbt.idbancos = b.idbancos AND cbt.idtrabajador='$idtrabajador' ORDER BY cbt.idcuenta_banco_trabajador ASC ;";
      $bancos = ejecutarConsultaArray($sql2);
      if ($bancos['status'] == false) { return  $bancos;}

      $sql3 = "SELECT  doc.iddesempenio 
      FROM detalle_desempenio as doc, trabajador as t, desempenio as o  
      WHERE doc.idtrabajador = t.idtrabajador AND doc.iddesempenio = o.iddesempenio AND t.idtrabajador = '$idtrabajador';";
      $detalle_desempenio = ejecutarConsultaArray($sql3);
      if ($detalle_desempenio['status'] == false) { return  $detalle_desempenio;}
      foreach ($detalle_desempenio['data'] as $key => $value) {
        array_push($array_desempenio, $value['iddesempenio'] ); 
      }

      return $retorno=['status'=>true, 'message'=>'todo oka ps', 'data'=>['trabajador'=>$trabajador['data'], 'bancos'=>$bancos['data'], 'detalle_desempenio'=>$array_desempenio]];
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function verdatos($idtrabajador) {
      $sql="SELECT t.nombres, t.tipo_documento, t.numero_documento, t.fecha_nacimiento, t.edad,
      t.titular_cuenta, t.direccion, t.telefono, t.email, t.imagen_perfil as imagen_perfil, t.imagen_dni_anverso, t.cv_documentado, 
      t.cv_no_documentado, t.imagen_dni_reverso as imagen_dni_reverso, tt.nombre as nombre_tipo_trabajador, o.nombre_ocupacion 
      FROM trabajador as t, tipo_trabajador as tt, ocupacion as o
      WHERE t.idtipo_trabajador = tt.idtipo_trabajador and t.idocupacion = o.idocupacion and t.idtrabajador='$idtrabajador' ";
      $trabajador = ejecutarConsultaSimpleFila($sql); if ($trabajador['status'] == false) { return  $trabajador;}

      $sql2 = "SELECT cbt.idcuenta_banco_trabajador, cbt.idtrabajador, cbt.idbancos, cbt.cuenta_bancaria, cbt.cci, cbt.banco_seleccionado, b.nombre as banco
      FROM cuenta_banco_trabajador as cbt, bancos as b
      WHERE cbt.idbancos = b.idbancos AND cbt.idtrabajador='$idtrabajador' ORDER BY cbt.idcuenta_banco_trabajador ASC ;";
      $bancos = ejecutarConsultaArray($sql2);  if ($bancos['status'] == false) { return  $bancos;}

      $sql3 = "SELECT doc.iddetalle_desempenio, doc.idtrabajador, doc.iddesempenio, o.nombre_desempenio 
      FROM detalle_desempenio as doc, trabajador as t, desempenio as o  
      WHERE doc.idtrabajador = t.idtrabajador AND doc.iddesempenio = o.iddesempenio AND t.idtrabajador = '$idtrabajador';";
      $detalle_desempenio = ejecutarConsultaArray($sql3);    if ($detalle_desempenio['status'] == false) { return  $detalle_desempenio;}

      $html_desempenio = "";
      foreach ($detalle_desempenio['data'] as $key => $value2) {
        $html_desempenio .=  '<li >'.$value2['nombre_desempenio'].'</li>';
      }

      return $retorno=['status'=>true, 'message'=>'todo oka ps', 
        'data'=>[
          'trabajador'=>$trabajador['data'], 
          'bancos'=>$bancos['data'], 
          'detalle_desempenio'=>$detalle_desempenio['data'], 
          'html_desempenio'=> '<ol class="pl-3">'. $html_desempenio . '</ol>'
        ]
      ];
    }

    //Implementar un método para listar los registros
    public function tbla_principal($estado) {
      $data = Array();
      $sql="SELECT t.idtrabajador,  t.nombres, t.tipo_documento, t.numero_documento, t.fecha_nacimiento, t.edad, t.telefono, t.imagen_perfil,  
      t.estado,  tt.nombre AS nombre_tipo, t.descripcion_expulsion, o.nombre_ocupacion
      FROM trabajador AS t, tipo_trabajador as tt, ocupacion as o
      WHERE  tt.idtipo_trabajador= t.idtipo_trabajador AND t.idocupacion = o.idocupacion AND  t.estado = '$estado' AND t.estado_delete = '1' ORDER BY  t.nombres ASC ;";
      $trabajdor = ejecutarConsultaArray($sql);
      if ($trabajdor['status'] == false) { return  $trabajdor;}

      foreach ($trabajdor['data'] as $key => $value) {
        $id = $value['idtrabajador'];
        $sql2 = "SELECT cbt.idcuenta_banco_trabajador, cbt.idtrabajador, cbt.idbancos, cbt.cuenta_bancaria, cbt.cci, cbt.banco_seleccionado, b.nombre as banco
        FROM cuenta_banco_trabajador as cbt, bancos as b
        WHERE cbt.idbancos = b.idbancos AND cbt.banco_seleccionado ='1' AND cbt.idtrabajador='$id' ;";
        $bancos = ejecutarConsultaSimpleFila($sql2);
        if ($bancos['status'] == false) { return  $bancos;}

        $sql3 = "SELECT doc.iddetalle_desempenio, doc.idtrabajador, doc.iddesempenio, o.nombre_desempenio 
        FROM detalle_desempenio as doc, trabajador as t, desempenio as o  
        WHERE doc.idtrabajador = t.idtrabajador AND doc.iddesempenio = o.iddesempenio AND t.idtrabajador = '$id';";
        $detalle_desempenio = ejecutarConsultaArray($sql3); if ($detalle_desempenio['status'] == false) { return  $detalle_desempenio;}

        $html_desempenio = "";
        foreach ($detalle_desempenio['data'] as $key => $value2) {
          $html_desempenio .=  '<li >'.$value2['nombre_desempenio'].'. </li>';
        }

        $data[] = array(
          'idtrabajador'    => $value['idtrabajador'],  
          'trabajador'      => $value['nombres'], 
          'tipo_documento'  => $value['tipo_documento'], 
          'numero_documento'=> $value['numero_documento'], 
          'fecha_nacimiento'=> $value['fecha_nacimiento'], 
          'edad'            => $value['edad'],          
          'telefono'        => $value['telefono'], 
          'imagen_perfil'   => $value['imagen_perfil'],  
          'estado'          => $value['estado'],          
          'nombre_tipo'     => $value['nombre_tipo'],
          'nombre_ocupacion'=> $value['nombre_ocupacion'], 
          'html_desempenio' => '<ol class="pl-3">'. $html_desempenio . '</ol>',
          'descripcion_expulsion' =>$value['descripcion_expulsion'],

          'banco'           => (empty($bancos['data']) ? "": $bancos['data']['banco']), 
          'cuenta_bancaria' => (empty($bancos['data']) ? "" : $bancos['data']['cuenta_bancaria']), 
          'cci'             => (empty($bancos['data']) ? "" : $bancos['data']['cci']), 
        );
      }
      return $retorno=['status'=>true, 'message'=>'todo oka ps', 'data'=>$data];
    }

    // obtebnemos los DOCS para eliminar
    public function obtenerImg($idtrabajador) {

      $sql = "SELECT imagen_perfil, imagen_dni_anverso, imagen_dni_reverso FROM trabajador WHERE idtrabajador='$idtrabajador'";

      return ejecutarConsultaSimpleFila($sql);
    }
    
    // obtebnemos los DOCS para eliminar
    public function obtenercv($idtrabajador) {

      $sql = "SELECT cv_documentado, cv_no_documentado FROM trabajador WHERE idtrabajador='$idtrabajador'";

      return ejecutarConsultaSimpleFila($sql);
    }

    public function select2_banco() {
      $sql="SELECT idbancos as id, nombre, alias FROM bancos WHERE estado='1' AND idbancos > 1 ORDER BY nombre ASC;";
      return ejecutarConsulta($sql);		
    }

    public function formato_banco($idbanco){
      $sql="SELECT nombre, formato_cta, formato_cci, formato_detracciones FROM bancos WHERE estado='1' AND idbancos = '$idbanco';";
      return ejecutarConsultaSimpleFila($sql);		
    }

    /* =========================== S E C C I O N   R E C U P E R A R   B A N C O S =========================== */

    public function recuperar_banco(){
      $sql="SELECT idtrabajador, idbancos, cuenta_bancaria_format, cci_format FROM trabajador;";
      $bancos_old = ejecutarConsultaArray($sql);
      if ($bancos_old['status'] == false) { return $bancos_old;}	
      
      $bancos_new = [];
      foreach ($bancos_old['data'] as $key => $value) {
        $id = $value['idtrabajador']; 
        $idbancos = $value['idbancos']; 
        $cuenta_bancaria_format = $value['cuenta_bancaria_format']; 
        $cci_format = $value['cci_format'];

        $sql2="INSERT INTO cuenta_banco_trabajador( idtrabajador, idbancos, cuenta_bancaria, cci, banco_seleccionado) 
        VALUES ('$id','$idbancos','$cuenta_bancaria_format','$cci_format', '1');";
        $bancos_new = ejecutarConsulta($sql2);
        if ($bancos_new['status'] == false) { return $bancos_new;}
      } 
      
      return $bancos_new;
    }

  }

?>
