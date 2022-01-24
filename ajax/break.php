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
          /* $f1 = '2022-01-09';
          $f2 = '2022-01-15';
          $nube_idproyect = '2';*/

          $rspta=$breaks->ver_detalle_semana_dias($f1,$f2,$nube_idproyect);

          //Vamos a declarar un array
          // $data= Array();           
          // while ($reg=$rspta->fetch_object()){  $data[]=array( "idtrabajador"=>$reg->idtrabajador); }

          //Codificar el resultado utilizando json
          echo json_encode($rspta);		
        break;
        /////////////////////// FIN BREAK///////////////////////
        case 'listar_totales_semana':

          $rspta=$breaks->listar($_GET['nube_idproyecto']);
          //Vamos a declarar un array
          $data= Array();
     
          while ($reg=$rspta->fetch_object()){ 
            $data[]=array(
              "0"=>'<div class="user-block">
              <span style="font-weight: bold;" ><p class="text-primary"style="margin-bottom: 0.2rem !important"; > Semana. '.$reg->numero_semana.'</p></span>
              <span style="font-weight: bold; font-size: 15px;">'.date("d/m/Y", strtotime($reg->fecha_inicial)).' - '.date("d/m/Y", strtotime($reg->fecha_final)).' </span>
              </div>',
            "1"=>'<b>'.number_format($reg->total, 2, '.', ',').'</b>', 
            "2"=>'<div class="text-center"> <button class="btn btn-info btn-sm" onclick="listar_facturas('.$reg->idsemana_break.')"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button></div>',
              );
          }
          $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
          echo json_encode($results);

        
          break;
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
        
      }

    } else {

      require 'noacceso.php';
    }
  }

	ob_end_flush();

?>