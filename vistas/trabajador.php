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
    <title>Admin Sevens | trabajadores</title>
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
                      <h3 class="card-title">
                        <button type="button" class="btn bg-gradient-success"  onclick="show_hide_form(true); estado_editar(false);"><i class="fas fa-user-plus"></i> Agregar</button>
                        Admnistra de manera eficiente a los trabajdores
                      </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <!-- Lista de trabajdores -->
                      <div id="mostrar-tabla">
                        <table id="tabla-trabajadors" class="table table-bordered table-striped display" style="width: 100% !important;">
                          <thead>
                            <tr>
                              <th class="">#</th>
                              <th class="">Aciones</th>
                              <th>Nombres</th>
                              <th>Fechas-Trabajo</th>
                              <th>Cuenta bancaria</th>
                              <th>Sueldo mensual</th>
                              <th>Tipo / cargo</th>
                              <th>Estado</th>
                            </tr>
                          </thead>
                          <tbody></tbody>
                          <tfoot>
                            <tr>
                            <th class="">#</th>
                              <th>Aciones</th>
                              <th>Nombres</th>
                              <th>Fechas-Trabajo</th>
                              <th>Cuenta bancaria</th>
                              <th>Sueldo mensual</th>
                              <th>Tipo / cargo</th>
                              <th>Estado</th>
                            </tr>
                          </tfoot>
                        </table>
                      </div>

                      <!-- agregar trabajador al sistema -->
                      <div id="mostrar-form" style="display: none;">
                        
                        <!-- form start -->
                        <form id="form-trabajador-proyecto" name="form-trabajador-proyecto" method="POST">
                          <div class="card-body">
                            <div class="row" id="cargando-1-fomulario">
                              <!-- id PROYECTO -->
                              <input type="hidden" name="idproyecto" id="idproyecto" />
                              <input type="hidden" name="idtrabajador_por_proyecto" id="idtrabajador_por_proyecto" />

                              <!-- Trabajador -->
                              <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="trabajador" id="trabajador_c">Trabajador</label>                               
                                  <select name="trabajador" id="trabajador" class="form-control select2" style="width: 100%;" onchange="capture_idtrabajador();"   >                                    
                                  </select>
                                  <input type="hidden" name="trabajador_old" id="trabajador_old" />
                                  <!-- <small id="trabajador_validar" class="text-danger" style="display: none;">Por favor selecione un trabajador</small>   -->
                                </div>                                                        
                              </div>

                              <!-- Tipo trabajador -->
                              <div class="col-lg-2">
                                <div class="form-group">
                                  <label for="tipo_trabajador">Tipo Trabajador</label>                               
                                  <select name="tipo_trabajador" id="tipo_trabajador" class="form-control select2" style="width: 100%;" onchange="captura_idtipo();">                                    
                                  </select>
                                </div>  
                              </div>
                              <!-- Tipo Ocupación -->
                              <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="ocupacion">Ocupación</label>                                   
                                  <input type="text" id="ocupacion" class="form-control" disabled>
                                </div>
                              </div>
                              <!-- cargo -->
                              <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="cargo">Cargo</label>
                                  <select name="cargo" id="cargo" class="form-control select2" style="width: 100%;"  > 
                                  </select>
                                </div>
                              </div>
                              <!-- Desempeño -->
                              <div class="col-lg-6">
                                <div class="form-group">
                                  <label for="desempeño">Desempeño</label>
                                  <input type="text" name="desempenio" class="form-control" id="desempenio" placeholder="Desempeño" />
                                </div>
                              </div>                              
                              
                              <!-- Sueldo(Mensual) -->
                              <div class="col-lg-2">
                                <div class="form-group">
                                  <label for="sueldo_mensual">Sueldo(Mensual)</label>
                                  <input type="number" step="any" name="sueldo_mensual" class="form-control" id="sueldo_mensual" onclick="sueld_mensual();" onkeyup="sueld_mensual();" />
                                </div>
                              </div>
                              <!-- Sueldo(Diario) -->
                              <div class="col-lg-2">
                                <div class="form-group">
                                  <label for="sueldo_diario">Sueldo(Diario)</label>
                                  <input type="number" step="any" name="sueldo_diario" class="form-control" id="sueldo_diario" readonly />
                                </div>
                              </div>
                              <!-- Sueldo(Hora) -->
                              <div class="col-lg-2">
                                <div class="form-group">
                                  <label for="sueldo_hora">Sueldo(8 Hora)</label>
                                  <input type="number" step="any" name="sueldo_hora" class="form-control" id="sueldo_hora" readonly />
                                </div>
                              </div>

                              <!-- FECHA INICIO -->
                              <div class="col-lg-2 ">
                                <div class="form-group">
                                  <label>Fecha Inicio:</label>
                                  <div class="input-group date"  data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#fecha_inicio" id="fecha_inicio" name="fecha_inicio" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask onchange="calcular_dias_trabajo();"  />
                                    <div class="input-group-append" data-target="#fecha_inicio" data-toggle="datetimepicker">
                                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                  </div>                                 
                                </div>
                              </div>

                              <!-- FECHA INICIO -->
                              <div class="col-lg-2">
                                <div class="form-group">
                                  <label>Fecha Fin:</label>
                                  <div class="input-group date"  data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#fecha_fin" id="fecha_fin" name="fecha_fin" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask onchange="calcular_dias_trabajo();"  />
                                    <div class="input-group-append" data-target="#fecha_fin" data-toggle="datetimepicker">
                                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                  </div>                                 
                                </div>
                              </div>  

                              <!-- Cantidad de Dias -->
                              <div class="col-lg-2">
                                <div class="form-group">
                                  <label for="cantidad_dias">Cantidad de dias</label>
                                  <input type="number" name="cantidad_dias" class="form-control" id="cantidad_dias" step="any" readonly />
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
                          <div class=" justify-content-between">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="show_hide_form(false);"> <i class="fas fa-arrow-left"></i> Close</button>
                            <button type="submit" class="btn btn-success" id="guardar_registro">Guardar Cambios</button>
                          </div>
                        </form>

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
    <!-- <script src="../plugins/moment/moment.min.js"></script> -->
    <script src="../plugins/moment/locales.js"></script>
    <script src="../plugins/inputmask/jquery.inputmask.min.js"></script>
    <!-- sweetalert2 -->
    <script src="../plugins/sweetalert2/sweetalert2.all.min.js"></script>

    <script type="text/javascript" src="scripts/trabajador.js"></script>

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
