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
    <div class="form-inline">
      <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search" />
        <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        
        <?php if ($_SESSION['escritorio']==1) {  ?>
          <!-- ESCRITORIO -->
          <li class="nav-item">
            <a href="escritorio.php" class="nav-link" id="mEscritorio">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Escritorio
                <span class="right badge badge-danger">New</span>
              </p>
            </a>
          </li>
        <?php  }  ?>

        <?php if ($_SESSION['acceso']==1) {  ?>
          <!-- ACCESOS -->
          <li class="nav-item" id="bloc_Accesos">
            <a href="#" class="nav-link" id="mAccesos">
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

        <?php if ($_SESSION['recurso']==1) {  ?>
          <!-- Recursos -->
          <li class="nav-item" id="bloc_Recurso">
            <a href="#" class="nav-link" id="mRecurso">
              <i class="nav-icon fas fa-project-diagram"></i>
              <p>
                Recursos
                <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right">2</span>
              </p>
            </a>
            <ul class="nav nav-treeview ">
              <!-- Usuarios del sistema -->
              <li class="nav-item ">
                <a href="permiso.php" class="nav-link" id="lAllTrabajador">
                  <i class="nav-icon fas fa-users"></i>
                  <p>All-Trabajador</p>
                </a>
              </li>
              <!-- Permisos de los usuarios del sistema -->
              <li class="nav-item ">
                <a href="permiso.php" class="nav-link" id="lAllProveedor">
                  <i class="nav-icon fas fa-truck"></i>
                  <p>All-Proveedor</p>
                </a>
              </li>  
            </ul>
          </li>
        <?php  }  ?>

      </ul>
      <!-- cargando -->
      <ul class="nav nav-pills nav-sidebar flex-column" id="ver-otros-modulos-2" style="display: none !important;">      
        <li class="nav-header">Modulos</li>        
        <li class="nav-item">
          <a href="#" class="nav-link" >
          <i class="fas fa-spinner fa-pulse "></i>
            <p>
              Cargando...
            </p>
          </a>
        </li>
      </ul>

      <ul class="nav nav-pills nav-sidebar flex-column" id="ver-otros-modulos-1" >
        <!-- OTROS -->
        <li class="nav-header">Modulos</li>

        <?php if ($_SESSION['trabajador']==1) {  ?>
          <!-- TRABAJADORES -->
          <li class="nav-item">
            <a href="trabajador.php" class="nav-link" id="mTrabajador">
              <!-- <i class="nav-icon fas fa-hard-hat"></i> -->
              <img src="../dist/svg/constructor-ico.svg " class="nav-icon" alt="" style="width: 21px !important;" >
              <p>
                Trabajadores
              </p>
            </a>
          </li>
        <?php  }  ?>

        <?php if ($_SESSION['asistencia_trabajador']==1) {  ?>
          <!-- REGISTRO DE ASISTENCIA -->
          <li class="nav-item">
            <a href="registro_sistencia.php" class="nav-link" id="mAsistencia">
              <i class="fas fa-clipboard-list nav-icon"></i>
              <p>
                Asistencia del trabajador
              </p>
            </a>
          </li>
        <?php  }  ?> 

        <?php if ($_SESSION['pago_trabajador']==1) {  ?>
          <!-- PAGOSD E TRABAJADORES -->
          <li class="nav-item">
            <a href="pago_trabajador.php" class="nav-link" id="mPagosTrabajador">
              <i class="fas fa-dollar-sign nav-icon"></i>
              <p>
                  Pago del Trabajador
              </p>
            </a>
          </li>
        <?php  }  ?>       

        <?php if ($_SESSION['proveedor']==1) {  ?>
          <!-- PROVEEDORES -->
          <li class="nav-item">
            <a href="proveedor.php" class="nav-link" id="mProveedor">
              <i class="fas fa-users nav-icon"></i>
              <p>
                Proveedores
              </p>
            </a>
          </li>
        <?php  }  ?>        

        <?php if ($_SESSION['compra']==1) {  ?>   
          <!-- COMPRAS -->      
          <li class="nav-item">
            <a href="compra.php" class="nav-link" id="mCompra">
              <i class="fas fa-shopping-cart nav-icon"></i>
              <p>
                Compras
                <span class="badge badge-info right">2</span>
              </p>
            </a>
          </li>
        <?php  }  ?>

        <?php if ($_SESSION['servicio']==1) {  ?>  
          <!-- SERVICIO -->       
          <li class="nav-item">
            <a href="servicio.php" class="nav-link" id="mServicio">
              <i class="nav-icon far fa-handshake"></i>
              <p>
                Servicios
                <span class="badge badge-info right">2</span>
              </p>
            </a>
          </li>
        <?php  }  ?>

      </ul>

     
       
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
