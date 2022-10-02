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
                          <!-- regresar -->
                          <h3 class="card-title mr-3" >
                            <button type="button" class="btn bg-gradient-warning btn-sm h-50px" onclick="mostrar_form_table(1);despintar_btn_select();" ><i class="fa-regular fa-rectangle-list"></i> <span class="d-none d-sm-inline-block">Todos</span> </button>
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
                              <div class="mailbox-attachments clearfix text-center row">
                                <div class="col-12 col-sm-6 col-md-6 col-lg-2" >     
                                  <li >                    
                                    <span class="mailbox-attachment-icon name_icon_1"><i class="far fa-file-pdf"></i></span>
                                    <div class="mailbox-attachment-info">
                                      <a href="#" class="mailbox-attachment-name name_doc_1"><i class="fas fa-paperclip"></i> Acta-de-contrato-de-obra</a>
                                        <span class="mailbox-attachment-size clearfix mt-1">
                                          <a href="#" class="btn btn-default btn-sm download_doc_1" download="" data-toggle="tooltip" data-original-title="Descargar"><i class="fas fa-cloud-download-alt"></i></a>
                                          <a href="#" class="btn btn-default btn-sm ver_doc_1" target="_blank" data-toggle="tooltip" data-original-title="Ver"><i class="far fa-eye"></i></a>
                                          
                                        </span>
                                    </div>
                                  </li>
                                </div>
                                <div class="col-12 col-sm-6 col-md-6 col-lg-2" >     
                                  <li >                    
                                    <span class="mailbox-attachment-icon name_icon_1"><i class="far fa-file-pdf"></i></span>
                                    <div class="mailbox-attachment-info">
                                      <a href="#" class="mailbox-attachment-name name_doc_1"><i class="fas fa-paperclip"></i> Acta-de-contrato-de-obra</a>
                                        <span class="mailbox-attachment-size clearfix mt-1">
                                          <a href="#" class="btn btn-default btn-sm download_doc_1" download="" data-toggle="tooltip" data-original-title="Descargar"><i class="fas fa-cloud-download-alt"></i></a>
                                          <a href="#" class="btn btn-default btn-sm ver_doc_1" target="_blank" data-toggle="tooltip" data-original-title="Ver"><i class="far fa-eye"></i></a>
                                          
                                        </span>
                                    </div>
                                  </li>
                                </div>
                                <div class="col-12 col-sm-6 col-md-6 col-lg-2" >     
                                  <li >                    
                                    <span class="mailbox-attachment-icon name_icon_1"><i class="far fa-file-pdf"></i></span>
                                    <div class="mailbox-attachment-info">
                                      <a href="#" class="mailbox-attachment-name name_doc_1"><i class="fas fa-paperclip"></i> Acta-de-contrato-de-obra</a>
                                        <span class="mailbox-attachment-size clearfix mt-1">
                                          <a href="#" class="btn btn-default btn-sm download_doc_1" download="" data-toggle="tooltip" data-original-title="Descargar"><i class="fas fa-cloud-download-alt"></i></a>
                                          <a href="#" class="btn btn-default btn-sm ver_doc_1" target="_blank" data-toggle="tooltip" data-original-title="Ver"><i class="far fa-eye"></i></a>
                                          
                                        </span>
                                    </div>
                                  </li>
                                </div>
                                <div class="col-12 col-sm-6 col-md-6 col-lg-2" >     
                                  <li >                    
                                    <span class="mailbox-attachment-icon name_icon_1"><i class="far fa-file-pdf"></i></span>
                                    <div class="mailbox-attachment-info">
                                      <a href="#" class="mailbox-attachment-name name_doc_1"><i class="fas fa-paperclip"></i> Acta-de-contrato-de-obra</a>
                                        <span class="mailbox-attachment-size clearfix mt-1">
                                          <a href="#" class="btn btn-default btn-sm download_doc_1" download="" data-toggle="tooltip" data-original-title="Descargar"><i class="fas fa-cloud-download-alt"></i></a>
                                          <a href="#" class="btn btn-default btn-sm ver_doc_1" target="_blank" data-toggle="tooltip" data-original-title="Ver"><i class="far fa-eye"></i></a>
                                          
                                        </span>
                                    </div>
                                  </li>
                                </div>
                              </div>
                              
                            </div>

                            <div class="col-12 div-docs-por-valorizacion" style="display: none;">
                              <div class="row">
                                <div class="col-lg-4"> 
                                  <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('');"> <i class="fas fa-file-upload"></i> Subir </a> 
                                </div> 
                                <div class="col-lg-4"> 
                                  <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> 
                                </div> 
                                <div class="col-lg-4 mb-4"> 
                                  <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> 
                                </div> 
                                <div class="col-lg-12 "> 
                                  <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> 
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

        <script type="text/javascript" src="scripts/valorizacion_fierro.js"></script>

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip();  }); </script>

      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
