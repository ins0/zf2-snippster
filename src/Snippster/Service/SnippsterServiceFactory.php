<?php
namespace Snippster\Service;

use Zend\Cache\Storage\Adapter\Filesystem;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SnippsterServiceFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');

        /** @var Filesystem $cacheAdapter */
        $cache = \Zend\Cache\StorageFactory::factory($config['snippster_configuration']['cache']);

        $service = new Snippster();
        $service->setServiceLocator($serviceLocator);
        $service->setCacheAdapter($cache);

        return $service;
    }

}