<?php
  //Activamos el almacenamiento en el buffer
  ob_start();

  session_start();
  if (!isset($_SESSION["nombre"])){
    header("Location: login.html");
  }else{
    ?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Admin Sevens | Otros servicios</title>
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
          if ($_SESSION['otro_servicio']==1){
          ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Otros servicios</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Otros servicios</li>
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
                                            <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-otro_servicio" onclick="limpiar();"><i class="fas fa-plus-circle"></i> Agregar</button>
                                            Admnistra de manera eficiente otros servicios.
                                        </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <style>
                                            table.colapsado {border-collapse: collapse;} 
                                            .clas_pading{ padding: 0.20rem  0.75rem  0.20rem  0.75rem !important;}
                                            .backgff9100{background-color: #ff9100;}
                                           .colorf0f8ff00{color: #f0f8ff00;}
                                        </style>
                                        <div class="container table-responsive disenio-scroll">
                                            <table class="table table-hover text-nowrap styletabla" style="border: black 1px solid;" border="1" style="width: 100%;" >
                                                <thead style="background-color: #408c98; color: white;" >
                                                    <tr>
                                                        <th colspan="7" class="text-center w-px-300 clas_pading backgff9100">Compras</th>
                                                     </tr>
                                                    <tr>
                                                        <th class="text-center clas_pading">#</th>
                                                        <th class="text-center  w-px-300 clas_pading">EMPRESA</th>
                                                        <th class="text-center clas_pading">FECHA</th>
                                                        <th class="text-center clas_pading">DESCRIPCIÓN</th>
                                                        <th class="text-center clas_pading">MONTO</th>
                                                        <th class="text-center clas_pading">PAGOS</th>
                                                        <th class="text-center clas_pading">SALDO</th>
                                                    </tr>
                                                </thead>
                                                <tbody  id="compras">
                                                    <!--aqui va el listado de los días-->
                                                </tbody>
                                                <tfoot>
                                                    <th colspan="3" class="clas_pading"></th>
                                                    <th  class="clas_pading">Total</th>
                                                    <th  class="clas_pading" id="monto_compras" ></th>
                                                    <th  class="clas_pading" id="pago_compras" ></th>
                                                    <th  class="clas_pading" id="saldo_compras" ></th>
                                                </tfoot>
                                            </table>

                                            <table class="table table-hover text-nowrap styletabla" style="border: black 1px solid;" border="1" style="width: 100%;" >
                                                <thead style="background-color: #408c98; color: white;" >
                                                    <tr>
                                                        <th colspan="7" class="text-center w-px-300 clas_pading backgff9100">Servicios-Maquinaria</th>
                                                     </tr>
                                                    <tr>
                                                        <th class="text-center  clas_pading">#</th>
                                                        <th class="text-center  w-px-300 clas_pading">MAQUINA</th>
                                                        <th class="text-center clas_pading">PROVEEDOR</th>
                                                        <th class="text-center clas_pading">DETALLE</th>
                                                        <th class="text-center clas_pading">MONTO</th>
                                                        <th class="text-center clas_pading">PAGOS</th>
                                                        <th class="text-center clas_pading">SALDO</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="serv_maquinas">
                                                    <!--aqui va el listado de los días-->
                                                </tbody>
                                                <tfoot >
                                                    <th colspan="3" class="clas_pading"></th>
                                                    <th class="clas_pading">Total</th>
                                                    <th class="clas_pading" id="monto_serv_maq"></th>
                                                    <th class="clas_pading" id="pago_serv_maq"></th>
                                                    <th class="clas_pading" id="saldo_serv_maq"></th>
                                                </tfoot>
                                            </table>

                                            <table class="table table-hover text-nowrap styletabla" style="border: black 1px solid;" border="1" style="width: 100%;" >
                                                <thead style="background-color: #408c98; color: white;" >
                                                    <tr>
                                                        <th colspan="7" class="text-center w-px-300 clas_pading backgff9100">Servicios-Equipo</th>
                                                     </tr>
                                                    <tr>
                                                        <th class="text-center  clas_pading">#</th>
                                                        <th class="text-center  w-px-300 clas_pading">MAQUINA</th>
                                                        <th class="text-center clas_pading">PROVEEDOR</th>
                                                        <th class="text-center clas_pading">DETALLE</th>
                                                        <th class="text-center clas_pading">MONTO</th>
                                                        <th class="text-center clas_pading">PAGOS</th>
                                                        <th class="text-center clas_pading">SALDO</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="serv_equipos">
                                                    <!--aqui va el listado de los días-->
                                                </tbody>
                                                <tfoot >
                                                    <th colspan="3" class="clas_pading"></th>
                                                    <th class="clas_pading">Total</th>
                                                    <th class="clas_pading" id="monto_serv_equi"></th>
                                                    <th class="clas_pading" id="pago_serv_equi"></th>
                                                    <th class="clas_pading" id="saldo_serv_equi"></th>
                                                </tfoot>
                                            </table>

                                            <table class="table table-hover text-nowrap styletabla" style="border: black 1px solid;" border="1" style="width: 100%;" >
                                                <thead style="background-color: #408c98; color: white;" >
                                                    <tr>
                                                        <th colspan="7" class="text-center w-px-300 clas_pading backgff9100">Personal</th>
                                                     </tr>
                                                    <tr>
                                                        <th class="text-center clas_pading">#</th>
                                                        <th class="text-center  w-px-300 clas_pading">EMPRESA</th>
                                                        <th class="text-center clas_pading">FECHA</th>
                                                        <th class="text-center clas_pading">DESCRIPCIÓN</th>
                                                        <th class="text-center clas_pading">MONTO</th>
                                                        <th class="text-center clas_pading">PAGOS</th>
                                                        <th class="text-center clas_pading">SALDO</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!--aqui va el listado de los días-->
                                                    <tr>
                                                        <td class="bg-color-b4bdbe47  clas_pading">1</td>
                                                        <td class="bg-color-b4bdbe47  clas_pading"><span>SEVEN´S INGENIEROS S.A.C yyyyyyyyyyyyyyyy yyyyyyyyyyyy yyyyyyyyyyyyyyyyyy  yyyyyyyyyyyyyyyyyyyyyy. </span></td>
                                                        <td class="bg-color-b4bdbe47  clas_pading"><span>20/09/2021 </span></td>
                                                        <td class="bg-color-b4bdbe47  clas_pading">MADERA</td>
                                                        <td class="bg-color-b4bdbe47  clas_pading">6000</td>
                                                        <td class="bg-color-b4bdbe47  clas_pading">6000</td>
                                                        <td class="bg-color-b4bdbe47  clas_pading">0.00</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bg-color-b4bdbe47  clas_pading">1</td>
                                                        <td class="bg-color-b4bdbe47  clas_pading"><span>SEVEN´S INGENIEROS S.A.C. </span></td>
                                                        <td class="bg-color-b4bdbe47  clas_pading"><span>20/09/2021 </span></td>
                                                        <td class="bg-color-b4bdbe47  clas_pading">MADERA</td>
                                                        <td class="bg-color-b4bdbe47  clas_pading">6000</td>
                                                        <td class="bg-color-b4bdbe47  clas_pading">6000</td>
                                                        <td class="bg-color-b4bdbe47  clas_pading">0.00</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bg-color-b4bdbe47  clas_pading">1</td>
                                                        <td class="bg-color-b4bdbe47  clas_pading"><span>SEVEN´S INGENIEROS S.A.C. </span></td>
                                                        <td class="bg-color-b4bdbe47  clas_pading"><span>20/09/2021 </span></td>
                                                        <td class="bg-color-b4bdbe47  clas_pading">MADERA</td>
                                                        <td class="bg-color-b4bdbe47  clas_pading">6000</td>
                                                        <td class="bg-color-b4bdbe47  clas_pading">6000</td>
                                                        <td class="bg-color-b4bdbe47  clas_pading">0.00</td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <th colspan="3" class="clas_pading"></th>
                                                    <th class="clas_pading">Total</th>
                                                    <th class="clas_pading"></th>
                                                    <th class="clas_pading"></th>
                                                    <th class="clas_pading"></th>
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

                    <!-- Modal agregar proveedores -->
                    <div class="modal fade" id="modal_ver_detalle_maq_equ">
                        <div class="modal-dialog modal-dialog-scrollable modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title"> <b>Maquinas - Equipos:</b></h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span class="text-danger" aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <!--la tabla-->
                                    <table id="tabla-detalle-m" class="table table-bordered table-striped display" style="width: 100% !important;">
                                        <thead>
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Horometro Inicial</th>
                                                <th>Horometro Final</th>
                                                <th>Total Horas </th>
                                                <th>Costo Unitario</th>
                                                <th>Unidad M.</th>
                                                <th>Cantidad</th>
                                                <th>Costo Parcial</th>
                                                <th>Descripción</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Horometro Inicial</th>
                                                <th>Horometro Final</th>
                                                <th id="horas-total">Total Horas </th>
                                                <th>Costo Unitario</th>
                                                <th>Unidad M.</th>
                                                <th>Cantidad</th>
                                                <th>Costo Parcial</th>
                                                <th>Descripción</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--===============Modal-ver-comprobante =========-->
                    <div class="modal fade" id="modal-ver-comprobante">
                          <div class="modal-dialog modal-dialog-scrollable modal-xl ">
                              <div class="modal-content">
                                  <div class="modal-header">
                                      <h4 class="modal-title">Comprobante otro servicio</h4>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span class="text-danger" aria-hidden="true">&times;</span>
                                      </button>
                                  </div>
                                  <div class="modal-body">
                                      <div  class="class-style" style="text-align: center;"> 
                                      <a class="btn btn-warning  btn-block" href="#" id="iddescargar" download=" Comprobante otro_servicio" style="padding:0px 12px 0px 12px !important;" type="button"><i class="fas fa-download"></i></a>
                                        <br>
                                        <img onerror="this.src='../dist/img/default/img_defecto.png';" src="../dist/img/default/img_defecto.png" class="img-thumbnail" id="img-factura" style="cursor: pointer !important;" width="auto" />
                                          <div id="ver_fact_pdf" style="cursor: pointer !important;" width="auto"></div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                    </div>
                    <!--Modal ver datos-->
                    <div class="modal fade" id="modal-ver-otro_servicio">
                        <div class="modal-dialog modal-dialog-scrollable modal-xm">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Datos otro servicio</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span class="text-danger" aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div id="datosotro_servicio" class="class-style">
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

       <!-- <script type="text/javascript" src="scripts/moment.min.js"></script>-->
        <script type="text/javascript" src="scripts/resumen_general.js"></script>

        <script>
            $(function () {
                $('[data-toggle="tooltip"]').tooltip();
            });
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
