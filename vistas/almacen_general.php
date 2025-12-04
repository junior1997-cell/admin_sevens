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
    require 'template/head.php';
    date_default_timezone_set('America/Lima'); ?>

    <style>
      .style_tabla_datatable td,
      tr {
        font-size: 11px;
        /* Reducir el tamaño de la fuente */
        padding: 5px;
        /* Ajustar el padding */
      }

      @media only screen and (max-width: 991px) {
        .ocultar_head {
          display: none;
        }

        .ver {
          display: block !important;
        }
      }
    </style>
  </head>

  <body class="hold-transition sidebar-collapse sidebar-mini layout-fixed layout-navbar-fixed pace-orange" idproyecto="<?php echo $_SESSION['idproyecto']; ?>">
    <!-- Content Wrapper. Contains page content -->
    <div class="wrapper">
      <?php
      require 'template/nav.php';
      require 'template/aside.php';
      if ($_SESSION['recurso'] == 1) {
        //require 'template/enmantenimiento.php';
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
                        <button type="button" class="btn bg-gradient-success btn_add_almacen" data-toggle="modal" data-target="#modal-agregar-almacen-general" onclick="limpiar();"><i class="fas fa-plus-circle"></i> Almacén</button>
                        <button type="button" class="btn bg-gradient-primary btn_add_prod_almacen" style="display: none; padding-left: 2px;" data-toggle="modal" data-target="#modal-agregar-otro-almacen" onclick="limpiar();"><i class="fas fa-plus-circle"></i> Traer insumos</button>
                        <button type="button" class="btn btn-secondary btn-flat btn_ing_d_almacen" style="padding-left: 2px;" data-toggle="modal" data-target="#modal-ingreso-directo" onclick="limpiar_ing_di();"><i class="fas fa-plus-circle"></i> Agresar insumo</button>
                        Administra de manera eficiente a tus almacenes.
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
                                            <th class="">Nombre almacén</th>
                                            <th class="text-center">Descripción</th>
                                          </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th class="">Nombre almacén</th>
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
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-5">
                                      <div class="row">
                                        <div class="col-12 mb-1">
                                          <button type="button" class="btn btn-warning btn-block btn-sm" onclick="limpiar_Transferencia(); listar_productos_transferencia();"><i class="fa fa-exchange"></i> Transferencia (Enviar)</button>
                                        </div>
                                      </div>

                                      <div class="card-body table-responsive p-0">                                      
                                        <table id="tabla-detalle-almacen" class="table table-bordered table-striped display style_tabla_datatable" style="width: 100% !important;">
                                          <div class="row mb-2 mt-2">
                                            <div class="col-sm-4">
                                              <h6><i class="nav-icon fas fa-box-open"></i> Tabla Insumos</h6>
                                            </div>
                                            <div class="col-sm-8">
                                              <h6 class="float-sm-right" style=" padding: 3px !important; margin-bottom: 0px !important;">
                                                <button class="btn btn-info btn-sm mayor_cero" onclick="stock('1');">Stok > 0</button>
                                                <button class="btn btn-secondary  btn-sm include_cero" onclick="stock('0');">Stock Incluido 0</button>
                                              </h6>
                                            </div>
                                          </div>
                                          <thead>
                                            <tr>
                                              <th class="text-center">#</th>
                                              <th class="">Producto</th>
                                              <th class="">Stock</th>
                                              <th class="">Ingreso</th>
                                              <th class="">Salida</th>
                                              <th class="">Ver</th>

                                              <th class="">Código</th>
                                              <th class="">Nombre</th>
                                              <th class="">Unidad</th>
                                            </tr>
                                          </thead>
                                          <tbody></tbody>
                                          <tfoot>
                                            <tr>
                                              <th class="text-center">#</th>
                                              <th class="">Producto</th>
                                              <th class="">Stock</th>
                                              <th class="">Ingreso</th>
                                              <th class="">Salida</th>
                                              <th class="">Ver</th>

                                              <th class="">Código</th>
                                              <th class="">Nombre</th>
                                              <th class="">Unidad</th>
                                            </tr>
                                          </tfoot>
                                        </table>
                                      </div>
                                    </div>

                                    <div class="col-12 col-sm-12 col-md-6 col-lg-7">
                                      <div class="col-12 mb-1" style="margin: 5px; color: #1f2d3d; background-color: #f8f9fa; border-color: #e9ecef;">
                                        <!--<div class="modal-header">
                                          <h6> Producto:<strong class="nombre_insumo"> <i class="fas fa-exclamation-triangle text-warning"></i> </strong> </h6>
                                        </div> -->

                                        <div class="alert alert-secondary alerta_inicial" role="alert" style=" background-color: #ffe69c; border-color: #ffe69c; color: black;">
                                          <h2 class="alert-heading">¡UPS! Ningún Producto seleccionado.</strong></h2>
                                          <hr>
                                          <p class="mb-0">Tiene que seleccionar un Producto para poder ver sus movimientos</p>
                                        </div>

                                        <div class="tabla_detalle_almacen_g" style="display: none; ">
                                          <h6 class="widget-user-username nombre_producto text-center"><span class="spinner-border spinner-border-md"></span></h6>
                                          <h6>Movimientos por Insumos </h6>
                                          <table id="tabla_detalle_almacen_general" class="table table-bordered table-striped display style_tabla_datatable" style="width: 100% !important;">
                                            <thead>
                                              <tr>
                                                <th class="text-center">#</th>
                                                <th class="">Movimiento</th>
                                                <th class="">Fecha</th>
                                                <th class="">Cantidad</th>
                                                <th class="">Pro/Alm</th>
                                              </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                              <tr>
                                                <th class="text-center">#</th>
                                                <th class="">Movimiento</th>
                                                <th class="">Fecha</th>
                                                <th class="">Cantidad</th>
                                                <th class="">Pro/Alm</th>
                                              </tr>
                                            </tfoot>
                                          </table>

                                        </div>
                                      </div>
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

            <!-- MODAL - AGREGAR ALMACEN GENERAL -->
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

            <!-- MODAL - AGREGAR DATOS A UN ALMACEN GENERAL  style="max-width: 95% !important;" -->
            <div class="modal fade" id="modal-agregar-otro-almacen">
              <div class="modal-dialog modal-dialog-scrollable modal-xl ">
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
                      <div class="px-2">
                        <div class="row" id="cargando-1-fomulario">
                          <!-- idalmacen general -->
                          <input type="hidden" name="idalmacen_general_ag" id="idalmacen_general_ag" />

                          <!-- Proyecto -->
                          <div class="col-12 col-sm-12 col-md-12 col-lg-4">
                            <div class="form-group">
                              <label for="proyecto_ag"> Proyecto <sup class="text-danger">*</sup></label>
                              <select name="proyecto_ag" id="proyecto_ag" class="form-control" placeholder="Proyecto" onchange="reload_proyect_ag(this);">
                              </select>
                            </div>
                          </div>

                          <!-- Fecha -->
                          <div class="col-12 col-sm-12 col-md-12 col-lg-3">
                            <div class="form-group">
                              <label for="fecha_ingreso_ag">Fecha</label>
                              <input type="date" name="fecha_ingreso_ag" class="form-control" id="fecha_ingreso_ag" value="<?php echo date("Y-m-d"); ?>" placeholder="Fecha" onchange="obtener_dia_ingreso(this);" />
                              <input type="hidden" name="dia_ingreso_ag" id="dia_ingreso_ag" />
                            </div>
                          </div>

                          <!-- Recurso  style="display: none; padding-left: 2px;"-->
                          <div class="col-12 col-sm-12 col-md-12 col-lg-5 ">
                            <div class="form-group select_recurso" style="display: none; ">
                              <label for="producto_ag">
                                <span class="badge badge-warning cursor-pointer" data-toggle="tooltip" data-original-title="Recargar comprados" onclick="reload_producto_comprados_ag();"><i class="fa-solid fa-rotate-right"></i></span>
                                Producto <span class="cargando_productos_ag"></span>

                              </label>
                              <select name="producto_ag" id="producto_ag" class="form-control" placeholder="Producto" onchange="add_producto_ag(this);">
                              </select>
                            </div>

                            <div class="form-group select_init_recurso">
                              <label for="producto_ag">
                                <span class="badge badge-danger cursor-pointer" data-toggle="tooltip" data-original-title="Selecciona un proyecto"><i class="fa-solid fa-rotate-right"></i></span>
                                Producto <span class="cargando_productos_ag"></span>

                              </label>
                              <p class="text-warning m-b-01rem" style="margin-top: 7px"> <strong>SELECCIONAR PROYECTO</strong> </p>
                              <!-- <input type="text" class="text-info form-control" placeholder="Seleccionar proyecto" desabled> -->

                              </select>
                            </div>

                          </div>

                          <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="pl-2" style="position: relative; top: 10px; z-index: +1; letter-spacing: 2px;"><span class="bg-white text-primary" for=""> <b class="mx-2">PRODUCTOS - AGREGADOS</b> </span></div>
                          </div>

                          <div class="col-12 col-sm-12 col-md-12 col-lg-12  ">
                            <div class="card px-3 py-3" style="box-shadow: 0 0 1px rgb(0 0 0), 0 1px 3px rgb(0 0 0 / 60%);">
                              <div class="row head_list" style="display: none;">
                                <div class="col-12 col-sm-12 col-md-4 col-lg-4 mt-2 mb-2 text-bold ocultar_head">Nombre Producto</div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-3 mt-2 mb-2 text-bold ocultar_head">Proyecto</div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-2 mt-2 mb-2 text-bold ocultar_head">Und.</div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-2 mt-2 mb-2 text-bold ocultar_head">Cantidad</div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-1 mt-2 mb-2 text-bold ocultar_head"><i class="far fa-trash-alt"></i></div>
                                <!-- <textarea name="" id="" cols="30" rows="10"></textarea> -->
                              </div>
                              <div class="row" id="html_producto_ag">
                                <div class="col-12 html_mensaje">
                                  <div class="alert alert-warning alert-dismissible mb-0">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="icon fas fa-exclamation-triangle"></i> Alerta!</h5>
                                    NO TIENES NINGÚN PRODUCTO SELECCIONADO.
                                  </div>
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

            <!-- MODAL ---- ENTRE ALMACENES GENERALES --- TRNASFERENCIA MASIVO A PROYECTO -->
            <div class="modal fade" id="modal-transferencia_aproyecto">
              <div class="modal-dialog modal-dialog-scrollable modal-xl">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Transferencias</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span class="text-danger" aria-hidden="true">&times;</span>
                    </button>
                  </div>

                  <div class="modal-body">
                    <!-- form start -->
                    <form id="form_proyecto_almacen" name="form_proyecto_almacen" method="POST">
                      <div class="card-body">
                        <div class="row" id="cargando-1-fomulario">

                          <!-- Tipo -->
                          <div class="col-12 col-sm-12 col-md-6 col-lg-3">
                            <div class="form-group">
                              <label for="tranferencia">Tranferencia a <sup class="text-danger">(*)</sup></label>
                              <select name="tranferencia" id="tranferencia" class="form-control" onchange="select_tipo_transferencia(this);">
                                <option value="Otro_Almacen">Otro Almacen</option>
                                <option value="Proyecto">Proyecto</option>
                              </select>
                            </div>
                          </div>
                          <!-- Nombre -->
                          <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group init_select">
                              <label for="">Destino <sup class="text-danger">(*)</sup></label>
                              <p class="text-warning m-b-01rem" style="margin-top: 7px"> <strong>ALMACEN O UN PROYECTO</strong> </p>
                            </div>

                            <div class="form-group select_proy_alm" style="display: none;">
                              <label for="name_alm_proyecto">Destino <sup class="text-danger">(*)</sup></label>
                              <select name="name_alm_proyecto" id="name_alm_proyecto" class="form-control">
                              </select>
                            </div>

                          </div>
                          <!-- Fecha -->
                          <div class="col-12 col-sm-12 col-md-6 col-lg-3">
                            <div class="form-group">
                              <label for="fecha_transf_proy_alm">Fecha</label>
                              <input type="date" name="fecha_transf_proy_alm" class="form-control" value="<?php echo date("Y-m-d"); ?>" id="fecha_transf_proy_alm" placeholder="Fecha" />
                            </div>
                          </div>
                          <div class="col-12">
                            <div class="table-responsive" style="width: 100%;" >
                              <table id="tabla-producto_tra" class="table table-bordered table-striped display" style="width: 100% !important;">
                                <thead>
                                  <tr>
                                    <th class="text-center">#</th>
                                    <th class="">Nombre Producto</th>
                                    <th class="">Unidad</th>
                                    <th class="text-center">Stock</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-center"> Marcar
                                      <div class="custom-control custom-switch hidden"  data-toggle="tooltip" data-original-title="Activar todos">
                                        <input class="custom-control-input" type="checkbox" id="marcar_todo" onchange="Activar_masivo();">
                                        <label for="marcar_todo" class="custom-control-label"></label>
                                      </div>
                                    </th>
                                  </tr>
                                </thead>
                                <tbody></tbody>
                              </table>
                            </div>
                          </div>
                          <!-- /.col -->

                          <div class="row" id="html_producto_transf">
                            <div class="col-12 html_mensaje">
                              <div class="alert alert-warning alert-dismissible mb-0">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h5><i class="icon fas fa-exclamation-triangle"></i> Alerta!</h5>
                                NO TIENES NINGÚN PRODUCTO SELECCIONADO.
                              </div>
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
                      <button type="submit" style="display: none;" id="submit-form-proyecto_almacen">Submit</button>
                    </form>
                  </div>
                  <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_Transferencia();">Close</button>
                    <button type="submit" class="btn btn-success btn_g_proy_alm" id="guardar_registro_proyecto_almacen">Guardar Cambios</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- MODAL - INGRESO DIRECTO - chargue 1-2 -->
            <div class="modal fade" id="modal-ingreso-directo">
              <div class="modal-dialog modal-dialog-scrollable modal-xl">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Ingreso Directo</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span class="text-danger" aria-hidden="true">&times;</span>
                    </button>
                  </div>

                  <div class="modal-body">
                    <!-- form start -->
                    <form id="form-almacen-tup" name="form-almacen-tup" method="POST">
                      <div class="px-2">
                        <div class="row" id="cargando-1-fomulario">
                          <!-- Producto -->
                          <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                            <div class="form-group">
                              <label for="producto_tup">
                                <!-- <span class="badge badge-info cursor-pointer" data-toggle="tooltip" data-original-title="Recargar todos" onclick="reload_producto_todos();"><i class="fa-solid fa-rotate-right"></i></span>  -->
                                <span class="badge badge-warning cursor-pointer" data-toggle="tooltip" data-original-title="Recargar" onclick="reload_producto_tup();"><i class="fa-solid fa-rotate-right"></i></span>
                                Producto <span class="cargando_producto_tup"></span>
                              </label>
                              <select name="producto_tup" id="producto_tup" class="form-control" placeholder="Producto" onchange="add_producto_tup(this);">
                              </select>
                            </div>
                          </div>
                          <!-- Almancen -->
                          <div class="col-12 col-sm-12 col-md-7 col-lg-3">
                            <div class="form-group">
                              <label for="almacen_tup"> Almacén </label>
                              <select name="almacen_tup" id="almacen_tup" class="form-control" placeholder="Selecionar almacen">
                              </select>
                            </div>
                          </div>

                          <!-- Fecha -->
                          <div class="col-12 col-sm-12 col-md-5 col-lg-3">
                            <div class="form-group">
                              <label for="fecha_tup">Fecha</label>
                              <input type="date" name="fecha_tup" class="form-control" id="fecha_tup" placeholder="Fecha" value="<?php echo date("Y-m-d"); ?>" />

                            </div>
                          </div>

                          <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="pl-2" style="position: relative; top: 10px; z-index: +1; letter-spacing: 2px;"><span class="bg-white text-primary" for=""> <b class="mx-2">PRODUCTOS - AGREGADOS</b> </span></div>
                          </div>

                          <div class="col-12 col-sm-12 col-md-12 col-lg-12 ">
                            <div class="card px-3 py-3" style="box-shadow: 0 0 1px rgb(0 0 0), 0 1px 3px rgb(0 0 0 / 60%);">
                              <div class="row titulo-add-producto-tup mt-2">
                                <div class="col-6 col-sm-6 col-md-6 col-lg-5 col-xl-5 ocultar_head"><label>Nombre Producto</label></div>
                                <div class="col-6 col-sm-6 col-md-6 col-lg-2 col-xl-2 ocultar_head"><label>U.M.</label></div>
                                <div class="col-6 col-sm-6 col-md-6 col-lg-2 col-xl-2 ocultar_head"><label>Marca </label></div>
                                <div class="col-6 col-sm-6 col-md-6 col-lg-2 col-xl-2 ocultar_head"><label>Cantidad</label></div>
                                <div class="col-6 col-sm-6 col-md-6 col-lg-1 col-xl-1 ocultar_head"></div>
                              </div>
                              <div class="row" id="html_producto_tup">
                                <span> Seleccione un producto</span>
                              </div>
                            </div>
                          </div>

                          <!-- barprogress -->
                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;" id="barra_progress_tup_div">
                            <div class="progress">
                              <div id="barra_progress_tup" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
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
                      <!-- /.px-2 -->
                      <button type="submit" style="display: none;" id="submit-form-almacen-tup">Submit</button>
                    </form>
                  </div>
                  <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" onclick="limpiar_form_tup();" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" id="guardar_registro_almacen_tup">Guardar Cambios</button>
                  </div>
                </div>
              </div>
            </div>



          </section>
          <!-- /.content -->
        </div>

      <?php
      } else {
        require 'template/noacceso.php';
      }
      require 'template/footer.php';
      ?>
    </div>
    <!-- /.content-wrapper -->
    <?php require 'template/script.php'; ?>

    <script type="text/javascript" src="scripts/almacen_general.js?version_jdl=2.07"></script>

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