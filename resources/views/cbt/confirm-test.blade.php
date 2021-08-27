@extends('layouts.app')
@section('body')

<div class="content-wrapper">
    <section class="container content-header">
        <div class="container-fluid">
            <div class="row mb-1">
                <div class="col-sm-12">
                    <h4 class="d-inline mr-2">{{ __('cbt.confirm_test_title') }}</h4>
                    <small class="d-inline text-gray">{{ __('cbt.small_confirm_title') }}</small>
                </div>
            </div>
    </section>

    <div class="container content">
        <div class="card" style="border: 1px #28a745 solid;">
            <div class="card-header bg-success">
                <h3 class="card-title">{{ __('cbt.confirm_test_title') }}</h3>
            </div>
            <form action="{{ route('cbt.test.start', $test->id) }}" method="POST">
                @csrf
                <div class="card-body">
                    <table class="table">
                        <tr style="height: 45px;">
                            <td></td>
                            <td>{{ __('cbt.user_test_name_text') }}</td>
                            <td><b>{{ Auth::user()->name }}</b></td>
                            <td></td>
                        </tr>
                        <tr style="height: 45px;">
                            <td></td>
                            <td>{{ __('cbt.test_name_text') }}</td>
                            <td><b>{{ $test->test_name }}</b></td>
                            <td></td>
                        </tr>
                        <tr style="height: 45px;">
                            <td></td>
                            <td>{{ __('cbt.duration_text') }}</td>
                            <td>{{ $test->duration }} {{ __('cbt.duration_unit_text') }}</td>
                            <td></td>
                        </tr>
                        <tr style="height: 45px;">
                            <td></td>
                            <td>{{ __('cbt.total_question_text') }}</td>
                            <td>{{ $test->total_question }} {{ __('cbt.question_text') }}
                            </td>
                            <td></td>
                        </tr>
                        <tr style="height: 45px;">
                            <td></td>
                            <td>{{ __('cbt.basic_point_text') }}</td>
                            <td>{{ $test->basic_point }}</td>
                            <td></td>
                        </tr>
                        <tr style="height: 45px;">
                            <td></td>
                            <td>{{ __('cbt.max_point_text') }}</td>
                            <td>{{ $test->maximal_point }}</td>
                            <td></td>
                        </tr>
                        <input type="hidden" name="token" id="token">
                    </table>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-sm float-right">Kerjakan</button>
                    <a href="{{ route('dashboard') }}"
                        class="btn btn-secondary btn-sm float-right mr-2">{{ __('cbt.back_button_text') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
