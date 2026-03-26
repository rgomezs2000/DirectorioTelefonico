<?php

namespace App\Http\Controllers\Web;

use App\Helpers\Api;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Sesion;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class LoginController extends Controller
{
    public function login(Request $request): View
    {
        if (! $request->session()->isStarted()) {
            $userAgent = $request->userAgent();

            // Invoca el helper para obtener el dispositivo a traves del user agent
            $dispositivo = Helper::obtenerDispositivoSesion($userAgent);

            $request->session()->start();
            $request->session()->put('session', (object) [
                'token_sesion' => $request->session()->getId(),
                'ip_origen'    => $request->ip(),
                'user_agent'  => $userAgent, // Guardará todo el agente"
                'dispositivo'  => $dispositivo, // Guardará el dispositivo"
            ]);
        }

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
                    $request->root(),
                    $request->getSchemeAndHttpHost() . $request->getBaseUrl(),
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

                $intento = Api::initAPI($url, 'POST', $payload, $token);

                if (($intento['status'] ?? 500) === 404) {
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

            $codigoRespuesta = (int) (($respuesta['json']['codigo'] ?? 0));
            if (in_array($codigoRespuesta, [309, 310, 311], true)) {
                $token = Helper::obtenerBearerTokenDesdeSesion($request, true);

                if ($token !== '') {
                    foreach ($baseUrls as $baseUrl) {
                        $url = $baseUrl . '/api/login/ingresar';

                        $intento = Api::initAPI($url, 'POST', $payload, $token);

                        if (($intento['status'] ?? 500) === 404) {
                            continue;
                        }

                        $respuesta = $intento;
                        break;
                    }
                }
            }

            $jsonRespuesta = $respuesta['json'] ?? [];
            $codigoRespuesta = (int) ($jsonRespuesta['codigo'] ?? 0);
            $statusRespuesta = (int) ($respuesta['status'] ?? 0);
            $mensajeRespuesta = mb_strtolower((string) ($jsonRespuesta['mensaje'] ?? ''));
            $loginExitoso = $codigoRespuesta === 200
                || $statusRespuesta === 200
                || str_contains($mensajeRespuesta, 'exitoso');

            if ($loginExitoso && isset($jsonRespuesta['data']['usuario']) && is_array($jsonRespuesta['data']['usuario'])) {
                $usuario = $jsonRespuesta['data']['usuario'];

                if (
                    isset($usuario['credencial'])
                    && is_array($usuario['credencial'])
                    && isset($usuario['credencial']['password_hash'])
                    && is_string($usuario['credencial']['password_hash'])
                    && $usuario['credencial']['password_hash'] !== ''
                ) {
                    try {
                        $usuario['credencial']['password_hash'] = Crypt::decryptString($usuario['credencial']['password_hash']);
                    } catch (Throwable) {
                        // Si no se puede descifrar, se conserva el valor original.
                    }
                }

                $sesion = (object) $request->session()->get('session', (object) [
                    'token_sesion' => $request->session()->getId(),
                    'ip_origen' => (string) $request->ip(),
                    'user_agent' => (string) $request->userAgent(),
                    'dispositivo' => (string) Helper::obtenerDispositivoSesion($request->userAgent()),
                ]);
                $idUsuarioSesion = (int) ($usuario['usuario'] ?? $usuario['id_usuario'] ?? 0);
                $idSesion = $idUsuarioSesion > 0
                    ? Sesion::registrarSesion($idUsuarioSesion, $sesion)
                    : null;

                if ($idSesion !== null) {
                    $sesion->id_sesion = $idSesion;
                    $request->session()->put('session', $sesion);
                }

                $request->session()->put('usuario', (object) $usuario);
            }

            return response()->json($jsonRespuesta, (int) ($respuesta['status'] ?? 500));
        } catch (Throwable $exception) {
            return response()->json([
                'codigo' => 500,
                'mensaje' => 'Error del servidor',
                'error' => Str::limit(trim((string) $exception->getMessage()) ?: 'Error inesperado', 120),
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
        try {
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

            $tokenInfo = Http::asForm()
                ->timeout(10)
                ->post('https://oauth2.googleapis.com/tokeninfo', [
                    'id_token' => $idToken,
                ])
                ->throw()
                ->json();

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
                    $request->root(),
                    $request->getSchemeAndHttpHost() . $request->getBaseUrl(),
                    (string) config('app.url', ''),
                    (string) url('/'),
                ]
            ))));

            $payload = [
                'email' => $email,
            ];

            $respuesta = null;
            foreach ($baseUrls as $baseUrl) {
                $url = $baseUrl . '/api/login/auth_google';

                $intento = Api::initAPI($url, 'POST', $payload, $token);

                if (($intento['status'] ?? 500) === 404) {
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

            $codigoRespuesta = (int) (($respuesta['json']['codigo'] ?? 0));
            if (in_array($codigoRespuesta, [309, 310, 311], true)) {
                $token = Helper::obtenerBearerTokenDesdeSesion($request, true);

                if ($token !== '') {
                    foreach ($baseUrls as $baseUrl) {
                        $url = $baseUrl . '/api/login/auth_google';

                        $intento = Api::initAPI($url, 'POST', $payload, $token);

                        if (($intento['status'] ?? 500) === 404) {
                            continue;
                        }

                        $respuesta = $intento;
                        break;
                    }
                }
            }

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

            $jsonRespuesta = $respuesta['json'] ?? [];
            $codigoRespuesta = (int) ($jsonRespuesta['codigo'] ?? 0);
            $statusRespuesta = (int) ($respuesta['status'] ?? 0);
            $mensajeRespuesta = mb_strtolower((string) ($jsonRespuesta['mensaje'] ?? ''));
            $loginExitoso = $codigoRespuesta === 200
                || $statusRespuesta === 200
                || str_contains($mensajeRespuesta, 'exitoso');

            if ($loginExitoso && isset($jsonRespuesta['data']['usuario']) && is_array($jsonRespuesta['data']['usuario'])) {
                $usuario = $jsonRespuesta['data']['usuario'];

                if (
                    isset($usuario['credencial'])
                    && is_array($usuario['credencial'])
                    && isset($usuario['credencial']['password_hash'])
                    && is_string($usuario['credencial']['password_hash'])
                    && $usuario['credencial']['password_hash'] !== ''
                ) {
                    try {
                        $usuario['credencial']['password_hash'] = Crypt::decryptString($usuario['credencial']['password_hash']);
                    } catch (Throwable) {
                        // Si no se puede descifrar, se conserva el valor original.
                    }
                }

                $sesion = (object) $request->session()->get('session', (object) [
                    'token_sesion' => $request->session()->getId(),
                    'ip_origen' => (string) $request->ip(),
                    'user_agent' => (string) $request->userAgent(),
                    'dispositivo' => (string) Helper::obtenerDispositivoSesion($request->userAgent()),
                ]);
                $idUsuarioSesion = (int) ($usuario['usuario'] ?? $usuario['id_usuario'] ?? 0);
                $idSesion = $idUsuarioSesion > 0
                    ? Sesion::registrarSesion($idUsuarioSesion, $sesion)
                    : null;

                if ($idSesion !== null) {
                    $sesion->id_sesion = $idSesion;
                    $request->session()->put('session', $sesion);
                }

                $request->session()->put('usuario', (object) $usuario);
            }

            return response()->json($jsonRespuesta, (int) ($respuesta['status'] ?? 500));
        } catch (RequestException $exception) {
            return response()->json([
                'ok' => false,
                'message' => 'No se pudo validar el token de Google',
                'error' => app()->hasDebugModeEnabled() ? $exception->getMessage() : null,
            ], 422);
        } catch (Throwable $exception) {
            return response()->json([
                'codigo' => 500,
                'mensaje' => 'Error del servidor',
                'error' => Str::limit(trim((string) $exception->getMessage()) ?: 'Error inesperado', 120),
            ], 500);
        }
    }
}
