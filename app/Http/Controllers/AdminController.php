<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Choice;
use App\Models\Quiz;
use App\Models\Result;
use App\Models\Test;
use App\Models\Time;
use App\Models\Type;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    public function index()
    {
        $tests = Test::all();
        $counts = [
            'users' => User::where('is_admin', '=', '0')->count(),
            'admins' => User::where('is_admin', '=', '1')->count(),
            'super_admins' => User::where('super_admin', '=', '1')->count(),
            'tests' => $tests->count(),
        ];

        $data = [
            'title' => 'Admin Dashboard',
            'tests' => $tests,
            'counts' => (object) $counts
        ];
        return view('cbt.admin.index', $data);
    }

    public function manage_users()
    {
        if (Auth::user()->super_admin) {
            $user = User::where('id', '!=', Auth::id())->get();
        } else if (Auth::user()->is_admin) {
            $user = User::where([
                ['super_admin', '=', '0'],
                ['is_admin', '=', '0']
            ])->get();
        } else {
            $user = User::where('is_admin', '=', '0')->get();
        }

        $data = [
            'title' => 'Manage Users',
            'users' => $user
        ];
        return view('cbt.admin.manage.users.index', $data);
    }

    public function manage_users_edit(User $user)
    {
        if (Auth::user()->is_admin && !Auth::user()->super_admin) {
            if ($user->is_admin == '1' || $user->super_admin == '1') {
                return redirect()->route('admin.manage.users')->with('error', 'User tidak ditemukan!');
            }
        }

        $data = [
            'title' => 'Edit User',
            'user' => $user
        ];
        return view('cbt.admin.manage.users.edit', $data);
    }

    public function manage_users_create()
    {
        $data = [
            'title' => 'Create New User'
        ];
        return view('cbt.admin.manage.users.create', $data);
    }

    public function manage_users_update(Request $request)
    {
        $rules = [
            'username' => 'sometimes|required|string|max:255|unique:users,username,' . $request->id,
            'name' => 'required|string|max:255',
            'class' => 'required|string|max:255'
        ];
        if ($request->password) {
            $rules['password'] = [Password::defaults()];
        }

        $request->validate($rules);

        $user = User::find($request->id);

        $user->username = $request->username;
        $user->name = $request->name;
        $user->class = $request->class;

        if (Auth::user()->super_admin) {
            $user->is_admin = (bool) $request->is_admin;
            $user->super_admin = (bool) $request->is_super_admin;
        }

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        if ($user->save()) {
            return redirect()->route('admin.manage.users')->with('message', 'Berhasil mengubah user!');
        } else {
            return redirect()->route('admin.manage.users')->withErrors([
                'user' => 'Gagal mengubah user baru!'
            ])->withInput();
        }
    }

    public function manage_users_store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'class' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => ['required', Password::defaults()],
        ]);

        $data = [
            'username' => $request->username,
            'name' => $request->name,
            'class' => $request->class,
            'password' => Hash::make($request->password)
        ];

        if (Auth::user()->super_admin) {
            $data['is_admin'] = (bool) $request->is_admin;
            $data['super_admin'] = (bool) $request->is_super_admin;
        }

        $user = User::create($data);

        if ($user) {
            return redirect()->route('admin.manage.users')->with('message', 'Berhasil menambahkan user baru!');
        } else {
            return redirect()->route('admin.manage.users')->withErrors([
                'user' => 'Gagal menambahkan user baru!'
            ])->withInput();
        }
    }

    public function manage_users_destroy(User $user)
    {
        if ($user->delete()) {
            return redirect()->route('admin.manage.users')->with('message', 'Berhasil menghapus user!');
        } else {
            return redirect()->route('admin.manage.users')->withErrors([
                'user' => 'Gagal menghapus user!'
            ]);
        }
    }

    public function manage_users_toggle_activate(User $user)
    {
        $current_status = boolval($user->is_active);
        $user->is_active = !$current_status;
        $user->save();

        if ($current_status == true) {
            return "active";
        } else {
            return "not_active";
        }
    }

    public function manage_tests()
    {
        if (Auth::user()->super_admin) {
            $tests = Test::all();
        } else {
            $tests = [];

            foreach (Test::all()->toArray() as $test) {
                if (!in_array(Auth::id(), explode(',', $test['for']))) {
                    array_push($tests, (object) $test);
                }
            }
        }

        $data = [
            'title' => 'Manage Tests',
            'tests' => $tests
        ];

        return view('cbt.admin.manage.tests.index', $data);
    }

    public function manage_tests_show(Test $test)
    {
        $participants = [];
        $exploded_for = explode(',', $test->for);
        $test->for = '';

        foreach ($exploded_for as $for) {
            $participant = User::find($for);
            if ($participant) {
                array_push($participants, $participant);
                $test->for .= $participant->username . ', ';
            }
        }

        $test->quizzes = $test->quizzes;
        $test->for = rtrim($test->for, ", ");
        $test->participants = $participants;

        return $test;
    }

    public function manage_tests_create()
    {
        $data = [
            'title' => 'Create New Test'
        ];
        return view('cbt.admin.manage.tests.create', $data);
    }

    public function manage_tests_store(Request $request)
    {
        if ($this->time_larger_than($request->start_test, $request->end_test)) {
            $request->validate([
                'name' => 'required|string|max:255',
                'duration' => 'required|numeric|max:255',
                'start_test' => 'required|string|max:255|date',
                'end_test' => 'required|string|max:255',
                'maximal_point' => 'required|numeric|max:255',
            ]);

            $test = Test::create([
                'test_name' => $request->name,
                'duration' => $request->duration,
                'start_test' => $request->start_test,
                'end_test' => $request->end_test,
                'maximal_point' => $request->maximal_point
            ]);

            if ($test) {
                if ($request->fill_question) {
                    return redirect()->route('admin.manage.tests.questions.create', $test->id);
                }

                return redirect()->route('admin.manage.tests')->with('message', 'Berhasil menambahkan tes baru!');
            } else {
                return redirect()->route('admin.manage.tests.create')->withErrors([
                    'test' => 'Gagal menambahkan tes baru!'
                ])->withInput();
            }
        } else {
            return redirect()->route('admin.manage.tests.create')->withErrors([
                'start_test' => 'Waktu selesai harus lebih lama dari waktu mulai!'
            ])->withInput();
        }
    }

    public function manage_tests_edit(Test $test)
    {
        $data = [
            'title' => 'Edit Test',
            'test' => $test
        ];

        return view('cbt.admin.manage.tests.edit', $data);
    }

    public function manage_tests_update(Request $request)
    {
        $test = Test::find($request->id);
        if ($this->time_larger_than($request->start_test, $request->end_test)) {
            $request->validate([
                'name' => 'required|string|max:255',
                'duration' => 'required|numeric|max:255',
                'start_test' => 'required|string|max:255',
                'end_test' => 'required|string|max:255',
                'maximal_point' => 'required|numeric|max:255',
            ]);

            $test->test_name = $request->name;
            $test->duration = $request->duration;
            $test->start_test = $request->start_test;
            $test->end_test = $request->end_test;
            $test->end_test = $request->end_test;
            $test->maximal_point = $request->maximal_point;

            if ($test->save()) {
                return redirect()->route('admin.manage.tests')->with('message', 'Berhasil mengubah tes!');
            } else {
                return redirect()->route('admin.manage.tests')->withErrors([
                    'test' => 'Gagal mengubah tes!'
                ])->withInput();
            }
        } else {
            return redirect()->route('admin.manage.tests.edit', $test->id)->withErrors([
                'start_test' => 'Waktu selesai harus lebih lama dari waktu mulai!'
            ])->withInput();
        }
    }

    public function manage_tests_user(Test $test)
    {
        $participants = [];
        $exploded_for = explode(',', $test->for);

        for ($i = 0; $i < count($exploded_for); $i++) {
            $participant = User::find($exploded_for[$i]);
            if ($participant) {
                array_push($participants, $participant);
            }
        }

        $test->participants = $participants;

        $data = [
            'title' => 'Manage test participants',
            'test' => $test,
            'users' => User::all()
        ];
        return view('cbt.admin.manage.tests.user', $data);
    }

    public function manage_tests_user_save(Request $request, Test $test)
    {
        $participants = $request->participants;

        if ($participants) {
            $for = implode(',', $participants);
            $test->for = $for;
        } else {
            $test->for = $participants;
        }

        if ($test->save()) {
            return redirect()->route('admin.manage.tests')->with('message', 'Berhasil mengubah peserta tes!');
        } else {
            return redirect()->route('admin.manage.tests')->withErrors([
                'test' => 'Gagal mengubah peserta tes!'
            ])->withInput();
        }
    }

    public function manage_tests_destroy(Test $test)
    {
        if ($test->delete()) {
            return redirect()->route('admin.manage.tests')->with('message', 'Berhasil menghapus tes!');
        } else {
            return redirect()->route('admin.manage.tests')->withErrors([
                'test' => 'Gagal menghapus tes!'
            ])->withInput();
        }
    }

    public function manage_tests_questions(Test $test)
    {
        for ($i = 0; $i < count($test->quizzes); $i++) {
            $test->quizzes[$i]->choice = Choice::find($test->quizzes[$i]->choice_id) ?? '-';
        }

        $data = [
            'title' => 'Manage All Questions',
            'test' => $test
        ];

        return view('cbt.admin.manage.tests.questions.index', $data);
    }

    public function manage_tests_questions_show(Test $test, Quiz $quiz)
    {
        $data = [
            'test' => $test,
            'quiz' => $quiz,
            'choice' => Choice::find($quiz->choice_id),
            'choices' => Choice::where('quiz_id', '=', $quiz->id)->get()
        ];
        return $data;
    }

    public function manage_tests_questions_create(Test $test)
    {
        $data = [
            'title' => 'Create New Question',
            'test' => $test,
            'types' => Type::all()
        ];

        return view('cbt.admin.manage.tests.questions.create', $data);
    }

    public function manage_tests_questions_store(Request $request, Test $test)
    {
        $request->validate([
            'question' => 'required|string',
            'type' => 'required|string|max:255',
            'correct_answer' => 'string',
        ]);

        if ($request->choice && $request->answer) {
            return redirect()->route('admin.manage.tests.questions.create', $test->id)->withErrors([
                'choice' => 'Choose one between choice or long answer!'
            ])->withInput();
        } else if (!$request->choice && !$request->answer) {
            return redirect()->route('admin.manage.tests.questions.create', $test->id)->withErrors([
                'choice' => 'Insert one between choice or long answer!'
            ])->withInput();
        }

        $quiz = Quiz::create([
            'question' => $request->question,
            'type_id' => $request->type,
            'test_id' => $test->id,
            'correct_answer' => $request->answer,
        ]);

        if ($request->choices) {
            foreach ($request->choices as $available_choice) {
                Choice::create([
                    'quiz_id' => $quiz->id,
                    'value' => $available_choice
                ]);
            }

            $correct_choice = Choice::where([
                ['quiz_id', '=', $quiz->id],
                ['value', '=', $request->choice]
            ])->first();

            if ($correct_choice) {
                $quiz->choice_id = $correct_choice->id;
                $quiz->save();
            }
        }

        if ($quiz) {
            if ($request->fill_question) {
                return redirect()->route('admin.manage.tests.questions.create', $test->id);
            }

            return redirect()->route('admin.manage.tests.questions', $test->id)->with('message', 'Berhasil menambahkan pertanyaan baru!');
        } else {
            return redirect()->route('admin.manage.tests.questions', $test->id)->withErrors([
                'question' => 'Gagal menambahkan pertanyaan baru!'
            ]);
        }
    }

    public function manage_tests_questions_edit(Test $test, Quiz $quiz)
    {
        $data = [
            'title' => 'Create New Question',
            'test' => $test,
            'quiz' => $quiz,
            'types' => Type::all()
        ];

        return view('cbt.admin.manage.tests.questions.edit', $data);
    }

    public function manage_tests_questions_update(Request $request, Test $test, Quiz $quiz)
    {
        $request->validate([
            'question' => 'required|string',
            'type' => 'required|string|max:255',
            'correct_answer' => 'string',
        ]);

        if ($request->choice && $request->answer) {
            return redirect()->route('admin.manage.tests.questions.update', $test->id)->withErrors([
                'choice' => 'Choose one between choice or long answer!'
            ])->withInput();
        } else if (!$request->choice && !$request->answer) {
            return redirect()->route('admin.manage.tests.questions.update', $test->id)->withErrors([
                'choice' => 'Insert one between choice or long answer!'
            ])->withInput();
        }

        $quiz->question = $request->question;
        $quiz->type_id = $request->type;
        $quiz->test_id = $test->id;
        $quiz->correct_answer = $request->answer;

        if ($request->choices) {
            foreach ($request->old_choices as $old_choice) {
                $this_choice = Choice::where([
                    ['quiz_id', '=', $quiz->id],
                    ['value', '=', $old_choice]
                ])->first();

                if ($this_choice) {
                    $this_choice->delete();
                }
            }

            foreach ($request->choices as $available_choice) {
                Choice::create([
                    'quiz_id' => $quiz->id,
                    'value' => $available_choice
                ]);
            }

            $correct_choice = Choice::where([
                ['quiz_id', '=', $quiz->id],
                ['value', '=', $request->choice]
            ])->first();

            if ($correct_choice) {
                $quiz->choice_id = $correct_choice->id;
                $quiz->save();
            }
        }

        if ($quiz->save()) {
            return redirect()->route('admin.manage.tests.questions', $test->id)->with('message', 'Berhasil mengubah pertanyaan!');
        } else {
            return redirect()->route('admin.manage.tests.questions', $test->id)->withErrors([
                'user' => 'Gagal mengubah pertanyaan!'
            ])->withInput();
        }
    }

    public function manage_tests_questions_destroy(Test $test, Quiz $quiz)
    {
        if ($quiz->delete()) {
            return redirect()->route('admin.manage.tests.questions', $test->id)->with('message', 'Berhasil menghapus pertanyaan!');
        } else {
            return redirect()->route('admin.manage.tests.questions', $test->id)->withErrors([
                'test' => 'Gagal menghapus pertanyaan!'
            ])->withInput();
        }
    }

    public function manage_tests_results_by_users()
    {
        $data = [
            'title' => 'Results by Users',
            'users' => User::all()
        ];

        return view('cbt.admin.manage.results.by_users.index', $data);
    }

    public function manage_tests_results_by_users_show(User $user)
    {
        $data = [
            'title' => $user->username . '\'s Results',
            'user' => $user,
            'results' => $user->results
        ];

        return view('cbt.admin.manage.results.by_users.show', $data);
    }

    public function manage_tests_results_by_users_destroy(User $user)
    {
        foreach ($user->results as $result) {
            $result->delete();
        }

        $my_tests = [];

        if ($this->get_my_tests('tests', $user->id)) {
            foreach ($this->get_my_tests('id', $user->id) as $id) {
                array_push($my_tests, Test::find($id));
            }
        }

        foreach ($my_tests as $my_test) {
            $test = Test::find($my_test->id);
            $new_for = [];
            foreach (explode(',', $test->for) as $for) {
                if ($for != $user->id) {
                    array_push($new_for, $for);
                }
            }
            $test->for = implode(',', $new_for);
            $test->save();
        }

        foreach ($user->answers as $answer) {
            $answer->delete();
        }

        return redirect()->route('admin.manage.results.by_users')->with('message', 'Berhasil menghapus hasil!');
    }

    public function manage_tests_results_by_users_destroy_all()
    {
        $users = User::all();
        foreach ($users as $user) {
            foreach ($user->results as $result) {
                $result->delete();
            }

            $my_tests = [];

            if ($this->get_my_tests('tests', $user->id)) {
                foreach ($this->get_my_tests('id', $user->id) as $id) {
                    array_push($my_tests, Test::find($id));
                }
            }

            foreach ($my_tests as $my_test) {
                $test = Test::find($my_test->id);
                $new_for = [];
                foreach (explode(',', $test->for) as $for) {
                    if ($for != $user->id) {
                        array_push($new_for, $for);
                    }
                }
                $test->for = implode(',', $new_for);
                $test->save();
            }

            foreach ($user->answers as $answer) {
                $answer->delete();
            }
        }

        return redirect()->route('admin.manage.results.by_users')->with('message', 'Berhasil menghapus hasil!');
    }

    protected function get_my_tests($type, $user_id, $test_id = 0)
    {
        $filtered_tests = [];
        $is_for_me = false;

        foreach (Test::all()->toArray() as $test) {
            if (in_array($user_id, explode(',', $test['for']))) {
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
