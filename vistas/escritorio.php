<?php
  //Activamos el almacenamiento en el buffer
  ob_start();
  session_start();

  if (!isset($_SESSION["nombre"])){
    header("Location: login.html");
  }else{ ?>
    <!DOCTYPE html>
    <html lang="es">
      <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Admin Sevens | Escritorio</title>
        <?php
          require 'head.php';
        ?>
         
      </head>
      <body class="hold-transition sidebar-mini layout-fixed">
        
        <div class="wrapper">
          <!-- Preloader -->
          <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="../dist/svg/logo-principal.svg" alt="AdminLTELogo" width="360" />
          </div>
        
          <?php
            require 'nav.php';
            require 'aside.php';
            if ($_SESSION['escritorio']==1){
          ?>           
          <!--Contenido-->
          <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
              <div class="container-fluid">
                <div class="row mb-2">
                  <div class="col-sm-6">
                    <h1 class="m-0">Tablero</h1>
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="escritorio.php">Home</a></li>
                      <li class="breadcrumb-item active">Tablero</li>
                    </ol>
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
              <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
              <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                  <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                      <div class="inner">
                        <h3>150</h3>

                        <p>Total de Proyectos</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-bag"></i>
                      </div>
                      <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                  </div>
                  <!-- ./col -->
                  <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                      <div class="inner">
                        <h3>53<sup style="font-size: 20px;">%</sup></h3>

                        <p>Total de Proveedores</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                      </div>
                      <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                  </div>
                  <!-- ./col -->
                  <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                      <div class="inner">
                        <h3>44</h3>

                        <p>Toal de Trabajadores</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-person-add"></i>
                      </div>
                      <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                  </div>
                  <!-- ./col -->
                  <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                      <div class="inner">
                        <h3>65</h3>

                        <p>Total de Servicio</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                      </div>
                      <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                  </div>
                  <!-- ./col -->
                </div>
                <!-- /.row -->
              </div>
              <!-- /.container-fluid -->
            </section>
            <!-- /.content -->

            <!-- Main content -->
            <section class="content">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-12">
                    <div class="card card-primary card-outline">
                      <div class="card-header">
                        <h3 class="card-title " >
                          <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-usuario" onclick="limpiar();">
                          <i class="fas fa-plus-circle"></i> Agregar
                          </button>
                          Proyectos                        
                        </h3>                      
                      </div>
                      <!-- /.card-header -->
                      <div class="card-body">
                        <table id="tabla-proyectos" class="table table-bordered table-striped display">
                          <thead>
                            <tr>
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
                <div class="modal-dialog modal-dialog-scrollable modal-lg">
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
                            <div class="col-lg-4">
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
                            <!-- permisos -->
                            <div class="col-lg-4">
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
          <!--Fin-Contenido-->
          <?php
            }else{
              require 'noacceso.php';
            }
            require 'footer.php';
          ?>
        </div>
        <?php          
          require 'script.php';
        ?>
         

        <script type="text/javascript" src="scripts/proyecto.js"></script>
      </body>
    </html>
    <?php    
  }
  ob_end_flush();
?>
