<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function ingresar(Request $request): JsonResponse
    {
        $login = (string) $request->input('login', '');
        $password = (string) $request->input('password', '');

        $respuestaLogin = Usuario::validarLogin($login);

        if (($respuestaLogin->codigo ?? null) !== 200 || empty($respuestaLogin->data)) {
            return response()->json([
                'codigo' => $respuestaLogin->codigo ?? 408,
                'mensaje' => $respuestaLogin->mensaje ?? 'login no existe',
                'data' => [],
            ]);
        }

        $passwordGuardado = $respuestaLogin->data['credencial']['password_hash'] ?? '';

        if (! $this->coincidePassword($passwordGuardado, $password)) {
            return response()->json([
                'codigo' => 308,
                'mensaje' => 'password incorrecto',
                'data' => [],
            ]);
        }

        return response()->json([
            'codigo' => 200,
            'mensaje' => 'login correcto',
            'data' => $respuestaLogin->data,
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
