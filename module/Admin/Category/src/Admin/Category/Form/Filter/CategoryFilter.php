<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 8/23/14
 * Time: 4:16 PM
 */

namespace Admin\Category\Form\Filter;


use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\Factory as InputFactory;
use Zend\Validator\StringLength;

class CategoryFilter implements InputFilterAwareInterface
{
    protected $inputFilter;

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not Used!");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {

            $inputFilter = new InputFilter();
            $factory = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name' => 'title',

                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    new StringLength(
                        array(
                            'encoding' => 'UTF-8',

                            'max' => 100,
                            'messages' => array(
                                StringLength::TOO_LONG => 'Category name cannot be more than 100 characters.',


                            )

                        )
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Category name is required.'
                            ),
                        ),
                    ),

                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'barcode',

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
                                StringLength::TOO_LONG => 'Barcode cannot be more than 128 characters.',


                            )

                        )
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Barcode is required.'
                            ),
                        ),
                    ),

                ),
            )));



            $inputFilter->add($factory->createInput(array(
                'name' => 'parent_category_id',

                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    array('name' => 'Int'),
                ),
                'validators' => array (
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Please select a parent category.'
                            ),
                        ),
                    ),
                )
            )));






            $this->inputFilter = $inputFilter;

        }
        return $this->inputFilter;
    }
} 