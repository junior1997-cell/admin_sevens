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
    <title>Admin Sevens | Calendario Global</title>

    <!-- Icono Sevens-Ingenieros-SAC -->
    <link rel="apple-touch-icon" href="../dist/svg/logo-icono.svg">
    <link rel="shortcut icon" href="../dist/svg/logo-icono.svg">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- fullCalendar -->
    <link rel="stylesheet" href="../plugins/fullcalendar/main.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">

  </head>
  <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed">
    <!-- Content Wrapper. Contains page content -->
    <div class="wrapper">
      <?php
      require 'nav.php';
      require 'aside.php';
      if ($_SESSION['calendario']==1){
      ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
          <!-- Content Header (Page header) -->
          <section class="content-header">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <h1>Calendario Global</h1>
                </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="all_calendario.php">Home</a></li>
                    <li class="breadcrumb-item active">Calendario</li>
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
                  <div class="card card-primary card-outline card-tabs">
                    
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-3">
                          <div class="sticky-top mb-3">
                            <div class="card">
                              <div class="card-header">
                                <h4 class="card-title">Lista de feriados</h4>
                              </div>
                              <div class="card-body">
                                <!-- the events -->
                                <div id="external-events" >                                     
                                                                  
                                </div>
                              </div>
                              <!-- /.card-body -->
                            </div>

                            <div class="card card-secondary">
                              <div class="card-header">
                                <h3 class="card-title">Feriados eliminados</h3>
                              </div>
                              <div class="card-body" id="external-events-eliminados">
                                
                              </div>
                            </div>
                            
                          </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-md-9">
                          <div class="card card-primary">
                            <div class="card-body p-0">
                              <!-- THE CALENDAR -->
                              <div id="calendar"></div>
                            </div>
                            <!-- /.card-body -->
                          </div>
                          <!-- /.card -->
                        </div>
                        <!-- /.col -->
                      </div>
                    </div>
                  </div>
                  <!-- /.card -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- /.container-fluid -->

            <!-- Modal agregar trabajador -->
            <div class="modal fade" id="modal-agregar-calendario">
              <div class="modal-dialog modal-dialog-scrollable modal-md">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Agregar Feriado</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span class="text-danger" aria-hidden="true">&times;</span>
                    </button>
                  </div>

                  <div class="modal-body">
                    <!-- form start -->
                    <form id="form-calendario" name="form-calendario" method="POST">
                      <div class="card-body" style="padding-top: 0px !important;">
                        <div class="row" id="cargando-1-fomulario">

                          <!-- id calendario -->
                          <input type="hidden" name="idcalendario" id="idcalendario" />
                          <!-- id proyecto -->
                          <input type="hidden" name="idproyecto" id="idproyecto" />          
                          <!-- fecha -->
                          <input type="hidden" name="fecha_feriado"  id="fecha_feriado" />
                          <!-- text color -->
                          <input type="hidden" name="text_color" id="text_color" value="#ffffff" />

                          <!-- fecha seleccionada  -->
                          <div class="col-lg-12 text-center mb-3">
                            <div class="external-event bg-warning">
                              <h4 id="fecha_select" style="margin-bottom: 0 !important;">2021/10/23</h4>
                            </div>                            
                          </div>

                          <!-- Titulo -->
                          <div class="col-lg-6">
                            <div class="form-group">
                              <label for="titulo">Titulo</label>
                              <input type="text" name="titulo" class="form-control" id="titulo" placeholder="Titulo" value="Feriado"/>
                            </div>
                          </div>                                                    

                          <!-- Background Color -->
                          <div class="col-lg-6">
                            <div class="form-group">
                              <label for="background_color">Color</label>
                              <input type="color" name="background_color" id="background_color" class="form-control" value="#dc3545" onchange="contraste();" />
                            </div>
                          </div>                        

                          <!-- Descripcion -->
                          <div class="col-lg-12">
                            <div class="form-group">
                              <label for="descripcion">Descripcion</label>
                              <textarea name="descripcion" id="descripcion" class="form-control" rows="3" placeholder="Ingrese descripción"></textarea>
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
                      <button type="submit" style="display: none;" id="submit-form-calendario">Submit</button>
                    </form>
                  </div>
                  <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn btn-danger" id="eliminar_registro">Eliminar</button>
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

    <!-- jQuery -->
    <script src="../plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery UI -->
    <script src="../plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.min.js"></script>
    <!-- fullCalendar 2.2.5 -->
    <script src="../plugins/moment/moment.min.js"></script>
    <script src="../plugins/fullcalendar/main.js"></script>

    <!-- sweetalert2 -->
    <script src="../plugins/sweetalert2/sweetalert2.all.min.js"></script>
    
    <script src="../plugins/fullcalendar/locales/es.js"></script>
    <script src="../plugins/fullcalendar/locales-all.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="../dist/js/demo.js"></script>

    <!-- jquery-validation -->
    <script src="../plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="../plugins/jquery-validation/additional-methods.min.js"></script>

    <script type="text/javascript" src="scripts/all_calendario.js"></script>

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