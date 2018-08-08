<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Admin\AdminAccount\Controller\Account' => 'Admin\AdminAccount\Controller\AccountController',
        ),
    ),

    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'admin-account' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/account[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',

                    ),
                    'defaults' => array(
                        'controller' => 'Admin\AdminAccount\Controller\Account',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'account' => __DIR__ . '/../view',
        ),
    ),


);