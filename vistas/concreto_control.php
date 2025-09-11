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
        <title>Valorización | Admin Sevens</title>

        <?php $title = "Valorización"; require 'head.php'; ?>
        <link rel="stylesheet" href="../plugins/bootstrap-table/dist/bootstrap-table.min.css">
        <!-- <link rel="stylesheet" href="../plugins/excel-preview/css/excel-preview.css"> -->
         <style>
          .fila-input_new {
            border: none;                 /* elimina todos los bordes */
            border-bottom: 1px dashed #bbb8b8ff; /* solo borde inferior dashed */
            outline: none;                /* quita el contorno al hacer focus */
            box-shadow: none;             /* elimina sombras si las hay por Bootstrap */
            padding: 4px 0;               /* opcional: espacio interno para que no se vea pegado */
            background: transparent;      /* opcional: que el fondo sea transparente */
          }
          .fila-input {
              border: none;
              outline: none; /* elimina el contorno al hacer focus */
              box-shadow: none; /* elimina sombras si las hay por bootstrap */
          }
          .s_general{
            border:1px solid #ccc; padding:4px;
          }

          .mi_style_n1 { background-color: #8cebdf; }

          /* Opcional: pequeños ajustes visuales */
          #tabla-dosificacion th, #tabla-dosificacion td { vertical-align: middle; }
          #tabla-dosificacion thead th { background: #f8f9fa; }
          #tabla-dosificacion tr:first-child th { background: #e9ecef; font-size: 1rem; }

            /* Ajuste de ancho específico para inputs de la tabla */
          #tabla-dosificacion .input-sm {
            /* max-width: 70px;       ancho máximo */
            /* padding: 2px 4px;      menos espacio interno */
            font-size: 0.8rem;    /* texto más pequeño */
            /*margin: auto;        centra horizontalmente */
            text-align: center;   /* centra el texto dentro del input */
          }
          
         </style>
      </head>
      <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed pace-orange" idproyecto="<?php echo $_SESSION['idproyecto']; ?>">
        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper">
          <?php
          require 'nav.php';
          require 'aside.php';
          if ($_SESSION['valorizacion_concreto']==1){
            //require 'enmantenimiento.php';
            ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper" >
              <!-- Content Header (Page header) -->
              <section class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1 >
                        <span class="h1-titulo">Concreto</span>                         
                      </h1> 
                      
                    </div>
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="valorizacion.php">Home</a></li>
                        <li class="breadcrumb-item active">Concreto</li>
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
                        <div class="card-header" >   
                          
                           <h3 class="card-title mr-3" >
                            <button type="button" class="btn bg-gradient-warning btn-sm h-50px" onclick="dosificacion_concreto();" ><i class="fa-regular fa-rectangle-list"></i> <span class="d-none d-sm-inline-block">Dosificación Concreto</span> </button>
                          </h3>
                          <!-- vertodos -->
                          <h3 class="card-title mr-3" >
                            <button type="button" class="btn bg-gradient-warning btn-sm h-50px" onclick="delay(function(){show_add_nivel1()}, 100 );" data-toggle="tooltip" data-original-title="Asignar nivel" ><i class="fa-regular fa-rectangle-list"></i> <span class="d-none d-sm-inline-block">Asignar Nivel 1</span> </button>
                          </h3>

                          <!-- listar quincenas -->
                          <div id="lista_quincenas" class="row-horizon disenio-scroll" >
                            <i class="fas fa-spinner fa-pulse fa-2x"></i>
                          </div>  

                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">                          
                          <div class="row">   
                            <div class="col-12 div-todos-los-docs">
                              <div class="row">
                                <div class="col-8 mb-4 datos-de-saldo" style="display:none; padding-left: 10px !important; border-radius: 5px; box-shadow: 0 0 2px rgb(0 0 0), 0 1px 5px 4px #494b4c6b;">
                                  <form id="form-asignar_nivel1" name="form-asignar_nivel1" method="POST" >
                                    <div class="row p-3">
                                        <input type="hidden" id="idcontrol_concreto" name="idcontrol_concreto">
                                        <input type="hidden" id="idproyectocontrol_concreto" name="idproyectocontrol_concreto">

                                        <div class="col-12 col-md-3 col-lg-3">
                                          <div class="form-group">
                                            <label for="fecha_concreto">Fecha</label>
                                            <input type="date" class="form-control" id="fecha_concreto" name="fecha_concreto" value="<?php echo date('Y-m-d'); ?>" />
                                          </div>
                                        </div>
                                        <div class="col-12 col-md-3 col-lg-3">
                                          <div class="form-group">
                                            <label for="r_cemento_usado">CEMENTO USADO (m³)</label>
                                            <input type="text" class="form-control" id="r_cemento_usado" name="r_cemento_usado"/> 
                                          </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-lg-6">
                                          <div class="form-group">
                                            <label for="descripcion_concreto">Descripción</label>
                                            <input type="text" class="form-control" id="descripcion_concreto" name="descripcion_concreto"/> 
                                          </div>
                                        </div>

                                        <!-- REALIDAD-->
                                        <div class="col-12 pl-0">
                                          <div class="text-primary"><label for="">REALIDAD</label></div>
                                        </div>
                                        <div class="card col-12 px-3 py-3" style="box-shadow: 0 0 1px rgb(0 0 0), 0 1px 3px rgb(0 0 0 / 60%); ">
                                          <div class="row">

                                            <div class="col-lg-3 class_pading">
                                              <div class="form-group">
                                                <label for="cuadrilla">CUADRILLA </label>
                                                <input type="text" name="cuadrilla" class="form-control" id="cuadrilla"/>
                                              </div>
                                            </div>
                                            <div class="col-lg-3 class_pading">
                                              <div class="form-group">
                                                <label for="hora_inicio">HORA DE INICIO</label>
                                                <input type="time" name="hora_inicio" class="form-control" id="hora_inicio" />
                                              </div>
                                            </div>
                                            <div class="col-lg-3 class_pading">
                                              <div class="form-group">
                                                <label for="hora_termino">HORA DE TERMINO</label>
                                                <input type="time" name="hora_termino" class="form-control" id="hora_termino"/>
                                              </div>
                                            </div>
                                            <div class="col-lg-3 class_pading">
                                              <div class="form-group">
                                                <label for="duracion_vaciado">DURACIÓN DEL VACIADO</label>
                                                <input type="text" name="duracion_vaciado" class="form-control" id="duracion_vaciado"   readonly/>
                                              </div>
                                            </div>

                                          </div>
                                        </div>

                                        <button type="submit" style="display: none;" id="submit-asignar_nivel1">Submit</button>
                                  </form>   
                                  <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="delay(function(){hide_add_nivel1()}, 100 );">Close</button>
                                    <button type="button" class="btn btn-success" id="guardar_registro_nivel1">Guardar Cambios</button>
                                  </div>                              
                                </div>
                              </div>

                            </div>

                            <div class="col-12 div-docs-por-valorizacion" style="display: none;">

                              <div class="" id="div-doc-val">

                                  <div style="overflow-x: auto; width: 100%;">
                                    <table id="control-concreto_semana" style="border-collapse:collapse; width:100%; font-size:12px;">
                                      <thead>
                                        <tr>
                                          <th class="text-center" colspan="30" style="border:1px solid #ccc; background:#f2f2f2;"><span>CONTROL DE CONCRETO DE OBRA</span> <span class="nombre_general text-red"></span></th>
                                        </tr>
                                        <tr>
                                          <th class="text-center" colspan="8" style="border:1px solid #ccc; background:#f2f2f2;">DATOS DE ELEMENTOS</th>
                                          <th class="text-center" colspan="5" style="border:1px solid #ccc; background:#f2f2f2;">DOSIFICACIÓN</th>
                                          <th class="text-center" colspan="4"  style="border:1px solid #ccc; background:#f2f2f2;">EXPECTATIVA</th>
                                          <th class="text-center" colspan="10"  style="border:1px solid #ccc; background:#f2f2f2;">REALIDAD</th>
                                          <th class="text-center" colspan="3"  style="border:1px solid #ccc; background:#f2f2f2;">ANÁLISIS</th>
                                        </tr>
                                        <tr>
                                          <!-- DATOS REALES DE MUESTRA (13) -->
                                          <th class="text-center s_general"><i class="fas fa-cogs"></i></th>
                                          <th class="text-center s_general">FECHA</th>
                                          <th class="text-center s_general">DESCRIPCIÓN</th>
                                          <th class="text-center s_general">CANTIDAD</th>
                                          <th class="text-center s_general">LARGO</th>
                                          <th class="text-center s_general">ANCHO</th>
                                          <th class="text-center s_general">ALTO</th>                                    
                                          <th class="text-center s_general">CALIDAD F’c (kg/cm²)</th>
                                          <th class="text-center s_general">DOsificación</th>
                                          <th class="text-center s_general">BOLSAS / m³</th>
                                          <th class="text-center s_general">PIEDRA / m³</th>
                                          <th class="text-center s_general">ARENA / m³</th>
                                          <th class="text-center s_general">HORMIGÓN / m³</th>

                                          <!-- EXPECTATIVA (6) -->
                                          <th class="text-center s_general" colspan="2">CONCRETO PROYECTADO (m³)</th>
                                          <th class="text-center s_general" colspan="2">CEMENTO PROYECTADO (bls)</th>
                                          <th class="text-center s_general">CONCRETO USADO (m³)</th>
                                          <th class="text-center s_general">CEMENTO USADO (bls)</th>
                                          <th class="text-center s_general">PIEDRA CHANCADA (m³)</th>
                                          <th class="text-center s_general">ARENA (m³)</th>
                                          <th class="text-center s_general">HORMIGÓN (m³)</th>

                                          <!-- REALIDAD (6) -->
                                          <th class="text-center s_general">PIEDRA GRANDE (m³)</th>
                                          
                                          <th class="text-center s_general">Cuadrilla</th>
                                          <th class="text-center s_general">HORA DE INICIO</th>
                                          <th class="text-center s_general">HORA DE TERMINO</th>
                                          <th class="text-center s_general">DURACIÓN DE VACIADO</th>

                                          <!-- ANÁLISIS (3) -->
                                          <th class="text-center s_general">DESPERDICIO CONCRETO (m³)</th>
                                          <th class="text-center s_general">DESPERDICIO CEMENTO (bls)</th>                                    
                                          <th class="text-center s_general">PORCENTAJE DE DESPERDICIO (%)</th>
                                        </tr>
                                      </thead>
                                      <tbody id="tabla_concreto_control_semana">
                                        <tr>
                                          <td colspan="30" class="text-center" style="border:1px solid #ccc; background:#f9f9f9;">
                                            <i class="fas fa-spinner fa-pulse fa-2x"></i> <h6>Cargando...</h6> 
                                          </td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>

                              </div>
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

                <!-- Modal agregar valorizacion -->
                <div class="modal fade" id="modal-dosificacion-concreto">
                  <div class="modal-dialog modal-dialog-scrollable modal-xl">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title" id="title-modal-1">DOSIFICACIÓN DE CONCRETO</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <a type="submit" class="btn btn-success m-1 " onclick="add_fila_dosificacion_concreto();"><i class="fas fa-plus-circle"></i> Agregar Fila </a>

                        <!-- form start -->
                        <form id="form-dosificacion-concreto" name="form-dosificacion-concreto" method="POST" >
                          <div class="row" id="cargando-1-fomulario">
                              
                            <div class="table-responsive">
                              <table class="table table-bordered table-sm align-middle text-nowrap" id="tabla-dosificacion">
                                <thead>
                                  <!-- Título -->
                                  <tr>
                                    <th colspan="10" class="text-center fw-bold">DOSIFICACIÓN DE CONCRETO</th>
                                  </tr>
                                  <!-- Cabeceras principales -->
                                  <tr class="text-center">
                                    <th rowspan="2" style="width:4%"><i class="fas fa-cogs"></i></th>
                                    <th colspan="3">RESISTENCIA</th>
                                    <th rowspan="2">CEMENTO (bls)</th>
                                    <th rowspan="2">ARENA (m³)</th>
                                    <th rowspan="2">GRAVA (m³)</th>
                                    <th rowspan="2">HORMIGÓN (m³)</th>
                                    <th rowspan="2">
                                      <div class="lh-1">
                                        CANTIDADES<br>
                                        <small>cmt - ar - gr</small>
                                      </div>
                                    </th>
                                  </tr>
                                  <!-- Subcabeceras de RESISTENCIA -->
                                  <tr class="text-center">
                                    <th>kg/cm²</th>
                                    <th>PSI</th>
                                    <th>Mpa</th>
                                  </tr>
                                </thead>

                                <tbody class="tbody_tabla_dosificacion_concreto">
                                </tbody>
                              </table>
                            </div>

                          </div>

                          <div class="row" id="cargando-2-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                              <br />
                              <h4>Cargando...</h4>
                            </div>
                          </div>
                          <!-- /.card-body -->
                          <button type="submit" style="display: none;" id="submit-dosificacion-concreto">Submit</button>
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

                <!-- MODAL - sub nivel -->
                <div class="modal fade" id="modal-agregar-sub_nivel">
                  <div class="modal-dialog modal-dialog-scrollable modal-xl">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title"><b>Agregar:</b> comprobante de hospedaje</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form id="form-sub_nivel" name="form-sub_nivel" method="POST">
                          <div class="card-body">
                            <div class="row" id="cargando-1-fomulario">
                              <!-- id proyecto -->
                              <input type="hidden" name="idcontrol_concreto_padre_sn" id="idcontrol_concreto_padre_sn" />
                              <input type="hidden" name="idcontrol_concreto_sn" id="idcontrol_concreto_sn" />
                              <input type="hidden" name="idproyecto_sn" id="idproyecto_sn" />
                              <input type="hidden" name="prefijo_sn" id="prefijo_sn" />
                              <input type="hidden" name="codigo_padre_sn" id="codigo_padre_sn" />
                              <input type="hidden" name="codigo_hijo_sn" id="codigo_hijo_sn" />

                              <!-- DATOS REALES DE MUESTRA-->
                              <div class="col-12 pl-0">
                                <div class="text-primary"><label for="">DATOS REALES DE MUESTRA Y ESPECTATIVA</label></div>
                              </div>
                              <div class="card col-12 px-3 py-3" style="box-shadow: 0 0 1px rgb(0 0 0), 0 1px 3px rgb(0 0 0 / 60%); ">
                                <div class="row">
                                  <!-- Fecha 1 onchange="calc_cantidad(); restrigir_fecha_input();" onkeyup="calc_cantidad(); calc_total();"-->
                                  <div class="col-lg-2 class_pading">
                                    <div class="form-group">
                                      <label for="fecha_sn">Fecha <sup class="text-danger">*</sup></label>
                                      <input type="date" name="fecha_sn" class="form-control" id="fecha_sn"  readonly />
                                    </div>
                                  </div>
                                  <!--Descripcion-->
                                  <div class="col-lg-4 class_pading">
                                    <div class="form-group">
                                      <label for="descripcion_sn">Descripción </label>
                                      <textarea name="descripcion_sn" id="descripcion_sn" class="form-control" rows="1"></textarea>
                                    </div>
                                  </div>
                                  <!-- Cantidad CANTIDAD	LARGO	ANCHO	ALTO	ALTURA DEL VACIADO	CALIDAD F’c (kg/cm²)	BOLSAS / m³	PIEDRA / m³	ARENA / m³	HORMIGÓN / m³ -->
                                  <div class="col-lg-2 class_pading">
                                    <div class="form-group">
                                      <label for="cantidad_sn">Cantidad</label>
                                      <input type="number" name="cantidad_sn" class="form-control" id="cantidad_sn" min="0.01" />
                                    </div>
                                  </div>
                                  <div class="col-lg-2 class_pading">
                                    <div class="form-group">
                                      <label for="largo_sn">Largo</label>
                                      <input type="number" name="largo_sn" class="form-control" id="largo_sn" min="0.01" />
                                    </div>
                                  </div>
                                  <div class="col-lg-2 class_pading">
                                    <div class="form-group">
                                      <label for="ancho_sn">Ancho</label>
                                      <input type="number" name="ancho_sn" class="form-control" id="ancho_sn" min="0.01" />
                                    </div>
                                  </div>
                                  <div class="col-lg-2 class_pading">
                                    <div class="form-group">
                                      <label for="alto_sn">Alto</label>
                                      <input type="number" name="alto_sn" class="form-control" id="alto_sn" min="0.01" />
                                    </div>
                                  </div>
                                  <div class="col-lg-2 class_pading hidden">
                                    <div class="form-group">
                                      <label for="altura_vaciado_sn">Altura del Vaciado</label>
                                      <input type="number" name="altura_vaciado_sn" class="form-control" id="altura_vaciado_sn" min="0.01" />
                                    </div>
                                  </div>
                                  <!--Calidad F’c (kg/cm²) <sup class="text-danger">*</sup>-->
                                  <div class="col-lg-2 class_pading">
                                    <div class="form-group">
                                      <label for="calidad_fc_kg_cm2_sn">Calidad F’c (kg/cm²) </label>
                                      <input type="number" name="calidad_fc_kg_cm2_sn" class="form-control" min="0.01" id="calidad_fc_kg_cm2_sn"/>
                                    </div>
                                  </div>
                                  <div class="col-lg-2 class_pading">
                                    <div class="form-group">
                                      <label for="bolsas_m3_sn">Bolsas/m3</label>
                                      <input type="number" name="bolsas_m3_sn" class="form-control" id="bolsas_m3_sn" readonly />
                                    </div>
                                  </div>
                                  <div class="col-lg-2 class_pading">
                                    <div class="form-group">
                                      <label for="piedra_m3_sn">Piedra/m3</label>
                                      <input type="number" name="piedra_m3_sn" class="form-control" id="piedra_m3_sn"   readonly/>
                                    </div>
                                  </div>
                                  <div class="col-lg-2 class_pading">
                                    <div class="form-group">
                                      <label for="arena_m3_sn">Arena/m3</label>
                                      <input type="number" name="arena_m3_sn" class="form-control" id="arena_m3_sn" readonly/>
                                    </div>
                                  </div>
                                  <div class="col-lg-2 class_pading">
                                    <div class="form-group">
                                      <label for="hormigon_m3_sn">Hormigón/m3</label>
                                      <input type="number" name="hormigon_m3_sn" class="form-control" id="hormigon_m3_sn" readonly />
                                    </div>
                                  </div>
                                  <div class="col-lg-2 class_pading">
                                    <div class="form-group">
                                      <label for="dosificacion_sn">Dosificación</label>
                                      <input type="text" name="dosificacion_sn" class="form-control" id="dosificacion_sn" readonly />
                                    </div>
                                  </div>
                                  <div class="col-lg-4 class_pading">
                                    <div class="form-group">
                                      <label for="concreto_proyectado_m3_sn">Concreto Proyectado/m3</label>
                                      <input type="number" name="concreto_proyectado_m3_sn" class="form-control" id="concreto_proyectado_m3_sn" readonly />
                                    </div>
                                  </div>

                                  <div class="col-lg-4 class_pading">
                                    <div class="form-group">
                                      <label for="cemento_proyectado_m3_sn">Cemento Proyectado/m3</label>
                                      <input type="number" name="cemento_proyectado_m3_sn" class="form-control" id="cemento_proyectado_m3_sn" readonly />
                                    </div>
                                  </div>

                                </div>
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
                                <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                                <br />
                                <h4>Cargando...</h4>
                              </div>
                            </div>
                          </div>
                          <!-- /.card-body -->
                          <button type="submit" style="display: none;" id="submit-form-sub_nivel">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_form_sub_nivel();">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro_sub_nivel">Guardar Cambios</button>
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

        <!-- table export EXCEL -->
        <script src="../plugins/export-xlsx/xlsx.full.min.js"></script>
        <script src="../plugins/export-xlsx/FileSaver.min.js"></script>
        <script src="../plugins/export-xlsx/tableexport.min.js"></script>
        
        <!-- EXCEL PREVIEW -->
        <script src="../plugins/bootstrap-table/dist/bootstrap-table.min.js" type="text/javascript"></script>
	      <script src="../plugins/bootstrap-table/dist/locale/bootstrap-table-es-MX.min.js" type="text/javascript"></script>
        <script src="../plugins/excel-preview/js/src/util.js" type="text/javascript" ></script>
	      <script src="../plugins/excel-preview/js/src/excel-preview.js" type="text/javascript" ></script>

        <script type="text/javascript" src="scripts/concreto_control.js?version_jdl=1.9"></script>

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip();  }); </script>

      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
