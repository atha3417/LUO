@extends('layouts.admin.app')

@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Results by users</h1>
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
                                <form action="{{ route('admin.manage.results.by_users.delete_all') }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-danger btn-html5"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus semua hasil?')">
                                        Clear All Results
                                    </button>
                                </form>
                            </div>
                        </div>
                        <table id="example2" class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Username</th>
                                    <th>Class</th>
                                    <th>Completed Test</th>
                                    <th>Last Completed Test</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                <tr>
                                    <th scope="row" width="1">{{ $loop->index + 1 }}</th>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->class }}</td>
                                    <td>{{ $user->results->count() }}</td>
                                    @if ($user->results->count() > 0)
                                    <td>{{ $user->results[count($user->results)-1]->user_ended }}</td>
                                    @else
                                    <td style="font-family: 'Fira Code'">-</td>
                                    @endif
                                    <td width="130">
                                        <a href="{{ route('admin.manage.results.by_users.show', $user->id) }}"
                                            class="btn badge badge-pill badge-secondary">
                                            <i class="fas fa-fw fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.manage.results.by_users.delete', $user) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-danger badge badge-pill"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus hasil ini?')">
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
