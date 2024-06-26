<?php 
  //Incluímos inicialmente la conexión a la base de datos
  require "../config/Conexion_v2.php";

  Class Ajax_general
  {
    //Implementamos nuestro constructor
    public function __construct()
    {

    }	 

    //CAPTURAR PERSONA  DE RENIEC 
    public function datos_reniec($dni) { 

      $url = "https://dniruc.apisperu.com/api/v1/dni/".$dni."?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Imp1bmlvcmNlcmNhZG9AdXBldS5lZHUucGUifQ.bzpY1fZ7YvpHU5T83b9PoDxHPaoDYxPuuqMqvCwYqsM";
      //  Iniciamos curl
      $curl = curl_init();
      // Desactivamos verificación SSL
      curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, 0 );
      // Devuelve respuesta aunque sea falsa
      curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
      // Especificamo los MIME-Type que son aceptables para la respuesta.
      curl_setopt( $curl, CURLOPT_HTTPHEADER, [ 'Accept: application/json' ] );
      // Establecemos la URL
      curl_setopt( $curl, CURLOPT_URL, $url );
      // Ejecutmos curl
      $json = curl_exec( $curl );
      // Cerramos curl
      curl_close( $curl );

      $respuestas = json_decode( $json, true );

      return $respuestas;
    }

    //CAPTURAR PERSONA  DE RENIEC
    public function datos_sunat($ruc)	{ 
      $url = "https://dniruc.apisperu.com/api/v1/ruc/".$ruc."?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Imp1bmlvcmNlcmNhZG9AdXBldS5lZHUucGUifQ.bzpY1fZ7YvpHU5T83b9PoDxHPaoDYxPuuqMqvCwYqsM";
      //  Iniciamos curl
      $curl = curl_init();
      // Desactivamos verificación SSL
      curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, 0 );
      // Devuelve respuesta aunque sea falsa
      curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
      // Especificamo los MIME-Type que son aceptables para la respuesta.
      curl_setopt( $curl, CURLOPT_HTTPHEADER, [ 'Accept: application/json' ] );
      // Establecemos la URL
      curl_setopt( $curl, CURLOPT_URL, $url );
      // Ejecutmos curl
      $json = curl_exec( $curl );
      // Cerramos curl
      curl_close( $curl );

      $respuestas = json_decode( $json, true );

      return $respuestas;    	

    }
    /* ══════════════════════════════════════ B I T A C O R A  ══════════════════════════════════════ */
    public function tabla_bitacora($nombre_tabla, $id_tabla){
      $sql = "SELECT bbd.idbitacora_bd, bbd.nombre_tabla, bbd.id_tabla, bbd.accion, bbd.sql_d, bbd.id_user, 
      bbd.estado, bbd.estado_delete, DATE_FORMAT(bbd.created_at, '%d/%m/%Y %h:%i %p') as created_at, u.login as responsable
      FROM bitacora_bd as bbd
      INNER JOIN usuario as u ON u.idusuario = bbd.id_user
      WHERE bbd.id_tabla = '$id_tabla' AND bbd.nombre_tabla = '$nombre_tabla';";
      return ejecutarConsulta($sql);
    }
    /* ══════════════════════════════════════ T R A B A J A D O R ══════════════════════════════════════ */

    public function select2_trabajador(){
      $sql = "SELECT idtrabajador as id, nombres as nombre, tipo_documento as documento, numero_documento, imagen_perfil 
      FROM trabajador WHERE estado = '1' AND estado_delete = '1' ORDER BY nombres ASC;";
      return ejecutarConsulta($sql);
    }

    public function select2_trabajador_por_proyecto($id_proyecto){
      $sql = "SELECT t.idtrabajador as id, t.nombres, t.tipo_documento, t.numero_documento, t.ruc , t.imagen_perfil 
      FROM trabajador  as t
      LEFT JOIN  trabajador_por_proyecto as tpp ON t.idtrabajador = tpp.idtrabajador and tpp.idproyecto = '$id_proyecto'
      WHERE t.estado='1' AND t.estado_delete = '1' AND tpp.idtrabajador IS NULL ORDER BY t.nombres ASC;";
      return ejecutarConsulta($sql);
    }

    public function select2_tipo_trabajador() {
      $sql="SELECT * FROM tipo_trabajador where estado='1' AND estado_delete = '1' ORDER BY nombre ASC";
      return ejecutarConsulta($sql);		
    }

    public function select2_cargo_trabajador_id($id_tipo) {
      $sql = "SELECT * FROM cargo_trabajador WHERE idtipo_trabajador='$id_tipo' AND estado='1' AND estado_delete = '1' ORDER BY nombre ASC";
      return ejecutarConsulta($sql);
    }

    public function select2_ocupacion_trabajador()  {
      $sql="SELECT idocupacion AS id, nombre_ocupacion AS nombre FROM ocupacion where idocupacion > 1 and estado = '1' AND estado_delete = '1' ORDER BY nombre_ocupacion ASC;";
		  return ejecutarConsulta($sql);
    }    

    /* ══════════════════════════════════════  D E S E M P E Ñ O ══════════════════════════════════════ */

    public function select2_desempenio_trabajador()  {
      $sql="SELECT iddesempenio AS id, nombre_desempenio AS nombre FROM desempenio where iddesempenio > 1 and estado = '1' AND estado_delete = '1' ORDER BY nombre_desempenio ASC;";
		  return ejecutarConsulta($sql);
    }

    public function select2_desempenio_por_trabajdor($id_trabajador) {
      $sql = "SELECT doc.idtrabajador, doc.iddesempenio, d.nombre_desempenio FROM detalle_desempenio as doc, desempenio as d 
      WHERE doc.iddesempenio = d.iddesempenio AND doc.idtrabajador = '$id_trabajador';";
      return ejecutarConsulta($sql);
    }
    

    /* ══════════════════════════════════════ P R O V E E D O R  ══════════════════════════════════════ */

    public function select2_proveedor() {
      $sql = "SELECT idproveedor, razon_social, ruc FROM proveedor WHERE estado='1' AND estado_delete = '1' AND idproveedor > 1 ORDER BY razon_social ASC;";
      return ejecutarConsulta($sql);
    }

    public function select2_proveedor_filtro() {
      $sql = "SELECT idproveedor, razon_social, ruc FROM proveedor WHERE idproveedor > 1 ORDER BY razon_social ASC;";
      return ejecutarConsulta($sql);
    }

    public function select2ProveedorFiltroCompra($idproyecto) {
      $sql = "SELECT p.*
      FROM compra_por_proyecto as cpp
      INNER JOIN proveedor as p ON p.idproveedor = cpp.idproveedor
      WHERE p.idproveedor > 1 and cpp.idproyecto = '$idproyecto' and cpp.estado = '1' AND cpp.estado_delete = '1'
      GROUP BY cpp.idproveedor 
      ORDER BY p.razon_social ASC;";
      return ejecutarConsulta($sql);
    }

    /* ══════════════════════════════════════ B A N C O ══════════════════════════════════════ */

    public function select2_banco() {
      $sql = "SELECT idbancos as id, nombre, alias, icono FROM bancos WHERE estado='1' AND estado_delete = '1' AND idbancos > 1 ORDER BY nombre ASC;";
      return ejecutarConsulta($sql);
    }

    public function formato_banco($idbanco){
      $sql="SELECT nombre, formato_cta, formato_cci, formato_detracciones FROM bancos WHERE estado='1' AND idbancos = '$idbanco';";
      return ejecutarConsultaSimpleFila($sql);		
    }

    /* ══════════════════════════════════════ C O L O R ══════════════════════════════════════ */

    public function select2_color() {
      $sql = "SELECT idcolor AS id, nombre_color AS nombre, hexadecimal FROM color WHERE idcolor > 1 AND estado='1' AND estado_delete = '1' ORDER BY nombre_color ASC;";
      return ejecutarConsulta($sql);
    }

    /* ══════════════════════════════════════ U N I D A D   D E   M E D I D A ══════════════════════════════════════ */

    public function select2_unidad_medida() {
      $sql = "SELECT idunidad_medida AS id, nombre_medida AS nombre, abreviacion FROM unidad_medida WHERE estado='1' AND estado_delete = '1' ORDER BY nombre_medida ASC;";
      return ejecutarConsulta($sql);
    }

    /* ══════════════════════════════════════ C A T E G O R I A ══════════════════════════════════════ */

    public function select2_categoria() {
      $sql = "SELECT idcategoria_insumos_af as id, nombre FROM categoria_insumos_af WHERE estado='1' AND estado_delete = '1' ORDER BY nombre ASC;";
      return ejecutarConsulta($sql);
    }

    public function select2_categoria_all() {
      $sql = "SELECT idcategoria_insumos_af as id, nombre FROM categoria_insumos_af WHERE estado='1' AND estado_delete = '1' ORDER BY nombre ASC;";
      return ejecutarConsulta($sql);
    }

    

    /* ══════════════════════════════════════ T I P O   T I E R R A   C O N C R E T O ══════════════════════════════════════ */

    public function select2_tierra_concreto() {
      $sql = "SELECT idtipo_tierra_concreto as id, nombre, modulo FROM tipo_tierra_concreto  WHERE estado='1' AND estado_delete = '1' AND idtipo_tierra_concreto > 1  ORDER BY modulo ASC;";
      return ejecutarConsulta($sql);
    }

    /* ══════════════════════════════════════ C L A S I F I C A C I O N   D E   G R U P O ══════════════════════════════════════ */

    public function select2_clasificacion_grupo() {
      $sql = "SELECT idclasificacion_grupo as id, nombre, descripcion FROM clasificacion_grupo  WHERE estado='1' AND estado_delete = '1' AND idclasificacion_grupo > 1  ORDER BY nombre ASC;";
      return ejecutarConsulta($sql);
    }

    /* ══════════════════════════════════════ P R O D U C T O  ══════════════════════════════════════ */
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar_producto($idproducto)  {
      $data = []; $array_marca_id = []; $array_marca = []; $marca_html_option = ""; $array_marca_name = [];

      $sql = "SELECT p.idproducto, p.idunidad_medida, p.idcolor, p.idcategoria_insumos_af, p.nombre, p.modelo, p.serie,  p.estado_igv, 
      p.precio_unitario, p.precio_igv, p.precio_sin_igv, p.precio_total, p.ficha_tecnica, p.descripcion, p.imagen, p.estado, p.created_at,
      um.nombre_medida, c.nombre_color, ciaf.nombre AS categoria
      FROM producto AS p, unidad_medida AS um, color AS c, categoria_insumos_af AS ciaf
      WHERE p.idunidad_medida = um.idunidad_medida AND p.idcolor = c.idcolor 
      AND p.idcategoria_insumos_af = ciaf.idcategoria_insumos_af AND p.idproducto = '$idproducto'";
      $activos = ejecutarConsultaSimpleFila($sql); if ($activos['status'] == false) { return  $activos;}

      if ( empty($activos['data'])  ) {
        return $retorno = ['status'=> true, 'message' => 'Salió todo ok,', 'data' => null ];
      }else{
        $sql3 = "SELECT dm.iddetalle_marca, m.idmarca, m.nombre_marca FROM detalle_marca as dm, marca as m WHERE dm.idmarca=m.idmarca AND dm.idproducto = '$idproducto';";
        $detalle_marca = ejecutarConsultaArray($sql3); if ($detalle_marca['status'] == false) { return  $detalle_marca;}

        if ( empty($detalle_marca['data']) ) { 
          array_push($array_marca_id, '1' );
          $array_marca[] = [ 'id' => 1, 'nombre' => 'SIN MARCA', 'selected' => 'selected' ];
          $marca_html_option = '<option value="SIN MARCA" selected >SIN MARCA</option>';
          array_push($array_marca_name, 'SIN MARCA' );
        } else {        
          foreach ($detalle_marca['data'] as $key => $value) { array_push($array_marca_id, $value['idmarca'] ); }
          foreach ($detalle_marca['data'] as $key => $value) { $array_marca[] = [ 'id' => $value['idmarca'], 'nombre' => $value['nombre_marca'] ]; }
          foreach ($detalle_marca['data'] as $key => $value) { $marca_html_option .= '<option value="'.$value['nombre_marca'].'">'.$value['nombre_marca'].'</option>'; }
          foreach ($detalle_marca['data'] as $key => $value) { array_push($array_marca_name, $value['nombre_marca'] ); }
        }
        
        $data = [
          'idproducto'      => $activos['data']['idproducto'],
          'idunidad_medida' => $activos['data']['idunidad_medida'],
          'nombre_medida'   => $activos['data']['nombre_medida'],
          'idcolor'         => $activos['data']['idcolor'],
          'nombre_color'    => $activos['data']['nombre_color'],
          'idcategoria_insumos_af'  => $activos['data']['idcategoria_insumos_af'],
          'categoria'               => $activos['data']['categoria'],
          'nombre'          => decodeCadenaHtml($activos['data']['nombre']),
          'modelo'          => decodeCadenaHtml($activos['data']['modelo']),
          'serie'           => decodeCadenaHtml($activos['data']['serie']),
          'estado_igv'      => (empty($activos['data']['estado_igv']) ? 0     : floatval($activos['data']['estado_igv']) ),
          'precio_unitario' => (empty($activos['data']['precio_unitario']) ? 0: floatval($activos['data']['precio_unitario']) ),
          'precio_igv'      => (empty($activos['data']['precio_igv']) ? 0     :  floatval($activos['data']['precio_igv']) ),
          'precio_sin_igv'  => (empty($activos['data']['precio_sin_igv']) ? 0 : floatval($activos['data']['precio_sin_igv']) ),
          'precio_total'    => (empty($activos['data']['precio_total']) ? 0   : floatval($activos['data']['precio_total']) ),
          'ficha_tecnica'   => $activos['data']['ficha_tecnica'],
          'descripcion'     => decodeCadenaHtml($activos['data']['descripcion']),
          'imagen'          => $activos['data']['imagen'],
          'estado'          => $activos['data']['estado'],
          'fecha'           => $activos['data']['created_at'],

          'id_marca'        => $array_marca_id,
          'marcas'          => $array_marca_name,
          'array_marcas'    => $array_marca,
          'marca_html_option'=> $marca_html_option,
        ];

        return $retorno = ['status'=> true, 'message' => 'Salió todo ok,', 'data' => $data ];  
      }       
    }

    public function buscar_precio_x_marca($idproducto, $marca) {
      $sql = "SELECT * FROM detalle_compra WHERE idproducto = '$idproducto' AND marca = '$marca' ORDER BY iddetalle_compra DESC LIMIT 1;";
      return ejecutarConsultaSimpleFila($sql);
    }

    public function tblaActivosFijos() {
      $data = [];
      $sql = "SELECT p.idproducto, p.nombre, p.descripcion, p.imagen, p.ficha_tecnica, um.nombre_medida, 
      ciaf.nombre AS categoria
      FROM producto as p, unidad_medida as um, categoria_insumos_af AS ciaf
      WHERE p.idunidad_medida= um.idunidad_medida  AND p.idcategoria_insumos_af = ciaf.idcategoria_insumos_af  AND 
      p.estado='1' AND p.estado_delete='1' and p.idcategoria_insumos_af != '1'
      ORDER BY p.nombre ASC;";
      $producto = ejecutarConsulta($sql); if ($producto['status'] == false){ return $producto; }

      foreach ($producto['data'] as $key => $value) {
        $id = $value['idproducto'];     
        $sql = "SELECT  AVG(precio_con_igv) AS promedio_precio FROM detalle_compra WHERE idproducto='$id';";
        $precio = ejecutarConsultaSimpleFila($sql);  if ($precio['status'] == false){ return $precio; }
  
        $data[] = Array(
          'idproducto'    =>  $value['idproducto'],
          'nombre'        => ( empty($value['nombre']) ? '' : decodeCadenaHtml($value['nombre'])),           
          'descripcion'   =>  $value['descripcion'],
          'imagen'        =>  $value['imagen'],
          'ficha_tecnica' =>  $value['ficha_tecnica'],
          'nombre_medida' =>  $value['nombre_medida'],
          'categoria'     =>  $value['categoria'],
          'promedio_precio' =>  (empty($precio['data']) ? 0 : ( empty($precio['data']['promedio_precio']) ? 0 : number_format(floatval($precio['data']['promedio_precio']), 2, '.', '' ) ) ),                  
        );  
      }
      return $retorno = ['status'=> true, 'message' => 'Salió todo ok,', 'data' => $data ];      
    }

    public function tblaInsumos() {
      $data = [];
      $sql = "SELECT p.idproducto, p.nombre, p.descripcion, p.imagen, p.ficha_tecnica, um.nombre_medida, 
      ciaf.nombre AS categoria
      FROM producto as p, unidad_medida as um, categoria_insumos_af AS ciaf
      WHERE p.idunidad_medida= um.idunidad_medida  AND p.idcategoria_insumos_af = ciaf.idcategoria_insumos_af  AND 
      p.estado='1' AND p.estado_delete='1' and p.idcategoria_insumos_af = '1'
      ORDER BY p.nombre ASC;";
      $producto = ejecutarConsulta($sql); if ($producto['status'] == false){ return $producto; }

      foreach ($producto['data'] as $key => $value) {
        $id = $value['idproducto'];     
        $sql = "SELECT  AVG(precio_con_igv) AS promedio_precio FROM detalle_compra WHERE idproducto='$id';";
        $precio = ejecutarConsultaSimpleFila($sql);  if ($precio['status'] == false){ return $precio; }
  
        $data[] = Array(
          'idproducto'    =>  $value['idproducto'],
          'nombre'        => ( empty($value['nombre']) ? '' : decodeCadenaHtml($value['nombre'])),           
          'descripcion'   =>  $value['descripcion'],
          'imagen'        =>  $value['imagen'],
          'ficha_tecnica' =>  $value['ficha_tecnica'],
          'nombre_medida' =>  $value['nombre_medida'],
          'categoria'     =>  $value['categoria'],
          'promedio_precio' =>  (empty($precio['data']) ? 0 : ( empty($precio['data']['promedio_precio']) ? 0 : number_format(floatval($precio['data']['promedio_precio']), 2, '.', '' ) ) ),                  
        );  
      }
      return $retorno = ['status'=> true, 'message' => 'Salió todo ok,', 'data' => $data ];      
    }

    public function tblaInsumosYActivosFijos() {
      $data = [];
      $sql_1 = "SELECT p.idproducto, p.nombre, p.descripcion, p.imagen, p.ficha_tecnica, um.nombre_medida, 
      ciaf.nombre AS categoria
      FROM producto as p, unidad_medida as um, categoria_insumos_af AS ciaf
      WHERE p.idunidad_medida= um.idunidad_medida  AND p.idcategoria_insumos_af = ciaf.idcategoria_insumos_af  AND 
      p.estado='1' AND p.estado_delete='1'
      ORDER BY p.nombre ASC;";
      $producto = ejecutarConsulta($sql_1); if ($producto['status'] == false){ return $producto; }

      foreach ($producto['data'] as $key => $value) {
        $id = $value['idproducto'];     
        $sql_2 = "SELECT  AVG(precio_con_igv) AS promedio_precio FROM detalle_compra WHERE idproducto='$id';";
        $precio = ejecutarConsultaSimpleFila($sql_2);  if ($precio['status'] == false){ return $precio; }

        $sql_3 = "SELECT m.nombre_marca, m.descripcion  FROM detalle_marca as dm, marca as m  WHERE dm.idmarca = m.idmarca AND dm.idproducto = '$id'";
        $marcas = ejecutarConsultaArray($sql_3);  if ($marcas['status'] == false){ return $marcas; }
        $html_marcas = '';

        if ( empty($marcas['data']) ) {
          $html_marcas = '<option value="SIN MARCA">SIN MARCA</option>';
        } else {
          foreach ($marcas['data'] as $key2 => $val2) {
            $html_marcas .= '<option value="'.$val2['nombre_marca'].'">'.$val2['nombre_marca'].'</option>';
          }
        }        

        $data[] = Array(
          'idproducto'    =>  $value['idproducto'],
          'nombre'        => ( empty($value['nombre']) ? '' : decodeCadenaHtml($value['nombre'])),           
          'descripcion'   =>  $value['descripcion'],
          'imagen'        =>  $value['imagen'],
          'ficha_tecnica' =>  $value['ficha_tecnica'],
          'nombre_medida' =>  $value['nombre_medida'],
          'categoria'     =>  $value['categoria'],
          'marcas_html'   =>  $html_marcas,
          'marcas_array'  =>  $marcas['data'],
          'promedio_precio' =>  (empty($precio['data']) ? 0 : ( empty($precio['data']['promedio_precio']) ? 0 : number_format(floatval($precio['data']['promedio_precio']), 2, '.', '' ) ) ),                  
        );  
      }
      return $retorno = ['status'=> true, 'message' => 'Salió todo ok,', 'data' => $data ];      
    }
    /* ══════════════════════════════════════ S E R V i C I O S  M A Q U I N A RI A ════════════════════════════ */

    public function select2_servicio($tipo) {
      $sql = "SELECT mq.idmaquinaria as idmaquinaria, mq.nombre as nombre, mq.codigo_maquina as codigo_maquina, p.razon_social as nombre_proveedor, mq.idproveedor as idproveedor
      FROM maquinaria as mq, proveedor as p WHERE mq.idproveedor=p.idproveedor AND mq.estado='1' AND mq.estado_delete='1' AND mq.tipo=$tipo";
      return ejecutarConsulta($sql);
    }

    /* ══════════════════════════════════════ E M P R E S A   A   C A R G O ══════════════════════════════════════ */
    public function select2_empresa_a_cargo() {
      $sql3 = "SELECT idempresa_a_cargo as id, razon_social as nombre, tipo_documento, numero_documento, logo FROM empresa_a_cargo WHERE estado ='1' AND estado_delete ='1' AND idempresa_a_cargo > 1 ;";
      return ejecutarConsultaArray($sql3);
    }

    /* ══════════════════════════════════════ M A R C A S  ════════════════════════════ */

    public function marcas() {
      $sql = "SELECT idmarca, nombre_marca FROM marca WHERE estado=1 and estado_delete=1;";
      return ejecutarConsulta($sql);
    }



  }

?>