<?php
	ob_start();
	if (strlen(session_id()) < 1){
		session_start();//Validamos si existe o no la sesión
	}
	if (!isset($_SESSION["nombre"])) {

		header("Location: ../vistas/login.html");//Validamos el acceso solo a los materials logueados al sistema.

	} else {
		//Validamos el acceso solo al material logueado y autorizado.
		if ($_SESSION['subcontrato']==1){

			require_once "../modelos/Sub_contrato.php";

			$sub_contrato=new Sub_contrato();

			$idproyecto          = isset($_POST["idproyecto"])? limpiarCadena($_POST["idproyecto"]):"";	
			$idproveedor         = isset($_POST["idproveedor"])? limpiarCadena($_POST["idproveedor"]):"";	
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

            $comprobante = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

            move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/sub_contrato/comprobante_subcontrato/" . $comprobante);
          
          }


          if (empty($idsubcontrato)){
            //var_dump($idproyecto,$idproveedor);
            $rspta=$sub_contrato->insertar($idproyecto, $idproveedor, $tipo_comprobante, $numero_comprobante, $forma_de_pago, $fecha_subcontrato, $val_igv, $subtotal, $igv, $costo_parcial, $descripcion, $comprobante);
            echo $rspta ? "ok" : "No se pudieron registrar todos los datos";
          }
          else {
            //validamos si existe comprobante para eliminarlo
            if ($flat_ficha1 == true) {

              $datos_ficha1 = $sub_contrato->ficha_tec($idsubcontrato);
        
              $ficha1_ant = $datos_ficha1->fetch_object()->comprobante;
        
              if ($ficha1_ant != "") {
        
                unlink("../dist/docs/sub_contrato/comprobante_subcontrato/" . $ficha1_ant);
              }
            }

            $rspta=$sub_contrato->editar($idsubcontrato, $idproyecto, $idproveedor, $tipo_comprobante, $numero_comprobante, $forma_de_pago, $fecha_subcontrato, $val_igv, $subtotal, $igv, $costo_parcial, $descripcion, $comprobante);
            
            echo $rspta ? "ok" : "No se pudo actualizar";
          }
				
				break;

				case 'desactivar':

          $rspta=$sub_contrato->desactivar($idsubcontrato);
          echo $rspta ? " Desactivado" : "No se puede desactivar";
	
				break;

				case 'activar':

          $rspta=$sub_contrato->activar($idsubcontrato);
          echo $rspta ? "Activado" : "No se puede activar";
		
				break;

				case 'eliminar':

          $rspta=$sub_contrato->eliminar($idsubcontrato);
          echo $rspta ? " Eliminado" : "No se puede Eliminar";
	
				break;

				case 'mostrar':

          $rspta=$sub_contrato->mostrar($idsubcontrato);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);
	
				break;

				case 'verdatos':

          $rspta=$sub_contrato->verdatos($idsubcontrato);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);
	
				break;

				case 'total':

          $rspta=$sub_contrato->total($idproyecto);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);
	
				break;

				case 'listar':

          $idproyecto= $_GET["idproyecto"];
          $rspta=$sub_contrato->listar($idproyecto);
          //Vamos a declarar un array
          $data= Array();
          $comprobante = '';
          $cont=1;
          $saldo=0; $estado=''; $c=''; $nombre=''; $icon=''; $info=''; 

          foreach ($rspta as $key => $reg) {
            
            empty($reg['comprobante'])?$comprobante='<div><center><a type="btn btn-danger" class=""><i class="fas fa-file-invoice-dollar fa-2x text-gray-50"></i></a></center></div>':$comprobante='<div><center><a type="btn btn-danger" class=""  href="#" onclick="modal_comprobante('."'".$reg['comprobante']."'".')"><i class="fas fa-file-invoice-dollar fa-2x"></i></a></center></div>';
            
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
              "1"=>($reg['estado'])?'<button class="btn btn-warning btn-sm" onclick="mostrar('.$reg['idsubcontrato'].')"><i class="fas fa-pencil-alt"></i></button>'.
                ' <button class="btn btn-danger  btn-sm" onclick="eliminar(' . $reg['idsubcontrato'] . ')"><i class="fas fa-skull-crossbones"></i> </button>'.
                ' <button class="btn btn-info btn-sm" onclick="ver_datos('.$reg['idsubcontrato'].')"><i class="far fa-eye"></i></button>':
                '<button class="btn btn-warning btn-sm" onclick="mostrar('.$reg['idsubcontrato'].')"><i class="fa fa-pencil-alt"></i></button>'.
                ' <button class="btn btn-primary btn-sm" onclick="activar('.$reg['idsubcontrato'].')"><i class="fa fa-check"></i></button>'.
                ' <button class="btn btn-info btn-sm" onclick="ver_datos('.$reg['idsubcontrato'].')"><i class="far fa-eye"></i></button>',
              "2"=>$reg['forma_de_pago'], 
              "3"=>'<div class="user-block">
                  <span class="username" style="margin-left: 0px !important;"> <p class="text-primary" style="margin-bottom: 0.2rem !important";>'.$reg['tipo_comprobante'].'</p> </span>
                  <span class="description" style="margin-left: 0px !important;">N° '.(empty($reg['numero_comprobante'])?" - ":$reg['numero_comprobante']).'</span>         
                </div>',
              "4"=> date("d/m/Y", strtotime($reg['fecha_subcontrato'])), 
              "5"=>'<textarea cols="30" rows="1" class="text_area_clss" readonly="">'.$reg['descripcion'].'</textarea>',
              "6"=>'S/. '.number_format($reg['subtotal'], 2, '.', ','),
              "7"=>'S/. '.number_format($reg['igv'], 2, '.', ','),
              "8"=>'S/. '.number_format($reg['costo_parcial'], 2, '.', ','),
              "9"=>'<div class="text-center text-nowrap"> 
                  <button class="btn btn-' . $c . ' btn-xs" onclick="listar_pagos(' .$reg['idsubcontrato']. ' , '.$reg['costo_parcial'].' , '.$reg['total_deposito'].')"><i class="fas fa-' . $icon . ' nav-icon"></i> ' . $nombre . '</button> ' .
                  ' <button style="font-size: 14px;" class="btn btn-' . $c . ' btn-xs">' . number_format($reg['total_deposito'], 2, '.', ',') . '</button> 
                </div>',
              "10"=>number_format($saldo, 2, '.', ','),
              "11"=>$comprobante
            );

          }

          $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
            "data"=>$data);
          echo json_encode($results);

				break;

				case 'select2Proveedor': 

					$rspta=$sub_contrato->select2_proveedor();

					while ($reg = $rspta->fetch_object())	{

						echo '<option value=' . $reg->idproveedor . '>' . $reg->razon_social .' - '. $reg->ruc . '</option>';

					}

				break;

				//:::::::::::::::::::::::... C R U D  P A G O S....::::::::::::::::

				case 'datos_proveedor':

          $rspta = $sub_contrato->datos_proveedor($_POST['idsubcontrato']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);

				break;
				
				case 'guardaryeditar_pago':

          // imgen de perfil
          if (!file_exists($_FILES['doc2']['tmp_name']) || !is_uploaded_file($_FILES['doc2']['tmp_name'])) {
          $imagen1 = $_POST["doc_old_2"];
          $flat_img1 = false;
          } else {
          $ext1 = explode(".", $_FILES["doc2"]["name"]);
          $flat_img1 = true;
      
          $imagen1 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);
      
          move_uploaded_file($_FILES["doc2"]["tmp_name"], "../dist/docs/sub_contrato/comprobante_pago/" . $imagen1);
          }
      
          if (empty($idpago_subcontrato)) {
          $rspta = $sub_contrato->insertar_pago($idsubcontrato_pago, $beneficiario_pago, $forma_pago, $tipo_pago,
                  $cuenta_destino_pago, $banco_pago, $titular_cuenta_pago, $fecha_pago, $monto_pago, $numero_op_pago, $descripcion_pago, $imagen1 );

          echo $rspta ? "ok" : "No se pudieron registrar todos los datos";
          } else {
          // validamos si existe LA IMG para eliminarlo
          if ($flat_img1 == true) {

            $datos_f1 = $sub_contrato->obtenerImg($idpago_subcontrato);
      
            $img1_ant = $datos_f1->fetch_object()->comprobante;
      
            if ($img1_ant != "") {
            unlink("../dist/docs/sub_contrato/comprobante_pago/" . $img1_ant);
            }
            
          }
      
          $rspta = $sub_contrato->editar_pago($idpago_subcontrato, $idsubcontrato_pago, $beneficiario_pago, $forma_pago, $tipo_pago,
          $cuenta_destino_pago, $banco_pago, $titular_cuenta_pago, $fecha_pago, $monto_pago, $numero_op_pago, $descripcion_pago, $imagen1 );
      
          echo $rspta ? "ok" : "No se pudo actualizar";
          }

				break;

				case 'listar_pagos_proveedor':

          $tipo_pago="Proveedor";

          $rspta=$sub_contrato->listar_pagos($_GET['idsubcontrato'],$tipo_pago);
          //Vamos a declarar un array
          $data= Array();

          $cont=1;
          $comprobante="";

          while ($reg = $rspta->fetch_object()) {
            
            empty($reg->comprobante)
            ? ($comprobante = '<div><center><a type="btn btn-danger" class=""><i class="fas fa-file-invoice-dollar fa-2x text-gray-50"></i></a></center></div>')
            : ($comprobante = '<div><center><a type="btn btn-danger" class=""  href="#" onclick="ver_modal_vaucher_pagos(' . "'" . $reg->comprobante . "'" . ')"><i class="fas fa-file-invoice-dollar fa-2x"></i></a></center></div>');

            $data[] = [

            "0" => $cont++,
            "1" => $reg->estado ? '<button class="btn btn-warning btn-sm" onclick="mostrar_pagos(' . $reg->idpago_subcontrato . ')"><i class="fas fa-pencil-alt"></i></button>' .
                        ' <button class="btn btn-danger  btn-sm" onclick="eliminar_pagos(' . $reg->idpago_subcontrato . ')"><i class="fas fa-skull-crossbones"></i> </button>':
                        '<button class="btn btn-warning btn-sm" onclick="mostrar_pagos(' . $reg->idpago_subcontrato . ')"><i class="fa fa-pencil-alt"></i></button>' .
                        ' <button class="btn btn-primary btn-sm" onclick="activar_pagos(' . $reg->idpago_subcontrato . ')"><i class="fa fa-check"></i></button>', 
            "2" => $reg->forma_pago,
            "3" => '<div class="user-block">
              <span class="username ml-0"><p class="text-primary m-b-02rem" >'. $reg->beneficiario .'</p></span>
              <span class="description ml-0"><b>'. $reg->bancos .'</b>: '. $reg->cuenta_destino .' </span>
              <span class="description ml-0"><b>Titular: </b>: '. $reg->titular_cuenta .' </span>            
            </div>',
            "4" => date("d/m/Y", strtotime($reg->fecha_pago)),
            "5" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.(empty($reg->descripcion) ? '- - -' : $reg->descripcion ).'</textarea>',
            "6" => $reg->numero_operacion,
            "7" => number_format($reg->monto, 2, '.', ','),
            "8" => $comprobante,
            "9" => $reg->estado ? '<span class="text-center badge badge-success">Activado</span>' : '<span class="text-center badge badge-danger">Desactivado</span>',
            ];
          }
          $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
            "data"=>$data);
          echo json_encode($results);
          //Fin de las validaciones de acceso

				break;

        case 'listar_pagos_detraccion':

          $tipo_pago="Detraccion";

          $rspta=$sub_contrato->listar_pagos($_GET['idsubcontrato'],$tipo_pago);
          //Vamos a declarar un array
          $data= Array();

          $cont=1;
          $comprobante="";

          while ($reg = $rspta->fetch_object()) {
            
            empty($reg->comprobante)
            ? ($comprobante = '<div><center><a type="btn btn-danger" class=""><i class="fas fa-file-invoice-dollar fa-2x text-gray-50"></i></a></center></div>')
            : ($comprobante = '<div><center><a type="btn btn-danger" class=""  href="#" onclick="ver_modal_vaucher_pagos(' . "'" . $reg->comprobante . "'" . ')"><i class="fas fa-file-invoice-dollar fa-2x"></i></a></center></div>');

            $data[] = [

            "0" => $cont++,
            "1" => $reg->estado ? '<button class="btn btn-warning btn-sm" onclick="mostrar_pagos(' . $reg->idpago_subcontrato . ')"><i class="fas fa-pencil-alt"></i></button>' .
                        ' <button class="btn btn-danger  btn-sm" onclick="eliminar_pagos(' . $reg->idpago_subcontrato . ')"><i class="fas fa-skull-crossbones"></i> </button>':
                        '<button class="btn btn-warning btn-sm" onclick="mostrar_pagos(' . $reg->idpago_subcontrato . ')"><i class="fa fa-pencil-alt"></i></button>' .
                        ' <button class="btn btn-primary btn-sm" onclick="activar_pagos(' . $reg->idpago_subcontrato . ')"><i class="fa fa-check"></i></button>', 
            "2" => $reg->forma_pago,
            "3" => '<div class="user-block">
              <span class="username ml-0"><p class="text-primary m-b-02rem" >'. $reg->beneficiario .'</p></span>
              <span class="description ml-0"><b>'. $reg->bancos .'</b>: '. $reg->cuenta_destino .' </span>
              <span class="description ml-0"><b>Titular: </b>: '. $reg->titular_cuenta .' </span>            
            </div>',
            "4" => date("d/m/Y", strtotime($reg->fecha_pago)),
            "5" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.(empty($reg->descripcion) ? '- - -' : $reg->descripcion ).'</textarea>',
            "6" => $reg->numero_operacion,
            "7" => number_format($reg->monto, 2, '.', ','),
            "8" => $comprobante,
            "9" => $reg->estado ? '<span class="text-center badge badge-success">Activado</span>' : '<span class="text-center badge badge-danger">Desactivado</span>',
            ];
          }
          $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
            "data"=>$data);
          echo json_encode($results);
          //Fin de las validaciones de acceso

				break;

				case 'desactivar_pagos':

          $rspta=$sub_contrato->desactivar_pagos($idpago_subcontrato);
          echo $rspta ? " Desactivado" : "No se puede desactivar";
	
				break;

				case 'activar_pagos':

          $rspta=$sub_contrato->activar_pagos($idpago_subcontrato);
          echo $rspta ? "Activado" : "No se puede activar";
	
				break;

				case 'eliminar_pagos':

          $rspta=$sub_contrato->eliminar_pagos($idpago_subcontrato);
          echo $rspta ? " Eliminado" : "No se puede Eliminar";
	
				break;

				case 'mostrar_pagos':

          $rspta=$sub_contrato->mostrar_pagos($idpago_subcontrato);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);
	
				break;

				case 'total_pagos_prov':
          
          $tipo_pago="Proveedor";

          $rspta=$sub_contrato->total_pagos($_POST['idsubcontrato'],$tipo_pago);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);
		
				break;
        case 'total_pagos_detrac':

          $tipo_pago="Detraccion";

          $rspta=$sub_contrato->total_pagos($_POST['idsubcontrato'],$tipo_pago);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);
		
				break;

				case 'select2Banco':
					$rspta = $sub_contrato->select2_banco();
				
					while ($reg = $rspta->fetch_object()) {
					echo '<option value=' . $reg->id . '>' . $reg->nombre . (empty($reg->alias) ? "" : " - $reg->alias") . '</option>';
					}
				
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
			}

		 //Fin de las validaciones de acceso
		} else {

		require 'noacceso.php';

		}

	}	

	ob_end_flush();
?>