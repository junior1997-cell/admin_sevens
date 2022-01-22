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
    <title>Admin Sevens | trabajadores</title>
    <?php
    require 'head.php';
    ?>
    <style>
        .tablee {
          width: 100%;
          border: 1px solid #000;
        }
        th, td {
          width: 25%;
          text-align: left;
          vertical-align: top;
          border: 1px solid #000;
          border-collapse: collapse;
          padding: 0.3em;
        }
        caption {
          padding: 0.3em;
        }
    </style>

  </head>
  <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed">
    <!-- Content Wrapper. Contains page content -->
    <div class="wrapper">
      <?php
      require 'nav.php';
      require 'aside.php';
      if ($_SESSION['trabajador']==1){
      ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
          <!-- Content Header (Page header) -->
          <section class="content-header">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <h1>Trabajador</h1>
                </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Trabajador</li>
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
                      <!-- regresar -->
                      <h3 class="card-title mr-3" id="card-regresar" style="display: none;" style="padding-left: 2px;">
                        <button type="button" class="btn bg-gradient-warning" onclick="mostrar_form_table(1);despintar_btn_select();" style="height: 61px;"><i class="fas fa-arrow-left"></i> Regresar</button>
                      </h3>
                      <!-- Editar -->
                      <h3 class="card-title mr-3" id="card-editar" style="display: none; padding-left: 2px;">
                        <button type="button" class="btn bg-gradient-orange" onclick="editarbreak();" style="height: 61px;"><i class="fas fa-pencil-alt"></i> Editar</button>
                      </h3>
                      <!-- Guardar -->
                      <h3 class="card-title mr-3" id="card-guardar" style="display: none; padding-left: 2px;">
                        <button type="button" class="btn bg-gradient-success" onclick="guardaryeditar_semana_break();" style="margin-right: 10px; height: 61px;"><i class="far fa-save"></i> Guardar</button>
                      </h3>
                      <!-- Botones de quincenas -->
                      <div id="Lista_breaks" class="row-horizon disenio-scroll " >
                        <!-- <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-asistencia" onclick="limpiar();"><i class="fas fa-user-plus"></i> Agregar </button>
                        <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-asistencia" onclick="limpiar();"><i class="fas fa-user-plus"></i> Agregar </button>-->
                      </div>   
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <!-- Lista de trabajdores -->
                      <div id="mostrar-tabla">
                        <table id="tabla-trabajadors" class="table table-bordered table-striped display" style="width: 100% !important;">
                          <thead>
                            <tr>
                              <th class="">Aciones</th>
                              <th>Nombres</th>
                              <th>Cuenta bancaria</th>
                              <th>Sueldo mensual</th>
                              <th>Tipo / cargo</th>
                              <th>Estado</th>
                            </tr>
                          </thead>
                          <tbody></tbody>
                          <tfoot>
                            <tr>
                              <th>Aciones</th>
                              <th>Nombres</th>
                              <th>Cuenta bancaria</th>
                              <th>Sueldo mensual</th>
                              <th>Tipo / cargo</th>
                              <th>Estado</th>
                            </tr>
                          </tfoot>
                        </table>
                      </div>

                      <!-- agregar trabajador al sistema -->
                      <div id="tabla-registro" style="display: none;">
                        <style>
                          table.colapsado {border-collapse: collapse;}
                        </style>
                        <div class="container table-responsive disenio-scroll">

                          <table class="table table-hover text-nowrap styletabla" style="border: black 1px solid;" border="1" style="width: 100%;" >
                              <thead style="background-color: #408c98; color: white;" >
                                <tr>
                                  <th class="text-center w-px-300">Día</th>
                                  <th class="text-center  w-px-135">Cantidad</th>
                                  <th class="text-center"> Parcial</th>
                                  <th class="text-center"> Descripción</th>
                                </tr>
                              </thead>
                              <tbody id="data_table_body">
                                <!--aqui va el listado de los días-->
                              </tbody>
                              <tfoot>
                                <th style="border-bottom: hidden;border-left: hidden;" ></th>
                                <th  class="text-center">Total</th>
                                <th id="monto_total" >----</th>
                                <th style="border-bottom: hidden;border-right: hidden;" ></th>
                              </tfoot>
                          </table>

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

            <!-- Modal cargando -->
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
                      <div class="progress h-px-30" id="div_barra_progress">
                        <div id="barra_progress" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                          0%
                        </div>
                      </div>
                    </div>
                    
                    <!-- boton -->
                    <div class="swal2-actions">
                      <div class="swal2-loader"></div>
                      <button onclick="cerrar_modal()" type="button" class="swal2-confirm swal2-styled" aria-label="" style="display: inline-block;">OK</button>
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

    <script type="text/javascript" src="scripts/break.js"></script>

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
