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
        <title>Pagos de Administradores | Admin Sevens</title>

        <?php $title = "Pagos de Administradores"; require 'head.php'; ?>
        
      </head>
      <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed pace-orange " idproyecto="<?php echo $_SESSION['idproyecto']; ?>">
        
        <div class="wrapper">
          <!-- Preloader -->
          <!-- <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="../dist/svg/logo-principal.svg" alt="AdminLTELogo" width="360" />
          </div> -->
        
          <?php
          require 'nav.php';
          require 'aside.php';
          if ($_SESSION['pago_trabajador']==1){
            //require 'enmantenimiento.php';
            ?>           
            <!--Contenido-->
            <div class="content-wrapper">
              <!-- Content Header (Page header) -->
              <div class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1 class="m-0 nombre-trabajador">Pagos de Administradores</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="pago_administrador.php">Pagos</a></li>
                        <li class="breadcrumb-item active">Pagos de Admin.</li>
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

                          <!-- regresar "tabla principal" -->
                          <h3 class="card-title mr-3" id="btn-regresar" style="display: none; padding-left: 2px;" >
                            <button type="button" class="btn bg-gradient-warning btn-sm" onclick="table_show_hide(1);"  ><i class="fas fa-arrow-left"></i> <span class="d-none d-sm-inline-block">Regresar</span> </button>
                          </h3>

                          <!-- regresar "tabla principal" -->
                          <h3 class="card-title mr-3" id="btn-regresar-todo" style="display: none; padding-left: 2px;" data-toggle="tooltip" data-original-title="Regresar a la tabla principal">
                            <button type="button" class="btn btn-block btn-outline-warning btn-sm" onclick="table_show_hide(1);"><i class="fas fa-arrow-left"></i></button>
                          </h3>
                          <!-- regresar "tabla fechas" -->
                          <h3 class="card-title mr-3" id="btn-regresar-bloque" style="display: none; padding-left: 2px;" data-toggle="tooltip" data-original-title="Regresar a la tabla fechas">
                            <button type="button" class="btn bg-gradient-warning btn-sm" onclick="table_show_hide(2); reload_table_fechas_mes();"  ><i class="fas fa-arrow-left"></i> <span class="d-none d-sm-inline-block">Regresar</span> </button>
                          </h3>
                          <!-- agregar pago  -->
                          <h3 class="card-title " id="btn-agregar" style="display: none; padding-left: 2px;" >
                            <button type="button" class="btn bg-gradient-success btn-sm" data-toggle="modal" data-target="#modal-agregar-pago-trabajdor" onclick="limpiar_pago_x_mes();">
                            <i class="fas fa-plus-circle"></i> Agregar pago 
                            </button>                     
                          </h3> 
                          
                          <h3 class="  " id="btn-nombre-mes" style="display: none; padding-left: 2px;" >&nbsp; - Enero </h3> 

                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                          <!-- tabla principal -->
                          <div class="row row-horizon disenio-scroll pb-3" id="tbl-principal">
                            <table id="tabla-principal" class="table table-bordered  table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>                                
                                  <th class="text-center">#</th> 
                                  <th>Trabajdor</th> 
                                  <th>Fecha inicio</th>
                                  <th>Hoy</th>
                                  <th class="text-center">Fecha <br> culminacion</th>
                                  <th class="text-center">Tiempo <br> trabajado (dias)</th>                                
                                  <th>Sueldo Mensual</th>
                                  <th class="text-center" data-toggle="tooltip" data-original-title="Pago total desde el dia inicial a final">Pago total</th>
                                  <th class="text-center" data-toggle="tooltip" data-original-title="Pago acumulado hasta hoy" >Pago <br> acumulado</th>
                                  <th class="text-center" data-toggle="tooltip" data-original-title="Depositos realizados" >Pago <br> realizado</th>                                
                                  <th data-toggle="tooltip" data-original-title="Saldo hasta hoy">Saldo</th>
                                  <th class="text-center" data-toggle="tooltip" data-original-title="Fecha pagada con anterioridad">Último <br> pago</th>
                                  <th class="text-center" data-toggle="tooltip" data-original-title="Fecha siguiente de pago">Pago <br> Siguiente</th>

                                  <th>Nombre</th>
                                  <th>Tipo Doc.</th>
                                  <th>Num. Doc.</th>
                                  <th>Tipo Trab.</th>
                                  <th>Cargo Trab.</th>
                                  <th>Pago realizado</th>
                                </tr>
                              </thead>
                              <tbody id="tbody-tabla-principal">                         
                                
                              </tbody>
                              <tfoot>
                                <tr> 
                                  <th class="text-center">#</th> 
                                  <th class="text-gray">Trabajdor</th> 
                                  <th class="text-gray">Fecha inicio</th>
                                  <th class="text-center text-gray">Hoy</th>
                                  <th class="text-center text-gray">Fecha <br> culminacion</th>
                                  <th class="text-center text-gray">Tiempo <br> trabajado (dias)</th>                                                                
                                  <th class="text-nowrap text-dark-0 pr-2"> <div class="formato-numero-conta "> <span>S/</span><span class="sueldo_total_tbla_principal">0.00</span> </div></th>
                                  <th class="text-nowrap text-dark-0 pr-2"><div class="formato-numero-conta "> <span>S/</span><span class="pago_total_tbla_principal">0.00</span> </div></th>                                
                                  <th class="text-nowrap text-dark-0 pr-2"><div class="formato-numero-conta "> <span>S/</span><span class="pago_hoy_total_tbla_principal">0.00</span> </div></th>
                                  <th class="text-nowrap text-dark-0 pr-2"><div class="formato-numero-conta "> <span>S/</span><span class="deposito_total_tbla_principal">0.00</span> </div></th>                                
                                  <th class="text-nowrap text-dark-0 pr-2"><div class="formato-numero-conta "> <span>S/</span><span class="saldo_total_tbla_principal">0.00</span> </div></th>  
                                  <th class="text-center text-gray">Último <br> pago</th>
                                  <th class="text-center text-gray">Siguiente <br> pago</th>

                                  <th>Nombre</th>
                                  <th>Tipo Doc.</th>
                                  <th>Num. Doc.</th>
                                  <th>Tipo Trab.</th>
                                  <th>Cargo Trab.</th>
                                  <th class="deposito_total_tbla_principal">S/ 0.00</th>
                                </tr>
                              </tfoot>
                            </table>
                          </div>                       

                          <!-- tabla fecha -->
                          <div class="table-responsive" id="tbl-fechas" style="display: none;">
                            <div class="table-responsive-lg disenio-scroll" >
                              <table class="table table-bordered /*table-striped*/ table-hover text-nowrap" style="border: black 1px solid;">
                                <thead >                                  
                                  <tr class="bg-gradient-info" >
                                    <th class="text-center">N°</th>
                                    <th class="text-center">Mes</th>
                                    <th colspan="2" class="text-center">Fechas Inicial/Final</th>
                                    <th class=" text-center">Días/Mes</th>
                                    <th class=" text-center">Sueldo</th>
                                    <th class="text-center">Monto</th>
                                    <th class="text-center">Pagar/Acumulado</th> 
                                    <th class="text-center">Saldo</th>
                                    <th class="text-center" data-toggle="tooltip" data-original-title="Recibos por Honorarios">R/H</th> 
                                  </tr>
                                </thead>
                                <tbody class="data-fechas-mes">
                                  <tr>
                                    <td>1</td>
                                    <td>Enero </td>
                                    <td>12-01-2022</td> 
                                    <td>31-01-2022</td>
                                    <td>19</td>
                                    <td> S/ 4400.00</td>
                                    <td> S/ 2696.77</td>
                                    <td>
                                      <button class="btn btn-info btn-sm" onclick="listar_tbla_pagos_x_mes(1);"><i class="fas fa-dollar-sign"></i> Pagar</button>
                                      <button style="font-size: 14px;" class="btn btn-danger btn-sm">S/ 900.00</button></div>
                                    </td>
                                  </tr>                        
                                </tbody>
                                <tfoot>
                                  <tr>     
                                    <th class="" ></th> 
                                    <th class="text-center cant_meses_total" >0 meses</th> 
                                    <th colspan="2" class="" ></th>
                                    <th class="text-center dias_x_mes_total">0</th>
                                    <th class=" "> </th>
                                    <th class=" "><div class="formato-numero-conta monto_x_mes_total"> <span>S/</span>0.00</div></th> 
                                    <th class=" "><div class="formato-numero-conta monto_x_mes_pagado_total"> <span>S/</span>S/ 0.00</div></th>
                                    <th class=" "><div class="formato-numero-conta saldo_total"> <span>S/</span>S/ 0.00</div></th> 
                                    <th class="text-center rh_total">0 <small>(docs)</small> </th>                          
                                  </tr>
                                </tfoot>
                              </table>
                            </div>
                          </div>      
                          
                          <!-- tabla ingresos de pagos -->
                          <div class=" " id="tbl-ingreso-pagos" style="display: none !important;">
                            <table id="tabla-ingreso-pagos" class="table table-bordered  table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr> 
                                  <th class="text-center">#</th>
                                  <th>Opciones</th>
                                  <th>Fecha deposito</th>
                                  <th>Cuenta depósito</th> 
                                  <th>Forma de pago</th>
                                  <th>Monto</th>
                                  <th>Baucher</th>
                                  <th>RH</th>
                                  <th>Descripcion</th> 
                                  <th>Estado</th>                                                        
                                </tr>
                              </thead>
                              <tbody>                         
                                
                              </tbody>
                              <tfoot>
                                <tr> 
                                  <th class="text-center">#</th>
                                  <th>Opciones</th>
                                  <th>Fecha deposito</th>
                                  <th>Cuenta depósito</th>
                                  <th>Forma de pago</th>
                                  <th>Monto</th>
                                  <th>Baucher</th>
                                  <th>RH</th>
                                  <th>Descripcion</th> 
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

                <!-- Modal agregar PAGOS POR MES -->
                <div class="modal fade" id="modal-agregar-pago-trabajdor">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Agregar pago: <b class="nombre_de_trabajador_modal"> <!-- NOMBRE DEL TRABAJDOR--> </b></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      
                      <div class="modal-body">
                        <!-- form start -->
                        <form class="mx-2" id="form-pagos-x-mes" name="form-pagos-x-mes"  method="POST" >                      
                          
                          <div class="row" id="cargando-1-fomulario">

                            <!-- id idpagos_x_mes_administrador -->
                            <input type="hidden" name="idpagos_x_mes_administrador" id="idpagos_x_mes_administrador" />

                            <!-- id idfechas_mes_pagos_administrador -->
                            <input type="hidden" name="idfechas_mes_pagos_administrador_pxm" id="idfechas_mes_pagos_administrador_pxm" />
                            <!-- id_tabajador_x_proyecto -->
                            <input type="hidden" name="id_tabajador_x_proyecto_pxm" id="id_tabajador_x_proyecto_pxm" />
                            <!-- fecha inicial -->
                            <input type="hidden" name="fecha_inicial_pxm" id="fecha_inicial_pxm" />
                            <!-- fecha final -->
                            <input type="hidden" name="fecha_final_pxm" id="fecha_final_pxm" />
                            <!-- nombre del mes -->
                            <input type="hidden" name="mes_nombre_pxm" id="mes_nombre_pxm" />
                            <!-- dias del mes -->
                            <input type="hidden" name="dias_mes_pxm" id="dias_mes_pxm" />
                            <!-- dias_regular -->
                            <input type="hidden" name="dias_regular_pxm" id="dias_regular_pxm" />
                            <!-- sueldo_mensual -->
                            <input type="hidden" name="sueldo_mensual_pxm" id="sueldo_mensual_pxm" />
                            <!-- monto_x_mes -->
                            <input type="hidden" name="monto_x_mes_pxm" id="monto_x_mes_pxm" />                            

                            <!-- Descripcion Pensión-->
                            <div class="col-12 pl-0">
                              <div class="text-primary"><label for="">DETALLES PAGO</label></div>
                            </div>

                            <div class="card col-12 px-3 py-3" style="box-shadow: 0 0 1px rgb(0 0 0), 0 1px 3px rgb(0 0 0 / 60%); ">
                              <div class="row">
                                <!-- Forma de pago hacia el trabajdor -->
                                <div class="col-lg-6">
                                  <div class="form-group">
                                  <label for="forma_pago">Forma Pago</label>
                                  <select name="forma_pago" id="forma_pago" class="form-control select2" style="width: 100%;">
                                    <option value="Transferencia">Transferencia</option>
                                    <option value="Efectivo">Efectivo</option>
                                  </select>
                                  </div>
                                </div>

                                <!-- Cuenta deposito enviada -->
                                <div class="col-lg-6">
                                  <div class="form-group">
                                    <label for="cuenta_deposito">Cuenta deposito <small>(del trabajdor)</small> </label>                               
                                    <input type="text" name="cuenta_deposito" id="cuenta_deposito" class="form-control"  placeholder="Cuenta deposito">  
                                  </div>                                                        
                                </div>

                                <!-- Monto (de cantidad a depositado) -->
                                <div class="col-lg-6">
                                  <div class="form-group">
                                    <label for="monto">Monto <small> (Monto a pagar) </small> </label>                               
                                    <input type="number" name="monto" id="monto" class="form-control"  placeholder="Monto a pagar"> 
                                  </div>                                                        
                                </div>

                                <!-- Fecha de deposito -->
                                <div class="col-lg-6">
                                  <div class="form-group">
                                    <label for="fecha_pago">Fecha de deposito </label>                               
                                    <input class="form-control" type="date" id="fecha_pago" name="fecha_pago" /> 
                                  </div>                                                        
                                </div>
                                
                                <!-- Mes del pago -->
                                <div class="col-lg-3">
                                  <div class="form-group">
                                    <label for="nombre_mes" class="text-gray">Mes </label>
                                    <span class="nombre_mes_modal text-gray form-control"> <sup>S/</sup> 0.00</span>
                                  </div>
                                </div>

                                <!-- Monto faltante -->
                                <div class="col-lg-3">
                                  <div class="form-group">
                                    <label for="nombre_mes" class="text-gray">Faltante </label>
                                    <span class="faltante_mes_modal text-gray form-control"> <sup>S/</sup> 0.00</span>
                                  </div>
                                </div>
                              </div>
                            </div>  
                            
                            <!-- Descripcion Pensión-->
                            <div class="col-12 pl-0">
                              <div class="text-primary"><label for="">RECIBO POR HONORARIO</label></div>
                            </div>

                            <div class="card col-12 px-3 py-3" style="box-shadow: 0 0 1px rgb(0 0 0), 0 1px 3px rgb(0 0 0 / 60%);">
                              <div class="row">
                                <!--N° de Comprobante -->
                                <div class="col-lg-6">
                                  <div class="form-group">
                                    <label for="numero_comprobante">N° de Comprobante  </label>                               
                                    <input type="text" name="numero_comprobante" id="numero_comprobante" class="form-control"  placeholder="N° de Comprobante">  
                                  </div>                                                        
                                </div>

                                <!-- tipo comprobante -->
                                <div class="col-lg-6">
                                  <div class="form-group">
                                    <label for="">Tipo Comprobante  </label>    
                                    <span class="form-control input-valido">Recibo por Honorario</span>
                                  </div>                                                        
                                </div>  

                                <!-- glosa -->
                                <div class="col-lg-12">
                                  <div class="form-group">
                                    <label for="">Glosa </label>    
                                    <span class="form-control input-valido">PERSONAL TÉCNICO</span>
                                  </div>                                                        
                                </div>  
                                
                                <!-- Descripcion-->
                                <div class="col-lg-12">
                                  <div class="form-group">
                                    <label for="descripcion">Descripción </label> <br>
                                    <textarea name="descripcion" id="descripcion" class="form-control" rows="2"></textarea>
                                  </div>                                                        
                                </div>
                              </div>
                            </div>                            
                            
                            <!-- Pdf 1 -->
                            <div class="col-12 col-md-6 col-lg-6" >                               
                              <div class="row text-center">
                                <div class="col-md-12" style="padding-top: 15px; padding-bottom: 5px;">
                                  <label for="cip" class="control-label" > Baucher de deposito </label>
                                </div>
                                <div class="col-6 col-md-6 text-center">
                                  <button type="button" class="btn btn-success btn-block btn-xs" id="doc1_i">
                                    <i class="fas fa-upload"></i> Subir.
                                  </button>
                                  <input type="hidden" id="doc_old_1" name="doc_old_1" />
                                  <input style="display: none;" id="doc1" type="file" name="doc1" accept="application/pdf, image/*" class="docpdf" /> 
                                </div>
                                <div class="col-6 col-md-6 text-center">
                                  <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(1, 'pago_administrador', 'baucher_deposito');">
                                  <i class="fas fa-redo"></i> Recargar.
                                  </button>
                                </div>
                              </div>                              
                              <div id="doc1_ver" class="text-center mt-4">
                                <img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >
                              </div>
                              <div class="text-center" id="doc1_nombre"><!-- aqui va el nombre del pdf --></div>
                            </div>

                            <!-- Pdf 2 -->
                            <div class="col-12 col-md-6 col-lg-6">
                              <div class="row text-center">        
                                <div class="col-md-12" style="padding-top: 15px; padding-bottom: 5px;">
                                  <label for="cip" class="control-label" > Recibo po Honorario </label>
                                </div>                       
                                <!-- Subir documento -->
                                <div class="col-6 col-md-6 text-center">
                                  <button type="button" class="btn btn-success btn-block btn-xs" id="doc2_i">
                                    <i class="fas fa-upload"></i> Subir.
                                  </button>
                                  <input type="hidden" id="doc_old_2" name="doc_old_2" />
                                  <input style="display: none;" id="doc2" type="file" name="doc2" accept="application/pdf, image/*" class="docpdf" /> 
                                </div>
                                <!-- Recargar -->
                                <div class="col-6 col-md-6 text-center comprobante">
                                  <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(2,'pago_administrador', 'recibos_x_honorarios');">
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
                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>
                              <h4>Cargando...</h4>
                            </div>
                          </div>                            
                                               
                          <button type="submit" style="display: none;" id="submit-form-pagos-x-mes">Submit</button>                      
                        </form>
                        <!-- /.form -->
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro">Guardar Cambios</button>
                      </div>                  
                    </div>
                  </div>
                </div>

                <!-- MODAL - LISTA DE RH -->
                <div class="modal fade" id="modal-tabla-recibo-por-honorario">
                  <div class="modal-dialog modal-dialog-scrollable modal-xl">
                    <div class="modal-content">
                      <div class="modal-header"> 
                        <h4 class="modal-title titulo-tabla-rh">Lista de RH</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body ">
                        <div class="row">
                          
                          <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12 mt-3">
                            <table id="tabla-recibo-por-honorario" class="table table-bordered table-striped display " style="width: 100% !important;">
                              <thead>
                                <tr> 
                                  <th class="text-center">#</th> 
                                  <th>Descripcion</th> 
                                  <th>Fecha</th> 
                                  <th>Monto</th> 
                                  <th>Comprobante</th>
                                  <th>Baucher</th>
                                  <th>RH</th>                                                   
                                </tr>
                              </thead>
                              <tbody>  </tbody>
                              <tfoot>
                                <tr> 
                                  <th class="text-center">#</th> 
                                  <th>Descripcion</th> 
                                  <th>Fecha</th> 
                                  <th>Monto</th> 
                                  <th>Comprobante</th>
                                  <th>Baucher</th>
                                  <th>RH</th>                        
                                </tr>
                              </tfoot>
                            </table>
                          </div>
                        </div>
                      </div> 
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- MODAL - VER PAGO POR PERSONA -->
                <div class="modal fade" id="modal-tabla-pago-x-persona">
                  <div class="modal-dialog modal-dialog-scrollable modal-xl">
                    <div class="modal-content">
                      <div class="modal-header"> 
                        <h4 class="modal-title titulo-tabla-rh">Lista de Pagos</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body ">                          
                          
                        <table id="tabla-pago-x-persona" class="table table-bordered table-striped display " style="width: 100% !important;">
                          <thead>
                            <tr> 
                              <th class="text-center">#</th> 
                              <th>OP</th> 
                              <th>Fecha Pago</th> 
                              <th>Tipo</th> 
                              <th>Numero</th>
                              <th>Monto</th>     
                              <th>Estado</th>                                        
                            </tr>
                          </thead>
                          <tbody>  </tbody>
                          <tfoot>
                            <tr> 
                              <th class="text-center">#</th> 
                              <th>OP</th> 
                              <th>Fecha Pago</th> 
                              <th>Tipo</th> 
                              <th>Numero</th>
                              <th>Monto</th>  
                              <th>Estado</th>               
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
                <!-- Modal recibo por honorarios - ----------------- NO SE USA --------------------------- -->
                <div class="modal fade" id="modal-recibos-x-honorarios">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title titulo_modal_recibo_x_honorarios">R/H: </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form id="form-recibos_x_honorarios" name="form-recibos_x_honorarios" method="POST">
                          
                          <div class="row" id="cargando-3-fomulario">
                            <!-- id idfechas_mes_pagos_administrador -->
                            <input type="hidden" name="idfechas_mes_pagos_administrador_rh" id="idfechas_mes_pagos_administrador_rh" />
                            <!-- id_tabajador_x_proyecto -->
                            <input type="hidden" name="id_tabajador_x_proyecto_rh" id="id_tabajador_x_proyecto_rh" />
                            <!-- fecha inicial -->
                            <input type="hidden" name="fecha_inicial_rh" id="fecha_inicial_rh" />
                            <!-- fecha final -->
                            <input type="hidden" name="fecha_final_rh" id="fecha_final_rh" />
                            <!-- nombre del mes -->
                            <input type="hidden" name="mes_nombre_rh" id="mes_nombre_rh" />
                            <!-- dias del mes -->
                            <input type="hidden" name="dias_mes_rh" id="dias_mes_rh" />
                            <!-- dias_regular -->
                            <input type="hidden" name="dias_regular_rh" id="dias_regular_rh" />
                            <!-- sueldo_mensual -->
                            <input type="hidden" name="sueldo_mensual_rh" id="sueldo_mensual_rh" />
                            <!-- monto_x_mes -->
                            <input type="hidden" name="monto_x_mes_rh" id="monto_x_mes_rh" />
                            
                            <!-- Pdf 2 -->
                            <div class="col-md-12 col-lg-12">
                              <div class="row text-center">                               
                                <!-- Subir documento -->
                                <div class="col-6 col-md-3 text-center">
                                  <button type="button" class="btn btn-success btn-block btn-xs" id="doc3_i">
                                    <i class="fas fa-upload"></i> Subir.
                                  </button>
                                  <input type="hidden" id="doc_old_3" name="doc_old_3" />
                                  <input style="display: none;" id="doc3" type="file" name="doc3" accept="application/pdf, image/*" class="docpdf" /> 
                                </div>
                                <!-- Recargar -->
                                <div class="col-6 col-md-3 text-center comprobante">
                                  <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(3,'pago_administrador', 'recibos_x_honorarios');">
                                  <i class="fas fa-redo"></i> Recargar.
                                </button>
                                </div>
                                <!-- Dowload -->
                                <div class="col-6 col-md-3 text-center descargar" style="display: none;">
                                  <a type="button" class="btn btn-warning btn-block btn-xs" id="descargar_rh" download="Recibo-por-honorario"> <i class="fas fa-download"></i> Descargar. </a>
                                </div>
                                <!-- Ver grande -->
                                <div class="col-6 col-md-3 text-center ver_completo" style="display: none;">
                                  <a type="button" class="btn btn-info btn-block btn-xs " target="_blank" id="ver_completo"> <i class="fas fa-expand"></i> Ver completo. </a>
                                </div>
                              </div>

                              <div id="doc3_ver" class="text-center mt-4">
                                <img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >
                              </div>
                              <div class="text-center" id="doc3_nombre"><!-- aqui va el nombre del pdf --></div>
                            </div>

                            <!-- barprogress -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 20px;">
                              <div class="progress" id="div_barra_progress2">
                                <div id="barra_progress2" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
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
                          
                          <!-- /.card-body -->
                          <button type="submit" style="display: none;" id="submit-form-recibo-x-honorario">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro_recibo-x-honorario">Guardar Cambios</button>
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

        <script type="text/javascript" src="scripts/pago_administrador.js?version_jdl=1.9"></script>
        
        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }) </script>
        
      </body>
    </html>
    <?php    
  }
  ob_end_flush();
?>
