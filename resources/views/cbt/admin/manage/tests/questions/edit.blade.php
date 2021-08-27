@extends('layouts.admin.app')

@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Question</h1>
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
                    <li class="breadcrumb-item">Edit</li>
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
                            <form
                                action="{{ route('admin.manage.tests.questions.update', ['test' => $test->id, 'quiz' => $quiz->id]) }}"
                                method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="question">Question</label>
                                    <textarea name="question" placeholder="Enter Question" class="form-control tinymce"
                                        autofocus required>
                                        {{ old('question') ?? $quiz->question }}
                                    </textarea>
                                </div>
                                <div class="form-group">
                                    <label for="type">Type</label>
                                    <select class="form-control select2bs4" name="type" id="type-question">
                                        @php
                                        if (old('type')) {
                                        $my_type = (array) old('type');
                                        } else {
                                        $my_type = $quiz->type_id;
                                        }
                                        @endphp

                                        @foreach ($types as $type)
                                        <option value="{{ $type->id }}" @if ($my_type==$type->id) selected @endif>
                                            {{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="choice-div">
                                    <label for="choices">Available Choices</label>
                                    <div class="form-group">
                                        <select class="d-none" name="old_choices[]" multiple="multiple">
                                            @foreach ($quiz->choices as $choice)
                                            <option class="built-in-choices" value="{{ $choice['value'] }}" selected>
                                                {{ $choice['value'] }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <select class="form-control select2bs4-tags" name="choices[]" id="choices"
                                            multiple="multiple">
                                            @php
                                            if (old('choices') > 0) {
                                            $choices = (array) old('choices');
                                            } else if ($quiz->choices->toArray() > 0) {
                                            $choices = $quiz->choices->toArray();
                                            } else {
                                            $choices = [];
                                            }
                                            @endphp
                                            @foreach ($choices as
                                            $choice)
                                            <option value="{{ $choice['value'] }}" selected>{{ $choice['value'] }}
                                            </option>
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
                                            @foreach ($choices as $choice)
                                            <option value="{{ $choice['value'] }}" @if (
                                                (old('choice')==$choice['value'])) selected @endif>
                                                {{ $choice['value'] }}</option>
                                            @endforeach
                                        </select>
                                        </select>
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
                                                {{ old('answer') ?? $quiz->correct_answer }}
                                            </textarea>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('admin.manage.tests.questions', $test->id) }}"
                                        class="btn btn-secondary">Back</a>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
