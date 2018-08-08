<?php
namespace Admin\AdminAccount;

use Zend\Mvc\MvcEvent;
use Zend\Mvc\ModuleRouteListener;
use Zend\Session\Container;
use Zend\View\Helper\ServerUrl;

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
//    public function onBootStrap($e){
//        $container = new Container();
//        if(!$container || !$container->admin_id){
//            //  Assuming your login route has a name 'login', this will do the assembly
//            // (you can also use directly $url=/path/to/login)
//            $url = $e->getRouter()->assemble(array('action' => 'login'), array('name' => 'admin'));
//            $response=$e->getResponse();
//            $response->getHeaders()->addHeaderLine('Location', $url);
//            $response->setStatusCode(302);
//            $response->sendHeaders();
//            // When an MvcEvent Listener returns a Response object,
//            // It automatically short-circuit the Application running
//            // -> true only for Route Event propagation see Zend\Mvc\Application::run
//
//            // To avoid additional processing
//            // we can attach a listener for Event Route with a high priority
//            $stopCallBack = function($event) use ($response){
//                $event->stopPropagation();
//                return $response;
//            };
//            //Attach the "break" as a listener with a high priority
//            $e->getApplication()->getEventManager()->attach(MvcEvent::EVENT_ROUTE, $stopCallBack,-10000);
//            return $response;
//        }
//     }

}
