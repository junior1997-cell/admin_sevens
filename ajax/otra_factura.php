<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {

    header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.  
    
  } else {

    if ($_SESSION['otra_factura'] == 1) {

      require_once "../modelos/Otra_factura.php";

      $otra_factura = new Otra_factura();

      $idotra_factura= isset($_POST["idotra_factura"]) ? limpiarCadena($_POST["idotra_factura"]) : "";      
      $idproveedor= isset($_POST["idproveedor"]) ? limpiarCadena($_POST["idproveedor"]) : "";      
      $fecha_emision = isset($_POST["fecha_emision"]) ? limpiarCadena($_POST["fecha_emision"]) : "";
      $forma_pago = isset($_POST["forma_pago"]) ? limpiarCadena($_POST["forma_pago"]) : "";
      $tipo_comprobante = isset($_POST["tipo_comprobante"]) ? limpiarCadena($_POST["tipo_comprobante"]) : "";
      $nro_comprobante = isset($_POST["nro_comprobante"]) ? limpiarCadena($_POST["nro_comprobante"]) : "";
      $subtotal = isset($_POST["subtotal"]) ? limpiarCadena($_POST["subtotal"]) : "";
      $igv = isset($_POST["igv"]) ? limpiarCadena($_POST["igv"]) : "";
      $val_igv = isset($_POST["val_igv"]) ? limpiarCadena($_POST["val_igv"]) : "";
      $precio_parcial = isset($_POST["precio_parcial"]) ? limpiarCadena($_POST["precio_parcial"]) : "";
      $descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
      $glosa = isset($_POST["glosa"]) ? limpiarCadena($_POST["glosa"]) : "";
      $tipo_gravada = isset($_POST["tipo_gravada"]) ? limpiarCadena($_POST["tipo_gravada"]) : "";

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
      
            move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/otra_factura/comprobante/" . $comprobante);
          }
      
          if (empty($idotra_factura)) {
            //var_dump($idproyecto,$idproveedor);
            $rspta = $otra_factura->insertar($idproveedor, $tipo_comprobante, $nro_comprobante, $forma_pago, $fecha_emision, $val_igv, $subtotal, $igv, $precio_parcial, $descripcion, $glosa, $comprobante, $tipo_gravada);
            
            echo $rspta ? "ok" : "No se pudieron registrar todos los datos";
      
          } else {
            //validamos si existe comprobante para eliminarlo
            if ($flat_ficha1 == true) {
      
              $datos_ficha1 = $otra_factura->ObtnerCompr($idotra_factura);
      
              $ficha1_ant = $datos_ficha1->fetch_object()->comprobante;
      
              if ($ficha1_ant != "") {
      
                unlink("../dist/docs/otra_factura/comprobante/" . $ficha1_ant);
              }
            }
      
            $rspta = $otra_factura->editar($idotra_factura, $idproveedor, $tipo_comprobante, $nro_comprobante, $forma_pago, $fecha_emision, $val_igv, $subtotal, $igv, $precio_parcial, $descripcion, $glosa, $comprobante, $tipo_gravada);
            //var_dump($idotra_factura,$idproveedor);
            echo $rspta ? "ok" : "No se pudo actualizar";
          }
        break;
              
        case 'desactivar':
      
          $rspta = $otra_factura->desactivar($idotra_factura);
      
          echo $rspta ? "Desactivado" : "No se puede desactivar";
      
        break;
      
        case 'eliminar':
      
          $rspta = $otra_factura->eliminar($idotra_factura);
      
          echo $rspta ? "Eliminado" : "No se puede Eliminar";
      
        break;
      
        case 'mostrar':
      
          $rspta = $otra_factura->mostrar($idotra_factura);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);
      
        break;
      
      
        case 'listar':
          $rspta = $otra_factura->listar();
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
              "1" => $reg->estado ? '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->idotra_factura. ')"><i class="fas fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-danger  btn-sm" onclick="eliminar(' . $reg->idotra_factura. ')"><i class="fas fa-skull-crossbones"></i> </button>':
                  '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->idotra_factura. ')"><i class="fa fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-primary btn-sm" onclick="activar(' . $reg->idotra_factura. ')"><i class="fa fa-check"></i></button>',
              "2" => $reg->forma_de_pago,
              "3" =>'<div class="user-block">
                  <span class="username" style="margin-left: 0px !important;"> <p class="text-primary" style="margin-bottom: 0.2rem !important";>' .
                $reg->tipo_comprobante .
                '</p> </span>
                  <span class="description" style="margin-left: 0px !important;">N° ' .
                (empty($reg->numero_comprobante) ? " - " : $reg->numero_comprobante) .
                '</span>         
                </div>',
              "4" => date("d/m/Y", strtotime($reg->fecha_emision)),
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
      
          $rspta = $otra_factura->total();
          //Codificar el resultado utilizando json
          echo json_encode($rspta);
      
        break;
        
        case 'select2Proveedor': 

          $rspta=$otra_factura->select2_proveedor();

          while ($reg = $rspta->fetch_object())	{

            echo '<option value=' . $reg->idproveedor . '>' . $reg->razon_social .' - '. $reg->ruc . '</option>';

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
      
    } else {

      require 'noacceso.php';
    }
  }

  ob_end_flush();
?>
