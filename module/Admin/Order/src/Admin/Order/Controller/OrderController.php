<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/11/14
 * Time: 8:23 AM
 */

namespace Admin\Order\Controller;


use Admin\Order\Form\OrderForm;
use Admin\Order\Model\Entity\Order;
use Admin\Order\Model\Entity\OrderAddress;
use Admin\Order\Model\Entity\OrderProduct;
use Application\Controller\ActionController;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class OrderController extends ActionController{
    public $orderTable;
    public $orderAddressTable;
    public $orderProductTable;

    public function indexAction()
    {
        $status = $this->params()->fromRoute('status', "");
        $success_message = "";
        $entity = "Order";
        switch($status){
            case "success-edit":
                $success_message = "$entity updated successfully.";
                break;
            case "success-delete":
                $success_message = "{$entity}/{$entity}s deleted successfully.";
                break;
            case "success-add":
                $success_message = "$entity added successfully.";
                break;

        }
        $paginator = $this->getOrderTable()->fetchAll(true);
        $paginator->setCurrentPageNumber((int)$this->params()->fromQuery('page', 1));
        $paginator->setItemCountPerPage(10);
        $view = new ViewModel(array(
            'entities' => $paginator,
            'success_message' => $success_message,
        ));

        return $view;
    }

    public function addAction()
    {
        $form = new OrderForm();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $entity = new Order();
            $form->setInputFilter($entity->getInputFilter());
            $form->setData($request->getPost());
            $post_data = $request->getPost();
            if ($form->isValid()) {
                $email = $post_data['email'];
                $user=  $this->getEntityManager()->getRepository('Catalog\Entity\User')
                    ->findOneBy(array('email' => $email));
                if(!$user){
                    $form->get('email')->setMessages(array("No user exists with selected email."));
                    $view = new ViewModel(array(
                        'entityform' => $form,
                    ));
                    $view->setTemplate("admin/order/add");
                    return $view;
                }
                else{
                    $post_data['user_id'] = $user->getUserId();
                }
                //print_r($post_data); exit;
                $entity->exchangeArray($post_data);
                $entity_id = $this->getOrderTable()->saveOrder($entity);
                $post_data['order_id'] = $entity_id;
                $order_address = new OrderAddress();
                $order_address->exchangeArray($post_data);
                $this->getOrderAddressTable()->saveOrderAddress($order_address);
                $total_products = count($post_data['title']);
                for($i=0; $i < $total_products; ++$i){
                    $order_product = new OrderProduct();
                    $order_product->exchangeArray(
                      array(
                          'order_product_id' => 0,
                          'order_id' => $entity_id,
                          'product_id' => $post_data['title'][$i],
                          'product_option_title' => $post_data['product_option_title'][$i],
                          'quantity' => $post_data['quantity'][$i],
                          'unit_price' => $post_data['unit_price'][$i],
                      )
                    );
                    $this->getOrderProductTable()->saveOrderProduct($order_product);
                }

                return $this->redirect()->toRoute('admin-order', array('status' => 'success-add'));
            }
        }

        $view = new ViewModel(array(
            'entityform' => $form,
        ));
        $view->setTemplate("admin/order/add");
        return $view;
    }

    public function editAction()
    {

        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin-order', array(
                'action' => 'add'
            ));
        }
        $entity = $this->getOrderTable()->getOrder($id);
        $entity2 = $this->getOrderAddressTable()->getOrderAddressOfOrder($entity->order_id);
        $form = new OrderForm();
        $form->bind($entity);
        $form->bind($entity2);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($entity->getInputFilter());
            $post_data = $request->getPost();
            $form->setData($post_data);

            if ($form->isValid()) {
                $email = $post_data['email'];
                $user=  $this->getEntityManager()->getRepository('Catalog\Entity\User')
                    ->findOneBy(array('email' => $email));
                if(!$user){
                    $form->get('email')->setMessages(array("No user exists with selected email."));
                    $view = new ViewModel(array(
                        'entityform' => $form,
                    ));
                    $view->setTemplate("admin/order/add");
                    return $view;
                }
                else{
                    $post_data['user_id'] = $user->getUserId();
                }
                $entity->exchangeArray($post_data);
                $entity_id = $this->getOrderTable()->saveOrder($entity);

                $post_data['order_id'] = $entity_id;
                $order_address = new OrderAddress();
                $order_address->exchangeArray($post_data);
                $this->getOrderAddressTable()->saveOrderAddress($order_address);
                $total_products = count($post_data['title']);
                $this->getOrderProductTable()->deleteOrderProducts($entity_id);
                for($i=0; $i < $total_products; ++$i){
                    $order_product = new OrderProduct();
                    $order_product->exchangeArray(
                        array(
                            'order_product_id' => 0,
                            'order_id' => $entity_id,
                            'product_id' => $post_data['title'][$i],
                            'product_option_title' => $post_data['product_option_title'][$i],
                            'quantity' => $post_data['quantity'][$i],
                            'unit_price' => $post_data['unit_price'][$i],
                        )
                    );
                    $this->getOrderProductTable()->saveOrderProduct($order_product);
                }

                return $this->redirect()->toRoute('admin-order', array('status' => 'success-edit'));
            }
        }
        $orderProducts = $this->getOrderProductTable()->getOrderProducts($id);

        $view = new ViewModel(array(
            'entity_id' => $id,
            'entityform' => $form,
            'orderProducts' => $orderProducts,
        ));
        $view->setTemplate("admin/order/add");
        return $view;
    }

    public function deleteAction()
    {
        $request = $this->getRequest();
        if($request->isPost()){
            $entities = $request->getPost()->toArray();
            if(isset($entities["entities"])){
                foreach($entities["entities"] as $entity_id){

                    $this->getOrderAddressTable()->deleteOrderAddressByOrderId($entity_id);
                    $this->getOrderProductTable()->deleteOrderProductsByOrderId($entity_id);
                    $this->getOrderTable()->deleteOrder($entity_id);
                }
                return $this->redirect()->toRoute('admin-order', array('status' => 'success-delete'));
            }
        }
        return $this->redirect()->toRoute('admin-order');
    }
    public function getOrderTable(){
        if(!$this->orderTable){
            $sm = $this->getServiceLocator();
            $this->orderTable = $sm->get('Admin\Order\Model\OrderTable');
        }
        return $this->orderTable;
    }
    public function getOrderAddressTable(){
        if(!$this->orderAddressTable){
            $sm = $this->getServiceLocator();
            $this->orderAddressTable = $sm->get('Admin\Order\Model\OrderAddressTable');
        }
        return $this->orderAddressTable;
    }
    public function getOrderProductTable(){
        if(!$this->orderProductTable){
            $sm = $this->getServiceLocator();
            $this->orderProductTable = $sm->get('Admin\Order\Model\OrderProductTable');
        }
        return $this->orderProductTable;
    }
} 