<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Admin\Staff\Controller\Staff' => 'Admin\Staff\Controller\StaffController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'admin-staff' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/staff[/:action][/:id]',
                    'constraints' => array(
                        //'status' => 'success-edit|success-delete|success-add',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Staff\Controller\Staff',
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