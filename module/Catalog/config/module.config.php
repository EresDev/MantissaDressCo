<?php
namespace Catalog;
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'controllers' => array(
        'invokables' => array(
            'Catalog\Controller\Home' => 'Catalog\Controller\HomeController',
            'Catalog\Controller\Category' => 'Catalog\Controller\CategoryController',
            'Catalog\Controller\Product' => 'Catalog\Controller\ProductController',
            'Catalog\Controller\ShoppingCart' => 'Catalog\Controller\ShoppingCartController',
            'Catalog\Controller\User' => 'Catalog\Controller\UserController',
            'Catalog\Controller\Account' => 'Catalog\Controller\AccountController',
            'Catalog\Controller\Checkout' => 'Catalog\Controller\CheckoutController',
            'Catalog\Controller\Contact' => 'Catalog\Controller\ContactController',
            'Catalog\Controller\Information' => 'Catalog\Controller\InformationController',
            'Catalog\Controller\Api' => 'Catalog\Controller\ApiController',
        ),
    ),

    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Catalog\Controller\Home',
                        'action'     => 'index',
                    ),
                ),
            ),
            'category' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/category',
                    'constraints' => array(

                    ),
                    'defaults' => array(
                        'controller' => 'Catalog\Controller\Category',
                        'action'     => 'index',
                    ),
                ),
            ),
            'products' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/products[/:category_id]',
                    'constraints' => array(
                      'category_id' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Catalog\Controller\Product',
                        'action'     => 'index',
                    ),
                ),
            ),
            'product' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/product[/:product_id]',
                    'constraints' => array(
                        'product_id' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Catalog\Controller\Product',
                        'action'     => 'product',
                    ),
                ),
            ),
            'cart' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/cart[/:action][/:last]',
                    'constraints' => array(
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'last' => 'last',
                    ),
                    'defaults' => array(
                        'controller' => 'Catalog\Controller\ShoppingCart',
                        'action'     => 'index',
                    ),
                ),
            ),
            'login' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/login',
                    'constraints' => array(
                        //'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Catalog\Controller\User',
                        'action'     => 'login',
                    ),
                ),
            ),
            'logout' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/logout',
                    'constraints' => array(
                        //'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Catalog\Controller\User',
                        'action'     => 'logout',
                    ),
                ),
            ),
            'register' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/register',
                    'constraints' => array(
                        //'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Catalog\Controller\User',
                        'action'     => 'register',
                    ),
                ),
            ),
            'forgot' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/forgot',
                    'constraints' => array(
                        //'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Catalog\Controller\User',
                        'action'     => 'forgot',
                    ),
                ),
            ),
            'account' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/account[/:action]',
                    'constraints' => array(
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Catalog\Controller\Account',
                        'action'     => 'index',
                    ),
                ),
            ),
            'checkout' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/checkout',
                    'constraints' => array(
                        //'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Catalog\Controller\Checkout',
                        'action'     => 'index',
                    ),
                ),
            ),

            'contact' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/contact',
                    'constraints' => array(
                        //'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Catalog\Controller\Contact',
                        'action'     => 'index',
                    ),
                ),
            ),
            'information' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/info[/:information_id]',
                    'constraints' => array(
                        //'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',

                        'information_id' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Catalog\Controller\Information',
                        'action'     => 'index',
                    ),
                ),
            ),
            'api' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/api/:action[/:entity][/:entity_id]',
                    'constraints' => array(
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',

                        //'entity_id' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Catalog\Controller\Api',
                        'action'     => 'r',
                    ),
                ),
            ),

        ),
    ),

    'strategies' => array(
        'ViewJsonStrategy',
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'doctrine' => array(
        'driver' => array(
            'Catalog_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Catalog/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Catalog\Entity' =>  'Catalog_driver'
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'aliases' => array(
            'Zend\Authentication\AuthenticationService' => 'AuthService',
        ),

    ),

);