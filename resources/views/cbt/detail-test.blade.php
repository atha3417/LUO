@extends('layouts.app')
@section('body')

<div class="content-wrapper">
    <section class="container content-header">
        <div class="container-fluid">
            <div class="row mb-1">
                <div class="col-sm-12">
                    <h4 class="d-inline mr-2">Detail Hasil Tes</h4>
                    <small class="d-inline text-gray">Detail tes yang telah dikerjakan</small>
                </div>
            </div>
    </section>

    <div class="container content">
        <div class="card" style="border: 1px #28a745 solid;">
            <div class="card-header bg-success mb-3">
                <h3 class="card-title">Informasi Tes</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label col-form-label-sm control-label">Nama Tes</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm input-sm"
                                    value="{{ $test->test_name }}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 control-label col-form-label col-form-label-sm">Nama Peserta</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm input-sm"
                                    value="{{ Auth::user()->name }}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 control-label col-form-label col-form-label-sm">Waktu Tes Mulai</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm input-sm"
                                    value="{{ $result->user_started }}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 control-label col-form-label col-form-label-sm">Waktu Tes Selesai</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm input-sm"
                                    value="{{ $result->user_ended }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 control-label col-form-label col-form-label-sm">Nilai</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm input-sm"
                                    value="{{ $result->status }}"
                                    readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 control-label col-form-label col-form-label-sm">Benar</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm input-sm"
                                    value="{{ $test->total_correct_answer }}"
                                    readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 control-label col-form-label col-form-label-sm">Salah</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm input-sm"
                                    value="{{ $test->total_question - $test->total_correct_answer }}"
                                    readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="/" class="btn btn-secondary btn-sm float-right">Kembali</a>
            </div>
        </div>
    </div>
</div>

@endsection
