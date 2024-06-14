<?php
  //Activamos el almacenamiento en el buffer
  ob_start();

  session_start();
  if (!isset($_SESSION["nombre"])){
    header("Location: index.php?file=".basename($_SERVER['PHP_SELF']));
  }else{
    ?>

    <!DOCTYPE html>
    <html lang="es">
      <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Pago de impuestos | Admin Sevens</title>
        
        <?php $title = "Recibo por Honorario"; require 'head.php'; ?>
          
      </head>
      <body class="hold-transition sidebar-collapse sidebar-mini layout-fixed layout-navbar-fixed" idproyecto="<?php echo $_SESSION['idproyecto']; ?>">
        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper">
          <?php
          require 'nav.php';
          require 'aside.php';
          if ($_SESSION['otro_gasto']==1){
            //require 'enmantenimiento.php';
            ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
              <!-- Content Header (Page header) -->
              <section class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1>Pago de impuestos</h1>
                    </div>
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Pago de impuestos</li>
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
                          <h3 class="card-title">Administra de manera eficiente Pago de impuestos. <strong>Valor de la UIT en el año 2023 es S/. 4,950.00</strong>  </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                          <div class="row">

                            <!--VENTA-->

                            <div class="col-sm-12 col-md-9 col-lg-3 col-xl-3">
                              <table width="100%" class="table table-striped">
                                <tr>
                                  <th class="text-center" colspan="2">VENTA</th>
                                </tr>
                                <tr>
                                  <td>SUBTOTAL</td>
                                  <td class="subtotal_venta"><i class="fas fa-spinner fa-pulse fa-1x"></i></td>
                                </tr>
                                <tr>
                                  <td>IGV</td>
                                  <td class="igv_venta"><i class="fas fa-spinner fa-pulse fa-1x"></i></td>
                                </tr>
                                <tr>
                                  <td>TOTAL</td>
                                  <td class="total_venta"><i class="fas fa-spinner fa-pulse fa-1x"></i></td>
                                </tr>
                              </table>
                            </div>

                            <!--GASTOS CON FACTURA-->

                            <div class="col-sm-12 col-md-9 col-lg-3 col-xl-3">
                              <table width="100%" class="table table-striped">
                                <tr>
                                  <th class="text-center" colspan="2">GASTOS</th>
                                </tr>
                                <tr>
                                  <td>GASTOS CON FACTURA</td>
                                  <td class="Subtotal_gasto"><i class="fas fa-spinner fa-pulse fa-1x"></i></td>
                                </tr>
                                <tr>
                                  <td>IGV</td>
                                  <td class="igv_gasto"><i class="fas fa-spinner fa-pulse fa-1x"></i></td>
                                </tr>
                                <tr>
                                  <td>VALOR COMPRA</td>
                                  <td class="compra_gasto"><i class="fas fa-spinner fa-pulse fa-1x"></i></td>
                                </tr>
                                <tr>
                                  <td>GASTOS SIN FACTURA</td>
                                  <td class="gasto_fact"><i class="fas fa-spinner fa-pulse fa-1x"></i></td>
                                </tr>
                                <tr>
                                  <td>MANO DE OBRA</td>
                                  <td class="rh_total"><i class="fas fa-spinner fa-pulse fa-1x"></i></td>
                                </tr>
                                <tr>
                                  <td>TOTAL</td>
                                  <td class="total_gasto"><i class="fas fa-spinner fa-pulse fa-1x"></i></td>
                                </tr>
                              </table>
                            </div>

                            <!--UTILIDAD-->

                            <div class="col-sm-12 col-md-9 col-lg-2 col-xl-2">
                              <table  width="100%" class="table table-striped">
                                <tr>
                                  <th class="text-center" colspan="2">UTILIDAD</th>
                                </tr>
                                <tr>
                                  <td>UTILIDAD</td>
                                  <td class="utilidad_sunat"><i class="fas fa-spinner fa-pulse fa-1x"></i></td>
                                </tr>
                              </table>
                            </div>

                            <!--IMPUESTOS A PAGAR-->

                            <div class="col-sm-12 col-md-9 col-lg-2 col-xl-2">
                              <table width="100%" class="table table-striped">
                                <tr>
                                  <th class="text-center" colspan="2">IMPUESTOS A PAGAR</th>
                                </tr>
                                <tr>
                                  <td>IGV</td>
                                  <td class="igv_renta"><i class="fas fa-spinner fa-pulse fa-1x"></i></td>
                                </tr>
                                <tr>
                                  <td>RENTA</td>
                                  <td class="renta"><i class="fas fa-spinner fa-pulse fa-1x"></i></td>
                                </tr>
                                <tr>
                                  <td>TOTAL</td>
                                  <td class="total_renta"><i class="fas fa-spinner fa-pulse fa-1x"></i></td>
                                </tr>
                              </table>
                            </div>

                            <!--UTILIDAD NETA-->

                            <div class="col-sm-12 col-md-9 col-lg-2 col-xl-2">
                              <table width="100%" class="table table-striped">
                                <tr>
                                  <th class="text-center" colspan="2">UTILIDAD NETA</th>
                                </tr>
                                <tr>
                                  <td>UTILIDAD</td>
                                  <td class="utilidad_neta" ><i class="fas fa-spinner fa-pulse fa-1x"></i></td>
                                </tr>
                              </table>
                            </div>

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

            <?php
          }else{
            require 'noacceso.php';
          }
          require 'footer.php';
          ?>
        </div>
        <!-- /.content-wrapper -->

        <?php require 'script.php'; ?>

        <script type="text/javascript" src="scripts/pago_impuesto.js?version_jdl=1.1"></script>

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>
        
      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
