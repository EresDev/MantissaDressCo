<?php
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
            'Admin\Category\Controller\Category' => 'Admin\Category\Controller\CategoryController',
        ),
    ),

    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'admin-category' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/category[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',

                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Category\Controller\Category',
                        'action'     => 'index',
                    ),
                ),
            ),
            'admin-category-edit' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/category/edit?edit=:id',
                    'constraints' => array(


                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Category\Controller\Category',
                        'action'     => 'edit',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'index' => __DIR__ . '/../view',
        ),
    ),
);