<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Epp
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }
  //$idepp_x_proyecto,$idproyecto,$fecha_viaje,$tipo_viajero,$tipo_ruta,$cantidad,$precio_unitario,$precio_parcial,$ruta,$descripcion,$foto2
  //Implementamos un método para insertar registros
  public function insertar($idproyecto,$idtrabajador_por_proyecto,$data_idalmacen_resumen,$fecha_g,$id_insumo,$cantidad,$marca)
  {
    $dia = extraer_dia($fecha_g);

    // Verificar si $id_insumo es un array y si contiene datos
    
    if (is_array($id_insumo) && !empty($id_insumo)) {

      $num_elementos = 0;
      $sw = true;     

      while ($num_elementos < count($id_insumo)) {

        $sql_detalle = "INSERT INTO epp_x_proyecto(idtrabajador_por_proyecto,idalmacen_resumen, fecha_ingreso, dia_ingreso, cantidad, marca,user_created) 
        VALUES ('$idtrabajador_por_proyecto','$data_idalmacen_resumen[$num_elementos]','$fecha_g','$dia','$cantidad[$num_elementos]','$marca[$num_elementos]','" . $_SESSION['idusuario'] . "')";
        $sw = ejecutarConsulta_retornarID($sql_detalle); if ($sw['status'] == false) {    return $sw ; }

        //add registro en nuestra bitacora
        $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('epp_x_proyecto','".$sw['data']."','Registro de EPP con num  ".$sw['data']."','" . $_SESSION['idusuario'] . "')";
        $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   

        $num_elementos = $num_elementos + 1;

      }  

      return $sw;

      // var_dump($sw);

    } else {
      echo '$id_insumo es un array vacío o no es un array.';
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
  public function desactivar($idepp_x_proyecto)
  {
    $sql = "UPDATE epp_x_proyecto SET estado='0' WHERE idepp_x_proyecto ='$idepp_x_proyecto'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para desactivar categorías
  public function eliminar($idepp_x_proyecto)
  {
    $sql = "UPDATE epp_x_proyecto SET estado_delete='0' WHERE idepp_x_proyecto ='$idepp_x_proyecto'";
    return ejecutarConsulta($sql);
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idepp_x_proyecto)
  {
    $sql = "SELECT axp.idepp_x_proyecto,axp.idalmacen_resumen, ar.idproducto, axp.idtrabajador_por_proyecto, axp.fecha_ingreso, axp.dia_ingreso, axp.cantidad, axp.marca, p.nombre , um.nombre_medida, um.abreviacion
    FROM epp_x_proyecto as axp
    INNER JOIN almacen_resumen as ar on ar.idalmacen_resumen=axp.idalmacen_resumen
    INNER JOIN producto as p on p.idproducto=ar.idproducto
    INNER JOIN unidad_medida as um on um.idunidad_medida=p.idunidad_medida
    WHERE axp.idepp_x_proyecto ='$idepp_x_proyecto';"; 
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
    $sql="SELECT epp.idepp_x_proyecto, p.nombre as producto, epp.marca, um.nombre_medida AS nombre_und, um.abreviacion, epp.cantidad, epp.fecha_ingreso
    FROM epp_x_proyecto as epp 
    INNER JOIN almacen_resumen as ar on ar.idalmacen_resumen = epp.idalmacen_resumen 
    INNER JOIN producto AS p ON P.idproducto=ar.idproducto
    INNER JOIN unidad_medida AS um ON UM.idunidad_medida = P.idunidad_medida
    WHERE epp.idtrabajador_por_proyecto='$id_tpp' AND ar.idproyecto='$proyecto'and epp.estado='1' and epp.estado_delete='1';";
    return ejecutarConsulta($sql);
  }

  //--------------------------
  //Implementar un método para listar los registros
  public function select_2_insumos_pp($idproyecto) {

    $sql = "SELECT ar.idalmacen_resumen, ar.idproyecto,p.idproducto, ar.saldo, p.nombre as nombre_producto, um.abreviacion,  p.modelo    
    FROM almacen_resumen as ar
    inner join producto as p on ar.idproducto =p.idproducto 
    inner join unidad_medida as um on um.idunidad_medida = p.idunidad_medida
    WHERE ar.idproyecto = '$idproyecto' ORDER BY p.nombre ASC;";

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

    $sql="SELECT epp.idalmacen_resumen,P.idproducto , p.nombre as producto, epp.marca, um.nombre_medida AS nombre_und, um.abreviacion, SUM(epp.cantidad) AS cantidad
    FROM epp_x_proyecto as epp 
    INNER JOIN almacen_resumen as ar on ar.idalmacen_resumen = epp.idalmacen_resumen 
    INNER JOIN producto AS p ON P.idproducto=ar.idproducto
    INNER JOIN unidad_medida AS um ON UM.idunidad_medida = P.idunidad_medida
    WHERE ar.idproyecto='$idproyecto'and epp.estado='1' and epp.estado_delete='1' GROUP BY epp.marca,p.idproducto;";

    $data_resumen = ejecutarConsultaArray($sql); if ($data_resumen['status'] == false) { return  $data_resumen;}

    foreach ($data_resumen['data'] as $key => $value) {

      $idalmacen_resumen = $value['idalmacen_resumen'];
      $marca = $value['marca'];

      $sql_detalle="SELECT SUM(cantidad) as total_salida FROM almacen_salida as al where al.idalmacen_resumen='$idalmacen_resumen' and al.marca='$marca'";
      
      $total_cant_salida = ejecutarConsultaSimpleFila($sql_detalle); if ($total_cant_salida['status'] == false) { return  $total_cant_salida;}

      $total_c = (empty($total_cant_salida['data']) ? 0 : ( empty($total_cant_salida['data']['total_salida']) ? 0 : floatval($total_cant_salida['data']['total_salida'])) ); 

      $calculando=$total_c- floatval($value['cantidad']); 

      $data[] = Array(
        'idalmacen_resumen'  => $value['idalmacen_resumen'],
        'idproducto'         => $value['idproducto'],
        'nombre'             => $value['producto'],
        'marca'              => $value['marca'],
        'nombre_medida'      => $value['nombre_und'],
        'abreviacion'        => $value['abreviacion'],
        'cantidad_rapartida' => $value['cantidad'],
        'cantidad_total'     => $total_c,
        'cantidad_q_queda'   => $calculando,
      ); 

    }
    // var_dump($data);die();
    return $retorno = ['status'=> true, 'message' => 'Salió todo ok,', 'data' => $data ];

  }

  function tbl_detalle_epp($idproducto,$idproyecto,$marca) {
    // var_dump();die();
    $sql ="SELECT ap.idepp_x_proyecto ,ap.idtrabajador_por_proyecto, ar.idproducto , ap.fecha_ingreso, ap.dia_ingreso, ap.cantidad, ap.marca, t.nombres 
    FROM epp_x_proyecto as ap 
    INNER JOIN almacen_resumen as ar on ar.idalmacen_resumen=ap.idalmacen_resumen 
    INNER JOIN trabajador_por_proyecto as tpp on tpp.idtrabajador_por_proyecto = ap.idtrabajador_por_proyecto 
    INNER JOIN trabajador as t on t.idtrabajador=tpp.idtrabajador 
    where ar.idproyecto='$idproyecto' AND ar.idproducto='$idproducto' AND ap.marca='$marca';";
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
