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

        <title>Formato | Admin Sevens</title>

        <?php $title = "Formato"; require 'head.php'; ?>
        <style>
          .class_text{ font-weight: 200; font-size: unset; }
        </style>
      </head>
      <body class="hold-transition sidebar-collapse sidebar-mini layout-fixed layout-navbar-fixed pace-orange" idproyecto="<?php echo $_SESSION['idproyecto']; ?>">
        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper">
          <?php  
          require 'nav.php'; 
          require 'aside.php'; 

          if ($_SESSION['asistencia_obrero']==1){  
            //require 'enmantenimiento.php';
            ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
              <!-- Content Header (Page header) -->
              <section class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1>formatos varios </h1>
                    </div>
                    <div class="col-sm-6"> 
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">formatos varios</li>
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
                        <!-- /.card-header -->
                        <div class="card-body">
                          <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                            <!-- Ats -->
                            <li class="nav-item">
                              <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">ATS</a>
                            </li>
                            <!-- Temperatura -->
                            <li class="nav-item">
                              <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">TEMPERATURA</a>
                            </li>
                            <!-- Check list -->
                            <li class="nav-item">
                              <a class="nav-link" id="custom-content-below-messages-tab" data-toggle="pill" href="#custom-content-below-messages" role="tab" aria-controls="custom-content-below-messages" aria-selected="false">CHECK LIST EPPS</a>
                            </li>   
                            <!--ASISTENCIA TRABAJADOR-->
                            <li class="nav-item">
                              <a class="nav-link" id="custom-content-below-asistencia-tab" data-toggle="pill" href="#custom-content-below-asistencia" role="tab" aria-controls="custom-content-below-asistencia" aria-selected="false">ASISTENCIA TRABAJADOR</a>
                            </li> 
                            <!--CONTROL_EPP_TRABAJADOR-->
                            <li class="nav-item">
                              <a class="nav-link" id="custom-content-below-controlEPP-tab" data-toggle="pill" href="#custom-content-below-controlEPP" role="tab" aria-controls="custom-content-below-controlEPP" aria-selected="false">CONTROL E.P.P TRABAJADOR</a>
                            </li>                       
                          </ul>
                          <div class="tab-content" id="custom-content-below-tabContent">

                            <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab"> 
                              <a type="button" class="btn bg-gradient-success btn-sm my-3" id="btn-export-ats" href=""  >
                                <i class="fa-regular fa-file-excel"></i> export
                              </a>                              
                              <!-- table con el formato de ATS diseño table -->
                              <div class="table-responsive pb-3">                                

                                <table id="formato_ats" class="table table-bordered table-striped" style="width: 100% !important;">
                                  <thead>
                                    <tr>
                                      <th class="p-y-2px"  rowspan="3"></th> 
                                      <th class="p-y-2px text-center"  rowspan="3"><img src="../dist/img/logo-principal.png" alt="logo Admin Sevens" width="100px" ></th> 
                                      <th class="p-y-2px text-center" colspan="5">Formato</th> 
                                      <th class="p-y-2px">CÓDIGO:</th> 
                                      <th class="p-y-2px">---</th>
                                    </tr>
                                    <tr>
                                      <th class="p-y-2px text-center" rowspan="2" colspan="5"  >ANÁLISIS SEGURO DE TRABAJO (ATS)</th>  
                                      <th class="p-y-2px">VERSION:</th> 
                                      <th class="p-y-2px">---</th>
                                    </tr>
                                    <tr>
                                      <th class="p-y-2px">FECHA:</th> 
                                      <th class="p-y-2px">---</th>
                                    </tr>
                                    <tr>
                                      <th class="p-y-2px"></th>
                                      <th class="p-y-2px bg-gray-light">Tarea a realizar:</th>
                                      <th class="p-y-2px" colspan="7"> TRABAJOS DE CONTRUCCÍON</th>
                                    </tr>
                                    <tr> 
                                      <th class="p-y-2px"></th>
                                      <th class="p-y-2px"> RAZÓN SOCIAL: </th>
                                      <th class="p-y-2px"> SEVEN´S INGENIEROS SELVA S.A.C.</th>
                                      <th class="p-y-2px"> RUC: </th>
                                      <th class="p-y-2px">20609935651 </th>
                                      <th class="p-y-2px" >Lugar: </th>
                                      <th class="p-y-2px"><textarea cols="30" rows="1"  id='ubicacion' class="textarea_datatable" readonly ></textarea> </th>
                                      <th class="p-y-2px"> N° DE REGISTRO: </th>
                                      <th class="p-y-2px"> --- </th> 
                                    </tr>
                                    <tr>
                                      <th class="p-y-10px" colspan="9"></th>
                                    </tr>
                                    <tr>
                                      <th colspan="9" class="text-center p-y-2px">EQUIPO DE ATS</th>
                                    </tr>
                                    <tr colspan="5"> 
                                      <th class="p-y-2px " > N°</th>
                                      <th class="p-y-2px" colspan="2">Apellidos y Nombres</th>
                                      <th class="p-y-2px" colspan="2">Firma</th>
                                      <th class="p-y-2px">Nº</th> 
                                      <th class="p-y-2px" colspan="2">Apellidos y Nombres</th>
                                      <th class="p-y-2px" colspan="2">Firma</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <!-- aqui va el detalle del la tabla -->
                                  </tbody>
                                </table>
                              </div>  
                              <!-- /.table-responsive -->
                            </div>
                            <!-- /.tab-panel -->

                            <div class="tab-pane fade" id="custom-content-below-profile" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                              <!-- <h1>----------------------------------</h1> -->
                              <!-- tabla REGISTRO - SATURACIÓN DE OXÍGENO Y TEMPERATURA -->
                              <a type="button" class="btn bg-gradient-success btn-sm my-3" id="btn-export-temperatura"> <i class="fa-regular fa-file-excel"></i> export </a>                              
                              <div class="table-responsive pb-3">
                                <table id="formato_temperatura"  class="table table-bordered table-striped">
                                  <thead>
                                    <tr class="font-size-14px text-center">                                      
                                      <th class="p-y-3px celda-b-y-0px" rowspan="3"><img src="../dist/img/logo-principal.png" alt="logo Admin Sevens" width="100px" ></th>
                                      <th class="p-y-3px" colspan="8" > --- </th>
                                      <th class="p-y-3px font-size-13px" >REVISIÓN</th>
                                    </tr>                                    
                                    <tr class="text-center">                                                                      
                                      <th class="p-y-3px font-size-14px" colspan="8" rowspan="2" >CONSTRUCCIÓN DE LA SEDE MISIÓN NOR ORIENTAL - II ETAPA</th>
                                      <th class="p-y-3px font-size-13px" > --- </th>
                                    </tr>
                                    <tr class="font-size-14px text-center">                                                                             
                                      <th class="p-y-3px celda-b-y-0px font-size-13px"> --- </th>
                                    </tr>
                                    <tr class="font-size-14px">                                      
                                      <th class="p-y-3px" colspan="1">PROYECTO</th>
                                      <th class="p-y-3px" colspan="9" id='proyec_temperatura'> --- </th>
                                    </tr>
                                    <tr class="font-size-14px">                                       
                                      <th class="p-y-3px" colspan="1">UBICACIÓN</th>
                                      <th class="p-y-3px" colspan="9" id='ubic_temperatura'> --- </th>
                                    </tr>
                                    <tr class="font-size-14px" colspan="10">                                      
                                      <th class="p-y-3px" colspan="1">EMPRESA</th>
                                      <th class="p-y-3px" colspan="4">SEVEN´S INGENIEROS SELVA S.A.C.</th>
                                      <th class="p-y-3px" colspan="1">RUC</th>
                                      <th class="p-y-3px" colspan="4">20609935651</th>
                                    </tr>
                                    <tr class="font-size-15px" colspan="10">
                                      <th class="p-y-4px" colspan="2"></th>
                                      <th class="p-y-3px text-center" colspan="4">INGRESO</th>
                                      <th class="p-y-3px text-center" colspan="4">SALIDA</th>
                                    </tr>
                                    <tr class="font-size-13px text-center">
                                      <th class="p-y-4px">N°</th>
                                      <th class="p-y-3px" >Nombre y Apellido</th>
                                      <th class="p-y-3px">Firma</th>
                                      <th class="p-y-3px">Hora del registro</th>
                                      <th class="p-y-3px">T (°C) <br> (34°-37.5°)</th>
                                      <th class="p-y-3px">%S.O. <br> (87-100)</th>
                                      <th class="p-y-3px">Firma</th>
                                      <th class="p-y-3px">Hora del registro</th>
                                      <th class="p-y-3px">T (°C) <br> (34°-37.5°)</th>
                                      <th class="p-y-3px">%S.O. <br> (87-100)</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <!-- aqui va el detalle del la tabla -->
                                  </tbody>
                                </table>
                              </div> 
                              <!-- /.table-responsive -->
                            </div>
                            <!-- /.tab-panel -->

                            <div class="tab-pane fade " id="custom-content-below-messages" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">
                              <!-- tabla REGISTRO - SATURACIÓN DE OXÍGENO Y TEMPERATURA -->
                              <a type="button" class="btn bg-gradient-success btn-sm my-3" id="btn-export-check-list-epps"> <i class="fa-regular fa-file-excel"></i> export </a>
                              <div class="table-responsive pb-3">
                                <table id="formato_check_list_epps"  class="table table-bordered table-striped">
                                  <thead class="text-center">
                                    <tr>
                                      <th class="p-y-3px celda-b-y-0px" rowspan="3" colspan="2"><img src="../dist/img/logo-principal.png" alt="logo Admin Sevens" width="100px" ></th>
                                      <th class="p-y-3px" colspan="24">CONTROL DIARIO DE EQUIPOS DE PROTECCIÓN PERSONAL</th>
                                    </tr>
                                    <tr>
                                      <th class="p-y-3px" colspan="24" id='proyec_check_list'> --- </th>
                                    </tr>
                                    <tr>
                                      <th class="p-y-3px" colspan="24"> --- </th>
                                    </tr>
                                    <tr>
                                      <th class="p-y-3px bg-color-acc3c7" colspan="26">DATOS DEL EMPLEADOR PRINCIPAL:</th>
                                    </tr>
                                    <tr>
                                      <th class="p-y-3px font-size-13px" colspan="2">RAZÓN SOCIAL O DENOMINACIÓN SOCIAL</th>
                                      <th class="p-y-3px font-size-13px" colspan="4">RUC</th>
                                      <th class="p-y-3px font-size-13px" colspan="6">DOMICILIO <br> (Dirección, distrito, departamento, provincia)</th>
                                      <th class="p-y-3px font-size-13px" colspan="6">TIPO DE ACTIVIDAD <br> ECONÓMICA</th>
                                      <th class="p-y-3px font-size-13px" colspan="4">N° TRABAJADORES  <br> EN EL TRABAJO</th>
                                      <th class="p-y-3px font-size-13px celda-b-x-0px" colspan="4" rowspan="2"></th>
                                    </tr>
                                    <tr>
                                      <th class="p-y-3px font-size-12px font-weight-normal" colspan="2">SEVEN´S INGENIEROS SELVA   S.A.C. </th>
                                      <th class="p-y-3px font-size-12px font-weight-normal" colspan="4">20609935651</th>
                                      <th class="p-y-3px font-size-12px font-weight-normal" colspan="6"> --- </th>
                                      <th class="p-y-3px font-size-12px font-weight-normal" colspan="6">ARQUITECTURA E INGENIERIA</th>
                                      <th class="p-y-3px font-size-12px font-weight-normal" colspan="4"> --- </th>
                                      <th class="p-y-3px font-size-12px celda-b-y-0px celda-b-x-0px" colspan="4" > </th>
                                    </tr>
                                    <tr class="font-size-12px">
                                      <th class="p-y-3px"></th>
                                      <th class="p-y-3px" >VERIFICACIÓN DE EQUIPOS DE SEGURIDAD</th>
                                      <th class="p-y-3px" colspan="2">CASCO</th>
                                      <th class="p-y-3px" colspan="2">CORTA VIENTO</th>
                                      <th class="p-y-3px" colspan="2">LENTES DE SEGURIDAD</th>
                                      <th class="p-y-3px" colspan="2">GUANTES DE NITRILO</th>
                                      <th class="p-y-3px" colspan="2">GUANTES DE JEBE</th>
                                      <th class="p-y-3px" colspan="2">GUANTES DE CUERO</th>
                                      <th class="p-y-3px" colspan="2">ZAPATOS DE SEGURIDAD</th>
                                      <th class="p-y-3px" colspan="2">PROTECTORES AUDITIVOS</th>
                                      <th class="p-y-3px" colspan="2">ARNES</th>
                                      <th class="p-y-3px" colspan="2">CARETA</th>
                                      <th class="p-y-3px" colspan="2">BOTAS DE JEBE</th>
                                      <th class="p-y-3px" colspan="2">CAPOTIN</th>
                                    </tr>
                                    <tr class="font-size-13px">

                                      <th class="p-y-4px">N°</th>
                                      <th class="p-y-8px">APELLIDOS Y NOMBRES</th>
                                      <th class="p-y-3px" >SI</th>
                                      <th class="p-y-3px" >NO</th>
                                      <th class="p-y-3px" >SI</th>
                                      <th class="p-y-3px" >NO</th>

                                      <th class="p-y-3px" >SI</th>
                                      <th class="p-y-3px" >NO</th>
                                      <th class="p-y-3px" >SI</th>
                                      <th class="p-y-3px" >NO</th>
                                      <th class="p-y-3px" >SI</th>
                                      <th class="p-y-3px" >NO</th>
                                      <th class="p-y-3px" >SI</th>
                                      <th class="p-y-3px" >NO</th>
                                      <th class="p-y-3px" >SI</th>
                                      <th class="p-y-3px" >NO</th>

                                      <th class="p-y-3px" >SI</th>
                                      <th class="p-y-3px" >NO</th>
                                      <th class="p-y-3px" >SI</th>
                                      <th class="p-y-3px" >NO</th>
                                      <th class="p-y-3px" >SI</th>
                                      <th class="p-y-3px" >NO</th>
                                      <th class="p-y-3px" >SI</th>
                                      <th class="p-y-3px" >NO</th>
                                      <th class="p-y-3px" >SI</th>
                                      <th class="p-y-3px" >NO</th>
                                    </tr>

                                  </thead>
                                  <tbody>                                                                    
                                    <!-- aqui va el detalle del la tabla -->
                                  </tbody>
                                </table>
                              </div>
                              <!-- /.table-responsive -->                                
                            </div>
                            <!-- /.tab-panel -->    
                            <!--Asistencia trabajador-->
                            <div class="tab-pane fade " id="custom-content-below-asistencia" role="tabpanel" aria-labelledby="custom-content-below-asistencia-tab">
                              <!-- tabla ASISTENCIA - TRABAJADOR -->
                              <a type="button" class="btn bg-gradient-success btn-sm my-3" id="btn-export-asistencia_trabajador" target="_blank"> <i class="fa-regular fa-file-excel"></i> export </a>
                              <div class="table-responsive pb-3">
                                <!--TABLA EXPORT ASISTENCIA TRABAJADOR-->
                                <table class="table table-hover table-head-fixed text-nowrap tabla_sistencia_obrero" style="border: black 1px solid;">
                                  <thead class="bg-white" >
                                    <tr class="celda-b-t-1px ">
                                      <th rowspan="3" class="stile-celda inicio_tabla">N°</th>
                                      <th rowspan="3" class="stile-celda ">Nombre</th>
                                      <th rowspan="3" class="stile-celda ">DNI</th>
                                      <th rowspan="3" class="stile-celda ">FECHA INGRESO</th>
                                      <th rowspan="3" class="stile-celda ">OCUPACIÓN</th>
                                      <th colspan="7" class="text-center py-1" style="border: black 1px solid;">                                    
                                        Horas de trabajo por día                                        
                                      </th>
                                      <th rowspan="3" class="stile-celda table_title_hne">Descripción</th>                                      
                                    </tr>
                                    
                                    <tr class="table-dias data-dia-semana">   
                                                                    
                                      <th class="py-1"> Do </th>
                                      <th class="py-1"> Lu </th>
                                      <th class="py-1"> Ma </th>
                                      <th class="py-1"> Mi </th>
                                      <th class="py-1"> Ju </th>
                                      <th class="py-1"> Vi </th>
                                      <th class="py-1 bg-color-acc3c7"> Sa </th>                                  
                                    </tr>
                                    <tr class="table-dias data-numero-semana">                                      
                                      <th class="py-1">01</th> <th class="py-1">02</th> <th class="py-1">03</th> <th class="py-1">04</th> <th class="py-1">05</th> <th class="py-1">06</th> <th class="py-1">07</th> 
                                    </tr>
                                  </thead>
                                  <tbody class="tcuerpo data_table_body" >
                                    
                                    
                                  </tbody>
                                </table>
                              </div>
                              <!-- /.table-responsive -->                                
                            </div>
                            <!-- /.tab-panel -->
                            
                            <!--control EPP Trabajador-->
                            <div class="tab-pane fade " id="custom-content-below-controlEPP" role="tabpanel" aria-labelledby="custom-content-below-controlEPP-tab">
                              <!-- tabla controlEPP - TRABAJADOR -->
                              <a type="button" class="btn bg-gradient-success btn-sm my-3" id="btn-export-controlEPP_trabajador"> <i class="fa-regular fa-file-excel"></i> export </a>
                              <div class="table-responsive pb-3">
                                  <!--TABLA EXPORT controlEPP TRABAJADOR-->
                                  <div class="content">
                                    <table class="table table-bordered table-striped" style="width: 100% !important;">
                                    <thead>
                                      <tr>
                                        <th class="p-y-2px text-center" style="width: 10px;" colspan="3" rowspan="3"><img src="../dist/img/logo-principal.png" alt="logo Admin Sevens" width="80px" ></th>
                                        <th class="p-y-2px text-center" colspan="17">REGISTRO DE EQUIPOS DE SEGURIDAD O EMERGENCIA</th> 
                                        <th class="p-y-2px text-center" colspan="2">REVISIÓN</th> 
                                        <!-- <th class="p-y-2px">---</th> -->
                                      </tr>
                                      <tr>
                                        <th class="p-y-2px text-center" rowspan="2" colspan="17"  >SEVEN´S INGENIEROS S.A.C.</th>  
                                        <th class="p-y-2px" >FECHA</th> 
                                        <th class="p-y-2px">---</th>
                                      </tr>
                                      <tr>
                                        <th class="p-y-2px">N° REGISTRO</th> 
                                        <th class="p-y-2px">---</th>
                                      </tr>
                                      <tr> 
                                        <th class="p-y-2px text-center" colspan="22" style="background-color: #7c8585;"> DATOS DEL EMPLEADOR PRINCIPAL:</th>
                                      </tr>
                                      <tr> 
                                        <th class="p-y-2px text-center" colspan="4"> RAZÓN SOCIAL O DENOMINACIÓN SOCIAL:</th>
                                        <th class="p-y-2px text-center" colspan="2">RUC</th>
                                        <th class="p-y-2px text-center" colspan="6">DOMICILIO</th>
                                        <th class="p-y-2px text-center" colspan="6">ACTIVIDAD ECONÓMICA</th>
                                        <th class="p-y-2px text-center" colspan="6">N° TRABAJADORES</th>
                                      </tr>
                                      <tr> 
                                        <th class="p-y-2px text-center" colspan="4">------------------------------</th>
                                        <th class="p-y-2px text-center" colspan="2">------------------------------</th>
                                        <th class="p-y-2px text-center" colspan="6">------------------------------</th>
                                        <th class="p-y-2px text-center" colspan="6">------------------------------</th>
                                        <th class="p-y-2px text-center" colspan="6">------------------------------</th>
                                      </tr>
                                      <tr>
                                        <th class="p-y-10px" colspan="4">APELLIDOS Y NOMBRES : </th>
                                        <th class="p-y-10px" colspan="18"></th>
                                      </tr>
                                      <tr> 
                                        <th class="p-y-2px text-center" style="width: 30px;" > N°</th>
                                        <th class="p-y-2px text-center" style="width: 30px;">CANT.</th>
                                        <th class="p-y-2px text-center" style="width: 30px;">UND.</th>
                                        <th class="p-y-2px text-center" colspan="12">EQUIPOS DE SEGURIDAD ENTREGADOS</th> 
                                        <th class="p-y-2px text-center" colspan="4">FECHA ENTREGA</th>
                                        <th class="p-y-2px text-center" colspan="8">FIRMA</th>
                                      </tr>
                                      
                                    </thead>
                                    <tbody>
                                      <!-- aqui va el detalle del la tabla -->
                                      <?php
                                      // Aquí puedes agregar el código PHP para generar filas de datos dinámicamente
                                      for ($i = 1; $i <= 5; $i++) {
                                          echo '<tr>';
                                          echo '<td>' . $i . '</td>';
                                          echo '<td></td>';
                                          echo '<td></td>';
                                          echo '<td colspan="12"></td>';
                                          echo '<td colspan="4"></td>';
                                          echo '<td colspan="8"></td>';
                                          // Agrega más celdas de datos aquí según sea necesario
                                          echo '</tr>';
                                      }
                                      ?>
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                              <!-- /.table-responsive -->                                
                            </div>
                            <!-- /.tab-panel -->      

                          </div>
                        </div>
                          <!-- /.card-body -->
                        <!-- /.card -->
                      </div>
                      <!-- /.col -->
                    </div>
                    <!-- /.row -->
                  </div>
                  <!-- /.container-fluid -->
                </div>
                

                <!-- MODAL - justificar asistencia -->
                <div class="modal fade" id="modal-justificar-asistencia">
                  <div class="modal-dialog modal-dialog-scrollable modal-md">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Justificación</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form id="form-justificar-asistencia" name="form-justificar-asistencia" method="POST">
                          <div class="row" id="cargando-3-fomulario">
                            
                            <!-- id asistencia -->
                            <input type="hidden" name="idasistencia_trabajador_j" id="idasistencia_trabajador_j" /> 
                            
                            <!-- Descripcion -->
                            <div class="col-md-12 col-lg-12">
                              <div class="form-group">
                                <label for="nombre">Descripción</label>
                                <textarea name="detalle_j" id="detalle_j" class="form-control" rows="5" placeholder="Ingresa descripción"></textarea>
                              </div>
                            </div>

                            <!-- Documento -->
                            <div class="col-md-12 col-lg-12" > 
                              <!-- linea divisoria -->
                              <div class="col-lg-12 borde-arriba-naranja mt-2"> </div>

                              <div class="row text-center">
                                <div class="col-md-12" style="padding-top: 15px; padding-bottom: 5px;">
                                  <label for="cip" class="control-label" > Evidencia </label>
                                </div>
                                <!-- Subir documento -->
                                <div class="col-md-3 text-center">
                                  <button type="button" class="btn btn-success btn-block btn-xs" id="doc1_i">
                                    <i class="fas fa-file-upload"></i> Subir.
                                  </button>
                                  <input type="hidden" id="doc_old_1" name="doc_old_1" />
                                  <input style="display: none;" id="doc1" type="file" name="doc1" accept="application/pdf, image/*" class="docpdf" /> 
                                </div>
                                <!-- Recargar -->
                                <div class="col-md-3 text-center"> 
                                  <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(1, 'asistencia_obrero', 'justificacion');">
                                    <i class="fa fa-eye"></i> PDF.
                                  </button>
                                </div>
                                <!-- Dowload -->
                                <div class="col-md-3 text-center descargar" style="display: none;">
                                  <a type="button" class="btn btn-warning btn-block btn-xs" id="descargar_rh" download="Justificacion"> <i class="fas fa-download"></i> Descargar. </a>
                                </div>
                                <!-- Ver grande -->
                                <div class="col-md-3 text-center ver_completo" style="display: none;">
                                  <a type="button" class="btn btn-info btn-block btn-xs " target="_blank" id="ver_completo"> <i class="fas fa-expand"></i> Ver completo. </a>
                                </div>
                              </div>                              
                              <div id="doc1_ver" class="text-center mt-4">
                                <img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >
                              </div>
                              <div class="text-center" id="doc1_nombre"><!-- aqui va el nombre del pdf --></div>                            
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
                          <button type="submit" style="display: none;" id="submit-form-justificacion">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro_justificacion">Guardar Cambios</button>
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
        <?php  require 'script.php';  ?> 
        
        <!-- table export EXCEL -->
        <script src="../plugins/export-xlsx/xlsx.full.min.js"></script>
        <script src="../plugins/export-xlsx/FileSaver.min.js"></script>
        <script src="../plugins/export-xlsx/tableexport.min.js"></script>
        
        <script type="text/javascript" src="scripts/formato_varios.js?version_jdl=2.02"></script>

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>
        
      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
