<?php
  //Activamos el almacenamiento en el buffer
  ob_start();

  session_start();
  if (!isset($_SESSION["nombre"])){
    header("Location: login.html");
  }else{
    ?>
    <!doctype html>
    <html lang="es">
      <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Admin Sevens | Usuarios</title>
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
                    <h1>Usuarios</h1>
                  </div>
                  <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="#">Home</a></li>
                      <li class="breadcrumb-item active">Usuarios</li>
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
                        <h3 class="card-title " >
                          <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-usuario" onclick="limpiar();">
                            <i class="fas fa-user-plus"></i> Agregar
                          </button>
                          Usuarios que administran el sistema                        
                        </h3>                      
                      </div>
                      <!-- /.card-header -->
                      <div class="card-body">
                        <table id="tabla-usuarios" class="table table-bordered table-striped display">
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
                          <tbody>                         
                            
                          </tbody>
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

              <!-- Modal agregar usuario -->
              <div class="modal fade" id="modal-agregar-usuario">
                <div class="modal-dialog modal-dialog-scrollable modal-xl">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Agregar usuario</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    
                    <div class="modal-body">
                      <!-- form start -->
                      <form id="form-usuario" name="form-usuario"  method="POST" >                      
                        <div class="card-body">
                          <div class="row" id="cargando-1-fomulario">
                            <!-- id usuario -->
                            <input type="hidden" name="idusuario" id="idusuario" />

                            <!-- Tipo de documento -->
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="tipo_documento">Tipo de documento</label>
                                <select name="tipo_documento" id="tipo_documento" class="form-control " onchange="seleccion();" placeholder="Tipo de documento">
                                    
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
                                <div class="input-group mb-3">
                                  <input type="number" name="num_documento" class="form-control" id="num_documento" placeholder="N° de documento">
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
                                <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Nombres y apellidos">
                              </div>
                            </div>
                            <!-- Correo electronico -->
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="email">Correo electrónico</label>
                                <input type="email" name="email" class="form-control" id="email" placeholder="Correo electrónico">
                              </div>
                            </div>
                            <!-- Direccion -->
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="direccion">Dirección</label>
                                <input type="text" name="direccion" class="form-control" id="direccion" placeholder="Dirección">
                              </div>
                            </div>
                            <!-- Telefono -->
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="telefono">Teléfono</label>                               
                                <input type="text" name="telefono" id="telefono" class="form-control" data-inputmask="'mask': ['999-999-999', '+099 99 99 999']" data-mask>
                              </div>
                            </div>
                            <!-- cargo -->
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="cargo">Cargo</label>                               
                                <select name="cargo" id="cargo" class="form-control select2" style="width: 100%;" onchange="seleccion();" >
                                  <option value="Administrador">Administrador</option>
                                  <option value="Recursos Humanos">Recursos Humanos</option>
                                  <option value="Cajero">Cajero</option>
                                </select>
                                <small id="cargo_validar" class="text-danger" style="display: none;">Por favor selecione un cargo</small>  
                              </div>
                                                        
                            </div>
                            <!-- Login -->
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="login">Login <small>(Dato para ingresar al sistema)</small></label>
                                <input type="text" name="login" class="form-control" id="login" placeholder="Login">
                              </div>
                            </div>
                            <!-- Contraseña -->
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="password">Contraseña <small>(por defecto "1234")</small></label>
                                <input type="password" name="password" class="form-control" id="password" placeholder="Contraseña" autocomplete="off">
                                <input type="hidden" name="password-old"   id="password-old"  >
                              </div>
                            </div>
                            <!-- imagen -->
                            <div class="col-lg-3">                              
                              <label for="foto2">Foto del usuario</label>
                              <img onerror="this.src='../dist/img/default/img_defecto.png';"   src="../dist/img/default/img_defecto.png" class="img-thumbnail" id="foto2_i" style="cursor: pointer !important;" width="auto" />
                              <input style="display: none;" type="file" name="foto2" id="foto2" accept="image/*" />
                              <input type="hidden" name="foto2_actual" id="foto2_actual" />
                              <div class="text-center" id="foto2_nombre"><!-- aqui va el nombre de la FOTO --></div>
                            </div>

                            <div class="col-lg-6">
                              <div class="form-group mb-0">
                                <label class="ml-4" for="permisos">Permisos</label>                               
                                <ul style="list-style: none; padding-left: 30px !important;" id="permisos"></ul>
                              </div>
                            </div>
                            
                          </div>  

                          <div class="row" id="cargando-2-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>
                              <h4>Cargando...</h4>
                            </div>
                          </div>
                          
                        </div>
                        <!-- /.card-body -->                      
                        <button type="submit" style="display: none;" id="submit-form-usuario">Submit</button>                      
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

      <script type="text/javascript" src="scripts/usuario.js"></script>

      <script>
        $(function () {
          $('[data-toggle="tooltip"]').tooltip();
        })
      </script>
      </body>
    </html> 
    
    <?php  
  }
  ob_end_flush();

?>
