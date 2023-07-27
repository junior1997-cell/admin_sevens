<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Epp
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }
  //$idalmacen_x_proyecto,$idproyecto,$fecha_viaje,$tipo_viajero,$tipo_ruta,$cantidad,$precio_unitario,$precio_parcial,$ruta,$descripcion,$foto2
  //Implementamos un método para insertar registros
  public function insertar($idproyecto,$idtrabajador_por_proyecto,$fecha_g,$id_insumo,$cantidad,$marca)
  {

    $dia = extraer_dia($fecha_g);

    // Verificar si $id_insumo es un array y si contiene datos
    
    if (is_array($id_insumo) && !empty($id_insumo)) {

      $num_elementos = 0;
      $sw = true;     

      while ($num_elementos < count($id_insumo)) {

        $sql_detalle = "INSERT INTO almacen_x_proyecto(idproducto, idtrabajador_por_proyecto, fecha_ingreso, dia_ingreso, cantidad, marca,user_created) 
        VALUES ('$id_insumo[$num_elementos]','$idtrabajador_por_proyecto','$fecha_g','$dia','$cantidad[$num_elementos]','$marca[$num_elementos]','" . $_SESSION['idusuario'] . "')";
        $sw = ejecutarConsulta_retornarID($sql_detalle); if ($sw['status'] == false) {    return $sw ; }

        //add registro en nuestra bitacora
        $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('almacen_x_proyecto','".$sw['data']."','Registro de EPP con num  ".$sw['data']."','" . $_SESSION['idusuario'] . "')";
        $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   

        $num_elementos = $num_elementos + 1;

      }  

      return $sw;

    } else {
      echo '$id_insumo es un array vacío o no es un array.';
    }

  }

  //Implementamos un método para editar registros
  public function editar($idalmacen_x_proyecto_xp, $idtrabajador_xp, $id_producto_xp, $fecha_ingreso_xp, $marca_xp, $cantidad_xp)
  {
    $dia = extraer_dia($fecha_ingreso_xp);

    $sql ="UPDATE almacen_x_proyecto 
    SET idproducto='$id_producto_xp',idtrabajador_por_proyecto='$idtrabajador_xp', fecha_ingreso='$fecha_ingreso_xp',dia_ingreso='$dia',
    cantidad='$cantidad_xp',marca='$marca_xp', user_updated= '" . $_SESSION['idusuario'] . "' WHERE idalmacen_x_proyecto='$idalmacen_x_proyecto_xp'";
    return ejecutarConsulta($sql);


   }

  //Implementamos un método para desactivar categorías
  public function desactivar($idalmacen_x_proyecto)
  {
    $sql = "UPDATE almacen_x_proyecto SET estado='0' WHERE idalmacen_x_proyecto ='$idalmacen_x_proyecto'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para desactivar categorías
  public function eliminar($idalmacen_x_proyecto)
  {
    $sql = "UPDATE almacen_x_proyecto SET estado_delete='0' WHERE idalmacen_x_proyecto ='$idalmacen_x_proyecto'";
    return ejecutarConsulta($sql);
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idalmacen_x_proyecto)
  {
    $sql = "SELECT axp.idalmacen_x_proyecto, axp.idproducto, axp.idtrabajador_por_proyecto, axp.fecha_ingreso, axp.dia_ingreso, axp.cantidad, axp.marca, p.nombre FROM almacen_x_proyecto as axp, producto as p 
    WHERE axp.idproducto=p.idproducto and axp.idalmacen_x_proyecto ='$idalmacen_x_proyecto';"; 
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

  function listar_epp_trabajdor($id_tpp){
    $sql="SELECT ap.idalmacen_x_proyecto, ap.idproducto, ap.idtrabajador_por_proyecto, ap.fecha_ingreso, ap.dia_ingreso, ap.cantidad, ap.marca, p.nombre as producto
    FROM almacen_x_proyecto as ap, producto as p 
    WHERE ap.idproducto=p.idproducto AND ap.idtrabajador_por_proyecto='$id_tpp' AND ap.estado=1 AND ap.estado_delete=1;";
    return ejecutarConsulta($sql);
  }

  //--------------------------
  //Implementar un método para listar los registros
  public function select_2_insumos_pp($idproyecto) {

    $resumen_producto = [];
    $sql = "SELECT cpp.idproyecto, cpp.idcompra_proyecto, dc.iddetalle_compra, dc.idproducto, um.nombre_medida,  um.nombre_medida, um.abreviacion,
    pr.nombre AS nombre_producto, pr.modelo, dc.marca, cg.idclasificacion_grupo, cg.nombre as grupo
    
    FROM proyecto AS p, compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr, clasificacion_grupo AS cg, unidad_medida AS um 
    WHERE p.idproyecto = cpp.idproyecto AND cpp.idcompra_proyecto = dc.idcompra_proyecto AND dc.idproducto = pr.idproducto
    AND um.idunidad_medida  = pr.idunidad_medida AND dc.idclasificacion_grupo = cg.idclasificacion_grupo
    AND cpp.idproyecto = '$idproyecto'  AND pr.idcategoria_insumos_af = '1' 
    AND cpp.estado = '1' AND cpp.estado_delete = '1' GROUP BY dc.idproducto ORDER BY pr.nombre ASC;";

    $producto = ejecutarConsultaArray($sql); if ($producto['status'] == false) { return $producto; }

    foreach ($producto['data'] as $key => $value) {

      $resumen_producto[] = [
        'idproyecto'        => $value['idproyecto'],
        'idcompra_proyecto' => $value['idcompra_proyecto'],
        'iddetalle_compra'  => $value['iddetalle_compra'],
        'idproducto'        => $value['idproducto'],
        'nombre_medida'     => $value['nombre_medida'],
        'abreviacion'       => $value['abreviacion'],
        'nombre_producto'   => $value['nombre_producto'],
        'modelo'            => $value['modelo'],
        'marca'             => $value['marca'],
        'idclasificacion_grupo'=> $value['idclasificacion_grupo'],
        'grupo'             => $value['grupo'],
      ];
    }
    return $retorno = ['status' => true, 'data' => $resumen_producto, 'message' => 'todo bien'];
  }

  function marcas_x_insumo($id_insumo, $idproyecto){
    $sql ="SELECT DISTINCT dc.marca, dc.unidad_medida FROM detalle_compra as dc, compra_por_proyecto as cpp 
    WHERE dc.idcompra_proyecto =cpp.idcompra_proyecto and dc.idproducto='$id_insumo' and cpp.idproyecto='$idproyecto';";
    return ejecutarConsultaArray($sql);
    
  }

  //===============================RESUMEN //==================================

function tabla_resumen_epp($idproyecto) {
  $sql="SELECT p.idproducto, p.nombre, ap.marca, um.nombre_medida,um.abreviacion, sum(ap.cantidad) as cantidad_rapartida FROM almacen_x_proyecto as ap, trabajador_por_proyecto as tpp, producto AS p, unidad_medida as um
  WHERE ap.idtrabajador_por_proyecto=tpp.idtrabajador_por_proyecto AND ap.idproducto = p.idproducto AND um.idunidad_medida=p.idunidad_medida
  AND tpp.idproyecto='$idproyecto' and ap.estado=1  AND ap.estado_delete=1  GROUP by ap.marca,ap.idproducto;";
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
