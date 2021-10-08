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
        <title>Admin Sevens | trabajadors</title>
        <?php
        require 'head.php';
        ?>
    </head>
    <body class="hold-transition sidebar-mini">
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
                                        <h3 class="card-title">
                                            <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-trabajador" onclick="limpiar();"><i class="fas fa-user-plus"></i> Agregar</button>
                                            Admnistra de manera eficiente a los trabajdores
                                        </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <table id="tabla-trabajadors" class="table table-bordered table-striped display">
                                            <thead>
                                                <tr>
                                                    <th class="">Aciones</th>
                                                    <th>Nombres</th>
                                                    <th>Telefono</th>
                                                    <th>Email</th>
                                                    <th>Direccion</th>
                                                    <th>Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Aciones</th>
                                                    <th>Nombres</th>
                                                    <th>Telefono</th>
                                                    <th>Email</th>
                                                    <th>Direccion</th>
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

                    <!-- Modal agregar trabajador -->
                    <div class="modal fade" id="modal-agregar-trabajador">
                        <div class="modal-dialog modal-dialog-scrollable modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Agregar trabajador</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span class="text-danger" aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <!-- form start -->
                                    <form id="form-trabajador" name="form-trabajador" method="POST">
                                        <div class="card-body">
                                            <div class="row" id="cargando-1-fomulario">
                                                <!-- id trabajador -->
                                                <input type="hidden" name="idtrabajador" id="idtrabajador" />

                                                <!-- Tipo de documento -->
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label for="tipo_documento">Tipo de documento</label>
                                                        <select name="tipo_documento" id="tipo_documento" class="form-control" placeholder="Tipo de documento">
                                                            <option selected value="DNI">DNI</option>
                                                            <option value="RUC">RUC</option>
                                                            <option value="CEDULA">CEDULA</option>
                                                            <option value="OTRO">OTRO</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <!-- N° de documento -->
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label for="num_documento">N° de documento</label>
                                                        <div class="input-group">
                                                            <input type="number" name="num_documento" class="form-control" id="num_documento" placeholder="N° de documento" />
                                                            <div class="input-group-append" data-toggle="tooltip" data-original-title="Buscar Reniec/SUNAT" onclick="buscar_sunat_reniec();">
                                                                <span class="input-group-text" style="cursor: pointer;">
                                                                    <i class="fas fa-search text-primary" id="search"></i>
                                                                    <i class="fa fa-spinner fa-pulse fa-fw fa-lg text-primary" id="charge" style="display: none;"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Nombre -->
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label for="nombre">Nombre y Apellidos/Razon Social</label>
                                                        <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Nombres y apellidos" />
                                                    </div>
                                                </div>
                                                <!-- Correo electronico -->
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label for="email">Correo electrónico</label>
                                                        <input type="email" name="email" class="form-control" id="email" placeholder="Correo electrónico" />
                                                    </div>
                                                </div>
                                                <!-- Direccion -->
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label for="direccion">Dirección</label>
                                                        <input type="text" name="direccion" class="form-control" id="direccion" placeholder="Dirección" />
                                                    </div>
                                                </div>
                                                <!-- Telefono -->
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label for="telefono">Teléfono</label>
                                                        <input type="text" name="telefono" id="telefono" class="form-control" data-inputmask="'mask': ['999-999-999', '+099 99 99 999']" data-mask />
                                                    </div>
                                                </div>
                                                <!-- fecha de nacimiento -->
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="fecha_nacimiento">Fecha Nacimiento</label>
                                                        <input type="date" class="form-control" name="nacimiento" id="nacimiento"placeholder="Fecha de Nacimiento" onclick="edades();" onchange="edades();">  
                                                    </div>
                                                </div>
                                                <!-- edad -->
                                                <div class="col-lg-1">
                                                    <div class="form-group">
                                                        <label for="edad">Edad</label>
                                                        <p id="p_edad" style="border: 1px solid #ced4da;border-radius: 4px;padding: 5px;" >0 años.</p> 
                                                    </div>
                                                </div>
                                                <!-- Tipo trabajador -->
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="tipo_trabajador">Tipo trabajador</label>
                                                        <select name="tipo_trabajador" id="tipo_trabajador" class="form-control select2" style="width: 100%;">
                                                            <option value="Técnico">Técnico</option>
                                                            <option value="Obrero">Obrero</option>
                                                        </select>
                                                        <small id="tipo_trab_validar" class="text-danger" style="display: none;">Por favor selecione un tipo trabajador </small>
                                                    </div>
                                                </div>
                                                <!-- cargo -->
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="cargo">Cargo</label>
                                                        <select name="cargo" id="cargo" class="form-control select2" style="width: 100%;">
                                                            <option value="Maestro">Maestro</option>
                                                            <option value="Operario">Operario</option>
                                                            <option value="Oficial">Oficial</option>
                                                            <option value="Peón">Peón</option>
                                                        </select>
                                                        <small id="cargo_validar" class="text-danger" style="display: none;">Por favor selecione un cargo</small>
                                                    </div>
                                                </div>
                                                <!-- Desempeño -->
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label for="desempeño">Desempeño</label>
                                                        <input type="text" name="desempenio" class="form-control" id="desempenio" placeholder="Desempeño" />
                                                    </div>
                                                </div>
                                                <!-- Cuenta bancaria -->
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label for="c_bancaria">Cuenta Bancaria</label>
                                                        <input type="number" name="c_bancaria" class="form-control" id="c_bancaria" placeholder="Cuenta Bancaria" />
                                                    </div>
                                                </div>
                                                <!-- banco -->
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label for="banco">Banco</label>
                                                        <select name="banco" id="banco" class="form-control select2" style="width: 100%;">
                                                            <option value="1">BCP</option>
                                                            <option value="2">BBVA</option>
                                                            <option value="3">SCOTIA BANK</option>
                                                            <option value="4">INTERBANK</option>
                                                            <option value="5">NACIÓN</option>
                                                        </select>
                                                        <small id="banco_validar" class="text-danger" style="display: none;">Por favor selecione un cargo</small>
                                                    </div>
                                                </div>
                                                <!-- Titular de la cuenta -->
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label for="tutular_cuenta">Titular de la cuenta</label>
                                                        <input type="text" name="tutular_cuenta" class="form-control" id="tutular_cuenta" placeholder="Titular de la cuenta" />
                                                    </div>
                                                </div>
                                                <!-- Sueldo(Mensual) -->
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label for="sueldo_mensual">Sueldo(Mensual)</label>
                                                        <input type="number" step="0.10" name="sueldo_mensual" class="form-control" id="sueldo_mensual" onclick="sueld_mensual();" onkeyup="sueld_mensual();" />
                                                    </div>
                                                </div>
                                                <!-- Sueldo(Diario) -->
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label for="sueldo_diario">Sueldo( 24 Diario)</label>
                                                        <input type="number" step="0.10" name="sueldo_diario" class="form-control" id="sueldo_diario" readonly />
                                                    </div>
                                                </div>
                                                <!-- Sueldo(Hora) -->
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label for="sueldo_hora">Sueldo(8 Hora)</label>
                                                        <input type="number" step="0.10" name="sueldo_hora" class="form-control" id="sueldo_hora" readonly/>
                                                    </div>
                                                </div>
                                                <!-- imagen -->
                                                <div class="col-lg-3">
                                                    <label for="foto2">Foto del trabajador</label>
                                                    <img
                                                        onerror="this.src='../dist/img/default/img_defecto.png';"
                                                        src="../dist/img/default/img_defecto.png"
                                                        class="img-thumbnail"
                                                        id="foto2_i"
                                                        style="cursor: pointer !important;"
                                                        width="auto"
                                                    />
                                                    <input style="display: none;" type="file" name="foto2" id="foto2" accept="image/*" />
                                                    <input type="hidden" name="foto2_actual" id="foto2_actual" />
                                                    <div class="text-center" id="foto2_nombre"><!-- aqui va el nombre de la FOTO --></div>
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

                    <!--Modal ver trabajador-->
                    <div class="modal fade" id="modal-ver-trabajador">
                        <div class="modal-dialog modal-dialog-scrollable modal-xm">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Datos trabajador</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span class="text-danger" aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <div id="datostrabajador" class="class-style">
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

        <script type="text/javascript" src="scripts/trabajador.js"></script>

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
