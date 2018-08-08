<?php
namespace Catalog;

use Catalog\View\Helper\CartHelper;
use Catalog\View\Helper\SettingHelper;
use Zend\Authentication\Adapter\DbTable;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session;
use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter;
class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    public function onBootstrap($e)
    {
        $e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractController', 'dispatch', function($e) {
            $controller = $e->getTarget();
            $controllerClass = get_class($controller);
            $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
            $config = $e->getApplication()->getServiceManager()->get('config');
            if (isset($config['module_layouts'][$moduleNamespace])) {
                $controller->layout($config['module_layouts'][$moduleNamespace]);
            }
        }, 100);
    }
    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'settingHelper' => function($sm) {
                        $helper = new SettingHelper() ;
                        $helper->setServiceLocator($sm->getServiceLocator());
                        return $helper;
                    },
                'cartHelper' => function($sm) {
                        $helper = new CartHelper() ;
                        $helper->setServiceLocator($sm->getServiceLocator());
                        return $helper;
                    }
            )
        );
    }
    public function getServiceConfig()
    {
        return array(
            'factories'=>array(
                'AuthStorage' => function($sm){
                        return new Session('logged_in');
                    },
                'AuthService' => function($sm) {
                        //My assumption, you've alredy set dbAdapter
                        //and has users table with columns : user_name and pass_word
                        //that password hashed with md5
                        $dbAdapter           = $sm->get('Zend\Db\Adapter\Adapter');
                        $dbTableAuthAdapter  = new CredentialTreatmentAdapter($dbAdapter,
                            'user','email','password', 'MD5(?)');

                        $authService = new AuthenticationService();
                        $authService->setAdapter($dbTableAuthAdapter);
                        $authService->setStorage($sm->get("AuthStorage"));

                        return $authService;
                    },
                'catalog-navigation' => 'Catalog\Navigation\CatalogNavigationFactory'
            ),
        );
    }
}
