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
    <title>Almacenes | Admin Sevens</title>

    <?php $title = "Almacenes";
    require 'head.php'; ?>


  </head>

  <body class="hold-transition sidebar-collapse sidebar-mini layout-fixed layout-navbar-fixed">
    <!-- Content Wrapper. Contains page content -->
    <div class="wrapper">
      <?php
      require 'nav.php';
      require 'aside.php';
      if ($_SESSION['recurso'] == 1) {
        //require 'enmantenimiento.php';
      ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
          <!-- Content Header (Page header) -->
          <section class="content-header">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <h1><i class="nav-icon fas fa-box-open"></i> Almacenes</h1>
                </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Almacenes</li>
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
                        <button type="button" class="btn bg-gradient-success btn_add_almacen" data-toggle="modal" data-target="#modal-agregar-almacen-general" onclick="limpiar();"><i class="fas fa-plus-circle"></i> Almacen</button>
                        <button type="button" class="btn bg-gradient-primary btn_add_prod_almacen" style="display: none; padding-left: 2px;" data-toggle="modal" data-target="#modal-agregar-otro-almacen" onclick="limpiar();"><i class="fas fa-plus-circle"></i> Agregar</button>
                        Admnistra de manera eficiente a tus almacenes.
                      </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body px-1 py-1">
                      <div class="row">
                        <div class=" col-12 col-sm-12">
                          <div class="card card-primary card-outline card-outline-tabs mb-0">
                            <div class="card-header p-0 border-bottom-0">
                              <ul class="nav nav-tabs lista-items" id="tabs-for-tab" role="tablist">
                                <li class="nav-item">
                                  <a class="nav-link active" role="tab"><i class="fas fa-spinner fa-pulse fa-sm"></i></a>
                                </li>
                              </ul>
                            </div>
                            <div class="card-body">
                              <div class="tab-content" id="tabs-for-tabContent">
                                <!-- TABLA - ALMACEN -->
                                <div class="tab-pane fade show active" id="tabs-for-almacen" role="tabpanel" aria-labelledby="tabs-for-almacen-tab">
                                  <div class="row">
                                    <div class="col-12">
                                      <table id="tabla-almacen" class="table table-bordered table-striped display" style="width: 100% !important;">
                                        <thead>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th class="">Nombre almacen</th>
                                            <th class="text-center">Descripción</th>
                                          </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th class="">Nombre almacen</th>
                                            <th class="text-center">Descripción</th>
                                          </tr>
                                        </tfoot>
                                      </table>
                                    </div>
                                    <!-- /.col -->
                                  </div>
                                  <!-- /.row -->
                                </div>

                                <!-- TABLA - DETALLE -->
                                <div class="tab-pane fade" id="tabs-for-detalle" role="tabpanel" aria-labelledby="tabs-for-detalle-tab">
                                  <div class="row">
                                    <div class="col-12">
                                      <table id="tabla-detalle-almacen" class="table table-bordered table-striped display" style="width: 100% !important;">
                                        <thead>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Trans.</th>
                                            <th class="">Proyecto</th>
                                            <th class="">Fecha</th>
                                            <th class="">Nombre producto</th>
                                            <th class="">Cantidad</th>
                                          </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Trans.</th>
                                            <th class="">Proyecto</th>
                                            <th class="">Fecha</th>
                                            <th class="">Nombre producto</th>
                                            <th class="">Cantidad</th>
                                          </tr>
                                        </tfoot>
                                      </table>
                                    </div>
                                    <!-- /.col -->
                                  </div>
                                  <!-- /.row -->
                                </div>
                              </div>
                              <!-- /.tab-content -->
                            </div>
                            <!-- /.card-body -->
                          </div>
                        </div>
                        <!-- /.col -->
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

            <!-- MODAL - AGREGAR ACTIVOS FIJOS -->
            <div class="modal fade" id="modal-agregar-almacen-general">
              <div class="modal-dialog modal-dialog-scrollable modal-md">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Agregar almacen</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span class="text-danger" aria-hidden="true">&times;</span>
                    </button>
                  </div>

                  <div class="modal-body">
                    <!-- form start -->
                    <form id="form-almacen-general" name="form-almacen-general" method="POST">
                      <div class="card-body">
                        <div class="row" id="cargando-1-fomulario">
                          <!--  -->
                          <input type="hidden" name="idalmacen_general" id="idalmacen_general" />

                          <!-- Nombre -->
                          <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                              <label for="nombre_almacen">Nombre <sup class="text-danger">(unico*)</sup></label>
                              <input type="text" name="nombre_almacen" class="form-control" id="nombre_almacen" placeholder="Nombre almacen." />
                            </div>
                          </div>

                          <!-- Descripcion -->
                          <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                              <label for="descripcion">Descripcion </label>
                              <textarea class="form-control" name="descripcion" id="descripcion" cols="30" rows="2"></textarea>
                            </div>
                          </div>

                          <!-- barprogress -->
                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 m-t-20px" id="barra_progress_almacen_div">
                            <div class="progress">
                              <div id="barra_progress_almacen" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
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
                      <button type="submit" style="display: none;" id="submit-form-almacen-general">Submit</button>
                    </form>
                  </div>
                  <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar();">Close</button>
                    <button type="submit" class="btn btn-success" id="guardar_registro_almacen">Guardar Cambios</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- MODAL - AGREGAR OTROS ALMACEN - chargue 5-6 -->
            <div class="modal fade" id="modal-agregar-otro-almacen">
              <div class="modal-dialog modal-dialog-scrollable modal-xl">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Agregar al Almacen General : <span class="nombre_almacen_g" style="color:red">nombre</span> </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="limpiar_form_otro_almacen();">
                      <span class="text-danger" aria-hidden="true">&times;</span>
                    </button>
                  </div>

                  <div class="modal-body">
                    <!-- form start -->
                    <form id="form-otro-almacen" name="form-otro-almacen" method="POST">
                      <div class="card-body">
                        <div class="row" id="cargando-1-fomulario">
                          <!-- idalmacen general -->
                          <input type="hidden" name="idalmacen_general_ag" id="idalmacen_general_ag" />
                          <!-- idalmacen_producto_guardado  -->
                          <input type="hidden" name="idalmacen_producto_guardado" id="idalmacen_producto_guardado" />

                          <!-- Proyecto -->
                          <div class="col-12 col-sm-12 col-md-5 col-lg-4">
                            <div class="form-group">
                              <label for="proyecto_ag"> Proyecto <sup class="text-danger">*</sup></label>
                              <select name="proyecto_ag" id="proyecto_ag" class="form-control" placeholder="Proyecto" onchange="reload_proyect_ag(this);">
                              </select>
                            </div>
                          </div>

                          <!-- Fecha -->
                          <div class="col-12 col-sm-12 col-md-2 col-lg-2">
                            <div class="form-group">
                              <label for="fecha_ingreso_ag">Fecha</label>
                              <input type="date" name="fecha_ingreso_ag" class="form-control" id="fecha_ingreso_ag" placeholder="Fecha" onchange="obtener_dia_ingreso(this);" />
                              <input type="hidden" name="dia_ingreso_ag" id="dia_ingreso_ag" />
                            </div>
                          </div>

                          <!-- Recurso  style="display: none; padding-left: 2px;"-->
                          <div class="col-12 col-sm-12 col-md-5 col-lg-6 ">
                            <div class="form-group select_recurso" style="display: none; padding-left: 2px;">
                              <label for="producto_ag">
                                <span class="badge badge-warning cursor-pointer" data-toggle="tooltip" data-original-title="Recargar comprados" onclick="reload_producto_comprados_ag();"><i class="fa-solid fa-rotate-right"></i></span>
                                Producto <small>(comprado)</small> <span class="cargando_productos_ag"></span>

                              </label>
                              <select name="producto_ag" id="producto_ag" class="form-control" placeholder="Producto" onchange="add_producto_ag(this);">
                              </select>
                            </div>

                            <div class="form-group select_init_recurso">
                              <label for="producto_ag">
                                <span class="badge badge-danger cursor-pointer" data-toggle="tooltip" data-original-title="Selecciona un proyecto"><i class="fa-solid fa-rotate-right"></i></span>
                                Producto <small>(comprado)</small> <span class="cargando_productos_ag"></span>

                              </label>
                              <p class="text-warning m-b-01rem" style="margin-top: 7px"> <strong>SELECCIONAR PROYECTO</strong> </p>
                              <!-- <input type="text" class="text-info form-control" placeholder="Seleccionar proyecto" desabled> -->

                              </select>
                            </div>

                          </div>

                          <div class="col-12 pl-0">
                            <div class="text-primary"><label for="">Productos agregados </label></div>
                          </div>

                          <div class="col-12 col-sm-12 col-md-12 col-lg-12 card px-3 py-3" style="box-shadow: 0 0 1px rgb(0 0 0), 0 1px 3px rgb(0 0 0 / 60%);">
                            <div class="row head_list" style="display: none;">
                              <div class="col-12 col-sm-12 col-md-6 col-lg-6 mt-2 mb-2 text-bold">Nombre Producto</div>
                              <div class="col-12 col-sm-12 col-md-6 col-lg-3 mt-2 mb-2 text-bold">Proyecto</div>
                              <div class="col-12 col-sm-12 col-md-6 col-lg-2 mt-2 mb-2 text-bold">Cantidad</div>
                              <div class="col-12 col-sm-12 col-md-6 col-lg-1 mt-2 mb-2 text-bold"><i class="far fa-trash-alt"></i></div>
                              <!-- <textarea name="" id="" cols="30" rows="10"></textarea> -->
                            </div>
                            <div class="row" id="html_producto_ag">
                              <div class="col-12 html_mensaje">
                                <div class="alert alert-warning alert-dismissible">
                                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                  <h5><i class="icon fas fa-exclamation-triangle"></i> Alerta!</h5>
                                  NO TIENES NINGÚN PRODUCTO SELECCIONADO.
                                </div>
                              </div>
                            </div>
                          </div>

                          <!-- barprogress -->
                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;" id="barra_progress_otro_almacen_div">
                            <div class="progress">
                              <div id="barra_progress_otro_almacen" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                0%
                              </div>
                            </div>
                          </div>

                        </div>

                        <div class="row" id="cargando-2-fomulario" style="display: none;">
                          <div class="col-lg-12 text-center"><i class="fas fa-spinner fa-pulse fa-6x"></i><br /><br />
                            <h4>Cargando...</h4>
                          </div>
                        </div>
                      </div>
                      <!-- /.card-body -->
                      <button type="submit" style="display: none;" id="submit-form-otro-almacen">Submit</button>
                    </form>
                  </div>
                  <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" onclick="limpiar_form_otro_almacen();" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" id="guardar_registro_otro_almacen">Guardar Cambios</button>
                  </div>
                </div>
              </div>
            </div>

            
            <!-- MODAL - TRNASFERENCIA -->
            <div class="modal fade" id="modal-transferencia">
              <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Transferencia</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span class="text-danger" aria-hidden="true">&times;</span>
                    </button>
                  </div>

                  <div class="modal-body">
                    <!-- form start -->
                    <form id="form-transf_almacen" name="form-transf_almacen" method="POST">
                      <div class="card-body">
                        <div class="row" id="cargando-1-fomulario">

                          <!-- Nombre -->
                          <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                              <label for="name_alm_origen">Almacen Origen <sup class="text-danger">(*)</sup></label>
                              <input type="text" name="name_alm_origen" class="form-control" id="name_alm_origen" placeholder="Nombre almacen." disabled />
                            </div>
                          </div>

                          <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                              <label for="name_alm_destino">Almacen Destino <sup class="text-danger">(*)</sup></label>
                              <select name="name_alm_destino" id="name_alm_destino" class="form-control" placeholder="Almacen destino " >
                              </select>
                            </div>
                          </div>

                          <!-- Nombre -->
                          <div class="col-12 col-sm-9 col-md-9 col-lg-9">
                            <div class="form-group">
                              <label for="name_prod_alm_origen">Producto <sup class="text-danger">(*)</sup></label>
                              <input type="text" name="name_prod_alm_origen" class="form-control" id="name_prod_alm_origen" placeholder="Nombre almacen." disabled />
                            </div>
                          </div>
                          <!-- Nombre -->
                          <div class="col-12 col-sm-3 col-md-3 col-lg-3">
                            <div class="form-group">
                              <label for="cantidad_alm_trans">Cantidad <sup class="text-danger">(*)</sup></label>
                              <input type="number" name="cantidad_alm_trans" class="form-control" id="cantidad_alm_trans" placeholder="Cantidad." />
                            </div>
                          </div>

                          <!-- barprogress -->
                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 m-t-20px" id="barra_progress_almacen_div">
                            <div class="progress">
                              <div id="barra_progress_almacen" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
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
                      <button type="submit" style="display: none;" id="submit-form-almacen-general">Submit</button>
                    </form>
                  </div>
                  <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar();">Close</button>
                    <button type="submit" class="btn btn-success" id="guardar_registro_almacen">Guardar Cambios</button>
                  </div>
                </div>
              </div>
            </div>



            <!-- MODAL - TRNASFERENCIA MASIVO NO SE UTILIZA -->
            <div class="modal fade" id="modal-transferencia_MASIVO">
              <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Transferencia</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span class="text-danger" aria-hidden="true">&times;</span>
                    </button>
                  </div>

                  <div class="modal-body">
                    <!-- form start -->
                    <form id="form-almacen-general" name="form-almacen-general" method="POST">
                      <div class="card-body">
                        <div class="row" id="cargando-1-fomulario">

                          <!-- Nombre -->
                          <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                              <label for="name_alm_orige">Almacen Origen <sup class="text-danger">(*)</sup></label>
                              <!-- <input type="text" name="name_alm_orige" class="form-control" id="name_alm_origen" placeholder="Nombre almacen." /> -->

                              <select name="name_alm_orige" id="name_alm_orige" class="form-control" placeholder="Almacen Origen" onchange="reload_transf_almacen(this);">
                              </select>
                            </div>
                          </div>

                          <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                              <label for="nombre_almacen">Almacen Origen <sup class="text-danger">(*)</sup></label>
                              <!-- <input type="text" name="nombre_almacen" class="form-control" id="nombre_almacen" placeholder="Nombre almacen." /> -->
                              <select name="name_alm_destin" id="name_alm_destin" class="form-control" placeholder="Almacen destino " disabled>
                              </select>
                            </div>
                          </div>

                          <!-- Nombre -->
                          <div class="col-12 col-sm-9 col-md-9 col-lg-9">
                            <div class="form-group">
                              <label for="name_prod_alm_origen">Producto <sup class="text-danger">(*)</sup></label>

                              <select name="name_prod_alm_orige" id="name_prod_alm_orige" class="form-control" placeholder="Producto">
                              </select>
                            </div>
                          </div>
                          <!-- Nombre -->
                          <div class="col-12 col-sm-3 col-md-3 col-lg-3">
                            <div class="form-group">
                              <label for="cantidad_alm_tran">Cantidad <sup class="text-danger">(*)</sup></label>
                              <input type="text" name="cantidad_alm_tran" class="form-control" id="cantidad_alm_tran" placeholder="Cantidad." />
                            </div>
                          </div>

                          <!-- Descripcion -->
                          <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                              <label for="descripcion">Descripcion </label>
                              <textarea class="form-control" name="descripcion" id="descripcion" cols="30" rows="2"></textarea>
                            </div>
                          </div>


                          <!-- barprogress -->
                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 m-t-20px" id="barra_progress_almacen_div">
                            <div class="progress">
                              <div id="barra_progress_almacen" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
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
                      <button type="submit" style="display: none;" id="submit-form-almacen-general">Submit</button>
                    </form>
                  </div>
                  <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar();">Close</button>
                    <button type="submit" class="btn btn-success" id="guardar_registro_almacen">Guardar Cambios</button>
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

    <script type="text/javascript" src="scripts/almacen_general.js"></script>

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