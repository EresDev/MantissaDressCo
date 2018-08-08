<?php
namespace Admin\Product\Controller;
use Admin\Category\Model\CategoryTable;
use Admin\Product\Form\ProductForm;
use Admin\Product\Model\Entity\Product;
use Admin\Product\Model\Entity\ProductImage;
use Admin\Product\Model\Entity\ProductOption;
use Application\Controller\AdminActionController;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ProductController extends AdminActionController{
    private $productTable;
    private $productOptiontable;
    private $productImageTable;
    private $orderProductTable;

    public function indexAction()
    {
        $status = $this->params()->fromRoute('status', "");
        $success_message = "";
        switch($status){
            case "success-edit":
                $success_message = "Product updated successfully.";
                break;
            case "success-delete":
                $success_message = "Product/Products deleted successfully.";
                break;
            case "success-add":
                $success_message = "Product added successfully.";
                break;

        }
        $paginator = $this->getProductTable()->fetchAll(true);
        $paginator->setCurrentPageNumber((int)$this->params()->fromQuery('page', 1));
        $paginator->setItemCountPerPage(10);

        $view = new ViewModel(array(
            'products' => $paginator,
            'success_message' => $success_message,
        ));

        return $view;
    }

    public function addAction()
    {

        $categoryTable = $this->getServiceLocator()->get('Admin\Category\Model\CategoryTable');
        $form = new ProductForm(null, $categoryTable);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost()->toArray();

            $product = new Product();
            $form->setInputFilter($product->getInputFilter());
            $form->setData($post);

            if ($form->isValid()) {
                $product->exchangeArray($form->getData());

                $product->main_image = $this->getDbImage($product->main_image);
                $product->description = $this->getDbDescription($product->description);

                $product_id = $this->getProductTable()->saveProduct($product);

                //$this->getProductOptionTable()->deleteProductOptions($product_id);
                $post_data = $request->getPost();

                for($i= 0; $i< count($post_data["option_title"]); ++$i){
                    if(! $post_data["option_title"][$i]){
                        continue;
                    }
                    $prefix = "";
                    if($post_data['product_options']){
                        $prefix = $post_data['product_options'] . " || ";
                    }

                    $product_option = new ProductOption();
                    $product_option->exchangeArray(
                        array(
                            'product_id' => $product_id,
                            'option_title' => $prefix.$post_data["option_title"][$i],
                            'available_qty' => $post_data["available_qty"][$i],
                        )
                    );

                    $this->getProductOptionTable()->saveProductOption($product_option);
                }
                for($i= 0; $i< count($post_data["image"]); ++$i){
                    if(! $post_data["image"][$i]){
                        continue;
                    }
                    $product_image = new ProductImage();
                    $product_image->exchangeArray(
                        array(
                            'product_id' => $product_id,
                            'image' => $this->getDbImage($post_data["image"][$i]),

                        )
                    );

                    $this->getProductImageTable()->saveProductImage($product_image);
                }

                return $this->redirect()->toRoute('admin-product', array('status' => 'success-add'));
            }

        }

        $options = $this->getEntityManager()->getRepository('Catalog\Entity\Options')->findAll();

        $view = new ViewModel(array(
            'productform' => $form,

            'options' => $options,
        ));
        $view->setTemplate("admin/product/add");
        return $view;
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin-product', array(
                'action' => 'add'
            ));
        }
        $product = $this->getProductTable()->getProduct($id);

        $categoryTable = $this->getServiceLocator()->get('Admin\Category\Model\CategoryTable');
        $form = new ProductForm(null, $categoryTable);
        $product->description = $this->getViewDescription($product->description);
        $product->main_image = $this->getViewImage($product->main_image );
        $form->bind($product);


        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($product->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $form->getData()->main_image = $this->getDbImage($form->getData()->main_image);
                $form->getData()->description = $this->getDbDescription($form->getData()->description);

                $post = $request->getPost()->toArray();

                $prod = $form->getData();

                $product_id = $this->getProductTable()->saveProduct($prod);
                $this->getProductOptionTable()->deleteProductOptions($product_id);
                $post_data = $request->getPost();
                $prefix = "";
                if($post_data['product_options']){
                    $prefix = $post_data['product_options'] . " || ";
                }
                for($i= 0; $i< count($post_data["option_title"]); ++$i){
                    if(! $post_data["option_title"][$i]){
                        continue;
                    }
                    $product_option = new ProductOption();
                    $product_option->exchangeArray(
                        array(
                            'product_id' => $product_id,
                            'option_title' => $prefix.$post_data["option_title"][$i],
                            'available_qty' => $post_data["available_qty"][$i],
                        )
                    );

                    $this->getProductOptionTable()->saveProductOption($product_option);
                }

                $this->getProductImageTable()->deleteProductImage($product_id);
                for($i= 0; $i< count($post_data["image"]); ++$i){

                    if(! $post_data["image"][$i]){
                        continue;
                    }
                    $product_image = new ProductImage();
                    $product_image->exchangeArray(
                        array(
                            'product_id' => $product_id,
                            'image' => $this->getDbImage($post_data["image"][$i]),

                        )
                    );

                    $this->getProductImageTable()->saveProductImage($product_image);
                }

                return $this->redirect()->toRoute('admin-product', array('status' => 'success-edit'));
            }
        }
        $productImages = $this->getProductImageTable()->getProductImages($id);
        $options = $this->getEntityManager()->getRepository('Catalog\Entity\Options')->findAll();

        $view = new ViewModel(array(
            'product_id' => $id,
            'productform' => $form,
            'productOptions' => $this->getProductOptionTable()->getProductOptions($id),
            'productImages' => $productImages,

            'options' => $options,

        ));
        $view->setTemplate("admin/product/add");
        return $view;
    }

    public function deleteAction()
    {
        $request = $this->getRequest();
        if($request->isPost()){
            $products = $request->getPost()->toArray();
            $table = $this->getOrderProductTable();
            if(isset($products["products"])){
                foreach($products["products"] as $product_id){
                    $review = $this->getEntityManager()->getRepository('Catalog\Entity\ProductReview')->findOneBy(
                        array(
                            'product' => $product_id,
                        )
                    );
                    if($review){
                        $this->getEntityManager()->remove($review);
                        $this->getEntityManager()->flush();
                    }
                    $this->getEntityManager()->flush();
                    $title = $this->getProductTable()->getProductTitle((int)$product_id);
                    $table->setOrderProductNull($product_id, $title);
                    $this->getProductOptionTable()->deleteProductOptions($product_id);
                    $this->getProductImageTable()->deleteProductImage($product_id);
                    $this->getProductTable()->deleteProduct($product_id);
                }
                //$this->success_message = "Product/Products deleted successfully.";
                return $this->redirect()->toRoute('admin-product', array('status' => 'success-delete'));
            }
        }
        return $this->redirect()->toRoute('admin-product');
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
    public function getProductImageTable(){
        if (! $this->productImageTable){
            $sm = $this->getServiceLocator();
            $this->productImageTable = $sm->get('Admin\Product\Model\ProductImageTable');
        }
        return $this->productImageTable;
    }
    public function getOrderProductTable(){
        if (! $this->orderProductTable){
            $sm = $this->getServiceLocator();
            $this->orderProductTable = $sm->get('Admin\Order\Model\OrderProductTable');
        }
        return $this->orderProductTable;
    }
} 