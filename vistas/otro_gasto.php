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
        <title>Admin Sevens | Otros Gastos</title>
        
        <?php $title = "Otros Gastos"; require 'head.php'; ?>
          
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
            <div class="content-wrapper">
              <!-- Content Header (Page header) -->
              <section class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1>Otros Gastos</h1>
                    </div>
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Otros Gastos</li>
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
                            <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-otro_gasto" onclick="limpiar();"><i class="fas fa-plus-circle"></i> Agregar</button>
                            Administra de manera eficiente otros Gastos.
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

                          <table id="tabla-otro_gasto" class="table table-bordered table-striped display" style="width: 100% !important;">
                            <thead>
                              <tr>
                                <th colspan="17" class="cargando text-center bg-danger"><i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando... </th>
                              </tr>
                              <tr>
                                <th class="text-center">#</th>
                                <th class="">Acciones</th>
                                <th class="">Proveedor</th>
                                <th data-toggle="tooltip" data-original-title="Forma Pago">Forma P.</th>
                                <th data-toggle="tooltip" data-original-title="Tipo Comprobante">Tipo Comprob</th>
                                <th>Fecha</th>
                                <th>Subtotal</th>
                                <th>IGV</th>
                                <th>Monto Total</th>
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
                                <th class="text-center">#</th>
                                <th class="">Acciones</th>
                                <th class="">Proveedor</th>
                                <th data-toggle="tooltip" data-original-title="Forma Pago">Forma P.</th>
                                <th data-toggle="tooltip" data-original-title="Tipo Comprobante">Tipo Comprob</th>
                                <th>Fecha</th>
                                <th>Subtotal</th>
                                <th>IGV</th>
                                <th class="text-nowrap" id="total_monto"></th>
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

                <!-- Modal agregar otros gastos -->
                <div class="modal fade" id="modal-agregar-otro_gasto">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title"><b>Agregar:</b> comprobante otros gastos</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form id="form-otro_gasto" name="form-otro_gasto" method="POST">
                          <div class="card-body">
                            <div class="row" id="cargando-1-fomulario">
                              <!-- id proyecto -->
                              <input type="hidden" name="idproyecto" id="idproyecto" />
                              <!-- id hospedaje -->
                              <input type="hidden" name="idotro_gasto" id="idotro_gasto" />
                              <!-- Tipo de comprobante -->
                              <!--forma pago-->
                              <div class="col-lg-6">
                                <div class="form-group">
                                  <label for="forma_pago">Forma Pago <sup class="text-danger">*</sup> </label>
                                  <select name="forma_pago" id="forma_pago" class="form-control select2" style="width: 100%;">
                                    <option value="Transferencia">Transferencia</option>
                                    <option value="Efectivo">Efectivo</option>
                                    <option value="Crédito">Crédito</option>
                                  </select>
                                </div>
                              </div>
                              <div class="col-lg-6" id="content-t-comprob">
                                <div class="form-group">
                                  <label for="tipo_comprobante">Tipo Comprobante  <sup class="text-danger">*</sup></label>
                                  <select name="tipo_comprobante" id="tipo_comprobante" class="form-control select2" onchange="delay(function(){select_comprobante();calc_total();}, 100 );" placeholder="Seleccinar un tipo de comprobante">
                                    <option value="Ninguno">Ninguno</option>
                                    <option value="Boleta">Boleta</option>
                                    <option value="Factura">Factura</option>
                                    <option value="Nota de venta">Nota de venta</option>
                                  </select>
                                </div>
                              </div> 
                              <!-- RUC style="display: none;"-->
                              <div class="col-lg-4 div_ruc" style="display: none;"  >
                                <div class="form-group">
                                  <label for="num_documento">R.U.C <sup class="text-danger">*</sup> <small class="text-danger text-lowercase"> (Único)</small></label>
                                  <div class="input-group">
                                    <input type="hidden" id="tipo_documento" value="RUC">
                                    <input type="number" name="num_documento" class="form-control" id="num_documento" placeholder="N° de documento" onchange="delay(function(){buscar_sunat_reniec('')}, 150 );" onkeyup="delay(function(){buscar_sunat_reniec('')}, 300 );" />
                                    <div class="input-group-append" data-toggle="tooltip" data-original-title="Buscar razón social" onclick="buscar_sunat_reniec('');">
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
                                  <label class="razon_social" for="razon_social">Razón social</label>
                                  <input type="text" name="razon_social" id="razon_social" class="form-control" placeholder="Razón social" readonly/>
                                  <input type="hidden" name="direccion" id="direccion"   />
                                </div>
                              </div>
                              <!-- Glosa-->
                              <div class="col-lg-4" id="content-t-comprob">
                                <div class="form-group">
                                  <label for="glosa">Selecc. Glosa  <sup class="text-danger">*</sup></label>
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
                                    <option value="DONACIONES">DONACIONES</option>
                                    <option value="SERVICIOS DE PROYECTOS">SERVICIOS DE PROYECTOS</option>
                                    <option value="OTROS">OTROS</option>
                                    
                                  </select>
                                </div>
                              </div>
                              <!-- Código-->
                              <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="nro_comprobante"><span class="nro_comprobante">Núm. comprobante</span> <small class="text-danger text-lowercase"> (Único)</small> </label>
                                  <input type="text" name="nro_comprobante" id="nro_comprobante" class="form-control" placeholder="Código" />
                                </div>
                              </div>

                              <!-- Fecha 1  -->
                              <div class="col-lg-4 class_pading">
                                <div class="form-group">
                                  <label for="fecha_g">Fecha Emisión  <sup class="text-danger">*</sup></label>
                                  <input type="date" name="fecha_g" class="form-control" id="fecha_g" />
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
                                  <input class="form-control igv" type="number" id="igv" name="igv" placeholder="IGV" readonly />
                                </div>
                              </div>
                              <!-- valor IGV -->
                              <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="val_igv" class="text-gray" style="font-size: 13px;">Valor - IGV </label>
                                    <input type="text" name="val_igv" id="val_igv" value="0.18" class="form-control" onkeyup="delay(function(){calc_total();}, 1300 );" onchange="delay(function(){calc_total();}, 100 );"> 
                                    <input class="form-control" type="hidden"  id="tipo_gravada" name="tipo_gravada"/>
                                </div>
                              </div>
                              <!--Precio Parcial-->
                              <div class="col-lg-4 class_pading">
                                <div class="form-group">
                                  <label for="marca">Monto total  <sup class="text-danger">*</sup></label>
                                  <input type="number" name="precio_parcial" id="precio_parcial" class="form-control" onkeyup="delay(function(){calc_total();}, 100 );" onchange="delay(function(){calc_total();}, 100 );" placeholder="Precio Parcial" />                                  
                                </div>
                              </div>

                              <!--Descripcion-->
                              <div class="col-lg-12 class_pading">
                                <div class="form-group">
                                  <label for="descripcion_pago">Descripción <sup class="text-danger">*</sup></label> <br />
                                  <textarea name="descripcion" id="descripcion" class="form-control" rows="2"></textarea>
                                </div>
                              </div>

                              <!-- Factura -->
                              <div class="col-md-6" >                               
                                <div class="row text-center">
                                  <div class="col-md-12" style="padding-top: 15px; padding-bottom: 5px;">
                                    <label for="cip" class="control-label" > Baucher de deposito </label>
                                  </div>
                                  <div class="col-6 col-md-6 text-center">
                                    <button type="button" class="btn btn-success btn-block btn-xs" id="doc1_i"><i class="fas fa-upload"></i> Subir.</button>
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
                          <button type="submit" style="display: none;" id="submit-form-otro_gasto">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar();">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro">Guardar Cambios</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!--===============Modal-ver-comprobante =========-->
                <div class="modal fade" id="modal-ver-comprobante">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Otros gastos: <span class="nombre_comprobante text-bold"></span> </h4>
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

                <!--Modal ver datos-->
                <div class="modal fade" id="modal-ver-otro_gasto">
                  <div class="modal-dialog modal-dialog-scrollable modal-xm">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Detalles</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <div id="datosotro_gasto" class="class-style">
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

        <script type="text/javascript" src="scripts/otro_gasto.js"></script>

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>
        
      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
