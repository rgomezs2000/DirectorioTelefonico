<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaisController extends Controller
{
    public function gestionarPais(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('usuario')) {
            return redirect()->route('login');
        }

        return view('paises');
    }
}
