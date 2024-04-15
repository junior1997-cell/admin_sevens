<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Epp
{
  //Implementamos nuestro variable global
  public $id_usr_sesion;
  //Implementamos nuestro constructor
  public function __construct($id_usr_sesion = 0)
  {
    $this->id_usr_sesion = $id_usr_sesion;
  }
  //$idepp_x_proyecto,$idproyecto,$fecha_viaje,$tipo_viajero,$tipo_ruta,$cantidad,$precio_unitario,$precio_parcial,$ruta,$descripcion,$foto2
  //Implementamos un método para insertar registros
  public function insertar($idproyecto,$idtrabajador_por_proyecto,$idProduc_almacen_resumen,$fecha_g,$id_insumo,$cantidad,$marca)
  {

    // Verificar si $id_insumo es un array y si contiene datos
    
    if (is_array($idProduc_almacen_resumen) && !empty($idProduc_almacen_resumen)) {

      $ii = 0;
      $sw = true;     

      while ($ii < count($idProduc_almacen_resumen)) {

        //ACTUALIZAMOS EL ALMACEN_RESUMEN
        $sql = "UPDATE almacen_resumen SET total_stok= total_stok - $cantidad[$ii] , total_egreso= total_egreso + $cantidad[$ii], 
        user_updated='$this->id_usr_sesion' WHERE idalmacen_resumen='$idProduc_almacen_resumen[$ii]';";

        $ar = ejecutarConsulta($sql,'U');
        if ($ar['status'] == false) {
          return $ar;
        }

        $sql_2 = "INSERT INTO almacen_detalle( idalmacen_resumen, idproyecto_destino, idalmacen_general,idtrabajador_por_proyecto, tipo_mov, fecha, marca, cantidad)      
        VALUES ($idProduc_almacen_resumen[$ii], '$idproyecto', NULL,'$idtrabajador_por_proyecto', 'EPT', '$fecha_g', '$marca[$ii]', '$cantidad[$ii]')";         
        $new_salida = ejecutarConsulta_retornarID($sql_2, 'C'); if ( $new_salida['status'] == false) {return $new_salida; }

        // //REGISTRAMOS EL EGRESO EN  ALMACEN_DETALLE
        // // $sql_alm_detall = "INSERT INTO almacen_detalle(idalmacen_resumen, idalmacen_general, tipo_mov, fecha, cantidad, stok_anterior, stok_actual) 
        // // VALUES ('$id_almacen_resumen[$ii]','$idalmacen_general_ag','EPG','$fecha_ingreso_ag','$cantidad_ag[$ii]','$stok[$ii]','$cantidad_ag[$ii]-$stok[$ii]')";

        // $sql_alm_det = ejecutarConsulta($sql_alm_detall,'C');
        // if ($sql_alm_det['status'] == false) {
        //   return $sql_alm_det;
        // }

        $ii = $ii + 1;

      }  

      // return $sw;
      return $retorno = ['status' => true, 'message' => 'todo oka ps', 'data' => ''];
      // var_dump($sw);

    } else {
      echo '$idProduc_almacen_resumen es un array vacío o no es un array.';
    }

  }

  //Implementamos un método para editar registros
  public function editar($idepp_x_proyecto_xp, $idtrabajador_xp,$idalmacen_resumen_xp, $id_producto_xp, $fecha_ingreso_xp, $marca_xp, $cantidad_xp)
  {
    $dia = extraer_dia($fecha_ingreso_xp);

    $sql ="UPDATE epp_x_proyecto SET idtrabajador_por_proyecto='$idtrabajador_xp',idalmacen_resumen='$idalmacen_resumen_xp',
    fecha_ingreso='$fecha_ingreso_xp',dia_ingreso='$dia',cantidad='$cantidad_xp',marca='$marca_xp',user_updated= '" . $_SESSION['idusuario'] . "'
    WHERE idepp_x_proyecto='$idepp_x_proyecto_xp'";
    return ejecutarConsulta($sql);

   }

  //Implementamos un método para desactivar categorías
  public function eliminar($idalmacen_detalle,$idalmacen_resumen,$cantidad)
  {
    // var_dump( $idalmacen_detalle,$idalmacen_resumen,$cantidad); die();
    // string(4) "3713" string(4) "1604" string(4) "1.00"

    //ACTUALIZAMOS EL ALMACEN_RESUMEN
    $sql = "UPDATE almacen_resumen SET total_stok= total_stok + $cantidad, total_ingreso= total_ingreso + $cantidad, 
    user_updated='$this->id_usr_sesion' WHERE idalmacen_resumen='$idalmacen_resumen';";

    $ar = ejecutarConsulta($sql,'U');
    if ($ar['status'] == false) {
      return $ar;
    }

    $sql = "DELETE FROM almacen_detalle WHERE idalmacen_detalle='$idalmacen_detalle'";
    return ejecutarConsulta($sql,'D');
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idepp_x_proyecto)
  {
    $sql = "SELECT p.nombre,um.abreviacion, ad.idalmacen_detalle, ad.idalmacen_resumen, ad.idproyecto_destino,
    ad.idtrabajador_por_proyecto, ad.tipo_mov,ad.marca, ad.fecha, ad.cantidad
    FROM almacen_detalle  as ad
    INNER JOIN almacen_resumen as ar on ad.idalmacen_resumen = ar.idalmacen_resumen
    INNER JOIN producto as p  on ar.idproducto=p.idproducto
    INNER JOIN unidad_medida as um on p.idunidad_medida = um.idunidad_medida
    where ad.idalmacen_detalle='$idepp_x_proyecto';"; 
    return ejecutarConsultaSimpleFila($sql);
  }

  //seelect2  - proveedores
  public function trabajador_proyecto($idproyecto)
  {
    $sql = "SELECT t.idtrabajador, t.nombres, tpp.idtrabajador_por_proyecto, t.talla_ropa, t.talla_zapato 
    FROM trabajador_por_proyecto as tpp, trabajador as t 
    WHERE tpp.idtrabajador = t.idtrabajador AND tpp.idproyecto = '$idproyecto' AND tpp.estado='1' AND tpp.estado_delete='1' ORDER BY tpp.orden_trabajador ASC;";
    return ejecutarConsulta($sql);
  }

  function listar_epp_trabajdor($id_tpp,$proyecto){
    $sql="SELECT p.nombre,um.abreviacion, ad.idalmacen_detalle, ad.idalmacen_resumen, ad.idproyecto_destino,
    ad.idtrabajador_por_proyecto, ad.tipo_mov,ad.marca, ad.fecha, ad.cantidad
    FROM almacen_detalle  as ad
    INNER JOIN almacen_resumen as ar on ad.idalmacen_resumen = ar.idalmacen_resumen
    INNER JOIN producto as p  on ar.idproducto=p.idproducto
    INNER JOIN unidad_medida as um on p.idunidad_medida = um.idunidad_medida
    where ad.tipo_mov='EPT' AND  ad.idproyecto_destino = '$proyecto' AND ad.idtrabajador_por_proyecto='$id_tpp'
    ORDER BY ad.idalmacen_detalle DESC;";
    return ejecutarConsulta($sql);
  }

  //--------------------------
  //Implementar un método para listar los registros
  public function select_2_insumos_pp($idproyecto) {

    $sql = "SELECT ar.idalmacen_resumen,ar.idproyecto,ar.idproducto,ar.tipo,ar.total_egreso,ar.total_stok,ar.total_ingreso, p.nombre as nombre_producto,p.modelo, 
    um.nombre_medida as unidad_medida,um.abreviacion, c.nombre as categoria
    FROM almacen_resumen as ar
    INNER JOIN producto as p on ar.idproducto=p.idproducto
    INNER JOIN unidad_medida um on p.idunidad_medida=um.idunidad_medida
    INNER JOIN categoria_insumos_af c on p.idcategoria_insumos_af=c.idcategoria_insumos_af
    where ar.idproyecto='$idproyecto'  and ar.tipo ='EPP' and ar.total_stok>'0' ORDER BY p.nombre ASC;";

    return ejecutarConsultaArray($sql); 
  }

  function marcas_x_insumo($id_insumo, $idproyecto){
    $sql ="SELECT DISTINCT dc.marca, dc.unidad_medida FROM detalle_compra as dc, compra_por_proyecto as cpp 
    WHERE dc.idcompra_proyecto =cpp.idcompra_proyecto and dc.idproducto='$id_insumo' and cpp.idproyecto='$idproyecto';";
    return ejecutarConsultaArray($sql);
    
  }

  //===============================RESUMEN //==================================

  function tabla_resumen_epp($idproyecto) {
    $data = [];

    $sql="SELECT ad.idalmacen_resumen, ad.idproyecto_destino,SUM(ad.cantidad) as cantidad_repartida, ad.marca,p.nombre, p.idproducto, um.abreviacion, ar.total_stok as Saldo, 
    (SUM(ad.cantidad) + ar.total_stok ) as total_stok
        FROM almacen_detalle  as ad
        INNER JOIN almacen_resumen as ar on ad.idalmacen_resumen = ar.idalmacen_resumen
        INNER JOIN producto as p  on ar.idproducto=p.idproducto
        INNER JOIN unidad_medida as um on p.idunidad_medida = um.idunidad_medida
        where ad.tipo_mov='EPT' and ad.idproyecto_destino='$idproyecto' 
        GROUP by  ad.idalmacen_resumen, ad.idproyecto_destino;";

    return ejecutarConsultaArray($sql); //if ($data_resumen['status'] == false) { return  $data_resumen;}

  }

  function tbl_detalle_epp($idproducto,$idproyecto,$marca) {

    $sql ="SELECT ad.idalmacen_detalle, ad.idalmacen_resumen, ad.idproyecto_destino, t.nombres, ad.cantidad, ad.fecha
    FROM almacen_detalle ad
    INNER JOIN trabajador_por_proyecto as tpp on  ad.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto
    INNER JOIN trabajador as t on tpp.idtrabajador = t.idtrabajador
    
    WHERE idalmacen_resumen='$idproducto' and idproyecto_destino='$idproyecto';";
    return ejecutarConsultaArray($sql);
    
  }

}


function extraer_dia($fecha){

  $diaSemana = date("w", strtotime($fecha));
  $nombresDias = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');
  $nombreDia = $nombresDias[$diaSemana];

  return $nombreDia;
  
}


?>
