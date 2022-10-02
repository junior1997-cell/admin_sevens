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
        <title>Valorización concreto | Admin Sevens</title>

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
                      <h1 > <span class="h1-titulo">Valorización concreto</span> </h1>                  
                    </div>
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="valorizacion.php">Home</a></li>
                        <li class="breadcrumb-item active">Valorización Concreto</li>
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
                          <!-- Guardar -->
                          <h3 class="card-title mr-3" id="btn-guardar" style="display: none; padding-left: 2px;">
                            <button type="button" class="btn bg-gradient-success btn-guardar-asistencia btn-sm h-50px" onclick="guardar_y_editar_resumen_q_s_valorizacion();" style="margin-right: 10px;"><i class="far fa-save"></i> <span class="d-none d-sm-inline-block"> Guardar </span> </button>
                          </h3>   
                          <!-- listar quincenas -->
                          <div id="lista_quincenas" class="row-horizon disenio-scroll" >
                            <i class="fas fa-spinner fa-pulse fa-2x"></i>
                          </div>  
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">                          
                          <div class="row">
                            <!-- tab info resumen valorizaciones -->
                            <div class="col-lg-12 col-xl-12" id="tab-contenido">
                              <div class="tab-pane text-left" id="tabs-1" role="tabpanel" aria-labelledby="tabs-1-tab">
                                <div class="row mb-1" id="documento1">
                                  <div class="col-12 col-lg-6 mx-auto shadow">
                                    <a class="btn btn-success  btn-block btn-xs" type="button" data-toggle="modal" data-target="#modal-agregar-valorizacion">
                                      <i class="fas fa-file-upload"></i> Subir
                                    </a>
                                  </div>
                                  <div class="col-12 col-lg-6 mx-auto shadow">
                                    <a class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/documento/08-08-2022 04.48.22 PM 16165999530229.pdf" download="1 Copia del contrato -  MISION TARAPOTO - Val2 - 2-10-2022">
                                      <i class="fas fa-download"></i> Descargar
                                    </a>
                                  </div>   
                                  <div class="col-12 col-lg-12 shadow"> <br>
                                  <h3 class="mb-6 text-center"> Visualizasion del Documento.</h3>         

                                    <div>doc aqui</div>
                                  </div>                     
                                </div>
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
                        <h4 class="modal-title" id="title-modal-1">Agregar Valorizacion concreto</h4>
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
                            <!-- <input type="hidden" name="indice" id="indice" /> -->
                            <!-- nombre -->
                            <!-- <input type="hidden" name="nombre" id="nombre" /> -->
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

        <script type="text/javascript" src="scripts/valorizacion_valorizacion.js"></script>

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip();  }); </script>

      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
