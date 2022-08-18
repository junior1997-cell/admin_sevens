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
        <title>Admin Sevens | Otros Ingresos</title>
        
        <?php $title = "Otros Ingresos"; require 'head.php'; ?>
          
      </head>
      <body class="hold-transition sidebar-collapse sidebar-mini layout-fixed layout-navbar-fixed">
        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper">
          <?php
          require 'nav.php';
          require 'aside.php';
          if ($_SESSION['otro_gasto']==1){
            //require 'enmantenimiento.php';
            ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper" >
              <!-- Content Header (Page header) -->
              <section class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1>Otros Ingresos</h1>
                    </div>
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="otro_ingreso.php">Home</a></li>
                        <li class="breadcrumb-item active">Otros Ingresos</li>
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
                          <h3 class="card-title btn-regresar" style="display: none;">
                            <button type="button" class="btn bg-gradient-warning" onclick="limpiar_form(); show_hide_form(1);"><i class="fas fa-arrow-left"></i> Regresar</button>                            
                          </h3>
                          <h3 class="card-title btn-agregar">
                            <button type="button" class="btn bg-gradient-success" onclick="limpiar_form(); show_hide_form(2);"><i class="fas fa-plus-circle"></i> Agregar</button>
                            Administra de manera eficiente otros ingresos.
                          </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                          <div id="mostrar-tabla">
                            <table id="tabla-otro-ingreso" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th class="text-center">#</th>
                                  <th class="">Acciones</th>
                                  <th data-toggle="tooltip" data-original-title="Forma Pago">Forma P.</th>
                                  <th data-toggle="tooltip" data-original-title="Tipo Comprobante">Tipo Comprob</th>
                                  <th>Fecha</th>
                                  <th>Subtotal</th>
                                  <th>IGV</th>
                                  <th>Monto Total</th>
                                  <th>Descripción</th>
                                  <th data-toggle="tooltip" data-original-title="Comprobante">Comprob</th>
                                  <th>Estado</th>
                                </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                <tr>
                                  <th class="text-center">#</th>
                                  <th class="">Acciones</th>
                                  <th data-toggle="tooltip" data-original-title="Forma Pago">Forma P.</th>
                                  <th data-toggle="tooltip" data-original-title="Tipo Comprobante">Tipo Comprob</th>
                                  <th>Fecha</th>
                                  <th>Subtotal</th>
                                  <th>IGV</th>
                                  <th class="text-nowrap text-right" id="total_monto"><i class="fas fa-spinner fa-pulse"></i></th>
                                  <th>Descripción</th>
                                  <th data-toggle="tooltip" data-original-title="Comprobante">Comprob</th>
                                  <th>Estado</th>
                                </tr>
                              </tfoot>
                            </table>
                          </div>

                          <div id="mostrar-form" style="display: none;">
                            
                            <!-- form start -->
                            <form id="form-otro-ingreso" name="form-otro-ingreso" method="POST">
                              <div class="card-body">
                                <div class="row" id="cargando-1-fomulario">
                                  <!-- id proyecto -->
                                  <input type="hidden" name="idproyecto" id="idproyecto" />
                                  <!-- id hospedaje -->
                                  <input type="hidden" name="idotro_ingreso" id="idotro_ingreso" />

                                  <!--Proveedor-->
                                   

                                  <div class="col-sm-12 col-md-9 col-lg-5 col-xl-5">
                                    <div class="form-group">
                                      <label for="idproveedor">Proveedor <sup class="text-danger">*</sup></label>
                                      <!-- <div class="input-group"> -->
                                        <select name="idproveedor" id="idproveedor" class="form-control select2" placeholder="Seleccinar un proveedor"> </select>
                                        <!-- <div class="input-group-append " data-toggle="modal" href="#modal-agregar-proveedor"  onclick="limpiar_form_proveedor();">
                                          <span class="input-group-text bg-success" data-toggle="tooltip" data-original-title="Agregar nuevo proveedor" style="cursor: pointer;">
                                            <i class="fas fa-user-plus"></i>
                                          </span>
                                        </div>
                                      </div> -->
                                    </div>
                                  </div>  

                                  <!-- adduser -->
                                  <div class="col-sm-12 col-md-3 col-lg-1 col-xl-1">
                                    <div class="form-group">
                                      <label class="text-white d-none show-min-width-576px">.</label> 
                                      <label class="d-none show-max-width-576px" >Nuevo proveedor</label>
                                      <a data-toggle="modal" href="#modal-agregar-proveedor" >
                                        <button type="button" class="btn btn-success btn-block" data-toggle="tooltip" data-original-title="Agregar nuevo Provedor" onclick="limpiar_form_proveedor();">
                                          <i class="fa fa-user-plus" aria-hidden="true"></i>
                                        </button>
                                      </a>
                                    </div>
                                  </div>                                 

                                  <!--forma pago-->
                                  <div class="col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                    <div class="form-group">
                                      <label for="forma_pago">Forma Pago</label>
                                      <select name="forma_pago" id="forma_pago" class="form-control select2" style="width: 100%;">
                                        <option value="Transferencia">Transferencia</option>
                                        <option value="Efectivo">Efectivo</option>
                                        <option value="Crédito">Crédito</option>
                                      </select>
                                    </div>
                                  </div>

                                  <!-- Tipo de comprobante -->
                                  <div class="col-sm-12 col-md-6 col-lg-3 col-xl-3" id="content-t-comprob">
                                    <div class="form-group">
                                      <label for="tipo_comprobante">Tipo Comprobante</label>
                                      <select name="tipo_comprobante" id="tipo_comprobante" class="form-control select2" onchange="comprob_factura(); validando_igv();" onkeyup="comprob_factura();" placeholder="Seleccinar un tipo de comprobante">
                                        <option value="Ninguno">Ninguno</option>
                                        <option value="Boleta">Boleta</option>
                                        <option value="Factura">Factura</option>
                                        <option value="Nota de venta">Nota de venta</option>
                                      </select>
                                    </div>
                                  </div>

                                  <!-- RUC style="display: none;"-->
                                  <!-- <div class="col-lg-4 div_ruc" style="display: none;"  >
                                    <div class="form-group">
                                      <label for="ruc">R.U.C</label>
                                      <div class="input-group">
                                        <input type="number" name="ruc" class="form-control" id="ruc" placeholder="N° de documento" />
                                        <div class="input-group-append" data-toggle="tooltip" data-original-title="Buscar razón social" onclick="buscar_sunat();">
                                          <span class="input-group-text" style="cursor: pointer;">
                                            <i class="fas fa-search text-primary" id="search"></i>
                                            <i class="fa fa-spinner fa-pulse fa-fw fa-lg text-primary" id="charge" style="display: none;"></i>
                                          </span>
                                        </div>
                                      </div>
                                    </div>
                                  </div> -->

                                  <!-- Razón social--> 
                                  <!-- <div class="col-lg-8 div_razon_social" style="display: none;">
                                    <div class="form-group">
                                      <label class="razon_social" for="razon_social">Razón social </label>
                                      <input type="text" name="razon_social" id="razon_social" class="form-control" placeholder="Razón social" readonly />
                                      <input type="hidden" name="direccion" id="direccion"   />
                                    </div>
                                  </div> -->

                                  <!-- Glosa-->
                                  <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4" id="content-t-comprob">
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
                                  <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                    <div class="form-group">
                                      <label class="nro_comprobante" for="nro_comprobante">Núm. comprobante </label>
                                      <input type="text" name="nro_comprobante" id="nro_comprobante" class="form-control" placeholder="Código" />
                                    </div>
                                  </div>

                                  <!-- Fecha 1  -->
                                  <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                    <div class="form-group">
                                      <label for="fecha_i">Fecha Emisión</label>
                                      <input type="date" name="fecha_i" class="form-control" id="fecha_i" />
                                    </div>
                                  </div>

                                  <!-- Sub total -->
                                  <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                    <div class="form-group">
                                      <label for="subtotal">Sub total</label>
                                      <input class="form-control" type="number" id="subtotal" name="subtotal" placeholder="Sub total" readonly />                                   
                                    </div>
                                  </div>

                                  <!-- IGV -->
                                  <div class="col-sm-12 col-md-6 col-lg-2 col-xl-2">
                                    <div class="form-group">
                                      <label for="igv">IGV</label>
                                      <input class="form-control igv" type="number" id="igv" name="igv" placeholder="IGV" readonly />
                                    </div>
                                  </div>

                                  <!-- valor IGV -->
                                  <div class="col-sm-12 col-md-6 col-lg-2 col-xl-2">
                                    <div class="form-group">
                                        <label for="val_igv" class="text-gray" style="font-size: 13px;">Valor - IGV </label>
                                        <input type="text" name="val_igv" id="val_igv" value="0.18" class="form-control" readonly onkeyup="calculandototales_fact();"> 
                                        <input class="form-control" type="hidden"  id="tipo_gravada" name="tipo_gravada"/>
                                    </div>
                                  </div>
                                  
                                  <!--Precio Parcial-->
                                  <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                    <div class="form-group">
                                      <label for="marca">Monto total </label>
                                      <input type="number" name="precio_parcial" id="precio_parcial" class="form-control" onchange="comprob_factura();" onkeyup="comprob_factura();" placeholder="Precio Parcial" />                                  
                                    </div>
                                  </div>

                                  <!--Descripcion-->
                                  <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <div class="form-group ">
                                      <label for="descripcion_pago">Descripción</label> <br />
                                      <textarea name="descripcion" id="descripcion" class="form-control" rows="2"></textarea>
                                    </div>
                                  </div>
                                  <!-- <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                  <div style="overflow: auto; resize: vertical;"><b>gola:</b> como estas gomero <br> <b>casa: </b> cerca del gim</div>
                                  </div> -->

                                  <!-- Factura -->
                                  <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4" >   
                                    <!-- linea divisoria -->
                                    <div class="borde-arriba-naranja mt-4"> </div>                            
                                    <div class="row text-center">
                                      <div class="col-md-12" style="padding-top: 15px; padding-bottom: 5px;">
                                        <label for="cip" class="control-label" > Comprobante </label>
                                      </div>
                                      <div class="col-6 col-md-6 text-center">
                                        <button type="button" class="btn btn-success btn-block btn-xs" id="doc1_i"> <i class="fas fa-upload"></i> Subir.</button>
                                        <input type="hidden" id="doc_old_1" name="doc_old_1" />
                                        <input style="display: none;" id="doc1" type="file" name="doc1" accept="application/pdf, image/*" class="docpdf" /> 
                                      </div>
                                      <div class="col-6 col-md-6 text-center">
                                        <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(1, 'otro_ingreso', 'comprobante');">
                                        <i class="fas fa-redo"></i> Recargar.
                                        </button>
                                      </div>
                                    </div>                              
                                    <div id="doc1_ver" class="text-center mt-4">
                                      <img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >
                                    </div>
                                    <div class="text-center" id="doc1_nombre"><!-- aqui va el nombre del pdf --></div>
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
                              <div class=" justify-content-between">
                                <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="show_hide_form(1);"> <i class="fas fa-arrow-left"></i> Close</button>
                                <button type="submit" class="btn btn-success" id="guardar_registro">Guardar Cambios</button>
                              </div>
                            </form>

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
                          <div class="card-body row">                               
                            
                            <!-- id proveedores -->
                            <input type="hidden" name="idproveedor_prov" id="idproveedor_prov" />

                            <!-- Tipo de documento -->
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="tipo_documento_prov">Tipo de documento</label>
                                <select name="tipo_documento_prov" id="tipo_documento_prov" class="form-control" placeholder="Tipo de documento">
                                  <option value="RUC">RUC</option>
                                  <option selected value="DNI">DNI</option>
                                </select>
                              </div>
                            </div>

                            <!-- N° de documento -->
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="num_documento_prov">N° RUC / DNI</label>
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
                                <label for="nombre_prov">Razón Social / Nombre y Apellidos</label>
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
                                <label for="banco_prov">Banco</label>
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

                <!-- MODAL - AGREGAR COMPROBANTE -->
                <div class="modal fade" id="modal-ver-comprobante">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Comprobante otros gastos</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body ver-comprobante">
                                                 
                        
                         
                      </div>
                    </div>
                  </div>
                </div>

                <!--MODAL - VER DETALLE DE OTRO INGRESO -->
                <div class="modal fade" id="modal-ver-otro-gasto">
                  <div class="modal-dialog modal-dialog-scrollable modal-xm">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Datos otros gastos</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body"> 
                        <div id="datos_otro_gasto" class="class-style">
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
        
        <!-- Funciones del modulo -->
        <script type="text/javascript" src="scripts/otro_ingreso.js"></script>

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>
        
      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
