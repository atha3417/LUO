@extends('layouts.admin.app')

@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create New Question</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">Manage</li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.manage.tests') }}">Tests</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.manage.tests.questions', $test->id) }}">Questions</a>
                    </li>
                    <li class="breadcrumb-item">Create</li>
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
                <div class="col-md-12">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form action="{{ route('admin.manage.tests.questions.store', $test->id) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="question">Question</label>
                                    <textarea name="question" placeholder="Enter Question" class="form-control tinymce"
                                        autofocus required>
                                        {{ old('question') }}
                                    </textarea>
                                </div>
                                <div class="form-group">
                                    <label for="type">Type</label>
                                    <select class="form-control select2bs4" name="type" id="type-question">
                                        @foreach ($types as $type)
                                        <option value="{{ $type->id }}" @if (old('type')==$type->id) selected @endif>
                                            {{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="choice-div">
                                    <div id="choices-div">
                                        <label for="choices">Available Choices</label>
                                        <div class="form-group">
                                            <select class="form-control select2bs4-tags" name="choices[]" id="choices"
                                                multiple="multiple">
                                                @foreach ((array) old('choices') as $choice)
                                                <option value="{{ $choice }}" selected>{{ $choice }}</option>
                                                @endforeach</select>
                                        </div>
                                    </div>
                                    <div id="correct-choice-div">
                                        <div class="form-group">
                                            <label for="choice">
                                                Correct Choice
                                                <small>
                                                    <sup class="ch">
                                                        <i class="fas fa-fw fa-question-circle"
                                                            title="Choose one between choice or long answer"></i>
                                                    </sup>
                                                </small>
                                            </label>
                                            <select class="form-control" name="choice" id="choice">
                                                @foreach ((array) old('choices') as $choice)
                                                <option value="{{ $choice }}" @if (old('choice')==$choice) selected
                                                    @endif>{{ $choice }}</option>
                                                @endforeach</select>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group d-none" id="answer-div">
                                    <label for="answer">
                                        Answer
                                        <small>
                                            <sup class="ch">
                                                <i class="fas fa-fw fa-question-circle"
                                                    title="Choose one between choice or long answer"></i>
                                            </sup>
                                        </small>
                                    </label>
                                    <textarea class="form-control tinymce" id="answer" name="answer"
                                        placeholder="Enter Long Answer">
                                        {{ old('answer') }}
                                    </textarea>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('admin.manage.tests.questions', $test->id) }}"
                                        class="btn btn-secondary">Back</a>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <button type="submit" name="fill_question" value="fill_question"
                                        class="btn btn-success">
                                        Submit and Fill Another Question
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
