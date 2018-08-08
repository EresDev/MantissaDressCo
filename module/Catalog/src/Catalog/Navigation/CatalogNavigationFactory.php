<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/26/14
 * Time: 8:59 AM
 */

namespace Catalog\Navigation;



use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CatalogNavigationFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $navigation = new CatalogNavigation();
        return $navigation->createService($serviceLocator);
    }
}