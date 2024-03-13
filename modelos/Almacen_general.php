<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Almacen_general
{
  //Implementamos nuestro variable global
  public $id_usr_sesion;

  //Implementamos nuestro constructor
  public function __construct($id_usr_sesion = 0)
  {
    $this->id_usr_sesion = $id_usr_sesion;
  }

  //Implementamos un método para insertar registros
  public function insertar($nombre, $descripcion)
  {

    $sql = "SELECT nombre_almacen, descripcion, estado, estado_delete FROM almacen_general WHERE nombre_almacen = '$nombre';";
    $buscando = ejecutarConsultaArray($sql);
    if ($buscando['status'] == false) {
      return $buscando;
    }


    if (empty($buscando['data'])) {
      $sql = "INSERT INTO almacen_general(nombre_almacen, descripcion, user_created ) VALUES ('$nombre','$descripcion', '$this->id_usr_sesion')";
      $insertar =  ejecutarConsulta_retornarID($sql);
      if ($insertar['status'] == false) {
        return $insertar;
      }

      //add registro en nuestra bitacora
      $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('nombre_almacen','" . $insertar['data'] . "','Nuevo registrado','$this->id_usr_sesion')";
      $bitacora = ejecutarConsulta($sql_bit);
      if ($bitacora['status'] == false) {
        return $bitacora;
      }

      return $insertar;
    } else {
      $info_repetida = '';

      foreach ($buscando['data'] as $key => $value) {
        $info_repetida .= '<li class="text-left font-size-13px">
          <b>Nombre: </b>' . $value['nombre_almacen'] . '<br>
          <b>Descripcion: </b>' . $value['descripcion'] . '<br>            
          <b>Papelera: </b>' . ($value['estado'] == 0 ? '<i class="fas fa-check text-success"></i> SI' : '<i class="fas fa-times text-danger"></i> NO') . '<br>
          <b>Eliminado: </b>' . ($value['estado_delete'] == 0 ? '<i class="fas fa-check text-success"></i> SI' : '<i class="fas fa-times text-danger"></i> NO') . '<br>
          <hr class="m-t-2px m-b-2px">
        </li>';
      }
      $sw = array('status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ul>' . $info_repetida . '</ul>', 'id_tabla' => '');
      return $sw;
    }
  }

  //Implementamos un método para editar registros
  public function editar($idalmacen_general, $nombre, $descripcion)
  {

    $sql = "UPDATE almacen_general SET nombre_almacen='$nombre', descripcion='$descripcion', user_updated = '$this->id_usr_sesion'
		WHERE idalmacen_general = '$idalmacen_general';";
    $editar =  ejecutarConsulta($sql);
    if ($editar['status'] == false) {
      return $editar;
    }

    //add registro en nuestra bitacora
    $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('almacen_general', '$idalmacen_general','Registro editado','$this->id_usr_sesion')";
    $bitacora = ejecutarConsulta($sql_bit);
    if ($bitacora['status'] == false) {
      return $bitacora;
    }

    return $editar;
  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idalmacen_general)
  {

    $sql = "UPDATE almacen_general SET estado='0',user_trash= '$this->id_usr_sesion'  WHERE idalmacen_general ='$idalmacen_general'";
    $desactivar = ejecutarConsulta($sql);
    if ($desactivar['status'] == false) {
      return $desactivar;
    }

    //add registro en nuestra bitacora
    $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('almacen_general','$idalmacen_general','Registro desactivado','$this->id_usr_sesion')";
    $bitacora = ejecutarConsulta($sql_bit);
    if ($bitacora['status'] == false) {
      return $bitacora;
    }

    return $desactivar;
  }

  //Implementamos un método para activar categorías
  public function activar($idalmacen_general)
  {
    $sql = "UPDATE almacen_general SET estado='1' WHERE idalmacen_general ='$idalmacen_general'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para eliminar
  public function eliminar($idalmacen_general)
  {
    $sql = "UPDATE almacen_general SET estado_delete='0',user_delete= '$this->id_usr_sesion' WHERE idalmacen_general ='$idalmacen_general'";
    $eliminar =  ejecutarConsulta($sql);
    if ($eliminar['status'] == false) {
      return $eliminar;
    }

    //add registro en nuestra bitacora
    $sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('almacen_general','$idalmacen_general','Registro Eliminado','$this->id_usr_sesion')";
    $bitacora = ejecutarConsulta($sql);
    if ($bitacora['status'] == false) {
      return $bitacora;
    }

    return $eliminar;
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idalmacen_general)
  {
    $sql = "SELECT idalmacen_general, nombre_almacen, descripcion
    FROM almacen_general WHERE idalmacen_general ='$idalmacen_general'; ";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para listar los registros
  public function tabla_principal()
  {
    $sql = "SELECT idalmacen_general, nombre_almacen, descripcion, estado, estado_delete 
    FROM almacen_general WHERE estado='1' AND estado_delete='1' ORDER BY nombre_almacen ASC;";
    return ejecutarConsulta($sql);
  }

  //Implementar un método para listar los registros
  public function tabla_detalle($id_proyecto, $id_almacen)
  {
    $sql = "SELECT apg.idalmacen_producto_guardado, apg.idalmacen_general, apg.idalmacen_resumen, apg.fecha_envio, apg.cantidad, prd.nombre as producto, 
    pry.nombre_codigo as proyecto
    FROM almacen_producto_guardado as apg, almacen_resumen as ar, producto as prd, proyecto as pry
    WHERE apg.idalmacen_resumen = ar.idalmacen_resumen AND ar.idproducto = prd.idproducto AND ar.idproyecto = pry.idproyecto 
    AND apg.idalmacen_general='$id_almacen' AND apg.estado = '1' AND apg.estado_delete = '1' AND ar.estado = '1' AND ar.estado_delete = '1'
    AND apg.cantidad>'0'
    ORDER BY pry.nombre_codigo ASC;";
    return ejecutarConsulta($sql);
  }

  //Seleccionar Trabajador Select2
  public function lista_de_categorias()
  {
    $sql = "SELECT idalmacen_general as idcategoria, nombre_almacen as nombre 
    FROM almacen_general WHERE estado='1' AND estado_delete='1' ; ";
    return ejecutarConsultaArray($sql);
  }

  //Seleccionar Trabajador Select2
  public function obtenerImg($idproducto)
  {
    $sql = "SELECT imagen FROM producto WHERE idproducto='$idproducto'";
    return ejecutarConsulta($sql);
  }

  //Seleccionar una ficha tecnica
  public function ficha_tec($idproducto)
  {
    $sql = "SELECT ficha_tecnica FROM producto WHERE idproducto='$idproducto'";
    return ejecutarConsulta($sql);
  }

  //------------------------------------------------------------------------------------
  // ------------ ADD PRODUCTOS DE PROYECTOS A UN ALMACEN GENERAL  ---------------------
  //------------------------------------------------------------------------------------

  public function marcas_x_producto($id_proyecto, $id_producto)
  {

    $sql_0 = "SELECT  dc.marca,  pr.nombre AS nombre_producto
		FROM compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr
		WHERE cpp.idcompra_proyecto = dc.idcompra_proyecto AND dc.idproducto = pr.idproducto     
    AND cpp.idproyecto = '$id_proyecto' AND dc.idproducto = '$id_producto'
    AND cpp.estado = '1' AND cpp.estado_delete = '1'  GROUP BY dc.idproducto, dc.marca ORDER BY pr.nombre ASC;";
    return ejecutarConsultaArray($sql_0);
  }

  public function select2_proyect()
  {
    $sql = "SELECT idproyecto,nombre_proyecto,nombre_codigo FROM proyecto;";
    return ejecutarConsultaArray($sql);
  }

  public function select2_recursos_almacen($idproyecto)
  {

    $sql = "SELECT ar.idalmacen_resumen,ar.idproyecto,ar.idproducto,ar.tipo,ar.saldo_anterior,ar.total_stok, p.nombre as nombre_producto, 
    um.nombre_medida as unidad_medida,um.abreviacion, c.nombre as categoria
    FROM almacen_resumen as ar
    INNER JOIN producto as p on ar.idproducto=p.idproducto
    INNER JOIN unidad_medida um on p.idunidad_medida=um.idunidad_medida
    INNER JOIN categoria_insumos_af c on p.idcategoria_insumos_af=c.idcategoria_insumos_af
    where ar.idproyecto='$idproyecto' and ar.total_stok>'0';";
    return ejecutarConsultaArray($sql);
  }

  //----------- Insertar productos a almacen general
  public function insertar_alm_general($idalmacen_producto_guardado, $idalmacen_general_ag, $fecha_ingreso_ag, $dia_ingreso, $idproducto_ag, $id_ar_ag, $cantidad_ag)
  {
    $ii = 0;

    if (!empty($id_ar_ag)) {

      while ($ii < count($idproducto_ag)) {
        //ACTUALIZAMOS EL ALMACEN_RESUMEN
        $sql = "UPDATE almacen_resumen SET  total_stok= total_stok - $cantidad_ag[$ii] , total_egreso= total_egreso + $cantidad_ag[$ii], user_updated='$this->id_usr_sesion'
          WHERE idalmacen_resumen='$id_ar_ag[$ii]';";
        $ar = ejecutarConsulta($sql);
        if ($ar['status'] == false) {
          return $ar;
        }

        //add registro en nuestra bitacora.
        $sql_bit_d = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('almacen_resumen','" . $id_ar_ag[$ii] . "','Actualizacion x por envio a almacen general','$this->id_usr_sesion')";
        $bitacora = ejecutarConsulta($sql_bit_d);
        if ($bitacora['status'] == false) {
          return $bitacora;
        }

        //INSERTAMOS A UN ALMACEN_GENERAL
        $sql_0 = " INSERT INTO almacen_producto_guardado( idalmacen_general, idalmacen_resumen, fecha_envio, cantidad,tipo_movimiento) 
          VALUES ('$idalmacen_general_ag','$id_ar_ag[$ii]','$fecha_ingreso_ag', '$cantidad_ag[$ii]','ID')";
        $creando = ejecutarConsulta_retornarID($sql_0);
        if ($creando['status'] == false) {
          return $creando;
        }
        $id = $creando['data'];
        //add registro en nuestra bitacora
        $sql_5 = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('almacen_producto_guardado','$id','Crear registro','$this->id_usr_sesion')";
        $bitacora = ejecutarConsulta($sql_5);
        if ($bitacora['status'] == false) {
          return $bitacora;
        }

        $ii = $ii + 1;
      }
      return $retorno = ['status' => true, 'message' => 'todo oka ps', 'data' => ''];
    }


    return $retorno = ['status' => true, 'message' => 'todo oka ps', 'data' => ''];
  }

  //------------------------------------------------------------------------------------
  // ----------------------- T R A N S F E R E N C I A S  ------------------------------
  //------------------------------------------------------------------------------------


  //SELECT ALMACEN ORIGEN, DESTINO
  public function select_lista_almacenes($id_alm_origen)
  {
    $id = "";
    if (!empty($id_alm_origen) && $id_alm_origen == '0') {
      $id = "";
    } elseif (!empty($id_alm_origen) && $id_alm_origen != '0') {
      $id = " AND idalmacen_general <> $id_alm_origen ";
    }
    $sql = "SELECT idalmacen_general, nombre_almacen as nombre 
    FROM almacen_general WHERE estado='1' AND estado_delete='1' $id ; ";
    return ejecutarConsultaArray($sql);
  }

  public function guardar_transf_almacen($name_alm_destino, $idalmacen_prod_guar, $cantidad_alm_trans, $fecha_transf, $alm_resumen_original)
  {
   // var_dump('$name_alm_destino :'. $name_alm_destino.' $idalmacen_prod_guar :'. $idalmacen_prod_guar.'$cantidad_alm_trans : '.$cantidad_alm_trans); die();

    //ACTUALIZAMOS EL ALMACEN_RESUMEN
    $sql = "UPDATE almacen_producto_guardado SET cantidad=cantidad-'$cantidad_alm_trans' WHERE idalmacen_producto_guardado='$idalmacen_prod_guar'";
    $update_alm = ejecutarConsulta($sql);

    if ($update_alm['status'] == false) {
      return $update_alm;
    }

    //INSERTAMOS A UN ALMACEN_GENERAL
    $sql_0 = " INSERT INTO almacen_producto_guardado( idalmacen_general, idalmacen_resumen, fecha_envio, cantidad,tipo_movimiento) 
            VALUES ('$name_alm_destino','$alm_resumen_original','$fecha_transf', '$cantidad_alm_trans','TEA')";
    $creando = ejecutarConsulta_retornarID($sql_0);
    if ($creando['status'] == false) {
      return $creando;
    }
    $id = $creando['data'];
    //add registro en nuestra bitacora
    $sql_5 = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('almacen_producto_guardado','$id','Crear registro','$this->id_usr_sesion')";
    $bitacora = ejecutarConsulta($sql_5);
    if ($bitacora['status'] == false) {
      return $bitacora;
    }


    return $retorno = ['status' => true, 'message' => 'todo oka ps', 'data' => ''];
  }
}
