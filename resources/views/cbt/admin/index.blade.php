@extends('layouts.admin.app')

@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Dashboard</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-gradient-info">
                    <div class="inner">
                        <h3>{{ $counts->users }}</h3>
                        <p>Murid</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-fw fa-user-graduate"></i>
                    </div>
                    <a href="{{ route('admin.manage.users') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-gradient-success">
                    <div class="inner">
                        <h3>{{ $counts->tests }}</h3>

                        <p>Tes</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-fw fa-file-alt"></i>
                    </div>
                    <a href="{{ route('admin.manage.tests') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-gradient-warning">
                    <div class="inner">
                        <h3>{{ $counts->admins }}</h3>

                        <p>Administrator</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-fw fa-users-cog"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-gradient-danger">
                    <div class="inner">
                        <h3>{{ $counts->super_admins }}</h3>
                        <p>Super Administrator</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-fw fa-user-shield"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
    </div>

</section>
<!-- /.content -->
@endsection
