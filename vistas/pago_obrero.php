<?php
  //Activamos el almacenamiento en el buffer
  ob_start();
  session_start();

  if (!isset($_SESSION["nombre"])){
    header("Location: login.html");
  }else{ ?>
    <!DOCTYPE html>
    <html lang="es">
      <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Admin Sevens | Pagos de Obrero</title>
        <?php
          require 'head.php';
        ?>
        <!-- Theme style -->
        <!-- <link rel="stylesheet" href="../dist/css/adminlte.min.css"> -->
      </head>
      <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed ">
        
        <div class="wrapper">
          <!-- Preloader -->
          <!-- <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="../dist/svg/logo-principal.svg" alt="AdminLTELogo" width="360" />
          </div> -->
        
          <?php
            require 'nav.php';
            require 'aside.php';
            if ($_SESSION['pago_trabajador']==1){
          ?>           
          <!--Contenido-->
          <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
              <div class="container-fluid">
                <div class="row mb-2">
                  <div class="col-sm-6">
                    <h1 class="m-0">Pagos de Obreros</h1>
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="pago_obrero.php">Pagos</a></li>
                      <li class="breadcrumb-item active">Pagos de Obreros</li>
                    </ol>
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
              <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            
            <!-- Main content -->
            <section class="content">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-12">
                    <div class="card card-primary card-outline">
                      <div class="card-header">
                        <!-- regresar -->
                        <h3 class="card-title mr-3 hidden" id="button-regresar" style="padding-left: 2px;">
                          <button type="button" class="btn bg-gradient-warning" onclick="show_hide_tablas(1);" ><i class="fas fa-arrow-left"></i> Regresar</button>
                        </h3>

                        <!-- Agregar pagos -->
                        <h3 class="card-title hidden" id="button-agregar-pago" >
                          <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-proyecto" onclick="limpiar();">
                          <i class="fas fa-plus-circle"></i> Agregar
                          </button>
                          Pagos de Trabajadores                        
                        </h3> 
                                             
                      </div>
                      <!-- /.card-header -->
                      <div class="card-body">
                        <div class="table-responsive disenio-scroll" id="card-tabla-pago">                           
                          <table class="table table-hover text-nowrap styletabla" style="border: black 1px solid;">
                              <thead>                                  
                                <tr>
                                  <th rowspan="2" class="stile-celda">Nombres</th>
                                  <th rowspan="2" class="stile-celda">Tipo/Cargo</th>
                                  <th colspan="5" class="stile-celda">
                                    <button class="btn btn-info btn-block" onclick="ver_detalle_quincena_semana(1);"  style="padding:0px 12px 0px 12px !important;">Quincena 1</button>                                      
                                  </th>
                                  <th colspan="5" class="stile-celda">
                                    <button class="btn btn-info btn-block" onclick="ver_detalle_quincena_semana(1);" style="padding:0px 12px 0px 12px !important;">Quincena 2</button>
                                  </th>
                                  <th rowspan="2" class="stile-celda">Total</th>
                                  <th rowspan="2" class="stile-celda">Pagado</th>
                                  <th rowspan="2" class="stile-celda">Saldo</th>
                                </tr>

                                <tr>                                    
                                  <th class="stile-celda p-x-10px">Hr.</th>
                                  <th class="stile-celda p-x-10px">Día</th> 
                                  <th class="stile-celda p-x-10px">Sa</th>
                                  <th class="stile-celda p-x-10px">Adicional</th> 
                                  <th class="stile-celda p-x-10px">Pago Quincena</th>  
                                  
                                  <th class="stile-celda p-x-10px">Hr.</th>
                                  <th class="stile-celda p-x-10px">Día</th> 
                                  <th class="stile-celda p-x-10px">Sa</th>
                                  <th class="stile-celda p-x-10px">Adicional</th> 
                                  <th class="stile-celda p-x-10px">Pago Quincena</th>                                 
                                </tr>   
                                    
                              </thead>
                              <tbody class="tcuerpo nameappend">
                                <tr>
                                  <td>POOL STIWART BRIONES SÁNCHEZ</td>
                                  <td>Técnico/Ing. residente</td>
                                  <td>44 h</td>
                                  <td>6 D</td>
                                  <td>1 Sa</td>
                                  <td>S/. 40.00</td>
                                  <td> S/. 440.0 <span class="badge badge-info float-right cursor-pointer" data-toggle="tooltip" data-original-title="Asignar pago" onclick="modal_agregar_pago( '', '1', '2021-09-01');"><i class="fas fa-dollar-sign fa-2x"></i></span>
                                  </td>
                                  <td>44 h</td>
                                  <td>6 D</td>
                                  <td>1 Sa</td> 
                                  <td>S/. 40.00</td>                                   
                                  <td>S/. 440.00 <span class="badge badge-info float-right cursor-pointer" data-toggle="tooltip" data-original-title="Asignar pago" onclick="modal_agregar_pago( '', '1', '2021-09-01');"><i class="fas fa-dollar-sign fa-2x"></i></span>
                                  </td>
                                  <td>S/. 880</td>
                                  <td>S/. 400</td>
                                  <td>S/. 480</td>
                                  <td>
                                    <button class="btn btn-info" onclick="ver_detalle_pagos(1);">Detalle</button>
                                  </td>                                   

                                </tr>
                                <tr>
                                  <td>PEDRO JUAN CARRASCO GARCÍA </td>
                                  <td>Técnico/Asistente Admin.</td>
                                  <td>44 h</td>
                                  <td>6 D</td>
                                  <td>1 Sa</td>
                                  <td>S/. 40.00</td>
                                  <td> S/. 440.0 <span class="badge badge-info float-right cursor-pointer" data-toggle="tooltip" data-original-title="Asignar pago" onclick="modal_agregar_pago( '', '1', '2021-09-01');"><i class="fas fa-dollar-sign fa-2x"></i></span>
                                  </td>
                                  <td>44 h</td>
                                  <td>6 D</td>
                                  <td>1 Sa</td>
                                  <td>S/. 40.00</td>
                                  <td>S/. 440.00<span class="badge badge-info float-right cursor-pointer" data-toggle="tooltip" data-original-title="Asignar pago" onclick="modal_agregar_pago( '', '1', '2021-09-01');"><i class="fas fa-dollar-sign fa-2x"></i></span>
                                  </td>
                                  <td>S/. 880</td>
                                  <td>S/. 400</td>
                                  <td>S/. 480</td>
                                  <td>
                                    <button class="btn btn-info" onclick="ver_detalle_pagos(1);">Detalle</button>
                                  </td>                                   

                                </tr>                                 
                              </tbody>
                          </table>                           
                        </div> 
                        
                        <div class="hidden" id="card-tabla-detalle-pago"> 
                          <table id="tabla-detalle-pago" class="table table-bordered table-striped display" style="width: 100% !important;">
                            <thead>
                              <tr>
                                <th class="">Aciones</th>
                                <th>Nombres</th>
                                <th>Cargo</th>
                                <th>Telefono</th>
                                <th>Pago</th>                                
                                <th>Estado</th>
                              </tr>
                            </thead>
                            <tbody>                         
                              
                            </tbody>
                            <tfoot>
                              <tr>
                                <th class="">Aciones</th>
                                <th>Nombres</th>
                                <th>Cargo</th>
                                <th>Telefono</th>
                                <th>Pago</th>                                
                                <th>Estado</th>
                              </tr>
                            </tfoot>
                          </table>
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

              <!-- Modal agregar pago -->
              <div class="modal fade" id="modal-agregar-pago">
                <div class="modal-dialog  modal-dialog-scrollable modal-sm">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Agregar pago</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    
                    <div class="modal-body">
                      <!-- form start -->
                      <form id="form-pago" name="form-pago"  method="POST" >                      
                         
                        <div class="row" id="cargando-1-fomulario">
                          <!-- id proyecto -->
                          <input type="hidden" name="idproyecto" id="idproyecto" />      

                          <!-- Trabajador -->
                          <div class="col-lg-12">
                            <div class="form-group">
                              <span id="trabajador"> POOL STIWART BRIONES SÁNCHEZ </span>
                            </div>                                                        
                          </div>

                          <!-- Pago -->
                          <div class="col-lg-12">
                            <div class="form-group">
                              <label for="pago_q_s">Pago <small>(pago de semana o quincena)</small> </label>                               
                              <input type="number" name="pago_q_s" id="pago_q_s" class="form-control"  placeholder="Pago">  
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

                        <div class="row" id="cargando-2-fomulario" style="display: none;">
                          <div class="col-lg-12 text-center">
                            <i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>
                            <h4>Cargando...</h4>
                          </div>
                        </div>                          
                         
                        <!-- /.card-body -->                      
                        <button type="submit" style="display: none;" id="submit-form-proyecto">Submit</button>                      
                      </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-success" id="guardar_registro">Guardar Cambios</button>
                    </div>                  
                  </div>
                </div>
              </div>

              <!-- Modal detalle: quincena, semana -->
              <div class="modal fade" id="modal-ver-quincena-semana">
                <div class="modal-dialog modal-dialog-scrollable modal-xl">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Documentos subidos</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    
                    <div class="modal-body">
                      <div class="table-responsive disenio-scroll">
                        <table class="table table-hover text-nowrap styletabla" style="border: black 1px solid;">
                          <thead>
                            <tr>
                              <th rowspan="4" class="stile">#</th>
                              <th rowspan="4" class="stile">Nombre del trabajador</th>
                              <th rowspan="4" class="stile">Cargo</th>
                              <th colspan="14" id="dias_asistidos_s_q" style="text-align: center !important; border: black 1px solid; padding: 0.5rem;">Horas de trabajo por día</th>
                              <th rowspan="3" class="stile">
                                Horas<br />
                                normal/extras
                              </th>
                              <th rowspan="3" class="stile">
                                Días<br />
                                asistidos
                              </th>
                              <th rowspan="3" class="stile">Sueldo Mensual</th>
                              <th rowspan="3" class="stile">Jornal</th>
                              <th rowspan="3" class="stile">Sueldo hora</th>
                              <th rowspan="3" class="stile">Sabatical</th>
                              <th rowspan="3" class="stile">
                                Pago <br />
                                parcial
                              </th>
                              <th rowspan="3" class="stile">
                                Adicional <br />
                                descuento
                              </th>
                              <th rowspan="3" class="stile">Pago quincenal</th>
                            </tr>
                            <tr class="table-dias data-dia-semana">
                              <th class="p-x-10px">
                                12 <br />
                                do
                              </th>
                              <th class="p-x-10px">
                                13 <br />
                                lu
                              </th>
                              <th class="p-x-10px">
                                14 <br />
                                ma
                              </th>
                              <th class="p-x-10px">
                                15 <br />
                                mi
                              </th>
                              <th class="p-x-10px">
                                16 <br />
                                ju
                              </th>
                              <th class="p-x-10px">
                                17 <br />
                                vi
                              </th>
                              <th class="p-x-10px bg-color-acc3c7">
                                18 <br />
                                sa
                              </th>
                              <th class="p-x-10px">
                                19 <br />
                                do
                              </th>
                              <th class="p-x-10px">
                                20 <br />
                                lu
                              </th>
                              <th class="p-x-10px">
                                21 <br />
                                ma
                              </th>
                              <th class="p-x-10px">
                                22 <br />
                                mi
                              </th>
                              <th class="p-x-10px">
                                23 <br />
                                ju
                              </th>
                              <th class="p-x-10px">
                                24 <br />
                                vi
                              </th>
                              <th class="p-x-10px bg-color-acc3c7">
                                25 <br />
                                sa
                              </th>
                            </tr>
                            <tr class="table-dias data-numero-semana">
                              <th class="p-x-10px">1</th>
                              <th class="p-x-10px">2</th>
                              <th class="p-x-10px">3</th>
                              <th class="p-x-10px">4</th>
                              <th class="p-x-10px">5</th>
                              <th class="p-x-10px">6</th>
                              <td class="p-x-10px bg-color-acc3c7">7</td>
                              <th class="p-x-10px">8</th>
                              <th class="p-x-10px">9</th>
                              <th class="p-x-10px">10</th>
                              <th class="p-x-10px">11</th>
                              <th class="p-x-10px">12</th>
                              <th class="p-x-10px">13</th>
                              <td class="p-x-10px bg-color-acc3c7">14</td>
                            </tr>
                          </thead>
                          <tbody class="tcuerpo data_table_body">
                            <tr>
                              <td>H/N</td>
                              <td rowspan="2" class="center-vertical">POOL STIWART BRIONES SÁNCHEZ</td>
                              <td rowspan="2" class="center-vertical">Operario</td>
                              <td class="text-center">
                                <span class="span_asist span_HN_1_12-09-2021">-</span>
                                <input
                                  class="w-px-30 input_asist input_HN_1_1 input_HN_1_12-09-2021 hidden"
                                  id="input_HN_1_12-09-2021"
                                  onkeyup="delay(function(){ calcular_he('12-09-2021', 'span_HE_1_12-09-2021', 'input_HN_1_12-09-2021', '1', '14', '58.33', '1') }, 300 );"
                                  type="text"
                                  value=""
                                  autocomplete="off"
                                />
                              </td>
                              <td class="text-center">
                                <span class="span_asist span_HN_1_13-09-2021">-</span>
                                <input
                                  class="w-px-30 input_asist input_HN_1_2 input_HN_1_13-09-2021 hidden"
                                  id="input_HN_1_13-09-2021"
                                  onkeyup="delay(function(){ calcular_he('13-09-2021', 'span_HE_1_13-09-2021', 'input_HN_1_13-09-2021', '1', '14', '58.33', '1') }, 300 );"
                                  type="text"
                                  value=""
                                  autocomplete="off"
                                />
                              </td>
                              <td class="text-center">
                                <span class="span_asist span_HN_1_14-09-2021">-</span>
                                <input
                                  class="w-px-30 input_asist input_HN_1_3 input_HN_1_14-09-2021 hidden"
                                  id="input_HN_1_14-09-2021"
                                  onkeyup="delay(function(){ calcular_he('14-09-2021', 'span_HE_1_14-09-2021', 'input_HN_1_14-09-2021', '1', '14', '58.33', '1') }, 300 );"
                                  type="text"
                                  value=""
                                  autocomplete="off"
                                />
                              </td>
                              <td class="text-center">
                                <span class="span_asist span_HN_1_15-09-2021">-</span>
                                <input
                                  class="w-px-30 input_asist input_HN_1_4 input_HN_1_15-09-2021 hidden"
                                  id="input_HN_1_15-09-2021"
                                  onkeyup="delay(function(){ calcular_he('15-09-2021', 'span_HE_1_15-09-2021', 'input_HN_1_15-09-2021', '1', '14', '58.33', '1') }, 300 );"
                                  type="text"
                                  value=""
                                  autocomplete="off"
                                />
                              </td>
                              <td class="text-center">
                                <span class="span_asist span_HN_1_16-09-2021">-</span>
                                <input
                                  class="w-px-30 input_asist input_HN_1_5 input_HN_1_16-09-2021 hidden"
                                  id="input_HN_1_16-09-2021"
                                  onkeyup="delay(function(){ calcular_he('16-09-2021', 'span_HE_1_16-09-2021', 'input_HN_1_16-09-2021', '1', '14', '58.33', '1') }, 300 );"
                                  type="text"
                                  value=""
                                  autocomplete="off"
                                />
                              </td>
                              <td class="text-center">
                                <span class="span_asist span_HN_1_17-09-2021">-</span>
                                <input
                                  class="w-px-30 input_asist input_HN_1_6 input_HN_1_17-09-2021 hidden"
                                  id="input_HN_1_17-09-2021"
                                  onkeyup="delay(function(){ calcular_he('17-09-2021', 'span_HE_1_17-09-2021', 'input_HN_1_17-09-2021', '1', '14', '58.33', '1') }, 300 );"
                                  type="text"
                                  value=""
                                  autocomplete="off"
                                />
                              </td>
                              <td rowspan="2" class="text-center bg-color-acc3c7 center-vertical"><input class="w-xy-20" type="checkbox" /></td>
                              <td class="text-center">
                                <span class="span_asist span_HN_1_19-09-2021">-</span>
                                <input
                                  class="w-px-30 input_asist input_HN_1_8 input_HN_1_19-09-2021 hidden"
                                  id="input_HN_1_19-09-2021"
                                  onkeyup="delay(function(){ calcular_he('19-09-2021', 'span_HE_1_19-09-2021', 'input_HN_1_19-09-2021', '1', '14', '58.33', '1') }, 300 );"
                                  type="text"
                                  value=""
                                  autocomplete="off"
                                />
                              </td>
                              <td class="text-center">
                                <span class="span_asist span_HN_1_20-09-2021">-</span>
                                <input
                                  class="w-px-30 input_asist input_HN_1_9 input_HN_1_20-09-2021 hidden"
                                  id="input_HN_1_20-09-2021"
                                  onkeyup="delay(function(){ calcular_he('20-09-2021', 'span_HE_1_20-09-2021', 'input_HN_1_20-09-2021', '1', '14', '58.33', '1') }, 300 );"
                                  type="text"
                                  value=""
                                  autocomplete="off"
                                />
                              </td>
                              <td class="text-center">
                                <span class="span_asist span_HN_1_21-09-2021">-</span>
                                <input
                                  class="w-px-30 input_asist input_HN_1_10 input_HN_1_21-09-2021 hidden"
                                  id="input_HN_1_21-09-2021"
                                  onkeyup="delay(function(){ calcular_he('21-09-2021', 'span_HE_1_21-09-2021', 'input_HN_1_21-09-2021', '1', '14', '58.33', '1') }, 300 );"
                                  type="text"
                                  value=""
                                  autocomplete="off"
                                />
                              </td>
                              <td class="text-center">
                                <span class="span_asist span_HN_1_22-09-2021">-</span>
                                <input
                                  class="w-px-30 input_asist input_HN_1_11 input_HN_1_22-09-2021 hidden"
                                  id="input_HN_1_22-09-2021"
                                  onkeyup="delay(function(){ calcular_he('22-09-2021', 'span_HE_1_22-09-2021', 'input_HN_1_22-09-2021', '1', '14', '58.33', '1') }, 300 );"
                                  type="text"
                                  value=""
                                  autocomplete="off"
                                />
                              </td>
                              <td class="text-center">
                                <span class="span_asist span_HN_1_23-09-2021">-</span>
                                <input
                                  class="w-px-30 input_asist input_HN_1_12 input_HN_1_23-09-2021 hidden"
                                  id="input_HN_1_23-09-2021"
                                  onkeyup="delay(function(){ calcular_he('23-09-2021', 'span_HE_1_23-09-2021', 'input_HN_1_23-09-2021', '1', '14', '58.33', '1') }, 300 );"
                                  type="text"
                                  value=""
                                  autocomplete="off"
                                />
                              </td>
                              <td class="text-center">
                                <span class="span_asist span_HN_1_24-09-2021">-</span>
                                <input
                                  class="w-px-30 input_asist input_HN_1_13 input_HN_1_24-09-2021 hidden"
                                  id="input_HN_1_24-09-2021"
                                  onkeyup="delay(function(){ calcular_he('24-09-2021', 'span_HE_1_24-09-2021', 'input_HN_1_24-09-2021', '1', '14', '58.33', '1') }, 300 );"
                                  type="text"
                                  value=""
                                  autocomplete="off"
                                />
                              </td>
                              <td rowspan="2" class="text-center bg-color-acc3c7 center-vertical"><input class="w-xy-20" type="checkbox" /></td>
                              <td class="text-center center-vertical"><span class="total_HN_1">0</span></td>
                              <td class="text-center center-vertical" rowspan="2"><span class="dias_asistidos_1">0</span></td>
                              <td class="text-center center-vertical" rowspan="2">14,000.00</td>
                              <td class="text-center center-vertical" rowspan="2">466.67</td>
                              <td class="text-center center-vertical" rowspan="2">58.33</td>
                              <td class="text-center center-vertical" rowspan="2"><span class="sabatical_1">0</span></td>
                              <td class="text-center center-vertical"><span class="pago_parcial_HN_1"> 0.00</span></td>
                              <td rowspan="2" class="text-center center-vertical">
                                <span class="span_asist">0</span> <input class="w-px-45 input_asist hidden adicional_descuento_1" onkeyup="delay(function(){ adicional_descuento('1', '1') }, 300 );" type="text" value="0" autocomplete="off" />
                                <span class="badge badge-info float-right cursor-pointer" data-toggle="tooltip" data-original-title="Por descuento" onclick="modal_adicional_descuento( '', '1', '2021-09-12');"><i class="far fa-eye"></i></span>
                              </td>
                              <td rowspan="2" class="text-center center-vertical"><span class="val_pago_quincenal_1 pago_quincenal_1"> 0.00 </span></td>
                              
                            </tr>
                            <tr>
                              <td>H/E</td>
                              <td class="text-center"><span class="span_HE_1_12-09-2021">-</span> <input class="w-px-30 input_HE_1_1 input_HE_1_12-09-2021 hidden" type="text" value="" /></td>
                              <td class="text-center"><span class="span_HE_1_13-09-2021">-</span> <input class="w-px-30 input_HE_1_2 input_HE_1_13-09-2021 hidden" type="text" value="" /></td>
                              <td class="text-center"><span class="span_HE_1_14-09-2021">-</span> <input class="w-px-30 input_HE_1_3 input_HE_1_14-09-2021 hidden" type="text" value="" /></td>
                              <td class="text-center"><span class="span_HE_1_15-09-2021">-</span> <input class="w-px-30 input_HE_1_4 input_HE_1_15-09-2021 hidden" type="text" value="" /></td>
                              <td class="text-center"><span class="span_HE_1_16-09-2021">-</span> <input class="w-px-30 input_HE_1_5 input_HE_1_16-09-2021 hidden" type="text" value="" /></td>
                              <td class="text-center"><span class="span_HE_1_17-09-2021">-</span> <input class="w-px-30 input_HE_1_6 input_HE_1_17-09-2021 hidden" type="text" value="" /></td>
                              <td class="text-center"><span class="span_HE_1_19-09-2021">-</span> <input class="w-px-30 input_HE_1_8 input_HE_1_19-09-2021 hidden" type="text" value="" /></td>
                              <td class="text-center"><span class="span_HE_1_20-09-2021">-</span> <input class="w-px-30 input_HE_1_9 input_HE_1_20-09-2021 hidden" type="text" value="" /></td>
                              <td class="text-center"><span class="span_HE_1_21-09-2021">-</span> <input class="w-px-30 input_HE_1_10 input_HE_1_21-09-2021 hidden" type="text" value="" /></td>
                              <td class="text-center"><span class="span_HE_1_22-09-2021">-</span> <input class="w-px-30 input_HE_1_11 input_HE_1_22-09-2021 hidden" type="text" value="" /></td>
                              <td class="text-center"><span class="span_HE_1_23-09-2021">-</span> <input class="w-px-30 input_HE_1_12 input_HE_1_23-09-2021 hidden" type="text" value="" /></td>
                              <td class="text-center"><span class="span_HE_1_24-09-2021">-</span> <input class="w-px-30 input_HE_1_13 input_HE_1_24-09-2021 hidden" type="text" value="" /></td>
                              <td class="text-center"><span class="total_HE_1">0</span></td>
                              <td class="text-center"><span class="pago_parcial_HE_1"> 0.00</span></td>
                            </tr>
                            <tr>
                              <td class="text-center" colspan="24"></td>
                              <td class="text-center"><b>TOTAL</b></td>
                              <td class="text-center"><span class="pago_total_quincenal"> 0.00</span></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>                    
                    </div>

                    <div class="modal-footer justify-content-end">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>                  
                  </div>
                </div>
              </div>

              <!-- Modal ver detalle del proyecto -->
              <div class="modal fade" id="modal-ver-detalle">
                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title" id="detalle_titl">Detalle del proyecto</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    
                    <div class="modal-body">
                      <div class="row" id="cargando-detalle-proyecto">
                        <div class="col-lg-12 text-center">
                          <i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>
                          <h4>Cargando...</h4>
                        </div>
                      </div>
                      <!-- /.card-body -->
                    </div>
                                       
                  </div>
                </div>
              </div>


            </section>
            <!-- /.content -->
          </div>
          <!--Fin-Contenido-->
          <?php
            }else{
              require 'noacceso.php';
            }
            require 'footer.php';
          ?>

        </div>
        <?php          
          require 'script.php';
        ?>
         

        <script type="text/javascript" src="scripts/pago_obrero.js"></script>
        <!-- previzualizamos el pdf cargado -->
        <script type="text/javascript">
          function PreviewImage() {

            pdffile=document.getElementById("doc").files[0];

            antiguopdf=$("#docActual").val();

            if(pdffile === undefined){

              var dr = antiguopdf;

              if (dr == "") {

                $("#ver_pdf").html(''+
                  '<div class="alert alert-danger alert-dismissible">'+
                      '<button style="color: white !important;" type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'+
                      '<h4><i class="icon fa fa-warning"></i> Alerta!</h4>'+
                      'Seleciona un documento y luego PULSE el boton AMARILLO.'+
                  '</div>'
                );

              } else {

                $("#ver_pdf").html('<iframe src="'+dr+'" frameborder="0" scrolling="no" width="100%" height="210"></iframe>');
              }
              // console.log('hola'+dr);
            }else{

              pdffile_url=URL.createObjectURL(pdffile);

              $("#ver_pdf").html('<iframe src="'+pdffile_url+'" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');

              console.log('hola');
            }
          }
        </script>
        <script>
          $(function () {
            $('[data-toggle="tooltip"]').tooltip();
          })
        </script>

        <script>
          if ( localStorage.getItem('nube_idproyecto') ) {

            console.log("icon_folder_"+localStorage.getItem('nube_idproyecto'));

            $("#ver-proyecto").html('<i class="fas fa-tools"></i> Proyecto: ' +  localStorage.getItem('nube_nombre_proyecto'));

            $(".ver-otros-modulos-1").show();

            // $('#icon_folder_'+localStorage.getItem('nube_idproyecto')).html('<i class="fas fa-folder-open"></i>');

          }else{
            $("#ver-proyecto").html('<i class="fas fa-tools"></i> Selecciona un proyecto');

            $(".ver-otros-modulos-1").hide();
          }
          
        </script>
      </body>
    </html>
    <?php    
  }
  ob_end_flush();
?>
