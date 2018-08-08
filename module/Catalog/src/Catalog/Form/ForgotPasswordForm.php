<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/22/14
 * Time: 11:00 PM
 */

namespace Catalog\Form;


use Zend\Captcha\Dumb;
use Zend\Form\Element\Captcha;
use Zend\Form\Element\Csrf;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilter;


use Zend\InputFilter\Factory as InputFactory;

class ForgotPasswordForm extends Form implements InputFilterAwareInterface{
    /**@var InputFilter $inputFilter */
    protected $inputFilter;
    public function __construct($name= ""){
        parent::__construct("forgotpasswordform");
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
        $captcha = new Captcha('captcha');
        $captcha
            ->setCaptcha(new Dumb())
            ->setAttribute('class', 'form-control')
            ->setLabel('Please verify you are human *');

        $this->add(
            $captcha
        );

        $csfr = new Csrf('csrf');
        $this->add($csfr);

        $this->add(
            array(
                'name' => 'submit',
                'attributes' => array(
                    'type' => 'submit',
                    'value' => 'Reset Password',
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


            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }
} 