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
        <title>Admin Sevens | Resumen por Recibos por Honorario</title>

        <?php require 'head.php'; ?>

        <!--CSS  switch_MATERIALES-->
        <link rel="stylesheet" href="../dist/css/switch_materiales.css" />

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
          if ($_SESSION['Resumen_recibo_por_honorario']==1){
            ?>
            <!--Contenido-->
            <div class="content-wrapper">
              <!-- Content Header (Page header) -->
              <div class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1 class="m-0">Compras</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="compra.php">Home</a></li>
                        <li class="breadcrumb-item active">Compras</li>
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
                                    <button type="button" class="btn bg-gradient-success" id="btn_agregar" onclick="ver_form_add(); limpiar_form_compra();">
                                      <i class="fas fa-plus-circle"></i> Agregar
                                    </button>
                                    <button type="button" class="btn bg-gradient-warning" id="regresar" style="display: none;" onclick="regresar();">
                                      <i class="fas fa-arrow-left"></i> Regresar
                                    </button>
                                    <button type="button" id="btn-pagar" class="btn bg-gradient-success" data-toggle="modal" style="display: none;" data-target="#modal-agregar-pago" onclick="limpiar_form_pago_compra();">
                                      <i class="fas fa-dollar-sign"></i> Agregar Pago
                                    </button>                                     
                                  </h3>
                                </div>
                              </div>
                              <!-- Leyecnda pagos -->
                              <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 hiddenn leyecnda_pagos" style="background-color: aliceblue;">
                                <div class="text-slid-box">
                                  <div id="offer-box" class="contenedor">
                                    <div> <b>Leyenda-pago</b> </div>
                                    <ul class="offer-box cls-ul">
                                      <li>
                                        <span class="text-center badge badge-danger" >Pago sin iniciar </span> 
                                      </li>
                                      <li>
                                        <span class="text-center badge badge-warning" >Pago en proceso </span>
                                      </li>
                                      <li>
                                        <span class="text-center badge badge-success" >Pago completo</span>
                                      </li>
                                    </ul>
                                  </div>
                                </div>
                              </div>
                              <!-- Leyecnda saldos -->
                              <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 hiddenn leyecnda_saldos" style="background-color: #f0f8ff7d;">
                                <div class="text-slid-box">
                                  <div id="offer-box" class="contenedorr">
                                    <div> <b>Leyenda-saldos</b> </div>
                                    <ul class="offer-box clss-ul">
                                      <li>
                                        <span class="text-center badge badge-warning " >Pago nulo o pago en proceso </span> 
                                      </li>
                                      <li>
                                        <span class="text-center badge badge-success" >Pago Completo </span>
                                      </li>
                                      <li>
                                        <span class="text-center badge badge-danger" >Pago excedido</span>
                                      </li>
                                    </ul>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- End Main Top -->

                        <!-- /.card-header -->
                        <div class="card-body">

                          <!-- TABLA - COMPRAS -->
                          <div id="div_tabla_compra">
                            <h5><b>Lista de compras Por Facturas</b></h5>
                            <table id="tabla-compra" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">Acciones</th>
                                  <th>Fecha</th>
                                  <th>Proveedor</th>
                                  <th data-toggle="tooltip" data-original-title="Tipo y Número Comprobante">Tipo</th>
                                  <th data-toggle="tooltip" data-original-title="Detraccion">Detrac.</th>
                                  <th>Total</th>
                                  <th>Añadir pago</th>
                                  <th>Deposito</th>
                                  <th>Saldo</th>
                                  <th data-toggle="tooltip" data-original-title="Comprobantes">CFDI.</th>
                                  <th>Descripción</th>
                                  <th>Estado</th>
                                </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">Acciones</th>
                                  <th>Fecha</th>
                                  <th>Proveedor</th>
                                  <th data-toggle="tooltip" data-original-title="Tipo y Número Comprobante">Tipo</th>
                                  <th>Detrac.</th>
                                  <th>Total</th>
                                  <th>Añadir pago</th>
                                  <th>Deposito</th>
                                  <th>Saldo</th>
                                  <th>CFDI.</th>
                                  <th>Descripción</th>
                                  <th>Estado</th>
                                </tr>
                              </tfoot>
                            </table>
                            <br />
                            <h4><b>Lista de Compras Por Proveedor</b></h4>
                            <table id="tabla-compra-proveedor" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">Acciones</th>
                                  <th>Proveedor</th>
                                  <th>Cant</th>
                                  <th>Cel.</th>
                                  <th>Total</th>
                                </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">Acciones</th> 
                                  <th>Proveedor</th>
                                  <th>Cant</th>
                                  <th>Cel.</th>
                                  <th>Total</th>
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

                  <!-- Modal agregar proveedores -->
                  <div class="modal fade" id="modal-agregar-proveedor">
                    <div class="modal-dialog modal-dialog-scrollable modal-xl">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Agregar proveedor</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                        </div>

                        <div class="modal-body">
                          <!-- form start -->
                          <form id="form-proveedor" name="form-proveedor" method="POST">
                            <div class="card-body row">                               
                             
                              <!-- id proveedores -->
                              <input type="hidden" name="idproveedor_prov" id="idproveedor_prov" />

                              <!-- Tipo de documento -->
                              <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="tipo_documento_prov">Tipo de documento</label>
                                  <select name="tipo_documento_prov" id="tipo_documento_prov" class="form-control" placeholder="Tipo de documento">
                                    <option value="RUC">RUC</option>
                                    <option selected value="DNI">DNI</option>
                                  </select>
                                </div>
                              </div>

                              <!-- N° de documento -->
                              <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="num_documento_prov">N° RUC / DNI</label>
                                  <div class="input-group">
                                    <input type="number" name="num_documento_prov" class="form-control" id="num_documento_prov" placeholder="N° de documento" />
                                    <div class="input-group-append" data-toggle="tooltip" data-original-title="Buscar Reniec/SUNAT" onclick="buscar_sunat_reniec();">
                                      <span class="input-group-text" style="cursor: pointer;">
                                        <i class="fas fa-search text-primary" id="search"></i>
                                        <i class="fa fa-spinner fa-pulse fa-fw fa-lg text-primary" id="charge" style="display: none;"></i>
                                      </span>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <!-- Nombre -->
                              <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="nombre_prov">Razón Social / Nombre y Apellidos</label>
                                  <input type="text" name="nombre_prov" class="form-control" id="nombre_prov" placeholder="Razón Social o  Nombre" />
                                </div>
                              </div>

                              <!-- Direccion -->
                              <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="direccion_prov">Dirección</label>
                                  <input type="text" name="direccion_prov" class="form-control" id="direccion_prov" placeholder="Dirección" />
                                </div>
                              </div>

                              <!-- Telefono -->
                              <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="telefono_prov">Teléfono</label>
                                  <input type="text" name="telefono_prov" id="telefono_prov" class="form-control" data-inputmask="'mask': ['999-999-999', '+099 99 99 999']" data-mask />
                                </div>
                              </div>

                              <!-- Titular de la cuenta -->
                              <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="titular_cuenta_prov">Titular de la cuenta</label>
                                  <input type="text" name="titular_cuenta_prov" class="form-control" id="titular_cuenta_prov" placeholder="Titular de la cuenta" />
                                </div>
                              </div>

                              <!-- banco -->
                              <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="banco_prov">Banco</label>
                                  <select name="banco_prov" id="banco_prov" class="form-control select2" style="width: 100%;" onchange="formato_banco();">
                                    <!-- Aqui listamos los bancos -->
                                  </select>
                                  <!-- <small id="banco_validar" class="text-danger" style="display: none;">Por favor selecione un cargo</small> -->
                                </div>
                              </div>

                              <!-- Cuenta bancaria -->
                              <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="c_bancaria_prov" class="chargue-format-1">Cuenta Bancaria</label>
                                  <input type="text" name="c_bancaria_prov" class="form-control" id="c_bancaria_prov" placeholder="Cuenta Bancaria" data-inputmask="" data-mask />
                                </div>
                              </div>

                              <!-- CCI -->
                              <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="cci_prov" class="chargue-format-2">CCI</label>
                                  <input type="text" name="cci_prov" class="form-control" id="cci_prov" placeholder="CCI" data-inputmask="" data-mask />
                                </div>
                              </div>

                              <!-- Cuenta de detracciones -->
                              <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="c_detracciones_prov" class="chargue-format-3">Cuenta Detracciones</label>
                                  <input type="text" name="c_detracciones_prov" class="form-control" id="c_detracciones_prov" placeholder="Cuenta Detracciones" data-inputmask="" data-mask />
                                </div>
                              </div>                  

                            </div>
                            <!-- /.card-body -->
                            <button type="submit" style="display: none;" id="submit-form-proveedor">Submit</button>
                          </form>
                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-success" id="guardar_registro_proveedor">Guardar Cambios</button>
                        </div>
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
        
        <script type="text/javascript" src="scripts/resumen_rh.js"></script>

        <script>
          $(function () {
            $('[data-toggle="tooltip"]').tooltip();
          });
        </script>

        <script>
          if (localStorage.getItem("nube_idproyecto")) {
            console.log("icon_folder_" + localStorage.getItem("nube_idproyecto"));

            $("#ver-proyecto").html('<i class="fas fa-tools"></i> Proyecto: ' + localStorage.getItem("nube_nombre_proyecto"));

            $(".ver-otros-modulos-1").show();

            // $('#icon_folder_'+localStorage.getItem('nube_idproyecto')).html('<i class="fas fa-folder-open"></i>');
          } else {
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
