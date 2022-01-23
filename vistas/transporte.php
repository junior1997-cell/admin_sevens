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
        <title>Admin Sevens | Proveedor</title>
        <?php
        require 'head.php';
        ?>
        
        <!--CSS  switch_MATERIALES-->

    <link rel="stylesheet" href="../dist/css/switch_materiales.css">

    </head>
    <body class="hold-transition sidebar-collapse sidebar-mini layout-fixed layout-navbar-fixed">
        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper">
            <?php
          require 'nav.php';
          require 'aside.php';
          if ($_SESSION['viatico']==1){
          ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Transporte</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Transporte</li>
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
                                            <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-transporte" onclick="limpiar();"><i class="fas fa-plus-circle"></i> Agregar</button>
                                            Admnistra de manera eficiente tus Transportes.
                                        </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <table id="tabla-hospedaje" class="table table-bordered table-striped display" style="width: 100% !important;">
                                            <thead>
                                                <tr>
                                                    <th class="">Acciones</th>
                                                    <th>Fecha</th>
                                                    <th>Tipo</th>
                                                    <th>Descripción</th>
                                                    <th data-toggle="tooltip" data-original-title="Ruta" style="text-align: center;" >Ruta</th>
                                                    <th data-toggle="tooltip" data-original-title="Cantidad">Cantidad</th>
                                                    <th data-toggle="tooltip" data-original-title="Precio Unitario">P.U.</th>
                                                    <th data-toggle="tooltip" data-original-title="Parcial">Momto Parcial</th>
                                                    <th>Comprobante</th>
                                                    <th>Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr>
                                                <th class="">Acciones</th>
                                                    <th>Fecha</th>
                                                    <th>Tipo</th>
                                                    <th>Descripción</th>
                                                    <th data-toggle="tooltip" data-original-title="Ruta" style="text-align: center;">Ruta</th>
                                                    <th data-toggle="tooltip" data-original-title="Cantidad">Cantidad</th>
                                                    <th data-toggle="tooltip" data-original-title="Precio Unitario">P.U.</th>
                                                    <th data-toggle="tooltip" data-original-title="Parcial" style="background-color: #ffdd00;"> <span id="total_monto"></span></th>
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
                    <div class="modal fade" id="modal-agregar-transporte">
                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title"> <b>Agregar</b> </h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span class="text-danger" aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <!-- form start -->
                                    <form id="form-transporte" name="form-transporte" method="POST">
                                        <div class="card-body">
                                            <div class="row" id="cargando-1-fomulario">
                                                <!-- id proyecto -->
                                                <input type="hidden" name="idproyecto" id="idproyecto" />
                                                <!-- id hospedaje -->
                                                <input type="hidden" name="idtransporte" id="idtransporte" />

                                                <!-- Fecha 1 onchange="calculando_cantidad(); restrigir_fecha_ant();" onkeyup="calculando_cantidad(); -->
                                                <div class="col-lg-4 class_pading">
                                                    <div class="form-group">
                                                        <label for="fecha">Fecha</label>
                                                        <input type="date" name="fecha_viaje" class="form-control" id="fecha_viaje" />
                                                    </div>

                                                </div>
                                                <!-- tipo_viajero-->
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label for="unidad">Tipo</label>
                                                        <select name="tipo_viajero"  id="tipo_viajero" class="form-control select2"  style="width: 100%;">
                                                        <option value="Personal">Personal</option>
                                                        <option value="Material">Material</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <!-- tipo_ruta idtransporte,fecha_viaje,tipo_viajero,tipo_ruta,cantidad,precio_unitario,precio_parcial,ruta,descripcion-->
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label for="unidad">Tipo ruta</label>
                                                        <select name="tipo_ruta"  id="tipo_ruta" class="form-control select2" style="width: 100%;">
                                                        <option value="Nacional">Nacional</option>
                                                        <option value="Provincial">Provincial</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <!-- Cantidad  -->
                                                <div class="col-lg-4 class_pading">
                                                    <div class="form-group">
                                                        <label for="cantidad">Cantidad</label>
                                                        <input type="number" name="cantidad" class="form-control" id="cantidad" placeholder="Cantidad." onchange="calculando_total();" onkeyup="calculando_total();" />
                                                    </div>

                                                </div>
                                                <!--Precio Unitario-->
                                                <div class="col-lg-4 class_pading">
                                                    <div class="form-group">
                                                        <label for="marca">Precio Unitario</label>
                                                        <input type="numbre" name="precio_unitario" class="form-control" id="precio_unitario" placeholder="Precio Unitario" onchange="calculando_total();" onkeyup="calculando_total();" />
                                                    </div>                                                  

                                                </div>
                                                <!--Precio Parcial-->
                                                <div class="col-lg-4 class_pading">
                                                    <div class="form-group">
                                                        <label for="marca">Precio Parcial </label>
                                                        <input type="numbre" name="precio_parcial" class="form-control" id="precio_parcial" placeholder="Precio Parcial" />
                                                    </div>                                                  

                                                </div>
                                                <!-- ruta 2 -->
                                                <div class="col-lg-6 class_pading">
                                                    <div class="form-group">
                                                        <label for="ruta">Ruta <span style="font-size: 12px;font-weight: normal;" >ej. Juliaca-Lima</span></label>
                                                        <textarea name="ruta" id="ruta" class="form-control" rows="2"></textarea>
                                                    </div>

                                                </div>
                                                <!--Descripcion-->
                                                <div class="col-lg-6 class_pading">
                                                    <div class="form-group">
                                                        <label for="descripcion_pago">Descripción <span style="font-size: 12px;font-weight: normal;" >ej. nombre,Lima,1 día</span> </label> <br>
                                                        <textarea name="descripcion" id="descripcion" class="form-control" rows="2"></textarea>
                                                    </div>                                              
                                                </div>
                                                <!-- Factura -->
                                                <div class="col-md-6 col-lg-6">
                                                    <label for="foto2">Factura <b style="color: red;">(Imagen o PDF)</b></label> <br>
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
                                        <button type="submit" style="display: none;" id="submit-form-transporte">Submit</button>
                                    </form>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar();">Close</button>
                                    <button type="submit" class="btn btn-success" id="guardar_registro">Guardar Cambios</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--===============Modal-ver-ficha-tècnica =========-->
                    <div class="modal fade" id="modal-ver-comprobante">
                          <div class="modal-dialog modal-dialog-scrollable modal-xl ">
                              <div class="modal-content">
                                  <div class="modal-header">
                                      <h4 class="modal-title">Comprobante Transporte</h4>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span class="text-danger" aria-hidden="true">&times;</span>
                                      </button>
                                  </div>
                                  <div class="modal-body">
                                      <div  class="class-style" style="text-align: center;"> 
                                      <a class="btn btn-warning  btn-block" href="#" id="iddescargar" download=" Comprobante transporte" style="padding:0px 12px 0px 12px !important;" type="button"><i class="fas fa-download"></i></a>
                                        <br>
                                        <img onerror="this.src='../dist/img/default/img_defecto.png';" src="../dist/img/default/img_defecto.png" class="img-thumbnail" id="img-factura" style="cursor: pointer !important;" width="auto" />
                                          <div id="ver_fact_pdf" style="cursor: pointer !important;" width="auto"></div>
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
        <script type="text/javascript" src="scripts/transporte.js"></script>

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
