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
        <title>Planillas y Seguros | Admin Sevens</title>
        
        <?php $title = "Planillas y Seguros";  require 'head.php'; ?>
          
      </head>
      <body class="hold-transition sidebar-collapse sidebar-mini layout-fixed layout-navbar-fixed">
        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper">
          <?php
          require 'nav.php';
          require 'aside.php';
          if ($_SESSION['planilla_seguro']==1){
            //require 'enmantenimiento.php';
            ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
              <!-- Content Header (Page header) -->
              <section class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1>Planillas y seguros</h1>
                    </div>
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Planillas y seguros</li>
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
                            <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-otro_servicio" onclick="limpiar();"><i class="fas fa-plus-circle"></i> Agregar</button>
                            Administra de manera eficiente planillas y seguros.
                          </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                          <table id="tabla-otro_servicio" class="table table-bordered table-striped display" style="width: 100% !important;">
                            <thead>
                              <tr>
                                <th class="text-center">#</th>
                                <th class="">Acciones</th>
                                <th>Fecha</th>
                                <th>Proveedor</th>
                                <th data-toggle="tooltip" data-original-title="Forma Pago">Forma P.</th>
                                <th data-toggle="tooltip" data-original-title="Tipo Comprobante">Tipo Comprob.</th>                                
                                <th>Subtotal</th>
                                <th>IGV</th>
                                <th>Monto Total</th>
                                <th>Descripción</th>
                                <th data-toggle="tooltip" data-original-title="Comprobante">CFDI.</th>
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
                                <th class="text-nowrap text-right px-2"><div class="formato-numero-conta"> <span>S/</span><span id="total_monto">0.00</span> </div></th>
                                <th>Descripción</th>
                                <th>CFDI.</th>                                
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

                <!-- MODAL - AGREGAR PLANILLA -->
                <div class="modal fade" id="modal-agregar-otro_servicio">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title"><b>Agregar:</b> Planillas y seguros</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form id="form-otro_servicio" name="form-otro_servicio" method="POST">
                          <div class="card-body">
                            <div class="row" id="cargando-1-fomulario">
                              <!-- id proyecto -->
                              <input type="hidden" name="idproyecto" id="idproyecto" />
                              <!-- id hospedaje -->
                              <input type="hidden" name="idplanilla_seguro" id="idplanilla_seguro" />
                              <!-- Tipo de comprobante -->

                              <input type="hidden" name="ruc_proveedor" id="ruc_proveedor">
                              

                              <!-- Tipo de Empresa -->
                              <div class="col-lg-12">
                                <div class="form-group">
                                  <label for="idproveedor">Proveedor <sup class="text-danger">(unico*)</sup></label>
                                  <select id="idproveedor" name="idproveedor" class="form-control select2" data-live-search="true" required title="Seleccione proveedor" onchange="extrae_ruc();"> </select>
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

                              <!-- Fecha 1 -->
                              <div class="col-lg-6 class_pading">
                                <div class="form-group">
                                  <label for="fecha">Fecha Emisión</label>
                                  <input type="date" name="fecha_p_s" class="form-control" id="fecha_p_s" />
                                </div>
                              </div>

                              <div class="col-lg-6" id="content-t-comprob">
                                <div class="form-group">
                                  <label for="tipo_comprobante">Tipo Comprobante <sup class="text-danger">(unico*)</sup></label>
                                  <select name="tipo_comprobante" id="tipo_comprobante" class="form-control select2" onchange="comprob_factura(); validando_igv();" onkeyup="comprob_factura();" placeholder="Seleccinar un tipo de comprobante">
                                    <option value="Ninguno">Ninguno</option>
                                    <option value="Boleta">Boleta</option>
                                    <option value="Factura">Factura</option>
                                    <option value="Nota de venta">Nota de venta</option>
                                  </select>
                                </div>
                              </div>
                              <!-- Código-->
                              <div class="col-lg-6">
                                <div class="form-group">
                                  <label for="codigo" class="nro_comprobante">Núm. comprobante <sup class="text-danger">(unico*)</sup></label>
                                  <input type="text" name="nro_comprobante" id="nro_comprobante" class="form-control" placeholder="Código" />
                                </div>
                              </div>

                              
                              <!-- Sub total -->
                              <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="subtotal">Sub total</label>
                                  <input class="form-control" type="number" id="subtotal" name="subtotal" placeholder="Sub total" readonly />
                                </div>
                              </div>
                              <!-- IGV -->
                              <div class="col-lg-2">
                                <div class="form-group">
                                  <label for="igv">IGV</label>
                                  <input class="form-control" name="igv" id="igv" type="number" placeholder="IGV" readonly />
                                </div>
                              </div>
                              <!-- valor IGV -->
                              <div class="col-lg-2">
                                <div class="form-group">
                                  <label for="val_igv" class="text-gray" style="font-size: 13px;">Valor - IGV </label>
                                  <input type="text" name="val_igv" id="val_igv" value="0.18" class="form-control" readonly onkeyup="calculandototales_fact();" />
                                  <input class="form-control" type="hidden" id="tipo_gravada" name="tipo_gravada" />
                                </div>
                              </div>
                              <!--Precio Parcial-->
                              <div class="col-lg-4 class_pading">
                                <div class="form-group">
                                  <label for="monto">Monto total </label>
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
                              <div class="col-md-6">
                                <div class="row text-center">
                                  <div class="col-md-12" style="padding-top: 15px; padding-bottom: 5px;">
                                    <label for="cip" class="control-label"> Baucher de deposito </label>
                                  </div>
                                  <div class="col-6 col-md-6 text-center">
                                    <button type="button" class="btn btn-success btn-block btn-xs" id="doc1_i"><i class="fas fa-upload"></i> Subir.</button>
                                    <input type="hidden" id="doc_old_1" name="doc_old_1" />
                                    <input style="display: none;" id="doc1" type="file" name="doc1" accept="application/pdf, image/*" class="docpdf" />
                                  </div>
                                  <div class="col-6 col-md-6 text-center">
                                    <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(1, 'planilla_seguro', 'comprobante');"><i class="fas fa-redo"></i> Recargar.</button>
                                  </div>
                                </div>
                                <div id="doc1_ver" class="text-center mt-4">
                                  <img src="../dist/svg/doc_uploads.svg" alt="" width="50%" />
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
                          <button type="submit" style="display: none;" id="submit-form-otro_servicio">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar();">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro">Guardar Cambios</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- MODAL - VER COMPROBANTE -->
                <div class="modal fade" id="modal-ver-comprobante">
                  <div class="modal-dialog modal-dialog-scrollable modal-xl">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Comprobante otro servicio</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div class="row" >
                          <div class="col-6 col-lg-6 col-xl-6">
                            <a class="btn btn-xs btn-warning btn-block" href="#" id="descargar" download="Comprobante planilla seguro" type="button"><i class="fas fa-download"></i> Descargar</a>
                          </div>
                          <div class="col-6 col-lg-6 col-xl-6">
                            <a class="btn btn-xs btn-info btn-block" href="#"  target="_blank" id="ver_grande" type="button"><i class="fas fa-expand"></i> Ver completo.</a>
                          </div>
                          <div class="col-12 col-lg-12 col-xl-12 mt-2">
                            <div id="ver_documento" ></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- MODAL - VER DATOS-->
                <div class="modal fade" id="modal-ver-otro_servicio">
                  <div class="modal-dialog modal-dialog-scrollable modal-xm">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Datos otro servicio</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <div id="datosotro_servicio" class="">
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

        <!-- <script type="text/javascript" src="scripts/moment.min.js"></script>-->
        <script type="text/javascript" src="scripts/planillas_seguros.js"></script>

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>

        <?php require 'extra_script.php'; ?>
          
      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
