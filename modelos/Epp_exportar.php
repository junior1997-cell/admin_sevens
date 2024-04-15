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

    $sql2="SELECT epp.idalmacen_detalle, p.nombre as producto, epp.marca, um.nombre_medida AS nombre_und, um.abreviacion, epp.cantidad, epp.fecha
    FROM almacen_detalle as epp 
    INNER JOIN almacen_resumen as ar on ar.idalmacen_resumen = epp.idalmacen_resumen 
    INNER JOIN producto AS p ON p.idproducto=ar.idproducto
    INNER JOIN unidad_medida AS um ON um.idunidad_medida = p.idunidad_medida
    WHERE epp.idtrabajador_por_proyecto='$id_tpp' AND ar.idproyecto='$idproyecto'and epp.estado='1' and epp.estado_delete='1'; ";

    $datos_EPP= ejecutarConsultaArray($sql2); if ($datos_EPP['status'] == false) { return $datos_EPP; }  

    return $retorno = ['status' => true, 'e' => $datostrabajador, 'ee' => $datos_EPP, 'message' => 'todo bien'];

  }

  function datos_epp_trabajador_full($idproyecto) {
    $data = [];
    $sql="SELECT t.idtrabajador, t.nombres,t.numero_documento, tpp.idtrabajador_por_proyecto
    FROM trabajador_por_proyecto as tpp, trabajador as t 
    WHERE tpp.idtrabajador = t.idtrabajador AND tpp.idproyecto = '$idproyecto' AND tpp.estado='1' AND tpp.estado_delete='1' ORDER BY tpp.orden_trabajador ASC;";
    $datos_EPP= ejecutarConsultaArray($sql); if ($datos_EPP['status'] == false) { return $datos_EPP; } 
    
    foreach ($datos_EPP['data'] as $key => $reg) {
      
      $id_tpp = $reg['idtrabajador_por_proyecto'];

      $sql_detalle="SELECT epp.idalmacen_detalle, p.nombre as producto, epp.marca, um.nombre_medida AS nombre_und, um.abreviacion, epp.cantidad, epp.fecha as fecha_ingreso 
            FROM almacen_detalle as epp 
            INNER JOIN almacen_resumen as ar on ar.idalmacen_resumen = epp.idalmacen_resumen 
            INNER JOIN producto AS p ON p.idproducto=ar.idproducto
            INNER JOIN unidad_medida AS um ON um.idunidad_medida = p.idunidad_medida
            WHERE epp.idtrabajador_por_proyecto='$id_tpp' AND ar.idproyecto='$idproyecto'and epp.estado='1' and epp.estado_delete='1';";
      
      $epps = ejecutarConsultaArray($sql_detalle); if ($epps['status'] == false) { return  $epps;}
      
      $data[] = Array(
        'idtrabajador'              => $reg['idtrabajador'],
        'nombres'                   => $reg['nombres'],
        'numero_documento'          => $reg['numero_documento'],
        'idtrabajador_por_proyecto' => $reg['idtrabajador_por_proyecto'],
        'detalle_epp'               => $epps,
      );

    }
    // var_dump($data);die();
    return $retorno = ['status'=> true, 'message' => 'Salió todo ok,', 'data' => $data ];
  }


}


?>
