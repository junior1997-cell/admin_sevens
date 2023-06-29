<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Almacen
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  //Implementar un método para listar los registros
  public function tbla_principal($idproyecto) {

    $resumen_producto = [];
    $sql = "SELECT cpp.idproyecto, cpp.idcompra_proyecto, dc.iddetalle_compra, dc.idproducto, um.nombre_medida,  um.nombre_medida, um.abreviacion,
		pr.nombre AS nombre_producto, pr.modelo, pr.marca, cg.idclasificacion_grupo, cg.nombre as grupo
    
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

?>
