<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\Type;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
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
        if ($user->is_admin == '1') {
            return redirect()->route('admin.manage.users')->with('error', 'User tidak ditemukan!');
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
        Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'name' => 'required|string|max:255',
            'class' => 'required|string|max:255',
            'password' => [Password::defaults()]
        ]);

        $user = User::find($request->id);

        $user->username = $request->username;
        $user->name = $request->name;
        $user->class = $request->class;

        if (Auth::user()->super_admin) {
            $user->is_admin = $request->is_admin;
            $user->super_admin = $request->is_super_admin;
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
            $data['is_admin'] = $request->is_admin;
            $data['super_admin'] = $request->is_super_admin;
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
                'user' => 'Gagal menghapus user baru!'
            ])->withInput();
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
                    $type = Type::find($test['type_id']);
                    $test['type'] = $type;
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

        $test->for = rtrim($test->for, ", ");
        $test->type = Type::find($test->type_id);
        $test->participants = $participants;

        return $test;
    }

    public function manage_tests_create()
    {
        $data = [
            'title' => 'Create New Test',
            'types' => Type::all()
        ];
        return view('cbt.admin.manage.tests.create', $data);
    }

    public function manage_tests_store(Request $request)
    {
        if ($this->time_larger_than($request->start_test, $request->end_test)) {
            $request->validate([
                'name' => 'required|string|max:255',
                'duration' => 'required|numeric|max:255',
                'type' => 'required|string|max:255',
                'start_test' => 'required|string|max:255|date',
                'end_test' => 'required|string|max:255',
                'basic_point' => 'required|numeric|max:255',
                'maximal_point' => 'required|numeric|max:255',
            ]);

            $test = Test::create([
                'test_name' => $request->name,
                'duration' => $request->duration,
                'type_id' => $request->type,
                'start_test' => $request->start_test,
                'basic_point' => $request->basic_point,
                'end_test' => $request->end_test,
                'maximal_point' => $request->maximal_point
            ]);

            if ($test) {
                return redirect()->route('admin.manage.tests')->with('message', 'Berhasil menambahkan tes baru!');
            } else {
                return redirect()->route('admin.manage.tests.create')->withErrors([
                    'test' => 'Gagal menambahkan tes baru!'
                ])->withInput();
            }
        } else {
            return redirect()->route('admin.manage.tests.create')->withErrors([
                'start_test' => 'Waktu mulai harus lebih lama dari waktu selesai!'
            ])->withInput();
        }
    }

    public function manage_tests_edit(Test $test)
    {
        $data = [
            'title' => 'Edit Test',
            'test' => $test,
            'types' => Type::all()
        ];

        return view('cbt.admin.manage.tests.edit', $data);
    }

    public function manage_tests_update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'duration' => 'required|numeric|max:255',
            'type' => 'required|string|max:255',
            'start_test' => 'required|string|max:255',
            'end_test' => 'required|string|max:255',
            'basic_point' => 'required|numeric|max:255',
            'maximal_point' => 'required|numeric|max:255',
        ]);

        $test = Test::find($request->id);

        $test->test_name = $request->name;
        $test->duration = $request->duration;
        $test->type_id = $request->type;
        $test->start_test = $request->start_test;
        $test->end_test = $request->end_test;
        $test->end_test = $request->end_test;
        $test->basic_point = $request->basic_point;
        $test->maximal_point = $request->maximal_point;

        if ($test->save()) {
            return redirect()->route('admin.manage.tests')->with('message', 'Berhasil mengubah tes!');
        } else {
            return redirect()->route('admin.manage.tests')->withErrors([
                'test' => 'Gagal mengubah tes!'
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
}
