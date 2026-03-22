<?php

namespace App\Http\Controllers\Web;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class LoginController extends Controller
{
    public function login(): View
    {
        return view('login');
    }

    public function ingresar(Request $request): JsonResponse
    {
        try {
            $token = Helper::obtenerBearerTokenDesdeSesion($request);

            if ($token === '') {
                return response()->json([
                    'codigo' => 500,
                    'mensaje' => 'No fue posible obtener el bearer token',
                    'data' => [],
                ], 500);
            }

            $url = rtrim(config('app.url', ''), '/') . '/api/login/ingresar';

            if ($url === '/api/login/ingresar') {
                return response()->json([
                    'codigo' => 500,
                    'mensaje' => 'URL de API inválida',
                    'data' => [],
                ], 500);
            }

            $respuesta = Http::acceptJson()
                ->withToken($token)
                ->post($url, [
                    'login' => (string) $request->input('login', ''),
                    'password' => (string) $request->input('password', ''),
                ]);

            $codigoRespuesta = (int) ($respuesta->json('codigo') ?? 0);
            if (in_array($codigoRespuesta, [309, 310, 311], true)) {
                $token = Helper::obtenerBearerTokenDesdeSesion($request, true);

                if ($token !== '') {
                    $respuesta = Http::acceptJson()
                        ->withToken($token)
                        ->post($url, [
                            'login' => (string) $request->input('login', ''),
                            'password' => (string) $request->input('password', ''),
                        ]);
                }
            }

            return response()->json($respuesta->json(), $respuesta->status());
        } catch (Throwable $exception) {
            return response()->json([
                'codigo' => 500,
                'mensaje' => 'Error interno del servidor',
                'error' => app()->hasDebugModeEnabled()
                    ? $exception->getMessage()
                    : 'Ocurrió un error inesperado',
            ], 500);
        }
    }

    public function authGoogle(Request $request)
    {
        return response()->json([
            'message' => 'Parámetros recibidos de Google OAuth',
            'data' => $request->all(),
        ]);
    }
}
