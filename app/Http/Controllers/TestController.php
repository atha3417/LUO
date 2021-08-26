<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Quiz;
use App\Models\Result;
use App\Models\Test;
use App\Models\Time;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function index()
    {
        $tests = [];

        if ($this->get_my_tests('tests')) {
            foreach ($this->get_my_tests('id') as $id) {
                $test = Test::where('id', $id)->first();
                $result = Result::where([
                    ['test_id', '=', $id],
                    ['user_id', '=', Auth::id()]
                ])->first();
                if (count($test->quizzes) >= 0) {
                    $test->status = $result->status ?? null;
                    $test->user_started = $result->user_started ?? null;
                    $test->user_ended = $result->user_ended ?? null;
                    $test->not_expired = $this->time_larger_than($test->start_test, $test->end_test);
                    array_push($tests, $test);
                }
            }
        }

        $data = [
            'title' => 'Dashboard',
            'tests' => $tests
        ];
        return view('cbt.index', $data);
    }

    public function all_questions(Test $test)
    {
        for ($i = 0; $i < count($test->quizzes); $i++) {
            $answer = Answer::where([
                ['test_id', '=', $test->id],
                ['quiz_id', '=', $test->quizzes[$i]->id],
                ['user_id', '=', Auth::id()]
            ])->first();

            $test->quizzes[$i]->my_answer = $answer;
        }

        return $test->quizzes;
    }

    protected function check_user()
    {
        if ((bool) Auth::user()->is_active == false) {
            return false;
        }
        return true;
    }

    public function show(Test $test)
    {
        if (!$test->id) redirect('/cbt');

        // dd(count($test->quizzes) <= 0);

        if (count($test->quizzes) <= 0) {
            redirect()->route('dashboard')->withErrors(['test' => 'Tes tidak ditemukan!']);
        }

        $test->total_question = count($test->quizzes);

        $data = [
            'title' => 'Mulai Tes',
            'test' => $test
        ];
        return view('cbt.confirm-test', $data);
    }

    public function detail_test(Test $test)
    {
        if (!$test->id) redirect('/cbt');
        $test->total_question = count($test->quizzes);

        $result = Result::where([
            ['test_id', '=', $test->id],
            ['user_id', '=', Auth::id()]
        ])->first();

        $answers = Answer::where([
            ['user_id', '=', Auth::id()],
            ['test_id', '=', $test->id]
        ])->get();

        $correct_answers = array_filter($answers->toArray(), function ($answer) {
            return (bool) $answer['is_correct'] == true;
        });

        $test->total_correct_answer = count($correct_answers);

        $data = [
            'title' => 'Detail Hasil Tes',
            'test' => $test,
            'result' => $result
        ];
        return view('cbt.detail-test', $data);
    }

    public function live_clock()
    {
        date_default_timezone_set('Asia/Jakarta');
        echo date('H:i:s');
    }

    public function start_test(Test $test)
    {
        if ($this->get_my_tests('bool', $test->id)) {
            $result = Result::create([
                'user_id' => Auth::id(),
                'test_id' => $test->id,
                'user_started' => now()
            ]);

            if ($result) {
                return redirect()->route('cbt.test.do', $test->id);
            }

            return redirect()->route('dashboard', $test->id)->withErrors([
                'test' => 'Gagal memulai test! silahkan coba lagi!'
            ]);
        } else {
            return redirect()->route('dashboard', $test->id);
        }
    }

    public function do_test(Test $test)
    {
        $result = Result::where([
            ['test_id', '=', $test->id],
            ['user_id', '=', Auth::id()]
        ])->first();

        if ($result) {
            if ($result->user_started == null) {
                return redirect()->route('dashboard');
            }
        }

        if ($test->quizzes) {
            $my_choice = array_filter($test->quizzes[0]->answers->toArray(), function ($answer) {
                return $answer['user_id'] == Auth::id();
            });


            if ($my_choice) {
                $is_doubt = $my_choice[0]['is_doubt'];
                $my_choice = $my_choice[0]['choice_id'];
            } else {
                $my_choice = 0;
                $is_doubt = 0;
            }
        }

        $time_db = Time::where([
            ['user_id', '=', Auth::id()],
            ['test_id', '=', $test->id]
        ])->first();

        if ($result) {
            if (!$time_db) {
                Time::create([
                    'user_id' => Auth::id(),
                    'test_id' => $test->id,
                    'time_left' => floor($test->duration * 60)
                ]);
            }
        }

        $data = [
            'title' => 'Pengerjaan soal',
            'test' => $test,
            'my_choice' => $my_choice ?? 0,
            'is_doubt' => $is_doubt ?? 0,
            'result' => $result
        ];

        return view('cbt.do-test', $data);
    }

    public function save_unix_time_left(Test $test, Request $request)
    {
        $time = Time::where([
            ['user_id', '=', Auth::id()],
            ['test_id', '=', $test->id]
        ])->first();

        if ($time) {
            $time->time_left = $request->time_left;
            $time->save();
        }

        return $time->time_left;
    }

    public function get_unix_time_left(Test $test)
    {
        $time = Time::where([
            ['user_id', '=', Auth::id()],
            ['test_id', '=', $test->id]
        ])->first();

        if ($time) {
            return $time;
        }
    }

    public function save_answer(Quiz $quiz, Request $request)
    {
        if ($this->check_user() == false) {
            $response['is_active'] = 'no';
            $response['status'] = 'error';

            return $response;
        }

        $my_answer = array_filter($quiz->answers->toArray(), function ($answer) {
            return $answer['user_id'] == Auth::id();
        });

        if ($my_answer) {
            $answer = Answer::where([
                ['user_id', '=', Auth::id()],
                ['quiz_id', '=', $quiz->id]
            ])->first();
            $answer->choice_id = (int) $request->id;

            if ($quiz->choice_id) {
                if ($request->id == $quiz->choice_id) {
                    $answer->is_correct = true;
                } else {
                    $answer->is_correct = null;
                }
            }
        } else {
            $answer = new Answer;
            $answer->user_id = Auth::id();
            $answer->test_id = (int) $quiz->test->id;
            $answer->quiz_id = (int) $quiz->id;
            $answer->choice_id = (int) $request->id;

            if ($quiz->choice_id) {
                if ($request->id == $quiz->choice_id) {
                    $answer->is_correct = true;
                } else {
                    $answer->is_correct = null;
                }
            }
        }

        if ($answer->save()) {
            $response['status'] = 'success';
        } else {
            $response['status'] = 'error';
        }
        $response['is_active'] = 'yes';

        return $response;
    }

    public function save_doubt(Quiz $quiz)
    {
        if ($this->check_user() == false) {
            $response['is_active'] = 'no';
            $response['status'] = 'error';

            return $response;
        }

        $answer = Answer::where([
            ['user_id', '=', Auth::id()],
            ['quiz_id', '=', $quiz->id]
        ])->first();

        if ($answer) {
            $answer->is_doubt = !$answer->is_doubt;
        } else {
            $answer = new Answer;
            $answer->user_id = Auth::id();
            $answer->test_id = (int) $quiz->test->id;
            $answer->quiz_id = (int) $quiz->id;
            $answer->is_doubt = !$answer->is_doubt;
        }

        if ($answer->save()) {
            $response['status'] = 'success';
        } else {
            $response['status'] = 'error';
        }
        $response['is_active'] = 'yes';

        return $response;
    }

    public function get_question(Quiz $quiz)
    {
        if ($this->check_user() == false) {
            $response['is_active'] = 'no';
            $response['status'] = 'error';

            return $response;
        }

        $quiz->choices = $quiz->choices;
        $quiz->my_choice = Answer::where([
            ['user_id', '=', Auth::id()],
            ['quiz_id', '=', $quiz->id]
        ])->first();

        $response['quiz'] = $quiz;
        $response['is_active'] = 'yes';

        return response($response, 200);
    }

    public function finish_test(Test $test)
    {
        $response = [];

        if ($this->check_user() == false) {
            $response['is_active'] = 'no';
            $response['status'] = 'error';

            return $response;
        }

        if ($this->get_my_tests('bool', $test->id)) {
            $result = Result::where([
                ['test_id', '=', $test->id],
                ['user_id', '=', Auth::id()]
            ])->first();

            $answers = Answer::where([
                ['user_id', '=', Auth::id()],
                ['test_id', '=', $test->id]
            ])->get();

            if ($result && $answers) {
                $correct_answers = array_filter($answers->toArray(), function ($answer) {
                    return (bool) $answer['is_correct'] == true;
                });

                $result->user_ended = now();
                $result->status = (count($correct_answers) * $test->basic_point);

                if ($result->save()) {
                    $response['status'] = 'success';
                    $response['message'] = 'Tes berhasil dikirim!';
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'Gagal mengirim tes! silahkan coba lagi!';
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Tes belum dimulai!';
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Tes tidak ditemukan!';
        }
        $response['is_active'] = 'yes';

        return $response;
    }

    public function validate_before_finish_test(Test $test)
    {
        $result_count = count($test->answers);
        $quiz_count = count($test->quizzes);
        $doubt_count = count(Answer::where([
            ['user_id', '=', Auth::id()],
            ['test_id', '=', $test->id],
            ['is_doubt', '=', true],
        ])->get());

        return [
            'answered' => $result_count,
            'not_answered' => (int) $quiz_count - (int) $result_count,
            'doubted' => $doubt_count
        ];
    }

    protected function get_my_tests($type, $test_id = 0)
    {
        $filtered_tests = [];
        $is_for_me = false;

        foreach (Test::all()->toArray() as $test) {
            if (in_array(Auth::id(), explode(',', $test['for']))) {
                if ($type == 'id') {
                    array_push($filtered_tests, $test['id']);
                } else {
                    array_push($filtered_tests, (object) $test);
                }
            }
        }

        if ($type == 'bool') {
            foreach ($filtered_tests as $filtered_test) {
                $for_me = in_array($test_id, (array) $filtered_test);
                $key = array_keys((array) $filtered_test, $test_id);

                if ($for_me == true && $key) {
                    if ($key[0] == 'id') {
                        $is_for_me = true;
                    }
                }
            }

            return $is_for_me;
        }

        return $filtered_tests;
    }
}
