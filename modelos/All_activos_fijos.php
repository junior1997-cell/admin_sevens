<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class All_activos_fijos
{
    //Implementamos nuestro constructor
    public function __construct()
    {
    }  
    //Implementamos un método para insertar registros
    public function insertar($idproveedor,$fecha_compra,$tipo_comprovante,$serie_comprovante,$descripcion,$subtotal_compra,$igv_compra,$total_compra_af_g,
    $idactivos_fijos,$unidad_medida,$nombre_color,$cantidad,$precio_sin_igv,$precio_igv,$precio_con_igv,$descuento,$ficha_tecnica_activo) {
        /*var_dump('subtotal_compra '.$subtotal_compra,'igv_compra '.$igv_compra,'total '.$total_compra_af_g);die();*/
        $sql = "INSERT INTO compra_af_general(idproveedor,fecha_compra,tipo_comprobante,serie_comprobante,descripcion,subtotal,igv,total)
		VALUES ('$idproveedor','$fecha_compra','$tipo_comprovante','$serie_comprovante','$descripcion','$subtotal_compra','$igv_compra','$total_compra_af_g')";
        //return ejecutarConsulta($sql);
        $idcompra_af_generalnew = ejecutarConsulta_retornarID($sql);

        $num_elementos = 0;
        $sw = true;

        while ($num_elementos < count($idactivos_fijos)) {
            $subtotal_activo_g= ( floatval($cantidad[$num_elementos]) * floatval($precio_con_igv[$num_elementos]) ) + $descuento[$num_elementos] ;

            $sql_detalle = "INSERT INTO detalle_compra_af_g(idcompra_af_general,idactivos_fijos,unidad_medida,color,ficha_tecnica_producto,cantidad,precio_sin_igv,igv,precio_con_igv,descuento,subtotal) 
			VALUES ('$idcompra_af_generalnew','$idactivos_fijos[$num_elementos]', '$unidad_medida[$num_elementos]',  '$nombre_color[$num_elementos]', 
            '$ficha_tecnica_activo[$num_elementos]','$cantidad[$num_elementos]', '$precio_sin_igv[$num_elementos]', '$precio_igv[$num_elementos]', '$precio_con_igv[$num_elementos]', 
            '$descuento[$num_elementos]', '$subtotal_activo_g')";
            ejecutarConsulta($sql_detalle) or ($sw = false);

            $num_elementos = $num_elementos + 1;
        }

        return $sw;
    }

    //Implementamos un método para editar registros
    public function editar($idcompra_af_general,$idproveedor,$fecha_compra,$tipo_comprovante,$serie_comprovante,$descripcion,$subtotal_compra,$igv_compra,$total_compra_af_g,
    $idactivos_fijos,$unidad_medida,$nombre_color,$cantidad,$precio_sin_igv,$precio_igv,$precio_con_igv,$descuento,$ficha_tecnica_activo){
		 			
		 /*var_dump($idcompra_af_general,$idproveedor,$fecha_compra,$tipo_comprovante,$serie_comprovante,$descripcion,$subtotal_compra,$igv_compra,$total_compra_af_g,
         $idactivos_fijos,$unidad_medida,$nombre_color,$cantidad,$precio_sin_igv,$precio_igv,$precio_con_igv,$descuento,$ficha_tecnica_activo);die();*/

		if ($idcompra_af_general != "" ) {			 

			//Eliminamos todos los permisos asignados para volverlos a registrar
			$sqldel="DELETE FROM detalle_compra_af_g WHERE idcompra_af_general='$idcompra_af_general';";
			ejecutarConsulta($sqldel);

            $sql = "UPDATE compra_af_general SET idproveedor='$idproveedor',fecha_compra='$fecha_compra',tipo_comprobante='$tipo_comprovante',serie_comprobante='$serie_comprovante'
            ,subtotal='$subtotal_compra',igv='$igv_compra',total='$total_compra_af_g',descripcion='$descripcion'
            WHERE idcompra_af_general = '$idcompra_af_general'";
            ejecutarConsulta($sql);
    
            $num_elementos = 0;
            $sw = true;
    
            while ($num_elementos < count($idactivos_fijos)) {

                $subtotal_activo_g= ( floatval($cantidad[$num_elementos]) * floatval($precio_con_igv[$num_elementos]) ) + $descuento[$num_elementos] ;

                $sql_detalle = "INSERT INTO detalle_compra_af_g(idcompra_af_general,idactivos_fijos,unidad_medida,color,ficha_tecnica_producto,cantidad,precio_sin_igv,igv,precio_con_igv,descuento,subtotal) 
                VALUES ('$idcompra_af_general','$idactivos_fijos[$num_elementos]', '$unidad_medida[$num_elementos]',  '$nombre_color[$num_elementos]', 
                '$ficha_tecnica_activo[$num_elementos]','$cantidad[$num_elementos]', '$precio_sin_igv[$num_elementos]', '$precio_igv[$num_elementos]', '$precio_con_igv[$num_elementos]', 
                '$descuento[$num_elementos]', '$subtotal_activo_g')";
                ejecutarConsulta($sql_detalle) or ($sw = false);
    
                $num_elementos = $num_elementos + 1;
            }			 
		}

		if ($idcompra_af_general != "") { return $sw;	} else { return false; }
	}

    public function mostrar_compra_para_editar($idcompra_af_general)
    {
        $sql = "SELECT  cafg.idcompra_af_general as idcompra_af_general, 
            cafg.idproveedor, cafg.fecha_compra, 
            cafg.tipo_comprobante as tipo_comprobante, 
            cafg.serie_comprobante as serie_comprobante, 
            cafg.descripcion as descripcion, 
            cafg.subtotal as subtotal, 
            cafg.igv as igv, 
            cafg.total as total,
            cafg.estado as estado
            FROM compra_af_general as cafg
            WHERE cafg.idcompra_af_general='$idcompra_af_general'";

        $compra_af_general = ejecutarConsultaSimpleFila($sql);
        
        $sql_2 = "SELECT dcafg.idactivos_fijos as idactivos_fijos,
            dcafg.ficha_tecnica_producto as ficha_tecnica,
            dcafg.cantidad as cantidad,
            dcafg.precio_sin_igv as precio_sin_igv, dcafg.igv, dcafg.precio_con_igv,
            dcafg.descuento as descuento,
            af.nombre as nombre_activo, af.imagen,
            dcafg.unidad_medida, dcafg.color
            FROM detalle_compra_af_g AS dcafg, activos_fijos AS af
            WHERE idcompra_af_general='$idcompra_af_general' AND  dcafg.idactivos_fijos=af.idactivos_fijos";

        $activos = ejecutarConsultaArray($sql_2);

        $results = array(
			"idcompra_af_general" =>$compra_af_general['idcompra_af_general'],
            "idproveedor" =>$compra_af_general['idproveedor'],
			"fecha_compra" =>$compra_af_general['fecha_compra'],
			"tipo_comprobante" =>$compra_af_general['tipo_comprobante'],
            "serie_comprobante" =>$compra_af_general['serie_comprobante'],
            "descripcion" =>$compra_af_general['descripcion'],
            "subtotal" =>$compra_af_general['subtotal'],
            "igv" =>$compra_af_general['igv'],
            "total" =>$compra_af_general['total'],
            "estado" =>$compra_af_general['estado'],
			"activos" =>$activos,
		);

        return $results ;
    }
    
    //Implementamos un método para desactivar categorías
    public function desactivar($idcompra_af_general)
    {
        $sql = "UPDATE compra_af_general SET estado='0' WHERE idcompra_af_general='$idcompra_af_general'";

        return ejecutarConsulta($sql);
    }

    //Implementamos un método para activar categorías
    public function activar($idcompra_por_proyecto)
    {
        $sql = "UPDATE compra_af_general SET estado='1' WHERE idcompra_af_general='$idcompra_por_proyecto'";

        return ejecutarConsulta($sql);
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idcompra_por_proyecto)
    {
        $sql = "SELECT * FROM compra_por_proyecto WHERE idcompra_por_proyecto='$idcompra_por_proyecto'";

        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para listar los registros
    public function listar_compra_activo_f_g()
    {
        // $idproyecto=2;
        $sql = "SELECT
            cafg.idcompra_af_general as idcompra_af_general,
            cafg.idproveedor as idproveedor,
            cafg.fecha_compra as fecha_compra,
            cafg.tipo_comprobante as tipo_comprobante,
            cafg.serie_comprobante as serie_comprobante,
            cafg.descripcion as descripcion,
            cafg.total as total,
            cafg.comprobante as imagen_comprobante,
            p.razon_social as razon_social, p.telefono,
            cafg.estado as estado
            FROM compra_af_general as cafg, proveedor as p 
            WHERE cafg.idproveedor=p.idproveedor
            ORDER BY cafg.idcompra_af_general DESC";
            return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los registros x proveedor
    public function listar_compraxporvee_af_g()
    {
        // $idproyecto=2;
        $sql = "SELECT  COUNT(cafg.idproveedor) as cantidad,
        SUM(cafg.total) as total,
        cafg.idproveedor as idproveedor,
        p.razon_social as razon_social
		FROM compra_af_general as cafg, proveedor as p 
		WHERE cafg.idproveedor=p.idproveedor GROUP BY cafg.idproveedor";
        return ejecutarConsulta($sql);
    }
    //Implementar un método para listar los registros x proveedor
    public function listar_detalle_comprax_provee($idproveedor)
    {
        //var_dump($idproyecto,$idproveedor);die();
        // $idproyecto=2;
        $sql = "SELECT* FROM compra_af_general WHERE idproveedor='$idproveedor'";
        return ejecutarConsulta($sql);
    }
	//mostrar detalles uno a uno de la factura
    public function ver_compra($idcompra_af_general)
    {
        $sql = "SELECT
		cafg.idcompra_af_general as idcompra_af_general,
		cafg.idproveedor as idproveedor,
		cafg.fecha_compra as fecha_compra,
		cafg.tipo_comprobante as tipo_comprobante,
		cafg.serie_comprobante as serie_comprobante,
		cafg.descripcion as descripcion,
        cafg.subtotal as subtotal,
		cafg.igv as igv,
		cafg.total as total,
		cafg.comprobante as imagen_comprobante,
		p.razon_social as razon_social, p.telefono,
		cafg.estado as estado
		FROM compra_af_general as cafg, proveedor as p 
		WHERE  cafg.idcompra_af_general='$idcompra_af_general' AND cafg.idproveedor=p.idproveedor";

        return ejecutarConsultaSimpleFila($sql);
    }
	//lismatamos los detalles
	public function listarDetalle($id_compra_afg)
    {
        $sql = "SELECT 
		dcafg.idactivos_fijos as idactivos_fijos,
		dcafg.ficha_tecnica_producto as ficha_tecnica,
		dcafg.cantidad as cantidad,
		dcafg.precio_con_igv as precio_con_igv,
		dcafg.descuento as descuento,
		af.nombre as nombre
		FROM detalle_compra_af_g as dcafg, activos_fijos as af
		WHERE dcafg.idcompra_af_general='$id_compra_afg' AND  dcafg.idactivos_fijos=af.idactivos_fijos";

        return ejecutarConsulta($sql);
    }

    //pago servicio
    public function pago_servicio($idcompra_af_general)
    {
        $sql = "SELECT SUM(monto) as total_pago_compras
		FROM pago_compras 
		WHERE idcompra_af_general='$idcompra_af_general' AND estado=1";
        return ejecutarConsultaSimpleFila($sql);
    }

    //----Comprobantes pagos-----

    public function editar_comprobante_af_g($idcompra_af_general, $doc_comprobante)
    {
        //var_dump($idcompra_af_general,$doc_comprobante);die();
        $sql = "UPDATE compra_af_general SET comprobante='$doc_comprobante' WHERE idcompra_af_general ='$idcompra_af_general'";
        return ejecutarConsulta($sql);
    }
    // obtebnemos los DOCS para eliminar
    public function obtener_comprobante_af_g($idcompra_af_general)
    {
        $sql = "SELECT comprobante FROM compra_af_general WHERE idcompra_af_general ='$idcompra_af_general'";

        return ejecutarConsultaSimpleFila($sql);
    }

    /**========================= */
    /**seccion facturas */
    /**========================= */
    public function insertar_factura($idproyectof, $idcomp_proyecto, $codigo, $monto_compraa, $fecha_emision, $descripcion_f, $doc_img, $subtotal_compraa, $igv_compraa)
    {
        //var_dump($idproyectof,$idcomp_proyecto,$codigo,$monto_compraa,$fecha_emision,$descripcion_f,$doc_img,$subtotal_compraa,$igv_compraa);die();
        $sql = "INSERT INTO facturas_compras(idproyecto,idcompra_af_general,codigo,monto,fecha_emision,descripcion,imagen,subtotal,igv) 
		VALUES ('$idproyectof','$idcomp_proyecto','$codigo','$monto_compraa','$fecha_emision','$descripcion_f','$doc_img','$subtotal_compraa','$igv_compraa')";
        return ejecutarConsulta($sql);
    }
    // obtebnemos los DOCS para eliminar
    public function obtenerDoc($idfacturacompra)
    {
        $sql = "SELECT imagen FROM facturas_compras WHERE idfacturacompra ='$idfacturacompra '";

        return ejecutarConsulta($sql);
    }

    //Implementamos un método para editar registros
    public function editar_factura($idproyectof, $idfacturacompra, $idcomp_proyecto, $codigo, $monto_compraa, $fecha_emision, $descripcion_f, $doc_img, $subtotal_compraa, $igv_compraa)
    {
        //$vaa="$idfactura,$idproyectof,$idmaquina,$codigo,$monto,$fecha_emision,$descripcion_f,$imagen2";
        $sql = "UPDATE facturas_compras SET
		idproyecto='$idproyectof',
		idcompra_af_general='$idcomp_proyecto',
		codigo='$codigo',
		monto='$monto_compraa',
		fecha_emision='$fecha_emision',
		descripcion='$descripcion_f',
		subtotal='$subtotal_compraa',
		igv='$igv_compraa',
		imagen='$doc_img'
		WHERE idfacturacompra ='$idfacturacompra'";
        return ejecutarConsulta($sql);
        //return $vaa;
    }
    //Listar
    public function listar_facturas($idcompra_af_general, $idproyecto)
    {
        //var_dump($idproyecto,$idmaquinaria);die();
        $sql = "SELECT *
		FROM facturas_compras
		WHERE idcompra_af_general = '$idcompra_af_general' AND  idproyecto='$idproyecto'";
        return ejecutarConsulta($sql);
    }
    public function total_monto_f($idcompra_af_general, $idproyecto)
    {
        //var_dump($idcompra_af_general,$idproyecto);die();

        $sql = "SELECT SUM(fs.monto) as total_mont_f
		FROM facturas_compras as fs
		WHERE fs.idcompra_af_general='$idcompra_af_general' AND fs.idproyecto='$idproyecto' AND  fs.estado='1'";
        return ejecutarConsultaSimpleFila($sql);
    }
    //mostrar_factura
    public function mostrar_factura($idfacturacompra)
    {
        $sql = "SELECT * FROM facturas_compras WHERE idfacturacompra ='$idfacturacompra'";
        return ejecutarConsultaSimpleFila($sql);
    }
    //Implementamos un método para activar categorías
    public function desactivar_factura($idfacturacompra)
    {
        //var_dump($idfacturacompra);die();
        $sql = "UPDATE facturas_compras SET estado='0' WHERE idfacturacompra ='$idfacturacompra'";
        return ejecutarConsulta($sql);
    }
    //Implementamos un método para desactivar categorías
    public function activar_factura($idfacturacompra)
    {
        //var_dump($idpago_compras);die();
        $sql = "UPDATE facturas_compras SET estado='1' WHERE idfacturacompra ='$idfacturacompra'";
        return ejecutarConsulta($sql);
    }
    /**=========================== */
    //SECCION PAGOS
    /**=========================== */
    public function insertar_pago(
        $idcompra_af_general_p,
        $idproveedor_pago,
        $beneficiario_pago,
        $forma_pago,
        $tipo_pago,
        $cuenta_destino_pago,
        $banco_pago,
        $titular_cuenta_pago,
        $fecha_pago,
        $monto_pago,
        $numero_op_pago,
        $descripcion_pago,
        $imagen1
    ) {
        /*var_dump($idcompra_af_general_p,$idproveedor_pago,$beneficiario_pago,$forma_pago,$tipo_pago,$cuenta_destino_pago,$banco_pago,
         $titular_cuenta_pago,$fecha_pago,$monto_pago,$numero_op_pago,$descripcion_pago,$imagen1);die();*/
        ///idproyecto
        $sql = "INSERT INTO pago_compras (idcompra_af_general,idproveedor,beneficiario,forma_pago,tipo_pago,cuenta_destino,idbancos,titular_cuenta,fecha_pago,monto,numero_operacion,descripcion,imagen) 
		VALUES ('$idcompra_af_general_p',
			'$idproveedor_pago',
			'$beneficiario_pago',
			'$forma_pago',
			'$tipo_pago',
			'$cuenta_destino_pago',
			'$banco_pago',
			'$titular_cuenta_pago',
			'$fecha_pago',
			'$monto_pago',
			'$numero_op_pago',
			'$descripcion_pago',
			'$imagen1')";
        return ejecutarConsulta($sql);
    }
    //Implementamos un método para editar registros
    public function editar_pago(
        $idpago_compras,
        $idcompra_af_general_p,
        $idproveedor_pago,
        $beneficiario_pago,
        $forma_pago,
        $tipo_pago,
        $cuenta_destino_pago,
        $banco_pago,
        $titular_cuenta_pago,
        $fecha_pago,
        $monto_pago,
        $numero_op_pago,
        $descripcion_pago,
        $imagen1
    ) {
        $sql = "UPDATE pago_compras SET
		idcompra_af_general ='$idcompra_af_general_p',
		idproveedor='$idproveedor_pago',
		beneficiario='$beneficiario_pago',
		forma_pago='$forma_pago',
		tipo_pago='$tipo_pago',
		cuenta_destino='$cuenta_destino_pago',
		idbancos='$banco_pago',
		titular_cuenta='$titular_cuenta_pago',
		fecha_pago='$fecha_pago',
		monto='$monto_pago',
		numero_operacion='$numero_op_pago',
		descripcion='$descripcion_pago',
		imagen='$imagen1'
		WHERE idpago_compras='$idpago_compras'";
        return ejecutarConsulta($sql);
    }
    //Listar pagos-normal
    public function listar_pagos($idcompra_af_general)
    {
        //var_dump($idproyecto,$idmaquinaria);die();
        $sql = "SELECT
		ps.idpago_compras  as idpago_compras,
		ps.forma_pago as forma_pago,
		ps.tipo_pago as tipo_pago,
		ps.beneficiario as beneficiario,
		ps.cuenta_destino as cuenta_destino,
		ps.titular_cuenta as titular_cuenta,
		ps.fecha_pago as fecha_pago,
		ps.descripcion as descripcion,
		ps.idbancos as id_banco,
		bn.nombre as banco,
		ps.numero_operacion as numero_operacion,
		ps.monto as monto,
		ps.imagen as imagen,
		ps.estado as estado
		FROM pago_compras ps, bancos as bn 
		WHERE ps.idcompra_af_general='$idcompra_af_general' AND bn.idbancos=ps.idbancos";
        return ejecutarConsulta($sql);
    }
        //Listar pagos1-con detraccion --tabla Proveedor
    public function listar_pagos_compra_prov_con_dtracc($idcompra_af_general,$tipo_pago)
    {
        //var_dump($idproyecto,$idmaquinaria);die();
        $sql = "SELECT
        ps.idpago_compras  as idpago_compras,
        ps.forma_pago as forma_pago,
        ps.tipo_pago as tipo_pago,
        ps.beneficiario as beneficiario,
        ps.cuenta_destino as cuenta_destino,
        ps.titular_cuenta as titular_cuenta,
        ps.fecha_pago as fecha_pago,
        ps.descripcion as descripcion,
        ps.idbancos as id_banco,
        bn.nombre as banco,
        ps.numero_operacion as numero_operacion,
        ps.monto as monto,
        ps.imagen as imagen,
        ps.estado as estado
        FROM pago_compras ps, bancos as bn 
        WHERE ps.idcompra_af_general='$idcompra_af_general' AND bn.idbancos=ps.idbancos AND ps.tipo_pago='$tipo_pago'";
        return ejecutarConsulta($sql);
    }
    //Implementamos un método para desactivar categorías
    public function desactivar_pagos($idpago_compras)
    {
        //var_dump($idpago_compras);die();
        $sql = "UPDATE pago_compras SET estado='0' WHERE idpago_compras ='$idpago_compras'";
        return ejecutarConsulta($sql);
    }
    //Implementamos un método para activar categorías
    public function activar_pagos($idpago_compras)
    {
        $sql = "UPDATE pago_compras SET estado='1' WHERE idpago_compras ='$idpago_compras'";
        return ejecutarConsulta($sql);
    }
    //Mostrar datos para editar Pago servicio.
    public function mostrar_pagos($idpago_compras)
    {
        $sql = "SELECT
		ps.idpago_compras as idpago_compras,
		ps.idcompra_af_general as idcompra_af_general,
		ps.idproveedor as idproveedor,
		ps.forma_pago as forma_pago,
		ps.tipo_pago as tipo_pago,
		ps.beneficiario as beneficiario,
		ps.cuenta_destino as cuenta_destino,
		ps.titular_cuenta as titular_cuenta,
		ps.fecha_pago as fecha_pago,
		ps.descripcion as descripcion,
		ps.idbancos as id_banco,
		bn.nombre as banco,
		ps.numero_operacion as numero_operacion,
		ps.monto as monto,
		ps.imagen as imagen,
		ps.estado as estado
		FROM pago_compras ps, bancos as bn
		WHERE idpago_compras='$idpago_compras' AND ps.idbancos = bn.idbancos";
        return ejecutarConsultaSimpleFila($sql);
    }

    // consulta para totales sin detracion
    public function suma_total_pagos($idcompra_af_general)
    {
        $sql = "SELECT SUM(ps.monto) as total_monto
		FROM pago_compras as ps
		WHERE  ps.idcompra_af_general='$idcompra_af_general' AND ps.estado='1'";
        return ejecutarConsultaSimpleFila($sql);
    }
    //consultas para totales con detracion
    public function suma_total_pagos_detraccion($idcompra_af_general,$tipopago)
    {
        $sql = "SELECT SUM(ps.monto) as total_montoo
		FROM pago_compras as ps
		WHERE  ps.idcompra_af_general='$idcompra_af_general' AND ps.tipo_pago='$tipopago' AND ps.estado='1'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function total_costo_parcial_pago($idmaquinaria, $idproyecto)
    {
        $sql = "SELECT
		SUM(s.costo_parcial) as costo_parcial  
		FROM servicio as s 
		WHERE s.idmaquinaria='$idmaquinaria' AND s.idproyecto='$idproyecto' AND s.estado='1'";

        return ejecutarConsultaSimpleFila($sql);
    }
    // obtebnemos los DOCS para eliminar
    public function obtenerImg($idpago_compras)
    {
        $sql = "SELECT imagen FROM pago_compras WHERE idpago_compras='$idpago_compras'";

        return ejecutarConsulta($sql);
    }
    //mostrar datos del proveedor y maquina en form
    public function most_datos_prov_pago($idcompra_af_general)
    {
        $sql = " SELECT * FROM compra_por_proyecto as cpp, proveedor as p  WHERE cpp.idproveedor=p.idproveedor AND cpp.idcompra_af_general='$idcompra_af_general'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //CAPTURAR PERSONA  DE RENIEC 
	public function datos_reniec($dni)
	{ 
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
	public function datos_sunat($ruc)
	{ 
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
}

?>
