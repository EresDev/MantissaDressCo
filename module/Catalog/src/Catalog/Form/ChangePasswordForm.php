<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/22/14
 * Time: 11:14 PM
 */

namespace Catalog\Form;


use Zend\Captcha\Dumb;
use Zend\Form\Element\Captcha;
use Zend\Form\Element\Checkbox;
use Zend\Form\Element\Csrf;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

use Zend\InputFilter\InputFilterAwareInterface;

use Zend\InputFilter\Factory as InputFactory;

use Zend\Validator\Identical;
use Zend\Validator\StringLength;


class ChangePasswordForm extends Form implements InputFilterAwareInterface{
    /**@var InputFilter $inputFilter */
    protected $inputFilter;

    public function __construct($name= ""){
        parent::__construct("changepassword");
        $this->setAttribute("method", 'post');

        $this->add(
            array(
                'name' => 'currentpassword',
                'attributes' => array(
                    'type' => 'password',
                    'class' => 'form-control',
                ),
                'options' => array(
                    'label' => 'Current Password *'
                ),
            )
        );

        $this->add(
            array(
                'name' => 'password',
                'attributes' => array(
                    'type' => 'password',
                    'class' => 'form-control',
                ),
                'options' => array(
                    'label' => 'New Password *'
                ),
            )
        );

        $this->add(
            array(
                'name' => 'cpassword',
                'attributes' => array(
                    'type' => 'password',
                    'class' => 'form-control',
                ),
                'options' => array(
                    'label' => 'Confirm New Password *',
                    'token' => 'password',
                ),
            )
        );

        $csfr = new Csrf('csrf');
        $this->add($csfr);

        $this->add(
            array(
                'name' => 'submit',
                'attributes' => array(
                    'type' => 'submit',
                    'value' => 'Update Password',
                    'class' => 'btn btn-default'
                ),

            )
        );
    }

    public function getCustomInputFilter(){
        if(! $this->inputFilter){
            $inputFilter = new InputFilter();
            $factory = new InputFactory();





            $inputFilter->add($factory->createInput(array(
                'name' => 'password',

                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    new StringLength(
                        array(
                            'encoding' => 'UTF-8',
                            'min' => 5,
                            'messages' => array(
                                StringLength::TOO_SHORT => 'New Password must be at-least 5 characters long.',


                            )

                        )
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'New Password cannot be empty.'
                            ),
                        ),
                    ),
                    array(
                        'name' => 'Identical',
                        'options' => array(
                            'token' => 'cpassword', // name of first password field
                            'messages' => array(
                                Identical::NOT_SAME => "New password does not match confirm new password. Try again.",
                            )
                        ),
                    ),

                ),
            )));

            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }
} 