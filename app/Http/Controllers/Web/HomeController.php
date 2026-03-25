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
            if (! $request->session()->isStarted()) {
                $request->session()->start();
            }

            return redirect()->route('login');
        }

        return view('home');
    }

    public function homePrueba(): View
    {
        return view('home');
    }
}
