<?php

declare(strict_types=1);

namespace Portal;

use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\ServiceManager\ServiceManager;
use Portal\Model\PortalTableModel;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig(): array
    {
        return ['factories' => [
            'Service\PortalTableModel' => function (ServiceManager $sm): PortalTableModel {
                return new PortalTableModel($sm->get('portal_db'));
            },
        ],
        ];
    }
}
