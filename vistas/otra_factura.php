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
                          <table id="tabla-otras_facturas" class="table table-bordered table-striped display" style="width: 100% !important;">
                            <thead>
                              <tr>
                                <th class="text-center">#</th>
                                <th class="">Acciones</th>
                                <th data-toggle="tooltip" data-original-title="Forma Pago">Forma Pago</th>
                                <th data-toggle="tooltip" data-original-title="Tipo Comprobante">Tipo Comprob</th>
                                <th>Fecha</th>
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
                                <th data-toggle="tooltip" data-original-title="Forma Pago">Forma P.</th>
                                <th data-toggle="tooltip" data-original-title="Tipo Comprobante">Tipo Comprob</th>
                                <th>Fecha</th>
                                <th>Subtotal</th>
                                <th>IGV</th>
                                <th class="text-nowrap" id="total_monto"></th>
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
                              <!-- Proceedor -->
                              <div class="col-lg-12">
                                <div class="form-group">
                                  <label for="idproveedor">Proveedor</label>
                                  <select name="idproveedor" id="idproveedor" class="form-control select2" placeholder="Seleccinar un proveedor"> </select>
                                </div>
                              </div>
                              <!--forma pago-->
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
                              <!--Tipo de comprobante-->
                              <div class="col-lg-6" id="content-t-comprob">
                                <div class="form-group">
                                  <label for="tipo_comprobante">Tipo Comprobante</label>
                                  <select name="tipo_comprobante" id="tipo_comprobante" class="form-control select2" onchange="validando_igv(); comprob_factura(); " placeholder="Seleccinar un tipo de comprobante">
                                    <option value="Ninguno">Ninguno</option>
                                    <option value="Boleta">Boleta</option>
                                    <option value="Factura">Factura</option>
                                    <option value="Nota de venta">Nota de venta</option>
                                  </select>
                                </div>
                              </div>
                              <!-- Glosa-->
                              <div class="col-lg-4" id="content-t-comprob">
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
                              <div class="col-lg-4">
                                <div class="form-group">
                                  <label class="nro_comprobante" for="nro_comprobante">Núm. comprobante </label>
                                  <input type="text" name="nro_comprobante" id="nro_comprobante" class="form-control" placeholder="Código" />
                                </div>
                              </div>

                              <!-- Fecha 1  -->
                              <div class="col-lg-4 class_pading">
                                <div class="form-group">
                                  <label for="fecha_emision">Fecha Emisión</label>
                                  <input type="date" name="fecha_emision" class="form-control" id="fecha_emision" />
                                </div>
                              </div>
                              <!-- Sub total -->
                              <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="subtotal">Sub total</label>
                                  <input class="form-control" type="number" id="subtotal" name="subtotal" placeholder="Sub total" readonly />                                   
                                </div>
                              </div>
                              <!-- IGV -->
                              <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="igv">IGV</label>
                                  <input class="form-control igv" type="number" id="igv" name="igv" placeholder="IGV" readonly />
                                </div>
                              </div>
                              <!-- valor IGV -->
                              <div class="col-lg-2">
                                <div class="form-group">
                                  <label for="val_igv" class="text-gray val_igv" style=" font-size: 13px;">Valor - IGV </label>
                                  <input type="text" name="val_igv" id="val_igv" value="0.18" class="form-control" onkeyup="calculandototales_fact();"> 
                                  <input type="hidden" name="tipo_gravada" id="tipo_gravada"> 
                                </div>
                              </div>
                              <!--Precio Parcial-->
                              <div class="col-lg-4 class_pading">
                                <div class="form-group">
                                  <label for="precio_parcial">Monto total </label>
                                  <input type="number" name="precio_parcial" id="precio_parcial" class="form-control" onchange="comprob_factura();" onkeyup="comprob_factura();" placeholder="Precio Parcial" />                                  
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

                <!-- MODAL - VER DETALLE INSUMO-->
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

        <script>
          $(function () { $('[data-toggle="tooltip"]').tooltip(); });
        </script>

        <?php require 'extra_script.php'; ?>

      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
