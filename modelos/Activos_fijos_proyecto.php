<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Activos_fijos_proyecto
{
    //Implementamos nuestro constructor
    public function __construct()
    {
    }
    //Implementamos un método para insertar registros
    public function insertar( $idproyecto, $idproveedor, $fecha_compra, $tipo_comprobante, $serie_comprobante, $descripcion, $total_compra_af_p,
        $subtotal_compra,  $igv_compra,
        $idactivos_fijos, $unidad_medida, $nombre_color, $cantidad, $precio_sin_igv, $precio_igv, $precio_con_igv, $descuento, $ficha_tecnica_activo ) {
      
        $sql = "INSERT INTO compra_af_proyecto(idproyecto,idproveedor, fecha_compra, tipo_comprobante, serie_comprobante,descripcion, subtotal, igv, total)
		VALUES ('$idproyecto','$idproveedor','$fecha_compra','$tipo_comprobante','$serie_comprobante','$descripcion','$subtotal_compra','$igv_compra','$total_compra_af_p')";
        //return ejecutarConsulta($sql);
        $idcompraafnew = ejecutarConsulta_retornarID($sql);

        $num_elementos = 0;
        $sw = true;

        while ($num_elementos < count($idactivos_fijos)) {

            $subtotal_x_activo= ( floatval($cantidad[$num_elementos]) * floatval($precio_con_igv[$num_elementos]) ) + $descuento[$num_elementos] ;
            $sql_detalle = "INSERT INTO detalle_compra_af_p(idcompra_af_proyecto, idactivos_fijos, unidad_medida, color, cantidad, precio_sin_igv, igv, precio_con_igv, descuento, subtotal, ficha_tecnica_producto ) 
			VALUES ('$idcompraafnew','$idactivos_fijos[$num_elementos]', '$unidad_medida[$num_elementos]',  '$nombre_color[$num_elementos]', '$cantidad[$num_elementos]', '$precio_sin_igv[$num_elementos]', '$precio_igv[$num_elementos]', '$precio_con_igv[$num_elementos]', '$descuento[$num_elementos]', '$subtotal_x_activo', '$ficha_tecnica_activo[$num_elementos]')";
            ejecutarConsulta($sql_detalle) or ($sw = false);

            $num_elementos = $num_elementos + 1;
        }

        return $sw;
    }

    //Implementamos un método para editar registros
    public function editar($idcompra_af_proyecto, $idproyecto, $idproveedor, $fecha_compra, $tipo_comprobante, $serie_comprobante, $descripcion, $total_compra_af_p,
    $subtotal_compra,  $igv_compra,
    $idactivos_fijos, $unidad_medida, $nombre_color, $cantidad, $precio_sin_igv, $precio_igv, $precio_con_igv, $descuento, $ficha_tecnica_activo ){
		 			
		 

		if ($idcompra_af_proyecto != "" ) {			 

			//Eliminamos todos los permisos asignados para volverlos a registrar
			$sqldel="DELETE FROM detalle_compra_af_p WHERE idcompra_af_proyecto='$idcompra_af_proyecto';";
			ejecutarConsulta($sqldel);

            $sql = "UPDATE compra_af_proyecto SET idproyecto = '$idproyecto', idproveedor = '$idproveedor', fecha_compra = '$fecha_compra',
            tipo_comprobante = '$tipo_comprobante', serie_comprobante = '$serie_comprobante', descripcion = '$descripcion',
            total = '$total_compra_af_p', subtotal = '$subtotal_compra', igv = '$igv_compra'
             WHERE idcompra_af_proyecto = '$idcompra_af_proyecto'";
            ejecutarConsulta($sql);
    
            $num_elementos = 0;
            $sw = true;
    
            while ($num_elementos < count($idactivos_fijos)) {
                $subtotal_x_activo= ( floatval($cantidad[$num_elementos]) * floatval($precio_con_igv[$num_elementos]) ) + $descuento[$num_elementos] ;
                $sql_detalle = "INSERT INTO detalle_compra_af_p( idcompra_af_proyecto, idactivos_fijos, unidad_medida, color, cantidad, precio_sin_igv, igv, precio_con_igv, descuento, subtotal, ficha_tecnica_producto ) 
                VALUES ('$idcompra_af_proyecto','$idactivos_fijos[$num_elementos]', '$unidad_medida[$num_elementos]',  '$nombre_color[$num_elementos]', '$cantidad[$num_elementos]', '$precio_sin_igv[$num_elementos]', '$precio_igv[$num_elementos]', '$precio_con_igv[$num_elementos]', '$descuento[$num_elementos]', '$subtotal_x_activo', '$ficha_tecnica_activo[$num_elementos]')";
                ejecutarConsulta($sql_detalle) or ($sw = false);
    
                $num_elementos = $num_elementos + 1;
            }			 
		}

		if ($idcompra_af_proyecto != "") { return $sw;	} else { return false; }
	}

    public function mostrar_compra_para_editar($id_compras_x_proyecto)
    {
        $sql = "SELECT  cafp.idcompra_af_proyecto as idcompra_af_proyecto, 
        cafp.idproyecto, cafp.idproveedor, cafp.fecha_compra, 
        cafp.tipo_comprobante as tipo_comprobante, 
        cafp.serie_comprobante as serie_comprobante, 
        cafp.descripcion as descripcion, 
        cafp.subtotal as subtotal, 
        cafp.igv as igv, 
        cafp.total as monto,
        cafp.estado as estado
        FROM compra_af_proyecto as cafp
        WHERE cafp.idcompra_af_proyecto='$id_compras_x_proyecto'";

        $compra_af_p = ejecutarConsultaSimpleFila($sql);
        
        $sql_2 = "SELECT 	dcafp.idactivos_fijos as idactivos_fijos,
            dcafp.ficha_tecnica_producto as ficha_tecnica,
            dcafp.cantidad as cantidad,
            dcafp.precio_con_igv as precio_con_igv, dcafp.igv, dcafp.precio_sin_igv,
            dcafp.descuento as descuento,
            af.nombre as nombre_activo, af.imagen,
            dcafp.unidad_medida, dcafp.color
            FROM detalle_compra_af_p AS dcafp, activos_fijos AS af, unidad_medida AS um, color AS c
            WHERE dcafp.idcompra_af_proyecto='$id_compras_x_proyecto' AND  dcafp.idactivos_fijos=af.idactivos_fijos AND af.idcolor = c.idcolor AND af.idunidad_medida = um.idunidad_medida";

        $activos = ejecutarConsultaArray($sql_2);

        $results = array(
			"idcompra_af_proyecto" =>$compra_af_p['idcompra_af_proyecto'],
            "idproyecto" =>$compra_af_p['idproyecto'],
            "idproveedor" =>$compra_af_p['idproveedor'],
			"fecha_compra" =>$compra_af_p['fecha_compra'],
			"tipo_comprobante" =>$compra_af_p['tipo_comprobante'],
            "serie_comprobante" =>$compra_af_p['serie_comprobante'],
            "descripcion" =>$compra_af_p['descripcion'],
            "subtotal_compras" =>$compra_af_p['subtotal'],
            "igv_compras_proyect" =>$compra_af_p['igv'],
            "monto_total" =>$compra_af_p['monto'],
            "estado" =>$compra_af_p['estado'],
			"activos" =>$activos,
		);

        return $results ;
    }
    
    //Implementamos un método para desactivar categorías
    public function desactivar($idcompra_af_proyecto)
    {
        $sql = "UPDATE compra_af_proyecto SET estado='0' WHERE idcompra_af_proyecto='$idcompra_af_proyecto'";

        return ejecutarConsulta($sql);
    }

    //Implementamos un método para activar categorías
    public function activar($idcompra_af_proyecto)
    {
        $sql = "UPDATE compra_af_proyecto SET estado='1' WHERE idcompra_af_proyecto='$idcompra_af_proyecto'";

        return ejecutarConsulta($sql);
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idcompra_af_proyecto)
    {
        $sql = "SELECT * FROM compra_af_proyecto WHERE idcompra_af_proyecto='$idcompra_af_proyecto'";

        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para listar los registros
    public function listar_compra($nube_idproyecto)
    {
        // $idproyecto=2;
        $sql = "SELECT
            cafp.idproyecto as idproyecto,
            cafp.idcompra_af_proyecto as idcompra_af_proyecto,
            cafp.idproveedor as idproveedor,
            cafp.fecha_compra as fecha_compra,
            cafp.tipo_comprobante as tipo_comprobante,
            cafp.serie_comprobante as serie_comprobante,
            cafp.descripcion as descripcion,
            cafp.total as monto_total,
            cafp.comprobante as imagen_comprobante,
            p.razon_social as razon_social, p.telefono,
            cafp.estado as estado
            FROM compra_af_proyecto as cafp, proveedor as p 
            WHERE cafp.idproyecto='$nube_idproyecto' AND cafp.idproveedor=p.idproveedor
            ORDER BY cafp.idcompra_af_proyecto DESC";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los registros x proveedor
    public function listar_compraxporvee($nube_idproyecto)
    {
        // $idproyecto=2;
        $sql = "SELECT
            cafp.idproyecto as idproyecto,
            SUM(cafp.total) as total,
            p.idproveedor as idproveedor,
            p.razon_social as razon_social
            FROM compra_af_proyecto as cafp, proveedor as p 
            WHERE cafp.idproyecto='$nube_idproyecto' AND cafp.idproveedor=p.idproveedor GROUP BY cafp.idproveedor";
        return ejecutarConsulta($sql);
    }
    //Implementar un método para listar los registros x proveedor
    public function listar_detalle_comprax_provee($idproyecto, $idproveedor)
    {
        //var_dump($idproyecto,$idproveedor);die();
        // $idproyecto=2;
        $sql = "SELECT* FROM compra_af_proyecto WHERE idproyecto='$idproyecto' AND idproveedor='$idproveedor'";
        return ejecutarConsulta($sql);
    }
	//mostrar detalles uno a uno de la factura
    public function ver_compra($idcompra_af_proyecto)
    {
        $sql = "SELECT  
            acfp.idcompra_af_proyecto as idcompra_af_proyecto, 
            acfp.idproyecto as idproyecto, 
            acfp.idproveedor as idproveedor, 
            p.razon_social as razon_social, 
            acfp.fecha_compra as fecha_compra, 
            acfp.tipo_comprobante as tipo_comprobante, 
            acfp.serie_comprobante as serie_comprobante, 
            acfp.descripcion as descripcion, 
            acfp.subtotal as subtotal, 
            acfp.igv as igv, 
            acfp.total as total,
            acfp.fecha_compra as fecha, 
            acfp.estado as estado
            FROM compra_af_proyecto as acfp, proveedor as p 
            WHERE acfp.idcompra_af_proyecto='$idcompra_af_proyecto'  AND acfp.idproveedor = p.idproveedor";

        return ejecutarConsultaSimpleFila($sql);
    }
	//lismatamos los detalles
	public function listarDetalle($id_compra)
    {
        $sql = "SELECT 
		dcafp.idactivos_fijos as idactivos_fijos,
		dcafp.ficha_tecnica_producto as ficha_tecnica,
		dcafp.cantidad as cantidad,
		dcafp.precio_con_igv as precio_con_igv,
		dcafp.descuento as descuento,
		af.nombre as nombre
		FROM detalle_compra_af_p  dcafp, activos_fijos as af
		WHERE dcafp.idcompra_af_proyecto='$id_compra' AND  dcafp.idactivos_fijos=af.idactivos_fijos";

        return ejecutarConsulta($sql);
    }

    //pago servicio
    public function pago_servicio($idcompra_af_proyecto)
    {
        $sql = "SELECT SUM(monto) as total_pago_compras
		FROM pago_af_proyecto 
		WHERE idcompra_af_proyecto='$idcompra_af_proyecto' AND estado=1";
        return ejecutarConsultaSimpleFila($sql);
    }

    //----Comprobantes pagos-----

    public function editar_comprobante($idcompra_af_proyecto, $doc_comprobante)
    {
        //var_dump($idfacturacompra);die();
        $sql = "UPDATE compra_af_proyecto SET comprobante='$doc_comprobante' WHERE idcompra_af_proyecto ='$idcompra_af_proyecto'";
        return ejecutarConsulta($sql);
    }
    // obtebnemos los DOCS para eliminar
    public function obtener_comprobante_comprasa_af_p($idcompra_af_proyecto)
    {
        $sql = "SELECT comprobante FROM compra_af_proyecto WHERE idcompra_af_proyecto ='$idcompra_af_proyecto'";

        return ejecutarConsulta($sql);
    }

    /**========================= */
    /**seccion facturas */
    /**========================= */
    public function insertar_factura($idproyectof, $idcomp_proyecto, $codigo, $monto_compraa, $fecha_emision, $descripcion_f, $doc_img, $subtotal_compraa, $igv_compraa)
    {
        //var_dump($idproyectof,$idcomp_proyecto,$codigo,$monto_compraa,$fecha_emision,$descripcion_f,$doc_img,$subtotal_compraa,$igv_compraa);die();
        $sql = "INSERT INTO facturas_compras(idproyecto,idcompra_proyecto,codigo,monto,fecha_emision,descripcion,imagen,subtotal,igv) 
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
		idcompra_proyecto='$idcomp_proyecto',
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
    public function listar_facturas($idcompra_proyecto, $idproyecto)
    {
        //var_dump($idproyecto,$idmaquinaria);die();
        $sql = "SELECT *
		FROM facturas_compras
		WHERE idcompra_proyecto = '$idcompra_proyecto' AND  idproyecto='$idproyecto'";
        return ejecutarConsulta($sql);
    }
    public function total_monto_f($idcompra_proyecto, $idproyecto)
    {
        //var_dump($idcompra_proyecto,$idproyecto);die();

        $sql = "SELECT SUM(fs.monto) as total_mont_f
		FROM facturas_compras as fs
		WHERE fs.idcompra_proyecto='$idcompra_proyecto' AND fs.idproyecto='$idproyecto' AND  fs.estado='1'";
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
        //var_dump($idpago_af_proyecto);die();
        $sql = "UPDATE facturas_compras SET estado='1' WHERE idfacturacompra ='$idfacturacompra'";
        return ejecutarConsulta($sql);
    }
    /**=========================== */
    //SECCION PAGOS
    /**=========================== */
    public function insertar_pago($idcompra_af_proyecto_p,$idproveedor_pago,$beneficiario_pago,$forma_pago,$tipo_pago,$cuenta_destino_pago,
    $banco_pago,$titular_cuenta_pago,$fecha_pago,$monto_pago,$numero_op_pago,$descripcion_pago,$imagen1) 
    {

        $sql = "INSERT INTO pago_af_proyecto (idcompra_af_proyecto,idproveedor,beneficiario,forma_pago,tipo_pago,cuenta_destino,idbancos,titular_cuenta,
        fecha_pago,monto,numero_operacion,descripcion,imagen) 
		VALUES ('$idcompra_af_proyecto_p','$idproveedor_pago','$beneficiario_pago','$forma_pago','$tipo_pago','$cuenta_destino_pago','$banco_pago','$titular_cuenta_pago',
			'$fecha_pago','$monto_pago','$numero_op_pago','$descripcion_pago','$imagen1')";
        return ejecutarConsulta($sql);
    }
    //Implementamos un método para editar registros
    public function editar_pago($idpago_af_proyecto,$idcompra_af_proyecto_p,$idproveedor_pago,$beneficiario_pago,$forma_pago,$tipo_pago,$cuenta_destino_pago,
    $banco_pago,$titular_cuenta_pago,$fecha_pago,$monto_pago,$numero_op_pago,$descripcion_pago,$idcompra_af_proyecto,$imagen1) 
    {
        $sql = "UPDATE pago_compras SET
        idcompra_af_proyecto ='$idcompra_af_proyecto_p',
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
        WHERE idpago_af_proyecto='$idpago_af_proyecto'";
        return ejecutarConsulta($sql);
    }
    //Listar pagos-normal
    public function listar_pagos($idcompra_proyecto)
    {
        //var_dump($idproyecto,$idmaquinaria);die();
        $sql = "SELECT
		ps.idpago_af_proyecto  as idpago_af_proyecto,
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
		WHERE ps.idcompra_proyecto='$idcompra_proyecto' AND bn.idbancos=ps.idbancos";
        return ejecutarConsulta($sql);
    }
        //Listar pagos1-con detraccion --tabla Proveedor
    public function listar_pagos_compra_prov_con_dtracc($idcompra_proyecto,$tipo_pago)
    {
        //var_dump($idproyecto,$idmaquinaria);die();
        $sql = "SELECT
        ps.idpago_af_proyecto  as idpago_af_proyecto,
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
        WHERE ps.idcompra_proyecto='$idcompra_proyecto' AND bn.idbancos=ps.idbancos AND ps.tipo_pago='$tipo_pago'";
        return ejecutarConsulta($sql);
    }
    //Implementamos un método para desactivar categorías
    public function desactivar_pagos($idpago_af_proyecto)
    {
        //var_dump($idpago_af_proyecto);die();
        $sql = "UPDATE pago_compras SET estado='0' WHERE idpago_af_proyecto ='$idpago_af_proyecto'";
        return ejecutarConsulta($sql);
    }
    //Implementamos un método para activar categorías
    public function activar_pagos($idpago_af_proyecto)
    {
        $sql = "UPDATE pago_compras SET estado='1' WHERE idpago_af_proyecto ='$idpago_af_proyecto'";
        return ejecutarConsulta($sql);
    }
    //Mostrar datos para editar Pago servicio.
    public function mostrar_pagos($idpago_af_proyecto)
    {
        $sql = "SELECT
		ps.idpago_af_proyecto as idpago_af_proyecto,
		ps.idcompra_proyecto as idcompra_proyecto,
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
		WHERE idpago_af_proyecto='$idpago_af_proyecto' AND ps.idbancos = bn.idbancos";
        return ejecutarConsultaSimpleFila($sql);
    }

    // consulta para totales sin detracion
    public function suma_total_pagos($idcompra_proyecto)
    {
        $sql = "SELECT SUM(ps.monto) as total_monto
		FROM pago_compras as ps
		WHERE  ps.idcompra_proyecto='$idcompra_proyecto' AND ps.estado='1'";
        return ejecutarConsultaSimpleFila($sql);
    }
    //consultas para totales con detracion
    public function suma_total_pagos_detraccion($idcompra_proyecto,$tipopago)
    {
        $sql = "SELECT SUM(ps.monto) as total_montoo
		FROM pago_compras as ps
		WHERE  ps.idcompra_proyecto='$idcompra_proyecto' AND ps.tipo_pago='$tipopago' AND ps.estado='1'";
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
    public function obtenerImg($idpago_af_proyecto)
    {
        $sql = "SELECT imagen FROM pago_af_proyecto WHERE idpago_af_proyecto='$idpago_af_proyecto'";

        return ejecutarConsulta($sql);
    }
    //mostrar datos del proveedor y maquina en form
    public function most_datos_prov_pago($idcompra_af_proyecto)
    {
        $sql = "SELECT * FROM compra_af_proyecto as cafp, proveedor as p  WHERE cafp.idproveedor=p.idproveedor AND cafp.idcompra_af_proyecto='$idcompra_af_proyecto'";
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
