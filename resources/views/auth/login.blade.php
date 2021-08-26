@extends('layouts.app')

@section('body')
<div class="content-wrapper">
    <section class="container content-header">
        <div class="container-fluid">
            <div class="row mb-1">
                <div class="col-sm-12">
                    <h4 class="d-inline mr-2">
                        {{ env('SCHOOL_NAME', 'SMAIT As-Syifa Wanareja') }}
                    </h4>
                    <small class="d-inline text-gray">Simulasi UTBK</small>
                </div>
            </div>
    </section>

    <section class="container content mt-5">
        <div class="row">
            <div class="col-4 offset-4">
                <div class="card" style="border: 3px #28a745 solid;">
                    <div class="login-logo">
                        <b class="font-weight-bold">User Login</b>
                    </div>
                    <div id="row alert">
                        <div class="col-11 ml-3">
                            @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                @foreach ($errors->all() as $error)
                                {{ $error }} <br>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-body login-card-body">
                        <p class="login-box-msg">Masukkan Username dan Password</p>

                        <form action="{{ route('login') }}" method="post" id="login-form">
                            @csrf
                            <div class="input-group mb-3">
                                <input type="text" id="username" name="username" class="form-control"
                                    placeholder="Username" autofocus>
                                <div class=" input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" id="password" name="password" class="form-control"
                                    placeholder="Password" autocomplete="off">
                                <div class="input-group-append" id="show-password" data-placement="right"
                                    title="Show Password">
                                    <div class="input-group-text">
                                        <span class="fas fa-eye"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-8">
                                    <div class="icheck-primary">
                                        <input type="checkbox" id="remember_me" name="remember">
                                        <label for="remember_me">
                                            &nbsp; Remember Me
                                        </label>
                                    </div>
                                </div>
                                <!-- /.col -->
                                <div class="col-4">
                                    <button type="submit" name="login" class="btn btn-primary btn-block">Sign
                                        In</button>
                                </div>
                                <!-- /.col -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </section>
</div>
@endsection
