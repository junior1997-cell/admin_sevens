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
        <title>Otros | Admin Sevens</title>

        <?php $title = "Otros"; require 'head.php'; ?>

        <!--CSS  switch_MATERIALES-->
        <link rel="stylesheet" href="../dist/css/switch_materiales.css" />
      </head>
      <body class="hold-transition sidebar-collapse sidebar-mini layout-fixed layout-navbar-fixed" idproyecto="<?php echo $_SESSION['idproyecto']; ?>">
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
              <!-- Main content -->
              <section class="content">
                <div class="container-fluid">
                  <div class="row">
                    <div class="col-12 col-sm-12 mt-4">
                      <div class="card card-primary card-outline card-tabs">
                        <div class="card-header p-0 pt-1 border-bottom-0">
                          <ul class="nav nav-tabs" id="custom-tab" role="tablist">
                            <li class="nav-item">
                              <a class="nav-link active" id="custom-producto-tab" data-toggle="pill" href="#custom-producto" role="tab" aria-controls="custom-producto" aria-selected="true"><i class="fa-solid fa-computer"></i> Producto</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" id="custom-persona-tab" data-toggle="pill" href="#custom-persona" role="tab" aria-controls="custom-persona" aria-selected="false"><i class="fas fa-user"></i> Persona</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" id="custom-compra-tab" data-toggle="pill" href="#custom-compra" role="tab" aria-controls="custom-compra" aria-selected="false"><i class="fas fa-shopping-cart"></i> Compra</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" id="custom-venta-tab" data-toggle="pill" href="#custom-venta" role="tab" aria-controls="custom-venta" aria-selected="false"><i class="fas fa-shopping-cart"></i> Venta</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" id="custom-sucursal-tab" data-toggle="pill" href="#custom-sucursal" role="tab" aria-controls="custom-sucursal" aria-selected="false"><i class="fas fa-store-alt"></i> Sucursal</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" id="custom-empresa-tab" data-toggle="pill" href="#custom-empresa" role="tab" aria-controls="custom-empresa" aria-selected="false"><i class="fa-solid fa-gear"></i> Empresa</a>
                            </li>
                          </ul>
                        </div>                        
                        <!-- /.card -->
                      </div>
                    </div>

                    <div class="col-12">
                      <div class="tab-content" id="custom-tabContent">
                        <div class="tab-pane fade show active" id="custom-producto" role="tabpanel" aria-labelledby="custom-producto-tab">
                          <div class="row">                            

                            <!-- TBLA - MARCA -->
                            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                              <!-- Content Header (Page header) -->
                              <section class="content-header">
                                <div class="container-fluid">
                                  <div class="row mb-2">
                                    <div class="col-sm-6">
                                      <h1>Marcas</h1>
                                    </div>
                                    <div class="col-sm-6">
                                      <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                        <li class="breadcrumb-item active">Marcas</li>
                                      </ol>
                                    </div>
                                  </div>
                                </div>
                                <!-- /.container-fluid -->
                              </section>

                              <!-- Main content -->

                              <!-- Main content -->
                              <section class="content">
                                <div class="container-fluid">
                                  <div class="card card-primary card-outline">
                                    <div class="card-header">
                                      <h3 class="card-title">
                                        <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-marca" onclick="limpiar();"><i class="fas fa-plus-circle"></i> Agregar</button>
                                        Admnistrar tus marcas.
                                      </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                      <table id="tabla-marca" class="table table-bordered table-striped display" style="width: 100% !important;">
                                        <thead>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Hexadecimal</th>
                                            <th>Estado</th>
                                          </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Hexadecimal</th>
                                            <th>Estado</th>
                                          </tr>
                                        </tfoot>
                                      </table>
                                    </div>
                                    <!-- /.card-body -->
                                  </div>
                                  <!-- /.card -->
                                </div>
                                <!-- /.container-fluid -->
                              </section>
                              <!-- /.content -->
                            </div>

                            <!-- TBLA - UNIDAD DE MEDIDA-->
                            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                              <!-- Content Header (Page header) -->
                              <section class="content-header">
                                <div class="container-fluid">
                                  <div class="row mb-2">
                                    <div class="col-sm-6">
                                      <h1>Unidades de Medida</h1>
                                    </div>
                                    <div class="col-sm-6">
                                      <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                        <li class="breadcrumb-item active">Unidad de Medida</li>
                                      </ol>
                                    </div>
                                  </div>
                                </div>
                                <!-- /.container-fluid -->
                              </section>

                              <!-- Main content -->

                              <!-- Main content -->
                              <section class="content">
                                <div class="container-fluid">
                                  <div class="card card-primary card-outline">
                                    <div class="card-header">
                                      <h3 class="card-title">
                                        <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-unidad-m" onclick="limpiar_unidades_m();"><i class="fas fa-plus-circle"></i> Agregar</button>
                                        Admnistrar Unidad de medidas.
                                      </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                      <table id="tabla-unidades-m" class="table table-bordered table-striped display" style="width: 100% !important;">
                                        <thead>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Abreviación</th>
                                            <th>Estado</th>
                                          </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Abreviación</th>
                                            <th>Estado</th>
                                          </tr>
                                        </tfoot>
                                      </table>
                                    </div>
                                    <!-- /.card-body -->
                                  </div>
                                  <!-- /.card -->
                                </div>
                                <!-- /.container-fluid -->
                              </section>
                              <!-- /.content -->
                            </div>

                            <!-- TBLA - CATEGORIAS - ACTIVOS FIJO -->
                            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                              <!-- Content Header (Page header) -->
                              <section class="content-header">
                                <div class="container-fluid">
                                  <div class="row mb-2">
                                    <div class="col-sm-6">
                                      <h1>Categorias activos fijos</h1>
                                    </div>
                                    <div class="col-sm-6">
                                      <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                        <li class="breadcrumb-item active">activos fijos</li>
                                      </ol>
                                    </div>
                                  </div>
                                </div>
                                <!-- /.container-fluid -->
                              </section>

                              <!-- Main content -->

                              <!-- Main content -->
                              <section class="content">
                                <div class="container-fluid">
                                  <div class="card card-primary card-outline">
                                    <div class="card-header">
                                      <h3 class="card-title">
                                        <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-categorias-af" onclick="limpiar_c_af();"><i class="fas fa-plus-circle"></i> Agregar</button>
                                        Categorías activos fijos.
                                      </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                      <table id="tabla-categorias-af" class="table table-bordered table-striped display" style="width: 100% !important;">
                                        <thead>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Estado</th>
                                          </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Estado</th>
                                          </tr>
                                        </tfoot>
                                      </table>
                                    </div>
                                    <!-- /.card-body -->
                                  </div>
                                  <!-- /.card -->
                                </div>
                                <!-- /.container-fluid -->
                              </section>
                              <!-- /.content -->
                            </div>
                           
                            <!-- TBLA - COLOR -->
                            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                              <!-- Content Header (Page header) -->
                              <section class="content-header">
                                <div class="container-fluid">
                                  <div class="row mb-2">
                                    <div class="col-sm-6">
                                      <h1>Colores</h1>
                                    </div>
                                    <div class="col-sm-6">
                                      <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                        <li class="breadcrumb-item active">Colores</li>
                                      </ol>
                                    </div>
                                  </div>
                                </div>
                                <!-- /.container-fluid -->
                              </section>

                              <!-- Main content -->

                              <!-- Main content -->
                              <section class="content">
                                <div class="container-fluid">
                                  <div class="card card-primary card-outline">
                                    <div class="card-header">
                                      <h3 class="card-title">
                                        <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-color" onclick="limpiar();"><i class="fas fa-plus-circle"></i> Agregar</button>
                                        Admnistrar Colores.
                                      </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                      <table id="tabla-colores" class="table table-bordered table-striped display" style="width: 100% !important;">
                                        <thead>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Hexadecimal</th>
                                            <th>Estado</th>
                                          </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Hexadecimal</th>
                                            <th>Estado</th>
                                          </tr>
                                        </tfoot>
                                      </table>
                                    </div>
                                    <!-- /.card-body -->
                                  </div>
                                  <!-- /.card -->
                                </div>
                                <!-- /.container-fluid -->
                              </section>
                              <!-- /.content -->
                            </div>

                          </div> <!-- /.row -->
                        </div> <!-- /.tab-pane -->
                        <div class="tab-pane fade" id="custom-persona" role="tabpanel" aria-labelledby="custom-persona-tab">
                          <div class="row">
                            <!-- TBLA - BANCOS -->
                            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                              <!-- Content Header (Page header) -->
                              <section class="content-header">
                                <div class="container-fluid">
                                  <div class="row mb-2">
                                    <div class="col-sm-6">
                                      <h1>Bancos</h1>
                                    </div>
                                    <div class="col-sm-6">
                                      <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                        <li class="breadcrumb-item active">Bancos</li>
                                      </ol>
                                    </div>
                                  </div>
                                </div>
                                <!-- /.container-fluid -->
                              </section>

                              <!-- Main content -->
                              <section class="content">
                                <div class="container-fluid">
                                  <div class="card card-primary card-outline">
                                    <div class="card-header">
                                      <h3 class="card-title">
                                        <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-bancos" onclick="limpiar_banco();"><i class="fas fa-plus-circle"></i> Agregar</button>
                                        Administrar Bancos.
                                      </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                      <table id="tabla-bancos" class="table table-bordered table-striped display" style="width: 100% !important;">
                                        <thead>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Formato Cta/CCI</th>
                                            <th>Estado</th>
                                            <th>Nombre</th>
                                            <th>Alias</th>
                                            <th>Formato Cta</th>
                                            <th>Formato CCI</th>
                                            <th>Formato Cta. Dtrac.</th>
                                          </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Formato</th>
                                            <th>Estado</th>
                                            <th>Nombre</th>
                                            <th>Alias</th>
                                            <th>Formato Cta</th>
                                            <th>Formato CCI</th>
                                            <th>Formato Cta. Dtrac.</th>
                                          </tr>
                                        </tfoot>
                                      </table>
                                    </div>
                                    <!-- /.card-body -->
                                  </div>
                                  <!-- /.card -->
                                </div>
                                <!-- /.container-fluid -->
                              </section>
                              <!-- /.content -->
                            </div>

                            <!-- TBLA - OCUPACION -->
                            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                              <!-- Content Header (Page header) -->
                              <section class="content-header">
                                <div class="container-fluid">
                                  <div class="row mb-2">
                                    <div class="col-sm-6">
                                      <h1>Ocupación</h1>
                                    </div>
                                  </div>
                                </div>
                                <!-- /.container-fluid -->
                              </section>

                              <!-- Main content -->

                              <!-- Main content -->
                              <section class="content">
                                <div class="container-fluid">
                                  <div class="card card-primary card-outline">
                                    <div class="card-header">
                                      <h3 class="card-title">
                                        <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-ocupacion" onclick="limpiar_ocupacion();"><i class="fas fa-plus-circle"></i> Agregar</button>
                                        Admnistrar Ocupaciones.
                                      </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                      <table id="tabla-ocupacion" class="table table-bordered table-striped display" style="width: 100% !important;">
                                        <thead>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Estado</th>
                                          </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Estado</th>
                                          </tr>
                                        </tfoot>
                                      </table>
                                    </div>
                                    <!-- /.card-body -->
                                  </div>
                                  <!-- /.card -->
                                </div>
                                <!-- /.container-fluid -->
                              </section>
                              <!-- /.content -->
                            </div>

                            <!-- TBLA - TIPO TRABAJADOR-->
                            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                              <!-- Content Header (Page header) -->
                              <section class="content-header">
                                <div class="container-fluid">
                                  <div class="row mb-2">
                                    <div class="col-sm-6">
                                      <h1>Tipo</h1>
                                    </div>
                                  </div>
                                </div>
                                <!-- /.container-fluid -->
                              </section>

                              <!-- Main content -->

                              <!-- Main content -->
                              <section class="content">
                                <div class="container-fluid">
                                  <div class="card card-primary card-outline">
                                    <div class="card-header">
                                      <h3 class="card-title">
                                        <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-tipo" onclick="limpiar_tipo();"><i class="fas fa-plus-circle"></i> Agregar</button>
                                        Admnistrar Tipo* .
                                      </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                      <table id="tabla-tipo" class="table table-bordered table-striped display" style="width: 100% !important;">
                                        <thead>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Estado</th>
                                          </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Estado</th>
                                          </tr>
                                        </tfoot>
                                      </table>
                                    </div>
                                    <!-- /.card-body -->
                                  </div>
                                  <!-- /.card -->
                                </div>
                                <!-- /.container-fluid -->
                              </section>
                              <!-- /.content -->
                            </div>

                            <!-- TBLA - CARGO-->
                            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                              <!-- Content Header (Page header) -->
                              <section class="content-header">
                                <div class="container-fluid">
                                  <div class="row mb-2">
                                    <div class="col-sm-6">
                                      <h1>Desempeño</h1>
                                    </div>
                                    <div class="col-sm-6">
                                      <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                        <li class="breadcrumb-item active">Desempeño</li>
                                      </ol>
                                    </div>
                                  </div>
                                </div>
                                <!-- /.container-fluid -->
                              </section>

                              <!-- Main content -->

                              <!-- Main content -->
                              <section class="content">
                                <div class="container-fluid">
                                  <div class="card card-primary card-outline">
                                    <div class="card-header">
                                      <h3 class="card-title">
                                        <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-desempenio" onclick="limpiar_desempenio();"><i class="fas fa-plus-circle"></i> Agregar</button>
                                        Admnistrar Desempeño.
                                      </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                      <table id="tabla-desempenio" class="table table-bordered table-striped display" style="width: 100% !important;">
                                        <thead>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Estado</th>
                                          </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Estado</th>
                                          </tr>
                                        </tfoot>
                                      </table>
                                    </div>
                                    <!-- /.card-body -->
                                  </div>
                                  <!-- /.card -->
                                </div>
                                <!-- /.container-fluid -->
                              </section>
                              <!-- /.content -->
                            </div>
                          </div> <!-- /.row -->
                        </div> <!-- /.tab-pane -->
                        <div class="tab-pane fade" id="custom-compra" role="tabpanel" aria-labelledby="custom-compra-tab">
                          <div class="row">
                            <!-- TBLA - GLOSA -->
                            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                              <!-- Content Header (Page header) -->
                              <section class="content-header">
                                <div class="container-fluid">
                                  <div class="row mb-2">
                                    <div class="col-sm-6">
                                      <h1>Glosas</h1>
                                    </div>
                                    <div class="col-sm-6">
                                      <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                        <li class="breadcrumb-item active">Glosas</li>
                                      </ol>
                                    </div>
                                  </div>
                                </div>
                                <!-- /.container-fluid -->
                              </section>

                              <!-- Main content -->

                              <!-- Main content -->
                              <section class="content">
                                <div class="container-fluid">
                                  <div class="card card-primary card-outline">
                                    <div class="card-header">
                                      <h3 class="card-title">
                                        <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-glosa" onclick="limpiar();"><i class="fas fa-plus-circle"></i> Agregar</button>
                                        Admnistrar tus Glosas.
                                      </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                      <table id="tabla-glosa" class="table table-bordered table-striped display" style="width: 100% !important;">
                                        <thead>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Descripcion</th>
                                            <th>Estado</th>
                                          </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Descripcion</th>
                                            <th>Estado</th>
                                          </tr>
                                        </tfoot>
                                      </table>
                                    </div>
                                    <!-- /.card-body -->
                                  </div>
                                  <!-- /.card -->
                                </div>
                                <!-- /.container-fluid -->
                              </section>
                              <!-- /.content -->
                            </div>
                          </div> <!-- /.row -->
                        </div> <!-- /.tab-pane -->
                        <div class="tab-pane fade" id="custom-venta" role="tabpanel" aria-labelledby="custom-venta-tab">
                          <div class="row">
                            <div class="col-12">
                            --- venta - vacia ---
                            </div>                            
                          </div> <!-- /.row -->
                        </div> <!-- /.tab-pane -->
                        <div class="tab-pane fade" id="custom-sucursal" role="tabpanel" aria-labelledby="custom-sucursal-tab">
                          <div class="row">
                            <div class="col-12">
                              --- sucursal - vacio ---
                            </div>
                            
                          </div> <!-- /.row -->
                        </div> <!-- /.tab-pane -->
                        <div class="tab-pane fade" id="custom-empresa" role="tabpanel" aria-labelledby="custom-empresa-tab">
                          <div class="row">
                            <div class="col-12">
                              --- empresa - vacio ---
                            </div>                            
                          </div> <!-- /.row -->
                        </div> <!-- /.tab-pane -->
                      </div>
                    </div>
                    
                  </div>
                </div>
                <!-- /.container-fluid -->
              </section>

              <!-- MODAL - BANCOS -->
              <div class="modal fade" id="modal-agregar-bancos">
                <div class="modal-dialog modal-dialog-scrollable modal-md">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Agregar Banco</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <div class="modal-body">
                      <!-- form start -->
                      <form id="form-bancos" name="form-bancos" method="POST" autocomplete="off">
                        <div class="card-body">
                          <div class="row" id="cargando-a-fomulario">
                            <!-- id banco -->
                            <input type="hidden" name="idbancos" id="idbancos" />

                            <!-- Nombre -->
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                              <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre del banco." />
                              </div>
                            </div>

                            <!-- alias -->
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                              <div class="form-group">
                                <label for="alias">Alias</label>
                                <input type="text" name="alias" id="alias" class="form-control" placeholder="Alias del banco." />
                              </div>
                            </div>

                            <!-- Formato cuenta bancaria -->
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                              <div class="form-group">
                                <label for="formato_cta">Formato Cuenta Bancaria</label>
                                <input type="text" name="formato_cta" id="formato_cta" class="form-control" placeholder="Formato." value="00000000" data-inputmask="'mask': ['99-99-99-99', '99 99 99 99']" data-mask />
                              </div>
                            </div>

                            <!-- Formato CCI -->
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                              <div class="form-group">
                                <label for="formato_cci">Formato CCI</label>
                                <input type="text" name="formato_cci" id="formato_cci" class="form-control" placeholder="Formato." value="00000000" data-inputmask="'mask': ['99-99-99-99', '99 99 99 99']" data-mask />
                              </div>
                            </div>

                            <!-- Formato CCI -->
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                              <div class="form-group">
                                <label for="formato_detracciones">Formato Detracción</label>
                                <input type="text" name="formato_detracciones" id="formato_detracciones" class="form-control" placeholder="Formato." value="00000000" data-inputmask="'mask': ['99-99-99-99', '99 99 99 99']" data-mask />
                              </div>
                            </div> 

                            <!--img-material-->
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                              <label for="imagen1">Imagen</label>
                              <div style="text-align: center;">
                                <img
                                  onerror="this.src='../dist/img/default/img_defecto_banco.png';"
                                  src="../dist/img/default/img_defecto_banco.png"
                                  class="img-thumbnail"
                                  id="imagen1_i"
                                  style="cursor: pointer !important; height: 100% !important;"
                                  width="auto"
                                />
                                <input style="display: none;" type="file" name="imagen1" id="imagen1" accept="image/*" />
                                <input type="hidden" name="imagen1_actual" id="imagen1_actual" />
                                <div class="text-center" id="imagen1_nombre"><!-- aqui va el nombre de la FOTO --></div>
                              </div>
                            </div>

                            <!-- barprogress -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                              <div class="progress" id="div_barra_progress_banco">
                                <div id="barra_progress_banco" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                  0%
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="row" id="cargando-b-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                              <br />
                              <h4>Cargando...</h4>
                            </div>
                          </div>
                        </div>
                        <!-- /.card-body -->
                        <button type="submit" style="display: none;" id="submit-form-bancos">Submit</button>
                      </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar();">Close</button>
                      <button type="submit" class="btn btn-success" id="guardar_registro">Guardar Cambios</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- MODAL - COLOR -->
              <div class="modal fade" id="modal-agregar-color">
                <div class="modal-dialog modal-dialog-scrollable modal-md">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Agregar Color</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <div class="modal-body">
                      <!-- form start -->
                      <form id="form-color" name="form-color" method="POST" autocomplete="off">
                        <div class="card-body">
                          <div class="row" id="cargando-1-fomulario">
                            <!-- id banco -->
                            <input type="hidden" name="idcolor" id="idcolor" />

                            <!-- Nombre -->
                            <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="nombre_color">Nombre</label>
                                <input type="text" name="nombre_color" class="form-control" id="nombre_color" placeholder="Nombre del color." />
                              </div>
                            </div>

                            <!-- hexadecimal -->
                            <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label>Color hexadecimal:</label>
                                <div class="input-group my-colorpicker2">
                                  <input type="text" name="hexadecimal" id="hexadecimal" class="form-control" placeholder="#00AFB">
                                  <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-square fa-lg"></i></span>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="col-lg-12 mt-4">
                              <div class="alert alert-warning alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h5><i class="icon fas fa-exclamation-triangle"></i> <b>Que es un Hexadecimal?</b></h5>
                                Un <b>color hexadecimal</b> sigue el formato #RRVVAA, donde RR es rojo, VV es verde y AA es azul. 
                                Estos enteros hexadecimales pueden encontrarse en un <b>rango de 00 a FF</b> para especificar la intensidad del color.
                                Mas informacion en: <a href="https://htmlcolorcodes.com/es/nombres-de-los-colores/" class="font-weight-bold" target="_blank" rel="noopener noreferrer" style="color: #000 !important;">https://htmlcolorcodes.com</a>
                              </div>
                            </div>

                            <!-- barprogress -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                              <div class="progress" id="div_barra_progress_color">
                                <div id="barra_progress_color" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
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
                        <button type="submit" style="display: none;" id="submit-form-color">Submit</button>
                      </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar();">Close</button>
                      <button type="submit" class="btn btn-success" id="guardar_registro_color">Guardar Cambios</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- MODAL - UNIDAD DE MEDIDA-->
              <div class="modal fade" id="modal-agregar-unidad-m">
                <div class="modal-dialog modal-dialog-scrollable modal-md">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Agregar Unidad de Medida</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <div class="modal-body">
                      <!-- form start -->
                      <form id="form-unidad-m" name="form-unidad-m" method="POST" autocomplete="off">
                        <div class="card-body">
                          <div class="row" id="cargando-3-fomulario">
                            <!-- id idunidad_medida -->
                            <input type="hidden" name="idunidad_medida" id="idunidad_medida" />

                            <!-- nombre_medida -->
                            <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" name="nombre_medida" class="form-control" id="nombre_medida" placeholder="Nombre de la medida" />
                              </div>
                            </div>

                            <!-- abreviacion -->
                            <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="abreviacion">Abreviación</label>
                                <input type="text" name="abreviacion" class="form-control" id="abreviacion" placeholder="Abreviación." />
                              </div>
                            </div>

                            <!-- barprogress -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                              <div class="progress" id="div_barra_progress_um">
                                <div id="barra_progress_um" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                  0%
                                </div>
                              </div>
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
                        <button type="submit" style="display: none;" id="submit-form-unidad-m">Submit</button>
                      </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_unidades_m();">Close</button>
                      <button type="submit" class="btn btn-success" id="guardar_registro_unidad_m">Guardar Cambios</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- MODAL - OCUPACION-->
              <div class="modal fade" id="modal-agregar-ocupacion">
                <div class="modal-dialog modal-dialog-scrollable modal-md">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Agregar Ocupación</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <div class="modal-body">
                      <!-- form start -->
                      <form id="form-ocupacion" name="form-ocupacion" method="POST" autocomplete="off">
                        <div class="card-body">
                          <div class="row" id="cargando-5-fomulario">
                            <!-- id idunidad_medida -->
                            <input type="hidden" name="idocupacion" id="idocupacion" />
                            <!-- nombre_medida -->
                            <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="nombre">Nombre Ocupación</label>
                                <input type="text" name="nombre_ocupacion" id="nombre_ocupacion" class="form-control" placeholder="Nombre de la Ocupación" />
                              </div>
                            </div>

                            <!-- barprogress -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                              <div class="progress" id="div_barra_progress_ocupacion">
                                <div id="barra_progress_ocupacion" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                  0%
                                </div>
                              </div>
                            </div>

                          </div>

                          <div class="row" id="cargando-6-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                              <br />
                              <h4>Cargando...</h4>
                            </div>
                          </div>
                        </div>
                        <!-- /.card-body -->
                        <button type="submit" style="display: none;" id="submit-form-ocupacion">Submit</button>
                      </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_ocupacion();">Close</button>
                      <button type="submit" class="btn btn-success" id="guardar_registro_ocupacion">Guardar Cambios</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- MODAL - TIPO DE TRABAJDOR -->
              <div class="modal fade" id="modal-agregar-tipo">
                <div class="modal-dialog modal-dialog-scrollable modal-md">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Tipo</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <div class="modal-body">
                      <!-- form start -->
                      <form id="form-tipo" name="form-tipo" method="POST" autocomplete="off">
                        <div class="card-body">
                          <div class="row" id="cargando-7-fomulario">
                            <!-- id idunidad_medida -->
                            <input type="hidden" name="idtipo_trabajador" id="idtipo_trabajador" />

                            <!-- nombre_medida -->
                            <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="nombre">Nombre Tipo</label>
                                <input type="text" name="nombre_tipo" id="nombre_tipo" class="form-control" placeholder="Nombre tipo" />
                              </div>
                            </div>

                            <!-- barprogress -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                              <div class="progress" id="div_barra_progress_tipo">
                                <div id="barra_progress_tipo" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                  0%
                                </div>
                              </div>
                            </div>

                          </div>

                          <div class="row" id="cargando-8-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                              <br />
                              <h4>Cargando...</h4>
                            </div>
                          </div>
                        </div>
                        <!-- /.card-body -->
                        <button type="submit" style="display: none;" id="submit-form-tipo">Submit</button>
                      </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_tipo();">Close</button>
                      <button type="submit" class="btn btn-success" id="guardar_registro_tipo">Guardar Cambios</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- MODAL - CARGO TRABAJDOR-->
              <div class="modal fade" id="modal-agregar-desempenio">
                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Desempenio</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <div class="modal-body">
                      <!-- form start -->
                      <form id="form-desempenio" name="form-desempenio" method="POST" autocomplete="off">
                        <div class="card-body">
                          <div class="row" id="cargando-9-fomulario">
                            <!-- id idunidad_medida -->
                            <input type="hidden" name="iddesempenio" id="iddesempenio" />                           

                            <!-- nombre -->
                            <div class="col-lg-12 ">
                              <div class="form-group">
                                <label for="nombre_desempenio">Nombre</label>
                                <input type="text" name="nombre_desempenio" id="nombre_desempenio" class="form-control" placeholder="Nombre" />
                              </div>
                            </div>

                            <!-- barprogress -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                              <div class="progress" id="div_barra_progress_desempenio">
                                <div id="barra_progress_desempenio" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                  0%
                                </div>
                              </div>
                            </div>

                          </div>

                          <div class="row" id="cargando-10-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                              <br />
                              <h4>Cargando...</h4>
                            </div>
                          </div>
                        </div>
                        <!-- /.card-body -->
                        <button type="submit" style="display: none;" id="submit-form-desempenio">Submit</button>
                      </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_desempenio();">Close</button>
                      <button type="submit" class="btn btn-success" id="guardar_registro_desempenio">Guardar Cambios</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- MODAL - CATEGORIAS - ACTIVO FIJO-->
              <div class="modal fade" id="modal-agregar-categorias-af">
                <div class="modal-dialog modal-dialog-scrollable modal-md">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Agregar categoría activo fijo</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <div class="modal-body">
                      <!-- form start -->
                      <form id="form-categoria-af" name="form-categoria-af" method="POST" autocomplete="off">
                        <div class="card-body">
                          <div class="row" id="cargando-11-fomulario">
                            <!-- id categoria_insumos_af -->
                            <input type="hidden" name="idcategoria_insumos_af" id="idcategoria_insumos_af" />

                            <!-- nombre categoria -->
                            <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="nombre_categoria">Nombre categoría</label>
                                <input type="text" name="nombre_categoria_af" id="nombre_categoria_af" class="form-control" placeholder="Nombre categoría" />
                              </div>
                            </div>

                            <!-- barprogress -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                              <div class="progress" id="div_barra_progress_categoria_af">
                                <div id="barra_progress_categoria_af" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                  0%
                                </div>
                              </div>
                            </div>

                          </div>

                          <div class="row" id="cargando-12-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                              <br />
                              <h4>Cargando...</h4>
                            </div>
                          </div>
                        </div>
                        <!-- /.card-body -->
                        <button type="submit" style="display: none;" id="submit-form-cateogrias-af">Submit</button>
                      </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_c_af();">Close</button>
                      <button type="submit" class="btn btn-success" id="guardar_registro_categoria_af">Guardar Cambios</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- MODAL - VER PERFIL BANCO-->
              <div class="modal fade" id="modal-ver-perfil-banco">
                <div class="modal-dialog modal-dialog-centered modal-md">
                  <div class="modal-content bg-color-0202022e shadow-none border-0">
                    <div class="modal-header">
                      <h4 class="modal-title text-white foto-banco">Foto Banco</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-white cursor-pointer" aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body"> 
                      <div id="perfil-banco" class="class-style">
                        <!-- vemos la foto del banco -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- MODAL - MARCA -->
              <div class="modal fade" id="modal-agregar-marca">
                <div class="modal-dialog modal-dialog-scrollable modal-md">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Agregar Marca</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <div class="modal-body">
                      <!-- form start -->
                      <form id="form-marca" name="form-marca" method="POST" autocomplete="off">
                        <div class="card-body">
                          <div class="row" id="cargando-13-fomulario">
                            <!-- id banco -->
                            <input type="hidden" name="idmarca" id="idmarca" />

                            <!-- Nombre -->
                            <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="nombre_marca">Nombre</label>
                                <input type="text" name="nombre_marca" class="form-control" id="nombre_marca" placeholder="Nombre del marca." />
                              </div>
                            </div>   

                            <!-- descripcion -->
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                              <div class="form-group">
                                <label for="descripcion_marca">Descripción </label> <br />
                                <textarea name="descripcion_marca" id="descripcion_marca" class="form-control" rows="2"></textarea>
                              </div>
                            </div>

                            <!-- barprogress -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                              <div class="progress" id="barra_progress_marca_div">
                                <div id="barra_progress_marca" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                  0%
                                </div>
                              </div>
                            </div>

                          </div>

                          <div class="row" id="cargando-14-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                              <br />
                              <h4>Cargando...</h4>
                            </div>
                          </div>
                        </div>
                        <!-- /.card-body -->
                        <button type="submit" style="display: none;" id="submit-form-marca">Submit</button>
                      </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar();">Close</button>
                      <button type="submit" class="btn btn-success" id="guardar_registro_marca">Guardar Cambios</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- MODAL - GLOSA -->
              <div class="modal fade" id="modal-agregar-glosa">
                <div class="modal-dialog modal-dialog-scrollable modal-md">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Agregar Glosa</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <div class="modal-body">
                      <!-- form start -->
                      <form id="form-glosa" name="form-glosa" method="POST" autocomplete="off">
                        <div class="card-body">
                          <div class="row" id="cargando-13-fomulario">
                            <!-- id banco -->
                            <input type="hidden" name="idglosa" id="idglosa" />

                            <!-- Nombre -->
                            <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="nombre_glosa">Nombre Glosa</label>
                                <input type="text" name="nombre_glosa" class="form-control" id="nombre_glosa" placeholder="Nombre del glosa." />
                              </div>
                            </div>   

                            <!-- descripcion -->
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                              <div class="form-group">
                                <label for="descripcion_glosa">Descripción </label> <br />
                                <textarea name="descripcion_glosa" id="descripcion_glosa" class="form-control" rows="2"></textarea>
                              </div>
                            </div>

                            <!-- barprogress -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                              <div class="progress" id="barra_progress_glosa_div">
                                <div id="barra_progress_glosa" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                  0%
                                </div>
                              </div>
                            </div>

                          </div>

                          <div class="row" id="cargando-14-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                              <br />
                              <h4>Cargando...</h4>
                            </div>
                          </div>
                        </div>
                        <!-- /.card-body -->
                        <button type="submit" style="display: none;" id="submit-form-glosa">Submit</button>
                      </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar();">Close</button>
                      <button type="submit" class="btn btn-success" id="guardar_registro_glosa">Guardar Cambios</button>
                    </div>
                  </div>
                </div>
              </div>

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
        
        <script type="text/javascript" src="scripts/otros.js"></script>
        <script type="text/javascript" src="scripts/bancos.js"></script>
        <script type="text/javascript" src="scripts/color.js"></script>
        <script type="text/javascript" src="scripts/unidades_m.js"></script>
        <script type="text/javascript" src="scripts/ocupacion.js"></script>
        <script type="text/javascript" src="scripts/tipo.js"></script>
        <script type="text/javascript" src="scripts/desempenio.js"></script>
        <script type="text/javascript" src="scripts/categoria_af.js"></script>
        <script type="text/javascript" src="scripts/marca.js"></script>
        <!-- <script type="text/javascript" src="scripts/glosa.js"></script> -->

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>
        
      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
