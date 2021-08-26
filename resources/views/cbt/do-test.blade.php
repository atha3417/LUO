@extends('layouts.app')
@section('body')

<div class="content-wrapper">
    <section class="container content-header">
        <div class="container-fluid">
            <div class="row mb-1">
                <div class="col-sm-12">
                    <h4 class="d-inline">
                        Tes : {{ $test->test_name }}
                    </h4>
                </div>
            </div>
    </section>

    <section class="container content">
        <div id="alert">
            @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                @foreach ($errors->all() as $error)
                {{ $error }} <br>
                @endforeach
            </div>
            @endif
            @if (session('error'))
            <div class="alert alert-warning" role="alert">
                {{ session('error') }}
            </div>
            @endif
        </div>

        <div class="card border-primary">
            <div class="card-header bg-gradient-primary">
                <h3 class="card-title float-left">Soal ke <span id="current_number">1</span></h3>
                <h3 id="sisa-waktu" class="card-title float-right" style="background-color: #90EE90; height: 25px;">
                    <b>
                        <p style="font-size:20px; color:red">
                            &nbsp;&nbsp;
                            <span id="time-left">
                                <span id="hours-left">00</span>
                                :
                                <span id="minutes-left">00</span>
                                :
                                <span id="seconds-left">00</span>
                            </span>
                            &nbsp;&nbsp;
                        </p>
                    </b>
                </h3>
            </div>
            <div class="card-body">
                <div class="quiz">
                    <input type="hidden" id="current_quiz_id" value="{{ $test->quizzes[0]->id }}">
                    <input type="hidden" id="current_test_id" value="{{ $test->id }}">

                    @if ($test->quizzes)
                    <div id="question">
                        {!! $test->quizzes[0]->question !!}
                    </div>
                    <hr />
                    <div id="choices">
                        @foreach ($test->quizzes[0]->choices as $choice)
                        @php ($choice_id = $choice->id) @endphp
                        @if ($test->type->id == 1)
                        <div class="form-check">
                            <div class="radio">
                                <input type="radio" class="form-check-input" name="choice"
                                    onchange="answer('{{ $choice->id }}', '{{ $test->quizzes[0]->id  }}')"
                                    value="{{ $choice->id }}" id="choice-{{ $choice->id }}" @if ($my_choice==$choice_id)
                                    checked @endif>
                                <label class="form-check-label" for="choice-{{ $choice->id }}">
                                    <p>
                                        {{ $choice->value }}
                                    </p>
                                </label>
                            </div>
                        </div>
                        @endforeach
                        @if ($test->type->id != 1)
                        <div class="form-group">
                            <label for="answer">Example textarea</label>
                            <textarea class="form-control" name="answer" rows="3"></textarea>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            <div class="card-footer">
                @if ($test->quizzes)
                <button type="button" class="btn btn-secondary btn-sm d-none" id="btn-prev">Soal
                    Sebelumnya</button>&nbsp;&nbsp;&nbsp;
                <div class="btn btn-warning btn-sm cp">
                    <input type="checkbox" class="cp" id="btn-doubt" @if ($is_doubt==true) checked @endif>
                    <label for="btn-doubt" class="m-0 cp">Ragu ragu</label>
                </div>&nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-secondary btn-sm"
                    onclick="get_question('{{ $test->quizzes[1]->id }}', '2')" id="btn-next">Soal
                    Selanjutnya</button>
                @endif
            </div>
        </div>

        <div class="border-success">
            <div class="card-header bg-gradient-success">
                <h3 class="card-title">Daftar Soal</h3>
            </div>
            <div class="card-body">
                @if ($test->quizzes)
                @foreach ($test->quizzes as $quiz)
                <button id="btn-question-{{ $quiz->id }}" class="numbers btn btn-default shadow-lg"
                    title="Soal ke {{ $loop->index+1 }}"
                    onclick="get_question('{{ $quiz->id }}', '{{ $loop->index+1 }}')" data-id="{{ $quiz->id }}">
                    <span style="color: black;">{{ $loop->index+1 }}</span>
                </button>
                @endforeach
                @endif

                <div class="mt-4">
                    <p class="help-block text-danger">
                        <b>Ket:</b>
                    </p>
                    <p class="help-block">
                        &emsp;Soal yang <u>sudah dijawab</u> akan berwarna <b>Biru</b>.
                    </p>
                    <p class="help-block">
                        &emsp;Soal yang <u>ragu-ragu</u> akan berwarna <b>Kuning</b>.
                    </p>
                    <p class="help-block">
                        &emsp;Soal yang <u>belum dijawab</u> akan berwarna <b>Putih</b>.
                    </p>
                    <p class="help-block">
                        &emsp;Soal yang <u>sedang dikerjakan</u> akan berwarna <b>Merah</b>.
                    </p>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-secondary btn-sm float-right" data-toggle="modal" data-target="#modal-finish"
                    id="btn-stop">Hentikan
                    Tes</button>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="modal-finish" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Konfirmasi Hentikan Tes</h4>
            </div>
            <div class="modal-body">
                <div class="card border-danger">
                    <div class="card-header bg-gradient-danger">
                        <h3 class="card-title">Peringatan</h3>
                    </div>
                    <div class="card-body">
                        Apakah anda yakin mengakhiri ujian ini?
                        <br>
                        Jawaban tes yang sudah selesai tidak dapat diubah!
                    </div>
                </div>

                <input type="hidden" id="test_id" value="{{ $test->id }}">
                <div class="form-group">
                    <label for="test_name">Nama Tes</label>
                    <input type="text" class="form-control" id="test_name" value="{{ $test->test_name }}" disabled>
                </div>
                <div class="form-group">
                    <label for="test_name">Keterangan Soal</label>
                    <input type="text" class="form-control" id="test_desc" value="0 Soal dijawab, 5 Soal belum dijawab."
                        disabled>
                </div>
                <div class="form-group">
                    <label for="confirm">Konfirmasi</label>
                    <input type="text" class="form-control" id="confirm" placeholder='Ketikkan "Saya sudah selesai"'
                        autocomplete="off">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary" id="finish_btn" id="password-submit">Hentikan
                    Tes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@endsection
