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
        <title>Admin Sevens | Resumen Activos Fijos</title>
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
          if ($_SESSION['compra']==1){
          ?>
  
        
          <!-- Content Wrapper. Contains page content -->
          <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
              <div class="container-fluid">
                <div class="row mb-2">
                  <div class="col-sm-6">
                    <h1>Resumen de Activos Fijos</h1>
                  </div>
                  <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="resumen_activos_fijos.php">Home</a></li>
                      <li class="breadcrumb-item active">Activos Fijos</li>
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
                          <!-- <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-usuario" onclick="limpiar();">
                            <i class="fas fa-user-plus"></i> Agregar
                          </button> -->
                          Lista de Activos Fijos usado en este proyecto                        
                        </h3>                      
                      </div>
                      <!-- /.card-header -->
                      <div class="card-body">
                        <table id="tabla-resumen-insumos" class="table table-bordered table-striped display" style="width: 100% !important;">
                          <thead>
                            <tr>
                              <th class="">Activos Fijos</th>
                              <th>Unidad de medida</th>
                              <th>Cantidad</th> 
                              <th>Precio promedio</th> 
                              <th>Precio actual</th>    
                              <th>Suma Total</th>                      
                            </tr>
                          </thead>
                          <tbody>                         
                            <!-- aqui la va el detalle de la tabla -->
                          </tbody>
                          <tfoot>
                            <tr>
                              <th class="">Activos Fijos</th>
                              <th>Unidad de medida</th>
                              <th class="text-center"> <h5 class="suma_total_productos"> <i class="fas fa-spinner fa-pulse fa-sm"></i> </h5> </th> 
                              <th>Precio promedio</th>
                              <th>Precio actual</th>   
                              <th class="text-center"> <h5 class="suma_total_de_compras">S/. <i class="fas fa-spinner fa-pulse fa-sm"></i> </h5></th>                               
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
              <div class="modal fade" id="modal-ver-precios">
                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                  <div class="modal-content">

                    <div class="modal-header">
                      <h4 class="modal-title nombre-producto-modal-titel">Producto y mas</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    
                    <div class="modal-body">
                      <div class="card-body">
                        <div class="row" id="cargando-1-fomulario">
                          <!-- Trabajador -->
                          <div class="col-lg-12">
                            <table id="tabla-precios" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th>Fecha compra</th>
                                  <th>Cantidad</th>
                                  <th>Precio</th>  
                                  <th>Descuento</th>
                                  <th>SubTotal</th>
                                  <th>Ficha técnica</th>                               
                                </tr>
                              </thead>
                              <tbody>                         
                                
                              </tbody>
                              <tfoot>
                                <tr>
                                  <th>Fecha compra</th>
                                  <th >Cantidad</th>
                                  <th > <h4 class="precio_promedio"> S/. --</h4> </th>  
                                  <th>Descuento</th> 
                                  <th><h4 class="subtotal_x_producto"> S/. --</h4></th>
                                  <th>Ficha técnica</th>                        
                                </tr>
                              </tfoot>
                            </table>                                                        
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
                    </div>

                    <div class="modal-footer justify-content-end">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                      <!-- <button type="submit" class="btn btn-success" id="guardar_registro">Guardar Cambios</button> -->
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

      <script type="text/javascript" src="scripts/resumen_activos_fijos.js"></script>

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