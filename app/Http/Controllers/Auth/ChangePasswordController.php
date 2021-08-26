<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class ChangePasswordController extends Controller
{
    public function change(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'password_lama' => 'required|current_password:web',
                'password_baru' => ['required', Password::defaults()],
                'konfirmasi_password' => 'required|same:password_baru'
            ]
        );

        if ($validator->fails()) {
            $response['result'] = false;
            $response['message'] = $validator->messages();
            return response()->json($response);
        }

        $user = $request->user()->fill([
            'password' => Hash::make($request->password_baru)
        ]);

        if ($user->save()) {
            $response['result'] = true;
            $response['message'] = "Password telah diubah!";
            return response()->json($response);
        }
    }
}
