<?php

	ob_start();

	if (strlen(session_id()) < 1){
		session_start();//Validamos si existe o no la sesión
	}
  
  if (!isset($_SESSION["nombre"])) {

    header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.

  } else {

    //Validamos el acceso solo al usuario logueado y autorizado.
    if ($_SESSION['trabajador'] == 1) {

      require_once "../modelos/Break.php";

      $breaks=new Breaks();

      //============Comprobantes========================
      $idsemana_break      = isset($_POST["idsemana_break"])? limpiarCadena($_POST["idsemana_break"]):"";
      $idfactura_break     = isset($_POST["idfactura_break"])? limpiarCadena($_POST["idfactura_break"]):"";
      $forma_pago          = isset($_POST["forma_pago"])? limpiarCadena($_POST["forma_pago"]):"";
      $tipo_comprobante    = isset($_POST["tipo_comprobante"])? limpiarCadena($_POST["tipo_comprobante"]):"";

      $nro_comprobante     = isset($_POST["nro_comprobante"])? limpiarCadena($_POST["nro_comprobante"]):"";
      $monto               = isset($_POST["monto"])? limpiarCadena($_POST["monto"]):"";
      $fecha_emision       = isset($_POST["fecha_emision"])? limpiarCadena($_POST["fecha_emision"]):"";
      $descripcion         = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
      $subtotal            = isset($_POST["subtotal"])? limpiarCadena($_POST["subtotal"]):"";
      $igv                 = isset($_POST["igv"])? limpiarCadena($_POST["igv"]):"";
      
      $ruc = isset($_POST["ruc"]) ? limpiarCadena($_POST["ruc"]) : "";
      $razon_social = isset($_POST["razon_social"]) ? limpiarCadena($_POST["razon_social"]) : "";
      $direccion = isset($_POST["direccion"]) ? limpiarCadena($_POST["direccion"]) : "";

      $imagen2             = isset($_POST["doc1"])? limpiarCadena($_POST["doc1"]):"";

      switch ($_GET["op"]){

        case 'guardaryeditar':

          $rspta=$breaks->insertar_editar($_POST['array_break'],$_POST['fechas_semanas_btn'],$_POST['idproyecto']);
          
          echo $rspta ? "ok" : "No se pudieron registrar todos datos";
          // echo $rspta ;

        break;
        	///////////////////////BREAK///////////////////////
        case 'listar_semana_botones':

          $nube_idproyecto = $_POST["nube_idproyecto"];

          $rspta=$breaks->listarsemana_botones($nube_idproyecto);

          //Codificar el resultado utilizando json
          echo json_encode($rspta);	

        break;

        case 'ver_datos_semana':
          
          $f1 = $_POST["f1"];
          $f2 = $_POST["f2"];
          $nube_idproyect = $_POST["nube_idproyect"];

          $rspta=$breaks->ver_detalle_semana_dias($f1,$f2,$nube_idproyect);
          echo json_encode($rspta);		

        break;

        case 'listar_totales_semana':

          $rspta=$breaks->listar($_GET['nube_idproyecto']);
          //Vamos a declarar un array
          $data= Array();
          $cont = 1;
          while ($reg=$rspta->fetch_object()){ 

            $data[]=array(
              "0"=>$cont++,
              "1"=>'<div class="user-block">
                    <span style="font-weight: bold;" ><p class="text-primary"style="margin-bottom: 0.2rem !important"; > Semana. '.$reg->numero_semana.'</p></span>
                    <span style="font-weight: bold; font-size: 15px;">'.date("d/m/Y", strtotime($reg->fecha_inicial)).' - '.date("d/m/Y", strtotime($reg->fecha_final)).' </span>
                  </div>',
              "2"=>'<b>'.number_format($reg->total, 2, '.', ',').'</b>', 
              "3"=>'<div class="text-center"> <button class="btn btn-info btn-sm" onclick="listar_comprobantes('.$reg->idsemana_break.')"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button></div>',   
            );

          }
          $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
          echo json_encode($results);

        
        break;
                
      /////////////////////// F I N  B R E A K///////////////////////

        /* case 'listar_totales_semana':
          $nube_idproyecto = $_POST["idproyecto"];
          //$array_fi_ff = $_GET["array_fi_ff"];

          $rspta=$breaks->listar_totales_semana($nube_idproyecto,$_POST["array_fi_ff"]);
          //Vamos a declarar un array
          $data= Array();

          $imagen_error = "this.src='../dist/svg/user_default.svg'";
          $total=0;
          foreach ( json_decode($rspta, true) as $key => $value) {
            //$total = $value['total'];
            $data[]=array(
              "0"=>'<div class="user-block">
                <span class="username"><p class="text-primary"style="margin-bottom: 0.2rem !important"; > Semana. '. $value['num_semana'] .'</p></span>
                <span class="description">'. $value['fecha_in'] .': '.  $value['fecha_fi'] .' </span>
                </div>',
              "1"=>'<b>'.number_format($value['total'], 2, '.', ',').'</b>' 
              );
          }
          $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
            "data"=>$data);
          echo json_encode($data);
         // echo $rspta;
        break;*/

        case 'guardaryeditar_Comprobante':

          if (!isset($_SESSION["nombre"])) {

            header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
      
          } else {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['viatico']==1)
            {
                // imgen de perfil
              if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {
      
                  $imagen2=$_POST["doc_old_1"]; $flat_img1 = false;
      
                } else {
      
                  $ext1 = explode(".", $_FILES["doc1"]["name"]); $flat_img1 = true;						
      
                  $imagen2 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);
      
                  move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/break/comprobante/" . $imagen2);
                
              }
      
      
              if (empty($idfactura_break)){
                
                $rspta=$breaks->insertar_comprobante($idsemana_break,$forma_pago,$tipo_comprobante,$nro_comprobante,$monto,$fecha_emision,$descripcion,$subtotal,$igv,$imagen2,$ruc,$razon_social,$direccion);
                echo $rspta ? "ok" : "No se pudieron registrar todos los datos de Comprobante";
              }
              else {
                // validamos si existe LA IMG para eliminarlo
                if ($flat_img1 == true) {
      
                  $datos_f1 = $breaks->obtenerDoc($idfactura_break);
            
                  $img1_ant = $datos_f1->fetch_object()->comprobante;
            
                  if ($img1_ant != "") {
            
                    unlink("../dist/docs/break/comprobante/" . $img1_ant);
                  }
                }
                
                $rspta=$breaks->editar_comprobante($idfactura_break,$idsemana_break,$forma_pago,$tipo_comprobante,$nro_comprobante,$monto,$fecha_emision,$descripcion,$subtotal,$igv,$imagen2,$ruc,$razon_social,$direccion);
                
                echo $rspta ? "ok" : "Comprobante no se pudo actualizar";
              }
              //Fin de las validaciones de acceso
            } else {
      
              require 'noacceso.php';

            }
          }
        break;
        
        case 'listar_comprobantes':

          if (!isset($_SESSION["nombre"]))
          {
            header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
          }
          else
          {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['viatico']==1)
            {	
              $rspta=$breaks->listar_comprobantes($_GET['idsemana_break']);

              //Vamos a declarar un array
              $data= Array();
              $comprobante='';
              $subtotal=0;
              $igv=0;
              $monto=0;
              $cont=1;

              while ($reg=$rspta->fetch_object()){
                $subtotal=round($reg->subtotal, 2);
                $igv=round($reg->igv, 2);
                $monto=round($reg->monto, 2 );
                if (strlen($reg->descripcion) >= 20 ) { $descripcion = substr($reg->descripcion, 0, 20).'...';  } else { $descripcion = $reg->descripcion; }
                empty($reg->comprobante)?$comprobante='<div><center><a type="btn btn-danger" class=""><i class="fas fa-file-invoice-dollar fa-2x text-gray-50"></i></a></center></div>':$comprobante='<div><center><a type="btn btn-danger" class=""  href="#" onclick="ver_modal_comprobante('."'".$reg->comprobante."'".')"><i class="fas fa-file-invoice fa-2x"></i></a></center></div>';
                $tool = '"tooltip"';   $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>"; 
               
                $data[]=array(

                  "0"=>$cont++,
                  "1"=>($reg->estado)?'<button class="btn btn-warning btn-sm" onclick="mostrar_comprobante('.$reg->idfactura_break .')"><i class="fas fa-pencil-alt"></i></button>'.
                  ' <button class="btn btn-danger  btn-sm" onclick="eliminar_comprobante(' . $reg->idfactura_break . ')"><i class="fas fa-skull-crossbones"></i> </button>':
                  '<button class="btn btn-warning btn-sm" onclick="mostrar_comprobante('.$reg->idfactura_break .')"><i class="fa fa-pencil-alt"></i></button>'.
                  ' <button class="btn btn-primary btn-sm" onclick="activar_comprobante('.$reg->idfactura_break .')"><i class="fa fa-check"></i></button>',
                  
                  "2"=> empty($reg->forma_de_pago)?' - ':$reg->forma_de_pago,
                  "3"=>'<div class="user-block">
                      <span class="username" style="margin-left: 0px !important;"> <p class="text-primary" style="margin-bottom: 0.2rem !important";>'.$reg->tipo_comprobante.'</p> </span>
                      <span class="description" style="margin-left: 0px !important;">N° '.(empty($reg->nro_comprobante)?" - ":$reg->nro_comprobante).'</span>         
                    </div>',	
                  "4"=>date("d/m/Y", strtotime($reg->fecha_emision)),
                  "5"=>'S/. '.number_format($subtotal, 2, '.', ','), 
                  "6"=>'S/. '.number_format($igv, 2, '.', ','),
                  "7"=>'S/. '.number_format($monto, 2, '.', ','),
                  "8"=>'<textarea cols="30" rows="1" class="text_area_clss" readonly="">'.$reg->descripcion.'</textarea>',
                  "9"=>$comprobante,
                  "10"=>($reg->estado)?'<span class="text-center badge badge-success">Activado</span>'.$toltip:
                  '<span class="text-center badge badge-danger">Desactivado</span>'.$toltip
                
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

        case 'desactivar_comprobante':
          if (!isset($_SESSION["nombre"]))
          {
            header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
          }
          else
          {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['viatico']==1)
            {
              $rspta=$breaks->desactivar_comprobante($idfactura_break);
               echo $rspta ? "Comprobante Anulado" : "Comprobante no se puede Anular";
            //Fin de las validaciones de acceso
            }
            else
            {
              require 'noacceso.php';
            }
          }		
        break;
      
        case 'activar_comprobante':
          if (!isset($_SESSION["nombre"]))
          {
            header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
          }
          else
          {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['viatico']==1)
            {
              $rspta=$breaks->activar_comprobante($idfactura_break);
               echo $rspta ? "Comprobante Restablecido" : "Comprobante no se pudo Restablecido";
            //Fin de las validaciones de acceso
            }
            else
            {
              require 'noacceso.php';
            }
          }		
        break;

        case 'eliminar_comprobante':
          if (!isset($_SESSION["nombre"]))
          {
            header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
          }
          else
          {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['viatico']==1)
            {
              $rspta=$breaks->eliminar_comprobante($idfactura_break);
               echo $rspta ? "Comprobante eliminado" : "Comprobante no se puede Eliminar";
            //Fin de las validaciones de acceso
            }
            else
            {
              require 'noacceso.php';
            }
          }		
        break;

        case 'mostrar_comprobante':
          if (!isset($_SESSION["nombre"]))
          {
            header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
          }
          else
          {
            //Validamos el acceso solo al usuario logueado y autorizado.
            if ($_SESSION['viatico']==1)
            {

              $rspta=$breaks->mostrar_comprobante($idfactura_break);
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

        case 'total_monto':
          //falta
          $rspta=$breaks->total_monto_comp($idsemana_break);
           echo json_encode($rspta);
     
        break;

        
      }

    } else {

      require 'noacceso.php';
    }
  }

	ob_end_flush();

?>