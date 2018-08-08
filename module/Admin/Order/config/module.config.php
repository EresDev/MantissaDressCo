<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Admin\Order\Controller\Order' => 'Admin\Order\Controller\OrderController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'admin-order' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/order[/:status][/:action][/:id]',
                    'constraints' => array(
                        'status' => 'success-edit|success-delete|success-add',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Order\Controller\Order',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
             __DIR__ . '/../view',
        ),
    ),
);