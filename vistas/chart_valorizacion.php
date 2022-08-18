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
            if ($_SESSION['grafico_valorizacion']==1){
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

                      <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
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

                      <div class="col-12 col-sm-6 col-md-6 col-lg-3  col-xl-3">
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

                      <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
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

                      <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
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
                      <!-- CHART LINEA ══════════════════════════════════════════ -->
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
                              <canvas id="chart-line-curva-s" height="350">
                                
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
                      
                      <!-- CHART BARRAS ══════════════════════════════════════════ -->
                      <div class="col-lg-12">
                        <div class="card">
                          <div class="card-header border-0">
                            <div class="d-flex justify-content-center">
                              <h3 class="card-title font-weight-bold">Montos por Valorización</h3>
                              <!-- <a href="javascript:void(0);">View Report</a> -->
                            </div>
                          </div>
                          <div class="card-body">
                            <div class="row">
                              <div class="col-md-12">
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
                                  <canvas id="chart-barra-curva-s" height="350"></canvas> 
                                </div>

                                <div class="d-flex flex-row justify-content-end">
                                  <span class="mr-2"><i class="fas fa-square text-dark"></i> Programado</span>
                                  <span>&nbsp;<i class="fas fa-square text-warning"></i> Valorizado</span>
                                  <span>&nbsp;<i class="fas fa-square text-danger"></i> Gastado</span>
                                </div>
                              </div>
                              <div class="col-md-4 hidden">
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

                      <!-- CHART LINEA ══════════════════════════════════════════ -->
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
                                  <canvas id="chart-line-utilidad" height="350"></canvas>
                                </div>

                                <div class="d-flex flex-row justify-content-end">
                                  <span class="mr-2"><i class="fas fa-square leyenda_utilidad"></i> Utilidad</span>
                                  <span class="mr-2"><i class="fas fa-square" style="color: #008080;"></i> Gasto</span>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <p class="text-center"> <strong>Utilidad Total</strong> </p>
                                <div class="progress-group text-center mb-4">
                                  <h2 class="progress_utilidad_total" ><i class="fas fa-spinner fa-pulse fa-lg"></i></h2>
                                </div>
                                <!-- /.progress-group -->

                                <p class="text-center"> <strong>Gasto Total</strong> </p>
                                <div class="progress-group text-center">
                                  <h2 class="monto_gastado_box" ><i class="fas fa-spinner fa-pulse fa-lg"></i></h2>
                                </div>
                                <!-- /.progress-group -->
                              </div>
                            </div>
                            
                          </div>
                        </div>
                        <!-- /.card -->
                      </div>

                      <div class="col-lg-12 my-4 text-center">
                        <h1>Gastos en los Módulos</h1>
                      </div>

                      <!-- CHART LINEA - C O M P R A S   D E   I N S U M O S ══════════════════════════════════════════ -->
                      <div class="col-lg-12">
                        <div class="card">
                          <div class="card-header border-0">
                            <div class="d-flex justify-content-center">
                              <h3 class="card-title font-weight-bold">Compras de Insumos por Valorización</h3>
                              <!-- <a href="javascript:void(0);">View Report</a> -->
                            </div>
                          </div>
                          <div class="card-body">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="card-header border-0">
                                  
                                  <div class="card-tools">
                                    <button onclick="export_excel('#tabla_modulo_compra_insumos','Compras Insumos', 'compras');" class="btn btn-tool btn-sm"> <i class="fas fa-download"></i> </button>
                                    <a href="#" class="btn btn-tool btn-sm"> <i class="fas fa-bars"></i> </a>
                                  </div> 
                                </div>
                                <div class="card-body table-responsive p-0">
                                  <table class="table table-striped table-valign-middle" id="tabla_modulo_compra_insumos">
                                    <thead>
                                      <tr>
                                        <th class="text-center">#</th>                                       
                                        <th class="text-center">Gasto</th>
                                        <th class="text-center" >Acumulado</th>
                                        <th class="text-center">Utilidad</th>
                                        <th class="text-center" >Acumulado</th>
                                        <th class="text-center">Mas</th>
                                      </tr>
                                    </thead>
                                    <tbody id="body_modulo_compra_insumos"> <!-- aqui van los detalles --> </tbody>
                                    <tfoot>
                                      <tr>
                                        <th class="text-center"></th>                                   
                                        <th class="text-center pr-2"><div class="formato-numero-conta"><span>S/</span><span class="foot_total_gasto_compra_insumos"><i class="fas fa-spinner fa-pulse fa-1x"> </i></span></div></th>
                                        <th class="text-center" ></th>
                                        <th class="text-center pr-2"><div class="formato-numero-conta"><span>S/</span><span class="foot_total_utilidad_compra_insumos"><i class="fas fa-spinner fa-pulse fa-1x"> </i></span></div></th>
                                        <th class="text-center" ></th>
                                        <th class="text-center"></th>
                                      </tr>
                                    </tfoot>
                                  </table>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="row">
                                  <div class="col-md-12">                                  
                                    <!-- <div class="d-flex">
                                      <p class="d-flex flex-column">
                                        <span class="text-bold text-lg">$18,230.00</span><span>Sales Over Time</span>
                                      </p>
                                      <p class="ml-auto d-flex flex-column text-right">
                                        <span class="text-success"><i class="fas fa-arrow-up"></i> 33.1%</span>
                                        <span class="text-muted">Since last month</span>
                                      </p>
                                    </div>  -->
                                    <!-- /.d-flex -->

                                    <div class="position-relative mb-4">
                                      <canvas id="chart-line-compra-de-insumos" height="350"></canvas>
                                    </div>

                                    <div class="d-flex flex-row justify-content-end">
                                      <span class="mr-2"><i class="fas fa-square leyenda_utilidad_compra_insumo"></i> Utilidad</span>
                                      <span class="mr-2"><i class="fas fa-square" style="color: #008080;"></i> Compra de Insumo</span>
                                    </div>
                                  </div>
                                  <!-- linea divisoria -->
                                  <div class="col-lg-12 borde-arriba-naranja mt-3 mb-3"> </div>
                                  <div class="col-md-6">
                                    <p class="text-center mb-2"> <strong>Utilidad Total</strong> </p>
                                    <div class="progress-group text-center mb-4">
                                      <h2 class="progress_total_utilidad_compra_insumo" ><i class="fas fa-spinner fa-pulse fa-lg"></i></h2>
                                    </div>
                                    <!-- /.progress-group -->
                                  </div>
                                  <div class="col-md-6">
                                    <p class="text-center mb-2"> <strong>Compra de Insumo</strong> </p>
                                    <div class="progress-group text-center">
                                      <h2 class="progress_total_compra_insumo" ><i class="fas fa-spinner fa-pulse fa-lg"></i></h2>
                                    </div>
                                    <!-- /.progress-group -->
                                  </div>
                                </div>                                
                              </div>                              
                            </div>
                            
                          </div>
                        </div>
                        <!-- /.card -->
                      </div>

                      <!-- CHART LINEA - M A Q U I N A S   Y   E Q U I P O S ══════════════════════════════════════════ -->
                      <div class="col-lg-12">
                        <div class="card">
                          <div class="card-header border-0">
                            <div class="d-flex justify-content-center">
                              <h3 class="card-title font-weight-bold">Maquinas y Equipos por Valorización</h3>
                              <!-- <a href="javascript:void(0);">View Report</a> -->
                            </div>
                          </div>
                          <div class="card-body">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="card-header border-0">
                                  
                                  <div class="card-tools">
                                    <button onclick="export_excel('#tabla_modulo_maquina_y_equipo','Maquinas Equipos','');" class="btn btn-tool btn-sm"> <i class="fas fa-download"></i> </button>
                                    <a href="#" class="btn btn-tool btn-sm"> <i class="fas fa-bars"></i> </a>
                                  </div> 
                                </div>
                                <div class="card-body table-responsive p-0">
                                  <table class="table table-striped table-valign-middle" id="tabla_modulo_maquina_y_equipo">
                                    <thead>
                                      <tr>
                                        <th class="text-center">#</th>                                        
                                        <th class="text-center">Gasto</th>
                                        <th class="text-center" >Acumulado</th>
                                        <th class="text-center">Utilidad</th>
                                        <th class="text-center" >Acumulado</th>
                                        <th class="text-center">Mas</th>
                                      </tr>
                                    </thead>
                                    <tbody id="body_modulo_maquina_y_equipo"> <!-- aqui van los detalles --> </tbody>
                                    <tfoot>
                                      <tr>
                                        <th class="text-center"></th>                                        
                                        <th class="text-center pr-2"><div class="formato-numero-conta"><span>S/</span><span class="foot_total_gasto_maquina_y_equipo"><i class="fas fa-spinner fa-pulse fa-1x"> </i></span></div></th>
                                        <th class="text-center" ></th>
                                        <th class="text-center pr-2"><div class="formato-numero-conta"><span>S/</span><span class="foot_total_utilidad_maquina_y_equipo"><i class="fas fa-spinner fa-pulse fa-1x"> </i></span></div></th>
                                        <th class="text-center" ></th>
                                        <th class="text-center"></th>
                                      </tr>
                                    </tfoot>
                                  </table>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="row">
                                  <div class="col-md-12">
                                    <!-- <div class="d-flex">
                                      <p class="d-flex flex-column">
                                        <span class="text-bold text-lg">$18,230.00</span><span>Sales Over Time</span>
                                      </p>
                                      <p class="ml-auto d-flex flex-column text-right">
                                        <span class="text-success"> <i class="fas fa-arrow-up"></i> 33.1%</span>
                                        <span class="text-muted">Since last month</span>
                                      </p>
                                    </div> -->
                                    <!-- /.d-flex -->

                                    <div class="position-relative mb-4">
                                      <canvas id="chart-line-maquina-y-equipo" height="350"></canvas>
                                    </div>

                                    <div class="d-flex flex-row justify-content-end">
                                      <span class="mr-2"><i class="fas fa-square leyenda_utilidad_maquina_y_equipo"></i> Utilidad</span>
                                      <span class="mr-2"><i class="fas fa-square" style="color: #008080;"></i> Maquinas y Equipos</span>
                                    </div>
                                  </div>
                                  <!-- linea divisoria -->
                                  <div class="col-lg-12 borde-arriba-naranja mt-3 mb-3"> </div>
                                  <div class="col-md-6">
                                    <p class="text-center"> <strong>Utilidad Total</strong> </p>
                                    <div class="progress-group text-center mb-4">
                                      <h2 class="progress_total_utilidad_maquina_y_equipo" ><i class="fas fa-spinner fa-pulse fa-lg"></i></h2>
                                    </div>
                                    <!-- /.progress-group -->                                    
                                  </div>
                                  <div class="col-md-6">
                                    <p class="text-center"> <strong>Maquinas y Equipos</strong> </p>
                                    <div class="progress-group text-center">
                                      <h2 class="progress_total_maquina_y_equipo" ><i class="fas fa-spinner fa-pulse fa-lg"></i></h2>
                                    </div>
                                    <!-- /.progress-group -->
                                  </div>
                                </div>
                                <!-- /.row -->                                
                              </div>
                              <!-- /.col -->                              
                            </div>                            
                          </div>
                        </div>
                        <!-- /.card -->
                      </div>

                      <!-- CHART LINEA - S U B C O N T R A T O ══════════════════════════════════════════ -->
                      <div class="col-lg-12">
                        <div class="card">
                          <div class="card-header border-0">
                            <div class="d-flex justify-content-center">
                              <h3 class="card-title font-weight-bold">Subcontrato por Valorización</h3>
                              <!-- <a href="javascript:void(0);">View Report</a> -->
                            </div>
                          </div>
                          <div class="card-body">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="card-header border-0">
                                  
                                  <div class="card-tools">
                                    <button onclick="export_excel('#tabla_modulo_subcontrato','Subcontrato');" class="btn btn-tool btn-sm"> <i class="fas fa-download"></i> </button>
                                    <a href="#" class="btn btn-tool btn-sm"> <i class="fas fa-bars"></i> </a>
                                  </div> 
                                </div>
                                <div class="card-body table-responsive p-0">
                                  <table class="table table-striped table-valign-middle" id="tabla_modulo_subcontrato">
                                    <thead>
                                      <tr>
                                        <th class="text-center">#</th>                                        
                                        <th class="text-center">Gasto</th>
                                        <th class="text-center" >Acumulado</th>
                                        <th class="text-center">Utilidad</th>
                                        <th class="text-center" >Acumulado</th>
                                        <th class="text-center">Mas</th>
                                      </tr>
                                    </thead>
                                    <tbody id="body_modulo_subcontrato"> <!-- aqui van los detalles --> </tbody>
                                    <tfoot>
                                      <tr>
                                        <th class="text-center"></th>                                        
                                        <th class="text-center pr-2"><div class="formato-numero-conta"><span>S/</span><span class="foot_total_gasto_subcontrato"><i class="fas fa-spinner fa-pulse fa-1x"> </i></span></div></th>
                                        <th class="text-center" ></th>
                                        <th class="text-center pr-2"><div class="formato-numero-conta"><span>S/</span><span class="foot_total_utilidad_subcontrato"><i class="fas fa-spinner fa-pulse fa-1x"> </i></span></div></th>
                                        <th class="text-center" ></th>
                                        <th class="text-center"></th>
                                      </tr>
                                    </tfoot>
                                  </table>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="row">
                                  <div class="col-md-12">
                                    <!-- <div class="d-flex">
                                      <p class="d-flex flex-column">
                                        <span class="text-bold text-lg">$18,230.00</span> <span>Sales Over Time</span>
                                      </p>
                                      <p class="ml-auto d-flex flex-column text-right">
                                        <span class="text-success"><i class="fas fa-arrow-up"></i> 33.1% </span>
                                        <span class="text-muted">Since last month</span>
                                      </p>
                                    </div> -->
                                    <!-- /.d-flex -->

                                    <div class="position-relative mb-4">
                                      <canvas id="chart-line-subcontrato" height="350"></canvas>
                                    </div>

                                    <div class="d-flex flex-row justify-content-end">
                                      <span class="mr-2"><i class="fas fa-square leyenda_utilidad_subcontrato"></i> Utilidad</span>
                                      <span class="mr-2"><i class="fas fa-square" style="color: #008080;"></i> Subcontrato</span>
                                    </div>
                                  </div>
                                  <!-- linea divisoria -->
                                  <div class="col-lg-12 borde-arriba-naranja mt-3 mb-3"> </div>

                                  <div class="col-md-6">
                                    <p class="text-center"> <strong>Utilidad Total</strong> </p>
                                    <div class="progress-group text-center mb-4">
                                      <h2 class="progress_total_utilidad_subcontrato" ><i class="fas fa-spinner fa-pulse fa-lg"></i></h2>
                                    </div>
                                    <!-- /.progress-group -->                                    
                                  </div>                                  
                                  <div class="col-md-6">
                                    <p class="text-center"> <strong>Subcontrato</strong> </p>
                                    <div class="progress-group text-center">
                                      <h2 class="progress_total_subcontrato" ><i class="fas fa-spinner fa-pulse fa-lg"></i></h2>
                                    </div>
                                    <!-- /.progress-group -->
                                  </div>
                                </div>    
                                <!-- /.row -->                      
                              </div>     
                              <!-- /.col -->
                            </div>                            
                          </div>
                        </div>
                        <!-- /.card -->
                      </div>

                      <!-- CHART LINEA - P L A N I L L A   S E G U R O ══════════════════════════════════════════ -->
                      <div class="col-lg-12">
                        <div class="card">
                          <div class="card-header border-0">
                            <div class="d-flex justify-content-center">
                              <h3 class="card-title font-weight-bold">Planilla Seguro por Valorización</h3>
                              <!-- <a href="javascript:void(0);">View Report</a> -->
                            </div>
                          </div>
                          <div class="card-body">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="card-header border-0">
                                  
                                  <div class="card-tools">
                                    <button onclick="export_excel('#tabla_modulo_planilla_seguro', 'Panilla Seguro','planilla');" class="btn btn-tool btn-sm"> <i class="fas fa-download"></i> </button>
                                    <a href="#" class="btn btn-tool btn-sm"> <i class="fas fa-bars"></i> </a>
                                  </div> 
                                </div>
                                <div class="card-body table-responsive p-0">
                                  <table class="table table-striped table-valign-middle" id="tabla_modulo_planilla_seguro">
                                    <thead>
                                      <tr>
                                        <th class="text-center">#</th>                                        
                                        <th class="text-center">Gasto</th>
                                        <th class="text-center" >Acumulado</th>
                                        <th class="text-center">Utilidad</th>
                                        <th class="text-center" >Acumulado</th>
                                        <th class="text-center">Mas</th>
                                      </tr>
                                    </thead>
                                    <tbody id="body_modulo_planilla_seguro"> <!-- aqui van los detalles --> </tbody>
                                    <tfoot>
                                      <tr>
                                        <th class="text-center"></th>                                        
                                        <th class="text-center pr-2"><div class="formato-numero-conta"><span>S/</span><span class="foot_total_gasto_planilla_seguro"><i class="fas fa-spinner fa-pulse fa-1x"> </i></span></div></th>
                                        <th class="text-center" ></th>
                                        <th class="text-center pr-2"><div class="formato-numero-conta"><span>S/</span><span class="foot_total_utilidad_planilla_seguro"><i class="fas fa-spinner fa-pulse fa-1x"> </i></span></div></th>
                                        <th class="text-center" ></th>
                                        <th class="text-center"></th>
                                      </tr>
                                    </tfoot>
                                  </table>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="row">
                                  <div class="col-md-12">
                                    <!-- <div class="d-flex">
                                      <p class="d-flex flex-column">
                                        <span class="text-bold text-lg">$18,230.00</span><span>Sales Over Time</span>
                                      </p>
                                      <p class="ml-auto d-flex flex-column text-right">
                                        <span class="text-success"> <i class="fas fa-arrow-up"></i> 33.1% </span>
                                        <span class="text-muted">Since last month</span>
                                      </p>
                                    </div> -->
                                    <!-- /.d-flex -->

                                    <div class="position-relative mb-4">
                                      <canvas id="chart-line-planilla-seguro" height="350"></canvas>
                                    </div>

                                    <div class="d-flex flex-row justify-content-end">
                                      <span class="mr-2"><i class="fas fa-square leyenda_utilidad_planilla_seguro"></i> Utilidad</span>
                                      <span class="mr-2"><i class="fas fa-square" style="color: #008080;"></i> Planilla Seguro</span>
                                    </div>
                                  </div>
                                  <!-- linea divisoria -->
                                  <div class="col-lg-12 borde-arriba-naranja mt-3 mb-3"> </div>
                                  <div class="col-md-6">
                                    <p class="text-center"> <strong>Utilidad Total</strong> </p>
                                    <div class="progress-group text-center mb-4">
                                      <h2 class="progress_total_utilidad_planilla_seguro" ><i class="fas fa-spinner fa-pulse fa-lg"></i></h2>
                                    </div>
                                    <!-- /.progress-group -->                                    
                                  </div>
                                  <div class="col-md-6">
                                    <p class="text-center"> <strong>Planilla Seguro</strong> </p>
                                    <div class="progress-group text-center">
                                      <h2 class="progress_total_planilla_seguro" ><i class="fas fa-spinner fa-pulse fa-lg"></i></h2>
                                    </div>
                                    <!-- /.progress-group -->
                                  </div>
                                </div>
                                <!-- /.row -->
                              </div>
                              <!-- /.col -->                              
                            </div>                            
                          </div>
                        </div>
                        <!-- /.card -->
                      </div>

                      <!-- CHART LINEA - O T R O   G A S T O ══════════════════════════════════════════ -->
                      <div class="col-lg-12">
                        <div class="card">
                          <div class="card-header border-0">
                            <div class="d-flex justify-content-center">
                              <h3 class="card-title font-weight-bold">Otro Gasto por Valorización</h3>
                              <!-- <a href="javascript:void(0);">View Report</a> -->
                            </div>
                          </div>
                          <div class="card-body">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="card-header border-0">
                                  
                                  <div class="card-tools">
                                    <button onclick="export_excel('#tabla_modulo_otro_gasto', 'Otro Gasto','gasto');" class="btn btn-tool btn-sm"> <i class="fas fa-download"></i> </button>
                                    <a href="#" class="btn btn-tool btn-sm"> <i class="fas fa-bars"></i> </a>
                                  </div> 
                                </div>
                                <div class="card-body table-responsive p-0">
                                  <table class="table table-striped table-valign-middle" id="tabla_modulo_otro_gasto">
                                    <thead>
                                      <tr>
                                        <th class="text-center">#</th>                                        
                                        <th class="text-center">Gasto</th>
                                        <th class="text-center" >Acumulado</th>
                                        <th class="text-center">Utilidad</th>
                                        <th class="text-center">Acumulado</th>
                                        <th class="text-center">Mas</th>
                                      </tr>
                                    </thead>
                                    <tbody id="body_modulo_otro_gasto"> <!-- aqui van los detalles --> </tbody>
                                    <tfoot>
                                      <tr>
                                        <th class="text-center"></th>                                        
                                        <th class="text-center pr-2"><div class="formato-numero-conta"><span>S/</span><span class="foot_total_gasto_otro_gasto"><i class="fas fa-spinner fa-pulse fa-1x"> </i></span></div></th>
                                        <th class="text-center" ></th>
                                        <th class="text-center pr-2"><div class="formato-numero-conta"><span>S/</span><span class="foot_total_utilidad_otro_gasto"><i class="fas fa-spinner fa-pulse fa-1x"> </i></span></div></th>
                                        <th class="text-center" ></th>
                                        <th class="text-center"></th>
                                      </tr>
                                    </tfoot>
                                  </table>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="row">
                                  <div class="col-md-12">
                                    <!-- <div class="d-flex">
                                      <p class="d-flex flex-column">
                                        <span class="text-bold text-lg">$18,230.00</span> <span>Sales Over Time</span>
                                      </p>
                                      <p class="ml-auto d-flex flex-column text-right">
                                        <span class="text-success"> <i class="fas fa-arrow-up"></i> 33.1%</span>
                                        <span class="text-muted">Since last month</span>
                                      </p>
                                    </div> -->
                                    <!-- /.d-flex -->

                                    <div class="position-relative mb-4">
                                      <canvas id="chart-line-otro-gasto" height="350"></canvas>
                                    </div>

                                    <div class="d-flex flex-row justify-content-end">
                                      <span class="mr-2"><i class="fas fa-square leyenda_utilidad_otro_gasto"></i> Utilidad</span>
                                      <span class="mr-2"><i class="fas fa-square" style="color: #008080;"></i> Otro Gasto</span>
                                    </div>
                                  </div>
                                  <!-- linea divisoria -->
                                  <div class="col-lg-12 borde-arriba-naranja mt-3 mb-3"> </div>
                                  <div class="col-md-6">
                                    <p class="text-center"> <strong>Utilidad Total</strong> </p>
                                    <div class="progress-group text-center mb-4">
                                      <h2 class="progress_total_utilidad_otro_gasto" ><i class="fas fa-spinner fa-pulse fa-lg"></i></h2>
                                    </div>
                                    <!-- /.progress-group -->                                    
                                  </div>
                                  <div class="col-md-6">
                                    <p class="text-center"> <strong>Otro Gasto</strong> </p>
                                    <div class="progress-group text-center">
                                      <h2 class="progress_total_otro_gasto" ><i class="fas fa-spinner fa-pulse fa-lg"></i></h2>
                                    </div>
                                    <!-- /.progress-group -->
                                  </div>
                                </div>
                                <!-- /.row -->
                              </div>
                              <!-- /.col -->                              
                            </div>                            
                          </div>
                        </div>
                        <!-- /.card -->
                      </div>

                      <!-- CHART LINEA - T R A N S P O R T E ══════════════════════════════════════════ -->
                      <div class="col-lg-12">
                        <div class="card">
                          <div class="card-header border-0">
                            <div class="d-flex justify-content-center">
                              <h3 class="card-title font-weight-bold">Transporte por Valorización</h3>
                              <!-- <a href="javascript:void(0);">View Report</a> -->
                            </div>
                          </div>
                          <div class="card-body">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="card-header border-0">
                                  
                                  <div class="card-tools">
                                    <button onclick="export_excel('#tabla_modulo_transporte','Transporte');" class="btn btn-tool btn-sm"> <i class="fas fa-download"></i> </button>
                                    <a href="#" class="btn btn-tool btn-sm"> <i class="fas fa-bars"></i> </a>
                                  </div> 
                                </div>
                                <div class="card-body table-responsive p-0">
                                  <table class="table table-striped table-valign-middle" id="tabla_modulo_transporte">
                                    <thead>
                                      <tr>
                                        <th class="text-center">#</th>                                        
                                        <th class="text-center">Gasto</th>
                                        <th class="text-center" >Acumulado</th>
                                        <th class="text-center">Utilidad</th>
                                        <th class="text-center">Acumulado</th>
                                        <th class="text-center">Mas</th>
                                      </tr>
                                    </thead>
                                    <tbody id="body_modulo_transporte"> <!-- aqui van los detalles --> </tbody>
                                    <tfoot>
                                      <tr>
                                        <th class="text-center"></th>                                        
                                        <th class="text-center pr-2"><div class="formato-numero-conta"><span>S/</span><span class="foot_total_gasto_transporte"><i class="fas fa-spinner fa-pulse fa-1x"> </i></span></div></th>
                                        <th class="text-center" ></th>
                                        <th class="text-center pr-2"><div class="formato-numero-conta"><span>S/</span><span class="foot_total_utilidad_transporte"><i class="fas fa-spinner fa-pulse fa-1x"> </i></span></div></th>
                                        <th class="text-center" ></th>
                                        <th class="text-center"></th>
                                      </tr>
                                    </tfoot>
                                  </table>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="row">
                                  <div class="col-md-12">
                                    <!-- <div class="d-flex">
                                      <p class="d-flex flex-column">
                                        <span class="text-bold text-lg">$18,230.00</span> <span>Sales Over Time</span>
                                      </p>
                                      <p class="ml-auto d-flex flex-column text-right">
                                        <span class="text-success"> <i class="fas fa-arrow-up"></i> 33.1% </span>
                                        <span class="text-muted">Since last month</span>
                                      </p>
                                    </div> -->
                                    <!-- /.d-flex -->

                                    <div class="position-relative mb-4">
                                      <canvas id="chart-line-transporte" height="350"></canvas>
                                    </div>

                                    <div class="d-flex flex-row justify-content-end">
                                      <span class="mr-2"><i class="fas fa-square leyenda_utilidad_transporte"></i> Utilidad</span>
                                      <span class="mr-2"><i class="fas fa-square" style="color: #008080;"></i> Transporte</span>
                                    </div>
                                  </div>
                                  <!-- linea divisoria -->
                                  <div class="col-lg-12 borde-arriba-naranja mt-3 mb-3"> </div>
                                  <div class="col-md-6">
                                    <p class="text-center"> <strong>Utilidad Total</strong> </p>
                                    <div class="progress-group text-center mb-4">
                                      <h2 class="progress_total_utilidad_transporte" ><i class="fas fa-spinner fa-pulse fa-lg"></i></h2>
                                    </div>
                                    <!-- /.progress-group -->
                                  </div>
                                  <div class="colmd-6">
                                    <p class="text-center"> <strong>Transporte</strong> </p>
                                    <div class="progress-group text-center">
                                      <h2 class="progress_total_transporte" ><i class="fas fa-spinner fa-pulse fa-lg"></i></h2>
                                    </div>
                                    <!-- /.progress-group -->
                                  </div>
                                </div>
                                <!-- /.row -->                                
                              </div>
                              <!-- /.col -->                              
                            </div>
                            <!-- /.row -->
                          </div>
                        </div>
                        <!-- /.card -->
                      </div>

                      <!-- CHART LINEA - H O S P E D A J E ══════════════════════════════════════════ -->
                      <div class="col-lg-12">
                        <div class="card">
                          <div class="card-header border-0">
                            <div class="d-flex justify-content-center">
                              <h3 class="card-title font-weight-bold">Hospedaje por Valorización</h3>
                              <!-- <a href="javascript:void(0);">View Report</a> -->
                            </div>
                          </div>
                          <div class="card-body">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="card-header border-0">
                                  
                                  <div class="card-tools">
                                    <button onclick="export_excel('#tabla_modulo_hospedaje', 'Hospedaje');" class="btn btn-tool btn-sm"> <i class="fas fa-download"></i> </button>
                                    <a href="#" class="btn btn-tool btn-sm"> <i class="fas fa-bars"></i> </a>
                                  </div> 
                                </div>
                                <div class="card-body table-responsive p-0">
                                  <table class="table table-striped table-valign-middle" id="tabla_modulo_hospedaje">
                                    <thead>
                                      <tr>
                                        <th class="text-center">#</th>                                        
                                        <th class="text-center">Gasto</th>
                                        <th class="text-center" >Acumulado</th>
                                        <th class="text-center">Utilidad</th>
                                        <th class="text-center">Acumulado</th>
                                        <th class="text-center">Mas</th>
                                      </tr>
                                    </thead>
                                    <tbody id="body_modulo_hospedaje"> <!-- aqui van los detalles --> </tbody>
                                    <tfoot>
                                      <tr>
                                        <th class="text-center"></th>                                        
                                        <th class="text-center pr-2"><div class="formato-numero-conta"><span>S/</span><span class="foot_total_gasto_hospedaje"><i class="fas fa-spinner fa-pulse fa-1x"> </i></span></div></th>
                                        <th class="text-center" ></th>
                                        <th class="text-center pr-2"><div class="formato-numero-conta"><span>S/</span><span class="foot_total_utilidad_hospedaje"><i class="fas fa-spinner fa-pulse fa-1x"> </i></span></div></th>
                                        <th class="text-center" ></th>
                                        <th class="text-center"></th>
                                      </tr>
                                    </tfoot>
                                  </table>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="row">
                                  <div class="col-md-12">
                                    <!-- <div class="d-flex">
                                      <p class="d-flex flex-column">
                                        <span class="text-bold text-lg">$18,230.00</span><span>Sales Over Time</span>
                                      </p>
                                      <p class="ml-auto d-flex flex-column text-right">
                                        <span class="text-success"> <i class="fas fa-arrow-up"></i> 33.1% </span>
                                        <span class="text-muted">Since last month</span>
                                      </p>
                                    </div> -->
                                    <!-- /.d-flex -->

                                    <div class="position-relative mb-4">
                                      <canvas id="chart-line-hospedaje" height="350"></canvas>
                                    </div>

                                    <div class="d-flex flex-row justify-content-end">
                                      <span class="mr-2"><i class="fas fa-square leyenda_utilidad_hospedaje"></i> Utilidad</span>
                                      <span class="mr-2"><i class="fas fa-square" style="color: #008080;"></i> Hospedaje</span>
                                    </div>
                                  </div>
                                  <!-- linea divisoria -->
                                  <div class="col-lg-12 borde-arriba-naranja mt-3 mb-3"> </div>
                                  <div class="col-md-6">
                                    <p class="text-center"> <strong>Utilidad Total</strong> </p>
                                    <div class="progress-group text-center mb-4">
                                      <h2 class="progress_total_utilidad_hospedaje" ><i class="fas fa-spinner fa-pulse fa-lg"></i></h2>
                                    </div>
                                    <!-- /.progress-group -->                                    
                                  </div>
                                  <div class="col-md-6">
                                    <p class="text-center"> <strong>Hospedaje</strong> </p>
                                    <div class="progress-group text-center">
                                      <h2 class="progress_total_hospedaje" ><i class="fas fa-spinner fa-pulse fa-lg"></i></h2>
                                    </div>
                                    <!-- /.progress-group -->
                                  </div>
                                </div>
                                <!-- /.row -->                                
                              </div>
                              <!-- /.col -->                              
                            </div>      
                            <!-- /.row -->                      
                          </div>
                        </div>
                        <!-- /.card -->
                      </div>

                      <!-- CHART LINEA - P E N S I O N ══════════════════════════════════════════ -->
                      <div class="col-lg-12">
                        <div class="card">
                          <div class="card-header border-0">
                            <div class="d-flex justify-content-center">
                              <h3 class="card-title font-weight-bold">Pension por Valorización</h3>
                              <!-- <a href="javascript:void(0);">View Report</a> -->
                            </div>
                          </div>
                          <div class="card-body">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="card-header border-0">
                                  
                                  <div class="card-tools">
                                    <button onclick="export_excel('#tabla_modulo_pension', 'Pension');" class="btn btn-tool btn-sm"> <i class="fas fa-download"></i> </button>
                                    <a href="#" class="btn btn-tool btn-sm"> <i class="fas fa-bars"></i> </a>
                                  </div> 
                                </div>
                                <div class="card-body table-responsive p-0">
                                  <table class="table table-striped table-valign-middle" id="tabla_modulo_pension">
                                    <thead>
                                      <tr>
                                        <th class="text-center">#</th>                                        
                                        <th class="text-center">Gasto</th>
                                        <th class="text-center" >Acumulado</th>
                                        <th class="text-center">Utilidad</th>
                                        <th class="text-center">Acumulado</th>
                                        <th class="text-center">Mas</th>
                                      </tr>
                                    </thead>
                                    <tbody id="body_modulo_pension"> <!-- aqui van los detalles --> </tbody>
                                    <tfoot>
                                      <tr>
                                        <th class="text-center"></th>                                        
                                        <th class="text-center pr-2"><div class="formato-numero-conta"><span>S/</span><span class="foot_total_gasto_pension"><i class="fas fa-spinner fa-pulse fa-1x"> </i></span></div></th>
                                        <th class="text-center" ></th>
                                        <th class="text-center pr-2"><div class="formato-numero-conta"><span>S/</span><span class="foot_total_utilidad_pension"><i class="fas fa-spinner fa-pulse fa-1x"> </i></span></div></th>
                                        <th class="text-center" ></th>
                                        <th class="text-center"></th>
                                      </tr>
                                    </tfoot>
                                  </table>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="row">
                                  <div class="col-md-12">
                                    <!-- <div class="d-flex">
                                      <p class="d-flex flex-column"> <span class="text-bold text-lg">$18,230.00</span> <span>Sales Over Time</span>
                                      </p>
                                      <p class="ml-auto d-flex flex-column text-right">
                                        <span class="text-success"> <i class="fas fa-arrow-up"></i> 33.1% </span>
                                        <span class="text-muted">Since last month</span>
                                      </p>
                                    </div> -->
                                    <!-- /.d-flex -->

                                    <div class="position-relative mb-4">
                                      <canvas id="chart-line-pension" height="350"></canvas>
                                    </div>

                                    <div class="d-flex flex-row justify-content-end">
                                      <span class="mr-2"><i class="fas fa-square leyenda_utilidad_pension"></i> Utilidad</span>
                                      <span class="mr-2"><i class="fas fa-square" style="color: #008080;"></i> Pension</span>
                                    </div>
                                  </div>
                                  <!-- linea divisoria -->
                                  <div class="col-lg-12 borde-arriba-naranja mt-3 mb-3"> </div>
                                  <div class="col-md-6">
                                    <p class="text-center"> <strong>Utilidad Total</strong> </p>
                                    <div class="progress-group text-center mb-4">
                                      <h2 class="progress_total_utilidad_pension" ><i class="fas fa-spinner fa-pulse fa-lg"></i></h2>
                                    </div>
                                    <!-- /.progress-group -->                                   
                                  </div>
                                  <div class="col-md-6">
                                    <p class="text-center"> <strong>Pension</strong> </p>
                                    <div class="progress-group text-center">
                                      <h2 class="progress_total_pension" ><i class="fas fa-spinner fa-pulse fa-lg"></i></h2>
                                    </div>
                                    <!-- /.progress-group -->
                                  </div>
                                </div>
                                <!-- /.row -->
                              </div>
                              <!-- /.col -->                              
                            </div>
                            <!-- /.row -->
                          </div>
                        </div>
                        <!-- /.card -->
                      </div>

                      <!-- CHART LINEA - B R E A C K ══════════════════════════════════════════ -->
                      <div class="col-lg-12">
                        <div class="card">
                          <div class="card-header border-0">
                            <div class="d-flex justify-content-center">
                              <h3 class="card-title font-weight-bold">Breack por Valorización</h3>
                              <!-- <a href="javascript:void(0);">View Report</a> -->
                            </div>
                          </div>
                          <div class="card-body">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="card-header border-0">
                                  
                                  <div class="card-tools">
                                    <button onclick="export_excel('#tabla_modulo_breack', 'Breack');" class="btn btn-tool btn-sm"> <i class="fas fa-download"></i> </button>
                                    <a href="#" class="btn btn-tool btn-sm"> <i class="fas fa-bars"></i> </a>
                                  </div> 
                                </div>
                                <div class="card-body table-responsive p-0">
                                  <table class="table table-striped table-valign-middle" id="tabla_modulo_breack">
                                    <thead>
                                      <tr>
                                        <th class="text-center">#</th>                                        
                                        <th class="text-center">Gasto</th>
                                        <th class="text-center" >Acumulado</th>
                                        <th class="text-center">Utilidad</th>
                                        <th class="text-center">Acumulado</th>
                                        <th class="text-center">Mas</th>
                                      </tr>
                                    </thead>
                                    <tbody id="body_modulo_breack"> <!-- aqui van los detalles --> </tbody>
                                    <tfoot>
                                      <tr>
                                        <th class="text-center"></th>                                        
                                        <th class="text-center pr-2"><div class="formato-numero-conta"><span>S/</span><span class="foot_total_gasto_breack"><i class="fas fa-spinner fa-pulse fa-1x"> </i></span></div></th>
                                        <th class="text-center" ></th>
                                        <th class="text-center pr-2"><div class="formato-numero-conta"><span>S/</span><span class="foot_total_utilidad_breack"><i class="fas fa-spinner fa-pulse fa-1x"> </i></span></div></th>
                                        <th class="text-center" ></th>
                                        <th class="text-center"></th>
                                      </tr>
                                    </tfoot>
                                  </table>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="row">
                                  <div class="col-md-12">
                                    <!-- <div class="d-flex">
                                      <p class="d-flex flex-column">
                                        <span class="text-bold text-lg">$18,230.00</span> <span>Sales Over Time</span>
                                      </p>
                                      <p class="ml-auto d-flex flex-column text-right">
                                        <span class="text-success"><i class="fas fa-arrow-up"></i> 33.1% </span>
                                        <span class="text-muted">Since last month</span>
                                      </p>
                                    </div> -->
                                    <!-- /.d-flex -->

                                    <div class="position-relative mb-4">
                                      <canvas id="chart-line-breack" height="350"></canvas>
                                    </div>

                                    <div class="d-flex flex-row justify-content-end">
                                      <span class="mr-2"><i class="fas fa-square leyenda_utilidad_breack"></i> Utilidad</span>
                                      <span class="mr-2"><i class="fas fa-square" style="color: #008080;"></i> breack</span>
                                    </div>
                                  </div>
                                  <!-- linea divisoria -->
                                  <div class="col-lg-12 borde-arriba-naranja mt-3 mb-3"> </div>
                                  <div class="col-md-6">
                                    <p class="text-center"> <strong>Utilidad Total</strong> </p>
                                    <div class="progress-group text-center mb-4">
                                      <h2 class="progress_total_utilidad_breack" ><i class="fas fa-spinner fa-pulse fa-lg"></i></h2>
                                    </div>
                                    <!-- /.progress-group -->                                    
                                  </div>
                                  <div class="col-md-6">
                                    <p class="text-center"> <strong>breack</strong> </p>
                                    <div class="progress-group text-center">
                                      <h2 class="progress_total_breack" ><i class="fas fa-spinner fa-pulse fa-lg"></i></h2>
                                    </div>
                                    <!-- /.progress-group -->
                                  </div>
                                </div>
                                
                              </div>
                              
                            </div>
                            
                          </div>
                        </div>
                        <!-- /.card -->
                      </div>

                      <!-- CHART LINEA - C O M I D A   E X T R A ══════════════════════════════════════════ -->
                      <div class="col-lg-12">
                        <div class="card">
                          <div class="card-header border-0">
                            <div class="d-flex justify-content-center">
                              <h3 class="card-title font-weight-bold">Comida Extra por Valorización</h3>
                              <!-- <a href="javascript:void(0);">View Report</a> -->
                            </div>
                          </div>
                          <div class="card-body">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="card-header border-0">
                                  
                                  <div class="card-tools">
                                    <button onclick="export_excel('#tabla_modulo_comida_extra', 'Comida Extra');" class="btn btn-tool btn-sm"> <i class="fas fa-download"></i> </button>
                                    <a href="#" class="btn btn-tool btn-sm"> <i class="fas fa-bars"></i> </a>
                                  </div> 
                                </div>
                                <div class="card-body table-responsive p-0">
                                  <table class="table table-striped table-valign-middle" id="tabla_modulo_comida_extra">
                                    <thead>
                                      <tr>
                                        <th class="text-center">#</th>                                        
                                        <th class="text-center">Gasto</th>
                                        <th class="text-center" >Acumulado</th>
                                        <th class="text-center">Utilidad</th>
                                        <th class="text-center">Acumulado</th>
                                        <th class="text-center">Mas</th>
                                      </tr>
                                    </thead>
                                    <tbody id="body_modulo_comida_extra"> <!-- aqui van los detalles --> </tbody>
                                    <tfoot>
                                      <tr>
                                        <th class="text-center"></th>                                        
                                        <th class="text-center pr-2"><div class="formato-numero-conta"><span>S/</span><span class="foot_total_gasto_comida_extra"><i class="fas fa-spinner fa-pulse fa-1x"> </i></span></div></th>
                                        <th class="text-center" ></th>
                                        <th class="text-center pr-2"><div class="formato-numero-conta"><span>S/</span><span class="foot_total_utilidad_comida_extra"><i class="fas fa-spinner fa-pulse fa-1x"> </i></span></div></th>
                                        <th class="text-center" ></th>
                                        <th class="text-center"></th>
                                      </tr>
                                    </tfoot>
                                  </table>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="row">
                                  <div class="col-md-12">
                                    <!-- <div class="d-flex">
                                      <p class="d-flex flex-column">
                                        <span class="text-bold text-lg">$18,230.00</span><span>Sales Over Time</span>
                                      </p>
                                      <p class="ml-auto d-flex flex-column text-right">
                                        <span class="text-success"><i class="fas fa-arrow-up"></i> 33.1% </span>
                                        <span class="text-muted">Since last month</span>
                                      </p>
                                    </div> -->
                                    <!-- /.d-flex -->

                                    <div class="position-relative mb-4">
                                      <canvas id="chart-line-comida-extra" height="350"></canvas>
                                    </div>

                                    <div class="d-flex flex-row justify-content-end">
                                      <span class="mr-2"><i class="fas fa-square leyenda_utilidad_comida_extra"></i> Utilidad</span>
                                      <span class="mr-2"><i class="fas fa-square" style="color: #008080;"></i> Comida Extra</span>
                                    </div>
                                  </div>
                                  <!-- linea divisoria -->
                                  <div class="col-lg-12 borde-arriba-naranja mt-3 mb-3"> </div>
                                  <div class="col-md-6">
                                    <p class="text-center"> <strong>Utilidad Total</strong> </p>
                                    <div class="progress-group text-center mb-4">
                                      <h2 class="progress_total_utilidad_comida_extra" ><i class="fas fa-spinner fa-pulse fa-lg"></i></h2>
                                    </div>
                                    <!-- /.progress-group -->                                    
                                  </div>
                                  <div class="col-md-6">
                                    <p class="text-center"> <strong>Comida Extra</strong> </p>
                                    <div class="progress-group text-center">
                                      <h2 class="progress_total_comida_extra" ><i class="fas fa-spinner fa-pulse fa-lg"></i></h2>
                                    </div>
                                    <!-- /.progress-group -->
                                  </div>
                                </div>
                                <!-- /.row -->
                              </div>
                              <!-- /.col -->                              
                            </div>
                            <!-- /.row -->
                          </div>
                        </div>
                        <!-- /.card -->
                      </div>

                      <!-- CHART LINEA - P A G O   A D M I N I S T R A D O R ══════════════════════════════════════════ -->
                      <div class="col-lg-12">
                        <div class="card">
                          <div class="card-header border-0">
                            <div class="d-flex justify-content-center">
                              <h3 class="card-title font-weight-bold">Pago Administrador por Valorización</h3>
                              <!-- <a href="javascript:void(0);">View Report</a> -->
                            </div>
                          </div>
                          <div class="card-body">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="card-header border-0">
                                  
                                  <div class="card-tools">
                                    <button onclick="export_excel('#tabla_modulo_pago_administrador', 'Pago Administrador');" class="btn btn-tool btn-sm"> <i class="fas fa-download"></i> </button>
                                    <a href="#" class="btn btn-tool btn-sm"> <i class="fas fa-bars"></i> </a>
                                  </div> 
                                </div>
                                <div class="card-body table-responsive p-0">
                                  <table class="table table-striped table-valign-middle" id="tabla_modulo_pago_administrador">
                                    <thead>
                                      <tr>
                                        <th class="text-center">#</th>                                        
                                        <th class="text-center">Gasto</th>
                                        <th class="text-center" >Acumulado</th>
                                        <th class="text-center">Utilidad</th>
                                        <th class="text-center">Acumulado</th>
                                        <th class="text-center">Mas</th>
                                      </tr>
                                    </thead>
                                    <tbody id="body_modulo_pago_administrador"> <!-- aqui van los detalles --> </tbody>
                                    <tfoot>
                                      <tr>
                                        <th class="text-center"></th>                                        
                                        <th class="text-center pr-2"><div class="formato-numero-conta"><span>S/</span><span class="foot_total_gasto_pago_administrador"><i class="fas fa-spinner fa-pulse fa-1x"> </i></span></div></th>
                                        <th class="text-center" ></th>
                                        <th class="text-center pr-2"><div class="formato-numero-conta"><span>S/</span><span class="foot_total_utilidad_pago_administrador"><i class="fas fa-spinner fa-pulse fa-1x"> </i></span></div></th>
                                        <th class="text-center" ></th>
                                        <th class="text-center"></th>
                                      </tr>
                                    </tfoot>
                                  </table>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="row">
                                  <div class="col-md-12">
                                    <!-- <div class="d-flex">
                                      <p class="d-flex flex-column">
                                        <span class="text-bold text-lg">$18,230.00</span><span>Sales Over Time</span>
                                      </p>
                                      <p class="ml-auto d-flex flex-column text-right">
                                        <span class="text-success"><i class="fas fa-arrow-up"></i> 33.1% </span>
                                        <span class="text-muted">Since last month</span>
                                      </p>
                                    </div> -->
                                    <!-- /.d-flex -->

                                    <div class="position-relative mb-4">
                                      <canvas id="chart-line-pago-administrador" height="350"></canvas>
                                    </div>

                                    <div class="d-flex flex-row justify-content-end">
                                      <span class="mr-2"><i class="fas fa-square leyenda_utilidad_pago_administrador"></i> Utilidad</span>
                                      <span class="mr-2"><i class="fas fa-square" style="color: #008080;"></i> Pago Administrador</span>
                                    </div>
                                  </div>
                                  <!-- linea divisoria -->
                                  <div class="col-lg-12 borde-arriba-naranja mt-3 mb-3"> </div>
                                  <div class="col-md-6">
                                    <p class="text-center"> <strong>Utilidad Total</strong> </p>
                                    <div class="progress-group text-center mb-4">
                                      <h2 class="progress_total_utilidad_pago_administrador" ><i class="fas fa-spinner fa-pulse fa-lg"></i></h2>
                                    </div>
                                    <!-- /.progress-group -->                                    
                                  </div>
                                  <div class="col-md-6">
                                    <p class="text-center"> <strong>Pago Administrador</strong> </p>
                                    <div class="progress-group text-center">
                                      <h2 class="progress_total_pago_administrador" ><i class="fas fa-spinner fa-pulse fa-lg"></i></h2>
                                    </div>
                                    <!-- /.progress-group -->
                                  </div>
                                </div>
                                <!-- /.row -->
                              </div>
                              <!-- /.col -->                              
                            </div>
                            <!-- /.row -->
                          </div>
                        </div>
                        <!-- /.card -->
                      </div>

                      <!-- CHART LINEA - P A G O   O B R E R O ══════════════════════════════════════════ -->
                      <div class="col-lg-12">
                        <div class="card">
                          <div class="card-header border-0">
                            <div class="d-flex justify-content-center">
                              <h3 class="card-title font-weight-bold">Pago Obrero por Valorización</h3>
                              <!-- <a href="javascript:void(0);">View Report</a> -->
                            </div>
                          </div>
                          <div class="card-body">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="card-header border-0">
                                  
                                  <div class="card-tools">
                                    <button onclick="export_excel('#tabla_modulo_pago_obrero', 'Pago Obrero');" class="btn btn-tool btn-sm"> <i class="fas fa-download"></i> </button>
                                    <a href="#" class="btn btn-tool btn-sm"> <i class="fas fa-bars"></i> </a>
                                  </div> 
                                </div>
                                <div class="card-body table-responsive p-0">
                                  <table class="table table-striped table-valign-middle" id="tabla_modulo_pago_obrero">
                                    <thead>
                                      <tr>
                                        <th class="text-center">#</th>                                        
                                        <th class="text-center">Gasto</th>
                                        <th class="text-center" >Acumulado</th>
                                        <th class="text-center">Utilidad</th>
                                        <th class="text-center">Acumulado</th>
                                        <th class="text-center">Mas</th>
                                      </tr>
                                    </thead>
                                    <tbody id="body_modulo_pago_obrero"> <!-- aqui van los detalles --> </tbody>
                                    <tfoot>
                                      <tr>
                                        <th class="text-center"></th>                                        
                                        <th class="text-center pr-2"><div class="formato-numero-conta"><span>S/</span><span class="foot_total_gasto_pago_obrero"><i class="fas fa-spinner fa-pulse fa-1x"> </i></span></div></th>
                                        <th class="text-center" ></th>
                                        <th class="text-center pr-2"><div class="formato-numero-conta"><span>S/</span><span class="foot_total_utilidad_pago_obrero"><i class="fas fa-spinner fa-pulse fa-1x"> </i></span></div></th>
                                        <th class="text-center" ></th>
                                        <th class="text-center"></th>
                                      </tr>
                                    </tfoot>
                                  </table>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="row">
                                  <div class="col-md-12">
                                    <!-- <div class="d-flex">
                                      <p class="d-flex flex-column">
                                        <span class="text-bold text-lg">$18,230.00</span><span>Sales Over Time</span>
                                      </p>
                                      <p class="ml-auto d-flex flex-column text-right">
                                        <span class="text-success"><i class="fas fa-arrow-up"></i> 33.1% </span>
                                        <span class="text-muted">Since last month</span>
                                      </p>
                                    </div> -->
                                    <!-- /.d-flex -->

                                    <div class="position-relative mb-4">
                                      <canvas id="chart-line-pago-obrero" height="350"></canvas>
                                    </div>

                                    <div class="d-flex flex-row justify-content-end">
                                      <span class="mr-2"><i class="fas fa-square leyenda_utilidad_pago_obrero"></i> Utilidad</span>
                                      <span class="mr-2"><i class="fas fa-square" style="color: #008080;"></i> Pago Obrero</span>
                                    </div>
                                  </div>
                                  <!-- linea divisoria -->
                                  <div class="col-lg-12 borde-arriba-naranja mt-3 mb-3"> </div>
                                  <div class="col-md-6">
                                    <p class="text-center"> <strong>Utilidad Total</strong> </p>
                                    <div class="progress-group text-center mb-4">
                                      <h2 class="progress_total_utilidad_pago_obrero" ><i class="fas fa-spinner fa-pulse fa-lg"></i></h2>
                                    </div>
                                    <!-- /.progress-group -->                                    
                                  </div>
                                  <div class="col-md-6">
                                    <p class="text-center"> <strong>Pago Obrero</strong> </p>
                                    <div class="progress-group text-center">
                                      <h2 class="progress_total_pago_obrero" ><i class="fas fa-spinner fa-pulse fa-lg"></i></h2>
                                    </div>
                                    <!-- /.progress-group -->
                                  </div>
                                </div>
                                <!-- /.row -->
                              </div>
                              <!-- /.col -->                              
                            </div>
                            <!-- /.row -->
                          </div>
                        </div>
                        <!-- /.card -->
                      </div>

                      <!-- TABLA -  ══════════════════════════════════════════ -->                       
                      <div class="col-lg-12">
                        <div class="card">
                          <div class="card-header border-0">
                            <div class="d-flex justify-content-center">
                              <h3 class="card-title font-weight-bold">Resumen de gastos por módulos</h3>
                              <!-- <a href="javascript:void(0);">View Report</a> -->
                            </div>
                          </div>
                          <div class="card-body">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="card-header border-0">
                                <h3 class="card-title text-center">Tabla Resumen</h3>
                                  <div class="card-tools">
                                    <button onclick="export_excel('#tabla_resumen_modulos', 'Resumen gastos', 'Resumen');" class="btn btn-tool btn-sm"> <i class="fas fa-download"></i> </button>
                                    <a href="#" class="btn btn-tool btn-sm"> <i class="fas fa-bars"></i> </a>
                                  </div> 
                                </div>
                                <div class="card-body table-responsive p-0">
                                  <table class="table table-striped table-valign-middle" id="tabla_resumen_modulos">
                                    <thead>
                                      <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center" >Módulo</th>
                                        <th class="text-center">Gasto</th>
                                        <th class="text-center">Utilidad</th>
                                        <th class="text-center">Mas</th>
                                      </tr>
                                    </thead>
                                    <tbody id="body_resumen_modulos"> <!-- aqui van los productos --> </tbody>
                                    <tfoot>
                                      <tr>
                                        <th class="text-center"></th>
                                        <th class="text-center" ></th>
                                        <th class="text-center pr-2"><div class="formato-numero-conta"><span>S/</span><span class="foot_total_gasto_resumen_modulos"><i class="fas fa-spinner fa-pulse fa-1x"> </i></span></div></th>
                                        <th class="text-center pr-2"><div class="formato-numero-conta"><span>S/</span><span class="foot_total_utilidad_resumen_modulos"><i class="fas fa-spinner fa-pulse fa-1x"> </i></span></div></th>
                                        <th class="text-center"></th>
                                      </tr>
                                    </tfoot>
                                  </table>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="row">
                                  <div class="col-md-12">
                                    <!-- <div class="d-flex">
                                      <p class="d-flex flex-column">
                                        <span class="text-bold text-lg">$18,230.00</span><span>Sales Over Time</span>
                                      </p>
                                      <p class="ml-auto d-flex flex-column text-right">
                                        <span class="text-success"><i class="fas fa-arrow-up"></i> 33.1% </span>
                                        <span class="text-muted">Since last month</span>
                                      </p>
                                    </div> -->
                                    <!-- /.d-flex -->

                                    <div class="position-relative mb-4">
                                      <canvas id="chart-barra-resumen-modulos" height="350"></canvas>
                                    </div>

                                    <div class="d-flex flex-row justify-content-end">
                                      <span class="mr-2"><i class="fas fa-square leyenda_utilidad_resumen_modulos"></i> Utilidad</span>
                                      <span class="mr-2"><i class="fas fa-square" style="color: #008080;"></i>Módulos</span>
                                    </div>
                                  </div>
                                  <!-- linea divisoria -->
                                  <div class="col-lg-12 borde-arriba-naranja mt-3 mb-3"> </div>
                                  <div class="col-md-6">
                                    <p class="text-center"> <strong>Utilidad Total</strong> </p>
                                    <div class="progress-group text-center mb-4">
                                      <h2 class="progress_total_utilidad_resumen_modulos" ><i class="fas fa-spinner fa-pulse fa-lg"></i></h2>
                                    </div>
                                    <!-- /.progress-group -->                                    
                                  </div>
                                  <div class="col-md-6">
                                    <p class="text-center"> <strong>Resumen Módulos</strong> </p>
                                    <div class="progress-group text-center">
                                      <h2 class="progress_total_resumen_modulos" ><i class="fas fa-spinner fa-pulse fa-lg"></i></h2>
                                    </div>
                                    <!-- /.progress-group -->
                                  </div>
                                </div>
                                <!-- /.row -->
                              </div>
                              <!-- /.col -->                              
                            </div>
                            <!-- /.row -->
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
                            <li class="m-b-04rem"><i class="fas fa-briefcase nav-icon"></i> PAGO ADMINSTRADOR</li>
                            <li class="m-b-04rem"><i class="fas fa-users nav-icon"></i> PAGO OBRERO</li>
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
        <!-- table export EXCEL -->
        <script src="../plugins/export-xlsx/xlsx.full.min.js"></script>
        <script src="../plugins/export-xlsx/FileSaver.min.js"></script>
        <script src="../plugins/export-xlsx/tableexport.min.js"></script> 
        
        <script type="text/javascript" src="scripts/chart_valorizacion.js"></script>         

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>
        
      </body>
    </html>
    <?php    
  }

  ob_end_flush();
?>
