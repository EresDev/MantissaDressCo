<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/11/14
 * Time: 12:03 PM
 */

namespace Admin\Product\Controller;


use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class ProductAjaxController extends AbstractRestfulController{
    public $productTable;
    public $productOptiontable;
    public function productLikeAction(){
        $like = $this->params()->fromRoute('like', "");
        $results = $this->getProductTable()->getProductByNameLike($like);
        $products = array();
        foreach($results as $row){

            $products['titles'][] = $row->title." (ID: {$row->product_id})";
            $products['price'][] = $row->price;
            $products['ids'][] = $row->product_id;
        }
        $json = new JsonModel($products);

        return $json;
    }
    public function productOptionsAction(){
        $like = $this->params()->fromRoute('product_id', "");
        $results = $this->getProductOptionTable()->getProductOptions($like);
        $entities = array();
        foreach($results as $row){

            $entities['titles'][] = $row->option_title;
            $entities['ids'][] = $row->product_option_id;
            $entities['qty'][] = $row->available_qty;
        }
        $json = new JsonModel($entities);

        return $json;
    }
    public function getProductTable(){
        if(!$this->productTable){
            $sm = $this->getServiceLocator();
            $this->productTable = $sm->get('Admin\Product\Model\ProductTable');
        }
        return $this->productTable;
    }
    public function getProductOptionTable(){
        if (! $this->productOptiontable){
            $sm = $this->getServiceLocator();
            $this->productOptiontable = $sm->get('Admin\Product\Model\ProductOptionTable');
        }
        return $this->productOptiontable;
    }

} 