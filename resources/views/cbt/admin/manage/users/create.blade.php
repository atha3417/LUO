@extends('layouts.admin.app')

@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create New User</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">Manage</li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.manage.users') }}">Users</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div id="alert">
            @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                @foreach ($errors->all() as $error)
                {{ $error }} <br>
                @endforeach
            </div>
            @endif
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form action="{{ route('admin.manage.users.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" id="username" name="username"
                                        placeholder="Enter Username" value="{{ old('username') }}" required autofocus>
                                </div>
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Enter Name" value="{{ old('name') }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="class">Class</label>
                                    <input type="text" class="form-control" id="class" name="class"
                                        placeholder="Enter Class" value="{{ old('class') }}" required>
                                </div>
                                @if (Auth::user()->super_admin)
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="is_admin">Is Admin</label>
                                        <select name="is_admin" id="is_admin" class="custom-select">
                                            <option value="0" @if (old('is_admin')=='0' ) selected @endif>
                                                No
                                            </option>
                                            <option value="1" @if (old('is_admin')=='1' ) selected @endif>Yes</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="is_super_admin">Is Super Admin</label>
                                        <select name="is_super_admin" id="is_super_admin" class="custom-select">
                                            <option value="0" @if (old('super_admin')=='0' ) selected @endif>
                                                No
                                            </option>
                                            <option value="1" @if (old('super_admin')=='1' ) selected @endif>Yes
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                @endif
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="8 Characters (letter and number)" value="{{ old('password') }}"
                                        required autocomplete="off">
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="show-password">
                                    <label class="form-check-label" for="show-password">Show Password</label>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('admin.manage.users') }}" class="btn btn-secondary">Back</a>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>
@endsection
