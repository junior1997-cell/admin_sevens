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
                            <div class="col-sm-12 tex-center" style="font-weight: bold;">
                            <div class="row">
                                <div class="col-6 text-right">
                                <h1  style="font-weight: bold;">RESUMEN GENERAL</h1>
                                </div>
                                <div class="col-6">
                                <button class="btn btn-success btn-md" style="font-weight: bold;">Exportar <i class="fas fa-file-excel"></i></button>
                                </div>
                            </div>
                               
                                
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
                                <div class="card card-primary card-outline" style="border: 2px solid #f60c;">
                                    <div class="row">
                                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="card-header" style="border: 1px solid #f60c !important; background-color: #f60c; color: #ffffff;">
                                            <div class="row">
                                                <div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="filtros">Filtar por Fecha </label>                               
                                                    <input type="date"  class="form-control"  placeholder="Seleccionar fecha" > 
                                                </div>
                                                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="filtros">Trabajador </label>
                                                    <select name="trabajador" id="trabajador" class="form-control select2" style="width: 100%;" > 
                                                    </select>
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="filtros">Proveedor </label>  
                                                    <select name="proveedor" id="proveedor" class="form-control select2" style="width: 100%;" > 
                                                    </select>
                                                </div>
                                                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="filtros">Filtrar por.</label>
                                                    <select name="filtrar_por" id="filtrar_por" class="form-control select2" style="width: 100%;" > 
                                                        <option value="proveedor_sindeuda">proveedor sin deuda</option>
                                                        <option value="proveedor_condeuda">proveedor con deuda</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <style>
                                            table.colapsado {border-collapse: collapse;} 
                                            .clas_pading{ padding: 0.20rem  0.75rem  0.20rem  0.75rem !important;}
                                            .backgff9100{background-color: #ffe300;}
                                            .colorf0f8ff00{color: #f0f8ff00;}
                                            .text_area_clss{
                                                width: 280px;
                                                background: rgb(215, 224, 225);
                                                border-block-color: inherit;
                                                border-bottom: aliceblue;
                                                border-left: aliceblue;
                                                border-right: aliceblue;
                                                border-top: hidden;
                                            }
                                            .bg-red-resumen {
                                                background-color: #ff2036 !important;
                                                color: #ffffff !important;
                                            }
                                            
                                        </style>
                                       <div class="container table-responsive disenio-scroll">
                                            <!--Compras-->
                                            <table id="tabla1_compras" class="display" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                    <th colspan="8" class="text-center w-px-300 clas_pading backgff9100">Compras</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center clas_pading">#</th>
                                                        <th class="text-center w-px-300 clas_pading">PROVEEDOR</th>
                                                        <th class="text-center clas_pading">FECHA</th>
                                                        <th class="text-center clas_pading">DESCRIPCIÓN</th>
                                                        <th class="text-center clas_pading" >DETALLE</th>
                                                        <th class="text-center clas_pading">MONTOS</th>
                                                        <th class="text-center clas_pading">PAGOS</th>
                                                        <th class="text-center clas_pading">SALDOS</th>
                                                    </tr>
                                                </thead>
                                                
                                                <tbody  id="compras"></tbody>

                                                <tbody>
                                                    <tr>
                                                        <th colspan="4" class="clas_pading"></th>
                                                        <th  class="clas_pading text-right">Total</th>
                                                        <th  class="clas_pading text-right" id="monto_compras" ></th>
                                                        <th  class="clas_pading text-right" id="pago_compras" ></th>
                                                        <th  class="clas_pading text-right" id="saldo_compras" ></th>
                                                    </tr>
                                                </tbody>

                                                <tfoot></tfoot>

                                            </table>
                                            <br>
                                            <!--Servicios-Maquinaria-->
                                            <table id="tabla2_maquinaria" class="display" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                    <th colspan="8" class="text-center w-px-300 clas_pading backgff9100">Servicios-Maquinaria</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center clas_pading">#</th>
                                                        <th class="text-center w-px-300 clas_pading">PROVEEDOR</th>
                                                        <th class="text-center clas_pading">FECHA</th>
                                                        <th class="text-center clas_pading">DESCRIPCIÓN</th>
                                                        <th class="text-center clas_pading" >DETALLE</th>
                                                        <th class="text-center clas_pading">MONTOS</th>
                                                        <th class="text-center clas_pading">PAGOS</th>
                                                        <th class="text-center clas_pading">SALDOS</th>
                                                    </tr>
                                                </thead>

                                                <tbody  id="serv_maquinas"></tbody>

                                                <tbody>
                                                    <tr>
                                                        <th colspan="4" class="clas_pading"></th>
                                                        <th class="clas_pading text-right">Total</th>
                                                        <th class="clas_pading text-right" id="monto_serv_maq"></th>
                                                        <th class="clas_pading text-right" id="pago_serv_maq"></th>
                                                        <th class="clas_pading text-right" id="saldo_serv_maq"></th>
                                                    </tr>
                                                </tbody>
                                                <tfoot> </tfoot>

                                            </table>
                                            <br>
                                            <!--Servicios-Equipo-->
                                            <table id="tabla3_equipo" class="display" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                    <th colspan="8" class="text-center w-px-300 clas_pading backgff9100">Servicios-Equipo</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center clas_pading">#</th>
                                                        <th class="text-center w-px-300 clas_pading">PROVEEDOR</th>
                                                        <th class="text-center clas_pading">FECHA</th>
                                                        <th class="text-center clas_pading">DESCRIPCIÓN</th>
                                                        <th class="text-center clas_pading" >DETALLE</th>
                                                        <th class="text-center clas_pading">MONTOS</th>
                                                        <th class="text-center clas_pading">PAGOS</th>
                                                        <th class="text-center clas_pading">SALDOS</th>
                                                    </tr>
                                                </thead>
                                                
                                                <tbody  id="serv_equipos"></tbody>

                                                <tbody>
                                                    <tr>
                                                        <th colspan="4" class="clas_pading"></th>
                                                        <th  class="clas_pading text-right">Total</th>
                                                        <th class="clas_pading text-right" id="monto_serv_equi"></th>
                                                        <th class="clas_pading text-right" id="pago_serv_equi"></th>
                                                        <th class="clas_pading text-right" id="saldo_serv_equi"></th>
                                                    </tr>
                                                </tbody>

                                                <tfoot></tfoot>

                                            </table>
                                            <br>
                                            <!--Transporte-->
                                            <table id="tabla4_transporte" class="display" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                    <th colspan="8" class="text-center w-px-300 clas_pading backgff9100">Transporte</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center clas_pading">#</th>
                                                        <th class="text-center w-px-300 clas_pading">PROVEEDOR</th>
                                                        <th class="text-center clas_pading">FECHA</th>
                                                        <th class="text-center clas_pading">DESCRIPCIÓN</th>
                                                        <th class="text-center clas_pading" >DETALLE</th>
                                                        <th class="text-center clas_pading">MONTOS</th>
                                                        <th class="text-center clas_pading">PAGOS</th>
                                                        <th class="text-center clas_pading">SALDOS</th>
                                                    </tr>
                                                </thead>
                                                
                                                <tbody  id="transportes"></tbody>

                                                <tbody>
                                                    <tr>
                                                        <th colspan="4" class="clas_pading"></th>
                                                        <th  class="clas_pading text-right">Total</th>
                                                        <th class="clas_pading text-right" id="monto_transp"></th>
                                                        <th class="clas_pading text-right" id="pago_transp"></th>
                                                        <th class="clas_pading text-right" id="saldo_transp"></th>
                                                    </tr>
                                                </tbody>

                                                <tfoot></tfoot>

                                            </table>
                                            <br>
                                            <!--Hospedaje-->
                                            <table id="tabla5_hospedaje" class="display" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                    <th colspan="8" class="text-center w-px-300 clas_pading backgff9100">Hospedaje</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center clas_pading">#</th>
                                                        <th class="text-center w-px-300 clas_pading">PROVEEDOR</th>
                                                        <th class="text-center clas_pading">FECHA</th>
                                                        <th class="text-center clas_pading">DESCRIPCIÓN</th>
                                                        <th class="text-center clas_pading" >DETALLE</th>
                                                        <th class="text-center clas_pading">MONTOS</th>
                                                        <th class="text-center clas_pading">PAGOS</th>
                                                        <th class="text-center clas_pading">SALDOS</th>
                                                    </tr>
                                                </thead>
                                                
                                                <tbody id="hospedaje"></tbody>

                                                <tbody>
                                                    <tr>
                                                        <th colspan="4" class="clas_pading"></th>
                                                        <th  class="clas_pading text-right">Total</th>
                                                        <th class="clas_pading text-right" id="monto_hosped"></th>
                                                        <th class="clas_pading text-right" id="pago_hosped"></th>
                                                        <th class="clas_pading text-right" id="saldo_hosped"></th>
                                                    </tr>
                                                </tbody>

                                                <tfoot></tfoot>

                                            </table>
                                            <br>
                                            <!--Comidas extras-->
                                            <table id="tabla6_comidas_ex" class="display" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                    <th colspan="8" class="text-center w-px-300 clas_pading backgff9100">Comidas extras</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center clas_pading">#</th>
                                                        <th class="text-center w-px-300 clas_pading">PROVEEDOR</th>
                                                        <th class="text-center clas_pading">FECHA</th>
                                                        <th class="text-center clas_pading">DESCRIPCIÓN</th>
                                                        <th class="text-center clas_pading" >DETALLE</th>
                                                        <th class="text-center clas_pading">MONTOS</th>
                                                        <th class="text-center clas_pading">PAGOS</th>
                                                        <th class="text-center clas_pading">SALDOS</th>
                                                    </tr>
                                                </thead>
                                                                                            
                                                <tbody id="comida_extra"></tbody>

                                                <tbody>
                                                    <tr>
                                                        <th colspan="4" class="clas_pading"></th>
                                                        <th  class="clas_pading text-right">Total</th>
                                                        <th class="clas_pading text-right" id="monto_cextra"></th>
                                                        <th class="clas_pading text-right" id="pago_cextra"></th>
                                                        <th class="clas_pading text-right" id="saldo_cextra"></th>
                                                    </tr>
                                                </tbody>

                                                <tfoot></tfoot>

                                            </table>
                                            <br>
                                            <!--Breaks-->
                                            <table id="tabla7_breaks" class="display" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                    <th colspan="8" class="text-center w-px-300 clas_pading backgff9100">Breaks</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center clas_pading">#</th>
                                                        <th class="text-center w-px-300 clas_pading">PROVEEDOR</th>
                                                        <th class="text-center clas_pading">FECHA</th>
                                                        <th class="text-center clas_pading">DESCRIPCIÓN</th>
                                                        <th class="text-center clas_pading" >DETALLE</th>
                                                        <th class="text-center clas_pading">MONTOS</th>
                                                        <th class="text-center clas_pading">PAGOS</th>
                                                        <th class="text-center clas_pading">SALDOS</th>
                                                    </tr>
                                                </thead>
                                                   
                                                <tbody id="breaks"></tbody>

                                                <tbody>
                                                    <tr>
                                                        <th colspan="4" class="clas_pading"></th>
                                                        <th  class="clas_pading text-right">Total</th>
                                                        <th class="clas_pading text-right" id="monto_break"></th>
                                                        <th class="clas_pading text-right" id="pago_break"></th>
                                                        <th class="clas_pading text-right" id="saldo_break"></th>
                                                    </tr>
                                                </tbody>

                                                <tfoot></tfoot>

                                            </table>
                                            <br>
                                            <!--Pensión-->
                                            <table id="tabla8_pension" class="display" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                    <th colspan="8" class="text-center w-px-300 clas_pading backgff9100">Pensión</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center clas_pading">#</th>
                                                        <th class="text-center w-px-300 clas_pading">PROVEEDOR</th>
                                                        <th class="text-center clas_pading">FECHA</th>
                                                        <th class="text-center clas_pading">DESCRIPCIÓN</th>
                                                        <th class="text-center clas_pading" >DETALLE</th>
                                                        <th class="text-center clas_pading">MONTOS</th>
                                                        <th class="text-center clas_pading">PAGOS</th>
                                                        <th class="text-center clas_pading">SALDOS</th>
                                                    </tr>
                                                </thead>
                                                   
                                                <tbody id="pension"></tbody>

                                                <tbody>
                                                    <tr>
                                                        <th colspan="4" class="clas_pading"></th>
                                                        <th  class="clas_pading text-right">Total</th>
                                                    <th class="clas_pading text-right" id="monto_pension"></th>
                                                    <th class="clas_pading text-right" id="pago_pension"></th>
                                                    <th class="clas_pading text-right" id="saldo_pension"></th>
                                                    </tr>
                                                </tbody>

                                                <tfoot></tfoot>

                                            </table>
                                            <br>
                                            <!--Personal Administrativo-->
                                            <table id="tabla9_per_adm" class="display" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th colspan="8" class="text-center w-px-300 clas_pading backgff9100">Personal Administrativo</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center clas_pading">#</th>
                                                        <th class="text-center w-px-300 clas_pading">PROVEEDOR</th>
                                                        <th class="text-center clas_pading">FECHA</th>
                                                        <th class="text-center clas_pading">DESCRIPCIÓN</th>
                                                        <th class="text-center clas_pading" >DETALLE</th>
                                                        <th class="text-center clas_pading">MONTOS</th>
                                                        <th class="text-center clas_pading">PAGOS</th>
                                                        <th class="text-center clas_pading">SALDOS</th>
                                                    </tr>
                                                </thead>
                                                   
                                                <tbody id="administrativo"></tbody>

                                                <tbody>
                                                    <tr>
                                                        <th colspan="4" class="clas_pading"></th>
                                                        <th  class="clas_pading text-right">Total</th>
                                                        <th class="clas_pading text-right" id="monto_adm"></th>
                                                        <th class="clas_pading text-right" id="pago_adm"></th>
                                                        <th class="clas_pading text-right" id="saldo_adm"></th>
                                                    </tr>
                                                </tbody>

                                                <tfoot></tfoot>

                                            </table>
                                            <br>
                                            <!--Personal obrero-->
                                            <table id="tabla10_per_obr" class="display" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th colspan="8" class="text-center w-px-300 clas_pading backgff9100">Personal Obrero</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center clas_pading">#</th>
                                                        <th class="text-center w-px-300 clas_pading">PROVEEDOR</th>
                                                        <th class="text-center clas_pading">FECHA</th>
                                                        <th class="text-center clas_pading">DESCRIPCIÓN</th>
                                                        <th class="text-center clas_pading" >DETALLE</th>
                                                        <th class="text-center clas_pading">MONTOS</th>
                                                        <th class="text-center clas_pading">PAGOS</th>
                                                        <th class="text-center clas_pading">SALDOS</th>
                                                    </tr>
                                                </thead>
                                                   
                                                <tbody id="obrero"></tbody>

                                                <tbody>
                                                    <tr>
                                                        <th colspan="4" class="clas_pading"></th>
                                                        <th  class="clas_pading text-right">Total</th>
                                                        <th class="clas_pading text-right" id="monto_obrero"></th>
                                                        <th class="clas_pading text-right" id="pago_obrero"></th>
                                                        <th class="clas_pading text-right" id="saldo_obrero"></th>
                                                    </tr>
                                                </tbody>

                                                <tfoot></tfoot>

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
                                    <h4 class="modal-title"> <span id="detalle_"></span> <b id="nombre_proveedor_"></b></h4>
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
                                                <th>Unidad M.</th>
                                                <th>Cantidad</th>
                                                <th>Costo Unitario</th>
                                                <th>Costo Parcial</th>
                                                <th>Descripción</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Unidad M.</th>
                                                <th>Cantidad</th>
                                                <th>Costo Unitario</th>
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
                    <!--modal-ver-ver-detalle-t-administ-->
                    <div class="modal fade" id="modal-ver-detalle-t-administ">
                        <div class="modal-dialog modal-dialog-scrollable modal-xl">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Detalles: <b id="nombre_trabajador_detalle"></b> </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span class="text-danger" aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <div  class="class-style tabla" style="text-align: center;"> 
                                    <div class="table-responsive" id="tbl-fechas" >
                                        <div class="table-responsive-lg">
                                            <table class="table styletabla table-hover text-nowrap" style="border: black 1px solid;">
                                                <thead>
                                                    <tr class="bg-gradient-info">
                                                        <th class="stile-celda">N°</th>
                                                        <th class="stile-celda">Mes</th>
                                                        <th colspan="2" class="stile-celda">Fechas Inicial/Final</th>
                                                        <th class="stile-celda text-center">Días laborables</th>
                                                        <th class="stile-celda text-center">Sueldo estimado</th>
                                                        <th class="stile-celda">Depósitos</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="tcuerpo data-detalle-pagos-administador">
                                                    <!--deatlle de los pagos adm-->
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td class="text-right sueldo_estimado"></td>
                                                        <td class="text-right depositos"></td>
                                                    </tr>
                                                </tbody>
                                                <tfoot class="">
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th class="stile-celda-right sueldo_estimado"></th>
                                                        <th class="stile-celda-right depositos"></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div style="display: none;" class="alerta">
                                    <div class="alert alert-warning alert-dismissible">
                                        <h5><i class="icon fas fa-exclamation-triangle fa-3x text-white"></i> <b>No hay pagos!</b> </h5>
                                        No hay detalles de pagos para mostrar, puede registrar pagos en el módulo <b>pagos trabajador.</b> 
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <!--modal-ver-ver-detalle-t-obrero-->
                    <div class="modal fade" id="modal-ver-detalle-t-obrero">
                        <div class="modal-dialog modal-dialog-scrollable modal-xl">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Detalles: <b id="nombre_trabajador_ob_detalle"></b> </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span class="text-danger" aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <div  class="class-style tabla_obrero" style="text-align: center;"> 
                                    <div class="table-responsive" id="tbl-fechas">
                                        <div class="table-responsive-lg disenio-scroll">
                                            <table class="table styletabla table-hover text-nowrap" style="border: black 1px solid;">
                                                <thead>                                  
                                                    <tr class="bg-gradient-info">
                                                    <th rowspan="2" class="stile-celda">N°</th>                                   
                                                    <th colspan="3" class="stile-celda pt-0 pb-0 nombre-bloque-asistencia"><b> Quincena </b></th>
                                                    <th rowspan="2" class="stile-celda text-center">Sueldo Hora</th>
                                                    <th rowspan="2" class="stile-celda text-center">Horas Normal/Extra</th>
                                                    <th rowspan="2" class="stile-celda text-center">Sabatical</th>
                                                    <th rowspan="2" class="stile-celda">Monto Normal/Extra</th>
                                                    <th rowspan="2" class="stile-celda text-center">Adicional</th>                                  
                                                    <th rowspan="2" class="stile-celda">Monto total</th>
                                                    <th rowspan="2" class="stile-celda ">Deposito</th> 
                                                    <th rowspan="2" class="stile-celda ">Saldo</th>
                                                    </tr>
                                                    <tr class="bg-gradient-info">                                                                     
                                                    <th class="stile-celda pt-0 pb-0">N°</th>
                                                    <th class="stile-celda pt-0 pb-0">Inicial</th>
                                                    <th class="stile-celda pt-0 pb-0">Final</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="tcuerpo detalle-data-q-s"></tbody>
                                                <tfoot>
                                                    <tr>                                    
                                                    <th colspan="5"></th> 
                                                    <th class="stile-celda total_hn_he"></th>
                                                    <th class="stile-celda total_sabatical"></th>
                                                    <th class="stile-celda total_monto_hn_he"></th> 
                                                    <th class="stile-celda-right total_descuento"></th>
                                                    <th class="stile-celda-right total_quincena"></th> 
                                                    <th class="stile-celda-right total_deposito"></th>                           
                                                    <th class="stile-celda-right total_saldo"></th> 
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div style="display: none;" class="alerta_obrero">
                                    <div class="alert alert-warning alert-dismissible">
                                        <h5><i class="icon fas fa-exclamation-triangle fa-3x text-white"></i> <b>No hay pagos!</b> </h5>
                                        No hay detalles de pagos para mostrar, puede registrar pagos en el módulo <b>pagos trabajador.</b> 
                                    </div>
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