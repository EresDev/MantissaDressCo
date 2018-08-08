<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Admin\Information\Controller\Information' => 'Admin\Information\Controller\InformationController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'admin-information' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/information[/:action][/:id]',
                    'constraints' => array(
                        //'status' => 'success-edit|success-delete|success-add',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Information\Controller\Information',
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