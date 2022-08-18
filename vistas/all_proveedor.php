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
        <title>Admin Sevens | Proveedor</title>

        <?php $title = "Proveedor"; require 'head.php'; ?>

      </head>
      <body class="hold-transition sidebar-collapse sidebar-mini layout-fixed layout-navbar-fixed">
        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper">
          <?php
          require 'nav.php';
          require 'aside.php';
          if ($_SESSION['recurso']==1){
            //require 'enmantenimiento.php';
            ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
              <!-- Content Header (Page header) -->
              <section class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1>Proveedores</h1>
                    </div>
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">proveedores</li>
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
                            <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-proveedor" onclick="limpiar();"><i class="fas fa-user-plus"></i> Agregar</button>
                            Admnistra de manera eficiente a tus proveedores.
                          </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                          <table id="tabla-proveedores" class="table table-bordered table-striped display" style="width: 100% !important;">
                            <thead>
                              <tr>
                                <th class="text-center">#</th>
                                <th class="">Aciones</th>
                                <th>Proveedor</th>
                                <th>Dirección</th>
                                <th>Cta. bancaria / CCI </th>
                                <th>Titular cuenta</th>
                                <th>Proveedor</th>
                                <th>Doc.</th>
                                <th>Num. Documento</th>
                                <th>Banco</th>
                                <th>Cta. Cte.</th>
                                <th>CCI</th>
                                <th>Cta. Detracciones</th>
                                <th>Titular Cta.</th>
                                <th>Direccion</th>
                                <th>Teléfono</th>
                              </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                              <tr>
                                <th class="text-center">#</th>
                                <th>Aciones</th>
                                <th>Proveedor</th>
                                <th>Dirección</th>
                                <th>C. bancaria / CCI</th>
                                <th>Titular cuenta</th>
                                <th>Proveedor</th>
                                <th>Doc.</th>
                                <th>Num. Documento</th>
                                <th>Banco</th>
                                <th>Cta. Cte.</th>
                                <th>CCI</th>
                                <th>Cta. Detracciones</th>
                                <th>Titular Cta.</th>
                                <th>Direccion</th>    
                                <th>Teléfono</th>                            
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
                        <h4 class="modal-title">Agregar proveedores</h4>
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
                                  <label for="tipo_documento">Tipo de documento <sup class="text-danger">*</sup></label>
                                  <select name="tipo_documento" id="tipo_documento" class="form-control" placeholder="Tipo de documento">
                                    <option value="RUC">RUC</option>
                                    <option selected value="DNI">DNI</option>
                                  </select>
                                </div>
                              </div>

                              <!-- N° de documento -->
                              <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="num_documento">N° RUC / DNI <sup class="text-danger">(unico*)</sup></label>
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
                                  <label for="nombre">Razón Social / Nombre y Apellidos <sup class="text-danger">*</sup></label>
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

                              <!-- Titular de la cuenta -->
                              <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="titular_cuenta">Titular de la cuenta</label>
                                  <input type="text" name="titular_cuenta" class="form-control" id="titular_cuenta" placeholder="Titular de la cuenta" />
                                </div>
                              </div>

                              <!-- banco -->
                              <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="banco">Banco <sup class="text-danger">*</sup></label>
                                  <select name="banco" id="banco" class="form-control select2" style="width: 100%;" onchange="formato_banco();">
                                    <!-- Aqui listamos los bancos -->
                                  </select>
                                  <!-- <small id="banco_validar" class="text-danger" style="display: none;">Por favor selecione un cargo</small> -->
                                </div>
                              </div>

                              <!-- Cuenta bancaria -->
                              <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="c_bancaria" class="chargue-format-1">Cuenta Bancaria</label>
                                  <input type="text" name="c_bancaria" class="form-control" id="c_bancaria" placeholder="Cuenta Bancaria" data-inputmask="" data-mask />
                                </div>
                              </div>

                              <!-- CCI -->
                              <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="cci" class="chargue-format-2">CCI</label>
                                  <input type="text" name="cci" class="form-control" id="cci" placeholder="CCI" data-inputmask="" data-mask />
                                </div>
                              </div>

                              <!-- Cuenta de detracciones -->
                              <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="c_detracciones" class="chargue-format-3">Cuenta Detracciones</label>
                                  <input type="text" name="c_detracciones" class="form-control" id="c_detracciones" placeholder="Cuenta Detracciones" data-inputmask="" data-mask />
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
                        <button type="submit" class="btn btn-success" id="guardar_registro">Guardar Cambios</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!--Modal ver proveedores-->
                <div class="modal fade" id="modal-ver-proveedores">
                  <div class="modal-dialog modal-dialog-scrollable modal-xm">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Datos proveedores</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <div id="datosproveedores" class="class-style"></div>
                      </div>
                    </div>
                  </div>
                </div> 

                <!--Modal ver mas detales del trabajador-->
                <div class="modal fade" id="modal-ver-mas-detalles-trabajador">
                  <div class="modal-dialog modal-dialog-scrollable modal-xm">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Datos Trabajador</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <div id="ver_mas_detalles_trabajador"></div>
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
        
        <?php require 'script.php';  ?>        

        <script type="text/javascript" src="scripts/all_proveedor.js"></script>

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>
        
      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
