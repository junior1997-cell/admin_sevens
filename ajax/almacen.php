<?php
  ob_start();

  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {
    //Validamos el acceso solo al usuario logueado y autorizado.
    if ($_SESSION['compra_insumos'] == 1) {

      require_once "../modelos/Almacen.php";

      $almacen = new Almacen($_SESSION['idusuario']);

      $imagen_error = "this.src='../dist/svg/user_default.svg'";
      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

      $idalmacen_x_proyecto     = isset($_POST["idalmacen_x_proyecto"])? limpiarCadena($_POST["idalmacen_x_proyecto"]):"";      
      $fecha_ingreso	= isset($_POST["fecha_ingreso"])? limpiarCadena($_POST["fecha_ingreso"]):"";
      $dia_ingreso	  = isset($_POST["dia_ingreso"])? limpiarCadena($_POST["dia_ingreso"]):"";

      switch ($_GET["op"]) {  

        case 'guardar_y_editar_almacen':

          if (empty($idalmacen_x_proyecto)) {
            $rspta = $almacen->insertar_almacen($fecha_ingreso, $dia_ingreso, $_POST["idproducto"], $_POST["cantidad"] );
            echo json_encode($rspta, true);
          } else {
            $rspta = $almacen->editar_almacen($idalmacen_x_proyecto, $fecha_ingreso, $dia_ingreso, $_POST["idproducto"], $_POST["cantidad"]);
            echo json_encode($rspta, true);
          }
          
        break; 

        case 'tabla_almacen':
          $rspta = $almacen->tbla_principal($_POST["id_proyecto"], $_POST["fip"], $_POST["ffp"], $_POST["fpo"] );
          // $rspta = $almacen->tbla_principal(6, '2023-04-18', '2023-04-22', 'semanal' );
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;   
        
        case 'tbla-ver-almacen':          

          $rspta=$almacen->tbla_ver_almacen($_GET["fecha"], $_GET["id_producto"]);
          
          //Vamos a declarar un array
          $data= Array(); $cont=1;

          if ($rspta['status'] == true) {

            foreach ($rspta['data'] as $key => $val) {             
              
          
              $data[]=array(
                "0"=>$cont++,
                "1"=>'<button class="btn btn-warning btn-sm" onclick="mostrar('.$val['idalmacen_x_proyecto'].')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>'.
                  ' <button class="btn btn-danger btn-sm" onclick="eliminar('.$val['idalmacen_x_proyecto'].', \''.encodeCadenaHtml($val['producto']).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i></button>',
                "2"=>'<div > <span class="username"><p class="text-primary m-b-02rem" >'. $val['producto'] .'</p></span> </div>',
                "3"=> $val['cantidad'],
                "4"=> $val['marca'],              
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

        case 'select2Productos': 
    
          $rspta = $almacen->select2_productos($_GET["idproyecto"]); $cont = 1; $data = "";
          
          if ($rspta['status'] == true) {  
            foreach ($rspta['data'] as $key => $value) {   
              $data .= '<option value="' . $value['idproducto'] . '" >' . $value['nombre_producto'] .'</option>';
            }  
            $retorno = array(
              'status' => true, 
              'message' => 'Salió todo ok', 
              'data' => $data, 
            );    
            echo json_encode($retorno, true);  
          } else {  
            echo json_encode($rspta, true); 
          }
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
