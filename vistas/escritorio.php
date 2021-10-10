<?php
  //Activamos el almacenamiento en el buffer
  ob_start();
  session_start();

  if (!isset($_SESSION["nombre"])){
    header("Location: login.html");
  }else{ ?>
    <!DOCTYPE html>
    <html lang="es">
      <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Admin Sevens | Escritorio</title>
        <?php
          require 'head.php';
        ?>
        <!-- Theme style -->
        <!-- <link rel="stylesheet" href="../dist/css/adminlte.min.css"> -->
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
                  <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                      <div class="inner">
                        <h3>150</h3>

                        <p>Total de Proyectos</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-bag"></i>
                      </div>
                      <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                  </div>
                  <!-- ./col -->
                  <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                      <div class="inner">
                        <h3>53<sup style="font-size: 20px;">%</sup></h3>

                        <p>Total de Proveedores</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                      </div>
                      <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                  </div>
                  <!-- ./col -->
                  <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                      <div class="inner">
                        <h3>44</h3>

                        <p>Toal de Trabajadores</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-person-add"></i>
                      </div>
                      <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                  </div>
                  <!-- ./col -->
                  <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                      <div class="inner">
                        <h3>65</h3>

                        <p>Total de Servicio</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                      </div>
                      <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                  </div>
                  <!-- ./col -->
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
                        <h3 class="card-title " >
                          <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-proyecto" onclick="limpiar();">
                          <i class="fas fa-plus-circle"></i> Agregar
                          </button>
                          Proyectos                        
                        </h3>                      
                      </div>
                      <!-- /.card-header -->
                      <div class="card-body">
                        <table id="tabla-proyectos" class="table table-bordered table-striped display" style="width: 100% !important;">
                          <thead>
                            <tr>
                              <th class="">Aciones</th>
                              <th>Empresa</th>
                              <th>Nombre de proyecto</th>
                              <th>Ubicación</th>
                              <th>Costo</th>
                              <th>Documentos</th>
                              <th>Estado</th>
                            </tr>
                          </thead>
                          <tbody>                         
                            
                          </tbody>
                          <tfoot>
                            <tr>
                              <th class="">Aciones</th>
                              <th>Empresa</th>
                              <th>Nombre de proyecto</th>
                              <th>Ubicación</th>
                              <th>Costo</th>
                              <th>Documentos</th>
                              <th>Estado</th>
                            </tr>
                          </tfoot>
                        </table>
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

              <!-- Modal agregar usuario -->
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
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="tipo_documento">Tipo de documento</label>
                                <select name="tipo_documento" id="tipo_documento" class="form-control"  placeholder="Tipo de documento">
                                  <option selected value="DNI">DNI</option>
                                  <option value="RUC">RUC</option>
                                  <option value="CEDULA">CEDULA</option>
                                  <option value="OTRO">OTRO</option>
                                </select>
                              </div>
                            </div>

                            <!-- N° de documento -->
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="numero_documento">N° de documento</label>
                                <div class="input-group">
                                  <input type="number" name="numero_documento" id="numero_documento" class="form-control" placeholder="N° de documento" />
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
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="empresa">Empresa <small>(para quien va la obra)</small> </label>                               
                                <input type="text" name="empresa" id="empresa" class="form-control"  placeholder="Empresa">  
                              </div>                                                        
                            </div>

                            <!-- Nombre del proyecto -->
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="nombre_proyecto">Nombre del proyecto</label>                               
                                <input type="text" name="nombre_proyecto" id="nombre_proyecto" class="form-control"  placeholder="Nombre">  
                              </div>                                                        
                            </div>

                            <!-- Ubicación (de la obra) -->
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="ubicacion">Ubicación <small> (de la obra) </small> </label>                               
                                <input type="text" name="ubicacion" id="ubicacion" class="form-control"  placeholder="Ubicación"> 
                              </div>                                                        
                            </div>

                            <!-- Actividad del trabajo -->
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="actividad_trabajo">Actividad del trabajo </label>
                                <input type="text" name="actividad_trabajo" id="actividad_trabajo" class="form-control" placeholder="Actividad del trabajo">
                              </div>
                            </div>

                            <!-- Fecha inicio/fin  -->
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="costo">Fecha inicio/fin</label>
                                <div class="input-group">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text">
                                      <i class="far fa-calendar-alt"></i>
                                    </span>
                                  </div>
                                  <input type="text" class="form-control float-right" name="fecha_inicio_fin" id="fecha_inicio_fin" onclick="calcular_palzo();" onchange="calcular_palzo();">
                                </div>
                              </div>
                            </div>

                            <!-- Actividad del trabajo -->
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="plazo">Plazo <small>(días calendario)</small></label>
                                <input type="text" name="plazo" id="plazo" class="form-control" placeholder="Plazo" readonly>
                              </div>
                            </div>

                            <!-- Costo total del proyecto -->
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="costo">Costo <small>("costo total del proyecto")</small></label>
                                <div class="input-group mb-3">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text">S/. </span>
                                  </div>
                                  <input type="number" step="0.10" name="costo" id="costo" class="form-control"  placeholder="Costo" min="1" >
                                </div>
                              </div>
                            </div>

                            <!-- Empresa a cargo -->
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="empresa_acargo">Empresa a cargo <small>("Seven's Ingenieros")</small></label>
                                <input type="text" name="empresa_acargo" id="empresa_acargo" class="form-control"  placeholder="Empresa a cargo" value="Seven's Ingenieros SAC">
                              </div>
                            </div>
                            
                            <!-- Pdf 1 -->
                            <div class="col-md-4" >                               
                              <div class="row text-center">
                                <div class="col-md-12" style="padding-top: 15px; padding-bottom: 5px;">
                                  <label for="cip" class="control-label" > Acta de contrato de obra </label>
                                </div>
                                <div class="col-md-6 text-center">
                                  <button type="button" class="btn btn-success btn-block" id="doc1_i">
                                    <i class="fas fa-file-upload"></i> Subir.
                                  </button>
                                  <input type="hidden" id="doc_old_1" name="doc_old_1" />
                                  <input style="display: none;" id="doc1" type="file" name="doc1" accept="application/pdf" class="docpdf" /> 
                                </div>
                                <div class="col-md-6 text-center">
                                  <button type="button" class="btn btn-info btn-block" onclick="PreviewImage();">
                                    <i class="fa fa-eye"></i> PDF.
                                  </button>
                                </div>
                              </div>                              
                              <div id="doc1_ver" class="text-center mt-4">
                                <img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >
                              </div>
                              <div class="text-center" id="doc1_nombre"><!-- aqui va el nombre del pdf --></div>
                            </div> 

                            <!-- Pdf 2 -->
                            <div class="col-md-4" >                               
                              <div class="row text-center">
                                <div class="col-md-12" style="padding-top: 15px; padding-bottom: 5px;">
                                  <label for="cip" class="control-label" > Acta de entrega de terreno</label>
                                </div>
                                <div class="col-md-6 text-center">
                                  <button type="button" class="btn btn-success btn-block" id="doc2_i">
                                    <i class="fas fa-file-upload"></i> Subir.
                                  </button>
                                  <input type="hidden" id="doc_old_2" name="doc_old_2" />
                                  <input style="display: none;" id="doc2" type="file" name="doc2" accept="application/pdf" class="docpdf" /> 
                                </div>
                                <div class="col-md-6 text-center">
                                  <button type="button" class="btn btn-info btn-block" onclick="PreviewImage();">
                                    <i class="fa fa-eye"></i> PDF.
                                  </button>
                                </div>
                              </div>                              
                              <div id="doc2_ver" class="text-center mt-4">
                                <img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >
                              </div>
                              <div class="text-center" id="doc2_nombre"><!-- aqui va el nombre del pdf --></div>
                            </div>
                            
                            <!-- Pdf 3 -->
                            <div class="col-md-4" >                               
                              <div class="row text-center">
                                <div class="col-md-12" style="padding-top: 15px; padding-bottom: 5px;">
                                  <label for="cip" class="control-label" > Acta de inicio de obra</label>
                                </div>
                                <div class="col-md-6 text-center">
                                  <button type="button" class="btn btn-success btn-block" id="doc3_i">
                                    <i class="fas fa-file-upload"></i> Subir.
                                  </button>
                                  <input type="hidden" id="doc_old_3" name="doc_old_3" />
                                  <input style="display: none;" id="doc3" type="file" name="doc3" accept="application/pdf" class="docpdf" /> 
                                </div>
                                <div class="col-md-6 text-center">
                                  <button type="button" class="btn btn-info btn-block" onclick="PreviewImage();">
                                    <i class="fa fa-eye"></i> PDF.
                                  </button>
                                </div>
                              </div>                              
                              <div id="doc3_ver" class="text-center mt-4">
                                <img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >
                              </div>
                              <div class="text-center" id="doc3_nombre"><!-- aqui va el nombre del pdf --></div>
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
        <?php          
          require 'script.php';
        ?>
         

        <script type="text/javascript" src="scripts/proyecto.js"></script>
        <!-- previzualizamos el pdf cargado -->
        <script type="text/javascript">
          function PreviewImage() {

            pdffile=document.getElementById("doc").files[0];

            antiguopdf=$("#docActual").val();

            if(pdffile === undefined){

              var dr = antiguopdf;

              if (dr == "") {

                $("#ver_pdf").html(''+
                  '<div class="alert alert-danger alert-dismissible">'+
                      '<button style="color: white !important;" type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'+
                      '<h4><i class="icon fa fa-warning"></i> Alerta!</h4>'+
                      'Seleciona un documento y luego PULSE el boton AMARILLO.'+
                  '</div>'
                );

              } else {

                $("#ver_pdf").html('<iframe src="'+dr+'" frameborder="0" scrolling="no" width="100%" height="210"></iframe>');
              }
              // console.log('hola'+dr);
            }else{

              pdffile_url=URL.createObjectURL(pdffile);

              $("#ver_pdf").html('<iframe src="'+pdffile_url+'" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');

              console.log('hola');
            }
          }
        </script>
      </body>
    </html>
    <?php    
  }
  ob_end_flush();
?>
