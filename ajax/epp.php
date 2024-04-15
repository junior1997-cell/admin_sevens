<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {

    if ($_SESSION['otro_gasto'] == 1) {

      require_once "../modelos/Epp.php";

      $epp = new Epp();
            
      date_default_timezone_set('America/Lima');  $date_now = date("d_m_Y__h_i_s_A");   
      $scheme_host =  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/admin_sevens/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');
      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';
      
      $idepp = isset($_POST["idepp"]) ? $_POST["idepp"] : ""; 
      $idproyecto = isset($_POST["idproyecto"]) ? limpiarCadena($_POST["idproyecto"]) : "";
      $idtrabajador_por_proyecto = isset($_POST["idtrabajador_por_proyecto"]) ? limpiarCadena($_POST["idtrabajador_por_proyecto"]) : "";           
      $idProduc_almacen_resumen = isset($_POST["idProduc_almacen_resumen"]) ? $_POST["idProduc_almacen_resumen"] : "";      
      $fecha_g = isset($_POST["fecha_g"]) ? $_POST["fecha_g"] : "";      
      $id_insumo = isset($_POST["id_insumo"]) ? $_POST["id_insumo"] : "";      
      $cantidad = isset($_POST["cantidad"]) ? $_POST["cantidad"] : ""; 
      $marca = isset($_POST["marca"]) ? $_POST["marca"] : ""; 

      //$idepp,$idproyecto,$idtrabajador_por_proyecto,$fecha_g,$id_insumo,$cantidad
      //------E D I T A R -----
       // <!-- $idalmacen_x_proyecto_xp, $idtrabajador_xp, $id_producto_xp, $fecha_ingreso_xp, $marca_xp, $cantidad_xp  -->
      $idalmacen_x_proyecto_xp= isset($_POST["idalmacen_x_proyecto_xp"]) ? $_POST["idalmacen_x_proyecto_xp"] : ""; 
      $idtrabajador_xp= isset($_POST["idtrabajador_xp"]) ? $_POST["idtrabajador_xp"] : ""; 
      $idalmacen_resumen_xp =isset($_POST["idalmacen_resumen_xp"]) ? $_POST["idalmacen_resumen_xp"] : ""; 
      $id_producto_xp= isset($_POST["id_producto_xp"]) ? $_POST["id_producto_xp"] : ""; 
      $fecha_ingreso_xp= isset($_POST["fecha_ingreso_xp"]) ? $_POST["fecha_ingreso_xp"] : ""; 
      $marca_xp= isset($_POST["marca_xp"]) ? $_POST["marca_xp"] : ""; 
      $cantidad_xp =isset($_POST["cantidad_xp"]) ? $_POST["cantidad_xp"] : ""; 
      

      
      switch ($_GET["op"]) {
        case 'guardar_epp':

          if (empty($idepp)) {
            //var_dump($idproyecto,$idproveedor);
            $rspta = $epp->insertar($idproyecto,$idtrabajador_por_proyecto,$idProduc_almacen_resumen,$fecha_g,$id_insumo,$cantidad,$marca);
            
            echo json_encode($rspta,true);
      
          } else {

            $rspta ='ERROR';
            echo $rspta;
          }

        break;

        case 'editar_epp':

          if (isset($idalmacen_x_proyecto_xp)) {
            //var_dump($idproyecto,$idproveedor);
            $rspta = $epp->editar($idalmacen_x_proyecto_xp, $idtrabajador_xp,$idalmacen_resumen_xp, $id_producto_xp, $fecha_ingreso_xp, $marca_xp, $cantidad_xp);
            
            echo json_encode($rspta,true);
      
          } else {

            $rspta ='ERROR';
            echo $rspta;
          }

        break;

        case 'eliminar':
      
          $rspta = $epp->eliminar($_POST['id_tabla'], $_POST['idalmacen_resumen'], $_POST['cantidad']);
      
          echo json_encode($rspta,true);
      
        break;
      
        case 'mostrar':
      
          $rspta = $epp->mostrar($idepp);
          //Codificar el resultado utilizando json
          echo json_encode($rspta,true);
      
        break;
            
        case 'listar_trabajdor':
          $rspta = $epp->trabajador_proyecto($_GET["idproyecto"]);
          //Vamos a declarar un array
          $data = [];
          
          $cont = 1;
          if ($rspta['status'] == true) {
            while ($reg = $rspta['data']->fetch_object()) {

              $data[] = [
                "0" => $cont++,
                "1" => $reg->nombres,
                "2" => $reg->talla_ropa,
                "3" => $reg->talla_zapato,
                "4" => $reg->idtrabajador_por_proyecto,
                
              ];
            }
            $results = [
              "sEcho" => 1, //Información para el datatables
              "iTotalRecords" => count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
              "data" => $data,
            ];
            echo json_encode($results);
          } else {

            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          }
        break;

        case 'listar_epp_trabajdor':
          $rspta = $epp->listar_epp_trabajdor($_GET["id_tpp"], $_GET["proyecto"]);
          //Vamos a declarar un array
          $data = [];

          $cont = 1;
          if ($rspta['status'] == true) {
            while ($reg = $rspta['data']->fetch_object()) {
              // '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->idalmacen_detalle . ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' .
              $data[] = [
                "0" => $cont++,
                "1" => '<button class="btn btn-danger btn-sm" onclick="eliminar_detalle(' . $reg->idalmacen_detalle .',' . $reg->idalmacen_resumen .', \''.encodeCadenaHtml($reg->cantidad).'\', \''.encodeCadenaHtml($reg->nombre).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i></button>',  
                "2" => $reg->nombre,
                "3" => $reg->marca,
                "4" => $reg->abreviacion,
                "5" => $reg->cantidad,
                "6" => $reg->fecha,
                
              ];
            }
            $results = [
              "sEcho" => 1, //Información para el datatables
              "iTotalRecords" => count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
              "data" => $data,
            ];
            echo json_encode($results);
          } else {

            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          }
        break;
      
        case 'select_2_insumos_pp':

          $rspta = $epp->select_2_insumos_pp($_GET['idproyecto']); $cont = 1; $data = "";

          if ($rspta['status'] == true) {

            foreach ($rspta['data'] as $key => $value) {  

                $data .= '<option value=' .$value['idproducto']. ' data-nombre=" \''.encodeCadenaHtml($value['nombre_producto']).'\'" data-idProduc_almacen_resu="'.$value['idalmacen_resumen'].'" data-modelo="'.$value['modelo'].'" data-abreviacion="'.$value['abreviacion'].'"   data-total_stok="'.$value['total_stok'].'"  >'.( !empty($value['nombre_producto']) ? $value['nombre_producto']: '') .'</option>';
                //data-marca="'.$value['marca'] .'"
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

        case 'marcas_x_insumo':
      
          $rspta = $epp->marcas_x_insumo($_POST['id_insumo'], $_POST['idproyecto']);
          //Codificar el resultado utilizando json
          echo json_encode($rspta,true);
      
        break;

        //resumen
        case'tabla_resumen_epp':
          $rspta = $epp->tabla_resumen_epp($_GET["idproyecto"]);
          //Vamos a declarar un array
          $data = [];
          
          $cont = 1;
          if ($rspta['status'] == true) {
            foreach ($rspta['data'] as $key => $value) {    
              // ad.idalmacen_resumen, ad.idproyecto_destino,SUM(ad.cantidad) as cantidad_repartida,p.nombre, p.idproducto, um.abreviacion, ar.total_stok as Saldo, 
              // (SUM(ad.cantidad) + ar.total_stok ) as total_stok
              $data[] = [
                "0" => $cont++,
                "1" =>$value['idproducto'],
                "2" =>$value['nombre'].' - '.$value['marca'],
                "3" =>$value['abreviacion'],
                "4" =>$value['total_stok'],
                "5" =>$value['cantidad_repartida'],                
                "6" =>$value['Saldo'],
                "7" => '<button class="btn btn-info btn-sm mb-2" onclick="tabla_detalle_epp(' . $value['idalmacen_resumen'] . ',' . $value['idproyecto_destino'] . ', \'' .  htmlspecialchars($value['nombre'], ENT_QUOTES) . '\', \'' .$value['marca']. '\')" data-toggle="tooltip" data-original-title="Ver compras" title="Ver compras"><i class="far fa-eye"></i></button>'. $toltip,
                
                
              ];
            }
            $results = [
              "sEcho" => 1, //Información para el datatables
              "iTotalRecords" => count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
              "data" => $data,
            ];
            echo json_encode($results);
          } else {

            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          }

        break;
        //detalle por epp reparidos
        case'tbla_detalle_epp':
          $rspta = $epp->tbl_detalle_epp($_GET["idproducto"],$_GET["idproyecto"],$_GET["marca"]);
          //Vamos a declarar un array
          $data = [];
          
          $cont = 1;
          if ($rspta['status'] == true) {
            foreach ($rspta['data'] as $key => $value) {    
              // ad.idalmacen_detalle, ad.idalmacen_resumen, ad.idproyecto_destino, t.nombres, ad.cantidad, ad.fecha
              $data[] = [
                "0" => $cont++,
                "1" =>$value['nombres'],
                "2" =>$value['cantidad'],
                "3" =>$value['fecha'],
                //"5" => '<button class="btn btn-info btn-sm mb-2" onclick="tbl_detalle_epp(' . $value['idproducto'] . ', \'' .  htmlspecialchars($value['nombre'], ENT_QUOTES) . '\', \'' .$value['marca']. '\')" data-toggle="tooltip" data-original-title="Ver compras" title="Ver compras"><i class="far fa-eye"></i></button>'. $toltip,
                //"6" =>$value['cantidad_q_queda'],
                
              ];
            }
            $results = [
              "sEcho" => 1, //Información para el datatables
              "iTotalRecords" => count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
              "data" => $data,
            ];
            echo json_encode($results);
          } else {

            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
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
