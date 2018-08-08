<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/10/14
 * Time: 6:46 AM
 */

namespace Admin\User\Form;
use Zend\Form\Form;
use Zend\Form\Element\Select;
class UserForm extends Form{
    public function __construct($name = null){
        parent::__construct("user");
        $this->setAttribute("method", "post");
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => "Save",
            )
        ));
        $this->setAttribute("id", "entityform");

        $this->add(
            array(
                'name' => 'user_id',
                'attributes' => array(
                    'type' => 'hidden',
                )
            )
        );

        $this->add(
            array(
                'name' => 'firstname',
                'attributes' => array(
                    'type' => 'text',

                ),
                'options' => array(
                    'label' => 'First Name'
                ),
            )
        );

        $this->add(
            array(
                'name' => 'lastname',
                'attributes' => array(
                    'type' => 'text',

                ),
                'options' => array(
                    'label' => 'Last Name'
                ),
            )
        );

        $this->add(
            array(
                'name' => 'email',
                'attributes' => array(
                    'type' => 'text',

                ),
                'options' => array(
                    'label' => 'Email'
                ),
            )
        );

        $this->add(
            array(
                'name' => 'telephone',
                'attributes' => array(
                    'type' => 'text',

                ),
                'options' => array(
                    'label' => 'Phone Number'
                ),
            )
        );

        $this->add(
            array(
                'name' => 'password',
                'attributes' => array(
                    'type' => 'password',

                ),
                'options' => array(
                    'label' => 'New Password'
                ),
            )
        );

        $this->add(
            array(
                'name' => 'address1',
                'attributes' => array(
                    'type' => 'text',

                ),
                'options' => array(
                    'label' => 'Address Line 1'
                ),
            )
        );

        $this->add(
            array(
                'name' => 'address2',
                'attributes' => array(
                    'type' => 'text',

                ),
                'options' => array(
                    'label' => 'Address Line 2'
                ),
            )
        );

        $this->add(
            array(
                'name' => 'city',
                'attributes' => array(
                    'type' => 'text',

                ),
                'options' => array(
                    'label' => 'City'
                ),
            )
        );

        $this->add(
            array(
                'name' => 'postcode',
                'attributes' => array(
                    'type' => 'text',

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

                ),
                'options' => array(
                    'label' => 'State'
                ),
            )
        );



        $this->add(
            array(
                'name' => 'country',
                'attributes' => array(
                    'type' => 'text',
                    'value' => 'Pakistan',
                    'readonly' => 'readonly',
                ),
                'options' => array(
                    'label' => 'Country',
                ),
            )
        );

        $select_newsletter = new Select();
        $select_newsletter->setName("newsletter");
        $select_newsletter->setLabel("Subscribe to newsletter");
        $value_options = array("1" => "Yes", "0" => "No");
        $select_newsletter->setValueOptions($value_options);
        $this->add($select_newsletter);

        $select_banned = new Select();
        $select_banned->setName("banned");
        $select_banned->setLabel("Account Status");
        $value_options = array("0" => "Active", "1" => "Banned");
        $select_banned->setValueOptions($value_options);
        $this->add($select_banned);

        $select_email_verfied = new Select();
        $select_email_verfied->setName("email_verified");
        $select_email_verfied->setLabel("Email Verification Status");
        $value_options = array("1" => "Verified", "0" => "Not Verified");
        $select_email_verfied->setValueOptions($value_options);
        $this->add($select_email_verfied);


    }
} 