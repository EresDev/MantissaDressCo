<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/9/14
 * Time: 5:19 AM
 */

namespace Admin\Product\Model;


use Admin\Product\Model\Entity\ProductImage;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;

class ProductImageTable {

    public $tableGateway;

    public function __construct(TableGateway $tableGateway){
        $this->tableGateway = $tableGateway;
    }
    public function getProductImages($product_id)
    {
        $product_id = (int)$product_id;
        $resultSet = $this->tableGateway->select(function (Select $select) use ($product_id) {
            $where = new Where();
            $where->equalTo("product_id", $product_id);
            $select->where($where);
        });
        return $resultSet;
    }

    public function saveProductImage(ProductImage $image)
    {
        $data = array(
            'product_id' => $image->product_id,
            'image' => $image->image,
        );
        $this->tableGateway->insert($data);
    }

    public function deleteProductImage($product_id)
    {
        $where = new Where();
        $where->equalTo("product_id", $product_id);
        $this->tableGateway->delete($where);
    }
} 