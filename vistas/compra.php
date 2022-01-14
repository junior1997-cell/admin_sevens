<?php
  //Activamos el almacenamiento en el buffer
  ob_start();
  session_start();

  if (!isset($_SESSION["nombre"])){
    header("Location: login.html");
  }else{ ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Admin Sevens | Compras</title>
        <?php
          require 'head.php';
        ?>
        <!--CSS  switch_MATERIALES-->
        <link rel="stylesheet" href="../dist/css/switch_compra.css" />
        <link rel="stylesheet" href="../dist/css/carouselTicker.css" />
        <!-- Theme style -->
        <!-- <link rel="stylesheet" href="../dist/css/adminlte.min.css"> -->
    </head>
    <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed">
        <div class="wrapper">
            <!-- Preloader -->
            <!-- <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="../dist/svg/logo-principal.svg" alt="AdminLTELogo" width="360" />
          </div> -->

            <?php
            require 'nav.php';
            require 'aside.php';
            if ($_SESSION['compra']==1){
          ?>
            <!--Contenido-->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0">Compras</h1>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="compra.php">Home</a></li>
                                    <li class="breadcrumb-item active">Compras</li>
                                </ol>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.container-fluid -->
                </div>
                <!-- /.content-header -->

                <!-- Main content -->
                <section class="content">

                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card card-primary card-outline">
                                                            <!-- Start Main Top -->
                            <div class="main-top">
                                <div class="container-fluid">
                                    <div class="row" >
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        
                                        <div class="card-header">
                                        <h3 class="card-title">
                                            
                                            <!--data-toggle="modal" data-target="#modal-agregar-compra"  onclick="limpiar();"-->
                                            <button type="button" class="btn bg-gradient-success" id="btn_agregar" onclick="ver_form_add();"><i class="fas fa-plus-circle"></i> Agregar</button>
                                            <button type="button" class="btn bg-gradient-warning" id="regresar" style="display: none;" onclick="regresar();"><i class="fas fa-arrow-left"></i> Regresar</button>
                                            <button type="button" id="btn-pagar" class="btn bg-gradient-success" data-toggle="modal" style="display: none;" data-target="#modal-agregar-pago" onclick="limpiar_c_pagos();">
                                                <i class="fas fa-dollar-sign"></i> Agregar Pago
                                            </button>
                                            <button type="button" id="btn-factura" class="btn bg-gradient-success" data-toggle="modal" style="display: none;" data-target="#modal-agregar-factura" onclick="limpiar_factura();">
                                                <i class="fas fa-file-invoice"></i> Agregar Factura
                                            </button>
                                        </h3>
                                    </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <div class="text-slid-box  ">
                                                <div id="offer-box" class="carouselTicker" style="float: right !important;">
                                                    <ul class="offer-box">
                                                        <li>
                                                    <span style=" color: #ffffff;"> Horario de atencíon </span>
                                                        </li>
                                                        <li>
                                                        <span style=" color: #ffffff;" > Lunes a Jueves de 8:00am a 1:00pm - 3:00pm a 6:00pm </span> 
                                                        </li>
                                                        <li>
                                                        <span style=" color: #ffffff;">  Viernes  de 8:00am a 1:00pm </span> 
                                                        </li>                            
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <!-- End Main Top -->

                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <div id="div_tabla_compra">
                                            <h5><b>Lista de compras Por Facturas</b></h5>
                                            <table id="tabla-compra" class="table table-bordered table-striped display" style="width: 100% !important;">
                                                <thead>
                                                    <tr>
                                                        <th class="">Acciones</th>
                                                        <th>Fecha</th>
                                                        <th>Proveedor</th>
                                                        <th data-toggle="tooltip" data-original-title="Tipo y Número Comprobante">Tipo. Num. - Comprob</th>
                                                        <th>Detracción</th>
                                                        <th>Total</th>
                                                        <th>Añadir pago</th>
                                                        <th>Saldo</th>
                                                        <th>Comprobantes</th>
                                                        <th>Descripción</th>
                                                        <th>Estado</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th class="">Acciones</th>
                                                        <th>Fecha</th>
                                                        <th>Proveedor</th>
                                                        <th data-toggle="tooltip" data-original-title="Tipo y Número Comprobante">Tipo. Num.-Comprob</th>
                                                        <th>Detracción</th>
                                                        <th>Total</th>
                                                        <th>Añadir pago</th>
                                                        <th>Saldo</th>
                                                        <th>Comprobantes</th>
                                                        <th>Descripción</th>
                                                        <th>Estado</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            <br />
                                            <h4><b>Lista de Compras Por Proveedor</b></h4>
                                            <table id="tabla-compra-proveedor" class="table table-bordered table-striped display" style="width: 100% !important;">
                                                <thead>
                                                    <tr>
                                                        <th class="">Acciones</th>
                                                        <th>Proveedor</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th class="">Acciones</th>
                                                        <th>Proveedor</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <div id="div_tabla_compra_proveedor" style="display: none;">
                                            <h5><b>Lista de compras Por Facturas</b></h5>
                                            <table id="detalles-tabla-compra-prov" class="table table-bordered table-striped display" style="width: 100% !important;">
                                                <thead>
                                                    <tr>
                                                        <th class="">Acciones</th>
                                                        <th>Fecha</th>
                                                        <th>Comprobante</th>
                                                        <th data-toggle="tooltip" data-original-title="Número Comprobante">Num. Comprobante</th>
                                                        <th>Total</th>
                                                        <th>Descripcion</th>
                                                        <th>Estado</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th class="">Acciones</th>
                                                        <th>Fecha</th>
                                                        <th>Comprobante</th>
                                                        <th data-toggle="tooltip" data-original-title="Número Comprobante">Num. Comprobante</th>
                                                        <th>Total</th>
                                                        <th>Descripcion</th>
                                                        <th>Estado</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <!--agregar_compras-->
                                        <div id="agregar_compras" style="display: none;">
                                            <div class="modal-body">
                                                <!-- form start -->
                                                <form id="form-compras" name="form-compras" method="POST">
                                                    <div class="card-body">
                                                        <div class="row" id="cargando-1-fomulario">
                                                            <!-- id proyecto -->
                                                            <input type="hidden" name="idproyecto" id="idproyecto" />
                                                            <input type="hidden" name="idcompra_proyecto" id="idcompra_proyecto" />

                                                            <!-- Tipo de Empresa -->
                                                            <div class="col-lg-7">
                                                                <div class="form-group">
                                                                    <label for="idproveedor">Proveedor</label>
                                                                    <select id="idproveedor" name="idproveedor" class="form-control select2" data-live-search="true" required title="Seleccione cliente"> </select>
                                                                </div>
                                                            </div>
                                                            <!-- adduser -->
                                                            <div class="col-lg-1">
                                                                <div class="form-group">
                                                                    <label for="Add" style="color: white;">.</label>
                                                                    <a data-toggle="modal" href="#agregar_cliente">
                                                                        <button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#modal-agregar-proveedor" onclick="limpiar();">
                                                                            <i class="fa fa-user-plus" aria-hidden="true"></i>
                                                                        </button>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <!-- fecha -->
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label for="fecha_compra">Fecha </label>
                                                                    <input type="date" name="fecha_compra" id="fecha_compra" class="form-control" placeholder="Fecha" />
                                                                </div>
                                                            </div>
                                                            <!-- Tipo de comprobante -->
                                                            <div class="col-lg-4" id="content-t-comprob">
                                                                <div class="form-group">
                                                                    <label for="tipo_comprovante">Tipo Comprobante</label>
                                                                    <select
                                                                        name="tipo_comprovante"
                                                                        id="tipo_comprovante"
                                                                        class="form-control select2"
                                                                        onchange="mostrar_igv(); ocultar_comprob();"
                                                                        placeholder="Seleccinar un tipo de comprobante"
                                                                    >
                                                                        <option selected value="Ninguno">Ninguno</option>
                                                                        <option selected value="Boleta">Boleta</option>
                                                                        <option value="Factura">Factura</option>
                                                                        <option value="Nota_de_venta">Nota de venta</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <!-- serie_comprovante-->
                                                            <div class="col-lg-2" id="content-comprob">
                                                                <div class="form-group">
                                                                    <label for="serie_comprovante">N° de Comprobante</label>
                                                                    <input type="text" name="serie_comprovante" id="serie_comprovante" class="form-control" placeholder="N° de Comprobante" />
                                                                </div>
                                                            </div>
                                                            <!-- IGV-->
                                                            <div class="col-lg-1" id="content-igv">
                                                                <div class="form-group">
                                                                    <label for="igv">IGV</label>
                                                                    <input type="text" name="igv" id="igv" class="form-control" readonly value="0.18" />
                                                                </div>
                                                            </div>
                                                            <!-- Descripcion-->
                                                            <div class="col-lg-5" id="content-descrp">
                                                                <div class="form-group">
                                                                    <label for="descripcion">Descripción </label> <br />
                                                                    <textarea name="descripcion" id="descripcion" class="form-control" rows="1"></textarea>
                                                                </div>
                                                            </div>
                                                            <!--Boton agregar material-->
                                                            <div class="row col-lg-12 justify-content-between">
                                                                <div class="col-lg-3 xs-12">
                                                                    <label for="" style="color: white;">.</label> <br />
                                                                    <a data-toggle="modal" data-target="#modal-elegir-material">
                                                                        <button id="btnAgregarArt" type="button" class="btn btn-success"><span class="fa fa-plus"></span> Agregar Material</button>
                                                                    </a>
                                                                </div>
                                                                <!-- Rounded switch -->
                                                                <div class="col-lg-1 class_pading">
                                                                    <div class="form-group">
                                                                        <div id="switch_detracc">
                                                                            <label for="">Detracción ?</label> <br />
                                                                            <!-- <input type="checkbox" name="my-checkbox" id="my-checkbox"   data-bootstrap-switch data-off-color="danger" data-on-color="success" > -->
                                                                            <div class="switch-holder" style="padding: 0px 0px !important;">
                                                                                <div class="switch-toggle">
                                                                                    <input type="checkbox" id="my-switch_detracc" />
                                                                                    <label for="my-switch_detracc"></label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <input type="hidden" name="estado_detraccion" id="estado_detraccion" value="0" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!--tabla detalles plantas-->
                                                            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
                                                                <br />
                                                                <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                                                                    <thead style="background-color: #ff6c046b;">
                                                                        <th>Opciones</th>
                                                                        <th>Material</th>
                                                                        <th>Unidad</th>
                                                                        <th>Cantidad</th>
                                                                        <th>Precio Compra(Sin IGV)</th>
                                                                        <th class="hidden">IGV</th>
                                                                        <th class="hidden">Precio(Con IGV)</th>
                                                                        <th>Descuento</th>
                                                                        <th>Subtotal</th>
                                                                    </thead>
                                                                    <tfoot>
                                                                        <td colspan="5" id="colpan"></td>
                                                                        <th class="text-center">
                                                                            <h5>Subtotal</h5>
                                                                            <h5>IGV</h5>
                                                                            <h5>TOTAL</h5>
                                                                        </th>
                                                                        <th>
                                                                            <h5 class="text-right" id="subtotal" style="font-weight: bold;">S/. 0.00</h5>
                                                                            <input type="text" name="subtotal_compra" id="subtotal_compra" />

                                                                            <h5 class="text-right" name="igv_comp" id="igv_comp" style="font-weight: bold;">S/. 0.00</h5>
                                                                            <input type="text" name="igv_compra" id="igv_compra" />
                                                                            <b>
                                                                                <h4 class="text-right" id="total" style="font-weight: bold;">S/. 0.00</h4>
                                                                                <input type="text" name="total_venta" id="total_venta" />
                                                                            </b>
                                                                        </th>
                                                                    </tfoot>
                                                                    <tbody></tbody>
                                                                </table>
                                                            </div>
                                                            <div class="row" id="cargando-2-fomulario" style="display: none;">
                                                                <div class="col-lg-12 text-center">
                                                                    <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                                                                    <br />
                                                                    <h4>Cargando...</h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- /.card-body -->
                                                    <button type="submit" style="display: none;" id="submit-form-compras">Submit</button>
                                                </form>
                                            </div>

                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-danger" onclick="regresar();" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-success" style="display: none;" id="guardar_registro_compras">Guardar Cambios</button>
                                            </div>
                                        </div>
                                        <!-- tabla_facturas Facturas Compras-->
                                        <div id="factura_compras" style="display: none;">
                                            <h5><b>Lista de compras Por Facturas</b></h5>

                                            <!--<div style="text-align:center;"> <h4 style="background: aliceblue;">Costo parcial: <b id="total_costo" style="color: #e52929;"></b> </h5> </div>-->
                                            <table id="tabla_facturas" class="table table-bordered table-striped display" style="width: 100% !important;">
                                                <thead>
                                                    <tr>
                                                        <th>Aciones</th>
                                                        <th>Código</th>
                                                        <th>Fecha Emisión</th>
                                                        <th>Sub total</th>
                                                        <th>IGV</th>
                                                        <th>Monto</th>
                                                        <th>Descripción</th>
                                                        <th>Factura</th>
                                                        <th>Estado</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th>Aciones</th>
                                                        <th>Código</th>
                                                        <th>Fecha Emisión</th>
                                                        <th>Sub total</th>
                                                        <th>IGV</th>
                                                        <th id="monto_total_f" style="color: #ff0000; background-color: #f3e700;"></th>
                                                        <th>Descripción</th>
                                                        <th>Factura</th>
                                                        <th>Estado</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>

                                        <!--Pagos sin detracciòn-->
                                        <div id="pago_compras" style="display: none;">
                                            <h5>pago Compras</h5>
                                            <div style="text-align: center;">
                                                <div> <h4>Total a pagar:  <b id="total_compra"></b> </h4></div> 
                                                <table id="tabla-pagos-proveedor" class="table table-bordered table-striped display" style="width: 100% !important;">
                                                    <thead>
                                                        <tr>
                                                            <th>Acciones</th>
                                                            <th>Forma pago</th>
                                                            <th>Beneficiario</th>
                                                            <th data-toggle="tooltip" data-original-title="Cuenta Destino">C. Destino</th>
                                                            <th>Banco</th>
                                                            <th data-toggle="tooltip" data-original-title="Titular Cuenta">Titular C.</th>
                                                            <th data-toggle="tooltip" data-original-title="Fecha Pago">Fecha P.</th>
                                                            <th>Descripción</th>
                                                            <th data-toggle="tooltip" data-original-title="Número Operación">Número Op.</th>
                                                            <th>Monto</th>
                                                            <th>Vaucher</th>
                                                            <th>Estado</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th>Aciones</th>
                                                            <th>Forma pago</th>
                                                            <th>Beneficiario</th>
                                                            <th data-toggle="tooltip" data-original-title="Cuenta Destino">C. Destino</th>
                                                            <th>Banco</th>
                                                            <th data-toggle="tooltip" data-original-title="Titular Cuenta">Titular C.</th>
                                                            <th data-toggle="tooltip" data-original-title="Fecha Pago">Fecha P.</th>
                                                            <th>Descripción</th>
                                                            <th data-toggle="tooltip" data-original-title="Número Operación">Número Op.</th>
                                                            <th style="color: #ff0000; background-color: #45c920;">
                                                                <b id="monto_total"></b> <br />
                                                                <b id="porcentaje" style="color: black;"></b>
                                                            </th>
                                                            <th>Vaucher</th>
                                                            <th>Estado</th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>

                                        <!--=======================
                                            Pagos con Detracción
                                        ========================-->
                                        <div id="pagos_con_detraccion" style="display: none;">
                                            <h5>pagos con detracccion</h5>
                                            <div style="text-align: center;">
                                                <div>
                                                    <h4>Total a pagar: <b id="ttl_monto_pgs_detracc"></b></h4>
                                                </div>
                                                <br />

                                                <div style="background-color: aliceblue;">
                                                    <h5>
                                                        Proveedor S/
                                                        <b id="t_proveedor"></b>
                                                        <input type="hidden" class="t_proveedor">
                                                        <i class="fas fa-arrow-right fa-xs"></i>
                                                        <b id="t_provee_porc"></b>
                                                        <b>%</b>
                                                    </h5>
                                                </div>
                                            </div>
                                            <!--tabla 1 t_proveedor, t_provee_porc,t_detaccion, t_detacc_porc -->
                                            <table id="tbl-pgs-detrac-prov-cmprs" class="table table-bordered table-striped display" style="width: 100% !important;">
                                                <thead>
                                                    <tr>
                                                        <th>Acciones</th>
                                                        <th>Forma pago</th>
                                                        <th>Beneficiario</th>
                                                        <th data-toggle="tooltip" data-original-title="Cuenta Destino">C. Destino</th>
                                                        <th>Banco</th>
                                                        <th data-toggle="tooltip" data-original-title="Titular Cuenta">Titular C.</th>
                                                        <th data-toggle="tooltip" data-original-title="Fecha Pago">Fecha P.</th>
                                                        <th>Descripción</th>
                                                        <th data-toggle="tooltip" data-original-title="Número Operación">Número Op.</th>
                                                        <th>Monto</th>
                                                        <th>Vaucher</th>
                                                        <th>Estado</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th>Aciones</th>
                                                        <th>Forma pago</th>
                                                        <th>Beneficiario</th>
                                                        <th data-toggle="tooltip" data-original-title="Cuenta Destino">C. Destino</th>
                                                        <th>Banco</th>
                                                        <th data-toggle="tooltip" data-original-title="Titular Cuenta">Titular C.</th>
                                                        <th data-toggle="tooltip" data-original-title="Fecha Pago">Fecha P.</th>
                                                        <th>Descripción</th>
                                                        <th data-toggle="tooltip" data-original-title="Número Operación">Número Op.</th>
                                                        <th style="color: #ff0000; background-color: #45c920;">
                                                            <b id="monto_total_prov"></b> <br />
                                                            <b id="porcnt_prove" style="color: black;"></b>
                                                        </th>
                                                        <th>Vaucher</th>
                                                        <th>Estado</th>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="8"></td>
                                                        <td style="font-weight: bold; font-size: 20px; text-align: center;">Saldo</td>
                                                        <th style="color: #ff0000; background-color: #f3e700;">
                                                            <b id="saldo_p"></b> <br />
                                                            <b id="porcnt_sald_p" style="color: black;"></b>
                                                        </th>
                                                        <td colspan="2"></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            <!--Tabla 2-->
                                            <br />
                                            <div style="text-align: center;">
                                                <div style="background-color: aliceblue;">
                                                    <h5>
                                                        Detracción S/
                                                        <b id="t_detaccion"></b>
                                                        <input type="hidden" class="t_detaccion">
                                                        <i class="fas fa-arrow-right fa-xs"></i>
                                                        <b id="t_detacc_porc"></b>
                                                        <b>%</b>
                                                    </h5>
                                                </div>
                                            </div>
                                            <table id="tbl-pgs-detrac-detracc-cmprs" class="table table-bordered table-striped display" style="width: 100% !important;">
                                                <thead>
                                                    <tr>
                                                        <th>Acciones</th>
                                                        <th>Forma pago</th>
                                                        <th>Beneficiario</th>
                                                        <th data-toggle="tooltip" data-original-title="Cuenta Destino">C. Destino</th>
                                                        <th>Banco</th>
                                                        <th data-toggle="tooltip" data-original-title="Titular Cuenta">Titular C.</th>
                                                        <th data-toggle="tooltip" data-original-title="Fecha Pago">Fecha P.</th>
                                                        <th>Descripción</th>
                                                        <th data-toggle="tooltip" data-original-title="Número Operación">Número Op.</th>
                                                        <th>Monto</th>
                                                        <th>Vaucher</th>
                                                        <th>Estado</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th>Aciones</th>
                                                        <th>Forma pago</th>
                                                        <th>Beneficiario</th>
                                                        <th data-toggle="tooltip" data-original-title="Cuenta Destino">C. Destino</th>
                                                        <th>Banco</th>
                                                        <th data-toggle="tooltip" data-original-title="Titular Cuenta">Titular C.</th>
                                                        <th data-toggle="tooltip" data-original-title="Fecha Pago">Fecha P.</th>
                                                        <th>Descripción</th>
                                                        <th data-toggle="tooltip" data-original-title="Número Operación">Número Op.</th>
                                                        <th style="color: #ff0000; background-color: #45c920;">
                                                            <b id="monto_total_detracc"></b> <br />
                                                            <b id="porcnt_detrcc" style="color: black;"></b>
                                                        </th>
                                                        <th>Vaucher</th>
                                                        <th>Estado</th>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="8"></td>
                                                        <td style="font-weight: bold; font-size: 20px; text-align: center;">Saldo</td>
                                                        <th style="color: #ff0000; background-color: #f3e700;">
                                                            <b id="saldo_d"></b> <br />
                                                           <!-- <input type="hidden" class="saldo_d">-->
                                                            <b id="porcnt_sald_d" style="color: black;"></b>
                                                        </th>
                                                        <td colspan="2"></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>

                                        <!-- /.card-body -->
                                    </div>
                                    <!-- /.card -->
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.container-fluid -->
                        <!-- Modal agregar proveedores -->
                        <div class="modal fade" id="modal-agregar-proveedor">
                            <div class="modal-dialog modal-dialog-scrollable modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Agregar proveedor</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span class="text-danger" aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <!-- form start -->
                                        <form id="form-proveedor" name="form-proveedor" method="POST">
                                            <div class="card-body">
                                                <div class="row" id="cargando-1-fomulario">
                                                    <!-- id proyecto -->
                                                    <input type="hidden" name="idproyecto" id="idproyecto" />
                                                    <!-- id proveedores -->
                                                    <input type="hidden" name="idproveedor" id="idproveedor" />

                                                    <!-- Tipo de documento -->
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label for="tipo_documento">Tipo de documento</label>
                                                            <select name="tipo_documento" id="tipo_documento" class="form-control" placeholder="Tipo de documento">
                                                                <option value="RUC">RUC</option>
                                                                <option selected value="DNI">DNI</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <!-- N° de documento -->
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label for="num_documento">N° RUC / DNI</label>
                                                            <div class="input-group">
                                                                <input type="number" name="num_documento" class="form-control" id="num_documento" placeholder="N° de documento" />
                                                                <div class="input-group-append" data-toggle="tooltip" data-original-title="Buscar Reniec/SUNAT" onclick="buscar_sunat_reniec();">
                                                                    <span class="input-group-text" style="cursor: pointer;">
                                                                        <i class="fas fa-search text-primary" id="search"></i>
                                                                        <i class="fa fa-spinner fa-pulse fa-fw fa-lg text-primary" id="charge" style="display: none;"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Nombre -->
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label for="nombre">Razón Social / Nombre y Apellidos</label>
                                                            <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Razón Social o  Nombre" />
                                                        </div>
                                                    </div>
                                                    <!-- Direccion -->
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label for="direccion">Dirección</label>
                                                            <input type="text" name="direccion" class="form-control" id="direccion" placeholder="Dirección" />
                                                        </div>
                                                    </div>
                                                    <!-- Telefono -->
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label for="telefono">Teléfono</label>
                                                            <input type="text" name="telefono" id="telefono" class="form-control" data-inputmask="'mask': ['999-999-999', '+099 99 99 999']" data-mask />
                                                        </div>
                                                    </div>
                                                    <!-- Cuenta bancaria -->
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label for="c_bancaria">Cuenta Bancaria</label>
                                                            <input type="number" name="c_bancaria" class="form-control" id="c_bancaria" placeholder="Cuenta Bancaria" />
                                                        </div>
                                                    </div>
                                                    <!-- fecha de nacimiento -->
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label for="fecha_nacimiento">Cuenta Detracciones</label>
                                                            <input type="number" name="c_detracciones" class="form-control" id="c_detracciones" placeholder="Cuenta Bancaria" />
                                                        </div>
                                                    </div>
                                                    <!-- banco -->
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label for="banco">Banco</label>
                                                            <select name="banco" id="banco" class="form-control select2" style="width: 100%;">
                                                                <option value="1">BCP</option>
                                                                <option value="2">BBVA</option>
                                                                <option value="3">SCOTIA BANK</option>
                                                                <option value="4">INTERBANK</option>
                                                                <option value="5">NACIÓN</option>
                                                            </select>
                                                            <small id="banco_validar" class="text-danger" style="display: none;">Por favor selecione un cargo</small>
                                                        </div>
                                                    </div>
                                                    <!-- Titular de la cuenta -->
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label for="titular_cuenta">Titular de la cuenta</label>
                                                            <input type="text" name="titular_cuenta" class="form-control" id="titular_cuenta" placeholder="Titular de la cuenta" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row" id="cargando-2-fomulario" style="display: none;">
                                                    <div class="col-lg-12 text-center">
                                                        <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                                                        <br />
                                                        <h4>Cargando...</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.card-body -->
                                            <button type="submit" style="display: none;" id="submit-form-proveedor">Submit</button>
                                        </form>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-success" id="guardar_registro_proveedor">Guardar Cambios</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal elegir material -->
                        <div class="modal fade" id="modal-elegir-material">
                            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Seleccionar material</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span class="text-danger" aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body table-responsive">
                                        <table id="tblamateriales" class="table table-striped table-bordered table-condensed table-hover" style="width: 100% !important;">
                                            <thead>
                                                <th>Opciones</th>
                                                <th>Nombre</th>
                                                <th>Marca</th>
                                                <th>Precio U.</th>
                                                <th>Descripción</th>
                                                <th>Ficha técnica</th>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--===============Modal agregar factura =========-->
                        <div class="modal fade" id="modal-agregar-factura">
                            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Agregar Factura</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span class="text-danger" aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <!-- form start -->
                                        <form id="form-agregar-factura" name="form-agregar-factura" method="POST">
                                            <div class="card-body">
                                                <div class="row" id="cargando-1-fomulario">
                                                    <!-- id proyecto -->
                                                    <input type="hidden" name="idproyectof" id="idproyectof" />
                                                    <!-- id maquina -->
                                                    <input type="hidden" name="idcomp_proyecto" id="idcomp_proyecto" />
                                                    <!-- id idfacturacompra  -->
                                                    <input type="text" name="idfacturacompra" id="idfacturacompra" />

                                                    <!-- Código-->
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="codigo">Código </label>
                                                            <input type="text" name="codigo" id="codigo" class="form-control" placeholder="Código" />
                                                        </div>
                                                    </div>
                                                    <!-- Monto-->
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="monto_compra">Monto</label>
                                                            <input type="number" name="monto_compraa" id="monto_compraa" class="form-control" placeholder="Monto" onchange="igv_subtotal();" onkeyup="igv_subtotal();" />
                                                        </div>
                                                    </div>

                                                    <!-- Fecha Emisión -->
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="fecha_emision">Fecha Emisión</label>
                                                            <input class="form-control" type="date" id="fecha_emision" name="fecha_emision" />
                                                        </div>
                                                    </div>
                                                    <!-- Sub total -->
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label for="subtotal_compra">Sub total</label>
                                                            <input class="form-control" type="number" id="subtotal_compraa" name="subtotal_compraa" placeholder="Sub total" />
                                                        </div>
                                                    </div>
                                                    <!-- Fecha Emisión -->
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label for="igv_compraa">IGV</label>
                                                            <input class="form-control" type="number" id="igv_compraa" name="igv_compraa" placeholder="IGV" />
                                                        </div>
                                                    </div>
                                                    <!-- Nota-->
                                                    <div class="col-lg-6" style="display: none;">
                                                        <div class="form-group">
                                                            <label for="nota">Nota </label> <br />
                                                            <textarea name="nota" id="nota" class="form-control" rows="2"></textarea>
                                                        </div>
                                                    </div>
                                                    <!-- Descripcion-->
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <label for="descripcion_f">Descripción </label> <br />
                                                            <textarea name="descripcion_f" id="descripcion_f" class="form-control" rows="2"></textarea>
                                                        </div>
                                                    </div>
                                                    <!--Factura-->
                                                    <div class="col-md-6 col-lg-12">
                                                        <div class="col-lg-12 borde-arriba-naranja mt-2 mb-2"></div>
                                                        <label for="foto2">Factura en <b style="color: red;">(Imagen o PDF)</b></label> <br />
                                                        <div class="text-center">
                                                            <img
                                                                onerror="this.src='../dist/img/default/img_defecto2.png';"
                                                                src="../dist/img/default/img_defecto2.png"
                                                                class="img-thumbnail"
                                                                id="foto2_i"
                                                                style="cursor: pointer !important;"
                                                                width="auto"
                                                            />
                                                            <div id="ver_pdf"></div>
                                                        </div>
                                                        <input style="display: none;" type="file" name="foto2" id="foto2" accept="image/*, .pdf" />
                                                        <input type="text" name="foto2_actual" id="foto2_actual" />
                                                        <div class="text-center" id="foto2_nombre"><!-- aqui va el nombre de la FOTO --></div>
                                                    </div>
                                                </div>

                                                <div class="row" id="cargando-2-fomulario" style="display: none;">
                                                    <div class="col-lg-12 text-center">
                                                        <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                                                        <br />
                                                        <h4>Cargando...</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.card-body -->
                                            <button type="submit" style="display: none;" id="submit-form-factura">Submit</button>
                                        </form>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_factura()">Close</button>
                                        <button type="submit" class="btn btn-success" id="guardar_registro_factura">Guardar Cambios</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--===============Modal-ver-factura =========-->
                        <div class="modal fade" id="modal-ver-factura">
                            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header" style="background-color: #ce834926;">
                                        <h4 class="modal-title">Factura</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span class="text-danger" aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="class-style" style="text-align: center;">
                                            <a class="btn btn-warning btn-block" href="#" id="iddescargar" download="factura" style="padding: 0px 12px 0px 12px !important;" type="button"><i class="fas fa-download"></i></a>
                                            <br />
                                            <img onerror="this.src='../dist/img/default/img_defecto.png';" src="../dist/img/default/img_defecto.png" class="img-thumbnail" id="img-factura" style="cursor: pointer !important;" width="auto" />
                                            <div id="ver_fact_pdf" style="cursor: pointer !important;" width="auto"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--===============Modal agregar Pagos =========-->
                        <div class="modal fade" id="modal-agregar-pago">
                            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Agregar Pago</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span class="text-danger" aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <!-- form start -->
                                        <form id="form-servicios-pago" name="form-servicios-pago" method="POST">
                                            <div class="card-body">
                                                <div class="row" id="cargando-1-fomulario">
                                                    <!-- id proveedor -->
                                                    <input type="hidden" name="idproveedor_pago" id="idproveedor_pago" />
                                                    <!-- idcompras_proyecto -->
                                                    <input type="hidden" name="idcompra_proyecto_p" id="idcompra_proyecto_p" />
                                                    <!-- id compras -->
                                                    <input type="hidden" name="idpago_compras" id="idpago_compras" />
                                                    <!-- Beneficiario -->
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <label for="beneficiario_pago">Beneficiario</label>
                                                            <input class="form-control" type="hidden" id="beneficiario_pago" name="beneficiario_pago" />
                                                            <br />
                                                            <b id="h4_mostrar_beneficiario" style="font-size: 16px; color: red;"> Jheyfer Arevealo Velasco</b>
                                                        </div>
                                                    </div>
                                                    <!--Forma de pago -->
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="forma_pago">Forma Pago</label>
                                                            <select name="forma_pago" id="forma_pago" class="form-control select2" style="width: 100%;">
                                                                <option value="Transferencia">Transferencia</option>
                                                                <option value="Efectivo">Efectivo</option>
                                                                <option value="Crédito">Crédito</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <!--tipo de pago -->
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="tipo_pago">Tipo Pago</label>
                                                            <select name="tipo_pago" id="tipo_pago" class="form-control select2" style="width: 100%;" onchange="captura_op();">
                                                                <option value="Proveedor">Proveedor</option>
                                                                <option value="Detraccion">Detracción</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <!-- Cuenta de destino-->
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="cuenta_destino_pago">Cuenta destino </label>
                                                            <input type="number" name="cuenta_destino_pago" id="cuenta_destino_pago" class="form-control" onchange="captura_op();" placeholder="Cuenta destino" />
                                                        </div>
                                                    </div>
                                                    <!-- banco -->
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="banco_pago">Banco</label>
                                                            <select name="banco_pago" id="banco_pago" class="form-control select2" style="width: 100%;">
                                                                <option value="1">BCP</option>
                                                                <option value="2">BBVA</option>
                                                                <option value="3">SCOTIA BANK</option>
                                                                <option value="4">INTERBANK</option>
                                                                <option value="5">NACIÓN</option>
                                                            </select>
                                                            <!-- <small id="banco_validar" class="text-danger" style="display: none;">Por favor selecione un cargo</small>-->
                                                        </div>
                                                    </div>
                                                    <!-- Titular Cuenta-->
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="titular_cuenta_pago">Titular Cuenta </label>
                                                            <input type="text" name="titular_cuenta_pago" id="titular_cuenta_pago" class="form-control" placeholder="Titular Cuenta" />
                                                        </div>
                                                    </div>

                                                    <!-- Fecha Inicio-->
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="fecha_pago">Fecha Pago </label>
                                                            <input type="date" name="fecha_pago" id="fecha_pago" class="form-control" />
                                                        </div>
                                                    </div>
                                                    <!-- Monto-->
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="monto_pago">Monto </label>
                                                            <input
                                                                type="number"
                                                                step="0.01"
                                                                name="monto_pago"
                                                                id="monto_pago"
                                                                class="form-control"
                                                                placeholder="Ingrese monto"
                                                                onkeyup="validando_excedentes();"
                                                                onchange="validando_excedentes();"
                                                            />
                                                        </div>
                                                    </div>
                                                    <!-- Número de Operación-->
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="numero_op_pago">Número de operación </label>
                                                            <input type="number" name="numero_op_pago" id="numero_op_pago" class="form-control" placeholder="Número de operación" />
                                                        </div>
                                                    </div>
                                                    <!-- Descripcion-->
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <label for="descripcion_pago">Descripción </label> <br />
                                                            <textarea name="descripcion_pago" id="descripcion_pago" class="form-control" rows="2"></textarea>
                                                        </div>
                                                    </div>
                                                    <!--vaucher-->
                                                    <div class="col-md-6 col-lg-4">
                                                        <div class="col-lg-12 borde-arriba-naranja mt-2 mb-2"></div>
                                                        <label for="foto1">Voucher</label> <br />
                                                        <img
                                                            onerror="this.src='../dist/img/default/img_defecto.png';"
                                                            src="../dist/img/default/img_defecto.png"
                                                            class="img-thumbnail"
                                                            id="foto1_i"
                                                            style="cursor: pointer !important;"
                                                            width="auto"
                                                        />
                                                        <input style="display: none;" type="file" name="foto1" id="foto1" accept="image/*" />
                                                        <input type="hidden" name="foto1_actual" id="foto1_actual" />
                                                        <div class="text-center" id="foto1_nombre"><!-- aqui va el nombre de la FOTO --></div>
                                                    </div>
                                                </div>

                                                <div class="row" id="cargando-2-fomulario" style="display: none;">
                                                    <div class="col-lg-12 text-center">
                                                        <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                                                        <br />
                                                        <h4>Cargando...</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.card-body -->
                                            <button type="submit" style="display: none;" id="submit-form-pago">Submit</button>
                                        </form>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_c_pagos();">Close</button>
                                        <button type="submit" class="btn btn-success" id="guardar_registro_pago">Guardar Cambios</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--===============Modal Ver compras =========-->
                        <div class="modal fade" id="modal-ver-compras">
                            <div class="modal-dialog modal-dialog-scrollable modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Detalle Compra</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span class="text-danger" aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="row" id="cargando-1-fomulario">
                                            <!-- Tipo de Empresa -->
                                            <div class="col-lg-7">
                                                <div class="form-group">
                                                    <label for="idproveedor">Proveedor</label>

                                                    <h5 class="idproveedor" style="border: 1px solid #ced4da; border-radius: 0.25rem; padding: 0.375rem 0.75rem;"></h5>
                                                </div>
                                            </div>
                                            <!-- fecha -->
                                            <div class="col-lg-5">
                                                <div class="form-group">
                                                    <label for="fecha_compra">Fecha </label>
                                                    <input type="date" class="form-control fecha_compra" placeholder="Fecha" />
                                                </div>
                                            </div>
                                            <!-- Tipo de comprobante -->
                                            <div class="col-lg-4 content-t-comprob">
                                                <div class="form-group">
                                                    <label for="tipo_comprovante">Tipo Comprobante</label>
                                                    <h5 class="tipo_comprovante" style="border: 1px solid #ced4da; border-radius: 0.25rem; padding: 0.375rem 0.75rem;"></h5>
                                                </div>
                                            </div>
                                            <!-- serie_comprovante-->
                                            <div class="col-lg-2 content-comprob">
                                                <div class="form-group">
                                                    <label for="serie_comprovante">N° de Comprobante</label>
                                                    <input type="text" class="form-control serie_comprovante" placeholder="N° de Comprobante" />
                                                </div>
                                            </div>
                                            <!-- IGV-->
                                            <div class="col-lg-1 content-igv" style="display: none;">
                                                <div class="form-group">
                                                    <label for="igv">IGV</label>
                                                    <input type="text" class="form-control igv" readonly value="0.18" />
                                                </div>
                                            </div>
                                            <!-- Descripcion-->
                                            <div class="col-lg-5 content-descrp">
                                                <div class="form-group">
                                                    <label for="descripcion">Descripción </label> <br />
                                                    <textarea class="form-control descripcion" rows="1"></textarea>
                                                </div>
                                            </div>
                                            <!--tabla detalles plantas-->
                                            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
                                                <br />
                                                <table id="detalles_compra" class="table table-striped table-bordered table-condensed table-hover">
                                                    <thead style="background-color: #ff6c046b;">
                                                        <th>Opciones</th>
                                                        <th>Material</th>
                                                        <th>Cantidad</th>
                                                        <th>Precio Compra</th>
                                                        <th>Descuento</th>
                                                        <th>Subtotal</th>
                                                    </thead>
                                                    <tfoot>
                                                        <td colspan="4"></td>
                                                        <th class="text-center">
                                                            <h5>Subtotal</h5>
                                                            <h5>IGV</h5>
                                                            <h5>TOTAL</h5>
                                                        </th>
                                                        <!--idproveedor,fecha_compra,tipo_comprovante,serie_comprovante,igv,descripcion, igv_comp, total-->
                                                        <th>
                                                            <h5 class="text-right subtotal" style="font-weight: bold;">S/. 0.00</h5>
                                                            <h5 class="text-right igv_comp" style="font-weight: bold;">S/. 0.00</h5>
                                                            <b>
                                                                <h4 class="text-right total" style="font-weight: bold;">S/. 0.00</h4>
                                                            </b>
                                                        </th>
                                                    </tfoot>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                            <div class="row" id="cargando-2-fomulario" style="display: none;">
                                                <div class="col-lg-12 text-center">
                                                    <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                                                    <br />
                                                    <h4>Cargando...</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--===============Modal agregar comprobantes =========-->
                        <!-- Modal agregar Comprobante -->
                        <div class="modal fade" id="modal-comprobantes-pago">
                            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Agregar Documentos</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span class="text-danger" aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <!-- form start -->
                                        <form id="form-plano-otro" name="form-plano-otro" method="POST">
                                            <div class="card-body">
                                                <div class="row" id="cargando-3-fomulario">
                                                    <!-- id Comprobante -->
                                                    <input type="hidden" name="comprobante_c" id="comprobante_c" />

                                                    <!-- Doc  -->
                                                    <div class="col-md-12 col-lg-12">
                                                        <div class="row text-center">
                                                            <div class="col-md-12" style="padding-top: 15px; padding-bottom: 5px;">
                                                                <label for="cip" class="control-label">Documento </label>
                                                            </div>
                                                            <div class="col-md-6 text-center subir">
                                                                <button type="button" class="btn btn-success btn-block btn-xs" id="doc1_i"><i class="fas fa-file-upload"></i> Subir.</button>
                                                                <input type="hidden" id="doc_old_1" name="doc_old_1" />
                                                                <input style="display: none;" id="doc1" type="file" name="doc1" class="docpdf" />
                                                            </div>
                                                            <div class="col-md-6 text-center comprobante">
                                                                <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion();"><i class="fa fa-eye"></i> Comprobante.</button>
                                                            </div>
                                                            <div class="col-md-4 text-center descargar" style="display: none;">
                                                                <a type="button" class="btn-xs btn btn-warning btn-block" id="descargar_comprob" style="padding: 0px 12px 0px 12px !important;" download="Comprobantes">
                                                                    <i class="fas fa-download"></i> Descargar.
                                                                </a>
                                                            </div>
                                                            <div class="col-md-4 text-center ver_completo" style="display: none;">
                                                                <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" id="ver_completo" style="padding: 0px 12px 0px 12px !important;">
                                                                    <i class="fas fa-expand"></i> Ver completo.
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div id="doc1_ver" class="text-center mt-4">
                                                            <img src="../dist/svg/doc_uploads.svg" alt="" width="50%" />
                                                        </div>
                                                        <div class="text-center" id="doc1_nombre"><!-- aqui va el nombre del pdf --></div>
                                                    </div>

                                                    <!-- ver_completo descargar comprobante subir -->
                                                    <!-- barprogress -->
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 20px;">
                                                        <div class="progress" id="div_barra_progress2">
                                                            <div
                                                                id="barra_progress2"
                                                                class="progress-bar progress-bar-striped progress-bar-animated"
                                                                role="progressbar"
                                                                aria-valuenow="2"
                                                                aria-valuemin="0"
                                                                aria-valuemax="100"
                                                                style="min-width: 2em; width: 0%;"
                                                            >
                                                                0%
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row" id="cargando-4-fomulario" style="display: none;">
                                                    <div class="col-lg-12 text-center">
                                                        <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                                                        <br />
                                                        <h4>Cargando...</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.card-body -->
                                            <button type="submit" style="display: none;" id="submit-form-planootro">Submit</button>
                                        </form>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-success" id="guardar_registro_2">Guardar Cambios</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal ver los documentos subidos -->
                        <div class="modal fade" id="modal-ver-docs">
                            <div class="modal-dialog modal-dialog-scrollable modal-md">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Documentos subidos</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span class="text-danger" aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="row">
                                            <!-- Pdf 1 -->
                                            <div class="col-md-12 col-lg-12 mb-4">
                                                <div class="text-center mb-4" id="verdoc1_nombre">
                                                    <!-- aqui va el nombre del pdf -->
                                                </div>

                                                <div id="verdoc1" class="text-center">
                                                    <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                                                    <br />
                                                    <h4>Cargando...</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer justify-content-end">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--===============Modal-ver-vaucher-pagos =========-->
                        <div class="modal fade" id="modal-ver-vaucher">
                            <div class="modal-dialog modal-dialog-scrollable modal-xm">
                                <div class="modal-content">
                                    <div class="modal-header" style="background-color: #ce834926;" >
                                        <h4 class="modal-title">voucher</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span class="text-danger" aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div id="datosservicios" class="class-style" style="text-align: center;"> 
                                        <a class="btn btn-warning  btn-block" href="#" id="descargar" download="Voucher" style="padding:0px 12px 0px 12px !important;" type="button"><i class="fas fa-download"></i></a>
                                            <br>
                                            <img onerror="this.src='../dist/img/default/img_defecto.png';" src="../dist/img/default/img_defecto.png" class="img-thumbnail" id="img-vaucher" style="cursor: pointer !important;" width="auto" />
                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- /.content -->
            </div>
            <!--Fin-Contenido-->
            <?php
            }else{
              require 'noacceso.php';
            }
            require 'footer.php';
          ?>
        </div>
        <?php          
          require 'script.php';
        ?>

        <!--<script type="text/javascript" src="scripts/all_proveedor.js"></script>-->
        <script type="text/javascript" src="scripts/compra.js"></script>
        <script>
            $(function () {
                $('[data-toggle="tooltip"]').tooltip();
            });
        </script>

        <script>
            if (localStorage.getItem("nube_idproyecto")) {
                console.log("icon_folder_" + localStorage.getItem("nube_idproyecto"));

                $("#ver-proyecto").html('<i class="fas fa-tools"></i> Proyecto: ' + localStorage.getItem("nube_nombre_proyecto"));

                $("#ver-otros-modulos-1").show();

                // $('#icon_folder_'+localStorage.getItem('nube_idproyecto')).html('<i class="fas fa-folder-open"></i>');
            } else {
                $("#ver-proyecto").html('<i class="fas fa-tools"></i> Selecciona un proyecto');

                $("#ver-otros-modulos-1").hide();
            }
        </script>
    </body>
</html>
<?php    
  }
  ob_end_flush();
?>
