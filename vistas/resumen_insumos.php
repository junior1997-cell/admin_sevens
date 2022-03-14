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

        <?php  require 'head.php';  ?>

        <!--CSS  switch_MATERIALES-->
        <link rel="stylesheet" href="../dist/css/switch_materiales.css" />
        
      </head>
      <body class="hold-transition sidebar-collapse sidebar-mini layout-fixed layout-navbar-fixed">
        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper">
          <?php
          require 'nav.php';
          require 'aside.php';
          if ($_SESSION['compra']==1){
            ?>   
          
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
              <!-- Content Header (Page header) -->
              <section class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1 class="m-0 nombre-insumo"><img src="../dist/svg/palana-ico-negro.svg" class="nav-icon" alt="" style="width: 21px !important;"> Resumen de Insumos</h1>
                    </div>
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="resumen_insumos.php">Home</a></li>
                        <li class="breadcrumb-item active">Insumos</li>
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

                          <h3 class="card-title mensaje-tbla-principal" >                           
                            Lista de materiales usado en este proyecto                        
                          </h3>  

                          <!-- regresar "tabla principal" -->
                          <h3 class="card-title mr-3" id="btn-regresar" style="display: none; padding-left: 2px;" >
                            <button type="button" class="btn bg-gradient-warning btn-sm" onclick="table_show_hide(1);"  ><i class="fas fa-arrow-left"></i> <span class="d-none d-sm-inline-block">Regresar</span> </button>
                          </h3>

                          <!-- regresar "tabla principal" -->
                          <h3 class="card-title mr-3" id="btn-regresar-todo" style="display: none; padding-left: 2px;" data-toggle="tooltip" data-original-title="Regresar a la tabla principal">
                            <button type="button" class="btn btn-block btn-outline-warning btn-sm" onclick="table_show_hide(1);"><i class="fas fa-arrow-left"></i></button>
                          </h3>

                          <!-- regresar "tabla facuras" -->
                          <h3 class="card-title mr-3" id="btn-regresar-bloque" style="display: none; padding-left: 2px;" data-toggle="tooltip" data-original-title="Regresar a la tabla fechas">
                            <button type="button" class="btn bg-gradient-warning btn-sm" onclick="table_show_hide(2);"  ><i class="fas fa-arrow-left"></i> <span class="d-none d-sm-inline-block">Regresar</span> </button>
                          </h3>                    
                        </div>
                        
                        <div class="card-body">
                          <!-- TBLA PRINCIPAL  -->
                          <div id="tabla-principal">
                            <table id="tbla-resumen-insumos" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">Op</th>
                                  <th class="">Producto</th>
                                  <th class="">Color</th>
                                  <th class="">Marca</th>
                                  <th data-toggle="tooltip" data-original-title="Unidad de Medida">UM</th>
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
                                  <th class="">Op</th>
                                  <th class="">Producto</th>
                                  <th class="">Color</th>
                                  <th class="">Marca</th>
                                  <th>UM</th>
                                  <th class="text-center suma_total_productos h5"><i class="fas fa-spinner fa-pulse fa-sm"></i></th> 
                                  <th>Compra</th>
                                  <th>Precio promedio</th>
                                  <th>Precio actual</th>   
                                  <th class="text-nowrap text-right suma_total_de_compras h5">S/. <i class="fas fa-spinner fa-pulse fa-sm"></i></th>                               
                                </tr>
                              </tfoot>
                            </table>
                          </div>

                          <!-- TBLA FACTURAS  -->
                          <div id="tabla-factura" style="display: none !important;">
                            <table id="tbla-facura" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th>#</th>
                                  <th>Op.</th>
                                  <th>Proveedor</th>
                                  <th>Fecha compra</th>
                                  <th data-toggle="tooltip" data-original-title="Centidad">Cant.</th>
                                  <th>Precio</th>  
                                  <th data-toggle="tooltip" data-original-title="Descuento">Dcto.</th>
                                  <th>SubTotal</th>
                                  <!-- <th data-toggle="tooltip" data-original-title="Ficha Técnica">FT</th>                                -->
                                </tr>
                              </thead>
                              <tbody>                         
                                
                              </tbody>
                              <tfoot>
                                <tr>
                                  <th>#</th>
                                  <th>Op.</th>
                                  <th>Proveedor</th>
                                  <th >Fecha compra</th>
                                  <th class="cantidad_x_producto"><i class="fas fa-spinner fa-pulse fa-sm"></i></th>
                                  <th class="text-right precio_promedio text-nowrap h5"> <i class="fas fa-spinner fa-pulse fa-sm"></i></th>  
                                  <th class="descuento_x_producto text-nowrap"><i class="fas fa-spinner fa-pulse fa-sm"></i></th> 
                                  <th class="text-right subtotal_x_producto text-nowrap h5"> <i class="fas fa-spinner fa-pulse fa-sm"></i></th>
                                  <!-- <th data-toggle="tooltip" data-original-title="Ficha Técnica">FT</th>                         -->
                                </tr>
                              </tfoot>
                            </table>
                          </div>

                          <!-- TBLA EDITAR FACTURA -->
                          <div id="tabla-editar-factura" style="display: none !important;">
                            <div class="modal-body">
                              <!-- form start -->
                              <form id="form-compras" name="form-compras" method="POST">   

                                <div class="row" id="cargando-1-fomulario">
                                  <!-- id proyecto -->
                                  <input type="hidden" name="idproyecto" id="idproyecto" />
                                  <input type="hidden" name="idcompra_proyecto" id="idcompra_proyecto" /> 

                                  <!-- Tipo de Empresa -->
                                  <div class="col-lg-5">
                                    <div class="form-group">
                                      <label for="idproveedor">Proveedor <sup class="text-danger">*</sup></label>
                                      <select id="idproveedor" name="idproveedor" class="form-control select2" data-live-search="true" required title="Seleccione cliente"> </select>
                                    </div>
                                  </div>

                                  <!-- adduser -->
                                  <div class="col-lg-1">
                                    <div class="form-group">
                                      <label for="Add" style="color: white;">.</label>
                                      <a data-toggle="modal" href="#modal-agregar-proveedor" >
                                        <button type="button" class="btn btn-success btn-block" data-toggle="tooltip" data-original-title="Agregar Provedor" onclick="limpiar_form_proveedor();">
                                          <i class="fa fa-user-plus" aria-hidden="true"></i>
                                        </button>
                                      </a>
                                    </div>
                                  </div>

                                  <!-- fecha -->
                                  <div class="col-lg-3">
                                    <div class="form-group">
                                      <label for="fecha_compra">Fecha <sup class="text-danger">*</sup></label>
                                      <input type="date" name="fecha_compra" id="fecha_compra" class="form-control" placeholder="Fecha" />
                                    </div>
                                  </div>

                                  <!-- Glosa -->
                                  <div class="col-lg-3">
                                    <div class="form-group">
                                      <label for="glosa">Glosa <sup class="text-danger">*</sup></label>
                                      <select id="glosa" name="glosa" class="form-control select2" data-live-search="true" required title="Seleccione glosa"> 
                                        <option value="MATERIAL">MATERIAL</option>
                                        <option value="CONBUSTIBLE">CONBUSTIBLE</option>
                                      </select>
                                    </div>
                                  </div>

                                  <!-- Tipo de comprobante -->
                                  <div class="col-lg-4" id="content-tipo-comprobante">
                                    <div class="form-group">
                                      <label for="tipo_comprobante">Tipo Comprobante <sup class="text-danger">*</sup></label>
                                      <select name="tipo_comprobante" id="tipo_comprobante" class="form-control select2"  onchange="default_val_igv(); modificarSubtotales(); ocultar_comprob();" placeholder="Seleccinar un tipo de comprobante">
                                        <option value="Ninguno">Ninguno</option>
                                        <option value="Boleta">Boleta</option>
                                        <option value="Factura">Factura</option>
                                        <option value="Nota_de_venta">Nota de venta</option>
                                      </select>
                                    </div>
                                  </div>

                                  <!-- serie_comprobante-->
                                  <div class="col-lg-2" id="content-serie-comprobante">
                                    <div class="form-group">
                                      <label for="serie_comprobante">N° de Comprobante</label>
                                      <input type="text" name="serie_comprobante" id="serie_comprobante" class="form-control" placeholder="N° de Comprobante" />
                                    </div>
                                  </div>

                                  <!-- IGV-->
                                  <div class="col-lg-1" id="content-igv">
                                    <div class="form-group">
                                      <label for="val_igv">IGV <sup class="text-danger">*</sup></label>
                                      <input type="text" name="val_igv" id="val_igv" class="form-control" value="0.18" onkeyup="modificarSubtotales();" />
                                    </div>
                                  </div>

                                  <!-- Descripcion-->
                                  <div class="col-lg-5" id="content-descripcion">
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
                                              <button id="btnAgregarArt" type="button" class="btn btn-primary btn-block"><span class="fa fa-plus"></span> Agregar Productos</button>
                                            </a>
                                        </div>
                                        <div class="col-lg-6">
                                          <!-- <label for="" style="color: white;">.</label> <br />
                                          <a data-toggle="modal" data-target="#modal-agregar-material-activos-fijos">
                                            <button id="btnAgregarArt" type="button" class="btn btn-success btn-block" onclick="limpiar_materiales()"><span class="fa fa-plus"></span> Crear Productos</button>
                                          </a> -->
                                        </div>
                                      </div>
                                    </div>

                                    <!-- Rounded switch -->
                                    <div class="col-lg-1   col-xs-3 class_pading">
                                      <div class="form-group">
                                        <div id="switch_detracc">
                                          <label for="" style="font-size: 13px;" >Detracción ?</label> <br />
                                          <div class="myestilo-switch2" >
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

                                  <!--tabla detalles plantas-->
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
                                        <th class="text-right">
                                          <h6 class="tipo_gravada">GRAVADA</h6>
                                          <h6 class="val_igv">IGV (18%)</h6>
                                          <h5 class="font-weight-bold">TOTAL</h5>
                                        </th>
                                        <th class="text-right"> 
                                          <h6 class="font-weight-bold subtotal_compra">S/. 0.00</h6>
                                          <input type="hidden" name="subtotal_compra" id="subtotal_compra" />
                                          <input type="hidden" name="tipo_gravada" id="tipo_gravada" />

                                          <h6 class="font-weight-bold igv_compra">S/. 0.00</h6>
                                          <input type="hidden" name="igv_compra" id="igv_compra" />
                                          
                                          <h5 class="font-weight-bold total_venta">S/. 0.00</h5>
                                          <input type="hidden" name="total_venta" id="total_venta" />
                                          
                                        </th>
                                      </tfoot>
                                      <tbody></tbody>
                                    </table>
                                  </div>                                    
                                </div>

                                <div class="row" id="cargando-2-fomulario" style="display: none;">
                                  <div class="col-lg-12 text-center">
                                    <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                                    <br />
                                    <h4>Cargando...</h4>
                                  </div>
                                </div>

                                <button type="submit" style="display: none;" id="submit-form-compras">Submit</button>
                              </form>
                            </div>

                            <div class="modal-footer justify-content-between">
                              <button type="button" class="btn btn-danger" onclick="table_show_hide(2);" data-dismiss="modal">Close</button>
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

                <!-- MODAL - agregar proveedores -->
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
                          <div class="row card-body">
                            
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

                <!-- MODAL - ELEGIR MATERIAL O ACTIVOS FIJOS -->
                <div class="modal fade" id="modal-elegir-material">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">
                          <a data-toggle="modal" data-target="#modal-agregar-material-activos-fijos">
                            <button id="btnAgregarArt" type="button" class="btn btn-success btn-sm" onclick="limpiar_materiales()"><span class="fa fa-plus"></span> Crear Productos</button>
                          </a>
                          Seleccionar producto
                        </h4>
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

                <!-- MODAL - AGREGAR MATERIALES O ACTIVOS FIJOS -->                 
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
                              <!-- cont registro -->
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

                <!-- MODAL - VER IMG GRANDE PRODUCTO -->
                <div class="modal fade" id="modal-ver-img-material">
                  <div class="modal-dialog modal-dialog-scrollable modal-md">
                    <div class="modal-content">
                      <div class="modal-header" style="background-color: #ce834926;">
                        <h4 class="modal-title nombre-img-material">Img producto</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">                        
                        <img onerror="this.src='../dist/svg/default_producto.svg';" src="" class="img-thumbnail " id="ver_img_material" style="cursor: pointer !important;" width="100%" />
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Modal Ver compras - charge -->
                <div class="modal fade" id="modal-ver-compras">
                  <div class="modal-dialog modal-dialog-scrollable modal-xl">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Detalle Compra</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <div class="row detalle_de_compra" id="cargando-5-fomulario">                            
                          <!--detalle de la compra-->
                        </div>

                        <div class="row" id="cargando-6-fomulario" style="display: none;">
                          <div class="col-lg-12 text-center">
                            <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                            <br />
                            <h4>Cargando...</h4>
                          </div>
                        </div>

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

        <?php require 'script.php';  ?>

        <!-- Bootstrap 4 -->
        <!-- <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script> -->
        <!-- jquery-validation -->
        <!-- <script src="../plugins/jquery-validation/jquery.validate.min.js"></script>
        <script src="../plugins/jquery-validation/additional-methods.min.js"></script> -->
        <!-- InputMask -->
        <!-- <script src="../plugins/moment/moment.min.js"></script>
        <script src="../plugins/inputmask/jquery.inputmask.min.js"></script>     -->
        <!-- sweetalert2 -->
        <!-- <script src="../plugins/sweetalert2/sweetalert2.all.min.js"></script> -->

        <script type="text/javascript" src="scripts/resumen_insumos.js"></script>

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
