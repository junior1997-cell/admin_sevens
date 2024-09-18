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
        <link rel="stylesheet" href="../plugins/bootstrap-table/dist/bootstrap-table.min.css">
        <!-- <link rel="stylesheet" href="../plugins/excel-preview/css/excel-preview.css"> -->
      </head>
      <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed" idproyecto="<?php echo $_SESSION['idproyecto']; ?>">
        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper">
          <?php
          require 'nav.php';
          require 'aside.php';
          if ($_SESSION['valorizacion_concreto']==1){
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
                        <span class="h1-titulo">Concreto</span>                         
                      </h1> 
                      
                    </div>
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="valorizacion.php">Home</a></li>
                        <li class="breadcrumb-item active">Concreto</li>
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
                          <!-- vertodos -->
                          <h3 class="card-title mr-3" >
                            <button type="button" class="btn bg-gradient-warning btn-sm h-50px" onclick="mostrar_form_table(1); despintar_btn_select(); todos_los_docs();" ><i class="fa-regular fa-rectangle-list"></i> <span class="d-none d-sm-inline-block">Todos</span> </button>
                          </h3>

                          <!-- listar quincenas -->
                          <div id="lista_quincenas" class="row-horizon disenio-scroll" >
                            <i class="fas fa-spinner fa-pulse fa-2x"></i>
                          </div>  

                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">                          
                          <div class="row">   
                            <div class="col-12 div-todos-los-docs">
                              <div class="mailbox-attachments clearfix text-center row" id="all-docs-valorizacion">
                                <!-- todos los docs -->
                              </div>
                              
                            </div>

                            <div class="col-12 div-docs-por-valorizacion" style="display: none;">
                              
                              <div class="row div-btn-doc" >
                                <div class="col-12 text-center ">                                   
                                  <i class="fas fa-spinner fa-pulse fa-6x"></i><br /> <br /> <h4>Cargando...</h4>                                   
                                </div>                                
                              </div>
                              <div class="" id="div-doc-val"></div>
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
                <div class="modal fade" id="modal-agregar-editar-doc">
                  <div class="modal-dialog modal-dialog-scrollable modal-md">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title" id="title-modal-1">Agregar Doc</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form id="form-fierro" name="form-fierro" method="POST">
                          <div class="row" id="cargando-1-fomulario">
                            <!-- id proyecto -->
                            <input type="hidden" name="idproyecto" id="idproyecto" />
                            <!-- id valorizacion -->
                            <input type="hidden" name="idconcreto_por_valorizacion" id="idconcreto_por_valorizacion" />                            
                            <!-- fecha inicio -->
                            <input type="hidden" name="fecha_inicial" id="fecha_inicial" />
                            <!-- fecha fin -->
                            <input type="hidden" name="fecha_final" id="fecha_final" />
                            <!-- fecha numero_q_s -->
                            <input type="hidden" name="numero_valorizacion" id="numero_valorizacion" />

                            <div class="col-lg-12">
                              <div class="form-group">
                                <label for="codigo">Nombre doc </label>
                                <input type="text" name="nombre_doc" id="nombre_doc" class="form-control" placeholder="Código" />
                              </div>
                            </div>

                            <!-- Doc Valorizaciones -->
                            <div class="col-md-12 col-lg-12">
                              <div class="row text-center">
                                <div class="col-md-12" style="padding-top: 15px; padding-bottom: 5px;">
                                  <label for="cip" class="control-label">Documento </label>
                                </div>
                                <div class="col-6 col-md-6 text-center">
                                  <button type="button" class="btn btn-success btn-block btn-xs" id="doc1_i"><i class="fas fa-file-upload"></i> Subir.</button>
                                  <input type="hidden" id="doc_old_1" name="doc_old_1" />
                                  <input style="display: none;" id="doc1" type="file" name="doc1" accept=".xlsx, .xlsm, .xls, .csv, .pdf" class="docpdf" />
                                </div> 
                                <div class="col-6 col-md-6 text-center">
                                  <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(1, 'valorizacion', 'documento');"><i class="fa fa-eye"></i> Doc.</button>
                                </div>
                              </div>
                              <div id="doc1_ver" class="text-center mt-4">
                                <img src="../dist/svg/doc_uploads.svg" alt="" width="50%" />
                              </div>
                              <div class="text-center" id="doc1_nombre"><!-- aqui va el nombre del pdf --></div>
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
                          <button type="submit" style="display: none;" id="submit-form-fierro">Submit</button>
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
        
        <!-- EXCEL PREVIEW -->
        <script src="../plugins/bootstrap-table/dist/bootstrap-table.min.js" type="text/javascript"></script>
	      <script src="../plugins/bootstrap-table/dist/locale/bootstrap-table-es-MX.min.js" type="text/javascript"></script>
        <script src="../plugins/excel-preview/js/src/util.js" type="text/javascript" ></script>
	      <script src="../plugins/excel-preview/js/src/excel-preview.js" type="text/javascript" ></script>

        <script type="text/javascript" src="scripts/valorizacion_concreto.js?version_jdl=1.5"></script>

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip();  }); </script>

      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
