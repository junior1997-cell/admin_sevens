<?php
  //Activamos el almacenamiento en el buffer
  ob_start();

  session_start();
  if (!isset($_SESSION["nombre"])){
    header("Location: login.html");
  }else{
    ?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Sevens | Valorización</title>
    <?php
    require 'head.php';
    ?>
    <style>
      .nav-link.active {
        border-color: #1e5b99 transparent #145aa1 #1b5691 !important;
      }
      .nav-tabs.flex-column {
        border-bottom: 0;
        border-right: 1px solid #32679d;
      }
    </style>
  </head>
  <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed">
    <!-- Content Wrapper. Contains page content -->
    <div class="wrapper">
      <?php
      require 'nav.php';
      require 'aside.php';
      if ($_SESSION['valorizacion']==1){
      ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
          <!-- Content Header (Page header) -->
          <section class="content-header">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <h1>Valorización</h1>
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
                    <div class="card-header row-horizon disenio-scroll" id="lista_quincenas">
                      <!-- Aqui van las fechas del proyecto -->
                      <i class="fas fa-spinner fa-pulse fa-2x"></i>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <h4>Valorización 2</h4>
                      <div class="row">
                        <!-- tab seleciones -->
                        <div class="col-lg-3 col-xl-4" id="tab-seleccione" style="display: none;">
                          <div class="nav flex-column nav-tabs h-100" id="tabs-tab" role="tablist" aria-orientation="vertical">

                            <a class="nav-link" id="tabs-1-tab" data-toggle="pill" href="#tabs-1" role="tab" aria-controls="tabs-1" aria-selected="false" onclick="add_data_form('doc1');">1. Copia del contrato</a>
                            <a class="nav-link" id="tabs-2-tab" data-toggle="pill" href="#tabs-2" role="tab" aria-controls="tabs-2" aria-selected="false" onclick="add_data_form('doc2');">2. Informe tecnico</a>
                            <span class="ml-3 nav-titulo" id="tabs-3-tab" data-toggle="pill" role="tab" aria-controls="tabs-3" aria-selected="false">3. Ejecución de obra</span>
                            <a class="nav-link ml-4" id="tabs-3-1-tab" data-toggle="pill" href="#tabs-3-1" role="tab" aria-controls="tabs-3-1" aria-selected="false" onclick="add_data_form('doc3.1');">3.1 Planilla de metrados</a>
                            <a class="nav-link ml-4" id="tabs-3-2-tab" data-toggle="pill" href="#tabs-3-2" role="tab" aria-controls="tabs-3-2" aria-selected="false" onclick="add_data_form('doc3.2');">3.2 Copia del contrato</a>
                            <a class="nav-link ml-4" id="tabs-3-3-tab" data-toggle="pill" href="#tabs-3-3" role="tab" aria-controls="tabs-3-3" aria-selected="false" onclick="add_data_form('doc3.3');">3.3 Informe tecnico</a>
                            <a class="nav-link ml-4" id="tabs-3-4-tab" data-toggle="pill" href="#tabs-3-4" role="tab" aria-controls="tabs-3-4" aria-selected="false" onclick="add_data_form('doc3.4');">3.4 Ejecución de obra</a>
                            <a class="nav-link" id="tabs-4-tab" data-toggle="pill" href="#tabs-4" role="tab" aria-controls="tabs-4" aria-selected="false" onclick="add_data_form('doc4');">4. Cronograma de obra valorizado</a>
                            <a class="ml-3 nav-titulo" id="tabs-5-tab" data-toggle="pill" role="tab" aria-controls="tabs-1" aria-selected="false" >5. Protocolo de calidad</a>
                            <a class="nav-link ml-4" id="tabs-5-1-tab" data-toggle="pill" href="#tabs-5-1" role="tab" aria-controls="tabs-2" aria-selected="false" onclick="add_data_form('doc5.1');">5.1 Ensayo de consistencia del concreto</a>
                            <a class="nav-link ml-4" id="tabs-5-2-tab" data-toggle="pill" href="#tabs-5-2" role="tab" aria-controls="tabs-3" aria-selected="false" onclick="add_data_form('doc5.2');">5.2 Ensayo de compresión</a>
                            <a class="nav-link" id="tabs-6-tab" data-toggle="pill" href="#tabs-6" role="tab" aria-controls="tabs-2" aria-selected="false" onclick="add_data_form('doc6');">6. Plan de seguridad y salud en el trabajo</a>
                            <a class="nav-link" id="tabs-7-tab" data-toggle="pill" href="#tabs-7" role="tab" aria-controls="tabs-2" aria-selected="false" onclick="add_data_form('doc7');">7. Plan de bioseguridad COVID19</a>
                            <a class="ml-3 nav-titulo" id="tabs-8-tab" data-toggle="pill" role="tab" aria-controls="tabs-2" aria-selected="false">8. Anexos</a>
                            <a class="nav-link ml-4" id="tabs-8-1-tab" data-toggle="pill" href="#tabs-8-1" role="tab" aria-controls="tabs-8-1" aria-selected="false" onclick="add_data_form('doc8.1');">8.1 Acta de entrega de terreno</a>
                            <a class="nav-link ml-4" id="tabs-8-2-tab" data-toggle="pill" href="#tabs-8-2" role="tab" aria-controls="tabs-8-2" aria-selected="false" onclick="add_data_form('doc8.2');">8.2 Acta de inicio de obra</a>
                            <a class="nav-link ml-4" id="tabs-8-3-tab" data-toggle="pill" href="#tabs-8-3" role="tab" aria-controls="tabs-8-3" aria-selected="false" onclick="add_data_form('doc8.3');">8.3 Certificado de habilidad del ingeniero residente</a>
                            <a class="nav-link ml-4" id="tabs-8-4-tab" data-toggle="pill" href="#tabs-8-4" role="tab" aria-controls="tabs-8-4" aria-selected="false" onclick="add_data_form('doc8.4');">8.4 Planilla del personal obrero</a>
                            <a class="nav-link ml-4" id="tabs-8-5-tab" data-toggle="pill" href="#tabs-8-5" role="tab" aria-controls="tabs-8-5" aria-selected="false" onclick="add_data_form('doc8.5');">8.5 Copia del seguro complementario contra todo riesgo</a>
                            <a class="nav-link ml-4" id="tabs-8-6-tab" data-toggle="pill" href="#tabs-8-6" role="tab" aria-controls="tabs-8-6" aria-selected="false" onclick="add_data_form('doc8.6');">8.6 Panel fotográfico</a>
                            <a class="nav-link ml-4" id="tabs-8-7-tab" data-toggle="pill" href="#tabs-8-7" role="tab" aria-controls="tabs-8-7" aria-selected="false" onclick="add_data_form('doc8.7');">8.7 Copia del cuaderno de obra</a>
                          </div>
                        </div>
                        <!-- Tab contenido -->
                        <div class="col-lg-9 col-xl-8" id="tab-contenido" style="display: none;">
                          <div class="tab-content" id="tabs-tabContent">
                            <!-- Resumen de documentos subidos -->
                            <div class="tab-pane fade show active"  role="tabpanel">
                              <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="far fa-bookmark"></i></span>

                                <div class="info-box-content">
                                  <span class="info-box-text">Documentos Subidos</span>
                                  <span class="info-box-number">Total 8/18</span>

                                  <div class="progress">
                                    <div class="progress-bar" style="width: 50%"></div>
                                  </div>
                                  <span class="progress-description">
                                    Estas a un 50% de documentos subidos!!!
                                  </span>
                                </div>
                              </div>
                            </div>

                            <!-- 1. Copia del contrato -->
                            <div class="tab-pane text-left fade" id="tabs-1" role="tabpanel" aria-labelledby="tabs-1-tab">
                              <div class="row">
                                <div class="col-lg-4">
                                  <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc();">
                                    <i class="fas fa-file-upload"></i> Subir
                                  </a>
                                </div>
                                <div class="col-lg-4">
                                  <a  class="btn btn-warning  btn-block btn-xs" type="button" >
                                    <i class="fas fa-download"></i> Descargar
                                  </a>
                                </div>
                                <div class="col-lg-4 mb-4">
                                  <a  class="btn btn-info  btn-block btn-xs" href="#"  target="_blank"  type="button" >
                                    <i class="fas fa-expand"></i> Ver completo
                                  </a>
                                </div>
                                <div class="col-lg-12 ">
                                  <div class='embed-responsive disenio-scroll' style='padding-bottom:90%'>
                                    <embed class="disenio-scroll" src="../dist/pdf/9163465089428.pdf" type="application/pdf" width="100%" height="100%" />
                                  </div>
                                </div>
                              </div>
                            </div>

                            <!-- 2. Informe tecnico -->
                            <div class="tab-pane fade" id="tabs-2" role="tabpanel" aria-labelledby="tabs-2-tab">
                              Doc 2
                            </div>

                            <!-- 3. Ejecución de obra -->
                            <div class="tab-pane fade" id="tabs-3" role="tabpanel" aria-labelledby="tabs-3-tab">
                              Doc 3
                            </div>

                            <!-- 3.1 Planilla de metrados -->
                            <div class="tab-pane fade" id="tabs-3-1" role="tabpanel" aria-labelledby="tabs-3-1-tab">
                              Doc 3.1
                            </div>

                            <!-- 3.2 Copia del contrato  -->
                            <div class="tab-pane fade" id="tabs-3-2" role="tabpanel" aria-labelledby="tabs-3-2-tab">
                              Doc 3.2
                            </div>

                            <!-- 3.3 Informe tecnico -->
                            <div class="tab-pane fade" id="tabs-3-3" role="tabpanel" aria-labelledby="tabs-3-3-tab">
                              Doc 3.3
                            </div>

                            <!-- 3.4 Ejecución de obra -->
                            <div class="tab-pane fade" id="tabs-4" role="tabpanel" aria-labelledby="tabs-4-tab">
                              Doc 4
                            </div>

                            <!-- 5. Protocolo de calidad -->
                            <div class="tab-pane fade" id="tabs-5" role="tabpanel" aria-labelledby="tabs-5-tab">
                              Doc 5
                            </div>

                            <!-- 5.1 Ensayo de consistencia del concreto -->
                            <div class="tab-pane fade" id="tabs-5-1" role="tabpanel" aria-labelledby="tabs-5-2-tab">
                              Doc 5.1
                            </div>

                            <!-- 5.2 Ensayo de compresión -->
                            <div class="tab-pane fade" id="tabs-5-2" role="tabpanel" aria-labelledby="tabs-5-2-tab">
                              Doc 5.2
                            </div>

                            <!-- 6. Plan de seguridad y salud en el trabajo -->
                            <div class="tab-pane fade" id="tabs-6" role="tabpanel" aria-labelledby="tabs-6-tab">
                              Doc 6
                            </div>

                            <!-- 7. Plan de bioseguridad COVID19 -->
                            <div class="tab-pane fade" id="tabs-7" role="tabpanel" aria-labelledby="tabs-7-tab">
                              Doc 7
                            </div>

                            <!-- 8. Anexos -->
                            <div class="tab-pane fade" id="tabs-8" role="tabpanel" aria-labelledby="tabs-8-tab">
                              Doc 8
                            </div>
                            <!-- 8.1 Acta de entrega de terreno -->                            
                            <div class="tab-pane fade" id="tabs-8-1" role="tabpanel" aria-labelledby="tabs-8-1-tab">
                              Doc 8.1
                            </div>

                            <!-- 8.2 Acta de inicio de obra -->                            
                            <div class="tab-pane fade" id="tabs-8-2" role="tabpanel" aria-labelledby="tabs-8-2-tab">
                              Doc 8.2
                            </div>

                            <!-- 8.3 Certificado de habilidad del ingeniero residente -->                            
                            <div class="tab-pane fade" id="tabs-8-3" role="tabpanel" aria-labelledby="tabs-8-3-tab">
                              Doc 8.3
                            </div>

                            <!-- 8.4 Planilla del personal obrero -->                            
                            <div class="tab-pane fade" id="tabs-8-4" role="tabpanel" aria-labelledby="tabs-8-4-tab">
                              Doc 8.4
                            </div>

                            <!-- 8.5 Copia del seguro complementario contra todo riesgo -->                            
                            <div class="tab-pane fade" id="tabs-8-5" role="tabpanel" aria-labelledby="tabs-8-5-tab">
                              Doc 8.5
                            </div>

                            <!-- 8.6 Panel fotográfico -->                            
                            <div class="tab-pane fade" id="tabs-8-6" role="tabpanel" aria-labelledby="tabs-8-6-tab">
                              Doc 8.6
                            </div>

                            <!-- 8.7 Copia del cuaderno de obra -->
                            <div class="tab-pane fade" id="tabs-8-7" role="tabpanel" aria-labelledby="tabs-8-7-tab">
                              Doc 8.7
                            </div>
                          </div>
                        </div>
                        <!-- tab info -->
                        <div class="col-lg-12 col-xl-12" id="tab-info" >
                          <div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-info"></i> Alerta!</h4>
                            Seleciona una quincena para ver todos los documentos de este proyecto.
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
                    <h4 class="modal-title">Agregar Valorizacion</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span class="text-danger" aria-hidden="true">&times;</span>
                    </button>
                  </div>

                  <div class="modal-body">
                    <!-- form start -->
                    <form id="form-valorizacion" name="form-valorizacion" method="POST">
                      
                      <div class="row" id="cargando-1-fomulario">

                        <!-- id proyecto -->
                        <input type="text" name="idproyecto" id="idproyecto" />
                        <!-- id valorizacion -->
                        <input type="text" name="idvalorizacion" id="idvalorizacion" />
                        <!-- nombre -->
                        <input type="text" name="nombre" id="nombre" />
                        <!-- fecha quincena -->
                        <input type="text" name="fecha_quincena" id="fecha_quincena" />                          

                        <!-- Doc Valorizaciones -->
                        <div class="col-md-12 col-lg-12" >                               
                          <div class="row text-center">
                            <div class="col-md-12" style="padding-top: 15px; padding-bottom: 5px;">
                              <label for="cip" class="control-label" >Valorizaciones </label>
                            </div>
                            <div class="col-md-6 text-center">
                              <button type="button" class="btn btn-success btn-block btn-xs" id="doc7_i">
                                <i class="fas fa-file-upload"></i> Subir.
                              </button>
                              <input type="hidden" id="doc_old_7" name="doc_old_7" />
                              <input style="display: none;" id="doc7" type="file" name="doc7" accept=".xlsx, .xlsm, .xls, .csv, .pdf" class="docpdf" /> 
                            </div>
                            <div class="col-md-6 text-center">
                              <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion();">
                                <i class="fa fa-eye"></i> Doc.
                              </button>
                            </div>
                          </div>                              
                          <div id="doc7_ver" class="text-center mt-4">
                            <img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >
                          </div>
                          <div class="text-center" id="doc7_nombre"><!-- aqui va el nombre del pdf --></div>
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
                      <button type="submit" style="display: none;" id="submit-form-trabajador">Submit</button>
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

      <?php
      }else{
        require 'noacceso.php';
      }
      require 'footer.php';
      ?>
    </div>
    <!-- /.content-wrapper -->
    <?php
    
    require 'script.php';
    ?>
    <style>
        .class-style label{
            font-size: 14px;
        }
        .class-style small {
            background-color: #f4f7ee;
            border: solid 1px #ce542a21;
            margin-left: 3px;
            padding: 5px;
            border-radius: 6px;
        }
    </style>
    <!-- Bootstrap 4 -->
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- jquery-validation -->
    <script src="../plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="../plugins/jquery-validation/additional-methods.min.js"></script>
    <!-- InputMask -->
    <script src="../plugins/moment/moment.min.js"></script>
    <script src="../plugins/inputmask/jquery.inputmask.min.js"></script>
    <!-- sweetalert2 -->
    <script src="../plugins/sweetalert2/sweetalert2.all.min.js"></script>

    <script type="text/javascript" src="scripts/valorizacion.js"></script>

    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    <script>
      if ( localStorage.getItem('nube_idproyecto') ) {

        console.log("icon_folder_"+localStorage.getItem('nube_idproyecto'));

        $("#ver-proyecto").html('<i class="fas fa-tools"></i> Proyecto: ' +  localStorage.getItem('nube_nombre_proyecto'));

        $("#ver-otros-modulos-1").show();

        // $('#icon_folder_'+localStorage.getItem('nube_idproyecto')).html('<i class="fas fa-folder-open"></i>');

      }else{
        $("#ver-proyecto").html('<i class="fas fa-tools"></i> Selecciona un proyecto');

        $("#ver-otros-modulos-1").hide();
      }
      
    </script>
  </body>
</html>

<?php  
  }
  ob_end_flush();

?>