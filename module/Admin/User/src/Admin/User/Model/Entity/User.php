<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/10/14
 * Time: 3:38 AM
 */
namespace Admin\User\Model\Entity;

use Zend\I18n\Validator\PhoneNumber;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\Factory as InputFactory;
use Zend\Validator\EmailAddress;
use Zend\Validator\StringLength;

class User implements InputFilterAwareInterface{
    public $user_id;
    public $firstname;
    public $lastname;
    public $email;
    public $telephone;
    public $password;
    public $address1;
    public $address2;
    public $city;
    public $postcode;
    public $state;
    public $country;
    public $newsletter;
    public $banned;
    public $email_verified;

    public $inputFilter;

    public function exchangeArray($data){
        $this->user_id = isset($data['user_id'])?$data['user_id']:null;
        $this->firstname = isset($data['firstname'])?$data['firstname']:null;
        $this->lastname = isset($data['lastname'])?$data['lastname']:null;
        $this->email = isset($data['email'])?$data['email']:null;
        $this->telephone = isset($data['telephone'])?$data['telephone']:null;
        $this->password = isset($data['password'])?$data['password']:null;
        $this->address1 = isset($data['address1'])?$data['address1']:null;
        $this->address2 = isset($data['address2'])?$data['address2']:null;
        $this->city = isset($data['city'])?$data['city']:null;
        $this->postcode = isset($data['postcode'])?$data['postcode']:null;
        $this->state = isset($data['state'])?$data['state']:null;
        $this->country = isset($data['country'])?$data['country']:null;
        $this->newsletter = isset($data['newsletter'])?$data['newsletter']:null;
        $this->banned = isset($data['banned'])?$data['banned']:null;
        $this->email_verified = isset($data['email_verified'])?$data['email_verified']:null;
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
                'name' => 'firstname',

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
                                StringLength::TOO_LONG => 'First name cannot be more than 35 characters.',


                            )

                        )
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'First name is required.'
                            ),
                        ),
                    ),

                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'lastname',

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
                                StringLength::TOO_LONG => 'Last name cannot be more than 35 characters.',


                            )

                        )
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Last name is required.'
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
                    new StringLength(
                        array(
                            'encoding' => 'UTF-8',
                            'max' => 96,
                            'messages' => array(
                                StringLength::TOO_LONG => 'Email cannot be more than 96 characters.',


                            )

                        )
                    ),
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