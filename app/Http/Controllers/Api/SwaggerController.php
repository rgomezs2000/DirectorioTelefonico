<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
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
                $endpointDoc = $this->endpointDocumentation($lowerMethod, $uri);

                $paths[$path][$lowerMethod] = [
                    'tags' => ['API'],
                    'summary' => $this->buildSummary($method, $uri),
                    'operationId' => $this->buildOperationId($method, $uri),
                    'parameters' => $this->pathParameters($uri),
                    'responses' => $endpointDoc['responses'] ?? $this->defaultResponses(),
                ];

                if (isset($endpointDoc['requestBody'])) {
                    $paths[$path][$lowerMethod]['requestBody'] = $endpointDoc['requestBody'];
                }
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

    private function pathParameters(string $uri): array
    {
        preg_match_all('/\{([^}]+)\}/', $uri, $matches);

        return collect($matches[1] ?? [])->map(function (string $parameter) {
            return [
                'name' => $parameter,
                'in' => 'path',
                'required' => true,
                'schema' => [
                    'type' => 'string',
                ],
                'description' => 'Parámetro de ruta',
            ];
        })->values()->all();
    }

    private function defaultResponses(): array
    {
        return [
            '200' => [
                'description' => 'OK',
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'type' => 'object',
                            'additionalProperties' => true,
                        ],
                    ],
                ],
            ],
            '422' => [
                'description' => 'Validation error',
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'type' => 'object',
                            'additionalProperties' => true,
                        ],
                    ],
                ],
            ],
        ];
    }

    private function endpointDocumentation(string $method, string $uri): array
    {
        $map = [
            'post api/db-test/validar-login-test' => [
                'requestBody' => [
                    'required' => true,
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'login' => [
                                        'type' => 'string',
                                        'example' => 'admin',
                                    ],
                                    'usuario' => [
                                        'type' => 'string',
                                        'example' => 'admin',
                                    ],
                                    'password' => [
                                        'type' => 'string',
                                        'example' => '123456',
                                    ],
                                    'clave' => [
                                        'type' => 'string',
                                        'example' => '123456',
                                    ],
                                ],
                                'anyOf' => [
                                    ['required' => ['login', 'password']],
                                    ['required' => ['usuario', 'clave']],
                                ],
                            ],
                        ],
                    ],
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Login válido',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'codigo' => ['type' => 'integer', 'example' => 200],
                                        'mensaje' => ['type' => 'string', 'example' => 'Usuario válido'],
                                        'datos' => [
                                            'type' => 'object',
                                            'additionalProperties' => true,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    '422' => [
                        'description' => 'Datos incompletos',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'codigo' => ['type' => 'integer', 'example' => 422],
                                        'mensaje' => [
                                            'type' => 'string',
                                            'example' => 'Debe enviar login/usuario y password/clave en JSON',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        return $map[Str::lower($method.' '.$uri)] ?? [];
    }
}
