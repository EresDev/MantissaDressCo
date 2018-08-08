<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/11/14
 * Time: 4:18 AM
 */
namespace Admin\Order\Model\Entity;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\Factory as InputFactory;
use Zend\Validator\Between;
use Zend\Validator\EmailAddress;
use Zend\Validator\StringLength;

class Order implements InputFilterAwareInterface{
    public $order_id;
    public $order_datetime;
    public $user_id;
    public $email;
    public $total_price;
    public $ostatus;
    protected $inputFilter;

    public function exchangeArray($data){
        $this->order_id = isset($data['order_id'])?$data['order_id']: null;
        $this->order_datetime = isset($data['order_datetime'])? $data['order_datetime'] : null;
        $this->user_id = isset($data['user_id']) ? $data['user_id'] : null;
        $this->email = isset($data['email']) ? $data['email'] : null;
        $this->total_price = isset($data['total_price']) ? $data['total_price'] : null;
        $this->ostatus = isset($data['ostatus']) ? $data['ostatus'] : null;
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

            $inputFilter->add($factory->createInput(array(
                'name' => 'order_id',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            )));
            $inputFilter->add($factory->createInput(array(
                'name' => 'user_id',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'order_address_id',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'order_product_id[]',
                'required' => false,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            )));
            $inputFilter->add($factory->createInput(array(
                'name' => 'product_id[]',
                'required' => false,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'email',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),

                ),
                'validators' => array(
                    array (
                        'name' => 'EmailAddress',
                        'options' => array (
                            'messages' => array(
                                EmailAddress::INVALID => 'Email is not valid.',
                            )
                        )
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'address1',
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

                            'max' => 128,
                            'messages' => array(
                                StringLength::TOO_LONG => 'Shipping address line 1 cannot be more than 128 characters.',
                            )
                        )
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Shipping address line 1 is required.'
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
                    array (
                        'name' => 'StringLength',
                        'options' => array (
                            'encoding' => 'UTF-8',
                            'max' => 128,
                            'messages' => array(
                                StringLength::TOO_LONG => 'Shipping address line 2 cannot be more than 128 characters.',
                            )
                        )
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'city',
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
                'name' => 'state',
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

            $inputFilter->add($factory->createInput(array(
                'name' => 'country',
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

                            'max' => 128,
                            'messages' => array(
                                StringLength::TOO_LONG => 'Country cannot be more than 128 characters.',
                            )
                        )
                    ),

                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Country is required.'
                            ),
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'postcode',
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

                            'max' => 100,
                            'messages' => array(
                                StringLength::TOO_LONG => 'Postcode cannot be more than 128 characters.',
                            )
                        )
                    ),

                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Postcode is required.'
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