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
        <title>Mano de Obra | Admin Sevens</title>

        <?php $title = "Mano de Obra"; require 'head.php'; ?>
        
      </head>
      <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed pace-orange" idproyecto="<?php echo $_SESSION['idproyecto']; ?>">
        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper">
          <?php
          require 'nav.php';
          require 'aside.php';
          if ($_SESSION['mano_obra']==1){
            //require 'enmantenimiento.php';
            ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
              <!-- Content Header (Page header) -->
              <section class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1 id="name_pension_head" ><i class="nav-icon fa-solid fa-person-digging"></i> Mano de Obra</h1>
                    </div>
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Mano de Obra</li>
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
                            <button type="button" class="btn bg-gradient-success btn-sm" data-toggle="modal" data-target="#modal-agregar-mdo" onclick="limpiar_form_mdo()" >
                              <i class="fa fa-plus"></i> Agregar
                            </button>
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
                                <select id="filtro_tipo_comprobante" class="form-control select2" disabled  style="width: 100%;"> 
                                  <option value="0">Todos</option>
                                  <option value="Ninguno">Ninguno</option>
                                  <option value="Boleta">Boleta</option>
                                  <option value="Factura">Factura</option>
                                  <option value="Nota de venta">Nota de venta</option>
                                </select>
                              </div>                                
                            </div>
                          </div>

                          <!-- Tabla principal resumen de las penciones -->
                          <div id="div-tabla-principal">
                            <table id="tabla-mdo" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th class="text-center">#</th>
                                  <th class="text-center"><i class="fas fa-cogs"></i></th>
                                  <th class="text-center">Fecha</th>
                                  <th>Proveedor</th>
                                  <th class="text-center">Fecha Inicial</th>
                                  <th class="text-center">Fecha Final</th>
                                  <th>Total</th>
                                  <th>Descripción</th> 

                                  <th>Razón Social</th>
                                  <th>Tipo Doc.</th>
                                  <th>Num. Doc.</th>
                                </tr>
                              </thead>
                              <tbody></tbody> 
                              <tfoot>
                                <tr>
                                  <th class="text-center">#</th>
                                  <th class="text-center"><i class="fas fa-cogs"></i></th>
                                  <th class="text-center">Fecha</th>
                                  <th>Proveedor</th>
                                  <th class="text-center">Fecha Inicial</th>
                                  <th class="text-center">Fecha Final</th>
                                  <th class="text-nowrap px-2"><div class="formato-numero-conta"><span>S/</span><span id="total_pension">0.00</span></div></th>        
                                  <th>Descripción</th>

                                  <th>Razón Social</th>
                                  <th>Tipo Doc.</th>
                                  <th>Num. Doc.</th>
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

                <!-- MODAL - AGREGAR MANO DE OBRA -->
                <div class="modal fade" id="modal-agregar-mdo">
                  <div class="modal-dialog modal-dialog-scrollable modal-md">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title name-modal-header">Agregar</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form id="form-agregar-mdo" name="form-agregar-mdo" method="POST">
                          <div class="card-body">
                            <div class="row" id="cargando-1-fomulario">
                              <!-- id semana_break -->
                              <input type="hidden" name="idproyecto" id="idproyecto" />
                              <!-- id idmano_de_obra -->
                              <input type="hidden" name="idmano_de_obra" id="idmano_de_obra" />

                              <!-- proveedor -->
                              <div class="col-lg-12">
                                <div class="form-group">
                                  <label>Proveedor <sup class="text-danger">*</sup> </label>
                                  <select name="idproveedor" id="idproveedor" class="form-control select2" onchange="extrae_ruc();" style="width: 100%;"> </select>
                                </div>
                                <input type="hidden" name="ruc_proveedor" id="ruc_proveedor" />
                              </div>

                              <!-- Fecha inicial -->
                              <div class="col-lg-6">
                                <div class="form-group">
                                  <label for="fecha_inicial">Fecha inicial <sup class="text-danger">*</sup></label>
                                  <input class="form-control" type="date" id="fecha_inicial" name="fecha_inicial" onchange="restrigir_fecha_input();" />
                                </div>
                              </div>

                              <!-- Fecha final -->
                              <div class="col-lg-6">
                                <div class="form-group">
                                  <label for="fecha_final">Fecha final <sup class="text-danger">*</sup></label>
                                  <input class="form-control" type="date" id="fecha_final" name="fecha_final" />
                                </div>
                              </div>

                              <!-- Fecha deposito -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                  <label for="fecha_deposito">Fecha deposito <sup class="text-danger">*</sup></label>
                                  <input class="form-control" type="date" id="fecha_deposito" name="fecha_deposito" />
                                </div>
                              </div>

                              <!-- Monto-->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                  <label for="monto">Monto <sup class="text-danger">*</sup></label>
                                  <input type="text" name="monto" class="form-control" id="monto" placeholder="Monto" />
                                </div>
                              </div>

                              <!-- Descripcion-->
                              <div class="col-lg-12">
                                <div class="form-group">
                                  <label for="descripcion">Descripción </label> <br />
                                  <textarea name="descripcion" id="descripcion" class="form-control" rows="2"></textarea>
                                </div>
                              </div>

                              <!-- barprogress -->
                              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                                <div class="progress" id="div_barra_progress_mdo">
                                  <div id="barra_progress_mdo" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
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
                          <button type="submit" style="display: none;" id="submit-form-mdo">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_form_mdo();">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro_mdo">Guardar Cambios</button>
                      </div>
                    </div>
                  </div>
                </div>  
                
                <!-- MODAL - VER DETALLE MANO DE OBRA-->
                <div class="modal fade" id="modal-ver-detalle-mdo">
                  <div class="modal-dialog modal-dialog-scrollable modal-md">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Datos del Insumo</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <div id="html_detalle_mdo" class="class-style">
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

        <script type="text/javascript" src="scripts/mano_de_obra.js?version_jdl=1.9"></script> 

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); });</script>

      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
