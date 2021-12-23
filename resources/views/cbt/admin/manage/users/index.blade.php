@extends('layouts.admin.app')

@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Manage All Users</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">Manage</li>
                    <li class="breadcrumb-item active"><a href="{{ route('admin.manage.users') }}">Users</a></li>
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
            @if (session('message'))
            <div class="alert alert-success" role="alert">
                {{ session('message') }}
            </div>
            @endif
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="d-none" id="btn-create">
                            <div class="btn-group">
                                <a href="{{ route('admin.manage.users.create') }}" class="btn btn-primary btn-html5"
                                    tab-index="0" aria-controls="example2" type="button">Add New User</a>
                            </div>
                        </div>
                        <table id="example2" class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Username</th>
                                    <th>Name</th>
                                    <th>Class</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                <tr>
                                    <th scope="row" width="1">{{ $loop->index + 1 }}</th>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->class }}</td>
                                    <td width="130">
                                        <button title="{{ $user->is_active == '0' ? 'Aktifkan' : 'Nonaktifkan' }}"
                                            class="btn btn-primary badge badge-pill" id="toggle-active"
                                            onclick="toggleActive('{{ $user->id }}')">
                                            @if ($user->is_active == '0')
                                            <i class="fas fa-fw fa-door-closed" id="door-{{ $user->id }}"></i>
                                            @else
                                            <i class="fas fa-fw fa-door-open" id="door-{{ $user->id }}"></i>
                                            @endif
                                        </button>
                                        <a href="{{ route('admin.manage.users.edit', $user->id) }}"
                                            class="btn badge badge-pill badge-success">
                                            <i class="fas fa-fw fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.manage.users.delete', $user->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-danger badge badge-pill"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                                <i class="fas fa-fw fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
