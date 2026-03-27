<?php

namespace App\Http\Controllers\Web;

use App\Helpers\Api;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
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
            $baseUrls = array_values(array_unique(array_filter(array_map(
                static fn (string $url): string => rtrim($url, '/'),
                [
                    $request->root(),
                    $request->getSchemeAndHttpHost() . $request->getBaseUrl(),
                    (string) config('app.url', ''),
                    (string) url('/'),
                ]
            ))));

            if (isset($sesionUsuario->id_usuario)) {
                $tokenUltimoAcceso = Helper::obtenerBearerTokenDesdeSesion($request, true);
                if ($tokenUltimoAcceso !== '') {
                    foreach ($baseUrls as $baseUrl) {
                        $url = $baseUrl.'/api/logout/ultimo_acceso/{id_usuario}';
                        $respuesta = Api::initAPI($url, 'POST', null, $tokenUltimoAcceso, (int) $sesionUsuario->id_usuario);

                        if (($respuesta['status'] ?? 500) !== 404) {
                            break;
                        }
                    }
                }
            }

            $sesionActual = $request->session()->get('session');

            if (isset($sesionUsuario->id_usuario, $sesionActual->id_sesion)) {
                $tokenCerrarSesion = Helper::obtenerBearerTokenDesdeSesion($request, true);
                if ($tokenCerrarSesion !== '') {
                    foreach ($baseUrls as $baseUrl) {
                        $url = $baseUrl.'/api/logout/{id_usuario}/{id_sesion}';
                        $respuesta = Api::initAPI(
                            $url,
                            'POST',
                            null,
                            $tokenCerrarSesion,
                            [(int) $sesionUsuario->id_usuario, (int) $sesionActual->id_sesion]
                        );

                        if (($respuesta['status'] ?? 500) !== 404) {
                            break;
                        }
                    }
                }
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
