
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js"></script> -->

  <title>.: Gestiona :.</title>
  
  <link rel="stylesheet" href="/css/app.css">
</head>
<body class="hold-transition sidebar-mini"> <!--sidebar-collapse">-->
<div class="wrapper" id="app">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
    
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
      </li>
    </ul>
    
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src="./img/logo.png" alt="rt Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Sistema de Gestion</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="./img/user.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">
            {{ Auth::user()->name }}
          </a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          <li class="nav-item">
            <router-link to="/dashboard" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt blue"></i>
              <p>
                Dashboard
              </p>
            </router-link>
          </li>

          <!-- <li class="nav-item">
            <router-link to="/calendario" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt blue"></i>
              <p>
                Calendario
              </p>
            </router-link>
          </li> -->

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-cog yellow"></i>
              <p>
                Mantenimiento
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/productos" class="nav-link">
                  <i class="fab fa-product-hunt nav-icon"></i>
                  <p>Productos</p>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link to="/depositos" class="nav-link">
                  <i class="fas fa-archive nav-icon"></i>
                  <p>Depositos</p>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link to="/bancos" class="nav-link">
                  <i class="fas fa-archive nav-icon"></i>
                  <p>Bancos</p>
                </router-link>
              </li>
              <hr>
              <li class="nav-item">
                <router-link to="/proveedores" class="nav-link">
                  <i class="fas fa-user-alt nav-icon"></i>
                  <p>Proveedores</p>
                </router-link>
              </li>    
              <li class="nav-item">
                <router-link to="/formaspago" class="nav-link">
                  <i class="fas fa-dollar-sign nav-icon"></i>
                  <p>Formas de Pago</p>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link to="/condicionespago" class="nav-link">
                  <i class="fas fa-dollar-sign nav-icon"></i>
                  <p>Condiciones de Pago</p>
                </router-link>
              </li>
              <hr>
              <li class="nav-item">
                <router-link to="/clientes" class="nav-link">
                  <i class="fas fa-user-alt nav-icon"></i>
                  <p>Clientes</p>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link to="/vendedores" class="nav-link">
                  <i class="fas fa-user-alt nav-icon"></i>
                  <p>Distribuidores</p>
                </router-link>
              </li>    
              <li class="nav-item">
                <router-link to="/formasventa" class="nav-link">
                <i class="fas fa-cart-arrow-down nav-icon"></i>
                  <p>Formas de Venta</p>
                </router-link>
              </li>      
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-money-check-alt red"></i>
              <p>
                Compras
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/ordenescompra" class="nav-link">
                  <i class="fas fa-file-invoice nav-icon"></i>
                  <p>Notas de Pedido Proveedor</p>
                </router-link>
              </li>
            </ul>
          </li>          
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-money-check-alt green"></i>
              <p>
                Ventas
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/notaspedido" class="nav-link">
                  <i class="fas fa-file-invoice nav-icon"></i>
                  <p>Notas de Venta Cliente</p>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link to="/recibos" class="nav-link">
                  <i class="fas fa-file-invoice-dollar nav-icon"></i>
                  <p>Recibos</p>
                </router-link>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <router-link to="/profile" class="nav-link">
              <i class="nav-icon fas fa-user-circle orange"></i>
              <p>
                Mi Perfil
              </p>
            </router-link>
          </li>           
          <li class="nav-item">
            <a class="nav-link" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                              document.getElementById('logout-form').submit();">
                <i class="nav-icon fas fa-power-off red"></i>
                <p>
                  {{ __('Salir') }}
                </p>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>            
          </li> 
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <router-view></router-view>
        <vue-progress-bar></vue-progress-bar>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      Anything you want
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2014-2018 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<script src="/js/app.js"></script>
<script src="/js/Chart.min.js"></script>
</body>
</html>
