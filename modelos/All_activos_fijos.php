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
        $a = array(); $b = array();

        // $idproyecto=2;
        $sql_1 = "SELECT
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

        $general = ejecutarConsultaArray($sql_1);

        if (!empty($general)) {

            foreach ($general as $key => $value) {

                $id_af_g=$value['idcompra_af_general'];

                $sql_1_2 = "SELECT SUM(monto) as total_pago_compras_af FROM pago_af_general WHERE idcompra_af_general='$id_af_g' AND estado=1";
                $total_pago= ejecutarConsultaSimpleFila($sql_1_2);
                

                $a[]=array(

                    "idtabla"=>$value['idcompra_af_general'],
                    "idproyecto"=>'',
                    "idproveedor"=>$value['idproveedor'],
                    "fecha_compra"=>$value['fecha_compra'],
                    "tipo_comprobante"=>$value['tipo_comprobante'],
                    "serie_comprobante"=>$value['serie_comprobante'],
                    "descripcion"=>$value['descripcion'],
                    "total"=>(empty($value['total'])) ? '0' :$value['total'],
                    "imagen_comprobante"=>$value['imagen_comprobante'],
                    "razon_social"=>$value['razon_social'],
                    "telefono"=>$value['telefono'],
                    "estado"=>$value['estado'],
                    "codigo_proyecto"=>'',
                    "deposito"=> (empty($total_pago)) ? '0' : (empty($total_pago['total_pago_compras_af'])) ? '0' : $total_pago['total_pago_compras_af'] 
                );
            }
        }
           
        $sql_2="SELECT
            cafp.idproyecto as idproyecto,
            cafp.idcompra_af_proyecto as idcompra_af_proyecto,
            cafp.idproveedor as idproveedor,
            cafp.fecha_compra as fecha_compra,
            cafp.tipo_comprobante as tipo_comprobante,
            cafp.serie_comprobante as serie_comprobante,
            cafp.descripcion as descripcion,
            cafp.total as total,
            cafp.comprobante as imagen_comprobante,
            p.razon_social as razon_social, p.telefono,
            cafp.estado as estado,
            proy.nombre_proyecto as nombre_proyecto,
            proy.nombre_codigo as nombre_codigo
            FROM compra_af_proyecto as cafp, proveedor as p, proyecto as proy
            WHERE cafp.idproveedor=p.idproveedor
            AND cafp.idproyecto=proy.idproyecto 
            ORDER BY cafp.idcompra_af_proyecto DESC";

        $proyecto  = ejecutarConsultaArray($sql_2);

        if (!empty($proyecto)) {

            foreach ($proyecto as $key => $value) {

                $id_af_p=$value['idcompra_af_proyecto'];

                $sql_2_2 = "SELECT SUM(monto) as total_pago_compras FROM pago_af_proyecto WHERE idcompra_af_proyecto='$id_af_p' AND estado=1";
                $total_pago= ejecutarConsultaSimpleFila($sql_2_2);

                $b[]=array(

                    "idtabla"=>$value['idcompra_af_proyecto'],
                    "idproyecto"=>$value['idproyecto'],
                    "idproveedor"=>$value['idproveedor'],
                    "fecha_compra"=>$value['fecha_compra'],
                    "tipo_comprobante"=>$value['tipo_comprobante'],
                    "serie_comprobante"=>$value['serie_comprobante'],
                    "descripcion"=>$value['descripcion'],
                    "total"=>(empty($value['total'])) ? '0' :$value['total'],
                    "imagen_comprobante"=>$value['imagen_comprobante'],
                    "razon_social"=>$value['razon_social'],
                    "telefono"=>$value['telefono'],
                    "estado"=>$value['estado'],
                    "codigo_proyecto"=>$value['nombre_codigo'],
                    "deposito"=>(empty($total_pago)) ? '0' : (empty($total_pago['total_pago_compras'])) ? '0' : $total_pago['total_pago_compras']

                );
            }
        }

        $results = array_merge($a,$b);

        return $results;
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
    public function total_pago_compras_af($idcompra_af_general)
    {
        $sql = "SELECT SUM(monto) as total_pago_compras_af FROM pago_af_general WHERE idcompra_af_general='$idcompra_af_general' AND estado=1";
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
    /**=========================== */
    //SECCION PAGOS
    /**=========================== */
    public function insertar_pago($idcompra_af_general_p,$beneficiario_pago,$forma_pago,$tipo_pago,$cuenta_destino_pago,$banco_pago,$titular_cuenta_pago,
        $fecha_pago,$monto_pago,$numero_op_pago,$descripcion_pago,$imagen1) 
    {
            $sql = "INSERT INTO pago_af_general(idcompra_af_general,beneficiario,forma_pago,tipo_pago,cuenta_destino,idbancos,
            titular_cuenta,fecha_pago,monto,numero_operacion,descripcion,imagen) 
            VALUES('$idcompra_af_general_p',
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
    public function editar_pago($idpago_af_general,$idcompra_af_general_p,$beneficiario_pago,$forma_pago,$tipo_pago,$cuenta_destino_pago,
        $banco_pago,$titular_cuenta_pago,$fecha_pago,$monto_pago,$numero_op_pago,$descripcion_pago,$imagen1) 
    {
                
        $sql = "UPDATE pago_af_general SET
        idcompra_af_general ='$idcompra_af_general_p',
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
        WHERE idpago_af_general='$idpago_af_general'";
        return ejecutarConsulta($sql);
    }

    //Listar pagos-normal
    public function listar_pagos_af_g($idcompra_af_general)
    {

        $sql = "SELECT
            pafg.idpago_af_general  as idpago_af_general,
            pafg.forma_pago as forma_pago,
            pafg.tipo_pago as tipo_pago,
            pafg.beneficiario as beneficiario,
            pafg.cuenta_destino as cuenta_destino,
            pafg.titular_cuenta as titular_cuenta,
            pafg.fecha_pago as fecha_pago,
            pafg.descripcion as descripcion,
            pafg.idbancos as id_banco,
            bn.nombre as banco,
            pafg.numero_operacion as numero_operacion,
            pafg.monto as monto,
            pafg.imagen as imagen,
            pafg.estado as estado
            FROM pago_af_general pafg, bancos as bn 
            WHERE pafg.idcompra_af_general='$idcompra_af_general' AND bn.idbancos=pafg.idbancos";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para desactivar categorías
    public function desactivar_pagos($idcompra_af_general)
    {
        //var_dump($idpago_compras);die();
        $sql = "UPDATE pago_af_general SET estado='0' WHERE idpago_af_general ='$idcompra_af_general'";
        return ejecutarConsulta($sql);
    }
    //Implementamos un método para activar categorías
    public function activar_pagos($idcompra_af_general)
    {
        $sql = "UPDATE pago_af_general SET estado='1' WHERE idpago_af_general ='$idcompra_af_general'";
        return ejecutarConsulta($sql);
    }
    //Mostrar datos para editar Pago servicio.
    public function mostrar_pagos($idcompra_af_general)
    {
        $sql = "SELECT
            pafg.idpago_af_general as idpago_af_general,
            pafg.idcompra_af_general as idcompra_af_general,
            pafg.forma_pago as forma_pago,
            pafg.tipo_pago as tipo_pago,
            pafg.beneficiario as beneficiario,
            pafg.cuenta_destino as cuenta_destino,
            pafg.titular_cuenta as titular_cuenta,
            pafg.fecha_pago as fecha_pago,
            pafg.descripcion as descripcion,
            pafg.idbancos as idbancos,
            bn.nombre as banco,
            pafg.numero_operacion as numero_operacion,
            pafg.monto as monto,
            pafg.imagen as imagen,
            pafg.estado as estado
            FROM pago_af_general pafg, bancos as bn
            WHERE pafg.idpago_af_general='$idcompra_af_general' AND pafg.idbancos = bn.idbancos";

        return ejecutarConsultaSimpleFila($sql);
    }

    // consulta para totales
    public function suma_total_pagos($idcompra_af_general)
    {
        $sql = "SELECT SUM(pafg.monto) as total_monto
		FROM pago_af_general as pafg
		WHERE  pafg.idcompra_af_general='$idcompra_af_general' AND pafg.estado='1'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // obtebnemos los DOCS para eliminar
    public function obtenerImg($idpago_af_general)
    {
        $sql = "SELECT imagen FROM pago_af_general WHERE idpago_af_general='$idpago_af_general'";

        return ejecutarConsulta($sql);
    }
    //mostrar datos del proveedor y maquina en form
    public function most_datos_prov_pago($idcompra_af_general)
    {
        $sql = "SELECT * FROM compra_af_general as cafg, proveedor as p  WHERE cafg.idproveedor=p.idproveedor AND cafg.idcompra_af_general='$idcompra_af_general'";
        return ejecutarConsultaSimpleFila($sql);
    }

}

?>
