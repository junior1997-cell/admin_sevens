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
        <title>Admin Sevens | Comidas Extras</title>

        <?php $title = "Comidas Extras"; require 'head.php'; ?>    

        <!--CSS  switch_MATERIALES-->
        <link rel="stylesheet" href="../dist/css/switch_materiales.css">

      </head>
      <body class="hold-transition sidebar-collapse sidebar-mini layout-fixed layout-navbar-fixed">
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
                      <h1>Comidas Extras</h1>
                    </div>
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Comidas Extras</li>
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
                            <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-comidas_ex" onclick="limpiar();"><i class="fas fa-plus-circle"></i> Agregar</button>
                            Administra comidas extras.
                          </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                          
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

                          <table id="tabla-comidas_extras" class="table table-bordered table-striped display" style="width: 100% !important;">
                            <thead>
                              <tr>
                                <th colspan="17" class="cargando text-center bg-danger"><i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando... </th>
                              </tr>
                              <tr>
                                <th class="">#</th>
                                <th class="">Acciones</th>
                                <th>Fecha</th>
                                <th>Proveedor</th>
                                <th data-toggle="tooltip" data-original-title="Tipo Comprobante">Comprob</th>
                                <th data-toggle="tooltip" data-original-title="Forma Pago">Forma P.</th>
                                <th>Sub total</th>
                                <th>Igv</th>
                                <th>Total</th>
                                <th>Descripción</th>
                                <th data-toggle="tooltip" data-original-title="Comprobante">Comprob</th>
                                <th>R.U.C</th>
                                <th>Razón social</th>
                                <th>Dirección</th>
                                <th>Tipo comprobante</th>
                                <th>N° comprobante</th>
                                <th>T. grabada</th>
                                <th>Glosa</th>
                              </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                              <tr>
                                <th class="">#</th>
                                <th class="">Acciones</th>
                                <th>Fecha</th>
                                <th>Proveedor</th>
                                <th data-toggle="tooltip" data-original-title="Tipo Comprobante">Comprob</th>
                                <th data-toggle="tooltip" data-original-title="Forma Pago">Forma P.</th>
                                <th>Sub total</th>
                                <th>Igv</th>
                                <th style="background-color: #ffdd00;" class="text-right text-nowrap" id="total_monto"><i class="fas fa-spinner fa-pulse fa-1x"> </i></th>
                                <th>Descripción</th>
                                <th data-toggle="tooltip" data-original-title="Comprobante">Comprob</th>
                                <th>R.U.C</th>
                                <th>Razón social</th>
                                <th>Dirección</th>
                                <th>Tipo comprobante</th>
                                <th>N° comprobante</th>
                                <th>T. grabada</th>
                                <th>Glosa</th>
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
                <div class="modal fade" id="modal-agregar-comidas_ex">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title"><b>Agregar: </b> Comida extra</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form id="form-comidas_ex" name="form-comidas_ex" method="POST">
                          <div class="card-body">
                            <div class="row" id="cargando-1-fomulario">
                              <!-- id proyecto -->
                              <input type="hidden" name="idproyecto" id="idproyecto" />
                              <!-- id hospedaje -->
                              <input type="hidden" name="idcomida_extra" id="idcomida_extra" />
                              <input type="hidden" name="tipo_documento" id="tipo_documento" value="RUC"/>

                              <!-- Fecha 1 onchange="calculando_cantidad(); restrigir_fecha_ant();" onkeyup="calculando_cantidad(); -->
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

                              <!-- Tipo de comprobante -->
                              <div class="col-lg-6" id="content-t-comprob">
                                <div class="form-group">
                                  <label for="tipo_comprobante">Tipo Comprobante <sup class="text-danger">*</sup> <small class="text-danger text-lowercase"> (Único)</small></label>
                                  <select name="tipo_comprobante" id="tipo_comprobante" class="form-control select2" onchange="delay(function(){select_comprobante();calc_total();}, 100 );" placeholder="Seleccinar un tipo de comprobante">
                                    <option value="Ninguno">Ninguno</option>
                                    <option value="Boleta">Boleta</option>
                                    <option value="Factura">Factura</option>
                                    <option value="Nota de venta">Nota de venta</option>
                                  </select>
                                </div>
                              </div>

                              <!-- RUC style="display: none;"-->
                              <div class="col-lg-4 div_ruc" style="display: none;">
                                <div class="form-group">
                                  <label for="num_documento">R.U.C <small class="text-danger text-lowercase"> (Único)</small></label>
                                  <div class="input-group">
                                    <input type="number" name="num_documento" class="form-control" id="num_documento" placeholder="N° de documento" onchange="delay(function(){buscar_sunat_reniec('')}, 150 );" onkeyup="delay(function(){buscar_sunat_reniec('')}, 150 );" />
                                    <div class="input-group-append" data-toggle="tooltip" data-original-title="Buscar razón social" onclick="buscar_sunat_reniec();">
                                      <span class="input-group-text" style="cursor: pointer;">
                                        <i class="fas fa-search text-primary" id="search"></i>
                                        <i class="fa fa-spinner fa-pulse fa-fw fa-lg text-primary" id="charge" style="display: none;"></i>
                                      </span>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <!-- Razón social-->
                              <div class="col-lg-8 div_razon_social" style="display: none;">
                                <div class="form-group">
                                  <label class="razon_social" for="razon_social">Razón social </label>
                                  <input type="text" name="razon_social" id="razon_social" class="form-control" placeholder="Razón social" readonly />
                                  <input type="hidden" name="direccion" id="direccion" />
                                </div>
                              </div>

                              <!-- Código-->
                              <div class="col-lg-6">
                                <div class="form-group">
                                  <label for="codigo"><span class="nro_comprobante">Núm. comprobante</span> <small class="text-danger text-lowercase"> (Único)</small> </label>
                                  <input type="text" name="nro_comprobante" id="nro_comprobante" class="form-control" placeholder="Código" />
                                </div>
                              </div>

                              <!--Fecha-->
                              <div class="col-lg-6 class_pading">
                                <div class="form-group">
                                  <label for="fecha">Fecha <sup class="text-danger">*</sup></label>
                                  <input type="date" name="fecha" class="form-control" id="fecha" />
                                </div>
                              </div>
                              <!-- Sub total -->
                              <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="subtotal">Sub total <small class="text-danger tipo_gravada text-lowercase"></small></label>
                                  <input class="form-control" type="number" id="subtotal" name="subtotal" placeholder="Sub total" readonly />
                                </div>
                              </div>
                              <!-- IGV -->
                              <div class="col-lg-2">
                                <div class="form-group">
                                  <label for="igv">IGV</label>
                                  <input class="form-control" id="igv" name="igv" type="number" placeholder="IGV" readonly />
                                </div>
                              </div>
                              <!-- valor IGV -->
                              <div class="col-lg-2">
                                <div class="form-group">
                                  <label for="val_igv" class="text-gray" style="font-size: 13px;">Valor - IGV </label>
                                  <input type="text" name="val_igv" id="val_igv" value="0.18" class="form-control" readonly onkeyup="delay(function(){calc_total();}, 100 );" onchange="delay(function(){calc_total();}, 100 );" />
                                  <input class="form-control" type="hidden" id="tipo_gravada" name="tipo_gravada" />
                                </div>
                              </div>
                              <!--Precio Parcial-->
                              <div class="col-lg-4 class_pading">
                                <div class="form-group">
                                  <label for="marca">Monto total </label>
                                  <input type="number" class="form-control" name="precio_parcial" id="precio_parcial" onkeyup="delay(function(){calc_total();}, 100 );" onchange="delay(function(){calc_total();}, 100 );" placeholder="Precio Parcial" />
                                </div>
                              </div>
                              <!--Descripcion-->
                              <div class="col-lg-12 class_pading">
                                <div class="form-group">
                                  <label for="descripcion_pago">Descripción <sup class="text-danger">*</sup> <span style="font-size: 12px; font-weight: normal;">ej. Almuerzo, aniversario de la empresa</span> </label> <br />
                                  <textarea name="descripcion" id="descripcion" class="form-control" rows="2"></textarea>
                                </div>
                              </div>
                              <!-- Factura -->
                              <div class="col-md-6">
                                <div class="row text-center">
                                  <div class="col-md-12" style="padding-top: 15px; padding-bottom: 5px;">
                                    <label for="cip" class="control-label"> Comprobante </label>
                                  </div>
                                  <div class="col-6 col-md-6 text-center">
                                    <button type="button" class="btn btn-success btn-block btn-xs" id="doc1_i"><i class="fas fa-upload"></i> Subir.</button>
                                    <input type="hidden" id="doc_old_1" name="doc_old_1" />
                                    <input style="display: none;" id="doc1" type="file" name="doc1" accept="application/pdf, image/*" class="docpdf" />
                                  </div>
                                  <div class="col-6 col-md-6 text-center">
                                    <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(1, 'comida_extra', 'comprobante');"><i class="fas fa-redo"></i> Recargar.</button>
                                  </div>
                                </div>
                                <div id="doc1_ver" class="text-center mt-4">
                                  <img src="../dist/svg/doc_uploads.svg" alt="" width="50%" />
                                </div>
                                <div class="text-center" id="doc1_nombre"><!-- aqui va el nombre del pdf --></div>
                              </div>

                              <!-- barprogress -->
                              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                                <div class="progress" id="div_barra_progress">
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
                          <button type="submit" style="display: none;" id="submit-form-comidas-ex">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar();">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro">Guardar Cambios</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!--===============Modal-ver-ficha-tècnica =========-->
                <div class="modal fade" id="modal-ver-comprobante">
                  <div class="modal-dialog modal-dialog-scrollable modal-xl">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Comprobante : <span class="nombre_comprobante"></span></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                          <div class="row">
                            <div class="col-6 col-md-6">
                              <a class="btn btn-xs btn-block btn-warning" href="#" id="iddescargar" download="" type="button"><i class="fas fa-download"></i> Descargar</a>
                            </div>
                            <div class="col-6 col-md-6">
                              <a class="btn btn-xs btn-block btn-info" href="#" id="ver_completo"  target="_blank" type="button"><i class="fas fa-expand"></i> Ver completo.</a>
                            </div>
                            <div class="col-12 col-md-12 mt-2">
                              <div id="ver_fact_pdf" width="auto"></div>
                            </div>
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
        <script type="text/javascript" src="scripts/comidas_extras.js"></script>

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>
          
      </body>
    </html>

    <?php  
  }

  ob_end_flush();

?>
