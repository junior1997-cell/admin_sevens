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
        max-height: auto; /* Ajusta la altura máxima según tus necesidades */
        overflow-y: scroll;
      }

      .custom-table {
        border-collapse: collapse;
        width: 100%;
      }

      .custom-table th,
      .custom-table td {
        border: 1px solid black;
        padding: 8px;
        text-align: center;
      }

      .custom-table th,
      .custom-table td{
        padding: 6px; /* Ajusta el valor del padding según tus necesidades */
      }

      .custom-table th {
        text-align: center;
        width: auto; /* Opcional: ajusta el ancho del encabezado según tus necesidades */
        /* vertical-align: middle; Centra verticalmente el contenido del encabezado */
        vertical-align: middle !important;
      }
      .style-head {
        padding-bottom: 4px!important;
        padding-top: 4px!important;
        padding-right: 10px!important;
        padding-left: 10px!important;
      }
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
                          <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="card-header">
                              <h3 class="card-title">
                                <!--data-toggle="modal" data-target="#modal-agregar-compra"  onclick="limpiar();"-->
                                <button type="button" class="btn bg-gradient-success" id="btn_agregar" onclick="table_show_hide(2); limpiar_form_compra();">
                                  <i class="fas fa-plus-circle"></i> Agregar
                                </button>
                                <H1>SEMANAS 1</H1>
                              </h3>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- End Main Top -->

                    <!-- /.card-header -->
                    <div class="card-body">
                      <!-- TABLA - Almacen -->
                      <div id="div_tabla_compra">
                      <div class="table-container">
                        <table class="custom-table" style="width: 100%;" role="grid">
                          <thead>
                            <tr>
                              <th rowspan="4">#</th>
                              <th rowspan="4">Code</th>
                              <th rowspan="4">Producto</th>
                              <th rowspan="4">UND</th>
                              <th rowspan="4">SALDO ANTERIOR</th>
                              <th colspan="6">JUNIO</th>
                              <th colspan="8">JULIO</th>
                              <th rowspan="4">INGRESO / SALIDA</th>
                              <th rowspan="4">SALDO</th>
                            </tr>
                            <tr>
                              <th class="style-head">25</th>
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
                              <th class="style-head">8</th>
                            </tr>

                            <tr>
                              <th colspan="7">SEMANA 1</th>
                              <th colspan="7">SEMANA 2</th>
                            </tr>
                            <tr>
                              <th class="style-head">D</th>
                              <th class="style-head">L</th>
                              <th class="style-head">M</th>
                              <th class="style-head">M</th>
                              <th class="style-head">J</th>
                              <th class="style-head">V</th>
                              <th class="style-head">S</th>
                              <!-- ------------- -->
                              <th class="style-head">D</th>
                              <th class="style-head">L</th>
                              <th class="style-head">M</th>
                              <th class="style-head">M</th>
                              <th class="style-head">J</th>
                              <th class="style-head">V</th>
                              <th class="style-head">S</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
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
                            </tr>
                            <!-- --------- -->
                            <tr>
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
                            </tr>
                            <!-- ----------- -->
                            <tr>
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
                            </tr>
                            <!-- Agrega más filas según sea necesario -->
                          </tbody>
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