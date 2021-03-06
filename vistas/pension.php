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
        <title>Admin Sevens | Pensión</title>

        <?php $title = "Pensión"; require 'head.php'; ?>
        
      </head>
      <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed">
        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper">
          <?php
          require 'nav.php';
          require 'aside.php';
          if ($_SESSION['viatico']==1){
            //require 'enmantenimiento.php';
            ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
              <!-- Content Header (Page header) -->
              <section class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1 id="nomb_pension_head" ><i class="fas fa-utensils nav-icon"></i> Pensión</h1>
                    </div>
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Pensión</li>
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
                          <!-- Guardar pension -->
                          <h3 class="card-title mr-3" id="btn_guardar_pension" style="padding-left: 2px;">
                            <button type="button" class="btn bg-gradient-success btn-sm" data-toggle="modal" data-target="#modal-agregar-pension" onclick="limpiar_pension()" >
                              <i class="far fa-save"></i> Agregar Pensión
                            </button>
                          </h3>
                          <!-- regresar -->
                          <h3 class="card-title mr-3" id="btn_regresar" style="display: none; padding-left: 2px;">
                            <button type="button" class="btn bg-gradient-warning btn-sm" onclick="mostrar_form_table(1); limpiar_comprobante();" >
                              <i class="fas fa-arrow-left"></i> <span class="d-none d-sm-inline-block">Regresar</span>
                            </button>
                          </h3>
                          <!-- agregar detalle pension -->
                          <h3 class="card-title mr-3" id="btn_guardar_detalle_pension" style="display: none; padding-left: 2px;">
                            <button type="button" class="btn bg-gradient-success btn-sm" data-toggle="modal" data-target="#modal-agregar-detalle-pension" onclick="limpiar_form_detalle_pension()">
                              <i class="far fa-save"></i> Agregar Detalle
                            </button>
                          </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                          <!-- Tabla principal resumen de las penciones -->
                          <div id="div-tabla-principal">
                            <table id="tabla-pension" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th class="text-center">#</th>
                                  <th><i class="fas fa-cogs"></i></th>
                                  <th>Pension</th>
                                  <th>Descripción</th>
                                  <th>Total</th>
                                  <th>Actualización</th>
                                </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                <tr>
                                  <th class="text-center">#</th>
                                  <th><i class="fas fa-cogs"></i></th> 
                                  <th>Pension</th>
                                  <th>Descripción</th>
                                  <th class="text-right text-nowrap pl-2 pr-2"><div class="formato-numero-conta"><span>S/</span><span id="total_pension"><i class="fas fa-spinner fa-pulse fa-sm"></i></span></div></th>                                  
                                  <th>Actualización</th>
                                </tr>
                              </tfoot>
                            </table>
                          </div>
                          <!-- Registrar pension al sistema -->
                          <div id="div-tabla-detalle" style="display: none;">
                            <table id="tabla-detalle-pension" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th class="text-center">#</th>
                                  <th>OP</th>
                                  <th>Descripción</th>
                                  <th>Fechas</th>
                                  <th>Cant</th>
                                  <th data-toggle="tooltip" data-original-title="Forma de pago">Forma</th>
                                  <th data-toggle="tooltip" data-original-title="Tipo Comprobante">Comprob</th>
                                  <th data-toggle="tooltip" data-original-title="Fecha Emisión">F. Emisión</th>
                                  <th>Sub total</th>
                                  <th>IGV</th>
                                  <th>Total</th>
                                  <th data-toggle="tooltip" data-original-title="Comprobante">CFDI.</th>

                                  <th>Fecha Inicial</th>
                                  <th>Fecha Final</th>
                                  <th>Tipo Comprobante</th>
                                  <th>Número Comprobante</th>
                                  <th>Val. IGV</th>
                                </tr>

                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                <tr>
                                  <th class="text-center">#</th>
                                  <th>OP</th> 
                                  <th>Descripción</th>
                                  <th>Fechas</th>
                                  <th  class="text-right text-nowrap pr-2" id="total_cantidad_personas"><i class="fas fa-spinner fa-pulse fa-sm"></i></th>
                                  <th data-toggle="tooltip" data-original-title="Forma de pago">Forma</th>
                                  <th data-toggle="tooltip" data-original-title="Tipo Comprobante">Comprob</th>
                                  <th data-toggle="tooltip" data-original-title="Fecha Emisión">F. Emisión</th>
                                  <th class="text-right text-nowrap pr-2" id="total_subtotal"><i class="fas fa-spinner fa-pulse fa-sm"></i></th>
                                  <th class="text-right text-nowrap pr-2" id="total_igv"><i class="fas fa-spinner fa-pulse fa-sm"></i></th>
                                  <th class="text-right text-nowrap pr-2" id="total_monto">S/ <i class="fas fa-spinner fa-pulse fa-sm"></i></th>
                                  <th >CFDI.</th>

                                  <th>Fecha Inicial</th>
                                  <th>Fecha Final</th>
                                  <th>Tipo Comprobante</th>
                                  <th>Número Comprobante</th>
                                  <th>Val. IGV</th>
                                </tr>
                              </tfoot>
                            </table>
                          </div>

                          <!-- Listar comprobantes-->
                          <div id="div-tabla-comprobantes" style="display: none;">
                            <table id="tabla-comprobantes" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th class="text-center">#</th>
                                  <th>Aciones</th>
                                  <th data-toggle="tooltip" data-original-title="Forma de pago">Forma</th>
                                  <th data-toggle="tooltip" data-original-title="Tipo Comprobante">Comprob</th>
                                  <th data-toggle="tooltip" data-original-title="Fecha Emisión">F. Emisión</th>
                                  <th>Sub total</th>
                                  <th>IGV</th>
                                  <th>Total</th>
                                  <th>Descripción</th>
                                  <th data-toggle="tooltip" data-original-title="Comprobante">Comprob</th>
                                </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                <tr>
                                  <th class="text-center">#</th>
                                  <th>Aciones</th>
                                  <th data-toggle="tooltip" data-original-title="Forma de pago">Forma</th>
                                  <th data-toggle="tooltip" data-original-title="Tipo Comprobante">Comprob</th>
                                  <th data-toggle="tooltip" data-original-title="Fecha Emisión">F. Emisión</th>
                                  <th>Sub total</th>
                                  <th>IGV</th>
                                  <th class="text-nowrap text-right" id="monto_total_f"></th>
                                  <th>Descripción</th>
                                  <th data-toggle="tooltip" data-original-title="Comprobante">Comprob</th> 
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

                <!--===============Modal agregar pension =========-->
                <div class="modal fade" id="modal-agregar-pension">
                  <div class="modal-dialog modal-dialog-scrollable modal-md">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title edit">Agregar nueva pensión</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form id="form-agregar-pension" name="form-agregar-pension" method="POST">
                          <div class="card-body">
                            <div class="row" id="cargando-1-fomulario">
                              <!-- id semana_break -->
                              <input type="hidden" name="idproyecto_p" id="idproyecto_p" />
                              <!-- id factura_break -->
                              <input type="hidden" name="idpension" id="idpension" />

                              <!-- proveedor -->
                              <div class="col-lg-12">
                                <div class="form-group">
                                  <label>Proveedor <sup class="text-danger">*</sup> </label>
                                  <select name="proveedor" id="proveedor" class="form-control select2" style="width: 100%;"> </select>
                                </div>
                              </div>
                              <!-- Descripcion-->
                              <div class="col-lg-12">
                                <div class="form-group">
                                  <label for="descripcion_pension">Descripción </label> <br />
                                  <textarea name="descripcion_pension" id="descripcion_pension" class="form-control" rows="2"></textarea>
                                </div>
                              </div>
                              <!-- barprogress -->
                              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                                <div class="progress" id="div_barra_progress_pension">
                                  <div id="barra_progress_pension" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
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
                          <button type="submit" style="display: none;" id="submit-form-pension">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_pension();">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro_pension">Guardar Cambios</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!--===============Modal agregar detalle pension =========-->
                <div class="modal fade" id="modal-agregar-detalle-pension">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title edit_detall_pens">Agregar Detalle Pensión</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form class="mx-2" id="form-agregar-detalle-pension" name="form-agregar-detalle-pension" method="POST">
                          
                          <div class="row" id="cargando-5-fomulario">
                            <!-- iddetalle_pension  -->
                            <input type="hidden" name="iddetalle_pension" id="iddetalle_pension" />
                            <!-- id_pension -->
                            <input type="hidden" name="id_pension" id="id_pension" />
                            <!-- tipo_gravada -->
                            <input type="hidden" name="tipo_gravada" id="tipo_gravada" />

                            <!-- Descripcion Pensión-->
                            <div class="col-12 pl-0">
                              <div class="text-primary"><label for="">DETALLES PENSIÓN</label></div>
                            </div>
                            <div class="card col-12 px-3 py-3" style="box-shadow: 0 0 1px rgb(0 0 0), 0 1px 3px rgb(0 0 0 / 60%); ">
                              <div class="row">
                                <!-- Fecha inicial -->
                                <div class="col-lg-4">
                                  <div class="form-group">
                                    <label for="fecha_inicial">Fecha inicial <sup class="text-danger">*</sup></label>
                                    <input class="form-control" type="date" id="fecha_inicial" name="fecha_inicial" onchange="restrigir_fecha_input();" />
                                  </div>
                                </div>

                                <!-- Fecha final -->
                                <div class="col-lg-4">
                                  <div class="form-group">
                                    <label for="fecha_final">Fecha final <sup class="text-danger">*</sup></label>
                                    <input class="form-control" type="date" id="fecha_final" name="fecha_final" />
                                  </div>
                                </div>

                                <!-- Cantidad Persona -->
                                <div class="col-lg-4">
                                  <div class="form-group">
                                    <label for="cantidad_persona">Cantidad Persona</label>
                                    <input class="form-control" type="number" id="cantidad_persona" name="cantidad_persona" placeholder="Cantidad de personas" />
                                  </div>
                                </div>
                              </div>
                            </div>

                            <!-- Descripcion Comporbante-->
                            <div class="col-12 pl-0">
                              <div class="text-primary"><label for="">COMPROBANTE </label></div>
                            </div>
                            <div class="card col-12 px-3 py-3" style="box-shadow: 0 0 1px rgb(0 0 0), 0 1px 3px rgb(0 0 0 / 60%);">
                              <div class="row">
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

                                <!-- Tipo de comprobante -->
                                <div class="col-lg-6" id="content-t-comprob">
                                  <div class="form-group">
                                    <label for="tipo_comprobante">Tipo Comprobante</label>
                                    <select name="tipo_comprobante" id="tipo_comprobante" class="form-control select2" onchange="comprob_factura(); validando_igv();" placeholder="Seleccinar un tipo de comprobante">
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
                                    <label for="codigo">Núm. comprobante </label>
                                    <input type="text" name="nro_comprobante" id="nro_comprobante" class="form-control" placeholder="Código" />
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
                                <div class="col-lg-4">
                                  <div class="form-group">
                                    <label for="subtotal">Sub total</label>
                                    <input class="form-control" type="number" id="subtotal" name="subtotal" placeholder="Sub total" readonly />
                                  </div>
                                </div>

                                <!-- Fecha IGV -->
                                <div class="col-lg-2">
                                  <div class="form-group">
                                    <label for="igv">IGV</label>
                                    <input class="form-control" type="number" id="igv" name="igv" placeholder="IGV" readonly />
                                  </div>
                                </div>

                                <!-- valor IGV -->
                                <div class="col-lg-2">
                                  <div class="form-group">
                                    <label for="val_igv" class="text-gray" style="font-size: 13px;">Valor - IGV </label>
                                    <input type="text" name="val_igv" id="val_igv" value="0.18" class="form-control" readonly onkeyup="calculandototales_fact();" />
                                    
                                  </div>
                                </div>

                                <!-- Monto-->
                                <div class="col-lg-4">
                                  <div class="form-group">
                                    <label for="monto">Total</label>
                                    <input type="number" class="form-control" name="monto" id="monto" onkeyup="comprob_factura();" placeholder="Monto" />
                                  </div>
                                </div>

                                <!-- Descripcion-->
                                <div class="col-lg-12">
                                  <div class="form-group">
                                    <label for="descripcion_detalle">Descripción <sup class="text-danger">*</sup> </label> <br />
                                    <textarea name="descripcion_detalle" id="descripcion_detalle" class="form-control" rows="3"></textarea>
                                  </div>
                                </div>
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
                                  <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(1, 'pension', 'comprobante');"><i class="fas fa-redo"></i> Recargar.</button>
                                </div>
                              </div>
                              <div id="doc1_ver" class="text-center mt-4">
                                <img src="../dist/svg/doc_uploads.svg" alt="" width="50%" />
                              </div>
                              <div class="text-center" id="doc1_nombre"><!-- aqui va el nombre del pdf --></div>
                            </div>

                            <!-- barprogress -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 20px;">
                              <div class="progress" id="div_barra_progress_detalle_pension">
                                <div id="barra_progress_detalle_pension" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                  0%
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="row" id="cargando-6-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                              <br />
                              <h4>Cargando...</h4>
                            </div>
                          </div>
                          
                          <!-- /.card-body -->
                          <button type="submit" style="display: none;" id="submit-form-detalle-pension">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_comprobante();">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro_detalle_pension">Guardar Cambios</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!--===============Modal-ver-vaucher =========-->
                <div class="modal fade" id="modal-ver-comprobante">
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
                          <a class="btn btn-warning btn-block btn-xs" href="#" id="iddescargar" download="factura"  type="button"><i class="fas fa-download"></i></a>
                          <br />
                          <img onerror="this.src='../dist/img/default/img_defecto.png';" src="../dist/img/default/img_defecto.png" class="img-thumbnail" id="img-factura" style="cursor: pointer !important;" width="auto" />
                          <div id="ver_fact_pdf" style="cursor: pointer !important;" width="auto"></div>
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

        <style>        
          .tcuerpo tr td {
            text-align: center !important;
            padding-top: 18px !important;
            border: black 1px solid;
            padding: 0.45rem 0.45rem 0.45rem 0.45rem !important;
          }
        </style>    

        <script type="text/javascript" src="scripts/pension.js"></script> 

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); });</script>

        <?php require 'extra_script.php'; ?>

      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
