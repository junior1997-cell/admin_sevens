<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Compra
{
    //Implementamos nuestro constructor
    public function __construct()
    {
    }
    //Implementamos un método para insertar registros
    public function insertar(
        $idproyecto,
        $idproveedor,
        $fecha_compra,
        $tipo_comprovante,
        $serie_comprovante,
        $descripcion,
        $total_venta,
        $subtotal_compra,
        $igv_compra,
        $estado_detraccion,
        $idproducto,
        $unidad_medida,
        $cantidad,
        $precio_sin_igv,
        $precio_igv,
        $precio_total,
        $descuento,
        $ficha_tecnica_producto
    	) {
        //var_dump($idproyecto,$idproveedor,$fecha_compra,$tipo_comprovante,$serie_comprovante,$descripcion,$total_venta,$idproducto,$cantidad, $precio_unitario,$descuento,$ficha_tecnica_producto);die();
        $sql = "INSERT INTO compra_por_proyecto(idproyecto,idproveedor,fecha_compra,tipo_comprovante,serie_comprovante,descripcion,monto_total,subtotal_compras_proyect,igv_compras_proyect,estado_detraccion)
		VALUES ('$idproyecto','$idproveedor','$fecha_compra','$tipo_comprovante','$serie_comprovante','$descripcion','$total_venta','$subtotal_compra','$igv_compra','$estado_detraccion')";
        //return ejecutarConsulta($sql);
        $idventanew = ejecutarConsulta_retornarID($sql);

        $num_elementos = 0;
        $sw = true;

        while ($num_elementos < count($idproducto)) {
            $sql_detalle = "INSERT INTO detalle_compra(idcompra_proyecto,idproducto,unidad_medida,cantidad,precio_venta,igv,precio_igv,descuento,ficha_tecnica_producto) 
			VALUES ('$idventanew','$idproducto[$num_elementos]','$unidad_medida[$num_elementos]','$cantidad[$num_elementos]','$precio_sin_igv[$num_elementos]','$precio_igv[$num_elementos]','$precio_total[$num_elementos]','$descuento[$num_elementos]','$ficha_tecnica_producto[$num_elementos]')";
            ejecutarConsulta($sql_detalle) or ($sw = false);

            $num_elementos = $num_elementos + 1;
        }

        return $sw;
    }

    //Implementamos un método para editar registros
    /*public function editar($idcompra_por_proyecto, $trabajador_old, $trabajador, $cargo, $login, $clave, $permisos){
		if (!empty($trabajador) ) {

			$sql="UPDATE compra_por_proyecto SET idtrabajador='$trabajador', cargo='$cargo', login='$login', password='$clave' WHERE idcompra_por_proyecto='$idcompra_por_proyecto'";
			
			// desmarcamos al trabajador old como compra_por_proyecto
			$sql3="UPDATE trabajador SET estado_compra_por_proyecto='0' WHERE idtrabajador='$trabajador_old';";
			ejecutarConsulta($sql3);
			// marcamos al trabajador new como compra_por_proyecto
			$sql4="UPDATE trabajador SET estado_compra_por_proyecto='1' WHERE idtrabajador='$trabajador';";
			ejecutarConsulta($sql4);
		} else {
			$sql="UPDATE compra_por_proyecto SET idtrabajador='$trabajador_old', cargo='$cargo', login='$login', password='$clave' WHERE idcompra_por_proyecto='$idcompra_por_proyecto'";
		}
		
				 	
		
		
		$num_elementos=0;	$sw=true;

		if ($permisos != "" ) {

			ejecutarConsulta($sql);

			//Eliminamos todos los permisos asignados para volverlos a registrar
			$sqldel="DELETE FROM compra_por_proyecto_permiso WHERE idcompra_por_proyecto='$idcompra_por_proyecto'";

			ejecutarConsulta($sqldel);

			while ($num_elementos < count($permisos)){

				$sql_detalle = "INSERT INTO compra_por_proyecto_permiso(idcompra_por_proyecto, idpermiso) VALUES('$idcompra_por_proyecto', '$permisos[$num_elementos]')";
				ejecutarConsulta($sql_detalle) or $sw = false;
				$num_elementos=$num_elementos + 1;
			}
		}

		if ($permisos != "") {

			return $sw;

		} else {

			return ejecutarConsulta($sql);
		}
	}*/

    //Implementamos un método para desactivar categorías
    public function desactivar($idcompra_proyecto)
    {
        $sql = "UPDATE compra_por_proyecto SET estado='0' WHERE idcompra_proyecto='$idcompra_proyecto'";

        return ejecutarConsulta($sql);
    }

    //Implementamos un método para activar categorías
    public function activar($idcompra_por_proyecto)
    {
        $sql = "UPDATE compra_por_proyecto SET estado='1' WHERE idcompra_por_proyecto='$idcompra_por_proyecto'";

        return ejecutarConsulta($sql);
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idcompra_por_proyecto)
    {
        $sql = "SELECT * FROM compra_por_proyecto WHERE idcompra_por_proyecto='$idcompra_por_proyecto'";

        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para listar los registros
    public function listar_compra($nube_idproyecto)
    {
        // $idproyecto=2;
        $sql = "SELECT
		cpp.idproyecto as idproyecto,
		cpp.idcompra_proyecto as idcompra_proyecto,
		cpp.idproveedor as idproveedor,
		cpp.fecha_compra as fecha_compra,
		cpp.tipo_comprovante as tipo_comprovante,
		cpp.serie_comprovante as serie_comprovante,
		cpp.descripcion as descripcion,
		cpp.monto_total as monto_total,
		cpp.imagen_comprobante as imagen_comprobante,
		cpp.estado_detraccion as estado_detraccion,
		p.razon_social as razon_social,
		cpp.estado as estado
		FROM compra_por_proyecto as cpp, proveedor as p 
		WHERE cpp.idproyecto='$nube_idproyecto' AND cpp.idproveedor=p.idproveedor
		ORDER BY cpp.idcompra_proyecto DESC ";
        return ejecutarConsulta($sql);
    }
    //Implementar un método para listar los registros x proveedor
    public function listar_compraxporvee($nube_idproyecto)
    {
        // $idproyecto=2;
        $sql = "SELECT
		cpp.idproyecto as idproyecto,
        COUNT(cpp.idcompra_proyecto) as cantidad,
        SUM(cpp.monto_total) as total,
        p.idproveedor as idproveedor,
        p.razon_social as razon_social
		FROM compra_por_proyecto as cpp, proveedor as p 
		WHERE cpp.idproyecto='$nube_idproyecto' AND cpp.idproveedor=p.idproveedor GROUP BY cpp.idproveedor";
        return ejecutarConsulta($sql);
    }
    //Implementar un método para listar los registros x proveedor
    public function listar_detalle_comprax_provee($idproyecto, $idproveedor)
    {
        //var_dump($idproyecto,$idproveedor);die();
        // $idproyecto=2;
        $sql = "SELECT* FROM compra_por_proyecto WHERE idproyecto='$idproyecto' AND idproveedor='$idproveedor'";
        return ejecutarConsulta($sql);
    }
	//mostrar detalles uno a uno de la factura
    public function ver_compra($idcompra_proyecto)
    {
        $sql = "SELECT  
		cpp.idcompra_proyecto as idcompra_proyecto, 
		cpp.idproyecto as idproyecto, 
		cpp.idproveedor as idproveedor, 
		p.razon_social as razon_social, 
		cpp.fecha_compra as fecha_compra, 
		cpp.tipo_comprovante as tipo_comprovante, 
		cpp.serie_comprovante as serie_comprovante, 
		cpp.descripcion as descripcion, 
		cpp.subtotal_compras_proyect as subtotal_compras, 
		cpp.igv_compras_proyect as igv_compras_proyect, 
		cpp.monto_total as monto_total,
		cpp.fecha as fecha, 
		cpp.estado as estado
		FROM compra_por_proyecto as cpp, proveedor as p 
		WHERE idcompra_proyecto='$idcompra_proyecto'  AND cpp.idproveedor = p.idproveedor";

        return ejecutarConsultaSimpleFila($sql);
    }
	//lismatamos los detalles
	public function listarDetalle($id_compra)
    {
        $sql = "SELECT 
		dp.idproducto as idproducto,
		dp.ficha_tecnica_producto as ficha_tecnica,
		dp.cantidad as cantidad,
		dp.precio_venta as precio_venta,
		dp.descuento as descuento,
		p.nombre as nombre
		FROM detalle_compra  dp, producto as p
		WHERE idcompra_proyecto='$id_compra' AND  dp.idproducto=p.idproducto";

        return ejecutarConsulta($sql);
    }


    //pago servicio
    public function pago_servicio($idcompra_proyecto)
    {
        $sql = "SELECT SUM(monto) as total_pago_compras
		FROM pago_compras 
		WHERE idcompra_proyecto='$idcompra_proyecto' AND estado=1";
        return ejecutarConsultaSimpleFila($sql);
    }

    //----Comprobantes pagos-----

    public function editar_comprobante($comprobante_c, $doc_comprobante)
    {
        //var_dump($idfacturacompra);die();
        $sql = "UPDATE compra_por_proyecto SET imagen_comprobante='$doc_comprobante' WHERE idcompra_proyecto ='$comprobante_c'";
        return ejecutarConsulta($sql);
    }
    // obtebnemos los DOCS para eliminar
    public function obtener_comprobante($comprobante_c)
    {
        $sql = "SELECT imagen_comprobante FROM compra_por_proyecto WHERE idcompra_proyecto ='$comprobante_c'";

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
        //var_dump($idpago_compras);die();
        $sql = "UPDATE facturas_compras SET estado='1' WHERE idfacturacompra ='$idfacturacompra'";
        return ejecutarConsulta($sql);
    }
    /**=========================== */
    //SECCION PAGOS
    /**=========================== */
    public function insertar_pago(
        $idcompra_proyecto_p,
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
        /*var_dump($idcompra_proyecto_p,$idproveedor_pago,$beneficiario_pago,$forma_pago,$tipo_pago,$cuenta_destino_pago,$banco_pago,
         $titular_cuenta_pago,$fecha_pago,$monto_pago,$numero_op_pago,$descripcion_pago,$imagen1);die();*/
        ///idproyecto
        $sql = "INSERT INTO pago_compras (idcompra_proyecto,idproveedor,beneficiario,forma_pago,tipo_pago,cuenta_destino,idbancos,titular_cuenta,fecha_pago,monto,numero_operacion,descripcion,imagen) 
		VALUES ('$idcompra_proyecto_p',
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
        $idcompra_proyecto_p,
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
		idcompra_proyecto ='$idcompra_proyecto_p',
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
    public function listar_pagos($idcompra_proyecto)
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
		WHERE ps.idcompra_proyecto='$idcompra_proyecto' AND bn.idbancos=ps.idbancos";
        return ejecutarConsulta($sql);
    }
        //Listar pagos1-con detraccion --tabla Proveedor
    public function listar_pagos_compra_prov_con_dtracc($idcompra_proyecto,$tipo_pago)
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
        WHERE ps.idcompra_proyecto='$idcompra_proyecto' AND bn.idbancos=ps.idbancos AND ps.tipo_pago='$tipo_pago'";
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
		WHERE idpago_compras='$idpago_compras' AND ps.idbancos = bn.idbancos";
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
    public function obtenerImg($idpago_compras)
    {
        $sql = "SELECT imagen FROM pago_compras WHERE idpago_compras='$idpago_compras'";

        return ejecutarConsulta($sql);
    }
    //mostrar datos del proveedor y maquina en form
    public function most_datos_prov_pago($idcompra_proyecto)
    {
        $sql = " SELECT * FROM compra_por_proyecto as cpp, proveedor as p  WHERE cpp.idproveedor=p.idproveedor AND cpp.idcompra_proyecto='$idcompra_proyecto'";
        return ejecutarConsultaSimpleFila($sql);
    }
}

?>
