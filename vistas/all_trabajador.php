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
    <title>Admin Sevens | All Trabajadores</title>
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
                  <h1>All Trabajadores</h1>
                </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="all_trabajador.php">Home</a></li>
                    <li class="breadcrumb-item active">Trabajadores</li>
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
                      <!-- Lista de trabajdores activos -->                      
                      <table id="tabla-trabajador" class="table table-bordered table-striped display" style="width: 100% !important;">
                        <thead>
                          <tr>
                            <th class="text-center">#</th>
                            <th class="">Aciones</th>
                            <th>Nombres</th>
                            <th>Tipo</th>
                            <th>Ocupación</th>
                            <th>Telefono</th>
                            <th>Fecha Nac. / Edad</th>
                            <th>Cuenta bancaria</th>
                            <th>Estado</th>
                          </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                          <tr>
                            <th class="text-center">#</th>
                            <th>Aciones</th>
                            <th>Nombres</th>
                            <th>Tipo</th>
                            <th>Ocupación</th>
                            <th>Telefono</th>
                            <th>Fecha Nac. / Edad</th>
                            <th>Cuenta bancaria</th>
                            <th>Estado</th>
                          </tr>
                        </tfoot>
                      </table>
                      
                      <div class="mt-4 card-danger card-outline">
                        <h1 style="text-align: center;background-color: aliceblue;">Trabajador Expulsado</h1>
                          <table id="tabla-trabajador-expulsado" class="table table-bordered table-striped display" style="width: 100% !important;">
                            <thead>
                              <tr>
                                <th class="text-center">#</th>
                                <th class="">Aciones</th>
                                <th>Nombres</th>
                                <th>Tipo</th>
                                <th>Ocupación</th>
                                <th>Telefono</th> 
                                <th>Descripción</th>
                                <th>Estado</th>
                              </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                              <tr>
                                <th class="text-center">#</th>
                                <th>Aciones</th>
                                <th>Nombres</th>
                                <th>Tipo</th>
                                <th>Ocupación</th>
                                <th>Telefono</th>
                                <th>Descripción</th>
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
                              <input type="email" name="email" class="form-control" id="email" placeholder="Correo electrónico" onkeyup="convert_minuscula(this);" />
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
                              <input type="text" name="telefono" id="telefono" class="form-control" data-inputmask="'mask': ['999-999-999', '+51 999 999 999']" data-mask />
                            </div>
                          </div>
                          <!-- fecha de nacimiento -->
                          <div class="col-lg-3">
                            <div class="form-group">
                              <label for="fecha_nacimiento">Fecha Nacimiento</label>
                              <input type="date" class="form-control" name="nacimiento" id="nacimiento" placeholder="Fecha de Nacimiento" onclick="edades();" onchange="edades();" />
                              <input type="hidden" name="edad" id="edad">
                            </div>
                          </div>
                          <!-- edad -->
                          <div class="col-lg-1">
                            <div class="form-group">
                              <label for="edad">Edad</label>
                              <p id="p_edad" style="border: 1px solid #ced4da; border-radius: 4px; padding: 5px;">0 años.</p>
                            </div>
                          </div> 
                          <!-- banco -->
                          <div class="col-lg-4">
                            <div class="form-group">
                              <label for="banco">Banco</label>
                              <select name="banco" id="banco" class="form-control select2" style="width: 100%;" onchange="formato_banco();">
                                <!-- Aqui listamos los bancos -->
                              </select>
                              <small id="banco_validar" class="text-danger" style="display: none;">Por favor selecione un cargo</small>
                            </div>
                          </div>                         
                          <!-- Cuenta bancaria -->
                          <div class="col-lg-4">
                            <div class="form-group">
                              <label for="c_bancaria" class="chargue-format-1">Cuenta Bancaria</label>
                              <input type="text" name="c_bancaria" class="form-control" id="c_bancaria" placeholder="Cuenta Bancaria" data-inputmask="" data-mask />
                            </div>
                          </div>
                          <!-- CCI -->
                          <div class="col-lg-4">
                            <div class="form-group">
                              <label for="c_bancaria" class="chargue-format-2">CCI</label>
                              <input type="text" name="cci" class="form-control" id="cci" placeholder="CCI" data-inputmask="" data-mask />
                            </div>
                          </div>
                          <!-- Titular de la cuenta -->
                          <div class="col-lg-3">
                            <div class="form-group">
                              <label for="titular_cuenta">Titular de la cuenta</label>
                              <input type="text" name="titular_cuenta" class="form-control" id="titular_cuenta" placeholder="Titular de la cuenta" />
                            </div>
                          </div>
                          <!-- tipo -->
                          <div class="col-lg-2">
                              <div class="form-group">
                                  <label for="tipo">Tipo</label>
                                  <select name="tipo" id="tipo" class="form-control select2" style="width: 100%;" >
                                  </select>
                                  <!--<input type="hidden" name="color_old" id="color_old" />-->
                              </div>
                          </div>
                          <!-- ocupacion -->
                          <div class="col-lg-3">
                              <div class="form-group">
                                  <label for="ocupacion">Ocupación</label>
                                  <select name="ocupacion" id="ocupacion" class="form-control select2" style="width: 100%;" >
                                  </select>
                                  <!--<input type="hidden" name="color_old" id="color_old" />-->
                              </div>
                          </div>
                          <!-- Ruc -->
                          <div class="col-lg-4">
                            <div class="form-group">
                              <label for="ruc">Ruc</label>
                              <input type="number" name="ruc" class="form-control" id="ruc" placeholder="Ingrese número de ruc" />
                            </div>
                          </div>
                          
                          <div class="col-lg-8"></div>

                          <!-- imagen perfil -->
                          <div class="col-md-6 col-lg-4">
                            <div class="col-lg-12 borde-arriba-naranja mt-2 mb-2"> </div>
                            <label for="foto1">Foto de perfil</label> <br>
                            <img onerror="this.src='../dist/img/default/img_defecto.png';" src="../dist/img/default/img_defecto.png" class="img-thumbnail" id="foto1_i" style="cursor: pointer !important;" width="auto" />
                            <input style="display: none;" type="file" name="foto1" id="foto1" accept="image/*" />
                            <input type="hidden" name="foto1_actual" id="foto1_actual" />
                            <div class="text-center" id="foto1_nombre"><!-- aqui va el nombre de la FOTO --></div>
                          </div>

                          <!-- imagen dni anverso -->
                          <div class="col-md-6 col-lg-4">
                            <div class="col-lg-12 borde-arriba-naranja mt-2 mb-2"> </div>
                            <label for="foto2">DNI anverso</label> <br>
                            <img onerror="this.src='../dist/img/default/dni_anverso.webp';" src="../dist/img/default/dni_anverso.webp" class="img-thumbnail" id="foto2_i" style="cursor: pointer !important;" width="auto" />
                            <input style="display: none;" type="file" name="foto2" id="foto2" accept="image/*" />
                            <input type="hidden" name="foto2_actual" id="foto2_actual" />
                            <div class="text-center" id="foto2_nombre"><!-- aqui va el nombre de la FOTO --></div>
                          </div>

                          <!-- imagen dni reverso -->
                          <div class="col-md-6 col-lg-4">
                            <div class="col-lg-12 borde-arriba-naranja mt-2 mb-2"> </div>
                            <label for="foto3">DNI reverso</label> <br>
                            <img onerror="this.src='../dist/img/default/dni_reverso.webp';" src="../dist/img/default/dni_reverso.webp" class="img-thumbnail" id="foto3_i" style="cursor: pointer !important;" width="auto" />
                            <input style="display: none;" type="file" name="foto3" id="foto3" accept="image/*" />
                            <input type="hidden" name="foto3_actual" id="foto3_actual" />
                            <div class="text-center" id="foto3_nombre"><!-- aqui va el nombre de la FOTO --></div>
                          </div>                           

                          <!-- Pdf 4 -->
                          <div class="col-md-6 col-lg-4  mt-2" >  
                            <!-- linea divisoria -->
                            <div class="col-lg-12 borde-arriba-naranja mt-2"> </div>                             
                            <div class="row">
                              <div class="col-md-12" style="padding-top: 15px; padding-bottom: 5px;">
                                <label for="Presupuesto" class="control-label" >CV Documentado</label>
                              </div>
                              <div class="col-md-6 text-center">
                                <button type="button" class="btn btn-success btn-block btn-xs" id="doc4_i">
                                  <i class="fas fa-file-upload"></i> Subir.
                                </button>
                                <input type="hidden" id="doc_old_4" name="doc_old_4" />
                                <input style="display: none;" id="doc4" type="file" name="doc4" accept=".pdf, .docx, .doc" class="docpdf" /> 
                              </div>
                              <div class="col-md-6 text-center">
                                <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(4,'cv_documentado');">
                                  <i class="fa fa-eye"></i> PDF.
                                </button>
                              </div>
                            </div>                              
                            <div id="doc4_ver" class="text-center mt-4">
                              <img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >
                            </div>
                            <div class="text-center" id="doc4_nombre"><!-- aqui va el nombre del pdf --></div>                              
                          </div>
                            
                          <!-- Pdf 5 -->
                          <div class="col-md-6 col-lg-4  mt-2" > 
                            <!-- linea divisoria -->
                            <div class="col-lg-12 borde-arriba-naranja mt-2"> </div>                              
                            <div class="row">
                              <div class="col-md-12" style="padding-top: 15px; padding-bottom: 5px;">
                                <label for="analisis-de-costos-unitarios" class="control-label" > CV No Documentado</label>
                              </div>
                              <div class="col-md-6 text-center">
                                <button type="button" class="btn btn-success btn-block btn-xs" id="doc5_i">
                                  <i class="fas fa-file-upload"></i> Subir.
                                </button>
                                <input type="hidden" id="doc_old_5" name="doc_old_5" />
                                <input style="display: none;" id="doc5" type="file" name="doc5" accept=".pdf, .docx, .doc" class="docpdf" /> 
                              </div>
                              <div class="col-md-6 text-center">
                                <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(5,'cv_no_documentado');">
                                  <i class="fa fa-eye"></i> PDF.
                                </button>
                              </div>
                            </div>                              
                            <div id="doc5_ver" class="text-center mt-4">
                              <img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >
                            </div>
                            <div class="text-center" id="doc5_nombre"><!-- aqui va el nombre del pdf --></div>                            
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
                    <button type="button" class="btn btn-danger" onclick="limpiar();" data-dismiss="modal">Close</button>
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
                      <!-- vemos los datos del trabajador -->
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

    <script type="text/javascript" src="scripts/all_trabajador.js"></script>

    <script>
      $(function () {  $('[data-toggle="tooltip"]').tooltip();  });
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
