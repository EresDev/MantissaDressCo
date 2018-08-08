<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/8/14
 * Time: 7:17 AM
 */

namespace Admin\Product\Model\Entity;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class ProductOption{
    public $product_option_id;
    public $product_id;
    public $option_title;
    public $available_qty;



    public function exchangeArray($data){
        $this->product_option_id = isset($data['product_option_id'])?$data['product_option_id']:null;
        $this->product_id = isset($data['product_id'])?$data['product_id']:null;
        $this->option_title = isset($data['option_title'])?$data['option_title']:null;
        $this->available_qty = isset($data['available_qty'])?$data['available_qty']:null;
    }
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }


} 