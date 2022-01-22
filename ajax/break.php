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

            $rspta=$breaks->insertar_editar($_POST['array_break'],$_POST['idproyecto']);
            
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

        case 'listar':
          $nube_idproyecto = $_GET["nube_idproyecto"];

          $rspta=$breaks->listar($nube_idproyecto);
          //Vamos a declarar un array
          $data= Array();

          $imagen_error = "this.src='../dist/svg/user_default.svg'";
          
          while ($reg=$rspta->fetch_object()){
            $data[]=array(
              "0"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idtrabajador_por_proyecto.','.$reg->idtipo_trabjador.')"><i class="fas fa-pencil-alt"></i></button>'.
                ' <button class="btn btn-danger" onclick="desactivar('.$reg->idtrabajador_por_proyecto.')"><i class="far fa-trash-alt  "></i></button>'.
                ' <button class="btn btn-info" onclick="verdatos('.$reg->idtrabajador_por_proyecto.')"><i class="far fa-eye"></i></button>':
                '<button class="btn btn-warning" onclick="mostrar('.$reg->idtrabajador_por_proyecto.','.$reg->idtipo_trabjador.')"><i class="fa fa-pencil-alt"></i></button>'.
                ' <button class="btn btn-primary" onclick="activar('.$reg->idtrabajador_por_proyecto.')"><i class="fa fa-check"></i></button>'.
                ' <button class="btn btn-info" onclick="verdatos('.$reg->idtrabajador_por_proyecto.')"><i class="far fa-eye"></i></button>',
              "1"=>'<div class="user-block">
                <img class="img-circle" src="../dist/img/usuarios/'. $reg->imagen .'" alt="User Image" onerror="'.$imagen_error.'">
                <span class="username"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'. $reg->nombres .'</p></span>
                <span class="description">'. $reg->tipo_documento .': '. $reg->numero_documento .' </span>
                </div>',
              "2"=>'<b>'.$reg->banco .': </b>'. $reg->cuenta_bancaria,
              "3"=>$reg->sueldo_mensual,
              "4"=>$reg->nombre_tipo.' / '.$reg->cargo,
              "5"=>($reg->estado)?'<span class="text-center badge badge-success">Activado</span>':
              '<span class="text-center badge badge-danger">Desactivado</span>'
              );
          }
          $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
            "data"=>$data);
          echo json_encode($results);
        break;
        
      }

    } else {

      require 'noacceso.php';
    }
  }

	ob_end_flush();

?>