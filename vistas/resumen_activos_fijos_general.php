<?php
  //Activamos el almacenamiento en el buffer
  ob_start();

  session_start();
  if (!isset($_SESSION["nombre"])){
    header("Location: index.php");
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

                        <div class="card collapsed-card card-primary card-outline ">
                          <div class="card-header">
                            <h3 class="card-title">Lista de activo: <b>Maquinarias</b>    </h3>

                            <div class="card-tools">
                              <button type="button" class="btn btn-default float-right" data-card-widget="collapse">
                                <i class="fas fa-plus"></i>
                              </button>
                            </div>
                          </div>
                          <!-- /.card-header -->
                          <div class="card-body  row-horizon sdisenio-scroll">
                            <table id="tabla-resumen-maquinarias" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">op</th>
                                  <th class="">Producto</th>
                                  <th>Color</th>
                                  <th>U. M</th>
                                  <th>Cantidad</th>
                                  <th>Compra</th> 
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
                                  <th class="">#</th>
                                  <th class="">op</th>
                                  <th class="">Producto</th>
                                  <th>Color</th>
                                  <th>U. M</th>
                                  <th class="text-center" > <h5 class="suma_total_cant_maquinarias" style="font-weight: bold;"> <i class="fas fa-spinner fa-pulse fa-sm"></i> </h5> </th> 
                                  <th>Compra</th>  
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
                        <div class="card collapsed-card  card-primary card-outline">
                          <div class="card-header">
                            <h3 class="card-title">Lista de activos: <b>Equipos</b>    </h3>

                            <div class="card-tools">
                              <button type="button" class="btn btn-default float-right" data-card-widget="collapse">
                                <i class="fas fa-plus"></i>
                              </button>
                            </div>
                          </div>
                          <!-- /.card-header -->
                          <div class="card-body  row-horizon sdisenio-scroll">
                            <table id="tabla-resumen-equipos" class="table table-bordered table-striped display" style="width: 100% !important;">
                                <thead>
                                  <tr>
                                    <th class="">#</th>
                                    <th class="">op</th>
                                    <th class="">Producto</th>
                                    <th>Color</th>
                                    <th>U. M</th>
                                    <th>Cantidad</th>
                                    <th>Compra</th> 
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
                                    <th class="">#</th>
                                    <th class="">op</th>
                                    <th class="">Producto</th>
                                    <th>Color</th>
                                    <th>U. M</th>
                                    <th class="text-center" > <h5 class="suma_total_cant_equipos" style="font-weight: bold;"> <i class="fas fa-spinner fa-pulse fa-sm"></i> </h5> </th> 
                                    <th>Cantidad</th>
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
                        <div class="card collapsed-card card-primary card-outline">
                          <div class="card-header">
                            <h3 class="card-title">Lista de activos:  <b>Herramientas</b>    </h3>

                            <div class="card-tools">
                              <button type="button" class="btn btn-default float-right" data-card-widget="collapse">
                                <i class="fas fa-plus"></i>
                              </button>
                            </div>
                          </div>
                          <!-- /.card-header -->
                          <div class="card-body  row-horizon sdisenio-scroll">
                            <table id="tabla-resumen-herramientas" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                    <th class="">#</th>
                                    <th class="">op</th>
                                    <th class="">Producto</th>
                                    <th>Color</th>
                                    <th>U. M</th>
                                    <th>Cantidad</th>
                                    <th>Compra</th> 
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
                                  <th class="">#</th>
                                  <th class="">op</th>
                                  <th class="">Producto</th>
                                  <th>Color</th>
                                  <th>U. M</th>
                                  <th class="text-center"> <h5 class="suma_total_herramientas"> <i class="fas fa-spinner fa-pulse fa-sm"></i> </h5> </th> 
                                  <th>Compra</th>
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
                                <i class="fas fa-plus"></i>
                              </button>
                            </div>
                          </div>
                          <!-- /.card-header -->
                          <div class="card-body row-horizon sdisenio-scroll">
                            <table id="tabla-resumen-oficina" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                    <th class="">#</th>
                                    <th class="">op</th>
                                    <th class="">Producto</th>
                                    <th>Color</th>
                                    <th>U. M</th>
                                    <th>Cantidad</th>
                                    <th>Compra</th> 
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
                                  <th class="">#</th>
                                  <th class="">op</th>
                                  <th class="">Producto</th>
                                  <th>Color</th>
                                  <th>U. M</th>
                                  <th class="text-center"> <h5 class="suma_total_oficina"> <i class="fas fa-spinner fa-pulse fa-sm"></i> </h5> </th> 
                                  <th>Compra</th> 
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
                          <!-- regresar "tabla facuras" -->
                          <h3 class="card-title mr-3" id="btn-regresar-bloque" style=" padding-left: 2px;" data-toggle="tooltip" data-original-title="Regresar a la tabla principal">
                            <button type="button" class="btn bg-gradient-warning btn-sm" onclick="regresar();"  ><i class="fas fa-arrow-left"></i> <span class="d-none d-sm-inline-block">Regresar</span> </button>
                          </h3> 
                          <!-- nombre producto -->
                          <h1 class="card-title mr-3 nombre-producto-modal-titel" id="btn-regresar-bloque" style=" padding-left: 2px;"> </h1> 
                        </div>
                        <div class="card-body maquinarias">

                          <div class="row" id="cargando-1-fomulario">
                            <!-- maquinarias -->
                            <div class="col-lg-12">
                              <table id="tabla-precios" class="table table-bordered table-striped display" style="width: 100% !important;">
                                <thead>
                                  <tr>
                                    <th class="text-center">#</th>
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
                                    <th class="text-center">#</th>
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
                      <!--EDITAR COMPRA GENERAL-->
                      <div class="card card-primary card-outline compra_general" style="display: none;">
                          <div class="card-header">
                          <!-- regresar "tabla principal" -->
                          <h3 class="card-title mr-3" id="btn-regresar-todo" style=" padding-left: 2px;" data-toggle="tooltip" data-original-title="Regresar a la tabla principal">
                            <button type="button" class="btn btn-block btn-outline-warning btn-sm" onclick="regresar();"><i class="fas fa-arrow-left"></i></button>
                          </h3>

                          <!-- regresar "tabla facuras" -->
                          <h3 class="card-title mr-3" id="btn-regresar-bloque" style=" padding-left: 2px;" data-toggle="tooltip" data-original-title="Regresar a la tabla facturas">
                            <button type="button" class="btn bg-gradient-warning btn-sm" onclick="regresar_div2();"  ><i class="fas fa-arrow-left"></i> <span class="d-none d-sm-inline-block">Regresar</span> </button>
                          </h3> 
                          </div>
                          <div class="card-body">

                            <div id="agregar_compras">
                              <div class="modal-body">
                                <!-- form start -->
                                <form id="form-compras" name="form-compras" method="POST">
                                  <div class="card-body">
                                    <div class="row" id="cargando-1-fomulario">

                                      <input type="hidden" name="idcompra_general" id="idcompra_general" /> 

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
                                           <!-- <div class="col-lg-6">
                                              <label for="" style="color: white;">.</label> <br />
                                              <a data-toggle="modal" data-target="#modal-agregar-material-activos-fijos">
                                                <button type="button" class="btn btn-success btn-block" onclick="limpiar_materiales()"><span class="fa fa-plus"></span> Crear Productos</button>
                                              </a>
                                            </div>-->
                                          </div>
                                        </div>

                                        <!-- Rounded switch -->
                                        <!--<div class="col-lg-1   col-xs-3 class_pading">
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
                                        </div>-->
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
                                <button type="button" class="btn btn-danger" onclick="regresar_div2();" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success" style="display: none;" id="guardar_registro_compras">Guardar Cambios</button>
                              </div>
                            </div>
                          </div>
                      </div>
                      <!--EDITAR COMPRA POR PROYECTO-->
                      <div class="card card-primary card-outline compra_proyecto" style="display: none;">
                          <div class="card-header">
                          <!-- regresar "tabla principal" -->
                          <h3 class="card-title mr-3" id="btn-regresar-todo" style=" padding-left: 2px;" data-toggle="tooltip" data-original-title="Regresar a la tabla principal">
                            <button type="button" class="btn btn-block btn-outline-warning btn-sm" onclick="regresar();"><i class="fas fa-arrow-left"></i></button>
                          </h3>

                          <!-- regresar "tabla facuras" -->
                          <h3 class="card-title mr-3" id="btn-regresar-bloque" style=" padding-left: 2px;" data-toggle="tooltip" data-original-title="Regresar a la tabla facturas">
                            <button type="button" class="btn bg-gradient-warning btn-sm" onclick="regresar_div2();"  ><i class="fas fa-arrow-left"></i> <span class="d-none d-sm-inline-block">Regresar</span> </button>
                          </h3> 
                          </div>
                          <div class="card-body">
                            <div id="agregar_compras_proyecto">
                              <div class="modal-body">
                                <!-- form start -->
                                <form id="form-compra-activos-p" name="form-compra-activos-p" method="POST">
                                  <div class="card-body">
                                    <div class="row" id="cargando-1-fomulario">
                                      <!-- id proyecto -->
                                      <input type="hidden" name="idproyecto_proy" id="idproyecto_proy" /> 
                                      <!-- id compras activo general o por proyecto -->
                                      <input type="hidden" name="idcompra_af_proy" id="idcompra_af_proy" /> 

                                      <!-- Tipo de Empresa -->
                                      <div class="col-lg-7">
                                        <div class="form-group">
                                          <label for="idproveedor">Proveedor</label>
                                          <select id="idproveedor_proy" name="idproveedor_proy" class="form-control select2" data-live-search="true" required title="Seleccione cliente"> </select>
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
                                          <input type="date" name="fecha_compra_proy" id="fecha_compra_proy" class="form-control" placeholder="Fecha" />
                                        </div>
                                      </div>

                                      <!-- Tipo de comprobante -->
                                      <div class="col-lg-4" id="content-t-comprob-p">
                                        <div class="form-group">
                                          <label for="tipo_comprovante">Tipo Comprobante</label>
                                          <select name="tipo_comprobante_proy" id="tipo_comprobante_proy" class="form-control select2" onchange="modificarSubtotales(); ocultar_comprob();" placeholder="Seleccinar un tipo de comprobante">
                                            <option value="Ninguno">Ninguno</option>
                                            <option value="Boleta">Boleta</option>
                                            <option value="Factura">Factura</option>
                                            <option value="Nota_de_venta">Nota de venta</option>
                                          </select>
                                        </div>
                                      </div>

                                      <!-- serie_comprovante-->
                                      <div class="col-lg-2" id="content-comprob-p">
                                        <div class="form-group">
                                          <label for="serie_comprovante">N° de Comprobante</label>
                                          <input type="text" name="serie_comprobante_proy" id="serie_comprobante_proy" class="form-control" placeholder="N° de Comprobante" />
                                        </div>
                                      </div>

                                      <!-- IGV-->
                                      <div class="col-lg-1" id="content-igv-p">
                                        <div class="form-group">
                                          <label for="igv">IGV</label>
                                          <input type="text" name="igv_proy" id="igv_proy" class="form-control" readonly value="0.18" />
                                        </div>
                                      </div>

                                      <!-- Descripcion-->
                                      <div class="col-lg-5" id="content-descrp-p">
                                        <div class="form-group">
                                          <label for="descripcion">Descripción </label> <br />
                                          <textarea name="descripcion_proy" id="descripcion_proy" class="form-control" rows="1"></textarea>
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
                                           <!-- <div class="col-lg-6">
                                              <label for="" style="color: white;">.</label> <br />
                                              <a data-toggle="modal" data-target="#modal-agregar-material-activos-fijos">
                                                <button type="button" class="btn btn-success btn-block" onclick="limpiar_materiales()"><span class="fa fa-plus"></span> Crear Productos</button>
                                              </a>
                                            </div>-->
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

                                      <!--tabla detalles productos-->
                                      <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive row-horizon disenio-scroll">
                                        <br />
                                        <table id="detalles_af_proyecto" class="table table-striped table-bordered table-condensed table-hover">
                                          <thead style="background-color: #127ab6ba;">
                                            <th data-toggle="tooltip" data-original-title="Opciones">Op.</th>
                                            <th>Activo</th>
                                            <th>Unidad medida</th>
                                            <th>Cantidad</th>
                                            <th class="hidden" data-toggle="tooltip" data-original-title="Valor Unitario" >V/U</th>
                                            <th class="hidden">IGV</th>
                                            <th data-toggle="tooltip" data-original-title="Precio Unitario">P/U</th>
                                            <th>Descuento</th>
                                            <th>Subtotal</th>
                                          </thead>
                                          <tfoot>
                                            <td colspan="5" id="colspan_subtotal_p"></td>
                                            <th class="text-center">
                                              <h5>Gravada</h5>
                                              <h5>IGV (18%)</h5>
                                              <h5>TOTAL</h5>
                                            </th>
                                            <th class=" "> 
                                              <h5 class="text-right " id="subtotal_proy" style="font-weight: bold;">S/. 0.00</h5>
                                              <input type="hidden" name="subtotal_compra_proy" id="subtotal_compra_proy" />

                                              <h5 class="text-right" name="igv_comp_proy" id="igv_comp_proy" style="font-weight: bold;">S/. 0.00</h5>
                                              <input type="hidden" name="igv_compra_proy" id="igv_compra_proy" />
                                              <b>
                                                <h4 class="text-right" id="total_proy" style="font-weight: bold;">S/. 0.00</h4>
                                                <input type="hidden" name="total_compra_proy" id="total_compra_proy" />
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
                                  <button type="submit" style="display: none;" id="submit-form-compra-activos-p">Submit</button>
                                </form>
                              </div>

                              <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-danger" onclick="regresar_div2();" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success" id="guardar_registro_compras_p">Guardar Cambios</button>
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
                      <h4 class="modal-title">Seleccionar producto -  </h4>
                      <a data-toggle="modal" data-target="#modal-agregar-material-activos-fijos">
                        <button type="button" class="btn btn-success btn-block" onclick="limpiar_materiales()"><span class="fa fa-plus"></span> Crear Productos</button>
                      </a>
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
                                <select name="tipo_documento" id="tipo_documento" class="form-control select2" placeholder="Tipo de documento">
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
                                </select>
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
              
              <!-- Modal agregar MATERIALES Y ACTIVOS FIJOS -->                 
              <div class="modal fade" id="modal-agregar-material-activos-fijos">
                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Agregar Producto</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <div class="modal-body">
                      <!-- form start -->
                      <form id="form-materiales" name="form-materiales" method="POST">
                        <div class="card-body">
                          <div class="row" id="cargando-3-fomulario">

                            <!-- idproducto -->
                            <input type="hidden" name="idproducto_p" id="idproducto_p" />                               
                            <input type="hidden" name="cont" id="cont" />                               

                            <!-- Nombre -->
                            <div class="col-lg-8 class_pading">
                              <div class="form-group">
                                <label for="nombre_p">Nombre <sup class="text-danger">*</sup></label>
                                <input type="text" name="nombre_p" class="form-control" id="nombre_p" placeholder="Nombre del producto." />
                              </div>
                            </div>

                            <!-- Categoria -->
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="categoria_insumos_af_p">Clasificación</label>
                                <select name="categoria_insumos_af_p" id="categoria_insumos_af_p" class="form-control select2" style="width: 100%;"> 
                                </select>
                              </div>
                            </div>

                            <!-- Modelo -->
                            <div class="col-lg-6">
                              <div class="form-group">
                                <label for="modelo_p">Modelo <sup class="text-danger">*</sup> </label>
                                <input class="form-control" type="text" id="modelo_p" name="modelo_p" placeholder="Modelo." />
                              </div>
                            </div>

                            <!-- Serie -->
                            <div class="col-lg-6">
                              <div class="form-group">
                                <label for="serie_p">Serie </label>
                                <input class="form-control" type="text" id="serie_p" name="serie_p" placeholder="Serie." />
                              </div>
                            </div>

                            <!-- Marca -->
                            <div class="col-lg-6">
                              <div class="form-group">
                                <label for="marca_p">Marca </label>
                                <input class="form-control" type="text" id="marca_p" name="marca_p" placeholder="Marca de activo." />
                              </div>
                            </div>

                            <!-- Color -->
                            <div class="col-lg-6">
                              <div class="form-group">
                                <label for="color_p">Color</label>
                                <select name="color_p" id="color_p" class="form-control select2" style="width: 100%;"> </select>
                              </div>
                            </div>
                            
                            <!-- Unnidad-->
                            <div class="col-lg-6" id="content-t-unidad">
                              <div class="form-group">
                                <label for="unidad_medida_p">Unidad-medida</label>
                                <select name="unidad_medida_p" id="unidad_medida_p" class="form-control select2" style="width: 100%;"> </select>
                              </div>
                            </div>

                            <!--Precio U-->
                            <div class="col-lg-4 class_pading">
                              <div class="form-group">
                                <label for="precio_unitario_p">Precio <sup class="text-danger">*</sup></label>
                                <input type="number" name="precio_unitario_p" class="form-control miimput" id="precio_unitario_p" placeholder="Precio Unitario." onchange="precio_con_igv();" onkeyup="precio_con_igv();" />
                              </div>
                            </div>

                            <!-- Rounded switch -->
                            <div class="col-lg-2 class_pading">
                              <div class="form-group">
                                <label for="" class="labelswitch">Sin o Con (Igv)</label>
                                <div id="switch_igv">
                                  <div class="switch-holder myestilo-switch">
                                    <div class="switch-toggle">
                                      <input type="checkbox" id="my-switch_igv" checked />
                                      <label for="my-switch_igv"></label>
                                    </div>
                                  </div>
                                </div>
                                <input type="hidden" name="estado_igv_p" id="estado_igv_p" />
                              </div>
                            </div>

                            <!--Sub Total subtotal igv total-->
                            <div class="col-lg-4 class_pading">
                              <div class="form-group">
                                <label for="precio_sin_igv_p">Sub Total</label>
                                <input type="number" class="form-control" name="precio_sin_igv_p" id="precio_sin_igv_p" placeholder="Precio real." onchange="precio_con_igv();" onkeyup="precio_con_igv();" readonly />
                              </div>
                            </div>

                            <!--IGV-->
                            <div class="col-lg-4 class_pading">
                              <div class="form-group">
                                <label for="precio_igv_p">IGV</label>
                                <input type="number" class="form-control" name="precio_igv_p" id="precio_igv_p" placeholder="Monto igv." onchange="precio_con_igv();" onkeyup="precio_con_igv();" readonly />
                              </div>
                            </div>

                            <!--Total-->
                            <div class="col-lg-4 class_pading">
                              <div class="form-group">
                                <label for="precio_total_p">Total</label>
                                <input type="number" class="form-control" name="precio_total_p" id="precio_total_p" placeholder="Precio real." readonly />
                              </div>
                            </div>

                            <!--Descripcion-->
                            <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="descripcion_p">Descripción </label> <br />
                                <textarea name="descripcion_p" id="descripcion_p" class="form-control" rows="2"></textarea>
                              </div>
                            </div>

                            <!--iamgen-material-->
                            <div class="col-md-6 col-lg-6">
                              <label for="foto2">Imagen</label>
                              <div style="text-align: center;">
                                <img
                                  onerror="this.src='../dist/img/default/img_defecto_activo_fijo_material.png';"
                                  src="../dist/img/default/img_defecto_activo_fijo_material.png"
                                  class="img-thumbnail"
                                  id="foto2_i"
                                  style="cursor: pointer !important; height: 100% !important;"
                                  width="auto"
                                />
                                <input style="display: none;" type="file" name="foto2" id="foto2" accept="image/*" />
                                <input type="hidden" name="foto2_actual" id="foto2_actual" />
                                <div class="text-center" id="foto2_nombre"><!-- aqui va el nombre de la FOTO --></div>
                              </div>
                            </div>

                            <!-- Ficha tecnica -->
                            <div class="col-md-6 col-lg-6">
                              <label for="doc2_i" >Ficha técnica <b class="text-danger">(Imagen o PDF)</b> </label>  
                              <div class="row text-center">                               
                                <!-- Subir documento -->
                                <div class="col-md-6 text-center">
                                  <button type="button" class="btn btn-success btn-block btn-xs" id="doc2_i">
                                    <i class="fas fa-upload"></i> Subir.
                                  </button>
                                  <input type="hidden" id="doc_old_2" name="doc_old_2" />
                                  <input style="display: none;" id="doc2" type="file" name="doc2" accept="application/pdf, image/*" class="docpdf" /> 
                                </div>
                                <!-- Recargar -->
                                <div class="col-md-6 text-center comprobante">
                                  <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(2, 'ficha_tecnica');">
                                  <i class="fas fa-redo"></i> Recargar.
                                </button>
                                </div>                                  
                              </div>
                              <div id="doc2_ver" class="text-center mt-4">
                                <img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >
                              </div>
                              <div class="text-center" id="doc2_nombre"><!-- aqui va el nombre del pdf --></div>
                            </div>

                          </div>

                          <div class="row" id="cargando-4-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                              <br />
                              <h4>Cargando...</h4>
                            </div>
                          </div>
                        </div>
                        <!-- /.card-body -->
                        <button type="submit" style="display: none;" id="submit-form-materiales">Submit</button>
                      </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_materiales();">Close</button>
                      <button type="submit" class="btn btn-success" id="guardar_registro_material">Guardar Cambios</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- MODAL - DETALLE MATERIALES O ACTIVOS FIJOS -->
              <div class="modal fade" id="modal-ver-detalle-material-activo-fijo">
                <div class="modal-dialog modal-dialog-scrollable modal-xm">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Datos Producto</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <div class="modal-body">
                      <div id="datosproductos" class="class-style">
                        <!-- vemos los datos del Producto -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- ============ Modal ver grande img producto -->
              <div class="modal fade" id="modal-ver-img-activo">
                <div class="modal-dialog modal-dialog-scrollable modal-md">
                  <div class="modal-content">
                    <div class="modal-header" style="background-color: #49a9ceb8;">
                      <h4 class="modal-title nombre-img-activo">Img producto</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <div class="class-style" style="text-align: center;">
                          
                        <img onerror="this.src='../dist/img/default/img_defecto_activo_fijo.png';" src="" class="img-thumbnail " id="ver_img_activo" style="cursor: pointer !important;" width="auto" />
                          
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
