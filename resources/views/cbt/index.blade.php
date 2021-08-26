@extends('layouts.app')
@section('body')

<style>
    th {
        cursor: pointer;
    }

</style>

<div class="content-wrapper">
    <section class="container content-header">
        <div class="container-fluid">
            <div class="row mb-1">
                <div class="col-sm-12">
                    <h4 class="d-inline mr-2">
                        {{ __('cbt.welcome_message_text') }}
                        {{ Auth::user()->name }}
                        |
                        {{ Auth::user()->class }}
                    </h4>
                    <small class="d-inline text-gray">{{ __('cbt.small_welcome_message_text') }}</small>
                </div>
            </div>
    </section>

    <section class="container content">
        <div id="alert"></div>
        <div class="row">
            <div class="col">
                <div class="card border-primary">
                    <div class="card-header bg-gradient-primary">
                        <h3 class="card-title">{{ __('cbt.info_title') }}</h3>
                    </div>
                    <div class="card-body">
                        {{ __('cbt.info_before_test') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-success">
            <div class="card-header bg-gradient-success">
                <h3 class="card-title">{{ __('cbt.test_list_title') }}</h3>
            </div>
            <div class="card-body">
                <table class="table table-hover" id="dataTable" style="font-size: 14px;">
                    <thead>
                        <tr id="sort">
                            <th>{{ __('cbt.test_number_text') }}</th>
                            <th>{{ __('cbt.test_name_text') }}</th>
                            <th>{{ __('cbt.test_start_time_text') }}</th>
                            <th>{{ __('cbt.test_end_time_text') }}</th>
                            <th>{{ __('cbt.test_state_text') }}</th>
                            <th>{{ __('cbt.test_action_text') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($tests))
                        @foreach ($tests as $test)
                        <tr>
                            <th scope="row">{{ $loop->index + 1 }}</th>
                            <td>
                                {!! !$test->not_expired ? '<s>' . $test->test_name . '</s>' : $test->test_name !!}
                            </td>
                            <td>
                                {!! !$test->not_expired ? '<s>' . $test->start_test . '</s>' : $test->start_test !!}
                            </td>
                            <td>
                                {!! !$test->not_expired ? '<s>' . $test->end_test . '</s>' : $test->end_test !!}
                            </td>
                            <td>{!! !$test->not_expired ? '<p>EXPIRED</p>' : $test->status !!}</td>
                            <td>
                                @if ($test->status || $test->user_ended)
                                <a href="/cbt/detail-test/{{ $test->id }}"
                                    class="btn btn-default btn-xs">{{ __('cbt.test_detail_text') }}</a>
                                @elseif (!$test->not_expired)
                                <p>EXPIRED</p>
                                @elseif ($test->user_started && !$test->user_ended && $test->not_expired)
                                <a href="{{ route('cbt.test.do', $test->id) }}"
                                    class="btn btn-warning btn-xs">{{ __('cbt.test_continue_text') }}</a>
                                @elseif (!$test->user_ended && $test->not_expired)
                                <a href="/cbt/confirm-test/{{ $test->id }}"
                                    class="btn btn-success btn-xs">{{ __('cbt.test_do_text') }}</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

@endsection
