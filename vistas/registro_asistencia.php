<?php
  //Activamos el almacenamiento en el buffer
  ob_start();

  session_start();
  if (!isset($_SESSION["nombre"])){
    header("Location: index.php");
  }else{
    ?>
     
    <!DOCTYPE html>
    <html lang="es">
      <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Admin Sevens | asistencia</title>
        <?php require 'head.php'; ?>
      </head>
      <body class="hold-transition sidebar-collapse sidebar-mini layout-fixed layout-navbar-fixed">
        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper">
          <?php  
          require 'nav.php'; 
          require 'aside.php'; 

          if ($_SESSION['asistencia_trabajador']==1){  
            ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
              <!-- Content Header (Page header) -->
              <section class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1>Asistencia 
                        <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-fechas-actividades" onclick="limpiar_form_fechas_actividades();">
                        <i class="far fa-calendar-alt"></i> Agregar Fechas de Actividades
                        </button>
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
                          <h3 class="card-title" id="card-registrar">
                            <!-- <button type="button" class="btn bg-gradient-success"  data-toggle="modal" data-target="#modal-agregar-asistencia" onclick="limpiar();" style="margin-right: 10px; height: 61px;"><i class="fas fa-user-plus"></i> Agregar </button> -->
                          </h3>
                          <!-- regresar -->
                          <h3 class="card-title mr-3" id="card-regresar" style="display: none; padding-left: 2px;">
                            <button type="button" class="btn bg-gradient-warning" onclick="mostrar_form_table(1);despintar_btn_select();" style="height: 61px;"><i class="fas fa-arrow-left"></i> <span class="d-none d-sm-inline-block">Regresar</span> </button>
                          </h3>
                          <!-- Editar -->
                          <h3 class="card-title mr-3" id="card-editar" style="display: none; padding-left: 2px;">
                            <button type="button" class="btn bg-gradient-orange" onclick="editar_fechas_asistencia(2);" style="height: 61px;"><i class="fas fa-pencil-alt"></i> <span class="d-none d-sm-inline-block">Editar</span> </button>
                          </h3>
                          <!-- Guardar -->
                          <h3 class="card-title mr-3" id="card-guardar" style="display: none; padding-left: 2px;">
                            <button type="button" class="btn bg-gradient-success" onclick="guardar_fechas_asistencia();" style="margin-right: 10px; height: 61px;"><i class="far fa-save"></i> <span class="d-none d-sm-inline-block"> Guardar </span> </button>
                          </h3>
                          <!-- Botones de quincenas -->
                          <div id="lista_quincenas" class="row-horizon disenio-scroll" >
                            <!-- <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-asistencia" onclick="limpiar();"><i class="fas fa-user-plus"></i> Agregar </button>
                            <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-asistencia" onclick="limpiar();"><i class="fas fa-user-plus"></i> Agregar </button>-->
                          </div>                        
                        </div>
                        <!-- /.card-header -->

                        <div class="card-body" >

                          <!-- tabla principal -->
                          <div id="tabla-asistencia-trab" >
                            <table id="tabla-asistencia" class="table table-bordered table-striped display " style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th>#</th>
                                  <th class="text-center" data-toggle="tooltip" data-original-title="Opciones.">Op.</th>
                                  <th>Nombre</th>
                                  <th class="text-center">Total Horas</th>
                                  <th class="text-center">Total Días</th>                                
                                  <th class="text-center">Sueldo hora</th>
                                  <th class="text-center">Sueldo diario</th>
                                  <th class="text-center">Sueldo mensual</th>                                
                                  <th class="text-center">Sabatical</th>
                                  <th class="text-center">Pago acumulado</th>
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
                                  <th class="text-gray">Sabatical</th>
                                  <th class="text-nowrap text-right"> <h5 id="total_acumulado_trabjadores">  S/. <i class="fas fa-spinner fa-pulse fa-sm"></i><br><br></h5></th>
                                </tr>
                              </tfoot>
                            </table>
                          </div>

                          <!--registro-asistencia-->                         
                          <div class="table-responsive disenio-scroll" id="ver_asistencia" style="display: none;">
                              
                            <table class="table table-hover text-nowrap styletabla" style="border: black 1px solid;">
                              <thead>
                                <tr>
                                  <th rowspan="2" class="stile-celda bg-color-acc3c775">#</th>
                                  <th rowspan="2" class="stile-celda bg-color-acc3c775"><i class="far fa-clock"></i></th>
                                  <th rowspan="2" class="stile-celda bg-color-acc3c775">Nombre del trabajador</th>
                                  <th rowspan="2" class="stile-celda bg-color-acc3c775">Cargo</th>
                                  <th colspan="14" id="dias_asistidos_s_q" class="text-center bg-color-acc3c775" style="border: black 1px solid;">
                                    <span class="badge badge-info float-left cursor-pointer" data-toggle="tooltip" data-original-title="Horas multiples" onclick="modal_horas_multiples();"><i class="far fa-clock fa-lg m-1"></i></span>  
                                    Horas de trabajo por día
                                  </th>
                                  <th rowspan="2" class="stile-celda bg-color-acc3c775">Horas<br>normal/extras</th>
                                  <th rowspan="2" class="stile-celda bg-color-acc3c775">Días<br>asistidos</th>
                                  <th rowspan="2" class="stile-celda bg-color-acc3c775">Sueldo Mensual</th>
                                  <th rowspan="2" class="stile-celda bg-color-acc3c775">Jornal</th>
                                  <th rowspan="2" class="stile-celda bg-color-acc3c775">Sueldo <br> hora</th>
                                  <th rowspan="2" class="stile-celda bg-color-acc3c775">Sabatical</th>
                                  <th rowspan="2" class="stile-celda bg-color-acc3c775">Pago <br> parcial</th>
                                  <th rowspan="2" class="stile-celda bg-color-acc3c775">Adicional <br> descuento</th>
                                  <th rowspan="2" class="stile-celda head_pago_q_s bg-color-acc3c775">Pago quincenal</th> 
                                  <th rowspan="2" class="stile-celda bg-color-acc3c775"><i class="fas fa-hand-holding-usd fa-2x"></i></th>
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

                          <!-- cargando tabla registro de asistencias -->
                          <div class="row" id="cargando-registro-asistencia" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                              <br />
                              <h4>Cargando...</h4>
                            </div>
                          </div>

                          <!-- Asistencia por Trabajdor -->
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
                                  <th>Horas Normal</th>
                                  <th>Pago Hr. Normal</th>
                                  <th>Hora Extras</th>
                                  <th>Pago Hr. Extras</th>
                                  <th>Fecha Asistencia</th>
                                  <th>Estado</th>
                                </tr>
                              </tfoot>
                            </table>
                          </div>

                          <!-- TBLA - QUINCENA SEMANA por Trabajdor -->
                          <div id="detalle_qs" style="display: none;">
                            <table id="tabla-detalle-qs-individual" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th class="">Op.</th>
                                  <th class="thead_num">Num.</th>
                                  <th class="thead_fecha">Fechas</th>
                                  <th>Pago Hr. Normal/Extra</th>
                                  <th>Adicional</th>
                                  <th>Sabatical</th>
                                  <th class="thead_pago">Pago</th>
                                  <th>Contador</th>
                                  <th>Estado</th>
                                </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                <tr>
                                  <th class="text-gray-50">Op.</th>
                                  <th class="text-gray-50 thead_num">Num.</th>
                                  <th class="text-gray-50 thead_fecha">Fechas</th>
                                  <th class="text-gray-50">Pago Hr. Normal/Extra</th>
                                  <th class="text-gray-50">Adicional</th>
                                  <th class="text-gray-50">Sabatical</th>
                                  <th class="text-nowrap text-right h5" id="suma_qs_individual">S/. <i class="fas fa-spinner fa-pulse fa-sm"></i></th>
                                  <th class="text-gray-50">Contador</th>
                                  <th class="text-gray-50">Estado</th>
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
                <!-- Modal agregar asistencia - :::::::::::::::::::::::::::::::::: NO SE SE USA -->
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

                <!-- Modal editar asistencia - :::::::::::::::::::::::::::::::::::: NO SE SE USA -->
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

                <!-- Modal justificar asistencia -->
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
                                  <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(1, 'justificacion');">
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
                
                <!-- Modal adicinoal / descuento -->
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
                            <input type="hidden" name="fecha_q_s" id="fecha_q_s" />                                         
                            
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

                <!-- Modal cargando -->
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
                        <div class="swal2-actions">
                          <div class="swal2-loader"></div>
                          <button onclick="cerrar_modal_cargando()" type="button" class="swal2-confirm swal2-styled" aria-label="" style="display: inline-block;">OK</button>                         
                        </div>
                      </div>                     
                    </div>
                  </div>
                </div>

                <!-- Modal Fechas de Actividades -->
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

                            <!-- FECHA INICIO DE ACTIVIDADES -->
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label>Fecha Inicio de actividades: <sup class="text-danger">*</sup></label>
                                <div class="input-group date"  data-target-input="nearest">
                                  <input type="text" class="form-control datetimepicker-input" data-target="#fecha_inicio_actividad" id="fecha_inicio_actividad" name="fecha_inicio_actividad" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask onchange="calcular_plazo_actividad();"  />
                                  <div class="input-group-append" data-target="#fecha_inicio_actividad" data-toggle="datetimepicker">
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
                                  <input type="text" class="form-control datetimepicker-input" data-target="#fecha_fin_actividad" id="fecha_fin_actividad" name="fecha_fin_actividad" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask onchange="calcular_plazo_actividad();" />
                                  <div class="input-group-append" data-target="#fecha_fin_actividad" data-toggle="datetimepicker">
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
                
                <!-- Modal Horas Multiples -->
                <div class="modal fade" id="modal-agregar-horas-multiples">
                  <div class="modal-dialog /*modal-dialog-scrollable*/ modal-md">
                    <div class="modal-content">

                      <div class="modal-header">
                        <h4 class="modal-title"><i class="far fa-clock fa-lg m-1"></i> Asignar horas multiples</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body"> 
                        <form id="form-horas-multiples" name="form-horas-multiples" method="POST">
                          <div class="row">
                          
                            <!-- Horas -->
                            <div class="col-lg-12"> 
                              <div class="form-group">
                                <label for="horas_multiples">Horas<sup class="text-danger">*</sup> <small class="text-danger">(para todos los trabajadores)</small> </label>
                                <input class="form-control" type="number" name="horas_multiples" id="horas_multiples" >
                              </div>
                            </div>

                          </div>
                          <button type="submit" style="display: none;" id="submit-form-horas-multiples">Submit</button>
                        </form>
                      </div>

                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button  class="btn btn-success horas-multiples" >Asignar horas</button>
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
          } require 'footer.php'; 
          ?>
        </div>

        <!-- /.content-wrapper -->
        <?php  require 'script.php';  ?>

        <style>
          .class-style label {
            font-size: 14px;
          }
          .class-style small {
            background-color: #f4f7ee;
            border: solid 1px #ce542a21;
            margin-left: 3px;
            padding: 5px;
            border-radius: 6px;
          }
          .fixed-celda{
            background-color: #fff;
            border-bottom: 0;
            box-shadow: inset 0 1px 0 #dee2e6, inset 0 -1px 0 #dee2e6;
            position: -webkit-sticky;
            position: sticky;
            top: 0;
            z-index: 10;
          }
        </style>
        <!-- Bootstrap 4 -->
        <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- jquery-validation -->
        <script src="../plugins/jquery-validation/jquery.validate.min.js"></script>
        <script src="../plugins/jquery-validation/additional-methods.min.js"></script>
        <!-- InputMask -->
        <!-- <script src="../plugins/moment/moment.min.js"></script> -->
        <script src="../plugins/inputmask/jquery.inputmask.min.js"></script>
        <!-- sweetalert2 -->
        <script src="../plugins/sweetalert2/sweetalert2.all.min.js"></script>
        <!-- moment locale -->
        <script src="../plugins/moment/locales.js"></script>

        <script type="text/javascript" src="scripts/registro_asistencia.js"></script>

        <script>
          $(function () {
            $('[data-toggle="tooltip"]').tooltip();
          });
        </script>
        <script>
          if (localStorage.getItem("nube_idproyecto")) {
            console.log("icon_folder_" + localStorage.getItem("nube_idproyecto"));

            $("#ver-proyecto").html('<i class="fas fa-tools"></i> Proyecto: ' + localStorage.getItem("nube_nombre_proyecto"));

            $(".ver-otros-modulos-1").show();

            // $('#icon_folder_'+localStorage.getItem('nube_idproyecto')).html('<i class="fas fa-folder-open"></i>');
          } else {
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
