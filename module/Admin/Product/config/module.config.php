<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Admin\Product\Controller\Product' => 'Admin\Product\Controller\ProductController',
            'Admin\Product\Controller\ProductAjax' => 'Admin\Product\Controller\ProductAjaxController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'admin-product' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/product[/:status][/:action][/:id]',
                    'constraints' => array(
                        'status' => 'success-edit|success-delete|success-add',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Product\Controller\Product',
                        'action'     => 'index',
                    ),
                ),
            ),
            'admin-product-ajax' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/product/ajax/:action[/:like]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',

                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Product\Controller\ProductAjax',
                        'action'     => 'productLike',
                    ),
                ),
            ),
            'admin-product-option-ajax' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/productoption/ajax/:action[/:product_id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'product_id'     => '[0-9]+',

                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Product\Controller\ProductAjax',
                        'action'     => 'productOptions',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),

);