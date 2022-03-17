<?php

	ob_start();

	if (strlen(session_id()) < 1){
		session_start();//Validamos si existe o no la sesión
	}
  
  if (!isset($_SESSION["nombre"])) {

    header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.

  } else {

    //Validamos el acceso solo al usuario logueado y autorizado.
    if ($_SESSION['pago_trabajador'] == 1) {

      require_once "../modelos/Papelera.php";
      require_once "../modelos/Fechas.php";

      $papelera = new Papelera();

      // DATA
      $nombre_tabla     = isset($_POST["nombre_tabla"])? limpiarCadena($_POST["nombre_tabla"]):"";
      $nombre_id_tabla 	= isset($_POST["nombre_id_tabla"])? limpiarCadena($_POST["nombre_id_tabla"]):""; 
      $id_tabla 		    = isset($_POST["id_tabla"])? limpiarCadena($_POST["id_tabla"]):""; 

      switch ($_GET["op"]){

        case 'listar_tbla_principal':

          $nube_idproyecto = $_GET["nube_idproyecto"];         

          $rspta=$papelera->tabla_principal($nube_idproyecto);
          //Vamos a declarar un array
          $data= Array();

          $cont=1;                          

          foreach ( $rspta as $key => $value) {            
            $info = '\''.$value['nombre_tabla'].'\', \''.$value['nombre_id_tabla'].'\', \''.$value['id_tabla'].'\'';
            $data[]=array(
              "0"=> $cont++,
              "1"=>'<button class="btn btn-success btn-sm" onclick="recuperar('.$info.')"><i class="fas fa-redo-alt"></i></button>'.
              ' <button class="btn btn-danger btn-sm" onclick="eliminar_permanente('.$info.')"><i class="far fa-trash-alt"></i></button>',
              "2"=>'<span class="text-bold">'. $value['nombre_royecto'] .'</span>',  
              "3"=>'<span class="text-bold">'. $value['modulo'] .'</span>',  
              "4"=>'<span class="text-primary text-bold">'. $value['nombre_archivo'] .'</span>',  
              "5"=>'<textarea cols="30" rows="1" class="textarea_datatable" readonly="">'.$value['descripcion'].'</textarea>',         
              "6"=> nombre_dia_semana( date("Y-m-d", strtotime($value['created_at'])) ) .', '. date("d/m/Y", strtotime($value['created_at'])) .' - '. date("g:i a", strtotime($value['created_at'])) ,
              "7"=> nombre_dia_semana( date("Y-m-d", strtotime($value['updated_at'])) ) .', '. date("d/m/Y", strtotime($value['updated_at'])) .' - '. date("g:i a", strtotime($value['updated_at']))
            );
          }
          $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
            "data"=>$data);
          echo json_encode($results);
        break;        

        case 'recuperar':

          $rspta=$papelera->recuperar($nombre_tabla, $nombre_id_tabla, $id_tabla);

          echo $rspta ? "ok" : "NO se puede anular";

        break;

        case 'eliminar_permanente':

          $rspta=$papelera->eliminar_permanente( $nombre_tabla, $nombre_id_tabla, $id_tabla );

          echo $rspta ? "ok" : "NO se puede ReActivar";

        break;         
        
      }

    } else {

      require 'noacceso.php';
    }
  }

  function quitar_guion($numero){ return str_replace("-", "", $numero); }

  function nombre_dia_mes_anio( $fecha_entrada ) {

    $fecha_nombre_completo = "";

    if (!empty($fecha_entrada) || $fecha_entrada != '0000-00-00') {
     
      $fecha_parse = new FechaEs($fecha_entrada);
      $dia_nombre = $fecha_parse->getDDDD().PHP_EOL;
      $num_dia = $fecha_parse->getdd().PHP_EOL;
      $mes = $fecha_parse->getMM().PHP_EOL;
      $anio = $fecha_parse->getYYYY().PHP_EOL;
      $fecha_nombre_completo = "$dia_nombre, $num_dia-$mes-$anio";
    }
    
    return $fecha_nombre_completo;
  }

  // NOMBRE DIA DE SEMANA
  function nombre_dia_semana($fecha) {

    $nombre_dia_semana = "";

    if (!empty($fecha) || $fecha != '0000-00-00') {

      $fechas = new FechaEs($fecha);

      $dia = $fechas->getDDDD().PHP_EOL;

      $nombre_dia_semana = $dia;
    }

    return $nombre_dia_semana;
  }

  // convierte de una fecha(dd-mm-aa): 23-12-2021 a una fecha(aa-mm-dd): 2021-12-23
  function format_a_m_d( $fecha ) {

    if (!empty($fecha)) {

      $fecha_expl = explode("-", $fecha);

      $fecha_convert =  $fecha_expl[0]."-".$fecha_expl[1]."-".$fecha_expl[2];

    }else{

      $fecha_convert = "";
    }   

    return $fecha_convert;
  }

  // convierte de una fecha(aa-mm-dd): 2021-12-23 a una fecha(dd-mm-aa): 23-12-2021
  function format_d_m_a( $fecha ) {

    if (!empty($fecha)) {

      $fecha_expl = explode("-", $fecha);

      $fecha_convert =  $fecha_expl[2]."-".$fecha_expl[1]."-".$fecha_expl[0];

    }else{

      $fecha_convert = "";
    }   

    return $fecha_convert;
  }

	ob_end_flush();

?>