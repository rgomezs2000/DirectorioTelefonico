<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class SwaggerController extends Controller
{
    public function ui(): View|JsonResponse
    {
        try {
            return view('swagger-ui');
        } catch (Throwable $exception) {
            return response()->json([
                'codigo' => 500,
                'mensaje' => 'Error del servidor',
                'error' => Str::limit(trim((string) $exception->getMessage()) ?: 'Error inesperado', 120),
            ], 500);
        }
    }

    public function spec(Request $request): JsonResponse
    {
        try {
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
        } catch (Throwable $exception) {
            return response()->json([
                'codigo' => 500,
                'mensaje' => 'Error del servidor',
                'error' => Str::limit(trim((string) $exception->getMessage()) ?: 'Error inesperado', 120),
            ], 500);
        }
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
        return ! in_array($uri, ['api/api_token', 'api/db-test/validar-login-test', 'api/admin/lista_menu', 'api/admin/obtener_modulo', 'api/admin/obtener_modulo/{ruta}'], true);
    }

    private function pathParameters(string $uri): array
    {
        preg_match_all('/\{([^}]+)\}/', $uri, $matches);

        return collect($matches[1] ?? [])->map(function (string $parameter) {
            $optional = str_ends_with($parameter, '?');
            $cleanParameter = $optional ? substr($parameter, 0, -1) : $parameter;
            $isIdParameter = str_starts_with(Str::lower($cleanParameter), 'id_');

            return [
                'name' => $cleanParameter,
                'in' => 'path',
                'required' => ! $optional,
                'schema' => [
                    'type' => $isIdParameter ? 'integer' : 'string',
                    'example' => $isIdParameter ? 1 : 'valor',
                ],
                'description' => $optional ? 'Parámetro de ruta opcional' : 'Parámetro de ruta',
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

    private function codigoResponse(int $codigo, string $mensaje, bool $includeData = false, bool $includeError = false): array
    {
        $properties = [
            'codigo' => ['type' => 'integer', 'example' => $codigo],
            'mensaje' => ['type' => 'string', 'example' => $mensaje],
        ];

        if ($includeData) {
            $properties['data'] = [
                'type' => 'array',
                'items' => ['type' => 'object'],
                'example' => [],
            ];
        }

        if ($includeError) {
            $properties['error'] = ['type' => 'string', 'example' => 'Error inesperado'];
        }

        return [
            'description' => $mensaje,
            'content' => [
                'application/json' => [
                    'schema' => [
                        'type' => 'object',
                        'properties' => $properties,
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
                    '500' => $this->codigoResponse(500, 'Error del servidor', false, true),
                ],
            ],
            'get api/maestros/paises/lista_paises/{campo?}/{palabra?}' => [
                'responses' => [
                    '200' => $this->codigoResponse(200, 'Registros encontrados', true),
                    '408' => $this->codigoResponse(408, 'No existen registros', true),
                    '500' => $this->codigoResponse(500, 'Error del servidor', false, true),
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
                    '202' => $this->codigoResponse(202, 'El usuario no existe'),
                    '203' => $this->codigoResponse(203, 'Clave inválida'),
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
                    '500' => $this->codigoResponse(500, 'Error del servidor', false, true),
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
                    '308' => $this->codigoResponse(308, 'Contraseña incorrecta', true),
                    '309' => $this->codigoResponse(309, 'Token incorrecto o credencial bloqueada por intentos', true),
                    '310' => $this->codigoResponse(310, 'Token usado', true),
                    '311' => $this->codigoResponse(311, 'Token expirado', true),
                    '407' => $this->codigoResponse(407, 'Usuario bloqueado', true),
                    '408' => $this->codigoResponse(408, 'Login no existe', true),
                    '500' => $this->codigoResponse(500, 'Error del servidor', false, true),
                ],
            ],
            'post api/login/auth_google' => [
                'requestBody' => [
                    'required' => true,
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'required' => ['email'],
                                'properties' => [
                                    'email' => [
                                        'type' => 'string',
                                        'format' => 'email',
                                        'description' => 'Correo del usuario autenticado con Google',
                                        'example' => 'admin@empresa.com',
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
                    '309' => $this->codigoResponse(309, 'Token incorrecto', true),
                    '310' => $this->codigoResponse(310, 'Token usado', true),
                    '311' => $this->codigoResponse(311, 'Token expirado', true),
                    '407' => $this->codigoResponse(407, 'Usuario bloqueado', true),
                    '408' => $this->codigoResponse(408, 'Login no existe', true),
                    '500' => $this->codigoResponse(500, 'Error del servidor', false, true),
                ],
            ],
            'post api/login/registrar_sesion/{id_usuario}' => [
                'requestBody' => [
                    'required' => true,
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'required' => ['session'],
                                'properties' => [
                                    'session' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'token_sesion' => ['type' => 'string', 'example' => 'abc123sesion'],
                                            'ip_origen' => ['type' => 'string', 'example' => '127.0.0.1'],
                                            'user_agent' => ['type' => 'string', 'example' => 'Mozilla/5.0'],
                                            'dispositivo' => ['type' => 'string', 'example' => 'Windows NT 10.0'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Sesión registrada',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'codigo' => ['type' => 'integer', 'example' => 200],
                                        'mensaje' => ['type' => 'string', 'example' => 'Sesion registrada'],
                                        'data' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'id_sesion' => ['type' => 'integer', 'example' => 123],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    '309' => $this->codigoResponse(309, 'Token incorrecto', true),
                    '310' => $this->codigoResponse(310, 'Token usado', true),
                    '311' => $this->codigoResponse(311, 'Token expirado', true),
                    '500' => $this->codigoResponse(500, 'Error del servidor', false, true),
                ],
            ],
            'post api/logout/ultimo_acceso/{id_usuario}' => [
                'responses' => [
                    '200' => [
                        'description' => 'Último acceso actualizado',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'codigo' => ['type' => 'integer', 'example' => 200],
                                        'mensaje' => ['type' => 'string', 'example' => 'ultimo acceso fue el: 2026-03-27 10:00:00'],
                                        'data' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'actualizado' => ['type' => 'boolean', 'example' => true],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    '309' => $this->codigoResponse(309, 'Token incorrecto', true),
                    '310' => $this->codigoResponse(310, 'Token usado', true),
                    '311' => $this->codigoResponse(311, 'Token expirado', true),
                    '500' => $this->codigoResponse(500, 'Error del servidor', false, true),
                ],
            ],
            'post api/logout/{id_usuario}/{id_sesion}' => [
                'responses' => [
                    '200' => [
                        'description' => 'Sesión cerrada',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'codigo' => ['type' => 'integer', 'example' => 200],
                                        'mensaje' => ['type' => 'string', 'example' => 'Sesion Cerrada'],
                                        'data' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'actualizado' => ['type' => 'boolean', 'example' => true],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    '309' => $this->codigoResponse(309, 'Token incorrecto', true),
                    '310' => $this->codigoResponse(310, 'Token usado', true),
                    '311' => $this->codigoResponse(311, 'Token expirado', true),
                    '500' => $this->codigoResponse(500, 'Error del servidor', false, true),
                ],
            ],

            'get api/admin/obtener_modulo' => [
                'responses' => [
                    '200' => [
                        'description' => 'Módulo actual: Inicio',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'codigo' => ['type' => 'integer', 'example' => 200],
                                        'mensaje' => ['type' => 'string', 'example' => 'estas en el modulo Inicio'],
                                        'data' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'tipo' => ['type' => 'string', 'example' => 'inicio'],
                                                'nombre' => ['type' => 'string', 'example' => 'Inicio'],
                                                'ruta_actual' => ['type' => 'string', 'example' => '/'],
                                                'breadcrumb' => [
                                                    'type' => 'array',
                                                    'items' => ['type' => 'string'],
                                                    'example' => ['Inicio'],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    '408' => [
                        'description' => 'No hay modulo seleccionado',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'codigo' => ['type' => 'integer', 'example' => 408],
                                        'mensaje' => ['type' => 'string', 'example' => 'No hay modulo seleccionado'],
                                        'data' => ['type' => 'object', 'nullable' => true, 'example' => null],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    '500' => $this->codigoResponse(500, 'Error del servidor', false, true),
                ],
            ],
            'get api/admin/obtener_modulo/{ruta}' => [
                'responses' => [
                    '200' => [
                        'description' => 'Módulo actual por ruta',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'codigo' => ['type' => 'integer', 'example' => 200],
                                        'mensaje' => ['type' => 'string', 'example' => 'estas en el modulo Listar Contactos'],
                                        'data' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'tipo' => ['type' => 'string', 'example' => 'modulo'],
                                                'nombre' => ['type' => 'string', 'example' => 'Listar Contactos'],
                                                'ruta_actual' => ['type' => 'string', 'example' => '/directorio/contactos'],
                                                'breadcrumb' => [
                                                    'type' => 'array',
                                                    'items' => ['type' => 'string'],
                                                    'example' => ['Inicio', 'Directorio', 'Mis Contactos', 'Listar Contactos'],
                                                ],
                                                'menu' => ['type' => 'object', 'additionalProperties' => true],
                                                'submenu' => ['type' => 'object', 'additionalProperties' => true],
                                                'modulo' => ['type' => 'object', 'additionalProperties' => true],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    '408' => [
                        'description' => 'No hay modulo seleccionado',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'codigo' => ['type' => 'integer', 'example' => 408],
                                        'mensaje' => ['type' => 'string', 'example' => 'No hay modulo seleccionado'],
                                        'data' => ['type' => 'object', 'nullable' => true, 'example' => null],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    '500' => $this->codigoResponse(500, 'Error del servidor', false, true),
                ],
            ],
            'get api/admin/lista_menu' => [
                'responses' => [
                    '200' => [
                        'description' => 'Módulos desplegados',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'codigo' => ['type' => 'integer', 'example' => 200],
                                        'mensaje' => ['type' => 'string', 'example' => 'modulos desplegados'],
                                        'data' => [
                                            'type' => 'array',
                                            'items' => [
                                                'type' => 'object',
                                                'properties' => [
                                                    'id_menu' => ['type' => 'integer', 'example' => 1],
                                                    'nombre' => ['type' => 'string', 'example' => 'Administración'],
                                                    'submenus' => [
                                                        'type' => 'array',
                                                        'items' => [
                                                            'type' => 'object',
                                                            'properties' => [
                                                                'id_submenu' => ['type' => 'integer', 'example' => 1],
                                                                'nombre' => ['type' => 'string', 'example' => 'Usuarios'],
                                                                'modulos' => [
                                                                    'type' => 'array',
                                                                    'items' => [
                                                                        'type' => 'object',
                                                                        'properties' => [
                                                                            'id_modulo' => ['type' => 'integer', 'example' => 1],
                                                                            'nombre' => ['type' => 'string', 'example' => 'Listado de usuarios'],
                                                                        ],
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    '408' => $this->codigoResponse(408, 'no hay modulos configurados en el sistema, consulte con su administrador', true),
                    '500' => $this->codigoResponse(500, 'Error del servidor', false, true),
                ],
            ],
        ];

        return $map[Str::lower($method.' '.$uri)] ?? [];
    }
}
