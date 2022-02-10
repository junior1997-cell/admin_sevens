<?php
  //Activamos el almacenamiento en el buffer
  ob_start();

  session_start();
  if (!isset($_SESSION["nombre"])){
    header("Location: login.html");
  }else{
    ?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Admin Sevens | Otros servicios</title>
        <?php
        require 'head.php';
        ?>
        
    </head>
    <body class="hold-transition sidebar-collapse sidebar-mini layout-fixed layout-navbar-fixed">
        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper">
            <?php
          require 'nav.php';
          require 'aside.php';
          if ($_SESSION['resumen_general']==1){
          ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-12 tex-center" style="text-align: center;font-weight: bold;">
                                <h1  style="font-weight: bold;">RESUMEN GENERAL</h1>
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
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <style>
                                            table.colapsado {border-collapse: collapse;} 
                                            .clas_pading{ padding: 0.20rem  0.75rem  0.20rem  0.75rem !important;}
                                            .backgff9100{background-color: #ff9100;}
                                            .colorf0f8ff00{color: #f0f8ff00;}
                                            .text_area_clss{
                                                width: 300px;
                                                background: rgb(215, 224, 225);
                                                border-block-color: inherit;
                                                border-bottom: aliceblue;
                                                border-left: aliceblue;
                                                border-right: aliceblue;
                                                }
                                        </style>
                                       <div class="container table-responsive disenio-scroll">

                                            <table class="table table-hover text-nowrap styletabla" style="border: black 1px solid;" border="1" style="width: 100%;" >
                                                <thead style="background-color: #408c98; color: white;" >
                                                    <tr>
                                                        <th colspan="8" class="text-center w-px-300 clas_pading backgff9100">Compras</th>
                                                     </tr>
                                                    <tr>
                                                        <th class="text-center clas_pading">#</th>
                                                        <th class="text-center  w-px-300 clas_pading">EMPRESA</th>
                                                        <th class="text-center clas_pading">FECHA</th>
                                                        <th class="text-center clas_pading"><i class="fas fa-cogs"></i></th>
                                                        <th class="text-center clas_pading">DESCRIPCIÓN</th>
                                                        <th class="text-center clas_pading">MONTO</th>
                                                        <th class="text-center clas_pading">PAGOS</th>
                                                        <th class="text-center clas_pading">SALDO</th>
                                                    </tr>
                                                </thead>
                                                <tbody  id="compras">
                                                    <!--aqui va el listado de los días-->
                                                </tbody>
                                                <tfoot>
                                                    <th colspan="4" class="clas_pading"></th>
                                                    <th  class="clas_pading">Total</th>
                                                    <th  class="clas_pading text-right" id="monto_compras" ></th>
                                                    <th  class="clas_pading text-right" id="pago_compras" ></th>
                                                    <th  class="clas_pading text-right" id="saldo_compras" ></th>
                                                </tfoot>
                                            </table>

                                            <table class="table table-hover text-nowrap styletabla" style="border: black 1px solid;" border="1" style="width: 100%;" >
                                                <thead style="background-color: #408c98; color: white;" >
                                                    <tr>
                                                        <th colspan="7" class="text-center w-px-300 clas_pading backgff9100">Servicios-Maquinaria</th>
                                                     </tr>
                                                    <tr>
                                                        <th class="text-center  clas_pading">#</th>
                                                        <th class="text-center  w-px-300 clas_pading">MAQUINA</th>
                                                        <th class="text-center clas_pading">PROVEEDOR</th>
                                                        <th class="text-center clas_pading">DETALLE</th>
                                                        <th class="text-center clas_pading">MONTO</th>
                                                        <th class="text-center clas_pading">PAGOS</th>
                                                        <th class="text-center clas_pading">SALDO</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="serv_maquinas">
                                                    <!--aqui va el listado de los días-->
                                                </tbody>
                                                <tfoot >
                                                    <th colspan="3" class="clas_pading"></th>
                                                    <th class="clas_pading">Total</th>
                                                    <th class="clas_pading text-right" id="monto_serv_maq"></th>
                                                    <th class="clas_pading text-right" id="pago_serv_maq"></th>
                                                    <th class="clas_pading text-right" id="saldo_serv_maq"></th>
                                                </tfoot>
                                            </table>

                                            <table class="table table-hover text-nowrap styletabla" style="border: black 1px solid;" border="1" style="width: 100%;" >
                                                <thead style="background-color: #408c98; color: white;" >
                                                    <tr>
                                                        <th colspan="7" class="text-center w-px-300 clas_pading backgff9100">Servicios-Equipo</th>
                                                     </tr>
                                                    <tr>
                                                        <th class="text-center  clas_pading">#</th>
                                                        <th class="text-center  w-px-300 clas_pading">MAQUINA</th>
                                                        <th class="text-center clas_pading">PROVEEDOR</th>
                                                        <th class="text-center clas_pading">DETALLE</th>
                                                        <th class="text-center clas_pading">MONTO</th>
                                                        <th class="text-center clas_pading">PAGOS</th>
                                                        <th class="text-center clas_pading">SALDO</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="serv_equipos">
                                                    <!--aqui va el listado de los días-->
                                                </tbody>
                                                <tfoot >
                                                    <th colspan="3" class="clas_pading"></th>
                                                    <th class="clas_pading">Total</th>
                                                    <th class="clas_pading text-right" id="monto_serv_equi"></th>
                                                    <th class="clas_pading text-right" id="pago_serv_equi"></th>
                                                    <th class="clas_pading text-right" id="saldo_serv_equi"></th>
                                                </tfoot>
                                            </table>

                                            <table class="table table-hover text-nowrap styletabla" style="border: black 1px solid;" border="1" style="width: 100%;" >
                                                <thead style="background-color: #408c98; color: white;" >
                                                    <tr>
                                                        <th colspan="7" class="text-center w-px-300 clas_pading backgff9100">Transporte</th>
                                                     </tr>
                                                    <tr>
                                                        <th class="text-center clas_pading">#</th>
                                                        <th class="text-center clas_pading">FECHA</th>
                                                        <th class="text-center clas_pading" data-toggle="tooltip" data-original-title="Comprobante">COMP</th>
                                                        <th class="text-center clas_pading">DESCRIPCIÓN</th>
                                                        <th class="text-center clas_pading">MONTO</th>
                                                        <th class="text-center clas_pading">PAGOS</th>
                                                        <th class="text-center clas_pading">SALDO</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="transportes">
                                                    <!--aqui va el listado de los días-->
                                                </tbody>
                                                <tfoot>
                                                    <th colspan="3" class="clas_pading"></th>
                                                    <th class="clas_pading">Total</th>
                                                    <th class="clas_pading text-right" id="monto_transp"></th>
                                                    <th class="clas_pading text-right" id="pago_transp"></th>
                                                    <th class="clas_pading text-right" id="saldo_transp"></th>
                                                </tfoot>
                                            </table>

                                            <table class="table table-hover text-nowrap styletabla" style="border: black 1px solid;" border="1" style="width: 100%;" >
                                                <thead style="background-color: #408c98; color: white;" >
                                                    <tr>
                                                        <th colspan="7" class="text-center w-px-300 clas_pading backgff9100">Hospedaje</th>
                                                     </tr>
                                                    <tr>
                                                        <th class="text-center clas_pading">#</th>
                                                        <th class="text-center clas_pading">FECHA</th>
                                                        <th class="text-center clas_pading" data-toggle="tooltip" data-original-title="Comprobante">COMP</th>
                                                        <th class="text-center clas_pading">DESCRIPCIÓN</th>
                                                        <th class="text-center clas_pading">MONTO</th>
                                                        <th class="text-center clas_pading">PAGOS</th>
                                                        <th class="text-center clas_pading">SALDO</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="hospedaje">
                                                    <!--aqui va el listado de los días-->
                                                </tbody>
                                                <tfoot>
                                                    <th colspan="3" class="clas_pading"></th>
                                                    <th class="clas_pading">Total</th>
                                                    <th class="clas_pading text-right" id="monto_hosped"></th>
                                                    <th class="clas_pading text-right" id="pago_hosped"></th>
                                                    <th class="clas_pading text-right" id="saldo_hosped"></th>
                                                </tfoot>
                                            </table>

                                            <table class="table table-hover text-nowrap styletabla" style="border: black 1px solid;" border="1" style="width: 100%;" >
                                                <thead style="background-color: #408c98; color: white;" >
                                                    <tr>
                                                        <th colspan="7" class="text-center w-px-300 clas_pading backgff9100">Comidas extras</th>
                                                     </tr>
                                                    <tr><!--<i class="far fa-file-pdf fa-2x" style="color:#ffffff"></i>-->
                                                        <th class="text-center clas_pading">#</th>
                                                        <th class="text-center clas_pading">FECHA</th>
                                                        <th class="text-center clas_pading" data-toggle="tooltip" data-original-title="Comprobante">COMP</th>
                                                        <th class="text-center w-px-300 clas_pading">DESCRIPCIÓN</th>
                                                        <th class="text-center clas_pading">MONTO</th>
                                                        <th class="text-center clas_pading">PAGOS</th>
                                                        <th class="text-center clas_pading">SALDO</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="comida_extra">
                                                    <!--aqui va el listado de los días-->
                                                </tbody>
                                                <tfoot>
                                                    <th colspan="3" class="clas_pading"></th>
                                                    <th class="clas_pading">Total</th>
                                                    <th class="clas_pading text-right" id="monto_cextra"></th>
                                                    <th class="clas_pading text-right" id="pago_cextra"></th>
                                                    <th class="clas_pading text-right" id="saldo_cextra"></th>
                                                </tfoot>
                                            </table>
                                            
                                            <table class="table table-hover text-nowrap styletabla" style="border: black 1px solid;" border="1" style="width: 100%;" >
                                                <thead style="background-color: #408c98; color: white;" >
                                                    <tr>
                                                        <th colspan="7" class="text-center w-px-300 clas_pading backgff9100">Breaks</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center clas_pading">#</th>
                                                        <th class="text-center clas_pading">SEMANA</th>
                                                        <th class="text-center clas_pading">FECHA</th>
                                                        <th class="text-center clas_pading" data-toggle="tooltip" data-original-title="Comprobante">COMP</th>
                                                        <th class="text-center clas_pading">MONTO</th>
                                                        <th class="text-center clas_pading">PAGOS</th>
                                                        <th class="text-center clas_pading">SALDO</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="breaks">
                                                    <!--aqui va el listado de los días-->
                                                </tbody>
                                                <tfoot>
                                                    <th colspan="3" class="clas_pading"></th>
                                                    <th class="clas_pading">Total</th>
                                                    <th class="clas_pading text-right" id="monto_break"></th>
                                                    <th class="clas_pading text-right" id="pago_break"></th>
                                                    <th class="clas_pading text-right" id="saldo_break"></th>
                                                </tfoot>
                                            </table>

                                            <table class="table table-hover text-nowrap styletabla" style="border: black 1px solid;" border="1" style="width: 100%;" >
                                                <thead style="background-color: #408c98; color: white;" >
                                                    <tr>
                                                        <th colspan="7" class="text-center w-px-300 clas_pading backgff9100">Pensión</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center clas_pading">#</th>
                                                        <th class="text-center clas_pading">PROVEEDOR</th>
                                                        <th class="text-center clas_pading">DIRECCIÓN</th>
                                                        <th class="text-center clas_pading" data-toggle="tooltip" data-original-title="Detalle-Comprobante">DETALL-COMP</th>
                                                        <th class="text-center clas_pading">MONTO</th>
                                                        <th class="text-center clas_pading">PAGOS</th>
                                                        <th class="text-center clas_pading">SALDO</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="pension">
                                                    <!--aqui va el listado de los días-->
                                                </tbody>
                                                <tfoot>
                                                    <th colspan="3" class="clas_pading"></th>
                                                    <th class="clas_pading">Total</th>
                                                    <th class="clas_pading text-right" id="monto_pension"></th>
                                                    <th class="clas_pading text-right" id="pago_pension"></th>
                                                    <th class="clas_pading text-right" id="saldo_pension"></th>
                                                </tfoot>
                                            </table>

                                       </div>
                                    </div>
                                    <a class="btn btn-info" target="_blank" href=""></a>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.container-fluid -->

                    <!--Modal ver detalles compras-->
                    <div class="modal fade" id="modal-ver-compras">
                        <div class="modal-dialog modal-dialog-scrollable modal-xl">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Detalle Compra</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span class="text-danger" aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div class="row" id="cargando-1-fomulario">
                                <!-- Tipo de Empresa -->
                                <div class="col-lg-7">
                                    <div class="form-group">
                                    <label for="idproveedor">Proveedor</label>

                                    <h5 class="idproveedor" style="border: 1px solid #ced4da; border-radius: 0.25rem; padding: 0.375rem 0.75rem;"></h5>
                                    </div>
                                </div>
                                <!-- fecha -->
                                <div class="col-lg-5">
                                    <div class="form-group">
                                    <label for="fecha_compra">Fecha </label>
                                    <input type="date" class="form-control fecha_compra" placeholder="Fecha" />
                                    </div>
                                </div>
                                <!-- Tipo de comprobante -->
                                <div class="col-lg-4 content-t-comprob">
                                    <div class="form-group">
                                    <label for="tipo_comprovante">Tipo Comprobante</label>
                                    <h5 class="tipo_comprovante" style="border: 1px solid #ced4da; border-radius: 0.25rem; padding: 0.375rem 0.75rem;"></h5>
                                    </div>
                                </div>
                                <!-- serie_comprovante-->
                                <div class="col-lg-2 content-comprob">
                                    <div class="form-group">
                                    <label for="serie_comprovante">N° de Comprobante</label>
                                    <input type="text" class="form-control serie_comprovante" placeholder="N° de Comprobante" />
                                    </div>
                                </div>
                                <!-- IGV-->
                                <div class="col-lg-1 content-igv" style="display: none;">
                                    <div class="form-group">
                                    <label for="igv">IGV</label>
                                    <input type="text" class="form-control igv" readonly value="0.18" />
                                    </div>
                                </div>
                                <!-- Descripcion-->
                                <div class="col-lg-5 content-descrp">
                                    <div class="form-group">
                                    <label for="descripcion">Descripción </label> <br />
                                    <textarea class="form-control descripcion" rows="1"></textarea>
                                    </div>
                                </div>
                                <!--tabla detalles plantas-->
                                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
                                    <br />
                                    <table id="detalles_compra" class="table table-striped table-bordered table-condensed table-hover">
                                    <thead style="background-color: #ff6c046b;">
                                        <th>Opciones</th>
                                        <th>Material</th>
                                        <th>Cantidad</th>
                                        <th>Precio Compra</th>
                                        <th>Descuento</th>
                                        <th>Subtotal</th>
                                    </thead>
                                    <tfoot>
                                        <td colspan="4"></td>
                                        <th class="text-center">
                                        <h5>Subtotal</h5>
                                        <h5>IGV</h5>
                                        <h5>TOTAL</h5>
                                        </th>
                                        <!--idproveedor,fecha_compra,tipo_comprovante,serie_comprovante,igv,descripcion, igv_comp, total-->
                                        <th>
                                        <h5 class="text-right subtotal" style="font-weight: bold;">S/. 0.00</h5>
                                        <h5 class="text-right igv_comp" style="font-weight: bold;">S/. 0.00</h5>
                                        <b>
                                            <h4 class="text-right total" style="font-weight: bold;">S/. 0.00</h4>
                                        </b>
                                        </th>
                                    </tfoot>
                                    <tbody></tbody>
                                    </table>
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
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal ver detalle maquina equipos -->
                    <div class="modal fade" id="modal_ver_detalle_maq_equ">
                        <div class="modal-dialog modal-dialog-scrollable modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title"> <b>Maquinas - Equipos:</b></h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span class="text-danger" aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <!--la tabla-->
                                    <table id="tabla-detalle-m" class="table table-bordered table-striped display" style="width: 100% !important;">
                                        <thead>
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Horometro Inicial</th>
                                                <th>Horometro Final</th>
                                                <th>Total Horas </th>
                                                <th>Costo Unitario</th>
                                                <th>Unidad M.</th>
                                                <th>Cantidad</th>
                                                <th>Costo Parcial</th>
                                                <th>Descripción</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Horometro Inicial</th>
                                                <th>Horometro Final</th>
                                                <th id="horas-total">Total Horas </th>
                                                <th>Costo Unitario</th>
                                                <th>Unidad M.</th>
                                                <th>Cantidad</th>
                                                <th>Costo Parcial</th>
                                                <th>Descripción</th>
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
                    <!-- Modal ver detalle maquina equipos -->
                    <div class="modal fade" id="modal_ver_breaks">
                        <div class="modal-dialog modal-dialog-scrollable modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title"> <b>BREAKS:</b></h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span class="text-danger" aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <!--la tabla-->
                                    <table id="t-comprobantes" class="table table-bordered table-striped display" style="width: 100% !important;">
                                        <thead>
                                            <tr>
                                                <th data-toggle="tooltip" data-original-title="Forma Pago">Forma P.</th>
                                                <th data-toggle="tooltip" data-original-title="Tipo Comprobante">Tipo</th>
                                                <th data-toggle="tooltip" data-original-title="Número Comprobante">Número</th>
                                                <th data-toggle="tooltip" data-original-title="Fecha Emisión">Fecha</th>
                                                <th>Sub total</th>
                                                <th>IGV</th>
                                                <th>Monto</th>
                                                <th>Descripción</th>
                                                <th>Comprobante</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <th data-toggle="tooltip" data-original-title="Forma Pago">Forma P.</th>
                                                <th data-toggle="tooltip" data-original-title="Tipo Comprobante">Tipo</th>
                                                <th data-toggle="tooltip" data-original-title="Número Comprobante">Número</th>
                                                <th data-toggle="tooltip" data-original-title="Fecha Emisión">Fecha</th>
                                                <th>Sub total</th>
                                                <th>IGV</th>
                                                <th>Monto</th>
                                                <th>Descripción</th>                                                   
                                                <th>Comprobante</th>
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
                    <!--modal-ver-detalle-semana pension-->
                    <div class="modal fade" id="modal-ver-detalle-semana">
                        <div class="modal-dialog modal-dialog-scrollable modal-xl">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Detalles por semana</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span class="text-danger" aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div  class="class-style" style="text-align: center;"> 
                                <table id="tabla-detalles-semanal" class="table table-bordered table-striped display" style="width: 100% !important;">
                                    <thead>
                                    <tr>
                                        <th class="">Tipo comida</th>
                                        <th class="">Precio</th>
                                        <th>Total platos</th>
                                        <th>Adicional</th>
                                        <th>total</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                    <tr>
                                        <th class="">Tipo comida</th>
                                        <th class="">Precio</th>
                                        <th>Total platos</th>
                                        <th>Adicional</th>
                                        <th>total</th>
                                    </tr>
                                    </tfoot>
                                </table> 
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <!--modal-ver-detalle-semana pension-->
                    <div class="modal fade" id="modal-ver-comprobantes_pension">
                        <div class="modal-dialog modal-dialog-scrollable modal-xl">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Comprobantes</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span class="text-danger" aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div  class="class-style" style="text-align: center;"> 

                                    <table id="t-comprobantes-pension" class="table table-bordered table-striped display" style="width: 100% !important;">
                                        <thead>
                                            <tr>
                                                <th data-toggle="tooltip" data-original-title="Forma de pago">Forma </th>
                                                <th data-toggle="tooltip" data-original-title="Tipo Comprobante">Tipo </th>
                                                <th data-toggle="tooltip" data-original-title="Número Comprobante">Número </th>
                                                <th data-toggle="tooltip" data-original-title="Fecha Emisión">F. Emisión</th>
                                                <th>Sub total</th>
                                                <th>IGV</th>
                                                <th>Monto</th>
                                                <th>Descripción</th>
                                                <th>Comprobante</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <th data-toggle="tooltip" data-original-title="Forma de pago">Forma </th>
                                                <th data-toggle="tooltip" data-original-title="Tipo Comprobante">Tipo </th>
                                                <th data-toggle="tooltip" data-original-title="Número Comprobante">Número</th>
                                                <th data-toggle="tooltip" data-original-title="Fecha Emisión">F. Emisión</th>
                                                <th>Sub total</th>
                                                <th>IGV</th>
                                                <th>Monto</th>
                                                <th>Descripción</th>                                                   
                                                <th>Comprobante</th>
                                            </tr> 
                                        </tfoot>
                                    </table>

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
        <?php
        
        require 'script.php';
        ?>
        <style>
            .class-style label{
                font-size: 14px;
            }
            .class-style small {
                background-color: #f4f7ee;
                border: solid 1px #ce542a21;
                margin-left: 3px;
                padding: 5px;
                border-radius: 6px;
            }
        </style>
        <!-- Bootstrap 4 -->
        <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- jquery-validation -->
        <script src="../plugins/jquery-validation/jquery.validate.min.js"></script>
        <script src="../plugins/jquery-validation/additional-methods.min.js"></script>
        <!-- InputMask -->
        <script src="../plugins/moment/moment.min.js"></script>
        <script src="../plugins/inputmask/jquery.inputmask.min.js"></script>
        <!-- sweetalert2 -->
        <script src="../plugins/sweetalert2/sweetalert2.all.min.js"></script>

       <!-- <script type="text/javascript" src="scripts/moment.min.js"></script>-->
        <script type="text/javascript" src="scripts/resumen_general.js"></script>

        <script>
            $(function () {
                $('[data-toggle="tooltip"]').tooltip();
            });
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
