<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/24/14
 * Time: 6:05 AM
 */

namespace Catalog\Controller;


use Catalog\Entity\OrderProduct;
use Catalog\Entity\Order;
use Application\Controller\UserActionController;
use Catalog\Entity\OrderAddress;
use Catalog\Entity\Product;
use Catalog\Entity\ProductOption;
use Catalog\Form\CheckoutForm;
use Zend\Session\Container;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\View\Model\ViewModel;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

class CheckoutController extends UserActionController{
    public function indexAction(){
        if (!$this->getAuthService()->hasIdentity()){
            $url = rawurlencode($this->url()->fromRoute('checkout', array(), array('force_canonical' => true)) );
            $this->flashmessenger()->addErrorMessage("You need to login or register to proceed to chechout.");
            return $this->redirect()->toRoute('register', array(), array('query' => array('redirect_to' => $url)));
        }
        $cart = new Container('cart');
        if(!($cart && $cart->items)){
            $this->flashmessenger()->addErrorMessage("Your cart is empty. Please add items to your cart first.");
            return $this->redirect()->toRoute('cart');
        }

        $form = new CheckoutForm();
        if($this->getRequest()->isPost()){
            $post = $this->getRequest()->getPost();
            $form->setData($post);
            $valid1 = $form->isValid();
            $valid2 = $form->isValid($form->setInputFilter($form->getCustomInputFilter()));
            if($valid1 && $valid2){
                $order = new Order();
                $em = $this->getEntityManager();
                $order->setUser($em->getRepository('Catalog\Entity\User')->find((int)$this->getAuthService()->getIdentity()));
                $order->setOrderDatetime(new \DateTime());
                $em->persist($order);
                $em->flush();
                $orderAddress = new OrderAddress();
                $hydrator = new ClassMethods();
                /** @var OrderAddress $orderAddress */
                $orderAddress = $hydrator->hydrate($post->toArray(), $orderAddress);
                $orderAddress->setOrder($order);
                $em->persist($orderAddress);
                $em->flush();
                $cart = new Container('cart');
                if($cart && $cart->items){
                    $already_sold = null;
                    $total_amount = 0.0;
                    $_order_products = array();
                    foreach($cart->items as $k => $item){

                        $product = explode("_", $k);
                        $product_id = (int) $product[1];
                        $option_cart = explode("-||-", $item);
                        $option_title = $option_cart[0];
                        $qty = (int) $option_cart[1];
                        /** @var Product $product */
                        $product = $em->getRepository('Catalog\Entity\Product')->find($product_id);
                        /** @var ProductOption $product_option */
                        $product_option = $em->getRepository('Catalog\Entity\ProductOption')->findOneBy(array('optionTitle' => $option_title));

                        if($product_option && $product_option->getAvailableQty() != -1 && $product_option->getAvailableQty() < $qty){
                            $already_sold = array($product, $product_option);
                            break;
                        }
                        /** @var OrderProduct $orderProduct */
                        $orderProduct = new OrderProduct();
                        $orderProduct->setOrder($order);
                        $orderProduct->setProduct($product);
                        $orderProduct->setProductOptionTitle($option_title);
                        $orderProduct->setQuantity($qty);
                        $orderProduct->setUnitPrice($product->getPrice());
                        $em->persist($orderProduct);
                        $total_amount += ($qty * $product->getPrice());
                        if($product_option->getAvailableQty() != -1){
                            $product_option->setAvailableQty($product_option->getAvailableQty() - $qty);
                            $em->persist($orderProduct);

                        }
                        $_order_products[] = $orderProduct;
                    }
                    if(!$already_sold){
                        $em->flush();
                        $this->sendEmailConfirmations($order, $_order_products, $orderAddress);
                        $cart_last = new Container('cart_last');
                        $cart_last->items = $cart->items;
                        $cart->getManager()->getStorage()->clear("cart");


                        $view = new ViewModel(
                            array(
                                'orderId' => $order->getOrderId(),
                                'totalAmount' => number_format((float)$total_amount, 2, '.', ''),
                                'post' => $post,
                            )
                        );
                        $view->setTemplate('catalog/checkout/checkout-success');
                        return $view;
                    }
                    else{
                        $this->flashMessenger()->addErrorMessage("Selected product/option '". $already_sold[0]->getTitle()."' is not available in requested quantity anymore.".
                        " Now the available quantity is ".$already_sold[1]->getAvailableQty().". Please fix this and try again.");
                        $this->redirect()->toRoute('cart');
                    }
                }

            }
        }
        else {
            $user = $this->getEntityManager()->getRepository('Catalog\Entity\User')->find((int)$this->getAuthService()->getIdentity());
            $hydrator = new ClassMethods();
            $user = $hydrator->extract($user);
            $form->setData($user);
        }
        return array(
            'form' => $form,
        );
    }

    private function getOrderHtml($order, $products, $address, $currency_prefix, $currency_postfix){
        $order_html = '<table border="1">' . '<tr>' . '<th colspan="3">' . 'Order ID: ' . $order->getOrderId() . '</th>' . '</tr>' . '<tr>' . '<th>Product</th>' . '<th>Quantity</th>' . '<th>Price</th>' . '</tr>';

        $total = 0.0;
        foreach ($products as $product) {
            $total += ($product->getQuantity() * $product->getUnitPrice());

            $order_html .= '<tr>' . '<td>' . $product->getProduct()->getTitle() . '</td>' . '<td>' . $product->getQuantity() . '</td>' . '<td>' . $product->getQuantity() . 'x' . $product->getUnitPrice() . '
			= ' . number_format((float) ($product->getQuantity() * $product->getUnitPrice()), 2, '.', '') . '
		</td>
	</tr>';


        }

        $order_html .= '<tr>' . '<td colspan="3" style="text-align:right;">' . 'Total: ' . $currency_prefix . number_format((float) ($total), 2, '.', '') . $currency_postfix. '</td>' . '</tr>' . '<tr>' . '<th colspan="3">Shipping Details</th>' . '</tr>' .
            '<tr>' . '<td colspan="3">' . $order->getUser()->getFirstname(). " " .$order->getUser()->getLastname(). '</td>' . '</tr>'.
            '<tr>' . '<td colspan="3">' . $address->getAddress1() . '</td>' . '</tr>';

        if ($address->getAddress2()) {
            $order_html .= '<tr>' . '<td colspan="3">' . $address->getAddress2() . '</td>' . '</tr>';
        }
        $order_html .= '<tr>' . '<td colspan="3">' . $address->getCity() . ",";
        if ($address->getPostcode()) {
            $order_html .= $address->getPostcode() . ",";
        }
        $order_html .= $address->getState() . ",";
        $order_html .= $address->getCountry().
        '</td>' . '</tr>' . '</table>';
        return $order_html;
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
        $user = $this->getEntityManager()->getRepository('Catalog\Entity\User')->find((int)$this->getAuthService()->getIdentity());


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
} 