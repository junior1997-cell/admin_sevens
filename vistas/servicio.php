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
        <title>Admin Sevens | servicios</title>
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
          if ($_SESSION['servicio_maquina']==1){
          ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>servicios</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">servicios</li>
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
                                        <h3 class="card-title display" id="btn-agregar" >
                                            <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-servicio" onclick="limpiar();"><i class="fas fa-user-plus"></i> Agregar</button>
                                            Admnistra de manera eficiente a tus servicios.
                                        </h3>
                                        <button id="btn-regresar" type="button" class="btn bg-gradient-warning"  style="display: none;" onclick="regresar_principal();"><i class="fas fa-arrow-left"></i> Regresar</button>
                                    </div>
                                    <!-- /.card-header -->
                                      <div class="card-body display" id="tabla_principal" >
                                        <table id="tabla-servicio" class="table table-bordered table-striped display" style="width: 100% !important;">
                                            <thead>
                                                <tr>
                                                    <th>Aciones</th>
                                                    <th>Nombre Máquina</th>
                                                    <th>Proveedor</th>
                                                    <th>Cantidad(veces)</th>
                                                    <th>Horas Horometro</th>
                                                    <th>Costo Parcial</th>
                                                    <th>Añadir pago</th>
                                                    <th>Saldo</th>
                                                    <th>Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Aciones</th>
                                                    <th>Nombre Máquina</th>
                                                    <th>Proveedor</th>
                                                    <th>Cantidad(veces)</th>
                                                    <th>Horas Horometro</th>
                                                    <th>Costo Parcial</th>
                                                    <th>Añadir pago</th>
                                                    <th>Saldo</th>
                                                    <th>Estado</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                      </div>
                                    <!-- /.card-body -->
                                      <!-- /.ver detalle por maquina -->
                                      <div class="card-body" id="tabla_detalles" style="display: none;">
                                        <table id="tabla-detalle-m" class="table table-bordered table-striped display" style="width: 100% !important;">
                                            <thead>
                                                <tr>
                                                    <th>Aciones</th>
                                                    <th>Fecha</th>
                                                    <th>Horometro Inicial</th>
                                                    <th>Horometro Final</th>
                                                    <th>Dif. Horas Horometro</th>
                                                    <th>Costo Unitario</th>
                                                    <th>Unidad M.</th>
                                                    <th>Costo Parcial</th>
                                                    <th>Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Aciones</th>
                                                    <th>Fecha</th>
                                                    <th>Horometro Inicial</th>
                                                    <th>Horometro Final</th>
                                                    <th id="horas-total">Dif. Horas Horometro</th>
                                                    <th>Costo Unitario</th>
                                                    <th>Unidad M.</th>
                                                    <th id="costo-parcial" style="color: #ff0000;background-color: #fedaff;"></th>
                                                    <th>Estado</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                      </div>
                                    <!-- /.detalle por maquina -->
                                      <!-- /.Pagar -->
                                        <div class="card-body" id="tabla_pagos" style="display: none;">
                                            <table id="tabla-pagos" class="table table-bordered table-striped display" style="width: 100% !important;">
                                              <thead>
                                                  <tr>
                                                      <th>Aciones</th>
                                                      <th>Fecha</th>
                                                      <th>Horometro Inicial</th>
                                                      <th>Horometro Final</th>
                                                      <th>Horas</th>
                                                      <th>Costo Unitario</th>
                                                      <th>Costo Parcial</th>
                                                      <th>Estado</th>
                                                  </tr>
                                              </thead>
                                              <tbody></tbody>
                                              <tfoot>
                                                  <tr>
                                                      <th>Aciones</th>
                                                      <th>Fecha</th>
                                                      <th>Horometro Inicial</th>
                                                      <th>Horometro Final</th>
                                                      <th id="horas-total" style="color: #ff0000;background-color: #fedaff;"></th>
                                                      <th>Costo Unitario</th>
                                                      <th id="costo-parcial" style="color: #ff0000;background-color: #fedaff;"></th>
                                                      <th>Estado</th>
                                                  </tr>
                                              </tfoot>
                                            </table>
                                       </div>
                                    <!-- /.Pagar -->
                                </div>
                                <!-- /.card -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.container-fluid -->

                    <!-- Modal agregar servicios -->
                    <div class="modal fade" id="modal-agregar-servicio">
                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Agregar servicios</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span class="text-danger" aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <!-- form start -->
                                    <form id="form-servicios" name="form-servicios" method="POST">
                                        <div class="card-body">
                                            <div class="row" id="cargando-1-fomulario">
                                                <!-- id proyecto -->
                                                <input type="hidden" name="idproyecto" id="idproyecto" />
                                                <!-- id servicios -->
                                                <input type="hidden" name="idservicio" id="idservicio" />

                                                <!-- Tipo de documento -->
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label for="maquinaria">Seleccionar maquina</label>
                                                        <div id="sssss">
                                                          <select name="maquinaria" id="maquinaria" class="form-control select2" style="width: 100%;" onchange="seleccion();" readonly >
                                                          </select>
                                                        </div>
                                                        <input class="form-control" style="display: none;" id="nomb_maq" disabled/>
                                                    </div>
                                                </div>
                                                <!-- Fecha Inicio-->
                                                <div class="col-lg-6">
                                                  <div class="form-group">
                                                    <label for="fecha_inicio">Fecha Inicio </label>                               
                                                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control"  placeholder="monto"> 
                                                  </div>                                                        
                                                </div> 
                                                <!-- Fecha fin-->
                                                <div class="col-lg-6">
                                                  <div class="form-group">
                                                    <label for="fecha_fin">Fecha Fin </label>                               
                                                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control"  placeholder="monto"> 
                                                  </div>                                                        
                                                </div> 
                                                <!-- Horometro inicial-->
                                                <div class="col-lg-6">
                                                  <div class="form-group">
                                                    <label for="horometro_inicial">Horometro Inicial </label>                               
                                                    <input type="number" step="0.01" name="horometro_inicial" id="horometro_inicial" class="form-control" placeholder="Horometro Inicial" onclick="capture_unidad();" onkeyup="capture_unidad();" > 
                                                  </div>                                                        
                                                </div> 
                                                <!-- Horometro final-->
                                                <div class="col-lg-6">
                                                  <div class="form-group">
                                                    <label for="horometro_final">Horometro Final </label>                               
                                                    <input type="number" step="0.01" name="horometro_final" id="horometro_final" class="form-control" placeholder="Horometro Final" onclick="capture_unidad();" onkeyup="capture_unidad();" > 
                                                  </div>                                                        
                                                </div>
                                                <!-- cargo -->
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                    <label for="unidad_m">Unidad de medidda</label>
                                                    <select name="unidad_m" id="unidad_m" class="form-control select2" style="width: 100%;" onchange="capture_unidad();"  >                                    
                                                        <option value="Hora">Hora</option>
                                                        <option value="Dia">Dia</option>
                                                        <option value="Mes">Mes</option>
                                                    </select>
                                                    <!-- <small id="cargo_validar" class="text-danger" style="display: none;">Por favor selecione un cargo</small> -->
                                                    </div>
                                                </div>
                                                <!-- Horas-->
                                                <div class="col-lg-3" id="horas_head">
                                                  <div class="form-group">
                                                    <label for="horas">Dif. Horometro </label>                               
                                                    <input type="number" step="0.01" name="horas" id="horas" class="form-control"  placeholder="Horas" onclick="capture_unidad();" onkeyup="capture_unidad();" readonly> 
                                                  </div>                                                        
                                                </div>
                                                <!-- Dias-->
                                                <div class="col-lg-3" id="dias_head">
                                                  <div class="form-group">
                                                    <label for="dias">Días </label>                               
                                                    <input type="number" step="0.01" name="dias" id="dias" class="form-control"  placeholder="Días" onclick="capture_unidad();" onkeyup="capture_unidad();"> 
                                                  </div>                                                        
                                                </div>
                                                <!-- Meses-->
                                                <div class="col-lg-3" style="display: none;" id="meses_head" >
                                                  <div class="form-group">
                                                    <label for="mes">Meses </label>                               
                                                    <input type="number" step="0.01" name="mes" id="mes" class="form-control"  placeholder="Mes" onclick="capture_unidad();" onkeyup="capture_unidad();"> 
                                                  </div>                                                        
                                                </div>
                                                <!-- Costo unitario-->
                                                <div class="col-lg-6">
                                                  <div class="form-group">
                                                    <label for="costo_unitario">Costo unitario </label>                               
                                                    <input type="number" step="0.01" name="costo_unitario" id="costo_unitario" class="form-control"  placeholder="Costo unitario" onclick="capture_unidad();" onkeyup="capture_unidad();"> 
                                                  </div>                                                        
                                                </div> 
                                                <!-- Costo Parcial-->
                                                <div class="col-lg-6">
                                                  <div class="form-group">
                                                    <label for="costo_parcial">Costo Parcial </label>                               
                                                    <input type="number" step="0.01" name="costo_parcial" id="costo_parcial" class="form-control"  placeholder="Costo Parcial" readonly>  
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
                                        <button type="submit" style="display: none;" id="submit-form-servicios">Submit</button>
                                    </form>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar();">Close</button>
                                    <button type="submit" class="btn btn-success" id="guardar_registro">Guardar Cambios</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--Modal ver servicios-->
                    <div class="modal fade" id="modal-ver-servicios">
                        <div class="modal-dialog modal-dialog-scrollable modal-xm">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Datos servicios</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span class="text-danger" aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <div id="datosservicios" class="class-style">
                                      
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

        <script type="text/javascript" src="scripts/servicio.js"></script>

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
