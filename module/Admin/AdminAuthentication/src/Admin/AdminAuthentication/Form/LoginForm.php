<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 8/15/14
 * Time: 9:12 AM
 */

namespace Admin\AdminAuthentication\Form;


use Zend\Form\Form;

class LoginForm extends Form{
    public function __construct($name = null){
        parent::__construct("loginform");
        $this->setAttribute('method', 'post');
        $this->add(array('name'=>'username',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Username:'
            )
        ));

        $this->add(array('name'=>'password',
            'attributes' => array(
                'type'=>'password'
            ),
            'options' => array(
                'label' => 'Password: '
            )
        ));

        $this->add(array('name'=>'submit',
            'attributes' => array(
                'value' => 'Login',
                'type' => 'submit'
            )
        ));

    }
} 