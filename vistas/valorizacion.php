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
        <title>Valorización | Admin Sevens</title>

        <?php $title = "Valorización"; require 'head.php'; ?>

        <style>
          .nav-link.active { border-color: #1e5b99 transparent #145aa1 #1b5691 !important; }
          .nav-tabs.flex-column { border-bottom: 0; border-right: 1px solid #32679d; }
        </style>

      </head>
      <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed">
        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper">
          <?php
          require 'nav.php';
          require 'aside.php';
          if ($_SESSION['valorizacion']==1){
            //require 'enmantenimiento.php';
            ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper" >
              <!-- Content Header (Page header) -->
              <section class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1 >
                        <span class="h1-titulo">Valorización</span> 
                        <button type="button" class="btn btn-success" onclick="export_excel_valorizacion()" ><i class="far fa-file-excel"></i> Export Excel</button>
                      </h1> 
                      
                    </div>
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="valorizacion.php">Home</a></li>
                        <li class="breadcrumb-item active">Valorización</li>
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
                        <div class="card-header" > 
                          <!-- Editar -->
                          <h3 class="card-title mr-3" id="btn-editar" style="padding-left: 2px;">
                            <button type="button" class="btn bg-gradient-orange btn-sm h-50px" onclick="show_hide_span_input(2);"><i class="fas fa-pencil-alt"></i> <span class="d-none d-sm-inline-block">Editar</span> </button>
                          </h3>
                          <!-- Guardar -->
                          <h3 class="card-title mr-3" id="btn-guardar" style="display: none; padding-left: 2px;">
                            <button type="button" class="btn bg-gradient-success btn-guardar-asistencia btn-sm h-50px" onclick="guardar_y_editar_resumen_q_s_valorizacion();" style="margin-right: 10px;"><i class="far fa-save"></i> <span class="d-none d-sm-inline-block"> Guardar </span> </button>
                          </h3>   
                          <!-- regresar -->
                          <h3 class="card-title mr-3" id="card-regresar" style="display: none; padding-left: 2px;">
                            <button type="button" class="btn bg-gradient-warning btn-sm h-50px" onclick="mostrar_form_table(1);despintar_btn_select();" ><i class="fas fa-arrow-left"></i> <span class="d-none d-sm-inline-block">Regresar</span> </button>
                          </h3>
                          <!-- listar quincenas -->
                          <div id="lista_quincenas" class="row-horizon disenio-scroll" >
                            <i class="fas fa-spinner fa-pulse fa-2x"></i>
                          </div>  
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">                          
                          <div class="row">
                            <!-- tab seleciones -->
                            <div class="col-lg-3 col-xl-4" id="tab-seleccione" style="display: none;">
                              <div class="nav flex-column nav-tabs h-100" id="tabs-tab" role="tablist" aria-orientation="vertical">
                                <a class="nav-link" id="tabs-1-tab" data-toggle="pill" href="#tabs-1" role="tab" aria-controls="tabs-1" aria-selected="false" onclick="add_data_form('1','Copia del contrato');">1. Copia del contrato</a>
                                <a class="nav-link" id="tabs-2-tab" data-toggle="pill" href="#tabs-2" role="tab" aria-controls="tabs-2" aria-selected="false" onclick="add_data_form('2','Informe técnico');">2. Informe técnico</a>
                                <span class="ml-3 nav-titulo" id="tabs-3-tab" data-toggle="pill" role="tab" aria-controls="tabs-3" aria-selected="false">3. Ejecución de obra</span>
                                <a class="nav-link ml-4" id="tabs-3-1-tab" data-toggle="pill" href="#tabs-3-1" role="tab" aria-controls="tabs-3-1" aria-selected="false" onclick="add_data_form('3.1', 'Planilla de metrados');">
                                  3.1 Planilla de metrados
                                </a>
                                <a class="nav-link ml-4" id="tabs-3-2-tab" data-toggle="pill" href="#tabs-3-2" role="tab" aria-controls="tabs-3-2" aria-selected="false" onclick="add_data_form('3.2', 'Valorización');">3.2 Valorización</a>
                                <a class="nav-link ml-4" id="tabs-3-3-tab" data-toggle="pill" href="#tabs-3-3" role="tab" aria-controls="tabs-3-3" aria-selected="false" onclick="add_data_form('3.3', 'Resumen de Valorización');">
                                  3.3 Resumen de Valorización
                                </a>
                                <a class="nav-link ml-4" id="tabs-3-4-tab" data-toggle="pill" href="#tabs-3-4" role="tab" aria-controls="tabs-3-4" aria-selected="false" onclick="add_data_form('3.4', 'Curva S');">3.4 Curva "S"</a>
                                <a class="nav-link" id="tabs-4-tab" data-toggle="pill" href="#tabs-4" role="tab" aria-controls="tabs-4" aria-selected="false" onclick="add_data_form('4', 'Cronograma de obra valorizado');">
                                  4. Cronograma de obra valorizado
                                </a>
                                <a class="ml-3 nav-titulo" id="tabs-5-tab" data-toggle="pill" role="tab" aria-controls="tabs-1" aria-selected="false">5. Protocolo de calidad</a>
                                <a class="nav-link ml-4" id="tabs-5-1-tab" data-toggle="pill" href="#tabs-5-1" role="tab" aria-controls="tabs-2" aria-selected="false" onclick="add_data_form('5.1', 'Ensayo de consistencia del concreto');">
                                  5.1 Ensayo de consistencia del concreto
                                </a>
                                <a class="nav-link ml-4" id="tabs-5-2-tab" data-toggle="pill" href="#tabs-5-2" role="tab" aria-controls="tabs-3" aria-selected="false" onclick="add_data_form('5.2', 'Ensayo de compresión');">
                                  5.2 Ensayo de compresión
                                </a>
                                <a class="nav-link" id="tabs-6-tab" data-toggle="pill" href="#tabs-6" role="tab" aria-controls="tabs-2" aria-selected="false" onclick="add_data_form('6', 'Plan de seguridad y salud en el trabajo');">
                                  6. Plan de seguridad y salud en el trabajo
                                </a>
                                <a class="nav-link" id="tabs-7-tab" data-toggle="pill" href="#tabs-7" role="tab" aria-controls="tabs-2" aria-selected="false" onclick="add_data_form('7', 'Plan de bioseguridad COVID19');">
                                  7. Plan de bioseguridad COVID19
                                </a>
                                <a class="ml-3 nav-titulo" id="tabs-8-tab" data-toggle="pill" role="tab" aria-controls="tabs-2" aria-selected="false">8. Anexos</a>
                                <a class="nav-link ml-4" id="tabs-8-1-tab" data-toggle="pill" href="#tabs-8-1" role="tab" aria-controls="tabs-8-1" aria-selected="false" onclick="add_data_form('8.1', 'Acta de entrega de terreno');">
                                  8.1 Acta de entrega de terreno
                                </a>
                                <a class="nav-link ml-4" id="tabs-8-2-tab" data-toggle="pill" href="#tabs-8-2" role="tab" aria-controls="tabs-8-2" aria-selected="false" onclick="add_data_form('8.2', 'Acta de inicio de obra');">
                                  8.2 Acta de inicio de obra
                                </a>
                                <a class="nav-link ml-4" id="tabs-8-3-tab" data-toggle="pill" href="#tabs-8-3" role="tab" aria-controls="tabs-8-3" aria-selected="false" onclick="add_data_form('8.3', 'Certificado de habilidad del ingeniero residente');" >
                                  8.3 Certificado de habilidad del ingeniero residente
                                </a>
                                <a class="nav-link ml-4" id="tabs-8-4-tab" data-toggle="pill" href="#tabs-8-4" role="tab" aria-controls="tabs-8-4" aria-selected="false" onclick="add_data_form('8.4', 'Planilla del personal obrero');">
                                  8.4 Planilla del personal obrero
                                </a>
                                <a class="nav-link ml-4" id="tabs-8-5-tab" data-toggle="pill" href="#tabs-8-5" role="tab" aria-controls="tabs-8-5" aria-selected="false" onclick="add_data_form('8.5', 'Copia del seguro complementario contra todo riesgo');" >
                                  8.5 Copia del seguro complementario contra todo riesgo
                                </a>
                                <a class="nav-link ml-4" id="tabs-8-6-tab" data-toggle="pill" href="#tabs-8-6" role="tab" aria-controls="tabs-8-6" aria-selected="false" onclick="add_data_form('8.6', 'Panel fotográfico');">
                                  8.6 Panel fotográfico
                                </a>
                                <a class="nav-link ml-4" id="tabs-8-7-tab" data-toggle="pill" href="#tabs-8-7" role="tab" aria-controls="tabs-8-7" aria-selected="false" onclick="add_data_form('8.7', 'Copia del cuaderno de obra');">
                                  8.7 Copia del cuaderno de obra
                                </a>
                                <a class="nav-link" id="tabs-resumen-tab" data-toggle="pill" href="#tabs-resumen" role="tab" aria-controls="tabs-resumen" aria-selected="false">RESUMEN</a>
                              </div>
                            </div>
                            <!-- Tab contenido -->
                            <div class="col-lg-9 col-xl-8" id="tab-contenido" style="display: none;">
                              <div class="tab-content" id="tabs-tabContent">
                                <!-- Resumen de documentos subidos -->
                                <div class="tab-pane fade show active" id="tabs-resumen" role="tabpanel" aria-labelledby="tabs-resumen-tab">
                                  <div class="info-box bg-warning">
                                    <span class="info-box-icon icon-resumen-cargando"><i class="fas fa-spinner fa-pulse fa-md"></i></span>
                                    <div class="info-box-content">
                                      <span class="info-box-text">Documentos Subidos</span>
                                      <span class="info-box-number total_docs_subidos">Total 0/18</span>
                                      <div class="progress" style="height: 10px !important;"> 
                                        <div class="progress-bar porcentaje_progress" ></div>
                                      </div>
                                      <span class="progress-description">
                                        Tienes un <b class="porcentaje_numero"> 0%</b> de documentos subidos!!!
                                      </span>
                                    </div>
                                  </div>
                                </div>

                                <!-- 1. Copia del contrato -->
                                <div class="tab-pane text-left fade" id="tabs-1" role="tabpanel" aria-labelledby="tabs-1-tab">
                                  <div class="row">
                                    <div class="col-lg-12"><h3>Copia del contrato</h3></div>
                                  </div>
                                  <div class="row" id="documento1">
                                    <!-- Aqui va - Copia del contrato -->
                                  </div>
                                </div>

                                <!-- 2. Informe tecnico -->
                                <div class="tab-pane fade" id="tabs-2" role="tabpanel" aria-labelledby="tabs-2-tab">
                                  <div class="row">
                                    <div class="col-lg-12"><h3>Informe técnico</h3></div>
                                  </div>
                                  <div class="row" id="documento2">
                                    <!-- Aqui va - Copia del contrato -->
                                  </div>
                                </div>

                                <!-- 3. Ejecución de obra -->
                                <div class="tab-pane fade" id="tabs-3" role="tabpanel" aria-labelledby="tabs-3-tab"></div>

                                <!-- 3.1 Planilla de metrados -->
                                <div class="tab-pane fade" id="tabs-3-1" role="tabpanel" aria-labelledby="tabs-3-1-tab">
                                  <div class="row">
                                    <div class="col-lg-12"><h3>Planilla de metrados</h3></div>
                                  </div>
                                  <div class="row" id="documento3-1">
                                    <!-- Aqui va - Planilla de metrados -->
                                  </div>
                                </div>

                                <!-- 3.2 Valorizaciones  -->
                                <div class="tab-pane fade" id="tabs-3-2" role="tabpanel" aria-labelledby="tabs-3-2-tab">
                                  <div class="row">
                                    <div class="col-lg-12"><h3>Valorizaciones</h3></div>
                                  </div>
                                  <div class="row" id="documento3-2">
                                    <!-- Aqui va - Valorizaciones -->
                                  </div>
                                </div>

                                <!-- 3.3 Resumen de valorizacion -->
                                <div class="tab-pane fade" id="tabs-3-3" role="tabpanel" aria-labelledby="tabs-3-3-tab">
                                  <div class="row">
                                    <div class="col-lg-12"><h3>Resumen de valorizacion</h3></div>
                                  </div>
                                  <div class="row" id="documento3-3">
                                    <!-- Aqui va - Resumen de valorizacion -->
                                  </div>
                                </div>

                                <!-- 3.4 Curva S -->
                                <div class="tab-pane fade" id="tabs-3-4" role="tabpanel" aria-labelledby="tabs-3-4-tab">
                                  <div class="row">
                                    <div class="col-lg-12"><h3>Curva "S"</h3></div>
                                  </div>
                                  <div class="row" id="documento3-4">
                                    <!-- Aqui va - Curva S -->
                                  </div>
                                </div>

                                <!-- 4 Cronograma de obra valorizado -->
                                <div class="tab-pane fade" id="tabs-4" role="tabpanel" aria-labelledby="tabs-4-tab">
                                  <div class="row">
                                    <div class="col-lg-12"><h3>Cronograma de obra valorizado</h3></div>
                                  </div>
                                  <div class="row" id="documento4">
                                    <!-- Aqui va - Cronograma de obra valorizado -->
                                  </div>
                                </div>

                                <!-- 5. Protocolo de calidad -->
                                <div class="tab-pane fade" id="tabs-5" role="tabpanel" aria-labelledby="tabs-5-tab">
                                  Doc 5
                                </div>

                                <!-- 5.1 Ensayo de consistencia del concreto -->
                                <div class="tab-pane fade" id="tabs-5-1" role="tabpanel" aria-labelledby="tabs-5-2-tab">
                                  <div class="row">
                                    <div class="col-lg-12"><h3>Ensayo de consistencia del concreto</h3></div>
                                  </div>
                                  <div class="row" id="documento5-1">
                                    <!-- Aqui va - Ensayo de consistencia del concreto -->
                                  </div>
                                </div>

                                <!-- 5.2 Ensayo de compresión -->
                                <div class="tab-pane fade" id="tabs-5-2" role="tabpanel" aria-labelledby="tabs-5-2-tab">
                                  <div class="row">
                                    <div class="col-lg-12"><h3>Ensayo de Compresión</h3></div>
                                  </div>
                                  <div class="row" id="documento5-2">
                                    <!-- Aqui va - Ensayo de compresión -->
                                  </div>
                                  <div class="row mt-4">
                                    <div class="col-lg-12"><h3>Respuesta de Ensayo de Compresión</h3></div>
                                  </div>
                                  <div class="row mt-3" id="documento5-2-1">
                                    <!-- Aqui va - Ensayo de compresión -->
                                  </div>
                                </div>

                                <!-- 6. Plan de seguridad y salud en el trabajo -->
                                <div class="tab-pane fade" id="tabs-6" role="tabpanel" aria-labelledby="tabs-6-tab">
                                  <div class="row">
                                    <div class="col-lg-12"><h3>Plan de seguridad y salud en el trabajo</h3></div>
                                  </div>
                                  <div class="row" id="documento6">
                                    <!-- Aqui va - Plan de seguridad y salud en el trabajo -->
                                  </div>
                                </div>

                                <!-- 7. Plan de bioseguridad COVID19 -->
                                <div class="tab-pane fade" id="tabs-7" role="tabpanel" aria-labelledby="tabs-7-tab">
                                  <div class="row">
                                    <div class="col-lg-12"><h3>Plan de bioseguridad COVID19</h3></div>
                                  </div>
                                  <div class="row" id="documento7">
                                    <!-- Aqui va - Plan de bioseguridad COVID19 -->
                                  </div>
                                </div>

                                <!-- 8. Anexos -->
                                <div class="tab-pane fade" id="tabs-8" role="tabpanel" aria-labelledby="tabs-8-tab">
                                  Doc 8
                                </div>

                                <!-- 8.1 Acta de entrega de terreno -->
                                <div class="tab-pane fade" id="tabs-8-1" role="tabpanel" aria-labelledby="tabs-8-1-tab">
                                  <div class="row">
                                    <div class="col-lg-12"><h3>Acta de entrega de terreno</h3></div>
                                  </div>
                                  <div class="row" id="documento8-1">
                                    <!-- Aqui va - Acta de entrega de terreno -->
                                  </div>
                                </div>

                                <!-- 8.2 Acta de inicio de obra -->
                                <div class="tab-pane fade" id="tabs-8-2" role="tabpanel" aria-labelledby="tabs-8-2-tab">
                                  <div class="row">
                                    <div class="col-lg-12"><h3>Acta de inicio de obra</h3></div>
                                  </div>
                                  <div class="row" id="documento8-2">
                                    <!-- Aqui va - Acta de inicio de obra -->
                                  </div>
                                </div>

                                <!-- 8.3 Certificado de habilidad del ingeniero residente -->
                                <div class="tab-pane fade" id="tabs-8-3" role="tabpanel" aria-labelledby="tabs-8-3-tab">
                                  <div class="row">
                                    <div class="col-lg-12"><h3>Certificado de habilidad del ingeniero residente</h3></div>
                                  </div>
                                  <div class="row" id="documento8-3">
                                    <!-- Aqui va - Certificado de habilidad del ingeniero residente  -->
                                  </div>
                                </div>

                                <!-- 8.4 Planilla del personal obrero -->
                                <div class="tab-pane fade" id="tabs-8-4" role="tabpanel" aria-labelledby="tabs-8-4-tab">
                                  <div class="row">
                                    <div class="col-lg-12"><h3>Planilla del personal obrero</h3></div>
                                  </div>
                                  <div class="row" id="documento8-4">
                                    <!-- Aqui va - Planilla del personal obrero -->
                                  </div>
                                </div>

                                <!-- 8.5 Copia del seguro complementario contra todo riesgo -->
                                <div class="tab-pane fade" id="tabs-8-5" role="tabpanel" aria-labelledby="tabs-8-5-tab">
                                  <div class="row">
                                    <div class="col-lg-12"><h3>Copia del seguro complementario contra todo riesgo</h3></div>
                                  </div>
                                  <div class="row" id="documento8-5">
                                    <!-- Aqui va - Copia del seguro complementario contra todo riesgo -->
                                  </div>
                                </div>

                                <!-- 8.6 Panel fotográfico -->
                                <div class="tab-pane fade" id="tabs-8-6" role="tabpanel" aria-labelledby="tabs-8-6-tab">
                                  <div class="row">
                                    <div class="col-lg-12"><h3>Panel fotográfico</h3></div>
                                  </div>
                                  <div class="row" id="documento8-6">
                                    <!-- Aqui va - Panel fotográfico -->
                                  </div>
                                </div>

                                <!-- 8.7 Copia del cuaderno de obra -->
                                <div class="tab-pane fade" id="tabs-8-7" role="tabpanel" aria-labelledby="tabs-8-7-tab">
                                  <div class="row">
                                    <div class="col-lg-12"><h3>Copia del cuaderno de obra</h3></div>
                                  </div>
                                  <div class="row" id="documento8-7">
                                    <!-- Aqui va - Copia del cuaderno de obra -->
                                  </div>
                                </div>
                              </div>
                            </div>
                            <!-- tab info resumen valorizaciones -->
                            <div class="col-lg-12 col-xl-12" id="tab-info">
                              <!-- tabla principal -->
                              <div class="table-responsive pb-3">
                                <table class="table table-bordered /*table-striped*/ table-hover text-nowrap" id="tbla_export_excel_valorizacion" >
                                  <thead>
                                    <tr class="text-center bg-color-48acc6">
                                      <th class="pt-1 pb-1 celda-b-r-2px" colspan="2"></th>
                                      <th class="pt-1 pb-1 celda-b-r-2px" colspan="3">Programado</th>
                                      <th class="pt-1 pb-1 celda-b-r-2px" colspan="3">Valorizado</th>
                                      <th class="pt-1 pb-1" colspan="3">Gastado</th>
                                    </tr>
                                    <tr class="text-center bg-color-48acc6">
                                      <th class="pt-1 pb-1">N° de</th>
                                      <th class="pt-1 pb-1 celda-b-r-2px">Periodo del</th>
                                      <th class="pt-1 pb-1">Monto</th>
                                      <th class="pt-1 pb-1 celda-b-r-2px" colspan="2">porcentaje %</th>
                                      <th class="pt-1 pb-1">Monto</th>
                                      <th class="pt-1 pb-1 celda-b-r-2px" colspan="2">porcentaje %</th>
                                      <th class="pt-1 pb-1">Monto</th>
                                      <th class="pt-1 pb-1" colspan="2">porcentaje %</th>
                                    </tr>
                                    <tr class="text-center bg-color-48acc6"> 
                                      <th class="pt-1 pb-1 celda-b-b-2px">Inf.</th> 
                                      <th class="pt-1 pb-1 celda-b-r-2px celda-b-b-2px">Informe</th>
                                      <th class="pt-1 pb-1 celda-b-b-2px">S/.</th>
                                      <th class="pt-1 pb-1 celda-b-b-2px">Programado</th>
                                      <th class="pt-1 pb-1 celda-b-r-2px celda-b-b-2px">Acumulado</th>         
                                      <th class="pt-1 pb-1 celda-b-b-2px">S/.</th>  
                                      <th class="pt-1 pb-1 celda-b-b-2px">Valorizado</th>  
                                      <th class="pt-1 pb-1 celda-b-r-2px celda-b-b-2px">Acumulado</th>
                                      <th class="pt-1 pb-1 celda-b-b-2px">S/.</th>  
                                      <th class="pt-1 pb-1 celda-b-b-2px">Gastado</th>  
                                      <th class="pt-1 pb-1 celda-b-b-2px">Acumulado</th>     
                                    </tr>
                                  </thead>
                                  <tbody id="tabla-principal">                         
                                    <tr><td colspan="11"><div class="row" ><div class="col-lg-12 text-center"><i class="fas fa-spinner fa-pulse fa-4x"></i><br/><br/><h4>Cargando...</h4></div></div></td></tr>
                                  </tbody>
                                  <tfoot>
                                    <tr> 
                                      <th class="pt-1 pb-1 celda-b-t-2px font-weight-normal" ></th> 
                                      <th class="pt-1 pb-1 celda-b-t-2px font-weight-normal text-center celda-b-r-2px"></th> 
                                      <th class="pt-1 pb-1 celda-b-t-2px font-weight-normal "><div class="formato-numero-conta"><span class="">S/&nbsp;</span><span class="suma_total_monto_programado_95"><i class="fas fa-spinner fa-pulse fa-sm"></i></span></div> </th>                                        
                                      <th class="pt-1 pb-1 celda-b-t-2px font-weight-normal text-center total_porcent_programado_95"><i class="fas fa-spinner fa-pulse fa-sm"></i></th>
                                      <th class="pt-1 pb-1 celda-b-t-2px font-weight-normal celda-b-r-2px"></th>
                                      <th class="pt-1 pb-1 celda-b-t-2px font-weight-normal"><div class="formato-numero-conta"><span class="">S/&nbsp;</span><span class="suma_total_monto_valorizado_95"><i class="fas fa-spinner fa-pulse fa-sm"></i></span></div> </th>
                                      <th class="pt-1 pb-1 celda-b-t-2px font-weight-normal text-center total_porcent_valorizado_95"><i class="fas fa-spinner fa-pulse fa-sm"></i></th>
                                      <th class="pt-1 pb-1 celda-b-t-2px font-weight-normal celda-b-r-2px"></th> 
                                      <th class="pt-1 pb-1 celda-b-t-2px font-weight-normal "><div class="formato-numero-conta"><span class="">S/&nbsp;</span><span class="suma_total_monto_gastado_95"><i class="fas fa-spinner fa-pulse fa-sm"></i></span></div> </th>
                                      <th class="pt-1 pb-1 celda-b-t-2px font-weight-normal text-center total_porcent_gastado_95"><i class="fas fa-spinner fa-pulse fa-sm"></i></th>
                                      <th class="pt-1 pb-1 celda-b-t-2px font-weight-normal"></th>                               
                                    </tr>
                                    <tr> 
                                      <th class="pt-1 pb-1 font-weight-normal" ></th> 
                                      <th class="pt-1 pb-1 font-weight-normal text-center celda-b-r-2px">(garantía)</th> 
                                      <th class="pt-1 pb-1 font-weight-normal"><div class="formato-numero-conta"><span class="">S/&nbsp;</span><span class="suma_total_monto_programado_5"><i class="fas fa-spinner fa-pulse fa-sm"></i></span></div> </th>                                        
                                      <th class="pt-1 pb-1 font-weight-normal text-center total_porcent_programado_5"><i class="fas fa-spinner fa-pulse fa-sm"></i></th>
                                      <th class="pt-1 pb-1 font-weight-normal celda-b-r-2px"></th>
                                      <th class="pt-1 pb-1 font-weight-normal"><div class="formato-numero-conta"><span class="">S/&nbsp;</span><span class="suma_total_monto_valorizado_5"><i class="fas fa-spinner fa-pulse fa-sm"></i></span></div> </th>
                                      <th class="pt-1 pb-1 font-weight-normal text-center total_porcent_valorizado_5"><i class="fas fa-spinner fa-pulse fa-sm"></i></th>
                                      <th class="pt-1 pb-1 font-weight-normal celda-b-r-2px"></th> 
                                      <th class="pt-1 pb-1 font-weight-normal"><div class="formato-numero-conta"><span class="">S/&nbsp;</span><span class="suma_total_monto_gastado_5"><i class="fas fa-spinner fa-pulse fa-sm"></i></span></div> </th>
                                      <th class="pt-1 pb-1 font-weight-normal text-center total_porcent_gastado_5"><i class="fas fa-spinner fa-pulse fa-sm"></i></th>
                                      <th class="pt-1 pb-1 font-weight-normal"></th>                               
                                    </tr> 
                                    <tr> 
                                      <th class="pt-1 pb-1 celda-b-t-2px" ></th> 
                                      <th class="pt-1 pb-1 celda-b-t-2px text-center celda-b-r-2px">Total</th> 
                                      <th class="pt-1 pb-1 celda-b-t-2px "><div class="formato-numero-conta"><span class="">S/&nbsp;</span><span class="suma_total_monto_programado"><i class="fas fa-spinner fa-pulse fa-sm"></i></span></div> </th>                                        
                                      <th class="pt-1 pb-1 celda-b-t-2px text-center total_porcent_programado"><i class="fas fa-spinner fa-pulse fa-sm"></i></th>
                                      <th class="pt-1 pb-1 celda-b-t-2px celda-b-r-2px"></th>
                                      <th class="pt-1 pb-1 celda-b-t-2px "><div class="formato-numero-conta"><span class="">S/&nbsp;</span><span class="suma_total_monto_valorizado"><i class="fas fa-spinner fa-pulse fa-sm"></i></span></div> </th>
                                      <th class="pt-1 pb-1 celda-b-t-2px text-center total_porcent_valorizado"><i class="fas fa-spinner fa-pulse fa-sm"></i></th>
                                      <th class="pt-1 pb-1 celda-b-t-2px celda-b-r-2px"></th> 
                                      <th class="pt-1 pb-1 celda-b-t-2px "><div class="formato-numero-conta"><span class="">S/&nbsp;</span><span class="suma_total_monto_gastado"><i class="fas fa-spinner fa-pulse fa-sm"></i></span></div> </th>
                                      <th class="pt-1 pb-1 celda-b-t-2px text-center total_porcent_gastado"><i class="fas fa-spinner fa-pulse fa-sm"></i></th>
                                      <th class="pt-1 pb-1 celda-b-t-2px"></th>                               
                                    </tr> 
                                  </tfoot>
                                </table>
                              </div>  
                            </div>
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

                <!-- Modal agregar valorizacion -->
                <div class="modal fade" id="modal-agregar-valorizacion">
                  <div class="modal-dialog modal-dialog-scrollable modal-md">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title" id="title-modal-1">Agregar Valorizacion</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form id="form-valorizacion" name="form-valorizacion" method="POST">
                          <div class="row" id="cargando-1-fomulario">
                            <!-- id proyecto -->
                            <input type="hidden" name="idproyecto" id="idproyecto" />
                            <!-- id valorizacion -->
                            <input type="hidden" name="idvalorizacion" id="idvalorizacion" />
                            <!-- indice -->
                            <input type="hidden" name="indice" id="indice" />
                            <!-- nombre -->
                            <input type="hidden" name="nombre" id="nombre" />
                            <!-- fecha inicio -->
                            <input type="hidden" name="fecha_inicio" id="fecha_inicio" />
                            <!-- fecha fin -->
                            <input type="hidden" name="fecha_fin" id="fecha_fin" />
                            <!-- fecha numero_q_s -->
                            <input type="hidden" name="numero_q_s" id="numero_q_s" />

                            <!-- Doc Valorizaciones -->
                            <div class="col-md-12 col-lg-12">
                              <div class="row text-center">
                                <div class="col-md-12" style="padding-top: 15px; padding-bottom: 5px;">
                                  <label for="cip" class="control-label">Documento </label>
                                </div>
                                <div class="col-6 col-md-6 text-center">
                                  <button type="button" class="btn btn-success btn-block btn-xs" id="doc7_i"><i class="fas fa-file-upload"></i> Subir.</button>
                                  <input type="hidden" id="doc_old_7" name="doc_old_7" />
                                  <input style="display: none;" id="doc7" type="file" name="doc7" accept=".xlsx, .xlsm, .xls, .csv, .pdf, .doc, .docx" class="docpdf" />
                                </div>
                                <div class="col-6 col-md-6 text-center">
                                  <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(7, 'valorizacion', 'documento');"><i class="fa fa-eye"></i> Doc.</button>
                                </div>
                              </div>
                              <div id="doc7_ver" class="text-center mt-4">
                                <img src="../dist/svg/doc_uploads.svg" alt="" width="50%" />
                              </div>
                              <div class="text-center" id="doc7_nombre"><!-- aqui va el nombre del pdf --></div>
                            </div>

                            <!-- barprogress -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 20px;">
                              <div class="progress" id="div_barra_progress">
                                <div id="barra_progress" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
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
                          <!-- /.card-body -->
                          <button type="submit" style="display: none;" id="submit-form-valorizacion">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro">Guardar Cambios</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!--Modal-ver-comprobante-->
                <div class="modal fade" id="modal-ver-comprobante">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title text-bold nombre_documento">Documentos valorización</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">  

                        <div class="row" id="ver-documento"> </div>            
                          
                      </div>
                    </div>
                  </div>
                </div>

                <!-- MODAL - AGREGAR RESUMEN Q S-->
                <div class="modal fade" id="modal-agregar-resumen_valorizacion">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title text-bold _edith">valorización</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">                          

                        <form id="form-resumen-valorizacion" name="form-resumen-valorizacion" method="POST">
                          <div class="card-body">
                            <div class="row" id="cargando-3-fomulario">
                              <!-- id proyecto --> 
                              <input type="hidden" name="idresumen_q_s_valorizacion" id="idresumen_q_s_valorizacion" />
                              <input type="hidden" name="idproyecto_q_s" id="idproyecto_q_s" />
                              <!-- id proveedores -->
                              <input type="hidden" name="numero_q_s_resumen_oculto" id="numero_q_s_resumen_oculto" />
                              
                              <!-- Tipo de documento -->
                              <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                  <label for="numero_q_s_resumen">Valorización</label>
                                  <select name="numero_q_s_resumen" id="numero_q_s_resumen" onchange="recoger_fecha_q_s();" class="form-control select2" style="width: 100%;" > </select>
                                  
                                </div>
                              </div>

                              <!-- fecha_inicial -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                  <label for="fecha_inicial">Fecha inicial <sup class="text-danger">(unico*)</sup></label>
                                  <input type="text" name="fecha_inicial" class="form-control" id="fecha_inicial" placeholder="Fecha inicial" readonly />
                                </div>
                              </div>

                              <!-- fecha_final -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                  <label for="fecha_final">Fecha final <sup class="text-danger">(unico*)</sup></label>
                                  <input type="text" name="fecha_final" class="form-control" id="fecha_final" placeholder="Fecha final" readonly />
                                </div>
                              </div>

                              <!-- monto_programado -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="monto_programado">Monto programado <sup class="text-danger">(*)</sup></label>
                                  <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text">S/. </span>
                                    </div>
                                    <input type="text"  name="monto_programado" id="monto_programado" class="form-control" onkeyup="formato_miles_input('#monto_programado');"  placeholder="Monto programado" >
                                  </div>
                                </div>
                              </div> 

                              <!-- monto_valorizado -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="monto_valorizado">Monto valorizado <sup class="text-danger">(*)</sup></label>
                                  <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text">S/. </span>
                                    </div>
                                    <input type="text"  name="monto_valorizado" id="monto_valorizado" class="form-control"  onkeyup="formato_miles_input('#monto_valorizado');"  placeholder="Monto valorizado" >
                                  </div>
                                </div>
                              </div>

                              <!-- monto_gastado -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="monto_gastado">Monto gastado <sup class="text-danger">(*)</sup></label>
                                  <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text">S/. </span>
                                    </div>
                                    <input type="text"  name="monto_gastado" id="monto_gastado" class="form-control"  placeholder="Monto gastado" readonly >
                                  </div>
                                </div>
                              </div>
                              
                              <!-- barprogress -->
                              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                                <div class="progress" id="div_barra_progress">
                                  <div id="barra_progress" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                    0%
                                  </div>
                                </div>
                              </div>

                            </div>

                            <div class="row" id="cargando-4-fomulario" style="display: none;">
                              <div class="col-lg-12 text-center">
                                <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                                <br />
                                <h4>Cargando...</h4>
                              </div>
                            </div>
                          </div>
                          <!-- /.card-body -->
                          <button type="submit" style="display: none;" id="submit-form-resumen-valorizacion">Submit</button>
                        </form>   
                          
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_resumen_q_s();">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro_resumen_valorizacion">Guardar Cambios</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- MODAL - cargando -->
                <div class="modal fade" id="modal-cargando" data-keyboard="false" data-backdrop="static">
                  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md">
                    <div class="modal-content">
                      <div class="modal-body">
                        
                        <div id="icono-respuesta">
                          <!-- icon ERROR -->
                          <!-- icon success -->
                        </div>
                        
                        <!-- barprogress -->
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                          <div class="progress h-px-30" id="barra_progress_cargando_div">
                            <div id="barra_progress_cargando" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                              0%
                            </div>
                          </div>
                        </div> 
                        
                        <!-- boton -->
                        <div class="swal2-actions" >
                          <div class="swal2-loader"></div>
                          <button type="button" class="swal2-confirm swal2-styled" data-dismiss="modal" aria-label="Close" style="display: inline-block;">OK</button>                         
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

        <!-- table export EXCEL -->
        <script src="../plugins/export-xlsx/xlsx.full.min.js"></script>
        <script src="../plugins/export-xlsx/FileSaver.min.js"></script>
        <script src="../plugins/export-xlsx/tableexport.min.js"></script>

        <script type="text/javascript" src="scripts/valorizacion.js"></script>

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip();  }); </script>

      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
