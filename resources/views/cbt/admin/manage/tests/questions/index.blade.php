@extends('layouts.admin.app')

@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Manage All Questions</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">Manage</li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.manage.tests') }}">Tests</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="{{ route('admin.manage.tests.questions', $test->id) }}">Questions</a>
                    </li>
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
                                <a href="{{ route('admin.manage.tests.questions.create', $test->id) }}"
                                    class="btn btn-primary btn-html5" tab-index="0" aria-controls="example2"
                                    type="button">Add New Question</a>
                            </div>
                        </div>
                        <table id="example2" class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Question</th>
                                    <th>Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($test->quizzes as $quiz)
                                <tr>
                                    <th scope="row" width="1">{{ $loop->index + 1 }}</th>
                                    <td>{!! Str::limit($quiz->question, 110) !!}</td>
                                    <td>{{ $quiz->type->name }}</td>
                                    <td width="170">
                                        <button title="Detail Question" type="button"
                                            class="btn badge badge-pill btn-secondary btn-detail-question"
                                            data-toggle="modal" data-target="#modal-detail" data-id="{{ $test->id }}"
                                            data-quiz-id="{{ $quiz->id }}">
                                            <i class="fas fa-fw fa-info"></i>
                                        </button>
                                        <a href="{{ route('admin.manage.tests.questions.edit',['test' => $test->id, 'quiz' => $quiz->id]) }}"
                                            class="btn badge badge-pill badge-primary">
                                            <i class="fas fa-fw fa-dot-circle"></i>
                                        </a>
                                        <a href="{{ route('admin.manage.tests.questions.edit',['test' => $test->id, 'quiz' => $quiz->id]) }}"
                                            class="btn badge badge-pill badge-success">
                                            <i class="fas fa-fw fa-edit"></i>
                                        </a>
                                        <form
                                            action="{{ route('admin.manage.tests.questions.delete',['test' => $test->id, 'quiz' => $quiz->id]) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-danger badge badge-pill"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus pertanyaan ini?')">
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
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Detail Pertanyaan</h4>
            </div>
            <div class="modal-body">
                <div class="form-group m-3">
                    <label class="form-label">Soal</label>
                    <div id="question"></div>
                </div>
                <div id="choice-div">
                    <div class="form-group m-3">
                        <label class="form-label">Pilihan</label>
                        <div id="available_choices"></div>
                    </div>
                    <div class="form-group m-3">
                        <label class="form-label">Pilihan Benar</label>
                        <input type="text" class="form-control" id="correct_choice" disabled>
                    </div>
                </div>
                <div id="answer-div">
                    <div class="form-group m-3">
                        <label class="form-label">Jawaban Benar</label>
                        <div id="answer"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection
