<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Credencial;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function ingresar(Request $request): JsonResponse
    {
        $bearerToken = (string) $request->bearerToken();
        $validacionToken = Helper::validarTokenHeader($bearerToken);

        if (($validacionToken['codigo'] ?? 306) !== 200) {
            return response()->json($validacionToken);
        }

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

        $idUsuario = (int) ($respuestaLogin->data['id_usuario'] ?? 0);
        $passwordGuardado = $respuestaLogin->data['credencial']['password_hash'] ?? '';

        $resultadoIntento = $this->coincidePassword($idUsuario, $passwordGuardado, $password);

        if ($resultadoIntento['fallido']) {
            return response()->json([
                'codigo' => ($resultadoIntento['intentos'] ?? 0) >= 3 ? 309 : 308,
                'mensaje' => $resultadoIntento['mensaje'] ?? 'Contraseña incorrecta',
                'data' => [],
            ]);
        }

        return response()->json([
            'codigo' => 200,
            'mensaje' => 'login correcto',
            'data' => $respuestaLogin->data,
        ]);
    }

    private function coincidePassword(int $idUsuario, string $passwordGuardado, string $passwordIngresado): array
    {
        if ($idUsuario <= 0 || $passwordGuardado === '' || $passwordIngresado === '') {
            return [
                'intentos' => 1,
                'fallido'  => true,
                'mensaje'  => 'Contraseña incorrecta. Te queda 2 intentos',
            ];
        }

        return Credencial::bloqueoIntento($idUsuario, $passwordIngresado);
    }
}
