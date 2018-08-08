<?php
namespace Admin\Product\Model\Entity;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\Factory as InputFactory;
use Zend\Validator\StringLength;

class Product implements InputFilterAwareInterface{
    public $product_id;
    public $category_id;
    public $title;
    public $description;
    public $price;
    public $sort_order;
    public $meta_title;
    public $meta_description;
    public $enabled;
    public $barcode;
    public $main_image;


    protected $inputFilter;

    public function exchangeArray($data){
        $this->product_id = isset($data['product_id'])? $data['product_id'] : null;
        $this->category_id = isset($data['category_id'])? $data['category_id'] : null;
        $this->title = isset($data['title'])? $data['title'] : null;
        $this->description = isset($data['description'])? $data['description'] : null;
        $this->price = isset($data['price'])? $data['price'] : null;
        $this->sort_order = isset($data['sort_order'])? $data['sort_order'] : null;
        $this->meta_title = isset($data['meta_title'])? $data['meta_title'] : null;
        $this->meta_description = isset($data['meta_description'])? $data['meta_description'] : null;
        $this->enabled = isset($data['enabled'])? $data['enabled'] : null;
        $this->barcode = isset($data['barcode'])? $data['barcode'] : null;
        $this->main_image = isset($data['main_image'])? $data['main_image'] : null;

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

//            $inputFilter->add(
//                $factory->createInput(
//                    array(
//                        'name'     => 'product_id',
//
//                        'filters'  => array(
//                            array('name' => 'Int'),
//                        ),
//                    )
//                )
//            );

            $inputFilter->add(
                $factory->createInput(
                    array(
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
                                        StringLength::TOO_LONG => 'Product name cannot be more than 100 characters.',


                                    )

                                )
                            ),
                            array(
                                'name' => 'NotEmpty',
                                'options' => array(
                                    'messages' => array(
                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'Product name is required.'
                                    ),
                                ),
                            ),

                        ),
                    )
                )
            );

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
                'name' => 'category_id',

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
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Please select a category.'
                            ),
                        ),
                    ),
                )
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'sort_order',


                'validators' => array (
                    array(
                        'name' => 'Int',
                        'options' => array(
                            'min' => 0,
                            'messages' => array(
                                \Zend\I18n\Validator\Int::NOT_INT=> 'Sort order must be an positive integer value.'
                            ),
                        ),
                    ),
                ),

            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'price',

                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'Float',
                        'options' => array(
                            'min' => 0,
                            'locale' => 'en_US',
                            'messages' => array(
                                \Zend\I18n\Validator\Float::NOT_FLOAT => 'Price must be a positive integer value with or without maximum 2 decimals.'
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