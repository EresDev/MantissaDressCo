<?php
namespace Admin\Options;

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
}
