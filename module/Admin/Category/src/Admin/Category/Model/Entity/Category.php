<?php
namespace Admin\Category\Model\Entity;

class Category {
    protected $_id;
    protected $_title;
    protected $_description;
    protected $_image;
    protected $_enabled;
    protected $_sort_order;
    protected $_meta_title;
    protected $_meta_description;
    protected $_meta_keyword;
    protected $_barcode;
    protected $_parent_category_id;

    public function exchangeArray($data = array()){

        $this->_id = isset($data['category_id'])?$data['category_id']:null;
        $this->_title = isset($data['title'])?$data['title']:null;
        $this->_description = isset($data['description'])?$data['description']:null;
        $this->_image = isset($data['image'])?$data['image']:null;
        $this->_enabled = isset($data['enabled'])?$data['enabled']:null;
        $this->_sort_order = isset($data['sort_order'])?$data['sort_order']:null;
        $this->_meta_title = isset($data['meta_title'])?$data['meta_title']:null;
        $this->_meta_description = isset($data['meta_description'])?$data['meta_description']:null;
        $this->_meta_keyword = isset($data['meta_keyword'])?$data['meta_keyword']:null;
        $this->_barcode = isset($data['barcode'])?$data['barcode']:null;
        $this->_parent_category_id = isset($data['parent_category_id'])?$data['parent_category_id']:null;
    }


    public function __construct(array $options = null){
        if(is_array($options)){
            $this->setOptions($options);
        }
    }
    public function __set($name, $value){
        $method = 'set'. $name;
        if(! method_exists($this, $method)){
            throw new \Exception("Invalid Setter");
        }
        $this->$method($value);

    }
    public function __get($name){
        $method = 'get' . $name;
        if( ! method_exists($this, $method)){
            throw new \Exception('Invalid Getter '.$method);
        }
        $this->$method($name);
    }

    public function setOptions(array $options){
        $methods = get_class_methods($this);
        foreach($options as $key => $value){
            $words = explode('_', strtolower($key));

            $return = '';
            foreach ($words as $word) {
                $return .= ucfirst(trim($word));
            }
            $method = 'set'.ucfirst($return);
            if(in_array($method, $methods)){
                $this->$method($value);

            }
        }
        return $this;
    }

    /**
     * @param mixed $barcode
     */
    public function setBarcode($barcode)
    {
        $this->_barcode = $barcode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBarcode()
    {
        return $this->_barcode;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->_description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * @param mixed $enabled
     */
    public function setEnabled($enabled)
    {
        $this->_enabled = $enabled;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEnabled()
    {
        return $this->_enabled;

    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->_id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->_image = $image;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->_image;
    }

    /**
     * @param mixed $meta_description
     */
    public function setMetaDescription($meta_description)
    {
        $this->_meta_description = $meta_description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetaDescription()
    {
        return $this->_meta_description;
    }

    /**
     * @param mixed $meta_keyword
     */
    public function setMetaKeyword($meta_keyword)
    {
        $this->_meta_keyword = $meta_keyword;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetaKeyword()
    {
        return $this->_meta_keyword;
    }

    /**
     * @param mixed $meta_title
     */
    public function setMetaTitle($meta_title)
    {
        $this->_meta_title = $meta_title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetaTitle()
    {
        return $this->_meta_title;
    }

    /**
     * @param mixed $sort_order
     */
    public function setSortOrder($sort_order)
    {
        $this->_sort_order = $sort_order;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSortOrder()
    {
        return $this->_sort_order;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->_title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * @param mixed $parent_category_id
     */
    public function setParentCategoryId($parent_category_id)
    {
        $this->_parent_category_id = $parent_category_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getParentCategoryId()
    {
        return $this->_parent_category_id;
    }
} 