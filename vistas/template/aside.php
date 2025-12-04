<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="escritorio.php" class="brand-link">
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

    <!-- SidebarSearch Form -->
    <div class="form-inline mt-4">
      <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search" />
        <div class="input-group-append"><button class="btn btn-sidebar"><i class="fas fa-search fa-fw"></i></button></div>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column /*nav-flat*/" data-widget="treeview" role="menu" data-accordion="false">
        <!-- MANUAL DE USUARIO -->
        <li class="nav-item">
          <a href="manual_de_usuario.php" class="nav-link pl-2" id="mManualDeUsuario">
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
            <a href="escritorio.php" class="nav-link pl-2" id="mEscritorio">
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
                <a href="usuario.php" class="nav-link " id="lUsuario">
                  <i class="nav-icon fas fa-users-cog"></i>
                  <p>Usuarios</p>
                </a>
              </li>
              <!-- Permisos de los usuarios del sistema -->
              <li class="nav-item ">
                <a href="permiso.php" class="nav-link" id="lPermiso">
                  <i class="nav-icon fas fa-lock"></i>
                  <p>Permisos</p>
                </a>
              </li>      
            </ul>
          </li>
        <?php  }  ?>

        <?php
          // Rutas del bloque "Recursos"
          $arrar_li_recursos = [ "all_trabajador.php", "all_proveedor.php", "all_maquinas.php",  "materiales.php",
            // SUBMENÚ ACTIVO FIJO
            "activos_fijos.php", "compra_activos_fijos.php", "resumen_activos_fijos_general.php",
            // RUTAS ADICIONALES DEL BLOQUE RECURSO
            "all_calendario.php", "almacen_general.php", "almacen_general_activos.php", "otros.php"
          ];

          // Página actual
          $currentPage = basename($_SERVER['PHP_SELF']);

          // Si la página pertenece al bloque recurso
          $isRecursoActive = in_array($currentPage, $arrar_li_recursos);

          // Submenú: Activos Fijos
          $arrar_activo_fijo = [
            "activos_fijos.php",
            "compra_activos_fijos.php",
            "resumen_activos_fijos_general.php"
          ];

          $isActivoFijoActive = in_array($currentPage, $arrar_activo_fijo);
        ?>

        <?php if ($_SESSION['recurso']==1) { ?>
          <!-- Recursos -->
          <li class="nav-item b-radio-3px <?php echo $isRecursoActive ? 'menu-open bg-color-191f24' : ''; ?>" id="bloc_Recurso">
            <a href="#" class="nav-link pl-2 <?php echo $isRecursoActive ? 'active' : ''; ?>" id="mRecurso">
              <i class="nav-icon fas fa-project-diagram"></i>
              <p>
                Recursos
                <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right">7</span>
              </p>
            </a>

            <ul class="nav nav-treeview">

              <!-- Usuarios del sistema -->
              <li class="nav-item">
                <a href="all_trabajador.php" class="nav-link <?php echo $currentPage == 'all_trabajador.php' ? 'active' : ''; ?>" id="lAllTrabajador">
                  <i class="nav-icon fas fa-users"></i>
                  <p>All-Trabajador</p>
                </a>
              </li>

              <!-- Proveedores de la empresa -->
              <li class="nav-item">
                <a href="all_proveedor.php" class="nav-link <?php echo $currentPage == 'all_proveedor.php' ? 'active' : ''; ?>" id="lAllProveedor">
                  <i class="nav-icon fas fa-truck"></i>
                  <p>All-Proveedor</p>
                </a>
              </li>

              <!-- Maquinas -->
              <li class="nav-item">
                <a href="all_maquinas.php" class="nav-link <?php echo $currentPage == 'all_maquinas.php' ? 'active' : ''; ?>" id="lAllMaquinas">
                  <i class="nav-icon fas fa-tractor"></i>
                  <p>Máquinas-Equipos</p>
                </a>
              </li>

              <!-- Materiales -->
              <li class="nav-item">
                <a href="materiales.php" class="nav-link <?php echo $currentPage == 'materiales.php' ? 'active' : ''; ?>" id="lAllMateriales">
                  <img src="../dist/svg/palana-ico.svg" class="nav-icon" style="width:21px !important;">
                  <p>All-Insumos</p>
                </a>
              </li>

              <!-- Contable (submenú) -->
              <li class="nav-item b-radio-3px <?php echo $isActivoFijoActive ? 'menu-open bg-color-02020280' : ''; ?>" id="bloc_ActivoFijo">
                <a href="#" class="nav-link pl-3 <?php echo $isActivoFijoActive ? 'active bg-primary' : ''; ?>" id="mActivoFijo">
                  <i class="nav-icon fas fa-project-diagram"></i>
                  <p>
                    All-Activos fijos
                    <i class="fas fa-angle-left right"></i>
                    <span class="badge badge-info right">3</span>
                  </p>
                </a>

                <ul class="nav nav-treeview">

                  <li class="nav-item">
                    <a href="activos_fijos.php" class="nav-link pl-4 <?php echo $currentPage == 'activos_fijos.php' ? 'active' : ''; ?>" id="lActivosfijos">
                      <i class="nav-icon fas fa-truck-pickup"></i>
                      <p>Activos fijos</p>
                    </a>
                  </li>

                  <?php if ($_SESSION['compra_activo_fijo'] == 1) { ?>
                    <li class="nav-item">
                      <a href="compra_activos_fijos.php" class="nav-link pl-4 <?php echo $currentPage == 'compra_activos_fijos.php' ? 'active' : ''; ?>" id="lCompraActivoFijo">
                        <i class="nav-icon fas fa-hand-holding-usd"></i>
                        <p>Compras de activos fijos</p>
                      </a>
                    </li>
                  <?php } ?>

                  <?php if ($_SESSION['resumen_activo_fijo_general'] == 1) { ?>
                    <li class="nav-item">
                      <a href="resumen_activos_fijos_general.php" class="nav-link pl-4 <?php echo $currentPage == 'resumen_activos_fijos_general.php' ? 'active' : ''; ?>" id="lResumenActivosFijosGeneral">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>Resumen activos fijos</p>
                      </a>
                    </li>
                  <?php } ?>

                </ul>
              </li>

              <!-- Calendario -->
              <li class="nav-item">
                <a href="all_calendario.php" class="nav-link <?php echo $currentPage == 'all_calendario.php' ? 'active' : ''; ?>" id="lAllCalendario">
                  <i class="nav-icon far fa-calendar-alt"></i>
                  <p>All-Calendario</p>
                </a>
              </li>

              <!-- Almacén -->
              <li class="nav-item">
                <a href="almacen_general.php" class="nav-link <?php echo $currentPage == 'almacen_general.php' ? 'active' : ''; ?>" id="lAlmacenGeneral">
                  <i class="nav-icon fas fa-box-open"></i>
                  <p>Almacenes</p>
                </a>
              </li>

              <!-- Almacén Activos -->
              <li class="nav-item">
                <a href="almacen_general_activos.php" class="nav-link <?php echo $currentPage == 'almacen_general_activos.php' ? 'active' : ''; ?>" id="lAlmacenGeneral_a">
                  <i class="nav-icon fas fa-box-open"></i>
                  <p>Almacenes Activos</p>
                </a>
              </li>

              <!-- Otros -->
              <li class="nav-item">
                <a href="otros.php" class="nav-link <?php echo $currentPage == 'otros.php' ? 'active' : ''; ?>" id="lOtros">
                  <i class="nav-icon fas fa-coins"></i>
                  <p>Otros</p>
                </a>
              </li>

            </ul>
          </li>
        <?php } ?>
   

        <!-- Contable -->
        <li class="nav-item  b-radio-3px" id="bloc_Contable">
          <a href="#" class="nav-link pl-2" id="mContable">
            <i class="nav-icon fa-solid fa-calculator"></i>
            <p>
              Contable
              <i class="fas fa-angle-left right"></i> 
              <span class="badge badge-info right">3</span>
            </p>
          </a>
          <ul class="nav nav-treeview ">  
              
              <?php if ($_SESSION['otra_factura']==1) {  ?>
                <li class="nav-item">
                  <a href="otra_factura.php" class="nav-link pl-2" id="lOtraFactura">
                    <i class="nav-icon fas fa-receipt"></i>
                    <p>Otras Facturas</p>
                  </a>
                </li>
              <?php  }  ?>
              
              <?php if ($_SESSION['resumen_factura']==1) {  ?>
                <li class="nav-item">
                  <a href="resumen_factura.php" class="nav-link pl-2" id="lResumenFacura">            
                    <i class="nav-icon fas fa-poll"></i>
                    <p>Resumen de Facturas</p>
                  </a>
                </li>
              <?php  }  ?>

              <?php if ($_SESSION['resumen_recibo_por_honorario']==1) {  ?>
                <li class="nav-item">
                  <a href="resumen_rh.php" class="nav-link pl-2" id="lResumenRH">            
                    <i class="nav-icon fas fa-poll"></i>
                    <p>Resumen de RH</p>
                  </a>
                </li>
              <?php  }  ?>

            </ul>
        </li>        
        
        <?php if ($_SESSION['papelera']==1) {  ?>
          <li class="nav-item">
            <a href="papelera.php" class="nav-link pl-2" id="mPapelera">
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

        <!-- <li class="nav-header bg-color-2c2c2c">LOGÍSTICA Y ADQUISICIONES</li> -->
        <?php
          // Página actual
          $currentPage = basename($_SERVER['PHP_SELF']);

          // RUTAS DEL BLOQUE LOGÍSTICA Y ADQUISICIONES
          $arrar_logistica = [
            "trabajador_por_proyecto.php",
            
            // compras
            "compra_insumos.php",
            "resumen_insumos.php",
            "resumen_activos_fijos.php",
            "chart_compra_insumo.php",

            // servicios
            "servicio_maquina.php",
            "servicio_equipos.php",

            // subcontrato
            "sub_contrato.php",

            // mano de obra
            "mano_de_obra.php",

            // planillas
            "planillas_seguros.php",

            // otros gastos
            "otro_gasto.php",

            // viáticos
            "transporte.php",
            "hospedaje.php",
            "pension.php",
            "comidas_extras.php",

            // resumen general
            "resumen_general.php"
          ];

          // Detecta si el bloque Logística debe estar abierto
          $isLogisticaActive = in_array($currentPage, $arrar_logistica);

          // SUBMENÚ COMPRAS
          $arrar_compras = [
            "compra_insumos.php",
            "resumen_insumos.php",
            "resumen_activos_fijos.php",
            "chart_compra_insumo.php"
          ];
          $isComprasActive = in_array($currentPage, $arrar_compras);

          // SUBMENÚ VIÁTICOS
          $arrar_viaticos = [
            "transporte.php",
            "hospedaje.php",
            "pension.php",
            "comidas_extras.php"
          ];
          $isViaticosActive = in_array($currentPage, $arrar_viaticos);

          // SUBMENÚ VIÁTICOS > COMIDAS
          $arrar_viaticos_comidas = [
            "pension.php",
            "comidas_extras.php"
          ];
          $isComidasActive = in_array($currentPage, $arrar_viaticos_comidas);
        ?>
        
        <!-- LOGÍSTICA Y ADQUISICIONES -->      
        <li class="nav-item ver-otros-modulos-1 <?php echo $isLogisticaActive ? 'menu-open' : ''; ?>" id="bloc_LogisticaAdquisiciones">
          <a href="#" class="nav-link bg-color-2c2c2c <?php echo $isLogisticaActive ? 'active' : ''; ?>" id="mLogisticaAdquisiciones" style="padding-left: 7px;">
            <i class="nav-icon far fa-circle"></i>
            <p class="font-size-14px">LOGÍSTICA Y ADQUISICIONES <i class="fas fa-angle-left right"></i></p>
          </a>

          <ul class="nav nav-treeview">

            <!-- TRABAJADORES -->
            <?php if ($_SESSION['trabajador']==1) { ?>
              <li class="nav-item ver-otros-modulos-1">
                <a href="trabajador_por_proyecto.php" class="nav-link pl-2 <?php echo $currentPage=='trabajador_por_proyecto.php' ? 'active' : ''; ?>" id="lTrabajador">
                  <img src="../dist/svg/constructor-ico.svg" class="nav-icon" style="width: 21px !important;">
                  <p>Trabajadores</p>
                </a>
              </li>
            <?php } ?>

            <!-- COMPRAS -->
            <?php if ($_SESSION['compra_insumos']==1) { ?>   
              <li class="nav-item ver-otros-modulos-1 b-radio-3px <?php echo $isComprasActive ? 'menu-open bg-color-191f24' : ''; ?>" id="bloc_Compras">
                <a href="#" class="nav-link pl-2 <?php echo $isComprasActive ? 'active bg-primary' : ''; ?>" id="mCompra">
                  <i class="fas fa-shopping-cart nav-icon"></i>
                  <p>Compras <i class="fas fa-angle-left right"></i> <span class="badge badge-info right">4</span></p>
                </a>

                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="compra_insumos.php" class="nav-link <?php echo $currentPage=='compra_insumos.php'?'active':''; ?>">
                      <i class="nav-icon fas fa-cart-plus"></i>
                      <p>Compras</p>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="resumen_insumos.php" class="nav-link <?php echo $currentPage=='resumen_insumos.php'?'active':''; ?>">
                      <i class="nav-icon fas fa-tasks"></i>
                      <p>Resumen de insumos</p>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="resumen_activos_fijos.php" class="nav-link <?php echo $currentPage=='resumen_activos_fijos.php'?'active':''; ?>">
                      <i class="nav-icon fas fa-tasks"></i>
                      <p>Resumen de Activos Fijos</p>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="chart_compra_insumo.php" class="nav-link <?php echo $currentPage=='chart_compra_insumo.php'?'active':''; ?>">
                      <i class="nav-icon fas fa-chart-line"></i>
                      <p>Gráficos</p>
                    </a>
                  </li>
                </ul>
              </li>
            <?php } ?>

            <!-- SERVICIO MAQUINA -->
            <?php if ($_SESSION['servicio_maquina']==1) { ?>
              <li class="nav-item ver-otros-modulos-1">
                <a href="servicio_maquina.php" class="nav-link pl-2 <?php echo $currentPage=='servicio_maquina.php'?'active':''; ?>">
                  <img src="../dist/svg/excabadora-ico.svg" class="nav-icon" style="width: 21px !important;">
                  <p>Servicio - Máquina</p>
                </a>
              </li>
            <?php } ?>

            <!-- SERVICIO EQUIPOS -->
            <?php if ($_SESSION['servicio_equipo']==1) { ?>
              <li class="nav-item ver-otros-modulos-1">
                <a href="servicio_equipos.php" class="nav-link pl-2 <?php echo $currentPage=='servicio_equipos.php'?'active':''; ?>">
                  <img src="../dist/svg/estacion-total-ico.svg" class="nav-icon" style="width: 21px !important;">
                  <p>Servicio - Equipos</p>
                </a>
              </li>
            <?php } ?>

            <!-- SUB CONTRATO -->
            <?php if ($_SESSION['subcontrato']==1) { ?>
              <li class="nav-item ver-otros-modulos-1">
                <a href="sub_contrato.php" class="nav-link pl-2 <?php echo $currentPage=='sub_contrato.php'?'active':''; ?>">
                  <i class="nav-icon fas fa-hands-helping"></i>
                  <p>Sub Contrato</p>
                </a>
              </li>
            <?php } ?>

            <!-- MANO DE OBRA -->
            <?php if ($_SESSION['mano_obra']==1) { ?>
              <li class="nav-item ver-otros-modulos-1">
                <a href="mano_de_obra.php" class="nav-link pl-2 <?php echo $currentPage=='mano_de_obra.php'?'active':''; ?>">
                  <i class="nav-icon fa-solid fa-person-digging"></i>
                  <p>Mano de Obra</p>
                </a>
              </li>
            <?php } ?>

            <!-- PLANILLAS Y SEGUROS -->
            <?php if ($_SESSION['planilla_seguro']==1) { ?>
              <li class="nav-item ver-otros-modulos-1">
                <a href="planillas_seguros.php" class="nav-link pl-2 <?php echo $currentPage=='planillas_seguros.php'?'active':''; ?>">
                  <img src="../dist/svg/planilla-seguro-ico.svg" class="nav-icon" style="width:21px!important;">
                  <p>Planillas y seguros</p>
                </a>
              </li>
            <?php } ?>

            <!-- OTROS GASTOS -->
            <?php if ($_SESSION['otro_gasto']==1) { ?>
              <li class="nav-item ver-otros-modulos-1">
                <a href="otro_gasto.php" class="nav-link pl-2 <?php echo $currentPage=='otro_gasto.php'?'active':''; ?>">
                  <i class="nav-icon fas fa-network-wired"></i>
                  <p>Otros Gastos</p>
                </a>
              </li>
            <?php } ?>

            <!-- VIÁTICOS -->
            <?php if ($_SESSION['viatico']==1) { ?>
              <li class="nav-item ver-otros-modulos-1 <?php echo $isViaticosActive ? 'menu-open' : ''; ?>" id="bloc_Viaticos">
                <a href="#" class="nav-link pl-2 <?php echo $isViaticosActive ? 'active' : ''; ?>" id="mViatico">
                  <i class="nav-icon fas fa-plane"></i>
                  <p>Viáticos <i class="right fas fa-angle-left"></i> <span class="badge badge-info right">3</span></p>
                </a>

                <ul class="nav nav-treeview">

                  <li class="nav-item">
                    <a href="transporte.php" class="nav-link <?php echo $currentPage=='transporte.php'?'active':''; ?>">
                      <i class="fas fa-shuttle-van nav-icon"></i>
                      <p>Transporte</p>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="hospedaje.php" class="nav-link <?php echo $currentPage=='hospedaje.php'?'active':''; ?>">
                      <i class="fas fa-hotel nav-icon"></i>
                      <p>Hospedaje</p>
                    </a>
                  </li>

                  <!-- COMIDAS -->
                  <li class="nav-item b-radio-3px <?php echo $isComidasActive ? 'menu-open' : ''; ?>" id="sub_bloc_comidas">
                    <a href="#" class="nav-link <?php echo $isComidasActive ? 'active' : ''; ?>" id="sub_mComidas">
                      <i class="fas fa-fish nav-icon"></i>
                      <p>Comida <i class="right fas fa-angle-left"></i></p>
                    </a>

                    <ul class="nav nav-treeview">
                      <li class="nav-item">
                        <a href="pension.php" class="nav-link <?php echo $currentPage=='pension.php'?'active':''; ?>">
                          <i class="fas fa-utensils nav-icon"></i>
                          <p>Pensión</p>
                        </a>
                      </li>

                      <li class="nav-item">
                        <a href="comidas_extras.php" class="nav-link <?php echo $currentPage=='comidas_extras.php'?'active':''; ?>">
                          <i class="fas fa-drumstick-bite nav-icon"></i>
                          <p>Comidas - extras</p>
                        </a>
                      </li>
                    </ul>
                  </li>

                </ul>
              </li>
            <?php } ?>

            <!-- RESUMEN GENERAL -->
            <?php if ($_SESSION['resumen_general']==1) { ?>
              <li class="nav-item ver-otros-modulos-1">
                <a href="resumen_general.php" class="nav-link pl-2 <?php echo $currentPage=='resumen_general.php'?'active':''; ?>">
                  <i class="nav-icon fas fa-list-ul"></i>
                  <p>Resumen general</p>
                </a>
              </li>
            <?php } ?>

          </ul>
        </li>
      
        
        <!-- <li class="nav-header bg-color-2c2c2c">TÉCNICO</li>  -->
         
        <li class="nav-item ver-otros-modulos-1" id="bloc_Tecnico">
          <a href="#" class="nav-link bg-color-2c2c2c" id="mTecnico" style="padding-left: 7px;">
            <i class="nav-icon far fa-circle"></i>
            <p class="font-size-14px">TÉCNICO <i class="fas fa-angle-left right"></i><span class="badge badge-info right">8</span></p>
          </a>
          <ul class="nav nav-treeview">
            <?php if ($_SESSION['valorizacion']==1) {  ?>
              <!-- ALMACEN -->
              <li class="nav-item ver-otros-modulos-1">
                <a href="almacen.php" class="nav-link pl-2" id="lAlmacen">
                  <i class="nav-icon fas fa-box-open"></i>
                  <p>Almacen Insumos </p>
                </a>
              </li>              
            <?php  }  ?>
            <?php if ($_SESSION['valorizacion']==1) {  ?>
              <!-- ALMACEN -->
              <li class="nav-item ver-otros-modulos-1">
                <a href="almacen_activos.php" class="nav-link pl-2" id="lAlmacen_act">
                  <i class="nav-icon fas fa-box-open"></i>
                  <p>Almacen Activos </p>
                </a>
              </li>              
            <?php  }  ?>
            <?php if ($_SESSION['valorizacion']==1) {  ?>
              <!-- E.P.P -->
              <li class="nav-item ver-otros-modulos-1">
                <a href="epp.php" class="nav-link pl-2" id="lEpp">
                <i class="nav-icon fas fa-hard-hat"></i>
                  <p>E.P.P </p>
                </a>
              </li>
            <?php  }  ?>
            <?php if ($_SESSION['valorizacion']==1) {  ?>
              <!-- VALORIZACIONES -->
              <li class="nav-item ver-otros-modulos-1">
                <a href="valorizacion.php" class="nav-link pl-2" id="lValorizacion">
                  <i class="nav-icon far fa-file-alt"></i>
                  <p>Valorizaciones </p>
                </a>
              </li>
            <?php  }  ?>
            
            <?php if ($_SESSION['grafico_valorizacion']==1) {  ?>
            <!-- graficos insumos -->
            <li class="nav-item ">
              <a href="chart_valorizacion.php" class="nav-link pl-2" id="lChartValorizacion">
                <i class="nav-icon fas fa-chart-line"></i> <p>Gráficos</p>
              </a>
            </li> 
            <?php  }  ?>

            <?php if ($_SESSION['asistencia_obrero']==1) {  ?>
              <!-- REGISTRO DE ASISTENCIA -->
              <li class="nav-item ver-otros-modulos-1">
                <a href="asistencia_obrero.php" class="nav-link pl-2" id="lAsistencia">
                  <i class="fa-regular fa-square-check nav-icon"></i>
                  <p>Asistencia del obrero </p>
                </a>
              </li>
            <?php  }  ?>
            <?php if ($_SESSION['asistencia_obrero']==1) {  ?>
              <!-- REGISTRO DE ASISTENCIA -->
              <li class="nav-item ver-otros-modulos-1">
                <a href="formatos_varios.php" class="nav-link pl-2" id="lformatos_varios">
                  <i class="fa-regular fa-square-check nav-icon"></i>
                  <p>Formatos varios </p>
                </a>
              </li>
            <?php  }  ?>

            <?php if ($_SESSION['calendario']==1) {  ?>
              <!-- CALENDARIO -->       
              <li class="nav-item ver-otros-modulos-1">
                <a href="calendario.php" class="nav-link pl-2" id="lCalendario">
                  <i class="nav-icon far fa-calendar-alt"></i>
                  <p>Calendario </p>
                </a>
              </li>
            <?php  }  ?>

            <?php if ($_SESSION['plano_otro']==1) {  ?>
              <!-- PLANOS Y OTROS -->       
              <li class="nav-item ver-otros-modulos-1">
                <a href="plano_otro.php" class="nav-link pl-2" id="lPlanoOtro">
                  <i class="nav-icon fas fa-map-marked-alt"></i>
                  <p>Planos y otros </p>
                </a>
              </li>
            <?php  }  ?>            

            <?php if ($_SESSION['clasificacion_grupo']==1) {  ?>
            <!-- CONCRETO, CEMENTO Y AGREGADO -->       
            <li class="nav-item ver-otros-modulos-1">
              <a href="clasificacion_de_grupo.php" class="nav-link pl-2" id="lConcretoAgregado">
                <i class="nav-icon fas fa-project-diagram"></i>
                <p>Clasificación de grupo</p>
              </a>
            </li>
            <?php  }  ?>

            <?php if ($_SESSION['valorizacion_concreto']==1) {  ?>
            <!-- CONCRETO -->       
            <li class="nav-item ver-otros-modulos-1">
              <a href="valorizacion_concreto.php" class="nav-link pl-2" id="lConcretoValorizacion">
                <!-- <i class="nav-icon fas fa-dumpster"></i> -->
                <img src="../dist/svg/concreto-agregado-ico.svg" class="nav-icon" alt="" style="width: 21px !important;" >
                <p>Valorización concreto</p>
              </a>
            </li>
            <?php  }  ?>
            
            <?php if ($_SESSION['valorizacion_concreto']==1) {  ?>
            <!-- CONCRETO -->       
            <li class="nav-item ver-otros-modulos-1">
              <a href="concreto_control.php" class="nav-link pl-2" id="lConcretoControlconcreto">
                <!-- <i class="nav-icon fas fa-dumpster"></i> -->
                <img src="../dist/svg/concreto-agregado-ico.svg" class="nav-icon" alt="" style="width: 21px !important;" >
                <p>Concreto control</p>
              </a>
            </li>
            <?php  }  ?>

            <?php if ($_SESSION['valorizacion_fierro']==1) {  ?>
            <!-- FIERROS -->       
            <li class="nav-item ver-otros-modulos-1">
              <a href="valorizacion_fierro.php" class="nav-link pl-2" id="lFierroValorizacion">
                <!-- <i class="nav-icon fas fa-dumpster"></i> -->
                <img src="../dist/svg/fierro-ico.svg" class="nav-icon" alt="" style="width: 21px !important;" >
                <p>Valorización Fierros</p>
              </a>
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
            <!-- agregando nuevos modulos -->
            <?php if ($_SESSION['otra_factura_proyecto']==1) {  ?>
                <li class="nav-item">
                  <a href="otra_factura_proy.php" class="nav-link pl-2" id="lOtraFacturaProy">
                    <i class="nav-icon fas fa-receipt"></i>
                    <p>Otras Facturas</p>
                  </a>
                </li>
              <?php  }  ?>
              
              <?php if ($_SESSION['resumen_factura_proyecto']==1) {  ?>
                <li class="nav-item">
                  <a href="resumen_factura_proy.php" class="nav-link pl-2" id="lResumenFacuraProy">            
                    <i class="nav-icon fas fa-poll"></i>
                    <p>Resumen de Facturas</p>
                  </a>
                </li>
              <?php  }  ?>

            <!-- F I N -->

            <?php if ($_SESSION['resumen_gasto']==1) {  ?>
              <!-- RESUMEN DE GASTOS -->
              <li class="nav-item ver-otros-modulos-1">
                <a href="resumen_gasto.php" class="nav-link pl-2" id="lResumenGastos">
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
                    <a href="pago_obrero.php" class="nav-link" id="lPagosObrero">
                      <i class="fas fa-users"></i>
                      <p>Obreros</p>
                    </a>
                  </li>
                  <!-- Administradores -->
                  <li class="nav-item ">
                    <a href="pago_administrador.php" class="nav-link" id="lPagosAdministrador">
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
                <a href="prestamo.php" class="nav-link pl-2" id="lPrestamo">
                  <i class="nav-icon fas fa-university"></i>
                  <p>Prestamos y Créditos</p>
                </a>
              </li>
            <?php  }  ?>
            
            <?php if ($_SESSION['estado_financiero']==1) {  ?>
              <li class="nav-item ver-otros-modulos-1">
                <a href="estado_financiero.php" class="nav-link pl-2" id="lEstadoFinanciero">             
                  <i class="nav-icon fas fa-balance-scale-left"></i>
                  <p>Estd. Financiero y Proyecciones</p>
                </a>
              </li>
            <?php  }  ?>

            <?php if ($_SESSION['otro_ingreso']==1) {  ?>
              <li class="nav-item ver-otros-modulos-1">
                <a href="otro_ingreso.php" class="nav-link pl-2" id="lOtroIngreso">             
                  <i class="nav-icon fas fa-hand-holding-usd"></i>
                  <p>Otro ingreso </p>
                </a>
              </li>
            <?php  }  ?>
            
            <?php if ($_SESSION['pago_valorizacion']==1) {  ?>
              <li class="nav-item ver-otros-modulos-1">
                <a href="pago_valorizacion.php" class="nav-link pl-2" id="lPagoValorizacion">             
                  <i class="fas fa-dollar-sign nav-icon"></i>
                  <p>Pago Valorización </p>
                </a>
              </li>
            <?php  }  ?>
            
            <?php if ($_SESSION['pago_valorizacion']==1) {  ?>
              <li class="nav-item ver-otros-modulos-1">
                <a href="pago_impuesto.php" class="nav-link pl-2" id="lrecibo">             
                  <!-- <i class="fa-regular fa-registered nav-icon"></i> -->
                  <i class="fa-solid fa-i nav-icon"></i>
                  <p>Pago Impuestos </p>
                </a>
              </li>
            <?php  }  ?>

            <?php if ($_SESSION['pago_valorizacion']==1) {  ?>
              <li class="nav-item ver-otros-modulos-1">
                <a href="recibo.php" class="nav-link pl-2" id="lrecibo">             
                  <i class="fa-regular fa-registered nav-icon"></i>
                  <p>Recibos Honorario </p>
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
