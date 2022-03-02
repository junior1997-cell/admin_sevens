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
        <title>Admin Sevens | Resumen de Factura</title>
        <?php
          require 'head.php';
        ?>
        <!-- Theme style -->
        <!-- <link rel="stylesheet" href="../dist/css/adminlte.min.css"> -->
      </head>
      <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed ">
        
        <div class="wrapper">
          <!-- Preloader -->
          <!-- <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="../dist/svg/logo-principal.svg" alt="AdminLTELogo" width="360" />
          </div> -->
        
          <?php
            require 'nav.php';
            require 'aside.php';
            if ($_SESSION['pago_trabajador']==1){
          ?>           
          <!--Contenido-->
          <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
              <div class="container-fluid">
                <div class="row mb-2">
                  <div class="col-sm-6">
                    <h1 class="m-0 nombre-trabajador">Resumen de Factura</h1>
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="resumen_factura.php">Home</a></li>
                      <li class="breadcrumb-item active">Resumen de Factura</li>
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
                <div class="row">
                  <div class="col-12">
                    <div class="card card-primary card-outline">
                      <div class="card-header"> 
                      </div>
                      <!-- /.card-header -->
                      <div class="card-body">

                        <!-- tabla resumen facturas compras -->
                        <div class=" pb-3" id="tbl-r-f-compras">
                           <h3 class="card-title">Resumen facturas: <b>Compras</b>    </h3>
                          <table id="tabla-r-f-compras" class="table table-bordered  table-striped display" style="width: 100% !important;">
                            <thead>
                              <tr> 
                                <th class="text-center">#</th> 
                                <th>Razón social</th>
                                <th>Número</th>
                                <th class="text-center">Fecha</th>
                                <th class="text-center">Sub-total</th>                                
                                <th class="text-center">IGV</th>
                                <th class="text-center">Total</th>                                                     
                              </tr>
                            </thead>
                            <tbody> </tbody>
                            <tfoot>
                              <tr> 
                                  <th class="text-center">#</th> 
                                  <th>Razón social</th>
                                  <th>Número</th>
                                  <th class="text-center">Fecha</th>
                                  <th class="text-center">Sub-total</th>                                
                                  <th class="text-center">IGV</th>
                                  <th class="text-right monto-total-compras">S/. 0.00</th>                                                     
                                </tr>
                            </tfoot>
                          </table>
                        </div>  
                        <!-- tabla resumen facturas maquinaria -->
                        <div class=" pb-3" id="tbl-r-f-maquinaria">
                          <h3 class="card-title">Resumen facturas: <b>Maquinaria</b>    </h3> 
                          <table id="tabla-r-f-maquinaria" class="table table-bordered  table-striped display" style="width: 100% !important;">
                            <thead>
                              <tr> 
                                <th class="text-center">#</th> 
                                <th>Razón social</th>
                                <th>Número</th>
                                <th class="text-center">Fecha</th>
                                <th class="text-center">Sub-total</th>                                
                                <th class="text-center">IGV</th>
                                <th class="text-center">Total</th>                                                     
                              </tr>
                            </thead>
                            <tbody> </tbody>
                            <tfoot>
                              <tr> 
                                  <th class="text-center">#</th> 
                                  <th>Razón social</th>
                                  <th>Número</th>
                                  <th class="text-center">Fecha</th>
                                  <th class="text-center">Sub-total</th>                                
                                  <th class="text-center">IGV</th>
                                  <th class="text-right monto-total-maquinaria">S/. 0.00</th>                                                     
                                </tr>
                            </tfoot>
                          </table>
                        </div>    
                        <!-- tabla resumen facturas equipos -->
                        <div class=" pb-3" id="tbl-r-f-equipos">
                          <h3 class="card-title">Resumen facturas: <b>Equipos</b>    </h3> 
                          <table id="tabla-r-f-equipos" class="table table-bordered  table-striped display" style="width: 100% !important;">
                            <thead>
                              <tr> 
                                <th class="text-center">#</th> 
                                <th>Razón social</th>
                                <th>Número</th>
                                <th class="text-center">Fecha</th>
                                <th class="text-center">Sub-total</th>                                
                                <th class="text-center">IGV</th>
                                <th class="text-center">Total</th>                                                     
                              </tr>
                            </thead>
                            <tbody> </tbody>
                            <tfoot>
                              <tr> 
                                  <th class="text-center">#</th> 
                                  <th>Razón social</th>
                                  <th>Número</th>
                                  <th class="text-center">Fecha</th>
                                  <th class="text-center">Sub-total</th>                                
                                  <th class="text-center">IGV</th>
                                  <th class="text-right monto-total-equipos">S/. 0.00</th>                                                     
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

        <script type="text/javascript" src="scripts/resumen_factura.js"></script>
         
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
