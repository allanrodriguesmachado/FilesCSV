<?php

declare(strict_types=1);

namespace Portal;

use Laminas\Db\Adapter\AdapterAbstractServiceFactory;
use Laminas\Db\Sql\Literal;
use Laminas\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'portal' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/portal/[:action]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]+'
                    ],
                    'defaults' => [
                        'controller' => Controller\PortalController::class,
                        'action' => 'index',
                    ]
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\PortalController::class => Controller\Factory\PortalControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'abstract_factories' => [
            'Laminas\Db\Adapter\AdapterAbstractServiceFactory' => AdapterAbstractServiceFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => false,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/500',
        'template_path_stack' => [
            'portal' => __DIR__ . '/../view',
        ],
        'template_map' => [
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/500' => __DIR__ . '/../view/error/500.phtml',
        ],
    ],
    'strategies' => [
        'ViewJsonStrategy',
    ],
];
