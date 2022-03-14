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
                  <h1 class="h1-titulo">Valorización - </h1>
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
                      <h4 id="nombre_titulo">Valorización</h4>
                      <div class="row">
                        <!-- tab seleciones -->
                        <div class="col-lg-3 col-xl-4" id="tab-seleccione" style="display: none;">
                          <div class="nav flex-column nav-tabs h-100" id="tabs-tab" role="tablist" aria-orientation="vertical">

                            <a class="nav-link" id="tabs-1-tab" data-toggle="pill" href="#tabs-1" role="tab" aria-controls="tabs-1" aria-selected="false" onclick="add_data_form('doc1','Copia del contrato');">1. Copia del contrato</a>
                            <a class="nav-link" id="tabs-2-tab" data-toggle="pill" href="#tabs-2" role="tab" aria-controls="tabs-2" aria-selected="false" onclick="add_data_form('doc2','Informe técnico');">2. Informe técnico</a>
                            <span class="ml-3 nav-titulo" id="tabs-3-tab" data-toggle="pill" role="tab" aria-controls="tabs-3" aria-selected="false">3. Ejecución de obra</span>
                            <a class="nav-link ml-4" id="tabs-3-1-tab" data-toggle="pill" href="#tabs-3-1" role="tab" aria-controls="tabs-3-1" aria-selected="false" onclick="add_data_form('doc3.1', 'Planilla de metrados');">3.1 Planilla de metrados</a>
                            <a class="nav-link ml-4" id="tabs-3-2-tab" data-toggle="pill" href="#tabs-3-2" role="tab" aria-controls="tabs-3-2" aria-selected="false" onclick="add_data_form('doc3.2', 'Valorización');">3.2 Valorización</a>
                            <a class="nav-link ml-4" id="tabs-3-3-tab" data-toggle="pill" href="#tabs-3-3" role="tab" aria-controls="tabs-3-3" aria-selected="false" onclick="add_data_form('doc3.3', 'Resumen de Valorización');">3.3 Resumen de Valorización</a>
                            <a class="nav-link ml-4" id="tabs-3-4-tab" data-toggle="pill" href="#tabs-3-4" role="tab" aria-controls="tabs-3-4" aria-selected="false" onclick="add_data_form('doc3.4', 'Curva S');">3.4 Curva "S"</a>
                            <a class="nav-link" id="tabs-4-tab" data-toggle="pill" href="#tabs-4" role="tab" aria-controls="tabs-4" aria-selected="false" onclick="add_data_form('doc4', 'Cronograma de obra valorizado');">4. Cronograma de obra valorizado</a>
                            <a class="ml-3 nav-titulo" id="tabs-5-tab" data-toggle="pill" role="tab" aria-controls="tabs-1" aria-selected="false" >5. Protocolo de calidad</a>
                            <a class="nav-link ml-4" id="tabs-5-1-tab" data-toggle="pill" href="#tabs-5-1" role="tab" aria-controls="tabs-2" aria-selected="false" onclick="add_data_form('doc5.1', 'Ensayo de consistencia del concreto');">5.1 Ensayo de consistencia del concreto</a>
                            <a class="nav-link ml-4" id="tabs-5-2-tab" data-toggle="pill" href="#tabs-5-2" role="tab" aria-controls="tabs-3" aria-selected="false" onclick="add_data_form('doc5.2', 'Ensayo de compresión');">5.2 Ensayo de compresión</a>
                            <a class="nav-link" id="tabs-6-tab" data-toggle="pill" href="#tabs-6" role="tab" aria-controls="tabs-2" aria-selected="false" onclick="add_data_form('doc6', 'Plan de seguridad y salud en el trabajo');">6. Plan de seguridad y salud en el trabajo</a>
                            <a class="nav-link" id="tabs-7-tab" data-toggle="pill" href="#tabs-7" role="tab" aria-controls="tabs-2" aria-selected="false" onclick="add_data_form('doc7', 'Plan de bioseguridad COVID19');">7. Plan de bioseguridad COVID19</a>
                            <a class="ml-3 nav-titulo" id="tabs-8-tab" data-toggle="pill" role="tab" aria-controls="tabs-2" aria-selected="false">8. Anexos</a>
                            <a class="nav-link ml-4" id="tabs-8-1-tab" data-toggle="pill" href="#tabs-8-1" role="tab" aria-controls="tabs-8-1" aria-selected="false" onclick="add_data_form('doc8.1', 'Acta de entrega de terreno');">8.1 Acta de entrega de terreno</a>
                            <a class="nav-link ml-4" id="tabs-8-2-tab" data-toggle="pill" href="#tabs-8-2" role="tab" aria-controls="tabs-8-2" aria-selected="false" onclick="add_data_form('doc8.2', 'Acta de inicio de obra');">8.2 Acta de inicio de obra</a>
                            <a class="nav-link ml-4" id="tabs-8-3-tab" data-toggle="pill" href="#tabs-8-3" role="tab" aria-controls="tabs-8-3" aria-selected="false" onclick="add_data_form('doc8.3', 'Certificado de habilidad del ingeniero residente');">8.3 Certificado de habilidad del ingeniero residente</a>
                            <a class="nav-link ml-4" id="tabs-8-4-tab" data-toggle="pill" href="#tabs-8-4" role="tab" aria-controls="tabs-8-4" aria-selected="false" onclick="add_data_form('doc8.4', 'Planilla del personal obrero');">8.4 Planilla del personal obrero</a>
                            <a class="nav-link ml-4" id="tabs-8-5-tab" data-toggle="pill" href="#tabs-8-5" role="tab" aria-controls="tabs-8-5" aria-selected="false" onclick="add_data_form('doc8.5', 'Copia del seguro complementario contra todo riesgo');">8.5 Copia del seguro complementario contra todo riesgo</a>
                            <a class="nav-link ml-4" id="tabs-8-6-tab" data-toggle="pill" href="#tabs-8-6" role="tab" aria-controls="tabs-8-6" aria-selected="false" onclick="add_data_form('doc8.6', 'Panel fotográfico');">8.6 Panel fotográfico</a>
                            <a class="nav-link ml-4" id="tabs-8-7-tab" data-toggle="pill" href="#tabs-8-7" role="tab" aria-controls="tabs-8-7" aria-selected="false" onclick="add_data_form('doc8.7', 'Copia del cuaderno de obra');">8.7 Copia del cuaderno de obra</a>
                            <a class="nav-link" id="tabs-resumen-tab" data-toggle="pill" href="#tabs-resumen" role="tab" aria-controls="tabs-resumen" aria-selected="false" >RESUMEN</a>
                          </div>
                        </div>
                        <!-- Tab contenido -->
                        <div class="col-lg-9 col-xl-8" id="tab-contenido" style="display: none;">
                          <div class="tab-content" id="tabs-tabContent">
                            <!-- Resumen de documentos subidos -->
                            <div class="tab-pane fade show active" id="tabs-resumen"  role="tabpanel" aria-labelledby="tabs-resumen-tab">
                              <!-- aqui va el resumen -->
                            </div>

                            <!-- 1. Copia del contrato -->
                            <div class="tab-pane text-left fade" id="tabs-1" role="tabpanel" aria-labelledby="tabs-1-tab">
                              <div class="row"> <div class="col-lg-12"> <h3>Copia del contrato</h3></div> </div>
                              <div class="row" id="documento1">
                                <!-- Aqui va - Copia del contrato -->                                
                              </div>
                            </div>

                            <!-- 2. Informe tecnico -->
                            <div class="tab-pane fade" id="tabs-2" role="tabpanel" aria-labelledby="tabs-2-tab">
                              <div class="row"> <div class="col-lg-12"> <h3>Informe técnico</h3></div> </div>
                              <div class="row" id="documento2">
                                <!-- Aqui va - Copia del contrato -->                                
                              </div>
                            </div>

                            <!-- 3. Ejecución de obra -->
                            <div class="tab-pane fade" id="tabs-3" role="tabpanel" aria-labelledby="tabs-3-tab">
                              
                            </div>

                            <!-- 3.1 Planilla de metrados -->
                            <div class="tab-pane fade" id="tabs-3-1" role="tabpanel" aria-labelledby="tabs-3-1-tab">
                              <div class="row"> <div class="col-lg-12"> <h3>Planilla de metrados</h3></div> </div>
                              <div class="row" id="documento3-1">
                                <!-- Aqui va - Planilla de metrados -->                                
                              </div>
                            </div>

                            <!-- 3.2 Valorizaciones  -->
                            <div class="tab-pane fade" id="tabs-3-2" role="tabpanel" aria-labelledby="tabs-3-2-tab">
                              <div class="row"> <div class="col-lg-12"> <h3>Valorizaciones</h3></div> </div>
                              <div class="row" id="documento3-2">
                                <!-- Aqui va - Valorizaciones -->                                
                              </div>
                            </div>

                            <!-- 3.3 Resumen de valorizacion -->
                            <div class="tab-pane fade" id="tabs-3-3" role="tabpanel" aria-labelledby="tabs-3-3-tab">
                              <div class="row"> <div class="col-lg-12"> <h3>Resumen de valorizacion</h3></div> </div>
                              <div class="row" id="documento3-3">
                                <!-- Aqui va - Resumen de valorizacion -->                                
                              </div>
                            </div>

                            <!-- 3.4 Curva S -->
                            <div class="tab-pane fade" id="tabs-3-4" role="tabpanel" aria-labelledby="tabs-3-4-tab">
                              <div class="row "> <div class="col-lg-12"> <h3>Curva "S"</h3></div> </div>
                              <div class="row" id="documento3-4">
                                <!-- Aqui va - Curva S -->                                
                              </div>
                            </div>

                            <!-- 4 Cronograma de obra valorizado -->
                            <div class="tab-pane fade" id="tabs-4" role="tabpanel" aria-labelledby="tabs-4-tab">
                              <div class="row "> <div class="col-lg-12"> <h3>Cronograma de obra valorizado</h3></div> </div>
                              <div class="row" id="documento4">
                                <!-- Aqui va - Cronograma de obra valorizado -->                                
                              </div>
                            </div>

                            <!-- 5. Protocolo de calidad -->
                            <div class="tab-pane fade" id="tabs-5" role="tabpanel" aria-labelledby="tabs-5-tab">
                              Doc 5
                            </div>

                            <!-- 5.1 Ensayo de consistencia del concreto -->
                            <div class="tab-pane fade" id="tabs-5-1" role="tabpanel" aria-labelledby="tabs-5-2-tab">
                              <div class="row"> <div class="col-lg-12"> <h3>Ensayo de consistencia del concreto</h3></div> </div>
                              <div class="row" id="documento5-1">
                                <!-- Aqui va - Ensayo de consistencia del concreto -->                                
                              </div>
                            </div>

                            <!-- 5.2 Ensayo de compresión -->
                            <div class="tab-pane fade" id="tabs-5-2" role="tabpanel" aria-labelledby="tabs-5-2-tab">
                              <div class="row"> <div class="col-lg-12"> <h3>Ensayo de Compresión</h3></div> </div>
                              <div class="row" id="documento5-2">
                                <!-- Aqui va - Ensayo de compresión -->                                
                              </div>
                              <div class="row mt-4"> <div class="col-lg-12"> <h3>Respuesta de Ensayo de Compresión</h3></div> </div>
                              <div class="row mt-3" id="documento5-2-1">
                                <!-- Aqui va - Ensayo de compresión -->                                
                              </div>
                            </div>

                            <!-- 6. Plan de seguridad y salud en el trabajo -->
                            <div class="tab-pane fade" id="tabs-6" role="tabpanel" aria-labelledby="tabs-6-tab">
                              <div class="row"> <div class="col-lg-12"> <h3>Plan de seguridad y salud en el trabajo</h3></div> </div>
                              <div class="row" id="documento6">
                                <!-- Aqui va - Plan de seguridad y salud en el trabajo -->                                
                              </div>
                            </div>

                            <!-- 7. Plan de bioseguridad COVID19 -->
                            <div class="tab-pane fade" id="tabs-7" role="tabpanel" aria-labelledby="tabs-7-tab">
                              <div class="row"> <div class="col-lg-12"> <h3>Plan de bioseguridad COVID19</h3></div> </div>
                              <div class="row" id="documento7">
                                <!-- Aqui va - Plan de bioseguridad COVID19 -->                                
                              </div>
                            </div>

                            <!-- 8. Anexos -->
                            <div class="tab-pane fade" id="tabs-8" role="tabpanel" aria-labelledby="tabs-8-tab">
                              Doc 8
                            </div>

                            <!-- 8.1 Acta de entrega de terreno -->                            
                            <div class="tab-pane fade" id="tabs-8-1" role="tabpanel" aria-labelledby="tabs-8-1-tab">
                              <div class="row"> <div class="col-lg-12"> <h3>Acta de entrega de terreno</h3></div> </div>
                              <div class="row" id="documento8-1">
                                <!-- Aqui va - Acta de entrega de terreno -->                                
                              </div>
                            </div>

                            <!-- 8.2 Acta de inicio de obra -->                            
                            <div class="tab-pane fade" id="tabs-8-2" role="tabpanel" aria-labelledby="tabs-8-2-tab">
                              <div class="row"> <div class="col-lg-12"> <h3>Acta de inicio de obra</h3></div> </div>
                              <div class="row" id="documento8-2">
                                <!-- Aqui va - Acta de inicio de obra -->                                
                              </div>
                            </div>

                            <!-- 8.3 Certificado de habilidad del ingeniero residente -->                            
                            <div class="tab-pane fade" id="tabs-8-3" role="tabpanel" aria-labelledby="tabs-8-3-tab">
                              <div class="row"> <div class="col-lg-12"> <h3>Certificado de habilidad del ingeniero residente </h3></div> </div>
                              <div class="row" id="documento8-3">
                                <!-- Aqui va - Certificado de habilidad del ingeniero residente  -->                                
                              </div>
                            </div>

                            <!-- 8.4 Planilla del personal obrero -->                            
                            <div class="tab-pane fade" id="tabs-8-4" role="tabpanel" aria-labelledby="tabs-8-4-tab">
                              <div class="row"> <div class="col-lg-12"> <h3>Planilla del personal obrero</h3></div> </div>
                              <div class="row" id="documento8-4">
                                <!-- Aqui va - Planilla del personal obrero -->                                
                              </div>
                            </div>

                            <!-- 8.5 Copia del seguro complementario contra todo riesgo -->                            
                            <div class="tab-pane fade" id="tabs-8-5" role="tabpanel" aria-labelledby="tabs-8-5-tab">
                              <div class="row"> <div class="col-lg-12"> <h3>Copia del seguro complementario contra todo riesgo</h3></div> </div>
                              <div class="row" id="documento8-5">
                                <!-- Aqui va - Copia del seguro complementario contra todo riesgo -->                                
                              </div>
                            </div>

                            <!-- 8.6 Panel fotográfico -->                            
                            <div class="tab-pane fade" id="tabs-8-6" role="tabpanel" aria-labelledby="tabs-8-6-tab">
                              <div class="row"> <div class="col-lg-12"> <h3>Panel fotográfico</h3></div> </div>
                              <div class="row" id="documento8-6">
                                <!-- Aqui va - Panel fotográfico -->                                
                              </div>
                            </div>

                            <!-- 8.7 Copia del cuaderno de obra -->
                            <div class="tab-pane fade" id="tabs-8-7" role="tabpanel" aria-labelledby="tabs-8-7-tab">
                              <div class="row"> <div class="col-lg-12"> <h3>Copia del cuaderno de obra</h3></div> </div>
                              <div class="row" id="documento8-7">
                                <!-- Aqui va - Copia del cuaderno de obra -->                                
                              </div>
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
                        <!-- nombre -->
                        <input type="hidden" name="nombre" id="nombre" />
                        <!-- fecha inicio -->
                        <input type="hidden" name="fecha_inicio" id="fecha_inicio" />
                        <!-- fecha fin -->
                        <input type="hidden" name="fecha_fin" id="fecha_fin" />
                        <!-- fecha numero_q_s -->
                        <input type="hidden" name="numero_q_s" id="numero_q_s" />                          

                        <!-- Doc Valorizaciones -->
                        <div class="col-md-12 col-lg-12" >                               
                          <div class="row text-center">
                            <div class="col-md-12" style="padding-top: 15px; padding-bottom: 5px;">
                              <label for="cip" class="control-label" >Documento </label>
                            </div>
                            <div class="col-md-6 text-center">
                              <button type="button" class="btn btn-success btn-block btn-xs" id="doc7_i">
                                <i class="fas fa-file-upload"></i> Subir.
                              </button>
                              <input type="hidden" id="doc_old_7" name="doc_old_7" />
                              <input style="display: none;" id="doc7" type="file" name="doc7" accept=".xlsx, .xlsm, .xls, .csv, .pdf, .doc, .docx" class="docpdf" /> 
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

                        <!-- barprogress -->
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
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
