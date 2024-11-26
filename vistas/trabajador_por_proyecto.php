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
        <title>Trabajadores por Proyecto | Admin Sevens</title>

        <?php $title = "Trabajadores"; require 'head.php'; ?>
        <!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css"> -->
        <style>
          #sortable { list-style-type: none !important; margin: 0 !important; padding: 0 !important; width: 60% !important; }
          #sortable li { margin: 0 3px 3px 3px !important; padding: 0.4em !important; padding-left: 1.5em !important; font-size: 1.4em !important; height: 18px !important; }
          #sortable li span { position: absolute !important; margin-left: -1.3em !important; }
        </style>
      </head> 
      <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed pace-orange" idproyecto="<?php echo $_SESSION['idproyecto']; ?>">
        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper"> 
          <?php
          require 'nav.php';
          require 'aside.php';
          if ($_SESSION['trabajador']==1){
            //require 'enmantenimiento.php';
            ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
              <!-- Content Header (Page header) -->
              <section class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1><img src="../dist/svg/negro-constructor-ico.svg" class="nav-icon" alt="" style="width: 21px !important;" > Trabajador por Proyecto</h1>
                    </div>
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Trabajador</li>
                      </ol>
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
                        <div class="card-header">
                          <h3 class="card-title">
                            <button type="button" class="btn bg-gradient-success btn-agregar-trabajador"  onclick="show_hide_form(2); limpiar_form_trabajador(); trabajador_no_usado();"><i class="fas fa-user-plus"></i> Agregar</button>
                            <button type="button" class="btn bg-gradient-info"  onclick="ver_lista_orden();"><i class="fa-solid fa-arrow-down-short-wide"></i> Ordenar</button>
                            Administra de manera eficiente a los trabajdores
                          </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                          <!-- Lista de trabajdores -->
                          <div id="mostrar-tabla">
                            <table id="tabla-trabajador" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">Aciones</th>
                                  <th>Nombre Completo</th>
                                  <th>Fechas</th>
                                  <th>Celular</th>
                                  <th>Nacimiento</th>
                                  <th>Cargos</th>                                  
                                  <th>Cuenta</th> 

                                  <th>Nombre Completo</th>
                                  <th>Tipo Doc.</th>
                                  <th>Num. Doc.</th>
                                  <th>Correo</th>
                                  <th>Fecha inicial</th>
                                  <th>Fecha final</th>
                                  <th>Tipo</th>
                                  <th>Ocupación</th>
                                  <th>Desempeño</th>
                                  <th>Banco</th>
                                  <th>Cta. Bancaria</th>
                                </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">Aciones</th>
                                  <th>Nombre Completo</th>
                                  <th>Fechas</th>
                                  <th>Celular</th>
                                  <th>Nacimiento</th>
                                  <th>Cargos</th>                                  
                                  <th>Cuenta</th> 

                                  <th>Nombre Completo</th>
                                  <th>Tipo Doc.</th>
                                  <th>Num. Doc.</th>
                                  <th>Correo</th>
                                  <th>Fecha inicial</th>
                                  <th>Fecha final</th>
                                  <th>Tipo</th>
                                  <th>Ocupación</th>
                                  <th>Desempeño</th>
                                  <th>Banco</th>
                                  <th>Cta. Bancaria</th>
                                </tr>
                              </tfoot>
                            </table>

                            <div class="mt-4 card-danger card-outline">
                              <h1 style="text-align: center;background-color: aliceblue;">Trabajador Suspendido</h1>
                              <table id="tabla-trabajador-suspendido" class="table table-bordered table-striped display" style="width: 100% !important;">
                                <thead>
                                  <tr>
                                    <th class="">#</th>
                                    <th class="">Aciones</th>
                                    <th>Nombre Completo</th>
                                    <th>Fechas</th>
                                    <th>Celular</th>
                                    <th>Correo</th>
                                    <th>Nacimiento</th>
                                    <th>Tipo</th>
                                    <th>Ocupación</th>
                                    <th>Desempeño</th>                                  
                                    <th>Cuenta</th> 

                                    <th>Nombre Completo</th>
                                    <th>DNI</th>
                                    <th>Fecha inicial</th>
                                    <th>Fecha final</th>
                                    <th>Banco</th>
                                    <th>Cta. Bancaria</th>
                                  </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                  <tr>
                                    <th class="">#</th>
                                    <th class="">Aciones</th>
                                    <th>Nombre Completo</th>
                                    <th>Fechas</th>
                                    <th>Celular</th>
                                    <th>Correo</th>
                                    <th>Nacimiento</th>
                                    <th>Tipo</th>
                                    <th>Ocupación</th>
                                    <th>Desempeño</th>
                                    <th>Cuenta</th> 

                                    <th>Nombre Completo</th>
                                    <th>DNI</th>
                                    <th>Fecha inicial</th>
                                    <th>Fecha final</th>
                                    <th>Banco</th>
                                    <th>Cta. Bancaria</th>
                                  </tr>
                                </tfoot>
                              </table>
                            </div>
                          </div>

                          <!-- agregar trabajador al sistema -->
                          <div id="mostrar-form" style="display: none;">
                            
                            <!-- form start -->
                            <form id="form-trabajador-proyecto" name="form-trabajador-proyecto" method="POST">
                              <div class="card-body">
                                <div class="row" id="cargando-1-fomulario">
                                  <!-- id PROYECTO -->
                                  <input type="hidden" name="idproyecto" id="idproyecto" />
                                  <input type="hidden" name="idtrabajador_por_proyecto" id="idtrabajador_por_proyecto" />

                                  <!-- Trabajador -->
                                  <div class="col-lg-5"> 
                                    <div class="form-group">
                                      <label for="trabajador" id="trabajador_c">Trabajador <sup class="text-danger">(unico*)</sup></label>                               
                                      <select name="trabajador" id="trabajador" class="form-control select2" onchange="capture_idtrabajador(estado_editar = false);" style="width: 100%;">                                    
                                      </select>
                                    </div>                                                        
                                  </div>                                  

                                  <!-- ADD TRABAJADOR -->
                                  <div class="col-lg-1">
                                    <div class="form-group">
                                    <label for="Add" class="d-none d-sm-inline-block text-break" style="color: white;">.</label> <br class="d-none d-sm-inline-block">
                                      <a data-toggle="modal" href="#modal-agregar-all-trabajador" >
                                        <button type="button" class="btn btn-success p-x-6px" data-toggle="tooltip" data-original-title="Agregar Trabajador" onclick="limpiar_form_all_trabajador();">
                                          <i class="fa fa-user-plus" aria-hidden="true"></i>
                                        </button>
                                      </a>
                                      <button type="button" class="btn btn-warning p-x-6px btn-editar-trabajador" data-toggle="tooltip" data-original-title="Editar:" onclick="mostrar_editar_trabajador();">
                                        <i class="fa-solid fa-pencil" aria-hidden="true"></i>
                                      </button>
                                    </div>
                                  </div>

                                  <!-- Tipo trabajador -->
                                  <div class="col-lg-3">
                                    <div class="form-group">
                                      <label for="tipo_trabajador">Tipo Trabajador</label>       
                                      <span class="form-control-mejorado" id="tipo_trabajador"></span>
                                    </div>  
                                  </div>

                                  <!-- Tipo Ocupación -->
                                  <div class="col-lg-3">
                                    <div class="form-group">
                                      <label for="ocupacion">Ocupación</label>  <br>                                 
                                      <span class="form-control-mejorado" id="ocupacion"></span>
                                    </div>
                                  </div>
                                  
                                  <!-- Desempeño -->
                                  <div class="col-lg-6">
                                    <div class="form-group">
                                      <label for="desempeño">Desempeño <span id="desempenio_charge"></span> </label>
                                      <select name="desempenio" id="desempenio" class="form-control select2" style="width: 100%;"  > 
                                      </select>
                                    </div>
                                  </div>                                   

                                  <!-- FECHA INICIO -->
                                  <div class="col-lg-2 ">
                                    <div class="form-group">
                                      <label>Fecha Inicio:</label>
                                      <div class="input-group date"  data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#fecha_inicio" id="fecha_inicio" name="fecha_inicio" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask onchange="calcular_dias_trabajo(); validar_fecha_rango();" autocomplete="off" />
                                        <div class="input-group-append" data-target="#fecha_inicio" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                      </div>                                 
                                    </div>
                                  </div>                               

                                  <!-- FECHA FIN -->
                                  <div class="col-lg-2">
                                    <div class="form-group">
                                      <label>Fecha Fin:</label>
                                      <div class="input-group date"  data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#fecha_fin" id="fecha_fin" name="fecha_fin" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask onchange="calcular_dias_trabajo(); validar_fecha_rango(0);" />
                                        <div class="input-group-append" data-target="#fecha_fin" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                      </div>                                 
                                    </div>
                                  </div>                                 

                                  <!-- Cantidad de Dias -->
                                  <div class="col-lg-2">
                                    <div class="form-group">
                                      <label for="cantidad_dias">Cantidad de dias</label>
                                      <input type="number" name="cantidad_dias" class="form-control" id="cantidad_dias" step="any" readonly />
                                    </div>
                                  </div> 

                                  <div class="col-12">
                                    <div class="row">
                                      <!-- Sueldo(Semanal) -->
                                      <div class="col-lg-3">
                                        <div class="form-group">
                                          <label for="sueldo_semanal">Sueldo(Semanal)</label>
                                          <input type="text" step="any" name="sueldo_semanal[]" class="form-control sueldo_semanal_0" readonly />
                                          <input type="hidden" step="any" name="sueldo_mensual[]" class="form-control sueldo_mensual_0" readonly />
                                        </div>
                                      </div>
                                      <!-- Sueldo(Diario) -->
                                      <div class="col-lg-2">
                                        <div class="form-group">
                                          <label for="sueldo_diario">Sueldo(Diario)</label>
                                          <input type="text" step="any" name="sueldo_diario[]" class="form-control sueldo_diario_0" onchange="salary_semanal(0);" onkeyup="salary_semanal(0);" onclick="this.select();"  />
                                        </div>
                                      </div>
                                      <!-- Sueldo(Hora) -->
                                      <div class="col-lg-2">
                                        <div class="form-group">
                                          <label for="sueldo_hora">Sueldo(8 Hora)</label>
                                          <input type="text" step="any" name="sueldo_hora[]" class="form-control sueldo_hora_0" readonly />
                                        </div>
                                      </div>
                                      <!-- Fecha inicial -->
                                      <div class="col-lg-2">
                                        <div class="form-group">
                                          <label for="fecha_desde">Desde</label>
                                          <input type="date" name="fecha_desde[]" class="form-control fecha_inicial fecha_desde_0"  placeholder="Fecha" />
                                        </div>
                                      </div>

                                      <!-- Fecha final -->
                                      <div class="col-lg-2">
                                        <div class="form-group">
                                          <label for="fecha_hasta">Hasta</label>
                                          <input type="date" name="fecha_hasta[]" class="form-control fecha_final fecha_hasta_0" placeholder="Fecha" />
                                        </div>
                                      </div>
                                      <!-- boton -->
                                      <div class="col-12 col-sm-12 col-md-6 col-lg-1">
                                        <div class="form-group mb-2">
                                          <div class="custom-control custom-radio ">
                                            <input class="custom-control-input custom-control-input-danger" type="radio" id="sueldo_seleccionado_0" name="sueldo_seleccionado" value="0" checked onclick="replicar_sueldo_actual(0);">
                                            <label for="sueldo_seleccionado_0" class="custom-control-label">Usar</label>
                                            <input type="hidden" name="sueldo_actual[]" class="sueldo_actual" id="sueldo_actual_0" value="1" >
                                          </div>
                                        </div>
                                        <button type="button" class="btn bg-gradient-success btn-sm" onclick="add_sueldo();" data-toggle="tooltip" data-original-title="Agregar neva fila"><i class="fas fa-plus"></i></button>
                                      </div>
                                      
                                      <div class="col-12 col-sm-12 col-md-6 col-lg-12">
                                        <div class="row" id="lista_sueldo"> </div>
                                      </div>
                                    </div>
                                  </div>                                  

                                  <!-- barprogress -->
                                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 m-l-20px" id="barra_progress_trabajador_div" style="display: none;" >
                                    <div class="progress">
                                      <div id="barra_progress_trabajador" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
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
                              <div class=" justify-content-between">
                                <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="show_hide_form(1);"> <i class="fas fa-arrow-left"></i> Close</button>
                                <button type="submit" class="btn btn-success" id="guardar_registro_trabajador">Guardar Cambios</button>
                              </div>
                            </form>

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

                <!-- MODAL - agregar trabajador -->
                <div class="modal fade" id="modal-agregar-trabajador">
                  <div class="modal-dialog modal-dialog-scrollable modal-xl">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Agregar trabajador</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        
                      </div>
                      
                    </div>
                  </div>
                </div>

                <!-- MODAL - ver trabajador-->
                <div class="modal fade" id="modal-ver-trabajador">
                  <div class="modal-dialog modal-dialog-scrollable modal-xm">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Datos trabajador</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <div id="datostrabajador"  >
                          
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- MODAL - agregar trabajador -->
                <div class="modal fade" id="modal-agregar-all-trabajador">
                  <div class="modal-dialog modal-dialog-scrollable modal-xl">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title modal-title-all-trabajador">Agregar All-Trabajador</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body ">
                        <!-- form start -->
                        <form id="form-all-trabajador" name="form-all-trabajador" method="POST">
                          <div class="card-body">
                            <div class="row" id="cargando-3-fomulario">
                              <!-- id trabajador -->
                              <input type="hidden" name="idtrabajador_all" id="idtrabajador_all" />

                              <!-- Tipo de documento -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="tipo_documento_all">Tipo de documento</label>
                                  <select name="tipo_documento_all" id="tipo_documento_all" class="form-control" placeholder="Tipo de documento">
                                    <option selected value="DNI">DNI</option>
                                    <option value="RUC">RUC</option>
                                    <option value="CEDULA">CEDULA</option>
                                    <option value="OTRO">OTRO</option>
                                  </select>
                                </div>
                              </div>

                              <!-- N° de documento -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="num_documento_all">N° de documento <sup class="text-danger">(unico*)</sup></label>
                                  <div class="input-group">
                                    <input type="number" name="num_documento_all" class="form-control" id="num_documento_all" placeholder="N° de documento" />
                                    <div class="input-group-append" data-toggle="tooltip" data-original-title="Buscar Reniec/SUNAT" onclick="buscar_sunat_reniec('_all');">
                                      <span class="input-group-text" style="cursor: pointer;">
                                        <i class="fas fa-search text-primary" id="search_all"></i>
                                        <i class="fa fa-spinner fa-pulse fa-fw fa-lg text-primary" id="charge_all" style="display: none;"></i>
                                      </span>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <!-- Nombre -->
                              <div class="col-12 col-sm-12 col-md-12 col-lg-4">
                                <div class="form-group">
                                  <label for="nombre_all">Nombre y Apellidos/Razon Social</label>
                                  <input type="text" name="nombre_all" class="form-control" id="nombre_all" placeholder="Nombres y apellidos" />
                                </div>
                              </div>

                              <!-- Correo electronico -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="email_all">Correo electrónico</label>
                                  <input type="email" name="email_all" class="form-control" id="email_all" placeholder="Correo electrónico" onkeyup="convert_minuscula(this);" />
                                </div>
                              </div>
                              
                              <!-- Telefono -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="telefono_all">Teléfono</label>
                                  <input type="text" name="telefono_all" id="telefono_all" class="form-control" data-inputmask="'mask': ['999-999-999', '+51 999 999 999']" data-mask />
                                </div>
                              </div>                              

                              <!-- FECHA NACIMIENTO -->
                              <div class="col-lg-3 ">
                                <div class="form-group">
                                  <label>Nacimiento:</label>
                                  <div class="input-group date"  data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#nacimiento_all" id="nacimiento_all" name="nacimiento_all" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask onchange="calcular_edad('#nacimiento_all','#input_edad','#span_edad');" autocomplete="off" />
                                    <div class="input-group-append" data-target="#nacimiento_all" data-toggle="datetimepicker">
                                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                  </div>                                 
                                </div>
                              </div>    
                              <!-- <div class="col-12 col-sm-10 col-md-6 col-lg-3">
                                <div class="form-group">
                                  <label for="">Nacimiento: <sup class="text-danger">*</sup></label>
                                  <div class="input-group date"  data-target-input="nearest">
                                    <input type="text" class="form-control" id="nacimiento_all" name="nacimiento_all" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask onchange="calcular_edad('#nacimiento_all','#input_edad','#span_edad');"  />
                                    <div class="input-group-append click-btn-nacimiento_all cursor-pointer" for="nacimiento_all" >
                                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                  </div>                                 
                                </div>
                              </div> -->

                              <!-- edad -->
                              <div class="col-12 col-sm-2 col-md-6 col-lg-1">
                                <div class="form-group">
                                  <label for="edad">Edad</label>
                                  <p id="span_edad" style="border: 1px solid #ced4da; border-radius: 4px; padding: 5px;">0 años.</p>
                                  <input type="hidden" name="input_edad" id="input_edad" />
                                </div>
                              </div>

                              <!-- banco -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-3">
                                <div class="form-group">
                                  <label for="banco_0">Banco</label>
                                  <select name="banco_0" id="banco_0" class="form-control select2 banco_0" style="width: 100%;" onchange="formato_banco(0);">
                                    <!-- Aqui listamos los bancos -->
                                  </select>
                                  <input type="hidden" name="banco_array[]" id="banco_array_0" >
                                </div>
                              </div>

                              <!-- Cuenta bancaria -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="cta_bancaria" class="0_chargue-format-1">Cuenta Bancaria</label>
                                  <input type="text" name="cta_bancaria[]" class="form-control cta_bancaria_0" id="cta_bancaria" placeholder="Cuenta Bancaria" data-inputmask="" data-mask />
                                </div>
                              </div>

                              <!-- CCI -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="cci" class="0_chargue-format-2">CCI</label>
                                  <input type="text" name="cci[]" class="form-control cci_0" id="cci" placeholder="CCI" data-inputmask="" data-mask />
                                </div>
                              </div> 

                              <div class="col-12 col-sm-12 col-md-6 col-lg-1">
                                <div class="form-group mb-2">
                                  <div class="custom-control custom-radio ">
                                    <input class="custom-control-input custom-control-input-danger" type="radio" id="banco_seleccionado_0" name="banco_seleccionado" value="0" checked>
                                    <label for="banco_seleccionado_0" class="custom-control-label">Usar</label>
                                  </div>
                                </div>
                                <button type="button" class="btn bg-gradient-success btn-sm" onclick="add_bancos();" data-toggle="tooltip" data-original-title="Agregar neva fila"><i class="fas fa-plus"></i></button>

                              </div>

                              <div class="col-12 col-sm-12 col-md-6 col-lg-12">
                                <div class="row" id="lista_bancos"> </div>
                              </div>
                              

                              <!-- Titular de la cuenta -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="titular_cuenta_all">Titular de la cuenta</label>
                                  <input type="text" name="titular_cuenta_all" class="form-control" id="titular_cuenta_all" placeholder="Titular de la cuenta" />
                                </div>
                              </div>

                              <!-- tipo -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="tipo_all">Tipo</label>
                                  <select name="tipo_all" id="tipo_all" class="form-control select2" style="width: 100%;"> </select>
                                  <!--<input type="hidden" name="color_old" id="color_old" />-->
                                </div>
                              </div>

                              <!-- ocupacion -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="ocupacion_all">Ocupación</label>
                                  <select name="ocupacion_all" id="ocupacion_all" class="form-control select2"  style="width: 100%;"> </select>
                                  <!--<input type="hidden" name="color_old" id="color_old" />-->
                                </div>
                              </div>

                              <!-- ocupacion -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-8">
                                <div class="form-group">
                                  <label for="desempenio_all">Desempeño</label>
                                  <select name="desempenio_all[]" id="desempenio_all" class="form-control select2"  multiple="multiple" style="width: 100%;"> </select>
                                  <!--<input type="hidden" name="color_old" id="color_old" />-->
                                </div>
                              </div>

                              <!-- Ruc -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="ruc_all">Ruc</label>
                                  <input type="number" name="ruc_all" class="form-control" id="ruc_all" placeholder="Ingrese número de ruc" />
                                </div>
                              </div>

                              <!-- Talla ropa talla_ropa_all,talla_zapato_all-->

                              <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                                <div class="form-group">
                                  <label for="talla_ropa_all">Talla ropa</label>
                                  <select name="talla_ropa_all" id="talla_ropa_all" class="form-control" placeholder="Talla ropa">
                                    <option value="16">16</option>
                                    <option value="S">S</option>
                                    <option value="M">M</option>
                                    <option value="L">L</option>
                                    <option value="XL">XL</option>
                                    <option value="XXL">XXL</option>
                                    <option value="XXXL">XXXL</option>
                                  </select>
                                </div>
                              </div>
                              
                              <!-- Talla zapato -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-3">
                                <div class="form-group">
                                  <label for="talla_zapato">Talla zapato</label>
                                  <input type="number" name="talla_zapato_all" class="form-control" id="talla_zapato_all" placeholder="Talla zapato" />
                                </div>
                              </div>

                              <!-- Direccion -->
                              <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                                <div class="form-group">
                                  <label for="direccion_all">Dirección</label>
                                  <input type="text" name="direccion_all" class="form-control" id="direccion_all" placeholder="Dirección" />
                                </div>
                              </div>

                              <!-- imagen perfil -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                                <div class="col-lg-12 borde-arriba-naranja mt-2 mb-2"></div>
                                <label for="foto1">Foto de perfil</label> <br />
                                <img onerror="this.src='../dist/img/default/img_defecto.png';" src="../dist/img/default/img_defecto.png" class="img-thumbnail" id="foto1_i" style="cursor: pointer !important;" width="auto" />
                                <input style="display: none;" type="file" name="foto1" id="foto1" accept="image/*" />
                                <input type="hidden" name="foto1_actual" id="foto1_actual" />
                                <div class="text-center" id="foto1_nombre"><!-- aqui va el nombre de la FOTO --></div>
                              </div>

                              <!-- imagen dni anverso -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                                <div class="col-lg-12 borde-arriba-naranja mt-2 mb-2"></div>
                                <label for="foto2">DNI anverso</label> <br />
                                <img onerror="this.src='../dist/img/default/dni_anverso.webp';" src="../dist/img/default/dni_anverso.webp" class="img-thumbnail" id="foto2_i" style="cursor: pointer !important;" width="auto" />
                                <input style="display: none;" type="file" name="foto2" id="foto2" accept="image/*" />
                                <input type="hidden" name="foto2_actual" id="foto2_actual" />
                                <div class="text-center" id="foto2_nombre"><!-- aqui va el nombre de la FOTO --></div>
                              </div>

                              <!-- imagen dni reverso -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                                <div class="col-lg-12 borde-arriba-naranja mt-2 mb-2"></div>
                                <label for="foto3">DNI reverso</label> <br />
                                <img onerror="this.src='../dist/img/default/dni_reverso.webp';" src="../dist/img/default/dni_reverso.webp" class="img-thumbnail" id="foto3_i" style="cursor: pointer !important;" width="auto" />
                                <input style="display: none;" type="file" name="foto3" id="foto3" accept="image/*" />
                                <input type="hidden" name="foto3_actual" id="foto3_actual" />
                                <div class="text-center" id="foto3_nombre"><!-- aqui va el nombre de la FOTO --></div>
                              </div>

                              <!-- Pdf 4 -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-4 mt-2">
                                <!-- linea divisoria -->
                                <div class="col-lg-12 borde-arriba-naranja mt-2"></div>
                                <div class="row">
                                  <div class="col-md-12 p-t-15px p-b-5px" >
                                    <label for="Presupuesto" class="control-label">CV Documentado</label>
                                  </div>
                                  <div class="col-6 col-md-6 col-lg-6 col-xl-6 text-center">
                                    <button type="button" class="btn btn-success btn-block btn-xs" id="doc4_i"><i class="fas fa-file-upload"></i> Subir.</button>
                                    <input type="hidden" id="doc_old_4" name="doc_old_4" />
                                    <input style="display: none;" id="doc4" type="file" name="doc4" accept=".pdf, .docx, .doc" class="docpdf" />
                                  </div>
                                  <div class="col-6 col-md-6 col-lg-6 col-xl-6 text-center">
                                    <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(4, 'all_trabajador', 'cv_documentado');"><i class="fa fa-eye"></i> PDF.</button>
                                  </div>
                                </div>
                                <div id="doc4_ver" class="text-center mt-4">
                                  <img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" />
                                </div>
                                <div class="text-center" id="doc4_nombre"><!-- aqui va el nombre del pdf --></div>
                              </div>

                              <!-- Pdf 5 -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-4 mt-2">
                                <!-- linea divisoria -->
                                <div class="col-lg-12 borde-arriba-naranja mt-2"></div> 
                                <div class="row">
                                  <div class="col-md-12 p-t-15px p-b-5px">
                                    <label for="analisis-de-costos-unitarios" class="control-label"> CV No Documentado</label>
                                  </div>
                                  <div class="col-6 col-md-6 col-lg-6 col-xl-6 text-center">
                                    <button type="button" class="btn btn-success btn-block btn-xs" id="doc5_i"><i class="fas fa-file-upload"></i> Subir.</button>
                                    <input type="hidden" id="doc_old_5" name="doc_old_5" />
                                    <input style="display: none;" id="doc5" type="file" name="doc5" accept=".pdf, .docx, .doc" class="docpdf" />
                                  </div>
                                  <div class="col-6 col-md-6 col-lg-6 col-xl-6 text-center">
                                    <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(5, 'all_trabajador', 'cv_no_documentado');"><i class="fa fa-eye"></i> PDF.</button>
                                  </div>
                                </div>
                                <div id="doc5_ver" class="text-center mt-4">
                                  <img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" />
                                </div>
                                <div class="text-center" id="doc5_nombre"><!-- aqui va el nombre del pdf --></div>
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

                            <div class="row" id="cargando-4-fomulario" style="display: none;">
                              <div class="col-lg-12 text-center">
                                <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                                <br />
                                <h4>Cargando...</h4>
                              </div>
                            </div>
                          </div>
                          <!-- /.card-body -->
                          <button type="submit" style="display: none;" id="submit-form-all-trabajador">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" onclick="limpiar_form_all_trabajador();" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro_all_trabajador">Guardar Cambios</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- MODAL - ORNDENAR-->
                <div class="modal fade" id="modal-order-trabajador">
                  <div class="modal-dialog modal-dialog-scrollable modal-xl">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Datos trabajador</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <form id="form-orden-trabajador" name="form-orden-trabajador" method="POST">
                          <div class="row">
                            <div class="col-6">
                              <table class="table table-bordered /*table-striped*/ table-hover text-nowrap" id="tbla_export_excel_orden_1" >
                                <thead>                             
                                  <tr class="text-center bg-color-48acc6"> 
                                    <th class="pt-1 pb-1 celda-b-b-2px">#</th> 
                                    <th class="pt-1 pb-1 celda-b-b-2px">Nombre</th>
                                  </tr>
                                </thead>
                                <tbody id="html_order_trabajador_1" class="orden_trabajador_1">  </tbody>                            
                              </table>
                            </div>
                            <div class="col-6">
                              <table class="table table-bordered /*table-striped*/ table-hover text-nowrap" id="tbla_export_excel_orden_2" >
                                <thead>                             
                                  <tr class="text-center bg-color-48acc6"> 
                                    <th class="pt-1 pb-1 celda-b-b-2px">#</th> 
                                    <th class="pt-1 pb-1 celda-b-b-2px">Nombre</th>
                                  </tr>
                                </thead>
                                <tbody id="html_order_trabajador_2" class="orden_trabajador_2">  </tbody>                            
                              </table> 
                            </div>
                          </div>                            

                          <button type="submit" style="display: none;" id="submit-form-orden-trabajador">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger"  data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro_orden_trabajador">Guardar Cambios</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- MODAL - VER PERFIL TRABAJADOR-->
                <div class="modal fade" id="modal-ver-perfil-trabajador">
                  <div class="modal-dialog modal-dialog-centered modal-md">
                    <div class="modal-content bg-color-02020280 shadow-none border-0">
                      <div class="modal-header">
                        <h4 class="modal-title text-white modal-title-perfil-trabajador">Foto Perfil</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-white cursor-pointer" aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body text-center" id="html-perfil-trabajador" >                         
                        <!-- vemos los datos del trabajador -->                       
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

        <?php require 'script.php'; ?>
        
        <!-- moment LOCALE -->
        <script src="../plugins/moment/locales.js"></script>
        
        <!-- Jquery UI -->
        <script src="../plugins/jquery-ui/jquery-ui.min.js"></script>
        <script src="../plugins/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

        <script type="text/javascript" src="scripts/trabajador_por_proyecto.js?version_jdl=1.8"></script>

        <script>  $(function () { $('[data-toggle="tooltip"]').tooltip();  }); </script>
        
      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
