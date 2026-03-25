<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;

class LougoutController extends Controller
{
    public function lougout(Request $request): RedirectResponse|JsonResponse
    {
        try {
            $sesionUsuario = $request->session()->get('usuario');

            if (isset($sesionUsuario->id_usuario)) {
                Usuario::setUltimoAcceso((int) $sesionUsuario->id_usuario);
            }
        } catch (Throwable $exception) {
            return response()->json([
                'codigo' => 500,
                'mensaje' => 'Error del servidor',
                'error' => Str::limit(trim((string) $exception->getMessage()) ?: 'Error inesperado', 120),
            ], 500);
        } finally {
            if ($request->session()->isStarted()) {
                $request->session()->flush();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }
        }

        return redirect()->route('home');
    }
}
