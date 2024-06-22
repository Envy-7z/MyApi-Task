<?php

return [
    'default' => 'default',
    'documentations' => [
        'default' => [
            'api' => [
                'title' => 'L5 Swagger UI', // Update with your API title
            ],

            'routes' => [
                'api' => 'api/documentation', // Route for accessing Swagger UI
            ],

            'paths' => [
                'use_absolute_path' => env('L5_SWAGGER_USE_ABSOLUTE_PATH', true),
                'docs_json' => 'api-docs.json', // Generated JSON documentation file name
                'docs_yaml' => 'api-docs.yaml', // Generated YAML documentation file name
                'annotations' => [
                    base_path('app'), // Directory containing Swagger annotations
                ],
            ],
        ],
    ],

    'defaults' => [
        'securityDefinitions' => [
            'securitySchemes' => [
                'bearerAuth' => [
                    'type' => 'http',
                    'scheme' => 'bearer',
                ],
            ],
            'security' => [
                ['bearerAuth' => []],
            ],
        ],

        'generate_always' => env('L5_SWAGGER_GENERATE_ALWAYS', false),
        'generate_yaml_copy' => env('L5_SWAGGER_GENERATE_YAML_COPY', false),

        'paths' => [
            'docs' => storage_path('api-docs'), // Location where parsed annotations are stored
            'views' => base_path('resources/views/vendor/l5-swagger'), // Directory for exporting views
            'base' => env('L5_SWAGGER_BASE_PATH', null), // API's base path
            'swagger_ui_assets_path' => env('L5_SWAGGER_UI_ASSETS_PATH', 'vendor/swagger-api/swagger-ui/dist/'), // Swagger UI assets path
            'excludes' => [],
        ],

        'scanOptions' => [
            'open_api_spec_version' => env('L5_SWAGGER_OPEN_API_SPEC_VERSION', \L5Swagger\Generator::OPEN_API_DEFAULT_SPEC_VERSION),
        ],

        'ui' => [
            'display' => [
                'doc_expansion' => env('L5_SWAGGER_UI_DOC_EXPANSION', 'none'),
                'filter' => env('L5_SWAGGER_UI_FILTERS', true), // Enable filtering
            ],

            'authorization' => [
                'persist_authorization' => env('L5_SWAGGER_UI_PERSIST_AUTHORIZATION', false),
                'oauth2' => [
                    'use_pkce_with_authorization_code_grant' => false,
                ],
            ],
        ],

        'constants' => [
            'L5_SWAGGER_CONST_HOST' => env('L5_SWAGGER_CONST_HOST', 'http://my-default-host.com'),
        ],
    ],
];
