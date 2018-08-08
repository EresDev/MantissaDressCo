<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/22/14
 * Time: 11:00 PM
 */

namespace Catalog\Form;


use Zend\Form\Form;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilter;


use Zend\InputFilter\Factory as InputFactory;

class LoginForm extends Form implements InputFilterAwareInterface{
    /**@var InputFilter $inputFilter */
    protected $inputFilter;
    public function __construct($name= ""){
        parent::__construct("loginform");
        $this->setAttribute("method", 'post');
        $this->add(
            array(
                'name' => 'email',
                'attributes' => array(
                    'type' => 'text',
                    'class' => 'form-control'
                ),
                'options' => array(
                    'label' => 'Email Address *'
                ),
            )
        );
        $this->add(
            array(
                'name' => 'password',
                'attributes' => array(
                    'type' => 'password',
                    'class' => 'form-control'

                ),
                'options' => array(
                    'label' => 'Password *'
                ),
            )
        );
        $this->add(
            array(
                'name' => 'submit',
                'attributes' => array(
                    'type' => 'submit',
                    'value' => 'Login',
                    'class' => 'btn btn-default'
                ),

            )
        );
    }

    public function getInputFilter(){
        if(! $this->inputFilter){
            $inputFilter = new InputFilter();
            $factory = new InputFactory();

            $inputFilter->add($factory->createInput(array(
            'name' => 'email',
            'validators' => array(
                array(

                    'name' => 'EmailAddress',
                    'options' =>array(
                        'domain'   => 'true',
                        'hostname' => 'true',
                        'mx'       => 'true',
                        'deep'     => 'true',
                        'message'  => 'Email is not valid.',
                    ),

                ),
            ))));
        $inputFilter->add($factory->createInput(array(
            'name' => 'password',

            )));

            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }
} 