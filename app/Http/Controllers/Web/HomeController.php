<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function home(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('usuario')) {
            return redirect()->route('login');
        }

        $sesionUsuario = $request->session()->get('usuario');

        return view('home', compact('sesionUsuario'));
    }

    public function homePrueba(): View
    {
        return view('home');
    }
}
