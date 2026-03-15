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
                    'parameters' => $this->buildParameters($uri),
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

    private function buildParameters(string $uri): array
    {
        $parameters = $this->pathParameters($uri);

        if ($this->requireTokenHeader($uri)) {
            $parameters[] = [
                'name' => 'Authorization',
                'in' => 'header',
                'required' => true,
                'schema' => [
                    'type' => 'string',
                    'example' => 'Bearer eyJ...oTokenGenerado',
                ],
                'description' => 'Bearer token obtenido desde /api/api_token. Formato: Bearer <token>',
            ];
        }

        return $parameters;
    }

    private function requireTokenHeader(string $uri): bool
    {
        return ! in_array($uri, ['api/api_token', 'api/db-test/validar-login-test'], true);
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
                    'example' => 'Bearer eyJ...oTokenGenerado',
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
            'get api/api_token' => [
                'responses' => [
                    '200' => [
                        'description' => 'Token generado correctamente',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'codigo' => ['type' => 'integer', 'example' => 200],
                                        'mensaje' => ['type' => 'string', 'example' => 'exitoso'],
                                        'data' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'id' => ['type' => 'integer', 'example' => 1],
                                                'api_token' => ['type' => 'string', 'example' => 'token_sha512...'],
                                                'fecha_token_inicio' => ['type' => 'string', 'example' => '2026-03-15 10:00:00'],
                                                'fecha_fin_token' => ['type' => 'string', 'example' => '2026-03-15 10:30:00'],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'post api/db-test/validar-login-test' => [
                'requestBody' => [
                    'required' => true,
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'login' => ['type' => 'string', 'example' => 'admin'],
                                    'usuario' => ['type' => 'string', 'example' => 'admin'],
                                    'password' => ['type' => 'string', 'example' => '123456'],
                                    'clave' => ['type' => 'string', 'example' => '123456'],
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
                                        'datos' => ['type' => 'object', 'additionalProperties' => true],
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
                                        'mensaje' => ['type' => 'string', 'example' => 'Debe enviar login/usuario y password/clave en JSON'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'post api/login/ingresar' => [
                'requestBody' => [
                    'required' => true,
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'required' => ['login', 'password'],
                                'properties' => [
                                    'login' => [
                                        'type' => 'string',
                                        'description' => 'Usuario o correo del usuario',
                                        'example' => 'admin',
                                    ],
                                    'password' => [
                                        'type' => 'string',
                                        'format' => 'password',
                                        'description' => 'Contraseña ingresada por el usuario',
                                        'example' => 'Admin@12345!',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Login correcto',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'codigo' => ['type' => 'integer', 'example' => 200],
                                        'mensaje' => ['type' => 'string', 'example' => 'login correcto'],
                                        'data' => ['type' => 'object', 'additionalProperties' => true],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    '306' => [
                        'description' => 'Token incorrecto',
                    ],
                    '307' => [
                        'description' => 'Token expirado',
                    ],
                    '308' => [
                        'description' => 'Password incorrecto',
                    ],
                    '408' => [
                        'description' => 'Login no existe',
                    ],
                ],
            ],
        ];

        return $map[Str::lower($method.' '.$uri)] ?? [];
    }
}
