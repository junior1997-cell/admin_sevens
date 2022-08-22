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
        <title>Pagos de Obrero | Admin Sevens</title>

        <?php $title = "Pagos de Obrero"; require 'head.php'; ?>

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
          if ($_SESSION['pago_trabajador']==1){
            //require 'enmantenimiento.php';
            ?>           
            <!--Contenido-->
            <div class="content-wrapper ">
              <!-- Content Header (Page header) -->
              <div class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1 class="m-0 nombre-trabajador">Pagos de Obreros</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="pago_obrero.php">Pagos</a></li>
                        <li class="breadcrumb-item active">Pagos de Obreros</li>
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
                            <button type="button" class="btn bg-gradient-warning btn-sm" onclick="table_show_hide(2); reload_table_detalle_x_q_s();"  ><i class="fas fa-arrow-left"></i> <span class="d-none d-sm-inline-block">Regresar</span> </button>
                          </h3>
                          <!-- agregar pago  -->
                          <h3 class="card-title " id="btn-agregar" style="display: none; padding-left: 2px;" >
                            <button type="button" class="btn bg-gradient-success btn-sm" data-toggle="modal" data-target="#modal-agregar-pago-trabajdor" onclick="limpiar_pago_q_s();">
                            <i class="fas fa-plus-circle"></i> Agregar pago 
                            </button>                     
                          </h3>                           
                          <h3 class="  " id="btn-nombre-mes" style="display: none; padding-left: 2px;" >&nbsp; - Enero </h3> 

                          <!-- Quincena actual-->
                          <div class="row-horizon disenio-scroll hidden" id="div_btn_quincenas_semanas">
                            <h3 class="card-title mr-2" id="btn_q_s_actual" style="padding-left: 2px;">                              
                              <div class="alert alert-danger alert-dismissible bg-white text-black">
                                <button type="button" class="close text-danger" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <i class="icon fas fa-exclamation-triangle text-danger"></i> No hay semana actual.                                
                              </div>
                            </h3>
                            <!-- Quincenas o Semanas -->
                            <div id="btn_quincenas_semanas"  >
                            </div>
                          </div>

                          <div class="btn_cargando_s_q ">
                            <div class="my-3" ><i class="fas fa-spinner fa-pulse fa-2x"></i>&nbsp;&nbsp;&nbsp;Cargando...</div>
                          </div>
                          
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">

                          <!-- tabla: principal -->
                          <div class="row row-horizon disenio-scroll pb-3" id="tbl-principal">
                            <table id="tabla-principal" class="table table-bordered  table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr> 
                                  <th class="text-center">#</th> 
                                  <th>Trabajdor</th>                              
                                  <th>Banco</th>                                
                                  <th>Cuenta</th>   
                                  <th class="text-center">Horas <br> Normal/Extra</th>
                                  <th data-toggle="tooltip" data-original-title="Sabatical">Sab.</th>                              
                                  <th >Sueldo Mensual</th>                                
                                  <th class="text-center" data-toggle="tooltip" data-original-title="Pagos que a estado acumulando con sus dias de asistencia.">Pago a <br> realizar</th>
                                  <th class="text-center" data-toggle="tooltip" data-original-title="Despositos que se ha estado enviando a trabajador.">Pago <br> Acumulado</th>
                                  <th>Saldo</th>
                                  <th class="text-center" data-toggle="tooltip" data-original-title="Cantidad de semanas enviadas a pagar.">Cant <br> S/Q</th>
                                  <th>Fecha inicio</th>
                                  <th>Hoy</th>
                                  <th class="text-center">Fecha <br> culminacion</th>

                                  <th>Trabajador</th>  
                                  <th>Cargo</th>
                                  <th>Tipo</th>
                                  <th>Tipo Doc</th>
                                  <th>Num. Doc.</th>
                                  <th>Hora Normal</th>
                                  <th>Hora Extra</th>
                                  <th>Pago Acumulado</th>
                                </tr>
                              </thead>
                              <tbody id="tbody-tabla-principal">   </tbody>
                              <tfoot>
                                <tr> 
                                  <th class="text-center text-gray"><small>#</small></th>                                 
                                  <th class="text-gray"><small>Trabajdor</small></th>                                                              
                                  <th class="text-gray"><small>Banco</small></th>                                
                                  <th class="text-gray"><small>Cuenta</small></th>
                                  <th class="text-center text-gray"><small>Horas Nrm/Extr</small></th>
                                  <th class="total_tbla_principal_sabatical"><i class="fas fa-spinner fa-pulse fa-sm"></i></th>                           
                                  <th class="text-center text-gray"><small>Sueldo Mensual</small></th>                                
                                  <th class="pr-2" ><div class="formato-numero-conta "><span>S/</span> <span class="total_tbla_principal_pago"><i class="fas fa-spinner fa-pulse fa-sm"></i></span></div></th>
                                  <th class="pr-2" ><div class="formato-numero-conta "><span>S/</span> <span class="total_tbla_principal_deposito"><i class="fas fa-spinner fa-pulse fa-sm"></i></span></div></th>
                                  <th class="pr-2" ><div class="formato-numero-conta "><span>S/</span> <span class="total_tbla_principal_saldo"><i class="fas fa-spinner fa-pulse fa-sm"></i></span></div></th>
                                  <th class="text-center"><span class="total_tbla_principal_cant_s_q"><i class="fas fa-spinner fa-pulse fa-sm"></i></span></th>
                                  <th class="text-center text-gray"><small>Fecha inicio</small></th>
                                  <th class="text-center text-gray"><small>Hoy</small></th>
                                  <th class="text-center text-gray"><small>Fecha fin</small></th>        
                                  
                                  <th>Trabajador</th>
                                  <th>Cargo</th>
                                  <th>Tipo</th>
                                  <th>Tipo Doc</th>
                                  <th>Num. Doc.</th>
                                  <th>Hora Normal</th>
                                  <th>Hora Extra</th> 
                                  <th ><span>S/</span> <span class="total_tbla_principal_deposito"></span></th>
                                </tr>
                              </tfoot>
                            </table>
                          </div>                       

                          <!-- tabla: quincena - semana -->
                          <div class="table-responsive" id="tbl-fechas" style="display: none;">
                            <div class="row-horizon disenio-scroll" >
                              <table class="table table-bordered /*table-striped*/ table-hover text-nowrap" >
                                <thead>                                  
                                  <tr class="text-center bg-gradient-info">
                                    <th rowspan="2" class="">N°</th>                                   
                                    <th colspan="3" class="pt-0 pb-0 nombre-bloque-asistencia">Semana </th>
                                    <th rowspan="2" class="">Sueldo Hora</th>
                                    <th rowspan="2" class="">Horas Normal/Extra</th>
                                    <th rowspan="2" class="">Sabatical</th>
                                    <th rowspan="2" class="">Monto Normal/Extra</th>
                                    <th rowspan="2" class="">Adicional</th>                                  
                                    <th rowspan="2" class="">Monto total</th>
                                    <th rowspan="2" class="">Pagar/Acumulado</th> 
                                    <th rowspan="2" class="">Saldo</th>
                                    <th rowspan="2" class="" data-toggle="tooltip" data-original-title="Recibos por Honorarios">R/H</th>
                                  </tr>
                                  <tr class="text-center bg-gradient-info">                                                                     
                                    <th class="pt-0 pb-0">N°</th>
                                    <th class="pt-0 pb-0">Inicial</th>
                                    <th class="pt-0 pb-0">Final</th>
                                  </tr>
                                </thead>
                                <tbody class="data-q-s">                                  
                                                                
                                </tbody>
                                <tfoot>
                                  <tr>                                    
                                    <th colspan="5" ></th> 
                                    <th class="text-center total_hn_he"></th>
                                    <th class="text-center total_sabatical"></th>
                                    <th> <div class="formato-numero-conta total_monto_hn_he"><i class="fas fa-spinner fa-pulse fa-sm"></i></div></th> 
                                    <th> <div class="formato-numero-conta"><span>S/</span> <span class="total_descuento"><i class="fas fa-spinner fa-pulse fa-sm"></i></span></div></th>
                                    <th> <div class="formato-numero-conta"><span>S/</span> <span class="total_quincena"><i class="fas fa-spinner fa-pulse fa-sm"></i></span></div></th> 
                                    <th> <div class="formato-numero-conta"><span>S/</span> <span class="total_deposito"><i class="fas fa-spinner fa-pulse fa-sm"></i></span></div></th>                           
                                    <th> <div class="formato-numero-conta"><span>S/</span> <span class="total_saldo"><i class="fas fa-spinner fa-pulse fa-sm"></i></span></div></th> 
                                    <th class="text-center rh_total"><i class="fas fa-spinner fa-pulse fa-sm"></i></th>
                                  </tr>
                                </tfoot>
                              </table>
                            </div>
                          </div>      
                          
                          <!-- tabla: ingresos de pagos -->
                          <div class=" " id="tbl-ingreso-pagos" style="display: none !important;">
                            <table id="tabla-ingreso-pagos" class="table table-bordered  table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr> 
                                  <th class="text-center">#</th> 
                                  <th>Op.</th> 
                                  <th>Fecha depósito</th> 
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
                                  <th>Op.</th> 
                                  <th>Fecha depósito</th> 
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
                          
                          <!-- tabla: pago multiple obrero quincena - semana -->
                          <div class="table-responsive" id="tbl-pago-multiple_obrero" style="display: none;">
                            <div class="row-horizon disenio-scroll" >
                              <table class="table table-bordered /*table-striped*/ table-hover text-nowrap" >
                                <thead>                                  
                                  <tr class="text-center bg-gradient-info"> 
                                    <th class="">N°</th> 
                                    <th class=" text-center nombre_q_s_obrero">Quincena</th>
                                    <th class=" text-center">Trabajador</th>
                                    <th class=" text-center">Banco</th>
                                    <th class=" text-center">Cuenta</th>
                                    <th class="">Horas Normal/Extra</th>                                  
                                    <th class="">Monto total</th>                                    
                                    <th class="">Pagar</th>
                                    <th class="">Saldo</th>
                                    <th class="" data-toggle="tooltip" data-original-title="Recibos por Honorarios">R/H</th>
                                  </tr>
                                </thead>
                                <tbody class="data-trabajadores-q-s">  <!-- Detalle -->   </tbody>                                
                                <tfoot>
                                  <tr>                                    
                                    <th colspan="5" ></th> 
                                    <th class="text-center multiple_total_hn_he"> <i class="fas fa-spinner fa-pulse fa-sm"></i></th>                           
                                    <th class=""><div class="formato-numero-conta"><span>S/</span><span class="multiple_total_deuda"><i class="fas fa-spinner fa-pulse fa-sm"></i></span></div></th> 
                                    <th class=""><div class="formato-numero-conta"><span>S/</span><span class="multiple_total_deposito"><i class="fas fa-spinner fa-pulse fa-sm"></i></span></div></th> 
                                    <th class=""><div class="formato-numero-conta"><span>S/</span><span class="multiple_total_saldo"><i class="fas fa-spinner fa-pulse fa-sm"></i></span></div></th> 
                                    <th class="text-center multiple_rh_total"><i class="fas fa-spinner fa-pulse fa-sm"></i></th>
                                  </tr>
                                </tfoot>
                              </table>
                            </div>
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

                <!-- MODAL - TABLA LISTA PAGOS -->
                <div class="modal fade" id="modal-tabla-pagos">
                  <div class="modal-dialog modal-dialog-scrollable modal-xl">
                    <div class="modal-content">
                      <div class="modal-header"> 
                        <h4 class="modal-title titulo-comprobante-compra">Lista de Comprobantes</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body ">
                        <div class="row">
                          <div class="col-12">
                            <button  class="btn btn-success btn-sm" data-toggle="modal"  data-target="#modal-agregar-pago-trabajdor" onclick="limpiar_pago_q_s();" >Agregar</button>
                          </div>
                          <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12 mt-3">
                            <table id="tabla-ingreso-pagos-modal" class="table table-bordered table-striped display " style="width: 100% !important;">
                              <thead>
                                <tr> 
                                  <th class="text-center">#</th> 
                                  <th>Op.</th> 
                                  <th>Fecha Deposito</th> 
                                  <th>Cuenta depósito</th> 
                                  <th>Monto</th>
                                  <th>Baucher</th>
                                  <th>RH</th>
                                  <th>Descripcion</th>                                                   
                                </tr>
                              </thead>
                              <tbody>  </tbody>
                              <tfoot>
                                <tr> 
                                  <th class="text-center">#</th> 
                                  <th>Op.</th> 
                                  <th>Fecha Deposito</th>
                                  <th>Cuenta depósito</th>
                                  <th>Monto</th>
                                  <th>Baucher</th>
                                  <th>RH</th>
                                  <th>Descripcion</th>                   
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
                
                <!-- MODAL - agregar PAGOS X QUINCENA O SEMANA -->
                <div class="modal fade bg-color-02020280" id="modal-agregar-pago-trabajdor">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg shadow-0px1rem3rem-rgb-0-0-0-50 rounded">
                    <div class="modal-content shadow-none border-0">
                      <div class="modal-header">
                        <h4 class="modal-title">Agregar pago: <b class="nombre_de_trabajador_modal"> <!-- NOMBRE DEL TRABAJDOR--> </b></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      
                      <div class="modal-body">
                        <!-- form start -->
                        <form class="mx-2" id="form-pagos-x-q-s" name="form-pagos-x-q-s"  method="POST" >                      
                          
                          <div class="row" id="cargando-1-fomulario">

                            <!-- id idpagos_q_s_obrero  -->
                            <input type="hidden" name="idpagos_q_s_obrero" id="idpagos_q_s_obrero" />
                            
                            <!-- id idresumen_q_s_asistencia -->
                            <input type="hidden" name="idresumen_q_s_asistencia" id="idresumen_q_s_asistencia" />

                            <!-- Descripcion Pensión-->
                            <div class="col-12 pl-0">
                              <div class="text-primary"><label for="">DETALLES PAGO</label></div>
                            </div>
                            <div class="card col-12 px-3 py-3" style="box-shadow: 0 0 1px rgb(0 0 0), 0 1px 3px rgb(0 0 0 / 60%);">
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
                                    <label for="nombre_mes" class="text-gray nombre_q_s">-- </label>
                                    <span class="numero_q_s text-gray form-control"> <sup>S/</sup> 0.00</span>
                                  </div>
                                </div>

                                <!-- Monto faltante -->
                                <div class="col-lg-3">
                                  <div class="form-group">
                                    <label for="nombre_mes" class="text-gray">Faltante </label>
                                    <span class="faltante_mes_modal form-control"> <sup>S/</sup> 0.00</span>
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
                                    <label for="numero_comprobante_rh">N° de Comprobante  </label>                               
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
                                    <span class="form-control input-valido">PERSONAL OBRERO</span>
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
                            <div class="col-md-6" >                               
                              <div class="row text-center">
                                <div class="col-md-12 p-t-15px p-b-5px" >
                                  <label for="doc1_i" class="control-label" > Baucher de deposito </label>
                                </div>
                                <div class="col-6 col-md-6 text-center">
                                  <button type="button" class="btn btn-success btn-block btn-xs" id="doc1_i"><i class="fas fa-upload"></i> Subir. </button>
                                  <input type="hidden" id="doc_old_1" name="doc_old_1" />
                                  <input style="display: none;" id="doc1" type="file" name="doc1" accept="application/pdf, image/*" class="docpdf" /> 
                                </div>
                                <div class="col-6 col-md-6 text-center">
                                  <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(1,'pago_obrero', 'baucher_deposito');">
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
                            <div class="col-md-6 col-lg-6">                              
                              <div class="row text-center">      
                                <div class="col-md-12 p-t-15px p-b-5px" >
                                  <label for="doc2_i" >Recibo x honorario  </label>
                                </div>                         
                                <!-- Subir documento -->
                                <div class="col-6 col-md-6 text-center">
                                  <button type="button" class="btn btn-success btn-block btn-xs" id="doc2_i"><i class="fas fa-upload"></i> Subir. </button>
                                  <input type="hidden" id="doc_old_2" name="doc_old_2" />
                                  <input style="display: none;" id="doc2" type="file" name="doc2" accept="application/pdf, image/*" class="docpdf" /> 
                                </div>
                                <!-- Recargar -->
                                <div class="col-6 col-md-6 text-center comprobante">
                                  <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(2, 'pago_obrero', 'recibos_x_honorarios');">
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
                              <div class="progress" id="barra_progress_pagos_x_mes_div">
                                <div id="barra_progress_pagos_x_mes" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
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
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro_pagos_x_mes">Guardar Cambios</button>
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
                
                <!-- MODAL - recibo por honorarios ------------ NO SE USA ------------- -->
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
                            <input type="hidden" name="idresumen_q_s_asistencia_rh" id="idresumen_q_s_asistencia_rh" />

                            <!-- Numero de semana -->
                            <div class="col-lg-6">
                              <div class="form-group">
                                <label for="numero_q_s_modal" class="nombre_tipo_pago_modal">N°  </label>
                                <span class="numero_q_s_modal  form-control"> </span>
                              </div>
                            </div>

                            <!-- fecha inicial -->
                            <div class="col-lg-6">
                              <div class="form-group">
                                <label for="fecha_incial_modal" class="">Fecha inicial </label>
                                <span class="fecha_incial_modal  form-control"> </span>
                              </div>
                            </div>
                            <!-- fecha final -->
                            <div class="col-lg-6">
                              <div class="form-group">
                                <label for="fecha_final_modal" class="">Fecha final </label>
                                <span class="fecha_final_modal  form-control"> </span>
                              </div>
                            </div> 

                            <!-- barprogress -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-4">
                              <div class="progress" id="barra_progress_r_h_div">
                                <div id="barra_progress_r_h" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
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
                        <button type="submit" class="btn btn-success" id="guardar_registro_recibo_x_honorario">Guardar Cambios</button>
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
        
        <script type="text/javascript" src="scripts/pago_obrero.js"></script>        
         
        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }) </script>
        
      </body>
    </html>
    <?php    
  }
  ob_end_flush();
?>
