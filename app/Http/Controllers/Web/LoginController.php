<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function login(): View
    {
        return view('login');
    }

    public function ingresar(Request $request)
    {
        return response()->json([
            'login' => $request->input('login'),
            'password' => $request->input('password'),
        ]);
    }

    public function authGoogleStatus(Request $request): JsonResponse
    {
        $googleUser = $request->session()->get('google_user');

        return response()->json([
            'ok' => true,
            'is_logged_in' => ! empty($googleUser),
            'user' => $googleUser,
        ]);
    }

    public function authGoogle(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'credential' => ['required', 'string'],
        ]);

        try {
            $tokenInfo = Http::asForm()
                ->timeout(10)
                ->post('https://oauth2.googleapis.com/tokeninfo', [
                    'id_token' => $validated['credential'],
                ])
                ->throw()
                ->json();
        } catch (RequestException $exception) {
            return response()->json([
                'ok' => false,
                'message' => 'No se pudo validar el token de Google',
                'error' => app()->hasDebugModeEnabled() ? $exception->getMessage() : null,
            ], 422);
        }

        $clientIdConfigurado = (string) config('services.google.client_id', '');
        $tokenAudience = (string) ($tokenInfo['aud'] ?? '');
        $email = (string) ($tokenInfo['email'] ?? '');
        $emailVerificado = filter_var($tokenInfo['email_verified'] ?? false, FILTER_VALIDATE_BOOLEAN);

        if ($clientIdConfigurado !== '' && $tokenAudience !== $clientIdConfigurado) {
            return response()->json([
                'ok' => false,
                'message' => 'El token no pertenece a esta aplicación',
            ], 422);
        }

        if ($email === '' || ! $emailVerificado) {
            return response()->json([
                'ok' => false,
                'message' => 'La cuenta de Google no tiene email verificado',
            ], 422);
        }

        $respuestaLogin = Usuario::validarLoginGoogle($email);
        $usuarioSistema = $respuestaLogin->data ?? null;

        $googleUser = [
            'sub' => $tokenInfo['sub'] ?? null,
            'email' => $email,
            'email_verified' => $emailVerificado,
            'name' => $tokenInfo['name'] ?? null,
            'given_name' => $tokenInfo['given_name'] ?? null,
            'family_name' => $tokenInfo['family_name'] ?? null,
            'picture' => $tokenInfo['picture'] ?? null,
        ];

        $request->session()->put('google_user', $googleUser);

        return response()->json([
            'ok' => true,
            'message' => 'Google OAuth validado correctamente',
            'is_registered' => ! empty($usuarioSistema),
            'google_user' => $googleUser,
            'system_user' => $usuarioSistema,
        ]);
    }
}
