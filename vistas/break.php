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
    <title>Admin Sevens | trabajadores</title>
    <?php
    require 'head.php';
    ?>
    <style>
        .tablee {
          width: 100%;
          border: 1px solid #000;
        }
        th, td {
          width: 25%;
          text-align: left;
          vertical-align: top;
          border: 1px solid #000;
          border-collapse: collapse;
          padding: 0.3em;
        }
        caption {
          padding: 0.3em;
        }
    </style>

  </head>
  <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed">
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
                  <h1>Break</h1>
                </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Break</li>
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
                      <!-- regresar -->
                      <h3 class="card-title mr-3" id="card-regresar" style="display: none;" style="padding-left: 2px;">
                        <button type="button" class="btn bg-gradient-warning" onclick="mostrar_form_table(1);despintar_btn_select();" style="height: 61px;"><i class="fas fa-arrow-left"></i> <span class="d-none d-sm-inline-block">Regresar</span> </button>
                      </h3>
                      <!-- Editar -->
                      <h3 class="card-title mr-3" id="card-editar" style="display: none; padding-left: 2px;">
                        <button type="button" class="btn bg-gradient-orange" onclick="editarbreak();" style="height: 61px;"><i class="fas fa-pencil-alt"></i> <span class="d-none d-sm-inline-block">Editar</span> </button>
                      </h3>
                      <!-- Guardar -->
                      <h3 class="card-title mr-3" id="card-guardar" style="display: none; padding-left: 2px;">
                        <button type="button" class="btn bg-gradient-success" onclick="guardaryeditar_semana_break();" style="margin-right: 10px; height: 61px;"><i class="far fa-save"></i> <span class="d-none d-sm-inline-block">Guardar</span> </button>
                      </h3>
                      <!-- regresar de comprobantes -->
                      <h3 class="card-title mr-3" id="regresar_aprincipal" style="display: none;" style="padding-left: 2px;">
                          <button type="button" class="btn bg-gradient-warning" onclick="regresar(); limpiar_comprobante();" style="height: 61px;"><i class="fas fa-arrow-left"></i> <span class="d-none d-sm-inline-block">Regresar</span> </button>
                      </h3>
                      <!-- Guardar comporbantees -->
                      <h3 class="card-title mr-3" id="guardar" style="display: none; padding-left: 2px;">
                        <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-comprobante" onclick="limpiar_comprobante()" style="margin-right: 10px; height: 61px;"><i class="far fa-save"></i> Agregar</button>
                      </h3>
                      
                      <!-- Botones de quincenas -->
                      <div id="Lista_breaks" class="row-horizon disenio-scroll " >
                        <!-- <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-asistencia" onclick="limpiar();"><i class="fas fa-user-plus"></i> Agregar </button>
                        <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-asistencia" onclick="limpiar();"><i class="fas fa-user-plus"></i> Agregar </button>-->
                      </div>   
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <!-- Lista de trabajdores -->
                      <div id="mostrar-tabla">
                        <table id="tabla-resumen-break-semanal" class="table table-bordered table-striped display" style="width: 100% !important;">
                          <thead>
                            <tr>
                              <th class="">Fecha semana</th>
                              <th>Total</th>
                              <th>Comprobantes</th>

                            </tr>
                          </thead>
                          <tbody></tbody>
                          <tfoot>
                            <tr>
                              <th class="">Fecha semana</th>
                              <th>Total</th>
                              <th>Comprobantes</th>
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                      
                      <!-- agregar trabajador al sistema -->
                      <div id="tabla-registro" style="display: none;">
                        <style>
                          table.colapsado {border-collapse: collapse;}
                        </style>
                        <div class="container table-responsive disenio-scroll">

                          <table class="table table-hover text-nowrap styletabla" style="border: black 1px solid;" border="1" style="width: 100%;" >
                              <thead style="background-color: #408c98; color: white;" >
                                <tr>
                                  <th class="text-center w-px-300">Día</th>
                                  <th class="text-center  w-px-135">Cantidad</th>
                                  <th class="text-center"> Parcial</th>
                                  <th class="text-center"> Descripción</th>
                                </tr>
                              </thead>
                              <tbody id="data_table_body">
                                <!--aqui va el listado de los días-->
                              </tbody>
                              <tfoot>
                                <th style="border-bottom: hidden;border-left: hidden;" ></th>
                                <th  class="text-center">Total</th>
                                <th id="monto_total" >----</th>
                                <th style="border-bottom: hidden;border-right: hidden;" ></th>
                              </tfoot>
                          </table>

                        </div>

                      </div>
                      <!-- Listar comprobantes-->
                      <div id="tabla-comprobantes" style="display: none;">
                        <table id="t-comprobantes" class="table table-bordered table-striped display" style="width: 100% !important;">
                          <thead>
                              <tr>
                                  <th>Aciones</th>
                                  <th data-toggle="tooltip" data-original-title="Forma Pago">Forma P.</th>
                                  <th data-toggle="tooltip" data-original-title="Tipo Comprobante">Tipo</th>
                                  <th data-toggle="tooltip" data-original-title="Número Comprobante">Número</th>
                                  <th data-toggle="tooltip" data-original-title="Fecha Emisión">Fecha</th>
                                  <th>Sub total</th>
                                  <th>IGV</th>
                                  <th>Monto</th>
                                  <th>Descripción</th>
                                  <th>Comprobante</th>
                                  <th>Estado</th>
                              </tr>
                          </thead>
                          <tbody></tbody>
                          <tfoot>
                              <tr>
                                  <th>Aciones</th>
                                  <th data-toggle="tooltip" data-original-title="Forma Pago">Forma P.</th>
                                  <th data-toggle="tooltip" data-original-title="Tipo Comprobante">Tipo</th>
                                  <th data-toggle="tooltip" data-original-title="Número Comprobante">Número</th>
                                  <th data-toggle="tooltip" data-original-title="Fecha Emisión">Fecha</th>
                                  <th>Sub total</th>
                                  <th>IGV</th>
                                  <th id="monto_total_f" style="color:#ff0000;background-color:#f3e700;"></th> 
                                  <th>Descripción</th>                                                   
                                  <th>Comprobante</th>
                                  <th>Estado</th>
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

            <!-- Modal cargando -->
            <div class="modal fade" id="modal-cargando" data-keyboard="false" data-backdrop="static">
              <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md">
                <div class="modal-content">
                  <div class="modal-body">
                    
                    <div id="icono-respuesta">
                      <!-- icon ERROR -->
                      <!-- icon success -->
                    </div>
                    
                    <!-- barprogress -->
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                      <div class="progress h-px-30" id="div_barra_progress">
                        <div id="barra_progress" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                          0%
                        </div>
                      </div>
                    </div>
                   <!-- <input type="hidden" class="class_fecha_${i}" value="${fecha_i}"/><input type="hidden" class="class_fecha_${i}" value="${fecha_f}"/>
                     boton -->
                    <div class="swal2-actions">
                      <div class="swal2-loader"></div>
                      <button onclick="cerrar_modal()" type="button" class="swal2-confirm swal2-styled" aria-label="" style="display: inline-block;">OK</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!--===============Modal agregar Comprobantes =========-->
            <div class="modal fade" id="modal-agregar-comprobante">
                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Agregar Comprobante: Break</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span class="text-danger" aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <!-- form start -->
                            <form id="form-agregar-comprobante" name="form-agregar-comprobante" method="POST">
                                <div class="card-body">
                                    <div class="row" id="cargando-1-fomulario">
                                        <!-- id semana_break -->
                                        <input type="hidden" name="idsemana_break" id="idsemana_break" />
                                        <!-- id factura_break -->
                                        <input type="hidden" name="idfactura_break" id="idfactura_break" />
                                      <!--Forma de pago -->
                                      <div class="col-lg-6">
                                        <div class="form-group">
                                          <label for="forma_pago">Forma Pago</label>
                                          <select name="forma_pago" id="forma_pago" class="form-control select2" style="width: 100%;">
                                            <option value="Transferencia">Transferencia</option>
                                            <option value="Efectivo">Efectivo</option>
                                            <option value="Crédito">Crédito</option>
                                          </select>
                                        </div>
                                      </div>
                                      <!-- Tipo de comprobante -->
                                      <div class="col-lg-6" id="content-t-comprob">
                                        <div class="form-group">
                                          <label for="tipo_comprovante">Tipo Comprobante</label>
                                          <select name="tipo_comprovante" id="tipo_comprovante" class="form-control select2" onchange="comprob_factura();" placeholder="Seleccinar un tipo de comprobante">
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
                                            <label for="codigo">Núm. comprobante </label>                               
                                            <input type="text"  name="nro_comprobante" id="nro_comprobante" class="form-control"  placeholder="Código"> 
                                          </div>                                                        
                                        </div>
                                        <!-- Fecha Emisión -->
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="fecha_emision">Fecha Emisión</label>
                                                <input class="form-control" type="date" id="fecha_emision" name="fecha_emision"/>
                                              </div>
                                        </div>
                                        <!-- Sub total -->
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="subtotal">Sub total</label>
                                                <input class="form-control" type="number"  id="subtotal" name="subtotal" placeholder="Sub total" readonly/>
                                              </div>
                                        </div>
                                        <!-- Fecha Emisión -->
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="igv">IGV</label>
                                                <input class="form-control" type="number"  id="igv" name="igv" placeholder="IGV"  readonly />
                                              </div>
                                        </div>
                                        <!-- Monto-->
                                        <div class="col-lg-4">
                                          <div class="form-group">
                                            <label for="monto">Monto total</label>                               
                                            <input type="number" name="monto" id="monto" class="form-control"  placeholder="Monto"  onkeyup="comprob_factura();"> 
                                          </div>                                                        
                                        </div>
                                        <!-- Descripcion-->
                                        <div class="col-lg-12">
                                          <div class="form-group">
                                            <label for="descripcion_f">Descripción </label> <br>
                                            <textarea name="descripcion" id="descripcion" class="form-control" rows="2"></textarea>
                                          </div>                                                        
                                        </div>
                                        <!--vaucher-->
                                        <div class="col-md-6 col-lg-12">
                                          
                                          <div class="col-lg-12 borde-arriba-naranja mt-2 mb-2"> </div>
                                          <label for="foto2">Factura en <b style="color: red;">(Imagen o PDF)</b></label> <br>
                                            <div class="text-center">
                                                <img onerror="this.src='../dist/img/default/img_defecto2.png';" src="../dist/img/default/img_defecto2.png" class="img-thumbnail" id="foto2_i" style="cursor: pointer !important;" width="auto" />
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
                                <button type="submit" style="display: none;" id="submit-form-comprobante">Submit</button>
                            </form>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_comprobante();">Close</button>
                            <button type="submit" class="btn btn-success" id="guardar_registro_comprobaante">Guardar Cambios</button>
                        </div>
                    </div>
                </div>
            </div>
            <!--===============Modal-ver-vaucher =========-->
            <div class="modal fade" id="modal-ver-comprobante">
              <div class="modal-dialog modal-dialog-scrollable modal-lg">
                  <div class="modal-content">
                      <div class="modal-header" style="background-color: #ce834926;" >
                          <h4 class="modal-title">Factura</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                      </div>
                      <div class="modal-body">
                          <div  class="class-style" style="text-align: center;"> 
                          <a class="btn btn-warning  btn-block" href="#" id="iddescargar" download="factura" style="padding:0px 12px 0px 12px !important;" type="button"><i class="fas fa-download"></i></a>
                            <br>
                            <img onerror="this.src='../dist/img/default/img_defecto.png';" src="../dist/img/default/img_defecto.png" class="img-thumbnail" id="img-factura" style="cursor: pointer !important;" width="auto" />
                              <div id="ver_fact_pdf" style="cursor: pointer !important;" width="auto"></div>
                          </div>
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

    <script type="text/javascript" src="scripts/break.js"></script>

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
