<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/27/14
 * Time: 7:47 AM
 */

namespace Admin\Information\Filter;


use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\Factory as InputFactory;
use Zend\Validator\StringLength;

class InformationFilter implements InputFilterAwareInterface{
    public $inputFilter;

    public function getInputFilter(){
        if(! $this->inputFilter){
            $inputFilter = new InputFilter();
            $factory = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name' => 'sortOrder',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'),
                ),

            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'content',
                'required' => true,

                'validators' => array(
                    array (
                        'name' => 'StringLength',
                        'options' => array (
                            'encoding' => 'UTF-8',

                            'max' => 10000,
                            'messages' => array(
                                StringLength::TOO_LONG => 'Content cannot be more than 10000 characters.',
                            )
                        )
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Content is required.'
                            ),
                        ),
                    ),

                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'title',

                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),

                ),
                'validators' => array(
                    array (
                        'name' => 'StringLength',
                        'options' => array (
                            'encoding' => 'UTF-8',
                            'max' => 250,
                            'messages' => array(
                                StringLength::TOO_LONG => 'Title cannot be more than 250 characters.',
                            )
                        )
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Title is required.'
                            ),
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'metaTitle',
                'required' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),

                ),
                'validators' => array(
                    array (
                        'name' => 'StringLength',
                        'options' => array (
                            'encoding' => 'UTF-8',
                            'max' => 250,
                            'messages' => array(
                                StringLength::TOO_LONG => 'Meta title cannot be more than 250 characters.',
                            )
                        )
                    ),

                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'metaDescription',
                'required' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),

                ),
                'validators' => array(
                    array (
                        'name' => 'StringLength',
                        'options' => array (
                            'encoding' => 'UTF-8',
                            'max' => 1000,
                            'messages' => array(
                                StringLength::TOO_LONG => 'Meta description cannot be more than 1000 characters.',
                            )
                        )
                    ),

                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'metaKeywords',
                'required' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),

                ),
                'validators' => array(
                    array (
                        'name' => 'StringLength',
                        'options' => array (
                            'encoding' => 'UTF-8',
                            'max' => 1000,
                            'messages' => array(
                                StringLength::TOO_LONG => 'Meta keywords cannot be more than 1000 characters.',
                            )
                        )
                    ),

                ),
            )));

            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }
    public function setInputFilter(InputFilterInterface $inputFiler){
        throw new \Exception("Method not in use.");
    }

} 