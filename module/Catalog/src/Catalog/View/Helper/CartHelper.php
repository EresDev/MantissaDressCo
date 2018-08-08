<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/21/14
 * Time: 4:06 PM
 */

namespace Catalog\View\Helper;


use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\Session\Container;
use Zend\View\Helper\AbstractHelper;


class CartHelper extends AbstractHelper implements ServiceLocatorAwareInterface
{

    //use \Zend\ServiceManager\ServiceLocatorAwareTrait;
    protected $sm;
    protected $response;
    public function __invoke($key){
        if(!$key || ($key != "items" && $key != "cost")){
            new \Exception("Invalid setting key");
        }
        if(isset($this->response[$key])){
            return $this->response[$key];
        }
        $cart = new Container('cart');
        if($cart && $cart->items){
            /** @var EntityManager $em */
            $em = $this->getServiceLocator()->getServiceLocator()
                ->get('doctrine.entitymanager.orm_default');
            $repository = $em->getRepository('Catalog\Entity\Product');
            $this->response['cost'] = 0.00;
            foreach($cart->items as $k => $v){
                $product = explode("_", $k);
                $prodcut_id = (int) $product[1];
                $info = explode("-||-", $v);
                $qty = (int) $info[1];
                /** @var \Catalog\Entity\Setting $setting */
                $entity = $repository->find($prodcut_id);
                if($entity){
                    $this->response['cost'] += ($qty * $entity->getPrice());
                }

            }
            $this->response['items'] = count($cart->items);

        }
        else{
            $this->response["items"] = 0;
            $this->response['cost'] = 0.00;
        }
        return $this->response[$key];

    }
    public function getServiceLocator(){
        return $this->sm;
    }
    public function setServiceLocator(ServiceLocatorInterface $sm){
        $this->sm = $sm;
        return $this;
    }


} 