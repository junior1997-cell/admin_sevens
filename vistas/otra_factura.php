<?php
  //Activamos el almacenamiento en el buffer
  ob_start();

  session_start();
  if (!isset($_SESSION["nombre"])){
    header("Location: index.php?file=".basename($_SERVER['PHP_SELF']));
  }else{
    ?>

    <!DOCTYPE html>
    <html lang="es">
      <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Admin Sevens | Otras facturas</title>
        
        <?php $title = "Otras facturas"; require 'head.php'; ?>
          
      </head>
      <body class="hold-transition sidebar-collapse sidebar-mini layout-fixed layout-navbar-fixed">
        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper">
          <?php
          require 'nav.php';
          require 'aside.php';
          if ($_SESSION['otra_factura']==1){
            //require 'enmantenimiento.php';
            ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
              <!-- Content Header (Page header) -->
              <section class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1>Otras facturas</h1>
                    </div>
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Otras facturas</li>
                      </ol>
                    </div>
                  </div>
                </div>
                <!-- /.container-fluid -->
              </section>

              <!-- Main content -->
              <section class="content">
                <div class="container-fluid">
                  <div class="row">
                    <div class="col-12">
                      <div class="card card-primary card-outline">
                        <div class="card-header">
                          <h3 class="card-title">
                            <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-otras_facturas" onclick="limpiar();"><i class="fas fa-plus-circle"></i> Agregar</button>
                            Administra de manera eficiente otras facturas.
                          </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                          <!-- filtros -->
                          <div class="filtros-inputs row mb-4">

                            <!-- filtro por: comprobante -->
                            <div class="col-12 col-sm-6 col-md-6 col-lg-2">
                              <div class="form-group">
                                <!-- <label for="filtros" >Tipo comprobante </label> -->
                                <select id="filtro_empresa_a_cargo" class="form-control select2" onchange="cargando_search(); delay(function(){filtros()}, 50 );" style="width: 100%;"> 
                                  
                                </select>
                              </div>                              
                            </div>

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
                            <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                              <div class="form-group">
                                <!-- <label for="filtros" class="cargando_proveedor">Proveedor &nbsp;<i class="text-dark fas fa-spinner fa-pulse fa-lg"></i><br /></label> -->
                                <select id="filtro_proveedor" class="form-control select2" onchange="cargando_search(); delay(function(){filtros()}, 50 );" style="width: 100%;"> 
                                </select>
                              </div>                          
                            </div>

                            <!-- filtro por: comprobante -->
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

                          <table id="tabla-otras_facturas" class="table table-bordered table-striped display" style="width: 100% !important;">
                            <thead>
                              <tr>
                                <th colspan="13" class="cargando text-center bg-danger"><i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando... </th>
                              </tr>
                              <tr>
                                <th class="text-center">#</th>
                                <th class="">Acciones</th>
                                <th>Fecha</th>                                
                                <th>Proveedor</th>
                                <th data-toggle="tooltip" data-original-title="Forma Pago">Forma Pago</th>
                                <th data-toggle="tooltip" data-original-title="Tipo Comprobante">Tipo Comprob</th>
                                <th>Subtotal</th>
                                <th>IGV</th>
                                <th>Monto Total</th>
                                <th>Descripción</th>
                                <th data-toggle="tooltip" data-original-title="Comprobante">Comprob</th>
                                <th>Glosa</th>
                                <th>Comprobante</th>
                                <th>Numero</th>
                              </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                              <tr>
                                <th class="text-center">#</th>
                                <th class="">Acciones</th>
                                <th>Fecha</th>                                
                                <th>Proveedor</th>
                                <th data-toggle="tooltip" data-original-title="Forma Pago">Forma P.</th>
                                <th data-toggle="tooltip" data-original-title="Tipo Comprobante">Tipo Comprob</th>
                                <th>Subtotal</th>
                                <th>IGV</th>
                                <th class="text-nowrap px-2"><div class="formato-numero-conta"> <span>S/</span><span id="total_monto">0.00</span></div></th>
                                <th>Descripción</th>
                                <th data-toggle="tooltip" data-original-title="Comprobante">Comprob</th>
                                <th>Glosa</th>
                                <th>Comprobante</th>
                                <th>Numero</th>
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


                <!-- MODAL - agregar otros gastos -->
                <div class="modal fade" id="modal-agregar-otras_facturas">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title"><b id="estado-edit-add-modal" >Agregar:</b> Comprobante Otra factura</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form id="form-otras_facturas" name="form-otras_facturas" method="POST">
                          <div class="card-body">
                            <div class="row" id="cargando-1-fomulario">
                              <!-- id hospedaje -->
                              <input type="hidden" name="idotra_factura" id="idotra_factura" />
                              <input type="hidden" name="ruc_proveedor" id="ruc_proveedor" />
                              <!-- Proceedor -->
                              <div class="col-lg-10">
                                <div class="form-group">
                                  <label for="idproveedor">Proveedor <sup class="text-danger">(único*)</sup></label>
                                  <select name="idproveedor" id="idproveedor" class="form-control select2" placeholder="Seleccinar un proveedor" onchange="extrae_ruc();"> </select>
                                </div>
                              </div>
                              <!-- adduser -->
                              <div class="col-lg-2">
                                <div class="form-group">
                                  <label for="Add" style="color: white;">.</label>
                                  <a data-toggle="modal" href="#modal-agregar-proveedor" >
                                    <button type="button" class="btn btn-success btn-block" data-toggle="tooltip" data-original-title="Agregar Provedor" onclick="limpiar_form_proveedor();">
                                      <i class="fa fa-user-plus" aria-hidden="true"></i>
                                    </button>
                                  </a>
                                </div>
                              </div>

                              <!-- Empresa a cargo -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                  <label for="empresa_acargo">Empresa a cargo <small class="text-orange d-none d-lg-inline-block empresa_a_cargo_form text-lowercase">("Seven's Ingenieros")</small></label>
                                  <select class="form-control select2" name="empresa_acargo" id="empresa_acargo" style="width: 100%;">
                                    <!-- <option selected value="Seven's Ingenieros SAC" title="logo-icono.svg">Seven's Ingenieros SAC</option> -->
                                    <!-- <option value="Consorcio Seven's Ingenieros SAC" title="logo-icono-plomo.svg">Consorcio Seven's Ingenieros SAC</option> -->
                                    <!-- <option value="Ninguno" title="emogi-carita-feliz.svg">Ninguno</option> -->
                                  </select>                                     
                                </div>
                              </div>

                                <!-- Fecha 1  -->
                              <div class="col-lg-6">
                                <div class="form-group">
                                  <label for="fecha_emision">Fecha Emisión <sup class="text-danger">*</sup></label>
                                  <input type="date" name="fecha_emision" class="form-control" id="fecha_emision" />
                                </div>
                              </div>

                              <!--forma pago-->
                              <div class="col-lg-6">
                                <div class="form-group">
                                  <label for="forma_pago">Forma Pago <sup class="text-danger">*</sup></label>
                                  <select name="forma_pago" id="forma_pago" class="form-control select2" style="width: 100%;">
                                    <option value="Transferencia">Transferencia</option>
                                    <option value="Efectivo">Efectivo</option>
                                    <option value="Crédito">Crédito</option>
                                  </select>
                                </div>
                              </div>
                              <!--Tipo de comprobante-->
                              <div class="col-lg-6" id="content-t-comprob">
                                <div class="form-group">
                                  <label for="tipo_comprobante">Tipo Comprobante <sup class="text-danger">(único*)</sup></label>
                                  <select name="tipo_comprobante" id="tipo_comprobante" class="form-control select2" onchange="delay(function(){select_comprobante();calc_total(); }, 100 );" placeholder="Seleccinar un tipo de comprobante">
                                    <option value="Ninguno">Ninguno</option>
                                    <option value="Boleta">Boleta</option>
                                    <option value="Factura">Factura</option>
                                    <option value="Nota de venta">Nota de venta</option>
                                  </select>
                                </div>
                              </div>
                              <!-- Glosa-->
                              <div class="col-lg-6" id="content-t-comprob">
                                <div class="form-group">
                                  <label for="glosa">Selecc. Glosa</label>
                                  <select name="glosa" id="glosa" class="form-control select2" placeholder="Seleccinar">
                                  
                                    <option value="ALIMENTACIÓN">ALIMENTACIÓN</option>
                                    <option value="COMBUSTIBLE">COMBUSTIBLE</option>
                                    <option value="MATERIAL">MATERIAL</option>
                                    <option value="PLOTEO">PLOTEO</option>
                                    <option value="AGUA">AGUA</option>
                                    <option value="COMPRAS">COMPRAS</option>
                                    <option value="SIERRA Y EXAGONALES">SIERRA Y EXAGONALES</option>
                                    <option value="HERRAMIENTAS">HERRAMIENTAS</option>
                                    <option value="ACERO Y CEMENTO">ACERO Y CEMENTO</option>
                                    <option value="ESTACIONAMIENTO">ESTACIONAMIENTO</option>
                                    <option value="PERSONALES">PERSONALES</option>
                                    <option value="PASAJE">PASAJE</option>
                                    <option value="EPPS">EPPS</option>
                                    <option value="OTROS">OTROS</option>
                                    
                                  </select>
                                </div>
                              </div>
                              <!-- Código-->
                              <div class="col-lg-6">
                                <div class="form-group">
                                  <label  for="nro_comprobante"> <span class="nro_comprobante">Núm. comprobante</span> <sup class="text-danger">(único*)</sup> </label>
                                  <input type="text" name="nro_comprobante" id="nro_comprobante" class="form-control" placeholder="Código" />
                                </div>
                              </div>
                              
                              <!-- Sub total -->
                              <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="subtotal">Sub total <small class="text-danger tipo_gravada text-lowercase"></small></label>
                                  <input class="form-control" type="text" id="subtotal" name="subtotal" placeholder="Sub total" readonly />                                   
                                </div>
                              </div>
                              <!-- IGV -->
                              <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="igv">IGV</label>
                                  <input class="form-control igv" type="text" id="igv" name="igv" placeholder="IGV" readonly />
                                </div>
                              </div>
                              <!-- valor IGV -->
                              <div class="col-lg-2">
                                <div class="form-group">
                                  <label for="val_igv" class="text-gray val_igv" style=" font-size: 13px;">Valor - IGV </label>
                                  <input type="text" name="val_igv" id="val_igv" value="0.18" class="form-control" onkeyup="delay(function(){calc_total();}, 100 );" onchange="delay(function(){calc_total();}, 100 );"> 
                                  <input type="hidden" name="tipo_gravada" id="tipo_gravada"> 
                                </div>
                              </div>
                              <!--Precio Parcial-->
                              <div class="col-lg-4 class_pading">
                                <div class="form-group">
                                  <label for="precio_parcial">Monto total <sup class="text-danger">*</sup></label>
                                  <input type="text" name="precio_parcial" id="precio_parcial" class="form-control" onkeyup="delay(function(){calc_total();}, 100 );" onchange="delay(function(){calc_total();}, 100 );" placeholder="Precio Parcial" />                                  
                                </div>
                              </div>

                              <!--Descripcion-->
                              <div class="col-lg-12 class_pading">
                                <div class="form-group">
                                  <label for="descripcion_pago">Descripción</label> <br />
                                  <textarea name="descripcion" id="descripcion" class="form-control" rows="2"></textarea>
                                </div>
                              </div>

                              <!-- Factura -->
                              <div class="col-md-6" >                               
                                <div class="row text-center">
                                  <div class="col-md-12" style="padding-top: 15px; padding-bottom: 5px;">
                                    <label for="doc1_i" class="control-label" > Baucher de deposito </label>
                                  </div>
                                  <div class="col-6 col-md-6 text-center">
                                    <button type="button" class="btn btn-success btn-block btn-xs" id="doc1_i">
                                      <i class="fas fa-upload"></i> Subir.
                                    </button>
                                    <input type="hidden" id="doc_old_1" name="doc_old_1" />
                                    <input style="display: none;" id="doc1" type="file" name="doc1" accept="application/pdf, image/*" class="docpdf" /> 
                                  </div>
                                  <div class="col-6 col-md-6 text-center">
                                    <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(1, 'comprobante');">
                                    <i class="fas fa-redo"></i> Recargar.
                                    </button>
                                  </div>
                                </div>                              
                                <div id="doc1_ver" class="text-center mt-4">
                                  <img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >
                                </div>
                                <div class="text-center" id="doc1_nombre"><!-- aqui va el nombre del pdf --></div>
                              </div>

                              <!-- barprogress -->
                              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                                <div class="progress" id="barra_progress_div">
                                  <div id="barra_progress" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                    0%
                                  </div>
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
                          <button type="submit" style="display: none;" id="submit-form-otras_facturas">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar();">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro">Guardar Cambios</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Modal agregar proveedores -->
                <div class="modal fade bg-color-02020280" id="modal-agregar-proveedor">
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
                          <div class="card-body row">                               
                            
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
                                <label for="num_documento_prov">N° RUC / DNI <sup class="text-danger">(único*)</sup></label>
                                <div class="input-group">
                                  <input type="number" name="num_documento_prov" class="form-control" id="num_documento_prov" placeholder="N° de documento" onchange="delay(function(){buscar_sunat_reniec('_prov')}, 300 );" onkeyup="delay(function(){buscar_sunat_reniec('_prov')}, 300 );" />
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

                <!-- MODAL -ver-comprobante-->
                <div class="modal fade" id="modal-ver-comprobante">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Comprobante otra factura</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body ver-comprobante">              
                         
                      </div>
                    </div>
                  </div>
                </div>

                <!-- MODAL - VER DETALLE-->
                <div class="modal fade" id="modal-ver-otra-factura">
                  <div class="modal-dialog modal-dialog-scrollable modal-md">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Detalle</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <div id="datos-otra-factura" class="class-style">
                          <!-- vemos los datos del trabajador -->
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

              </section>
              <!-- /.content -->
            </div>

            <?php
          }else{
            require 'noacceso.php';
          }
          require 'footer.php';
          ?>
        </div>
        <!-- /.content-wrapper -->

        <?php require 'script.php'; ?>

        <script type="text/javascript" src="scripts/otra_factura.js"></script>

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>

      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
