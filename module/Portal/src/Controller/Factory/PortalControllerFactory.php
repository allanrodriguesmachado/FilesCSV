<?php

namespace Portal\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Portal\Controller\PortalController;

class PortalControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): PortalController
    {
        return new PortalController($container->get('Service\PortalTableModel'));
    }
}
