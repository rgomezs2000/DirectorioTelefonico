<?php

namespace App\Http\Controllers\Web;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

            $baseUrls = array_values(array_unique(array_filter(array_map(
                static fn (string $url): string => rtrim($url, '/'),
                [
                    $request->getSchemeAndHttpHost(),
                    (string) config('app.url', ''),
                    (string) url('/'),
                ]
            ))));

            $payload = [
                'login' => (string) $request->input('login', ''),
                'password' => (string) $request->input('password', ''),
            ];

            $respuesta = null;
            foreach ($baseUrls as $baseUrl) {
                $url = $baseUrl . '/api/login/ingresar';

                $intento = Http::acceptJson()
                    ->withToken($token)
                    ->post($url, $payload);

                if ($intento->status() === 404) {
                    continue;
                }

                $respuesta = $intento;
                break;
            }

            if ($respuesta === null) {
                return response()->json([
                    'codigo' => 500,
                    'mensaje' => 'No se encontró un endpoint válido para login API',
                    'data' => [],
                ], 500);
            }

            $codigoRespuesta = (int) ($respuesta->json('codigo') ?? 0);
            if (in_array($codigoRespuesta, [309, 310, 311], true)) {
                $token = Helper::obtenerBearerTokenDesdeSesion($request, true);

                if ($token !== '') {
                    foreach ($baseUrls as $baseUrl) {
                        $url = $baseUrl . '/api/login/ingresar';

                        $intento = Http::acceptJson()
                            ->withToken($token)
                            ->post($url, $payload);

                        if ($intento->status() === 404) {
                            continue;
                        }

                        $respuesta = $intento;
                        break;
                    }
                }
            }

            $jsonRespuesta = $respuesta->json();
            if (! is_array($jsonRespuesta)) {
                return response()->json([
                    'codigo' => $respuesta->status(),
                    'mensaje' => 'La API devolvió una respuesta no JSON',
                    'data' => [],
                ], $respuesta->status());
            }

            return response()->json($jsonRespuesta, $respuesta->status());
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

    public function googleStatus(Request $request): JsonResponse
    {
        $googleUser = $request->session()->get('google_user');

        return response()->json([
            'ok' => true,
            'is_logged_in' => ! empty($googleUser),
            'user' => $googleUser,
        ]);
    }

    /**
     * Compatibilidad con implementaciones anteriores.
     */
    public function authGoogleStatus(Request $request): JsonResponse
    {
        return $this->googleStatus($request);
    }

    public function authGoogle(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'credential' => ['nullable', 'string', 'required_without:id_token'],
            'id_token' => ['nullable', 'string', 'required_without:credential'],
        ]);

        $idToken = (string) ($validated['credential'] ?? $validated['id_token'] ?? '');

        if ($idToken === '') {
            return response()->json([
                'ok' => false,
                'message' => 'Google OAuth no envió id_token válido',
            ], 422);
        }

        try {
            $tokenInfo = Http::asForm()
                ->timeout(10)
                ->post('https://oauth2.googleapis.com/tokeninfo', [
                    'id_token' => $idToken,
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
