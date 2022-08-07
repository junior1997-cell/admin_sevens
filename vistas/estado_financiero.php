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

                          </div>
                          <!-- /.card-header -->
                          <div class="card-body">
                            <div class="row">
                              <div class="col-6">
                                <!-- tabla principal -->                            
                                <table  class="table table-bordered /*table-striped*/ table-hover text-nowrap" >
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
                                    <tr><td class="py-1 text-center">1</td><td class="py-1">CAJA</td>                             <td class="py-1"><div class="formato-numero-conta span_ef"><span>S/</span>100</div> <input type="text" id="caja_ef" class="hidden input_ef w-100"></td> </tr>
                                    <tr><td class="py-1 text-center">2</td><td class="py-1">PRESTAMOS Y CRÉDITOS (por pagar)</td> <td class="py-1"><div class="formato-numero-conta"><span>S/</span>100</div></td> </tr>
                                    <tr><td class="py-1 text-center">3</td><td class="py-1">GASTOS ACTUALIZADOS</td>              <td class="py-1"><div class="formato-numero-conta"><span>S/</span>100</div></td> </tr>
                                    <tr><td class="py-1 text-center">4</td><td class="py-1">VALORIZACIONES COBRADAS (9)</td>      <td class="py-1"><div class="formato-numero-conta"><span>S/</span>100</div></td> </tr>
                                    <tr><td class="py-1 text-center">5</td><td class="py-1">VALORIZACIONES POR COBRAR (3)</td>    <td class="py-1"><div class="formato-numero-conta"><span>S/</span>100</div></td> </tr>
                                    <tr><td class="py-1 text-center">6</td><td class="py-1">GARANTÍA</td>                         <td class="py-1"><div class="formato-numero-conta"><span>S/</span>100</div></td > </tr>
                                    <tr><td class="py-1 text-center">7</td><td class="py-1">MONTO DE OBRA</td>                    <td class="py-1"><div class="formato-numero-conta"><span>S/</span>100</div></td > </tr>

                                  </tbody>
                                  <tfoot>
                                    <tr>                                       
                                      <th class="py-1 celda-b-t-2px" colspan="2">INTERÉS PAGADO</th>
                                      <th class="py-1 celda-b-t-2px"><div class="formato-numero-conta"><span>S/</span>100</div> </th>      
                                    </tr>
                                    <tr>                                       
                                      <th class="py-1" colspan="2" rowspan="2">GANANCIA ACTUAL (SIN DESCONTAR INTERÉS POR PAGAR)</th>
                                      <th class="py-1"><div class="formato-numero-conta"><span>S/</span>100</div></th>      
                                    </tr>
                                    <tr>                                       
                                      <th class="py-1 text-right">7%</th>      
                                    </tr>
                                  </tfoot>
                                </table> 
                                <!-- /.table -->
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
                            <!-- tabla principal -->                            
                            <table  class="table table-bordered /*table-striped*/ table-hover text-nowrap" >
                              <thead class="bg-info">
                                <tr> 
                                  <th class="py-1 text-center" colspan="3">ESTADO FINANCIERO - PROYECCIÓN AL</th> 
                                  <th class="py-1 text-center" >01/12/2022</th>                                                
                                </tr>
                                <tr> 
                                  <th class="py-1 text-center" colspan="4">PRESENTANDO LA VALORIZACIÓN 10, SIN RECIBIR EL PAGO</th>               
                                </tr>
                                <tr> 
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
                                      <div class="formato-numero-conta span_ef">
                                      <span>S/</span>100
                                    </div> 
                                    <input type="text" id="caja_ef" class="hidden input_ef_p w-100">
                                  </td> 
                                </tr>
                                <!-- /.tr -->

                                <tr>
                                  <td class="py-1 text-center">2</td>
                                  <td class="py-1">DEVOLUCIÓN DE PRESTAMOS</td>
                                  <td class="py-1"> </td>                           
                                  <td class="py-1">
                                    <div class="formato-numero-conta span_ef">
                                      <span>S/</span>130,000.00
                                    </div> 
                                    <input type="text" id="caja_ef" class="hidden input_ef_p w-100">
                                  </td> 
                                </tr>
                                <!-- /.tr -->

                                <tr>
                                  <td class="py-1 text-center"></td>
                                  <td class="py-1 text-right">DAVID REQUEJO </td>                                                            
                                  <td class="py-1">
                                    <div class="formato-numero-conta span_ef">
                                      <span>S/</span>10,000.00
                                    </div> 
                                    <input type="text" id="caja_ef" class="hidden input_ef_p w-100">
                                  </td> 
                                  <td class="py-1"> </td> 
                                </tr>
                                <!-- /.tr -->
                                <tr>
                                  <td class="py-1 text-center"></td>
                                  <td class="py-1 text-right">DAVID REQUEJO</td>                                                            
                                  <td class="py-1">
                                    <div class="formato-numero-conta span_ef">
                                      <span>S/</span>20,000.00
                                    </div> 
                                    <input type="text" id="caja_ef" class="hidden input_ef_p w-100">
                                  </td> 
                                  <td class="py-1"> </td> 
                                </tr>
                                <!-- /.tr -->
                                <tr>
                                  <td class="py-1 text-center"></td>
                                  <td class="py-1 text-right">DAVID REQUEJO</td>                                                            
                                  <td class="py-1">
                                    <div class="formato-numero-conta span_ef">
                                      <span>S/</span>100,000.00
                                    </div> 
                                    <input type="text" id="caja_ef" class="hidden input_ef_p w-100">
                                  </td> 
                                  <td class="py-1"> </td> 
                                </tr>
                                <!-- /.tr -->

                                <tr>
                                  <td class="py-1 text-center">3</td>
                                  <td class="py-1">COMPRAS</td>
                                  <td class="py-1"> </td>                           
                                  <td class="py-1">
                                    <div class="formato-numero-conta span_ef">
                                      <span>S/</span>100
                                    </div> 
                                    <input type="text" id="caja_ef" class="hidden input_ef_p w-100">
                                  </td> 
                                </tr>
                                <!-- /.tr -->

                                <tr>
                                  <td class="py-1 text-center"></td>
                                  <td class="py-1 text-right">VIGA CERO</td>
                                  <td class="py-1">
                                    <div class="formato-numero-conta span_ef_p">
                                      <span>S/</span>100
                                    </div> 
                                    <input type="text" id="caja_ef" class="hidden input_ef_p w-100">
                                  </td>                           
                                  <td class="py-1"> </td> 
                                </tr>
                                <!-- /.tr -->

                                <tr>
                                  <td class="py-1 text-center"></td>
                                  <td class="py-1 text-right">MADERA</td>
                                  <td class="py-1">
                                    <div class="formato-numero-conta span_ef_p">
                                      <span>S/</span>100
                                    </div> 
                                    <input type="text" id="caja_ef" class="hidden input_ef_p w-100">
                                  </td>                           
                                  <td class="py-1"> </td> 
                                </tr>
                                <!-- /.tr -->

                                <tr>
                                  <td class="py-1 text-center">4</td>
                                  <td class="py-1">MANO DE OBRA</td>
                                  <td class="py-1">
                                      <div class="formato-numero-conta span_ef_p">
                                      <span>S/</span>100
                                    </div> 
                                    <input type="text" id="caja_ef" class="hidden input_ef_p w-100">
                                  </td>                           
                                  <td class="py-1">
                                      <div class="formato-numero-conta span_ef">
                                      <span>S/</span>100
                                    </div> 
                                    <input type="text" id="caja_ef" class="hidden input_ef_p w-100">
                                  </td> 
                                </tr>
                                <!-- /.tr -->

                                <tr>
                                  <td class="py-1 text-center">5</td>
                                  <td class="py-1">DONACIONES</td>
                                  <td class="py-1"> </td>                           
                                  <td class="py-1">
                                    <div class="formato-numero-conta span_ef">
                                      <span>S/</span>100
                                    </div> 
                                    <input type="text" id="caja_ef" class="hidden input_ef_p w-100">
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

                                <!-- id idpagos_x_mes_administrador -->
                                <input type="hidden" name="idpagos_x_mes_administrador" id="idpagos_x_mes_administrador" />
                                <!-- id idfechas_mes_pagos_administrador -->
                                <input type="hidden" name="idfechas_mes_pagos_administrador_pxm" id="idfechas_mes_pagos_administrador_pxm" />                   

                                <!-- fecha de proyeccion-->
                                <div class="col-lg-12">
                                  <div class="form-group">
                                    <label for="">Fecha Inicio de actividades: <sup class="text-danger">*</sup></label>
                                    <div class="input-group date "  data-target-input="nearest">
                                      <input type="text" class="form-control" id="fecha_proyeccion" name="fecha_proyeccion" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask   />
                                      <div class="input-group-append click-btn-fecha-proyeccion cursor-pointer" for="fecha_proyeccion" >
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                      </div>
                                    </div>                                 
                                  </div>
                                </div>
                                
                                <!-- Descripcion-->
                                <div class="col-lg-12">
                                  <div class="form-group">
                                    <label for="descripcion">Descripción </label> <br>
                                    <textarea name="descripcion" id="descripcion" class="form-control" rows="2"></textarea>
                                  </div>                                                        
                                </div>

                                <!-- barprogress -->
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                                  <div class="progress" id="barra_progress_div">
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

        <script type="text/javascript" src="scripts/estado_financiero.js"></script>
         
        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>

        <?php require 'extra_script.php'; ?>
        
      </body>
    </html>
    <?php    
  }
  ob_end_flush();
?>
