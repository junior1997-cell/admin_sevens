<?php
  //Activamos el almacenamiento en el buffer
  ob_start();
  session_start();

  if (!isset($_SESSION["nombre"])){
    header("Location: index.php?file=".basename($_SERVER['PHP_SELF']));
  }else{ ?>
    <!DOCTYPE html>
    <html lang="es">
      <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title> Resumen de Factura | Admin Sevens</title>

        <?php $title = "Resumen de Activos Fijos"; require 'head.php'; ?>
        
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
            if ($_SESSION['resumen_factura']==1){
              //require 'enmantenimiento.php';
              ?>     

              <!--Contenido-->
              <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <div class="content-header">
                  <div class="container-fluid">
                    <div class="row mb-2">
                      <div class="col-sm-6">
                        <h1 class="m-0 nombre-trabajador">
                          Resumen de Factura 
                          <button class="btn btn-success btn-md btn-zip" onclick="desccargar_zip_comprobantes();">
                            <i class="far fa-file-archive fa-lg"></i> Comprobantes .zip 
                          </button>
                        </h1>
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
                            <div class="row">
                              <!-- modulos incluidos -->
                              <div class="col-lg-2 col-md-2 col-sm-2 col-12">
                                <label class="text-info" >Ver modulos</label><br>
                                <button type="button" class="btn btn-block bg-gradient-info btn-sm" data-toggle="modal" data-target="#modal-modulos-incluidos">
                                  <i class="fas fa-eye"></i> Módulos Incluidos
                                </button>
                              </div>

                              <!-- filtro por: fecha -->
                              <div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
                                <label for="filtros" >Fecha inicio </label>
                                <!-- fecha inicial -->
                                <input name="fecha_filtro" id="fecha_filtro_1" type="date" class="form-control form-control-sm m-b-1px" placeholder="Seleccionar fecha" onchange="cargando_search(); delay(function(){filtros()}, 50 );" />
                              </div>
                              <div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
                                <label for="filtros" >Fecha fin </label>
                                <!-- fecha final -->
                                <input name="fecha_filtro" id="fecha_filtro_2" type="date" class="form-control form-control-sm" placeholder="Seleccionar fecha" onchange="cargando_search(); delay(function(){filtros()}, 50 );" />
                              </div>

                              <!-- filtro por: proveedor -->
                              <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                <label for="filtros" class="cargando_proveedor">Proveedor &nbsp;<i class="text-dark fas fa-spinner fa-pulse fa-lg"></i><br /></label>
                                <select name="proveedor_filtro" id="proveedor_filtro" class="form-control select2" onchange="cargando_search(); delay(function(){filtros()}, 50 );" style="width: 100%;"> 
                                </select>
                              </div>

                              <!-- filtro por: proveedor -->
                              <div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
                                <label for="filtros" >Tipo comprobante </label>
                                <select name="tipo_comprobante_filtro" id="tipo_comprobante_filtro" class="form-control select2" onchange="cargando_search(); delay(function(){filtros()}, 50 );" style="width: 100%;"> 
                                  <option value="0">Todos</option>
                                  <option value="Factura">Factura</option>
                                  <option value="Boleta">Boleta</option>
                                </select>
                              </div>

                            </div>                        
                          </div>
                          <!-- /.card-header -->
                          <div class="card-body">

                            <!-- tabla resumen facturas compras -->
                            <div class="pb-3">
                              <h3 class="card-title mb-2">Resumen facturas: <b>Compras</b>    </h3>
                              <table id="tabla-principal" class="table table-bordered  table-striped display" style="width: 100% !important;">
                                <thead>
                                  <tr>
                                    <th colspan="12" class="cargando text-center bg-danger"><i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando... </th>
                                  </tr>
                                  <tr> 
                                    <th class="text-center">#</th> 
                                    <th class="text-center">Fecha</th>
                                    <th>Comprobante</th>
                                    <th>Documento</th>
                                    <th>RUC</th>
                                    <th>Razón social</th>                                     
                                    <th class="text-center">Subtotal</th>                                
                                    <th class="text-center">IGV</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Glosa</th> 
                                    <th class="text-center">Operación</th> 
                                    <th class="text-center">CFDI.</th>
                                    <th class="text-center">Módulo</th>                                                     
                                  </tr>
                                </thead>
                                <tbody> </tbody>
                                <tfoot> 
                                  <tr> 
                                    <th class="text-center text-black-50">#</th> 
                                    <th class="text-center text-black-50">Fecha</th>
                                    <th class="text-black-50">Comprobante</th>
                                    <th class="text-black-50">Documento</th>
                                    <th>RUC</th>
                                    <th class="text-black-50">Razón social</th>                                      
                                    <th class="text-right text-nowrap total-subtotal">Subtotal</th>                                
                                    <th class="text-right text-nowrap total-igv">IGV</th>
                                    <th class="text-right text-nowrap total-total">Total</th>
                                    <th class="text-center text-black-50">Glosa</th> 
                                    <th class="text-center text-black-50">Operación</th> 
                                    <th class="text-center text-black-50">CFDI.</th>
                                    <th class="text-center">Módulo</th>                                             
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
                            <!-- <li class="m-b-04rem"><i class="nav-icon fas fa-hands-helping"></i> SUB CONTRATO</li> -->
                            <!-- <li class="m-b-04rem"><img src="../dist/svg/negro-planilla-seguro-ico.svg" class="nav-icon" alt="" style="width: 21px !important;" > PLANILLA SEGURO</li> -->
                            <li class="m-b-04rem"><i class="nav-icon fas fa-network-wired"></i> OTRO GASTO</li>
                            <li class="m-b-04rem"><i class="fas fa-shuttle-van nav-icon"></i> TRANSPORTE</li>
                            <li class="m-b-04rem"><i class="fas fa-hotel nav-icon"></i> HOSPEDAJE</li>
                            <li class="m-b-04rem"><i class="fas fa-utensils nav-icon"></i> PENSION</li>
                            <li class="m-b-04rem"><i class="fas fa-hamburger nav-icon"></i> BREAK</li>
                            <li class="m-b-04rem"><i class="fas fa-drumstick-bite nav-icon"></i> COMIDA EXTRA</li>
                            <li class="m-b-04rem"><i class="nav-icon fas fa-receipt"></i> OTRA FACTURA <small class="text-red">(sin proyecto)</small></li>
                            <!-- <li>OTRO INGRESO</li> -->
                            <!-- <li class="m-b-04rem"><i class="fas fa-briefcase nav-icon"></i> PAGO ADMINISTRADORES</li> -->
                            <!-- <li class="m-b-04rem"><i class="fas fa-users nav-icon"></i> PAGO OBREROS</li> -->
                          </ol>
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

        <?php require 'script.php'; ?>    

        <script src="../plugins/jszip/jszip.js"></script>
        <script src="../plugins/jszip/dist/jszip-utils.js"></script>
        <script src="../plugins/FileSaver/dist/FileSaver.js"></script>

        <script type="text/javascript" src="scripts/resumen_factura.js"></script>
        
        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>

        <?php require 'extra_script.php'; ?>
        
      </body>
    </html>
    <?php    
  }
  ob_end_flush();
?>
