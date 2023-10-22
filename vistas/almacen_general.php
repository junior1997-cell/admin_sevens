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
        <title>Almacenes | Admin Sevens</title>
        
        <?php $title = "Almacenes"; require 'head.php'; ?>        
        

      </head>
      <body class="hold-transition sidebar-collapse sidebar-mini layout-fixed layout-navbar-fixed">
        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper">
          <?php
          require 'nav.php';
          require 'aside.php';
          if ($_SESSION['recurso']==1){
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
                            <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-almacen-general" onclick="limpiar();"><i class="fas fa-plus-circle"></i> Agregar</button>
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
                                      <a class="nav-link active" role="tab" ><i class="fas fa-spinner fa-pulse fa-sm"></i></a>
                                    </li>           
                                  </ul>
                                </div>
                                <div class="card-body" >                                  
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
                                                <th class="text-center" >Descripción</th>
                                              </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                              <tr>
                                                <th class="text-center">#</th>
                                                <th class="">Acciones</th>
                                                <th class="">Nombre almacen</th>
                                                <th class="text-center" >Descripción</th>
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
                                <div class="progress" >
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
          }else{
            require 'noacceso.php';
          }
          require 'footer.php';
          ?>
        </div>
        <!-- /.content-wrapper -->
        <?php require 'script.php'; ?>       

        <script type="text/javascript" src="scripts/almacen_general.js"></script>

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>
        
      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
