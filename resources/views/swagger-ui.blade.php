<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Swagger UI</title>
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5/swagger-ui.css" />
    <style>
        html { box-sizing: border-box; overflow-y: scroll; }
        *, *:before, *:after { box-sizing: inherit; }
        body { margin: 0; background: #fafafa; }
    </style>
</head>
<body>
<div id="swagger-ui"></div>
<script src="https://unpkg.com/swagger-ui-dist@5/swagger-ui-bundle.js"></script>
<script>
    window.onload = function () {
        window.ui = SwaggerUIBundle({
            url: '{{ url('/api/openapi.json') }}',
            dom_id: '#swagger-ui',
            deepLinking: true,
            displayRequestDuration: true,
            filter: true,
            tryItOutEnabled: true,
            persistAuthorization: true,
            docExpansion: 'list',
            defaultModelsExpandDepth: 1,
            presets: [SwaggerUIBundle.presets.apis],
            layout: 'BaseLayout'
        });
    };
</script>
</body>
</html>
