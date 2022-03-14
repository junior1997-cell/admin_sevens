<?php
  //Activamos el almacenamiento en el buffer
  ob_start();
  session_start();

  if (!isset($_SESSION["nombre"])){
    header("Location: index.php");
  }else{ ?>
    <!DOCTYPE html>
    <html lang="es">
      <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Admin Sevens | Resumen de Factura</title>
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
            if ($_SESSION['resumen_factura']==1){
          ?>           
          <!--Contenido-->
          <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
              <div class="container-fluid">
                <div class="row mb-2">
                  <div class="col-sm-6">
                    <h1 class="m-0 nombre-trabajador">
                      Resumen de Factura 
                      <button class="btn btn-success btn-md btn-zip" onclick="desccargar_zip_comprobantes();">
                        <i class="far fa-file-archive fa-lg"></i> Comprobantes .zip 
                      </button>
                    </h1>
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="resumen_factura.php">Home</a></li>
                      <li class="breadcrumb-item active">Resumen de Factura</li>
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
                        <div class="row">

                          <!-- filtro por: fecha -->
                          <div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
                            <label for="filtros" >Fecha inicio </label>
                            <!-- fecha inicial -->
                            <input name="fecha_filtro" id="fecha_filtro_1" type="date" class="form-control form-control-sm m-b-1px" placeholder="Seleccionar fecha" onchange="filtros()" />
                          </div>
                          <div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
                            <label for="filtros" >Fecha fin </label>
                            <!-- fecha final -->
                            <input name="fecha_filtro" id="fecha_filtro_2" type="date" class="form-control form-control-sm" placeholder="Seleccionar fecha" onchange="filtros()" />
                          </div>

                          <!-- filtro por: proveedor -->
                          <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                            <label for="filtros" class="cargando_proveedor">Proveedor &nbsp;<i class="text-dark fas fa-spinner fa-pulse fa-lg"></i><br /></label>
                            <select name="proveedor_filtro" id="proveedor_filtro" class="form-control select2" onchange="filtros()" style="width: 100%;"> 
                            </select>
                          </div>

                          <!-- filtro por: proveedor -->
                          <div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
                            <label for="filtros" >Tipo comprobante </label>
                            <select name="tipo_comprobante_filtro" id="tipo_comprobante_filtro" class="form-control select2" onchange="filtros()" style="width: 100%;"> 
                              <option value="0">Todos</option>
                              <option value="Factura">Factura</option>
                              <option value="Boleta">Boleta</option>
                            </select>
                          </div>

                        </div>                        
                      </div>
                      <!-- /.card-header -->
                      <div class="card-body">

                        <!-- tabla resumen facturas compras -->
                        <div class="pb-3">
                          <h3 class="card-title mb-2">Resumen facturas: <b>Compras</b>    </h3>
                          <table id="tabla-principal" class="table table-bordered  table-striped display" style="width: 100% !important;">
                            <thead>
                              <tr>
                                <th colspan="12" class="cargando text-center bg-danger"><i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando... </th>
                              </tr>
                              <tr> 
                                <th class="text-center">#</th> 
                                <th class="text-center">Fecha</th>
                                <th>Comprobante</th>
                                <th>Documento</th>
                                <th>Razón social</th>
                                <th class="text-center">Total</th> 
                                <th class="text-center">Subtotal</th>                                
                                <th class="text-center">IGV</th>
                                <th class="text-center">Glosa</th> 
                                <th class="text-center">Operación</th> 
                                <th class="text-center">CFDI.</th>
                                <th class="text-center">Módulo</th>                                                     
                              </tr>
                            </thead>
                            <tbody> </tbody>
                            <tfoot> 
                              <tr> 
                                <th class="text-center text-black-50">#</th> 
                                <th class="text-center text-black-50">Fecha</th>
                                <th class="text-black-50">Comprobante</th>
                                <th class="text-black-50">Documento</th>
                                <th class="text-black-50">Razón social</th>
                                <th class="text-right text-nowrap total-total">Total</th>  
                                <th class="text-right text-nowrap total-subtotal">Subtotal</th>                                
                                <th class="text-right text-nowrap total-igv">IGV</th>
                                <th class="text-center text-black-50">Glosa</th> 
                                <th class="text-center text-black-50">Operación</th> 
                                <th class="text-center text-black-50">CFDI.</th>
                                <th class="text-center">Módulo</th>                                             
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
              
              <!-- MODAL - COMPROBANTE  -->
              <div class="modal fade" id="modal-ver-comprobante">
                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Comprobante</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body ver-comprobante">
                      <!-- detalle de la factura -->
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
        <script src="../plugins/jszip/jszip.js"></script>
        <script src="../plugins/jszip/dist/jszip-utils.js"></script>
        <script src="../plugins/FileSaver/dist/FileSaver.js"></script>
        <script type="text/javascript" src="scripts/resumen_factura.js"></script>
        <!-- <script type="text/javascript" src="scripts/verificar_url.js"></script> -->

         
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
