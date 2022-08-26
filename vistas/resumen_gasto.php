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
        <title>Resumen de Gasto | Admin Sevens</title>
        <?php $title = "Resumen de Gasto"; require 'head.php';  ?>       

        <link rel="stylesheet" href="../dist/css/switch_materiales.css">

      </head>
      <body class="hold-transition sidebar-collapse sidebar-mini layout-fixed layout-navbar-fixed">
        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper">
          <?php
          require 'nav.php';
          require 'aside.php';
          if ($_SESSION['resumen_gasto']==1){
            //require 'enmantenimiento.php';
            ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
              <!-- Content Header (Page header) -->
              <div class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1 class="m-0 nombre-trabajador">
                      <i class="nav-icon fas fa-comments-dollar"></i> Resumen de Gasto 
                        <button class="btn btn-success btn-md btn-zip" onclick="descargar_zip_comprobantes();">
                          <i class="far fa-file-archive fa-lg"></i> Comprobantes .zip 
                        </button>
                      </h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="resumen_gasto.php">Home</a></li>
                        <li class="breadcrumb-item active">Resumen de Gasto</li>
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
                          <div class="row">

                            <!-- modulos incluidos -->
                            <div class="col-lg-2 col-md-2 col-sm-2 col-12">
                              <label class="text-info" >Ver modulos</label><br>
                              <button type="button" class="btn btn-block bg-gradient-info btn-sm" data-toggle="modal" data-target="#modal-modulos-incluidos">
                                <i class="fas fa-eye"></i> Módulos Incluidos
                              </button>
                            </div>
                            <!-- filtro por: fecha -->
                            <div class="col-lg-2 col-md-6 col-sm-6 col-12">
                              <label for="filtros" >Fecha inicio </label>
                              <!-- fecha inicial -->
                              <input name="fecha_filtro" id="fecha_filtro_1" type="date" class="form-control form-control-sm h-40px m-b-1px" placeholder="Seleccionar fecha" onchange="cargando_search(); delay(function(){filtros()}, 50 );" />
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-6 col-12">
                              <label for="filtros" >Fecha fin </label>
                              <!-- fecha final -->
                              <input name="fecha_filtro" id="fecha_filtro_2" type="date" class="form-control form-control-sm h-40px" placeholder="Seleccionar fecha" onchange="cargando_search(); delay(function(){filtros()}, 50 );" />
                            </div>

                            <!-- filtro por: proveedor -->
                            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                              <label for="filtros" class="cargando_proveedor">Proveedor &nbsp;<i class="text-dark fas fa-spinner fa-pulse fa-lg"></i><br /></label>
                              <select name="proveedor_filtro" id="proveedor_filtro" class="form-control select2" onchange="cargando_search(); delay(function(){filtros()}, 50 );" style="width: 100%;"> 
                              </select>
                            </div>

                            <!-- filtro por: proveedor -->
                            <div class="col-lg-2 col-md-6 col-sm-6 col-12">
                              <label for="filtros" >Tipo comprobante </label>
                              <select name="tipo_comprobante_filtro" id="tipo_comprobante_filtro" class="form-control select2" onchange="cargando_search(); delay(function(){filtros()}, 50 );" style="width: 100%;"> 
                                <option value="0">Todos</option>
                                <option value="Factura">Factura</option>
                                <option value="Boleta">Boleta</option>
                                <option value="Recibo por Honorario">Recibo por Honorario</option>
                              </select>
                            </div>

                          </div>                        
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                          <!-- tabla resumen facturas compras -->
                          <div class="pb-3">
                            <!-- <h3 class="card-title mb-2">Resumen facturas: <b>Compras</b>    </h3> -->
                            <table id="tabla-principal" class="table table-bordered  table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th colspan="12" class="cargando text-center bg-danger"><i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando... </th>
                                </tr>
                                <tr> 
                                  <th class="text-center">#</th> 
                                  <th class="text-center">OP</th>
                                  <th class="text-center" data-toggle="tooltip" data-original-title="Aprobado por:">V°B°</th>
                                  <th class="text-center">Fecha</th>
                                  <th>Tipo compr.</th>
                                  <th class="text-center">Módulo</th>
                                  <th class="text-center">Glosa</th>
                                  <th>Razón social</th>                                  
                                  <th class="text-center ">Subtotal</th>                                
                                  <th class="text-center">IGV</th>
                                  <th class="text-center">Total</th> 
                                  <th class="text-center">CFDI.</th>  
                                  <th class="text-center">Estado</th>                                                   
                                </tr>
                              </thead>
                              <tbody> </tbody>
                              <tfoot> 
                                <tr> 
                                  <th class="text-center text-black-50">#</th> 
                                  <th class="text-center text-black-50">OP</th>
                                  <th class="text-center text-black-50">V°B°</th>
                                  <th class="text-center text-black-50">Fecha</th>
                                  <th class="text-center text-black-50">Tipo compr.</th>
                                  <th class="text-center text-black-50">Módulo</th>
                                  <th class="text-center text-black-50">Glosa</th>
                                  <th class="text-black-50">Razón social</th>                                  
                                  <th class="text-right text-nowrap total-subtotal">Subtotal</th>                                
                                  <th class="text-right text-nowrap total-igv">IGV</th>
                                  <th class="text-right text-nowrap total-total">Total</th>  
                                  <th class="text-center text-black-50">CFDI.</th>
                                  <th class="text-center">Estado</th> 
                                </tr>
                              </tfoot>
                            </table>
                          </div>

                          <!-- tabla resumen facturas compras -->
                          <div class="pb-3">
                            <h4><b>Lista de Gastos Aceptados</b></h4>
                            <!-- <h3 class="card-title mb-2">Resumen facturas: <b>Compras</b>    </h3> -->
                            <table id="tabla-visto-bueno" class="table table-bordered  table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th colspan="12" class="cargando text-center bg-danger"><i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando... </th>
                                </tr>
                                <tr> 
                                  <th class="text-center">#</th> 
                                  <th class="text-center">OP</th>
                                  <th class="text-center" data-toggle="tooltip" data-original-title="Aprobado por:">V°B°</th>
                                  <th class="text-center">Fecha</th>
                                  <th>Tipo compr.</th>
                                  <th class="text-center">Módulo</th>
                                  <th class="text-center">Glosa</th>
                                  <th>Razón social</th>                                  
                                  <th class="text-center ">Subtotal</th>                                
                                  <th class="text-center">IGV</th>
                                  <th class="text-center">Total</th> 
                                  <th class="text-center">CFDI.</th>  
                                  <th class="text-center">Estado</th>                                                   
                                </tr>
                              </thead>
                              <tbody> </tbody>
                              <tfoot> 
                                <tr> 
                                  <th class="text-center text-black-50">#</th> 
                                  <th class="text-center text-black-50">OP</th>
                                  <th class="text-center text-black-50">V°B°</th>
                                  <th class="text-center text-black-50">Fecha</th>
                                  <th class="text-center text-black-50">Tipo compr.</th>
                                  <th class="text-center text-black-50">Módulo</th>
                                  <th class="text-center text-black-50">Glosa</th>
                                  <th class=" text-black-50">Razón social</th>                                  
                                  <th class="text-right text-nowrap total-subtotal-visto-bueno">Subtotal</th>                                
                                  <th class="text-right text-nowrap total-igv-visto-bueno">IGV</th>
                                  <th class="text-right text-nowrap total-total-visto-bueno">Total</th>  
                                  <th class="text-center text-black-50">CFDI.</th>
                                  <th class="text-center">Estado</th>
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
                
                <!-- MODAL - COMPROBANTE  -->
                <div class="modal fade" id="modal-ver-comprobante">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Comprobante</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body ver-comprobante">
                        <!-- detalle de la factura -->
                      </div>
                    </div>
                  </div>
                </div>

                <!-- MODAL - MODULOS INCLUIDOS  -->
                <div class="modal fade" id="modal-modulos-incluidos">
                  <div class="modal-dialog modal-dialog-scrollable modal-md">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Módulos Incluidos</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body"> 
                        <ol>
                          <li class="m-b-04rem"><i class="fas fa-shopping-cart nav-icon"></i> COMPRAS INSUMOS</li>
                          <!-- <li>COMPRAS ACTIVOS FIJOS <small class="text-red">(sin proyecto)</small></li> -->
                          <li class="m-b-04rem"><img src="../dist/svg/negro-excabadora-ico.svg" class="nav-icon" alt="" style="width: 21px !important;" > SERVICIO MAQUINA </li>
                          <li class="m-b-04rem"><img src="../dist/svg/negro-estacion-total-ico.svg" class="nav-icon" alt="" style="width: 21px !important;" > SERVICIO EQUIPO</li>
                          <li class="m-b-04rem"><i class="nav-icon fas fa-hands-helping"></i> SUB CONTRATO</li>
                          <li class="m-b-04rem"><img src="../dist/svg/negro-planilla-seguro-ico.svg" class="nav-icon" alt="" style="width: 21px !important;" > PLANILLA SEGURO</li>
                          <li class="m-b-04rem"><i class="nav-icon fas fa-network-wired"></i> OTRO GASTO</li>
                          <li class="m-b-04rem"><i class="fas fa-shuttle-van nav-icon"></i> TRANSPORTE</li>
                          <li class="m-b-04rem"><i class="fas fa-hotel nav-icon"></i> HOSPEDAJE</li>
                          <li class="m-b-04rem"><i class="fas fa-utensils nav-icon"></i> PENSION</li>
                          <li class="m-b-04rem"><i class="fas fa-hamburger nav-icon"></i> BREAK</li>
                          <li class="m-b-04rem"><i class="fas fa-drumstick-bite nav-icon"></i> COMIDA EXTRA</li>
                          <!-- <li class="m-b-04rem"><i class="nav-icon fas fa-receipt"></i> OTRA FACTURA <small class="text-red">(sin proyecto)</small></li> -->
                          <!-- <li>OTRO INGRESO</li> -->
                          <li class="m-b-04rem"><i class="fas fa-briefcase nav-icon"></i> PAGO ADMINISTRADORES</li>
                          <li class="m-b-04rem"><i class="fas fa-users nav-icon"></i> PAGO OBREROS</li>
                        </ol>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- MODAL - DETALLE compras - charge -->
                <div class="modal fade" id="modal-ver-compras">
                  <div class="modal-dialog modal-dialog-scrollable modal-xl">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title nombre-title-detalle-modal">Detalle</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <div class="row detalle_de_modulo" id="cargando-1-fomulario"> 
                          <!--detalle de la compra-->
                        </div>

                        <div class="row" id="cargando-2-fomulario" style="display: none;">
                          <div class="col-lg-12 text-center">
                            <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                            <br />
                            <h4>Cargando...</h4>
                          </div>
                        </div> 

                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-outline-danger modal-eliminar-permanente" data-toggle="tooltip" data-original-title="Eliminar permanente" onclick=""><i class="fas fa-trash-alt"></i></button>
                        <button type="button" class="btn btn-outline-success modal-add-remove-visto-bueno" data-toggle="tooltip" data-original-title="Dar visto bueno" onclick=""><i class="fas fa-check"></i></button>
                        <button type="button" class="btn btn-success float-right" id="excel_compra" onclick="export_excel_detalle_factura()" ><i class="far fa-file-excel"></i> <span class="d-none d-sm-inline-block">Excel</span></button>
                        <a type="button" class="btn btn-info" id="print_pdf_compra" target="_blank" ><i class="fas fa-print"></i><span class="d-none d-sm-inline-block">Imprimir/PDF</span></a>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- MODAL - COMRPOBANTE multiple -->
                <div class="modal fade" id="modal-tabla-comprobantes-multiple">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                      <div class="modal-header"> 
                        <h4 class="modal-title titulo-comprobante-multiple">Lista de Comprobantes</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body row">                        
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12 mt-3">
                          <table id="tabla-comprobantes-multiple" class="table table-bordered table-striped display " style="width: 100% !important;">
                            <thead>
                              <tr>
                                <th class="">#</th>
                                <th data-toggle="tooltip" data-original-title="Opciones">OP</th>
                                <th data-toggle="tooltip" data-original-title="Documentos">Comprobante</th>
                                <th data-toggle="tooltip" data-original-title="Fecha de subida">Fecha</th>                          
                              </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                              <tr>
                                <th class="">#</th>
                                <th class="">OP</th>
                                <th>Doc</th>
                                <th>Fecha</th>                                    
                              </tr>
                            </tfoot>
                          </table>
                        </div>

                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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

        <?php  require 'script.php'; ?> 

        <!-- ZIP -->
        <script src="../plugins/jszip/jszip.js"></script>
        <script src="../plugins/jszip/dist/jszip-utils.js"></script>
        <script src="../plugins/FileSaver/dist/FileSaver.js"></script>

        <!-- table export EXCEL -->
        <script src="../plugins/export-xlsx/xlsx.full.min.js"></script>
        <script src="../plugins/export-xlsx/FileSaver.min.js"></script>
        <script src="../plugins/export-xlsx/tableexport.min.js"></script>

        <script type="text/javascript" src="scripts/resumen_gasto.js"></script>

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); });  </script>

      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
