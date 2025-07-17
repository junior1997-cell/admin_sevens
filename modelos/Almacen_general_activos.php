<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Almacen_general_activos
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

    $sql = "SELECT nombre_almacen, descripcion,tipo_almacen, estado, estado_delete FROM almacen_general WHERE tipo_almacen='Activos' and nombre_almacen = '$nombre';";
    $buscando = ejecutarConsultaArray($sql);
    if ($buscando['status'] == false) {
      return $buscando;
    }

    if (empty($buscando['data'])) {
      $sql = "INSERT INTO almacen_general(nombre_almacen, descripcion,tipo_almacen, user_created ) VALUES ('$nombre','$descripcion','Activos', '$this->id_usr_sesion')";
      $insertar =  ejecutarConsulta_retornarID($sql, 'C');
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
    $sql = "UPDATE almacen_general SET nombre_almacen='$nombre', descripcion='$descripcion',tipo_almacen='Activos', user_updated = '$this->id_usr_sesion'
		WHERE idalmacen_general = '$idalmacen_general';";
    $editar =  ejecutarConsulta($sql, 'U');
    return $editar;
  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idalmacen_general)
  {

    $sql = "UPDATE almacen_general SET estado='0',user_trash= '$this->id_usr_sesion'  WHERE idalmacen_general ='$idalmacen_general'";
    $desactivar = ejecutarConsulta($sql, 'T');

    return $desactivar;
  }

  //Implementamos un método para eliminar
  public function eliminar($idalmacen_general)
  {
    $sql = "UPDATE almacen_general SET estado_delete='0',user_delete= '$this->id_usr_sesion' WHERE idalmacen_general ='$idalmacen_general'";
    $eliminar =  ejecutarConsulta($sql, 'D');

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
    FROM almacen_general WHERE tipo_almacen='Activos' AND  estado='1' AND estado_delete='1' ORDER BY nombre_almacen ASC;";
    return ejecutarConsulta($sql);
  }

  //Implementar un método para listar los registros
  public function tabla_detalle($id_proyecto, $id_almacen, $_stock)
  {
    $stock = "";

    if ($_stock == '1') {
      $stock = "AND agr.total_stok>'0'";
    } else {
      $stock = "";
    }

    $sql = "SELECT agr.idalmacen_general_resumen,agr.tipo,agr.total_stok,agr.total_ingreso,agr.total_egreso, ag.idalmacen_general,p.nombre as nombre_producto, 
    um.nombre_medida as unidad_medida,um.abreviacion, c.nombre as categoria, LPAD(agr.idproducto, 5, '0') as idproducto_v2
    FROM almacen_general_resumen AS agr
    INNER JOIN almacen_general as ag on agr.idalmacen_general = ag.idalmacen_general
    -- INNER JOIN almacen_resumen as ar on agr.idalmacen_resumen=ar.idalmacen_resumen
    INNER JOIN producto as p on agr.idproducto = p.idproducto
    INNER JOIN unidad_medida um on p.idunidad_medida=um.idunidad_medida
    INNER JOIN categoria_insumos_af c on p.idcategoria_insumos_af=c.idcategoria_insumos_af
    WHERE agr.idalmacen_general='$id_almacen'  $stock AND ag.tipo_almacen='Activos' AND c.idcategoria_insumos_af !='1' AND agr.estado = '1' AND agr.estado_delete = '1'";
    return ejecutarConsulta($sql);
  }

  public function tabla_detalle_almacen_general($idalmacen_general, $idalmacen_general_resumen)
  {
    // var_dump($idalmacen_general); die();
    $sql = "SELECT 
    ad.idalmacen_general_detalle, 
    ad.idalmacen_general_resumen, 
    ad.idalmacen_general_destino, 
    ad.idproyecto, 
    ad.tipo_mov,
    CASE ad.tipo_mov
        WHEN 'IEA' THEN 'INGRESO ENTRE ALMACENES'
        WHEN 'EEA' THEN 'EGRESO ENTRE ALMACENES'
        WHEN 'IGP' THEN 'INGRESO A ALMACEN GENERAL DE PROYECTO'
        WHEN 'EGP' THEN 'EGRESO DE ALMACEN GENERAL E INGRESO A UN PROYECTO' 
        WHEN 'ED' THEN 'EGRESO POR DETERIORO'
    END AS tipo_movimiento ,
    ad.fecha, 
    ad.name_day, 
    ad.name_month, 
    ad.name_year, 
    CASE ad.tipo_mov
        WHEN 'IEA' THEN ad.cantidad
        WHEN 'EEA' THEN -1*ad.cantidad
        WHEN 'IGP' THEN ad.cantidad
        WHEN 'EGP' THEN -1*ad.cantidad
        WHEN 'IDAG' THEN ad.cantidad
        WHEN 'EGP' THEN -1*ad.cantidad
        WHEN 'ED' THEN -1*ad.cantidad
    END AS cantidad, 
    ad.stok_anterior, 
    ad.stok_actual,
    CASE ad.tipo_mov
        WHEN 'IEA' THEN agd.nombre_almacen
        WHEN 'EEA' THEN agd.nombre_almacen
        WHEN 'IGP' THEN p.nombre_codigo
        WHEN 'EGP' THEN p.nombre_codigo
        WHEN 'IDAG' THEN 'Externo'
    END AS nombre_proyecto_almacen
  FROM almacen_general_detalle ad
  LEFT JOIN proyecto p ON ad.idproyecto = p.idproyecto
  LEFT JOIN almacen_general agd ON ad.idalmacen_general_destino = agd.idalmacen_general
  INNER JOIN almacen_general_resumen as agr on agr.idalmacen_general_resumen=ad.idalmacen_general_resumen
  where agr.idalmacen_general = '$idalmacen_general' and agr.idalmacen_general_resumen='$idalmacen_general_resumen';";

    return ejecutarConsultaArray($sql);
  }
  //Seleccionar Trabajador Select2
  public function lista_de_categorias()
  {
    $sql = "SELECT idalmacen_general as idcategoria, nombre_almacen as nombre 
    FROM almacen_general WHERE tipo_almacen='Activos' AND estado='1' AND estado_delete='1' ; ";
    return ejecutarConsultaArray($sql);
  }

  //------------------------------------------------------------------------------------
  // ------------ ADD PRODUCTOS DE PROYECTOS A UN ALMACEN GENERAL  ---------------------
  //------------------------------------------------------------------------------------

  public function select2_recursos_almacen($idproyecto)
  {
    $sql = "SELECT ar.idalmacen_resumen,ar.idproyecto,ar.idproducto,ar.tipo,ar.total_egreso,ar.total_stok,ar.total_ingreso, p.nombre as nombre_producto, 
    um.nombre_medida as unidad_medida,um.abreviacion, c.nombre as categoria
    FROM almacen_resumen as ar
    INNER JOIN producto as p on ar.idproducto=p.idproducto
    INNER JOIN unidad_medida um on p.idunidad_medida=um.idunidad_medida
    INNER JOIN categoria_insumos_af c on p.idcategoria_insumos_af=c.idcategoria_insumos_af
    where ar.idproyecto='$idproyecto' AND c.idcategoria_insumos_af !='1' and ar.total_stok>'0' ORDER BY p.nombre ASC;";
    return ejecutarConsultaArray($sql);
  }

  //----------- Insertar productos a almacen general ,-------$id_ar_ag = id_almacen_resumen
  public function insertar_alm_general(
    $idalmacen_general_ag,
    $fecha_ingreso_ag,
    $dia_ingreso,
    $idproducto_ag,
    $proyecto_ag,
    $id_almacen_resumen,
    $cantidad_ag,
    $stok,
    $t_egreso,
    $t_ingreso,
    $tipo_mov
  ) {

    $ii = 0;

    if (!empty($id_almacen_resumen)) {

      while ($ii < count($idproducto_ag)) {

        //=================A L M A C E N  P O R  P R O Y E C T O =====================

        //ACTUALIZAMOS EL ALMACEN_RESUMEN
        $sql = "UPDATE almacen_resumen SET total_stok= total_stok - $cantidad_ag[$ii] , total_egreso= total_egreso + $cantidad_ag[$ii], 
        user_updated='$this->id_usr_sesion' WHERE idalmacen_resumen='$id_almacen_resumen[$ii]';";

        $ar = ejecutarConsulta($sql, 'U');
        if ($ar['status'] == false) {
          return $ar;
        }

        //REGISTRAMOS EL EGRESO EN  ALMACEN_DETALLE
        $sql_alm_detall = "INSERT INTO almacen_detalle(idalmacen_resumen, idalmacen_general, tipo_mov, fecha, cantidad, stok_anterior, stok_actual) 
        VALUES ('$id_almacen_resumen[$ii]','$idalmacen_general_ag','EPG','$fecha_ingreso_ag','$cantidad_ag[$ii]','$stok[$ii]','$cantidad_ag[$ii]-$stok[$ii]')";

        $sql_alm_det = ejecutarConsulta($sql_alm_detall, 'C');
        if ($sql_alm_det['status'] == false) {
          return $sql_alm_det;
        }
        //=================F I N  A L M A C E N  P O R  P R O Y E C T O ====================

        $sql_validate = "SELECT idalmacen_general_resumen,idalmacen_general,idproducto 
        FROM almacen_general_resumen where idalmacen_general='$idalmacen_general_ag' and idproducto='$idproducto_ag[$ii]' ";

        $validate = ejecutarConsultaSimpleFila($sql_validate);
        if ($validate['status'] == false) {
          return $validate;
        }

        if (!empty($validate['data'])) {

          $idalmacen_general_r = $validate['data']['idalmacen_general_resumen'];
          $idalmacen           = $validate['data']['idalmacen_general'];
          $id_producto_r = $validate['data']['idproducto'];
        } else {
          $idalmacen_general_r = null;
          $idalmacen           = null;
          $id_producto_r       = null;
        }

        if (!empty($idalmacen_general_r) &&  !empty($idalmacen) && !empty($id_producto_r) && $idalmacen = $idalmacen_general_ag  && $id_producto_r = $idproducto_ag[$ii]) {

          //ACTUALIZAMOS EL QUE YA EXISTE
          $sql_update = "UPDATE almacen_general_resumen SET 
          idalmacen_general='$idalmacen_general_ag', idproducto='$idproducto_ag[$ii]', total_stok=total_stok + $cantidad_ag[$ii],
          total_ingreso=total_ingreso+ $cantidad_ag[$ii], user_updated='$this->id_usr_sesion'
          WHERE idalmacen_general_resumen='$idalmacen_general_r'";

          $sql_alm_detalle = ejecutarConsulta($sql_update, 'U');

          if ($sql_alm_detalle['status'] == false) {
            return $sql_alm_detalle;
          }

          //Registramos un nuevo detalle
          $sql_create_det = "INSERT INTO almacen_general_detalle( idalmacen_general_resumen, idproyecto, tipo_mov, fecha,cantidad, user_created) 
          VALUES ('$idalmacen_general_r','$proyecto_ag[$ii]','IGP','$fecha_ingreso_ag','$cantidad_ag[$ii]','$this->id_usr_sesion')";
          $sql_alm_det_gen = ejecutarConsulta($sql_create_det, 'C');

          if ($sql_alm_det_gen['status'] == false) {
            return $sql_alm_det_gen;
          }
        } else {

          //AGREGAMOS UNO NUEVO
          $sql_nuevo = "INSERT INTO almacen_general_resumen(idalmacen_general, idproducto, tipo, total_stok, total_ingreso, user_created) 
          VALUES ('$idalmacen_general_ag','$idproducto_ag[$ii]','$tipo_mov[$ii]','$cantidad_ag[$ii]','$cantidad_ag[$ii]','$this->id_usr_sesion')";
          $sql_new_regist = ejecutarConsulta_retornarID($sql_nuevo, 'C');

          if ($sql_new_regist['status'] == false) {
            return $sql_new_regist;
          }

          $idalm_general_resumen = $sql_new_regist['data'];

          //Registramos un nuevo detalle
          $sql_create_det = "INSERT INTO almacen_general_detalle( idalmacen_general_resumen, idproyecto, tipo_mov, fecha,cantidad, user_created) 
          VALUES ('$idalm_general_resumen','$proyecto_ag[$ii]','IGP','$fecha_ingreso_ag','$cantidad_ag[$ii]','$this->id_usr_sesion')";
          $sql_alm_det_gen = ejecutarConsulta($sql_create_det, 'C');

          if ($sql_alm_det_gen['status'] == false) {
            return $sql_alm_det_gen;
          }
        }

        $ii = $ii + 1;
      }
      return $retorno = ['status' => true, 'message' => 'todo oka ps', 'data' => ''];
    }


    return $retorno = ['status' => true, 'message' => 'todo oka ps', 'data' => ''];
  }

  //-----------------------------------------------------------------------------------------------------------------
  // ----------- T R A N S F E R E N C I A S  A   P R O Y E C T O S   Y   A L M A C E N  G E N E R A L --------------
  //-----------------------------------------------------------------------------------------------------------------


  public function guardar_transf_almacen_proyecto(
    $array_data_g,
    $idalmacen_general_origen,
    $tranferencia,
    $name_alm_proyecto,
    $fecha_transf_proy_alm
  ) {
    $array_data_trns = json_decode($array_data_g, true);

    if (!empty($tranferencia) && $tranferencia == "Otro_Almacen") {
      //enviamos otro almacen

      // var_dump($tipo_trns); die();
      if (!empty($array_data_trns)) {

        foreach ($array_data_trns as $key => $value) {

          //ACTUALIZAMOS EN EL ALMACEN ORIGEN
          $sql_update = "UPDATE almacen_general_resumen SET 
              total_stok=total_stok - " . $value['cantidad__trns_env'] . ", total_egreso=total_egreso+ " . $value['cantidad__trns_env'] . ", user_updated='$this->id_usr_sesion'         
              WHERE idalmacen_general_resumen='" . $value['idalmacen_general_resumen_trns'] . "' 
              and idproducto='" . $value['idproducto_trns'] . "' 
              and idalmacen_general='" . $value['idalmacen_general_trns'] . "'";

          $sql_alm_detalle = ejecutarConsulta($sql_update, 'U');
          if ($sql_alm_detalle['status'] == false) {
            return $sql_alm_detalle;
          }

          //REGISTRAMOS LA SALIDA DEL ALAMACEN ORIGEN
          $sql_create_det = "INSERT INTO almacen_general_detalle( idalmacen_general_resumen, idalmacen_general_destino, tipo_mov, fecha,cantidad, user_created) 
              VALUES ('" . $value['idalmacen_general_resumen_trns'] . "','$name_alm_proyecto','EEA','$fecha_transf_proy_alm','" . $value['cantidad__trns_env'] . "','$this->id_usr_sesion')";
          $sql_alm_det_gen = ejecutarConsulta($sql_create_det, 'C');

          if ($sql_alm_det_gen['status'] == false) {
            return $sql_alm_det_gen;
          }

          //REGISTRO  AL NUEVO ALMACEN

          //Verificamos si hay el producto en el almacen nuevo 
          $sql_verif = "SELECT * FROM almacen_general_resumen WHERE idalmacen_general='$name_alm_proyecto' and idproducto='" . $value['idproducto_trns'] . "'";
          $r_verficar = ejecutarConsultaSimpleFila($sql_verif);

          if ($r_verficar['status'] == false) {
            return $r_verficar;
          }
          if (!empty($r_verficar['data'])) {

            $idalmacen_general_r = $r_verficar['data']['idalmacen_general_resumen'];
            $idalmacen           = $r_verficar['data']['idalmacen_general'];
            $id_producto_r = $r_verficar['data']['idproducto'];
          } else {
            $idalmacen_general_r = null;
            $idalmacen           = null;
            $id_producto_r       = null;
          }

          if (!empty($idalmacen_general_r) &&  !empty($idalmacen) && !empty($id_producto_r) && $idalmacen = $name_alm_proyecto  && $id_producto_r = $value['idproducto_trns']) {

            //ACTUALIZAMOS EL QUE YA EXISTE ".$value['cantidad__trns_env']."
            $sql_update = "UPDATE almacen_general_resumen SET 
                total_stok=total_stok + " . $value['cantidad__trns_env'] . ", total_ingreso=total_ingreso + " . $value['cantidad__trns_env'] . ", 
                user_updated='$this->id_usr_sesion' WHERE idalmacen_general_resumen='$idalmacen_general_r'";

            $sql_alm_detalle = ejecutarConsulta($sql_update, 'U');
            if ($sql_alm_detalle['status'] == false) {
              return $sql_alm_detalle;
            }

            //Registramos un nuevo detalle
            $sql_create_det = "INSERT INTO almacen_general_detalle( idalmacen_general_resumen, idalmacen_general_destino, tipo_mov, fecha,cantidad, user_created) 
                VALUES ('$idalmacen_general_r','$idalmacen_general_origen','IEA','$fecha_transf_proy_alm','" . $value['cantidad__trns_env'] . "','$this->id_usr_sesion')";
            $sql_alm_det_gen = ejecutarConsulta($sql_create_det, 'C');

            if ($sql_alm_det_gen['status'] == false) {
              return $sql_alm_det_gen;
            }
          } else {

            //AGREGAMOS UNO NUEVO
            $sql_nuevo = "INSERT INTO almacen_general_resumen(idalmacen_general, idproducto, tipo, total_stok, total_ingreso, user_created) 
                VALUES ('$name_alm_proyecto','" . $value['idproducto_trns'] . "','" . $value['tipo_trns'] . "','" . $value['cantidad__trns_env'] . "','" . $value['cantidad_trns'] . "','$this->id_usr_sesion')";
            $sql_new_regist = ejecutarConsulta_retornarID($sql_nuevo, 'C');

            if ($sql_new_regist['status'] == false) {
              return $sql_new_regist;
            }

            $idalm_general_resumen = $sql_new_regist['data'];

            //Registramos un nuevo detalle
            $sql_create_det = "INSERT INTO almacen_general_detalle( idalmacen_general_resumen, idalmacen_general_destino,  tipo_mov, fecha,cantidad, user_created) 
                VALUES ('$idalm_general_resumen','$idalmacen_general_origen','IEA','$fecha_transf_proy_alm','" . $value['cantidad__trns_env'] . "','$this->id_usr_sesion')";
            $sql_alm_det_gen = ejecutarConsulta($sql_create_det, 'C');

            if ($sql_alm_det_gen['status'] == false) {
              return $sql_alm_det_gen;
            }
          }

          # code...
        }
        return $retorno = ['status' => true, 'message' => 'todo oka ps', 'data' => ''];
      }

      // -----------------------------
      //ACTUALIZAR EL ALMACEN RESUMEN GENERAL ... listo
      //REGISTRAR EN EL DETALLE LA SALIDA .... Listo
      //REGISTRAR EL INGRESO AL NUEVO ALMACEN 
      //rEGISTRAR EL DETALLE DEL INGRESO AL ALMACEN

    } else {
      //enviamos producto a un almacen de proyecto

      //  var_dump('proyect'); die();
      if (!empty($array_data_trns)) {

        foreach ($array_data_trns as $key => $val) {

          //ACTUALIZAMOS EN EL ALMACEN ORIGEN
          $sql_update = "UPDATE almacen_general_resumen SET 
            total_stok=total_stok - ".$val['cantidad__trns_env'].", total_egreso=total_egreso + " . $val['cantidad__trns_env'] . ", user_updated='$this->id_usr_sesion'         
            WHERE idalmacen_general_resumen='" . $val['idalmacen_general_resumen_trns'] . "' 
            and idproducto='" . $val['idproducto_trns'] . "' 
            and idalmacen_general='" . $val['idalmacen_general_trns'] . "'";

          $sql_alm_detalle = ejecutarConsulta($sql_update, 'U');
          if ($sql_alm_detalle['status'] == false) {
            return $sql_alm_detalle;
          }

          //REGISTRAMOS LA SALIDA DEL ALAMACEN ORIGEN
          $sql_create_det = "INSERT INTO almacen_general_detalle( idalmacen_general_resumen, idproyecto, tipo_mov, fecha,cantidad, user_created) 
            VALUES ('" . $val['idalmacen_general_resumen_trns'] . "','$name_alm_proyecto','EGP','$fecha_transf_proy_alm','" . $val['cantidad__trns_env'] . "','$this->id_usr_sesion')";
          $sql_alm_det_gen = ejecutarConsulta($sql_create_det, 'C');

          if ($sql_alm_det_gen['status'] == false) {
            return $sql_alm_det_gen;
          }

          //REGISTRO  AL NUEVO ALMACEN DE PROYECTO

          //Verificamos si hay el producto en el almacen nuevo 
          $sql_verif = "SELECT * FROM almacen_resumen WHERE idproyecto='$name_alm_proyecto' and idproducto='" . $val['idproducto_trns'] . "'";
          $r_verficar = ejecutarConsultaSimpleFila($sql_verif);

          if ($r_verficar['status'] == false) {
            return $r_verficar;
          }
          if (!empty($r_verficar['data'])) {

            $idalmacen_resumen_p = $r_verficar['data']['idalmacen_resumen'];
            $idproyecto_p           = $r_verficar['data']['idproyecto'];
            $id_producto_p = $r_verficar['data']['idproducto'];
          } else {
            $idalmacen_resumen_p = null;
            $idproyecto_p        = null;
            $id_producto_p       = null;
          }

          if (!empty($idalmacen_resumen_p) &&  !empty($idproyecto_p) && !empty($id_producto_p) && $idproyecto_p = $name_alm_proyecto  && $id_producto_p = $val['idproducto_trns']) {
            //  var_dump($idalmacen_general_r); die();
            //ACTUALIZAMOS EL QUE YA EXISTE
            $sql_update = "UPDATE almacen_resumen SET 
              total_stok=total_stok + " . $val['cantidad__trns_env'] . ", total_ingreso=total_ingreso + " . $val['cantidad__trns_env'] . ", 
              user_updated='$this->id_usr_sesion' WHERE idalmacen_resumen='$idalmacen_resumen_p'";

            $sql_alm_detalle = ejecutarConsulta($sql_update, 'U');
            if ($sql_alm_detalle['status'] == false) {
              return $sql_alm_detalle;
            }

            //Registramos un nuevo detalle
            $sql_create_det = "INSERT INTO almacen_detalle( idalmacen_resumen, idalmacen_general, tipo_mov, fecha,cantidad, user_created) 
              VALUES ('$idalmacen_resumen_p','$idalmacen_general_origen','IPG','$fecha_transf_proy_alm','" . $val['cantidad__trns_env'] . "','$this->id_usr_sesion')";
            $sql_alm_det_gen = ejecutarConsulta($sql_create_det, 'C');

            if ($sql_alm_det_gen['status'] == false) {
              return $sql_alm_det_gen;
            }
          } else {

            //AGREGAMOS UNO NUEVO
            $sql_nuevo = "INSERT INTO almacen_resumen(idproyecto, idproducto, tipo, total_stok, total_ingreso, user_created) 
              VALUES ('$name_alm_proyecto','" . $val['idproducto_trns'] . "','" . $val['tipo_trns'] . "','" . $val['cantidad__trns_env'] . "','" . $val['cantidad__trns_env'] . "','$this->id_usr_sesion')";
            $sql_new_regist = ejecutarConsulta_retornarID($sql_nuevo, 'C');

            if ($sql_new_regist['status'] == false) {
              return $sql_new_regist;
            }

            $idalmacen_resumen = $sql_new_regist['data'];

            //Registramos un nuevo detalle
            $sql_create_det = "INSERT INTO almacen_detalle( idalmacen_resumen, idalmacen_general,  tipo_mov, fecha,cantidad, user_created) 
              VALUES ('$idalmacen_resumen','$idalmacen_general_origen','IEA','$fecha_transf_proy_alm','" . $val['cantidad__trns_env'] . "','$this->id_usr_sesion')";
            $sql_alm_det_gen = ejecutarConsulta($sql_create_det, 'C');

            if ($sql_alm_det_gen['status'] == false) {
              return $sql_alm_det_gen;
            }
          }
        }
        return $retorno = ['status' => true, 'message' => 'todo oka ps', 'data' => ''];
      }

      // -----------------------------
      //ACTUALIZAR EL ALMACEN RESUMEN GENERAL
      //REGISTRAR EN EL DETALLE LA SALIDA
      //REGISTRAR EL INGRESO AL NUEVO ALMACEN 
      //rEGISTRAR EL DETALLE DEL INGRESO AL ALMACEN
    }

    return $retorno = ['status' => true, 'message' => 'todo oka ps', 'data' => ''];
  }

  //Implementar un método para listar los registros
  public function transferencia_a_proy_almacen($id_almacen)
  {
    $data = [];

    $sql = "SELECT 
    agr.idalmacen_general_resumen,
    p.idproducto,
    agr.tipo,
    agr.total_stok,
    agr.total_ingreso,
    agr.total_egreso,
    ag.idalmacen_general,
    p.nombre as nombre_producto,
    um.nombre_medida as unidad_medida,
    um.abreviacion,
    c.nombre as categoria
    FROM almacen_general_resumen AS agr
    INNER JOIN almacen_general as ag on agr.idalmacen_general = ag.idalmacen_general
    INNER JOIN producto as p on agr.idproducto = p.idproducto
    INNER JOIN unidad_medida um on p.idunidad_medida=um.idunidad_medida
    INNER JOIN categoria_insumos_af c on p.idcategoria_insumos_af=c.idcategoria_insumos_af
    WHERE agr.idalmacen_general='$id_almacen' AND c.idcategoria_insumos_af !='1' AND agr.total_stok>'0' AND agr.estado = '1' AND agr.estado_delete = '1';";


    $sql_return = ejecutarConsultaArray($sql);

    if ($sql_return['status'] == false) {
      return $sql_return;
    }

    if (!empty($sql_return['data'])) {

      // echo json_encode($sql_return['data'],true);die();
      $cont = 1;

      foreach ($sql_return['data'] as $key => $value) {

        $data[] = [
          'indice'          => $cont,
          'idalmacen_general_resumen' => $value['idalmacen_general_resumen'],
          'nombre_producto' => '<textarea class="form-control textarea_datatable" rows="1" style="font-size: 12px;">' . $value['nombre_producto'] . ' - ' . $value['abreviacion'] . '</textarea>
                                <input type="hidden" name="idalmacen_general_trns[]"  id="idalmacen_general_trns' . $cont . '" value="' . $value['idalmacen_general'] . '"/>
                                
                                <input type="hidden" name="idproducto_trns[]" id="idproducto_trns' . $cont . '" value="' . $value['idproducto'] . '"/>
                                <input type="hidden" name="idalmacen_general_resumen_trns[]" id="idalmacen_general_resumen_trns' . $cont . '" value="' . $value['idalmacen_general_resumen'] . '"/>
                                <input type="hidden" name="tipo_trns[]" id="tipo_trns' . $cont . '" value="' . $value['tipo'] . '"/>
                                <input type="hidden" name="categoria_trns[]" id="categoria_trns' . $cont . '" value="' . $value['categoria'] . '"/>',

          'unidad'          => $value['unidad_medida'],

          'stock'           => $value['total_stok'],

          'cantidad'        => '<input type="number" class="form-control cant_g" name="cantidad_tr' . $cont . '" id="cantidad__trns' . $cont . '" onkeyup="replicar_cantidad_a_r(' . $cont . ')" disabled="true" placeholder="cantidad"  min="0" step="0.01" max="' . $value['total_stok'] . '"/>
                               <input type="hidden" name="cantidad_trns[]" class="form-control" id="cantidad__trns_env' . $cont . '"/>',

          'activar'         => '<div class="custom-control custom-switch">
                                  <input class="custom-control-input checked_all " type="checkbox" id="customCheckbox' . $cont . '" onchange="update_valueChec(' . $cont . ',\'' .$value['total_stok']. '\' )" >
                                  <input type="hidden" class="estadochecked_all input_checkted" name="ValorCheck_trns[]" id="ValorCheck' . $cont . '">
                                  <label for="customCheckbox' . $cont . '" class="custom-control-label"></label>
                                </div>',

        ];
        $cont++;
      };
      //echo json_encode($data,true);die();
      return $retorno = ['status' => true, 'message' => 'todo oka ps', 'data' => $data];
    } else {

      return $sql_return;
    }
  }

  /**
   * -----------------------------------------------------
   * ------------------INGRESO DIRECTO.-------------------
   * -----------------------------------------------------
   */

  public function guardar_y_prod_id_tup($almacen_tup, $fecha_tup, $idproducto_tup, $marca_tup, $cantidad_tup)
  {
    /*var_dump($idproducto_tup);
    die();*/

    $ii = 0;

    if (!empty($idproducto_tup)) {

      while ($ii < count($idproducto_tup)) {
        //Verificamos si hay el producto en el almacen nuevo 
        $sql_validate = "SELECT idalmacen_general_resumen,idalmacen_general,idproducto 
        FROM almacen_general_resumen where idalmacen_general='$almacen_tup' and idproducto='$idproducto_tup[$ii]' ";

        $validate = ejecutarConsultaSimpleFila($sql_validate);
        if ($validate['status'] == false) {
          return $validate;
        }

        if (!empty($validate['data'])) {

          $idalmacen_general_r = $validate['data']['idalmacen_general_resumen'];
          $idalmacen           = $validate['data']['idalmacen_general'];
          $id_producto_r = $validate['data']['idproducto'];
        } else {
          $idalmacen_general_r = null;
          $idalmacen           = null;
          $id_producto_r       = null;
        }

        if (!empty($idalmacen_general_r) &&  !empty($idalmacen) && !empty($id_producto_r) && $idalmacen = $almacen_tup  && $id_producto_r = $idproducto_tup[$ii]) {

          //ACTUALIZAMOS EL QUE YA EXISTE
          $sql_update = "UPDATE almacen_general_resumen SET 
          idalmacen_general='$almacen_tup', idproducto='$idproducto_tup[$ii]', total_stok=total_stok + $cantidad_tup[$ii],
          total_ingreso=total_ingreso+ $cantidad_tup[$ii], user_updated='$this->id_usr_sesion'
          WHERE idalmacen_general_resumen='$idalmacen_general_r'";

          $sql_alm_detalle = ejecutarConsulta($sql_update, 'U');

          if ($sql_alm_detalle['status'] == false) {
            return $sql_alm_detalle;
          }

          //Registramos un nuevo detalle
          $sql_create_det = "INSERT INTO almacen_general_detalle( idalmacen_general_resumen, tipo_mov, fecha,cantidad,marca,user_created) 
          VALUES ('$idalmacen_general_r','IDAG','$fecha_tup','$cantidad_tup[$ii]','$marca_tup[$ii]','$this->id_usr_sesion')";
          $sql_alm_det_gen = ejecutarConsulta($sql_create_det, 'C');

          if ($sql_alm_det_gen['status'] == false) {
            return $sql_alm_det_gen;
          }
        } else {

          //AGREGAMOS UNO NUEVO $almacen_tup, $fecha_tup, $idproducto_tup, $marca_tup, $cantidad_tup
          $sql_nuevo = "INSERT INTO almacen_general_resumen(idalmacen_general, idproducto, total_stok, total_ingreso, user_created) 
          VALUES ('$almacen_tup','$idproducto_tup[$ii]','$cantidad_tup[$ii]','$cantidad_tup[$ii]','$this->id_usr_sesion')";
          $sql_new_regist = ejecutarConsulta_retornarID($sql_nuevo, 'C');

          if ($sql_new_regist['status'] == false) {
            return $sql_new_regist;
          }

          $idalm_general_resumen = $sql_new_regist['data'];

          //Registramos un nuevo detalle
          $sql_create_det = "INSERT INTO almacen_general_detalle( idalmacen_general_resumen, tipo_mov, fecha,cantidad,marca, user_created) 
          VALUES ('$idalm_general_resumen','IDAG','$fecha_tup','$cantidad_tup[$ii]','$marca_tup[$ii]','$this->id_usr_sesion')";
          $sql_alm_det_gen = ejecutarConsulta($sql_create_det, 'C');

          if ($sql_alm_det_gen['status'] == false) {
            return $sql_alm_det_gen;
          }
        }

        $ii = $ii + 1;
      }
      return $retorno = ['status' => true, 'message' => 'todo oka ps', 'data' => ''];
    }
    return $retorno = ['status' => true, 'message' => 'todo oka ps', 'data' => 'Error'];
  }

  public function select2_proyect_almacen($tipo_transf, $id_almacen_g)
  {
    // var_dump($tipo_transf);die();
    $sql_return = "";

    if ($tipo_transf == 'Proyecto') {
      $sql = "SELECT idproyecto as id ,nombre_proyecto,nombre_codigo as nombre FROM proyecto  ORDER BY idproyecto desc;";

      $sql_return = ejecutarConsulta($sql);
      if ($sql_return['status'] == false) {
        return $sql_return;
      }
    } elseif ($tipo_transf === "Otro_Almacen") {
      $sql = "SELECT idalmacen_general  as id ,nombre_almacen as nombre 
      FROM almacen_general where  idalmacen_general<>'$id_almacen_g' AND tipo_almacen='Activos' AND estado = '1' AND estado_delete = '1'  ORDER BY idalmacen_general desc;";

      $sql_return = ejecutarConsulta($sql);
      if ($sql_return['status'] == false) {
        return $sql_return;
      }
    }else{
      $sql = "SELECT idalmacen_general  as id ,nombre_almacen as nombre 
      FROM almacen_general where  idalmacen_general='$id_almacen_g' AND tipo_almacen='Activos' AND estado = '1' AND estado_delete = '1'  ORDER BY idalmacen_general desc;";

      $sql_return = ejecutarConsulta($sql);
      if ($sql_return['status'] == false) {
        return $sql_return;
      }
    }
    return $sql_return;
  }

  //----------------------------------------------------------------
  //Implementar un método para listar los registros
  public function select2_productos_todos()
  {
    $sql_0 = "SELECT pr.idproducto, pr.nombre AS nombre_producto, um.nombre_medida, um.abreviacion,  pr.modelo, ci.nombre as clasificacion    
		FROM  producto AS pr, categoria_insumos_af AS ci, unidad_medida AS um 
		WHERE pr.idcategoria_insumos_af = ci.idcategoria_insumos_af AND um.idunidad_medida  = pr.idunidad_medida 
    AND pr.estado = '1' AND ci.idcategoria_insumos_af !='1' AND pr.estado_delete = '1' ORDER BY pr.nombre ASC;";
    return ejecutarConsultaArray($sql_0);
  }

  public function marcas_x_producto($id_producto)
  {
    $array_marca = [];

    //listar detalle_marca
    $sql = "SELECT dm.iddetalle_marca, dm.idproducto, dm.idmarca, m.nombre_marca as marca 
    FROM detalle_marca as dm, marca as m 
    WHERE dm.idmarca = m.idmarca AND dm.idproducto = '$id_producto' AND dm.estado='1' AND dm.estado_delete='1' ORDER BY dm.iddetalle_marca ASC;";
    $detalle_marca = ejecutarConsultaArray($sql);
    if ($detalle_marca['status'] == false) {
      return $detalle_marca;
    }



    if (empty($detalle_marca['data'])) {
      $array_marca[] = ['idproducto' => $id_producto, 'marca' => 'SIN MARCA', 'selected' => 'selected'];
    } else {
      foreach ($detalle_marca['data'] as $key => $val) {
        $array_marca[] = ['idproducto' => $id_producto, 'marca' => $val['marca']];
      }
    }
    return $retorno = ['status' => true, 'message' => 'Salió todo ok,', 'data' => $array_marca];
  }
}
