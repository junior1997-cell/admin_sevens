<?php
  //Activamos el almacenamiento en el buffer
  ob_start();

  session_start();
  if (!isset($_SESSION["nombre"])){
    header("Location: index.php");
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
      <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed">
        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper">
          <?php
          require 'nav.php';
          require 'aside.php';
          if ($_SESSION['acceso']==1){
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
                      <li class="breadcrumb-item"><a href="usuario.php">Home</a></li>
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
                        <table id="tabla-usuarios" class="table table-bordered table-striped display" style="width: 100% !important;">
                          <thead>
                            <tr>
                              <th class="">#</th>
                              <th class="">Aciones</th>
                              <th>Nombres</th>
                              <th>Telefono</th>
                              <th>Usuario</th>
                              <th>Cargo</th>
                              <th>Estado</th>
                            </tr>
                          </thead>
                          <tbody>                         
                            
                          </tbody>
                          <tfoot>
                            <tr>
                              <th>#</th>
                              <th>Aciones</th>
                              <th>Nombres</th>
                              <th>Telefono</th>
                              <th>Usuario</th>
                              <th>Cargo</th>
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

                            <!-- Trabajador -->
                            <div class="col-lg-8">
                              <div class="form-group">
                                <label for="trabajador" id="trabajador_c">Trabajador</label>                               
                                <select name="trabajador" id="trabajador" class="form-control select2" style="width: 100%;" onchange="seleccion();" >
                                  
                                </select>
                                <input type="hidden" name="trabajador_old" id="trabajador_old" />
                                <small id="trabajador_validar" class="text-danger" style="display: none;">Por favor selecione un trabajador</small>  
                              </div>                                                        
                            </div>

                            <!-- cargo -->
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="cargo">Cargo</label>                               
                                <select name="cargo" id="cargo" class="form-control select2" style="width: 100%;"  >
                                  <option value="Administrador">Administrador</option>
                                  <option value="Recursos Humanos">Recursos Humanos</option>
                                  <option value="Cajero">Cajero</option>
                                </select> 
                              </div>                                                        
                            </div>

                            <!-- Login -->
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="login">Login <small class="text-danger">(Dato para ingresar al sistema)</small></label>
                                <input type="text" name="login" class="form-control" id="login" placeholder="Login" autocomplete="off">
                              </div>
                            </div>

                            <!-- Contraseña -->
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="password">Contraseña <small class="text-danger">(por defecto "123456")</small></label>
                                <input type="password" name="password" class="form-control" id="password" placeholder="Contraseña" autocomplete="off">
                                <input type="hidden" name="password-old"   id="password-old"  >
                              </div>
                            </div>                             
                            <!-- permisos -->
                            <div class="col-lg-4">
                              <div class="form-group mb-0">
                                <label class="ml-1" for="permisos">Permisos</label>                               
                                <ul style="list-style: none; padding-left: 10px !important;" id="permisos"></ul>
                              </div>
                            </div>
                            
                          </div>  

                          <div class="row" id="cargando-2-fomulario" style="display: none;" >
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
      <!-- <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script> -->
      <!-- jquery-validation -->
      <!-- <script src="../plugins/jquery-validation/jquery.validate.min.js"></script>
      <script src="../plugins/jquery-validation/additional-methods.min.js"></script> -->
      <!-- InputMask -->
      <!-- <script src="../plugins/moment/moment.min.js"></script>
      <script src="../plugins/inputmask/jquery.inputmask.min.js"></script>     -->
      <!-- sweetalert2 -->
      <!-- <script src="../plugins/sweetalert2/sweetalert2.all.min.js"></script> -->

      <script type="text/javascript" src="scripts/usuario.js"></script>

      <script>
        $(function () {
          $('[data-toggle="tooltip"]').tooltip();
        })
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
