<?php
  //Activamos el almacenamiento en el buffer
  ob_start();
  session_start();

  if (!isset($_SESSION["nombre"])){

    header("Location: index.php?file=".basename($_SERVER['PHP_SELF']));

  }else{ ?>
    <!DOCTYPE html>
    <html lang="en">
      <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Graficos | Admin Sevens</title>

        <?php $title = "Compras  de Insumos"; require 'head.php'; ?>

      </head>
      <!--
      `body` tag options:

        Apply one or more of the following classes to to the body tag
        to get the desired effect

        * sidebar-collapse
        * sidebar-mini
      -->
      <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed pace-orange" idproyecto="<?php echo $_SESSION['idproyecto']; ?>">
        <div class="wrapper">
          <?php
            require 'nav.php';
            require 'aside.php';
            if ($_SESSION['compra_insumos']==1){
              //require 'enmantenimiento.php';
              ?>

              <!-- Content Wrapper. Contains page content -->
              <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <div class="content-header">
                  <div class="container-fluid">
                    <div class="row mb-2">
                      <div class="col-sm-6">
                        <h1 class="m-0">Reportes</h1>
                      </div><!-- /.col -->
                      <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                          <li class="breadcrumb-item"><a href="#">Home</a></li>
                          <li class="breadcrumb-item active">Reportes</li>
                        </ol>
                      </div><!-- /.col -->
                    </div><!-- /.row -->
                  </div><!-- /.container-fluid -->
                </div>
                <!-- /.content-header -->

                <!-- Main content -->
                <div class="content">
                  <div class="container-fluid">
                    <div class="row">
                      <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                        <div class="info-box">
                          <span class="info-box-icon bg-info elevation-1"><i class="fas fa-people-arrows"></i></span>

                          <div class="info-box-content">
                            <span class="info-box-text">Proveedores</span>
                            <span class="info-box-number cant_proveedores_box"> <i class="fas fa-spinner fa-pulse fa-lg"></i></span>
                          </div>
                          <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                      </div>
                      <!-- /.col -->
                      <div class="col-6 col-sm-6 col-md-3 col-lg-3  col-xl-3">
                        <div class="info-box mb-3">
                          <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-layer-group"></i></span>

                          <div class="info-box-content">
                            <span class="info-box-text">Productos</span>
                            <span class="info-box-number cant_producto_box"> <i class="fas fa-spinner fa-pulse fa-lg"></i></span>
                          </div>
                          <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                      </div>
                      <!-- /.col -->

                      <!-- fix for small devices only -->
                      <div class="clearfix hidden-md-up"></div>

                      <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                        <div class="info-box mb-3">
                          <span class="info-box-icon bg-success elevation-1"><img src="../dist/svg/negro-palana-ico.svg" class="nav-icon" alt="" style="width: 31px !important;" ></span>

                          <div class="info-box-content">
                            <span class="info-box-text">Insumos</span>
                            <span class="info-box-number cant_insumos_box"> <i class="fas fa-spinner fa-pulse fa-lg"></i></span>
                          </div>
                          <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                      </div>
                      <!-- /.col -->
                      <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                        <div class="info-box mb-3">
                          <span class="info-box-icon bg-warning elevation-1"><i class="nav-icon fas fa-truck-pickup"></i></span>

                          <div class="info-box-content">
                            <span class="info-box-text">Activos Fijos</span>
                            <span class="info-box-number cant_activo_fijo_box"> <i class="fas fa-spinner fa-pulse fa-lg"></i></span>
                          </div>
                          <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                      </div>
                      <!-- /.col -->
                    </div>

                    <div class="align-content-between row">
                      <!-- Año -->
                      <div class="col-6 col-lg-6">
                        <div class="form-group">
                          <!-- <label for="year_filtro">Año </label> -->
                          <select name="year_filtro" id="year_filtro" class="form-control select2" style="width: 100%;" onchange="chart_linea_barra();"> </select>
                        </div>
                      </div>

                      <!-- Mes -->
                      <div class="col-6 col-lg-6">
                        <div class="form-group">
                          <!-- <label for="month_filtro">Mes </label> -->
                          <select name="month_filtro" id="month_filtro" class="form-control select2" style="width: 100%;" onchange="chart_linea_barra();">
                            <option value="1">Enero</option> 
                            <option value="2">Febrero</option> 
                            <option value="3">Marzo</option> 
                            <option value="4">Abril</option> 
                            <option value="5">Mayo</option> 
                            <option value="6">Junio</option> 
                            <option value="7">Julio</option> 
                            <option value="8">Agosto</option> 
                            <option value="9">Setiembre</option> 
                            <option value="10">Octubre</option> 
                            <option value="11">Noviembre</option> 
                            <option value="12">Diciembre</option> 
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="row">

                      <div class="col-lg-6">
                        <div class="card">
                          <div class="card-header border-0 ">                            
                            <h3 class="card-title font-weight-bold">Compras y Pagos por Mes</h3>
                            <!-- <a href="javascript:void(0);">View Report</a> -->                            
                            <div class="card-tools">
                              <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
                              <div class="btn-group">
                                <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                                  <i class="fas fa-wrench"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" role="menu">
                                  <button class="dropdown-item btn-download-cpxm-png"><i class="fa-solid fa-image"></i> Descargar .png</button>
                                  <button class="dropdown-item btn-download-cpxm-xlsx"><i class="fa-regular fa-file-excel"></i> Descargar .xlsx</button>
                                  <a href="compra_insumos.php" class="dropdown-item"><i class="fa-solid fa-link"></i> Ir a compras</a>                                  
                                  <a class="dropdown-divider"></a>
                                  <a href="resumen_insumos.php" class="dropdown-item"><i class="fa-solid fa-link"></i> Ir a resumen</a>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="card-body">
                            <div class="position-relative mb-4">
                              <canvas id="visitors-chart" height="350"> </canvas>                              
                            </div>
                            <div class="d-flex flex-row justify-content-end">
                              <span class="mr-2"><i class="fas fa-square text-primary"></i>Compra</span>
                              <span><i class="fas fa-square text-gray"></i> Pago</span>
                            </div>
                          </div>
                          <div class="card-footer">
                            <div class="row">
                              <div class="col-sm-4 col-6">
                                <div class="description-block border-right border-left">
                                  <span class="description-percentage total_material_p"><i class="fas fa-caret-up"></i> -</span>
                                  <h5 class="description-header total_material"><i class="fas fa-spinner fa-pulse fa-lg"></i></h5>
                                  <span class="description-text">TOTAL MATERIAL</span>
                                </div>
                                <!-- /.description-block -->
                              </div>
                              <!-- /.col -->
                              <div class="col-sm-4 col-6">
                                <div class="description-block border-right">
                                  <span class="description-percentage total_combustible_p"><i class="fas fa-caret-up"></i> -</span>
                                  <h5 class="description-header total_combustible"><i class="fas fa-spinner fa-pulse fa-lg"></i></h5>
                                  <span class="description-text">TOTAL COMBUSTIBLE</span>
                                </div>
                                <!-- /.description-block -->
                              </div>
                              <!-- /.col -->
                              <div class="col-sm-4 col-6">
                                <div class="description-block border-right">
                                  <span class="description-percentage total_equipo_p"><i class="fas fa-caret-up"></i> -</span>
                                  <h5 class="description-header total_equipo"><i class="fas fa-spinner fa-pulse fa-lg"></i></h5>
                                  <span class="description-text">TOTAL EQUIPO</span>
                                </div>
                                <!-- /.description-block -->
                              </div>
                              <!-- /.col -->                              
                            </div>
                            <!-- /.row -->
                          </div>
                        </div>
                        <!-- /.card -->
                      </div>

                      <div class="col-lg-6">
                        <div class="card">
                          <div class="card-header border-0 ">                            
                            <h3 class="card-title font-weight-bold">Compras y Pagos por Mes</h3>
                            <!-- <a href="javascript:void(0);">View Report</a> -->                            
                            <div class="card-tools">
                              <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
                            </div>
                          </div>
                          <div class="card-body">
                            <div class="position-relative mb-4">
                              <canvas id="sales-chart" height="350"></canvas>
                            </div>
                            <div class="d-flex flex-row justify-content-end">
                              <span class="mr-2"> <i class="fas fa-square text-primary"></i> Compra </span>
                              <span> <i class="fas fa-square text-gray"></i> Pago </span>
                            </div>
                          </div>
                        </div>
                        <!-- /.card -->
                      </div>

                      <div class="col-lg-12">
                        <div class="card">
                          <div class="card-header border-0">
                            <div class="d-flex justify-content-center">
                              <h3 class="card-title font-weight-bold">Compras y Pagos por Mes</h3>
                              <!-- <a href="javascript:void(0);">View Report</a> -->
                            </div>
                          </div>
                          <div class="card-body">
                            <div class="row">
                              <div class="col-md-8">
                                
                              </div>
                              <div class="col-md-4">
                                <p class="text-center">
                                  <strong>Detalles de Factura</strong>
                                </p>

                                <div class="progress-group">
                                  <span class="progress-text text--success">Facturas aceptadas</span>
                                  <span class="float-right cant_ft_aceptadas"><i class="fas fa-spinner fa-pulse fa-lg"></i></span>
                                  <div class="progress progress-sm">
                                    <div class="progress-bar bg-success progress_ft_aceptadas" style="width: 0%"></div>
                                  </div>
                                </div>
                                <!-- /.progress-group -->

                                <div class="progress-group">
                                  <span class="progress-text text--warning">Facturas rechazadas</span>
                                  <span class="float-right cant_ft_rechazadas"><i class="fas fa-spinner fa-pulse fa-lg"></i></span>
                                  <div class="progress progress-sm">
                                    <div class="progress-bar bg-warning progress_ft_rechazadas" style="width: 0%"></div>
                                  </div>
                                </div>
                                <!-- /.progress-group -->
                               
                                <div class="progress-group">
                                  <span class="progress-text text--danger">Facturas eliminadas</span>
                                  <span class="float-right cant_ft_eliminadas"><i class="fas fa-spinner fa-pulse fa-lg"></i></span>
                                  <div class="progress progress-sm">
                                    <div class="progress-bar bg-danger progress_ft_eliminadas" style="width: 0%"></div>
                                  </div>
                                </div>
                                <!-- /.progress-group -->

                                <div class="progress-group">
                                  <span class="progress-text text--danger">Facturas rechazada y eliminadas</span>
                                  <span class="float-right cant_ft_rechazadas_eliminadas"><i class="fas fa-spinner fa-pulse fa-lg"></i></span>
                                  <div class="progress progress-sm">
                                    <div class="progress-bar bg-danger progress_ft_rechazadas_eliminadas" style="width: 0%"></div>
                                  </div>
                                </div>
                                <!-- /.progress-group -->

                                <p class="text-center mt-4">
                                  <strong class="mt-2">Pagos de Factura</strong>
                                </p>
                                 <!-- /.seccion -->

                                <div class="progress-group">
                                  <span class="progress-text font-weight-bold text--success">Montos Pagadas</span>
                                  <span class="float-right monto_pagado"><i class="fas fa-spinner fa-pulse fa-lg"></i></span>
                                  <div class="progress progress-sm">
                                    <div class="progress-bar bg-success progress_monto_pagado" style="width: 0%"></div>
                                  </div>
                                </div>
                                <!-- /.progress-group -->
                                
                                <div class="progress-group">
                                  <span class="progress-text font-weight-bold text-danger">Montos NO Pagadas</span>
                                  <span class="float-right monto_no_pagado"><i class="fas fa-spinner fa-pulse fa-lg"></i></span>
                                  <div class="progress progress-sm">
                                    <div class="progress-bar bg-danger progress_monto_no_pagado" style="width: 0%"></div>
                                  </div>
                                </div>
                                <!-- /.progress-group -->
                              </div>
                            </div>
                            
                          </div>
                        </div>
                        <!-- /.card -->
                      </div>

                      <div class="col-lg-6">
                        <div class="card">
                          <div class="card-header border-0">
                            <h3 class="card-title text-center">Productos mas usados</h3>
                            <div class="card-tools">
                              <button class="btn btn-tool btn-sm" onclick="export_excel('#tbla_productos_mas_vendidos','Productos mas usados');">
                                <i class="fas fa-download"></i>
                              </button>
                              <!-- <a href="#" class="btn btn-tool btn-sm"> <i class="fas fa-bars"></i> </a> -->
                            </div> 
                          </div>
                          <div class="card-body table-responsive p-0">
                            <table class="table table-striped table-valign-middle" id="tbla_productos_mas_vendidos">
                              <thead>
                              <tr>
                                <th>Producto</th>
                                <th>Precio referencial</th>
                                <th>Cantidad</th>
                                <th>Mas</th>
                              </tr>
                              </thead>
                              <tbody id="body_productos_mas_vendidos">
                                <!-- aqui van los productos -->
                              </tbody>
                            </table>
                          </div>
                        </div>
                        <!-- /.card -->
                      </div>
                      
                      <div class="col-lg-6">
                        <div class="card">
                          <div class="card-header border-0">
                            <h3 class="card-title text-center">Productos mas usados</h3>
                            <div class="card-tools">                              
                              <button class="btn btn-tool btn-sm btn-download-pmu-png" > <i class="fas fa-download"></i> </button>
                              <!-- <a href="#" class="btn btn-tool btn-sm"> <i class="fas fa-bars"></i>  </a> -->
                            </div> 
                          </div>
                          <div class="card-body bg-white" id="div-download-chart-pie-productos-mas-usados">
                            <div class="row">
                              <div class="col-md-12">
                                <div class="chart-responsive">
                                  <canvas id="chart_pie_productos_mas_usadoss" height="250"></canvas>
                                </div>
                              </div>
                              <!-- <div class="col-md-4"> <ul class="chart-legend clearfix leyenda-pai-productos-mas-usados" > </ul> </div> -->
                            </div>                            
                          </div>
                        </div>
                        <!-- /.card -->
                      </div>

                      <div class="col-lg-6 hidden">
                        <div class="card">
                          <div class="card-header border-0">
                            <h3 class="card-title">Resumen</h3>
                            <div class="card-tools">
                              <a href="#" class="btn btn-sm btn-tool">
                                <i class="fas fa-download"></i>
                              </a>
                              <a href="#" class="btn btn-sm btn-tool">
                                <i class="fas fa-bars"></i>
                              </a>
                            </div>
                          </div>
                          <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                              <p class="text-success text-xl">
                                <i class="ion ion-social-usd-outline"></i> 
                              </p>
                              <p class="d-flex flex-column text-right">
                                <span class="font-weight-bold">
                                  <i class="ion ion-android-arrow-up text-success"></i> 12%
                                  
                                </span>
                                <span class="text-muted">PAGOS</span>
                              </p>
                            </div>
                            <!-- /.d-flex -->
                            <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                              <p class="text-warning text-xl">
                                <i class="ion ion-ios-cart-outline"></i>
                              </p>
                              <p class="d-flex flex-column text-right">
                                <span class="font-weight-bold">
                                  <i class="ion ion-android-arrow-up text-warning"></i> 0.8%
                                </span>
                                <span class="text-muted">COMPRAS</span>
                              </p>
                            </div>
                            <!-- /.d-flex -->
                            <div class="d-flex justify-content-between align-items-center mb-0">
                              <p class="text-danger text-xl">
                                <i class="ion ion-ios-people-outline"></i>
                              </p>
                              <p class="d-flex flex-column text-right">
                                <span class="font-weight-bold">
                                  <i class="ion ion-android-arrow-down text-danger"></i> 1%
                                </span>
                                <span class="text-muted">DEUDA</span>
                              </p>
                            </div>
                            <!-- /.d-flex -->
                          </div>
                        </div>
                        <!-- /.card -->
                      </div>
                      <!-- /.col-md-6 -->

                    </div>
                    <!-- /.row -->
                  </div>
                  <!-- /.container-fluid -->

                  <!-- MODAL - VER PERFIL INSUMO-->
                  <div class="modal fade" id="modal-ver-perfil-insumo">
                    <div class="modal-dialog modal-dialog-centered modal-md">
                      <div class="modal-content bg-color-0202022e shadow-none border-0">
                        <div class="modal-header">
                          <h4 class="modal-title text-white foto-insumo">Foto Insumo</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-white cursor-pointer" aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body"> 
                          <div id="perfil-insumo" class="class-style">
                            <!-- vemos los datos del trabajador -->
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
                <!-- /.content -->
              </div>
              <!-- /.content-wrapper -->

              <!-- Control Sidebar -->
              <aside class="control-sidebar control-sidebar-dark">
                <!-- Control sidebar content goes here -->
              </aside>
              <!-- /.control-sidebar -->

              <?php
            }else{
              require 'noacceso.php';
            }
            require 'footer.php';
          ?>
        </div>
        <!-- ./wrapper -->

        <!-- REQUIRED SCRIPTS -->

        <?php require 'script.php'; ?>
        
        
        <!-- OPTIONAL SCRIPTS -->
        <!-- <script src="../plugins/chart.js/Chart.min.js"></script> -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>        
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

        <!-- AdminLTE for demo purposes -->
        <script src="../dist/js/demo.js"></script>
        
        <!-- html2canvas -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
        <script src="../plugins/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>

        <!-- table export EXCEL -->
        <script src="../plugins/export-xlsx/xlsx.full.min.js"></script>
        <script src="../plugins/export-xlsx/FileSaver.min.js"></script>
        <script src="../plugins/export-xlsx/tableexport.min.js"></script> 
        
        <script type="text/javascript" src="scripts/chart_compra_insumo.js?version_jdl=2.04"></script>         

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>
        
      </body>
    </html>
    <?php    
  }

  ob_end_flush();
?>
