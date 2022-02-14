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
        <title>Admin Sevens | Activos fijos</title>
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
          if ($_SESSION['activo_fijo']==1){
          ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1><b>Activos fijos</b></h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Activos fijos</li>
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
                                            Admnistra de manera los activos fijos.
                                        </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <table id="tabla-otro_servicio" class="table table-bordered table-striped display" style="width: 100% !important;">
                                            <thead>
                                                <tr>
                                                    <th class="">Acciones</th>                                                    
                                                    <th data-toggle="tooltip" data-original-title="Forma Pago">Forma P.</th>
                                                    <th data-toggle="tooltip" data-original-title="Tipo Comprobante">Tipo</th>
                                                    <th data-toggle="tooltip" data-original-title="Número Comprobante">Número</th>
                                                    <th>Fecha</th>
                                                    <th>Subtotal</th>
                                                    <th>IGV</th>
                                                    <th>Monto Total </th>
                                                    <th>Descripción </th>
                                                    <th>Comprobante</th>
                                                    <th>Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr>
                                                    <th class="">Acciones</th>
                                                    <th data-toggle="tooltip" data-original-title="Forma Pago">Forma P.</th>
                                                    <th data-toggle="tooltip" data-original-title="Tipo Comprobante">Tipo</th>
                                                    <th data-toggle="tooltip" data-original-title="Número Comprobante">Número</th>
                                                    <th>Fecha</th>
                                                    <th>Subtotal</th>
                                                    <th>IGV</th>
                                                    <th id="total_monto"></th>
                                                    <th>Descripción </th>
                                                    <th>Comprobante</th>
                                                    <th>Estado</th>                                            
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
                    <div class="modal fade" id="modal-agregar-otro_servicio">
                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title"> <b>Agregar:</b> Activo fijo</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span class="text-danger" aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <!-- form start -->
                                    <form id="form-activos-fijos" name="form-activos-fijos" method="POST">
                                        <div class="card-body">
                                            <div class="row" id="cargando-1-fomulario">
                                                <!-- id idactivos_fijos -->
                                                <input type="hidden" name="idactivos_fijos" id="idactivos_fijos" />
                                                <!-- Tipo de comprobante -->
                                                <!-- nombre_de_activo -->
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label for="nombre_de_activo">Nombre <sup class="text-danger">*</sup> </label>
                                                        <input class="form-control" type="text" id="nombre_de_activo" name="nombre_de_activo" placeholder="Nombre de activo" required/>
                                                    </div>
                                                </div>
                                                 <!-- Modelo -->
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label for="modelo_de_activo">Modelo <sup class="text-danger">*</sup> </label>
                                                        <input class="form-control" type="text" id="modelo_de_activo" name="modelo_de_activo" placeholder="Modelo"/>
                                                    </div>
                                                </div>
                                                <!-- Serie -->
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label for="serie_de_activo">Serie </label>
                                                        <input class="form-control" type="text" id="serie_de_activo" name="serie_de_activo" placeholder="Serie"/>
                                                    </div>
                                                </div>
                                                 <!-- Marca -->
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label for="marca_de_activo">Marca </label>
                                                        <input class="form-control" type="text" id="marca_de_activo" name="marca_de_activo" placeholder="Marca de activo"/>
                                                    </div>
                                                </div>
                                                <!--forma pago-->
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                    <label for="forma_de_pago">Forma Pago <sup class="text-danger">*</sup> </label>
                                                    <select name="forma_de_pago" id="forma_de_pago" class="form-control select2" style="width: 100%;">
                                                        <option value="Transferencia">Transferencia</option>
                                                        <option value="Efectivo">Efectivo</option>
                                                        <option value="Crédito">Crédito</option>
                                                    </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6" id="content-t-comprob">
                                                    <div class="form-group">
                                                    <label for="tipo_comprobante">Tipo Comprobante <sup class="text-danger">*</sup> </label>
                                                    <select name="tipo_comprobante" id="tipo_comprobante" class="form-control select2" onchange="comprob_factura();" onkeyup="comprob_factura();" placeholder="Seleccinar un tipo de comprobante">
                                                        <option value="Ninguno">Ninguno</option>
                                                        <option value="Boleta">Boleta</option>
                                                        <option value="Factura">Factura</option>
                                                        <option value="Nota_de_venta">Nota de venta</option>
                                                    </select>
                                                    </div>
                                                </div>
                                                <!-- Código-->
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="numero_comprobante" data-toggle="tooltip" data-placement="top" title="Número comprobante">Núm. comprobante </label>                               
                                                        <input type="text"  name="numero_comprobante" id="numero_comprobante" class="form-control"  placeholder="Código"> 
                                                    </div>                                                        
                                                </div>

                                                <!-- Fecha--> 
                                                <div class="col-lg-6 class_pading">
                                                    <div class="form-group">
                                                        <label for="fecha">Fecha Emisión <sup class="text-danger">*</sup></label>
                                                        <input type="date" class="form-control" name="fecha_comprobante"  id="fecha_comprobante" />
                                                    </div>

                                                </div>
                                                <!-- Sub total -->
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label for="subtotal">Sub total</label>
                                                        <input class="form-control subtotal" type="number" id="subtotal" name="subtotal" placeholder="Sub total" readonly/>
                                                    </div>
                                                </div>
                                                <!-- IGV -->
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label for="igv">IGV</label>
                                                        <input class="form-control igv" type="number"  id="igv" name="igv" placeholder="IGV" readonly />
                                                    </div>
                                                </div>
                                                <!--Precio Parcial-->
                                                <div class="col-lg-4 class_pading">
                                                    <div class="form-group">
                                                        <label for="marca">Monto total <sup class="text-danger">*</sup> </label>
                                                        <input type="number" class="form-control total"  name="total" id="total" onchange="comprob_factura();" onkeyup="comprob_factura();" placeholder="Precio Parcial" />
                                                      
                                                    </div>                                                  
                                                </div>
                                                <!--Descripcion-->
                                                <div class="col-lg-12 class_pading">
                                                    <div class="form-group">
                                                        <label for="descripcion_pago">Descripción</label> <br>
                                                        <textarea name="descripcion" id="descripcion" class="form-control" rows="2"></textarea>
                                                    </div>                                              
                                                </div>
                                                <!-- Factura -->
                                                <div class="col-md-6 col-lg-6">
                                                    <label for="foto2">Comprobante <b style="color: red;">(Imagen o PDF)</b></label> <br>
                                                      <div class="text-center">
                                                          <img onerror="this.src='../dist/img/default/pdf.png';" src="../dist/img/default/pdf.png" class="img-thumbnail" id="foto2_i" style="cursor: pointer !important;" width="auto" height="150px" />
                                                          <div id="ver_pdf"></div>
                                                      </div>
                                                    <input style="display: none;" type="file" name="foto2" id="foto2" accept="image/*, .pdf" />
                                                    <input type="hidden" name="foto2_actual" id="foto2_actual" />
                                                    <div class="text-center" id="foto2_nombre"><!-- aqui va el nombre de la FOTO --></div>

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
                                        <button type="submit" style="display: none;" id="submit-form-activos-fijos">Submit</button>
                                    </form>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar();">Close</button>
                                    <button type="submit" class="btn btn-success" id="guardar_registro">Guardar Cambios</button>
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
        
        <script type="text/javascript" src="scripts/activos_fijos.js"></script>

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
