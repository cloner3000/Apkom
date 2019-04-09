<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Aplikasi Kompetensi Mahasiswa</title>
  <link rel="stylesheet" href="{{asset('css/app.css')}}">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper" id="app">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand bg-primary navbar-light border-bottom">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
      </li>
    </ul>
    <!-- SEARCH FORM -->
    <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" @keyup="searching" v-model="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fa fa-search"></i>
          </button>
        </div>
      </div>
    </form>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-light-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="{{asset('img/logo.png')}}" alt="Logo UKDC" class="brand-image img-circle"
           style="opacity: .8">
      <span class="brand-text font-weight-light text-primary">Aplikasi SKPI</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
        <img src="{{asset('img/user.jpg')}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block text-bold">{{  Str::words(Auth::user()->name , 2,'') }}</a>
          <a href="#" class="d-block">{{ Auth::user()->role }} </a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <router-link to="/dashboard" active-class="active" class="nav-link" exact>
              <i class="nav-icon fas fa-tachometer-alt text-belizehole"></i>
              <p>
                Dashboard
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/skpi" active-class="active" class="nav-link" exact>
              <i class="nav-icon fas fa-id-card text-darkblue"></i>
              <p>
                SKPI
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/mahasiswa-achievement" active-class="active" class="nav-link" exact>
              <i class="nav-icon fas fa-award text-orange"></i>
              <p>
                Mahasiswa Achievement
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/mahasiswa" active-class="active" class="nav-link" exact>
              <i class="nav-icon fas fa-user-graduate text-black"></i>
              <p>
                Mahasiswa
              </p>
            </router-link>
          </li>        
          <li class="nav-item">
            <router-link to="/jurusan" active-class="active" class="nav-link" exact>
              <i class="nav-icon fas fa-graduation-cap text-teal"></i>
              <p>
                Jurusan
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/bidang-kompetensi" active-class="active" class="nav-link" exact>
              <i class="nav-icon fas fa-atom text-blue"></i>
              <p>
                Bidang Kompetensi
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/account" active-class="active" class="nav-link" exact>
              <i class="nav-icon fas fa-user text-guava"></i>
              <p>
                Account
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/backup" active-class="active" class="nav-link" exact>
              <i class="nav-icon fas fa-database text-grey"></i>
              <p>
                Backup Database
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
              <i class="nav-icon fas fa-power-off text-red"></i>
              <p>{{ __('Logout') }}</p>
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
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Manage Account</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Manage Account</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <router-view></router-view>
          <vue-progress-bar></vue-progress-bar>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- Default to the left -->
    Copyright &copy; 2019 <a href="http://ukdc.ac.id/">Universitas Katolik Darma Cendika</a>. All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<script src="{{asset('js/app.js')}}"></script>
</body>
</html>
