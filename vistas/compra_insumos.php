<?php
  //Activamos el almacenamiento en el buffer
  ob_start();
  session_start();

  if (!isset($_SESSION["nombre"])){

    header("Location: index.php?file=".basename($_SERVER['PHP_SELF']));

  }else{ ?>

    <!DOCTYPE html> 
    <html lang="es">
      <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title> Compras | Admin Sevens </title>

        <?php $title = "Compras"; require 'head.php'; ?>

        <!--CSS  switch_MATERIALES-->
        <link rel="stylesheet" href="../dist/css/switch_materiales.css" />
        <link rel="stylesheet" href="../dist/css/leyenda.css" />
        
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
          if ($_SESSION['compra_insumos']==1){
            //require 'enmantenimiento.php';
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
                        <li class="breadcrumb-item"><a href="compra_insumos.php">Home</a></li>
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
                          <div class="container-fluid border-bottom">
                            <div class="row">
                              <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"> 
                                <div class="card-header">
                                  <h3 class="card-title">
                                    <!--data-toggle="modal" data-target="#modal-agregar-compra"  onclick="limpiar();"-->
                                    <button type="button" class="btn bg-gradient-success" id="btn_agregar" onclick="ver_form_add(); limpiar_form_compra();">
                                      <i class="fas fa-plus-circle"></i> Agregar
                                    </button>                                    
                                    <button type="button" class="btn bg-gradient-warning" id="regresar" style="display: none;" onclick="regresar();">
                                      <i class="fas fa-arrow-left"></i> Regresar
                                    </button>
                                    <button type="button" id="btn-pagar" class="btn bg-gradient-success" style="display: none;" data-toggle="modal"  data-target="#modal-agregar-pago" onclick="limpiar_form_pago_compra();">
                                      <i class="fas fa-dollar-sign"></i> Agregar Pago
                                    </button>                                     
                                  </h3>
                                </div>
                              </div>
                              <!-- Leyecnda pagos -->
                              <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 hiddenn leyecnda_pagos" style="background-color: aliceblue;">
                                <div class="text-slid-box">
                                  <div id="offer-box" class="contenedor">
                                    <div> <b>Leyenda-pago</b> </div>
                                    <ul class="offer-box cls-ul">
                                      <li>
                                        <span class="text-center badge badge-danger" >Pago sin iniciar </span> 
                                      </li>
                                      <li>
                                        <span class="text-center badge badge-warning" >Pago en proceso </span>
                                      </li>
                                      <li>
                                        <span class="text-center badge badge-success" >Pago completo</span>
                                      </li>
                                    </ul>
                                  </div>
                                </div>
                              </div>
                              <!-- Leyecnda saldos -->
                              <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 hiddenn leyecnda_saldos" style="background-color: #f0f8ff7d;">
                                <div class="text-slid-box">
                                  <div id="offer-box" class="contenedorr">
                                    <div> <b>Leyenda-saldos</b> </div>
                                    <ul class="offer-box clss-ul">
                                      <li>
                                        <span class="text-center badge badge-warning " >Pago nulo o pago en proceso </span> 
                                      </li>
                                      <li>
                                        <span class="text-center badge badge-success" >Pago Completo </span>
                                      </li>
                                      <li>
                                        <span class="text-center badge badge-danger" >Pago excedido</span>
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
                          <!-- TABLA - COMPRAS -->
                          <div id="div_tabla_compra">
                            <h5><b>Lista de compras Por Facturas</b></h5>
                            <!-- filtros -->
                            <div class="filtros-inputs row mb-4">

                              <!-- filtro por: fecha inicial -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-2">    
                                <div class="form-group">
                                  <!-- <label for="filtro_fecha_inicio" >Fecha inicio </label> -->
                                  <div class="input-group date"  >
                                    <div class="input-group-append cursor-pointer click-btn-fecha-inicio" >
                                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                    <input type="text" class="form-control"  id="filtro_fecha_inicio" onchange="cargando_search(); delay(function(){filtros()}, 50 );" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask autocomplete="off" />                                    
                                  </div>
                                </div>                                
                              </div>

                              <!-- filtro por: fecha final -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-2">                                
                                <div class="form-group">
                                  <!-- <label for="filtro_fecha_inicio" >Fecha fin </label> -->
                                  <div class="input-group date"  >
                                    <div class="input-group-append cursor-pointer click-btn-fecha-fin" >
                                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                    <input type="text" class="form-control"  id="filtro_fecha_fin" onchange="cargando_search(); delay(function(){filtros()}, 50 );" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask autocomplete="off" />                                    
                                  </div>
                                </div> 
                              </div>

                              <!-- filtro por: proveedor -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                  <!-- <label for="filtros" class="cargando_proveedor">Proveedor &nbsp;<i class="text-dark fas fa-spinner fa-pulse fa-lg"></i><br /></label> -->
                                  <select id="filtro_proveedor" class="form-control select2" onchange="cargando_search(); delay(function(){filtros()}, 50 );" style="width: 100%;"> 
                                  </select>
                                </div>
                                
                              </div>

                              <!-- filtro por: proveedor -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-2">
                                <div class="form-group">
                                  <!-- <label for="filtros" >Tipo comprobante </label> -->
                                  <select id="filtro_tipo_comprobante" class="form-control select2" onchange="cargando_search(); delay(function(){filtros()}, 50 );" style="width: 100%;"> 
                                    <option value="0">Todos</option>
                                    <option value="Ninguno">Ninguno</option>
                                    <option value="Boleta">Boleta</option>
                                    <option value="Factura">Factura</option>
                                    <option value="Nota de venta">Nota de venta</option>
                                  </select>
                                </div>
                                
                              </div>
                            </div>
                            <!-- /.filtro -->
                            
                            <table id="tabla-compra" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th colspan="13" class="cargando text-center bg-danger"><i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando... </th>
                                </tr>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">Acciones</th>
                                  <th>Fecha</th>
                                  <th>Proveedor</th>
                                  <th data-toggle="tooltip" data-original-title="Tipo y Número Comprobante">Tipo</th>
                                  <th data-toggle="tooltip" data-original-title="Detraccion">Detrac.</th>
                                  <th>Total</th>
                                  <th>Añadir pago</th>
                                  <th>Deposito</th>
                                  <th>Saldo</th>
                                  <th data-toggle="tooltip" data-original-title="Comprobantes">CFDI.</th>
                                  <th>Descripción</th>
                                  <th> <button class="btn btn-info btn-sm btn-descarga-multiple" onclick="download_multimple();" data-toggle="tooltip" data-original-title="Descarga múltiple" ><i class="fas fa-cloud-download-alt"></i></button> </th>
                                </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">Acciones</th>
                                  <th>Fecha</th>
                                  <th>Proveedor</th>
                                  <th data-toggle="tooltip" data-original-title="Tipo y Número Comprobante">Tipo</th>
                                  <th>Detrac.</th>
                                  <th>Total</th>
                                  <th>Añadir pago</th>
                                  <th>Deposito</th>
                                  <th>Saldo</th>
                                  <th>CFDI.</th>
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
                                  <th class="">#</th>
                                  <th class="">Acciones</th>
                                  <th>Proveedor</th>
                                  <th>Cant</th>
                                  <th>Cel.</th>
                                  <th>Total</th>
                                </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">Acciones</th> 
                                  <th>Proveedor</th>
                                  <th>Cant</th>
                                  <th>Cel.</th>
                                  <th>Total</th>
                                </tr>
                              </tfoot>
                            </table>
                          </div>

                          <!-- TABLA - COMPRAS POR PROVEEDOR -->
                          <div id="div_tabla_compra_proveedor" style="display: none;">
                            <h5><b>Lista de compras Por Facturas</b></h5>
                            <table id="detalles-tabla-compra-prov" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th class="">#</th>
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
                                  <th class="">#</th>
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

                          <!-- TABLA - AGREGAR COMPRA-->
                          <div id="agregar_compras" style="display: none;">
                            <div class="modal-body p-0px mb-2">
                              <!-- form start -->
                              <form id="form-compras" name="form-compras" method="POST">
                                 
                                <div class="row" id="cargando-1-fomulario">
                                  <!-- id proyecto -->
                                  <input type="hidden" name="idproyecto" id="idproyecto" />
                                  <input type="hidden" name="idcompra_proyecto" id="idcompra_proyecto" /> 

                                  <!-- Tipo de Empresa -->
                                  <div class="col-lg-5">
                                    <div class="form-group">
                                      <label for="idproveedor">Proveedor <sup class="text-danger">(único*)</sup></label>
                                      <select id="idproveedor" name="idproveedor" class="form-control select2" data-live-search="true" required title="Seleccione cliente" onchange="extrae_ruc();"> </select>
                                    </div>
                                  </div>

                                  <!-- adduser -->
                                  <div class="col-lg-1">
                                    <div class="form-group">
                                    <label for="Add" class="d-none d-sm-inline-block text-break" style="color: white;">.</label> <br class="d-none d-sm-inline-block">
                                      <a data-toggle="modal" href="#modal-agregar-proveedor" >
                                        <button type="button" class="btn btn-success p-x-6px" data-toggle="tooltip" data-original-title="Agregar Provedor" onclick="limpiar_form_proveedor();">
                                          <i class="fa fa-user-plus" aria-hidden="true"></i>
                                        </button>
                                      </a>
                                      <button type="button" class="btn btn-warning p-x-6px btn-editar-proveedor" data-toggle="tooltip" data-original-title="Editar:" onclick="mostrar_para_editar_proveedor();">
                                        <i class="fa-solid fa-pencil" aria-hidden="true"></i>
                                      </button>
                                    </div>
                                  </div>

                                  <!-- fecha -->
                                  <div class="col-lg-3" >
                                    <div class="form-group">
                                      <label for="fecha_compra">Fecha <sup class="text-danger">*</sup></label>
                                      <input type="date" name="fecha_compra" id="fecha_compra" class="form-control" placeholder="Fecha" />
                                    </div>
                                  </div>

                                  <!-- Glosa -->
                                  <div class="col-lg-3">
                                    <div class="form-group">
                                      <label for="glosa">Glosa <sup class="text-danger">*</sup></label>
                                      <select id="glosa" name="glosa" class="form-control select2" data-live-search="true" required title="Seleccione glosa"> 
                                        <option title="fas fa-hammer" value="MATERIAL">MATERIAL</option>
                                        <option title="fas fa-gas-pump" value="COMBUSTIBLE">COMBUSTIBLE</option>
                                        <option title="fas fa-snowplow" value="EQUIPOS">EQUIPOS</option>
                                      </select>
                                    </div> 
                                  </div>

                                  <!-- Tipo de comprobante -->
                                  <div class="col-lg-4" id="content-tipo-comprobante">
                                    <div class="form-group">
                                      <label for="tipo_comprobante">Tipo Comprobante <sup class="text-danger">(único*)</sup></label>
                                      <select name="tipo_comprobante" id="tipo_comprobante" class="form-control select2"  onchange="default_val_igv(); modificarSubtotales(); ocultar_comprob();" placeholder="Seleccinar un tipo de comprobante">
                                        <option value="Ninguno">Ninguno</option>
                                        <option value="Boleta">Boleta</option>
                                        <option value="Factura">Factura</option>
                                        <option value="Nota de venta">Nota de venta</option>
                                      </select>
                                    </div>
                                  </div> 

                                  <!-- serie_comprobante-->
                                  <div class="col-lg-2" id="content-serie-comprobante">
                                    <div class="form-group">
                                      <label for="serie_comprobante">N° de Comprobante <sup class="text-danger">(único*)</sup></label>
                                      <input type="text" name="serie_comprobante" id="serie_comprobante" class="form-control" placeholder="N° de Comprobante" />
                                    </div>
                                  </div>

                                  <!-- IGV-->
                                  <div class="col-lg-1" id="content-igv">
                                    <div class="form-group">
                                      <label for="val_igv">IGV <sup class="text-danger">*</sup></label>
                                      <input type="text" name="val_igv" id="val_igv" class="form-control" value="0.18" onkeyup="modificarSubtotales();" />
                                    </div>
                                  </div>

                                  <!-- Descripcion-->
                                  <div class="col-lg-5" id="content-descripcion">
                                    <div class="form-group">
                                      <label for="descripcion">Descripción </label> <br />
                                      <textarea name="descripcion" id="descripcion" class="form-control" rows="1"></textarea>
                                    </div>
                                  </div>                                  

                                  <!--Boton agregar material-->
                                  <div class="row col-lg-12 justify-content-between">
                                    <div class="col-lg-4 col-xs-12">
                                      <div class="row">
                                        <div class="col-lg-6">
                                            <label for="" style="color: white;">.</label> <br />
                                            <a data-toggle="modal" data-target="#modal-elegir-material">
                                              <button id="btnAgregarArt" type="button" class="btn btn-primary btn-block"><span class="fa fa-plus"></span> Agregar Productos</button>
                                            </a>
                                        </div>
                                        <div class="col-lg-6">
                                          <!-- <label for="" style="color: white;">.</label> <br />
                                          <a data-toggle="modal" data-target="#modal-agregar-material-activos-fijos">
                                            <button id="btnAgregarArt" type="button" class="btn btn-success btn-block" onclick="limpiar_materiales()"><span class="fa fa-plus"></span> Crear Productos</button>
                                          </a> -->
                                        </div>
                                      </div>
                                    </div>

                                    <!-- Rounded switch -->
                                    <div class="col-lg-1 col-xs-3">
                                      <div class="form-group">
                                        <div id="switch_detracc">
                                          <label for="" style="font-size: 13px;" >Detracción ?</label> <br />
                                          <div class="myestilo-switch2" >
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
                                  <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive row-horizon disenio-scroll">
                                    <br />
                                    <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                                      <thead style="background-color: #ff6c046b;">
                                        <th data-toggle="tooltip" data-original-title="Opciones">Op.</th>
                                        <th>Material</th>
                                        <th>Unidad</th>
                                        <th>Cantidad</th>
                                        <th class="hidden" data-toggle="tooltip" data-original-title="Valor Unitario" >V/U</th>
                                        <th class="hidden">IGV</th>
                                        <th data-toggle="tooltip" data-original-title="Precio Unitario">P/U</th>
                                        <th>Descuento</th>
                                        <th>Subtotal</th>
                                      </thead>
                                      <tfoot>
                                        <td colspan="5" id="colspan_subtotal"></td>
                                        <th class="text-right">
                                          <h6 class="tipo_gravada">GRAVADA</h6>
                                          <h6 class="val_igv">IGV (18%)</h6>
                                          <h5 class="font-weight-bold">TOTAL</h5>
                                        </th>
                                        <th class="text-right"> 
                                          <h6 class="font-weight-bold subtotal_compra">S/ 0.00</h6>
                                          <input type="hidden" name="subtotal_compra" id="subtotal_compra" />
                                          <input type="hidden" name="tipo_gravada" id="tipo_gravada" />

                                          <h6 class="font-weight-bold igv_compra">S/ 0.00</h6>
                                          <input type="hidden" name="igv_compra" id="igv_compra" />
                                          
                                          <h5 class="font-weight-bold total_venta">S/ 0.00</h5>
                                          <input type="hidden" name="total_venta" id="total_venta" />
                                          
                                        </th>
                                      </tfoot>
                                      <tbody></tbody>
                                    </table>
                                  </div>                                    
                                </div>

                                <div class="row" id="cargando-2-fomulario" style="display: none;">
                                  <div class="col-lg-12 text-center">
                                    <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                                    <br />
                                    <h4>Cargando...</h4>
                                  </div>
                                </div>                                 
                                 
                                <button type="submit" style="display: none;" id="submit-form-compras">Submit</button>
                              </form>
                            </div>

                            <div class="modal-footer justify-content-between pl-0 pb-0 ">
                              <button type="button" class="btn btn-danger" onclick="regresar();" data-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-success" style="display: none;" id="guardar_registro_compras">Guardar Cambios</button>
                            </div>
                          </div>

                          <!-- TABLA - FACTURAS COMPRAS-->
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

                          <!-- TABLA - PAGOS SIN DETRACCION -->
                          <div id="pago_compras" style="display: none;">
                            <h5>pago Compras</h5>
                            <div style="text-align: center;">
                              <div>
                                <h4>Total a pagar: <b id="total_compra"></b></h4>
                              </div>
                              <table id="tabla-pagos-proveedor" class="table table-bordered table-striped display" style="width: 100% !important;">
                                <thead>
                                  <tr>
                                    <th>#</th>
                                    <th>Acciones</th>
                                    <th data-toggle="tooltip" data-original-title="Forma Pago">Forma</th>
                                    <th>Beneficiario</th>
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
                                    <th>#</th>
                                    <th>Aciones</th>
                                    <th>Forma</th>
                                    <th>Beneficiario</th>                                     
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

                          <!-- TABLA - PAGOS CON DETRACCION-->
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
                                  <input type="hidden" class="t_proveedor" />
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
                                  <th>#</th>
                                  <th>Acciones</th>
                                  <th>Forma pago</th>
                                  <th>Beneficiario</th>
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
                                  <th>#</th>
                                  <th>Aciones</th>
                                  <th>Forma pago</th>
                                  <th>Beneficiario</th>
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
                                  <td colspan="6"></td>
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
                                  <input type="hidden" class="t_detaccion" />
                                  <i class="fas fa-arrow-right fa-xs"></i>
                                  <b id="t_detacc_porc"></b>
                                  <b>%</b>
                                </h5>
                              </div>
                            </div>
                            <table id="tbl-pgs-detrac-detracc-cmprs" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th>#</th>
                                  <th>Acciones</th>
                                  <th>Forma pago</th>
                                  <th>Beneficiario</th> 
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
                                  <th>#</th>
                                  <th>Aciones</th>
                                  <th>Forma pago</th>
                                  <th>Beneficiario</th> 
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
                                  <td colspan="6"></td>
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
                            <div class="card-body ">  
                              <div class="row" id="cargando-11-fomulario">  
                                <!-- id proveedores -->
                                <input type="hidden" name="idproveedor_prov" id="idproveedor_prov" />

                                <!-- Tipo de documento -->
                                <div class="col-lg-4">
                                  <div class="form-group">
                                    <label for="tipo_documento_prov">Tipo de documento <sup class="text-danger">*</sup></label>
                                    <select name="tipo_documento_prov" id="tipo_documento_prov" class="form-control" placeholder="Tipo de documento">
                                      <option value="RUC">RUC</option>
                                      <option selected value="DNI">DNI</option>
                                    </select>
                                  </div>
                                </div>

                                <!-- N° de documento -->
                                <div class="col-lg-4">
                                  <div class="form-group">
                                    <label for="num_documento_prov">N° RUC / DNI <sup class="text-danger">(unico*)</sup></label>
                                    <div class="input-group">
                                      <input type="number" name="num_documento_prov" class="form-control" id="num_documento_prov" placeholder="N° de documento" />
                                      <div class="input-group-append" data-toggle="tooltip" data-original-title="Buscar Reniec/SUNAT" onclick="buscar_sunat_reniec('_prov');">
                                        <span class="input-group-text" style="cursor: pointer;">
                                          <i class="fas fa-search text-primary" id="search_prov"></i>
                                          <i class="fa fa-spinner fa-pulse fa-fw fa-lg text-primary" id="charge_prov" style="display: none;"></i>
                                        </span>
                                      </div>
                                    </div>
                                  </div>
                                </div>

                                <!-- Nombre -->
                                <div class="col-lg-4">
                                  <div class="form-group">
                                    <label for="nombre_prov">Razón Social / Nombre y Apellidos <sup class="text-danger">*</sup></label>
                                    <input type="text" name="nombre_prov" class="form-control" id="nombre_prov" placeholder="Razón Social o  Nombre" />
                                  </div>
                                </div>

                                <!-- Direccion -->
                                <div class="col-lg-4">
                                  <div class="form-group">
                                    <label for="direccion_prov">Dirección</label>
                                    <input type="text" name="direccion_prov" class="form-control" id="direccion_prov" placeholder="Dirección" />
                                  </div>
                                </div>

                                <!-- Telefono -->
                                <div class="col-lg-4">
                                  <div class="form-group">
                                    <label for="telefono_prov">Teléfono</label>
                                    <input type="text" name="telefono_prov" id="telefono_prov" class="form-control" data-inputmask="'mask': ['999-999-999', '+099 99 99 999']" data-mask />
                                  </div>
                                </div>

                                <!-- Titular de la cuenta -->
                                <div class="col-lg-4">
                                  <div class="form-group">
                                    <label for="titular_cuenta_prov">Titular de la cuenta</label>
                                    <input type="text" name="titular_cuenta_prov" class="form-control" id="titular_cuenta_prov" placeholder="Titular de la cuenta" />
                                  </div>
                                </div>

                                <!-- banco -->
                                <div class="col-lg-3">
                                  <div class="form-group">
                                    <label for="banco_prov">Banco <sup class="text-danger">*</sup></label>
                                    <select name="banco_prov" id="banco_prov" class="form-control select2" style="width: 100%;" onchange="formato_banco();">
                                      <!-- Aqui listamos los bancos -->
                                    </select>
                                    <!-- <small id="banco_validar" class="text-danger" style="display: none;">Por favor selecione un cargo</small> -->
                                  </div>
                                </div>

                                <!-- Cuenta bancaria -->
                                <div class="col-lg-3">
                                  <div class="form-group">
                                    <label for="c_bancaria_prov" class="chargue-format-1">Cuenta Bancaria</label>
                                    <input type="text" name="c_bancaria_prov" class="form-control" id="c_bancaria_prov" placeholder="Cuenta Bancaria" data-inputmask="" data-mask />
                                  </div>
                                </div>

                                <!-- CCI -->
                                <div class="col-lg-3">
                                  <div class="form-group">
                                    <label for="cci_prov" class="chargue-format-2">CCI</label>
                                    <input type="text" name="cci_prov" class="form-control" id="cci_prov" placeholder="CCI" data-inputmask="" data-mask />
                                  </div>
                                </div>

                                <!-- Cuenta de detracciones -->
                                <div class="col-lg-3">
                                  <div class="form-group">
                                    <label for="c_detracciones_prov" class="chargue-format-3">Cuenta Detracciones</label>
                                    <input type="text" name="c_detracciones_prov" class="form-control" id="c_detracciones_prov" placeholder="Cuenta Detracciones" data-inputmask="" data-mask />
                                  </div>
                                </div>  
                                
                                <!-- barprogress -->
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                                  <div class="progress" id="barra_progress_proveedor_div">
                                    <div id="barra_progress_proveedor" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                      0%
                                    </div>
                                  </div>
                                </div>                     
                              </div>
                              <div class="row" id="cargando-12-fomulario" style="display: none;">
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
                          <h4 class="modal-title "> 
                            <a data-toggle="modal" data-target="#modal-agregar-material-activos-fijos">
                              <button id="btnAgregarArt" type="button" class="btn btn-success" onclick="limpiar_materiales()"><span class="fa fa-plus"></span> Crear Productos</button>
                            </a>
                            Seleccionar producto
                          </h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body table-responsive">
                          <table id="tblamateriales" class="table table-striped table-bordered table-condensed table-hover" style="width: 100% !important;">
                            <thead>
                              <th data-toggle="tooltip" data-original-title="Opciones">Op.</th>
                              <th>Nombre Producto</th>
                              <th>Clasificación</th>
                              <th data-toggle="tooltip" data-original-title="Precio Unitario">P/U.</th>
                              <th>Descripción</th>
                              <th data-toggle="tooltip" data-original-title="Ficha Técnica" >F.T.</th>
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

                  <!-- Modal agregar Pagos - charge -->
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
                          <form id="form-pago-compra" name="form-pago-compra" method="POST">
                             
                            <div class="row" id="cargando-3-fomulario">
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
                                  <select name="forma_pago" id="forma_pago" class="form-control select2" style="width: 100%;" onchange="validar_forma_de_pago();">
                                    <option value="Transferencia">Transferencia</option>
                                    <option value="Efectivo">Efectivo</option>
                                    <option value="Crédito">Crédito</option>
                                  </select>
                                </div>
                              </div>
                              <!--tipo de pago -->
                              <div class="col-lg-6 validar_fp">
                                <div class="form-group">
                                  <label for="tipo_pago">Tipo Pago</label>
                                  <select name="tipo_pago" id="tipo_pago" class="form-control select2" style="width: 100%;" onchange="captura_op();">
                                    <option value="Proveedor">Proveedor</option>
                                    <option value="Detraccion">Detracción</option>
                                  </select>
                                </div>
                              </div>
                              <!-- Cuenta de destino-->
                              <div class="col-lg-6 validar_fp">
                                <div class="form-group">
                                  <label for="cuenta_destino_pago">Cuenta destino </label>
                                  <input type="text" name="cuenta_destino_pago" id="cuenta_destino_pago" class="form-control" placeholder="Cuenta destino" />
                                </div>
                              </div>
                              <!-- banco -->
                              <div class="col-lg-6 validar_fp">
                                <div class="form-group">
                                  <label for="banco_pago">Banco</label>
                                  <select name="banco_pago" id="banco_pago" class="form-control select2" style="width: 100%;">
                                  </select>
                                </div>
                              </div>
                              <!-- Titular Cuenta-->
                              <div class="col-lg-6 validar_fp">
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
                                  <input type="number" step="0.01" name="monto_pago" id="monto_pago" class="form-control" placeholder="Ingrese monto" onkeyup="validando_excedentes();" onchange="validando_excedentes();" />
                                </div>
                              </div>
                              <!-- Número de Operación-->
                              <div class="col-lg-6 validar_fp">
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
                              <div class="col-md-6 col-lg-6">
                                <div class="col-lg-12 borde-arriba-naranja mt-2 mb-2"></div>
                                <label for="doc3_i" >Comprobante <b class="text-danger">(Imagen o PDF)</b> </label>  
                                <div class="row text-center">                               
                                  <!-- Subir documento -->
                                  <div class="col-6 col-md-6 text-center">
                                    <button type="button" class="btn btn-success btn-block btn-xs" id="doc3_i">
                                      <i class="fas fa-upload"></i> Subir.
                                    </button>
                                    <input type="hidden" id="doc_old_3" name="doc_old_3" />
                                    <input style="display: none;" id="doc3" type="file" name="doc3" accept="application/pdf, image/*" class="docpdf" /> 
                                  </div>
                                  <!-- Recargar -->
                                  <div class="col-6 col-md-6 text-center comprobante">
                                    <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(3, 'compra' ,'comprobante_pago'); reload_zoom();">
                                    <i class="fas fa-redo"></i> Recargar.
                                  </button>
                                  </div>                                  
                                </div>
                                <div id="doc3_ver" class="text-center mt-4">
                                  <img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >
                                </div>
                                <div class="text-center" id="doc3_nombre"><!-- aqui va el nombre del pdf --></div>
                              </div>

                            </div>

                            <div class="row" id="cargando-4-fomulario" style="display: none;">
                              <div class="col-lg-12 text-center">
                                <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                                <br />
                                <h4>Cargando...</h4>
                              </div>
                            </div>
                             
                            <!-- /.card-body -->
                            <button type="submit" style="display: none;" id="submit-form-pago">Submit</button>
                          </form>
                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_form_pago_compra();">Close</button>
                          <button type="submit" class="btn btn-success" id="guardar_registro_pago">Guardar Cambios</button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- MODAL - DETALLE compras - charge -->
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
                          <div class="row detalle_de_compra" id="cargando-5-fomulario">                            
                            <!--detalle de la compra-->
                          </div>

                          <div class="row" id="cargando-6-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                              <br />
                              <h4>Cargando...</h4>
                            </div>
                          </div>

                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                          <button type="button" class="btn btn-success float-right" id="excel_compra" onclick="export_excel_detalle_factura()" ><i class="far fa-file-excel"></i> Excel</button>
                          <a type="button" class="btn btn-info" id="print_pdf_compra" target="_blank" ><i class="fas fa-print"></i> Imprimir/PDF</a>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- MODAL -  agregar comprobantes - charge -->
                  <div class="modal fade" id="modal-tabla-comprobantes-compra">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg">
                      <div class="modal-content">
                        <div class="modal-header"> 
                          <h4 class="modal-title titulo-comprobante-compra">Lista de Comprobantes</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                        </div>

                        <div class="modal-body row">
                          <div class="col-12">
                            <button  class="btn btn-success btn-sm" data-toggle="modal"  data-target="#modal-comprobantes-compra" onclick="limpiar_form_comprobante();" >Agregar</button>
                          </div>
                          <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12 mt-3">
                            <table id="tabla-comprobantes-compra" class="table table-bordered table-striped display " style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th class="">#</th>
                                  <th data-toggle="tooltip" data-original-title="Opciones">OP</th>
                                  <th data-toggle="tooltip" data-original-title="Documentos">Comprobante</th>
                                  <th data-toggle="tooltip" data-original-title="Fecha de subida">Fecha</th>                          
                                </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">OP</th>
                                  <th>Doc</th>
                                  <th>Fecha</th>                                    
                                </tr>
                              </tfoot>
                            </table>
                          </div>

                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="modal fade bg-color-02020263" id="modal-comprobantes-compra">
                    <div class="modal-dialog  modal-dialog-scrollable modal-md shadow-0px1rem3rem-rgb-0-0-0-50 rounded">
                      <div class="modal-content">
                        <div class="modal-header"> 
                          <h4 class="modal-title titulo-comprobante-compra">Comprobantes</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                        </div>

                        <div class="modal-body ">
                          <!-- form start -->
                          <form id="form-comprobante" name="form-comprobante" method="POST" >
                             
                            <div class="row mx-2" id="cargando-7-fomulario">
                              <!-- id Comprobante -->
                              <input type="hidden" name="id_compra_proyecto" id="id_compra_proyecto" />
                              <input type="hidden" name="idfactura_compra_insumo" id="idfactura_compra_insumo" />

                              <!-- Doc  -->
                              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 ">
                                <div class="row">
                                  <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 pl-0 mb-3 text-center">
                                    <button type="button" class="btn btn-success btn-block btn-xs" id="doc1_i"><i class="fas fa-file-upload"></i> Subir.</button>
                                    <input type="hidden" id="doc_old_1" name="doc_old_1" />
                                    <input style="display: none;" id="doc1" type="file" name="doc1" class="docpdf" accept="application/pdf, image/*" />
                                  </div>
                                  <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 pr-0 mb-3 text-center">
                                    <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(1, 'compra_insumo', 'comprobante_compra', '100%', '320'); reload_zoom();"><i class="fa fa-eye"></i> Recargar.</button>
                                  </div>                                                                     
                                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center mt-1" id="doc1_ver"> 
                                    <img src="../dist/svg/doc_uploads.svg" alt="" width="50%" />                           
                                  </div>                                                                
                                  <div class="col-12 col-sm-12 col-md-7 col-lg-12 col-xl-12 text-center" id="doc1_nombre"><!-- aqui va el nombre del pdf --></div>                                                                   
                                </div>
                              </div>
                              <!-- barprogress -->
                              <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12 mb-3" style="margin-top: 20px;">
                                <div class="progress" id="barra_progress_comprobante_div">
                                  <div id="barra_progress_comprobante" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                    0%
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="row" id="cargando-8-fomulario" style="display: none;">
                              <div class="col-lg-12 text-center">
                                <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                                <br />
                                <h4>Cargando...</h4>
                              </div>
                            </div>
                             
                            <!-- /.card-body -->
                            <button type="submit" style="display: none;" id="submit-form-comprobante-compra"></button>
                          </form>
                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-success btn-sm float-right" id="guardar_registro_comprobante_compra" >Guardar Cambios</button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Modal-ver-vaucher-pagos -->
                  <div class="modal fade" id="modal-ver-vaucher">
                    <div class="modal-dialog modal-dialog-scrollable modal-xm">
                      <div class="modal-content">
                        <div class="modal-header" style="background-color: #ce834926;">
                          <h4 class="modal-title">Voucher</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body text-center ver-comprobante-pago"> 
                          
                        </div>
                      </div>
                    </div>
                  </div> 

                  <!-- Modal ver grande img producto -->
                  <div class="modal fade" id="modal-ver-img-material">
                    <div class="modal-dialog modal-dialog-scrollable modal-md shadow-0px1rem3rem-rgb-0-0-0-50 rounded">
                      <div class="modal-content bg-color-0202022e shadow-none border-0" >
                        <div class="modal-header">
                          <h4 class="modal-title text-white nombre-img-material">Img producto</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-white" aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <div class="class-style" style="text-align: center;">
                            
                            <img onerror="this.src='../dist/svg/404-v2.svg';" src="" class="img-thumbnail " id="ver_img_material" style="cursor: pointer !important;" width="auto" />
                            
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Modal agregar MATERIALES Y ACTIVOS FIJOS - charge -->                 
                  <div class="modal fade bg-color-02020263" id="modal-agregar-material-activos-fijos">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Agregar Producto</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                        </div>

                        <div class="modal-body">
                          <!-- form start -->
                          <form id="form-materiales" name="form-materiales" method="POST">
                            <div class="card-body">
                              <div class="row" id="cargando-9-fomulario">

                                <!-- idproducto -->
                                <input type="hidden" name="idproducto_p" id="idproducto_p" />  
                                <!-- cont registro -->
                                <input type="hidden" name="cont" id="cont" />  
                                <!-- serie -->
                                <input class="form-control" type="hidden" id="serie_p" name="serie_p" placeholder="Serie." />
                                <!-- modelo -->
                                <input class="form-control" type="hidden" id="modelo_p" name="modelo_p" placeholder="Modelo." />
                                <!-- color -->
                                <input type="hidden" name="color_p" id="color_p" value="1">
                                <!-- marca -->
                                <input type="hidden" name="marca_p" id="marca_p" value="1">


                                <!-- Nombre -->
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                  <div class="form-group">
                                    <label for="nombre_p">Nombre <sup class="text-danger">(unico*)</sup></label>
                                    <input type="text" name="nombre_p" class="form-control" id="nombre_p" placeholder="Nombre del producto."  />
                                  </div>
                                </div>

                                <!-- Categoria -->
                                <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                  <div class="form-group">
                                    <label for="categoria_insumos_af_p">Clasificación <sup class="text-danger">(unico*)</sup></label>
                                    <select name="categoria_insumos_af_p" id="categoria_insumos_af_p" class="form-control select2" style="width: 100%;" onchange="grupo_no_select();"> 
                                    </select>
                                  </div>
                                </div>

                                <!-- Grupo -->
                                <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                  <div class="form-group">
                                    <label for="idtipo_tierra_concreto">Grupo <sup class="text-danger">(unico*)</sup></label>
                                    <select name="idtipo_tierra_concreto" id="idtipo_tierra_concreto" class="form-control select2" style="width: 100%;"> 
                                    </select>
                                  </div>
                                </div>

                                <!-- Modelo -->
                                <!-- <div class="col-lg-6">
                                  <div class="form-group">
                                    <label for="modelo_p">Modelo <sup class="text-danger">*</sup> </label>
                                    <input class="form-control" type="text" id="modelo_p" name="modelo_p" placeholder="Modelo." />
                                  </div>
                                </div> -->

                                <!-- Serie -->
                                <!-- <div class="col-lg-6">
                                  <div class="form-group">
                                    <label for="serie_p">Serie </label>
                                    <input class="form-control" type="hidden" id="serie_p" name="serie_p" placeholder="Serie." />
                                  </div>
                                </div> -->

                                <!-- Marca -->
                                <!-- <div class="col-lg-6">
                                  <div class="form-group">
                                    <label for="marca_p">Marca </label>
                                    <input class="form-control" type="text" id="marca_p" name="marca_p" placeholder="Marca de activo." />
                                  </div>
                                </div> -->

                                <!-- Color -->
                                <!-- <div class="col-lg-6 hidden">
                                  <div class="form-group">
                                    <label for="color_p">Color <sup class="text-danger">(unico*)</sup></label>
                                    <select name="color_p" id="color_p" class="form-control select2" style="width: 100%;"> </select>
                                  </div>
                                </div> -->
                                
                                <!-- Unnidad-->
                                <div class="col-lg-6" id="content-t-unidad">
                                  <div class="form-group">
                                    <label for="unidad_medida_p">Unidad-medida <sup class="text-danger">(unico*)</sup></label>
                                    <select name="unidad_medida_p" id="unidad_medida_p" class="form-control select2" style="width: 100%;"> </select>
                                  </div>
                                </div>

                                <!--Precio U-->
                                <div class="col-lg-4">
                                  <div class="form-group">
                                    <label for="precio_unitario_p">Precio <sup class="text-danger">*</sup></label>
                                    <input type="text" name="precio_unitario_p" class="form-control miimput" id="precio_unitario_p" placeholder="Precio Unitario." onchange="precio_con_igv();" onkeyup="precio_con_igv();" />
                                  </div>
                                </div>

                                <!-- Rounded switch -->
                                <div class="col-lg-2">
                                  <div class="form-group">
                                    <label for="" class="labelswitch">Sin o Con (Igv)</label>
                                    <div id="switch_igv">
                                      <div class="myestilo-switch">
                                        <div class="switch-toggle">
                                          <input type="checkbox" id="my-switch_igv" checked />
                                          <label for="my-switch_igv"></label>
                                        </div>
                                      </div>
                                    </div>
                                    <input type="hidden" name="estado_igv_p" id="estado_igv_p" />
                                  </div>
                                </div>

                                <!--Sub Total subtotal igv total-->
                                <div class="col-lg-4">
                                  <div class="form-group">
                                    <label for="precio_sin_igv_p">Sub Total</label>
                                    <input type="text" class="form-control" name="precio_sin_igv_p" id="precio_sin_igv_p" placeholder="Precio real." onchange="precio_con_igv();" onkeyup="precio_con_igv();" readonly />
                                  </div>
                                </div>

                                <!--IGV-->
                                <div class="col-lg-4">
                                  <div class="form-group">
                                    <label for="precio_igv_p">IGV</label>
                                    <input type="text" class="form-control" name="precio_igv_p" id="precio_igv_p" placeholder="Monto igv." onchange="precio_con_igv();" onkeyup="precio_con_igv();" readonly />
                                  </div>
                                </div>

                                <!--Total-->
                                <div class="col-lg-4">
                                  <div class="form-group">
                                    <label for="precio_total_p">Total</label>
                                    <input type="text" class="form-control" name="precio_total_p" id="precio_total_p" placeholder="Precio real." readonly />
                                  </div>
                                </div>

                                <!--Descripcion-->
                                <div class="col-lg-12">
                                  <div class="form-group">
                                    <label for="descripcion_p">Descripción </label> <br />
                                    <textarea name="descripcion_p" id="descripcion_p" class="form-control" rows="2"></textarea>
                                  </div>
                                </div>

                                <!--iamgen-material-->
                                <div class="col-md-6 col-lg-6">
                                  <label for="foto2">Imagen</label>
                                  <div style="text-align: center;">
                                    <img
                                      onerror="this.src='../dist/img/default/img_defecto_activo_fijo_material.png';"
                                      src="../dist/img/default/img_defecto_activo_fijo_material.png"
                                      class="img-thumbnail"
                                      id="foto2_i"
                                      style="cursor: pointer !important; height: 100% !important;"
                                      width="auto"
                                    />
                                    <input style="display: none;" type="file" name="foto2" id="foto2" accept="image/*" />
                                    <input type="hidden" name="foto2_actual" id="foto2_actual" />
                                    <div class="text-center" id="foto2_nombre"><!-- aqui va el nombre de la FOTO --></div>
                                  </div>
                                </div>

                                <!-- Ficha tecnica -->
                                <div class="col-md-6 col-lg-6">
                                  <label for="doc2_i" >Comprobante <small><b class="text-danger">(Imagen o PDF)</b></small>  </label>  
                                  <div class="row text-center">                               
                                    <!-- Subir documento -->
                                    <div class="col-6 col-md-6 text-center">
                                      <button type="button" class="btn btn-success btn-block btn-xs" id="doc2_i">
                                        <i class="fas fa-upload"></i> Subir.
                                      </button>
                                      <input type="hidden" id="doc_old_2" name="doc_old_2" />
                                      <input style="display: none;" id="doc2" type="file" name="doc2" accept="application/pdf, image/*" class="docpdf" /> 
                                    </div>
                                    <!-- Recargar -->
                                    <div class="col-6 col-md-6 text-center comprobante">
                                      <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(2, 'material', 'ficha_tecnica'); reload_zoom();">
                                      <i class="fas fa-redo"></i> Recargar.
                                    </button>
                                    </div>                                  
                                  </div>
                                  <div id="doc2_ver" class="text-center mt-4">
                                    <img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >
                                  </div>
                                  <div class="text-center" id="doc2_nombre"><!-- aqui va el nombre del pdf --></div>
                                </div>

                              </div>

                              <div class="row" id="cargando-10-fomulario" style="display: none;">
                                <div class="col-lg-12 text-center">
                                  <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                                  <br />
                                  <h4>Cargando...</h4>
                                </div>
                              </div>
                            </div>
                            <!-- /.card-body -->
                            <button type="submit" style="display: none;" id="submit-form-materiales">Submit</button>
                          </form>
                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_materiales();">Close</button>
                          <button type="submit" class="btn btn-success" id="guardar_registro_material">Guardar Cambios</button>
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
        
        <?php require 'script.php'; ?>

        <!-- table export EXCEL -->
        <script src="../plugins/export-xlsx/xlsx.full.min.js"></script>
        <script src="../plugins/export-xlsx/FileSaver.min.js"></script>
        <script src="../plugins/export-xlsx/tableexport.min.js"></script>

        <!-- ZIP -->
        <script src="../plugins/jszip/jszip.js"></script>
        <script src="../plugins/jszip/dist/jszip-utils.js"></script>
        <script src="../plugins/FileSaver/dist/FileSaver.js"></script>
        
        <script type="text/javascript" src="scripts/compra_insumos.js"></script>         

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>
        
      </body>
    </html>
    <?php    
  }

  ob_end_flush();
?>
