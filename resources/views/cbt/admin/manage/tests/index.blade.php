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
                    <li class="breadcrumb-item active"><a href="{{ route('admin.manage.tests') }}">Tests</a></li>
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
                        <table id="example2" class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('cbt.test_number_text') }}</th>
                                    <th>{{ __('cbt.test_name_text') }}</th>
                                    <th>{{ __('cbt.test_start_time_text') }}</th>
                                    <th>{{ __('cbt.test_end_time_text') }}</th>
                                    <th>{{ __('cbt.test_action_text') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tests as $test)
                                <tr>
                                    <th scope="row" width="1">{{ $loop->index + 1 }}</th>
                                    <td>{{ $test->test_name }}</td>
                                    <td>{{ $test->start_test }}</td>
                                    <td>{{ $test->end_test }}</td>
                                    <td>
                                        <button title="Test Detail" type="button"
                                            class="btn btn-secondary badge badge-pill btn-detail" data-toggle="modal"
                                            data-target="#modal-detail" data-id="{{ $test->id }}">
                                            <i class="fas fa-fw fa-info"></i>
                                        </button>
                                        <a title="Manage Questions"
                                            href="{{ route('admin.manage.tests.questions', $test->id) }}"
                                            class="btn badge badge-pill badge-info">
                                            <i class="fas fa-fw fa-book-open"></i>
                                        </a>
                                        <a title="Edit Test" href="{{ route('admin.manage.tests.edit', $test->id) }}"
                                            class="btn badge badge-pill badge-success">
                                            <i class="fas fa-fw fa-edit"></i>
                                        </a>
                                        <a title="Manage Participants"
                                            href="{{ route('admin.manage.tests.users', $test->id) }}"
                                            class="btn badge badge-pill badge-primary">
                                            <i class="fas fa-fw fa-user"></i>
                                        </a>
                                        <form action="{{ route('admin.manage.tests.delete', $test->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')

                                            <button title="Delete Test" type="submit"
                                                class="btn btn-danger badge badge-pill"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus tes ini?')">
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

<div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Detail Tes</h4>
            </div>
            <div class="modal-body">
                <div class="form-group row m-3">
                    <label class="form-label col-sm-3">{{ __('cbt.test_name_text') }}</label>
                    <input type="text" class="form-control col-sm-9" id="test_name" disabled>
                </div>
                <div class="form-group row m-3">
                    <label class="form-label col-sm-3">{{ __('cbt.test_for_text') }}</label>
                    <input type="text" class="form-control col-sm-8" id="for" disabled>
                    <button type="button" class="col-sm-1 btn btn-info" data-toggle="modal" data-target="#modal-user"
                        id="btn-user">
                        <i class="fas fa-fw fa-eye"></i>
                    </button>
                </div>
                <div class="form-group row m-3">
                    <label class="form-label col-sm-3">Jumlah Soal</label>
                    <input type="text" class="form-control col-sm-9" id="total_question" disabled>
                </div>
                <div class="form-group row m-3">
                    <label class="form-label col-sm-3">{{ __('cbt.test_start_time_text') }}</label>
                    <input type="text" class="form-control col-sm-9" id="start_time" disabled>
                </div>
                <div class="form-group row m-3">
                    <label class="form-label col-sm-3">{{ __('cbt.test_end_time_text') }}</label>
                    <input type="text" class="form-control col-sm-9" id="end_time" disabled>
                </div>
                <div class="form-group row m-3">
                    <label class="form-label col-sm-3">{{ __('cbt.basic_point_text') }}</label>
                    <input type="text" class="form-control col-sm-9" id="basic_point" disabled>
                </div>
                <div class="form-group row m-3">
                    <label class="form-label col-sm-3">{{ __('cbt.max_point_text') }}</label>
                    <input type="text" class="form-control col-sm-9" id="max_point" disabled>
                </div>
                <div class="form-group row m-3">
                    <label class="form-label col-sm-3">{{ __('cbt.duration_text') }}</label>
                    <input type="text" class="form-control col-sm-9" id="duration" disabled>
                </div>
                <div class="form-group row m-3">
                    <label class="form-label col-sm-3">{{ __('cbt.test_created_at_text') }}</label>
                    <input type="text" class="form-control col-sm-9" id="created_at" disabled>
                </div>
                <div class="form-group row m-3">
                    <label class="form-label col-sm-3">{{ __('cbt.test_updated_at_text') }}</label>
                    <input type="text" class="form-control col-sm-9" id="updated_at" disabled>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="modal-user" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Peserta Tes</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Username</th>
                            <th scope="col">Name</th>
                            <th scope="col">Class</th>
                        </tr>
                    </thead>
                    <tbody id="tbl-body"></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection
