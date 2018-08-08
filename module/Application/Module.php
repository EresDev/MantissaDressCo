<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $uri = $e->getRequest()->getUriString();
        $is_admin_panel = strpos($uri, "/admin/") !== false;
        if($is_admin_panel){
            $em = $e->getApplication()->getEventManager();
            $em->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'onDispatch'));
        }
        else{

        }

    }
    public function onDispatch(MvcEvent $e){
        $action = $e->getRouteMatch()->getParam('action', 'yes');

        $container = new Container('admin');
        $allowed_actions = array(
          'login', 'logout', 'forgot'
        );


        if((!$container || !$container->admin_id) && !in_array($action, $allowed_actions)){

            $url = $e->getRouter()->assemble( array('name' => 'admin'), array('action' => 'login'));

            $response = $e->getResponse();
            $response->getHeaders()->addHeaderLine('Location', $url);
            $response->setStatusCode(302);
            $response->sendHeaders();

            exit;
        }
        if(!in_array($action, $allowed_actions)){
            $controller = $e->getTarget();
            $controller->layout('layout/admin');

        }

    }

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
}
