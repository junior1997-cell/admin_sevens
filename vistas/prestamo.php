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
        <title>Admin Sevens | Prestamos</title>

        <?php $title = "Prestamos"; require 'head.php'; ?>
        
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
            if ($_SESSION['prestamo']==1){
              // require 'endesarrollo.php';
              ?>           
              <!--Contenido-->
              <div class="content-wrapper" >
                <!-- Content Header (Page header) -->
                <div class="content-header">
                  <div class="container-fluid">
                    <div class="row mb-2">
                      <div class="col-sm-6">
                        <h1 class="m-0 nombre-trabajador">Préstamos y créditos</h1>
                      </div>
                      <!-- /.col -->
                      <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                          <li class="breadcrumb-item"><a href="prestamo.php">Home</a></li>
                          <li class="breadcrumb-item active">Préstamos y créditos</li>
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

                        <!-- CARD - Préstamo --------------------------------------------- -->
                        <div class="card card-primary cur_point card-outline collapsed-card">
                          <div class="card-header">
                            <h3 class="card-title">Tabla: <b>Préstamo</b></h3>
                            <div class="card-tools">
                              <button type="button" class="btn btn-default float-right" data-card-widget="collapse" >
                                <i class="fas fa-plus"></i>
                              </button>
                            </div>
                          </div>
                          <div class="card-body pb-1 pt-2">
                            <!-- agregar pago  -->
                            <h3 class="card-title "  >
                              <button type="button" class="btn bg-gradient-success btn-sm" id="btn-agregar" data-toggle="modal" data-target="#modal-agregar-prestamo" onclick="limpiar_prestamos();"><i class="fas fa-plus-circle"></i> Agregar Prestamo </button>  

                              <button type="button" class="btn bg-gradient-warning" id="btn-regresar" style="display: none;" onclick="table_show_hide(1);"><i class="fas fa-arrow-left"></i> Regresar</button>
                              <button type="button" class="btn bg-gradient-success" id="btn-pagar" data-toggle="modal" style="display: none;" data-target="#modal-agregar-pagar-prestamo" onclick="limpiar_form_pago_prestamo();"> <i class="fas fa-dollar-sign"></i> Agregar Pago </button>                 
                            </h3> 
                          </div>
                          <!-- /.card-header -->
                          
                          <div class="card-body row-horizon disenio-scroll">

                            <!-- tabla prestamos -->
                            <div id="div-tabla-prestamos">
                              <table id="tbla-prestamos" class="table table-bordered table-striped display" style="width: 100% !important;">
                                <thead>
                                  <tr>
                                    <th class="">#</th>
                                    <th class="">OP</th>
                                    <th class="">Entidad</th>
                                    <th class="">Descripción</th>
                                    <th class="">Monto de préstamos</th>
                                    <th class="">Pagos de Préstamos</th>
                                    <th>Deuda</th>
                                    <th>Fecha Inicio</th> 
                                    <th>Feha Final</th>                            
                                  </tr>
                                </thead>
                                <tbody>                         
                                  <!-- aqui la va el detalle de la tabla -->
                                </tbody>
                                <tfoot>
                                  <tr>
                                    <th class="">#</th>
                                    <th class="">OP</th>
                                    <th class="">Entidad</th>
                                    <th class="">Descripción</th>
                                    <th class="text-nowrap text-right suma_total_de_monto_prestamo h5">S/ <i class="fas fa-spinner fa-pulse fa-sm"></i></th>                               
                                    <th class="text-nowrap text-right suma_total_de_paagos_prestamos h5">S/ <i class="fas fa-spinner fa-pulse fa-sm"></i></th>                               
                                    <th class="text-nowrap text-right suma_total_de_deudas_prestamos h5">S/ <i class="fas fa-spinner fa-pulse fa-sm"></i></th>  
                                    <th>Fecha Inicio</th> 
                                    <th>Feha Final</th>                         
                                  </tr>
                                </tfoot>
                              </table>
                            </div>

                            <!-- Tabla pagos prestamos -->
                            <div id="div-tabla-pagos-prestamos" style="display: none;">
                              <div class="row m-1 bg-color-0202022e p-5px">
                                <div class="col-4"> <h5 > <spam>Entidad: </spam> <b class="entidad"></b></h5></div>
                                <div class="col-4"> <h5 ><spam>total Emprestamo: </spam> <b class="total_empres"></b>  </h5></div>
                                <div class="col-4"> <h5 > <span class="estado_saldo">Deuda: </span> <b class="total_deuda"></b> </h5></div>
                              </div>
                              
                              <table id="tbla-pago-prestamos" class="table table-bordered table-striped display" style="width: 100% !important;">
                                <thead>
                                  <tr>
                                  <th class="">#</th>
                                    <th class="">Opciones</th>
                                    <th class="">Descripción</th>
                                    <th class="">Monto</th>
                                    <th class="">Fecha</th>
                                    <th>Comprobante</th>                            
                                  </tr>
                                </thead>
                                <tbody>                         
                                  <!-- aqui la va el detalle de la tabla -->
                                </tbody>
                                <tfoot>
                                  <tr>
                                    <th class="">#</th>
                                    <th class="">Opciones</th>
                                    <th class="">Descripción</th>
                                    <th class="text-nowrap text-right suma_total_pago_prestamo h5">S/ <i class="fas fa-spinner fa-pulse fa-sm"></i></th>                               
                                    <th class="">Fecha</th>
                                    <th>Comprobante</th>                         
                                  </tr>
                                </tfoot>
                              </table>
                            </div>

                          </div>
                         <!-- /.card-body -->
                        </div>

                        <!-- CARD - Créditos --------------------------------------------- -->
                        <div class="card card-primary cur_point card-outline collapsed-card" data-card-widget="collapse">
                          <div class="card-header">
                            <h3 class="card-title">Tabla: <b>Créditos</b></h3>
                            <div class="card-tools">
                              <button type="button" class="btn btn-default float-right" data-card-widget="collapse" >
                                <i class="fas fa-plus"></i>
                              </button>
                            </div>
                          </div>
                          <div class="card-body pb-1 pt-2">
                            <!-- agregar pago  -->
                            <h3 class="card-title " id="btn-agregar" >
                              <button type="button" class="btn bg-gradient-success btn-sm" data-toggle="modal" data-target="#modal-agregar-pago-trabajdor" onclick="limpiar_pago_x_mes();">
                              <i class="fas fa-plus-circle"></i> Agregar Créditos 
                              </button>                     
                            </h3> 
                          </div>
                          <!-- /.card-header -->
                          <div class="card-body row-horizon disenio-scroll">
                            <table id="tbla-resumen-maquinaria" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">Op</th>
                                  <th class="">Producto</th>
                                  <th class="">Color</th>
                                  <th class="">Marca</th>
                                  <th data-toggle="tooltip" data-original-title="Unidad de Medida">UM</th>
                                  <th>Cantidad</th>
                                  <th>Compra</th> 
                                  <th>Precio promedio</th> 
                                  <th>Precio actual</th>    
                                  <th>Suma Total</th>                      
                                </tr>
                              </thead>
                              <tbody>                         
                                <!-- aqui la va el detalle de la tabla -->
                              </tbody>
                              <tfoot>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">Op</th>
                                  <th class="">Producto</th>
                                  <th class="">Color</th>
                                  <th class="">Marca</th>
                                  <th>UM</th>
                                  <th class="text-center suma_total_productos_m h5"><i class="fas fa-spinner fa-pulse fa-sm"></i></th> 
                                  <th>Compra</th>
                                  <th>Precio promedio</th>
                                  <th>Precio actual</th>   
                                  <th class="text-nowrap text-right suma_total_de_compras_m h5">S/ <i class="fas fa-spinner fa-pulse fa-sm"></i></th>                               
                                </tr>
                              </tfoot>
                            </table>
                          </div>
                          <!-- /.card-body -->
                        </div>

                      </div>
                      <!-- /.col -->
                    </div>
                    <!-- /.row -->
                  </div>
                  <!-- /.container-fluid -->             

                  <!-- Modal agregar prestamos -->
                  <div class="modal fade" id="modal-agregar-prestamo">
                    <div class="modal-dialog modal-dialog-scrollable modal-md">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4>Agregar Préstamo</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        
                        <div class="modal-body">
                          <!-- form start -->
                          <form id="form-prestamo" name="form-prestamo"  method="POST" >                      
                            <div class="card-body">
                              <div class="row" id="cargando-1-fomulario">

                                <!-- id prestamo -->
                                <input type="hidden" name="idprestamo" id="idprestamo" />   

                                <!-- id proyecto_prestamo -->
                                <input type="hidden" name="id_proyecto_prestamo" id="id_proyecto_prestamo" />                 

                                <!-- Entidad -->
                                <div class="col-lg-12">
                                  <div class="form-group">
                                  <label for="entidad">Entidad</label>
                                  <input type="text" name="entidad_prestamo" id="entidad_prestamo" class="form-control"  placeholder="Entidad">  
                                  </div>
                                </div>
                                <!-- Fecha Inicio -->
                                <div class="col-lg-6">
                                  <div class="form-group">
                                  <label for="fecha_inicio">Fecha Inicio</label>
                                  <input type="date" name="fecha_inicio_prestamo" id="fecha_inicio_prestamo" class="form-control"  placeholder="Fecha Inicio">  
                                  </div>
                                </div>
                                <!-- Fecha fin -->
                                <div class="col-lg-6">
                                  <div class="form-group">
                                  <label for="fecha_fin">Fecha Fin</label>
                                  <input type="date" name="fecha_fin_prestamo" id="fecha_fin_prestamo" class="form-control"  placeholder="Fecha Fin">  
                                  </div>
                                </div>

                                <!-- Monto prestamo -->
                                <div class="col-lg-12">
                                  <div class="form-group">
                                    <label for="monto_prestamo">Monto Préstamo</label>                               
                                    <input type="number" name="monto_prestamo" id="monto_prestamo" class="form-control"  placeholder="Monto prestamo">  
                                  </div>                                                        
                                </div>
                                
                                <!-- Descripcion-->
                                <div class="col-lg-12">
                                  <div class="form-group">
                                    <label for="descripcion_prestamo">Descripción </label> <br>
                                    <textarea name="descripcion_prestamo" id="descripcion_prestamo" class="form-control" rows="2"></textarea>
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
                              
                            </div>
                            <!-- /.card-body -->                      
                            <button type="submit" style="display: none;" id="submit-form-prestamo">Submit</button>                      
                          </form>
                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-success" id="guardar_registro_prestamo">Guardar Préstamo</button>
                        </div>                  
                      </div>
                    </div>
                  </div> 
                  
                  <!-- Modal agregar pagar prestamos -->
                  <div class="modal fade" id="modal-agregar-pagar-prestamo">
                    <div class="modal-dialog modal-dialog-scrollable modal-md">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4>Agregar Pago Préstamo</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        
                        <div class="modal-body">
                          <!-- form start -->
                          <form id="form-pago-prestamo" name="form-pago-prestamo"  method="POST" >                      
                            <div class="card-body">
                              <div class="row" id="cargando-3-fomulario">

                                <!-- id pago prestamo -->
                                <input type="hidden" name="idpago_prestamo" id="idpago_prestamo" /> 

                                <!-- id prestamo -->
                                <input type="hidden" name="idprestamo_p" id="idprestamo_p" />   
            
                                <!-- Entidad -->
                                <div class="col-lg-6">
                                  <div class="form-group">
                                  <label for="fecha_pago_p">Fecha</label>
                                  <input type="date" name="fecha_pago_p" id="fecha_pago_p" class="form-control"  placeholder="Fecha">  
                                  </div>
                                </div>
                                <!-- Fecha Inicio -->
                                <div class="col-lg-6">
                                  <div class="form-group">
                                  <label for="monto_pago_p">Monto Pago</label>
                                  <input type="number" name="monto_pago_p" id="monto_pago_p" class="form-control"  placeholder="Monto Pago">  
                                  </div>
                                </div>
                                
                                <!-- Descripcion-->
                                <div class="col-lg-12">
                                  <div class="form-group">
                                    <label for="descripcion_pago_p">Descripción </label> <br>
                                    <textarea name="descripcion_pago_p" id="descripcion_pago_p" class="form-control" rows="2"></textarea>
                                  </div>                                                        
                                </div>

                                <!-- Factura -->
                                <div class="col-md-6">
                                  <div class="row text-center">
                                    <div class="col-md-12" style="padding-top: 15px; padding-bottom: 5px;">
                                      <label for="cip" class="control-label"> Baucher</label>
                                    </div>
                                    <div class="col-md-6 text-center">
                                      <button type="button" class="btn btn-success btn-block btn-xs" id="doc1_i"><i class="fas fa-upload"></i> Subir.</button>
                                    <div class="col-6 col-md-6 text-center">
                                      <button type="button" class="btn btn-success btn-block btn-xs" id="doc1_i"><i class="fas fa-upload"></i> Subir. </button>
                                      <input type="hidden" id="doc_old_1" name="doc_old_1" />
                                      <input style="display: none;" id="doc1" type="file" name="doc1" accept="application/pdf, image/*" class="docpdf" />
                                    </div>
                                    <div class="col-md-6 text-center">
                                      <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(1, 'pago_prestamo');"><i class="fas fa-redo"></i> Recargar.</button>
                                  </div>
                                  <div id="doc1_ver" class="text-center mt-4">
                                    <img src="../dist/svg/doc_uploads.svg" alt="" width="50%" />
                                  </div>
                                  <div class="text-center" id="doc1_nombre"><!-- aqui va el nombre del pdf --></div>
                                </div>

                                <!-- barprogress -->
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                                  <div class="progress" id="div_barra_progress_pag">
                                    <div id="barra_progress_pag" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                      0%
                                    </div>
                                  </div>
                                </div>                                          

                              </div>  

                              <div class="row" id="cargando-4-fomulario" style="display: none;">
                                <div class="col-lg-12 text-center">
                                  <i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>
                                  <h4>Cargando...</h4>
                                </div>
                              </div>
                              
                            </div>
                            <!-- /.card-body -->                      
                            <button type="submit" style="display: none;" id="submit-form-pago-prestamo">Submit</button>                      
                          </form>
                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-success" id="guardar_registro_pago_prestamo">Guardar Préstamo</button>
                        </div>                  
                      </div>
                    </div>
                  </div>

                  <!--===============Modal-ver-vaucher =========-->
                  <div class="modal fade" id="modal-ver-comprobante">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg">
                      <div class="modal-content">
                        <div class="modal-header" style="background-color: #23b1ec52;">
                          <h4 class="modal-title">Comprobante</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <div class="class-style" style="text-align: center;">
                          <div class="row">
                            <div class="col-6" id="iddescargar"> </div>
                            <div class="col-6 view_comprobante_pago"> </div>
                          </div>

                            <br />
                            <img onerror="this.src='../dist/img/default/img_defecto.png';" src="../dist/img/default/img_defecto.png" class="img-thumbnail" id="img-factura" style="cursor: pointer !important;" width="auto" />
                            <div id="ver_fact_pdf" style="cursor: pointer !important;" width="auto"></div>
                          </div>
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

        <script type="text/javascript" src="scripts/prestamo.js"></script>
         
        <script>
          $(function () {
            $('[data-toggle="tooltip"]').tooltip();
          })
        </script>

        <?php require 'extra_script.php'; ?>
        
      </body>
    </html>
    <?php    
  }
  ob_end_flush();
?>
