<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Admin\User\Controller\User' => 'Admin\User\Controller\UserController',
            'Admin\User\Controller\UserAjax' => 'Admin\User\Controller\UserAjaxController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'admin-user' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/user[/:status][/:action][/:id]',
                    'constraints' => array(
                        'status' => 'success-edit|success-delete|success-add',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\User\Controller\User',
                        'action'     => 'index',
                    ),
                ),
            ),
            'admin-user-ajax' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/user/ajax/:action[/:like]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',

                    ),
                    'defaults' => array(
                        'controller' => 'Admin\User\Controller\UserAjax',
                        'action'     => 'usersLike',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'user' => __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);