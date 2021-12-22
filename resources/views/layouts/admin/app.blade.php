<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ env('SCHOOL_NAME', 'SMAIT As-Syifa Wanareja') . ' | ' . $title }}</title>

    <link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/css/adminlte.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables & Plugins -->
    <link rel="stylesheet" href="/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="/plugins/summernote/summernote-bs4.min.css">
    <link rel="stylesheet" href="/css/style-admin.css">
</head>

<body class="hold-transition sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('dashboard') }}" class="nav-link">CBT</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="javascript:void(0)" role="button">
                        <img src="/img/default-user.png" class="img-circle" alt="User Image" width="30px">
                        <b>{{ Auth::user()->name }}</b>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ route('admin.dashboard') }}" class="brand-link">
                <img src="/img/institution-logo.png" alt="{{ env('SCHOOL_NAME', 'SMAIT As-Syifa Wanareja') }}"
                    class="brand-image">
                <span class="brand-text font-weight-light">&nbsp;</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link @if (Request::segment(2) == 'admin' && Request::segment(3) == null) active
                                @endif">
                                <i class="nav-icon fas fa-home"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-header">Manage</li>
                        <li class="nav-item">
                            <a href="{{ route('admin.manage.users') }}"
                                class="nav-link @if (Request::segment(4) == 'users') active @endif">
                                <i class="nav-icon fas fa-users"></i>
                                <b>Users</b>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.manage.tests') }}"
                                class="nav-link @if (Request::segment(4) == 'tests') active @endif">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <b>Tests</b>
                            </a>
                        </li>
                        <li class="nav-header">Other</li>
                        <li class="nav-item">
                            <a href="{{ route('admin.other.files') }}" class="nav-link @if (Request::segment(4) == 'files') active @endif">
                                <i class="nav-icon fas fa-folder-open"></i>
                                <b>Files</b>
                            </a>
                        </li>
                        <li class="nav-header">Users</li>
                        <li class="nav-item">
                            <a href="#" class="nav-link btn-logout">
                                <i class="nav-icon fas fa-sign-out-alt"></i>
                                <b>Log out</b>
                            </a>
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
            @yield('body')
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                @if (Auth::check())
                <small class="text-dark">
                    <b>{{ Auth::user()->name }} |
                        <a href="#" class="btn-logout">Log out</a>
                    </b>
                </small>
                @endif
            </div>
            &nbsp;
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="/js/demo.js"></script>
    <!-- DataTables & Plugins -->
    <script src="/js/redirect-mobile.js"></script>
    <script src="/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="/plugins/jszip/jszip.min.js"></script>
    <script src="/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <!-- InputMask -->
    <script src="/plugins/moment/moment.min.js"></script>
    <script src="/plugins/inputmask/jquery.inputmask.min.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Select2 -->
    <script src="/plugins/select2/js/select2.full.min.js"></script>
    <!-- Summernote -->
    <script src="https://cdn.tiny.cloud/1/z6u87t8quz1nw4suo2g7d1m82297o69ut5x4qvkani1g69hj/tinymce/5/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script src="/js/admin/script.js"></script>
</body>

</html>
