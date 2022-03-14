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
        <title>Admin Sevens | Sub Contrato</title>
        <?php
          require 'head.php';
        ?>
      </head>
      <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed ">
        
        <div class="wrapper">
          <?php
            require 'nav.php';
            require 'aside.php';
            if ($_SESSION['subcontrato']==1){
          ?>           
          <!--Contenido-->
          <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
              <div class="container-fluid">
                <div class="row mb-2">
                  <div class="col-sm-6">
                    <h1 class="m-0 nombre-trabajador"><i class="nav-icon fas fa-hands-helping"></i> Sub Contrato</h1>
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="sub_contrato.php">Home</a></li>
                      <li class="breadcrumb-item active">Sub Contrato</li>
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
                    <div class=" card card-primary card-outline">
                      <div class="card-header"> 

                        <!-- agregar pago  -->
                        <h3 class="card-title " id="btn-agregar" >
                          <button type="button" class="btn bg-gradient-success" id="add_sub_contrato" data-toggle="modal" data-target="#modal-agregar-sub-contrato" onclick="limpiar(); regresar(1);"><i class="fas fa-plus-circle"></i> Agregar</button>
                          <button type="button" class="btn bg-gradient-warning" id="regresar" style="display: none;" onclick="regresar(1);"><i class="fas fa-arrow-left"></i> Regresar</button>
                          <button type="button" class="btn bg-gradient-success" id="add_agregar_pago" style="display: none;" data-toggle="modal" data-target="#modal-agregar-pago" onclick="limpiar_pagos(); regresar(2);"><i class="fas fa-plus-circle"></i> Agregar Pago</button>
                            Administra tus sub contratos.
                        </h3> 

                      </div>
                      <!-- /.card-header -->
                      <div class="card-body">

                        <!-- tabla principal -->
                        <div class="pb-3" id="tbl-principal">
                          <table id="tabla-sub-contratos" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                  <tr>
                                      <th class="text-center">#</th>                                                    
                                      <th class="">Acciones</th>                                                    
                                      <th data-toggle="tooltip" data-original-title="Forma Pago">Forma P.</th>
                                      <th data-toggle="tooltip" data-original-title="Comprobante">Tipo comprob</th>
                                      <th>Fecha</th>
                                      <th>Descripción </th>
                                      <th>Sub total</th>
                                      <th>Igv</th>
                                      <th>Total </th>
                                      <th>Añadir Pago </th>
                                      <th>Saldo </th>
                                      <th data-toggle="tooltip" data-original-title="Comprobante">Comprob</th>
                                  </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                  <tr>
                                      <th class="text-center">#</th>
                                      <th class="">Acciones</th>
                                      <th data-toggle="tooltip" data-original-title="Forma Pago">Forma P.</th>
                                      <th data-toggle="tooltip" data-original-title="Comprobante">Tipo comprob</th>
                                      <th>Fecha</th>
                                      <th>Descripción </th>
                                      <th>Sub total</th>
                                      <th>Igv</th>
                                      <th class="text-nowrap total_monto"></th>
                                      <th>Añadir Pago </th>
                                      <th>Saldo </th>
                                      <th>Comprob</th>                                          
                                  </tr>
                              </tfoot>
                          </table>
                        </div>
                        <!-- tabla pagos -->
                        <div class="pb-3" id="tbl-pagos" style="display: none;">
                          <div style="text-align: center;"><h4>Total a pagar: <b id="total_apagar"></b></h4></div>
                          <br>
                          <div class="text-center" style="background-color: aliceblue;">
                            <h5>Proveedor S/ <b id="t_proveedor"></b> <i class="fas fa-arrow-right fa-xs"></i> <b id="t_provee_porc"></b> <b>%</b></h5>
                          </div>
                          <!--tabla-pagos-proveedor-->
                          <table id="tabla-pagos-proveedor" class="table table-bordered table-striped display" style="width: 100% !important;">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Acciones</th>
                                <th>Forma pago</th>
                                <th>Beneficiario</th>
                                <th data-toggle="tooltip" data-original-title="Fecha Pago">Fecha P.</th>
                                <th>Descripción</th>
                                <th data-toggle="tooltip" data-original-title="Número Operación">Número Op.</th>
                                <th>Monto</th>
                                <th>Vaucher</th>
                                <th>Estado</th>
                              </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                              <tr>
                                <th>#</th>
                                <th>Aciones</th>
                                <th>Forma pago</th>
                                <th>Beneficiario</th>
                                <th data-toggle="tooltip" data-original-title="Fecha Pago">Fecha P.</th>
                                <th>Descripción</th>
                                <th data-toggle="tooltip" data-original-title="Número Operación">Número Op.</th>
                                <th class="text-nowrap" style="color: #ff0000; background-color: #45c920;"> 
                                  <b class="monto_total_deposito_prov"></b> <br>
                                  <b class="porcnt_deposito_prov" style="color: black;"></b>
                                </th>
                                <th>Vaucher</th>
                                <th>Estado</th>
                              </tr>
                              <tr>
                                <td colspan="6"></td>
                                <td class="text-nowrap" style="font-weight: bold; font-size: 20px; text-align: center;">Saldo</td>
                                <th class="text-nowrap" style="color: #ff0000; background-color: #f3e700;">
                                  <b id="saldo_prov"></b><br /> 
                                  <b id="porcnt_sald_prov" style="color: black;"></b>
                                </th>
                                <td colspan="2"></td>
                              </tr>
                            </tfoot>
                          </table>

                          <br>
                          
                          <div class="text-center" style="background-color: aliceblue;">
                            <h5>Detracciòn S/ <b id="t_detaccion"></b> <i class="fas fa-arrow-right fa-xs"></i> <b id="t_detacc_porc"></b> <b>%</b></h5>
                          </div>
                          <!--tabla-pagos-detraccion-->
                          <table id="tabla-pagos-detraccion" class="table table-bordered table-striped display" style="width: 100% !important;">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Acciones</th>
                                <th>Forma pago</th>
                                <th>Beneficiario</th>
                                <th data-toggle="tooltip" data-original-title="Fecha Pago">Fecha P.</th>
                                <th>Descripción</th>
                                <th data-toggle="tooltip" data-original-title="Número Operación">Número Op.</th>
                                <th>Monto</th>
                                <th>Vaucher</th>
                                <th>Estado</th>
                              </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                              <tr>
                                <th>#</th>
                                <th>Aciones</th>
                                <th>Forma pago</th>
                                <th>Beneficiario</th>
                                <th data-toggle="tooltip" data-original-title="Fecha Pago">Fecha P.</th>
                                <th>Descripción</th>
                                <th data-toggle="tooltip" data-original-title="Número Operación">Número Op.</th>
                                <th class="text-nowrap"  style="color: #ff0000; background-color: #45c920;"> 
                                  <b class="monto_total_deposito_detracc"></b>  <br />
                                  <b class="porcent_detracc" style="color: black;"></b>
                                </th>
                                <th>Vaucher</th>
                                <th>Estado</th>
                              </tr>
                              <tr>
                                <td colspan="6"></td>
                                <td class="text-nowrap" style="font-weight: bold; font-size: 20px; text-align: center;">Saldo</td>
                                <th class="text-nowrap" style="color: #ff0000; background-color: #f3e700;">
                                  <b id="saldo_detracc"></b> <br />
                                  <b id="porcnt_saldo_detracc" style="color: black;"></b>
                                </th>
                                <td colspan="2"></td>
                              </tr>
                            </tfoot>
                          </table>
                        </div>                          

                      </div>
                      <!-- /.card-body -->
                    </div>

                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
              <!-- /.container-fluid -->             

              <!-- Modal agregar sub contrato -->
              <div class="modal fade" id="modal-agregar-sub-contrato">
                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Agregando: <b>Sub contrato</b></h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    
                    <div class="modal-body">
                      <!-- form start -->
                      <form id="form-agregar-sub-contrato" name="form-agregar-sub-contrato"  method="POST" >                      
                        <div class="card-body">
                          <div class="row" id="cargando-1-fomulario">

                            <!-- id sub contratro  -->
                            <input type="hidden" name="idproyecto" id="idproyecto" />     
                            <input type="hidden" name="idsubcontrato" id="idsubcontrato" />     

                            <!-- proveedor -->
                            <div class="col-lg-12">
                              <div class="form-group">
                              <label for="idproveedor">proveedor</label>
                              <select name="idproveedor" id="idproveedor" class="form-control select2" style="width: 100%;"> </select>
                              </div>
                            </div>               

                            <!-- Forma de pago hacia el trabajdor -->
                            <div class="col-lg-6">
                              <div class="form-group">
                              <label for="forma_pago">Forma Pago</label>
                              <select name="forma_de_pago" id="forma_de_pago" class="form-control select2" style="width: 100%;">
                                <option value="Transferencia">Transferencia</option>
                                <option value="Efectivo">Efectivo</option>
                              </select>
                              </div>
                            </div>

                            <!-- tipo de comprobante -->
                            <div class="col-lg-6">
                              <div class="form-group">
                              <label for="tipo_comprobante">Tipo Comprobante</label>
                              <select name="tipo_comprobante" id="tipo_comprobante" class="form-control select2" onchange="comprob_factura(); validando_igv();" style="width: 100%;">
                                <option value="Recibo por honorario">Recibo por honorario</option>
                                <option value="Ninguno">Ninguno</option>
                                <!--<option value="Comprobante">Comprobante</option>-->
                              </select>
                              </div>
                            </div>

                            <!-- Número comprobante -->
                            <div class="col-lg-6">
                              <div class="form-group">
                                <label for="numero_comprobante" class="nro_comprobante" >Núm. comprobante </label>                               
                                <input type="text" name="numero_comprobante" id="numero_comprobante" class="form-control"  placeholder="Código"> 
                              </div>                                                        
                            </div>
                             
                            <!-- Fecha -->
                            <div class="col-lg-6">
                              <div class="form-group">
                                <label for="Fecha" class="text-gray">Fecha </label>
                                <input type="date" name="fecha_subcontrato" id="fecha_subcontrato" class="form-control"  placeholder="Fecha"> 
                              </div>
                            </div>

                            <!-- Sub total -->
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="subtotal" class="text-gray">Sub total </label>
                                <input type="text" name="subtotal" id="subtotal" class="form-control"  placeholder="Sub total" readonly> 
                              </div>
                            </div>

                            <!-- IGV -->
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="igv" class="text-gray">IGV </label>
                                <input type="text" name="igv" id="igv" class="form-control"  placeholder="IGV" readonly> 
                              </div>
                            </div>

                            <!-- valor IGV -->
                            <div class="col-lg-2">
                              <div class="form-group">
                                <label for="val_igv" class="text-gray val_igv" style=" font-size: 13px;">Valor - IGV </label>
                                <input type="text" name="val_igv" id="val_igv" value="0.18" class="form-control" onkeyup="calculandototales_fact();"> 
                              </div>
                            </div>
                            
                            <!-- Total -->
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="costo_parcial" class="text-gray">Total </label>
                                <input type="text" name="costo_parcial" id="costo_parcial" class="form-control"  onchange="comprob_factura();" onkeyup="comprob_factura();"  placeholder="Total"> 
                              </div>
                            </div>
                             
                            <!-- Descripcion-->
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label for="descripcion">Descripción </label> <br>
                                <textarea name="descripcion" id="descripcion" class="form-control" rows="2"></textarea>
                              </div>                                                        
                            </div>
                             
                            <!-- Pdf 1 -->
                            <div class="col-md-6" >                               
                              <div class="row text-center">
                                <div class="col-md-12" style="padding-top: 15px; padding-bottom: 5px;">
                                  <label for="cip" class="control-label" >Comprobante </label>
                                </div>
                                <div class="col-md-6 text-center">
                                  <button type="button" class="btn btn-success btn-block btn-xs" id="doc1_i">
                                    <i class="fas fa-upload"></i> Subir.
                                  </button>
                                  <input type="hidden" id="doc_old_1" name="doc_old_1" />
                                  <input style="display: none;" id="doc1" type="file" name="doc1" accept="application/pdf, image/*" class="docpdf" /> 
                                </div>
                                <div class="col-md-6 text-center">
                                  <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(1, 'comprobante_subcontrato');">
                                  <i class="fas fa-redo"></i> Recargar.
                                  </button>
                                </div>
                              </div>                              
                              <div id="doc1_ver" class="text-center mt-4">
                                <img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >
                              </div>
                              <div class="text-center" id="doc1_nombre"><!-- aqui va el nombre del pdf --></div>
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
                        <button type="submit" style="display: none;" id="submit-form-agregar-sub-contrato">Submit</button>                      
                      </form>
                    </div>

                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-success" id="guardar_registro">Guardar Cambios</button>
                    </div>    

                  </div>
                </div>
              </div>
              <!--Modal-ver-comprobante-->
              <div class="modal fade" id="modal-ver-comprobante">
                <div class="modal-dialog modal-dialog-scrollable modal-xl ">
                    <div class="modal-content">
                        <div class="modal-header" style=" background-color: #73777b2e;">
                            <h4 class="modal-title">Comprobante </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span class="text-danger" aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div  class="class-style" style="text-align: center;"> 
                            <a class="btn btn-warning  btn-block" href="#" id="iddescargar" download=" _Comprobante sub contrato" style="padding:0px 12px 0px 12px !important;" type="button"><i class="fas fa-download"></i></a>
                              <br>
                              <img onerror="this.src='../dist/img/default/img_defecto.png';" src="../dist/img/default/img_defecto.png" class="img-thumbnail" id="img-factura" style="cursor: pointer !important;" width="auto" />
                                <div id="ver_fact_pdf" style="cursor: pointer !important;" width="auto"></div>
                            </div>
                        </div>
                    </div>
                </div>
              </div> 
              <!--Modal ver datos-->
              <div class="modal fade" id="modal-ver-datos-sub-contrato">
                  <div class="modal-dialog modal-dialog-scrollable modal-md">
                      <div class="modal-content">
                      <div class="modal-header">
                          <h4 class="modal-title">Datos comprobante</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                      </div>

                      <div class="modal-body">
                          <div id="datos-sub-contrato" class="class-style">
                          <!-- vemos los datos del trabajador -->
                          </div>
                      </div>
                      </div>
                  </div>
              </div> 

              <!--:...::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
              <!--Modal agregar Pagos-->
              <div class="modal fade" id="modal-agregar-pago">
                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                  <div class="modal-content">

                    <div class="modal-header">
                      <h4 class="modal-title">Agregar Pago</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <div class="modal-body">
                      <!-- form start -->
                      <form id="form-add-pago-subcontrato" name="form-add-pago-subcontrato" method="POST">
                        <div class="card-body">
                          <div class="row" id="cargando-1-fomulario">
                            <!-- id pago_subcontrato -->
                            <input type="hidden" name="idpago_subcontrato" id="idpago_subcontrato" />
                            <!-- id subcontrato -->
                            <input type="hidden" name="idsubcontrato_pago" id="idsubcontrato_pago" />

                            <!-- Beneficiario -->
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label for="beneficiario_pago">Beneficiario</label>
                                <input type="hidden" id="beneficiario_pago" name="beneficiario_pago" />
                                <br />
                                <b id="h4_mostrar_beneficiario" class="class_ver_provee"></b>
                              </div>
                            </div>
                            <!--Forma de pago -->
                            <div class="col-lg-6">
                              <div class="form-group">
                                <label for="forma_pago">Forma Pago</label>
                                <select name="forma_pago" id="forma_pago" class="form-control select2" style="width: 100%;">
                                  <option value="Transferencia">Transferencia</option>
                                  <option value="Efectivo">Efectivo</option>
                                  <option value="Crédito">Crédito</option>
                                </select>
                              </div>
                            </div>
                            <!--tipo de pago -->
                            <div class="col-lg-6 validar_fp">
                              <div class="form-group">
                                <label for="tipo_pago">Tipo Pago</label>
                                <select name="tipo_pago" id="tipo_pago" class="form-control select2" style="width: 100%;" onchange="captura_op();">
                                  <option value="Proveedor">Proveedor</option>
                                  <option value="Detraccion">Detracción</option>
                                </select>
                              </div>
                            </div>
                            <!-- Cuenta de destino-->
                            <div class="col-lg-6 validar_fp">
                              <div class="form-group">
                                <label for="cuenta_destino_pago">Cuenta destino </label>
                                <input type="text" name="cuenta_destino_pago" id="cuenta_destino_pago" class="form-control" placeholder="Cuenta destino" />
                              </div>
                            </div>
                            <!-- banco -->
                            <div class="col-lg-6 validar_fp">
                              <div class="form-group">
                                <label for="banco_pago">Banco</label>
                                <select name="banco_pago" id="banco_pago" class="form-control select2" style="width: 100%;"></select>
                              </div>
                            </div>
                            <!-- Titular Cuenta-->
                            <div class="col-lg-6 validar_fp">
                              <div class="form-group">
                                <label for="titular_cuenta_pago">Titular Cuenta </label>
                                <input type="text" name="titular_cuenta_pago" id="titular_cuenta_pago" class="form-control" placeholder="Titular Cuenta" />
                              </div>
                            </div>
                            <!-- Fecha Inicio-->
                            <div class="col-lg-6">
                              <div class="form-group">
                                <label for="fecha_pago">Fecha Pago </label>
                                <input type="date" name="fecha_pago" id="fecha_pago" class="form-control" />
                              </div>
                            </div>
                            <!-- Monto-->
                            <div class="col-lg-6">
                              <div class="form-group">
                                <label for="monto_pago">Monto </label>
                                <input type="number" step="0.01" name="monto_pago" id="monto_pago" class="form-control" placeholder="Ingrese monto" onkeyup="validando_excedentes();" onchange="validando_excedentes();" />
                              </div>
                            </div>
                            <!-- Número de Operación-->
                            <div class="col-lg-6 validar_fp">
                              <div class="form-group">
                                <label for="numero_op_pago">Número de operación </label>
                                <input type="number" name="numero_op_pago" id="numero_op_pago" class="form-control" placeholder="Número de operación" />
                              </div>
                            </div>
                            <!-- Descripcion-->
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label for="descripcion_pago">Descripción </label> <br />
                                <textarea name="descripcion_pago" id="descripcion_pago" class="form-control" rows="2"></textarea>
                              </div>
                            </div>
                            
                            <!-- Pdf 2 -->
                            <div class="col-md-6" >                               
                              <div class="row text-center">
                                <div class="col-md-12" style="padding-top: 15px; padding-bottom: 5px;">
                                  <label for="cip" class="control-label" >Comprobante </label>
                                </div>
                                <div class="col-md-6 text-center">
                                  <button type="button" class="btn btn-success btn-block btn-xs" id="doc2_i">
                                    <i class="fas fa-upload"></i> Subir.
                                  </button>
                                  <input type="hidden" id="doc_old_2" name="doc_old_2" />
                                  <input style="display: none;" id="doc2" type="file" name="doc2" accept="application/pdf, image/*" class="docpdf" /> 
                                </div>
                                <div class="col-md-6 text-center">
                                  <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(2, 'comprobante_pago');">
                                  <i class="fas fa-redo"></i> Recargar.
                                  </button>
                                </div>
                              </div>                              
                              <div id="doc2_ver" class="text-center mt-4">
                                <img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >
                              </div>
                              <div class="text-center" id="doc2_nombre"><!-- aqui va el nombre del pdf --></div>
                            </div>

                            <div class="row" id="cargando-2-fomulario" style="display: none;">
                              <div class="col-lg-12 text-center">
                                <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                                <br />
                                <h4>Cargando...</h4>
                              </div>
                            </div>

                          </div>
                        </div>
                        <!-- /.card-body -->
                        <button type="submit" style="display: none;" id="submit-form-pago">Submit</button>
                      </form>
                    </div>

                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_pagos();">Close</button>
                      <button type="submit" class="btn btn-success" id="guardar_registro_pago">Guardar Cambios</button>
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

        <script type="text/javascript" src="scripts/sub_contrato.js"></script>
         
        <script>
          $(function () {
            $('[data-toggle="tooltip"]').tooltip();
          })
        </script>
                
        <style>

          textarea.form-control {
              height: auto;
          }
          .text_area_clss {
              width: 100%;
              background: rgb(215 224 225 / 22%);
              border-block-color: inherit;
              border-bottom: aliceblue;
              border-left: aliceblue;
              border-right: aliceblue;
              border-top: hidden;
          }
          .class_ver_provee{
            display: block;
            width: 100%;
            height: calc(2.25rem + 2px);
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            box-shadow: inset 0 0 0 transparent;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
          }

        </style>

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
