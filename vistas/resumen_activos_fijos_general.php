<?php
  //Activamos el almacenamiento en el buffer
  ob_start();

  session_start();
  if (!isset($_SESSION["nombre"])){
    header("Location: login.html");
  }else{
    ?>
    <!doctype html>
    <html lang="es">
      <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Admin Sevens | Resumen Insumos</title>
        <?php
        require 'head.php';
        ?>
        <!--CSS  switch_MATERIALES-->
        <link rel="stylesheet" href="../dist/css/switch_materiales.css" />
      </head>
      <body class="hold-transition sidebar-collapse sidebar-mini layout-fixed layout-navbar-fixed">
        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper">
          <?php
          require 'nav.php';
          require 'aside.php';
          if ($_SESSION['resumen_activo_fijo_general']==1){
          ?>
        
          <!-- Content Wrapper. Contains page content -->
          <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
              <div class="container-fluid">
                <div class="row mb-2">
                  <div class="col-sm-6">
                    <h1>Resumen activos según <b>Clasificación</b>  </h1>
                  </div>
                  <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="">Home</a></li>
                      <li class="breadcrumb-item active">Activos</li>
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
                  <div class="col-12 primer-div">
                    <div class="row">
                      <!-- /.Maquinarias -->
                      <div class="col-12">

                        <div class="card card-primary card-outline ">
                          <div class="card-header">
                            <h3 class="card-title">Lista de activo: <b>Maquinarias</b>    </h3>

                            <div class="card-tools">
                              <button type="button" class="btn btn-default float-right" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                              </button>
                            </div>
                          </div>
                          <!-- /.card-header -->
                          <div class="card-body  row-horizon sdisenio-scroll">
                            <table id="tabla-resumen-maquinarias" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th class="">Producto</th>
                                  <th>U. medida</th>
                                  <th>Cantidad</th> 
                                  <th>Precio promedio</th> 
                                  <th>Precio actual</th>    
                                  <th>Suma Total</th>                      
                                </tr>
                              </thead>
                              <tbody>                         
                                <!-- aqui la va el detalle de la tabla -->
                              </tbody>
                              <tfoot>
                                <tr>
                                  <th class="">Producto</th>
                                  <th>U. medida</th>
                                  <th class="text-center" > <h5 class="suma_total_cant_maquinarias" style="font-weight: bold;"> <i class="fas fa-spinner fa-pulse fa-sm"></i> </h5> </th> 
                                  <th>Precio promedio</th>
                                  <th>Precio actual</th>   
                                  <th class="text-right" > <h5 class="suma_total_de_maquinarias" style="font-weight: bold;">S/. <i class="fas fa-spinner fa-pulse fa-sm"></i> </h5></th>                               
                                </tr>
                              </tfoot>
                            </table>
                          </div>
                          <!-- /.card-body -->
                        </div>

                        <!-- /.card -->
                      </div>
                      <!-- /.Equipos -->
                      <div class="col-12">
                        <div class="card  card-primary card-outline">
                          <div class="card-header">
                            <h3 class="card-title">Lista de activos: <b>Equipos</b>    </h3>

                            <div class="card-tools">
                              <button type="button" class="btn btn-default float-right" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                              </button>
                            </div>
                          </div>
                          <!-- /.card-header -->
                          <div class="card-body  row-horizon sdisenio-scroll">
                            <table id="tabla-resumen-equipos" class="table table-bordered table-striped display" style="width: 100% !important;">
                                <thead>
                                  <tr>
                                    <th class="">Producto</th>
                                    <th>U. medida</th>
                                    <th>Cantidad</th> 
                                    <th>Precio promedio</th> 
                                    <th>Precio actual</th>    
                                    <th>Suma Total</th>                      
                                  </tr>
                                </thead>
                                <tbody>                         
                                  <!-- aqui la va el detalle de la tabla -->
                                </tbody>
                                <tfoot>
                                  <tr>
                                    <th class="">Producto</th>
                                    <th>U. medida</th>
                                    <th class="text-center" > <h5 class="suma_total_cant_equipos" style="font-weight: bold;"> <i class="fas fa-spinner fa-pulse fa-sm"></i> </h5> </th> 
                                    <th>Precio promedio</th>
                                    <th>Precio actual</th>   
                                    <th class="text-right" > <h5 class="suma_total_de_equipos" style="font-weight: bold;">S/. <i class="fas fa-spinner fa-pulse fa-sm"></i> </h5></th>                               
                                  </tr>
                                </tfoot>
                              </table>
                            </div>
                          <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                      </div>
                      <!-- /. Herramientas -->
                      <div class="col-12">
                        <div class="card card-primary card-outline">
                          <div class="card-header">
                            <h3 class="card-title">Lista de activos:  <b>Herramientas</b>    </h3>

                            <div class="card-tools">
                              <button type="button" class="btn btn-default float-right" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                              </button>
                            </div>
                          </div>
                          <!-- /.card-header -->
                          <div class="card-body  row-horizon sdisenio-scroll">
                            <table id="tabla-resumen-herramientas" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th class="">Producto</th>
                                  <th>Unidad de medida</th>
                                  <th>Cantidad</th> 
                                  <th>Precio promedio</th> 
                                  <th>Precio actual</th>    
                                  <th>Suma Total</th>                      
                                </tr>
                              </thead>
                              <tbody>                         
                                <!-- aqui la va el detalle de la tabla -->
                              </tbody>
                              <tfoot>
                                <tr>
                                  <th class="">Producto</th>
                                  <th>Unidad de medida</th>
                                  <th class="text-center"> <h5 class="suma_total_herramientas"> <i class="fas fa-spinner fa-pulse fa-sm"></i> </h5> </th> 
                                  <th>Precio promedio</th>
                                  <th>Precio actual</th>   
                                  <th class="text-center"> <h5 class="suma_total_de_herramientas">S/. <i class="fas fa-spinner fa-pulse fa-sm"></i> </h5></th>                               
                                </tr>
                              </tfoot>
                            </table>
                          </div>
                          <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                      </div>
                      <!-- /.Oficina -->
                      <div class="col-12">
                        <div class="card collapsed-card card-primary card-outline">
                          <div class="card-header">
                            <h3 class="card-title">Lista de activos <b>Oficina</b>    </h3>

                            <div class="card-tools">
                              <button type="button" class="btn btn-default float-right" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                              </button>
                            </div>
                          </div>
                          <!-- /.card-header -->
                          <div class="card-body row-horizon sdisenio-scroll">
                            <table id="tabla-resumen-oficina" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th class="">Producto</th>
                                  <th>Unidad de medida</th>
                                  <th>Cantidad</th> 
                                  <th>Precio promedio</th> 
                                  <th>Precio actual</th>    
                                  <th>Suma Total</th>                      
                                </tr>
                              </thead>
                              <tbody>                         
                                <!-- aqui la va el detalle de la tabla -->
                              </tbody>
                              <tfoot>
                                <tr>
                                  <th class="">Producto</th>
                                  <th>Unidad de medida</th>
                                  <th class="text-center"> <h5 class="suma_total_oficina"> <i class="fas fa-spinner fa-pulse fa-sm"></i> </h5> </th> 
                                  <th>Precio promedio</th>
                                  <th>Precio actual</th>   
                                  <th class="text-center"> <h5 class="suma_total_de_oficina">S/. <i class="fas fa-spinner fa-pulse fa-sm"></i> </h5></th>                               
                                </tr>
                              </tfoot>
                            </table>
                          </div>
                          <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                      </div>
                    </div>
                  </div>
                  <div class="col-12 segundo-div" style="display: none;" >
                    <div class="card card-primary card-outline">
                        <!--maquinaria-->
                        <div class="card-header">
                          <div class="row">
                            <div class="col-lg-2">
                            <button type="button" class="btn bg-gradient-warning" id="regresar" onclick="regresar();"><i class="fas fa-arrow-left"></i> Regresar</button>
                            </div>
                            <div class="col-lg-6">
                              <h4 class="nombre-producto-modal-titel">Producto y mas</h4>
                            </div>
                          </div>
                        </div>
                        <div class="card-body maquinarias">

                          <div class="row" id="cargando-1-fomulario">
                            <!-- maquinarias -->
                            <div class="col-lg-12">
                              <table id="tabla-precios" class="table table-bordered table-striped display" style="width: 100% !important;">
                                <thead>
                                  <tr>
                                    <th>OP.</th>
                                    <th>Proveedor</th>
                                    <th>Fecha compra</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>  
                                    <th>Descuento</th>
                                    <th>SubTotal</th>
                                    <th>Ficha técnica</th>                               
                                  </tr>
                                </thead>
                                <tbody>                         
                                  
                                </tbody>
                                <tfoot>
                                  <tr>
                                    <th>OP.</th>
                                    <th>Proveedor</th>
                                    <th>Fecha compra</th>
                                    <th >Cantidad</th>
                                    <th > <h4 class="precio_promedio"> S/. --</h4> </th>  
                                    <th>Descuento</th> 
                                    <th><h4 class="subtotal_x_producto"> S/. --</h4></th>
                                    <th>Ficha técnica</th>                        
                                  </tr>
                                </tfoot>
                              </table>                                                        
                            </div>
                          </div>  

                          <div class="row" id="cargando-2-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>
                              <h4>Cargando...</h4>
                            </div>
                          </div>
                          
                        </div>
                    </div>
                  </div>
                  <div class="col-12 tercer-div" style="display: none;" >

                      <div class="card card-primary card-outline">
                          <!--maquinaria-->
                          <div class="card-header">
                              <h3 class="">
                                <div class="row">
                                    <div class="col-lg-1 col-md-6 col-sm-6 col-xs-12">
                                        <button type="button" class="btn btn-block btn-outline-warning " onclick="table_show_hide(1);">
                                        <i class="fas fa-arrow-left"></i>
                                      </button>
                                      </div>
                                      <div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
                                      <button type="button" class="btn btn-block bg-gradient-warning" id="regresar"  onclick="regresar();">
                                        <i class="fas fa-arrow-left"></i> Regresar
                                      </button>  
                                    </div>
                                </div>
                              
                              </h3>
                          </div>
                          <div class="card-body">

                            <div id="agregar_compras">
                              <div class="modal-body">
                                <!-- form start -->
                                <form id="form-compras" name="form-compras" method="POST">
                                  <div class="card-body">
                                    <div class="row" id="cargando-1-fomulario">

                                      <input type="hidden" name="idcompra_proyecto" id="idcompra_proyecto" /> 

                                      <!-- Tipo de Empresa -->
                                      <div class="col-lg-7">
                                        <div class="form-group">
                                          <label for="idproveedor">Proveedor</label>
                                          <select id="idproveedor" name="idproveedor" class="form-control select2" data-live-search="true" required title="Seleccione cliente"> </select>
                                        </div>
                                      </div>

                                      <!-- adduser -->
                                      <div class="col-lg-1">
                                        <div class="form-group">
                                          <label for="Add" style="color: white;">.</label>
                                          <a data-toggle="modal" href="#modal-agregar-proveedor" >
                                            <button type="button" class="btn btn-success btn-block" data-toggle="tooltip" data-original-title="Agregar Provedor" onclick="limpiardatosproveedor();">
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
                                      <div class="col-lg-4 content-t-comprob">
                                        <div class="form-group">
                                          <label for="tipo_comprovante">Tipo Comprobante</label>
                                          <select name="tipo_comprovante" id="tipo_comprovante" class="form-control select2" onchange="modificarSubtotales(); ocultar_comprob();" placeholder="Seleccinar un tipo de comprobante">
                                            <option value="Ninguno">Ninguno</option>
                                            <option value="Boleta">Boleta</option>
                                            <option value="Factura">Factura</option>
                                            <option value="Nota_de_venta">Nota de venta</option>
                                          </select>
                                        </div>
                                      </div>

                                      <!-- serie_comprovante-->
                                      <div class="col-lg-2 content-comprob">
                                        <div class="form-group">
                                          <label for="serie_comprovante">N° de Comprobante</label>
                                          <input type="text" name="serie_comprovante" id="serie_comprovante" class="form-control" placeholder="N° de Comprobante" />
                                        </div>
                                      </div>

                                      <!-- IGV-->
                                      <div class="col-lg-1 content-igv">
                                        <div class="form-group">
                                          <label for="igv">IGV</label>
                                          <input type="text" name="igv" id="igv" class="form-control" readonly value="0.18" />
                                        </div>
                                      </div>

                                      <!-- Descripcion-->
                                      <div class="col-lg-5 content-descrp">
                                        <div class="form-group">
                                          <label for="descripcion">Descripción </label> <br />
                                          <textarea name="descripcion" id="descripcion" class="form-control" rows="1"></textarea>
                                        </div>
                                      </div>

                                      <!--Boton agregar material-->
                                      <div class="row col-lg-12 justify-content-between">
                                        <div class="col-lg-4 col-xs-12">
                                          <div class="row">
                                            <div class="col-lg-6">
                                                <label for="" style="color: white;">.</label> <br />
                                                <a data-toggle="modal" data-target="#modal-elegir-material">
                                                  <button type="button" class="btn btn-primary btn-block"><span class="fa fa-plus"></span> Agregar Productos</button>
                                                </a>
                                            </div>
                                            <div class="col-lg-6">
                                              <label for="" style="color: white;">.</label> <br />
                                              <a data-toggle="modal" data-target="#modal-agregar-material-activos-fijos">
                                                <button type="button" class="btn btn-success btn-block" onclick="limpiar_materiales()"><span class="fa fa-plus"></span> Crear Productos</button>
                                              </a>
                                            </div>
                                          </div>
                                        </div>

                                        <!-- Rounded switch -->
                                        <div class="col-lg-1   col-xs-3 class_pading">
                                          <div class="form-group">
                                            <div id="switch_detracc">
                                              <label for="" style="font-size: 13px;" >Detracción ?</label> <br />
                                              <div class="switch-holder myestilo-switch2" >
                                                <div class="switch-toggle">
                                                  <input type="checkbox" id="my-switch_detracc" />
                                                  <label for="my-switch_detracc"></label>
                                                </div>
                                              </div>
                                            </div>
                                            <input type="hidden" name="estado_detraccion" id="estado_detraccion" value="0" />
                                          </div>
                                        </div>
                                      </div>
                                      <!--tabla detalles materiales-->
                                      <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive row-horizon disenio-scroll">
                                        <br />
                                        <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                                          <thead style="background-color: #ff6c046b;">
                                            <th data-toggle="tooltip" data-original-title="Opciones">Op.</th>
                                            <th>Material</th>
                                            <th>Unidad</th>
                                            <th>Cantidad</th>
                                            <th class="hidden" data-toggle="tooltip" data-original-title="Valor Unitario" >V/U</th>
                                            <th class="hidden">IGV</th>
                                            <th data-toggle="tooltip" data-original-title="Precio Unitario">P/U</th>
                                            <th>Descuento</th>
                                            <th>Subtotal</th>
                                          </thead>
                                          <tfoot>
                                            <td colspan="5" id="colspan_subtotal"></td>
                                            <th class="text-center">
                                              <h5>Gravada</h5>
                                              <h5>IGV (18%)</h5>
                                              <h5>TOTAL</h5>
                                            </th>
                                            <th class=" "> 
                                              <h5 class="text-right " id="subtotal" style="font-weight: bold;">S/. 0.00</h5>
                                              <input type="hidden" name="subtotal_compra" id="subtotal_compra" />

                                              <h5 class="text-right" name="igv_comp" id="igv_comp" style="font-weight: bold;">S/. 0.00</h5>
                                              <input type="hidden" name="igv_compra" id="igv_compra" />
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
                      </div>
                  </div>
                </div>
              </div>
              <!-- /.container-fluid -->

              <!-- Modal elegir material -->
              <div class="modal fade" id="modal-elegir-material">
                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Seleccionar producto</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body table-responsive">
                      <table id="tblamateriales" class="table table-striped table-bordered table-condensed table-hover" style="width: 100% !important;">
                        <thead>
                          <th data-toggle="tooltip" data-original-title="Opciones">Op.</th>
                          <th>Nombre Producto</th>
                          <th>Clasificación</th>
                          <th data-toggle="tooltip" data-original-title="Precio Unitario">P/U.</th>
                          <th>Descripción</th>
                          <th data-toggle="tooltip" data-original-title="Ficha Técnica" >F.T.</th>
                        </thead>
                        <tbody></tbody>
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

      <script type="text/javascript" src="scripts/resumen_activos_fijos_general.js"></script>

      <style>
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
        })
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