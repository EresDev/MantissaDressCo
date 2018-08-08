<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/27/14
 * Time: 7:47 AM
 */

namespace Admin\Review\Filter;


use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\Factory as InputFactory;
use Zend\Validator\StringLength;

class ReviewFilter implements InputFilterAwareInterface{
    public $inputFilter;

    public function getInputFilter(){
        if(! $this->inputFilter){
            $inputFilter = new InputFilter();
            $factory = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name' => 'stars',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'),
                ),
                'validators' => array(
                    array(
                        'name' => 'Between',
                        'options' => array(
                            'min' => 1,
                            'max' => 5,

                        ),
                    )
                )
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'review',
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

                            'max' => 250,
                            'messages' => array(
                                StringLength::TOO_LONG => 'Review cannot be more than 250 characters.',
                            )
                        )
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Review is required.'
                            ),
                        ),
                    ),

                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'reviewerName',

                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),

                ),
                'validators' => array(
                    array (
                        'name' => 'StringLength',
                        'options' => array (
                            'encoding' => 'UTF-8',
                            'max' => 70,
                            'messages' => array(
                                StringLength::TOO_LONG => 'Reviewer name cannot be more than 250 characters.',
                            )
                        )
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Reviewer name is required.'
                            ),
                        ),
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