<?php
	ob_start();
	if (strlen(session_id()) < 1){
		session_start();//Validamos si existe o no la sesión
	}

	if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
	} else {
		//Validamos el acceso solo al material logueado y autorizado.
		if ($_SESSION['subcontrato']==1){

			require_once "../modelos/Sub_contrato.php";

			$sub_contrato=new Sub_contrato();

      date_default_timezone_set('America/Lima');
      $date_now = date("d-m-Y h.i.s A");

      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

			$idproyecto          = isset($_POST["idproyecto"])? limpiarCadena($_POST["idproyecto"]):"";	
			$idproveedor         = isset($_POST["idproveedor"])? limpiarCadena($_POST["idproveedor"]):"";	
			$ruc_proveedor         = isset($_POST["ruc_proveedor"])? limpiarCadena($_POST["ruc_proveedor"]):"";	
			$idsubcontrato       = isset($_POST["idsubcontrato"])? limpiarCadena($_POST["idsubcontrato"]):"";	
			$fecha_subcontrato   = isset($_POST["fecha_subcontrato"])? limpiarCadena($_POST["fecha_subcontrato"]):"";

			$descripcion	       = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";

			$forma_de_pago       = isset($_POST["forma_de_pago"])? limpiarCadena($_POST["forma_de_pago"]):"";
			$tipo_comprobante    = isset($_POST["tipo_comprobante"])? limpiarCadena($_POST["tipo_comprobante"]):"";
			$numero_comprobante  = isset($_POST["numero_comprobante"])? limpiarCadena($_POST["numero_comprobante"]):"";
			$subtotal            = isset($_POST["subtotal"])? limpiarCadena($_POST["subtotal"]):"";
			$igv                 = isset($_POST["igv"])? limpiarCadena($_POST["igv"]):"";
			$costo_parcial       = isset($_POST["costo_parcial"])? limpiarCadena($_POST["costo_parcial"]):"";
			$val_igv             = isset($_POST["val_igv"])? limpiarCadena($_POST["val_igv"]):"";
      $tipo_gravada        = isset($_POST["tipo_gravada"])? limpiarCadena($_POST["tipo_gravada"]):"";

			$foto2               = isset($_POST["doc1"]) ? limpiarCadena($_POST["doc1"]) : "";

			//....::::::::::::. Datos pagod sub contrato .........::::::::::::::::::::::::::::::::::::::.

			$idpago_subcontrato  = isset($_POST["idpago_subcontrato"]) ? limpiarCadena($_POST["idpago_subcontrato"]) : "";
			$idsubcontrato_pago  = isset($_POST["idsubcontrato_pago"]) ? limpiarCadena($_POST["idsubcontrato_pago"]) : "";
			$beneficiario_pago   = isset($_POST["beneficiario_pago"]) ? limpiarCadena($_POST["beneficiario_pago"]) : "";
			$forma_pago          = isset($_POST["forma_pago"]) ? limpiarCadena($_POST["forma_pago"]) : "";
			$tipo_pago           = isset($_POST["tipo_pago"]) ? limpiarCadena($_POST["tipo_pago"]) : "";
			$cuenta_destino_pago = isset($_POST["cuenta_destino_pago"]) ? limpiarCadena($_POST["cuenta_destino_pago"]) : "";
			$banco_pago          = isset($_POST["banco_pago"]) ? limpiarCadena($_POST["banco_pago"]) : "";
			$titular_cuenta_pago = isset($_POST["titular_cuenta_pago"]) ? limpiarCadena($_POST["titular_cuenta_pago"]) : "";
			$fecha_pago          = isset($_POST["fecha_pago"]) ? limpiarCadena($_POST["fecha_pago"]) : "";
			$monto_pago          = isset($_POST["monto_pago"]) ? limpiarCadena($_POST["monto_pago"]) : "";
			$numero_op_pago      = isset($_POST["numero_op_pago"]) ? limpiarCadena($_POST["numero_op_pago"]) : "";
			$descripcion_pago    = isset($_POST["descripcion_pago"]) ? limpiarCadena($_POST["descripcion_pago"]) : "";

			$imagen1             = isset($_POST["doc2"]) ? limpiarCadena($_POST["doc2"]) : "";


			switch ($_GET["op"]){

        //:::::::::::::::::::::::... C R U D  S U B  C O N T R A T O ....::::::::::::::::

				case 'guardaryeditar':

          // Comprobante
          if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {

            $comprobante=$_POST["doc_old_1"]; $flat_ficha1 = false;

          } else {

            $ext1 = explode(".", $_FILES["doc1"]["name"]); $flat_ficha1 = true;	
            $comprobante = $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);
            move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/sub_contrato/comprobante_subcontrato/" . $comprobante);
          
          }


          if (empty($idsubcontrato)){
            //var_dump($idproyecto,$idproveedor);
            $rspta=$sub_contrato->insertar($idproyecto, $idproveedor,$ruc_proveedor, $tipo_comprobante, $numero_comprobante, $forma_de_pago, $fecha_subcontrato, $val_igv, $subtotal, $igv, $costo_parcial, $descripcion, $comprobante,$tipo_gravada);
            echo json_encode($rspta, true);
          }
          else {
            //validamos si existe comprobante para eliminarlo
            if ($flat_ficha1 == true) {

              $datos_ficha1 = $sub_contrato->ficha_tec($idsubcontrato);        
              $ficha1_ant = $datos_ficha1['data']->fetch_object()->comprobante;        
              if ($ficha1_ant != "") { unlink("../dist/docs/sub_contrato/comprobante_subcontrato/" . $ficha1_ant);  }
            }

            $rspta=$sub_contrato->editar($idsubcontrato, $idproyecto, $idproveedor,$tipo_comprobante, $numero_comprobante, $forma_de_pago, $fecha_subcontrato, $val_igv, $subtotal, $igv, $costo_parcial, $descripcion, $comprobante,$tipo_gravada);            
            echo json_encode($rspta, true);
          }
				
				break;

				case 'desactivar':
          $rspta=$sub_contrato->desactivar($_GET["id_tabla"]);
          echo json_encode($rspta, true);	
				break;

				case 'activar':
          $rspta=$sub_contrato->activar($idsubcontrato);
          echo json_encode($rspta, true);		
				break;

				case 'eliminar':
          $rspta=$sub_contrato->eliminar($_GET["id_tabla"]);
          echo json_encode($rspta, true);	
				break;

				case 'mostrar':
          $rspta=$sub_contrato->mostrar($idsubcontrato);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);	
				break;

				case 'verdatos':
          $rspta=$sub_contrato->verdatos($idsubcontrato);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);	
				break;

				case 'total':
          $rspta=$sub_contrato->total($_POST["idproyecto"], $_POST["fecha_1"], $_POST["fecha_2"], $_POST["id_proveedor"], $_POST["comprobante"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);	
				break;

				case 'tabla_principal':

          $rspta=$sub_contrato->tabla_principal($_GET["idproyecto"], $_GET["fecha_1"], $_GET["fecha_2"], $_GET["id_proveedor"], $_GET["comprobante"]);
          //Vamos a declarar un array
          $data= Array();
           
          $cont=1;
          $saldo=0; $estado=''; $c=''; $nombre=''; $icon=''; $info='';              

          if ($rspta['status'] == true) {

            foreach ($rspta['data'] as $key => $reg) {
              
              $comprobante = empty($reg['comprobante'])?'<div><center><a type="btn btn-danger" class=""><i class="fas fa-file-invoice-dollar fa-2x text-gray-50"></i></a></center></div>':'<div><center><a type="btn btn-danger" class=""  href="#" onclick="modal_comprobante(\''.$reg['comprobante'].'\',\''.decodeCadenaHtml($reg['tipo_comprobante']. (empty($reg['numero_comprobante'])?"":' - '.$reg['numero_comprobante'] )).'\')"><i class="fas fa-file-invoice-dollar fa-2x"></i></a></center></div>';
              
              $saldo = $reg['costo_parcial'] - $reg['total_deposito']; 

              if ($saldo == $reg['costo_parcial']) {

                $estado = '<span class="text-center badge badge-danger">Sin pagar</span>';
                $c      = "danger"; $nombre = "Pagar"; $icon   = "dollar-sign";

              } else {

                if ($saldo < $reg['costo_parcial'] && $saldo > "0") {

                  $estado = '<span class="text-center badge badge-warning">En proceso</span>';
                  $c = "warning"; $nombre = "Pagar"; $icon = "dollar-sign";

                } else {

                  if ($saldo <= "0" || $saldo == "0") {

                    $estado = '<span class="text-center badge badge-success">Pagado</span>';
                    $c = "success"; $nombre = "Ver"; $info = "success"; $icon = "eye";

                  } else {

                    $estado = '<span class="text-center badge badge-success">Error</span>';
                  }
                }
              }

              $data[]=array(
                "0"=>$cont++,
                "1"=>'<button class="btn btn-warning btn-sm" onclick="mostrar('.$reg['idsubcontrato'].')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>'.
                  (($reg['estado'])?' <button class="btn btn-danger  btn-sm" onclick="eliminar(' . $reg['idsubcontrato'] .',\''.encodeCadenaHtml($reg['tipo_comprobante']. (empty($reg['numero_comprobante'])?"":' - '.$reg['numero_comprobante'] )). '\')" data-toggle="tooltip" data-original-title="Papelera o Eliminar"><i class="fas fa-skull-crossbones"></i> </button>':
                  ' <button class="btn btn-primary btn-sm" onclick="activar('.$reg['idsubcontrato'].')" data-toggle="tooltip" data-original-title="Activar"><i class="fa fa-check"></i></button>').
                  ' <button class="btn btn-info btn-sm" onclick="ver_datos('.$reg['idsubcontrato'].')" data-toggle="tooltip" data-original-title="Ver detalle"><i class="far fa-eye"></i></button>',
                "2"=> $reg['fecha_subcontrato'], 
                "3"=> '<div class="w-150px recorte-text" data-toggle="tooltip" data-original-title="'. $reg['proveedor'] .'">'. $reg['proveedor'] .'</div>' ,
                "4"=>'<span ><b class="text-primary">'.$reg['tipo_comprobante'].'</b>'. (empty($reg['numero_comprobante'])?"":' - '.$reg['numero_comprobante'] ).'</span>',                
                "5"=>'<textarea cols="30" rows="1" class="textarea_datatable" readonly="">'.$reg['descripcion'].'</textarea>',                
                "6"=> $reg['costo_parcial'],
                "7"=>'<div class="text-nowrap formato-numero-conta"> 
                    <button class="btn btn-' . $c . ' btn-xs" onclick="listar_pagos(' .$reg['idsubcontrato']. ' , '.$reg['costo_parcial'].' , '.$reg['total_deposito'].')"><i class="fas fa-' . $icon . ' nav-icon"></i> ' . $nombre . '</button> ' .
                    ' <button style="font-size: 14px;" class="btn btn-' . $c . ' btn-xs">' . number_format($reg['total_deposito'], 2, '.', ',') . '</button> 
                  </div>',
                "8"=>number_format($saldo, 2, '.', ','),
                "9"=>$comprobante . $toltip,

                "10"=> $reg['tipo_documento'],
                "11"=> $reg['ruc'],
                "12"=> $reg['tipo_comprobante'],
                "13"=> $reg['numero_comprobante'],
                "14"=> $reg['forma_de_pago'],
                "15"=> number_format($reg['subtotal'], 2, '.', ','),
                "16"=> number_format($reg['igv'], 2, '.', ','),
                "17"=> $reg['val_igv'],
                "18"=> number_format($reg['total_deposito'], 2, '.', ','),
                "19"=> $reg['tipo_gravada'],
                "20"=> $reg['glosa'],
              );

            }

            $results = array(
              "sEcho"=>1, //Información para el datatables
              "iTotalRecords"=>count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
              "data"=>$data);
            echo json_encode($results, true);
          } else {
            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          }          

				break;

				//:::::::::::::::::::::::... C R U D  P A G O S....::::::::::::::::

				case 'datos_proveedor':

          $rspta = $sub_contrato->datos_proveedor($_POST['idsubcontrato']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);

				break;
				
				case 'guardaryeditar_pago':

          // imgen de perfil
          if (!file_exists($_FILES['doc2']['tmp_name']) || !is_uploaded_file($_FILES['doc2']['tmp_name'])) {
            $imagen1 = $_POST["doc_old_2"];
            $flat_img1 = false;
          } else {
            $ext1 = explode(".", $_FILES["doc2"]["name"]);
            $flat_img1 = true;      
            $imagen1 = $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);      
            move_uploaded_file($_FILES["doc2"]["tmp_name"], "../dist/docs/sub_contrato/comprobante_pago/" . $imagen1);
          }
      
          if (empty($idpago_subcontrato)) {
            $rspta = $sub_contrato->insertar_pago($idsubcontrato_pago, $beneficiario_pago, $forma_pago, $tipo_pago,  $cuenta_destino_pago, $banco_pago, $titular_cuenta_pago, $fecha_pago, $monto_pago, $numero_op_pago, $descripcion_pago, $imagen1 );
            echo json_encode($rspta, true);
          } else {
            // validamos si existe LA IMG para eliminarlo
            if ($flat_img1 == true) {
              $datos_f1 = $sub_contrato->obtenerImg($idpago_subcontrato);      
              $img1_ant = $datos_f1['data']->fetch_object()->comprobante;      
              if ($img1_ant != "") { unlink("../dist/docs/sub_contrato/comprobante_pago/" . $img1_ant);  }              
            }
      
            $rspta = $sub_contrato->editar_pago($idpago_subcontrato, $idsubcontrato_pago, $beneficiario_pago, $forma_pago, $tipo_pago, $cuenta_destino_pago, $banco_pago, $titular_cuenta_pago, $fecha_pago, $monto_pago, $numero_op_pago, $descripcion_pago, $imagen1 );      
            echo json_encode($rspta, true);
          }

				break;

				case 'listar_pagos_proveedor':

          $rspta=$sub_contrato->listar_pagos($_GET['idsubcontrato'],"Proveedor");
          //Vamos a declarar un array
          $data= Array();  $cont=1;

          if ($rspta['status'] == true) {
            while ($reg = $rspta['data']->fetch_object()) {
              
              $comprobante = empty($reg->comprobante)
              ? ( '<div><center><a type="btn btn-danger" class=""><i class="fas fa-file-invoice-dollar fa-2x text-gray-50"></i></a></center></div>')
              : ( '<div><center><a type="btn btn-danger" class=""  href="#" onclick="ver_modal_vaucher_pagos(\''  . $reg->comprobante . '\',\''.encodeCadenaHtml(date("d/m/Y", strtotime('2022-12-24')). ' - '.$reg->beneficiario). '\')"><i class="fas fa-file-invoice-dollar fa-2x"></i></a></center></div>');

              $data[] = [
                "0" => $cont++,
                "1" => $reg->estado ? '<button class="btn btn-warning btn-sm" onclick="mostrar_pagos(' . $reg->idpago_subcontrato . ')"><i class="fas fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-danger  btn-sm" onclick="eliminar_pagos(' . $reg->idpago_subcontrato .', \''. encodeCadenaHtml(date("d/m/Y", strtotime('2022-12-24')). ' - '.$reg->beneficiario) . '\')"><i class="fas fa-skull-crossbones"></i> </button>':
                  '<button class="btn btn-warning btn-sm" onclick="mostrar_pagos(' . $reg->idpago_subcontrato . ')"><i class="fa fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-primary btn-sm" onclick="activar_pagos(' . $reg->idpago_subcontrato . ')"><i class="fa fa-check"></i></button>', 
                "2" => $reg->forma_pago,
                "3" => '<div class="user-block">
                  <span class="username ml-0"><p class="text-primary m-b-02rem" >'. $reg->beneficiario .'</p></span>
                  <span class="description ml-0"><b>'. $reg->bancos .'</b>: '. $reg->cuenta_destino .' </span>
                  <span class="description ml-0"><b>Titular: </b>: '. $reg->titular_cuenta .' </span>            
                </div>',
                "4" => $reg->fecha_pago,
                "5" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.(empty($reg->descripcion) ? '- - -' : $reg->descripcion ).'</textarea>',
                "6" => $reg->numero_operacion,
                "7" => number_format($reg->monto, 2, '.', ','),
                "8" => $comprobante . $toltip,
                "9" => $reg->estado ? '<span class="text-center badge badge-success">Activado</span>' : '<span class="text-center badge badge-danger">Desactivado</span>',
              ];
            }
            $results = array(
              "sEcho"=>1, //Información para el datatables
              "iTotalRecords"=>count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
              "data"=>$data);
            echo json_encode($results, true);
          } else {
            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          }

				break;

        case 'listar_pagos_detraccion':

          $rspta=$sub_contrato->listar_pagos($_GET['idsubcontrato'], "Detraccion");
          //Vamos a declarar un array
          $data= Array();    $cont=1;

          if ($rspta['status'] == true) {
            while ($reg = $rspta['data']->fetch_object()) {
              
              $comprobante = empty($reg->comprobante)
              ? ( '<div><center><a type="btn btn-danger" class=""><i class="fas fa-file-invoice-dollar fa-2x text-gray-50"></i></a></center></div>')
              : ( '<div><center><a type="btn btn-danger" class=""  href="#" onclick="ver_modal_vaucher_pagos(' . "'" . $reg->comprobante . "'" . ')"><i class="fas fa-file-invoice-dollar fa-2x"></i></a></center></div>');

              $data[] = [
                "0" => $cont++,
                "1" => $reg->estado ? '<button class="btn btn-warning btn-sm" onclick="mostrar_pagos(' . $reg->idpago_subcontrato . ')"><i class="fas fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-danger  btn-sm" onclick="eliminar_pagos(' . $reg->idpago_subcontrato .', \''. encodeCadenaHtml($reg->beneficiario) . '\')"><i class="fas fa-skull-crossbones"></i> </button>':
                  '<button class="btn btn-warning btn-sm" onclick="mostrar_pagos(' . $reg->idpago_subcontrato . ')"><i class="fa fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-primary btn-sm" onclick="activar_pagos(' . $reg->idpago_subcontrato . ')"><i class="fa fa-check"></i></button>', 
                "2" => $reg->forma_pago,
                "3" => '<div class="user-block">
                  <span class="username ml-0"><p class="text-primary m-b-02rem" >'. $reg->beneficiario .'</p></span>
                  <span class="description ml-0"><b>'. $reg->bancos .'</b>: '. $reg->cuenta_destino .' </span>
                  <span class="description ml-0"><b>Titular: </b>: '. $reg->titular_cuenta .' </span>            
                </div>',
                "4" => $reg->fecha_pago,
                "5" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.(empty($reg->descripcion) ? '- - -' : $reg->descripcion ).'</textarea>',
                "6" => $reg->numero_operacion,
                "7" => number_format($reg->monto, 2, '.', ','),
                "8" => $comprobante . $toltip,
                "9" => $reg->estado ? '<span class="text-center badge badge-success">Activado</span>' : '<span class="text-center badge badge-danger">Desactivado</span>',
              ];
            }
            $results = array(
              "sEcho"=>1, //Información para el datatables
              "iTotalRecords"=>count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
              "data"=>$data);
            echo json_encode($results);
          } else {
            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          }

				break;

				case 'desactivar_pagos':

          $rspta=$sub_contrato->desactivar_pagos($_GET["id_tabla"]);
          echo json_encode($rspta, true);
	
				break;

				case 'activar_pagos':
          $rspta=$sub_contrato->activar_pagos($idpago_subcontrato);
          echo json_encode($rspta, true);	
				break;

				case 'eliminar_pagos':
          $rspta=$sub_contrato->eliminar_pagos($_GET["id_tabla"]);
          echo json_encode($rspta, true);	
				break;

				case 'mostrar_pagos':
          $rspta=$sub_contrato->mostrar_pagos($idpago_subcontrato);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);	
				break;

				case 'total_pagos_prov':
          $rspta=$sub_contrato->total_pagos($_POST['idsubcontrato'],"Proveedor");
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);		
				break;

        case 'total_pagos_detrac':
          $rspta=$sub_contrato->total_pagos($_POST['idsubcontrato'],"Detraccion");
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);		
				break;

			  //:::::::::::::::::::::::... C R U D  F A C T U R A S ....::::::::::::::::

				case 'salir':
					//Limpiamos las variables de sesión   
					session_unset();
					//Destruìmos la sesión
					session_destroy();
					//Redireccionamos al login
					header("Location: ../index.php");
				break;

        default: 
          $rspta = ['status'=>'error_code', 'message'=>'Te has confundido en escribir en el <b>swich.</b>', 'data'=>[]]; echo json_encode($rspta, true); 
        break;
			}

		 //Fin de las validaciones de acceso
		} else {
      $retorno = ['status'=>'nopermiso', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
      echo json_encode($retorno);
		}
	}	

	ob_end_flush();
?>