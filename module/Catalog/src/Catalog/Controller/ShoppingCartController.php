<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/22/14
 * Time: 8:46 PM
 */

namespace Catalog\Controller;


use Application\Controller\ActionController;
use Zend\Session\Container;
use Zend\View\Model\JsonModel;

class ShoppingCartController extends ActionController{
    public function addAction(){
        return $this->addItemForGuest();
    }
    public function addItemForGuest(){
        $data = $this->getRequest()->getPost();
        $response = array();
        $cart = new Container('cart');
        if($cart && $cart->items){
            if(((int)$data['qty']) == 0){
                $temp = $cart->items;
                unset($temp['product_'.$data['product_id']]);
                $cart->items = $temp;

            } else {
                if(isset($cart->items['product_'.$data['product_id']])){
                    $details = explode("-||-", $cart->items['product_'.$data['product_id']] );
                    $response['updatedFrom'] = $details[1];

                }
                $cart->items['product_'.$data['product_id']] = $data['option']."-||-".$data['qty'];

            }
        }
        else{

            $cart->items = array('product_'.$data['product_id'] => $data['option']."-||-".$data['qty'] );
        }

        return new JsonModel($response);
    }
    public function summaryAction(){
        $response = array();
        $cart = new Container('cart');
        $param = $this->params()->fromRoute('last');
        if($param){
            $cart = new Container('cart_last');
        }

        if($cart && $cart->items){
            $response['status'] = 'contains';
            $response['products'] = array();
            foreach($cart->items as $k => $item){
                $product = explode("_", $k);
                $product_id = (int) $product[1];
                $product = $this->getEntityManager()->getRepository('Catalog\Entity\Product')->find($product_id);
                $option_cart = explode("-||-", $item);
                $option = $this->getEntityManager()->getRepository('Catalog\Entity\ProductOption')->findOneBy(array('product' =>$product_id, 'optionTitle' => $option_cart[0]));
                $options = $this->getEntityManager()->getRepository('Catalog\Entity\ProductOption')->findBy(array('product' => $product_id));
                if($product && $option){
                    $option_array = array();
                    foreach ($options as $opt){
                        $option_array[] = array(
                            'option_title' => $opt->getOptionTitle(),
                            'available_qty' => $opt->getAvailableQty(),
                        );
                    }
                    $temp = array(
                        'product_id' => $product_id,
                        'title' => $product->getTitle(),
                        'main_image' => $product->getMainImage(),
                        'price' => $product->getPrice(),
                        'option' => array(
                            'title' => $option->getOptionTitle(),
                            'available_qty' => $option->getAvailableQty(),
                        ),
                        'order_qty' => $option_cart[1],
                        'options' =>$option_array,

                    );


                    $response['products'][] = $temp;
                }


            }
        }
        else{
            $response['status'] = 'empty';
        }
        if($param){
            $cart->getManager()->getStorage()->clear('cart_last');
        }
        return new JsonModel($response);
    }

    public function indexAction(){
        return array();
    }

} 