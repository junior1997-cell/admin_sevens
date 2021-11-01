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
                                        <button type="button" id="btn-pagar" class="btn bg-gradient-success" data-toggle="modal"  style="display: none;" data-target="#modal-agregar-pago" onclick="limpiar();"><i class="fas fa-dollar-sign"></i> Agregar Pago</button>
                                    </div>
                                    <!-- /.card-header -->
                                      <div class="card-body display" id="tabla_principal" >
                                        <table id="tabla-servicio" class="table table-bordered table-striped display" style="width: 100% !important;">
                                            <thead>
                                                <tr>
                                                    <th>Aciones</th>
                                                    <th>Nombre Máquina</th>
                                                    <th>Proveedor</th>
                                                    <th>Unidad Medida</th>
                                                    <th>Cantidad(veces)</th>
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
                                                    <th>Unidad Medida</th>
                                                    <th>Cantidad(veces)</th>
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
                                                    <th>Total Horas </th>
                                                    <th>Costo Unitario</th>
                                                    <th>Unidad M.</th>
                                                    <th>Costo Parcial</th>
                                                    <th>Descripción</th>
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
                                                    <th id="horas-total">Total Horas </th>
                                                    <th>Costo Unitario</th>
                                                    <th>Unidad M.</th>
                                                    <th id="costo-parcial" style="color: #ff0000;background-color: #fedaff;"></th>
                                                    <th>Descripción</th>
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
                                                <!-- cargo -->
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                    <label for="unidad_m">Unidad de medida</label>
                                                    <select name="unidad_m" id="unidad_m" class="form-control select2" style="width: 100%;" onchange="capture_unidad();"  >                                    
                                                        <option value="Hora">Hora</option>
                                                        <option value="Dia">Dia</option>
                                                        <option value="Mes">Mes</option>
                                                    </select>
                                                    </div>
                                                </div>
                                                <!-- Fecha Inicio-->
                                                <div class="col-lg-6" id="fecha_i">
                                                  <div class="form-group">
                                                    <label for="fecha_inicio" id="fecha-i-tutulo">Fecha Inicio </label>                               
                                                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" onchange="calculardia();"> 
                                                  </div>                                                        
                                                </div> 
                                                <!-- Fecha fin-->
                                                <div class="col-lg-6" id="fecha_f">
                                                  <div class="form-group">
                                                    <label for="fecha_fin" id="fecha_fi">Fecha Fin </label>                               
                                                    <input type="text" name="fecha_fin" id="fecha_fin" class="form-control" > 
                                                  </div>                                                        
                                                </div> 
                                                <!-- Horometro inicial-->
                                                <div class="col-lg-6" id="horometro_i">
                                                  <div class="form-group">
                                                    <label for="horometro_inicial">Horometro Inicial </label>                               
                                                    <input type="number" step="0.01" name="horometro_inicial" id="horometro_inicial" class="form-control" placeholder="Horometro Inicial" onclick="costo_partcial();" onkeyup="costo_partcial();" > 
                                                  </div>                                                        
                                                </div> 
                                                <!-- Horometro final-->
                                                <div class="col-lg-6" id="horometro_f">
                                                  <div class="form-group">
                                                    <label for="horometro_final">Horometro Final </label>                               
                                                    <input type="number" step="0.01" name="horometro_final" id="horometro_final" class="form-control" placeholder="Horometro Final" onclick="costo_partcial();" onkeyup="costo_partcial();" > 
                                                  </div>                                                        
                                                </div>
                                                <!-- Horas-->
                                                <div class="col-lg-6" id="horas_head">
                                                  <div class="form-group">
                                                    <label for="horas">Total Horas </label>                               
                                                    <input type="number" step="0.01" name="horas" id="horas" class="form-control"  placeholder="Horas" onclick="costo_partcial();" onkeyup="costo_partcial();" readonly> 
                                                  </div>                                                        
                                                </div>
                                                <!-- Dias-->
                                                <div class="col-lg-6" id="dias_head">
                                                  <div class="form-group">
                                                    <label for="dias">Días </label>                               
                                                    <input type="number" step="0.01" name="dias" id="dias" class="form-control"  placeholder="Días"> 
                                                  </div>                                                        
                                                </div>
                                                <!-- Meses-->
                                                <div class="col-lg-6" style="display: none;" id="meses_head" >
                                                  <div class="form-group">
                                                    <label for="mes">Meses </label>                               
                                                    <input type="number" step="0.01" name="mes" id="mes" class="form-control"  placeholder="Mes" onclick="capture_unidad();" onkeyup="capture_unidad();"> 
                                                  </div>                                                        
                                                </div>
                                                <!-- Costo unitario-->
                                                <div class="col-lg-6" id="costo_unit">
                                                  <div class="form-group">
                                                    <label for="costo_unitario">Costo unitario </label>                               
                                                    <input type="number" step="0.01" name="costo_unitario" id="costo_unitario" class="form-control"  placeholder="Costo unitario" onclick="costo_partcial();" onkeyup="costo_partcial();"> 
                                                  </div>                                                        
                                                </div> 
                                                <!-- Costo Parcial-->
                                                <div class="col-lg-6">
                                                  <div class="form-group">
                                                    <label for="costo_parcial">Costo Parcial </label>                               
                                                    <input type="number" step="0.01" name="costo_parcial" id="costo_parcial" class="form-control"  placeholder="Costo Parcial"  readonly>  
                                                  </div>                                                        
                                                </div>
                                                <!-- Descripcion-->
                                                <div class="col-lg-12">
                                                  <div class="form-group">
                                                    <label for="descripcion">Descripción </label> <br>
                                                    <textarea name="descripcion" id="descripcion" class="form-control" rows="2"></textarea>
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

                      <!-- Modal agregar Pagos -->
                      <div class="modal fade" id="modal-agregar-pago">
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

                                                <!-- Maquina-->
                                                <div class="col-lg-12">
                                                  <div class="form-group">
                                                    <label for="maquinaria">Maquinaria </label>                               
                                                    <input type="text" name="maquinaria" id="maquinaria" class="form-control"  placeholder="maquinaria"> 
                                                  </div>                                                        
                                                </div> 

                                                <!-- Beneficiario -->
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label for="beneficiario">Beneficiario</label>
                                                        <input class="form-control" id="beneficiario" name="beneficiario" value="SEVEN´S INGENIEROS S.A.C." disabled/>
                                                    </div>
                                                </div>
                                                <!--Forma de pago -->
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                    <label for="forma_pago">Forma Pago</label>
                                                    <select name="forma_pago" id="forma_pago" class="form-control select2" style="width: 100%;" onchange="capture_unidad();"  >                                    
                                                        <option value="Transferencia">Transferencia</option>
                                                        <option value="Efectivo">Efectivo</option>
                                                        <option value="Crédito">Crédito</option>
                                                    </select>
                                                    </div>
                                                </div>
                                                <!--tipo de pago -->
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                    <label for="forma_pago">Tipo  Pago</label>
                                                    <select name="forma_pago" id="forma_pago" class="form-control select2" style="width: 100%;" onchange="capture_unidad();"  >                                    
                                                        <option value="Detracción">Detracción</option>
                                                        <option value="Usuario">Usuario</option>
                                                    </select>
                                                    </div>
                                                </div>
                                                <!-- Cuenta de destino-->
                                                <div class="col-lg-6">
                                                  <div class="form-group">
                                                    <label for="cuenta_destino">Cuenta destino </label>                               
                                                    <input type="number" name="cuenta_destino" id="cuenta_destino" class="form-control"  placeholder="Cuenta destino"> 
                                                  </div>                                                        
                                                </div>
                                                <!-- banco -->
                                                <div class="col-lg-6">
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
                                                <!-- Titular Cuenta-->
                                                <div class="col-lg-6">
                                                  <div class="form-group">
                                                    <label for="titular_cuenta">Titular Cuenta </label>                               
                                                    <input type="text" name="titular_cuenta" id="titular_cuenta" class="form-control"  placeholder="Titular Cuenta"> 
                                                  </div>                                                        
                                                </div>

                                                <!-- Fecha Inicio-->
                                                <div class="col-lg-6">
                                                  <div class="form-group">
                                                    <label for="fecha">Fecha Pago </label>                               
                                                    <input type="date" name="fecha" id="fecha" class="form-control"  placeholder="monto"> 
                                                  </div>                                                        
                                                </div>
                                                <!-- Monto-->
                                                <div class="col-lg-6">
                                                  <div class="form-group">
                                                    <label for="monto">Monto </label>                               
                                                    <input type="number" name="monto" id="monto" class="form-control"  placeholder="number"> 
                                                  </div>                                                        
                                                </div>
                                                <!-- Número de Operación-->
                                                <div class="col-lg-6">
                                                  <div class="form-group">
                                                    <label for="numero_op">Número de operación </label>                               
                                                    <input type="number" name="numero_op" id="numero_op" class="form-control"  placeholder="Número de operación"> 
                                                  </div>                                                        
                                                </div>
                                                <!-- Descripcion-->
                                                <div class="col-lg-12">
                                                  <div class="form-group">
                                                    <label for="descripcion">Descripción </label> <br>
                                                    <textarea name="descripcion" id="descripcion" class="form-control" rows="2"></textarea>
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
