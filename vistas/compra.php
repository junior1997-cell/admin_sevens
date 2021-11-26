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
        <title>Admin Sevens | Compras</title>
        <?php
          require 'head.php';
        ?>
        <!-- Theme style -->
        <!-- <link rel="stylesheet" href="../dist/css/adminlte.min.css"> -->
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
            if ($_SESSION['compra']==1){
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
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            <!--data-toggle="modal" data-target="#modal-agregar-compra"  onclick="limpiar();"-->
                                            <button type="button" class="btn bg-gradient-success" id="btn_agregar" onclick="ver_form_add();"><i class="fas fa-plus-circle"></i> Agregar</button>
                                            <button type="button" class="btn bg-gradient-warning" id="regresar" style="display: none;" onclick="regresar();"><i class="fas fa-arrow-left"></i> Regresar</button>
                                        </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <table id="tabla-compra" class="table table-bordered table-striped display" style="width: 100% !important;">
                                            <thead>
                                                <tr>
                                                    <th class="">Aciones</th>
                                                    <th>Empresa</th>
                                                    <th>Nombre de proyecto</th>
                                                    <th>Ubicación</th>
                                                    <th>Costo</th>
                                                    <th>Docs</th>
                                                    <th>Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr>
                                                    <th class="">Aciones</th>
                                                    <th>Empresa</th>
                                                    <th>Nombre de proyecto</th>
                                                    <th>Ubicación</th>
                                                    <th>Costo</th>
                                                    <th>Docs</th>
                                                    <th>Estado</th>
                                                </tr>
                                            </tfoot>
                                        </table>

                                        <div id="agregar_compras" style="display: none;">
                                            <div class="modal-body">
                                                <!-- form start -->
                                                 <form id="form-compras" name="form-compras" method="POST">
                                                     <div class="card-body">
                                                        <div class="row" id="cargando-1-fomulario">
                                                            <!-- id proyecto -->
                                                            <input type="hidden" name="idproyecto" id="idproyecto" />

                                                            <!-- Tipo de Empresa -->
                                                            <div class="col-lg-7">
                                                                <div class="form-group">
                                                                    <label for="idproveedor">Proveedor</label>
                                                                    <select id="idproveedor" name="idproveedor" class="form-control select2 " data-live-search="true" required title="Seleccione cliente">                              
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <!-- adduser -->
                                                            <div class="col-lg-1">
                                                                <div class="form-group">
                                                                    <label for="Add" style="color: white;">.</label>
                                                                    <a data-toggle="modal" href="#agregar_cliente">
                                                                        <button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#modal-agregar-proveedor"  onclick="limpiar();">
                                                                            <i class="fa fa-user-plus" aria-hidden="true"></i>
                                                                        </button>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <!-- fecha -->
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label for="fecha_compra">Fecha </label>
                                                                    <input type="date" name="fecha_compra" id="fecha_compra" class="form-control" placeholder="Fecha" />
                                                                </div>
                                                            </div>
                                                            <!-- Tipo de comprobante -->
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label for="tipo_comprovante">Tipo Comprobante</label>
                                                                    <select name="tipo_comprovante" id="tipo_comprovante" class="form-control select2" placeholder="Seleccinar un tipo de comprobante">
                                                                        <option selected value="Boleta">Boleta</option>
                                                                        <option value="Factura">Factura</option>
                                                                        <option value="Ticket">Ticket</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <!-- Monto-->
                                                            <div class="col-lg-2">
                                                                <div class="form-group">
                                                                    <label for="serie_comprovante">N° de Comprobante</label>
                                                                    <input type="text" name="serie_comprovante" id="serie_comprovante" class="form-control" placeholder="N° de Comprobante" />
                                                                </div>
                                                            </div>
                                                            <!-- Descripcion-->
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label for="descripcion">Descripción </label> <br />
                                                                    <textarea name="descripcion" id="descripcion" class="form-control" rows="1"></textarea>
                                                                </div>
                                                            </div>
                                                            <!--Boton agregar material-->
                                                            <div class="col-lg-3 xs-12">
                                                                <a data-toggle="modal" data-target="#modal-elegir-material">
                                                                    <button id="btnAgregarArt" type="button" class="btn btn-success"><span class="fa fa-plus"></span> Agregar Material</button>
                                                                </a>
                                                            </div>

                                                            <!--tabla detalles plantas-->
                                                            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
                                                                <br />
                                                                <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                                                                    <thead style="background-color: #ff6c046b;">
                                                                        <th>Opciones</th>
                                                                        <th>Material</th>
                                                                        <th>Cantidad</th>
                                                                        <th>Precio Compra</th>
                                                                        <th>Descuento</th>
                                                                        <th>Subtotal</th>
                                                                    </thead>
                                                                    <tfoot>
                                                                        <th></th>
                                                                        <th></th>
                                                                        <th></th>
                                                                        <th></th>
                                                                        <th class="text-center"><h4>TOTAL</h4></th>
                                                                        <th>
                                                                            <b>
                                                                                <h4 class="text-right" id="total" style="font-weight: bold;">S/. 0.00</h4>
                                                                                <input type="hidden" name="total_venta" id="total_venta" />
                                                                            </b>
                                                                        </th>
                                                                    </tfoot>
                                                                    <tbody></tbody>
                                                                </table>
                                                            </div>
                                                            <div class="row" id="cargando-2-fomulario" style="display: none;">
                                                                <div class="col-lg-12 text-center">
                                                                    <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                                                                    <br />
                                                                    <h4>Cargando...</h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- /.card-body -->
                                                    <button type="submit" style="display: none;" id="submit-form-compras">Submit</button>
                                                </form>
                                            </div>

                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-danger" onclick="regresar();" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-success" style="display: none;" id="guardar_registro_compras">Guardar Cambios</button>
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
                                          <div class="card-body">
                                              <div class="row" id="cargando-1-fomulario">
                                                  <!-- id proyecto -->
                                                  <input type="hidden" name="idproyecto" id="idproyecto" />
                                                  <!-- id proveedores -->
                                                  <input type="hidden" name="idproveedor" id="idproveedor" />

                                                  <!-- Tipo de documento -->
                                                  <div class="col-lg-4">
                                                      <div class="form-group">
                                                          <label for="tipo_documento">Tipo de documento</label>
                                                          <select name="tipo_documento" id="tipo_documento" class="form-control" placeholder="Tipo de documento">
                                                              <option value="RUC">RUC</option>
                                                              <option selected value="DNI">DNI</option>
                                                          </select>
                                                      </div>
                                                  </div>
                                                  <!-- N° de documento -->
                                                  <div class="col-lg-4">
                                                      <div class="form-group">
                                                          <label for="num_documento">N° RUC / DNI</label>
                                                          <div class="input-group">
                                                              <input type="number" name="num_documento" class="form-control" id="num_documento" placeholder="N° de documento" />
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
                                                          <label for="nombre">Razón Social / Nombre y Apellidos</label>
                                                          <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Razón Social o  Nombre" />
                                                      </div>
                                                  </div>
                                                  <!-- Direccion -->
                                                  <div class="col-lg-4">
                                                      <div class="form-group">
                                                          <label for="direccion">Dirección</label>
                                                          <input type="text" name="direccion" class="form-control" id="direccion" placeholder="Dirección" />
                                                      </div>
                                                  </div>
                                                  <!-- Telefono -->
                                                  <div class="col-lg-4">
                                                      <div class="form-group">
                                                          <label for="telefono">Teléfono</label>
                                                          <input type="text" name="telefono" id="telefono" class="form-control" data-inputmask="'mask': ['999-999-999', '+099 99 99 999']" data-mask />
                                                      </div>
                                                  </div>
                                                  <!-- Cuenta bancaria -->
                                                  <div class="col-lg-4">
                                                      <div class="form-group">
                                                          <label for="c_bancaria">Cuenta Bancaria</label>
                                                          <input type="number" name="c_bancaria" class="form-control" id="c_bancaria" placeholder="Cuenta Bancaria" />
                                                      </div>
                                                  </div>
                                                  <!-- fecha de nacimiento -->
                                                  <div class="col-lg-4">
                                                      <div class="form-group">
                                                          <label for="fecha_nacimiento">Cuenta Detracciones</label>
                                                          <input type="number" name="c_detracciones" class="form-control" id="c_detracciones" placeholder="Cuenta Bancaria" />
                                                      </div>
                                                  </div>
                                                  <!-- banco -->
                                                  <div class="col-lg-4">
                                                      <div class="form-group">
                                                          <label for="banco">Banco</label>
                                                          <select name="banco" id="banco" class="form-control select2" style="width: 100%;">
                                                              <option value="1">BCP</option>
                                                              <option value="2">BBVA</option>
                                                              <option value="3">SCOTIA BANK</option>
                                                              <option value="4">INTERBANK</option>
                                                              <option value="5">NACIÓN</option>
                                                          </select>
                                                          <small id="banco_validar" class="text-danger" style="display: none;">Por favor selecione un cargo</small>
                                                      </div>
                                                  </div>
                                                  <!-- Titular de la cuenta -->
                                                  <div class="col-lg-4">
                                                      <div class="form-group">
                                                          <label for="titular_cuenta">Titular de la cuenta</label>
                                                          <input type="text" name="titular_cuenta" class="form-control" id="titular_cuenta" placeholder="Titular de la cuenta" />
                                                      </div>
                                                  </div>
                                              </div>

                                              <div class="row" id="cargando-2-fomulario" style="display: none;">
                                                  <div class="col-lg-12 text-center">
                                                      <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                                                      <br />
                                                      <h4>Cargando...</h4>
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
                      <!-- Modal elegir material -->
                      <div class="modal fade" id="modal-elegir-material">
                          <div class="modal-dialog modal-dialog-scrollable modal-lg">
                              <div class="modal-content">
                                  <div class="modal-header">
                                      <h4 class="modal-title">Seleccionar material</h4>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span class="text-danger" aria-hidden="true">&times;</span>
                                      </button>
                                  </div>
                                  <div class="modal-body table-responsive">
                                        <table id="tblamateriales" class="table table-striped table-bordered table-condensed table-hover" style="width: 100% !important;">
                                            <thead>
                                                <th>Opciones</th>
                                                <th>Nombre</th>
                                                <th>Precio U.</th>
                                                <th>Descripción</th>
                                            </thead>
                                            <tbody>
                                            
                                            </tbody>
                                            
                                        </table>
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

        <!--<script type="text/javascript" src="scripts/all_proveedor.js"></script>-->
        <script type="text/javascript" src="scripts/compra.js"></script>
        <script>
            $(function () {
                $('[data-toggle="tooltip"]').tooltip();
            });
        </script>

        <script>
            if (localStorage.getItem("nube_idproyecto")) {
                console.log("icon_folder_" + localStorage.getItem("nube_idproyecto"));

                $("#ver-proyecto").html('<i class="fas fa-tools"></i> Proyecto: ' + localStorage.getItem("nube_nombre_proyecto"));

                $("#ver-otros-modulos-1").show();

                // $('#icon_folder_'+localStorage.getItem('nube_idproyecto')).html('<i class="fas fa-folder-open"></i>');
            } else {
                $("#ver-proyecto").html('<i class="fas fa-tools"></i> Selecciona un proyecto');

                $("#ver-otros-modulos-1").hide();
            }
        </script>
    </body>
</html>
<?php    
  }
  ob_end_flush();
?>
