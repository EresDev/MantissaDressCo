<?php
namespace Admin\Category;

use Admin\Category\Model\CategoryTable;

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
    public function getServiceConfig(){
        return array(
            'factories' => array(
                'Admin\Category\Model\CategoryTable' => function($sm) {
                        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                        $table = new CategoryTable($dbAdapter);
                        return $table;
                    },
            ),
        );
    }
}
