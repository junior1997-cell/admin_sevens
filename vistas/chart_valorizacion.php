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
        <title>Graficos Valorización | Admin Sevens</title>

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
                        <h1 class="m-0 h1-titulo">Reportes Valorización</h1>
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
                          <span class="info-box-icon bg-info elevation-1 cargando_filtro_valorizacion cursor-pointer" data-toggle="tooltip" data-original-title="Ver Módulos" onclick="ver_modulos();"><i class="fas fa-spinner fa-pulse"></i></span>
                          <div class="info-box-content">
                            <span class="info-box-number"> 
                              <select name="valorizacion_filtro" id="valorizacion_filtro" class="form-control select2" style="width: 100%;" onchange="chart_linea_barra();"> </select>                              
                            </span>
                          </div>
                          <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                      </div>
                      <!-- /.col -->

                      <div class="col-6 col-sm-6 col-md-3 col-lg-3  col-xl-3">
                        <div class="info-box mb-3">
                          <span class="info-box-icon bg-dark elevation-1"><i class="fas fa-layer-group"></i></span>
                          <div class="info-box-content">
                            <span class="info-box-text">Monto programado</span>
                            <span class="info-box-number monto_programado_box"> <i class="fas fa-spinner fa-pulse fa-lg"></i></span>
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
                          <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-hand-holding-usd"></i></span>
                          <div class="info-box-content">
                            <span class="info-box-text">Monto valorizado</span>
                            <span class="info-box-number monto_valorizado_box"> <i class="fas fa-spinner fa-pulse fa-lg"></i></span>
                          </div>
                          <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                      </div>
                      <!-- /.col -->

                      <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                        <div class="info-box mb-3">
                          <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-dollar-sign"></i></span>
                          <div class="info-box-content">
                            <span class="info-box-text">Monto gastado</span>
                            <span class="info-box-number monto_gastado_box"> <i class="fas fa-spinner fa-pulse fa-lg"></i></span>
                          </div>
                          <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                      </div>
                      <!-- /.col -->
                    </div>                    

                    <div class="row">

                      <div class="col-lg-12">
                        <div class="card">
                          <div class="card-header border-0 ">
                            <div class=" d-flex justify-content-center ">
                              <h3 class="card-title font-weight-bold">Montos por Valorización</h3>
                              <!-- <a href="javascript:void(0);">View Report</a> -->
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
                              <canvas id="visitors-chart" height="350">
                                
                              </canvas>
                              
                            </div>

                            <div class="d-flex flex-row justify-content-end">
                              <span class="mr-2"><i class="fas fa-square text-dark"></i> Programado</span>
                              <span>&nbsp;<i class="fas fa-square text-warning"></i> Valorizado</span>
                              <span>&nbsp;<i class="fas fa-square text-danger"></i> Gastado</span>
                            </div>
                          </div>
                        </div>
                        <!-- /.card -->
                      </div>

                      <div class="col-lg-12">
                        <div class="card">
                          <div class="card-header border-0">
                            <div class="d-flex justify-content-center">
                              <h3 class="card-title font-weight-bold">Utilidad por Valorización</h3>
                              <!-- <a href="javascript:void(0);">View Report</a> -->
                            </div>
                          </div>
                          <div class="card-body">
                            <div class="row">
                              <div class="col-md-8">
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
                                  <span class="mr-2"><i class="fas fa-square text-success"></i> Utildiad</span>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <p class="text-center">
                                  <strong>Utilidad Total</strong>
                                </p>

                                <div class="progress-group text-center">
                                  <h2 class="progress_utilidad_total" ><i class="fas fa-spinner fa-pulse fa-lg"></i></h2>
                                </div>
                                <!-- /.progress-group -->
                              </div>
                            </div>
                            
                          </div>
                        </div>
                        <!-- /.card -->
                      </div>

                      <div class="col-lg-6 hidden">
                        <div class="card">
                          <div class="card-header border-0">
                            <h3 class="card-title text-center">Productos mas usados</h3>
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
                                <th>Precio referencial</th>
                                <th>Cantidad</th>
                                <th>Mas</th>
                              </tr>
                              </thead>
                              <tbody id="tbla_productos_mas_vendidos">
                                <!-- aqui van los productos -->
                              </tbody>
                            </table>
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

                  <!-- MODAL - MODULOS INCLUIDOS  -->
                  <div class="modal fade" id="modal-modulos-incluidos">
                    <div class="modal-dialog modal-dialog-scrollable modal-md">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Módulos Incluidos</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body"> 
                          <ol>
                            <li class="m-b-04rem"><i class="fas fa-shopping-cart nav-icon"></i> COMPRAS INSUMOS</li>
                            <!-- <li>COMPRAS ACTIVOS FIJOS <small class="text-red">(sin proyecto)</small></li> -->
                            <li class="m-b-04rem"><img src="../dist/svg/negro-excabadora-ico.svg" class="nav-icon" alt="" style="width: 21px !important;" > SERVICIO MAQUINA </li>
                            <li class="m-b-04rem"><img src="../dist/svg/negro-estacion-total-ico.svg" class="nav-icon" alt="" style="width: 21px !important;" > SERVICIO EQUIPO</li>
                            <li class="m-b-04rem"><i class="nav-icon fas fa-hands-helping"></i> SUB CONTRATO</li>
                            <li class="m-b-04rem"><img src="../dist/svg/negro-planilla-seguro-ico.svg" class="nav-icon" alt="" style="width: 21px !important;" > PLANILLA SEGURO</li>
                            <li class="m-b-04rem"><i class="nav-icon fas fa-network-wired"></i> OTRO GASTO</li>
                            <li class="m-b-04rem"><i class="fas fa-shuttle-van nav-icon"></i> TRANSPORTE</li>
                            <li class="m-b-04rem"><i class="fas fa-hotel nav-icon"></i> HOSPEDAJE</li>
                            <li class="m-b-04rem"><i class="fas fa-utensils nav-icon"></i> PENSION</li>
                            <li class="m-b-04rem"><i class="fas fa-hamburger nav-icon"></i> BREAK</li>
                            <li class="m-b-04rem"><i class="fas fa-drumstick-bite nav-icon"></i> COMIDA EXTRA</li>
                            <!-- <li class="m-b-04rem"><i class="nav-icon fas fa-receipt"></i> OTRA FACTURA <small class="text-red">(sin proyecto)</small></li> -->
                            <!-- <li>OTRO INGRESO</li> -->
                          </ol>
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
        <script src="../plugins/chart.js/Chart.min.js"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="../dist/js/demo.js"></script>
        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        <!-- <script src="../dist/js/pages/dashboard3.js"></script> -->
        
        <script type="text/javascript" src="scripts/chart_valorizacion.js"></script>         

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>

        <?php require 'extra_script.php'; ?>
        
      </body>
    </html>
    <?php    
  }

  ob_end_flush();
?>
