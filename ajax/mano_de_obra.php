<?php

	ob_start();

	if (strlen(session_id()) < 1){
		session_start();//Validamos si existe o no la sesión
	}
  
  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {

    //Validamos el acceso solo al usuario logueado y autorizado.
    if ($_SESSION['mano_obra'] == 1) {

      require_once "../modelos/Mano_de_obra.php";

      $mano_de_obra=new Mano_de_obra();
      
      date_default_timezone_set('America/Lima');  $date_now = date("d_m_Y__h_i_s_A");

      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

      $idproyecto     = isset($_POST["idproyecto"])? limpiarCadena($_POST["idproyecto"]):"";
      $idmano_de_obra = isset($_POST["idmano_de_obra"])? limpiarCadena($_POST["idmano_de_obra"]):"";
      $idproveedor    = isset($_POST["idproveedor"])? limpiarCadena($_POST["idproveedor"]):"";
      $ruc_proveedor  = isset($_POST["ruc_proveedor"])? limpiarCadena($_POST["ruc_proveedor"]):"";
      $fecha_inicial  = isset($_POST["fecha_inicial"])? limpiarCadena($_POST["fecha_inicial"]):"";
      $fecha_final    = isset($_POST["fecha_final"])? limpiarCadena($_POST["fecha_final"]):"";
      $fecha_deposito = isset($_POST["fecha_deposito"])? limpiarCadena($_POST["fecha_deposito"]):"";
      $monto          = isset($_POST["monto"])? limpiarCadena($_POST["monto"]):"";
      $descripcion    = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";

       

      switch ($_GET["op"]){
        // :::::::::::::::::::::::::: S E C C I O N   P E N S I O N  ::::::::::::::::::::::::::::::::::::::::::
        case 'guardar_y_editar_mdo':

          if (empty($idmano_de_obra)){            
            $rspta=$mano_de_obra->insertar_mdo($idproyecto, $idproveedor, $ruc_proveedor, $fecha_inicial, $fecha_final, $fecha_deposito, quitar_formato_miles( $monto), $descripcion);
            echo json_encode($rspta,true);
          } else {            
            $rspta=$mano_de_obra->editar_mdo($idmano_de_obra, $idproyecto, $idproveedor, $fecha_inicial, $fecha_final, $fecha_deposito, quitar_formato_miles($monto), $descripcion);            
            echo json_encode($rspta,true);
          }

        break;

        case 'desactivar':

          $rspta = $mano_de_obra->desactivar( $_GET["id_tabla"] );

          echo json_encode( $rspta, true) ;

        break;      

        case 'eliminar':

          $rspta = $mano_de_obra->eliminar( $_GET["id_tabla"] );

          echo json_encode( $rspta, true) ;

        break;

        case 'tabla_principal':

          $rspta=$mano_de_obra->tabla_principal($_GET['nube_idproyecto'], $_GET["fecha_1"], $_GET["fecha_2"], $_GET["id_proveedor"], $_GET["comprobante"]);
          //Vamos a declarar un array
          $data= Array();
          
          $cont=1;          

          if ($rspta['status'] == true) {
            foreach ($rspta['data'] as $key => $reg) {

              $data[]=array(
                "0"=>$cont++,
                "1"=>'<button class="btn btn-warning btn-sm" onclick="mostrar_editar_mdo('.$reg['idmano_de_obra'].')"><i class="fas fa-pencil-alt"></i></button>'.
                ' <button class="btn btn-danger btn-sm" onclick="eliminar(' . $reg['idmano_de_obra'] .', \''.encodeCadenaHtml($reg['razon_social']).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i></button>'.
                ' <button class="btn btn-info btn-sm" onclick="ver_detalle_mdo('.$reg['idmano_de_obra'].');"> <i class="fa-solid fa-eye"></i></button>',
                "2"=>$reg['fecha_deposito'],
                "3"=>'<div class="user-block">
                  <span ><p class="text-primary m-b-02rem font-weight-bold" > '.$reg['razon_social'].'</p></span>
                  <span class="text-gray font-size-13px"><b>'.$reg['tipo_documento'] .'</b>: '.$reg['ruc'].' </span>
                  </div>',
                "4"=>$reg['fecha_inicial'],  
                "5"=>$reg['fecha_final'],  
                "6"=>$reg['monto'],                 
                "7"=>'<textarea cols="30" rows="2" class="textarea_datatable" readonly="">'.$reg['descripcion'].'</textarea>',   
                
                "8"=>$reg['razon_social'], 
                "9"=>$reg['tipo_documento'],
                "10"=>$reg['ruc'],   
              );
            }
            $results = array(
              "sEcho"=>1, //Información para el datatables
              "iTotalRecords"=>count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
              "aaData"=>$data);
            echo json_encode($results,true);
            
          } else {
            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          }
        
        break;        
        
        case 'total_mdo':
          $rspta=$mano_de_obra->total_mdo($_POST['idproyecto'], $_POST["fecha_1"], $_POST["fecha_2"], $_POST["id_proveedor"], $_POST["comprobante"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta,true);
        break;

        case 'mostrar_mdo':
          $rspta=$mano_de_obra->mostrar_mdo($idmano_de_obra);
          //Codificar el resultado utilizando json
          echo json_encode($rspta,true);
        break;

        default: 
          $rspta = ['status'=>'error_code', 'message'=>'Te has confundido en escribir en el <b>swich.</b>', 'data'=>[]]; echo json_encode($rspta, true); 
        break;
        
      }

    } else {
      $retorno = ['status'=>'nopermiso', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
      echo json_encode($retorno);
    }
  }

	ob_end_flush();

?>