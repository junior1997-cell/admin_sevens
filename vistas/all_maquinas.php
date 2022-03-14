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
        <title>Admin Sevens | Maquinarias</title>
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
          if ($_SESSION['recurso']==1){
          ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Máquinaria y Equipos</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Máquinarias y Equipos</li>
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
                                            <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-maquinaria" onclick="limpiar();"><i class="fas fa-user-plus"></i> Agregar</button>
                                            Admnistra tus máquinarias y/o equipos.
                                        </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <h1 style="text-align: center;background-color: aliceblue;">Máquinas</h1>
                                        <table id="tabla-maquinas" class="table table-bordered table-striped display" style="width: 100% !important;">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">Aciones</th>                                                    
                                                    <th>Nombre máquina</th>
                                                    <th>Modelo</th>
                                                    <th>Proveedor</th>
                                                    <th>Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">Aciones</th>                                                    
                                                    <th>Nombre máquina</th>
                                                    <th>Modelo</th>
                                                    <th>Proveedor</th>
                                                    <th>Estado</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <!-- /.card-body -->
                                    <!-- /.card-equipos -->
                                    <div class="card-body">
                                        <h1 style="text-align: center;background-color: aliceblue;">Equipos</h1>
                                        <table id="tabla-equipos" class="table table-bordered table-striped display" style="width: 100% !important;">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">Aciones</th>                                                    
                                                    <th>Nombre equipo</th>
                                                    <th>Modelo</th>
                                                    <th>Proveedor</th>
                                                    <th>Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">Aciones</th>                                                    
                                                    <th>Nombre equipo</th>
                                                    <th>Modelo</th>
                                                    <th>Proveedor</th>
                                                    <th>Estado</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <!-- /.card-equipos -->
                                </div>
                                <!-- /.card -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.container-fluid -->

                    <!-- Modal agregar proveedores -->
                    <div class="modal fade" id="modal-agregar-maquinaria">
                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Agregar Máquina y/o Equipo</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span class="text-danger" aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <!-- form start -->
                                    <form id="form-maquinaria" name="form-maquinaria" method="POST">
                                        <div class="card-body">
                                            <div class="row" id="cargando-1-fomulario">
                                                <!-- id proveedores -->
                                                <input type="hidden" name="idmaquinaria" id="idmaquinaria" />

                                                <!-- cargo -->
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                    <label for="tipo">Tipo</label>
                                                    <select name="tipo" id="tipo" class="form-control select2" style="width: 100%;"  >                                    
                                                        <option value="1">Maquina</option>
                                                        <option value="2">Equipo</option>
                                                    </select>
                                                    <!-- <small id="cargo_validar" class="text-danger" style="display: none;">Por favor selecione un cargo</small> -->
                                                    </div>
                                                </div>
                                                <!-- Nombre Máquina -->
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="nombre_maquina">Nombre Máquina / Equipo</label>
                                                        <input type="text" name="nombre_maquina" class="form-control" id="nombre_maquina" placeholder="Ingrese nombre de una máquina" />
                                                    </div>
                                                </div>
                                                <!-- Codigo de la máquina -->
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="codigo_m">Modelo</label>
                                                        <input type="text" name="codigo_m" class="form-control" id="codigo_m" placeholder="Dirección" />
                                                    </div>
                                                </div>
                                                <!-- Codigo de la máquina -->
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="proveedor">Seleccionar Proveedor</label>
                                                        <select name="proveedor" id="proveedor" class="form-control select2" style="width: 100%;">
                                                        </select>
                                                        <input type="hidden" name="proveedor_old" id="proveedor_old" />
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
                                        <button type="submit" style="display: none;" id="submit-form-maquinaria">Submit</button>
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

        <script type="text/javascript" src="scripts/all_maquinaria.js"></script>

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
