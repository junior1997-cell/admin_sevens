<?php
  //Activamos el almacenamiento en el buffer
  ob_start();
  session_start();

  if (!isset($_SESSION["nombre"])){
    header("Location: index.php?file=".basename($_SERVER['PHP_SELF']));
  }else{ ?>
    <!DOCTYPE html>
    <html lang="es">
      <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Estado Financiero | Admin Sevens </title>

        <?php $title = "Estado Financiero"; require 'head.php'; ?>

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
            if ($_SESSION['estado_financiero']==1){
              //require 'endesarrollo.php';
              ?>           
              <!--Contenido-->
              <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <div class="content-header">
                  <div class="container-fluid">
                    <div class="row mb-2">
                      <div class="col-sm-6">
                        <h1 class="m-0 nombre-trabajador">Estado Financiero</h1>
                      </div>
                      <!-- /.col -->
                      <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                          <li class="breadcrumb-item"><a href="estado_financiero.php">Home</a></li>
                          <li class="breadcrumb-item active">Estado Financiero</li>
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

                            <!-- Editar -->
                            <h3 class="card-title mr-3 p-l-2px" id="btn-editar-ef" >
                              <button type="button" class="btn bg-gradient-orange btn-sm " onclick="show_hide_span_input_ef(2);"><i class="fas fa-pencil-alt"></i> <span class="d-none d-sm-inline-block">Editar</span> </button>
                            </h3>
                            <!-- Guardar -->
                            <h3 class="card-title mr-3 p-l-2px" id="btn-guardar-ef" style="display: none;">
                              <button type="button" class="btn bg-gradient-success btn-guardar-asistencia btn-sm " onclick="guardar_y_editar_estado_financiero();" ><i class="far fa-save"></i> <span class="d-none d-sm-inline-block"> Guardar </span> </button>
                            </h3>

                            <h3 class="card-title mr-3 p-l-2px">
                              <button type="button" class="btn bg-gradient-success btn-sm " onclick="html_table_to_excel('tabla_estado_financiero', 'xlsx', 'Estado Financiero Actual', 'detalle');"><i class="far fa-file-excel"></i> <span class="d-none d-sm-inline-block"> Exportar</span></button>
                            </h3>

                          </div>
                          <!-- /.card-header -->
                          <div class="card-body">
                            <div class="row">
                              <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                <!-- tabla principal --> 
                                <div class="table-responsive">                                                        
                                  <table  class="table table-bordered /*table-striped*/ table-hover text-nowrap" id="tabla_estado_financiero" >
                                    <thead class="bg-info">
                                      <tr> 
                                        <th class="py-1 text-center" colspan="3">ESTADO FINANCIERO ACTUAL</th>                                                 
                                      </tr>
                                      <tr> 
                                        <th class="py-1 text-center">#</th> 
                                        <th class="py-1">DESCRIPCIÓN</th>
                                        <th class="py-1 text-center">MONTO</th>                                                          
                                      </tr>
                                    </thead>
                                    <tbody>                         
                                      <tr>
                                        <td class="py-1 text-center">1</td>
                                        <td class="py-1">CAJA</td>
                                        <td class="py-1">
                                          <div class="formato-numero-conta span_ef"><span>S/</span> <span class="caja_ef"><i class="fas fa-spinner fa-pulse"></i></span> </div> 
                                          <input type="text" id="caja_ef" class="hidden input_ef w-100" onkeyup="delay(function(){update_interes_y_ganancia_ef()}, 200 );" autocomplete="off">
                                          <input type="hidden" id="idestado_financiero" >
                                        </td> 
                                      </tr>
                                      <tr>
                                        <td class="py-1 text-center">2</td>
                                        <td class="py-1">PRESTAMOS Y CRÉDITOS (por pagar)</td> 
                                        <td class="py-1">
                                          <div class="formato-numero-conta"><span>S/</span> <span class="prestamo_y_credito_ef"><i class="fas fa-spinner fa-pulse"></i></span> </div>
                                        </td> 
                                      </tr>
                                      <tr>
                                        <td class="py-1 text-center">3</td>
                                        <td class="py-1">GASTOS ACTUALIZADOS</td>              
                                        <td class="py-1">
                                          <div class="formato-numero-conta"><span>S/</span> <span class="gastos_actuales_ef"><i class="fas fa-spinner fa-pulse"></i></span> </div>
                                        </td> 
                                      </tr>
                                      <tr>
                                        <td class="py-1 text-center">4</td>
                                        <td class="py-1">VALORIZACIONES COBRADAS (<span class="cant_cobradas"><i class="fas fa-spinner fa-pulse"></i></span>)</td>      
                                        <td class="py-1">
                                          <div class="formato-numero-conta"><span>S/</span> <span class="valorizacion_cobrada_ef"><i class="fas fa-spinner fa-pulse"></i></span></div>
                                      </td> 
                                      </tr>
                                      <tr>
                                        <td class="py-1 text-center">5</td>
                                        <td class="py-1">VALORIZACIONES POR COBRAR (<span class="cant_por_cobrar"><i class="fas fa-spinner fa-pulse"></i></span>)</td>    
                                        <td class="py-1">
                                          <div class="formato-numero-conta"><span>S/</span> <span class="valorizacion_por_cobrar_ef"><i class="fas fa-spinner fa-pulse"></i></span> </div>
                                        </td> 
                                      </tr>
                                      <tr>
                                        <td class="py-1 text-center">6</td>
                                        <td class="py-1">GARANTÍA</td>                         
                                        <td class="py-1">
                                          <div class="formato-numero-conta"><span>S/</span> <span class="garantia_ef"><i class="fas fa-spinner fa-pulse"></i></span> </div>
                                        </td > 
                                      </tr>
                                      <tr>
                                        <td class="py-1 text-center">7</td>
                                        <td class="py-1">MONTO DE OBRA</td>                    
                                        <td class="py-1">
                                          <div class="formato-numero-conta"><span>S/</span> <span class="monto_de_obra_ef"><i class="fas fa-spinner fa-pulse"></i></span> </div>
                                        </td > 
                                      </tr>

                                    </tbody>
                                    <tfoot>
                                      <tr>                                       
                                        <th class="py-1 celda-b-t-2px" colspan="2">INTERÉS PAGADO</th>
                                        <th class="py-1 celda-b-t-2px"><div class="formato-numero-conta"><span>S/</span><span class="interes_pagado"><i class="fas fa-spinner fa-pulse"></i></span></div> </th>      
                                      </tr>
                                      <tr>                                       
                                        <th class="py-1" colspan="2" rowspan="2">GANANCIA ACTUAL (SIN DESCONTAR INTERÉS POR PAGAR)</th>
                                        <th class="py-1"><div class="formato-numero-conta"><span>S/</span><span class="ganacia_actual"><i class="fas fa-spinner fa-pulse"></i></span></div></th>      
                                      </tr>
                                      <tr>                                       
                                        <th class="py-1 text-right"><span class="ganacia_actual_porcentaje"><i class="fas fa-spinner fa-pulse"></i></span></th>      
                                      </tr>
                                    </tfoot>
                                  </table> 
                                  <!-- /.table -->
                                </div>   
                                <!-- /.table-responsive -->
                              </div>
                              <!-- /.col -->
                            </div>
                            <!-- /.row -->                            
                          </div>
                          <!-- /.card-body -->
                        </div>
                        <!-- /.card -->                      

                      </div>

                      <!-- /.col -->
                      <div class="col-12">
                        <h2>Proyecciones</h2>
                      </div>
                      <!-- /.col -->

                      <div class="col-12">
                        <div class="card card-primary card-outline">
                          <div class="card-header"> 

                            <!-- agregar pago  -->
                            <h3 class="card-title " id="btn-agregar" >
                              <button type="button" class="btn bg-gradient-success btn-sm" data-toggle="modal" data-target="#modal-agregar-proyecciones" onclick="limpiar_form_proyecciones();">
                              <i class="fas fa-plus-circle"></i> Agregar Proyecciones
                              </button>                     
                            </h3> 

                          </div>
                          <!-- /.card-header -->
                          <div class="card-body">
                            <div class="row">
                              <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4 mb-2">
                                <button type="button" class="btn bg-gradient-warning btn-sm btn-editar-p-1" onclick="show_hide_span_input_p(2,1);"><i class="fas fa-pencil-alt"></i> <span class="d-none d-sm-inline-block">Editar </span></button>
                                <button type="button" class="btn bg-gradient-success btn-sm btn-guardar-p-1 hidden" onclick="show_hide_span_input_p(1,1);"><i class="far fa-save"></i> <span class="d-none d-sm-inline-block"> Guardar</span></button>                                
                                <button type="button" class="btn bg-gradient-danger btn-sm" onclick="show_hide_span_input_p(2);"><i class="fas fa-skull-crossbones"></i> <span class="d-none d-sm-inline-block">Editar</span></button>
                                <button type="button" class="btn bg-gradient-gray btn-sm " onclick="html_table_to_excel('proyeccion-1', 'xlsx', 'detalle excel', 'hoja 1');"><i class="far fa-file-excel"></i> <span class="d-none d-sm-inline-block">Exportar</span></button>
                              </div>
                            </div>
                            <div class="table-responsive">                            
                              <!-- tabla principal -->                            
                              <table  class="table table-bordered /*table-striped*/ table-hover text-nowrap" id="proyeccion-1">
                                <thead >
                                  <tr class="bg-info">                                    
                                    <th class="py-1 text-center" colspan="3">ESTADO FINANCIERO - PROYECCIÓN AL</th> 
                                    <th class="py-1 text-center" >
                                      <span class="span_p_1">01/12/2022</span>  
                                      <input class="hidden input_p_1 w-100" type="date" id="" value="2022-12-01">
                                    </th>                                                
                                  </tr>
                                  <tr class="bg-info"> 
                                    <th class="py-1 text-left" colspan="4">
                                      <span class="span_p_1">PRESENTANDO LA VALORIZACIÓN 10, SIN RECIBIR EL PAGO</span> 
                                      <input class="hidden input_p_1 w-100" type="text"  id="" value="PRESENTANDO LA VALORIZACIÓN 10, SIN RECIBIR EL PAGO">
                                    </th>               
                                  </tr>
                                  <tr class="bg-info"> 
                                    <th class="py-1 text-center">#</th> 
                                    <th class="py-1">DESCRIPCIÓN</th>
                                    <th class="py-1">DETALLE</th>
                                    <th class="py-1 text-center">MONTO</th>                                                          
                                  </tr>
                                </thead>
                                <tbody>                         
                                  <tr>
                                    <td class="py-1 text-center">1</td>
                                    <td class="py-1">SEGUROS DE VIDA</td>
                                    <td class="py-1">
                                    </td>                           
                                    <td class="py-1">
                                      <div class="formato-numero-conta span_p_1">
                                        <span>S/</span> <span >100</span> 
                                      </div> 
                                      <input type="text" id="" class="hidden input_p_1 w-100" value="100">
                                    </td> 
                                  </tr>
                                  <!-- /.tr -->

                                  <tr class="detalle_tr_2" data-widget="expandable-table" aria-expanded="true" >
                                    <td class="py-1 text-center " onclick="delay(function(){show_hide_tr('.detalle_tr_2','.sub_detalle_tr_2')}, 200 );" > <i class="expandable-table-caret fas fa-caret-right fa-fw"></i>2 </td>
                                    <td class="py-1">DEVOLUCIÓN DE PRESTAMOS</td>
                                    <td class="py-1"> </td>                           
                                    <td class="py-1">
                                      <div class="formato-numero-conta ">
                                        <span>S/</span> <span >130,000.00</span>
                                      </div> 
                                    </td>                                     
                                  </tr>
                                  <!-- /.tr --> 

                                  <tr class="sub_detalle_tr_2">
                                    <td class="py-1 text-center"></td>
                                    <td class="py-1 text-right">DAVID REQUEJO </td>                                                            
                                    <td class="py-1">
                                      <div class="formato-numero-conta span_p_1">
                                        <span>S/</span>10,000.00
                                      </div> 
                                      <input type="text" id="" class="hidden input_p_1 w-100" value="10,000.00">
                                    </td> 
                                    <td class="py-1"> </td> 
                                  </tr>
                                  <!-- /.tr -->                                  

                                  <tr class="sub_detalle_tr_2">
                                    <td class="py-1 text-center"></td>
                                    <td class="py-1 text-right">DAVID REQUEJO </td>                                                            
                                    <td class="py-1">
                                      <div class="formato-numero-conta span_p_1">
                                        <span>S/</span>20,000.00
                                      </div> 
                                      <input type="text" id="" class="hidden input_p_1 w-100" value="20,000.00">
                                    </td> 
                                    <td class="py-1"> </td> 
                                  </tr>
                                  <!-- /.tr -->

                                  <tr class="sub_detalle_tr_2">
                                    <td class="py-1 text-center"></td>
                                    <td class="py-1 text-right">DAVID REQUEJO </td>                                                            
                                    <td class="py-1">
                                      <div class="formato-numero-conta span_p_1">
                                        <span>S/</span>30,000.00
                                      </div> 
                                      <input type="text" id="" class="hidden input_p_1 w-100" value="30,000.00">
                                    </td> 
                                    <td class="py-1"> </td> 
                                    <td class="py-1">
                                      <button type="button" class="btn bg-gradient-success btn-sm" ><i class="fas fa-plus"></i> </button>
                                    </td>
                                  </tr>
                                  <!-- /.tr -->                               

                                  <tr class="detalle_tr_3" data-widget="expandable-table" aria-expanded="true" onclick="delay(function(){show_hide_tr('.detalle_tr_3','.sub_detalle_tr_3')}, 200 );">
                                    <td class="py-1 text-center"><i class="expandable-table-caret fas fa-caret-right fa-fw"></i>3</td>
                                    <td class="py-1">COMPRAS</td>
                                    <td class="py-1"> </td>                           
                                    <td class="py-1">
                                      <div class="formato-numero-conta">
                                        <span>S/</span>500
                                      </div> 
                                    </td> 
                                  </tr>
                                  <!-- /.tr -->

                                  <tr class="sub_detalle_tr_3">
                                    <td class="py-1 text-center"></td>
                                    <td class="py-1 text-right">VIGA CERO</td>
                                    <td class="py-1">
                                      <div class="formato-numero-conta span_p_1">
                                        <span>S/</span>5,000.00
                                      </div> 
                                      <input type="text" id="" class="hidden input_p_1 w-100" value="5,000.00">
                                    </td>                           
                                    <td class="py-1"> </td> 
                                  </tr>
                                  <!-- /.tr -->

                                  <tr class="sub_detalle_tr_3">
                                    <td class="py-1 text-center"></td>
                                    <td class="py-1 text-right">MADERA</td>
                                    <td class="py-1">
                                      <div class="formato-numero-conta span_p_1">
                                        <span>S/</span>7,000.00
                                      </div> 
                                      <input type="text" id="" class="hidden input_p_1 w-100" value="7,000.00">
                                    </td>                           
                                    <td class="py-1"> </td> 
                                  </tr>
                                  <!-- /.tr -->

                                  <tr>
                                    <td class="py-1 text-center">4</td>
                                    <td class="py-1">MANO DE OBRA</td>
                                    <td class="py-1">
                                    </td>                           
                                    <td class="py-1">
                                      <div class="formato-numero-conta span_p_1">
                                        <span>S/</span>19,500.00
                                      </div> 
                                      <input type="text" id="" class="hidden input_p_1 w-100" value="19,500.00">
                                    </td> 
                                  </tr>
                                  <!-- /.tr -->

                                  <tr>
                                    <td class="py-1 text-center">5</td>
                                    <td class="py-1">DONACIONES</td>
                                    <td class="py-1"> </td>                           
                                    <td class="py-1">
                                      <div class="formato-numero-conta span_p_1">
                                        <span>S/</span>3,500.00
                                      </div> 
                                      <input type="text" id="" class="hidden input_p_1 w-100" value="3,500.00">
                                    </td> 
                                  </tr>
                                  <!-- /.tr -->
                                </tbody>
                                <tr>
                                  <td class="py-1 celda-b-y-2px "></td>
                                  <td class="py-1 celda-b-y-2px">GASTOS PROYECTADOS</td>
                                  <td class="py-1 celda-b-y-2px"> 01/12/2022</td>                           
                                  <td class="py-1 celda-b-y-2px">
                                    <div class="formato-numero-conta span_ef">
                                      <span>S/</span>50,000.0
                                    </div> 
                                    <input type="text" id="caja_ef" class="hidden input_ef_p w-100">
                                  </td> 
                                </tr>
                                <tbody>
                                  <tr><td class="py-1 text-center">1</td><td class="py-1">CAJA</td>                             <td class="py-1">01/12/2022</td><td class="py-1"><div class="formato-numero-conta span_ef"><span>S/</span>100</div> <input type="text" id="caja_ef" class="hidden input_ef w-100"></td> </tr>
                                  <tr><td class="py-1 text-center">2</td><td class="py-1">PRESTAMOS Y CRÉDITOS (por pagar)</td> <td class="py-1">01/12/2022</td><td class="py-1"><div class="formato-numero-conta"><span>S/</span>100</div></td> </tr>
                                  <tr><td class="py-1 text-center">3</td><td class="py-1">GASTOS ACTUALIZADOS</td>              <td class="py-1">01/12/2022</td><td class="py-1"><div class="formato-numero-conta"><span>S/</span>100</div></td> </tr>
                                  <tr><td class="py-1 text-center">4</td><td class="py-1">VALORIZACIONES COBRADAS (9)</td>      <td class="py-1">01/12/2022</td><td class="py-1"><div class="formato-numero-conta"><span>S/</span>100</div></td> </tr>
                                  <tr><td class="py-1 text-center">5</td><td class="py-1">VALORIZACIONES POR COBRAR (3)</td>    <td class="py-1">01/12/2022</td><td class="py-1"><div class="formato-numero-conta"><span>S/</span>100</div></td> </tr>
                                  <tr><td class="py-1 text-center">6</td><td class="py-1">GARANTÍA</td>                         <td class="py-1">01/12/2022</td><td class="py-1"><div class="formato-numero-conta"><span>S/</span>100</div></td > </tr>
                                  <tr><td class="py-1 text-center">7</td><td class="py-1">MONTO DE OBRA</td>                    <td class="py-1">01/12/2022</td><td class="py-1"><div class="formato-numero-conta"><span>S/</span>100</div></td > </tr>

                                </tbody>
                                <tfoot>
                                  <tr>                                       
                                    <th class="py-1 celda-b-t-2px" colspan="2">INTERÉS PAGADO</th>
                                    <th class="py-1 celda-b-t-2px" >01/12/2022</th>
                                    <th class="py-1 celda-b-t-2px"><div class="formato-numero-conta"><span>S/</span>100</div> </th>      
                                  </tr>
                                  <tr>                                       
                                    <th class="py-1" colspan="2" rowspan="2">GANANCIA ACTUAL (SIN DESCONTAR INTERÉS POR PAGAR)</th>
                                    <th class="py-1" rowspan="2">01/12/2022</th>
                                    <th class="py-1"><div class="formato-numero-conta"><span>S/</span>100</div></th>      
                                  </tr>
                                  <tr>                                       
                                    <th class="py-1 text-right">6%</th>      
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

                  <!-- Modal agregar PAGOS POR MES -->
                  <div class="modal fade" id="modal-agregar-proyecciones">
                    <div class="modal-dialog modal-dialog-scrollable modal-md">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Agregar Proyecciones</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        
                        <div class="modal-body">
                          <!-- form start -->
                          <form id="form-proyecciones" name="form-proyecciones"  method="POST" >                      
                            <div class="card-body">
                              <div class="row" id="cargando-1-fomulario">

                                <!-- id proyecccion -->
                                <input type="hidden" name="idproyeccion_p" id="idproyeccion_p" /> 
                                <!-- id proyecto -->
                                <input type="hidden" name="idproyecto_p" id="idproyecto_p" />                   

                                <!-- fecha de proyeccion-->
                                <div class="col-lg-6">
                                  <div class="form-group">
                                    <label for="fecha_p">Proyección al <sup class="text-danger">*</sup></label>
                                    <div class="input-group date "  data-target-input="nearest">
                                      <input type="text" class="form-control" id="fecha_p" name="fecha_p" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask   />
                                      <div class="input-group-append click-btn-fecha-p cursor-pointer" for="fecha_p" >
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                      </div>
                                    </div>                                 
                                  </div>
                                </div>

                                <!-- caja-->
                                <div class="col-lg-6">
                                  <div class="form-group">
                                    <label for="caja_p">Caja</label>
                                    <input type="number" class="form-control" name="caja_p" id="caja_p"  placeholder="Caja" />
                                  </div>
                                </div>
                                
                                <!-- Descripcion-->
                                <div class="col-lg-12">
                                  <div class="form-group">
                                    <label for="descripcion_p">Descripción </label> <br>
                                    <textarea name="descripcion_p" id="descripcion_p" class="form-control" rows="2"></textarea>
                                  </div>                                                        
                                </div>

                                <!-- barprogress -->
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                                  <div class="progress" id="barra_progress_proyeccion_div">
                                    <div id="barra_progress_proyeccion" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
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
                              
                            </div>
                            <!-- /.card-body -->                      
                            <button type="submit" style="display: none;" id="submit-form-proyecciones">Submit</button>                      
                          </form>
                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-success" id="guardar_registro_proyecciones">Guardar Cambios</button>
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

        <?php require 'script.php'; ?>     

        <script type="text/javascript" src="../plugins/xlsx/xlsx.full.min.js"></script>

        <script type="text/javascript" src="scripts/estado_financiero.js"></script>
         
        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>

        <?php require 'extra_script.php'; ?>
        
        
      </body>
    </html>
    <?php    
  }
  ob_end_flush();
?>
