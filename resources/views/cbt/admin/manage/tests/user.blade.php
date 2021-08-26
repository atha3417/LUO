@extends('layouts.admin.app')

@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Manage All Tests</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">Manage</li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.manage.tests') }}">Tests</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('admin.manage.tests') }}">Participants</a></li>
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
                                <a href="{{ route('admin.manage.tests.create') }}" class="btn btn-primary btn-html5"
                                    tab-index="0" aria-controls="example2" type="button">Add New Test</a>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success mb- float-right"
                            id="submit-form-participant">Save</button>
                        <form action="{{ route('admin.manage.tests.users.save', $test->id) }}" method="POST"
                            id="form-participant">
                            @csrf
                            <table id="dataTable" class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Username</th>
                                        <th>Name</th>
                                        <th>Class</th>
                                        <th id="th-checkbox">
                                            <input type="checkbox" name="select_all" id="select_all"
                                                class="checkbox-md cp">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                    <tr>
                                        <th scope="row" width="1">{{ $loop->index + 1 }}</th>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->class }}</td>
                                        <td width="80">
                                            @php
                                            $is_checked = false;
                                            @endphp
                                            @if ($test->participants)
                                            @foreach ($test->participants as $participant)
                                            @if ($participant->id == $user->id)
                                            @php
                                            $is_checked = true;
                                            @endphp
                                            @endif
                                            @endforeach
                                            @endif

                                            <input type="checkbox" name="participants[]"
                                                class="participants checkbox-md cp" value="{{ $user->id }}" @if (
                                                ($is_checked)) checked @endif>
                                            @php
                                            $is_checked = false;
                                            @endphp
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
