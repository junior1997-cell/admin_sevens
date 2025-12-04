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
        <title>Activos fijos | Admin Sevens</title>
        
        <?php $title = "Activos fijos"; require 'template/head.php'; ?>
        
        <!--CSS  switch_MATERIALES-->
        <link rel="stylesheet" href="../dist/css/switch_materiales.css">

        <style>
          #tabla-activos_filter { width: calc(100% - 10px) !important; display: flex !important; justify-content: space-between !important; }
          #tabla-activos_filter label { width: 100% !important;  }
          #tabla-activos_filter label input { width: 100% !important;   }

          #tbla-facura_filter { width: calc(100% - 10px) !important; display: flex !important; justify-content: space-between !important; }
          #tbla-facura_filter label { width: 100% !important;  }
          #tbla-facura_filter label input { width: 100% !important;   }
        </style>

      </head>
      <body class="hold-transition sidebar-collapse sidebar-mini layout-fixed layout-navbar-fixed pace-orange" idproyecto="<?php echo $_SESSION['idproyecto']; ?>">
        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper">
          <?php
          require 'template/nav.php';
          require 'template/aside.php';
          if ($_SESSION['recurso']==1){
            //require 'template/enmantenimiento.php';
            ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
              <!-- Content Header (Page header) -->
              <section class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1>Activos fijos</h1>
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

                    <div class="col-12" id="div-tabla-principal">
                      <div class="card card-primary card-outline">
                        <div class="card-header">
                          <h3 class="card-title">
                            <button type="button" class="btn bg-gradient-success btn-agregar-material" data-toggle="modal" data-target="#modal-agregar-activos-fijos" onclick="limpiar();"><i class="fas fa-plus-circle"></i> Agregar</button>
                            
                            <button type="button" class="btn bg-gradient-warning btn-regresar"style="display: none;" onclick="table_show_hide(1);"> <i class="fas fa-arrow-left"></i> Regresar</button>
                          
                            Admnistra de manera eficiente a tus activos fijos.
                          </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body px-1 py-1">
                          <div class="row">                              
                            <div class=" col-12 col-sm-12">
                              <div  id="div_materiales">
                                <div class="card card-primary card-outline card-outline-tabs mb-0">
                                  <div class="card-header p-0 border-bottom-0">
                                    <ul class="nav nav-tabs lista-items" id="tabs-for-tab" role="tablist">
                                      <li class="nav-item">
                                        <a class="nav-link active" role="tab" ><i class="fas fa-spinner fa-pulse fa-sm"></i></a>
                                      </li>           
                                    </ul>
                                  </div>
                                  <div class="card-body" >                                  
                                    <div class="tab-content" id="tabs-for-tabContent">
                                      <!-- TABLA - RESUMEN -->
                                      <div class="tab-pane fade show active" id="tabs-for-activo-fijo" role="tabpanel" aria-labelledby="tabs-for-activo-fijo-tab">
                                        <div class="table-responsive">                                        
                                          
                                          <table id="tabla-activos" class="table table-bordered table-striped display" style="width: 100% !important;">
                                            <thead> 
                                              <tr>
                                                <th class="text-center">#</th>
                                                <th class="">Acciones</th>
                                                <th class="">Code</th>
                                                <th>Producto</th>
                                                <th>UM</th>
                                                <th>Marca</th>
                                                <th>Precio Promedio</th>
                                                <th>Compra</th>
                                                <th data-toggle="tooltip" data-original-title="Ficha técnica">FT</th>
                                                <th >Nombre</th>
                                                <th >Descripción</th>
                                                <th >Marcas</th>
                                              </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                              <tr>
                                                <th class="text-center">#</th>
                                                <th class="">Acciones</th>
                                                <th class="">Code</th>
                                                <th>Producto</th>
                                                <th>UM</th>
                                                <th>Marca</th>
                                                <th>Precio Promedio</th>
                                                <th>Compra</th>
                                                <th data-toggle="tooltip" data-original-title="Ficha técnica">FT</th>
                                                <th >Nombre</th>
                                                <th >Descripción</th>
                                                <th >Marcas</th>
                                              </tr>
                                            </tfoot>
                                          </table>
                                          
                                        </div>
                                        <!-- /.row -->
                                      </div>                                    
                                    </div>
                                    <!-- /.tab-content -->
                                  </div>
                                  <!-- /.card-body -->
                                </div>
                              </div>
                              <div id="div_facturas" style="display: none;">
                                <div class="table-responsive">
                                  <table id="tbla-facura" class="table table-bordered table-striped display" style="width: 100% !important;">
                                      <thead>
                                        <tr>
                                          <th>#</th>
                                          <th>Op.</th>
                                          <th>Proveedor</th>
                                          <th>N° Comprob.</th>
                                          <th>Proyecto</th>
                                          <th>Fecha compra</th>
                                          <th data-toggle="tooltip" data-original-title="Centidad">Cant.</th>
                                          <th>Precio</th>  
                                          <th data-toggle="tooltip" data-original-title="Descuento">Dcto.</th>
                                          <th>SubTotal</th>
                                          <th data-toggle="tooltip" data-original-title="Comprobante">CFDI.</th>    
                                          <th>Tipo</th>
                                          <th>N° Comprob</th>
                                        </tr>
                                      </thead>
                                      <tbody>                         
                                        
                                      </tbody>
                                      <tfoot>
                                        <tr>
                                          <th>#</th>
                                          <th>Op.</th>
                                          <th>Proveedor</th>
                                          <th>N° Comprob.</th>
                                          <th>Proyecto</th>
                                          <th >Fecha compra</th>
                                          <th class="cantidad_x_producto text-center"><i class="fas fa-spinner fa-pulse fa-sm"></i></th>
                                          <th class="text-nowrap px-2 h5"><div class="formato-numero-conta"><span>S/</span><span class="precio_promedio">0.00</span></div></th>  
                                          <th class="text-nowrap px-2"><div class="formato-numero-conta"><span>S/</span><span class="descuento_x_producto">0.00</span></div></th> 
                                          <th class="text-nowrap px-2"> <div class="formato-numero-conta"><span>S/</span><span class="subtotal_x_producto">0.00</span></div></th>
                                          <th data-toggle="tooltip" data-original-title="Comprobante">CFDI.</th>                            
                                          <th>Tipo</th>
                                          <th>N° Comprob</th>
                                        </tr>
                                      </tfoot>
                                  </table>
                                </div>
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
                                      <input type="hidden" name="tipo_compra" id="tipo_compra" value="GENERAL" /> 

                                      <!-- Proveedor -->
                                      <div class="col-sm-12 col-md-12 col-lg-6">
                                        <div class="form-group">
                                          <label for="idproveedor">Proveedor <sup class="text-danger">*</sup></label>
                                          <select id="idproveedor" name="idproveedor" class="form-control select2" data-live-search="true" required title="Seleccione cliente" onchange="extrae_ruc();"> </select>
                                        </div>
                                      </div>

                                      <!-- adduser -->
                                      <!--<div class="col-sm-2 col-md-2 col-lg-1">
                                        <div class="form-group">
                                          <label for="Add" class="d-none d-sm-inline-block text-break" style="color: white;">.</label> <br class="d-none d-sm-inline-block">
                                          <a data-toggle="modal" href="#modal-agregar-proveedor" class="w-50">
                                            <button type="button" class="btn btn-success p-x-6px " data-toggle="tooltip" data-original-title="Agregar Provedor" onclick="limpiar_form_proveedor();">
                                              <i class="fa fa-user-plus" aria-hidden="true"></i>
                                            </button>
                                          </a>

                                          <button type="button" class="btn btn-warning p-x-6px btn-editar-proveedor" data-toggle="tooltip" data-original-title="Editar:" onclick="mostrar_para_editar_proveedor();">
                                            <i class="fa-solid fa-pencil" aria-hidden="true"></i>
                                          </button>


                                        </div>

                                      </div>-->

                                      <!-- fecha -->
                                      <div class="col-sm-6 col-md-6 col-lg-3">
                                        <div class="form-group">
                                          <label for="fecha_compra">Fecha <sup class="text-danger">*</sup></label>
                                          <input type="date" name="fecha_compra" id="fecha_compra" class="form-control" placeholder="Fecha" />
                                        </div>
                                      </div>

                                      <!-- Glosa -->
                                      <div class="col-sm-6 col-md-6 col-lg-3">
                                        <div class="form-group">
                                          <label for="glosa">Glosa <sup class="text-danger">*</sup></label>
                                          <select id="glosa" name="glosa" class="form-control select2" data-live-search="true" required title="Seleccione glosa"> 
                                            <option title="fas fa-hammer" value="MATERIAL">MATERIAL</option>
                                            <option title="fas fa-gas-pump" value="COMBUSTIBLE">COMBUSTIBLE</option>
                                            <option title="fas fa-snowplow" value="EQUIPOS">EQUIPOS</option>
                                          </select>
                                        </div>
                                      </div>

                                      <!-- Tipo de comprobante -->
                                      <div class="col-sm-6 col-md-6 col-lg-4" id="content-tipo-comprobante">
                                        <div class="form-group">
                                          <label for="tipo_comprobante">Tipo Comprobante <sup class="text-danger">*</sup></label>
                                          <select name="tipo_comprobante" id="tipo_comprobante" class="form-control select2"  onchange="default_val_igv(); modificarSubtotales(); ocultar_comprob();" placeholder="Seleccinar un tipo de comprobante">
                                            <option value="Ninguno">Ninguno</option>
                                            <option value="Boleta">Boleta</option>
                                            <option value="Factura">Factura</option>
                                            <option value="Nota de Crédito">Nota de Crédito</option>
                                            <option value="Nota de venta">Nota de venta</option>
                                          </select>
                                        </div>
                                      </div>
                                      <!-- Tipo de serie de comprobante para la anulación de la factura -->
                                      <div class="col-lg-2" id="content_slt2_serie_comprobante">
                                        <div class="form-group">
                                          <label for="slt2_serie_comprobante">Nro. Factura <sup class="text-danger">(Para Nota de credito*)</sup></label>
                                          <select id="slt2_serie_comprobante" name="slt2_serie_comprobante" class="form-control select2 slt2_serie_comprobante" data-live-search="true" title="Seleccionar"> </select>
                                        </div>
                                      </div>

                                      <!-- serie_comprobante-->
                                      <div class="col-sm-6 col-md-6 col-lg-2" id="content-serie-comprobante">
                                        <div class="form-group">
                                          <label for="serie_comprobante">N° de Comprobante</label>
                                          <input type="text" name="serie_comprobante" id="serie_comprobante" class="form-control" placeholder="N° de Comprobante" />
                                        </div>
                                      </div>

                                      <!-- IGV-->
                                      <div class="col-sm-6 col-md-6 col-lg-1" id="content-igv">
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
                                              <h6 class="font-weight-bold subtotal_compra">S/ 0.00</h6>
                                              <input type="hidden" name="subtotal_compra" id="subtotal_compra" />
                                              <input type="hidden" name="tipo_gravada" id="tipo_gravada" />

                                              <h6 class="font-weight-bold igv_compra">S/ 0.00</h6>
                                              <input type="hidden" name="igv_compra" id="igv_compra" />
                                              
                                              <h5 class="font-weight-bold total_venta">S/ 0.00</h5>
                                              <input type="hidden" name="total_venta" id="total_venta" />
                                              
                                            </th>
                                          </tfoot>
                                          <tbody class="orden_producto"></tbody>
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
                                  <button type="button" class="btn btn-danger" onclick="table_show_hide(3);" data-dismiss="modal">Close</button>
                                  <button type="button" class="btn btn-success" style="display: none;" id="guardar_registro_compras">Guardar Cambios</button>
                                </div>
                              </div>

                            </div>
                            <!-- /.col -->
                          </div>
                          
                        </div>
                        <!-- /.card-body -->
                      </div>
                      <!-- /.card --> 
                    </div>

                    <div class="col-lg-12" id="div-detalle-compras" >

                    </div>


                    <!-- /.col -->
                  </div>
                  <!-- /.row -->
                </div>
                <!-- /.container-fluid -->

                <!-- MODAL - AGREGAR ACTIVOS FIJOS -->
                <div class="modal fade" id="modal-agregar-activos-fijos">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Agregar Activo fijo</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form id="form-materiales-activos-fijos" name="form-materiales-activos-fijos" method="POST">
                          <div class="card-body">
                            <div class="row" id="cargando-1-fomulario">
                              <!--  -->
                              <input type="hidden" name="idproducto" id="idproducto" /> 
                              
                              <input type="hidden" id="modelo" name="modelo" />
                              <input type="hidden" id="serie" name="serie" />
                              <input type="hidden" id="color" name="color" value="1" />

                              <input type="hidden" id="precio_unitario" name="precio_unitario" value="0" />
                              <input type="hidden" id="estado_igv" name="estado_igv" value="0" />
                              <input type="hidden" id="precio_sin_igv" name="precio_sin_igv" value="0" />
                              <input type="hidden" id="precio_igv" name="precio_igv" value="0" />
                              <input type="hidden" id="precio_total" name="precio_total" value="0" />

                              <!-- Nombre -->
                              <div class="col-12 col-sm-12 col-md-12 col-lg-8">
                                <div class="form-group">
                                  <label for="nombre">Nombre <sup class="text-danger">(unico*)</sup></label>
                                  <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Nombre del activo." />
                                </div>
                              </div>

                              <!-- Clasificación -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="categoria_insumos_af">Clasificación <sup class="text-danger">(unico*)</sup></label>
                                  <select name="categoria_insumos_af" id="categoria_insumos_af" class="form-control select2" style="width: 100%;"> 
                                  </select>
                                </div>
                              </div>                               
                              
                              <!-- Unnidad de medida-->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-6" >
                                <div class="form-group">
                                  <label for="Unidad-medida">Unidad-medida <sup class="text-danger">(unico*)</sup></label>
                                  <select name="unidad_medida" id="unidad_medida" class="form-control select2" style="width: 100%;"> </select>
                                </div>
                              </div>     
                              
                              <!-- select2 multiple marca-->
                              <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                                <div class="form-group">
                                  <label for="marcas">Marca <sup class="text-danger">(unico*)</sup></label>
                                  <div class="select2-purple">
                                    <select name="marcas[]" id="marcas" class="form-control select2" multiple="multiple" data-dropdown-css-class="select2-purple" data-placeholder="Seleccione" style="width: 100%;"> </select>
                                  </div>
                                </div>
                              </div>

                              <!--Descripcion-->
                              <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                  <label for="descripcion">Descripción </label> <br />
                                  <textarea name="descripcion" id="descripcion" class="form-control" rows="2"></textarea>
                                </div>
                              </div>

                              <!--imagen-material-->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                <label for="foto1">Imagen</label>
                                <div style="text-align: center;">
                                  <img
                                    onerror="this.src='../dist/img/default/img_defecto_activo_fijo.png';"
                                    src="../dist/img/default/img_defecto_activo_fijo.png"
                                    class="img-thumbnail"
                                    id="foto1_i"
                                    style="cursor: pointer !important; height: 100% !important;"
                                    width="auto"
                                  />
                                  <input style="display: none;" type="file" name="foto1" id="foto1" accept="image/*" />
                                  <input type="hidden" name="foto1_actual" id="foto1_actual" />
                                  <div class="text-center" id="foto1_nombre"><!-- aqui va el nombre de la FOTO --></div>
                                </div>
                              </div>

                              <!-- Ficha tecnica -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                <label for="doc2_i" >Ficha técnica <b class="text-danger">(Imagen o PDF)</b> </label>  
                                <div class="row text-center">                               
                                  <!-- Subir documento -->
                                  <div class="col-6 col-md-6 col-lg-6 col-xl-6 text-center">
                                    <button type="button" class="btn btn-success btn-block btn-xs" id="doc2_i">
                                      <i class="fas fa-upload"></i> Subir.
                                    </button>
                                    <input type="hidden" id="doc_old_2" name="doc_old_2" />
                                    <input style="display: none;" id="doc2" type="file" name="doc2" accept="application/pdf, image/*" class="docpdf" /> 
                                  </div>
                                  <!-- Recargar -->
                                  <div class="col-6 col-md-6 col-lg-6 col-xl-6 text-center comprobante">
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

                              <!-- barprogress -->
                              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                                <div class="progress" id="div_barra_progress">
                                  <div id="barra_progress" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                    0%
                                  </div>
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

                <!-- MODAL-->
                <div class="modal fade" id="modal-ver-activos-fijos">
                  <div class="modal-dialog modal-dialog-scrollable modal-xm">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Datos Activos Fijos</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <div id="datos-activos-fjos" class=""></div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- MODAL - FICHA TECNICA-->
                <div class="modal fade" id="modal-ver-ficha_tec">
                  <div class="modal-dialog modal-dialog-scrollable modal-xl">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Ficha Técnica</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div class="class-style" style="text-align: center;">
                          <a class="btn btn-warning btn-block" href="#" id="iddescargar" download="Ficha Técnica" style="padding: 0px 12px 0px 12px !important;" type="button"><i class="fas fa-download"></i></a>
                          <br />
                          <img onerror="this.src='../dist/img/default/img_defecto.png';" src="../dist/img/default/img_defecto.png" class="img-thumbnail" id="img-factura" style="cursor: pointer !important;" width="auto" />
                          <div id="ver_fact_pdf" style="cursor: pointer !important;" width="auto"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- MODAL - VER PERFIL INSUMO-->
                <div class="modal fade" id="modal-ver-perfil-activo-fijo">
                  <div class="modal-dialog modal-dialog-centered modal-md">
                    <div class="modal-content bg-color-0202022e shadow-none border-0">
                      <div class="modal-header">
                        <h4 class="modal-title text-white foto-insumo">Foto Activo Fijo</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-white cursor-pointer" aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body"> 
                        <div id="perfil-insumo" class="class-style">
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
            require 'template/noacceso.php';
          }
          require 'template/footer.php';
          ?>
        </div>
        <!-- /.content-wrapper -->
        <?php require 'template/script.php'; ?>     
        
        <!-- Jquery UI -->
        <script src="../plugins/jquery-ui/jquery-ui.min.js"></script>
        <script src="../plugins/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

        <script type="text/javascript" src="scripts/activos_fijos.js?version_jdl=2.07"></script>
        <script type="text/javascript" src="scripts/js_compra_insumo_repetido.js?version_jdl=2.07"></script>


        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>
        
      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
