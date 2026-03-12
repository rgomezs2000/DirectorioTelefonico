<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class SwaggerController extends Controller
{
    public function ui(): View
    {
        return view('swagger-ui');
    }

    public function spec(Request $request): JsonResponse
    {
        $paths = [];

        foreach (Route::getRoutes() as $route) {
            $uri = $route->uri();

            if (! str_starts_with($uri, 'api/')) {
                continue;
            }

            if (in_array($uri, ['api/openapi.json'], true)) {
                continue;
            }

            $path = '/'.$uri;
            $methods = array_values(array_diff($route->methods(), ['HEAD']));

            foreach ($methods as $method) {
                $lowerMethod = strtolower($method);

                $paths[$path][$lowerMethod] = [
                    'tags' => ['API'],
                    'summary' => $this->buildSummary($method, $uri),
                    'operationId' => $this->buildOperationId($method, $uri),
                    'responses' => [
                        '200' => [
                            'description' => 'OK',
                        ],
                        '422' => [
                            'description' => 'Validation error',
                        ],
                    ],
                ];
            }
        }

        ksort($paths);

        return response()->json([
            'openapi' => '3.0.3',
            'info' => [
                'title' => config('app.name').' API',
                'description' => 'Documentación automática de endpoints disponibles bajo /api.',
                'version' => '1.0.0',
            ],
            'servers' => [
                [
                    'url' => $request->getSchemeAndHttpHost(),
                    'description' => 'Servidor actual',
                ],
            ],
            'paths' => $paths,
        ]);
    }

    private function buildSummary(string $method, string $uri): string
    {
        return sprintf('%s %s', strtoupper($method), '/'.$uri);
    }

    private function buildOperationId(string $method, string $uri): string
    {
        return strtolower($method).'_'.str_replace(['/', '{', '}'], ['_', '', ''], $uri);
    }
}
