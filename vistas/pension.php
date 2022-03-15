<?php
  //Activamos el almacenamiento en el buffer
  ob_start();

  session_start();
  if (!isset($_SESSION["nombre"])){
    header("Location: index.php");
  }else{
    ?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Sevens | Pensión</title>
    <?php
    require 'head.php';
    ?>
    
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
                  <h1>Pensión - <b id="nomb_pension_head"></b> </h1>
                </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Pensión</li>
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
                      <!-- Guardar pension -->
                      <h3 class="card-title mr-3" id="guardar_pension" style=" padding-left: 2px;">
                        <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-pension" onclick="limpiar_pension()" style="margin-right: 10px; height: 61px;"><i class="far fa-save"></i> Agregar Pensión</button>
                      </h3>
                      <!-- regresar -->
                      <h3 class="card-title mr-3" id="card-regresar" style="display: none; padding-left: 2px;">
                        <button type="button" class="btn bg-gradient-warning" onclick="mostrar_form_table(1);despintar_btn_select();" style="height: 61px;"><i class="fas fa-arrow-left"></i> <span class="d-none d-sm-inline-block">Regresar</span> </button>
                      </h3>
                      <!-- Editar -->
                      <h3 class="card-title mr-3" id="card-editar" style="display: none; padding-left: 2px;">
                        <button type="button" class="btn bg-gradient-orange" onclick="editarbreak();" style="height: 61px;"><i class="fas fa-pencil-alt"></i> <span class="d-none d-sm-inline-block">Editar</span> </button>
                      </h3>
                      <!-- Guardar -->
                      <h3 class="card-title mr-3" id="card-guardar" style="display: none; padding-left: 2px;">
                        <button type="button" class="btn bg-gradient-success" onclick="guardaryeditar_semana_pension();" style="margin-right: 10px; height: 61px;"><i class="far fa-save"></i> <span class="d-none d-sm-inline-block">Guardar</span> </button>
                      </h3>
                      <!-- regresar de comprobantes -->
                      <h3 class="card-title mr-3" id="regresar_aprincipal" style="display: none; padding-left: 2px;">
                          <button type="button" class="btn bg-gradient-warning" onclick="regresar(); limpiar_comprobante();" style="height: 61px;"><i class="fas fa-arrow-left"></i> Regresar</button>
                      </h3>
                      <!-- Guardar comporbantees -->
                      <h3 class="card-title mr-3" id="guardar" style="display: none; padding-left: 2px;">
                        <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-comprobante" onclick="limpiar_comprobante()" style="margin-right: 10px; height: 61px;"><i class="far fa-save"></i> Agregar</button>
                      </h3>
                      
                      <!-- Botones de quincenas -->
                      <div id="List_smnas_pen" style="display: none; padding-left: 2px;" class="row-horizon disenio-scroll " >
                        <!-- <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-asistencia" onclick="limpiar();"><i class="fas fa-user-plus"></i> Agregar </button>
                        <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-asistencia" onclick="limpiar();"><i class="fas fa-user-plus"></i> Agregar </button>-->
                      </div>   
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <!-- Tabla principal resumen de las penciones -->
                      <div id="mostrar-tabla">
                        <table id="tabla-resumen-break-semanal" class="table table-bordered table-striped display" style="width: 100% !important;">
                          <thead>
                            <tr>
                              <th class="text-center">#</th>
                              <th><i class="fas fa-cogs"></i></th>
                              <th>Pension</th>
                              <th>Descripción</th>
                              <th>Total</th>
                              <th>Servicios</th>
                              <th>Comprobantes</th>
                              <th>Saldo</th>

                            </tr>
                          </thead>
                          <tbody></tbody>
                          <tfoot>
                            <tr>
                              <th class="text-center">#</th>
                              <th><i class="fas fa-cogs"></i></th>
                              <th>Pension</th>
                              <th>Descripción</th>
                              <th id="total_pension" class="text-right">S/. <i class="fas fa-spinner fa-pulse fa-sm"></i></th>
                              <th>Servicios</th>
                              <th>Comprobantes</th>
                              <th id="total_saldo" class="text-right">S/. <i class="fas fa-spinner fa-pulse fa-sm"></i></th>
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                      <!-- Registrar pension al sistema -->
                      <div id="tabla-registro" style="display: none;">
                        <style> .w-pxx-80{ width: 80px; } .clas_pading{ padding: 0.20rem  0.75rem  0.20rem  0.75rem !important;} </style>
                        <div class="container table-responsive disenio-scroll" style="max-width: 1241px!important;" >

                        <table class="table table-hover text-nowrap styletabla" style="border: black 1px solid;" border="1" style="width: 100%;" >
                          <thead style="background-color: #bebebe1f; color: black;" >
                            <tr>
                              <th  rowspan="2" colspan="1" class="text-center w-px-300">Descripción</th>
                              <th  rowspan="2" colspan="1" class="text-center  w-px-135">Precio actual</th>
                              <th  colspan="7" class="text-center clas_pading">Días de la semana</th>
                              <th  rowspan="2" colspan="1" class="text-center">Cantidad</th>
                              <th  rowspan="2" colspan="1" class="text-center">Adicional</th>
                              <th  rowspan="2" colspan="1" class="text-center">Parcial</th>
                              <th  rowspan="2" colspan="1" class="text-center">Descripción</th>
                            </tr>
                            <tr id="bloque_fechas"></tr>
                          </thead>
                          <tbody class="tcuerpo" id="data_table_body">
                            <!--aqui va el listado de los días-->
                          </tbody>
                          <tfoot>
                            <tr>
                            <th colspan="10" style="border-bottom: hidden;border-left: hidden;"></th>
                            <th class="text-center">Total</th>
                            <th class="text-center" id="parcial_total_x_semana">----</th>
                            <th class="text-center" style="border-bottom: hidden;border-right: hidden;"></th>
                            </tr>
                          </tfoot>
                        </table>

                        </div>

                      </div>

                      <!-- cargando tabla registro de pension -->
                      <div class="row" id="cargando-registro-pension" style="display: none;">
                        <div class="col-lg-12 text-center">
                          <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                          <br />
                          <h4>Cargando...</h4>
                        </div>
                      </div>

                      <!-- Listar comprobantes-->
                      <div id="tabla-comprobantes" style="display: none;">
                        <table id="t-comprobantes" class="table table-bordered table-striped display" style="width: 100% !important;">
                          <thead>
                              <tr>
                                  <th class="text-center">#</th>
                                  <th>Aciones</th>
                                  <th data-toggle="tooltip" data-original-title="Forma de pago">Forma </th>
                                  <th data-toggle="tooltip" data-original-title="Tipo Comprobante">Comprob </th>
                                  <th data-toggle="tooltip" data-original-title="Fecha Emisión">F. Emisión</th>
                                  <th>Sub total</th>
                                  <th>IGV</th>
                                  <th>Total</th>
                                  <th>Descripción</th>
                                  <th data-toggle="tooltip" data-original-title="Comprobante">Comprob </th>
                                  <th>Estado</th>
                              </tr>
                          </thead>
                          <tbody></tbody>
                          <tfoot>
                              <tr>
                                  <th class="text-center">#</th>
                                  <th>Aciones</th>
                                  <th data-toggle="tooltip" data-original-title="Forma de pago">Forma </th>
                                  <th data-toggle="tooltip" data-original-title="Tipo Comprobante">Comprob </th>
                                  <th data-toggle="tooltip" data-original-title="Fecha Emisión">F. Emisión</th>
                                  <th>Sub total</th>
                                  <th>IGV</th>
                                  <th class="text-nowrap text-right" id="monto_total_f"></th> 
                                  <th>Descripción</th>                                                   
                                  <th data-toggle="tooltip" data-original-title="Comprobante">Comprob </th>
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
            <!--===============Modal agregar pension =========-->
            <div class="modal fade" id="modal-agregar-pension">
              <div class="modal-dialog modal-dialog-scrollable modal-lg">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h4 class="modal-title">Agregar nueva pensión</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                      </div>

                      <div class="modal-body">
                          <!-- form start -->
                          <form id="form-agregar-pension" name="form-agregar-pension" method="POST">
                              <div class="card-body">
                                  <div class="row" id="cargando-1-fomulario">
                                      <!-- id semana_break -->
                                      <input type="hidden" name="idproyecto_p" id="idproyecto_p" />
                                      <!-- id factura_break -->
                                      <input type="hidden" name="idpension" id="idpension" />

                                    <!-- proveedor -->
                                    <div class="col-lg-6">
                                      <div class="form-group">
                                        <label>Proveedor <sup  class="text-danger">*</sup> </label>                               
                                        <select name="proveedor" id="proveedor" class="form-control select2" style="width: 100%;" >                                    
                                        </select>
                                      </div>                                                        
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Servicio <sup  class="text-danger">*</sup> <small class="text-danger">los compos que seleccione, al editar no se borrarar</small> </label>
                                            <div class="select2-purple">
                                              <select class="form-control select2" multiple="multiple" name="servicio_p[]"  id="servicio_p" data-dropdown-css-class="select2-purple" data-placeholder="Seleccione"  style="width: 100%;">
                                                <option value="Desayuno">Desayuno</option>
                                                <option value="Almuerzo">Almuerzo</option>
                                                <option value="Cena">Cena</option>
                                              </select>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- Fecha precios -->
                                    <div class="col-lg-4">
                                      <div class="form-group">
                                        <label for="monto">Precio Desayuno</label>                               
                                        <input type="number" name="p_desayuno" id="p_desayuno" class="form-control"  placeholder="Precio Desayuno"> 
                                      </div>                                                        
                                    </div>
                                    <div class="col-lg-4">
                                      <div class="form-group">
                                        <label for="monto">Precio Almuerzo</label>                               
                                        <input type="number" name="p_almuerzo" id="p_almuerzo" class="form-control"  placeholder="Precio Almuerzo"> 
                                      </div>                                                        
                                    </div>
                                    <div class="col-lg-4">
                                      <div class="form-group">
                                        <label for="monto">Precio Cena</label>                               
                                        <input type="number" name="p_cena" id="p_cena" class="form-control"  placeholder="Precio Cena"> 
                                      </div>                                                        
                                    </div>

                                  </div>
                                  
                                  <!-- Descripcion pwnsion-->
                                  <div class="col-lg-12">
                                    <div class="form-group">
                                      <label for="descripcion_pension">Descripción </label> <br>
                                      <textarea name="descripcion_pension" id="descripcion_pension" class="form-control" rows="2"></textarea>
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
                              <button type="submit" style="display: none;" id="submit-form-pension">Submit</button>
                          </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_pension();">Close</button>
                          <button type="submit" class="btn btn-success" id="guardar_registro_pension">Guardar Cambios</button>
                      </div>
                  </div>
              </div>
            </div>
            <!--===============Modal agregar Comprobantes =========-->
            <div class="modal fade" id="modal-agregar-comprobante">
                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Agregar Comprobante</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span class="text-danger" aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <!-- form start -->
                            <form id="form-agregar-comprobante" name="form-agregar-comprobante" method="POST">
                                <div class="card-body">
                                    <div class="row" id="cargando-1-fomulario">
                                        <!-- id idpensionn_f -->
                                        <input type="hidden" name="idpension_f" id="idpension_f" />
                                        <!-- id idfactura_pension idpension_f,idfactura_pension-->
                                        <input type="hidden" name="idfactura_pension" id="idfactura_pension" />
                                      <!--forma pago-->
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
                                                <input class="form-control subtotal" type="number" placeholder="Sub total" readonly/>
                                                <input type="hidden" id="subtotal"  name="subtotal"  readonly/>
                                              </div>
                                        </div>
                                        <!-- Fecha Emisión -->
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="igv">IGV</label>
                                                <input class="form-control igv" type="number" placeholder="IGV"  readonly />
                                                <input type="hidden"  id="igv" name="igv" readonly />
                                              </div>
                                        </div>
                                        <!-- Monto-->
                                        <div class="col-lg-4">
                                          <div class="form-group">
                                            <label for="monto">Total</label>                               
                                            <input type="number" class="form-control monto"  placeholder="Monto"  onkeyup="comprob_factura();"> 
                                            <input type="hidden" name="monto" id="monto" class="form-control"   onkeyup="comprob_factura();"> 
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
              <div class="modal-dialog modal-dialog-scrollable modal-xm">
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
            <!--===============modal-ver-detalle-semana =========-->
            <div class="modal fade" id="modal-ver-detalle-semana">
              <div class="modal-dialog modal-dialog-scrollable modal-xl">
                <div class="modal-content">
                  <div class="modal-header">
                      <h4 class="modal-title">Detalles por semana</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                    <div  class="class-style" style="text-align: center;"> 
                      <table id="tabla-detalles-semanal" class="table table-bordered table-striped display" style="width: 100% !important;">
                        <thead>
                          <tr>
                            <th class="text-center">#</th>
                            <th class="">Tipo comida</th>
                            <th class="">Precio</th>
                            <th>Total platos</th>
                            <th>Adicional</th>
                            <th>total</th>
                          </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                          <tr>
                            <th class="text-center">#</th>
                            <th class="">Tipo comida</th>
                            <th class="">Precio</th>
                            <th>Total platos</th>
                            <th>Adicional</th>
                            <th>total</th>
                          </tr>
                        </tfoot>
                      </table> 
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
        .tcuerpo tr td {
            text-align: center !important;
            padding-top: 18px !important;
            border: black 1px solid;
            padding: 0.45rem 0.45rem 0.45rem 0.45rem !important;
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

    <script type="text/javascript" src="scripts/pension.js"></script>

    <style>

        textarea.form-control {
            height: auto;
        }
        .text_area_clss {
            width: 100%;
            background: rgb(215 224 225 / 22%);
            border-block-color: inherit;
            border-bottom: aliceblue;
            border-left: aliceblue;
            border-right: aliceblue;
            border-top: hidden;
        }

    </style>

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
