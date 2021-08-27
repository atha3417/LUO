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
                            <form action="{{ route('admin.manage.tests.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Enter test name" value="{{ old('name') }}" required autofocus>
                                </div>
                                <div class="form-group">
                                    <label for="duration">Duration</label>
                                    <input type="text" class="form-control" id="duration" name="duration"
                                        placeholder="Enter test duration" value="{{ old('duration') }}" required
                                        autofocus>
                                </div>
                                <div class="form-group">
                                    <label for="maximal_point">Maximal Point</label>
                                    <input type="text" class="form-control" id="maximalpoint" name="maximal_point"
                                        placeholder="Enter maximal point" value="{{ old('maximal_point') }}" required>
                                </div>
                                <div class=" form-group">
                                    <label for="start-test">Start Test</label>
                                    <div class="input-group date" id="start-test" data-target-input="nearest">
                                        <input type="text" name="start_test" id="start-test"
                                            class="form-control datetimepicker-input" data-target="#start-test"
                                            placeholder="Enter start test" value="{{ old('start_test') }}" required
                                            autocomplete="off">
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
                                            placeholder="Enter end test" value="{{ old('end_test') }}" required
                                            autocomplete="off">
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
                                    <button type="submit" name="fill_question" class="btn btn-success"
                                        value="fill_question">
                                        Submit And Fill Question
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>
@endsection
