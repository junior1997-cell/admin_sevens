<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Epp
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }
  //$idotro_gasto,$idproyecto,$fecha_viaje,$tipo_viajero,$tipo_ruta,$cantidad,$precio_unitario,$precio_parcial,$ruta,$descripcion,$foto2
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
  public function editar($idepp,$idproyecto,$idtrabajador_por_proyecto,$fecha_g,$id_insumo,$cantidad)
  {

  //   if ($tipo_comprobante =='Factura' || $tipo_comprobante =='Boleta' ) { } else { $ruc =''; $razon_social =''; $direccion =''; }
    
  //   $sql = "UPDATE otro_gasto SET 
	// 	idproyecto='$idproyecto',
	// 	fecha_g='$fecha_g',
	// 	costo_parcial='$precio_parcial',
	// 	subtotal='$subtotal',
	// 	igv='$igv',
	// 	val_igv='$val_igv',
	// 	tipo_gravada='$tipo_gravada',
	// 	descripcion='$descripcion',
	// 	forma_de_pago='$forma_pago',
	// 	tipo_comprobante='$tipo_comprobante',
	// 	numero_comprobante='$nro_comprobante',
	// 	comprobante='$comprobante',
  //   ruc='$ruc',
  //   razon_social='$razon_social',
  //   direccion='$direccion',
  //   glosa='$glosa'

	// 	WHERE idotro_gasto='$idotro_gasto'";
  //   return ejecutarConsulta($sql);
   }

  //Implementamos un método para desactivar categorías
  public function desactivar($idotro_gasto)
  {
    $sql = "UPDATE otro_gasto SET estado='0' WHERE idotro_gasto ='$idotro_gasto'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para desactivar categorías
  public function eliminar($idotro_gasto)
  {
    $sql = "UPDATE otro_gasto SET estado_delete='0' WHERE idotro_gasto ='$idotro_gasto'";
    return ejecutarConsulta($sql);
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idotro_gasto)
  {
    $sql = "SELECT*FROM otro_gasto   
		WHERE idotro_gasto ='$idotro_gasto'";
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

}

function extraer_dia($fecha){

  $diaSemana = date("w", strtotime($fecha));
  $nombresDias = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');
  $nombreDia = $nombresDias[$diaSemana];

  return $nombreDia;
  
}

?>
