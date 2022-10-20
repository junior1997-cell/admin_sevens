<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  switch ($_GET["op"]) {

    case 'verificar':

      require_once "../modelos/Usuario.php";
      $usuario = new Usuario(); 

      $logina = $_POST['logina'];
      $clavea = $_POST['clavea'];

      //Hash SHA256 en la contraseña
      $clavehash = hash("SHA256", $clavea);

      $rspta = $usuario->verificar($logina, $clavehash);   //$fetch = $rspta->fetch_object();

      if ( $rspta['status'] ) {
        if ( !empty($rspta['data']) ) {
          //Declaramos las variables de sesión
          $_SESSION['idusuario'] = $rspta['data']['idusuario'];
          $_SESSION['nombre'] = $rspta['data']['nombres'];
          $_SESSION['imagen'] = $rspta['data']['imagen_perfil'];
          $_SESSION['login'] = $rspta['data']['login'];
          $_SESSION['cargo'] = $rspta['data']['cargo'];
          $_SESSION['tipo_documento'] = $rspta['data']['tipo_documento'];
          $_SESSION['num_documento'] = $rspta['data']['numero_documento'];
          $_SESSION['telefono'] = $rspta['data']['telefono'];
          $_SESSION['email'] = $rspta['data']['email'];

          //Obtenemos los permisos del usuario
          $marcados = $usuario->listarmarcados($rspta['data']['idusuario']);
          
          //Declaramos el array para almacenar todos los permisos marcados
          $valores = [];

          if ($rspta['status']) {
            //Almacenamos los permisos marcados en el array
            foreach ($marcados['data'] as $key => $value) {
              array_push($valores, $value['idpermiso']);
            }
            echo json_encode($rspta);
          }else{
            echo json_encode($marcados);
          }       

          //Determinamos los accesos del usuario
          in_array(1, $valores) ? ($_SESSION['escritorio'] = 1)         : ($_SESSION['escritorio'] = 0);
          in_array(2, $valores) ? ($_SESSION['acceso'] = 1)             : ($_SESSION['acceso'] = 0);
          in_array(3, $valores) ? ($_SESSION['recurso'] = 1)            : ($_SESSION['recurso'] = 0);          
          in_array(4, $valores) ? ($_SESSION['compra_activo_fijo'] = 1) : ($_SESSION['compra_activo_fijo'] = 0);
          in_array(5, $valores) ? ($_SESSION['resumen_activo_fijo_general'] = 1) : ($_SESSION['resumen_activo_fijo_general'] = 0);
          in_array(6, $valores) ? ($_SESSION['otra_factura'] = 1)       : ($_SESSION['otra_factura'] = 0);
          in_array(7, $valores) ? ($_SESSION['resumen_factura'] = 1)    : ($_SESSION['resumen_factura'] = 0);
          in_array(8, $valores) ? ($_SESSION['resumen_recibo_por_honorario'] = 1) : ($_SESSION['resumen_recibo_por_honorario'] = 0);
          in_array(9, $valores) ? ($_SESSION['papelera'] = 1)           : ($_SESSION['papelera'] = 0);

          // LOGISTICA Y ADQUISICIONES
          in_array(10, $valores) ? ($_SESSION['trabajador'] = 1)        : ($_SESSION['trabajador'] = 0);
          in_array(11, $valores) ? ($_SESSION['compra_insumos'] = 1)    : ($_SESSION['compra_insumos'] = 0);
          in_array(12, $valores) ? ($_SESSION['servicio_maquina'] = 1)  : ($_SESSION['servicio_maquina'] = 0);
          in_array(13, $valores) ? ($_SESSION['servicio_equipo'] = 1)   : ($_SESSION['servicio_equipo'] = 0);
          in_array(14, $valores) ? ($_SESSION['subcontrato'] = 1)       : ($_SESSION['subcontrato'] = 0);
          in_array(15, $valores) ? ($_SESSION['mano_obra'] = 1): ($_SESSION['mano_obra'] = 0);          

          in_array(16, $valores) ? ($_SESSION['planilla_seguro'] = 1)   : ($_SESSION['planilla_seguro'] = 0);
          in_array(17, $valores) ? ($_SESSION['otro_gasto'] = 1)        : ($_SESSION['otro_gasto'] = 0);          
          in_array(18, $valores) ? ($_SESSION['viatico'] = 1)           : ($_SESSION['viatico'] = 0);
          in_array(19, $valores) ? ($_SESSION['resumen_general'] = 1)   : ($_SESSION['resumen_general'] = 0);

          // TECNICO
          in_array(20, $valores) ? ($_SESSION['valorizacion'] = 1)      : ($_SESSION['valorizacion'] = 0);
          in_array(21, $valores) ? ($_SESSION['grafico_valorizacion'] = 1) : ($_SESSION['grafico_valorizacion'] = 0);
          in_array(22, $valores) ? ($_SESSION['asistencia_obrero'] = 1) : ($_SESSION['asistencia_obrero'] = 0);
          in_array(23, $valores) ? ($_SESSION['calendario'] = 1)        : ($_SESSION['calendario'] = 0);
          in_array(24, $valores) ? ($_SESSION['plano_otro'] = 1)        : ($_SESSION['plano_otro'] = 0);  

          in_array(25, $valores) ? ($_SESSION['clasificacion_grupo'] = 1) : ($_SESSION['clasificacion_grupo'] = 0);
          in_array(26, $valores) ? ($_SESSION['valorizacion_concreto'] = 1) : ($_SESSION['valorizacion_concreto'] = 0);
          in_array(27, $valores) ? ($_SESSION['valorizacion_fierro'] = 1)            : ($_SESSION['valorizacion_fierro'] = 0);
          
          // CONTABLE Y FINANCIERO
          in_array(28, $valores) ? ($_SESSION['resumen_gasto'] = 1)     : ($_SESSION['resumen_gasto'] = 0);         
          in_array(29, $valores) ? ($_SESSION['pago_trabajador'] = 1)   : ($_SESSION['pago_trabajador'] = 0);
          in_array(30, $valores) ? ($_SESSION['prestamo'] = 1)          : ($_SESSION['prestamo'] = 0);
          in_array(31, $valores) ? ($_SESSION['estado_financiero'] = 1) : ($_SESSION['estado_financiero'] = 0);        
          in_array(32, $valores) ? ($_SESSION['otro_ingreso'] = 1)      : ($_SESSION['otro_ingreso'] = 0);
          in_array(33, $valores) ? ($_SESSION['pago_valorizacion'] = 1) : ($_SESSION['pago_valorizacion'] = 0); //new

        } else {
          echo json_encode($rspta, true);
        }
      }else{
        
        echo json_encode($rspta, true);
      }
      
    break;
    
    case 'salir':
      //Limpiamos las variables de sesión
      session_unset();
      //Destruìmos la sesión
      session_destroy();
      //Redireccionamos al login
      header("Location: index.php?file=".(isset($_GET["file"]) ? $_GET["file"] : ""));
    break;

    // default: 
    //   $rspta = ['status'=>'error_code', 'message'=>'Te has confundido en escribir en el <b>swich.</b>', 'data'=>[]]; echo json_encode($rspta, true); 
    // break;
    
  }
 
  require_once "../modelos/Usuario.php";
  require_once "../modelos/Permiso.php";
  require_once "../modelos/AllTrabajador.php";      

  $usuario = new Usuario();  
  $permisos = new Permiso();
  $alltrabajador = new AllTrabajador();

  // ::::::::::::::::::::::::::::::::: D A T O S   U S U A R I O S :::::::::::::::::::::::::::::
  $idusuario = isset($_POST["idusuario"]) ? limpiarCadena($_POST["idusuario"]) : "";
  $trabajador = isset($_POST["trabajador"]) ? limpiarCadena($_POST["trabajador"]) : "";
  $trabajador_old = isset($_POST["trabajador_old"]) ? limpiarCadena($_POST["trabajador_old"]) : "";
  $cargo = isset($_POST["cargo"]) ? limpiarCadena($_POST["cargo"]) : "";
  $login = isset($_POST["login"]) ? limpiarCadena($_POST["login"]) : "";
  $clave = isset($_POST["password"]) ? limpiarCadena($_POST["password"]) : "";
  $clave_old = isset($_POST["password-old"]) ? limpiarCadena($_POST["password-old"]) : "";
  $permiso = isset($_POST['permiso']) ? $_POST['permiso'] : "";

  // ::::::::::::::::::::::::::::::::: D A T O S   T R A B A J A D O R :::::::::::::::::::::::::::::
  $idtrabajador_trab	  = isset($_POST["idtrabajador_trab"])? limpiarCadena($_POST["idtrabajador_trab"]):"";
  $nombre_trab		      = isset($_POST["nombre_trab"])? limpiarCadena($_POST["nombre_trab"]):"";
  $tipo_documento_trab 	= isset($_POST["tipo_documento_trab"])? limpiarCadena($_POST["tipo_documento_trab"]):"";
  $num_documento_trab  	= isset($_POST["num_documento_trab"])? limpiarCadena($_POST["num_documento_trab"]):"";
  $direccion_trab		    = isset($_POST["direccion_trab"])? limpiarCadena($_POST["direccion_trab"]):"";
  $telefono_trab		    = isset($_POST["telefono_trab"])? limpiarCadena($_POST["telefono_trab"]):"";
  $nacimiento_trab		  = isset($_POST["nacimiento_trab"])? limpiarCadena($_POST["nacimiento_trab"]):"";
  $edad_trab		        = isset($_POST["edad_trab"])? limpiarCadena($_POST["edad_trab"]):"";      
  $email_trab			      = isset($_POST["email_trab"])? limpiarCadena($_POST["email_trab"]):"";
  $banco_trab			      = isset($_POST["banco_trab"])? limpiarCadena($_POST["banco_trab"]):"";
  $c_bancaria_trab		  = isset($_POST["c_bancaria_trab"])? limpiarCadena($_POST["c_bancaria_trab"]):"";
  $c_bancaria_format    = isset($_POST["c_bancaria_trab"])? limpiarCadena($_POST["c_bancaria_trab"]):"";     
  $cci_trab	          	= isset($_POST["cci_trab"])? limpiarCadena($_POST["cci_trab"]):"";
  $cci_format      	    = isset($_POST["cci_trab"])? limpiarCadena($_POST["cci_trab"]):"";
  $titular_cuenta_trab  = isset($_POST["titular_cuenta_trab"])? limpiarCadena($_POST["titular_cuenta_trab"]):""; 
  $tipo_trab	          = isset($_POST["tipo_trab"])? limpiarCadena($_POST["tipo_trab"]):"";
  $ocupacion_trab	      = isset($_POST["ocupacion_trab"])? limpiarCadena($_POST["ocupacion_trab"]):"";
  $ruc_trab	          	= isset($_POST["ruc_trab"])? limpiarCadena($_POST["ruc_trab"]):"";

  $imagen1			    = isset($_POST["foto1"])? limpiarCadena($_POST["foto1"]):"";
  $imagen2			    = isset($_POST["foto2"])? limpiarCadena($_POST["foto2"]):"";
  $imagen3			    = isset($_POST["foto3"])? limpiarCadena($_POST["foto3"]):"";

  $cv_documentado			    = isset($_POST["doc4"])? limpiarCadena($_POST["doc4"]):"";
  $cv_nodocumentado			  = isset($_POST["doc5"])? limpiarCadena($_POST["doc5"]):"";

  switch ($_GET["op"]) {

    case 'guardar_y_editar_usuario':

      $clavehash = "";

      if (!empty($clave)) {
        //Hash SHA256 en la contraseña
        $clavehash = hash("SHA256", $clave);
      } else {
        if (!empty($clave_old)) {
          // enviamos la contraseña antigua
          $clavehash = $clave_old;
        } else {
          //Hash SHA256 en la contraseña
          $clavehash = hash("SHA256", "123456");
        }
      }

      if (empty($idusuario)) {

        $rspta = $usuario->insertar($trabajador, $cargo, $login, $clavehash, $permiso);

        echo json_encode($rspta, true);

      } else {

        $rspta = $usuario->editar($idusuario, $trabajador_old, $trabajador, $cargo, $login, $clavehash, $permiso);

        echo json_encode($rspta, true);
      }
    break;

    case 'desactivar':

      $rspta = $usuario->desactivar($_GET["id_tabla"]);

      echo json_encode($rspta, true);

    break;

    case 'activar':

      $rspta = $usuario->activar($_GET["id_tabla"]);

      echo json_encode($rspta, true);

    break;

    case 'eliminar':

      $rspta = $usuario->eliminar($_GET["id_tabla"]);

      echo json_encode($rspta, true);

    break;

    case 'mostrar':

      $rspta = $usuario->mostrar($idusuario);
      //Codificar el resultado utilizando json
      echo json_encode($rspta, true);

    break;

    case 'tbla_principal':

      $rspta = $usuario->listar();
          
      //Vamos a declarar un array
      $data = [];  
      $imagen_error = "this.src='../dist/svg/user_default.svg'"; $cont=1;
      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

      if ($rspta['status']) {
        foreach ($rspta['data'] as $key => $value) {
          $data[] = [
            "0"=>$cont++,
            "1" => $value['estado'] ? '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $value['idusuario'] . ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' .
                ($value['cargo']=='Administrador' ? ' <button class="btn btn-danger btn-sm disabled" data-toggle="tooltip" data-original-title="El administrador no se puede eliminar."><i class="fas fa-skull-crossbones"></i> </button>' : ' <button class="btn btn-danger  btn-sm" onclick="eliminar(' . $value['idusuario'] .', \''.encodeCadenaHtml($value['nombres']).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i> </button>' ) :
                '<button class="btn btn-warning  btn-sm" onclick="mostrar(' . $value['idusuario'] . ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' . 
                ' <button class="btn btn-primary  btn-sm" onclick="activar(' . $value['idusuario'] . ')" data-toggle="tooltip" data-original-title="Recuperar"><i class="fa fa-check"></i></button>',
            "2" => '<div class="user-block">'. 
              '<img class="img-circle" src="../dist/docs/all_trabajador/perfil/' . $value['imagen_perfil'] . '" alt="User Image" onerror="' . $imagen_error . '">'.
              '<span class="username"><p class="text-primary m-b-02rem" >' . $value['nombres'] . '</p></span>'. 
              '<span class="description"> - ' . $value['tipo_documento'] .  ': ' . $value['numero_documento'] . ' </span>'.
            '</div>',
            "3" => $value['telefono'],
            "4" => $value['login'],
            "5" => $value['cargo'],
            "6" => ($value['estado'] ? '<span class="text-center badge badge-success">Activado</span>' : '<span class="text-center badge badge-danger">Desactivado</span>').$toltip,
          ];
        }
        $results = [
          "sEcho" => 1, //Información para el datatables
          "iTotalRecords" => count($data), //enviamos el total registros al datatable
          "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
          "data" => $data,
        ];
        echo json_encode($results, true);
      } else {
        echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
      }

    break;

    case 'permisos':
      //Obtenemos todos los permisos de la tabla permisos      
      $rspta = $permisos->listar();

      if ( $rspta['status'] ) {

        //Obtener los permisos asignados al usuario
        $id = $_GET['id'];
        $marcados = $usuario->listarmarcados($id);
        //Declaramos el array para almacenar todos los permisos marcados
        $valores = [];

        if ($marcados['status']) {

          //Almacenar los permisos asignados al usuario en el array
          foreach ($marcados['data'] as $key => $value) {
            array_push($valores, $value['idpermiso']);
          }

          $data = ""; $num = 8;  $stado_close = false;
          //Mostramos la lista de permisos en la vista y si están o no marcados <label for=""></label>
          foreach ($rspta['data'] as $key => $value) {

            $div_open = ''; $div_close = '';

            if ( ($key + 1) == 1 ) {                  
              $div_open = '<ol class="list-unstyled row"><div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">'. 
              '<li class="text-primary"><input class="h-1rem w-1rem" type="checkbox" id="marcar_todo" onclick="marcar_todos_permiso();"> ' .
                '<label for="marcar_todo" class="marcar_todo">Marcar Todo</label>'.
              '</li>';                 
            } else {
              if ( ($key + 1) == $num ) { 
                $div_close = '</div>';
                $num += 9;
                $stado_close = true;
              } else {
                if ($stado_close) {
                  $div_open = '<div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">';
                  $stado_close = false; 
                }             
              }
            }               
            
            $sw = in_array($value['idpermiso'], $valores) ? 'checked' : '';

            $data .= $div_open.'<li>'. 
              '<div class="form-group mb-0">'.
                '<div class="custom-control custom-checkbox">'.
                  '<input id="permiso_'.$value['idpermiso'].'" class="custom-control-input permiso h-1rem w-1rem" type="checkbox" ' . $sw . ' name="permiso[]" value="' . $value['idpermiso'] . '"> '.
                  '<label for="permiso_'.$value['idpermiso'].'" class="custom-control-label font-weight-normal" >' .$value['icono'] .' '. $value['nombre'].'</label>' . 
                '</div>'.
              '</div>'.
            '</li>'. $div_close;
          }

          $retorno = array(
            'status' => true, 
            'message' => 'Salió todo ok', 
            'data' => $data.'</ol>', 
          );

          echo json_encode($retorno, true);

        } else {
          echo json_encode($marcados, true);
        }

      } else {
        echo json_encode($rspta, true);
      }    

    break;    

    case 'select2Trabajador':

      $rspta = $usuario->select2_trabajador();  $data = "";

      if ($rspta['status']) {

        foreach ($rspta['data'] as $key => $value) {
          $data  .= '<option value=' . $value['id'] . ' title="'.$value['imagen_perfil'].'">' . $value['nombre'] . ' - ' . $value['numero_documento'] . '</option>';
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
    
    // ::::::::::::::::::::::::::::::::: S E C C I O N   T R A B A J A D O R :::::::::::::::::::::::::::::
    case 'guardar_y_editar_trabajador':

      // imgen de perfil
      if (!file_exists($_FILES['foto1']['tmp_name']) || !is_uploaded_file($_FILES['foto1']['tmp_name'])) {

        $imagen1=$_POST["foto1_actual"]; $flat_img1 = false;

      } else {

        $ext1 = explode(".", $_FILES["foto1"]["name"]); $flat_img1 = true;						

        $imagen1 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

        move_uploaded_file($_FILES["foto1"]["tmp_name"], "../dist/docs/all_trabajador/perfil/" . $imagen1);
        
      }

      // imgen DNI ANVERSO
      if (!file_exists($_FILES['foto2']['tmp_name']) || !is_uploaded_file($_FILES['foto2']['tmp_name'])) {

        $imagen2=$_POST["foto2_actual"]; $flat_img2 = false;

      } else {

        $ext2 = explode(".", $_FILES["foto2"]["name"]); $flat_img2 = true;

        $imagen2 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext2);

        move_uploaded_file($_FILES["foto2"]["tmp_name"], "../dist/docs/all_trabajador/dni_anverso/" . $imagen2);
        
      }

      // imgen DNI REVERSO
      if (!file_exists($_FILES['foto3']['tmp_name']) || !is_uploaded_file($_FILES['foto3']['tmp_name'])) {

        $imagen3=$_POST["foto3_actual"]; $flat_img3 = false;

      } else {

        $ext3 = explode(".", $_FILES["foto3"]["name"]); $flat_img3 = true;
        
        $imagen3 = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext3);

        move_uploaded_file($_FILES["foto3"]["tmp_name"], "../dist/docs/all_trabajador/dni_reverso/" . $imagen3);
        
      }

      // cv documentado
      if (!file_exists($_FILES['doc4']['tmp_name']) || !is_uploaded_file($_FILES['doc4']['tmp_name'])) {

        $cv_documentado=$_POST["doc_old_4"]; $flat_cv1 = false;

      } else {

        $ext3 = explode(".", $_FILES["doc4"]["name"]); $flat_cv1 = true;
        
        $cv_documentado = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext3);

        move_uploaded_file($_FILES["doc4"]["tmp_name"], "../dist/docs/all_trabajador/cv_documentado/" .  $cv_documentado);
        
      }

      // cv  no documentado
      if (!file_exists($_FILES['doc5']['tmp_name']) || !is_uploaded_file($_FILES['doc5']['tmp_name'])) {

        $cv_nodocumentado=$_POST["doc_old_5"]; $flat_cv2 = false;

      } else {

        $ext3 = explode(".", $_FILES["doc5"]["name"]); $flat_cv2 = true;
        
        $cv_nodocumentado = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext3);

        move_uploaded_file($_FILES["doc5"]["tmp_name"], "../dist/docs/all_trabajador/cv_no_documentado/" . $cv_nodocumentado);
        
      }

      if (empty($idtrabajador)){

        $rspta=$alltrabajador->insertar($nombre_trab, $tipo_documento_trab, $num_documento_trab, $direccion_trab, $telefono_trab, $nacimiento_trab, $edad_trab, $email_trab, $banco_trab, str_replace("-", "",$c_bancaria_trab), $c_bancaria_format, str_replace("-", "",$cci_trab), $cci_format, $titular_cuenta_trab, $tipo_trab, $ocupacion_trab, $ruc_trab, $imagen1, $imagen2, $imagen3, $cv_documentado, $cv_nodocumentado);
        
        echo json_encode($rspta, true);

      }else {            
        $rspta = array( 'status' => false, 'message' => 'No hay editar usuario en este modulo', );      
        echo json_encode($rspta, true);
      }            

    break;

    // default: 
    //   $rspta = ['status'=>'error_code', 'message'=>'Te has confundido en escribir en el <b>swich.</b>', 'data'=>[]]; echo json_encode($rspta, true); 
    // break;
  }
  
  ob_end_flush();
?>
