<?php
namespace Admin\Category\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Admin\Category\Model\Entity\Category;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class CategoryTable extends AbstractTableGateway{
    protected $table = 'category';
    public function __construct(Adapter $adapter){
        $this->adapter = $adapter;
    }
    public function fetchAll2(){
        $resultSet = $this->select(function(Select $select){
            $select->order('category_id DESC');
        });
        $entities = array();
        foreach ($resultSet as $row){
            $entity = new Category();
            $entity->setId($row->category_id)
                ->setTitle($row->title)
                ->setDescription($row->description)
                ->setImage($row->image)
                ->setEnabled($row->enabled)
                ->setSortOrder($row->sort_order)
                ->setMetaTitle($row->meta_title)
                ->setMetaDescription($row->meta_description)
                ->setMetaKeyword($row->meta_keyword)
                ->setBarcode($row->barcode)
                ->setParentCategoryId($row->parent_category_id);
            $entities[] = $entity;
        }


        return $entities;
    }
    public function fetchAll()
    {

            // create a new Select object for the table album
            $select = new Select('category');
            $select->order('category_id DESC');
            // create a new result set based on the Album entity
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Category());
            // create a new pagination adapter object
            $paginatorAdapter = new DbSelect(
            // our configured select object
                $select,
                // the adapter to run it against
                $this->getAdapter(),
                // the result set to hydrate
                $resultSetPrototype
            );
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;


    }
    public function getCategory($id){
        $row = $this->select(array('category_id' => (int) $id))->current();
        if(! $row)
            return false;

        $category = new Category(array(
            'id' => $row->category_id,
            'title' => $row->title,
            'description' => $row->description,
            'image'=> $row->image,
            'enabled' =>    $row->enabled,
            'sort_order' => $row->sort_order,
            'meta_title' => $row->meta_title,
            'meta_description' => $row->meta_description,
            'meta_keyword' => $row->meta_keyword,
            'barcode' => $row->barcode,
            'parent_category_id' => $row->parent_category_id
        ));
        return $category;
    }

    public function saveCategory(Category $category){

        $data = array(
            'title' => $category->getTitle(),
            'description' => $category->getDescription(),
            'image'=> $category->getImage(),
            'enabled' =>    $category->getEnabled(),
            'sort_order' => $category->getSortOrder(),
            'meta_title' => $category->getMetaTitle(),
            'meta_description' => $category->getMetaDescription(),
            'meta_keyword' => $category->getMetaKeyword(),
            'barcode' => $category->getBarcode(),

        );
        $data["parent_category_id"] = (int) $category->getParentCategoryId();
        if(! (int) $category->getParentCategoryId()){
            $data["parent_category_id"] = null;
        }
        $id = (int) $category->getId();
        if($id == 0){
            if(!$this->insert($data))
                return false;
            return $this->getLastInsertValue();
        }
        else if($this->getCategory($id)){
            if(!$this->update($data, array('category_id' => $id))){
                return false;
            }
            return $id;
        }
        else return false;
    }

    public function removeCategory($id){
        $where= array(
            'parent_category_id' => (int) $id
        );
        $data= array(
            'parent_category_id' => null,
        );
        $this->update($data, $where);
        return $this->delete(array('category_id' => (int) $id));
    }
} 