<?php
//Activamos el almacenamiento en el buffer
ob_start();

session_start();
if (!isset($_SESSION["nombre"])) {
  header("Location: index.php?file=" . basename($_SERVER['PHP_SELF']));
} else {
?>

  <!DOCTYPE html>
  <html lang="es">

  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>E.P.P | Admin Sevens</title>

    <?php $title = "Equipos de Protección Personal";
    require 'head.php'; ?>

  </head>

  <body class="hold-transition sidebar-collapse sidebar-mini layout-fixed layout-navbar-fixed">
    <!-- Content Wrapper. Contains page content -->
    <div class="wrapper">
      <?php
      require 'nav.php';
      require 'aside.php';
      if ($_SESSION['otro_gasto'] == 1) {
        //require 'enmantenimiento.php';
      ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
          <!-- Main content -->
          <section class="content">
            <div class="container-fluid">
              <div class="row">
                <div class="col-12">
                  <div class="card card-primary card-tabs">
                    <div class="card-header p-0 pt-1">
                      <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true"> <i class="fa-solid fa-wand-magic-sparkles"></i> RESUMEN E.P.P</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false"><i class="fa-solid fa-sliders"></i> DETALLE E.P.P</a>
                        </li>
                      </ul>
                    </div>
                    <div class="card-body">
                      <div class="tab-content" id="custom-tabs-one-tabContent">
                        <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                          <table id="tabla-resumen-epp-x-tpp" class="table table-bordered table-striped display" style="width: 100% !important;">
                            <thead>
                              <tr>
                                <th colspan="7" class="cargando text-center bg-danger"><i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando... </th>
                              </tr>
                              <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Code</th>
                                <th class="">INSUMOS</th>
                                <th class="">UND</th>
                                <th class="">CANTIDAD REPARTIDA</th>
                                <th class="">E.P.P</th>
                                <th class="">CANTIDAD EN ALMACÉN</th>
                              </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                              <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Code</th>
                                <th class="">INSUMOS</th>
                                <th class="">UND</th>
                                <th class="">CANTIDAD REPARTIDA</th>
                                <th class="">E.P.P</th>
                                <th class="">CANTIDAD EN ALMACÉN</th>
                              </tr>
                            </tfoot>
                          </table>
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                          <div class="row">

                            <div class="col-4">

                              <table id="tabla-epp" class="table table-bordered table-striped display" style="width: 100% !important;">
                                <thead>
                                  <tr>
                                    <th colspan="3" class="cargando text-center bg-danger"><i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando... </th>
                                  </tr>
                                  <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Nombre</th>
                                    <th class="">Talla</th>
                                  </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                  <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Nombre</th>
                                    <th class="">Talla</th>
                                  </tr>
                                </tfoot>
                              </table>
                            </div>

                            <div class="col-8">

                              <div style="margin: 5px; color: #1f2d3d; background-color: #f8f9fa; border-color: #e9ecef;">

                                <div class="modal-header">
                                  <h6> TRABAJADOR:<strong class="nombre_epp"> <i class="fas fa-exclamation-triangle text-warning"></i> </strong> </h6>
                                  <h6> Talla:<strong class="tallas"> <i class="fas fa-exclamation-triangle text-warning"></i> </strong> </h6>
                                  <button type="button" class="btn bg-gradient-primary btn-sm btn_add_epps" style="display: none;" data-toggle="modal" data-target="#modal-agregar-epp" onclick="limpiar();"><i class="fas fa-plus-circle"></i> Agregar</button></button>
                                </div>
                              </div>

                              <div class="alert alert-secondary alerta_inicial" role="alert" style=" background-color: #ffe69c; border-color: #ffe69c; color: black;">
                                <h2 class="alert-heading">¡UPS! Ningún Trabajador seleccionado.</strong></h2>
                                <hr>
                                <p class="mb-0">Tiene que seleccionar un Tarabajador para poder ver sus E.E.P asigandos.</p>
                              </div>

                              <div class="tabla_epp_x_tpp" style="display: none; ">

                                <table id="tabla-epp-x-tpp" class="table table-bordered table-striped display" style="width: 100% !important;">
                                  <thead>
                                    <tr>
                                      <th colspan="4" class="cargando text-center bg-danger"><i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando... </th>
                                    </tr>
                                    <tr>
                                      <th class="text-center">#</th>
                                      <th class="text-center">Acciones</th>
                                      <th class="text-center">Descripción</th>
                                      <th class="text-center">Marca</th>
                                      <th class="">Cantidad</th>
                                      <th class="">Fecha</th>
                                    </tr>
                                  </thead>
                                  <tbody></tbody>
                                  <tfoot>
                                    <tr>
                                      <th class="text-center">#</th>
                                      <th class="text-center">Acciones</th>
                                      <th class="text-center">Descripción</th>
                                      <th class="text-center">Marca</th>
                                      <th class="">Cantidad</th>
                                      <th class="">Fecha</th>
                                    </tr>
                                  </tfoot>
                                </table>
                              </div>

                            </div>

                          </div>

                        </div>

                      </div>
                    </div>
                    <!-- /.card -->
                  </div>
                </div>

              </div>
              <!-- /.container-fluid -->

              <!-- Modal agregar otros epp -->
              <div class="modal fade" id="modal-agregar-epp">
                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title"><b>Agregar:</b> E.P.P PARA <b class="nombre_trab_modal text-primary"></b> </h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <div class="modal-body">
                      <!-- form start -->
                      <form id="form-epp" name="form-epp" method="POST">
                        <div class="card-body">
                          <div class="row" id="cargando-1-fomulario">
                            <!-- id proyecto -->
                            <input type="hidden" name="idproyecto" id="idproyecto" />
                            <!-- trabajador_por_proyecto -->
                            <input type="hidden" name="idtrabajador_por_proyecto" id="idtrabajador_por_proyecto" />
                            <!-- id hospedaje -->
                            <input type="hidden" name="idepp" id="idepp" />
                            <!-- SELECT EPP -->
                            <div class="col-lg-8" id="content-t-comprob">
                              <div class="form-group">
                                <label for="select_id_insumo">Seleccionar Epp <sup class="text-danger">*</sup></label>
                                <select name="select_id_insumo" id="select_id_insumo" class="form-control select2" onchange="add_row(this);" placeholder="Seleccinar un tipo de comprobante">
                                </select>
                              </div>
                            </div>
                            <!-- Fecha 1  -->
                            <div class="col-lg-4 class_pading">
                              <div class="form-group">
                                <label for="fecha_g">Fecha <sup class="text-danger">*</sup></label>
                                <input type="date" name="fecha_g" class="form-control" id="fecha_g" />
                              </div>
                            </div>
                            <div class="col-12 pl-0">
                              <div class="text-primary"><label for="">E.P.P Seleccionados </label></div>
                            </div>

                            <div class="card col-12 px-3 py-3 codigoGenerado" style="box-shadow: 0 0 1px rgb(0 0 0), 0 1px 3px rgb(0 0 0 / 60%);">
                              <!-- agregando -->
                              <div class="alert alert-warning alert-dismissible alerta">
                                <h5><i class="icon fas fa-exclamation-triangle"></i> Alerta!</h5>
                                NO TIENES NINGÚN EQUIPO DE PROTECCIÓN PERSONAL SELECCIONADO.
                              </div>
                            </div>

                            <!-- barprogress -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                              <div class="progress" id="barra_progress_epp_div">
                                <div id="barra_progress_epp" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
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
                        <button type="submit" style="display: none;" id="submit-form-epp">Submit</button>
                      </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar();">Close</button>
                      <button type="submit" class="btn btn-success" id="guardar_registro">Guardar Cambios</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- MODAL - VER ALMACEN - chargue 3 -->
              <div class="modal fade" id="modal-ver-editar-epp">
                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">
                        Editar E.P.P</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <div class="modal-body">

                      <!-- form start -->
                      <form id="form-almacen-x-dia" name="form-almacen-x-dia" method="POST">
                        <div class="card-body">
                          <div class="row" id="cargando-3-fomulario">
                            <!-- id trabajador -->
                            <input type="hidden" name="idalmacen_x_proyecto_xp" id="idalmacen_x_proyecto_xp" />
                            <input type="hidden" name="idtrabajador_xp" id="idtrabajador_xp" />

                            <!-- Producto -->
                            <div class="col-12 col-sm-12 col-md-6 col-lg-12">
                              <div class="form-group">
                                <label for="producto_xp">Seleccionar E.P.P <span class="cargando_productos"></span> </label>
                                <select name="producto_xp" id="producto_xp" class="form-control" placeholder="Producto">
                                </select>
                              </div>
                            </div>
                            <!-- Fecha -->
                            <div class="col-12 col-sm-12 col-md-6 col-lg-8">
                              <div class="form-group">
                                <label for="epp_xp">E.P.P</label>
                                <input type="text" id="epp_xp" class="form-control" placeholder="E.P.P" onchange="obtener_dia_ingreso(this);" readonly />
                                <input type="hidden" name="id_producto_xp" id="id_producto_xp" />
                              </div>
                            </div>
                            
                            <!-- Fecha -->
                            <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                              <div class="form-group">
                                <label for="fecha_ingreso">Fecha</label>
                                <input type="date" name="fecha_ingreso_xp" id="fecha_ingreso_xp" class="form-control" placeholder="Fecha" onchange="obtener_dia_ingreso(this);" />
                                <!-- <input type="hidden" name="dia_ingreso_xp" id="dia_ingreso_xp" /> -->
                              </div>
                            </div>

                            <!-- Marcas -->
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                              <div class="form-group">
                                <label for="marca_xp">Marcas</label>
                                <select name="marca_xp" id="marca_xp" class="form-control"></select>
                              </div>
                            </div>

                            <!-- Cantidad -->
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                              <div class="form-group">
                                <label for="cantidad_xp">Cantidad</label>
                                <input type="number" name="cantidad_xp" class="form-control" id="cantidad_xp" placeholder="Cantidad" />
                              </div>
                            </div>

                            <!-- barprogress -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;" id="barra_progress_almacen_x_dia_div">
                              <div class="progress">
                                <div id="barra_progress_almacen_x_dia" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                  0%
                                </div>
                              </div>
                            </div>

                          </div>

                          <div class="row" id="cargando-4-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center"><i class="fas fa-spinner fa-pulse fa-6x"></i><br /><br />
                              <h4>Cargando...</h4>
                            </div>
                          </div>
                        </div>
                        <!-- /.card-body -->
                        <button type="submit" style="display: none;" id="submit-form-almacen-x-dia">Submit</button>
                      </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-danger" onclick="limpiar_form_almacen_x_dia();" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-success" id="guardar_registro_almacen_x_dia" style="display: none;">Guardar Cambios</button>
                    </div>
                  </div>
                </div>
              </div>


          </section>
          <!-- /.content -->
        </div>

      <?php
      } else {
        require 'noacceso.php';
      }
      require 'footer.php';
      ?>
    </div>
    <!-- /.content-wrapper -->

    <?php require 'script.php'; ?>

    <script type="text/javascript" src="scripts/epp.js"></script>

    <script>
      $(function() {
        $('[data-toggle="tooltip"]').tooltip();
      });
    </script>

  </body>

  </html>

<?php
}
ob_end_flush();

?>