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
  case 'guardaryeditar':
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

          echo $rspta ? "ok" : "No se pudieron registrar todos los datos del usuario";
        } else {
          $rspta = $usuario->editar($idusuario, $trabajador_old, $trabajador, $cargo, $login, $clavehash, $permiso);

          echo $rspta ? "ok" : "Usuario no se pudo actualizar";
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
        $rspta = $usuario->desactivar($idusuario);

        echo $rspta ? "ok" : "Usuario no se puede desactivar";
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
        $rspta = $usuario->activar($idusuario);
        echo $rspta ? "ok" : "Usuario no se puede activar";
        //Fin de las validaciones de acceso
      } else {
        require 'noacceso.php';
      }
    }
  break;
    //case sun usar 
  case 'activar':
    if (!isset($_SESSION["nombre"])) {
      header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
    } else {
      //Validamos el acceso solo al usuario logueado y autorizado.
      if ($_SESSION['acceso'] == 1) {
        $rspta = $usuario->activar($idusuario);
        echo $rspta ? "ok" : "Usuario no se puede activar";
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
        $rspta = $usuario->eliminar($idusuario);
        echo $rspta ? "ok" : "Usuario no se puede eliminar";
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
        echo json_encode($rspta);
        //Fin de las validaciones de acceso
      } else {
        require 'noacceso.php';
      }
    }
  break;

  case 'listar':
    if (!isset($_SESSION["nombre"])) {
      header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
    } else {
      //Validamos el acceso solo al usuario logueado y autorizado.
      if ($_SESSION['acceso'] == 1) {
        $rspta = $usuario->listar();
        $cont=1;
        //Vamos a declarar un array
        $data = [];
        $imagen_error = "this.src='../dist/svg/user_default.svg'";
        while ($reg = $rspta->fetch_object()) {
          $data[] = [
            "0"=>$cont++,
            "1" => $reg->estado ? '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->idusuario . ')"><i class="fas fa-pencil-alt"></i></button>' .
                ' <button class="btn btn-danger  btn-sm" onclick="eliminar(' . $reg->idusuario . ')"><i class="fas fa-skull-crossbones"></i> </button>':
                '<button class="btn btn-warning  btn-sm" onclick="mostrar(' . $reg->idusuario . ')"><i class="fas fa-pencil-alt"></i></button>' . 
                ' <button class="btn btn-primary  btn-sm" onclick="activar(' . $reg->idusuario . ')"><i class="fa fa-check"></i></button>',
            "2" => '<div class="user-block"> 
                      <img class="img-circle" src="../dist/img/usuarios/' . $reg->imagen_perfil . '" alt="User Image" onerror="' . $imagen_error . '">
                      <span class="username"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >' . $reg->nombres . '</p></span> 
                      <span class="description">' . $reg->tipo_documento .  ': ' . $reg->numero_documento . ' </span>
                    </div>',
            "3" => $reg->telefono,
            "4" => $reg->login,
            "5" => $reg->cargo,
            "6" => $reg->estado ? '<span class="text-center badge badge-success">Activado</span>' : '<span class="text-center badge badge-danger">Desactivado</span>',
          ];
        }
        $results = [
          "sEcho" => 1, //Información para el datatables
          "iTotalRecords" => count($data), //enviamos el total registros al datatable
          "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
          "data" => $data,
        ];
        echo json_encode($results);
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

    //Obtener los permisos asignados al usuario
    $id = $_GET['id'];
    $marcados = $usuario->listarmarcados($id);
    //Declaramos el array para almacenar todos los permisos marcados
    $valores = [];

    //Almacenar los permisos asignados al usuario en el array
    while ($per = $marcados->fetch_object()) {
      array_push($valores, $per->idpermiso);
    }

    $data = "";
    //Mostramos la lista de permisos en la vista y si están o no marcados
    while ($reg = $rspta->fetch_object()) {
      $sw = in_array($reg->idpermiso, $valores) ? 'checked' : '';

      $data .= '<li> <input class="permiso" type="checkbox" ' . $sw . '  name="permiso[]" value="' . $reg->idpermiso . '"> ' . $reg->nombre . ' </li>';
    }

    echo '<li class="text-primary"><input type="checkbox" id="marcar_todo" onclick="marcar_todos_permiso();"> <b class="marcar_todo">Marcar Todo</b></li>'.$data;
  break;

  case 'verificar':
    $logina = $_POST['logina'];
    $clavea = $_POST['clavea'];

    //Hash SHA256 en la contraseña
    $clavehash = hash("SHA256", $clavea);

    $rspta = $usuario->verificar($logina, $clavehash);

    $fetch = $rspta->fetch_object();

    if (isset($fetch)) {
      //Declaramos las variables de sesión
      $_SESSION['idusuario'] = $fetch->idusuario;
      $_SESSION['nombre'] = $fetch->nombres;
      $_SESSION['imagen'] = $fetch->imagen_perfil;
      $_SESSION['login'] = $fetch->login;
      $_SESSION['cargo'] = $fetch->cargo;
      $_SESSION['tipo_documento'] = $fetch->tipo_documento;
      $_SESSION['num_documento'] = $fetch->numero_documento;
      $_SESSION['telefono'] = $fetch->telefono;
      $_SESSION['email'] = $fetch->email;

      //Obtenemos los permisos del usuario
      $marcados = $usuario->listarmarcados($fetch->idusuario);

      //Declaramos el array para almacenar todos los permisos marcados
      $valores = [];

      //Almacenamos los permisos marcados en el array
      while ($per = $marcados->fetch_object()) {
        array_push($valores, $per->idpermiso);
      }

      //Determinamos los accesos del usuario
      in_array(1, $valores) ? ($_SESSION['escritorio'] = 1) : ($_SESSION['escritorio'] = 0);
      in_array(2, $valores) ? ($_SESSION['acceso'] = 1) : ($_SESSION['acceso'] = 0);
      in_array(3, $valores) ? ($_SESSION['recurso'] = 1) : ($_SESSION['recurso'] = 0);
      in_array(4, $valores) ? ($_SESSION['valorizacion'] = 1) : ($_SESSION['valorizacion'] = 0);
      in_array(5, $valores) ? ($_SESSION['trabajador'] = 1) : ($_SESSION['trabajador'] = 0);
      in_array(6, $valores) ? ($_SESSION['asistencia_trabajador'] = 1) : ($_SESSION['asistencia_trabajador'] = 0);
      in_array(7, $valores) ? ($_SESSION['pago_trabajador'] = 1) : ($_SESSION['pago_trabajador'] = 0);
      in_array(8, $valores) ? ($_SESSION['compra'] = 1) : ($_SESSION['compra'] = 0);
      in_array(9, $valores) ? ($_SESSION['servicio_maquina'] = 1) : ($_SESSION['servicio_maquina'] = 0);
      in_array(10, $valores) ? ($_SESSION['servicio_equipo'] = 1) : ($_SESSION['servicio_equipo'] = 0);
      in_array(11, $valores) ? ($_SESSION['calendario'] = 1) : ($_SESSION['calendario'] = 0);
      in_array(12, $valores) ? ($_SESSION['plano_otro'] = 1) : ($_SESSION['plano_otro'] = 0);
      in_array(13, $valores) ? ($_SESSION['viatico'] = 1) : ($_SESSION['viatico'] = 0);
      in_array(14, $valores) ? ($_SESSION['planilla_seguro'] = 1) : ($_SESSION['planilla_seguro'] = 0);
      in_array(15, $valores) ? ($_SESSION['otro_gasto'] = 1) : ($_SESSION['otro_gasto'] = 0);
      in_array(16, $valores) ? ($_SESSION['resumen_general'] = 1) : ($_SESSION['resumen_general'] = 0);
      in_array(17, $valores) ? ($_SESSION['activo_fijo_general'] = 1) : ($_SESSION['activo_fijo_general'] = 0);
      in_array(18, $valores) ? ($_SESSION['resumen_activo_fijo_general'] = 1) : ($_SESSION['resumen_activo_fijo_general'] = 0);
      in_array(19, $valores) ? ($_SESSION['otra_factura'] = 1) : ($_SESSION['otra_factura'] = 0);
      in_array(20, $valores) ? ($_SESSION['resumen_factura'] = 1) : ($_SESSION['resumen_factura'] = 0);
      in_array(21, $valores) ? ($_SESSION['papelera'] = 1) : ($_SESSION['papelera'] = 0);
      in_array(22, $valores) ? ($_SESSION['subcontrato'] = 1) : ($_SESSION['subcontrato'] = 0);
      in_array(23, $valores) ? ($_SESSION['Resumen_recibo_por_honorario'] = 1) : ($_SESSION['Resumen_recibo_por_honorario'] = 0);
    }
    echo json_encode($fetch);
  break;

  case 'select2Trabajador':
    $rspta = $usuario->select2_trabajador();

    while ($reg = $rspta->fetch_object()) {
      echo '<option value=' . $reg->id . '>' . $reg->nombre . ' - ' . $reg->numero_documento . '</option>';
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
