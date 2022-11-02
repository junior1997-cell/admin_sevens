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

        <title>Asistencia Obrero | Admin Sevens</title>

        <?php $title = "Asistencia Obrero"; require 'head.php'; ?>
<style>
  .class_text{
    font-weight: 200;
    font-size: unset;
  }
</style>
      </head>
      <body class="hold-transition sidebar-collapse sidebar-mini layout-fixed layout-navbar-fixed">
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
                            <li class="nav-item">
                              <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">ATS</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">TEMPERATURA</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" id="custom-content-below-messages-tab" data-toggle="pill" href="#custom-content-below-messages" role="tab" aria-controls="custom-content-below-messages" aria-selected="false">CHECK LIST EPPS</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" id="custom-content-below-settings-tab" data-toggle="pill" href="#custom-content-below-settings" role="tab" aria-controls="custom-content-below-settings" aria-selected="false">xxxxxxx</a>
                            </li>
                          </ul>
                          <div class="tab-content" id="custom-content-below-tabContent">

                            <div class="tab-pane fade " id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab"> 
                              <button type="button" class="btn bg-gradient-success" onclick="export_excel_detalle_factura();">
                                <i class="fas fa-plus-circle"></i> export
                              </button>
                              <!-- table con el formato de ATS diseño table -->
                              <table id="formato_ats" class="table table-bordered table-striped" style="width: 100% !important;">
                                <thead>
                                  <tr>
                                    <th class="p-y-2px"  rowspan="3"></th> 
                                    <th class="p-y-2px"  rowspan="3">#</th> 
                                    <th class="p-y-2px">Formato</th> <th class="p-y-2px" rowspan="3"></th>
                                    <th class="p-y-2px">CÓDIGO</th> <th class="p-y-2px">---</th>
                                  </tr>
                                  <tr>
                                    <th class="p-y-2px" rowspan="2"  >ANÁLISIS SEGURO DE TRABAJO (ATS)</th>  
                                    <th class="p-y-2px">VERSION</th> <th class="p-y-2px">---</th>
                                  </tr>
                                  <tr>
                                     <th class="p-y-2px">FECHA</th> 
                                     <th class="p-y-2px">12/10/22 MIÉRCOLES</th>
                                  </tr>
                                  <tr>
                                    <th class="p-y-2px"></th>
                                    <th class="p-y-2px">Tarea a realizar:</th>
                                    <th class="p-y-2px" colspan="4"> LIMPIEZA, SOLDADURA, TARRAJEO, CORTE EN MUROS, ARMADO DE ANDAMIOS, INSTALACIONES SANITARIAS.</th>
                                  </tr>
                                  <tr> 
                                    <th class="p-y-2px"></th>
                                    <th class="p-y-2px"> <b> RAZÓN SOCIAL: </b>  <span> SEVEN´S INGENIEROS SELVA S.A.C.</span></th>
                                    <th class="p-y-2px"> <b>  RUC: </b>  <span> 20609935651</span></th>
                                    <th class="p-y-2px"></th>
                                    <th class="p-y-2px" > <b> Lugar: </b>  <span> SEDE MNO</span></th>
                                    <th class="p-y-2px"> <b> N° DE REGISTRO: </b>  <span> 000001-4546</span></th> 
                                  </tr>
                                  <tr>
                                    <th class="p-y-2px"></th>
                                    <th class="p-y-10px" colspan="5"></th>
                                  </tr>
                                  <tr>
                                    <th class="p-y-2px"></th>
                                    <th colspan="5" class="text-center p-y-2px">EQUIPO DE ATS</th>
                                  </tr>
                                  <tr colspan="5"> 
                                    <th class="p-y-2px " > N°</th>
                                    <th class="p-y-2px">Apellidos y Nombres</th>
                                    <th class="p-y-2px">Firma</th>
                                    <th class="p-y-2px">Nº</th> 
                                    <th class="p-y-2px">Apellidos y Nombres</th>
                                    <th class="p-y-2px">Firma</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td class="p-y-2px">1</td>
                                    <td class="p-y-2px">Requejo Sannta cruz David</td>
                                    <td class="p-y-2px">firma</td>
                                    <td class="p-y-2px">1</td>
                                    <td class="p-y-2px">Requejo Sannta cruz David</td>
                                    <td class="p-y-2px">firma</td>
                                  </tr>
                                  <tr>
                                    <td class="p-y-2px">1</td>
                                    <td class="p-y-2px">Requejo Sannta cruz David</td>
                                    <td class="p-y-2px">firma</td>
                                    <td class="p-y-2px">1</td>
                                    <td class="p-y-2px">Requejo Sannta cruz David</td>
                                    <td class="p-y-2px">firma</td>
                                  </tr>
                                  <tr>
                                    <td class="p-y-2px">1</td>
                                    <td class="p-y-2px">Requejo Sannta cruz David</td>
                                    <td class="p-y-2px">firma</td>
                                    <td class="p-y-2px">1</td>
                                    <td class="p-y-2px">Requejo Sannta cruz David</td>
                                    <td class="p-y-2px">firma</td>
                                  </tr>
                                  <tr>
                                    <td class="p-y-2px">1</td>
                                    <td class="p-y-2px">Requejo Sannta cruz David</td>
                                    <td class="p-y-2px">firma</td>
                                    <td class="p-y-2px">1</td>
                                    <td class="p-y-2px">Requejo Sannta cruz David</td>
                                    <td class="p-y-2px">firma</td>
                                  </tr>
                                  <tr>
                                    <td class="p-y-2px">1</td>
                                    <td class="p-y-2px">Requejo Sannta cruz David</td>
                                    <td class="p-y-2px">firma</td>
                                    <td class="p-y-2px">1</td>
                                    <td class="p-y-2px">Requejo Sannta cruz David</td>
                                    <td class="p-y-2px">firma</td>
                                  </tr>
                                  <tr>
                                    <td class="p-y-2px">1</td>
                                    <td class="p-y-2px">Requejo Sannta cruz David</td>
                                    <td class="p-y-2px">firma</td>
                                    <td class="p-y-2px">1</td>
                                    <td class="p-y-2px">Requejo Sannta cruz David</td>
                                    <td class="p-y-2px">firma</td>
                                  </tr>
                                  <tr>
                                    <td class="p-y-2px">1</td>
                                    <td class="p-y-2px">Requejo Sannta cruz David</td>
                                    <td class="p-y-2px">firma</td>
                                    <td class="p-y-2px">1</td>
                                    <td class="p-y-2px">Requejo Sannta cruz David</td>
                                    <td class="p-y-2px">firma</td>
                                  </tr>
                                  <tr>
                                    <td class="p-y-2px">1</td>
                                    <td class="p-y-2px">Requejo Sannta cruz David</td>
                                    <td class="p-y-2px">firma</td>
                                    <td class="p-y-2px">1</td>
                                    <td class="p-y-2px">Requejo Sannta cruz David</td>
                                    <td class="p-y-2px">firma</td>
                                  </tr>

                                </tbody>
                              </table>


                            </div>

                            <div class="tab-pane fade" id="custom-content-below-profile" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                              <!-- <h1>----------------------------------</h1> -->
                              <!-- tabla REGISTRO - SATURACIÓN DE OXÍGENO Y TEMPERATURA -->
                              <button type="button" class="btn bg-gradient-success m-05rem" onclick="export_excel_detalle_temperatura();"> <i class="fas fa-plus-circle"></i> export </button>
                              <br>
                              <table id="formato_temperatura"  class="table table-bordered table-striped">
                                <thead>
                                  <tr class="font-size-14px">
                                    <th class="p-y-3px celda-b-y-0px" colspan="1"></th>
                                    <th class="p-y-3px" colspan="7" rowspan="2">REGISTRO - SATURACIÓN DE OXÍGENO Y TEMPERATURA</th>
                                    <th class="p-y-3px" colspan="2"></th>
                                  </tr>
                                  <tr>
                                    <th class="p-y-3px celda-b-y-0px" colspan="1"></th>
                                    <th class="p-y-3px font-size-13px" colspan="7">REVISIÓN</th>
                                  </tr>
                                  <tr>
                                    <th class="p-y-3px celda-b-y-0px" colspan="1"></th>
                                    <th class="p-y-3px font-size-14px" colspan="7" rowspan="2">CONSTRUCCIÓN DE LA SEDE MISIÓN NOR ORIENTAL - II ETAPA</th>
                                    <th class="p-y-3px font-size-13px" colspan="2">FECHA: 12/10/22</th>
                                  </tr>
                                  <tr class="font-size-14px">
                                    <th class="p-y-3px celda-b-y-0px" colspan="1"></th>
                                    <th class="p-y-3px celda-b-y-0px font-size-13px" colspan="8">MIÉRCOLES</th>
                                  </tr>
                                  <tr class="font-size-14px">
                                    <th class="p-y-3px" colspan="1">PROYECTO</th>
                                    <th class="p-y-3px" colspan="9">CONSTRUCCIÓN DE LA SEDE MISIÓN NOR ORIENTAL - II ETAPA</th>
                                  </tr>
                                  <tr class="font-size-14px">
                                    <th class="p-y-3px" colspan="1">UBICACIÓN</th>
                                    <th class="p-y-3px" colspan="9">JR. RAMIREZ HURTADO N° 317 - DISTRITO DE TARAPOTO, PROVINCIA Y DEPARTAMENTO DE SAN MARTÍN.</th>
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
                                    <th class="p-y-3px">Nombre y Apellido</th>
                                    <th class="p-y-3px">Firma</th>
                                    <th class="p-y-3px">Hora del registro</th>
                                    <th class="p-y-3px">T (°C) (34°-37.5°)</th>
                                    <th class="p-y-3px">%S.O. (87-100)</th>
                                    <th class="p-y-3px">Firma</th>
                                    <th class="p-y-3px">Hora del registro</th>
                                    <th class="p-y-3px">T (°C) (34°-37.5°)</th>
                                    <th class="p-y-3px">%S.O. (87-100)</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td class="p-y-2px">1</td>
                                    <td class="p-y-2px">Requejo Sannta cruz David</td>
                                    <td class="p-y-2px">firma</td>
                                    <td class="p-y-2px">1</td>
                                    <td class="p-y-2px">Requejo Sannta cruz David</td>
                                    <td class="p-y-2px">firma</td>
                                    <td class="p-y-2px">firma</td>
                                    <td class="p-y-2px">firma</td>
                                    <td class="p-y-2px">firma</td>
                                    <td class="p-y-2px">firma</td>
                                  </tr>
                                  <tr>
                                    <td class="p-y-2px">1</td>
                                    <td class="p-y-2px">Requejo Sannta cruz David</td>
                                    <td class="p-y-2px">firma</td>
                                    <td class="p-y-2px">1</td>
                                    <td class="p-y-2px">Requejo Sannta cruz David</td>
                                    <td class="p-y-2px">firma</td>
                                    <td class="p-y-2px">firma</td>
                                    <td class="p-y-2px">firma</td>
                                    <td class="p-y-2px">firma</td>
                                    <td class="p-y-2px">firma</td>
                                  </tr>
                                  <tr>
                                    <td class="p-y-2px">1</td>
                                    <td class="p-y-2px">Requejo Sannta cruz David</td>
                                    <td class="p-y-2px">firma</td>
                                    <td class="p-y-2px">1</td>
                                    <td class="p-y-2px">Requejo Sannta cruz David</td>
                                    <td class="p-y-2px">firma</td>
                                    <td class="p-y-2px">firma</td>
                                    <td class="p-y-2px">firma</td>
                                    <td class="p-y-2px">firma</td>
                                    <td class="p-y-2px">firma</td>
                                  </tr>
                                  <tr>
                                    <td class="p-y-2px">1</td>
                                    <td class="p-y-2px">Requejo Sannta cruz David</td>
                                    <td class="p-y-2px">firma</td>
                                    <td class="p-y-2px">1</td>
                                    <td class="p-y-2px">Requejo Sannta cruz David</td>
                                    <td class="p-y-2px">firma</td>
                                    <td class="p-y-2px">firma</td>
                                    <td class="p-y-2px">firma</td>
                                    <td class="p-y-2px">firma</td>
                                    <td class="p-y-2px">firma</td>
                                  </tr>
                                  <tr>
                                    <td class="p-y-2px">1</td>
                                    <td class="p-y-2px">Requejo Sannta cruz David</td>
                                    <td class="p-y-2px">firma</td>
                                    <td class="p-y-2px">1</td>
                                    <td class="p-y-2px">Requejo Sannta cruz David</td>
                                    <td class="p-y-2px">firma</td>
                                    <td class="p-y-2px">firma</td>
                                    <td class="p-y-2px">firma</td>
                                    <td class="p-y-2px">firma</td>
                                    <td class="p-y-2px">firma</td>
                                  </tr>
                                </tbody>
                              </table>

                            </div>

                            <div class="tab-pane fade show active" id="custom-content-below-messages" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">
                              <!-- tabla REGISTRO - SATURACIÓN DE OXÍGENO Y TEMPERATURA -->
                              <button type="button" class="btn bg-gradient-success m-05rem" onclick="export_excel_control_equipos();"> <i class="fas fa-plus-circle"></i> export </button>
                              <br>
                              <table id="formato_control_equipos"  class="table table-bordered table-striped">
                                <thead>
                                  <tr>
                                    <th class="p-y-3px text-center" colspan="26">CONTROL DIARIO DE EQUIPOS DE PROTECCIÓN PERSONAL</th>
                                  </tr>
                                  <tr>
                                    <th class="p-y-3px text-center" colspan="26">CONSTRUCCIÓN DE LA SEDE MISIÓN NOR ORIENTAL - II ETAPA 12/10/2022 MIERCOLES</th>
                                  </tr>
                                  <tr>
                                    <th class="p-y-3px text-center bg-color-acc3c7" colspan="26">DATOS DEL EMPLEADOR PRINCIPAL:</th>
                                  </tr>
                                  <tr>
                                    <th class="p-y-3px font-size-13px" colspan="3">RAZÓN SOCIAL O <br> DENOMINACIÓN SOCIAL</th>
                                    <th class="p-y-3px font-size-13px" colspan="3">RUC</th>
                                    <th class="p-y-3px font-size-13px" colspan="3">DOMICILIO <br> (Dirección, distrito, departamento, provincia)</th>
                                    <th class="p-y-3px font-size-13px" colspan="3">TIPO DE ACTIVIDAD <br> ECONÓMICA</th>
                                    <th class="p-y-3px font-size-13px" colspan="3">N° TRABAJADORES  <br> EN EL TRABAJO</th>
                                    <th class="p-y-3px font-size-13px celda-b-x-0px" colspan="10" rowspan="2"></th>
                                  </tr>
                                  <tr>
                                    <th class="p-y-3px font-size-12px" colspan="3">SEVEN´S INGENIEROS SELVA  <br>  S.A.C. </th>
                                    <th class="p-y-3px font-size-12px" colspan="3">20609935651</th>
                                    <th class="p-y-3px font-size-12px" colspan="3">JR. MANCO CAPAC N°491  <br> SAN MARTIN - MORALES.</th>
                                    <th class="p-y-3px font-size-12px" colspan="3">ARQUITECTURA E INGENIERIA</th>
                                    <th class="p-y-3px font-size-12px" colspan="3"></th>
                                    <th class="p-y-3px font-size-12px celda-b-y-0px celda-b-x-0px" colspan="11" ></th>
                                  </tr>
                                  <tr class="font-size-12px text-center">
                                    <th class="p-y-3px">N°</th>
                                    <th class="p-y-3px" >VERIFICACIÓN DE EQUIPOS DE SEGURIDAD</th>
                                    <th class="p-y-3px"  colspan="2">CASCO</th>
                                    <th class="p-y-3px"  colspan="2">CORTA VIENTO</th>
                                    <th class="p-y-3px"  colspan="2">LENTES DE SEGURIDAD</th>
                                    <th class="p-y-3px"  colspan="2">GUANTES DE NITRILO</th>
                                    <th class="p-y-3px"  colspan="2">GUANTES DE JEBE</th>
                                    <th class="p-y-3px"  colspan="2">GUANTES DE CUERO</th>
                                    <th class="p-y-3px"  colspan="2">ZAPATOS DE SEGURIDAD</th>
                                    <th class="p-y-3px"  colspan="2">PROTECTORES AUDITIVOS</th>
                                    <th class="p-y-3px"  colspan="2">ARNES</th>
                                    <th class="p-y-3px"  colspan="2">CARETA</th>
                                    <th class="p-y-3px"  colspan="2">BOTAS DE JEBE</th>
                                    <th class="p-y-3px"  colspan="2">CAPOTIN</th>
                                  </tr>
                                  <tr class="font-size-13px text-center">

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
                                  <tr>
                                    <td class="p-y-2px">1</td>
                                    <td class="p-y-2px text-nowrap">Requejo Sannta cruz David</td>
                                  </tr>
                                  <tr>
                                    <td class="p-y-2px">1</td>
                                    <td class="p-y-2px">Requejo Sannta cruz David melvin Requejo sa nta  crf </td>
                                  </tr>
                                  <tr>
                                    <td class="p-y-2px">1</td>
                                    <td class="p-y-2px">Requejo Sannta cruz David</td>
                                  </tr>

                                </tbody>
                              </table>
                            </div>

                            <div class="tab-pane fade" id="custom-content-below-settings" role="tabpanel" aria-labelledby="custom-content-below-settings-tab">
                              Pellentesque vestibulum commodo nibh nec blandit. Maecenas neque magna, iaculis tempus turpis ac, ornare sodales tellus. Mauris eget blandit dolor. Quisque tincidunt venenatis vulputate. Morbi euismod molestie tristique. Vestibulum consectetur dolor a vestibulum pharetra. Donec interdum placerat urna nec pharetra. Etiam eget dapibus orci, eget aliquet urna. Nunc at consequat diam. Nunc et felis ut nisl commodo dignissim. In hac habitasse platea dictumst. Praesent imperdiet accumsan ex sit amet facilisis.
                            </div>

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
                <!-- MODAL - agregar asistencia - :::::::::::::::::::::::::::::::::: NO SE SE USA -->
                <div class="modal fade" id="modal-agregar-asistencia">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Agregar asistencia</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form id="form-asistencia" name="form-asistencia" method="POST">                    
                          <div class="row" >
                            <!-- id proyecto -->
                            <input type="hidden" name="idproyecto" id="idproyecto" required />

                            <!-- id asistencia -->
                            <input type="hidden" name="idasistencia_trabajador" id="idasistencia_trabajador" />

                            <!-- fecha del registro de la asistencia -->
                            <div class="col-lg-4  mb-2">
                              <div class="form-group">
                                <label for="fecha">Fecha de asistencia</label>
                                <input type="date" class="form-control" name="fecha" id="fecha"  />                            
                              </div>
                            </div>

                            <!-- Seleccionar una fecha para todos -->
                            <div class="col-lg-4 mb-2">
                              <div class="bootstrap-timepicker">
                                <div class="form-group">
                                  <label>Hora para todos:</label>
                                  <div class="input-group date" id="timepicker" data-target-input="nearest">
                                    <input type="text" id="hora_all" class="form-control datetimepicker-input" data-target="#timepicker" onchange="agregar_hora_all();" onkeyup="agregar_hora_all();" oninput="agregar_hora_all()" />
                                    <div class="input-group-append" data-target="#timepicker" data-toggle="datetimepicker">
                                      <div class="input-group-text"><i class="far fa-clock"></i></div>
                                    </div>
                                    </div>
                                  <!-- /.input group -->
                                </div>
                                <!-- /.form group -->
                              </div>
                            </div>

                            <div class="col-lg-4"></div>
                            
                            <div class="col-lg-12">
                              <div class="row" id="lista-de-trabajadores">
                                <!-- Lista de todos lo trabajadores -->
                              </div>                                                  
                            </div> 
                          </div>                   
                          
                          <!-- /.card-body -->
                          <button type="submit" style="display: none;" id="submit-form-asistencia">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro">Guardar Cambios</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- MODAL - editar asistencia - :::::::::::::::::::::::::::::::::::: NO SE SE USA -->
                <div class="modal fade" id="modal-editar-asistencia">
                  <div class="modal-dialog modal-dialog-scrollable modal-md">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Editar asistencia</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form id="form-editar-asistencia" name="form-editar-asistencia" method="POST">
                          <div class="row" id="cargando-1-fomulario">
                            <!-- id proyecto -->
                            <input type="hidden" name="idproyecto2" id="idproyecto2" required />

                            <!-- id asistencia -->
                            <input type="hidden" name="idasistencia_trabajador2" id="idasistencia_trabajador2"   />

                            <!-- fecha del registro de la asistencia -->
                            <div class="col-lg-12 mb-2">
                              <div class="form-group">
                                <label for="fecha">Fecha de asistencia</label>
                                <input type="date" class="form-control" name="fecha2" id="fecha2"  />                            
                              </div>
                            </div>                      
                            
                            <div class="col-lg-12">
                              <div class="row" id="lista-de-trabajadores2">
                                <!-- Lista de todos lo trabajadores -->
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
                          <!-- /.card-body -->
                          <button type="submit" style="display: none;" id="submit-form-asistencia2">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro2">Guardar Cambios</button>
                      </div>
                    </div>
                  </div>
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
                
                <!-- MODAL - adicinoal / descuento -->
                <div class="modal fade" id="modal-adicional-descuento" >
                  <div class="modal-dialog modal-dialog-scrollable modal-md">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Adicional / descuento</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form id="form-adicional-descuento" name="form-adicional-descuento" method="POST">
                          <div class="row" id="cargando-5-fomulario">
                            
                            <!-- id adicionales -->
                            <input type="hidden" name="idresumen_q_s_asistencia" id="idresumen_q_s_asistencia" /> 
                            <!-- ID trabajador por proyecto -->
                            <input type="hidden" name="idtrabajador_por_proyecto" id="idtrabajador_por_proyecto" />   
                            <!-- fecha de quincena o semana -->
                            <input type="hidden" name="fecha_q_s" id="fecha_q_s" />                                         
                            
                            <!-- Descripcion -->
                            <div class="col-md-12 col-lg-12">
                              <div class="form-group">
                                <label for="nombre">Descripción</label>
                                <textarea name="detalle_adicional" id="detalle_adicional" class="form-control" rows="5" placeholder="Ingresa descripción"></textarea>
                              </div>
                            </div> 
                          </div>

                          <div class="row" id="cargando-6-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                              <br />
                              <h4>Cargando...</h4>
                            </div>
                          </div>
                          <!-- /.card-body -->
                          <button type="submit" style="display: none;" id="submit-form-adicional-descuento">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_adicional_descuento">Guardar Cambios</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- MODAL - cargando -->
                <div class="modal fade" id="modal-cargando" data-keyboard="false" data-backdrop="static">
                  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md">
                    <div class="modal-content">
                      <div class="modal-body">
                        
                        <div id="icono-respuesta">
                          <!-- icon ERROR -->
                          <!-- icon success -->
                        </div>
                        
                        <!-- barprogress -->
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                          <div class="progress h-px-30" id="div_barra_progress">
                            <div id="barra_progress" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                              0%
                            </div>
                          </div>
                        </div> 
                        
                        <!-- boton -->
                        <div class="swal2-actions" >
                          <div class="swal2-loader"></div>
                          <button onclick="cerrar_modal_cargando()" type="button" class="swal2-confirm swal2-styled" aria-label="" style="display: inline-block;">OK</button>                         
                        </div>
                      </div>                     
                    </div>
                  </div>
                </div>

                <!-- MODAL - Fechas de Actividades -->
                <div class="modal fade" id="modal-agregar-fechas-actividades">
                  <div class="modal-dialog /*modal-dialog-scrollable*/ modal-md">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Fechas de Actividades</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form id="form-fechas-actividades" name="form-fechas-actividades" method="POST">
                          <div class="row" id="cargando-7-fomulario">
                            
                            <!-- id asistencia -->
                            <input type="hidden" name="id_proyecto_f" id="id_proyecto_f" /> 

                            <!-- FECHA INICIO DE ACTIVIDADES -->
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label for="">Fecha Inicio de actividades: <sup class="text-danger">*</sup></label>
                                <div class="input-group date "  data-target-input="nearest">
                                  <input type="text" class="form-control" id="fecha_inicio_actividad" name="fecha_inicio_actividad" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask onchange="calcular_plazo_actividad();"  />
                                  <div class="input-group-append click-btn-fecha-inicio-actividad cursor-pointer" for="fecha_inicio_actividad" >
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                  </div>
                                </div>                                 
                              </div>
                            </div>
                              
                            <!-- FECHA INICIO FIN DE ACTIVIDADES -->
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label>Fecha Fin de actividades: <sup class="text-danger">*</sup></label>
                                <div class="input-group date"  data-target-input="nearest">
                                  <input type="text" class="form-control" id="fecha_fin_actividad" name="fecha_fin_actividad" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask onchange="calcular_plazo_actividad();" />
                                  <div class="input-group-append click-btn-fecha-fin-actividad cursor-pointer">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                  </div>
                                </div>                                 
                              </div>
                            </div>

                            <!-- Dias habiles -->
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label for="plazo_actividad">Plazo Actividades<sup class="text-danger">*</sup> <small class="text-orange">(días hábiles)</small> </label>
                                <span class="form-control plazo_actividad"> 0 </span>
                                <input type="hidden" name="plazo_actividad" id="plazo_actividad" >
                              </div>
                            </div>                                                     

                          </div>

                          <div class="row" id="cargando-8-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                              <br />
                              <h4>Cargando...</h4>
                            </div>
                          </div>
                          <!-- /.card-body -->
                          <button type="submit" style="display: none;" id="submit-form-fechas-actividades">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro_fechas_actividades">Guardar Cambios</button>
                      </div>
                    </div>
                  </div>
                </div> 
                
                <!-- MODAL - Horas Multiples -->
                <div class="modal fade" id="modal-agregar-horas-multiples" data-keyboard="false" data-backdrop="static">
                  <div class="modal-dialog /*modal-dialog-scrollable*/ modal-md">
                    <div class="modal-content">

                      <div class="modal-header">
                        <h4 class="modal-title"><i class="far fa-clock fa-lg m-1"></i> Asignar horas multiples</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body"> 
                        <form id="form-horas-multiples" name="form-horas-multiples" method="POST">                         
                          
                          <!-- Horas -->
                          <div class="col-lg-12"> 
                            <div class="form-group">
                              <label for="horas_multiples">Horas<sup class="text-danger">*</sup> <small class="text-danger">(para todos los trabajadores)</small> </label>
                              <input class="form-control" type="number" name="horas_multiples" id="horas_multiples" >
                            </div>
                          </div>

                          <!-- barprogress -->
                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                            <div class="progress_h_multiple h-px-30" id="div_barra_progress_h_multiple">
                              <div id="barra_progress_h_multiple" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                0%
                              </div>
                            </div>
                          </div> 
                          
                          <button type="submit" style="display: none;" id="submit-form-horas-multiples">Submit</button>
                        </form>
                      </div>

                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button  class="btn btn-success horas-multiples" >Asignar horas</button>
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
        
        <!-- moment locale -->
        <script src="../plugins/moment/locales.js"></script>

        <script type="text/javascript" src="scripts/formato_varios.js"></script>

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>
        
      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
