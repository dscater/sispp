<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $usuario = $request->usuario;
        $password = $request->password;
        $res = Auth::attempt(['usuario' => $usuario, 'password' => $password]);
        if ($res) {
            return response()->JSON([
                'user' => Auth::user(),
            ], 200);
        }

        return response()->JSON([], 401);
    }

    public function logout()
    {
        Auth::logout();
        return response()->JSON(['code' => 204], 204);
    }
}
