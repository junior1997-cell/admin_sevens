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
        <title>Admin Sevens | Resumen RH</title>
        
        <?php require 'head.php'; ?>
          
      </head>
      <body class="hold-transition sidebar-collapse sidebar-mini layout-fixed layout-navbar-fixed">
        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper">
          <?php
          require 'nav.php';
          require 'aside.php';
          if ($_SESSION['Resumen_recibo_por_honorario']==1){
            ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
              <!-- Content Header (Page header) -->
              <section class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1 class="m-0">
                       Resumen recibos por honorario
                      <button class="btn btn-success btn-md btn-zip" onclick="desccargar_zip_recibos_honorarios();">
                        <i class="far fa-file-archive fa-lg"></i> Recibos por honorario.zip 
                      </button>
                    </h1>
                    </div>
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Resumen RH</li>
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
                        <div class="card-header"> </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                          <table id="tabla_resumen_rh" class="table table-bordered table-striped display" style="width: 100% !important;">
                            <thead>
                              <tr>
                                <th class="text-center">#</th>
                                <th class="">Módulo</th>
                                <th>Trabajador y/ proveedor</th>
                                <th>Monto</th>
                                <th>Documento</th>
                              </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                              <tr>
                                <th class="text-center">#</th>
                                <th class="">Módulo</th>
                                <th>Trabajador y/ proveedor</th>
                                <th>Monto</th>
                                <th>Documento</th>
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

                 <!--Modal-ver-comprobante-->
                <div class="modal fade" id="modal-ver-comprobante">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Recibo por honorario</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body ver-comprobante">              
                         
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

        <?php require 'script.php'; ?>

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
          .text_area_clss {
              width: 100%;
              height: auto;
              background: rgb(215 224 225 / 22%);
              border-block-color: inherit;
              border-bottom: aliceblue;
              border-left: aliceblue;
              border-right: aliceblue;
              border-top: hidden;
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

        <!-- <script type="text/javascript" src="scripts/moment.min.js"></script>-->
        <script src="../plugins/jszip/jszip.js"></script>
        <script src="../plugins/jszip/dist/jszip-utils.js"></script>
        <script src="../plugins/FileSaver/dist/FileSaver.js"></script>
        <script type="text/javascript" src="scripts/resumen_rh.js"></script>

        <script>
          $(function () { $('[data-toggle="tooltip"]').tooltip(); });
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
