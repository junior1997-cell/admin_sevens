<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start(); date_default_timezone_set('America/Lima');

if (!isset($_SESSION["nombre"])) {

  header("Location: index.php?file=" . basename($_SERVER['PHP_SELF']));
} else { ?>

  <!DOCTYPE html>
  <html lang="es">

  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title> Almacén | Admin Sevens </title>

    <?php $title = "Almacén";
    require 'head.php'; ?>

    <!--CSS  switch_MATERIALES-->
    <link rel="stylesheet" href="../dist/css/switch_materiales.css" />
    <link rel="stylesheet" href="../dist/css/leyenda.css" />

    <style>
      .table-container {
        /* max-height: 560px; */
        /* Ajusta la altura máxima según tus necesidades */
        overflow-y: scroll;
      }

      .custom-table { border-collapse: collapse; width: 100%; }

      .custom-table th, .custom-table td { border: 1px solid black; padding: 8px; text-align: center; }

      .custom-table th, .custom-table td {
        padding: 6px;
        /* Ajusta el valor del padding según tus necesidades */
      }

      .custom-table th {
        text-align: center;
        width: auto;
        /* Opcional: ajusta el ancho del encabezado según tus necesidades */
        /* vertical-align: middle; Centra verticalmente el contenido del encabezado */
        vertical-align: middle !important;
      }

      .style-head { padding-bottom: 4px !important; padding-top: 4px !important; padding-right: 10px !important; padding-left: 10px !important; }
      .st_tr_style{
        /* background: #fff;  */
        border-bottom: 0;
        box-shadow: inset 0 1px 0 #dee2e6, inset 0 -1px 0 #dee2e6;
        position: -webkit-sticky;
        position: sticky;
        top: 0;
        z-index: 10;
      }
      .text_producto{  text-align: inherit !important; }

      /*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
      ::                  FIJADO DE COLUMNAS
      :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */  

      /* Fijar las primeras 3 columnas */
      .tabla_almacen th:nth-child(1),
      .tabla_almacen td:nth-child(1) {
        position: sticky !important;
        left: 0 !important;
        background-color: white !important;
        z-index: 3 !important; /* Asegura que estas celdas estén sobre otras */
        border-right: 1px solid #000000 !important;
        box-shadow: 2px 0 0 0 #000000 !important;
      }

      .tabla_almacen th:nth-child(2),
      .tabla_almacen td:nth-child(2) {
        position: sticky !important;
        left: 30px !important; /* Ajusta este valor según el ancho de la columna */
        background-color: white !important;
        z-index: 3 !important;
        border-right: 1px solid #000000 !important;
      }

      .tabla_almacen th:nth-child(3),
      .tabla_almacen td:nth-child(3) {
        position: sticky !important;
        left: 80px !important; /* Ajusta este valor según el ancho de la columna */
        background-color: white !important;
        z-index: 3 !important;
        border-right: 1px solid #000000 !important;
      }

      /* Ajuste para la primera columna con rowspan */
      .tabla_almacen th[rowspan="4"],
      .tabla_almacen td[rowspan="2"] {        
        z-index: 4 !important; /* Aumenta el z-index para que no se superponga con otras celdas */
        border: 1px solid #000000 !important;
        /* background-color: #ffd146 !important; */
      }

      /* Manejar las columnas de fechas (4ta a 10ma columna) */
      /* .tabla_almacen th:nth-child(6),
      .tabla_almacen td:nth-child(6) {
        position: sticky !important;
        left: 180px !important; 
        background-color: white !important;
        z-index: 2 !important;
      } */
      .tabla_almacen thead th:nth-child(n+6):nth-child(-n+30),
      .tabla_almacen tbody td:nth-child(n+6):nth-child(-n+30) {
        position: sticky !important;
        left: calc(180px + 60px) !important; /* Ajusta este valor según el ancho total de las columnas fijas */
       background-color: white !important;
        z-index: 2 !important;
        border-right: 1px solid #000000 !important;
      }
    </style>
  </head>

  <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed  pace-orange" idproyecto="<?php echo $_SESSION['idproyecto']; ?>">
    
    <div class="wrapper">
      <!-- Preloader -->
      <!-- <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="../dist/svg/logo-principal.svg" alt="AdminLTELogo" width="360" />
          </div> -->

      <?php
      require 'nav.php';
      require 'aside.php';
      if ($_SESSION['compra_insumos'] == 1) {
        //require 'enmantenimiento.php';
      ?>
        <!--Contenido-->
        <div class="content-wrapper">
        
          <!-- Content Header (Page header) -->
          <div class="content-header">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <h1 class="m-0">
                    <i class="nav-icon fas fa-box-open"></i> Almacén 
                    <!-- <a type="button" class="btn bg-gradient-gray btn-sm" id="btn-export-qs" href="#" onclick="toastr_error('No hay datos!!', 'Seleccione una quincena o semana para exportar.');">
                      <i class="fa-regular fa-file-excel"></i> Export
                    </a>  -->
                  </h1>  
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="compra_insumos.php">Home</a></li>
                    <li class="breadcrumb-item active">Almacén</li>
                  </ol>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
          </div>
          <!-- /.content-header -->

          <!-- Main content -->
          <section class="content">
            <div class="container-fluid">
              <div class="row">
                <!-- :::::::::::::::::: CARD 1 ::::::::::::::::: -->
                <div class="col-12 card-almacen-1">
                  <div class="card card-primary card-outline card-almacen">
                    <!-- Start Main Top -->
                    <div class="main-top">
                      <div class="container-fluid border-bottom">
                        <div class="row">
                          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="card-header">
                              <h3 class="card-title mr-2">
                                <button type="button" class="btn bg-gradient-warning btn-regresar" style="display: none;" onclick="show_hide_tablas(1);">
                                  <i class="fas fa-arrow-left"></i> 
                                </button>
                              </h3>
                              <h3 class="card-title mr-2">
                                <div class="input-group-prepend " >
                                  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Opciones</button>
                                  <div class="dropdown-menu" style="box-shadow: 0px 0rem 2rem 8px rgb(0 0 0 / 64%) !important;">
                                    <button type="button" class="dropdown-item my-2 btn-salida" data-toggle="modal" data-target="#modal-transferencia-uso-proyecto" onclick="limpiar_form_tup();" ><i class="fa-solid fa-arrow-right-to-bracket fa-flip-horizontal"></i> Enviar a uso de Obra</button>
                                    <div class="dropdown-divider"></div>                           
                                    <button type="button" class="dropdown-item my-2" data-toggle="modal" data-target="#modal-transferencia-entre-proyecto" onclick="limpiar_form_tep();" ><i class="fa-solid fa-arrow-right-to-bracket fa-flip-horizontal"></i> Enviar a otro proyecto</button>  
                                    <div class="dropdown-divider"></div>
                                    <button type="button" class="dropdown-item my-2 btn-general"  data-toggle="modal" data-target="#modal-transferencia-almacen-general" onclick="limpiar_form_tag();"><i class="fa-solid fa-arrow-right-to-bracket fa-flip-horizontal"></i> Enviar Almacén General</button>                                             
                                    <div class="dropdown-divider"></div>
                                    <button type="button" class="dropdown-item my-2 btn-general"  onclick="tranferencia_masiva('', '', '');"><i class="fa-solid fa-list-check"></i> Transferencia Masiva</button>                                             
                                  </div>
                                </div>                                                         
                              </h3>
                              <!-- Botones de quincenas -->
                              <div id="lista_quincenas" class="row-horizon disenio-scroll ml-2" >
                                <div class="my-3" ><i class="fas fa-spinner fa-pulse fa-2x"></i>&nbsp;&nbsp;&nbsp;Cargando...</div>
                              </div>                              
                            </div>
                          </div>
                        </div>
                      </div>
                    </div> 
                    <!-- End Main Top -->

                    <!-- /.card-header -->
                    <div class="card-body">
                      <div class="" id="div_tabla_principal">
                        <div class="row">                                        
                          <div class="col-12">
                            <table id="tabla-almacen-resumen" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead> 
                                <tr>
                                  <th class="text-center">#</th>
                                  <th class="">Acciones</th>
                                  <th class="">Código</th>
                                  <th class="">Nombre almacen</th>
                                  <th class="text-center" >UND</th>
                                  <th class="text-center" >Entrada</th>
                                  <th class="text-center" >Salida</th>
                                  <th class="text-center" >Saldo</th>
                                </tr>
                              </thead>
                              <tbody></tbody> 
                              <tfoot>
                                <tr>
                                  <th class="text-center">#</th>
                                  <th class="">Acciones</th>
                                  <th class="">Código</th>
                                  <th class="">Nombre almacen</th>
                                  <th class="text-center" >UND</th>
                                  <th class="text-center" >Entrada</th>
                                  <th class="text-center" >Salida</th>
                                  <th class="text-center" >Saldo</th>
                                </tr>
                              </tfoot>
                            </table>
                          </div>
                          <!-- /.col -->
                        </div>
                      </div>

                      <div id="div_tabla_almacen_search" >
                        <div class="row">
                          <div class="col-4">
                            <div class="form-group" >
                              <div class="input-group input-group-sm mb-3">
                                <input class="form-control form-control-sm" type="text" name="buscar_insumo" id="buscar_insumo" placeholder="Buscar insumo...">
                                <div class="input-group-append cursor-pointer" onclick="buscar_producto();"  data-toggle="tooltip" data-original-title="Click para buscar">
                                  <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>                         
                      </div>

                      
                      
                      <!-- TABLA - Almacen -->                      
                      <div class="table-container" id="div_tabla_almacen" style="display: none;">
                        <form id="form-almacen-saldo-anterior" name="form-almacen-saldo-anterior" method="POST">                        
                          <table class="custom-table tabla_almacen" style="width: 100%;" role="grid">
                            <thead class="st_tr_style bg-white">
                              <tr class="thead-f1">
                                <!-- <th rowspan="4">#</th>
                                <th rowspan="4">Code</th>
                                <th rowspan="4">Producto</th>
                                <th rowspan="4">UND</th>
                                <th rowspan="4">SALDO <br> ANTERIOR</th>
                                <th colspan="6">JUNIO</th>
                                <th colspan="8">JULIO</th>
                                <th rowspan="4">INGRESO /<br> SALIDA</th>
                                <th rowspan="4">SALDO</th> -->
                              </tr>
                              <tr class="thead-f2"> 
                                <!-- <th class="style-head">25</th>
                                <th class="style-head">26</th>
                                <th class="style-head">27</th>
                                <th class="style-head">28</th>
                                <th class="style-head">29</th>
                                <th class="style-head">30</th>
                                <th class="style-head">1</th>
                                <th class="style-head">2</th>
                                <th class="style-head">3</th>
                                <th class="style-head">4</th>
                                <th class="style-head">5</th>
                                <th class="style-head">6</th>
                                <th class="style-head">7</th>
                                <th class="style-head">8</th> -->
                              </tr>

                              <tr class="thead-f3">
                                <!-- <th colspan="7">SEMANA 1</th>
                                <th colspan="7">SEMANA 2</th> -->
                              </tr>
                              <tr class="thead-f4">
                                <!-- <th class="style-head">D</th>
                                <th class="style-head">L</th>
                                <th class="style-head">M</th>
                                <th class="style-head">M</th>
                                <th class="style-head">J</th>
                                <th class="style-head">V</th>
                                <th class="style-head">S</th>
                                
                                <th class="style-head">D</th>
                                <th class="style-head">L</th>
                                <th class="style-head">M</th>
                                <th class="style-head">M</th>
                                <th class="style-head">J</th>
                                <th class="style-head">V</th>
                                <th class="style-head">S</th> -->
                              </tr>                              
                            </thead>
                            <tbody class="data_tbody_almacen">
                              <!-- <tr>
                                <td rowspan="2">1</td>
                                <td rowspan="2">354</td>
                                <td rowspan="2">ACEITE SAE 20W50 X 1GLN</td>
                                <td rowspan="2">UND</td>
                                <td rowspan="2">100</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                              </tr>
                              <tr>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                              </tr> -->
                              <!-- --------- -->
                              <!-- <tr>
                                <td rowspan="2">1</td>
                                <td rowspan="2">354</td>
                                <td rowspan="2">ACEITE SAE 20W50 X 1GLN</td>
                                <td rowspan="2">UND</td>
                                <td rowspan="2">100</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                              </tr>
                              <tr>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                              </tr> -->
                              <!-- ----------- -->
                              <!-- <tr>
                                <td rowspan="2">1</td>
                                <td rowspan="2">354</td>
                                <td rowspan="2">ACEITE SAE 20W50 X 1GLN</td>
                                <td rowspan="2">UND</td>
                                <td rowspan="2">100</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                              </tr>
                              <tr>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                              </tr> -->
                              <!-- Agrega más filas según sea necesario -->
                            </tbody>
                          </table>
                          <button type="submit" style="display: none;" id="submit-form-almacen-sa">Submit</button>
                        </form>
                      </div>                      

                      <!-- CARGANDO -->
                      <div class="row" id="cargando-table-almacen" style="display: none;">   
                        <div class="col-lg-12 text-center"><i class="fas fa-spinner fa-pulse fa-6x"></i><br /><br /><h4>Cargando...</h4></div>                     
                      </div>
                      <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                    
                  </div>
                  <!-- /.card -->
                </div>

                <!-- :::::::::::::::::: CARD 1 ::::::::::::::::: -->
                <div class="col-12 card-almacen-2" style="display: none;">
                  <div class="card card-primary card-outline">
                    <!-- Start Main Top -->
                    <div class="main-top">
                      <div class="container-fluid border-bottom">
                        <div class="row">
                          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="card-header">
                              <h3 class="card-title mr-2">
                                <button type="button" class="btn bg-gradient-warning btn-regresar-2" onclick="show_hide_tablas(1);">
                                  <i class="fas fa-arrow-left"></i> 
                                </button>
                                <button type="button" class="btn bg-gradient-success btn-guardar-tm"  >
                                  <i class="fa-solid fa-floppy-disk"></i> Guardar
                                </button> 
                              </h3>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div> 
                    <!-- End Main Top -->

                    <!-- /.card-header -->
                    <div class="card-body">   
                      <!-- FILTROS NEW -->
                      <div class="row">                          

                        <div class="col-6 col-sm-6 col-md-6 col-lg-4 col-xl-4">                        
                          <div class="form-group">
                            <select id="filtro_tm_es_epp" class="form-control select2" onchange="delay(function(){filtros_tm()}, 50 );" style="width: 100%;"> 
                              <option value="0">Todos</option>
                              <option value="PN">NO EPP</option>
                              <option value="EPP">SI EPP</option>                                    
                            </select>
                          </div> 
                        </div>

                        <div class="col-6 col-sm-6 col-md-6 col-lg-4 col-xl-4">
                          <div class="form-group">                                  
                            <select id="filtro_tm_categoria" class="form-control select2" onchange="delay(function(){filtros_tm()}, 50 );" style="width: 100%;"> 
                              <option value="0">Todos</option>
                              <option value="Ninguno">Ninguno</option>
                              <option value="Boleta">Boleta</option>
                              <option value="Factura">Factura</option>                                    
                              <option value="Nota de Crédito">Nota de Crédito</option>
                              <option value="Nota de venta">Nota de venta</option>
                            </select>
                          </div> 
                        </div>   
                        
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                          <div class="form-group">
                            <select id="filtro_tm_unidad_medida" class="form-control select2" onchange="delay(function(){filtros_tm()}, 50 );" style="width: 100%;"> 
                            </select>
                          </div>
                        </div>
                                
                      </div>  

                      <form id="form-almacen-tm" name="form-almacen-tm" method="POST">
                        <input type="hidden" name="idproyecto_origen_tm" id="idproyecto_origen_tm" value="<?php echo $_SESSION['idproyecto'];?>"/>

                        <div class="row">
                          <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="pl-2" style="position: relative; top: 10px; z-index: +1; letter-spacing: 2px;"><span class="bg-white text-primary" for=""> <b class="mx-2" >PRODUCTOS - AGREGADOS</b>  </span></div>
                          </div>
                          <div class="col-12 col-sm-12 col-md-12 col-lg-12 " >
                            <div class="card px-3 py-3" style="box-shadow: 0 0 1px rgb(0 0 0), 0 1px 3px rgb(0 0 0 / 60%);">                              
                              <div class="row" >                               
                                <!-- Fecha --> 
                                <div class="col-6 col-sm-6 col-md-6 col-lg-3 col-xl-2">
                                  <div class="form-group">                            
                                    <input type="date" name="fecha_tm" class="form-control" id="fecha_tm" placeholder="Fecha" data-toggle="tooltip" data-original-title="Fecha de Transferencia"  value="<?php echo date("Y-m-d"); ?>" />
                                  </div>
                                </div> 
                                <!-- Descripción --> 
                                <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                  <div class="form-group">                              
                                    <textarea name="descripcion_tm" id="descripcion_tm" class="form-control" cols="30" rows="1" placeholder="ejem: Por que se terminó el proyecto." ></textarea>                         
                                  </div>
                                </div>   
                                <!-- Tabla -->
                                <div class="col-12">
                                  <div class="table-responsive" id="html-transferencia-masiva"></div>
                                </div> 
                              </div>
                            </div>                       
                          </div>   
                                                 
                          
                        </div>
                        <button type="submit" style="display: none;" id="submit-form-almacen-tm">Submit</button>
                      </form>                       
                    </div>
                    <!-- /.card -->
                  </div>
                </div>
                <!-- /.row -->
              </div>
              <!-- /.container-fluid -->
            </div>

            <!-- MODAL - TRANSFERENCIA USO DE PROYECTO - chargue 1-2 -->
            <div class="modal fade" id="modal-transferencia-uso-proyecto">
              <div class="modal-dialog modal-dialog-scrollable modal-xl">
                <div class="modal-content">
                  <div class="modal-header"> 
                    <h4 class="modal-title">Enviar a: <span class="font-weight-normal" >Uso de Obra</span> </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span class="text-danger" aria-hidden="true">&times;</span>
                    </button>
                  </div> 

                  <div class="modal-body"> 
                    <!-- form start -->
                    <form id="form-almacen-tup" name="form-almacen-tup" method="POST">
                      <div class="px-2">
                        <div class="row" id="cargando-1-fomulario">
                          
                          <!-- idproyecto_origen_tup -->
                          <input type="hidden" name="idproyecto_origen_tup" id="idproyecto_origen_tup" value="<?php echo $_SESSION['idproyecto'];?>"/>

                          <!-- Producto -->
                          <div class="col-12 col-sm-12 col-md-6 col-lg-8">
                            <div class="form-group">
                              <label for="producto_tup"> 
                                <!-- <span class="badge badge-info cursor-pointer" data-toggle="tooltip" data-original-title="Recargar todos" onclick="reload_producto_todos();"><i class="fa-solid fa-rotate-right"></i></span>  -->
                                <span class="badge badge-warning cursor-pointer" data-toggle="tooltip" data-original-title="Recargar" onclick="reload_producto_tup();"><i class="fa-solid fa-rotate-right"></i></span> 
                                Producto <span class="cargando_producto_tup"></span> 
                              </label>
                              <select name="producto_tup" id="producto_tup" class="form-control" placeholder="Producto" onchange="add_producto_tup(this);">                                
                              </select>
                            </div>
                          </div> 

                          <!-- Fecha --> 
                          <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                            <div class="form-group">
                              <label for="fecha_tup">Fecha</label>
                              <input type="date" name="fecha_tup" class="form-control" id="fecha_tup" placeholder="Fecha" value="<?php echo date("Y-m-d"); ?>" />
                              
                            </div>
                          </div>   

                          <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="pl-2" style="position: relative; top: 10px; z-index: +1; letter-spacing: 2px;"><span class="bg-white text-primary" for=""> <b class="mx-2" >PRODUCTOS - AGREGADOS</b>  </span></div>
                          </div>

                          <div class="col-12 col-sm-12 col-md-12 col-lg-12 " >
                            <div class="card px-3 py-3" style="box-shadow: 0 0 1px rgb(0 0 0), 0 1px 3px rgb(0 0 0 / 60%);">
                              <div class="row titulo-add-producto-tup mt-2" >    
                                <div class="col-6 col-sm-6 col-md-6 col-lg-5 col-xl-5"><label >Nombre Producto</label></div>
                                <div class="col-6 col-sm-6 col-md-6 col-lg-2 col-xl-2"><label >U.M.</label></div>
                                <div class="col-6 col-sm-6 col-md-6 col-lg-2 col-xl-2"><label >Marca </label></div>
                                <div class="col-6 col-sm-6 col-md-6 col-lg-2 col-xl-2"><label >Cantidad</label></div>
                                <div class="col-6 col-sm-6 col-md-6 col-lg-1 col-xl-1"></div>                          
                              </div>
                              <div class="row" id="html_producto_tup" >                               
                                <span> Seleccione un producto</span>
                              </div>
                            </div>                       
                          </div>     

                          <!-- Descripción --> 
                          <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                              <label for="descripcion_tup">Descripción</label>
                              <textarea name="descripcion_tup" id="descripcion_tup" class="form-control" cols="30" rows="2" placeholder="ejem: Por que se terminó el proyecto." ></textarea>                         
                            </div>
                          </div>                                               

                          <!-- barprogress -->
                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;" id="barra_progress_tup_div">
                            <div class="progress">
                              <div id="barra_progress_tup" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                0%
                              </div>
                            </div>
                          </div> 

                        </div>

                        <div class="row" id="cargando-2-fomulario" style="display: none;">
                          <div class="col-lg-12 text-center"><i class="fas fa-spinner fa-pulse fa-6x"></i><br/><br/><h4>Cargando...</h4></div>
                        </div>
                      </div>
                      <!-- /.px-2 -->
                      <button type="submit" style="display: none;" id="submit-form-almacen-tup">Submit</button>
                    </form>
                  </div>
                  <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" onclick="limpiar_form_tup();" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" id="guardar_registro_almacen_tup">Guardar Cambios</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- MODAL - TRANSFERENCIA ENTRE PROYECTO - charge 3-4  -->
            <div class="modal fade" id="modal-transferencia-entre-proyecto">
              <div class="modal-dialog modal-dialog-scrollable modal-xl">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title title_transferencia_entre_proyecto">Transferencia entre Proyecto</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="text-danger" aria-hidden="true">&times;</span></button>
                  </div>
                  <div class="modal-body">     
                    <form id="form-almacen-tep" name="form-almacen-tep" method="POST">
                      <div class="px-2">
                        <div class="row" id="cargando-3-fomulario">

                          <input type="hidden" name="idproyecto_origen_tep" id="idproyecto_origen_tep" value="<?php echo $_SESSION['idproyecto'];?>"/>                          

                          <!-- Poyecto Destino -->
                          <div class="col-12 col-sm-12 col-md-6 col-lg-5">
                            <div class="form-group">
                              <label for="proyecto_tep">                                 
                                <span class="badge badge-warning cursor-pointer" data-toggle="tooltip" data-original-title="Recargar" onclick="reload_proyecto_tep();"><i class="fa-solid fa-rotate-right"></i></span> 
                                Proyecto destino <span class="cargando_proyecto_tep"></span> 
                              </label>
                              <select name="proyecto_tep" id="proyecto_tep" class="form-control" placeholder="Proyecto" onchange="replicar_proyecto_destino();" >                                
                              </select>
                            </div>
                          </div>
                          <!-- Producto -->
                          <div class="col-12 col-sm-12 col-md-6 col-lg-5">
                            <div class="form-group">
                              <label for="producto_tep">                                 
                                <span class="badge badge-warning cursor-pointer" data-toggle="tooltip" data-original-title="Recargar" onclick="reload_producto_tep();"><i class="fa-solid fa-rotate-right"></i></span> 
                                Producto <small>(más EPP)</small> <span class="cargando_producto_tep"></span> 
                              </label>
                              <select name="producto_tep" id="producto_tep" class="form-control" placeholder="Producto" onchange="add_producto_tep(this);">                                
                              </select>
                            </div>
                          </div>
                          <!-- Fecha --> 
                          <div class="col-12 col-sm-12 col-md-6 col-lg-2">
                            <div class="form-group">
                              <label for="fecha_tep">Fecha</label>
                              <input type="date" name="fecha_tep" class="form-control" id="fecha_tep" placeholder="Fecha" value="<?php echo date("Y-m-d"); ?>"  />                          
                            </div>
                          </div>  

                          <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="pl-2" style="position: relative; top: 10px; z-index: +1; letter-spacing: 2px;"><span class="bg-white text-primary" for=""> <b class="mx-2" >PRODUCTOS - AGREGADOS</b>  </span></div>
                          </div>

                          <div class="col-12 col-sm-12 col-md-12 col-lg-12 " >
                            <div class="card px-3 py-3" style="box-shadow: 0 0 1px rgb(0 0 0), 0 1px 3px rgb(0 0 0 / 60%);">
                              <div class="row titulo-add-producto-tep mt-2" >    
                                <div class="col-6 col-sm-6 col-md-6 col-lg-5 col-xl-5"><label >Nombre Producto</label></div>
                                <div class="col-6 col-sm-6 col-md-6 col-lg-2 col-xl-2"><label >U.M.</label></div>
                                <div class="col-6 col-sm-6 col-md-6 col-lg-2 col-xl-2"><label >Marca </label></div>
                                <div class="col-6 col-sm-6 col-md-6 col-lg-2 col-xl-2"><label >Cantidad</label></div>
                                <div class="col-6 col-sm-6 col-md-6 col-lg-1 col-xl-1"></div>                          
                              </div>
                              <div class="row" id="html_producto_tep" >                               
                                <span> Seleccione un producto</span>
                              </div>
                            </div>                       
                          </div>     

                          <!-- Descripción --> 
                          <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                              <label for="descripcion_tep">Descripción</label>
                              <textarea name="descripcion_tep" id="descripcion_tep" class="form-control" cols="30" rows="2" placeholder="ejem: Por que se terminó el proyecto." ></textarea>                         
                            </div>
                          </div>                                              

                          <!-- barprogress -->
                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;" id="barra_progress_tep_div">
                            <div class="progress">
                              <div id="barra_progress_tep" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                0%
                              </div>
                            </div>
                          </div>  
                        </div>
                        <div class="row" id="cargando-4-fomulario" style="display: none;">
                          <div class="col-lg-12 text-center"><i class="fas fa-spinner fa-pulse fa-6x"></i><br/><br/><h4>Cargando...</h4></div>
                        </div>
                      </div>
                      <!-- /.px-2 -->
                      <button type="submit" style="display: none;" id="submit-form-almacen-tep">Submit</button>
                    </form> 
                    <!-- /.form -->                    
                  </div>
                  <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_form_tep();">Close</button>        
                    <button type="submit" class="btn btn-success" id="guardar_registro_almacen_tep">Guardar Cambios</button>            
                  </div>
                </div>
              </div>
            </div>

            <!-- MODAL - TRANSFERENCIA A ALMACEN GENERAL - chargue 5-6 -->
            <div class="modal fade" id="modal-transferencia-almacen-general">
              <div class="modal-dialog modal-dialog-scrollable modal-xl">
                <div class="modal-content">
                  <div class="modal-header"> 
                    <h4 class="modal-title">Enviar a: <span class="font-weight-normal" >Almacén general</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span class="text-danger" aria-hidden="true">&times;</span>
                    </button>
                  </div> 

                  <div class="modal-body"> 
                    <!-- form start -->
                    <form id="form-almacen-tag" name="form-almacen-tag" method="POST">
                      <div class="px-2">
                        <div class="row" id="cargando-5-fomulario">
                          <input type="hidden" name="idproyecto_origen_tag" id="idproyecto_origen_tag" value="<?php echo $_SESSION['idproyecto'];?>"/>

                          <!-- Tipo de documento --> 
                          <div class="col-12 col-sm-12 col-md-6 col-lg-8">
                            <div class="form-group">
                              <label for="producto_tag">                                 
                                <span class="badge badge-warning cursor-pointer" data-toggle="tooltip" data-original-title="Recargar comprados" onclick="reload_producto_tag();"><i class="fa-solid fa-rotate-right"></i></span> 
                                Producto <small >(mas EPP)</small> <span class="cargando_productos_tag"></span> 
                              </label>
                              <select name="producto_tag" id="producto_tag" class="form-control" placeholder="Producto" onchange="add_producto_tag(this);">                                
                              </select>
                            </div>
                          </div> 

                          <!-- Correo electronico --> 
                          <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                            <div class="form-group">
                              <label for="fecha_tag">Fecha</label>
                              <input type="date" name="fecha_tag" class="form-control" id="fecha_tag" placeholder="Fecha" value="<?php echo date("Y-m-d"); ?>" />
                            </div>
                          </div>   

                          <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="pl-2" style="position: relative; top: 10px; z-index: +1; letter-spacing: 2px;"><span class="bg-white text-primary" for=""> <b class="mx-2" >PRODUCTOS - AGREGADOS</b>  </span></div>
                          </div>

                          <div class="col-12 col-sm-12 col-md-12 col-lg-12 " >
                            <div class="card px-3 py-3" style="box-shadow: 0 0 1px rgb(0 0 0), 0 1px 3px rgb(0 0 0 / 60%);">
                              <div class="row titulo-add-producto-tag mt-2" >    
                                <div class="col-6 col-sm-6 col-md-6 col-lg-5 col-xl-5"><label >Nombre Producto</label></div>
                                <div class="col-6 col-sm-6 col-md-6 col-lg-2 col-xl-2"><label >U.M.</label></div>
                                <div class="col-6 col-sm-6 col-md-6 col-lg-2 col-xl-2"><label >Almacen </label></div>
                                <div class="col-6 col-sm-6 col-md-6 col-lg-2 col-xl-2"><label >Cantidad</label></div>
                                <div class="col-6 col-sm-6 col-md-6 col-lg-1 col-xl-1"></div>                          
                              </div>
                              <div class="row" id="html_producto_tag" >                               
                                <span> Seleccione un producto</span>
                              </div>
                            </div>                       
                          </div>     

                          <!-- Descripción --> 
                          <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                              <label for="descripcion_tag">Descripción</label>
                              <textarea name="descripcion_tag" id="descripcion_tag" class="form-control" cols="30" rows="2" placeholder="ejem: Por que se terminó el proyecto." ></textarea>                         
                            </div>
                          </div>                                                      

                          <!-- barprogress -->
                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;" id="barra_progress_tag_div">
                            <div class="progress">
                              <div id="barra_progress_tag" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                0%
                              </div>
                            </div>
                          </div> 

                        </div>

                        <div class="row" id="cargando-6-fomulario" style="display: none;">
                          <div class="col-lg-12 text-center"><i class="fas fa-spinner fa-pulse fa-6x"></i><br/><br/><h4>Cargando...</h4></div>
                        </div>
                      </div>
                      <!-- /.px-2 -->
                      <button type="submit" style="display: none;" id="submit-form-almacen-tag">Submit</button>
                    </form>
                  </div>
                  <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" onclick="limpiar_form_tag();" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" id="guardar_registro_almacen_tag">Guardar Cambios</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- MODAL - VER ALMACEN X DIA - chargue 7-8 -->
            <div class="modal fade" id="modal-ver-almacen">
              <div class="modal-dialog modal-dialog-scrollable modal-xl">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">
                      <button type="button" class="btn bg-gradient-warning btn-regresar-x-dia" onclick="show_hide_form_x_dia(1);" style="display: none;" >
                        <i class="fa-solid fa-arrow-left"></i> Regresar
                      </button>
                      Ver almacen x dia</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span class="text-danger" aria-hidden="true">&times;</span>
                    </button>
                  </div>

                  <div class="modal-body">
                    
                    <div class="div-tabla-ver-almacen-x-dia">
                      <table id="tabla-ver-almacen" class="table table-bordered table-striped display" style="width: 100% !important;">
                        <thead>
                          <tr>
                            <th class="text-center">#</th> 
                            <!-- <th class="">Aciones</th> -->
                            <th>Fecha</th>                             
                            <th>Movimiento</th>                                 
                            <th>Destino</th>
                            <th>Cant</th>  
                            <th>Descripcion</th>                                                    
                          </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                          <tr>
                            <th class="text-center">#</th> 
                            <!-- <th class="">Aciones</th> -->
                            <th>Fecha</th>                             
                            <th>Movimiento</th>                                 
                            <th>Destino</th>
                            <th>Cant</th>  
                            <th>Descripcion</th>              
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                    
                    <!-- form start -->
                    <form id="form-almacen-x-dia" name="form-almacen-x-dia" method="POST" style="display: none;">
                      <div class="card-body">
                        <div class="row" id="cargando-7-fomulario">
                          <!-- id  -->
                          <input type="hidden" name="idalmacen_salida_xp" id="idalmacen_salida_xp" />
                          <input type="hidden" name="idalmacen_resumen_xp" id="idalmacen_resumen_xp" />
                          <input type="hidden" name="idproyecto_xp" id="idproyecto_xp" />

                          <!-- Producto -->
                          <div class="col-12 col-sm-12 col-md-6 col-lg-12">
                            <div class="form-group">
                              <label for="producto_xp">Producto <span class="cargando_productos"></span> </label>
                              <select name="producto_xp" id="producto_xp" class="form-control" placeholder="Producto" onchange="cambiar_producto_salida(this);" >                                
                              </select>
                            </div>
                          </div>                            

                          <!-- Fecha -->
                          <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                            <div class="form-group">
                              <label for="fecha_ingreso_xp">Fecha</label>
                              <input type="date" name="fecha_ingreso_xp" id="fecha_ingreso_xp" class="form-control"  placeholder="Fecha"  onchange="obtener_dia_ingreso(this);" />
                              <input type="hidden" name="dia_ingreso_xp" id="dia_ingreso_xp" />
                            </div>
                          </div>   

                          <!-- Marcas -->
                          <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                            <div class="form-group">
                              <label for="marca_xp">Marcas <small class="chargue_edit_marca"></small> </label>
                              <select name="marca_xp" id="marca_xp" class="form-control" ></select>                             
                            </div>
                          </div>  

                          <!-- Cantidad -->
                          <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                            <div class="form-group">
                              <label for="cantidad_xp">Cantidad</label>
                              <input type="number" name="cantidad_xp" class="form-control" id="cantidad_xp" placeholder="Cantidad" />                              
                            </div>
                          </div>                                                                       

                          <!-- barprogress -->
                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;" id="barra_progress_almacen_x_dia_div">
                            <div class="progress">
                              <div id="barra_progress_almacen_x_dia" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                0%
                              </div>
                            </div>
                          </div> 

                        </div>

                        <div class="row" id="cargando-8-fomulario" style="display: none;">
                          <div class="col-lg-12 text-center"><i class="fas fa-spinner fa-pulse fa-6x"></i><br/><br/><h4>Cargando...</h4></div>
                        </div>
                      </div>
                      <!-- /.card-body -->
                      <button type="submit" style="display: none;" id="submit-form-almacen-x-dia">Submit</button>
                    </form>
                  </div>
                  <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" onclick="limpiar_form_almacen_x_dia();" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" id="guardar_registro_almacen_x_dia" style="display: none;">Guardar Cambios</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- MODAL - VER SALDOS ANTERIORES -  -->
            <div class="modal fade" id="modal-saldo-anterior">
              <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title title_saldo_anterior">Saldos:</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span class="text-danger" aria-hidden="true">&times;</span>
                    </button>
                  </div>

                  <div class="modal-body">                    
                    
                    <table id="tabla-saldo-anterior" class="table table-bordered table-striped display" style="width: 100% !important;">
                      <thead>
                        <tr>
                          <th class="text-center">#</th>
                          <th class="">Producto</th>
                          <th>Entrada</th>                                
                          <th>Salida</th> 
                          <th>Saldo</th>                                                       
                        </tr>
                      </thead>
                      <tbody></tbody>
                      <tfoot>
                        <tr>
                          <th class="text-center">#</th>
                          <th class="">Producto</th>
                          <th>Entrada</th>                                
                          <th>Salida</th>
                          <th>Marca</th>                             
                        </tr>
                      </tfoot>
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
        <!--Fin-Contenido-->

      <?php
      } else {
        require 'noacceso.php';
      }
      require 'footer.php';
      ?>
    </div>

    <?php require 'script.php'; ?>

    <!-- table export EXCEL -->
    <script src="../plugins/export-xlsx/xlsx.full.min.js"></script>
    <script src="../plugins/export-xlsx/FileSaver.min.js"></script>
    <script src="../plugins/export-xlsx/tableexport.min.js"></script>

    <!-- ZIP -->
    <script src="../plugins/jszip/jszip.js"></script>
    <script src="../plugins/jszip/dist/jszip-utils.js"></script>
    <script src="../plugins/FileSaver/dist/FileSaver.js"></script>

    <script type="text/javascript" src="scripts/almacen.js?version_jdl=2.01"></script>
    <!-- <script type="text/javascript" src="scripts/js_compra_insumo_repetido.js"></script> -->

    <script>
      $(function() {
        $('[data-toggle="tooltip"]').tooltip();          

      });
    </script>

  </body>

  </html>
<?php
}

ob_end_flush();
?>