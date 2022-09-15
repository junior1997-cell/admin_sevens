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

    /* ══════════════════════════════════════ T R A B A J A D O R ══════════════════════════════════════ */

    public function select2_trabajador(){
      $sql = "SELECT idtrabajador as id, nombres as nombre, tipo_documento as documento, numero_documento, imagen_perfil 
      FROM trabajador WHERE estado = '1' AND estado_delete = '1' ORDER BY nombres ASC;";
      return ejecutarConsulta($sql);
    }

    public function select2_tipo_trabajador() {
      $sql="SELECT * FROM tipo_trabajador where estado='1' AND estado_delete = '1' ORDER BY nombre ASC";
      return ejecutarConsulta($sql);		
    }

    public function select2_cargo_trabajador_id($id_tipo) {
      $sql = "SELECT * FROM cargo_trabajador WHERE idtipo_trabjador='$id_tipo' AND estado='1' AND estado_delete = '1' ORDER BY nombre ASC";
      return ejecutarConsulta($sql);
    }

    public function select2_ocupacion_trabajador()  {
      $sql="SELECT idocupacion AS id, nombre_ocupacion AS nombre FROM ocupacion where estado = '1' AND estado_delete = '1' ORDER BY nombre_ocupacion ASC;";
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
      $sql = "SELECT idcategoria_insumos_af as id, nombre FROM categoria_insumos_af WHERE estado='1' AND estado_delete = '1' AND idcategoria_insumos_af > 1 ORDER BY nombre ASC;";
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

    /* ══════════════════════════════════════ P R O D U C T O  ══════════════════════════════════════ */
    public function tblaActivosFijos() {
      $sql = "SELECT p.idproducto,p.idcategoria_insumos_af, p.nombre, p.modelo, p.serie, p.marca,p.precio_unitario, p.precio_igv as igv, 
      p.precio_sin_igv, p.precio_total as precio_con_igv, p.ficha_tecnica, p.descripcion, p.imagen, um.nombre_medida, c.nombre_color, 
      ciaf.nombre AS categoria
      FROM producto as p, unidad_medida as um, color as c, categoria_insumos_af AS ciaf
      WHERE p.idunidad_medida= um.idunidad_medida AND p.idcolor=c.idcolor AND p.idcategoria_insumos_af = ciaf.idcategoria_insumos_af AND p.idcategoria_insumos_af!='1' AND 
      p.estado='1' AND p.estado_delete='1'
      ORDER BY p.nombre ASC;";
      return ejecutarConsulta($sql);
    }

    public function tblaInsumos() {
      $sql = "SELECT p.idproducto,p.idcategoria_insumos_af, p.nombre, p.modelo, p.serie, p.marca,p.precio_unitario, p.precio_igv as igv, 
      p.precio_sin_igv, p.precio_total as precio_con_igv, p.ficha_tecnica, p.descripcion, p.imagen, um.nombre_medida, c.nombre_color, 
      ciaf.nombre AS categoria
      FROM producto as p, unidad_medida as um, color as c, categoria_insumos_af AS ciaf
      WHERE p.idunidad_medida= um.idunidad_medida AND p.idcolor=c.idcolor AND p.idcategoria_insumos_af = ciaf.idcategoria_insumos_af AND p.idcategoria_insumos_af='1' AND 
      p.estado='1' AND p.estado_delete='1'
      ORDER BY p.nombre ASC;";
      return ejecutarConsulta($sql);
    }

    public function tblaInsumosYActivosFijos() {
      $sql = "SELECT p.idproducto,p.idcategoria_insumos_af, p.nombre, p.modelo, p.serie, p.marca,p.precio_unitario, p.precio_igv as igv, 
      p.precio_sin_igv, p.precio_total as precio_con_igv, p.ficha_tecnica, p.descripcion, p.imagen, um.nombre_medida, c.nombre_color, 
      ciaf.nombre AS categoria
      FROM producto as p, unidad_medida as um, color as c, categoria_insumos_af AS ciaf
      WHERE p.idunidad_medida= um.idunidad_medida AND p.idcolor=c.idcolor AND p.idcategoria_insumos_af = ciaf.idcategoria_insumos_af  AND 
      p.estado='1' AND p.estado_delete='1'
      ORDER BY p.nombre ASC;";
      return ejecutarConsulta($sql);
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

    /* ══════════════════════════════════════ M A R C A S   D E   A C T I V O S ════════════════════════════ */

    public function marcas_activos() {
      $sql = "SELECT idmarca, nombre_marca FROM marca WHERE estado=1 and estado_delete=1;";
      return ejecutarConsulta($sql);
    }

  }

?>