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
    <link rel="stylesheet" href="/plugins/datatables-bs4/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>

<body class="hold-transition sidebar-collapse" @if (Request::segment(2)=='do-test' && $result !=[]) onload="load()"
    @endif>

    <input type="hidden" id="app_mode" value="{{ env('APP_MODE', 'development') }}">

    <div class="d-none" id="closer"></div>
    <div class="wrapper">
        <div class="main-sidebar brand-link"></div>
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark bg-success">
            <!-- Left navbar links -->
            <div class="container">
                <span class="mr-2" style="font-size: 20px;">
                    <b>
                        <a href="/" class="text-white">{{ env('SCHOOL_NAME', 'SMAIT As-Syifa Wanareja') }}</a>
                    </b>
                </span>

                <!-- Right navbar links -->
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link btn btn-success" id="timestamp" role="button"></a>
                    </li>
                    @if (Auth::check())
                    <li class="nav-item dropdown">
                        <a class="nav-link btn btn-success" data-toggle="dropdown" role="button">
                            <img src="/img/default-user.png" class="user-image" alt="User Image" width="30px">
                            {{ (Auth::user()->name) }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            <div class="dropdown-item text-center bg-success">
                                <b>{{ (Auth::user()->name) }}</b>
                                <br>
                                <i>{{ Auth::user()->class }}</i>
                            </div>
                            <div class="row">
                                <div class="col-md-6 p-3 float-left">
                                    <button class="btn btn-sm btn-primary float-left" data-toggle="modal"
                                        data-target="#modal-password">Password</button>
                                </div>
                                <div class="col-md-6 p-3 float-right">
                                    <button class="btn btn-sm btn-danger float-right btn-logout" role="button">Log
                                        out</button>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endif
                </ul>
            </div>
        </nav>
        <!-- /.navbar -->

        @include('layouts.modal')

        @yield('body')

        <div style="background-color: #f4f6f9"><br><br><br><br><br><br></div>

        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                @if (Auth::check())
                <small class="text-dark">
                    {{ (Auth::user()->name) }} |
                    <a href="#" class="btn-logout">
                        <b>Log out</b>
                    </a>
                </small>
                @else
                <a href="/"><b>Log In Admin</b></a>
                @endif
            </div>
            @if (Auth::check() && Auth::user()->is_admin == 1)
            <a href="{{ route('admin.dashboard') }}">
                <b>Admin Panel</b>
            </a>
            @else
            &nbsp;
            @endif
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/js/script.js"></script>
    <script>
        var user_id = '<?= Auth::user()->id ?? "" ?>';

    </script>
</body>

</html>
