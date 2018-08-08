<?php
namespace Admin\Setting;

use Admin\Setting\Model\Entity\Setting;
use Admin\Setting\Model\SettingTable;
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
                'Admin\Setting\Model\SettingTable' =>  function($sm) {
                        $tableGateway = $sm->get('SettingTableGateway');
                        $table = new SettingTable($tableGateway);
                        return $table;
                    },
                'SettingTableGateway' => function ($sm) {
                        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                        $resultSetPrototype = new ResultSet();
                        $resultSetPrototype->setArrayObjectPrototype(new Setting());
                        return new TableGateway('setting', $dbAdapter, null, $resultSetPrototype);
                    },

            ),
        );
    }
}
