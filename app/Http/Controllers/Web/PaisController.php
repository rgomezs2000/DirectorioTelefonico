<?php

namespace App\Http\Controllers\Web;

use App\Helpers\Api;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\View\View;

class PaisController extends Controller
{
    public function gestionarPais(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('usuario')) {
            return redirect()->route('login');
        }

        $resultado = $this->obtenerPaisesDesdeApi($request);
        $rows = Arr::wrap(data_get($resultado, 'json.data', []));

        return view('paises', [
            'datatableConfig' => Helper::construirConfiguracionDatatable($rows),
        ]);
    }

    public function listarPaises(Request $request, ?string $campo = null, ?string $palabra = null): JsonResponse
    {
        $resultado = $this->obtenerPaisesDesdeApi($request, $campo, $palabra);
        $rows = Arr::wrap(data_get($resultado, 'json.data', []));

        return response()->json([
            'codigo' => (int) data_get($resultado, 'json.codigo', data_get($resultado, 'status', 500)),
            'mensaje' => (string) data_get($resultado, 'json.mensaje', 'Sin respuesta'),
            'data' => Helper::construirConfiguracionDatatable($rows),
        ], (int) data_get($resultado, 'status', 500));
    }

    private function obtenerPaisesDesdeApi(Request $request, ?string $campo = null, ?string $palabra = null): array
    {
        $token = Helper::obtenerBearerTokenDesdeSesion($request);

        if ($token === '') {
            return [
                'status' => 500,
                'json' => [
                    'codigo' => 500,
                    'mensaje' => 'No fue posible obtener el bearer token',
                    'data' => [],
                ],
            ];
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

        $endpoint = '/api/maestros/paises/lista_paises';
        $parametros = array_values(array_filter([
            $campo !== null && trim($campo) !== '' ? trim($campo) : null,
            $palabra !== null && trim($palabra) !== '' ? trim($palabra) : null,
        ]));

        if ($parametros !== []) {
            $endpoint .= '/' . implode('/', array_map(static fn (string $valor): string => rawurlencode($valor), $parametros));
        }

        $respuesta = null;

        foreach ($baseUrls as $baseUrl) {
            $url = $baseUrl . $endpoint;
            $intento = Api::initAPI($url, 'GET', null, $token);

            if (($intento['status'] ?? 500) === 404) {
                continue;
            }

            $respuesta = $intento;
            break;
        }

        // Desecha token independientemente del resultado de la consulta.
        Helper::obtenerBearerTokenDesdeSesion($request, true);

        if ($respuesta === null) {
            return [
                'status' => 500,
                'json' => [
                    'codigo' => 500,
                    'mensaje' => 'No se encontró un endpoint válido para listar países',
                    'data' => [],
                ],
            ];
        }

        return $respuesta;
    }
}
