<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {

  header("Location: index.php?file=" . basename($_SERVER['PHP_SELF']));
} else { ?>

  <!DOCTYPE html>
  <html lang="es">

  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title> Almacen | Admin Sevens </title>

    <?php $title = "Almacen";
    require 'head.php'; ?>

    <!--CSS  switch_MATERIALES-->
    <link rel="stylesheet" href="../dist/css/switch_materiales.css" />
    <link rel="stylesheet" href="../dist/css/leyenda.css" />

    <style>
      .table-container {
        max-height: 560px;
        /* Ajusta la altura máxima según tus necesidades */
        overflow-y: scroll;
      }

      .custom-table { border-collapse: collapse; width: 100%; }

      .custom-table th, .custom-table td { border: 1px solid black; padding: 8px; text-align: center; }

      .custom-table th, .custom-table td {
        padding: 6px;
        /* Ajusta el valor del padding según tus necesidades */
      }

      .custom-table th {
        text-align: center;
        width: auto;
        /* Opcional: ajusta el ancho del encabezado según tus necesidades */
        /* vertical-align: middle; Centra verticalmente el contenido del encabezado */
        vertical-align: middle !important;
      }

      .style-head { padding-bottom: 4px !important; padding-top: 4px !important; padding-right: 10px !important; padding-left: 10px !important; }
      .st_tr_style{
        /* background-color: #fff; */
        border-bottom: 0;
        box-shadow: inset 0 1px 0 #dee2e6, inset 0 -1px 0 #dee2e6;
        position: -webkit-sticky;
        position: sticky;
        top: 0;
        z-index: 10;
      }
      .text_producto{  text-align: inherit !important; }
    </style>
  </head>

  <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed">
    <div class="wrapper">
      <!-- Preloader -->
      <!-- <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="../dist/svg/logo-principal.svg" alt="AdminLTELogo" width="360" />
          </div> -->

      <?php
      require 'nav.php';
      require 'aside.php';
      if ($_SESSION['compra_insumos'] == 1) {
        //require 'enmantenimiento.php';
      ?>
        <!--Contenido-->
        <div class="content-wrapper">
          <!-- Content Header (Page header) -->
          <div class="content-header">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <h1 class="m-0">Almacen</h1> 
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="compra_insumos.php">Home</a></li>
                    <li class="breadcrumb-item active">Almacen</li>
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
                    <!-- Start Main Top -->
                    <div class="main-top">
                      <div class="container-fluid border-bottom">
                        <div class="row">
                          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="card-header">
                              <h3 class="card-title">
                                <button type="button" class="btn bg-gradient-success mr-2" data-toggle="modal" data-target="#modal-agregar-almacen" onclick="limpiar_form_almacen();" >
                                  <i class="fas fa-plus-circle"></i> Agregar
                                </button>
                              </h3>
                              <!-- Botones de quincenas -->
                              <div id="lista_quincenas" class="row-horizon disenio-scroll" >
                                <div class="my-3" ><i class="fas fa-spinner fa-pulse fa-2x"></i>&nbsp;&nbsp;&nbsp;Cargando...</div>
                              </div>                              
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- End Main Top -->

                    <!-- /.card-header -->
                    <div class="card-body">
                      <!-- TABLA - Almacen -->
                      <div id="div_tabla_almacen" style="display: none;">
                        <div class="table-container">
                          <table class="custom-table" style="width: 100%;" role="grid">
                            <thead class="st_tr_style bg-color-f3e700">
                              <tr class="thead-f1">
                                <!-- <th rowspan="4">#</th>
                                <th rowspan="4">Code</th>
                                <th rowspan="4">Producto</th>
                                <th rowspan="4">UND</th>
                                <th rowspan="4">SALDO <br> ANTERIOR</th>
                                <th colspan="6">JUNIO</th>
                                <th colspan="8">JULIO</th>
                                <th rowspan="4">INGRESO /<br> SALIDA</th>
                                <th rowspan="4">SALDO</th> -->
                              </tr>
                              <tr class="thead-f2">
                                <!-- <th class="style-head">25</th>
                                <th class="style-head">26</th>
                                <th class="style-head">27</th>
                                <th class="style-head">28</th>
                                <th class="style-head">29</th>
                                <th class="style-head">30</th>
                                <th class="style-head">1</th>
                                <th class="style-head">2</th>
                                <th class="style-head">3</th>
                                <th class="style-head">4</th>
                                <th class="style-head">5</th>
                                <th class="style-head">6</th>
                                <th class="style-head">7</th>
                                <th class="style-head">8</th> -->
                              </tr>

                              <tr class="thead-f3">
                                <!-- <th colspan="7">SEMANA 1</th>
                                <th colspan="7">SEMANA 2</th> -->
                              </tr>
                              <tr class="thead-f4">
                                <!-- <th class="style-head">D</th>
                                <th class="style-head">L</th>
                                <th class="style-head">M</th>
                                <th class="style-head">M</th>
                                <th class="style-head">J</th>
                                <th class="style-head">V</th>
                                <th class="style-head">S</th>
                                
                                <th class="style-head">D</th>
                                <th class="style-head">L</th>
                                <th class="style-head">M</th>
                                <th class="style-head">M</th>
                                <th class="style-head">J</th>
                                <th class="style-head">V</th>
                                <th class="style-head">S</th> -->
                              </tr>
                            </thead>
                            <tbody class="data_tbody_almacen">
                              <!-- <tr>
                                <td rowspan="2">1</td>
                                <td rowspan="2">354</td>
                                <td rowspan="2">ACEITE SAE 20W50 X 1GLN</td>
                                <td rowspan="2">UND</td>
                                <td rowspan="2">100</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                              </tr>
                              <tr>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                              </tr> -->
                              <!-- --------- -->
                              <!-- <tr>
                                <td rowspan="2">1</td>
                                <td rowspan="2">354</td>
                                <td rowspan="2">ACEITE SAE 20W50 X 1GLN</td>
                                <td rowspan="2">UND</td>
                                <td rowspan="2">100</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                              </tr>
                              <tr>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                              </tr> -->
                              <!-- ----------- -->
                              <!-- <tr>
                                <td rowspan="2">1</td>
                                <td rowspan="2">354</td>
                                <td rowspan="2">ACEITE SAE 20W50 X 1GLN</td>
                                <td rowspan="2">UND</td>
                                <td rowspan="2">100</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                              </tr>
                              <tr>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                              </tr> -->
                              <!-- Agrega más filas según sea necesario -->
                            </tbody>
                          </table>
                        </div>
                      </div>

                      <!-- CARGANDO - REGISTRO DE ASISTENCIA -->
                      <div class="row" id="cargando-table-almacen" >   
                        <div class="col-lg-12 text-center"><i class="fas fa-spinner fa-pulse fa-6x"></i><br /><br /><h4>Cargando...</h4></div>                     
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

            <!-- MODAL - AGREGAR ALMACEN - chargue 1 -->
            <div class="modal fade" id="modal-agregar-almacen">
              <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Agregar salida de producto</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span class="text-danger" aria-hidden="true">&times;</span>
                    </button>
                  </div>

                  <div class="modal-body">
                    <!-- form start -->
                    <form id="form-almacen" name="form-almacen" method="POST">
                      <div class="card-body">
                        <div class="row" id="cargando-1-fomulario">
                          <!-- id trabajador -->
                          <input type="hidden" name="idtrabajador" id="idtrabajador" />

                          <!-- Tipo de documento -->
                          <div class="col-12 col-sm-12 col-md-6 col-lg-8">
                            <div class="form-group">
                              <label for="producto">Producto</label>
                              <select name="producto" id="producto" class="form-control" placeholder="Producto">                                
                              </select>
                            </div>
                          </div> 

                          <!-- Correo electronico -->
                          <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                            <div class="form-group">
                              <label for="fecha_ingreso">Fecha</label>
                              <input type="date" name="fecha_ingreso" class="form-control" id="fecha_ingreso" placeholder="Fecha" value="<?php echo date("Y-m-d"); ?>" />
                            </div>
                          </div>                          

                          <!-- barprogress -->
                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;" id="barra_progress_almacen_div">
                            <div class="progress">
                              <div id="barra_progress_almacen" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                0%
                              </div>
                            </div>
                          </div> 

                        </div>

                        <div class="row" id="cargando-2-fomulario" style="display: none;">
                          <div class="col-lg-12 text-center"><i class="fas fa-spinner fa-pulse fa-6x"></i><br/><br/><h4>Cargando...</h4></div>
                        </div>
                      </div>
                      <!-- /.card-body -->
                      <button type="submit" style="display: none;" id="submit-form-almacen">Submit</button>
                    </form>
                  </div>
                  <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" onclick="limpiar_form_almacen();" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" id="guardar_registro_almacen">Guardar Cambios</button>
                  </div>
                </div>
              </div>
            </div>
          </section>
          <!-- /.content -->
        </div>
        <!--Fin-Contenido-->

      <?php
      } else {
        require 'noacceso.php';
      }
      require 'footer.php';
      ?>
    </div>

    <?php require 'script.php'; ?>

    <!-- table export EXCEL -->
    <script src="../plugins/export-xlsx/xlsx.full.min.js"></script>
    <script src="../plugins/export-xlsx/FileSaver.min.js"></script>
    <script src="../plugins/export-xlsx/tableexport.min.js"></script>

    <!-- ZIP -->
    <script src="../plugins/jszip/jszip.js"></script>
    <script src="../plugins/jszip/dist/jszip-utils.js"></script>
    <script src="../plugins/FileSaver/dist/FileSaver.js"></script>

    <script type="text/javascript" src="scripts/almacen.js"></script>
    <!-- <script type="text/javascript" src="scripts/js_compra_insumo_repetido.js"></script> -->

    <script>
      $(function() {
        $('[data-toggle="tooltip"]').tooltip();
      });
    </script>

  </body>

  </html>
<?php
}

ob_end_flush();
?>