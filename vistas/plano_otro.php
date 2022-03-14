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
    <title>Admin Sevens | Planos y otros</title>
    <?php
    require 'head.php';
    ?>
  </head>
  <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed">
    <!-- Content Wrapper. Contains page content -->
    <div class="wrapper">
      <?php
      require 'nav.php';
      require 'aside.php';
      if ($_SESSION['recurso']==1){
      ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
          <!-- Content Header (Page header) -->
          <section class="content-header">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <h1>Planos y otros</h1>
                </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="plano_otro.php">Home</a></li>
                    <li class="breadcrumb-item active">Plano y otros</li>
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
                      <h3 class="card-title" id="title-1">
                        <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-carpeta" onclick="limpiar();"><i class="fas fa-folder-plus"></i> Agregar</button>
                        Admnistra de manera eficiente tus carpetas.
                      </h3>
                      <h3 class="card-title" id="title-2" style="display: none !important;">
                        <button type="button" class="btn bg-gradient-warning"  onclick="regresar();"><i class="fas fa-arrow-left"></i> Regresar</button>
                        <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-planootros" onclick="limpiar();"><i class="fas fa-file-contract"></i> Agregar</button>
                        Admnistra de manera eficiente tus plano y otros archivos.
                      </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <div id="ver-tabla-carpeta">
                        <!-- Lista de carpeta -->                      
                        <table id="tabla-carpeta" class="table table-bordered table-striped display" style="width: 100% !important;">
                          <thead>
                            <tr>
                              <th class="text-center">#</th>
                              <th class="">Aciones</th>
                              <th>Nombres</th>
                              <th>Descripción</th>
                              <th>Estado</th>
                            </tr>
                          </thead>
                          <tbody></tbody>
                          <tfoot>
                            <tr>
                              <th class="text-center">#</th>
                              <th class="">Aciones</th>
                              <th>Nombres</th>
                              <th>Descripción</th>
                              <th>Estado</th>
                            </tr>
                          </tfoot>
                        </table>
                      </div>

                      <div id="ver-tabla-plano" style="display: none !important;">
                        <!-- Lista de carpeta -->                      
                        <table id="tabla-planos-otros" class="table table-bordered table-striped display" style="width: 100% !important;">
                          <thead>
                            <tr>
                              <th class="text-center">#</th>
                              <th class="">Aciones</th>
                              <th>Nombres</th>
                              <th>Descripción</th>
                              <th>Doc.</th>
                              <th>Estado</th>
                            </tr>
                          </thead>
                          <tbody></tbody>
                          <tfoot>
                            <tr>
                              <th class="text-center">#</th>
                              <th class="">Aciones</th>
                              <th>Nombres</th>
                              <th>Descripción</th>
                              <th>Doc.</th>
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

            <!-- Modal agregar CARPETA -->
            <div class="modal fade" id="modal-agregar-carpeta">
              <div class="modal-dialog modal-dialog-scrollable modal-md">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Agregar carpeta</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span class="text-danger" aria-hidden="true">&times;</span>
                    </button>
                  </div>

                  <div class="modal-body">
                    <!-- form start -->
                    <form id="form-carpeta" name="form-carpeta" method="POST">
                      <div class="card-body">
                        <div class="row" id="cargando-1-fomulario">

                          <!-- id planos -->
                          <input type="hidden" name="idproyecto" id="idproyecto" />
                          <!-- id carpeta -->
                          <input type="hidden" name="idcarpeta" id="idcarpeta" />

                          <!-- Nombre -->
                          <div class="col-lg-12">
                            <div class="form-group">
                              <label for="nombre">Nombre <small class="text-orange">(del archivo subido)</small></label>
                              <input type="text" name="nombre_carpeta" class="form-control" id="nombre_carpeta" placeholder="Nombre" />
                            </div>
                          </div>

                          <!-- Descripcion -->
                          <div class="col-lg-12">
                            <div class="form-group">
                              <label for="descripcion">Descripcion</label>
                              <textarea name="descripcion_carpeta" id="descripcion_carpeta" class="form-control" rows="3" placeholder="Ingrese descripción"></textarea>
                            </div>
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
                      </div>
                      <!-- /.card-body -->
                      <button type="submit" style="display: none;" id="submit-form-carpeta">Submit</button>
                    </form>
                  </div>
                  <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" id="guardar_registro">Guardar Cambios</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Modal agregar Planos -->
            <div class="modal fade" id="modal-agregar-planootros">
              <div class="modal-dialog modal-dialog-scrollable modal-md">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Agregar Documentos</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span class="text-danger" aria-hidden="true">&times;</span>
                    </button>
                  </div>

                  <div class="modal-body">
                    <!-- form start -->
                    <form id="form-plano-otro" name="form-plano-otro" method="POST">
                      <div class="card-body">
                        <div class="row" id="cargando-3-fomulario">

                          <!-- id planos -->
                          <input type="hidden" name="idplano_otro" id="idplano_otro" />
                          <!-- id proyecto -->
                          <input type="hidden" name="id_carpeta" id="id_carpeta" />

                          <!-- Nombre -->
                          <div class="col-lg-12">
                            <div class="form-group">
                              <label for="nombre">Nombre <small class="text-orange">(del archivo subido)</small></label>
                              <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Nombre" />
                            </div>
                          </div>

                          <!-- Descripcion -->
                          <div class="col-lg-12">
                            <div class="form-group">
                              <label for="descripcion">Descripcion</label>
                              <textarea name="descripcion" id="descripcion" class="form-control" rows="3" placeholder="Ingrese descripción"></textarea>
                            </div>
                          </div>

                          <!-- Doc  -->
                          <div class="col-md-12 col-lg-12" >                               
                            <div class="row text-center">
                              <div class="col-md-12" style="padding-top: 15px; padding-bottom: 5px;">
                                <label for="cip" class="control-label" >Documento </label>
                              </div>
                              <div class="col-md-6 text-center">
                                <button type="button" class="btn btn-success btn-block btn-xs" id="doc1_i">
                                  <i class="fas fa-file-upload"></i> Subir.
                                </button>
                                <input type="hidden" id="doc_old_1" name="doc_old_1" />
                                <input style="display: none;" id="doc1" type="file" name="doc1"  class="docpdf" /> 
                              </div>
                              <div class="col-md-6 text-center">
                                <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion();">
                                  <i class="fa fa-eye"></i> Doc.
                                </button>
                              </div>
                            </div>                              
                            <div id="doc1_ver" class="text-center mt-4">
                              <img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >
                            </div>
                            <div class="text-center" id="doc1_nombre"><!-- aqui va el nombre del pdf --></div>
                          </div>

                          <!-- barprogress -->
                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                            <div class="progress" id="div_barra_progress2">
                              <div id="barra_progress2" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
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
                      <button type="submit" style="display: none;" id="submit-form-planootro">Submit</button>
                    </form>
                  </div>
                  <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" id="guardar_registro_2">Guardar Cambios</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Modal ver los documentos subidos -->
            <div class="modal fade" id="modal-ver-docs">
              <div class="modal-dialog modal-dialog-scrollable modal-md">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Documentos subidos</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span class="text-danger" aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  
                  <div class="modal-body">
                    <div class="row" >

                      <!-- Pdf 1 -->
                      <div class="col-md-12 col-lg-12 mb-4" >
                        <div class="text-center mb-4" id="verdoc1_nombre">
                          <!-- aqui va el nombre del pdf -->
                        </div>                          

                        <div id="verdoc1" class="text-center">
                          <i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>
                          <h4>Cargando...</h4>
                        </div>
                                                  
                      </div>

                    </div>                      
                  </div>

                  <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-danger"  data-dismiss="modal">Close</button>
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
        .text_area_clss {
            width: 100%;
            height: auto;
            background: rgb(215 224 225 / 22%);
            border-block-color: inherit;
            border-bottom: aliceblue;
            border-left: aliceblue;
            border-right: aliceblue;
            border-top: hidden;
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

    <script type="text/javascript" src="scripts/plano_otro.js"></script>

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
