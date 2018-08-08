<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/26/14
 * Time: 10:50 AM
 */

namespace Catalog\Controller;


use Application\Controller\UserActionController;
use Catalog\Entity\Category;
use Catalog\Entity\InformationPage;
use Catalog\Entity\LoginHistory;
use Catalog\Entity\Order;
use Catalog\Entity\OrderAddress;
use Catalog\Entity\OrderProduct;
use Catalog\Entity\Product;
use Catalog\Entity\ProductImage;
use Catalog\Entity\ProductOption;
use Catalog\Entity\ProductReview;
use Catalog\Entity\Setting;
use Catalog\Entity\User;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\View\Model\JsonModel;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

class ApiController extends UserActionController{
    private $salt = 12345;

    private function validateRequest(){
        return false;
        $time = $this->params()->fromQuery('happen');
        $string = $this->salt.$time; // concate

        $token = md5(sha1($string));

        if($token != $this->params()->fromQuery('token')){
            return new JsonModel(array('status' => 'AUTHORIZATION FAILED.'));
        }
        else return false;
    }
    public function cAction(){
        $is_invalid = $this->validateRequest();
        if($is_invalid){
            return $is_invalid;
        }

        $entity =  $this->params()->fromRoute('entity', "");

        if(!$entity){
            return new JsonModel(array('status' => 'Invalid Entity Name. Received: '.$entity));
        }
        $post = $this->getRequest()->getPost()->toArray();
        $hydrator = new ClassMethods();
        if($entity == 'User'){
            $user = $this->getEntityManager()->getRepository('Catalog\Entity\User')->findOneBy(array('email' => $post['email']));
            if($user){
                return new JsonModel(array('status' => 'exist', 'message' => 'Entity retrieved successfully.'
                , 'entity' => $hydrator->extract($user)));
            }
        }
        if(! $this->getRequest()->isPost()){
            return new JsonModel(array('status' => 'This action only accepts POST requests.'));

        }


        $entity = ('\Catalog\Entity\\'.$entity);
        $ent = new $entity;

        $hydrator->hydrate($post, $ent);
        $this->getEntityManager()->persist($ent);
        $this->getEntityManager()->flush();
        return new JsonModel(array('status' => 'success', 'message' => 'Entity saved successfully.'
        , 'entity' => $hydrator->extract($ent)));

    }

    public function cOrderAction(){
        $is_invalid = $this->validateRequest();
        if($is_invalid){
            return $is_invalid;
        }

        $entity =  $this->params()->fromRoute('entity', "");

        if(!$entity){
            return new JsonModel(array('status' => 'Invalid Entity Name. Received: '.$entity));
        }
        if(! $this->getRequest()->isPost()){
            return new JsonModel(array('status' => 'This action only accepts POST requests.'));

        }
        $post = $this->getRequest()->getPost()->toArray();
        $user = $this->getEntityManager()->getRepository('Catalog\Entity\User')->find((int) $post['user']);
        if(!$user){
            return new JsonModel(array('status' => 'User not found with ID '.$post['user']));

        }
        $post['user'] = $user;
        $post['orderDatetime'] = new \DateTime();
        $hydrator = new ClassMethods();
        $entity = ('\Catalog\Entity\\'.$entity);

        $ent = new $entity;
        $hydrator->hydrate($post, $ent);
        $this->getEntityManager()->persist($ent);
        $this->getEntityManager()->flush();
        return new JsonModel(array('status' => 'success', 'message' => 'Entity saved successfully.',
         'entity' => $hydrator->extract($ent)));

    }
    public function rAction(){
        $is_invalid = $this->validateRequest();
        if($is_invalid){
            return $is_invalid;
        }
        $entity = $this->params()->fromRoute('entity', "");
        $entity_id = (int) $this->params()->fromRoute('entity_id', 0);
        if(!$entity_id){
            return new JsonModel(array('status' => 'Invalid Entity ID. Received: '.$entity_id));
        }
        if(!$entity){
            return new JsonModel(array('status' => 'Invalid Entity Name. Received: '.$entity));
        }

        $entity = $this->getEntityManager()->getRepository('Catalog\Entity\\'.$entity)->find($entity_id);

        $hydrator = new ClassMethods();
        $entity = $hydrator->extract($entity);
        return new JsonModel(
            array(
                'status' => 'success',
                'entity' => $entity
            )
        );

    }
    public function rbAction(){
        $is_invalid = $this->validateRequest();
        if($is_invalid){
            return $is_invalid;
        }
        $entity = $this->params()->fromRoute('entity', "");
        $entity_id = $this->params()->fromRoute('entity_id', '');
        if(!$entity_id){
            return new JsonModel(array('status' => 'Invalid Entity Barcode. Received: '.$entity_id));
        }
        if(!$entity){
            return new JsonModel(array('status' => 'Invalid Entity Barcode. Received: '.$entity));
        }

        $entity = $this->getEntityManager()->getRepository('Catalog\Entity\\'.$entity)->findOneBy(array('barcode' => $entity_id));
        if(!$entity){
            return new JsonModel(
                array(
                    'status' => 'error',
                    'message' => 'Entity not found with given info.'
                )
            );
        }
        $hydrator = new ClassMethods();
        $entity = $hydrator->extract($entity);
        return new JsonModel(
            array(
                'status' => 'success',
                'entity' => $entity
            )
        );

    }

    public function cOrderAddressAction(){
        $is_invalid = $this->validateRequest();
        if($is_invalid){
            return $is_invalid;
        }

        $entity =  $this->params()->fromRoute('entity', "");

        if(!$entity){
            return new JsonModel(array('status' => 'Invalid Entity Name. Received: '.$entity));
        }
        if(! $this->getRequest()->isPost()){
            return new JsonModel(array('status' => 'This action only accepts POST requests.'));

        }
        $post = $this->getRequest()->getPost()->toArray();
        $order = $this->getEntityManager()->getRepository('Catalog\Entity\Order')->find((int) $post['order']);
        if(!$order){
            return new JsonModel(array('status' => 'Order not found with ID '.$post['order']));

        }
        $post['order'] = $order;

        $hydrator = new ClassMethods();
        $entity = ('\Catalog\Entity\\'.$entity);

        $ent = new $entity;
        $hydrator->hydrate($post, $ent);
        $this->getEntityManager()->persist($ent);
        $this->getEntityManager()->flush();
        return new JsonModel(array('status' => 'success', 'message' => 'Entity saved successfully.',
            'entity' => $hydrator->extract($ent)));

    }

    public function isUserAction(){
        $is_invalid = $this->validateRequest();
        if($is_invalid){
            return $is_invalid;
        }
        $entity = $this->params()->fromRoute('entity', "");
        $entity_email =  $this->params()->fromRoute('entity_id', 0);
        if(!$entity_email){
            return new JsonModel(array('status' => 'Invalid Entity Email. Received: '.$entity_email));
        }
        if(!$entity){
            return new JsonModel(array('status' => 'Invalid Entity Name. Received: '.$entity));
        }

        $entity = $this->getEntityManager()->getRepository('Catalog\Entity\\'.$entity)->findOneBy(array('email' => $entity_email));
        if(!$entity){
            return new JsonModel(
                array(
                    'status' => 'no',
                    'message' => 'Entity with given info do not exist.',
                )
            );
        }
        $hydrator = new ClassMethods();
        $entity = $hydrator->extract($entity);
        return new JsonModel(
            array(
                'status' => 'yes',
                'entity' => $entity
            )
        );

    }
    public function optionsAction(){
        $is_invalid = $this->validateRequest();
        if($is_invalid){
            return $is_invalid;
        }
        $entity = $this->params()->fromRoute('entity', "");
        $entity_info =  $this->params()->fromRoute('entity_id', 0);
        if(!$entity_info){
            return new JsonModel(array('status' => 'Invalid Entity. Received: '.$entity_info));
        }
        if(!$entity){
            return new JsonModel(array('status' => 'Invalid Entity Name. Received: '.$entity));
        }

        $entity = $this->getEntityManager()->getRepository('Catalog\Entity\\'.$entity)->findBy(array('product' => (int)$entity_info));
        if(count($entity) == 0){
            return new JsonModel(
                array(
                    'status' => 'no',
                    'message' => 'Product does not have any options or product do not exist.',
                )
            );
        }
        $hydrator = new ClassMethods();
        $entities = array();
        foreach($entity as $en){
            $entities[] = $hydrator->extract($en);;
        }


        return new JsonModel(
            array(
                'status' => 'yes',
                'entities' => $entities
            )
        );

    }

    public function confirmAction(){
        $is_invalid = $this->validateRequest();
        if($is_invalid){
            return $is_invalid;
        }
        $order_id =  (int) $this->params()->fromRoute('entity_id', 0);

        $orderProduct = $this->getEntityManager()->getRepository('Catalog\Entity\OrderProduct')->findOneBy(array('order' => $order_id));
        if(!$orderProduct){
            return new JsonModel(
                array(
                    'status' => 'no',
                    'message' => 'Could not find order product.'
                )
            );
        }
        /** @var ProductOption $product_option */
        $product_option = $this->getEntityManager()->getRepository('Catalog\Entity\ProductOption')->findOneBy(array('optionTitle' => $orderProduct->getProductOptionTitle(), 'product' => $orderProduct->getProduct()->getProductId()));

        if(!$product_option){
            return new JsonModel(
                array(
                    'status' => 'no',
                    'message' => 'Could not find product option.'
                )
            );
        }

        $qty = $product_option->getAvailableQty();
        if($qty > 0){
            $product_option->setAvailableQty($qty-1);
            $this->getEntityManager()->persist($product_option);
            $this->getEntityManager()->flush();
        }

        $order = $this->getEntityManager()->getRepository('Catalog\Entity\Order')->find($order_id);
        if(!$order_id){
            return new JsonModel(
                array(
                    'status' => 'no',
                    'message' => 'Could not find order.'
                )
            );
        }

        $products = $this->getEntityManager()->getRepository('Catalog\Entity\OrderProduct')->findBy(array('order' => $order_id));
        if(count($products) == 0){
            return new JsonModel(
                array(
                    'status' => 'no',
                    'message' => 'Could not find order products.'
                )
            );
        }

        $address = $this->getEntityManager()->getRepository('Catalog\Entity\OrderAddress')->findOneBy(array('order' => $order_id));
        if(!$address){
            return new JsonModel(
                array(
                    'status' => 'no',
                    'message' => 'Could not find order address.'
                )
            );
        }
        $this->sendEmailConfirmations($order, $products, $address);


        return new JsonModel(
            array(
                'status' => 'yes',
                'message' => 'Product Option Qty updated. Email sent to user.'
            )
        );


    }

    private function sendEmailConfirmations($order, $products, $address){
        $rep = $this->getEntityManager()->getRepository('Catalog\Entity\Setting');

        $currency_prefix = $rep->findOneBy(array('settingKey' => 'currency_prefix'));
        if(!$currency_prefix){
            throw new \Exception("currency_prefix was not found.");
        }

        $currency_postfix = $rep->findOneBy(array('settingKey' => 'currency_postfix'));
        if(!$currency_postfix){
            throw new \Exception("currency_postfix was not found.");
        }

        $site_name = $rep->findOneBy(array('settingKey' => 'site_name'));
        if(!$site_name){
            throw new \Exception("site_name was not found.");
        }


        $from_email = $rep->findOneBy(array('settingKey' => 'from_email'));
        if(!$from_email){
            throw new \Exception("from_email was not found.");
        }

        $email_footer = $rep->findOneBy(array('settingKey' => 'email_footer'));
        if(!$email_footer){
            throw new \Exception("email_footer was not found.");
        }

        $site_email = $rep->findOneBy(array('settingKey' => 'site_email'));
        if(!$site_email){
            throw new \Exception("site_name was not found.");
        }

        /** @var User $user */
        $user = $order->getUser();


        $order_html = $this->getOrderHtml($order, $products, $address, $currency_prefix->getSettingValue(), $currency_postfix->getSettingValue());
        $htmlMarkup_to_user = "".
            "<h1>".$site_name->getSettingValue()."</h1><hr>".
            "Hello ".$user->getLastname().",<br>".
            "<p>Thank you for shopping with us Find below the details of your order.</p>".
            $order_html."<br>".
            $email_footer->getSettingValue();


        $html = new MimePart($htmlMarkup_to_user);
        $html->type = "text/html";

        $body = new MimeMessage();
        $body->setParts(array($html));

        $message = new Message();
        $message->addFrom($from_email->getSettingValue(), $site_name->getSettingValue())
            ->addTo($user->getEmail())
            ->setSubject("Order Confirmation");

        $message->setBody($body);
        $transport = new Sendmail();
        $transport->send($message);

        $htmlMarkup_to_admin = "".
            "<h1>".$site_name->getSettingValue()."</h1><hr>".
            "Hello,<br>".
            "<p>A new order has been received.</p>".
            $order_html."<br>";


        $html = new MimePart($htmlMarkup_to_admin);
        $html->type = "text/html";

        $body = new MimeMessage();
        $body->setParts(array($html));

        $message = new Message();
        $message->addFrom($from_email->getSettingValue(), $site_name->getSettingValue())
            ->addTo($site_email->getSettingValue())
            ->setSubject("New Order");

        $message->setBody($body);
        $transport = new Sendmail();
        $transport->send($message);
    }
    private function getOrderHtml($order, $products, $address, $currency_prefix, $currency_postfix){
        $order_html = '<table border="1">' . '<tr>' . '<th colspan="3">' . 'Order ID: ' . $order->getOrderId() . '</th>' . '</tr>' . '<tr>' . '<th>Product</th>' . '<th>Quantity</th>' . '<th>Price</th>' . '</tr>';

        $total = 0.0;
        foreach ($products as $product) {
            $total += ($product->getQuantity() * $product->getUnitPrice());

            $order_html .= '<tr>' . '<td><a href="' . rawurldecode(rawurlencode($this->url()->fromRoute('product', array(
                    'product_id' => $product->getProduct()->getProductId()
                ), array(
                    'force_canonical' => true
                )))) . '" >' . $product->getProduct()->getTitle() . '</a></td>' . '<td>' . $product->getQuantity() . '</td>' . '<td>' . $product->getQuantity() . 'x' . $product->getUnitPrice() . '
			= ' . number_format((float) ($product->getQuantity() * $product->getUnitPrice()), 2, '.', '') . '
		</td>
	</tr>';


        }

        $order_html .= '<tr>' . '<td colspan="3" style="text-align:right;">' . 'Total: ' . $currency_prefix . number_format((float) ($total), 2, '.', '') . $currency_postfix. '</td>' . '</tr>' . '<tr>' . '<th colspan="3">Shipping Details</th>' . '</tr>' .
            '<tr>' . '<td colspan="3">' . $order->getUser()->getFirstname(). " " .$order->getUser()->getLastname(). '</td>' . '</tr>'.
            '<tr>' . '<td colspan="3">' . $address->getAddress1() . '</td>' . '</tr>';

        if ($address->getAddress2()) {
            $order_html .= '<tr>' . '<td colspan="3">' . $address->getAddress1() . '</td>' . '</tr>';
        }
        $order_html .= '<tr>' . '<td colspan="3">' . $address->getCity() . ",";
        if ($address->getPostcode()) {
            $order_html .= $address->getPostcode() . ",";
        }
        $order_html .= $address->getState() . ",";
        $order_html .= $address->getCountry();
        '</td>' . '</tr>' . '</table>';
        return $order_html;
    }

    public function cOrderProductAction(){
        $is_invalid = $this->validateRequest();
        if($is_invalid){
            return $is_invalid;
        }


        $post = $this->getRequest()->getPost()->toArray();

        $product = new OrderProduct();

        $order = $this->getEntityManager()->getRepository('Catalog\Entity\Order')->find((int) $post['order']);
        $product->setOrder($order);

        $orderProduct = $this->getEntityManager()->getRepository('Catalog\Entity\Product')->find((int) $post['product']);
        $product->setProduct($orderProduct);

        $product->setProductOptionTitle($post['productOptionTitle']);

        $product->setQuantity((int)$post['quantity']);

        $product->setUnitPrice((float)$post['unitPrice']);

        $this->getEntityManager()->persist($product);
        $this->getEntityManager()->flush();

        $hydrator = new ClassMethods();
        return new JsonModel(array('status' => 'success', 'message' => 'Entity saved successfully.',
            'entity' => $hydrator->extract($product)));
    }
} 