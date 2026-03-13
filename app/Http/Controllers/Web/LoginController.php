<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function login(): View
    {
        return view('login');
    }

    public function ingresar(Request $request)
    {
        return response()->json([
            'login' => $request->input('login'),
            'password' => $request->input('password'),
        ]);
    }

    public function authGoogle(Request $request)
    {
        return response()->json([
            'message' => 'Parámetros recibidos de Google OAuth',
            'data' => $request->all(),
        ]);
    }
}
