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
        <title>Admin Sevens | All activos fijos</title>
        <?php
          require 'head.php';
        ?>
        <!--CSS  switch_MATERIALES-->
        <link rel="stylesheet" href="../dist/css/switch_compra.css" />
        <link rel="stylesheet" href="../dist/css/leyenda.css" />
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
          if ($_SESSION['activo_fijo_general']==1){
          ?>
          <!--Contenido-->
          <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
              <div class="container-fluid">
                <div class="row mb-2">
                  <div class="col-sm-6">
                    <h1 class="m-0">All activos fijos</h1>
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="#">Home</a></li>
                      <li class="breadcrumb-item active">All activos fijos</li>
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
                          <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                              <div class="card-header">
                                <h3 class="card-title">
                                  <!--data-toggle="modal" data-target="#modal-agregar-compra"  onclick="limpiar();"-->
                                  <button type="button" class="btn bg-gradient-success" id="btn_agregar" onclick="ver_form_add();"><i class="fas fa-plus-circle"></i> Agregar</button>
                                  <button type="button" class="btn bg-gradient-warning" id="regresar" style="display: none;" onclick="regresar();"><i class="fas fa-arrow-left"></i> Regresar</button>
                                  <button type="button" id="btn-pagar" class="btn bg-gradient-success" data-toggle="modal" style="display: none;" data-target="#modal-agregar-pago" onclick="limpiar_c_pagos();">
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

                            <!-- Leyecnda saldos leyecnda_pagos,leyecnda_saldos-->
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
                        <!-- TABLA - Lista de compras Por Facturas -->
                        <div id="div_tabla_compra">
                          <table id="tabla-compra" class="table table-bordered table-striped display" style="width: 100% !important;">
                            <thead>
                              <tr>
                                <th class="">Acciones</th>
                                <th>Fecha</th>
                                <th>Proveedor</th>
                                <th data-toggle="tooltip" data-original-title="Tipo y Número Comprobante">Tipo</th>
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
                                <th data-toggle="tooltip" data-original-title="Tipo y Número Comprobante">Tipo</th>
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

                        <!-- TABLA - Lista de Compras Por Proveedor -->
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
                            <form id="form-compra-activos-f" name="form-compra-activos-f" method="POST">
                              <div class="card-body">
                                <div class="row" id="cargando-1-fomulario">
                                  <!-- id proyecto -->
                                  <input type="hidden" name="idcompra_af_general" id="idcompra_af_general" /> 

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
                                      <a data-toggle="modal" href="#modal-agregar-proveedor" >
                                        <button type="button" class="btn btn-success btn-block" data-toggle="tooltip" data-original-title="Agregar Provedor" onclick="limpiardatosproveedor();">
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
                                      <select name="tipo_comprovante" id="tipo_comprovante" class="form-control select2" onchange="modificarSubtotales(); ocultar_comprob();" placeholder="Seleccinar un tipo de comprobante">
                                        <option value="Ninguno">Ninguno</option>
                                        <option value="Boleta">Boleta</option>
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

                                  <!--Boton agregar Activo-->
                                  <div class="row col-lg-12 justify-content-between">
                                    <div class="col-lg-3 xs-12">
                                      <label for="" style="color: white;">.</label> <br />
                                      <a data-toggle="modal" data-target="#modal-elegir-activos">
                                        <button id="btnAgregarArt" type="button" class="btn btn-success"><span class="fa fa-plus"></span> Agregar Activo</button>
                                      </a>
                                    </div>

                                </div>
                                  <!--tabla detalles plantas-->
                                  <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive row-horizon disenio-scroll">
                                    <br />
                                    <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                                      <thead style="background-color: #127ab6ba;">
                                        <th data-toggle="tooltip" data-original-title="Opciones">Op.</th>
                                        <th>Activo</th>
                                        <th>Unidad medida</th>
                                        <th>Cantidad</th>
                                        <th class="hidden" data-toggle="tooltip" data-original-title="Valor Unitario" >V/U</th>
                                        <th class="hidden">IGV</th>
                                        <th data-toggle="tooltip" data-original-title="Precio Unitario">P/U</th>
                                        <th>Descuento</th>
                                        <th>Subtotal</th>
                                      </thead>
                                      <tfoot>
                                        <td colspan="5" id="colspan_subtotal"></td>
                                        <th class="text-center">
                                          <h5>Gravada</h5>
                                          <h5>IGV (18%)</h5>
                                          <h5>TOTAL</h5>
                                        </th>
                                        <th class=" "> 
                                          <h5 class="text-right " id="subtotal" style="font-weight: bold;">S/. 0.00</h5>
                                          <input type="hidden" name="subtotal_compra" id="subtotal_compra" />

                                          <h5 class="text-right" name="igv_comp" id="igv_comp" style="font-weight: bold;">S/. 0.00</h5>
                                          <input type="hidden" name="igv_compra" id="igv_compra" />
                                          <b>
                                            <h4 class="text-right" id="total" style="font-weight: bold;">S/. 0.00</h4>
                                            <input type="hidden" name="total_compra_af_g" id="total_compra_af_g" />
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
                              <button type="submit" style="display: none;" id="submit-form-compra-activos-f">Submit</button>
                            </form>
                          </div>

                          <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-danger" onclick="regresar();" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success" style="display: none;" id="guardar_registro_compras">Guardar Cambios</button>
                          </div>
                        </div>
                        <!--Pagos sin detracciòn-->
                        <div id="pago_compras" style="display: none;">
                          <h5>pago Compras</h5>
                          <div style="text-align: center;">
                            <div>
                              <h4>Total a pagar: <b id="total_compra"></b></h4>
                            </div>
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
                <div class="modal fade" id="modal-elegir-activos">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Seleccionar Activo</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body table-responsive">
                        <table id="tblaactivos" class="table table-striped table-bordered table-condensed table-hover" style="width: 100% !important;">
                          <thead>
                            <th data-toggle="tooltip" data-original-title="Opciones">Op.</th>
                            <th>Nombre Activo</th>
                            <th>Marca</th>
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
                              <input type="hidden" name="idcompra_af_general_p" id="idcompra_af_general_p" />
                              <!-- id compras -->
                              <input type="hidden" name="idpago_af_general" id="idpago_af_general" />
                              <!-- Beneficiario -->
                              <div class="col-lg-12">
                                <div class="form-group">
                                  <label for="beneficiario_pago">Beneficiario</label>
                                  <input class="form-control" type="hidden" id="beneficiario_pago" name="beneficiario_pago" />
                                  <br />
                                  <b id="h4_mostrar_beneficiario" style="font-size: 16px; color: red;"></b>
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
                                  <input type="text" name="cuenta_destino_pago" id="cuenta_destino_pago" class="form-control" placeholder="Cuenta destino" />
                                </div>
                              </div>
                              <!-- banco -->
                              <div class="col-lg-6">
                                <div class="form-group">
                                  <label for="banco_pago">Banco</label>
                                  <select name="banco_pago" id="banco_pago" class="form-control select2" style="width: 100%;">
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
                                  <input type="number" step="0.01" name="monto_pago" id="monto_pago" class="form-control" placeholder="Ingrese monto" onkeyup="validando_excedentes();" onchange="validando_excedentes();" />
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
                                <img onerror="this.src='../dist/img/default/img_defecto.png';" src="../dist/img/default/img_defecto.png" class="img-thumbnail" id="foto1_i" style="cursor: pointer !important;" width="auto" />
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
                                <th>Activo</th>
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
                <!--===============Modal agregar comprobantes general =========-->
                <!-- Modal agregar Comprobante -->
                <div class="modal fade" id="modal-comprobantes-af-g">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Actualizar Comprobante</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form id="form-comprobante" name="form-comprobante" method="POST">
                          <div class="card-body">
                            <div class="row" id="cargando-3-fomulario">
                              <!-- id Comprobante -->
                              <input type="hidden" name="idcompra_af_g_o_p" id="idcompra_af_g_o_p" />

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
                                    <a type="button" class="btn-xs btn btn-warning btn-block" id="descargar_comprob" style="padding: 0px 12px 0px 12px !important;" download="Comprobantes"> <i class="fas fa-download"></i> Descargar. </a>
                                  </div>
                                  <div class="col-md-4 text-center ver_completo" style="display: none;">
                                    <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" id="ver_completo" style="padding: 0px 12px 0px 12px !important;"> <i class="fas fa-expand"></i> Completo. </a>
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
                                  <div id="barra_progress2" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
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
                <!--===============Modal agregar comprobantes proyecto=========-->
                <!-- Modal agregar Comprobante -->
                <div class="modal fade" id="modal-comprobantes-af-p">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Actualizar Comprobante</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form id="form-comprobante_p" name="form-comprobante_p" method="POST">
                          <div class="card-body">
                            <div class="row" id="cargando-3-fomulario">
                              <!-- id Comprobante -->
                              <input type="hidden" name="comp_idcompra_af_proyecto" id="comp_idcompra_af_proyecto" />

                              <!-- Doc  -->
                              <div class="col-md-12 col-lg-12">
                                <div class="row text-center">
                                  <div class="col-md-12" style="padding-top: 15px; padding-bottom: 5px;">
                                    <label for="cip" class="control-label">Documento </label>
                                  </div>
                                  <div class="col-md-6 text-center subir_c">
                                    <button type="button" class="btn btn-success btn-block btn-xs" id="doc2_i"><i class="fas fa-file-upload"></i> Subir.</button>
                                    <input type="hidden" id="doc_old_2" name="doc_old_2" />
                                    <input style="display: none;" id="doc2" type="file" name="doc2" class="docpdf" />
                                  </div>
                                  <div class="col-md-6 text-center comprobante_c">
                                    <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion2();"><i class="fa fa-eye"></i> Comprobante.</button>
                                  </div>
                                  <div class="col-md-4 text-center descargar_c" style="display: none;">
                                    <a type="button" class="btn-xs btn btn-warning btn-block" id="descargar_c_comprob" style="padding: 0px 12px 0px 12px !important;" download="Comprobantes"> <i class="fas fa-download"></i> Descargar. </a>
                                  </div>
                                  <div class="col-md-4 text-center ver_c_completo" style="display: none;">
                                    <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" id="ver_c_completo" style="padding: 0px 12px 0px 12px !important;"> <i class="fas fa-expand"></i> Completo. </a>
                                  </div>
                                </div>
                                <div id="doc2_ver" class="text-center mt-4">
                                  <img src="../dist/svg/doc_uploads.svg" alt="" width="50%" />
                                </div>
                                <div class="text-center" id="doc2_nombre"><!-- aqui va el nombre del pdf --></div>
                              </div>

                              <!-- ver_completo descargar comprobante subir -->
                              <!-- barprogress -->
                              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 20px;">
                                <div class="progress" id="div_barra_progress2">
                                  <div id="barra_progress2" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
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
                          <button type="submit" style="display: none;" id="submit-form-comprobante-p">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro_3">Guardar Cambios</button>
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
                      <div class="modal-header" style="background-color: #ce834926;">
                        <h4 class="modal-title">voucher</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div id="datosservicios" class="class-style" style="text-align: center;">
                          <a class="btn btn-warning btn-block" href="#" id="descargar" download="Voucher" style="padding: 0px 12px 0px 12px !important;" type="button"><i class="fas fa-download"></i></a>
                          <br />
                          <img onerror="this.src='../dist/img/default/img_defecto.png';" src="../dist/img/default/img_defecto.png" class="img-thumbnail" id="img-vaucher" style="cursor: pointer !important;" width="auto" />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- ============ Modal ver grande img producto -->
                <div class="modal fade" id="modal-ver-img-activo">
                  <div class="modal-dialog modal-dialog-scrollable modal-md">
                    <div class="modal-content">
                      <div class="modal-header" style="background-color: #49a9ceb8;">
                        <h4 class="modal-title nombre-img-activo">Img producto</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div class="class-style" style="text-align: center;">
                           
                          <img onerror="this.src='../dist/img/default/default_activos_fijos_empresa.png';" src="" class="img-thumbnail " id="ver_img_activo" style="cursor: pointer !important;" width="auto" />
                           
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
       
      <script type="text/javascript" src="scripts/all_activos_fijos.js"></script>
      
      <script>
        $(function () {
          $('[data-toggle="tooltip"]').tooltip();
        });
      </script>

      <script>
        if (localStorage.getItem("nube_idproyecto")) {
          console.log("icon_folder_" + localStorage.getItem("nube_idproyecto"));

          $("#ver-proyecto").html('<i class="fas fa-tools"></i> Proyecto: ' + localStorage.getItem("nube_nombre_proyecto"));

          $(".ver-otros-modulos-1").show();

          // $('#icon_folder_'+localStorage.getItem('nube_idproyecto')).html('<i class="fas fa-folder-open"></i>');
        } else {
          $("#ver-proyecto").html('<i class="fas fa-tools"></i> Selecciona un proyecto');

          $(".ver-otros-modulos-1").hide();
        }
      </script>
    </body>
  </html>
<?php    
  }
  ob_end_flush();
?>
