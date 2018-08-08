<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 8/23/14
 * Time: 1:13 PM
 */

namespace Admin\Category\Controller;


use Admin\Category\Form\CategoryForm;

use Application\Controller\AdminActionController;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Category\Model\Entity\Category;

class CategoryController extends AdminActionController{
    protected $_categoryTable;

    public function getCategoryTable(){
        if(!$this->_categoryTable){
            $sm = $this->getServiceLocator();
            $this->_categoryTable = $sm->get('Admin\Category\Model\CategoryTable');
        }
        return $this->_categoryTable;
    }

    public function indexAction(){
        $request = $this->getRequest();
        $success_add = false;
        if($request->isPost()){
            $categories = $request->getPost()->toArray();
            $sm = $this->getServiceLocator();
            $productTable = $sm->get('Admin\Product\Model\ProductTable');
            if(isset($categories["categories"])){ // to differentiate between add and edit
                foreach($categories["categories"] as $category_id){
                    $productTable->setProductCategoryNull($category_id);
                    $this->getCategoryTable()->removeCategory($category_id);
                    $success_add = "Category/Categories deleted successfully.";
                }
            }
        }
        $categories = $this->getCategoryTable()->fetchAll();
        $categories->setCurrentPageNumber((int)$this->params()->fromQuery('page', 1));
        $categories->setItemCountPerPage(10);
        return array('categories' => $categories, 'category_table' => $this->getCategoryTable(), "success_add" => $success_add);
    }
    public function addAction(){
        $categoryform = new CategoryForm(null, $this->getCategoryTable());
        $request = $this->getRequest();
        if($request->isPost()){
            $post_data = $request->getPost()->toArray();
            $categoryform->setData($post_data);
            if($categoryform->isValid()){

                $category = new Category(
                    array(
                        'id' => 0,
                        'title' => $post_data["title"],
                        'description' => $this->getDbDescription($post_data["description"]),
                        'image'=> $this->getDbImage($post_data["image"]),
                        'enabled' =>    $post_data["enabled"],
                        'sort_order' => $post_data["sort_order"],
                        'meta_title' => $post_data["meta_title"],
                        'meta_description' => $post_data["meta_description"],
                        'meta_keyword' => $post_data["meta_keyword"],
                        'barcode' => $post_data["barcode"],
                        'parent_category_id' => $post_data["parent_category_id"]
                    )
                );

                $category_table = $this->getCategoryTable();
                $category_table->saveCategory($category);
                $indexaction = $this->indexAction();
                $indexaction["success_add"] = "Category added successfully.";
                $view = new ViewModel($indexaction);
                $view->setTemplate("admin/category/index");
                return $view;

            }

        }
        $view = new ViewModel(array("categoryform" => $categoryform));
        $view->setTemplate("admin/category/add");
        return $view;
    }

    public function editAction(){
        $category_id = $this->getRequest()->getQuery()->edit;
        $category_table = $this->getCategoryTable();
        $category = $category_table->getCategory($category_id);

        $categoryform = new CategoryForm(null, $this->getCategoryTable(), $category_id);
        $request = $this->getRequest();
        if($request->isPost()){
            $post_data = $request->getPost()->toArray();
            $categoryform->setData($post_data);
            if($categoryform->isValid()){

                $category = new Category(
                    array(
                        'id' => $post_data["category_id"],
                        'title' => $post_data["title"],
                        'description' => $this->getDbDescription($post_data["description"]),
                        'image'=> $this->getDbImage($post_data["image"]),
                        'enabled' =>    $post_data["enabled"],
                        'sort_order' => $post_data["sort_order"],
                        'meta_title' => $post_data["meta_title"],
                        'meta_description' => $post_data["meta_description"],
                        'meta_keyword' => $post_data["meta_keyword"],
                        'barcode' => $post_data["barcode"],
                        'parent_category_id' => $post_data["parent_category_id"]
                    )
                );


                $category_table->saveCategory($category);
                $indexaction = $this->indexAction();
                $indexaction["success_add"] = "Category updated successfully.";
                $view = new ViewModel($indexaction);
                $view->setTemplate("admin/category/index");
                return $view;

            }

        }

        $category->setDescription($this->getViewDescription($category->getDescription()));
        $category->setImage($this->getViewImage($category->getImage()));
        $view = new ViewModel(array(
            "categoryform" => $categoryform,
            "category" => $category
        ));
        $view->setTemplate("admin/category/edit");
        return $view;
    }

} 