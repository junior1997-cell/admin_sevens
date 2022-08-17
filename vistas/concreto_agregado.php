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
        <title>Concreto y Agregado | Admin Sevens</title>
        <?php $title = "Concreto y Agregado"; require 'head.php';  ?>       

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
                      <h1><i class="nav-icon fas fa-dumpster"></i> Concreto y Agregado</h1>
                    </div>
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Concreto</li>
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
                            <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-items" onclick="limpiar_form_item();"><i class="fas fa-plus-circle"></i> Agregar Item</button>
                            <button type="button" class="btn bg-gradient-info" data-toggle="modal" data-target="#modal-tabla-items" onclick="limpiar_form_item();"><i class="fas fa-eye"></i> Ver Item</button>
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
                                  <ul class="nav nav-tabs" id="tabs-for-tab" role="tablist">
                                    <li class="nav-item">
                                      <a class="nav-link active" id="tabs-for-resumen-tab" data-toggle="pill" href="#tabs-for-resumen" role="tab" aria-controls="tabs-for-resumen" aria-selected="true">Resumen</a>
                                    </li>
                                    <li class="nav-item">
                                      <a class="nav-link" id="tabs-for-concreto-tab" data-toggle="pill" href="#tabs-for-concreto" role="tab" aria-controls="tabs-for-concreto" aria-selected="false">Arena Gruesa</a>
                                    </li>                                    
                                  </ul>
                                </div>
                                <div class="card-body">
                                  <div class="tab-content" id="tabs-for-tabContent">
                                    <div class="tab-pane fade show active" id="tabs-for-resumen" role="tabpanel" aria-labelledby="tabs-for-resumen-tab">
                                      <div class="row">                                        
                                        <div class="col-12">
                                          <table id="tabla-materiales" class="table table-bordered table-striped display" style="width: 100% !important;">
                                            <thead>
                                              <tr>
                                                <th colspan="14" class="cargando text-center bg-danger"><i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando... </th>
                                              </tr>
                                              <tr>
                                                <th class="text-center">#</th>
                                                <th class="">Acciones</th>
                                                <th>Nombre</th>
                                                <th>Unidad</th>
                                                <th>Marca</th>
                                                <th data-toggle="tooltip" data-original-title="Precio Unitario">Precio ingresado</th>
                                                <th data-toggle="tooltip" data-original-title="Sub total">Subtotal</th>
                                                <th data-toggle="tooltip" data-original-title="IGV">IGV</th>
                                                <th data-toggle="tooltip" data-original-title="Precio real">Precio real</th>
                                                <th>Ficha técnica</th>
                                                <th>Estado</th>
                                                <th>Nombre</th>
                                                <th>Color</th>
                                                <th>Descripción</th>
                                              </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                              <tr>
                                                <th class="text-center">#</th>
                                                <th class="">Acciones</th>
                                                <th>Nombre</th>
                                                <th>Unidad</th>
                                                <th>Marca</th>
                                                <th data-toggle="tooltip" data-original-title="Precio Ingresado">Precio ingresado</th>
                                                <th data-toggle="tooltip" data-original-title="Sub total">Sub total</th>
                                                <th data-toggle="tooltip" data-original-title="IGV">IGV</th>
                                                <th data-toggle="tooltip" data-original-title="Precio real">Precio real</th>
                                                <th>Ficha técnica</th>
                                                <th>Estado</th>
                                                <th>Nombre</th>
                                                <th>Color</th>
                                                <th>Descripción</th>
                                              </tr>
                                            </tfoot>
                                          </table>
                                        </div>
                                        <!-- /.col -->
                                      </div>
                                      <!-- /.row -->
                                    </div>
                                    <div class="tab-pane fade" id="tabs-for-concreto" role="tabpanel" aria-labelledby="tabs-for-concreto-tab">
                                      <div class="row">
                                        <div class="col-12 mb-2">
                                          <button type="button" class="btn bg-gradient-success btn-sm" data-toggle="modal" data-target="#modal-agregar-detalle-items" ><i class="fas fa-plus-circle"></i> <span class="d-none d-sm-inline-block">Agregar </span></button>                                
                                          <!-- <button type="button" class="btn bg-gradient-danger btn-sm"><i class="fas fa-skull-crossbones"></i> <span class="d-none d-sm-inline-block">Eliminar</span></button> -->
                                        </div>
                                        <div class="col-12">
                                          <table id="tabla-materiales" class="table table-bordered table-striped display" style="width: 100% !important;">
                                            <thead>
                                              <tr>
                                                <th colspan="14" class="cargando text-center bg-danger"><i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando... </th>
                                              </tr>
                                              <tr>
                                                <th class="text-center">#</th>
                                                <th class="">Acciones</th>
                                                <th>Nombre</th>
                                                <th>Unidad</th>
                                                <th>Marca</th>
                                                <th data-toggle="tooltip" data-original-title="Precio Unitario">Precio ingresado</th>
                                                <th data-toggle="tooltip" data-original-title="Sub total">Subtotal</th>
                                                <th data-toggle="tooltip" data-original-title="IGV">IGV</th>
                                                <th data-toggle="tooltip" data-original-title="Precio real">Precio real</th>
                                                <th>Ficha técnica</th>
                                                <th>Estado</th>
                                                <th>Nombre</th>
                                                <th>Color</th>
                                                <th>Descripción</th>
                                              </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                              <tr>
                                                <th class="text-center">#</th>
                                                <th class="">Acciones</th>
                                                <th>Nombre</th>
                                                <th>Unidad</th>
                                                <th>Marca</th>
                                                <th data-toggle="tooltip" data-original-title="Precio Ingresado">Precio ingresado</th>
                                                <th data-toggle="tooltip" data-original-title="Sub total">Sub total</th>
                                                <th data-toggle="tooltip" data-original-title="IGV">IGV</th>
                                                <th data-toggle="tooltip" data-original-title="Precio real">Precio real</th>
                                                <th>Ficha técnica</th>
                                                <th>Estado</th>
                                                <th>Nombre</th>
                                                <th>Color</th>
                                                <th>Descripción</th>
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
                        <h4 class="modal-title titulo-comprobante-compra">Lista de Comprobantes</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body row">
                        <div class="col-12">
                          <button  class="btn btn-success btn-sm" data-toggle="modal"  data-target="#modal-agregar-items" onclick="limpiar_form_item();" >Agregar Item</button>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12 mt-3">
                          <table id="tabla-items" class="table table-bordered table-striped display " style="width: 100% !important;">
                            <thead>
                              <tr>
                                <th class="">#</th>
                                <th data-toggle="tooltip" data-original-title="Opciones">OP</th>
                                <th data-toggle="tooltip" data-original-title="Documentos">Nombre</th>
                                <th data-toggle="tooltip" data-original-title="Columna">Colum. Calidad</th>
                                <th data-toggle="tooltip" data-original-title="Columna">Descripción</th>                        
                              </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                              <tr>
                                <th class="">#</th>
                                <th data-toggle="tooltip" data-original-title="Opciones">OP</th>
                                <th data-toggle="tooltip" data-original-title="Documentos">Nombre</th>
                                <th data-toggle="tooltip" data-original-title="Columna">Colum. Calidad</th>
                                <th data-toggle="tooltip" data-original-title="Columna">Descripción</th>                                   
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
                  <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Agregar Items</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form id="form-items" name="form-items" method="POST">
                          <div class="card-body">
                            <div class="row" id="cargando-1-fomulario">
                              <!-- id proyecto -->
                              <input type="hidden" name="idproyecto" id="idproyecto" />
                              <!-- id tabla -->
                              <input type="hidden" name="idtipo_tierra" id="idtipo_tierra" />
                              <!-- id categoria_insumos_af -->
                              <input type="hidden" name="modulo" id="modulo" value="Concreto y Agregado" />

                              <!-- Nombre -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-8 col-xl-9">
                                <div class="form-group">
                                  <label for="nombre_item">Nombre <sup class="text-danger">*</sup></label>
                                  <input type="text" name="nombre_item" class="form-control" id="nombre_item" placeholder="Nombre del Item." />
                                </div>
                              </div>

                              <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                  <label for="columna_calidad">Columna Calidad </label>                                  
                                  <div class="custom-control custom-switch custom-switch-on-success">
                                    <input type="checkbox" class="custom-control-input" id="columna_calidad" name="columna_calidad" id="columna_calidad" value="1">
                                    <label class="custom-control-label cursor-pointer" for="columna_calidad"></label>
                                  </div>                               
                                </div>
                              </div>

                              <!--descripcion_material-->
                              <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                  <label for="descripcion_item">Descripción </label> <br />
                                  <textarea name="descripcion_item" id="descripcion_item" class="form-control" rows="2"></textarea>
                                </div>
                              </div>
                              
                              <!-- barprogress -->
                              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                                <div class="progress" id="barra_progress_items_div">
                                  <div id="barra_progress_items" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
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
                          <button type="submit" style="display: none;" id="submit-form-items">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_form_item();">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro_items">Guardar Cambios</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- MODAL - ITEMS AGREGAR DETALLE -->
                <div class="modal fade" id="modal-agregar-detalle-items">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Agregar </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form id="form-materiales" name="form-materiales" method="POST">
                          <div class="card-body">
                            <div class="row" id="cargando-1-fomulario">
                              <!-- id proyecto -->
                              <input type="hidden" name="idproyecto" id="idproyecto" />
                              <!-- id tabla -->
                              <input type="hidden" name="idproducto" id="idproducto" />
                              

                              <!-- Nombre -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                  <label for="nombre_material">Nombre <sup class="text-danger">(unico*)</sup></label>
                                  <input type="text" name="nombre_material" class="form-control" id="nombre_material" placeholder="Nombre del Insumo." />
                                </div>
                              </div>
                              <!-- Nombre -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                  <label for="nombre_material">Nombre <sup class="text-danger">(unico*)</sup></label>
                                  <input type="text" name="nombre_material" class="form-control" id="nombre_material" placeholder="Nombre del Insumo." />
                                </div>
                              </div>
                              <!-- Nombre -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                  <label for="nombre_material">Nombre <sup class="text-danger">(unico*)</sup></label>
                                  <input type="text" name="nombre_material" class="form-control" id="nombre_material" placeholder="Nombre del Insumo." />
                                </div>
                              </div>
                              <!-- Nombre -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                  <label for="nombre_material">Nombre <sup class="text-danger">(unico*)</sup></label>
                                  <input type="text" name="nombre_material" class="form-control" id="nombre_material" placeholder="Nombre del Insumo." />
                                </div>
                              </div>

                              <!--descripcion_material-->
                              <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                  <label for="descripcion_material">Descripción </label> <br />
                                  <textarea name="descripcion_material" id="descripcion_material" class="form-control" rows="2"></textarea>
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
                          <button type="submit" style="display: none;" id="submit-form-materiales">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_form_material();">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro">Guardar Cambios</button>
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

                <!-- MDOAL - VER COMPROBANTE -->
                <div class="modal fade" id="modal-ver-comprobante">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg ">
                    <div class="modal-content">
                      <div class="modal-header" >
                        <h4 class="modal-title">Comprobante: <b class="tile-modal-comprobante"></b></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="text-danger" aria-hidden="true">&times;</span></button>
                      </div>
                      <div class="modal-body">
                        <div class="row">
                          <div class="col-6 col-md-6">
                            <a class="btn btn-xs btn-block btn-warning" href="#" id="iddescargar" download="" type="button"><i class="fas fa-download"></i> Descargar</a>
                          </div>
                          <div class="col-6 col-md-6">
                            <a class="btn btn-xs btn-block btn-info" href="#" id="ver_completo"  target="_blank" type="button"><i class="fas fa-expand"></i> Ver completo.</a>
                          </div>
                          <div class="col-12 col-md-12 mt-2">
                            <div id="ver_fact_pdf" width="auto"></div>
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

        <?php  require 'script.php'; ?>        

        <script type="text/javascript" src="scripts/concreto_agregado.js"></script>

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>

        <?php require 'extra_script.php'; ?>        

      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
