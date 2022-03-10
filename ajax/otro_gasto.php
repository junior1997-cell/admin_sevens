<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {

    header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.  
    
  } else {

    if ($_SESSION['otro_gasto'] == 1) {

      require_once "../modelos/Otro_gasto.php";

      $otro_gasto = new Otro_gasto();
      
      $idproyecto = isset($_POST["idproyecto"]) ? limpiarCadena($_POST["idproyecto"]) : "";
      $idotro_gasto = isset($_POST["idotro_gasto"]) ? limpiarCadena($_POST["idotro_gasto"]) : "";      
      $fecha_g = isset($_POST["fecha_g"]) ? limpiarCadena($_POST["fecha_g"]) : "";
      $forma_pago = isset($_POST["forma_pago"]) ? limpiarCadena($_POST["forma_pago"]) : "";
      $tipo_comprobante = isset($_POST["tipo_comprobante"]) ? limpiarCadena($_POST["tipo_comprobante"]) : "";
      $nro_comprobante = isset($_POST["nro_comprobante"]) ? limpiarCadena($_POST["nro_comprobante"]) : "";
      $subtotal = isset($_POST["subtotal"]) ? limpiarCadena($_POST["subtotal"]) : "";
      $igv = isset($_POST["igv"]) ? limpiarCadena($_POST["igv"]) : "";
      $precio_parcial = isset($_POST["precio_parcial"]) ? limpiarCadena($_POST["precio_parcial"]) : "";
      $descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";

      $ruc = isset($_POST["ruc"]) ? limpiarCadena($_POST["ruc"]) : "";
      $razon_social = isset($_POST["razon_social"]) ? limpiarCadena($_POST["razon_social"]) : "";
      $direccion = isset($_POST["direccion"]) ? limpiarCadena($_POST["direccion"]) : "";
      $glosa = isset($_POST["glosa"]) ? limpiarCadena($_POST["glosa"]) : "";

      $foto2 = isset($_POST["doc1"]) ? limpiarCadena($_POST["doc1"]) : "";
      
      switch ($_GET["op"]) {
        case 'guardaryeditar':
          // Comprobante
          if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {
      
            $comprobante = $_POST["doc_old_1"];
      
            $flat_ficha1 = false;
      
          } else {
      
            $ext1 = explode(".", $_FILES["doc1"]["name"]);
      
            $flat_ficha1 = true;
      
            $comprobante = rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);
      
            move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/otro_gasto/comprobante/" . $comprobante);
          }
      
          if (empty($idotro_gasto)) {
            //var_dump($idproyecto,$idproveedor);
            $rspta = $otro_gasto->insertar($idproyecto, $fecha_g, $precio_parcial, $subtotal, $igv, $descripcion, $forma_pago, $tipo_comprobante, $nro_comprobante, $comprobante, $ruc, $razon_social, $direccion, $glosa);
            
            echo $rspta ? "ok" : "No se pudieron registrar todos los datos";
      
          } else {
            //validamos si existe comprobante para eliminarlo
            if ($flat_ficha1 == true) {
      
              $datos_ficha1 = $otro_gasto->ficha_tec($idotro_gasto);
      
              $ficha1_ant = $datos_ficha1->fetch_object()->comprobante;
      
              if ($ficha1_ant != "") {
      
                unlink("../dist/docs/otro_gasto/comprobante/" . $ficha1_ant);
              }
            }
      
            $rspta = $otro_gasto->editar($idotro_gasto, $idproyecto, $fecha_g, $precio_parcial, $subtotal, $igv, $descripcion, $forma_pago, $tipo_comprobante, $nro_comprobante, $comprobante, $ruc, $razon_social, $direccion,$glosa);
            //var_dump($idotro_gasto,$idproveedor);
            echo $rspta ? "ok" : "No se pudo actualizar";
          }
        break;
      
        case 'desactivar':
      
          $rspta = $otro_gasto->desactivar($idotro_gasto);
      
          echo $rspta ? " Desactivado" : "No se puede desactivar";
      
        break;
      
        case 'activar':
      
          $rspta = $otro_gasto->activar($idotro_gasto);
      
          echo $rspta ? "Activado" : "No se puede activar";
      
        break;

        case 'eliminar':
      
          $rspta = $otro_gasto->eliminar($idotro_gasto);
      
          echo $rspta ? "Elinado" : "No se puede Eliminar";
      
        break;
      
        case 'mostrar':
      
          $rspta = $otro_gasto->mostrar($idotro_gasto);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);
      
        break;
      
        case 'verdatos':
      
          $rspta = $otro_gasto->mostrar($idotro_gasto);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);
      
        break;
      
        case 'listar':
          $idproyecto = $_GET["idproyecto"];
          $rspta = $otro_gasto->listar($idproyecto);
          //Vamos a declarar un array
          $data = [];
          $comprobante = '';
          $cont = 1;
          while ($reg = $rspta->fetch_object()) {
            // empty($reg->comprobante)?$comprobante='<div><center><a type="btn btn-danger" class=""><i class="far fa-times-circle fa-2x"></i></a></center></div>':$comprobante='<center><a target="_blank" href="../dist/img/comprob_otro_gasto/'.$reg->comprobante.'"><i class="far fa-file-pdf fa-2x" style="color:#ff0000c4"></i></a></center>';
      
            empty($reg->comprobante)
              ? ($comprobante = '<div><center><a type="btn btn-danger" class=""><i class="fas fa-file-invoice-dollar fa-2x text-gray-50"></i></a></center></div>')
              : ($comprobante = '<div><center><a type="btn btn-danger" class=""  href="#" onclick="modal_comprobante(' . "'" . $reg->comprobante . "'" . ')"><i class="fas fa-file-invoice-dollar fa-2x"></i></a></center></div>');
            if (strlen($reg->descripcion) >= 20) {
              $descripcion = substr($reg->descripcion, 0, 20) . '...';
            } else {
              $descripcion = $reg->descripcion;
            }
            $tool = '"tooltip"';
            $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>";
            $data[] = [
              "0" => $cont++,
              "1" => $reg->estado ? '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->idotro_gasto . ')"><i class="fas fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-danger  btn-sm" onclick="eliminar(' . $reg->idotro_gasto . ')"><i class="fas fa-skull-crossbones"></i> </button>':
                  '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->idotro_gasto . ')"><i class="fa fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-primary btn-sm" onclick="activar(' . $reg->idotro_gasto . ')"><i class="fa fa-check"></i></button>',
              "2" => $reg->forma_de_pago,
              "3" =>'<div class="user-block">
                  <span class="username" style="margin-left: 0px !important;"> <p class="text-primary" style="margin-bottom: 0.2rem !important";>' .
                $reg->tipo_comprobante .
                '</p> </span>
                  <span class="description" style="margin-left: 0px !important;">N° ' .
                (empty($reg->numero_comprobante) ? " - " : $reg->numero_comprobante) .
                '</span>         
                </div>',
              "4" => date("d/m/Y", strtotime($reg->fecha_g)),
              "5" =>'S/. '. number_format($reg->subtotal, 2, '.', ','),
              "6" =>'S/. '. number_format($reg->igv, 2, '.', ','),
              "7" =>'S/. '. number_format($reg->costo_parcial, 2, '.', ','),
              "8" => '<textarea cols="30" rows="1" class="text_area_clss" readonly="">' . $reg->descripcion . '</textarea>',
              "9" => $comprobante,
              "10" => $reg->estado ? '<span class="text-center badge badge-success">Activado</span>' . $toltip : '<span class="text-center badge badge-danger">Desactivado</span>' . $toltip,
            ];
          }
          $results = [
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
            "data" => $data,
          ];
          echo json_encode($results);
        break;
      
        case 'total':
      
          $rspta = $otro_gasto->total($idproyecto);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);
      
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
      
    } else {

      require 'noacceso.php';
    }
  }

  ob_end_flush();
?>
