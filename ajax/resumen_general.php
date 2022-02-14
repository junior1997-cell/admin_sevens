<?php
ob_start();
if (strlen(session_id()) < 1){
	session_start();//Validamos si existe o no la sesión
}
require_once "../modelos/Resumen_general.php";
require_once "../modelos/Fechas.php";

$resumen_general=new Resumen_general();

switch ($_GET["op"]){

	case 'listar_r_compras':

		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los materials logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al material logueado y autorizado.
			if ($_SESSION['resumen_general']==1)
			{
				$rspta=$resumen_general->r_compras($_POST['idproyecto']);
		 		//Codificar el resultado utilizando json
		 		echo json_encode($rspta);
			//Fin de las validaciones de acceso
			}
			else
			{
		  	require 'noacceso.php';
			}
		}		
	break;

	case 'ver_detalle_compras':

		$rspta  =  $resumen_general->detalles_compras($_GET['id_compra']);
		$rspta2 =  $resumen_general->ver_compras($_GET['id_compra']);

		$subtotal=0;
		$ficha='';
		echo '<thead style="background-color:#A9D0F5">
				<th>Ficha técnica</th>
				<th>Material</th>
				<th>Cantidad</th>
				<th>Precio Compra</th>
				<th>Descuento</th>
				<th>Subtotal</th>
		</thead>';

		while ($reg = $rspta->fetch_object())
		{
			$subtotal = ($reg->cantidad*$reg->precio_venta)-$reg->descuento;
			
			empty($reg->ficha_tecnica)
			? ($ficha = '<a ><i class="far fa-file-pdf fa-2x" style="color:#000000c4"></i></a>')
			: ($ficha = '<a target="_blank" href="../dist/ficha_tecnica_materiales/' . $reg->ficha_tecnica . '"><i class="far fa-file-pdf fa-2x" style="color:#ff0000c4"></i></a>');
			echo '<tr class="filas">
					<td>'.$ficha.'</td>
					<td>'.$reg->nombre.'</td>
					<td>'.$reg->cantidad.'</td>
					<td>'.$reg->precio_venta.'</td>
					<td>'.$reg->descuento.'</td>
					<td>'.$subtotal.'</td></tr>';
		}
		echo '<tfoot>
				<td colspan="4"></td>
				<th class="text-center">
					<h5>Subtotal</h5>
					<h5>IGV</h5>
					<h5>TOTAL</h5>
				</th>
				<th>
					<h5 class="text-right subtotal"  style="font-weight: bold;">S/'.$rspta2['subtotal_compras'].'</h5>
					<h5 class="text-right igv_comp" style="font-weight: bold;">S/'.$rspta2['igv_compras_proyect'].'</h5>
					<b>
						<h4 class="text-right total"  style="font-weight: bold;">S/'.$rspta2['monto_total'].'</h4>
					</b>
			</tfoot>';

    break;

	case 'listar_r_serv_maquinaria':

		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los materials logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al material logueado y autorizado.
			if ($_SESSION['resumen_general']==1)
			{
				$tipo='1';
				$rspta=$resumen_general->r_serv_maquinaria_equipos($_POST['idproyecto'],$tipo);
		 		//Codificar el resultado utilizando json
		 		echo json_encode($rspta);
			//Fin de las validaciones de acceso
			}
			else
			{
		  	require 'noacceso.php';
			}
		}		
	break;

	case 'listar_r_serv_equipos':

		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los materials logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al material logueado y autorizado.
			if ($_SESSION['resumen_general']==1)
			{
				$tipo='2';
				$rspta=$resumen_general->r_serv_maquinaria_equipos
				($_POST['idproyecto'],$tipo);
		 		//Codificar el resultado utilizando json
		 		echo json_encode($rspta);
			//Fin de las validaciones de acceso
			}
			else
			{
		  	require 'noacceso.php';
			}
		}		
	break;
			
	case 'ver_detalle_maquina':
		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['resumen_general']==1)
			{
				$idmaquinaria=$_GET["idmaquinaria"];
				$idproyecto=$_GET["idproyecto"];

				$rspta=$resumen_general->ver_detalle_maq_equ($idmaquinaria,$idproyecto);
				$fecha_entreg='';
				$fecha_recoj='';
				$fecha='';
				//Vamos a declarar un array
					$data= Array();
					
					while ($reg=$rspta->fetch_object()){

						if (empty($reg->fecha_recojo) || $reg->fecha_recojo=='0000-00-00') {
							$fechas=new FechaEs($reg->fecha_entrega);
							$dia=$fechas->getDDDD().PHP_EOL;
							$mun_dia=$fechas->getdd().PHP_EOL;
							$mes=$fechas->getMMMM().PHP_EOL;
							$anio=$fechas->getYYYY().PHP_EOL;
							$fecha_entreg="$dia, $mun_dia de $mes del $anio";
							$fecha="<b style=".'color:#1570cf;'.">$fecha_entreg</b>";
						}else{
							$fechas=new FechaEs($reg->fecha_entrega);
							//----------
							$dia=$fechas->getDDDD().PHP_EOL;
							$mun_dia=$fechas->getdd().PHP_EOL;
							$mes=$fechas->getMMMM().PHP_EOL;
							$anio=$fechas->getYYYY().PHP_EOL;
							$fecha_entreg="$dia, $mun_dia de $mes del $anio";
							//----------
							$fechas=new FechaEs($reg->fecha_recojo);
							$dia2=$fechas->getDDDD().PHP_EOL;
							$mun_dia2=$fechas->getdd().PHP_EOL;
							$mes2=$fechas->getMMMM().PHP_EOL;
							$anio2=$fechas->getYYYY().PHP_EOL;
							$fecha_recoj="$dia2, $mun_dia2 de $mes2 del $anio2";
							$fecha="<b style=".'color:#1570cf;'.">$fecha_entreg </b> / <br> <b  style=".'color:#ff0000;'.">$fecha_recoj<b>";

						}
						if (strlen($reg->descripcion) >= 20 ) { $descripcion = substr($reg->descripcion, 0, 20).'...';  } else { $descripcion = $reg->descripcion; }
						
						$tool = '"tooltip"';   $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>"; 
						
						$data[]=array(
							"0"=>$fecha,
							"1"=>empty($reg->unidad_medida)?'-':$reg->unidad_medida,
							"2"=>empty($reg->cantidad)?'-':$reg->cantidad,
							"3"=>empty($reg->costo_unitario) || $reg->costo_unitario=='0.00'?'-':number_format($reg->costo_unitario, 2, '.', ','),
							"4"=>empty($reg->costo_parcial)?'-':number_format($reg->costo_parcial, 2, '.', ','),
							"5"=>empty($reg->descripcion)?'-':'<div data-toggle="tooltip" data-original-title="'.$reg->descripcion.'">'.$descripcion.'</div>',
							);
					}
					$results = array(
						"sEcho"=>1, //Información para el datatables
						"iTotalRecords"=>count($data), //enviamos el total registros al datatable
						"iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
						"data"=>$data);
					echo json_encode($results);
			//Fin de las validaciones de acceso
			}
			else
			{
		  	require 'noacceso.php';
			}
		}		
	break;
	case 'listar_r_transportes':

		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los materials logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al material logueado y autorizado.
			if ($_SESSION['resumen_general']==1)
			{
				$rspta=$resumen_general->r_transportes($_POST['idproyecto']);
		 		//Codificar el resultado utilizando json
		 		echo json_encode($rspta);
			//Fin de las validaciones de acceso
			}
			else
			{
		  	require 'noacceso.php';
			}
		}		
	break;

	case 'listar_r_hospedajes':

		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los materials logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al material logueado y autorizado.
			if ($_SESSION['resumen_general']==1)
			{
				$rspta=$resumen_general->r_hospedajes($_POST['idproyecto']);
		 		//Codificar el resultado utilizando json
		 		echo json_encode($rspta);
			//Fin de las validaciones de acceso
			}
			else
			{
		  	require 'noacceso.php';
			}
		}		
	break;

	case 'listar_r_comidas_extras':

		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los materials logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al material logueado y autorizado.
			if ($_SESSION['resumen_general']==1)
			{
				$rspta=$resumen_general->r_comidas_extras($_POST['idproyecto']);
		 		//Codificar el resultado utilizando json
		 		echo json_encode($rspta);
			//Fin de las validaciones de acceso
			}
			else
			{
		  	require 'noacceso.php';
			}
		}		
	break;

	case 'listar_r_breaks':

		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los materials logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al material logueado y autorizado.
			if ($_SESSION['resumen_general']==1)
			{
				$rspta=$resumen_general->r_breaks($_POST['idproyecto']);
		 		//Codificar el resultado utilizando json
		 		echo json_encode($rspta);
			//Fin de las validaciones de acceso
			}
			else
			{
		  	require 'noacceso.php';
			}
		}		
	break;

	case 'listar_comprobantes_breaks':

		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
		  //Validamos el acceso solo al usuario logueado y autorizado.
		  if ($_SESSION['resumen_general']==1)
		  {	
			$rspta=$resumen_general->listar_comprobantes_breaks($_GET['idsemana_break']);

			//Vamos a declarar un array
			$data= Array();
			$comprobante='';
			$subtotal=0;
			$igv=0;
			$monto=0;

			while ($reg=$rspta->fetch_object()){
			  $subtotal=round($reg->subtotal, 2);
			  $igv=round($reg->igv, 2);
			  $monto=round($reg->monto, 2 );
			  if (strlen($reg->descripcion) >= 20 ) { $descripcion = substr($reg->descripcion, 0, 20).'...';  } else { $descripcion = $reg->descripcion; }
			  empty($reg->comprobante)?$comprobante='<div><center><a type="btn btn-danger" class=""><i class="far fa-times-circle fa-2x"></i></a></center></div>':$comprobante='<div><center><a type="btn btn-danger" target="_blank" href="../dist/img/comprob_breaks/'.$reg->comprobante.'"><i class="fas fa-file-invoice fa-2x"></i></a></center></div>';
			  $tool = '"tooltip"';   $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>"; 
			  $data[]=array(		
				"0"=> empty($reg->forma_de_pago)?' - ':$reg->forma_de_pago,	 				
				"1"=> empty($reg->tipo_comprobante)?' - ':$reg->tipo_comprobante,	 				
				"2"=> empty($reg->nro_comprobante)?' - ':$reg->nro_comprobante,	 				
				"3"=>date("d/m/Y", strtotime($reg->fecha_emision)),
				"4"=>number_format($subtotal, 2, '.', ','), 
				"5"=>number_format($igv, 2, '.', ','),
				"6"=>number_format($monto, 2, '.', ','),
				"7"=>empty($reg->descripcion)?'-':'<div data-toggle="tooltip" data-original-title="'.$reg->descripcion.'">'.$descripcion.'</div>',
				"8"=>$comprobante
				);

			}
			$results = array(
			  "sEcho"=>1, //Información para el datatables
			  "iTotalRecords"=>count($data), //enviamos el total registros al datatable
			  "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
			  "data"=>$data
			 );
			echo json_encode($results);
		  //Fin de las validaciones de acceso
		  }
		  else
		  {
			require 'noacceso.php';
		  }
		}
	break;

	
	case 'listar_r_pensiones':

		$rspta=$resumen_general->r_pensiones($_POST['idproyecto']);
		//Codificar el resultado utilizando json
		echo json_encode($rspta);
	  
	break;

	case 'ver_detalle_x_servicio':

		$rspta=$resumen_general->ver_detalle_x_servicio($_GET['idpension']);
		//Vamos a declarar un array
		$data= Array();
		$cont=1;
		while ($reg=$rspta->fetch_object()){ 
		  $data[]=array(
			"0"=>'<div class="user-block">
			  <span style="font-weight: bold;" ><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'.$cont.'. '.$reg->nombre_servicio .'</p></span></div>',
			"1"=>'<b>'.number_format($reg->precio, 2, '.', ',').'</b>', 
			"2"=>'<b>'.$reg->cantidad_total_platos.'</b>', 
			"3"=>'<b>'.number_format($reg->adicional_descuento, 2, '.', ',').'</b>', 
			"4"=>'<b>'.number_format($reg->total, 2, '.', ',').'</b>'
		  );
		  $cont++;
		}
		$results = array(
		  "sEcho"=>1, //Información para el datatables
		  "iTotalRecords"=>count($data), //enviamos el total registros al datatable
		  "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
		  "aaData"=>$data);
		echo json_encode($results);
		
	break;
	        
	case 'listar_comprobantes_pension':

		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
		  //Validamos el acceso solo al usuario logueado y autorizado.
		  if ($_SESSION['resumen_general']==1)
		  {	
			//$idpension_f ='5';
			//$_GET['idpension_f']
			$rspta=$resumen_general->listar_comprobantes_pension($_GET['idpension']);

			//Vamos a declarar un array
			$data= Array();
			$comprobante='';
			$subtotal=0;
			$igv=0;
			$monto=0;

			while ($reg=$rspta->fetch_object()){
			  $subtotal=round($reg->subtotal, 2);
			  $igv=round($reg->igv, 2);
			  $monto=round($reg->monto, 2 );
			 
			  if (strlen($reg->descripcion) >= 20 ) { $descripcion = substr($reg->descripcion, 0, 20).'...';  } else { $descripcion = $reg->descripcion; }

			  empty($reg->comprobante)?$comprobante='<div><center><a type="btn btn-danger" class=""><i class="far fa-times-circle fa-2x"></i></a></center></div>':$comprobante='<div><center><a type="btn btn-danger" target="_blank"  href="../dist/img/comprob_pension/'.$reg->comprobante.'" ><i class="fas fa-file-invoice fa-2x"></i></a></center></div>';
			  
			  $tool = '"tooltip"';   $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>"; 
			 
			  $data[]=array(
				"0"=> empty($reg->forma_de_pago)?' - ':$reg->forma_de_pago,	 				
				"1"=> empty($reg->tipo_comprobante)?' - ':$reg->tipo_comprobante,	 				
				"2"=> empty($reg->nro_comprobante)?' - ':$reg->nro_comprobante,	 				
				"3"=>date("d/m/Y", strtotime($reg->fecha_emision)),
				"4"=>number_format($subtotal, 2, '.', ','), 
				"5"=>number_format($igv, 2, '.', ','),
				"6"=>number_format($monto, 2, '.', ','),
				"7"=>empty($reg->descripcion)?'-':'<div data-toggle="tooltip" data-original-title="'.$reg->descripcion.'">'.$descripcion.'</div>',
				"8"=>$comprobante,
				);

			}
			//$suma=array_sum($rspta->fetch_object()->monto);
			$results = array(
			  "sEcho"=>1, //Información para el datatables
			  "iTotalRecords"=>count($data), //enviamos el total registros al datatable
			  "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
			  "data"=>$data
			 );
			echo json_encode($results);
		  //Fin de las validaciones de acceso
		  }
		  else
		  {
			require 'noacceso.php';
		  }
		}
	break;

	case 'listar_r_trab_administrativo':

		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los materials logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al material logueado y autorizado.
			if ($_SESSION['resumen_general']==1)
			{
				$rspta=$resumen_general->r_trab_administrativo($_POST['idproyecto']);
		 		//Codificar el resultado utilizando json
		 		echo json_encode($rspta);
			//Fin de las validaciones de acceso
			}
			else
			{
		  	require 'noacceso.php';
			}
		}	

	break;
	case 'ver_detalle_pagos_x_trab_adms':

		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los materials logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al material logueado y autorizado.
			if ($_SESSION['resumen_general']==1)
			{
				//$_POST['idtrabajador_por_proyecto']
				$rspta=$resumen_general->r_detalle_trab_administrativo($_POST['idtrabajador_por_proyecto']);
		 		//Codificar el resultado utilizando json
		 		echo json_encode($rspta);
			//Fin de las validaciones de acceso
			}
			else
			{
		  	require 'noacceso.php';
			}
		}	

	break;
	
	case 'select_proveedores':

		$rspta=$resumen_general->select_proveedores();
		$estado=true;
		while ($reg = $rspta->fetch_object())  {
			
			if ($estado) {
				echo '<option>Seleccionar proveedor</option>';
				$estado=false;
			}

		  echo '<option  value='.$reg->idproveedor. '>' . $reg->razon_social .'-'. $reg->ruc.'</option>';
		}

	break;

	case 'selecct_trabajadores':

		$rspta=$resumen_general->selecct_trabajadores($_GET['idproyecto']);
		$estado=true;
		while ($reg = $rspta->fetch_object())  {

			if ($estado) {
				echo '<option>Seleccionar trabajador</option>';
				$estado=false;
			}
			  echo '<option  value=' . $reg->idtrabajador_por_proyecto  . '>' . $reg->nombres .'-'.$reg->numero_documento.'</option>';
		}

	break;

	case 'listar_r_trabajador_obrero':

		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los materials logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al material logueado y autorizado.
			if ($_SESSION['resumen_general']==1)
			{
				$rspta=$resumen_general->r_trabajador_obrero($_POST['idproyecto']);
		 		//Codificar el resultado utilizando json
		 		echo json_encode($rspta);
			//Fin de las validaciones de acceso
			}
			else
			{
		  	require 'noacceso.php';
			}
		}	

	break;

	case 'ver_detalle_pagos_x_trab_obrero':

		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los materials logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al material logueado y autorizado.
			if ($_SESSION['resumen_general']==1)
			{
				//$_POST['idtrabajador_por_proyecto']
				$rspta=$resumen_general->r_detalle_x_obrero($_POST['idtrabajador_por_proyecto']);
		 		//Codificar el resultado utilizando json
		 		echo json_encode($rspta);
			//Fin de las validaciones de acceso
			}
			else
			{
		  	require 'noacceso.php';
			}
		}	

	break;

	
	


	case 'salir':
		//Limpiamos las variables de sesión   
        session_unset();
        //Destruìmos la sesión
        session_destroy();
        //Redireccionamos al login
        header("Location: ../index.php");

	break;
}
ob_end_flush();
?>