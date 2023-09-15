<?php 
  require '../vendor/autoload.php';
  use Luecano\NumeroALetras\NumeroALetras;

  if (strlen(session_id()) < 1) {
    session_start();
  }

  if (!isset($_SESSION["nombre"])) {
    header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {   

    require_once "../modelos/Compra_activos_fijos.php";
    require_once "../modelos/Compra_insumos.php";  
    
    $compra_activos_fijos = new Compra_activos_fijos();
    $compra_insumo = new Compra_insumos();
    $numero_a_letra = new NumeroALetras();

    $rspta = ''; $html_producto = ''; $cont = 1; 

    if (empty($_GET)) {
      header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
    } else if ($_GET['op'] == 'insumo') {
      $id = $_GET['id'];
      $rspta = $compra_insumo->ver_detalle_compra($id);      
    }

    foreach ($rspta['data']['detalle_producto'] as $key => $reg) {
      $html_producto .= '<tr>
        <td>'.$cont++.'</td>
        <td>'.$reg['nombre'].'</td>
        <td>'.$reg['abreviacion'].'</td>
        <td>'.$reg['cantidad'].'</td>
        <td>'.number_format($reg['precio_sin_igv'], 2, '.',',').'</td>
        <td>'.number_format($reg['igv'], 2, '.',',').'</td>
        <td>'.number_format($reg['precio_con_igv'], 2, '.',',') .'</td>
        <td>'.number_format($reg['descuento'], 2, '.',',').'</td>
        <td class="text-right">'.number_format($reg['subtotal'], 2, '.',',').'</td>
      </tr>';
    }

    $num_total = $numero_a_letra->toMoney( $rspta['data']['total'], 2, 'soles' );  #echo $num_total; die;
    $centimos = (isset($decimales_mun[1])? $decimales_mun[1] : '00' ) . '/100 CÉNTIMOS';
    $con_letra = strtoupper( $num_total .' '. $centimos );  
    
  }

?>

<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta http-equiv="Cache-control" content="no-cache">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante compra - <?php echo $rspta['data']['tipo_comprobante'] . '-' . $rspta['data']['serie_comprobante']; ?></title>

    <link rel="apple-touch-icon" href="../dist/svg/logo-icono.svg">
    <link rel="shortcut icon" href="../dist/svg/logo-icono.svg">
    
    <!-- fontawesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free-6.2.0/css/all.min.css" />

    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css" />

    <!-- style nuevo -->
    <link rel="stylesheet" href="../dist/css/style_new.css" />

    <!-- busquda de modulo -->
    <style>
      .search-title{ color: black !important;}
      .search-title strong { color: #007bff !important; }      
    </style>

  </head>
  <body class="hold-transition sidebar-collapse layout-top-nav">
    <div class="wrapper">
      <!-- Navbar -->
      <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
        <div class="container">
          <a href="../index.php" class="navbar-brand">
            <img src="../dist/svg/logo-principal-nombre.svg" alt="Sevens Logo" class="w-130px" style="opacity: 0.8;" />
            <!-- <span class="brand-text font-weight-light"></span> -->
          </a>

          <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse order-3" id="navbarCollapse">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
              <!-- <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
              </li> -->
              <li class="nav-item">
                <a href="../vistas/escritorio.php" class="nav-link"><i class="fa-solid fa-house-chimney"></i> Home</a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link"><i class="fa-solid fa-user-secret"></i> Contact</a>
              </li>              
            </ul>

            <!-- SEARCH FORM -->
            <form class="form-inline ml-0 ml-md-3">
              <div class="input-group input-group-sm" data-widget="sidebar-search">
                <input class="form-control form-control-navbar w-300px" type="search" placeholder="Buscar modulo" aria-label="Search" id="busqueda_modulos"/>
                <div class="input-group-append"><button class="btn btn-navbar" type="submit" ><i class="fas fa-search"></i></button></div>
              </div>              
            </form>
          </div>

          <!-- Right navbar links -->
          <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">            
           
            <!-- Pantalla completa -->
            <li class="nav-item d-none d-sm-inline-block">
              <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
              </a>
            </li>
            <!-- FIN Pantalla completa -->
          </ul>
        </div>
      </nav>
      <!-- /.navbar -->

      <!-- Main Sidebar Container -->
      <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="../vistas/escritorio.php" class="brand-link">
          <img src="../dist/svg/logo-icono.svg" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: 0.8;" />
          <span class="brand-text font-weight-light">Admin Sevens</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar"> 
          <!-- Sidebar user panel (optional) -->
          <!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
              <img src="../dist/svg/empresa-logo.svg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
              <a href="#" class="d-block">Construccion del baño portodoloque parte de no se</a>
            </div>
          </div>     -->

          

          <!-- Sidebar Menu -->
          <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column /*nav-flat*/" data-widget="treeview" role="menu" data-accordion="false">
              <!-- MANUAL DE USUARIO -->
              <li class="nav-item">
                <a href="../vistas/manual_de_usuario.php" class="nav-link pl-2" id="mManualDeUsuario">
                  <i class="nav-icon fas fa-book"></i>
                  <p>
                    Manual de Usuario
                    <span class="right badge badge-success">new</span>
                  </p>
                </a>
              </li>
              <?php if ($_SESSION['escritorio']==1) {  ?>
                <!-- ESCRITORIO -->
                <li class="nav-item">
                  <a href="../vistas/escritorio.php" class="nav-link pl-2" id="mEscritorio">
                    <i class="nav-icon fas fa-th"></i>
                    <p>
                      Escritorio
                      <span class="right badge badge-danger">Home</span>
                    </p>
                  </a>
                </li>
              <?php  }  ?>

              <?php if ($_SESSION['acceso']==1) {  ?>
                <!-- ACCESOS -->
                <li class="nav-item  b-radio-3px" id="bloc_Accesos">
                  <a href="#" class="nav-link pl-2" id="mAccesos">
                    <i class="nav-icon fas fa-shield-alt"></i>
                    <p>
                      Accesos
                      <i class="fas fa-angle-left right"></i>
                      <span class="badge badge-info right">2</span>
                    </p>
                  </a>
                  <ul class="nav nav-treeview ">
                    <!-- Usuarios del sistema -->
                    <li class="nav-item ">
                      <a href="../vistas/usuario.php" class="nav-link " id="lUsuario">
                        <i class="nav-icon fas fa-users-cog"></i>
                        <p>Usuarios</p>
                      </a>
                    </li>
                    <!-- Permisos de los usuarios del sistema -->
                    <li class="nav-item ">
                      <a href="../vistas/permiso.php" class="nav-link" id="lPermiso">
                        <i class="nav-icon fas fa-lock"></i>
                        <p>Permisos</p>
                      </a>
                    </li>      
                  </ul>
                </li>
              <?php  }  ?>


              <?php if ($_SESSION['recurso']==1) {  ?>
                <!-- Recursos -->
                <li class="nav-item  b-radio-3px" id="bloc_Recurso">
                  <a href="#" class="nav-link pl-2" id="mRecurso">
                    <i class="nav-icon fas fa-project-diagram"></i>
                    <p>
                      Recursos
                      <i class="fas fa-angle-left right"></i>
                      <span class="badge badge-info right">6</span>
                    </p>
                  </a>
                  <ul class="nav nav-treeview ">
                    <!-- Usuarios del sistema -->
                    <li class="nav-item ">
                      <a href="../vistas/all_trabajador.php" class="nav-link" id="lAllTrabajador">
                        <i class="nav-icon fas fa-users"></i>
                        <p>All-Trabajador</p>
                      </a>
                    </li>
                    <!-- Proveedores de la empresa -->
                    <li class="nav-item ">
                      <a href="../vistas/all_proveedor.php" class="nav-link" id="lAllProveedor">
                        <i class="nav-icon fas fa-truck"></i>
                        <p>All-Proveedor</p>
                      </a>
                    </li>  
                    <!-- Maquinas de la empresa -->
                    <li class="nav-item ">
                      <a href="../vistas/all_maquinas.php" class="nav-link" id="lAllMaquinas">
                        <i class="nav-icon fas fa-tractor"></i>
                        <p>Máquinas-Equipos</p>
                      </a>
                    </li>
                    <!-- Materiales para la empresa -->
                    <li class="nav-item ">
                      <a href="../vistas/materiales.php" class="nav-link" id="lAllMateriales">
                        
                      <img src="../dist/svg/palana-ico.svg" class="nav-icon" alt="" style="width: 21px !important;" >
                        <p>Insumos</p>
                      </a>
                    </li>
                    <!-- Activos fijos -->
                    <li class="nav-item ">
                      <a href="../vistas/activos_fijos.php" class="nav-link" id="lActivosfijos">
                      <i class="nav-icon fas fa-truck-pickup"></i>
                        <p>Activos fijos</p>
                      </a>
                    </li>
                    <!-- Calendario de la empresa -->
                    <li class="nav-item ">
                      <a href="../vistas/all_calendario.php" class="nav-link" id="lAllCalendario">
                      <i class="nav-icon far fa-calendar-alt"></i>
                        <p>All-Calendario</p>
                      </a>
                    </li>
                    <!-- Datos Generales Bancos y color -->
                    <li class="nav-item ">
                      <a href="../vistas/otros.php" class="nav-link" id="lOtros">
                        <i class="nav-icon fas fa-coins"></i>
                        <p>Otros</p>
                      </a>
                    </li>
                  </ul>
                </li>
              <?php  }  ?>

              <?php if ($_SESSION['compra_activo_fijo']==1) {  ?>
                <!-- ALL ACTIVOS FIJOS -->
                <li class="nav-item">
                  <a href="../vistas/compra_activos_fijos.php" class="nav-link pl-2" id="mAllactivos_fijos">
                    <i class="nav-icon fas fa-hand-holding-usd"></i>
                    <p>Compras de activos fijos</p>
                  </a>
                </li>
              <?php  }  ?>

              <?php if ($_SESSION['resumen_activo_fijo_general']==1) {  ?>
                <!-- RESUMEN ACTIVOS FIJOS GENERAL-->
                <li class="nav-item">
                  <a href="../vistas/resumen_activos_fijos_general.php" class="nav-link pl-2" id="mResumenActivosFijosGeneral">
                    <i class="nav-icon fas fa-tasks"></i>
                    <p>Resumen activos fijos</p>
                  </a>
                </li>
              <?php  }  ?>

              <?php if ($_SESSION['otra_factura']==1) {  ?>
                <li class="nav-item">
                  <a href="../vistas/otra_factura.php" class="nav-link pl-2" id="lOtraFactura">
                    <i class="nav-icon fas fa-receipt"></i>
                    <p>Otras Facturas</p>
                  </a>
                </li>
              <?php  }  ?>
              
              <?php if ($_SESSION['resumen_factura']==1) {  ?>
                <li class="nav-item">
                  <a href="../vistas/resumen_factura.php" class="nav-link pl-2" id="lResumenFacura">            
                    <i class="nav-icon fas fa-poll"></i>
                    <p>Resumen de Facturas</p>
                  </a>
                </li>
              <?php  }  ?>

              <?php if ($_SESSION['resumen_recibo_por_honorario']==1) {  ?>
                <li class="nav-item">
                  <a href="../vistas/resumen_rh.php" class="nav-link pl-2" id="lResumenRH">            
                    <i class="nav-icon fas fa-poll"></i>
                    <p>Resumen de RH</p>
                  </a>
                </li>
              <?php  }  ?>
              
              <?php if ($_SESSION['papelera']==1) {  ?>
                <li class="nav-item">
                  <a href="../vistas/papelera.php" class="nav-link pl-2" id="mPapelera">
                    <i class="nav-icon fas fa-trash-alt"></i>
                    <p>Papelera</p>
                  </a>
                </li>
              <?php  }  ?>
              
              <li class="nav-header">MÓDULOS</li>

              <!-- cargando -->     
              <li class="nav-item ver-otros-modulos-2" style="display: none !important;">
                <a href="#" class="nav-link" >
                <i class="fas fa-spinner fa-pulse "></i>
                  <p>Cargando...</p>
                </a>
              </li>

              <!-- <li class="nav-header bg-color-2c2c2c">TÉCNICO</li>  -->
              
              <li class="nav-item ver-otros-modulos-1" id="bloc_Tecnico">
                <a href="#" class="nav-link bg-color-2c2c2c" id="mTecnico" style="padding-left: 7px;">
                  <i class="nav-icon far fa-circle"></i>
                  <p class="font-size-14px">TÉCNICO <i class="fas fa-angle-left right"></i><span class="badge badge-info right">4</span></p>
                </a>
                <ul class="nav nav-treeview">
                  <?php if ($_SESSION['valorizacion']==1) {  ?>
                    <!-- VALORIZACIONES -->
                    <li class="nav-item ver-otros-modulos-1">
                      <a href="../vistas/valorizacion.php" class="nav-link pl-2" id="lValorizacion">
                        <i class="nav-icon far fa-file-alt"></i>
                        <p>Valorizaciones </p>
                      </a>
                    </li>
                  <?php  }  ?>
                  
                  <?php if ($_SESSION['grafico_valorizacion']==1) {  ?>
                  <!-- graficos insumos -->
                  <li class="nav-item ">
                    <a href="../vistas/chart_valorizacion.php" class="nav-link pl-2" id="lChartValorizacion">
                      <i class="nav-icon fas fa-chart-line"></i> <p>Gráficos</p>
                    </a>
                  </li> 
                  <?php  }  ?>

                  <?php if ($_SESSION['asistencia_obrero']==1) {  ?>
                    <!-- REGISTRO DE ASISTENCIA -->
                    <li class="nav-item ver-otros-modulos-1">
                      <a href="../vistas/asistencia_obrero.php" class="nav-link pl-2" id="lAsistencia">
                        <i class="fas fa-clipboard-list nav-icon"></i>
                        <p>Asistencia del obrero </p>
                      </a>
                    </li>
                  <?php  }  ?>

                  <?php if ($_SESSION['calendario']==1) {  ?>
                    <!-- CALENDARIO -->       
                    <li class="nav-item ver-otros-modulos-1">
                      <a href="../vistas/calendario.php" class="nav-link pl-2" id="lCalendario">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>Calendario </p>
                      </a>
                    </li>
                  <?php  }  ?>

                  <?php if ($_SESSION['plano_otro']==1) {  ?>
                    <!-- PLANOS Y OTROS -->       
                    <li class="nav-item ver-otros-modulos-1">
                      <a href="../vistas/plano_otro.php" class="nav-link pl-2" id="lPlanoOtro">
                        <i class="nav-icon fas fa-map-marked-alt"></i>
                        <p>Planos y otros </p>
                      </a>
                    </li>
                  <?php  }  ?>

                  <?php if ($_SESSION['movimiento_tierras']==1) {  ?>
                  <!-- MOVIMIENTO DE TIERRA -->       
                  <li class="nav-item ver-otros-modulos-1">
                    <a href="../vistas/movimiento_tierra.php" class="nav-link pl-2" id="lMovientoTierras">
                      <!--<i class="nav-icon fas fa-map-marked-alt"></i>lanilla-seguro-ico.svg-->
                      <i class="nav-icon fas fa-dumpster"></i>
                      <p>Movimiento de Tierras</p>
                    </a>
                  </li>
                  <?php  }  ?>

                  <?php if ($_SESSION['concreto_agregado']==1) {  ?>
                  <!-- CONCRETO, CEMENTO Y AGREGADO -->       
                  <li class="nav-item ver-otros-modulos-1">
                    <a href="../vistas/concreto_agregado.php" class="nav-link pl-2" id="lConcretoAgregado">
                      <!-- <i class="nav-icon fas fa-dumpster"></i> -->
                      <img src="../dist/svg/concreto-agregado-ico.svg" class="nav-icon" alt="" style="width: 21px !important;" >
                      <p>Concreto y Agregado</p>
                    </a>
                  </li>
                  <?php  }  ?>

                  <?php if ($_SESSION['fierro']==1) {  ?>
                  <!-- FIERROS -->       
                  <li class="nav-item ver-otros-modulos-1">
                    <a href="../vistas/fierro.php" class="nav-link pl-2" id="lFierro">
                      <!-- <i class="nav-icon fas fa-dumpster"></i> -->
                      <img src="../dist/svg/fierro-ico.svg" class="nav-icon" alt="" style="width: 21px !important;" >
                      <p>Fierros</p>
                    </a>
                  </li>
                  <?php  }  ?>
                </ul>
              </li>        

              <!-- <li class="nav-header bg-color-2c2c2c">LOGÍSTICA Y ADQUISICIONES</li> -->
              
              <!-- LOGÍSTICA Y ADQUISICIONES -->      
              <li class="nav-item ver-otros-modulos-1" id="bloc_LogisticaAdquisiciones">
                <a href="#" class="nav-link bg-color-2c2c2c" id="mLogisticaAdquisiciones" style="padding-left: 7px;">
                  <i class="nav-icon far fa-circle"></i>
                  <p class="font-size-14px">LOGÍSTICA Y ADQUISICIONES <i class="fas fa-angle-left right"></i></p>
                </a>
                <ul class="nav nav-treeview">

                  <?php if ($_SESSION['trabajador']==1) {  ?>
                    <!-- TRABAJADORES -->
                    <li class="nav-item ver-otros-modulos-1">
                      <a href="../vistas/trabajador.php" class="nav-link pl-2" id="lTrabajador">
                        <!-- <i class="nav-icon fas fa-hard-hat"></i> -->
                        <img src="../dist/svg/constructor-ico.svg" class="nav-icon" alt="" style="width: 21px !important;" >
                        <p>Trabajadores</p>
                      </a>
                    </li>
                  <?php  }  ?>

                  <?php if ($_SESSION['compra_insumos']==1) {  ?>   
                    <!-- COMPRAS -->      
                    <li class="nav-item ver-otros-modulos-1 b-radio-3px" id="bloc_Compras">
                      <a href="#" class="nav-link pl-2" id="mCompra">
                        <i class="fas fa-shopping-cart nav-icon"></i>
                        <p>Compras <i class="fas fa-angle-left right"></i> <span class="badge badge-info right">3</span></p>
                      </a>
                      <ul class="nav nav-treeview">
                        <!-- Compras del proyecto -->
                        <li class="nav-item ">
                          <a href="../vistas/compra_insumos.php" class="nav-link" id="lCompras">
                            <i class="nav-icon fas fa-cart-plus"></i> <p>Compras</p>
                          </a>
                        </li>
                        <!-- Resumend de Insumos -->
                        <li class="nav-item ">
                          <a href="../vistas/resumen_insumos.php" class="nav-link" id="lResumenInsumos">
                            <i class="nav-icon fas fa-tasks"></i> <p>Resumen de insumos</p>
                          </a>
                        </li> 
                        <!-- Resumend de Insumos -->
                        <li class="nav-item ">
                          <a href="../vistas/resumen_activos_fijos.php" class="nav-link" id="lResumenActivosFijos">
                            <i class="nav-icon fas fa-tasks"></i> <p>Resumen de Activos Fijos</p>
                          </a>
                        </li> 
                        <!-- graficos insumos -->
                        <li class="nav-item ">
                          <a href="../vistas/chart_compra_insumo.php" class="nav-link" id="lChartCompraInsumo">
                            <i class="nav-icon fas fa-chart-line"></i> <p>Gráficos</p>
                          </a>
                        </li> 
                      </ul>
                    </li>
                  <?php  }  ?>           

                  <?php if ($_SESSION['servicio_maquina']==1) {  ?>  
                    <!-- SERVICIO -->       
                    <li class="nav-item ver-otros-modulos-1">
                      <a href="../vistas/servicio_maquina.php" class="nav-link pl-2" id="lMaquina">
                        <!-- <i class="nav-icon fas fa-tractor"></i> -->
                        <img src="../dist/svg/excabadora-ico.svg" class="nav-icon" alt="" style="width: 21px !important;" >
                        <p>Servicio - Máquina </p>
                      </a>
                    </li>
                  <?php  }  ?>

                  <?php if ($_SESSION['servicio_equipo']==1) {  ?>  
                    <!-- EQUIPOS -->       
                    <li class="nav-item ver-otros-modulos-1">
                      <a href="../vistas/servicio_equipos.php" class="nav-link pl-2" id="lEquipo">
                        <!-- <i class="nav-icon fas fa-tractor"></i> -->
                        <img src="../dist/svg/estacion-total-ico.svg" class="nav-icon" alt="" style="width: 21px !important;" >
                        <p>Servicio - Equipos </p>
                      </a>
                    </li>
                  <?php  }  ?>

                  <?php if ($_SESSION['subcontrato']==1) {  ?>  
                  <li class="nav-item ver-otros-modulos-1">
                    <a href="../vistas/sub_contrato.php" class="nav-link pl-2" id="lSubContrato">
                      <i class="nav-icon fas fa-hands-helping"></i>
                      <p>Sub Contrato </p>
                    </a>
                  </li>
                  <?php  }  ?>            
                  
                  <?php if ($_SESSION['planilla_seguro']==1) {  ?>
                    <!-- PLANILLAS Y SEGUROS -->       
                    <li class="nav-item ver-otros-modulos-1">
                      <a href="../vistas/planillas_seguros.php" class="nav-link pl-2" id="lPlanillaSeguro">
                        <!--<i class="nav-icon fas fa-map-marked-alt"></i>lanilla-seguro-ico.svg-->
                        <img src="../dist/svg/planilla-seguro-ico.svg" class="nav-icon" alt="" style="width: 21px !important;" >
                        <p>Planillas y seguros</p>
                      </a>
                    </li>
                  <?php  }  ?>

                  <?php if ($_SESSION['otro_gasto']==1) {  ?>
                    <!-- OTROS GASTOS -->       
                    <li class="nav-item ver-otros-modulos-1">
                      <a href="../vistas/otro_gasto.php" class="nav-link pl-2" id="lOtroGasto">
                        <i class="nav-icon fas fa-network-wired"></i>
                        <p>Otros Gastos </p>
                      </a>
                    </li>
                  <?php  }  ?>

                  <?php if ($_SESSION['resumen_general']==1) {  ?>
                    <!-- OTROS SERVICIOS -->       
                    <li class="nav-item ver-otros-modulos-1">
                      <a href="../vistas/resumen_general.php" class="nav-link pl-2" id="lresumen_general">                  
                        <i class="nav-icon fas fa-list-ul"></i>
                        <p>Resumen general </p>
                      </a>
                    </li>
                  <?php  }  ?>
                  
                  <?php if ($_SESSION['viatico']==1) {  ?>
                    <!-- BIÁTICOS -->
                    <li class="nav-item ver-otros-modulos-1"  id="bloc_Viaticos">
                      <a href="#" class="nav-link pl-2" id="mViatico">
                        <i class="nav-icon fas fa-plane"></i>
                        <p>Viáticos <i class="right fas fa-angle-left"></i> <span class="badge badge-info right">3</span> </p>
                      </a>
                      <ul class="nav nav-treeview">
                        <!-- TRANSPORTE -->
                        <li class="nav-item">
                          <a href="../vistas/transporte.php" class="nav-link" id="lTransporte">
                            <i class="fas fa-shuttle-van nav-icon"></i>
                            <p>Transporte</p>
                          </a>
                        </li>
                        <!-- HOSPEDAJE -->
                        <li class="nav-item">
                          <a href="../vistas/hospedaje.php" class="nav-link" id="lHospedaje"> 
                            <i class="fas fa-hotel nav-icon"></i>
                            <p>Hospedaje</p>
                          </a>
                        </li>
                        <!-- COMIDA -->
                        <li class="nav-item  b-radio-3px" id="sub_bloc_comidas">
                          <a href="#" class="nav-link"  id="sub_mComidas">
                            <i class="fas fa-fish nav-icon"></i>
                            <p>Comida <i class="right fas fa-angle-left"></i> <span class="badge badge-info right">3</span></p>
                          </a>
                          <ul class="nav nav-treeview">
                            <li class="nav-item">
                              <a href="../vistas/pension.php" class="nav-link" id="lPension">
                                <i class="fas fa-utensils nav-icon"></i>
                                <p>Pensión</p>
                              </a>
                            </li>
                            <li class="nav-item">
                              <a href="../vistas/break.php" class="nav-link" id="lBreak" >
                                <i class="fas fa-hamburger nav-icon"></i>
                                <p>Break</p>
                              </a>
                            </li>
                            <li class="nav-item">
                              <a href="../vistas/comidas_extras.php" class="nav-link" id="lComidasExtras" >
                                <i class="fas fa-drumstick-bite nav-icon"></i>
                                <p>Comidas - extras</p>
                              </a>
                            </li>
                          </ul>
                        </li>              
                      </ul>
                    </li>
                  <?php  }  ?>
                </ul>
              </li>        

              <!-- <li class="nav-header bg-color-2c2c2c">CONTABLE Y FINANCIERO</li> -->
              
              <li class="nav-item ver-otros-modulos-1" id="bloc_ContableFinanciero">
                <a href="#" class="nav-link bg-color-2c2c2c" id="mContableFinanciero" style="padding-left: 7px;">
                  <i class="nav-icon far fa-circle"></i>
                  <p class="font-size-14px">CONTABLE Y FINANCIERO<i class="fas fa-angle-left right"></i></p>
                </a>
                <ul class="nav nav-treeview">

                  <?php if ($_SESSION['resumen_gasto']==1) {  ?>
                    <!-- RESUMEN DE GASTOS -->
                    <li class="nav-item ver-otros-modulos-1">
                      <a href="../vistas/resumen_gasto.php" class="nav-link pl-2" id="lResumenGastos">
                        <i class="nav-icon fas fa-comments-dollar"></i>
                        <p>Resumen de Gastos</p>
                      </a>
                    </li>
                  <?php  }  ?>

                  <?php if ($_SESSION['pago_trabajador']==1) {  ?>          
                    <!-- PAGOS DE TRABAJADORES -->
                    <li class="nav-item ver-otros-modulos-1  b-radio-3px" id="bloc_PagosTrabajador">
                      <a href="#" class="nav-link pl-2" id="mPagosTrabajador">
                        <i class="fas fa-dollar-sign nav-icon"></i>
                        <p>Pago Trabajador <i class="fas fa-angle-left right"></i> <span class="badge badge-info right">2</span>
                        </p>
                      </a>
                      <ul class="nav nav-treeview">
                        <!-- Obreros  -->
                        <li class="nav-item ">
                          <a href="../vistas/pago_obrero.php" class="nav-link" id="lPagosObrero">
                            <i class="fas fa-users"></i>
                            <p>Obreros</p>
                          </a>
                        </li>
                        <!-- Administradores -->
                        <li class="nav-item ">
                          <a href="../vistas/pago_administrador.php" class="nav-link" id="lPagosAdministrador">
                            <i class="fas fa-briefcase"></i>
                            <p>Administradores</p>
                          </a>
                        </li> 
                      </ul>
                    </li>
                  <?php  }  ?>
                  
                  <?php if ($_SESSION['prestamo']==1) {  ?>
                    <!-- PRESTAMOS -->
                    <li class="nav-item ver-otros-modulos-1">
                      <a href="../vistas/prestamo.php" class="nav-link pl-2" id="lPrestamo">
                        <i class="nav-icon fas fa-university"></i>
                        <p>Prestamos y Créditos</p>
                      </a>
                    </li>
                  <?php  }  ?>
                  
                  <?php if ($_SESSION['estado_financiero']==1) {  ?>
                    <li class="nav-item ver-otros-modulos-1">
                      <a href="../vistas/estado_financiero.php" class="nav-link pl-2" id="lEstadoFinanciero">             
                        <i class="nav-icon fas fa-balance-scale-left"></i>
                        <p>Estd. Financiero y Proyecciones</p>
                      </a>
                    </li>
                  <?php  }  ?>

                  <?php if ($_SESSION['otro_ingreso']==1) {  ?>
                    <li class="nav-item ver-otros-modulos-1">
                      <a href="../vistas/otro_ingreso.php" class="nav-link pl-2" id="lOtroIngreso">             
                        <i class="nav-icon fas fa-hand-holding-usd"></i>
                        <p>Otro ingreso </p>
                      </a>
                    </li>
                  <?php  }  ?>
                  
                  <?php if ($_SESSION['pago_valorizacion']==1) {  ?>
                    <li class="nav-item ver-otros-modulos-1">
                      <a href="../vistas/pago_valorizacion.php" class="nav-link pl-2" id="lPagoValorizacion">             
                        <i class="fas fa-dollar-sign nav-icon"></i>
                        <p>Pago Valorización </p>
                      </a>
                    </li>
                  <?php  }  ?>

                </ul>
              </li>

            </ul>      
          </nav>
          <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <h1>Comprobante</h1>
                </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="../vistas/escritorio.php">Home</a></li>
                    <li class="breadcrumb-item active">Comprobante</li>
                  </ol>
                </div>
              </div>
            </div><!-- /.container-fluid -->
          </div>          
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="container bg-white p-4">
            <!-- title row -->
            <div class="row">
              <div class="col-12">
                <h2 class="page-header">
                  <i class="fas fa-globe"></i> <?php echo $rspta['data']['razon_social']; ?>
                  <!-- <small class="float-right">Date: 2/10/2014</small> -->
                </h2>
              </div>
              <!-- /.col -->
            </div>
            <!-- info row -->
            <div class="row invoice-info">
              <div class="col-sm-8 invoice-col">                
                <address>
                  <!-- <strong><?php echo $rspta['data']['razon_social']; ?></strong><br> -->
                  <?php echo $rspta['data']['direccion']; ?><br>                  
                </address>
              </div>
              <!-- /.col -->
              <div class="col-sm-4 invoice-col text-center border">                
                <address class="m-1">
                  <strong><?php echo $rspta['data']['tipo_comprobante']; ?> Electronica</strong><br>
                  <?php echo $rspta['data']['tipo_documento'] . ': '. $rspta['data']['ruc']; ?><br>
                  <?php echo $rspta['data']['serie_comprobante']; ?><br>                  
                </address>
              </div>
              <div class="col-12"><hr></div>
              
              <!-- /.col -->
              <div class="col-sm-12 invoice-col">
                <table>
                  <tr>
                    <th>Fecha de Emisión</th><td>: <?php echo  date("d/m/Y", strtotime($rspta['data']['fecha_compra'])); ?></td>
                  </tr>
                  <tr>
                    <th>Señor(es)</th><td>: SEVENS INGENIEROS S.A.C</td>
                  </tr>
                  <tr>
                    <th>RUC</th><td>: 20606456892</td>
                  </tr>
                  <tr>
                    <th>Dirección del Cliente</th><td>: PJ. YUNGAY 151 P.J. SANTA ROSA LAMBAYEQUE-CHICLAYOCHICLAYO </td>
                  </tr>
                  <tr>
                    <th>Tipo de Moneda</th><td>: Soles</td>
                  </tr>
                  <tr>
                    <th>Observación</th><td>: <?php echo $rspta['data']['descripcion']; ?></td>
                  </tr>
                </table>
                
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- Table row -->
            <div class="row mt-4">
              <div class="col-12 table-responsive">
                <table class="table table-striped">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Producto</th>
                    <th>Abreviación</th>
                    <th>Cantidad</th>
                    <th>PU</th>
                    <th>IGV</th>
                    <th>VU</th>
                    <th>Dscto</th>
                    <th>Subtotal</th>
                  </tr>
                  </thead>
                  <tbody>             
                    <?php echo $html_producto; ?>                  
                  </tbody>
                </table>
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row">
              <!-- accepted payments column -->
              <div class="col-8">
              <p class="lead"><?php echo $con_letra; ?></p>
                <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                Esta es una representación impresa de la factura electrónica, generada en el Sistema de SUNAT. Puede verificarla utilizando su clave SOL
                </p>
                
                <img src="../dist/img/credit/visa.png" alt="Visa">
                <img src="../dist/img/credit/mastercard.png" alt="Mastercard">
                <img src="../dist/img/credit/american-express.png" alt="American Express">
                <img src="../dist/img/credit/paypal2.png" alt="Paypal">

                
              </div>
              <!-- /.col -->
              <div class="col-4">
                <!-- <p class="lead">Amount Due 2/22/2014</p> -->

                <div class="table-responsive">
                  <table class="table">
                    <tr>
                      <th style="width:50%">Subtotal:</th>
                      <td class="text-right"><?php echo number_format( $rspta['data']['subtotal'], 2, '.',','); ?></td>
                    </tr>
                    <tr>
                      <th>IGV (<?php echo ( ( empty($rspta['data']['val_igv']) ? 0 : floatval($rspta['data']['val_igv']) )  * 100 ) ; ?>%)</th>
                      <td class="text-right"><?php echo number_format( $rspta['data']['igv'], 2, '.',','); ?></td>
                    </tr>
                    <tr>
                      <th>TOTAL:</th>
                      <td class="text-right"><?php echo number_format( $rspta['data']['total'], 2, '.',','); ?></td>
                    </tr>                    
                  </table>
                </div>
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
          </div>
          <!-- /.container -->
        </section>
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->

      <!-- Footer -->
      <footer class="main-footer">
        <!-- <strong id="copyright">Copyright &copy; 2021 - <script>document.getElementById('copyright').appendChild(document.createTextNode(new Date().getFullYear()))</script> <a href="escritorio.php">SevensIngenieros.SAC</a>.</strong>
        Todos los derechos reservados  .
        <div class="float-right d-none d-sm-inline-block"><b>Version</b> 0.0.1</div> -->
      </footer>
      <!-- /.footer -->
    </div>    

    <!-- jQuery -->
    <script src="../plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <!-- <script src="../dist/js/demo.js"></script> -->
    <!-- Funciones Crud -->
    <script type="text/javascript" src="../dist/js/funcion_crud.js"></script>
    <!-- Funciones Generales -->
    <script type="text/javascript" src="../dist/js/funcion_general.js"></script>

    <script>
      window.addEventListener("load", window.print());
    </script>
  </body>
</html>
