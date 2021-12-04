<?php

namespace Auth;

use Laminas\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/[:action]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ],
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\AuthController::class => Controller\Factory\AuthControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/500',
        'template_path_stack' => [
            'auth' => __DIR__ . '/../view',
        ],
        'template_map' => [
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/500' => __DIR__ . '/../view/error/500.phtml',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];
