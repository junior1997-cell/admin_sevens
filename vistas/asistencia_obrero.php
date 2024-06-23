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

        <title>Asistencia Obrero | Admin Sevens</title>

        <?php $title = "Asistencia Obrero"; require 'head.php'; ?>
        <link rel="stylesheet" href="../dist/css/switch_domingo.css">

        <style>          
          /* fijar table head */
          .table.table-head-fixed thead tr:nth-child(2) th {
            background-color: #fff;
            border-bottom: black 1px solid;
            box-shadow: 0 1px 10px 7px #dee2e6, 12px -2px 9px 6px #dee2e6;
            position: -webkit-sticky;
            position: sticky;
            top: 50px;
            z-index: 10;
          }
        </style>

      </head>
      <body class="hold-transition sidebar-collapse sidebar-mini layout-fixed layout-navbar-fixed" idproyecto="<?php echo $_SESSION['idproyecto']; ?>">
        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper">
          <?php  
          require 'nav.php'; 
          require 'aside.php'; 

          if ($_SESSION['asistencia_obrero']==1){  
            //require 'enmantenimiento.php';
            ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
              <!-- Content Header (Page header) -->
              <section class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1>Asistencia 
                        <button type="button" class="btn bg-gradient-success btn-sm" data-toggle="modal" data-target="#modal-agregar-fechas-actividades" onclick="limpiar_form_fechas_actividades();">
                          <i class="far fa-calendar-alt"></i> Fechas de Actividades
                        </button>
                        <button type="button" class="btn bg-gradient-fuchsia btn-sm" data-toggle="modal" data-target="#modal-agregar-horario" onclick="mostrar_horario();">
                          <i class="far fa-clock"></i> Horario
                        </button>
                        <a type="button" class="btn bg-gradient-gray btn-sm" id="btn-export-qs-todos" href="#" target="_blank" >
                          <i class="fa-regular fa-file-excel"></i> Export todo
                        </a>
                        <a type="button" class="btn bg-gradient-gray btn-sm" id="btn-export-qs" href="#" target="_blank" onclick="toastr_error('No hay datos!!', 'Seleccione una quincena o semana para exportar.');">
                          <i class="fa-regular fa-file-excel"></i> Export
                        </a> 

                        <button type="button" class="btn bg-gradient-primary btn-sm btn_mostrar_hne activado_hne" data-toggle="tooltip" data-original-title="Hora normal y extra." style="display: none;" >HNE</button>
                        <button type="button" class="btn btn-outline-primary btn-sm btn_mostrar_hn" data-toggle="tooltip" data-original-title="Hora normal." style="display: none;">HN</button>
                        <button type="button" class="btn btn-outline-primary btn-sm btn_mostrar_he" data-toggle="tooltip" data-original-title="Hora extra" style="display: none;">HE</button>
                      </h1>
                    </div>
                    <div class="col-sm-6"> 
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">asistencia</li>
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
                          <h3 class="card-title" id="btn-registrar">
                            <!-- <button type="button" class="btn bg-gradient-success"  data-toggle="modal" data-target="#modal-agregar-asistencia" onclick="limpiar();" style="margin-right: 10px;"><i class="fas fa-user-plus"></i> Agregar </button> -->
                          </h3>
                          <!-- regresar -->
                          <h3 class="card-title mr-3" id="btn-regresar" style="display: none; padding-left: 2px;">
                            <button type="button" class="btn bg-gradient-warning btn-sm h-50px" onclick="mostrar_form_table(1);despintar_btn_select();"><i class="fas fa-arrow-left"></i> <span class="d-none d-sm-inline-block">Regresar</span> </button>
                          </h3>
                          <!-- Editar --> 
                          <h3 class="card-title mr-3" id="btn-editar" style="display: none; padding-left: 2px;">
                            <button type="button" class="btn bg-gradient-orange btn-sm h-50px" onclick="show_hide_span_input(2);"><i class="fas fa-pencil-alt"></i> <span class="d-none d-sm-inline-block">Editar</span> </button>
                          </h3>
                          <!-- Guardar -->
                          <h3 class="card-title mr-3" id="btn-guardar-hn" style="display: none; padding-left: 2px;">
                            <button type="button" class="btn bg-gradient-success btn-guardar-asistencia btn-sm h-50px" onclick="guardar_fechas_asistencia_hn();" style="margin-right: 10px;"><i class="far fa-save"></i> <span class="d-none d-sm-inline-block"> Guardar hn </span> </button>
                          </h3>
                          <h3 class="card-title mr-3" id="btn-guardar-he" style="display: none; padding-left: 2px;">
                            <button type="button" class="btn bg-gradient-success btn-guardar-asistencia btn-sm h-50px" onclick="guardar_fechas_asistencia_he();" style="margin-right: 10px;"><i class="far fa-save"></i> <span class="d-none d-sm-inline-block"> Guardar he </span> </button>
                          </h3>
                          <!-- Botones de quincenas -->
                          <div id="lista_quincenas" class="row-horizon disenio-scroll" >
                            <!-- <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-asistencia" onclick="limpiar();"><i class="fas fa-user-plus"></i> Agregar </button>
                            <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-asistencia" onclick="limpiar();"><i class="fas fa-user-plus"></i> Agregar </button>-->
                          </div>                        
                        </div>
                        <!-- /.card-header --> 

                        <div class="card-body " >

                          <!-- TBLA - PRINCIPAL -->
                          <div id="tabla-asistencia-trab" >
                            <table id="tabla-asistencia" class="table table-bordered table-striped display " style="width: 100% !important;">
                              <thead >
                                <tr>
                                  <th>#</th>
                                  <th class="text-center" data-toggle="tooltip" data-original-title="Opciones.">Op.</th>
                                  <th>Nombre</th>
                                  <th class="text-center">Total Horas</th>
                                  <th class="text-center">Total Días</th>                                
                                  <th class="text-center">Sueldo hora</th>
                                  <th class="text-center">Sueldo diario</th>
                                  <th class="text-center">Sueldo mensual</th>                                
                                  <th class="text-center">Sáb.</th>
                                  <th class="text-center">Adicional</th>
                                  <th class="text-center">Pago acumulado</th>
                                  <th class="text-center">Cargo</th>
                                  <th class="text-center">Trabajdor</th>
                                  <th class="text-center">Documento</th> 
                                </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                <tr>
                                  <th>#</th>
                                  <th class="text-gray">Op</th>
                                  <th class="text-gray">Nombre</th>
                                  <th class="text-gray">total Horas</th>
                                  <th class="text-gray">total Días</th>                                
                                  <th class="text-gray">Pago / hora</th> 
                                  <th class="text-gray">Sueldo diario</th>                                
                                  <th class="text-gray">Sueldo mensual</th>                                
                                  <th class="text-gray">Sáb.</th>
                                  <th class="text-center">0.00</th>
                                  <th class="text-nowrap h5 "> 0.00</th>
                                  <th class="text-center">Cargo</th>
                                  <th class="text-center">Trabajdor</th>
                                  <th class="text-center">Documento</th>
                                </tr>
                              </tfoot>
                            </table>
                          </div>

                          <!-- TBLA- REGISTRO DE ASISTENCIA-->                         
                          <div class="table-responsive p-0" id="ver_asistencia" style="display: none; ">
                            <input type="hidden" id="tipo_hora">
                            <table class="table table-hover table-head-fixed text-nowrap tabla_sistencia_obrero" style="border: black 1px solid;">
                              <thead class="bg-white" >
                                <tr class="celda-b-t-1px ">
                                  <th rowspan="2" class="stile-celda inicio_tabla">#</th>
                                  <th rowspan="2" class="stile-celda "><i class="far fa-clock"></i></th>
                                  <th rowspan="2" class="stile-celda ">Nombre del trabajador</th>
                                  <th rowspan="2" class="stile-celda ">Cargo</th>
                                  <th colspan="14" id="dias_asistidos_s_q" class="text-center " style="border: black 1px solid;">
                                    <span class="badge badge-info float-left cursor-pointer shadow-1px06rem09rem-rgb-52-174-193-77 p-y-6px btn-horas-multiples" data-toggle="tooltip" data-original-title="Horas multiples" onclick="modal_horas_multiples();"><i class="far fa-clock fa-lg m-1"></i></span>  
                                    Horas de trabajo por día
                                    <span class="badge badge-info float-right cursor-pointer shadow-1px06rem09rem-rgb-52-174-193-77 p-y-6px ir_a_right mr-1" data-toggle="tooltip" data-original-title="Deslizar al la derecha"><i class="far fa-arrow-alt-circle-right fa-lg m-1"></i></span>
                                    <span class="badge badge-info float-right cursor-pointer shadow-1px06rem09rem-rgb-52-174-193-77 p-y-6px ir_a_bottom mr-1" data-toggle="tooltip" data-original-title="Deslizar al final"><i class="far fa-arrow-alt-circle-down fa-lg m-1"></i></span>
                                    <span class="badge badge-info float-right cursor-pointer shadow-1px06rem09rem-rgb-52-174-193-77 p-y-6px ir_a_left mr-1" data-toggle="tooltip" data-original-title="Deslizar al la izquierda"><i class="far fa-arrow-alt-circle-left fa-lg m-1"></i></span>                                  
                                     
                                  </th>
                                  <th rowspan="2" class="stile-celda table_title_hne">Horas<br>HN/HE</th>
                                  <th rowspan="2" class="stile-celda ">Total<br>Días</th>
                                  <th rowspan="2" class="stile-celda ">Sueldo <br> Semanal</th>
                                  <th rowspan="2" class="stile-celda ">Jornal</th>
                                  <th rowspan="2" class="stile-celda ">Sueldo <br> hora</th>
                                  <th rowspan="2" class="stile-celda ">Sáb.</th>
                                  <th rowspan="2" class="stile-celda ">Pago <br> parcial</th>
                                  <th rowspan="2" class="stile-celda ">Adicional <br> descuento</th>
                                  <th rowspan="2" class="stile-celda head_pago_q_s ">Pago <br> quincenal</th> 
                                  <th rowspan="2" class="stile-celda final_tabla"><i class="fas fa-hand-holding-usd fa-2x"></i></th>
                                </tr>
                                
                                <tr class="table-dias data-dia-semana">
                                  <!-- <th class=""> Lu </th>
                                  <th class=""> Ma </th>
                                  <th class=""> Mi </th>
                                  <th class=""> Ju </th>
                                  <th class=""> Vi </th>
                                  <th class=" bg-color-acc3c7" >Sa </th>
                                  <th class=""> Do </th>
                                  <th class=""> Lu </th>
                                  <th class=""> Ma </th>
                                  <th class=""> Mi </th>
                                  <th class=""> Ju </th>
                                  <th class=""> Vi </th>
                                  <th class=" bg-color-acc3c7"> Sa </th>
                                  <th class=""> Do </th>
                                  <th class=""> Lu </th> -->
                                </tr>
                                <!-- <tr class="table-dias data-numero-semana">
                                  <th>1</th> <th>2</th> <th>3</th> <th>4</th> <th>5</th> <th>6</th> <th>7</th> 
                                  <th>8</th> <th>9</th> <th>10</th> <th>11</th> <th>12</th> <th>13</th> <th>14</th> 
                                </tr> -->
                              </thead>
                              <tbody class="tcuerpo data_table_body" >
                                <!-- <tr>
                                  <td>H/N</td>
                                  <td>Maestro de obra</td>
                                  <td>8</td>
                                  <td>8</td>
                                  <td>8</td>
                                  <td>8</td>
                                  <td>8</td>
                                  <td>0</td>
                                  <td>4</td>
                                  <td>48</td>
                                  <td>3000</td>
                                  <td>107</td>
                                  <td>13.39</td>
                                  <td>1</td>
                                  <td>1</td>
                                  <td>750.00</td>
                                </tr>
                                <tr>
                                  <td>H/E</td>
                                  <td>Maestro de obra</td>
                                  <td>0</td>
                                  <td>2</td>
                                  <td>1</td>
                                  <td>0</td>
                                  <td>0</td>
                                  <td>0</td>
                                  <td>1</td>
                                  <td>4</td>
                                  <td>300</td>
                                  <td>107.00</td>
                                  <td>13.39</td>
                                  <td>0</td>
                                  <td>0</td>
                                  <td>53.56</td>
                                </tr>
                                <tr>
                                  <td colspan="14"></td>
                                  <td ><b>TOTAL</b></td>
                                  <td>803.56</td>
                                </tr> -->
                              </tbody>
                            </table>
                              
                          </div>                         

                          <!-- CARGANDO - REGISTRO DE ASISTENCIA -->
                          <div class="row" id="cargando-registro-asistencia" style="display: none;">
                            
                          </div>

                          <!-- TBLA - DIAS DE ASISTENCIA POR TRABAJADOR -->
                          <div id="detalle_asistencia" style="display: none;">
                            <table id="tabla-detalle-asistencia-individual" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th class="">Aciones</th>
                                  <th>Nombre</th>
                                  <th>Horas Normal</th>
                                  <th>Pago Hr. Normal</th>
                                  <th>Hora Extras</th>
                                  <th>Pago Hr. Extras</th>
                                  <th>Fecha Asistencia</th>
                                  <th>Estado</th>
                                </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                <tr>
                                  <th class="">Aciones</th>
                                  <th>Nombre</th>
                                  <th class="text-center">Horas Normal</th>
                                  <th class="text-nowrap">Pago Hr. Normal</th>
                                  <th class="text-center">Hora Extras</th>
                                  <th class="text-nowrap">Pago Hr. Extras</th>
                                  <th>Fecha Asistencia</th>
                                  <th>Estado</th>
                                </tr>
                              </tfoot>
                            </table>
                          </div>

                          <!-- TBLA - QUINCENA SEMANA POR TRABAJADOR  -->
                          <div id="detalle_qs" style="display: none;">
                            <table id="tabla-detalle-qs-individual" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th class="text-center">Op.</th>                                  
                                  <th class="text-center thead_fecha">Fechas</th>
                                  <th >Hr. Normal/Extra</th>
                                  <th class="text-center">Dias</th>
                                  <th>Pago Hr. Normal/Extra</th>
                                  <th>Adicional</th>
                                  <th class="text-center">Sáb.</th>
                                  <th class="thead_pago">Pago</th>
                                  <th class="text-center">Contador</th>
                                  <th class="text-center">Estado</th>
                                  <th class="text-center">Trabajador</th>
                                  <th class="text-center">Documento</th>
                                  <th class="text-center thead_num">Num.</th>
                                </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                <tr>
                                  <th class="text-gray-50 text-center">Op.</th>                                  
                                  <th class="text-gray-50 text-center thead_fecha">Fechas</th>
                                  <th class="text-gray-50 ">Hr. Normal/Extra</th>
                                  <th class="text-nowrap text-center h5 suma_qs_dias_asistidos"><i class="fas fa-spinner fa-pulse fa-sm"></i></th>
                                  <th class="text-gray-50">Pago Hr. Normal/Extra</th>
                                  <th class="text-nowrap text-right h5 suma_qs_adicional">S/ <i class="fas fa-spinner fa-pulse fa-sm"></i></th>
                                  <th class="text-nowrap text-center h5 suma_qs_sabatical"><i class="fas fa-spinner fa-pulse fa-sm"></i></th>
                                  <th class="text-nowrap text-right h5 suma_qs_pago_quincenal">S/ <i class="fas fa-spinner fa-pulse fa-sm"></i></th>
                                  <th class="text-gray-50 text-center">Contador</th>
                                  <th class="text-gray-50">Estado</th>
                                  <th class="text-center">Trabajador</th>
                                  <th class="text-center">Documento</th>
                                  <th class="text-center thead_num">Num.</th>
                                </tr>
                              </tfoot>
                            </table>
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
                </div>
                <!-- MODAL - agregar asistencia - :::::::::::::::::::::::::::::::::: NO SE SE USA -->
                <div class="modal fade" id="modal-agregar-asistencia">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Agregar asistencia</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form id="form-asistencia" name="form-asistencia" method="POST">                    
                          <div class="row" >
                            <!-- id proyecto -->
                            <input type="hidden" name="idproyecto" id="idproyecto" required />

                            <!-- id asistencia -->
                            <input type="hidden" name="idasistencia_trabajador" id="idasistencia_trabajador" />

                            <!-- fecha del registro de la asistencia -->
                            <div class="col-lg-4  mb-2">
                              <div class="form-group">
                                <label for="fecha">Fecha de asistencia</label>
                                <input type="date" class="form-control" name="fecha" id="fecha"  />                            
                              </div>
                            </div>

                            <!-- Seleccionar una fecha para todos -->
                            <div class="col-lg-4 mb-2">
                              <div class="bootstrap-timepicker">
                                <div class="form-group">
                                  <label>Hora para todos:</label>
                                  <div class="input-group date" id="timepicker" data-target-input="nearest">
                                    <input type="text" id="hora_all" class="form-control datetimepicker-input" data-target="#timepicker" onchange="agregar_hora_all();" onkeyup="agregar_hora_all();" oninput="agregar_hora_all()" />
                                    <div class="input-group-append" data-target="#timepicker" data-toggle="datetimepicker">
                                      <div class="input-group-text"><i class="far fa-clock"></i></div>
                                    </div>
                                    </div>
                                  <!-- /.input group -->
                                </div>
                                <!-- /.form group -->
                              </div>
                            </div>

                            <div class="col-lg-4"></div>
                            
                            <div class="col-lg-12">
                              <div class="row" id="lista-de-trabajadores">
                                <!-- Lista de todos lo trabajadores -->
                              </div>                                                  
                            </div> 
                          </div>                   
                          
                          <!-- /.card-body -->
                          <button type="submit" style="display: none;" id="submit-form-asistencia">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro">Guardar Cambios</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- MODAL - editar asistencia - chargue 1,2 - :::::::::::::::::::::::::::::::::::: NO SE SE USA -->
                <div class="modal fade" id="modal-editar-asistencia">
                  <div class="modal-dialog modal-dialog-scrollable modal-md">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Editar asistencia</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form id="form-editar-asistencia" name="form-editar-asistencia" method="POST">
                          <div class="row" id="cargando-1-fomulario">
                            <!-- id proyecto -->
                            <input type="hidden" name="idproyecto2" id="idproyecto2" required />

                            <!-- id asistencia -->
                            <input type="hidden" name="idasistencia_trabajador2" id="idasistencia_trabajador2"   />

                            <!-- fecha del registro de la asistencia -->
                            <div class="col-lg-12 mb-2">
                              <div class="form-group">
                                <label for="fecha">Fecha de asistencia</label>
                                <input type="date" class="form-control" name="fecha2" id="fecha2"  />                            
                              </div>
                            </div>                      
                            
                            <div class="col-lg-12">
                              <div class="row" id="lista-de-trabajadores2">
                                <!-- Lista de todos lo trabajadores -->
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
                          <button type="submit" style="display: none;" id="submit-form-asistencia2">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro2">Guardar Cambios</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- MODAL - justificar asistencia - chargue 3,4 -->
                <div class="modal fade" id="modal-justificar-asistencia">
                  <div class="modal-dialog modal-dialog-scrollable modal-md">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Justificación</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form id="form-justificar-asistencia" name="form-justificar-asistencia" method="POST">
                          <div class="row" id="cargando-3-fomulario">
                            
                            <!-- id asistencia -->
                            <input type="hidden" name="idasistencia_trabajador_j" id="idasistencia_trabajador_j" /> 
                            
                            <!-- Descripcion -->
                            <div class="col-md-12 col-lg-12">
                              <div class="form-group">
                                <label for="nombre">Descripción</label>
                                <textarea name="detalle_j" id="detalle_j" class="form-control" rows="5" placeholder="Ingresa descripción"></textarea>
                              </div>
                            </div>

                            <!-- Documento -->
                            <div class="col-md-12 col-lg-12" > 
                              <!-- linea divisoria -->
                              <div class="col-lg-12 borde-arriba-naranja mt-2"> </div>

                              <div class="row text-center">
                                <div class="col-md-12" style="padding-top: 15px; padding-bottom: 5px;">
                                  <label for="cip" class="control-label" > Evidencia </label>
                                </div>
                                <!-- Subir documento -->
                                <div class="col-md-3 text-center">
                                  <button type="button" class="btn btn-success btn-block btn-xs" id="doc1_i">
                                    <i class="fas fa-file-upload"></i> Subir.
                                  </button>
                                  <input type="hidden" id="doc_old_1" name="doc_old_1" />
                                  <input style="display: none;" id="doc1" type="file" name="doc1" accept="application/pdf, image/*" class="docpdf" /> 
                                </div>
                                <!-- Recargar -->
                                <div class="col-md-3 text-center"> 
                                  <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(1, 'asistencia_obrero', 'justificacion');">
                                    <i class="fa fa-eye"></i> PDF.
                                  </button>
                                </div>
                                <!-- Dowload -->
                                <div class="col-md-3 text-center descargar" style="display: none;">
                                  <a type="button" class="btn btn-warning btn-block btn-xs" id="descargar_rh" download="Justificacion"> <i class="fas fa-download"></i> Descargar. </a>
                                </div>
                                <!-- Ver grande -->
                                <div class="col-md-3 text-center ver_completo" style="display: none;">
                                  <a type="button" class="btn btn-info btn-block btn-xs " target="_blank" id="ver_completo"> <i class="fas fa-expand"></i> Ver completo. </a>
                                </div>
                              </div>                              
                              <div id="doc1_ver" class="text-center mt-4">
                                <img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >
                              </div>
                              <div class="text-center" id="doc1_nombre"><!-- aqui va el nombre del pdf --></div>                            
                            </div> 

                          </div>

                          <div class="row" id="cargando-4-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                              <br />
                              <h4>Cargando...</h4>
                            </div>
                          </div>
                          <!-- /.card-body -->
                          <button type="submit" style="display: none;" id="submit-form-justificacion">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro_justificacion">Guardar Cambios</button>
                      </div>
                    </div>
                  </div>
                </div> 
                
                <!-- MODAL - adicinoal / descuento - chargue 5,6 -->
                <div class="modal fade" id="modal-adicional-descuento" >
                  <div class="modal-dialog modal-dialog-scrollable modal-md">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Adicional / descuento</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form id="form-adicional-descuento" name="form-adicional-descuento" method="POST">
                          <div class="row" id="cargando-5-fomulario">
                            
                            <!-- id adicionales -->
                            <input type="hidden" name="idresumen_q_s_asistencia" id="idresumen_q_s_asistencia" /> 
                            <!-- ID trabajador por proyecto -->
                            <input type="hidden" name="idtrabajador_por_proyecto" id="idtrabajador_por_proyecto" />   
                            <!-- fecha de quincena o semana -->
                            <input type="hidden" name="ad_hne" id="ad_hne" />                                         
                            
                            <!-- Descripcion -->
                            <div class="col-md-12 col-lg-12">
                              <div class="form-group">
                                <label for="nombre">Descripción</label>
                                <textarea name="detalle_adicional" id="detalle_adicional" class="form-control" rows="5" placeholder="Ingresa descripción"></textarea>
                              </div>
                            </div> 
                          </div>

                          <div class="row" id="cargando-6-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                              <br />
                              <h4>Cargando...</h4>
                            </div>
                          </div>
                          <!-- /.card-body -->
                          <button type="submit" style="display: none;" id="submit-form-adicional-descuento">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_adicional_descuento">Guardar Cambios</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- MODAL - adicinoal / descuento - chargue 9,10 -->
                <div class="modal fade" id="modal-adicional-descuento-hne" >
                  <div class="modal-dialog modal-dialog-scrollable modal-md">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Adicional / descuento</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">                       
                        <div class="row" id="html-ad-hne">                                                             
                          
                        </div>                          
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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
                          <div class="progress h-px-30" id="div_barra_progress">
                            <div id="barra_progress" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                              0%
                            </div>
                          </div>
                        </div> 
                        
                        <!-- boton -->
                        <div class="swal2-actions" >
                          <div class="swal2-loader"></div>
                          <button onclick="cerrar_modal_cargando()" type="button" class="swal2-confirm swal2-styled" aria-label="" style="display: inline-block;">OK</button>                         
                        </div>
                      </div>                     
                    </div>
                  </div>
                </div>

                <!-- MODAL - Fechas de Actividades - chargue 7,8 -->
                <div class="modal fade" id="modal-agregar-fechas-actividades">
                  <div class="modal-dialog /*modal-dialog-scrollable*/ modal-md">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Fechas de Actividades</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form id="form-fechas-actividades" name="form-fechas-actividades" method="POST">
                          <div class="row" id="cargando-7-fomulario">
                            
                            <!-- id asistencia -->
                            <input type="hidden" name="id_proyecto_f" id="id_proyecto_f" /> 

                            <!-- fechas de pago de obreros -->
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">                               
                              <div class="form-group show_hide_select_1">
                                <label for="fecha_pago_obrero_f">Pago de obreros <sup class="text-danger">*</sup></label>
                                <select class="form-control select2" name="fecha_pago_obrero_f" id="fecha_pago_obrero_f" style="width: 100%;" onchange="validar_permanent();">
                                  <option value="quincenal">Quincenal</option>
                                  <option value="semanal">Semanal</option>
                                </select>                                
                              </div>
                              <div class="form-group show_hide_select_2" style="display: none !important;">
                                
                              </div>
                            </div>

                            <!-- Swichs permanente -->
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                              <label for="fecha_pago_obrero" class="">Definir permanente </label>
                              <div class="switch-toggle show_hide_switch_1">
                                <input type="checkbox" id="definiendo" >
                                <label for="definiendo" onclick="permanente_pago_obrero()"></label>
                              </div>
                              <div class="show_hide_switch_2 mb-2" style="display: none;">
                                Esta no se configuracion <b class="text-danger">NO se puede cambiar</b>
                              </div>
                              <input type="hidden" name="permanente_pago_obrero" id="permanente_pago_obrero"  >
                            </div>

                            <!-- FECHA INICIO DE ACTIVIDADES -->
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label for="">Fecha Inicio de actividades: <sup class="text-danger">*</sup></label>
                                <div class="input-group date "  data-target-input="nearest">
                                  <input type="text" class="form-control" id="fecha_inicio_actividad" name="fecha_inicio_actividad" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask onchange="calcular_plazo_actividad();"  />
                                  <div class="input-group-append click-btn-fecha-inicio-actividad cursor-pointer" for="fecha_inicio_actividad" >
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                  </div>
                                </div>                                 
                              </div>
                            </div>
                              
                            <!-- FECHA INICIO FIN DE ACTIVIDADES -->
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label>Fecha Fin de actividades: <sup class="text-danger">*</sup></label>
                                <div class="input-group date"  data-target-input="nearest">
                                  <input type="text" class="form-control" id="fecha_fin_actividad" name="fecha_fin_actividad" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask onchange="calcular_plazo_actividad();" />
                                  <div class="input-group-append click-btn-fecha-fin-actividad cursor-pointer">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                  </div>
                                </div>                                 
                              </div>
                            </div>

                            <!-- Dias habiles -->
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label for="plazo_actividad">Plazo Actividades<sup class="text-danger">*</sup> <small class="text-orange">(días hábiles)</small> </label>
                                <span class="form-control plazo_actividad"> 0 </span>
                                <input type="hidden" name="plazo_actividad" id="plazo_actividad" >
                              </div>
                            </div> 

                          </div>

                          <div class="row" id="cargando-8-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                              <br />
                              <h4>Cargando...</h4>
                            </div>
                          </div>
                          <!-- /.card-body -->
                          <button type="submit" style="display: none;" id="submit-form-fechas-actividades">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro_fechas_actividades">Guardar Cambios</button>
                      </div>
                    </div>
                  </div>
                </div> 
                
                <!-- MODAL - Horas Multiples -->
                <div class="modal fade" id="modal-agregar-horas-multiples" data-keyboard="false" data-backdrop="static">
                  <div class="modal-dialog /*modal-dialog-scrollable*/ modal-md">
                    <div class="modal-content">

                      <div class="modal-header">
                        <h4 class="modal-title"><i class="far fa-clock fa-lg m-1"></i> Asignar horas multiples</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body"> 
                        
                        <div class="row">
                          <div class="col-12">
                            <div class="card card-primary card-outline card-outline-tabs">
                              <div class="card-header p-0 border-bottom-0">
                                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                  <li class="nav-item">
                                    <a class="nav-link active" id="custom-tabs-four-todo_sq-tab" data-toggle="pill" href="#custom-tabs-four-todo_sq" role="tab" aria-controls="custom-tabs-four-todo_sq" aria-selected="true" onclick="btn_guardar_horas_multiple(1);">Todo</a>
                                  </li>
                                  <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-four-por_dia-tab" data-toggle="pill" href="#custom-tabs-four-por_dia" role="tab" aria-controls="custom-tabs-four-por_dia" aria-selected="false" onclick="btn_guardar_horas_multiple(2);">Por dia</a>
                                  </li>
                                </ul>
                              </div>
                              <div class="card-body">
                                <div class="tab-content" id="custom-tabs-four-tabContent">
                                  <div class="tab-pane fade show active" id="custom-tabs-four-todo_sq" role="tabpanel" aria-labelledby="custom-tabs-four-todo_sq-tab">
                                    <form id="form-horas-multiples" name="form-horas-multiples" method="POST">                         
                                      
                                      <!-- Horas -->
                                      <div class="col-lg-12"> 
                                        <div class="form-group">
                                          <label for="horas_multiples">Horas<sup class="text-danger">*</sup> <small class="text-danger">(para todos los trabajadores)</small> </label>
                                          <input class="form-control" type="number" name="horas_multiples" id="horas_multiples" >
                                        </div>
                                      </div>

                                      <!-- barprogress -->
                                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                                        <div class="progress_h_multiple h-px-30" id="div_barra_progress_h_multiple">
                                          <div id="barra_progress_h_multiple" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                            0%
                                          </div>
                                        </div>
                                      </div> 
                                      
                                      <button type="submit" style="display: none;" id="submit-form-horas-multiples">Submit</button>
                                    </form>
                                  </div>
                                  <div class="tab-pane fade" id="custom-tabs-four-por_dia" role="tabpanel" aria-labelledby="custom-tabs-four-por_dia-tab">
                                    <!-- estamos en desarollo -->
                                    <form id="form-horas-por-dia-multiples" name="form-horas-por-dia-multiples" method="POST  ">
                                      <!-- dia -->
                                      <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group">
                                          <label for="dia_xd">Dia <sup class="text-danger">(unico*)</sup></label>
                                          <div class="select2-purple">
                                            <select name="dia_xd" id="dia_xd" class="form-control select2"  data-dropdown-css-class="select2-purple" data-placeholder="Seleccione" style="width: 100%;"> 
                                              <option value="Domingo">Domingo</option>
                                              <option value="Lunes">Lunes</option>
                                              <option value="Martes">Martes</option>
                                              <option value="Miércoles">Miércoles</option>
                                              <option value="Jueves">Jueves</option>
                                              <option value="Viernes">Viernes</option>
                                            </select>
                                          </div>
                                        </div>
                                      </div> 

                                      <!-- Horas -->
                                      <div class="col-lg-12"> 
                                        <div class="form-group">
                                          <label for="horas_xd">Horas<sup class="text-danger">*</sup> <small class="text-danger">(para todos los trabajadores)</small> </label>
                                          <input class="form-control" type="number" name="horas_xd" id="horas_xd" >
                                        </div>
                                      </div>

                                      <!-- barprogress -->
                                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 m-t-20px" >
                                        <div class="progress_h_por_dia h-px-30" id="div_barra_progress_h_por_dia">
                                          <div id="barra_progress_h_por_dia" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                            0%
                                          </div>
                                        </div>
                                      </div>

                                      <button type="submit" style="display: none;" id="submit-form-horas-por-dia-multiples">Submit</button>
                                    </form>
                                  </div>
                                </div>
                              </div>
                              <!-- /.card -->
                            </div>
                          </div>
                        </div>
                      </div>
                      

                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button  class="btn btn-success horas-multiples" >Asignar horas</button>
                        <button  class="btn btn-success horas-por-dia-multiples" style="display: none;" >Asignar horas x dia</button>
                      </div>

                    </div>
                  </div>
                </div>

                <!-- MODAL - HORARIO  -->
                <div class="modal fade" id="modal-agregar-horario">
                  <div class="modal-dialog modal-dialog-scrollable modal-xl">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title title-horario">Horario</h4> 
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">                        
                        <!-- form start -->                        
                        <form id="form-horario-proyecto" name="form-horario-proyecto" method="POST">
                          <input type="hidden" name="idproyecto_horario" id="idproyecto_horario">
                          <div class="row" >
                            <div class="col-12 table-responsive">
                              <table class="table table-bordered table-hover" id="tabla-hora-normal">
                                <tbody>
                                  <tr > <td class="text-center"> <i class="fas fa-spinner fa-pulse fa-6x"></i><br /> <br /> <h4>Cargando...</h4> </td> </tr>
                                </tbody>
                              </table>
                            </div>
                            <div class="col-12 table-responsive mt-4">
                              <table class="table table-bordered table-hover" id="tabla-hora-extra">
                                <tr > <td class="text-center"> <i class="fas fa-spinner fa-pulse fa-6x"></i><br /> <br /> <h4>Cargando...</h4> </td> </tr>
                              </table>
                            </div>
                          </div>                          
                          <!-- /.card-body -->
                          <button type="submit" style="display: none;" id="submit-form-horario-proyecto">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <a type="submit" class="btn bg-gradient-gray" id="bnt-exportar-horario" href="#"><i class="fa-regular fa-file-excel"></i> Exportar</a>
                        <button type="submit" class="btn btn-warning" id="btn-editar-horario" onclick="show_hide_form_horario(2);">Editar</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro_horario" style="display: none;">Guardar Cambios</button>
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
        <?php  require 'script.php';  ?>          
        
        <!-- moment locale -->
        <script src="../plugins/moment/locales.js"></script>

        <script type="text/javascript" src="scripts/asistencia_obrero.js?version_jdl=1.3"></script>

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>
        
      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
