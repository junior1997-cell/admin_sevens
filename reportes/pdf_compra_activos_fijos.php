<?php
//Activamos el almacenamiento en el buffer
ob_start();
if (strlen(session_id()) < 1) {
  session_start();
}

if (!isset($_SESSION["nombre"])) {
  header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
} else {
   
   
  require 'Factura.php';
  require_once "../modelos/Compra_activos_fijos.php";
  require_once "../modelos/Compra_insumos.php";

  //Establecemos la configuración de la factura
  $pdf = new PDF_Invoice('P', 'mm', 'A4');
  
  $compra_activos_fijos = new Compra_activos_fijos();
  $compra_insumo = new Compra_insumos();

  if (empty($_GET)) {
    header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
  } else if ($_GET['op'] == 'insumo') {
    $id = $_GET['id'];
    $rspta = $compra_insumo->ver_compra($id);
    $rspta2 = $compra_insumo->ver_detalle_compra($id);
  } else {
    $id = $_GET['id'];
    $rspta = $compra_activos_fijos->ver_compra_general($id);
    $rspta2 = $compra_activos_fijos->ver_detalle_compra_general($id);
  }

  //Establecemos los datos de la empresa
  $logo     = "../dist/img/default/empresa-logo.jpg";
  $ext_logo = "jpg";
  $empresa  = (strlen($rspta['data']['razon_social'])>= 35 ? substr($rspta['data']['razon_social'], 0, 35).'...' : $rspta['data']['razon_social']);
  $documento= $rspta['data']['tipo_documento'] . ': '. $rspta['data']['ruc'];
  $direccion= (strlen($rspta['data']['direccion']) >= 40? substr($rspta['data']['direccion'], 0, 40).'...' : $rspta['data']['direccion'] );
  $telefono = (empty($rspta['data']['telefono'])? '- - -' : $rspta['data']['telefono']) ;

  //Enviamos los datos de la empresa al método addSociete de la clase Factura
  $pdf->AddPage();  
  $pdf->addSociete(utf8_decode($empresa), 
  $documento . "\n" . utf8_decode("Dirección: ") . utf8_decode($direccion) . "\n" . utf8_decode("Teléfono: ") . $telefono , 
  $logo, $ext_logo);
  $pdf->fact_dev($rspta['data']['tipo_comprobante'], $rspta['data']['serie_comprobante']);
  $pdf->addDate(format_d_m_a($rspta['data']['fecha_compra']));

  $pdf->temporaire( utf8_decode("Seven's Ingenieros") );

  //Enviamos los datos del cliente al método addClientAdresse de la clase Factura
  $pdf->addClientAdresse(utf8_decode("SEVEN'S INGENIEROS S.A.C."), 
    "Domicilio: " . utf8_decode('Pj. Yungay Nro. 151 P.J. Santa Rosa Lambayeque / Chiclayo / Chiclayo'), 
    'Ruc' . ": " .'20606456892', 
    "Email: " . 'gerencia@sevensingenieros.com', 
    "Telefono: " . '+51 954 201 310'
  );
  $pdf->addReference( utf8_decode( decodeCadenaHtml((empty($rspta['data']['descripcion'])) ? '- - -' :$rspta['data']['descripcion']) ));

  //Establecemos las columnas que va a tener la sección donde mostramos los detalles de la venta
  $cols = [ "#" => 8, "PRODUCTO" => 70, "UM" => 13, "CANT." => 14, "V/U" => 18, "IGV" => 14, "P.U." => 20, "DSCT." => 13, "SUBTOTAL" => 20];
  $pdf->addCols($cols);
  $cols = [ "#" => "C", "PRODUCTO" => "L", "UM" => "C",  "CANT." => "C", "V/U" => "R", "IGV" => "R","P.U." => "R", "DSCT." => "R", "SUBTOTAL" => "R"];
  $pdf->addLineFormat($cols);
  $pdf->addLineFormat($cols);
  //Actualizamos el valor de la coordenada "y", que será la ubicación desde donde empezaremos a mostrar los datos
  $y = 89;

  $cont = 1;
  //Obtenemos todos los detalles de la venta actual
  while ($reg = $rspta2['data']->fetch_object()) {
    $line = [ "#" => $cont++, "PRODUCTO" => utf8_decode( decodeCadenaHtml($reg->nombre)), "UM" => $reg->abreviacion, "CANT." => $reg->cantidad, "V/U" => number_format($reg->precio_sin_igv, 2, '.',','), "IGV" => number_format($reg->igv, 2, '.',','), "P.U." => number_format($reg->precio_con_igv, 2, '.',','), "DSCT." => number_format($reg->descuento, 2, '.',','), "SUBTOTAL" => number_format($reg->subtotal, 2, '.',',')];
    $size = $pdf->addLine($y, $line);
    $y += $size + 2;
  }

  //Convertimos el total en letras
  require_once "Letras.php";
  $V = new EnLetras();
  $num_total = floatval($rspta['data']['total']);
  $con_letra = strtoupper($V->ValorEnLetras(503, "SOLES"));
  $pdf->addCadreTVAs("---" . $con_letra);

  //Mostramos el impuesto
  $pdf->addTVAs(number_format($rspta['data']['subtotal'], 2, '.',','), number_format($rspta['data']['igv'], 2, '.',','), number_format($rspta['data']['total'], 2, '.',','), "S/ ");
  $pdf->addCadreEurosFrancs('IGV ('.( ( empty($rspta['data']['val_igv']) ? 0 : floatval($rspta['data']['val_igv']) )  * 100 ) . '%)');
  $pdf->Output('Reporte de compra.pdf', 'I');
   
}

function number_words($valor,$desc_moneda, $sep, $desc_decimal) {
  $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
  return $f->format(1432);
}
ob_end_flush();
?>
