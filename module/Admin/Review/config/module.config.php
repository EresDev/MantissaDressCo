<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Admin\Review\Controller\Review' => 'Admin\Review\Controller\ReviewController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'admin-review' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/review[/:action][/:id]',
                    'constraints' => array(
                        //'status' => 'success-edit|success-delete|success-add',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Review\Controller\Review',
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