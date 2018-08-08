<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/11/14
 * Time: 6:26 AM
 */

namespace Admin\Order\Form;


use Zend\Form\Element\Select;
use Zend\Form\Form;

class OrderForm extends Form{
    public function __construct($name = null){
        parent::__construct("order");
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
                'name' => 'order_id',
                'attributes' => array(
                    'type' => 'hidden',
                )
            )
        );

        $this->add(
            array(
                'name' => 'order_datetime',
                'attributes' => array(
                    'type' => 'text',
                    'readonly' => 'readonly',
                ),
                'options' => array(
                    'label' => 'Order DateTime'
                ),
            )
        );

        $ostatus = new Select();
        $ostatus->setValueOptions(
          array(
            0 =>'Pending',
            1 =>   "Complete"
            )
        );
        $ostatus->setName("ostatus");
        $ostatus->setLabel("Order Status");

        $this->add($ostatus);


        $this->add(
            array(
                'name' => 'user_id',
                'attributes' => array(
                    'type' => 'hidden',

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
                    'label' => 'Order By User (email autofill)'
                ),
            )
        );

        $this->add(
            array(
                'name' => 'order_address_id',
                'attributes' => array(
                    'type' => 'hidden',
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
                    'label' => 'Shipping Address Line 1'
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
                    'label' => 'Shipping Address Line 2'
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
                    'readonly' => 'readonly'
                ),
                'options' => array(
                    'label' => 'Country'
                ),
            )
        );

        $this->add(
            array(
                'name' => 'order_product_id[]',
                'attributes' => array(
                    'type' => 'hidden',

                ),

            )
        );

        $this->add(
            array(
                'name' => 'product_id[]',
                'attributes' => array(
                    'type' => 'hidden',

                ),

            )
        );

        $this->add(
            array(
                'name' => 'title[]',
                'attributes' => array(
                    'type' => 'Collection',
                    'class' => 'product_title'
                ),
                'options' => array(
                    'label' => 'Product name (auto-fill)',
                    'inarrayvalidator' => true,
                ),
            )
        );

        $this->add(
            array(
                'name' => 'product_option_title[]',
                'attributes' => array(
                    'type' => 'text',
                    'class' => 'product_option_title',
                ),
                'options' => array(
                    'label' => 'Product Option'
                ),
            )
        );

        $this->add(
            array(
                'name' => 'quantity[]',
                'attributes' => array(
                    'type' => 'text',
                    'class' => 'quantity',
                ),
                'options' => array(
                'label' => 'Quantity'
                ),
            )
        );

        $this->add(
            array(
                'name' => 'unit_price[]',
                'attributes' => array(
                    'type' => 'text',
                    'class' => 'unit_price'
                ),
                'options' => array(
                    'label' => 'Unit Price'
                ),
            )
        );


    }
} 