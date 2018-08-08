<?php
namespace Admin\Product\Model;

use Admin\Product\Model\Entity\Product;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class ProductTable
{
    protected $tableGatway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGatway = $tableGateway;
    }

    public function fetchAll($paginated = false)
    {
        if($paginated) {
            // create a new Select object for the table album
            $select = new Select('product');
            $select->order('product_id DESC');
            // create a new result set based on the Album entity
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Product());
            // create a new pagination adapter object
            $paginatorAdapter = new DbSelect(
            // our configured select object
                $select,
                // the adapter to run it against
                $this->tableGatway->getAdapter(),
                // the result set to hydrate
                $resultSetPrototype
            );
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;
        }
        $resultSet = $this->tableGatway->select(function(Select $select){
            $select->order('product_id DESC');
        });
        return $resultSet;
    }
    public function fetchAllIdTitle(){
        $resultSet = $this->tableGatway->select(function(Select $select){
            $select->columns(array('product_id', 'title'));
            $select->order('product_id DESC');
        });
        return $resultSet;
    }

    public function getProduct($id)
    {
        $id = (int)$id;
        $rowset = $this->tableGatway->select(array('product_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Cannot find a product with ID: " . $id);
        }
        return $row;
    }

    public function saveProduct(Product $product)
    {
        $data = array(
            'category_id' => $product->category_id,
            'title' => $product->title,
            'description' => $product->description,
            'price' => number_format((float)$product->price, 2, '.', ''),
            'sort_order' => $product->sort_order,
            'meta_title' => $product->meta_title,
            'meta_description' => $product->meta_description,
            'enabled' => $product->enabled,
            'barcode' => $product->barcode,
            'main_image' => $product->main_image,

        );
        if(! (int) $product->category_id){
            $data['category_id'] = null;
        }
        $id = (int)$product->product_id;
        if ($id == 0) {
            $this->tableGatway->insert($data);
            $id = $this->tableGatway->getLastInsertValue();
        } else {
            if ($this->getProduct($id)) {
                $this->tableGatway->update($data, array('product_id' => $id));
            } else {
                throw new \Exception('Cannot find product with ID: '.$id);
            }
        }
        return $id;

    }
    public function setProductCategoryNull($category_id){
        $data = array(
            'category_id' => null
        );
        $id = (int) $category_id;
        $this->tableGatway->update($data, array('category_id' => $id));
    }
    public function deleteProduct($id)
    {
        $this->tableGatway->delete(array('product_id' => $id));
    }
    public function getProductByNameLike($like){
        $resultSet = $this->tableGatway->select(function(Select $select) use ($like){
            $select->columns(array('product_id', 'title', 'price'));
            $where = new Where();
            $where->like('title', $like."%");
            $select->where($where);
            $select->order('title');
        });

        return $resultSet;
    }
    public function getProductTitle($id)
    {
        $id = (int)$id;
        $rowset = $this->tableGatway->select(array('product_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Cannot find a product with ID: " . $id);
        }
        return $row->title;
    }
} 