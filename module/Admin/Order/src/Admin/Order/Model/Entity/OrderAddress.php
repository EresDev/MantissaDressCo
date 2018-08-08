<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/11/14
 * Time: 4:25 AM
 */

namespace Admin\Order\Model\Entity;


class OrderAddress {
    public $order_address_id;
    public $address1;
    public $address2;
    public $city;
    public $postcode;
    public $state;
    public $country;
    public $order_id;

    public function exchangeArray($data){
        $this->order_address_id = isset($data['order_address_id'])? $data['order_address_id'] : null;
        $this->address1 = isset($data['address1']) ? $data['address1'] : null;
        $this->address2 = isset($data['address2']) ? $data['address2'] : null;
        $this->city = isset($data['city']) ? $data['city'] : null;
        $this->postcode = isset($data['postcode']) ? $data['postcode'] : null;
        $this->state = isset($data['state']) ? $data['state'] : null;
        $this->country = isset($data['country']) ? $data['country'] : null;
        $this->order_id = isset($data['order_id']) ? $data['order_id'] : null;
    }
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
} 