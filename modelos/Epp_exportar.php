<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Epp_exportar
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  //seelect2  - proveedores
  public function datos_epp_x_trabajador_unico($idproyecto,$id_tpp)
  {
    $resumen_producto = [];

    $sql1 = "SELECT t.idtrabajador, t.nombres, tpp.idtrabajador_por_proyecto, t.talla_ropa, t.talla_zapato 
    FROM trabajador_por_proyecto as tpp, trabajador as t 
    WHERE tpp.idtrabajador = t.idtrabajador AND tpp.idproyecto = '$idproyecto' and tpp.idtrabajador_por_proyecto='$id_tpp' ";
    $datostrabajador =  ejecutarConsultaSimpleFila($sql1); if ($datostrabajador['status'] == false) { return $datostrabajador; }

    $sql2="SELECT ap.idalmacen_x_proyecto, ap.idproducto, ap.idtrabajador_por_proyecto, ap.fecha_ingreso, ap.dia_ingreso, ap.cantidad, ap.marca, p.nombre as producto, um.nombre_medida, um.abreviacion
    FROM almacen_x_proyecto as ap, producto as p ,unidad_medida AS um 
    WHERE ap.idproducto=p.idproducto AND  um.idunidad_medida  = p.idunidad_medida AND ap.idtrabajador_por_proyecto='$id_tpp' AND ap.estado=1 AND ap.estado_delete=1;";
    $datos_EPP= ejecutarConsultaArray($sql2); if ($datos_EPP['status'] == false) { return $datos_EPP; }

    return $retorno = ['status' => true, 'e' => $datostrabajador, 'ee' => $datos_EPP, 'message' => 'todo bien'];


  }


}


?>
