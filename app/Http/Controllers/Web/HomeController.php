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

        if (! $request->session()->has('session_pool')) {
            $request->session()->put('session_pool', [
                'initialized_at' => now()->toIso8601String(),
                'current_session_id' => $request->session()->getId(),
                'login_events' => [],
                'transaction_events' => [],
            ]);
        }

        return view('home');
    }
}
