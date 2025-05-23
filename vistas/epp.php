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

  <body class="hold-transition sidebar-collapse sidebar-mini layout-fixed layout-navbar-fixed pace-orange" idproyecto="<?php echo $_SESSION['idproyecto']; ?>">
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
                          <div class="card-header">
                            <h3 class="card-title regresar" style="display: none;">
                              <button type="button" class="btn bg-gradient-warning" onclick="table_show_hide(1);">
                                <i class="fas fa-arrow-left"></i> Regresar
                              </button> <strong class="nombre_produc" style="font-size: 22px;"></strong> - <span class="marca_produc" style="font-size: 18px;"></span>

                            </h3>
                          </div>
                          <div class="tbl_resumen_epp_x_tpp">

                            <table id="tabla-resumen-epp-x-tpp" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th colspan="7" class="cargando text-center bg-danger"><i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando... </th>
                                </tr>
                                <tr>
                                  <th class="text-center">#</th>
                                  <th class="text-center">CODE</th>
                                  <th class="">INSUMOS - MARCA</th>
                                  <th class="">UND</th>
                                  <th class="">CANT. TOTAL</th>
                                  <th class="">CANT. REPARTIDA</th>
                                  <th class="">SALDO</th>
                                  <th class="">E.P.P</th>
                                </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                <tr>
                                  <th class="text-center">#</th>
                                  <th class="text-center">CODE</th>
                                  <th class="">INSUMOS - MARCA</th>
                                  <th class="">UND</th>
                                  <th class="">CANT. TOTAL</th>
                                  <th class="">CANT. REPARTIDA</th>
                                  <th class="">SALDO</th>
                                  <th class="">E.P.P</th>
                                </tr>
                              </tfoot>
                            </table>
                          </div>
                          <div class="tbl_detalle_epp" style="display: none;">

                            <table id="tbla_detalle_epp" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th colspan="4" class="cargando text-center bg-danger"><i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando... </th>
                                </tr>
                                <tr>
                                  <th class="text-center">#</th>
                                  <th class="text-center">NOMBRE</th>
                                  <th class="">CANTIDAD</th>
                                  <th class="">FECHA</th>
                                </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                <tr>
                                  <th class="text-center">#</th>
                                  <th class="text-center">NOMBRE</th>
                                  <th class="">CANTIDAD</th>
                                  <th class="">FECHA</th>
                                </tr>
                              </tfoot>
                            </table>

                          </div>

                        </div>
                        <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                          <div class="row">

                            <div class="col-4">

                              <a type="button" class="btn btn-block btn-outline-info" id="btn_export_epp_full"> <i class="fa-regular fa-file-excel"></i> EXPORTAR EPPS DE TODOS LOS TRABAJADORES </a> <br>

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

                                  <a type="button" class="btn bg-gradient-gray btn-sm " id="btn_export_eppxt" style="display: none;"> <i class="fa-regular fa-file-excel"></i> Exportar EPP </a>

                                  <button type="button" class="btn bg-gradient-primary btn-sm btn_add_epps" style="display: none;" data-toggle="modal" data-target="#modal-agregar-epp" onclick="limpiar();"><i class="fas fa-plus-circle"></i> Agregar</button>
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
                                        <th class="text-center">U.M</th>
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
                                        <th class="text-center">U.M</th>
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
                  <div class="modal-dialog modal-dialog-scrollable modal-xl">
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


                              <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="pl-2" style="position: relative; top: 10px; z-index: +1; letter-spacing: 2px;"><span class="bg-white text-primary" for=""> <b class="mx-2">E.P.P - AGREGADOS</b> </span></div>
                              </div>

                              <div class="col-12 col-sm-12 col-md-12 col-lg-12 ">
                                <div class="card px-3 py-3" style="box-shadow: 0 0 1px rgb(0 0 0), 0 1px 3px rgb(0 0 0 / 60%);">
                                  <div class="row head_list" > <!-- style="display: none;" -->
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 mt-2 mb-2 text-bold">Nombre Producto</div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-1 mt-2 mb-2 text-bold">Unidad</div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-2 mt-2 mb-2 text-bold">Marca</div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-2 mt-2 mb-2 text-bold">Cantidad</div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-1 mt-2 mb-2 text-bold"></div>
                                    <!-- <textarea name="" id="" cols="30" rows="10"></textarea> -->
                                  </div>
                                  <div class="codigoGenerado" id="html_producto_ag">
                                    <div class="col-12  col-lg-12 html_mensaje alerta">
                                      <div class="alert alert-warning alert-dismissible mb-0">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="icon fas fa-exclamation-triangle "></i> Alerta!</h5>
                                        NO TIENES NINGÚN PRODUCTO SELECCIONADO.
                                      </div>
                                    </div>
                                  </div>
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

                <!-- MODAL - editar EPP - chargue 3 -->
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
                        <form id="form-editar-x-epp" name="form-editar-x-epp" method="POST">
                          <div class="card-body">
                            <div class="row" id="cargando-3-fomulario">
                              <!-- id trabajador -->
                              <input type="hidden" name="idalmacen_x_proyecto_xp" id="idalmacen_x_proyecto_xp" />
                              <input type="hidden" name="idtrabajador_xp" id="idtrabajador_xp" />
                              <input type="hidden" name="idalmacen_resumen_xp" id="idalmacen_resumen_xp" />
                              <!-- idalmacen_x_proyecto_xp, idtrabajador_xp, id_producto_xp, fecha_ingreso_xp, marca_xp, cantidad_xp  -->
                              <!-- Producto -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-12">
                                <div class="form-group">
                                  <label for="producto_xp">Seleccionar E.P.P <span class="cargando_productos"></span> </label>
                                  <select name="producto_xp" id="producto_xp" class="form-control" onchange="select_producto_edit(this);" placeholder="Producto">
                                  </select>
                                </div>
                              </div>
                              <!-- Fecha -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-8">
                                <div class="form-group">
                                  <label for="epp_xp">E.P.P</label>
                                  <input type="text" id="epp_xp" class="form-control" placeholder="E.P.P" readonly />
                                  <input type="hidden" name="id_producto_xp" id="id_producto_xp" />
                                </div>
                              </div>

                              <!-- Fecha -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="fecha_ingreso">Fecha</label>
                                  <input type="date" name="fecha_ingreso_xp" id="fecha_ingreso_xp" class="form-control" placeholder="Fecha" />
                                  <!-- <input type="hidden" name="dia_ingreso_xp" id="dia_ingreso_xp" /> -->
                                </div>
                              </div>

                              <!-- Marcas -->
                              <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                                <div class="form-group">
                                  <label for="marca_xp">Marcas</label>
                                  <select name="marca_xp" id="marca_xp" class="form-control"></select>
                                </div>
                              </div>
                              <!-- um -->
                              <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                                <div class="form-group">
                                  <label for="marca_xp">U.M</label>
                                  <input type="text" id="unidad_m" class="form-control" placeholder="U.M" readonly />
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
                              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;" id="barra_progress_epp_xp_div">
                                <div class="progress">
                                  <div id="barra_progress_epp_xp" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
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
                          <button type="submit" style="display: none;" id="submit-form-editar-x-epp">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" onclick="limpiar_edit_epp();" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro_epp_xp">Guardar Cambios</button>
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

    <script type="text/javascript" src="scripts/epp.js?version_jdl=1.9"></script>

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