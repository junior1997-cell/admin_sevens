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
        <title>Admin Sevens | Pagos de Administradores</title>
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
                    <h1 class="m-0 nombre-trabajador">Pagos de Administradores</h1>
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="pago_administrador.php">Pagos</a></li>
                      <li class="breadcrumb-item active">Pagos de Admin.</li>
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
                        <h3 class="card-title mr-3" id="btn-regresar" style="display: none; padding-left: 2px;" >
                          <button type="button" class="btn bg-gradient-warning btn-sm" onclick="table_show_hide(1);"  ><i class="fas fa-arrow-left"></i> <span class="d-none d-sm-inline-block">Regresar</span> </button>
                        </h3>

                        <!-- regresar todo -->
                        <h3 class="card-title mr-3" id="btn-regresar-todo" style="display: none; padding-left: 2px;" data-toggle="tooltip" data-original-title="Regresar a la tabla principal">
                          <button type="button" class="btn btn-block btn-outline-warning btn-sm" onclick="table_show_hide(1);"><i class="fas fa-arrow-left"></i></button>
                        </h3>
                        <!-- regresar 1 -->
                        <h3 class="card-title mr-3" id="btn-regresar-bloque" style="display: none; padding-left: 2px;" data-toggle="tooltip" data-original-title="Regresar a la tabla fechas">
                          <button type="button" class="btn bg-gradient-warning btn-sm" onclick="table_show_hide(2);"  ><i class="fas fa-arrow-left"></i> <span class="d-none d-sm-inline-block">Regresar</span> </button>
                        </h3>
                        <!-- agregar pago  -->
                        <h3 class="card-title " id="btn-agregar" style="display: none; padding-left: 2px;" >
                          <button type="button" class="btn bg-gradient-success btn-sm" data-toggle="modal" data-target="#modal-agregar-pago-trabajdor" onclick="limpiar();">
                          <i class="fas fa-plus-circle"></i> Agregar pago 
                          </button>                     
                        </h3> 
                        
                        <h3 class="  " id="btn-nombre-mes" style="display: none; padding-left: 2px;" >&nbsp; - Enero </h3> 

                      </div>
                      <!-- /.card-header -->
                      <div class="card-body">

                        <!-- tabla principal -->
                        <div class="row row-horizon disenio-scroll pb-3" id="tbl-principal">
                          <table id="tabla-principal" class="table table-bordered  table-striped display" style="width: 100% !important;">
                            <thead>
                              <tr> 
                                <th>Trabajdor</th> 
                                <th>Fecha inicio</th>
                                <th>Hoy</th>
                                <th class="text-center">Fecha <br> culminacion</th>
                                <th class="text-center">Tiempo <br> trabajado (dias)</th>
                                <th>Ultimo pago</th>
                                <th class="text-center">Pago <br> Siguiente</th>
                                <th>Sueldo Mensual</th>
                                <th class="text-center">Pago <br> acumulado</th>
                                <th class="text-center">Pago <br> realizado</th>
                                <th>Saldo</th>
                                <th>Cel:</th>                         
                              </tr>
                            </thead>
                            <tbody>                         
                              
                            </tbody>
                            <tfoot>
                              <tr> 
                                <th>Trabajdor</th> 
                                <th>Fecha inicio</th>
                                <th>Hoy</th>
                                <th class="text-center">Fecha <br> culminacion</th>
                                <th class="text-center">Tiempo <br> trabajado (dias)</th>
                                <th>Ultimo pago</th>
                                <th>Siguiente pago</th>
                                <th class="text-primary">S/. 9,030.00</th>
                                <th class="text-primary">S/. 900.00</th>
                                <th class="text-primary">S/. 13,500.00</th>
                                <th>Saldo</th>  
                                <th>Cel:</th>                            
                              </tr>
                            </tfoot>
                          </table>
                        </div>                       

                        <!-- tabla fecha -->
                        <div class="table-responsive" id="tbl-fechas" style="display: none;">
                          <div class="table-responsive-lg" >
                            <table class="table styletabla" style="border: black 1px solid;">
                              <thead>                                  
                                <tr class="bg-gradient-info">
                                  <th class="stile-celda">N°</th>
                                  <th class="stile-celda">Mes</th>
                                  <th colspan="2" class="stile-celda">Fechas Inicial/Final</th>
                                  <th class="stile-celda text-center">Días</th>
                                  <th class="stile-celda text-center">Sueldo</th>
                                  <th class="stile-celda">Monto</th>
                                  <th class="stile-celda">Pagar/Acumulado</th> 
                                </tr>
                              </thead>
                              <tbody class="tcuerpo data-fechas-mes">
                                <tr>
                                  <td>1</td>
                                  <td>Enero </td>
                                  <td>12-01-2022</td>
                                  <td>31-01-2022</td>
                                  <td>19</td>
                                  <td> S/. 4400.00</td>
                                  <td> S/. 2696.77</td>
                                  <td>
                                    <button class="btn btn-info btn-sm" onclick="listar_tbla_pagos_x_mes(1);"><i class="fas fa-dollar-sign"></i> Pagar</button>
                                    <button style="font-size: 14px;" class="btn btn-danger btn-sm">S/. 900.00</button></div>
                                  </td>
                                </tr>
                                <tr>
                                  <td>2</td>
                                  <td>Febrero </td>
                                  <td>01-02-2022</td>
                                  <td>28-02-2022</td>
                                  <td>28</td>
                                  <td> S/. 4400.00</td>
                                  <td> S/. 4400.00</td>
                                  <td>
                                    <button class="btn btn-info btn-sm" onclick="listar_tbla_pagos_x_mes(1);"><i class="fas fa-dollar-sign"></i> Pagar</button>
                                    <button style="font-size: 14px;" class="btn btn-danger btn-sm">S/. 900.00</button></div>
                                  </td>
                                </tr>  
                                <tr>
                                  <td>3</td>
                                  <td>Marzo </td>
                                  <td>01-03-2022</td>
                                  <td>31-03-2022</td>
                                  <td>31</td>
                                  <td> S/. 4400.00</td>
                                  <td> S/. 4400.00</td>
                                  <td>
                                    <button class="btn btn-info btn-sm" onclick="listar_tbla_pagos_x_mes(1);"><i class="fas fa-dollar-sign"></i> Pagar</button>
                                    <button style="font-size: 14px;" class="btn btn-danger btn-sm">S/. 900.00</button></div>
                                  </td>
                                </tr>
                                <tr>
                                  <td>4</td>
                                  <td>Abril </td>
                                  <td>01-04-2022</td>
                                  <td>30-04-2022</td>
                                  <td>30</td>
                                  <td> S/. 4400.00</td>
                                  <td> S/. 4400.00</td>
                                  <td>
                                    <button class="btn btn-info btn-sm" onclick="listar_tbla_pagos_x_mes(1);"><i class="fas fa-dollar-sign"></i> Pagar</button>
                                    <button style="font-size: 14px;" class="btn btn-danger btn-sm">S/. 900.00</button></div>
                                  </td>
                                </tr>  
                                <tr>
                                  <td>5</td>
                                  <td>Mayo </td>
                                  <td>01-05-2022</td>
                                  <td>27-05-2022</td>
                                  <td>27</td>
                                  <td> S/. 4400.00</td>
                                  <td> S/. 3832.26</td>
                                  <td>
                                    <button class="btn btn-info btn-sm" onclick="listar_tbla_pagos_x_mes(1);"><i class="fas fa-dollar-sign"></i> Pagar</button>
                                    <button style="font-size: 14px;" class="btn btn-danger btn-sm">S/. 900.00</button></div>
                                  </td>
                                </tr>                              
                              </tbody>
                              <tfoot>
                                <tr> 
                                   
                                  <th colspan="6" class="text-right" >Total</th> 
                                  <th class="stile-celda monto_x_mes_total">S/. 19,729.03</th> 
                                  <th class="stile-celda monto_x_mes_pagado_total">S/. 4,500.00</th>                           
                                </tr>
                              </tfoot>
                            </table>
                          </div>
                        </div>      
                        
                        <!-- tabla ingresos de pagos -->
                        <div class=" " id="tbl-ingreso-pagos" style="display: none !important;">
                          <table id="tabla-ingreso-pagos" class="table table-bordered  table-striped display" style="width: 100% !important;">
                            <thead>
                              <tr> 
                                <th>Cuenta depósito</th> 
                                <th>Forma de pago</th>
                                <th>Cantidad</th>
                                <th>Baucher</th>
                                <th>Recibos por honorarios</th>
                                <th>Descripcion</th> 
                                <th>Estado</th>                                                        
                              </tr>
                            </thead>
                            <tbody>                         
                              
                            </tbody>
                            <tfoot>
                              <tr> 
                                <th>Cuenta depósito</th>
                                <th>Forma de pago</th>
                                <th>S/. 900</th>
                                <th>Baucher</th>
                                <th>Recibos por honorarios</th>
                                <th>Descripcion</th> 
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

              <!-- Modal agregar usuario -->
              <div class="modal fade" id="modal-agregar-pago-trabajdor">
                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Agregar pago: <b> MELVA LOURDES MEDINA MARCHENA </b></h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    
                    <div class="modal-body">
                      <!-- form start -->
                      <form id="form-proyecto" name="form-proyecto"  method="POST" >                      
                        <div class="card-body">
                          <div class="row" id="cargando-1-fomulario">
                            <!-- id proyecto -->
                            <input type="hidden" name="idproyecto" id="idproyecto" />

                            <!-- Empresa -->
                            <div class="col-lg-6">
                              <div class="form-group">
                                <label for="empresa">Cuenta deposito <small>(del trabajdor)</small> </label>                               
                                <input type="text" value="0989-768568756-568" name="empresa" id="empresa" class="form-control"  placeholder="Empresa">  
                              </div>                                                        
                            </div>

                            <!-- Nombre del proyecto -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                <label for="forma_pago">Forma Pago</label>
                                <select name="forma_pago" id="forma_pago" class="form-control select2" style="width: 100%;">
                                    <option value="Transferencia">Transferencia</option>
                                    <option value="Efectivo">Efectivo</option>
                                    <option value="Crédito">Crédito</option>
                                </select>
                                </div>
                            </div>

                            <!-- Ubicación (de la obra) -->
                            <div class="col-lg-6">
                              <div class="form-group">
                                <label for="ubicacion">Cantidad <small> (cantidad a depositado) </small> </label>                               
                                <input type="text" name="ubicacion" id="ubicacion" class="form-control"  placeholder="Ubicación"> 
                              </div>                                                        
                            </div>

                            <!-- Actividad del trabajo -->
                            <div class="col-lg-6">
                              <div class="form-group">
                                <label for="actividad_trabajo">Mes </label>
                                <input type="text" value="Enero" name="actividad_trabajo" id="actividad_trabajo" class="form-control" placeholder="Actividad del trabajo">
                              </div>
                            </div>

                            <!-- Descripcion-->
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label for="descripcion">Descripción </label> <br>
                                <textarea name="descripcion" id="descripcion" class="form-control" rows="2"></textarea>
                              </div>                                                        
                            </div>
                             
                            <!-- Pdf 1 -->
                            <div class="col-md-6" >                               
                              <div class="row text-center">
                                <div class="col-md-12" style="padding-top: 15px; padding-bottom: 5px;">
                                  <label for="cip" class="control-label" > Baucher de deposito </label>
                                </div>
                                <div class="col-md-6 text-center">
                                  <button type="button" class="btn btn-success btn-block btn-xs" id="doc1_i">
                                    <i class="fas fa-file-upload"></i> Subir.
                                  </button>
                                  <input type="hidden" id="doc_old_1" name="doc_old_1" />
                                  <input style="display: none;" id="doc1" type="file" name="doc1" accept="application/pdf" class="docpdf" /> 
                                </div>
                                <div class="col-md-6 text-center">
                                  <button type="button" class="btn btn-info btn-block btn-xs" onclick="PreviewImage();">
                                    <i class="fa fa-eye"></i> PDF.
                                  </button>
                                </div>
                              </div>                              
                              <div id="doc1_ver" class="text-center mt-4">
                                <img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >
                              </div>
                              <div class="text-center" id="doc1_nombre"><!-- aqui va el nombre del pdf --></div>
                            </div> 

                            <!-- Pdf 2 -->
                            <div class="col-md-6" >                               
                              <div class="row text-center">
                                <div class="col-md-12" style="padding-top: 15px; padding-bottom: 5px;">
                                  <label for="cip" class="control-label" > Recibo x honorarios</label>
                                </div>
                                <div class="col-md-6 text-center">
                                  <button type="button" class="btn btn-success btn-block btn-xs" id="doc2_i">
                                    <i class="fas fa-file-upload"></i> Subir.
                                  </button>
                                  <input type="hidden" id="doc_old_2" name="doc_old_2" />
                                  <input style="display: none;" id="doc2" type="file" name="doc2" accept="application/pdf" class="docpdf" /> 
                                </div>
                                <div class="col-md-6 text-center">
                                  <button type="button" class="btn btn-info btn-block btn-xs" onclick="PreviewImage();">
                                    <i class="fa fa-eye"></i> PDF.
                                  </button>
                                </div>
                              </div>                              
                              <div id="doc2_ver" class="text-center mt-4">
                                <img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >
                              </div>
                              <div class="text-center" id="doc2_nombre"><!-- aqui va el nombre del pdf --></div>
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
         

        <script type="text/javascript" src="scripts/pago_administrador.js"></script>
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
