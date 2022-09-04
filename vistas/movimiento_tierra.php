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
        <title>Movimiento de Tierra | Admin Sevens</title>
        <?php $title = "Movimiento de Tierra"; require 'head.php';  ?>       

        <link rel="stylesheet" href="../dist/css/switch_materiales.css">

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
                      <h1><i class="nav-icon fas fa-dumpster"></i> Movimiento de Tierra</h1>
                    </div>
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Tierra</li>
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
                            <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-items" onclick="limpiar_form_grupo();"><i class="fas fa-plus-circle"></i> Agregar Grupo</button>
                            <button type="button" class="btn bg-gradient-info" data-toggle="modal" data-target="#modal-tabla-items"><i class="fas fa-eye"></i> Ver Grupo</button>
                            Admnistra de manera eficiente el Concreto y Agregado.
                          </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body px-1 py-1">
                          <div class="row"> 
                            <div class="filtros-inputs col-12 col-sm-12">       
                              <!-- filtros -->
                              <div class="row my-2">

                                <!-- filtro por: fecha inicial -->
                                <div class="col-12 col-sm-6 col-md-6 col-lg-2">    
                                  <div class="form-group">
                                    <!-- <label for="filtro_fecha_inicio" >Fecha inicio </label> -->
                                    <div class="input-group date"  >
                                      <div class="input-group-append cursor-pointer click-btn-fecha-inicio" >
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                      </div>
                                      <input type="text" class="form-control"  id="filtro_fecha_inicio" onchange="cargando_search(); delay(function(){filtros()}, 50 );" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask autocomplete="off" />                                    
                                    </div>
                                  </div>                                
                                </div>

                                <!-- filtro por: fecha final -->
                                <div class="col-12 col-sm-6 col-md-6 col-lg-2">                                
                                  <div class="form-group">
                                    <!-- <label for="filtro_fecha_inicio" >Fecha fin </label> -->
                                    <div class="input-group date"  >
                                      <div class="input-group-append cursor-pointer click-btn-fecha-fin" >
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                      </div>
                                      <input type="text" class="form-control"  id="filtro_fecha_fin" onchange="cargando_search(); delay(function(){filtros()}, 50 );" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask autocomplete="off" />                                    
                                    </div>
                                  </div> 
                                </div>

                                <!-- filtro por: proveedor -->
                                <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                  <div class="form-group">
                                    <!-- <label for="filtros" class="cargando_proveedor">Proveedor &nbsp;<i class="text-dark fas fa-spinner fa-pulse fa-lg"></i><br /></label> -->
                                    <select id="filtro_proveedor" class="form-control select2" onchange="cargando_search(); delay(function(){filtros()}, 50 );" style="width: 100%;"> 
                                    </select>
                                  </div>                          
                                </div>

                                <!-- filtro por: comprobante -->
                                <div class="col-12 col-sm-6 col-md-6 col-lg-2">
                                  <div class="form-group">
                                    <!-- <label for="filtros" >Tipo comprobante </label> -->
                                    <select id="filtro_tipo_comprobante" disabled class="form-control select2" onchange="cargando_search(); delay(function(){filtros()}, 50 );" style="width: 100%;"> 
                                      <option value="0">Todos</option>
                                      <option value="Ninguno">Ninguno</option>
                                      <option value="Boleta">Boleta</option>
                                      <option value="Factura">Factura</option>
                                      <option value="Nota de venta">Nota de venta</option>
                                    </select>
                                  </div>
                                  
                                </div>
                              </div>     
                            </div>

                            <div class=" col-12 col-sm-12">
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
                                    <div class="tab-pane fade show active" id="tabs-for-resumen" role="tabpanel" aria-labelledby="tabs-for-resumen-tab">
                                      <div class="row">                                        
                                        <div class="col-12">
                                          <table id="tabla-resumen" class="table table-bordered table-striped display" style="width: 100% !important;">
                                              <thead>                                              
                                                <tr>
                                                  <th class="text-center">#</th>
                                                  <th>INSUMO</th>
                                                  <th>UND</th>
                                                  <th class="text-center" >CANTIDAD</th>
                                                  <th data-toggle="tooltip" data-original-title="Precio Promedio">PRECIO PARCIAL</th>
                                                  <th >DESCUENTO</th>
                                                  <th data-toggle="tooltip" data-original-title="Sub total">PRECIO TOTAL</th>
                                                </tr>
                                              </thead>
                                              <tbody></tbody>
                                              <tfoot>
                                                <tr>
                                                  <th class="text-center">#</th>
                                                  <th>INSUMO</th>
                                                  <th>UND</th>
                                                  <th class="text-center px-2" ><span class="total_resumen_cantidad">0.00</span></th>
                                                  <th >PRECIO PARCIAL</th>
                                                  <th class="text-nowrap px-2"><div class="formato-numero-conta"> <span>S/</span><span class="total_resumen_descuento">0.00</span></div></th>
                                                  <th class="text-nowrap px-2"><div class="formato-numero-conta"> <span>S/</span><span class="total_resumen">0.00</span></div></th>
                                                </tr>
                                              </tfoot>
                                            </table>
                                        </div>
                                        <!-- /.col -->
                                      </div>
                                      <!-- /.row -->
                                    </div> 

                                    <!-- TABLA - TIERRAS -->
                                    <div class="tab-pane fade" id="tabs-for-tierra" role="tabpanel" aria-labelledby="tabs-for-tierra-tab">
                                      <div class="row">
                                        <!-- <div class="col-12 mb-2">
                                          <button type="button" class="btn bg-gradient-success btn-sm btn-agregar-tierra" data-toggle="modal" data-target="#modal-agregar-detalle-items" onclick="limpiar_form_detalle_item();" ><i class="fas fa-plus-circle"></i> <span class="d-none d-sm-inline-block">Agregar </span></button>                                
                                        </div> -->
                                        <div class="col-12 row-horizon disenio-scroll">
                                          <table id="tabla-tierra" class="table table-bordered table-striped display" style="width: 100% !important;">
                                            <thead>
                                              <tr>
                                                <th colspan="14" class="cargando_concreto text-center bg-danger"><i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando... </th>
                                              </tr>
                                              <tr>
                                                <th class="text-center">#</th>
                                                <th data-toggle="tooltip" data-original-title="Opciones">OP</th>
                                                <th>Descipcion</th>
                                                <th data-toggle="tooltip" data-original-title="Día de Semana">Dia</th>
                                                <th>Fecha</th>
                                                <th class="text-center">Cantidad</th>
                                                <th data-toggle="tooltip" data-original-title="Precio Unitario">Subtotal</th>                                                
                                                <th >Bombeado</th>
                                                <th >Descuento</th>
                                                <th >Precio Total</th>
                                                <th >Provedor</th>
                                                <th >CFDI</th>
                                              </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                              <tr>
                                                <th class="text-center">#</th>
                                                <th data-toggle="tooltip" data-original-title="Opciones">OP</th>
                                                <th>Descipcion</th>
                                                <th data-toggle="tooltip" data-original-title="Día de Semana">Dia</th>
                                                <th>Fecha</th>
                                                <th class="text-nowrap px-2 text-center" ><span class="total_concreto_cantidad">0.00</span></th>
                                                <th class="text-nowrap px-2"><div class="formato-numero-conta"> <span>S/</span><span class="total_concreto_subtotal">0.00</span></div></th>
                                                <th ><div class="formato-numero-conta"> <span>S/</span><span class="total_concreto_bombeado">0.00</span></div></th>
                                                <th class="text-nowrap px-2"><div class="formato-numero-conta"> <span>S/</span><span class="total_concreto_descuento">0.00</span></div></th>                                                
                                                <th class="text-nowrap px-2"><div class="formato-numero-conta"> <span>S/</span><span class="total_concreto">0.00</span></div></th>
                                                <th >Provedor</th>
                                                <th >CFDI</th>
                                              </tr>
                                            </tfoot>
                                          </table>
                                        </div>
                                        <!-- /.col -->
                                      </div>
                                      <!-- /.row -->
                                    </div>
                                  </div>
                                  <!-- /.tab-content -->
                                </div>
                                <!-- /.card-body -->
                              </div>
                            </div>
                            <!-- /.col -->
                          </div>
                          <!-- /.row -->
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

                <!-- MODAL - ITEMS TABLA  -->
                <div class="modal fade" id="modal-tabla-items">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                      <div class="modal-header"> 
                        <h4 class="modal-title titulo-comprobante-compra">Lista de Grupos</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body row">
                        <div class="col-12">
                          <button  class="btn btn-success btn-sm" data-toggle="modal"  data-target="#modal-agregar-items" onclick="limpiar_form_grupo();" >Agregar</button>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12 mt-3">
                          <table id="tabla_item" class="table table-bordered table-striped display " style="width: 100% !important;">
                            <thead>
                              <tr>
                                <th class="">#</th>
                                <th data-toggle="tooltip" data-original-title="Opciones">OP</th>
                                <th data-toggle="tooltip" data-original-title="Nombre">Nombre</th>
                                <th data-toggle="tooltip" data-original-title="descripcion">Descripcion</th>                          
                              </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                              <tr>
                                <th class="">#</th>
                                <th data-toggle="tooltip" data-original-title="Opciones">OP</th>
                                <th data-toggle="tooltip" data-original-title="Nombre">Nombre</th>
                                <th data-toggle="tooltip" data-original-title="descripcion">Descripcion</th>                                      
                              </tr>
                            </tfoot>
                          </table>
                        </div>

                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- MODAL - ITEMS AGREGAR  -->
                <div class="modal fade bg-color-02020280" id="modal-agregar-items">
                  <div class="modal-dialog modal-dialog-scrollable modal-md">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Agregar Grupo</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form id="form-item" name="form-item" method="POST">
                          <div class="card-body">
                            <div class="row" id="cargando-1-fomulario">
                              <!-- id proyecto -->
                              <input type="hidden" name="idproyecto" id="idproyecto" />
                              <!-- idtipo_tierra -->
                              <input type="hidden" name="idtipo_tierra" id="idtipo_tierra" />
                              <!--  modulo -->
                              <input type="hidden" name="modulo" id="modulo" value="Movimiento de Tierras" />
                              <input type="hidden" name="columna_servicio_bombeado" id="columna_servicio_bombeado" value="0">

                              <!-- Nombre -->
                              <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                  <label for="nombre">Nombre <sup class="text-danger">*</sup></label>
                                  <input type="text" name="nombre_item" class="form-control" id="nombre_item" placeholder="Nombre del Insumo." />
                                </div>
                              </div>
                              <!--descripcion-->
                              <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                  <label for="descripcion_item">Descripción </label> <br />
                                  <textarea name="descripcion_item" id="descripcion_item" class="form-control" rows="2"></textarea>
                                </div>
                              </div>
                              
                              <!-- barprogress -->
                              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                                <div class="progress" id="barra_progress_div">
                                  <div id="barra_progress" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                    0%
                                  </div>
                                </div>
                              </div>

                            </div>
                            <!-- /.cargando -->

                            <div class="row" id="cargando-2-fomulario" style="display: none;">
                              <div class="col-lg-12 text-center">
                                <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                                <br />
                                <h4>Cargando...</h4>
                              </div>
                            </div>
                            <!-- /.cargando -->
                          </div>
                          <!-- /.card-body -->
                          <button type="submit" style="display: none;" id="submit-form-item">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_form_grupo();">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro_item">Guardar Cambios</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- MODAL - ITEMS AGREGAR DETALLE -->
                <div class="modal fade" id="modal-agregar-detalle-items">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Agregar: <b class="modal-title-detalle-items"></b> </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form id="form-detalle-item" name="form-detalle-item" method="POST">
                          <div class="card-body">
                            <div class="row" id="cargando-3-fomulario">
                              <!-- id tabla -->
                              <input type="hidden" name="idmovimiento_tierra" id="idmovimiento_tierra" />
                              <!-- id categoria_insumos_af -->
                              <input type="hidden" name="idtipo_tierra_det" id="idtipo_tierra_det" />

                               <!-- Proveedor -->
                               <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                  <label for="idproveedor">Proveedor <sup class="text-danger">*</sup></label>
                                  <select id="idproveedor" name="idproveedor" class="form-control select2" style="width: 100%;"> </select>
                                </div>
                              </div>

                              <!-- Fecha  -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                  <label for="fecha">Fecha <sup class="text-danger">*</sup></label>
                                  <input type="date" name="fecha" class="form-control" id="fecha" onchange="optener_dia_de_semana();" onkeyup="optener_dia_de_semana();" />
                                </div>
                              </div>
                              <!-- idmovimiento_tierra,idtipo_tierra_det,idproveedor,fecha,nombre_dia,cantidad,precio_unitario,total,descripcion_tierra -->
                              <!-- Nombre dia -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                  <label for="nombre_dia">Nombre dia <sup class="text-danger">*</sup></label>
                                  <input type="text" name="nombre_dia" class="form-control" id="nombre_dia" placeholder="Nombre dia." readonly />
                                </div>
                              </div>

                              <!-- Cantidad -->
                              <div class="col-12 col-sm-3 col-md-3 col-lg-4">
                                <div class="form-group">
                                  <label for="cantidad">Cantidad <sup class="text-danger">*</sup></label>
                                  <input type="number" name="cantidad" class="form-control" id="cantidad" placeholder="Cantidad." onchange="calcular_total();" onkeyup="calcular_total();"/>
                                </div>
                              </div>                            

                              <!-- Precio Parcial  -->
                              <div class="col-12 col-sm-5 col-md-5 col-lg-4">
                                <div class="form-group">
                                  <label for="precio_unitario">Precio Parcial  <sup class="text-danger">(unico*)</sup></label>
                                  <input type="number" name="precio_unitario" class="form-control" id="precio_unitario" placeholder="Precio Parcial." onchange="calcular_total();" onkeyup="calcular_total();" />
                                </div>
                              </div>

                               <!-- Precio Total -->
                               <div class="col-12 col-sm-4 col-md-4 col-lg-4">
                                <div class="form-group">
                                  <label for="total">Precio Total <sup class="text-danger">*</sup></label>
                                  <input type="text" name="total" class="form-control" id="total" placeholder="Precio Total." readonly />
                                </div>
                              </div>
                              <!--  -->
                              <!-- Descripcion -->
                              <div class="col-12 col-sm-12 col-md-12 col-lg-12" style="display:none;">
                                <div class="form-group">
                                  <label for="">Descripción </label> <br />
                                  <textarea name="descripcion_tierra" id="descripcion_tierra" class="form-control" rows="2"></textarea>
                                </div>
                              </div>
                              
                              <!-- barprogress -->
                              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                                <div class="progress" id="barra_progress_div">
                                  <div id="barra_progress" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                    0%
                                  </div>
                                </div>
                              </div>

                            </div>
                            <!-- /.cargando -->

                            <div class="row" id="cargando-4-fomulario" style="display: none;">
                              <div class="col-lg-12 text-center">
                                <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                                <br />
                                <h4>Cargando...</h4>
                              </div>
                            </div>
                            <!-- /.cargando -->
                          </div>
                          <!-- /.card-body -->
                          <button type="submit" style="display: none;" id="submit-form-detalle-item">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_form_detalle_item();">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro_detalle_item">Guardar Cambios</button>
                      </div>
                    </div>
                  </div>
                </div>                 

                <!-- MODAL - VER DETALLE ITEM-->
                <div class="modal fade" id="modal-ver-detalle-item">
                  <div class="modal-dialog modal-dialog-scrollable modal-md">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Datos del Insumo</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <div id="datosinsumo" class="class-style">
                          <!-- vemos los datos del trabajador -->
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- MODAL - DETALLE compras - charge -->
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
                        <button type="button" class="btn btn-success float-right" id="excel_compra" onclick="export_excel_detalle_factura()" ><i class="far fa-file-excel"></i> Excel</button>
                        <a type="button" class="btn btn-info" id="print_pdf_compra" target="_blank" ><i class="fas fa-print"></i> Imprimir/PDF</a>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- MODAL - ver grande img producto -->
                <div class="modal fade bg-color-02020280" id="modal-ver-img-material">
                  <div class="modal-dialog modal-dialog-scrollable modal-md shadow-0px1rem3rem-rgb-0-0-0-50 rounded">
                    <div class="modal-content bg-color-0202022e shadow-none border-0" >
                      <div class="modal-header">
                        <h4 class="modal-title text-white nombre-img-material">Img producto</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-white" aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div class="text-center" id="ver_img_insumo_o_activo_fijo"> </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- MODAL -  comprobantes - charge -->
                <div class="modal fade" id="modal-tabla-comprobantes-compra">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                      <div class="modal-header"> 
                        <h4 class="modal-title titulo-comprobante-compra">Lista de Comprobantes</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body row">                        
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12 mt-3">
                          <table id="tabla-comprobantes-compra" class="table table-bordered table-striped display " style="width: 100% !important;">
                            <thead>
                              <tr>
                                <th class="">#</th>
                                <th data-toggle="tooltip" data-original-title="Opciones">OP</th>
                                <th data-toggle="tooltip" data-original-title="Documentos">Comprobante</th>
                                <th data-toggle="tooltip" data-original-title="Fecha de subida">Fecha</th>                          
                              </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                              <tr>
                                <th class="">#</th>
                                <th class="">OP</th>
                                <th>Doc</th>
                                <th>Fecha</th>                                    
                              </tr>
                            </tfoot>
                          </table>
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

        <?php  require 'script.php'; ?>    
        <!-- table export EXCEL -->
        <script src="../plugins/export-xlsx/xlsx.full.min.js"></script>
        <script src="../plugins/export-xlsx/FileSaver.min.js"></script>
        <script src="../plugins/export-xlsx/tableexport.min.js"></script>    

        <script type="text/javascript" src="scripts/movimiento_tierra.js"></script>

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>

      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
