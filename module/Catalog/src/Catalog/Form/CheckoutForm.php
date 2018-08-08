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

use Zend\Validator\StringLength;


class CheckoutForm extends Form implements InputFilterAwareInterface{
    /**@var InputFilter $inputFilter */
    protected $inputFilter;

    public function __construct($name= ""){
        parent::__construct("checkout");
        $this->setAttribute("method", 'post');
        $this->add(
            array(
                'name' => 'firstname',
                'attributes' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'readonly' => 'readonly',
                ),
                'options' => array(
                    'label' => 'First Name *'
                ),
            )
        );
        $this->add(
            array(
                'name' => 'lastname',
                'attributes' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'readonly' => 'readonly',
                ),
                'options' => array(
                    'label' => 'Last Name *'
                ),
            )
        );




        $this->add(
            array(
                'name' => 'telephone',
                'attributes' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                ),
                'options' => array(
                    'label' => 'Phone Number'
                ),
            )
        );


        $this->add(
            array(
                'name' => 'address1',
                'attributes' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                ),
                'options' => array(
                    'label' => 'Shipping Address Line 1 *'
                ),
            )
        );

        $this->add(
            array(
                'name' => 'address2',
                'attributes' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                ),
                'options' => array(
                    'label' => 'Shipping Address Line 2'
                ),
            )
        );

        $this->add(
            array(
                'name' => 'city',
                'attributes' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                ),
                'options' => array(
                    'label' => 'City *'
                ),
            )
        );

        $this->add(
            array(
                'name' => 'postcode',
                'attributes' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                ),
                'options' => array(
                    'label' => 'Postcode'
                ),
            )
        );

        $this->add(
            array(
                'name' => 'state',
                'attributes' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                ),
                'options' => array(
                    'label' => 'State *'
                ),
            )
        );

        $this->add(
            array(
                'name' => 'country',
                'attributes' => array(
                    'type' => 'text',
                    'readonly' => 'readonly',
                    'value' => 'Pakistan',
                    'class' => 'form-control',
                ),
                'options' => array(
                    'label' => 'Coutnry *'
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
                    'value' => 'Place Order',
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
                'name' => 'address1',

                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    new StringLength(
                        array(
                            'encoding' => 'UTF-8',

                            'max' => 35,
                            'messages' => array(
                                StringLength::TOO_LONG => 'Address line 1 cannot be more than 128 characters.',


                            )

                        )
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Address line 1 is required.'
                            ),
                        ),
                    ),

                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'address2',
                'required' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    new StringLength(
                        array(
                            'encoding' => 'UTF-8',
                            'max' => 35,
                            'messages' => array(
                                StringLength::TOO_LONG => 'Address line 2 cannot be more than 128 characters.',


                            )

                        )
                    ),

                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'city',

                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    new StringLength(
                        array(
                            'encoding' => 'UTF-8',

                            'max' => 128,
                            'messages' => array(
                                StringLength::TOO_LONG => 'City cannot be more than 128 characters.',


                            )

                        )
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'City is required.'
                            ),
                        ),
                    ),

                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'postcode',
                'required' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    new StringLength(
                        array(
                            'encoding' => 'UTF-8',

                            'max' => 128,
                            'messages' => array(
                                StringLength::TOO_LONG => 'Postcode cannot be more than 128 characters.',


                            )

                        )
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'state',

                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    new StringLength(
                        array(
                            'encoding' => 'UTF-8',

                            'max' => 128,
                            'messages' => array(
                                StringLength::TOO_LONG => 'State cannot be more than 128 characters.',


                            )

                        )
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'State is required.'
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