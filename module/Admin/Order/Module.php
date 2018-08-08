<?php
namespace Admin\Order;

use Admin\Order\Model\Entity\Order;
use Admin\Order\Model\Entity\OrderAddress;
use Admin\Order\Model\Entity\OrderProduct;
use Admin\Order\Model\OrderAddressTable;
use Admin\Order\Model\OrderProductTable;
use Admin\Order\Model\OrderTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

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
                'Admin\Order\Model\OrderTable' =>  function($sm) {
                        $tableGateway = $sm->get('OrderTableGateway');
                        $table = new OrderTable($tableGateway);
                        return $table;
                    },
                'OrderTableGateway' => function ($sm) {
                        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                        $resultSetPrototype = new ResultSet();
                        $resultSetPrototype->setArrayObjectPrototype(new Order());
                        return new TableGateway('order', $dbAdapter, null, $resultSetPrototype);
                    },
                'Admin\Order\Model\OrderAddressTable' =>  function($sm) {
                        $tableGateway = $sm->get('OrderAddressTableGateway');
                        $table = new OrderAddressTable($tableGateway);
                        return $table;
                    },
                'OrderAddressTableGateway' => function ($sm) {
                        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                        $resultSetPrototype = new ResultSet();
                        $resultSetPrototype->setArrayObjectPrototype(new OrderAddress());
                        return new TableGateway('order_address', $dbAdapter, null, $resultSetPrototype);
                    },
                'Admin\Order\Model\OrderProductTable' =>  function($sm) {
                        $tableGateway = $sm->get('OrderProductTableGateway');
                        $table = new OrderProductTable($tableGateway);
                        return $table;
                    },
                'OrderProductTableGateway' => function ($sm) {
                        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                        $resultSetPrototype = new ResultSet();
                        $resultSetPrototype->setArrayObjectPrototype(new OrderProduct());
                        return new TableGateway('order_product', $dbAdapter, null, $resultSetPrototype);
                    },
            ),
        );
    }
}
