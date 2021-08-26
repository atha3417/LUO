@extends('layouts.admin.app')

@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Test</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">Manage</li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.manage.tests') }}">Test</a></li>
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
                            <form action="{{ route('admin.manage.tests.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="id" value="{{ $test->id }}">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Enter test name" value="{{ old('name') ?? $test->test_name }}"
                                        required autofocus>
                                </div>
                                <div class="form-group">
                                    <label for="type">Type</label>
                                    <select class="form-control select2bs4" name="type" id="type">
                                        @foreach ($types as $type)
                                        <option value="{{ $type->id }}" @if (old('type') ?? $test->type->id==$type->id)
                                            selected @endif>
                                            {{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="duration">Duration</label>
                                    <input type="text" class="form-control" id="duration" name="duration"
                                        placeholder="Enter test duration"
                                        value="{{ old('duration') ?? $test->duration }}" required autofocus>
                                </div>
                                <div class="form-group">
                                    <label for="basic_point">Basic Point</label>
                                    <input type="text" class="form-control" id="basic-point" name="basic_point"
                                        placeholder="Enter basic point"
                                        value="{{ old('basic_point') ?? $test->basic_point }}">
                                </div>
                                <div class="form-group">
                                    <label for="maximal_point">Maximal Point</label>
                                    <input type="text" class="form-control" id="maximalpoint" name="maximal_point"
                                        placeholder="Enter maximal point"
                                        value="{{ old('maximal_point') ?? $test->maximal_point }}" required>
                                </div>
                                <div class=" form-group">
                                    <label for="start-test">Start Test</label>
                                    <div class="input-group date" id="start-test" data-target-input="nearest">
                                        <input type="text" name="start_test" id="start-test"
                                            class="form-control datetimepicker-input" data-target="#start-test"
                                            placeholder="Enter start test"
                                            value="{{ old('start_test') ?? $test->start_test }}" required>
                                        <div class="input-group-append" data-target="#start-test"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="end-test">End Test</label>
                                    <div class="input-group date" id="end-test" data-target-input="nearest">
                                        <input type="text" name="end_test" id="end-test"
                                            class="form-control datetimepicker-input" data-target="#end-test"
                                            placeholder="Enter end test"
                                            value="{{ old('end_test') ?? $test->end_test }}" required>
                                        <div class="input-group-append" data-target="#end-test"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('admin.manage.tests') }}" class="btn btn-secondary">Back</a>
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
