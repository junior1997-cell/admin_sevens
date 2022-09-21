<?php
  //Activamos el almacenamiento en el buffer
  ob_start();
  session_start();

  if (!isset($_SESSION["nombre"])){
    header("Location: index.php?file=".basename($_SERVER['PHP_SELF']));
  }else{ ?>
    <!DOCTYPE html>
    <html lang="es">
      <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Escritorio | Admin Sevens</title>

        <?php $title = "Escritorio"; require 'head.php'; ?>
     
        <link rel="stylesheet" href="../dist/css/switch_domingo.css">
      </head>
      <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed ">
        
        <div class="wrapper">
          <!-- Preloader -->
          <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="../dist/svg/logo-principal.svg" alt="AdminLTELogo" width="360" />
          </div>
        
          <?php
            require 'nav.php';
            require 'aside.php';
            if ($_SESSION['escritorio']==1){
              //require 'enmantenimiento.php';
              ?>           
              <!--Contenido-->
              <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <div class="content-header">
                  <div class="container-fluid">
                    <div class="row mb-2">
                      <div class="col-sm-6">
                        <h1 class="m-0">Tablero</h1>
                      </div>
                      <!-- /.col -->
                      <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                          <li class="breadcrumb-item"><a href="escritorio.php">Home</a></li>
                          <li class="breadcrumb-item active">Tablero</li>
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
                    <!-- Small boxes (Stat box) -->
                    <div class="row">

                      <!-- CANIDAD DE PROYECTOS -->
                      <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                          <div class="inner">
                            <h3 id="cantidad_proyectos" > <i class="fas fa-spinner fa-pulse "></i> </h3>

                            <p>Total de Proyectos</p>
                          </div>
                          <div class="icon">
                            <i class="fas fa-th"></i>
                          </div>
                          <a href="#" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                      </div>

                      <!-- CANTIDAD DE PROVEEDORES -->
                      <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                          <div class="inner">
                            <h3 id="cantidad_proveedores"> <i class="fas fa-spinner fa-pulse "></i> </h3>
                            <p>Total de Proveedores</p>
                          </div>
                          <div class="icon">
                            <i class="fas fa-truck"></i>
                          </div>
                          <a href="all_proveedor.php" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                      </div>

                      <!-- CANTIDAD DE TRABAJADORES -->
                      <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                          <div class="inner">
                            <h3 id="cantidad_trabajadores"> <i class="fas fa-spinner fa-pulse "></i> </h3>

                            <p>Total de Trabajadores</p>
                          </div>
                          <div class="icon">
                            <i class="fas fa-users"></i>
                          </div>
                          <a href="all_trabajador.php" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                      </div>

                      <!-- CANTIDAD DE SERVICIOS -->
                      <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                          <div class="inner">
                            <h3 id="cantidad_compra"> <i class="fas fa-spinner fa-pulse "></i> </h3>

                            <p>Total de Compras de insumos</p>
                          </div>
                          <div class="icon">
                            <i class="fas fa-shopping-cart"></i>
                          </div>
                          <a href="compra_insumos.php" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                      </div>

                    </div>
                    <!-- /.row -->
                  </div>
                  <!-- /.container-fluid -->
                </section>
                <!-- /.content -->

                <!-- Main content -->
                <section class="content">
                  <div class="container-fluid">
                    <div class="row">
                      <div class="col-12">
                        <div class="card card-primary card-outline">
                          <div class="card-header">
                            <h2 class="card-title " >
                              <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-proyecto" onclick="limpiar();">
                              <i class="fas fa-plus-circle"></i> Agregar
                              </button>
                              Proyectos no empezados o en proceso                      
                            </h2>                      
                          </div>
                          <!-- /.card-header -->
                          <div class="card-body">

                            <div class="row mb-3">
                              <div class="col-md-3 col-sm-6 col-12">
                                <div class="info-box cursor-pointer box-proceso" onclick="delay(function(){tbla_principal(1, 'shadow-0px-05rem-1rem-rgb-255-193-7', '.box-proceso')}, 100 );" data-toggle="tooltip" data-original-title="Click para ver">
                                  <span class="info-box-icon bg-warning "><i class="fas fa-hourglass-half"></i></span>

                                  <div class="info-box-content">
                                    <span class="info-box-text">Proyectos</span>
                                    <span class="info-box-number">(<span class="cant_proceso"></span>) EN PROCESO</span>
                                  </div>
                                  <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                              </div>
                              <!-- /.col -->

                              <div class="col-md-3 col-sm-6 col-12">
                                <div class="info-box cursor-pointer box-no-empezado" onclick="delay(function(){tbla_principal(2, 'shadow-0px-05rem-1rem-rgb-220-53-69', '.box-no-empezado')}, 100 );" data-toggle="tooltip" data-original-title="Click para ver">
                                  <span class="info-box-icon bg-danger"><i class="fas fa-hourglass-start"></i></span>

                                  <div class="info-box-content">
                                    <span class="info-box-text">Proyectos</span>
                                    <span class="info-box-number">(<span class="cant_no_emmpezado"></span>) NO EMPEZADOS</span>
                                  </div>
                                  <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                              </div>
                              <!-- /.col -->

                              <div class="col-md-3 col-sm-6 col-12">
                                <div class="info-box cursor-pointer box-terminado" onclick="delay(function(){tbla_principal(0, 'shadow-0px-05rem-1rem-rgb-40-167-69', '.box-terminado')}, 100 );" data-toggle="tooltip" data-original-title="Click para ver">
                                  <span class="info-box-icon bg-success"><i class="fas fa-hourglass-end"></i></span>

                                  <div class="info-box-content">
                                    <span class="info-box-text">Proyectos</span>
                                    <span class="info-box-number">(<span class="cant_teminado"></span>) TERMINADOS</span>
                                  </div>
                                  <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                              </div>
                              <!-- /.col -->

                              <div class="col-md-3 col-sm-6 col-12">
                                <div class="info-box cursor-pointer box-todos" onclick="delay(function(){tbla_principal(3, 'shadow-0px-05rem-1rem-rgb-23-162-184', '.box-todos')}, 100 );" data-toggle="tooltip" data-original-title="Click para ver">
                                  <span class="info-box-icon bg-info"><i class="fas fa-tasks"></i></span>

                                  <div class="info-box-content">
                                    <span class="info-box-text">Proyectos</span>
                                    <span class="info-box-number">(<span class="cant_todos"></span>) TODOS</span>
                                  </div>
                                  <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                              </div>
                              <!-- /.col -->
                            </div>                            
                            
                            <table id="tabla-proyectos" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th>#</th>
                                  <th class="">Aciones</th>
                                  <th>Empresa</th>
                                  <th>Código proyecto</th>
                                  <th>Ubicación</th>                              
                                  <th>Costo</th>
                                  <th>Empresa</th>
                                  <th>Documento</th>
                                  <th>Ubicación</th>
                                  <th>Fecha Inicio</th>
                                  <th>Fecha Fin</th>
                                  <th>Plazo</th>
                                  <th>Dias Hábiles</th>
                                  <th>Valorizacion</th>
                                  <th>Pago Obrero</th>
                                  <th>Permanente</th>
                                  <th>contractual</th>
                                  <th>Estado</th>
                                </tr>
                              </thead>
                              <tbody>                         
                                
                              </tbody>
                              <tfoot>
                                <tr>
                                  <th>#</th>
                                  <th class="">Aciones</th>
                                  <th>Empresa</th>
                                  <th>Código proyecto</th>
                                  <th>Ubicación</th>                              
                                  <th>Costo</th>
                                  <th>Empresa</th>
                                  <th>Documento</th>
                                  <th>Ubicación</th>
                                  <th>Fecha Inicio</th>
                                  <th>Fecha Fin</th>
                                  <th>Plazo</th>
                                  <th>Dias Hábiles</th>
                                  <th>Valorizacion</th>
                                  <th>Pago Obrero</th>
                                  <th>Permanente</th>
                                  <th>contractual</th>
                                  <th>Estado</th>
                                </tr>
                              </tfoot>
                            </table>

                            <div class="mt-4 card-danger card-outline hidden">
                              <h1 style="text-align: center;background-color: aliceblue;">Proyectos Terminados</h1>
                              <table id="tabla-proyectos-terminados" class="table table-bordered table-striped display" style="width: 100% !important;">
                                <thead>
                                  <tr>
                                    <th>#</th>
                                    <th class="">Aciones</th>
                                    <th>Empresa</th>
                                    <th>Código proyecto</th>
                                    <th>Ubicación</th>                              
                                    <th>Costo</th>
                                    <th>Empresa</th>
                                    <th>Documento</th>
                                    <th>Ubicación</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Plazo</th>
                                    <th>Dias Hábiles</th>
                                    <th>Valorizacion</th>
                                    <th>Pago Obrero</th>
                                    <th>Permanente</th>
                                    <th>contractual</th>
                                    <th>Estado</th>
                                  </tr>
                                </thead>
                                <tbody>                         
                                  
                                </tbody>
                                <tfoot>
                                  <tr>
                                    <th>#</th>
                                    <th class="">Aciones</th>
                                    <th>Empresa</th>
                                    <th>Código proyecto</th>
                                    <th>Ubicación</th>                              
                                    <th>Costo</th>
                                    <th>Empresa</th>
                                    <th>Documento</th>
                                    <th>Ubicación</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Plazo</th>
                                    <th>Dias Hábiles</th>
                                    <th>Valorizacion</th>
                                    <th>Pago Obrero</th>
                                    <th>Permanente</th>
                                    <th>contractual</th>
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

                  <!-- Modal agregar proyecto -->
                  <div class="modal fade" id="modal-agregar-proyecto">
                    <div class="modal-dialog modal-dialog-scrollable modal-xl">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Agregar proyecto</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        
                        <div class="modal-body">
                          <!-- form start -->
                          <form id="form-proyecto" name="form-proyecto"  method="POST" >                      
                            <div class="card-body">
                              <div class="row" id="cargando-1-fomulario">
                                <!-- id proyecto -->
                                <input type="hidden" name="idproyecto" id="idproyecto" />

                                <!-- Tipo de documento -->
                                <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                                  <div class="form-group">
                                    <label for="tipo_documento">Tipo de documento <sup class="text-danger">*</sup> </label>
                                    <select name="tipo_documento" id="tipo_documento" class="form-control"  placeholder="Tipo de documento">
                                      <option selected value="DNI">DNI</option>
                                      <option value="RUC">RUC</option>
                                      <option value="CEDULA">CEDULA</option>
                                      <option value="OTRO">OTRO</option>
                                    </select>
                                  </div>
                                </div>

                                <!-- N° de documento -->
                                <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                                  <div class="form-group">
                                    <label for="num_documento">N° de documento <sup class="text-danger">*</sup></label>
                                    <div class="input-group">
                                      <input type="number" name="num_documento" id="num_documento" class="form-control" placeholder="N° de documento" />
                                      <div class="input-group-append" data-toggle="tooltip" data-original-title="Buscar Reniec/SUNAT" onclick="buscar_sunat_reniec();">
                                        <span class="input-group-text" style="cursor: pointer;">
                                          <i class="fas fa-search text-primary" id="search"></i>
                                          <i class="fa fa-spinner fa-pulse fa-fw fa-lg text-primary" id="charge" style="display: none;"></i>
                                        </span>
                                      </div>
                                    </div>
                                  </div>
                                </div>

                                <!-- Empresa -->
                                <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                                  <div class="form-group">
                                    <label for="empresa">Empresa <sup class="text-danger">*</sup> <small class="text-orange">(para quien va la obra)</small> </label>                               
                                    <input type="text" name="empresa" id="empresa" class="form-control"  placeholder="Empresa">  
                                  </div>                                                        
                                </div>

                                <!-- Nombre del proyecto -->
                                <div class="col-12 col-sm-12 col-md-6 col-lg-8">
                                  <div class="form-group">
                                    <label for="nombre_proyecto">Nombre del proyecto <sup class="text-danger">*</sup></label>
                                    <textarea name="nombre_proyecto" id="nombre_proyecto" class="form-control" rows="3" placeholder="Ingresa nombre">
                                    </textarea>
                                  </div>                                                        
                                </div>

                                <!-- Ubicación (de la obra) -->
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                  <div class="form-group">
                                    <label for="ubicacion">Ubicación <small class="text-orange"> (de la obra) </small> </label>                               
                                    <!-- <input type="text" name="ubicacion" id="ubicacion" class="form-control"  placeholder="Ubicación">  -->
                                    <textarea name="ubicacion" id="ubicacion" class="form-control" rows="3" placeholder="Ubicación">
                                    </textarea>
                                  </div>                                                        
                                </div>

                                <!-- Codigo del proyecto -->
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                  <div class="form-group">
                                    <label for="nombre_codigo">Código del proyecto</label>
                                    <input type="text" name="nombre_codigo" id="nombre_codigo" class="form-control"  placeholder="Codigo proyecto">
                                  </div>                                                        
                                </div>                           

                                <!-- Actividad del trabajo -->
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                  <div class="form-group">
                                    <label for="actividad_trabajo">Actividad del trabajo </label>
                                    <input type="text" name="actividad_trabajo" id="actividad_trabajo" class="form-control" placeholder="Actividad del trabajo">
                                  </div>
                                </div>  
                                
                                <!-- FECHA INICIO DE ACTIVIDADES -->
                                <!-- <div class="col-lg-4">
                                  <div class="form-group">
                                    <label>Fecha Inicio de actividades: <sup class="text-danger">*</sup></label>
                                    <div class="input-group date"  data-target-input="nearest">
                                      <input type="text" class="form-control datetimepicker-input" data-target="#fecha_inicio_actividad" id="fecha_inicio_actividad" name="fecha_inicio_actividad" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask onchange="calcular_plazo_actividad();"  />
                                      <div class="input-group-append" data-target="#fecha_inicio_actividad" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                      </div>
                                    </div>                                 
                                  </div>
                                </div> -->
                                
                                <!-- FECHA INICIO FIN DE ACTIVIDADES -->
                                <!-- <div class="col-lg-4">
                                  <div class="form-group">
                                    <label>Fecha Fin de actividades: <sup class="text-danger">*</sup></label>
                                    <div class="input-group date"  data-target-input="nearest">
                                      <input type="text" class="form-control datetimepicker-input" data-target="#fecha_fin_actividad" id="fecha_fin_actividad" name="fecha_fin_actividad" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask onchange="calcular_plazo_actividad();" />
                                      <div class="input-group-append" data-target="#fecha_fin_actividad" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                      </div>
                                    </div>                                 
                                  </div>
                                </div> -->

                                <!-- Dias habiles -->
                                <!-- <div class="col-lg-4">
                                  <div class="form-group">
                                    <label for="plazo_actividad">Plazo Actividades<sup class="text-danger">*</sup> <small class="text-orange">(días hábiles)</small> </label>
                                    <span class="form-control plazo_actividad"> 0 </span>
                                    <input type="hidden" name="plazo_actividad" id="plazo_actividad" >
                                  </div>
                                </div> -->

                                <!-- linea divisoria -->
                                <!-- <div class="col-lg-12 borde-arriba-naranja mt-2 mb-2"> </div> -->
                                
                                <!-- FECHA INICIO -->
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                  <div class="form-group">
                                    <label>Fecha Inicio: <sup class="text-danger">*</sup></label>
                                    <div class="input-group date"  data-target-input="nearest">
                                      <input type="text" class="form-control " id="fecha_inicio" name="fecha_inicio" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask onchange="calcular_plazo_fechafin()" autocomplete="off" />
                                      <div class="input-group-append cursor-pointer click-btn-fecha-inicio">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                      </div>
                                    </div>                                 
                                  </div>
                                </div>

                                <!-- Dias habiles -->
                                <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                                  <div class="form-group">
                                    <label for="dias_habiles">Plazo <sup class="text-danger">*</sup> <small class="text-orange">(días hábiles)</small> </label>
                                    <input type="number" name="dias_habiles" id="dias_habiles" class="form-control" min="0"  placeholder="Días habiles" onchange="calcular_plazo_fechafin()" onkeyup="calcular_plazo_fechafin()" >
                                  </div>
                                </div>

                                <!-- Plazo -->
                                <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                                  <div class="form-group">
                                    <label for="plazo">Plazo <sup class="text-danger">*</sup> <small class="text-orange">(días calendario)</small></label>
                                    <input type="number" name="plazo" id="plazo" class="form-control" placeholder="Días calendario" readonly >
                                  </div>
                                </div>
                                
                                <!-- FECHA FIN -->
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                  <div class="form-group">
                                    <label>Fecha fin: <sup class="text-danger">*</sup></label>
                                    <div class="input-group date"  data-target-input="nearest">
                                      <input type="text" class="form-control" id="fecha_fin" name="fecha_fin" readonly  data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask/>
                                      <div class="input-group-append">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                      </div>
                                    </div>                                 
                                  </div>
                                </div>

                                <!-- Costo total del proyecto -->
                                <div class="col-12 col-sm-12 col-md-6 col-lg-3 pr-lg-0">
                                  <div class="form-group">
                                    <label for="costo">Costo <small class="text-orange d-none d-lg-inline-block">("costo total del proyecto")</small></label>
                                    <div class="input-group mb-3">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text">S/. </span>
                                      </div>
                                      <input type="text"  name="costo" id="costo" class="form-control"  placeholder="Costo" onkeyup="formato_miles_input('#costo')" >
                                    </div>
                                  </div>
                                </div> 

                                <!-- Costo total del proyecto -->
                                <div class="col-12 col-sm-12 col-md-6 col-lg-1 pl-lg-0">
                                  <div class="form-group">
                                    <label for="garantia"><small class="text-orange">(Garantia)</small></label>
                                    <input type="text"  name="garantia" id="garantia" class="form-control"  placeholder="%"  >                                    
                                  </div>
                                </div>
                                
                                <!-- Empresa a cargo -->
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                  <div class="form-group">
                                    <label for="empresa_acargo">Empresa a cargo <small class="text-orange d-none d-lg-inline-block">("Seven's Ingenieros")</small></label>
                                    <select class="form-control select2 select2-purple" name="empresa_acargo" id="empresa_acargo" data-dropdown-css-class="select2-purple" style="width: 100%;">
                                      <!-- <option selected value="Seven's Ingenieros SAC" title="logo-icono.svg">Seven's Ingenieros SAC</option> -->
                                      <!-- <option value="Consorcio Seven's Ingenieros SAC" title="logo-icono-plomo.svg">Consorcio Seven's Ingenieros SAC</option> -->
                                      <!-- <option value="Ninguno" title="emogi-carita-feliz.svg">Ninguno</option> -->
                                    </select>                                     
                                  </div>
                                </div>

                                <!-- fechas de valorizacion -->
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4">                               
                                  <div class="form-group">
                                    <label for="fecha_valorizacion">Valorizacion <sup class="text-danger">*</sup></label>
                                    <select class="form-control select2" name="fecha_valorizacion" id="fecha_valorizacion" style="width: 100%;">
                                      <option selected value="quincenal">Quincenal</option>
                                      <option value="mensual">Mensual</option>
                                      <option value="al finalizar">Al finalizar</option>
                                    </select>
                                  </div>
                                </div>

                                <!-- fechas de pago de obreros -->
                                <div class="col-8 col-sm-7 col-md-6 col-lg-4">                               
                                  <div class="form-group show_hide_select_1">
                                    <label for="fecha_pago_obrero">Pago de obreros <sup class="text-danger">*</sup></label>
                                    <select class="form-control select2" name="fecha_pago_obrero" id="fecha_pago_obrero" style="width: 100%;" onchange="validar_permanent();">
                                      <option value="quincenal">Quincenal</option>
                                      <option value="semanal">Semanal</option>
                                    </select>                                
                                  </div>
                                  <div class="form-group show_hide_select_2" style="display: none !important;">
                                    
                                  </div>
                                </div>

                                <!-- Swichs permanente -->
                                <div class="col-4 col-sm-5 col-md-6 col-lg-4">
                                  <label for="fecha_pago_obrero" class="d-none show-min-width-576px">Definir permanente <small class="text-danger">(pago de obreros)</small> </label>
                                  <label for="fecha_pago_obrero" class="d-none show-max-width-576px"><small class="text-danger">(Permanente)</small> </label>
                                  <div class="switch-toggle show_hide_switch_1">
                                    <input type="checkbox" id="definiendo" >
                                    <label for="definiendo" onclick="permanente_pago_obrero()"></label>
                                  </div>
                                  <div class="show_hide_switch_2" style="display: none;">
                                    Esta no se configuracion <b class="text-danger">NO se puede cambiar</b>
                                  </div>
                                  <input type="hidden" name="permanente_pago_obrero" id="permanente_pago_obrero"  >
                                </div>

                                <!-- Pdf 1 -->
                                <div class="col-12 col-sm-6 col-md-6 col-lg-4" >                               
                                  <div class="row text-center">
                                    <div class="col-md-12" style="padding-top: 15px; padding-bottom: 5px;">
                                      <label for="cip" class="control-label" > Contrato de obra </label>
                                    </div>
                                    <div class="col-6 col-md-6 col-lg-6 col-xl-6 text-center">
                                      <button type="button" class="btn btn-success btn-block btn-xs" id="doc1_i">
                                        <i class="fas fa-file-upload"></i> Subir.
                                      </button>
                                      <input type="hidden" id="doc_old_1" name="doc_old_1" />
                                      <input style="display: none;" id="doc1" type="file" name="doc1" accept=".pdf, .doc, .docx, .csv, .xls, .xlsx, .xlsm" class="docpdf" /> 
                                    </div>
                                    <div class="col-6 col-md-6 col-lg-6 col-xl-6 text-center">
                                      <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(1);">
                                        <i class="fa fa-eye"></i> PDF.
                                      </button>
                                    </div>
                                  </div>                              
                                  <div id="doc1_ver" class="text-center mt-4">
                                    <img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >
                                  </div>
                                  <div class="text-center" id="doc1_nombre"><!-- aqui va el nombre del pdf --></div>

                                  <!-- linea divisoria -->
                                  <div class="col-lg-12 borde-arriba-naranja mt-2"> </div>
                                </div> 

                                <!-- Pdf 2 -->
                                <div class="col-12 col-sm-6 col-md-6 col-lg-4" >                               
                                  <div class="row text-center">
                                    <div class="col-md-12" style="padding-top: 15px; padding-bottom: 5px;">
                                      <label for="cip" class="control-label" > Acta de entrega de terreno</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-lg-6 col-xl-6 text-center">
                                      <button type="button" class="btn btn-success btn-block btn-xs" id="doc2_i">
                                        <i class="fas fa-file-upload"></i> Subir.
                                      </button>
                                      <input type="hidden" id="doc_old_2" name="doc_old_2" />
                                      <input style="display: none;" id="doc2" type="file" name="doc2" accept=".pdf, .doc, .docx, .csv, .xls, .xlsx, .xlsm" class="docpdf" /> 
                                    </div>
                                    <div class="col-6 col-md-6 col-lg-6 col-xl-6 text-center">
                                      <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(2);">
                                        <i class="fa fa-eye"></i> PDF.
                                      </button>
                                    </div>
                                  </div>                              
                                  <div id="doc2_ver" class="text-center mt-4">
                                    <img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >
                                  </div>
                                  <div class="text-center" id="doc2_nombre"><!-- aqui va el nombre del pdf --></div>

                                  <!-- linea divisoria -->
                                  <div class="col-lg-12 borde-arriba-naranja mt-2"> </div>
                                </div>
                                
                                <!-- Pdf 3 -->
                                <div class="col-12 col-sm-6 col-md-6 col-lg-4" >                               
                                  <div class="row text-center">
                                    <div class="col-md-12" style="padding-top: 15px; padding-bottom: 5px;">
                                      <label for="cip" class="control-label" > Acta de inicio de obra</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-lg-6 col-xl-6 text-center">
                                      <button type="button" class="btn btn-success btn-block btn-xs" id="doc3_i">
                                        <i class="fas fa-file-upload"></i> Subir.
                                      </button>
                                      <input type="hidden" id="doc_old_3" name="doc_old_3" />
                                      <input style="display: none;" id="doc3" type="file" name="doc3" accept=".pdf, .doc, .docx, .csv, .xls, .xlsx, .xlsm" class="docpdf" /> 
                                    </div>
                                    <div class="col-6 col-md-6 col-lg-6 col-xl-6 text-center">
                                      <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(3);">
                                        <i class="fa fa-eye"></i> PDF.
                                      </button>
                                    </div>
                                  </div>                              
                                  <div id="doc3_ver" class="text-center mt-4">
                                    <img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >
                                  </div>
                                  <div class="text-center" id="doc3_nombre"><!-- aqui va el nombre del pdf --></div>

                                  <!-- linea divisoria -->
                                  <div class="col-lg-12 borde-arriba-naranja mt-2"> </div>
                                </div>                            

                                <!-- Pdf 4 -->
                                <div class="col-12 col-sm-6 col-md-6 col-lg-4" >                               
                                  <div class="row text-center">
                                    <div class="col-md-12" style="padding-top: 15px; padding-bottom: 5px;">
                                      <label for="Presupuesto" class="control-label" >Presupuesto</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-lg-6 col-xl-6 text-center">
                                      <button type="button" class="btn btn-success btn-block btn-xs" id="doc4_i">
                                        <i class="fas fa-file-upload"></i> Subir.
                                      </button>
                                      <input type="hidden" id="doc_old_4" name="doc_old_4" />
                                      <input style="display: none;" id="doc4" type="file" name="doc4" accept=".pdf, .doc, .docx, .csv, .xls, .xlsx, .xlsm" class="docpdf" /> 
                                    </div>
                                    <div class="col-6 col-md-6 col-lg-6 col-xl-6 text-center">
                                      <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(4);">
                                        <i class="fa fa-eye"></i> PDF.
                                      </button>
                                    </div>
                                  </div>                              
                                  <div id="doc4_ver" class="text-center mt-4">
                                    <img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >
                                  </div>
                                  <div class="text-center" id="doc4_nombre"><!-- aqui va el nombre del pdf --></div>

                                  <!-- linea divisoria -->
                                  <div class="col-lg-12 borde-arriba-naranja mt-2"> </div>
                                </div>
                                
                                <!-- Pdf 5 -->
                                <div class="col-12 col-sm-6 col-md-6 col-lg-4" >                               
                                  <div class="row text-center">
                                    <div class="col-md-12" style="padding-top: 15px; padding-bottom: 5px;">
                                      <label for="analisis-de-costos-unitarios" class="control-label" > Analisis de costos unitarios</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-lg-6 col-xl-6 text-center">
                                      <button type="button" class="btn btn-success btn-block btn-xs" id="doc5_i">
                                        <i class="fas fa-file-upload"></i> Subir.
                                      </button>
                                      <input type="hidden" id="doc_old_5" name="doc_old_5" />
                                      <input style="display: none;" id="doc5" type="file" name="doc5" accept=".pdf, .doc, .docx, .csv, .xls, .xlsx, .xlsm" class="docpdf" /> 
                                    </div>
                                    <div class="col-6 col-md-6 col-lg-6 col-xl-6 text-center">
                                      <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(5);">
                                        <i class="fa fa-eye"></i> PDF.
                                      </button>
                                    </div>
                                  </div>                              
                                  <div id="doc5_ver" class="text-center mt-4">
                                    <img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >
                                  </div>
                                  <div class="text-center" id="doc5_nombre"><!-- aqui va el nombre del pdf --></div>

                                  <!-- linea divisoria -->
                                  <div class="col-lg-12 borde-arriba-naranja mt-2"> </div>
                                </div>

                                <!-- Pdf 6 -->
                                <div class="col-12 col-sm-6 col-md-6 col-lg-4" >                               
                                  <div class="row text-center">
                                    <div class="col-md-12" style="padding-top: 15px; padding-bottom: 5px;">
                                      <label for="inicio-de-obra" class="control-label" > Insumos</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-lg-6 col-xl-6 text-center">
                                      <button type="button" class="btn btn-success btn-block btn-xs" id="doc6_i">
                                        <i class="fas fa-file-upload"></i> Subir.
                                      </button>
                                      <input type="hidden" id="doc_old_6" name="doc_old_6" />
                                      <input style="display: none;" id="doc6" type="file" name="doc6" accept=".pdf, .doc, .docx, .csv, .xls, .xlsx, .xlsm" class="docpdf" /> 
                                    </div>
                                    <div class="col-6 col-md-6 col-lg-6 col-xl-6 text-center">
                                      <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(6);">
                                        <i class="fa fa-eye"></i> PDF.
                                      </button>
                                    </div>
                                  </div>                              
                                  <div id="doc6_ver" class="text-center mt-4">
                                    <img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >
                                  </div>
                                  <div class="text-center" id="doc6_nombre"><!-- aqui va el nombre del pdf --></div>

                                  <!-- linea divisoria -->
                                  <div class="col-lg-12 borde-arriba-naranja mt-2"> </div>
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
                                  <i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>
                                  <h4>Cargando...</h4>
                                </div>
                              </div>
                              
                            </div>
                            <!-- /.card-body -->                      
                            <button type="submit" style="display: none;" id="submit-form-proyecto">Submit</button>                      
                          </form>
                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-success" id="guardar_registro">Guardar Cambios</button>
                        </div>                  
                      </div>
                    </div>
                  </div>

                  <!-- Modal ver los documentos subidos -->
                  <div class="modal fade" id="modal-ver-docs">
                    <div class="modal-dialog modal-dialog-scrollable modal-xl">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Documentos subidos</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        
                        <div class="modal-body">
                          <div class="row" >

                            <!-- Pdf 1 -->
                            <div class="col-md-12 col-lg-6 mb-4" >      
                              <div id="verdoc1" class="text-center">
                                <i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>
                                <h4>Cargando...</h4>
                              </div>
                              <div class="text-center" id="verdoc1_nombre">
                                <!-- aqui va el nombre del pdf -->
                              </div>

                              <!-- linea divisoria -->
                              <div class="borde-arriba-naranja mt-4"> </div>
                            </div> 

                            <!-- Pdf 2 -->
                            <div class="col-md-12 col-lg-6 mb-4" >                            
                              <div id="verdoc2" class="text-center">
                                <i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>
                                <h4>Cargando...</h4>
                              </div>
                              <div class="text-center" id="verdoc2_nombre">
                                <!-- aqui va el nombre del pdf -->
                              </div>

                              <!-- linea divisoria -->
                              <div class="borde-arriba-naranja mt-4"> </div>
                            </div>
                            
                            <!-- Pdf 3 -->
                            <div class="col-md-12 col-lg-6 mb-4" >                             
                              <div id="verdoc3" class="text-center">
                                <i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>
                                <h4>Cargando...</h4>
                              </div>
                              <div class="text-center" id="verdoc3_nombre">
                                  <!-- aqui va el nombre del pdf -->
                              </div>

                              <!-- linea divisoria -->
                              <div class="borde-arriba-naranja mt-4"> </div>
                            </div>

                            <!-- Pdf 4 -->
                            <div class="col-md-12 col-lg-6 mb-4" >                             
                              <div id="verdoc4" class="text-center">
                                <i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>
                                <h4>Cargando...</h4>
                              </div>
                              <div class="text-center" id="verdoc4_nombre">
                                  <!-- aqui va el nombre del pdf -->
                              </div>

                              <!-- linea divisoria -->
                              <div class="borde-arriba-naranja mt-4"> </div>
                            </div>

                            <!-- Pdf 5 -->
                            <div class="col-md-12 col-lg-6 mb-4" >                             
                              <div id="verdoc5" class="text-center">
                                <i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>
                                <h4>Cargando...</h4>
                              </div>
                              <div class="text-center" id="verdoc5_nombre">
                                  <!-- aqui va el nombre del pdf -->
                              </div>

                              <!-- linea divisoria -->
                              <div class="borde-arriba-naranja mt-4"> </div>
                            </div>

                            <!-- Pdf 6 -->
                            <div class="col-md-12 col-lg-6 mb-4" >                             
                              <div id="verdoc6" class="text-center">
                                <i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>
                                <h4>Cargando...</h4>
                              </div>
                              <div class="text-center " id="verdoc6_nombre">
                                  <!-- aqui va el nombre del pdf -->
                              </div>

                              <!-- linea divisoria -->
                              <div class="borde-arriba-naranja mt-4"> </div>
                            </div>

                          </div>                      
                        </div>

                        <div class="modal-footer justify-content-end">
                          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>                  
                      </div>
                    </div>
                  </div>

                  <!-- Modal ver detalle del proyecto -->
                  <div class="modal fade" id="modal-ver-detalle">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title" id="detalle_titl">Detalle del proyecto</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        
                        <div class="modal-body">

                          <div id="cargando-3-fomulario">

                            <div class="row" id="cargando-detalle-proyecto"> </div>
                            
                            <div class="mailbox-attachments  clearfix text-center row">
                              <div class="col-12 col-sm-6 col-md-6 col-lg-4" >     
                                <li >                    
                                  <span class="mailbox-attachment-icon name_icon_1"><i class="far fa-file-pdf"></i></span>
                                  <div class="mailbox-attachment-info">
                                    <a href="#" class="mailbox-attachment-name name_doc_1"><i class="fas fa-paperclip"></i> Acta-de-contrato-de-obra</a>
                                      <span class="mailbox-attachment-size clearfix mt-1">
                                        <a href="#" class="btn btn-default btn-sm download_doc_1" download="" data-toggle="tooltip" data-original-title="Descargar"><i class="fas fa-cloud-download-alt"></i></a>
                                        <a href="#" class="btn btn-default btn-sm ver_doc_1" target="_blank" data-toggle="tooltip" data-original-title="Ver"><i class="far fa-eye"></i></a>
                                        <a href="#" class="btn btn-default btn-sm imprimir_doc_1" data-toggle="tooltip" data-original-title="Imprimir" onclick=""><i class="fas fa-print"></i></a>
                                      </span>
                                  </div>
                                </li>
                              </div>
  
                              <div class="col-12 col-sm-6 col-md-6 col-lg-4" >  
                                <li>                       
                                  <span class="mailbox-attachment-icon name_icon_2"><i class="far fa-file-pdf"></i></span>
                                  <div class="mailbox-attachment-info">
                                    <a href="#" class="mailbox-attachment-name name_doc_2"><i class="fas fa-paperclip"></i> Acta-de-entrega-de-terreno</a>
                                      <span class="mailbox-attachment-size clearfix mt-1">
                                        <a href="#" class="btn btn-default btn-sm download_doc_2" download="" data-toggle="tooltip" data-original-title="Descargar"><i class="fas fa-cloud-download-alt"></i></a>
                                        <a href="#" class="btn btn-default btn-sm ver_doc_2" target="_blank" data-toggle="tooltip" data-original-title="Ver"><i class="far fa-eye"></i></a>
                                        <a href="#" class="btn btn-default btn-sm imprimir_doc_2" data-toggle="tooltip" data-original-title="Imprimir" onclick=""><i class="fas fa-print"></i></a>
                                      </span>
                                  </div>
                                </li>
                              </div>
  
                              <div class="col-12 col-sm-6 col-md-6 col-lg-4" >  
                                <li>                       
                                  <span class="mailbox-attachment-icon name_icon_3"><i class="far fa-file-pdf"></i></span>
                                  <div class="mailbox-attachment-info">
                                    <a href="#" class="mailbox-attachment-name name_doc_3"><i class="fas fa-paperclip"></i> Acta-de-inicio-de-obra</a>
                                      <span class="mailbox-attachment-size clearfix mt-1">
                                        <a href="#" class="btn btn-default btn-sm download_doc_3" download="" data-toggle="tooltip" data-original-title="Descargar"><i class="fas fa-cloud-download-alt"></i></a>
                                        <a href="#" class="btn btn-default btn-sm ver_doc_3" target="_blank" data-toggle="tooltip" data-original-title="Ver"><i class="far fa-eye"></i></a>
                                        <a href="#" class="btn btn-default btn-sm imprimir_doc_3" data-toggle="tooltip" data-original-title="Imprimir" onclick=""><i class="fas fa-print"></i></a>
                                      </span>
                                  </div>
                                </li>
                              </div>
  
                              <div class="col-12 col-sm-6 col-md-6 col-lg-4" >     
                                <li>                    
                                  <span class="mailbox-attachment-icon name_icon_4"><i class="far fa-file-pdf"></i></span>
                                  <div class="mailbox-attachment-info">
                                    <a href="#" class="mailbox-attachment-name name_doc_4"><i class="fas fa-paperclip"></i> Presupuesto</a>
                                      <span class="mailbox-attachment-size clearfix mt-1">
                                        <a href="#" class="btn btn-default btn-sm download_doc_4" download="" data-toggle="tooltip" data-original-title="Descargar"><i class="fas fa-cloud-download-alt"></i></a>
                                        <a href="#" class="btn btn-default btn-sm ver_doc_4" target="_blank" data-toggle="tooltip" data-original-title="Ver"><i class="far fa-eye"></i></a>
                                        <a href="#" class="btn btn-default btn-sm imprimir_doc_4" data-toggle="tooltip" data-original-title="Imprimir" onclick=""><i class="fas fa-print"></i></a>
                                      </span>
                                  </div>
                                </li>
                              </div>
  
                              <div class="col-12 col-sm-6 col-md-6 col-lg-4" >  
                                <li>                       
                                  <span class="mailbox-attachment-icon name_icon_5"><i class="far fa-file-pdf"></i></span>
                                  <div class="mailbox-attachment-info">
                                    <a href="#" class="mailbox-attachment-name name_doc_5"><i class="fas fa-paperclip"></i> Analisis-de-costos-unitarios</a>
                                      <span class="mailbox-attachment-size clearfix mt-1">
                                        <a href="#" class="btn btn-default btn-sm download_doc_5" download="" data-toggle="tooltip" data-original-title="Descargar"><i class="fas fa-cloud-download-alt"></i></a>
                                        <a href="#" class="btn btn-default btn-sm ver_doc_5" target="_blank" data-toggle="tooltip" data-original-title="Ver"><i class="far fa-eye"></i></a>
                                        <a href="#" class="btn btn-default btn-sm imprimir_doc_5" data-toggle="tooltip" data-original-title="Imprimir" onclick=""><i class="fas fa-print"></i></a>
                                      </span>
                                  </div>
                                </li>
                              </div>
  
                              <div class="col-12 col-sm-6 col-md-6 col-lg-4" >  
                                <li>                       
                                  <span class="mailbox-attachment-icon name_icon_6"><i class="far fa-file-pdf"></i></span>
                                  <div class="mailbox-attachment-info">
                                    <a href="#" class="mailbox-attachment-name name_doc_6"><i class="fas fa-paperclip"></i> Insumos</a>
                                      <span class="mailbox-attachment-size clearfix mt-1">
                                        <a href="#" class="btn btn-default btn-sm download_doc_6" download="" data-toggle="tooltip" data-original-title="Descargar"><i class="fas fa-cloud-download-alt"></i></a>
                                        <a href="#" class="btn btn-default btn-sm ver_doc_6" target="_blank" data-toggle="tooltip" data-original-title="Ver"><i class="far fa-eye"></i></a>
                                        <a href="#" class="btn btn-default btn-sm imprimir_doc_6" data-toggle="tooltip" data-original-title="Imprimir" onclick=""><i class="fas fa-print"></i></a>
                                      </span>
                                  </div>
                                </li>
                              </div>
                            </div>
                          </div>

                          <div id="cargando-4-fomulario">
                            <div class="col-lg-12 text-center">
                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>
                              <h4>Cargando...</h4>
                            </div>
                          </div>

                        </div>
                                          
                      </div>
                    </div>
                  </div>

                  <!-- Modal agregar valorizaciones -->
                  <div class="modal fade" id="modal-agregar-valorizaciones">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Valorizaciones <small class="text-danger">(El documento nuevo reemplazara al antiguo)</small></h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        
                        <div class="modal-body">
                          <!-- form start -->
                          <form id="form-valorizaciones" name="form-valorizaciones"  method="POST" >                      
                            
                            <div class="row" id="cargando-3-fomulario">
                              <!-- id proyecto -->
                              <input type="hidden" name="idproyect" id="idproyect" />
                              
                              <!-- Doc Valorizaciones -->
                              <div class="col-md-12 col-lg-6" >                               
                                <div class="row text-center">
                                  <div class="col-md-12" style="padding-top: 15px; padding-bottom: 5px;">
                                    <label for="cip" class="control-label" >Valorizaciones </label>
                                  </div>
                                  <div class="col-md-6 text-center">
                                    <button type="button" class="btn btn-success btn-block" id="doc7_i">
                                      <i class="fas fa-file-upload"></i> Subir.
                                    </button>
                                    <input type="hidden" id="doc_old_7" name="doc_old_7" />
                                    <input style="display: none;" id="doc7" type="file" name="doc7" accept=".xlsx, .xlsm, .xls, .csv" class="docpdf" /> 
                                  </div>
                                  <div class="col-md-6 text-center">
                                    <button type="button" class="btn btn-info btn-block" disabled onclick="PreviewImage();">
                                      <i class="fa fa-eye"></i> Excel.
                                    </button>
                                  </div>
                                </div>                              
                                <div id="doc7_ver" class="text-center mt-4">
                                  <img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >
                                </div>
                                <div class="text-center" id="doc7_nombre"><!-- aqui va el nombre del pdf --></div>
                              </div>

                              <!-- Documento existe -->
                              <!-- Pdf 1 -->
                              <div class="col-md-12 col-lg-6 " > 
                                <!-- linea divisoria -->
                                <div class="borde-arriba-naranja mt-4"> </div>

                                <div class="text-center" id="verdoc7_nombre">
                                  <!-- aqui va el nombre del pdf -->
                                </div>    
                                <div id="verdoc7" class="text-center mt-4">
                                  <i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>
                                  <h4>Cargando...</h4>
                                </div>                            
                              </div> 

                              <!-- barprogress -->
                              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                                <div class="progress" id="div_barra_progress">
                                  <div id="barra_progress2" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                    0%
                                  </div>
                                </div>
                              </div>                                          

                            </div>  

                            
                            
                            <div class="row" id="cargando-4-fomulario" style="display: none;">
                              <div class="col-lg-12 text-center">
                                <i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>
                                <h4>Cargando...</h4>
                              </div>
                            </div>
                            <!-- /.card-body -->                      
                            <button type="submit" style="display: none;" id="submit-form-valorizaciones">Submit</button>                      
                          </form>
                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-success" id="guardar_registro_valorizaciones">Guardar Cambios</button>
                        </div>                  
                      </div>
                    </div>
                  </div>

                </section>
                <!-- /.content -->
              </div>
              <!--Fin-Contenido-->
              <?php
            }else{
              require 'noacceso.php';
            }
            require 'footer.php';
          ?>

        </div>

        <?php require 'script.php'; ?>

        <!-- <script src="../plugins/moment/moment.min.js"></script> -->
        <!-- <script src="../plugins/moment/locales.js"></script> -->
        <!-- <script src="../plugins/moment/locale/es.js"></script> -->
        <script type="text/javascript" src="scripts/proyecto.js"></script>

        <script>  $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>
        
      </body>
    </html>
    <?php    
  }
  ob_end_flush();
?>
