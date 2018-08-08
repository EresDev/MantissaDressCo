<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Admin\Setting\Controller\Setting' => 'Admin\Setting\Controller\SettingController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'admin-setting' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/setting[/:status]',
                    'constraints' => array(
                        'status' => 'success|fail'
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Setting\Controller\Setting',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'setting' => __DIR__ . '/../view',
        ),
    ),
);