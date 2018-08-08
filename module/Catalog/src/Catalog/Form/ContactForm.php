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
use Zend\Form\Element\Textarea;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

use Zend\InputFilter\InputFilterAwareInterface;

use Zend\InputFilter\Factory as InputFactory;

use Zend\Validator\StringLength;


class ContactForm extends Form implements InputFilterAwareInterface{
    /**@var InputFilter $inputFilter */
    protected $inputFilter;

    public function __construct($name= ""){
        parent::__construct("register");
        $this->setAttribute("method", 'post');
        $this->add(
            array(
                'name' => 'yourname',
                'attributes' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                ),
                'options' => array(
                    'label' => 'Your Name *'
                ),
            )
        );

        $this->add(
            array(
                'name' => 'email',
                'attributes' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                ),
                'options' => array(
                    'label' => 'Your Email Address *'
                ),
            )
        );


        $this->add(
            array(
                'name' => 'subject',
                'attributes' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                ),
                'options' => array(
                    'label' => 'Subject *'
                ),
            )
        );

        $message = new Textarea();
        $message->setAttribute('class', 'form-control');
        $message->setName("message");
        $message->setLabel("Message *");
        $this->add($message);

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
                    'value' => 'Submit',
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
                'name' => 'yourname',

                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(

                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Your name is required.'
                            ),
                        ),
                    ),

                ),
            )));



            $inputFilter->add($factory->createInput(array(
                'name' => 'email',

                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
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
                'name' => 'message',

                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(

                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Message is required.'
                            ),
                        ),
                    ),

                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'subject',

                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Subject is required.'
                            ),
                        ),
                    ),

                ),
            )));


            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }
} 