<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/21/14
 * Time: 4:29 PM
 */

namespace Catalog\Controller;


use Application\Controller\ActionController;

class HomeController extends ActionController{
    public function indexAction(){
        $settings = $this->getEntityManager()->getRepository('Catalog\Entity\Setting');
        $featured_ids = $settings->findOneBy(array('settingKey' => 'featured_products'));
        $new_arrival_count = $settings->findOneBy(array('settingKey' => 'new_arrival_count'));
        if(!$featured_ids){
            throw new \Exception("Cannot find featured_products in settings.");
            return;
        }
        if(!$new_arrival_count){
            throw new \Exception("Cannot find new_arrival_count in settings");
            return;
        }
        $featured = array();
        $ids = explode(",", $featured_ids->getSettingValue());

        $products = $this->getEntityManager()->getRepository('Catalog\Entity\Product');
        foreach($ids as $id){
            $id = (int)$id;
            $product = $products->find($id);
            if($product){
                $featured[] = $product;
            }
        }
        $new_arrival_count = (int)$new_arrival_count->getSettingValue();
        $new_arrival = $products->findBy(array(), array('productId' => 'DESC'), $new_arrival_count);

        return array(
            'featured' => $featured,
            'new_arrival' => $new_arrival
        );
    }
} 