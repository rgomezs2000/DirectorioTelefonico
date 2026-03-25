<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;

class LoginController extends Controller
{
    public function ingresar(Request $request): JsonResponse
    {
        try {
            $validacionToken = Helper::validarTokenHeader();

            if (($validacionToken->codigo ?? null) !== 200) {
                return response()->json([
                    'codigo' => $validacionToken->codigo ?? 309,
                    'mensaje' => $validacionToken->mensaje ?? 'Token Incorrecto',
                    'data' => [],
                ]);
            }

            $login = (string) $request->input('login', '');
            $password = (string) $request->input('password', '');

            $respuestaLogin = Usuario::validarLogin($login, $password);

            if (($respuestaLogin->codigo ?? null) !== 200 || empty($respuestaLogin->data)) {
                return response()->json([
                    'codigo' => $respuestaLogin->codigo ?? 408,
                    'mensaje' => $respuestaLogin->mensaje ?? 'Credenciales inválidas',
                    'data' => [],
                ]);
            }

            $tokenHeader = (string) ($request->bearerToken()
                ?: $request->header('X-Api-Token', $request->header('api-token', '')));
            Helper::tokenUsado($tokenHeader);

            return response()->json([
                'codigo' => 200,
                'mensaje' => 'login correcto',
                'data' => [
                    'usuario' => $respuestaLogin->data,
                ],
            ]);
        } catch (Throwable $exception) {
            return response()->json([
                'codigo' => 500,
                'mensaje' => 'Error del servidor',
                'error' => Str::limit(trim((string) $exception->getMessage()) ?: 'Error inesperado', 120),
            ], 500);
        }
    }

    public function authGoogle(Request $request): JsonResponse
    {
        try {
            $validacionToken = Helper::validarTokenHeader();

            if (($validacionToken->codigo ?? null) !== 200) {
                return response()->json([
                    'codigo' => $validacionToken->codigo ?? 309,
                    'mensaje' => $validacionToken->mensaje ?? 'Token Incorrecto',
                    'data' => [],
                ]);
            }

            $email = (string) $request->input('email', '');
            $respuestaLogin = Usuario::validarLoginGoogle($email);

            if (($respuestaLogin->codigo ?? null) !== 200 || empty($respuestaLogin->data)) {
                return response()->json([
                    'codigo' => $respuestaLogin->codigo ?? 408,
                    'mensaje' => $respuestaLogin->mensaje ?? 'Credenciales inválidas',
                    'data' => [],
                ]);
            }

            $tokenHeader = (string) ($request->bearerToken()
                ?: $request->header('X-Api-Token', $request->header('api-token', '')));
            Helper::tokenUsado($tokenHeader);

            return response()->json([
                'codigo' => 200,
                'mensaje' => 'login correcto',
                'data' => [
                    'usuario' => $respuestaLogin->data,
                ],
            ]);
        } catch (Throwable $exception) {
            return response()->json([
                'codigo' => 500,
                'mensaje' => 'Error del servidor',
                'error' => Str::limit(trim((string) $exception->getMessage()) ?: 'Error inesperado', 120),
            ], 500);
        }
    }
}
