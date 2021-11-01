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
          if ($_SESSION['asistencia_trabajador']==1){
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
                    <h3 class="card-title" id="card-registrar">
                      <button type="button" class="btn bg-gradient-success"  data-toggle="modal" data-target="#modal-agregar-asistencia" onclick="limpiar();" style="margin-right: 10px; height: 61px;"><i class="fas fa-user-plus"></i> Agregar </button>
                    </h3>
                      <div id="Lista_quincenas" class="row-horizon" >
                        <!-- <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-asistencia" onclick="limpiar();"><i class="fas fa-user-plus"></i> Agregar </button>
                        <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-asistencia" onclick="limpiar();"><i class="fas fa-user-plus"></i> Agregar </button>-->
                     </div>
                    <h3 class="card-title" id="card-regresar" style="display: none;" style="padding-left: 10px;">
                      <button type="button" class="btn bg-gradient-warning" onclick="mostrar_form_table(1);"><i class="fas fa-arrow-left"></i> Regresar</button>
                    </h3>
                  </div>

                  <!-- /.card-header -->
                  <div class="card-body">
                    <div id="tabla-asistencia-trab">
                      <table id="tabla-asistencia" class="table table-bordered table-striped display" style="width: 100% !important;">
                        <thead>
                          <tr>
                            <th class="">Aciones</th>
                            <th>Nombre</th>
                            <th>total Días</th>
                            <th>total Horas</th>
                            <th>Pago / hora</th>
                            <th>Pago acumulado</th>
                            <th>Sueldo mensual</th>
                            <th>Sueldo diario</th>
                            <th>Sabatical</th>
                          </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                          <tr>
                            <th>Aciones</th>
                            <th>Nombre</th>
                            <th>total Días</th>
                            <th>total Horas</th>
                            <th>Pago / hora</th>
                            <th>Pago acumulado</th>
                            <th>Sueldo mensual</th>
                            <th>Sueldo diario</th>
                            <th>Sabatical</th>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                    <!--registro-asistencia-->
                    <div id="ver_asistencia" style="display: none;">
                      <div class="table-responsive">
                        <div class="table-responsive-lg" style="overflow-x: scroll;">
                           <table class="table styletabla" style="border: black 1px solid;">
                              <thead>
                                  <tr>
                                      <th rowspan="4" class="stile">#</th>
                                      <th rowspan="4" class="stile">Nombre</th>
                                      <th rowspan="4" class="stile">Cargo</th>
                                      <th colspan="15" style="
                                      text-align: center !important;
                                      border: black 1px solid; 
                                      padding: 0.5rem;">Horas de trabajo por día</th>
                                      <th rowspan="3" class="stile">Horas normal/ extras</th>
                                      <th rowspan="3" class="stile">Sueldo Mensual</th>
                                      <th rowspan="3" class="stile">Jornal</th>
                                      <th rowspan="3" class="stile">Sueldo hora</th>
                                      <th rowspan="3" class="stile">Sabatical</th>
                                      <th rowspan="3" class="stile">Adicional</th>
                                      <th rowspan="3" class="stile">Pago quincenal</th>
                                  </tr>
                                  <tr class="dias">
                                      <th>L</th>
                                      <th>M</th>
                                      <th>M</th>
                                      <th>J</th>
                                      <th>V</th>
                                      <th>S</th>
                                      <th>D</th>
                                      <th>L</th>
                                      <th>M</th>
                                      <th>M</th>
                                      <th>J</th>
                                      <th>V</th>
                                      <th>S</th>
                                      <th>D</th>
                                      <th>L</th>
                                  </tr>
                                  <tr class="dias">
                                      <th>1</th>
                                      <th>2</th>
                                      <th>3</th>
                                      <th>4</th>
                                      <th>5</th>
                                      <th>6</th>
                                      <th>7</th>
                                      <th>8</th>
                                      <th>9</th>
                                      <th>10</th>
                                      <th>11</th>
                                      <th>12</th>
                                      <th>13</th>
                                      <th>14</th>
                                      <th>15</th>
                                  </tr>
                              </thead>
                              <tbody class="tcuerpo nameappend">
                                  <!--<tr>
                                      <td>H/N</td>
                                      <td>Maestro de obra</td>
                                      <td>8</td>
                                      <td>8</td>
                                      <td>8</td>
                                      <td>8</td>
                                      <td>8</td>
                                      <td>0</td>
                                      <td>4</td>
                                      <td>48</td>
                                      <td>3000</td>
                                      <td>107</td>
                                      <td>13.39</td>
                                      <td>1</td>
                                      <td>1</td>
                                      <td>750.00</td>

                                  </tr>
                                  <tr>
                                      <td>H/E</td>
                                      <td>Maestro de obra</td>
                                      <td>0</td>
                                      <td>2</td>
                                      <td>1</td>
                                      <td>0</td>
                                      <td>0</td>
                                      <td>0</td>
                                      <td>1</td>
                                      <td>4</td>
                                      <td>300</td>
                                      <td>107.00</td>
                                      <td>13.39</td>
                                      <td>0</td>
                                      <td>0</td>
                                      <td>53.56</td>

                                  </tr>
                                  <tr>
                                      <td colspan="14"></td>
                                      <td ><b>TOTAL</b></td>
                                      <td>803.56</td>

                                  </tr>-->
                              </tbody>
                          </table>
                        </div>
                      </div>
                    </div>

                    <div id="detalle_asistencia" style="display: none;">
                      <table id="tabla-detalle-asistencia-individual" class="table table-bordered table-striped display" style="width: 100% !important;">
                        <thead>
                          <tr>
                            <th class="">Aciones</th>
                            <th>Nombre</th>
                            <th>Horas Normal</th>
                            <th>Pago Horas Normal</th>
                            <th>Hora Extras</th>
                            <th>Pago Hora Extras</th>
                            <th>Fecha Asistencia</th>
                            <th>Estado</th>
                          </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                          <tr>
                            <th class="">Aciones</th>
                            <th>Nombre</th>
                            <th>Horas Normal</th>
                            <th>Pago Horas Normal</th>
                            <th>Hora Extras</th>
                            <th>Pago Hora Extras</th>
                            <th>Fecha Asistencia</th>
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
          </div>
          <!-- Modal agregar asistencia -->
          <div class="modal fade" id="modal-agregar-asistencia">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
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
                    <div class="row" >
                      <!-- id proyecto -->
                      <input type="hidden" name="idproyecto" id="idproyecto" required />

                      <!-- id asistencia -->
                      <input type="hidden" name="idasistencia_trabajador" id="idasistencia_trabajador" />

                      <!-- fecha del registro de la asistencia -->
                      <div class="col-lg-4  mb-2">
                        <div class="form-group">
                          <label for="fecha">Fecha de asistencia</label>
                          <input type="date" class="form-control" name="fecha" id="fecha"  />                            
                        </div>
                      </div>

                      <!-- Seleccionar una fecha para todos -->
                      <div class="col-lg-4 mb-2">
                        <div class="bootstrap-timepicker">
                          <div class="form-group">
                            <label>Hora para todos:</label>
                            <div class="input-group date" id="timepicker" data-target-input="nearest">
                              <input type="text" id="hora_all" class="form-control datetimepicker-input" data-target="#timepicker" onchange="agregar_hora_all();" onkeyup="agregar_hora_all();" oninput="agregar_hora_all()" />
                              <div class="input-group-append" data-target="#timepicker" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="far fa-clock"></i></div>
                              </div>
                              </div>
                            <!-- /.input group -->
                          </div>
                          <!-- /.form group -->
                        </div>
                      </div>

                      <div class="col-lg-4"></div>
                      
                      <div class="col-lg-12">
                        <div class="row" id="lista-de-trabajadores">
                          <!-- Lista de todos lo trabajadores -->
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

          <!-- Modal editar asistencia -->
          <div class="modal fade" id="modal-editar-asistencia">
            <div class="modal-dialog modal-dialog-scrollable modal-md">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Editar asistencia</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="text-danger" aria-hidden="true">&times;</span>
                  </button>
                </div>

                <div class="modal-body">
                  <!-- form start -->
                  <form id="form-editar-asistencia" name="form-editar-asistencia" method="POST">
                    <div class="row" id="cargando-1-fomulario">
                      <!-- id proyecto -->
                      <input type="hidden" name="idproyecto2" id="idproyecto2" required />

                      <!-- id asistencia -->
                      <input type="hidden" name="idasistencia_trabajador2" id="idasistencia_trabajador2" />

                      <!-- fecha del registro de la asistencia -->
                      <div class="col-lg-12 mb-2">
                        <div class="form-group">
                          <label for="fecha">Fecha de asistencia</label>
                          <input type="date" class="form-control" name="fecha2" id="fecha2"  />                            
                        </div>
                      </div>                      
                      
                      <div class="col-lg-12">
                        <div class="row" id="lista-de-trabajadores2">
                          <!-- Lista de todos lo trabajadores -->
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
                    <button type="submit" style="display: none;" id="submit-form-asistencia2">Submit</button>
                  </form>
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-success" id="guardar_registro2">Guardar Cambios</button>
                </div>
              </div>
            </div>
          </div>

          <!-- Modal justificar asistencia -->
          <div class="modal fade" id="modal-justificar-asistencia">
            <div class="modal-dialog modal-dialog-scrollable modal-md">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Justificación</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="text-danger" aria-hidden="true">&times;</span>
                  </button>
                </div>

                <div class="modal-body">
                  <!-- form start -->
                  <form id="form-justificar-asistencia" name="form-justificar-asistencia" method="POST">
                    <div class="row" id="cargando-3-fomulario">
                      
                      <!-- id asistencia -->
                      <input type="hidden" name="idasistencia_trabajador3" id="idasistencia_trabajador3" />                                         
                      
                      <!-- Descripcion -->
                      <div class="col-md-12 col-lg-12">
                        <div class="form-group">
                          <label for="nombre">Descripción</label>
                          <textarea name="detalle" id="detalle" class="form-control" rows="5" placeholder="Ingresa descripción"></textarea>
                        </div>
                      </div>

                      <!-- Documento -->
                      <div class="col-md-12 col-lg-12" >                               
                        <div class="row text-center">
                          <div class="col-md-12" style="padding-top: 15px; padding-bottom: 5px;">
                            <label for="cip" class="control-label" > Evidencia </label>
                          </div>
                          <div class="col-md-6 text-center">
                            <button type="button" class="btn btn-success btn-block" id="doc1_i">
                              <i class="fas fa-file-upload"></i> Subir.
                            </button>
                            <input type="hidden" id="doc_old_1" name="doc_old_1" />
                            <input style="display: none;" id="doc1" type="file" name="doc1" accept=".pdf" class="docpdf" /> 
                          </div>
                          <div class="col-md-6 text-center">
                            <button type="button" class="btn btn-info btn-block" onclick="PreviewImage();">
                              <i class="fa fa-eye"></i> PDF.
                            </button>
                          </div>
                        </div>                              
                        <div id="doc1_ver" class="text-center mt-4">
                          <img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >
                        </div>
                        <div class="text-center" id="doc1_nombre"><!-- aqui va el nombre del pdf --></div>

                        <!-- linea divisoria -->
                        <div class="col-lg-12 borde-arriba-naranja mt-2"> </div>
                      </div> 

                    </div>

                    <div class="row" id="cargando-4-fomulario" style="display: none;">
                      <div class="col-lg-12 text-center">
                        <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                        <br />
                        <h4>Cargando...</h4>
                      </div>
                    </div>
                    <!-- /.card-body -->
                    <button type="submit" style="display: none;" id="submit-form-asistencia2">Submit</button>
                  </form>
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-success" id="guardar_registro2">Guardar Cambios</button>
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
      .class-style label {
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
    <script>
      if (localStorage.getItem("nube_idproyecto")) {
        console.log("icon_folder_" + localStorage.getItem("nube_idproyecto"));

        $("#ver-proyecto").html('<i class="fas fa-tools"></i> Proyecto: ' + localStorage.getItem("nube_nombre_proyecto"));

        $("#ver-otros-modulos-1").show();

        // $('#icon_folder_'+localStorage.getItem('nube_idproyecto')).html('<i class="fas fa-folder-open"></i>');
      } else {
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
