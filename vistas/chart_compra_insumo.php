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
        <meta name="viewport" content="width=device-width, initial-scale=3">
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
      <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed">
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

                      <div class="col-lg-12">
                        <div class="card">
                          <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                              <h3 class="card-title">Compras y pagos por mes</h3>
                              <a href="javascript:void(0);">View Report</a>
                            </div>
                          </div>
                          <div class="card-body">
                            <!-- <div class="d-flex">
                              <p class="d-flex flex-column">
                                <span class="text-bold text-lg">820</span>
                                <span>Visitors Over Time</span>
                              </p>
                              <p class="ml-auto d-flex flex-column text-right">
                                <span class="text-success">
                                  <i class="fas fa-arrow-up"></i> 12.5%
                                </span>
                                <span class="text-muted">Since last week</span>
                              </p>
                            </div> -->
                            <!-- /.d-flex -->

                            <div class="position-relative mb-4">
                              <canvas id="visitors-chart" height="350"></canvas>
                            </div>

                            <div class="d-flex flex-row justify-content-end">
                              <span class="mr-2">
                                <i class="fas fa-square text-primary"></i>Compra
                              </span>

                              <span>
                                <i class="fas fa-square text-gray"></i> Pago
                              </span>
                            </div>
                          </div>
                        </div>
                        <!-- /.card -->
                      </div>

                      <div class="col-lg-12">
                        <div class="card">
                          <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                              <h3 class="card-title">Compras y pagos por mes</h3>
                              <a href="javascript:void(0);">View Report</a>
                            </div>
                          </div>
                          <div class="card-body">
                            <!-- <div class="d-flex">
                              <p class="d-flex flex-column">
                                <span class="text-bold text-lg">$18,230.00</span>
                                <span>Sales Over Time</span>
                              </p>
                              <p class="ml-auto d-flex flex-column text-right">
                                <span class="text-success">
                                  <i class="fas fa-arrow-up"></i> 33.1%
                                </span>
                                <span class="text-muted">Since last month</span>
                              </p>
                            </div> -->
                            <!-- /.d-flex -->

                            <div class="position-relative mb-4">
                              <canvas id="sales-chart" height="350"></canvas>
                            </div>

                            <div class="d-flex flex-row justify-content-end">
                              <span class="mr-2">
                                <i class="fas fa-square text-primary"></i> Compra
                              </span>

                              <span>
                                <i class="fas fa-square text-gray"></i> Pago
                              </span>
                            </div>
                          </div>
                        </div>
                        <!-- /.card -->
                      </div>

                      <div class="col-lg-6">
                        <div class="card">
                          <div class="card-header border-0">
                            <h3 class="card-title">Productos mas usados</h3>
                            <div class="card-tools">
                              <a href="#" class="btn btn-tool btn-sm">
                                <i class="fas fa-download"></i>
                              </a>
                              <a href="#" class="btn btn-tool btn-sm">
                                <i class="fas fa-bars"></i>
                              </a>
                            </div>
                          </div>
                          <div class="card-body table-responsive p-0">
                            <table class="table table-striped table-valign-middle">
                              <thead>
                              <tr>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>Compra</th>
                                <th>Mas</th>
                              </tr>
                              </thead>
                              <tbody>
                              <tr>
                                <td>
                                  <img src="dist/img/default-150x150.png" alt="Product 1" onerror="this.src='../dist/svg/404-v2.svg';" class="img-thumbnail img-circle img-size-32 mr-2">
                                  CONCRETO PREMEZCLADO 210 KG/CM2
                                </td>
                                <td>S/ 13.00</td>
                                <td>
                                  <small class="text-success mr-1"> <i class="fas fa-arrow-up"></i> 12% </small>
                                  12,000 Sold
                                </td>
                                <td>
                                  <a href="resumen_insumos.php" class="text-muted"> <i class="fas fa-search"></i> </a>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <img src="dist/img/default-150x150.png" alt="Product 1" onerror="this.src='../dist/svg/404-v2.svg';" class="img-thumbnail img-circle img-size-32 mr-2">
                                  GASOLINA 90
                                </td>
                                <td>S/ 29.00</td>
                                <td>
                                  <small class="text-warning mr-1"> <i class="fas fa-arrow-down"></i> 0.5% </small>
                                  123,234 Sold
                                </td>
                                <td>
                                  <a href="resumen_insumos.php" class="text-muted"> <i class="fas fa-search"></i> </a>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <img src="dist/img/default-150x150.png" alt="Product 1" onerror="this.src='../dist/svg/404-v2.svg';" class="img-thumbnail img-circle img-size-32 mr-2">
                                  TABLAS DE 1X8X10 PIES LUPUNA
                                </td>
                                <td>S/ 1,230.00</td>
                                <td>
                                  <small class="text-danger mr-1"> <i class="fas fa-arrow-down"></i> 3% </small>
                                  198 Sold
                                </td>
                                <td>
                                  <a href="resumen_insumos.php" class="text-muted"> <i class="fas fa-search"></i> </a>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <img src="dist/img/default-150x150.png" alt="Product 1" onerror="this.src='../dist/svg/404-v2.svg';" class="img-thumbnail img-circle img-size-32 mr-2">
                                  FIERRO CORRUGADO 5/8" ACEROS AREQUIPA
                                  <span class="badge bg-danger">NEW</span>
                                </td>
                                <td>S/ 199.00</td>
                                <td>
                                  <small class="text-success mr-1"> <i class="fas fa-arrow-up"></i> 63%  </small>
                                  87 Sold
                                </td>
                                <td>
                                  <a href="resumen_insumos.php" class="text-muted"> <i class="fas fa-search"></i> </a>
                                </td>
                              </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                        <!-- /.card -->
                      </div>                     

                      <div class="col-lg-6">
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
        <script src="../plugins/chart.js/Chart.min.js"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="../dist/js/demo.js"></script>
        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        <!-- <script src="../dist/js/pages/dashboard3.js"></script> -->
        
        <script type="text/javascript" src="scripts/chart_compra_insumo.js"></script>         

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>

        <?php require 'extra_script.php'; ?>
        
      </body>
    </html>
    <?php    
  }

  ob_end_flush();
?>
