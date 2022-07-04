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
    public function insertar( $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $nacimiento, $edad,  $email, $banco_seleccionado, $banco, $cta_bancaria,  $cci,  $titular_cuenta, $tipo, $ocupacion, $ruc, $imagen1, $imagen2, $imagen3, $cv_documentado, $cv_nodocumentado) {
      $sw = Array();
      $sql_0 = "SELECT * FROM trabajador WHERE numero_documento = '$num_documento' AND nombres = '$nombre'";
      $existe = ejecutarConsultaArray($sql_0);
      if ($existe['status'] == false) { return $existe;}
      
      if ( empty($existe['data']) ) {
        $sql="INSERT INTO trabajador ( nombres, tipo_documento, numero_documento, fecha_nacimiento, edad, titular_cuenta, direccion, telefono, email, imagen_perfil, imagen_dni_anverso, imagen_dni_reverso, idtipo_trabajador , idocupacion, ruc, cv_documentado, cv_no_documentado)
        VALUES ( '$nombre', '$tipo_documento', '$num_documento', '$nacimiento', '$edad', '$titular_cuenta', '$direccion', '$telefono', '$email', '$imagen1', '$imagen2', '$imagen3', '$tipo', '$ocupacion', '$ruc', '$cv_documentado', '$cv_nodocumentado')";
        $new_trabajador = ejecutarConsulta_retornarID($sql);
        if ($new_trabajador['status'] == false) { return $new_trabajador;}
        
        $num_elementos = 0;
        while ($num_elementos < count($banco)) {
          $id = $new_trabajador['data'];
          $sql_detalle = "";
          if ($num_elementos == $banco_seleccionado) {
            $sql_detalle = "INSERT INTO cuenta_banco_trabajador( idtrabajador, idbancos, cuenta_bancaria, cci, banco_seleccionado) VALUES ('$id','$banco[$num_elementos]', '$cta_bancaria[$num_elementos]',  '$cci[$num_elementos]', '1')";
          } else {
            $sql_detalle = "INSERT INTO cuenta_banco_trabajador( idtrabajador, idbancos, cuenta_bancaria, cci, banco_seleccionado) VALUES ('$id','$banco[$num_elementos]', '$cta_bancaria[$num_elementos]',  '$cci[$num_elementos]', '0')";
          }          
          
          $banco_new =  ejecutarConsulta($sql_detalle);
          if ($banco_new['status'] == false) { return  $banco_new;}

          $num_elementos = $num_elementos + 1;
        }

        $sw = array( 'status' => true, 'message' => 'noduplicado', 'data' => $new_trabajador['data'], 'id_tabla' =>$new_trabajador['id_tabla'] );

      } else {
        $info_repetida = ''; 

        foreach ($existe['data'] as $key => $value) {
          $info_repetida .= '<li class="text-left font-size-13px">
            <bNombre: </b>'.$value['nombres'].'<br>
            <b>'.$value['tipo_documento'].': </b>'.$value['numero_documento'].'<br>
            <b>Papelera: </b>'.( $value['estado']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO') .'<br>
            <b>Eliminado: </b>'. ($value['estado_delete']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO').'<br>
            <hr class="m-t-2px m-b-2px">
          </li>'; 
        }
        $sw = array( 'status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ul>'.$info_repetida.'</ul>', 'id_tabla' => '' );
      }      
      
      return $sw;        
    }

    //Implementamos un método para editar registros $cci, $tipo, $ocupacion, $ruc, $cv_documentado, $cv_nodocumentado
    public function editar($idtrabajador, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $nacimiento, $edad,  $email, $banco_seleccionado, $banco, $cta_bancaria,  $cci, $titular_cuenta, $tipo, $ocupacion, $ruc, $imagen1, $imagen2, $imagen3, $cv_documentado, $cv_nodocumentado) {
      $sql="UPDATE trabajador SET nombres='$nombre', tipo_documento='$tipo_documento', numero_documento='$num_documento', fecha_nacimiento='$nacimiento', edad='$edad',  titular_cuenta='$titular_cuenta',direccion='$direccion', 
      telefono='$telefono', email='$email', imagen_perfil ='$imagen1', imagen_dni_anverso ='$imagen2', imagen_dni_reverso ='$imagen3',
      idtipo_trabajador ='$tipo', idocupacion='$ocupacion', ruc='$ruc', cv_documentado='$cv_documentado', cv_no_documentado='$cv_nodocumentado'
      WHERE idtrabajador='$idtrabajador'";	      
      $trabajdor = ejecutarConsulta($sql);
      if ($trabajdor['status'] == false) { return  $trabajdor;}
      
      $sql2 = "DELETE FROM cuenta_banco_trabajador WHERE idtrabajador='$idtrabajador';";
      $delete = ejecutarConsulta($sql2);
      if ($delete['status'] == false) { return  $delete;}

      $num_elementos = 0; $compra_new = [];
      while ($num_elementos < count($banco)) {         
        $sql_detalle = "";
        if ($num_elementos == $banco_seleccionado) {
          $sql_detalle = "INSERT INTO cuenta_banco_trabajador( idtrabajador, idbancos, cuenta_bancaria, cci, banco_seleccionado) VALUES ('$idtrabajador','$banco[$num_elementos]', '$cta_bancaria[$num_elementos]',  '$cci[$num_elementos]', '1')";
        } else {
          $sql_detalle = "INSERT INTO cuenta_banco_trabajador( idtrabajador, idbancos, cuenta_bancaria, cci, banco_seleccionado) VALUES ('$idtrabajador','$banco[$num_elementos]', '$cta_bancaria[$num_elementos]',  '$cci[$num_elementos]', '0')";
        }          
        
        $banco_new =  ejecutarConsulta($sql_detalle);
        if ($banco_new['status'] == false) { return  $banco_new;}

        $num_elementos = $num_elementos + 1;
      }

      return $banco_new;      
    }

    //Implementamos un método para desactivar categorías
    public function desactivar($idtrabajador, $descripcion) {
      $sql="UPDATE trabajador SET estado='0', descripcion_expulsion = '$descripcion' WHERE idtrabajador='$idtrabajador'";
      return ejecutarConsulta($sql);
    }

    //Implementamos un método para desactivar categorías
    public function desactivar_1($idtrabajador) {
      $sql="UPDATE trabajador SET estado='0' WHERE idtrabajador='$idtrabajador'";
      return ejecutarConsulta($sql);
    }

    //Implementamos un método para activar categorías
    public function activar($idtrabajador) {
      $sql="UPDATE trabajador SET estado='1' WHERE idtrabajador='$idtrabajador'";
      return ejecutarConsulta($sql);
    }

    //Implementamos un método para activar categorías
    public function eliminar($idtrabajador) {
      $sql="UPDATE trabajador SET estado_delete='0' WHERE idtrabajador='$idtrabajador'";
      return ejecutarConsulta($sql);
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idtrabajador) {
      $sql="SELECT * FROM trabajador WHERE idtrabajador='$idtrabajador'";
      $trabajador = ejecutarConsultaSimpleFila($sql);
      if ($trabajador['status'] == false) { return  $trabajador;}

      $sql2 = "SELECT cbt.idcuenta_banco_trabajador, cbt.idtrabajador, cbt.idbancos, cbt.cuenta_bancaria, cbt.cci, cbt.banco_seleccionado, b.nombre as banco
      FROM cuenta_banco_trabajador as cbt, bancos as b
      WHERE cbt.idbancos = b.idbancos AND cbt.idtrabajador='$idtrabajador' ORDER BY cbt.idcuenta_banco_trabajador ASC ;";
      $bancos = ejecutarConsultaArray($sql2);
      if ($bancos['status'] == false) { return  $bancos;}

      return $retorno=['status'=>true, 'message'=>'todo oka ps', 'data'=>['trabajador'=>$trabajador['data'], 'bancos'=>$bancos['data'],]];
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function verdatos($idtrabajador) {
      $sql="SELECT t.nombres, t.tipo_documento, t.numero_documento, t.fecha_nacimiento, 
      t.titular_cuenta, t.direccion, t.telefono, t.email, t.imagen_perfil as imagen_perfil, t.imagen_dni_anverso, t.cv_documentado, 
      t.cv_no_documentado, t.imagen_dni_reverso as imagen_dni_reverso
      FROM trabajador as t WHERE t.idtrabajador='$idtrabajador' ";
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
    public function tbla_principal($estado) {
      $data = Array();
      $sql="SELECT t.idtrabajador,  t.nombres, t.tipo_documento, t.numero_documento, t.fecha_nacimiento, t.edad, t.telefono, t.imagen_perfil,  
      t.estado,  tt.nombre AS nombre_tipo, o.nombre_ocupacion, t.descripcion_expulsion
      FROM trabajador AS t, tipo_trabajador as tt, ocupacion as o
      WHERE t.idocupacion =o.idocupacion  AND tt.idtipo_trabajador= t.idtipo_trabajador AND  t.estado = '$estado' AND t.estado_delete = '1' ORDER BY  t.nombres ASC ;";
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
          'fecha_nacimiento'=> $value['fecha_nacimiento'], 
          'edad'            => $value['edad'],          
          'telefono'        => $value['telefono'], 
          'imagen_perfil'   => $value['imagen_perfil'],  
          'estado'          => $value['estado'],          
          'nombre_tipo'     => $value['nombre_tipo'], 
          'nombre_ocupacion'=> $value['nombre_ocupacion'],
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
