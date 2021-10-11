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
        <title>Admin Sevens | asistencia</title>
        <?php
        require 'head.php';
        ?>
    </head>
    <body class="hold-transition sidebar-collapse sidebar-mini layout-fixed layout-navbar-fixed">
        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper">
            <?php
          require 'nav.php';
          require 'aside.php';
          if ($_SESSION['escritorio']==1){
          ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Asistencia</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">asistencia</li>
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
                                        <h3 class="card-title">
                                            <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-asistencia" onclick="limpiar();"><i class="fas fa-user-plus"></i> Agregar</button>
                                            Admnistra de manera eficiente a tus asistencia.
                                        </h3>
                                    </div>
                                    
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <table id="tabla-asistencia" class="table table-bordered table-striped display" style="width: 100% !important;">
                                            <thead>
                                                <tr>
                                                    <th class="">Aciones</th>
                                                    <th>Nombre</th>
                                                    <th>Horas</th>
                                                    <th>Horas extras </th>
                                                    <th>Sueldo mensual </th>
                                                    <th>Pago / hora</th>
                                                    <th>Jonal diario</th>
                                                    <th>Sabatical</th>
                                                    <th>Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Aciones</th>
                                                    <th>Nombre</th>
                                                    <th>Horas</th>
                                                    <th>Horas extras </th>
                                                    <th>Sueldo mensual </th>
                                                    <th>Pago / hora</th>
                                                    <th>Jonal diario</th>
                                                    <th>Sabatical</th>
                                                    <th>Estado</th>
                                                </tr>
                                            </tfoot>
                                        </table>
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

                    <!-- Modal agregar asistencia -->
                    <div class="modal fade" id="modal-agregar-asistencia">
                        <div class="modal-dialog /*modal-dialog-scrollable*/ modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Agregar asistencia</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span class="text-danger" aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <!-- form start -->
                                    <form id="form-asistencia" name="form-asistencia" method="POST">
                                        <div class="card-body">
                                            <div class="row" id="cargando-1-fomulario">
                                                <!-- id asistencia -->
                                                <input type="hidden" name="idasistencia" id="idasistencia" />

                                                
                                                <!-- Trabajador -->
                                                <div class="col-lg-7">
                                                <div class="form-group">
                                                    <label for="trabajador" id="trabajador_c">Trabajador</label>                               
                                                    <select name="trabajador" id="trabajador" class="form-control select2" style="width: 100%;" onchange="seleccion();" >
                                                    
                                                    </select>
                                                    <small id="trabajador_validar" class="text-danger" style="display: none;">Por favor selecione un trabajador</small>  
                                                </div>                                                        
                                                </div>
                                                <!-- Horas de trabajo -->
                                                <div class="col-lg-5">
                                                    <div class="form-group">
                                                        <label for="horas_tabajo">Horas de trabajo</label>
                                                        <!-- <input type="time" name="horas_tabajo" class="form-control" id="horas_tabajo" placeholder="Ingrese las horas de trabajo" /> -->
                                                    

                                                        <div class="input-group date" id="timepicker" data-target-input="nearest">
                                                            <input id="horas_tabajo" name="horas_tabajo" placeholder="Ingrese las horas de trabajo" type="text" class="form-control datetimepicker-input" data-target="#timepicker" data-inputmask='"mask": "99:99"' data-mask/>
                                                            <div class="input-group-append" data-target="#timepicker" data-toggle="datetimepicker">
                                                                <div class="input-group-text"><i class="far fa-clock"></i></div>
                                                            </div>
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
                                        <button type="submit" style="display: none;" id="submit-form-asistencia">Submit</button>
                                    </form>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success" id="guardar_registro">Guardar Cambios</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--Modal ver asistencia-->
                    <div class="modal fade" id="modal-ver-asistencia">
                        <div class="modal-dialog modal-dialog-scrollable modal-xm">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Datos asistencia</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span class="text-danger" aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <div id="datosasistencia" class="class-style">
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

        <script type="text/javascript" src="scripts/registro_asistencia.js"></script>

        <script>
            $(function () {
                $('[data-toggle="tooltip"]').tooltip();
            });
        </script>
    </body>
</html>

<?php  
  }
  ob_end_flush();

?>
