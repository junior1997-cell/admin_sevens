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
        <title>Admin Sevens | Compras</title>
        <?php
          require 'head.php';
        ?>
        <!-- Theme style -->
        <!-- <link rel="stylesheet" href="../dist/css/adminlte.min.css"> -->
      </head>
      <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed ">
        
        <div class="wrapper">
          <!-- Preloader -->
          <!-- <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="../dist/svg/logo-principal.svg" alt="AdminLTELogo" width="360" />
          </div> -->
        
          <?php
            require 'nav.php';
            require 'aside.php';
            if ($_SESSION['compra']==1){
          ?>           
          <!--Contenido-->
          <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
              <div class="container-fluid">
                <div class="row mb-2">
                  <div class="col-sm-6">
                    <h1 class="m-0">Compras</h1>
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="compra.php">Home</a></li>
                      <li class="breadcrumb-item active">Compras</li>
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
                  <div class="col-12">
                    <div class="card card-primary card-outline">
                      <div class="card-header">
                        <h3 class="card-title " >
                          <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-agregar-compra" onclick="limpiar();">
                          <i class="fas fa-plus-circle"></i> Agregar
                          </button>
                          Compras                        
                        </h3>                      
                      </div>
                      <!-- /.card-header -->
                      <div class="card-body">
                        <table id="tabla-compra" class="table table-bordered table-striped display" style="width: 100% !important;">
                          <thead>
                            <tr>
                              <th class="">Aciones</th>
                              <th>Empresa</th>
                              <th>Nombre de proyecto</th>
                              <th>Ubicación</th>
                              <th>Costo</th>
                              <th>Docs</th>
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
                              <th>Docs</th>
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
              <div class="modal fade" id="modal-agregar-compra">
                <div class="modal-dialog /*modal-dialog-scrollable*/ modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Agregar Compra</h4>
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

                            <!-- Tipo de Empresa -->
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="tipo_documento">Empresa</label>
                                <select name="tipo_documento" id="tipo_documento" class="form-control"  placeholder="Tipo de documento">
                                  <option selected value="DNI">SEVEN´S INGENIEROS S.A.C.</option>
                                  <option value="RUC">SEVEN´S INGENIEROS S.A.C.</option>
                                  <option value="CEDULA">SEVEN´S INGENIEROS S.A.C.</option>
                                  <option value="OTRO">SEVEN´S INGENIEROS S.A.C.</option>
                                </select>
                              </div>
                            </div>
                           <!-- fecha -->
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="fecha">Fecha</small> </label>                               
                                <input type="date" name="fecha" id="fecha" class="form-control"  placeholder="fecha">  
                              </div>                                                        
                            </div>
                            <!-- Monto-->
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="monto">Monto </label>                               
                                <input type="text" name="monto" id="monto" class="form-control"  placeholder="monto"> 
                              </div>                                                        
                            </div> 

                            <!--  descripcion -->
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label for="descripcion">Descripción</label>                               
                                <input type="text" name="descripcion" id="descripcion" class="form-control"  placeholder="descripcion">  
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
                        <div class="col-md-6 mb-4" >      
                          <div id="verdoc1" class="text-center">
                            <i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>
                            <h4>Cargando...</h4>
                          </div>
                          <div class="text-center" id="verdoc1_nombre">
                            <!-- aqui va el nombre del pdf -->
                          </div>
                        </div> 

                        <!-- Pdf 2 -->
                        <div class="col-md-6 mb-4" >                            
                          <div id="verdoc2" class="text-center">
                            <i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>
                            <h4>Cargando...</h4>
                          </div>
                          <div class="text-center" id="verdoc2_nombre">
                            <!-- aqui va el nombre del pdf -->
                          </div>
                        </div>
                        
                        <!-- Pdf 3 -->
                        <div class="col-md-12 mb-4" >                             
                          <div id="verdoc3" class="text-center">
                            <i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>
                            <h4>Cargando...</h4>
                          </div>
                          <div class="text-center" id="verdoc3_nombre">
                            <!-- aqui va el nombre del pdf -->
                        </div>
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
                      <div class="row" id="cargando-detalle-proyecto">
                        <div class="col-lg-12 text-center">
                          <i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>
                          <h4>Cargando...</h4>
                        </div>
                      </div>
                      <!-- /.card-body -->
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
         

        <script type="text/javascript" src="scripts/compra.js"></script>
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
        <script>
          $(function () {
            $('[data-toggle="tooltip"]').tooltip();
          })
        </script>

        <script>
          if ( localStorage.getItem('nube_idproyecto') ) {

            console.log("icon_folder_"+localStorage.getItem('nube_idproyecto'));

            $("#ver-proyecto").html('<i class="fas fa-tools"></i> Proyecto: ' +  localStorage.getItem('nube_nombre_proyecto'));

            $("#ver-otros-modulos-1").show();

            // $('#icon_folder_'+localStorage.getItem('nube_idproyecto')).html('<i class="fas fa-folder-open"></i>');

          }else{
            $("#ver-proyecto").html('<i class="fas fa-tools"></i> Selecciona un proyecto');

            $("#ver-otros-modulos-1").hide();
          }
          
        </script>
      </body>
    </html>
    <?php    
  }
  ob_end_flush();
?>
