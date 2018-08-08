<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/19/14
 * Time: 10:37 PM
 */

namespace Admin\Setting\Model\Entity;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\Factory as InputFactory;
use Zend\Validator\Between;
use Zend\Validator\EmailAddress;
use Zend\Validator\StringLength;


class Setting implements InputFilterAwareInterface{
    public $setting_id;
    public $setting_key;
    public $setting_value;
    public $setting_name;
    public $setting_type;

    protected $inputFilter;

    public function exchangeArray($data){
        $this->setting_id = isset($data['setting_id']) ? $data['setting_id'] : null;
        $this->setting_key = isset($data['setting_key']) ? $data['setting_key'] : null;
        $this->setting_value = isset($data['setting_value']) ? $data['setting_value'] : null;
        $this->setting_name = isset($data['setting_name']) ? $data['setting_name'] : null;
        $this->setting_type = isset($data['setting_type']) ? $data['setting_type'] : null;
    }
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setInputFilter(InputFilterInterface $inputFilter){
        throw new \Exception("Method not in use.");
    }

    public function getInputFilter(){
        if(! $this->inputFilter){
            $inputFilter = new InputFilter();
            $factory = new InputFactory();
            $sameValidatorFor = array(
                'site_name',
                'site_description',
                'footer_content',

            );
            $optionalElements = array(
                'currency_prefix',
                'currency_postfix',
                'phone_number',
            );
            foreach($sameValidatorFor as $element){

                $inputFilter->add($factory->createInput(array(
                    'name' => $element,
                    'required' => true,
                    'filters' => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),

                    ),
                    'validators' => array(
                        array (
                            'name' => 'StringLength',
                            'options' => array (
                                'encoding' => 'UTF-8',

                                'max' => 500,
                                'messages' => array(
                                    StringLength::TOO_LONG => 'Value cannot be more than 500 characters.',
                                )
                            )
                        ),
                        array(
                            'name' => 'NotEmpty',
                            'options' => array(
                                'messages' => array(
                                    \Zend\Validator\NotEmpty::IS_EMPTY => 'Value is required.'
                                ),
                            ),
                        ),
                    ),
                )));
            }
            foreach($optionalElements as $element){

                $inputFilter->add($factory->createInput(array(
                    'name' => $element,
                    'required' => false,
                    'filters' => array(
                        array('name' => 'StripTags'),
                        //array('name' => 'StringTrim'),

                    ),
                    'validators' => array(
                        array (
                            'name' => 'StringLength',
                            'options' => array (
                                'encoding' => 'UTF-8',

                                'max' => 500,
                                'messages' => array(
                                    StringLength::TOO_LONG => 'Value cannot be more than 500 characters.',
                                )
                            )
                        ),

                    ),
                )));
            }

            $inputFilter->add($factory->createInput(array(
                'name' => 'site_email',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),

                ),
                'validators' => array(
                    array (
                        'name' => 'StringLength',
                        'options' => array (
                            'encoding' => 'UTF-8',

                            'max' => 500,
                            'messages' => array(
                                StringLength::TOO_LONG => 'Value cannot be more than 500 characters.',
                            )
                        )
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Value is required.'
                            ),
                        ),
                    ),
                    array(
                        'name' => "EmailAddress"
                    )
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'site_url',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),

                ),
                'validators' => array(
                    array (
                        'name' => 'StringLength',
                        'options' => array (
                            'encoding' => 'UTF-8',

                            'max' => 500,
                            'messages' => array(
                                StringLength::TOO_LONG => 'Value cannot be more than 500 characters.',
                            )
                        )
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Value is required.'
                            ),
                        ),
                    ),
                    array(
                        'name' => 'Uri'
                    )
                ),
            )));

            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }
} 