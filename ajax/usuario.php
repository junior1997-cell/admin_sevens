<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }
  require_once "../modelos/Usuario.php";

  $usuario = new Usuario();

  $idusuario = isset($_POST["idusuario"]) ? limpiarCadena($_POST["idusuario"]) : "";
  $trabajador = isset($_POST["trabajador"]) ? limpiarCadena($_POST["trabajador"]) : "";
  $trabajador_old = isset($_POST["trabajador_old"]) ? limpiarCadena($_POST["trabajador_old"]) : "";
  $cargo = isset($_POST["cargo"]) ? limpiarCadena($_POST["cargo"]) : "";
  $login = isset($_POST["login"]) ? limpiarCadena($_POST["login"]) : "";
  $clave = isset($_POST["password"]) ? limpiarCadena($_POST["password"]) : "";
  $clave_old = isset($_POST["password-old"]) ? limpiarCadena($_POST["password-old"]) : "";
  $permiso = isset($_POST['permiso']) ? $_POST['permiso'] : "";

  switch ($_GET["op"]) {
    case 'guardar_y_editar_usuario':
      if (!isset($_SESSION["nombre"])) {
        header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
      } else {
        //Validamos el acceso solo al usuario logueado y autorizado.
        if ($_SESSION['acceso'] == 1) {
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
          //Fin de las validaciones de acceso
        } else {
          require 'noacceso.php';
        }
      }
    break;

    case 'desactivar':
      if (!isset($_SESSION["nombre"])) {
        header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
      } else {
        //Validamos el acceso solo al usuario logueado y autorizado.
        if ($_SESSION['acceso'] == 1) {
          $rspta = $usuario->desactivar($_POST["id_tabla"]);

          echo json_encode($rspta, true);
          //Fin de las validaciones de acceso
        } else {
          require 'noacceso.php';
        }
      }
    break;

    case 'activar':
      if (!isset($_SESSION["nombre"])) {
        header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
      } else {
        //Validamos el acceso solo al usuario logueado y autorizado.
        if ($_SESSION['acceso'] == 1) {
          $rspta = $usuario->activar($_POST["id_tabla"]);
          echo json_encode($rspta, true);
          //Fin de las validaciones de acceso
        } else {
          require 'noacceso.php';
        }
      }
    break;

    case 'eliminar':
      if (!isset($_SESSION["nombre"])) {
        header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
      } else {
        //Validamos el acceso solo al usuario logueado y autorizado.
        if ($_SESSION['acceso'] == 1) {
          $rspta = $usuario->eliminar($_POST["id_tabla"]);
          echo json_encode($rspta, true);
          //Fin de las validaciones de acceso
        } else {
          require 'noacceso.php';
        }
      }
    break;

    case 'mostrar':
      if (!isset($_SESSION["nombre"])) {
        header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
      } else {
        //Validamos el acceso solo al usuario logueado y autorizado.
        if ($_SESSION['acceso'] == 1) {
          $rspta = $usuario->mostrar($idusuario);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
          //Fin de las validaciones de acceso
        } else {
          require 'noacceso.php';
        }
      }
    break;

    case 'tbla_principal':
      if (!isset($_SESSION["nombre"])) {
        header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
      } else {
        //Validamos el acceso solo al usuario logueado y autorizado.
        if ($_SESSION['acceso'] == 1) {

          $rspta = $usuario->listar();
          
          //Vamos a declarar un array
          $data = [];  $imagen_error = "this.src='../dist/svg/user_default.svg'"; $cont=1;
          if ($rspta['status']) {
            foreach ($rspta['data'] as $key => $value) {
              $data[] = [
                "0"=>$cont++,
                "1" => $value['estado'] ? '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $value['idusuario'] . ')"><i class="fas fa-pencil-alt"></i></button>' .
                    ' <button class="btn btn-danger  btn-sm" onclick="eliminar(' . $value['idusuario'] . ')"><i class="fas fa-skull-crossbones"></i> </button>':
                    '<button class="btn btn-warning  btn-sm" onclick="mostrar(' . $value['idusuario'] . ')"><i class="fas fa-pencil-alt"></i></button>' . 
                    ' <button class="btn btn-primary  btn-sm" onclick="activar(' . $value['idusuario'] . ')"><i class="fa fa-check"></i></button>',
                "2" => '<div class="user-block">'. 
                  '<img class="img-circle" src="../dist/docs/all_trabajador/perfil/' . $value['imagen_perfil'] . '" alt="User Image" onerror="' . $imagen_error . '">'.
                  '<span class="username"><p class="text-primary m-b-02rem" >' . $value['nombres'] . '</p></span>'. 
                  '<span class="description">' . $value['tipo_documento'] .  ': ' . $value['numero_documento'] . ' </span>'.
                '</div>',
                "3" => $value['telefono'],
                "4" => $value['login'],
                "5" => $value['cargo'],
                "6" => $value['estado'] ? '<span class="text-center badge badge-success">Activado</span>' : '<span class="text-center badge badge-danger">Desactivado</span>',
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
          
          //Fin de las validaciones de acceso
        } else {
          require 'noacceso.php';
        }
      }
    break;

    case 'permisos':
      //Obtenemos todos los permisos de la tabla permisos
      require_once "../modelos/Permiso.php";
      $permiso = new Permiso();
      $rspta = $permiso->listar();

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

          $data = "";
          //Mostramos la lista de permisos en la vista y si están o no marcados <label for=""></label>
          foreach ($rspta['data'] as $key => $value) {
            
            $sw = in_array($value['idpermiso'], $valores) ? 'checked' : '';

            $data .= '<li> <input class="permiso" type="checkbox" ' . $sw . '  name="permiso[]" value="' . $value['idpermiso'] . '"> ' . $value['nombre'] . ' </li>';
          }

          $retorno = array(
            'status' => true, 
            'message' => 'Salió todo ok', 
            'data' => '<li class="text-primary"><input type="checkbox" id="marcar_todo" onclick="marcar_todos_permiso();"> <label for="marcar_todo" class="marcar_todo">Marcar Todo</label></li>'.$data, 
          );

          echo json_encode($retorno, true);

        } else {
          echo json_encode($marcados, true);
        }

      } else {
        echo json_encode($rspta, true);
      }    

    break;

    case 'verificar':
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
          in_array(1, $valores) ? ($_SESSION['escritorio'] = 1) : ($_SESSION['escritorio'] = 0);
          in_array(2, $valores) ? ($_SESSION['acceso'] = 1) : ($_SESSION['acceso'] = 0);
          in_array(3, $valores) ? ($_SESSION['recurso'] = 1) : ($_SESSION['recurso'] = 0);
          in_array(4, $valores) ? ($_SESSION['valorizacion'] = 1) : ($_SESSION['valorizacion'] = 0);
          in_array(5, $valores) ? ($_SESSION['trabajador'] = 1) : ($_SESSION['trabajador'] = 0);
          in_array(6, $valores) ? ($_SESSION['asistencia_obrero'] = 1) : ($_SESSION['asistencia_obrero'] = 0);
          in_array(7, $valores) ? ($_SESSION['pago_trabajador'] = 1) : ($_SESSION['pago_trabajador'] = 0);
          in_array(8, $valores) ? ($_SESSION['compra_insumos'] = 1) : ($_SESSION['compra_insumos'] = 0);
          in_array(9, $valores) ? ($_SESSION['servicio_maquina'] = 1) : ($_SESSION['servicio_maquina'] = 0);
          in_array(10, $valores) ? ($_SESSION['servicio_equipo'] = 1) : ($_SESSION['servicio_equipo'] = 0);
          in_array(11, $valores) ? ($_SESSION['calendario'] = 1) : ($_SESSION['calendario'] = 0);
          in_array(12, $valores) ? ($_SESSION['plano_otro'] = 1) : ($_SESSION['plano_otro'] = 0);
          in_array(13, $valores) ? ($_SESSION['viatico'] = 1) : ($_SESSION['viatico'] = 0);
          in_array(14, $valores) ? ($_SESSION['planilla_seguro'] = 1) : ($_SESSION['planilla_seguro'] = 0);
          in_array(15, $valores) ? ($_SESSION['otro_gasto'] = 1) : ($_SESSION['otro_gasto'] = 0);
          in_array(16, $valores) ? ($_SESSION['resumen_general'] = 1) : ($_SESSION['resumen_general'] = 0);
          in_array(17, $valores) ? ($_SESSION['compra_activo_fijo'] = 1) : ($_SESSION['compra_activo_fijo'] = 0);
          in_array(18, $valores) ? ($_SESSION['resumen_activo_fijo_general'] = 1) : ($_SESSION['resumen_activo_fijo_general'] = 0);
          in_array(19, $valores) ? ($_SESSION['otra_factura'] = 1) : ($_SESSION['otra_factura'] = 0);
          in_array(20, $valores) ? ($_SESSION['resumen_factura'] = 1) : ($_SESSION['resumen_factura'] = 0);
          in_array(21, $valores) ? ($_SESSION['papelera'] = 1) : ($_SESSION['papelera'] = 0);
          in_array(22, $valores) ? ($_SESSION['subcontrato'] = 1) : ($_SESSION['subcontrato'] = 0);
          in_array(23, $valores) ? ($_SESSION['resumen_recibo_por_honorario'] = 1) : ($_SESSION['resumen_recibo_por_honorario'] = 0);

        } else {
          echo json_encode($rspta, true);
        }
      }else{
        echo json_encode($rspta, true);
      }
      
    break;

    case 'select2Trabajador':

      $rspta = $usuario->select2_trabajador();  $data = "";

      if ($rspta['status']) {

        foreach ($rspta['data'] as $key => $value) {
          $data  .= '<option value=' . $value['id'] . '>' . $value['nombre'] . ' - ' . $value['numero_documento'] . '</option>';
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

    case 'salir':
      //Limpiamos las variables de sesión
      session_unset();
      //Destruìmos la sesión
      session_destroy();
      //Redireccionamos al login
      header("Location: ../index.php");

    break;
  }
  ob_end_flush();
?>
