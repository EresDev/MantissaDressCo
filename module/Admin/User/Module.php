<?php
namespace Admin\User;

use Admin\User\Model\Entity\User;
use Admin\User\Model\UserTable;
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
                'Admin\User\Model\UserTable' =>  function($sm) {
                        $tableGateway = $sm->get('UserTableGateway');
                        $table = new UserTable($tableGateway);
                        return $table;
                    },
                'UserTableGateway' => function ($sm) {
                        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                        $resultSetPrototype = new ResultSet();
                        $resultSetPrototype->setArrayObjectPrototype(new User());
                        return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
                    },

            ),
        );
    }
}
