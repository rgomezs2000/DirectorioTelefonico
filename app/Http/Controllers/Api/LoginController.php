<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\ApiToken;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function ingresar(Request $request): JsonResponse
    {
        $validacionToken = Helper::validarTokenHeader();

        if (($validacionToken->codigo ?? null) !== 200) {
            return response()->json([
                'codigo' => $validacionToken->codigo ?? 309,
                'mensaje' => $validacionToken->mensaje ?? 'Token Incorrecto',
                'data' => [],
            ]);
        }

        $tokenConsumido = (string) ($validacionToken->data['token'] ?? '');
        if ($tokenConsumido !== '') {
            ApiToken::tokenUsado($tokenConsumido);
        }

        $nuevoToken = ApiToken::obtenerToken();

        $login = (string) $request->input('login', '');
        $password = (string) $request->input('password', '');

        $respuestaLogin = Usuario::validarLogin($login);

        if (($respuestaLogin->codigo ?? null) !== 200 || empty($respuestaLogin->data)) {
            return response()->json([
                'codigo' => $respuestaLogin->codigo ?? 408,
                'mensaje' => $respuestaLogin->mensaje ?? 'login no existe',
                'data' => [
                    'token' => $nuevoToken->api_token,
                ],
            ]);
        }

        $passwordGuardado = $respuestaLogin->data['credencial']['password_hash'] ?? '';

        if (! $this->coincidePassword($passwordGuardado, $password)) {
            return response()->json([
                'codigo' => 308,
                'mensaje' => 'password incorrecto',
                'data' => [
                    'token' => $nuevoToken->api_token,
                ],
            ]);
        }

        return response()->json([
            'codigo' => 200,
            'mensaje' => 'login correcto',
            'data' => [
                'usuario' => $respuestaLogin->data,
                'token' => $nuevoToken->api_token,
            ],
        ]);
    }

    private function coincidePassword(string $passwordGuardado, string $passwordIngresado): bool
    {
        if ($passwordGuardado === '' || $passwordIngresado === '') {
            return false;
        }

        if (Hash::check($passwordIngresado, $passwordGuardado)) {
            return true;
        }

        try {
            $passwordPlano = Crypt::decryptString($passwordGuardado);

            return hash_equals($passwordPlano, $passwordIngresado);
        } catch (\Throwable) {
            return false;
        }
    }
}
