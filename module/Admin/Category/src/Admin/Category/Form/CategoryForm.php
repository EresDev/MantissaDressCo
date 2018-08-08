<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 8/23/14
 * Time: 3:20 PM
 */

namespace Admin\Category\Form;


use Admin\Category\Form\Filter\CategoryFilter;
use Admin\Category\Model\CategoryTable;
use Zend\Di\ServiceLocator;
use Zend\Form\Element\Radio;
use Zend\Form\Form;
use Zend\Form\Element\Textarea;
use Zend\Form\Element\Select;
use Zend\Http\Request;

class CategoryForm extends Form{
    public function __construct($name = null, CategoryTable $categoryTable, $edit_id = 0){
        parent::__construct("categoryform");
        $this->setAttribute('method', 'post');
        $this->add(
            array(
                'name' => 'title',
                'attributes' => array(
                    'type' => 'text',

                ),
                'options' => array(
                    'label' => 'Category Name'
                ),
            )
        );

        $this->add(
            array(
                'name' => 'barcode',
                'attributes' => array(
                    'type' => 'text',

                ),
                'options' => array(
                    'label' => 'Barcode'
                ),
            )
        );

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => "Save",
            )
        ));
        $ob_j = (new Textarea());
        $this->add( $ob_j
                ->setAttributes(
                    array(
                        'name' => 'description',
                        'id' => 'description',

                    )
                )
                ->setOptions(array(
                    'label' => 'Description'
                ))
        );

        //select parent category

        $select_parent_category = new Select();
        $select_parent_category->setName("parent_category_id");
        $select_parent_category->setLabel("Parent Category");
        $value_options = array("0" => "None");
        $categories = $categoryTable->fetchAll2();

        foreach($categories as $category){
            if($edit_id && $edit_id == $category->getId()){
                continue;
            }
            $already_parent_flag = false;

            $name = $category->getTitle();
            $temp = $category;
            while($temp->getParentCategoryId() != 0){
                if((!$already_parent_flag) && $edit_id && $edit_id == $temp->getParentCategoryId()){
                    $already_parent_flag = true;
                    break;
                }
                $temp = $categoryTable->getCategory($temp->getParentCategoryId());

                $name = $temp->getTitle() . " > " . $name;
            }
            if(!$already_parent_flag){
                $value_options[$category->getId()] = $name;
            }


        }
        $select_parent_category->setValueOptions($value_options);
        $this->add($select_parent_category);

        $this->add(
            array(
                'name' => 'meta_title',
                'attributes' => array(
                    'type' => 'text',

                ),
                'options' => array(
                    'label' => 'Meta Title'
                ),
            )
        );

        $this->add((new Textarea())
                ->setAttributes(
                    array(
                        'name' => 'meta_description',
                    )
                )
                ->setOptions(array(
                    'label' => 'Meta Description/Short Description'
                ))
        );

        $this->add(
            array(
                'name' => 'meta_keyword',
                'attributes' => array(
                    'type' => 'text',

                ),
                'options' => array(
                    'label' => 'Meta Keywords'
                ),
            )
        );

        $this->add(
            array(
                'name' => 'sort_order',
                'attributes' => array(
                    'type' => 'text',
                    'value' => 0,
                ),
                'options' => array(
                    'label' => 'Sort Order'
                ),
            )
        );

        $this->add(
            array(
                'name' => 'image',
                'attributes' => array(
                    'type' => 'text',
                    'id' => 'category_image',
                    'class'=>'form-control',
                    'placeholder' => 'Category Image',
                    'readonly' => 'readonly'
                ),
                'options' => array(
                    //'label' => 'Category Image'
                ),
            )
        );

        //category status
        $this->add(array(
            'type'  => 'radio',
            'name'  => 'enabled',

            'options' => array(
                'label' => 'Status',

                'value_options' => array(
                    'enabled' => array(
                        'label' => 'Enabled',
                        'value' => '1',

                    ),
                    'disabled' => array(
                        'label' => 'Disabled',
                        'value' => '0',

                    ),

                ),
            ),
            'attributes' => array(
                'value' => 1
            )
        ));

    }
    public function isValid(){
        $inputFilter = new CategoryFilter();
        $this->setInputFilter($inputFilter->getInputFilter());
        return parent::isValid();
    }
} 