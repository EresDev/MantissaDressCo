<?php
namespace Admin\Product;

use Admin\Product\Model\Entity\Product;
use Admin\Product\Model\Entity\ProductImage;
use Admin\Product\Model\Entity\ProductOption;
use Admin\Product\Model\Entity\ProductOptionTable;
use Admin\Product\Model\ProductImageTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Admin\Product\Model\ProductTable;
class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        $namespace = str_replace("\\", "/", __NAMESPACE__);
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . $namespace,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Admin\Product\Model\ProductTable' =>  function($sm) {
                        $tableGateway = $sm->get('ProductTableGateway');
                        $table = new ProductTable($tableGateway);
                        return $table;
                    },
                'ProductTableGateway' => function ($sm) {
                        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                        $resultSetPrototype = new ResultSet();
                        $resultSetPrototype->setArrayObjectPrototype(new Product());
                        return new TableGateway('product', $dbAdapter, null, $resultSetPrototype);
                    },
                'Admin\Product\Model\ProductOptionTable' =>  function($sm) {
                        $tableGateway = $sm->get('ProductOptionTableGateway');
                        $table = new ProductOptionTable($tableGateway);
                        return $table;
                    },
                'ProductOptionTableGateway' => function ($sm) {
                        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                        $resultSetPrototype = new ResultSet();
                        $resultSetPrototype->setArrayObjectPrototype(new ProductOption());
                        return new TableGateway('product_option', $dbAdapter, null, $resultSetPrototype);
                    },
                'Admin\Product\Model\ProductImageTable' =>  function($sm) {
                        $tableGateway = $sm->get('ProductImageTableGateway');
                        $table = new ProductImageTable($tableGateway);
                        return $table;
                    },
                'ProductImageTableGateway' => function ($sm) {
                        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                        $resultSetPrototype = new ResultSet();
                        $resultSetPrototype->setArrayObjectPrototype(new ProductImage());
                        return new TableGateway('product_image', $dbAdapter, null, $resultSetPrototype);
                    },
            ),
        );
    }

}
