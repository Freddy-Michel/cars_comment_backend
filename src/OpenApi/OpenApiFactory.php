<?php

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\Model\Operation;
use ApiPlatform\Core\OpenApi\Model\PathItem;
use ApiPlatform\Core\OpenApi\Model\RequestBody;
use ApiPlatform\Core\OpenApi\OpenApi;

class OpenApiFactory implements OpenApiFactoryInterface
{
    public function __construct(private OpenApiFactoryInterface $decorated)
    {

    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);
        
        foreach ($openApi->getPaths()->getPaths() as $key => $path){
            if($path->getPatch() && $path->getPatch()->getSummary() == 'hidden'){
                $openApi->getPaths()->addPath($key, $path->withPatch(null));
            }
        }

        $schemas = $openApi->getComponents()->getSecuritySchemes();
        

        $schemas['cookieAuth'] = new \ArrayObject([
            'type' => 'apiKey',
            'in' => 'cookie',
            'name' => 'PHPSESSID'
        ]);

        $schemas = $openApi->getComponents()->getSchemas();
        $schemas['Credentials'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'username' => [
                    'type' => 'string',
                    'example' => 'michelfreddy1992@gmail.com'
                ],
                'password' => [
                    'type' => 'string',
                    'example' => '0000'
                ]
            ]
        ]);

        $myOperation = $openApi->getPaths()->getPath('/api/me')->getGet()->withParameters([]);
        $myPathItem = $openApi->getPaths()->getPath('/api/me')->withGet($myOperation);
        $openApi->getPaths()->addPath('/api/me', $myPathItem);

        // Login path
        $pathItem = new PathItem(
            post: new Operation(
                operationId: 'apiLogin',
                tags: ['Auth'],
                requestBody: new RequestBody(
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/Credentials'
                            ]
                        ]
                    ]),
                ),
                responses: [
                    '200' => [
                        'desciption' => 'Utilisateur connecte',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/User-read.User'
                                ]
                            ]
                        ]
                    ]
                ]
            )
        );

        $openApi->getPaths()->addPath('/api/login', $pathItem);

        // Logout path

        $pathItem = new PathItem(
            post: new Operation(
                operationId: 'apiLogout',
                tags: ['Auth'],
                
                responses: [
                    '204'
                ]
            )
        );

        $openApi->getPaths()->addPath('/api/logout', $pathItem);

        return $openApi;
    }
}