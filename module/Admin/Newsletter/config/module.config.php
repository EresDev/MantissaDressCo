<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Admin\Newsletter\Controller\Newsletter' => 'Admin\Newsletter\Controller\NewsletterController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'admin-newsletter' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/newsletter',
                    'constraints' => array(
                        //'status' => 'success-edit|success-delete|success-add',
                        //'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        //'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Newsletter\Controller\Newsletter',
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