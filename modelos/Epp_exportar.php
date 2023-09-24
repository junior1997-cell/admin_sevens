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

    $sql2="SELECT epp.idepp_x_proyecto, p.nombre as producto, epp.marca, um.nombre_medida AS nombre_und, um.abreviacion, epp.cantidad, epp.fecha_ingreso
    FROM epp_x_proyecto as epp 
    INNER JOIN almacen_resumen as ar on ar.idalmacen_resumen = epp.idalmacen_resumen 
    INNER JOIN producto AS p ON P.idproducto=ar.idproducto
    INNER JOIN unidad_medida AS um ON UM.idunidad_medida = P.idunidad_medida
    WHERE epp.idtrabajador_por_proyecto='$id_tpp' AND ar.idproyecto='$idproyecto'and epp.estado='1' and epp.estado_delete='1'; ";

    $datos_EPP= ejecutarConsultaArray($sql2); if ($datos_EPP['status'] == false) { return $datos_EPP; }  

    return $retorno = ['status' => true, 'e' => $datostrabajador, 'ee' => $datos_EPP, 'message' => 'todo bien'];


  }


}


?>
