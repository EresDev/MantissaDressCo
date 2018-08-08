<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 8/19/14
 * Time: 10:54 AM
 */

namespace Admin\AdminAccount\Controller;


use Application\Controller\ActionController;
use Zend\Mvc\Controller\AbstractActionController;

class AccountController extends ActionController{
    public function indexAction(){
        $em  = $this->getEntityManager();
        $orders = $em->getRepository('Catalog\Entity\Order')->findBy(
          array(), array('orderId' => 'DESC'), 10
        );
        $products = array();
        foreach($orders as $order){
            $products[] = $em->getRepository('Catalog\Entity\OrderProduct')->findBy(array('order' => $order->getOrderId()));
        }

        $reviews = $this->getEntityManager()->getRepository('Catalog\Entity\ProductReview')->findBy(
            array(), array('productReviewId' => 'DESC'), 10
        );

        return array(
           'orders' => $orders,
            'products' => $products,
            'reviews' => $reviews

        );
    }
} 