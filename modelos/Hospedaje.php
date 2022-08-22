<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Hospedaje
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  //Implementamos un método para insertar registros
  public function insertar(  $idproyecto, $fecha_inicio, $fecha_fin, $cantidad, $unidad, $precio_unitario, $precio_parcial, $descripcion, $forma_pago, $tipo_comprobante, $fecha_comprobante, $nro_comprobante, $subtotal, $igv, $val_igv, $tipo_gravada, $comprobante, $ruc, $razon_social, $direccion  ) {
    if ($tipo_comprobante == 'Factura' || $tipo_comprobante == 'Boleta') {
    } else {
      $ruc = '';
      $razon_social = '';
      $direccion = '';
    }

    $sql_1 = "SELECT  ruc, razon_social, fecha_comprobante, tipo_comprobante, numero_comprobante, precio_parcial, descripcion, estado, estado_delete
		FROM hospedaje WHERE ruc = '$ruc' and tipo_comprobante = '$tipo_comprobante' AND numero_comprobante = '$nro_comprobante';";
    $prov = ejecutarConsultaArray($sql_1);
    if ($prov['status'] == false) {
      return $prov;
    }

    if (empty($prov['data']) || $tipo_comprobante == 'Ninguno') {
      $sql = "INSERT INTO hospedaje (idproyecto,fecha_inicio,fecha_fin,cantidad,unidad,precio_unitario,precio_parcial,descripcion,forma_de_pago,tipo_comprobante,fecha_comprobante,numero_comprobante,subtotal,igv,val_igv,tipo_gravada,comprobante,ruc,razon_social,direccion) 
			VALUES ('$idproyecto','$fecha_inicio','$fecha_fin','$cantidad','$unidad','$precio_unitario','$precio_parcial','$descripcion','$forma_pago','$tipo_comprobante','$fecha_comprobante','$nro_comprobante','$subtotal','$igv','$val_igv','$tipo_gravada','$comprobante','$ruc','$razon_social','$direccion')";
      return ejecutarConsulta($sql);
    } else {
      $info_repetida = '';

      foreach ($prov['data'] as $key => $value) {
        $info_repetida .= '<li class="text-left font-size-13px">
          <span class="font-size-18px text-danger"><b >' . $value['tipo_comprobante'] . ': </b> ' . $value['numero_comprobante'] . '</span><br>
          <b>Razón Social: </b>' . $value['razon_social'] . '<br>
          <b>Ruc: </b>' . $value['ruc'] . '<br>          
          <b>Fecha: </b>' . format_d_m_a($value['fecha_comprobante']) . '<br>
          <b>Costo: </b>' . number_format($value['precio_parcial'], 2, '.', ',') . '<br>
          <b>Descripción: </b> <textarea cols="30" rows="1" class="textarea_datatable" readonly="">' . $value['descripcion'] . '</textarea><br>
          <b>Papelera: </b>' . ($value['estado'] == 0 ? '<i class="fas fa-check text-success"></i> SI' : '<i class="fas fa-times text-danger"></i> NO') . ' <b>|</b>
          <b>Eliminado: </b>' . ($value['estado_delete'] == 0 ? '<i class="fas fa-check text-success"></i> SI' : '<i class="fas fa-times text-danger"></i> NO') .
          '<br>	<hr class="m-t-2px m-b-2px">
				</li>';
      }
      return $sw = ['status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ol>' . $info_repetida . '</ol>', 'id_tabla' => ''];
    }
  }

  //Implementamos un método para editar registros
  public function editar( $idhospedaje, $idproyecto, $fecha_inicio, $fecha_fin, $cantidad, $unidad, $precio_unitario, $precio_parcial, $descripcion, $forma_pago, $tipo_comprobante, $fecha_comprobante, $nro_comprobante, $subtotal, $igv, $val_igv, $tipo_gravada, $comprobante, $ruc, $razon_social, $direccion  ) {
    if ($tipo_comprobante == 'Factura' || $tipo_comprobante == 'Boleta') {
    } else {
      $ruc = '';
      $razon_social = '';
      $direccion = '';
    }

    $sql = "UPDATE hospedaje SET 
		idproyecto='$idproyecto',
		fecha_inicio='$fecha_inicio',
		fecha_fin='$fecha_fin',
		cantidad='$cantidad',
		unidad='$unidad',
		precio_unitario='$precio_unitario',
		precio_parcial='$precio_parcial',
		descripcion='$descripcion',
		forma_de_pago='$forma_pago',
		tipo_comprobante='$tipo_comprobante',
		fecha_comprobante='$fecha_comprobante',
		numero_comprobante='$nro_comprobante',
		subtotal='$subtotal',
		igv='$igv',
		val_igv='$val_igv',
		tipo_gravada='$tipo_gravada',
		comprobante='$comprobante',
		ruc='$ruc',
		razon_social='$razon_social',
		direccion='$direccion'

		WHERE idhospedaje='$idhospedaje'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idhospedaje)  {
    $sql = "UPDATE hospedaje SET estado='0' WHERE idhospedaje ='$idhospedaje'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para activar categorías
  public function activar($idhospedaje)  {
    $sql = "UPDATE hospedaje SET estado='1' WHERE idhospedaje ='$idhospedaje'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para desactivar categorías
  public function eliminar($idhospedaje)  {
    $sql = "UPDATE hospedaje SET estado_delete='0' WHERE idhospedaje ='$idhospedaje'";
    return ejecutarConsulta($sql);
  }
  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idhospedaje) {
    $sql = "SELECT*FROM hospedaje   
		WHERE idhospedaje ='$idhospedaje'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para listar los registros
  public function tabla_principal($idproyecto, $fecha_1, $fecha_2, $id_proveedor, $comprobante) {
    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = "";

    if (!empty($fecha_1) && !empty($fecha_2)) {
      $filtro_fecha = "AND fecha_comprobante BETWEEN '$fecha_1' AND '$fecha_2'";
    } elseif (!empty($fecha_1)) {
      $filtro_fecha = "AND fecha_comprobante = '$fecha_1'";
    } elseif (!empty($fecha_2)) {
      $filtro_fecha = "AND fecha_comprobante = '$fecha_2'";
    }

    if (empty($id_proveedor)) { } elseif ($id_proveedor =='vacio') { $filtro_proveedor = "AND ruc IN ('', NULL)"; } else { $filtro_proveedor = "AND ruc = '$id_proveedor'"; }

    if (empty($comprobante)) { } else { $filtro_comprobante = "AND tipo_comprobante = '$comprobante'"; }

    $sql = "SELECT * FROM hospedaje 
		WHERE idproyecto='$idproyecto' AND estado_delete='1' AND estado='1' $filtro_proveedor $filtro_comprobante $filtro_fecha
		ORDER BY fecha_comprobante DESC";
    return ejecutarConsulta($sql);
  }

  //total
  public function total($idproyecto, $fecha_1, $fecha_2, $id_proveedor, $comprobante) {

    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = "";

    if (!empty($fecha_1) && !empty($fecha_2)) {
      $filtro_fecha = "AND fecha_comprobante BETWEEN '$fecha_1' AND '$fecha_2'";
    } elseif (!empty($fecha_1)) {
      $filtro_fecha = "AND fecha_comprobante = '$fecha_1'";
    } elseif (!empty($fecha_2)) {
      $filtro_fecha = "AND fecha_comprobante = '$fecha_2'";
    }

    if (empty($id_proveedor)) { } elseif ($id_proveedor =='vacio') { $filtro_proveedor = "AND ruc IN ('', NULL)"; } else { $filtro_proveedor = "AND ruc = '$id_proveedor'"; }

    if (empty($comprobante)) {  } else {  $filtro_comprobante = "AND tipo_comprobante = '$comprobante'";  }

    $sql = "SELECT SUM(precio_parcial) as precio_parcial, SUM(subtotal) as subtotal, SUM(igv) as igv
		FROM hospedaje 
		WHERE idproyecto='$idproyecto' AND estado='1' AND estado_delete='1' $filtro_proveedor $filtro_comprobante $filtro_fecha ";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Seleccionar un comprobante
  public function comprobante_hospedaje($idhospedaje) {
    $sql = "SELECT comprobante FROM hospedaje WHERE idhospedaje='$idhospedaje'";
    return ejecutarConsulta($sql);
  }

  public function select2Proveedor($idproyecto) {
    $sql2 = "SELECT ruc, razon_social  FROM hospedaje 
    WHERE estado = '1' AND estado_delete = '1' AND idproyecto = '$idproyecto' AND ruc != '' AND razon_social != '' GROUP BY ruc;";
    return ejecutarConsultaArray($sql2);    
  }
}

?>
